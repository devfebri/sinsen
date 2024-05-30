<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Koneksi_ksu extends CI_Controller {

    var $tables =   "ms_koneksi_ksu";	
		var $folder =   "master";
		var $page		=		"koneksi_ksu";
    var $pk     =   "id_koneksi_ksu";
    var $title  =   "Master Data Koneksi KSU";
    

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
		$data['dt_koneksi_ksu'] = $this->db->query("SELECT ms_koneksi_ksu.*,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm FROM ms_koneksi_ksu INNER JOIN ms_tipe_kendaraan
							ON ms_koneksi_ksu.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan");
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_ksu'] = $this->m_admin->getSortCond("ms_ksu","ksu","ASC");					
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");					
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function cari_id(){
		$ksu							= $this->input->post('ksu');		
		$pr_num 				= $this->db->query("SELECT * FROM ms_koneksi_ksu ORDER BY id_koneksi_ksu DESC LIMIT 0,1");						
		$kode = $this->m_admin->cari_id("ms_koneksi_ksu","id_koneksi_ksu");
		//$kode = "123";
		echo $kode;
	}
	public function t_ksu(){
		$id = $this->input->post('id_koneksi_ksu');
		$dq = "SELECT ms_koneksi_ksu_detail.*,ms_ksu.* FROM ms_koneksi_ksu_detail INNER JOIN ms_ksu ON ms_koneksi_ksu_detail.id_ksu = ms_ksu.id_ksu
						WHERE ms_koneksi_ksu_detail.id_koneksi_ksu = '$id'";
		$data['dt_ksu'] = $this->db->query($dq);
		$this->load->view('master/t_ksu',$data);
	}
	public function delete_ksu(){
		$id 		= $this->input->post('id_koneksi_ksu_detail');		
		$da 		= "DELETE FROM ms_koneksi_ksu_detail WHERE id_koneksi_ksu_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_ksu(){
		$id_koneksi_ksu	= $this->input->post('id_koneksi_ksu');
		$id_ksu					= $this->input->post('id_ksu');		
		$c 			= $this->db->query("SELECT * FROM ms_koneksi_ksu_detail WHERE id_ksu ='$id_ksu' AND id_koneksi_ksu = '$id_koneksi_ksu'");
		if($c->num_rows()==0){
			$data['id_koneksi_ksu']		= $this->input->post('id_koneksi_ksu');			
			$data['id_ksu']			= $this->input->post('id_ksu');			
			$this->m_admin->insert('ms_koneksi_ksu_detail',$data);							
			echo "nihil";
		}else{
			echo "nothing";
		}
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_koneksi_ksu'] 			= $this->input->post('id_koneksi_ksu');
			$data['id_tipe_kendaraan'] 		= $this->input->post('id_tipe_kendaraan');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;						
			$this->m_admin->insert($tabel,$data);	
			//echo "nihil";		
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/koneksi_ksu'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}	
	}
	public function delete()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{				
			$da 		= $this->db->query("DELETE FROM ms_koneksi_ksu_detail WHERE id_koneksi_ksu_detail = '$id'");
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/koneksi_ksu'>";
		}
	}
	public function ajax_bulk_delete()
	{
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$list_id 		= $this->input->post('id');
		foreach ($list_id as $id) {
			$this->m_admin->delete($tabel,$pk,$id);
		}
		echo json_encode(array("status" => TRUE));
	}
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_koneksi_ksu'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_ksu'] = $this->m_admin->getSortCond("ms_ksu","ksu","ASC");					
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");					
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');

		$tabel					= $this->tables;
		$pk 						= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_koneksi_ksu'] 			= $this->input->post('id_koneksi_ksu');
			$data['id_tipe_kendaraan'] 		= $this->input->post('id_tipe_kendaraan');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/koneksi_ksu'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}