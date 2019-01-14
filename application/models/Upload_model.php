<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_model extends CI_Model {
	public function __construct(){
		parent:: __construct();
	}
	
	public function index(){
		
	}
	
	//login by email and password 
	
  public function update_info($data){
		$data2 = array();   
		$data2['upload_name'] = trim($data['upload_name']) ;
		$result = $this->db->insert('uploads', $data2); 
		
		if($result){
			return $result;
		}else{
			return false;
		}
	}
public function get_file_by_id($id){
	$this->db->select('upload_name');
	$this->db->from('uploads');
	$this->db->where('id', $id);
	$file = $this->db->get();
	return $file->row();

}

public function delete_file_info($id){
	$this->db->where('id', $id);
	return  $this->db->delete('uploads');
}

  


	public function get_upload_by_id_and_supplier($id, $uploadName){
		$upload = 0;
		$this->db->where(array('id' => $id, 'uploadName' => $uploadName)); 
		$this->db->limit(1);
		$result = $this->db->get("uploads");
		
		if($result){
			foreach($result->result() as $row){
				$upload = $row;
			}
		}else{
			return false;
		}
		
		if($upload){
			return $upload;
		}else{
			return false;
		}
	}
	
	//Count total record of upload 
	public function upload_record_count() {
		return $this->db->count_all("uploads");
	}
	
	//Get all upload
	public function get_all_upload(){
		$allUser = array();
		$this->db->order_by("id", "desc"); 
		$result = $this->db->get('uploads'); 
		
		if($result){
			foreach($result->result() as $row){
				$allUser[] = $row;
			}
		}else{
			return false;
		}
		
		if(count($allUser) > 0){
			return $allUser;
		}else{
			return false;
		}
	}
	
	//Get all signedin  upload
	public function get_all_signed_in_upload(){
		$allUser = array();
		$this->db->order_by("id", "desc"); 
		$this->db->where(array('status' => 1)); 
		$result = $this->db->get('uploads'); 
		
		if($result){
			foreach($result->result() as $row){
				$allUser[] = $row;
			}
		}else{
			return false;
		}
		
		if(count($allUser) > 0){
			return $allUser;
		}else{
			return false;
		}
	}
	
	//get upload list 
	public function get_uploads() {
		
		$this->db->order_by("id", "desc");
		$query = $this->db->get("uploads");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}

			return $data;
		}
		return false;
	}
	
	//insert upload data
	public function insert_upload_data($data){
		$inseringData = array(
		   'id' => trim($data['id']) ,
		   'title' => trim($data['title']) ,
		   'file_name' => trim($data['file_name']),
		   'created' => trim($data['created']),
		   'modified' => trim($data['modified']),
		   'status' => trim($data['status'])
		);

		$result = $this->db->insert('uploads', $inseringData); 
		
		if($result){
			return $result;
		}else{
			return false;
		}
	}
	
	//Update upload data
	public function edit_upload_by_id($data, $id){
		$updatingData = array(
		   'id' => trim($data['id']) ,
		   'title' => trim($data['title']) ,
		   'file_name' => trim($data['file_name']),
		   'created' => trim($data['created']),
		   'modified' => trim($data['modified']),
		   'status' => trim($data['status'])
		);
		
		//if($data['upload_data']['file_name']){
		//	$updatingData['profile_picture'] = trim($data['upload_data']['file_name']);
		//}

		$result = $this->db->update('uploads', $updatingData, array('id' => $id)); 
		
		if($result){
			return $result;
		}else{
			return false;
		}
	}
	
	//Delete User by id 
	public function delete_upload_by_id($id){
		$result = $this->db->delete('uploads', array('id' => $id));
		if($result){
			return true;
		}else{
			return false;
		}
	}
	//Delete multiple upload 
	public function delete_uploads($uploads){
		foreach($uploads as $id){
			$result = $this->db->delete('uploads', array('id'=>$id));
		}
		
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	//Get User by id
	public function get_upload_by_id($id){
		$upload = 0;
		$result = $this->db->get_where('uploads', array('id' => $id), 1); 
		
		if($result)
		{
			foreach($result->result() as $row){
				$upload = $row;
			}
		}else{
			return false;
		}
		
		if($upload){
			return $upload;
		}else{
			return false;
		}
	}
	
	//Get multiple upload  by id
	public function get_multiple_upload_by_id($ids){
		$upload = array();
		foreach($ids as $id){
			$result = $this->db->get_where('uploads', array('id' => $id)); 
		
			if($result){
				foreach($result->result() as $row){
					$upload[] = $row;
				}
			}else{
				return false;
			}
		}
		
		if(count($upload) > 0){
			return $upload;
		}else{
			return false;
		}
	}
	
	//Search upload by keyword 
	public function search_upload_by_keyword($keyword){
		//$this->db->select('*');
		//$this->db->from('uploads');
		$this->db->like('file_name', $keyword);
		$this->db->or_like('title', $keyword);
		//$this->db->or_like('contact_number', $keyword);
		$query = $this->db->get('uploads');
		if($query){
			return $query;
		}else{
			return false;
		}
	}
	
	//Search upload by keyword with pagination
	public function search_upload_by_keyword_with_pagination($keyword, $limit, $start){
		$this->db->like('file_name', $keyword);
		$this->db->or_like('title', $keyword);
		//$this->db->or_like('contact_number', $keyword);
		$query = $this->db->get('uploads', $limit, $start-1 );
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}else{
			return false;
		}
	}
	
	//Change upload status by id 
	public function change_upload_status_by_id($id, $status){
		$updatingData = array(
			'status'=> $status
		);
		$result = $this->db->update('uploads', $updatingData, array('id' => $id)); 
		
		if($result){
			return 1;
		}else{
			return 0;
		}
	}
	
	//Change upload location by id 
	public function change_upload_location_by_id($id, $lat, $lng){
		$updatingData = array(
			'latitude'=> $lat,
			'longitude'=> $lng
		);
		$result = $this->db->update('uploads', $updatingData, array('id' => $id)); 
		
		if($result){
			return 1;
		}else{
			return 0;
		}
	}
	
	//Register User Device by id 
	public function register_upload_device($uploadId, $deviceRegId){
		$updatingData = array(
			'device_reg_id'=> $deviceRegId
		);
		$result = $this->db->update('uploads', $updatingData, array('id' => $uploadId)); 
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	//Set upload status by id 
	public function set_upload_status_by_id($uploadId, $status){
		$updatingData = array(
			'status'=> $status
		);
		
		$result = $this->db->update('uploads', $updatingData, array('id' => $uploadId)); 
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
}