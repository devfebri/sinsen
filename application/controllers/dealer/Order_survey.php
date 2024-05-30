<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Order_survey extends CI_Controller {



  var $tables =   "tr_spk";	

	var $folder =   "dealer";

	var $page		=		"order_survey";

  var $pk     =   "id_spk";

  var $title  =   "Order Survey";

    function mata_uang($a){

    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);

	    return number_format($a, 0, ',', '.');

	 }

	 function format_tanggal($a)

	 {

	 	if($a == '0000-00-00'){

	 		return '0000-00-00';

	 	}else{

	 		return date('d-m-Y', strtotime($a));;

	 	}

	 }

	public function __construct()

	{		

		parent::__construct();

		

		//===== Load Database =====

		$this->load->database();

		$this->load->helper('url');

		//===== Load Model =====

		$this->load->model('m_admin');		
		$this->load->model('m_dealer_order_survey_datatables');		

		//===== Load Library =====

		//$this->load->library('fpdf');

		$this->load->library('cfpdf');

		$this->load->library('PDF_HTML');	

		$this->load->library('mpdf_l');		

		$this->load->helper('tgl_indo');





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

		$id_dealer = $this->m_admin->cari_dealer();

		 if (isset($_GET['set_page'])) {

		 	$data['set_page'] = $_GET['set_page'];

		// 	$data['dt_order_survey'] = $this->db->query("SELECT * FROM tr_order_survey INNER JOIN tr_spk ON tr_order_survey.no_spk = tr_spk.no_spk WHERE tr_spk.id_dealer = '$id_dealer'");				

		// }else{

			

		 }

		// $data['dt_order_survey'] = $this->db->query("SELECT *,tr_order_survey.id_finance_company FROM tr_order_survey INNER JOIN tr_spk ON tr_order_survey.no_spk = tr_spk.no_spk WHERE tr_spk.id_dealer = '$id_dealer'");				

		/* $data['dt_order_survey'] = $this->db->query("SELECT *,tr_order_survey.id_finance_company FROM tr_order_survey

			JOIN tr_spk ON tr_order_survey.no_spk=tr_spk.no_spk

		 WHERE tr_order_survey.id_dealer = '$id_dealer' 

		 AND tr_spk.status_spk<>'closed' 

		 ORDER BY no_order_survey DESC");*/		

		$this->template($data);			

	}	

	public function fetch_data_spk_datatables()
	{
		$id_dealer = $this->m_admin->cari_dealer();

		$list = $this->m_dealer_order_survey_datatables->get_datatables($id_dealer);

		$data = array();
		$no = $_POST['start']; 

		foreach($list as $row) {
            $cek=$this->db->query("SELECT max(no_order_survey) as no_order_survey FROM tr_order_survey WHERE no_spk ='$row->no_spk'")->row()->no_order_survey;
            $cek2=$this->db->query("SELECT no_spk FROM tr_hasil_survey WHERE no_spk='$row->no_spk' AND status_approval = 'approved'");
           
              if ($cek != $row->no_order_survey OR $cek2->num_rows() > 0) {                  
                // $prospek = $this->m_admin->getByID("tr_prospek","id_customer",$row->id_customer);
                // if($prospek->num_rows() > 0){
                //   $rt = $prospek->row();
                //   $nama = $rt->nama_konsumen;
                // }else{
                //   $nama = "";
                // }

                // $tipe = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan);
                // if($tipe->num_rows() > 0){
                //   $rs = $tipe->row();
                //   $ahm = $rs->tipe_ahm;
                // }else{
                //   $ahm = "";
                // }

                // $warna = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna);
                // if($warna->num_rows() > 0){
                //   $rw = $warna->row();
                //   $war = $rw->warna;
                // }else{
                //   $war = "";
                // }

                // $leasing = $this->m_admin->getByID("ms_finance_company","id_finance_company",$row->id_finance_company);
                // if($leasing->num_rows() > 0){
                //   $rd = $leasing->row();
                //   $fin = $rd->finance_company;
                // }else{
                //   $fin = "";
                // }

				$tombol = "	<a href='dealer/order_survey/cetak_order?id=$row->no_order_survey' target='_blank'>
							<button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Order Survey</button>
			  				</a>";

			}else{
				if ($cek == $row->no_order_survey AND $cek2->num_rows() == 0) {            
				//   $tipe = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan);
				//   if($tipe->num_rows() > 0){
				// 	$rs = $tipe->row();
				// 	$ahm = $rs->tipe_ahm;
				//   }else{
				// 	$ahm = "";
				//   }
				//   $warna = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna);
				//   if($warna->num_rows() > 0){
				// 	$rw = $warna->row();
				// 	$war = $rw->warna;
				//   }else{
				// 	$war = "";
				//   }
  
				//   $leasing = $this->m_admin->getByID("ms_finance_company","id_finance_company",$row->id_finance_company);
				//   if($leasing->num_rows() > 0){
				// 	$rd = $leasing->row();
				// 	$fin = $rd->finance_company;
				//   }else{
				// 	$fin = "";
				//   }
  
				  $tombol = "<a href='dealer/order_survey/cetak_order?id=$row->no_order_survey' target='_blank'>
				  				<button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Order Survey</button>
							</a>
							<a data-toggle='tooltip' title='Download' href='dealer/order_survey/download?id=$row->no_spk'><button class='btn btn-flat btn-xs btn-success'>Download</button></a>";
				}
			}

			$no++;
			$rows = array();
			// $rows[] = $no;
			// $rows[] = $row->no_order_survey;
			// $rows[] = $row->no_spk;
			// $rows[] = $row->nama_konsumen;
			// $rows[] = $fin;
			// $rows[] = $row->alamat;
			// $rows[] = $ahm;
			// $rows[] = $war;
			// $rows[] = $row->no_ktp;
			// $rows[] = $tombol;
			// $data[] = $rows;
			
			$rows[] = $no;
			$rows[] = $row->no_order_survey;
			$rows[] = $row->no_spk;
			$rows[] = $row->nama_konsumen;
			$rows[] = $row->finance_company;
			$rows[] = $row->alamat;
			$rows[] = $row->tipe_ahm;
			$rows[] = $row->warna;
			$rows[] = $row->no_ktp;
			$rows[] = $tombol;
			$data[] = $rows;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_dealer_order_survey_datatables->count_all($id_dealer),
			"recordsFiltered" => $this->m_dealer_order_survey_datatables->count_filtered($id_dealer),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function gc()

	{				

		$data['isi']    = $this->page;		

		$data['title']	= $this->title." Group Customer";															

		$data['set']		= "view_gc";

		$id_dealer = $this->m_admin->cari_dealer();

		 if (isset($_GET['set_page'])) {

		 	$data['set_page'] = $_GET['set_page'];

		// 	$data['dt_order_survey'] = $this->db->query("SELECT * FROM tr_order_survey INNER JOIN tr_spk ON tr_order_survey.no_spk = tr_spk.no_spk WHERE tr_spk.id_dealer = '$id_dealer'");				

		// }else{

			

		 }

		// $data['dt_order_survey'] = $this->db->query("SELECT *,tr_order_survey.id_finance_company FROM tr_order_survey INNER JOIN tr_spk ON tr_order_survey.no_spk = tr_spk.no_spk WHERE tr_spk.id_dealer = '$id_dealer'");				

		$data['dt_order_survey'] = $this->db->query("SELECT *,tr_order_survey_gc.id_finance_company,tr_order_survey_gc.status_survey FROM tr_order_survey_gc

			JOIN tr_spk_gc ON tr_order_survey_gc.no_spk_gc=tr_spk_gc.no_spk_gc

		 WHERE tr_order_survey_gc.id_dealer = '$id_dealer' 

		 AND tr_spk_gc.status<>'closed' AND tr_spk_gc.status = 'approved' 

		 ORDER BY no_order_survey_gc DESC");				

		$this->template($data);			

	}	



	public function cetak_order(){

		$tgl 				= gmdate("y-m-d", time()+60*60*7);

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= $this->tables;

		$pk 				= $this->pk;		

		$id 				= $this->input->get('id');			



		$pdf = new PDF_HTML('p','mm','A4');

	    $pdf->AddPage();

       // head	  

		  $pdf->SetFont('ARIAL','B',11);

		  $pdf->Cell(190, 6, 'Order Survey', 0, 1, 'C');

		  $pdf->Cell(190, 7, 'DATA CUSTOMER', 1, 1, 'C');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','',10);

			$id_dealer = $this->m_admin->cari_dealer();

		 //  $dt_order_survey = $this->db->query("SELECT *,tr_spk.alamat as alamat_konsumen,ms_karyawan_dealer.nama_lengkap,tr_prospek.email as email_konsumen,tr_spk.no_ktp as ktp_konsumen, tr_spk.no_telp as telp_konsumen,tr_spk.pekerjaan,tr_spk.no_kk,tr_order_survey.created_at,tr_spk.tgl_lahir as tgl_lahir_k,tr_spk.tempat_lahir as tempat_lahir_k ,tr_spk.status_rumah as sts_rmh,tr_spk.email as email_k,tr_spk.voucher_tambahan_2 as vt_2,tr_spk.no_hp,tr_order_survey.id_finance_company



		 //  	FROM tr_order_survey INNER JOIN tr_spk ON tr_order_survey.no_spk = tr_spk.no_spk 

		 //  	LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer

		 //  	LEFT JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk



			// LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin

			

			// LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

			// LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna

		 //  				left join ms_dealer on tr_spk.id_dealer = ms_dealer.id_dealer

		 //  				left join ms_karyawan_dealer on tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer

		 //  	WHERE tr_spk.id_dealer = '$id_dealer' AND no_order_survey='$id' ");	



		   $dt_order_survey = $this->db->query("SELECT *,tr_order_survey.alamat as alamat_konsumen,ms_karyawan_dealer.nama_lengkap,tr_prospek.email as email_konsumen,tr_order_survey.no_ktp as ktp_konsumen, tr_order_survey.no_telp as telp_konsumen,tr_order_survey.pekerjaan,tr_order_survey.no_kk,tr_order_survey.created_at,tr_order_survey.tgl_lahir as tgl_lahir_k,tr_order_survey.tempat_lahir as tempat_lahir_k ,tr_order_survey.status_rumah as sts_rmh,tr_order_survey.email as email_k,tr_order_survey.voucher_tambahan_2 as vt_2,tr_order_survey.no_hp,tr_order_survey.id_finance_company
		  	FROM tr_order_survey
			JOIN tr_prospek ON tr_order_survey.id_customer = tr_prospek.id_customer		
			JOIN ms_tipe_kendaraan ON tr_order_survey.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan	
			JOIN ms_warna ON tr_order_survey.id_warna = ms_warna.id_warna
			join ms_dealer on tr_order_survey.id_dealer = ms_dealer.id_dealer
			join ms_karyawan_dealer on tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
			WHERE tr_order_survey.id_dealer = '$id_dealer' AND no_order_survey='$id' ");	

		  if ($dt_order_survey->num_rows() > 0) {

		  	$dt_order = $dt_order_survey->row();



		  	$penghasilan_penjamin =$dt_order->penghasilan_penjamin;

			$penghasilan =$dt_order->penghasilan;

			$uang_muka =$dt_order->uang_muka;

			$harga_on_road =$dt_order->harga_on_road;

			$dp_stor =$dt_order->dp_stor;

			$angsuran =$dt_order->angsuran;

			$v_t = '';

			if ($dt_order->penghasilan_penjamin!=null OR $dt_order->penghasilan_penjamin!='') {

		  		$penghasilan_penjamin=$this->mata_uang((int)$dt_order->penghasilan_penjamin);

		  	}if ($dt_order->penghasilan!=null OR $dt_order->penghasilan!='') {

		  		$penghasilan=$this->mata_uang((int)$dt_order->penghasilan);



		  	}if ($dt_order->uang_muka!=null OR $dt_order->uang_muka!='') {

		  		$uang_muka=$this->mata_uang($dt_order->uang_muka);

		  	}if ($dt_order->harga_on_road!=null OR $dt_order->harga_on_road!='') {

		  		$harga_on_road=$this->mata_uang($dt_order->harga_on_road);

		  	}if ($dt_order->dp_stor!=null OR $dt_order->dp_stor!='') {

		  		$dp_stor=$this->mata_uang($dt_order->dp_stor);

		  	}if ($dt_order->angsuran!=null OR $dt_order->angsuran!='') {

		  		$angsuran=$this->mata_uang($dt_order->angsuran);

		  	}if ($dt_order->vt_2!=null OR $dt_order->vt_2!='') {

		  		$v_t=$this->mata_uang($dt_order->vt_2);

		  	}



		  	$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_order->id_finance_company'");

		  	$kerja				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_order->pekerjaan'");

		  	if($kerja->num_rows() > 0){

		  		$tr = $kerja->row();

		  		$pekerjaan = $tr->pekerjaan;

		  	}else{

		  		$pekerjaan = "-";

		  	}

		  	$kerja2				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_order->pekerjaan_penjamin'");

		  	if($kerja2->num_rows() > 0){

		  		$tr = $kerja2->row();

		  		$pekerjaan_penjamin = $tr->pekerjaan;

		  	}else{

		  		$pekerjaan_penjamin = "-";

		  	}

		  	$lokasi = explode(',', $dt_order->denah_lokasi);


			if($this->config->item('google_apis')){
				$latitude = str_replace(' ', '', $lokasi[0]);
				$longitude = str_replace(' ', '', $lokasi[1]);
				$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
				$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77",164,25,33,0,'PNG'); //  error google 502
			}else{				
				$latitude = $this->config->item('latitude');
				$longitude = $this->config->item('longitude');
				$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
				$url = base_url('assets/panel/images/chart_qr_md.png');
				$pdf->Image($url, 164, 25, 33, 0, 'PNG');
			}

		  	$pdf->Cell(35, 5, 'No. Survey', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $dt_order->no_order_survey, 0, 1, 'L');

		  	$tgl_survey = explode(' ', $dt_order->created_at);

		  	$tgl = date('d-m-Y', strtotime($tgl_survey[0]));

		  	$pdf->Cell(35, 5, 'Tanggal', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $tgl, 0, 1, 'L');

		  	$pdf->Cell(35, 5, 'Nama Dealer', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $dt_order->nama_dealer, 0, 1, 'L');

		  	$pdf->Cell(35, 5, 'Nama Sales Person', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $dt_order->nama_lengkap, 0, 1, 'L');

		  $pdf->SetFont('ARIAL','BU',10);

		  $pdf->Ln(8);

		  $pdf->Cell(185, 5, $qr_generate, 0, 1, 'R');

		  $pdf->Ln(9);

		  $pdf->Cell(95, 5, 'Pemohon', 0, 0, 'L');$pdf->Cell(95, 5, 'Penjamin', 0, 1, 'L');

		  $pdf->SetFont('ARIAL','',10);

		  $pdf->Cell(35, 5, 'Nama', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->nama_konsumen, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Nama', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5,  $dt_order->nama_penjamin, 0, 1, 'L');

		  

		  $x = $pdf->GetX();

			$y = $pdf->GetY();							

		  $pdf->Cell(35, 5, 'Alamat', 10, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->MultiCell(58, 5, $dt_order->alamat_konsumen, 0, 'L');		  

			$pdf->SetXY($x + 95, $y);

		  $pdf->Cell(35, 5, 'Alamat', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->MultiCell(58, 5, $dt_order->alamat_penjamin, 0, 'L');

		  $pdf->Cell(35, 5, 'Tempat/Tgl Lahir', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tempat_lahir_k.' / '.$this->format_tanggal($dt_order->tgl_lahir_k), 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Tempat/Tgl Lahir', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tempat_lahir_penjamin.' / '.$this->format_tanggal($dt_order->tgl_lahir_penjamin), 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. KTP', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->ktp_konsumen, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'No. KTP', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_ktp_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. HP #1', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_hp, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'No. HP #1', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_hp_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. HP #2', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_hp_2, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. Telepon Rumah', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->telp_konsumen, 0, 1, 'L');

		   $pdf->Cell(35, 5, 'Penghasilan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$penghasilan, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Penghasilan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$penghasilan_penjamin, 0, 1, 'L');

		    $pdf->Cell(35, 5, 'Pekerjaan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $pekerjaan, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Pekerjaan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $pekerjaan_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Stts Kepemilikan Rmh', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->sts_rmh, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Hub. dg Penjamin', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->hub_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. Kartu Keluarga', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_kk, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Nama Ibu Kandung', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->nama_ibu, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'E-Mail', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->email_k, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Nama STNK/BPKB', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->nama_bpkb, 0, 1, 'L');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','B',11);

		  $pdf->Cell(190, 7, 'DATA KREDIT', 1, 1, 'C');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','',10);

		  $pdf->Cell(35, 5, 'Tipe Kendaraan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tipe_ahm, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Down Payment Gross', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$uang_muka, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Warna Kendaraan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->warna, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Nilai Voucher', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$this->mata_uang($dt_order->voucher_2), 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Harga On The Road', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5,'Rp. '.$harga_on_road, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Voucher Tambahan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$v_t, 0, 1, 'L');

		   $program_umum = '';

		  $program_g 	= '';

		  //	$program[0] = $row->program_umum==null?null:$row->program_umum;

		  	if ($dt_order->program_umum!=null OR $dt_order->program_umum!='') {

		  		$program_umum = $dt_order->program_umum;

		  	}if ($dt_order->program_gabungan!=null OR $dt_order->program_gabungan!='') {

		  		$program_g = "&	 $dt_order->program_gabungan";

		  	}

		  $pdf->Cell(35, 5, 'Program', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $program_umum.' '.$program_g, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Down Payment Setor', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$dp_stor, 0, 1, 'L');

		  $pdf->Cell(35, 5, '', 0, 0, 'L');$pdf->Cell(2, 5, '', 0, 0, 'C');$pdf->Cell(58, 5, '', 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Tenor', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tenor, 0, 1, 'L');

		  $pdf->Cell(35, 5, '', 0, 0, 'L');$pdf->Cell(2, 5, '', 0, 0, 'C');$pdf->Cell(58, 5, '', 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Angsuran/Bulan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$angsuran, 0, 1, 'L');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','B',11);

		  $pdf->Cell(190, 7, 'HASIL SURVEY', 1, 1, 'C');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','',10);



		  if ($finco->num_rows()>0) {

		  	$finco = $finco->row()->finance_company;

		  }else{

		  	$finco='';

		  }

		  $pdf->Cell(40,7, 'Finance Company', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, $finco, 0, 1, 'L');

		  $pdf->Cell(40,7, 'Verifier/CMO', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Tanggal Survey', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Jam Report', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Hasil Survey', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Keterangan Hasil Survey', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Ln(20);

		  $pdf->Cell(190,7, '(____________________)', 0, 1, 'C');

		  $pdf->Cell(190,7, 'CMO', 0, 1, 'C');



		  





		  //$pdf->Image(base_url('assets/panel/files/'.$dt_order->file_foto),60,30,30,0,'JPG');

		  if ($dt_order->file_foto!=null OR $dt_order->file_foto!='') {

		  	$pdf->AddPage();

		 	$pdf->centreImage(base_url('assets/panel/files/'.$dt_order->file_foto));

		  }

		  if ($dt_order->file_kk!=null OR $dt_order->file_kk!='') {

		  	$pdf->AddPage();

		 	$pdf->centreImage(base_url('assets/panel/files/'.$dt_order->file_kk));

		  }

		  if ($dt_order->file_ktp_2!=null OR $dt_order->file_ktp_2!='') {

		  	$pdf->AddPage();

		 	$pdf->centreImage(base_url('assets/panel/files/'.$dt_order->file_ktp_2));

		  }

		  }

	  	  



	  	  $pdf->Output(); 

	}

	public function cetak_order_gc(){

		$tgl 				= gmdate("y-m-d", time()+60*60*7);

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');

		$tabel			= $this->tables;

		$pk 				= $this->pk;		

		$id 				= $this->input->get('id');			



		$pdf = new PDF_HTML('p','mm','A4');

	    $pdf->AddPage();

       // head	  

		  $pdf->SetFont('ARIAL','B',11);

		  $pdf->Cell(190, 6, 'Order Survey', 0, 1, 'C');

		  $pdf->Cell(190, 7, 'DATA CUSTOMER', 1, 1, 'C');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','',10);

			$id_dealer = $this->m_admin->cari_dealer();

		 //  $dt_order_survey = $this->db->query("SELECT *,tr_spk.alamat as alamat_konsumen,ms_karyawan_dealer.nama_lengkap,tr_prospek.email as email_konsumen,tr_spk.no_ktp as ktp_konsumen, tr_spk.no_telp as telp_konsumen,tr_spk.pekerjaan,tr_spk.no_kk,tr_order_survey.created_at,tr_spk.tgl_lahir as tgl_lahir_k,tr_spk.tempat_lahir as tempat_lahir_k ,tr_spk.status_rumah as sts_rmh,tr_spk.email as email_k,tr_spk.voucher_tambahan_2 as vt_2,tr_spk.no_hp,tr_order_survey.id_finance_company



		 //  	FROM tr_order_survey INNER JOIN tr_spk ON tr_order_survey.no_spk = tr_spk.no_spk 

		 //  	LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer

		 //  	LEFT JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk



			// LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin

			

			// LEFT JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

			// LEFT JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna

		 //  				left join ms_dealer on tr_spk.id_dealer = ms_dealer.id_dealer

		 //  				left join ms_karyawan_dealer on tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer

		 //  	WHERE tr_spk.id_dealer = '$id_dealer' AND no_order_survey='$id' ");	



		   $dt_order_survey = $this->db->query("SELECT *,tr_order_survey_gc.alamat as alamat_konsumen,ms_karyawan_dealer.nama_lengkap,tr_prospek.email as email_konsumen,tr_order_survey.no_ktp as ktp_konsumen, tr_order_survey.no_telp as telp_konsumen,tr_order_survey.pekerjaan,tr_order_survey.no_kk,tr_order_survey.created_at,tr_order_survey.tgl_lahir as tgl_lahir_k,tr_order_survey.tempat_lahir as tempat_lahir_k ,tr_order_survey.status_rumah as sts_rmh,tr_order_survey.email as email_k,tr_order_survey.voucher_tambahan_2 as vt_2,tr_order_survey.no_hp,tr_order_survey.id_finance_company



		  	FROM tr_order_survey

		  	LEFT JOIN tr_prospek ON tr_order_survey.id_customer = tr_prospek.id_customer

		  	LEFT JOIN tr_sales_order ON tr_order_survey.no_spk = tr_sales_order.no_spk



			LEFT JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin

			

			LEFT JOIN ms_tipe_kendaraan ON tr_order_survey.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

			LEFT JOIN ms_warna ON tr_order_survey.id_warna = ms_warna.id_warna

		  				left join ms_dealer on tr_order_survey.id_dealer = ms_dealer.id_dealer

		  				left join ms_karyawan_dealer on tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer

		  	WHERE tr_order_survey.id_dealer = '$id_dealer' AND no_order_survey='$id' ");	



		  if ($dt_order_survey->num_rows() > 0) {

		  	$dt_order = $dt_order_survey->row();



		  	$penghasilan_penjamin =$dt_order->penghasilan_penjamin;

			$penghasilan =$dt_order->penghasilan;

			$uang_muka =$dt_order->uang_muka;

			$harga_on_road =$dt_order->harga_on_road;

			$dp_stor =$dt_order->dp_stor;

			$angsuran =$dt_order->angsuran;

			$v_t = '';

			if ($dt_order->penghasilan_penjamin!=null OR $dt_order->penghasilan_penjamin!='') {

		  		$penghasilan_penjamin=$this->mata_uang($dt_order->penghasilan_penjamin);

		  	}if ($dt_order->penghasilan!=null OR $dt_order->penghasilan!='') {

		  		$penghasilan=$this->mata_uang($dt_order->penghasilan);



		  	}if ($dt_order->uang_muka!=null OR $dt_order->uang_muka!='') {

		  		$uang_muka=$this->mata_uang($dt_order->uang_muka);

		  	}if ($dt_order->harga_on_road!=null OR $dt_order->harga_on_road!='') {

		  		$harga_on_road=$this->mata_uang($dt_order->harga_on_road);

		  	}if ($dt_order->dp_stor!=null OR $dt_order->dp_stor!='') {

		  		$dp_stor=$this->mata_uang($dt_order->dp_stor);

		  	}if ($dt_order->angsuran!=null OR $dt_order->angsuran!='') {

		  		$angsuran=$this->mata_uang($dt_order->angsuran);

		  	}if ($dt_order->vt_2!=null OR $dt_order->vt_2!='') {

		  		$v_t=$this->mata_uang($dt_order->vt_2);

		  	}



		  	$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_order->id_finance_company'");

		  	$kerja				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_order->pekerjaan'");

		  	if($kerja->num_rows() > 0){

		  		$tr = $kerja->row();

		  		$pekerjaan = $tr->pekerjaan;

		  	}else{

		  		$pekerjaan = "-";

		  	}

		  	$kerja2				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_order->pekerjaan_penjamin'");

		  	if($kerja2->num_rows() > 0){

		  		$tr = $kerja2->row();

		  		$pekerjaan_penjamin = $tr->pekerjaan;

		  	}else{

		  		$pekerjaan_penjamin = "-";

		  	}

		  	$lokasi = explode(',', $dt_order->denah_lokasi);

			$latitude = str_replace(' ', '', $lokasi[0]);

			$longitude = str_replace(' ', '', $lokasi[1]);

			$qr_generate = "maps.google.com/local?q=$latitude,$longitude";

			$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77",164,25,33,0,'PNG');



		  	$pdf->Cell(35, 5, 'No. Survey', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $dt_order->no_order_survey, 0, 1, 'L');

		  	$tgl_survey = explode(' ', $dt_order->created_at);

		  	$tgl = date('d-m-Y', strtotime($tgl_survey[0]));

		  	$pdf->Cell(35, 5, 'Tanggal', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $tgl, 0, 1, 'L');

		  	$pdf->Cell(35, 5, 'Nama Dealer', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $dt_order->nama_dealer, 0, 1, 'L');

		  	$pdf->Cell(35, 5, 'Nama Sales Person', 0, 0, 'L');$pdf->Cell(3, 5, ':', 0, 0, 'C');$pdf->Cell(35, 5, $dt_order->nama_lengkap, 0, 1, 'L');

		  $pdf->SetFont('ARIAL','BU',10);

		  $pdf->Ln(8);

		  $pdf->Cell(185, 5, $qr_generate, 0, 1, 'R');

		  $pdf->Ln(9);

		  $pdf->Cell(95, 5, 'Pemohon', 0, 0, 'L');$pdf->Cell(95, 5, 'Penjamin', 0, 1, 'L');

		  $pdf->SetFont('ARIAL','',10);

		  $pdf->Cell(35, 5, 'Nama', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->nama_konsumen, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Nama', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5,  $dt_order->nama_penjamin, 0, 1, 'L');

		  

		  $x = $pdf->GetX();

			$y = $pdf->GetY();							

		  $pdf->Cell(35, 5, 'Alamat', 10, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->MultiCell(58, 5, $dt_order->alamat_konsumen, 0, 'L');		  

			$pdf->SetXY($x + 95, $y);

		  $pdf->Cell(35, 5, 'Alamat', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->MultiCell(58, 5, $dt_order->alamat_penjamin, 0, 'L');

		  $pdf->Cell(35, 5, 'Tempat/Tgl Lahir', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tempat_lahir_k.' / '.$this->format_tanggal($dt_order->tgl_lahir_k), 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Tempat/Tgl Lahir', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tempat_lahir_penjamin.' / '.$this->format_tanggal($dt_order->tgl_lahir_penjamin), 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. KTP', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->ktp_konsumen, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'No. KTP', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_ktp_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. HP #1', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_hp, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'No. HP #1', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_hp_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. HP #2', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_hp_2, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. Telepon Rumah', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->telp_konsumen, 0, 1, 'L');

		   $pdf->Cell(35, 5, 'Penghasilan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$penghasilan, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Penghasilan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$penghasilan_penjamin, 0, 1, 'L');

		    $pdf->Cell(35, 5, 'Pekerjaan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $pekerjaan, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Pekerjaan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $pekerjaan_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Stts Kepemilikan Rmh', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->sts_rmh, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Hub. dg Penjamin', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->hub_penjamin, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'No. Kartu Keluarga', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->no_kk, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Nama Ibu Kandung', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->nama_ibu, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'E-Mail', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->email_k, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Nama STNK/BPKB', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->nama_bpkb, 0, 1, 'L');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','B',11);

		  $pdf->Cell(190, 7, 'DATA KREDIT', 1, 1, 'C');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','',10);

		  $pdf->Cell(35, 5, 'Tipe Kendaraan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tipe_ahm, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Down Payment Gross', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$uang_muka, 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Warna Kendaraan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->warna, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Nilai Voucher', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$this->mata_uang($dt_order->voucher_2), 0, 1, 'L');

		  $pdf->Cell(35, 5, 'Harga On The Road', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5,'Rp. '.$harga_on_road, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Voucher Tambahan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$v_t, 0, 1, 'L');

		   $program_umum = '';

		  $program_g 	= '';

		  //	$program[0] = $row->program_umum==null?null:$row->program_umum;

		  	if ($dt_order->program_umum!=null OR $dt_order->program_umum!='') {

		  		$program_umum = $dt_order->program_umum;

		  	}if ($dt_order->program_gabungan!=null OR $dt_order->program_gabungan!='') {

		  		$program_g = "&	 $dt_order->program_gabungan";

		  	}

		  $pdf->Cell(35, 5, 'Program', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $program_umum.' '.$program_g, 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Down Payment Setor', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$dp_stor, 0, 1, 'L');

		  $pdf->Cell(35, 5, '', 0, 0, 'L');$pdf->Cell(2, 5, '', 0, 0, 'C');$pdf->Cell(58, 5, '', 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Tenor', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, $dt_order->tenor, 0, 1, 'L');

		  $pdf->Cell(35, 5, '', 0, 0, 'L');$pdf->Cell(2, 5, '', 0, 0, 'C');$pdf->Cell(58, 5, '', 0, 0, 'L');

		  $pdf->Cell(35, 5, 'Angsuran/Bulan', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0, 0, 'C');$pdf->Cell(58, 5, 'Rp. '.$angsuran, 0, 1, 'L');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','B',11);

		  $pdf->Cell(190, 7, 'HASIL SURVEY', 1, 1, 'C');

		  $pdf->Ln(3);

		  $pdf->SetFont('ARIAL','',10);



		  if ($finco->num_rows()>0) {

		  	$finco = $finco->row()->finance_company;

		  }else{

		  	$finco='';

		  }

		  $pdf->Cell(40,7, 'Finance Company', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, $finco, 0, 1, 'L');

		  $pdf->Cell(40,7, 'Verifier/CMO', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Tanggal Survey', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Jam Report', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Hasil Survey', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Cell(40,7, 'Keterangan Hasil Survey', 0, 0, 'L');$pdf->Cell(2,7, ':', 0, 0, 'C');$pdf->Cell(58,7, '___________________________________________________________________________', 0, 1, 'L');

		  $pdf->Ln(20);

		  $pdf->Cell(190,7, '(____________________)', 0, 1, 'C');

		  $pdf->Cell(190,7, 'CMO', 0, 1, 'C');



		  





		  //$pdf->Image(base_url('assets/panel/files/'.$dt_order->file_foto),60,30,30,0,'JPG');

		  if ($dt_order->file_foto!=null OR $dt_order->file_foto!='') {

		  	$pdf->AddPage();

		 	$pdf->centreImage(base_url('assets/panel/files/'.$dt_order->file_foto));

		  }

		  if ($dt_order->file_kk!=null OR $dt_order->file_kk!='') {

		  	$pdf->AddPage();

		 	$pdf->centreImage(base_url('assets/panel/files/'.$dt_order->file_kk));

		  }

		  if ($dt_order->file_ktp_2!=null OR $dt_order->file_ktp_2!='') {

		  	$pdf->AddPage();

		 	$pdf->centreImage(base_url('assets/panel/files/'.$dt_order->file_ktp_2));

		  }

		  }

	  	  



	  	  $pdf->Output(); 

	}

	public function cetak_gc(){

		$data['tanggal'] = $tgl 				= gmdate("d/m/Y", time()+60*60*7);

		$waktu 			= gmdate("y-m-d h:i:s", time()+60*60*7);

		$login_id		= $this->session->userdata('id_user');



		$mpdf = $this->mpdf_l->load();

		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE

    $mpdf->charset_in='UTF-8';

    $mpdf->autoLangToFont = true;

  	$data['id'] = $id 					= $this->input->get('id');			

  	$data['no_spk'] = $no_spk = $this->m_admin->getByID("tr_order_survey_gc","no_order_survey_gc",$id)->row()->no_spk_gc;  	

  	$sql = $this->db->query("SELECT tr_spk_gc.*, tr_prospek_gc.id_karyawan_dealer,

	  				ms_karyawan_dealer.nama_lengkap,ms_dealer.pic,tr_spk_gc.no_ktp,

	  				ms_dealer.kode_dealer_md,ms_dealer.nama_dealer FROM tr_spk_gc 

			LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc

			LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer			

			LEFT JOIN ms_dealer ON tr_spk_gc.id_dealer = ms_dealer.id_dealer

			WHERE tr_spk_gc.no_spk_gc = '$no_spk'");



  	$spk = $data['dt_os'] = $sql->row();  	  	

  	$data['cetak'] = 'cetak_gc';

  	

  	//$this->load->view('dealer/sales_order_cetak_gc', $data);    

  	$html = $this->load->view('dealer/os_cetak_gc', $data, true);    

    $mpdf->WriteHTML($html);    

    $output = 'cetak_.pdf';

    $mpdf->Output("$output", 'I');

	}
	public function download()
	{
		$data['isi']   = $this->page;		
		$data['title'] = $this->title;		
		$data['mode']  = 'detail';
		$data['set']   = "download";

		$no_spk = $this->input->get('id');
		$spk = $this->db->query("SELECT tr_spk.*,tipe_ahm,warna FROM tr_spk 
					JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
					JOIN ms_warna ON ms_warna.id_warna=tr_spk.id_warna
					WHERE no_spk='$no_spk'");
		if ($spk->num_rows()>0) {
			$data['row'] = $spk->row();
			$this->template($data);	
		}else{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/order_survey'>";
		}
	}
}