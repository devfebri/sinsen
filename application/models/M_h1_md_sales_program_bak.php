<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_md_sales_program extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getSalesProgram($filter = NULL)
  {
    $where = "WHERE 1=1 ";
    $join = "";

    if (isset($filter['id_program_md'])) {
      if ($filter['id_program_md'] != '') {
        $where .= " AND pr.id_program_md='{$filter['id_program_md']}'";
      }
    }

    // if (isset($filter['search'])) {
    //   $search = $filter['search'];
    //   if ($search != '') {
    //     $where .= " AND (spk.no_spk LIKE '%$search%'
    //                         OR spk.nama_konsumen LIKE '%$search%'
    //                         OR tk.tipe_ahm LIKE '%$search%'
    //                         ) 
    //         ";
    //   }
    // }

    $order = '';
    // if (isset($filter['order'])) {
    //   $order = $filter['order'];
    //   if ($order != '') {
    //     if ($filter['order_column'] == 'history') {
    //       $order_column = ['spk.no_spk_gc', 'spk.nama_npwp', 'spk.no_npwp', 'spk.alamat', 'spk.status', NULL];
    //     }
    //     $order_clm  = $order_column[$order['0']['column']];
    //     $order_by   = $order['0']['dir'];
    //     $order = " ORDER BY $order_clm $order_by ";
    //   } else {
    //     $order .= " ORDER BY spk.created_at DESC ";
    //   }
    // }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    $select = "pr.id_sales_program,pr.id_program_ahm,pr.id_program_md,pr.id_jenis_sales_program,pr.periode_awal,pr.periode_akhir,judul_kegiatan,target_penjualan,otomatis";
    if (isset($filter['select'])) {
    }

    return $this->db->query("SELECT $select
    FROM tr_sales_program pr
    $join
		$where
    $order
    $limit
    ");
  }
  function getClaimSalesProgram($filter = NULL)
  {
    $where = "WHERE 1=1 ";
    $join = "";

    if (isset($filter['id_program_md'])) {
      if ($filter['id_program_md'] != '') {
        $where .= " AND cd.id_program_md='{$filter['id_program_md']}'";
      }
    }

    // if (isset($filter['search'])) {
    //   $search = $filter['search'];
    //   if ($search != '') {
    //     $where .= " AND (spk.no_spk LIKE '%$search%'
    //                         OR spk.nama_konsumen LIKE '%$search%'
    //                         OR tk.tipe_ahm LIKE '%$search%'
    //                         ) 
    //         ";
    //   }
    // }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    $alasan_reject = "SELECT alasan_reject FROM tr_claim_dealer_syarat WHERE id_claim=cd.id_claim AND alasan_reject IS NOT NULL LIMIT 1";
    $select = "cspd.id_claim_dealer,cspd.nilai_potongan,cspd.status,cspd.perlu_revisi,cd.id_sales_order,tgl_ajukan_claim,tgl_approve_reject_md,sp.id_program_ahm,sp.id_program_md,so.no_invoice,LEFT(tgl_cetak_invoice2,10) as tgl_invoice,no_po_leasing,tgl_po_leasing,so.no_rangka,so.no_mesin,sb.tipe_motor id_tipe_kendaraan,sb.warna id_warna,wr.warna,tk.deskripsi_ahm,spk.jenis_beli,spk.id_finance_company,fc.finance_company,spk.nama_konsumen,spk.alamat,spk.id_kelurahan,csp.status,($alasan_reject) alasan_reject,so.id_dealer,LEFT(so.tgl_bastk,10) tgl_bastk";
    if (isset($filter['select'])) {
    }

    return $this->db->query("SELECT $select
    FROM tr_claim_sales_program_detail cspd
    JOIN tr_claim_sales_program csp ON csp.id_claim_sp=cspd.id_claim_sp
    JOIN tr_claim_dealer cd ON cd.id_claim=cspd.id_claim_dealer
    JOIN tr_sales_order so ON so.id_sales_order=cd.id_sales_order
    JOIN tr_scan_barcode sb ON sb.no_mesin=so.no_mesin
    JOIN tr_sales_program sp ON sp.id_program_md=cd.id_program_md
    JOIN ms_warna wr ON wr.id_warna=sb.warna
    JOIN tr_spk spk ON spk.no_spk=so.no_spk
    LEFT JOIN ms_finance_company fc ON fc.id_finance_company=spk.id_finance_company
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=sb.tipe_motor
		$where
    ");
  }
}
