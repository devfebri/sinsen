<?php

class h3_md_sales_order_parts_model extends Honda_Model
{
	protected $table = 'tr_h3_md_sales_order_parts';

	public function set_int_relation($id)
	{
		$data = $this->db
			->select('sop.id')
			->select('p.id_part_int')
			->select('so.id as id_sales_order_int')
			->from(sprintf('%s as sop', $this->table))
			->join('ms_part as p', 'p.id_part = sop.id_part')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
			->limit(1)
			->where('sop.id', $id)
			->get()->row_array();

		if ($data != null) {
			$this->db
				->set('id_part_int', $data['id_part_int'])
				->set('id_sales_order_int', $data['id_sales_order_int'])
				->where('id', $data['id'])
				->update($this->table);
		}
	}

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('harga_setelah_diskon');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
	}

	public function qty_do($id_sales_order, $id_part = null, $sql = false)
	{
		$this->db
			->select('sum(dop.qty_supply) as qty_do')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->where('do.status !=', 'Rejected');

		if ($id_part != null) {
			if ($sql) {
				$this->db->where("dop.id_part = {$id_part}");
			} else {
				$this->db->where('dop.id_part', $id_part);
			}
		}

		if ($sql) {
			$this->db->where("do.id_sales_order = {$id_sales_order}");
			return $this->db->get_compiled_select();
		} else {
			$this->db->where('do.id_sales_order', $id_sales_order);
			$data = $this->db->get()->row_array();

			return $data != null ? $data['qty_do'] : 0;
		}
	}

	public function insert($data)
	{
		parent::insert($data);
		$id = $this->db->insert_id();

		$this->set_int_relation($id);
		$this->set_hpp($data['id_sales_order'], $data['id_part']);
		$this->set_perhitungan_diskon($data['id_sales_order'], $data['id_part']);
		$this->set_harga_setelah_diskon($id);
	}

	public function set_harga_setelah_diskon($id)
	{
		$this->load->helper('harga_setelah_diskon');

		$part = $this->db
			->select('sop.id')
			->select('sop.id_sales_order')
			->select('sop.id_part')
			->select('sop.harga')
			->select('sop.tipe_diskon')
			->select('sop.diskon_value')
			->select('sop.tipe_diskon_campaign')
			->select('sop.diskon_value_campaign')
			->select('
        case
            when sc.id is not null then (sc.jenis_diskon_campaign = "Additional")
            else 0
        end as additional
        ', false)
			->from('tr_h3_md_sales_order_parts as sop')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left')
			->where('sop.id', $id)
			->limit(1)
			->get()->row_array();

		if ($part == null) {
			log_message('debug', sprintf('Data untuk %s tidak ditemukan [%s]', __FUNCTION__, $id));
			return;
		};

		$harga_setelah_diskon = harga_setelah_diskon($part['tipe_diskon'], $part['diskon_value'], $part['harga'], $part['additional'] == 1, $part['tipe_diskon_campaign'], $part['diskon_value_campaign']);
		$this->db
			->set('harga_setelah_diskon', $harga_setelah_diskon)
			->where('id', $part['id'])
			->update('tr_h3_md_sales_order_parts');

		log_message('debug', sprintf('Hitung harga setelah diskon untuk kode part %s di sales order MD %s menjad %s [%s]', $part['id_part'], $part['id_sales_order'], $harga_setelah_diskon, $id));
	}

	public function get_sales_order_parts($id_sales_order)
	{
		$this->load->model('H3_md_ms_sim_part_model', 'sim_part');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
		$this->load->model('dealer_model', 'dealer');

		$qty_actual_dealer = $this->stock_int->qty_actual_dealer('sop.id_part_int', 'so.id_dealer', true);

		$parts = $this->db
			->select('so.id_sales_order')
			->select('so.id_dealer')
			->select('so.produk')
			->select('so.po_type')
			->select('sop.id_part_int')
			->select('sop.id_part')
			->select('p.nama_part')
			->select('sop.id_tipe_kendaraan')
			->select('IFNULL(p.qty_dus, 1) as qty_dus')
			->select('
            CONCAT(
                "Rp ",
                format(sop.harga, 0, "ID_id")
            )
        as harga_dealer_user')
			->select('sop.harga')
			->select("
            IFNULL(
                ({$qty_actual_dealer}), 0
            )
		as qty_actual_dealer")
			->select('sop.qty_order')
			->select('sop.qty_pemenuhan')
			->select('IFNULL(sop.tipe_diskon, "") as tipe_diskon')
			->select('IFNULL(sop.diskon_value, 0) as diskon_value')
			->select('IFNULL(sop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
			->select('IFNULL(sop.diskon_value_campaign, 0) as diskon_value_campaign')
			->select('sop.id_campaign_diskon')
			->select('sc.jenis_diskon_campaign')
			->from('tr_h3_md_sales_order_parts as sop')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
			->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left')
			->where('sop.id_sales_order', $id_sales_order)
			->order_by('sop.id_part', 'asc')
			->get()->result_array();

		$jumlah_dus = $this->get_jumlah_dus($parts);
		$parts = array_map(function ($data) use ($jumlah_dus) {
			$data['qty_on_hand'] = $this->stock_int->qty_on_hand($data['id_part_int']);
			$data['qty_avs'] = $this->stock_int->qty_avs($data['id_part_int'], [], false, $data['po_type'] == 'HLO');
			$data['qty_sim_part'] = $this->sim_part->qty_sim_part($data['id_dealer'], $data['id_part_int']);
			$data['jumlah_dus'] = $jumlah_dus;

			return $data;
		}, $parts);

		return $parts;
	}

	public function hitung_diskon($tipe_diskon, $diskon_value, $harga)
	{
		if ($tipe_diskon == 'Rupiah') {
			return $diskon_value;
		} elseif ($tipe_diskon == 'Persen') {
			return ($diskon_value / 100) * $harga;
		}

		return 0;
	}

	public function set_hpp($id_sales_order, $id_part)
	{
		$part = $this->db
			->select('sop.harga')
			->select('p.harga_md_dealer as hpp')
			->from('tr_h3_md_sales_order_parts as sop')
			->join('ms_part as p', 'p.id_part = sop.id_part')
			->limit(1)
			->where('sop.id_sales_order', $id_sales_order)
			->where('sop.id_part', $id_part)
			->get()->row_array();

		if ($part == null) return;

		$this->db
			->set('hpp', $part['hpp'])
			->where('id_sales_order', $id_sales_order)
			->where('id_part', $id_part)
			->update($this->table);
	}

	public function set_perhitungan_diskon($id_sales_order, $id_part)
	{
		$part = $this->db
			->select('sop.id_sales_order')
			->select('sop.id_part')
			->select('sop.qty_order')
			->select('sop.harga')
			->select('p.harga_md_dealer as hpp')
			->select('sop.tipe_diskon')
			->select('sop.diskon_value')
			->select('sop.tipe_diskon_campaign')
			->select('sop.diskon_value_campaign')
			->select('sop.id_campaign_diskon')
			->select('sc.jenis_diskon_campaign')
			->from('tr_h3_md_sales_order_parts as sop')
			->join('ms_part as p', 'p.id_part = sop.id_part')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left')
			->where('sop.id_sales_order', $id_sales_order)
			->where('sop.id_part', $id_part)
			->limit(1)
			->get()->row_array();

		if ($part == null) return;

		$diskon = $this->sales_order_parts->hitung_diskon($part['tipe_diskon'], $part['diskon_value'], $part['harga']);

		$diskon_campaign = 0;
		if ($part['jenis_diskon_campaign'] == 'Additional') {
			$harga = $part['harga'] - $part['diskon_value_campaign'];
			$diskon_campaign = $this->sales_order_parts->hitung_diskon($part['tipe_diskon_campaign'], $part['diskon_value_campaign'], $harga);
		} else if ($part['jenis_diskon_campaign'] == 'Non Additional') {
			$diskon_campaign = $this->sales_order_parts->hitung_diskon($part['tipe_diskon_campaign'], $part['diskon_value_campaign'], $part['harga']);
		}

		$this->db
			->set('diskon', $diskon)
			->set('diskon_campaign', $diskon_campaign)
			->where('id_sales_order', $id_sales_order)
			->where('id_part', $id_part)
			->update($this->table);
	}

	private function get_jumlah_dus($parts)
	{
		$total_dus = 0;
		foreach ($parts as $part) {
			$total_dus += $part['qty_order'] / $part['qty_dus'];
		}

		return floor($total_dus);
	}

	public function update_harga($id)
	{
		$data = $this->db
			->select('sop.id_sales_order')
			->select('sop.id_part')
			->select('p.id_part_int')
			->select('sop.qty_order as kuantitas')
			->select('sop.hpp')
			->select('p.harga_md_dealer as hpp_terakhir')
			->select('sop.harga')
			->select('p.harga_dealer_user as harga_terakhir')
			->select('sop.harga_setelah_diskon')
			->select('sop.tipe_diskon')
			->select('sop.diskon_value')
			->select('sop.tipe_diskon_campaign')
			->select('sop.diskon_value_campaign')
			->select('sop.id_campaign_diskon')
			->where('sop.id', $id)
			->from(sprintf('%s as sop', $this->table))
			->join('ms_part as p', 'p.id_part = sop.id_part')
			->get()->row_array();

		if ($data == null) return;

		$campaign = null;
		if (isset($data['id_campaign_diskon'])) {
			$campaign = $this->db
				->select('sc.jenis_diskon_campaign')
				->from('ms_h3_md_sales_campaign as sc')
				->where('sc.id', $data['id_campaign_diskon'])
				->get()->row_array();
		}

		$data['harga_setelah_diskon_terakhir'] = harga_setelah_diskon($data['tipe_diskon'], $data['diskon_value'], $data['harga_terakhir'], ($campaign != null and $campaign['jenis_diskon_campaign'] == 'Additional'), $data['tipe_diskon_campaign'], $data['diskon_value_campaign']);

		$this->db
			->set('sop.hpp', $data['hpp_terakhir'])
			->set('sop.harga', $data['harga_terakhir'])
			->set('sop.harga_setelah_diskon', $data['harga_setelah_diskon_terakhir'])
			->where('sop.id', $id)
			->update(sprintf('%s as sop', $this->table));

		log_message('debug', sprintf('[%s] Update harga SO MD %s untuk kode part %s[%s] [payload] %s', $id, $data['id_sales_order'], $data['id_part'], $data['id_part_int'], print_r($data, true)));

		$this->sales_order->update_total_amount($data['id_sales_order']);
	}
}
