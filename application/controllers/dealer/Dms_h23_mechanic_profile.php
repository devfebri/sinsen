<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_h23_mechanic_profile extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h23_mechanic_profile";
  var $title  = "Mechanic Profile";

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
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h2_master', 'm_h2_m');
    $this->load->model('m_dms');


    //===== Load Library =====
    $this->load->library('upload');
    $this->load->helper('tgl_indo');
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

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $btn_detail = "<a data-toggle='tooltip' title='Mechanic Profile' href='dealer/$this->page/detail?id=$rs->id_karyawan_dealer' class='btn btn-flat btn-xs btn-info'><i class='fa fa-user'></i></a>";
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = $rs->id_flp_md;
      $sub_array[] = $rs->nama_lengkap;
      $sub_array[] = $rs->jabatan;
      $sub_array[] = $active;
      $sub_array[] = $btn_detail;
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
      'order_column' => 'view_mekanik',
      'id_jabatan_in' => sql_jabatan_mekanik(),
      'id_dealer' => dealer()->id_dealer
    ];
    if ($recordsFiltered == true) {
      return $this->m_dms->getKaryawanDealer($filter)->num_rows();
    } else {
      return $this->m_dms->getKaryawanDealer($filter)->result();
    }
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "detail";
    $id_karyawan_dealer     = $this->input->get('id');

    $filter  = [
      'id_dealer' => dealer()->id_dealer,
      'id_karyawan_dealer' => $id_karyawan_dealer,
      'tanggal' => get_ymd()
    ];
    $result = $this->m_dms->getMechanicProfile($filter);
    if ($result->num_rows() > 0) {
      $data['row']    = $result->row();
      $filter_wo = [
        'id_work_order_not_null' => true,
        'id_karyawan_dealer' => $id_karyawan_dealer,
        'select' => ['concat_tipe_pekerjaan'],
        'tgl_servis' => get_ymd()
      ];
      $wo_now = $this->m_wo->get_sa_form($filter_wo);
      if ($wo_now->num_rows() > 0) {
        $data['wo_now'] = $wo_now->row();
      }
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }


  public function fetchRiwayat()
  {
    $fetch_data = $this->make_query_riwayat();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      // $btn_detail = "<a data-toggle='tooltip' title='Mechanic Profile' href='dealer/$this->page/detail?id=$rs->id_karyawan_dealer' class='btn btn-flat btn-xs btn-info'><i class='fa fa-user'></i></a>";

      $sub_array[] = $rs->id_work_order_int;
      $sub_array[] = $rs->id_work_order;
      $sub_array[] = $rs->concat_tipe_pekerjaan;
      $sub_array[] = $rs->estimasi_waktu_kerja . ' Menit';
      // $sub_array[] = round($rs->total_waktu / 60);
      $sub_array[] = round(($rs->total_waktu / 60), 1) . ' Menit';
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_riwayat(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_riwayat($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $order[0] = ['column' => 0, 'dir' => 'desc'];
    $filter = [
      'limit'  => $limit,
      'order'  => $order,
      'search' => $this->input->post('search')['value'],
      'id_karyawan_dealer' => $this->input->post('id_karyawan_dealer'),
      'status_wo' => ['closed'],
      'tahun_bulan_wo' => get_ym(),
      'select' => ['concat_tipe_pekerjaan']
    ];
    if ($recordsFiltered == true) {
      return $this->m_wo->get_sa_form($filter)->num_rows();
    } else {
      return $this->m_wo->get_sa_form($filter)->result();
    }
  }
  public function fetchDiagramRiwayatServis($id_karyawan_dealer = NULL)
  {
    $post = $this->input->post();
    $tipe = $this->m_h2_m->get_job_type();
    $label      = [];
    $background = [];
    $data       = [];
    $bg = [
      'kpb' => '#F65F4F',
      'claim' => '#8846DB',
      'quick_service' => '#165ED7',
      'heavy_repair' => '#13B9E9',
      'light_repair' => '#FDCA42',
      'light_service' => '#a11cad',
      'complete_service' => '#fffe00',
      'oil_replacement' => '#c15a5a',
    ];
    $label_set = [
      'kpb' => 'KPB',
      'claim' => 'Claim',
      'quick_service' => 'Quick Service',
      'heavy_repair' => 'Heavy Repair',
      'light_repair' => 'Light Repair',
      'light_service' => 'Light Service',
      'complete_service' => 'Complete Service',
      'oil_replacement' => 'Oil Replacement',
    ];
    if ($this->input->post('id_karyawan_dealer') != NULL) {
      $id_karyawan_dealer = $this->input->post('id_karyawan_dealer');
    }
    $get['id_karyawan_dealer'] = $id_karyawan_dealer;
    $get['tahun_bulan_wo'] = get_ym();
    $result = $this->m_dms->mechanic_diagram($get);
    $no = 0;
    foreach ($result as $key => $value) {

      $label[] = $label_set[$key];
      $data[] = $value;
      $no++;
      $background[] = $bg[$key];
    }
    // foreach ($tipe->result() as $key => $tp) {
    //   $label[]      = $tp->deskripsi;
    //   $background[] = $bg[$key];
    //   $filter = [
    //     'id_karyawan_dealer' => $post['id_karyawan_dealer'],
    //     'id_type' => $tp->id_type
    //   ];
    //   $data[]       = $this->m_wo->getMekanikHistoryServis($filter);
    // }
    $res = ['status' => 'sukses', 'label' => $label, 'background' => $background, 'data' => $data];
    send_json($res);
  }
}
