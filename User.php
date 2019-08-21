<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); 
class User extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('users_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    public function index(){
        $title = ['title'=>'Login'];
        $this->load->view('header',$title);
         if(!empty($_SESSION['username'])){
            redirect('profile');
        }else{
            $this->load->view('login');
        }
        $this->load->view('footer');
    }

    
    public function signup(){
        $title = ['title'=>'Signup'];
        $this->load->view('header',$title);
        $this->load->view('signup');
        $this->load->view('footer');
    }
    
    public function ajaxSignup(){
        $output['error'] = "";
        $this->form_validation->set_rules('name', 'Full Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[7]|max_length[30]');
        $this->form_validation->set_rules('repeatpass', 'Repeat Password', 'required|min_length[7]|max_length[30]');
        
        if($this->form_validation->run() == FALSE){
            $output['error']    = true;
            $output['messsage'] = validation_errors(); 
        }else{
            $user['name']       = $this->input->post('name');
            $user['email']      = $this->input->post('email');
            $user['username']   = $this->input->post('username');
            $user['password']   = md5($this->input->post('password'));
            $user['user_id']    = mt_rand(1000000, 9999999); 
            $repeatpass = $this->input->post('repeatpass');
            if( $this->input->post('password') !== $repeatpass ){
                $output['error'] = true;
                $output['message'] = "Password not matched";
            }else{
                $userAlreadyExist = $this->users_model->userAlreadyExist($user['email']);
                if(empty($userAlreadyExist)){
                    $userregister = $this->users_model->register($user);
                    $output['error'] = false;
                    $output['message'] = "User registered successfully";
                }else{
                    $output['error'] = true;
                    $output['message'] = 'Email ID already exists';
                }  
            }
            
        }
        echo json_encode($output);
    }
    
    public function ajaxLogin(){ 
        $output['error'] = "";
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->form_validation->run() == false){
            $output['error']    = true;
            $output['messsage'] = validation_errors(); 
        }else{
            $user['email']    = $this->input->post('email');
            $user['password'] = $this->input->post('password');
            
            $userLogin = $this->users_model->userLogin($user['email'], md5($user['password']));
            if($userLogin){

                $output['error']    = false;
                $output['messsage'] = "Successfully Loggedin";
                $_SESSION['auth'] = "Yes";
                $_SESSION['username'] = $userLogin->username;
                $_SESSION['email'] = $userLogin->email;
                $_SESSION['user_id'] = $userLogin->user_id;

            }else{
                $output['error']    = true;
                $output['messsage'] = "Invalid email or password";
            }
            
        }
       
        echo json_encode($output);
    }
    
    public function logout(){
        session_start();
        session_unset();
        session_destroy();
        redirect(base_url(),'home');
    }
    public function image()  
      { 
        $this->load->view('image_upload');
      }  
    public function ajaxImageStore()  
    {  
         if(isset($_FILES["image_file"]["name"]))  
         {  
              $config['upload_path'] = './uploads/';  
              $config['allowed_types'] = 'jpg|jpeg|png|gif';  
              $this->load->library('upload', $config);  
              if(!$this->upload->do_upload('image_file'))  
              {  
                  $error =  $this->upload->display_errors(); 
                  echo json_encode(array('msg' => $error, 'success' => false));
              }  
              else 
              {  
                   $data = $this->upload->data(); 

                   $insert['name'] = $data['file_name'];
                   $this->db->insert('image',$insert);
                       $sql = $this->db->last_query();
                       print_r($sql);die();

                   $getId = $this->db->insert_id();

                   $arr = array('msg' => 'Image has not uploaded successfully', 'success' => false);
 
                   if($getId){
                    $arr = array('msg' => 'Image has been uploaded successfully', 'success' => true);
                   }
                   echo json_encode($arr);
              }  
         }  
}
    }