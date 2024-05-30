<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proyeksi_distribusi_md extends CI_Controller {

    var $tables =   "tr_analisis_proyeksi_order";	
		var $folder =   "dealer";
		var $page		=		"proyeksi_distribusi_md";
    var $pk     =   "id_analisis";
    var $title  =   "Proyeksi Distribusi MD";

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
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt'] = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order 
					inner join ms_dealer on tr_analisis_proyeksi_order.id_dealer=ms_dealer.id_dealer
					WHERE tr_analisis_proyeksi_order.id_dealer = '$id_dealer'
			ORDER BY id_analisis DESC");
		$this->template($data);		
	}


	

	public function cari_id(){		
		$th					= date("Y");		
		$bln					= date("m");		
		$tgl_					= date("d");		
		$tgl					= date("Y-m-d");		
		$pr_num 				= $this->db->query("SELECT * FROM tr_analisis_proyeksi_order WHERE LEFT(created_at,10)='$tgl' ORDER BY id_analisis DESC LIMIT 0,1");						
       
       if($pr_num->num_rows()>0){
       	$row 	= $pr_num->row();		
       	$id = substr($row->id_analisis,9,4); 
        $kode = $th.$bln.$tgl_.sprintf("%04d", $id+1);
		}
		else{
			$kode = $th.$bln.$tgl_.'0001';
		} 
		return $kode;
	}

	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$id_analisis 	= $this->input->get('id');
		$data['dt_analisis'] = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order INNER JOIN ms_dealer on tr_analisis_proyeksi_order.id_dealer=ms_dealer.id_dealer WHERE id_analisis='$id_analisis' ");
		if ($data['dt_analisis']->num_rows() > 0) {
			$data['set']		= "detail";
			$data['dt_detail']	= $this->db->query("SELECT *,ms_tipe_kendaraan.tipe_ahm FROM tr_analisis_proyeksi_order_detail 
				inner join ms_tipe_kendaraan on tr_analisis_proyeksi_order_detail.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
				WHERE id_analisis='$id_analisis'");
			$this->template($data);	
		}else{
			$this->index();
		}
	}

	public function approval()
	{				
		if (!$this->input->post('submit')) {
			$data['isi']    = $this->page;		
			$data['title']	= $this->title;		
			$id_analisis 	= $this->input->get('id');
			$data['dt_analisis'] = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order INNER JOIN ms_dealer on tr_analisis_proyeksi_order.id_dealer=ms_dealer.id_dealer WHERE id_analisis='$id_analisis' ");
			if ($data['dt_analisis']->num_rows() > 0) {
				$dt 				= $data['dt_analisis']->row();
				if ($dt->status!='approved') {
					$data['set']		= "approval";
					$data['dt_detail']	= $this->db->query("SELECT *,ms_tipe_kendaraan.tipe_ahm FROM tr_analisis_proyeksi_order_detail 
						inner join ms_tipe_kendaraan on tr_analisis_proyeksi_order_detail.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
						WHERE id_analisis='$id_analisis'");
					$this->template($data);	
				}else{
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/analisis_proyeksi_order'>";
				}
			}else{
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/analisis_proyeksi_order'>";
			}
		}else{
			$submit 	= $this->input->post('submit');
			if ($submit=='approved') {
				$data['status']	= 'approved';
			}elseif ($submit=='rejected') {
				$data['status']	= 'approved';
			}
			$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
			$login_id		= $this->session->userdata('id_user');

			$data['keterangan'] = $this->input->post('keterangan');
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;
			$this->m_admin->update("tr_analisis_proyeksi_order",$data,'id_analisis',$this->input->post('id_analisis'));
			 $_SESSION['pesan'] 		= "Data has been $submit";
			 $_SESSION['tipe'] 		= "success";
		     echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/analisis_proyeksi_order'>";	
		}
	}

	// public function delete()
	// {		
	// 	$tabel			= $this->tables;
	// 	$pk 			= $this->pk;
	// 	$id 			= $this->input->get('id');		
	// 	$this->db->trans_begin();			
	// 	$this->db->delete($tabel,array($pk=>$id));
	// 	$this->db->trans_commit();			
	// 	$result = 'Success';									

	// 	if($this->db->trans_status() === FALSE){
	// 		$result = 'You can not delete this data because it already used by the other tables';										
	// 		$_SESSION['tipe'] 	= "danger";			
	// 	}else{
	// 		$result = 'Data has been deleted succesfully';										
	// 		$_SESSION['tipe'] 	= "success";			
	// 	}
	// 	$_SESSION['pesan'] 	= $result;
	// 	echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/stok_ditahan'>";
	// }

	// public function ajax_bulk_delete()
	// {
	// 	$tabel			= $this->tables;
	// 	$pk 			= $this->pk;
	// 	$list_id 		= $this->input->post('id');
	// 	foreach ($list_id as $id) {
	// 		$this->m_admin->delete($tabel,$pk,$id);
	// 	}
	// 	echo json_encode(array("status" => TRUE));
	// }
	public function edit()
	{		
		$tabel			= $this->tables;
		$pk 			= $this->pk;		
		$id 			= $this->input->get('id');
		$d 				= array($pk=>$id);		
		$data['dt_stok_ditahan'] = $this->m_admin->kondisi($tabel,$d);
		$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
		$data['dt_item'] = $this->m_admin->getSortCond("ms_item","id_item","ASC");	
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['set']	= "edit";									
		$this->template($data);	
	}
	public function update()
	{		
		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id					= $this->input->post("id");
		$id_				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel,$pk,$id_)->num_rows();
		if($cek == 0 or $id == $id_){
			$data['id_kelompok_harga'] 	= $this->input->post('id_kelompok_harga');		
			$data['harga_bbn'] 					= $this->input->post('harga_bbn');		
			$data['harga_jual'] 				= $this->input->post('harga_jual');		
			$data['id_item'] 						= $this->input->post('id_item');		
			$data['start_date']					= $this->input->post('start_date');		
			$data['end_date']						= $this->input->post('end_date');		
			if($this->input->post('active') == '1') $data['active'] = $this->input->post('active');		
				else $data['active'] 		= "";					
			$data['updated_at']				= $waktu;		
			$data['updated_by']				= $login_id;		
			$this->m_admin->update($tabel,$data,$pk,$id);
			$_SESSION['pesan'] 	= "Data has been updated successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."master/stok_ditahan'>";
		}else{
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
}