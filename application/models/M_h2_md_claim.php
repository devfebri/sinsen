<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_md_claim extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getDataClaimKPBMD($filter)
  {
    $where = 'WHERE 1=1 ';
    $status = "CASE WHEN clm.status IS NULL THEN ckgd.status END";


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
    // if (isset($filter['tgl_service'])) {
    //   if ($filter['tgl_service'] != '') {
    //     $where .= " AND clm.tgl_service='{$filter['tgl_service']}'";
    //   }
    // }

    if (isset($filter['tgl_service_akhir'])&&isset($filter['tgl_service_awal'])) {
      if ($filter['tgl_service_akhir'] != '' && $filter['tgl_service_awal'] != '') {
        $where .= " AND clm.tgl_service>='{$filter['tgl_service_awal']}' AND clm.tgl_service<='{$filter['tgl_service_akhir']}'";
      }
    }
    
    if (isset($filter['status'])) {
      if ($filter['status'] != '') {
        if ($filter['status']=='input') {
          $where .= " AND ($status) IS NULL";
        }else{
          $where .= " AND ($status)='{$filter['status']}'";
        }
      }
    }

    if (isset($filter['no_mesin_5'])) {
      if ($filter['no_mesin_5'] != '') {
        $where .= " AND tk.no_mesin='{$filter['no_mesin_5']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (   clm.id_dealer LIKE '%$search%'
                            OR clm.kpb_ke LIKE '%$search%'
                            OR clm.no_mesin LIKE '%$search%'
                            OR clm.no_rangka LIKE '%$search%'
                            OR clm.kpb_ke LIKE '%$search%'
                            OR clm.tgl_beli_smh LIKE '%$search%'
                            OR clm.tgl_service LIKE '%$search%'
                            OR clm.km_service LIKE '%$search%'
                            OR clm.created_at LIKE '%$search%'
                            OR dl.kode_dealer_md LIKE '%$search%'
                            OR dl.nama_dealer LIKE '%$search%'
                            OR tk.tipe_ahm LIKE '%$search%'
                            OR tk.tipe_ahm LIKE '%$search%'
                            ) 
            ";
      }
    }

    $set_order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view_claim_kpb') {
          $order_column = ['kode_dealer_md', 'nama_dealer', 'clm.no_mesin', 'clm.no_rangka', 'clm.no_kpb', 'tk.tipe_ahm', 'clm.kpb_ke', 'clm.tgl_beli_smh', 'clm.km_service', 'clm.tgl_service', 'clm.created_at', $status, NULL];
        } elseif ($filter['order_column'] == 'view_upload') {
          $order_column = ['clm.no_mesin', 'clm.no_rangka', 'clm.id_tipe_kendaraan', 'clm.kpb_ke', 'clm.created_at'];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $set_order .= " ORDER BY $order_clm $order_by ";
      } else {
        $set_order .= " ORDER BY clm.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT clm.id_claim_kpb,clm.no_mesin,clm.no_rangka,clm.id_tipe_kendaraan,no_kpb,kpb_ke,id_part,harga_jasa,harga_material,diskon_material,tgl_beli_smh,km_service,tgl_service,clm.id_dealer,clm.id_periode,clm.status,dl.kode_dealer_md,dl.nama_dealer,clm.created_at,tk.tipe_ahm 
    FROM tr_claim_kpb clm
    LEFT JOIN ms_dealer dl ON dl.id_dealer=clm.id_dealer
    LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=clm.id_tipe_kendaraan
    LEFT JOIN tr_claim_kpb_generate_detail ckgd ON ckgd.id_claim_kpb=clm.id_claim_kpb
    $where $set_order $limit
    ");
  }
  function getNosinClaimKPB($filter)
  {
    $get_pekerjaan_kpb = "SELECT id_type FROM tr_h2_wo_dealer_pekerjaan wop JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa WHERE id_work_order=wo.id_work_order AND id_type IN('ASS1','ASS2','ASS3','ASS4') AND wop.pekerjaan_batal=0 LIMIT 1";

    $ass = "SELECT RIGHT(js.id_type,1) FROM tr_h2_wo_dealer_pekerjaan wdp
    JOIN ms_h2_jasa js ON js.id_jasa=wdp.id_jasa
    WHERE wdp.id_work_order=wo.id_work_order AND js.id_type IN('ASS1','ASS2','ASS3','ASS4') AND wdp.pekerjaan_batal=0 LIMIT 1";

    $set_filter   = "WHERE 1=1 
        AND wo.status='closed' 
        AND wo.no_njb IS NOT NULL
        AND (SELECT COUNT(wdp.id_jasa) FROM tr_h2_wo_dealer_pekerjaan wdp
        JOIN ms_h2_jasa js ON js.id_jasa=wdp.id_jasa
        WHERE wdp.id_work_order=wo.id_work_order AND js.id_type IN('ASS1','ASS2','ASS3','ASS4')
        AND wdp.pekerjaan_batal=0
        )>0
        AND clk.no_mesin IS NULL
        
    ";
    // $set_filter = "WHERE 1=1 ";

    if (isset($filter['periode'])) {
      if ($filter['periode'] != '') {
        $end = $filter['end_date'];
        $start = $filter['start_date'];
        $set_filter .= "AND sa.tgl_servis BETWEEN '$start' AND '$end' ";
      }
    }
    if (isset($filter['id_dealer'])) {
      $set_filter .= " AND wo.id_dealer='{$filter['id_dealer']}' ";
    }
    if (isset($filter['no_mesin'])) {
      $set_filter .= " AND ch23.no_mesin='{$filter['no_mesin']}' ";
    }
    if (isset($filter['no_rangka'])) {
      $set_filter .= " AND ch23.no_rangka='{$filter['no_rangka']}' ";
    }
    if (isset($filter['id_work_order'])) {
      $id_work_orders = arr_in_sql($filter['id_work_order']);
      $set_filter .= " AND wo.id_work_order NOT IN ($id_work_orders)";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $set_filter .= " AND (ch23.no_mesin LIKE '%$search%'
                            OR ch23.no_rangka LIKE '%$search%'
                            OR ch23.id_tipe_kendaraan LIKE '%$search%'
                            OR ch23.id_warna LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        $order_column = $filter['order_column'];
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $set_filter .= " ORDER BY $order_clm $order_by ";
      } else {
        $set_filter .= "ORDER BY wo.id_work_order DESC ";
      }
    }

    if (isset($filter['limit'])) {
      $set_filter .= $filter['limit'];
    }

    return $this->db->query("SELECT ch23.no_mesin,ch23.no_rangka,ch23.id_tipe_kendaraan,ch23.id_warna,ch23.tgl_pembelian AS tgl_invoice,tipe_ahm,warna,ch23.tgl_pembelian, ($get_pekerjaan_kpb) AS kpb,wo.id_work_order,sa.tgl_servis,km_terakhir,($ass) AS kpb_ke,DATE_FORMAT(LEFT(sa.tgl_servis,10),'%d/%m/%Y') AS tgl_servis_indo,DATE_FORMAT(LEFT(ch23.tgl_pembelian,10),'%d/%m/%Y') AS tgl_pembelian_indo, (CASE WHEN ch23.no_mesin=tf.no_mesin_spasi and ch23.id_tipe_kendaraan=tf.kode_tipe and ch23.no_rangka=tf.no_rangka THEN '' ELSE '⚠' END) as icon
    FROM tr_h2_wo_dealer AS wo
    LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
    LEFT JOIN ms_warna wr ON wr.id_warna=ch23.id_warna
    LEFT JOIN tr_fkb tf ON tf.no_mesin_spasi = ch23.no_mesin
    LEFT JOIN tr_claim_kpb clk ON clk.no_mesin=ch23.no_mesin AND kpb_ke=($ass)
    $set_filter
    ");
  }
  public function getPekerjaanWOClaimKPB($filter = null)
  {
    $where = "WHERE 1=1 ";

    if ($filter != null) {
      if (isset($filter['id_work_order'])) {
        $where .= " AND wop.id_work_order='{$filter['id_work_order']}' ";
      }
      if (isset($filter['id_type'])) {
        $where .= " AND js.id_type='{$filter['id_type']}' ";
      }
      if (isset($filter['no_mesin'])) {
        $where .= " AND ch23.no_mesin='{$filter['no_mesin']}' ";
      }
    }
    $harga_material = "SELECT mp.harga_dealer_user FROM tr_h2_wo_dealer_parts prt 
        JOIN ms_part mp ON mp.id_part=prt.id_part
        WHERE prt.id_work_order=wop.id_work_order AND prt.id_jasa=wop.id_jasa LIMIT 1";

    $material = "SELECT prt.id_part FROM tr_h2_wo_dealer_parts prt 
        JOIN ms_part mp ON mp.id_part=prt.id_part
        WHERE prt.id_work_order=wop.id_work_order AND prt.id_jasa=wop.id_jasa LIMIT 1";

    $harga_jasa = "SELECT harga_jasa FROM ms_kpb_detail kpbd 
              WHERE id_tipe_kendaraan=ch23.id_tipe_kendaraan AND kpb_ke=RIGHT(js.id_type,1) LIMIT 1
    ";

    return $this->db->query("SELECT ($harga_material) AS harga_material,($material) AS id_part,($harga_jasa) AS harga_jasa
    FROM tr_h2_wo_dealer_pekerjaan wop
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
    $where ");
  }

  function getLKH($filter = null)
  {
    $where = "WHERE 1=1 ";

    if ($filter != null) {
      if (isset($filter['id_lkh'])) {
        $where .= " AND lkh.id_lkh='{$filter['id_lkh']}' ";
      }
      if (isset($filter['id_rekap_claim_null'])) {
        if ($filter['id_rekap_claim_null'] == true) {
          $where .= " AND rcw.id_rekap_claim IS NULL";
        }
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (lkh.id_lkh LIKE '%$search%'
              OR lkh.tgl_lkh LIKE '%$search%'
              OR dl.kode_dealer_md LIKE '%$search%'
              OR dl.nama_dealer LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'modalLHK') {
          $order_column = ['id_lkh', 'tgl_lkh', 'kode_dealer_md', 'nama_dealer', 'sa.no_claim_c2', 'ch23.id_tipe_kendaraan', 'tema', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY lkh.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $tgl_lkh = sql_date_dmy('tgl_lkh');
    $tgl_kejadian = sql_date_dmy('tgl_kejadian');
    $tgl_pembelian = sql_date_dmy('lkh.tgl_pembelian');
    return $this->db->query("SELECT lkh.id_lkh,($tgl_lkh) AS tgl_lkh,tech_serv_ahm,cc_tech_serv_ahm,lkh.id_dealer,kode_model,tema,part_utama,symptom_code,grade,pelapor,kepala_bengkel,gejala,file_ilustrasi,diagnosis,penyebab_utama,tindakan_sementara,kirim_part_rusak,$tgl_pembelian AS tgl_pembelian,$tgl_kejadian AS tgl_kejadian,jam,km,lkh.no_rangka,lkh.no_mesin,lkh.status,lkh.id_work_order,sa.no_buku_claim_c2,sa.no_claim_c2,sy.symptom_id,nama_part,lkh.kategori_claim,kode_dealer_md,nama_dealer,ch23.no_hp AS no_telepon,kab.id_kabupaten,kabupaten,ch23.id_tipe_kendaraan,kel.id_kelurahan,kelurahan,kecamatan,kel.kode_pos,ch23.alamat,ch23.no_hp,sa.keluhan_konsumen,sa.id_sa_form,ongkos_kerja
    FROM tr_lkh lkh
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_work_order=lkh.id_work_order
    LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=ch23.id_kelurahan
    LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
    LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_symptom sy ON sy.id_symptom=lkh.symptom_code
    LEFT JOIN ms_part prt ON prt.id_part=lkh.part_utama
    LEFT JOIN ms_dealer dl ON dl.id_dealer=lkh.id_dealer
    LEFT JOIN tr_rekap_claim_waranty rcw ON rcw.no_lkh=lkh.id_lkh
    $where $order $limit");
  }
  function getLKHPartTerkait($filter = null)
  {
    $where = "WHERE 1=1 ";

    if ($filter != null) {
      if (isset($filter['id_lkh'])) {
        $where .= " AND lkhp.id_lkh='{$filter['id_lkh']}' ";
      }
    }
    return $this->db->query("SELECT lkhp.id_lkh,lkhp.id_part,nama_part
    FROM tr_lkh_part_terkait lkhp
    JOIN ms_part prt ON prt.id_part=lkhp.id_part
    $where ");
  }
  function getRekapClaimWarranty($filter = null)
  {
    $where = "WHERE 1=1 ";
    $group = 'GROUP BY rcw.no_registrasi';
    $order = " ";
    // send_json($filter);
    if ($filter != null) {
      $join = '';
      $tgl_lkh               = sql_date_dmy('lkh.tgl_lkh');
      $tgl_pembelian         = sql_date_dmy('lkh.tgl_pembelian');
      $tgl_pengajuan         = sql_date_dmy('rcw.tgl_pengajuan');
      $tgl_kerusakan         = sql_date_dmy('rcw.tgl_kerusakan');
      $tgl_perbaikan         = sql_date_dmy('rcw.tgl_perbaikan');
      $tgl_selesai_perbaikan = sql_date_dmy('rcw.tgl_selesai_perbaikan');
      $tgl_lbpc              = sql_date_dmy('lbpc.tgl_lbpc');
      $start_date            = sql_date_dmy('lbpc.start_date');
      $end_date              = sql_date_dmy('lbpc.end_date');
      if (isset($filter['select'])) {
        $select = "SELECT rcw.no_registrasi,rcw.ktg_claim,rcw.kelompok_pengajuan,dl.kode_dealer_md,'E20' AS kode_md,ch23.no_rangka,$tgl_pembelian tgl_pembelian,$tgl_kerusakan tgl_kerusakan,rcw.km_kerusakan,ch23.nama_customer,ch23.alamat,kel.kelurahan,kec.kecamatan,kel.kode_pos, kab.id_kabupaten,ch23.no_hp,$tgl_perbaikan tgl_perbaikan,$tgl_selesai_perbaikan tgl_selesai_perbaikan,km_perbaikan,uraian_gejala_kerusakan,rcw.id_symptom,rcw.rank,''kode_kerusakan,rcwd.id_part,rcwd.qty,rcwd.tipe_penggantian,rcwd.harga,rcwd.ongkos,CASE WHEN rcwd.status_part=1 THEN 'M' ELSE 'R' END AS status_part,rcw.no_lkh,''id_hlo,$tgl_lkh tgl_lkh,'' AS tgl_ho,ch23.no_polisi,'' AS kode_produksi_part,rekomendasi_sa,$tgl_pengajuan AS tgl_pengajuan,lbpc.no_lbpc,lbpc.no_lbpc_ahass_to_md";
      } else {
        $select = "SELECT rcw.no_registrasi,rcw.id_rekap_claim,rcw.id_dealer,rcw.ktg_claim,rcw.sub_ktg_claim,rcw.no_lkh,rcw.no_registrasi,dl.kode_dealer_md,rcw.kelompok_pengajuan,$tgl_pembelian AS tgl_pembelian, nama_dealer,lkh.no_mesin,lkh.no_rangka,$tgl_lkh AS tgl_lkh,$tgl_pengajuan AS tgl_pengajuan,$tgl_kerusakan AS tgl_kerusakan,km_kerusakan,ch23.alamat,kel.kode_pos,ch23.no_hp AS no_telepon,rcw.kode_area,$tgl_perbaikan AS tgl_perbaikan,$tgl_selesai_perbaikan AS tgl_selesai_perbaikan,km_perbaikan,uraian_gejala_kerusakan,rcw.rank,ch23.id_kelurahan,kel.kelurahan,kabupaten,alasan_reject,rcw.id_symptom,symptom_id,part_utama,prt.nama_part,rcw.no_lbpc,$tgl_lbpc AS tgl_lbpc,$start_date AS start_date,$end_date AS end_date,rcw.id_ptca,'' AS status,lbpc.no_lbpc,lbpc.no_lbpc_ahass_to_md";
      }

      if (isset($filter['join_rekap_claim_part'])) {
        $join .= " LEFT JOIN tr_rekap_claim_waranty_detail rcwd ON rcwd.id_rekap_claim=rcw.id_rekap_claim";
      }
      if (isset($filter['join_rekap_tagihan'])) {
        $select .= ", rptca.start_date,rptca.end_date,rptca.status,rptca.tgl_ptca";
        $join .= " LEFT JOIN tr_rekap_tagihan_ptca rptca ON rptca.id_ptca=rcw.id_ptca";
      }
      if (isset($filter['id_rekap_claim'])) {
        $where .= " AND rcw.id_rekap_claim='{$filter['id_rekap_claim']}' ";
      }
      if (isset($filter['no_claim_in'])) {
        $where .= " AND rcw.id_rekap_claim IN ({$filter['no_claim_in']}) ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND rcw.id_dealer='{$filter['id_dealer']}' ";
      }
      if (isset($filter['no_lbpc'])) {
        $where .= " AND rcw.no_lbpc='{$filter['no_lbpc']}' ";
      }
      if (isset($filter['id_ptca'])) {
        $where .= " AND rcw.id_ptca='{$filter['id_ptca']}' ";
      }
      if (isset($filter['status_rekap_claim'])) {
        $where .= " AND rcw.status='{$filter['status_rekap_claim']}' ";
      }
      if (isset($filter['kelompok_pengajuan'])) {
        $where .= " AND rcw.kelompok_pengajuan='{$filter['kelompok_pengajuan']}' ";
      }
      if (isset($filter['periode_pengajuan'])) {
        if (isset($filter['start_date']) && isset($filter['end_date'])) {
          // $where .= " AND rcw.tgl_pengajuan BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}' ";
        }
      }
      if (isset($filter['periode_lbpc'])) {
        if (isset($filter['start_date']) && isset($filter['end_date'])) {
          $where .= " AND lbpc.tgl_lbpc BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}' ";
        }
      }
      if (isset($filter['lbpc_null'])) {
        $where .= " AND rcw.no_lbpc IS NULL";
      }
      if (isset($filter['lbpc_not_null'])) {
        $where .= " AND rcw.no_lbpc IS NOT NULL";
      }
      if (isset($filter['ptca_not_null'])) {
        $where .= " AND rcw.id_ptca IS NOT NULL";
      }
      if (isset($filter['ptca_null'])) {
        $where .= " AND rcw.id_ptca IS NULL";
      }

      if (isset($filter['ceklist_lbpc'])) {
        $select .= ", CASE 
                      WHEN rcw.no_lbpc IS NULL THEN 0
                      ELSE 1
                    END AS ceklist ";
      }
      if (isset($filter['ceklist_ptca'])) {
        $select .= ", CASE 
                      WHEN rcw.id_ptca IS NULL THEN 0
                      ELSE 1
                    END AS ceklist ";
      }
      if (isset($filter['get_summary'])) {
        $nilai_part = "SELECT SUM(harga*qty) FROM tr_rekap_claim_waranty_detail WHERE id_rekap_claim=rcw.id_rekap_claim";
        $nilai_jasa = "SELECT SUM(ongkos) FROM tr_rekap_claim_waranty_detail WHERE id_rekap_claim=rcw.id_rekap_claim";
        $nilai_pokok = "($nilai_part)+($nilai_jasa)";
        $persen_ppn = getPPN(0.1,false);
        $nilai_ppn = "CASE 
                        WHEN dl.kode_dealer_md='0' THEN 0
                        WHEN dl.kode_dealer_md='0' THEN 0
                        WHEN dl.kode_dealer_md='0' THEN 0
                        WHEN dl.kode_dealer_md='0' THEN 0
                        WHEN dl.kode_dealer_md='0' THEN 0
                        WHEN dl.kode_dealer_md='0' THEN 0
                      ELSE (($nilai_part)+($nilai_jasa)) * $persen_ppn
                      END
                      ";
        $nilai_pokok_ppn = "(($nilai_pokok)+ROUND(($nilai_ppn)))";
        $nilai_pph = "CASE 
        WHEN dl.kode_dealer_md='00888' THEN 0
        WHEN dl.kode_dealer_md='05391' THEN 0
        WHEN dl.kode_dealer_md='11791' THEN 0
        WHEN dl.kode_dealer_md='12203' THEN 0
        WHEN dl.kode_dealer_md='05545' THEN 0
        WHEN dl.kode_dealer_md='05529' THEN 0
      ELSE
        CASE WHEN rcw.pkp_dealer=1 THEN ($nilai_jasa)*0.02
                           ELSE ($nilai_jasa)*0.04
                      END END
                      ";
        $total = "($nilai_part)+($nilai_jasa)+($nilai_ppn)-($nilai_pph)";
        $select .= ", ($nilai_part) AS nilai_part, ($nilai_jasa) AS nilai_jasa,($nilai_pokok) AS nilai_pokok, ROUND(($nilai_ppn)) AS nilai_ppn,($nilai_pokok_ppn) AS nilai_pokok_ppn, ROUND($nilai_pph) AS nilai_pph,($total) AS total";
      }
      //GROUP BY
      if (isset($filter['group_by_dealer'])) {
        $group .= " ,rcw.id_dealer ";
      }
      if (isset($filter['order_by'])) {
        $order .= "ORDER BY rcw.created_at DESC ";
      }
    }

    return $this->db->query("$select
    FROM tr_rekap_claim_waranty rcw
    JOIN tr_lkh lkh ON lkh.id_lkh=rcw.no_lkh
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_work_order=lkh.id_work_order
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    JOIN ms_kelurahan kel ON kel.id_kelurahan=ch23.id_kelurahan
    JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
    JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_symptom sy ON sy.id_symptom=rcw.id_symptom
    JOIN ms_part prt ON prt.id_part=lkh.part_utama
    JOIN ms_dealer dl ON dl.id_dealer=rcw.id_dealer
    LEFT JOIN tr_lbpc lbpc ON lbpc.no_lbpc=rcw.no_lbpc
    $join
    $where 
    $group
    $order
    
    ");
  }
  function getRekapClaimWarrantyParts($filter = null)
  {
    // send_json($filter);
    $where = "WHERE 1=1 ";
    $group = '';

    if ($filter != null) {
      if (isset($filter['id_rekap_claim'])) {
        $where .= " AND rcw.id_rekap_claim='{$filter['id_rekap_claim']}' ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND rcw.id_dealer='{$filter['id_dealer']}' ";
      }
      if (isset($filter['no_lbpc'])) {
        $where .= " AND rcw.no_lbpc='{$filter['no_lbpc']}' ";
      }
      if (isset($filter['no_ptcd'])) {
        $where .= " AND rcwp.no_ptcd='{$filter['no_ptcd']}' ";
      }
      if (isset($filter['kelompok_pengajuan'])) {
        $where .= " AND rcw.kelompok_pengajuan='{$filter['kelompok_pengajuan']}' ";
      }
      if (isset($filter['periode_pengajuan'])) {
        if (isset($filter['start_date']) && isset($filter['end_date'])) {
          // $where .= " AND rcw.tgl_pengajuan BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}' ";
        }
      }
      if (isset($filter['lbpc_null'])) {
        $where .= " AND rcw.no_lbpc IS NULL";
      }
      if (isset($filter['lbpc_not_null'])) {
        $where .= " AND rcw.no_lbpc IS NOT NULL";
      }
      if (isset($filter['periode_lbpc'])) {
        if (isset($filter['start_date']) && isset($filter['end_date'])) {
          $where .= " AND lbpc.tgl_lbpc BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}' ";
        }
      }
      if (isset($filter['no_claim_in'])) {
        $where .= " AND rcw.id_rekap_claim IN ({$filter['no_claim_in']}) ";
      }


      //GROUP BY
      if (isset($filter['group_by_dealer'])) {
        $group .= " GROUP BY rcw.id_dealer";
      }
    }
    $count = "SELECT COUNT(id_rekap_claim) FROM tr_rekap_claim_waranty_detail WHERE id_rekap_claim=rcwp.id_rekap_claim";
    $ganti_uang = "CASE
      WHEN tipe_penggantian='U' THEN (harga*qty)
      ELSE 0
    END";
    $ganti_part = "CASE
      WHEN tipe_penggantian='P' THEN (harga*qty)
      ELSE 0
    END";
    $nilai_part = "(rcwp.harga*rcwp.qty)";
    $ppn = "CASE 
      WHEN dl.kode_dealer_md='00888' THEN 0
      WHEN dl.kode_dealer_md='05391' THEN 0
      WHEN dl.kode_dealer_md='11791' THEN 0
      WHEN dl.kode_dealer_md='12203' THEN 0
      WHEN dl.kode_dealer_md='05545' THEN 0
      WHEN dl.kode_dealer_md='05529' THEN 0
    ELSE (($nilai_part)+IFNULL(rcwp.ongkos,0)) * 0.1
    END";
    $pph = "CASE WHEN rcw.pkp_dealer=1 THEN IFNULL(rcwp.ongkos,0)*0.02
          ELSE IFNULL(rcwp.ongkos,0)*0.04
    END
    ";
    $total = "($nilai_part+IFNULL(rcwp.ongkos,0)+$ppn-$pph)";
    return $this->db->query("SELECT rcwp.id_rekap_claim,rcwp.id_part,rcwp.harga,rcwp.status_part,rcwp.qty,rcwp.tipe_penggantian,rcwp.ongkos,prt.nama_part,rcw.no_registrasi,
    $ganti_uang AS ganti_uang,
    $ganti_part AS ganti_part,
    $nilai_part AS nilai_part,
    ROUND($ppn) AS ppn,
    ROUND($pph) AS pph,
    $total AS total,
    ($count) AS count_id_rekap,rcw.id_dealer,dl.kode_dealer_md,dl.nama_dealer,
    rcwp.jml_accept,rcwp.no_ptcd,lkh.no_rangka,rcw.no_lbpc
    FROM tr_rekap_claim_waranty_detail rcwp
    JOIN tr_rekap_claim_waranty rcw ON rcw.id_rekap_claim=rcwp.id_rekap_claim
    JOIN tr_lkh lkh ON lkh.id_lkh=rcw.no_lkh
    JOIN ms_part prt ON prt.id_part=rcwp.id_part
    LEFT JOIN tr_lbpc lbpc ON lbpc.no_lbpc=rcw.no_lbpc
    JOIN ms_dealer dl ON dl.id_dealer=rcw.id_dealer
    $where 
    $group
    ");
  }

  function getLBPC($filter = null)
  {
    $where = "WHERE 1=1 ";

    if ($filter != null) {
      if (isset($filter['no_lbpc'])) {
        $where .= " AND lbpc.no_lbpc='{$filter['no_lbpc']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['id_ptca'])) {
        $where .= " AND lbpc.id_ptca='{$filter['id_ptca']}' ";
      }
    }
    if ($filter != null) {
      if (isset($filter['ptca_not_null'])) {
        $where .= " AND lbpc.id_ptca IS NOT NULL ";
      }
    }
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = [NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY lbpc.no_lbpc DESC ";
      }
    } else {
      $order = '';
    }
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $tgl_lbpc = sql_date_dmy('tgl_lbpc');
    $start_date = sql_date_dmy('start_date');
    $end_date = sql_date_dmy('end_date');
    return $this->db->query("SELECT no_lbpc,no_lbpc_ahass_to_md,$start_date AS start_date,$end_date AS end_date,kelompok_pengajuan,$tgl_lbpc AS tgl_lbpc,lbpc.status
    FROM tr_lbpc lbpc
    $where 
    $order
    $limit
    ");
  }
  function getLBPCDetail($filter = null)
  {
    $where = "WHERE 1=1 ";

    if ($filter != null) {
      if (isset($filter['no_lbpc'])) {
        $where .= " AND wrt.no_lbpc='{$filter['no_lbpc']}' ";
      }
    }
    $tgl_pengajuan = sql_date_dmy('tgl_pengajuan');
    $tgl_kerusakan = sql_date_dmy('tgl_kerusakan');
    $tgl_pembelian = sql_date_dmy('tgl_pembelian');
    return $this->db->query("SELECT no_lbpc,no_registrasi,$tgl_pengajuan AS tgl_pengajuan,wrt.id_dealer,kode_dealer_md,nama_dealer,no_mesin,no_rangka,$tgl_pembelian AS tgl_pembelian,$tgl_kerusakan AS tgl_kerusakan,wrt.id_rekap_claim,1 AS ceklist
    FROM tr_rekap_claim_waranty wrt
    JOIN tr_lkh lkh ON lkh.id_lkh=wrt.no_lkh
    JOIN ms_dealer dl ON dl.id_dealer=wrt.id_dealer
    $where ");
  }

  function getPOKPB($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND po.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_po_kpb'])) {
      if ($filter['id_po_kpb'] != '') {
        $where .= " AND po.id_po_kpb='{$filter['id_po_kpb']}'";
      }
    }
    if (isset($filter['tgl_po_kpb'])) {
      if ($filter['tgl_po_kpb'] != '') {
        $where .= " AND po.tgl_po_kpb='{$filter['tgl_po_kpb']}'";
      }
    }
    if (isset($filter['status'])) {
      if ($filter['status'] != '') {
        $where .= " AND po.status='{$filter['status']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dl.kode_dealer_md LIKE '%$search%'
              OR dl.nama_dealer LIKE '%$search%'
              OR po.id_po_kpb LIKE '%$search%'
              OR po.tgl_po_kpb LIKE '%$search%'
              OR po.status LIKE '%$search%'
              OR po.grand_total LIKE '%$search%'
              OR po.tot_qty LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.grand_total', 'po.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY po.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_po_kpb,tgl_po_kpb,po.start_date,po.end_date,kode_dealer_md,nama_dealer,po.id_dealer,po.created_at,po.status,tot_qty,ppn,grand_total
    FROM tr_po_kpb po
    JOIN ms_dealer dl ON dl.id_dealer=po.id_dealer
    $where $order $limit
    ");
  }
  function getPOKPBDetail($filter)
  {
    $where = 'WHERE 1=1 ';
    $select = '';
    if (isset($filter['id_po_kpb'])) {
      if ($filter['id_po_kpb'] != '') {
        $where .= " AND pod.id_po_kpb='{$filter['id_po_kpb']}'";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND po.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan_in'])) {
      $where .= " AND pod.id_tipe_kendaraan IN({$filter['id_tipe_kendaraan_in']})";
    }
    if (isset($filter['summary_qty'])) {
      $select .= ",SUM(qty) AS sum_qty";
    }
    if (isset($filter['periode_kpb'])) {
      $tgl = $filter['periode_kpb'];
      $where .= " AND po.tgl_po_kpb BETWEEN '{$tgl[0]}' AND '{$tgl[1]}'";
    }

    return $this->db->query("SELECT pod.id_detail, pod.id_part,nama_part,pod.id_tipe_kendaraan,pod.harga_material, tk.tipe_ahm, qty,(harga_material-diskon) AS harga_setelah_diskon, tk.no_mesin as no_mesin_5, ((harga_material-diskon) * qty) AS total,tipe_diskon diskon_tipe,pod.diskon $select
    FROM tr_po_kpb_detail pod
    JOIN tr_po_kpb po ON po.id_po_kpb=pod.id_po_kpb
    JOIN ms_part prt ON prt.id_part=pod.id_part
    join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = pod.id_tipe_kendaraan
    $where
    ");
  }
  function getClaimGenerated($filter)
  {
    // send_json($filter);
    $where = 'WHERE 1=1';
    if (isset($filter['part_not_null'])) {
      $where .= " AND prt.id_part is not null";
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND ck.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['status'])) {
      if ($filter['status'] != '') {
        $where .= " AND ckgd.status='{$filter['status']}'";
      }
    }
    if (isset($filter['no_surat_claim'])) {
      if ($filter['no_surat_claim'] != '') {
        $where .= " AND ckg.no_surat_claim='{$filter['no_surat_claim']}'";
      }
    }
    if (isset($filter['no_mesin'])) {
      if ($filter['no_mesin'] != '') {
        $where .= " AND tk.no_mesin='{$filter['no_mesin']}'";
      }
    }
    if (isset($filter['no_mesin_5'])) {
      $where .= " AND (tk.no_mesin='{$filter['no_mesin_5']}')";
      // $where .= " AND (tk.no_mesin='{$filter['no_mesin_5']}' AND LEFT(ck.no_mesin,5)='{$filter['no_mesin_5']}')";
    }
    if (isset($filter['kpb_ke'])) {
      if ($filter['kpb_ke'] != '') {
        $where .= " AND ck.kpb_ke='{$filter['kpb_ke']}'";
      }
    }
    if (isset($filter['status_reject'])) {
      if ($filter['status_reject'] != '') {
        $where .= " AND ckgd.status!='reject'";
      }
    }
    if (isset($filter['id_po_kpb_null'])) {
      if ($filter['id_po_kpb_null'] != '') {
        $where .= " AND ckgd.id_po_kpb IS NULL";
      }
    }
    if (isset($filter['id_po_kpb_not_null'])) {
      if ($filter['id_po_kpb_not_null'] != '') {
        $where .= " AND ckgd.id_po_kpb IS NOT NULL";
      }
    }
    if (isset($filter['periode_servis'])) {
      if ($filter['periode_servis'] != '') {
        $where .= " AND ck.tgl_service BETWEEN '{$filter['start_date']}' AND '{$filter['end_date']}'";
      }
    }
    if (isset($filter['chk_reject'])) {
      if ($filter['chk_reject'] != '') {
        $where .= " AND ckgd.chk_reject='{$filter['chk_reject']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan_in'])) {
      $where .= " AND ck.id_tipe_kendaraan IN({$filter['id_tipe_kendaraan_in']})";
    }
    $group = '';
    $qty = 0;
    if (isset($filter['group_by_part'])) {
      $group .= " GROUP BY ck.id_part";
      $qty = "COUNT(ck.id_part)";
    }
    if (isset($filter['group_by_no_mesin_5_digit'])) {
      $group .= " GROUP BY tk.no_mesin";
    }
    if (isset($filter['group_by_tipe_kendaraan'])) {
      $group .= " GROUP BY ck.id_tipe_kendaraan";
      $qty = "COUNT(ck.id_tipe_kendaraan)";
    }

    if (isset($filter['group_by_tipe_kendaraan_part'])) {
      $group .= " GROUP BY ck.id_tipe_kendaraan, cko.id_part";
      $qty = "SUM(cko.qty)";
    }
    $diskon = 0;

    if (isset($filter['select'])) {
      $qty = "COUNT(ck.no_mesin)";

      if ($filter['select'] == 'select_lap_tanda_terima') {
        $tot_insentif_oli   = "CASE WHEN ck.kpb_ke=1 THEN (IFNULL(ms_kpb.insentif_oli,0)*$qty) ELSE 0 END";
        $harga_jasa = "CASE WHEN tk.id_tipe_kendaraan IS NOT NULL THEN 
                        (SELECT harga_jasa FROM ms_kpb_detail WHERE id_tipe_kendaraan=tk.id_tipe_kendaraan AND kpb_ke=ck.kpb_ke)
                     ELSE
                        (SELECT harga_jasa FROM ms_kpb_detail kd JOIN ms_tipe_kendaraan tkd ON tkd.id_tipe_kendaraan=kd.id_tipe_kendaraan WHERE kpb_ke={$filter['kpb_ke']} AND tkd.no_mesin='{$filter['no_mesin_5']}' LIMIT 1)
                     END
                      ";
        $tot_jasa   = "($qty*($harga_jasa))";
        $tot_all    = "($tot_jasa+$tot_insentif_oli)";
        $amn_ppn    = getPPN(0.1);
        $ppn        = "CASE WHEN dl.PKP = 'Ya' THEN $tot_all * $amn_ppn ELSE 0 END";
        $set_pph    = "CASE WHEN dl.kode_dealer_md IN ('07443','TP-P001','08998','06782','00804','01318','KRC-A004','08997','08994','01310','07442','08880','SB-K003') THEN 0.04 ELSE 0.02 END";
        // $set_pph    = "CASE WHEN dl.kode_dealer_md IN ('00888','11791','12203','05391','05529','05545') THEN 0.02 ELSE 0.04 END";
        // $pph        = "CASE WHEN (dl.PKP = 'Ya' AND IFNULL(dl.npwp,'')!='') THEN $tot_jasa * 0.02 ELSE $tot_jasa * $set_pph END";
        $pph        = "CASE WHEN (dl.PKP = 'Ya' AND IFNULL(dl.npwp,'')!='') THEN $tot_jasa * $set_pph ELSE $tot_jasa * $set_pph END";
        $qty_oli    = "COUNT(prt.id_part)";
        $sub_total  = "($tot_jasa+$tot_insentif_oli+$ppn)-$pph";
        $select = "$qty AS qty,
                  ($harga_jasa)harga_jasa,
                  cko.harga harga_material,
                  CASE WHEN ck.kpb_ke=1 THEN IFNULL(ms_kpb.insentif_oli,0) ELSE 0 END AS insentif_oli,
                  $tot_jasa AS tot_jasa,
                  $tot_insentif_oli AS tot_insentif_oli,
                  0 AS tot_oli,
                  $ppn AS ppn,
                  $pph AS pph,
                  $tot_all AS tot_all,
                  IFNULL($sub_total,0) sub_total
                  ";
      } elseif ($filter['select'] == 'select_rekap_all_tagihan_kpb') {
        $select = "$qty AS qty";
      }elseif ($filter['select'] == 'tot_oli_1000') {
        $harga = "SELECT harga_oli FROM ms_kpb WHERE id_tipe_kendaraan=ck.id_tipe_kendaraan AND ms_kpb.status=1";
        $select = "SUM(($harga)) AS tot_oli_1000";
      } elseif ($filter['select'] == 'select_lap_pembayaran_ke_ahass') {
        $qty_oli            = "COUNT(prt.id_part)";
        $select = "$qty AS qty, $qty_oli AS qty_oli";
      } elseif ($filter['select'] == 'select_lap_pembayaran_ke_ahass_total') {
        $select = "($qty) tot_qty";
      } elseif ($filter['select'] == 'sum_qty') {
        $select = "($qty) sum_qty";
      }
    } else {
      $select = "cko.id_part,nama_part,ck.id_tipe_kendaraan,cko.harga harga_material,($qty) AS qty,
            ($diskon) AS diskon,cko.het,
            (cko.harga-($diskon)) AS harga_setelah_diskon,
            ((cko.harga-($diskon)) * $qty) AS total,
            ckgd.id_detail,ckgd.id_claim_kpb,
            ck.no_mesin,ck.no_rangka,
            tk.tipe_ahm,
      CASE 
        WHEN ck.no_kpb IS NULL THEN 0 
        WHEN ck.no_kpb ='' THEN 0 
        ELSE ck.no_kpb 
      END AS no_kpb,
      ck.tgl_beli_smh,ck.km_service,ck.tgl_service,ck.kpb_ke,tk.no_mesin AS no_mesin_5";
    }
    // $where = ''; //Testing skip filter
    return $this->db->query("SELECT $select
    FROM tr_claim_kpb_generate_detail ckgd
    JOIN tr_claim_kpb_generate ckg ON ckg.no_generate=ckgd.no_generate AND ckgd.tahun=ckg.tahun
		JOIN tr_claim_kpb ck ON ck.id_claim_kpb=ckgd.id_claim_kpb
    LEFT JOIN tr_claim_kpb_oli cko ON cko.no_mesin=ck.no_mesin AND cko.kpb_ke=ck.kpb_ke
    LEFT JOIN ms_part prt ON prt.id_part=cko.id_part
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ck.id_tipe_kendaraan
    left JOIN ms_kpb ON ms_kpb.id_tipe_kendaraan=tk.id_tipe_kendaraan
    JOIN ms_dealer dl ON dl.id_dealer=ck.id_dealer
    $where 
    $group
    order by tk.tipe_ahm asc
    ");
  }

  function get_id_po_kpb()
  {
    $ym       = date('Y-m');
    $get_data  = $this->db->query("SELECT id_po_kpb FROM tr_po_kpb po
			WHERE LEFT(created_at,7)='$ym'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->id_po_kpb, -4);
      $new_kode   = 'PO-KPB/' . $ym . '/' . sprintf("%'.04d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_po_kpb', ['id_po_kpb' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = 'PO-KPB/' . $ym . '/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'PO-KPB/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }
  function get_id_lkh()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $ymd     = date('Y-m-d');
    $ymd2     = date('ymd');
    $get_data  = $this->db->query("SELECT * FROM tr_lkh
			WHERE LEFT(created_at,7)='$th_bln' 
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_lkh   = substr($row->id_lkh, -5);
      $new_kode = $th_bln . '/LKH/' . sprintf("%'.05d", $id_lkh + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_lkh', ['id_lkh' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = $th_bln . '/LKH/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $th_bln . '/LKH/00001';
    }
    return strtoupper($new_kode);
  }
  function getTTPK($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['no_ttpk'])) {
      if ($filter['no_ttpk'] != '') {
        $where .= " AND ttpk.no_ttpk='{$filter['no_ttpk']}'";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $where .= " AND dl.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['tgl_ttpk'])) {
      if ($filter['tgl_ttpk'] != '') {
        $where .= " AND ttpk.ttpk_date='{$filter['tgl_ttpk']}'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dl.kode_dealer_md LIKE '%$search%'
              OR dl.nama_dealer LIKE '%$search%'
              OR po.id_po_kpb LIKE '%$search%'
              OR po.tgl_po_kpb LIKE '%$search%'
              OR po.status LIKE '%$search%'
              OR po.tot_harga LIKE '%$search%'
              OR po.tot_qty LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.tot_harga', 'po.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY ttpk.upload_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT ttpk.*,nama_dealer
    FROM tr_ttpk ttpk
    LEFT JOIN ms_dealer dl ON dl.kode_dealer_md=ttpk.kode_dealer_md
    $where $order $limit
    ");
  }

  function getTTPKFinance($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['no_ttpk'])) {
      if ($filter['no_ttpk'] != '') {
        $where .= " AND ttpk.no_ttpk='{$filter['no_ttpk']}'";
      }
    }
    // if (isset($filter['id_dealer'])) {
    //   if ($filter['id_dealer'] != '') {
    //     $where .= " AND dl.id_dealer='{$filter['id_dealer']}'";
    //   }
    // }
    if (isset($filter['tgl_ttpk'])) {
      if ($filter['tgl_ttpk'] != '') {
        $where .= " AND ttpk.tgl_ttpk='{$filter['tgl_ttpk']}'";
      }
    }
    if (isset($filter['status_ttpk_finance'])) {
      if ($filter['status_ttpk_finance'] != '') {
        if ($filter['status_ttpk_finance'] == 'NULL') {
          $where .= " AND ttpk.status IS NULL";
        } else {
          $where .= " AND ttpk.status='{$filter['status_ttpk_finance']}'";
        }
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dl.kode_dealer_md LIKE '%$search%'
              OR dl.nama_dealer LIKE '%$search%'
              OR po.id_po_kpb LIKE '%$search%'
              OR po.tgl_po_kpb LIKE '%$search%'
              OR po.status LIKE '%$search%'
              OR po.tot_harga LIKE '%$search%'
              OR po.tot_qty LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['no_ttpk', 'no_surat_claim', 'tgl_ttpk', 'amount_material', 'amount_jasa', 'amount_pokok', 'ppn', 'nilai_pokok_ppn', 'nilai_pph', 'total_dibayar', 'status'];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY ttpk.upload_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT ttpk.*
    FROM tr_ttpk_finance ttpk
    -- LEFT JOIN ms_dealer dl ON dl.kode_dealer_md=ttpk.kode_dealer_md
    $where $order $limit
    ");
  }

  function getKPBTipeKendaraan($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.tot_harga', 'po.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tk.id_tipe_kendaraan ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT tk.id_tipe_kendaraan,tipe_ahm,no_mesin,kpb.id_tipe_kendaraan AS set_kpb, tk.n_kpb
    FROM ms_tipe_kendaraan tk
    LEFT JOIN ms_kpb kpb ON kpb.id_tipe_kendaraan=tk.id_tipe_kendaraan AND kpb.status=1
    $where $order $limit
    ");
  }
  function getMasterKPB($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
              OR tk.tipe_ahm LIKE '%$search%'
              OR tk.no_mesin LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['id_po_kpb', 'tgl_po_kpb', 'kode_dealer_md', 'nama_dealer', 'po.tot_qty', 'po.tot_harga', 'po.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tk.id_tipe_kendaraan ASC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT kpb.*,tipe_ahm,no_mesin
    FROM ms_kpb kpb
    LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=kpb.id_tipe_kendaraan
    $where $order $limit
    ");
  }

  function get_statistik_6_terakhir($params = NULL)
  {
    if (isset($params['awal'])) {
      $end = strtotime(date($params['awal']));
    } else {
      $end = strtotime(date("Y-m-01"));
    }
    // $end = strtotime("+1 months", $end);
    $month = strtotime("-6 months", $end);

    $periode = 1;
    while ($end > $month) {
      $end = strtotime("-1 month", $end);
      $filter_statistik = [
        'id_part_5' => $params['id_part_5'],
        'tgl_pengajuan_ym' => date('Y-m', $end),
        'select' => 'select_sum_qty'
      ];
      $jml = $this->getStatistikClaimPartUtama($filter_statistik)->row()->sum;
      if (!isset($filter_statistik['id_part_5'])) {
        $jml = 0;
      }
      $statistik[] = [
        'bulan'   => date('m', $end),
        'periode' => $periode,
        // 'bulan_teks' => getbln(date('m',$end)),
        'jml_kejadian' => $jml
      ];
      $periode++;
    }
    return $statistik;
  }

  function getStatistikClaimPartUtama($filter)
  {
    $where = "WHERE status_part=1 ";
    $select = "id_part,harga,qty,ongkos";

    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_sum_qty') {
        $select = "IFNULL(SUM(qty),0) AS sum";
      }
    }

    if (isset($filter['id_part_5'])) {
      $where .= " AND LEFT(id_part,5)='{$filter['id_part_5']}'";
    }
    if (isset($filter['tgl_pengajuan_ym'])) {
      $where .= " AND LEFT(tgl_pengajuan,7)='{$filter['tgl_pengajuan_ym']}'";
    }
    return $this->db->query("SELECT $select 
      FROM tr_rekap_claim_waranty_detail rcd
      JOIN tr_rekap_claim_waranty rc ON rc.id_rekap_claim=rcd.id_rekap_claim
      $where
    ");
  }

  function getClaimGenerateHeader($filter)
  {
    $where = 'WHERE 1=1 ';

    if (isset($filter['no_ckg'])) {
      if ($filter['no_ckg'] != '') {
        $where .= " AND ckg.no_ckg='{$filter['no_ckg']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (ckg.status LIKE '%$search%'
              OR tahun LIKE '%$search%'
              OR no_generate LIKE '%$search%'
              OR no_surat_claim LIKE '%$search%'
              OR tgl_generate LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view_verifikasi') {
          $order_column = ['nama_file', 'no_surat_claim', 'tgl_generate', 'status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY ckg.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT ckg.*
    FROM tr_claim_kpb_generate ckg
    $where $order $limit
    ");
  }
}
