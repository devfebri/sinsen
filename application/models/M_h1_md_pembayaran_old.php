<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_md_pembayaran extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getSPK($filter = NULL)
  {
    $id_dealer = $this->input->post('id_dealer');
    // send_json($id_dealer);
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


  function getDealerInvoiceReceipt($filter = NULL)
  {
   
    $dealer=dealer($filter['id_dealer']);
    $where = "WHERE dir.id_dealer = '$dealer->id_dealer' ";

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND dir.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_invoice'])) {
      if ($filter['id_invoice'] != '') {
        $where .= " AND dir.id_invoice='{$filter['id_invoice']}'";
      }
    }
    if (isset($filter['id_kwitansi'])) {
      if ($filter['id_kwitansi'] != '') {
        $where .= " AND dir.id_kwitansi='{$filter['id_kwitansi']}'";
      }
    }
    if (isset($filter['tgl_pembayaran']) && isset($filter['tgl_pembayaran2'])) {
      if ($filter['tgl_pembayaran'] != '') {
        // $where .= " AND dir.tgl_pembayaran >='{$filter['tgl_pembayaran']}' AND dir.tgl_pembayaran <='{$filter['tgl_pembayaran2']}' ";
        $where .= " AND dir.tgl_pembayaran BETWEEN '{$filter['tgl_pembayaran']}' AND '{$filter['tgl_pembayaran2']}' ";
      }
    }
    if (isset($filter['created_at_lebih_kecil'])) {
      if ($filter['created_at_lebih_kecil'] != '') {
        $where .= " AND dir.created_at<'{$filter['created_at_lebih_kecil']}'";
      }
    }

    if (isset($filter['jenis_invoice_in'])) {
      if ($filter['jenis_invoice_in'] != '') {
        $where .= " AND dir.jenis_invoice IN({$filter['jenis_invoice_in']})";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk LIKE '%$search%'
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
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY dir.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    if (isset($filter['get_first_kwitansi'])) {
      $order = "ORDER BY dir.created_at ASC";
    }
    $select = "
      dir.id_kwitansi,dir.tgl_pembayaran,dir.amount,dir.cara_bayar,IFNULL(dir.nominal_lebih,0) AS nominal_lebih,dir.jenis_invoice,dir.print_ke,dir.id_kwitansi_int,
    CASE 
      WHEN tjs.nama_konsumen IS NOT NULL THEN tjs.nama_konsumen
      WHEN dp.nama_konsumen IS NOT NULL THEN dp.nama_konsumen
      WHEN lunas.nama_konsumen IS NOT NULL THEN lunas.nama_konsumen
    END AS nama_konsumen,
    CASE 
      WHEN tjs.no_ktp IS NOT NULL THEN tjs.no_ktp
      WHEN dp.no_ktp IS NOT NULL THEN dp.no_ktp
      WHEN lunas.no_ktp IS NOT NULL THEN lunas.no_ktp
    END AS no_ktp,
    CASE 
      WHEN tjs.alamat IS NOT NULL THEN tjs.alamat
      WHEN dp.alamat IS NOT NULL THEN dp.alamat
      WHEN lunas.alamat IS NOT NULL THEN lunas.alamat
    END AS alamat,
    CASE 
      WHEN tjs.id_customer IS NOT NULL THEN tjs.id_customer
      WHEN dp.id_customer IS NOT NULL THEN dp.id_customer
      WHEN lunas.id_customer IS NOT NULL THEN lunas.id_customer
    END AS id_customer,
    CASE 
      WHEN kd_tjs.id_flp_md IS NOT NULL THEN kd_tjs.id_flp_md
      WHEN kd_dp.id_flp_md IS NOT NULL THEN kd_dp.id_flp_md
      WHEN kd_lunas.id_flp_md IS NOT NULL THEN kd_lunas.id_flp_md
    END AS id_flp_md,
    CASE 
      WHEN kd_tjs.id_flp_md IS NOT NULL THEN kd_tjs.nama_lengkap
      WHEN kd_dp.id_flp_md IS NOT NULL THEN kd_dp.nama_lengkap
      WHEN kd_lunas.id_flp_md IS NOT NULL THEN kd_lunas.nama_lengkap
    END AS nama_lengkap,
    CASE 
      WHEN tjs.tgl_spk IS NOT NULL THEN tjs.tgl_spk
      WHEN dp.tgl_spk IS NOT NULL THEN dp.tgl_spk
      WHEN lunas.tgl_spk IS NOT NULL THEN lunas.tgl_spk
    END AS tgl_spk,
    dir.no_spk, dir.note,
    CASE 
      WHEN (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk) IS NOT NULL THEN 
        (SELECT is_paid FROM tr_sales_order WHERE no_spk=dir.no_spk)
      ELSE (SELECT is_paid FROM tr_sales_order_gc WHERE no_spk_gc=dir.no_spk and status_cetak !='reject')
    END AS is_paid,
    CASE 
      WHEN (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk) IS NOT NULL THEN 
        (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk)
      ELSE (SELECT id_sales_order_gc FROM tr_sales_order_gc WHERE no_spk_gc=dir.no_spk and status_cetak !='reject')
    END AS id_sales_order,
    (SELECT CONCAT(id_invoice,'|',LEFT(created_at,10),'|',amount) FROM tr_invoice_tjs WHERE id_spk=dir.no_spk LIMIT 1) AS detail_tjs,dir.created_at,
    CASE WHEN dir.cara_bayar='cash' THEN 1 ELSE 2 END AS cara_bayar_id
      ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_amount') {
        $select = "IFNULL(SUM(dir.amount),0) AS sum_amount";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_h1_dealer_invoice_receipt dir
    LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=dir.id_invoice
    LEFT JOIN tr_invoice_dp dp ON dp.id_invoice_dp=dir.id_invoice
    LEFT JOIN tr_invoice_pelunasan lunas ON lunas.id_inv_pelunasan=dir.id_invoice
    LEFT JOIN ms_karyawan_dealer kd_tjs ON kd_tjs.id_karyawan_dealer=tjs.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_dp ON kd_dp.id_karyawan_dealer=dp.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_lunas ON kd_lunas.id_karyawan_dealer=lunas.id_karyawan_dealer
		$where
    $order
    $limit
    ");
  }

  function  getDealerInvoiceReceiptDetail($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_kwitansi'])) {
      $where .= " AND rcp.id_kwitansi='{$filter['id_kwitansi']}'";
    }
    if (isset($filter['no_spk'])) {
      $where .= " AND rc.no_spk='{$filter['no_spk']}'";
    }
    if (isset($filter['created_at_lebih_kecil'])) {
      $where .= " AND rc.created_at<'{$filter['created_at_lebih_kecil']}'";
    }
    if (isset($filter['tgl_pembayaran_lebih_kecil'])) {
      $where .= " AND rc.tgl_pembayaran<'{$filter['tgl_pembayaran_lebih_kecil']}'";
    }

    if (isset($filter['tgl_pembayaran'])) {
      $where .= " AND rc.tgl_pembayaran='{$filter['tgl_pembayaran']}'";
    }
    if (isset($filter['jenis_invoice_in'])) {
      if ($filter['jenis_invoice_in'] != '') {
        $where .= " AND rc.jenis_invoice IN({$filter['jenis_invoice_in']})";
      }
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_nominal') {
        $select = "IFNULL(SUM(nominal),0) AS sum_nominal";
      }
    } else {
      $select = "rcp.*,bk.bank,rc.jenis_invoice,rc.tgl_pembayaran,rc.id_invoice,rc.amount ";
    }
    return $this->db->query("SELECT $select
    FROM tr_h1_dealer_invoice_receipt_pembayaran rcp 
    JOIN tr_h1_dealer_invoice_receipt rc ON rc.id_kwitansi=rcp.id_kwitansi
    LEFT JOIN ms_bank bk ON bk.id_bank=rcp.id_bank
    $where ORDER BY rc.created_at ASC");
  }
}
