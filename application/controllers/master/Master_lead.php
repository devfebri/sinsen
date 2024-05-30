<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_lead extends CI_Controller {

    var $tables =   "ms_master_lead_detail";	
		var $folder =   "master";
		var $page		=		"master_lead";
    var $pk     =   "id_master_lead_detail";
    var $title  =   "Master Data Master Lead Time";

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

	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$dq = "SELECT ms_master_lead_detail.*, ms_utd.kode_type_actual as id_tipe_kendaraan,ms_utd.kode_warna_actual as warna FROM  ms_utd
			LEFT JOIN ms_master_lead_detail ON ms_master_lead_detail.id_tipe_kendaraan = ms_utd.kode_type_actual and ms_master_lead_detail.warna = ms_utd.kode_warna_actual	
		";
		$data['dt_master_lead'] = $this->db->query($dq);
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";	
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$this->template($data);	
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
			$data['id_dealer']		= $this->input->post('id_dealer');						
			$data['lead_time_md_d']			= $this->input->post('lead_time_md_d');			
			$data['proses_receiving']		= $this->input->post('proses_receiving');			
			$data['lead_time_ahm_md']			= $this->input->post('lead_time_ahm_md');			
			$data['proses_receiving_md']		= $this->input->post('proses_receiving_md');			
			$data['total_lead_time']		= $this->input->post('total_lead_time');			
			$data['active']							= $this->input->post('status');									
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/master_lead/add'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}	
	}
	
	/*
	public function delete()
	{		
		$tabel		= "ms_master_lead_detail";
		$pk 			= "id_master_lead_detail";
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
				$this->m_admin->delete("ms_master_lead_detail",$pk,$id);			
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/master_lead'>";
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
		$id_tipe_kendaraan 			= $this->input->get('id_tipe_kendaraan');
		$warna			= $this->input->get('warna');

		if ($id == '') {
			$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
			$cek_tipe = $this->db->get_where('ms_master_lead_detail', array('id_tipe_kendaraan'=>$id_tipe_kendaraan,'warna'=>$warna));
			if ($cek_tipe->num_rows() == 1) {
				redirect('master/Master_lead','refresh');
			}
			$this->db->insert('ms_master_lead_detail', array(
				'id_tipe_kendaraan'=>$id_tipe_kendaraan,
				'warna'=>$warna,
				'created_at' => $waktu
			));
			$id = $this->db->insert_id();
		}

		$d 				= array($pk=>$id);		
		$data['dt_master_lead'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){			
			// $data['id_dealer']		= $this->input->post('id_dealer');						
			$data['id_tipe_kendaraan']		= $this->input->post('id_tipe_kendaraan');				
			$data['warna']		= $this->input->post('warna');						
			$data['lead_time_md_d']			= $this->input->post('lead_time_md_d');			
			$data['proses_receiving']		= $this->input->post('proses_receiving');			
			$data['lead_time_ahm_md']			= $this->input->post('lead_time_ahm_md');			
			$data['proses_receiving_md']		= $this->input->post('proses_receiving_md');			
			$data['total_lead_time']		= $this->input->post('total_lead_time');			
			$data['active']							= $this->input->post('status');			
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/master_lead'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}