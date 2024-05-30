<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Product_category extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sm_master', 'm_master');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function list_category()
  {
    $get = $this->input->get();
    $mandatory = [
      'offset' => 'required',
      'limit' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_ctg = [
      'offset' => $get['offset'],
      'length' => $get['limit'],
    ];

    $result = $this->m_master->getProductCategory($f_ctg)->result();
    send_json(msg_sc_success($result, NULL));
  }
}
