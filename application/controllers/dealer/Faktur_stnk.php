<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Faktur_stnk extends CI_Controller
{
	var $tables =   "tr_faktur_stnk";
	var $folder =   "dealer";
	var $page		=		"faktur_stnk";
	var $pk     =   "id_faktur_stnk";
	var $title  =   "Berkas BBN";
	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('mokita_model');
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
	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "view";
		$id_dealer 			= $this->m_admin->cari_dealer();
		$data['dt_faktur'] = $this->db->query("SELECT * FROM tr_faktur_stnk WHERE id_dealer = '$id_dealer' ORDER BY no_bastd DESC");
		$this->template($data);
	}
	public function konfirmasi()
	{
		$id    					= $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= "Konfirmasi " . $this->title;
		$data['set']		= "konfirmasi";
		$data['dt_faktur'] = $this->m_admin->getByID($this->tables, "no_bastd", $id);
		$this->template($data);
	}
	public function detail()
	{
		$id    					= $this->input->get("id");
		$data['isi']    = $this->page;
		$data['title']	= "Detail " . $this->title;
		$data['set']		= "detail";
		$no_bastd 			= $this->input->get('id');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'");
		$data['dt_faktur'] = $this->m_admin->getByID($this->tables, "no_bastd", $id);
		$this->template($data);
	}
	public function cari_id()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");
		$id_dealer	= $this->m_admin->cari_dealer();
		$get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
		if ($get_dealer->num_rows() > 0) {
			$get_dealer = $get_dealer->row()->kode_dealer_md;
			$panjang = strlen($get_dealer);
		} else {
			$get_dealer = '';
			$panjang = '';
		}
		$kode_dealer = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer)->row()->kode_dealer_md;
		$pr_num = $this->db->query("SELECT * FROM tr_faktur_stnk WHERE RIGHT(no_bastd,$panjang) = '$kode_dealer' AND LEFT(no_bastd,4) = '$th' ORDER BY no_bastd DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->no_bastd) - (12 + $panjang);
			$id 	= substr($row->no_bastd, $pan, 5) + 1;
			$id_s 	= substr($row->no_bastd, $pan, 5);
			if ($id < 10) {
				$kode1 = $th . "/" . "0000" . $id . "/BASTD";
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . "/" . "000" . $id . "/BASTD";
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . "/" . "00" . $id . "/BASTD";
			} elseif ($id > 999) {
				$kode1 = $th . "/" . "0" . $id . "/BASTD";
			}
			$kode = $kode1 . "-" . $kode_dealer;
		} else {
			$kode = $th . "/00001/BASTD-" . $kode_dealer;
		}
		return $kode;
	}
	public function t_stnk()
	{
		$start_date = $this->input->post('start_date');
		$end_date 	= $this->input->post('end_date');
		$id_dealer 	= $this->m_admin->cari_dealer();
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_sales_order 
				WHERE tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' 
				AND id_dealer = '$id_dealer'
				AND tr_sales_order.id_sales_order NOT IN (SELECT tr_faktur_stnk_detail.id_sales_order FROM tr_faktur_stnk_detail JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd 
					WHERE tr_faktur_stnk_detail.id_sales_order IS NOT NULL AND (tr_faktur_stnk.status_faktur = 'approved' OR tr_faktur_stnk.status_faktur = 'proses'))
					and tgl_terima_unit_ke_konsumen != '' and tgl_terima_unit_ke_konsumen is not null 
				");
		// ambil data dari tr_bantuan_bbn_luar
		$this->db->where('status', 'approved');
		$data['dt_bbn_luar'] = $this->db->get('tr_bantuan_bbn_luar');

		//tanggal masih belum fixxx
		$data['dt_stnk_gc'] = $this->db->query("SELECT * FROM tr_sales_order_gc 
				INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
				JOIN tr_generate_list_unit_delivery_detail on tr_sales_order_gc_nosin.no_mesin = tr_generate_list_unit_delivery_detail.no_mesin and tr_generate_list_unit_delivery_detail.id_sales_order = tr_sales_order_gc_nosin.id_sales_order_gc
				WHERE tgl_cetak_invoice BETWEEN '$start_date' AND '$end_date' 
				AND tr_sales_order_gc.id_dealer = '$id_dealer' AND tr_sales_order_gc.status_so = 'so_invoice' 
				AND tr_sales_order_gc_nosin.no_mesin NOT IN (SELECT tr_faktur_stnk_detail.id_sales_order FROM tr_faktur_stnk_detail JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd 
					WHERE tr_faktur_stnk_detail.id_sales_order IS NOT NULL AND (tr_faktur_stnk.status_faktur = 'approved' OR tr_faktur_stnk.status_faktur = 'proses'))");
		$data['status'] = "input";
		$this->load->view('dealer/t_stnk', $data);
	}
	public function t_stnk_detail()
	{
		$no_bastd = $this->input->post('no_bastd');
		$data['dt_stnk'] = $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_sales_order ON tr_faktur_stnk_detail.no_spk = tr_sales_order.no_spk 
			WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd'");
		$data['status'] = "detail";
		$this->load->view('dealer/t_stnk', $data);
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$this->template($data);
	}
	public function save()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$no_mesin		= $this->input->post("no_mesin");
		$no_bastd 	= $this->cari_id();
		$da['no_bastd'] 			  = $no_bastd;
		$da['tgl_bastd'] 			 = $tgl;
		$da['start_date'] 		 = $this->input->post("start_date");
		$da['end_date'] 			  = $this->input->post("end_date");
		$da['id_dealer'] 			 = $this->m_admin->cari_dealer();
		$da['status_faktur'] = $this->input->post("mode");
		$da['created_at'] 	  = waktu_full();
		$da['created_by'] 	  = $login_id;
		$cek_dealer = 0;
		$unit = 0;
		$data['no_bastd'] = $data_gc['no_bastd']		= $no_bastd;
		$no 		 = $this->input->post('no');
		
		if ($no >= 1) {
			$temp_nosin = $_POST['no_mesin_1'];

			$temp_id_dealer = $this->db->query("SELECT id_dealer FROM tr_sales_order WHERE no_mesin = '$temp_nosin'")->row()->id_dealer;
			if ($da['id_dealer'] != $temp_id_dealer) {
				$cek_dealer = 1;
				
				$_SESSION['pesan'] 	= "Silahkan Refresh / Login ulang kembali";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/faktur_stnk/add'>";
			}
		}

		$this->db->query("SET FOREIGN_KEY_CHECKS=0");
		for ($key = 1; $key <= $no; $key++) {
			$no_mesin 	= $_POST['no_mesin_' . $key];
			$no_rangka 	= $_POST['no_rangka_' . $key];
			$nama_konsumen 	= $_POST['nama_konsumen_' . $key];
			$alamat 		= $_POST['alamat_' . $key];

			$data["biaya_bbn"] 			= $_POST['biaya_bbn_' . $key];
			$data["biaya_bbn_md"]		= $_POST['biaya_bbn_md_' . $key];
			$data["harga_unit"]			= $_POST['harga_unit_' . $key];
			$data["no_spk"] 				= $_POST['no_spk_' . $key];
			$data["id_sales_order"] = $_POST['id_sales_order_' . $key];
			$data["no_mesin"] 			= $no_mesin;
			$data["no_rangka"] 			= $no_rangka;
			$data["nama_konsumen"] 	= $nama_konsumen;
			$data["alamat"] 				= $alamat;
			if (isset($_POST['check_ktp_' . $key])) {
				$data["ktp"] = "ya";
				$ktp = "ya";
			} else {
				$data["ktp"] 		= "tidak";
				$ktp = "tidak";
			}
			if (isset($_POST['check_fisik_' . $key])) {
				$data["fisik"] = "ya";
				$fisik = "ya";
			} else {
				$data["fisik"] 		= "tidak";
				$fisik = "tidak";
			}
			if (isset($_POST['check_stnk_' . $key])) {
				$data["stnk"] = "ya";
				$stnk = "ya";
			} else {
				$data["stnk"] 		= "tidak";
				$stnk = "tidak";
			}
			if (isset($_POST['check_bpkb_' . $key])) {
				$data["bpkb"] = "ya";
				$bpkb = "ya";
			} else {
				$data["bpkb"] 		= "tidak";
				$bpkb = "tidak";
			}
			if (isset($_POST['check_kuasa_' . $key])) {
				$data["kuasa"] = "ya";
				$kuasa = "ya";
			} else {
				$data["kuasa"] 		= "tidak";
				$kuasa = "tidak";
			}
			if (isset($_POST['check_ckd_' . $key])) {
				$data["ckd"] = "ya";
				$ckd = "ya";
			} else {
				$data["ckd"] 		= "tidak";
				$ckd = "tidak";
			}
			if (isset($_POST['check_permohonan_' . $key])) {
				$data["permohonan"] = "ya";
				$permohonan = "ya";
			} else {
				$data["permohonan"] 		= "tidak";
				$permohonan = "tidak";
			}
			$this->db->where('id_bantuan_bbn_luar', $_POST['id_sales_order_' . $key]);
			$this->db->update('tr_bantuan_bbn_luar', array('status' => 'proses'));

			$cek = $this->db->query("SELECT * FROM tr_faktur_stnk JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
 						WHERE no_mesin = '$no_mesin' and status_faktur !='rejected'");

			if ($ktp == 'ya' && $fisik == 'ya' && $stnk == 'ya' && $bpkb == 'ya' && $kuasa == 'ya' && $ckd == 'ya' && $permohonan == 'ya') {
				$get_leads = $this->mokita_model->cek_sales_order(['no_mesin' => $no_mesin]);
				if ($get_leads) {
					$kirim_ce_stnk[] = $get_leads;
				}
				if ($cek->num_rows() > 0) {
					$this->m_admin->update("tr_faktur_stnk_detail", $data, "no_mesin", $no_mesin);
				} else {
					$this->m_admin->insert("tr_faktur_stnk_detail", $data);
				}
				$unit++;
			}
		}
		$no_gc = $this->input->post('no_gc');
		for ($kuy = 1; $kuy <= $no_gc; $kuy++) {
			$no_mesin_gc 	= $_POST['no_mesin_gc_' . $kuy];
			$no_rangka_gc 	= $_POST['no_rangka_gc_' . $kuy];
			$nama_konsumen_gc 	= $_POST['nama_konsumen_gc_' . $kuy];
			$alamat_gc 		= $_POST['alamat_gc_' . $kuy];

			$data_gc["biaya_bbn"] 			= $_POST['biaya_bbn_gc_' . $kuy];
			$data_gc["biaya_bbn_md"]		= $_POST['biaya_bbn_md_gc_' . $kuy];
			$data_gc["harga_unit"]			= $_POST['harga_unit_gc_' . $kuy];
			$data_gc["no_spk"] 				= $_POST['no_spk_gc_' . $kuy];
			$data_gc["id_sales_order"] = $_POST['id_sales_order_gc_' . $kuy];
			$data_gc["no_mesin"] 			= $no_mesin_gc;
			$data_gc["no_rangka"] 			= $no_rangka_gc;
			$data_gc["nama_konsumen"] 	= $nama_konsumen_gc;
			$data_gc["alamat"] 				= $alamat_gc;
			if (isset($_POST['check_ktp_gc_' . $kuy])) {
				$data_gc["ktp"] = "ya";
				$ktp_gc = "ya";
			} else {
				$data_gc["ktp"] 		= "tidak";
				$ktp_gc = "tidak";
			}
			if (isset($_POST['check_fisik_gc_' . $kuy])) {
				$data_gc["fisik"] = "ya";
				$fisik_gc = "ya";
			} else {
				$data_gc["fisik"] 		= "tidak";
				$fisik_gc = "tidak";
			}
			if (isset($_POST['check_stnk_gc_' . $kuy])) {
				$data_gc["stnk"] = "ya";
				$stnk_gc = "ya";
			} else {
				$data_gc["stnk"] 		= "tidak";
				$stnk_gc = "tidak";
			}
			if (isset($_POST['check_bpkb_gc_' . $kuy])) {
				$data_gc["bpkb"] = "ya";
				$bpkb_gc = "ya";
			} else {
				$data_gc["bpkb"] 		= "tidak";
				$bpkb_gc = "tidak";
			}
			if (isset($_POST['check_kuasa_gc_' . $kuy])) {
				$data_gc["kuasa"] = "ya";
				$kuasa_gc = "ya";
			} else {
				$data_gc["kuasa"] 		= "tidak";
				$kuasa_gc = "tidak";
			}
			if (isset($_POST['check_ckd_gc_' . $kuy])) {
				$data_gc["ckd"] = "ya";
				$ckd_gc = "ya";
			} else {
				$data_gc["ckd"] 		= "tidak";
				$ckd_gc = "tidak";
			}
			if (isset($_POST['check_permohonan_gc_' . $kuy])) {
				$data_gc["permohonan"] = "ya";
				$permohonan_gc = "ya";
			} else {
				$data_gc["permohonan"] 		= "tidak";
				$permohonan_gc = "tidak";
			}

			$cek_gc = $this->db->query("SELECT * FROM tr_faktur_stnk_detail WHERE no_mesin = '$no_mesin_gc'");
			if ($ktp_gc == 'ya' && $fisik_gc == 'ya' && $stnk_gc == 'ya' && $bpkb_gc == 'ya' && $kuasa_gc == 'ya' && $ckd_gc == 'ya' && $permohonan_gc == 'ya') {
				if ($cek_gc->num_rows() > 0) {
					$this->m_admin->update("tr_faktur_stnk_detail", $data_gc, "no_mesin", $no_mesin_gc);
				} else {
					$this->m_admin->insert("tr_faktur_stnk_detail", $data_gc);
				}
				$unit++;
			}
		}

		if ($cek_dealer) {			
			$_SESSION['pesan'] 	= "Silahkan Refresh / Login ulang kembali";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/faktur_stnk/add'>";
		} else {
			if (isset($kirim_ce_stnk)) {
				foreach ($kirim_ce_stnk as $key => $spk) {
					$last_status_ce_apps = $this->mokita_model->last_tracking($spk->no_spk);
					$array_post = [
						'AppsOrderNumber'   => '',
						'DmsOrderNumber'    => '',
						'CustomerPhoneNumber' => $spk->no_hp,
						'CreditStatus' => $last_status_ce_apps ? $last_status_ce_apps->CreditStatus : '',
						'IndentStatus' => $last_status_ce_apps ? $last_status_ce_apps->IndentStatus : '',
						'DeliveryStatus' => $last_status_ce_apps ? $last_status_ce_apps->DeliveryStatus : '',
						'EstimatedDeliveryDate' => $spk->tgl_pengiriman,
						'EngineNumber' => $spk->no_mesin,
						'StnkStatus' => 'Pengajuan faktur STNK',
						'BpkbStatus' => 'Pengajuan faktur BPKB',
						'VehicleNumber' => '',
					];
					// send_json($array_post);
					if ($spk->input_from=='sinsengo') {
						$this->db_crm = $this->load->database('db_crm', true);
						$get_leads = $this->db_crm->get_where("leads", ['leads_id' => $spk->leads_id])->row();
						if ($get_leads!=null) {
							$array_post['AppsOrderNumber']   = $get_leads->sourceRefID;
							$array_post['DmsOrderNumber']    = $get_leads->batchID;
							$this->load->library("mokita");
							$this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
						}
					}
					$this->mokita_model->set_tracking($spk->no_spk, $array_post);
				}
			}
			if ($unit > 0) {
				$this->m_admin->insert("tr_faktur_stnk", $da);
				$_SESSION['pesan'] 	= "Data has been saved successfully";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/faktur_stnk'>";
			} else {
				$_SESSION['pesan'] 	= "Silahkan ceklist semua";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/faktur_stnk/add'>";
			}
			$this->db->query("SET FOREIGN_KEY_CHECKS=1");
		}
	}
	public function cari_id_bbn()
	{
		$tgl						= date("d");
		$bln 						= date("m");
		$th 						= date("Y");

		$pr_num = $this->db->query("SELECT * FROM tr_monout_piutang_bbn where LEFT(no_rekap,4) = '$th' ORDER BY no_rekap DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->no_rekap) - 3;
			$id 	= substr($row->no_rekap, 11, 5) + 1;
			$isi 	= sprintf("%'.05d", $id);
			$kode = $th . $bln . "/PIB/" . $isi;
		} else {
			$kode = $th . $bln . "/PIB/00001";
		}
		return $kode;
	}
	public function cetak()
	{
		$waktu 			= gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl 				= gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id		= $this->session->userdata('id_user');
		$tabel			= $this->tables;
		$pk 				= $this->pk;
		$no_bastd 			= $this->input->get('id');
		$cek_bastd = $this->db->query("SELECT * FROM tr_faktur_stnk WHERE no_bastd='$no_bastd'")->row();
		if ($cek_bastd->status_faktur == 'input') {
			$data['status_faktur'] = "proses";
			$data['updated_at']		= $waktu;
			$data['tgl_cetak']		= date('Y-m-d');
			$data['updated_by']		= $login_id;
			$this->m_admin->update("tr_faktur_stnk", $data, "no_bastd", $no_bastd);
		}
		$sql = $this->db->query("SELECT SUM(biaya_bbn) AS jum FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'")->row();
		$dr['no_rekap'] 	= $this->cari_id_bbn();
		$dr['tgl_rekap'] 	= $tgl;
		$dr['no_bastd'] 	= $no_bastd;
		$dr['total'] 			= $sql->jum;
		$cek = $this->m_admin->getByID("tr_monout_piutang_bbn", "no_bastd", $no_bastd);
		if ($cek->num_rows() > 0) {
			$f = $cek->row();
			$dr['updated_at'] 					= $waktu;
			$dr['updated_by'] 					= $login_id;
			$this->m_admin->update("tr_monout_piutang_bbn", $dr, "no_bastd", $f->no_bastd);
		} else {
			$dr['status_mon']	= "input";
			$dr['created_at'] 					= $waktu;
			$dr['created_by'] 					= $login_id;
			$this->m_admin->insert("tr_monout_piutang_bbn", $dr);
		}
		$get_stnk 	= $this->db->query("SELECT *
			 	FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer		 		
		 		WHERE tr_faktur_stnk.no_bastd = '$no_bastd'")->row();

		$s = $this->db->query("SELECT COUNT(no_mesin) AS qty FROM tr_faktur_stnk_detail WHERE no_bastd = '$no_bastd'")->row();
		if (isset($s->qty)) {
			$jum = $s->qty;
		} else {
			$jum = 0;
		}
		$pdf = new FPDF('p', 'mm', 'A4');
		$pdf->AddPage();
		// head	  
		$pdf->SetFont('TIMES', '', 10);
		$pdf->Cell(50, 5, 'Jambi, ' . date("d-m-Y", strtotime($get_stnk->tgl_bastd)) . '', 0, 1, 'L');
		$pdf->Cell(50, 5, 'Kepada Yth,', 0, 1, 'L');
		$pdf->Cell(50, 5, 'PT. SINAR SENTOSA PRIMATAMA', 0, 1, 'L');
		$pdf->Cell(50, 5, 'Jl.Kolonel Abunjani No.09 Jambi', 0, 1, 'L');
		$pdf->Line(11, 31, 200, 31);

		$pdf->SetFont('TIMES', '', 10);
		$pdf->Cell(1, 2, '', 0, 1);
		$pdf->Cell(30, 5, 'Nomor', 0, 0);
		$pdf->Cell(70, 5, ': ' . $no_bastd . '', 0, 1);
		$pdf->Cell(30, 5, 'Perihal ', 0, 0);
		$pdf->Cell(70, 5, ': Map Berkas untuk BBN', 0, 1);
		$pdf->Cell(30, 5, 'Dengan Hormat ', 0, 1);
		$pdf->MultiCell(190, 5, 'Bersama dengan surat ini kami dari ' . $get_stnk->nama_dealer . ' mengirimkan map untuk proses BBN sebanyak ' . $jum . ' unit dengan perincian sebagai berikut  :', 0, 1);

		$pdf->Cell(2, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 12);
		// buat tabel disini
		$pdf->SetFont('TIMES', 'B', 10);

		// kasi jarak
		$pdf->Cell(2, 5, '', 5, 10);

		$pdf->Cell(10, 5, 'No', 1, 0);
		$pdf->Cell(70, 5, 'Nama', 1, 0);
		$pdf->Cell(28, 5, 'No Mesin', 1, 0);
		$pdf->Cell(53, 5, 'Kode Tipe', 1, 0);
		$pdf->Cell(28, 5, 'Biaya BBN (Rp)', 1, 1);
		$pdf->SetFont('times', '', 10);
		$get_nosin 	= $this->db->query("SELECT * FROM tr_faktur_stnk_detail 
			INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin=tr_scan_barcode.no_mesin 
	  		WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd' ORDER BY tr_scan_barcode.tipe_motor ASC");
		$i = 1;
		$to = 0;
		foreach ($get_nosin->result() as $r) {
			$cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,
	  		tr_scan_barcode.tipe_motor
			 	FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin		 					 				 	
		 		WHERE tr_faktur_stnk_detail.no_mesin = '$r->no_mesin' and tr_faktur_stnk_detail.no_bastd ='$no_bastd' ORDER BY tr_scan_barcode.tipe_motor ASC")->row();
			$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan ='$cek_pik->tipe_motor' ");
			if ($tipe->num_rows() > 0) {
				$tipe = $tipe->row();
			}
			$cek_harga = $this->m_admin->getByID("tr_sales_order_gc_nosin", "no_mesin", $cek_pik->no_mesin)->row();
			$harga_m = $cek_pik->biaya_bbn;
			/*
			$pdf->Cell(10, 5, $i, 1, 0);
			$pdf->Cell(70, 5, $r->nama_konsumen, 1, 0);
			$pdf->Cell(28, 5, $cek_pik->no_mesin, 1, 0);
			$pdf->Cell(53, 5, $tipe->id_tipe_kendaraan . ' | ' . strip_tags($tipe->deskripsi_ahm), 1, 0);
			$pdf->Cell(28, 5, number_format($cek_pik->biaya_bbn, 0, ',', '.'), 1, 1, 'R');
			*/

			$height = ceil($pdf->GetStringWidth($r->nama_konsumen) / 70) * 5;
			if($height==0){
				$height=5;
			}

			$x = $pdf->getX();
			$y = $pdf->getY();
			if($y<271){
				$pdf->MultiCell(10, $height, $i , 1, 'L');
				$pdf->setXY($x+10,$y);
				$pdf->MultiCell(70, 5, $r->nama_konsumen, 1, 'L');
				$pdf->setXY($x+80,$y);
				$pdf->MultiCell(28, $height, $r->no_mesin, 1, 'L');			
				$pdf->setXY($x+108,$y);
				$pdf->MultiCell(53, $height, $tipe->id_tipe_kendaraan . ' | ' . strip_tags($tipe->deskripsi_ahm), 1, 'L');
				$pdf->setXY($x+161,$y);
				$pdf->MultiCell(28, $height, number_format($cek_pik->biaya_bbn, 0, ',', '.'), 1,'R');
			}else{
				$pdf->AddPage();
				$x = 10.00125;
				$y = 10.00125;
				
				$pdf->setXY($x,$y);
				$pdf->MultiCell(10, $height, $i , 1, 'L');
				$pdf->setXY($x+10,$y);
				$pdf->MultiCell(70, 5, $r->nama_konsumen, 1, 'L');
				$pdf->setXY($x+80,$y);
				$pdf->MultiCell(28, $height, $r->no_mesin, 1, 'L');			
				$pdf->setXY($x+108,$y);
				$pdf->MultiCell(53, $height, $tipe->id_tipe_kendaraan . ' | ' . strip_tags($tipe->deskripsi_ahm), 1, 'L');
				$pdf->setXY($x+161,$y);
				$pdf->MultiCell(28, $height, number_format($cek_pik->biaya_bbn, 0, ',', '.'), 1,'R');
			}

			$i++;
			$to = $to + $harga_m;
		}
		// ambil data tr_bantuan_bbn_luar
		$get_nosin1 	= $this->db->query("SELECT * FROM tr_faktur_stnk_detail 
			INNER JOIN tr_bantuan_bbn_luar ON tr_faktur_stnk_detail.no_mesin=tr_bantuan_bbn_luar.no_mesin 
	  		WHERE tr_faktur_stnk_detail.no_bastd = '$no_bastd' ");
		foreach ($get_nosin1->result() as $r) {
			$cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_bantuan_bbn_luar.no_rangka,tr_bantuan_bbn_luar.id_tipe_kendaraan
			 	FROM tr_faktur_stnk_detail INNER JOIN tr_bantuan_bbn_luar ON tr_faktur_stnk_detail.no_mesin = tr_bantuan_bbn_luar.no_mesin		 					 				 	
		 		WHERE tr_faktur_stnk_detail.no_mesin = '$r->no_mesin' ORDER BY tr_bantuan_bbn_luar.id_tipe_kendaraan ASC")->row();
			$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan ='$cek_pik->id_tipe_kendaraan' ");
			if ($tipe->num_rows() > 0) {
				$tipe = $tipe->row();
			}
			$cek_harga = $this->m_admin->getByID("tr_sales_order_gc_nosin", "no_mesin", $cek_pik->no_mesin)->row();
			$harga_m = $cek_pik->biaya_bbn;
			$pdf->Cell(10, 5, $i, 1, 0);
			$pdf->Cell(70, 5, $r->nama_konsumen, 1, 0);
			$pdf->Cell(28, 5, $cek_pik->no_mesin, 1, 0);
			$pdf->Cell(53, 5, $tipe->id_tipe_kendaraan . ' | ' . strip_tags($tipe->deskripsi_ahm), 1, 0);
			$pdf->Cell(28, 5, number_format($cek_pik->biaya_bbn, 0, ',', '.'), 1, 1, 'R');
			$i++;
			$to = $to + $harga_m;
		}
		$pdf->Cell(10, 5, '', 1, 0);
		$pdf->Cell(70, 5, '', 1, 0);
		$pdf->Cell(28, 5, '', 1, 0);
		$pdf->Cell(53, 5, 'Total Biaya BBN', 1, 0);
		$pdf->Cell(28, 5, number_format($to, 0, ',', '.'), 1, 1, 'R');

		$pdf->Cell(9, 3, '', 5, 10);
		$pdf->SetFont('TIMES', '', 10);
		$pdf->Cell(10, 5, '', 0, 1);
		$pdf->Cell(10, 15, '', 0, 0);
		$pdf->Cell(30, 5, 'Pembayaran Biaya BBN tersebut di atas telah kami transfer ke rekening :', 0, 1, 'L');

		$pdf->Cell(30, 5, 'Atas Nama ', 0, 0);
		$pdf->Cell(70, 5, ': PT. Sinar Sentosa Primatama', 0, 1);
		$pdf->Cell(30, 5, 'No. Rekening ', 0, 0);
		$pdf->Cell(70, 5, ': ', 0, 1);
		$pdf->Cell(30, 5, 'Nama Bank ', 0, 0);
		$pdf->Cell(70, 5, ': ', 0, 1);
		$pdf->Cell(30, 5, 'Tanggal Transfer ', 0, 0);
		$pdf->Cell(70, 5, ': ', 0, 1);
		$pdf->MultiCell(190, 5, 'Demikian surat pengantar ini kami buat untuk pemrosesan BBN. Atas perhatian dan kerjasamanya kami ucapkan terima kasih', 0, 1);
		$pdf->Cell(50, 5, '', 0, 1, 'C');
		$pdf->Cell(50, 5, 'Dibuat :', 0, 0, 'C');
		$pdf->Cell(50, 5, 'Diketahui:', 0, 1, 'C');
		$pdf->Cell(10, 8, '', 0, 0);
		$pdf->Cell(10, 10, '', 0, 1);
		$pdf->Cell(10, 5, '', 0, 1);
		$pdf->SetFont('TIMES', '', 8);
		$pdf->Cell(10, 3, 'Catatan :', 0, 1, 'L');
		$pdf->Cell(10, 3, '1. Pengisian daftar map harus diurutkan sesuai Tipe Motor', 0, 1, 'L');
		$pdf->Cell(10, 3, '2. Ujung kanan map harus dibuat nomor sesuai dengan nama dalam surat', 0, 1, 'L');
		$pdf->Cell(10, 3, '3. Map yang dikirim harus telah lengkap sesuai dengan persyaratan yang berlaku', 0, 1, 'L');
		$pdf->Cell(10, 3, '4. Fotocopy bukti transfer harus dilampirkan', 0, 1, 'L');

		$pdf->Cell(70, 5, '=======================================================================================================================', 0, 1);
		$pdf->Cell(50, 5, '', 0, 1, 'C');

		$pdf->SetFont('TIMES', '', 10);
		$pdf->Cell(195, 1, 'Map telah diterima oleh pihak PT. Sinar Sentosa Primatama', 0, 1, 'C');
		$pdf->Cell(50, 5, '', 0, 1, 'C');
		$pdf->SetX(7);
		$pdf->Cell(85, 5, '1. Bagian Keuangan', 0, 0, 'L');
		$pdf->Cell(50, 5, '2. Bagian Faktur', 0, 1, 'L');
		$pdf->Cell(15, 5, 'Nama', 0, 0);
		$pdf->Cell(70, 5, ': ______________________', 0, 0);
		$pdf->Cell(15, 5, 'Nama', 0, 0);
		$pdf->Cell(60, 5, ': ______________________', 0, 1);
		$pdf->Cell(15, 5, 'Tanggal', 0, 0);
		$pdf->Cell(70, 5, ': _________________Jam :________WIB', 0, 0);
		$pdf->Cell(15, 5, 'Tanggal', 0, 0);
		$pdf->Cell(60, 5, ': _________________Jam :_________WIB', 0, 1);
		$pdf->Cell(50, 5, '', 0, 1, 'C');
		$pdf->Cell(50, 5, '', 0, 1, 'C');

		$pdf->Cell(15, 5, 'TTD', 0, 0);
		$pdf->Cell(70, 5, ': ______________________', 0, 0);
		$pdf->Cell(15, 5, 'TTD', 0, 0);
		$pdf->Cell(60, 5, ': ______________________', 0, 1);
		$pdf->Output();
	}
	public function status_nosin()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']   = "status_nosin";
		$id_dealer     = $this->m_admin->cari_dealer();
		$data['nosin'] = $this->db->query("SELECT tr_sales_order.*,nama_konsumen,tipe_ahm,warna,tr_spk.id_tipe_kendaraan,tr_spk.id_warna FROM tr_sales_order 
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			JOIN ms_warna ON tr_spk.id_warna=ms_warna.id_warna
			WHERE tr_sales_order.id_dealer=$id_dealer AND no_invoice IS NOT NULL")->result();
		$this->template($data);
	}
}
