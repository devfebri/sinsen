<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Denied extends CI_Controller {

	public function __construct()
	{		
		parent::__construct();		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url','string');    				
		$this->load->model("m_admin");
	}
	protected function template($page, $data)
	{		
		$this->load->view('template/header',$data);			
		$this->load->view('template/aside');
		$this->load->view("$page");		
		$this->load->view('template/footer');		
	}
	public function index(){
		$page						= "denied";		
		$data['title']	= "Access Denied";			
		$data['isi']		= "";		
		$data['judul']	= "Maaf, Anda tidak Memiliki hak akses untuk menu ini!";			
		//$this->template($page, $data);
		$this->load->view($page, $data);
	}	
	public function send_email(){
    	$email = $this->input->get('em');      
      $this->load->library('email');
      $config['mailtype'] = 'html'; // or html
      $config['protocol'] = 'mail';
        $config['validation'] = TRUE; // bool whether to validate email or not      
        $this->email->initialize($config);
        //$this->email->from('admin@sikesal.jambikota.go.id', 'Admin Sikesal');
        $this->email->from('lailynfuad@gmail.com', 'Admin Sikesal');
        $this->email->to($email); 
        $this->email->subject('Verifikasi Pendaftaran');
        $this->email->message("Terima kasih telah mendaftar di aplikasi SiKesal Kota Jambi. Kode Konfirmasi Anda , Username Anda tes, Password Anda tes. Selamat Mencoba, Terima Kasih.");
        //$this->email->send();
        if($this->email->send())
        {
          echo 'Email sent.';
        }
         else
        {
         show_error($this->email->print_debugger());
        }
    //echo "<script>history.go(-1);</script>";  
  }
}
