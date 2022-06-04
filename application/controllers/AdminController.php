<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url','session');
        $this->load->library('form_validation'); 
        $this->load->model('userModel');
        $this->load->model('adminModel');
		
	   // User login status 
	   $this->isUserLoggedIn = $this->session->userdata('isUserLoggedIn'); 
   } 
	
   public function index(){ 
	   if($this->isUserLoggedIn){ 
		   redirect('admin/home'); 
	   }else{  
		   redirect('users/login'); 
	   } 
   }  

   public function logout(){ 
	$this->session->unset_userdata('isUserLoggedIn'); 
	$this->session->unset_userdata('userId'); 
	$this->session->sess_destroy(); 
	redirect('users/login/'); 
	} 

    
	public function home(){ 
        $data = array(); 
        if($this->isUserLoggedIn){ 
            $con = array( 
                'id' => $this->session->userdata('userId') 
            ); 
            $data['user'] = $this->userModel->getRows($con); 
             
            // Pass the user data and load view 
			//$data['get_all_members'] = $this->adminModel->get_all_members();
            $this->load->view('includes/header');
			//$this->load->view('admin/admin-nav');
            $this->load->view('admin/home', $data ); 
            $this->load->view('includes/footer'); 
        }else{ 
            redirect('users/login'); 
        } 
    } 

    public function member_edit($id) {
		$data = array(); 
        if($this->isUserLoggedIn){ 
            $con = array( 
                'id' => $this->session->userdata('userId') 
            ); 
            $data['user'] = $this->user->getRows($con);

			$data['member_details'] = $this->admin_model->get_id_member($id);

			if ($this->input->post('btnEdit')) {
				$editData['status'] = $this->input->post('status');

				$update = $this->admin_model->update($editData, $id);
				if ($update) {
					redirect('admin/home');
				}
			}


			$this->load->view('includes/header');
			$this->load->view('admin/admin-nav');
			$this->load->view('admin/account_edit', $data);
			$this->load->view('includes/footer');
    
		}else{ 
            redirect('users/login'); 
        } 
	}

	public function member_search(){
		$data = array(); 
        if($this->isUserLoggedIn){ 
            $con = array( 
                'id' => $this->session->userdata('userId') 
            ); 
            $data['user'] = $this->user->getRows($con);

			//$data['member_details'] = $this->admin_model->get_id_member();

			$key = $this->input->post('username');
			$data['result'] = $this->admin_model->search($key);




			$this->load->view('includes/header');
			$this->load->view('admin/admin-nav');
			$this->load->view('admin/member-search', $data);
			$this->load->view('includes/footer');
    
		}else{ 
            redirect('users/login'); 
        } 
	}

}
