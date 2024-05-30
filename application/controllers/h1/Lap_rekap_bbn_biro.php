<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Lap_rekap_bbn_biro extends CI_Controller {

	

	var $folder 	=   "h1/laporan";

	var $page		=	"laporan_rekap_bbn_biro";

	var $title  	=   "Laporan Rekap BBN Biro MD";



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

		// $tgl2 = $this->input->get('tanggal2');

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

				// $data['tanggal2']              	= $this->input->get('tanggal2');

				$data['date_create']			= get_waktu();


				
				// $data['query'] = $this->db->query("
				// select a.nama_dealer,b.tgl_mohon_samsat,b.no_bastd,b.nama_konsumen,b.no_hp,b.alamat,b.no_mesin,b.no_rangka,b.biaya_bbn,b.biaya_bbn_md_bj,d.no_stnk,d.no_pol,d.no_plat,d.no_bpkb from tr_pengajuan_bbn_detail b  join tr_pengajuan_bbn c on b.no_bastd = c.no_bastd Left join ms_dealer a on a.id_dealer = c.id_dealer left join tr_entry_stnk d on b.no_mesin =d.no_mesin
            	// where b.tgl_mohon_samsat BETWEEN '$tgl1' and '$tgl2' order by b.tgl_mohon_samsat,a.nama_dealer
				// ");

				$data['query'] = $this->db->query("
					select a.nama_dealer,b.tgl_mohon_samsat,b.no_bastd,b.nama_konsumen,b.no_hp,b.alamat,b.no_mesin,b.no_rangka,b.biaya_bbn,b.biaya_bbn_md_bj,d.no_stnk,d.no_pol,d.no_plat,d.no_bpkb, f.tgl_kirim_stnk , h.tgl_kirim_bpkb, j.tgl_kirim_plat, k.no_serah_bpkb , l.no_serah_plat , m.no_serah_stnk 
					from tr_pengajuan_bbn_detail b  
					join tr_pengajuan_bbn c on b.no_bastd = c.no_bastd 
					Left join ms_dealer a on a.id_dealer = c.id_dealer 
					left join tr_entry_stnk d on b.no_mesin =d.no_mesin
					left join tr_kirim_stnk_detail e on e.no_mesin = b.no_mesin 
					left join tr_kirim_stnk f on f.no_kirim_stnk = e.no_kirim_stnk 
					left join tr_kirim_bpkb_detail g on g.no_mesin = b.no_mesin 
					left join tr_kirim_bpkb h on h.no_kirim_bpkb = g.no_kirim_bpkb 
					left join tr_kirim_plat_detail i on i.no_mesin = b.no_mesin 
					left join tr_kirim_plat j on i.no_kirim_plat = j.no_kirim_plat 
					left join tr_penyerahan_bpkb_detail k on k.no_mesin = b.no_mesin 
					left join tr_penyerahan_plat_detail l on l.no_mesin = b.no_mesin 
					left join tr_penyerahan_stnk_detail m on m.no_mesin = b.no_mesin 
					where b.tgl_mohon_samsat = '$tgl1' order by b.tgl_mohon_samsat,a.nama_dealer
				");

				

				// $data['query']					= $this->db->query("

				// 	SELECT

				// 		c.nama_dealer,

				// 		a.tgl_mohon_samsat,

				// 		a.no_bastd,

				// 		a.nama_konsumen,

				// 		a.alamat,

				// 		a.no_mesin,

				// 		a.no_rangka,

				// 		a.biaya_bbn,

				// 		a.biaya_bbn_md_bj 

				// 	FROM

				// 		tr_pengajuan_bbn_detail a

				// 		JOIN (

				// 		SELECT

				// 			id_dealer,

				// 			no_mesin 

				// 		FROM

				// 			tr_sales_order UNION

				// 		SELECT

				// 			b.id_dealer,

				// 			a.no_mesin 

				// 		FROM

				// 			tr_sales_order_gc_nosin a

				// 			JOIN tr_sales_order_gc b ON a.no_spk_gc = b.no_spk_gc 

				// 		) b ON b.no_mesin = a.no_mesin

				// 		JOIN ms_dealer c ON b.id_dealer = c.id_dealer 

				// 	WHERE

				// 		a.id_generate IS NOT NULL 

				// 		AND a.tgl_mohon_samsat BETWEEN '$tgl1' 

				// 		AND '$tgl2' 

				// 	ORDER BY

				// 		a.tgl_mohon_samsat ASC,

				// 		c.nama_dealer,

				// 		a.nama_konsumen

				// 	");

				

				$html = $this->load->view('h1/laporan/laporan_rekap_bbn_biro', $data, true);
                
				// render the view into HTML
                $mpdf->AddPage("L","","","","","5","5","15","5","","","","","","","","","","","","A1");
				$mpdf->WriteHTML($html);

				// write the HTML into the mpdf

				$date_buat = date("dmY-hi", strtotime(get_waktu()));

				$output = "rekap_bbn-$date_buat.pdf";

				$mpdf->Output("$output", 'I');

			} elseif ($_GET['cetak'] == 'export_excel') {

				ini_set('memory_limit', '-1');

				ini_set('max_execution_time', 900);

				$data['set']                   	= 'export_excel';            

				$data['tanggal1']              	= $this->input->get('tanggal1');      

				// $data['tanggal2']              	= $this->input->get('tanggal2');

				$data['date_create']			= get_waktu();

				// $data['query'] = $this->db->query("
				// select a.nama_dealer,b.tgl_mohon_samsat,b.no_bastd,b.nama_konsumen,b.no_hp,b.alamat,b.no_mesin,b.no_rangka,b.biaya_bbn,b.biaya_bbn_md_bj,d.no_stnk,d.no_pol,d.no_plat,d.no_bpkb from tr_pengajuan_bbn_detail b  join tr_pengajuan_bbn c on b.no_bastd = c.no_bastd Left join ms_dealer a on a.id_dealer = c.id_dealer left join tr_entry_stnk d on b.no_mesin =d.no_mesin
            	// where b.tgl_mohon_samsat BETWEEN '$tgl1' and '$tgl2' order by b.tgl_mohon_samsat,a.nama_dealer
				// ");

				$data['query'] = $this->db->query("
					select a.nama_dealer,b.tgl_mohon_samsat,b.no_bastd,b.nama_konsumen,b.no_hp,b.alamat,b.no_mesin,b.no_rangka,b.biaya_bbn,b.biaya_bbn_md_bj,d.no_stnk,d.no_pol,d.no_plat,d.no_bpkb, f.tgl_kirim_stnk , h.tgl_kirim_bpkb, j.tgl_kirim_plat, k.no_serah_bpkb , l.no_serah_plat , m.no_serah_stnk 
					from tr_pengajuan_bbn_detail b  
					join tr_pengajuan_bbn c on b.no_bastd = c.no_bastd 
					Left join ms_dealer a on a.id_dealer = c.id_dealer 
					left join tr_entry_stnk d on b.no_mesin =d.no_mesin
					left join tr_kirim_stnk_detail e on e.no_mesin = b.no_mesin 
					left join tr_kirim_stnk f on f.no_kirim_stnk = e.no_kirim_stnk 
					left join tr_kirim_bpkb_detail g on g.no_mesin = b.no_mesin 
					left join tr_kirim_bpkb h on h.no_kirim_bpkb = g.no_kirim_bpkb 
					left join tr_kirim_plat_detail i on i.no_mesin = b.no_mesin 
					left join tr_kirim_plat j on i.no_kirim_plat = j.no_kirim_plat 
					left join tr_penyerahan_bpkb_detail k on k.no_mesin = b.no_mesin 
					left join tr_penyerahan_plat_detail l on l.no_mesin = b.no_mesin 
					left join tr_penyerahan_stnk_detail m on m.no_mesin = b.no_mesin 
					where b.tgl_mohon_samsat = '$tgl1' order by b.tgl_mohon_samsat,a.nama_dealer
				");



				// $data['query']					= $this->db->query("

				// 	SELECT

				// 		c.nama_dealer,

				// 		a.tgl_mohon_samsat,

				// 		a.no_bastd,

				// 		a.nama_konsumen,

				// 		a.alamat,

				// 		a.no_mesin,

				// 		a.no_rangka,

				// 		a.biaya_bbn,

				// 		a.biaya_bbn_md_bj 

				// 	FROM

				// 		tr_pengajuan_bbn_detail a

				// 		JOIN (

				// 		SELECT

				// 			id_dealer,

				// 			no_mesin 

				// 		FROM

				// 			tr_sales_order UNION

				// 		SELECT

				// 			b.id_dealer,

				// 			a.no_mesin 

				// 		FROM

				// 			tr_sales_order_gc_nosin a

				// 			JOIN tr_sales_order_gc b ON a.no_spk_gc = b.no_spk_gc 

				// 		) b ON b.no_mesin = a.no_mesin

				// 		JOIN ms_dealer c ON b.id_dealer = c.id_dealer 

				// 	WHERE

				// 		a.id_generate IS NOT NULL 

				// 		AND a.tgl_mohon_samsat BETWEEN '$tgl1' 

				// 		AND '$tgl2' 

				// 	ORDER BY

				// 		a.tgl_mohon_samsat ASC,

				// 		c.nama_dealer,

				// 		a.nama_konsumen

				// 	");

				

				$this->load->view('h1/laporan/laporan_rekap_bbn_biro', $data);

		    }

		} else {

			$data['isi']    = $this->page;		

			$data['title']	= $this->title;															

			$data['set']		= "view";			

			$this->template($data);	

		}	

		



	}	





}