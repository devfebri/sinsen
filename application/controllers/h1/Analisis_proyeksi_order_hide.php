<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analisis_proyeksi_order extends CI_Controller {

    var $tables =   "tr_analisis_proyeksi_order";	
		var $folder =   "h1";
		var $page		=		"analisis_proyeksi_order";
    var $pk     =   "id_analisis";
    var $title  =   "Analisis Proyeksi Order";

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
		// $data['dt_stok_ditahan'] = $this->db->query("SELECT ms_stok_ditahan.*,ms_kelompok_harga.kelompok_harga FROM ms_stok_ditahan LEFT JOIN ms_kelompok_harga 
					// ON ms_stok_ditahan.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga");							
		// $data['dt_stok_ditahan']=$this->db->query("SELECT ms_stok_ditahan.*,ms_tipe_kendaraan.tipe_ahm FROM ms_stok_ditahan
		// 	INNER JOIN ms_tipe_kendaraan on ms_stok_ditahan.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
		// 	WHERE ms_stok_ditahan.status<>'new'
		// 	ORDER BY ms_stok_ditahan.id_analisis DESC 
		// 	");
		$data['dt'] = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order 
					inner join ms_dealer on tr_analisis_proyeksi_order.id_dealer=ms_dealer.id_dealer
			ORDER BY id_analisis DESC");
		$this->template($data);		
	}


	public function generate()
	{
		$id_dealer			= $this->input->post('id_dealer');
		$dealer 			= $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$id_dealer' ");
		$data['dealer']		= $dealer->num_rows()>0?$dealer->row()->nama_dealer:'';
		$data['id_dealer']		= $dealer->num_rows()>0?$dealer->row()->id_dealer:'';
		$tipe=$this->db->query("SELECT * FROM ms_stok_ditahan WHERE (select count(id_tipe_kendaraan)as c FROM ms_stok_ditahan)=(select count(id_tipe_kendaraan)as c FROM ms_tipe_kendaraan) ");
			//$this->load->view('h1/t_analisis_proyeksi_order_hitung',$data);
		
		if ($tipe->num_rows()>0) {
			$this->load->view('h1/t_analisis_proyeksi_order_hitung',$data);
		}else{
			echo 'error';
		}
	}

	// public function addStok(){
	// 	$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
	// 	$login_id	= $this->session->userdata('id_user');
	// 	$data['id_tipe_kendaraan']			= $this->input->post('id_tipe_kendaraan');				
	// 	$data['persen_stok_ditahan']				= $this->input->post('persen_stok_ditahan');			
	// 	$data['jenis_moving']				= $this->input->post('jenis_moving');			
	// 	$data['status']					= 'new';					
	// 	$data['created_by']				= $login_id;					
	// 	$data['created_at']				= $waktu;					
	// 	$this->m_admin->insert("ms_stok_ditahan",$data);		
	// 	echo "nihil";
	// }

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

	public function save()
	{		
		$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);
		$login_id	= $this->session->userdata('id_user');	
		$id_dealer	= $this->input->post('id_dealer');

		$data[0]['id_analisis'] = $this->cari_id();
		$data[0]['id_dealer']	 = $id_dealer[0];
		$data[0]['created_by']	 = $login_id;
		$data[0]['created_at']	 = $waktu;
		$data[0]['tahun']	 = date('Y');
		$data[0]['bulan']	 = date('m');
		$data[0]['status']	 = 'input';

		foreach ($id_dealer as $key => $dealer) {
			$dt_detail[$key]['id_analisis'] = $this->cari_id();
			$dt_detail[$key]['id_tipe_kendaraan'] = $this->input->post('tipe_'.$key);
			$dt_detail[$key]['st_awal_md'] = $this->input->post('stok_awal_md_'.$key);
			$dt_detail[$key]['stok_md'] = $this->input->post('stok_md_'.$key);
			$dt_detail[$key]['displan_ahm_awal'] = $this->input->post('displan_ahm_awal_'.$key);
			$dt_detail[$key]['displan_ahm'] = $this->input->post('displan_ahm_'.$key);
			$dt_detail[$key]['penjualan_dealer_m1'] = $this->input->post('penjualan_dealer_m1_'.$key);
			$dt_detail[$key]['penjualan_dealer_m'] = $this->input->post('penjualan_dealer_m_'.$key);
			$dt_detail[$key]['penjualan_all_dealer_m1'] = $this->input->post('penjualan_all_dealer_m1_'.$key);
			$dt_detail[$key]['penjualan_all_dealer_m'] = $this->input->post('penjualan_all_dealer_m_'.$key);
			$dt_detail[$key]['dist_ke_dealer_m1'] = $this->input->post('dist_ke_dealer_m1_'.$key);
			$dt_detail[$key]['dist_ke_dealer_m'] = $this->input->post('dist_ke_dealer_m_'.$key);
			$dt_detail[$key]['distribusi'] = $this->input->post('distribusi_'.$key);
			$dt_detail[$key]['stok_ditahan'] = $this->input->post('stok_ditahan_'.$key);
			$dt_detail[$key]['qty_order'] = $this->input->post('qty_order_'.$key);
			$dt_detail[$key]['suggest_distribusi'] = $this->input->post('suggest_distribusi_'.$key);
			$dt_detail[$key]['jenis_moving'] = $this->input->post('jenis_moving_'.$key);
			$dt_detail[$key]['stok_distribusi'] = $this->input->post('stok_distribusi_'.$key);
		}			

		$this->db->trans_begin();
		$this->db->insert_batch('tr_analisis_proyeksi_order',$data); 
		$this->db->insert_batch('tr_analisis_proyeksi_order_detail',$dt_detail); 
		if ($this->db->trans_status() === FALSE)
            {
                    $this->db->trans_rollback();
                     $_SESSION['pesan'] 		= "Something Wen't Wrong";
					$_SESSION['tipe'] 		= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/analisis_proyeksi_order'>";	
            }
        else
            {
                $this->db->trans_commit();
                $_SESSION['pesan'] 		= "Data has been saved successfully";
				$_SESSION['tipe'] 		= "success";
				echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/analisis_proyeksi_order'>";	
            }
					
	}


	public function add()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;		
		$data['dt_kel'] = $this->m_admin->getSortCond("ms_kelompok_harga","kelompok_harga","ASC");	
		$data['set']	= "insert";									
		$this->template($data);	
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