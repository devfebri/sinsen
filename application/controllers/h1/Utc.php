<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Utc extends CI_Controller {
    var $tables =   "tr_utc";	
		var $folder =   "h1";
		var $page		=		"utc";
    var $pk     =   "id_item";
    var $title  =   "UTC";
	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');		
		$this->load->library('csvimport');
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
		$data['dt_utc'] = $this->db->query("SELECT tr_utc.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_utc 
											LEFT JOIN ms_tipe_kendaraan ON tr_utc.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
											LEFT JOIN ms_warna ON tr_utc.id_warna = ms_warna.id_warna 
											ORDER BY tr_utc.id_item ASC");			
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
    if($_FILES['userfile']['size'] > 0)
    {
        $file = fopen($filename,"r");
        $is_header_removed = FALSE;
				$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum1 = 1;$isi="";
        while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
        {
            // if(!$is_header_removed){
            //     $is_header_removed = TRUE;
            //     continue;
            // }            
          $row = array(
              'id_item'     =>  !empty($importdata[0])?$importdata[0]:'',
              'id_tipe_kendaraan'     =>  !empty($importdata[1])?$importdata[1]:'',
              'deskripsi_tipe'         =>  !empty($importdata[2])?$importdata[2]:'',
              'id_warna'        =>  !empty($importdata[3])?$importdata[3]:'',
              'deskripsi_warna'       =>  !empty($importdata[4])?$importdata[4]:'',
              'nama_pasar'       =>  !empty($importdata[5])?$importdata[5]:'',
              'cc_motor'       =>  !empty($importdata[6])?$importdata[6]:'',
              'class'       =>  !empty($importdata[7])?$importdata[7]:'',
              'tgl_awal'       =>  !empty($importdata[8])?$importdata[8]:'',
              'tgl_akhir'       =>  !empty($importdata[9])?$importdata[9]:'',
              'status_wl'       =>  !empty($importdata[10])?$importdata[10]:'',
              'qty_wl'       =>  !empty($importdata[11])?$importdata[11]:'',                
              'alt_1'       =>  !empty($importdata[12])?$importdata[12]:'',                
              'alt_2'       =>  !empty($importdata[13])?$importdata[13]:'',                
              'alt_3'       =>  !empty($importdata[14])?$importdata[14]:''                
          );
        
        $cek = $this->db->query("SELECT * FROM tr_utc WHERE id_tipe_kendaraan = '$importdata[1]' AND id_warna = '$importdata[3]'");
				if($cek->num_rows() == 0){
					$this->db->trans_begin();
					$this->db->insert('tr_utc', $row);
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
				//insert tabel tipe
				$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
				$login_id		= $this->session->userdata('id_user');
				$row2 = array(            
            'id_tipe_kendaraan'     =>  !empty($importdata[1])?$importdata[1]:'',
            'deskripsi_ahm'         =>  !empty($importdata[2])?$importdata[2]:'',            
            'tipe_ahm'       =>  !empty($importdata[5])?$importdata[5]:'',
            'created_at'       =>  $waktu,
            'created_by'       =>  $login_id,
            'active'       =>  "1"            
        );
				$cek2 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$importdata[1]'");
				if($cek2->num_rows() == 0){
					$this->db->trans_begin();
					$this->db->insert('ms_tipe_kendaraan', $row2);
					if(!$this->db->trans_status()){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_commit();
					}
				}
				//insert tabel item
				$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
				$login_id		= $this->session->userdata('id_user');
				$row3 = array(            
            'id_tipe_kendaraan'     =>  !empty($importdata[1])?$importdata[1]:'',
            'id_item'         =>  !empty($importdata[0])?$importdata[0]:'',            
            'id_warna'       =>  !empty($importdata[3])?$importdata[3]:'',
            'created_at'       =>  $waktu,
            'created_by'       =>  $login_id,
            'active'       =>  "1"            
        );
				$cek3 = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$importdata[1]' AND id_warna = '$importdata[3]'");
				if($cek3->num_rows() == 0){
					$this->db->trans_begin();
					$this->db->insert('ms_item', $row3);
					if(!$this->db->trans_status()){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_commit();
					}
				}
			}
			fclose($file);
			$_SESSION['pesan'] 	= $jum." Data yang anda import, sebanyak ".$no2." berhasil dan ".$no1." gagal import (".$isi.")";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/utc'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/utc'>";	
		}		
  }
}