<?php
defined('BASEPATH') or exit('No direct script access allowed');
//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Download_cetak_dokumen extends CI_Controller
{
  var $page  = "download_cetak_dokumen";
  var $folder  = "h2";
  var $title = "Download dan Cetak Dokumen";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_md_claim', 'm_claim');
    $this->load->model('m_h2_md_laporan', 'm_lap');
    $this->load->helper('tgl_indo');


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
    $no = $this->input->post('start') + 1;
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';

      if ($rs->status == 'input') {
        $status = '<label class="label label-primary">Input</label>';
      } elseif ($rs->status == 'approved') {
        $status = '<label class="label label-success">Approved</label>';
      } elseif ($rs->status == 'rejected') {
        $status = '<label class="label label-danger">Rejected</label>';
      }
      $sub_array[] = $rs->id_rekap_claim;
      $sub_array[] = $no;
      $sub_array[] = $rs->id_rekap_claim;
      $sub_array[] = $rs->tgl_lbpc;
      $sub_array[] = $rs->no_lbpc;
      $sub_array[] = mata_uang_rp($rs->nilai_part);
      $sub_array[] = mata_uang_rp($rs->nilai_jasa);
      $sub_array[] = mata_uang_rp($rs->nilai_pokok);
      $sub_array[] = mata_uang_rp($rs->nilai_ppn);
      $sub_array[] = mata_uang_rp($rs->nilai_pokok_ppn);
      $sub_array[] = mata_uang_rp($rs->nilai_pph);
      $sub_array[] = mata_uang_rp($rs->total);
      $sub_array[] = $status;
      $data[]      = $sub_array;
      $no++;
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
      'limit'              => $limit,
      'order'              => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column'       => 'view',
      // 'periode_lbpc'    => true,
      'start_date'         => date_ymd($_POST['start_date']),
      'end_date'           => date_ymd($_POST['end_date']),
      'kelompok_pengajuan' => $_POST['kelompok_pengajuan'],
      'lbpc_not_null'      => true,
      'get_summary'        => true,
      'ceklist_ptca'       => true,
      // 'ptca_null'       => true, 
      'search'             => $this->input->post('search')['value']
    ];
    if (isset($_POST['id_dealer'])) {
      $filter['id_dealer'] = $_POST['id_dealer'];
    }
    if (isset($_POST['tgl_po_kpb'])) {
      $filter['tgl_po_kpb'] = $_POST['tgl_po_kpb'];
    }
    if (isset($_POST['status'])) {
      $filter['status'] = $_POST['status'];
    }
    if ($recordsFiltered == true) {
      return $this->m_claim->getRekapClaimWarranty($filter)->num_rows();
    } else {
      return $this->m_claim->getRekapClaimWarranty($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Insert ' . $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    $this->template($data);
  }

  public function setDataPrint()
  {
    $filter = [
      'order_column'  => 'view',
      'periode_lbpc'  => true,
      'lbpc_not_null' => true,
      'get_summary'   => true,
      'ceklist_ptca'  => true,
      'no_claim_in'   => arr_in_sql($this->input->post('no_claim'))
    ];
    $result = $this->m_claim->getRekapClaimWarranty($filter)->result();
    $response = ['status' => 'sukses', 'data' => $result];
    send_json($response);
  }

  function printing()
  {
    $params = $this->input->get();
    $params['periode_pengajuan'] = true;
    // send_json($params);
    if ($params['dokumen'] === 'wos') {
      $this->download_xls_wos($params);
    } elseif ($params['dokumen'] === 'lbpc_ahm') {
      $this->printing_lbpc($params);
    } elseif ($params['dokumen'] === 'rekap_lbpc_internal') {
      $this->printing_rekap_lbpc_internal($params);
    } elseif ($params['dokumen'] === 'ganti_claim_internal') {
      $this->printing_ganti_claim_internal($params);
    }
  }

  function printing_lbpc($params)
  {
    $params['lbpc_not_null'] = true;
    if ($params['set'] == 'selected') {
      $params['no_claim_in'] = arr_in_sql($this->input->get('data'));
    }
    // send_json($params);
    $result = $this->m_lap->getCetakLBPC($params);

    $this->load->library('mpdf_l');
    $mpdf                           = $this->mpdf_l->load();
    $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
    $mpdf->charset_in               = 'UTF-8';
    $mpdf->autoLangToFont           = true;
    $data['set'] = $_GET['dokumen'];
    if ($params['dokumen'] == 'lbpc_ahm') {
      $data['detail'] = $result;
    }
    // send_json($data);
    $html = $this->load->view('h2/download_cetak_dokumen_cetak', $data, true);
    // render the view into HTML
    $mpdf->WriteHTML($html);
    // write the HTML into the mpdf
    $output = 'cetak_dokumen.pdf';
    $mpdf->Output("$output", 'I');
    // }
  }
  function printing_rekap_lbpc_internal($params)
  {
    $params['lbpc_not_null'] = true;
    $params['group_by_dealer'] = true;
    if ($params['set'] == 'selected') {
      $params['no_claim_in'] = arr_in_sql($this->input->get('data'));
    }
    // send_json($params);
    $result = $this->m_lap->getRekapLBCInternal($params);
    $data['set']   = $_GET['dokumen'];
    $data['tipe']  = $_GET['tipe'];
    $data['title'] = 'Cetak Rekap LBPC Internal';
    if ($params['dokumen'] == 'rekap_lbpc_internal') {
      $data['detail'] = $result['detail'];
      $data['grand'] = $result['grand'];
    }
    if ($params['tipe'] == 'print') {
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;
      $html = $this->load->view('h2/download_cetak_dokumen_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_dokumen.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      $this->load->view('h2/download_cetak_dokumen_cetak', $data);
    }
  }

  function download_xls_wos($params)
  {
    $writer = WriterFactory::create(Type::XLSX);
    //$writer = WriterFactory::create(Type::CSV); // for CSV files
    //$writer = WriterFactory::create(Type::ODS); // for ODS files

    //stream to browser
    $file_date = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $file_name = 'E20-' . $file_date . '-LBPC 077.xls';
    $writer->openToBrowser($file_name);
    $params['select']                = 'select';
    $params['join_rekap_claim_part'] = true;
    $data = $this->m_claim->getRekapClaimWarranty($params)->result();
    // send_json($data);

    $header = [
      'No',
      'No. Registrasi Claim',
      'Kategori Claim',
      'Kelompok Pengajuan',
      'Kode AHASS',
      'Kode Main Dealer',
      'No Rangka',
      'Tgl Pembelian',
      'Tgl Kerusakan',
      'Kilometer Kerusakan',
      'Customer',
      'Alamat',
      'Kelurahan',
      'Kecamatan',
      'Kodepos',
      'Kode Kota',
      'Nomor Telepon',
      'Tgl Perbaikan',
      'Tgl Selesai Perbaikan',
      'Kilometer Perbaikan',
      'Uraian/ Penjelasan tentang gejala kerusakan',
      'Symptom Code',
      'Rank',
      'Kode Kerusakan',
      'Nomor Part',
      'Jumlah Claim Dealer',
      'Tipe Penggantian',
      'HET',
      'Harga Labour',
      'Status Part',
      'LKH No.',
      'Hotline Order No.',
      'Tgl LKH',
      'Tgl HO',
      'No. Polisi',
      'Kode Produksi Part',
      'Hasil analisa AHASS',
      'Tgl MD terima data klaim dari Dealer'
    ];
    $writer->addRow($header); // add a row at a time
    $set_arr = [];
    $no = 1;
    foreach ($data as $dt) {
      // send_json($dt);
      $res_ = [];
      foreach ($dt as $val) {
        $res_[] = $val;
      }
      array_unshift($res_, $no);
      $set_arr[] = $res_;
      $no++;
    }
    // send_json($set_arr);
    $writer->addRows($set_arr); // add multiple rows at a time

    $writer->close();
  }

  function printing_ganti_claim_internal($params)
  {
    $params['lbpc_not_null'] = true;
    if ($params['set'] == 'selected') {
      $params['no_claim_in'] = arr_in_sql($this->input->get('data'));
    }
    // send_json($params);
    $params['group_by_dealer'] = true;
    $result = $this->m_lap->getGantiClaimInternal($params);
    $data['set']   = $_GET['dokumen'];
    $data['tipe']  = $_GET['tipe'];
    $data['title'] = 'Cetak Daftar Penggantian Claim ';
    if ($params['dokumen'] == 'ganti_claim_internal') {
      $data['detail'] = $result;
    }
    // send_json($data);
    if ($params['tipe'] == 'print') {
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;
      $html = $this->load->view('h2/download_cetak_dokumen_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_dokumen.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      $this->load->view('h2/download_cetak_dokumen_cetak', $data);
    }
  }
}
