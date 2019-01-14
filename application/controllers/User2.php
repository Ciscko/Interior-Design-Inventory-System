<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User2 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
			$this->load->model('users');
		$this->load->model('user_model','user');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['persons'] = $this->user->get_persons();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		
		$this->load->view('userList',$data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->user->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$user->people_personId.'">';
			$row[] = $user->people_personId;
			$row[] = $user->userName;
			$row[] = $user->email;
			$row[] = $user->password;
			$row[] = $user->level;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_user('."'".$user->people_personId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_user('."'".$user->people_personId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->user->count_all(),
						"recordsFiltered" => $this->user->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->user->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				'people_personId' => $this->input->post('people_personId'),
				'userName' => $this->input->post('userName'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password')),
				'level' => $this->input->post('level')
			);

		$insert = $this->user->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
                'people_personId' => $this->input->post('people_personId'),
				'userName' => $this->input->post('userName'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password')),
				'level' => $this->input->post('level')
			);
		$this->user->update(array('people_personId' => $this->input->post('people_personId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->user->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('people_personId');
		foreach ($list_id as $id) {
			$this->user->delete_by_id($id);
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

		if($this->input->post('userName') == '')
		{
			$data['inputerror'][] = 'userName';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('password') == '')
		{
			$data['inputerror'][] = 'password';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('level') == '')
		{
			$data['inputerror'][] = 'level';
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
