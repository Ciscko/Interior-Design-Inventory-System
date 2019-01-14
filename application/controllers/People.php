<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class People extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users');
		$this->load->model('People_model','people');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('peopleList');
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->people->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $people) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$people->personId.'">';
			$row[] = $people->personId;
			$row[] = $people->name;
			$row[] = $people->email;
			$row[] = $people->phone;
			$row[] = $people->gender;
			$row[] = $people->classification;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_people('."'".$people->personId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_people('."'".$people->personId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->people->count_all(),
						"recordsFiltered" => $this->people->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($personId)
	{
		$data = $this->people->get_by_id($personId);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				'personId' => $this->input->post('personId'),
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'gender' => $this->input->post('gender'),
				'classification' => $this->input->post('classification')
			);

		$insert = $this->people->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
                'personId' => $this->input->post('personId'),
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'gender' => $this->input->post('gender'),
				'classification' => $this->input->post('classification')
			);
		$this->people->update(array('personId' => $this->input->post('personId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->people->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('personId');
		foreach ($list_id as $id) {
			$this->people->delete_by_id($id);
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
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] = 'name';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('phone') == '')
		{
			$data['inputerror'][] = 'phone';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('classification') == '')
		{
			$data['inputerror'][] = 'classification';
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
