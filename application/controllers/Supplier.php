<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('supplier_model','supplier');
			$this->load->model('users');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['persons'] = $this->supplier->get_persons();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('supplierList',$data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->supplier->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $supplier) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$supplier->id.'">';
			$row[] = $supplier->personId;
			$row[] = $supplier->name;
			$row[] = $supplier->company;
			$row[] = $supplier->vatNo;
			$row[] = $supplier->pinNo;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_supplier('."'".$supplier->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_supplier('."'".$supplier->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->supplier->count_all(),
						"recordsFiltered" => $this->supplier->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->supplier->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				'personId' => $this->input->post('personId'),
				'name' => $this->input->post('name'),
				'company' => $this->input->post('company'),
				'vatNo' => $this->input->post('vatNo'),
				'pinNo' => $this->input->post('pinNo')
			);

		$insert = $this->supplier->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
            'personId' => $this->input->post('personId'),
            'name' => $this->input->post('name'),
            'company' => $this->input->post('company'),
            'vatNo' => $this->input->post('vatNo'),
            'pinNo' => $this->input->post('pinNo')
			);
		$this->supplier->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->supplier->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->supplier->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('personId') == '')
		{
			$data['inputerror'][] = 'personId';
			$data['error_string'][] = 'Supplier Person ID Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] = 'name';
			$data['error_string'][] = 'Person Name is Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('company') == '')
		{
			$data['inputerror'][] = 'company';
			$data['error_string'][] = 'Supplier Company name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('vatNo') == '')
		{
			$data['inputerror'][] = 'vatNo';
			$data['error_string'][] = 'VAT NO Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('pinNo') == '')
		{
			$data['inputerror'][] = 'pinNo';
			$data['error_string'][] = 'PIN NO is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
