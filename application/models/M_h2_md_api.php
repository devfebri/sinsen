<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_md_api extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function fetch_getAllParts($filter)
  {

    $order_column = array('id_part ', 'nama_part', 'kelompok_vendor', 'harga_dealer_user', null);
    $set_filter   = "WHERE 1=1 ";

    if (isset($filter['part_oli'])) {
      if ($filter['part_oli'] != '') {
        $set_filter .= "AND kelompok_part='OIL'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $set_filter .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
                        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
                        JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
                        WHERE pvt.no_part=ms_part.id_part AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'
                       )";
      }
    }
    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_part LIKE '%$search%'
                            OR nama_part LIKE '%$search%'
                            OR kelompok_vendor LIKE '%$search%'
                            OR harga_dealer_user LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY nama_part ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_part,nama_part,kelompok_vendor,harga_dealer_user
    FROM ms_part
    $set_filter
    ");
  }

  function fetch_getJasa($filter)
  {

    $order_column = array('id_jasa ', 'js.deskripsi', 'tp.deskripsi', 'kategori', 'tipe_motor', 'js.harga', null);
    $set_filter   = "WHERE 1=1 ";
    $harga = 'harga';
    if (isset($filter['id_dealer'])) {
      if ((string)$filter['id_dealer'] != '') {
        $harga = "SELECT harga_dealer FROM ms_h2_jasa_dealer jsd WHERE jsd.id_jasa=js.id_jasa AND id_dealer='{$filter['id_dealer']}'";
      }
    }

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

    return $this->db->query("SELECT id_jasa,js.deskripsi,tp.deskripsi AS type,kategori,tipe_motor,
    CASE WHEN ($harga) IS NULL THEN harga ELSE ($harga) END  harga
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
  function fetch_getSymptom($filter)
  {

    $order_column = array('id_symptom ', 'symptom_id', 'symptom_en', 'id_kelompok_symptom', 'deskripsi_id', 'deskripsi_en', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (id_symptom LIKE '%$search%'
                            OR symptom_en LIKE '%$search%'
                            OR symptom_id LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY symptom_id ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_symptom AS symptom_code,id_symptom,symptom_en,symptom_id,sy.id_kelompok_symptom,deskripsi_en,deskripsi_id
    FROM ms_symptom sy
    JOIN ms_kelompok_symptom ksy ON ksy.id_kelompok_symptom=sy.id_kelompok_symptom
    $set_filter
    ");
  }

  function getDealer($filter = null)
  {
    $where = "WHERE 1=1 ";

    if ($filter != null) {
      if (isset($filter['kode_dealer_md'])) {
        $where .= " AND dl.kode_dealer_md='{$filter['kode_dealer_md']}' ";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dl.kode_dealer_md LIKE '%$search%'
              OR dl.nama_dealer LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalDealer') {
          $order_column = ['kode_dealer_md', 'nama_dealer', '', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY dl.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    return $this->db->query("SELECT kode_dealer_md,nama_dealer,id_dealer,alamat
    FROM ms_dealer dl
    $where $order $limit");
  }
}
