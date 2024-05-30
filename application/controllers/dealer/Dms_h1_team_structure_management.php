<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_h1_team_structure_management extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h1_team_structure_management";
  var $title  = "Team Structure Management";

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
        if ($data['mode'] == 'detail_njb') {
          $page = 'njb';
        }
        if ($data['mode'] == 'detail_nsc') {
          $page = 'nsc';
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
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      $btn_edit = '<a data-toggle="tooltip" title="Edit Data" style="margin-top:2px; margin-right:1px;"href="dealer/' . $this->page . '/edit?id=' . $rs->id_team_structure . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      if (can_access($this->page, 'can_update')) $button = $btn_edit;
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail?id=' . $rs->id_team_structure . '">' . $rs->id_team_structure . '</a>';
      $sub_array[] = $rs->nama_team;
      $sub_array[] = $rs->nama_lengkap;
      $sub_array[] = $rs->tot_detail;
      $sub_array[] = $active;
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
      'id_dealer' => dealer()->id_dealer,

    ];
    if ($recordsFiltered == true) {
      return $this->m_dms->getTeamStructureManagement($filter)->num_rows();
    } else {
      return $this->m_dms->getTeamStructureManagement($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    // send_json($data);
    $this->template($data);
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $id_team_structure    = $this->input->get('id');

    $filter['id_team_structure'] = $id_team_structure;
    $result = $this->m_dms->getTeamStructureManagement($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_dms->getTeamStructureManagementDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }

  function save()
  {
    $post       = $this->input->post();
    $id_dealer  = $this->m_admin->cari_dealer();
    $id_team_structure = $this->m_dms->get_id_team_structure();

    $insert = [
      'id_team_structure' => $id_team_structure,
      'id_dealer' => $id_dealer,
      'id_team' => $post['id_team'],
      'id_sales_coordinator' => $post['id_sales_coordinator'],
      'created_at' => waktu_full(),
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'created_by' => user()->id_user,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'id_team_structure' => $id_team_structure,
        'id_karyawan_dealer' => $pr['id_karyawan_dealer']
      ];
    }
    // $tes = ['insert' => $insert, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('dms_team_structure_management', $insert);
    $this->db->insert_batch('dms_team_structure_management_detail', $ins_detail);
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
        'link' => base_url('dealer/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $id_team_structure    = $this->input->get('id');

    $filter['id_team_structure'] = $id_team_structure;
    $result = $this->m_dms->getTeamStructureManagement($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_dms->getTeamStructureManagementDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $post       = $this->input->post();
    $id_dealer  = $this->m_admin->cari_dealer();
    $id_team_structure = $post['id_team_structure'];

    $update = [
      'id_dealer' => $id_dealer,
      'id_team' => $post['id_team'],
      'id_sales_coordinator' => $post['id_sales_coordinator'],
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'updated_at' => waktu_full(),
      'updated_by' => user()->id_user,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'id_team_structure' => $id_team_structure,
        'id_karyawan_dealer' => $pr['id_karyawan_dealer']
      ];
    }
    // $tes = ['update' => $update, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_team_structure_management', $update, ['id_team_structure' => $id_team_structure]);
    $this->db->delete('dms_team_structure_management_detail', ['id_team_structure' => $id_team_structure]);
    if (isset($ins_detail)) {
      $this->db->insert_batch('dms_team_structure_management_detail', $ins_detail);
    }
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
        'link' => base_url('dealer/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }

  public function cetak_gab()
  {
    $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $id_work_order = $this->input->get('id');

    $filter = ['id_work_order' => $id_work_order];
    $get_wo = $this->m_wo->get_sa_form($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      // $upd = ['cetak_nsc_ke'=> $row->cetak_nsc_ke+1,
      //         'cetak_nsc_at'=> $waktu,
      //         'cetak_nsc_by'=> $login_id,
      //       ];
      // $this->db->update('tr_h2_wo_dealer',$upd,['no_njb'=>$no_njb]);

      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set'] = 'cetak_gabungan';
      $data['row']    = $row;
      $data['nsc'] = $this->m_lap->detailNSC(['id_work_order' => $row->id_work_order]);
      $data['njb'] = $this->m_lap->detailNJB($row->id_work_order);

      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_nsc.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }
}
