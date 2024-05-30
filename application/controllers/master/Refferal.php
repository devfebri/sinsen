<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refferal extends CI_Controller {

	var $tables =   "ms_refferal";	
	var $folder =   "master";
	var $page		=		"refferal";
	var $pk     =   "refferal_id";
	var $title  =   "Master Refferal ID";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_refferal');		
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
		$data['dt_refferal'] = $this->db->query("SELECT * FROM ms_refferal ORDER BY refferal_id DESC");			
		$this->template($data);		
	}
	public function ajax_list()
	{
		$list = $this->m_refferal->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$r = $this->m_admin->getByID("ms_refferal","refferal_id",$isi->refferal_id)->row();
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->refferal_id;
			$row[] = $isi->no_rangka;						
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_refferal->count_all(),
						"recordsFiltered" => $this->m_refferal->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
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

    if($size > 0 AND $name_r[1] == 'csv')
    {		
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;
			$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum1 = 1;$isi="";
			while(($importdata = fgetcsv($file, 10000, ",")) !== FALSE)
			{
				$row = array(
					'refferal_id'    =>  !empty($importdata[0])?$importdata[0]:'',					
					'no_rangka'       =>  !empty($importdata[1])?$importdata[1]:''
				);

				$cek = $this->db->query("SELECT * FROM ms_refferal WHERE refferal_id = '$importdata[0]'");
				if($cek->num_rows() == 0){
					$this->db->trans_begin();
					$this->db->insert('ms_refferal', $row);
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
			$_SESSION['pesan'] 	= $jum." Data yang anda import. <br>
			 Berhasil = ".$no2." data.  <br>
			 Gagal = ".$no1." data (".$isi.")";
			 
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/refferal'>";	
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/refferal'>";	
		}				
  }
}