<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Rekap_penerimaan_unit extends CI_Controller {

	

	var $folder =   "h1/report";

	var $page		="rekap_penerimaan_unit";	

	var $isi		="laporan_1";	

	var $title  =   "Data Penerimaan Unit";



	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');		

		//===== Load Library =====		

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

		// $data['dt_vendor'] = $this->db->query("SELECT DISTINCT(ms_vendor.vendor_name),ms_vendor.id_vendor FROM ms_unit_transporter INNER JOIN ms_vendor ON ms_unit_transporter.id_vendor = ms_vendor.id_vendor ORDER BY ms_vendor.id_vendor ASC");

		$this->template($data);		    	    

	}		

	public function	download(){				
		$data['tgl1'] = $tgl1		= $this->input->post('tgl1');
		$data['tgl2'] = $tgl2		= $this->input->post('tgl2');		
		
		$data['re_tgl1'] = date('d-m-Y', strtotime($tgl1)); 
		$data['re_tgl2'] = date('d-m-Y', strtotime($tgl2)); 
		
		$sql = $this->db->query("
     	    select a.no_faktur, STR_TO_DATE(a.tgl_faktur, '%d%m%Y') as tgl_faktur, b.id_modell as id_tipe_kendaraan, b.id_warna, b.no_mesin_lengkap as no_mesin, b.no_rangka, 
            floor(a.harga/a.qty) as dpp, (floor(a.ppn /a.qty)) as ppn, floor(a.pph /a.qty) as pph, a.disc_quo, d.no_polisi, e.vendor_name, a.no_sipb, 
            a.no_sl, b.tgl_sl, d.id_penerimaan_unit, d.tgl_penerimaan
            from tr_shipping_list b 
            join tr_invoice a on b.no_shipping_list = a.no_sl and a.id_tipe_kendaraan = b.id_modell and a.id_warna = b.id_warna
            left join tr_penerimaan_unit_detail c on b.no_shipping_list = c.no_shipping_list
            left join tr_penerimaan_unit d on d.id_penerimaan_unit = c.id_penerimaan_unit
            left join ms_vendor e on d.ekspedisi = e.id_vendor
            where STR_TO_DATE(a.tgl_faktur, '%d%m%Y') between '$tgl1' and '$tgl2'
	    group by b.no_mesin_lengkap
	    order by a.no_faktur asc, a.no_sipb asc, a.no_sl asc, b.id_modell asc, b.id_warna asc, e.vendor_name asc
        ");
		
	    $data['sql'] = $sql;	
    
		$this->load->view("h1/report/template/temp_rekap_penerimaan_unit",$data);

	}	

}