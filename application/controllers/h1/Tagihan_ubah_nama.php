<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tagihan_ubah_nama extends CI_Controller {

    var $tables =   "tr_bantuan_bbn";	
		var $folder =   "h1";
		var $page		=		"tagihan_ubah_nama";
    var $pk     =   "id_bantuan_bbn";
    var $title  =   "Tagihan Ubah Nama";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_kelurahan');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('cfpdf');
		$this->load->library('mpdf_l');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');

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
		$data['dt_tagihan'] = $this->db->query("SELECT * FROM tr_pengajuan_bbn INNER JOIN tr_pengajuan_bbn_detail ON tr_pengajuan_bbn.no_bastd = tr_pengajuan_bbn_detail.no_bastd 
				INNER JOIN ms_dealer ON tr_pengajuan_bbn.id_dealer = ms_dealer.id_dealer
				WHERE tr_pengajuan_bbn_detail.sengaja = 1");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "add";		
		$this->template($data);			
	}	
	public function t_detail(){
		$id_dealer = $this->input->post('id_dealer');
		$data['dt_tagihan'] = $this->db->query("SELECT * FROM tr_tagihan_ubah_nama_detail 
				INNER JOIN tr_pengajuan_bbn_detail ON tr_tagihan_ubah_nama_detail.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
				WHERE tr_tagihan_ubah_nama_detail.id_dealer = '$id_dealer' AND (tr_tagihan_ubah_nama_detail.id_tagihan_ubah_nama IS NULL 
				OR tr_tagihan_ubah_nama_detail.id_tagihan_ubah_nama = '') AND tr_tagihan_ubah_nama_detail.status = 'approved'");		 
		$this->load->view('h1/t_tagihan_detail',$data);
	}	
	public function list_tagihan()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "list";		
		$data['dt_tagihan'] = $this->db->query("SELECT * FROM tr_tagihan_ubah_nama INNER JOIN ms_dealer ON tr_tagihan_ubah_nama.id_dealer = ms_dealer.id_dealer 
						ORDER BY tr_tagihan_ubah_nama.created_at DESC");
		$this->template($data);			
	}	


	public function detail_popup()
	{				
		$no_mesin = $this->input->post("no_mesin");
		$data['isi']    = $this->page;	
		$data['dt_tagihan']	= $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_mesin = '$no_mesin'");
		$data['title']	= "Detail";						
		$this->load->view("h1/t_tagihan_ubah_nama.php",$data);		
	}
	public function reject(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->get("id");							
		$sql = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn.no_bastd = tr_pengajuan_bbn_detail.no_bastd 
						WHERE no_mesin='$id'")->row();
		$data['status'] 					= "rejected";
		$no_bastd = $data['no_bastd']					= $sql->no_bastd;		
		$data['no_mesin']					= $sql->no_mesin;		
		$data['id_dealer']				= $sql->id_dealer;		
		$data['updated_by']				= $login_id;
		$data['updated_by']				= $waktu;
		$cek = $this->m_admin->getByID("tr_tagihan_ubah_nama_detail","no_bastd",$no_bastd);
		if($cek->num_rows() > 0){
			$this->m_admin->update("tr_tagihan_ubah_nama_detail",$data,"no_bastd",$no_bastd);			
		}else{
			$this->m_admin->insert("tr_tagihan_ubah_nama_detail",$data);			
		}

		$ds['sengaja'] = '';
		$this->m_admin->update("tr_pengajuan_bbn_detail",$ds,"no_bastd",$no_bastd);			

		$_SESSION['pesan'] 	= "Data has been rejected successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_ubah_nama'>";		
	}
	public function approve(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("no_mesin");							
		$sql = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn.no_bastd = tr_pengajuan_bbn_detail.no_bastd 
						WHERE no_mesin='$id'")->row();
		$data['status'] 					= "approved";
		$no_bastd = $data['no_bastd']					= $sql->no_bastd;		
		$data['no_mesin']					= $sql->no_mesin;		
		$data['id_dealer']				= $sql->id_dealer;		
		$data['biaya_denda']			= $this->input->post('biaya_denda');		
		$data['updated_by']				= $login_id;
		$data['updated_by']				= $waktu;
		$cek = $this->m_admin->getByID("tr_tagihan_ubah_nama_detail","no_bastd",$no_bastd);
		if($cek->num_rows() > 0){
			$this->m_admin->update("tr_tagihan_ubah_nama_detail",$data,"no_bastd",$no_bastd);			
		}else{
			$this->m_admin->insert("tr_tagihan_ubah_nama_detail",$data);			
		}
		$_SESSION['pesan'] 	= "Data has been approved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_ubah_nama'>";		
	}	
	public function cari_id(){		 
		 $tahun						= date("Y");
		 $bln 						= date("m");		
		 $pr_num 				= $this->db->query("SELECT * FROM tr_tagihan_ubah_nama ORDER BY created_at DESC LIMIT 0,1");						
		 if($pr_num->num_rows()>0){
		 	$row 	= $pr_num->row();
		 	$id = explode('/', $row->id_tagihan_ubah_nama);
		 	if (count($id) > 1) {
		 		$isi 	= "TUNSTNK/".$bln."/".$tahun."/".sprintf("%'.03d",$id[3]+1);			
		 	}else{
		 		$kode = "TUNSTNK/".$bln."/".$tahun."/001";
		 	}						 	
		 	$kode = $isi;
		}else{
		  $kode = "TUNSTNK/".$bln."/".$tahun."/001";
		} 			
		 return $kode;
	}
	public function save_all(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$jum 										= $this->input->post("jum");		
		$id_tagihan_ubah_nama = $this->cari_id();
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_tagihan_".$i])){				

				$data['id_tagihan_ubah_nama'] 		= $id_tagihan_ubah_nama;
				$data['no_mesin'] = $no_mesin = $_POST["no_mesin_".$i];			
				$data["status"]   = "checked";				

				$this->m_admin->update("tr_tagihan_ubah_nama_detail",$data,"no_mesin",$no_mesin);												
			}			
		}
		
		$da['id_tagihan_ubah_nama'] 		= $id_tagihan_ubah_nama;
		$da['tgl_tagih'] = $this->input->post("tgl_tagih");
		$da['id_dealer'] = $this->input->post("id_dealer");
		$da['status_tagih'] = "input";
		$da['created_by']				= $login_id;
		$da['created_at']				= $waktu;

		$ce = $this->db->query("SELECT * FROM tr_tagihan_ubah_nama WHERE id_tagihan_ubah_nama = '$id_tagihan_ubah_nama'");
		if($ce->num_rows() > 0){						
			$this->m_admin->update("tr_tagihan_ubah_nama",$da,"id_tagihan_ubah_nama",$id_tagihan_ubah_nama);								
		}else{
			$this->m_admin->insert("tr_tagihan_ubah_nama",$da);								
		}

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/tagihan_ubah_nama'>";
	}	





}