<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Konfirmasi_bast extends CI_Controller
{
	var $tables = "tr_prospek";
	var $folder = "dealer";
	var $page   = "konfirmasi_bast";
	var $pk     = "id_prospek";
	var $title  = "Konfirmasi BAST";

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
		$data['set']	= "view";
		$id_dealer = $this->m_admin->cari_dealer();
		$data['dt_sales_order'] = $this->db->query("
			SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka 
			FROM tr_sales_order 
			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_dealer = '$id_dealer' 
			AND tr_sales_order.no_bastk IS NOT NULL AND tr_sales_order.status_delivery='in_progress'
			AND id_sales_order IN(SELECT id_sales_order FROM tr_generate_list_unit_delivery_detail JOIN tr_generate_list_unit_delivery ON tr_generate_list_unit_delivery_detail.id_generate=tr_generate_list_unit_delivery.id_generate WHERE print_ke IS NOT NULL)
			ORDER BY tr_sales_order.id_sales_order Desc");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function detail_konfirmasi()
	{
		$id_sales_order = $this->input->post("id_sales_order");
		$dt = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,tr_spk.no_ktp,
			CASE WHEN tr_sales_order.longitude IS NULL THEN tr_spk.longitude ELSE tr_sales_order.longitude END AS longitude,CASE WHEN tr_sales_order.latitude IS NULL THEN tr_spk.latitude ELSE tr_sales_order.latitude END AS latitude,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka FROM tr_sales_order 
			INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
			INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
			INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE tr_sales_order.id_sales_order = '$id_sales_order'
			ORDER BY tr_sales_order.id_sales_order ASC")->row();
		echo $dt->id_sales_order . '|' . $dt->no_mesin . '|' . $dt->nama_konsumen . '|' . $dt->no_rangka . '|' . $dt->alamat . '|' . $dt->tipe_ahm . '|' . $dt->no_ktp . '|' . $dt->warna . '|' . $dt->longitude . '|' . $dt->latitude;
	}
	public function konfirmasi()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "insert";
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}
	public function detail()
	{
		$id = $this->input->get("id");
		$data['dt_sales_order'] = $this->db->query("SELECT tr_spk.*,tr_sales_order.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE id_sales_order = '$id'");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "detail";
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}

	public function detail_gc()
	{
		$id = $this->input->get("id");
		$data['dt_sales_order'] = $this->db->query("SELECT tr_spk.*,tr_sales_order.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
			INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
			INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna
			WHERE id_sales_order = '$id'");
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']		= "detail";
		$data['dt_warna'] = $this->m_admin->getSortCond("ms_warna", "warna", "ASC");
		$this->template($data);
	}

	public function cari_id()
	{

		//$tgl				= $this->input->post('tgl');
		$th 				= date("y");
		$bln 				= date("m");
		$tgl 				= date("d");
		$dealer 			= $this->session->userdata("id_karyawan_dealer");
		$isi 				= $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$dealer'")->row();
		$kode_dealer 		= $isi->kode_dealer_md;
		$pr_num 			= $this->db->query("SELECT * FROM tr_prospek ORDER BY id_prospek DESC LIMIT 0,1");
		if ($pr_num->num_rows() > 0) {
			$row 	= $pr_num->row();
			$pan  = strlen($row->id_prospek) - 11;
			$id 	= substr($row->id_prospek, $pan, 11) + 1;
			if ($id < 10) {
				$kode1 = $th . $bln . $tgl . "0000" . $id;
			} elseif ($id > 9 && $id <= 99) {
				$kode1 = $th . $bln . $tgl . "000" . $id;
			} elseif ($id > 99 && $id <= 999) {
				$kode1 = $th . $bln . $tgl . "00" . $id;
			} elseif ($id > 999) {
				$kode1 = $th . $bln . $tgl . "0" . $id;
			}
			$kode = $kode_dealer . $kode1;
		} else {
			$kode = $kode_dealer . $th . $bln . $tgl . "00001";
		}
		$rt = rand(1111, 9999);
		echo $kode . "|" . $rt;
	}
	public function take_sales()
	{
		$id_karyawan_dealer	= $this->input->post('id_karyawan_dealer');
		$dt_eks				= $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_karyawan_dealer = '$id_karyawan_dealer'");
		if ($dt_eks->num_rows() > 0) {
			$da = $dt_eks->row();
			$kode = $da->id_flp_md;
			$nama = $da->nama_lengkap;
		} else {
			$kode = "";
			$nama = "";
		}
		echo $kode . "|" . $nama;
	}
	public function save_pu()
	{
		$id_penerimaan_unit		= $this->input->post('id_penerimaan_unit');
		$no_shipping_list			= $this->input->post('no_shipping_list');
		$data['id_penerimaan_unit']		= $this->input->post('id_penerimaan_unit');
		$data['no_shipping_list']			= $this->input->post('no_shipping_list');
		$c = $this->db->query("SELECT * FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit = '$id_penerimaan_unit' AND no_shipping_list = '$no_shipping_list'");
		if ($c->num_rows() > 0) {
			echo "no";
		} else {
			$cek2 = $this->m_admin->insert("tr_penerimaan_unit_detail", $data);
			echo "ok";
		}
	}
	public function delete_pu()
	{
		$id = $this->input->post('id_penerimaan_unit_detail');
		$this->db->query("DELETE FROM tr_penerimaan_unit_detail WHERE id_penerimaan_unit_detail = '$id'");
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
			$data['id_penerimaan_unit'] 	= $this->input->post('id_penerimaan_unit');
			$data['no_antrian'] 					= $this->input->post('no_antrian');
			$data['no_surat_jalan'] 			= $this->input->post('no_surat_jalan');
			$data['tgl_surat_jalan'] 			= $this->input->post('tgl_surat_jalan');
			$data['ekspedisi'] 						= $this->input->post('ekspedisi');
			$data['no_polisi'] 						= $this->input->post('no_polisi');
			$data['nama_driver'] 					= $this->input->post('nama_driver');
			$data['no_telp'] 							= $this->input->post('no_telp');
			$data['gudang'] 							= $this->input->post('gudang');
			$data['tgl_penerimaan'] 			= $this->input->post('tgl_penerimaan');
			if ($this->input->post('active') == '1') $data['active'] = $this->input->post('active');
			else $data['active'] 		= "";
			$data['created_at']				= $waktu;
			$data['created_by']				= $login_id;
			$this->m_admin->insert($tabel, $data);
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/penerimaan_unit/add'>";
		} else {
			$_SESSION['pesan'] 	= "Duplicate entry for primary key";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		}
	}
	// public function confirm()
	// {
	// 	$id_sales_order = $this->input->post('id');
	// 	$tgl_terima_unit_ke_konsumen = $this->input->post('tgl_terima_unit_ke_konsumen');
	// 	$this->db->query("UPDATE tr_sales_order set status_cetak ='konsumen' WHERE id_sales_order = '$id_sales_order' tgl_terima_unit_ke_konsumen='$tgl_terima_unit_ke_konsumen'");
	// 		echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/konfirmasi_bast'>";
	// }
	public function confirm()
	{
		$waktu     = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id  = $this->session->userdata('id_user');
		$tabel     = $this->tables;
		$pk        = $this->pk;
		$id_dealer = $this->m_admin->cari_dealer();

		$id_sales_order = $this->input->post('id_sales_order');

		$config['upload_path'] 			= './assets/panel/images/konfirmasi_bast';
		$config['allowed_types'] 		= 'jpg|jpeg|png|pdf|bmp|gif';
		$config['max_size']					= '1000';
		$config2['upload_path'] 		= './assets/panel/images/konfirmasi_bast';
		$config2['allowed_types'] 	= 'jpg|jpeg|png|pdf|bmp|gif';
		$config2['max_size']				= '1000';

		$this->upload->initialize($config);
		if (!$this->upload->do_upload('foto_ktp_penerima')) {
			$foto_ktp_penerima = "";
		} else {
			$foto_ktp_penerima = $this->upload->file_name;
		}
		$this->upload->initialize($config2);
		if (!$this->upload->do_upload('foto_serah_terima')) {
			$foto_serah_terima = "";
		} else {
			$foto_serah_terima = $this->upload->file_name;
		}

		$so = $this->db->query("SELECT so.no_spk,tr_spk.nama_konsumen,tr_spk.id_tipe_kendaraan,so.no_mesin,gludd.id_generate, prospek.leads_id,tr_spk.no_hp,so.tgl_pengiriman,so.no_mesin,prospek.id_prospek,prospek.input_from
					FROM tr_sales_order so
					JOIN tr_spk ON so.no_spk=tr_spk.no_spk
					JOIN tr_prospek prospek ON prospek.id_customer=tr_spk.id_customer
					LEFT JOIN tr_generate_list_unit_delivery_detail gludd ON gludd.no_mesin=so.no_mesin
					WHERE so.id_sales_order='$id_sales_order'
			")->row();

		$tgl_terima = $data['tgl_terima_unit_ke_konsumen'] = $this->input->post('tgl_terima_unit_ke_konsumen');
		// $data['nama_penerima']            = $this->input->post('nama_penerima');		
		// $data['no_hp_penerima']           = $this->input->post('no_hp_penerima');		
		$data['longitude']                   = $this->input->post('longitude');
		$data['latitude']                    = $this->input->post('latitude');
		$data['csl']                         = $this->input->post('csl');
		$data['foto_serah_terima']           = $foto_serah_terima;
		$data['foto_ktp_penerima']           = $foto_ktp_penerima;
		$data['status_cetak']                = "konsumen";
		$data['status_delivery']             = 'delivered';
		$data['status_close']                = 'closed';
		$data['explanation_bast']            = $this->input->post('explanation_bast') != '' ? '1' : '0';

		//Cek Generated Delivery Apakah Sudah Semua
		$cek_all_unit  = $this->db->query("SELECT COUNT(gludd.no_mesin) as count
		FROM tr_generate_list_unit_delivery_detail gludd 
		LEFT JOIN tr_sales_order so ON so.no_mesin=gludd.no_mesin
		WHERE gludd.no_mesin NOT IN('$so->no_mesin') AND gludd.id_generate='$so->id_generate'")->row()->count;

		$cek_delivered  = $this->db->query("SELECT COUNT(gludd.no_mesin) as count
		FROM tr_generate_list_unit_delivery_detail gludd 
		LEFT JOIN tr_sales_order so ON so.no_mesin=gludd.no_mesin
		WHERE gludd.no_mesin NOT IN('$so->no_mesin') AND gludd.id_generate='$so->id_generate' AND so.status_delivery='delivered'")->row()->count;

		if ($cek_all_unit == $cek_delivered) {
			$upd_generate_unit = ['status' => 'delivered'];
		}

		//Service Reminder
		$sett               = $this->db->get_where("ms_h2_kpb_reminder", ['active' => 1]);
		$get_kpb1 = $this->db->get_where("ms_kpb_detail", ['id_tipe_kendaraan' => $so->id_tipe_kendaraan, 'kpb_ke' => 1]);
		if ($get_kpb1->num_rows() > 0 && $sett->num_rows() > 0) {
			$kpb              = $get_kpb1->row();
			$sett = $sett->row();

			$tgl               = tambah_dmy('tanggal', -$sett->sms_kpb1, $tgl_terima);
			$tgl_reminder_sms  = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];

			$tgl               = tambah_dmy('tanggal', -$sett->call_kpb1, $tgl_terima);
			$tgl_reminder_call = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];

			$tgl              = tambah_dmy('tanggal', $kpb->batas_maks_kpb, $tgl_terima);
			$tgl_srv_next     = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];

			$ins_reminder = [
				'id_dealer'              => $id_dealer,
				'tgl_contact_call'       => $tgl_reminder_call,
				'tgl_contact_sms'        => $tgl_reminder_call,
				'tgl_reminder_sms'       => $tgl_reminder_sms,
				'tgl_servis_berikutnya'  => $tgl_srv_next,
				'tipe_servis_berikutnya' => 'ass1',
				'created_at'             => $waktu,
				'created_by'             => $login_id,
				'reminder_from'          => 'bast',
				'no_mesin'               => $so->no_mesin
			];
			$pesan_reminder = 'Service reminder berhasil diproses';
		} else {
			$pesan_reminder = 'Service reminder gagal diproses';
		}

		$this->load->model('mokita_model');
		$last_status_ce_apps = $this->mokita_model->last_tracking($so->no_spk);
		$array_post = [
			'AppsOrderNumber'   => '',
			'DmsOrderNumber'    => '',
			'CustomerPhoneNumber' => $so->no_hp,
			'CreditStatus' => $last_status_ce_apps ? $last_status_ce_apps->CreditStatus : '',
			'IndentStatus' => $last_status_ce_apps ? $last_status_ce_apps->IndentStatus : '',
			'DeliveryStatus' => 'Motor sudah diterima',
			'EstimatedDeliveryDate' => $so->tgl_pengiriman,
			'EngineNumber' => $so->no_mesin,
			'StnkStatus' => '',
			'BpkbStatus' => '',
			'VehicleNumber' => '',
		];
		// send_json($array_post);
		if ((string)$so->input_from == 'sinsengo') {
			$this->db_crm = $this->load->database('db_crm', true);
			$get_leads = $this->db_crm->get_where("leads", ['leads_id' => $so->leads_id])->row();
			$array_post['AppsOrderNumber']   = $get_leads->sourceRefID;
			$array_post['DmsOrderNumber']    = $get_leads->batchID;
			$this->load->library("mokita");
			$this->mokita->h1_credit_approval_indent_delivery_stnk_bpkb($array_post);
		}
		$this->mokita_model->set_tracking($so->no_spk, $array_post);
		// send_json($so);

		/* followup konsumen + perbaikan data*/
		$ins_manage = [
			'no_spk'          => $so->no_spk,
			'created_at'      => $waktu,
			'kategori'        => 'Reminder follow up after sales',
			'status'          => 'Not Started',
			'no_mesin'        => $so->no_mesin,
			'detail_activity' => "Follow UP – Ucapan Terima kasih atas pembelian sepeda motor HONDA & Reminder KPB 1",
			'id_dealer'       => dealer()->id_dealer,
			'created_by'      => $login_id
		];
		$this->db->insert('tr_manage_activity_after_dealing', $ins_manage);

		$this->db->trans_begin();
		if (isset($ins_reminder)) {
			$this->db->insert('tr_h2_service_reminder', $ins_reminder);
		}
		if (isset($upd_generate_unit)) {
			$this->db->update('tr_generate_list_unit_delivery', $upd_generate_unit, ['id_generate' => $so->id_generate]);
		}
		$this->m_admin->update('tr_sales_order', $data, 'id_sales_order', $id_sales_order);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$_SESSION['pesan'] 	= "Something went wrong";
			$_SESSION['tipe'] 	= "danger";
			echo "<script>history.go(-1)</script>";
		} else {
			$this->db->trans_commit();
			$_SESSION['pesan'] 	= "Data berhasil diproses. $pesan_reminder";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/konfirmasi_bast'>";
		}

		// $this->db->insert('tr_h2_service_reminder', $ins_reminder);
		// $this->m_admin->update('tr_sales_order', $data, 'id_sales_order', $id_sales_order);

		// $_SESSION['pesan'] = "Data successfully updated";
		// $_SESSION['tipe']  = "success";
		// echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/konfirmasi_bast'>";

		// }else{
		// 	$_SESSION['pesan'] 	= "Duplicate entry for primary key";
		// 	$_SESSION['tipe'] 	= "danger";
		// 	echo "<script>history.go(-1)</script>";
		// }
	}
	public function cetak_striker()
	{
		$data['isi']    = $this->page;
		$data['title']	= "Cetak Ulang Stiker";
		$no_shipping_list 	= $this->input->get("id");
		$data['set']		= "cetak";
		$data['dt_shipping_list'] = $this->db->query("SELECT * FROM tr_shipping_list INNER JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna 
					WHERE tr_shipping_list.no_shipping_list = '$no_shipping_list'");
		$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");
		$this->template($data);
		//$this->load->view('trans/logistik',$data);
	}
	public function list_ksu()
	{
		$data['isi']    = $this->page;
		$data['title']	= "List KSU";
		$data['set']	= "list_ksu";
		//$data['dt_item'] = $this->db->query("SELECT DISTINCT(no_shipping_list) FROM tr_shipping_list ORDER BY tgl_sl DESC");						
		$this->template($data);
	}
}
