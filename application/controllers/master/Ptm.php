<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ptm extends CI_Controller {

	var $tables =   "ms_ptm";	
	var $folder =   "master";
	var $page		=		"ptm";
	var $pk     =   "no_ptm";
	var $title  =   "Part Tipe Motor (PTM)";

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
		$data['dt_ptm'] = $this->db->query("SELECT * FROM ms_ptm");
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
					'tipe_motor'    =>  !empty($importdata[0])?$importdata[0]:'',
					'tipe_produksi'     =>  !empty($importdata[1])?$importdata[1]:'',
					'deskripsi'         =>  !empty($importdata[2])?$importdata[2]:'',
					'tgl_berlaku'        =>  !empty($importdata[3])?$importdata[3]:''					
				);


				$this->db->trans_begin();
				$this->db->insert('ms_ptm', $row);
				if(!$this->db->trans_status()){
					$this->db->trans_rollback();
				}else{
					$this->db->trans_commit();
				}
			}
			fclose($file);
			$_SESSION['pesan'] 	= "Data berhasil diimport";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/ptm'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/ptm'>";	
		}				
  }
}