<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_pemesanan extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getIndent($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE po.id_dealer = '$id_dealer'";

    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND po.status IN({$filter['status_in']})";
      }
    }
    if (isset($filter['no_mesin_null'])) {
      if ($filter['no_mesin_null'] != '') {
        $where .= " AND spk.no_mesin_spk IS NULL";
      }
    }
    if (isset($filter['status_not_in'])) {
      if ($filter['status_not_in'] != '') {
        $where .= " AND po.status NOT IN({$filter['status_not_in']})";
      }
    }

    // $amount_tjs = "SELECT SUM(amount) FROM tr_invoice_tjs_receipt tjs WHERE tjs.id_spk=po.id_spk";
    // $amount_tjs = "SELECT IFNULL(SUM(tanda_jadi),0) FROM tr_spk spk WHERE spk.no_spk=po.id_spk";
    return $this->db->query("SELECT po.*,tk.tipe_ahm,wr.warna,tanda_jadi AS amount_tjs, x.id_kwitansi,
(case when x.id_kwitansi is not null then 'Lunas' else 'Belum Lunas' end) as status_bayar  
		FROM tr_po_dealer_indent po
    INNER JOIN tr_spk spk ON spk.no_spk=po.id_spk
		INNER JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan= po.id_tipe_kendaraan
		INNER JOIN ms_warna wr ON wr.id_warna = po.id_warna 
		left join (
		select a.id_kwitansi, a.amount, b.id_spk from tr_h1_dealer_invoice_receipt a
		join tr_invoice_tjs b on a.no_spk =b.id_spk 
		where jenis_invoice ='tjs' and a.id_dealer = '$id_dealer' limit 1
	) x on  x.id_spk  = spk.no_spk 
		$where
		ORDER BY po.created_at DESC limit 1");
  }


  function getIndent_new($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE po.id_dealer = '$id_dealer' ";

    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND po.status IN({$filter['status_in']})";
      }
    }
    if (isset($filter['no_mesin_null'])) {
      if ($filter['no_mesin_null'] != '') {
        $where .= " AND spk.no_mesin_spk IS NULL";
      }
    }
    if (isset($filter['status_not_in'])) {
      if ($filter['status_not_in'] != '') {
        $where .= " AND po.status NOT IN({$filter['status_not_in']})";
      }
    }
    $where .="and ( spk.jenis_beli = 'Kredit' AND z.po_dari_finco != '' ) 
        OR spk.jenis_beli = 'Cash'";

    // $amount_tjs = "SELECT SUM(amount) FROM tr_invoice_tjs_receipt tjs WHERE tjs.id_spk=po.id_spk";
    // $amount_tjs = "SELECT IFNULL(SUM(tanda_jadi),0) FROM tr_spk spk WHERE spk.no_spk=po.id_spk";
    return $this->db->query("SELECT po.*,tk.tipe_ahm,wr.warna,tanda_jadi AS amount_tjs, x.id_kwitansi,
(case when x.id_kwitansi is not null then 'Lunas' else 'Belum Lunas' end) as status_bayar  
    FROM tr_po_dealer_indent po
    INNER JOIN tr_spk spk ON spk.no_spk=po.id_spk
    INNER JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan= po.id_tipe_kendaraan
    INNER JOIN ms_warna wr ON wr.id_warna = po.id_warna 
    left join (
    select a.id_kwitansi, a.amount, b.id_spk from tr_h1_dealer_invoice_receipt a
    join tr_invoice_tjs b on a.no_spk =b.id_spk 
    where jenis_invoice ='tjs' 
  ) x on  x.id_spk  = spk.no_spk 
    LEFT JOIN tr_entry_po_leasing AS z ON z.no_spk = spk.no_spk
    $where
    ORDER BY po.created_at DESC");
  }


}
