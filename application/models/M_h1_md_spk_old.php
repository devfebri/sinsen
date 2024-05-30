<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_md_spk extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    // $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->library('cfpdf');
    $this->load->library('PDF_HTML');
    $this->load->library('PDF_HTML');
    $this->load->library('mpdf_l');
    $this->load->helper('tgl_indo');
    $this->load->model('m_admin');
  }
  // function mata_uang($a)
  // {
  //   if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
  //   return number_format($a, 0, ',', '.');
  // }
  // function format_tgl($a)
  // {
  //   return date('d/m/Y', strtotime($a));
  // }


  function getSPK($filter = NULL)
  {
    
    $dealer=dealer($filter['id_dealer']);

    $where_id  = "WHERE spk.id_dealer    = '$dealer->id_dealer' ";
    $where_gc  = "WHERE spk_gc.id_dealer = '$dealer->id_dealer' ";
   
    // $where_id  = "WHERE 1=1 ";
    // $where_gc  = "WHERE 1=1 ";
    $select_id = '';
    $select_gc = '';
    $dp_gc = "SELECT SUM(IFNULL(dp_stor,0)) FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc";
    $angsuran_gc = "SELECT SUM(IFNULL(angsuran,0)) FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc";
    $tenor_gc = "SELECT tenor FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc LIMIT 1";
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where_id .= " AND spk.no_spk='{$filter['no_spk']}'";
        $where_gc .= " AND spk_gc.no_spk_gc='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where_id .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
        $where_gc .= " AND 1=0";
      }
    }
    if (isset($filter['id_invoice_tjs'])) {
      if ($filter['id_invoice_tjs'] != '') {
        $where_id .= " AND tjs.id_invoice='{$filter['id_invoice_tjs']}'";
        $where_gc .= " AND tjs_gc.id_invoice='{$filter['id_invoice_tjs']}'";
      }
    }
    if (isset($filter['id_customer'])) {
      if ($filter['id_customer'] != '') {
        $where_id .= " AND spk.id_customer='{$filter['id_customer']}'";
        $where_gc .= " AND spk_gc.id_prospek_gc='{$filter['id_customer']}'";
      }
    }
    if (isset($filter['bulan_spk'])) {
      if ($filter['bulan_spk'] != '') {
        $where_id .= " AND LEFT(spk.tgl_spk,7)='{$filter['bulan_spk']}'";
        $where_gc .= " AND LEFT(spk_gc.tgl_spk_gc,7)='{$filter['bulan_spk']}'";
      }
    }
    if (isset($filter['status_in'])) {
      $where_id .= " AND spk.status_spk in ({$filter['status_in']})";
      $where_gc .= " AND spk_gc.status in ({$filter['status_in']})";
    }
    if (isset($filter['expired'])) {
      $where_id .= " AND spk.expired=1";
      $where_gc .= " AND spk_gc.expired=1";
    }
    if (isset($filter['spk_ada_tanda_jadi'])) {
      $where_id .= " AND IFNULL(spk.tanda_jadi,0)>0";
      $where_gc .= " AND IFNULL(spk_gc.tanda_jadi,0)>0";
    }
    if (isset($filter['spk_ada_dp'])) {
      $where_id .= " AND IFNULL(spk.dp_stor,0)>0 AND jenis_beli='Kredit' ";
      $where_gc .= " AND IFNULL($dp_gc)>0 AND spk_gc.jenis_beli='Kredit' ";
    }
    if (isset($filter['id_invoice_dp_null'])) {
      $where_id .= " AND dp.id_invoice_dp IS NULL";
      $where_gc .= " AND dp_gc.id_invoice_dp IS NULL";
    }
    if (isset($filter['id_tjs_null'])) {
      $where_id .= " AND tjs.id_invoice IS NULL";
      $where_gc .= " AND tjs_gc.id_invoice IS NULL";
    }
    if (isset($filter['ada_program'])) {
      $where_id .= " AND (spk.program_umum!='' OR spk.program_gabungan IS NOT NULL)";
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where_id .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1)='{$filter['id_karyawan_dealer']}'";
        $where_gc .= " AND prp_gc.id_karyawan_dealer = ({$filter['id_karyawan_dealer']})";
      }
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where_id .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1) IN ({$filter['id_karyawan_dealer_in']})";
        $where_gc .= " AND prp_gc.id_karyawan_dealer IN ({$filter['id_karyawan_dealer_in']})";
      }
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
          $order_column = ['no_spk', 'tgl_spk', 'nama_konsumen', 'no_ktp', 'no_hp', 'id_tipe_kendaraan', 'id_warna', 'jenis_beli', 'tanda_jadi', 'total_bayar', NULL];
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

    $join_additional_id = '';
    $join_additional_gc = '';
    $select = "*";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'invoice_tjs') {
        $select_id = "no_spk,spk.nama_konsumen,tgl_spk,spk.no_ktp, spk.the_road, spk.no_hp,spk.id_tipe_kendaraan,spk.id_warna,spk.jenis_beli," . sql_total_bayar_spk() . " AS total_bayar,spk.tanda_jadi,spk.created_at," . sql_diskon_spk() . " AS diskon,tk.tipe_ahm,wr.warna,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,tjs.id_invoice,tjs.created_at created_at_tjs,tjs_r.print_at,tjs_r.print_by,tjs_r.print_ke,spk.harga_tunai, 'individu' as kategori_spk";
        $join_additional_id = " LEFT JOIN tr_h1_dealer_invoice_receipt tjs_r ON tjs_r.id_invoice=tjs.id_invoice";

        $select_gc = "no_spk_gc,spk_gc.nama_npwp,tgl_spk_gc,spk_gc.no_npwp,spk_gc.on_road_gc as the_road, spk_gc.no_telp,'' AS id_tipe_kendaraan,AS id_warna,spk_gc.jenis_beli,(" . sql_total_bayar_spk_gc_summary() . ") AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,(" . sql_diskon_spk_gc_summary() . ") AS diskon,'' AS tipe_ahm,'' AS warna,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,tjs_gc.id_invoice,tjs_gc.created_at,tjs_gc_r.print_at,tjs_gc_r.print_by,tjs_gc_r.print_ke,0 AS harga_tunai, 'gc' as kategori_spk";
        $join_additional_gc = " LEFT JOIN tr_h1_dealer_invoice_receipt tjs_gc_r ON tjs_gc_r.id_invoice=tjs_gc.id_invoice";
      } elseif ($filter['select'] == 'count') {

        $select_id = "spk.no_spk";
        $select_gc = "spk_gc.no_spk_gc";
        $select = "COUNT(no_spk) AS count";
      }
    } else {
      $select_id = "no_spk,spk.nama_konsumen,spk.tgl_spk,spk.no_ktp,spk.no_hp,spk.the_road, spk.id_tipe_kendaraan,spk.id_warna,spk.jenis_beli," . sql_total_bayar_spk() . " AS total_bayar,spk.tanda_jadi,spk.created_at," . sql_diskon_spk() . " AS diskon,tk.tipe_ahm,wr.warna,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,dp_stor,'individu' AS jenis_spk,prp.id_customer,spk.alamat,spk.harga_tunai,spk.biaya_bbn,spk.tenor,spk.angsuran,spk.id_finance_company,finco.finance_company, 'individu' as kategori_spk";
      // $dp_gc = 0;
      $select_gc = "spk_gc.no_spk_gc,spk_gc.nama_npwp,tgl_spk_gc,spk_gc.no_npwp,spk_gc.on_road_gc as the_road,spk_gc.no_telp,'' AS id_tipe_kendaraan,'' AS id_warna,spk_gc.jenis_beli,(" . sql_total_bayar_spk_gc_summary() . ") AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,(" . sql_diskon_spk_gc_summary() . ") AS diskon,'' AS tipe_ahm,'' AS warna,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,($dp_gc) AS dp_stor,'gc' AS jenis_spk,prp_gc.id_prospek_gc,spk_gc.alamat,0 AS harga_tunai,0 AS biaya_bbn,($tenor_gc),($angsuran_gc),spk_gc.id_finance_company,finco_gc.finance_company,'gc' as kategori_spk";
    }
    
    return $this->db->query("SELECT $select FROM(
      SELECT $select_id
      FROM tr_spk spk
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
      JOIN ms_warna wr ON wr.id_warna=spk.id_warna
      LEFT JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
      LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp.id_karyawan_dealer
      LEFT JOIN tr_invoice_tjs tjs ON tjs.id_spk=spk.no_spk
      LEFT JOIN tr_invoice_dp dp ON dp.id_spk=spk.no_spk
      LEFT JOIN ms_finance_company finco ON finco.id_finance_company=spk.id_finance_company
      $join_additional_id
      $where_id
      UNION
      SELECT $select_gc
      FROM tr_spk_gc spk_gc
      LEFT JOIN tr_prospek_gc prp_gc ON prp_gc.id_prospek_gc=spk_gc.id_prospek_gc
      LEFT JOIN ms_karyawan_dealer kd_gc ON kd_gc.id_karyawan_dealer=prp_gc.id_karyawan_dealer
      LEFT JOIN tr_invoice_tjs tjs_gc ON tjs_gc.id_spk=spk_gc.no_spk_gc
      LEFT JOIN tr_invoice_dp dp_gc ON dp_gc.id_spk=spk_gc.no_spk_gc
      LEFT JOIN ms_finance_company finco_gc ON finco_gc.id_finance_company=spk_gc.id_finance_company
      $join_additional_gc
      $where_gc
    ) AS tabel
    $order
    $limit
    ");
  }
}
