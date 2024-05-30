<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipe extends CI_Controller {

    var $tables =   "ms_tipe_kendaraan";	
		var $folder =   "master";
		var $page		=		"tipe";
    var $pk     =   "id_tipe_kendaraan";
    var $title  =   "Master Data Tipe Kendaraan";

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
		$data['dt_tipe'] = $this->db->query("SELECT ms_tipe_kendaraan.*,ms_segment.segment,ms_kategori.kategori FROM ms_tipe_kendaraan LEFT JOIN ms_segment 
																ON ms_tipe_kendaraan.id_segment		=	ms_segment.id_segment LEFT JOIN ms_kategori
																ON ms_tipe_kendaraan.id_kategori	=	ms_kategori.id_kategori LEFT JOIN ms_series
																ON ms_tipe_kendaraan.id_series = ms_series.id_series
																ORDER BY id_tipe_kendaraan ASC");							
		$this->template($data);	
	}


	public function add()
	{				
		$data['isi']    			= $this->page;		
		$data['title']				= $this->title;				
		$data['dt_segment'] 	= $this->m_admin->getSortCond("ms_segment","segment","ASC");	
		$data['dt_kategori'] 	= $this->m_admin->getSortCond("ms_kategori","kategori","ASC");			
		$data['dt_series'] 	= $this->m_admin->getSortCond("ms_series","series","ASC");			
		$data['set']					= "insert";									
		$this->template($data);	
	}


	public function part_ev()
	{				
		$data['isi']    = 'part_ev';		
		$data['title']	= 'Setting Battery Qty EV';															
		$data['set']		= "part_ev";
		$data['dt_tipe'] = $this->db->query("SELECT * from ms_setting_part_battery_ev ORDER BY id_tipe_kendaraan ASC");							
		$this->template($data);	
	}

	
	public function add_part_ev_qty()
	{				
		$data['isi']    			= $this->page;		
		$data['title']				= $this->title;				
		$data['dt_tipe_kendaraan'] = $this->db->query("SELECT * from ms_tipe_kendaraan WHERE id_kategori ='ev'");	
		$data['dt_part'] = $this->db->query("SELECT DISTINCT part_id,part_desc  from tr_shipping_list_ev_accoem");	
		$data['set']					= "add_part_ev_qty";	
		$this->template($data);	
	}

	
	public function save_part_qty()
	{
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			        = $this->session->userdata('id_user');	

		$id_part 		   = $data['id_part']			 = $this->input->post('id_part');
		$id_tipe_kendaraan = $data['id_tipe_kendaraan']  = $this->input->post('id_tipe_kendaraan');

		$data['qty'] 				= $this->input->post('qty');
		$data['created_at']			= $waktu;
		$data['created_by']			= $login_id;	
		$data['active']			    = $this->input->post('active');	

		$cek  = $this->db->query("SELECT * from ms_setting_part_battery_ev WHERE id_part='$id_part' and id_tipe_kendaraan ='$id_tipe_kendaraan'")->row();	

		if($cek == 0){
			$this->m_admin->insert('ms_setting_part_battery_ev',$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe/add_part_ev_qty'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe/add_part_ev_qty'>";
		}
	}

	public function edit_part_ev()
	{
		$tabel			    = 'ms_setting_part_battery_ev';
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		
		$data['dt_tipe']        = $this->m_admin->kondisi($tabel,$d)->row();
		$data['value'] = $this->db->query("SELECT * from ms_setting_part_battery_ev WHERE id_tipe_kendaraan ='$id'")->row();
		$data['tipe_kendaraan'] = $this->db->query("SELECT * from ms_tipe_kendaraan WHERE id_kategori ='ev'")->result();	
		$data['id_part']        = $this->db->query("SELECT DISTINCT part_id  from tr_shipping_list_ev_accoem")->result();			

		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']		= "edit_ev";									
		$this->template($data);	
	}


	public function update_ev()
	{
		$tabel			    = 'ms_setting_part_battery_ev';
		$pk 				= $this->pk;		
		$id 				= $this->input->post('tipe_kendaraan');
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();

		if($cek == 1){
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			        = $this->session->userdata('id_user');	
		$data['updated_at']			= $waktu;
		$data['updated_by']			= $login_id;
		$data['id_part']			= $this->input->post('id_part');
		$data['active']			= $this->input->post('active');
		$data['qty']			= $this->input->post('qty');

		$this->m_admin->update($tabel,$data,$pk,$id);					
		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe/part_ev'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
		}
	}


	public function view_part_ev()
	{
		$tabel			    = 'ms_setting_part_battery_ev';
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['row']        = $this->m_admin->kondisi($tabel,$d)->row();

		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']		= "view_ev";									
		$this->template($data);	
	
	}






	public function get_tipe_group(){
		$id_tipe_group		= $this->input->post('id_tipe_group');	
		$dt_tipe_level		= $this->m_admin->getByID("ms_tipe_kendaraan_level","id_tipe_group",$id_tipe_group);								
		$data .= "<option value=''>- choose -</option>";
		foreach ($dt_tipe_level->result() as $row) {
			$data .= "<option value='$row->id_tipe_level'>$row->tipe_level</option>\n";
		}
		echo $data;
	}

	public function save()
	{		
		$tabel						= $this->tables;
		$waktu 						= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id					= $this->session->userdata('id_user');		
		$pk								= $this->pk;
		$id  							= $this->input->post($pk);
		$cek 							= $this->m_admin->getByID($tabel,$pk,$id)->num_rows();
		if($cek == 0){
			$data['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');		
			$data['tipe_ahm'] 						= $this->input->post('tipe_ahm');		
			$data['deskripsi_ahm'] 						= $this->input->post('deskripsi_ahm');		
			$data['tipe_customer'] 		= $this->input->post('tipe_customer');		
			$data['tipe_part'] 		= $this->input->post('tipe_part');		
			$data['id_segment'] 			= $this->input->post('id_segment');		
			$data['id_kategori'] 		= $this->input->post('id_kategori');				
			$data['id_series'] 		= $this->input->post('id_series');				
			$data['cc_motor'] 			= $this->input->post('cc_motor');				
			$data['tgl_awal']							= $this->input->post('tgl_awal');
			$data['tgl_akhir']				= $this->input->post('tgl_akhir');				
			if($this->input->post('status_wl') == '1'){
				$data['status_wl']					= $this->input->post('status_wl');				
			}else{
				$data['status_wl'] 			= "";
			}
			$data['qty_wl']				= $this->input->post('qty_wl');				
			$data['deskripsi_samsat']	= $this->input->post('deskripsi_samsat');				
			$data['no_mesin']					= $this->input->post('no_mesin');				
			$data['kode_ptm']					= $this->input->post('kode_ptm');				
			$data['kode_part']				= $this->input->post('kode_part');						
			if($this->input->post('active') == '1'){
				$data['active']				= $this->input->post('active');		
			}else{
				$data['active'] 			= "";
			}		
			$data['created_at']			= $waktu;
			$data['created_by']			= $login_id;				
			$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe'>";
		}
	}

	public function delete_part_ev()
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe'>";
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
		$pk 				= $this->pk;		
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);		
		$data['dt_tipe'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_segment'] = $this->m_admin->getSortCond("ms_segment","segment","ASC");	
		$data['dt_series'] 	= $this->m_admin->getSortCond("ms_series","series","ASC");			
		$data['dt_kategori'] = $this->m_admin->getSortCond("ms_kategori","kategori","ASC");			
		$data['isi']    	= $this->page;		
		$data['title']		= $this->title;		
		$data['set']			= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$tabel				= $this->tables;
		$pk 					= $this->pk;
		$waktu 				= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id			= $this->session->userdata('id_user');		

		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_tipe_kendaraan'] 				= $this->input->post('id_tipe_kendaraan');		
			$data['tipe_ahm'] 						= $this->input->post('tipe_ahm');		
			$data['deskripsi_ahm'] 						= $this->input->post('deskripsi_ahm');		
			$data['tipe_customer'] 		= $this->input->post('tipe_customer');		
			$data['tipe_part'] 		= $this->input->post('tipe_part');		
			$data['id_segment'] 			= $this->input->post('id_segment');		
			$data['id_kategori'] 		= $this->input->post('id_kategori');				
			$data['id_series'] 		= $this->input->post('id_series');				
			$data['cc_motor'] 			= $this->input->post('cc_motor');				
			$data['tgl_awal']							= $this->input->post('tgl_awal');
			$data['tgl_akhir']				= $this->input->post('tgl_akhir');				
			if($this->input->post('status_wl') == '1'){
				$data['status_wl']					= $this->input->post('status_wl');				
			}else{
				$data['status_wl'] 			= "";
			}
			$data['qty_wl']				= $this->input->post('qty_wl');				
			$data['deskripsi_samsat']	= $this->input->post('deskripsi_samsat');				
			$data['no_mesin']					= $this->input->post('no_mesin');				
			$data['kode_ptm']					= $this->input->post('kode_ptm');				
			$data['kode_part']				= $this->input->post('kode_part');						
			if($this->input->post('active') == '1'){
				$data['active']				= $this->input->post('active');		
			}else{
				$data['active'] 			= "";
			}
			$data['updated_at']			= $waktu;
			$data['updated_by']			= $login_id;

			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/tipe'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function view()
	{		
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$page				= $this->page;
		$id 				= $this->input->get('id');
		$d 					= array($pk=>$id);			
		$data['dt_divisi'] = $this->m_admin->getSortCond("ms_divisi","id_divisi","ASC");	
		$data['dt_jabatan'] = $this->m_admin->getSortCond("ms_jabatan","id_jabatan","ASC");	
		$data['dt_agama'] = $this->m_admin->getSortCond("ms_agama","id_agama","ASC");
		$data['dt_tipe'] = $this->m_admin->getByID($tabel,$pk,$id);
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;
		$data['set']		= "detail";									
		$this->template($data);
		
	}
}