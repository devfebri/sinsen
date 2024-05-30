<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends CI_Controller {

    var $tables =   "ms_kecamatan";	
		var $folder =   "master";
		var $page		=		"kecamatan";
    var $pk     =   "id_kecamatan";
    var $title  =   "Master Data Kecamatan";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		$this->load->model('m_kecamatan');			
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
		$data['dt_kecamatan'] = $this->db->query("SELECT * FROM ms_kecamatan LEFT JOIN ms_kabupaten 
																ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten ORDER BY kabupaten,kecamatan ASC");							
		$this->template($data);	
	}
	public function ajax_list()
	{
		$list = $this->m_kecamatan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$r = $this->m_admin->getByID("ms_kabupaten","id_kabupaten",$isi->id_kabupaten)->row();
			$no++;
			$id_menu = $this->m_admin->getMenu($this->page);
			$group 	= $this->session->userdata("group");
			$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
			$delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            

			$row = array();
			$row[] = $no;
			$row[] = $isi->id_kecamatan;
			$row[] = $isi->kecamatan;			
			$row[] = $r->kabupaten;
			$row[] = "<a data-toggle=\"tooltip\" $delete title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"master/kecamatan/delete?id=$isi->id_kecamatan\"><i class=\"fa fa-trash-o\"></i></a>
                <a data-toggle=\"tooltip\" $edit title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"master/kecamatan/edit?id=$isi->id_kecamatan\"><i class=\"fa fa-edit\"></i></a>";

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_kecamatan->count_all(),
						"recordsFiltered" => $this->m_kecamatan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['dt_kabupaten'] = $this->m_admin->getSort("ms_kabupaten","kabupaten","ASC");	
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function save()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_kecamatan'] 	= $this->input->post('id_kecamatan');		
			$data['id_kabupaten'] 	= $this->input->post('id_kabupaten');		
			$data['kecamatan'] 		= $this->input->post('kecamatan');				
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kecamatan/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kecamatan'>";
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
		$data['dt_kecamatan'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_kabupaten'] = $this->m_admin->getSort("ms_kabupaten","kabupaten","ASC");			
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_kabupaten'] 	= $this->input->post('id_kabupaten');		
			$data['kecamatan'] 		= $this->input->post('kecamatan');				
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kecamatan'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}