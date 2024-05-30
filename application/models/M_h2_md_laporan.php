<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_md_laporan extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_md_claim', 'm_claim');
    $this->load->model('m_tipe_vs_5digitnosin', 'm_tipe_5nosin');
  }

  function getCetakLBPC($filter = null)
  {
    $potong = 1;
    if (isset($filter['id_dealer'])) {
      $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $filter['id_dealer']]);
      if ($dealer->num_rows() > 0) {
        $dealer = $dealer->row();
        if ($dealer->kode_dealer_md == '00888') {
          $potong = 0.77;
        }
      }
    }
    // send_json($filter);
    $parts = $this->m_claim->getRekapClaimWarrantyParts(($filter))->result();

    $tot_ganti_uang = 0;
    $tot_ganti_part = 0;
    $tot_ongkos = 0;
    $tot_qty = 0;
    foreach ($parts as  $pr) {
      $res_parts[] = $pr;
      $tot_ganti_uang += $pr->ganti_uang;
      $tot_ganti_part += $pr->ganti_part;
      $tot_ongkos += $pr->ongkos;
      $tot_qty += $pr->qty;
    }
    $subtotal = $tot_ganti_uang * $potong;
    $result = [
      'parts' => isset($res_parts) ? $res_parts : NULL,
      'ganti_uang' => $tot_ganti_uang,
      'ganti_part' => $tot_ganti_part,
      'subtotal' => $subtotal,
      'ongkos' => $tot_ongkos,
      'tot_qty' => $tot_qty,
      'tot_tagihan' => $tot_ongkos + $subtotal,
    ];
    return $result;
  }

  function getRekapLBCInternal($filter)
  {
    $dealer = $this->m_claim->getRekapClaimWarranty($filter)->result();
    unset($filter['group_by_dealer']);
    // send_json($filter);
    $grand = [
      'tot_part' => 0,
      'jasa' => 0,
      'tot_part_jasa' => 0,
      'ppn' => 0,
      'pph' => 0,
      'total' => 0,
      'tanggal_pengajuan' => '',
    ];
    foreach ($dealer as $dl) {
      $filter['id_dealer'] = $dl->id_dealer;
      $filter['get_summary'] = true;
      // send_json($filter);
      $lbpc = $this->m_claim->getRekapClaimWarranty($filter)->result();
      // send_json($lbpc);
      $tot_part      = 0;
      $jasa          = 0;
      $ppn           = 0;
      $pph           = 0;
      $total         = 0;
      $tot_part_jasa = 0;
      foreach ($lbpc as $lb) {
        $tot_part      += $lb->nilai_part;
        $jasa          += $lb->nilai_jasa;
        $tot_part_jasa += $lb->nilai_part + $lb->nilai_jasa;
        $ppn           += $lb->nilai_ppn;
        $pph           += $lb->nilai_pph;
        $total         += $lb->total;
      }
      $result[] = [
        'id_dealer'      => $dl->id_dealer,
        'kode_dealer_md' => $dl->kode_dealer_md,
        'nama_dealer'    => $dl->nama_dealer,
        'no_lbpc' => $dl->no_lbpc,
        'no_lbpc_ahass_to_md' => $dl->no_lbpc_ahass_to_md,
        'tot_part'       => $tot_part,
        'jasa'           => $jasa,
        'tot_part_jasa'  => $tot_part_jasa,
        'ppn'            => $ppn,
        'pph'            => $pph,
        'total'          => $total
      ];
      $grand = [
        'tot_part'      => $grand['tot_part'] + $tot_part,
        'jasa'          => $grand['jasa'] + $jasa,
        'tot_part_jasa' => $grand['tot_part_jasa'] + $tot_part_jasa,
        'ppn'           => $grand['ppn'] + $ppn,
        'pph'           => $grand['pph'] + $pph,
        'total'         => $grand['total'] + $total,
        'tgl_pengajuan' => $dl->tgl_pengajuan,
      ];
    }
    return ['detail' => $result, 'grand' => $grand];
  }
  function getGantiClaimInternal($filter)
  {
    $dealer = $this->m_claim->getRekapClaimWarranty($filter)->result();
    unset($filter['group_by_dealer']);
    foreach ($dealer as $dl) {
      $filter['id_dealer'] = $dl->id_dealer;
      // send_json($filter);
      $claim = $this->m_claim->getRekapClaimWarranty($filter)->result();
      $grand = [
        'nilai_part' => 0,
        'ongkos'     => 0,
        'ppn'        => 0,
        'pph'        => 0,
        'total'      => 0,
        'part_ongkos' => 0,
      ];
      $result = [];
      foreach ($claim as $cl) {
        $filter['id_rekap_claim'] = $cl->id_rekap_claim;
        $parts = $this->m_claim->getRekapClaimWarrantyParts($filter)->result();
        $total = [
          'nilai_part' => 0,
          'ongkos'     => 0,
          'ppn'        => 0,
          'pph'        => 0,
          'total'      => 0,
          'part_ongkos' => 0,
        ];
        foreach ($parts as $prt) {
          $total = [
            'nilai_part' => $total['nilai_part'] + $prt->nilai_part,
            'ongkos'     => $total['ongkos'] + $prt->ongkos,
            'ppn'        => $total['ppn'] + $prt->ppn,
            'pph'        => $total['pph'] + $prt->pph,
            'total'      => $total['total'] + $prt->total,
            'part_ongkos' => $total['part_ongkos'] + ($prt->nilai_part + $prt->ongkos)
          ];
        }
        $grand = [
          'nilai_part' => $grand['nilai_part'] + $total['nilai_part'],
          'ongkos'     => $grand['ongkos'] + $total['ongkos'],
          'ppn'        => $grand['ppn'] + $total['ppn'],
          'pph'        => $grand['pph'] + $total['pph'],
          'total'      => $grand['total'] + $total['total'],
          'part_ongkos' => $grand['part_ongkos'] + ($total['nilai_part'] + $total['ongkos'])
        ];
        $result[] = [
          'id_rekap_claim' => $cl->id_rekap_claim,
          'no_registrasi' => $cl->no_registrasi,
          'kode_dealer_md' => $cl->kode_dealer_md,
          'id_dealer'      => $cl->id_dealer,
          'nama_dealer'    => $cl->nama_dealer,
          'no_lbpc'        => $cl->no_lbpc,
          'parts'          => $parts,
          'total'          => $total
        ];
      }
      $all_res[$dl->id_dealer] = ['result' => $result, 'grand' => $grand];
    }
    // send_json($all_res);
    return $all_res;
  }

  function getDealer($filter = NULL)
  {
    $where = "WHERE 1=1 ";
    $group = "";
    if ($filter != NULL) {
      if (isset($filter['id_dealer'])) {
        $where .= " AND dl.id_dealer='{$filter['id_dealer']}'";
      }
      if (isset($filter['id_kabupaten'])) {
        $where .= " AND kab.id_kabupaten='{$filter['id_kabupaten']}'";
      }
      if (isset($filter['periode_service'])) {
        $tgl = $filter['periode_service'];
        $where .= " AND dl.id_dealer IN(SELECT ck.id_dealer FROM tr_claim_kpb ck JOIN tr_claim_kpb_generate_detail ckg ON ckg.id_claim_kpb=ck.id_claim_kpb WHERE tgl_service BETWEEN '{$tgl[0]}' AND '{$tgl[1]}' AND ckg.status='approved' AND id_po_kpb IS NOT NULL)";
      }
      if (isset($filter['group_by_kab'])) {
        $group .= " GROUP BY kab.id_kabupaten";
      }
    }
    return $this->db->query("SELECT id_dealer,kode_dealer_md,nama_dealer,kab.id_kabupaten,kabupaten FROM ms_dealer dl
      JOIN ms_kelurahan kel ON kel.id_kelurahan=dl.id_kelurahan
      JOIN ms_kecamatan kec ON kec.id_kecamatan=kel.id_kecamatan
      JOIN ms_kabupaten kab ON kab.id_kabupaten=kec.id_kabupaten
      $where $group
    ");
  }
  function getLaporanFormOliKPB($filter)
  {
    $periode_service = [$filter['tgl_awal'], $filter['tgl_akhir']];
    $filter_kab_dealer = ['group_by_kab' => true, 'periode_service' => $periode_service];
    $kab_dealer = $this->getDealer($filter_kab_dealer);
    $result = [];
    foreach ($kab_dealer->result() as $rs) {
      $filter_dealer = ['id_kabupaten' => $rs->id_kabupaten, 'periode_service' => $periode_service];
      $dealer = $this->getDealer($filter_dealer);
      $res_dealer = [];
      foreach ($dealer->result() as $dl) {
        $filter_detail = ['id_dealer' => $dl->id_dealer, 'periode_service' => $periode_service];
        $res_ = $this->getLaporanFormOliKPBDetail($filter_detail);
        $res_dealer[] = [
          'id_dealer' => $dl->kode_dealer_md,
          'nama_dealer' => $dl->nama_dealer,
          'data' => $res_
        ];
      }
      $result[] = [
        'id_kabupaten' => $rs->id_kabupaten,
        'kabupaten' => $rs->kabupaten,
        'result_dealer' => $res_dealer
      ];
    }
    return $result;
  }

  function getLaporanFormOliKPBDetail($filter, $show_all = false)
  {
    // send_json($filter);
    $filter_5 = ['aktif' => 1, 'select_concat_detail' => true];
    $tipe_5 = $this->m_tipe_5nosin->fetchData($filter_5)->result();
    // send_json(count($tipe_5));
    $res_data = array();
    foreach ($tipe_5 as $rs) {
      $ps = $filter['periode_service'];
      $filter_detail = [
        'id_tipe_kendaraan_in'    => arr_in_sql($rs->concat_detail),
        'id_dealer'               => $filter['id_dealer'],
        'periode_servis'          => true,
        'start_date'              => $ps[0],
        'end_date'                => $ps[1],
        'id_po_kpb_not_null'      => true,
        'select'                  => 'sum_qty'
      ];
      // send_json($filter_detail);
      $res = $this->m_claim->getClaimGenerated($filter_detail)->row()->sum_qty;
      if ($show_all == false) {
        $res_data[] = $res == NULL ? 0 : $res;
      } else {
        $res_data[] = [
          'nama_tipe'   => $rs->nama_tipe,
          'qty'         => (int)$res
        ];
      }
    }
    $result = isset($res_data) ? $res_data : NULL;
    return $result;
  }

  function getLaporanTandaTerimaKPB($filter)
  {
    // send_json($filter);
    $filter_claim = [
      'group_by_no_mesin_5_digit' => true,
      'id_dealer' => $filter['id_dealer'],
      'periode_servis' => true,
      'start_date' => $filter['tgl_awal'],
      'end_date' => $filter['tgl_akhir'],
      // 'id_po_kpb_not_null' => true,
    ];
    // send_json($filter_claim);
    $claim_no_mesin_5 = $this->m_claim->getClaimGenerated($filter_claim)->result();
    $total_all_jasa_insentif_oli    = 0;
    $total_pph                      = 0;
    $total_ppn                      = 0;
    $total_jasa                     = 0;
    $total_insentif_oli             = 0;
    $sub_total                      = 0;
    foreach ($claim_no_mesin_5 as $nosin) {
      $res_kpb = [];
      for ($i = 1; $i <= 4; $i++) {
        $filter_k = [
          'id_dealer'         => $filter['id_dealer'],
          'no_mesin_5'        => $nosin->no_mesin_5,
          'select'            => 'select_lap_tanda_terima',
          'kpb_ke'            => $i,
          'periode_servis'    => true,
          'status_reject'     => true,
          'start_date'        => $filter['tgl_awal'],
          'end_date'          => $filter['tgl_akhir'],
        ];
        if ($i == 1) {
          $filter_k['id_po_kpb_not_null']   = true;
          $filter_k['part_not_null']        = true;
        }

        $kpb = $this->m_claim->getClaimGenerated($filter_k)->row_array();
        $kpb['kpb'] = $i;
        $res_kpb[] = $kpb;
      }

      $result[] = [
        'no_mesin_5' => $nosin->no_mesin_5,
        'kpb' => $res_kpb
      ];
    }
    // send_json($result);

    // init total all
    for ($i = 1; $i <= 4; $i++) {
      $total_all[$i]['qty']                 = 0;
      $total_all[$i]['tot_jasa']            = 0;
      $total_all[$i]['tot_insentif_oli']    = 0;
      $total_all[$i]['tot_oli']             = 0;
      $total_all[$i]['tot_all']             = 0;
      $total_all[$i]['ppn']                 = 0;
      $total_all[$i]['pph']                 = 0;
      $total_all[$i]['sub_total']           = 0;
      $total_all[$i]['kpb']                 = 0;
    }

    foreach ($result as $rsl) {
      foreach ($rsl['kpb'] as $kpb) {
        for ($i = 1; $i <= 4; $i++) {
          if ($i == $kpb['kpb']) {
            $total_all[$i]['qty']                 += $kpb['qty'];
            $total_all[$i]['tot_jasa']            += $kpb['tot_jasa'];
            $total_all[$i]['tot_insentif_oli']    += $kpb['tot_insentif_oli'];
            $total_all[$i]['tot_oli']             += $kpb['tot_oli'];
            $total_all[$i]['tot_all']             += $kpb['tot_all'];
            $total_all[$i]['ppn']                 += $kpb['ppn'];
            $total_all[$i]['pph']                 += $kpb['pph'];
            $total_all[$i]['sub_total']           += $kpb['sub_total'];
            $total_all[$i]['kpb']                 = $kpb['kpb'];
            
            $total_all_jasa_insentif_oli    += $kpb['tot_jasa'] + $kpb['tot_insentif_oli'];
            $total_pph                      += $kpb['pph'];
            $total_ppn                      += $kpb['ppn'];
            $total_jasa                     += $kpb['tot_jasa'];
            $total_insentif_oli             += $kpb['tot_insentif_oli'];
            $sub_total             += $kpb['sub_total'];
          }
        }
      }
    }
    $fil5 = [
      'periode_service' => [$filter['tgl_awal'], $filter['tgl_akhir']],
      'id_dealer' => $filter['id_dealer'],
    ];
    $get_tipe_5 = $this->getLaporanFormOliKPBDetail($fil5, true);
    $res_tipe_5 = [];
    foreach ($get_tipe_5 as $tp) {
      $dus = floor($tp['qty'] / 24);
      $botol = (int)($tp['qty'] % 24);
      $res_tipe_5[] = [
        'nama_tipe' => $tp['nama_tipe'],
        'tot_qty' => $tp['qty'],
        'dus' => $dus,
        'botol' => $dus > 0 ? $botol : 0
      ];
    }
    $result = [
      'details'   => $result,
      'total_all' => $total_all,
      'tipe_5'    => $res_tipe_5
    ];
    if (isset($filter['return'])) {
      if ($filter['return'] == 'total_all_jasa_insentif_oli') {
        $return =[
            'total_jasa'                    => $total_jasa,
            'total_insentif_oli'            => $total_insentif_oli,
            'total_all_jasa_insentif_oli'   => $total_all_jasa_insentif_oli,
            'total_pph'                     => $total_pph,
            'total_ppn'                     => $total_ppn,
            'sub_total'                     => $sub_total,
        ];
        if ($filter['id_dealer']==66) {
          // send_json($return);
        }
        return $return;
      }
    } else {
      return isset($result) ? $result : [];
    }
  }

  function getRekapAllPenagihanKPB($filter)
  {
    $periode_service = [$filter['tgl_awal'], $filter['tgl_akhir']];
    $filter_kab_dealer = ['group_by_kab' => true, 'periode_service' => $periode_service];
    $kab_dealer = $this->getDealer($filter_kab_dealer)->result();
    foreach ($kab_dealer as $rs) {
      $filter_dealer = ['id_kabupaten' => $rs->id_kabupaten, 'periode_service' => $periode_service];
      $dealer = $this->getDealer($filter_dealer)->result();
      $res_dealer = [];
      foreach ($dealer as $dl) {
        $filter_detail = [
          'id_dealer'         => $dl->id_dealer,
          'periode_service'   => $periode_service,
          'tgl_awal'          => $filter['tgl_awal'],
          'tgl_akhir'         => $filter['tgl_akhir'],
          'return'            => 'total_all_jasa_insentif_oli'
        ];
        $res_ = $this->getRekapAllPenagihanKPBDetail($filter_detail);
        $res_dealer[] = [
          'id_dealer' => $dl->kode_dealer_md,
          'nama_dealer' => $dl->nama_dealer,
          'data' => $res_
        ];
      }
      $result[] = [
        'id_kabupaten' => $rs->id_kabupaten,
        'kabupaten' => $rs->kabupaten,
        'result_dealer' => $res_dealer
      ];
    }
    return $result;
  }

  function getRekapAllPenagihanKPBDetail($filter)
  {
    $prd = $filter['periode_service'];

    //Cek Tanda Terima Untuk Mendapatkan Tot Jasa & Insentif Oli
    $tot_jasa_insentif = $this->getLaporanTandaTerimaKPB($filter)['total_all_jasa_insentif_oli'];

    // Total Oli
    $filter_k = [
      'id_dealer'         => $filter['id_dealer'],
      'select'            => 'tot_oli_1000',
      'kpb_ke'            => 1,
      'periode_servis'    => true,
      'start_date'        => $prd[0],
      'end_date'          => $prd[1],
    ];
    $tot_oli    = $this->m_claim->getClaimGenerated($filter_k)->row()->tot_oli_1000;

    // Ambil Qty Per KPB
    for ($i = 1; $i <= 4; $i++) {
      $filter_k = [
        'id_dealer'         => $filter['id_dealer'],
        'select'            => 'select_rekap_all_tagihan_kpb',
        'kpb_ke'            => $i,
        'periode_servis'    => true,
        'start_date'        => $prd[0],
        'end_date'          => $prd[1],
      ];
      $kpb = $this->m_claim->getClaimGenerated($filter_k)->row_array();
      $result[$i] = $kpb;
    }

    if ($filter['id_dealer'] == '80') {
      // send_json([$result, $tot_oli, $tot_jasa_insentif]);
      // send_json($result);
    }
    return $result = ['kpb' => $result, 'total' => $tot_oli + $tot_jasa_insentif];
    // json_encode(($result));
  }

  function getLaporanPembayaranKeAHASS($filter)
  {
    $periode_service = [$filter['tgl_awal'], $filter['tgl_akhir']];
    $filter_kab_dealer = ['group_by_kab' => true, 'periode_service' => $periode_service];
    $kab_dealer = $this->getDealer($filter_kab_dealer)->result();
    foreach ($kab_dealer as $rs) {
      $filter_dealer = ['id_kabupaten' => $rs->id_kabupaten, 'periode_service' => $periode_service];
      $dealer = $this->getDealer($filter_dealer)->result();
      $res_dealer = [];
      foreach ($dealer as $dl) {
        $filter_detail = [
          'id_dealer'         => $dl->id_dealer,
          'periode_service'   => $periode_service,
          'tgl_awal'          => $filter['tgl_awal'],
          'tgl_akhir'         => $filter['tgl_akhir'],
          'return'            => 'total_all_jasa_insentif_oli'
        ];
        $res_ = $this->getLaporanPembayaranKeAHASSDetail($filter_detail);
        $res_dealer[] = [
          'id_dealer' => $dl->kode_dealer_md,
          'nama_dealer' => $dl->nama_dealer,
          'data' => $res_
        ];
      }
      $result[] = [
        'id_kabupaten' => $rs->id_kabupaten,
        'kabupaten' => $rs->kabupaten,
        'result_dealer' => $res_dealer
      ];
    }
    return $result;
  }

  function getLaporanPembayaranKeAHASSDetail($filter)
  {
    $prd = $filter['periode_service'];

    //Cek Tanda Terima Untuk Mendapatkan Tot Jasa & Insentif Oli
    $totjsp = $this->getLaporanTandaTerimaKPB($filter);

    // Total Oli
    $filter_k = [
      'id_dealer'         => $filter['id_dealer'],
      'select'            => 'tot_oli_1000',
      'kpb_ke'            => 1,
      'periode_servis'    => true,
      'start_date'        => $prd[0],
      'end_date'          => $prd[1],
    ];
    $tot_oli    = $this->m_claim->getClaimGenerated($filter_k)->row()->tot_oli_1000;

    // Ambil Qty Per KPB
    for ($i = 1; $i <= 4; $i++) {
      $filter_k = [
        'id_dealer'         => $filter['id_dealer'],
        'select'            => 'select_rekap_all_tagihan_kpb',
        'kpb_ke'            => $i,
        'periode_servis'    => true,
        'start_date'        => $prd[0],
        'end_date'          => $prd[1],
      ];
      $kpb = $this->m_claim->getClaimGenerated($filter_k)->row_array();
      $result[$i] = $kpb;
    }

    $total    = $tot_oli+$totjsp['total_all_jasa_insentif_oli'];
    $ppn      = round($total* getPPN(0.1));
    $pph_jasa = round($totjsp['total_jasa']* 2/100);
    $tot_ppn  = $total+$ppn;
    $cair_ahm = $tot_ppn - $pph_jasa;
    $result   = ['kpb' => $result, 'tagih_ahm' => $tot_ppn,'cair_ahm'=>$cair_ahm,'bayar_ahass'=>$totjsp['sub_total']];
    if ($filter['id_dealer']==66) {
      // send_json($pph_jasa);
    }
    return $result;
  }
  function _dealer_sinsen()
  {
    return [103,51,66,22,80,77];
  }
}
