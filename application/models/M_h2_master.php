<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_master extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function fetch_jasa_h2_dealer($filter, $id_tipe_kendaraan = '')
  {
    $order_column = array('id_jasa', 'deskripsi', 'id_type', 'kategori', null, 'harga', null, 'harga_dealer', 'waktu', 'active', null);
    $sel_hrg_dealer = "IFNULL((SELECT harga_dealer FROM ms_h2_jasa_dealer WHERE id_jasa=ms_h2_jasa.id_jasa AND id_dealer='{$filter['id_dealer']}'),0)";

    $order_by  = 'ORDER BY ms_h2_jasa.created_at DESC';
    $searchs   = "WHERE ms_h2_jasa.active=1 ";

    if (isset($filter['id_jasa_or_id_jasa_int'])) {
      $searchs .= " AND (ms_h2_jasa.id_jasa='{$filter['id_jasa_or_id_jasa_int']}' OR ms_h2_jasa.id_jasa_int='{$filter['id_jasa_or_id_jasa_int']}')";
    }

    if (isset($filter['id_tipe_kendaraan'])) {
      $searchs .= "AND ms_h2_jasa.tipe_motor IN(SELECT tipe_produksi FROM ms_ptm WHERE tipe_marketing='{$filter['id_tipe_kendaraan']}')";
      $id_tipe_kendaraan = $filter['id_tipe_kendaraan'];
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $search = $filter['search'];
        $searchs .= " AND (ms_h2_jasa.id_jasa LIKE '%$search%' 
                OR ms_h2_jasa.deskripsi LIKE '%$search%'
                OR ms_h2_jasa.id_type LIKE '%$search%'
                OR kategori LIKE '%$search%'
                )
            ";
      }
    }

    if (isset($filter['order'])) {
      if ($filter['order'] != '') {
        $order = $filter['order'];
        $order_clm = $order_column[$order['0']['column']];
        $order_by  = $order['0']['dir'];
        $order_by     = "ORDER BY $order_clm $order_by";
      } else {
        $order_by     = "ORDER BY ms_h2_jasa.id_jasa ASC ";
      }
    }
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    return $this->db->query("SELECT ms_h2_jasa.id_jasa_int,ms_h2_jasa.id_jasa,ms_h2_jasa.active,ms_h2_jasa.tipe_motor,harga,batas_bawah,batas_atas,waktu,
          ms_h2_jasa.deskripsi,ms_h2_jasa.id_type,ms_h2_jasa.kategori,
          ms_h2_jasa_type.id_type_int,
          ms_h2_jasa_type.deskripsi AS desk_tipe,
          CASE 
            WHEN ms_h2_jasa.id_type IN('ASS1','ASS2','ASS3','ASS4') THEN
                (SELECT harga_jasa FROM ms_kpb_detail WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND kpb_ke=RIGHT(ms_h2_jasa.id_type,1))
            WHEN $sel_hrg_dealer=0 THEN ms_h2_jasa.harga 
            ELSE $sel_hrg_dealer END AS harga_dealer,is_favorite,
            CASE WHEN ms_h2_jasa.id_type IN('ASS1','ASS2','ASS3','ASS4') THEN RIGHT(ms_h2_jasa.id_type,1) ELSE 0 END kpb_ke,ms_h2_jasa.id_type
         FROM ms_h2_jasa
         left JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=ms_h2_jasa.id_type
         $searchs $order_by $limit ");
  }

  function get_jasa_h2($id_dealer, $id_jasa = null, $kategori = null, $id_type_not_in = NULL)
  {
    $where = 'WHERE ms_h2_jasa.active=1 AND ms_h2_jasa.deleted_at IS NULL ';
    if ($id_jasa != null) {
      $where .= "AND ms_h2_jasa.id_jasa='$id_jasa'";
    }
    if ($id_type_not_in != null) {
      $where .= " AND ms_h2_jasa.id_type NOT IN($id_type_not_in)";
    }
    if ($kategori != null) {
      $where .= " AND ms_h2_jasa.kategori='$kategori' GROUP BY id_type";
    }

    return $this->db->query("SELECT ms_h2_jasa.id_jasa,id_jasa2,ms_h2_jasa.active,harga,batas_bawah,batas_atas,waktu,ms_h2_jasa.deskripsi,kategori,tipe_motor,ms_h2_jasa.id_type,ms_h2_jasa_type.deskripsi AS desk_type, IFNULL(harga_dealer,0) AS harga_dealer,id_dealer,ms_h2_jasa.is_favorite
        FROM ms_h2_jasa  
        LEFT JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=ms_h2_jasa.id_type
        LEFT JOIN ms_h2_jasa_dealer ON ms_h2_jasa_dealer.id_jasa=ms_h2_jasa.id_jasa AND id_dealer='$id_dealer'
        $where");
  }

  function get_tipe_motor()
  {
    return $this->db->query("SELECT tipe_motor,deskripsi FROM ms_ptm GROUP BY tipe_motor");
  }

  function get_job_type()
  {
    return $this->db->get('ms_h2_jasa_type');
  }

  function get_booking($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE tr_h2_manage_booking.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['status_null'])) {
        $status_not = '';
        if (isset($filter['status_not'])) {
          $status = $filter['status_not'];
          $status_not = "OR tr_h2_manage_booking.status <> '$status'";
        }

        $where .= "AND (tr_h2_manage_booking.status IS NULL $status_not) ";
      }
      if (isset($filter['id_booking'])) {
        $where .= " AND tr_h2_manage_booking.id_booking='{$filter['id_booking']}' ";
      }
      if (isset($filter['status'])) {
        $where .= " AND tr_h2_manage_booking.status='{$filter['status']}' ";
      }
      if (isset($filter['tgl_servis_lebih_besar'])) {
        $where .= "AND tr_h2_manage_booking.tgl_servis >= '{$filter['tgl_servis_lebih_besar']}'";
      }
      if (isset($filter['tgl_servis'])) {
        $where .= "AND tr_h2_manage_booking.tgl_servis = '{$filter['tgl_servis']}'";
      }
    }
    return $this->db->query("SELECT ms_customer_h23.id_customer_int,
    ms_customer_h23.id_customer,
    ms_customer_h23.nama_customer,
    ms_customer_h23.no_identitas,
    ms_customer_h23.jenis_identitas,
    ms_customer_h23.no_hp,
    ms_customer_h23.alamat,
    ms_customer_h23.id_kelurahan,
    ms_customer_h23.id_kecamatan,
    ms_customer_h23.id_kabupaten,
    ms_customer_h23.id_provinsi,
    ms_customer_h23.id_tipe_kendaraan,
    ms_customer_h23.id_warna,
    ms_customer_h23.no_mesin,
    ms_customer_h23.no_rangka,
    ms_customer_h23.tahun_produksi,
    ms_customer_h23.no_polisi,
    ms_customer_h23.id_cdb,
    ms_customer_h23.no_spk,
    ms_customer_h23.id_dealer,
    ms_customer_h23.created_at,
    ms_customer_h23.created_by,
    ms_customer_h23.updated_at,
    ms_customer_h23.updated_by,
    ms_customer_h23.ganti_customer,
    ms_customer_h23.tgl_pembelian,
    ms_customer_h23.jenis_kelamin,
    ms_customer_h23.alamat_identitas,
    ms_customer_h23.email,
    ms_customer_h23.nama_stnk,
    ms_customer_h23.jenis_customer_beli,
    ms_customer_h23.id_agama,
    ms_customer_h23.id_kelurahan_identitas,
    ms_customer_h23.longitude,
    ms_customer_h23.latitude,
    ms_customer_h23.id_dealer_h1,
    ms_customer_h23.is_dealer,
    ms_customer_h23.is_direct_sales,
    ms_customer_h23.tgl_lahir,
    ms_customer_h23.id_pekerjaan,
    ms_customer_h23.jangka_waktu_top,
    ms_customer_h23.instagram,
    ms_customer_h23.facebook,
    ms_customer_h23.twitter,
    ms_customer_h23.sumber_data,
    ms_customer_h23.nama_file_inject,
    ms_customer_h23.generated_inject_id,
    ms_customer_h23.input_from,tr_h2_manage_booking.id_booking_int,
    tr_h2_manage_booking.id_booking,
    tr_h2_manage_booking.id_dealer,
    tr_h2_manage_booking.id_customer,
    tr_h2_manage_booking.tgl_servis,
    tr_h2_manage_booking.jam_servis,
    tr_h2_manage_booking.id_pit,
    tr_h2_manage_booking.id_type,
    tr_h2_manage_booking.created_at,
    tr_h2_manage_booking.created_by,
    tr_h2_manage_booking.updated_at,
    tr_h2_manage_booking.updated_by,
    tr_h2_manage_booking.status,
    tr_h2_manage_booking.alasan_cancel,
    tr_h2_manage_booking.cancel_at,
    tr_h2_manage_booking.cancel_by,
    tr_h2_manage_booking.customer_from,
    tr_h2_manage_booking.nama_pembawa,
    tr_h2_manage_booking.keluhan,
    tr_h2_manage_booking.carrier_phone,
    tr_h2_manage_booking.carrier_name, CONCAT(ms_customer_h23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(ms_customer_h23.id_warna,' | ',warna) AS warna, id_sa_form,tr_h2_manage_booking.customer_apps_booking_number
      FROM tr_h2_manage_booking 
      JOIN ms_customer_h23 ON tr_h2_manage_booking.id_customer=ms_customer_h23.id_customer
      LEFT JOIN ms_tipe_kendaraan AS tk ON ms_customer_h23.id_tipe_kendaraan=tk.id_tipe_kendaraan
      LEFT JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
      LEFT JOIN tr_h2_sa_form ON tr_h2_sa_form.id_booking=tr_h2_manage_booking.id_booking
      $where
      ORDER BY tr_h2_manage_booking.created_at DESC");
  }

  function wo_detail($filter = null)
  {
    $id_dealer  = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 ";
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
      if ($id_dealer != '') {
        $where .= "AND tr_h2_wo_dealer.id_dealer='$id_dealer' ";
      }
    }

    if ($filter != null) {
      if (isset($filter['id_work_order'])) {
        $id_work_order = $filter['id_work_order'];
        $where .= "AND wo_pk.id_work_order='" . $id_work_order . "'";
      }
      if (isset($filter['id_surat_jalan'])) {
        $id_surat_jalan = $filter['id_surat_jalan'];
        $where .= "AND wo_pk.id_surat_jalan='" . $id_surat_jalan . "'";
      }
      if (isset($filter['pekerjaan_luar'])) {
        $where .= "AND wo_pk.pekerjaan_luar='{$filter['pekerjaan_luar']}'";
      }
      if (isset($filter['surat_jalan_null'])) {
        $where .= "AND wo_pk.id_surat_jalan IS NULL";
      }
    }

    $details = $this->db->query("SELECT wo_pk.id_work_order ,kategori,ms_h2_jasa.id_type AS job_type,wo_pk.tipe_motor,wo_pk.id_jasa AS pekerjaan,wo_pk.harga,wo_pk.waktu,wo_pk.id_jasa,ms_h2_jasa.id_type,
        CASE WHEN need_parts='y' OR need_parts=1 
          THEN 'Yes' 
          ELSE 'No' 
        END AS need_parts, '' AS masukkan_wo,kategori,ms_h2_jasa.id_jasa AS jasa,pekerjaan_luar,
        CASE 
          WHEN wo_pk.id_promo IS NULL THEN ''
          ELSE wo_pk.id_promo
        END AS id_promo,
        pr.nama_promo, prj.tipe_diskon, prj.diskon,ms_h2_jasa.deskripsi,jst.deskripsi AS desk_type,wo_pk.id_tipe_servis,sts.tipe_servis,frt_claim,labour_cost,
        CASE 
          WHEN wo_pk.pekerjaan_batal IS NULL THEN 0
          WHEN wo_pk.pekerjaan_batal=0 THEN 0
          WHEN wo_pk.pekerjaan_batal=1 THEN 1
          ELSE 0
        END AS pekerjaan_batal
        FROM tr_h2_wo_dealer_pekerjaan AS wo_pk
        JOIN tr_h2_wo_dealer ON tr_h2_wo_dealer.id_work_order=wo_pk.id_work_order
        JOIN ms_h2_jasa ON ms_h2_jasa.id_jasa=wo_pk.id_jasa
        LEFT JOIN ms_h2_jasa_type jst ON ms_h2_jasa.id_type=jst.id_type
        LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wo_pk.id_promo AND prj.id_jasa=wo_pk.id_jasa
        LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
        LEFT JOIN setup_h2_tipe_servis sts ON sts.id=wo_pk.id_tipe_servis
        $where
      ");
    $set_sj = '';
    foreach ($details->result() as $rs) {
      if (isset($filter['surat_jalan_null'])) {
        $rs->buat_sj = false;
        $set_sj = ", '' AS set_sj ";
      }
      $status_picking_slip = "SELECT ps.status 
          FROm tr_h3_dealer_sales_order_parts prts
          JOIN tr_h3_dealer_picking_slip ps ON ps.nomor_so=prts.nomor_so
          WHERE prts.id_part=tr_h2_wo_dealer_parts.id_part AND prts.nomor_so=tr_h2_wo_dealer_parts.nomor_so ORDER BY ps.nomor_ps LIMIT 1
          ";
      $return = "SELECT IFNULL(kuantitas_return,0) FROM tr_h3_dealer_sales_order_parts spt WHERE spt.nomor_so=tr_h2_wo_dealer_parts.nomor_so AND spt.id_part=tr_h2_wo_dealer_parts.id_part";

      $rs->parts = $this->db->query("SELECT tr_h2_wo_dealer_parts.id_work_order,
      tr_h2_wo_dealer_parts.id_jasa,
      tr_h2_wo_dealer_parts.id_part,
      tr_h2_wo_dealer_parts.qty,
      tr_h2_wo_dealer_parts.harga,
      tr_h2_wo_dealer_parts.sudah_terbuat_picking_slip,
      tr_h2_wo_dealer_parts.id_kirim_part,
      tr_h2_wo_dealer_parts.id_gudang,
      tr_h2_wo_dealer_parts.id_rak,
      tr_h2_wo_dealer_parts.nomor_so,
      tr_h2_wo_dealer_parts.jenis_order,
      tr_h2_wo_dealer_parts.tambahan,
      tr_h2_wo_dealer_parts.id_surat_jalan,
      tr_h2_wo_dealer_parts.tipe_diskon,
      tr_h2_wo_dealer_parts.diskon_value,
      tr_h2_wo_dealer_parts.id_promo,
      tr_h2_wo_dealer_parts.order_to,
      tr_h2_wo_dealer_parts.send_notif,
      tr_h2_wo_dealer_parts.id_booking,
      tr_h2_wo_dealer_parts.part_utama,
      tr_h2_wo_dealer_parts.ppn,
      tr_h2_wo_dealer_parts.subtotal,
      tr_h2_wo_dealer_parts.pekerjaan_batal,
      tr_h2_wo_dealer_parts.nomor_ps,
      tr_h2_wo_dealer_parts.id_part_int,
      CASE
        WHEN no_njb IS NOT NULL THEN tr_h2_wo_dealer_parts.qty-IFNULL(($return),0)
        ELSE tr_h2_wo_dealer_parts.qty
      END AS qty,
      tr_h2_wo_dealer_parts.harga AS harga_dealer_user,CASE 
      WHEN jenis_order ='HLO' THEN
        CASE WHEN dl.id_dealer IS NOT NULL THEN nama_dealer
        ELSE 'MD'
        END
    END order_to_name, nama_part,($return)as qty_return,($status_picking_slip) as status_picking_slip $set_sj FROM tr_h2_wo_dealer_parts
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=tr_h2_wo_dealer_parts.id_work_order
          JOIN ms_part ON ms_part.id_part=tr_h2_wo_dealer_parts.id_part
          LEFT JOIN ms_dealer dl ON dl.id_dealer=tr_h2_wo_dealer_parts.order_to
          WHERE tr_h2_wo_dealer_parts.id_work_order='$id_work_order' AND id_jasa='$rs->id_jasa' ORDER BY part_utama DESC")->result();
      $rs->parts_demand = [];
      $dt_details[] = $rs;
    }

    if (isset($dt_details)) {
      return $dt_details;
    }
  }
  function get_part($id_part)
  {
    return $this->db->query("SELECT harga_dealer_user FROM ms_part WHERE id_part='$id_part'");
  }

  function getSAParts($filter = null)
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
      if (isset($filter['order_to'])) {
        $where .= " AND order_to='{$filter['order_to']}' ";
      }
      if (isset($filter['group_by_order_to'])) {
        if ($filter['group_by_order_to'] == true) {
          $where .= " GROUP BY order_to ";
        }
      }
    }
    return  $this->db->query("SELECT sfp.id_part,sfp.qty,sfp.jenis_order,sfp.harga,nama_part,send_notif,order_to, 
    CASE 
      WHEN jenis_order ='HLO' THEN
        CASE WHEN dl.id_dealer IS NOT NULL THEN nama_dealer
        ELSE 'MD'
        END
    END order_to_name
     FROM tr_h2_sa_form_parts AS sfp
    JOIN ms_part ON ms_part.id_part=sfp.id_part
    LEFT JOIN ms_dealer dl ON dl.id_dealer=sfp.order_to
    $where ");
  }

  function getSANeedParts($filter = null)
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
    FROM tr_h2_sa_form_parts 
    $where ")->row()->tot;
  }

  function get_tot_waktu($id_work_order)
  {
    return $this->db->query("SELECT IFNULL(SUM(detik),0) AS tot FROM tr_h2_wo_dealer_waktu WHERE id_work_order='$id_work_order'")->row()->tot;
  }

  function get_last_waktu_wo($id_work_order)
  {
    return $this->db->query("SELECT stats,set_at FROM tr_h2_wo_dealer_waktu WHERE id_work_order='$id_work_order' ORDER BY set_at DESC LIMIT 1");
  }

  function get_absen_mekanik($filter = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $tot = "SELECT count(id_karyawan_dealer) FROM tr_h2_absen_mekanik_detail WHERE id_absen=tam.id_absen";
    $tot_hadir = "SELECT count(id_karyawan_dealer) FROM tr_h2_absen_mekanik_detail WHERE id_absen=tam.id_absen AND aktif=1";
    $tot_tidak_hadir = "SELECT count(id_karyawan_dealer) FROM tr_h2_absen_mekanik_detail WHERE id_absen=tam.id_absen AND aktif<>1";

    $where = "WHERE id_dealer='$id_dealer'";
    // if ($filter != null) {
    //   foreach ($filter as $key => $val) {
    //     $field = array_keys($val);
    //     $field = $field[0];
    //     $where .= " AND $field='$val[$field]'";
    //   }
    // }
    if (isset($filter['tanggal'])) {
      $where .= " AND tam.tanggal='{$filter['tanggal']}' ";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (tam.created_at LIKE '%$search%'
              OR tam.tanggal LIKE '%$search%'
              ) 
        ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'view') {
          $order_column = ['tam.tanggal', "($tot)", "($tot_hadir)", "($tot_tidak_hadir)", NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order = " ORDER BY tam.created_at DESC ";
      }
    } else {
      $order = '';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT id_absen,tanggal,
      ($tot) AS tot, 
      ($tot_hadir) AS tot_hadir,
      ($tot_tidak_hadir) AS tot_tidak_hadir
    FROM tr_h2_absen_mekanik AS tam $where $order $limit");
  }

  function get_detail_absen_mekanik($id_absen = null, $tanggal = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $where = "WHERE id_dealer='$id_dealer' 
                AND tr_h2_absen_mekanik_detail.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
                AND aktif=1";
    if ($tanggal != null) {
      $where .= " AND tr_h2_absen_mekanik.tanggal='$tanggal'";
    }

    $get_data = $this->db->query("SELECT id_karyawan_dealer,id_flp_md,honda_id,nama_lengkap,
        CASE 
        WHEN 
          (SELECT aktif 
           FROM tr_h2_absen_mekanik_detail
           JOIN tr_h2_absen_mekanik ON tr_h2_absen_mekanik.id_absen=tr_h2_absen_mekanik_detail.id_absen 
           $where
          ) IS NULL THEN
          CASE WHEN
            (SELECT count(aktif) 
            FROM tr_h2_absen_mekanik_detail
            JOIN tr_h2_absen_mekanik ON tr_h2_absen_mekanik.id_absen=tr_h2_absen_mekanik_detail.id_absen 
            WHERE id_dealer='$id_dealer' 
            AND tr_h2_absen_mekanik_detail.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
            AND tr_h2_absen_mekanik.tanggal='$tanggal'
            )=1 THEN false
          ELSE true
          END
        WHEN 
          (SELECT aktif 
           FROM tr_h2_absen_mekanik_detail
           JOIN tr_h2_absen_mekanik ON tr_h2_absen_mekanik.id_absen=tr_h2_absen_mekanik_detail.id_absen 
           $where
          ) IS NOT NULL THEN true
        END AS aktif,jbt.jabatan
        FROM ms_karyawan_dealer
        JOIN ms_jabatan jbt ON jbt.id_jabatan=ms_karyawan_dealer.id_jabatan
        WHERE id_dealer='$id_dealer'
        AND ms_karyawan_dealer.id_jabatan IN('JBT-042','JBT-043')
        ORDER BY nama_lengkap ASC
        ");
    return $get_data;
  }

  function get_select_wo($search, $mode)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $where = "WHERE wod.id_dealer='$id_dealer' AND wod.status='closed'";
    if ($mode == 'create_njb') {
      $where .= "AND no_njb IS NULL";
    }
    if ($mode == 'create_nsc') {
      $where .= "AND no_nsc IS NULL";
    }
    $where .= " AND (id_work_order LIKE '%$search%' OR no_polisi LIKE '%$search%')";
    return $this->db->query("SELECT id_work_order AS id, CONCAT(id_work_order,' | ',no_polisi) AS text
      FROM tr_h2_wo_dealer wod
      JOIN tr_h2_sa_form AS sa_form ON sa_form.id_sa_form=wod.id_sa_form
      JOIN ms_customer_h23 AS cus ON cus.id_customer=sa_form.id_customer
      $where
    ")->result();
  }
  function getPekerjaanWO($id_work_order)
  {
    // send_json($id_work_order);
    if (is_array($id_work_order)) {
      if (isset($id_work_order['id_dealer'])) {
        $id_dealer = $id_work_order['id_dealer'];
      } else {
        $id_dealer = $this->m_admin->cari_dealer();
      }
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $where = "WHERE id_dealer='$id_dealer'";
    if (is_array($id_work_order)) {
      $filter = $id_work_order;
      if (isset($filter['id_work_order'])) {
        $where .= " AND wdp.id_work_order='{$filter['id_work_order']}'";
      }
      if (isset($filter['pekerjaan_luar'])) {
        $where .= " AND wdp.pekerjaan_luar='{$filter['pekerjaan_luar']}'";
      }
      if (isset($filter['pekerjaan_batal'])) {
        $where .= " AND (wdp.pekerjaan_batal='{$filter['pekerjaan_batal']}' OR wdp.pekerjaan_batal IS NULL)";
      }
      if (isset($filter['id_surat_jalan_null'])) {
        $where .= " AND wdp.id_surat_jalan IS NULL";
      }
      if (isset($filter['id_surat_jalan_not_null'])) {
        $where .= " AND wdp.id_surat_jalan IS NOT NULL";
      }
      if (isset($filter['id_type_in'])) {
        $where .= " AND mjt.id_type IN({$filter['id_type_in']})";
      }
    } else {
      $where .= " AND wdp.id_work_order='$id_work_order'";
    }

    $diskon = " ROUND(IFNULL((CASE
    WHEN wdp.id_promo IS NOT NULL THEN
      CASE 
        WHEN prj.tipe_diskon='rupiah' THEN prj.diskon
        ELSE 
          CASE 
            WHEN wo.pkp_njb=1 THEN (wdp.harga) * (prj.diskon/100)
            ELSE wdp.harga * (prj.diskon/100)
          END
      END
    ELSE 0
    END),0))";
    return $this->db->query("SELECT mj.deskripsi,kategori,mjt.deskripsi AS job_type,wdp.id_jasa,wdp.harga,wdp.waktu,mj.deskripsi AS pekerjaan,
    CASE 
      WHEN wdp.id_promo IS NULL THEN ''
      ELSE wdp.id_promo
    END AS id_promo,
    pr.nama_promo,prj.tipe_diskon,IFNULL(prj.diskon,0) AS diskon,
    $diskon AS diskon_rp,(wdp.harga-$diskon) AS harga_net,subtotal,wdp.pekerjaan_batal,wdp.id_work_order
      FROM tr_h2_wo_dealer_pekerjaan AS wdp
      JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wdp.id_work_order
      JOIN ms_h2_jasa AS mj ON mj.id_jasa=wdp.id_jasa
      JOIN ms_h2_jasa_type AS mjt ON mjt.id_type=mj.id_type
      LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wdp.id_promo AND prj.id_jasa=wdp.id_jasa
      LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo
      $where
      ");
  }

  function getPartsWO($id_work_order)
  {
    $where = "WHERE 1=1 ";
    if (is_array($id_work_order)) {
      $filter = $id_work_order;
      if (isset($filter['id_kirim_null'])) {
        $where .= " AND id_kirim_part IS NULL ";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND wps.id_work_order='{$filter['id_work_order']}' ";
      }
      if (isset($filter['nomor_so_not_null'])) {
        $where .= " AND wps.nomor_so IS NOT NULL ";
      }
      if (isset($filter['jenis_order'])) {
        $where .= " AND wps.jenis_order='{$filter['jenis_order']}' ";
      }
    } else {
      $where =  "WHERE id_work_order='$id_work_order'";
    }
    return $this->db->query("SELECT wps.id_part,qty,wps.harga,nama_part,wps.id_gudang,wps.id_jasa,wps.id_rak,
      CASE
        WHEN sudah_terbuat_picking_slip = 0 THEN 'Awal'
        ELSE 'Telah di picking'
      END AS picking,wps.id_promo,wps.diskon_value,wps.tipe_diskon,ms_part.id_part_int,wps.id_work_order
      FROM tr_h2_wo_dealer_parts wps
      JOIN ms_part ON ms_part.id_part=wps.id_part
      $where
      ");
  }

  function cekSRBU($no_mesin)
  {
    return $this->db->query("SELECT COUNT(no_mesin) AS c FROM tr_h2_unit_srbu WHERE no_mesin='$no_mesin'")->row()->c;
  }

  function getCDB($filter = null)
  {
    $where = "WHERE 1=1 AND tr_cdb.id_cdb NOT EXISTS (SELECT id_cdb FROM ms_customer_h23 WHERE id_cdb=tr_cdb.id_cdb) ";
    if ($filter != null) {
      if (isset($filter['id_cdb'])) {
        $where .= "AND tr_cdb.id_cdb='{$filter['id_cdb']}' ";
      }
    }
    return $this->db->query("SELECT tr_cdb.id_cdb,
    tr_spk.nama_konsumen,tr_spk.no_hp,tr_spk.alamat,(SELECT no_plat FROM tr_tandaterima_stnk_konsumen_detail WHERE no_mesin=tr_sales_order.no_mesin ORDER BY id DESC LIMIT 1) AS no_polisi,id_tipe_kendaraan,id_warna,no_mesin,no_rangka,tahun_produksi,id_kelurahan,id_kecamatan,id_kabupaten,id_provinsi,tr_cdb.no_spk,no_ktp,LEFT(tr_sales_order.tgl_cetak_invoice,10) AS tgl_pembelian
    FROM tr_cdb 
    JOIN tr_spk ON tr_cdb.no_spk=tr_spk.no_spk
    JOIN tr_sales_order ON tr_cdb.no_spk=tr_sales_order.no_spk
    $where
    ");
  }
  public function get_id_customer($filter = NULL)
  {
    $th        = date('y');
    $bln       = date('m');
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    if (isset($filter['tgl_pembelian'])) {
      $th_bln = date('Y-m', strtotime($filter['tgl_pembelian']));
      $thbln = date('y/m', strtotime($filter['tgl_pembelian']));
    } else {
      $th_bln    = date('Y-m');
      $thbln     = date('y/m');
    }

    $get_data  = $this->db->query("SELECT id_customer FROM ms_customer_h23
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $gen_number    = substr($row->id_customer, -4);
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/' . sprintf("%'.04d", $gen_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_customer_h23', ['id_customer' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/0001';
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_customer_h23', ['id_customer' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    }
    return strtoupper($new_kode);
  }

  public function get_id_booking()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $ymd     = date('Y-m-d');
    $ymd2     = date('ymd');
    $get_data  = $this->db->query("SELECT id_booking FROM tr_h2_manage_booking
			WHERE LEFT(created_at,10)='$ymd' 
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $id_booking = substr($row->id_booking, -3);
      $new_kode   = 'B_' . $ymd2 . '_' . sprintf("%'.03d", $id_booking + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_manage_booking', ['id_booking' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'B_' . $ymd2 . '_' . sprintf("%'.03d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'B_' . $ymd2 . '_001';
    }
    return strtoupper($new_kode);
  }

  public function get_id_antrian($jenis_customer, $id_dealer = NULL)
  {
    $th        = date('y');
    $bln       = date('m');
    $tgl    = date('Y-m-d');
    $thbln     = date('ymd');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    if ($jenis_customer == 'booking') {
      $cust = 'B';
    }
    if ($jenis_customer == 'reguler') {
      $cust = 'R';
    }
    $get_data  = $this->db->query("SELECT id_antrian FROM tr_h2_sa_form
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,10)='$tgl'
			AND jenis_customer='$jenis_customer'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->id_antrian, -3);
      $new_kode   = $dealer->kode_dealer_md . '/' . $thbln . '/ATR/' . $cust . '/' . sprintf("%'.03d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_sa_form', ['id_antrian' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -3);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/ATR/' . $cust . '/' . sprintf("%'.03d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/ATR/' . $cust . '/001';
    }
    return strtoupper($new_kode);
  }

  function getCustomer23($filter = null)
  {
    $where  = 'WHERE 1=1 ';
    if ($filter != null) {
      if (isset($filter['id_customer'])) {
        $where .= " AND ch23.id_customer='{$filter['id_customer']}' ";
      }
      if (isset($filter['no_mesin'])) {
        $where .= " AND ch23.no_mesin='{$filter['no_mesin']}' ";
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $search = $filter['search'];
          $where .= " AND (ch23.id_customer LIKE '%$search%' 
                  OR ch23.nama_customer LIKE '%$search%'
                  OR ch23.id_tipe_kendaraan LIKE '%$search%'
                  OR ch23.id_warna LIKE '%$search%'
                  OR ch23.no_mesin LIKE '%$search%'
                  OR ch23.no_rangka LIKE '%$search%'
                  )
              ";
        }
      }
      $limit = '';
      if (isset($filter['limit'])) {
        $limit = " {$filter['limit']}";
      }
    }
    return $this->db->query("SELECT id_customer,nama_customer,jenis_kelamin,no_identitas,jenis_identitas,alamat_identitas,no_hp,ch23.email,ch23.alamat,ch23.id_kelurahan,kelurahan,kecamatan,kabupaten,provinsi,ch23.id_tipe_kendaraan,ch23.id_warna,CONCAT(ch23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm,CONCAT(ch23.id_warna,' | ',warna) AS warna,ch23.no_mesin,ch23.no_rangka,ch23.no_polisi,nama_stnk,ch23.tgl_pembelian,ch23.tahun_produksi,ch23.tgl_lahir 
    FROM ms_customer_h23 AS ch23 
      LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=ch23.id_kelurahan
      LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
      LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
      LEFT JOIN ms_dealer AS msd ON msd.id_dealer=ch23.id_dealer
      JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      JOIN ms_warna AS wr ON wr.id_warna=ch23.id_warna
      $where
      $limit
    ");
  }

  function cekSendPartWO($id_work_order)
  {
    // $id_dealer     = $this->m_admin->cari_dealer();
    return  $this->db->query("SELECT count(id_part)AS tot FROM tr_h2_wo_dealer_parts 
    WHERE id_work_order='$id_work_order' AND id_kirim_part IS NULL AND jenis_order='reguler'")->row()->tot;
  }
  function cekWONeedParts($id_work_order)
  {
    return  $this->db->query("SELECT count(id_part)AS tot FROM tr_h2_wo_dealer_parts 
    WHERE id_work_order='$id_work_order'")->row()->tot;
  }
  public function get_id_kirim_part()
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
      $get_data  = $this->db->query("SELECT id_kirim_part FROM tr_h2_kirim_ke_part_counter
			WHERE LEFT(created_at,7)='$th_bln' and id_dealer='$id_dealer'
      	ORDER BY created_at DESC LIMIT 0,1");
    } else {
      $get_data  = $this->db->query("SELECT id_kirim_part FROM tr_h2_kirim_ke_part_counter
			WHERE LEFT(created_at,7)>='$th_bln' and id_dealer='$id_dealer'
    	ORDER BY id_kirim_part DESC LIMIT 0,1");
    }

    if ($get_data->num_rows() > 0) {
      $row           = $get_data->row();
      $id_kirim_part = substr($row->id_kirim_part, -4);
      $new_kode      = $dealer->kode_dealer_md . '/SEND/' . $ym . '/' . sprintf("%'.04d", $id_kirim_part + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_kirim_ke_part_counter', ['id_kirim_part' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/SEND/' . $ym . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $dealer->kode_dealer_md . '/SEND/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getKaryawanDealerLogin($id_user)
  {
    return $this->db->query("SELECT ms_user.id_user,
    ms_user.jenis_user,
    ms_user.id_karyawan_dealer,
    ms_user.username,
    ms_user.password,
    ms_user.admin_password,
    ms_user.id_user_group,
    ms_user.avatar,
    ms_user.last_login_date,
    ms_user.last_login_duration,
    ms_user.last_login_ip,
    ms_user.last_mac_address,
    ms_user.input_date,
    ms_user.update_date,
    ms_user.input_username,
    ms_user.active,
    ms_user.session_id,
    ms_user.status,
    ms_user.jenis_user_bagian,
    ms_user.sc_reset_pass_id,
    ms_user.sc_reset_pass_at,
    ms_user.sc_reset_pass_ke,
    ms_user.sc_reset_proses_at,
    ms_user.akses_dms,
    ms_user.akses_sc,
    ms_user.role_sc,
    ms_user.username_sc,
    ms_user.password_sc,
    ms_user.active_sc,
    ms_user.sc_created_date,
    ms_user.sc_updated_date,
    ms_user.last_login_sc,
    ms_user.regid,kd.id_karyawan_dealer_int,
kd.id_karyawan_dealer,
kd.id_flp_md,
kd.nama_lengkap,
kd.id_dealer,
kd.id_pos_dealer,
kd.id_divisi,
kd.id_jabatan,
kd.nik,
kd.tempat_lahir,
kd.tgl_lahir,
kd.alamat,
kd.id_agama,
kd.jk,
kd.no_hp,
kd.no_telp,
kd.email,
kd.tgl_masuk,
kd.tgl_keluar,
kd.alasan_keluar,
kd.created_at,
kd.created_by,
kd.updated_at,
kd.updated_by,
kd.active,
kd.honda_id,
kd.image FROM ms_user
      JOIN ms_karyawan_dealer AS kd ON kd.id_karyawan_dealer=ms_user.id_karyawan_dealer
      WHERE id_user='$id_user'
    ");
  }
  public function get_id_satisfaction()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $ymd     = date('Y-m-d');
    $ym     = date('ym');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

    $get_data  = $this->db->query("SELECT id_satisfaction,
    id_referensi,
    id_dealer,
    level,
    created_at,
    created_by,
    updated_at,
    updated_by,
    sumber FROM tr_h2_service_satisfaction
			WHERE LEFT(created_at,7)='$th_bln'  AND id_dealer='$id_dealer'
      ORDER BY created_at DESC LIMIT 0,1");

    if ($get_data->num_rows() > 0) {
      $row           = $get_data->row();
      $id_satisfaction = substr($row->id_satisfaction, -4);
      $new_kode      = $dealer->kode_dealer_md . '/STF/' . $ym . '/' . sprintf("%'.04d", $id_satisfaction + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_service_satisfaction', ['id_satisfaction' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/STF/' . $ym . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $dealer->kode_dealer_md . '/STF/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }
  function getWOSatisfaction($filter)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE sat.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['id_work_order'])) {
        $where .= " AND sat.id_referensi='{$filter['id_work_order']}' ";
      }
    }
    return $this->db->query("SELECT sat.id_satisfaction,
    sat.id_referensi,
    sat.id_dealer,
    sat.level,
    sat.created_at,
    sat.created_by,
    sat.updated_at,
    sat.updated_by,
    sat.sumber,
    wo.id_work_order_int,
    wo.id_work_order,
    wo.id_dealer,
    wo.id_sa_form,
    wo.nomor_so,
    wo.id_karyawan_dealer,
    wo.status,
    wo.created_at,
    wo.created_by,
    wo.updated_at,
    wo.updated_by,
    wo.start_at,
    wo.start_by,
    wo.closed_at,
    wo.closed_by,
    wo.servis_dealer_lain,
    wo.no_njb,
    wo.waktu_njb,
    wo.created_njb_at,
    wo.created_njb_by,
    wo.status_njb,
    wo.no_nsc,
    wo.waktu_nsc,
    wo.created_nsc_at,
    wo.created_nsc_by,
    wo.status_nsc,
    wo.tipe_pembayaran,
    wo.cetak_njb_ke,
    wo.cetak_njb_at,
    wo.cetak_njb_by,
    wo.pkp_njb,
    wo.cetak_gab_ke,
    wo.cetak_gab_at,
    wo.cetak_gab_by,
    wo.total_part,
    wo.total_jasa,
    wo.total_ppn,
    wo.total_tanpa_ppn,
    wo.grand_total,
    wo.tampil_ppn,
    wo.id_rekap_kpb,
    wo.tgl_jatuh_tempo,
    wo.document,
    wo.saran_mekanik,
    wo.assigned_at,
    wo.assigned_by,
    wo.cetak_wo_ke,
    wo.cetak_wo_by,
    wo.cetak_wo_at,
    wo.input_from
      FROM tr_h2_service_satisfaction AS sat
      JOIN tr_h2_wo_dealer AS wo ON wo.id_work_order=sat.id_referensi      
      $where
      ORDER BY sat.created_at DESC");
  }

  function getAgama()
  {
    return $this->db->query("SELECT id_agama,agama FROM ms_agama ORDER BY id_agama ASC");
  }
  function getPekerjaan()
  {
    return $this->db->query("SELECT id_pekerjaan,pekerjaan FROM ms_pekerjaan ORDER BY id_pekerjaan ASC");
  }
  public function get_id_pembawa($id_dealer = NULL)
  {
    $th_bln    = date('Y-m');
    $ymd       = date('Y-m-d');
    $ym        = date('ym');
    if ($id_dealer == NULL) {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

    $get_data  = $this->db->query("SELECT id_pembawa FROM ms_h2_pembawa
			WHERE LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");

    if ($get_data->num_rows() > 0) {
      $row           = $get_data->row();
      $id_pembawa = substr($row->id_pembawa, -5);
      $new_kode      = $dealer->kode_dealer_md . '/PBW/' . $ym . '/' . sprintf("%'.05d", $id_pembawa + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_h2_pembawa', ['id_pembawa' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = $dealer->kode_dealer_md . '/PBW/' . $ym . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $dealer->kode_dealer_md . '/PBW/' . $ym . '/00001';
    }
    return strtoupper($new_kode);
  }
  public function get_id_surat_jalan()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $ymd     = date('Y-m-d');
    $ym     = date('y/m');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

    $get_data  = $this->db->query("SELECT id_surat_jalan 
      FROM tr_h2_wo_dealer_surat_jalan_keluar sj
      JOIN tr_h2_wo_dealer wo ON wo.id_work_order=sj.id_work_order
			WHERE LEFT(sj.created_at,7)='$th_bln' AND wo.id_dealer='$id_dealer'
      ORDER BY sj.created_at DESC LIMIT 0,1");
    // $wo             = substr($wo, -4);
    if ($get_data->num_rows() > 0) {
      $row            = $get_data->row();
      $id_surat_jalan = substr($row->id_surat_jalan, -4);

      $new_kode       = 'SJK/' . $dealer->kode_dealer_md . '/' . $ym . sprintf("%'.04d", $id_surat_jalan + 1);
      $i              = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_wo_dealer_surat_jalan_keluar', ['id_surat_jalan' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = 'SJK/' . $dealer->kode_dealer_md . '/' . $ym . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'SJK/' . $dealer->kode_dealer_md . '/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }
  public function get_id_follow_up()
  {
    $th_bln    = date('Y-m');
    $ymd       = date('Y-m-d');
    $my        = date('my');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

    $get_data  = $this->db->query("SELECT id_follow_up FROM tr_h2_follow_up_after_service
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer='$id_dealer'
      ORDER BY created_at DESC LIMIT 0,1");

    if ($get_data->num_rows() > 0) {
      $row           = $get_data->row();
      $id_follow_up = substr($row->id_follow_up, -4);
      $new_kode      = 'FUSRVC/' . $dealer->kode_dealer_md . '/' . $my . '/' . sprintf("%'.04d", $id_follow_up + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_follow_up_after_service', ['id_follow_up' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = 'FUSRVC/' . $dealer->kode_dealer_md . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'FUSRVC/' . $dealer->kode_dealer_md . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }

  public function get_id_promo($kode, $sumber = NULL)
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $where  = '';
    if ($sumber == 'dealer') {
      $where = "AND EXISTS (SELECT id_dealer FROM ms_promo_servis_dealer WHERE id_promo=ms_promo_servis.id_promo AND id_dealer='$kode->id_dealer') AND sumber='dealer'";
      $kode = $kode->kode_dealer_md;
    }
    $get_data  = $this->db->query("SELECT id_promo FROM ms_promo_servis
			WHERE LEFT(created_at,7)='$th_bln' 
      $where
      ORDER BY created_at DESC LIMIT 0,1");

    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_promo = substr($row->id_promo, -4);
      $new_kode = 'PR/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $id_promo + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('ms_promo_servis', ['id_promo' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = 'PR/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'PR/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }

  function get_promo_servis($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {

      if (isset($filter['id_promo'])) {
        $where .= " AND pr.id_promo='" . $filter['id_promo'] . "'";
      }
    }
    return $this->db->query("SELECT pr.id_promo,start_date,nama_promo,end_date,aktif,kode_promo_customer_apps
    FROM ms_promo_servis pr
    $where
    ");
  }

  function get_promo_servis_jasa($filter = null)
  {
    $order_column = array('id_jasa ', 'nama_promo', 'deskripsi', 'tipe_diskon', 'diskon', null);
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_promo'])) {
        $where .= " AND prsj.id_promo='{$filter['id_promo']}'";
      }
      if (isset($filter['id_jasa'])) {
        $where .= " AND prsj.id_jasa='{$filter['id_jasa']}'";
      }
      if (isset($filter['cek_periode'])) {
        $where .= " AND '{$filter['cek_periode']}' BETWEEN ps.start_date AND ps.end_date ";
      }
      $sel_hrg_dealer = 0;
      if (isset($filter['id_dealer'])) {
        $where .= " AND EXISTS (SELECT id_dealer FROM ms_promo_servis_dealer WHERE id_promo=prsj.id_promo AND id_dealer='{$filter['id_dealer']}') ";
        $sel_hrg_dealer = "IFNULL((SELECT harga_dealer FROM ms_h2_jasa_dealer WHERE id_jasa=js.id_jasa AND id_dealer='{$filter['id_dealer']}'),0)";
      } else {
        $id_dealer = $this->m_admin->cari_dealer();
        $sel_hrg_dealer = "IFNULL((SELECT harga_dealer FROM ms_h2_jasa_dealer WHERE id_jasa=js.id_jasa AND id_dealer='$id_dealer'),0)";
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $search = $this->db->escape_str($filter['search']);
          $where .= " AND (ps.nama_promo LIKE '%$search%' 
                  OR ps.start_date LIKE '%$search%'
                  OR ps.end_date LIKE '%$search%'
                  OR ps.id_promo LIKE '%$search%'
                  )
              ";
        }
      }
      if (isset($filter['order'])) {
        $order = $this->db->escape_str($filter['order']);
        if ($order == '') {
          $where .= "ORDER BY ps.created_at DESC ";
        } else {
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $where .= " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['group_by_id_promo'])) {
        $where .= " GROUP BY ps.id_promo";
      }
      if (isset($filter['limit'])) {
        $where .= $filter['limit'];
      }
    }
    return $this->db->query("SELECT prsj.id_promo,prsj.id_jasa,prsj.tipe_diskon,prsj.diskon,js.deskripsi,ps.nama_promo,start_date,end_date,ps.id_promo_int,CASE WHEN ($sel_hrg_dealer)=0 THEN js.harga ELSE ($sel_hrg_dealer) END harga
    FROM ms_promo_servis_jasa prsj
    JOIN ms_promo_servis ps ON ps.id_promo=prsj.id_promo
    JOIN ms_h2_jasa js ON js.id_jasa=prsj.id_jasa
    $where
    ");
  }
  function get_promo_part($filter = null)
  {
    $order_column = array('id_jasa ', 'nama_promo', 'deskripsi', 'tipe_diskon', 'diskon', null);
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_dealer'])) {
        $where .= " AND pp.id_dealer='{$filter['id_dealer']}'";
      }
      if (isset($filter['cek_periode'])) {
        // $where .= " AND '{$filter['cek_periode']}' BETWEEN pp.start_date AND pp.end_date ";
      }

      if (isset($filter['order'])) {
        $order = $filter['order'];
        if ($order == '') {
          $where .= "ORDER BY pp.created_at DESC ";
        } else {
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $where .= " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        $where .= $filter['limit'];
      }
    }
    return $this->db->query("SELECT pp.id,pp.nama,pp.tipe_diskon_master,diskon_value_master,pp.id_promo
    FROM ms_h3_promo_dealer pp
    $where
    ");
  }
  function get_promo_servis_dealer($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_promo'])) {
        $where .= " AND prsd.id_promo='" . $filter['id_promo'] . "'";
      }
      if (isset($filter['id_dealer'])) {
        $where .= " AND prsd.id_dealer='" . $filter['id_dealer'] . "'";
      }
    }
    return $this->db->query("SELECT prsd.id_promo,prsd.id_dealer,dl.kode_dealer_md,nama_dealer
    FROM ms_promo_servis_dealer prsd
    JOIN ms_dealer dl ON dl.id_dealer=prsd.id_dealer
    $where
    ");
  }
  function getDealer()
  {
    $id_dealer    = $this->m_admin->cari_dealer();
    return $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
  }

  public function get_id_vendor($dealer)
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $where  = '';
    $get_data  = $this->db->query("SELECT id_vendor FROM ms_h2_vendor_pekerjaan_luar
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      $where
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_vendor = substr($row->id_vendor, -4);
      $new_kode = 'VPL/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $id_vendor + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('ms_h2_vendor_pekerjaan_luar', ['id_vendor' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = 'VPL/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'VPL/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }

  function get_vendor_pekerjaan_luar($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE id_dealer='$id_dealer' ";
    if ($filter != null) {

      if (isset($filter['id_vendor'])) {
        $where .= " AND vdr.id_vendor='" . $filter['id_vendor'] . "'";
      }
    }
    return $this->db->query("SELECT id_vendor,nama_vendor,no_hp,alamat,aktif
    FROM ms_h2_vendor_pekerjaan_luar vdr
    $where
    ");
  }

  function get_vendor_pekerjaan_luar_jasa($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_vendor'])) {
        $where .= " AND vdr.id_vendor='{$filter['id_vendor']}'";
      }
    }
    return $this->db->query("SELECT id_vendor,vdr.id_jasa,deskripsi
    FROM ms_h2_vendor_pekerjaan_luar_jasa vdr
    JOIN ms_h2_jasa js ON js.id_jasa=vdr.id_jasa
    $where
    ");
  }

  function cekKPB($params)
  {
    $kpb_ke            = substr($params['kpb_ke'], 3, 1);
    // $kpb_ke            = $kpb_ke[1];

    $id_tipe_kendaraan = $params['id_tipe_kendaraan'];
    $tgl_pembelian       = $params['tgl_pembelian'];

    //Cek Pernah KBP/ Belum
    if (isset($params['cek_md'])) {
      $cek_kpb = $this->db->query("SELECT COUNT(no_mesin) c FROM tr_claim_kpb WHERE no_mesin='{$params['no_mesin']}' AND kpb_ke='$kpb_ke'")->row();
    }else{
      $cek_kpb = $this->db->query("SELECT COUNT(wop.id_work_order) AS c, wop.id_work_order
      FROM tr_h2_wo_dealer_pekerjaan wop
      JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
      JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
      JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      WHERE js.id_type='{$params['kpb_ke']}' AND ch23.no_mesin='{$params['no_mesin']}'
      AND wo.status='closed' AND wop.pekerjaan_batal!=1
      ")->row();
    }

    if ($cek_kpb->c==0) {
      // perlu pengecekan sa form atau wo yg belum status nya close agar tidak terjadi dobel input
      /*
*/
      $tgl_skrg = date('Y-m-d');
      $cek_data_sa = $this->db->query("
        select count(1) as jmlh
        from tr_h2_sa_form a
        join tr_h2_sa_form_pekerjaan b on a.id_sa_form = b.id_sa_form 
        JOIN ms_h2_jasa c ON c.id_jasa = b.id_jasa
      	JOIN ms_customer_h23 e ON e.id_customer=a.id_customer
        left join tr_h2_wo_dealer d on a.id_sa_form = d.id_sa_form
        where (status_form not in ('closed','cancel') or d.status not in ('closed','cancel')) and c.id_type ='{$params['kpb_ke']}' and tgl_servis ='$tgl_skrg' AND e.no_mesin='{$params['no_mesin']}'      
      ")->row()->jmlh;
      
      if($cek_data_sa > 0){
      
      // if(0){
        $resp = [
            'status' => 'tgl_lewat',
            'msg' => "No. Mesin : {$params['no_mesin']} sudah dilakukan penginputan KPB pada hari ini!"
          ];
      }else{

      //Get Batas KPB
      //   if ($kpb_ke > 1) {
      //     for ($i = $kpb_ke; $i < 1; $i--) {
      //       $cek_kpb = $this->db->query("SELECT COUNT(wop.id_work_order) AS c
      // FROM tr_h2_wo_dealer_pekerjaan wop
      // JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
      // JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
      // JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
      // JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
      // WHERE js.id_type='{$params['kpb_ke']}' AND ch23.no_mesin='{$params['no_mesin']}'
      // ")->row()->c;
      //     }
      //   }
        $get_batas = $this->db->query("SELECT id_tipe_kendaraan,batas_maks_kpb,km,toleransi,oli,harga_jasa,kpb_ke FROM ms_kpb_detail WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND kpb_ke='$kpb_ke'");
        if ($get_batas->num_rows() > 0) {
          $batas = $get_batas->row();
          //Cek Tanggal
          $batas_hari = $batas->batas_maks_kpb + $batas->toleransi;
          $tgl_maks = date('Y-m-d', strtotime("+$batas_hari days", strtotime($tgl_pembelian)));
          $date_now = isset($params['tgl_service'])?$params['tgl_service']:date('Y-m-d');
          if (bandingTgl($date_now, $tgl_maks) == 1) {
            $resp = [
              'status' => 'tgl_lewat',
              'msg' => "No. Mesin : {$params['no_mesin']} telah melewati batas tanggal KPB $kpb_ke !"
            ];
          }
          //Cek KM
          if (isset($params['km_terakhir'])) {
            $km_terakhir = $params['km_terakhir'];
            if ($km_terakhir > $batas->km) {
              $resp = [
                'status' => 'km_lewat',
                'msg' => "Telah melewati batas KM KPB $kpb_ke !"
              ];
            }
          }
        } else {
          $resp = ['status' => 'kosong', 'msg' => "Batas KPB $kpb_ke belum ditentukan untuk tipe kendaraan $id_tipe_kendaraan !. Hubungi MD"];
        }
      }
    } else {
      $pesan = "No. Mesin = {$params['no_mesin']} sudah melakukan KPB Ke-$kpb_ke.";
      if (!isset($params['cek_md'])) {
        $pesan .=" ID WO : $cek_kpb->id_work_order";
      }
      $resp = ['status' => 'kosong', 'msg' => $pesan];
    }

    if (empty($resp)) {
      $resp = ['status' => 'oke'];
    }
    return $resp;
  }

  public function get_id_customer_ev($filter = NULL)
  {
    $th        = date('y');
    $bln       = date('m');
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    if (isset($filter['tgl_pembelian'])) {
      $th_bln = date('Y-m', strtotime($filter['tgl_pembelian']));
      $thbln = date('y/m', strtotime($filter['tgl_pembelian']));
    } else {
      $th_bln    = date('Y-m');
      $thbln     = date('y/m');
    }

    $get_data  = $this->db->query("SELECT id_customer FROM ms_customer_h23
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
      AND is_ev = 1 
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $gen_number    = substr($row->id_customer, -4);
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/EV/' . sprintf("%'.04d", $gen_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_customer_h23', ['id_customer' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/EV/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/EV/0001';
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('ms_customer_h23', ['id_customer' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = $dealer->kode_dealer_md . '/' . $thbln . '/CUS/EV/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    }
    return strtoupper($new_kode);
  }
}
