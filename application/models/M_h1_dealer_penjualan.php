<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_penjualan extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getSPK($filter = NULL)
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
    return $this->db->query("SELECT po.*,tk.tipe_ahm,wr.warna,tanda_jadi AS amount_tjs 
		FROM tr_po_dealer_indent po
    INNER JOIN tr_spk spk ON spk.no_spk=po.id_spk
		INNER JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan= po.id_tipe_kendaraan
		INNER JOIN ms_warna wr ON wr.id_warna = po.id_warna 
		$where
		ORDER BY po.created_at DESC");
  }

  function getSO($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where_id = "WHERE spk.id_dealer = '$id_dealer' ";
    $where_gc = "WHERE spk_gc.id_dealer = '$id_dealer' ";
    $select = '';
    $dp_gc = "SELECT SUM(IFNULL(dp_stor,0)) FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc";
    // $dp_gc = "0";

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where_id .= " AND spk.no_spk='{$filter['no_spk']}'";
        $where_gc .= " AND spk_gc.no_spk_gc='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_inv_pelunasan'])) {
      if ($filter['id_inv_pelunasan'] != '') {
        $where_id .= " AND lunas.id_inv_pelunasan='{$filter['id_inv_pelunasan']}'";
        $where_gc .= " AND lunas_gc.id_inv_pelunasan='{$filter['id_inv_pelunasan']}'";
      }
    }
    if (isset($filter['id_invoice_dp'])) {
      if ($filter['id_invoice_dp'] != '') {
        $where_id .= " AND dp.id_invoice_dp='{$filter['id_invoice_dp']}'";
        $where_gc .= " AND dp_gc.id_invoice_dp='{$filter['id_invoice_dp']}'";
      }
    }
    if (isset($filter['jenis_beli'])) {
      if ($filter['jenis_beli'] != '') {
        $where_id .= " AND spk.jenis_beli='{$filter['jenis_beli']}'";
        $where_gc .= " AND spk_gc.jenis_beli='{$filter['jenis_beli']}'";
      }
    }
    if (isset($filter['spk_ada_dp'])) {
      $where_id .= " AND IFNULL(spk.dp_stor,0)>0 AND spk.jenis_beli='Kredit' ";
      $where_gc .= " AND IFNULL(($dp_gc),0)>0 AND spk_gc.jenis_beli='Kredit' ";
    }
    if (isset($filter['id_invoice_dp_null'])) {
      $where_id .= " AND dp.id_invoice_dp IS NULL";
      $where_gc .= " AND dp_gc.id_invoice_dp IS NULL";
    }
    if (isset($filter['id_inv_pelunasan_null'])) {
      $where_id .= " AND lunas.id_inv_pelunasan IS NULL";
      $where_gc .= " AND lunas_gc.id_inv_pelunasan IS NULL";
    }
    if (isset($filter['status_tjs_in'])) {
      $where_id .= " AND 
                  CASE 
                    WHEN spk.tanda_jadi>0 THEN 
                      CASE 
                        WHEN tjs.status IN ({$filter['status_tjs_in']}) THEN 1
                        ELSE 0
                      END
                    ELSE 1
                  END = 1
      ";
      $where_gc .= " AND 
                  CASE 
                    WHEN spk_gc.tanda_jadi>0 THEN 
                      CASE 
                        WHEN tjs_gc.status IN ({$filter['status_tjs_in']}) THEN 1
                        ELSE 0
                      END
                    ELSE 1
                  END = 1
      ";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where_id .= " AND (spk.no_spk LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_ktp LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.jenis_beli LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status_spk LIKE '%$search%'
                            ) 
            ";
        $where_gc .= " AND (spk_gc.no_spk_gc LIKE '%$search%'
                            OR spk_gc.nama_npwp LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'pembayaran') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $diskon = sql_diskon_spk();
    $total_bayar = sql_total_bayar_spk();
    $sisa_pelunasan = "($total_bayar-IFNULL(dp_stor,0)-IFNULL(tanda_jadi,0))";

    $total_bayar_gc = sql_total_bayar_spk_gc_summary();
    $diskon_gc = sql_diskon_spk_gc_summary();
    $sisa_pelunasan_gc = "($total_bayar_gc)-IFNULL(($dp_gc),0)-IFNULL(spk_gc.tanda_jadi,0)";

    // $diskon = 0;
    // $total_bayar = 0;
    // $sisa_pelunasan = 0;

    // $total_bayar_gc = 0;
    // $diskon_gc = 0;
    // $sisa_pelunasan_gc = 0;

$t='';

if($id_dealer==70){
	$t=' s';
}

    return $this->db->query("SELECT * FROM(
      SELECT so.id_sales_order,spk.no_spk,spk.nama_konsumen,spk.tgl_spk,spk.no_ktp,spk.no_hp,spk.jenis_beli,$total_bayar AS total_bayar,spk.tanda_jadi,spk.created_at,$diskon AS diskon,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,dp_stor,dp.amount_dp,dp.id_invoice_dp,dp.created_at AS created_dp_at,$sisa_pelunasan AS sisa_pelunasan,id_inv_pelunasan,lunas.created_at AS created_lunas_at,'individu' AS jenis_spk,tjs.status AS status_tjs
      FROM tr_sales_order so
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
      LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp.id_karyawan_dealer
      LEFT JOIN tr_invoice_tjs tjs ON tjs.id_spk=spk.no_spk
      LEFT JOIN tr_invoice_dp dp ON dp.id_spk=spk.no_spk
      LEFT JOIN tr_invoice_pelunasan lunas ON lunas.id_spk=spk.no_spk
      $where_id
      UNION
      SELECT so_gc.id_sales_order_gc,spk_gc.no_spk_gc,spk_gc.nama_npwp,spk_gc.tgl_spk_gc,spk_gc.no_npwp,spk_gc.no_telp,spk_gc.jenis_beli,($total_bayar_gc) AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,($diskon_gc) AS diskon,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,($dp_gc) AS dp_stor,dp_gc.amount_dp,dp_gc.id_invoice_dp,dp_gc.created_at AS created_dp_at,($sisa_pelunasan_gc) AS sisa_pelunasan,id_inv_pelunasan,lunas_gc.created_at AS created_lunas_at,'gc' AS jenis_spk,tjs_gc.status AS status_tjs
      FROM tr_sales_order_gc so_gc
      JOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc=so_gc.no_spk_gc
      JOIN tr_prospek_gc prp_gc ON prp_gc.id_prospek_gc=spk_gc.id_prospek_gc
      LEFT JOIN ms_karyawan_dealer kd_gc ON kd_gc.id_karyawan_dealer=prp_gc.id_karyawan_dealer
      LEFT JOIN tr_invoice_tjs tjs_gc ON tjs_gc.id_spk=spk_gc.no_spk_gc
      LEFT JOIN tr_invoice_dp dp_gc ON dp_gc.id_spk=spk_gc.no_spk_gc
      LEFT JOIN tr_invoice_pelunasan lunas_gc ON lunas_gc.id_spk=spk_gc.no_spk_gc
		  $where_gc
    ) AS tabel
    $order 
    $limit
    ");
  }
}
