<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sipb extends CI_Controller {
	var $tables =   "tr_sipb";	
	var $folder =   "h1";
	var $page		=		"sipb";
	var $pk     =   "no_sipb";
	var $title  =   "Surat Izin Pengiriman Barang (SIPB)";
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
		$this->load->library('csvimport');
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
		$data['dt_sipb'] = $this->db->query("SELECT tr_sipb.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_sipb 
								LEFT JOIN ms_tipe_kendaraan ON tr_sipb.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
								LEFT JOIN ms_warna ON tr_sipb.id_warna = ms_warna.id_warna ORDER BY CONCAT_WS('-', RIGHT(tgl_sipb,4),MID(tgl_sipb,3,2),LEFT(tgl_sipb,2)) DESC");			
		$this->template($data);		
	}
	public function upload()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "upload";		
		$this->template($data);		
	}
	function import_db(){
		$filename = $_FILES["userfile"]["tmp_name"];
		$name 		= $_FILES["userfile"]["name"];
		$type 		= $_FILES["userfile"]["type"];
		$size 		= $_FILES["userfile"]["size"];
		$name_r   = explode('.', $name);
    if($size > 0 AND $name_r[1] == 'SIPB')
    {		
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;
			$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum1 = 1;$isi="";
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				// if(!$is_header_removed){
				// 	$is_header_removed = TRUE;
				// 	continue;
				// }
				$row = array(
					'no_sipb'    =>  !empty($importdata[0])?$importdata[0]:'',
					'tgl_sipb'     =>  !empty($importdata[1])?$importdata[1]:'',
					'no_spes'         =>  !empty($importdata[2])?$importdata[2]:'',
					'tgl_spes'        =>  !empty($importdata[3])?$importdata[3]:'',
					'id_tipe_kendaraan'       =>  !empty($importdata[4])?$importdata[4]:'',
					'id_warna'       =>  !empty($importdata[5])?$importdata[5]:'',
					'jumlah'       =>  !empty($importdata[6])?$importdata[6]:'',
					'harga'       =>  !empty($importdata[7])?$importdata[7]:'',
					'disc'       =>  !empty($importdata[8])?$importdata[8]:'',
					'q_flag'       =>  !empty($importdata[9])?$importdata[9]:'',
					'no_po_md'       =>  !empty($importdata[10])?$importdata[10]:'',
					'dealer_qq'       =>  !empty($importdata[11])?$importdata[11]:'',
					'amount'       =>  !empty($importdata[12])?$importdata[12]:'',
					'ppn'       =>  !empty($importdata[13])?$importdata[13]:'',
					'pph'       =>  !empty($importdata[14])?$importdata[14]:'',
					'nama_dealer'       =>  !empty($importdata[15])?$importdata[15]:'',
					'provinsi'       =>  !empty($importdata[16])?$importdata[16]:''
				);
				$cek = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[0]' AND id_tipe_kendaraan = '$importdata[4]' AND id_warna = '$importdata[5]'");
				if($cek->num_rows() == 0){
					$this->db->trans_begin();
					$this->db->insert('tr_sipb', $row);
					if(!$this->db->trans_status()){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_commit();
					}
					$no2++;
				}else{
					if($isi==""){
						$isi = $jum1;
					}else{
						$isi = $isi.",".$jum1;
					}
					$no1++;					
				}
				$jum++;
				$jum1++;
			}
			fclose($file);
			$_SESSION['pesan'] 	= $jum." Data yang anda import. Berhasil = ".$no2." data. Gagal = ".$no1." data (".$isi.")";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sipb'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/sipb'>";	
		}				
  }
}