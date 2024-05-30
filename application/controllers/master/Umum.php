<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umum extends CI_Controller {
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
	protected function template($page, $data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
			$this->load->view('panel/template/header',$data);
			$this->load->view('panel/template/aside');			
			$this->load->view("panel/$page");		
			$this->load->view('panel/template/footer');
		}
	}

	public function index()
	{		
		$page			= "umum";
		$tabel			= "tbl_setting";
		$data['isi']    = "umum";		
		$data['title']	= "Pengaturan";															
		$data['set']	= "view";		
		$data['dt_setting'] = $this->m_admin->getAll($tabel);							
		$this->template($page, $data);	
	}

	public function save()
	{		
		$tabel				= "tbl_setting";
		$pk 				= "id_setting";
		$id 				= "1";
		$config['upload_path'] 		= './assets/panel/images/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg|bmp';
		$config['max_size']			= '2000';
		$config['max_width']  		= '2000';
		$config['max_height']  		= '1024';
				
		$this->upload->initialize($config);
		if($this->upload->do_upload('logo')){
			$data['logo']=$this->upload->file_name;					
		}
		if($this->upload->do_upload('favicon')){
			$data['favicon']=$this->upload->file_name;					
		}

		$data['nama_perusahaan']	= $this->input->post('nama_perusahaan');			
		$data['nama_kecil']			= $this->input->post('nama_kecil');								
		$data['nama_pimpinan']		= $this->input->post('nama_pimpinan');										
		$data['alamat']				= $this->input->post('alamat');
		$data['no_telp']			= $this->input->post('no_telp');
		$data['email']				= $this->input->post('email');
		$data['twitter']			= $this->input->post('twitter');
		$data['facebook']			= $this->input->post('facebook');
		$data['google']				= $this->input->post('google');
		$data['running_text']		= $this->input->post('running_text');										
		$data['ketentuan']			= $this->input->post('ketentuan');										
		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data berhasil diubah";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."adm/umum'>";
	}
}