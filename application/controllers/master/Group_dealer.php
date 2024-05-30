<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Group_dealer extends CI_Controller {
    var $tables =   "ms_group_dealer";	
		var $folder =   "master";
		var $page		=		"group_dealer";
    var $pk     =   "id_group_dealer";
    var $title  =   "Master Data Group Dealer";
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
		$data['set']	= "view";
		$data['dt_group_dealer'] = $this->db->query("SELECT * FROM ms_group_dealer");							
		$this->template($data);	
	}

	public function custome()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= 'Master Data Group Dealer';															
		$data['set']	= "custome";
		$data['dt_group_dealer'] = $this->db->query("SELECT * from ms_group_dealer_custome");							
		$this->template($data);	
	}

	public function custome_add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "custome_insert";	
		$data['dt_dealer'] = $this->db->query("SELECT * FROM ms_dealer where h1='1' and active ='1' order by nama_dealer asc");		
		$this->template($data);	
	}

	public function save_group_costume(){
		$data['id_dealer']		= $this->input->post('id_dealer');			
		$this->m_admin->insert('ms_group_dealer_custome',$data);			
		$this->m_admin->insert('ms_group_dealer_custome_detail',$data);							
	}


	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "insert";	
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");								
		$this->template($data);	
	}

	public function t_group(){
		$id = $this->input->post('id_group_dealer');
		$dq = "SELECT ms_group_dealer_detail.*,ms_dealer.nama_dealer,ms_dealer.kode_dealer_md FROM ms_group_dealer_detail INNER JOIN ms_dealer ON ms_group_dealer_detail.id_dealer=ms_dealer.id_dealer
						WHERE ms_group_dealer_detail.id_group_dealer = '$id'";
		$data['dt_group'] = $this->db->query($dq);
		$this->load->view('master/t_group',$data);
	}

	public function delete_group(){
		$id 		= $this->input->post('id_group_dealer_detail');		
		$da 		= "DELETE FROM ms_group_dealer_detail WHERE id_group_dealer_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}

	public function save_group(){
		$id_dealer				= $this->input->post('id_dealer');
		$id_group_dealer	= $this->input->post('id_group_dealer');		
		$c 			= $this->db->query("SELECT * FROM ms_group_dealer_detail WHERE id_dealer ='$id_dealer' AND id_group_dealer = '$id_group_dealer'");
		if($c->num_rows()==0){
			$data['id_dealer']		= $this->input->post('id_dealer');			
			$data['id_group_dealer']			= $this->input->post('id_group_dealer');
			$data['head_office']			= $this->input->post('head_office');			
			$this->m_admin->insert('ms_group_dealer_detail',$data);							
			echo "nihil";
		}else{
			echo "nothing";
		}
	}

	public function save_custome()
	{		
		$waktu 									= gmdate("y-m-d h:i:s", time()+60*60*7);
		$id_get = $this->db->query("SELECT count(1) AS max_id FROM ms_group_dealer_custome")->row();
		$re_id_get_max = intval($id_get->max_id) + 1;
		
		$current_year = date("Y");
		$new_id = sprintf("%03d/GD/%d", $re_id_get_max, $current_year);

		$login_id								= $this->session->userdata('id_user');		
		$data['id_group_dealer_custome'] 		= $new_id;			
		$data['qq_kwitansi'] 					= $this->input->post('qq_kwitansi');	
		$data['created_at']						= $waktu;		
		$data['created_by']						= $login_id;
		$dealer_group 							= $this->input->post('id_dealer_temp');	

		if($this->input->post('active') == '1'){
			$data['active'] 					= $this->input->post('active');		
		}else{
			$data['active'] 					= "";					
		}	

		$temp = array();
		foreach ($dealer_group as $row ){
			$temp[]=array(
				'id_dealer' => $row,
				'id_group_dealer_custome' =>$new_id,
			);
		}

		$this->db->insert('ms_group_dealer_custome', $data);
		$this->db->insert_batch('ms_group_dealer_custome_detail', $temp);
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_dealer/custome'>";
	}


	
	public function delete_group_customer()
	{		
		$tabel			= $this->tables;
		$pk 			= 'id_group_dealer_custome';
		$id 			= $this->input->get('id');	

		$this->db->trans_begin();			
		$this->db->delete('ms_group_dealer_custome',array($pk=>$id));
		$this->db->delete('ms_group_dealer_custome_detail',array($pk=>$id));
		$this->db->trans_commit();			
		$result = 'Success';			

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_dealer/custome'>";
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
			$data['id_group_dealer'] 	= $this->input->post('id_group_dealer');				
			$data['group_dealer'] 		= $this->input->post('group_dealer');				
			$data['qq_kwitansi'] 		= $this->input->post('qq_kwitansi');				
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_dealer/add'>";
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
				$this->m_admin->delete("ms_group_dealer_detail",$pk,$id);						
				$result = 'Data has been deleted succesfully';										
				$_SESSION['tipe'] 	= "success";			
			}
			$_SESSION['pesan'] 	= $result;
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_dealer'>";
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
		$data['dt_group_dealer'] = $this->m_admin->kondisi($tabel,$d);
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
			$data['group_dealer'] 	= $this->input->post('group_dealer');				
			$data['qq_kwitansi'] 	= $this->input->post('qq_kwitansi');				
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_dealer'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}