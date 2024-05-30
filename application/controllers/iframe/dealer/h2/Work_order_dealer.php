<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Work_order_dealer extends CI_Controller
{

  var $tables = "tr_h2_wo_dealer";
  var $folder = "dealer";
  var $page   = "sa_form";
  var $title  = "Work Order";

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
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h2_api', 'm_api');
    $this->load->model('m_h2_billing', 'm_bil');

    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
  }
  public function detail()
  {
    $data['isi']    = $this->page;
    $data['folder'] = $this->folder;
    $data['page']   = $this->page;
    $data['title']  = 'Detail Work Order';
    $data['mode']   = 'detail_wo';
    $data['set']    = "form";
    $id_work_order  = $this->input->get('id');

    $filter['id_work_order'] = $id_work_order;
    $filter['skip_dealer'] = true;
    $sa_form = $this->m_wo->get_sa_form($filter);
    if ($sa_form->num_rows() > 0) {
      $row                     = $data['row_wo'] = $sa_form->row();
      $data['tipe_coming']     = explode(',', $row->tipe_coming);
      $data['pkp'] = $row->pkp;
      $data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
      // send_json($data);
      iframe_template($data);
    } else {
    }
  }
}
