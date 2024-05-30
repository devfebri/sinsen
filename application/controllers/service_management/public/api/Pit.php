<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Pit extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2_booking', 'm_booking');
    $this->load->model('m_h2_master', 'm_h2_m');
    $this->load->model('m_sm_master', 'm_sm');
    $this->load->model('m_h2');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function list_type()
  {
    $result = $this->m_h2->get_jenis_pit();
    $res = [];
    foreach ($result->result() as $rs) {
      $res[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
      ];
    }
    send_json(msg_sc_success($res, NULL));
  }
  function list_pit()
  {
    $get = $this->input->get();
    $mandatory = [
      'pit_type_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    // $f_type = [
    //   'id' => $get['pit_type_id']
    // ];

    // $get_data = $this->m_h2->get_jenis_pit($f_type);
    // cek_referensi($get_data, 'PIT Type ID');
    // $type = $get_data->row();
    // $f_pit = [
    //   'jenis_pit' => $type->name,
    //   'id_dealer' => $this->login->id_dealer,
    //   'select' => 'api_sm',
    //   'ada_mekanik' => true,
    //   'ready_wo' => true,
    // ];
    // $result = $this->m_h2->get_pit($f_pit);
    // $res = [];
    // foreach ($result->result() as $rs) {
    //   $res[] = [
    //     'id' => (int)$rs->id,
    //     'no' => (int)$rs->no,
    //     'pit_type_id' => (int)$rs->pit_type_id,
    //     'pit_type_name' => $rs->pit_type_name,
    //   ];
    // }

    $id_dealer = $this->login->id_dealer;
    $f_mc = [
      'id_dealer'  => $id_dealer,
    ];
    $result = [];
    $get_data = $this->m_h2->get_pit_mekanik($f_mc);
    foreach ($get_data->result() as $rs) {
      $f_pit = ['id_pit' => $rs->id_pit, 'id_dealer' => $rs->id_dealer, 'select' => 'api_sm',];
      $pit = $this->m_h2->get_pit($f_pit)->row();

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
          'id' => (int)$pit->id,
          'no' => (int)$pit->no,
          'pit_type_id' => (int)$pit->pit_type_id,
          'pit_type_name' => $pit->pit_type_name,
        ];
      }
    }
    send_json(msg_sc_success($result, NULL));
  }
}
