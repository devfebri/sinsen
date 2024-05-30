<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Target_sales extends CI_Controller {

    var $tables =   "ms_target_sales";	
		var $folder =   "master";
		var $page		=		"target_sales";
    var $pk     =   "id_target_sales";
    var $title  =   "Master Data Target Sales";

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
		$data['dt_target_sales'] = $this->db->query("SELECT ms_target_sales.*,ms_dealer.nama_dealer FROM ms_target_sales INNER JOIN ms_dealer ON 
						ms_target_sales.id_dealer = ms_dealer.id_dealer");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";	
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");								
		$this->template($data);	
	}
	public function t_target(){
		$id = $this->input->post('id_target_sales');
		$dq = "SELECT ms_target_sales_detail.*,ms_tipe_kendaraan.tipe_ahm FROM ms_target_sales_detail INNER JOIN ms_tipe_kendaraan 
						ON ms_target_sales_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						WHERE ms_target_sales_detail.id_target_sales = '$id'";
		$data['dt_target'] = $this->db->query($dq);
		$this->load->view('master/t_target',$data);
	}
	public function delete_target(){
		$id 		= $this->input->post('id_target_sales_detail');		
		$da 		= "DELETE FROM ms_target_sales_detail WHERE id_target_sales_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_target(){
		$id_tipe_kendaraan	= $this->input->post('id_tipe_kendaraan');
		$id_target_sales		= $this->input->post('id_target_sales');		
		$c 			= $this->db->query("SELECT * FROM ms_target_sales_detail WHERE id_tipe_kendaraan ='$id_tipe_kendaraan' AND id_target_sales = '$id_target_sales'");
		if($c->num_rows()==0){
			$data['id_tipe_kendaraan']		= $this->input->post('id_tipe_kendaraan');			
			$data['id_target_sales']			= $this->input->post('id_target_sales');
			$data['jan']			= $this->input->post('jan');			
			$data['feb']			= $this->input->post('feb');			
			$data['mar']			= $this->input->post('mar');			
			$data['apr']			= $this->input->post('apr');			
			$data['mei']			= $this->input->post('mei');			
			$data['jun']			= $this->input->post('jun');			
			$data['jul']			= $this->input->post('jul');			
			$data['agus']			= $this->input->post('agus');			
			$data['sept']			= $this->input->post('sept');			
			$data['okt']			= $this->input->post('okt');			
			$data['nov']			= $this->input->post('nov');			
			$data['des']			= $this->input->post('des');			
			$this->m_admin->insert('ms_target_sales_detail',$data);							
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
			$id_dealer 	= $this->input->post('id_dealer');			
			$cek2 			= $this->m_admin->getByID($tabel,"id_dealer",$id_dealer)->num_rows();
			if($cek2 == 0){
				$data['id_target_sales'] 	= $this->input->post('id_target_sales');				
				$data['id_dealer'] 				= $this->input->post('id_dealer');
				$data['tahun'] 						= $this->input->post('tahun');
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
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/target_sales/add'>";
			}else{
				$_SESSION['pesan'] 	= "This data already selected before!";
				$_SESSION['tipe'] 	= "danger";
				echo "<script>history.go(-1)</script>";
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
				$this->m_admin->delete("ms_target_sales_detail",$pk,$id);			
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/target_sales'>";
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
		$data['dt_target_sales'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");								
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
			$data['id_dealer'] 				= $this->input->post('id_dealer');
			$data['tahun'] 						= $this->input->post('tahun');
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/target_sales'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}