<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Worker extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('worker_model','worker');
		$this->load->model('users');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['persons'] = $this->worker->get_persons();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('workerList',$data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->worker->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $worker) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$worker->people_personId.'">';
			$row[] = $worker->people_personId;
			$row[] = $worker->name;
			$row[] = $worker->role;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_worker('."'".$worker->people_personId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_worker('."'".$worker->people_personId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->worker->count_all(),
						"recordsFiltered" => $this->worker->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->worker->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				'people_personId' => $this->input->post('people_personId'),
				'name' => $this->input->post('name'),
				'role' => $this->input->post('role')
				);

		$insert = $this->worker->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
                'people_personId' => $this->input->post('people_personId'),
				'name' => $this->input->post('name'),
				'role' => $this->input->post('role')
				
			);
		$this->worker->update(array('people_personId' => $this->input->post('people_personId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->worker->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('people_personId');
		foreach ($list_id as $id) {
			$this->worker->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('people_personId') == '')
		{
			$data['inputerror'][] = 'people_personId';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] = 'name';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('role') == '')
		{
			$data['inputerror'][] = 'role';
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
