<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_juklak_dealer_cc extends CI_Controller {

    var $tables =   "ms_cc_email";	
	var $folder =   "h1";
	var $page	=   "email_juklak_dealer_cc";
    var $pk     =   "id";
    var $title  =   "Email Juklak Dealer CC";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}
	}


	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{						
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header',$data);
			$this->load->view('template/aside');			
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$data['dt_mail'] = $this->db->query("SELECT id,email_cc,module,active FROM `ms_cc_email`");						
		$this->template($data);			
	}


	public function email_juklak()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$id_dealer 		= $this->input->get("id");	
		$data['set']	   = "detail";			
		
		$data['count']             = $this->db->query("SELECT COUNT(id) as jumlah FROM ms_cc_email WHERE active='1'");	
		$data['dt_dealer_show']    = $this->db->query("SELECT id,email,id_dealer,active from ms_send_to_email WHERE id_dealer='$id_dealer'");	
		$data['dt_dealer_header']  = $this->db->query("SELECT nama_dealer,kode_dealer_md,id_dealer  from ms_dealer WHERE id_dealer='$id_dealer' limit 1")->row();	
		$this->template($data);			
	}


	public function add()
	{				
		$tabel			 = $this->tables;
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$email 		    = $this->input->post("email_juklak_cc");	
		$status 		= 1;	

		
		$hasil = $this->db->query("SELECT email_cc FROM $tabel WHERE email_cc='$email' ")->row();

		if (!empty($hasil)) {
		$_SESSION['pesan'] 		= "Email has been used";
		$_SESSION['tipe'] 		= "danger";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer_cc'>";
		
		  }else if (empty($hasil)) {
		
			$params = [
				'email_cc' =>$email,
				'nickname' => NULL,
				'module' => 'auto_claim',
				'active' => $status,
			];
			
			$this->db->insert($tabel, $params);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
	
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer_cc'>";
		  }

	
	}


	public function edit()
	{		
		$tabel			  = $this->tables;
		$pk 			  = 'id';		
		$status			  = $this->input->post('status_active');
		$datanya['email_cc'] = $this->input->post('email_juklak');
		$idpost	          = $this->input->post("id_email");	
		if ($status == 'on') {
			$datanya['active']= 1;
		}else {
			$datanya['active']= $status;
		}
		$data['dt_email'] = $this->m_admin->update($tabel, $datanya, $pk, $idpost);
		$data['isi']      = $this->page;		
		$data['title']    = $this->title;	
		$data['set']	  = "detail";	
		
		
		$_SESSION['pesan'] 		= "Data has been update successfully";
		$_SESSION['tipe'] 		= "success";

		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer_cc'>";					
	}



	public function delete()
	{		
		$tabel			    = $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');

		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{		
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/email_juklak_dealer_cc'>";
		}
	}




}