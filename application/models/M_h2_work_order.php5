<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_work_order extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    $this->load->model('m_h2_master', 'mh2');
  }

  function get_sj_keluar($filter = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 AND wo.id_dealer='$id_dealer'";
    if ($filter != null) {
      if (isset($filter['id_surat_jalan'])) {
        $where .= " AND sj.id_surat_jalan='{$filter['id_surat_jalan']}'";
      }
      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND (sj.id_surat_jalan LIKE '%$search%'
                            OR sj.tgl_surat_jalan LIKE '%$search%'
                            OR sj.id_work_order LIKE '%$search%'
                            OR sj.id_vendor LIKE '%$search%'
                            OR vdr.vendor LIKE '%$search%'
                            OR cus.id_customer LIKE '%$search%'
                            OR cus.nama_customer LIKE '%$search%'
                            OR sj.dibawa_oleh LIKE '%$search%'
                            ) 
                ";
        }
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          if ($filter['order_column'] == 'view') {
            $order_column = ['id_surat_jalan', 'tgl_surat_jalan', 'sj.id_work_order', 'sj.id_vendor', 'vdr.nama_vendor', 'cus.nama_customer', 'sj.dibawa_oleh'];
          }
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY sj.created_at DESC ";
        }
      } else {
        $order = " ORDER BY sj.created_at DESC ";
      }

      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT id_surat_jalan,sj.id_work_order,tgl_surat_jalan,sj.id_vendor,alasan,dibawa_oleh,vdr.nama_vendor,nama_customer,wo.id_sa_form,no_polisi,cus.no_mesin,cus.no_rangka
    FROM tr_h2_wo_dealer_surat_jalan_keluar sj
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=sj.id_work_order
    JOIN ms_h2_vendor_pekerjaan_luar vdr ON vdr.id_vendor=sj.id_vendor
    JOIN tr_h2_sa_form sa_f ON sa_f.id_sa_form=wo.id_sa_form
    JOIN ms_customer_h23 cus ON cus.id_customer=sa_f.id_customer
    $where
    ");
  }

  function get_sj_keluar_pekerjaans($filter = null)
  {

    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_surat_jalan'])) {
        $where .= " AND wop.id_surat_jalan='{$filter['id_surat_jalan']}'";
      }
    }
    return $this->db->query("SELECT id_surat_jalan,wop.id_jasa,wop.harga,wop.harga_dari_vendor,js.deskripsi
    FROM tr_h2_wo_dealer_pekerjaan wop
    JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
    $where
    ");
  }

  function get_sj_keluar_parts_related($filter = null)
  {

    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_surat_jalan'])) {
        $where .= " AND sjpr.id_surat_jalan='{$filter['id_surat_jalan']}'";
      }
    }
    return $this->db->query("SELECT id_surat_jalan,sjpr.id_part,prt.nama_part,sjpr.qty
    FROM tr_h2_wo_dealer_surat_jalan_keluar_part_related sjpr
    JOIN ms_part prt ON prt.id_part=sjpr.id_part
    $where
    ");
  }

  function get_sa_form($filter)
  {
    $id_dealer = '';
    $order_column = array('sa_form.id_sa_form', 'id_antrian', 'sa_form.tgl_servis', 'sa_form.jenis_customer', 'cus.no_polisi', 'cus.nama_customer', 'cus.no_mesin', 'cus.no_rangka', 'tk.id_tipe_kendaraan', 'cus.id_warna', 'cus.tahun_produksi', null);

    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      if (isset($filter['skip_dealer'])) {
        $id_dealer = '';
      } else {
        $id_dealer = $this->m_admin->cari_dealer();
      }
    }
    if (isset($filter['skip_dealer'])) {
      $id_dealer = '';
    }

    if ($id_dealer == '') {
      echo "<script>alert('id dealer tidak boleh kosong, silahkan hubungi MD')</script>";
      die();
    }
    $dealer = '';
    if ($id_dealer != '') {
      $dealer = " AND sa_form.id_dealer='$id_dealer'";
    }
    $where = "WHERE 1=1 $dealer";

    if ($filter == null) {
      $where = "WHERE 1=0";
    }

    $order = "ORDER BY sa_form.created_at DESC ";
    $limit = '';
    if ($filter != null) {
      if (isset($filter['id_antrian'])) {
        $where .= " AND sa_form.id_antrian='{$filter['id_antrian']}'";
      }

      if (isset($filter['id_customer'])) {
        $where .= " AND sa_form.id_customer='{$filter['id_customer']}'";
      }
      if (isset($filter['id_pit'])) {
        $where .= " AND sa_form.id_pit='{$filter['id_pit']}'";
      }
      if (isset($filter['id_rekap_kpb'])) {
        $where .= " AND wod.id_rekap_kpb='{$filter['id_rekap_kpb']}'";
      }
      if (isset($filter['except_wo'])) {
        $where .= " AND wod.id_work_order!='{$filter['except_wo']}'";
      }
      if (isset($filter['id_sa_form'])) {
        $where .= " AND sa_form.id_sa_form='{$filter['id_sa_form']}'";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND wod.id_work_order='{$filter['id_work_order']}'";
      }
      if (isset($filter['id_work_order_int'])) {
        $where .= " AND wod.id_work_order_int='{$filter['id_work_order_int']}'";
      }
      if (isset($filter['id_karyawan_dealer'])) {
        $where .= " AND wod.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
      }
      if (isset($filter['start_at_null'])) {
        $where .= " AND wod.start_at is null";
      }
      if (isset($filter['tgl_servis'])) {
        $where .= " AND sa_form.tgl_servis='{$filter['tgl_servis']}'";
      }
      if (isset($filter['tipe_pembayaran'])) {
        $where .= " AND wod.tipe_pembayaran='{$filter['tipe_pembayaran']}'";
      }
      if (isset($filter['tgl_servis_lebih_kecil'])) {
        $where .= " AND sa_form.tgl_servis < '{$filter['tgl_servis_lebih_kecil']}'";
      }
      if (isset($filter['tgl_servis_lebih_kecil_sama'])) {
        $where .= " AND sa_form.tgl_servis <= '{$filter['tgl_servis_lebih_kecil_sama']}'";
      }
      if (isset($filter['id_sa_form_not_null'])) {
        $where .= " AND sa_form.id_sa_form IS NOT NULL";
      }
      if (isset($filter['id_work_order_not_null'])) {
        $where .= " AND wod.id_work_order IS NOT NULL";
        $order = "ORDER BY wod.created_at DESC ";
      }
      if (isset($filter['id_karyawan_dealer_not_null'])) {
        $where .= " AND (wod.id_karyawan_dealer !='')";
      }
      if (isset($filter['status_wo_not'])) {
        $where .= " AND wod.status!='{$filter['status_wo_not']}'";
      }
      if (isset($filter['status_form'])) {
        // $where .= " AND sa_form.status_form='{$filter['status_form']}'";
        $status_form = arr_in_sql($filter['status_form']);
        $where .= " AND sa_form.status_form IN($status_form) ";
      }
      if (isset($filter['status_wo'])) {
        $status_wo = arr_in_sql($filter['status_wo']);
        $where .= " AND wod.status IN($status_wo) ";
      }
      if (isset($filter['status_form_not'])) {
        $where .= " AND sa_form.status_form!='{$filter['status_form_not']}'";
      }
      if (isset($filter['njb_or_nsc_not_null'])) {
        $where .= " AND (wod.no_njb IS NOT NULL OR wod.no_nsc IS NOT NULL)";
      }
      if (isset($filter['njb_not_null'])) {
        $where .= " AND (wod.no_njb IS NOT NULL)";
        $order = "ORDER BY wod.created_njb_at DESC ";
      }
      if (isset($filter['njb_null'])) {
        $where .= " AND wod.no_njb IS NULL";
      }
      if (isset($filter['no_njb'])) {
        $where .= " AND wod.no_njb='" . $filter['no_njb'] . "'";
      }
      if (isset($filter['tgl_njb'])) {
        $where .= " AND LEFT(wod.created_njb_at,10)='" . $filter['tgl_njb'] . "'";
      }
      if (isset($filter['no_nsc'])) {
        $where .= " AND wod.no_nsc='" . $filter['no_nsc'] . "'";
      }
      if (isset($filter['tgl_servis'])) {
        $where .= " AND sa_form.tgl_servis='" . $filter['tgl_servis'] . "'";
      }
      if (isset($filter['status_wo_in'])) {
        $where .= " AND wod.status IN ({$filter['status_wo_in']})";
      }
      if (isset($filter['level_satisfaction_null'])) {
        $where .= " AND NOT EXISTS(SELECT id_referensi FROM tr_h2_service_satisfaction WHERE id_referensi=wod.id_work_order) ";
      }
      if (isset($filter['not_in_rekap'])) {
        $where .= " AND NOT EXISTS(SELECT id_rekap_kpb FROM tr_h2_dealer_rekap_kpb WHERE id_rekap_kpb=wod.id_rekap_kpb) ";
      }

      if (isset($filter['filter_created_wo'])) {
        $where .= " AND LEFT(wod.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }
      if (isset($filter['periode_servis'])) {
        $where .= " AND sa_form.tgl_servis BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }
      if (isset($filter['id_type_wo_in'])) {
        $where .= " AND (SELECT COUNT(id_type)
                    FROM tr_h2_wo_dealer_pekerjaan wdp
                    JOIN ms_h2_jasa js ON js.id_jasa=wdp.id_jasa
                    WHERE wdp.id_work_order=wod.id_work_order AND id_type IN ({$filter['id_type_wo_in']})
                    )>0
                  ";
      }
      if (isset($filter['tahun_bulan_wo'])) {
        $where .= " AND LEFT(wod.created_at,7)='{$filter['tahun_bulan_wo']}'";
      }
      if (isset($filter['tahun_bulan_njb'])) {
        $where .= " AND LEFT(wod.created_njb_at,7)='{$filter['tahun_bulan_njb']}'";
      }
      if (isset($filter['tahun_bulan_sa'])) {
        $where .= " AND LEFT(sa_form.created_sa_form_at,7)='{$filter['tahun_bulan_sa']}'";
      }
      if (isset($filter['last_stats'])) {
        $where .= " AND (SELECT stats FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wod.id_work_order ORDER BY set_at DESC LIMIT 1)='{$filter['last_stats']}'";
      }
      if (isset($filter['last_stats_not'])) {
        $where .= " AND (SELECT count(id) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wod.id_work_order AND stats='{$filter['last_stats_not']}' ORDER BY id DESC LIMIT 1)=0";
      }
      if (isset($filter['no_mesin'])) {
        $where .= " AND cus.no_mesin='{$filter['no_mesin']}'";
      }
      if (isset($filter['srbu'])) {
        $where .= " AND sa_form.srbu='{$filter['srbu']}'";
      }
      if (isset($filter['id_pit_not_null'])) {
        $where .= " AND sa_form.id_pit IS NOT NULL";
      }
      if (isset($filter['mekanik_not_null'])) {
        $where .= " AND sa_form.id_karyawan_dealer !=0";
      }
      if (isset($filter['njb_in_receipt'])) {
        $where .= " AND no_njb IN(SELECT no_njb FROM tr_h2_receipt_customer_transaksi WHERE id_referensi=no_njb)";
      }

      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND (cus.nama_customer LIKE '%$search%'
                                OR cus.id_customer LIKE '%$search%'
                                OR cus.no_mesin LIKE '%$search%'
                                OR cus.no_rangka LIKE '%$search%'
                                OR tk.tipe_ahm LIKE '%$search%'
                                OR warna LIKE '%$search%'
                                OR sa_form.id_sa_form LIKE '%$search%'
                                OR sa_form.jenis_customer LIKE '%$search%'
                                OR wod.id_work_order LIKE '%$search%'
                                OR sa_form.tgl_servis LIKE '%$search%'
                                OR sa_form.jam_servis LIKE '%$search%'
                                ) 
                ";
        }
      }
      $group_by = "";
      if (isset($filter['group_by_no_mesin_id_dealer'])) {
        $group_by .= "GROUP BY cus.no_mesin, wod.id_dealer";
      }
      if (isset($filter['order'])) {
        if (isset($filter['order_column'])) {
          if ($filter['order_column'] == 'history') {
            $order_column = ['id_work_order', 'sa_form.id_sa_form', 'sa_form.tgl_servis', 'sa_form.jenis_customer', 'cus.no_polisi', 'cus.nama_customer', 'cus.no_mesin', 'cus.no_rangka', 'tk.tipe_ahm', 'wr.warna', 'cus.tahun_produksi', 'wod.status', NULL];
          }
        }
        if ($filter['order'] != '') {
          if ($filter['order'] == 'order_jam_asc') {
            $order = "ORDER BY sa_form.tgl_servis ASC";
          } else {
            $order = $filter['order'];
            $order_clm  = $order_column[$order[0]['column']];
            $order_by   = $order[0]['dir'];
            $order = " ORDER BY $order_clm $order_by ";
          }
        } else {
          $order = " ORDER BY sa_form.created_at DESC ";
        }
      } else {
        $order = " ORDER BY sa_form.created_at DESC ";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
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
    // $diskon_pekerjaan = "CASE 
    // WHEN wop.id_promo IS NOT NULL THEN
    //  CASE 
    //    WHEN prj.tipe_diskon='rupiah' THEN prj.diskon
    //    ELSE
    //     CASE 
    //       WHEN wod.pkp_njb=1 THEN  (wop.harga/1.1) * (prj.diskon/100)
    //       ELSE wop.harga * (prj.diskon/100)
    //     END
    //   END
    // ELSE 0
    // END";
    $diskon_pekerjaan = "IFNULL((wop.harga * (prj.diskon/100)),0)";
    $tot_njb = "IFNULL(SUM(wop.harga-$diskon_pekerjaan),0)";

    $potongan_part = "
    (CASE 
      WHEN tipe_diskon='Value' THEN diskon_value
      ELSE 0
     END
    )
    ";
    $harga = "
      (CASE 
        WHEN sa_form.pkp=1 THEN 
          CASE 
            WHEN tipe_diskon='Percentage' THEN harga - ((harga)*(diskon_value/100))
            ELSE harga
          END
        ELSE harga
        END
      )
    ";
    $qty = "
      (CASE 
        WHEN tipe_diskon='FoC' THEN qty-diskon_value
        ELSE qty
       END
      )
    ";
    $tot_part = "(IFNULL(SUM(($harga*$qty)-$potongan_part),0))";
    $tot_part_ppn = "
        SELECT CASE 
            WHEN sa_form.pkp=1 THEN ($tot_part+($tot_part*0.1))
            ELSE $tot_part
          END
        FROM tr_h2_wo_dealer_parts woprt
        WHERE woprt.id_work_order=wod.id_work_order
    ";

    $where_pekerjaan = '';
    if (isset($filter['id_type_wo_in'])) {
      $where_pekerjaan = "AND jstp.id_type IN ({$filter['id_type_wo_in']})";
    }
    $tot_pekerjaan = "SELECT $tot_njb 
    FROM tr_h2_wo_dealer_pekerjaan AS wop
    JOIN ms_h2_jasa jstp ON jstp.id_jasa=wop.id_jasa
    LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wop.id_promo AND prj.id_jasa=wop.id_jasa
    LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
    WHERE wop.id_work_order=wod.id_work_order $where_pekerjaan";

    $cek_km = "CASE WHEN (SELECT COUNT(id_customer) FROM tr_h2_sa_form sa_c WHERE sa_c.id_customer=sa_h.id_customer)>1 THEN sa_h.km_terakhir ELSE 0 END";
    $km_sebelumnya = "SELECT $cek_km AS km_sebelumnya FROM tr_h2_sa_form sa_h JOIN tr_h2_wo_dealer wo_h ON wo_h.id_sa_form=sa_h.id_sa_form WHERE sa_h.id_customer=sa_form.id_customer AND wo_h.no_njb IS NOT NULL ORDER BY created_njb_at DESC LIMIT 1";

    $id_antrian_short = "REPLACE(RIGHT(sa_form.id_antrian,5),'/','')";

    $select = "id_antrian,
    sa_form.id_sa_form,sa_form.id_customer,jenis_customer,sa_form.id_dealer,
    DATE_FORMAT(sa_form.tgl_servis,'%d/%m/%Y') AS tgl_servis,
    sa_form.jam_servis,status_form, REPLACE(nama_customer,'\'','`') AS nama_customer,
    cus.jenis_kelamin,cus.jenis_identitas,cus.no_identitas,REPLACE(cus.alamat_identitas,'\'','`') AS alamat_identitas,REPLACE(cus.alamat,'\'','`') AS alamat,ms_kelurahan.kelurahan,ms_kelurahan.id_kelurahan,ms_kelurahan.kode_pos,
    ms_kecamatan.kecamatan,ms_kecamatan.id_kecamatan,
    ms_kabupaten.kabupaten,ms_kabupaten.id_kabupaten,
    ms_provinsi.provinsi,ms_provinsi.id_provinsi,
    cus.no_hp,cus.email,no_polisi,cus.id_tipe_kendaraan,
    cus.no_mesin,cus.no_rangka,cus.tahun_produksi,tipe_ahm,cetak_sa_form_ke,warna,
    tipe_coming,informasi_bensin,alasan_ke_ahass,asal_unit_entry,km_terakhir,
    keluhan_konsumen,rekomendasi_sa,wod.id_work_order,pit_sa.id_pit,pit_sa.jenis_pit,
  wod.id_karyawan_dealer,mkd.nama_lengkap,wod.status AS status_wo,wod.start_at,
    (SELECT IFNULL(SUM(waktu),0)*60 FROM tr_h2_wo_dealer_pekerjaan WHERE id_work_order=wod.id_work_order) AS etr,
    (SELECT IFNULL(SUM(waktu),0) FROM tr_h2_sa_form_pekerjaan WHERE id_sa_form=sa_form.id_sa_form) AS estimasi_waktu_kerja,
    (SELECT IFNULL(SUM(detik),0) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wod.id_work_order) AS total_waktu,
    (SELECT stats FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wod.id_work_order ORDER BY set_at DESC LIMIT 1) AS last_stats,nama_dealer,kode_dealer_md,
    wod.start_at,wod.no_njb,DATE_FORMAT(LEFT(created_njb_at,10),'%d/%m/%Y') AS tgl_njb,wod.no_nsc,sa_form.id_pembawa, nama_pemakai,sa_form.tipe_pembayaran,no_buku_claim_c2,catatan_tambahan,konfirmasi_pekerjaan_tambahan,no_claim_c2,cus.tgl_pembelian,
    job_return,cus.id_dealer_h1,
  CONCAT(DATE_FORMAT(LEFT(wod.waktu_njb,10),'%d/%m/%Y'),' ',RIGHT(wod.waktu_njb,8)) AS waktu_njb,cetak_njb_ke,id_wo_job_return,
    sa_form.created_at AS estimasi_waktu_daftar,cus.id_dealer_h1,
    motor_ditinggal,
    CASE WHEN sa_form.pkp=1 THEN 1 ELSE 0 END AS pkp,pkp_njb,keterangan_tambahan,cetak_gab_ke,sa_form.id_type,wod.created_at AS waktu_pkb,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN cus.nama_customer
      ELSE pbw.nama
    END AS nama_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN cus.no_hp
      ELSE pbw.no_hp
    END AS no_hp_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN cus.alamat
      ELSE pbw.alamat_saat_ini
    END AS alamat_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN cus.id_kelurahan
      ELSE pbw.id_kelurahan
    END AS id_kel_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ms_kecamatan.id_kecamatan
      ELSE kec_pbw.id_kecamatan
    END AS id_kec_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ms_kabupaten.id_kabupaten
      ELSE kab_pbw.id_kabupaten
    END AS id_kab_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ms_provinsi.id_provinsi
      ELSE prov_pbw.id_provinsi
    END AS id_prov_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ms_kelurahan.kode_pos
      ELSE kel_pbw.kode_pos
    END AS kodepos_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ms_kelurahan.kelurahan
      ELSE kel_pbw.kelurahan
    END AS kelurahan_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ms_kecamatan.kecamatan
      ELSE kec_pbw.kecamatan
    END AS kecamatan_pembawa,
    CASE 
      WHEN pbw.id_pembawa IS NULL THEN ''
      ELSE hubungan_dengan_pemilik
    END AS hubungan_dengan_pemilik,
    sa_form.created_by,wod.id_karyawan_dealer AS id_mekanik,grand_total,cus.tgl_lahir,cus.id_pekerjaan,mkd.nama_lengkap AS mekanik,
    CASE WHEN mkd.honda_id IS NULL THEN mkd.id_flp_md ELSE mkd.honda_id END AS honda_id_mekanik,
    mdl.kode_dealer_md,wod.updated_at AS updated_at_wo,ROUND(($tot_pekerjaan)) AS tot_pekerjaan,cus.jangka_waktu_top,wod.tgl_jatuh_tempo,tk.kode_ptm,sa_form.id_antrian_int,cus.id_customer_int,sa_form.id_booking,cus.facebook facebook_cus,cus.twitter twitter_cus,cus.instagram instagram_cus,activity_capacity_id,activity_promotion_id,id_work_order_int,LEFT(wod.created_at,10) tgl_wo,wod.document,wod.saran_mekanik,sa_form.assigned_at,wod.created_by AS id_user_sa,vehicle_offroad,waktu_kedatangan,($km_sebelumnya) AS km_sebelumnya,DATE_FORMAT(LEFT(sa_form.tgl_servis,10),'%d/%m/%Y') AS tgl_servis_indo,DATE_FORMAT(LEFT(cus.tgl_pembelian,10),'%d/%m/%Y') AS tgl_pembelian_indo,$id_antrian_short id_antrian_short,ms_warna.warna only_warna,sa_form.activity_promotion_id,sa_form.activity_capacity_id,wod.id_sa_form_int ";

    //Cek Total Oli
    $f_oli = [
      'sql_id_work_order' => 'wod.id_work_order',
      'kelompok_part_in' => "'OIL'",
      'sum_total' => true,
      'sql' => true,
      'get_only_grand' => true
    ];
    $tot_oli = $this->m_bil->getNSCParts($f_oli);

    if (isset($filter['select'])) {
      if (in_array('concat_tipe_pekerjaan', $filter['select'])) {
        $concat_tipe_pekerjaan = "SELECT GROUP_CONCAT(jst.deskripsi SEPARATOR ', ') 
        FROM tr_h2_wo_dealer_pekerjaan wop_concat
        JOIN ms_h2_jasa js ON js.id_jasa=wop_concat.id_jasa
        JOIN ms_h2_jasa_type jst ON jst.id_type=js.id_type
        WHERE id_work_order=wod.id_work_order
        ";
        $select .= " ,($concat_tipe_pekerjaan) AS concat_tipe_pekerjaan";
      } elseif (in_array('select_performance_ahass', $filter['select'])) {

        unset($filter['kelompok_part_in']);
        // $filter['kelompok_part_not_in'] = "'OLI','OIL'";
        // $tot_part = $this->m_bil->getNSCParts($filter);

        $filter = [
          'sql' => true,
          'sql_no_mesin' => "cus.no_mesin",
          'id_type_in' => "'ASS1','ASS2','ASS3','ASS4'",
        ];
        $qty_ass = $this->m_lap->getUnitEntri($filter);

        $filter = [
          'sql' => true,
          'sql_no_mesin' => "cus.no_mesin",
          'id_type_in' => "'ASS1','ASS2','ASS3','ASS4'",
          'sum_total' => true,
        ];
        $pendapatan_kpb = $this->m_bil->getNJB($filter);
        $filter = [
          'sql' => true,
          'sql_no_mesin' => "cus.no_mesin",
          'id_type_not_in' => "'ASS1','ASS2','ASS3','ASS4'",
          'sum_total' => true,
        ];
        $pendapatan_pl_pr_or = $this->m_bil->getNJB($filter);
        // $pendapatan_kpb = 0;
        // $tot_part = 0;
        // $tot_oli = 0;
        // $qty_ass = 0;

        $select = "mdl.id_dealer,mdl.kode_dealer_md,
        mdl.nama_dealer,
        cus.no_mesin,
        LEFT(wod.created_at,4) AS tahun,
        MID(wod.created_at,6,2) AS bulan,
        COUNT(cus.no_mesin) tot_kunjungan,
        ROUND(IFNULL(($pendapatan_kpb),0)) AS pendapatan_kpb,
        ($pendapatan_pl_pr_or) AS pendapatan_pl_pr_or,
        ($tot_part) AS tot_part,
        ($tot_oli) AS tot_oli,
        ($qty_ass) AS qty_ass
        ";
      } elseif (in_array('count_data', $filter['select'])) {
        $select = "COUNT(wod.id_work_order) AS count";
      } elseif (in_array('count_sum_grand_data', $filter['select'])) {
        $select = "COUNT(wod.id_work_order) AS count,SUM(IFNULL(grand_total,0)) AS total";
      } elseif (in_array('count_sum_jasa_data', $filter['select'])) {
        $select = "COUNT(wod.id_work_order) AS count,SUM(IFNULL(total_jasa,0)) AS total_jasa";
      } elseif (in_array('count_sa', $filter['select'])) {
        $select = "COUNT(sa_form.id_sa_form) AS count_sa";
      } elseif (in_array('sum_waktu_estimasi_aktual', $filter['select'])) {
        $select = "SUM((SELECT IFNULL(SUM(waktu),0) FROM tr_h2_wo_dealer_pekerjaan WHERE id_work_order=wod.id_work_order)) AS etr,
        SUM((SELECT IFNULL(CEIL(SUM(detik/60)),0) FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wod.id_work_order)) AS actual";
      }
    }

    if (isset($filter['select_add'])) {
      $kpb_ke = "SELECT RIGHT(id_type,1)
      FROM tr_h2_wo_dealer_pekerjaan wdp
      JOIN ms_h2_jasa js ON js.id_jasa=wdp.id_jasa
      WHERE wdp.id_work_order=wod.id_work_order AND id_type IN('ASS1','ASS2','ASS3','ASS4') LIMIT 1";
      $tot_qty_oli = "SELECT SUM(qty) 
        FROM tr_h2_wo_dealer_parts wdparts
        JOIN ms_part prt ON prt.id_part=wdparts.id_part
        WHERE wdparts.id_work_order=wod.id_work_order 
        AND kelompok_part IN(SELECT id_kelompok_part FROM ms_h3_md_setting_kelompok_produk WHERE produk='Oil')
      ";
      $jml_oli = "($tot_oli)";
      $checked = 0;
      foreach ($filter['select_add'] as $val) {
        $field = $$val;
        $select .= ", IFNULL(($field),0) AS $val";
      }
    }

    return $this->db->query("SELECT $select
        FROM tr_h2_sa_form AS sa_form
        JOIN ms_dealer AS mdl ON mdl.id_dealer=sa_form.id_dealer
        LEFT JOIN tr_h2_wo_dealer AS wod ON wod.id_sa_form_int=sa_form.id_antrian_int
        LEFT JOIN ms_customer_h23 AS cus ON cus.id_customer=sa_form.id_customer
        LEFT JOIN ms_tipe_kendaraan AS tk ON cus.id_tipe_kendaraan=tk.id_tipe_kendaraan
        LEFT JOIN ms_warna ON cus.id_warna=ms_warna.id_warna
        LEFT JOIN ms_kelurahan ON cus.id_kelurahan=ms_kelurahan.id_kelurahan
        LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan=ms_kecamatan.id_kecamatan
        LEFT JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten=ms_kabupaten.id_kabupaten
        LEFT JOIN ms_provinsi ON ms_kabupaten.id_provinsi=ms_provinsi.id_provinsi
        LEFT JOIN ms_h2_pit AS pit_sa ON pit_sa.id_pit=sa_form.id_pit AND pit_sa.id_dealer=sa_form.id_dealer
        LEFT JOIN ms_karyawan_dealer AS mkd ON mkd.id_karyawan_dealer=wod.id_karyawan_dealer
        LEFT JOIN ms_h2_pembawa pbw ON pbw.id_pembawa=sa_form.id_pembawa
        LEFT JOIN ms_kelurahan kel_pbw ON pbw.id_kelurahan=kel_pbw.id_kelurahan
        LEFT JOIN ms_kecamatan kec_pbw ON kec_pbw.id_kecamatan=kel_pbw.id_kecamatan
        LEFT JOIN ms_kabupaten kab_pbw ON kab_pbw.id_kabupaten=kec_pbw.id_kabupaten
        LEFT JOIN ms_provinsi prov_pbw ON prov_pbw.id_provinsi=kab_pbw.id_provinsi
        LEFT JOIN dms_ms_activity_capacity act_cap ON act_cap.id=sa_form.activity_capacity_id
        LEFT JOIN dms_ms_activity_promotion act_promotion ON act_promotion.id=sa_form.activity_promotion_id
        $where 
        $group_by
        $order
        $limit
        ");
  }

  public function get_id_sa_form($id_dealer = NULL)
  {
    $th        = date('y');
    $thn        = date('Y');
    $bln       = date('m');
    $tgl       = date('Y-m-d');
    $tgl_bln       = date('Y-m');
    $thbln     = date('ymd');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT * FROM tr_h2_sa_form
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,4)='$thn'
			AND id_sa_form IS NOT NULL
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      if ((int)$bln == 5 && $th == '21') {
        $last_number = substr($row->id_sa_form, -4);
        $new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/' . sprintf("%'.04d", $last_number + 1);
      } else {
        $last_number = substr($row->id_sa_form, -5);
        $new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/' . sprintf("%'.05d", $last_number + 1);
      }
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_sa_form', ['id_sa_form' => $new_kode])->num_rows();
        if ($cek > 0) {
          if ((int)$bln == 5 && $th == '21') {
            $gen_number    = substr($new_kode, -4);
            $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/' . sprintf("%'.04d", $gen_number + 1);
          } else {
            $gen_number    = substr($new_kode, -5);
            $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/' . sprintf("%'.05d", $gen_number + 1);
          }
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/SA-FORM/00001';
    }
    return strtoupper($new_kode);
  }

  public function get_id_work_order($id_dealer = NULL)
  {
    $thbln     = date('ymd');
    $tahun     = date('Y');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT * FROM tr_h2_wo_dealer
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,4)='$tahun'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->id_work_order, -5);
      $new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/WO/' . sprintf("%'.05d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_wo_dealer', ['id_work_order' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -5);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/WO/' . sprintf("%'.05d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/WO/00001';
    }
    return strtoupper($new_kode);
  }

  function sa_form_detail($filter = null)
  {
    $id_sa_form = '';
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer  = $this->m_admin->cari_dealer();
    }

    $where = "WHERE tr_h2_sa_form.id_dealer='$id_dealer' ";

    if ($filter != null) {
      if (isset($filter['id_sa_form'])) {
        $id_sa_form = $filter['id_sa_form'];
        $where .= "AND sa_form_pk.id_sa_form='" . $id_sa_form . "'";
      }
    }
    $details = $this->db->query("SELECT sa_form_pk.id_sa_form,kategori,ms_h2_jasa.id_type AS job_type,ms_h2_jasa.id_type,sa_form_pk.tipe_motor,sa_form_pk.id_jasa AS pekerjaan,sa_form_pk.harga,sa_form_pk.waktu,sa_form_pk.id_jasa,ms_h2_jasa.id_jasa AS jasa,ms_h2_jasa.deskripsi,
        CASE WHEN need_parts='y' OR need_parts=1
          THEN 'yes' 
          ELSE 'no' 
        END AS need_parts, '' AS masukkan_wo,kategori,
        CASE 
          WHEN sa_form_pk.id_promo IS NULL THEN ''
          ELSE sa_form_pk.id_promo
        END AS id_promo,
        pr.nama_promo,prj.tipe_diskon,prj.diskon,sa_form_pk.pekerjaan_luar,jst.deskripsi AS desk_type,sa_form_pk.id_tipe_servis,tipe_servis,labour_cost,frt_claim
        FROM tr_h2_sa_form_pekerjaan AS sa_form_pk
        JOIN tr_h2_sa_form ON tr_h2_sa_form.id_sa_form=sa_form_pk.id_sa_form
        JOIN ms_h2_jasa ON ms_h2_jasa.id_jasa=sa_form_pk.id_jasa
        LEFT JOIN ms_h2_jasa_type jst ON jst.id_type=ms_h2_jasa.id_type
        LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=sa_form_pk.id_promo AND prj.id_jasa=sa_form_pk.id_jasa
        LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
        LEFT JOIN setup_h2_tipe_servis sts ON sts.id=sa_form_pk.id_tipe_servis
        -- JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=ms_h2_jasa.id_type
        $where
      ");
    foreach ($details->result() as $rs) {
      $rs->parts = $this->db->query("SELECT tr_h2_sa_form_parts.*,  
      CASE 
        WHEN jenis_order ='HLO' THEN
          CASE WHEN dl.id_dealer IS NOT NULL THEN nama_dealer
          ELSE 'MD'
          END
      END order_to_name,
      nama_part,tr_h2_sa_form_parts.harga AS harga_dealer_user ,satuan
          FROM tr_h2_sa_form_parts 
          JOIN ms_part ON ms_part.id_part=tr_h2_sa_form_parts.id_part
          LEFT JOIN ms_satuan st ON st.id_satuan=ms_part.id_satuan
          LEFT JOIN ms_dealer dl ON dl.id_dealer=tr_h2_sa_form_parts.order_to
          WHERE id_sa_form='$id_sa_form' AND id_jasa='$rs->id_jasa' ORDER BY part_utama DESC")->result();
      $rs->parts_demand = [];
      $dt_details[] = $rs;
    }
    return $dt_details;
  }

  function setupTipeServis()
  {
    return $this->db->query("SELECT id, tipe_servis FROM setup_h2_tipe_servis");
  }

  public function get_good_issue_id()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $ymd     = date('Y-m-d');
    $ym     = date('ym');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

    if ($ymd >= '2021-08-01') {
      $get_data  = $this->db->query("SELECT good_issue_id FROM tr_h2_kirim_ke_part_counter
			WHERE LEFT(created_at,10)='$ymd' AND good_issue_id IS NOT NULL and id_dealer='$id_dealer'
      	ORDER BY created_at DESC LIMIT 0,1");
    } else {
      $get_data  = $this->db->query("SELECT good_issue_id FROM tr_h2_kirim_ke_part_counter
			WHERE LEFT(created_at,10)>='$th_bln' AND good_issue_id IS NOT NULL and id_dealer='$id_dealer'
      	ORDER BY good_issue_id DESC LIMIT 0,1");
    }

    if ($get_data->num_rows() > 0) {
      $row           = $get_data->row();
      $good_issue_id = substr($row->good_issue_id, -4);
      $new_kode      = $dealer->kode_dealer_md . '/GOOD/' . $ym . '/' . sprintf("%'.04d", $good_issue_id + 1);
      $i = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_kirim_ke_part_counter', ['good_issue_id' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/GOOD/' . $ym . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $dealer->kode_dealer_md . '/GOOD/' . $ym . '/0001';
    }

    return strtoupper($new_kode);
  }



  function get_kirim_wo($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE kkc.id_dealer='$id_dealer' AND sa.id_sa_form IS NOT NULL ";
    if ($filter != null) {
      if (isset($filter['good_issue_null'])) {
        $where .= " AND kkc.good_issue_id IS NULL ";
      }
      if (isset($filter['good_issue_not_null'])) {
        $where .= " AND kkc.good_issue_id IS NOT NULL ";
      }
      if (isset($filter['status_wo_in'])) {
        $where .= " AND wo.status IN ({$filter['status_wo_in']})";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND kkc.id_work_order='" . $filter['id_work_order'] . "'";
      }
      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND (ch23.nama_customer LIKE '%$search%'
                                OR ch23.id_customer LIKE '%$search%'
                                OR ch23.no_mesin LIKE '%$search%'
                                OR ch23.no_rangka LIKE '%$search%'
                                OR tipe_ahm LIKE '%$search%'
                                OR warna LIKE '%$search%'
                                OR sa.id_sa_form LIKE '%$search%'
                                OR sa.jenis_customer LIKE '%$search%'
                                OR wo.id_work_order LIKE '%$search%'
                                OR sa.tgl_servis LIKE '%$search%'
                                OR sa.jam_servis LIKE '%$search%'
                                ) 
                ";
        }
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_column = $filter['order_column'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY kkc.created_at DESC ";
        }
      } else {
        $order = " ORDER BY kkc.created_at DESC ";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT id_antrian,kkc.id_work_order,tgl_servis,jenis_customer,no_polisi,nama_customer,'Reguler' AS jenis_order,ch23.no_mesin,ch23.no_rangka,tipe_ahm,warna,good_issue_id,kkc.nomor_so,nomor_ps
    FROM tr_h2_kirim_ke_part_counter AS kkc
    JOIN tr_h2_wo_dealer AS wo ON wo.id_work_order=kkc.id_work_order
    JOIN tr_h2_sa_form AS sa ON sa.id_sa_form=wo.id_sa_form
    JOIN tr_h3_dealer_picking_slip ps ON ps.nomor_so=kkc.nomor_so
    left JOIN ms_customer_h23 AS ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
    LEFT JOIN ms_warna AS wr ON wr.id_warna=ch23.id_warna
    $where
    ");
  }

  function getWOPekerjaan($filter = NULL)
  {
    // send_json($filter);
    $where = "WHERE 1=1 ";
    if ($filter != NULL) {
      if (isset($filter['id_work_order'])) {
        $where .= " AND wo.id_work_order='{$filter['id_work_order']}'";
      }
      if (isset($filter['id_sa_form'])) {
        $where .= " AND wo.id_sa_form='{$filter['id_sa_form']}'";
      }
      if (isset($filter['no_njb_not_null'])) {
        $where .= " AND wo.no_njb IS NOT NULL";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND wo.id_dealer='{$filter['id_dealer']}'";
      }
      if (isset($filter['ada_promo'])) {
        $where .= " AND wopk.id_promo IS NOT NULL";
      }
      if (isset($filter['filter_created_wo'])) {
        $where .= " AND LEFT(wo.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }
      if (isset($filter['type_jasa_in'])) {
        $where .= " AND js.id_type IN({$filter['type_jasa_in']})";
      }
    }
    $diskon_pekerjaan = "CASE 
    WHEN wopk.id_promo IS NOT NULL THEN
     CASE 
       WHEN prj.tipe_diskon='rupiah' THEN prj.diskon
       ELSE
        CASE 
          WHEN wo.pkp_njb=1 THEN  (wopk.harga) * (prj.diskon/100)
          ELSE wopk.harga * (prj.diskon/100)
        END
      END
    ELSE 0
    END";
    $diskon_rp = "CASE 
    WHEN wopk.id_promo IS NOT NULL THEN
     CASE 
       WHEN prj.tipe_diskon='rupiah' THEN prj.diskon
       ELSE
        CASE 
          WHEN wo.pkp_njb=1 THEN  (wopk.harga) * (prj.diskon/100)
          ELSE wopk.harga * (prj.diskon/100)
        END
      END
    ELSE 0
    END";
    $diskon_persen = "CASE 
    WHEN wopk.id_promo IS NOT NULL THEN
     CASE 
       WHEN prj.tipe_diskon='rupiah' THEN 0
       ELSE
        prj.diskon
      END
    ELSE 0
    END";
    $select = "wo.no_njb,wopk.id_work_order,wopk.id_jasa,js.deskripsi,jst.deskripsi AS desk_type,ROUND((wopk.harga-$diskon_pekerjaan)) AS biaya_servis,wo.created_at,$diskon_rp AS diskon_rp,$diskon_persen AS diskon_persen,wopk.harga,wopk.id_promo,wo.updated_at,pr.nama_promo,wopk.diskon_value,pr.id_promo_int,jst.id_type,jst.color";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'concat_jasa') {
        $select = "CONCAT(js.deskripsi,', ') as concat_jasa";
      } elseif ($filter['select'] == 'select_type') {
        $select = "jst.deskripsi as desk_type";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_h2_wo_dealer_pekerjaan wopk 
    JOIN ms_h2_jasa js ON js.id_jasa=wopk.id_jasa
    JOIN ms_h2_jasa_type jst ON jst.id_type=js.id_type
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wopk.id_work_order
    LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wopk.id_promo AND prj.id_jasa=wopk.id_jasa
    LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
    $where
    ");
  }

  function getHLOWOParts($filter = null)
  {
    $where = "WHERE 1=1 AND wop.jenis_order='HLO' ";
    $select = "*";
    if ($filter != null) {
      if (isset($filter['select'])) {
        $select = $filter['select'];
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND wo.id_dealer='{$filter['id_dealer']}'";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND wop.id_work_order='{$filter['id_work_order']}'";
      }
      if (isset($filter['not_exists_picking_slip'])) {
        $where .= " AND NOT EXISTS(
                        SELECT booking_id_reference FROM tr_h3_dealer_sales_order so
                        JOIN tr_h3_dealer_request_document req ON req.id_booking=so.booking_id_reference
                        JOIN tr_h3_dealer_picking_slip pl ON pl.nomor_so=so.nomor_so
                        WHERE req.id_sa_form=wo.id_sa_form AND booking_id_reference=wop.id_booking
                        ) 
                    ";
      }
      if (isset($filter['group_by'])) {
        $where .= " GROUP BY {$filter['group_by']} ";
      }
    }

    return $this->db->query("SELECT $select FROM tr_h2_wo_dealer_parts wop 
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
    JOIN tr_h3_dealer_sales_order sod ON sod.booking_id_reference=wop.id_booking
    $where");
  }

  function getWOParts($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_sa_form'])) {
        $where .= " AND id_sa_form='{$filter['id_sa_form']}' ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND wo.id_dealer='{$filter['id_dealer']}' ";
      }
      if (isset($filter['filter_created_wo'])) {
        $where .= " AND LEFT(wo.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND wo.id_dealer='{$filter['id_dealer']}' ";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND wo.id_work_order='{$filter['id_work_order']}' ";
      }
      if (isset($filter['id_jasa'])) {
        $where .= " AND wop.id_jasa='{$filter['id_jasa']}' ";
      }
      if (isset($filter['id_part'])) {
        $where .= " AND wop.id_part='{$filter['id_part']}' ";
      }
      if (isset($filter['jenis_order'])) {
        $where .= " AND jenis_order='{$filter['jenis_order']}' ";
      }
      if (isset($filter['send_notif'])) {
        $where .= " AND send_notif='{$filter['send_notif']}' ";
      }
      if (isset($filter['order_to'])) {
        $where .= " AND order_to='{$filter['order_to']}' ";
      }
      if (isset($filter['nomor_so_null'])) {
        $where .= " AND  wop.nomor_so IS NULL ";
      }
      if (isset($filter['group_by_order_to'])) {
        if ($filter['group_by_order_to'] == true) {
          $where .= " GROUP BY order_to ";
        }
      }
      if (isset($filter['kelompok_part_in'])) {
        if ($filter['kelompok_part_in'] != '') {
          $where .= " AND ms_part.kelompok_part IN ({$filter['kelompok_part_in']})";
        }
      }
    }
    $potongan_nsc = "
          (CASE 
            WHEN wop.tipe_diskon='Value' THEN diskon_value
            ELSE 0
          END
          )
          ";
    $harga_beli = "
          (CASE 
            WHEN wo.pkp_njb=1 THEN 
              CASE 
                WHEN wop.tipe_diskon='Percentage' THEN harga - (harga*(diskon_value/100)) * wop.qty
                ELSE harga
              END
            ELSE 
              CASE 
                WHEN wop.tipe_diskon='Percentage' THEN harga - (harga*(diskon_value/100)) * wop.qty
                ELSE harga
              END
            END
          )
        ";
    $qty = "
        (CASE 
          WHEN wop.tipe_diskon='FoC' THEN qty-diskon_value
          ELSE qty
        END
        )
      ";
    $promo_rp = "$potongan_nsc+(harga-$harga_beli)+(CASE WHEN qty<$qty THEN $harga_beli*(qty-$qty) ELSE 0 END)";
    $tot_part = "(IFNULL(SUM(($harga_beli*$qty)-$potongan_nsc),0))";

    if (isset($filter['select'])) {
      if ($filter['select'] == 'nomor_so') {
        $select = 'wop.nomor_so';
      } elseif ($filter['select'] == 'picking_slip') {
        $select = 'wop.*,psl.nomor_ps,psl.status';
      } elseif ($filter['select'] == 'wo_parts') {
        $select = "wop.*,wop.id_jasa,wop.id_part,wop.qty,wop.jenis_order,wop.harga,nama_part,wop.id_part_int,send_notif,order_to,$promo_rp promo_rp,(wop.harga*wop.qty - wop.diskon_value) tot_part,nama_part, promo.nama nama_promo,wop.id_promo,wo.created_at,wo.updated_at";
      }
    } else {
      $select = "wop.id_work_order,wop.id_jasa,wop.id_part,wop.qty,wop.jenis_order,wop.harga,nama_part,wop.id_part_int,send_notif,order_to, 
    CASE 
      WHEN jenis_order ='HLO' THEN
        CASE WHEN dl.id_dealer IS NOT NULL THEN nama_dealer
        ELSE 'MD'
        END
    END order_to_name,st.satuan,wo.created_at,wop.part_utama,wop.tipe_diskon,$promo_rp AS promo_rp,$tot_part AS tot_part,nama_part, promo.nama nama_promo,wop.id_promo";
    }
    if (isset($filter['select_add'])) {
      if ($filter['select_add'] == 'harga_oli_kpb') {
        $select .= ",(SELECT harga_oli FROM ms_kpb WHERE id_tipe_kendaraan=(SELECT id_tipe_kendaraan FROM tr_h2_sa_form sa
        JOIN ms_customer_h23 cush23 ON cush23.id_customer=sa.id_customer
        WHERE ms_kpb.id_tipe_kendaraan=cush23.id_tipe_kendaraan AND sa.id_sa_form=wo.id_sa_form
        )) harga_oli_kpb";
      }
    }
    $join = '';
    if (isset($filter['join'])) {
      if ($filter['join'] == 'picking_slip') {
        $join = "JOIN tr_h3_dealer_picking_slip psl ON psl.nomor_so=wop.nomor_so";
      }
    }
    $group_by = '';
    if (isset($filter['group_by'])) {
      if ($filter['group_by'] == 'group_by_part') {
        $group_by = 'group BY wop.id_part_int';
      }
    }
    return  $this->db->query("SELECT $select
    FROM tr_h2_wo_dealer_parts AS wop
    JOIN ms_part ON ms_part.id_part_int=wop.id_part_int
    LEFT JOIN ms_h3_promo_dealer promo ON promo.id_promo=wop.id_promo
    LEFT JOIN ms_dealer dl ON dl.id_dealer=wop.order_to
    LEFT JOIN ms_satuan st ON st.id_satuan=ms_part.id_satuan
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
    $join
    $where 
    $group_by
    ");
  }

  function getWONeedParts($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_sa_form'])) {
        $where .= " AND id_sa_form='{$filter['id_sa_form']}' ";
      }
      if (isset($filter['jenis_order'])) {
        $where .= " AND jenis_order='{$filter['jenis_order']}' ";
      }
      if (isset($filter['send_notif'])) {
        $where .= " AND send_notif='{$filter['send_notif']}' ";
      }
    }
    return  $this->db->query("SELECT count(id_part)AS tot 
    FROM tr_h2_wo_dealer_parts wop
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
    $where ")->row()->tot;
  }

  function getJumlahJobPerTipe($filter = null)
  {
    $where = "WHERE wo.id_dealer='" . dealer()->id_dealer . "'";
    if ($filter != null) {
      if (isset($filter['bulan'])) {
        // $where .= " AND LEFT(wo.waktu_njb,7)='{$filter['bulan']}'";
      }
    }
    $bulan = $filter['bulan'];
    $where .= "GROUP BY ch23_tk.id_tipe_kendaraan ORDER BY tipe_ahm ASC";
    $filter = [
      'sql' => true,
      'bulan_njb' => $bulan,
      'id_type_in' => "'ASS1'",
      'id_tipe_kendaraan_sql' => "tk.id_tipe_kendaraan",
    ];
    $ass1 = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'ASS2'";
    $ass2 = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'ASS3'";
    $ass3 = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'ASS4'";
    $ass4 = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'C1','C2'";
    $claim = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'CS'";
    $cs = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'LS'";
    $ls = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'OR+'";
    $or_p = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'LR'";
    $lr = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'HR'";
    $hr = $this->m_lap->getUnitEntri($filter);
    $filter['id_type_in'] = "'OTHER'";
    $other = $this->m_lap->getUnitEntri($filter);
    $filter = [
      'sql' => true,
      'bulan_njb' => $bulan,
    ];
    $ue = $this->m_lap->getUnitEntri($filter);

    return $this->db->query("SELECT tipe_ahm ,($ass1)AS ass1,($ass2)AS ass2,($ass3)AS ass3,($ass4)AS ass4,($claim)AS claim,($cs)AS cs,($ls)AS ls,($or_p)AS or_p,($lr)AS lr,($hr)AS hr,($other)AS other,0 AS jr,($ue) AS ue
    FROM tr_h2_wo_dealer wo
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    JOIN ms_customer_h23 ch23_tk ON ch23_tk.id_customer=sa.id_customer
    JOIN ms_tipe_kendaraan tks ON tks.id_tipe_kendaraan=ch23_tk.id_tipe_kendaraan
    $where
    ");
  }
  function getLabourCost($filter = null)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_tipe_kendaraan'])) {
      $where .= " AND lct.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }
    if (isset($filter['tgl_mulai_berlaku'])) {
      $where .= " AND lc.tgl_mulai_berlaku <='{$filter['tgl_mulai_berlaku']}'";
    }
    return $this->db->query("SELECT nominal 
    FROM ms_labour_cost_tipe lct
    JOIN ms_labour_cost lc ON lc.id=lct.id
    $where ORDER BY created_at DESC LIMIT 1");
  }

  public function get_id_claim_c2()
  {
    $thbln     = date('m/Y');
    $th        = date('Y');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT no_claim_c2 FROM tr_h2_sa_form
			WHERE id_dealer='$id_dealer'
			AND no_claim_c2 IS NOT NULL AND LEFT(created_at,4)='$th'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = explode('/', $row->no_claim_c2)[3];
      $new_kode   = 'C2/E20/' . $dealer->kode_dealer_md . '/' . sprintf("%'.05d", $last_number + 1) . '/' . $thbln;
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_sa_form', ['no_claim_c2' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number = explode('/', $row->no_claim_c2)[3];
          $new_kode   = 'C2/E20/' . $dealer->kode_dealer_md . '/' . sprintf("%'.05d", $gen_number + 1) . '/' . $thbln;
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'C2/E20/' . $dealer->kode_dealer_md . '/00001/' . $thbln;
    }
    return strtoupper($new_kode);
  }

  function getPromoServis($filter)
  {
    $where  = "WHERE 1=1";
    if (isset($filter['id_promo'])) {
      $where .= " AND psj.id_promo='{$filter['id_promo']}'";
    }
    if (isset($filter['id_jasa'])) {
      $where .= " AND psj.id_jasa='{$filter['id_jasa']}'";
    }
    if (isset($filter['id_dealer'])) {
      $where .= " AND '{$filter['id_dealer']}' IN(SELECT id_dealer FROM ms_promo_servis_dealer WHERE id_promo=psj.id_promo)";
    }
    return $this->db->query("SELECT diskon,tipe_diskon,id_promo,id_jasa 
    FROM ms_promo_servis_jasa psj
    $where");
  }

  function getMekanikHistoryServis($filter)
  {
    $where = "WHERE wo.status='closed'";
    if (isset($filter['id_karyawan_dealer'])) {
      $where .= " AND id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
    }
    if (isset($filter['id_dealer'])) {
      $where .= " AND wo.id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['id_type'])) {
      $where .= " AND js.id_type='{$filter['id_type']}'";
    }
    if (isset($filter['tgl_njb'])) {
      $where .= " AND LEFT(wo.created_njb_at,10)='{$filter['tgl_njb']}'";
    }
    if (isset($filter['tahun_bulan_njb'])) {
      $where .= " AND LEFT(wo.created_njb_at,7)='{$filter['tahun_bulan_njb']}'";
    }
    if (isset($filter['tahun_bulan_wo'])) {
      $where .= " AND LEFT(wo.created_at,7)='{$filter['tahun_bulan_wo']}'";
    }
    if (isset($filter['periode_njb'])) {
      $where .= " AND LEFT(wo.created_njb_at,10) BETWEEN'{$filter['periode_njb']['start']}' AND '{$filter['periode_njb']['end']}'";
    }
    if (isset($filter['id_type_in'])) {
      $where .= " AND js.id_type IN({$filter['id_type_in']})";
    }

    return $this->db->query("SELECT COUNT(id_type) AS c
    FROM tr_h2_wo_dealer_pekerjaan wodp 
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wodp.id_work_order
    JOIN ms_h2_jasa js ON js.id_jasa=wodp.id_jasa
    $where
    ")->row()->c;
  }

  function getWorkOrder($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_dealer'])) {
      $where .= " AND wo.id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['is_sa_form'])) {
      $where .= " AND wo.is_sa_form='{$filter['is_sa_form']}'";
    }
    if (isset($filter['id_work_order'])) {
      $where .= " AND wo.id_work_order='{$filter['id_work_order']}'";
    }
    if (isset($filter['id_karyawan_dealer'])) {
      $where .= " AND wo.id_karyawan_dealer='{$filter['id_karyawan_dealer']}'";
    }
    if (isset($filter['id_karyawan_dealer_int'])) {
      $where .= " AND kry.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
    }
    if (isset($filter['status_in'])) {
      $where .= " AND wo.status IN({$filter['status_in']})";
    }
    if (isset($filter['tahun_bulan_wo'])) {
      $where .= " AND LEFT(wo.created_at,7)='{$filter['tahun_bulan_wo']}'";
    }
    if (isset($filter['tgl_njb'])) {
      $where .= " AND LEFT(wo.created_njb_at,10)='{$filter['tgl_njb']}'";
    }
    if (isset($filter['no_njb_not_null'])) {
      $where .= " AND no_njb IS NOT NULL";
    }
    $dibayar = "(SELECT IFNULL(SUM(nominal),0) 
        FROM tr_h2_receipt_customer_metode rcm
        JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
        WHERE id_referensi=wo.id_work_order
      )
    ";
    if (isset($filter['wo_lunas'])) {
      $where .= " AND wo.grand_total=($dibayar)";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (wo.id_work_order LIKE '%$search%'
                          OR wo.id_sa_form LIKE '%$search%'
                          OR sa.tgl_servis LIKE '%$search%'
                          OR sa.jenis_customer LIKE '%$search%'
                          OR ch23.no_polisi LIKE '%$search%'
                          OR ch23.nama_customer LIKE '%$search%'
                          OR ch23.no_mesin LIKE '%$search%'
                          OR ch23.no_rangka LIKE '%$search%'
                          OR tk.tipe_ahm LIKE '%$search%'
                          OR wr.warna LIKE '%$search%'
                          OR ch23.tahun_produksi LIKE '%$search%'
                          ) 
              ";
      }
    }


    $select = "wo.*";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(wo.id_work_order) AS count";
      } elseif ($filter['select'] == 'history') {
        $select = "wo.id_work_order,wo.id_sa_form,sa.tgl_servis,sa.jenis_customer,ch23.no_polisi,ch23.nama_customer,ch23.no_mesin,ch23.no_rangka,tk.tipe_ahm,wr.warna,ch23.tahun_produksi,wo.status,wo.no_njb,LEFT(created_njb_at,10) tgl_njb";
      }
    }
    $join = '';
    if (isset($filter['join'])) {
      if (in_array('customer', $filter['join'])) {
        $join .= " JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer ";
        if (in_array('tipe_kendaraan', $filter['join'])) {
          $join .= " JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan ";
        }
        if (in_array('warna', $filter['join'])) {
          $join .= " LEFT JOIN ms_warna wr ON wr.id_warna=ch23.id_warna";
        }
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    return $this->db->query("SELECT $select 
    FROM tr_h2_wo_dealer wo 
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=wo.id_karyawan_dealer
    $join
    $where
    $limit
    ");
  }

  function setClockMekanik($params)
  {
    $id_work_order = $params['id_work_order'];
    $stats         = $params['stats'];
    $waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id      = $params['login_id'];

    $cek_stat_wo = $this->db->get_where('tr_h2_wo_dealer', ['id_work_order' => $id_work_order]);
    $data_wo = $cek_stat_wo->row();
    // $post = $this->input->post();
    // send_json($post);
    if ($stats == 'start') {
      $data = [
        'start_at' => $waktu,
        'start_by' => $login_id
      ];
      $ins_waktu = [
        'id_work_order' => $id_work_order,
        'set_at' => $waktu,
        'set_by' => $login_id,
        'stats' => 'start',
        'detik' => 0
      ];
    }
    if ($stats == 'pause') {
      $data = ['status' => 'pause'];
      $get_last_set = $this->mh2->get_last_waktu_wo($id_work_order)->row()->set_at;
      $detik = strtotime($waktu) - strtotime($get_last_set);
      $ins_waktu = [
        'id_work_order' => $id_work_order,
        'set_at' => $waktu,
        'set_by' => $login_id,
        'stats'  => 'pause',
        'detik'  => $detik
      ];
    }
    if ($stats == 'resume') {
      $data = ['status' => 'open'];
      $ins_waktu = [
        'id_work_order' => $id_work_order,
        'set_at' => $waktu,
        'set_by' => $login_id,
        'stats'  => 'resume',
        'detik'  => 0
      ];
    }
    if ($stats == 'closed') {
      // send_json($post);
      // $data = ['status'=>'closed'];
      //Cek Jika Ada HLO Belum Selesai
      $filter = [
        'id_work_order' => $id_work_order,
        'select' => 'id_booking',
        'not_exists_picking_slip' => true,
        'group_by' => 'id_booking'
      ];
      $cek_hlo_belum_selesai = $this->m_wo->getHLOWOParts($filter);
      if ($cek_hlo_belum_selesai->num_rows() > 0) {
        if (isset($params['from'])) {
          if ($params['from'] == 'api_mobile') {
            $msg = ['Masih ada parts HLO yang belum selesai di proses !'];
            send_json(msg_sc_error($msg));
          }
        } else {
          $result = [
            'status' => 'error',
            'pesan' => 'Masih ada parts HLO yang belum selesai di proses !',
          ];
          send_json($result);
        }
      }
      $filter = [
        'id_work_order' => $id_work_order,
        'select' => 'nomor_so',
        'jenis_order' => 'reguler',
        'nomor_so_null' => true
      ];
      $cek_reg_belum_selesai = $this->m_wo->getWOParts($filter);
      // send_json($cek_reg_belum_selesai->row());
      if ($cek_reg_belum_selesai->num_rows() > 0) {
        if (isset($params['from'])) {
          $msg = ['Masih ada parts reguler yang belum selesai di proses !'];
          send_json(msg_sc_success(NULL, $msg));
        } else {
          $result = [
            'status' => 'error',
            'pesan' => 'Masih ada parts reguler yang belum selesai di proses !',
          ];
        }
        send_json($result);
      }
      // send_json($filter);
      $cek_stat_wo = $data_wo->status;
      $detik = 0;
      if ($cek_stat_wo == 'open') {
        $get_last_set = $this->mh2->get_last_waktu_wo($id_work_order)->row()->set_at;
        $detik = strtotime($waktu) - strtotime($get_last_set);
      }
      $ins_waktu = [
        'id_work_order' => $id_work_order,
        'set_at' => $waktu,
        'set_by' => $login_id,
        'stats'  => 'end',
        'detik'  => $detik
      ];
    }
    // $tes = ['status' => 'error', 'wo' => $data_wo, 'ins_waktu' => $ins_waktu, 'upd' => $data];
    // send_json($tes);
    $this->db->trans_begin();
    $upd_sa = ['status_monitor' => 'diservis'];
    $this->db->update('tr_h2_sa_form', $upd_sa, ['id_sa_form' => $data_wo->id_sa_form]);
    if (isset($data)) {
      $this->db->update('tr_h2_wo_dealer', $data, ['id_work_order' => $id_work_order]);
    }
    if (isset($ins_waktu)) {
      $this->db->insert('tr_h2_wo_dealer_waktu', $ins_waktu);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'data_sheduling' => null
      ];
    }
    return $rsp;
  }

  function cetak_wo($params)
  {
    $data['set'] = 'print';
    $data['judul_laporan'] = 'PERINTAH KERJA BENGKEL';
    $get_data = $this->m_wo->get_sa_form($params);
    $row = $get_data->row();
    $this->updateGrandTotalWO($row->id_work_order);
    if ($get_data->num_rows() > 0) {
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;
      $get_data = $this->m_wo->get_sa_form($params);
      $row = $get_data->row();
      $data['set'] = 'print';
      $data['judul_laporan'] = 'PERINTAH KERJA BENGKEL';
      $data['row'] = $row;

      $filter_pekerjaan['id_work_order'] = $row->id_work_order;
      $filter_pekerjaan['id_dealer'] = $row->id_dealer;
      $data['pekerjaan'] = $this->m_h2->getPekerjaanWO($filter_pekerjaan)->result();

      $filter_part['id_work_order'] = $row->id_work_order;
      $filter_part['id_dealer'] = $row->id_dealer;
      $filter_part['group_by'] = 'group_by_part';
      $data['parts'] = $this->m_wo->getWOParts($filter_part)->result();

      $data['estimasi_biaya'] = $row->grand_total;

      $filter_sa = [
        'id_customer' => $row->id_customer,
        'id_dealer' => $row->id_dealer,
        'except_wo' => $row->id_work_order
      ];
      $last_wo = $this->m_wo->get_sa_form($filter_sa);
      if ($last_wo->num_rows() > 0) {
        $data['last_wo'] = $last_wo->row();
      }
      $data['id_dealer'] = $row->id_dealer;

      $data['nama_lengkap'] = $row->mekanik;
      $html = $this->load->view('dealer/sa_form_cetak', $data, true);
      $mpdf->WriteHTML($html);

      if (isset($params['save_server'])) {
        $wo = $this->db->get_where('tr_h2_wo_dealer', ['id_work_order' => $row->id_work_order])->row();
        $next_cetak = $wo->cetak_wo_ke + 1;
        $path = 'uploads/document_wo/' . get_y() . '/' . $row->kode_dealer_md . '/' . get_m() . '/' . get_d();
        if (!is_dir($path)) {
          mkdir($path, 0777, true);
        }
        $nama_file = str_replace('/', '-', $row->id_work_order);
        $doc = $upd_wo['document'] = $path . '/' . $nama_file . '-' . $next_cetak . '.pdf';
        $upd_wo['cetak_wo_ke'] = $next_cetak;
        $upd_wo['cetak_wo_by'] = $params['id_user'];
        $upd_wo['cetak_wo_at'] = waktu_full();
        if (file_exists(FCPATH . $doc)) {
          unlink($doc); //Delete File
        }
        if (($wo->document == NULL || $wo->document == '') == false) {
          if (file_exists(FCPATH . $wo->document)) {
            unlink($wo->document); //Delete File
          }
        }
        $this->db->update('tr_h2_wo_dealer', $upd_wo, ['id_sa_form' => $params['id_sa_form']]);
        $mpdf->Output("$doc", 'F');
        return base_url($doc);
      } else {
        $output = 'cetak_work_order.pdf';
        $mpdf->Output("$output", 'I');
      }
    } else {
      return false;
    }
  }

  function getPITdanWO($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_dealer'])) {
      $where .= " AND pit.id_dealer='{$filter['id_dealer']}'";
    }

    $wo_aktif = "SELECT id_work_order_int
    FROM tr_h2_wo_dealer wo
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    WHERE sa.id_pit=pit.id_pit AND (wo.status='pause' OR wo.status='open') AND wo.id_dealer=pit.id_dealer
    LIMIT 1
    ";

    if (isset($filter['id_work_order_not_null'])) {
      $where .= " AND ($wo_aktif) IS NOT NULL";
    }


    $select = "id_pit_int,id_pit,jenis_pit, ($wo_aktif) AS id_work_order_int ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(wo.id_work_order_int) AS count";
      }
    }

    return $this->db->query("SELECT $select
    FROM ms_h2_pit pit
    $where");
  }
  function getH3PODealer($filter = NULL)
  {
    // send_json($filter);
    $where = "WHERE 1=1 ";
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        $where .= " AND po.id_dealer='{$filter['id_dealer']}'";
      }
      if (isset($filter['tahun_bulan'])) {
        $where .= " AND LEFT(po.tanggal_order,7)='{$filter['tahun_bulan']}'";
      }
      if (isset($filter['po_type'])) {
        $where .= " AND po.po_type='{$filter['po_type']}'";
      }
    }

    $select = "*";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(po.po_id) AS count";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_h3_dealer_purchase_order po
    $where
    ");
  }

  function updateGrandTotalWO($id_work_order)
  {
    $this->db->select('id_work_order,created_at');
    $this->db->where('id_work_order', $id_work_order);
    $wo = $this->db->get('tr_h2_wo_dealer');
    // send_json($wo->result());
    foreach ($wo->result() as $w) {
      $jasa = $this->db->query("SELECT SUM(subtotal) tot FROM tr_h2_wo_dealer_pekerjaan WHERE id_work_order='$w->id_work_order' AND pekerjaan_batal!=1")->row()->tot;

      $return = "SELECT kuantitas_return FROM tr_h3_dealer_sales_order_parts spt WHERE spt.nomor_so=prts.nomor_so AND spt.id_part=prts.id_part";
      $part = $this->db->query("SELECT prts.*,prt.harga_dealer_user,($return) AS qty_return 
        FROM tr_h2_wo_dealer_parts prts 
        JOIN ms_part prt ON prt.id_part=prts.id_part
        WHERE prts.id_work_order='$w->id_work_order' AND (pekerjaan_batal IS NULL OR pekerjaan_batal!=1)");
      $total_part = 0;
      // send_json($part->result());
      foreach ($part->result_array() as $prt) {
        $prt['qty'] = $prt['qty'] - $prt['qty_return'];
        $sub = subtotal_part($prt, $prt['harga']);
        $total_part += $sub;
        $upd_subtotal[] = [
          'id_part' => $prt['id_part'],
          'harga' => $prt['harga_dealer_user'],
          'subtotal' => $sub
        ];
      }

      $grand_total = $jasa + $total_part;
      // send_json($total_part);
      if ($grand_total > 0) {
        $upd[] = [
          'id_work_order' => $w->id_work_order,
          'grand_total' => $grand_total,
          'total_jasa' => $jasa,
          'total_part' => $total_part
        ];
      }
      // send_json($upd);
      if (isset($upd)) {
        $this->db->update_batch('tr_h2_wo_dealer', $upd, 'id_work_order');
      }
      if (isset($upd_subtotal)) {
        $this->db->where('id_work_order', $w->id_work_order);
        $this->db->update_batch('tr_h2_wo_dealer_parts', $upd_subtotal, 'id_part');
      }
    }
    // send_json($upd_subtotal);
  }

  function getSAFormHistory($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE sa.id_dealer='$id_dealer' AND sa.id_sa_form IS NOT NULL ";
    if ($filter != null) {
      if (isset($filter['status_form_in'])) {
        $where .= " AND sa.status_form IN({$filter['status_form_in']}) ";
      }

      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND (ch23.nama_customer LIKE '%$search%'
                                OR ch23.id_customer LIKE '%$search%'
                                OR ch23.no_polisi LIKE '%$search%'
                                OR ch23.tahun_produksi LIKE '%$search%'
                                OR ch23.no_mesin LIKE '%$search%'
                                OR ch23.no_rangka LIKE '%$search%'
                                OR tk.tipe_ahm LIKE '%$search%'
                                OR wr.warna LIKE '%$search%'
                                OR sa.id_sa_form LIKE '%$search%'
                                OR sa.jenis_customer LIKE '%$search%'
                                OR sa.tgl_servis LIKE '%$search%'
                                OR sa.jam_servis LIKE '%$search%'
                                ) 
                ";
        }
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_column = $filter['order_column'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY sa.created_at DESC ";
        }
      } else {
        $order = " ORDER BY sa.created_at DESC ";
      }
      $limit = '';
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT sa.id_sa_form,sa.id_antrian,sa.tgl_servis,DATE_FORMAT(sa.tgl_servis,'%d/%m/%Y') tgl_servis_indo,sa.jenis_customer,ch23.no_polisi,ch23.nama_customer,ch23.no_mesin,ch23.no_rangka,tk.tipe_ahm,wr.warna,ch23.tahun_produksi,sa.status_form
    FROM tr_h2_sa_form sa
    JOIN ms_customer_h23 AS ch23 ON ch23.id_customer_int=sa.id_customer_int
    JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
    LEFT JOIN ms_warna AS wr ON wr.id_warna=ch23.id_warna
    $where
    $order
    $limit
    ");
  }

  function get_saran_ganti_saperpart($is_tambahan)
  {
    return $this->db->get_where('ms_saran_ganti_sparepart', ['is_paket_tambahan' => $is_tambahan])->result();
  }

  function cetak_sa_form($params, $id_dealer = null)
  {
    $data['set'] = 'print';
    $data['judul_laporan'] = 'CETAK FORM SERVICE ADVISOR';
    if ($id_dealer != null) {
      $params['id_dealer'] = $id_dealer;
    }

    $get_data = $this->m_wo->get_sa_form($params);
    $row = $get_data->row();
    if ($get_data->num_rows() > 0) {
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;
      $get_data = $this->m_wo->get_sa_form($params);
      $row = $get_data->row();
      // send_json($row);
      $next_cetak = $row->cetak_sa_form_ke + 1;
      $upd = [
        'cetak_sa_form_ke' => $next_cetak,
        'cetak_sa_form_at' => waktu_full(),
        'cetak_sa_form_by' => $params['id_user'],
      ];
      // send_json($upd);
      $data['row'] = $row;
      $filter_pekerjaan['id_sa_form'] = $row->id_sa_form;
      $filter_pekerjaan['id_dealer'] = $row->id_dealer;
      $details = $this->m_wo->sa_form_detail($filter_pekerjaan);
      $parts = [];
      $pekerjaan = [];
      foreach ($details as $dt) {
        if (isset($dt->parts)) {
          foreach ($dt->parts as $prts) {
            $parts[] = $prts;
          }
        }
        $dt->parts = null;
        $pekerjaan[] = $dt;
      }
      $data['pekerjaan'] = $pekerjaan;
      $data['parts'] = $parts;
      // send_json($data);
      $filter_part['id_sa_form'] = $row->id_sa_form;

      $filter_sa = [
        'id_customer' => $row->id_customer,
        'id_dealer' => $params['id_dealer'],
        'except_wo' => $row->id_work_order
      ];
      $last_wo = $this->m_wo->get_sa_form($filter_sa);
      if ($last_wo->num_rows() > 0) {
        $data['last_wo'] = $last_wo->row();
        // send_json($data);
      }
      $data['saran_ganti_utama'] = $this->get_saran_ganti_saperpart(0);
      $data['saran_ganti_tambahan'] = $this->get_saran_ganti_saperpart(1);
      // send_json($upd);
      $html = $this->load->view('dealer/sa_form_new_cetak', $data, true);
      $mpdf->WriteHTML($html);
      $this->db->update('tr_h2_sa_form', $upd, ['id_sa_form' => $row->id_sa_form]);
      $upd_wo = ['id_sa_form_int' => $row->id_antrian_int];
      $this->db->update('tr_h2_wo_dealer', $upd_wo, ['id_sa_form' => $row->id_sa_form]);
      $this->updateGrandTotalWO($row->id_work_order);
      if (isset($params['save_server'])) {
        $path = 'uploads/document_wo/' . get_y() . '/' . $row->kode_dealer_md . '/' . get_m() . '/' . get_d();
        if (!is_dir($path)) {
          mkdir($path, 0777, true);
        }
        $nama_file = str_replace('/', '-', $row->id_sa_form);
        $doc = $updwo['document'] = $path . '/' . $nama_file . '-' . $next_cetak . '.pdf';
        $this->db->update('tr_h2_wo_dealer', $updwo, ['id_sa_form' => $row->id_sa_form]);
        $mpdf->Output("$doc", 'F');
        return base_url($doc);
      } else {
        $path = 'uploads/document_wo/' . get_y() . '/' . $row->kode_dealer_md . '/' . get_m() . '/' . get_d();
        if (!is_dir($path)) {
          mkdir($path, 0777, true);
        }
        $nama_file = str_replace('/', '-', $row->id_sa_form);
        $doc = $updsa['document_sa'] = $path . '/' . $nama_file . '-' . $next_cetak . '.pdf';
        $this->db->update('tr_h2_sa_form', $updsa, ['id_sa_form' => $row->id_sa_form]);
        $mpdf->Output("$doc", 'F');
        $output = 'cetak_sa_form_' . strtotime(waktu_full()) . '.pdf';
        $mpdf->Output("$output", 'I');
      }
      $this->cetak_wo($params);
    } else {
      return false;
    }
  }

  function get_sa_form_header($filter = null)
  {
    $id_dealer = '';

    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }

    // send_json($id_dealer);
    $dealer = '';
    if ($id_dealer != '') {
      $dealer = " AND sa.id_dealer='$id_dealer'";
    }
    $where = "WHERE 1=1 $dealer";

    if ($filter == null) {
      $where = "WHERE 1=0";
    }

    $order = "ORDER BY sa.created_at DESC ";
    $limit = '';
    if ($filter != null) {
      if (isset($filter['id_antrian'])) {
        $where .= " AND sa.id_antrian='{$filter['id_antrian']}'";
      }

      if (isset($filter['status_form'])) {
        $status_form = arr_in_sql($filter['status_form']);
        $where .= " AND sa.status_form IN($status_form) ";
      }

      if (isset($filter['status_form_not'])) {
        $where .= " AND sa.status_form!='{$filter['status_form_not']}'";
      }

      if (isset($filter['tgl_servis'])) {
        $where .= " AND sa.tgl_servis='" . $filter['tgl_servis'] . "'";
      }

      if (isset($filter['tgl_servis_lebih_kecil_sama'])) {
        $where .= " AND sa.tgl_servis <= '{$filter['tgl_servis_lebih_kecil_sama']}'";
      }

      if (isset($filter['except_wo'])) {
        $where .= " AND wod.id_work_order != '{$filter['except_wo']}'";
      }

      if (isset($filter['id_customer'])) {
        $where .= " AND sa.id_customer='{$filter['id_customer']}'";
      }

      if (isset($filter['id_sa_form'])) {
        $where .= " AND sa.id_sa_form='{$filter['id_sa_form']}'";
      }

      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND (cus.nama_customer LIKE '%$search%'
                                OR cus.id_customer LIKE '%$search%'
                                OR cus.no_mesin LIKE '%$search%'
                                OR cus.no_rangka LIKE '%$search%'
                                OR tk.tipe_ahm LIKE '%$search%'
                                OR warna LIKE '%$search%'
                                OR sa.id_sa_form LIKE '%$search%'
                                OR sa.jenis_customer LIKE '%$search%'
                                OR wod.id_work_order LIKE '%$search%'
                                OR sa.tgl_servis LIKE '%$search%'
                                OR sa.jam_servis LIKE '%$search%'
                                ) 
                ";
        }
      }

      $order_column = array('sa.id_sa_form', 'id_antrian', 'sa.tgl_servis', 'sa.jenis_customer', 'cus.no_polisi', 'cus.nama_customer', 'cus.no_mesin', 'cus.no_rangka', 'tk.id_tipe_kendaraan', 'cus.id_warna', 'cus.tahun_produksi', null);
      if (isset($filter['order'])) {
        if (isset($filter['order_column'])) {
          if ($filter['order_column'] == 'history') {
            $order_column = ['id_work_order', 'sa.id_sa_form', 'sa.tgl_servis', 'sa.jenis_customer', 'cus.no_polisi', 'cus.nama_customer', 'cus.no_mesin', 'cus.no_rangka', 'tk.tipe_ahm', 'wr.warna', 'cus.tahun_produksi', 'wod.status', NULL];
          }
        }
        if ($filter['order'] != '') {
          if ($filter['order'] == 'order_jam_asc') {
            $order = "ORDER BY sa.tgl_servis ASC";
          } else {
            $order = $filter['order'];
            $order_clm  = $order_column[$order[0]['column']];
            $order_by   = $order[0]['dir'];
            $order = " ORDER BY $order_clm $order_by ";
          }
        } else {
          $order = " ORDER BY sa.created_at DESC ";
        }
      } else {
        $order = " ORDER BY sa.created_at DESC ";
      }

      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }


    $select = "id_antrian,sa.id_sa_form,tgl_servis,jenis_customer,cus.no_polisi,cus.nama_customer,cus.no_mesin,cus.no_rangka,tipe_ahm,warna,cus.tahun_produksi,sa.status_form,sa.id_antrian_int";

    return $this->db->query("SELECT $select
        FROM tr_h2_sa_form AS sa
        JOIN ms_dealer AS mdl ON mdl.id_dealer=sa.id_dealer
        LEFT JOIN tr_h2_wo_dealer AS wod ON wod.id_sa_form=sa.id_sa_form
        LEFT JOIN ms_customer_h23 AS cus ON cus.id_customer=sa.id_customer
        LEFT JOIN ms_tipe_kendaraan AS tk ON cus.id_tipe_kendaraan=tk.id_tipe_kendaraan
        LEFT JOIN ms_warna ON cus.id_warna=ms_warna.id_warna
        $where
        $order
        $limit
        ");
  }
}
