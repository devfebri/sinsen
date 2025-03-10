<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Konfirmasi_stnk extends CI_Controller
{

	var $tables =   "tr_konfirmasi_stnk";
	var $folder =   "dealer";
	var $page		=		"konfirmasi_stnk";
	var $pk     =   "no_konfirmasi";
	var $title  =   "Konfirmasi Penerimaan STNK, Plat & BPKB";

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
		$this->template($data);
	}
	public function add()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$data['dt_dealer'] = $this->m_admin->getSortCond("ms_dealer", "nama_dealer", "ASC");
		$this->template($data);
	}
	public function detail()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "detail";
		if (isset($_GET['bpkb'])) {
			$id = $this->input->get('bpkb');
			$data['jenis'] = 'bpkb';
			$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_bpkb", "no_serah_bpkb", $id);
		} elseif (isset($_GET['stnk'])) {
			$id = $this->input->get('stnk');
			$data['jenis'] = 'stnk';
			$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_stnk", "no_serah_stnk", $id);
		} elseif (isset($_GET['plat'])) {
			$id = $this->input->get('plat');
			$data['jenis'] = 'plat';
			$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_plat", "no_serah_plat", $id);
		}

		$this->template($data);
	}

	public function konfirmasi()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "konfirmasi";
		if (isset($_GET['bpkb'])) {
			$id = $this->input->get('bpkb');
			$data['jenis'] = 'bpkb';
			$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_bpkb", "no_serah_bpkb", $id);
		} elseif (isset($_GET['stnk'])) {
			$id = $this->input->get('stnk');
			$data['jenis'] = 'stnk';
			$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_stnk", "no_serah_stnk", $id);
		} elseif (isset($_GET['plat'])) {
			$id = $this->input->get('plat');
			$data['jenis'] = 'plat';
			$data['dt_stnk'] = $this->m_admin->getByID("tr_penyerahan_plat", "no_serah_plat", $id);
		}

		$this->template($data);
	}
	public function save_konfirmasi()
	{
		$waktu     = gmdate("y-m-d h:i:s", time() + 60 * 60 * 7);
		$tgl       = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$id_dealer = $this->m_admin->cari_dealer();
		$da['no_serah_terima'] = $this->input->post("no_serah");
		$no_serah_terima       = $this->input->post("no_serah");
		$da['tgl_terima']      = $tgl;
		$da['id_dealer']       = $this->m_admin->cari_dealer();
		$da['jenis_dokumen']   = $this->input->post("jenis_dokumen");
		$jenis                 = $this->input->post("jenis_dokumen");

		$da['status_serah'] 		= "input";
		$da['created_at'] 			= $waktu;
		$da['created_by'] 			= $login_id;
		
		$waktu_skrg = date('Y-m-d H:i:s');

		$jum = $this->input->post("jum");
		for ($i = 1; $i <= $jum; $i++) {
			if (isset($_POST["check_" . $i])) {
				$nosin                   = $_POST["no_mesin_" . $i];
				$data['no_serah_terima'] = $no_serah_terima;
				$data['no_mesin']        = $nosin;
				$data["status_nosin"]    = "input";

				if ($jenis == 'stnk') {
					$this->db->query("UPDATE tr_penyerahan_stnk SET status_stnk = 'terima' WHERE no_serah_stnk = '$no_serah_terima'");
					$this->db->query("UPDATE tr_penyerahan_stnk_detail SET status_nosin = 'terima', tgl_terima_dealer = '$waktu_skrg' WHERE no_mesin = '$nosin'");
				} elseif ($jenis == 'bpkb') {
					$this->db->query("UPDATE tr_penyerahan_bpkb SET status_bpkb = 'terima' WHERE no_serah_bpkb = '$no_serah_terima'");
					$this->db->query("UPDATE tr_penyerahan_bpkb_detail SET status_nosin = 'terima', tgl_terima_dealer = '$waktu_skrg' WHERE no_mesin = '$nosin'");
				} elseif ($jenis == 'plat') {
					$this->db->query("UPDATE tr_penyerahan_plat SET status_plat = 'terima' WHERE no_serah_plat = '$no_serah_terima'");
					$this->db->query("UPDATE tr_penyerahan_plat_detail SET status_nosin = 'terima', tgl_terima_dealer = '$waktu_skrg' WHERE no_mesin = '$nosin'");
				}

				$cek = $this->db->query("SELECT * FROM tr_konfirmasi_dokumen_detail WHERE no_mesin = '$nosin'");
				if ($cek->num_rows() > 0) {
					$this->m_admin->update("tr_konfirmasi_dokumen_detail", $data, "no_mesin", $nosin);
				} else {

					$this->m_admin->insert("tr_konfirmasi_dokumen_detail", $data);
				}
				$cek_activity = $this->db->get_where('tr_manage_activity_after_dealing', ['no_mesin' => $nosin])->num_rows();
				if ($cek_activity == 0) {

					//Cek Cust Cash / Kredit
					// $cek_cust = $this->db->query("SELECT * FROM(SELECT jenis_beli FROM tr_spk
					// 	JOIN tr_sales_order ON tr_spk.no_spk=tr_sales_order.no_spk
					// 	WHERE tr_sales_order.no_mesin='$nosin'	
					// 	UNION
					// 	SELECT jenis_beli FROM tr_spk_gc 
					// 	JOIN tr_sales_order_gc ON tr_sales_order_gc.no_spk_gc=tr_spk_gc.no_spk_gc
					// 	WHERE tr_sales_order_gc.id_sales_order_gc=(SELECT id_sales_order_gc FROM tr_sales_order_gc_nosin WHERE no_mesin='$nosin')
					// 	LIMIT 1) AS x LIMIT 1
					// 	")->row()->jenis_beli;

					$cek_cust = $this->db->query("SELECT jenis_beli,tr_spk.no_spk,tr_spk.no_hp,tr_spk.nama_konsumen FROM tr_spk
						JOIN tr_sales_order ON tr_spk.no_spk=tr_sales_order.no_spk
						WHERE tr_sales_order.no_mesin='$nosin'
						");
					if ($cek_cust->num_rows() > 0) {
						$cek_cust = $cek_cust->row();
						if (strtolower($cek_cust->jenis_beli) == 'kredit') {
							$detail_act = 'Follow UP - STNK/Plat No telah tersedia.';
							$kategori   = 'Reminder STNK/Plat No.';
						} else {
							$detail_act = 'Follow UP - STNK/BPKB/SRUT/Plat No telah tersedia.';
							$kategori   = 'Reminder STNK/BPKB/SRUT/Plat No.';
						}
						$ins_act[] = [
							'no_mesin' => $nosin,
							'no_spk'		  => $cek_cust->no_spk,
							'id_dealer'       => $id_dealer,
							'kategori'        => $kategori,
							'detail_activity' => $detail_act,
							'created_at'      => $waktu,
							'created_by'      => $login_id,
							'status'          => 'Not Started'
						];
					}
				}
				$so = $this->db->query("SELECT jenis_beli,tr_spk.no_spk,tr_spk.no_hp,tr_spk.nama_konsumen,tr_spk.id_tipe_kendaraan,tr_spk.id_warna FROM tr_spk
						JOIN tr_sales_order ON tr_spk.no_spk=tr_sales_order.no_spk
						WHERE tr_sales_order.no_mesin='$nosin'
						");
				if ($so->num_rows() > 0) {
					$ymd = date('Y-m-d');
					$so = $so->row();
					if (strtolower($so->jenis_beli) == 'kredit') {
						$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Reminder STNK (Kredit)' AND id_dealer='$id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1 ");
						if ($pesan_sms->num_rows() > 0) {
							$pesan  = $pesan_sms->row()->konten;
							$id_get = [
								'NamaDealer' => $id_dealer,
								'TipeUnit' => $so->id_tipe_kendaraan,
								'Warna' => $so->id_warna,
								'NamaCustomer' => $so->nama_konsumen,
							];
							$notif_sms[] = [
								'pesan' => pesan($pesan, $id_get),
								'no_hp' => $so->no_hp,
								'no_spk' => $so->no_spk,
								'nama_konsumen' => $so->nama_konsumen,
							];
						} else {
							$pesan = '';
						}
					} else {
						$pesan_sms = $this->db->query("SELECT * FROM ms_pesan WHERE tipe_pesan='Reminder STNK (Cash)' AND id_dealer='$id_dealer'  AND '$ymd' BETWEEN start_date AND end_date ORDER BY created_at DESC LIMIT 1 ");
						if ($pesan_sms->num_rows() > 0) {
							$pesan  = $pesan_sms->row()->konten;
							$id_get = [
								'NamaDealer' => $id_dealer,
								'TipeUnit' => $so->id_tipe_kendaraan,
								'Warna' => $so->id_warna,
								'NamaCustomer' => $so->nama_konsumen,
							];
							$notif_sms[] = [
								'pesan' => pesan($pesan, $id_get),
								'no_hp' => $so->no_hp,
								'no_spk' => $so->no_spk,
								'nama_konsumen' => $so->nama_konsumen,
							];
						} else {
							$pesan = "";
						}
					}
				}
			}
		}
		if (isset($ins_act)) {
			$this->db->insert_batch('tr_manage_activity_after_dealing', $ins_act);
		}
		$ce = $this->db->query("SELECT * FROM tr_konfirmasi_dokumen WHERE no_serah_terima = '$no_serah_terima'");
		if ($ce->num_rows() > 0) {
			$this->m_admin->update("tr_konfirmasi_dokumen", $da, "no_serah_terima", $no_serah_terima);
		} else {
			$this->m_admin->insert("tr_konfirmasi_dokumen", $da);
		}
		$pesan_sms = '';
		if (isset($notif_sms)) {
			$pesan_sms .= 'Keterangan pengiriman SMS kepada konsumen : </br>';
			foreach ($notif_sms as $val) {
				$status = sms_zenziva($val['no_hp'], $val['pesan']);
				if ($status['status'] == 0) {
					$pesan_sms .= '- ' . $val['nama_konsumen'] . ' ( No SPK : ' . $val['no_spk'] . ') Berhasil';
					$pesan_sms .= "</br>";
				} elseif ($status['status'] == 1) {
					$pesan_sms .= '- ' . $val['nama_konsumen'] . ' ( No SPK : ' . $val['no_spk'] . '). No tujuan tidak valid';
					$pesan_sms .= "</br>";
				}
			}
		}
		$_SESSION['pesan'] 	= "Data berhasil disimpan. $pesan_sms";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/konfirmasi_stnk'>";
	}
}
