<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class H1_entry_penerimaan_bank extends CI_Model {

	public function data_entry_penerimaan_bank($search, $limit, $start, $order_field, $order_ascdesc)
	{

		$cari = '';
		if ($search != '') {
			$cari = " where id_penerimaan_bank LIKE '%$search%' OR tgl_entry LIKE '%$search%' OR total LIKE '%$search%' OR nama_dealer LIKE '%$search%'";
		}

		$sql = "SELECT
		q.id_penerimaan_bank as id_penerimaan_bank,
		q.tgl_entry as tgl_entry,
		q.total as total,
		q.nama_dealer as nama_dealer,
		q.status as status
		FROM
		(SELECT 
			a.id_penerimaan_bank as id_penerimaan_bank,
			a.tgl_entry as tgl_entry,
			sum(b.nominal) as total,
			c.nama_dealer as nama_dealer,
			a.status as status
		FROM tr_penerimaan_bank as a 
		INNER JOIN tr_penerimaan_bank_detail b ON a.id_penerimaan_bank=b.id_penerimaan_bank
		LEFT JOIN ms_dealer as c ON a.dibayar = c.id_dealer
		GROUP BY b.id_penerimaan_bank) as q
		$cari
		ORDER BY $order_field $order_ascdesc
		LIMIT $start,$limit

		";
		return $this->db->query($sql);
	}

	public function count_filter($search)
	{
		$cari = '';
		if ($search != '') {
			$cari = " where id_penerimaan_bank LIKE '%$search%' OR tgl_entry LIKE '%$search%' OR total LIKE '%$search%' OR nama_dealer LIKE '%$search%'";
		}

		$sql = "

		SELECT
		q.id_penerimaan_bank as id_penerimaan_bank
		FROM
		(SELECT 
			a.id_penerimaan_bank as id_penerimaan_bank,
			a.tgl_entry as tgl_entry,
			sum(b.nominal) as total,
			c.nama_dealer as nama_dealer,
			a.status as status
		FROM tr_penerimaan_bank as a 
		INNER JOIN tr_penerimaan_bank_detail b ON a.id_penerimaan_bank=b.id_penerimaan_bank
		LEFT JOIN ms_dealer as c ON a.dibayar = c.id_dealer
		GROUP BY b.id_penerimaan_bank) as q
		$cari

		";
		return $this->db->query($sql)->num_rows();
	}

}

/* End of file H1_entry_penerimaan_bank.php */