<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Files management class created by CodexWorld
 */
class Files extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('users');
        $this->load->model('file');
          $this->load->helper(array('form', 'url'));
        $this->load->model('upload_model');
    }
    
    public function index(){
        $data = array();
        $data['files'] = $this->file->getRows();
        $data['title'] = "Download";
        //load the view
        $data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
       $this->load->view('header',$data);
       $this->load->view('sidebar',$data);
        $this->load->view('files/index', $data);
        

    }
    
    public function downloadz($id){
        //$this->load->model('file');
        if(!empty($id)){
            //load download helper
            $this->load->helper('download');
            //$fileInfo=array();
            //get file info from database
            $fileInfo = $this->file->getRows(array('id' => $id));
            
            //file path
            $file = 'uploads/'.$fileInfo['upload_name'];
            //$name = $fileInfo['upload_name']."zip";
            //download file from directory
            force_download($file, NULL);

        }
        else{ echo "error";}
    }

    public function upload()
    { 
        $params = array();
        if (!empty($_FILES['zip_file']['name'])){

        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'zip|gif|png|jpg|pdf';
        $config['max_size']             = 2000000000;
        $this->load->library('upload', $config);

         $this->upload->initialize($config);

            if (!$this->upload->do_upload('zip_file')) {
               $params = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('message', $params['error']);
                redirect('files');
            }
            else{
                $result = array();
                $post_image1 = $this->upload->data();
                $data['upload_name'] = $post_image1['file_name'];
                $data['names'] = $this->file->getRows();
               
                 $data['files'] = $this->file->getRows();
                
               $result = $this->upload_model->update_info($data);
                if($result != false){
                    $success = 'Succesful';
                    $this->session->set_flashdata('message', $success);
                    redirect('files');
                    
                }else{ 
                     $data['files'] = $this->file->getRows();
                     $error = 'Unsuccessful';
                    $this->session->set_flashdata('message', $error);
                    redirect('files');
                   
                }
                    
            }
    } else
             {
                            $error = 'No file selected.';
                            $this->session->set_flashdata('message', $error);
                            redirect('files');
                           
             }

}


public function delete_file($id){
        $delete_file = array();
        $delete_file = $this->upload_model->get_file_by_id($id);
        unlink('uploads/'.$delete_file->upload_name);
        $result = $this->upload_model->delete_file_info($id);
        if ($result) {
            $this->session->set_flashdata('message', 'File Deleted Sucessfully');
            redirect('files');
        } else {
            $this->session->set_flashdata('message', 'File Deleted Failed');
             redirect('files');
        }
    }

}