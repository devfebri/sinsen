<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_claim extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
  }

  function getClaim($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    // send_json($filter);
    $where_utama = "WHERE so.id_dealer = '$id_dealer' AND no_invoice IS NOT NULL AND sp.id_program_md IS NOT NULL";

    $where_gabungan = "WHERE so.id_dealer = '$id_dealer' AND no_invoice IS NOT NULL AND sp.id_program_md IS NOT NULL";

    if (isset($filter['id_claim'])) {
      if ($filter['id_claim'] != '') {
        $where_utama .= " AND id_claim ='{$filter['id_claim']}'";
        $where_gabungan .= " AND id_claim ='{$filter['id_claim']}'";
      }
    }
    if (isset($filter['id_claim_int'])) {
      if ($filter['id_claim_int'] != '') {
        $where_utama .= " AND id_claim_int ='{$filter['id_claim_int']}'";
        $where_gabungan .= " AND id_claim_int ='{$filter['id_claim_int']}'";
      }
    }
    if (isset($filter['status_spk'])) {
      if ($filter['status_spk'] != '') {
        $where_utama .= " AND status_spk ='{$filter['status_spk']}'";
        $where_gabungan .= " AND status_spk ='{$filter['status_spk']}'";
      }
    }
    if (isset($filter['status_proposal'])) {
      if ($filter['status_proposal'] == 'set_null') {
        $where_utama .= " AND status_proposal IS NULL";
        $where_gabungan .= " AND status_proposal IS NULL";
      } else {
        $where_utama .= " AND status_proposal ='{$filter['status_proposal']}'";
        $where_gabungan .= " AND status_proposal ='{$filter['status_proposal']}'";
      }
    }
    $limit = ' LIMIT 5';
    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    $order = "ORDER BY created_at DESC";

    return $this->db->query("
    SELECT * FROM (
      SELECT 
        program_umum AS id_program_md, tr_spk.no_spk,so.id_sales_order, so.no_mesin,so.no_rangka,harga_on_road,
        CASE WHEN voucher_1 IS NULL OR voucher_1=0 THEN voucher_2 ELSE voucher_1 END as voucher,
        so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'umum' AS jenis_program,id_claim,tr_claim_dealer.alasan_reject,so.id_sales_order_int,LEFT(so.created_at,10) AS tgl_so,tr_spk.nama_konsumen,id_claim_int,tr_claim_dealer.status status_claim
        FROM tr_sales_order AS so
        JOIN tr_spk ON so.no_spk=tr_spk.no_spk
        JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=so.no_mesin
        JOIN tr_sales_program sp ON sp.id_program_md=tr_spk.program_umum
        LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_umum=tr_claim_dealer.id_program_md
        $where_utama
      UNION 
      SELECT 
        program_gabungan AS id_program_md, tr_spk.no_spk,so.id_sales_order,so.no_mesin,so.no_rangka,harga_on_road,
        CASE WHEN voucher_tambahan_1 IS NULL OR voucher_tambahan_1=0 THEN voucher_tambahan_2 ELSE voucher_tambahan_1 END as voucher,
        so.created_at,id_tipe_kendaraan,id_warna,jenis_beli,status_proposal,'gabungan' AS jenis_program,id_claim,tr_claim_dealer.alasan_reject,so.id_sales_order_int,LEFT(so.created_at,10) AS tgl_so,tr_spk.nama_konsumen,id_claim_int,tr_claim_dealer.status
        FROM tr_sales_order AS so
        JOIN tr_spk ON so.no_spk=tr_spk.no_spk
        JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=so.no_mesin
        JOIN tr_sales_program sp ON sp.id_program_md=tr_spk.program_gabungan
        LEFT JOIN tr_claim_dealer ON so.id_sales_order=tr_claim_dealer.id_sales_order AND tr_spk.program_gabungan=tr_claim_dealer.id_program_md
        $where_gabungan
    ) AS table_union $order $limit
    ");
  }

  function getClaimTimeAverage($id_dealer)
  {
    return $this->db->query("SELECT AVG(TIMESTAMPDIFF(DAY,tgl_ajukan_claim,LEFT(tgl_approve_reject_md,10))) avg_hari
    FROM tr_claim_dealer WHERE status_proposal='completed_by_md' AND id_dealer='$id_dealer'")->row()->avg_hari;
  }

  function getWaitingApprovalClaim($id_dealer)
  {
    return $this->db->query("SELECT count(id_sales_order) c
        FROM tr_claim_dealer cld
        JOIN tr_sales_program pr ON pr.id_program_md=cld.id_program_md
        WHERE cld.id_dealer=$id_dealer 
        AND DATE(NOW()) BETWEEN pr.periode_awal AND pr.periode_akhir 
        AND cld.status='ajukan'")->row()->c;
  }

  function getInprogressClaim($id_dealer)
  {
    return $this->db->query("SELECT SUM(IFNULL(spk.voucher_1,0)+IFNULL(spk.voucher_2,0)) tot
        FROM tr_claim_dealer cld
        JOIN tr_sales_program pr ON pr.id_program_md=cld.id_program_md
        JOIN tr_sales_order so ON so.id_sales_order=cld.id_sales_order
        JOIN tr_spk spk ON spk.no_spk=so.no_spk
        WHERE cld.id_dealer=$id_dealer 
        AND DATE(NOW()) BETWEEN pr.periode_awal AND pr.periode_akhir 
        AND cld.status='approved'")->row()->tot;
  }

  function getOnlyClaim($filter)
  {
    $where = "";
    if (isset($filter['status_proposal'])) {
      $where .= "AND cld.status_proposal='{$filter['status_proposal']}'";
    }
    if (isset($filter['status_in'])) {
      $where .= "AND cld.status IN({$filter['status_in']})";
    }

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

    if (isset($filter['periode'])) {
      // api_bm/Claim/list_approval
      // api_bm/Claim/index
      $where .= " AND (
        CASE 
          WHEN DATE(NOW()) > sp.tanggal_maks_bastk THEN
          CASE WHEN so.tgl_cetak_invoice BETWEEN DATE_FORMAT(NOW() ,'%Y-%m-01') AND DATE(NOW()) THEN 1 ELSE 0 END
          ELSE
          CASE WHEN so.tgl_cetak_invoice BETWEEN DATE_FORMAT(DATE(NOW() - INTERVAL 1 MONTH) ,'%Y-%m-01') AND sp.tanggal_maks_bastk THEN 1 ELSE 0 END
        END
      )=1
      ";
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = "ORDER BY {$filter['order']['field']} {$filter['order']['sort']}";
    }

    $select = "id_claim_int,spk.no_spk,LEFT(spk.tgl_spk,10) AS tanggal,sp.judul_kegiatan,(IFNULL(spk.voucher_1,0)+IFNULL(spk.voucher_2,0)) voucher,sp.periode_awal,sp.periode_akhir,spk.nama_konsumen,CONCAT(tk.tipe_ahm,'(',tk.id_tipe_kendaraan,' - ',spk.id_warna,')') tipe_motor,cld.status status_claim,cld.id_program_md";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(cld.id_claim_int) c";
      } elseif ($filter['select'] == 'sum') {
        $select = "SUM(IFNULL(spk.voucher_1,0)+IFNULL(spk.voucher_2,0)) jml";
      }
    }
    return $this->db->query("SELECT $select
      FROM tr_claim_dealer cld
      JOIN tr_sales_program sp ON sp.id_program_md=cld.id_program_md
      JOIN tr_sales_order so ON so.id_sales_order=cld.id_sales_order
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
      WHERE cld.id_dealer={$filter['id_dealer']} 
      $where
      $order
      $limit
    ");
  }
}
