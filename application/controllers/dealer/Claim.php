<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Claim extends CI_Controller {
    var $tables =   "tr_claim_marketing";	
	var $folder =   "dealer";
	var $page	=	"claim";
    var $pk     =   "id_claim";
    var $title  =   "Claim";
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
		$data['set']	= "view";	
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_claim'] = $this->db->query("SELECT *,tr_claim_marketing.status AS status_claim FROM tr_claim_marketing inner join tr_proposal_dealer on tr_claim_marketing.id_proposal = tr_proposal_dealer.id_proposal 
			WHERE tr_proposal_dealer.id_dealer = '$id_dealer' order by tr_claim_marketing.id_claim ASC");						
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";					
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna","warna","ASC");								
		$this->template($data);										
	}
	public function cari_id(){
		
		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 			= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek ORDER BY id_prospek DESC LIMIT 0,1");						
		if($pr_num->num_rows()>0){
			$row 	= $pr_num->row();				
			$pan  = strlen($row->id_prospek)-11;
			$id 	= substr($row->id_prospek,$pan,11)+1;	
			if($id < 10){
				$kode1 = $th.$bln.$tgl."0000".$id;          
		    }elseif($id > 9 && $id <= 99){
				$kode1 = $th.$bln.$tgl."000".$id;                    
		    }elseif($id > 99 && $id <= 999){
				$kode1 = $th.$bln.$tgl."00".$id;          					          
		    }elseif($id > 999){
				$kode1 = $th.$bln.$tgl."0".$id;                    
		    }
			$kode = $kode_dealer.$kode1;
		}else{
			$kode = $kode_dealer.$th.$bln.$tgl."00001";
		} 	
		$rt = rand(1111,9999);
		echo $kode."|".$rt;
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$config['upload_path'] 			= './assets/panel/files/';
		$config['allowed_types'] 		= 'jpg|jpeg|png|doc|docx|pdf|png';
		$config['max_size']					= '5000';
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('file_lpj')){
			$file_lpj = "gagal";
		}else{
			$file_lpj = $this->upload->file_name;
		}		
		if($file_lpj != "gagal"){
			$data['file_lpj'] 		= $file_lpj;		
		}
		
		$data['id_proposal'] 	= $this->input->post('id_proposal');
		$data['created_at']		= $waktu;		
		$data['created_by']		= $login_id;	
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/claim/add'>";
	}
	public function cetak_striker()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak Ulang Stiker";	
		$no_shipping_list 	= $this->input->get("id");	
		$data['set']		= "cetak";
		$data['dt_shipping_list'] = $this->db->query("SELECT * FROM tr_shipping_list INNER JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna 
					WHERE tr_shipping_list.no_shipping_list = '$no_shipping_list'");				
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");								
		$this->template($data);	
		//$this->load->view('trans/logistik',$data);
	}
	public function list_ksu(){
		$data['isi']    = $this->page;		
		$data['title']	= "List KSU";															
		$data['set']	= "list_ksu";
		//$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);										
	}
}