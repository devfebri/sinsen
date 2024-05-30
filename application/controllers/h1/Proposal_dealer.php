<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Proposal_dealer extends CI_Controller {
    var $tables =   "tr_proposal_dealer";	
		var $folder =   "h1";
		var $page		=		"proposal_dealer";
    var $pk     =   "id_proposal";
    var $title  =   "Proposal Dealer";
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
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_proposal_dealer INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer	
						WHERE tr_proposal_dealer.status = 'waiting'");
		$this->template($data);			
	}	
	public function approve()
	{				
		$id = $this->input->get('id');
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_proposal_dealer INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer	
						WHERE tr_proposal_dealer.id_proposal = '$id'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "approve";				
		$this->template($data);			
	}	
	public function reject()
	{				
		$id = $this->input->get('id');
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_proposal_dealer INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer	
						WHERE tr_proposal_dealer.id_proposal = '$id'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "reject";				
		$this->template($data);			
	}			
	public function save_approval()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_proposal	=	$this->input->post("id_proposal");
		
		
		$data['tgl_lpj'] 	=	$this->input->post("tgl_lpj");
		$data['no_juklak']				=	$this->input->post("no_juklak");
		$data['ahm_text']			=	$this->input->post("ahm_text");
		$data['md_text']			=	$this->input->post("md_text");
		$data['dealer_text']					=	$this->input->post("dealer_text");
		$data['lainnya_text']					=	$this->input->post("lainnya_text");
		$data['status']			=	"approved";
		$data['updated_at']			=	$waktu;
		$data['updated_by']			=	$login_id;
		
		$this->m_admin->update($tabel,$data,$pk,$id_proposal);
			
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/proposal_dealer'>";		
	}
	public function save_reject()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_proposal	=	$this->input->post("id_proposal");
		
		
		$data['alasan_reject'] 	=	$this->input->post("alasan_reject");		
		$data['status']			=	"rejected";
		$data['updated_at']			=	$waktu;
		$data['updated_by']			=	$login_id;
		
		$this->m_admin->update($tabel,$data,$pk,$id_proposal);
			
		$_SESSION['pesan'] 	= "Data has been rejected successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/proposal_dealer'>";		
	}
	public function revisi()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_proposal	=	$this->input->get("id");
				
		$data['status']					=	"revisi";
		$data['updated_at']			=	$waktu;
		$data['updated_by']			=	$login_id;
		
		$this->m_admin->update($tabel,$data,$pk,$id_proposal);
			
		$_SESSION['pesan'] 	= "Data has been update successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/proposal_dealer'>";		
	}
}