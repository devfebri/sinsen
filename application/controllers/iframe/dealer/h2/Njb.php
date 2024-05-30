<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Njb extends CI_Controller
{

  var $tables = "tr_h2_wo_dealer";
  var $folder = "dealer";
  var $page   = "njb";
  var $title  = "NJB";

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
    $data['title']  = 'Detail NJB';
    $data['mode']  = 'detail_njb';
    $data['set']   = "form";
    $no_njb = $this->input->get('id');
    $filter = ['no_njb' => $no_njb];
    $get_wo = $this->m_wo->get_sa_form($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      $data['pkp'] = $row->pkp_njb;
      $data['pkp'] = 0;
      iframe_template($data);
    } else {
    }
  }
}
