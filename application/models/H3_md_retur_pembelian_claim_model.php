<?php

class H3_md_retur_pembelian_claim_model extends Honda_Model
{

	protected $table = 'tr_h3_md_retur_pembelian_claim';

	public function __construct()
	{
		parent::__construct();
	}

	public function proses($no_retur)
	{
		$this->update([
			'status' => 'Processed',
			'proses_at' => Mcarbon::now()->toDateTimeString(),
			'proses_by' => $this->session->userdata('id_user')
		], ['no_retur' => $no_retur]);

		$this->potong_stock($no_retur);
		$this->create_ar_ke_ahm($no_retur);
		$this->item_claim_ahm_diproses($no_retur);
	}

	private function potong_stock($no_retur)
	{
		$this->load->model('H3_md_kartu_stock_model', 'kartu_stock');

		$item_retur_pembelian_claim = $this->db
			->select('rpcai.no_retur')
			->select('rpcai.id_part')
			->select('rpcai.nominal')
			->select('rpcai.qty')
			->from('tr_h3_md_retur_pembelian_claim_items as rpcai')
			->where('rpcai.no_retur', $no_retur)
			->get()->result_array();

		foreach ($item_retur_pembelian_claim as $row) {
			$stock = $this->db
				->select('sp.id_part')
				->select('sp.id_lokasi_rak')
				->from('tr_stok_part as sp')
				->where('sp.id_part', $row['id_part'])
				->where('sp.qty >=', $row['qty'])
				->limit(1)
				->get()->row_array();

			if ($stock != null) {
				$transaksi_stock = [
					'id_part' => $stock['id_part'],
					'id_lokasi_rak' => $stock['id_lokasi_rak'],
					'tipe_transaksi' => '-',
					'sumber_transaksi' => 'h3_md_retur_pembelian_claim',
					'referensi' => $row['no_retur'],
					'stock_value' => $row['qty'],
				];

				$this->kartu_stock->insert($transaksi_stock);

				$this->db
					->set('sp.qty', "sp.qty - {$row['qty']}", false)
					->where('sp.id_part', $stock['id_part'])
					->where('sp.id_lokasi_rak', $stock['id_lokasi_rak'])
					->update('tr_stok_part as sp');
			}
		}
	}

	private function create_ar_ke_ahm($no_retur)
	{
		$this->load->model('H3_md_ar_part_model', 'ar_part');

		$item_retur_pembelian_claim = $this->db
			->select('rpcai.no_retur')
			->select('rpcai.id_part')
			->select('rpcai.nominal')
			->select('rpcai.qty')
			->from('tr_h3_md_retur_pembelian_claim_items as rpcai')
			->where('rpcai.no_retur', $no_retur)
			->get()->result_array();
		$total_retur_pembelian_claim = array_sum(
			array_map(function ($row) {
				return floatval($row['nominal']);
			}, $item_retur_pembelian_claim)
		);

		$retur_pembelian = $this->db
			->select('rpc.no_retur')
			->select('cmd.id_claim')
			->select('psp.packing_sheet_number')
			->select('ifnull(po.produk, "tidak_diketahui") as produk')
			->from('tr_h3_md_retur_pembelian_claim as rpc')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmd', 'cmd.id_claim = rpc.id_claim')
			->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = cmd.packing_sheet_number_int')
			->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = psp.no_po', 'left')
			->where('rpc.no_retur', $no_retur)
			->group_by('psp.packing_sheet_number_int')
			->limit(1)
			->get()->row_array();
		
			if($retur_pembelian == null) throw new Exception('Return pembelian claim tidak ditemukan');

		$this->ar_part->insert([
			'referensi' => $no_retur,
			'nama_customer' => 'ASTRA HONDA MOTOR (AHM)',
			'jenis_transaksi' => strtolower($retur_pembelian['produk']),
			'tipe_referensi' => 'retur_pembelian_claim',
			'tanggal_transaksi' => Mcarbon::now()->toDateString(),
			'tanggal_jatuh_tempo' => Mcarbon::now()->toDateString(),
			'total_amount' => $total_retur_pembelian_claim,
		]);
	}

	private function item_claim_ahm_diproses($no_retur)
	{
		$this->db
			->select('claim_item.*')
			->from('tr_h3_md_retur_pembelian_claim_items as rpcai')
			->join('tr_h3_md_terima_claim_ahm_item as terima_claim_item', '(terima_claim_item.id_terima_claim_ahm = rpcai.id_terima_claim_ahm and terima_claim_item.id_part = rpcai.id_part and terima_claim_item.no_doos = rpcai.no_doos and terima_claim_item.no_po = rpcai.no_po and terima_claim_item.id_kode_claim = rpcai.id_kode_claim)')
			->join('tr_h3_md_claim_main_dealer_ke_ahm_item as claim_item', '(claim_item.id_claim_int = terima_claim_item.id_claim_int and claim_item.id_part_int = terima_claim_item.id_part_int and claim_item.no_doos = terima_claim_item.no_doos and claim_item.no_po = terima_claim_item.no_po)')
			->where('rpcai.no_retur', $no_retur);

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
				->set('sudah_proses_retur_pembelian_claim', 1)
				->where('id', $row['id'])
				->update('tr_h3_md_claim_main_dealer_ke_ahm_item');

			log_message('info', sprintf('Sudah proses retur pembelian claim main dealer [%s]', $row['id']));
		}
	}

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';

		parent::insert($data);
	}

	public function update($data, $condition)
	{
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

		parent::update($data, $condition);
	}

	public function generateID()
	{
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');

		$query = $this->db->select('*')
			->from($this->table)
			// ->where("LEFT(created_at, 7)='{$th_bln}'")
			->order_by('created_at', 'DESC')
			->limit(1)
			->get();

		if ($query->num_rows() > 0) {
			$row        = $query->row();
			$no_retur = substr($row->no_retur, 0, 5);
			$no_retur = sprintf("%'.05d", $no_retur + 1);
			$id   = "{$no_retur}";
		} else {
			$id   = "00001";
		}

		return strtoupper($id);
	}
}
