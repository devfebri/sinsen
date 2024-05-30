<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Manage_booking extends CI_Controller
{

	var $tables = "tr_h2_manage_booking";
	var $folder = "dealer";
	var $page   = "manage_booking";
	var $title  = "Manage Booking";

	public function __construct()
	{
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_h2_master', 'm_h2');
		$this->load->model('m_h2_booking', 'm_bk');

		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->library('mokita');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']   = $this->page;
		$data['title'] = $this->title;
		$data['set']	 = "index";
		$id_dealer     = $this->m_admin->cari_dealer();
		$date          = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$filter = [
			// 'status_null' => 'y',
			'status' => 'draft',
			'tgl_servis_lebih_besar' => $date
		];
		$data['booking'] = $this->m_h2->get_booking($filter);
		$this->template($data);
	}
	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$this->template($data);
	}

	public function fetch_history()
	{
		$fetch_data = $this->make_query_history();
		$data       = array();
		$tanggal    = gmdate("Y-m-d", time() + 60 * 60 * 7);

		foreach ($fetch_data->result() as $rs) {
			$status = '';
			if ($rs->status == 'cancel') {
				$status = '<span class="label label-danger">Canceled</span>';
			} elseif ($rs->id_antrian != null) {
				$status = '<span class="label label-info">Visit</span>';
			} elseif ($rs->status_wo == 'open') {
				$status = '<span class="label label-primary">Execute</span>';
			} elseif ($rs->status_wo == 'closed') {
				$status = '<span class="label label-success">Completed</span>';
			} elseif ($rs->tgl_servis < $tanggal) {
				$status = '<span class="label label-danger">Canceled</span>';
			}
			$sub_array   = array();
			$sub_array[] = "<a href=\"" . site_url('dealer/manage_booking/detail?id=' . $rs->id_booking) . "\">$rs->id_booking</a>";
			$sub_array[] = $rs->nama_customer;
			$sub_array[] = $rs->alamat;
			$sub_array[] = $rs->id_tipe_kendaraan;
			$sub_array[] = $rs->id_warna;
			$sub_array[] = $rs->no_polisi;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = date_dmy($rs->tgl_servis);
			$sub_array[] = $rs->jam_servis;
			$sub_array[] = date_dmy($rs->created_at);
			// $sub_array[] = $rs->created_at;
			$sub_array[] = $status;
			$data[]      = $sub_array;
		}

		$output = array(
			"draw"            => intval($_POST["draw"]),
			"recordsFiltered" => $this->get_filtered_data_history(),
			"data"            => $data
		);
		echo json_encode($output);
	}

	public function make_query_history($no_limit = null)
	{
		$date   = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$start  = $this->input->post('start');
		$length = $this->input->post('length');
		$limit  = "LIMIT $start, $length";

		if ($no_limit == 'y') $limit = '';

		$filter = [
			'search' => $this->input->post('search')['value'],
			'limit' => $limit,
			'order' => isset($_POST['order']) ? $_POST["order"] : '',
			'status_booking' => ["'cancel'"],
			'id_sa_form_not_null' => 1,
			'tgl_servis_lebih_kecil' => $date
		];
		return $this->m_bk->fetch_booking($filter);
	}

	function get_filtered_data_history()
	{
		return $this->make_query_history('y')->num_rows();
	}

	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->db->order_by('nama_jasa', 'ASC');
		$data['jasa'] = $this->db->get('ms_jasa_servis');
		$this->template($data);
	}
	public function ubah_jadwal()
	{
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['mode']      = 'ubah_jadwal';
		$data['set']       = "form";
		$id_dealer = $this->m_admin->cari_dealer();
		$this->db->order_by('nama_jasa', 'ASC');
		$data['jasa'] = $this->db->get('ms_jasa_servis');
		$id_booking = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM tr_h2_manage_booking WHERE id_booking='$id_booking' AND id_dealer='$id_dealer'");
		if ($row->num_rows() > 0) {
			$data['row'] = $row->row();
			$filter           = ['id_customer' => $data['row']->id_customer];
			$data['customer'] = $this->m_h2->getCustomer23($filter)->row();
			// echo json_encode($data);
			// die();
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		}
	}

	public function detail()
	{
		$data['isi']       = $this->page;
		$data['title']     = $this->title;
		$data['mode']      = 'detail';
		$data['set']       = "form";
		$id_dealer = $this->m_admin->cari_dealer();
		$this->db->order_by('nama_jasa', 'ASC');
		$data['jasa'] = $this->db->get('ms_jasa_servis');
		$id_booking = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM tr_h2_manage_booking WHERE id_booking='$id_booking' AND id_dealer='$id_dealer'");
		if ($row->num_rows() > 0) {
			$data['row']      = $row->row();
			$filter           = ['id_customer' => $data['row']->id_customer];
			$cs = $data['customer'] = $this->m_h2->getCustomer23($filter)->row();
			$data['alamat_sama'] = $cs->alamat == $cs->alamat_identitas ? 'sama' : '';
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		}
	}

	public function cekKonsumen()
	{
		$nama_konsumen = $this->input->post('nama_konsumen');
		$no_polisi     = $this->input->post('no_polisi');
		$no_hp         = $this->input->post('no_hp');
		// $id_dealer = $this->m_admin->cari_dealer();

		// Cari Customer H23
		$cari_nama_konsumen = $nama_konsumen != '' ? "AND nama_customer LIKE '%$nama_konsumen%' " : '';
		$cari_no_hp         = $no_hp != '' ? " AND no_hp LIKE '%$no_hp%' " : '';
		$cari_no_polisi     = $no_polisi != '' ? " AND no_polisi LIKE '%$no_polisi%' " : '';
		$customer = $this->db->query("SELECT no_spk,no_hp,nama_customer AS nama_konsumen,alamat,no_polisi, CONCAT(ms_customer_h23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(ms_customer_h23.id_warna,' | ',warna) AS warna,ms_customer_h23.no_mesin,no_rangka,id_cdb,id_customer
			FROM ms_customer_h23 
			JOIN ms_tipe_kendaraan AS tk ON ms_customer_h23.id_tipe_kendaraan=tk.id_tipe_kendaraan
			JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
			WHERE no_polisi IS NOT NULL $cari_nama_konsumen $cari_no_hp $cari_no_polisi");
		if ($customer->num_rows() > 0) {
			$response = ['row' => $customer->num_rows(), 'data' => $customer->result()];
		} else {
			$cari_nama_konsumen = $nama_konsumen != '' ? "AND nama_konsumen LIKE '%$nama_konsumen%' " : '';
			$cari_no_hp = $no_hp != '' ? " AND no_hp LIKE '%$no_hp%' " : '';
			$cari_no_polisi = $no_polisi != '' ? " AND (SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1)='$no_polisi' " : '';
			$cek = $this->db->query("SELECT tr_spk.no_spk,tr_spk.no_hp,nama_konsumen,alamat,(SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1) AS no_polisi,warna,CONCAT(tr_spk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(tr_spk.id_warna,' | ',warna) AS warna,tr_sales_order.no_mesin,(SELECT no_rangka FROM tr_scan_barcode WHERE no_mesin=tr_sales_order.no_mesin) AS no_rangka, tr_cdb.id_cdb,'' AS id_customer
				FROM tr_cdb
				JOIN tr_spk ON tr_cdb.no_spk=tr_spk.no_spk
				JOIN tr_sales_order ON tr_sales_order.no_spk=tr_spk.no_spk
				JOIN ms_tipe_kendaraan AS tk ON tr_spk.id_tipe_kendaraan=tk.id_tipe_kendaraan
				JOIN ms_warna ON tr_spk.id_warna=ms_warna.id_warna
				WHERE (SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1) IS NOT NULL
				$cari_nama_konsumen
				$cari_no_hp
				$cari_no_polisi
				LIMIT 1
				");
			if ($cek->num_rows() > 0) {
				$response = ['row' => $cek->num_rows(), 'data' => $cek->result()];
			} else {
				$response = ['row' => $cek->num_rows];
			}
		}
		echo json_encode($response);
	}
	function getBooking()
	{
		$id_booking = $this->input->post('id_booking');
		$booking = $this->db->query("SELECT tr_h2_manage_booking.*,ms_customer_h23.*, nama_customer AS nama_konsumen,CONCAT(ms_customer_h23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(ms_customer_h23.id_warna,' | ',warna) AS warna
						FROM tr_h2_manage_booking 	
								JOIN ms_customer_h23 ON tr_h2_manage_booking.id_customer=ms_customer_h23.id_customer
								JOIN ms_tipe_kendaraan ON ms_customer_h23.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
								JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
								WHERE id_booking='$id_booking'
			");
		$result = [
			'row' => $booking->num_rows(),
			'data' => $booking->row()
		];
		echo json_encode($result);
	}
	public function save()
	{
		$waktu         = waktu_full();
		$tgl           = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$login_id      = $this->session->userdata('id_user');
		$id_dealer     = $this->m_admin->cari_dealer();
		$id_booking    = $this->m_h2->get_id_booking();
		$customer_from = $this->input->post('customer_from');
		$customer      = $this->input->post('customer');
		$edit_cust     = $this->input->post('edit_cust');

		$book = [
			'id_booking'    => $id_booking,
			'id_dealer'     => $id_dealer,
			'tgl_servis'    => $this->input->post('tgl_servis'),
			'jam_servis'    => $this->input->post('jam_servis'),
			// 'nama_pembawa'  => $this->input->post('nama_pembawa'),
			'keluhan'       => $this->input->post('keluhan'),
			'customer_from' => $customer_from,
			'id_pit'        => $this->input->post('id_pit'),
			'id_type'       => $this->input->post('id_type'),
			'created_at'    => $waktu,
			'created_by'    => $login_id,
			'status'        => 'draft',
		];
		if ($customer_from == 'h23') {
			$id_customer = $book['id_customer'] = $customer['id_customer'];
			if ($edit_cust == 1) {
				$upd_cust = update_customer($customer);
				$upd_cust['id_dealer']  = $id_dealer;
				$upd_cust['updated_at'] = $waktu;
				$upd_cust['updated_by'] = $login_id;
			}
		} elseif ($customer_from == 'h1' || $customer_from == 'baru') {

			// $id_customer = $book['id_customer'] = $this->m_h2->get_id_customer();
			// $ins_cust = insert_customer($customer);
			$id_tipe_kendaraan = $customer['id_tipe_kendaraan'];
			
			//Cek apakah customer EV atau tidak
			$tipe_motor = $this->db->query("SELECT id_kategori from ms_tipe_kendaraan mtk where id_tipe_kendaraan='$id_tipe_kendaraan'")->row_array();
			if($tipe_motor['id_kategori'] == 'EV'){
				$id_customer = $book['id_customer'] = $this->m_h2->get_id_customer_ev();
				$is_ev = 1;
			}else{
				$id_customer = $book['id_customer'] = $this->m_h2->get_id_customer();
				$is_ev = null;
			}
			$ins_cust = insert_customer($customer);
			$ins_cust['is_ev']  = $is_ev;
			$ins_cust['id_customer'] = $id_customer;
			$ins_cust['id_dealer']   = $id_dealer;
			$ins_cust['created_at']  = $waktu;
			$ins_cust['created_by']  = $login_id;
		}
		// $test = [
		// 	'book'     => $book,
		// 	'ins_cust' => isset($ins_cust) ? $ins_cust : '',
		// 	'upd_cust' => isset($upd_cust) ? $upd_cust : '',
		// 	'customer' => $customer
		// ];
		// send_json($test);
		$this->db->trans_begin();
		$this->db->insert('tr_h2_manage_booking', $book);
		if (isset($ins_cust)) {
			$this->db->insert('ms_customer_h23', $ins_cust);
		}
		if (isset($upd_cust)) {
			$this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $id_customer]);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/manage_booking')
			];
			$_SESSION['pesan'] 	= "Data has been saved successfully";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function save_jadwal()
	{
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');
		$id_dealer  = $this->m_admin->cari_dealer();

		$id_booking     = $this->input->post('id_booking');
		$cek_book = $this->db->get_where("tr_h2_manage_booking", ['id_booking' => $id_booking]);
		if ($cek_book->num_rows() > 0) {
			$upd_data = [
				'tgl_servis' => $this->input->post('tgl_servis'),
				'jam_servis' => $this->input->post('jam_servis'),
				'id_pit'     => $this->input->post('id_pit'),
				'updated_at' => $waktu,
				'updated_by' => $login_id
			];
		} else {
			$rsp = [
				'status' => 'error',
				'pesan' => 'Error. Wait Upd !'
			];
			exit;
		}
		// echo json_encode($upd_data);
		// die();
		$this->db->trans_begin();
		$this->db->update('tr_h2_manage_booking', $upd_data, ['id_booking' => $id_booking]);
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/manage_booking')
			];
			$_SESSION['pesan'] 	= "Jadwal berhasil diubah";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function cancel_booking()
	{
		$waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id      = $this->session->userdata('id_user');
		$tabel         = $this->tables;
		$id_booking    = $this->input->get('id');
		$alasan_cancel = $this->input->get('alasan_cancel');
		$cek           = $this->db->get_where('tr_h2_manage_booking', ['id_booking' => $id_booking]);
		if ($cek->num_rows() == 1) {
			$data['status']        = "cancel";
			$data['alasan_cancel'] = $alasan_cancel;
			$data['cancel_at']     = $waktu;
			$data['cancel_by']     = $login_id;
			$this->db->update('tr_h2_manage_booking', $data, ['id_booking' => $id_booking]);
			$_SESSION['pesan'] 	= "Data has been cancelled successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		}
	}

	function getBookingGrid()
	{
		$mode      = $this->input->post('mode');
		$set       = $this->db->get('ms_h2_setting_jadwal')->row();
		$id_dealer = $this->m_admin->cari_dealer();
		if ($mode == '') {
			$mode = 'view';
		}

		//Setting Batasan Waktu
		$begin    = new DateTime($set->waktu_mulai);
		date_add($begin, date_interval_create_from_date_string("-$set->selisih_waktu min"));
		$end      = new DateTime($set->waktu_selesai);
		$interval = DateInterval::createFromDateString("$set->selisih_waktu min");

		$times    = new DatePeriod($begin, $interval, $end);
		$date     = new DateTime();
		// $date->modify('-1 days')->format('Y-m-d');
		for ($days = $set->jumlah_hari; $days--;) {
			$hr     = $date->modify('+1 days')->format('Y-m-d');
			$hari[] = date('d-m-Y', strtotime($hr));
		}

		// Get PIT
		$pit = $this->db->get_where('ms_h2_pit', ['id_dealer' => $id_dealer, 'booking' => 1]);
		// Looping Waktu
		foreach ($times as $time) {
			$wkt     = $time->add($interval)->format('H:i');
			$waktu[] = $wkt;
			//Looping Hari
			$arrBook = [];
			$arrBook[] = ['id_type' => $wkt, 'color' => '#76839a', 'btn' => null];
			foreach ($hari as $hr) {
				$hr = date('Y-m-d', strtotime($hr));
				// Looping PIT
				foreach ($pit->result() as $pt) {
					$cek_book = $this->db->query("SELECT mb.id_type,color
			  		FROM tr_h2_manage_booking AS mb
			  		JOIN ms_h2_jasa_type AS jt ON jt.id_type=mb.id_type
			  		WHERE mb.id_pit='$pt->id_pit' AND mb.tgl_servis='$hr' AND mb.jam_servis LIKE'$wkt%' AND status<>'cancel' AND mb.id_dealer='$id_dealer'
			  		");
					$color        = 'white';
					$id_type      = null;
					$chooseButton = null;
					if ($cek_book->num_rows() > 0) {
						$cb      = $cek_book->row();
						$color   = $cb->color;
						$id_type = $cb->id_type;
					}
					if (($mode == 'insert' || $mode == 'ubah_jadwal') && $id_type == null) {
						$json_dt = [
							'id_pit'     => $pt->id_pit,
							'jenis_pit'  => $pt->jenis_pit,
							'tgl_servis' => $hr,
							'jam_servis' => $wkt
						];
						// $color = "#a6adad";
						// $chooseButton        = '<button data-dismiss=\'modal\' onClick=\'return pilihSlot(' . json_encode($json_dt) . ')\' class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>';
						$chooseButton        = $json_dt;
					}
					$arrBook[] = ['id_type' => $id_type, 'color' => $color, 'btn' => $chooseButton];
				}
			}
			$booking[] = $arrBook;
		}
		$result = ['hari' => $hari, 'data' => $booking, 'dt_pit' => $pit->result(), 'pit' => $pit->num_rows(), 'mode' => $mode];
		echo json_encode($result);
	}

	function get_select_pit()
	{
		$id_dealer  = $this->m_admin->cari_dealer();
		$where = "WHERE ms_h2_pit.id_dealer='$id_dealer' AND active=1";
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
			$where .= "AND (jenis_pit LIKE '%$search%' OR id_pit LIKE '%$search%')";
		}
		$rs = $this->db->query("SELECT id_pit AS id,CONCAT(id_pit,' | ',jenis_pit) AS text 
				FROM ms_h2_pit $where
			  ");
		echo json_encode($rs->result());
	}

	public function consolidated_ce()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$id_booking = $this->input->get('id');
		$row = $this->db->query("SELECT * FROM tr_h2_manage_booking WHERE id_booking='$id_booking' AND id_dealer='$id_dealer'");
		if ($row->num_rows() > 0) {
			$row = $row->row();
			$id_dealer = $this->m_admin->cari_dealer();
			$filter    = ['id_customer' => $row->id_customer];
			$customer  = $this->m_h2->getCustomer23($filter)->row();
			$dealer = $this->db->get_where("ms_dealer", ['id_dealer' => $id_dealer])->row();

			$array_post = [
				"UserPhoneNumber"   => $customer->no_hp,
				"EngineNumber"      => $customer->no_mesin,
				"BookingDate"       => $row->tgl_servis,
				"BookingTime"       => $row->jam_servis,
				"AhassAhmCode"      => $dealer->kode_dealer_ahm,
				"Notes"             => $row->keluhan,
				"VehicleNumber"     => str_replace(' ', '', $customer->no_polisi),
				"ServiceType"       => $row->customer_apps_service_type == null ? 'bengkel' : $row->customer_apps_service_type,
				"DmsBookingNumber"  => $row->id_booking
			];
			// send_json($array_post);
			$response = json_decode($this->mokita->h2_external($array_post));

			if ($response->status == 1) {
				$update = [
					'customer_apps_booking_number' => $response->data->AppsBookingNumber
				];
				$this->db->update("tr_h2_manage_booking", $update, ['id_booking' => $id_booking, 'id_dealer' => $id_dealer]);
				$_SESSION['pesan'] 	= "Proses Consolidated Data Booking DMS ke CE Apps Berhasil";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
			} else {
				$_SESSION['pesan'] 	= "Proses Consolidated Data Booking DMS ke CE Apps Gagal. <strong><i>($response->message)</i></strong>";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
			}
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/manage_booking'>";
		}
	}
}
