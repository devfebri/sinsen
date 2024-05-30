<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Inbox extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_sc_sp_inbox', 'm_inbox');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }
  function index()
  {
    $get = $this->input->get();
    $get['select_tanggal'] = true;
    $get['group_by_tanggal'] = true;
    $get['sent'] = 1;
    $get['username'] = $this->login->username;
    $get['order'] = "dm.created_at DESC";
    $res_ = $this->m_inbox->getInbox($get);
    $new_res = [];
    $no = 1;
    foreach ($res_->result() as $val) {
      $filter = [
        'tanggal' => $val->tanggal,
        'username' => $this->login->username,
        'select_detail' => true,
        'sent' => 1,
        'order' => "dm.created_at DESC"
      ];
      $list = $this->m_inbox->getInbox($filter)->result();
      $new_list = [];
      foreach ($list as $ls) {
        $ls = [
          'id'         => $ls->iid,
          'name'       => $ls->name,
          'info'       => (string)$ls->info,
          'code'       => $ls->code,
          'time'       => $ls->time,
          'content'    => $ls->content,
          'categories' => $ls->message_type,
          'read'       => $ls->bisread,
          'expired'    => $ls->expired,
        ];
        $new_list[] = $ls;
      }
      $res = [
        'id'      => $no,
        'date' => $val->tanggal,
        'list'    => $new_list,
      ];
      $new_res[] = $res;
      $no++;
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }

  function read()
  {
    // send_json($this->login);
    $post = $this->input->post();
    if ($post['id'] == '') {
      $result = ['status' => 0, 'message' => ['ID Requided !']];
      send_json($result);
    }
    $where = [
      'iid' => $post['id'],
      'vusername' => $this->login->username
    ];

    $this->db->update('dms_master_message', ['bisread' => 1], $where);
    if ($this->db->affected_rows() > 0) {
      $result = [
        'status' => 1,
        'message' => ["Read data success"]
      ];
    } else {
      $result = [
        'status' => 0,
        'message' => ['Failed read data']
      ];
    }
    send_json($result);
  }
}
