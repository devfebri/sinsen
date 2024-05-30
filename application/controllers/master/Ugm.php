<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ugm extends CI_Controller {

	var $tables =   "ms_ugm";	
	var $folder =   "master";
	var $page		=		"ugm";
	var $pk     =   "no_ugm";
	var $title  =   "Unit Group Motor (UGM)";

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
			$this->load->view($this->folder."/".$this->page);		
			$this->load->view('template/footer');
		}
	}

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "view";
		$data['dt_ugm'] = $this->db->query("SELECT * FROM ms_ugm");
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
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				// if(!$is_header_removed){
				// 	$is_header_removed = TRUE;
				// 	continue;
				// }

				$row = array(
					'id_tipe_kendaraan'    =>  !empty($importdata[0])?$importdata[0]:'',
					'deskripsi'     =>  !empty($importdata[1])?$importdata[1]:'',
					'segment'         =>  !empty($importdata[2])?$importdata[2]:'',
					'kategori'        =>  !empty($importdata[3])?$importdata[3]:'',					
					'tipe_ahm'        =>  !empty($importdata[4])?$importdata[4]:''					
				);


				$cek = $this->m_admin->getByID("ms_ugm","id_tipe_kendaraan",$importdata[0]);
				if($cek->num_rows() == 0){
					$this->db->trans_begin();
					$this->db->insert('ms_ugm', $row);
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/ugm'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/ugm'>";	
		}				
  }
}