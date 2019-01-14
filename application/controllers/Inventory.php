<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Inventory_model','inventory');
		$this->load->model('users');
		$this->load->library('excel');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('inventory');
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->inventory->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $inventory) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$inventory->id.'">';
			$row[] = $inventory->id;
			$row[] = $inventory->detail;
			$row[] = $inventory->qtypurchased;
			$row[] = $inventory->pricePer;
			$row[] = $inventory->qtyStock;
			$row[] = $inventory->stockWorth;
			$row[] = $inventory->reorderL;
			$row[] = $inventory->reorderQty;
			$row[] = $inventory->qtySold;
			$row[] = $inventory->discoPrdct;
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_inventory('."'".$inventory->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_inventory('."'".$inventory->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->inventory->count_all(),
						"recordsFiltered" => $this->inventory->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->inventory->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				
			'detail' => $this->input->post('detail'),
			'qtypurchased' => $this->input->post('qtypurchased'),
			'pricePer' => $this->input->post('pricePer'),
			'qtyStock' => $this->input->post('qtyStock'),
			'stockWorth' => $this->input->post('stockWorth'),
			'reorderL' => $this->input->post('reorderL'),
			'reorderQty' => $this->input->post('reorderQty'),
			'qtySold' => $this->input->post('qtySold'),
			'discoPrdct' => $this->input->post('discoPrdct')
			);

		$insert = $this->inventory->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
					'id' => $this->input->post('id'),
					'detail' => $this->input->post('detail'),
					'qtypurchased' => $this->input->post('qtypurchased'),
					'pricePer' => $this->input->post('pricePer'),
					'qtyStock' => $this->input->post('qtyStock'),
					'stockWorth' => $this->input->post('stockWorth'),
					'reorderL' => $this->input->post('reorderL'),
					'reorderQty' => $this->input->post('reorderQty'),
					'qtySold' => $this->input->post('qtySold'),
					'discoPrdct' => $this->input->post('discoPrdct')
			);
		$this->inventory->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->inventory->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->inventory->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}

	// import excel data
    public function save() {
       //$this->load->library('excel');
        
        if ($this->input->post('importfile')) {
            $path = './uploads/inventories/';
 
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
            $createArray = array('detail', 'qtypurchased', 'pricePer', 'qtyStock','qtypurchased', 'stockWorth','reorderL','reorderQty','qtySold','discoPrdct');
            $makeArray = array('detail' => 'detail', 'qtypurchased' => 'qtypurchased', 'pricePer' => 'pricePer', 'qtyStock' => 'qtyStock', 'qtypurchased' =>'qtypurchased', 'stockWorth'=>'stockWorth','reorderL'=>'reorderL','reorderQty' => 'reorderQty', 'qtySold'=>'qtySold','discoPrdct' =>'discoPrdct');
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
                    $detail = $SheetDataKey['detail'];
                    $qtySold = $SheetDataKey['qtySold'];
                    $stockWorth = $SheetDataKey['stockWorth'];
                    $reorderL = $SheetDataKey['reorderL'];
                    $reorderQty = $SheetDataKey['reorderQty'];
                    $qtypurchased = $SheetDataKey['qtypurchased'];
                     $pricePer = $SheetDataKey['pricePer'];
                    $qtyStock = $SheetDataKey['qtyStock'];
                    $discoPrdct = $SheetDataKey['discoPrdct'];
                    //$id = filter_var($allDataInSheet[$i][$id]), FILTER_SANITIZE_STRING);
                    $qtySold = filter_var($allDataInSheet[$i][$qtySold], FILTER_SANITIZE_STRING);
                     $detail = filter_var($allDataInSheet[$i][$detail], FILTER_SANITIZE_STRING);
                    $stockWorth = filter_var($allDataInSheet[$i][$stockWorth], FILTER_SANITIZE_STRING);
                    $reorderL = filter_var($allDataInSheet[$i][$reorderL], FILTER_SANITIZE_EMAIL);
                    $reorderQty = filter_var($allDataInSheet[$i][$reorderQty], FILTER_SANITIZE_STRING);
                    $qtypurchased = filter_var($allDataInSheet[$i][$qtypurchased], FILTER_SANITIZE_STRING);
                    $pricePer = filter_var($allDataInSheet[$i][$pricePer], FILTER_SANITIZE_STRING);
                    $qtyStock = filter_var($allDataInSheet[$i][$qtyStock], FILTER_SANITIZE_STRING);
                     $discoPrdct = filter_var($allDataInSheet[$i][$discoPrdct], FILTER_SANITIZE_STRING);
                    $fetchData[] = array('detail' => $detail,'qtySold' => $qtySold, 'stockWorth' => $stockWorth, 'reorderL' => $reorderL, 'reorderQty' => $reorderQty, 'qtypurchased'=> $qtypurchased,  'pricePer'=> $pricePer,'qtyStock' => $qtyStock, 'discoPrdct' => $discoPrdct);
                }              
                //$data['cashbooks'] = $fetchData;
                $this->inventory->setBatchImport($fetchData);
                $this->inventory->importData();
            } else {
                echo "Please import correct file";
            }
        }
         unlink('uploads/inventories/'.$import_xls_file);
       redirect('inventory');
        
    }

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if($this->input->post('detail') == '')
		{
			$data['inputerror'][] = 'detail';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		

		if($this->input->post('qtypurchased') == '')
		{
			$data['inputerror'][] = 'qtypurchased';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('pricePer') == '')
		{
			$data['inputerror'][] = 'pricePer';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}


		if($this->input->post('stockWorth') == '')
		{
			$data['inputerror'][] = 'stockWorth';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('reorderL') == '')
		{
			$data['inputerror'][] = 'reorderL';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('reorderQty') == '')
		{
			$data['inputerror'][] = 'reorderQty';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('qtySold') == '')
		{
			$data['inputerror'][] = 'qtySold';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('discoPrdct') == '')
		{
			$data['inputerror'][] = 'discoPrdct';
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
