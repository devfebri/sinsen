<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggantian_fkb extends CI_Controller {

    var $tables =   "tr_penggantian_fkb";	
		var $folder =   "h1";
		var $page		=		"penggantian_fkb";
    var $pk     =   "no_surat";
    var $title  =   "Proses Penggantian FKB";

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
		$data['dt_alasan'] = $this->m_admin->getAll($this->tables);
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function cari_id()
	{				
		$id = $this->m_admin->cari_id("tr_penggantian_fkb","no_surat");
		echo $id;
	}
	public function t_data(){
		$id 	= $this->input->post('no_surat');		
		$dq = "SELECT * FROM tr_penggantian_fkb_detail WHERE no_surat = '$id'";
		$data['dt_data'] = $this->db->query($dq);		
		$data['mode'] = $this->input->post('mode');
		$this->load->view('h1/t_penggantian_fkb',$data);
	}
	public function ambil_nosin()
	{				
		$no_mesin = $this->input->post("no_mesin");
		$sql = $this->db->query("SELECT * FROM tr_fkb INNER JOIN ms_tipe_kendaraan ON tr_fkb.kode_tipe=ms_tipe_kendaraan.id_tipe_kendaraan 
							INNER JOIN ms_warna ON tr_fkb.kode_warna=ms_warna.id_warna
							WHERE tr_fkb.no_mesin_spasi = '$no_mesin'")->row();		
		// $sql = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan 
		// 					INNER JOIN ms_warna ON tr_scan_barcode.kode_warna=ms_warna.id_warna
		// 					WHERE tr_scan_barcode.no_mesin = '$no_mesin'")->row();		
		echo $sql->tipe_ahm."|".$sql->warna."|".$sql->tahun_produksi."|".$sql->nomor_faktur;
	}
	public function save_nosin(){
		$no_surat		= $this->input->post('no_surat');			
		$no_mesin			= $this->input->post('no_mesin');			
		$data['no_surat']		= $this->input->post('no_surat');			
		$data['no_mesin']			= $this->input->post('no_mesin');
		$data['alasan_penggantian']			= $this->input->post('alasan_penggantian');
		$cek = $this->db->query("SELECT * FROM tr_penggantian_fkb_detail WHERE no_surat = '$no_surat' AND no_mesin = '$no_mesin'");
		if($cek->num_rows() > 0){
			$sq = $cek->row();
			$id = $sq->id_penggantian_fkb_detail;
			$this->m_admin->update("tr_penggantian_fkb_detail",$data,"id_penggantian_fkb_detail",$id);			
		}else{
			$this->m_admin->insert("tr_penggantian_fkb_detail",$data);			
		}				
		echo "ok";
	}	
	public function delete_nosin(){
		$id = $this->input->post('id_penggantian_fkb_detail');		
		$this->db->query("DELETE FROM tr_penggantian_fkb_detail WHERE id_penggantian_fkb_detail = '$id'");			
		echo "nihil";
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel		= $this->tables;		
		
		$data['no_surat'] 					= $this->input->post('no_surat');		
		$data['tgl_entry'] 					= $this->input->post('tgl_entry');		
		$data['ditujukan_ke'] 			= $this->input->post('ditujukan_ke');				
		$data['nama_pengirim'] 			= $this->input->post('nama_pengirim');				
		$data['jabatan'] 						= $this->input->post('jabatan');				
		$data['created_at']					= $waktu;		
		$data['created_by']					= $login_id;
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 		= "Data has been saved successfully";
		$_SESSION['tipe'] 		= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/penggantian_fkb/add'>";		
	}
	public function detail()
	{				
		$id = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$data['set']		= "detail";		
		$data['dt_alasan'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$this->template($data);			
	}
	public function cetak()
	{				
		$id = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= "Cetak ".$this->title;															
		$data['set']		= "cetak";		
		$data['dt_alasan'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$this->template($data);			
	}
	public function cetak_fix()
	{						
		$id = $this->input->get("id");		
		$data['dt_alasan'] = $this->m_admin->getByID($this->tables,$this->pk,$id);
		$this->load->view('h1/cetak_penggantian_fkb',$data);
	}
}