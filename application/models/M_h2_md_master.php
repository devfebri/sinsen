<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_md_master extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function geMasterKPB($filter)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_tipe_kendaraan'])) {
        $where = "AND id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
      if (isset($filter['kpb_ke'])) {
        $where = "AND kpb_ke='{$filter['kpb_ke']}'";
      }
      if (isset($filter['id_work_order'])) {
        $where = "AND id_work_order='{$filter['id_work_order']}'";
      }
    }
    $harga_material  ="SELECT harga_md_dealer FROM ms_kpb_oli WHERE "
    return $this->db->query("SELECT id_tipe_kendaraan,IFNULL((SELECT harga_md_dealer FROM ms_part WHERE id_part=oli),0) AS harga_material FROM ms_kpb_detail 
    $where
    ORDER BY id_detail DESC LIMIT 1
    ");
  }

  function fetch_getJasa($filter)
  {

    $order_column = array('id_jasa ', 'js.deskripsi', 'tp.deskripsi', 'kategori', 'tipe_motor', 'js.harga', null);
    $set_filter   = "WHERE 1=1 ";

    // if (isset($filter['part_oli'])) {
    //   if ($filter['part_oli'] != '') {
    //     $set_filter .= "AND part_oli IS NOT NULL ";
    //   }
    // }
    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_jasa LIKE '%$search%'
                            OR js.deskripsi LIKE '%$search%'
                            OR tp.deskripsi LIKE '%$search%'
                            OR kategori LIKE '%$search%'
                            OR tipe_motor LIKE '%$search%'
                            OR harga LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY js.deskripsi ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_jasa,js.deskripsi,tp.deskripsi AS type,kategori,tipe_motor,harga
    FROM ms_h2_jasa js
    JOIN ms_h2_jasa_type tp ON tp.id_type=js.id_type
    $set_filter
    ");
  }

  function fetch_getAHASS($filter)
  {

    $order_column = array('kode_dealer_md ', 'nama_dealer', null);
    $set_filter   = "WHERE 1=1 ";

    // if (isset($filter['part_oli'])) {
    //   if ($filter['part_oli'] != '') {
    //     $set_filter .= "AND part_oli IS NOT NULL ";
    //   }
    // }
    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (kode_dealer_md LIKE '%$search%'
                            OR nama_dealer LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY nama_dealer ASC ";
    }

    $set_filter .= $filter['limit'];
    $id_pic_dealer = "(SELECT id_karyawan_dealer FROM ms_karyawan_dealer WHERE id_dealer=dl.id_dealer AND id_jabatan='JBT-053' LIMIT 1)";
    $nama_pic_dealer = "(SELECT nama_lengkap FROM ms_karyawan_dealer WHERE id_dealer=dl.id_dealer AND id_jabatan='JBT-053' LIMIT 1)";

    return $this->db->query("SELECT kode_dealer_md,nama_dealer,id_dealer,$id_pic_dealer AS id_pic_dealer,$nama_pic_dealer AS nama_pic_dealer
    FROM ms_dealer dl
    $set_filter
    ");
  }
  function fetch_getKaryawanMD($filter)
  {

    $order_column = array('id_karyawan ', 'id_flp_md', 'nama_lengkap', 'jabatan', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_karyawan LIKE '%$search%'
                            OR id_flp_md LIKE '%$search%'
                            OR nama_lengkap LIKE '%$search%'
                            OR jabatan LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY nama_lengkap ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_karyawan,nama_lengkap,jabatan
    FROM ms_karyawan kry
    LEFT JOIN ms_jabatan jb ON jb.id_jabatan=kry.id_jabatan
    $set_filter
    ");
  }
  function fetch_getKabupaten($filter)
  {

    $order_column = array('id_kabupaten ', 'kabupaten', 'provinsi', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_kabupaten LIKE '%$search%'
                            OR kabupaten LIKE '%$search%'
                            OR provinsi LIKE '%$search%'
                            ) 
            ";
    }
    if (isset($filter['id_provinsi'])) {
      $set_filter .= " AND kab.id_provinsi='{$filter['id_provinsi']}' AND kab.id_kabupaten NOT IN(1501,1572)";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY kabupaten ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_kabupaten,kabupaten,provinsi
    FROM ms_kabupaten kab
    LEFT JOIN ms_provinsi prv ON prv.id_provinsi=kab.id_provinsi
    $set_filter
    ");
  }
}
