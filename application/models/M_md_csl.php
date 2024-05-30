<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_md_csl extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
    $this->load->model('m_md_csl_master', 'm_csl_master');
  }

  function getAtributCSL($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['kategori'])) {
      if ($filter['kategori'] != '') {
        $where .= " AND attr.kategori='{$filter['kategori']}'";
      }
    }
    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND attr.id='{$filter['id']}'";
      }
    }
    if (isset($filter['code'])) {
      if ($filter['code'] != '') {
        $where .= " AND attr.code='{$filter['code']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND attr.active='{$filter['active']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (attr.kategori LIKE '%$search%'
              OR attr.code LIKE '%$search%'
              OR attr.nama_atribut LIKE '%$search%'
              OR attr.id LIKE '%$search%'
              OR attr.active LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id', 'kategori', 'code', 'nama_atribut', 'active', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY attr.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id,kategori,code,nama_atribut,created_at,created_by,active
    FROM ms_csl_atribut attr
    $where $order $limit
    ");
  }

  function get_id_upload()
  {
    $ym       = date('Y-m');
    $get_data  = $this->db->query("SELECT id_upload FROM tr_csl_upload upl
			WHERE LEFT(created_at,7)='$ym'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->id_upload, -4);
      $new_kode   = 'UPL-CSL/' . $ym . '/' . sprintf("%'.04d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_csl_upload', ['id_upload' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = 'UPL-CSL/' . $ym . '/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'UPL-CSL/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getUploadCSL($filter)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['id_upload'])) {
      if ($filter['id_upload'] != '') {
        $where .= " AND upl.id_upload='{$filter['id_upload']}'";
      }
    }
    if (isset($filter['tahun'])) {
      if ($filter['tahun'] != '') {
        $where .= " AND upl.tahun='{$filter['tahun']}'";
      }
    }
    if (isset($filter['bulan'])) {
      if ($filter['bulan'] != '') {
        $where .= " AND upl.bulan='{$filter['bulan']}'";
      }
    }
    if (isset($filter['kategori'])) {
      if ($filter['kategori'] != '') {
        $where .= " AND upl.kategori='{$filter['kategori']}'";
      }
    }
    if (isset($filter['tipe'])) {
      if ($filter['tipe'] != '') {
        $where .= " AND upl.tipe='{$filter['tipe']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (upl.kategori LIKE '%$search%'
              OR upl.id_upload LIKE '%$search%'
              OR upl.tahun LIKE '%$search%'
              OR upl.bulan LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_upload', 'upl.created_at', 'tahun', 'bulan', 'tipe', 'kategori', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY upl.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_upload,LEFT(created_at,10) tgl_upload,tahun,bulan,tipe,kategori,status
    FROM tr_csl_upload upl
    $where $order $limit
    ");
  }
  function getDetailTargetListUpladCSL($filter)
  {
    //WHERE
    $where = 'WHERE 1=1 ';
    if (isset($filter['id_upload'])) {
      if ($filter['id_upload'] != '') {
        $where .= " AND upl.id_upload='{$filter['id_upload']}'";
      }
    }
    if (isset($filter['tahun'])) {
      if ($filter['tahun'] != '') {
        $where .= " AND upl.tahun='{$filter['tahun']}'";
      }
    }
    if (isset($filter['bulan'])) {
      if ($filter['bulan'] != '') {
        $where .= " AND upl.bulan='{$filter['bulan']}'";
      }
    }
    if (isset($filter['kategori'])) {
      if ($filter['kategori'] != '') {
        $where .= " AND upl.kategori='{$filter['kategori']}'";
      }
    }
    if (isset($filter['tipe'])) {
      if ($filter['tipe'] != '') {
        $where .= " AND upl.tipe='{$filter['tipe']}'";
      }
    }
    if (isset($filter['id_atribut'])) {
      if ($filter['id_atribut'] != '') {
        $where .= " AND upl_tg.id_atribut='{$filter['id_atribut']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (upl.kategori LIKE '%$search%'
              OR upl.id_upload LIKE '%$search%'
              OR upl.tahun LIKE '%$search%'
              OR upl.bulan LIKE '%$search%'
              ) 
        ";
      }
    }

    //ORDER
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order == 'atribut_code_asc') {
        $order = "ORDER BY atr.code ASC";
      } else {
        if ($order != '') {
          if ($filter['order_column'] == 'view') {
            $order_column = ['id_upload', 'upl.created_at', 'tahun', 'bulan', 'tipe', 'kategori', NULL];
          }
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY upl.created_at DESC ";
        }
      }
    } else {
      $order = '';
    }


    // LIMIT
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $select = "upl_tg.*";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'target_atribut') {
        $select = "upl_tg.id_atribut,atr.code,atr.nama_atribut,target";
      } elseif ($filter['select'] == 'average') {
        $select = "IFNULL(AVG(target),0) as average";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_csl_upload_target upl_tg
    JOIN ms_csl_atribut atr ON atr.id=upl_tg.id_atribut
    JOIN tr_csl_upload upl ON upl.id_upload=upl_tg.id_upload
    $where $order $limit
    ");
  }

  function getDetailActualUpladCSL($filter)
  {
    //WHERE
    $where = 'WHERE 1=1 ';
    if (isset($filter['id_upload'])) {
      if ($filter['id_upload'] != '') {
        $where .= " AND upl.id_upload='{$filter['id_upload']}'";
      }
    }
    if (isset($filter['tahun'])) {
      if ($filter['tahun'] != '') {
        $where .= " AND upl.tahun='{$filter['tahun']}'";
      }
    }
    if (isset($filter['bulan'])) {
      if ($filter['bulan'] != '') {
        $where .= " AND upl.bulan='{$filter['bulan']}'";
      }
    }
    if (isset($filter['kategori'])) {
      if ($filter['kategori'] != '') {
        $where .= " AND upl.kategori='{$filter['kategori']}'";
      }
    }
    if (isset($filter['tipe'])) {
      if ($filter['tipe'] != '') {
        $where .= " AND upl.tipe='{$filter['tipe']}'";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND upl_ad.id_dealer='{$filter['id_dealer']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (upl.kategori LIKE '%$search%'
              OR upl.id_upload LIKE '%$search%'
              OR upl.tahun LIKE '%$search%'
              OR upl.bulan LIKE '%$search%'
              ) 
        ";
      }
    }

    //GROUP
    $group = '';
    if (isset($filter['group_by_dealer'])) {
      $group = " GROUP BY upl_ad.id_dealer ";
    }

    //ORDER
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order == 'atribut_code_asc') {
        $order = "ORDER BY atr.code ASC";
      } else {
        if ($order != '') {
          if ($filter['order_column'] == 'view') {
            $order_column = ['id_upload', 'upl.created_at', 'tahun', 'bulan', 'tipe', 'kategori', NULL];
          }
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY upl.created_at DESC ";
        }
      }
    } else {
      $order = '';
    }


    // LIMIT
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $select = "upl_ad.*, atr.nama_atribut,dl.kode_dealer_md, dl.nama_dealer,upl.kategori,upl.tipe";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'actual_atribut') {
        $select = "upl_ad.id_atribut,atr.code,atr.nama_atribut,actual";
      } elseif ($filter['select'] == 'average') {
        $select = "IFNULL(AVG(actual),0) AS average";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_csl_upload_actual_dealer upl_ad
    JOIN ms_csl_atribut atr ON atr.id=upl_ad.id_atribut
    JOIN tr_csl_upload upl ON upl.id_upload=upl_ad.id_upload
    JOIN ms_dealer dl ON dl.id_dealer=upl_ad.id_dealer
    $where $group $order $limit
    ");
  }

  function getDetailActualListPerDealerUpladCSL($filter)
  {
    $filter['group_by_dealer'] = true;
    $dealer = $this->getDetailActualUpladCSL($filter)->result();
    foreach ($dealer as $dl) {
      $f_d = [
        'id_dealer' => $dl->id_dealer,
        'id_upload' => $dl->id_upload,
        'order' => 'atribut_code_asc',
        'select' => 'actual_atribut'
      ];
      $actual = $this->getDetailActualUpladCSL($f_d)->result();
      $result[] = [
        'id_dealer' => $dl->id_dealer,
        'kode_dealer_md' => $dl->kode_dealer_md,
        'nama_dealer' => $dl->nama_dealer,
        'actual' => $actual,
      ];
    }
    if (isset($result)) {
      return $result;
    }
  }
}
