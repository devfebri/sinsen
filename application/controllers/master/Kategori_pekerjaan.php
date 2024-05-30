<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_pekerjaan extends CI_Controller {

	var $tables = "ms_h2_kategori_pekerjaan";	
	var $folder = "master";
	var $page   = "kategori_pekerjaan";
	var $pk     = "id";
	var $title  = "Master Kategori Pekerjaan";

	public function __construct()
	{		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}else{
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
		$this->db->order_by('id','DESC');
		$data['data']	= $this->db->get('ms_h2_kategori_pekerjaan');
		$this->template($data);	
	}

	public function add()
	{				
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";									
		$this->template($data);	
	}
	
	public function save()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_kategori'] = $this->input->post('id_kategori');	
			$data['kategori']        = $this->input->post('kategori');	

			$this->db->trans_begin();
				$this->m_admin->insert($tabel,$data);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kategori_pekerjaan'>";			
	      	}					
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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kategori_pekerjaan'>";
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
		$id              = $this->input->get('id');
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'edit';
		$data['row']     = $this->db->get_where('ms_h2_kategori_pekerjaan',['id'=>$id]);
		$data['set']     = "form";									
		$this->template($data);	
	}

	public function save_edit()
	{		
		$waktu          = gmdate("y-m-d H:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek==1){
			$data['id_kategori'] = $this->input->post('id_kategori');	
			$data['kategori']        = $this->input->post('kategori');
			
			$this->db->trans_begin();
				$this->db->update('ms_h2_kategori_pekerjaan',$data,['id'=>$id]);
			if ($this->db->trans_status() === FALSE)
	      	{
				$this->db->trans_rollback();
				$_SESSION['pesan'] 	= "Something went wrong";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
	      	}
	      	else
	      	{
	        	$this->db->trans_commit();
	        	$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kategori_pekerjaan'>";			
	      	}					
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}		
	}
	
	public function detail()
	{				
		$id              = $this->input->get('id');
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'detail';
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","id_tipe_kendaraan","ASC");
		$data['kpb']     = $this->db->get_where('ms_kpb',['id_tipe_kendaraan'=>$id]);
		$data['details'] = $this->db->get_where('ms_kpb_detail',['id_tipe_kendaraan'=>$id])->result();
		$data['set']     = "form";									
		$this->template($data);	
	}
}