<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Other extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sm_master', 'm_master');
    $this->load->model('m_sc_master', 'm_master_sc');
    $this->load->model('m_master_unit', 'm_unit');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function list_type_color()
  {
    $res_color = $this->m_master->getWarnaKendaraan()->result();
    $res_type = $this->m_master->getTipeKendaraan()->result();
    $result = ['color' => $res_color, 'vehicle_type' => $res_type];
    send_json(msg_sc_success($result, NULL));
  }

  function sub_district()
  {
    $get = $this->input->get();
    $f = [
      'search' => isset($get['search']) ? $get['search'] : '',
      'select' => 'concat',
    ];
    $result = $this->m_master_sc->getKelurahan($f)->result();
    $new_res = [];
    foreach ($result as $key => $value) {
      $new_res[] = [
        'id' => (int)$value->id,
        'name' => $value->name
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
  }
}
