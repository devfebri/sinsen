<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_monitoring_picker extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_monitoring_picker";
	protected $title  = "Monitoring Picker";

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

		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('karyawan_md_model', 'karyawan_md');
		$this->load->model('h3_md_do_revisi_model', 'do_revisi');
		$this->load->model('h3_md_do_revisi_item_model', 'do_revisi_item');
		$this->load->model('H3_md_do_revisi_cashback_model', 'do_revisi_cashback');
		$this->load->model('notifikasi_model', 'notifikasi');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function lepas_picker()
	{
		$this->db->trans_start();
		$this->picking_list->update(['id_picker' => null], $this->input->get(['id_picking_list']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Berhasil melepas picker');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->session->set_userdata('pesan', 'Gagal melepas picker');
			$this->session->set_userdata('tipe', 'danger');
		}
		redirect(
			base_url("h3/$this->page")
		);
	}

	public function ready_for_scan()
	{
		$id_picking_list = $this->input->get('id_picking_list');
		$this->db->trans_begin();
			
		try {
			if ($this->picking_list->selisih_validasi($id_picking_list)) {
				$this->picking_list->create_do_revisi_from_validasi($id_picking_list);
			}
			$this->picking_list->ready_for_scanning($id_picking_list);
			$this->picking_list->create_picking_list_ready_to_scan_nofitication($id_picking_list);

			$this->db->trans_commit();

			$this->session->set_userdata('pesan', 'Picking List berhasil ditambahkan ke list Scan Picking List');
			$this->session->set_userdata('tipe', 'info');

		} catch (Exception $e) {
			$this->db->trans_rollback();

			log_message('debug', $e);
			
			$this->session->set_userdata('pesan', $e->getMessage());
			$this->session->set_userdata('tipe', 'danger');
		}

		redirect(
			base_url("h3/$this->page")
		);
	}
}
