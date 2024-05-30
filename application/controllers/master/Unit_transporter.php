<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_transporter extends CI_Controller {

    var $tables =   "ms_unit_transporter";	
		var $folder =   "master";
		var $page		=		"unit_transporter";
    var $pk     =   "id_unit_transporter";
    var $title  =   "Master Data Unit Trasporter";

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
		$data['dt_unit_transporter'] = $this->db->query("SELECT ms_unit_transporter.*,ms_vendor.vendor_name FROM ms_unit_transporter INNER JOIN ms_vendor 
																ON ms_unit_transporter.id_vendor=ms_vendor.id_vendor");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['dt_vendor'] = $this->m_admin->getSort("ms_vendor","vendor_name","ASC");	
		$data['set']		= "insert";									
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
			$data['id_unit_transporter'] 	= $this->input->post('id_unit_transporter');		
			$data['id_vendor'] 	= $this->input->post('id_vendor');		
			$data['no_polisi'] 		= $this->input->post('no_polisi');				
			$data['merk'] 		= $this->input->post('merk');				
			$data['warna'] 		= $this->input->post('warna');				
			$data['kapasitas'] 		= $this->input->post('kapasitas');	
			if($this->input->post('keur') == '1') $data['keur'] = $this->input->post('keur');		
				else $data['keur'] 		= "";	
			if($this->input->post('stnk') == '1') $data['stnk'] = $this->input->post('stnk');		
				else $data['stnk'] 		= "";	
			if($this->input->post('izin_operasi') == '1') $data['izin_operasi'] = $this->input->post('izin_operasi');		
				else $data['izin_operasi'] 		= "";	
			if($this->input->post('tools') == '1') $data['tools'] = $this->input->post('tools');		
				else $data['tools'] 		= "";	
			if($this->input->post('apar') == '1') $data['apar'] = $this->input->post('apar');		
				else $data['apar'] 		= "";
			if($this->input->post('segitiga') == '1') $data['segitiga'] = $this->input->post('segitiga');		
				else $data['segitiga'] 		= "";	
			if($this->input->post('kotak_p3k') == '1') $data['kotak_p3k'] = $this->input->post('kotak_p3k');		
				else $data['kotak_p3k'] 		= "";	
			if($this->input->post('safety_belt') == '1') $data['safety_belt'] = $this->input->post('safety_belt');		
				else $data['safety_belt'] 		= "";	
			if($this->input->post('izin_trayek') == '1') $data['izin_trayek'] = $this->input->post('izin_trayek');		
				else $data['izin_trayek'] 		= "";	
			if($this->input->post('no_izin') == '1') $data['no_izin'] = $this->input->post('no_izin');		
				else $data['no_izin'] 		= "";	
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/unit_transporter/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/unit_transporter'>";
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
		$data['dt_unit_transporter'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_vendor'] = $this->m_admin->getSort("ms_vendor","vendor_name","ASC");			
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel				= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_unit_transporter'] 	= $this->input->post('id_unit_transporter');		
			$data['id_vendor'] 	= $this->input->post('id_vendor');		
			$data['no_polisi'] 		= $this->input->post('no_polisi');				
			$data['merk'] 		= $this->input->post('merk');				
			$data['warna'] 		= $this->input->post('warna');				
			$data['kapasitas'] 		= $this->input->post('kapasitas');	
			if($this->input->post('keur') == '1') $data['keur'] = $this->input->post('keur');		
				else $data['keur'] 		= "";	
			if($this->input->post('stnk') == '1') $data['stnk'] = $this->input->post('stnk');		
				else $data['stnk'] 		= "";	
			if($this->input->post('izin_operasi') == '1') $data['izin_operasi'] = $this->input->post('izin_operasi');		
				else $data['izin_operasi'] 		= "";	
			if($this->input->post('tools') == '1') $data['tools'] = $this->input->post('tools');		
				else $data['tools'] 		= "";	
			if($this->input->post('apar') == '1') $data['apar'] = $this->input->post('apar');		
				else $data['apar'] 		= "";
			if($this->input->post('segitiga') == '1') $data['segitiga'] = $this->input->post('segitiga');		
				else $data['segitiga'] 		= "";	
			if($this->input->post('kotak_p3k') == '1') $data['kotak_p3k'] = $this->input->post('kotak_p3k');		
				else $data['kotak_p3k'] 		= "";	
			if($this->input->post('safety_belt') == '1') $data['safety_belt'] = $this->input->post('safety_belt');		
				else $data['safety_belt'] 		= "";	
			if($this->input->post('izin_trayek') == '1') $data['izin_trayek'] = $this->input->post('izin_trayek');		
				else $data['izin_trayek'] 		= "";	
			if($this->input->post('no_izin') == '1') $data['no_izin'] = $this->input->post('no_izin');		
				else $data['no_izin'] 		= "";
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/unit_transporter'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}