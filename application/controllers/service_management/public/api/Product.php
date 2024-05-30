<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Product extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sm_master', 'm_master');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function list_product()
  {
    $get = $this->input->get();
    $mandatory = [
      'product_category_id' => 'required',
      'offset' => 'required',
      'limit' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_ctg = [
      'id_kategori_int' => $get['product_category_id'],
      // 'offset' => $get['offset'],
      // 'length' => $get['limit'],
      'active' => 1,
    ];

    $result = $this->m_master->getTipeKendaraan($f_ctg)->result();
    $new_res = [];
    foreach ($result as $rs) {
      $new_res[] = [
        'id' => $rs->id,
        'name' => $rs->name
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
}
