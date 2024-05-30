<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Rep_penerimaan_unit extends CI_Controller {

	

	var $folder =   "h1/report";

	var $page		=		"rep_penerimaan_unit";	

	var $isi		=		"laporan_1";	

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

		$data['dt_vendor'] = $this->db->query("SELECT DISTINCT(ms_vendor.vendor_name),ms_vendor.id_vendor FROM ms_unit_transporter INNER JOIN ms_vendor ON ms_unit_transporter.id_vendor = ms_vendor.id_vendor ORDER BY ms_vendor.id_vendor ASC");

		$this->template($data);		    	    

	}		

	public function	download(){		
		// log_r($_POST);		

		$data['tgl1'] = $tgl1		= $this->input->post('tgl1');

		$data['tgl2'] = $tgl2		= $this->input->post('tgl2');				

		$data['ekspedisi'] = $ekspedisi	= $this->input->post('ekspedisi');

		$where = "";
	        if($ekspedisi!=""){
	            $where = "AND c.ekspedisi = '$ekspedisi'";
	        }
	 	// $data['sql'] = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
	  //    		INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit 		
	  //    		WHERE tr_penerimaan_unit.tgl_penerimaan BETWEEN '$tgl1' AND '$tgl2' $where");


	    $data['sql'] = $this->db->query("
	    				SELECT
							a.no_shipping_list,
							a.no_mesin,
							a.no_rangka,
							a.warna,
							a.lokasi,
							a.slot,
							a.tipe_motor,
							a.id_item as id_item2,
							concat(h.kode_tipe,'-',h.kode_warna) as id_item,
							c.id_penerimaan_unit,
							c.tgl_penerimaan,
							c.no_polisi,
							d.tgl_sl,
							d.no_sipb,
							c.ekspedisi,
							g.tipe_ahm,
							(select tgl_sipb from tr_sipb where no_sipb=d.no_sipb limit 1) as tgl_sipb,
							h.tahun_produksi,
							f.vendor_name
						FROM
							tr_scan_barcode a
							INNER JOIN tr_shipping_list d ON a.no_mesin = d.no_mesin
							INNER JOIN tr_penerimaan_unit_detail b ON d.no_shipping_list = b.no_shipping_list
							INNER JOIN tr_penerimaan_unit c ON b.id_penerimaan_unit = c.id_penerimaan_unit
							INNER JOIN ms_vendor f ON f.id_vendor= c.ekspedisi
							INNER JOIN ms_tipe_kendaraan g ON g.id_tipe_kendaraan=a.tipe_motor
							LEFT JOIN tr_fkb h ON h.no_mesin_spasi=a.no_mesin
						WHERE
							c.tgl_penerimaan BETWEEN '$tgl1' 
							AND '$tgl2' $where");

	    // log_r($this->db->last_query());
		
		// INNER JOIN tr_penerimaan_unit_detail b ON a.no_shipping_list = b.no_shipping_list
		// INNER JOIN tr_penerimaan_unit c ON b.id_penerimaan_unit = c.id_penerimaan_unit
		// INNER JOIN tr_shipping_list d ON a.no_mesin = d.no_mesin
		// INNER JOIN ms_vendor f ON f.id_vendor= c.ekspedisi
		// INNER JOIN ms_tipe_kendaraan g ON g.id_tipe_kendaraan=a.tipe_motor
		// LEFT JOIN tr_fkb h ON h.no_mesin_spasi=a.no_mesin
			
		$this->load->view("h1/report/template/temp_penerimaan_unit",$data);

	}	

}