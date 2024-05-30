<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskon_oli_kpb extends CI_Controller {

    var $tables =   "ms_diskon_kpb";	
		var $folder =   "master";
		var $page		=		"diskon_oli_kpb";
    var $pk     =   "id_diskon_kpb";
    var $title  =   "Master Diskon Oli KPB";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('m_part');		
		//===== Load Library =====
		$this->load->library("udp_cart");//load library 
		$this->item   = new Udp_cart("item");
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
		$data['dt_diskon_oli_kpb'] = $this->db->query("SELECT * FROM ms_diskon_kpb LEFT JOIN ms_part ON ms_diskon_kpb.id_part = ms_part.id_part
				LEFT JOIN ms_tipe_kendaraan ON ms_diskon_kpb.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan");							
		//$data['dt_part']= $this->m_admin->getSortCond("ms_part","nama_part","ASC");
		$data['dt_part']= $this->m_admin->getSortCond("ms_part","nama_part","ASC");
		$data['dt_tipe']= $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");		
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;								
		$data['set']		= "insert";				
		$data['dt_part']= $this->m_admin->getSortCond("ms_part","nama_part","ASC");
		$data['dt_tipe']= $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");		
		$this->template($data);	
	}	
	
  public function ajax_list()
	{				
		$list = $this->m_part->get_datatables();		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $isi) {			
			$no++;
			$row = array();
			$row[] = $no;			
			$row[] = $isi->id_part;			
			$row[] = $isi->nama_part;			
			$row[] = "<button title=\"Choose\" data-dismiss=\"modal\" onclick=\"chooseitem('$isi->id_part')\" class=\"btn btn-flat btn-success btn-sm\"><i class=\"fa fa-check\"></i></button>";
			$data[] = $row;			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_part->count_all(),
						"recordsFiltered" => $this->m_part->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
  public function take_part()
	{		
		$id_part	= $this->input->post('id_part');	
		$dt_kel						= $this->db->query("SELECT * FROM ms_part WHERE id_part = '$id_part'")->row();
		$nama_part 		= $dt_kel->nama_part;		
		echo $id_part."|".$nama_part;
	}
	public function save()
	{		
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id					= $this->session->userdata('id_user');		

		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			
			$data['id_part']				= $this->input->post('id_part');		
			$data['id_tipe_kendaraan'] 			= $this->input->post('id_tipe_kendaraan');		
			$data['tipe_diskon']				= $this->input->post('tipe_diskon');		
			$data['diskon_oli']					= $this->input->post('diskon_oli');								
			if($this->input->post('status') == '1'){
				$data['status']				= $this->input->post('status');		
			}else{
				$data['status'] 			= "";
			}
			$data['created_at']			= $waktu;
			$data['created_by']			= $login_id;				
			
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_kpb/add'>";			
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_kpb'>";
		}
	}
	
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_diskon_oli_kpb'] = $this->db->query("SELECT *,ms_diskon_kpb.status as status2 FROM ms_diskon_kpb LEFT JOIN ms_part ON ms_diskon_kpb.id_part = ms_part.id_part
				LEFT JOIN ms_tipe_kendaraan ON ms_diskon_kpb.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				WHERE ms_diskon_kpb.id_diskon_kpb = '$id'");							
		$data['dt_part']= $this->m_admin->getSortCond("ms_part","nama_part","ASC");
		$data['dt_tipe']= $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");		
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']			= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id_diskon_kpb');
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$id					= $this->input->post("id_diskon_kpb");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_part']				= $this->input->post('id_part');		
			$data['id_tipe_kendaraan'] 			= $this->input->post('id_tipe_kendaraan');		
			$data['tipe_diskon']				= $this->input->post('tipe_diskon');		
			$data['diskon_oli']					= $this->input->post('diskon_oli');															
			
			$data['updated_at']			= $waktu;
			$data['updated_by']			= $login_id;

			if($this->input->post('status') == '1'){
				$data['status']	= $this->input->post('status');		
			}else{
				$data['status'] 			= "";
			}
			
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/diskon_oli_kpb'>";			
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}