<?php

class h3_md_sales_order_model extends Honda_Model
{

	protected $table = 'tr_h3_md_sales_order';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->helper('get_diskon_part');
	}

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'New SO';

		parent::insert($data);
	}

	public function get_sales_order($id_sales_order)
	{
		$this->load->model('H3_md_ms_plafon_model', 'plafon');

		$sales_order = $this->db
			->select('so.id_sales_order')
			->select('so.jenis_pembayaran')
			->select('so.batas_waktu')
			->select('so.created_by_md')
			->select('so.id_ref')
			->select('so.tanggal_order')
			->select('d.id_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('so.po_type')
			->select('so.batas_waktu')
			->select('so.kategori_po')
			->select('so.produk')
			->select('so.id_salesman')
			->select('so.tipe_source')
			->select('so.target_customer')
			->select('so.sales_order_target')
			->select('so.persentase_sales_order_target')
			->select('so.sales_order_out_target')
			->select('so.persentase_sales_order_out_target')
			->select('k.nama_lengkap as nama_salesman')
			->select('so.status')
			->select('so.gimmick')
			->select('so.no_bapb')
			->select('so.autofulfillment_md')
			->select('so.created_by_md')
			->select('so.id_rekap_purchase_order_dealer')
			->select('so.from_upload')
			->select('so.is_hadiah')
			->select('so.is_ev')
			->select('po_aksesoris.id_paket_bundling')
			->select('wpi.id_wilayah_penagihan is not null as ada_wilayah_penagihan', false)
			->from('tr_h3_md_sales_order as so')
			->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->join('tr_po_aksesoris as po_aksesoris', 'po_aksesoris.no_po_aksesoris = so.referensi_po_bundling', 'left')
			->join('ms_h3_md_wilayah_penagihan_item as wpi', 'wpi.id_dealer = d.id_dealer', 'left')
			->where('so.id_sales_order', $id_sales_order)
			->get()->row_array();
		$sales_order['plafon'] = $this->plafon->get_plafon($sales_order['id_dealer'], $sales_order['gimmick'], $sales_order['kategori_po'], $sales_order['id_sales_order']);
		$sales_order['plafon_booking'] = $this->plafon->get_plafon_booking($sales_order['id_dealer'], $sales_order['gimmick'], $sales_order['kategori_po']);
		$sales_order['plafon_yang_dipakai'] = $this->plafon->get_plafon_terpakai($sales_order['id_dealer'], $sales_order['gimmick'], $sales_order['kategori_po']);

		return $sales_order;
	}

	public function update_total($id_sales_order)
	{
		$total_parts = $this->get_total_parts($id_sales_order);

		return $this->update([
			'total_amount' => $total_parts,
		], [
			'id_sales_order' => $id_sales_order
		]);
	}

	public function get_total_parts($id_sales_order)
	{
		$parts = $this->sales_order_parts->get_sales_order_parts($id_sales_order);

		$parts = array_map(function ($data) {
			$data['harga_setelah_diskon'] = $this->harga_setelah_diskon($data);
			$data['amount'] = $this->amount($data);
			return $data;
		}, $parts);

		$parts = array_map(function ($data) {
			return $this->amount($data);
		}, $parts);

		return array_sum($parts);
	}

	public function get_amount_so($id_sales_order)
	{
		$data = $this->db
			->select('sum(so.total_amount) as total_amount')
			->from('tr_h3_md_sales_order as so')
			->where('so.id_sales_order', $id_sales_order)
			->get()->row_array();

		return $data != null ? $data['total_amount'] : 0;
	}

	public function get_amount_do($id_sales_order)
	{
		$data = $this->db
			->select('sum(do.total) as total')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_sales_order', $id_sales_order)
			->get()->row_array();

		return $data != null ? $data['total'] : 0;
	}

	public function get_amount_do_sudah_create_faktur($id_sales_order)
	{
		$data = $this->db
			->select('sum(do.sub_total) as sub_total')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_sales_order', $id_sales_order)
			->where('do.sudah_create_faktur', 1)
			->get()->row_array();

		return $data != null ? $data['sub_total'] : 0;
	}

	public function get_sub_total_do($id_sales_order)
	{
		$data = $this->db
			->select('sum(do.sub_total) as total')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_sales_order', $id_sales_order)
			->group_start()
			->where('do.status !=', 'Canceled')
			->where('do.status !=', 'Rejected')
			->group_end()
			->get()->row_array();

		return $data != null ? $data['total'] : 0;
	}

	public function harga_setelah_diskon($part)
	{
		return $part['harga'] -
			$this->calculate_discount($part['diskon_value'], $part['tipe_diskon'], $part['harga']) -
			$this->calculate_discount($part['diskon_value_campaign'], $part['tipe_diskon_campaign'], $part['harga']);
	}

	public function calculate_discount($discount, $tipe_diskon, $price)
	{
		if ($tipe_diskon == 'Persen') {
			if ($discount == 0) return 0;
			return $discount = ($discount / 100) * $price;
		} else if ($tipe_diskon == 'Rupiah') {
			return $discount;
		}
		return 0;
	}

	public function amount($part)
	{
		return $this->harga_setelah_diskon($part) * $part['qty_order'];
	}

	public function generateID($tipe_po, $id_dealer, $time = null, $gimmick = false, $kpb = false)
	{
		if ($time == null) {
			$th        = date('Y');
			$bln       = date('m');
			$th_bln    = date('Y-m');
			$thbln     = date('ym');
		} else {
			$th        = date('Y', strtotime($time));
			$bln       = date('m', strtotime($time));
			$th_bln    = date('Y-m', strtotime($time));
			$thbln     = date('ym', strtotime($time));
		}

		$dealer = $this->db
			->select('d.kode_dealer_md')
			->from('ms_dealer as d')
			->where('d.id_dealer', $id_dealer)
			->get()->row();

		$query = $this->db
			->from($this->table)
			->where("LEFT(tanggal_order, 7)='{$th_bln}'")
			->order_by('id', 'DESC')
			->limit(1)
			->where('po_type', $tipe_po)
			->where('id_dealer', $id_dealer)
			->where('created_at > ', '2020-06-29 15:32:00')
			->order_by('created_at', 'desc')
			->get();

		if ($query->num_rows() > 0) {
			$row        = $query->row();
			$id_sales_order = substr($row->id_sales_order, 0, 5);
			$id_sales_order = sprintf("%'.05d", $id_sales_order + 1);
			$id   = "{$id_sales_order}/SO-{$tipe_po}/{$dealer->kode_dealer_md}/{$bln}/{$th}";
		} else {
			$id   = "00001/SO-{$tipe_po}/{$dealer->kode_dealer_md}/{$bln}/{$th}";
		}

		if ($gimmick) {
			$id .= '/FGD';
		} else if ($kpb) {
			$id .= '/KPB';
		}

		return strtoupper($id);
	}

	public function update_harga($id_part_int)
	{
		$data_part = $this->db
			->select('p.id_part')
			->where('p.id_part_int', $id_part_int)
			->from('ms_part as p')
			->limit(1)
			->get()->row_array();

		if ($data_part == null) return;

		$qty_sudah_do = $this->db
			->select('SUM(dop.qty_supply) as kuantitas', false)
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->where('do.status !=', 'Canceled')
			->where('do.id_sales_order = sop.id_sales_order', null, false)
			->where('dop.id_part = sop.id_part', null, false)
			->get_compiled_select();

		$this->db
			->select('sop.id')
			->select('sop.id_sales_order')
			->from('tr_h3_md_sales_order_parts as sop')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
			->where('sop.id_part', $data_part['id_part'])
			->group_start()
			->where('so.status !=', 'Canceled')
			->where('so.status !=', 'Closed')
			->group_end()
			->where("sop.qty_order > IFNULL(({$qty_sudah_do}), 0)", null, false);

		foreach ($this->db->get()->result_array() as $part) {
			$this->sales_order_parts->update_harga($part['id']);
		}
	}

	public function update_total_amount($id_sales_order)
	{
		$parts = $this->db
			->select('(sop.qty_order * sop.harga_setelah_diskon) as total_harga_part', false)
			->where('sop.id_sales_order', $id_sales_order)
			->from('tr_h3_md_sales_order_parts as sop')
			->get()->result_array();

		log_message('debug', sprintf('Parts untuk SO MD %s adalah %s', $id_sales_order, print_r($parts, true)));

		$total_amount = array_sum(
			array_column($parts, 'total_harga_part')
		);

		$updated = $this->db
			->set('so.total_amount', $total_amount)
			->where('so.id_sales_order', $id_sales_order)
			->update(sprintf('%s as so', $this->table));

		if ($updated) log_message('debug', sprintf('Update total amount SO MD [%s] menjadi %s', $id_sales_order, $total_amount));
	}

	public function update_diskon_sales_order()
	{
		$this->db
			->select('so.id')
			->select('so.id_dealer')
			->select('so.id_sales_order')
			->select('so.po_type')
			->select('so.produk')
			->select('so.kategori_po')
			->select('so.status')
			->from(sprintf('%s as so', $this->table))
			->group_start()
			->where('so.status !=', 'Canceled')
			->where('so.status !=', 'Closed')
			->group_end();

		foreach ($this->db->get()->result_array() as $sales_order) {
			$this->update_diskon($sales_order['id_sales_order']);
		}
	}

	public function update_diskon($id_sales_order)
	{
		$sales_order = $this->db
			->select('so.id')
			->select('so.id_dealer')
			->select('so.id_sales_order')
			->select('so.po_type')
			->select('so.produk')
			->select('so.kategori_po')
			->select('so.status')
			->from(sprintf('%s as so', $this->table))
			->where('so.id_sales_order', $id_sales_order)
			->get()->row_array();

		if ($sales_order == null) throw new Exception(sprintf('Sales order dengan nomor %s tidak ditemukan', $id_sales_order));

		log_message('debug', sprintf('Memperbarui diskon untuk SO MD dengan nomor %s bertipe %s dan status %s [%s]', $sales_order['id_sales_order'], $sales_order['po_type'], $sales_order['status'], $sales_order['id']));

		$qty_sudah_do = $this->db
			->select('SUM(dop.qty_supply) as kuantitas', false)
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->where('do.status !=', 'Canceled')
			->where('do.sudah_create_faktur', 1)
			->where('do.id_sales_order = sop.id_sales_order', null, false)
			->where('dop.id_part = sop.id_part', null, false)
			->get_compiled_select();

		$parts = $this->db
			->select('sop.id')
			->select('sop.id_part')
			->select('sop.id_tipe_kendaraan')
			->select('sop.tipe_diskon')
			->select('sop.diskon_value')
			->select('sop.qty_order as kuantitas')
			->select('sop.tipe_diskon_campaign')
			->select('sop.diskon_value_campaign')
			->select('sop.id_campaign_diskon')
			->select('sop.jenis_diskon_campaign')
			->from('tr_h3_md_sales_order_parts as sop')
			->where('sop.id_sales_order', $sales_order['id_sales_order'])
			->where("sop.qty_order > IFNULL(({$qty_sudah_do}), 0)", null, false)
			->get()->result_array();

		log_message('debug', sprintf('Parts untuk nomor SO %s [parts] %s', $sales_order['id_sales_order'], print_r($parts, true)));

		$parts = get_diskon_part($sales_order['id_dealer'], $sales_order['po_type'], $sales_order['produk'], $sales_order['kategori_po'], $parts);

		foreach ($parts as $part) {
			$this->db
				->set('sop.tipe_diskon', $part['tipe_diskon'])
				->set('sop.diskon_value', $part['diskon_value'])
				->set('sop.tipe_diskon_campaign', $part['tipe_diskon_campaign'])
				->set('sop.diskon_value_campaign', $part['diskon_value_campaign'])
				->set('sop.id_campaign_diskon', $part['id_campaign_diskon'])
				->set('sop.jenis_diskon_campaign', $part['jenis_diskon_campaign'])
				->where('sop.id', $part['id'])
				->update('tr_h3_md_sales_order_parts as sop');
			$this->sales_order_parts->update_harga($part['id']);
		}
	}

	public function check_kuantitas_do($id, $kuantitas_akan_dideliver)
	{
		$sales_order = $this->db
			->select('id_sales_order')
			->where('id', $id)
			->from($this->table)
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception('Sales order tidak ditemukan', 404);

		$delivered_parts = $this->db
			->select('dop.qty_supply as kuantitas')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order_int = do.id')
			->where('do.id_sales_order', $sales_order['id_sales_order'])
			->group_start()
			->where('do.status !=', 'Canceled')
			->where('do.status !=', 'Rejected')
			->group_end()
			->get()->result_array();

		$total_kuantitas_delivered_parts = array_sum(
			array_map(function ($row) {
				return (int) $row['kuantitas'];
			}, $delivered_parts)
		);

		$sales_order_parts = $this->db
			->select('sop.qty_order as kuantitas')
			->from('tr_h3_md_sales_order_parts as sop')
			->where('sop.id_sales_order', $sales_order['id_sales_order'])
			->get()->result_array();

		$total_kuantitas_sales_order_parts = array_sum(
			array_map(function ($row) {
				return (int) $row['kuantitas'];
			}, $sales_order_parts)
		);

		if ($total_kuantitas_sales_order_parts < ($total_kuantitas_delivered_parts + $kuantitas_akan_dideliver)) {
			throw new Exception('Kuantitas yang akan dibuatkan DO melebihi kuantitas SO', 403);
		}
	}

	public function set_new_so_bo($id)
	{
		$sales_order = $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception('Sales order tidak ditemukan');

		$updated = $this->update(['status' => 'New SO BO'], ['id' => $id]);
		log_message('info', sprintf('Status sales order %s diubah menjadi New SO BO', $sales_order['id_sales_order']));

		return $updated;
	}

	public function set_new_so($id)
	{
		$sales_order = $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception('Sales order tidak ditemukan');

		$updated = $this->update(['status' => 'New SO'], ['id' => $id]);
		log_message('info', sprintf('Status sales order %s diubah menjadi New SO', $sales_order['id_sales_order']));

		return $updated;
	}
}
