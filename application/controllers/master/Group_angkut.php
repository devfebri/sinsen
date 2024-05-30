<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_angkut extends CI_Controller {

    var $tables =   "ms_group_angkut";	
		var $folder =   "master";
		var $page		=		"group_angkut";
    var $pk     =   "id_group_angkut";
    var $title  =   "Master Data Group Ongkos Angkut";

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
		$data['dt_group_angkut'] = $this->db->query("SELECT * FROM ms_group_angkut ORDER BY id_group_angkut DESC");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;	
		$data['dt_vendor'] = $this->m_admin->getSort("ms_vendor","vendor_name","ASC");	
		$data['dt_tipe_kendaraan'] = $this->m_admin->getSort("ms_tipe_kendaraan","tipe_ahm","ASC");	
		$data['set']		= "insert";									
		$this->template($data);	
	}
	public function cari_id(){		
		$kode = $this->m_admin->cari_id("ms_koneksi_ksu","id_koneksi_ksu");		
		$token = $this->m_admin->get_tmp();
		echo $kode.$token;
	}
	public function cari_id_real2(){				
		$kode = $this->m_admin->cari_id_fake("ms_group_angkut","id_group_angkut");		
		return $kode;
	}
	public function cari_id_real(){        
		$tabel = $this->tables;
		$id = $this->pk;
    $no   = $this->db->query("SELECT * FROM $tabel ORDER BY $id DESC LIMIT 0,1");                             
    if($no->num_rows()>0){
        $row    = $no->row();                           
        $id     = $row->$id + 1;
        $kode   = $id;
    }else{
        $kode   = 1;
    }
    return $kode;
   }
	public function t_data(){
		$id = $this->input->post('id_group_angkut');
		$dq = "SELECT ms_group_angkut_detail.*,ms_tipe_kendaraan.tipe_ahm FROM ms_group_angkut_detail INNER JOIN ms_tipe_kendaraan ON ms_group_angkut_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						WHERE ms_group_angkut_detail.id_group_angkut = '$id'";
		$data['dt_tipe'] = $this->db->query($dq);
		$this->load->view('master/t_angkut_tipe',$data);
	}
	public function delete_data(){
		$id 		= $this->input->post('id_group_angkut_detail');		
		$da 		= "DELETE FROM ms_group_angkut_detail WHERE id_group_angkut_detail = '$id'";
		$this->db->query($da);			
		echo "nihil";
	}
	public function save_data(){
		$id_group_angkut		= $this->input->post('id_group_angkut');
		$id_tipe_kendaraan	= $this->input->post('id_tipe_kendaraan');		
		$c 	= $this->db->query("SELECT * FROM ms_group_angkut_detail WHERE id_tipe_kendaraan ='$id_tipe_kendaraan' AND id_group_angkut = '$id_group_angkut'");
		$data['id_tipe_kendaraan']	= $this->input->post('id_tipe_kendaraan');			
		$data['id_group_angkut']		= $this->input->post('id_group_angkut');			
		if($c->num_rows()==0){
			$this->m_admin->insert('ms_group_angkut_detail',$data);							
			echo "nihil";
		}else{			
			$t = $c->row();
			$this->m_admin->update('ms_group_angkut_detail',$data,'id_group_angkut_detail',$t->id_group_angkut_detail);										
			echo "nihil";
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
			$id_group_angkut_old 			= $this->input->post('id_group_angkut');
			$id_group_angkut 					= $this->cari_id_real();
			$data['id_group_angkut'] 	= $this->cari_id_real();
			$data['group_angkut'] 		= $this->input->post('group_angkut');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;						

			$ty = $this->m_admin->getByID("ms_group_angkut_detail","id_group_angkut",$id_group_angkut_old);
			foreach ($ty->result() as $key) {
				$this->db->query("UPDATE ms_group_angkut_detail SET id_group_angkut = '$id_group_angkut' WHERE id_group_angkut = '$id_group_angkut_old'");
			}
			$this->m_admin->insert($tabel,$data);	
			//echo "nihil";		
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_angkut'>";
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
		$da 				= $this->db->query("DELETE FROM ms_group_angkut_detail WHERE id_group_angkut = '$id'");
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
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_angkut'>";
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
		$data['dt_group_angkut'] = $this->m_admin->kondisi($tabel,$d);		
		$data['dt_tipe_kendaraan'] = $this->m_admin->getSort("ms_tipe_kendaraan","tipe_ahm","ASC");	
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
		$id				= $this->input->post($pk);				
		$data['group_angkut'] 		= $this->input->post('group_angkut');
		if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
			else $data['active'] 		= "";					
		$data['updated_at']				= $waktu;		
		$data['updated_by']				= $login_id;
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/group_angkut'>";		
	}
}