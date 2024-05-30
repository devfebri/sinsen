<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terima_plat extends CI_Controller {

    var $tables =   "tr_kirim_plat";	
		var $folder =   "h1";
		var $page		=		"terima_plat";
    var $pk     =   "no_kirim_plat";
    var $title  =   "Terima Plat dari Biro Jasa";

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
		//$this->sync();
		$data['dt_plat']	= $this->db->query("SELECT * FROM tr_kirim_plat ORDER BY no_kirim_plat DESC");
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";			
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";		
		$id = $this->input->get('id');
		$data['sql'] = $this->db->query("SELECT * FROM tr_kirim_plat_detail INNER JOIN tr_entry_stnk ON tr_kirim_plat_detail.no_mesin = tr_entry_stnk.no_mesin
			inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		WHERE tr_kirim_plat_detail.no_kirim_plat = '$id'");
		$this->template($data);			
	}
	public function konfirm()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "konfirm";		
		$id = $this->input->get('id');
		$data['no_kirim_plat'] = $id;
		$data['sql'] = $this->db->query("SELECT * FROM tr_kirim_plat_detail INNER JOIN tr_entry_stnk ON tr_kirim_plat_detail.no_mesin = tr_entry_stnk.no_mesin
			inner join tr_faktur_stnk_detail on tr_entry_stnk.no_mesin = tr_faktur_stnk_detail.no_mesin
        inner join tr_faktur_stnk on tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd
        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
		WHERE tr_kirim_plat_detail.no_kirim_plat = '$id'");
		$this->template($data);			
	}		

	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$no_kirim_plat 					= $this->input->post('no_kirim_plat');		
		$da['updated_at'] 			= $waktu;		
		$da['updated_by'] 			= $login_id;		
	
		$jum 										= $this->input->post("jum");		
		for ($i=1; $i <= $jum; $i++) { 
			if(isset($_POST["cek_plat_".$i])){
				$nosin 								= trim($_POST["no_mesin_".$i]);
				$this->db->query("UPDATE tr_kirim_plat_detail SET konfirm = 'ya' WHERE no_mesin = '$nosin'");										
				$amb = $this->m_admin->getByID('tr_entry_stnk','no_mesin',$nosin)->row();
				$amc = $this->m_admin->getByID('tr_pengajuan_bbn_detail','no_mesin',$nosin)->row();
				$cek = $this->db->query("SELECT * FROM tr_terima_bj WHERE no_mesin = '$nosin'");
				if($cek->num_rows() == 1){
					$ds['no_kirim_plat'] 	= $no_kirim_plat;
					$ds['no_plat'] 				= $amb->no_plat;
					$ds['tgl_plat'] 			= $amb->tgl_plat;
					$ds['tgl_terima_plat']= $tgl;
					$ds['status_bj'] 			= "input";
					$ds['updated_by']			= $login_id;
					$ds['updated_at']			= $waktu;
					$this->m_admin->update('tr_terima_bj',$ds,'no_mesin',$nosin);
				}elseif($cek->num_rows() == 0){
					$ds['no_bastd'] 			= $amc->no_bastd;
					$ds['no_kirim_plat'] 	= $no_kirim_plat;
					$ds['no_plat'] 				= $amb->no_plat;
					$ds['no_mesin']				= $amb->no_mesin;
					$ds['no_rangka']			= $amb->no_rangka;
					$ds['nama_konsumen'] 	= $amb->nama_konsumen;
					$ds['id_tipe_kendaraan'] 	= $amb->id_tipe_kendaraan;
					$ds['id_warna'] 			= $amb->id_warna;
					$ds['notice_pajak'] 	= $amb->notice_pajak;
					$ds['status_bj'] 			= "input";
					$ds['tgl_plat'] 			= $amb->tgl_plat;
					$ds['tgl_terima_plat']= $tgl;
					$ds['created_by']			= $login_id;
					$ds['created_at']			= $waktu;
					$this->m_admin->insert('tr_terima_bj',$ds);
				}
			}			
		}
			
		$this->m_admin->update("tr_kirim_plat",$da,"no_kirim_plat",$no_kirim_plat);								
		
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/terima_plat'>";
	}
	public function sync(){
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');								
		$cek = $this->db->query("select * from tr_kirim_plat_detail WHERE no_mesin not in (select no_mesin FROM tr_terima_bj where no_kirim_plat is not null) and konfirm = 'ya'");
		foreach ($cek->result() as $isi) {
			$nosin = $isi->no_mesin;
    				$amb = $this->m_admin->getByID('tr_entry_stnk','no_mesin',$nosin)->row();
    				$amc = $this->m_admin->getByID('tr_pengajuan_bbn_detail','no_mesin',$nosin)->row();
    				$cek = $this->db->query("SELECT * FROM tr_terima_bj WHERE no_mesin = '$nosin'");
    				if($cek->num_rows() > 0){
    					$ds['no_kirim_plat'] 	= $isi->no_kirim_plat;
    					$ds['no_stnk'] 				= $amb->no_stnk;
    					$ds['tgl_stnk'] 			= $amb->tgl_stnk;
    					//$ds['tgl_terima_stnk']= $tgl;
    					$ds['updated_by']			= $login_id;
    					$ds['status_bj'] 			= "input";
    					$ds['updated_at']			= $waktu;
    					$this->m_admin->update('tr_terima_bj',$ds,'no_mesin',$nosin);
    				}else{
    					$ds['no_bastd'] 			= $amc->no_bastd;
    					$ds['no_kirim_plat'] 	= $isi->no_kirim_plat;
    					$ds['no_stnk'] 				= $amb->no_stnk;
    					$ds['no_mesin']				= $amb->no_mesin;
    					$ds['no_rangka']			= $amb->no_rangka;
    					$ds['nama_konsumen'] 	= $amb->nama_konsumen;
    					$ds['id_tipe_kendaraan'] 	= $amb->id_tipe_kendaraan;
    					$ds['id_warna'] 			= $amb->id_warna;
    					$ds['notice_pajak'] 	= $amb->notice_pajak;
    					$ds['status_bj'] 			= "input";
    					$ds['tgl_stnk'] 			= $amb->tgl_stnk;
    					$ds['tgl_terima_stnk']= $tgl;
    					$ds['created_by']			= $login_id;
    					$ds['created_at']			= $waktu;
    					$this->m_admin->insert('tr_terima_bj',$ds);
    				}
		}
	}
}