<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contractor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contractor_model','contractor');
		$this->load->model('users');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$data['persons'] = $this->contractor->get_persons();
		$data['sites'] = $this->contractor->get_sites();
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('contractorList',$data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->contractor->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $contractor) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$contractor->personId.'">';
			$row[] = $contractor->personId;
			$row[] = $contractor->cName;
			$row[] = $contractor->moneyAgreed;
			$row[] = $contractor->moneyPaid;
			$row[] = $contractor->balance;
			$row[] = $contractor->siteName;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_contractor('."'".$contractor->personId."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_contractor('."'".$contractor->personId."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->contractor->count_all(),
						"recordsFiltered" => $this->contractor->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->contractor->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				'personId' => $this->input->post('personId'),
				'cName' => $this->input->post('cName'),
				'moneyAgreed' => $this->input->post('moneyAgreed'),
				'moneyPaid' => $this->input->post('moneyPaid'),
				'balance' => ($this->input->post('moneyAgreed'))-($this->input->post('moneyPaid')),
				'siteName' => $this->input->post('siteName')
			);

		$insert = $this->contractor->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
                'personId' => $this->input->post('personId'),
				'cName' => $this->input->post('cName'),
				'moneyAgreed' => $this->input->post('moneyAgreed'),
				'moneyPaid' => $this->input->post('moneyPaid'),
				'balance' => ($this->input->post('moneyAgreed'))-($this->input->post('moneyPaid')),
				'siteName' => $this->input->post('siteName')
			);
		$this->contractor->update(array('personId' => $this->input->post('personId')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->contractor->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->contractor->delete_by_id($id);
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
			$data['error_string'][] = 'ID Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('cName') == '')
		{
			$data['inputerror'][] = 'cName';
			$data['error_string'][] = 'Name Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('moneyAgreed') == '')
		{
			$data['inputerror'][] = 'moneyAgreed';
			$data['error_string'][] = 'Money Agreed is Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('moneyPaid') == '')
		{
			$data['inputerror'][] = 'moneyPaid';
			$data['error_string'][] = 'Money Paid is Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('siteName') == '')
		{
			$data['inputerror'][] = 'siteName';
			$data['error_string'][] = 'Site is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
