<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Prospek extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_dms');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function detail()
  {
    $get = $this->input->get();
    $mandatory = ['prospek_id' => 'required'];
    // send_json($get);
    cek_mandatory($mandatory, $get);

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'all'
    ];

    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prp = $get_data->row();
    $filter_folup = [
      'id_prospek' => $prp->id_prospek,
      'id_dealer' => $this->login->id_dealer,
      'order' => 'ORDER BY id DESC',
    ];

    $folup = $this->m_prospek->getProspekFollowUp($filter_folup);
    if ($folup->num_rows() > 0) {
      $no = $folup->num_rows();
      foreach ($folup->result() as $fol) {
        $latest_folup[] = [
          'id' => (int)$fol->id,
          'name' => 'Follow Up ' . $no,
          'activity' => $fol->metode_fol_up_text,
          'check_date' => $fol->check_date == NULL ? '' : mediumdate_indo($fol->check_date, ' '),
          'commited_date' => (string)mediumdate_indo($fol->tgl_fol_up, ' '),
          'description' => $fol->keterangan
        ];
        $no--;
      }
    }
    $f_acc = [
      'id_prospek' => $prp->id_prospek,
      'select' => 'sum_qty',
      'id_dealer' => $prp->id_dealer
    ];
    $tot_acc = (int)$this->m_prospek->getProspekAccessories($f_acc)->row()->tot;
    $result = [
      'status' => $prp->status_prospek,
      'name' => $prp->nama_konsumen,
      'phone' => $prp->no_hp,
      'image' => image_karyawan($prp->customer_image, $prp->jenis_kelamin),
      'produk' => $prp->id_tipe_kendaraan,
      'accessories' => $tot_acc,
      'test_kendaraan' => (string)$prp->tgl_tes_kendaraan == '' ? '' : mediumdate_indo($prp->tgl_tes_kendaraan, ' '),
      'follow_up' => isset($latest_folup) ? $latest_folup : [],
    ];

    send_json(msg_sc_success($result, NULL));
  }

  function follow_up()
  {
    $get = $this->input->get();
    $mandatory = [
      'prospek_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);
    $filter = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'page' => $get['page'],
      'return' => 'for_service_concept'
    ];
    $prospek = $this->m_prospek->getProspekFollowUp($filter);
    send_json(msg_sc_success($prospek, NULL));
  }

  function informasi_pelanggan()
  {
    $get = $this->input->get();
    $mandatory = [
      'prospek_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    $filter = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'customer_detail_sc',
    ];
    $get_data = $this->m_prospek->getProspek($filter);
    cek_referensi($get_data, 'Prospek ID');
    $prp = $get_data->row();
    // send_json($prp);
    $prospek = [
      'name' => $prp->name,
      'title' => set_title($prp->jenis_kelamin),
      'image' => image_karyawan($prp->image, $prp->jenis_kelamin),
      'nik' => $prp->ktp,
      'jenis_kelamin' => $prp->jenis_kelamin,
      'no_telepon' => $prp->no_telepon,
      'no_hp' => $prp->no_hp,
      'phone' => $prp->no_hp,
      'tempat_lahir' => $prp->tempat_lahir ?: '',
      'birthdate' => (string)$prp->tgl_lahir == '' ? '' : mediumdate_indo($prp->tgl_lahir, ' '),
      'address' => $prp->address,
      'latitude' => $prp->latitude,
      'longitude' => $prp->longitude,
      'pekerjaan_id' => $prp->pekerjaan_id,
      'pekerjaan_name' => $prp->pekerjaan_name,
      'office_phone' => $prp->office_phone,
      'office_address' => $prp->office_address,
      'sumber_prospek_id' => (int)$prp->sumber_prospek_id,
      'sumber_prospek_name' => $prp->sumber_prospek_name,
      'metode_follow_up_id' => (int)$prp->metode_follow_up_id,
      'metode_follow_up_name' => $prp->metode_fol_up_name,
      'postal_code' => (int)$prp->kodepos,
      'email' => $prp->email ?: '',
      'summer_prospek' => ''
    ];

    send_json(msg_sc_success($prospek, NULL));
  }
  function informasi_produk()
  {
    $get = $this->input->get();
    $mandatory = [
      'prospek_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    $filter = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter);
    cek_referensi($get_data, 'Prospek ID');
    $prp = $get_data->row();
    $get_item = $this->m_prospek->getProspekUnitDetail($filter)->result();
    foreach ($get_item as $itm) {
      $item[] = [
        'id'         => (int)$itm->id_item_int,
        'product_id'         => (int)$itm->id_item_int,
        'name'       => $itm->tipe_ahm,
        'image'      => '',
        'code'       => $itm->id_item,
        'price'      => (int)$prp->price_unit,
        'color_name' => $itm->warna,
        'color_hex'  => '',
        'type'       => 'Unit'
      ];
    }
    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_acc = $this->m_prospek->getProspekAccessories($filter_prospek)->result();
    foreach ($get_acc as $acc) {
      $item[] = [
        'id'         => $acc->id_part,
        'name'       => $acc->nama_part,
        'image'      => '',
        'code'       => $acc->id_part,
        'price'      => $acc->accessories_harga,
        'color_name' => '',
        'color_hex'  => '',
        'type'       => 'Accessories'
      ];
    }

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_app = $this->m_prospek->getProspekApparel($filter_prospek)->result();
    foreach ($get_app as $app) {
      $item[] = [
        'id'         => $app->id_part,
        'name'       => $app->nama_part,
        'image'      => '',
        'code'       => $app->id_part,
        'price'      => $app->apparel_harga,
        'color_name' => '',
        'color_hex'  => '',
        'type'       => 'Apparel'
      ];
    }
    send_json(msg_sc_success($item, NULL));
  }

  function prospek_list()
  {
    $get = $this->input->get();
    $get['status_prospek'] = $this->input->get('status');
    $get['id_dealer'] = $this->login->id_dealer;
    $get['id_karyawan_dealer_int'] = $get['employee_id'];
    $get['select'] = "all";
    $get_kry = $this->m_master->getKaryawan($get);
    cek_referensi($get_kry, 'Employee ID');
    $kry = $get_kry->row();
    $prospek = $this->m_prospek->getProspekActivity($kry->id_karyawan_dealer, $this->login, $kry->honda_id);

    $data = [
      'name' => $kry->nama_lengkap,
      'image' => image_karyawan($kry->image, 'laki-laki'),
      'actual' => $prospek['actual'],
      'target' => $prospek['target'],
    ];


    $get['select'] = 'show_prospek_mobile';
    $get['bulan_prospek'] = get_ym();
    $res_ = $this->m_prospek->getProspek($get);
    $data['item'] = [];
    foreach ($res_->result() as $rs) {
      $data['item'][] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
        'image' => image_karyawan($rs->image, 'laki-laki'),
        'produk' => $rs->produk_name,
        'status' => $rs->status,
        'assigned' => $rs->assigned,
        'followup' => $rs->follow_up
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function index()
  {
    $get = $this->input->get();
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_dt = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer,
      'search_sc' => isset($get['search']) ? $get['search'] : '',
    ];
    $data_sales = $this->m_dms->getTeamStructureManagementDetail($f_dt);

    $actual = 0;
    $target = 0;
    $hot = 0;
    $low = 0;

    foreach ($data_sales->result() as $dt) {
      $kry = new stdClass();
      $kry->id_karyawan_dealer = $dt->id_karyawan_dealer;
      $kry->honda_id = $dt->honda_id == NULL ? $dt->id_flp_md : $dt->honda_id;

      $user = new stdClass();
      $user->id_dealer = $this->login->id_dealer;

      $prospek = $this->m_prospek->getProspekActivity($kry->id_karyawan_dealer, $user, $kry->honda_id);

      $item[] = [
        'id' => (int)$dt->id_karyawan_dealer_int,
        'name' => $dt->nama_lengkap,
        'image' => image_karyawan($dt->image, $dt->jenis_kelamin),
        'actual' => $prospek['actual'],
        'target' => $prospek['target'],
        'hot' => $prospek['hot'],
        'medium' => $prospek['medium'],
        'low' => $prospek['low'],
      ];
      $actual += $prospek['actual'];
      $target += $prospek['target'];
      $hot    += $prospek['hot'];
      $low    += $prospek['low'];
    }
    $result = [
      'actual' => $actual,
      'target' => $target,
      'hot' => $hot,
      'low' => $low,
      'item' => isset($item) ? $item : ''
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function reassign_customer()
  {
    //Cek Mandatory
    $post = $this->input->post();
    $mandatory = [
      'prospek_id' => 'required',
      'employee_id' => 'required',
      'master_alasan_reassign_id' => 'required',
      'catatan' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    //Get Data Prospek
    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    //Get Alasan Reassign
    $f_asg = ['id' => $post['master_alasan_reassign_id'], 'cek_referensi' => true];
    $alasan = $this->m_master->getAlasanReassign($f_asg);

    //Get Data Karyawan
    $f_kry = ['id_karyawan_dealer_int' => $post['employee_id'], 'cek_referensi' => true, 'select' => 'all'];
    cek_referensi($get_data, 'Employee ID');
    $kry = $this->m_master->getKaryawan($f_kry);

    // send_json($prospek);
    $insert = [
      'id_prospek' => $prospek->id_prospek,
      'id_karyawan_dealer_lama' => $prospek->id_karyawan_dealer,
      'id_karyawan_dealer_baru' => $kry->id_karyawan_dealer,
      'alasan_reassign_id' => $alasan->id,
      'catatan' => $post['catatan'],
      'first' => 0,
      'last' => 1,
      'created_at' => waktu_full(),
      'created_by' => $this->login->id_user
    ];

    $upd_prospek = [
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_flp_md' => $kry->id_flp_md,
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user
    ];


    //Get Activity Berdasarkan Parent ID
    $f_act = [
      'parent_id' => $prospek->id_prospek,
      'check_date_null' => true,
      'select' => 'all',
      'order' => 'new',
      'limit' => "LIMIT 1"
    ];
    // send_json($f_act);
    $get_act = $this->m_activity->getActivity($f_act);
    cek_referensi($get_act, 'Activity ID');
    $act = $get_act->row();

    $upd_activity = [
      'id_karyawan_dealer_int' => $kry->id_karyawan_dealer_int,
      'id_karyawan_dealer_int_old' => $act->id_karyawan_dealer_int,
      'reassign_at' => waktu_full(),
      'reassign_by' => $this->login->id_user
    ];
    // $tes = [
    //   'upd_prospek' => $upd_prospek,
    //   'upd_activity' => $upd_activity,
    //   'insert' => $insert,
    // ];
    // send_json($tes);

    $this->db->trans_begin();
    $this->db->update('tr_prospek', $upd_prospek, ['id_prospek' => $prospek->id_prospek]);
    $this->db->update('tr_sc_sales_activity', $upd_activity, ['id' => $act->id]);
    $upd = ['last' => 0];
    $this->db->update('tr_prospek_history_reassign', $upd, ['id_prospek' => $prospek->id_prospek]);
    $this->db->insert('tr_prospek_history_reassign', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Reassign customer success"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }
}
