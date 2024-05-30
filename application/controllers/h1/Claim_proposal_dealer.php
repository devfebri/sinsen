<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Claim_proposal_dealer extends CI_Controller {
    var $tables =   "tr_claim_marketing";	
		var $folder =   "h1";
		var $page		=		"claim_proposal_dealer";
    var $pk     =   "id_claim";
    var $title  =   "Claim Proposal Dealer";
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
		$data['dt_claim'] = $this->db->query("SELECT *,tr_claim_marketing.status AS status_claim FROM tr_claim_marketing inner join 
			tr_proposal_dealer on tr_claim_marketing.id_proposal = tr_proposal_dealer.id_proposal
			INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer order by tr_claim_marketing.id_claim ASC");						
		$this->template($data);			
	}	
	public function approve()
	{				
		$id = $this->input->get('id');
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_claim_marketing inner join 
						tr_proposal_dealer on tr_claim_marketing.id_proposal = tr_proposal_dealer.id_proposal
						INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer
						WHERE tr_proposal_dealer.id_proposal = '$id'");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "approve";				
		$this->template($data);			
	}	
	public function reject()
	{				
		$id = $this->input->get('id');
		$data['dt_proposal'] = $this->db->query("SELECT * FROM tr_claim_marketing inner join 
						tr_proposal_dealer on tr_claim_marketing.id_proposal = tr_proposal_dealer.id_proposal
						INNER JOIN ms_dealer ON tr_proposal_dealer.id_dealer = ms_dealer.id_dealer
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
		$id_claim	=	$this->input->post("id_claim");
		
		$data['status']			=	"approved";
		$data['updated_at']			=	$waktu;
		$data['updated_by']			=	$login_id;
		
		$this->m_admin->update($tabel,$data,$pk,$id_claim);
			
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/claim_proposal_dealer'>";		
	}
	public function save_reject()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');		
		$id_claim	=	$this->input->post("id_claim");
		
		
		$data['alasan_reject'] 	=	$this->input->post("alasan_reject");		
		$data['status']			=	"rejected";
		$data['updated_at']			=	$waktu;
		$data['updated_by']			=	$login_id;
		
		$this->m_admin->update($tabel,$data,$pk,$id_claim);
			
		$_SESSION['pesan'] 	= "Data has been rejected successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/claim_proposal_dealer'>";		
	}	
}