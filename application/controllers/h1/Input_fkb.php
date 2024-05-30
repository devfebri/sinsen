<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Input_fkb extends CI_Controller {
    var $tables =   "tr_input_fkb";	
		var $folder =   "h1";
		var $page		=		"input_fkb";
    var $pk     =   "id_input_fkb";
    var $title  =   "Input FKB";
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
		$this->template($data);			
	}	
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "insert";				
		$this->template($data);			
	}		
	public function check()
	{				
		$id = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= "Check ".$this->title;															
		$data['dt_fkb'] = $this->m_admin->getByID("tr_fkb","no_surat",$id);
		$data['set']		= "check";				
		$this->template($data);			
	}
	public function edit()
	{				
		$id = $this->input->get("id");
		$data['isi']    = $this->page;		
		$data['title']	= "Edit ".$this->title;															
		$data['dt_fkb'] = $this->db->query("SELECT * FROM tr_input_fkb INNER JOIN tr_fkb ON tr_input_fkb.no_surat=tr_fkb.no_surat
			WHERE tr_input_fkb.no_surat = '$id'");
		$data['set']		= "edit";				
		$this->template($data);			
	}
	public function save()
	{				
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_surat		= $this->input->post("no_surat");
		$file_name	= $this->input->post("nama_file");
		$id_input_fkb = $this->m_admin->cari_id("tr_input_fkb","id_input_fkb");
		foreach($no_surat AS $key => $val){
			$no_surat = $_POST['no_surat'][$key];
			$no_mesin = $_POST['no_mesin'][$key];
			if(isset($_POST['check_fkb'][$key])){
				$data["no_mesin"] 		= $no_mesin;
				$data["id_input_fkb"] = $id_input_fkb;
				$this->m_admin->insert("tr_input_fkb_detail",$data);				
			}					
		}
		$da['id_input_fkb'] = $id_input_fkb;
		$da['no_surat'] 		= $no_surat;
		$da['file_name'] 		= $file_name;
		$da['created_by']		= $login_id;
		$da['created_at']		= $waktu;
		$da['status']				= "open";
		$this->m_admin->insert("tr_input_fkb",$da);				
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/input_fkb'>";
	}
	public function update()
	{
		$save = $this->input->post("save");
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$no_surat		= $this->input->post("no_surat");
		$file_name	= $this->input->post("nama_file");
		$id_input_fkb = $this->input->post("id");
			
		if($save == 'save'){							
			foreach($no_surat AS $key => $val){
				$no_surat = $_POST['no_surat'][$key];
				$no_mesin = $_POST['no_mesin'][$key];
				if(isset($_POST['check_fkb'][$key])){								
					$cek = $this->db->query("SELECT * FROM tr_input_fkb_detail INNER JOIN tr_input_fkb ON tr_input_fkb_detail.id_input_fkb = tr_input_fkb.id_input_fkb 
									WHERE tr_input_fkb_detail.no_mesin = '$no_mesin' AND tr_input_fkb.no_surat = '$no_surat'");
					if($cek->num_rows() == 0){
						$data["no_mesin"] 		= $no_mesin;
						$data["id_input_fkb"] = $id_input_fkb;
						$this->m_admin->insert("tr_input_fkb_detail",$data);				
					}				
				}else{
					$cek2 = $this->db->query("SELECT * FROM tr_input_fkb_detail INNER JOIN tr_input_fkb ON tr_input_fkb_detail.id_input_fkb = tr_input_fkb.id_input_fkb 
									WHERE tr_input_fkb_detail.no_mesin = '$no_mesin' AND tr_input_fkb.no_surat = '$no_surat'");
					if($cek2->num_rows() > 0){
						$amb = $cek2->row();					
						$id_input_fkb_detail = $amb->id_input_fkb_detail;
						$this->db->query("DELETE FROM tr_input_fkb_detail WHERE no_mesin = '$no_mesin'");								
					}
				}					
			}
			$id_input_fkb 			= $id_input_fkb;						
			$da['updated_by']		= $login_id;
			$da['updated_at']		= $waktu;
			$da['status']				= "open";
			$this->m_admin->update("tr_input_fkb",$da,"id_input_fkb",$id_input_fkb);				
		}else{
			$id_input_fkb 			= $id_input_fkb;						
			$da['updated_by']		= $login_id;
			$da['updated_at']		= $waktu;
			$da['status']				= "close";
			$this->m_admin->update("tr_input_fkb",$da,"id_input_fkb",$id_input_fkb);				
		}
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";		
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/input_fkb'>";
	}
}