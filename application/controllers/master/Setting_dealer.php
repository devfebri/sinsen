<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_dealer extends CI_Controller {

    var $tables =   "ms_setting";	
		var $folder =   "master";
		var $page		=		"setting_dealer";
    var $pk     =   "id_setting";
    var $title  =   "Master Data Setting Dealer";

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
		$data['dt_setting'] = $this->db->query("SELECT ms_setting.*,ms_dealer.nama_dealer FROM ms_setting INNER JOIN ms_dealer ON ms_setting.id_dealer = ms_dealer.id_dealer ORDER BY ms_dealer.nama_dealer ASC");
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['dt_dealer'] = $this->m_admin->getSort("ms_dealer","nama_dealer","ASC");			
		$data['set']		= "insert";									
		$this->template($data);	
	}
	
	public function save()
	{		
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id					= $this->session->userdata('id_user');		

		$config['upload_path'] 		= './assets/panel/images/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';
				
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('logo')){
			$logo	= "";
		}else{
			$logo	= $this->upload->file_name;
		}
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('favicon')){
			$favicon	= "";
		}else{
			$favicon	= $this->upload->file_name;
		}
		$data['id_dealer'] 				= $this->input->post('id_dealer');		
		$data['nama_perusahaan'] 	= $this->input->post('nama_perusahaan');		
		$data['nama_kecil'] 			= $this->input->post('nama_kecil');		
		$data['alamat'] 					= $this->input->post('alamat');				
		$data['no_telp'] 					= $this->input->post('no_telp');				
		$data['email'] 						= $this->input->post('email');				
		$data['nama_pimpinan']		= $this->input->post('nama_pimpinan');
		$data['logo']							= $logo;		
		$data['favicon']					= $favicon;		
		$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/setting_dealer/add'>";
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
			$this->m_admin->delete($tabel,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been deleted successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."setting_dealer/user'>";
		}
	}
	
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_user'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_kelurahan'] = $this->m_admin->getSort("ms_kelurahan","kelurahan","ASC");	
		$data['dt_karyawan'] = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE active = 1 ORDER BY nama_lengkap ASC");	
		$data['dt_user_group'] = $this->m_admin->getSort("ms_user_group","user_group","ASC");			
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');

		$config['upload_path'] 		= './assets/panel/images/user/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']				= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';

		$this->upload->initialize($config);
		if($this->upload->do_upload('avatar')){
			$data['avatar']=$this->upload->file_name;
			
			$one = $this->m_admin->getByID($tabel,$pk,$id)->row();			
			unlink("assets/panel/images/user/".$one->avatar); //Hapus Gambar
		}

		$data['id_kelurahan'] 	= $this->input->post('id_kelurahan');		
		$data['id_karyawan_dealer'] 		= $this->input->post('id_karyawan_dealer');		
		$data['id_user_group'] 	= $this->input->post('id_user_group');		
		$data['username'] 			= $this->input->post('username');				
		if($this->input->post('active') == '1'){
			$data['active']	= $this->input->post('active');		
		}else{
			$data['active'] 				= "";
		}
		if($this->input->post('password') <> ''){		
			$data['password'] 			= md5($this->input->post('password'));				
		}
		if($this->input->post('admin_password') <> ''){		
			$data['admin_password']	= $this->input->post('admin_password');			
		}
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user'>";
	}
	
}