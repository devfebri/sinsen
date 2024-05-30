<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Invoice_ahm extends CI_Controller {



	var $tables =   "tr_invoice";	

	var $folder =   "h1";

	var $isi		=		"invoice_terima";	

	var $page		=		"invoice_ahm";

	var $pk     =   "no_faktur";

	var $title  =   "Invoice AHM Unit";



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

		$this->load->library('csvimport');



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
		$data['page']   = $this->page;		
		$data['set']		= "view";				
		// $data['dt_invoice'] = $this->db->query("SELECT DISTINCT(no_faktur),tgl_faktur,tgl_pokok,tgl_ppn,tgl_pph,status FROM tr_invoice ORDER BY tr_invoice.no_faktur DESC");			
		$data['dt_invoice'] = $this->db->query("SELECT DISTINCT(no_faktur),tgl_faktur,tgl_pokok,tgl_ppn,tgl_pph,status FROM tr_invoice ORDER BY CONCAT_WS('-', RIGHT(tr_invoice.tgl_faktur,4),MID(tr_invoice.tgl_faktur,3,2),LEFT(tr_invoice.tgl_faktur,2)) DESC LIMIT 0,10");			
		$this->template($data);		
	}

	public function detail()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title;															

		$data['set']		= "detail";

		$no_faktur = $this->input->get('id');

		$data['dt_invoice'] = $this->db->query("SELECT tr_invoice.id_tipe_kendaraan ,tr_invoice.id_warna ,tr_invoice.qty ,tr_invoice.no_sipb ,tr_invoice.no_sl ,tr_invoice.disc_quo ,tr_invoice.disc_type ,tr_invoice.disc_other ,tr_invoice.harga ,tr_invoice.ppn ,tr_invoice.pph , b.deskripsi_ahm FROM tr_invoice join ms_tipe_kendaraan b on b.id_tipe_kendaraan =  tr_invoice.id_tipe_kendaraan WHERE tr_invoice.no_faktur = '$no_faktur' ORDER BY tr_invoice.no_sl ASC");			

		$this->template($data);		

	}

	public function upload()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title;															

		$data['set']		= "upload";		

		$this->template($data);		

	}

	function import_db(){

		$filename = $_FILES["userfile"]["tmp_name"];

		if($_FILES['userfile']['size'] > 0)

		{

			$file = fopen($filename,"r");

			$is_header_removed = FALSE;

			$no = array();$no1 = 0;$no2 = 0;$jum = 0;$jum2 = 1;$isi = "";$cek_sipb = "";$no3 = 0;

			while(($importdata = fgetcsv($file, 10000, ";")) !== FALSE)

			{

				// if(!$is_header_removed){

				// 	$is_header_removed = TRUE;

				// 	continue;

				// }



				$row = array(

					'no_faktur'    =>  !empty($importdata[0])?$importdata[0]:'',

					'tgl_faktur'     =>  !empty($importdata[1])?$importdata[1]:'',

					'tgl_pokok'         =>  !empty($importdata[2])?$importdata[2]:'',

					'tgl_ppn'        =>  !empty($importdata[3])?$importdata[3]:'',

					'tgl_pph'       =>  !empty($importdata[4])?$importdata[4]:'',

					'no_sl'       =>  !empty($importdata[5])?$importdata[5]:'',

					'no_sipb'       =>  !empty($importdata[6])?$importdata[6]:'',

					'id_tipe_kendaraan'       =>  !empty($importdata[7])?$importdata[7]:'',

					'id_warna'       =>  !empty($importdata[8])?$importdata[8]:'',

					'qty'       =>  !empty($importdata[9])?$importdata[9]:'',

					'harga'       =>  !empty($importdata[10])?$importdata[10]:'',

					'ppn'       =>  !empty($importdata[11])?$importdata[11]:'',

					'pph'       =>  !empty($importdata[12])?$importdata[12]:'',

					'disc_quo'       =>  !empty($importdata[13])?$importdata[13]:'',

					'disc_type'       =>  !empty($importdata[14])?$importdata[14]:'',

					'disc_other'       =>  !empty($importdata[15])?$importdata[15]:''					

				);



				$cek = $this->db->query("SELECT * FROM tr_invoice WHERE no_sl = '$importdata[5]' AND no_faktur = '$importdata[0]' AND id_tipe_kendaraan = '$importdata[7]' AND id_warna = '$importdata[8]'");

				if($cek->num_rows() == 0){

					$sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$importdata[6]'");

					if($sipb->num_rows() > 0){

						$this->db->trans_begin();

						$this->db->insert('tr_invoice', $row);

						if(!$this->db->trans_status()){

							$this->db->trans_rollback();

						}else{

							$this->db->trans_commit();

						}

						$no2++;

					}else{

						if($cek_sipb==""){

							$cek_sipb = $jum2;

						}else{

							$cek_sipb = $cek_sipb.",".$jum2;

						}

						$no3++;

					}

				}else{

					if($isi==""){

						$isi = $jum;

					}else{

						$isi = $isi.",".$jum;

					}

					$no1++;

				}

				$jum++;

				$jum2++;

			}

			fclose($file);

			$_SESSION['pesan'] 	= $jum." Data yang anda import. Berhasil = ".$no2." data. Gagal = ".$no1." data (".$isi."). Tidak ditemukan No SIPB = ".$no3." data (".$cek_sipb.")";

			$_SESSION['tipe'] 	= "success";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	

		}else{

			$_SESSION['pesan'] 	= "Data gagal diimport";

			$_SESSION['tipe'] 	= "danger";

			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice'>";	

		}				

  }

  public function cari_id(){		

		$tgl						= date("d");

		$bln 						= date("m");		

		$th 						= date("Y");

		$isi = $tgl.$bln.$th;

				

		//$pr_num = $this->db->query("SELECT * FROM tr_monitor_tempo WHERE tgl_jatuh_tempo = '$isi' ORDER BY no_rekap DESC LIMIT 0,1");							

		$pr_num = $this->db->query("SELECT * FROM tr_monitor_tempo ORDER BY no_rekap DESC LIMIT 0,1");							

		if($pr_num->num_rows()>0){

			$row 	= $pr_num->row();				

			$pan  = strlen($row->no_rekap)-3;

			$id 	= substr($row->no_rekap,11,5)+1;			

			$isi 	= sprintf("%'.05d",$id);		

			$kode = $th.$bln."/INV/".$isi;

		}else{

			$kode = $th.$bln."/INV/00001";

		}						

		return $kode;

	}

  public function approve(){

  	$id = $this->input->get('id');

  	$waktu 		= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id	= $this->session->userdata('id_user');		

		$ir = $this->m_admin->getByID("tr_invoice","no_faktur",$id)->row();

  	// $tr = $this->db->query("SELECT DISTINCT(tgl_pokok) AS tgl_pokok,no_faktur,tgl_faktur,SUM(ppn*qty) AS jum_ppn,

  	// 			SUM(pph*qty) AS jum_pph,SUM(disc_quo+disc_type+disc_other) AS jum_disc,SUM(qty) jum_qty,SUM(qty*harga) AS jum_bayar,

  	// 			SUM(harga * qty) AS jum_amount FROM tr_invoice WHERE tgl_pokok = '$ir->tgl_pokok'");

		$tr = $this->db->query("SELECT DISTINCT(tgl_pokok) AS tgl_pokok,no_faktur,tgl_faktur,SUM(ppn) AS jum_ppn,

  				SUM(pph) AS jum_pph,SUM(disc_quo+disc_type+disc_other) AS jum_disc,SUM(qty) jum_qty,SUM(harga) AS jum_bayar,

  				SUM(harga) AS jum_amount FROM tr_invoice WHERE tgl_pokok = '$ir->tgl_pokok'");

  	foreach ($tr->result() as $isi) {  		

	  	$data['tgl_jatuh_tempo']	= $isi->tgl_pokok;  		

	  	$data['total_pembayaran'] = $isi->jum_bayar;  		

	  	$data['total_amount']	 		= $isi->jum_amount;  		

	  	$data['total_diskon'] 		= $isi->jum_disc;  		

	  	$data['total_ppn'] 				= $isi->jum_ppn;  		

	  	$data['total_pph'] 				= $isi->jum_pph;  		  		

	  	$data['total_bayar'] 			= $isi->jum_amount + $isi->jum_pph + $isi->jum_ppn;  		  		

	  	//$data['total_bayar'] 			= $isi->jum_amount + $isi->jum_pph + $isi->jum_ppn - $isi->disc;  		  		

	  	$data['no_faktur']				= $isi->no_faktur;  		

	  	$data['tgl_faktur']	 			= $isi->tgl_faktur;  		

	  	$data['status_monitor'] 	= "input";  		

	  	$data['created_at'] 			= $waktu;  		

	  	$data['created_by'] 			= $login_id;

	  	$cek = $this->m_admin->getByID("tr_monitor_tempo","tgl_jatuh_tempo",$ir->tgl_pokok);

	  	if($cek->num_rows() > 0){

	  		$y = $cek->row();

	  		$this->m_admin->update("tr_monitor_tempo",$data,"id_monitor_tempo",$y->id_monitor_tempo);

	  	}else{

	  		$data['no_rekap'] 				= $this->cari_id();

	  		$this->m_admin->insert("tr_monitor_tempo",$data);

	  	}	  	  		

  	}





  	$this->db->query("UPDATE tr_invoice SET status = 'approve' WHERE no_faktur = '$id'");

  	$_SESSION['pesan'] 	= "Data berhasil diubah";

		$_SESSION['tipe'] 	= "success";

		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice_ahm'>";	

  }

  public function reject(){

  	$id = $this->input->get('id');

  	$this->db->query("UPDATE tr_invoice SET status = 'reject' WHERE no_faktur = '$id'");

  	$_SESSION['pesan'] 	= "Data berhasil diubah";

		$_SESSION['tipe'] 	= "success";

		echo "<meta http-equiv='refresh' content='0; url=".base_url()."h1/invoice_ahm'>";	

  }

}