<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_konfirmasi_pu extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function get($filter=NULL)
  {

    $where = 'WHERE 1=1';
    $where .= " AND tr_surat_jalan.status = 'close'";

    $select = '';

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND tr_surat_jalan.id_dealer = ({$filter['id_dealer']})";
      }
    }


    if ($filter != null) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $filter['search'] = $this->db->escape_str($filter['search']);
          $where .= " AND ( tr_penerimaan_unit_dealer.id_goods_receipt LIKE'%{$filter['search']}%'
                            OR tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer LIKE'%{$filter['search']}%'
                            OR tr_penerimaan_unit_dealer.no_surat_jalan LIKE'%{$filter['search']}%'
                            OR tr_surat_jalan.no_surat_jalan LIKE'%{$filter['search']}%'
                            OR tr_penerimaan_unit_dealer.tgl_penerimaan LIKE'%{$filter['search']}%'
          )";
        }
      }

      if (isset($filter['select'])) {
        if ($filter['select'] == 'dropdown') {
          $select = "leads_id id, leads_id text";
        } elseif ($filter['select'] == 'count') {
          $select = "COUNT(tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer) count";
        } else {
          $select = $filter['select'];
        }
      }

    }

    $order_data = '';
    if (isset($filter['order'])) {
      $order_column = [null, null];
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order_data = " ORDER BY $order_clm $order_by ";
      } else {
        $order_data = "ORDER BY tr_penerimaan_unit_dealer.created_at DESC";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $group_by = '';
    if (isset($filter['group_by'])) {
      $group_by = "GROUP BY " . $filter['group_by'];
    }

   return $this->db->query("SELECT $select
                 case when tr_penerimaan_unit_dealer.no_surat_jalan is null then 1 else 0 end as button,
                 tr_penerimaan_unit_dealer.*,tr_surat_jalan.id_surat_jalan,tr_sppm.no_do,tr_surat_jalan.tgl_surat,tr_surat_jalan.no_surat_jalan,tr_sppm.tgl_do
							  FROM tr_penerimaan_unit_dealer LEFT JOIN tr_surat_jalan ON tr_penerimaan_unit_dealer.no_surat_jalan=tr_surat_jalan.no_surat_jalan								
							  LEFT JOIN tr_sppm ON tr_sppm.no_surat_sppm = tr_surat_jalan.no_surat_sppm
    $where
    $group_by
    $order_data
    $limit
    ");
   
  }






}
