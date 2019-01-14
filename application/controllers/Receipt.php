<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Receipt_model','receipt');
		$this->load->model('users');
		$this->load->library('excel');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$data['suppliers'] = $this->receipt->get_suppliers();
		$data['invoices'] = $this->receipt->get_invoices();

		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('receipt', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->receipt->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $receipt) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$receipt->id.'">';
			$row[] = $receipt->id;
			$row[] = $receipt->receiptNo;
			$row[] = $receipt->date;
			$row[] = $receipt->supplier;
			$row[] = $receipt->item;
			$row[] = $receipt->gross;
			$row[] = $receipt->tax;
			$row[] = $receipt->net;
			$row[] = $receipt->invoiceNo;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_receipt('."'".$receipt->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_receipt('."'".$receipt->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->receipt->count_all(),
						"recordsFiltered" => $this->receipt->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->receipt->get_by_id($id);
		// if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'receiptNo' => $this->input->post('receiptNo'),
				'date' => $this->input->post('date'),
				'supplier' => $this->input->post('supplier'),
				'item' => $this->input->post('item'),
				'gross' => $this->input->post('gross'),
				'tax' => $this->input->post('tax'),
				'net' =>$this->input->post('gross')-(($this->input->post('tax')/100)*$this->input->post('gross')),
				'invoiceNo' => $this->input->post('invoiceNo')
			);

		$insert = $this->receipt->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id' => $this->input->post('id'),
				'receiptNo' => $this->input->post('receiptNo'),
				'date' => $this->input->post('date'),
				'supplier' => $this->input->post('supplier'),
				'item' => $this->input->post('item'),
				'gross' => $this->input->post('gross'),
				'tax' => $this->input->post('tax'),
				'net' => $this->input->post('gross')-(($this->input->post('tax')/100)*$this->input->post('gross')),
				'invoiceNo' => $this->input->post('invoiceNo')
			);
		$this->receipt->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->receipt->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->receipt->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}


	// import excel data
    public function save() {
       //$this->load->library('excel');
        
        if ($this->input->post('importfile')) {
            $path = './uploads/receipts/';
 
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
            $createArray = array('receiptNo', 'date', 'item', 'supplier','date', 'gross','tax','net','invoiceNo');
            $makeArray = array('receiptNo' => 'receiptNo', 'date' => 'date', 'item' => 'item', 'supplier' => 'supplier', 'date' =>'date', 'gross'=>'gross','tax'=>'tax','net' => 'net', 'invoiceNo'=>'invoiceNo');
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
                    $receiptNo = $SheetDataKey['receiptNo'];
                    $invoiceNo = $SheetDataKey['invoiceNo'];
                    $gross = $SheetDataKey['gross'];
                    $tax = $SheetDataKey['tax'];
                    $net = $SheetDataKey['net'];
                    $date = $SheetDataKey['date'];
                     $item = $SheetDataKey['item'];
                    $supplier = $SheetDataKey['supplier'];
                    //$id = filter_var($allDataInSheet[$i][$id]), FILTER_SANITIZE_STRING);
                    $invoiceNo = filter_var($allDataInSheet[$i][$invoiceNo], FILTER_SANITIZE_STRING);
                     $receiptNo = filter_var($allDataInSheet[$i][$receiptNo], FILTER_SANITIZE_STRING);
                    $gross = filter_var($allDataInSheet[$i][$gross], FILTER_SANITIZE_STRING);
                    $tax = filter_var($allDataInSheet[$i][$tax], FILTER_SANITIZE_EMAIL);
                    $net = filter_var($allDataInSheet[$i][$net], FILTER_SANITIZE_STRING);
                    $date = filter_var($allDataInSheet[$i][$date], FILTER_SANITIZE_STRING);
                    $item = filter_var($allDataInSheet[$i][$item], FILTER_SANITIZE_STRING);
                    $supplier = filter_var($allDataInSheet[$i][$supplier], FILTER_SANITIZE_STRING);
                    $fetchData[] = array('receiptNo' => $receiptNo,'invoiceNo' => $invoiceNo, 'gross' => $gross, 'tax' => $tax, 'net' => $net, 'date'=> $date,  'item'=> $item,'supplier' => $supplier);
                }              
                //$data['cashbooks'] = $fetchData;
                $this->receipt->setBatchImport($fetchData);
                $this->receipt->importData();
            } else {
                echo "Please import correct file";
            }
        }
        unlink('uploads/inventories/'.$import_xls_file);
       redirect('receipt');
        
    }



	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('receiptNo') == '')
		{
			$data['inputerror'][] = 'receiptNo';
			$data['error_string'][] = 'Receipt No required';
			$data['status'] = FALSE;
		}

		if($this->input->post('date') == '')
		{
			$data['inputerror'][] = 'date';
			$data['error_string'][] = 'Date is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('supplier') == '')
		{
			$data['inputerror'][] = 'supplier';
			$data['error_string'][] = 'Supplier is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('item') == '')
		{
			$data['inputerror'][] = 'item';
			$data['error_string'][] = 'Product Required';
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

		if($this->input->post('invoiceNo') == '')
		{
			$data['inputerror'][] = 'invoiceNo';
			$data['error_string'][] = 'Invoice No is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
