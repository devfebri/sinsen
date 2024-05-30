<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_rekap_po_logistik extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_rekap_po_logistik";
	protected $title  = "Rekap PO Logistik";

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
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
		$this->load->model('H3_md_po_logistik_model', 'po_logistik');
		$this->load->model('H3_md_po_logistik_parts_model', 'po_logistik_parts');
		$this->load->model('H3_md_po_logistik_parts_detail_model', 'po_logistik_parts_detail');
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('H3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['dealer_h1'] = $this->db
			->select('d.id_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->from('ms_dealer as d')
			->where('d.kode_dealer_md', 'E20-H1')
			->get()->row_array();

		$this->template($data);
	}

	public function generate_po_logistik()
	{
		$dealer_h1 = $this->db
			->from('ms_dealer as d')
			->where('d.kode_dealer_md', 'E20-H1')
			->get()->row_array();

		if ($dealer_h1 == null) {
			$this->session->unset_userdata('id_checker_selected');

			$this->session->set_flashdata('pesan', 'Dealer H1 Logistik tidak ditemukan.');
			$this->session->set_flashdata('tipe', 'warning');
			redirect(
				base_url('h3/h3_md_rekap_po_logistik')
			);
		}

		if (count($this->session->userdata('id_checker_selected')) < 1) {
			$this->session->set_flashdata('pesan', 'Tidak ada NRFS yang dipilih.');
			$this->session->set_flashdata('tipe', 'warning');
			redirect(
				base_url('h3/h3_md_rekap_po_logistik')
			);
		}

		$purchase_order = [
			'po_id' => $this->purchase_order->generatePONumber('URG', $dealer_h1['id_dealer']),
			'po_type' => 'URG',
			'kategori_po' => 'Non SIM Part',
			'produk' => 'Parts',
			'id_dealer' => $dealer_h1['id_dealer'],
			'tanggal_order' => date('Y-m-d'),
			'status' => 'Processed by MD',
			'po_logistik' => 1,
		];

		$parts = $this->db
			->select('p.id_part_int')
			->select('cd.id_part')
			->select('SUM(cd.qty_order) as kuantitas')
			->select('p.harga_dealer_user as harga_saat_dibeli')
			->select('(SUM(cd.qty_order) * p.harga_dealer_user) as tot_harga_part')
			->from('tr_checker_detail as cd')
			->join('ms_part as p', 'p.id_part = cd.id_part')
			->where_in('cd.id_checker', $this->session->userdata('id_checker_selected'))
			->where('cd.qty_order > 0', null, false)
			->group_by('cd.id_part')
			->get()->result_array();

		$parts = array_map(function ($part) use ($purchase_order) {
			$part['po_id'] = $purchase_order['po_id'];
			return $part;
		}, $parts);

		$pemenuhan_po = array_map(function ($row) use ($purchase_order) {
			return [
				'id_part' => $row['id_part'],
				'po_id' => $purchase_order['po_id']
			];
		}, $parts);

		$total_amount = array_sum(
			array_map(function ($row) {
				return floatval($row['harga_saat_dibeli']) * floatval($row['kuantitas']);
			}, $parts)
		);
		$purchase_order['total_amount'] = $total_amount;

		$parts_detail = $this->db
			->select('cd.id_part')
			->select('c.id_checker as referensi')
			->select('scan_barcode.tipe_motor as type_code', false)
			->select('cd.qty_order as kuantitas')
			->from('tr_checker_detail as cd')
			->join('tr_checker as c', 'c.id_checker = cd.id_checker')
			->join('tr_scan_barcode as scan_barcode', 'scan_barcode.no_mesin = c.no_mesin')
			->join('ms_part as p', 'p.id_part = cd.id_part')
			->where_in('cd.id_checker', $this->session->userdata('id_checker_selected'))
			->where('cd.qty_order > 0', null, false)
			->get()->result_array();

		$parts_detail = array_map(function ($part) use ($purchase_order, $total_amount) {
			$part['id_po_logistik'] = $purchase_order['po_id'];
			return $part;
		}, $parts_detail);

		$this->db->trans_start();
		if (count($parts) > 0) {
			$this->purchase_order->insert($purchase_order);
			$this->purchase_order_parts->insert_batch($parts);

			$tracking_parts = $this->getOnly(['po_id', 'id_part'], $parts);
			$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
			foreach ($tracking_parts as $part) {
				$order_part_tracking = $this->order_parts_tracking->get($part, true);
				if ($order_part_tracking == null) {
					$this->order_parts_tracking->insert($part);
				}
			}

			$this->pemenuhan_po->insert_batch($pemenuhan_po);

			$this->po_logistik_parts_detail->insert_batch($parts_detail);

			foreach ($this->session->userdata('id_checker_selected') as $id_checker) {
				$this->db
					->set('c.po_id_dealer_h3', $purchase_order['po_id'])
					->set('c.status_checker', 'H3-Processed')
					->where('c.id_checker', $id_checker)
					->update('tr_checker as c');
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->unset_userdata('id_checker_selected');

			$this->session->set_flashdata('pesan', 'Rekap PO logistik berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
		} else {
			$this->session->set_flashdata('pesan', 'Rekap PO logistik tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'warning');
		}
		redirect(
			base_url("h3/h3_md_pemenuhan_po_urgent_dealer/detail?id={$purchase_order['po_id']}")
		);
	}
}
