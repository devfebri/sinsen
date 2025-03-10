<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dgi_api extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function fetch_getProspek($filter)
  {
    $id_dealer = dealer()->id_dealer;
    $where_id   = "WHERE prp.id_dealer='$id_dealer' ";
    $where_gc   = "WHERE prp_hd.id_dealer='$id_dealer' ";
    $where = "WHERE 1=1 ";

    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where_id .= "AND prp.id_prospek='{$filter['id_prospek']}' ";
        $where_gc .= "AND prp_gc.id_prospek_gc='{$filter['id_prospek']}' ";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where_id .= "AND prp.id_karyawan_dealer='{$filter['id_karyawan_dealer']}' ";
        $where_gc .= "AND prp_hd.id_karyawan_dealer='{$filter['id_karyawan_dealer']}' ";
      }
    }
    if (isset($filter['start']) && isset($filter['end'])) {
      if ($filter['start'] != NULL && $filter['end'] != NULL) {
        $start = date_ymd($filter['start']);
        $end = date_ymd($filter['end']);
        $where_id .= "AND LEFT(prp.created_at,10) BETWEEN '$start' AND '$end'";
        $where_gc .= "AND LEFT(prp_gc.created_at,10) BETWEEN '$start' AND '$end'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where_id .= " AND (id_prospek LIKE '%$search%'
                            OR nama_konsumen LIKE '%$search%'
                            OR prp.no_hp LIKE '%$search%'
                            OR sumber_prospek LIKE '%$search%'
                            OR LEFT(prp.created_at,10) LIKE '%$search%'
                            ) 
            ";
        $where_gc .= " AND (id_prospek LIKE '%$search%'
                            OR nama_npwp LIKE '%$search%'
                            OR prp.no_hp LIKE '%$search%'
                            OR prp_g.sumber_prospek LIKE '%$search%'
                            OR LEFT(prp_g.created_at,10) LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalProspek') {
          $order_column = ['id_prospek', 'tgl_prospek', 'nama_konsumen', 'no_hp', 'sumber_prospek', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $where .= " ORDER BY $order_clm $order_by ";
      } else {
        $where .= " ORDER BY nama_konsumen ASC ";
        // $where_gc .= " ORDER BY nama_npwp ASC ";
      }
    }

    if (isset($filter['limit'])) {
      $where .= $filter['limit'];
      // $where_gc .= $filter['limit'];
    }

    $group_by_id = '';
    $group_by_gc = '';
    if (isset($filter['group_by_prospek'])) {
      if ($filter['group_by_prospek'] != '') {
        $group_by_id = " GROUP BY prp.id_prospek ";
        $group_by_gc = " GROUP BY prp_gc.id_prospek_gc ";
      }
    }

    $tgl_appointment = "SELECT tgl_fol_up FROM tr_prospek_fol_up WHERE id_prospek=prp.id_prospek ORDER BY id DESC LIMIT 1";
    $tgl_appointment_gc = "SELECT tgl_fol_up FROM tr_prospek_fol_up WHERE id_prospek=prp_gc.id_prospek_gc ORDER BY id DESC LIMIT 1";
    $waktu_appointment = "SELECT waktu_fol_up FROM tr_prospek_fol_up WHERE id_prospek=prp.id_prospek ORDER BY id DESC LIMIT 1";
    $waktu_appointment_gc = "SELECT waktu_fol_up FROM tr_prospek_fol_up WHERE id_prospek=prp_gc.id_prospek_gc ORDER BY id DESC LIMIT 1";

    $metode_fol_up = "SELECT 
      CASE 
        WHEN metode_fol_up = 'SMS' THEN 1
        WHEN metode_fol_up = 'Telepon' THEN 2
        WHEN metode_fol_up = 'Visit' THEN 3
        WHEN metode_fol_up = 'Direct Touch' THEN 4
      END
    FROM tr_prospek_fol_up 
    WHERE id_prospek=prp.id_prospek ORDER BY id DESC LIMIT 1";

    $metode_fol_up_gc = "SELECT 
      CASE 
        WHEN metode_fol_up = 'SMS' THEN 1
        WHEN metode_fol_up = 'Telepon' THEN 2
        WHEN metode_fol_up = 'Visit' THEN 3
        WHEN metode_fol_up = 'Direct Touch' THEN 4
      END
    FROM tr_prospek_fol_up 
    WHERE id_prospek=prp_hd.id_prospek_gc ORDER BY id DESC LIMIT 1";

    $query = "SELECT * FROM(
      SELECT 
        id_prospek,
        nama_konsumen,
        LEFT(prp.created_at,10) AS tgl_prospek,
        prp.no_hp,
        sumber_prospek,
        no_ktp,
        prp.alamat,
        prov.id_provinsi,
        kab.id_kabupaten,
        kec.id_kecamatan,
        kel.id_kelurahan,
        kel.kode_pos,
        alamat_kantor,
        latitude,
        longitude,
        pekerjaan,
        prp.created_at,
        no_telp_kantor,
        ($tgl_appointment) AS tgl_appointment,
        ($waktu_appointment) AS waktu_appointment,
        ($metode_fol_up) AS metode_fol_up,
        status_prospek,
        sales.id_flp_md,
        sales.honda_id,
        prp.id_tipe_kendaraan,
        CASE WHEN test_ride_preference ='yes' THEN 1 ELSE 0 END AS test_ride_preference,
        kel_kantor.id_kelurahan AS id_kelurahan_kantor,
        kec_kantor.id_kecamatan AS id_kecamatan_kantor,
        kab_kantor.id_kabupaten AS id_kabupaten_kantor,
        prov_kantor.id_provinsi AS id_provinsi_kantor,
        kel_kantor.kode_pos AS kode_pos_kantor,
        prioritas_prospek,
        dl.kode_dealer_md,
        prp.id_event,
        prp.program_umum,
        prp.updated_at
        FROM tr_prospek prp
        JOIN ms_kelurahan kel ON kel.id_kelurahan=prp.id_kelurahan
        LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
        LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
        LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
        LEFT JOIN ms_karyawan_dealer sales ON sales.id_karyawan_dealer=prp.id_karyawan_dealer
        LEFT JOIN ms_kelurahan kel_kantor ON kel_kantor.id_kelurahan=prp.id_kelurahan_kantor
        LEFT JOIN ms_kecamatan kec_kantor ON kec_kantor.id_kecamatan=kel_kantor.id_kecamatan
        LEFT JOIN ms_kabupaten kab_kantor ON kab_kantor.id_kabupaten=kec_kantor.id_kabupaten
        LEFT JOIN ms_provinsi prov_kantor ON prov_kantor.id_provinsi=kab.id_provinsi
        JOIN ms_dealer dl ON dl.id_dealer=prp.id_dealer
        $where_id
        $group_by_id
      UNION
        SELECT 
        prp_gc.id_prospek_gc,
        nama_npwp,
        LEFT(prp_hd.created_at,10) AS tgl_prospek,
        prp_hd.no_hp,
        sumber_prospek,
        no_npwp,
        prp_hd.alamat,
        prov.id_provinsi,
        kab.id_kabupaten,
        kec.id_kecamatan,
        kel.id_kelurahan,
        kel.kode_pos,
        alamat_kantor,
        latitude,
        longitude,
        '',
        prp_hd.created_at,
        no_telp_kantor,
        ($tgl_appointment_gc) AS tgl_appointment,
        ($waktu_appointment_gc) AS waktu_appointment,
        ($metode_fol_up_gc) AS metode_fol_up,
        status_prospek,
        sales.id_flp_md,
        sales.honda_id,
        prp_gc.id_tipe_kendaraan,
        CASE WHEN test_ride_preference ='yes' THEN 1 ELSE 0 END AS test_ride_preference,
        kel_kantor.id_kelurahan AS id_kelurahan_kantor,
        kec_kantor.id_kecamatan AS id_kecamatan_kantor,
        kab_kantor.id_kabupaten AS id_kabupaten_kantor,
        prov_kantor.id_provinsi AS id_provinsi_kantor,
        kel_kantor.kode_pos AS kode_pos_kantor,
        prioritas_prospek,
        dl.kode_dealer_md,
        prp_hd.id_event,
        prp_hd.program_umum,
        prp_hd.updated_at
        FROM tr_prospek_gc_kendaraan prp_gc
        JOIN tr_prospek_gc prp_hd ON prp_hd.id_prospek_gc=prp_gc.id_prospek_gc
        JOIN ms_kelurahan kel ON kel.id_kelurahan=prp_hd.id_kelurahan
        LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
        LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
        LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
        LEFT JOIN ms_karyawan_dealer sales ON sales.id_karyawan_dealer=prp_hd.id_karyawan_dealer
        LEFT JOIN ms_kelurahan kel_kantor ON kel_kantor.id_kelurahan=prp_hd.id_kelurahan_kantor
        LEFT JOIN ms_kecamatan kec_kantor ON kec_kantor.id_kecamatan=kel_kantor.id_kecamatan
        LEFT JOIN ms_kabupaten kab_kantor ON kab_kantor.id_kabupaten=kec_kantor.id_kabupaten
        LEFT JOIN ms_provinsi prov_kantor ON prov_kantor.id_provinsi=kab.id_provinsi
        JOIN ms_dealer dl ON dl.id_dealer=prp_hd.id_dealer
        $where_gc
        $group_by_gc
    ) AS tabel $where
    ";
    return $this->db->query($query);
  }

  function fetch_getKaryawanDealer($filter)
  {
    $set_filter   = "WHERE kd.id_dealer='{$filter['id_dealer']}' ";

    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $set_filter .= "AND id_karyawan_dealer='{$filter['id_karyawan_dealer']}' ";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $set_filter .= "AND kd.active='{$filter['active']}' ";
      }
    }
    if (isset($filter['id_jabatan'])) {
      if ($filter['id_jabatan'] != '') {
        $set_filter .= "AND kd.id_jabatan='{$filter['id_jabatan']}' ";
      }
    }
    if (isset($filter['filter_not_in_team_structure'])) {
      if ($filter['filter_not_in_team_structure'] != '') {
        $set_filter .= "AND NOT EXISTS(SELECT id_karyawan_dealer FROM dms_team_structure_management_detail tsmd WHERE tsmd.id_karyawan_dealer=kd.id_karyawan_dealer) ";
      }
    }
    if (isset($filter['filter_sales_coordinator_not_in_team_structure'])) {
      if ($filter['filter_sales_coordinator_not_in_team_structure'] != '') {
        $set_filter .= "AND NOT EXISTS(SELECT id_karyawan_dealer FROM dms_team_structure_management tsmd WHERE tsmd.id_sales_coordinator=kd.id_karyawan_dealer) ";
      }
    }
    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (kd.id_karyawan_dealer LIKE '%$search%'
                            OR nama_lengkap LIKE '%$search%'
                            OR id_flp_md LIKE '%$search%'
                            OR honda_id LIKE '%$search%'
                            OR jbt.jabatan LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      if ($filter['order_column'] == 'modalKaryawanDealer') {
        $order_column = ['id_karyawan_dealer', 'id_flp_md', 'honda_id', 'nama_lengkap', 'jabatan', NULL];
      }
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY nama_lengkap ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_karyawan_dealer,nama_lengkap,id_flp_md,honda_id,kd.id_jabatan,jabatan,no_hp
    FROM ms_karyawan_dealer kd
    LEFT JOIN ms_jabatan jbt ON jbt.id_jabatan=kd.id_jabatan
    $set_filter
    ");
  }

  function getSPKAnggotaKK($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $set_filter   = "WHERE 1=1 ";

    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $set_filter .= "AND id_prospek='{$filter['id_prospek']}' ";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $set_filter .= "AND prp.id_karyawan_dealer='{$filter['id_karyawan_dealer']}' ";
      }
    }
    if (isset($filter['start']) && isset($filter['end'])) {
      $set_filter .= "AND LEFT(spk.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    $query = "SELECT spk.no_spk,id_prospek,anggota,spk.created_at,spk.updated_at
    FROM tr_spk spk
    JOIN tr_spk_anggota_kk spk_kk ON spk_kk.no_spk=spk.no_spk
    JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
    $set_filter
    ";
    return $this->db->query($query);
  }

  function getSPK($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $where_id   = "WHERE spk.id_dealer='$id_dealer' ";
    $where_gc   = "WHERE spk_gc.id_dealer='$id_dealer' ";
    $join_id = '';
    $join_gc = '';
    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where_id .= "AND (SELECT id_prospek FROm tr_prospek WHERE id_customer=spk.id_customer)='{$filter['id_prospek']}' ";
        $where_gc .= "AND spk_gc.id_prospek_gc='{$filter['id_prospek']}' ";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where_id .= "AND prp.id_karyawan_dealer='{$filter['id_karyawan_dealer']}' ";
        $where_gc .= "AND prp_gc.id_karyawan_dealer='{$filter['id_karyawan_dealer']}' ";
      }
    }
    if (isset($filter['start']) && isset($filter['end'])) {
      if (isset($filter['periode'])) {
        if ($filter['periode'] == 'bast') {
          $where_id .= " AND LEFT(so.tgl_bastk,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
          $where_gc .= " AND LEFT(so_gc.tgl_bastk,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
        }
      } else {
        $where_id .= "AND LEFT(spk.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
        $where_gc .= "AND LEFT(spk_gc.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where_id .= " AND (prp.id_prospek LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_customer LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            ) 
            ";
        $where_gc .= " AND (prp_gc.id_prospek_gc LIKE '%$search%'
                            OR spk_gc.nama_npwp LIKE '%$search%'
                            OR spk_gc.no_hp LIKE '%$search%'
                            OR spk_gc.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }
    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalSPK') {
          $order_column = ['no_spk', 'id_customer', 'nama_konsumen', 'no_ktp', 'alamat', 'id_tipe_kendaraan', 'id_warna', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY nama_konsumen ASC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $diskon = "CASE 
                WHEN jenis_beli='Kredit' THEN
                  IFNULL(voucher_2,0) + IFNULL(voucher_tambahan_2,0) + IFNULL(diskon,0)
                WHEN jenis_beli='Cash' THEN
                  IFNULL(voucher_1,0) + IFNULL(voucher_tambahan_1,0) + IFNULL(diskon,0)
              END
    ";
    $diskon_gc = "0";
    if (isset($filter['spk_so_generate_list_unit'])) {
      if ($filter['spk_so_generate_list_unit'] == true) {
        $join_id = " JOIN tr_sales_order so ON so.no_spk=spk.no_spk
            JOIN tr_generate_list_unit_delivery_detail glud ON glud.id_sales_order=so.id_sales_order
            JOIN tr_generate_list_unit_delivery glu ON glu.id_generate=glud.id_generate
        ";
        $join_gc = "JOIN tr_sales_order_gc so_gc ON so_gc.no_spk_gc=spk_gc.no_spk_gc
          JOIN tr_sales_order_gc_nosin so_gc_nosin ON so_gc_nosin.no_spk_gc=so_gc.no_spk_gc
          JOIN tr_generate_list_unit_delivery_detail glud_gc ON glud_gc.no_mesin=so_gc_nosin.no_mesin 
          JOIN tr_generate_list_unit_delivery glu_gc ON glu_gc.id_generate=glud_gc.id_generate
      ";
      }
    }
    if (isset($filter['finco_not_null'])) {
      if ($filter['finco_not_null'] == true) {
        $where_id .= "AND (spk.id_finance_company!='' OR spk.id_finance_company IS NULL)";
        $where_gc .= "AND (spk_gc.id_finance_company!='' OR spk_gc.id_finance_company IS NULL)";
      }
    }
    if (isset($filter['spk_id_so_not_null'])) {
      if ($filter['spk_id_so_not_null'] == true) {
        $join_id = "JOIN tr_sales_order so ON so.no_spk=spk.no_spk";
        $join_gc = "JOIN tr_sales_order_gc so_gc ON so_gc.no_spk_gc=spk_gc.no_spk_gc";
      }
      // $join_gcd = " JOIN tr_sales_order_gc so_gc ON so_gc.no_spk_gc=spk.no_spk
      //     JOIN tr_generate_list_unit_delivery_detail glud ON glud.id_sales_order=so.id_sales_order
      //     JOIN tr_generate_list_unit_delivery glu ON glu.id_generate=glud.id_generate
      // ";
    }
    if (isset($filter['spk_id_so_not_null'])) {
      $where_id .= "AND spk.id_finance_company IS NOT NULL";
      $where_gc .= "AND spk_gc.id_finance_company IS NOT NULL";
      // $join_gcd = " JOIN tr_sales_order_gc so_gc ON so_gc.no_spk_gc=spk.no_spk
      //     JOIN tr_generate_list_unit_delivery_detail glud ON glud.id_sales_order=so.id_sales_order
      //     JOIN tr_generate_list_unit_delivery glu ON glu.id_generate=glud.id_generate
      // ";
    }
    // send_json($join_id);
    $query = "SELECT * FROM(
    SELECT 
      spk.no_spk,
      id_prospek,
      spk.nama_konsumen,
      spk.no_ktp,
      spk.alamat,
      prov.id_provinsi,
      kab.id_kabupaten,
      kec.id_kecamatan,
      kel.id_kelurahan,
      spk.kodepos,
      spk.no_hp,
      nama_bpkb_stnk,
      no_ktp_bpkb,
      alamat_ktp_bpkb,
      spk.alamat2 AS alamat_ktp,
      kel2.id_kelurahan AS id_kel_ktp,
      kec2.id_kecamatan AS id_kec_ktp,
      kab2.id_kabupaten AS id_kab_ktp,
      prov2.id_provinsi AS id_prov_ktp,
      kel2.kode_pos AS kodepos_ktp,
      spk.latitude,
      spk.longitude,
      spk.npwp,
      spk.no_kk,
      alamat_kk,
      kel_kk.id_kelurahan AS id_kel_kk,
      kec_kk.id_kecamatan AS id_kec_kk,
      kab_kk.id_kabupaten AS id_kab_kk,
      prov_kk.id_provinsi AS id_prov_kk,
      spk.kode_pos_kk AS kodepos_kk,
      fax,
      spk.email,
      prp.id_flp_md,
      spk.id_event,
      tgl_spk,
      status_spk,
      spk.id_tipe_kendaraan,
      spk.id_warna,
      harga,
      ($diskon) AS diskon,
      faktur_pajak AS faktur_pajak,
      spk.jenis_beli,
      spk.tanda_jadi,
      spk.tgl_pengiriman,
      '' AS id_apparel,
      spk.id_customer,
      1 AS qty,
      0 AS amount_ppn,
      0 AS program_umum,
      spk.created_at,
      spk.updated_at,
      dl.kode_dealer_md
      FROM tr_spk spk
      JOIN ms_dealer dl ON dl.id_dealer=spk.id_dealer
      JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
      JOIN ms_kelurahan kel ON kel.id_kelurahan=spk.id_kelurahan
      LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
      LEFT JOIN ms_provinsi prov ON prov.id_provinsi=kab.id_provinsi
      JOIN ms_kelurahan kel2 ON kel2.id_kelurahan=spk.id_kelurahan2
      LEFT JOIN ms_kecamatan kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
      LEFT JOIN ms_kabupaten kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
      LEFT JOIN ms_provinsi prov2 ON prov2.id_provinsi=kab2.id_provinsi
      LEFT JOIN ms_kelurahan kel_kk ON kel_kk.id_kelurahan=spk.id_kelurahan_kk
      LEFT JOIN ms_kecamatan kec_kk ON kec_kk.id_kecamatan=kel2.id_kecamatan
      LEFT JOIN ms_kabupaten kab_kk ON kab_kk.id_kabupaten=kec2.id_kabupaten
      LEFT JOIN ms_provinsi prov_kk ON prov_kk.id_provinsi=kab_kk.id_provinsi
      $join_id
      $where_id
      GROUP BY spk.no_spk
    UNION
    SELECT 
      spk_gc.no_spk_gc,
      spk_gc.id_prospek_gc,
      spk_gc.nama_npwp,
      spk_gc.no_npwp,
      spk_gc.alamat,
      prov_gc.id_provinsi,
      kab_gc.id_kabupaten,
      kec_gc.id_kecamatan,
      kel_gc.id_kelurahan,
      spk_gc.kodepos,
      spk_gc.no_hp,
      '' nama_bpkb_stnk,
      '' no_ktp_bpkb,
      '' alamat_ktp_bpkb,
      spk_gc.alamat2 AS alamat_ktp,
      kel2_gc.id_kelurahan AS id_kel_ktp,
      kec2_gc.id_kecamatan AS id_kec_ktp,
      kab2_gc.id_kabupaten AS id_kab_ktp,
      prov2_gc.id_provinsi AS id_prov_ktp,
      kel2_gc.kode_pos AS kodepos_ktp,
      spk_gc.latitude,
      spk_gc.longitude,
      spk_gc.no_npwp,
      '' AS no_kk,
      '' AS alamat_kk,
      '' AS id_kel_kk,
      '' AS id_kec_kk,
      '' AS id_kab_kk,
      '' AS id_prov_kk,
      '' AS kodepos_kk,
      no_fax,
      spk_gc.email,
      kd_gc.id_flp_md,
      spk_gc.id_event,
      tgl_spk_gc,
      spk_gc.status,
      spk_dt.id_tipe_kendaraan,
      spk_dt.id_warna,
      spk_dt.harga,
      ($diskon_gc) AS diskon,
      faktur_pajak AS faktur_pajak,
      spk_gc.jenis_beli,
      spk_gc.tanda_jadi,
      spk_gc.tgl_pengiriman,
      '' AS id_apparel,
      spk_gc.id_prospek_gc,
      1 AS qty,
      0 AS amount_ppn,
      0 AS program_umum,
      spk_gc.created_at,
      spk_gc.updated_at,
      dl.kode_dealer_md
      FROM tr_spk_gc_detail spk_dt
      JOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc=spk_dt.no_spk_gc
      JOIN ms_dealer dl ON dl.id_dealer=spk_gc.id_dealer
      JOIN tr_prospek_gc prp_gc ON prp_gc.id_prospek_gc=spk_gc.id_prospek_gc
      JOIN ms_karyawan_dealer kd_gc ON kd_gc.id_karyawan_dealer=prp_gc.id_karyawan_dealer
      JOIN ms_kelurahan kel_gc ON kel_gc.id_kelurahan=spk_gc.id_kelurahan
      LEFT JOIN ms_kecamatan kec_gc ON kec_gc.id_kecamatan=kel_gc.id_kecamatan
      LEFT JOIN ms_kabupaten kab_gc ON kab_gc.id_kabupaten=kec_gc.id_kabupaten
      LEFT JOIN ms_provinsi prov_gc ON prov_gc.id_provinsi=kab_gc.id_provinsi
      JOIN ms_kelurahan kel2_gc ON kel2_gc.id_kelurahan=spk_gc.id_kelurahan2
      LEFT JOIN ms_kecamatan kec2_gc ON kec2_gc.id_kecamatan=kel2_gc.id_kecamatan
      LEFT JOIN ms_kabupaten kab2_gc ON kab2_gc.id_kabupaten=kec2_gc.id_kabupaten
      LEFT JOIN ms_provinsi prov2_gc ON prov2_gc.id_provinsi=kab2_gc.id_provinsi
      $join_gc
      $where_gc
    ) AS tabel
    $order
    $limit
    ";
    return $this->db->query($query);
  }

  function getInvoiceSPK($filter)
  {
    $id_dealer = dealer()->id_dealer;

    $where   = "WHERE dl.id_dealer='$id_dealer' ";

    if (isset($filter['start']) && isset($filter['end'])) {
      // $where .= "AND inv.created_at BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= "AND inv.id_spk='{$filter['no_spk']}' ";
      }
    }

    if (isset($filter['id_customer'])) {
      if ($filter['id_customer'] != '') {
        $where .= "AND inv.id_customer='{$filter['id_customer']}' ";
      }
    }

    $where_tjs   = '';
    $where_dp    = '';
    $where_lunas = '';

    if (isset($filter['id_invoice'])) {
      if ($filter['id_invoice'] != '') {
        $where_tjs = " AND inv.id_invoice='{$filter['id_invoice']}' ";
        $where_dp = " AND inv.id_invoice_dp='{$filter['id_invoice']}' ";
        $where_lunas = " AND inv.id_inv_pelunasan='{$filter['id_invoice']}' ";
      }
    }

    return $this->db->query("SELECT * FROM(
      SELECT inv.id_invoice,inv.id_spk no_spk,inv.id_customer,rc.amount,inv.jenis_beli,cara_bayar, inv.status,note,dl.kode_dealer_md,inv.created_at,inv.updated_at,inv.id_dealer 
      FROM tr_invoice_tjs inv
      JOIN tr_h1_dealer_invoice_receipt rc ON rc.id_invoice=inv.id_invoice
      JOIN ms_dealer dl ON dl.id_dealer=inv.id_dealer
      $where
      $where_tjs
      UNION
      SELECT inv.id_invoice_dp,inv.id_spk,inv.id_customer,amount_dp AS amount,jenis_beli,rc.cara_bayar, inv.status,note,dl.kode_dealer_md,inv.created_at,inv.updated_at,inv.id_dealer
      FROM tr_invoice_dp inv
      JOIN tr_h1_dealer_invoice_receipt rc ON rc.id_invoice=inv.id_invoice_dp
      JOIN ms_dealer dl ON dl.id_dealer=inv.id_dealer
      $where
      $where_dp
      UNION
      SELECT inv.id_inv_pelunasan,inv.id_spk,inv.id_customer,amount_pelunasan AS amount,jenis_beli,rc.cara_bayar, inv.status,note,dl.kode_dealer_md,inv.created_at,inv.updated_at,inv.id_dealer
      FROM tr_invoice_pelunasan inv
      JOIN tr_h1_dealer_invoice_receipt rc ON rc.id_invoice=inv.id_inv_pelunasan
      JOIN ms_dealer dl ON dl.id_dealer=inv.id_dealer
      $where
      $where_lunas
    ) AS tabel ORDER BY created_at DESC
    ");
  }

  function getLeasingData($filter)
  {
    // send_json($filter);
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE 1=1 AND etr.id_dealer='$id_dealer' ";

    if (isset($filter['no_spk'])) {
      $where .= " AND etr.no_spk='{$filter['no_spk']}'";
    }
    if (isset($filter['periode'])) {
      if ($filter['periode'] == 'lsng') {
        if (isset($filter['start']) && isset($filter['end'])) {
          $where .= " AND LEFT(etr.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
        }
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (prp.id_prospek LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_customer LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalDelivery') {
          $order_column = ['delivery_document_id', 'id_sales_order', 'spk.nama_konsumen', 'no_mesin', 'no_rangka', 'id_tipe_kendaraan', 'id_warna', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY nama_konsumen ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT etr.*,kode_dealer_md
      FROM tr_entry_po_leasing etr
      JOIN ms_dealer dl ON dl.id_dealer=etr.id_dealer
      $where
      $order
      $limit
    ");
  }
  function getSalesOrder($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $where_id   = "WHERE so.id_dealer='$id_dealer' ";
    $where_gc   = "WHERE so_gc.id_dealer='$id_dealer' ";
    $join_id = '';
    $select_id = '';
    $group_id = '';
    $join_gc = '';
    $select_gc = '';
    $group_gc = '';
    $where = 'WHERE 1=1 ';

    if (isset($filter['no_spk'])) {
      $where_id .= " AND spk.no_spk='{$filter['no_spk']}'";
      $where_gc .= " AND spk_gc.no_spk_gc='{$filter['no_spk']}'";
    }
    if (isset($filter['id_sales_order'])) {
      $where_id .= " AND so.id_sales_order='{$filter['id_sales_order']}'";
      $where_gc .= " AND so_gc.id_sales_order_gc='{$filter['id_sales_order']}'";
    }
    if (isset($filter['id_generate'])) {
      if ($filter['id_generate'] != '') {
        $where_id .= " AND glu.id_generate='{$filter['id_generate']}'";
        $where_gc .= " AND glu_gc.id_generate='{$filter['id_generate']}'";
      }
    }
    if (isset($filter['finco_not_null'])) {
      if ($filter['finco_not_null'] != '') {
        $where_id .= " AND fn.id_finance_company IS NOT NULL";
        $where_gc .= " AND fn_gc.id_finance_company!=''";
      }
    }
    if (isset($filter['delivery_document_id_not_null'])) {
      if ($filter['delivery_document_id_not_null'] != '') {
        $where_id .= " AND glu.id_generate IS NOT NULL ";
        $where_gc .= " AND glu_gc.id_generate IS NOT NULL ";
      }
    }
    if (isset($filter['periode'])) {
      if ($filter['periode'] == 'bast') {
        if (isset($filter['start']) && isset($filter['end'])) {
          $where_id .= " AND LEFT(so.tgl_bastk,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
          $where_gc .= " AND LEFT(so_gc.tgl_bastk,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
        }
      } elseif ($filter['periode'] == 'lsng') {
        if (isset($filter['start']) && isset($filter['end'])) {
          $where_id .= " AND LEFT(so.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
          $where_gc .= " AND LEFT(so_gc.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
        }
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (prp.id_prospek LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_customer LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalDelivery') {
          $order_column = ['delivery_document_id', 'id_sales_order', 'spk.nama_konsumen', 'no_mesin', 'no_rangka', 'id_tipe_kendaraan', 'id_warna', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY nama_konsumen ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    if (isset($filter['join_generate_list'])) {
      if ($filter['join_generate_list'] != '') {
        $select_id = ",glu.id_generate";
        $join_id = "JOIN tr_generate_list_unit_delivery_detail glud ON glud.id_sales_order=so.id_sales_order 
      JOIN tr_generate_list_unit_delivery glu ON glu.id_generate=glud.id_generate
      ";
        $select_gc = ",glu_gc.id_generate";
        $join_gc = "JOIN tr_generate_list_unit_delivery_detail glud_gc ON glud_gc.no_mesin=so_gc_nosin.no_mesin 
      JOIN tr_generate_list_unit_delivery glu_gc ON glu_gc.id_generate=glud_gc.id_generate
      ";
      }
    }

    if (isset($filter['group_id_generate'])) {
      $group_id = " GROUP BY glu.id_generate";
      $group_gc = " GROUP BY glu_gc.id_generate";
    }
    // $where = '';
    $chk_kelengkapan = "SELECT manual_book 
      FROM tr_generate_list_unit_delivery glu
      JOIN tr_generate_list_unit_delivery_detail glud ON glud.id_generate=glu.id_generate
      WHERE glud.no_mesin=so.no_mesin LIMIT 1
    ";
    $chk_kelengkapan_gc = "SELECT manual_book 
      FROM tr_generate_list_unit_delivery glu
      JOIN tr_generate_list_unit_delivery_detail glud ON glud.id_generate=glu.id_generate
      WHERE glud.no_mesin=so_gc_nosin.no_mesin LIMIT 1
    ";
    // $where = '';
    $id_dok_pengajuan = "SELECT id_hasil_survey FROM tr_hasil_survey WHERE no_spk=spk.no_spk ORDER BY created_at DESC LIMIT 1";
    $id_dok_pengajuan_gc = "SELECT id_hasil_survey_gc FROM tr_hasil_survey_gc WHERE no_spk_gc=spk_gc.no_spk_gc ORDER BY created_at DESC LIMIT 1";
    $tgl_pengajuan = "SELECT LEFT(created_at,10) FROM tr_hasil_survey WHERE no_spk=spk.no_spk ORDER BY created_at DESC LIMIT 1";
    $tgl_pengajuan_gc = "SELECT LEFT(created_at,10) FROM tr_hasil_survey_gc WHERE no_spk_gc=spk_gc.no_spk_gc ORDER BY created_at DESC LIMIT 1";
    $dp_stor_gc = "SELECT SUM(dp_stor) FROM tr_spk_gc_detail WHERE no_spk_gc=so_gc.no_spk_gc";
    $tenor_gc = "SELECT tenor FROM tr_spk_gc_detail WHERE no_spk_gc=so_gc.no_spk_gc LIMIT 1";
    $angsuran_gc = "SELECT SUM(angsuran) FROM tr_spk_gc_detail WHERE no_spk_gc=so_gc.no_spk_gc";

    return $this->db->query("SELECT * FROM(
      SELECT 
        so.tgl_pengiriman,
        kd_s.id_flp_md,
        status_delivery,
        so.id_sales_order,
        so.no_spk,
        so.no_mesin,
        so.no_rangka,
        spk.id_customer,
        so.waktu_pengiriman,
        ($chk_kelengkapan) AS cek_kelengkapan_unit,
        lokasi_pengiriman,
        spk.latitude,
        spk.longitude,
        nama_penerima,
        no_hp_penerima AS no_kontak_penerima,
        so.tgl_bastk AS created_at,
        spk.nama_konsumen,
        spk.id_tipe_kendaraan,
        spk.id_warna,
        so.no_po_leasing,
        so.tgl_po_leasing,
        spk.id_finance_company,
        fn.finance_company,
        ($id_dok_pengajuan) AS id_dok_pengajuan,
        ($tgl_pengajuan) AS tgl_pengajuan, 
        spk.dp_stor,
        spk.tenor,
        spk.angsuran,
        pdr.driver,
        dl.kode_dealer_md,
        '' AS updated_at,
        so.created_at AS created_at_so
        $select_id 
      FROM tr_sales_order so
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      LEFT JOIN ms_plat_dealer pdr ON pdr.id_master_plat=so.id_master_plat
      LEFT JOIN ms_karyawan_dealer kd_s ON kd_s.id_karyawan_dealer=pdr.id_karyawan_dealer
      LEFT JOIN ms_finance_company fn ON fn.id_finance_company=spk.id_finance_company
      JOIN ms_dealer dl ON dl.id_dealer=so.id_dealer
      $join_id
      $where_id
      $group_id
      UNION
      SELECT 
        so_gc.tgl_pengiriman,
        kd_s_gc.id_flp_md,
        so_gc_nosin.status_delivery,
        so_gc.id_sales_order_gc,
        so_gc.no_spk_gc,
        so_gc_nosin.no_mesin,
        sc.no_rangka,
        spk_gc.id_prospek_gc,
        so_gc.waktu_pengiriman,
        ($chk_kelengkapan_gc) AS cek_kelengkapan_unit,
        so_gc.lokasi_pengiriman,
        spk_gc.latitude,
        spk_gc.longitude,
        so_gc.nama_penerima,
        so_gc.no_hp_penerima AS no_kontak_penerima,
        so_gc.tgl_bastk AS created_at,
        spk_gc.nama_npwp,
        sc.tipe_motor,
        sc.warna,
        so_gc.no_po_leasing,
        so_gc.tgl_po_leasing,
        spk_gc.id_finance_company,
        fn_gc.finance_company,
        ($id_dok_pengajuan_gc) AS id_dok_pengajuan,
        ($tgl_pengajuan_gc) AS tgl_pengajuan, 
        ($dp_stor_gc) AS dp_stor,
        ($tenor_gc) AS tenor,
        ($angsuran_gc) AS  angsuran,
        pdr_gc.driver,
        dl_gc.kode_dealer_md,
        '' AS updated_at,
        so_gc.created_at AS created_at_so
        $select_gc 
      FROM tr_sales_order_gc_nosin so_gc_nosin
      JOIN tr_scan_barcode sc ON sc.no_mesin=so_gc_nosin.no_mesin
      JOIN tr_sales_order_gc so_gc ON so_gc.id_sales_order_gc=so_gc_nosin.id_sales_order_gc
      JOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc=so_gc.no_spk_gc
      JOIN ms_plat_dealer pdr_gc ON pdr_gc.id_master_plat=so_gc_nosin.id_master_plat
      LEFT JOIN ms_karyawan_dealer kd_s_gc ON kd_s_gc.id_karyawan_dealer=pdr_gc.id_karyawan_dealer
      LEFT JOIN ms_finance_company fn_gc ON fn_gc.id_finance_company=spk_gc.id_finance_company
      JOIN ms_dealer dl_gc ON dl_gc.id_dealer=so_gc.id_dealer
      $join_gc
      $where_gc
      $group_gc
    ) AS tabel
    $where
    $order
    $limit
    ");
  }

  function getFakturSTNK($filter)
  {

    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = dealer()->id_dealer;
    }
    $where   = "WHERE so.id_dealer='$id_dealer' ";
    // $where   = "WHERE 1=1 ";

    if (isset($filter['no_spk'])) {
      $where .= " AND (so.no_spk='{$filter['no_spk']}' OR so_gc_n.no_spk_gc='{$filter['no_spk']}')";
    }
    if (isset($filter['id_sales_order'])) {
      $where .= " AND so.id_sales_order='{$filter['id_sales_order']}'";
    }

    if (isset($filter['start']) && isset($filter['end'])) {
      // $where .= "AND LEFT(fs.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (prp.id_prospek LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_customer LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalDelivery') {
          $order_column = ['delivery_document_id', 'so.id_sales_order', 'spk.nama_konsumen', 'so.no_mesin', 'so.no_rangka', 'spk.id_tipe_kendaraan', 'spk.id_warna', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY spk.nama_konsumen ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $tgl_terima_stnk_konsumen = "SELECT tgl_terima_stnk FROM tr_tandaterima_stnk_konsumen tsk
    JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.kd_stnk_konsumen=tsk.kd_stnk_konsumen
    WHERE jenis_cetak='stnk' AND tskd.no_mesin=fsd.no_mesin
    ORDER BY tsk.created_at DESC LIMIT 1
    ";

    $tgl_terima_bpkb_konsumen = "SELECT tgl_terima_bpkb 
    FROM tr_tandaterima_stnk_konsumen tsk
    JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.kd_stnk_konsumen=tsk.kd_stnk_konsumen
    WHERE jenis_cetak='bpkb' AND tskd.no_mesin=fsd.no_mesin
    ORDER BY tsk.created_at DESC LIMIT 1
    ";
    $nama_penerima = "SELECT diterima FROM tr_tandaterima_stnk_konsumen tsk
    JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.kd_stnk_konsumen=tsk.kd_stnk_konsumen
    WHERE tskd.no_mesin=fsd.no_mesin
    ORDER BY tsk.created_at DESC LIMIT 1
    ";
    $jenis_id_penerima = "SELECT jenis_id FROM tr_tandaterima_stnk_konsumen tsk
    JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.kd_stnk_konsumen=tsk.kd_stnk_konsumen
    WHERE tskd.no_mesin=fsd.no_mesin
    ORDER BY tsk.created_at DESC LIMIT 1
    ";
    $no_id_penerima = "SELECT no_id FROM tr_tandaterima_stnk_konsumen tsk
    JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.kd_stnk_konsumen=tsk.kd_stnk_konsumen
    WHERE tskd.no_mesin=fsd.no_mesin
    ORDER BY tsk.created_at DESC LIMIT 1
    ";
    return $this->db->query("SELECT 
    fsd.no_mesin,
    CASE WHEN so.no_mesin IS NULL THEN so_gc_n.id_sales_order_gc ELSE so.id_sales_order END AS id_sales_order,
    CASE WHEN so.no_mesin IS NULL THEN so_gc.no_spk_gc ELSE so.no_spk END AS no_spk,
    fsd.no_bastd AS no_faktur_stnk,
    tgl_bastd AS tgl_pengajuan,
    fs.status_faktur AS status_faktur_stnk,
    bj.no_stnk,
    bj.tgl_terima_stnk,
    bj.no_plat,
    bj.tgl_terima_plat,
    bj.no_bpkb,
    bj.tgl_terima_bpkb,
    ($tgl_terima_stnk_konsumen) AS tgl_terima_stnk_konsumen,
    ($tgl_terima_bpkb_konsumen) AS tgl_terima_bpkb_konsumen,
    ($nama_penerima) AS nama_penerima,
    ($jenis_id_penerima) AS jenis_id_penerima,
    ($no_id_penerima) AS no_id_penerima,
    fs.created_at
    FROM tr_faktur_stnk_detail fsd
    JOIN tr_faktur_stnk fs ON fs.no_bastd=fsd.no_bastd
    LEFT JOIN tr_sales_order so ON so.no_mesin=fsd.no_mesin
    LEFT JOIN tr_sales_order_gc_nosin so_gc_n ON so_gc_n.no_mesin=fsd.no_mesin
    LEFT JOIN tr_sales_order_gc so_gc ON so_gc.id_sales_order_gc=so_gc_n.id_sales_order_gc
    -- JOIN tr_spk spk ON spk.no_spk=so.no_spk
    LEFT JOIN tr_terima_bj bj ON bj.no_mesin=fsd.no_mesin
    $where
    $order
    $limit
    ");
  }

  function getUnitInbound($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $where   = "WHERE sj.id_dealer='$id_dealer' ";
    // $where   = "WHERE 1=1 ";

    if (isset($filter['no_surat_jalan'])) {
      $where .= " AND sjd.no_surat_jalan='{$filter['no_surat_jalan']}'";
    }
    if (isset($filter['po_id'])) {
      $where .= " AND dpo.no_po='{$filter['po_id']}'";
    }

    if (isset($filter['start']) && isset($filter['end'])) {
      $where .= "AND LEFT(pu.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dpo.no_po LIKE '%$search%'
                            OR dpo.source LIKE '%$search%'
                            OR sj.tgl_surat LIKE '%$search%'
                            OR dpo.no_do LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalPO') {
          $order_column = ['id_po', 'dpo.source', 'pod.tahun', NULL];
        }
        if ($filter['order_column'] == 'modalSJ') {
          $order_column = ['sjd.no_surat_jalan', 'sj.tgl_surat', 'dpo.no_do', 'dpo.no_po', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY pu.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    // $where = '';
    $kelengkapan = "SELECT GROUP_CONCAT(ksu.ksu separator ',') 
            FROM tr_penerimaan_ksu_dealer ksud
            JOIN ms_ksu ksu ON ksu.id_ksu=ksud.id_ksu
            JOIN ms_item itm ON itm.id_item=ksud.id_item
            WHERE no_surat_jalan=sjd.no_surat_jalan 
              AND id_penerimaan_unit_dealer=pu.id_penerimaan_unit_dealer
              AND itm.id_tipe_kendaraan=sbc.tipe_motor
              AND itm.id_warna=sbc.warna
    ";
    return $this->db->query("SELECT sjd.no_surat_jalan,sjd.no_mesin,sbc.no_rangka,pud.jenis_pu AS status_rfs_nrfs,sj.id_dealer,pu.tgl_penerimaan,kode_dealer_md,sj.status,pl.no_do,idl.no_faktur,dpo.no_po,sbc.tipe_motor AS id_tipe_kendaraan,sbc.warna AS id_warna,id_goods_receipt,
    (SELECT dokumen_nrfs_id FROM tr_dokumen_nrfs WHERE no_mesin=sjd.no_mesin ORDER BY tgl_dokumen LIMIT 1) AS dokumen_nrfs_id, pu.created_at,($kelengkapan) AS kelengkapan,dpo.source,pod.tahun,pod.bulan,sj.tgl_surat,1 AS qty_terima,1 AS qty_kirim 
    FROM tr_surat_jalan_detail sjd
    JOIN tr_surat_jalan sj ON sj.no_surat_jalan=sjd.no_surat_jalan
    JOIN tr_penerimaan_unit_dealer_detail pud ON pud.no_mesin=sjd.no_mesin
    JOIN tr_penerimaan_unit_dealer pu ON pu.no_surat_jalan=sj.no_surat_jalan
    JOIN ms_dealer dl ON dl.id_dealer=sj.id_dealer
    JOIN tr_picking_list_view plv ON plv.no_mesin=sjd.no_mesin
    JOIN tr_picking_list pl ON pl.no_picking_list=plv.no_picking_list
    JOIN tr_invoice_dealer idl ON idl.no_do=pl.no_do
    JOIN tr_do_po dpo ON dpo.no_do=pl.no_do
    JOIN tr_po_dealer pod ON pod.id_po=dpo.no_po
    JOIN tr_scan_barcode sbc ON sbc.no_mesin=sjd.no_mesin
    $where
    $order
    $limit
    ");
  }
  function getPOPart($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $where   = "WHERE po.id_dealer='$id_dealer' ";
    $id_work_order = "SELECT id_work_order FROM tr_h2_wo_dealer_parts woprt WHERE id_booking=po.id_booking LIMIT 1";
    $join_part = '';
    $select = '';

    if (isset($filter['po_type'])) {
      if ($filter['po_type'] != '') {
        $where .= " AND po.po_type='{$filter['po_type']}'";
        if (strtolower($filter['po_type']) === 'hlo') {
          $where .= " AND po.id_booking IS NOT NULL";
        }
      }
    }
    if (isset($filter['po_id'])) {
      $where .= " AND po.po_id='{$filter['po_id']}'";
    }
    if (isset($filter['id_work_order'])) {
      $where .= " AND ($id_work_order)='{$filter['id_work_order']}'";
    }
    if (isset($filter['id_work_order'])) {
      $where .= " AND ($id_work_order)='{$filter['id_work_order']}'";
    }
    if (isset($filter['inv_jaminan_null'])) {
      $where .= " AND uj.no_inv_uang_jaminan IS NULL";
    }
    if (isset($filter['inv_jaminan_not_null'])) {
      $where .= " AND uj.no_inv_uang_jaminan IS NOT NULL";
    }

    if (isset($filter['start']) && isset($filter['end'])) {
      $where .= " AND LEFT(po.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (po.po_id LIKE '%$search%'
                            OR req.id_customer LIKE '%$search%'
                            OR ch23.nama_customer LIKE '%$search%'
                            OR uj.total_bayar LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalPOPart') {
          $order_column = ['po_id', 'tanggal_order', 'req.id_customer', 'ch23.nama_customer', NULL];
        } elseif ($filter['order_column'] == 'modalPODealerPart') {
          $order_column = ['po_id', 'po_type', 'tanggal_order', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY po.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['join_part'])) {
      $subtotal = "harga_saat_dibeli*kuantitas";
      $select = ",po_prt.id_part,nama_part,kuantitas,harga_saat_dibeli,($subtotal) AS subtotal";
      $join_part = "JOIN tr_h3_dealer_purchase_order_parts po_prt ON po_prt.po_id=po.po_id
                    JOIN ms_part prt ON po_prt.id_part=prt.id_part
      ";
    }
    return $this->db->query("SELECT po.po_id,po_type,tanggal_order,req.id_customer,nama_customer,uj.total_bayar AS uang_muka,no_inv_uang_jaminan,uj.sisa_bayar,($id_work_order) AS id_work_order,req.no_buku_khusus_claim_c2,ch23.no_identitas,ch23.alamat,prv.id_provinsi,kab.id_kabupaten,kec.id_kecamatan,ch23.id_kelurahan,kel.kode_pos,ch23.no_hp,ch23.id_tipe_kendaraan,ch23.tahun_produksi,ch23.no_mesin,ch23.no_rangka,req.flag_numbering,req.vor,req.job_return_flag,dl.kode_dealer_md, po.created_at,'' AS updated_at $select
    FROM tr_h3_dealer_purchase_order po
    LEFT JOIN tr_h3_dealer_request_document req ON req.id_booking=po.id_booking
    LEFT JOIN tr_h2_uang_jaminan uj ON uj.id_booking=req.id_booking
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=req.id_customer
    JOIN ms_dealer dl ON dl.id_dealer=po.id_dealer
    LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=ch23.id_kelurahan
    left JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
    left JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
    left JOIN ms_provinsi prv ON prv.id_provinsi=kab.id_provinsi
    $join_part
    $where
    $order
    $limit
    ");
  }
  function getPenerimaanPartDealer($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $where   = "WHERE pb.id_dealer='$id_dealer' ";

    if (isset($filter['po_id'])) {
      $where .= " AND po.po_id='{$filter['po_id']}'";
    }

    if (isset($filter['start']) && isset($filter['end'])) {
      $where .= " AND LEFT(pb.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (po.po_id LIKE '%$search%'
                            OR req.id_customer LIKE '%$search%'
                            OR ch23.nama_customer LIKE '%$search%'
                            OR uj.total_bayar LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalPOPart') {
          $order_column = ['po_id', 'tanggal_order', 'req.id_customer', 'ch23.nama_customer', NULL];
        } elseif ($filter['order_column'] == 'modalPODealerPart') {
          $order_column = ['po_id', 'po_type', 'tanggal_order', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY po.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT pb.id_penerimaan_barang,pb.tanggal,pb.id_surat_pengantar,kode_dealer_md,pb.created_at,po.po_id,po.po_type,pbi.id_gudang_good,id_rak_good,pbi.id_part,qty_ship,qty_good,st.satuan,pb.created_at,'' AS updated_at
    FROM tr_h3_dealer_penerimaan_barang pb
    JOIN tr_h3_dealer_penerimaan_barang_items pbi ON pbi.id_penerimaan_barang=pb.id_penerimaan_barang
    JOIN tr_h3_dealer_purchase_order po ON po.po_id=pb.nomor_po
    JOIN ms_dealer dl ON dl.id_dealer=pb.id_dealer
    LEFT JOIN ms_part prt ON prt.id_part=pbi.id_part
    LEFT JOIN ms_satuan st ON st.id_satuan=prt.id_satuan
    $where
    $order
    $limit
    ");
  }

  function getPartSales($filter)
  {

    $id_dealer = dealer()->id_dealer;
    $where   = "WHERE so.id_dealer='$id_dealer' ";
    $id_work_order = "SELECT id_work_order FROM tr_h2_wo_dealer_parts woprt WHERE id_booking=po.id_booking LIMIT 1";
    $join_part = '';
    $select = '';

    if (isset($filter['po_type'])) {
      $where .= " AND po.po_type='{$filter['po_type']}'";
      if (strtolower($filter['po_type']) === 'hlo') {
        $where .= " AND (req.id_booking IS NOT NULL)";
        // $where .= " AND (req.id_booking IS NOT NULL req.id_booking!='')";
      }
    }
    if (isset($filter['po_id'])) {
      if ($filter['po_id'] != '') {
        $where .= " AND po.po_id='{$filter['po_id']}'";
      }
    }
    if (isset($filter['id_work_order'])) {
      $where .= " AND ($id_work_order)='{$filter['id_work_order']}'";
    }
    if (isset($filter['inv_jaminan_null'])) {
      $where .= " AND uj.no_inv_uang_jaminan IS NULL";
    }
    if (isset($filter['inv_jaminan_not_null'])) {
      $where .= " AND uj.no_inv_uang_jaminan IS NOT NULL";
    }

    if (isset($filter['start']) && isset($filter['end'])) {
      $where .= " AND LEFT(so.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (po.po_id LIKE '%$search%'
                            OR req.id_customer LIKE '%$search%'
                            OR ch23.nama_customer LIKE '%$search%'
                            OR uj.total_bayar LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalPOPart') {
          $order_column = ['po_id', 'tanggal_order', 'req.id_customer', 'ch23.nama_customer', NULL];
        } elseif ($filter['order_column'] == 'modalPODealerPart') {
          $order_column = ['po_id', 'po_type', 'tanggal_order', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY so.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['join_part'])) {
      $subtotal = "harga_saat_dibeli*kuantitas";
      $total_so = 0;
      $select = ",so_part.id_part,kuantitas,harga_saat_dibeli,st.satuan,diskon_value,0 AS diskon_persen,0 AS ppn, ($subtotal) AS subtotal, so.uang_muka,so.booking_id_reference,$total_so total_so ";

      $join_part = "JOIN tr_h3_dealer_sales_order_parts so_part ON so_part.nomor_so=so.nomor_so
      JOIN ms_part prt ON so_part.id_part=prt.id_part
      JOIN ms_satuan st ON st.id_satuan=prt.id_satuan
      ";
    }
    return $this->db->query("SELECT so.nomor_so,so.tanggal_so,so.id_customer,nama_pembeli,0 AS diskon_so,kode_dealer_md,so.created_at,'' AS updated_at $select
    FROM tr_h3_dealer_sales_order so
    JOIN ms_dealer dl ON dl.id_dealer=so.id_dealer
    LEFT JOIN tr_h3_dealer_purchase_order po ON po.id_booking=so.booking_id_reference
    $join_part
    $where
    $order
    $limit
    ");
  }
  function generate_key($post)
  {
    $cek = 1;
    while ($cek > 0) {
      // Generate a random salt
      $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);
      if ($salt === FALSE) {
        $salt = hash('sha256', time() . mt_rand() . $post['id_dealer']);
      }
      $cek = $this->db->get_where('ms_dgi_api_key', ['api_key' => $salt, 'secret_key' => $salt])->num_rows();
      $result['api_key'] = substr($salt, 0, 25);
    }
    $cek = 1;
    while ($cek > 0) {
      // Generate a random salt
      $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);
      if ($salt === FALSE) {
        $salt = hash('sha256', time() . mt_rand() . $post['id_dealer']);
      }
      $cek = $this->db->get_where('ms_dgi_api_key', ['api_key' => $salt, 'secret_key' => $salt])->num_rows();
      $result['secret_key'] = substr($salt, 0, 25);
    }
    if (isset($result)) {
      return $result;
    }
  }

  function getAPIKey($filter)
  {
    if (isset($filter['id_dealer'])) {
      $where   = "WHERE dl.id_dealer='{$filter['id_dealer']}' ";
    } else {
      $where = 'WHERE 1=1 ';
    }

    if (isset($filter['active'])) {
      $where .= " AND api.active='{$filter['active']}'";
    }
    if (isset($filter['api_key'])) {
      $where .= " AND api.api_key='{$filter['api_key']}'";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $updated = sql_date_dmyhi('api.updated_at');
        $created = sql_date_dmyhi('api.created_at');
        $where .= " AND (api.api_key LIKE '%$search%'
                            OR api.id_dealer LIKE '%$search%'
                            OR api.secret_key LIKE '%$search%'
                            OR dl.nama_dealer LIKE '%$search%'
                            OR dl.kode_dealer_md LIKE '%$search%'
                            OR $updated LIKE '%$search%'
                            OR $created LIKE '%$search%'
                            OR api.active LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'viewMD') {
          $order_column = ['api.id_dealer', 'nama_dealer', 'api_key', 'secret_key', 'api.created_at', 'api.updated_at', 'api.active', NULL];
        }
        if ($filter['order_column'] == 'viewDealer') {
          $order_column = ['api.id_dealer', 'nama_dealer', 'api_key', 'secret_key', 'api.created_at', 'api.updated_at', 'api.active'];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY api.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $created_at = sql_date_dmyhi('api.created_at');
    $updated_at = sql_date_dmyhi('api.updated_at');
    return $this->db->query("SELECT api.id_dealer,nama_dealer,api_key,secret_key,api.active,$created_at AS created_at,$updated_at AS updated_at
    FROM ms_dgi_api_key api
    JOIN ms_dealer dl ON dl.kode_dealer_md=api.id_dealer
    $where
    $order
    $limit
    ");
  }
  function getActivityLog($filter)
  {

    if (isset($filter['id_dealer'])) {
      $where   = "WHERE dl.id_dealer='{$filter['id_dealer']}' ";
    } else {
      $where = 'WHERE 1=1 ';
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (po.po_id LIKE '%$search%'
                            OR req.id_customer LIKE '%$search%'
                            OR ch23.nama_customer LIKE '%$search%'
                            OR uj.total_bayar LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'viewMD') {
          $order_column = ['api.id_dealer', 'nama_dealer', 'api_key', 'secret_key', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY log.request_time DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT api.id_dealer,endpoint,request_time,ip_address,data_count,http_response_code,log.status,dl.nama_dealer,message
    FROM dgi_activity_log log
    LEFT JOIN ms_dgi_api_key api ON api.api_key=log.api_key
    LEFT JOIN ms_dealer dl ON dl.kode_dealer_md=api.id_dealer
    $where
    $order
    $limit
    ");
  }
  function getTeamSales($filter)
  {

    // send_json($filter);
    if (isset($filter['id_dealer'])) {
      $where   = "WHERE tm.id_dealer='{$filter['id_dealer']}' ";
    } else {
      $where = 'WHERE 1=1 ';
    }

    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND tm.active={$filter['active']} ";
      }
    }

    if (isset($filter['team_not_in_team_structure'])) {
      if ($filter['team_not_in_team_structure'] != '') {
        $where .= "AND NOT EXISTS(SELECT id_team FROM dms_team_structure_management tsmd WHERE tsmd.id_team=tm.id_team AND tsmd.id_dealer=tm.id_dealer) ";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tm.id_team LIKE '%$search%'
                            OR tm.nama_team LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['tm.id_team', 'nama_team', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY tm.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_team,nama_team,tm.active
    FROM dms_ms_team tm
    $where
    $order
    $limit
    ");
  }
}
