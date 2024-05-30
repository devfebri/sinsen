<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_reporting_eta_hotline extends CI_Controller{
	
	var $folder ="dealer";
	var $page   ="h3_dealer_reporting_eta_hotline";	
	var $isi    ="Reporting ETA Hotline";	
	var $title  ="Reporting ETA Hotline";
	
	public function __construct(){	
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');	
		//===== Load Library =====		
		// $this->load->library('pdf');		
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

	protected function template($data){
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
	
	public function index(){	
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		// $data['id_dealer'] = $id_dealer	= $this->m_admin->cari_dealer();
		// $data['start_date']= $start_date= $this->input->post('tgl1');
		// $data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['dashboard'] = false;
		$data['report'] = [];
		$this->template($data);		    	    
	}	

	public function downloadReport()
	{
		$data['id_dealer'] = $id_dealer	= $this->m_admin->cari_dealer();
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');

		if($_POST['process']=='excel'){
			$data['report'] = $this->db->query("
				SELECT mch.nama_customer, mch.no_hp, mch.id_tipe_kendaraan as deskripsi, rd.id_booking as referensi, rd.created_at as tgl_pesan, 
				rdp.id_part, rdp.id_part_int, rdp.kuantitas as qty, rdp.eta_terlama, pop.id_part as pn_po, pop.kuantitas as qty_po, po.po_id,
				(CASE WHEN sl.id_shipping_list IS NULL THEN '-' ELSE sl.id_shipping_list END) as no_shipping, (CASE WHEN sl.id_shipping_list IS NULL THEN '-' ELSE sl.id_ref END) as ref_shipping, po.tanggal_order as pesan_ke_md
				FROM tr_h3_dealer_request_document rd
				JOIN tr_h3_dealer_request_document_parts rdp on rd.id_booking=rdp.id_booking 
				JOIN ms_customer_h23 mch on mch.id_customer=rd.id_customer
				JOIN tr_h3_dealer_purchase_order po on po.id_booking=rd.id_booking 
				JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id=po.po_id and pop.id_part_int=rdp.id_part_int 
				LEFT JOIN tr_h3_dealer_shipping_list sl on sl.id_ref=po.po_id 
				where left(rd.created_at,10) >= '$start_date' and left(rd.created_at,10) <='$end_date' and rd.id_dealer='$id_dealer' 
			");
			// var_dump($data['report']);
			// die();
			$this->load->view("dealer/laporan/temp_reporting_eta_hotline",$data);
		}
		elseif($_POST['process']=='load'){
			$data['isi']       = $this->page;
			$data['title']     = $this->title;
			$data['set']       = "view";
			$data['dashboard'] = true;
			$data['report'] = $this->db->query("
				SELECT rd.id_booking as referensi, 
				rdp.id_part, rdp.id_part_int, po.po_id
				FROM tr_h3_dealer_request_document rd
				JOIN tr_h3_dealer_request_document_parts rdp on rd.id_booking=rdp.id_booking 
				JOIN tr_h3_dealer_purchase_order po on po.id_booking=rd.id_booking 
				JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id=po.po_id and pop.id_part_int=rdp.id_part_int
				where left(rd.created_at,10) >= '$start_date' and left(rd.created_at,10) <='$end_date' and rd.id_dealer='$id_dealer' 
	  			");
	  $this->template($data);		
		}
	}
}
?>