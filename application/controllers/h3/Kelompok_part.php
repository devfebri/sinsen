<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_part extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "kelompok_part";
	protected $title  = "Kelompok Part";

	public function __construct()
	{
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('m_part');
		$this->load->model('kelompok_part_model', 'kelompok_part');
		$this->load->model('kelompok_part_item_model', 'kelompok_part_item');
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
	}

	public function index()
	{
		$data['set']	= "index";
		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save()
	{
		$this->validate();

		$data = $this->input->post([
			'id_kelompok_part', 'kelompok_part', 'start_date', 'end_date',
			'proses_barang_bagi', 'keep_stock_toko', 'keep_stock_dealer', 'keep_stock_dealer_Fix', 'keep_stock_hotline',
			'active', 'include_ppn', 'plastik_part'
		]);
		$kelompok_part = $this->clean_data($data);

		$this->db->trans_start();
		$this->kelompok_part->insert($kelompok_part);
		$id = $this->db->insert_id();
		$items = $this->getOnly(['id_part', 'id_part_int', 'qty_keep_stock'], $this->input->post('items'), [
			'id_kelompok' => $id
		]);
		if (count($items) > 0) {
			$this->kelompok_part_item->insert_batch($items);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$kelompok_part = $this->kelompok_part->find($id);
			send_json([
				'redirect_url' => base_url(sprintf('h3/kelompok_part/detail?id=%s', $kelompok_part->id)),
				'payload' => $kelompok_part,
				'message' => 'Berhasil menyimpan kelompok part'
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan kelompok part'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['kelompok_part'] = $this->kelompok_part->find($this->input->get('id'));

		$stock_on_hand = $this->db
			->select('sum(sp.qty)')
			->from('tr_stok_part as sp')
			->where('sp.id_part_int = p.id_part_int')
			->group_by('sp.id_part')
			->get_compiled_select();

		$penjualan_parts = [];
		for ($i = 0; $i < 6; $i++) {
			$start_date_index = ($i + 1) * 30;
			$end_date_index = $i * 30;
			$start_date = date('Y-m-d', strtotime("-{$start_date_index} days", time()));
			$end_date = date('Y-m-d', strtotime("-{$end_date_index} days", time()));

			$parts_terjual = $this->db
				->select('sum(sop.qty_order)')
				->from('tr_h3_md_sales_order_parts as sop')
				->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
				->where('sop.id_part_int = p.id_part_int')
				->group_start()
				->where('so.tanggal_order >', $start_date)
				->where('so.tanggal_order <=', $end_date)
				->group_end()
				->group_by('sop.id_part')
				->get_compiled_select();

			$penjualan_parts[] = $parts_terjual;
		}

		$this->db
			->select('kpi.id_part_int')
			->select('p.id_part')
			->select('p.nama_part')
			->select('p.status')
			->select('concat(
            "Rp ",
            format(p.harga_dealer_user, 0, "ID_id")
        ) as het')
			->select("
            ifnull(
                ({$stock_on_hand}),
                0
			) as stock_avs")
			->select('kpi.qty_keep_stock')
			->from('ms_kelompok_part_item as kpi')
			->join('ms_part as p', 'p.id_part_int = kpi.id_part_int')
			->where('kpi.id_kelompok', $this->input->get('id'));

		foreach ($penjualan_parts as $index => $query_penjualan) {
			$bulan_penjualan = $index + 1;
			$this->db->select("
            ifnull(
                ({$query_penjualan}),
                0
            ) as m_{$bulan_penjualan}");
		}

		$data['items'] = $this->db->get()->result();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['kelompok_part'] = $this->kelompok_part->find($this->input->get('id'));

		$stock_on_hand = $this->db
			->select('sum(sp.qty)')
			->from('tr_stok_part as sp')
			->where('sp.id_part_int = p.id_part_int')
			->group_by('sp.id_part')
			->get_compiled_select();

		$penjualan_parts = [];
		for ($i = 0; $i < 6; $i++) {
			$start_date_index = ($i + 1) * 30;
			$end_date_index = $i * 30;
			$start_date = date('Y-m-d', strtotime("-{$start_date_index} days", time()));
			$end_date = date('Y-m-d', strtotime("-{$end_date_index} days", time()));

			$parts_terjual = $this->db
				->select('sum(sop.qty_order)')
				->from('tr_h3_md_sales_order_parts as sop')
				->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
				->where('sop.id_part_int = p.id_part_int')
				->group_start()
				->where('so.tanggal_order >', $start_date)
				->where('so.tanggal_order <=', $end_date)
				->group_end()
				->group_by('sop.id_part')
				->get_compiled_select();

			$penjualan_parts[] = $parts_terjual;
		}

		$this->db
			->select('kpi.id_part_int')
			->select('p.id_part')
			->select('p.nama_part')
			->select('p.status')
			->select('concat(
            "Rp ",
            format(p.harga_dealer_user, 0, "ID_id")
        ) as het')
			->select("
            ifnull(
                ({$stock_on_hand}),
                0
			) as stock_avs")
			->select('kpi.qty_keep_stock')
			->from('ms_kelompok_part_item as kpi')
			->join('ms_part as p', 'p.id_part_int = kpi.id_part_int')
			->where('kpi.id_kelompok', $this->input->get('id'));

		foreach ($penjualan_parts as $index => $query_penjualan) {
			$bulan_penjualan = $index + 1;
			$this->db->select("
            ifnull(
                ({$query_penjualan}),
                0
            ) as m_{$bulan_penjualan}");
		}

		$data['items'] = $this->db->get()->result();
		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();
		$this->validate();

		$data = $this->input->post([
			'id_kelompok_part', 'kelompok_part', 'start_date', 'end_date',
			'proses_barang_bagi', 'keep_stock_toko', 'keep_stock_dealer', 'keep_stock_dealer_fix', 'keep_stock_hotline',
			'active', 'include_ppn', 'plastik_part'
		]);
		$data = $this->clean_data($data);

		$this->kelompok_part->update($data, $this->input->post(['id']));
		$items = $this->getOnly(['id_part', 'id_part_int', 'qty_keep_stock'], $this->input->post('items'), [
			'id_kelompok' => $this->input->post('id')
		]);
		$this->kelompok_part_item->delete($this->input->post('id'), 'id_kelompok');
		if (count($items) > 0) {
			$this->kelompok_part_item->insert_batch($items);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$kelompok_part = $this->kelompok_part->find($this->input->post('id'));
			send_json([
				'redirect_url' => base_url(sprintf('h3/kelompok_part/detail?id=%s', $kelompok_part->id)),
				'payload' => $kelompok_part,
				'message' => 'Berhasil menperbarui kelompok part'
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menperbarui kelompok part'
			], 422);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(3) == 'update') {
			$kelompok_part = $this->kelompok_part->find($this->input->post('id'));

			if (
				!($kelompok_part->id_kelompok_part == $this->input->post('id_kelompok_part'))
			) {
				$this->form_validation->set_rules('id_kelompok_part', 'ID Kelompok Part', 'required|is_unique[ms_kelompok_part.id_kelompok_part]');
			}
		} else {
			$this->form_validation->set_rules('id_kelompok_part', 'ID Kelompok Part', 'required|is_unique[ms_kelompok_part.id_kelompok_part]');
		}
		$this->form_validation->set_rules('kelompok_part', 'Kelompok Part', 'required');
		$this->form_validation->set_rules('proses_barang_bagi', 'Proses Barang Bagi', 'required');
		$this->form_validation->set_rules('keep_stock_toko', 'Keep Stock Toko (%)', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
		$this->form_validation->set_rules('keep_stock_dealer', 'Keep Stock Dealer (%)', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
		$this->form_validation->set_rules('keep_stock_dealer_fix', 'Keep Stock Dealer (%)', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
}
