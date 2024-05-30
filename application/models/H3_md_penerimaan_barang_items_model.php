<?php

class H3_md_penerimaan_barang_items_model extends Honda_Model
{

	protected $table = 'tr_h3_md_penerimaan_barang_items';

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
		$id = $this->db->insert_id();

		$this->set_int_relation($id);
		$this->set_jumlah_item_diterima_pada_nomor_karton($id);
		return $id;
	}

	public function insert_ev($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
		$id = $this->db->insert_id();

		$this->set_int_relation_ev($id);
		// $this->set_jumlah_item_diterima_pada_nomor_karton($id);
		return $id;
	}

	public function set_jumlah_item_diterima_pada_nomor_karton($id)
	{
		$data = $this->db
			->select('pbi.nomor_karton_int')
			->from(sprintf('%s as pbi', $this->table))
			->where('pbi.id', $id)
			->limit(1)
			->get()->row_array();

		if ($data != null) {
			$this->load->model('h3_md_nomor_karton_model', 'nomor_karton');
			$this->nomor_karton->set_jumlah_item_diterima($data['nomor_karton_int']);
		}
	}

	public function set_int_relation($id)
	{
		$data = $this->db
			->select('psp.packing_sheet_number_int')
			->select('psp.no_po_int')
			->select('p.id_part_int')
			->select('psl.id as surat_jalan_ahm_int')
			->select('pb.id as no_penerimaan_barang_int')
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->join('ms_part as p', 'p.id_part = pbi.id_part')
			->join('tr_h3_md_ps_parts as psp', '(psp.id_part = pbi.id_part AND psp.no_po = pbi.no_po AND psp.packing_sheet_number = pbi.packing_sheet_number)', 'left')
			->join('tr_h3_md_psl as psl', 'psl.surat_jalan_ahm = pbi.surat_jalan_ahm', 'left')
			->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang', 'left')
			->where('pbi.id', $id)
			->get()->row_array();

		if ($data == null) return;

		$this->db
			->set('pbi.packing_sheet_number_int', $data['packing_sheet_number_int'])
			->set('pbi.no_po_int', $data['no_po_int'])
			->set('pbi.id_part_int', $data['id_part_int'])
			->set('pbi.surat_jalan_ahm_int', $data['surat_jalan_ahm_int'])
			->set('pbi.no_penerimaan_barang_int', $data['no_penerimaan_barang_int'])
			->where('pbi.id', $id)
			->update(sprintf('%s as pbi', $this->table));

		log_message('debug', sprintf('Set int relation penerimaan barang item [%s] [payload] %s', $id, print_r($data, true)));
	}

	public function set_int_relation_ev($id)
	{

		$data = $this->db
			->select('psp.packing_sheet_number_int')
			->select('psp.no_po_int')
			->select('p.id_part_int')
			->select('psl.id as surat_jalan_ahm_int')
			->select('psp.no_doos_int as nomor_karton_int')
			->select('pb.id as no_penerimaan_barang_int')
			->from('tr_h3_md_penerimaan_barang_items as pbi')
			->join('ms_part as p', 'p.id_part = pbi.id_part')
			->join('tr_h3_md_ps_parts as psp', '(psp.id_part = pbi.id_part AND psp.no_po = pbi.no_po AND psp.packing_sheet_number = pbi.packing_sheet_number)', 'left')
			->join('tr_h3_md_psl as psl', 'psl.surat_jalan_ahm = pbi.surat_jalan_ahm', 'left')
			->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = pbi.no_penerimaan_barang', 'left')
			->where('pbi.id', $id)
			->get()->row_array();

		if ($data == null) return;

		$this->db
			->set('pbi.packing_sheet_number_int', $data['packing_sheet_number_int'])
			->set('pbi.no_po_int', $data['no_po_int'])
			->set('pbi.id_part_int', $data['id_part_int'])
			->set('pbi.surat_jalan_ahm_int', $data['surat_jalan_ahm_int'])
			->set('pbi.nomor_karton_int', $data['nomor_karton_int'])
			->set('pbi.no_penerimaan_barang_int', $data['no_penerimaan_barang_int'])
			->where('pbi.id', $id)
			->update(sprintf('%s as pbi', $this->table));

		log_message('debug', sprintf('Set int relation penerimaan barang item [%s] [payload] %s', $id, print_r($data, true)));

	}


	public function set_qty_packing_sheet($id)
	{
		$data = $this->db
			->select('psp.packing_sheet_quantity')
			->from(sprintf('%s as pbi', $this->table))
			->join('tr_h3_md_ps_parts as psp', '(psp.id_part_int = pbi.id_part_int AND psp.packing_sheet_number_int = pbi.packing_sheet_number_int AND psp.no_doos = pbi.nomor_karton AND psp.no_po = pbi.no_po)')
			->where('pbi.id', $id)
			->get()->row_array();

		if ($data != null) {
			$this->db
				->set('pbi.qty_packing_sheet', $data['packing_sheet_quantity'])
				->where('pbi.id', $id)
				->update(sprintf('%s as pbi', $this->table));

			log_message('debug', sprintf('Set qty packing sheet untuk data dengan id [%s] menjadi %s', $id, $data['packing_sheet_quantity']));
		} else {
			log_message('debug', sprintf('Tidak bisa set qty packing sheet untuk data dengan id [%s] dikarenakan data tidak ditemukan', $id));
		}
	}

	public function count_claim_ekspedisi($id)
	{
		$data = $this->db
			->select('IFNULL(SUM(pbr.qty), 0) as qty', false)
			->from('tr_h3_md_penerimaan_barang_reasons as pbr')
			->join('ms_kategori_claim_c3  as kc', 'kc.id = pbr.id_claim')
			->where('pbr.id_penerimaan_barang_item', $id)
			->where('pbr.checked', 1)
			->where('pbr.qty > ', 0)
			->where('kc.tipe_claim', 'Claim Ekspedisi')
			->limit(1)
			->get()->row_array();


		if ($data == null) throw new Exception('Data tidak ditemukan');

		$qty = $data['qty'];

		$this->db
			->set('qty_claim_ekspedisi', $qty)
			->where('id', $id)
			->update($this->table);

		log_message('debug', sprintf('Set qty %s claim ekspedisi pada item penerimaan barang [%s]', $qty, $id));
	}

	public function count_selain_claim_ekspedisi($id)
	{
		$data = $this->db
			->select('IFNULL(SUM(pbr.qty), 0) as qty', false)
			->from('tr_h3_md_penerimaan_barang_reasons as pbr')
			->join('ms_kategori_claim_c3  as kc', 'kc.id = pbr.id_claim')
			->where('pbr.id_penerimaan_barang_item', $id)
			->where('pbr.checked', 1)
			->where('pbr.qty > ', 0)
			->where('kc.tipe_claim != ', 'Claim Ekspedisi')
			->limit(1)
			->get()->row_array();

		if ($data == null) throw new Exception('Data tidak ditemukan');

		$qty = $data['qty'];

		$this->db
			->set('qty_selain_claim_ekspedisi', $qty)
			->where('id', $id)
			->update($this->table);

		log_message('debug', sprintf('Set qty %s claim selain ekspedisi pada item penerimaan barang [%s]', $qty, $id));
	}

	public function set_jumlah_item_diterima_pada_karton($id)
	{
		$data = $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();

		if ($data == null) throw new Exception('Data tidak ditemukan');

		if ($data['nomor_karton_int'] == null) throw new Exception('ID nomor karton tidak ditemukan');

		$this->load->model('H3_md_nomor_karton_model', 'nomor_karton');

		$this->nomor_karton->set_jumlah_item_diterima($data['nomor_karton_int']);
	}
}
