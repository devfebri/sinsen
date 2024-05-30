<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinsi extends CI_Controller {

    var $tables =   "ms_provinsi";	
		var $folder =   "master";
		var $page		=		"provinsi";
    var $pk     =   "id_provinsi";
    var $title  =   "Master Data Provinsi";

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
		$data['dt_provinsi'] = $this->m_admin->getAll($this->tables);							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";
		$data['akses'] = "<button type='submit' name='save' value='save' class='btn btn-info btn-flat'><i class='fa fa-edit'></i> Save</button>                
                  		<button type='reset' class='btn btn-default btn-flat'><i class='fa fa-refresh'></i> Cancel</button>";												
		$this->template($data);	
	}
	
	public function test()
	{				
		// $data['isi']    = $this->page;		
		// $data['title']	= $this->title;		
		// $data['set']		= "rawbt";
		// $this->template($data);	

		// echo '123';
		// echo sys_get_temp_dir();

		// echo sys_get_temp_dir()."/Test001";
		// echo realpath("ci_session0cc57cdf9ced5af94e93273df2277bbb8f8a7c36");
		// delete file pakai unlink(path);
		// =======
		// $temp_file = tempnam(sys_get_temp_dir(), 'Test001');
		// echo '<br>'.$temp_file;
		// output: /tmp/Test001Ajk4Q9
	}

	public function print_raw(){
		$this->load->view('sample_receipt');
	}

	public function save()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_provinsi'] 			= $this->input->post('id_provinsi');
			$data['provinsi'] 	= $this->input->post('provinsi');		
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/provinsi/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/provinsi'>";
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
		$data['dt_provinsi'] = $this->m_admin->kondisi($tabel,$d);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";						
		$data['akses'] = "<button type='submit' name='process' value='edit' class='btn btn-info btn-flat'><i class='fa fa-edit'></i> Update</button>                
                  		<button type='reset' class='btn btn-default btn-flat'><i class='fa fa-refresh'></i> Cancel</button>";			
		$this->template($data);	
	}
	public function update()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_provinsi'] 	= $this->input->post('id_provinsi');
			$data['provinsi'] 		= $this->input->post('provinsi');		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/provinsi'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}