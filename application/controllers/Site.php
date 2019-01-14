<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users');
		$this->load->model('Site_model','site');
	}

	public function index()
	{
		$this->load->helper('url');
		//avail all data necessary here
		$data['invoices'] = $this->site->get_invoices();
		$data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->view('header',$data);
		$this->load->view('sidebar',$data);
		$this->load->view('siteList', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->site->get_datatables();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $site) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$site->id.'">';
			$row[] = $site->id;
			$row[] = $site->siteName;
			$row[] = $site->start;
			$row[] = $site->end;
			$row[] = $site->invoiceNo;
			$row[] = $site->amt;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_site('."'".$site->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_site('."'".$site->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->site->count_all(),
						"recordsFiltered" => $this->site->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->site->get_by_id($id);
		 
		echo json_encode($data);
	}

	
	public function ajax_add()
	{
		
		$this->_validate();
		
		$data = array(
				
				'siteName' => $this->input->post('siteName'),
				'start' => $this->input->post('start'),
				'end' => $this->input->post('end'),
				'invoiceNo' => $this->input->post('invoiceNo'),
				'amt' => $this->input->post('amt')
			);

		$insert = $this->site->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id' => $this->input->post('id'),
				'siteName' => $this->input->post('siteName'),
				'start' => $this->input->post('start'),
				'end' => $this->input->post('end'),
				'invoiceNo' => $this->input->post('invoiceNo'),
				'amt' => $this->input->post('amt')

			);
		$this->site->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->site->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->site->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if($this->input->post('siteName') == '')
		{
			$data['inputerror'][] = 'siteName';
			$data['error_string'][] = ' Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('start') == '')
		{
			$data['inputerror'][] = 'start';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('end') == '')
		{
			$data['inputerror'][] = 'end';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}

		if($this->input->post('invoiceNo') == '')
		{
			$data['inputerror'][] = 'invoiceNo';
			$data['error_string'][] = 'Required';
			$data['status'] = FALSE;
		}


		if($this->input->post('amt') == '')
		{
			$data['inputerror'][] = 'amt';
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
