<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Lap_dokumen_handling extends CI_Controller {

	

	var $folder 	=   "h1/laporan";

	var $page		=	"laporan_dokumen_handling";

	var $title  	=   "Laporan Document Handling";



	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_doch');		
		$this->load->model('m_admin');		

		//===== Load Library =====		

		$this->load->library('pdf');	




	}

	protected function template($data)

	{

		$name = $this->session->userdata('nama');

		if($name=="")

		{

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		}else{

			$data['id_menu'] = $this->m_admin->getMenu('Lap_dokumen_handling');

			$data['group'] 	= $this->session->userdata("group");

			$this->load->view('template/header',$data);

			$this->load->view('template/aside');			

			$this->load->view($this->folder."/".$this->page);		

			$this->load->view('template/footer');

		}

	}



	public function index()

	{

		$tgl1 = $this->input->get('tanggal1');

		$tgl2 = $this->input->get('tanggal2');

		if (isset($_GET['cetak'])) {

			if ($_GET['cetak'] == 'cetak') {

				ini_set('memory_limit', '-1');

				ini_set('max_execution_time', 900);

				$mpdf                           = $this->pdf->load();

				$mpdf->allow_charset_conversion =true;  // Set by default to TRUE

				$mpdf->charset_in               ='UTF-8';

				$mpdf->autoLangToFont           = true;

				$data['set']                   	= 'cetak';            

				$data['tanggal1']              	= $this->input->get('tanggal1');      

				$data['tanggal2']              	= $this->input->get('tanggal2');

				$data['date_create']			= get_waktu();



				$data['query'] = $this->m_doch->getData($tgl1, $tgl2);

				$html = $this->load->view('h1/laporan/laporan_dokumen_handling', $data, true);
                
				// render the view into HTML
                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");
				$mpdf->WriteHTML($html);

				// write the HTML into the mpdf

				$date_buat = date("dmY-hi", strtotime(get_waktu()));

				$output = "Document_Handling-$date_buat.pdf";

				$mpdf->Output("$output", 'I');

			} elseif ($_GET['cetak'] == 'export_excel') {
				ini_set('memory_limit', '-1');
				ini_set('max_execution_time', 900);
				$data['set']                   	= 'export_excel';   
				$data['tanggal1']              	= $this->input->get('tanggal1'); 
				$data['tanggal2']              	= $this->input->get('tanggal2');
				$data['date_create']			= get_waktu();
				$data['query'] = $this->m_doch->getData($tgl1, $tgl2);
				$this->load->view('h1/laporan/laporan_dokumen_handling', $data);
		    } elseif ($_GET['cetak'] == 'export_stock') {
				ini_set('memory_limit', '-1');
				ini_set('max_execution_time', 900);
				$data['set']                   	= 'export_excel';            
				$data['tanggal1']              	= $this->input->get('tanggal1');      
				$data['tanggal2']              	= $this->input->get('tanggal2');
				$data['date_create']			= get_waktu();
				$data['query'] = $this->m_doch->getDataDetailDoch($tgl1, $tgl2);
				$this->load->view('h1/laporan/laporan_stock_doch', $data);
		     }
		} else {
			$data['isi']    = $this->page;	
			$data['title']	= $this->title;										
			$data['set']		= "view";			
			$this->template($data);	
		}	
	}	
}