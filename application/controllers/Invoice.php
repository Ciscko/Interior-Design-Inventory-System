<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Invoice_model','invoice');
		$this->load->model('users');
		$this->load->library('excel');
		$this->load->library('pdf');
		 $this->load->helper('download');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['sites'] = $this->invoice->get_sites();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('invoice', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->invoice->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $invoice) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$invoice->id.'">';
			$row[] = $invoice->id;
			$row[] = $invoice->invoiceNo;
			$row[] = $invoice->date;
			$row[] = $invoice->gross;
			$row[] = $invoice->tax;
			$row[] = $invoice->net;
			$row[] = $invoice->siteName;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_invoice('."'".$invoice->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="PDF" onclick="pdf_invoice('."'".$invoice->id."'".')"><i class="glyphicon glyphicon-pencil"></i> PDF</a>

				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_invoice('."'".$invoice->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->invoice->count_all(),
						"recordsFiltered" => $this->invoice->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->invoice->get_by_id($id);
		// if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'invoiceNo' => $this->input->post('invoiceNo'),
				'gross' => $this->input->post('gross'),
				'tax' => $this->input->post('tax'),
				'net' =>$this->input->post('gross')-(($this->input->post('tax')/100)*($this->input->post('gross'))),
				'date' => $this->input->post('date'),
				'siteName' => $this->input->post('siteName')
			);

		$insert = $this->invoice->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id' => $this->input->post('id'),
				'invoiceNo' => $this->input->post('invoiceNo'),
				'gross' => $this->input->post('gross'),
				'tax' => $this->input->post('tax'),
				'net' =>$this->input->post('gross')-(($this->input->post('tax')/100)*($this->input->post('gross'))),
				'date' => $this->input->post('date'),
				'siteName' => $this->input->post('siteName')
			);
		$this->invoice->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->invoice->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	 public function pdf() {
        $data['id'] = $this->input->post('id');
        $data['pdf_data'] = $this->invoice->get_by_id($data['id']);

        $this->load->library('pdf');
        $this->pdf->load_view('invoice_pdf', $data);
        $this->pdf->setPaper('A6', 'landscape');
        $this->pdf->render();
        $this->pdf->stream(date('H:i A d-M-Y').'--invoice.pdf');

        //echo json_encode(array("status" => TRUE));
    }

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->invoice->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}


	// import excel data
    public function save() {
       //$this->load->library('excel');
        
        if ($this->input->post('importfile')) {
            $path = './uploads/invoices/';
 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls|jpg|png';
            $config['remove_spaces'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
            
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $path . $import_xls_file;
            try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true,true);
            
            $arrayCount = count($allDataInSheet);
            $flag = 0;
            $createArray = array('invoiceNo', 'gross', 'tax', 'net','date', 'siteName');
            $makeArray = array('invoiceNo' => 'invoiceNo', 'gross' => 'gross', 'tax' => 'tax', 'net' => 'net', 'date' =>'date', 'siteName'=>'siteName');
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array($value, $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[$value] = $key;
                    } else {
                        
                    }
                }
            }
            $data = array_diff_key($makeArray, $SheetDataKey);
           
            if (empty($data)) {
                $flag = 1;
            }
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {
                    $addresses = array();
                     //$id = $SheetDataKey['id'];
                    $invoiceNo = $SheetDataKey['invoiceNo'];
                    $gross = $SheetDataKey['gross'];
                    $tax = $SheetDataKey['tax'];
                    $net = $SheetDataKey['net'];
                    $date = $SheetDataKey['date'];
                    $siteName = $SheetDataKey['siteName'];
                    //$id = filter_var($allDataInSheet[$i][$id]), FILTER_SANITIZE_STRING);
                    $invoiceNo = filter_var($allDataInSheet[$i][$invoiceNo], FILTER_SANITIZE_STRING);
                    $gross = filter_var($allDataInSheet[$i][$gross], FILTER_SANITIZE_STRING);
                    $tax = filter_var($allDataInSheet[$i][$tax], FILTER_SANITIZE_EMAIL);
                    $net = filter_var($allDataInSheet[$i][$net], FILTER_SANITIZE_STRING);
                    $date = filter_var($allDataInSheet[$i][$date], FILTER_SANITIZE_STRING);
                    $siteName = filter_var($allDataInSheet[$i][$siteName], FILTER_SANITIZE_STRING);
                    $fetchData[] = array('invoiceNo' => $invoiceNo, 'gross' => $gross, 'tax' => $tax, 'net' => $net, 'date'=> $date, 'siteName' => $siteName);
                }              
                //$data['cashbooks'] = $fetchData;
                $this->invoice->setBatchImport($fetchData);
                $this->invoice->importData();
            } else {
                echo "Please import correct file";
            }
        }
        unlink('uploads/inventories/'.$import_xls_file);
       redirect('invoice');
        
    }


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('invoiceNo') == '')
		{
			$data['inputerror'][] = 'invoiceNo';
			$data['error_string'][] = 'Invoice No required';
			$data['status'] = FALSE;
		}

		if($this->input->post('date') == '')
		{
			$data['inputerror'][] = 'date';
			$data['error_string'][] = 'Date is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('gross') == '')
		{
			$data['inputerror'][] = 'gross';
			$data['error_string'][] = 'Gross Amount is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('tax') == '')
		{
			$data['inputerror'][] = 'tax';
			$data['error_string'][] = 'TAX is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('siteName') == '')
		{
			$data['inputerror'][] = 'siteName';
			$data['error_string'][] = 'Site Name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}




    


}
