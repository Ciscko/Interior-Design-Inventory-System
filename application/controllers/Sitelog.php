<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitelog extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Sitelog_model','sitelog');
		$this->load->model('users');
		$this->load->library('excel');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['sites'] = $this->sitelog->get_sites();
		$data['suppliers'] = $this->sitelog->get_suppliers();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('sitelogs', $data);
		
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->sitelog->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $sitelog) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$sitelog->id.'">';
			$row[] = $sitelog->id;
			$row[] = $sitelog->site;
			$row[] = $sitelog->item;
			$row[] = $sitelog->qty;
			$row[] = $sitelog->perCost;
			$row[] = $sitelog->total;
			$row[] = $sitelog->supplier;
			$row[] = $sitelog->purpose;
			$row[] = $sitelog->date;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_sitelog('."'".$sitelog->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_sitelog('."'".$sitelog->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->sitelog->count_all(),
						"recordsFiltered" => $this->sitelog->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->sitelog->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				
				'site' => $this->input->post('site'),
				'item' => $this->input->post('item'),
				'qty' => $this->input->post('qty'),
				'perCost' => $this->input->post('perCost'),
				'total' => ($this->input->post('qty')* $this->input->post('perCost')),
				'supplier' => $this->input->post('supplier'),
				'purpose' => $this->input->post('purpose'),
				'date' => $this->input->post('date')
			);

		$insert = $this->sitelog->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id' => $this->input->post('id'),
				'site' => $this->input->post('site'),
				'item' => $this->input->post('item'),
				'qty' => $this->input->post('qty'),
				'perCost' => $this->input->post('perCost'),
				'total' => ($this->input->post('qty')* $this->input->post('perCost')),
				'supplier' => $this->input->post('supplier'),
				'purpose' => $this->input->post('purpose'),
				'date' => $this->input->post('date')
			);
		$this->sitelog->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->sitelog->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->sitelog->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}


	// import excel data
    public function save() {
       //$this->load->library('excel');
        
        if ($this->input->post('importfile')) {
            $path = './uploads/logs/';
 
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
            $createArray = array('site', 'item', 'perCost', 'total', 'date','supplier','purpose','qty');
            $makeArray = array('site' => 'site', 'item' => 'item', 'perCost' => 'perCost', 'total' => 'total',  'date'=>'date','supplier'=>'supplier','purpose' => 'purpose','qty'=>'qty');
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
                    $site = $SheetDataKey['site'];
                    $date = $SheetDataKey['date'];
                    $supplier = $SheetDataKey['supplier'];
                    $purpose = $SheetDataKey['purpose'];
                    $item = $SheetDataKey['item'];
                    $perCost = $SheetDataKey['perCost'];
                    $total = $SheetDataKey['total'];
                    $qty = $SheetDataKey['qty'];

                    $site = filter_var($allDataInSheet[$i][$site], FILTER_SANITIZE_STRING);
                    $date = filter_var($allDataInSheet[$i][$date], FILTER_SANITIZE_STRING);
                    $supplier = filter_var($allDataInSheet[$i][$supplier], FILTER_SANITIZE_EMAIL);
                    $purpose = filter_var($allDataInSheet[$i][$purpose], FILTER_SANITIZE_STRING);
                    $item = filter_var($allDataInSheet[$i][$item], FILTER_SANITIZE_STRING);
                    $perCost = filter_var($allDataInSheet[$i][$perCost], FILTER_SANITIZE_STRING);
                    $total = filter_var($allDataInSheet[$i][$total], FILTER_SANITIZE_STRING);
                     $qty = filter_var($allDataInSheet[$i][$qty], FILTER_SANITIZE_STRING);
                    $fetchData[] = array('site' => $site, 'date' => $date, 'supplier' => $supplier, 'purpose' => $purpose, 'item'=> $item,  'perCost'=> $perCost,'total' => $total, 'qty' => $qty);
                }              
                //$data['cashbooks'] = $fetchData;
                $this->sitelog->setBatchImport($fetchData);
                $this->sitelog->importData();
            } else {
                echo "Please import correct file";
            }
        }
        unlink('uploads/inventories/'.$import_xls_file);
       redirect('sitelog');
        
    }

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if($this->input->post('site') == '')
		{
			$data['inputerror'][] = 'site';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('item') == '')
		{
			$data['inputerror'][] = 'item';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('qty') == '')
		{
			$data['inputerror'][] = 'qty';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('perCost') == '')
		{
			$data['inputerror'][] = 'perCost';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}


		if($this->input->post('supplier') == '')
		{
			$data['inputerror'][] = 'supplier';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('purpose') == '')
		{
			$data['inputerror'][] = 'purpose';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('date') == '')
		{
			$data['inputerror'][] = 'date';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
