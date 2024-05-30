<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting_h1 extends CI_Controller {
    var $tables =   "ms_setting_h1";	
		var $folder =   "master";
		var $page		=		"setting_h1";
    var $pk     =   "id_setting_h1";
    var $title  =   "Master Data Setting H1";
    
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
		$data['dt_setting_h1'] = $this->m_admin->getAll($this->tables);
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
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		
		$tabel										= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['presentase_t1'] 		= $this->input->post('presentase_t1');
			$data['presentase_t2'] 		= $this->input->post('presentase_t2');
			$data['biaya_stnk'] 		= $this->input->post('biaya_stnk');
			$data['biaya_bpkb'] 		= $this->input->post('biaya_bpkb');
			$data['biaya_plat'] 		= $this->input->post('biaya_plat');
			$data['biaya_penjualan'] 		= $this->input->post('biaya_penjualan');
			$data['maks_stock_days'] 		= $this->input->post('maks_stock_days');
			$data['jml_hari_indent'] 		= $this->input->post('jml_hari_indent');
			$data['masa_aki'] 		= $this->input->post('masa_aki');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['created_at']				= $waktu;		
			$data['created_by']				= $login_id;						
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/setting_h1/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/setting_h1'>";
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
		$data['dt_setting_h1'] = $this->m_admin->kondisi($tabel,$d);
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
			$data['presentase_t1']            = $this->input->post('presentase_t1');
			$data['presentase_t2']            = $this->input->post('presentase_t2');
			$data['biaya_stnk']               = $this->input->post('biaya_stnk');
			$data['biaya_bpkb']               = $this->input->post('biaya_bpkb');
			$data['biaya_plat']               = $this->input->post('biaya_plat');
			$data['biaya_penjualan']          = $this->input->post('biaya_penjualan');
			$data['maks_stock_days']          = $this->input->post('maks_stock_days');
			$data['jml_hari_indent']          = $this->input->post('jml_hari_indent');
			$data['masa_aki']                 = $this->input->post('masa_aki');
			$data['po_fix_dealer']            = $this->input->post('po_fix_dealer');
			$data['po_t1_dealer']             = $this->input->post('po_t1_dealer');
			$data['deadline_po_dealer']       = $this->input->post('deadline_po_dealer');
			$data['reminder_spk']             = $this->input->post('reminder_spk');
			$data['reminder_service']         = $this->input->post('reminder_service');
			$data['reminder_sales_follow_up'] = $this->input->post('reminder_sales_follow_up');
			$data['maks_uncontactable_sales_fol_up'] = $this->input->post('maks_uncontactable_sales_fol_up');
			// if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
			// else $data['active'] = "";					
			// $data['updated_at']  = $waktu;		
			// $data['updated_by']  = $login_id;
			$this->db->update($tabel,$data,['id_setting_h1'=>$id]);
			// $this->db->query("UPDATE ms_setting_h1 SET presentase_t1 = '$presentase_t1',presentase_t2='$presentase_t2',biaya_stnk='$biaya_stnk',
			// 		biaya_bpkb = '$biaya_bpkb',biaya_plat='$biaya_plat',biaya_penjualan='$biaya_penjualan',maks_stock_days='$maks_stock_days',
			// 		jml_hari_indent = '$jml_hari_indent',masa_aki='$masa_aki',po_fix_dealer='$po_fix_dealer',po_t1_dealer='$po_t1_dealer',deadline_po_dealer='$deadline_po_dealer', reminder_spk='$reminder_spk',reminder_service='$reminder_service'
			// 		WHERE id_setting_h1 = '$id'");
			//$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/setting_h1'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}