<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_reporting_eta_hotline extends CI_Controller{
	
	var $folder ="h3";
	var $page   ="h3_md_reporting_eta_hotline";	
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
		$data['dealer'] = $this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where id_dealer in('1','2','4','5','6','8','10','13','18','19','22','23','25','28','29','30','38','39','40','41','43','44','46','47','51','54','56','58','64','65','66','69','70','71','74','76','77','78','80','81','82','83','84','85','86','88','90','91','94','96','97','98','101','102','103','104','105','106','107','128','714','715','716')")->result();
		// $data['id_dealer'] = $id_dealer	= $this->m_admin->cari_dealer();
		// $data['start_date']= $start_date= $this->input->post('tgl1');
		// $data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['dashboard'] = false;
		$data['report'] = [];
		$this->template($data);		    	    
	}	

	public function downloadReport()
	{
		$data['id_dealer'] = $id_dealer	= $this->input->post('dealer');
		$data['start_date']= $start_date= $this->input->post('tgl1');
		$data['end_date']  = $end_date	= $this->input->post('tgl2');
		$data['dealer'] = $this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where id_dealer in('1','2','4','5','6','8','10','13','18','19','22','23','25','28','29','30','38','39','40','41','43','44','46','47','51','54','56','58','64','65','66','69','70','71','74','76','77','78','80','81','82','83','84','85','86','88','90','91','94','96','97','98','101','102','103','104','105','106','107','128','714','715','716')")->result();
		if($_POST['process']=='excel'){
			$filter_dealer = '';
			if($id_dealer !='all'){
				$filter_dealer = " AND rd.id_dealer = '$id_dealer'";
			}
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
				where left(rd.created_at,10) >= '$start_date' and left(rd.created_at,10) <='$end_date' $filter_dealer
			");
			$this->load->view("h3/laporan/temp_reporting_eta_hotline",$data);
		}elseif($_POST['process']=='load'){
			$data['isi']       = $this->page;
			$data['title']     = $this->title;
			$data['set']       = "view";
			$data['dashboard'] = true;
			$filter_dealer2 = '';
			if($id_dealer !='all'){
				$filter_dealer2 = " AND c.id_dealer = '$id_dealer'";
			}
			$data['report'] = $this->db->query("
				SELECT rd.id_booking as referensi, 
				rdp.id_part, rdp.id_part_int, po.po_id
				FROM tr_h3_dealer_request_document rd
				JOIN tr_h3_dealer_request_document_parts rdp on rd.id_booking=rdp.id_booking 
				JOIN tr_h3_dealer_purchase_order po on po.id_booking=rd.id_booking 
				JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id=po.po_id and pop.id_part_int=rdp.id_part_int
				where left(rd.created_at,10) >= '$start_date' and left(rd.created_at,10) <='$end_date' $filter_dealer2
	  			");
			// // $report = $data['report'];
				// var_dump($report->result());
				// die();
	  		$this->template($data);		
		}
	}
}
?>