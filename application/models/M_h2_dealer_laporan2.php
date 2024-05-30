<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_h2_dealer_laporan2 extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_master', 'm_h2');
    $this->load->model('m_h2_billing', 'm_bil');
  }

  function detailNJB($id_work_order)
  {
    $filter = ['id_work_order' => $id_work_order];
    $wo = $this->m_wo->get_sa_form($filter)->row();

    $res_pekerjaan = $this->m_h2->getPekerjaanWO($id_work_order)->result();
    $tot_pekerjaan = 0;
    $tot_ppn = 0;
    $tot_no_ppn = 0;
    foreach ($res_pekerjaan as $rs) {
      if ($rs->pekerjaan_batal == 1) continue;
      $tot_pekerjaan++;
      $tot_no_ppn += $rs->harga - (int) $rs->diskon_rp;
      $rs->diskon_rp = (int) $rs->diskon_rp;
      $pekerjaan[] = $rs;
    }
    if ($wo->pkp_njb == 1) {
      // $tot_ppn = $tot_no_ppn * (10 / 100);
      $tot_ppn = 0;
    }
    $grand_tot = $tot_no_ppn + $tot_ppn;
    $total = ['tot_pekerjaan' => $tot_pekerjaan, 'grand_tot' => $grand_tot, 'tot_ppn' => $tot_ppn, 'tot_no_ppn' => $tot_no_ppn];
    return $result = ['details' => isset($pekerjaan) ? $pekerjaan : null, 'total' => $total];
  }

  function detailNSC($filter)
  {
    $get_nsc = $this->m_bil->getNSC($filter)->row();
    // send_json($get_nsc);
    function subtotal($rs, $get_nsc)
    {
      $harga_real = $rs->harga_beli;
      $harga = $rs->harga_beli;
      if ($get_nsc->pkp == 1) {
        $harga = $harga;
      }
      if ($rs->tipe_diskon == 'Percentage') {
        $diskon = ($rs->diskon_value / 100) * $harga;
        $harga_real -= $diskon;
      }
      $qty = $rs->qty;
      if ($rs->tipe_diskon == 'FoC') {
        $qty -= $rs->diskon_value;
      }
      $potongan_harga = 0;
      if ($rs->tipe_diskon == 'Value') {
        $potongan_harga = $rs->diskon_value;
      }
      if ($get_nsc->tampil_ppn == 1) {
        $harga_real = $harga_real / 1.1;
      }
      return ($qty * $harga_real) - $potongan_harga;
    }

    $res_parts = $this->m_bil->getNSCParts($filter)->result();
    // send_json($res_parts);
    $tot_parts = 0;
    $tot_no_ppn = 0;
    foreach ($res_parts as $rs) {
      $tot_parts++;
      $subtotal     = subtotal($rs, $get_nsc);
      $rs->subtotal = $subtotal;
      $rs->dpp      = ROUND($rs->harga_beli / 1.1);
      $tot_no_ppn   += $subtotal;
      $parts[]      = $rs;
    }
    $tot_ppn = $get_nsc->tampil_ppn == 1 ? ROUND($tot_no_ppn * (10 / 100)) : 0;
    $grand_tot = ROUND(($tot_no_ppn + $tot_ppn) - $get_nsc->total_bayar);
    $total = [
      'tot_parts' => $tot_parts,
      'tot_no_ppn' => ROUND($tot_no_ppn),
      'grand_tot' => $grand_tot,
      'tot_ppn' => $tot_ppn,
      'uang_muka' => $get_nsc->total_bayar
    ];
    $result = [
      'details' => isset($parts) ? $parts : null,
      'total' => $total
    ];
    // send_json($result);
    return $result;
  }

  function getDetailTransaksiCustomer($filter = null)
  {
    //NJB
    $grand_total = 0;
    if (isset($filter['id_work_order'])) {
      $filter_njb = ['id_work_order' => $filter['id_work_order'], 'group_njb' => 1];
      // send_json($filter_njb);
      $njb = $this->m_bil->getNJB($filter_njb)->result();
      foreach ($njb as $nj) {
        $details[] = [
          'id_referensi' => $nj->no_njb,
          'nilai' => (int) $nj->harga_net,
          'tgl_transaksi' => $nj->tgl_njb
        ];
        $grand_total += $nj->harga_net;
      }

      $filter_nsc['id_work_order']   = $filter['id_work_order'];
      $filter_nsc['group_by_no_nsc'] = 1;
    } else {
      $filter_nsc['no_nsc'] = $filter['no_nsc'];
      $filter_nsc['group_by_no_nsc'] = 1;
    }

    //NSC
    // send_json($filter_nsc);
    $nsc =  $this->m_bil->getNSCParts($filter_nsc)->result();
    foreach ($nsc as $ns) {
      $fc['no_nsc'] = $ns->no_nsc;
      // send_json($ns);
      $total = $this->m_bil->getNSC($fc)->row()->tot_nsc;
      // send_json($tot);
      $uang_muka_terpakai = "SELECT uang_muka_terpakai FROM tr_h2_uang_jaminan WHERE no_inv_uang_jaminan=tr_h23_nsc.no_inv_jaminan";
      $uang_muka = "uang_muka-IFNULL(($uang_muka_terpakai),0)";
      $details[] = [
        'id_referensi' => $ns->no_nsc,
        'tgl_transaksi' => date_dmy($ns->tgl_nsc, '/'),
        'nilai' => round($total),
        'uang_muka' => $this->db->query("SELECT no_inv_jaminan,($uang_muka) uang_muka FROM tr_h23_nsc WHERE no_nsc='$ns->no_nsc' AND uang_muka>0")->row()
      ];
      $grand_total += round($total);
    }
    // send_json($details);
    return ['details' => isset($details) ? $details : null, 'grand_total' => $grand_total];
  }

  public function bayar_receipt_cash()
  {
    return "(SELECT IFNULL(SUM(nominal),0) FROM tr_h2_receipt_customer_metode rcm WHERE metode_bayar='Cash' AND rcm.id_receipt=rc.id_receipt)";
  }
  public function bayar_receipt_transfer()
  {
    return "(SELECT IFNULL(SUM(nominal),0) FROM tr_h2_receipt_customer_metode rcm WHERE metode_bayar='Transfer' AND rcm.id_receipt=rc.id_receipt)";
  }
  public function bayar_receipt_uang_muka()
  {
    return "(SELECT IFNULL(SUM(nominal),0) FROM tr_h2_receipt_customer_metode rcm WHERE metode_bayar='uang_muka' AND rcm.id_receipt=rc.id_receipt)";
  }
  public function tgl_receipt_transfer()
  {
    return "(SELECT GROUP_CONCAT(tanggal SEPARATOR ', ') FROM tr_h2_receipt_customer_metode rcm WHERE metode_bayar='Transfer' AND rcm.id_receipt=rc.id_receipt GROUP BY rcm.id_receipt)";
    // return "(SELECT tanggal FROM tr_h2_receipt_customer_metode rcm WHERE metode_bayar='Transfer' AND rcm.id_receipt=rc.id_receipt)";
  }

  function getPendapatanHarianServis($filter = null)
  {
    $kelompok_parts_in = $this->kelompok_parts();
    $kelompok_oil_in = $this->kelompok_oil();
    $cek = [
      'kelompok_parts_in' => $kelompok_parts_in,
      'kelompok_oil_in' => $kelompok_oil_in,
    ];
    // send_json($cek);

    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer     = $this->m_admin->cari_dealer();
    }
    $where = "WHERE rc.id_dealer='$id_dealer' ";

    if ($filter != null) {
      if (isset($filter['id_work_order'])) {
        $where .= " AND wo_lap.id_work_order='{$filter['id_work_order']}' ";
        if ($filter['id_work_order']=='00888/220804/WO/15456') {
          // $where.="ss";
        }
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi'])) {
        $where .= " AND rc.tgl_receipt='{$filter['tgl_transaksi']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_awal'])) {
        $where .= " AND rc.tgl_receipt>='{$filter['tgl_transaksi_awal']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_akhir'])) {
        $where .= " AND rc.tgl_receipt<='{$filter['tgl_transaksi_akhir']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tahun_bulan'])) {
        $where .= " AND LEFT(rc.tgl_receipt,7)='{$filter['tahun_bulan']}' ";
      }
    }
    // $tgl_transaksi = $filter['tgl_transaksi'];
    $bayar_receipt_cash = $this->bayar_receipt_cash();
    $bayar_receipt_transfer = $this->bayar_receipt_transfer();
    $bayar_receipt_uang_muka = $this->bayar_receipt_uang_muka();
    $tgl_receipt_transfer = $this->tgl_receipt_transfer();
    $tot_jasa_customer = $this->m_bil->getNJB(['sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_tot_tanpa_diskon' => true, 'id_dealer' => $id_dealer]);
    $tot_jasa_kpb1 = $this->m_bil->getNJB(['id_type_in' => "'ASS1'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_total' => true, 'id_dealer' => $id_dealer]);
    $tot_jasa_kpb2 = $this->m_bil->getNJB(['id_type_in' => "'ASS2'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_total' => true, 'id_dealer' => $id_dealer]);
    $tot_jasa_kpb3 = $this->m_bil->getNJB(['id_type_in' => "'ASS3'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_total' => true, 'id_dealer' => $id_dealer]);
    $tot_jasa_kpb4 = $this->m_bil->getNJB(['id_type_in' => "'ASS4'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_total' => true, 'id_dealer' => $id_dealer]);
    $tot_jasa_kpb_all = $this->m_bil->getNJB(['id_type_in' => "'ASS1','ASS2','ASS3','ASS4'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_total' => true, 'id_dealer' => $id_dealer]);
    $tot_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_oil_in", 'sql' => true, 'sum_total' => true,]);
    $tot_fed_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "'FED OIL'", 'sql' => true, 'sum_total' => true,]);

    $tot_part = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_parts_in", 'sql' => true, 'sum_total' => true]);
    // $tot_oli_kpb1 = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part' => 'OIL', 'id_type_in' => "'ASS1'", 'sql' => true, 'get_only_grand' => true]);
    $waktu = "(SELECT (SUM(detik)/60) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo_lap.id_work_order)";
    $tot_diskon_njb = $this->m_bil->getNJB(['id_type_not_in' => "'ASS1','ASS2','ASS3','ASS4'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_diskon' => true, 'id_dealer' => $id_dealer]);
    $tot_diskon = '(' . $tot_diskon_njb . ')';
    $tot_oli_kpb1 = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "'OIL'", 'sql' => true, 'sum_total' => true, 'id_type_in' => "'ASS1'"]);
    // $bayar_receipt_cash = 0;
    // $bayar_receipt_transfer = 0;
    // $tgl_receipt_transfer = 0;
    // $tot_jasa_customer = 0;
    // $tot_oli = 0;
    // $tot_part = 0;
    // $tot_diskon = 0;
    $tot_jasa_kpb1 = 0;
    $tot_oli_kpb1 = 0;
    $tot_jasa_kpb2 = 0;
    $tot_jasa_kpb3 = 0;
    $tot_jasa_kpb4 = 0;
    $select = "wo_lap.id_work_order,rct.id_receipt,nama_customer,mhp.nama as nama_pembawa,(CASE WHEN ch23.id_tipe_kendaraan is not null or ch23.id_tipe_kendaraan != '' then (SELECT (CASE WHEN kategori.kategori ='AT' then 'Matic' WHEN kategori.kategori ='CUB' then 'CUB' WHEN kategori.kategori ='SPORT' then 'Sport' else kategori.kategori end) FROM ms_tipe_kendaraan mtk JOIN ms_kategori kategori on mtk.id_kategori = kategori.id_kategori WHERE mtk.id_tipe_kendaraan=ch23.id_tipe_kendaraan) else '-' end) AS tipe_kendaraan, no_polisi,$waktu AS waktu, nama_lengkap AS mekanik,($bayar_receipt_cash) AS bayar_cash,($bayar_receipt_transfer) AS bayar_transfer,($tgl_receipt_transfer) AS tgl_transfer,($bayar_receipt_uang_muka) AS bayar_uang_muka,sa.tipe_coming, 
    ROUND(($tot_jasa_customer)) AS tot_jasa_customer,ROUND(($tot_jasa_customer))-ROUND(($tot_jasa_kpb_all)) AS tot_jasa_customer_non_kpb,
    ($tot_oli) AS tot_oli,
    ($tot_fed_oli) AS tot_fed_oli,
    ($tot_part) AS tot_part,
    ($tot_diskon) AS diskon,
    ($tot_jasa_kpb1) AS tot_jasa_kpb1,
    ($tot_oli_kpb1) AS tot_oli_kpb1,
    ($tot_jasa_kpb2) AS tot_jasa_kpb2,
    ($tot_jasa_kpb3) AS tot_jasa_kpb3,
    ($tot_jasa_kpb4) AS tot_jasa_kpb4,
    nsc_lap.no_nsc,wo_lap.no_njb";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_oli') {
        $select = "($tot_oli) AS total";
      } elseif ($filter['select'] == 'sum_non_oli') {
        $select = "($tot_part) AS total";
      }
    }
    // send_json($select);
    $kpb_ke = "SELECT RIGHT(js.id_type,1)  
        FROM tr_h2_wo_dealer_pekerjaan wopk 
        JOIN ms_h2_jasa js ON js.id_jasa=wopk.id_jasa
        WHERE wopk.id_work_order=wo_lap.id_work_order AND js.id_type IN('ASS1','ASS2','ASS3','ASS4') AND wopk.pekerjaan_batal != 1 LIMIT 1";
    $result =  $this->db->query("SELECT $select, ($kpb_ke) kpb_ke,wo_lap.id_work_order
      FROM tr_h2_receipt_customer_transaksi rct
      JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
      JOIN tr_h2_wo_dealer wo_lap ON wo_lap.no_njb=rct.id_referensi
      LEFT JOIN tr_h23_nsc nsc_lap ON nsc_lap.id_referensi=wo_lap.id_work_order
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo_lap.id_sa_form
      JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      LEFT JOIN ms_h2_pembawa mhp ON mhp.id_pembawa=sa.id_pembawa
      LEFT JOIN ms_karyawan_dealer mk ON mk.id_karyawan_dealer=wo_lap.id_karyawan_dealer
      $where
      ORDER BY rct.id_receipt ASC
      ");
    if (isset($filter['sum_final_result'])) {
      $res_ = 0;
      foreach ($result->result() as $rs) {
        $res_ += $rs->total;
      }
      return $res_;
    } else {
      return $result;
    }
  }

  function kelompok_parts()
  {
    $get_kelompok_parts = $this->db->query("SELECT id_kelompok_part FROM ms_h3_md_setting_kelompok_produk WHERE id_kelompok_part NOT IN ('FED OIL','GMO','OIL')");
    $kelompok_parts_in = [];
    foreach ($get_kelompok_parts->result() as $prt) {
      $kelompok_parts_in[] = $prt->id_kelompok_part;
    }
    return arr_in_sql($kelompok_parts_in);
  }

  function kelompok_oil()
  {
    // $get_kelompok_oil = $this->db->query("SELECT id_kelompok_part FROM ms_h3_md_setting_kelompok_produk WHERE produk='Oil'");
    // $kelompok_oil_in = [];
    // foreach ($get_kelompok_oil->result() as $prt) {
    //   $kelompok_oil_in[] = $prt->id_kelompok_part;
    // }
    // return arr_in_sql($kelompok_oil_in);
    return "'OIL','GMO'";
  }

  function getPendapatanHarianServisSalesPartsDirect($filter = null)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $where     = "WHERE rc.id_dealer = '$id_dealer' ";

    if ($filter != null) {
      if (isset($filter['tgl_transaksi'])) {
        $where .= " AND rc.tgl_receipt='{$filter['tgl_transaksi']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_awal'])) {
        $where .= " AND rc.tgl_receipt>='{$filter['tgl_transaksi_awal']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_akhir'])) {
        $where .= " AND rc.tgl_receipt<='{$filter['tgl_transaksi_akhir']}' ";
      }
    }
    // $tgl_transaksi = $filter['tgl_transaksi'];
    $bayar_receipt_cash = $this->bayar_receipt_cash();
    $bayar_receipt_transfer = $this->bayar_receipt_transfer();
    $tgl_receipt_transfer = $this->tgl_receipt_transfer();

    $kelompok_parts_in = $this->kelompok_parts();
    // send_json($kelompok_parts_in);

    $tot_part = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_parts_in", 'sql' => true, 'get_only_grand' => true]);
    $tot_qty_part = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_parts_in", 'sql' => true, 'sum_qty' => true, 'id_type_not_in' => "'ASS1'"]);

    $tot_jasa_customer = 0;
    // $tot_oli = 0;
    // $tot_part = 0;
    $tot_diskon = 0;
    $tot_jasa_kpb1 = 0;
    $tot_oli_kpb1 = 0;
    $tot_jasa_kpb2 = 0;
    $tot_jasa_kpb3 = 0;
    $tot_jasa_kpb4 = 0;

    $select = "
    so.nomor_so,rct.id_receipt,
    so.nama_pembeli AS nama_customer,($bayar_receipt_cash) AS bayar_cash,
    ($bayar_receipt_transfer) AS bayar_transfer,
    ($tgl_receipt_transfer) AS tgl_transfer,
    ROUND(($tot_jasa_customer)) AS tot_jasa_customer,
    ($tot_part) AS tot_part,
    ($tot_diskon) AS diskon,
    ($tot_jasa_kpb1) AS tot_jasa_kpb1,
    ($tot_oli_kpb1) AS tot_oli_kpb1,
    ($tot_jasa_kpb2) AS tot_jasa_kpb2,
    ($tot_jasa_kpb3) AS tot_jasa_kpb3,
    ($tot_jasa_kpb4) AS tot_jasa_kpb4,
    nsc_lap.no_nsc";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_part') {
        $select = "SUM(($tot_part)) total,SUM(($tot_qty_part)) total_qty";
      }
    }

    $res_q = $this->db->query("SELECT $select
    FROM tr_h2_receipt_customer_transaksi rct
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
    JOIN tr_h23_nsc nsc_lap ON nsc_lap.no_nsc=rct.id_referensi
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=nsc_lap.id_referensi
    -- LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=so.id_customer
    $where
    order by nsc_lap.no_nsc asc, rc.id_receipt asc
    ");

    return $res_q;
  }
  function getPendapatanHarianServisSalesOliDirect($filter = null)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $where     = "WHERE rc.id_dealer = '$id_dealer' ";

    if ($filter != null) {
      if (isset($filter['tgl_transaksi'])) {
        $where .= " AND rc.tgl_receipt='{$filter['tgl_transaksi']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_awal'])) {
        $where .= " AND rc.tgl_receipt>='{$filter['tgl_transaksi_awal']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_akhir'])) {
        $where .= " AND rc.tgl_receipt<='{$filter['tgl_transaksi_akhir']}' ";
      }
    }
    // $tgl_transaksi = $filter['tgl_transaksi'];
    $bayar_receipt_cash = $this->bayar_receipt_cash();
    $bayar_receipt_transfer = $this->bayar_receipt_transfer();
    $tgl_receipt_transfer = $this->tgl_receipt_transfer();

    $kelompok_oil_in = $this->kelompok_oil() . ",'FED OIL'";

    $tot_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_oil_in", 'sum_total' => true, 'sql' => true, 'get_only_grand' => true]);
    $tot_qty_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_oil_in", 'sql' => true, 'sum_qty' => true, 'id_type_not_in' => "'ASS1'"]);

    $tot_jasa_customer = 0;
    // $tot_oli = 0;
    // $tot_part = 0;
    $tot_diskon = 0;
    $tot_jasa_kpb1 = 0;
    $tot_oli_kpb1 = 0;
    $tot_jasa_kpb2 = 0;
    $tot_jasa_kpb3 = 0;
    $tot_jasa_kpb4 = 0;

    $select = "
    so.nomor_so,rct.id_receipt,
    so.nama_pembeli AS nama_customer,($bayar_receipt_cash) AS bayar_cash,
    ($bayar_receipt_transfer) AS bayar_transfer,
    ($tgl_receipt_transfer) AS tgl_transfer,
    ROUND(($tot_jasa_customer)) AS tot_jasa_customer,
    ($tot_oli) AS tot_oli,
    ($tot_diskon) AS diskon,
    ($tot_jasa_kpb1) AS tot_jasa_kpb1,
    ($tot_oli_kpb1) AS tot_oli_kpb1,
    ($tot_jasa_kpb2) AS tot_jasa_kpb2,
    ($tot_jasa_kpb3) AS tot_jasa_kpb3,
    ($tot_jasa_kpb4) AS tot_jasa_kpb4,
    nsc_lap.no_nsc";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_oli') {
        $select = "SUM(($tot_oli)) total,SUM(($tot_qty_oli)) total_qty";
      }
    }

    $res_q = $this->db->query("SELECT $select
    FROM tr_h2_receipt_customer_transaksi rct
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
    JOIN tr_h23_nsc nsc_lap ON nsc_lap.no_nsc=rct.id_referensi
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=nsc_lap.id_referensi
    -- LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=so.id_customer
    $where
    ");

    return $res_q;
  }

  function getLaporanKPB($filter)
  {
    foreach ($filter['kpb'] as $kpb) {
      $detail_kpb = [];
      $start = strtotime($filter['bulan_awal'] . '-01');
      $end = strtotime($filter['bulan_akhir'] . '-01');
      while ($start <= $end) {
        $show_bulan =  date('M-y', $start);
        $njb = [];
        for ($i = 1; $i <= 31; $i++) {
          $tgl = date('Y-m', $start) . '-' . sprintf("%02d", $i);
          $filter_njb = ['tgl_njb' => $tgl, 'group_njb' => 1, 'pekerjaan_kpb' => $kpb];
          $njb[] = ['tgl' => $i, 'tot' => $this->m_bil->getNJB($filter_njb)->num_rows()];
        }
        $detail_kpb[] = ['bulan' => $show_bulan, 'data' => $njb];
        $start = strtotime("+1 month", $start);
      }
      $result[] = [
        'kpb' => $kpb,
        'details' => $detail_kpb
      ];
    }
    return $result;
  }

  function getLaporanSalesHarianByNSC($filter)
  {
    $where = "WHERE nsc.id_dealer = '" . dealer()->id_dealer . "' AND nsc_p.qty>0";
    if (isset($filter['start_date']) && isset($filter['end_date'])) {
      $where .= " AND tgl_nsc BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}'";
    }
    if (isset($filter['tgl_nsc'])) {
      $where .= " AND tgl_nsc='{$filter['tgl_nsc']}'";
    }
    if (isset($filter['bln_nsc'])) {
      $where .= " AND LEFT(tgl_nsc,7)='{$filter['bln_nsc']}'";
    }
    if (isset($filter['bln_nsc_sql'])) {
      $where .= " AND LEFT(tgl_nsc,7)={$filter['bln_nsc_sql']}";
    }
    if (isset($filter['except_kelompok_part'])) {
      $where .= " AND prt.kelompok_part NOT IN({$filter['except_kelompok_part']})";
    }
    if (isset($filter['kelompok_part_in'])) {
      $where .= " AND prt.kelompok_part IN({$filter['kelompok_part_in']})";
    }

    $potongan_nsc = "
    (CASE 
      WHEN nsc_p.tipe_diskon='Value' THEN diskon_value
      ELSE 0
     END
    )
    ";
    $harga_beli = "
      (CASE 
        WHEN nsc.pkp=1 THEN 
          CASE 
            WHEN nsc_p.tipe_diskon='Percentage' THEN nsc_p.harga_beli - ((nsc_p.harga_beli)*(nsc_p.diskon_value/100))
            ELSE nsc_p.harga_beli
          END
        ELSE nsc_p.harga_beli
        END
      )
    ";
    $qty = "
      (CASE 
        WHEN nsc_p.tipe_diskon='FoC' THEN nsc_p.qty-nsc_p.diskon_value
        ELSE nsc_p.qty
       END
      )
    ";
    $tot_nsc_fil = "(($harga_beli*$qty)-$potongan_nsc)";
    $tot_nsc = "nsc.tot_nsc";

    $select = "nsc.tgl_nsc,nsc.no_nsc,nsc_p.id_part,nama_part,nsc_p.harga_beli,nsc_p.qty,nsc_p.tipe_diskon,nsc_p.diskon_value,
    CASE 
      WHEN ps_so.nomor_so IS NOT NULL THEN ps_so.nomor_so 
      WHEN ps_wo.nomor_so IS NOT NULL THEN ps_wo.nomor_so
    END AS nomor_so,
    CASE 
      WHEN ps_so.nomor_so IS NOT NULL THEN ps_so.nomor_ps 
      WHEN ps_wo.nomor_so IS NOT NULL THEN ps_wo.nomor_ps
    END AS nomor_ps,
    nsc.no_inv_jaminan no_inv_uang_jaminan,
    $tot_nsc AS total, nsc_p.diskon_value diskon,prt.kelompok_part,(nsc_p.qty*nsc_p.harga_beli)-IFNULL(nsc_p.diskon_value,0) subtotal ";
    if (isset($filter['sum_total'])) {
      $select .= ", SUM($tot_nsc) AS sum_total";
    }
    if (isset($filter['sum_total_filter_kelompok'])) {
      $select .= ", SUM($tot_nsc_fil) AS sum_total";
    }
    if (isset($filter['sum_qty'])) {
      if ($filter['sum_qty'] == true) {
        $select = "SUM(qty) AS sum_qty";
      }
    }
    if (isset($filter['amount_parts'])) {
      if ($filter['amount_parts'] == true) {
        $select = "SUM($tot_nsc) AS amount_parts";
      }
    }
    $sql = "SELECT $select 
    FROM tr_h23_nsc_parts nsc_p
    JOIN tr_h23_nsc nsc ON nsc.no_nsc=nsc_p.no_nsc
    JOIN ms_part prt ON prt.id_part=nsc_p.id_part
    LEFT JOIN tr_h3_dealer_picking_slip ps_so ON ps_so.nomor_so=nsc.id_referensi
    LEFT JOIN tr_h3_dealer_sales_order so_so ON so_so.nomor_so=nsc.id_referensi
    LEFT JOIN tr_h3_dealer_picking_slip ps_wo ON ps_wo.nomor_so=nsc_p.nomor_so_wo
    LEFT JOIN tr_h3_dealer_sales_order so_wo ON so_wo.nomor_so=nsc_p.nomor_so_wo
    $where
    ORDER BY nsc_p.no_nsc ASC
    ";
    if (isset($filter['sql'])) {
      return $sql;
    } else {
      return $this->db->query($sql);
    }
  }

  function getUnitEntri($filter = null)
  {
    $where = "WHERE 1=1 ";
    if (dealer() != false) {
      $where = "WHERE wo.id_dealer='" . dealer()->id_dealer . "'";
    }
    if ($filter != null) {
      if (isset($filter['bulan_awal']) && isset($filter['bulan_akhir'])) {
        $where .= " AND LEFT(wo.created_at,7) BETWEEN '{$filter['bulan_awal']}' AND '{$filter['bulan_akhir']}'";
      }
      if (isset($filter['bulan_servis'])) {
        $where .= " AND LEFT(wo.created_at,7) ='{$filter['bulan_servis']}'";
      }
      if (isset($filter['bulan_njb'])) {
        $where .= " AND LEFT(waktu_njb,7) ='{$filter['bulan_njb']}'";
      }
      if (isset($filter['id_tipe_kendaraan_sql'])) {
        $where .= " AND tks.id_tipe_kendaraan={$filter['id_tipe_kendaraan_sql']}";
      }
      if (isset($filter['njb_not_null'])) {
        // $where .= " AND no_njb IS NOT NULL ";
      }
      if (isset($filter['id_type_in'])) {
        $where .= " AND EXISTS(SELECT id_work_order FROM tr_h2_wo_dealer_pekerjaan wop
        JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
        WHERE wop.id_work_order=wo.id_work_order AND js.id_type IN({$filter['id_type_in']})
        ) ";
      }
      if (isset($filter['id_type_not_in'])) {
        $where .= " AND EXISTS(SELECT id_work_order FROM tr_h2_wo_dealer_pekerjaan wop
        JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
        WHERE wop.id_work_order=wo.id_work_order AND js.id_type NOT IN({$filter['id_type_not_in']})
        ) ";
      }
      if (isset($filter['bln_servis_sql'])) {
        $where .= " AND LEFT(tgl_servis,7) ={$filter['bln_servis_sql']}";
      }
      if (isset($filter['tgl_servis'])) {
        $where .= " AND tgl_servis ='{$filter['tgl_servis']}'";
      }
      if (isset($filter['created_at'])) {
        $where .= " AND LEFT(wo.created_at,10) ='{$filter['created_at']}'";
      }
      if (isset($filter['status_wo'])) {
        $where .= " AND wo.status='{$filter['status_wo']}'";
      }
      if (isset($filter['sql_no_mesin'])) {
        $where .= " AND ch23.no_mesin={$filter['sql_no_mesin']}";
      }
      if (isset($filter['concat_tgl_servis'])) {
        $where .= " AND tgl_servis =CONCAT('{$filter['concat_tgl_servis']}',tgl) ";
      }
    }
    $sql = "SELECT COUNT(id_work_order) AS tot 
      FROM tr_h2_wo_dealer wo  
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      $where
    ";
    if (isset($filter['sql'])) {
      return $sql;
    } else {
      return $this->db->query($sql)->row()->tot;
    }
  }

  function getLaporanHarianAHASS($filter)
  {
    //Unit Entri
    $start = strtotime($filter['bulan_awal'] . '-01');
    $end = strtotime($filter['bulan_akhir'] . '-01');
    while ($start <= $end) {
      $show_bulan =  date('M-y', $start);
      $u_e = [];
      $prt = [];
      $prt_oli = [];
      $prt_gmo = [];
      $js = [];
      for ($i = 1; $i <= 31; $i++) {
        $tgl = date('Y-m', $start) . '-' . sprintf("%02d", $i);
        $filter = ['created_at' => $tgl, 'status_wo' => 'closed'];
        $u_e[] = ['tgl' => $i, 'tot' => $this->getUnitEntri($filter)];

        $filter = ['tgl_nsc' => $tgl, 'except_kelompok_part' => "'OIL','GMO','FED OIL','ACC'", 'sum_total_filter_kelompok' => true];
        $prt[] = ['tgl' => $i, 'tot' => $this->getLaporanSalesHarianByNSC($filter)->row()->sum_total];

        $filter = ['tgl_nsc' => $tgl, 'kelompok_part_in' => "'OIL','FED OIL'", 'sum_total_filter_kelompok' => true];
        $prt_oli[] = ['tgl' => $i, 'tot' => $this->getLaporanSalesHarianByNSC($filter)->row()->sum_total];

        $filter = ['tgl_nsc' => $tgl, 'kelompok_part_in' => "'GMO'", 'sum_total_filter_kelompok' => true];
        $prt_gmo[] = ['tgl' => $i, 'tot' => $this->getLaporanSalesHarianByNSC($filter)->row()->sum_total];

        $filter = ['start_tgl_wo' => $tgl, 'end_tgl_wo' => $tgl, 'group_tgl_wo' => true];
        $res_js = $this->m_bil->getNJB($filter);
        $js[] = ['tgl' => $i, 'tot' => $res_js->num_rows() > 0 ? $res_js->row()->harga_net : 0];
      }
      $unit_entri[] = ['bulan' => $show_bulan, 'data' => $u_e];
      $parts[] = ['bulan' => $show_bulan, 'data' => $prt];
      $parts_oli[] = ['bulan' => $show_bulan, 'data' => $prt_oli];
      $parts_gmo[] = ['bulan' => $show_bulan, 'data' => $prt_gmo];
      $jasa[] = ['bulan' => $show_bulan, 'data' => $js];
      $start = strtotime("+1 month", $start);
    }
    $result = [
      'unit_entri' => $unit_entri,
      'parts' => $parts,
      'parts_oli' => $parts_oli,
      'parts_gmo' => $parts_gmo,
      'jasa' => $jasa
    ];
    return $result;
  }

  function getLaporanSalesKelompokPart($filter)
  {
    $filters = [
      'bln_servis_sql' => "DATE_FORMAT(m1, '%Y-%m')",
      'bln_nsc_sql' => "DATE_FORMAT(m1, '%Y-%m')",
      'kelompok_part_in' => "'OIL'",
      'sql' => true,
      'sum_qty' => true
    ];
    $unit_entri = $this->getUnitEntri($filters);

    $parts_qty_oli = $this->getLaporanSalesHarianByNSC($filters);
    unset($filters['sum_qty']);
    $filters['amount_parts'] = true;
    $parts_amount_oli = $this->getLaporanSalesHarianByNSC($filters);

    unset($filters['amount_parts']);
    $filters['sum_qty'] = true;
    $filters['kelompok_part_in'] = "'GMO'";
    $parts_qty_gmo = $this->getLaporanSalesHarianByNSC($filters);
    unset($filters['sum_qty']);
    $filters['amount_parts'] = true;
    $parts_amount_gmo = $this->getLaporanSalesHarianByNSC($filters);

    unset($filters['amount_parts']);
    $filters['sum_qty'] = true;
    $filters['kelompok_part_in'] = "'HIC'";
    $parts_qty_hic = $this->getLaporanSalesHarianByNSC($filters);
    unset($filters['sum_qty']);
    $filters['amount_parts'] = true;
    $parts_amount_hic = $this->getLaporanSalesHarianByNSC($filters);

    unset($filters['amount_parts']);
    $filters['sum_qty'] = true;
    $filters['kelompok_part_in'] = "'HPC'";
    $parts_qty_hpc = $this->getLaporanSalesHarianByNSC($filters);
    unset($filters['sum_qty']);
    $filters['amount_parts'] = true;
    $parts_amount_hpc = $this->getLaporanSalesHarianByNSC($filters);

    unset($filters['amount_parts']);
    $filters['sum_qty'] = true;
    $filters['kelompok_part_in'] = "'cvt'";
    $parts_qty_cvt = $this->getLaporanSalesHarianByNSC($filters);
    unset($filters['sum_qty']);
    $filters['amount_parts'] = true;
    $parts_amount_cvt = $this->getLaporanSalesHarianByNSC($filters);

    unset($filters['amount_parts']);
    $filters['sum_qty'] = true;
    $filters['kelompok_part_in'] = "'chain'";
    $parts_qty_chain = $this->getLaporanSalesHarianByNSC($filters);
    unset($filters['sum_qty']);
    $filters['amount_parts'] = true;
    $parts_amount_chain = $this->getLaporanSalesHarianByNSC($filters);

    return $this->db->query("SELECT
      DATE_FORMAT(m1, '%Y-%m') AS ym, 
      IFNULL(($unit_entri),0) AS ue,
      IFNULL(($parts_qty_oli),0) AS qty_oli,
      ROUND(IFNULL(($parts_amount_oli),0)) AS amount_oli,
      IFNULL(($parts_qty_gmo),0) AS qty_gmo,
      ROUND(IFNULL(($parts_amount_gmo),0)) AS amount_gmo,
      IFNULL(($parts_qty_hic),0) AS qty_hic,
      ROUND(IFNULL(($parts_amount_hic),0)) AS amount_hic,
      IFNULL(($parts_qty_hpc),0) AS qty_hpc,
      ROUND(IFNULL(($parts_amount_hpc),0)) AS amount_hpc,
      IFNULL(($parts_qty_cvt),0) AS qty_cvt,
      ROUND(IFNULL(($parts_amount_cvt),0)) AS amount_cvt,
      IFNULL(($parts_qty_chain),0) AS qty_chain,
      ROUND(IFNULL(($parts_amount_chain),0)) AS amount_chain,
      ROUND(IFNULL((IFNULL(($unit_entri),0)/IFNULL(($parts_qty_oli),0)),0)) AS ue_oli,
      ROUND(IFNULL((IFNULL(($unit_entri),0)/IFNULL(($parts_qty_gmo),0)),0)) AS ue_gmo,
      ROUND(IFNULL((IFNULL(($unit_entri),0)/IFNULL(($parts_qty_hic),0)),0)) AS ue_hic,
      ROUND(IFNULL((IFNULL(($unit_entri),0)/IFNULL(($parts_qty_hpc),0)),0)) AS ue_hpc,
      ROUND(IFNULL((IFNULL(($unit_entri),0)/IFNULL(($parts_qty_cvt),0)),0)) AS ue_cvt,
      ROUND(IFNULL((IFNULL(($unit_entri),0)/IFNULL(($parts_qty_chain),0)),0)) AS ue_chain
    FROM " . sql_generate_ym($filter))->result();
  }

  public function getLaporanSalesKelompokHarian($filter)
  {
    foreach ($filter['kelompok'] as $key => $kel) {
      $start = strtotime($filter['bulan_awal'] . '-01');
      $end = strtotime($filter['bulan_akhir'] . '-01');
      $result = [];
      while ($start <= $end) {
        $show_bulan =  date('M-y', $start);
        $bulan =  date('Y-m-', $start);
        $filter_ue = ['concat_tgl_servis' => $bulan, 'status_wo' => 'closed', 'sql' => true, 'njb_not_null' => true];
        $unit_entri = $this->getUnitEntri($filter_ue);

        $filter_ass1 = [
          'concat_tgl_servis' => $bulan,
          'status_wo' => 'closed',
          'sql' => true,
          'njb_not_null' => true,
          'id_type_in' => "'ASS1'"
        ];
        $ass1 = $this->getUnitEntri($filter_ass1);

        $filter_ass24 = [
          'concat_tgl_servis' => $bulan,
          'status_wo' => 'closed',
          'njb_not_null' => true,
          'id_type_in' => "'ASS2','ASS3','ASS4'",
          'sql' => true
        ];
        $ass24 = $this->getUnitEntri($filter_ass24);

        $filter_others = [
          'concat_tgl_servis' => $bulan,
          'status_wo' => 'closed',
          'njb_not_null' => true,
          'id_type_not_in' => "'ASS1','ASS2','ASS3','ASS4'",
          'sql' => true
        ];
        $others = $this->getUnitEntri($filter_others);
        // $oil = "ROUND(IFNULL(($ass1),0) + IFNULL(($ass24),0) + IFNULL(($others),0))";
        $filter_oil = [
          'concat_tgl_nsc' => $bulan,
          'kelompok_part' => $key,
          'sum_qty' => true,
          'sql' => true
        ];
        $oil = $this->m_bil->getNSCParts($filter_oil);
        $res = $this->db->query("SELECT
          IFNULL(($unit_entri),0) AS ue,
          ROUND(IFNULL(($ass1),0)) AS ass1,
          ROUND(IFNULL(($ass24),0)) AS ass24,
          ROUND(IFNULL(($others),0)) AS others,
          ($oil) AS oil,
          ROUND(IFNULL(($oil)/IFNULL(($unit_entri),0),0)) AS ach
          FROM " . sql_generate_tanggal())->result();
        $result[] = ['bulan' => $show_bulan, 'data' => $res];
        $start = strtotime("+1 month", $start);
      }
      $dts[$key] = $result;
    }
    return $dts;
  }
  function getLaporanBulananBengkel($filter = null)
  {
    $tipe_kendaraan = $this->m_wo->getJumlahJobPerTipe($filter)->result();
    send_json($tipe_kendaraan);
  }

  function getNSCTotal($where)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_dealer'])) {
      $where .= " AND id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['tahun_bulan'])) {
      $where .= " AND LEFT(created_at,7)='{$filter['tahun_bulan']}'";
    }
    return $this->db->query("SELECT SUM(tot_nsc_oli) tot_oli,SUM(tot_nsc_part) tot_part FROM tr_h23_nsc $where")->row();
  }

  function getWOClosedByFilter($filter)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer     = $this->m_admin->cari_dealer();
    }
    $cek_part = "SELECT ifnull(SUM(woprt.qty-kuantitas_return),0)
                 FROM tr_h2_wo_dealer_parts woprt 
                 LEFT JOIN tr_h3_dealer_sales_order_parts spt ON spt.nomor_so=woprt.nomor_so AND spt.id_part_int=woprt.id_part_int
                 WHERE woprt.id_work_order=wo.id_work_order
                 AND (pekerjaan_batal IS NULL OR pekerjaan_batal=0)
                 ";

    $where = "WHERE wo.id_dealer='$id_dealer' 
              AND wo.status='closed' 
              AND IFNULL(wo.no_njb,'')!=''
              AND (
                CASE 
                  WHEN IFNULL(($cek_part),0)=0 THEN 1
                  ELSE CASE WHEN nsc.id_referensi IS NULL THEN 0 ELSE 1 END
                END
              )=1
              ";
    if (isset($filter['tgl_transaksi'])) {
      $where .= " AND LEFT(wo.closed_at,10)='{$filter['tgl_transaksi']}'";
    }
    if (isset($filter['tgl_transaksi_awal'])) {
      $where .= " AND LEFT(wo.closed_at,10) >='{$filter['tgl_transaksi_awal']}'";
    }
    if (isset($filter['tgl_transaksi_akhir'])) {
      $where .= " AND LEFT(wo.closed_at,10) <='{$filter['tgl_transaksi_akhir']}'";
    }
    return $this->db->query("SELECT id_work_order
    FROM tr_h2_wo_dealer wo
    LEFT JOIN tr_h23_nsc nsc ON nsc.id_referensi=wo.id_work_order
    $where
    ORDER BY wo.id_work_order ASC
    ");
  }
  function getDetailWOClosedByFilter($filter)
  {
    $kelompok_parts_in = $this->kelompok_parts();
    $kelompok_oil_in = $this->kelompok_oil();
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer     = $this->m_admin->cari_dealer();
    }

    // $cek_part = "SELECT COUNT(id_work_order) FROM tr_h2_wo_dealer_parts woprt WHERE woprt.id_work_order=wo_lap.id_work_order";
	/*    $cek_part = "
		select sum(c.kuantitas - c.kuantitas_return )
		from tr_h3_dealer_picking_slip a 
		join tr_h3_dealer_sales_order b on a.nomor_so = b.nomor_so 
		join tr_h3_dealer_sales_order_parts c on a.nomor_so = c.nomor_so 
		where b.id_work_order=wo_lap.id_work_order
	";
	*/

     $cek_part = "SELECT ifnull(SUM(woprt.qty-kuantitas_return),0)
                 FROM tr_h2_wo_dealer_parts woprt 
                 LEFT JOIN tr_h3_dealer_sales_order_parts spt ON spt.nomor_so=woprt.nomor_so AND spt.id_part_int=woprt.id_part_int
                 WHERE woprt.id_work_order=wo_lap.id_work_order
                 AND (pekerjaan_batal IS NULL OR pekerjaan_batal=0)
                 ";

    $tot_jasa_customer = $this->m_bil->getNJB(['sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_tot_tanpa_diskon' => true, 'id_dealer' => $id_dealer]);

    $tot_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc.no_nsc", 'kelompok_part_in' => "$kelompok_oil_in", 'sql' => true, 'sum_total' => true,]);
    $tot_fed_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc.no_nsc", 'kelompok_part_in' => "'FED OIL'", 'sql' => true, 'sum_total' => true,]);
    $tot_part = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc.no_nsc", 'kelompok_part_in' => "$kelompok_parts_in", 'sql' => true, 'sum_total' => true]);
    $waktu = "(SELECT (SUM(detik)/60) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo_lap.id_work_order)";
    $tot_diskon_njb = $this->m_bil->getNJB(['id_type_not_in' => "'ASS1','ASS2','ASS3','ASS4'", 'sql' => true, 'sql_id_work_order' => "wo_lap.id_work_order", 'sum_diskon' => true, 'id_dealer' => $id_dealer]);
    $tot_diskon = '(' . $tot_diskon_njb . ')';
    $kpb_ke = "SELECT RIGHT(js.id_type,1)  
    FROM tr_h2_wo_dealer_pekerjaan wopk 
    JOIN ms_h2_jasa js ON js.id_jasa=wopk.id_jasa
    WHERE wopk.id_work_order=wo_lap.id_work_order AND js.id_type IN('ASS1','ASS2','ASS3','ASS4') LIMIT 1";

  $tipe_kendaraan = "(CASE WHEN ch23.id_tipe_kendaraan is not null or ch23.id_tipe_kendaraan != '' then (SELECT (CASE WHEN kategori.kategori ='AT' then 'Matic' WHEN kategori.kategori ='CUB' then 'CUB' WHEN kategori.kategori ='SPORT' then 'Sport' else kategori.kategori end) FROM ms_tipe_kendaraan mtk JOIN ms_kategori kategori on mtk.id_kategori = kategori.id_kategori WHERE mtk.id_tipe_kendaraan=ch23.id_tipe_kendaraan) else '-' end)";

    $where = "WHERE wo_lap.id_dealer='$id_dealer' 
              AND wo_lap.status='closed' 
              AND IFNULL(wo_lap.no_njb,'')!=''
              AND (
                CASE 
                  WHEN ($cek_part)=0 THEN 1
                  ELSE CASE WHEN nsc.id_referensi IS NULL THEN 0 ELSE 1 END
                END
              )=1
              ";
    if (isset($filter['id_work_order'])) {
      $where .= " AND wo_lap.id_work_order='{$filter['id_work_order']}'";
    }
    return $this->db->query("SELECT wo_lap.id_work_order,'' id_receipt, $tipe_kendaraan as tipe_kendaraan, nama_customer,no_polisi,($waktu) waktu,nama_lengkap AS mekanik,no_njb,nsc.no_nsc,ROUND(($tot_jasa_customer)) AS tot_jasa_customer,0 bayar_cash,0 bayar_transfer,0 bayar_uang_muka,
    LEFT(wo_lap.closed_at,10) tgl_transfer,
    0 tot_oli_kpb1,
    ($tot_oli) tot_oli,
    ($tot_fed_oli) tot_fed_oli,
    ($tot_part) tot_part,
    ($tot_diskon) diskon,
    ($kpb_ke) kpb_ke,0 tot_jasa_kpb1,0 tot_jasa_kpb2, 0 tot_jasa_kpb3, 0 tot_jasa_kpb4
    FROM tr_h2_wo_dealer wo_lap
    LEFT JOIN tr_h23_nsc nsc ON nsc.id_referensi=wo_lap.id_work_order
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo_lap.id_sa_form
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN ms_karyawan_dealer mk ON mk.id_karyawan_dealer=wo_lap.id_karyawan_dealer
    $where
    ");
  }

  function getPendapatanHarianServisSalesOliDirect_non_fed($filter = null)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $where     = "WHERE rc.id_dealer = '$id_dealer' ";

    if ($filter != null) {
      if (isset($filter['tgl_transaksi'])) {
        $where .= " AND rc.tgl_receipt='{$filter['tgl_transaksi']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_awal'])) {
        $where .= " AND rc.tgl_receipt>='{$filter['tgl_transaksi_awal']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_akhir'])) {
        $where .= " AND rc.tgl_receipt<='{$filter['tgl_transaksi_akhir']}' ";
      }
    }
    // $tgl_transaksi = $filter['tgl_transaksi'];
    $bayar_receipt_cash = $this->bayar_receipt_cash();
    $bayar_receipt_transfer = $this->bayar_receipt_transfer();
    $tgl_receipt_transfer = $this->tgl_receipt_transfer();

    $kelompok_oil_in = $this->kelompok_oil();

    $tot_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_oil_in", 'sum_total' => true, 'sql' => true, 'get_only_grand' => true, 'kelompok_part_not_in' =>"'FED OIL'"]);
    $tot_qty_oli = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_oil_in", 'sql' => true, 'sum_qty' => true, 'id_type_not_in' => "'ASS1'",'kelompok_part_not_in' =>"'FED OIL'"]);

    $tot_jasa_customer = 0;
    // $tot_oli = 0;
    // $tot_part = 0;
    $tot_diskon = 0;
    $tot_jasa_kpb1 = 0;
    $tot_oli_kpb1 = 0;
    $tot_jasa_kpb2 = 0;
    $tot_jasa_kpb3 = 0;
    $tot_jasa_kpb4 = 0;

    $select = "
    so.nomor_so,rct.id_receipt,
    so.nama_pembeli AS nama_customer,($bayar_receipt_cash) AS bayar_cash,
    ($bayar_receipt_transfer) AS bayar_transfer,
    ($tgl_receipt_transfer) AS tgl_transfer,
    ROUND(($tot_jasa_customer)) AS tot_jasa_customer,
    ($tot_oli) AS tot_oli,
    ($tot_diskon) AS diskon,
    ($tot_jasa_kpb1) AS tot_jasa_kpb1,
    ($tot_oli_kpb1) AS tot_oli_kpb1,
    ($tot_jasa_kpb2) AS tot_jasa_kpb2,
    ($tot_jasa_kpb3) AS tot_jasa_kpb3,
    ($tot_jasa_kpb4) AS tot_jasa_kpb4,
    nsc_lap.no_nsc";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_oli') {
        $select = "SUM(($tot_oli)) total,SUM(($tot_qty_oli)) total_qty";
      }
    }

    $res_q = $this->db->query("SELECT $select
    FROM tr_h2_receipt_customer_transaksi rct
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
    JOIN tr_h23_nsc nsc_lap ON nsc_lap.no_nsc=rct.id_referensi
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=nsc_lap.id_referensi
    -- LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=so.id_customer
    $where
    ");

    return $res_q;
  }

  function getPendapatanHarianServisSalesPartsDirect_non_fed($filter = null)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $where     = "WHERE rc.id_dealer = '$id_dealer' ";

    if ($filter != null) {
      if (isset($filter['tgl_transaksi'])) {
        $where .= " AND rc.tgl_receipt='{$filter['tgl_transaksi']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_awal'])) {
        $where .= " AND rc.tgl_receipt>='{$filter['tgl_transaksi_awal']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['tgl_transaksi_akhir'])) {
        $where .= " AND rc.tgl_receipt<='{$filter['tgl_transaksi_akhir']}' ";
      }
    }
    // $tgl_transaksi = $filter['tgl_transaksi'];
    $bayar_receipt_cash = $this->bayar_receipt_cash();
    $bayar_receipt_transfer = $this->bayar_receipt_transfer();
    $tgl_receipt_transfer = $this->tgl_receipt_transfer();

    $kelompok_parts_in = $this->kelompok_parts();
    // send_json($kelompok_parts_in);

    $tot_part = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_parts_in", 'sql' => true, 'get_only_grand' => true,'kelompok_part_not_in' =>"'FED OIL'"]);
    $tot_qty_part = $this->m_bil->getNSCParts(['sql_no_nsc' => "nsc_lap.no_nsc", 'kelompok_part_in' => "$kelompok_parts_in", 'sql' => true, 'sum_qty' => true, 'id_type_not_in' => "'ASS1'",'kelompok_part_not_in' =>"'FED OIL'"]);

    $tot_jasa_customer = 0;
    // $tot_oli = 0;
    // $tot_part = 0;
    $tot_diskon = 0;
    $tot_jasa_kpb1 = 0;
    $tot_oli_kpb1 = 0;
    $tot_jasa_kpb2 = 0;
    $tot_jasa_kpb3 = 0;
    $tot_jasa_kpb4 = 0;

    $select = "
    so.nomor_so,rct.id_receipt,
    so.nama_pembeli AS nama_customer,($bayar_receipt_cash) AS bayar_cash,
    ($bayar_receipt_transfer) AS bayar_transfer,
    ($tgl_receipt_transfer) AS tgl_transfer,
    ROUND(($tot_jasa_customer)) AS tot_jasa_customer,
    ($tot_part) AS tot_part,
    ($tot_diskon) AS diskon,
    ($tot_jasa_kpb1) AS tot_jasa_kpb1,
    ($tot_oli_kpb1) AS tot_oli_kpb1,
    ($tot_jasa_kpb2) AS tot_jasa_kpb2,
    ($tot_jasa_kpb3) AS tot_jasa_kpb3,
    ($tot_jasa_kpb4) AS tot_jasa_kpb4,
    nsc_lap.no_nsc";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_part') {
        $select = "SUM(($tot_part)) total,SUM(($tot_qty_part)) total_qty";
      }
    }

    $res_q = $this->db->query("SELECT $select
    FROM tr_h2_receipt_customer_transaksi rct
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
    JOIN tr_h23_nsc nsc_lap ON nsc_lap.no_nsc=rct.id_referensi
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=nsc_lap.id_referensi
    -- LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=so.id_customer
    $where
    order by nsc_lap.no_nsc asc, rc.id_receipt asc
    ");

    return $res_q;
  }
}
