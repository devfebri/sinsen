<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi_unit extends CI_Controller {

    var $tables =   "ms_lokasi_unit";	
		var $folder =   "master";
		var $page		=		"lokasi_unit";
    var $pk     =   "id_lokasi_unit";
    var $title  =   "Master Data Lokasi Unit";
    

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
		$data['dt_lokasi_unit'] = $this->db->query("SELECT ms_lokasi_unit.*,ms_gudang.gudang FROM ms_lokasi_unit INNER JOIN ms_gudang ON ms_lokasi_unit.id_gudang = ms_gudang.id_gudang");
		$this->template($data);	
	}
	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']		= "insert";									
		$data['dt_gudang'] = $this->m_admin->getSortCond("ms_gudang","gudang","ASC");
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");										
		$this->template($data);	
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "detail";
		$lokasi 				= $this->input->get('id');
		$data['dt_lokasi'] = $this->db->query("SELECT * FROM tr_scan_barcode WHERE lokasi = '$lokasi' AND (status = 2 OR status = 1 OR status = 7) ORDER BY id_scan_barcode ASC");			
		$this->template($data);		
	}
	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');		

		$tabel										= $this->tables;
		$pk					= $this->pk;
		$id_gudang 		= $this->input->post('id_gudang');
		$get_gudang = $this->db->query("SELECT * FROM ms_gudang WHERE id_gudang ='$id_gudang'")->row();
		$id_lokasi_unit = $get_gudang->alias.$this->input->post('lantai').$this->input->post('kolom').$this->input->post('baris');
	
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_lokasi_unit)->num_rows();
		if($cek == 0){
			

			$data['id_lokasi_unit'] 		= $id_lokasi_unit;
			$data['lantai'] 		= $this->input->post('lantai');
			$data['kolom'] 		= $this->input->post('kolom');
			$data['baris'] 		= $this->input->post('baris');
			$data['qty'] 		= $this->input->post('qty');
			$data['id_gudang'] 		= $this->input->post('id_gudang');
			$data['status_unit'] 		= $this->input->post('status_unit');
			$data['tipe_dedicated'] 		= $this->input->post('tipe_dedicated');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
				$data['created_at']				= $waktu;		
				$data['created_by']				= $login_id;						
				$this->m_admin->insert($tabel,$data);
			$_SESSION['pesan'] 		= "Data has been saved successfully";
			$_SESSION['tipe'] 		= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/lokasi_unit/add'>";
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
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/lokasi_unit'>";
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
		$data['dt_gudang'] = $this->m_admin->getSortCond("ms_gudang","gudang","ASC");					
		$data['dt_tipe'] = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");					
		$data['dt_lokasi_unit'] = $this->m_admin->kondisi($tabel,$d);
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
			$data['id_lokasi_unit'] 		= $this->input->post('id_lokasi_unit');
			$data['lantai'] 		= $this->input->post('lantai');
			$data['kolom'] 		= $this->input->post('kolom');
			$data['baris'] 		= $this->input->post('baris');
			$data['qty'] 		= $this->input->post('qty');
			$qty 		= $this->input->post('qty');
			$data['id_gudang'] 		= $this->input->post('id_gudang');			
			$data['tipe_dedicated'] 		= $this->input->post('tipe_dedicated');
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;

			$cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$id' AND status = 1 ORDER BY slot DESC LIMIT 0,1");
			if($cek_slot->num_rows() > 0){
				$sl = $cek_slot->row();
				$hasil = $sl->slot;				
				if($hasil < 10){
					$hasil2 = substr($hasil, 1,1);
				}else{
					$hasil2 = $hasil;
				}
				$jum = $cek_slot->num_rows();
				if($qty >= $jum){
					$this->m_admin->update($tabel,$data,$pk,$id);
					$_SESSION['pesan'] 	= "Data has been updated successfully";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/lokasi_unit'>";
				}else{
					$_SESSION['pesan'] 	= "Tidak boleh mengubah QTY melebihi jumlah slot terisi".$qty.$jum;
					$_SESSION['tipe'] 	= "danger";
					echo "<script>history.go(-1)</script>";		
				}
			}else{
				$data['status_unit'] 		= $this->input->post('status_unit');
				$this->m_admin->update($tabel,$data,$pk,$id);	
				$_SESSION['pesan'] 	= "Data has been updated successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/lokasi_unit'>";
			}			
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}