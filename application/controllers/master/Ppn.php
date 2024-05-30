<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppn extends CI_Controller {
    var $tables =   "ms_ppn";	
    var $folder =   "master";
    var $page	=   "ppn";
    var $pk     =   "id_ppn";
    var $title  =   "Master Setting PPN";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_ppn');		
		//===== Load Library =====
		$this->load->library('upload');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false' OR $sess=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
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

	public function ajax_list()
	{
		$list = $this->m_ppn->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {
			$id_menu = $this->m_admin->getMenu($this->page);
			$group 	= $this->session->userdata("group");
			$edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
			// $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $isi->start_date;
			$row[] = $isi->end_date;
			$row[] = $isi->persen_ppn;
			$button ="<a data-toggle=\"tooltip\" $edit title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"master/ppn/edit?id=$isi->id_ppn->id\"><i class=\"fa fa-edit\"></i></a>";
			// $row[] = "";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_ppn->count_all(),
			"recordsFiltered" => $this->m_ppn->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
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

	public function save()
	{		
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		// $cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();

		$validasi = $this->db->query("select count(1) from ms_ppn where start_date between '$this->input->post('start_date')' and '$this->input->post('end_date')' ");
		if(1){
			$data['start_date'] 	= $this->input->post('start_date');		
			$data['end_date'] 	= $this->input->post('end_date');		
			$data['persen_ppn'] 	= $this->input->post('persen_ppn');
			$data['persen_1'] 	= $this->input->post('persen_ppn')/100;				
			$data['persen_2']	= ($this->input->post('persen_ppn')/100)+1;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/ppn/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	/*
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/kelurahan'>";
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
	*/

	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['get_data'] = $this->m_admin->kondisi($tabel,$d)->row();		
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";					
		$this->template($data);	
	}

	public function update()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 1){
			$data['perihal'] 	= $this->input->post('perihal');		
			$data['untuk'] 	= $this->input->post('untuk');		
			$data['isi'] 			= $this->input->post('isi');				
			$data['tgl_aktif'] 			= $this->input->post('start_date');				
			$data['tgl_expired']		= $this->input->post('end_date');
			$active = 1;
			if($this->input->post('active') == null) { 
				$active = 0;
			}			
			$data['active']		= $active;		
			$data['updated_at']		= date('Y-m-d H:i:s');		
			$data['updated_by']		= $this->session->userdata('id_user');
			
			// $this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/ppn'>";
		}else{
			$_SESSION['pesan'] 	= "Something Wrong!";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}