<?php

class h3_md_surat_pengantar_model extends Honda_Model
{

	protected $table = 'tr_h3_md_surat_pengantar';

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
		$id = $this->db->insert_id();

		return $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();
	}

	public function generateID($id_dealer)
	{
		$tahun = date('Y');
		$bulan = date('m');
		$tahun_bulan = date('Y-m');

		$dealer = $this->db
			->from('ms_dealer as d')
			->where('d.id_dealer', $id_dealer)
			->get()->row();

		$query = $this->db
			->from("$this->table as sp")
			->where("LEFT(sp.tanggal, 4)='{$tahun}'")
			->where('sp.id_dealer', $id_dealer)
			->order_by('sp.created_at', 'desc')
			->limit(1)
			->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$id_surat_pengantar = substr($row->id_surat_pengantar, 0, 6);
			$id_surat_pengantar = sprintf("%'.06d", $id_surat_pengantar + 1);
			$id = "{$id_surat_pengantar}/SL-{$dealer->kode_dealer_md}/{$bulan}/{$tahun}";
		} else {
			$id = "000001/SL-{$dealer->kode_dealer_md}/{$bulan}/{$tahun}";
		}

		return strtoupper($id);
	}
}
