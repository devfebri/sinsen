<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class H2_jasa extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_jasa', 'm_jasa');
  }

  public function get_detail_work_list()
  {
    $fetch_data = $this->make_query_get_detail_work_list();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button onClick=\'return pilihDetailWorkList(' . json_encode($rs) . ')\' class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>';
      $sub_array[] = $rs->kode_detail;
      $sub_array[] = $rs->nama_detail;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_get_detail_work_list(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_get_detail_work_list($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_jasa->fetch_detail_work_list($filter)->num_rows();
    } else {
      return $this->m_jasa->fetch_detail_work_list($filter)->result();
    }
  }

  public function get_spareparts()
  {
    $fetch_data = $this->make_query_get_spareparts();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button onClick=\'return pilihSpareparts(' . json_encode($rs) . ')\' class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>';
      $sub_array[] = $rs->id_part;
      $sub_array[] = $rs->nama_part;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_get_spareparts(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_get_spareparts($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_jasa->fetch_spareparts($filter)->num_rows();
    } else {
      return $this->m_jasa->fetch_spareparts($filter)->result();
    }
  }

  public function get_jasa()
  {
    $fetch_data = $this->make_query_get_jasa();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $rs->detail_work_lists = $this->m_jasa->get_detail_work_lists_jasa($rs->id_jasa);
      $rs->spareparts = $this->m_jasa->get_spareparts_jasa($rs->id_jasa);
      $link        = '<button onClick=\'return pilihJasa(' . json_encode($rs) . ')\' class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>';
      $sub_array[] = $rs->id_jasa;
      $sub_array[] = $rs->nama_jasa;
      $sub_array[] = $rs->deskripsi;
      $sub_array[] = $rs->tot_detail_work_list;
      $sub_array[] = $rs->tot_spareparts;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_get_jasa(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_get_jasa($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_jasa->fetch_jasa($filter)->num_rows();
    } else {
      return $this->m_jasa->fetch_jasa($filter)->result();
    }
  }
}
