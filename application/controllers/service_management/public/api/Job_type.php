<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Job_type extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sm_master', 'm_master');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function list_job()
  {
    $result = $this->m_master->getJobType()->result();
    $new_res = [];
    foreach ($result as $rs) {
      $new_res[] = [
        'id' => (int)$rs->id,
        'code' => $rs->code,
        'name' => $rs->name,
        'color' => (string)$rs->color,
      ];
    }
    send_json(msg_sc_success($new_res, NULL));
  }
}
