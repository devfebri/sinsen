<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sm_master extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
  }

  function getProductCategory($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['offset'])) {
        $page = $filter['offset'];
        $page = $page < 0 ? 0 : $page;
        $length = $filter['length'];
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT id_kategori_int AS id, kategori name
    FROM ms_kategori ktg
    $where
    $limit
    ");
  }
  function getTipeKendaraan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_kategori_int'])) {
        if ($filter['id_kategori_int'] != '') {
          $where .= " AND ktg.id_kategori_int='{$filter['id_kategori_int']}'";
        }
      }
      if (isset($filter['id_tipe_kendaraan_int'])) {
        if ($filter['id_tipe_kendaraan_int'] != '') {
          $where .= " AND id_tipe_kendaraan_int='{$filter['id_tipe_kendaraan_int']}'";
        }
      }
      if (isset($filter['active'])) {
        if ($filter['active'] != '') {
          $where .= " AND tk.active='{$filter['active']}'";
        }
      }
      if (isset($filter['offset'])) {
        $page = $filter['offset'];
        $page = $page < 0 ? 0 : $page;
        $length = $filter['length'];
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT id_tipe_kendaraan_int AS id, id_tipe_kendaraan name, id_tipe_kendaraan
    FROM ms_tipe_kendaraan tk
    LEFT JOIN ms_kategori ktg ON ktg.id_kategori=tk.id_kategori
    $where
    $limit
    ");
  }
  function getJobType($filter = NULL)
  {
    $where = 'WHERE 1=1 and active = 1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['offset'])) {
        $page = $filter['offset'];
        $page = $page < 0 ? 0 : $page;
        $length = $filter['length'];
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT id_type_int AS id, id_type code,deskripsi name,color
    FROM ms_h2_jasa_type
    $where
    $limit
    ");
  }
  function getWarnaKendaraan($filter = NULL)
  {
    $where = 'WHERE active=1';
    if (isset($fitler['vehicle_color_id'])) {
      $where .= " AND id_warna_int='{$fitler['vehicle_color_id']}'";
    }
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['offset'])) {
        $page = $filter['offset'];
        $page = $page < 0 ? 0 : $page;
        $length = $filter['length'];
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    $select = "id_warna_int AS id, CONCAT(warna) name";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'all') {
        $select = "*";
      }
    }
    return $this->db->query("SELECT $select
    FROM ms_warna
    $where
    $limit
    ");
  }

  function getTypeJob()
  {
    $this->db->where('active = 1');
    return $this->db->get('ms_h2_jasa_type');
  }

  function  getActivityPromotion($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    // if ($filter != NULL) {
    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND act.id='{$filter['id']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND act.active='{$filter['active']}'";
      }
    }
    if (isset($filter['kode'])) {
      if ($filter['kode'] != '') {
        $where .= " AND act.kode='{$filter['kode']}'";
      }
    }
    if (isset($filter['name'])) {
      if ($filter['name'] != '') {
        $where .= " AND act.name='{$filter['name']}'";
      }
    }
    $order = "ORDER BY act.order ASC";
    if (isset($filter['order'])) {
      # code...
    }
    if (isset($filter['offset'])) {
      $page = $filter['offset'];
      $page = $page < 0 ? 0 : $page;
      $length = $filter['length'];
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    // }
    return $this->db->query("SELECT id,kode,name
    FROM dms_ms_activity_promotion act
    $where
    $order
    $limit
    ");
  }
  function getActivityCapacity($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    // if ($filter != NULL) {
    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND act.id='{$filter['id']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND act.active='{$filter['active']}'";
      }
    }
    if (isset($filter['kode'])) {
      if ($filter['kode'] != '') {
        $where .= " AND act.kode='{$filter['kode']}'";
      }
    }
    if (isset($filter['keterangan'])) {
      if ($filter['keterangan'] != '') {
        $where .= " AND act.keterangan='{$filter['keterangan']}'";
      }
    }
    $order = "ORDER BY act.order ASC";
    if (isset($filter['order'])) {
      # code...
    }
    if (isset($filter['offset'])) {
      $page = $filter['offset'];
      $page = $page < 0 ? 0 : $page;
      $length = $filter['length'];
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    // }
    return $this->db->query("SELECT id,kode,keterangan
    FROM dms_ms_activity_capacity act
    $where
    $order
    $limit
    ");
  }
  function getMekanikHadir($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        if ($filter['id_dealer'] != '') {
          $where .= " AND am.id_dealer='{$filter['id_dealer']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer'])) {
        if ($filter['id_karyawan_dealer'] != '') {
          $where .= " AND kry.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer_int'])) {
        if ($filter['id_karyawan_dealer_int'] != '') {
          $where .= " AND kry.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
        }
      }
      if (isset($filter['tanggal'])) {
        if ($filter['tanggal'] != '') {
          $where .= " AND am.tanggal='{$filter['tanggal']}'";
        }
      }
      if (isset($filter['ready_work'])) {
        $where .= " AND kry.id_karyawan_dealer NOT IN(SELECT id_karyawan_dealer FROM tr_h2_wo_dealer wo WHERE id_karyawan_dealer=kry.id_karyawan_dealer AND (wo.status='open' OR wo.status='pause') AND (SELECT COUNT(id) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo.id_work_order AND stats='end')=0)";
      }

      $order = "ORDER BY kry.nama_lengkap ASC";
      if (isset($filter['order'])) {
        # code...
      }
      $limit = '';
      if (isset($filter['offset'])) {
        $page = $filter['offset'];
        $page = $page < 0 ? 0 : $page;
        $length = $filter['length'];
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }

      $group = '';
      if (isset($filter['group_by_id_karyawan_dealer'])) {
        $group = "GROUP BY amd.id_karyawan_dealer";
      }
    }
    $select = "amd.id_karyawan_dealer,id_karyawan_dealer_int,nama_lengkap";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(amd.id_karyawan_dealer) AS count";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_h2_absen_mekanik_detail amd
    JOIN ms_karyawan_dealer kry ON kry.id_karyawan_dealer=amd.id_karyawan_dealer
    JOIN tr_h2_absen_mekanik am ON am.id_absen=amd.id_absen
    $where
    $group
    $order
    $limit
    ");
  }

  function getNearByDealer($filter = NULL)
  {
    $where = 'WHERE active=1 ';
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        $where .= " AND dt.id_dealer='{$filter['id_dealer']}'";
      }
    }
    $result =  $this->db->query("SELECT dlt.nama_dealer name, dlt.alamat address,dlt.no_telp phone 
      FROM ms_h3_dealer_terdekat dt 
      JOIN ms_dealer dlt ON dlt.id_dealer=dt.id_dealer_terdekat
      $where ORDER BY dlt.nama_dealer ASC");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Dealer ID');
      return $result->row();
    } else {
      return $result;
    }
  }

  function getParts($filter = NULL)
  {
    //WHERE
    $where = 'WHERE 1=1 ';
    if ($filter != NULL) {
      if (isset($filter['id_part'])) {
        if ($filter['id_part'] != '') {
          $where .= " AND prt.id_part='{$filter['id_part']}'";
        }
      }
      if (isset($filter['id_part_int'])) {
        if ($filter['id_part_int'] != '') {
          $where .= " AND prt.id_part_int='{$filter['id_part_int']}'";
        }
      }
      if (isset($filter['id_tipe_kendaraan_int'])) {
        if ($filter['id_tipe_kendaraan_int'] != 0) {
          $where .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
                          JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
                          JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
                          WHERE pvt.no_part=prt.id_part AND tk.id_tipe_kendaraan_int='{$filter['id_tipe_kendaraan_int']}'
                         )";
        }
      }
      if (isset($filter['id_kategori_int'])) {
        if ($filter['id_kategori_int'] != 0) {
          $where .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
                          JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
                          JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
                          JOIN ms_kategori ktg ON ktg.id_kategori=tk.id_kategori
                          WHERE pvt.no_part=prt.id_part AND ktg.id_kategori_int='{$filter['id_kategori_int']}'
                         )";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (prt.nama_part LIKE '%{$filter['search']}%'
                           OR prt.id_part LIKE '%{$filter['search']}%')";
        }
      }
    }

    // $tanggal = date("Y-m-d");
    // if($tanggal >='2023-08-06' && $tanggal <='2023-08-11'){      
    //   $where .= " and prt.kelompok_part !='FED OIL'";
    // }
   
    if($this->config->item('ahm_d_only')){
      $where .= " and prt.kelompok_part !='FED OIL'";
      // $this->db->where("prt.kelompok_part !='FED OIL'");
    }    

    // LIMIT
    $limit = "";
    if (isset($filter['offset'])) {
      $page = $filter['offset'];
      $page = $page < 0 ? 0 : $page;
      $length = $filter['length'];
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    //ORDER
    $order = "ORDER BY prt.nama_part ASC";
    if (isset($filter['sort'])) {
      if ($filter['sort'] != '') {
        $explode = explode(',', $filter['sort']);
        $order = "ORDER BY {$explode[0]} {$explode[1]}";
      }
    }
    $result =  $this->db->query("SELECT prt.id_part_int,prt.id_part,prt.nama_part,prt.harga_dealer_user,prt.kelompok_part 
      FROM ms_part prt
      $where $order $limit");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Part ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getPIT($filter = NULL)
  {
    $where = 'WHERE active=1 ';
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        $where .= " AND pit.id_dealer='{$filter['id_dealer']}'";
      }
      if (isset($filter['id_pit'])) {
        $where .= " AND pit.id_pit='{$filter['id_pit']}'";
      }
    }
    $result =  $this->db->query("SELECT * 
        FROM ms_h2_pit pit
        $where
        ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Dealer ID');
      return $result->row();
    } else {
      return $result;
    }
  }

  function cekStokPart($filter)
  {
    return $this->db->query("SELECT IFNULL(SUM(stock),0) AS stok
    FROM ms_h3_dealer_stock AS ds
    WHERE ds.id_dealer='{$filter['id_dealer']}' AND ds.id_part='{$filter['id_part']}'
    ")->row()->stok;
  }


  function cekSRBU($no_mesin)
  {
    $cek_srbu = $this->m_h2_master->cekSRBU($no_mesin);
    if ($cek_srbu > 0) {
      $cek = $this->db->query("SELECT COUNT(cus.no_mesin) c
      FROM tr_h2_sa_form sa 
      JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=sa.id_sa_form
      JOIN ms_customer_h23 cus ON cus.id_customer=sa.id_customer
      WHERE cus.no_mesin='$no_mesin' AND sa.srbu=1 AND (wo.status='closed' OR wo.status='close')
      ")->row()->c;
      if ($cek > 0) {
        return 'Sudah Dikerjakan';
      } else {
        return 'Belum Dikerjakan';
      }
    } else {
      return 'Tidak Termasuk';
    }
  }

  public function promo_part_query($id_part, $kelompok_part)
  {
    $now = date('Y-m-d', time());

    $this->db
      ->select('prm.*')
      ->select('date_format(prm.start_date, "%d/%m/%Y") as start_date')
      ->select('date_format(prm.end_date, "%d/%m/%Y") as end_date')
      ->select('prmi.tipe_disc')
      ->select('prmi.disc_value')
      ->from('ms_h3_promo_dealer as prm')
      ->join('ms_h3_promo_dealer_items as prmi', 'prm.id_promo = prmi.id_promo')
      ->group_start()
      ->where('prmi.id_part', $id_part)
      ->or_where('prmi.kelompok_part', $kelompok_part)
      ->group_end()
      ->where("'{$now}' between prm.start_date and prm.end_date", null, false)
      ->group_by('prm.id_promo')
      ->order_by('prm.created_at', 'desc');

    $data = [];
    $index = 0;
    foreach ($this->db->get()->result_array() as $each) {
      $sub_array = $each;

      $hadiah_master = $this->db
        ->from('ms_h3_promo_dealer_hadiah as h')
        ->where('h.id_promo', $sub_array['id_promo'])
        ->where('h.id_items', null)
        ->get()->result_array();

      $sub_array['gifts'] = count($hadiah_master) > 0 ? $hadiah_master : [];

      $this->db
        ->from('ms_h3_promo_dealer_items as prmi')
        ->where('prmi.id_promo', $each['id_promo'])
        ->order_by('prmi.qty', 'desc');

      $promo_items = [];
      foreach ($this->db->get()->result_array() as $promo_item) {
        $sub_array_item = $promo_item;
        $hadiah_item  = $hadiah_master = $this->db
          ->from('ms_h3_promo_dealer_hadiah as h')
          ->where('h.id_promo', $sub_array_item['id_promo'])
          ->where('h.id_items', $sub_array_item['id'])
          ->get()->result_array();

        $sub_array_item['gifts'] = count($hadiah_item) > 0 ? $hadiah_item : [];
        $promo_items[] = $sub_array_item;
      }

      $sub_array['promo_items'] = $promo_items;

      $data[$index] = $sub_array;
      $index++;
    }

    return $data;
  }

  function getPilihanKesediaanCustomer()
  {
    $data = $this->db->query("SELECT id,deskripsi FROM ms_h2_kesediaan_customer_lcr WHERE active=1");
    return $data;
  }

  function getAlasanTidakBersedia()
  {
    $data = $this->db->query("SELECT id,deskripsi FROM ms_h2_record_reason_lcr WHERE active=1");
    return $data;
  }

  function getHasilPengecekanLCR()
  {
    $data = $this->db->query("SELECT id,deskripsi FROM ms_h2_hasil_pengecekan_lcr WHERE active=1");
    return $data;
  }
}
