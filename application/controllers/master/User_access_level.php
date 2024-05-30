<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_access_level extends CI_Controller {
  var $tables =   "ms_user_access_level";	
	var $folder =   "master";
	var $page		=		"user_access_level";
  var $pk     =   "id_user_access_level";
  var $title  =   "Master Data User Access Level";
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
		$data['dt_user_access_level'] = $this->db->query("SELECT * FROM ms_user_access_level INNER JOIN ms_user_group ON
																					ms_user_access_level.id_user_group=ms_user_group.id_user_group INNER JOIN ms_menu ON
																					ms_user_access_level.id_menu=ms_menu.id_menu");							
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";
		$data['dt_user_group'] = $this->m_admin->getSort("ms_user_group","user_group","ASC");									
		$data['dt_menu'] 			= $this->m_admin->getSort("ms_menu","menu_name","ASC");									
		$this->template($data);	
	}
	public function t_group(){
		$id = $this->input->post('id_user_group');
		$df = $this->m_admin->getByID("ms_user_group","id_user_group",$id)->row();				
		$data['id'] = $df->jenis_user;
		$this->load->view('master/t_user_group',$data);
	}
	public function save()
	{		
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);		
		$login_id					= $this->session->userdata('id_user');
		$tabel								= $this->tables;
		$id_menu 							= $this->input->post('id_menu');		
		$id_user_group				= $this->input->post('id_user_group');				
		if($this->input->post('active') == '1'){
			$active			= $this->input->post('active');		
		}
		$cek = $this->db->query("SELECT * FROM ms_user_access_level WHERE id_user_group = '$id_user_group'");
		if(count($cek) == 0){
			foreach($id_menu AS $key => $val){			
				$id_menu 	= $_POST['id_menu'][$key];
				$can_sele = $_POST['can_select'][$key];
				if($can_sele == '1'){
					$can_select		= 9;
				}else{
					$can_select		= 7;
				}		
				// $can_ins = $_POST['can_insert'][$key];
				// if($can_ins == '1'){
				// 	$can_insert		= $_POST['can_insert'][$key];
				// }else{
				// 	$can_insert		= 0;
				// }		
				// $can_upd = $_POST['can_update'][$key];
				// if($can_upd == '1'){
				// 	$can_update		= $_POST['can_update'][$key];
				// }else{
				// 	$can_update		= 0;
				// }		
				// $can_dele = $_POST['can_delete'][$key];
				// if($can_dele == '1'){
				// 	$can_delete		= $_POST['can_delete'][$key];
				// }else{
				// 	$can_delete		= 0;
				// }		
				// $can_pri = $_POST['can_print'][$key];
				// if($can_pri == '1'){
				// 	$can_print		= $_POST['can_print'][$key];
				// }else{
				// 	$can_print		= 0;
				// }		
				// $can_dow = $_POST['can_download'][$key];
				// if($can_dow == '1'){
				// 	$can_download		= $_POST['can_download'][$key];
				// }else{
				// 	$can_download		= 0;
				// }
				// $can_app = $_POST['can_approval'][$key];
				// if($can_app == '1'){
				// 	$can_approval		= $_POST['can_approval'][$key];
				// }else{
				// 	$can_approval		= 0;
				// }		
							
				// $result[] = array(
				// 	"id_user_group"  	=> $id_user_group,
				// 	"id_menu"  				=> $id_menu,
				// 	"can_select"  		=> $can_select,
				// 	"can_insert"  		=> $can_insert,
				// 	"can_update"  		=> $can_update,
				// 	"can_delete"  		=> $can_delete,
				// 	"can_print"  			=> $can_print,
				// 	"can_download"  	=> $can_download,
				// 	"can_approval"  	=> $can_approval,
				// 	"created_at"  		=> $waktu,
				// 	"created_by"  		=> $login_id,
				// 	"active"  				=> $active
				// );			
				// $testb = $this->m_admin->insert('ms_user_access_level', $result);
				$data["id_user_group"]  	= $id_user_group;
					$data["id_menu"]  				= $id_menu;
					$data["can_select"]  		= $can_select;				
				$testb = $this->m_admin->insert('ms_user_access_level', $data);
			}
			//$testb= $this->db->insert_batch('ms_user_access_level', $result);
		}else{	
			$del = $this->db->query("DELETE FROM ms_user_access_level WHERE id_user_group = '$id_user_group'");		
			foreach($id_menu AS $key => $val){			
				$id_menu 	= $_POST['id_menu'][$key];
				
				$can_sele = $_POST['can_select'][$key];
				if($can_sele == '1'){
					$can_select		= 9;
				}else{
					$can_select		= 7;
				}		
				
				// $can_ins = $_POST['can_insert'][$key];
				// if($can_ins == '1'){
				// 	$can_insert		= $_POST['can_insert'][$key];
				// }else{
				// 	$can_insert		= 0;
				// }		
				
				// $can_upd = $_POST['can_update'][$key];
				// if($can_upd == '1'){
				// 	$can_update		= $_POST['can_update'][$key];
				// }else{
				// 	$can_update		= 0;
				// }		
				
				// $can_dele = $_POST['can_delete'][$key];
				// if($can_dele == '1'){
				// 	$can_delete		= $_POST['can_delete'][$key];
				// }else{
				// 	$can_delete		= 0;
				// }		
				
				// $can_pri = $_POST['can_print'][$key];
				// if($can_pri == '1'){
				// 	$can_print		= $_POST['can_print'][$key];
				// }else{
				// 	$can_print		= 0;
				// }
				// $can_dow = $_POST['can_download'][$key];
				// if($can_dow == '1'){
				// 	$can_download		= $_POST['can_download'][$key];
				// }else{
				// 	$can_download		= 0;
				// }
				// $can_app = $_POST['can_approval'][$key];
				// if($can_app == '1'){
				// 	$can_approval		= $_POST['can_approval'][$key];
				// }else{
				// 	$can_approval		= 0;
				// }		
							
				// $result[] = array(
				// 	"id_user_group"  	=> $id_user_group,
				// 	"id_menu"  				=> $id_menu,
				// 	"can_select"  		=> $can_select,
				// 	"can_insert"  		=> $can_insert,
				// 	"can_update"  		=> $can_update,
				// 	"can_delete"  		=> $can_delete,
				// 	"can_print"  			=> $can_print,
				// 	"can_download"  	=> $can_download,
				// 	"can_approval"  	=> $can_approval,
				// 	"created_at"  		=> $waktu,
				// 	"created_by"  		=> $login_id,
				// 	"active"  				=> $active
				// );
					$data["id_user_group"]  	= $id_user_group;
					$data["id_menu"]  				= $id_menu;
					$data["can_select"]  		= $can_select;				
				$testb = $this->m_admin->insert('ms_user_access_level', $data);
			}
			//$testb= $this->db->insert_batch('ms_user_access_level', $result);
		}
		//$this->m_admin->insert($tabel,$data);
		$_SESSION['pesan']= "Data has been saved successfully";
		$_SESSION['tipe'] = "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user_access_level/add'>";
	}
	public function delete()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->get('id');
		$cek_approval  = $this->m_admin->cek_approval($tabel,$pk,$id);		
		if($cek_approval == 'salah'){
			$_SESSION['pesan']  = 'Gagal! Anda tidak punya akses.';										
			$_SESSION['tipe'] 	= "danger";			
			echo "<script>history.go(-1)</script>";
		}else{		
			$this->m_admin->delete($tabel,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been deleted successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user_access_level'>";
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
		$data['dt_user_access_level'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_user_role'] = $this->m_admin->getSort("ms_user_role","user_role","ASC");									
		$data['dt_menu'] 			= $this->m_admin->getSort("ms_menu","menu_name","ASC");
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);		
		$login_id					= $this->session->userdata('id_user');
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$id 					= $this->input->post('id');
		$data['id_menu'] 			= $this->input->post('id_menu');
		$data['id_user_role']	= $this->input->post('id_user_role');
		if($this->input->post('can_view') == '1'){
			$data['can_view']		= $this->input->post('can_view');					
		}else{
			$data['can_view']		= "";								
		}		
		if($this->input->post('can_add') == '1'){
			$data['can_add']		= $this->input->post('can_add');		
		}else{
			$data['can_add']		= "";								
		}
		if($this->input->post('can_edit') == '1'){
			$data['can_edit']		= $this->input->post('can_edit');		
		}else{
			$data['can_edit']		= "";								
		}
		if($this->input->post('can_delete') == '1'){
			$data['can_delete']	= $this->input->post('can_delete');		
		}else{
			$data['can_delete']		= "";								
		}
		if($this->input->post('can_print') == '1'){
			$data['can_print']	= $this->input->post('can_print');		
		}else{
			$data['can_print']		= "";								
		}
		$data['updated_at']		= $waktu;
		$data['updated_by']		= $login_id;
		if($this->input->post('active') == '1'){
			$data['active']			= $this->input->post('active');		
		}else{
			$data['active']		= "";								
		}		
		$this->m_admin->update($tabel,$data,$pk,$id);
		$_SESSION['pesan'] 	= "Data has been updated successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/user_access_level'>";
	}
}