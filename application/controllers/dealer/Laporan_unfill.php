<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_unfill extends CI_Controller {

    var $tables =   "tr_prospek";	
		var $folder =   "dealer";
		var $page		=		"laporan_unfill";
    var $pk     =   "id_prospek";
    var $title  =   "Laporan Stok Unfill";

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
		$data['set']		= "view";		
		$id_dealer 			= $this->m_admin->cari_dealer();	
		// $data['dt_do'] 	= $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
		// 												WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) AND
		// 												tr_do_po.id_dealer = '$id_dealer' AND tr_do_po.status = 'approved' ORDER BY tr_do_po.no_do ASC");
		// $data['dt_do'] 	= $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
		// 												WHERE tr_do_po.id_dealer = '$id_dealer' AND tr_do_po.status = 'approved' ORDER BY tr_do_po.no_do ASC");
		
		
		$data['dt_do'] 	= $this->db->query("SELECT tr_do_po.no_do , tr_do_po.tgl_do , tr_do_po.no_po, count(tr_scan_barcode.no_mesin) as sisa
				FROM tr_do_po 
				INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
				join tr_picking_list_view on tr_picking_list_view.no_picking_list  = tr_picking_list.no_picking_list  and tr_picking_list_view.retur =0
				join tr_scan_barcode on tr_scan_barcode.no_mesin  = tr_picking_list_view.no_mesin 
				WHERE tr_do_po.status = 'approved' and tr_scan_barcode.status <4 
				and tr_do_po.id_dealer = '$id_dealer' 
				group by  tr_do_po.no_do , tr_do_po.tgl_do , tr_do_po.no_po
				ORDER BY tr_do_po.no_do ASC
		");
		
		$this->template($data);			
	}
	public function detail()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= "Detail ".$this->title;															
		$id							= $this->input->get("id");															
		$data['id']		  = $this->input->get("id");															
		$data['set']		= "detail";		
		$id_dealer 			= $this->m_admin->cari_dealer();	
		$data['dt_do'] 	= $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
											WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) AND
											tr_do_po.id_dealer = '$id_dealer' AND tr_do_po.status = 'approved' ORDER BY tr_do_po.no_do ASC");
		$this->template($data);			
	}
}