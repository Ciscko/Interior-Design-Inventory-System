<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users');
		$this->load->model('Leave_model','leave');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['persons'] = $this->leave->get_persons();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('leaveList', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->leave->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $leave) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$leave->leaveNo.'">';
			$row[] = $leave->leaveNo;
			$row[] = $leave->leaved;
			$row[] = $leave->returnd;
			$row[] = $leave->personId;
			$row[] = $leave->reason;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_leave('."'".$leave->leaveNo."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_leave('."'".$leave->leaveNo."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->leave->count_all(),
						"recordsFiltered" => $this->leave->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($leaveNo)
	{
		$data = $this->leave->get_by_id($leaveNo);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				
				'leaved' => $this->input->post('leaved'),
				'returnd' => $this->input->post('returnd'),
				'personId' => $this->input->post('personId'),
				'reason' => $this->input->post('reason')
				
			);

		$insert = $this->leave->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'leaveNo' => $this->input->post('leaveNo'),
				'leaved' => $this->input->post('leaved'),
				'returnd' => $this->input->post('returnd'),
				'personId' => $this->input->post('personId'),
				'reason' => $this->input->post('reason')

			);
		$this->leave->update(array('leaveNo' => $this->input->post('leaveNo')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->leave->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('leaveNo');
		foreach ($list_id as $id) {
			$this->leave->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if($this->input->post('leaved') == '')
		{
			$data['inputerror'][] = 'leaved';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('returnd') == '')
		{
			$data['inputerror'][] = 'returnd';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('personId') == '')
		{
			$data['inputerror'][] = 'personId';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('reason') == '')
		{
			$data['inputerror'][] = 'reason';
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
