<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Lap_data_kk extends CI_Controller {

	

	var $folder 	=   "h1/laporan";

	var $page		=	"laporan_data_kk";

	var $title  	=   "Laporan Data KK";



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

// 		$name = $this->session->userdata('nama');

// 		$auth = $this->m_admin->user_auth($this->page,"select");		

// 		$sess = $this->m_admin->sess_auth();						

// 		if($name=="" OR $auth=='false')

// 		{

// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";

// 		}elseif($sess=='false'){

// 			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";

// 		}





	}

	protected function template($data)

	{

		$name = $this->session->userdata('nama');

		if($name=="")

		{

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";

		}else{

			$data['id_menu'] = $this->m_admin->getMenu('lap_rekap_bbn_biro');

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



				$data['query'] = $this->db->query("

				select a.no_spk , a.nama_lengkap , b.no_kk ,  a.nik, (CASE when a.jk = 1 then 'Pria' when a.jk=2 then 'Wanita' else '-' end) as jk,
				a.tempat_lahir , a.tgl_lahir, c.agama , d.pendidikan , f.status_pernikahan , e.hub_keluarga , 
				(CASE when a.jenis_wn = 1 then 'WNI' when a.jenis_wn=2 then 'WNA' else '-' end) as kewarganegaraan
				from tr_cdb_kk a
				join tr_spk b on a.no_spk = b.no_spk 
				join ms_agama c on a.id_agama  = c.id_agama 
				join ms_pendidikan d on d.id_pendidikan  = a.id_pekerjaan 
				join ms_hub_keluarga e on a.id_hub_keluarga = e.id_hub_keluarga 
				join ms_status_pernikahan f on f.id_status_pernikahan = a.id_status_pernikahan 
				where cast(b.created_at as date) between '$tgl1' and '$tgl2'
				order by b.no_kk ASC 


				");
				

				$html = $this->load->view('h1/laporan/laporan_data_kk', $data, true);
                
				// render the view into HTML
                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");
				$mpdf->WriteHTML($html);

				// write the HTML into the mpdf

				$date_buat = date("dmY-hi", strtotime(get_waktu()));

				$output = "rekap_data_kk-$date_buat.pdf";

				$mpdf->Output("$output", 'I');

			} elseif ($_GET['cetak'] == 'export_excel') {

				ini_set('memory_limit', '-1');

				ini_set('max_execution_time', 900);

				$data['set']                   	= 'export_excel';            

				$data['tanggal1']              	= $this->input->get('tanggal1');      

				$data['tanggal2']              	= $this->input->get('tanggal2');

				$data['date_create']			= get_waktu();



				$data['query'] = $this->db->query("

				select a.no_spk , a.nama_lengkap , b.no_kk ,  a.nik, (CASE when a.jk = 1 then 'Pria' when a.jk=2 then 'Wanita' else '-' end) as jk,
				a.tempat_lahir , a.tgl_lahir, c.agama , d.pendidikan , f.status_pernikahan , e.hub_keluarga , 
				(CASE when a.jenis_wn = 1 then 'WNI' when a.jenis_wn=2 then 'WNA' else '-' end) as kewarganegaraan
				from tr_cdb_kk a
				join tr_spk b on a.no_spk = b.no_spk 
				join ms_agama c on a.id_agama  = c.id_agama 
				join ms_pendidikan d on d.id_pendidikan  = a.id_pekerjaan 
				join ms_hub_keluarga e on a.id_hub_keluarga = e.id_hub_keluarga 
				join ms_status_pernikahan f on f.id_status_pernikahan = a.id_status_pernikahan 
				where cast(b.created_at as date) between '$tgl1' and '$tgl2'
				order by b.no_kk ASC 

				");


				$this->load->view('h1/laporan/laporan_data_kk', $data);

		    }

		} else {

			$data['isi']    = $this->page;		

			$data['title']	= $this->title;															

			$data['set']		= "view";			

			$this->template($data);	

		}	

		



	}	





}