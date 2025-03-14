<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_billing extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_work_order', 'm_wo');
  }

  function getNSC($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE nsc.id_dealer='$id_dealer' ";

    $nama_customer = "
      CASE 
        WHEN wo.id_work_order IS NULL THEN so.nama_pembeli
        ELSE ch23.nama_customer
      END
      ";
    $id_customer = "CASE 
      WHEN ch23_so.id_customer_int IS NOT NULL THEN ch23_so.id_customer
      WHEN ch23.id_customer IS NOT NULL THEN ch23.id_customer
      ELSE ''
    END";

    if ($filter != null) {
      if (isset($filter['no_nsc'])) {
        $where .= " AND nsc.no_nsc='{$filter['no_nsc']}' ";
      }
      if (isset($filter['nomor_so'])) {
        $where .= " AND nsc.id_referensi='{$filter['nomor_so']}' ";
      }
      if (isset($filter['referensi'])) {
        $where .= " AND nsc.referensi='{$filter['referensi']}' ";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND nsc.id_referensi='{$filter['id_work_order']}' ";
      }
      if (isset($filter['tgl_nsc'])) {
        $where .= " AND nsc.tgl_nsc='{$filter['tgl_nsc']}' ";
      }
      if (isset($filter['tgl_transaksi'])) {
        $where .= " AND nsc.tgl_nsc='{$filter['tgl_transaksi']}' ";
      }
      if (isset($filter['no_nsc_or_id_wo'])) {
        $where .= " AND (nsc.id_referensi='{$filter['no_nsc_or_id_wo']}' OR nsc.no_nsc='{$filter['no_nsc_or_id_wo']}') ";
      }
      if (isset($filter['po_id'])) {
        $where .= " AND po.po_id='{$filter['po_id']}' ";
      }
      if (isset($filter['level_satisfaction_null'])) {
        $where .= " AND  NOT EXISTS(SELECT id_referensi FROM tr_h2_service_satisfaction AS tss WHERE id_referensi=nsc.id_referensi)";
      }

      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $search = $filter['search'];
          $where .= " AND ($nama_customer LIKE '%$search%'
                            OR $id_customer LIKE '%$search%'
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
          $where .= " ORDER BY $order_clm $order_by ";
        } else {
          $where .= " ORDER BY nsc.created_at DESC ";
        }
      } else {
        $where .= " ORDER BY nsc.created_at DESC ";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $where .= ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT nsc.no_nsc,DATE_FORMAT(nsc.tgl_nsc,'%d/%m/%Y') AS tgl_nsc,referensi,nsc.id_referensi,
      $nama_customer AS nama_customer,
      $id_customer AS id_customer,
      CASE 
        WHEN ch23_so.id_tipe_kendaraan IS NOT NULL THEN ch23_so.id_tipe_kendaraan
        WHEN ch23.id_tipe_kendaraan IS NOT NULL THEN ch23.id_tipe_kendaraan
        ELSE ''
      END AS id_tipe_kendaraan,
      CASE 
        WHEN ch23_so.no_polisi IS NOT NULL THEN ch23_so.no_polisi
        WHEN ch23.no_polisi IS NOT NULL THEN ch23.no_polisi
        ELSE ''
      END AS no_polisi,
      CASE
        WHEN nsc.referensi='sales' THEN so.alamat_pembeli
        WHEN ch23_so.alamat IS NOT NULL THEN ch23_so.alamat
        WHEN ch23.alamat IS NOT NULL THEN ch23.alamat
        ELSE so.alamat_pembeli
      END AS alamat,
      CASE 
        WHEN ch23_so.no_hp IS NOT NULL THEN ch23_so.no_hp
        WHEN ch23.no_hp IS NOT NULL THEN ch23.no_hp
        ELSE so.no_hp_pembeli
      END AS no_hp,
      CASE 
        -- WHEN nsc.referensi='sales' THEN ''
        WHEN ch23_so.id_tipe_kendaraan IS NOT NULL THEN (SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=ch23_so.id_tipe_kendaraan)
        WHEN ch23.id_tipe_kendaraan IS NOT NULL THEN (SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=ch23.id_tipe_kendaraan)
        ELSE ''
      END AS tipe_ahm,
      CONCAT(DATE_FORMAT(LEFT(nsc.created_at,10),'%d/%m/%Y'),' ',RIGHT(nsc.created_at,8)) AS waktu_nsc,
      nsc.id_dealer,dl.nama_dealer,dl.kode_dealer_md,
      DATE_FORMAT(so.tanggal_so,'%d/%m/%Y') AS tanggal_so,uj.no_inv_uang_jaminan,nsc.pkp,
      CASE 
        WHEN no_inv_jaminan IS NOT NULL THEN uj.total_bayar
        WHEN no_inv_jaminan IS NULL THEN nsc.uang_muka
      END AS total_bayar,
      CASE 
        WHEN nsc.referensi='sales' THEN id_referensi
        ELSE NULL
      END AS nomor_so,
      CASE 
        WHEN nsc.referensi='work_order' THEN id_referensi
        ELSE NULL
      END AS id_work_order,
      CASE WHEN nsc.tampil_ppn=1 THEN 1 ELSE 0 END tampil_ppn,nsc.cetak_nsc_ke,nsc.tot_nsc,so.id_dealer_pembeli,so.pembelian_dari_dealer_lain,concat(dl_lain.kode_dealer_md,' | ',dl_lain.nama_dealer) AS dealer_po,nsc.no_inv_jaminan, ch23.no_mesin, ch23.no_rangka
      FROM tr_h23_nsc nsc
      LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=nsc.id_customer
      LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      LEFT JOIN tr_h2_wo_dealer wo ON wo.id_work_order=nsc.id_referensi
      LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      LEFT JOIN ms_dealer dl ON dl.id_dealer=nsc.id_dealer
      LEFT JOIN tr_h3_dealer_sales_order so ON so.nomor_so=nsc.id_referensi
      LEFT JOIN ms_customer_h23 ch23_so ON ch23_so.id_customer_int=so.id_customer_int
      LEFT JOIN tr_h3_dealer_request_document rd ON rd.id_booking = so.booking_id_reference AND rd.id_dealer=nsc.id_dealer
      LEFT JOIN ms_dealer dl_lain ON dl_lain.id_dealer=so.id_dealer_pembeli
      LEFT JOIN tr_h2_uang_jaminan uj ON uj.no_inv_uang_jaminan=nsc.no_inv_jaminan
      $where
      ");
  }

  function getNSCParts($filter = null)
  {
    // send_json($filter);
    $where = "WHERE 1=1 ";
    $group_by = '';
    $join ='';
    $select2 ='';

    if ($filter != null) {
      if (isset($filter['no_nsc'])) {
        $where .= " AND nscp.no_nsc='{$filter['no_nsc']}' ";
      }
      if (isset($filter['sql_no_nsc'])) {
        $where .= " AND ns.no_nsc={$filter['sql_no_nsc']} ";
      }
      if (isset($filter['tgl_nsc'])) {
        $where .= " AND ns.tgl_nsc='{$filter['tgl_nsc']}' ";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND ns.id_dealer='{$filter['id_dealer']}' ";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND wo.id_work_order='{$filter['id_work_order']}' ";
      }
      if (isset($filter['no_mesin'])) {
        $where .= " AND cus_nsc.no_mesin='{$filter['no_mesin']}' ";
      }
      if (isset($filter['sql_no_mesin'])) {
        $where .= " AND cus_nsc.no_mesin={$filter['sql_no_mesin']} ";
      }
      if (isset($filter['sql_id_work_order'])) {
        $where .= " AND ns.id_referensi={$filter['sql_id_work_order']} ";
      }
      if (isset($filter['id_work_order_not_null'])) {
        $where .= " AND wo.id_work_order IS NOT NULL ";
      }
      if (isset($filter['nomor_so_not_null'])) {
        $where .= " AND ns.nomor_so IS NOT NULL ";
      }
      if (isset($filter['referensi'])) {
        $where .= " AND ns.referensi='{$filter['referensi']}' ";
      }
      if (isset($filter['id_type_in'])) {
        $where .= " AND (SELECT COUNT(id_type) FROM tr_h2_wo_dealer_parts wop
                         JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
                         WHERE id_work_order=ns.id_referensi AND id_type IN ({$filter['id_type_in']})
                        )>0 ";
      }
      if (isset($filter['id_type_not_in'])) {
        $where .= " AND (SELECT COUNT(id_type) FROM tr_h2_wo_dealer_parts wop
                         JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
                         WHERE id_work_order=ns.id_referensi AND id_type IN ({$filter['id_type_not_in']})
                        )=0 ";
      }
      if (isset($filter['kelompok_part'])) {
        if ($filter['kelompok_part'] != '') {
          $where .= " AND mp.kelompok_part = '{$filter['kelompok_part']}'";
        }
      }
      if (isset($filter['kelompok_part_in'])) {
        if ($filter['kelompok_part_in'] != '') {
          $where .= " AND mp.kelompok_part IN ({$filter['kelompok_part_in']})";
        }
      }
      if (isset($filter['kelompok_part_not_in'])) {
        if ($filter['kelompok_part_not_in'] != '') {
          $where .= " AND mp.kelompok_part NOT IN ({$filter['kelompok_part_not_in']})";
        }
      }
      if (isset($filter['concat_tgl_nsc'])) {
        $where .= " AND ns.tgl_nsc =CONCAT('{$filter['concat_tgl_nsc']}',tgl) ";
      }
      if (isset($filter['filter_created_wo'])) {
        $where .= " AND LEFT(wo.created_at,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }
      $potongan_nsc = "
          (CASE 
            WHEN tipe_diskon='Value' THEN diskon_value
            ELSE 0
          END
          )
          ";
      $harga_beli = "
          (
            CASE 
              WHEN tipe_diskon='Percentage' THEN harga_beli - (harga_beli*(diskon_value/100))
              ELSE harga_beli
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
      $tot_nsc = "(IFNULL(SUM(($harga_beli*$qty)-($qty*$potongan_nsc)),0))";
      $promo_rp = "($qty*$potongan_nsc)+(harga_beli-$harga_beli)+(CASE WHEN qty<$qty THEN $harga_beli*(qty-$qty) ELSE 0 END)";
      $promo_persen = "(CASE WHEN tipe_diskon='Percentage' THEN diskon_value ELSE 0 END)";
      $subtotal_nsc = "($harga_beli*$qty-($qty*$potongan_nsc))";
      // $grand = "ns.tot_nsc";
      $id_jasa = "SELECT id_jasa FROM tr_h2_wo_dealer_parts WHERE id_part=nscp.id_part AND id_work_order=ns.id_referensi LIMIT 1";
      $uang_muka = "SELECT sisa_bayar FROM tr_h2_uang_jaminan WHERE no_inv_uang_jaminan=ns.no_inv_jaminan";
      $persen_ppn = getPPN(0.1,date('Y-m-d'));
      $ppn = "IFNULL($subtotal_nsc,0)/$persen_ppn";
      $select = "nscp.*,DATE_FORMAT(ns.tgl_nsc,'%d/%m/%Y') AS tgl_nsc, nama_part,ns.created_at,($id_jasa) AS id_jasa,ROUND(IFNULL($subtotal_nsc,0)) AS subtotal,ns.uang_muka AS uang_muka,ROUND($ppn) AS ppn,$promo_persen AS promo_persen,ROUND($promo_rp) AS promo_rp,ns.updated_at, (CASE WHEN mp.kelompok_part = 'EVBT' THEN 'B' WHEN mp.kelompok_part = 'EVCH' THEN 'C' ELSE '' END) as type_acc";

      if (isset($filter['sum_qty'])) {
        $select = " COUNT(nscp.qty) AS qty ";
      } elseif (isset($filter['sum_total'])) {
        $select = " SUM($subtotal_nsc) AS sum_total ";
      } elseif (isset($filter['group_by_no_nsc'])) {
        $select = "ROUND($tot_nsc) AS total,nscp.no_nsc,tgl_nsc,ROUND(($subtotal_nsc -IFNULL(ns.uang_muka,0)))AS grand_total,ns.no_inv_jaminan";
        if (isset($filter['group_by_no_nsc_only_grand'])) {
          $select = "SUM(ROUND(($subtotal_nsc))) gt";
        }
        $where .= " GROUP BY nscp.no_nsc";
      }
      if (isset($filter['get_only_grand'])) {
        // $subtotal_nsc = "ns.tot_nsc";
        // $select = "ROUND(($subtotal_nsc -IFNULL(ns.uang_muka,0))) AS tot";
        $select = "ROUND(SUM(($subtotal_nsc))) AS tot";
        $where .= " GROUP BY nscp.no_nsc";
      }

      if(isset($filter['cetakan'])){
        $select2 = " ,dsos.serial_number, (CASE WHEN dsos.serial_number is not null then 'ev' else '' end) as ev";
        $join = "LEFT JOIN tr_h3_dealer_sales_order_serial_ev dsos ON dsos.id_part_int = nscp.id_part_int";
      }
    }
    $sql = "SELECT $select
    $select2
    FROM tr_h23_nsc_parts nscp
    JOIN tr_h23_nsc ns ON ns.no_nsc=nscp.no_nsc
    JOIN ms_part mp ON mp.id_part_int=nscp.id_part_int
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_work_order=ns.id_referensi
    LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    LEFT JOIN ms_customer_h23 cus_nsc ON cus_nsc.id_customer=sa.id_customer
    $join
    $where
    $group_by
    ";
    if (isset($filter['sql'])) {
      return $sql;
    } else {
      return $this->db->query($sql);
    }
  }

  function getNSCPartsHeader($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE nsc.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['start_tgl_nsc']) && isset($filter['end_tgl_nsc'])) {
        $where .= " AND nsc.tgl_nsc BETWEEN '{$filter['start_tgl_nsc']}' AND '{$filter['end_tgl_nsc']}' ";
      }
    }
    return $this->db->query("SELECT nscp.*,nama_part,tgl_nsc
      FROM tr_h23_nsc_parts nscp
      JOIN tr_h23_nsc nsc ON nsc.no_nsc=nscp.no_nsc
      JOIN ms_part mp ON mp.id_part=nscp.id_part
      $where");
  }

  function getNJB($filter = null)
  {

    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer     = $this->m_admin->cari_dealer();
    }
    if ($id_dealer != '') {
      $id_dealer = "AND wo.id_dealer='$id_dealer'";
    }
    $where = "WHERE 1=1 $id_dealer AND (wop.pekerjaan_batal IS NULL OR wop.pekerjaan_batal=0)";
    $select = "no_njb,DATE_FORMAT(LEFT(waktu_njb,10),'%d/%m/%Y') AS tgl_njb,js.id_jasa,wop.harga,jst.deskripsi AS desk_type ";
    if ($filter != null) {
      if (isset($filter['start_tgl_njb']) && isset($filter['end_tgl_njb'])) {
        $where .= " AND LEFT(waktu_njb,10) BETWEEN '{$filter['start_tgl_njb']}' AND '{$filter['end_tgl_njb']}' ";
      }
      if (isset($filter['start_tgl_wo']) && isset($filter['end_tgl_wo'])) {
        $where .= " AND LEFT(wo.created_at,10) BETWEEN '{$filter['start_tgl_wo']}' AND '{$filter['end_tgl_wo']}' ";
      }
      if (isset($filter['filter_bulan_tahun'])) {
        $where .= " AND LEFT(waktu_njb,7)='{$filter['filter_bulan_tahun']}'";
      }
      if (isset($filter['tgl_njb'])) {
        $where .= " AND LEFT(waktu_njb,10)='{$filter['tgl_njb']}'";
      }
      if (isset($filter['concat_tgl_njb'])) {
        $where .= " AND LEFT(waktu_njb,10)=CONCAT('{$filter['concat_tgl_njb']}',tgl)";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND wop.id_work_order='{$filter['id_work_order']}'";
      }
      if (isset($filter['no_njb'])) {
        $where .= " AND wo.no_njb='{$filter['no_njb']}'";
      }
      if (isset($filter['sql_id_work_order'])) {
        $where .= " AND wop.id_work_order={$filter['sql_id_work_order']}";
      }
      if (isset($filter['sql_no_mesin'])) {
        $where .= " AND cus_njb.no_mesin={$filter['sql_no_mesin']}";
      }
      if (isset($filter['pekerjaan_kpb'])) {
        $where .= " AND jst.id_type='ASS{$filter['pekerjaan_kpb']}'";
      }
      if (isset($filter['id_type'])) {
        $where .= " AND jst.id_type='{$filter['id_type']}'";
      }
      if (isset($filter['id_type_in'])) {
        $where .= " AND jst.id_type IN({$filter['id_type_in']})";
      }
      if (isset($filter['id_type_not_in'])) {
        $where .= " AND jst.id_type NOT IN({$filter['id_type_not_in']})";
      }
      $diskon = "CASE 
          WHEN wop.id_promo IS NOT NULL THEN
           CASE 
             WHEN prj.tipe_diskon='rupiah' THEN prj.diskon
             ELSE 
              CASE
                WHEN wo.pkp_njb=1 THEN (wop.harga) * (prj.diskon/100)
                ELSE wop.harga * (prj.diskon/100)
              END
            END
          ELSE 0
          END";

      $total = "wop.harga-$diskon";
      $total_no_diskon = "CASE 
          WHEN wo.pkp_njb=1 THEN 
            CASE WHEN ($diskon)> 0 THEN wop.harga
            ELSE wop.harga
            END
          ELSE wop.harga
        END
      ";
      if (isset($filter['sum_total'])) {
        $select = "SUM($total) AS total";
      } elseif (isset($filter['sum_tot_tanpa_diskon'])) {
        $select = "SUM($total_no_diskon) AS total";
      } elseif (isset($filter['sum_diskon'])) {
        $select = "SUM($diskon) AS total";
      } elseif (isset($filter['group_type'])) {
        $where .= " GROUP BY no_njb,js.id_type";
        $harga_net = "SUM(wop.harga-$diskon) AS harga_net";
        $select .= ",$diskon AS diskon, $harga_net";
      } elseif (isset($filter['group_njb'])) {
        $where .= " GROUP BY no_njb";
        $harga_net = "ROUND(SUM($total)) AS harga_net";
        $select .= ",ROUND($diskon) AS diskon, $harga_net";
      } elseif (isset($filter['group_tgl_njb'])) {
        $where .= " GROUP BY LEFT(waktu_njb,10)";
        $harga_net = "SUM($total) AS harga_net";
        if (isset($filter['sql'])) {
          $select = $harga_net . 's';
        } else {
          $select .= ",$diskon AS diskon, $harga_net";
        }
      } elseif (isset($filter['group_tgl_wo'])) {
        $where .= " GROUP BY LEFT(wo.created_at,10)";
        $harga_net = "SUM($total) AS harga_net";
        if (isset($filter['sql'])) {
          $select = $harga_net . 's';
        } else {
          $select .= ",$diskon AS diskon, $harga_net";
        }
      }
    }
    $sql = "SELECT $select
    FROM tr_h2_wo_dealer_pekerjaan wop
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    JOIN ms_customer_h23 cus_njb ON cus_njb.id_customer=sa.id_customer
    JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
    LEFT JOIN ms_h2_jasa_type jst ON jst.id_type=js.id_type
    LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wop.id_promo AND prj.id_jasa=wop.id_jasa
    LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
    $where 
    ";
    if (isset($filter['sql'])) {
      return $sql;
    } else {
      return $this->db->query($sql);
    }
  }

  function sel_tot_njb($tot_njb)
  {
    return "(SELECT $tot_njb  
    FROM tr_h2_wo_dealer_pekerjaan AS wop
    LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wop.id_promo AND prj.id_jasa=wop.id_jasa
    LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
    WHERE wop.id_work_order=wo.id_work_order)";
  }

  function get_njb_nsc_print($filter = null)
  {
    // send_json($filter);
    $id_dealer     = $this->m_admin->cari_dealer();
    // if ($filter != null) {
    //   if (isset($filter['no_wo'])) {
    //     $where .= " AND wo.no_wo='{$filter['no_wo']}' ";
    //   }
    // }
    $diskon_njb = "CASE 
    WHEN wop.id_promo IS NOT NULL THEN
     CASE 
       WHEN prj.tipe_diskon='rupiah' THEN prj.diskon
       ELSE
        CASE 
          WHEN wo.pkp_njb=1 THEN  (wop.harga) * (prj.diskon/100)
          ELSE wop.harga * (prj.diskon/100)
        END
      END
    ELSE 0
    END";
    // $diskon_njb = 0;
    $potongan_nsc = "
    (CASE 
      WHEN tipe_diskon='Value' THEN diskon_value
      ELSE 0
     END
    )
    ";
    $harga_beli = "
      (CASE 
        WHEN nsc.pkp=1 THEN 
          CASE 
            WHEN tipe_diskon='Percentage' THEN harga_beli - ((harga_beli)*(diskon_value/100))
            ELSE harga_beli
          END
        ELSE harga_beli
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

    $where_jenis = '';
    if ($filter != null) {
      if (isset($filter['jenis_penerimaan'])) {
        $where_jenis .= " AND rcm.metode_bayar='{$filter['jenis_penerimaan']}'";
      }
    }
    // $tot_bayar_njb_nsc = 0;
    $dibayar_njb = "(SELECT IFNULL(SUM(nominal),0) 
        FROM tr_h2_receipt_customer_metode rcm
        JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
        WHERE id_referensi=wo.id_work_order $where_jenis
      )
    ";
    // $dibayar_njb = 0;
    $sisa_njb = "(ROUND(wo.grand_total-$dibayar_njb))";
    $return = "SELECT kuantitas_return FROM tr_h3_dealer_sales_order_parts spt WHERE spt.nomor_so=wdp.nomor_so AND spt.id_part_int=wdp.id_part_int";
    $cek_nsc = " AND 
                  (CASE 
                    WHEN 
                      (SELECT SUM(wdp.qty-kuantitas_return) 
                        FROM tr_h2_wo_dealer_parts wdp 
                        LEFT JOIN tr_h3_dealer_sales_order_parts spt ON spt.nomor_so=wdp.nomor_so AND spt.id_part_int=wdp.id_part_int
                        WHERE wdp.id_work_order=wo.id_work_order 
                        AND (pekerjaan_batal IS NULL OR pekerjaan_batal=0) )>0 
                  THEN (SELECT COUNT(id_referensi) FROM tr_h23_nsc WHERE tr_h23_nsc.id_referensi=wo.id_work_order)
                  ELSE 1
                  END)>0";
    $where_njb = "WHERE wo.id_dealer='$id_dealer' $cek_nsc AND no_njb IS NOT NULL and wo.status <>'cancel'";


    // $tot_nsc = "(SELECT $tot_nsc_ppn  FROM tr_h23_nsc_parts WHERE no_nsc=nsc.no_nsc )";
    $uang_muka = "SELECT SUM(IFNULL(uang_muka_terpakai,0)) FROM tr_h2_uang_jaminan nsc_um WHERE nsc_um.no_inv_uang_jaminan=nsc.no_inv_jaminan";
    // $uang_muka = 0;
    $dibayar_nsc = "((SELECT IFNULL(SUM(nominal),0) 
    FROM tr_h2_receipt_customer_metode rcm
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
    WHERE id_referensi=nsc.id_referensi $where_jenis
    ))
    ";
    $sisa_nsc = "(ROUND(nsc.tot_nsc-$dibayar_nsc))";
    $where_nsc = "WHERE nsc.id_dealer='$id_dealer' and nsc.tot_nsc > 0";

    // $order_column = ['referensi', 'pk.id_referensi', 'coa', null];
    $where = "WHERE 1=1 ";
    $limit = '';
    $order = " ORDER BY tgl_invoice DESC";

    $all_print = "CASE 
          WHEN referensi='Work Order' THEN
            CASE 
              WHEN no_nsc IS NULL THEN 
                CASE WHEN cetak_njb_ke>0 THEN 1 ELSE 0 END
              WHEN no_nsc IS NOT NULL THEN
                CASE WHEN cetak_njb_ke>0 AND cetak_nsc_ke>0 AND cetak_gab_ke>0 THEN 1 ELSE 0 END
            END
          WHEN referensi='Part Sales' THEN
            CASE WHEN cetak_nsc_ke>0 THEN 1 ELSE 0 END
        END
      ";

    if ($filter != null) {
      if (isset($filter['sisa_lebih_besar'])) {
        if ($filter['sisa_lebih_besar'] != '') {
          $where_njb .= " AND $sisa_njb>0";
          $where_nsc .= " AND $sisa_nsc>0";
        }
      }
      if (isset($filter['sisa_0'])) {
        if ($filter['sisa_0'] != '') {
          $where_njb .= " AND $sisa_njb<=0";
          $where_nsc .= " AND $sisa_nsc<=0";
        }
      }
      if (isset($filter['not_exist_penerimaan'])) {
        $where_njb .= " AND NOT EXISTS (SELECT id_referensi FROM tr_h23_penerimaan_finance_detail WHERE id_referensi=no_njb) ";
        $where_nsc .= " AND NOT EXISTS (SELECT id_referensi FROM tr_h23_penerimaan_finance_detail WHERE id_referensi=nsc.no_nsc) ";
      }
      if (isset($filter['id_customer'])) {
        $where_njb .= " AND ch23.id_customer='{$filter['id_customer']}' ";
        $where_nsc .= " AND ch23.id_customer='{$filter['id_customer']}' ";
      }

      // if (isset($filter['not_exist_rekap'])) {
      //   $where_njb .= " AND NOT EXISTS (SELECT id_referensi FROM tr_h23_penerimaan_finance_detail WHERE id_referensi=no_njb) ";
      //   $where_nsc .= " AND NOT EXISTS (SELECT id_referensi FROM tr_h23_penerimaan_finance_detail WHERE id_referensi=nsc.no_nsc) ";
      // }
      if (isset($filter['referensi'])) {
        $where .= " AND referensi='{$filter['referensi']}'";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND referensi='{$filter['id_work_order']}'";
      }
      if (isset($filter['tgl_nsc'])) {
        $where .= " AND tgl_nsc='{$filter['tgl_nsc']}'";
      }
      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND ( no_nsc LIKE '%{$filter['search']}%' 
                  OR id_referensi LIKE '%{$filter['search']}%'
                  OR referensi LIKE '%{$filter['search']}%'
                  OR no_njb LIKE '%{$filter['search']}%'
                  OR tgl_invoice LIKE '%{$filter['search']}%'
                  OR nama_customer LIKE '%{$filter['search']}%'
                  OR tipe_ahm LIKE '%{$filter['search']}%'
                  OR no_polisi LIKE '%{$filter['search']}%'
                )";
        }
      }

      if (isset($filter['dibayar'])) {
        $where .= " AND dibayar {$filter['dibayar']}";
      }
      if (isset($filter['all_print'])) {
        if ($filter['all_print'] != '') {
          $where .= " AND ($all_print)={$filter['all_print']}";
        }
      }
      if (isset($filter['filter_created_wo'])) {
        $where .= " AND LEFT(created_at_wo,10) BETWEEN '{$filter['start']}' AND '{$filter['end']}'";
      }

      if (isset($filter['order'])) {
        if (isset($filter['order_column'])) {
          if ($filter['order_column'] == 'print_receipt') {
            $order_column = ['referensi', 'id_referensi', 'no_njb', 'no_nsc', 'no_polisi', 'nama_customer', 'tipe_ahm', 'total_bayar', 'dibayar', 'sisa', null];
          } elseif ($filter['order_column'] == 'list_ar') {
            $order_column = ['tgl_invoice', 'tgl_jatuh_tempo', 'nama_customer', 'no_nsc', 'no_njb', 'nilai_jasa', 'nilai_oli', 'nilai_part', 'total_bayar', 'dibayar', 'sisa'];
          } else {
            $order_column = $filter['order_column'];
          }
        }
        if ($filter['order'] != '') {
          $filter_order = $filter['order'];
          $order_clm    = $order_column[$filter['order'][0]['column']];
          // send_json($filter['order_column']);
          $order_by     = $filter_order[0]['dir'];
          $order        = " ORDER BY $order_clm $order_by ";
        } else {
          $order  = " ORDER BY created_at DESC";
        }
      } else {
        $order  = " ORDER BY created_at DESC";
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    // $filter_oli = [
    //   'sql' => true,
    //   'sql_no_nsc' => 'nsc.no_nsc',
    //   'group_by_no_nsc' => true,
    //   'group_by_no_nsc_only_grand' => true,
    //   'kelompok_part' => 'Oil'
    // ];
    // $nilai_oli = $this->getNSCParts($filter_oli);

    // $filter_part = [
    //   'sql' => true,
    //   'sql_no_nsc' => 'nsc.no_nsc',
    //   'group_by_no_nsc' => true,
    //   'group_by_no_nsc_only_grand' => true,
    //   'kelompok_part_not_in' => "'Oil'"
    // ];
    // $nilai_part = $this->getNSCParts($filter_part);
    // $nilai_part = 0;
    $nilai_oli = "nsc.tot_nsc_oli";
    $nilai_part = "nsc.tot_nsc_part";
    // $sisa_njb = 0;
    // $dibayar_njb = 0;
    // $dibayar_nsc = 0;
    // $sisa_nsc = 0;
    $select_wo = "'Work Order' AS referensi,no_njb,nsc.no_nsc,  
    wo.id_work_order AS id_referensi,nama_customer,wo.created_njb_at AS created_at,no_polisi,ch23.alamat,ch23.id_customer,ch23.no_hp,tipe_ahm,
    wo.grand_total AS total_bayar,$dibayar_njb AS dibayar, $sisa_njb AS sisa,wo.id_work_order,wo.total_jasa AS nilai_jasa, LEFT(created_njb_at,10) AS tgl_invoice,LEFT(created_njb_at,10) AS tgl_njb,tgl_nsc,cetak_njb_ke,cetak_gab_ke,cetak_nsc_ke,
    CASE WHEN nsc.no_nsc IS NOT NULL THEN ($nilai_oli) ELSE 0 END AS nilai_oli,
    CASE WHEN nsc.no_nsc IS NOT NULL THEN ($nilai_part) ELSE 0 END AS nilai_part,wo.created_at AS created_at_wo,wo.total_jasa,nsc.tot_nsc,wo.tgl_jatuh_tempo";
    $select_nsc = "'Part Sales' AS referensi,'-' AS no_njb,nsc.no_nsc,id_referensi,
    nama_pembeli nama_customer,
    nsc.created_at,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN ''
      ELSE ch23.no_polisi
    END AS no_polisi,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.alamat_pembeli
      ELSE ch23.alamat
    END AS alamat,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.id_customer
      ELSE ch23.id_customer
    END AS id_customer,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.no_hp_pembeli
      ELSE ch23.no_hp
    END AS no_hp,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='')THEN ''
      ELSE ''
    END AS tipe_ahm,
    nsc.tot_nsc AS total_bayar,$dibayar_nsc AS dibayar,$sisa_nsc AS sisa,'' AS id_work_order,0 AS nilai_jasa,LEFT(nsc.created_at,10) AS tgl_invoice,'' AS tgl_njb, tgl_nsc,0 AS cetak_njb_ke,0 AS cetak_gab_ke,cetak_nsc_ke, ($nilai_oli) AS nilai_oli,($nilai_part) AS nilai_part,'' AS created_at_wo,'' AS total_jasa,nsc.tot_nsc,'' AS tgl_jatuh_tempo";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'print_njb_nsc') {
        $select_wo = "'Work Order' AS referensi,no_njb,nsc.no_nsc,  
    wo.id_work_order AS id_referensi,nama_customer,wo.created_njb_at AS created_at,no_polisi,ch23.alamat,ch23.id_customer,ch23.no_hp,tipe_ahm,
    wo.grand_total AS total_bayar,$dibayar_njb AS dibayar, $sisa_njb AS sisa,wo.id_work_order,wo.total_jasa AS nilai_jasa, LEFT(created_njb_at,10) AS tgl_invoice,LEFT(created_njb_at,10) AS tgl_njb,tgl_nsc,cetak_njb_ke,cetak_gab_ke,cetak_nsc_ke,wo.created_at AS created_at_wo,wo.total_jasa,nsc.tot_nsc,wo.tgl_jatuh_tempo";
        if (isset($filter['select_add'])) {
          if ($filter['select_add'] == 'last_kwitansi') {
            $select_wo .= "
              ,(SELECT id_receipt FROM tr_h2_receipt_customer WHERE id_referensi=wo.id_work_order ORDER BY created_at DESC LIMIT 1) last_id_kwitansi,
              (SELECT LEFT(created_at,10) FROM tr_h2_receipt_customer WHERE id_referensi=wo.id_work_order ORDER BY created_at DESC LIMIT 1)  last_tgl_kwitansi
              ";
          }
        }
        $select_nsc = "'Part Sales' AS referensi,'-' AS no_njb,nsc.no_nsc,id_referensi,
    nama_pembeli nama_customer,
    nsc.created_at,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN ''
      ELSE ch23.no_polisi
    END AS no_polisi,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.alamat_pembeli
      ELSE ch23.alamat
    END AS alamat,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.id_customer
      ELSE ch23.id_customer
    END AS id_customer,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='') THEN so3.no_hp_pembeli
      ELSE ch23.no_hp
    END AS no_hp,
    CASE 
      WHEN (so3.id_customer IS NULL OR so3.id_customer='')THEN ''
      ELSE tk_ch23.tipe_ahm
    END AS tipe_ahm,
    nsc.tot_nsc AS total_bayar,$dibayar_nsc AS dibayar,$sisa_nsc AS sisa,'' AS id_work_order,0 AS nilai_jasa,LEFT(nsc.created_at,10) AS tgl_invoice,'' AS tgl_njb, tgl_nsc,0 AS cetak_njb_ke,0 AS cetak_gab_ke,cetak_nsc_ke,'' AS created_at_wo,'' AS total_jasa,nsc.tot_nsc,'' AS tgl_jatuh_tempo";
        if (isset($filter['select_add'])) {
          if ($filter['select_add'] == 'last_kwitansi') {
            $select_nsc .= "
          ,(SELECT id_receipt FROM tr_h2_receipt_customer WHERE id_referensi=nsc.id_referensi AND referensi='part_sales' ORDER BY created_at DESC LIMIT 1),
          (SELECT LEFT(created_at,10) FROM tr_h2_receipt_customer WHERE id_referensi=nsc.id_referensi AND referensi='part_sales' ORDER BY created_at DESC LIMIT 1)
          ";
          }
        }
      }
    }

    return $this->db->query("SELECT * FROM 
    (SELECT $select_wo
      FROM tr_h2_wo_dealer AS wo
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      LEFT JOIN tr_h23_nsc nsc ON nsc.id_referensi=wo.id_work_order
      JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      $where_njb
      UNION 
      SELECT $select_nsc
      FROM tr_h23_nsc nsc
      JOIN tr_h3_dealer_sales_order so3 ON so3.nomor_so=nsc.id_referensi
      LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer_int=so3.id_customer_int
      LEFT JOIN ms_tipe_kendaraan tk_ch23 ON tk_ch23.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      $where_nsc
    ) AS tabel 
    $where $order $limit
    ");
  }

  function getPOCustomer($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();

    $where = "WHERE po.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['po_id'])) {
        $where .= " AND po.po_id='{$filter['po_id']}' ";
      }
      if (isset($filter['id_booking'])) {
        $where .= " AND po.id_booking='{$filter['id_booking']}' ";
      }
    }
    return $this->db->query("SELECT po.po_id,tanggal_order,po.id_booking,ch23.id_customer,ch23.nama_customer
    FROM tr_h3_dealer_purchase_order AS po
    JOIN tr_h3_dealer_request_document rq ON rq.id_booking=po.id_booking
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=rq.id_customer
      $where");
  }

  function getPOParts($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['po_id'])) {
        $where .= " AND prts.po_id='{$filter['po_id']}' ";
      }
      if (isset($filter['id_booking'])) {
        $where .= " AND po.id_booking='{$filter['id_booking']}' ";
      }
    }
    return $this->db->query("SELECT prts.id_part,kuantitas,harga_saat_dibeli,tipe_diskon,diskon_value,nama_part,prts.uang_muka
    FROM tr_h3_dealer_purchase_order_parts AS prts
    JOIN tr_h3_dealer_purchase_order po ON po.po_id=prts.po_id
    JOIN ms_part mp ON mp.id_part=prts.id_part
    $where");
  }
  function getRequestDocumentParts($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_booking'])) {
        $where .= " AND prts.id_booking='{$filter['id_booking']}' ";
      }
    }
    return $this->db->query("SELECT prts.id_part,kuantitas,harga_saat_dibeli,nama_part,prts.persen_uang_muka,prts.uang_muka, prts.part_revisi_dari_md
    FROM tr_h3_dealer_request_document_parts AS prts
    JOIN ms_part mp ON mp.id_part=prts.id_part
    $where");
  }

  function getRequestDocumentPartsRevisi($filter = null)
  {
    $where = "WHERE 1=1 AND prts.revisi_part_dealer=1 and uang_muka = 0 ";
    if ($filter != null) {
      if (isset($filter['id_booking'])) {
        $where .= " AND prts.id_booking='{$filter['id_booking']}' ";
        if ($filter['id_booking']=='05391/BOK-044') {
          $where .="ss";
        }
      }
    }
    return $this->db->query("SELECT prts.id_part,kuantitas,harga_saat_dibeli,nama_part,prts.persen_uang_muka,prts.uang_muka
    FROM tr_h3_dealer_request_document_parts AS prts
    JOIN ms_part mp ON mp.id_part=prts.id_part
    $where");
  }

  function getRequestDocumentPartsReject($filter = null)
  {
    $where = "WHERE 1=1 AND prts.part_revisi_dari_md=1 and prts.alasan_part_revisi_md != '' ";
    if ($filter != null) {
      if (isset($filter['id_booking'])) {
        $where .= " AND prts.id_booking='{$filter['id_booking']}' ";
        if ($filter['id_booking']=='05391/BOK-044') {
          $where .="ss";
        }
      }
    }
    return $this->db->query("SELECT prts.id_part,kuantitas,harga_saat_dibeli,nama_part,prts.persen_uang_muka,prts.uang_muka, 
    (case when prts.alasan_part_revisi_md = 'discontinue' then 'Discontinue' when prts.alasan_part_revisi_md = 'part_set' then 'Part Set' when prts.alasan_part_revisi_md = 'supersede' then 'Supersede' else 'Lainnya' end) as alasan_part_revisi_md
    FROM tr_h3_dealer_request_document_parts AS prts
    JOIN ms_part mp ON mp.id_part=prts.id_part
    $where");
  }

  function get_uang_jaminan($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE uj.id_dealer='$id_dealer' and uj.status is null ";
    $cek_dipakai = "uj.uang_muka_terpakai";


    $limit = '';
    $order = '';
    if ($filter != null) {
      if (isset($filter['no_inv_uang_jaminan'])) {
        $where .= " AND uj.no_inv_uang_jaminan='{$filter['no_inv_uang_jaminan']}' ";
      }
      if (isset($filter['cetak_ke'])) {
        $where .= " AND uj.cetak_ke='{$filter['cetak_ke']}' ";
      }
      if (isset($filter['id_booking'])) {
        $where .= " AND uj.id_booking='{$filter['id_booking']}' ";
      }
      if (isset($filter['id_customer'])) {
        $where .= " AND ch23.id_customer='{$filter['id_customer']}' ";
      }
      if (isset($filter['sisa'])) {
        if ($filter['sisa'] != NULL) {
          if (is_array($filter['sisa'])) {
            $operator = $filter['sisa']['operator'];
            $value = $filter['sisa']['value'];
            $where .= " AND uj.uang_muka_terpakai $operator $value ";
          } else {
            $where .= " AND uj.uang_muka_terpakai={$filter['sisa']} ";
          }
        }
      }

      if (isset($filter['periode_created'])) {
        $periode = $filter['periode_created'];
        $where .= " AND uj.created_at BETWEEN '{$periode['start']}' AND '{$periode['end']}' ";
      }
      if (isset($filter['tanggal'])) {
        $where .= " AND LEFT(uj.created_at,10)='{$filter['tanggal']}'";
      }

      if (isset($filter['search'])) {
        $search = $filter['search'];
        if ($search != '') {
          $where .= " AND (uj.no_inv_uang_jaminan LIKE '%$search%'
                            OR uj.id_booking LIKE '%$search%'
                            OR DATE_FORMAT(LEFT(rq.created_at,10),'%d/%m/%Y') LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
                            OR ch23.nama_customer LIKE '%$search%'
                            OR uj.total_bayar LIKE '%$search%'
                            OR uj.sisa_bayar LIKE '%$search%'
                            OR LEFT(rq.created_at,10) LIKE '%$search%'
                            ) 
                ";
        }
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          if ($filter['order_column'] == 'view') {
            $order_column = ['no_inv_uang_jaminan', 'tgl_invoice', 'uj.id_booking', 'rq.created_at', 'ch23.id_customer', 'ch23.nama_customer', 'total_bayar', 'sisa_bayar'];
          }
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY uj.created_at DESC ";
        }
      } else {
        $order = " ORDER BY uj.created_at DESC ";
      }

      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }

    $select = " uj.*,ch23.id_customer,nama_customer,DATE_FORMAT(LEFT(rq.created_at,10),'%d/%m/%Y') AS tgl_request,dl.kode_dealer_md,dl.nama_dealer,LEFT(uj.created_at,10) tgl_uang_jaminan,wo.id_work_order, rq.no_claim_c2 as no_claim_c2, (case when rq.order_to = 0 then 'Main Dealer' when rq.order_to = $id_dealer then 'Ahass' else 'Dealer Lain' end) order_to, ch23.no_hp ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sisa') {
        $select = " uj.*,(uj.total_bayar-$cek_dipakai) AS sisa,LEFT(rq.created_at,10) tgl_request2 ";
      } elseif ($filter['select'] == 'summary') {
        $select = "IFNULL(SUM(uj.total_bayar),0) summary";
      }
    }

    return $this->db->query("SELECT $select    
    FROM tr_h2_uang_jaminan AS uj
    JOIN tr_h3_dealer_request_document rq ON rq.id_booking=uj.id_booking
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=rq.id_customer
    LEFT JOIN ms_dealer dl ON dl.id_dealer=uj.id_dealer
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=rq.id_sa_form
    -- LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=rq.id_sa_form 
    $where $order $limit
    ");
  }

  function get_uang_jaminan_detail($filter, $po)
  {
    $total_no_ppn = 0;
    $details = $this->getRequestDocumentParts($filter);
    foreach ($details->result() as $dtl) {
      $subtotal = $dtl->harga_saat_dibeli * $dtl->kuantitas;
      $total_no_ppn += $subtotal;
      $dtl->subtotal = $subtotal;
      $detail_part[] = $dtl;
    }
    // $ppn = (10 / 100) * $total_no_ppn;
    $ppn = 0;
    $grand = $total_no_ppn + $ppn;
    $sisa = $grand - $po->total_bayar;
    return [
      'detail' => $detail_part,
      'total_no_ppn' => $total_no_ppn,
      'ppn' => $ppn,
      'grand' => $grand,
      'sisa' => $sisa
    ];
  }

  function get_uang_jaminan_detail_revisi($filter, $po)
  {
    $total_no_ppn = 0;
    $details = $this->getRequestDocumentPartsRevisi($filter);
    foreach ($details->result() as $dtl) {
      $subtotal = $dtl->harga_saat_dibeli * $dtl->kuantitas;
      $total_no_ppn += $subtotal;
      $dtl->subtotal = $subtotal;
      $detail_part[] = $dtl;
    }
    // $ppn = (10 / 100) * $total_no_ppn;
    $ppn = 0;
    $grand = $total_no_ppn + $ppn;
    $sisa = $grand - $po->total_bayar;
    return [
      'detail' => $detail_part,
      'total_no_ppn' => $total_no_ppn,
      'ppn' => $ppn,
      'grand' => $grand,
      'sisa' => $sisa
    ];
  }


  function get_uang_jaminan_metode($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE uj.id_dealer='$id_dealer' ";

    $limit = '';
    if ($filter != null) {
      if (isset($filter['no_inv_uang_jaminan'])) {
        $where .= " AND uj.no_inv_uang_jaminan='{$filter['no_inv_uang_jaminan']}' ";
      }

      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          if ($filter['order_column'] == 'view') {
            $order_column = ['no_inv_uang_jaminan', 'tgl_invoice', 'uj.id_booking', 'rq.created_at', 'ch23.id_customer', 'ch23.nama_customer', 'total_bayar', 'sisa_bayar'];
          }
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        } else {
          $order = " ORDER BY uj.created_at DESC ";
        }
      } else {
        $order = " ORDER BY uj.created_at DESC ";
      }

      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }

    $select = "ujm.id_uang_jaminan_metode,ujm.no_inv_uang_jaminan,metode_bayar,ujm.no_rekening,ujm.id_bank,tanggal_transaksi,ujm.nominal,bk.bank";
    return $this->db->query("SELECT $select    
    FROM tr_h2_uang_jaminan_metode AS ujm
    JOIN tr_h2_uang_jaminan uj ON uj.no_inv_uang_jaminan=ujm.no_inv_uang_jaminan
    LEFT JOIN ms_bank bk ON bk.id_bank=ujm.id_bank
    $where $order $limit
    ");
  }

  function fetchHistorySatisfaction($filter)
  {

    $order_column = array('sumber ', 'id_referensi', '', 'nama_customer', 'tipe_ahm', 'level', null);
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE sat.id_dealer='$id_dealer' ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (ch23.nama_customer LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
                            OR po.id_antrian LIKE '%$search%'
                            OR po.tgl_servis LIKE '%$search%'
                            OR po.jam_servis LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY sat.created_at DESC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT sat.*,
    CASE 
      WHEN level=1 THEN 'Very Poor'
      WHEN level=2 THEN 'Poor'
      WHEN level=3 THEN 'Dont Know'
      WHEN level=4 THEN 'Good'
      WHEN level=5 THEN 'Very Good'
    END AS level_teks,
    CASE
      WHEN wod.id_work_order IS NULL THEN
        CASE 
          WHEN so3.id_customer IS NULL THEN nama_pembeli
          WHEN so3.id_customer='' THEN nama_pembeli
          ELSE cs_so.nama_customer
        END
      ELSE cs_wo.nama_customer
    END AS nama_customer,
    CASE
      WHEN wod.id_work_order IS NULL THEN
        CASE 
          WHEN so3.id_customer IS NULL THEN no_hp_pembeli
          WHEN so3.id_customer='' THEN no_hp_pembeli
          ELSE cs_so.no_hp
        END
      ELSE cs_wo.no_hp
    END AS no_hp,
    CASE 
      WHEN wod.id_work_order IS NULL THEN tk_so.tipe_ahm
      ELSE tk_wo.tipe_ahm
    END AS tipe_ahm
    FROM tr_h2_service_satisfaction AS sat
    LEFT JOIN tr_h2_wo_dealer wod ON wod.id_work_order=sat.id_referensi
    LEFT JOIN tr_h3_dealer_sales_order so3 ON so3.nomor_so=sat.id_referensi
    LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=wod.id_sa_form
    LEFT JOIN ms_customer_h23 cs_wo ON cs_wo.id_customer=sa.id_customer
    LEFT JOIN ms_customer_h23 cs_so ON cs_so.id_customer=so3.id_customer
    LEFT JOIN ms_tipe_kendaraan tk_wo ON tk_wo.id_tipe_kendaraan=cs_wo.id_tipe_kendaraan
    LEFT JOIN ms_tipe_kendaraan tk_so ON tk_so.id_tipe_kendaraan=cs_so.id_tipe_kendaraan
    $set_filter
    ");
  }

  public function get_id_receipt_customer()
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT * FROM tr_h2_receipt_customer
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_receipt = substr($row->id_receipt, -5);
      $new_kode = 'KWT/' . $kode . '/' . $my . '/' . sprintf("%'.05d", $id_receipt + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_receipt_customer', ['id_receipt' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = 'KWT/' . $kode . '/' . $my . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'KWT/' . $kode . '/' . $my . '/00001';
    }
    return strtoupper($new_kode);
  }

  function getTransaksiSudahDibayar($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_dealer'])) {
        $where .= " AND rc.id_dealer='{$filter['id_dealer']}' ";
      }
      if (isset($filter['id_referensi'])) {
        $where .= " AND rc.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND rc.id_referensi='{$filter['id_work_order']}' ";
      }
      if (isset($filter['nomor_so'])) {
        $where .= " AND rc.id_referensi='{$filter['nomor_so']}' ";
      }
      if (isset($filter['id_receipt'])) {
        $where .= " AND rc.id_receipt='{$filter['id_receipt']}' ";
      }
      if (isset($filter['metode_bayar'])) {
        $where .= " AND rcm.metode_bayar='{$filter['metode_bayar']}' ";
      }
      if (isset($filter['tanggal'])) {
        $where .= " AND rcm.tanggal='{$filter['tanggal']}' ";
      }
    }

    return (int)$this->db->query("SELECT SUM(nominal) AS total 
    FROM tr_h2_receipt_customer_metode rcm
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
    $where")->row()->total;
  }
  function getKwitansi($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();

    $where = "WHERE rc.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['id_referensi'])) {
        $where .= " AND rc.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['id_receipt'])) {
        $where .= " AND rc.id_receipt='{$filter['id_receipt']}' ";
      }
    }
    return $this->db->query("SELECT id_receipt,DATE_FORMAT(tgl_receipt,'%d/%m/%Y') AS tgl_receipt,(SELECT SUM(nominal) FROM tr_h2_receipt_customer_metode WHERE id_receipt=rc.id_receipt) AS dibayar,id_referensi,referensi,rc.kode_coa,cod.coa,rc.nominal_lebih
    FROM tr_h2_receipt_customer rc
    LEFT JOIN ms_coa_dealer cod ON cod.kode_coa=rc.kode_coa
    $where");
  }

  function getRefKwitansi($id)
  {
    //Cek WO
    $filter['id_work_order'] = $id;
    $wo = $this->m_wo->get_sa_form(($filter));
    if ($wo->num_rows() > 0) {
      $row = $wo->row();
      $filter_detail['id_work_order'] = $row->id_work_order;
      $filter_sudah_bayar['id_referensi'] = $row->id_work_order;
    } else {
      $filter_detail['nomor_so'] = $id;
      // send_json($filter_detail);
      $nsc = $this->m_bil->getNSC($filter_detail);
      if ($nsc->num_rows() > 0) {
        $row = $nsc->row();
        $filter_detail['no_nsc'] = $row->no_nsc;
        $filter_sudah_bayar['id_referensi'] = $row->id_referensi;
        // send_json($filter_detail);
      }
    }
    return [
      'row' => isset($row) ? $row : null,
      'filter_detail' => $filter_detail,
      'filter_sudah_bayar' => $filter_sudah_bayar
    ];
  }

  function getKwitansiMetodeBayar($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();

    $where = "WHERE rc.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['id_referensi'])) {
        $where .= " AND rc.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['id_receipt'])) {
        $where .= " AND rc.id_receipt='{$filter['id_receipt']}' ";
      }
    }
    return $this->db->query("SELECT rcm.*, rc.history_pembayaran ,bk.bank,rek.nama_rek AS atas_nama,DATE_FORMAT(rcm.tanggal,'%d/%m/%Y') AS tanggal,uj.tgl_invoice tgl_uang_jaminan
    FROM tr_h2_receipt_customer_metode rcm
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
    LEFT JOIN ms_norek_dealer_detail rek ON rek.no_rek=rcm.no_rekening AND rek.id_norek_dealer=rc.id_dealer
    LEFT JOIN ms_bank bk ON bk.id_bank=rcm.id_bank
    LEFT JOIN tr_h2_uang_jaminan uj ON uj.no_inv_uang_jaminan=rcm.no_inv_jaminan
    $where order by rcm.tanggal asc");
  }
  function getKwitansiTransaksi($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();

    $where = "WHERE rc.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['id_referensi'])) {
        $where .= " AND rc.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['id_receipt'])) {
        $where .= " AND rc.id_receipt='{$filter['id_receipt']}' ";
      }
    }
    return $this->db->query("SELECT rct.*
    FROM tr_h2_receipt_customer_transaksi rct
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
    $where");
  }

  public function getKwitansiTransaksiTotal($filter)
  {
    $id_dealer     = $this->m_admin->cari_dealer();

    $where = "WHERE rc.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['id_referensi'])) {
        $where .= " AND rc.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['id_receipt'])) {
        $where .= " AND rc.id_receipt='{$filter['id_receipt']}' ";
      }
    }
    return (int) $this->db->query("SELECT SUM(nilai) AS tot
    FROM tr_h2_receipt_customer_transaksi rct
    JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rct.id_receipt
    $where ")->row()->tot;
  }

  public function get_no_njb()
  {
    $th        = date('y');
    $bln       = date('m');
    $tgl       = date('Y-m-d');
    $t_b       = date('Y-m');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT no_njb FROM tr_h2_wo_dealer
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_njb_at,7)='$t_b'
			ORDER BY no_njb DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->no_njb, -4);
      $new_kode   = 'NJB/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $last_number + 1);
    } else {
      $new_kode = 'NJB/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getRequestDocument($filter)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE rd.id_dealer='$id_dealer' AND rd.status !='Canceled'";

    if (isset($filter['not_exists_on_po'])) {
      if ($filter['not_exists_on_po'] != '') {
        $set_filter .= "AND NOT EXISTS(SELECT id_booking FROM tr_h3_dealer_purchase_order WHERE id_booking=rd.id_booking) ";
      }
    }
    if (isset($filter['not_exists_uang_jaminan'])) {
      $set_filter .= "  AND NOT EXISTS(SELECT id_booking FROM tr_h2_uang_jaminan WHERE id_booking=rd.id_booking) ";
    }
    if (isset($filter['id_booking'])) {
      if ($filter['id_booking'] != '') {
        $set_filter .= " AND rd.id_booking='{$filter['id_booking']}' ";
      }
    }
    if (isset($filter['status'])) {
      if ($filter['status'] != '') {
        $set_filter .= " AND rd.status='{$filter['status']}' ";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $set_filter .= " AND (ch23.nama_customer LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
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
        $set_filter .= "ORDER BY rd.created_at DESC ";
      }
    }
    if (isset($filter['limit'])) {
      $set_filter .= $filter['limit'];
    }

    return $this->db->query("SELECT rd.id_booking,LEFT(rd.created_at,10) AS tgl_request,ch23.id_customer,REPLACE(ch23.nama_customer,'\'','`') AS nama_customer,uang_muka,wo.id_work_order, (case when rd.order_to = 0 then 'Main Dealer' when rd.order_to = $id_dealer then 'Ahass' else 'Dealer Lain' end) order_to, rd.no_claim_c2 as no_claim_c2
    FROM tr_h3_dealer_request_document AS rd
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=rd.id_customer
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=rd.id_sa_form 
    -- LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=rd.id_sa_form 
    $set_filter
    ");
  }
  function getRequestDocumentPart($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_booking'])) {
        $where .= " AND prts.id_booking='{$filter['id_booking']}' ";
      }
    }
    return $this->db->query("SELECT prts.id_part,kuantitas,harga_saat_dibeli,nama_part
    FROM tr_h3_dealer_request_document_parts AS prts
    JOIN ms_part mp ON mp.id_part=prts.id_part
    $where");
  }

  function getTotBayarUangJaminan($id_sa_form)
  {
    return $this->db->query("SELECT total_bayar,no_inv_uang_jaminan 
          FROM tr_h3_dealer_request_document req 
          JOIN tr_h2_uang_jaminan uj ON uj.id_booking=req.id_booking
          WHERE id_sa_form='$id_sa_form'")->result();
  }

  function getNomorSODariWO($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_work_order'])) {
      $book_wo = "SELECT id_booking FROM tr_h2_wo_dealer_parts wop where id_work_order='{$filter['id_work_order']}'";
      $id_dealer_wo = "SELECT id_dealer FROM tr_h2_wo_dealer wo where id_work_order='{$filter['id_work_order']}'";
      $where .= " AND (id_work_order='{$filter['id_work_order']}' 
                        OR 
                       (IFNULL(booking_id_reference,'')!='' AND booking_id_reference IN ($book_wo) AND so.id_dealer=($id_dealer_wo))
                      )";
      // $where .= "AND id_work_order='{$filter['id_work_order']}'";
    }
    return $this->db->query("SELECT GROUP_CONCAT(nomor_so SEPARATOR ', ') AS group_so FROM tr_h3_dealer_sales_order so $where 
    ");
  }
}
