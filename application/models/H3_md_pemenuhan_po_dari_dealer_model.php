<?php

class H3_md_pemenuhan_po_dari_dealer_model extends Honda_Model
{

	protected $table = 'tr_h3_md_pemenuhan_po_dari_dealer';

	public function insert($data)
	{
		$purchase_order_dealer = $this->db
			->select('po.id')
			->from('tr_h3_dealer_purchase_order as po')
			->where('po.po_id', $data['po_id'])
			->get()->row_array();

		if ($purchase_order_dealer != null) {
			$data['po_id_int'] = $purchase_order_dealer['id'];
		}

		$part = $this->db
			->select('p.id_part_int')
			->from('ms_part as p')
			->where('p.id_part', $data['id_part'])
			->get()->row_array();

		if ($part != null) {
			$data['id_part_int'] = $part['id_part_int'];
		}

		parent::insert($data);
	}

	public function set_int_relation($id)
	{
		$data = $this->db
			->select('po.id as po_id_int')
			->select('p.id_part_int')
			->from(sprintf('%s as ppdd', $this->table))
			->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ppdd.po_id')
			->join('ms_part as p', 'p.id_part = ppdd.id_part')
			->where('ppdd.id', $id)
			->get()->row_array();

		if ($data == null) return;

		$this->db
			->set('po_id_int', $data['po_id_int'])
			->set('id_part_int', $data['id_part_int'])
			->where('id', $id)
			->update($this->table);
	}

	public function kurangi_qty_do($purchase_order_id, $id_part, $kuantitas)
	{
		$this->db
			->set('ppd.qty_do', sprintf('(ppd.qty_do - %s)', $kuantitas), false)
			->where('ppd.id_part', $id_part)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas DO pemenuhan dari dealer untuk purchase order dealer %s kode part %s dikurangi sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}

	public function kurangi_qty_do_v2($purchase_order_id, $id_part, $kuantitas, $id_part_int)
	{
		$this->db
			->set('ppd.qty_do', sprintf('(ppd.qty_do - %s)', $kuantitas), false)
			->where('ppd.id_part_int', $id_part_int)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas DO pemenuhan dari dealer untuk purchase order dealer %s kode part %s dikurangi sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}

	public function tambah_qty_do($purchase_order_id, $id_part, $kuantitas)
	{
		$this->db
			->set('ppd.qty_do', sprintf('(ppd.qty_do + %s)', $kuantitas), false)
			->where('ppd.id_part', $id_part)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas DO pemenuhan dari dealer untuk purchase order dealer %s kode part %s ditambahkan sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}

	public function kurangi_qty_so($purchase_order_id, $id_part, $kuantitas)
	{
		$this->db
			->set('ppd.qty_so', sprintf('(ppd.qty_so - %s)', $kuantitas), false)
			->where('ppd.id_part', $id_part)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas SO pemenuhan dari dealer untuk purchase order dealer %s kode part %s dikurangi sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}

	public function tambah_qty_so($purchase_order_id, $id_part, $kuantitas)
	{
		$this->db
			->set('ppd.qty_so', sprintf('(ppd.qty_so + %s)', $kuantitas), false)
			->where('ppd.id_part', $id_part)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas SO pemenuhan dari dealer untuk purchase order dealer %s kode part %s ditambahkan sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}

	public function tambah_qty_supply($purchase_order_id, $id_part, $kuantitas)
	{
		$this->db
			->set('ppd.qty_supply', sprintf('(ppd.qty_supply + %s)', $kuantitas), false)
			->where('ppd.id_part', $id_part)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas supply pemenuhan dari dealer untuk purchase order dealer %s kode part %s ditambahkan sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}

	public function tambah_qty_supply_v2($purchase_order_id, $id_part, $kuantitas, $id_part_int)
	{
		$this->db
			->set('ppd.qty_supply', sprintf('(ppd.qty_supply + %s)', $kuantitas), false)
			->where('ppd.id_part_int', $id_part_int)
			->where('ppd.po_id', $purchase_order_id)
			->update(sprintf('%s as ppd', $this->table));

		log_message('info', sprintf('Kuantitas supply pemenuhan dari dealer untuk purchase order dealer %s kode part %s ditambahkan sebesar %s', $purchase_order_id, $id_part, $kuantitas));
	}
}
