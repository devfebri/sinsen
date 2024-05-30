<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_apk_pkb extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function query_pkb_header($filter=[])
  {
    $where = "WHERE 1=1 ";
    $limit = "";
    if (count($filter)>0) {
      if (isset($filter['id_dealer'])) {
        $where .=" AND sa.id_dealer={$filter['id_dealer']} ";
      }
      if (isset($filter['tgl_servis'])) {
        $where .=" AND sa.tgl_servis='{$filter['tgl_servis']}' ";
        if ($filter['tgl_servis']=='2021-11-15') {
          // $where.="-";
        }
      }
      if (isset($filter['status_wo_in'])) {
        $where .=" AND wo.status IN({$filter['status_wo_in']}) ";
      }
      if (isset($filter['offset'])) {
        $page = $filter['offset'];
        $page = $page < 0 ? 0 : $page;
        $length = $filter['length'];
        $start = $length * $page;
        $start = $page;
        $limit = " LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT id_work_order_int, id_work_order, LEFT(wo.created_at,10) tgl_wo,
      REPLACE(cus.nama_customer,'\'','`') AS nama_customer,cus.no_hp,
      REPLACE(cus.alamat,'\'','`') AS alamat,
      REPLACE(kec.kecamatan,'\'','`') AS kecamatan,
      (SELECT ROUND(IFNULL(SUM(detik),0)/60) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo.id_work_order) estimasi_waktu_kerja, jenis_pit, document,wo.status status_wo,
      (SELECT stats FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo.id_work_order ORDER BY set_at DESC LIMIT 1) AS last_stats,pit_sa.id_pit,wo.created_by,wo.id_sa_form,wo.id_dealer
      FROM tr_h2_sa_form sa
      JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=sa.id_sa_form
      LEFT JOIN ms_customer_h23 AS cus ON cus.id_customer=sa.id_customer
      LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=cus.id_kelurahan
      LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_h2_pit AS pit_sa ON pit_sa.id_pit=sa.id_pit AND pit_sa.id_dealer=sa.id_dealer
      $where
      ORDER BY wo.id_work_order_int DESC
      $limit
      ");
  }

  function get_pkb_list($id_dealer, $tgl_servis, $offset, $limit)
  {
    $filter =[
      'id_dealer'       => $id_dealer,
      'tgl_servis'      => $tgl_servis,
      'offset'          => $offset,
      'length'           => $limit,
      'status_wo_in'    => "'open','closed','pause','pending'"
    ];
    return $this->query_pkb_header($filter)->result();
  }

}
