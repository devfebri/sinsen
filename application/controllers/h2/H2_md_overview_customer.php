<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_md_overview_customer extends CI_Controller
{

  var $folder =   "h2";
  var $page   =   "h2_md_overview_customer";
  var $title  =   "Overview Customer";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('H2_md_customer_list_model');
    //===== Load Library =====		

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
    }
    ini_set('display_errors', 0);
  }

  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
      $data['isi']       = $this->page;
      $data['title']     = $this->title;
      $data['set']       = "view";
      $tgl1 = $this->input->get('tgl1');
      $tgl2 = $this->input->get('tgl2');
      $tgl3 = $this->input->get('tgl3');
      $tgl4 = $this->input->get('tgl4');
      $dealer2 = $this->input->get('dealer');
      $data['dealer'] = $this->H2_md_customer_list_model->getDataDealer();
      $data['kendaraan'] = $this->H2_md_customer_list_model->getDataKendaraan();
      $data['pekerjaan'] = $this->H2_md_customer_list_model->getDataPekerjaan();
      $profesi = $this->input->get('profesi');
      // var_dump($profesi);
      // die();
      if ($tgl1 != NULL && $tgl2 != NULL && $dealer2 != NULL) {
        $data['chart'] = true;
        $data['grafikActivePassive'] = $this->H2_md_customer_list_model->grafikActivePassive($tgl1,$tgl2,$dealer2);
        $data['frequencyOfVisit'] = $this->H2_md_customer_list_model->frequencyOfVisit($tgl1,$tgl2,$dealer2);
        $data['salesAbility'] = $this->H2_md_customer_list_model->salesAbility($tgl1,$tgl2,$dealer2);
      } else {
        $data['chart'] = false;
        $data['grafikActivePassive'] = [];
        
      }

      if ($tgl3 != NULL && $tgl4 != NULL && $dealer2!=NULL) {
        $data['chart2'] = true;
        $data['grafikActivePassiveAfter'] = $this->H2_md_customer_list_model->grafikActivePassiveAfter($tgl3,$tgl4,$dealer2);
        $data['frequencyOfVisitAfter'] = $this->H2_md_customer_list_model->frequencyOfVisitAfter($tgl3,$tgl4,$dealer2);
        $data['salesAbilityAfter'] = $this->H2_md_customer_list_model->salesAbilityAfter($tgl3,$tgl4,$dealer2);
      } else {
        $data['chart2'] = false;
        $data['grafikActivePassiveAfter'] = [];
      }

      
      $this->template($data);
  }

  public function getTipeKendaraan()
  {
    $fetch_data = $this->make_query_getTipeKendaraan();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihAHASS(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getTipeKendaraan(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getTipeKendaraan($recordsFiltered = null)
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
      return $this->m_api->fetch_getTipeKendaraan($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getTipeKendaraan($filter)->result();
    }
  }
}
