<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H1_pengajuan_bbn_md extends CI_Model {

	public function data_bbn_md($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$cari = '';
		if ($search != '') {
			$cari = "AND (tr_faktur_stnk.no_bastd LIKE '%$search%' OR tr_faktur_stnk.tgl_bastd LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%' OR tr_faktur_stnk.status_faktur LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%') ";
		}
		$sql = "
			SELECT
				tr_faktur_stnk.no_bastd,
				tr_faktur_stnk.tgl_bastd,
				ms_dealer.nama_dealer,
				tr_faktur_stnk.status_faktur
			FROM
				tr_faktur_stnk
				INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer 
			WHERE
				tr_faktur_stnk.status_faktur != 'approved' $cari
			ORDER BY
				$order_field $order_ascdesc
			limit $start,$limit
		";
		return $this->db->query($sql);
	}

	public function count_filter_bbm_md($search)
	{
		$cari = '';
		if ($search != '') {
			$cari = "AND (tr_faktur_stnk.no_bastd LIKE '%$search%' OR tr_faktur_stnk.tgl_bastd LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%' OR tr_faktur_stnk.status_faktur LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%') ";
		}
		$sql = "

			SELECT
				tr_faktur_stnk.no_bastd
			FROM
				tr_faktur_stnk
				INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer 
			WHERE
				tr_faktur_stnk.status_faktur != 'approved' $cari 

		";
		return $this->db->query($sql)->num_rows();
	}

	public function data_bbn_md_history($search, $limit, $start, $order_field, $order_ascdesc)
	{
		$cari = '';
		if ($search != '') {
			$cari = "AND (tr_faktur_stnk.no_bastd LIKE '%$search%' OR tr_faktur_stnk.tgl_bastd LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%' OR tr_faktur_stnk.status_faktur LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%') ";
		}
		$sql = "
			SELECT
				tr_faktur_stnk.no_bastd,
				tr_faktur_stnk.tgl_bastd,
				ms_dealer.nama_dealer,
				tr_faktur_stnk.status_faktur
			FROM
				tr_faktur_stnk
				INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer 
			WHERE
				tr_faktur_stnk.status_faktur IN ('approved','rejected')  $cari
			ORDER BY
				$order_field $order_ascdesc
			limit $start,$limit
		";
		return $this->db->query($sql);
	}

	public function count_filter_bbm_md_history($search)
	{
		$cari = '';
		if ($search != '') {
			$cari = "AND (tr_faktur_stnk.no_bastd LIKE '%$search%' OR tr_faktur_stnk.tgl_bastd LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%' OR tr_faktur_stnk.status_faktur LIKE '%$search%' OR ms_dealer.nama_dealer LIKE '%$search%') ";
		}
		$sql = "

			SELECT
				tr_faktur_stnk.no_bastd
			FROM
				tr_faktur_stnk
				INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer = ms_dealer.id_dealer 
			WHERE
				tr_faktur_stnk.status_faktur IN ('approved','rejected') $cari

		";
		return $this->db->query($sql)->num_rows();
	}

}

/* End of file H1_pengajuan_bbn_md.php */