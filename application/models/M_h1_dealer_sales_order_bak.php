<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_sales_order extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_dms');
  }

  function getSalesOrderGC($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE so.id_dealer = '$id_dealer' ";

    if (isset($filter['id_sales_order_gc'])) {
      if ($filter['id_sales_order_gc'] != '') {
        $where .= " AND so.id_sales_order_gc='{$filter['id_sales_order_gc']}'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk_gc LIKE '%$search%'
                            OR spk.nama_npwp LIKE '%$search%'
                            OR spk.no_npwp LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'history') {
          $order_column = ['spk.no_spk_gc', 'spk.nama_npwp', 'spk.no_npwp', 'spk.alamat', 'spk.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY so.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT so.*,spk.nama_npwp,spk.no_npwp,kd.id_flp_md,kd.nama_lengkap,LEFT(so.created_at,10) AS tgl_so_gc,spk.tgl_spk_gc
    FROM tr_sales_order_gc so
    JOIN tr_spk_gc spk ON spk.no_spk_gc=so.no_spk_gc
    LEFT JOIN tr_prospek_gc prp ON prp.id_prospek_gc=spk.id_prospek_gc
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp.id_karyawan_dealer
		$where
    $order
    $limit
    ");
  }
  function getSalesOrderIndividu($filter = NULL)
  {
    if (dealer()) {
      $id_dealer = dealer()->id_dealer;
    } else {
      $id_dealer = $filter['id_dealer'];
    }
    $where = "WHERE so.id_dealer = '$id_dealer' ";
    $status_on_sc = "CASE WHEN so.tgl_terima_unit_ke_konsumen='0000-00-00' THEN 'Incomplete' ELSE 'Completed' END";
    if (isset($filter['id_sales_order'])) {
      if ($filter['id_sales_order'] != '') {
        $where .= " AND so.id_sales_order='{$filter['id_sales_order']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1)='{$filter['id_karyawan_dealer']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1) IN ({$filter['id_karyawan_dealer_in']})";
      }
    }
    if (isset($filter['bulan_so'])) {
      if ($filter['bulan_so'] != '') {
        $where .= " AND LEFT(so.created_at,7)='{$filter['bulan_so']}'";
      }
    }
    if (isset($filter['status_on_sc'])) {
      if ($filter['status_on_sc'] != '') {
        $where .= " AND $status_on_sc='{$filter['status_on_sc']}'";
      }
    }
    if (isset($filter['status_delivery'])) {
      if ($filter['status_delivery'] != '') {
        $where .= " AND so.status_delivery='{$filter['status_delivery']}'";
      }
    }
    if (isset($filter['tgl_cetak_invoice'])) {
      if ($filter['tgl_cetak_invoice'] != '') {
        $where .= " AND so.tgl_cetak_invoice='{$filter['tgl_cetak_invoice']}'";
      }
    }
    if (isset($filter['tgl_cetak_invoice_not_null'])) {
      if ($filter['tgl_cetak_invoice_not_null'] != '') {
        $where .= " AND so.tgl_cetak_invoice IS NOT NULL";
      }
    }
    if (isset($filter['jenis_beli'])) {
      if ($filter['jenis_beli'] != '') {
        $where .= " AND spk.jenis_beli='{$filter['jenis_beli']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND spk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['id_kategori_kendaraan'])) {
      if ($filter['id_kategori_kendaraan'] != '') {
        $where .= " AND tk.id_kategori='{$filter['id_kategori_kendaraan']}'";
      }
    }
    if (isset($filter['id_sales_order_int'])) {
      if ($filter['id_sales_order_int'] != '') {
        $where .= " AND so.id_sales_order_int='{$filter['id_sales_order_int']}'";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_ktp LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = 'ORDER BY so.created_at DESC';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'history') {
          $order_column = ['spk.no_spk_gc', 'spk.nama_npwp', 'spk.no_npwp', 'spk.alamat', 'spk.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY so.created_at DESC ";
      }
    } else {
      $order = 'ORDER BY so.created_at DESC';
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(so.id_sales_order) AS count";
      }
    } else {
      $customer_image = "SELECT customer_image FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1";
      $jenis_kelamin = "SELECT jenis_kelamin FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1";
      $tot_act_doc = "SELECT COUNT(id) FROM tr_spk_file WHERE no_spk=so.no_spk";
      $tot_doc = "SELECT COUNT(id) FROM sc_ms_document_prospek WHERE active=1";
      $select = "so.*,spk.id_customer,($customer_image) AS customer_image,tipe_ahm,spk.nama_konsumen,$status_on_sc  AS status_on_sc,($tot_act_doc) AS actual_document,($tot_doc) AS total_document,($jenis_kelamin) jenis_kelamin,spk.program_umum,spk.program_gabungan,spk.voucher_1,spk.voucher_tambahan_1,spk.voucher_2,spk.voucher_tambahan_2
      ";
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page = $page < 0 ? 0 : $page;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    return $this->db->query("SELECT $select
    FROM tr_sales_order so
    JOIN tr_spk spk ON spk.no_spk=so.no_spk
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
		$where
    $order
    $limit
    ");
  }

  function getSales($so)
  {
    $get = $this->db->query("SELECT * FROM tr_sales_order 
			JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
			WHERE id_sales_order='$so'");
    $sales = '';
    if ($get->num_rows() > 0) {
      $so      = $get->row();
      $prospek = $this->db->query("SELECT * FROM tr_prospek 
				JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
				WHERE id_customer='$so->id_customer' ORDER BY tr_prospek.created_at DESC LIMIT 1");
      if ($prospek->num_rows() > 0) {
        $pr = $prospek->row();
        $sales = ['sales_id' => $pr->id_flp_md, 'sales_name' => $pr->nama_lengkap];
      }
    } else {
      $sales = $this->db->query("SELECT tr_prospek_gc.id_flp_md AS sales_id,nama_lengkap AS sales_name,
				tr_sales_order_gc.nama_penerima,
				tr_sales_order_gc.tgl_pengiriman,
				tr_sales_order_gc.waktu_pengiriman,
				tr_sales_order_gc.lokasi_pengiriman,
				tr_sales_order_gc.no_hp_penerima
				FROM tr_sales_order_gc
				JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc=tr_sales_order_gc.no_spk_gc
				JOIN tr_prospek_gc ON tr_prospek_gc.id_prospek_gc=tr_spk_gc.id_prospek_gc
				JOIN ms_karyawan_dealer ON ms_karyawan_dealer.id_karyawan_dealer=tr_prospek_gc.id_karyawan_dealer
				WHERE tr_sales_order_gc.id_sales_order_gc='$so'
				")->row();
    }
    return $sales;
  }
  function getSalesOrderGCNoMesin($filter)
  {
    $where = 'WHERE 1=1 ';
    if (isset($filter['id_sales_order_gc'])) {
      if ($filter['id_sales_order_gc'] != '') {
        $where .= " AND so_nosin.id_sales_order_gc='{$filter['id_sales_order_gc']}'";
      }
    }
    $result = $this->db->query("SELECT so_nosin.*,sc.no_rangka,tk.id_tipe_kendaraan,wr.id_warna,CONCAT(tk.id_tipe_kendaraan,' | ',tk.tipe_ahm) AS tipe_kendaraan, CONCAT(wr.id_warna,' | ',wr.warna) AS warna_kendaraan,pd.id_master_plat,pd.driver,status_delivery,delivery_document_id
            FROM tr_sales_order_gc_nosin so_nosin
            JOIN tr_scan_barcode sc ON sc.no_mesin=so_nosin.no_mesin
            JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=sc.tipe_motor
            JOIN ms_warna wr ON wr.id_warna=sc.warna
            LEFT JOIN ms_plat_dealer pd ON pd.id_master_plat=so_nosin.id_master_plat
            $where
    ");
    return $result;
  }

  function get_delivery_document_id_gc($last = null, $new_kode = NULL)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $th_bln    = date('Y-m');
    $dmy       = date('dmy');

    if ($last == true) {
      $get_data  = $this->db->query("SELECT delivery_document_id 
                  FROM tr_sales_order_gc_nosin so_gc_nosin
                  JOIN tr_sales_order_gc so_gc ON so_gc.id_sales_order_gc=so_gc_nosin.id_sales_order_gc
                  WHERE LEFT(so_gc_nosin.created_at,7)='$th_bln' 
                  AND id_dealer=$id_dealer AND delivery_document_id IS NOT NULL
			            ORDER BY so_gc_nosin.created_at DESC LIMIT 1");
      if ($get_data->num_rows() > 0) {
        $row      = $get_data->row();
        return $row->delivery_document_id;
      }
    } else {
      if ($new_kode == 'new') {
        return 'DDoc/' . $dealer->kode_dealer_md . '/' . $dmy . '/0001';
      } else {
        $neww = substr($new_kode, -4);
        return 'DDoc/' . $dealer->kode_dealer_md . '/' . $dmy . '/' . sprintf("%'.04d", $neww + 1);
      }
    }
  }

  function getSOFollowUp($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE p.id_dealer = '$id_dealer' ";

    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND pf.id='{$filter['id']}'";
      }
    }
    if (isset($filter['id_sales_order'])) {
      if ($filter['id_sales_order'] != '') {
        $where .= " AND pf.id_sales_order='{$filter['id_sales_order']}'";
      }
    }
    if (isset($filter['id_sales_order_int'])) {
      if ($filter['id_sales_order_int'] != '') {
        $where .= " AND p.id_sales_order_int='{$filter['id_sales_order_int']}'";
      }
    }

    $select = 'pf.*,fol.name AS metode_fol_up_text';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(pf.id_sales_order) AS count";
      }
    }

    $order = "ORDER BY pf.id ASC";
    if (isset($filter['order'])) {
      $order = $filter['order'];
    }
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $result = $this->db->query("SELECT 
    $select
    FROM tr_sales_order_fol_up pf
    LEFT JOIN tr_sales_order p ON p.id_sales_order=pf.id_sales_order
    LEFT JOIN sc_ms_metode_follow_up fol ON fol.id=pf.activity_id
    $where
    $order
    $limit
    ");
    if (isset($filter['return'])) {
      if ($filter['return'] == 'for_service_concept') {
        $no = 1;
        $new_result = [];
        foreach ($result->result() as $rs) {
          $new_result[] = [
            'id' => $rs->id,
            'nama' => 'Follow Up ' . $no,
            'activity' => $rs->activity,
            'check_date' => $rs->tgl_fol_up,
            'commited_date' => $rs->commited_date,
            'description' => $rs->description,
          ];
          $no++;
        }

        $f_spk = [
          'no_spk' => $filter['no_spk'],
          'id_dealer' => $filter['id_dealer'],
          'id_customer' => $filter['id_customer'],
          'return' => 'for_service_concept'
        ];
        $spk = $this->m_spk->getSPKFollowUp($f_spk);
        $result = [
          'sales' => $new_result,
          'spk' => $spk['spk'],
          'prospek' => $spk['prospek']
        ];
        return $result;
      }
    } else {
      return $result;
    }
  }

  function getSalesOrderActivity($id_karyawan_dealer, $user, $honda_id)
  {
    $this->load->model('m_dms');

    $filter_target_sales = [
      'honda_id_in' => $honda_id == NULL ? '' : $honda_id,
      'id_dealer' => $user->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_sales',
      'active' => 1,

    ];

    $filter_actual_sales = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count'
    ];
    $actual_sales = $this->getSalesOrderIndividu($filter_actual_sales)->row()->count;

    $f_so = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count',
      'jenis_beli' => 'cash'
    ];
    $so_tunai = $this->getSalesOrderIndividu($f_so)->row()->count;

    $f_so = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count',
      'jenis_beli' => 'kredit'
    ];
    $so_kredit = $this->getSalesOrderIndividu($f_so)->row()->count;

    // send_json($filter_target_sales);
    return [
      'actual' => (int)$actual_sales,
      'target' => (int)$this->m_dms->getH1TargetManagement($filter_target_sales)->row()->sum_sales,
      'tunai' => (int)$so_tunai,
      'kredit' => (int)$so_kredit,
    ];
  }

  function getRankSalesPeopleOnTeam($filter)
  {
    $f_kry['id_dealer'] = $filter['id_dealer'];
    $f_kry['group_by_id_sales_people'] = true;
    if (isset($filter['id_sales_coordinator'])) {
      $f_kry['id_sales_coordinator'] = $filter['id_sales_coordinator'];
    }
    $karyawan = $this->m_dms->getTeamSalesPeople($f_kry);
    // send_json($karyawan->result());
    $result = [];
    foreach ($karyawan->result() as $kd) {
      $filter_actual_sales = [
        'id_karyawan_dealer_in' => $kd->id_karyawan_dealer,
        'id_dealer' => $filter['id_dealer'],
        'bulan_so' => get_ym(),
        'select' => 'count'
      ];
      $sales = $this->getSalesOrderIndividu($filter_actual_sales)->row()->count;
      $result[] = [
        'id_karyawan_dealer' => $kd->id_karyawan_dealer,
        'penjualan' => (int)$sales
      ];
    }
    array_sort_by_column($result, "penjualan", SORT_DESC);

    //Get Bulan Kemarin
    if (isset($filter['cek_bulan_sebelumnya'])) {
      $f_kry = [
        'id_sales_coordinator' => $filter['id_sales_coordinator'],
        'bulan_so' => bulan_kemarin($filter['bulan_so'] . '-01'),
        'id_dealer' => $filter['id_dealer']
      ];
      // send_json($f_kry);
      $data_bln_min_1 = $this->m_so->getRankSalesPeopleOnTeam($f_kry);
    }
    //Add Field Rank
    $new_res = [];
    foreach ($result as $key => $rs) {
      $rank_sebelumnya = 0;
      $rank = $key + 1;
      $info = '';
      if (isset($data_bln_min_1)) {
        $rank_sebelumnya = $data_bln_min_1[$rs['id_karyawan_dealer']]['rank'];
        if ($rank_sebelumnya == $rank) {
          $info = 'bertahan';
        } elseif ($rank_sebelumnya > $rank) {
          $info = 'turun';
        } elseif ($rank_sebelumnya < $rank) {
          $info = 'naik';
        }
      }
      $rs['rank'] = $rank;
      $rs['rank_sebelumnya'] = $rank_sebelumnya;
      $rs['position_info'] = $info;
      $rs['position_number'] = $info == 'bertahan' ? 0 : 1;
      $new_res[$rs['id_karyawan_dealer']] = $rs;
    }
    if (isset($filter['id_karyawan_dealer'])) {
      return $new_res[$filter['id_karyawan_dealer']];
    } else {
      return $new_res;
    }
  }

  function getRankTeam($params)
  {
    $f_sc = [
      'id_dealer' => $params['id_dealer'],
      'group_by_sales_coordinator' => true,
      'tahun_bulan' => $params['tahun_bulan']
    ];

    $re_sc = $this->m_dms->getTeamStructureManagement($f_sc);
    // send_json($re_sc->result());
    foreach ($re_sc->result() as $rs) {
      $f_kry = ['id_team' => $rs->id_team, 'return' => 'multi_id_karyawan_dealer'];
      $id_karyawan_dealer_multi = $this->m_dms->getTeamSalesPeople($f_kry);
      $filter_actual_sales = [
        'id_karyawan_dealer_in' => arr_in_sql($id_karyawan_dealer_multi),
        'id_dealer' => $params['id_dealer'],
        'bulan_so' => $params['tahun_bulan'],
        'select' => 'count'
      ];
      $sales = $this->getSalesOrderIndividu($filter_actual_sales)->row()->count;
      $result[] = [
        'id_team' => $rs->id_team,
        'nama_team' => $rs->nama_team,
        'id_sales_coordinator' => $rs->id_sales_coordinator,
        'penjualan' => (int)$sales
      ];
    }
    array_sort_by_column($result, "penjualan", SORT_DESC);

    //Get Bulan Kemarin
    if (isset($filter['cek_bulan_sebelumnya'])) {
      $f_cek_sebelumnya = [
        'tahun_bulan' => bulan_kemarin($filter['bulan_so'] . '-01'),
        'id_dealer' => $filter['id_dealer']
      ];
      $data_bln_min_1 = $this->m_so->getRankSalesPeopleOnTeam($f_cek_sebelumnya);
    }

    // send_json($result);
    //Add Field Rank
    $new_res = [];
    foreach ($result as $key => $rs) {
      $rank_sebelumnya = 0;
      $rank = $key + 1;
      $info = 'bertahan';
      if (isset($data_bln_min_1)) {
        $rank_sebelumnya = $data_bln_min_1[$rs['id_sales_coordinator']]['rank'];
        if ($rank_sebelumnya == $rank) {
          $info = 'bertahan';
        } elseif ($rank_sebelumnya > $rank) {
          $info = 'turun';
        } elseif ($rank_sebelumnya < $rank) {
          $info = 'naik';
        }
      }
      $rs['rank'] = $rank;
      $rs['rank_sebelumnya'] = $rank_sebelumnya;
      $rs['position_info'] = $info;
      $rs['position_number'] = $info == 'bertahan' ? 0 : 1;
      $new_res[$rs['id_sales_coordinator']] = $rs;
    }
    // send_json($new_res);
    return $new_res;
  }

  function getPenjualanDenganDiskon($filter)
  {
    $where = "WHERE so.id_dealer='{$filter['id_dealer']}' AND IFNULL(spk.diskon,0)>0";
    if (isset($filter['id_karyawan_dealer'])) {
      $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY tgl_prospek DESC LIMIT 1)='{$filter['id_karyawan_dealer']}'";
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY tgl_prospek DESC LIMIT 1) IN ({$filter['id_karyawan_dealer_in']})";
    }
    if (isset($filter['bulan_so'])) {
      $where .= " AND LEFT(so.created_at,7)='{$filter['bulan_so']}'";
    }
    return $this->db->query("SELECT COUNT(so.no_spk) AS tot 
      FROM tr_sales_order so 
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      $where
    ")->row()->tot;
  }

  function getPenyerahanSTNKMDDealer($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['no_mesin'])) {
      $where .= " AND no_mesin='{$filter['no_mesin']}'";
    }
    if (isset($filter['status_nosin'])) {
      $where .= " AND status_nosin='{$filter['status_nosin']}'";
    }

    return  $this->db->query("SELECT stnk_d.*,stnk.created_at
    FROM tr_penyerahan_stnk_detail stnk_d
          JOIN tr_penyerahan_stnk stnk ON stnk.no_serah_stnk=stnk_d.no_serah_stnk
    $where");
  }

  function getPengajuanBBNDetail($filter)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['no_mesin'])) {
      $where .= " AND no_mesin='{$filter['no_mesin']}'";
    }
    if (isset($filter['id_generate_not_null'])) {
      $where .= " AND (bbn_d.id_generate IS NOT NULL )";
    }
    $select = "bbn_d.*,bbn.created_at AS created_at_pengajuan";
    $join = '';
    if (isset($filter['left_join_biro'])) {
      $join .= "LEFT JOIN tr_kirim_biro kb ON kb.id_generate=bbn_d.id_generate ";
      $select .= ", kb.created_at";
    }

    return  $this->db->query("SELECT $select 
    FROM tr_pengajuan_bbn_detail bbn_d 
    JOIN tr_pengajuan_bbn bbn ON bbn.no_bastd=bbn_d.no_bastd
    $join $where");
  }

  function cekProsesSTNK($no_mesin)
  {
    $status_stnk = '';
    $tgl_stnk = '';

    $filter_mohon = ['no_mesin' => $no_mesin];
    $get_pengajuan = $this->getPengajuanBBNDetail($filter_mohon);
    if ($get_pengajuan->num_rows() > 0) {
      $get = $get_pengajuan->row();
      if (($get->tgl_mohon_samsat == '0000-00-00' || $get->tgl_mohon_samsat == NULL) == false) {
        $status_stnk = 'Mohon faktur';
        $tgl_stnk = $get->created_at_pengajuan;
      }
    }

    $filter_pengajuan = [
      'id_generate_not_null' => true,
      'left_join_biro' => true,
      'no_mesin' => $no_mesin
    ];
    $get_pengajuan = $this->getPengajuanBBNDetail($filter_pengajuan);
    if ($get_pengajuan->num_rows() > 0) {
      $pj = $get_pengajuan->row();
      $status_stnk = 'Sudah diserahkan ke Biro Jasa';
      $tgl_stnk = $pj->created_at;
    }

    $filter_penyerahan = [
      'no_mesin' => $no_mesin,
      'status_nosin' => 'terima'
    ];
    $get_stnk = $this->getPenyerahanSTNKMDDealer($filter_penyerahan);
    if ($get_stnk->num_rows() > 0) {
      $get_stnk = $get_stnk->row();
      $status_stnk = 'STNK Selesai';
      $tgl_stnk = $get_stnk->created_at;
    }

    $get_terima_stnk = $this->db->query("SELECT tgl_terima_stnk FROM tr_tandaterima_stnk_konsumen tsk
    JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.kd_stnk_konsumen=tsk.kd_stnk_konsumen
    WHERE jenis_cetak='stnk' AND tskd.no_mesin='$no_mesin'
    ORDER BY tsk.created_at DESC LIMIT 1");
    if ($get_terima_stnk->num_rows() > 0) {
      $tg = $get_terima_stnk->row();
      if ($tg->tgl_terima_stnk != NULL) {
        $status_stnk = 'STNK diserahkan ke konsumen';
        $tgl_stnk = $tg->tgl_terima_stnk;
      }
    }

    return ['status_stnk' => $status_stnk, 'tgl_stnk' => $tgl_stnk];
  }
}
