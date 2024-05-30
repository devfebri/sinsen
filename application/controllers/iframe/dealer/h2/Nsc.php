<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nsc extends CI_Controller
{

  var $tables = "tr_h2_wo_dealer";
  var $folder = "dealer";
  var $page   = "nsc";
  var $title  = "NSC";

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
    $this->load->model('m_h23_nsc', 'm_nsc');

    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
  }
  public function detail()
  {
    $data['isi']    = $this->page;
    $data['folder'] = $this->folder;
    $data['page']   = $this->page;
    $data['title']  = 'Detail NSC';
    $data['mode']  = 'detail_nsc';
    $data['set']   = "form";
    $no_nsc = $this->input->get('id');

    $filter = ['no_nsc' => $no_nsc];
    $get_nsc = $this->m_bil->getNSC($filter);
    // send_json($get_nsc->row());
    if ($get_nsc->num_rows() > 0) {
      $nsc = $get_nsc->row();
      $filter = ['id_work_order' => $nsc->id_referensi];
      $wo = $this->m_wo->get_sa_form($filter);
      if ($wo->num_rows() > 0) {
        $wo = $wo->row();
        $nsc->tgl_servis         = $wo->tgl_servis;
        $nsc->id_karyawan_dealer = $wo->id_karyawan_dealer;
        $nsc->nama_lengkap       = $wo->nama_lengkap;
        $nsc->kd_dealer_so       = $wo->kode_dealer_md;
        $nsc->dealer_so          = $wo->nama_dealer;
        $nsc->tipe_ahm           = $wo->tipe_ahm;
        $nsc->no_polisi          = $wo->no_polisi;
      } else {
        $so = $this->m_nsc->getSOH3($nsc->id_referensi);
        $nsc->dealer_so       = $so->nama_dealer;
        $nsc->kd_dealer_so       = $so->kode_dealer_md;
        $nsc->nama_lengkap       = $so->nama_lengkap;
      }
      $filter = ['no_nsc' => $nsc->no_nsc];
      $nsc->parts = $this->m_bil->getNSCParts($filter)->result();
      $data['row'] = $nsc;
      $data['pkp'] = $nsc->pkp;
      $data['tampil_ppn'] = $nsc->tampil_ppn;
      $data['tampil_ppn'] = 0;
      iframe_template($data);
    } else {
    }
  }
}
