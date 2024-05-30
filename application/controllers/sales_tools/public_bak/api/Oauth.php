<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');
class Oauth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sc_auth', 'm_auth');
    $this->load->helper('sc');
  }
  public function token()
  {
    $header    = $this->input->request_headers();

    // send_json($header);
    // send_json($_SERVER);
    $filter    = pseudo_request($header);
    // send_json($filter);
    // $cek_login = $this->m_auth->cekLogin($filter);
    // if ($cek_login == false) {
    //   if ($filter) {
    //     $result = [
    //       'status' => 0,
    //       'message' => ["Username/Password is invalid"]
    //     ];
    //   } else {
    //     $result = ['status' => 0, 'message' => ['Header Authorization Tidak Terdeteksi']];
    //   }
    //   send_json($result);
    // }
    $result = $this->m_auth->generateToken($filter);
    if ($result) {
      $result =
        [
          'status' => 1,
          'message' => ['success'],
          'data' => $result
        ];
    } else {
      $result = [
        'status' => 0,
        'message' => ["Credential id wrong"]
      ];
    }
    send_json($result);
  }

  public function renew()
  {

    $header    = $this->input->request_headers();
    $filter    = token_bearer($header);
    // send_json($filter);
    $post = $this->input->post();
    $filter['password_renew'] = $post['password'];
    $res_ = $this->m_auth->renewToken($filter);
    if ($res_) {

      $result =
        [
          'status' => 1,
          'message' => ['Renewal Token Success'],
          'data' => $res_
        ];
    } else {
      // send_json($res_);
      $result = [
        'status' => 0,
        'message' => ["Authorization Token Invalid"],
        'data' => null
      ];
    }
    send_json($result);
  }
}
