<?php
defined('BASEPATH') or exit('No direct script access allowed');


class H1_model_do extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	


	public function filter($search, $limit, $start, $order_field, $order_ascdesc){
		if ($search !='') {
			$cari = "AND (
			a.no_do LIKE '%$search%' 
			OR a.status LIKE '%$search%'
			OR a.tgl_do LIKE '%$search%'
			OR b.nama_dealer LIKE '%$search%'

			 )";
		} else {
			$cari = "";
		}
		$query = $this->db->query("
			SELECT
				a.no_do,
				a.source,
				b.kode_dealer_md,
				b.nama_dealer,
				a.tgl_do,
				'' as a,
				'' as b,
				a.status,
				'' as c,
				a.id_dealer
			FROM
				tr_do_po a
				INNER JOIN ms_dealer b ON a.id_dealer = b.id_dealer 
			WHERE a.status !='rejected' AND a.status !='reject finance'	$cari
			ORDER BY $order_field $order_ascdesc
			LIMIT $start,$limit
			");
		return $query;
	}

	

	public function count_all(){

	}

	public function count_filter($search){
		if ($search !='') {
			$cari = "AND (
			a.no_do LIKE '%$search%' 
			OR a.status LIKE '%$search%'
			OR a.tgl_do LIKE '%$search%'
			OR b.nama_dealer LIKE '%$search%'

			 )";
		} else {
			$cari = "";
		}
		$query = $this->db->query("
			SELECT
				a.no_do,
				a.source,
				a.id_dealer,
				a.tgl_do,
				a.status,
				b.kode_dealer_md,
				b.nama_dealer
				
			FROM
				tr_do_po a
				INNER JOIN ms_dealer b ON a.id_dealer = b.id_dealer 
				WHERE a.status !='rejected' AND a.status !='reject finance'
				$cari
			");
		return $query->num_rows();
	}


}