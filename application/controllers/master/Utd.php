<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utd extends CI_Controller {

	var $tables =   "ms_utd";	
	var $folder =   "master";
	var $page		="utd";
	var $pk     =   "id_utd";
	var $title  =   "Unit Type Dummy (UTD)";

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
		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}		
		$this->load->library('csvimport');

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
			$this->load->view($this->folder."/".$data['isi']);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = "utd/utd";		
		$data['title']	= $this->title;		
		$data['dt_utd'] = $this->db->query("SELECT * FROM ms_utd");
		$this->template($data);		
	}
	public function upload()
	{				
		$data['isi']    = "utd/upload";		
		$data['title']	= "Upload File UTD";	
		$this->template($data);		
	}


	function import_db(){
		$filename = $_FILES["userfile"]["tmp_name"];
		if($_FILES['userfile']['size'] > 0)
		{
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;			
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				
				$row = array(
					"kode_type_dummy" => !empty($importdata[0])?$importdata[0]:'',
					"deskripsi_type_dummy" => !empty($importdata[1])?$importdata[1]:'',
					"kode_warna_dummy" => !empty($importdata[2])?$importdata[2]:'',
					"deskripsi_warna_dummy" => !empty($importdata[3])?$importdata[3]:'',
					"kode_type_actual" => !empty($importdata[4])?$importdata[4]:'',
					"deskripsi_kode_tipe_actual" => !empty($importdata[5])?$importdata[5]:'',
					"kode_warna_actual" => !empty($importdata[6])?$importdata[6]:'',
					"deskripsi_warna_actual" => !empty($importdata[7])?$importdata[7]:'',
					"tanggal_mulai_indent" => !empty($importdata[8])?$importdata[8]:'',
					"tanggal_berakhir_indent" => !empty($importdata[9])?$importdata[9]:'',
					"begin_effective_date" => !empty($importdata[10])?$importdata[10]:'',
					"end_effective_date" => !empty($importdata[11])?$importdata[11]:'',
					"new_model_type" => !empty($importdata[12])?$importdata[12]:'',
					"new_model_color" => !empty($importdata[13])?$importdata[13]:'',
					"date_created" => "",
					"date_updated" => "",
				);


				$this->db->where('kode_type_actual', $importdata[4]);
				$this->db->where('kode_warna_actual', $importdata[6]);
				$cek = $this->db->get('ms_utd');
				if($cek->num_rows() == 0){
					$this->db->trans_begin();
					$row["date_created"] = get_waktu();
					$this->db->insert('ms_utd', $row);
					if(!$this->db->trans_status()){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_commit();
					}
				} else {
					$this->db->trans_begin();
					$row["date_created"] = $cek->row()->date_created;
					$row["date_updated"] = get_waktu();

					$this->db->where('kode_type_actual', $importdata[4]);
					$this->db->where('kode_warna_actual', $importdata[6]);
					$this->db->update('ms_utd', $row);
					if(!$this->db->trans_status()){
						$this->db->trans_rollback();
					}else{
						$this->db->trans_commit();
					}
				}
			}
			fclose($file);
			$_SESSION['pesan'] 	= "Data berhasil diimport";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/utd'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/utd'>";	
		}				
  }
}