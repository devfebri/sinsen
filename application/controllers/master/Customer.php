<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    var $tables =   "ms_customer";	
		var $folder =   "master";
		var $page		=		"customer";
    var $pk     =   "id_customer";
    var $title  =   "Master Data Customer";
    

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
		$data['dt_customer'] = $this->db->query("SELECT * FROM ms_customer LEFT JOIN ms_pekerjaan ON
																					ms_customer.id_pekerjaan=ms_pekerjaan.id_pekerjaan LEFT JOIN ms_pengeluaran_bulan ON
																					ms_customer.id_pengeluaran_bulan=ms_pengeluaran_bulan.id_pengeluaran_bulan LEFT JOIN ms_pendidikan ON
																					ms_customer.id_pendidikan=ms_pendidikan.id_pendidikan LEFT JOIN ms_jenis_sebelumnya ON
																					ms_customer.id_jenis_sebelumnya=ms_jenis_sebelumnya.id_jenis_sebelumnya LEFT JOIN ms_merk_sebelumnya ON
																					ms_customer.id_merk_sebelumnya=ms_merk_sebelumnya.id_merk_sebelumnya LEFT JOIN ms_digunakan ON
																					ms_customer.id_digunakan=ms_digunakan.id_digunakan LEFT JOIN ms_sumber_media ON
																					ms_customer.id_sumber_media=ms_sumber_media.id_sumber_media LEFT JOIN ms_hobi ON
																					ms_customer.id_hobi=ms_hobi.id_hobi");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['dt_pekerjaan'] 			= $this->m_admin->getSortCond("ms_pekerjaan","pekerjaan","ASC");		
		$data['dt_pendidikan'] 			= $this->m_admin->getSortCond("ms_pendidikan","pendidikan","ASC");		
		$data['dt_hobi'] 						= $this->m_admin->getSortCond("ms_hobi","hobi","ASC");		
		$data['dt_jenis_sebelumnya']= $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");		
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");		
		$data['dt_digunakan'] 			= $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");		
		$data['dt_sumber_media'] 		= $this->m_admin->getSortCond("ms_sumber_media","sumber_media","ASC");		
		$data['dt_pengeluaran_bulan'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan","pengeluaran","ASC");		
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel										= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_customer']			= $this->input->post('id_customer');		
			$data['nama'] 						= $this->input->post('nama');		
			$data['no_ktp'] 					= $this->input->post('no_ktp');		
			$data['id_pekerjaan']			= $this->input->post('id_pekerjaan');		
			$data['id_pendidikan']		= $this->input->post('id_pendidikan');		
			$data['id_hobi'] 					= $this->input->post('id_hobi');		
			$data['id_digunakan'] 		= $this->input->post('id_digunakan');		
			$data['id_jenis_sebelumnya'] 	= $this->input->post('id_jenis_sebelumnya');
			$data['id_merk_sebelumnya'] 	= $this->input->post('id_merk_sebelumnya');
			$data['id_pengeluaran_bulan']	= $this->input->post('id_pengeluaran_bulan');
			$data['id_sumber_media'] 	= $this->input->post('id_sumber_media');
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}		
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/customer/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/customer'>";
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
		$data['dt_customer'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_pekerjaan'] 			= $this->m_admin->getSortCond("ms_pekerjaan","pekerjaan","ASC");		
		$data['dt_pendidikan'] 			= $this->m_admin->getSortCond("ms_pendidikan","pendidikan","ASC");		
		$data['dt_hobi'] 						= $this->m_admin->getSortCond("ms_hobi","hobi","ASC");		
		$data['dt_jenis_sebelumnya']= $this->m_admin->getSortCond("ms_jenis_sebelumnya","jenis_sebelumnya","ASC");		
		$data['dt_merk_sebelumnya'] = $this->m_admin->getSortCond("ms_merk_sebelumnya","merk_sebelumnya","ASC");		
		$data['dt_digunakan'] 			= $this->m_admin->getSortCond("ms_digunakan","digunakan","ASC");		
		$data['dt_sumber_media'] 		= $this->m_admin->getSortCond("ms_sumber_media","sumber_media","ASC");		
		$data['dt_pengeluaran_bulan'] = $this->m_admin->getSortCond("ms_pengeluaran_bulan","pengeluaran","ASC");		
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
			$data['id_customer']			= $this->input->post('id_customer');		
			$data['nama'] 						= $this->input->post('nama');		
			$data['no_ktp'] 					= $this->input->post('no_ktp');		
			$data['id_pekerjaan']			= $this->input->post('id_pekerjaan');		
			$data['id_pendidikan']		= $this->input->post('id_pendidikan');		
			$data['id_hobi'] 					= $this->input->post('id_hobi');		
			$data['id_digunakan'] 		= $this->input->post('id_digunakan');		
			$data['id_jenis_sebelumnya'] 	= $this->input->post('id_jenis_sebelumnya');
			$data['id_merk_sebelumnya'] 	= $this->input->post('id_merk_sebelumnya');
			$data['id_pengeluaran_bulan']	= $this->input->post('id_pengeluaran_bulan');
			$data['id_sumber_media'] 	= $this->input->post('id_sumber_media');
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}		
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/customer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}