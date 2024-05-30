<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Performance_ahass extends CI_Controller
{
  var $page   = "performance_ahass";
  var $folder = "h2";
  var $title  = "Performance AHASS";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_md_ahass_network', 'm_network');
    $this->load->model('m_h2_work_order', 'm_wo');
    //===== Load Library =====
    $this->load->library('upload');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false' or $sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $data['folder']   = $this->folder;
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
    $data['set']   = "view";
    $this->template($data);
  }
  public function fetch()
  {
    $fetch_data = $this->make_query_fetch();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $btn_detail = "<a class='btn btn-primary btn-xs btn-flat' href=\"" . base_url('h2/' . $this->page . '/detail_performance?id=' . $rs->no_mesin . '&id_d=' . $rs->id_dealer) . "\"><i class='fa fa-eye'></i></a>";
      // $button = $btn_detail;
      $sub_array[] = $rs->kode_dealer_md;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->tahun;
      $sub_array[] = $rs->bulan;
      $sub_array[] = $rs->tot_kunjungan;
      $sub_array[] = mata_uang_rp($rs->pendapatan_kpb);
      $sub_array[] = mata_uang_rp($rs->pendapatan_pl_pr_or);

      $filter = [
        'no_mesin' => $rs->no_mesin,
        'id_dealer' => $rs->id_dealer,
        'sum_total' => true,
        // 'get_only_grand' => true
      ];
      $filter['kelompok_part_not_in'] = "'OLI','OIL'";
      $tot_part = $this->m_bil->getNSCParts($filter)->row()->sum_total;
      $sub_array[] = mata_uang_rp($tot_part);

      $filter = [
        'no_mesin' => $rs->no_mesin,
        'id_dealer' => $rs->id_dealer,
        'sum_total' => true,
        'kelompok_part_in' => "'OLI','OIL'",
        // 'get_only_grand' => true
      ];
      $tot_oli = $this->m_bil->getNSCParts($filter)->row()->sum_total;
      $sub_array[] = mata_uang_rp($tot_oli);
      $sub_array[] = mata_uang_rp($rs->qty_ass);
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_fetch(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_fetch($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'view',
      'select' => ['select_performance_ahass'],
      'group_by_no_mesin_id_dealer' => true,
      'filter_created_wo' => true,
      'status_wo' => ['closed'],
      'start' => $this->input->post('start_date'),
      'end' => $this->input->post('end_date'),
      'search' => $this->input->post('search')['value']
    ];
    if (isset($_POST['id_dealer'])) {
      if ($_POST['id_dealer'] != '') {
        $filter['id_dealer'] = $_POST['id_dealer'];
      }
    }
    if (isset($_POST['tgl_po_kpb'])) {
      $filter['tgl_po_kpb'] = $_POST['tgl_po_kpb'];
    }
    if (isset($_POST['status'])) {
      $filter['status'] = $_POST['status'];
    }
    if ($recordsFiltered == true) {
      return $this->m_wo->get_sa_form($filter)->num_rows();
    } else {
      return $this->m_wo->get_sa_form($filter)->result();
    }
  }

  public function detail()
  {
    $id = $this->input->get('id');
    $filter = ['id_po_kpb' => $id];
    $row = $this->m_claim->getpoKPB($filter);
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Detail ' . $this->title;
      $data['mode']  = 'detail';
      $data['set']   = "form";
      $data['row'] = $row->row();
      $data['details'] = $this->m_claim->getPOKPBDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }

  function download_xls()
  {
    $params = json_decode($_GET['params']);

    $writer = WriterFactory::create(Type::XLSX);
    //stream to browser
    $file_date = gmdate("Y-m-d-H-i-s", time() + 60 * 60 * 7);
    $file_name = 'Performance_AHASS-' . $file_date . '.xls';
    $writer->openToBrowser($file_name);
    if ($params->id_dealer != '') {
      $filter['id_dealer'] = $params->id_dealer;
    }
    $filter['select']    = ['select_performance_ahass'];
    $filter['status_wo'] = ['closed'];
    $filter['group_by_no_mesin_id_dealer'] = true;
    $filter['filter_created_wo'] = true;
    $filter['start'] = $params->start_date;
    $filter['end'] = $params->end_date;
    $data = $this->m_wo->get_sa_form($filter)->result();
    $header = [
      'No.',
      'Kode AHASS',
      'Nama AHASS',
      'No. Mesin',
      'Tahun',
      'Bulan',
      'Kunjungan Konsumen',
      'Pendapatan KPB(Rp)',
      'Pendapatan PL/PR/OR(Rp)',
      'Sparepart(Rp)',
      'Oli(Rp)',
      'Total ASS'
    ];
    $writer->addRow($header); // add a row at a time
    $no = 1;
    // send_json($data);
    foreach ($data as $dt) {
      $filter = [
        'no_mesin' => $dt->no_mesin,
        'id_dealer' => $dt->id_dealer,
        'sum_total' => true,
        // 'get_only_grand' => true
      ];
      $filter['kelompok_part_not_in'] = "'OLI','OIL'";
      $tot_part = $this->m_bil->getNSCParts($filter)->row()->sum_total;

      $filter = [
        'no_mesin' => $dt->no_mesin,
        'id_dealer' => $dt->id_dealer,
        'sum_total' => true,
        'kelompok_part_in' => "'OLI','OIL'",
        // 'get_only_grand' => true
      ];
      $tot_oli = $this->m_bil->getNSCParts($filter)->row()->sum_total;

      $new[]  = [
        $no,
        $dt->kode_dealer_md,
        $dt->nama_dealer,
        $dt->no_mesin,
        $dt->tahun,
        $dt->bulan,
        $dt->tot_kunjungan,
        ROUND($dt->pendapatan_kpb),
        ROUND($dt->pendapatan_pl_pr_or),
        ROUND($tot_part),
        ROUND($tot_oli),
        $dt->qty_ass,
      ];
      $no++;
    }
    // send_json($new);
    $writer->addRows($new); // add multiple rows at a time

    $writer->close();
  }
}
