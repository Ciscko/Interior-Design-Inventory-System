<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('users');
        $this->load->model('user_model');
         $this->load->library('form_validation');
    }

    public function index(){
        $this->account();
    }

    /*
     * User account information
     */
    public function account(){
        $data = array();
        if($this->session->userdata('isUserLoggedIn')){
            $data['user'] = $this->users->getRows(array('id'=>$this->session->userdata('userId')));
            //load the view
            $this->load->view('header',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('users/landing', $data);
        }else{
            redirect('user/login');
        }
    }
    
    /*
     * User login
     */
    public function login(){
        $data = array();
        if($this->session->userdata('success_msg')){
            $data['success_msg'] = $this->session->userdata('success_msg');
            $this->session->unset_userdata('success_msg');
        }
        if($this->session->userdata('error_msg')){
            $data['error_msg'] = $this->session->userdata('error_msg');
            $this->session->unset_userdata('error_msg');
        }
        if($this->input->post('loginSubmit')){
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'password', 'required');
            if ($this->form_validation->run() == true) {
                $con['returnType'] = 'single';
                $con['conditions'] = array(
                    'email'=>$this->input->post('email'),
                    'password' => md5($this->input->post('password'))
                    
                );
                $checkLogin = $this->users->getRows($con);
                if($checkLogin){
                    $this->session->set_userdata('isUserLoggedIn',TRUE);
                    $this->session->set_userdata('userId',$checkLogin['id']);
                    redirect('user/account/');
                }else{
                    $data['error_msg'] = 'Wrong email or password, please try again.';
                }
            }
        }
        //load the view
        $this->load->view('users/login', $data);
    }
    
    /*
     * User registration
     */
    public function registration(){
        $data = array();
        $userData = array();
        if($this->input->post('regisSubmit')){
            $this->form_validation->set_rules('people_personId', 'ID', 'required');
            $this->form_validation->set_rules('userName', 'Name', 'required');
            $this->form_validation->set_rules('level', 'Category', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]');

            $userData = array(
                'people_personId' => strip_tags($this->input->post('people_personId')),
                'userName' => strip_tags($this->input->post('userName')),
                'email' => strip_tags($this->input->post('email')),
                'password' => md5($this->input->post('password')),
                'level' => strip_tags($this->input->post('level'))
            );

            if($this->form_validation->run() == true){
                $insert = $this->users->insert($userData);
                if($insert){
                    $this->session->set_userdata('success_msg', 'Your registration was successfully. Please login to your account.');
                    redirect('user/login');
                }else{
                    $data['error_msg'] = 'Some problems occured, please try again.';
                }
            }
        }
        $data['user'] = $userData;
        $data['persons'] = $this->user_model->get_persons();
        //load the view
        $this->load->view('users/registration', $data);
    }
    
    /*
     * User logout
     */
    public function logout(){
        $this->session->unset_userdata('isUserLoggedIn');
        $this->session->unset_userdata('userId');
        $this->session->sess_destroy();
        redirect('user/login/');
    }
    
  
}