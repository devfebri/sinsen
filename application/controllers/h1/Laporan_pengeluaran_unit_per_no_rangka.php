<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pengeluaran_unit_per_no_rangka extends CI_Controller {
	
	var $folder =   "h1/laporan";
	var $page		=		"laporan_pengeluaran_unit_per_no_rangka";
	var $title  =   "Laporan Pengeluaran Unit Per No. Rangka";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('tgl_indo');
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
		if (isset($_GET['cetak'])) {
	      ini_set('memory_limit', '-1');
	      ini_set('max_execution_time', 900);
	      $mpdf                           = $this->pdf->load();
	      $mpdf->allow_charset_conversion =true;  // Set by default to TRUE
	      $mpdf->charset_in               ='UTF-8';
	      $mpdf->autoLangToFont           = true;
	      $data['set']                   	= 'cetak';            
	      $tgl_awal = $data['tgl_awal']              	= $this->input->get('tgl_awal');      
	      $tgl_akhir = $data['tgl_akhir']              	= $this->input->get('tgl_akhir');      
	      $dealer              	= $this->input->get('id_dealer'); 
	      $data['title'] = $this->title;												
	      if ($dealer=='all') {
	      	$sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 
	      		AND id_dealer IN (SELECT id_dealer FROM tr_sales_order WHERE LEFT(tr_sales_order.tgl_bastk,10) BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY id_dealer) AND h1=1
	      		ORDER BY ms_dealer.id_dealer ASC");
			foreach ($sql_dealer->result() as $isi) {
				$data['id_dealer'] = $isi->id_dealer;
	      		$html = $this->load->view('h1/laporan/laporan_pengeluaran_unit_per_no_rangka', $data, true);
	      		
	      		// render the view into HTML
	      		$mpdf->WriteHTML($html);
	      		$mpdf->AddPage('L');
			}
	      }else{
	      	$data['id_dealer'] = $dealer;
	      	$data['page_count'] = $mpdf->page;
	      	$html = $this->load->view('h1/laporan/laporan_pengeluaran_unit_per_no_rangka', $data, true);
	      	// render the view into HTML
	      	// $mpdf->WriteHTML('<div align="center"><b>{PAGENO} / {nbpg}</b></div>');
	      	$mpdf->WriteHTML($html);
	      	// $mpdf->setFooter('{PAGENO}');
	      }
	      // write the HTML into the mpdf
	      $output = 'print.pdf';
	      $mpdf->Output("$output", 'I');
	    }else{
			$data['isi']   = $this->page;		
			$data['title'] = $this->title;													
			$data['page'] = $this->page;													
			$data['set']   = "view";		
			$this->template($data);		    	
    	}
	}		
	public function tes(){		
    $data['tahun']  = 2018;
		$this->load->view('h1/laporan/tes_laporan', $data);
	}
}