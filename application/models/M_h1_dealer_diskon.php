<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_diskon extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getPengajuanDiskon($filter = NULL)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = dealer()->id_dealer;
    }
    $where = "WHERE pd.id_dealer = '$id_dealer' ";

    if (isset($filter['status'])) {
      if ($filter['status'] != '') {
        $where .= " AND pd.status='{$filter['status']}'";
      }
    }
    if (isset($filter['id_prospek'])) {
      if ($filter['id_prospek'] != '') {
        $where .= " AND pd.id_prospek='{$filter['id_prospek']}'";
      }
    }
    if (isset($filter['id_pengajuan'])) {
      if ($filter['id_pengajuan'] != '') {
        $where .= " AND pd.id_pengajuan='{$filter['id_pengajuan']}'";
      }
    }
    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND pd.status in({$filter['status_in']})";
      }
    }
    if (isset($filter['periode_pengajuan'])) {
      if ($filter['periode_pengajuan'] != '') {
        if ($filter['start_date'] != '') {
          $where .= " AND LEFT(pd.created_at,10) BETWEEN '({$filter['start_date']})' AND '({$filter['end_date']})'";
        }
      }
    }
    if (isset($filter['tahun_bulan'])) {
      if ($filter['tahun_bulan'] != '') {
        $where .= " AND LEFT(pd.created_at,7)='{$filter['tahun_bulan']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (prp.id_dealer LIKE '%$search%'
                            OR prp.nama_konsumen LIKE '%$search%'
                            OR prp.id_prospek LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        // if ($filter['order_column'] == 'view_claim_kpb') {
        //   $order_column = ['kode_dealer_md', 'nama_dealer', 'clm.no_mesin', 'clm.no_rangka', 'clm.no_kpb', 'tk.tipe_ahm', 'clm.kpb_ke', 'clm.tgl_beli_smh', 'clm.created_at', NULL];
        // }
        $order_column = [];
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order .= " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY clm.created_at DESC ";
      }
    } else {
      $order = "ORDER BY tr_prospek.created_at DESC";
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page = $page < 0 ? 0 : $page;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    $select = "pd.id_pengajuan,pd.id_prospek,kd.nama_lengkap,kd.image,kd.jk,pd.nominal_diskon,kd.id_karyawan_dealer_int,
      CASE WHEN dsk.discount_name IS NULL THEN 'Diskon Dealer' ELSE dsk.discount_name END discount_name,
    pd.tipe_pembayaran payment,IFNULL(pd.keterangan,'') keterangan,LEFT(pd.approved_at,10) tgl_konfirmasi,jatah_approve_terpakai,LEFT(pd.created_at,10) tgl_pengajuan,dsk.byk_jatah,pd.status,
    CASE 
      WHEN pd.status='Approved Disc' THEN 'Approved'
      WHEN pd.status='Reject Disc' THEN 'Rejected'
      ELSE ''
    END status_disc,pd.catatan_approval,spk.no_spk,spk.nama_konsumen
    ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(tr_prospek.id_prospek) AS count";
      } elseif ($filter['select'] == 'all') {
        $select = "*";
      } elseif ($filter['select'] == 'sum_nominal') {
        $select = 'SUM(pd.nominal_diskon) sum_nominal';
      } elseif ($filter['select'] == 'sum_nominal_count_data') {
        $select = 'SUM(pd.nominal_diskon) sum_nominal,COUNT(pd.nominal_diskon) AS count';
      }
    }
    return $this->db->query("SELECT 
    $select
    FROM tr_pengajuan_diskon pd
      JOIN tr_prospek prp ON prp.id_prospek=pd.id_prospek
      LEFT JOIN tr_spk spk ON spk.id_customer=prp.id_customer
      JOIN ms_dealer dl ON dl.id_dealer=pd.id_dealer
      LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=pd.id_karyawan_dealer
      LEFT JOIN ms_diskon dsk ON dsk.id_diskon=pd.id_diskon
			$where
      $limit
			");
  }

  function cekDiskon($filter)
  {
    // $spk = $filter['spk'];
    $get_diskon = $this->db->query("SELECT * FROM ms_diskon WHERE '{$filter['id_tipe_kendaraan']}' 
																		IN(SELECT id_tipe_kendaraan FROM ms_diskon_kendaraan WHERE id_diskon=ms_diskon.id_diskon) 
																		AND '{$filter['id_warna']}' IN(SELECT id_warna FROM ms_diskon_kendaraan WHERE id_diskon=ms_diskon.id_diskon)
																		AND '{$filter['id_karyawan_dealer']}' IN(SELECT id_karyawan_dealer FROM ms_diskon_assignment WHERE id_diskon=ms_diskon.id_diskon)
                                    ");
    if ($get_diskon->num_rows() > 0) {
      $get_diskon = $get_diskon->row();
      $id_diskon  = $get_diskon->id_diskon;
      if ($filter['diskon'] > $get_diskon->value) {
        $status = 'Waiting Approval Disc';
      } else {
        $status = 'Approved Disc';
      }
    } else {
      $status = 'Waiting Approval Disc';
    }
    return ['status' => $status, 'id_diskon' => isset($id_diskon) ? $id_diskon : NULL];
  }
  
  function setDiskon($filter)
  {
    $id_prospek = $filter['id_prospek'];
    if (isset($filter['spk'])) {
      $spk = $filter['spk'];
      $filter['id_karyawan_dealer'] = $id_karyawan_dealer = $spk->id_karyawan_dealer;
      $filter['jenis_beli'] = $jenis_beli = $spk->jenis_beli;
    } else {
      $id_karyawan_dealer = $filter['id_karyawan_dealer'];
      $jenis_beli = $filter['jenis_beli'];
    }

    // $result = $this->cekDiskon($filter);
    $tgl=explode('-',tanggal());
    $fcd = [
      'id_dealer'          => $filter['id_dealer'],
      'id_tipe_kendaraan'  => $filter['id_tipe_kendaraan'],
      'id_karyawan_dealer' => $filter['id_karyawan_dealer'],
      'tahun'              => $tgl[0],
      'bulan'              => $tgl[1],
      'tahun_bulan'        => $tgl[0].'-'.$tgl[1],
      'nominal_diskon'     => $filter['diskon']
    ];
    $status_diskon = $this->cekPengajuanDiskonSettingDms($fcd);

    $insert = [
      'id_prospek'         => $id_prospek,
      'tipe_pembayaran'    => $jenis_beli,
      'id_karyawan_dealer' => $id_karyawan_dealer,
      // 'id_diskon'          => $result['id_diskon'],
      'id_tipe_kendaraan'  => $filter['id_tipe_kendaraan'],
      'id_warna'           => $filter['id_warna'],
      'nominal_diskon'     => $filter['diskon'],
      'status'             => $status_diskon,
      'id_dealer'          => $filter['id_dealer'],
      'created_at'         => waktu_full(),
      'created_by'         => $filter['id_user']
    ];
    $this->db->insert('tr_pengajuan_diskon', $insert);
    return $status_diskon;
  }

  function updateDiskon($filter)
  {
    $id_prospek = $filter['id_prospek'];
    $id_karyawan_dealer = $filter['id_karyawan_dealer'];
    $jenis_beli = $filter['jenis_beli'];

    $result = $this->cekDiskon($filter);

    $update = [
      'tipe_pembayaran'    => $jenis_beli,
      'id_karyawan_dealer' => $id_karyawan_dealer,
      'id_diskon'          => $result['id_diskon'],
      'id_tipe_kendaraan'  => $filter['id_tipe_kendaraan'],
      'id_warna'           => $filter['id_warna'],
      'nominal_diskon'     => $filter['diskon'],
      'status'             => $result['status'],
      'id_dealer'          => $filter['id_dealer'],
      'created_at'         => waktu_full(),
      'created_by'         => $filter['id_user']
    ];
    $this->db->update('tr_pengajuan_diskon', $update, ['id_prospek' => $id_prospek]);
  }

  function cekPengajuanDiskonSettingDms($filter)
  {
    $this->load->model('m_dms');
    $dms                   = $this->m_dms->getTargetSalesPeopleByTipe($filter['id_karyawan_dealer'],$filter['id_tipe_kendaraan'],$filter['tahun'],$filter['bulan']);
    $kuota                 = 0;
    $batas_approval_diskon = 0;
    if ($dms) {
      $kuota                 = $dms->kuota_unit_diskon;
      $batas_approval_diskon = $dms->batas_approval_diskon;
    }

    $kuota_terpakai = $this->db->query("SELECT COUNT(tpd.id_prospek) c from tr_pengajuan_diskon tpd 
    JOIN tr_prospek tp ON tp.id_prospek=tpd.id_prospek
    WHERE tp.id_tipe_kendaraan ='{$filter['id_tipe_kendaraan']}' 
    AND tp.id_karyawan_dealer='{$filter['id_karyawan_dealer']}' AND LEFT(tp.created_at,7)='{$filter['tahun_bulan']}' AND tpd.status='Approved Disc' AND active=1
    ")->row()->c;

    if ($kuota>$kuota_terpakai && $batas_approval_diskon>=$filter['nominal_diskon']) {
      return 'Approved Disc';
    }else{
      return 'Waiting Approval Disc';
    }
  }
}
