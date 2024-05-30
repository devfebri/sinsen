<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_print_receipt extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getHistoryPrintReceiptWO($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where_jenis = '';
    if ($filter != null) {
      if (isset($filter['jenis_penerimaan'])) {
        $where_jenis .= " AND rcm.metode_bayar='{$filter['jenis_penerimaan']}'";
      }
    }
    // $tot_bayar_njb_nsc = 0;
    $dibayar_njb = "(SELECT IFNULL(SUM(nominal),0) 
        FROM tr_h2_receipt_customer_metode rcm
        JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
        WHERE id_referensi=wo.id_work_order $where_jenis
      )
    ";
    // $dibayar_njb = 0;
    $sisa_njb = "(ROUND(wo.grand_total-$dibayar_njb))";
    $return = "SELECT kuantitas_return FROM tr_h3_dealer_sales_order_parts spt WHERE spt.nomor_so=wdp.nomor_so AND spt.id_part=wdp.id_part";

    $cek_nsc = " AND 
                  (CASE WHEN (SELECT COUNT(id_part-IFNULL(($return),0)) FROM tr_h2_wo_dealer_parts wdp WHERE wdp.id_work_order=wo.id_work_order AND pekerjaan_batal!=1) >0 
                  THEN (SELECT COUNT(id_referensi) FROM tr_h23_nsc WHERE tr_h23_nsc.id_referensi=wo.id_work_order)
                  ELSE 1
                  END)>0";
    $where_njb = "WHERE wo.id_dealer='$id_dealer' $cek_nsc AND no_njb IS NOT NULL";

    $uang_muka = "SELECT SUM(IFNULL(uang_muka_terpakai,0)) FROM tr_h2_uang_jaminan uj WHERE uj.no_inv_uang_jaminan=nsc.no_inv_jaminan";

    $dibayar_nsc = "((SELECT IFNULL(SUM(nominal),0) 
    FROM tr_h2_receipt_customer_metode rcm
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
    WHERE id_referensi=nsc.id_referensi $where_jenis
    )+($uang_muka))
    ";
    $sisa_nsc = "(ROUND(nsc.tot_nsc-$dibayar_nsc))";

    // $order_column = ['referensi', 'pk.id_referensi', 'coa', null];
    $where = "WHERE 1=1 ";
    $limit = '';
    $order = " ORDER BY tgl_invoice DESC";

    if ($filter != null) {
      if (isset($filter['sisa_0'])) {
        if ($filter['sisa_0'] != '') {
          $where_njb .= " AND $sisa_njb<=0";
        }
      }

      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND ( no_nsc LIKE '%{$filter['search']}%' 
                  OR id_referensi LIKE '%{$filter['search']}%'
                  OR referensi LIKE '%{$filter['search']}%'
                  OR no_njb LIKE '%{$filter['search']}%'
                  OR tgl_invoice LIKE '%{$filter['search']}%'
                  OR nama_customer LIKE '%{$filter['search']}%'
                  OR tipe_ahm LIKE '%{$filter['search']}%'
                  OR no_polisi LIKE '%{$filter['search']}%'
                )";
        }
      }

      if (isset($filter['order'])) {
        if (isset($filter['order_column'])) {
          if ($filter['order_column'] = 'print_receipt') {
            $order_column = ['referensi', 'id_referensi', 'no_njb', 'no_nsc', 'no_polisi', 'nama_customer', 'tipe_ahm', 'total_bayar', 'dibayar', 'sisa', null];
          } else {
            $order_column = $filter['order_column'];
          }
        }
        if ($filter['order'] != '') {
          $filter_order = $filter['order'];
          $order_clm    = $order_column[$filter['order'][0]['column']];
          $order_by     = $filter_order[0]['dir'];
          $order        = " ORDER BY $order_clm $order_by ";
        } else {
          $order  = " ORDER BY created_at DESC";
        }
      } else {
        $order  = " ORDER BY created_at DESC";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    $nilai_oli = "nsc.tot_nsc_oli";
    $nilai_part = "nsc.tot_nsc_part";
    $select_wo = "
    'Work Order' referensi,
    wo.id_work_order AS id_referensi,
    no_njb,
    nsc.no_nsc,  
    no_polisi,
    nama_customer,
    tipe_ahm,
    wo.grand_total AS total_bayar,
    $dibayar_njb AS dibayar,
    $sisa_njb AS sisa,
    wo.created_at
    ";

    return $this->db->query("SELECT * FROM 
    (SELECT $select_wo
      FROM tr_h2_wo_dealer AS wo
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      LEFT JOIN tr_h23_nsc nsc ON nsc.id_referensi=wo.id_work_order
      JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      $where_njb
    ) AS tabel 
    $where $order $limit
    ");
  }
  function getHistoryPrintReceiptPartSales($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $uang_muka = "SELECT SUM(IFNULL(uang_muka_terpakai,0)) FROM tr_h2_uang_jaminan uj WHERE uj.no_inv_uang_jaminan=nsc.no_inv_jaminan";
    // $uang_muka = 0;
    $dibayar_nsc = "((SELECT IFNULL(SUM(nominal),0) 
    FROM tr_h2_receipt_customer_metode rcm
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
    WHERE id_referensi=nsc.id_referensi
    )+($uang_muka))
    ";
    $sisa_nsc = "(ROUND(nsc.tot_nsc-$dibayar_nsc))";
    $where_nsc = "WHERE nsc.id_dealer='$id_dealer'";

    // $order_column = ['referensi', 'pk.id_referensi', 'coa', null];
    $where = "WHERE 1=1 ";
    $limit = '';
    $order = " ORDER BY tgl_invoice DESC";

    $all_print = "CASE 
          WHEN referensi='Work Order' THEN
            CASE 
              WHEN no_nsc IS NULL THEN 
                CASE WHEN cetak_njb_ke>0 THEN 1 ELSE 0 END
              WHEN no_nsc IS NOT NULL THEN
                CASE WHEN cetak_njb_ke>0 AND cetak_nsc_ke>0 AND cetak_gab_ke>0 THEN 1 ELSE 0 END
            END
          WHEN referensi='Part Sales' THEN
            CASE WHEN cetak_nsc_ke>0 THEN 1 ELSE 0 END
        END
      ";

    if ($filter != null) {
      if (isset($filter['sisa_lebih_besar'])) {
        if ($filter['sisa_lebih_besar'] != '') {
          $where_nsc .= " AND $sisa_nsc>0";
        }
      }
      if (isset($filter['sisa_0'])) {
        if ($filter['sisa_0'] != '') {
          $where_nsc .= " AND $sisa_nsc<=0";
        }
      }

      if (isset($filter['referensi'])) {
        $where .= " AND referensi='{$filter['referensi']}'";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND referensi='{$filter['id_work_order']}'";
      }
      if (isset($filter['tgl_nsc'])) {
        $where .= " AND tgl_nsc='{$filter['tgl_nsc']}'";
      }
      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND ( no_nsc LIKE '%{$filter['search']}%' 
                  OR id_referensi LIKE '%{$filter['search']}%'
                  OR referensi LIKE '%{$filter['search']}%'
                  OR no_njb LIKE '%{$filter['search']}%'
                  OR tgl_invoice LIKE '%{$filter['search']}%'
                  OR nama_customer LIKE '%{$filter['search']}%'
                  OR tipe_ahm LIKE '%{$filter['search']}%'
                  OR no_polisi LIKE '%{$filter['search']}%'
                )";
        }
      }

      if (isset($filter['dibayar'])) {
        $where .= " AND dibayar {$filter['dibayar']}";
      }
      if (isset($filter['all_print'])) {
        if ($filter['all_print'] != '') {
          $where .= " AND ($all_print)={$filter['all_print']}";
        }
      }
      if (isset($filter['filter_created_wo'])) {
        $where .= " AND LEFT(created_at_wo,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }

      if (isset($filter['order'])) {
        if (isset($filter['order_column'])) {
          if ($filter['order_column'] = 'print_receipt') {
            $order_column = ['referensi', 'id_referensi', 'no_njb', 'no_nsc', 'no_polisi', 'nama_customer', 'tipe_ahm', 'total_bayar', 'dibayar', 'sisa', null];
          } else {
            $order_column = $filter['order_column'];
          }
        }
        if ($filter['order'] != '') {
          $filter_order = $filter['order'];
          $order_clm    = $order_column[$filter['order'][0]['column']];
          $order_by     = $filter_order[0]['dir'];
          $order        = " ORDER BY $order_clm $order_by ";
        } else {
          $order  = " ORDER BY created_at DESC";
        }
      } else {
        $order  = " ORDER BY created_at DESC";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    // $filter_oli = [
    //   'sql' => true,
    //   'sql_no_nsc' => 'nsc.no_nsc',
    //   'group_by_no_nsc' => true,
    //   'group_by_no_nsc_only_grand' => true,
    //   'kelompok_part' => 'Oil'
    // ];
    // $nilai_oli = $this->getNSCParts($filter_oli);

    // $filter_part = [
    //   'sql' => true,
    //   'sql_no_nsc' => 'nsc.no_nsc',
    //   'group_by_no_nsc' => true,
    //   'group_by_no_nsc_only_grand' => true,
    //   'kelompok_part_not_in' => "'Oil'"
    // ];
    // $nilai_part = $this->getNSCParts($filter_part);
    // $nilai_part = 0;
    $nilai_oli = "nsc.tot_nsc_oli";
    $nilai_part = "nsc.tot_nsc_part";

    $select_nsc = "'Part Sales' AS referensi,'-' AS no_njb,nsc.no_nsc,id_referensi,
    nama_pembeli nama_customer,
    nsc.created_at,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN ''
      ELSE ch23.no_polisi
    END AS no_polisi,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.alamat_pembeli
      ELSE ch23.alamat
    END AS alamat,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.id_customer
      ELSE ch23.id_customer
    END AS id_customer,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.no_hp_pembeli
      ELSE ch23.no_hp
    END AS no_hp,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='')THEN ''
      ELSE tk_ch23.tipe_ahm
    END AS tipe_ahm,
    nsc.tot_nsc AS total_bayar,$dibayar_nsc AS dibayar,$sisa_nsc AS sisa,'' AS id_work_order,0 AS nilai_jasa,LEFT(nsc.created_at,10) AS tgl_invoice,'' AS tgl_njb, tgl_nsc,0 AS cetak_njb_ke,0 AS cetak_gab_ke,cetak_nsc_ke, ($nilai_oli) AS nilai_oli,($nilai_part) AS nilai_part,'' AS created_at_wo,'' AS total_jasa,nsc.tot_nsc,'' AS tgl_jatuh_tempo";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'print_njb_nsc') {
        $select_nsc = "'Part Sales' AS referensi,'-' AS no_njb,nsc.no_nsc,id_referensi,
    nama_pembeli nama_customer,
    nsc.created_at,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN ''
      ELSE ch23.no_polisi
    END AS no_polisi,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.alamat_pembeli
      ELSE ch23.alamat
    END AS alamat,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.id_customer
      ELSE ch23.id_customer
    END AS id_customer,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.no_hp_pembeli
      ELSE ch23.no_hp
    END AS no_hp,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='')THEN ''
      ELSE tk_ch23.tipe_ahm
    END AS tipe_ahm,
    nsc.tot_nsc AS total_bayar,$dibayar_nsc AS dibayar,$sisa_nsc AS sisa,'' AS id_work_order,0 AS nilai_jasa,LEFT(nsc.created_at,10) AS tgl_invoice,'' AS tgl_njb, tgl_nsc,0 AS cetak_njb_ke,0 AS cetak_gab_ke,cetak_nsc_ke,'' AS created_at_wo,'' AS total_jasa,nsc.tot_nsc,'' AS tgl_jatuh_tempo";
      }
    }
    return $this->db->query("SELECT * FROM 
    (
      SELECT $select_nsc
      FROM tr_h23_nsc nsc
      JOIN tr_h3_dealer_sales_order so3 ON so3.nomor_so=nsc.id_referensi
      LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer_int=so3.id_customer_int
      LEFT JOIN ms_tipe_kendaraan tk_ch23 ON tk_ch23.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      $where_nsc
    ) AS tabel 
    $where $order $limit
    ");
  }
}
