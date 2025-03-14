<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sa_form extends CI_Controller
{

	var $tables = "tr_h2_sa_form";
	var $folder = "dealer";
	var $page   = "sa_form";
	var $title  = "SA Form";

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
		$this->load->model('m_h2_api', 'm_api');
		$this->load->model('m_h2_work_order', 'm_wo');
		$this->load->model('notifikasi_model', 'notifikasi');
		$this->load->model('h3_dealer_request_document_model', 'm_req');
		$this->load->model('m_sm_master', 'm_sm');


		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
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
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['set']     = "index";

		// $filter['id_sa_form_not_null'] = 'y';
		// $data['sa_form'] = $this->m_wo->get_sa_form($filter);
		$this->template($data);
	}
	public function history()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['set']     = "history_tes";
		$this->template($data);
	}
	public function history_tes()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['set']     = "history_tes";
		$this->template($data);
	}

	public function add()
	{
		$data['isi']                   = $this->page;
		$data['title']                 = $this->title;
		$data['mode']                  = 'insert';
		$data['set']                   = "form";
		$data['pkp']                   = dealer()->pkp;
		$data['estimasi_waktu_daftar'] = gmdate("y-m-d H: i: s", time() + 60 * 60 * 7);
		$data['activity_promotion'] = $this->m_sm->getActivityPromotion()->result();
		$data['activity_cap'] = $this->m_sm->getActivityCapacity()->result();
		$data['options_frt'] = [];
			for ($i = 0; $i <= 8; $i += 0.1) {
				$data['options_frt'][] = number_format($i, 1);
		}
		$this->template($data);
	}

	function get_select_antrian()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$where     = "WHERE sa_form.id_dealer = '$id_dealer' AND sa_form.tgl_servis='$tanggal'";
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
			$where  = " AND id_antrian LIKE '%$search%'";
		}
		$rs = $this->db->query("SELECT id_antrian AS id,
			CONCAT(id_antrian,' | ',nama_customer) AS text 
			FROM tr_h2_sa_form AS sa_form
			JOIN ms_customer_h23 ON ms_customer_h23.id_customer=sa_form.id_customer
			$where AND id_sa_form IS NULL
		");

		echo json_encode($rs->result());
	}

	function getSaForm()
	{
		$filter['id_antrian'] = $this->db->escape_str($this->input->post('id_antrian'));
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$sa       = $sa_form->row();
			$filter   = ['id_customer' => $sa->id_customer];
			$customer = $this->m_api->getCustomerH23($filter)->row();
			if ($sa->id_pembawa != '') {
				$filter = ['id_pembawa' => $sa->id_pembawa];
				$pembawa  = $this->m_api->getPembawa($filter)->row();
			} else {
				$pembawa = $customer;
			}
		} else {
			// $result = ['status' => 'error', 'pesan' => 'Data tidak ditemukan !'];
			// echo json_encode($result);
			// die();
		}
		$cek_srbu = 0;
		if ($customer != NULL) {
			$cek_srbu = $this->m_h2->cekSRBU($customer->no_mesin);
		} else {
			$customer = [
				'nama_customer' => ''
			];
		}
		$result = [
			'status'   => 'sukses',
			'customer' => $customer,
			'sa'       => $sa,
			'pembawa'       => $pembawa,
			'srbu'     => $cek_srbu
		];
		echo json_encode($result);
	}

	public function detail()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'SA Form';
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$id_sa_form    = $this->db->escape_str($this->input->get('id'));

		$filter['id_sa_form'] = $id_sa_form;
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$row                 = $data['row'] = $sa_form->row();
			$data['tipe_coming'] = explode(',', $row->tipe_coming);
			$data['pkp'] = $row->pkp;

			$filter['id_sa_form']          = $id_sa_form;
			$data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
			$data['details']               = $this->m_wo->sa_form_detail($filter);
			$data['activity_promotion']    = $this->m_sm->getActivityPromotion()->result();
			$data['activity_cap']          = $this->m_sm->getActivityCapacity()->result();
			$data['options_frt'] = [];
			for ($i = 0; $i <= 8; $i += 0.1) {
				$data['options_frt'][] = number_format($i, 1);
			}
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sa_form'>";
		}
	}

	function save()
	{
		$waktu           = waktu_full();
		$tgl             = date("Y-m-d");
		$login_id        = $this->session->userdata('id_user');
		$id_dealer       = $this->m_admin->cari_dealer();
		$id_sa_form      = $this->m_wo->get_id_sa_form();
		$id_antrian      = $this->input->post('id_antrian');
		$tipe_coming     = $this->input->post('tipe_coming');
		$tipe_coming_imp = implode(',', $tipe_coming);
		$filter          = ['id_antrian'  => $id_antrian];
		$no_mesin        = $this->m_wo->get_sa_form($filter)->row()->no_mesin;
		$cek_srbu        = $this->m_h2->cekSRBU($no_mesin);
		$job_return = $this->input->post('job_return');

		$motor_ditinggal = 1;
		if ($this->input->post('motor_ditinggal') == 'tidak' || $this->input->post('motor_ditinggal') == 0) {
			$motor_ditinggal = 0;
		}

		if ($job_return == 'tidak' || $job_return == 0) {
			$job_return = 0;
		} else {
			$job_return = 1;
		}

		// Cek Asal Unit Entry
		$fc = ['name' => $this->input->post('asal_unit_entry')];
		$act_promo = $this->m_sm->getActivityPromotion($fc)->row();

		$fc = ['id' => $this->input->post('activity_capacity_id')];
		$act_cap = $this->m_sm->getActivityCapacity($fc)->row();

		$asal_unit_entry = $act_promo != NULL ? $act_promo->name : NULL;

		$data 		= [
			'id_sa_form'                    => $id_sa_form,
			'informasi_bensin'              => $this->input->post('informasi_bensin'),
			'km_terakhir'                   => $this->input->post('km_terakhir'),
			'soc'              				=> $this->input->post('soc'),
			'serial_number_battery'         => $this->input->post('serial_number_battery'),
			'alasan_ke_ahass'               => $this->input->post('alasan_ke_ahass'),
			'rekomendasi_sa'                => $this->input->post('rekomendasi_sa'),
			'keluhan_konsumen'              => $this->input->post('keluhan_konsumen'),
			'asal_unit_entry'               => $asal_unit_entry,
			'id_pit'                        => $this->input->post('id_pit'),
			'id_karyawan_dealer'            => $this->input->post('id_karyawan_dealer'),
			'id_wo_job_return'              => $this->input->post('id_wo_job_return'),
			'tipe_pembayaran'               => $this->input->post('tipe_pembayaran'),
			'nama_pemakai'                  => $this->input->post('nama_pemakai'),
			'no_buku_claim_c2'              => $this->input->post('no_buku_claim_c2'),
			'konfirmasi_pekerjaan_tambahan' => $this->input->post('konfirmasi_pekerjaan_tambahan'),
			'catatan_tambahan'              => $this->input->post('catatan_tambahan'),
			// 'no_claim_c2'                => $this->input->post('no_claim_c2'),
			'motor_ditinggal'               => $motor_ditinggal,
			'pkp'                           => dealer()->pkp,
			'job_return'                    => $job_return,
			'tipe_coming'                   => $tipe_coming_imp,
			'created_sa_form_at'            => $waktu,
			'created_sa_form_by'            => $login_id,
			'status_form'                   => 'open',
			'status_monitor'                => 'menunggu_masuk_pit',
			'srbu'                          => $cek_srbu,
			'activity_promotion_id' 				=> $act_promo != NULL ? $act_promo->id : NULL,
			'activity_capacity_id' 					=> $act_cap != NULL ? $act_cap->id : NULL
		];

		if (isset($_POST['details'])) {
			$details = $this->input->post('details');
			foreach ($details as $keys => $val) {
				if ($val['id_type'] == 'C2') {
					$get_id_claim_c2 = true;
				}
				$need_parts = 0;
				if ($val['need_parts'] == 'yes' || $val['need_parts'] == 'y') {
					$need_parts = 1;
				}
				$frt_claim = 0;
				if ($val['frt_claim'] == 'yes') {
					$frt_claim = 1;
				}
				$ins_details[$keys] = [
					'id_sa_form' => $id_sa_form,
					'harga'      => $val['harga'],
					'waktu'      => $val['waktu'],
					'tipe_motor' => isset($val['tipe_motor']) ? $val['tipe_motor'] : null,
					'id_jasa'    => $val['jasa'],
					'id_promo'   => $val['id_promo'] != '' ? $val['id_promo'] : null,
					'pekerjaan_luar' => $val['pekerjaan_luar'],
					'id_tipe_servis' => $val['id_tipe_servis'],
					'need_parts' => $need_parts,
					'frt_claim' => $val['frt_claim'] == '' ? 0 : $val['frt_claim'],
					'labour_cost' => $val['labour_cost'],
				];
				if (strtolower($val['need_parts'] == 'yes') || strtolower($val['need_parts'] == 'y') || strtolower($val['need_parts'] == 1)) {
					foreach ($val['parts'] as $ky => $prt) {
						$part = $this->db->get_where('ms_part', ['id_part' => $prt['id_part']])->row();
						$harga = $part->harga_dealer_user;
						if ($val['id_type'] == 'C1' || $val['id_type'] == 'C2') {
							$harga = round($part->harga_dealer_user / 1.1);
						}
						$ins_parts[] = [
							'id_sa_form'   => $id_sa_form,
							'id_jasa'      => $val['jasa'],
							'id_part'      => $prt['id_part'],
							'qty'          => $prt['qty'],
							'harga'        => $harga,
							'id_gudang'    => isset($prt['id_gudang']) ? $prt['id_gudang']      : '',
							'id_rak'       => isset($prt['id_rak']) ? $prt['id_rak']            : '',
							'tipe_diskon'  => isset($prt['tipe_diskon']) ? $prt['tipe_diskon']  : null,
							'diskon_value' => isset($prt['diskon_value']) ? (int)$prt['diskon_value'] : 0,
							'id_promo'     => isset($prt['id_promo']) ? $prt['id_promo']        : null,
							'jenis_order'  => $prt['jenis_order'],
							'order_to'     => $prt['order_to'] == '' ? 0 : $prt['order_to'],
							'part_utama'     => $prt['part_utama'],
						];
					}
					if (isset($val['parts_demand'])) {
						foreach ($val['parts_demand'] as $ky => $prt) {
							$part = $this->db->get_where('ms_part', ['id_part' => $prt['id_part']])->row();
							$ins_parts_demand[] = [
								'id_sa_form'  => $id_sa_form,
								'id_part'       => $prt['id_part'],
								'id_dealer'     => $id_dealer,
								'search_result' => $prt['nama_part'] . ', ' . $prt['id_part'],
								'qty'           => $prt['qty'],
								'note_field'    => $prt['alasan'],
								'harga_satuan'  => $part->harga_dealer_user,
								'search_field' => '',
								'sisa_stock' => 0
							];
						}
					}
				}
			}
		} else {
			$result = ['status' => 'error', 'pesan' => 'Pekerjaan masih kosong !'];
			echo json_encode($result);
			die();
		}

		//Update Customer
		$edit_cust = $this->input->post('edit_cust');
		$customer = $this->input->post('customer');
		if ($edit_cust == 1) {
			$upd_cust = update_customer($customer);
			$upd_cust['id_dealer']  = $id_dealer;
			$upd_cust['updated_at'] = $waktu;
			$upd_cust['updated_by'] = $login_id;
		}

		if (in_array('milik', $tipe_coming) == false) {
			//Data Pembawa
			$pembawa = $this->input->post('pembawa');
			if (empty($pembawa['id_pembawa'])) {
				$id_pembawa = $this->m_h2->get_id_pembawa();
				$ins_pembawa = [
					'id_pembawa'              => $id_pembawa,
					'id_customer'             => $customer['id_customer'],
					'nama'                    => isset($pembawa['nama_pembawa']) ? $pembawa['nama_pembawa'] : null,
					'no_hp'                   => isset($pembawa['no_hp']) ? $pembawa['no_hp'] : null,
					'email'                   => isset($pembawa['email']) ? $pembawa['email'] : null,
					'alamat_saat_ini'         => isset($pembawa['alamat_saat_ini']) ? $pembawa['alamat_saat_ini'] : null,
					'jenis_kelamin'           => isset($pembawa['jenis_kelamin']) ? $pembawa['jenis_kelamin'] : null,
					'id_agama'                => isset($pembawa['id_agama']) ? $pembawa['id_agama'] : null,
					'id_kelurahan'            => isset($pembawa['id_kelurahan']) ? $pembawa['id_kelurahan'] : null,
					'jenis_identitas'         => isset($pembawa['jenis_identitas']) ? $pembawa['jenis_identitas'] : null,
					'no_identitas'            => isset($pembawa['no_identitas']) ? $pembawa['no_identitas'] : null,
					'alamat_identitas'        => isset($pembawa['alamat_identitas']) ? $pembawa['alamat_identitas'] : null,
					'id_kelurahan_identitas'  => isset($pembawa['id_kelurahan_identitas']) ? $pembawa['id_kelurahan_identitas'] : null,
					'hubungan_dengan_pemilik' => isset($pembawa['hubungan_dengan_pemilik']) ? $pembawa['hubungan_dengan_pemilik'] : null,
					'aktif'                   => 1,
					'created_at'              => $waktu,
					'created_by'              => $login_id,
				];
			} else {
				$id_pembawa = $pembawa['id_pembawa'];
			}
			$data['id_pembawa'] = $id_pembawa;
		}

		//Cek Apakah Claim C2
		if (isset($get_id_claim_c2)) {
			$data['no_claim_c2'] = $this->m_wo->get_id_claim_c2();
		}

		//Set ID Customer INT
		// $cust = $this->db->query("SELECT id_customer_int FROM ms_customer_h23 ch23 WHERE id_customer='{$customer['id_customer']}' ")->row;
		// $data['id_customer_int'] = $cust->id_customer_int;

		$tes = [
			'data'        => $data,
			'ins_details' => isset($ins_details) ? $ins_details : null,
			'ins_parts'   => isset($ins_parts) ? $ins_parts    : null,
			'ins_parts_demand'   => isset($ins_parts_demand) ? $ins_parts_demand    : null,
			'ins_pembawa' => isset($ins_pembawa) ? $ins_pembawa : null,
			'upd_cust'    => isset($upd_cust) ? $upd_cust      : null,
			'customer'    => isset($customer) ? $customer      : null,
		];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->update('tr_h2_sa_form', $data, ['id_antrian' => $id_antrian]);
		if (isset($ins_parts)) {
			$this->db->insert_batch('tr_h2_sa_form_parts', $ins_parts);
		}
		if (isset($ins_parts_demand)) {
			$this->db->insert_batch('tr_h3_dealer_record_reasons_and_parts_demand', $ins_parts_demand);
		}
		if (isset($ins_details)) {
			$this->db->insert_batch('tr_h2_sa_form_pekerjaan', $ins_details);
		}
		if (isset($upd_cust)) {
			$this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $customer['id_customer']]);
		}
		if (isset($ins_pembawa)) {
			$this->db->insert('ms_h2_pembawa', $ins_pembawa);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$cust = $this->db->get_where('ms_customer_h23', ['id_customer' => $customer['id_customer']])->row();
			if ($cust != NULL) {
				$upd_sa = ['id_customer_int' => $cust->id_customer_int];
				$this->db->update('tr_h2_sa_form', $upd_sa, ['id_antrian' => $id_antrian]);
			}
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/sa_form')
			];
			$_SESSION['pesan'] 	= "Data SA Form selesai diproses";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	public function cetak()
	{
		$params = [
			'id_sa_form' => $this->input->get('id'),
			'id_dealer' => $this->m_admin->cari_dealer(),
			// 'save_server' => true
		];
		$this->m_wo->cetak_sa_form($params);
	}

	function cekKPB()
	{
		$post = $this->input->post();
		if (!isset($post['id_tipe_kendaraan'])) {
			$result = ['status' => 'error', 'pesan' => 'Silahkan tentukan data customer terlebih dahulu !'];
			send_json($result);
		}
		$params = [
			'kpb_ke' => $post['kpb_ke'],
			'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
			'no_mesin' => $post['no_mesin'],
			'tgl_pembelian' => $post['tgl_pembelian'],
			'km_terakhir' => $post['km_terakhir'],
		];
		$result = $this->m_h2->cekKPB($params);
		send_json($result);
	}

	public function cancel_sa()
	{
		$waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id      = $this->session->userdata('id_user');
		$id_sa_form    = $this->input->get('id');
		$alasan_cancel = $this->input->get('alasan_cancel');
		$cek           = $this->db->get_where('tr_h2_sa_form', ['id_sa_form' => $id_sa_form, 'status_form' => 'open']);
		if ($cek->num_rows() == 1) {
			$data['status_form']   = "cancel";
			$data['alasan_cancel'] = $alasan_cancel;
			$data['cancel_at']     = $waktu;
			$data['cancel_by']     = $login_id;
			$this->db->update('tr_h2_sa_form', $data, ['id_sa_form' => $id_sa_form]);
			$_SESSION['pesan'] 	= "Data has been cancelled successfully";
			$_SESSION['tipe'] 	= "success";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sa_form'>";
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sa_form'>";
		}
	}

	function get_sel_wo_job_return()
	{
		$id_dealer   = $this->m_admin->cari_dealer();
		$id_customer = $this->input->post('id_customer');
		$where       = "WHERE wo.id_dealer = '$id_dealer' AND sa_form.id_customer = '$id_customer' AND wo.status='closed'";
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
			$where  = " AND id_work_order LIKE '%$search%'";
		}
		$rs = $this->db->query("SELECT id_work_order AS id,
			CONCAT(id_work_order,' | ',nama_customer) AS text 
			FROM tr_h2_wo_dealer AS wo
			JOIN tr_h2_sa_form AS sa_form ON sa_form.id_sa_form=wo.id_sa_form
			JOIN ms_customer_h23 ON ms_customer_h23.id_customer=sa_form.id_customer
			$where
		");

		echo json_encode($rs->result());
	}

	// public function send_notif()
	// {
	// 	$data['isi']   = $this->page;
	// 	$data['title'] = 'Notify Parts Counter For Required Parts';
	// 	$data['mode']  = 'send';
	// 	$data['set']   = "send_notif";
	// 	$id_sa_form    = $this->input->get('id');

	// 	$filter['id_sa_form'] = $id_sa_form;
	// 	$sa_form = $this->m_wo->get_sa_form($filter);
	// 	if ($sa_form->num_rows() > 0) {
	// 		$data['row']           = $sa_form->row();
	// 		$filter['jenis_order'] = 'HLO';
	// 		$filter['send_notif']  = 0;
	// 		$filter['group_by_order_to'] = true;
	// 		$order_to = $this->m_h2->getSAParts($filter)->result();
	// 		$id_parts = [];
	// 		foreach ($order_to as  $ord) {
	// 			$filter['group_by_order_to'] = false;
	// 			$filter['order_to'] = $ord->order_to;
	// 			$parts = $this->m_h2->getSAParts($filter)->result();
	// 			$parts_order[] = [
	// 				'order_to' => $ord->order_to,
	// 				'order_to_name' => $ord->order_to_name,
	// 				'parts' => $parts
	// 			];
	// 			foreach ($parts as $prt) {
	// 				$id_parts[] = $prt->id_part;
	// 			}
	// 		}
	// 		$data['id_parts'] = $id_parts;
	// 		$data['parts_order'] = $parts_order;
	// 		// send_json($data);
	// 		// $data['id_parts'][] = '0005ZKWWA00';
	// 		$this->template($data);
	// 	} else {
	// 		$_SESSION['pesan'] 	= "Data not found !";
	// 		$_SESSION['tipe'] 	= "danger";
	// 		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sa_form'>";
	// 	}
	// }

	function save_send_notif()
	{
		$post = $this->input->post();
		// send_json($post);
		$id_booking = $this->m_req->generateIdBooking();
		foreach ($post['parts_order'] as $key => $val) {
			if ($key > 0) {
				$id_booking_expl = explode('-', $id_booking);
				$id_booking = $id_booking_expl[0] . '-' . sprintf("%'.03d", $id_booking_expl[1] + 1);
			}
			$check_id_customer_int = $this->db->select('id_customer_int')
												->from('ms_customer_h23')
												->where('id_customer', $post['id_customer'])
												->get()->row_array();

			$request_document[] = [
				'id_booking'              => $id_booking,
				'id_customer'             => $post['id_customer'],
				'id_customer_int'         => $check_id_customer_int['id_customer_int'],
				'id_sa_form'              => $post['id_sa_form'],
				'flag_numbering'          => $post['flag_numbering'],
				'no_buku_khusus_claim_c2' => $post['no_buku_khusus_claim_c2'],
				'vor'                     => $post['vor'],
				'job_return_flag'         => $post['job_return_flag'],
				'eta'                     => $post['eta'],
				'keterangan_tambahan'     => $post['keterangan_tambahan'],
				'no_claim_c2'     		  => $post['no_claim_c2'],
				'order_to'                => $val['order_to'] == '' ? 0 : $val['order_to'],
				'id_dealer'               => dealer()->id_dealer,
			];
			foreach ($val['parts'] as $key_p => $prt) {
				$request_document_parts[] = [
					'id_booking' => $id_booking,
					'id_part' => $prt['id_part'],
					'harga_saat_dibeli' => $prt['harga'],
					'kuantitas' => $prt['qty'],
					'eta_terlama' => $prt['eta_terlama'],
				];
				$upd_parts[] = [
					'id_part'    => $prt['id_part'],
					'id_booking' => $id_booking,
					'send_notif' => 1,
				];
			}
		}
		// if ($post['page'] == 'sa_form') {
		$upd_sa = [
			'flag_numbering' => $post['flag_numbering'],
			'vehicle_offroad' => $post['vor'],
			'keterangan_tambahan' => $post['keterangan_tambahan']
		];
		// }
		$data = [
			'req' => $request_document,
			'req_p' => $request_document_parts,
			'sa' => isset($upd_sa) ? $upd_sa : '',
			'sa_p' => $upd_parts
		];
		// send_json($data);
		$this->db->trans_begin();
		if (isset($request_document)) {
			$this->db->insert_batch('tr_h3_dealer_request_document', $request_document);
		}
		if (isset($request_document_parts)) {
			$this->db->insert_batch('tr_h3_dealer_request_document_parts', $request_document_parts);
		}
		if (isset($upd_sa)) {
			$this->db->update('tr_h2_sa_form', $upd_sa, ['id_sa_form' => $post['id_sa_form']]);
		}
		if (isset($upd_parts)) {
			if ($post['page'] == 'sa_form') {
				$this->db->where(['id_sa_form' => $post['id_sa_form'], 'jenis_order' => 'HLO', 'send_notif' => 0]);
				$this->db->update_batch('tr_h2_sa_form_parts', $upd_parts, 'id_part');
			}
			if ($post['page'] == 'work_order_dealer') {
				$this->db->where(['id_work_order' => $post['id_work_order'], 'jenis_order' => 'HLO', 'send_notif' => 0]);
				$this->db->update_batch('tr_h2_wo_dealer_parts', $upd_parts, 'id_part');
			}
		}
		foreach ($request_document as $val) {
			$this->notifikasi->insert([
				'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notify_req_part_to_part_count')->get()->row()->id_notif_kat,
				'judul'        => 'Required Part From SA Form',
				'pesan'        => "Terdapat request part dari SA Form. Nomor SA Form: {$post['id_sa_form']}.",
				'link'         => "dealer/h3_dealer_request_document/detail?k={$val['id_booking']}",
				'id_referensi' => $val['id_booking'],
				'id_dealer'    => $this->m_admin->cari_dealer(),
				'show_popup'   => false,
			]);
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
				'link' => base_url('dealer/' . $post['page'])
			];
			$_SESSION['pesan'] 	= "Proses Parts HLO Berhasil";
			$_SESSION['tipe'] 	= "success";
		}
		send_json($rsp);
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_cancel = '<button style="margin-top:2px; margin-right:2px;" type="button" onclick="cancelPrompt(\'' . $rs->id_sa_form . '\')" class="btn btn-danger btn-xs btn-flat">Cancel</button>';
			$btn_print = '<a style="margin-top:2px; margin-right:1px;"href="dealer/sa_form/cetak?id=' . $rs->id_sa_form . '" class="btn btn-success btn-xs btn-flat">Cetak</a>';
			$btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="dealer/sa_form/edit?id=' . $rs->id_sa_form . '" class="btn btn-warning btn-xs btn-flat">Edit</a>';
			$btn_notif = '<a href="dealer/sa_form/send_notif?id=' . $rs->id_sa_form . '" class="btn btn-info btn-xs btn-flat">Notify Part Counter</a>';
			$button = $btn_print;

			if ($rs->status_form == 'open') {
				$status = '<label class="label label-primary">Open</label>';
				if (can_access($this->page, 'can_update'))  $button .= $btn_edit;
				if (can_access($this->page, 'can_cancel')) 	$button .= $btn_cancel;
				$filter = ['id_sa_form' => $rs->id_sa_form, 'jenis_order' => 'HLO', 'send_notif' => 0];
				// $cek_hlo = $this->m_h2->getSANeedParts($filter);
				// if ($cek_hlo > 0) {
				// 	if (can_access($this->page, 'can_update')) $button .= $btn_notif;
				// }
			} elseif ($rs->status_form == 'closed') {
				$status = '<label class="label label-warning">Closed</label>';
			} elseif ($rs->status_form == 'cancel') {
				$status = '<label class="label label-danger">Canceled</label>';
			}
			$sub_array[] = '<a href="dealer/sa_form/detail?id=' . $rs->id_sa_form . '">' . $rs->id_sa_form . '</a>';;
			$sub_array[] = $rs->id_antrian;
			$sub_array[] = $rs->tgl_servis;
			$sub_array[] = $rs->jenis_customer;
			$sub_array[] = $rs->no_polisi;
			$sub_array[] = $rs->nama_customer;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->tahun_produksi;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query($recordsFiltered = null)
	{
		$start        = $this->db->escape_str($this->input->post('start'));
		$length       = $this->db->escape_str($this->input->post('length'));
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $this->db->escape_str($_POST['order']) : '',
			'search' => $this->input->post('search')['value'],
			'id_sa_form_not_null' => 'y',
		];
		if (isset($_POST['status_form'])) {
			$filter['status_form'] = $this->db->escape_str($this->input->post('status_form'));
		}
		if (isset($_POST['status_form_not'])) {
			$filter['status_form_not'] = $this->db->escape_str($this->input->post('status_form_not'));
		}
		if (isset($_POST['tgl_servis'])) {
			$filter['tgl_servis'] = $this->db->escape_str($this->input->post('tgl_servis'));
		}
		if (isset($_POST['tgl_servis_lebih_kecil_sama'])) {
			$filter['tgl_servis_lebih_kecil_sama'] = $this->db->escape_str($this->input->post('tgl_servis_lebih_kecil_sama'));
		}
		if ($recordsFiltered == true) {
			return $this->m_wo->get_sa_form_header($filter)->num_rows();
		} else {
			return $this->m_wo->get_sa_form_header($filter)->result();
		}
	}

	public function edit()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Edit SA Form';
		$data['mode']  = 'edit';
		$data['set']   = "form";
		$id_sa_form    = $this->input->get('id');

		$filter['id_sa_form'] = $id_sa_form;
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$row                 = $data['row'] = $sa_form->row();
			$data['tipe_coming'] = explode(',', $row->tipe_coming);
			$data['pkp'] = $row->pkp;

			$filter['id_sa_form'] = $id_sa_form;
			$data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
			$data['details']      = $this->m_wo->sa_form_detail($filter);
			$data['activity_promotion'] = $this->m_sm->getActivityPromotion()->result();
			$data['activity_cap'] = $this->m_sm->getActivityCapacity()->result();
			$data['options_frt'] = [];
			for ($i = 0; $i <= 8; $i += 0.1) {
				$data['options_frt'][] = number_format($i, 1);
			}
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/sa_form'>";
		}
	}

	function save_edit()
	{
		$waktu           = waktu_full();
		$tgl             = date("Y-m-d");
		$login_id        = $this->session->userdata('id_user');
		$id_dealer       = $this->m_admin->cari_dealer();
		$id_antrian      = $this->input->post('id_antrian');
		$id_sa_form      = $this->input->post('id_sa_form');
		$tipe_coming     = $this->input->post('tipe_coming');
		$tipe_coming_imp = implode(',', $tipe_coming);
		$filter          = ['id_antrian'  => $id_antrian];
		$no_mesin        = $this->m_wo->get_sa_form($filter)->row()->no_mesin;
		$cek_srbu        = $this->m_h2->cekSRBU($no_mesin);
		$job_return = $this->input->post('job_return');

		// Cek Asal Unit Entry
		$fc = ['name' => $this->input->post('asal_unit_entry')];
		$act_promo = $this->m_sm->getActivityPromotion($fc)->row();

		$fc = ['id' => $this->input->post('activity_capacity_id')];
		$act_cap = $this->m_sm->getActivityCapacity($fc)->row();

		$asal_unit_entry = $act_promo != NULL ? $act_promo->name : NULL;

		$data 		= [
			'informasi_bensin'              => $this->input->post('informasi_bensin'),
			'km_terakhir'                   => $this->input->post('km_terakhir'),
			'alasan_ke_ahass'               => $this->input->post('alasan_ke_ahass'),
			'rekomendasi_sa'                => $this->input->post('rekomendasi_sa'),
			'keluhan_konsumen'              => $this->input->post('keluhan_konsumen'),
			'soc'              				=> $this->input->post('soc'),
			'serial_number_battery'         => $this->input->post('serial_number_battery'),
			'asal_unit_entry'               => $asal_unit_entry,
			'id_pit'                        => $this->input->post('id_pit'),
			'id_karyawan_dealer'            => $this->input->post('id_karyawan_dealer'),
			'id_wo_job_return'              => $this->input->post('id_wo_job_return'),
			'tipe_pembayaran'               => $this->input->post('tipe_pembayaran'),
			'nama_pemakai'                  => $this->input->post('nama_pemakai'),
			'no_buku_claim_c2'              => $this->input->post('no_buku_claim_c2'),
			'konfirmasi_pekerjaan_tambahan' => $this->input->post('konfirmasi_pekerjaan_tambahan'),
			'catatan_tambahan'              => $this->input->post('catatan_tambahan'),
			'no_claim_c2'                   => $this->input->post('no_claim_c2'),
			'motor_ditinggal'                   => $this->input->post('motor_ditinggal'),
			'job_return'                    => $job_return,
			// 'job_return'                    => $job_return == 'ya' ? 1 : 0,
			'tipe_coming'                   => $tipe_coming_imp,
			'updated_sa_form_at'            => waktu_full(),
			'updated_sa_form_by'            => $login_id,
			'status_form'                   => 'open',
			'srbu'                          => $cek_srbu,
			'activity_promotion_id' => $act_promo != NULL ? $act_promo->id : NULL,
			'activity_capacity_id' 					=> $act_cap != NULL ? $act_cap->id : NULL

		];

		if (isset($_POST['details'])) {
			$details = $this->input->post('details');
			foreach ($details as $keys => $val) {
				$need_parts = 0;
				if ($val['need_parts'] == 'yes' || $val['need_parts'] == 'y') {
					$need_parts = 1;
				}
				$frt_claim = 0;
				if ($val['frt_claim'] == 'yes') {
					$frt_claim = 1;
				}
				$ins_details[$keys] = [
					'id_sa_form' => $id_sa_form,
					'harga'      => $val['harga'],
					'waktu'      => $val['waktu'],
					'tipe_motor' => isset($val['tipe_motor']) ? $val['tipe_motor'] : null,
					'id_jasa'    => $val['jasa'],
					'need_parts' => $need_parts,
					'frt_claim' => $val['frt_claim'] == '' ? 0 : $val['frt_claim'],
					'id_promo'   => $val['id_promo'] != '' ? $val['id_promo'] : null,
					'pekerjaan_luar' => $val['pekerjaan_luar'],
					'id_tipe_servis' => $val['id_tipe_servis'],
					'labour_cost' => $val['labour_cost'],
				];
				if (strtolower($val['need_parts'] == 'yes') || strtolower($val['need_parts'] == 'y') || strtolower($val['need_parts'] == 1)) {
					foreach ($val['parts'] as $ky => $prt) {
						$part = $this->db->get_where('ms_part', ['id_part' => $prt['id_part']])->row();
						$harga = $part->harga_dealer_user;
						if ($val['id_type'] == 'C1' || $val['id_type'] == 'C2') {
							$harga = round($part->harga_dealer_user / 1.1);
						}
						$ins_parts[] = [
							'id_sa_form'  => $id_sa_form,
							'id_jasa'     => $val['jasa'],
							'id_part'     => $prt['id_part'],
							'qty'         => $prt['qty'],
							'harga'       => $harga,
							'id_gudang'   => isset($prt['id_gudang']) ? $prt['id_gudang'] : '',
							'id_rak'      => isset($prt['id_rak']) ? $prt['id_rak']      : '',
							'diskon_value' => isset($prt['diskon_value']) ? (int)$prt['diskon_value'] : 0,
							'id_promo'     => isset($prt['id_promo']) ? $prt['id_promo']        : null,
							'order_to'     => $prt['order_to'] == '' ? 0 : $prt['order_to'],
							'jenis_order' => $prt['jenis_order'],
							'part_utama' => $prt['part_utama'],
							'tipe_diskon'  => isset($prt['tipe_diskon']) ? $prt['tipe_diskon']  : null,
						];
					}
					if (isset($val['parts_demand'])) {
						foreach ($val['parts_demand'] as $ky => $prt) {
							$part = $this->db->get_where('ms_part', ['id_part' => $prt['id_part']])->row();
							$ins_parts_demand[] = [
								'id_sa_form'  => $id_sa_form,
								'id_part'       => $prt['id_part'],
								'id_dealer'     => $id_dealer,
								'search_result' => $prt['nama_part'] . ', ' . $prt['id_part'],
								'search_field' => '',
								'qty'           => $prt['qty'],
								'note_field'    => $prt['alasan'],
								'harga_satuan'  => $part->harga_dealer_user,
								'sisa_stock' => 0,
							];
						}
					}
				}
			}
		} else {
			$result = ['status' => 'error', 'pesan' => 'Pekerjaan masih kosong !'];
			echo json_encode($result);
			die();
		}

		//Update Customer
		$edit_cust = $this->input->post('edit_cust');
		$customer = $this->input->post('customer');
		if ($edit_cust == 1) {
			$upd_cust = update_customer($customer);
			$upd_cust['id_dealer']  = $id_dealer;
			$upd_cust['updated_at'] = $waktu;
			$upd_cust['updated_by'] = $login_id;
		}

		if (in_array('milik', $tipe_coming) == false) {
			//Data Pembawa
			$pembawa = $this->input->post('pembawa');
			if (empty($pembawa['id_pembawa'])) {
				$id_pembawa = $this->m_h2->get_id_pembawa();
				$ins_pembawa = [
					'id_pembawa'              => $id_pembawa,
					'id_customer'             => $customer['id_customer'],
					'nama'                    => isset($pembawa['nama']) ? $pembawa['nama'] : null,
					'no_hp'                   => isset($pembawa['no_hp']) ? $pembawa['no_hp'] : null,
					'email'                   => isset($pembawa['email']) ? $pembawa['email'] : null,
					'alamat_saat_ini'         => isset($pembawa['alamat_saat_ini']) ? $pembawa['alamat_saat_ini'] : null,
					'jenis_kelamin'           => isset($pembawa['jenis_kelamin']) ? $pembawa['jenis_kelamin'] : null,
					'id_agama'                => isset($pembawa['id_agama']) ? $pembawa['id_agama'] : null,
					'id_kelurahan'            => isset($pembawa['id_kelurahan']) ? $pembawa['id_kelurahan'] : null,
					'jenis_identitas'         => isset($pembawa['jenis_identitas']) ? $pembawa['jenis_identitas'] : null,
					'no_identitas'            => isset($pembawa['no_identitas']) ? $pembawa['no_identitas'] : null,
					'alamat_identitas'        => isset($pembawa['alamat_identitas']) ? $pembawa['alamat_identitas'] : null,
					'id_kelurahan_identitas'  => isset($pembawa['id_kelurahan_identitas']) ? $pembawa['id_kelurahan_identitas'] : null,
					'hubungan_dengan_pemilik' => isset($pembawa['hubungan_dengan_pemilik']) ? $pembawa['hubungan_dengan_pemilik'] : null,
					'aktif'                   => 1,
					'updated_at'              => $waktu,
					'updated_by'              => $login_id,
				];
			} else {
				$id_pembawa = $pembawa['id_pembawa'];
			}
			$data['id_pembawa'] = $id_pembawa;
		}
		// $tes = [
		// 	'data'        => $data,
		// 	'ins_details' => isset($ins_details) ? $ins_details : null,
		// 	'ins_parts'   => isset($ins_parts) ? $ins_parts    : null,
		// 	'ins_parts_demand'   => isset($ins_parts_demand) ? $ins_parts_demand    : null,
		// 	'ins_pembawa' => isset($ins_pembawa) ? $ins_pembawa : null,
		// 	'upd_cust'    => isset($upd_cust) ? $upd_cust      : null,
		// 	'customer'    => isset($customer) ? $customer      : null,
		// ];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->update('tr_h2_sa_form', $data, ['id_antrian' => $id_antrian]);
		$this->db->delete('tr_h2_sa_form_parts', ['id_sa_form' => $id_sa_form]);
		if (isset($ins_parts)) {
			$this->db->insert_batch('tr_h2_sa_form_parts', $ins_parts);
		}
		if (isset($ins_parts_demand)) {
			$this->db->insert_batch('tr_h3_dealer_record_reasons_and_parts_demand', $ins_parts_demand);
		}
		$this->db->delete('tr_h2_sa_form_pekerjaan', ['id_sa_form' => $id_sa_form]);

		if (isset($ins_details)) {
			$this->db->insert_batch('tr_h2_sa_form_pekerjaan', $ins_details);
		}
		if (isset($upd_cust)) {
			$this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $customer['id_customer']]);
		}
		if (isset($ins_pembawa)) {
			$this->db->insert('ms_h2_pembawa', $ins_pembawa);
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
				'link' => base_url('dealer/sa_form')
			];
			$_SESSION['pesan'] 	= "Data SA Form selesai diproses";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}
	function getLabourCost()
	{
		$post = $this->input->post();
		$post['tgl_mulai_berlaku'] = get_ymd();
		$result = $this->m_wo->getLabourCost($post);
		if ($result->num_rows() > 0) {
			$resp = ['status' => 'sukses', 'labour_cost' => $result->row()->nominal];
		} else {
			$resp = ['staus' => 'error', 'pesan' => 'Labour cost tidak ditemukan !'];
		}
		send_json($resp);
	}

	public function fetch_history()
	{
		$fetch_data = $this->make_query_history();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_print = '<a style="margin-top:2px; margin-right:1px;"href="dealer/sa_form/cetak?id=' . $rs->id_sa_form . '" class="btn btn-success btn-xs btn-flat">Cetak</a>';
			if ($rs->status_form == 'closed') {
				$status = '<label class="label label-warning">Closed</label>';
			} elseif ($rs->status_form == 'cancel') {
				$status = '<label class="label label-danger">Canceled</label>';
			}
			$sub_array[] = '<a href="dealer/sa_form/detail?id=' . $rs->id_sa_form . '">' . $rs->id_sa_form . '</a>';;
			$sub_array[] = $rs->id_antrian;
			$sub_array[] = $rs->tgl_servis;
			$sub_array[] = $rs->jenis_customer;
			$sub_array[] = $rs->no_polisi;
			$sub_array[] = $rs->nama_customer;
			$sub_array[] = $rs->no_mesin;
			$sub_array[] = $rs->no_rangka;
			$sub_array[] = $rs->tipe_ahm;
			$sub_array[] = $rs->warna;
			$sub_array[] = $rs->tahun_produksi;
			$sub_array[] = $status;
			$sub_array[] = $button;
			$data[]      = $sub_array;
		}
		$output = array(
			"draw"            =>     intval($_POST["draw"]),
			"recordsFiltered" =>     $this->make_query_history(true),
			"data"            =>     $data
		);
		echo json_encode($output);
	}

	public function make_query_history($recordsFiltered = null)
	{
		$start        = $this->db->escape_str($this->input->post('start'));
		$length       = $this->db->escape_str($this->input->post('length'));
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';

		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $this->db->escape_str($_POST['order']) : '',
			'search' => $this->input->post('search')['value'],
			'status_form_in' => "'cancel','closed'"
		];

		if ($recordsFiltered == true) {
			return $this->m_wo->getSAFormHistory($filter)->num_rows();
		} else {
			return $this->m_wo->getSAFormHistory($filter)->result();
		}
	}

	function checkLCR()
	{
		$id_customer = $this->input->post('id_customer');
		$id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');

		$tipe_motor_lcr = array('HN2','HM5','HM7','LD0','LE0','LEA','GRA','LD2','LE0','LE1','LNA','LN0','LP0','LPA','LH0','LH1','LK0','LKA','LK1','LKC','LJ1','LN1','LNE','LP1','LPB','LV0','LW0','LWA','LVA','LZ0','LY0','LN2','LP2','LPC','LNG','LH2','LK2','LJ0','LJ2','LY1','LZ1','LV1','LVE','LW1','LN3','LNJ','LP3','LPD');
		
		if(in_array($id_tipe_kendaraan,$tipe_motor_lcr)){
			//Cek apakah sudah melakukan LCR Check sebelumnya
			// $cek_servis = $this->db
			// 					   ->select('lcr.hasil_pengecekan_lcr_id')
			// 					   ->from('tr_h2_wo_lcr_history lcr')
			// 					   ->where('lcr.id_customer',$id_customer)
			// 					   ->where('lcr.status_lcr','LC')
			// 					   ->group_start()
			// 						->where('lcr.status','closed')
			// 						->or_where('lcr.status','open')
			// 					   ->group_end()
			// 					   ->group_start()
			// 						->where('lcr.kesediaan_customer_lcr_id',1)
			// 						->or_where('lcr.kesediaan_customer_lcr_id',2)
			// 					   ->group_end()
			// 					   ->limit(1)
			// 					   ->get()->row_array();

			$cek_servis = $this->db
								   ->select('lcr.hasil_pengecekan_lcr_id')
								   ->from('tr_h2_wo_lcr_history lcr')
								   ->where('lcr.id_customer',$id_customer)
								   ->where('lcr.status_lcr','LC')
								   ->where('lcr.kesediaan_customer_lcr_id',1)
								   ->where('lcr.status','closed')
								   ->limit(1)
								   ->get()->row_array();

			if(!empty($cek_servis)){
				if($cek_servis['hasil_pengecekan_lcr_id'] == 1){
					//Cek apakah sudah melakukan treatment 1
					// $cek_servis_lcr_treatment = $this->db
					// 			   ->select('lcr.id_work_order')
					// 			   ->select('lcr.hasil_pengecekan_lcr_id')
					// 			   ->from('tr_h2_wo_lcr_history lcr')
					// 			   ->where('lcr.id_customer',$id_customer)
					// 			   ->where('lcr.status_lcr','LT1')
					// 			   ->group_start()
					// 				->where('lcr.status','closed')
					// 				->or_where('lcr.status','open')
					// 			   ->group_end()
					// 			   ->group_start()
					// 				->where('lcr.kesediaan_customer_lcr_id',1)
					// 				->or_where('lcr.kesediaan_customer_lcr_id',2)
					// 			   ->group_end()
					// 			   ->limit(1)
					// 			   ->get()->row_array();

					$cek_servis_lcr_treatment = $this->db
								   ->select('lcr.id_work_order')
								   ->select('lcr.hasil_pengecekan_lcr_id')
								   ->from('tr_h2_wo_lcr_history lcr')
								   ->where('lcr.id_customer',$id_customer)
								   ->where('lcr.status_lcr','LT1')
								   ->where('lcr.kesediaan_customer_lcr_id',1)
								   ->where('lcr.status','closed')
								   ->limit(1)
								   ->get()->row_array();

					if(empty($cek_servis_lcr_treatment)){
						$result = [
							'status'   => 'perlu_lcr_treatment'
						];
					}else{
						// if($cek_servis_lcr_treatment['hasil_pengecekan_lcr_id'] == 2){
						// 	$result = [
						// 		'status'   => 'perlu_lcr_penggantian'
						// 	];
						// }else{
						// 	$result = [
						// 		'status'   => 'bukan_lcr'
						// 	];
						// }
						$result = [
							'status'   => 'bukan_lcr'
						];
					}
				}elseif($cek_servis['hasil_pengecekan_lcr_id'] == 2){
					$cek_servis_lcr_ganti = $this->db
								   ->select('lcr.id_work_order')
								   ->select('lcr.hasil_pengecekan_lcr_id')
								   ->from('tr_h2_wo_lcr_history lcr')
								   ->where('lcr.id_customer',$id_customer)
								   ->where('lcr.status_lcr','LG')
								   ->where('lcr.kesediaan_customer_lcr_id',1)
								   ->where('lcr.status','closed')
								//    ->group_start()
								// 	->where('lcr.status','closed')
								// 	->or_where('lcr.status','open')
								//    ->group_end()
								//    ->group_start()
								// 	->where('lcr.kesediaan_customer_lcr_id',1)
								// 	->or_where('lcr.kesediaan_customer_lcr_id',2)
								//    ->group_end()
								   ->limit(1)
								   ->get()->row_array();

					if(empty($cek_servis_lcr_ganti)){
						$result = [
							'status'   => 'perlu_lcr_penggantian'
						];
					}else{
						$result = [
							'status'   => 'bukan_lcr'
						];
					}	
				}else{
					$result = [
						'status'   => 'bukan_lcr'
					];
				}
			}else{
				$result = [
					'status'   => 'perlu_lcr_check'
				];
			}
		}else{
			$result = [
				'status'   => 'bukan_lcr'
			];
		}

		echo json_encode($result);
	}

	function check_serial_number(){
		if(isset($_POST['serial_number_battery'])) {
			$serial_number = $_POST['serial_number_battery'];
		
			$check_data_serial_number = $this->db->query("SELECT serial_number FROM tr_h3_serial_ev_tracking WHERE serial_number = '$serial_number'")->row_array();
		
			// Cek hasil dan kirim respons
			if(empty($check_data_serial_number['serial_number'])){
				$check_data_serial_number_h1 = $this->db->query("SELECT serial_number FROM tr_stock_battery WHERE serial_number = '$serial_number'")->row_array();
				if(empty($check_data_serial_number_h1['serial_number'])){
					echo json_encode(['available' => false, 'message' => 'Serial Number : ' .$serial_number. ' Tidak tersedia, cek kembali Serial Number']);
				}else{
					echo json_encode(['available' => true, 'message' => '']);
				}
			}else{
				echo json_encode(['available' => true, 'message' => '']);
			}
		} 
	}
}
