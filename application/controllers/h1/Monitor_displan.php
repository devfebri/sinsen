<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Monitor_displan extends CI_Controller {
    var $tables =   "tr_displan";	
		var $folder =   "h1";
		var $page		=		"monitor_displan";
    var $pk     =   "id_displan";
    var $title  =   "Monitor Dist. Plan";
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
		$this->load->library('csvimport');
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
		$data['dt_displan'] = $this->db->query("SELECT tr_displan.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_displan 
								LEFT JOIN ms_tipe_kendaraan ON tr_displan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
								LEFT JOIN ms_warna ON tr_displan.id_warna = ms_warna.id_warna ORDER BY tr_displan.id_displan DESC");			
		$this->template($data);		
	}
	public function tampil_data2(){
		$tipe = $data['tipe'] = $this->input->post('tipe');		
		$bulan = $data['bulan'] = $this->input->post('bulan');		
		$tahun = $data['tahun'] = $this->input->post('tahun');				
		//$data['dt_do_add'] = $this->db->query($dq);			
		$this->load->view('h1/t_monitor_displan',$data);	
	}
	public function tampil_data()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Filter ".$this->title;															
		$data['set']		= "filter";
		$id_tipe_kendaraan = $data['id_tipe_kendaraan'] = $this->input->post('id_tipe_kendaraan');		
		$bulan = $data['bulan'] = $this->input->post('bulan');		
		$tahun = $data['tahun'] = $this->input->post('tahun');				
		if($id_tipe_kendaraan != "" AND $tahun != "" AND $bulan != ""){
			$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		}elseif($id_tipe_kendaraan != "" AND $tahun != ""){
			$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";		
		}elseif($id_tipe_kendaraan != "" AND $tahun != "" AND $bulan != ""){
			$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";		
		}elseif($id_tipe_kendaraan != "" AND $bulan != ""){
			$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND MID(tr_displan.tanggal,3,2) = '$bulan'";		
		}elseif($tahun != "" AND $bulan != ""){
			$kondisi = "WHERE MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		}elseif($tahun != ""){
			$kondisi = "WHERE RIGHT(tr_displan.tanggal,4) = '$tahun'";
		}elseif($bulan != ""){
			$kondisi = "WHERE MID(tr_displan.tanggal,3,2) = '$bulan'";
		}elseif($id_tipe_kendaraan != ""){
			$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan'";		
		}
		// if($id_tipe_kendaraan != "" AND $id_warna != "" AND $tahun != "" AND $bulan != ""){
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_displan.id_warna = '$id_warna' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($id_tipe_kendaraan != "" AND $id_warna != "" AND $tahun != ""){
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_displan.id_warna = '$id_warna' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($id_tipe_kendaraan != "" AND $id_warna != "" AND $bulan != ""){
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_displan.id_warna = '$id_warna' AND MID(tr_displan.tanggal,3,2) = '$bulan'";
		// }elseif($id_tipe_kendaraan != "" AND $tahun != "" AND $bulan != ""){
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($id_warna != "" AND $tahun != "" AND $bulan != ""){
		// 	$kondisi = "WHERE tr_displan.id_warna = '$id_warna' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($id_warna != "" AND $bulan != ""){
		// 	$kondisi = "WHERE tr_displan.id_warna = '$id_warna' AND MID(tr_displan.tanggal,3,2) = '$bulan'";
		// }elseif($id_warna != "" AND $tahun != ""){
		// 	$kondisi = "WHERE tr_displan.id_warna = '$id_warna' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($id_tipe_kendaraan != "" AND $bulan != ""){
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND MID(tr_displan.tanggal,3,2) = '$bulan'";
		// }elseif($id_tipe_kendaraan != "" AND $id_warna != ""){	
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_displan.id_warna = '$id_warna'";
		// }elseif($tahun != "" AND $bulan != ""){
		// 	$kondisi = "WHERE MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($tahun != ""){
		// 	$kondisi = "WHERE RIGHT(tr_displan.tanggal,4) = '$tahun'";
		// }elseif($bulan != ""){
		// 	$kondisi = "WHERE MID(tr_displan.tanggal,3,2) = '$bulan'";
		// }elseif($id_tipe_kendaraan != ""){
		// 	$kondisi = "WHERE tr_displan.id_tipe_kendaraan = '$id_tipe_kendaraan'";
		// }elseif($id_warna != ""){
		// 	$kondisi = "WHERE tr_displan.id_warna = '$id_warna'";
		// }
		$data['dt_displan'] = $this->db->query("SELECT SUM(tr_displan.qty_plan) AS jum,tr_displan.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_displan 
								LEFT JOIN ms_tipe_kendaraan ON tr_displan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
								LEFT JOIN ms_warna ON tr_displan.id_warna = ms_warna.id_warna 
								".$kondisi."
								GROUP BY tr_displan.id_tipe_kendaraan,tr_displan.id_warna
								ORDER BY tr_displan.id_displan DESC");			
		//$this->template($data);		
		$this->load->view('h1/t_monitor_displan',$data);	
	}
	public function detail_popup()
	{				
		$tipe = $data['tipe'] = $this->input->post("tipe");
		$warna = $data['warna'] = $this->input->post("warna");
		$bulan = $data['bulan'] = $this->input->post("bulan");
		$tahun = $data['tahun'] = $this->input->post("tahun");
		$jenis = $data['jenis'] = $this->input->post("jenis");
		$data['isi']    = $this->page;	
		if($jenis=='qty_plan'){
			$data['dt_displan'] = $this->db->query("SELECT tr_displan.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_displan 
		    LEFT JOIN ms_tipe_kendaraan ON tr_displan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		    LEFT JOIN ms_warna ON tr_displan.id_warna = ms_warna.id_warna 
		    WHERE tr_displan.id_warna = '$warna' AND tr_displan.id_tipe_kendaraan = '$tipe' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'		    
		    ORDER BY tr_displan.id_displan DESC");      
		}elseif($jenis=='qty_do'){
			$data['dt_displan'] = $this->db->query("SELECT tr_sipb.*,ms_warna.warna,ms_tipe_kendaraan.tipe_ahm FROM tr_sipb 
				LEFT JOIN ms_tipe_kendaraan ON tr_sipb.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
		    LEFT JOIN ms_warna ON tr_sipb.id_warna = ms_warna.id_warna 
				WHERE tr_sipb.id_tipe_kendaraan = '$tipe'
    		AND MID(tr_sipb.tgl_sipb,3,2) = '$bulan' AND RIGHT(tr_sipb.tgl_sipb,4) = '$tahun' AND tr_sipb.id_warna = '$warna'");
		}elseif($jenis=='qty_sl'){
			$data['dt_displan'] = $this->db->query("SELECT tr_shipping_list.*, ms_warna.warna,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan FROM tr_shipping_list 
    		LEFT JOIN ms_tipe_kendaraan ON tr_shipping_list.id_modell = ms_tipe_kendaraan.id_tipe_kendaraan
		    LEFT JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna 
				WHERE tr_shipping_list.id_modell = '$tipe' AND MID(tr_shipping_list.tgl_sl,3,2) = '$bulan' 
				AND RIGHT(tr_shipping_list.tgl_sl,4) = '$tahun' AND tr_shipping_list.id_warna = '$warna'");
		}elseif($jenis=='qty_pu'){
			$data['dt_displan'] = $this->db->query("SELECT *,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_scan_barcode 
				LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
				WHERE tr_scan_barcode.tipe_motor = '$tipe'
    		AND MID(tr_scan_barcode.tgl_penerimaan,6,2) = '$bulan' AND LEFT(tr_scan_barcode.tgl_penerimaan,4) = '$tahun' AND tr_scan_barcode.warna = '$warna'");
		}
		$data['title']	= $this->title;						
		$this->load->view("h1/t_dist_detail_popup.php",$data);		
	}
	public function upload()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']		= "upload";		
		$this->template($data);		
	}
	function import_db(){
		$filename   = $_FILES["userfile"]["tmp_name"];
		$name 		= $_FILES["userfile"]["name"];
		$type 		= $_FILES["userfile"]["type"];
		$size 		= $_FILES["userfile"]["size"];
		$name_r     = explode('.', $name);

		if($size > 0 AND $name_r[1] == 'UDDS'){
			$file = fopen($filename,"r");
			$is_header_removed = FALSE;
			$indent="";
			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				$row = array(
					'kode_md'    =>  !empty($importdata[0])?$importdata[0]:'',
					'id_tipe_kendaraan'     =>  !empty($importdata[1])?$importdata[1]:'',
					'id_warna'         =>  !empty($importdata[2])?$importdata[2]:'',
					'jenis_po'        =>  !empty($importdata[3])?$importdata[3]:'',
					'tanggal'       =>  !empty($importdata[4])?$importdata[4]:'',
					'qty_plan'       =>  !empty($importdata[5])?$importdata[5]:''
				);
				$this->db->trans_begin();
				
				// $row_tanggal = substr($importdata[4],2,6);
				$cek_data_displan = $this->db->query("SELECT tanggal, qty_plan FROM tr_displan WHERE tanggal ='$importdata[4]' and id_tipe_kendaraan = '$importdata[1]' AND id_warna = '$importdata[2]' ");

				if($cek_data_displan->num_rows() > 0){			
					// $this->db->delete('tr_displan', ['tanggal' => $row['tanggal']]);	
					// $this->db->insert('tr_displan', $row);
				
					$data_update = array(
						'qty_plan' => $importdata[5]
					);

					$this->db->update('tr_displan', $data_update, array('tanggal' => $importdata[4], 'id_tipe_kendaraan' => $importdata[1], 'id_warna' => $importdata[2]) );
				}else{
					$this->db->insert('tr_displan', $row);
				}
						
				if(!$this->db->trans_status()){
					$this->db->trans_rollback();
					$_SESSION['pesan'] 	= "Data gagal diimport";
					$_SESSION['tipe'] 	= "danger";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_displan'>";	            
					break;
				}else{
					$this->db->trans_commit();                
					$cek_indent = $this->db->query("SELECT id_indent FROM tr_po_dealer_indent WHERE id_tipe_kendaraan = '$importdata[1]' AND id_warna = '$importdata[2]' AND status = 'sent'");
					if($cek_indent->num_rows() > 0){
						$isi = $cek_indent->row();
						if($indent==""){
							$indent = $isi->id_indent;
						}else{
							$indent = $indent.",".$isi->id_indent;
						}
					}
					$_SESSION['pesan'] 	= "Data berhasil diimport";
					$_SESSION['tipe'] 	= "success";
					echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_displan'>";	
				}
			}
			fclose($file);
		}else{
			$_SESSION['pesan'] 	= "Data gagal diimport, tipe data salah!";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/monitor_displan'>";	
		}	
  	}
}