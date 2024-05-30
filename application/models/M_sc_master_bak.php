<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_master extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
    $this->load->model('m_h2_master');
  }

  function getAgama($filter = NULL)
  {
    $where = 'WHERE active=1 ';
    if ($filter != NULL) {
      if (isset($filter['id_agama'])) {
        $where .= " AND id_agama='{$filter['id_agama']}'";
      }
      if (isset($filter['agama'])) {
        $where .= " AND agama='{$filter['agama']}'";
      }
    }
    $result =  $this->db->query("SELECT id_agama id, agama name FROM ms_agama $where ORDER BY id_agama ASC");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Agama ID');
      return $result->row();
    } else {
      return $result;
    }
  }

  function getBulan()
  {
    $res_ = [];
    for ($i = 1; $i <= 12; $i++) {
      $res_[] = [
        'id' => $i,
        'name' => bulan_pjg($i) . ' ' . date('Y')
      ];
    }
    return $res_;
  }
  function getCalendar($filter = NULL)
  {
    $year = $filter['year'] == '' ? year() : $filter['year'];
    if ($filter['month'] == '') {
      $start = 1;
      $end   = 12;
    } else {
      $start = $filter['month']; //sprintf("%02d", );
      $end = $filter['month']; //sprintf("%02d", );
    }
    for ($i = $start; $i <= $end; $i++) {
      $hari = [];
      $digit_bulan   = sprintf("%02d", $i);
      $start_tanggal = $year . '-' . $digit_bulan . '-1';
      $end_tanggal   = $year . '-' . $digit_bulan . '-' . date('t', strtotime($start_tanggal));
      $hari = [];
      while (strtotime($start_tanggal) <= strtotime($end_tanggal)) {
        $id  = date('z', strtotime($start_tanggal));
        $tanggal  = date('d', strtotime($start_tanggal));
        $ymd  = date('Y-m-d', strtotime($start_tanggal));
        $hari[] =
          [
            'id' => (int)$id,
            'tgl' => $tanggal,
            'name' => nama_hari($ymd),
            'full_name' => nama_hari($ymd) . ', ' . tgl_indo($ymd),
            'date' => $ymd
          ];

        $start_tanggal = date("Y-m-d", strtotime("+1 day", strtotime($start_tanggal)));
      }
      $bulan[] = [
        'id' => $i,
        'name' => bulan_pjg($i),
        'full_name' => bulan_pjg($i) . ' ' . $year,
        'hari' => $hari
      ];
    }
    $res_ = [
      'default_year' => $filter['year'],
      'default_month' => sprintf("%02d", $filter['month']),
      'bulan' => $bulan,
    ];
    return $res_;
  }

  function getCustomer($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['page'])) {
        $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
        $length = 10;
        // $start = $page == 1 ? 0 : $length * ($page - 1);
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT 
      id_prospek_int id,
      nama_konsumen name,
      customer_image,
      no_ktp ktp,
      no_hp phone,
      alamat address,
      CASE WHEN latitude='' OR latitude IS NULL OR latitude='-' THEN '0.0' else latitude END latitude,
      CASE WHEN longitude='' OR longitude IS NULL OR longitude='-' THEN '0.0' else longitude END longitude,
      prp.pekerjaan pekerjaan_id,
      pkj.pekerjaan pekerjaan_name,
      alamat_kantor office_address,
      no_telp_kantor office_phone
    FROM tr_prospek prp
    LEFT JOIN ms_pekerjaan pkj ON pkj.id_pekerjaan=prp.pekerjaan
    $where 
    ORDER BY id_customer ASC
    $limit
    ");
  }
  function getProvinsi($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    return $this->db->query("SELECT 
      id_provinsi id,
      provinsi name
    FROM ms_provinsi prv
    $where 
    ORDER BY id_provinsi ASC
    ");
  }

  function getKabupatenKota($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_kabupaten LIKE '%{$filter['search']}%'
                        OR kabupaten LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['provinsi_id'])) {
        if ($filter['provinsi_id'] != '') {
          $where .= " AND kbp.id_provinsi='{$filter['provinsi_id']}'";
        } else {
          $where .= " AND 1=0";
        }
      }
    }
    return $this->db->query("SELECT 
      id_kabupaten id,
      kabupaten name
    FROM ms_kabupaten kbp
    $where 
    ORDER BY id_kabupaten ASC
    $limit
    ");
  }
  function getKecamatan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['kabupaten_kota_id'])) {
        if ($filter['kabupaten_kota_id'] != '') {
          $where .= " AND kec.id_kabupaten='{$filter['kabupaten_kota_id']}'";
        } else {
          $where .= " AND 1=0";
        }
      }
    }
    return $this->db->query("SELECT 
      id_kecamatan id,
      kecamatan name
    FROM ms_kecamatan kec
    $where 
    ORDER BY id_kecamatan ASC
    $limit
    ");
  }
  function getKelurahan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (kecamatan LIKE '%{$filter['search']}%'
                        OR id_kelurahan LIKE '%{$filter['search']}%'
                        OR kelurahan LIKE '%{$filter['search']}%'
                        OR kecamatan LIKE '%{$filter['search']}%'
                        OR CONCAT(kelurahan,',',kecamatan) LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['id_kelurahan'])) {
        if ($filter['id_kelurahan'] != '') {
          $where .= " AND kel.id_kelurahan='{$filter['id_kelurahan']}'";
        } else {
          $where .= " AND 1=0";
        }
      }
      if (isset($filter['kecamatan_id'])) {
        if ($filter['kecamatan_id'] != '') {
          $where .= " AND kel.id_kecamatan='{$filter['kecamatan_id']}'";
        } else {
          $where .= " AND 1=0";
        }
      }
    }
    $select = "id_kelurahan id,kelurahan name,kel.id_kecamatan, kec.id_kabupaten,kab.id_provinsi";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'concat') {
        $select = "id_kelurahan id,CONCAT(kelurahan,', ',kecamatan,'-',id_kelurahan) name";
      }
    }
    return $this->db->query("SELECT $select
    FROM ms_kelurahan kel
    JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
    $where 
    ORDER BY id_kelurahan ASC
    $limit
    ");
  }
  function getLeasing($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_finance_company_int'])) {
        if ($filter['id_finance_company_int'] != '') {
          $where .= " AND id_finance_company_int='{$filter['id_finance_company_int']}'";
        }
      }
      if (isset($filter['active'])) {
        if ($filter['active'] != '') {
          $where .= " AND active='{$filter['active']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    return $this->db->query("SELECT 
      id_finance_company_int id,
      id_finance_company,
      finance_company name
    FROM ms_finance_company fc
    $where 
    ORDER BY id_finance_company ASC
    $limit
    ");
  }
  function getMotorCycleBrand($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND id_merk_sebelumnya='{$filter['id_merk_sebelumnya']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result =  $this->db->query("SELECT 
      id_merk_sebelumnya id,
      merk_sebelumnya name
    FROM ms_merk_sebelumnya js
    $where 
    ORDER BY id_merk_sebelumnya ASC
    $limit
    ");
    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Motorcycle Brand ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getMotorType($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_kategori_int'])) {
        if ($filter['id_kategori_int'] != '') {
          $where .= " AND id_kategori_int='{$filter['id_kategori_int']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (kategori LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_kategori_int id,
      kategori name
    FROM ms_kategori 
    $where 
    ORDER BY id_kategori_int ASC
    $limit
    ");
    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Motorcycle Type ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getJenisSebelumnya($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_jenis_sebelumnya'])) {
        if ($filter['id_jenis_sebelumnya'] != '') {
          $where .= " AND id_jenis_sebelumnya='{$filter['id_jenis_sebelumnya']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_jenis_sebelumnya id,
      jenis_sebelumnya name
    FROM ms_jenis_sebelumnya 
    $where 
    ORDER BY id_jenis_sebelumnya ASC
    $limit
    ");
    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Motorcycle Type ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getPekerjaan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_pekerjaan'])) {
        if ($filter['id_pekerjaan'] != '') {
          $where .= " AND id_pekerjaan='{$filter['id_pekerjaan']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_pekerjaan id,
      pekerjaan name,
      is_address_instansi 
    FROM ms_pekerjaan pkj
    $where 
    ORDER BY id_pekerjaan ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Pekerjaan ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getPendidikan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_pendidikan'])) {
        if ($filter['id_pendidikan'] != '') {
          $where .= " AND id_pendidikan='{$filter['id_pendidikan']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_pendidikan id,
      pendidikan name
    FROM ms_pendidikan pkj
    $where 
    ORDER BY id_pendidikan ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Pendidikan ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getPengeluaran($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_pengeluaran_bulan'])) {
        if ($filter['id_pengeluaran_bulan'] != '') {
          $where .= " AND id_pengeluaran_bulan='{$filter['id_pengeluaran_bulan']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_pengeluaran_bulan id,
      pengeluaran name
    FROM ms_pengeluaran_bulan pkj
    $where 
    ORDER BY id_pengeluaran_bulan ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Pendidikan ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getPenggunaKendaraan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id='{$filter['id']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result =  $this->db->query("SELECT id,name
    FROM sc_ms_pengguna_kendaraan pkj
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Pengguna Kendaraan ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getTenor($filter = null)
  {
    $res = [];
    for ($i = 1; $i <= 50; $i++) {
      $res[] = ['id' => $i, 'value' => $i];
    }
    return $res;
  }
  function getPaymentMethod($filter = null)
  {
    $res[] = ['id' => 1, 'name' => 'Cash', 'is_expand' => false];
    $res[] = ['id' => 2, 'name' => 'Kredit', 'is_expand' => true];
    return $res;
  }
  // function getDocumentProspek($filter = null)
  // {
  //   $res[] = ['id' => 1, 'key' => 'ktp', 'name' => 'Fotocopy KTP', 'is_required' => true];
  //   $res[] = ['id' => 2, 'key' => 'kk', 'name' => 'Kartu Keluarga', 'is_required' => true];
  //   return $res;
  // }
  function getDocumentProspek($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id='{$filter['id']}'";
        }
      }
      if (isset($filter['key'])) {
        if ($filter['key'] != '') {
          $where .= " AND sc_ms_document_prospek.key='{$filter['key']}'";
        }
      }
    }
    $result = $this->db->query("SELECT *
    FROM sc_ms_document_prospek
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Key ID');
      return $result->row();
    } else {
      foreach ($result->result() as $rs) {
        $new_res[] = [
          'id' => $rs->id,
          'key' => $rs->key,
          'name' => $rs->name,
          'is_required' => $rs->is_required == 1 ? true : false,
        ];
      }
      return $new_res;
    }
  }
  function getDocumentSPK($filter = null)
  {
    $res[] = ['id' => 1, 'key' => 'ktp', 'name' => 'Fotocopy KTP'];
    $res[] = ['id' => 2, 'key' => 'kk', 'name' => 'Kartu Keluarga'];
    return $res;
  }
  function getDocumentSales($filter = null)
  {
    $res[] = ['id' => 1, 'key' => 'ktp', 'name' => 'Fotocopy KTP'];
    $res[] = ['id' => 2, 'key' => 'kk', 'name' => 'Kartu Keluarga'];
    return $res;
  }
  function getHobi($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_hobi'])) {
        if ($filter['id_hobi'] != '') {
          $where .= " AND id_hobi='{$filter['id_hobi']}'";
        }
      }
      if (isset($filter['hobi'])) {
        if ($filter['hobi'] != '') {
          $where .= " AND hobi LIKE '%{$filter['hobi']}%'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_hobi id,
      hobi name
    FROM ms_hobi jp
    $where 
    ORDER BY id_hobi ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Hobi');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getJenisPembelian($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id_jenis_pembelian='{$filter['id']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_jenis_pembelian id,
      jenis_pembelian AS name
    FROM ms_jenis_pembelian jp
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Jenis Pembelian ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getJenisPenjualan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id='{$filter['id']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id,
      name
    FROM sc_ms_jenis_penjualan jp
    $where 
    ORDER BY id ASC
    $limit
    ");
    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Jenis Penjualan ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getKeperluanPembelian($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id_digunakan='{$filter['id']}'";
        }
      }
      if (isset($filter['name'])) {
        if ($filter['name'] != '') {
          $where .= " AND digunakan='{$filter['name']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_digunakan LIKE '%{$filter['search']}%'
                        OR digunakan LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_digunakan id,
      digunakan name
    FROM ms_digunakan dg
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Keperluan Pembelian ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getMetodeFollowUp($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        $where .= " AND id='{$filter['id']}'";
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    return $this->db->query("SELECT 
      id,
      name
    FROM sc_ms_metode_follow_up fu
    $where 
    ORDER BY id ASC
    $limit
    ");
  }
  function getStatusRumah($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        $where .= " AND id='{$filter['id']}'";
      }
      if (isset($filter['name'])) {
        $where .= " AND sr.name='{$filter['name']}'";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT id,name
      FROM sc_ms_status_rumah sr
    $where 
    ORDER BY id ASC
    $limit
    ");
    if (isset($filter['cek_referensi'])) {

      cek_referensi($result, 'Status Rumah ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getSetting($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_customer LIKE '%{$filter['search']}%'
                        OR nama_konsumen LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    return $this->db->query("SELECT prospek_status_min,prospek_status_max
      FROM sc_setting st
    $where
    $limit
    ");
  }
  function getElearning($filter)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['business_function'])) {
        if ($filter['business_function'] != '') {
          $where .= " AND business_function='{$filter['business_function']}'";
        }
      }
      if (isset($filter['role'])) {
        if ($filter['role'] != '') {
          $where .= " AND role.code='{$filter['role']}'";
        }
      }
      if (isset($filter['aktif'])) {
        if ($filter['aktif'] != '') {
          $where .= " AND sc.active='{$filter['aktif']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (title LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['page'])) {
        $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
        $length = 10;
        // $start = $page == 1 ? 0 : $length * ($page - 1);
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT 
      sc.id,title
    FROM sc_ms_elearning sc
    JOIN sc_ms_role role ON role.id=sc.role
    $where 
    ORDER BY id ASC
    $limit
    ");
  }
  function getElearningDetail($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['parent_id'])) {
        if ($filter['parent_id'] != '') {
          $where .= " AND parent_id='{$filter['parent_id']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (title LIKE '%{$filter['search']}%'
                        OR content_type LIKE '%{$filter['search']}%'
                        OR extension LIKE '%{$filter['search']}%'
                        OR url LIKE '%{$filter['search']}%'
                        OR player LIKE '%{$filter['search']}%'
                        OR size LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
      if (isset($filter['page'])) {
        $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
        $length = 10;
        // $start = $page == 1 ? 0 : $length * ($page - 1);
        $start = $length * $page;
        $limit = "LIMIT $start, $length";
      }
    }
    return $this->db->query("SELECT 
      id,parent_id,title,content_type,extension,url,player,size
    FROM sc_ms_elearning_detail dtl
    $where 
    ORDER BY id ASC
    $limit
    ");
  }
  function getAlasanReassign($filter = NULL)
  {
    $where = 'WHERE aktif=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id='{$filter['id']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id LIKE '%{$filter['search']}%'
                        OR name LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result =  $this->db->query("SELECT id,name
    FROM sc_ms_alasan_reassign asg
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Alasan Reassign ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getKaryawan($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    $join = '';
    // send_json($filter);
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        if ($filter['id_dealer'] != '') {
          $where .= " AND kry.id_dealer='{$filter['id_dealer']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer'])) {
        if ($filter['id_karyawan_dealer'] != '') {
          $where .= " AND kry.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
        }
      }
      if (isset($filter['id_user'])) {
        if ($filter['id_user'] != '') {
          $where .= " AND usr.id_user='{$filter['id_user']}'";
        }
      }
      if (isset($filter['role_code'])) {
        if ($filter['role_code'] != '') {
          $where .= " AND role.code='{$filter['role_code']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer_int'])) {
        if ($filter['id_karyawan_dealer_int'] != '') {
          $where .= " AND kry.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer_int_not'])) {
        if ($filter['id_karyawan_dealer_int_not'] != '') {
          $where .= " AND kry.id_karyawan_dealer_int!='{$filter['id_karyawan_dealer_int_not']}'";
        }
      }
      if (isset($filter['id_user_not_null'])) {
        if ($filter['id_user_not_null'] != '') {
          $where .= " AND usr.id_user IS NOT NULL";
        }
      }
      if (isset($filter['id_jabatan_in'])) {
        if ($filter['id_jabatan_in'] != '') {
          $where .= " AND kry.id_jabatan IN({$filter['id_jabatan_in']})";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_karyawan_dealer LIKE '%{$filter['search']}%'
                        OR nama_lengkap LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $select = "id_karyawan_dealer_int AS id,nama_lengkap AS name, '' AS image";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'all') {
        $select = "*,kry.id_karyawan_dealer";
      } else {
        $select = $filter['select'];
      }
    }

    if (isset($filter['join'])) {
      if ($filter['join'] == 'join_team_structure') {
        $join = "
        JOIN dms_team_structure_management_detail tsmd ON tsmd.id_karyawan_dealer=kry.id_karyawan_dealer
        JOIN dms_team_structure_management tsm ON tsm.id_team_structure=tsmd.id_team_structure
        ";
      }
    }
    $result =  $this->db->query("SELECT $select
    FROM ms_karyawan_dealer kry
    LEFT JOIN ms_user usr ON usr.id_karyawan_dealer=kry.id_karyawan_dealer
    LEFT JOIN sc_ms_role role ON role.id=usr.role_sc
    $join
    $where 
    ORDER BY kry.id_karyawan_dealer ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Employee ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getKaryawanTraining($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        if ($filter['id_dealer'] != '') {
          $where .= " AND kry.id_dealer='{$filter['id_dealer']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer'])) {
        if ($filter['id_karyawan_dealer'] != '') {
          $where .= " AND kry.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
        }
      }
      if (isset($filter['id_user'])) {
        if ($filter['id_user'] != '') {
          $where .= " AND usr.id_user='{$filter['id_user']}'";
        }
      }
      if (isset($filter['id_karyawan_dealer_int'])) {
        if ($filter['id_karyawan_dealer_int'] != '') {
          $where .= " AND kry.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
        }
      }
      if (isset($filter['id_user_not_null'])) {
        if ($filter['id_user_not_null'] != '') {
          $where .= " AND usr.id_user IS NOT NULL";
        }
      }
      if (isset($filter['id_jabatan_in'])) {
        if ($filter['id_jabatan_in'] != '') {
          $where .= " AND kry.id_jabatan IN({$filter['id_jabatan_in']})";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_karyawan_dealer LIKE '%{$filter['search']}%'
                        OR nama_lengkap LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $select = "kry.id_karyawan_dealer,kryt.id_training,training";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'all') {
        $select = "*";
      }
    }
    $result =  $this->db->query("SELECT $select
    FROM ms_karyawan_dealer kry
    JOIN ms_karyawan_dealer_training kryt ON kryt.id_karyawan_dealer=kry.id_karyawan_dealer
    JOIN ms_training tr ON tr.id_training=kryt.id_training
    LEFT JOIN ms_user usr ON usr.id_karyawan_dealer=kry.id_karyawan_dealer
    $where 
    ORDER BY kryt.id_karyawan_dealer_training DESC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Employee ID');
      return $result->row();
    } else {
      return $result;
    }
  }

  function getSubject($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= "imsgtype='{$filter['id']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (imsgtype LIKE '%{$filter['search']}%'
                        OR message_type LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result =  $this->db->query("SELECT imsgtype id,message_type name
    FROM dms_message_type sbj
    $where 
    ORDER BY imsgtype ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Subject ID');
      return $result->row();
    } else {
      return $result;
    }
  }

  function getFollowUpActivity($filter = NULL)
  {
    $where = 'WHERE aktif=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= "id='{$filter['id']}'";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id LIKE '%{$filter['search']}%'
                        OR name LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result =  $this->db->query("SELECT id,name
    FROM sc_ms_follow_up_activity fol
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Follow Up Activity ID');
      return $result->row();
    } else {
      return $result;
    }
  }

  function getTanggalDalamBulan($params)
  {
    $year = gmdate("Y", time() + 60 * 60 * 7);
    $bulan = gmdate("m", time() + 60 * 60 * 7);
    $params['bulan'] = $params['bulan'] == '' ? $bulan : $params['bulan'];

    $start_tanggal = $year . '-' . $params['bulan'] . '-1';
    $end_tanggal   = $year . '-' . $params['bulan'] . '-' . date('t', strtotime($start_tanggal));
    $id = 1;
    while (strtotime($start_tanggal) <= strtotime($end_tanggal)) {
      $tanggal  = date('d', strtotime($start_tanggal));
      $ymd  = date('Y-m-d', strtotime($start_tanggal));
      $hari[] =
        [
          'id' => $id,
          'tgl' => (int)$tanggal,
          'name' => nama_hari($ymd)
        ];

      $start_tanggal = date("Y-m-d", strtotime("+1 day", strtotime($start_tanggal)));
      $id++;
    }
    return $hari;
  }
  function getAlasanNotDeal($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_alasan_cancel'])) {
        if ($filter['id_alasan_cancel'] != '') {
          $where .= " AND id_alasan_cancel='{$filter['id_alasan_cancel']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id_alasan_cancel LIKE '%{$filter['search']}%'
                        OR alasan_cancel LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_alasan_cancel id,
      alasan_cancel name
    FROM ms_alasan_cancel ms
    $where 
    ORDER BY id_alasan_cancel ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Alasan Not Deal ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getSumberProspek($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id'])) {
        if ($filter['id'] != '') {
          $where .= " AND id='{$filter['id']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id LIKE '%{$filter['search']}%'
                        OR ms.description LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id,
      ms.description name
    FROM ms_sumber_prospek ms
    $where 
    ORDER BY id ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Sumber prospek ID');
      return $result->row();
    } else {
      return $result;
    }
  }
  function getHobby($filter = NULL)
  {
    $where = 'WHERE 1=1 ';
    $limit = '';
    if ($filter != NULL) {
      if (isset($filter['id_hobi'])) {
        if ($filter['id_hobi'] != '') {
          $where .= " AND id_hobi='{$filter['id']}'";
        }
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $where .= " AND (id LIKE '%{$filter['search']}%'
                        OR ms.hobi LIKE '%{$filter['search']}%'
                        )
        ";
        }
      }
    }
    $result = $this->db->query("SELECT 
      id_hobi id,
      ms.hobi name
    FROM ms_hobi ms
    $where 
    ORDER BY id_hobi ASC
    $limit
    ");

    if (isset($filter['cek_referensi'])) {
      cek_referensi($result, 'Hobby ID');
      return $result->row();
    } else {
      return $result;
    }
  }
}
