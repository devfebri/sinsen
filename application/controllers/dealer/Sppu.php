<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Sppu extends CI_Controller
{
	var $tables =   "tr_sppu";
	var $folder =   "dealer";
	var $page		=		"sppu";
	var $pk     =   "no_sppu";
	//var $title  =   "Surat Perintah Pengiriman Unit";
	var $title  =   "BASTK";
	function mata_uang($a)
	{
		if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		return number_format($a, 0, ',', '.');
	}
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
		$this->load->library('cfpdf');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$data['id_menu'] = $this->m_admin->getMenu($this->page);
			$data['group'] 	= $this->session->userdata("group");
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}
	/*
	public function index()
	{				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "view";
		$data['dt_sppu'] = $this->m_admin->getAll($this->tables);				
		$this->template($data);			
	}
*/
	public function monitor()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view_new";
		$id_dealer = $this->m_admin->cari_dealer();
/*
		$data['dt_sppu'] = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_spk.id_customer FROM tr_sales_order 
			LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_dealer = '$id_dealer' 
			AND LEFT(tr_sales_order.created_at,7)>'2019-11'
			AND tr_sales_order.id_master_plat is not null 
			ORDER BY tr_sales_order.id_sales_order ASC");
		$data['dt_sppu2'] = $this->db->query("SELECT tr_sales_order_gc.*,tr_spk_gc.nama_npwp,tr_spk_gc.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,
			tr_scan_barcode.no_rangka,tr_scan_barcode.no_mesin FROM tr_sales_order_gc_nosin
			LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
			LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc			
			INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			WHERE tr_sales_order_gc.id_dealer = '$id_dealer' 
			AND LEFT(tr_sales_order_gc.created_at,7)>'2019-11'
			AND tr_sales_order_gc.id_master_plat is not null 
			GROUP BY id_sales_order_gc
			ORDER BY tr_sales_order_gc.id_sales_order_gc ASC ");
*/
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "monitor";
		$id_dealer = $this->m_admin->cari_dealer();
		$this->template($data);
	}


	public function getAllData(){

        $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
	$limit = $_POST['length']; // Ambil data limit per page
	$start = $_POST['start']; // Ambil data start
	/*
	$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
	$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
	$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
	*/

        $id_menu = $this->m_admin->getMenu($this->page);
	$group 	= $this->session->userdata("group");
        $id_dealer = $this->m_admin->cari_dealer();

        $cari = '';
		if ($search != '') {
			$cari = " 
			and (a.nama_konsumen LIKE '%$search%' 
				OR a.alamat LIKE '%$search%' 
				OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%' 
				OR a.id_sales_order LIKE '%$search%' 
				OR ms_warna.warna LIKE '%$search%'
				OR tr_scan_barcode.no_rangka LIKE '%$search%'
				OR tr_scan_barcode.no_mesin LIKE '%$search%')
			";
		}

        $dataSo = $this->db->query("
			select a.*, tr_scan_barcode.no_rangka, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna from (
				select tr_sales_order.created_at, tr_sales_order.id_dealer , tr_sales_order.id_sales_order, tr_sales_order.delivery_document_id , tr_sales_order.no_mesin , tr_prospek.nama_konsumen,tr_spk.alamat, tr_sales_order.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order.tgl_cetak_invoice,'1' as tipe_so
				FROM tr_sales_order 
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek.id_karyawan_dealer 
				where LEFT(tr_sales_order.created_at,7)>'2019-11' and tr_sales_order.id_dealer = '$id_dealer'
				UNION 
				select tr_sales_order_gc.created_at, tr_sales_order_gc.id_dealer , tr_sales_order_gc_nosin.id_sales_order_gc  as id_sales_order, tr_sales_order_gc_nosin.delivery_document_id , tr_sales_order_gc_nosin.no_mesin ,  tr_spk_gc.nama_npwp as nama_konsumen,tr_spk_gc.alamat, tr_sales_order_gc_nosin.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order_gc.tgl_cetak_invoice,'2' as tipe_so
				FROM tr_sales_order_gc_nosin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc		
				LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc 
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek_gc.id_karyawan_dealer 
				where LEFT(tr_sales_order_gc.created_at,7)>'2019-11' and tr_sales_order_gc.id_dealer = '$id_dealer'
			)a 
			INNER JOIN tr_scan_barcode ON a.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			where a.tgl_cetak_invoice is not null and a.status_delivery is not null
			$cari	
			order by a.created_at desc
			LIMIT $start,$limit
        	");

        $data = array();

        foreach($dataSo->result() as $row)
        {
            $print    = $this->m_admin->set_tombol($id_menu, $group, 'print');	
	    if($row->tipe_so == '1'){
		$tombol = "<a href='dealer/sppu/cetak_sppu?id=$row->id_sales_order' target='_blank'>
                    	<button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak</button>
                  	</a>";
		$nomesin[0] = "<a data-toggle='tooltip' href='dealer/sppu/detail_no_mesin?id=$row->no_mesin'>$row->no_mesin</a>"; 
            	$norangka[0]=  $row->no_rangka; 
              	$tipe[0] = $row->tipe_ahm; 
              	$warna[0] = $row->warna; 
             }else if($row->tipe_so =='2'){
			$tombol = "<a href='dealer/sppu/cetak_sppu_gc?id=$row->id_sales_order' target='_blank'>
                    	<button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak</button>
                  	</a>";

		$dt_nomesin = $this->db->query("SELECT tr_scan_barcode.no_mesin,no_rangka 
              	FROM tr_sales_order_gc_nosin 
              	JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
              	WHERE id_sales_order_gc='$row->id_sales_order'
              	");

	            $dt_tipe = $this->db->query("SELECT tipe_ahm,ms_warna.warna FROM tr_sales_order_gc_nosin 
              JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin 
              JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
              JOIN ms_warna ON ms_warna.id_warna=tr_scan_barcode.warna
              WHERE id_sales_order_gc='$row->id_sales_order' GROUP BY tipe_motor,warna
              ");

            $tipe=array();
            $warna=array();
            foreach ($dt_tipe->result() as $tp) {
              $tipe[] = $tp->tipe_ahm; 
              $warna[] = $tp->warna; 
            }

            $nomesin=array();
            $norangka=array();
            foreach ($dt_nomesin->result() as $rs) {
              $nomesin[] = $rs->no_mesin; 
              $norangka[] = $rs->no_rangka; 
            }

	}
	      $status_delivery='';
	      if ($row->status_delivery=='in_progress') {
                $status_delivery = "<label class='label label-warning'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }
              if ($row->status_delivery=='delivered') {
                $status_delivery = "<label class='label label-success'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }
              if ($row->status_delivery=='ready') {
                $status_delivery = "<label class='label label-success'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }
              if ($row->status_delivery=='back_to_dealer') {
                $status_delivery = "<label class='label label-primary'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }


            $data[]= array(
            	'',
                $row->id_sales_order,
                implode(', </br>', $nomesin),
                implode(', </br>', $norangka),
                implode(', </br>', $tipe),
                implode(', </br>', $warna),
                $row->nama_konsumen,
                $row->alamat,
                $row->nama_lengkap,
		$status_delivery,
		$tombol
            );     
        }

        $get_total = $this->db->query("
			select a.*, tr_scan_barcode.no_rangka, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna from (
				select tr_sales_order.created_at, tr_sales_order.id_dealer , tr_sales_order.id_sales_order, tr_sales_order.delivery_document_id , tr_sales_order.no_mesin , tr_prospek.nama_konsumen,tr_spk.alamat, tr_sales_order.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order.tgl_cetak_invoice
				FROM tr_sales_order 
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek.id_karyawan_dealer 
				where LEFT(tr_sales_order.created_at,7)>'2019-11' and tr_sales_order.id_dealer = '$id_dealer'
				UNION 
				select tr_sales_order_gc.created_at, tr_sales_order_gc.id_dealer , tr_sales_order_gc_nosin.id_sales_order_gc , tr_sales_order_gc_nosin.delivery_document_id , tr_sales_order_gc_nosin.no_mesin ,  tr_spk_gc.nama_npwp,tr_spk_gc.alamat, tr_sales_order_gc_nosin.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order_gc.tgl_cetak_invoice
				FROM tr_sales_order_gc_nosin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc		
				LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc 
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek_gc.id_karyawan_dealer 
				where LEFT(tr_sales_order_gc.created_at,7)>'2019-11' and tr_sales_order_gc.id_dealer = '$id_dealer'
			)a 
			INNER JOIN tr_scan_barcode ON a.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			where a.tgl_cetak_invoice is not null and a.status_delivery is not null
			$cari        
	");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


	public function cetak_sppux()
	{
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$id 				= $this->input->get('id');
		$amb = $this->m_admin->getByID("tr_sales_order", "id_sales_order", $id)->row();
		$id_dealer = $this->m_admin->cari_dealer();
		$dt_sppu = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order 
			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_dealer = '$id_dealer'
			ORDER BY tr_sales_order.id_sales_order ASC");
		$pdf = new FPDF('p', 'mm', 'A4');
		$pdf->AddPage();
		// head	  
		$pdf->SetFont('TIMES', '', 10);
		$pdf->Cell(50, 5, 'Cetak Surat Perintah Pengiriman Unit', 0, 1, 'C');
		$pdf->Cell(50, 5, 'Jambi, ' . date('d/m/Y') . '', 0, 1, 'L');
		$pdf->Cell(50, 5, 'Kepada Yth,', 0, 1, 'L');
		$pdf->Cell(50, 5, 'PT. SINAR SENTOSA PRIMATAMA', 0, 1, 'L');
		$pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
		$pdf->Line(11, 31, 200, 31);
		$pdf->Output();
	}

	public function cetak_sppu()
	{
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$tabel     = $this->tables;
		$pk        = $this->pk;
		$id        = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$cek = $this->m_admin->getByID("tr_sales_order", "id_sales_order", $id);
		$ymd = date('Y-m-d');
		if ($cek->num_rows() > 0) {
			$so = $cek->row();
			// $so = $this->db->query("SELECT * FROM tr_sales_order JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk WHERE id_sales_order='$id'")->row();
			// if ($so->notif_sms_bastk_status==NULL) {
			// 	$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Reminder BASTK' AND id_dealer='$id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1 ");
			// 	if ($pesan_sms->num_rows()>0) {
			// 		$pesan  = $pesan_sms->row()->konten;
			// 		$id_get = ['IdSalesOrder'=>$so->id_sales_order,
			// 				   'NamaDealer'=>$id_dealer,
			// 				   'TanggalPengirimanUnit'=>$so->id_sales_order,
			// 				   'WaktuPengirimanUnit'=>$so->id_sales_order,
			// 				   'NamaCustomer'=>$so->no_spk,
			// 				   'TipeUnit'=>$so->id_tipe_kendaraan,
			// 				   'Warna'=>$so->id_warna];
			// 		$status = sms_zenziva($so->no_hp, pesan($pesan, $id_get));
			// 		$data['notif_sms_bastk_status'] = $status['status'];
			// 		$data['notif_sms_bastk_at']     = $waktu;
			// 		$data['notif_sms_bastk_by']     = $login_id;
			// 	}
			// }
			if ($so->status_delivery != 'delivered') {
				// $data['status_delivery']	='in_progress';	
			}
			$data['cetak_bastk_ke'] = $so->cetak_bastk_ke + 1;
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$this->m_admin->update("tr_sales_order", $data, "id_sales_order", $id);
			$so = $this->db->query("SELECT tr_sales_order.*,ms_dealer.nama_dealer,ms_dealer.alamat as alamat_dealer,ms_dealer.no_telp,ms_dealer.id_kelurahan as kelurahan_dealer, tr_scan_barcode.id_item, tr_spk.*,tr_spk.no_hp as no_hp_cons, ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm, ms_warna.warna,ms_finance_company.finance_company,tr_scan_barcode.no_rangka,ms_plat_dealer.driver,ms_plat_dealer.no_plat,ms_plat_dealer.no_hp as hp_driver,tr_prospek.nama_konsumen as nama_konsumena,tr_sales_order.no_mesin,tr_sales_order.tgl_pengiriman,id_flp_md,tr_sales_order.id_dealer FROM tr_sales_order 
					left join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
					left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk
					LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
					left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
					left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
					left join ms_dealer on tr_sales_order.id_dealer = ms_dealer.id_dealer
					left join ms_plat_dealer on tr_sales_order.id_master_plat = ms_plat_dealer.id_master_plat
					left join ms_finance_company on tr_spk.id_finance_company = ms_finance_company.id_finance_company
					WHERE id_sales_order = '$id' ")->row();
			$fkb = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi ='$so->no_mesin' ");
			if ($fkb->num_rows() > 0) {
				$tahun_produksi = $fkb->row()->tahun_produksi;
			} else {
				$tahun_produksi = '';
			}
			$ksu = $this->db->query("SELECT * from tr_sales_order_ksu 
		 						  JOIN ms_ksu on tr_sales_order_ksu.id_ksu = ms_ksu.id_ksu
		 						  WHERE id_sales_order='$id' ");
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->id_kelurahan'");
			$kelurahan 		= $dt_kel->num_rows() > 0 ? $dt_kel->row()->kelurahan : '';
			$id_kecamatan 		= $dt_kel->num_rows() > 0 ? $dt_kel->row()->id_kecamatan : '';
			$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$so->id_kecamatan'");
			$kecamatan 		= $dt_kec->num_rows() > 0 ? $dt_kec->row()->kecamatan : '';
			$id_kabupaten 		= $dt_kec->num_rows() > 0 ? $dt_kec->row()->id_kabupaten : '';
			$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$so->id_kabupaten'");
			$kabupaten 		= $dt_kab->num_rows() > 0 ? $dt_kab->row()->kabupaten : '';
			$id_provinsi 		= $dt_kab->num_rows() > 0 ? $dt_kab->row()->id_provinsi : '';
			$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$so->id_provinsi'")->row();
			$dt_kel_dealer				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->kelurahan_dealer'")->row();
			$kelurahan_dealer 		= $dt_kel_dealer->kelurahan;
			$id_kecamatan_dealer = $dt_kel_dealer->id_kecamatan;
			$dt_kec_dealer				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan_dealer'")->row();
			$kecamatan_dealer 		= $dt_kec_dealer->kecamatan;
			$id_kabupaten_dealer = $dt_kec_dealer->id_kabupaten;
			$dt_kab_dealer				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten_dealer'")->row();
			$kabupaten_dealer  	= $dt_kab_dealer->kabupaten;
			$pdf = new FPDF('P', 'mm', 'A4');
			$pdf->SetMargins(8, 8, 8);
			$pdf->AddPage();

			//Pengecekan Dealer Induk
			$cek_dl = $this->db->query("SELECT dli.* 
				FROM ms_dealer dl 
				JOIN ms_dealer dli ON dli.id_dealer=dl.id_dealer_induk
				WHERE dl.id_dealer='$so->id_dealer' AND dl.id_dealer_induk!=0
			");
			if ($cek_dl->num_rows() > 0) {
				$cek_dl = $cek_dl->row();
				// send_json($cek_dl);
				$so->nama_dealer = $cek_dl->nama_dealer;
				// $so->alamat_dealer = $cek_dl->alamat_dealer;
				$so->no_telp = $cek_dl->no_telp;
			}
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(190, 4, $so->nama_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->alamat_dealer, 0, 1, 'L');
			$pdf->Ln(4);
			$pdf->Cell(190, 4, $kabupaten_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->no_telp, 0, 1, 'L');
			$pdf->SetFont('ARIAL', 'B', 12);
			$pdf->setFillColor(10, 10, 10);
			$pdf->Cell(194, 7, 'BERITA ACARA SERAH TERIMA KENDARAAN', 1, 1, 'C');
			$pdf->Ln(4);
			$pdf->SetFont('ARIAL', '', 11);
			$tgl_bastk_exp = explode(' ', $so->tgl_bastk);
			$tgl_bastk = date('d-m-Y', strtotime($tgl_bastk_exp[0]));
			$pdf->Cell(30, 5, 'No BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_bastk, 0, 1, 'L');
			$pdf->Cell(30, 5, 'Tgl BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $tgl_bastk . ', ' . $tgl_bastk_exp[1], 0, 1, 'L');
			$pdf->Cell(30, 5, 'No SO', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->id_sales_order, 0, 1, 'L');
			$pdf->Cell(30, 5, 'No Invoice', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_invoice, 0, 1, 'L');
			$pdf->Cell(30, 5, 'ID SPK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_spk, 0, 1, 'L');

			if ($so->ambil == "Dikirim") {
				$driver = $so->driver;
				$no_plat = $so->no_plat;
			} else {
				$driver = "";
				$no_plat = "";
			}
			$pdf->SetFont('ARIAL', '', 10);
			
			if($this->config->item('google_apis')){				
				$latitude  = str_replace(',', '', $so->latitude);
				$longitude  = str_replace(',', '', $so->longitude);
				$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
				$url = str_replace(" ", '%20', "https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77"); // sedang error 502 googlenya
			}else{				
				$latitude = $this->config->item('latitude');
				$longitude = $this->config->item('longitude');
				$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
				$url = base_url('assets/panel/images/chart_qr_md.png');
			}

			$pdf->Cell(194, 5, $qr_generate, 0, 1, 'R');
			$pdf->Ln(3);
			$pdf->Image($url, 164, 36, 28, 0, 'PNG');

			$pdf->SetFont('ARIAL', 'BU', 11);
			$pdf->Cell(97, 5, "Pengirim :", 0, 0, 'L');
			$pdf->Cell(97, 5, "Kepada :", 0, 1, 'L');
			$pdf->SetFont('ARIAL', '', 11);
			$pdf->Cell(30, 5, "   Pengemudi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $driver, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Nama", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			// $pdf->Cell(60, 5, $so->nama_konsumen, 0, 1, 'L');
			$pdf->MultiCell(65, 5, $so->nama_konsumen, 0, 1);
			$pdf->Cell(30, 5, "   No Polisi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $no_plat, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Alamat", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->MultiCell(60, 5, $so->alamat, 0, 1);
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->hp_driver, 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			} else {
				$pdf->Cell(30, 5, "", 0, 0, 'L');
				$pdf->Cell(4, 5, "", 0, 0, 'L');
				$pdf->Cell(60, 5, "", 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			}
			
			$pdf->Ln(3);
			$pdf->Cell(194, 5, "Diterima dengan baik dan lengkap kendaraan beserta perlengkapannya sebagai berikut :", 0, 1, 'L');
			$pdf->Ln(3);
			$pdf->SetFont('ARIAL', 'B', '11');
			$pdf->Cell(194, 7, "DETAIL KENDARAAN", 1, 1, 'C');
			$pdf->Cell(82, 5, "Type", 1, 0, 'C');
			$pdf->Cell(40, 5, "Warna", 1, 0, 'C');
			$pdf->Cell(14, 5, "Tahun", 1, 0, 'C');
			$pdf->Cell(27, 5, "No Mesin", 1, 0, 'C');
			$pdf->Cell(31, 5, "No Rangka", 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', '9');
			$pdf->Cell(82, 5, strip_tags($so->deskripsi_ahm) ." - ". $so->tipe_ahm, 1, 0, 'C');
			$pdf->Cell(40, 5, $so->warna, 1, 0, 'C');
			$pdf->Cell(14, 5, $tahun_produksi, 1, 0, 'C');
			$pdf->Cell(27, 5, $so->no_mesin, 1, 0, 'C');
			$pdf->Cell(31, 5, $so->no_rangka, 1, 1, 'C');

			// ev BAST
			$oem = $this->db->query("SELECT * from tr_stock_battery where no_sales_order= '$so->id_sales_order' ");
			if ($oem->num_rows() > 0){

			$pdf->Ln(5);
			$pdf->SetFont('ARIAL', 'B', '11');
			$pdf->Cell(194, 7, "KELENGKAPAN EV", 1, 1, 'C');
			$pdf->Cell(30, 5, "Tipe", 1, 0, 'C');
			$pdf->Cell(45, 5, "Kode Part", 1, 0, 'C');
			$pdf->Cell(65, 5, "Nama Part", 1, 0, 'C');
			$pdf->Cell(54, 5, "Nomor Seri", 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', '8');

			foreach ($oem->result() as $ev) {
				$pdf->Cell(30, 5, 'B', 1, 0, 'C');
				$pdf->Cell(45, 5, $ev->part_id, 1, 0, 'C');
				$pdf->Cell(65, 5, $ev->part_desc, 1, 0, 'C');
				$pdf->Cell(54, 5, $ev->serial_number, 1, 1, 'C');
				}
			}

			$rem = $this->db->query("SELECT * from tr_h3_serial_ev_tracking where no_mesin= '$so->no_mesin' ");
			if ($rem->num_rows() > 0){
				foreach ($rem->result() as $rem) {
				$pdf->Cell(30, 5, $rem->type_accesories, 1, 0, 'C');
				$pdf->Cell(45, 5, $rem->id_part, 1, 0, 'C');
				$pdf->Cell(65, 5, $rem->nama_part, 1, 0, 'C');
				$pdf->Cell(54, 5, $rem->serial_number, 1, 1, 'C');
				}
			}

			$pdf->Ln(5);
			$pdf->SetFont('ARIAL', 'B', '11');
			$pdf->Cell(194, 7, "KELENGKAPAN STANDAR UNIT (KSU)", 1, 1, 'C');
			$pdf->Cell(87, 5, "Nama KSU", 1, 0, 'C');
			$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
			$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', '10');
			if ($ksu->num_rows() > 0) {
				foreach ($ksu->result() as $ks) {
					$pdf->Cell(87, 5, $ks->ksu, 1, 0, 'C');
					$pdf->Cell(30, 5, "1", 1, 0, 'C');
					$pdf->Cell(77, 5, "", 1, 1, 'C');
				}
			}
			if ($so->direct_gift != NULL) {
				$pdf->Ln(5);
				$pdf->SetFont('ARIAL', 'B', '11');
				$pdf->Cell(194, 7, "DIRECT GIFT", 1, 1, 'C');
				$pdf->Cell(87, 5, "Nama Direct Gift", 1, 0, 'C');
				$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
				$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', '10');
				$pdf->Cell(87, 5, $so->direct_gift, 1, 0, 'C');
				// $pdf->Cell(30,5,"1",1,0,'C');
				$qty = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_tipe_kendaraan='$so->id_tipe_kendaraan' AND id_warna LIKE '%$so->id_warna%'");
				if ($qty->num_rows() > 0) {
					$qty = $qty->row()->qty_minimum;
					if ($qty == 0) {
						$qty = 1;
					}
				} else {
					$qty = 1;
				}
				$pdf->Cell(30, 5, $qty, 1, 0, 'C');
				$pdf->Cell(77, 5, "", 1, 1, 'C');
			}
			/*$pdf->Ln(5);
		  $pdf->SetFont('ARIAL','B','11');
		  $pdf->Cell(194,7,"DIRECT GIFT",1,1,'C');
		  $pdf->Cell(87,5,"Nama Direct Gift",1,0,'C');
		  $pdf->Cell(30,5,"Qty",1,0,'C');
		  $pdf->Cell(77,5,"Keterangan",1,1,'C');
		  */
			$pdf->Ln(2);
			$pdf->SetFont('ARIAL', '', '11');
			$pdf->Cell(65, 5, "Diterima Oleh", 0, 0, 'L');
			$pdf->Cell(65, 5, "Diserahkan Oleh", 0, 0, 'L');
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(65, 5, "Dikeluarkan Oleh", 0, 0, 'L');
			} else {
				$pdf->Cell(65, 5, "Diketahui Oleh", 0, 0, 'L');
			}
			$pdf->Ln(18);
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " : " . $so->nama_penerima, 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');

			$pdf->Ln(4);

			$pdf->SetFont('ARIAL', '', '11');

			$pdf->Cell(50, 5, "Lokasi Pengiriman", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $so->lokasi_pengiriman, 0, 1, 'L');
			$tgl_pengiriman = date('d-m-Y', strtotime($so->tgl_pengiriman));
			$pdf->Cell(50, 5, "Tgl & Waktu Pengiriman", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $tgl_pengiriman . ', ' . $so->waktu_pengiriman, 0, 1, 'L');
			$sales = $this->db->get_where('ms_karyawan_dealer', ['id_flp_md' => $so->id_flp_md]);
			$nama_lengkap = ($sales->num_rows() > 0) ? $sales->row()->nama_lengkap : "";
			$pdf->Cell(50, 5, "Sales People ID", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $so->id_flp_md, 0, 1, 'L');
			$pdf->Cell(50, 5, "Sales People", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $nama_lengkap, 0, 1, 'L');
			$pdf->Output();
		} else {
			$dt_spk_gc = $this->db->get_where('tr_sales_order_gc', ['id_sales_order_gc' => $id])->row();
			// if ($dt_spk_gc->cetak_bastk_ke==0) {
			// 	$data['status_cetak']	='cetak_bastk';		
			// }
			$data['cetak_bastk_ke']	= $dt_spk_gc->cetak_bastk_ke + 1;
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);

			$so = $this->db->query("SELECT *,ms_dealer.id_kelurahan AS kelurahan_dealer,ms_dealer.alamat AS alamat_dealer,ms_plat_dealer.no_hp AS hp_driver,
		 			tr_spk_gc.no_hp AS no_hp_cons   
		 			FROM tr_sales_order_gc INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
		 			INNER JOIN ms_dealer ON tr_spk_gc.id_dealer = ms_dealer.id_dealer
		 			LEFT JOIN ms_plat_dealer ON tr_sales_order_gc.id_master_plat = ms_plat_dealer.id_master_plat
					WHERE tr_sales_order_gc.id_sales_order_gc = '$id' ")->row();
			//$fkb = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi ='$so->no_mesin' ");
			// if ($fkb->num_rows() > 0) {
			// 	$tahun_produksi = $fkb->row()->tahun_produksi;
			// }else{
			$tahun_produksi = '';
			//}
			$ksu = $this->db->query("SELECT * from tr_sales_order_ksu 
		 						  JOIN ms_ksu on tr_sales_order_ksu.id_ksu = ms_ksu.id_ksu
		 						  WHERE id_sales_order='$id' ");
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->id_kelurahan'")->row();
			$kelurahan 		= $dt_kel->kelurahan;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$so->id_kecamatan'")->row();
			$kecamatan 		= $dt_kec->kecamatan;
			$id_kabupaten = $dt_kec->id_kabupaten;
			$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$so->id_kabupaten'")->row();
			$kabupaten  	= $dt_kab->kabupaten;
			$id_provinsi  = $dt_kab->id_provinsi;
			$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$so->id_provinsi'")->row();
			$dt_kel_dealer				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->kelurahan_dealer'")->row();
			$kelurahan_dealer 		= $dt_kel_dealer->kelurahan;
			$id_kecamatan_dealer = $dt_kel_dealer->id_kecamatan;
			$dt_kec_dealer				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan_dealer'")->row();
			$kecamatan_dealer 		= $dt_kec_dealer->kecamatan;
			$id_kabupaten_dealer = $dt_kec_dealer->id_kabupaten;
			$dt_kab_dealer				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten_dealer'")->row();
			$kabupaten_dealer  	= $dt_kab_dealer->kabupaten;
			$pdf = new FPDF('P', 'mm', 'A4');
			$pdf->SetMargins(8, 8, 8);
			$pdf->AddPage();
			// head	  
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(190, 4, $so->nama_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->alamat_dealer, 0, 1, 'L');
			$pdf->Ln(4);
			$pdf->Cell(190, 4, $kabupaten_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->no_telp, 0, 1, 'L');
			$pdf->SetFont('ARIAL', 'B', 12);
			$pdf->setFillColor(10, 10, 10);
			$pdf->Cell(194, 7, 'BERITA ACARA SERAH TERIMA KENDARAAN', 1, 1, 'C');
			$pdf->Ln(4);
			$pdf->SetFont('ARIAL', '', 11);
			$tgl_bastk_exp = explode(' ', $so->tgl_bastk);
			$tgl_bastk = date('d-m-Y', strtotime($tgl_bastk_exp[0]));
			$pdf->Cell(30, 5, 'No BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_bastk, 0, 1, 'L');
			$pdf->Cell(30, 5, 'Tgl BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $tgl_bastk, 0, 1, 'L');
			$pdf->Cell(30, 5, 'No SO', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->id_sales_order_gc, 0, 1, 'L');
			$pdf->Cell(30, 5, 'No Invoice', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_invoice, 0, 1, 'L');
			$pdf->Ln(5);
			//$lokasi = explode(',', $so->denah_lokasi);
			//$latitude = str_replace(' ', '', $lokasi[0]);
			//$longitude = str_replace(' ', '', $lokasi[1]);
			//$qr_generate = "maps.google.com/local?q=$latitude,$longitude"; //data yang akan di jadikan QR CODE

			//$qr_generate = "maps.google.com/local?q=111133311,113331111"; //data yang akan di jadikan QR COD/E

			if ($so->ambil == "Dikirim") {
				$driver = $so->driver;
				$no_plat = $so->no_plat;
			} else {
				$driver = "";
				$no_plat = "";
			}
			$pdf->SetFont('ARIAL', '', 10);
			//$pdf->Cell(194, 5,$qr_generate, 0, 1, 'R');
			$pdf->Ln(3);
			//$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77",164,36,28,0,'PNG');
			$pdf->SetFont('ARIAL', 'BU', 11);
			$pdf->Cell(97, 5, "Pengirim :", 0, 0, 'L');
			$pdf->Cell(97, 5, "Kepada :", 0, 1, 'L');
			$pdf->SetFont('ARIAL', '', 11);
			$pdf->Cell(30, 5, "   Pengemudi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $driver, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Nama", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $so->nama_npwp, 0, 1, 'L');
			$pdf->Cell(30, 5, "   No Polisi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $no_plat, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Alamat", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->MultiCell(60, 5, $so->alamat, 0, 1);
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->hp_driver, 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			} else {
				$pdf->Cell(30, 5, "", 0, 0, 'L');
				$pdf->Cell(4, 5, "", 0, 0, 'L');
				$pdf->Cell(60, 5, "", 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			}
			$pdf->Ln(3);
			$pdf->Cell(194, 5, "Diterima dengan baik dan lengkap kendaraan beserta perlengkapannya sebagai berikut :", 0, 1, 'L');
			$pdf->Ln(3);
			$pdf->SetFont('ARIAL', 'B', '11');

			$pdf->Line(8, 62, 208, 62);
			$pdf->Cell(194, 7, "DETAIL KENDARAAN", 1, 1, 'C');
			$pdf->Cell(54, 4, 'Type', 1, 0, 'C');
			$pdf->Cell(40, 4, 'Warna', 1, 0, 'C');
			$pdf->Cell(20, 4, 'Tahun', 1, 0, 'C');
			$pdf->Cell(40, 4, 'No Mesin', 1, 0, 'C');
			$pdf->Cell(40, 4, 'No Rangka', 1, 1, 'C');
			$pdf->SetFont('Arial', '', 9);
			$get_nosin 	= $this->db->query("SELECT tr_sales_order_gc_nosin.*, tr_scan_barcode.no_rangka,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna 
		  	FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin 
		  	LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		  	LEFt JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
		  	WHERE tr_sales_order_gc_nosin.id_sales_order_gc = '$so->id_sales_order_gc'");
			foreach ($get_nosin->result() as $r) {
				$thn = $this->m_admin->getByID("tr_fkb", "no_mesin_spasi", $r->no_mesin);
				if ($thn->num_rows() > 0) {
					$ty = $thn->row();
					$tahun_p = $ty->tahun_produksi;
				} else {
					$tahun_p = "";
				}
				$pdf->Cell(54, 5, $r->tipe_ahm, 1, 0, "C");
				$pdf->Cell(40, 5, $r->warna, 1, 0, "C");
				$pdf->Cell(20, 5, $tahun_p, 1, 0, "C");
				$pdf->Cell(40, 5, $r->no_mesin, 1, 0, "C");
				$pdf->Cell(40, 5, $r->no_rangka, 1, 1, "C");
			}
			$pdf->Ln(5);
			$pdf->SetFont('ARIAL', 'B', '11');
			$pdf->Cell(194, 7, "KELENGKAPAN STANDAR UNIT (KSU)", 1, 1, 'C');
			$pdf->Cell(87, 5, "Nama KSU", 1, 0, 'C');
			$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
			$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', '10');
			if ($ksu->num_rows() > 0) {
				foreach ($ksu->result() as $ks) {
					$pdf->Cell(87, 5, $ks->ksu, 1, 0, 'C');
					$pdf->Cell(30, 5, "1", 1, 0, 'C');
					$pdf->Cell(77, 5, "", 1, 1, 'C');
				}
			}
			if ($so->direct_gift != NULL) {
				$pdf->Ln(5);
				$pdf->SetFont('ARIAL', 'B', '11');
				$pdf->Cell(194, 7, "DIRECT GIFT", 1, 1, 'C');
				$pdf->Cell(87, 5, "Nama Direct Gift", 1, 0, 'C');
				$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
				$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', '10');
				$pdf->Cell(87, 5, $so->direct_gift, 1, 0, 'C');
				// $pdf->Cell(30,5,"1",1,0,'C');
				$qty = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_tipe_kendaraan='$so->id_tipe_kendaraan' AND id_warna LIKE '%$so->id_warna%'");
				if ($qty->num_rows() > 0) {
					$qty = $qty->row()->qty_minimum;
				} else {
					$qty = 1;
				}
				$pdf->Cell(30, 5, $qty, 1, 0, 'C');
				$pdf->Cell(77, 5, "", 1, 1, 'C');
			}
			/*$pdf->Ln(5);
		  $pdf->SetFont('ARIAL','B','11');
		  $pdf->Cell(194,7,"DIRECT GIFT",1,1,'C');
		  $pdf->Cell(87,5,"Nama Direct Gift",1,0,'C');
		  $pdf->Cell(30,5,"Qty",1,0,'C');
		  $pdf->Cell(77,5,"Keterangan",1,1,'C');
		  */
			$pdf->Ln(0);
			$pdf->SetFont('ARIAL', '', '11');
			$pdf->Cell(65, 5, "Diterima Oleh", 0, 0, 'L');
			$pdf->Cell(65, 5, "Diserahkan Oleh", 0, 0, 'L');
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(65, 5, "Dikeluarkan Oleh", 0, 0, 'L');
			} else {
				$pdf->Cell(65, 5, "Diketahui Oleh", 0, 0, 'L');
			}
			$pdf->Ln(18);
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');
			$pdf->Output();
			// ob_clean();
		}
	}

	public function cetak_sppu_gc()
	{
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$tabel     = $this->tables;
		$pk        = $this->pk;
		$id        = $this->input->get('id');
		$id_dealer = $this->m_admin->cari_dealer();
		$cek = $this->m_admin->getByID("tr_sales_order_gc", "id_sales_order_gc", $id);
		$ymd = date('Y-m-d');
		if ($cek->num_rows() > 0) {
			$so = $cek->row();
			// $so = $this->db->query("SELECT * FROM tr_sales_order JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk WHERE id_sales_order='$id'")->row();
			// if ($so->notif_sms_bastk_status==NULL) {
			// 	$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Reminder BASTK' AND id_dealer='$id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1 ");
			// 	if ($pesan_sms->num_rows()>0) {
			// 		$pesan  = $pesan_sms->row()->konten;
			// 		$id_get = ['IdSalesOrder'=>$so->id_sales_order,
			// 				   'NamaDealer'=>$id_dealer,
			// 				   'TanggalPengirimanUnit'=>$so->id_sales_order,
			// 				   'WaktuPengirimanUnit'=>$so->id_sales_order,
			// 				   'NamaCustomer'=>$so->no_spk,
			// 				   'TipeUnit'=>$so->id_tipe_kendaraan,
			// 				   'Warna'=>$so->id_warna];
			// 		$status = sms_zenziva($so->no_hp, pesan($pesan, $id_get));
			// 		$data['notif_sms_bastk_status'] = $status['status'];
			// 		$data['notif_sms_bastk_at']     = $waktu;
			// 		$data['notif_sms_bastk_by']     = $login_id;
			// 	}
			// }
			// if ($so->status_delivery!='delivered') {
			// 	// $data['status_delivery']	='in_progress';	
			// }
			$data['cetak_bastk_ke'] = $so->cetak_bastk_ke + 1;
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
			$so = $this->db->query("SELECT tr_sales_order_gc.*,ms_dealer.nama_dealer,ms_dealer.alamat as alamat_dealer,ms_dealer.no_telp,ms_dealer.id_kelurahan as kelurahan_dealer, tr_scan_barcode.id_item, tr_spk_gc.*,tr_spk_gc.no_hp as no_hp_cons, ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.deskripsi_ahm, ms_warna.warna,ms_finance_company.finance_company,tr_scan_barcode.no_rangka,ms_plat_dealer.driver,ms_plat_dealer.no_plat,ms_plat_dealer.no_hp as hp_driver,tr_prospek_gc.nama_npwp as nama_konsumena,tr_sales_order_gc.tgl_pengiriman,id_flp_md,tr_sales_order_gc.nama_penerima 
		 		FROM tr_sales_order_gc 
		 		JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc_nosin.id_sales_order_gc=tr_sales_order_gc.id_sales_order_gc
					left join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_sales_order_gc_nosin.no_mesin
					left join tr_spk_gc on tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
					LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
					left join ms_tipe_kendaraan on tr_scan_barcode.tipe_motor=ms_tipe_kendaraan.id_tipe_kendaraan
					left join ms_warna on tr_scan_barcode.warna = ms_warna.id_warna
					left join ms_dealer on tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
					left join ms_plat_dealer on tr_sales_order_gc_nosin.id_master_plat = ms_plat_dealer.id_master_plat
					left join ms_finance_company on tr_spk_gc.id_finance_company = ms_finance_company.id_finance_company
					WHERE tr_sales_order_gc.id_sales_order_gc = '$id' ")->row();
			// $fkb = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi ='$so->no_mesin' ");
			// if ($fkb->num_rows() > 0) {
			// 	$tahun_produksi = $fkb->row()->tahun_produksi;
			// }else{
			// 	$tahun_produksi = '';
			// }
			// $ksu = $this->db->query("SELECT * from tr_sales_order_ksu 
			// 					  JOIN ms_ksu on tr_sales_order_ksu.id_ksu = ms_ksu.id_ksu
			// 					  WHERE id_sales_order='$id' ");
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->id_kelurahan'");
			$kelurahan 		= $dt_kel->num_rows() > 0 ? $dt_kel->row()->kelurahan : '';
			$id_kecamatan 		= $dt_kel->num_rows() > 0 ? $dt_kel->row()->id_kecamatan : '';
			$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$so->id_kecamatan'");
			$kecamatan 		= $dt_kec->num_rows() > 0 ? $dt_kec->row()->kecamatan : '';
			$id_kabupaten 		= $dt_kec->num_rows() > 0 ? $dt_kec->row()->id_kabupaten : '';
			$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$so->id_kabupaten'");
			$kabupaten 		= $dt_kab->num_rows() > 0 ? $dt_kab->row()->kabupaten : '';
			$id_provinsi 		= $dt_kab->num_rows() > 0 ? $dt_kab->row()->id_provinsi : '';
			$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$so->id_provinsi'")->row();
			$dt_kel_dealer				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->kelurahan_dealer'")->row();
			$kelurahan_dealer 		= $dt_kel_dealer->kelurahan;
			$id_kecamatan_dealer = $dt_kel_dealer->id_kecamatan;
			$dt_kec_dealer				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan_dealer'")->row();
			$kecamatan_dealer 		= $dt_kec_dealer->kecamatan;
			$id_kabupaten_dealer = $dt_kec_dealer->id_kabupaten;
			$dt_kab_dealer				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten_dealer'")->row();
			$kabupaten_dealer  	= $dt_kab_dealer->kabupaten;
			$pdf = new FPDF('P', 'mm', 'A4');
			$pdf->SetMargins(8, 8, 8);
			$pdf->AddPage();
			// head	  
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(190, 4, $so->nama_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->alamat_dealer, 0, 1, 'L');
			$pdf->Ln(4);
			$pdf->Cell(190, 4, $kabupaten_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->no_telp, 0, 1, 'L');
			$pdf->SetFont('ARIAL', 'B', 12);
			$pdf->setFillColor(10, 10, 10);
			$pdf->Cell(194, 7, 'BERITA ACARA SERAH TERIMA KENDARAAN', 1, 1, 'C');
			$pdf->Ln(4);
			$pdf->SetFont('ARIAL', '', 11);
			$tgl_bastk_exp = explode(' ', $so->tgl_bastk);
			$tgl_bastk = date('d-m-Y', strtotime($tgl_bastk_exp[0]));
			$pdf->Cell(30, 5, 'No BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_bastk, 0, 1, 'L');
			$pdf->Cell(30, 5, 'Tgl BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $tgl_bastk . ', ' . $tgl_bastk_exp[1], 0, 1, 'L');
			$pdf->Cell(30, 5, 'No SO', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->id_sales_order_gc, 0, 1, 'L');
			$pdf->Cell(30, 5, 'No Invoice', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_invoice, 0, 1, 'L');
			$pdf->Cell(30, 5, 'ID SPK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_spk_gc, 0, 1, 'L');
			// $pdf->Ln(5);
			$qr_generate = '';
			if ($so->denah_lokasi != null) {
				//  	$lokasi = explode(',', $so->denah_lokasi);
				// $latitude = str_replace(' ', '', $lokasi[0]);
				// $longitude = str_replace(' ', '', $lokasi[1]);
				$latitude = $so->latitude;
				$longitude = $so->longitude;
				$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
			}
			//data yang akan di jadikan QR CODE
			//$qr_generate = "maps.google.com/local?q=111133311,113331111"; //data yang akan di jadikan QR COD/E

			if ($so->ambil == "Dikirim") {
				$driver = $so->driver;
				$no_plat = $so->no_plat;
			} else {
				$driver = "";
				$no_plat = "";
			}
			$pdf->SetFont('ARIAL', '', 10);
			$pdf->Cell(194, 5, $qr_generate, 0, 1, 'R');
			$pdf->Ln(3);
			$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77", 164, 36, 28, 0, 'PNG');
			$pdf->SetFont('ARIAL', 'BU', 11);
			$pdf->Cell(97, 5, "Pengirim :", 0, 0, 'L');
			$pdf->Cell(97, 5, "Kepada :", 0, 1, 'L');
			$pdf->SetFont('ARIAL', '', 11);
			$pdf->Cell(30, 5, "   Pengemudi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $driver, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Nama", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			// $pdf->Cell(60, 5, $so->nama_konsumena, 0, 1, 'L');
			$pdf->MultiCell(65, 5, $so->nama_konsumena, 0, 1);
			$pdf->Cell(30, 5, "   No Polisi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $no_plat, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Alamat", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->MultiCell(60, 5, $so->alamat, 0, 1);
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->hp_driver, 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			} else {
				$pdf->Cell(30, 5, "", 0, 0, 'L');
				$pdf->Cell(4, 5, "", 0, 0, 'L');
				$pdf->Cell(60, 5, "", 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			}
			$pdf->Ln(3);
			$pdf->Cell(194, 5, "Diterima dengan baik dan lengkap kendaraan beserta perlengkapannya sebagai berikut :", 0, 1, 'L');
			$pdf->Ln(3);
			$pdf->SetFont('ARIAL', 'B', '11');
			$pdf->Cell(194, 7, "DETAIL KENDARAAN", 1, 1, 'C');
			$pdf->Cell(65, 5, "Type", 1, 0, 'C');
			$pdf->Cell(57, 5, "Warna", 1, 0, 'C');
			$pdf->Cell(14, 5, "Tahun", 1, 0, 'C');
			$pdf->Cell(27, 5, "No Mesin", 1, 0, 'C');
			$pdf->Cell(31, 5, "No Rangka", 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', '9');
			$get_nosin = $this->db->query("SELECT tr_sales_order_gc_nosin.no_mesin,ms_warna.warna,ms_tipe_kendaraan.deskripsi_ahm,tahun_produksi,tr_scan_barcode.no_rangka FROM tr_sales_order_gc_nosin 
		  	JOIN tr_fkb ON tr_fkb.no_mesin_spasi=tr_sales_order_gc_nosin.no_mesin
		  	JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
		  	JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
		  	JOIN ms_warna ON ms_warna.id_warna=tr_scan_barcode.warna
		  	WHERE id_sales_order_gc='$so->id_sales_order_gc' ORDER BY id_item ASC ");
			$qty_item = array();
			$temp =array();
			foreach ($get_nosin->result() as $rs) {
				$pdf->Cell(65, 5, strip_tags($rs->deskripsi_ahm), 1, 0, 'C');
				$pdf->Cell(57, 5, $rs->warna, 1, 0, 'C');
				$pdf->Cell(14, 5, $rs->tahun_produksi, 1, 0, 'C');
				$pdf->Cell(27, 5, $rs->no_mesin, 1, 0, 'C');
				$pdf->Cell(31, 5, $rs->no_rangka, 1, 1, 'C');

				$temp[]=$rs->no_mesin;
			}
			$item_gc = $this->db->query("SELECT tipe_motor, warna, id_item, COUNT(id_item) AS count 
		  	FROM tr_sales_order_gc_nosin
			JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
			WHERE id_sales_order_gc='$so->id_sales_order_gc'
			GROUP BY tipe_motor,warna ORDER BY id_item ASC
			");


				$oem = $this->db->query("SELECT * from tr_stock_battery where no_sales_order= '$id' ");
				if ($oem->num_rows() > 0){

				$pdf->Ln(5);
				$pdf->SetFont('ARIAL', 'B', '11');
				$pdf->Cell(194, 7, "KELENGKAPAN EV", 1, 1, 'C');
				$pdf->Cell(30, 5, "Tipe", 1, 0, 'C');
				$pdf->Cell(45, 5, "Kode Part", 1, 0, 'C');
				$pdf->Cell(65, 5, "Nama Part", 1, 0, 'C');
				$pdf->Cell(54, 5, "Nomor Seri", 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', '8');

				foreach ($oem->result() as $ev) {
					$pdf->Cell(30, 5, 'B', 1, 0, 'C');
					$pdf->Cell(45, 5, $ev->part_id, 1, 0, 'C');
					$pdf->Cell(65, 5, $ev->part_desc, 1, 0, 'C');
					$pdf->Cell(54, 5, $ev->serial_number, 1, 1, 'C');
					}
				}

					$this->db->select('*');
					$this->db->from('tr_h3_serial_ev_tracking');
					$this->db->where_in('no_mesin', $temp);
					$rem = $this->db->get();
					
				if ($rem->num_rows() > 0){
					foreach ($rem->result() as $rem) {
					$pdf->Cell(30, 5, $rem->type_accesories, 1, 0, 'C');
					$pdf->Cell(45, 5, $rem->id_part, 1, 0, 'C');
					$pdf->Cell(65, 5, $rem->nama_part, 1, 0, 'C');
					$pdf->Cell(54, 5, $rem->serial_number, 1, 1, 'C');
					}
				}


			foreach ($item_gc->result() as $itm) {
				$pdf->Ln(5);
				$pdf->SetFont('ARIAL', 'B', '11');
				$pdf->Cell(194, 7, "KELENGKAPAN STANDAR UNIT (KSU)", 1, 1, 'C');
				$pdf->Cell(87, 5, "Nama KSU", 1, 0, 'C');
				$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
				$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', '10');
				$ksu = $this->db->query("
			  	SELECT * FROM ms_koneksi_ksu
					JOIN ms_koneksi_ksu_detail ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu
					JOIN ms_ksu ON ms_koneksi_ksu_detail.id_ksu = ms_ksu.id_ksu
					WHERE id_tipe_kendaraan='$itm->tipe_motor'
			  	");
				if ($ksu->num_rows() > 0) {
					foreach ($ksu->result() as $ks) {
						$pdf->Cell(87, 5, $ks->ksu, 1, 0, 'C');
						$pdf->Cell(30, 5, $itm->count, 1, 0, 'C');
						$pdf->Cell(77, 5, "", 1, 1, 'C');
					}
				}
				if ($so->direct_gift != NULL) {
					$pdf->Ln(5);
					$pdf->SetFont('ARIAL', 'B', '11');
					$pdf->Cell(194, 7, "DIRECT GIFT", 1, 1, 'C');
					$pdf->Cell(87, 5, "Nama Direct Gift", 1, 0, 'C');
					$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
					$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
					$pdf->SetFont('ARIAL', '', '10');
					$pdf->Cell(87, 5, $so->direct_gift, 1, 0, 'C');
					// $pdf->Cell(30,5,"1",1,0,'C');
					$qty = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_tipe_kendaraan='$so->id_tipe_kendaraan' AND id_warna LIKE '%$so->id_warna%'");
					if ($qty->num_rows() > 0) {
						$qty = $qty->row()->qty_minimum;
						if ($qty == 0) {
							$qty = 1;
						}
					} else {
						$qty = 1;
					}
					$pdf->Cell(30, 5, $qty, 1, 0, 'C');
					$pdf->Cell(77, 5, "", 1, 1, 'C');
				}
			}

			/*$pdf->Ln(5);
		  $pdf->SetFont('ARIAL','B','11');
		  $pdf->Cell(194,7,"DIRECT GIFT",1,1,'C');
		  $pdf->Cell(87,5,"Nama Direct Gift",1,0,'C');
		  $pdf->Cell(30,5,"Qty",1,0,'C');
		  $pdf->Cell(77,5,"Keterangan",1,1,'C');
		  */
			$pdf->Ln(5);
			$pdf->SetFont('ARIAL', '', '11');
			$pdf->Cell(65, 5, "Diterima Oleh", 0, 0, 'L');
			$pdf->Cell(65, 5, "Diserahkan Oleh", 0, 0, 'L');
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(65, 5, "Dikeluarkan Oleh", 0, 0, 'L');
			} else {
				$pdf->Cell(65, 5, "Diketahui Oleh", 0, 0, 'L');
			}
			$pdf->Ln(18);
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " : " . $so->nama_penerima, 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');

			$pdf->Ln(15);
			$pdf->Cell(50, 5, "Lokasi Pengiriman", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $so->lokasi_pengiriman, 0, 1, 'L');
			$tgl_pengiriman = date('d-m-Y', strtotime($so->tgl_pengiriman));
			$pdf->Cell(50, 5, "Tgl & Waktu Pengiriman", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $tgl_pengiriman . ', ' . $so->waktu_pengiriman, 0, 1, 'L');
			$sales = $this->db->get_where('ms_karyawan_dealer', ['id_flp_md' => $so->id_flp_md])->row();
			$pdf->Cell(50, 5, "Sales People ID", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $so->id_flp_md, 0, 1, 'L');
			$pdf->Cell(50, 5, "Sales People", 0, 0, 'L');
			$pdf->Cell(80, 5, ': ' . $sales->nama_lengkap, 0, 1, 'L');
			$pdf->Output();
		} else {
			$dt_spk_gc = $this->db->get_where('tr_sales_order_gc', ['id_sales_order_gc' => $id])->row();
			// if ($dt_spk_gc->cetak_bastk_ke==0) {
			// 	$data['status_cetak']	='cetak_bastk';		
			// }
			$data['cetak_bastk_ke']	= $dt_spk_gc->cetak_bastk_ke + 1;
			$data['updated_at']		= $waktu;
			$data['updated_by']		= $login_id;
			$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);

			$so = $this->db->query("SELECT *,ms_dealer.id_kelurahan AS kelurahan_dealer,ms_dealer.alamat AS alamat_dealer,ms_plat_dealer.no_hp AS hp_driver,
		 			tr_spk_gc.no_hp AS no_hp_cons   
		 			FROM tr_sales_order_gc INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
		 			INNER JOIN ms_dealer ON tr_spk_gc.id_dealer = ms_dealer.id_dealer
		 			LEFT JOIN ms_plat_dealer ON tr_sales_order_gc.id_master_plat = ms_plat_dealer.id_master_plat
					WHERE tr_sales_order_gc.id_sales_order_gc = '$id' ")->row();
			//$fkb = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi ='$so->no_mesin' ");
			// if ($fkb->num_rows() > 0) {
			// 	$tahun_produksi = $fkb->row()->tahun_produksi;
			// }else{
			$tahun_produksi = '';
			//}
			$ksu = $this->db->query("SELECT * from tr_sales_order_ksu 
		 						  JOIN ms_ksu on tr_sales_order_ksu.id_ksu = ms_ksu.id_ksu
		 						  WHERE id_sales_order='$id' ");
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->id_kelurahan'")->row();
			$kelurahan 		= $dt_kel->kelurahan;
			$id_kecamatan = $dt_kel->id_kecamatan;
			$dt_kec				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$so->id_kecamatan'")->row();
			$kecamatan 		= $dt_kec->kecamatan;
			$id_kabupaten = $dt_kec->id_kabupaten;
			$dt_kab				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$so->id_kabupaten'")->row();
			$kabupaten  	= $dt_kab->kabupaten;
			$id_provinsi  = $dt_kab->id_provinsi;
			$dt_pro				= $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$so->id_provinsi'")->row();
			$dt_kel_dealer				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->kelurahan_dealer'")->row();
			$kelurahan_dealer 		= $dt_kel_dealer->kelurahan;
			$id_kecamatan_dealer = $dt_kel_dealer->id_kecamatan;
			$dt_kec_dealer				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan_dealer'")->row();
			$kecamatan_dealer 		= $dt_kec_dealer->kecamatan;
			$id_kabupaten_dealer = $dt_kec_dealer->id_kabupaten;
			$dt_kab_dealer				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten_dealer'")->row();
			$kabupaten_dealer  	= $dt_kab_dealer->kabupaten;
			$pdf = new FPDF('P', 'mm', 'A4');
			$pdf->SetMargins(8, 8, 8);
			$pdf->AddPage();
			// head	  
			$pdf->SetFont('ARIAL', '', 9);
			$pdf->Cell(190, 4, $so->nama_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->alamat_dealer, 0, 1, 'L');
			$pdf->Ln(4);
			$pdf->Cell(190, 4, $kabupaten_dealer, 0, 1, 'L');
			$pdf->Cell(190, 4, $so->no_telp, 0, 1, 'L');
			$pdf->SetFont('ARIAL', 'B', 12);
			$pdf->setFillColor(10, 10, 10);
			$pdf->Cell(194, 7, 'BERITA ACARA SERAH TERIMA KENDARAAN', 1, 1, 'C');
			$pdf->Ln(4);
			$pdf->SetFont('ARIAL', '', 11);
			$tgl_bastk_exp = explode(' ', $so->tgl_bastk);
			$tgl_bastk = date('d-m-Y', strtotime($tgl_bastk_exp[0]));
			$pdf->Cell(30, 5, 'No BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_bastk, 0, 1, 'L');
			$pdf->Cell(30, 5, 'Tgl BASTK', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $tgl_bastk, 0, 1, 'L');
			$pdf->Cell(30, 5, 'No SO', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->id_sales_order_gc, 0, 1, 'L');
			$pdf->Cell(30, 5, 'No Invoice', 0, 0, 'L');
			$pdf->Cell(7, 5, ' : ', 0, 0, 'L');
			$pdf->Cell(60, 5, $so->no_invoice, 0, 1, 'L');
			$pdf->Ln(5);
			//$lokasi = explode(',', $so->denah_lokasi);
			//$latitude = str_replace(' ', '', $lokasi[0]);
			//$longitude = str_replace(' ', '', $lokasi[1]);
			//$qr_generate = "maps.google.com/local?q=$latitude,$longitude"; //data yang akan di jadikan QR CODE

			//$qr_generate = "maps.google.com/local?q=111133311,113331111"; //data yang akan di jadikan QR COD/E

			if ($so->ambil == "Dikirim") {
				$driver = $so->driver;
				$no_plat = $so->no_plat;
			} else {
				$driver = "";
				$no_plat = "";
			}
			$pdf->SetFont('ARIAL', '', 10);
			//$pdf->Cell(194, 5,$qr_generate, 0, 1, 'R');
			$pdf->Ln(3);
			//$pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77",164,36,28,0,'PNG');
			$pdf->SetFont('ARIAL', 'BU', 11);
			$pdf->Cell(97, 5, "Pengirim :", 0, 0, 'L');
			$pdf->Cell(97, 5, "Kepada :", 0, 1, 'L');
			$pdf->SetFont('ARIAL', '', 11);
			$pdf->Cell(30, 5, "   Pengemudi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $driver, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Nama", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $so->nama_npwp, 0, 1, 'L');
			$pdf->Cell(30, 5, "   No Polisi", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->Cell(60, 5, $no_plat, 0, 0, 'L');
			$pdf->Cell(30, 5, "   Alamat", 0, 0, 'L');
			$pdf->Cell(4, 5, " : ", 0, 0, 'L');
			$pdf->MultiCell(60, 5, $so->alamat, 0, 1);
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->hp_driver, 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			} else {
				$pdf->Cell(30, 5, "", 0, 0, 'L');
				$pdf->Cell(4, 5, "", 0, 0, 'L');
				$pdf->Cell(60, 5, "", 0, 0, 'L');
				$pdf->Cell(30, 5, "   No HP", 0, 0, 'L');
				$pdf->Cell(4, 5, " : ", 0, 0, 'L');
				$pdf->Cell(60, 5, $so->no_hp_cons, 0, 1, 'L');
			}
			$pdf->Ln(3);
			$pdf->Cell(194, 5, "Diterima dengan baik dan lengkap kendaraan beserta perlengkapannya sebagai berikut :", 0, 1, 'L');
			$pdf->Ln(3);
			$pdf->SetFont('ARIAL', 'B', '11');

			$pdf->Line(8, 62, 208, 62);
			$pdf->Cell(194, 7, "DETAIL KENDARAAN", 1, 1, 'C');
			$pdf->Cell(54, 4, 'Type', 1, 0, 'C');
			$pdf->Cell(40, 4, 'Warna', 1, 0, 'C');
			$pdf->Cell(20, 4, 'Tahun', 1, 0, 'C');
			$pdf->Cell(40, 4, 'No Mesin', 1, 0, 'C');
			$pdf->Cell(40, 4, 'No Rangka', 1, 1, 'C');
			$pdf->SetFont('Arial', '', 9);
			$get_nosin 	= $this->db->query("SELECT tr_sales_order_gc_nosin.*, tr_scan_barcode.no_rangka,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna 
		  	FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin 
		  	LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		  	LEFt JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
		  	WHERE tr_sales_order_gc_nosin.id_sales_order_gc = '$so->id_sales_order_gc'");
			foreach ($get_nosin->result() as $r) {
				$thn = $this->m_admin->getByID("tr_fkb", "no_mesin_spasi", $r->no_mesin);
				if ($thn->num_rows() > 0) {
					$ty = $thn->row();
					$tahun_p = $ty->tahun_produksi;
				} else {
					$tahun_p = "";
				}
				$pdf->Cell(54, 5, $r->tipe_ahm, 1, 0, "C");
				$pdf->Cell(40, 5, $r->warna, 1, 0, "C");
				$pdf->Cell(20, 5, $tahun_p, 1, 0, "C");
				$pdf->Cell(40, 5, $r->no_mesin, 1, 0, "C");
				$pdf->Cell(40, 5, $r->no_rangka, 1, 1, "C");
			}
			$pdf->Ln(5);
			$pdf->SetFont('ARIAL', 'B', '11');
			$pdf->Cell(194, 7, "KELENGKAPAN STANDAR UNIT (KSU)", 1, 1, 'C');
			$pdf->Cell(87, 5, "Nama KSU", 1, 0, 'C');
			$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
			$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
			$pdf->SetFont('ARIAL', '', '10');
			if ($ksu->num_rows() > 0) {
				foreach ($ksu->result() as $ks) {
					$pdf->Cell(87, 5, $ks->ksu, 1, 0, 'C');
					$pdf->Cell(30, 5, "1", 1, 0, 'C');
					$pdf->Cell(77, 5, "", 1, 1, 'C');
				}
			}
			if ($so->direct_gift != NULL) {
				$pdf->Ln(5);
				$pdf->SetFont('ARIAL', 'B', '11');
				$pdf->Cell(194, 7, "DIRECT GIFT", 1, 1, 'C');
				$pdf->Cell(87, 5, "Nama Direct Gift", 1, 0, 'C');
				$pdf->Cell(30, 5, "Qty", 1, 0, 'C');
				$pdf->Cell(77, 5, "Keterangan", 1, 1, 'C');
				$pdf->SetFont('ARIAL', '', '10');
				$pdf->Cell(87, 5, $so->direct_gift, 1, 0, 'C');
				// $pdf->Cell(30,5,"1",1,0,'C');
				$qty = $this->db->query("SELECT * FROM tr_sales_program_tipe WHERE id_tipe_kendaraan='$so->id_tipe_kendaraan' AND id_warna LIKE '%$so->id_warna%'");
				if ($qty->num_rows() > 0) {
					$qty = $qty->row()->qty_minimum;
				} else {
					$qty = 1;
				}
				$pdf->Cell(30, 5, $qty, 1, 0, 'C');
				$pdf->Cell(77, 5, "", 1, 1, 'C');
			}
			/*$pdf->Ln(5);
		  $pdf->SetFont('ARIAL','B','11');
		  $pdf->Cell(194,7,"DIRECT GIFT",1,1,'C');
		  $pdf->Cell(87,5,"Nama Direct Gift",1,0,'C');
		  $pdf->Cell(30,5,"Qty",1,0,'C');
		  $pdf->Cell(77,5,"Keterangan",1,1,'C');
		  */
			$pdf->Ln(5);
			$pdf->SetFont('ARIAL', '', '11');
			$pdf->Cell(65, 5, "Diterima Oleh", 0, 0, 'L');
			$pdf->Cell(65, 5, "Diserahkan Oleh", 0, 0, 'L');
			if ($so->ambil == 'Dikirim') {
				$pdf->Cell(65, 5, "Dikeluarkan Oleh", 0, 0, 'L');
			} else {
				$pdf->Cell(65, 5, "Diketahui Oleh", 0, 0, 'L');
			}
			$pdf->Ln(18);
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Nama", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 0, 'L');
			$pdf->Cell(10, 5, "Tgl", 0, 0, 'L');
			$pdf->Cell(55, 5, " :______________________", 0, 1, 'L');
			$pdf->Output();
		}
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_karyawan'] 	= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND active = '1' ORDER BY  nama_lengkap ASC");
		$data['dt_no_mesin'] = $this->m_admin->getSort("tr_scan_barcode", "no_mesin", "ASC");
		$this->template($data);
	}
	public function t_sppu()
	{
		$id = $this->input->post('no_sppu');
		$data['dt_sppu'] = $this->db->query("SELECT * FROM tr_sppu_detail WHERE no_sppu = '$id'");
		$this->load->view('dealer/t_sppu', $data);
	}
	public function cari_id()
	{
		$rt = $this->m_admin->cari_id("tr_sppu", "no_sppu");
		echo $rt;
	}
	public function cek_nosin()
	{
		$no_mesin = $this->input->post('no_mesin');
		$sql = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_sales_order 
			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna			
			WHERE tr_scan_barcode.no_mesin = '$no_mesin'");
		if ($sql->num_rows() > 0) {
			$dt_ve = $sql->row();
			$no_mesin 	= $dt_ve->no_mesin;
			$no_rangka 	= $dt_ve->no_rangka;
			$tipe_ahm 	= $dt_ve->tipe_ahm;
			$warna 			= $dt_ve->warna;
			$id_item 		= $dt_ve->id_item;
			$nama_konsumen 		= $dt_ve->nama_konsumen;
			$alamat 		= $dt_ve->alamat;
			echo "ok" . "|" . $no_mesin . "|" . $no_rangka . "|" . $id_item . "|" . $tipe_ahm . "|" . $warna . "|" . $nama_konsumen . "|" . $alamat;
		} else {
			echo "There is no data found!";
		}
	}
	public function save_sppu()
	{
		$no_sppu			= $this->input->post('no_sppu');
		$no_mesin			= $this->input->post('no_mesin');
		$data['no_sppu']			= $this->input->post('no_sppu');
		$data['no_mesin']			= $this->input->post('no_mesin');
		$c = $this->db->query("SELECT * FROM tr_sppu_detail WHERE no_sppu = '$no_sppu' AND no_mesin = '$no_mesin'");
		if ($c->num_rows() > 0) {
			echo "no";
		} else {
			$cek2 = $this->m_admin->insert("tr_sppu_detail", $data);
			echo "nihil";
		}
	}
	public function delete_sppu()
	{
		$id = $this->input->post('id_sppu_detail');
		$this->db->query("DELETE FROM tr_sppu_detail WHERE id_sppu_detail = '$id'");
		echo "nihil";
	}
	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk					= $this->pk;
		$id  				= $this->input->post($pk);
		$cek 				= $this->m_admin->getByID($tabel, $pk, $id)->num_rows();
		if ($cek == 0) {
			$data['no_sppu'] 					= $this->input->post('no_sppu');
			$data['tgl_sppu'] 				= $this->input->post('tgl_sppu');
			$data['warehouse_head'] 	= $this->input->post('warehouse_head');
			$data['delivery_man'] 		= $this->input->post('delivery_man');
			$data['security'] 				= $this->input->post('security');
			$data['status_sppu'] 			= "input";
			$data['created_at']				= $waktu;
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel, $data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sppu/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	public function konfirmasi()
	{
		$id = $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= "Konsirmasi " . $this->title;
		$data['set']		= "konfirmasi";
		$data['dt_sppu'] = $this->m_admin->getByID("tr_sppu", "no_sppu", $id);
		$this->template($data);
	}
	public function save_konfirmasi()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_sppu		= $this->input->post("no_sppu");
		$no_mesin		= $this->input->post("no_mesin");
		foreach ($no_mesin as $key => $val) {
			$no_mesin 	= $_POST['no_mesin'][$key];
			$tgl_terima = $_POST['tgl_terima'][$key];
			if (isset($_POST['check_sppu'][$key])) {
				$data["konfirmasi"] = "ya";
				$data["tgl_terima"] = $tgl_terima;
				$this->m_admin->update("tr_sppu_detail", $data, "no_mesin", $no_mesin);
			} else {
				$data["konfirmasi"] = "tidak";
				$this->m_admin->update("tr_sppu_detail", $data, "no_mesin", $no_mesin);
			}
		}

		$_SESSION['pesan'] 	= "Data has been saved successfully";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sppu'>";
	}
	public function detail()
	{
		$id = $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "detail";
		$data['dt_sppu'] = $this->m_admin->getByID("tr_sppu", "no_sppu", $id);
		$this->template($data);
	}
	public function detail_no_mesin()
	{
		$id = $this->input->get("id");
		$id_dealer = $this->m_admin->cari_dealer();
		$so = $this->db->query("SELECT tr_sales_order.*,nama_konsumen,id_tipe_kendaraan,id_warna,alamat,tr_sales_order.latitude,tr_sales_order.longitude,no_hp,id_customer FROM tr_sales_order 
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE tr_sales_order.no_mesin='$id'");
		if ($so->num_rows() > 0) {
			$so            = $so->row();
			$data['isi']   = $this->page;
			$data['title'] = "Detail No. Mesin";
			$data['set']   = "detail_no_mesin";
			$data['row']    = $so;
			$this->template($data);
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sppu'>";
		}
	}


	public function getAllDataMonitor(){

    $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
	$limit = $_POST['length']; // Ambil data limit per page
	$start = $_POST['start']; // Ambil data start
	/*
	$order_index = $_POST['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
	$order_field = $_POST['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
	$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
	*/

        $id_menu = $this->m_admin->getMenu($this->page);
	$group 	= $this->session->userdata("group");
        $id_dealer = $this->m_admin->cari_dealer();

        $cari = '';
		if ($search != '') {
			$cari = " 
			and (a.nama_konsumen LIKE '%$search%' 
				OR a.alamat LIKE '%$search%' 
				OR ms_tipe_kendaraan.tipe_ahm LIKE '%$search%' 
				OR a.id_sales_order LIKE '%$search%' 
				OR ms_warna.warna LIKE '%$search%'
				OR tr_scan_barcode.no_rangka LIKE '%$search%'
				OR tr_scan_barcode.no_mesin LIKE '%$search%')
			";
		}

        $dataSo = $this->db->query("
			select a.*, tr_scan_barcode.no_rangka, ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,
			(	
				select b.tgl_pengiriman 
				from tr_generate_list_unit_delivery_detail x 
				join tr_generate_list_unit_delivery b on x.id_generate =b.id_generate 
				where x.no_mesin = a.no_mesin 
				order by b.created_at DESC limit 1
			) as tgl_pengiriman 
			from (
				select tr_sales_order.created_at, tr_sales_order.id_dealer , tr_sales_order.id_sales_order, tr_sales_order.delivery_document_id , tr_sales_order.no_mesin , tr_prospek.nama_konsumen,tr_spk.alamat, tr_sales_order.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order.tgl_cetak_invoice,'1' as tipe_so, tr_sales_order.no_bastk , concat(concat(tr_spk.tgl_pengiriman,' '),tr_spk.waktu_pengiriman ) as tgl_rencana_pengiriman, date(tr_sales_order.tgl_pengiriman) as tgl_bastk , (case when tr_sales_order.tgl_terima_unit_ke_konsumen ='0000-00-00' then '' else tr_sales_order.tgl_terima_unit_ke_konsumen end) as tgl_terima_unit_ke_konsumen 
				FROM tr_sales_order 
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek.id_karyawan_dealer 
				where LEFT(tr_sales_order.created_at,7)>'2019-11' and tr_sales_order.id_dealer = '$id_dealer'
				UNION 
				select tr_sales_order_gc.created_at, tr_sales_order_gc.id_dealer , tr_sales_order_gc_nosin.id_sales_order_gc  as id_sales_order, tr_sales_order_gc_nosin.delivery_document_id , tr_sales_order_gc_nosin.no_mesin ,  tr_spk_gc.nama_npwp as nama_konsumen,tr_spk_gc.alamat, tr_sales_order_gc_nosin.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order_gc.tgl_cetak_invoice,'2' as tipe_so, tr_sales_order_gc.no_bastk, concat(concat(tr_spk_gc.tgl_pengiriman,' '),tr_spk_gc.waktu_pengiriman ), date(tr_sales_order_gc.tgl_bastk) as tgl_bastk , (case when tr_sales_order_gc.tgl_terima_unit_konsumen ='0000-00-00' then '' else tr_sales_order_gc.tgl_terima_unit_konsumen end) as tgl_terima_unit_ke_konsumen
				FROM tr_sales_order_gc_nosin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc		
				LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc 
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek_gc.id_karyawan_dealer 
				where LEFT(tr_sales_order_gc.created_at,7)>'2019-11' and tr_sales_order_gc.id_dealer = '$id_dealer'
			)a 
			INNER JOIN tr_scan_barcode ON a.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			where a.tgl_cetak_invoice is not null and a.status_delivery is not null
			$cari	
			order by a.created_at desc
			LIMIT $start,$limit
        	");

        $data = array();

        foreach($dataSo->result() as $row)
        {
            $print    = $this->m_admin->set_tombol($id_menu, $group, 'print');	
	    if($row->tipe_so == '1'){
		$tombol = "<a href='dealer/sppu/cetak_sppu?id=$row->id_sales_order' target='_blank'>
                    	<button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak</button>
                  	</a>";
		$nomesin[0] = "<a data-toggle='tooltip' href='dealer/sppu/detail_no_mesin?id=$row->no_mesin'>$row->no_mesin</a>"; 
            	$norangka[0]=  $row->no_rangka; 
              	$tipe[0] = $row->tipe_ahm; 
              	$warna[0] = $row->warna; 
             }else if($row->tipe_so =='2'){
			$tombol = "<a href='dealer/sppu/cetak_sppu_gc?id=$row->id_sales_order' target='_blank'>
                    	<button $print class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak</button>
                  	</a>";

		$dt_nomesin = $this->db->query("SELECT tr_scan_barcode.no_mesin,no_rangka 
              	FROM tr_sales_order_gc_nosin 
              	JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
              	WHERE id_sales_order_gc='$row->id_sales_order'
              	");

	            $dt_tipe = $this->db->query("SELECT tipe_ahm,ms_warna.warna FROM tr_sales_order_gc_nosin 
              JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin 
              JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
              JOIN ms_warna ON ms_warna.id_warna=tr_scan_barcode.warna
              WHERE id_sales_order_gc='$row->id_sales_order' GROUP BY tipe_motor,warna
              ");

            $tipe=array();
            $warna=array();
            foreach ($dt_tipe->result() as $tp) {
              $tipe[] = $tp->tipe_ahm; 
              $warna[] = $tp->warna; 
            }

            $nomesin=array();
            $norangka=array();
            foreach ($dt_nomesin->result() as $rs) {
              $nomesin[] = $rs->no_mesin; 
              $norangka[] = $rs->no_rangka; 
            }

	}
	      $status_delivery='';
	      if ($row->status_delivery=='in_progress') {
                $status_delivery = "<label class='label label-info'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }
              if ($row->status_delivery=='delivered') {
                $status_delivery = "<label class='label label-success'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }
              if ($row->status_delivery=='ready') {
                $status_delivery = "<label class='label label-default'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }
              if ($row->status_delivery=='back_to_dealer') {
                $status_delivery = "<label class='label label-danger'>".ucwords(str_replace('_', ' ', $row->status_delivery))."</label>";
              }


            $data[]= array(
            	'',           
                $row->id_sales_order,
                implode(', </br>', $nomesin),
                implode(', </br>', $norangka),
                implode(', </br>', $tipe),
                implode(', </br>', $warna),
                $row->nama_konsumen,
                $row->alamat,
                $row->tgl_cetak_invoice,
		$row->tgl_rencana_pengiriman,   
		$row->tgl_bastk,      
		$row->tgl_pengiriman,
                $row->tgl_terima_unit_ke_konsumen,
		$status_delivery,
		$tombol
            );     
        }

        $get_total = $this->db->query("
			select tr_scan_barcode.no_rangka, tr_scan_barcode.no_mesin from (
				select tr_sales_order.created_at, tr_sales_order.id_dealer , tr_sales_order.id_sales_order, tr_sales_order.delivery_document_id , tr_sales_order.no_mesin , tr_prospek.nama_konsumen,tr_spk.alamat, tr_sales_order.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order.tgl_cetak_invoice
				FROM tr_sales_order 
				LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
				LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek.id_karyawan_dealer 
				where LEFT(tr_sales_order.created_at,7)>'2019-11' and tr_sales_order.id_dealer = '$id_dealer'
				UNION 
				select tr_sales_order_gc.created_at, tr_sales_order_gc.id_dealer , tr_sales_order_gc_nosin.id_sales_order_gc , tr_sales_order_gc_nosin.delivery_document_id , tr_sales_order_gc_nosin.no_mesin ,  tr_spk_gc.nama_npwp,tr_spk_gc.alamat, tr_sales_order_gc_nosin.status_delivery, ms_karyawan_dealer.id_flp_md, ms_karyawan_dealer.nama_lengkap, tr_sales_order_gc.tgl_cetak_invoice
				FROM tr_sales_order_gc_nosin
				LEFT JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc		
				LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc 
				left join ms_karyawan_dealer on ms_karyawan_dealer.id_karyawan_dealer = tr_prospek_gc.id_karyawan_dealer 
				where LEFT(tr_sales_order_gc.created_at,7)>'2019-11' and tr_sales_order_gc.id_dealer = '$id_dealer'
			)a 
			INNER JOIN tr_scan_barcode ON a.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			where a.tgl_cetak_invoice is not null and a.status_delivery is not null
			$cari        
	");

        $total_data = $get_total->num_rows();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_data,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

}
