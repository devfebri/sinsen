<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Pkb extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_dms');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_sc_master', 'm_sc');
    $this->load->model('m_sm_master', 'm_sm');
    $this->load->model('m_h2_master', 'm_h2_m');
    $this->load->model('m_h2', 'm_h2_ms');
    $this->load->model('m_h2_api');
    $this->load->model('m_master_unit', 'm_unit');


    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function get_list()
  {
    $this->load->model('m_h2_apk_pkb','m_pkb_apk');
    $id_dealer = $this->login->id_dealer;
    $get = $this->input->get();
    $mandatory = [
      'limit' => 'required',
      'offset' => 'required',
      'date' => 'required',
    ];
    cek_mandatory($mandatory, $get);
    
    $get_data = $this->m_pkb_apk->get_pkb_list($this->login->id_dealer,$get['date'],$get['offset'],$get['limit']);
    $result = [];
    foreach ($get_data as $rs) {
      $f_cc = [
        'id_work_order' => $rs->id_work_order,
        'select' => 'concat_jasa'
      ];
      $concat_jasa = $this->m_wo->getWOPekerjaan($f_cc)->row()->concat_jasa;

      $f_cc['select'] = 'select_type';
      $desk_type = $this->m_wo->getWOPekerjaan($f_cc)->row()->desk_type;

      $customer = [
        'name' => $rs->nama_customer,
        'phone' => $rs->no_hp,
        'address' => $rs->alamat,
        'district' => $rs->kecamatan,
      ];
      $params = [
        'status' => $rs->status_wo,
        'last_stats' => $rs->last_stats
      ];
      $status_pkb = status_pkb_apps($params);
      $cond = ['id_pit' => $rs->id_pit, 'id_dealer' => $id_dealer];
      $pit = $this->db->get_where('ms_h2_pit', $cond);
      $id_pit = 0;
      if ($pit->num_rows() > 0) {
        $id_pit = substr($pit->row()->id_pit, 1, 3);
      }

      $result[] = [
        'id' => (int)$rs->id_work_order_int,
        'pkb_no' => $rs->id_work_order,
        'pkb_date' => $rs->tgl_wo,
        'customer' => $customer,
        'service_type' => $concat_jasa,
        'job_type_name' => $desk_type,
        'status' => $status_pkb['status'],
        'status_color' => $status_pkb['status_color'],
        'time_estimate' => $rs->estimasi_waktu_kerja . ' Menit',
        'pit_no' => (int)$id_pit,
        'pit_type' => $rs->jenis_pit ?: '',
        'document' => base_url($rs->document),
      ];
      if ((string)$rs->document == '') {
        $params = [
          'id_user'     => $rs->created_by,
          'id_sa_form'  => $rs->id_sa_form,
          'id_dealer'   => $rs->id_dealer,
          'save_server' => true
        ];
        $this->m_wo->cetak_wo($params);
      }
    }
    send_json(msg_sc_success($result, NULL));
  }
  function list_status()
  {
    $sts = $this->db->query("SELECT status AS name, status_color as color from sc_ms_service_management_status WHERE status_for='pkb'")->result();
    send_json(msg_sc_success($sts, NULL));
  }
  function list_service_advisor()
  {
    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_jabatan_in' => "'JBT-079'",
      'id_user_not_null' => true
    ];

    $get_data = $this->m_sc->getKaryawan($f_mc)->result();
    $result = [];
    foreach ($get_data as $rs) {
      $result[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function list_assign_user($return = false)
  {
    $id_dealer = $this->login->id_dealer;
    $f_mc = [
      'id_dealer'  => $id_dealer,
    ];
    $result = [];
    $get_data = $this->m_h2_ms->get_pit_mekanik($f_mc);
    foreach ($get_data->result() as $rs) {
      $f_mc = [
        'id_dealer'  => $this->login->id_dealer,
        'id_karyawan_dealer' => $rs->id_karyawan_dealer,
        // 'group_by_id_karyawan_dealer' => true,
        'tanggal' => tanggal(),
        'ready_work' => true,
        'select' => 'count'
      ];
      $cek_mk = $this->m_sm->getMekanikHadir($f_mc)->row()->count;
      if ($cek_mk > 0) {
        $result[] = [
          'id' => (int)$rs->id_karyawan_dealer_int,
          'name' => $rs->nama_lengkap,
          'pit_no' => (int)substr($rs->id_pit, 1, 3),
          'id_pit' => $rs->id_pit
        ];
      }
    }
    if ($return == true) {
      return $result;
    } else {
      send_json(msg_sc_success($result, NULL));
    }
  }

  function save_assigned()
  {
    $post = $this->input->post();
    $mandatory = [
      'pkb_id' => 'required',
      'assigned_id' => 'required',
      'service_advisor_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_karyawan_dealer_int' => $post['assigned_id'],
      'tanggal' => tanggal()
    ];
    $mekanik = $this->m_sm->getMekanikHadir($f_mc);
    cek_referensi($mekanik, 'Assigned ID');
    $mekanik = $mekanik->row();

    $f_sa = [
      'id_dealer'  => $this->login->id_dealer,
      'id_karyawan_dealer_int' => $post['service_advisor_id'],
      'select' => 'all',
      'id_user_not_null' => true,
    ];
    $sa = $this->m_sc->getKaryawan($f_sa);
    cek_referensi($sa, 'Service Advisor ID');
    $sa = $sa->row();

    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_work_order_int' => $post['pkb_id']
    ];

    // send_json($f_mc);
    $wo = $this->m_wo->get_sa_form($f_mc);
    cek_referensi($wo, 'PKB ID');
    $wo = $wo->row();


    //Get Pit
    $pit = $this->list_assign_user(true);
    $id_pit = NULL;
    foreach ($pit as $pt) {
      if ($pt['id'] == $post['assigned_id']) {
        $id_pit = $pt['id_pit'];
        break;
      }
    }
    $update_sa = [
      'id_pit'             => $id_pit,
      'created_by'         => $sa->id_user,
      'id_karyawan_dealer' => $mekanik->id_karyawan_dealer,
      'assigned_at'        => waktu_full(),
      'assigned_by'        => $this->login->id_user
    ];
    $update_wo = [
      'created_by'    => $sa->id_user,
      'id_karyawan_dealer' => $mekanik->id_karyawan_dealer,
      'assigned_at' => waktu_full(),
      'assigned_by' => $this->login->id_user
    ];

    if ($wo->last_stats != NULL) {
      if ($mekanik->id_karyawan_dealer != $wo->id_karyawan_dealer) {
        $msg = ['Mekanik tidak boleh diubah karena PKB sedang dikerjakan '];
        send_json(msg_sc_error($msg));
      }
    }

    // $tes = [
    //   'update_sa' => $update_sa,
    //   'update_wo' => $update_wo,
    // ];
    // send_json($tes);
    $this->db->trans_begin();

    $cond = ['id_sa_form' => $wo->id_sa_form];
    $this->db->update('tr_h2_sa_form', $update_sa, $cond);

    $cond = ['id_work_order' => $wo->id_work_order];
    $this->db->update('tr_h2_wo_dealer', $update_wo, $cond);
    $this->m_wo->updateGrandTotalWO($wo->id_work_order);

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["success"];
      $params = [
        'id_user' => $this->login->id_user,
        'id_dealer' => $this->login->id_dealer,
        'id_sa_form' => $wo->id_sa_form,
        'save_server' => true
      ];
      $cetak = $this->m_wo->cetak_wo($params);
      $data = ['document' => $cetak];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function update_pkb()
  {

    ini_set("allow_url_fopen", true);
    $post = json_decode(file_get_contents('php://input'), true);
    $id_dealer = $this->login->id_dealer;
    $f = [
      'id_work_order_int' => $post['pkb_id'],
      'id_dealer' => $id_dealer,
    ];
    $get_data = $this->m_wo->get_sa_form($f);
    cek_referensi($get_data, 'PKB ID');
    $wo = $get_data->row();

    $total_jasa = 0;
    foreach ($post['service_type'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_jasa_or_id_jasa_int' => $st,
      ];
      $get_data = $this->m_h2_m->fetch_jasa_h2_dealer($f);
      if ($get_data->num_rows() > 0) {
        $res_type = $get_data->row();
      } else {
        $msg = ['Service Type Tidak Ditemukan'];
        send_json(msg_sc_error($msg));
      }

      $id_promo = NULL;
      $diskon_value = 0;

      // if ($post['service_promo_code'] != '') {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'cek_periode' => tanggal(),
        'group_by_id_promo' => true,
        'id_jasa' => $res_type->id_jasa
      ];
      $srv = $this->m_h2_master->get_promo_servis_jasa($f)->row();
      if ($srv != NULL) {
        $id_promo = $srv->id_promo;
        if ($srv->tipe_diskon == 'persen') {
          $diskon_value = $res_type->harga_dealer * ($srv->diskon / 100);
        } else {
          $diskon_value = $srv->diskon;
        }
      }
      $subtotal = $res_type->harga_dealer - $diskon_value;
      $ins_jasa_sa[] = [
        'id_jasa' => $res_type->id_jasa,
        'harga' => $res_type->harga_dealer,
        'waktu' => $res_type->waktu,
        'id_promo' => isset($id_promo) ? $id_promo : NULL,
        'diskon_value' => $diskon_value,
        // 'subtotal' => $subtotal
      ];

      $total_jasa += $subtotal;
    }

    $total_part = 0;
    //Part Ready Stok
    foreach ($post['part'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_part_int' => $st['part_id'],
        'cek_referensi' => true
      ];
      $prt = $this->m_sm->getParts($f);

      // if ($key == 0) {
      //   if ($post['part_promo_code'] != '') {
      //     $id_promo = $post['part_promo_code'];
      //     $diskon_value = $post['part_promo_price'];
      //   }
      // }

      // 2024-01-23 : update utk disabled auto update promo di menu wo
      $id_promo_part = NULL;
      $diskon_value_part = 0;
      $tipe_diskon_part = NULL;

      //Get Diskon Part
      $cek_diskon_part = $this->m_sm->promo_part_query($prt->id_part, $prt->kelompok_part);
      if (count($cek_diskon_part) > 0) {
        // $id_promo_part = $cek_diskon_part[0]['id_promo'];
        $get_part_d = $cek_diskon_part[0]['promo_items'];
        foreach ($get_part_d as $key => $pd) {
          if ($pd['id_part'] == $prt->id_part) {
            // $tipe_diskon_part = $pd['tipe_disc'];
            // $diskon_value_part = $pd['disc_value'];
            break;
          }
        }
      }

      $rak_gudang = $this->_getRakGudangByPartInDealer($prt->id_part, $id_dealer);
      $id_gudang = '';
      $id_rak = '';

      if ($rak_gudang!=null) {
        if ($rak_gudang->id_gudang!='---') {
          $id_gudang =$rak_gudang->id_gudang;
        }else{
          $msg = ['ID Gudang untuk part : '.$prt->id_part.' kosong'];
          send_json(msg_sc_error($msg));
        }
        if ($rak_gudang->id_rak!='---') {
          $id_rak =$rak_gudang->id_rak;
        }else{
          $msg = ['ID Rak untuk part : '.$prt->id_part.' kosong'];
          send_json(msg_sc_error($msg));
        }
      }

      $ins_part_sa[] = [
        'id_jasa'       => $ins_jasa_sa[0]['id_jasa'],
        'id_part'       => $prt->id_part,
        'harga'         => $prt->harga_dealer_user,
        'qty'           => $st['quantity'],
        'id_gudang'     => $id_gudang,
        'id_rak'        => $id_rak,
        'jenis_order'   => 'Reguler',
        'id_promo'      => $id_promo_part,
        'diskon_value'  => $diskon_value_part,
        'tipe_diskon'   => $tipe_diskon_part,
        'part_utama'    => 0,
        // 'tipe_diskon' => $tipe_diskon_part
      ];
      $total_part += ($res_type->harga_dealer * $st['quantity']) - $diskon_value_part;
    }

    //Hotline
    foreach ($post['hotline_part'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_part_int' => $st['part_id'],
        'cek_referensi' => true
      ];
      $prt = $this->m_sm->getParts($f);

      $ins_part_sa[] = [
        'id_jasa' => '',
        'id_part' => $prt->id_part,
        'harga' => $prt->harga_dealer_user,
        'qty' => $st['quantity'],
        'id_gudang' => '',
        'id_rak' => '',
        'jenis_order' => 'HLO',
        'id_promo' => NULL,
        'diskon_value' => 0,
        'part_utama' => 0
      ];

      $total_part += $res_type->harga_dealer;
    }

    // $total_part = isset($diskon_value) ? $total_part - $diskon_value : $total_part;
    //Part Demand
    foreach ($post['part_demand'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_part_int' => $st['part_id'],
        'cek_referensi' => true
      ];
      $prt = $this->m_sm->getParts($f);

      $ins_part_demand[] = [
        'id_part' => $prt->id_part,
        'id_dealer' => $this->login->id_dealer,
        'search_result' => $prt->nama_part . ', ' . $prt->id_part,
        'qty'           => $st['quantity'],
        'harga_satuan'  => $prt->harga_dealer_user,
        'search_field' => '',
        'sisa_stock' => 0,
        'note_field' => ''
      ];
    }

    $i_js=1;
    foreach ($ins_jasa_sa as $key => $js) {
      $subtotal = $js['harga'] - $js['diskon_value'];
      $need_parts='n';
      if ($i_js==1 && isset($ins_parts_sa)) {
        $need_parts='y';
      }
      $ins_jasa_wo[] = [
        'id_work_order' => $wo->id_work_order,
        'id_jasa' => $js['id_jasa'],
        'harga' => $js['harga'],
        'waktu' => $js['waktu'],
        'id_promo' => $js['id_promo'],
        'disc_amount' => $js['diskon_value'],
        'pekerjaan_luar' => 0,
        'subtotal' => $subtotal,
        'need_parts'=>$need_parts
      ];
      $i_js++;
    }

    $total_part = 0;
    if (isset($ins_part_sa)) {
      foreach ($ins_part_sa as $key => $js) {
        $prt = $this->db->get_where('ms_part', ['id_part' => $js['id_part']])->row();
        $subtotal = ($js['harga'] * $js['qty']) - $js['diskon_value'];
        $total_part += $subtotal;
        $ins_part_wo[] = [
          'id_work_order' => $wo->id_work_order,
          'id_jasa' => $js['id_jasa'],
          'id_part_int' => $prt->id_part_int,
          'id_part' => $js['id_part'],
          'qty' => $js['qty'],
          'harga' => $js['harga'],
          'id_gudang' => $js['id_gudang'],
          'id_rak' => $js['id_rak'],
          'jenis_order' => $js['jenis_order'],
          'diskon_value' => $js['diskon_value'],
          'id_promo' => $js['id_promo'],
          'order_to' => 0,
          'send_notif' => 0,
          'part_utama' => 0,
          'subtotal' => $subtotal,
          'tipe_diskon' => $js['tipe_diskon']
        ];
      }
    }

    $cond = ['id_pit_int' => $post['pit_id']];
    $pit = $this->db->get_where('ms_h2_pit', $cond);
    $id_pit = 0;
    $jenis_pit = '';
    if ($pit->num_rows() > 0) {
      $id_pit = $pit->row()->id_pit;
      $jenis_pit = $pit->row()->jenis_pit;
    }
    $upd_sa = [
      'id_pit' => $id_pit,
    ];

    // send_json($wo);
    if ($wo->last_stats != NULL) {
      if ($id_pit != $wo->id_pit) {
        // if ($id_pit != 0) {
        $msg = ['Pit tidak boleh diubah karena PKB sedang dikerjakan '];
        send_json(msg_sc_error($msg));
        // }
      }
    }

    $upd_wo = [
      'grand_total' => $total_jasa + $total_part,
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user
    ];

    $tes = [
      'upd_wo' => isset($upd_wo) ? $upd_wo : NULL,
      'ins_part_sa' => isset($ins_part_sa) ? $ins_part_sa : NULL,
      'ins_part_wo' => isset($ins_part_wo) ? $ins_part_wo : NULL,
      'ins_part_demand' => isset($ins_part_demand) ? $ins_part_demand : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    $cond_sa = ['id_sa_form' => $wo->id_sa_form];
    $this->db->update('tr_h2_sa_form', $upd_sa, $cond_sa);
    $cond_sa = ['id_sa_form' => $wo->id_sa_form];
    $this->db->update('tr_h2_wo_dealer', $upd_wo, $cond_sa);

    $cond = ['id_work_order' => $wo->id_work_order];
    $this->db->delete('tr_h2_wo_dealer_pekerjaan', $cond);
    $this->db->insert_batch('tr_h2_wo_dealer_pekerjaan', $ins_jasa_wo);
    $this->db->delete('tr_h2_wo_dealer_parts', $cond);
    if (isset($ins_part_wo)) {
      $this->db->insert_batch('tr_h2_wo_dealer_parts', $ins_part_wo);
    }

    if (isset($ins_part_demand)) {
      $this->db->insert_batch('tr_h3_dealer_record_reasons_and_parts_demand', $ins_part_demand);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["PKB Berhasil Diupdate !"];
      $this->m_wo->updateGrandTotalWO($wo->id_work_order);
      $params = [
        'id_user' => $this->login->id_user,
        'id_sa_form' => $wo->id_sa_form,
        'id_dealer' => $wo->id_dealer,
        'save_server' => true
      ];
      $cetak = $this->m_wo->cetak_wo($params);
      $data = [
        'document' => $cetak
      ];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function detail($pkb_id)
  {
    $f = [
      'id_work_order_int' => $pkb_id,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_wo->get_sa_form($f);
    cek_referensi($get_data, 'PKB ID');
    $wo = $get_data->row();

    $customer = [
      'name' => $wo->nama_customer,
      'phone' => $wo->no_hp,
      'address' => $wo->alamat,
      'district' => $wo->kelurahan,
    ];

    $f_k = ['id_tipe_kendaraan' => $wo->id_tipe_kendaraan];
    $tk = $this->m_unit->getTipeKendaraan($f_k)->row();
    $vehicle = [
      'police_no' => $wo->no_polisi,
      'stnk_name' => $wo->nama_customer,
      'vehicle_year' => (int)$wo->tahun_produksi,
      'vehicle_color_name' => $wo->warna,
      'vehicle_type_name' => $tk->id_tipe_kendaraan,
      'code_type_unit' => $tk->id_tipe_kendaraan,
      'vehicle_product_name' => $wo->tipe_ahm,
      'engine_no' => $wo->no_mesin,
      'frame_no' => $wo->no_rangka,
    ];

    $f_pr = ['id' => $wo->activity_promotion_id];
    $get_act_promo = $this->m_sm->getActivityPromotion($f_pr);
    if ($get_act_promo->num_rows() > 0) {
      $act_promo = $get_act_promo->row_array();
    }

    $params = [
      'status' => $wo->status_wo,
      'last_stats' => $wo->last_stats
    ];
    $status_pkb = status_pkb_apps($params);

    $f_p = ['id_work_order' => $wo->id_work_order, 'group_by' => 'group_by_part', 'select' => 'wo_parts'];
    $part = $this->m_wo->getWOParts($f_p);
    $part_list = [];
    $promo_part_id = 0;
    $promo_part_name = '';
    $promo_part_amount = 0;
    foreach ($part->result() as $prt) {
      if ($prt->id_work_order == NULL) {
        continue;
      }
      $part_list[] = [
        'part_id' => (int)$prt->id_part_int,
        'name' => $prt->nama_part,
        'code' => $prt->id_part,
        'unit_price' => (int)$prt->harga,
        'qty' => (int)$prt->qty,
        'total' => (float)$prt->tot_part,
      ];
      if (($prt->id_promo == '' || $prt->id_promo = NULL) != true) {
        $promo_part_id = $prt->id_promo;
        $promo_part_name = (string)$prt->nama_promo;
        $promo_part_amount = (int)$prt->promo_rp;
      }
    }

    $promo_service_id = 0;
    $promo_service_name = '';
    $promo_service_amount = 0;
    $f_cc = [
      'id_work_order' => $wo->id_work_order,
    ];
    $get_service_type = $this->m_wo->getWOPekerjaan($f_cc);
    $service_type = [];
    foreach ($get_service_type->result() as $rs) {
      $service_type[] = $rs->id_jasa;
    }
    $f_cc = ['id_work_order' => $wo->id_work_order, 'ada_promo' => true];
    $type = $this->m_wo->getWOPekerjaan($f_cc);
    if ($type->num_rows() > 0) {
      $type = $type->row();
      $promo_service_id = (int)$type->id_promo_int;
      $promo_service_name = $type->nama_promo;
      $promo_service_amount = (int)$type->diskon_value;
    }

    $f_k = ['id_karyawan_dealer' => $wo->id_karyawan_dealer];
    $mk = $this->m_sc->getKaryawan($f_k)->row();

    $f_k = ['id_user' => $wo->id_user_sa];
    $sad = $this->m_sc->getKaryawan($f_k)->row();

    $est_hours = floor($wo->etr / 3600);
    $sisa = $wo->etr % 3600;
    $est_minutes = floor($sisa / 60);

    $cond = ['id_pit' => $wo->id_pit, 'id_dealer' => $this->login->id_dealer];
    $pit = $this->db->get_where('ms_h2_pit', $cond);
    $id_pit = 0;
    $jenis_pit = '';
    if ($pit->num_rows() > 0) {
      $id_pit = substr($pit->row()->id_pit, 1, 3);
      $jenis_pit = $pit->row()->jenis_pit;
    }

    $result = [
      'pkb_date' => $wo->tgl_wo,
      'code_type_unit' => $tk->id_tipe_kendaraan,
      'pit_no' => (int)$id_pit,
      'pit_type' => (string)$jenis_pit,
      'customer' => $customer,
      'vehicle' => $vehicle,
      'kilometer' => (int)$wo->km_terakhir,
      'bbm' => (int)$wo->informasi_bensin,
      'assigned_at' => (string)$wo->assigned_at,
      'assigned_to_name' => isset($mk->name) ? $mk->name : '',
      'service_advisor' => isset($sad->name) ? $sad->name : '',
      'service_type' => $service_type,
      'promo_service_id' => $promo_service_id,
      'promo_service_name' => $promo_service_name,
      'promo_service_amount' => $promo_service_amount,
      'promo_part_id' => (int)$promo_part_id,
      'promo_part_name' => $promo_part_name,
      'promo_part_amount' => (int)$promo_part_amount,
      'estimate_hours' => (int)$est_hours,
      'estimate_minutes' => (int)$est_minutes,
      'status' => $status_pkb['status'],
      'complaint' => $wo->keluhan_konsumen,
      'mechanic_suggest' => $wo->rekomendasi_sa ?: '',
      'status_color' => $status_pkb['status_color'],
      'part_list' => $part_list,
    ];

    send_json(msg_sc_success($result, NULL));
  }

  function _getRakGudangByPartInDealer($id_part,$id_dealer)
  {
    $this->load->model('h3_dealer_stock_model', 'dealer_stock');
    $qty_avs = $this->dealer_stock->qty_avs('ds.id_dealer', 'ds.id_part', 'ds.id_gudang', 'ds.id_rak', true);

    $query = $this->db
    ->select('ds.*')
    ->select('mp.id_part')
    ->select('mp.nama_part')
    ->select('mp.harga_dealer_user as harga_saat_dibeli')
    ->select("
        case
            when ds.id_gudang is not null then ds.id_gudang
            else '---'
        end as id_gudang
    ")
    ->select("
        case
            when ds.id_rak is not null then ds.id_rak
            else '---'
        end as id_rak
    ")
    ->select("IFNULL(({$qty_avs}), 0) AS stock")
    ->from('ms_part as mp')
    ->join('ms_h3_dealer_stock as ds', "(ds.id_part = mp.id_part and ds.id_dealer = '{$id_dealer}')", 'left')
    ->where('mp.id_part', $id_part)
    ->where("IFNULL(({$qty_avs}), 0) > 0")
    ->order_by("ds.stock", 'desc');
    return $query->get()->row();
  }
}
