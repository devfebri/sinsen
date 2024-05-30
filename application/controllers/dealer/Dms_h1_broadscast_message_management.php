<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_h1_broadscast_message_management extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h1_broadscast_message_management";
  var $title  = "Broadcast Message Management";

  public function __construct()
  {
    parent::__construct();
    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_dms');


    //===== Load Library =====
    $this->load->library('upload');
    $this->load->helper('tgl_indo');
    $this->load->helper('sc');
    $this->load->helper('terbilang');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['folder'] = $this->folder;
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";
    $this->template($data);
  }
  public function history()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "history";
    $this->template($data);
  }
  public function received()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "received";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $btn_edit = '<a data-toggle="tooltip" title="Edit" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->iid . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $btn_detail = '<a data-toggle="tooltip" title="Detail" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->iid . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-eye"></i></a>';
      $btn_send = '<a data-toggle="tooltip" title="Send" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/send?id=' . $rs->iid . '" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-send"></i></a>';
      $btn_delete = '<a onclick=\' return confirm("Apakah Anda yakin ?")\' data-toggle="tooltip" title="Delete" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/deleted?id=' . $rs->iid . '" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></a>';
      // if (can_access($this->page, 'can_update')) $button .= $btn_edit;
      // if (can_access($this->page, 'can_delete')) $button .= $btn_delete;
      // $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';
      // $sub_array[] = $rs->iid;
      $button .= $btn_detail . ' ';
      if ($rs->sent == 0) {
        if (empty($_POST['is_history'])) {
          $button .= " " . $btn_edit . ' ' . $btn_send;
        }
      }
      if (isset($_POST['is_history'])) {
        $button = $btn_detail;
      }
      $sub_array[] = $rs->iid;
      $sub_array[] = $rs->vtitle;
      $sub_array[] = $rs->message_type;
      $sub_array[] = $rs->created_at;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'search' => $this->input->post('search')['value'],
      'order_column' => 'view',
    ];
    $filter['sender_id'] = user()->id_user;
    if (isset($_POST['periode'])) {
      $filter['periode'] = $_POST['periode'];
    }
    if (isset($_POST['periode_lebih_kecil'])) {
      $filter['periode_lebih_kecil'] = $_POST['periode_lebih_kecil'];
    }
    if ($recordsFiltered == true) {
      return $this->m_dms->getH1BroadcastMessage($filter)->num_rows();
    } else {
      return $this->m_dms->getH1BroadcastMessage($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']      = $this->page;
    $data['title']    = $this->title;
    $data['mode']     = 'insert';
    $data['set']      = "form";
    $data['kry']      = kry_login(user()->id_user);
    $data['msg_type'] = $this->m_dms->getMessageType()->result();
    // send_json($data);
    $this->template($data);
  }

  function save()
  {
    $post       = $this->input->post();
    $id_message = $this->m_dms->get_id_message();
    $insert = [
      'iid'        => $id_message,
      'vtitle'     => $post['vtitle'],
      'vcontents'  => $post['vcontents'],
      'bisdelete'  => 0,
      'imsgtype'   => $post['imsgtype'],
      'sender_id'  => user()->id_user,
      'sent'       => 0,
      'created_at' => waktu_full(),
      'created_by' => user()->id_user,
    ];
    foreach ($post['to'] as $val) {
      if ($val['username'] == '' || $val['username'] == NULL) {
        $val['username'] = $val['username_sc'];
      }
      $ins_to[] =
        [
          'iid'       => $id_message,
          'vusername' => $val['username'],
        ];
    }

    // $tes = ['insert' => $insert, 'ins_to' => $ins_to];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('dms_detail_message', $insert);
    $this->db->insert_batch('dms_master_message', $ins_to);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }
  function deleted()
  {
    $get       = $this->input->get();
    $filter = ['id' => $get['id']];
    $cek = $this->m_dms->getH1TargetManagement($filter);
    if ($cek->num_rows() > 0) {
      $this->db->trans_begin();
      $deleted = ['deleted' => 1];
      $this->db->update('dms_h1_target_management', $deleted, ['id' => $get['id']]);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $_SESSION['pesan']   = "Something went wrong !";
        $_SESSION['tipe']   = "error";
      } else {
        $this->db->trans_commit();
        $_SESSION['pesan']   = "Data has been deleted successfully";
        $_SESSION['tipe']   = "success";
      }
    }
    echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->page) . "'>";
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $iid    = $this->input->get('id');

    $filter['iid'] = $iid;
    $result = $this->m_dms->getH1BroadcastMessage($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['msg_type'] = $this->m_dms->getMessageType()->result();
      $data['to'] = $this->m_dms->getH1BroadcastMessageTo($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $iid    = $this->input->get('id');

    $filter = [
      'iid' => $iid,
      'sent' => 0
    ];
    $result = $this->m_dms->getH1BroadcastMessage($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['msg_type'] = $this->m_dms->getMessageType()->result();
      $data['to'] = $this->m_dms->getH1BroadcastMessageTo($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $post       = $this->input->post();
    $iid = $post['iid'];
    // send_json($post);
    $update = [
      'vtitle'     => $post['vtitle'],
      'vcontents'  => $post['vcontents'],
      'bisdelete'  => 0,
      'imsgtype'   => $post['imsgtype'],
      'sender_id'  => user()->id_user,
      'sent'       => 0,
      'updated_at' => waktu_full(),
      'updated_by' => user()->id_user,
    ];
    foreach ($post['to'] as $val) {
      if ($val['username'] == '' || $val['username'] == NULL) {
        $val['username'] = $val['username_sc'];
      }
      $ins_to[] =
        [
          'iid'        => $iid,
          'vusername'  => $val['username'],
          'xpmmsg_iid' => $val['xpmmsg_iid'],
          'bisdelete'  => $val['bisdelete'] == '' ? 0 : $val['bisdelete'],
          'bismarked'  => $val['bismarked'] == '' ? 0 : $val['bismarked'],
          'bisread'    => $val['bisread']   == '' ? 0 : $val['bisread'],
          'bissent'    => $val['bissent'] == '' ? 0 : $val['bissent'],
        ];
    }
    // $tes = ['update' => $update, 'ins_to' => $ins_to];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_detail_message', $update, ['iid' => $iid]);
    $this->db->delete('dms_master_message', ['iid' => $iid]);
    $this->db->insert_batch('dms_master_message', $ins_to);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function send()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'send';
    $data['set']   = "form";
    $iid    = $this->input->get('id');

    $filter['iid'] = $iid;
    $result = $this->m_dms->getH1BroadcastMessage($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['msg_type'] = $this->m_dms->getMessageType()->result();
      $data['to'] = $this->m_dms->getH1BroadcastMessageTo($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_send()
  {
    $post       = $this->input->post();
    $iid = $post['iid'];
    // send_json($post);
    $update = [
      'sent'       => 1,
      'sending_at' => waktu_full(),
      'sending_by' => user()->id_user,
    ];
    $filter['iid'] = $iid;
    $to = $this->m_dms->getH1BroadcastMessageTo($filter)->result();
    foreach ($to as $val) {
      $bissent = broadcast_message($to);
      $update_receiver[] =
        [
          'vusername' => $val->username_bc,
          'bissent' => $bissent,
        ];
      $regid_ = $this->db->query("SELECT regid FROM ms_user WHERE username='$val->username_bc'")->row();
      if ($regid_ != NULL) {
        if ($regid_->regid != NULL) {
          $regid[] = $regid_->regid;
        }
      } else {
        $regid_ = $this->db->query("SELECT regid FROM ms_user WHERE username_sc='$val->username_bc'")->row();
        if ($regid_ != NULL) {
          if ($regid_->regid != NULL) {
            $regid[] = $regid_->regid;
          }
        }
      }
    }
    if (isset($regid)) {
      $msg = $this->db->get_where('dms_detail_message', ['iid' => $iid])->row();
      $params = [
        'judul' => $msg->vtitle,
        'pesan' => $msg->vcontents,
        'command' => '',
        'for' => 'h1',
        'is_mobile' => false,
        'regid' => $regid
      ];
      $res = send_fcm($params);
    }
    // $tes = [
    //   'update' => $update,
    //   'update_receiver' => $update_receiver,
    //   'regid' => $regid,
    //   'params' => $params,
    //   'response_fcm' => $res,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_detail_message', $update, ['iid' => $iid]);
    $this->db->where(['iid' => $iid]);
    $this->db->update_batch('dms_master_message', $update_receiver, 'vusername');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been sending successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function fetch_received()
  {
    $fetch_data = $this->make_query_received();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      // $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_message . '">' . $rs->id_message . '</a>';
      $sub_array[] = $rs->iid;
      $sub_array[] = $rs->created_at;
      $sub_array[] = $rs->nama_lengkap;
      $sub_array[] = $rs->message_type;
      $sub_array[] = $rs->vtitle;
      $sub_array[] = $rs->vcontents;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_received($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'search' => $this->input->post('search')['value'],
      'order_column' => 'view',
      'id_karyawan_dealer_receiver' => $_POST['id_karyawan_dealer_receiver']
    ];
    if ($recordsFiltered == true) {
      return $this->m_dms->getH1BroadcastMessageReceived($filter)->num_rows();
    } else {
      return $this->m_dms->getH1BroadcastMessageReceived($filter)->result();
    }
  }
}
