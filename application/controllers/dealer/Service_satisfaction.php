<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service_satisfaction extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "service_satisfaction";
  var $title  = "Service Satisfaction";

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
    $this->load->model('m_h2_master', 'm_h2');
    $this->load->model('m_h2_billing', 'm_bil');
    $this->load->model('m_h2_work_order', 'm_wo');

    //===== Load Library =====
    $this->load->library('upload');
    $this->load->library('form_validation');
    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $page = $this->page;
      if (isset($data['mode'])) {
        if ($data['mode'] == 'detail_wo') {
          $page = 'sa_form';
        }
        if ($data['mode'] == 'detail_njb' || $data['mode'] == 'detail_nsc') {
          $page = 'billing_process';
        }
      }
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";

    $filter        = ['njb_not_null' => 'ya', 'level_satisfaction_null' => 1, 'id_dealer' => $this->m_admin->cari_dealer()];
    $wo    = $this->m_wo->get_sa_form($filter);
    foreach ($wo->result() as $w) {
      $result[] = [
        'sumber' => 'Work Order',
        'id_referensi' => $w->id_work_order,
        'no_polisi' => $w->no_polisi,
        'nama_customer' => $w->nama_customer,
        'tipe_motor' => $w->tipe_ahm,
      ];
    }
    $filter = ['referensi' => 'sales', 'level_satisfaction_null' => 1];
    $nsc    = $this->m_bil->getNSC($filter);
    foreach ($nsc->result() as $ns) {
      $result[] = [
        'sumber'        => 'Part Sales',
        'id_referensi'  => $ns->id_referensi,
        'no_polisi'     => '',
        'nama_customer' => $ns->nama_customer,
        'tipe_motor'    => $ns->tipe_ahm,
      ];
    }
    if (empty($result)) {
      $result = [];
    }
    $data['result'] = $result;
    // send_json($data);
    $this->template($data);
  }

  public function record_satisfaction()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Record ' . $this->title;
    $data['mode']  = 'record';
    $data['set']   = "form";
    $id_work_order    = $this->input->get('id');

    $filter['id_work_order'] = $this->input->post('id_work_order');
    $sa_form = $this->m_h2->get_sa_form($filter);
    if ($sa_form->num_rows() > 0) {
      $row                     = $data['wo'] = $sa_form->row();
      $data['tipe_coming']     = explode(',', $row->tipe_coming);
      $filter['id_work_order'] = $id_work_order;
      $data['details']         = $this->m_h2->wo_detail($filter);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/work_order_dealer'>";
    }
  }

  public function save()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $id_satisfaction = $this->m_h2->get_id_satisfaction();
    $id_dealer    = $this->m_admin->cari_dealer();
    $post = $this->input->post();
    $data   = [
      'id_satisfaction' => $id_satisfaction,
      'id_referensi'    => $post['id_referensi'],
      'sumber'          => $post['sumber'] == 'Service' ? 'service' : 'sales',
      'level'           => $post['level'],
      'created_at'      => $waktu,
      'created_by'      => $login_id,
      'id_dealer'       => $id_dealer
    ];

    // send_json($data);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_service_satisfaction', $data);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/service_satisfaction')
      ];
      $_SESSION['pesan']   = "Data has been recorded successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function history()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "history";
    $this->template($data);
  }

  public function fetchHistory()
  {
    $fetch_data = $this->make_query_fetchHistorySatisfaction();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $sub_array[] = ucwords($rs->sumber) == 'Service' ? 'Work Order' : ucwords($rs->sumber);
      $sub_array[] = $rs->id_referensi;
      $sub_array[] = '';
      $sub_array[] = ucwords($rs->nama_customer);
      $sub_array[] = ucwords($rs->tipe_ahm);
      $sub_array[] = $rs->level_teks;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_fetchHistorySatisfaction(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_fetchHistorySatisfaction($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';
    $id_dealer  = $this->m_admin->cari_dealer();
    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'search' => $this->input->post('search')['value'],
    ];

    if ($recordsFiltered == true) {
      return $this->m_bil->fetchHistorySatisfaction($filter)->num_rows();
    } else {
      return $this->m_bil->fetchHistorySatisfaction($filter)->result();
    }
  }
}
