<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class UsersController extends CI_Controller { 
     
    function __construct() { 
        parent::__construct(); 
         
        // Load form validation ibrary & user model 
        $this->load->library('form_validation'); 
        $this->load->model('userModel');
         
        // User login status 
        $this->isUserLoggedIn = $this->session->userdata('isUserLoggedIn'); 
    } 
     
    public function index(){ 
        if($this->isUserLoggedIn){ 
            redirect('users/members'); 
        }else{  
            redirect('users/login'); 
        } 
    } 

    public function logout(){ 
        $this->session->unset_userdata('isUserLoggedIn'); 
        $this->session->unset_userdata('userId'); 
        $this->session->sess_destroy(); 
        redirect('user-login'); 
    }

    public function login(){ 
        $data = array(); 
         
        // Get messages from the session 
        if($this->session->userdata('success_msg')){ 
            $data['success_msg'] = $this->session->userdata('success_msg'); 
            $this->session->unset_userdata('success_msg'); 
        } 
        if($this->session->userdata('error_msg')){ 
            $data['error_msg'] = $this->session->userdata('error_msg'); 
            $this->session->unset_userdata('error_msg'); 
        } 
         
        // If login request submitted 
        if($this->input->post('loginSubmit')){ 
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
             
            if($this->form_validation->run() == true){ 
                $con = array( 
                    'returnType' => 'single', 
                    'conditions' => array( 
                        'email'=> $this->input->post('email'), 
                        'password' => md5($this->input->post('password')), 
                        'status' => 1,
						'tag' => 1
                    ) 
                ); 

				$conn = array( 
                    'returnType' => 'single', 
                    'conditions' => array( 
                        'email'=> $this->input->post('email'), 
                        'password' => md5($this->input->post('password')), 
                        'status' => 1,
						'tag' => 0
                    ) 
                );

                $checkLogin = $this->userModel->getRows($con); 
				$checkLoginn = $this->userModel->getRows($conn); 

                if($checkLogin){ 
                    $this->session->set_userdata('isUserLoggedIn', TRUE); 
                    $this->session->set_userdata('userId', $checkLogin['id']); 
                    redirect('admin-home'); 
                }elseif($checkLoginn){ 
                    $this->session->set_userdata('isUserLoggedIn', TRUE); 
                    $this->session->set_userdata('userId', $checkLoginn['id']); 
                    redirect('user-home'); 
                }else{ 
                    $data['error_msg'] = 'Wrong email or password or account not yet activated, please contact unilevel administrator.'; 
                } 
            }
        } 
         
        // Load view 
        $this->load->view('includes/header', $data); 
        $this->load->view('login', $data); 
        $this->load->view('includes/footer'); 
    }
 
    public function home(){ 
        $data = array(); 
        if($this->isUserLoggedIn){ 
            $conn = array( 
                'id' => $this->session->userdata('userId') 
            ); 
            $data['user'] = $this->userModel->getRows($conn); 
             
            // Pass the user data and load view 
            $this->load->view('includes/header', $data); 
            //$this->load->view('users/user-nav', $data); 
            $this->load->view('users/home', $data); 
            $this->load->view('includes/footer'); 
        }else{ 
            redirect('users/login'); 
        } 
    }

	public function edit($id){ 
        $data = array(); 
        if($this->isUserLoggedIn){ 
            $conn = array( 
                'id' => $this->session->userdata('userId') 
            ); 
            $data['user'] = $this->user->getRows($conn); 

            if ($this->input->post('btnEdit')) {
                $this->form_validation->set_rules('firstname', 'First Name', 'required'); 
                $this->form_validation->set_rules('lastname', 'Last Name', 'required');
                
				if($this->form_validation->run()){
                    $old_filename = $this->input->post('old_picture');
                    $new_filename = $_FILES['picture']['name'];

                    if($new_filename == TRUE){
                        $update_filename = time()."_".str_replace(' ', '-', $_FILES['picture']['name']);
                        $config = [
                            'upload_path' => "./images/",
                            'allowed_types' => 'jpg|png|jpeg',
                            'file_name' => $update_filename,
                        ];
                        $this->load->library('upload',$config);
                        if($this->upload->do_upload('picture')){
                            if(file_exists("./images/".$old_filename)){
                                unlink("./images".$old_filename);
                            }
                        }
                    }
                }else{
                    $update_filename = $old_filename;
                }
                $editData['firstname'] = $this->input->post('firstname');
				$editData['lastname'] = $this->input->post('lastname');
                $editData['picture'] = $update_filename;

				$update = $this->admin_model->update($editData, $id);
				if ($update) {
					redirect('users/members');
				}
			}
            // Pass the user data and load view 
            $this->load->view('includes/header', $data); 
            $this->load->view('users/user-nav', $data); 
            $this->load->view('users/account-edit', $data); 
            $this->load->view('includes/footer'); 
        }else{ 
            redirect('users/login'); 
        } 
    } 
	

     
 
    
 
    public function registration($referral=''){
        
        
        $data = $userData = array(); 
        // If registration request is submitted 
        if($this->input->post('signupSubmit')){ 

           

            $this->form_validation->set_rules('username', 'User Name', 'required|callback_username_check');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check'); 
            $this->form_validation->set_rules('password', 'password', 'required'); 
            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]'); 
			$this->form_validation->set_rules('serialnum', 'Serial Number', 'required'); 
            $this->form_validation->set_rules('serialcode', 'Serial Code', 'required');
			$this->form_validation->set_rules('sponsor', 'Sponsor Name', 'required|callback_sponsor_check'); 
            $this->form_validation->set_rules('placement', 'Placement', 'required');
			$this->form_validation->set_rules('firstname', 'First Name', 'required'); 
            $this->form_validation->set_rules('lastname', 'Last Name', 'required'); 
			$this->form_validation->set_rules('birthday', 'Birthday', 'required'); 
			$this->form_validation->set_rules('gender', 'Gender', 'required'); 
			$this->form_validation->set_rules('phone', 'Phone', 'required'); 
			$this->form_validation->set_rules('address', 'Address', 'required'); 


            $lvl_two = '';
            $lvl_three = '';
            $lvl_four = '';
            $lvl_five = '';
            $lvl_six = '';
            $lvl_seven = '';
            $lvl_eight = '';
            $lvl_nine = '';
            $lvl_ten = '';

            $magickey = $this->input->post('sponsor'); 
            $query = $this->db->query("SELECT * FROM users WHERE username='$magickey'");

            foreach ($query->result() as $row)
            {
                    $lvl_two = $row->sponsor_lvl_one;
                    $lvl_three = $row->sponsor_lvl_two;
                    $lvl_four = $row->sponsor_lvl_three;
                    $lvl_five = $row->sponsor_lvl_four;
                    $lvl_six = $row->sponsor_lvl_five;
                    $lvl_seven = $row->sponsor_lvl_six;
                    $lvl_eight = $row->sponsor_lvl_seven;
                    $lvl_nine = $row->sponsor_lvl_eight;
                    $lvl_ten = $row->sponsor_lvl_nine;
            }
            
 
            $userData = array( 
                'username' => strip_tags($this->input->post('username')), 
                'email' => strip_tags($this->input->post('email')), 
                'password' => md5($this->input->post('password')),
				'serialnum' => strip_tags($this->input->post('serialnum')), 
				'serialcode' => strip_tags($this->input->post('serialcode')), 

				'sponsor_lvl_one' => $magickey,
                'sponsor_lvl_two' =>  $lvl_two,
                'sponsor_lvl_three' =>  $lvl_three,
                'sponsor_lvl_four' =>  $lvl_four,
                'sponsor_lvl_five' =>  $lvl_five,
                'sponsor_lvl_six' =>  $lvl_six,
                'sponsor_lvl_seven' =>  $lvl_seven,
                'sponsor_lvl_eight' =>  $lvl_eight,
                'sponsor_lvl_nine' =>  $lvl_nine,
                'sponsor_lvl_ten' =>  $lvl_ten,

				'placement' => $this->input->post('placement'), 
				'firstname' => strip_tags($this->input->post('firstname')), 
				'lastname' => strip_tags($this->input->post('lastname')), 
				'birthday' => strip_tags($this->input->post('birthday')), 
				'gender' => $this->input->post('gender'),
				'phone' => strip_tags($this->input->post('phone')), 
                'address' => strip_tags($this->input->post('address')) // default no comma here
            ); 
 
            if($this->form_validation->run() == true){ 
                $insert = $this->user->insert($userData); 
                if($insert){ 
                    $this->session->set_userdata('success_msg', 'Your account registration has been successful. Please wait for your account activation.'); 
                    redirect('users/login'); 
                }else{ 
                    $data['error_msg'] = 'Some problems occured, please try again.'; 
                } 
            }else{ 
                $data['error_msg'] = 'Please fill all the mandatory fields.'; 
            } 
        } 
         
        // Posted data 
        $data['user'] = $userData; 
        $data['ref'] = $referral;
        // Load view 
        $this->load->view('includes/header', $data); 
        $this->load->view('users/registration', $data); 
        $this->load->view('includes/footer'); 
    } 
     
    // Existing email check during validation 
    public function email_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'email' => $str 
            ) 
        ); 
        $checkEmail = $this->user->getRows($con); 
        if($checkEmail > 0){ 
            $this->form_validation->set_message('email_check', 'The given email already exists.'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    }

     // Existing Username check during validation 
     public function username_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'username' => $str 
            ) 
        ); 
        $checkUsername = $this->user->getRows($con); 
        if($checkUsername > 0){ 
            $this->form_validation->set_message('username_check', 'The given Username already exists.'); 
            return FALSE; 
        }else{ 
            return TRUE; 
        } 
    }

     // Existing Sponsor check during validation 
     public function sponsor_check($str){ 
        $con = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'username' => $str 
            ) 
        ); 
        $checkSponsor = $this->user->getRows($con); 
        if(!$checkSponsor > 0){ 
            $this->form_validation->set_message('sponsor_check', 'Sponsor Name do not exists.'); 
            return FALSE;
        }else{ 
            return TRUE; 
        } 
    }

    public function geneology(){ 
        $data = array(); 
        if($this->isUserLoggedIn){ 
            $conn = array( 
                'id' => $this->session->userdata('userId'),
                'username' => $this->session->userdata('username') 
            ); 
            $data['user'] = $this->user->getRows($conn); 
             
            // Pass the user data and load view 
            $data['first_level'] = $this->admin_model->first_level();
            $this->load->view('includes/header', $data); 
            $this->load->view('users/user-nav', $data); 
            $this->load->view('users/genealogy', $data); 
            $this->load->view('includes/footer'); 
        }else{ 
            redirect('users/login'); 
        } 
    }


}
