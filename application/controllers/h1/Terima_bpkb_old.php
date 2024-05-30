<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terima_bpkb extends CI_Controller {

    var $tables =   "tr_do_dealer";	
		var $folder =   "h1";
		var $page		=		"terima_bpkb";
    var $pk     =   "no_do";
    var $title  =   "Terima BPKB dari Biro Jasa";

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
		$data['dt_bpkb']	= $this->db->query("SELECT tr_entry_stnk.*,tr_pengajuan_bbn_detail.no_bastd FROM tr_entry_stnk INNER JOIN tr_pengajuan_bbn_detail ON 
			tr_entry_stnk.no_mesin = tr_pengajuan_bbn_detail.no_mesin 
			WHERE tr_entry_stnk.no_bpkb <> '' ORDER BY id_entry_stnk ASC");
		$this->template($data);			
	}	
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$tgl 				= gmdate("y-m-d", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');													
		$jum 										= $this->input->post("jum");
		//$am=0;$tot=0;
		for ($i=1; $i <= $jum; $i++){ 
			if(isset($_POST["save_".$i])){
				$nosin 								= $_POST["no_mesin_".$i];			
				$no_serah_terima 			= $_POST["no_serah_terima_".$i];			

				$amb = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$nosin)->row();
				$data['no_mesin'] 				= $nosin;				
				$data['tgl_bpkb'] 				= $amb->tgl_bpkb;
				$data['status_bj'] 				= "input";
				if(isset($_POST["no_bpkb_".$i]) AND $_POST["no_bpkb_".$i] != ""){
					$data["no_bpkb"] 				= $_POST["no_bpkb_".$i];						 										
				}else{
					$data['no_bpkb']  			= "";					
				}				
				if(isset($_POST["terima_bpkb_".$i])){
					$data['terima_bpkb'] = 'ya';
					$data['tgl_terima_bpkb'] = $tgl;
				}else{
					$data['terima_bpkb'] = '';
					$data['tgl_terima_bpkb'] = '0000-00-00';
				}
				
				$data['created_at'] 			= $waktu;		
				$data['created_by'] 			= $login_id;						
				
				$cek = $this->db->query("SELECT * FROM tr_terima_bj WHERE no_mesin = '$nosin'");
				if($cek->num_rows() > 0){						
					$this->m_admin->update("tr_terima_bj",$data,"no_mesin",$nosin);								
				}else{
					$this->m_admin->insert("tr_terima_bj",$data);								
				}	

				$da['updated_at'] 			= $waktu;		
				$da['updated_by'] 			= $login_id;		
				$da['status_stnk']			= 'terima_md';

				$ce = $this->db->query("SELECT * FROM tr_serah_terima WHERE no_serah_terima = '$no_serah_terima'");
				if($ce->num_rows() > 0){						
					$this->m_admin->update("tr_serah_terima",$da,"no_serah_terima",$no_serah_terima);								
				}else{
					$this->m_admin->insert("tr_serah_terima",$da);								
				}
				$_SESSION['pesan'] 	= "Data has been save successfully";
				$_SESSION['tipe'] 	= "success";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/terima_bpkb'>";			
			}elseif(isset($_POST["edit_".$i])){
				$nosin 								= $_POST["no_mesin_".$i];			
				$no_serah_terima 			= $_POST["no_serah_terima_".$i];			

				$amb = $this->m_admin->getByID("tr_entry_stnk","no_mesin",$nosin)->row();
				$data['no_mesin'] 				= $nosin;				
				$data['tgl_bpkb'] 				= $amb->tgl_bpkb;
				$data['status_bj'] 				= "input";
				if(isset($_POST["no_bpkb_".$i]) AND $_POST["no_bpkb_".$i] != ""){
					$data["no_bpkb"] 				= $_POST["no_bpkb_".$i];						 										
				}else{
					$data['no_bpkb']  			= "";					
				}				
				if(isset($_POST["terima_bpkb_".$i])){
					$data['terima_bpkb'] = 'ya';
					$data['tgl_terima_bpkb'] = $tgl;
				}else{
					$data['terima_bpkb'] = '';
					$data['tgl_terima_bpkb'] = '0000-00-00';
				}
				
				$data['updated_at'] 			= $waktu;		
				$data['updated_by'] 			= $login_id;						
								
				$this->m_admin->update("tr_terima_bj",$data,"no_mesin",$nosin);												

				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/terima_bpkb'>";			
			}else{
				$_SESSION['pesan'] 	= "Gagal, pastikan semua data terisi dengan benar";
				$_SESSION['tipe'] 	= "danger";		
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/terima_bpkb'>";			
			}											
		}			
	}		
	public function cetak_tenda_terima()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "cetak_terima";				
		$this->template($data);			
	}	
}