<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_md_ahass_network extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getDataClaimKPBMD($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND clm.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['no_mesin'])) {
      if ($filter['no_mesin'] != '') {
        $where .= " AND clm.no_mesin='{$filter['no_mesin']}'";
      }
    }
    if (isset($filter['kpb_ke'])) {
      if ($filter['kpb_ke'] != '') {
        $where .= " AND clm.kpb_ke='{$filter['kpb_ke']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (clm.id_dealer LIKE '%$search%'
                            OR clm.no_mesin LIKE '%$search%'
                            OR clm.no_rangka LIKE '%$search%'
                            OR clm.kpb_ke LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view_claim_kpb') {
          $order_column = ['kode_dealer_md', 'nama_dealer', 'clm.no_mesin', 'clm.no_rangka', 'clm.no_kpb', 'tk.tipe_ahm', 'clm.kpb_ke', 'clm.tgl_beli_smh', 'clm.created_at', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY clm.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_claim_kpb,clm.no_mesin,clm.no_rangka,clm.id_tipe_kendaraan,no_kpb,kpb_ke,id_part,harga_jasa,harga_material,diskon_material,tgl_beli_smh,km_service,tgl_service,clm.id_dealer,clm.id_periode,clm.status,dl.kode_dealer_md,dl.nama_dealer,clm.created_at,tk.tipe_ahm 
    FROM tr_claim_kpb clm
    JOIN ms_dealer dl ON dl.id_dealer=clm.id_dealer
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=clm.id_tipe_kendaraan
    $where $order $limit
    ");
  }
}
