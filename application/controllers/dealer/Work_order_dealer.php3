<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Work_order_dealer extends CI_Controller
{

	var $tables = "tr_h2_wo_dealer";
	var $folder = "dealer";
	var $page   = "work_order_dealer";
	var $title  = "Work Order";

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
		$this->load->model('m_h2_work_order', 'm_wo');
		$this->load->model('m_h2_api', 'm_api');
		$this->load->model('m_h2_billing', 'm_bil');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
		$this->load->model('m_sm_master', 'm_sm');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$page = $this->page;
			if (isset($data['mode'])) {
				if ($data['mode'] == 'insert_wo') $page = 'sa_form';
				if ($data['mode'] == 'detail_wo') $page = 'sa_form';
				if ($data['mode'] == 'update_wo') $page = 'sa_form';
				if ($data['mode'] == 'send') $page = 'sa_form';
			}
			$this->load->view($this->folder . "/" . $page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "index";
		// $filter = ['id_work_order_not_null' => 'ya'];
		// $data['wo'] = $this->m_wo->get_sa_form($filter);
		$this->template($data);
	}
	public function history()
	{
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "history";
		$this->template($data);
	}
	public function add()
	{
		$data['isi']     = $this->page;
		$data['title']   = $this->title;
		$data['mode']    = 'insert_wo';
		$data['set']     = "form";
		$data['pkp'] = dealer()->pkp;
		$data['estimasi_waktu_daftar'] = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$this->template($data);
	}

	function save_wo()
	{
		$waktu              = waktu_full();
		$tgl                = date("Y-m-d");
		$login_id           = $this->session->userdata('id_user');
		$id_dealer          = $this->m_admin->cari_dealer();
		$post               = $this->input->post();
		// send_json($post);
		$id_work_order      = $this->m_wo->get_id_work_order();
		// $id_antrian         = $post['id_antrian'];
		// $tipe_coming     = implode($post['tipe_coming'), ','];
		$id_sa_form         = $post['id_sa_form'];
		$id_pit             = $post['id_pit'];
		$id_karyawan_dealer = $post['id_karyawan_dealer'];
		$tipe_pembayaran    = $post['tipe_pembayaran'];

		$cek_sa_on_wo = $this->db->get_where('tr_h2_wo_dealer', ['id_sa_form' => $id_sa_form])->row();
		if ($cek_sa_on_wo != null) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'SA Form : ' . $id_sa_form . " sudah dibuat WO. ID Work Order : " . $cek_sa_on_wo->id_work_order
			];
			send_json($rsp);
		}

		$sa = $this->m_wo->get_sa_form_header(['id_sa_form' => $id_sa_form])->row();
		$ins_data 		= [
			'id_work_order'      => $id_work_order,
			'id_sa_form'         => $id_sa_form,
			'id_sa_form_int'     => $sa->id_antrian_int,
			'id_dealer'          => $id_dealer,
			// 'id_pit'          => $id_pit,
			'id_karyawan_dealer' => $id_karyawan_dealer,
			'tipe_pembayaran'    => $tipe_pembayaran,
			'status'             => 'open',
			'created_at'         => $waktu,
			'created_by'         => $login_id,
			'grand_total'        => round($post['grand_total']),
			'total_jasa'         => round($post['total_jasa']),
			'total_part'         => round($post['total_part']),
			'total_ppn'          => round($post['total_ppn']),
			'total_tanpa_ppn'    => round($post['total_tanpa_ppn']),
		];
		// 		if ($post['grand_total'] == 0) {
		// 			$rsp = [
		// 				'status' => 'error',
		// 				'pesan' => 'Grand Total Kosong !'
		// 			];
		// 			send_json($rsp);
		// 		}
		$upd_sa_form = [
			'status_form' => 'closed',
			'job_return' => $post['job_return'],
			'motor_ditinggal' => $post['motor_ditinggal'],
			'closed_at'   => $waktu,
			'id_pit'      => $id_pit,
			'closed_by'   => $login_id
		];

		$details = $this->input->post('details');

		foreach ($details as $keys => $val) {
			if ($val['masukkan_wo'] == 'true') {
				$id_promo = $val['id_promo'] != '' ? $val['id_promo'] : null;
				$disc_amount     = 0;
				$disc_percentage = 0;
				$diskon_real = 0;

				if ($id_promo != NULL) {
					$filter_diskon = [
						'id_dealer' => $id_dealer,
						'id_jasa' => $val['id_jasa'],
						'id_promo' => $id_promo
					];
					$diskon = $this->m_wo->getPromoServis($filter_diskon);
					if ($diskon->num_rows() > 0) {
						$diskon = $diskon->row();
						if ($diskon->tipe_diskon == 'rupiah') {
							$disc_amount   = $diskon_real = $diskon->diskon;
						} elseif ($diskon->tipe_diskon == 'persen') {
							$disc_percentage = $diskon->diskon;
							$diskon_real = $val['harga'] * round($disc_percentage / 100);
						}
					}
				}
				$subtotal = $val['harga'] - $diskon_real;
				$ins_details[$keys] = [
					'id_work_order'   => $id_work_order,
					'id_jasa'         => $val['id_jasa'],
					'harga'           => $val['harga'],
					'waktu'           => $val['waktu'],
					'tipe_motor'      => $val['tipe_motor'],
					'need_parts'      => substr($val['need_parts'], 0, 1),
					'id_promo'        => $id_promo,
					'pekerjaan_luar'  => $val['pekerjaan_luar'],
					'id_tipe_servis'  => $val['id_tipe_servis'],
					'disc_amount'     => $disc_amount,
					'diskon_value'     => $disc_amount,
					'disc_percentage' => $disc_percentage,
					'subtotal'        => $subtotal,
					'frt_claim'      => $val['frt_claim']   == '' ? 0 : $val['frt_claim'],
					'labour_cost'    => $val['labour_cost'] == '' ? 0 : $val['labour_cost']
				];
				if (strtolower($val['need_parts']) == 'yes' || strtolower($val['need_parts']) == 'y' || strtolower($val['need_parts']) == 1) {
					foreach ($val['parts'] as $ky => $prt) {
						$part = $this->db->get_where('ms_part', ['id_part' => $prt['id_part']])->row();
						$harga = $part->harga_dealer_user;
						if ($val['id_type'] == 'C1' || $val['id_type'] == 'C2') {
							$harga = round($part->harga_dealer_user / getPPN(1.1, false));
						}
						$ppn = round($harga * getPPN(0.1, false));
						$subtotal = subtotal_part($prt, $harga);
						$diskon_value = $prt['diskon_value'] == '' ? 0 : $prt['diskon_value'];
						$send_notif = 1;
						if ($prt['send_notif'] == '' || $prt['send_notif'] == 0) {
							$send_notif = 0;
						}
						$ins_parts[] = [
							'id_work_order' => $id_work_order,
							'id_jasa'       => $val['id_jasa'],
							'id_part_int' => $part->id_part_int,
							'id_part'       => $prt['id_part'],
							'qty'           => $prt['qty'],
							'id_gudang'     => $prt['id_gudang'],
							'id_rak'        => $prt['id_rak'],
							'jenis_order'   => $prt['jenis_order'],
							'tipe_diskon'   => $prt['tipe_diskon'],
							'diskon_value'  => $diskon_value,
							'id_promo'      => $prt['id_promo'],
							'harga'         => $harga,
							'ppn'           => $ppn,
							'subtotal'      => $subtotal,
							'order_to'      => $prt['order_to'] == '' ? 0 : $prt['order_to'],
							'send_notif'    => $send_notif,
							'id_booking'    => $prt['id_booking'],
							'part_utama'    => $prt['part_utama'] == NULL ? 0 : $prt['part_utama'],
						];
					}
				}
			}
		}
		if (empty($ins_details)) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'Pekerjaan untuk work order belum ditentukan !'
			];
			echo json_encode($rsp);
			exit();
		}
		// $tes = ['ins_data' => $ins_data, 'ins_details' => $ins_details, 'ins_parts' => isset($ins_parts) ? $ins_parts : '', 'upd_sa_form' => $upd_sa_form];
		// send_json($tes);
		$this->db->trans_begin();
		$this->db->insert('tr_h2_wo_dealer', $ins_data);
		$this->db->update('tr_h2_sa_form', $upd_sa_form, ['id_sa_form' => $id_sa_form]);
		if (isset($ins_parts)) {
			$this->db->insert_batch('tr_h2_wo_dealer_parts', $ins_parts);
		}
		if (isset($ins_details)) {
			$this->db->insert_batch('tr_h2_wo_dealer_pekerjaan', $ins_details);
		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$this->m_wo->updateGrandTotalWO($id_work_order);
			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/work_order_dealer')
			];
			$_SESSION['pesan'] 	= "Sukses membuat work order";
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	function getDataWO()
	{
		$filter['id_sa_form'] = $this->input->post('id_sa_form');
		if (isset($_POST['id_dealer'])) {
			if ($_POST['id_dealer'] != '') {
				$filter['id_dealer'] = $this->input->post('id_dealer');
			}
		}
		$mode                 = isset($_POST['mode']) ? $_POST['mode'] : '';
		// send_json($filter);
		$sa_form              = $this->m_wo->get_sa_form($filter);
		// send_json($sa_form->row());
		if ($sa_form->num_rows() > 0) {
			$sa = $sa_form->row();
			// echo json_encode($sa);
			// die();
			$filter   = ['id_customer' => $sa->id_customer];
			$customer = $this->m_api->getCustomerH23($filter)->row();
			$no_mesin = $customer == NULL ? '' : $customer->no_mesin;
			if ($sa->id_pembawa != '') {
				$filter = ['id_pembawa' => $sa->id_pembawa];
				$pembawa  = $this->m_api->getPembawa($filter)->row();
			} else {
				$pembawa = $customer;
			}
		} else {
			$result = ['status' => 'error', 'pesan' => 'Data tidak ditemukan !'];
			echo json_encode($result);
			die();
		}
		// send_json($sa);
		if ($sa->id_work_order != null) {
			$filter_wo['id_work_order'] = $sa->id_work_order;
			if ($mode == 'insert_surat_jalan') {
				$filter_wo['surat_jalan_null'] = 'ya';
				$filter_wo['pekerjaan_luar']   = 1;
			}
			$details = $this->m_h2->wo_detail($filter_wo);
		} else {
			$filter = ['id_sa_form' => $sa->id_sa_form];
			$details = $this->m_wo->sa_form_detail($filter);
		}
		$cek_srbu = $this->m_h2->cekSRBU($no_mesin);
		$result = [
			'status' => 'sukses',
			'sa' => $sa,
			'customer' => $customer,
			'pembawa' => $pembawa,
			'tipe_coming' => explode(',', $sa->tipe_coming),
			'details' => $details,
			'srbu'     => $cek_srbu

		];
		send_json($result);
	}

	public function detail()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Detail Work Order';
		$data['mode']  = 'detail_wo';
		$data['set']   = "form";
		$id_work_order    = $this->input->get('id');

		$filter['id_work_order'] = $id_work_order;
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$row                     = $data['row_wo'] = $sa_form->row();
			$data['tipe_coming']           = explode(',', $row->tipe_coming);
			$data['pkp']                   = $row->pkp;
			$data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
			$data['activity_promotion']    = $this->m_sm->getActivityPromotion()->result();
			$data['activity_cap']          = $this->m_sm->getActivityCapacity()->result();
			// $filter['id_work_order'] = $id_work_order;
			// $data['details']         = $this->m_h2->wo_detail($filter);
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}

	public function cetak()
	{
		$get = $this->input->get();
		$tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');
		$id_sa_form = $this->input->get('id');
		$filter['id_sa_form'] = $id_sa_form;
		$get_data = $this->m_wo->get_sa_form($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();
			$sad = $this->db->query("SELECT nama_lengkap FROM ms_karyawan_dealer dl JOIN ms_user usr ON usr.id_karyawan_dealer=dl.id_karyawan_dealer WHERE id_user='$row->created_by'")->row();
			$row->service_advisor = $sad->nama_lengkap;

			$upd = [
				'cetak_sa_form_ke' => $row->cetak_sa_form_ke + 1,
				'cetak_sa_form_at' => $waktu,
				'cetak_sa_form_by' => $login_id,
			];
			// $this->db->update('tr_h2_sa_form', $upd, ['id_sa_form' => $id_sa_form]);
			$this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'print';
			$data['judul_laporan'] = 'PERINTAH KERJA BENGKEL';
			$data['row'] = $row;
			$filter_pekerjaan['id_work_order'] = $row->id_work_order;
			$data['pekerjaan'] = $this->m_h2->getPekerjaanWO($filter_pekerjaan)->result();
			$filter_part['id_work_order'] = $row->id_work_order;
			$filter_part['select'] = 'wo_parts';
			$data['parts'] = $this->m_wo->getWOParts($filter_part)->result();
			$filter = [
				'group_by_no_nsc' => true,
				'id_work_order' => $row->id_work_order
			];
			$data['estimasi_biaya'] = $row->grand_total;
			$filter_sa = [
				'id_customer' => $row->id_customer,
				'except_wo' => $row->id_work_order
			];
			$last_wo = $this->m_wo->get_sa_form($filter_sa);
			if ($last_wo->num_rows() > 0) {
				$data['last_wo'] = $last_wo->row();
				// send_json($data);
			}
			if (isset($get['tes'])) {
				send_json($data);
			}
			$html = $this->load->view('dealer/sa_form_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_work_order.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}
	public function cetak_dengan_harga()
	{
		$get = $this->input->get();
		$tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
		$waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
		$login_id   = $this->session->userdata('id_user');
		$id_sa_form = $this->input->get('id');
		$filter['id_sa_form'] = $id_sa_form;
		$get_data = $this->m_wo->get_sa_form($filter);
		if ($get_data->num_rows() > 0) {
			$row = $data['row'] = $get_data->row();
			$upd = [
				'cetak_sa_form_ke' => $row->cetak_sa_form_ke + 1,
				'cetak_sa_form_at' => $waktu,
				'cetak_sa_form_by' => $login_id,
			];
			// $this->db->update('tr_h2_sa_form', $upd, ['id_sa_form' => $id_sa_form]);
			$this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

			$data['set'] = 'cetak_pkb_dengan_harga';
			$data['judul_laporan'] = 'PERINTAH KERJA BENGKEL';
			$data['row'] = $row;
			$filter_pekerjaan['id_work_order'] = $row->id_work_order;
			$data['pekerjaan'] = $this->m_h2->getPekerjaanWO($filter_pekerjaan)->result();
			$filter_part['id_work_order'] = $row->id_work_order;
			$filter_part['select'] = 'wo_parts';
			$data['parts'] = $this->m_wo->getWOParts($filter_part)->result();

			$data['estimasi_biaya'] = $row->grand_total;
			$filter_sa = [
				'id_customer' => $row->id_customer,
				'except_wo' => $row->id_work_order
			];
			$last_wo = $this->m_wo->get_sa_form($filter_sa);
			if ($last_wo->num_rows() > 0) {
				$data['last_wo'] = $last_wo->row();
				// send_json($data);
			}
			if (isset($get['tes'])) {
				send_json($data);
			}
			$html = $this->load->view('dealer/sa_form_cetak', $data, true);
			// render the view into HTML
			$mpdf->WriteHTML($html);
			// write the HTML into the mpdf
			$output = 'cetak_work_order.pdf';
			$mpdf->Output("$output", 'I');
		} else {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}

	function get_select_sa_form()
	{
		$id_dealer  = $this->m_admin->cari_dealer();

		$where = "WHERE tr_h2_sa_form.id_dealer='$id_dealer' AND status_form='open'";
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
			$where .= "AND id_sa_form LIKE '%$search%'";
		}
		$rs = $this->db->query("SELECT id_sa_form AS id,id_sa_form AS text 
				FROM tr_h2_sa_form $where
				");
		foreach ($rs->result() as $rs) {
			//Cek Kebutuhan Parts HLO
			$filter = ['id_sa_form' => $rs->id, 'jenis_order' => 'HLO'];
			$cek_jenis_hlo = $this->m_h2->getSANeedParts($filter);
			if ($cek_jenis_hlo > 0) {
				$filter['send_notif'] = 1;
				$cek_hlo_send = $this->m_h2->getSANeedParts($filter);
				if ($cek_hlo_send > 0) {
					$result[] = $rs;
				}
				// $result[] = $rs;
			} else {
				$result[] = $rs;
			}
		}
		echo json_encode($result);
	}

	function get_select_pit()
	{
		$id_dealer  = $this->m_admin->cari_dealer();

		$where = "WHERE ms_h2_pit.id_dealer='$id_dealer'";
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
			$where .= "AND id_pit LIKE '%$search%'";
		}
		$rs = $this->db->query("SELECT id_pit AS id, CONCAT(id_pit,' | ',jenis_pit) AS text 
				FROM ms_h2_pit $where
			  ");
		echo json_encode($rs->result());
	}
	function get_sel_mekanik_ready()
	{
		$tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
		$id_dealer = $this->m_admin->cari_dealer();
		$id_pit    = $this->input->post('id_pit');

		$where = "WHERE mpm.id_dealer='$id_dealer' AND mpm.id_pit='$id_pit'";
		if (isset($_POST['searchTerm'])) {
			$search = $_POST['searchTerm'];
			$where .= " AND (nama_lengkap LIKE '%$search%'
							OR mpm.id_karyawan_dealer LIKE '%$search%'
						   )
					  ";
		}

		//Cek Absensi
		$where .= "AND mpm.id_karyawan_dealer IN(SELECT id_karyawan_dealer 
					FROM tr_h2_absen_mekanik_detail AS tamd
					JOIN tr_h2_absen_mekanik AS tam ON tam.id_absen=tamd.id_absen
					WHERE id_karyawan_dealer=mpm.id_karyawan_dealer
					AND tam.tanggal='$tanggal' AND tamd.aktif=1
					)";

		$where .= "AND mpm.id_karyawan_dealer NOT IN(SELECT id_karyawan_dealer FROM tr_h2_wo_dealer wo WHERE id_karyawan_dealer=mpm.id_karyawan_dealer AND wo.status='open' AND (SELECT COUNT(id) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo.id_work_order AND stats='end')=0)";

		$rs = $this->db->query("SELECT mpm.id_karyawan_dealer AS id, CONCAT(mpm.id_karyawan_dealer,' | ',nama_lengkap) AS text 
				FROM ms_h2_pit_mekanik AS mpm
				JOIN ms_karyawan_dealer mkd ON mkd.id_karyawan_dealer=mpm.id_karyawan_dealer
				$where
			  ");

		echo json_encode($rs->result());
	}

	public function update()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Update Work Order';
		$data['mode']  = 'update_wo';
		$data['set']   = "form";
		$id_work_order = $this->input->get('id');

		$filter['id_work_order'] = $id_work_order;
		$filter['last_stats_not'] = 'end';
		$filter['status_wo_not'] = "closed";
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$row                 = $data['row_wo'] = $sa_form->row();
			$data['tipe_coming'] = explode(',', $row->tipe_coming);
			$data['pkp']         = $row->pkp;
			$data['activity_promotion'] = $this->m_sm->getActivityPromotion()->result();
			$data['activity_cap'] = $this->m_sm->getActivityCapacity()->result();
			$data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
			// send_json($data);
			$this->template($data);
		} else {
			$_SESSION['pesan']   = "Data not found !";
			$_SESSION['tipe']   = "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}

	function save_update_wo()
	{
		$waktu         = waktu_full();
		$tgl           = date("Y-m-d");
		$login_id      = $this->session->userdata('id_user');
		$id_work_order = $this->input->post('id_work_order');
		$details       = $this->input->post('details');
		$post          = $this->input->post();
		$id_dealer     = dealer()->id_dealer;
		$fsa = ['id_work_order' => $id_work_order];
		$wo = $this->m_wo->get_sa_form($fsa)->row();

		// send_json($details);
		foreach ($details as $keys => $val) {
			if (isset($val['update'])) {
				$id_promo = $val['id_promo'] != '' ? $val['id_promo'] : null;
				$disc_amount     = 0;
				$disc_percentage = 0;
				$diskon_real = 0;
				if (!isset($val['pekerjaan_batal'])) {
					$val['pekerjaan_batal'] = 0;
				}
				if ($id_promo != NULL) {
					// if (!isset($val['id_jasa'])) {
					// 	$val['id_jasa'] = $val['jasa'];
					// }

					$filter_diskon = [
						'id_dealer' => $id_dealer,
						'id_jasa' => $val['jasa'],
						'id_promo' => $id_promo
					];
					$diskon = $this->m_wo->getPromoServis($filter_diskon);
					if ($diskon->num_rows() > 0) {
						$diskon = $diskon->row();
						if ($diskon->tipe_diskon == 'rupiah') {
							$disc_amount   = $diskon_real = $diskon->diskon;
						} elseif ($diskon->tipe_diskon == 'persen') {
							$disc_percentage = $diskon->diskon;
							$diskon_real = $val['harga'] * round($disc_percentage / 100);
						}
					}
				}
				$subtotal = $val['harga'] - $diskon_real;
				$ins_details[$keys] = [
					'id_work_order'   => $id_work_order,
					'id_jasa'         => $val['jasa'],
					'harga'           => $val['harga'],
					'waktu'           => $val['waktu'],
					// 'pekerjaan_luar'  => $val['pekerjaan_luar'] == '' ? 0 : 1,
					'pekerjaan_luar'  => $val['pekerjaan_luar'],
					'id_tipe_servis'  => $val['id_tipe_servis'],
					'need_parts'      => substr($val['need_parts'], 0, 1),
					'tambahan'        => 1,
					'disc_amount'     => $disc_amount,
					'diskon_value'     => $disc_amount,
					'disc_percentage' => $disc_percentage,
					'subtotal'        => $subtotal,
					'id_promo' => $id_promo,
					'frt_claim'      => $val['frt_claim']   == '' ? 0 : $val['frt_claim'],
					'labour_cost'    => $val['labour_cost'] == '' ? 0 : $val['labour_cost']
				];
				if (strtolower($val['need_parts']) == 'yes'  || strtolower($val['need_parts']) == 'y' || strtolower($val['need_parts']) == 1) {
					foreach ($val['parts'] as $ky => $prt) {
						$part = $this->db->get_where('ms_part', ['id_part' => $prt['id_part']])->row();
						$harga = $part->harga_dealer_user;
						if ($val['id_type'] == 'C1' || $val['id_type'] == 'C2') {
							$harga = round($part->harga_dealer_user / getPPN(1.1, false));
						}

						$ppn = round($harga * getPPN(0.1, false));
						$subtotal = subtotal_part($prt, $harga);
						$ins_parts[] = [
							'id_work_order' => $id_work_order,
							'id_jasa'       => $val['jasa'],
							'id_part_int'       => $part->id_part_int,
							'id_part'       => $prt['id_part'],
							'qty'           => $prt['qty'],
							'ppn'           => $ppn,
							'subtotal'      => $subtotal,
							'id_promo'      => isset($prt['id_promo']) ? $prt['id_promo']        : null,
							'id_kirim_part' => NULL,
							'diskon_value'  => isset($prt['diskon_value']) ? $prt['diskon_value'] : 0,
							'tipe_diskon'   => isset($prt['tipe_diskon']) ? $prt['tipe_diskon']  : null,
							'nomor_ps' => NULL,
							'nomor_so' => NULL,
							'part_utama'   => isset($prts['part_utama']) ? $prts['part_utama']  : 0,
							'id_gudang'     => isset($prt['id_gudang']) ? $prt['id_gudang']      : '',
							'id_rak'        => isset($prt['id_rak']) ? $prt['id_rak']            : '',
							'send_notif' 		=> isset($prt['send_notif']) ? $prt['send_notif'] : 0,
							'id_booking' 		=> isset($prt['id_booking']) ? $prt['id_booking'] : null,
							'sudah_terbuat_picking_slip' => 0,
							'jenis_order'   => $prt['jenis_order'],
							'harga'         => $harga,
							'tambahan'      => 1,
							'order_to'      => $prt['order_to'] == '' ? 0 : $prt['order_to'],
							'pekerjaan_batal'  => $val['pekerjaan_batal'],
						];
					}
				}
			} else {
				$upd_batal[] = [
					'id_jasa' => $val['id_jasa'],
					'pekerjaan_batal' => $val['pekerjaan_batal']
				];
				$del_part[] = $val['id_jasa'];
				if (isset($val['parts'])) {
					foreach ($val['parts'] as $prts) {
						$f = [
							'id_work_order' => $id_work_order,
							'id_part' => $prts['id_part'],
							'id_jasa' => $val['id_jasa'],
							'join' => 'picking_slip',
							'select' => 'picking_slip',
						];
						$cek_part_wo = $this->m_wo->getWOParts($f);
						if ($cek_part_wo->num_rows() > 0) {
							$get_part = $this->db->get_where('ms_part', ['id_part' => $prts['id_part']])->row();
							$harga = $prts['harga'];
							if ($val['id_type'] == 'C1' || $val['id_type'] == 'C2') {
								$harga = round($prts['harga'] / getPPN(1.1, false));
							}

							$ppn = round($harga * getPPN(0.1, false));
							$subtotal = subtotal_part($prts, $harga);
							$ins_parts[] = [
								'id_work_order'   => $id_work_order,
								'id_jasa'         => $val['jasa'],
								'id_part_int'     => $get_part->id_part_int,
								'id_part'         => $prts['id_part'],
								'qty'             => $prts['qty'],
								'ppn'             => $ppn,
								'subtotal'        => $subtotal,
								'id_promo'      => isset($prts['id_promo']) ? $prts['id_promo']        : null,
								'id_booking'      => isset($prts['id_booking']) ? $prts['id_booking']        : null,
								'id_kirim_part'      => isset($prts['id_kirim_part']) ? $prts['id_kirim_part']        : null,
								'diskon_value'  => isset($prts['diskon_value']) ? $prts['diskon_value'] : 0,
								'tipe_diskon'   => isset($prts['tipe_diskon']) ? $prts['tipe_diskon']  : null,
								'nomor_ps'   => isset($prts['nomor_ps']) ? $prts['nomor_ps']  : null,
								'nomor_so'   => isset($prts['nomor_so']) ? $prts['nomor_so']  : null,
								'part_utama'   => isset($prts['part_utama']) ? $prts['part_utama']  : 0,
								'id_gudang'     => isset($prts['id_gudang']) ? $prts['id_gudang']      : '',
								'id_rak'        => isset($prts['id_rak']) ? $prts['id_rak']            : '',
								'send_notif'        => isset($prts['send_notif']) ? $prts['send_notif'] : 0,
								'sudah_terbuat_picking_slip'        => isset($prts['sudah_terbuat_picking_slip']) ? $prts['sudah_terbuat_picking_slip']            : 0,
								'jenis_order'   => $prts['jenis_order'],
								'harga'         => $harga,
								'tambahan'      => 0,
								'order_to'      => $prts['order_to'] == '' ? 0 : $prts['order_to'],
								'pekerjaan_batal'       => $val['pekerjaan_batal'],
							];
						} else {
							$part = $this->db->get_where('ms_part', ['id_part' => $prts['id_part']])->row();
							$harga = $part->harga_dealer_user;
							if ($val['id_type'] == 'C1' || $val['id_type'] == 'C2') {
								$harga = round($part->harga_dealer_user / getPPN(1.1, false));
							}
							$ppn = round($harga * getPPN(0.1, false));
							$subtotal = subtotal_part($prts, $harga);
							$ins_parts[] = [
								'id_work_order' => $id_work_order,
								'id_jasa'       => $val['jasa'],
								'id_part_int' => $part->id_part_int,
								'id_part'       => $prts['id_part'],
								'qty'           => $prts['qty'],
								'ppn'           => $ppn,
								'subtotal'      => $subtotal,
								'id_promo'      => isset($prts['id_promo']) ? $prts['id_promo'] : null,
								'id_booking'    => isset($prts['id_booking']) ? $prts['id_booking'] : null,
								'send_notif'    => isset($prts['send_notif']) ? $prts['send_notif'] : 0,
								'id_kirim_part' => NULL,
								'diskon_value'  => isset($prts['diskon_value']) ? $prts['diskon_value'] : 0,
								'tipe_diskon'   => isset($prts['tipe_diskon']) ? $prts['tipe_diskon']  : null,
								'nomor_ps' => NULL,
								'nomor_so' => NULL,
								'part_utama'   => isset($prts['part_utama']) ? $prts['part_utama']  : 0,
								'id_gudang'     => isset($prts['id_gudang']) ? $prts['id_gudang'] : '',
								'id_rak'        => isset($prts['id_rak']) ? $prts['id_rak'] : '',
								'sudah_terbuat_picking_slip' => 0,
								'jenis_order'                => $prts['jenis_order'],
								'harga'                      => $harga,
								'tambahan'                   => 1,
								'order_to'      => $prts['order_to'] == '' ? 0 : $prts['order_to'],
								'pekerjaan_batal'            => $val['pekerjaan_batal'],
							];
						}
					}
				}
			}
		}
		$upd_wo = [
			'grand_total'     => round($post['grand_total']),
			'total_jasa'      => round($post['total_jasa']),
			'total_part'      => round($post['total_part']),
			'total_ppn'       => round($post['total_ppn']),
			'total_tanpa_ppn' => round($post['total_tanpa_ppn']),
			'updated_at'      => waktu_full(),
			'updated_by'      => user()->id_user,
		];

		$new_id_pit = $this->input->post('id_pit');
		if ($wo->id_pit != $new_id_pit) {
			$id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
			$upd_sa_form = [
				'id_pit' => $new_id_pit,
				'id_karyawan_dealer' => $id_karyawan_dealer,
			];
			$upd_wo['id_karyawan_dealer'] = $id_karyawan_dealer;
		}

		$new_id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
		if ($wo->id_karyawan_dealer != $new_id_karyawan_dealer) {
			$id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
			$upd_sa_form['id_karyawan_dealer']   = $id_karyawan_dealer;
			$upd_wo['id_karyawan_dealer']        = $id_karyawan_dealer;
		}

		if ($post['grand_total'] == 0) {
			$rsp = [
				'status' => 'error',
				'pesan' => 'Grand Total Kosong !'
			];
			send_json($rsp);
		}
		// if (empty($ins_details)) {
		// 	$rsp = [
		// 		'status' => 'error',
		// 		'pesan' => 'Pekerjaan tambahan untuk work order belum ditentukan !'
		// 	];
		// 	echo json_encode($rsp);
		// 	exit();
		// }
		$result = [
			'post' => $this->input->post(),
			'upd' => $upd_wo,
			'ins_details' => isset($ins_details) ? $ins_details : '',
			'ins_parts' => isset($ins_parts) ? $ins_parts : '',
			'del_part' => isset($del_part) ? $del_part : '',
			'upd_sa_form' => isset($upd_sa_form) ? $upd_sa_form : ''
		];
		if ($login_id == '308') {
			// send_json($result);
		}

		$this->db->trans_begin();
		$this->db->update('tr_h2_wo_dealer', $upd_wo, ['id_work_order' => $post['id_work_order']]);
		if (isset($del_part)) {
			$cond = [
				'id_work_order' => $id_work_order,
			];
			$this->db->where_in('id_jasa', $del_part);
			$this->db->delete('tr_h2_wo_dealer_parts', $cond);
		}
		if (isset($ins_parts)) {
			// send_json($ins_parts);
			$this->db->insert_batch('tr_h2_wo_dealer_parts', $ins_parts);
		}
		if (isset($ins_details)) {
			$this->db->insert_batch('tr_h2_wo_dealer_pekerjaan', $ins_details);
		}
		if (isset($upd_batal)) {
			$this->db->where('id_work_order', $id_work_order);
			$this->db->update_batch('tr_h2_wo_dealer_pekerjaan', $upd_batal, 'id_jasa');

			$this->db->where('id_work_order', $id_work_order);
			$this->db->update_batch('tr_h2_wo_dealer_parts', $upd_batal, 'id_jasa');
		}
		if (isset($upd_sa_form)) {
			$cond = ['id_sa_form' => $wo->id_sa_form];
			$this->db->update('tr_h2_sa_form', $upd_sa_form, $cond);
		}
		// Cek Apakah Dari Customer App. Jika Ya, Cek Apakah Terdapat Part/Pekerjaan Tambahan
		// Cek Apakah Dari Customer App
		$book = $this->db->query("SELECT book.id_booking,book.customer_apps_booking_number
				FROM tr_h2_manage_booking book 
				JOIN tr_h2_sa_form sa ON sa.id_booking=book.id_booking
				JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=sa.id_sa_form
				WHERE wo.id_work_order='$id_work_order' AND IFNULL(customer_apps_booking_number,'')!=''
				")->row();
		if ($book != null) {
			$insert_service_ce_apps = [];
			if (isset($ins_details)) {
				foreach ($ins_details as $det) {
					if ($det['tambahan'] == 1) {
						$js = $this->db->get_where("ms_h2_jasa", ['id_jasa' => $det['id_jasa']])->row();
						$insert_service_ce_apps[] = [
							'ServiceCode' => $det['id_jasa'],
							'ServiceName' => $js->nama_jasa,
							'ServicePrice' => (int)$det['harga']
						];
					}
				}
			}
			$insert_sparepart_ce_apps = [];
			if (isset($ins_parts)) {
				foreach ($ins_parts as $prt) {
					if ($prt['tambahan'] == 1) {
						$part = $this->db->get_where("ms_part", ['id_part' => $prt['id_part']])->row();
						$insert_sparepart_ce_apps[] = [
							'SparepartCode' => $prt['id_part'],
							'SparepartName' => $part->nama_part,
							'SparepartPrice' => (int)$prt['harga']
						];
					}
				}
			}
			$array_post = [
				'AppsBookingNumber'   => $book->customer_apps_booking_number,
				'DmsBookingNumber'    => $book->id_booking,
				'Service'             => $insert_service_ce_apps,
				'Sparepart'           => $insert_sparepart_ce_apps
			];
			$this->load->library('mokita');
			$response_ce_apps = json_decode($this->mokita->h2_sparepart_status($array_post));
			$response_ce_apps_message = $response_ce_apps->msg;
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$rsp = [
				'status' => 'error',
				'pesan' => ' Something went wrong'
			];
		} else {
			$this->db->trans_commit();
			$this->m_wo->updateGrandTotalWO($id_work_order);

			$rsp = [
				'status' => 'sukses',
				'link' => base_url('dealer/work_order_dealer')
			];
						$pesan = "Data berhasil diubah.";
			if (isset($response_ce_apps_message)) {
				$pesan .= " Response CE Apps : $response_ce_apps_message.";
			}
			$_SESSION['pesan'] 	= $pesan;
			$_SESSION['tipe'] 	= "success";
			// echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
		}
		echo json_encode($rsp);
	}

	function cancel_wo()
	{
		$id_work_order = $this->input->get('id');
?>
		<script type="text/javascript">
			let alasan = prompt("Alasan Batal Work Order/ PKB:");
			var id = '<?php echo $id_work_order; ?>';
			if (alasan == null || alasan == "") {
				window.location = '<?= base_url("dealer/work_order_dealer/") ?>';
			} else {
				window.location = '<?= base_url("dealer/work_order_dealer/reason_cancel?id=") ?>' + id + '&reason=' + alasan;

			}
		</script>
<?php
	}

	function reason_cancel()
	{
		$id_work_order = $this->input->get('id');
		$alasan = strtoupper($this->input->get('reason'));
		$date = date('Y-m-d H:i:s');
		$update = $this->db->query("update tr_h2_wo_dealer set status='cancel', alasan_cancel_wo = '$alasan' , tgl_cancel_wo = '$date'  where id_work_order = '$id_work_order'");

		$_SESSION['pesan'] 	= "Data WO berhasil dibatalkan";
		$_SESSION['tipe'] 	= "success";
		echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
	}

	public function fetch()
	{
		$fetch_data = $this->make_query();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_closed = '<a href="dealer/work_order_dealer/closed_wo?id=' . $rs->id_work_order . '" onclick="return confirm(\'Apakah Anda yakin ? \')" class="btn btn-warning btn-xs btn-flat">Closed</a>';
			$btn_print = '<a style="margin-right:1px" href="dealer/work_order_dealer/cetak?id=' . $rs->id_sa_form . '" class="btn btn-success btn-xs btn-flat">Cetak</a>';
			$btn_cetak_dengan_harga = '<a style="margin-top:1px;margin-right:1px" href="dealer/work_order_dealer/cetak_dengan_harga?id=' . $rs->id_sa_form . '" class="btn btn-success btn-xs btn-flat">Cetak Dengan Harga</a>';
			$btn_update = '<a style="margin-top:1px" href="dealer/work_order_dealer/update?id=' . $rs->id_work_order . '" class="btn btn-info btn-xs btn-flat">Update WO</a>';
			$btn_notif = '<a style="margin-top:1px" href="dealer/work_order_dealer/send_notif?id=' . $rs->id_sa_form . '" class="btn btn-warning btn-xs btn-flat">Notify Part Counter</a>';
			$btn_cancel = '<a href="dealer/work_order_dealer/cancel_wo?id=' . $rs->id_work_order . '" onclick="myFunction(' . $rs->id_work_order . ')" class="btn btn-danger btn-xs btn-flat">Cancel</a>';
			$btn_kpb_non_booking_ce_apps = '<a href="dealer/work_order_dealer/kpb_non_booking_ce_apps?id=' . $rs->id_work_order . '" onclick="return confirm(\'Apakah Anda yakin ingin memproses KPB Non Booking ke CE Apps ? \')" class="btn btn-primary btn-xs btn-flat">KPB Non Booking Ke CE Apps</a>';

			if (can_access($this->page, 'can_print'))  $button .= $btn_print . ' ' . $btn_cetak_dengan_harga;
			if ($rs->status_wo == 'open') {
				if (can_access($this->page, 'can_update')) $button .= $btn_update;
				$filter = ['id_sa_form' => $rs->id_sa_form, 'jenis_order' => 'HLO', 'send_notif' => 0];
				$cek_hlo = $this->m_wo->getWONeedParts($filter);
				if ($cek_hlo > 0) {
					if (can_access($this->page, 'can_update')) $button .= $btn_notif;
				}
				if ($rs->last_stats == 'start' || $rs->last_stats == 'resume') {
					$status = '<label class="label label-info">Working</label>';
				} elseif ($rs->last_stats == null) {
					$status = '<label class="label label-primary">Open</label>';
				} elseif ($rs->last_stats == 'end') {
					$status = '<label class="label label-success">End</label></br>
					<label class="label label-success">Ready To Bill</label>';
					$button = '';
				}
				// if ($this->session->userdata('id_user') == 299) {
				// 	$cek_jasa_kpb = $this->m_wo->cek_jasa_kpb_by_wo($rs->id_work_order)->num_rows();
				// 	if ($cek_jasa_kpb > 0 && (string)$rs->id_booking == '') {
				// 		$button .= $btn_kpb_non_booking_ce_apps;
				// 	}
				// }
			} elseif ($rs->status_wo == 'pause') {
				if (can_access($this->page, 'can_update')) $button .= $btn_update;
				$status = '<label class="label label-warning">Pause</label>';
				if ($rs->last_stats == 'end') {
					$status = '<label class="label label-success">End</label></br>
					<label class="label label-success">Ready To Bill</label>';
					$button = '';
				}
			} elseif ($rs->status_wo == 'closed') {
				$status = '<label class="label label-warning">Closed</label>';
			} elseif ($rs->status_wo == 'canceled') {
				$status = '<label class="label label-danger">Canceled</label>';
			} elseif ($rs->status_wo == 'pending') {
				$status = '<label class="label label-danger">Pending</label>';
			}

			$sub_array[] = '<a href="dealer/work_order_dealer/detail?id=' . $rs->id_work_order . '">' . $rs->id_work_order . '</a>';
			if (isset($_POST['history'])) {
				if (can_access($this->page, 'can_print')) $button = $btn_print;
			}

			// penambahan tombol batal wo (sebelum wo closed/ nsc belum terbuat/ pengambilan sparepart tidak ada / sdh diretur)
			$date = date("Y-m-d");
			if ($date >= '2022-06-08') {
				if ($rs->status_wo !== 'closed' || $rs->status_wo != 'cancel') {
					$cek_qty_picking = $this->db->query(
						"
						select a.id_work_order , sum(b.kuantitas - b.kuantitas_return) as qty_picking  
						from tr_h3_dealer_sales_order a 
						join tr_h3_dealer_sales_order_parts b on a.nomor_so = b.nomor_so 
						where id_work_order ='$rs->id_work_order'
						group by id_work_order"
					);

					if ($cek_qty_picking->num_rows() > 0) {
						if ($cek_qty_picking->row()->qty_picking == 0) {
							$button .= $btn_cancel;
						}
					} else {
						$button .= $btn_cancel;
					}
				}
			}

			$sub_array[] = $rs->id_sa_form;
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
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';


		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST['order'] : '',
			'search' => $this->input->post('search')['value'],
			'id_work_order_not_null' => 'y',
		];
		if (isset($_POST['history'])) {
			$filter['order_column'] = 'history';
		}
		if (isset($_POST['status_wo'])) {
			$filter['status_wo'] = $this->input->post('status_wo');
		}
		if ($recordsFiltered == true) {
			// return $this->m_wo->get_sa_form($filter)->num_rows();
			return $this->get_list_wo($filter)->num_rows();
		} else {
			// return $this->m_wo->get_sa_form($filter)->result();
			return $this->get_list_wo($filter)->result();
		}
	}

	public function get_list_wo($filter)
	{
		$id_dealer = '';
		$order_column = array('sa.id_sa_form', 'id_antrian', 'sa.tgl_servis', 'sa.jenis_customer', 'mch.no_polisi', 'mch.nama_customer', 'mch.no_mesin', 'mch.no_rangka', 'tk.id_tipe_kendaraan', 'mch.id_warna', 'mch.tahun_produksi', null);

		if (isset($filter['id_dealer'])) {
			$id_dealer = $filter['id_dealer'];
		} else {
			if (isset($filter['skip_dealer'])) {
				$id_dealer = '';
			} else {
				$id_dealer = $this->m_admin->cari_dealer();
			}
		}

		if ($id_dealer == '') {
			// send_json($filter);
			echo "<script>alert('id dealer tidak boleh kosong, silahkan hubungi MD')</script>";
			die();
		}
		$dealer = '';
		if ($id_dealer != '') {
			$dealer = " AND sa.id_dealer='$id_dealer'";
		}
		$where = "WHERE 1=1 and wo.status not in ('Closed','Canceled','Cancel') $dealer";

		if ($filter == null) {
			$where = "WHERE 1=0";
		}

		$order = "ORDER BY sa.created_at DESC ";
		$limit = '';
		if ($filter != null) {
			if (isset($filter['id_work_order_not_null'])) {
				$where .= " AND wo.id_work_order IS NOT NULL";
				$order = "ORDER BY wo.created_at DESC ";
			}

			if (isset($filter['search'])) {
				$search = $filter['search'];
				if ($search != '') {
					$where .= " AND (mch.nama_customer LIKE '%$search%'
						OR mch.id_customer LIKE '%$search%'
						OR mch.no_mesin LIKE '%$search%'
						OR mch.no_rangka LIKE '%$search%'
						OR mch.no_polisi LIKE '%$search%'
						OR tk.tipe_ahm LIKE '%$search%'
						OR warna LIKE '%$search%'
						OR sa.id_sa_form LIKE '%$search%'
						OR sa.jenis_customer LIKE '%$search%'
						OR wo.id_work_order LIKE '%$search%'
						OR sa.tgl_servis LIKE '%$search%'
						OR sa.jam_servis LIKE '%$search%'
						) 
					";
				}
			}

			if (isset($filter['order'])) {
				if (isset($filter['order_column'])) {
					if ($filter['order_column'] == 'history') {
						$order_column = ['id_work_order', 'sa.id_sa_form', 'sa.tgl_servis', 'sa.jenis_customer', 'mch.no_polisi', 'mch.nama_customer', 'mch.no_mesin', 'mch.no_rangka', 'tk.tipe_ahm', 'ms_warna.warna', 'mch.tahun_produksi', 'wo.status', NULL];
					}
				}
				if ($filter['order'] != '') {
					if ($filter['order'] == 'order_jam_asc') {
						$order = "ORDER BY sa.tgl_servis ASC";
					} else {
						$order = $filter['order'];
						$order_clm  = $order_column[$order[0]['column']];
						$order_by   = $order[0]['dir'];
						$order = " ORDER BY $order_clm $order_by ";
					}
				} else {
					$order = " ORDER BY sa.created_at DESC ";
				}
			} else {
				$order = " ORDER BY sa.created_at DESC ";
			}

			if (isset($filter['order_by'])) {
				$order = "ORDER BY {$filter['order_by']}";
			}

			if (isset($filter['limit'])) {
				if ($filter['limit'] != '') {
					$limit = ' ' . $filter['limit'];
				}
			}

			if (isset($filter['offset'])) {
				$page = $filter['offset'];
				$page = $page < 0 ? 0 : $page;
				$length = $filter['length'];
				$start = $length * $page;
				$start = $page;
				$limit = " LIMIT $start, $length";
			}
		}

		$id_dealer  = $this->m_admin->cari_dealer();
		return $this->db->query("select wo.id_work_order, sa.id_sa_form, DATE_FORMAT(sa.tgl_servis,'%d-%m-%Y') as tgl_servis, 
			sa.jenis_customer, mch.no_polisi, mch.nama_customer,  mch.no_mesin, mch.no_rangka, mch.tahun_produksi, wo.status as status_wo, (SELECT stats FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo.id_work_order ORDER BY set_at DESC LIMIT 1) AS last_stats ,tipe_ahm, warna
			from tr_h2_wo_dealer wo
			join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form 
			join ms_customer_h23 mch on mch.id_customer=sa.id_customer 
			LEFT JOIN ms_tipe_kendaraan AS tk ON mch.id_tipe_kendaraan=tk.id_tipe_kendaraan
			LEFT JOIN ms_warna ON mch.id_warna=ms_warna.id_warna
			$where 
			$order
			$limit
		");


		// where wo.id_dealer = '$id_dealer' and wo.id_work_order IS NOT NULL  and wo.status not in ('Closed','Canceled','Cancel')
		// ORDER BY wo.created_at DESC
		// LIMIT 10
	}

	public function send_notif()
	{
		$data['isi']   = $this->page;
		$data['title'] = 'Notify Parts Counter For Required Parts';
		$data['mode']  = 'send';
		$data['set']   = "send_notif";
		$id_sa_form    = $this->input->get('id');

		$filter['id_sa_form'] = $id_sa_form;
		$sa_form = $this->m_wo->get_sa_form($filter);
		if ($sa_form->num_rows() > 0) {
			$data['row']           = $sa_form->row();
			$filter['jenis_order'] = 'HLO';
			$filter['send_notif']  = 0;
			$filter['group_by_order_to'] = true;
			$order_to = $this->m_wo->getWOParts($filter)->result();
			$id_parts = [];
			foreach ($order_to as  $ord) {
				$filter['order_to'] = $ord->order_to;
				unset($filter['group_by_order_to']);
				$filter['select'] = 'wo_parts';
				$parts = $this->m_wo->getWOParts($filter)->result();
				$parts_order[] = [
					'order_to' => $ord->order_to,
					'order_to_name' => $ord->order_to_name,
					'parts' => $parts
				];
				foreach ($parts as $prt) {
					$id_parts[] = $prt->id_part;
				}
			}
			$data['id_parts'] = $id_parts;
			$data['parts_order'] = $parts_order;
			// send_json($data);
			// $data['id_parts'][] = '0005ZKWWA00';
			$this->template($data);
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}
	function create_cetak()
	{
		$id = $this->input->get('id');
		$params = [
			'id_user' => 0,
			'id_sa_form' => $id,
			'save_server' => true
		];
		$cetak = $this->m_wo->cetak_wo($params);
	}

	public function fetchHistory()
	{
		$fetch_data = $this->make_query_history();
		$data = array();
		foreach ($fetch_data as $rs) {
			$sub_array = array();
			$status = '';
			$button = '';
			$btn_print = '<a style="margin-right:1px" href="dealer/work_order_dealer/cetak?id=' . $rs->id_sa_form . '" class="btn btn-success btn-xs btn-flat">Cetak</a>';
			$btn_cetak_dengan_harga = '<a style="margin-top:1px;margin-right:1px" href="dealer/work_order_dealer/cetak_dengan_harga?id=' . $rs->id_sa_form . '" class="btn btn-success btn-xs btn-flat">Cetak Dengan Harga</a>';

			if ($rs->status == 'closed') {
				$status = '<label class="label label-success">Closed</label>';
			} else if ($rs->status == 'cancel' || $rs->status == 'canceled') {
				$status = '<label class="label label-warning">Cancel</label>';
			}

			$sub_array[] = '<a href="dealer/work_order_dealer/detail?id=' . $rs->id_work_order . '">' . $rs->id_work_order . '</a>';
			if (isset($_POST['history'])) {
				if (can_access($this->page, 'can_print')) $button = $btn_print . ' ' . $btn_cetak_dengan_harga;
			}
			$sub_array[] = $rs->id_sa_form;
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
		$start        = $this->input->post('start');
		$length       = $this->input->post('length');
		$limit        = "LIMIT $start, $length";

		if ($recordsFiltered == true) $limit = '';


		$filter = [
			'limit'  => $limit,
			'order'  => isset($_POST['order']) ? $_POST['order'] : '',
			'search' => $this->input->post('search')['value'],
			'status_in' => "'closed','canceled','cancel'",
			'id_dealer' => $this->m_admin->cari_dealer(),
			'join' => ['customer', 'tipe_kendaraan', 'warna'],
			'select' => 'history'
		];
		if ($recordsFiltered == true) {
			return $this->m_wo->getWorkOrder($filter)->num_rows();
		} else {
			return $this->m_wo->getWorkOrder($filter)->result();
		}
	}

	function injectHargaKPB()
	{
		$kpb = "SELECT harga_jasa FROM ms_kpb_detail WHERE id_tipe_kendaraan=ch23.id_tipe_kendaraan AND kpb_ke=RIGHT(js.id_type,1)";
		// $kpb = 0;
		$data = $this->db->query("SELECT wopk.id_work_order,wopk.harga,ch23.id_tipe_kendaraan,($kpb) harga_kpb,RIGHT(js.id_type,1),wopk.id_jasa
		FROM tr_h2_wo_dealer_pekerjaan wopk 
		JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wopk.id_work_order
		JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
		JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
		JOIN ms_h2_jasa js ON js.id_jasa=wopk.id_jasa 
		WHERE js.id_type IN('ASS1','ASS2','ASS3','ASS4') AND (wopk.harga!=($kpb) OR wopk.subtotal!=($kpb))
		-- LIMIT 200
		");
		$this->db->trans_begin();
		if ($data->num_rows() == 0) {
			send_json('Tidak Ada Data');
		}
		foreach ($data->result() as $dt) {
			$upd = ['harga' => $dt->harga_kpb, 'subtotal' => $dt->harga_kpb];
			$cond = ['id_work_order' => $dt->id_work_order, 'id_jasa' => $dt->id_jasa];
			$this->db->update('tr_h2_wo_dealer_pekerjaan', $upd, $cond);
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
			];
		}
		send_json($rsp);
	}
	public function injectGrandTotalWO()
	{
		$this->db->select('id_work_order,created_at');
		if (isset($_GET['id'])) {
			$this->db->where('id_work_order', $_GET['id']);
		}
		if (isset($_GET['gt'])) {
			$this->db->where('grand_total', 0);
		}
		$this->m_wo->updateGrandTotalWO($_GET['id']);
	}
	function injectNoNSC()
	{
		$get_data = $this->db->query("SELECT id_work_order,wo.no_nsc,nsc.no_nsc no_nsc_real,nsc.created_at created_nsc_at, nsc.created_by created_nsc_by FROM
		 tr_h2_wo_dealer wo 
		 JOIN tr_h23_nsc nsc ON nsc.id_referensi=wo.id_work_order
		 WHERE wo.no_nsc IS NULL");
		if ($get_data->num_rows() > 0) {
			foreach ($get_data->result() as $rs) {
				$data[] = [
					'id_work_order' => $rs->id_work_order,
					'no_nsc' => $rs->no_nsc_real,
					'created_nsc_at' => $rs->created_nsc_at,
					'created_nsc_by' => $rs->created_nsc_by,
				];
			}
		}
		if (isset($_GET['cek'])) {
			send_json($data);
		} else {
			if (isset($data)) {
				$this->db->update_batch('tr_h2_wo_dealer', $data, 'id_work_order');
				echo 'sukses';
			}
		}
	}

	function tesCetakWO()
	{
		$id = $this->input->get('id');
		$wo  = $this->db->get_where('tr_h2_wo_dealer', ['id_work_order' => $id])->row();
		$params = [
			'id_work_order' => $id,
			'id_dealer' => $this->m_admin->cari_dealer(),
			'save_server' => true,
			'id_sa_form' => $wo->id_sa_form
		];
		// send_json($params);
		$this->m_wo->cetak_wo($params);
	}

	function kpb_non_booking_ce_apps()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$id_work_order = $this->input->get('id');
		$filter['id_work_order'] = $id_work_order;
		$row = $this->m_wo->get_sa_form($filter);
		if ($row->num_rows() > 0) {
			$row = $row->row();
			$filter    = ['id_customer' => $row->id_customer];
			$customer  = $this->m_h2->getCustomer23($filter)->row();
			$dealer = $this->db->get_where("ms_dealer", ['id_dealer' => $id_dealer])->row();

			$array_post = [
				"PKBNumber"       => $id_work_order,
				"EngineNumber"    => $customer->no_mesin,
				"AHMCode"         => $dealer->kode_dealer_ahm
			];

			$this->load->library('mokita');
			$response = json_decode($this->mokita->h2_kpb_non_booking($array_post));
			$status = isset($response->status) ? $response->status : $response->Status;
			$message = isset($response->message) ? $response->message : $response->Message;
			if ($status == 1) {
				$_SESSION['pesan'] 	= "Proses KPB Non Booking Untuk CE Apps Berhasil.";
				$_SESSION['tipe'] 	= "success";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
			} else {
				$_SESSION['pesan'] 	= "Proses KPB Non Booking Untuk CE Apps Gagal. <strong><i>($message)</i></strong>";
				$_SESSION['tipe'] 	= "danger";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
			}
		} else {
			$_SESSION['pesan'] 	= "Data not found !";
			$_SESSION['tipe'] 	= "danger";
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
		}
	}
}
