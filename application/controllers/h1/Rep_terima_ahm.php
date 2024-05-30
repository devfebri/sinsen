<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_terima_ahm extends CI_Controller {
	
	var $folder =   "h1/report";
	var $page		=		"rep_terima_ahm";	
	var $isi		=		"laporan_4";	
	var $title  =   "Terima dari AHM & Data SL";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====		
		$this->load->library('PDF_HTML');
		$this->load->library('PDF_HTML_Table');
		$this->load->helper('terbilang');
		$this->load->library('mpdf_l');
		$this->load->library('pdf');		

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
		$data['isi']    = $this->isi;		
		$data['title']	= $this->title;															
		$data['set']		= "view";				
		$this->template($data);		    	    
	}		
	public function download()
	{								
		$data['tgl1'] = $tgl1 = $this->input->post('tgl1');
		$data['tgl2'] = $tgl2 = $this->input->post('tgl2');		
		$sl = $this->input->post('get_sl');		

		if($sl !='on'){
			$data['nama_file'] = 'TerimaAHM';
			$data['count_unit'] = 1;
			$data['sql'] = $this->db->query("select count(tr_shipping_list.no_mesin) as jumlah,  tr_shipping_list.tgl_sl,tr_shipping_list.no_shipping_list,tr_shipping_list.id_modell,tr_penerimaan_unit.ekspedisi,tr_penerimaan_unit.no_polisi
			FROM tr_shipping_list               
            INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin = tr_shipping_list.no_mesin
            INNER JOIN tr_penerimaan_unit_detail ON tr_penerimaan_unit_detail.no_shipping_list = tr_shipping_list.no_shipping_list
            INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit WHERE concat(right(tr_shipping_list.tgl_sl,4),'-',mid(tr_shipping_list.tgl_sl,3,2),'-',left(tr_shipping_list.tgl_sl,2)) BETWEEN '$tgl1' AND '$tgl2'
			group by tr_shipping_list.tgl_sl,tr_shipping_list.no_shipping_list,tr_shipping_list.id_modell,tr_penerimaan_unit.ekspedisi,tr_penerimaan_unit.no_polisi 
			");  
		}else{
			$data['nama_file'] = 'DataSL';
			$data['count_unit'] = 1;
			$data['sql'] = $this->db->query("select tr_shipping_list.tgl_sl,tr_shipping_list.no_shipping_list,tr_shipping_list.id_modell, ms_unit_transporter.id_vendor as ekspedisi, no_pol_eks as no_polisi , count(tr_shipping_list.no_mesin) as jumlah
			FROM tr_shipping_list     
			left join ms_unit_transporter on (tr_shipping_list.no_pol_eks = ms_unit_transporter.no_polisi  or replace(tr_shipping_list.no_pol_eks, ' ', '')  = ms_unit_transporter.no_polisi ) and  ms_unit_transporter.active = 1          
			WHERE concat(right(tr_shipping_list.tgl_sl,4),'-',mid(tr_shipping_list.tgl_sl,3,2),'-',left(tr_shipping_list.tgl_sl,2)) BETWEEN '$tgl1' AND '$tgl2'
			group by tr_shipping_list.tgl_sl,tr_shipping_list.no_shipping_list,tr_shipping_list.id_modell, tr_shipping_list.no_pol_eks  
			");  
		}
		
		$this->load->view('h1/report/template/temp_terima_ahm',$data);
	}
}