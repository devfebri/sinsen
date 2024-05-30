<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_diskon_oli_reguler extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_diskon_oli_reguler";
	protected $title  = "Diskon Oli Reguler";

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

		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$this->load->model('h3_md_diskon_oli_reguler_item_model', 'diskon_oli_reguler_item');
		$this->load->model('h3_md_diskon_oli_reguler_ranges_model', 'diskon_oli_reguler_ranges');
		$this->load->model('H3_md_diskon_oli_reguler_general_ranges_model', 'diskon_oli_reguler_general_ranges');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
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

		$diskon_oli_reguler_data = $this->input->post(['active', 'id_part']);

		$this->db->trans_start();
		$this->diskon_oli_reguler->insert($diskon_oli_reguler_data);
		$id_diskon_oli_reguler = $this->db->insert_id();

		$general_ranges = $this->input->post('general_ranges');
		if (count($general_ranges)) {
			$general_ranges = $this->getOnly(['id_range_dus_oli', 'tipe_diskon', 'diskon_value'], $general_ranges, [
				'id_diskon_oli_reguler' => $id_diskon_oli_reguler
			]);
			$this->diskon_oli_reguler_general_ranges->insert_batch($general_ranges);
		}

		$items = $this->input->post('dealers');
		if (count($items) > 0) {
			foreach ($items as $item) {
				$data = [];
				$data['id_diskon_oli_reguler'] = $id_diskon_oli_reguler;
				$data['id_dealer'] = $item['id_dealer'];
				$this->diskon_oli_reguler_item->insert($data);
				$id_diskon_oli_reguler_item = $this->db->insert_id();

				if (isset($item['ranges'])) {
					$ranges = $this->getOnly([
						'tipe_diskon', 'diskon_value', 'id_range_dus_oli'
					], $item['ranges'], [
						'id_diskon_oli_reguler_item' => $id_diskon_oli_reguler_item
					]);
					$this->diskon_oli_reguler_ranges->insert_batch($ranges);
				}
			}
		}
		$this->db->trans_complete();

		$diskon = $this->diskon_oli_reguler->find($id_diskon_oli_reguler, 'id');

		if ($this->db->trans_status()) {
			send_json([
				'redirect_url' => sprintf('h3/h3_md_diskon_oli_reguler/detail?id=%s', $diskon->id)
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan diskon'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['diskon_oli_reguler'] = $this->db
			->select('dor.id')
			->select('dor.id_part')
			->select('p.nama_part')
			->select('p.harga_dealer_user')
			->select('p.kelompok_part')
			->select('p.status')
			->select('dor.active')
			->from('ms_h3_md_diskon_oli_reguler as dor')
			->join('ms_part as p', 'p.id_part = dor.id_part')
			->where('dor.id', $this->input->get('id'))
			->get()->row();

		$data['general_ranges'] = $this->db
			->select('dorgr.*')
			->select('rdo.kode_range')
			->select('rdo.range_start')
			->select('rdo.range_end')
			->from('ms_h3_md_diskon_oli_reguler_general_ranges as dorgr')
			->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorgr.id_range_dus_oli')
			->where('dorgr.id_diskon_oli_reguler', $this->input->get('id'))
			->get()->result_array();

		$dealers = $this->db
			->select('dori.*')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('kab.kabupaten')
			->select('kab.id_kabupaten')
			->from('ms_h3_md_diskon_oli_reguler_item as dori')
			->join('ms_dealer as d', 'd.id_dealer = dori.id_dealer')
			->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
			->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
			->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
			->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
			->where('dori.id_diskon_oli_reguler', $this->input->get('id'))
			->get()->result_array();

		$items = [];
		foreach ($dealers as $dealer) {
			$dealer['ranges'] = $this->db
				->select('dorr.*')
				->select('rdo.kode_range')
				->select('rdo.range_start')
				->select('rdo.range_end')
				->from('ms_h3_md_diskon_oli_reguler_ranges as dorr')
				->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorr.id_range_dus_oli')
				->where('dorr.id_diskon_oli_reguler_item', $dealer['id'])
				->get()->result_array();

			$items[] = $dealer;
		}
		$data['items'] = $items;

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['diskon_oli_reguler'] = $this->db
			->select('dor.id')
			->select('dor.id_part')
			->select('p.nama_part')
			->select('p.harga_dealer_user')
			->select('p.kelompok_part')
			->select('p.status')
			->select('dor.active')
			->from('ms_h3_md_diskon_oli_reguler as dor')
			->join('ms_part as p', 'p.id_part = dor.id_part')
			->where('dor.id', $this->input->get('id'))
			->get()->row();

		$data['general_ranges'] = $this->db
			->select('dorgr.*')
			->select('rdo.kode_range')
			->select('rdo.range_start')
			->select('rdo.range_end')
			->from('ms_h3_md_diskon_oli_reguler_general_ranges as dorgr')
			->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorgr.id_range_dus_oli')
			->where('dorgr.id_diskon_oli_reguler', $this->input->get('id'))
			->get()->result_array();

		$dealers = $this->db
			->select('dori.*')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('kab.kabupaten')
			->select('kab.id_kabupaten')
			->from('ms_h3_md_diskon_oli_reguler_item as dori')
			->join('ms_dealer as d', 'd.id_dealer = dori.id_dealer')
			->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
			->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
			->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
			->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
			->where('dori.id_diskon_oli_reguler', $this->input->get('id'))
			->get()->result_array();

		$items = [];
		foreach ($dealers as $dealer) {
			$dealer['ranges'] = $this->db
				->select('dorr.*')
				->select('rdo.kode_range')
				->select('rdo.range_start')
				->select('rdo.range_end')
				->from('ms_h3_md_diskon_oli_reguler_ranges as dorr')
				->join('ms_h3_md_range_dus_oli as rdo', 'rdo.id = dorr.id_range_dus_oli')
				->where('dorr.id_diskon_oli_reguler_item', $dealer['id'])
				->get()->result_array();

			$items[] = $dealer;
		}
		$data['items'] = $items;

		$this->template($data);
	}

	public function update()
	{
		$this->validate();
		$this->db->trans_start();
		$diskon_oli_reguler_data = $this->input->post(['active', 'id_part']);

		$this->diskon_oli_reguler->update($diskon_oli_reguler_data, $this->input->post(['id']));

		$this->diskon_oli_reguler_general_ranges->delete($this->input->post('id'), 'id_diskon_oli_reguler');
		$general_ranges = $this->input->post('general_ranges');
		if (count($general_ranges)) {
			$general_ranges = $this->getOnly(['id_range_dus_oli', 'tipe_diskon', 'diskon_value'], $general_ranges, [
				'id_diskon_oli_reguler' => $this->input->post('id')
			]);
			$this->diskon_oli_reguler_general_ranges->insert_batch($general_ranges);
		}

		$id_items_diskon_oli_reguler = $this->db
			->select('dori.id')
			->from('ms_h3_md_diskon_oli_reguler_item as dori')
			->where('dori.id_diskon_oli_reguler', $this->input->post('id'))
			->get()->result_array();
		$id_items_diskon_oli_reguler = array_map(function ($data) {
			return $data['id'];
		}, $id_items_diskon_oli_reguler);

		if (count($id_items_diskon_oli_reguler)) {
			$this->db->where_in('id_diskon_oli_reguler_item', $id_items_diskon_oli_reguler)->delete('ms_h3_md_diskon_oli_reguler_ranges');
		}

		$this->diskon_oli_reguler_item->delete($this->input->post('id'), 'id_diskon_oli_reguler');
		$items = $this->input->post('dealers');
		if (count($items) > 0) {
			foreach ($items as $item) {
				$data = [];
				$data['id_diskon_oli_reguler'] = $this->input->post('id');
				$data['id_dealer'] = $item['id_dealer'];
				$this->diskon_oli_reguler_item->insert($data);
				$id_diskon_oli_reguler_item = $this->db->insert_id();

				$this->diskon_oli_reguler_ranges->delete($id_diskon_oli_reguler_item, 'id_diskon_oli_reguler_item');
				if (isset($item['ranges'])) {
					$ranges = $this->getOnly([
						'tipe_diskon', 'diskon_value', 'id_range_dus_oli'
					], $item['ranges'], [
						'id_diskon_oli_reguler_item' => $id_diskon_oli_reguler_item
					]);
					$this->diskon_oli_reguler_ranges->insert_batch($ranges);
				}
			}
		}
		$this->db->trans_complete();

		$diskon = $this->diskon_oli_reguler->find($this->input->post('id'));

		if ($this->db->trans_status()) {
			send_json([
				'redirect_url' => sprintf('h3/h3_md_diskon_oli_reguler/detail?id=%s', $diskon->id)
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil memperbarui diskon'
			], 422);
		}
	}

	public function get_parts_diskon_oli_reguler()
	{
		$result = [];
		$jumlah_dus = $this->get_jumlah_dus($this->input->post('parts'));
		foreach ($this->input->post('parts') as $part) {
			$result[] = $this->diskon_oli_reguler->get_diskon($part['id_part'], $this->input->post('id_dealer'), $jumlah_dus);
		}
		send_json($result);
	}

	private function get_jumlah_dus($parts)
	{
		$total_dus = 0;
		foreach ($parts as $part) {
			$data_part = $this->db
				->select('IFNULL(p.qty_dus, 1) as qty_dus')
				->from('ms_part as p')
				->where('p.id_part', $part['id_part'])
				->get()->row_array();
			$total_dus += $part['kuantitas'] / $data_part['qty_dus'];
		}

		return floor($total_dus);
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_part', 'Part Number', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
}
