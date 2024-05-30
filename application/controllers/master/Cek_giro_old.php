<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek_giro extends CI_Controller {

		var $tables 	=   "ms_cek_giro";	
		var $folder 	=   "master";
		var $page			=		"cek_giro";
		var $pk     	=   "id_cek_giro";
		var $title  	=   "Master Data No Cek & Giro";

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
		$this->load->library('form_validation');

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
		$data['dt_cek_giro'] = $this->m_admin->getAll($this->tables);							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";									
		$this->template($data);	
	}
	public function save()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){						
			$data['id_cek_giro'] 				= $this->input->post('id_cek_giro');					
			$data['tgl_buat'] 				= $this->input->post('tgl_buat');					
			$data['kode_giro'] 				= $this->input->post('kode_giro');					
			$data['bank'] 						= $this->input->post('bank');					
			$data['dari'] 						= $this->input->post('dari');					
			$data['sampai'] 					= $this->input->post('sampai');					
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";							
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro/add'>";			
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}		
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$id 			= $this->input->get('id');		
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{
			$this->db->trans_begin();			
			$this->db->delete($tabel,array($pk=>$id));
			$this->db->trans_commit();			
			$result = 'Success';									

			if($this->db->trans_status() === FALSE){
				$result = 'You can not delete this data because it already used by the other tables';										
				$_SESSION['tipe'] 	= "danger";			
			}else{
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 			= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_cek_giro'] = $this->m_admin->kondisi($tabel,$d);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['tgl_buat'] 				= $this->input->post('tgl_buat');					
			$data['kode_giro'] 				= $this->input->post('kode_giro');					
			$data['bank'] 						= $this->input->post('bank');					
			$data['dari'] 						= $this->input->post('dari');					
			$data['sampai'] 					= $this->input->post('sampai');					
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";							
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/cek_giro'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function cari_id(){
		$rt = $this->m_admin->cari_id("ms_cek_giro","id_cek_giro");
		echo $rt;
	}
	public function t_giro(){
		$id = $this->input->post('id_cek_giro');
		$dq = "SELECT * FROM ms_cek_giro_detail WHERE id_cek_giro = '$id'";
		$data['dt_giro'] = $this->db->query($dq);
		$this->load->view('master/t_giro',$data);
	}
	public function delete_giro(){
		$id 		= $this->input->post('id_cek_giro_detail');		
		$da 		= "DELETE FROM ms_cek_giro_detail WHERE id_cek_giro_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_giro(){
		$id_cek_giro	= $this->input->post('id_cek_giro');
		$no_cek					= $this->input->post('no_cek');		
		$c 			= $this->db->query("SELECT * FROM ms_cek_giro_detail WHERE id_cek_giro ='$id_cek_giro' AND no_cek = '$no_cek'");
		$data['id_cek_giro']		= $this->input->post('id_cek_giro');			
		$data['no_cek']			= $this->input->post('no_cek');			
		if($c->num_rows()==0){
			$this->m_admin->insert('ms_cek_giro_detail',$data);							
		}else{
			$op = $c->row();
			$this->m_admin->update('ms_cek_giro_detail',$data,"id_cek_giro_detail",$op->id_cek_giro_detail);							
		}
		echo "nihil";
	}
}