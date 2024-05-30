<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Norek_dealer extends CI_Controller {

    var $tables =   "ms_norek_dealer";	
		var $folder =   "master";
		var $page		=		"norek_dealer";
    var $pk     =   "id_norek_dealer";
    var $title  =   "Master Data No Rekening Dealer";

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
		$data['set']	= "view";
		$data['dt_norek_dealer'] = $this->db->query("SELECT ms_norek_dealer.*,ms_dealer.nama_dealer FROM ms_norek_dealer INNER JOIN ms_dealer
							ON ms_norek_dealer.id_dealer=ms_dealer.id_dealer");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";	
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$data['dt_bank'] = $this->m_admin->getSortCond("ms_bank","bank","ASC");								
		$this->template($data);	
	}
	public function t_norek(){
		$id = $this->input->post('id_norek_dealer');
		$dq = "SELECT ms_norek_dealer_detail.*,ms_bank.bank FROM ms_norek_dealer_detail INNER JOIN ms_bank ON ms_norek_dealer_detail.id_bank=ms_bank.id_bank
						WHERE ms_norek_dealer_detail.id_norek_dealer = '$id'";
		$data['dt_norek'] = $this->db->query($dq);
		$this->load->view('master/t_norek',$data);
	}
	public function delete_norek(){
		$id 		= $this->input->post('id_norek_dealer_detail');		
		$da 		= "DELETE FROM ms_norek_dealer_detail WHERE id_norek_dealer_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_norek(){
		$id_bank					= $this->input->post('id_bank');
		$id_norek_dealer	= $this->input->post('id_norek_dealer');		
		$c 			= $this->db->query("SELECT * FROM ms_norek_dealer_detail WHERE id_bank ='$id_bank' AND id_norek_dealer = '$id_norek_dealer'");
		if($c->num_rows()==0){
			$data['id_bank']		= $this->input->post('id_bank');			
			$data['id_norek_dealer']			= $this->input->post('id_norek_dealer');
			$data['jenis_rek']			= $this->input->post('jenis_rek');			
			$data['no_rek']			= $this->input->post('no_rek');			
			$data['nama_rek']			= $this->input->post('nama_rek');			
			$this->m_admin->insert('ms_norek_dealer_detail',$data);							
			echo "nihil";
		}else{
			echo "nothing";
		}
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
			$data['id_norek_dealer'] 	= $this->input->post('id_norek_dealer');				
			$data['id_dealer'] 				= $this->input->post('id_dealer');
			if($this->input->post('active') == '1'){
				$data['active'] 					= $this->input->post('active');		
			}else{
				$data['active'] 					= "";					
			}				
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/norek_dealer/add'>";
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
				$this->m_admin->delete("ms_norek_dealer_detail",$pk,$id);			
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/norek_dealer'>";
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
		$data['dt_norek_dealer'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_bank'] = $this->m_admin->getSortCond("ms_bank","bank","ASC");								
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
			$data['id_norek_dealer'] 	= $this->input->post('id_norek_dealer');				
			$data['id_dealer'] 				= $this->input->post('id_dealer');
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/norek_dealer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}