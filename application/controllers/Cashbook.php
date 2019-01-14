<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashbook extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Cashbook_model','cashbook');
		$this->load->model('users');
		$this->load->library('excel');
	}

	public function index()
	{
		$data = array();
        if($this->session->userdata('isUserLoggedIn')){
            $data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
            //load the view
			$this->load->helper('url');
			$this->load->view('header', $data);
			$this->load->view('sidebar',$data);
			$this->load->view('cashbook');
        }else{
            redirect('user/login');
        }
		
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->cashbook->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $cashbook) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$cashbook->id.'">';
			$row[] = $cashbook->id;
			$row[] = $cashbook->date;
			$row[] = $cashbook->type;
			$row[] = $cashbook->particulars;
			$row[] = $cashbook->amount;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_cashbook('."'".$cashbook->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_cashbook('."'".$cashbook->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->cashbook->count_all(),
						"recordsFiltered" => $this->cashbook->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->cashbook->get_by_id($id);
		// if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'date' => $this->input->post('date'),
				'particulars' => $this->input->post('particulars'),
				'type' => $this->input->post('type'),
				'amount' => $this->input->post('amount')	
			);

		$insert = $this->cashbook->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id' => $this->input->post('id'),
				'date' => $this->input->post('date'),
				'particulars' => $this->input->post('particulars'),
				'type' => $this->input->post('type'),
				'amount' => $this->input->post('amount')
			);
		$this->cashbook->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->cashbook->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->cashbook->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}


	// import excel data
    public function save() {
       //$this->load->library('excel');
        
        if ($this->input->post('importfile')) {
            $path = './uploads/cashbooks/';
 
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
            $createArray = array('date', 'type', 'particulars', 'amount');
            $makeArray = array('date' => 'date', 'type' => 'type', 'particulars' => 'particulars', 'amount' => 'amount');
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
                    $date = $SheetDataKey['date'];
                    $type = $SheetDataKey['type'];
                    $particulars = $SheetDataKey['particulars'];
                    $amount = $SheetDataKey['amount'];
                   
                    //$id = filter_var($allDataInSheet[$i][$id]), FILTER_SANITIZE_STRING);
                    $date = filter_var($allDataInSheet[$i][$date], FILTER_SANITIZE_STRING);
                    $type = filter_var($allDataInSheet[$i][$type], FILTER_SANITIZE_STRING);
                    $particulars = filter_var($allDataInSheet[$i][$particulars], FILTER_SANITIZE_EMAIL);
                    $amount = filter_var($allDataInSheet[$i][$amount], FILTER_SANITIZE_STRING);
                    
                    $fetchData[] = array('date' => $date, 'type' => $type, 'particulars' => $particulars, 'amount' => $amount);
                }              
                //$data['cashbooks'] = $fetchData;
                $this->cashbook->setBatchImport($fetchData);
                $this->cashbook->importData();
            } else {
                echo "Please import correct file";
            }
        }
        unlink('uploads/inventories/'.$import_xls_file);
       redirect('cashbook');
        
    }


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('date') == '')
		{
			$data['inputerror'][] = 'date';
			$data['error_string'][] = 'Date Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('particulars') == '')
		{
			$data['inputerror'][] = 'particulars';
			$data['error_string'][] = 'Particulars Required';
			$data['status'] = FALSE;
		}
		if($this->input->post('type') == '')
		{
			$data['inputerror'][] = 'type';
			$data['error_string'][] = 'Category Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('amount') == '')
		{
			$data['inputerror'][] = 'amount';
			$data['error_string'][] = ' Amount is required';
			$data['status'] = FALSE;
		}

		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


}