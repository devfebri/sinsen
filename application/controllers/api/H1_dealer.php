<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class H1_dealer extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h1_api', 'm_api');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_penjualan', 'm_jual');
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
  }

  public function getDriverDeliveryUnit()
  {
    $fetch_data = $this->make_query_getDriverDeliveryUnit();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-toggle="tooltip" title="Pilih Data" data-dismiss=\'modal\' onClick=\'return pilihDriver(' . json_encode($rs) . ')\' class="btn btn-success btn-xs btn-flat"><i class="fa fa-check"></i></button>';
      $sub_array[] = $rs->id_karyawan_dealer;
      $sub_array[] = $rs->honda_id;
      $sub_array[] = $rs->id_flp_md;
      $sub_array[] = $rs->driver;
      $sub_array[] = $rs->no_plat;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getDriverDeliveryUnit(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getDriverDeliveryUnit($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'view',
      'search' => $this->input->post('search')['value'],
      'id_dealer' => dealer()->id_dealer,
    ];
    if (isset($_POST['po_type'])) {
      $filter['po_type'] = $_POST['po_type'];
    }
    if ($recordsFiltered == true) {
      return $this->m_api->getDriverDeliveryUnit($filter)->num_rows();
    } else {
      return $this->m_api->getDriverDeliveryUnit($filter)->result();
    }
  }

  public function getSPK()
  {
    $fetch_data = $this->make_query_getSPK();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $parse = ['no_spk' => $rs->no_spk, 'jenis_spk' => $rs->jenis_spk];
      $link        = '<button data-toggle="tooltip" title="Pilih Data" data-dismiss=\'modal\' onClick=\'return pilihSPK(' . json_encode($parse) . ')\' class="btn btn-success btn-xs btn-flat"><i class="fa fa-check"></i></button>';
      $sub_array[] = $rs->no_spk;
      $sub_array[] = $rs->tgl_spk;
      $sub_array[] = $rs->nama_konsumen;
      $sub_array[] = $rs->no_ktp;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->jenis_beli;
      $sub_array[] = mata_uang_rp($rs->tanda_jadi);
      $sub_array[] = mata_uang_rp($rs->total_bayar);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getSPK(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getSPK($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'pembayaran',
      'search' => $this->input->post('search')['value'],
    ];
    if (isset($_POST['spk_ada_tanda_jadi'])) {
      $filter['spk_ada_tanda_jadi'] = $_POST['spk_ada_tanda_jadi'];
    }
    if (isset($_POST['id_tjs_null'])) {
      $filter['id_tjs_null'] = $_POST['id_tjs_null'];
    }
    if ($recordsFiltered == true) {
      return $this->m_spk->getSPK($filter)->num_rows();
    } else {
      return $this->m_spk->getSPK($filter)->result();
    }
  }

  public function getSO()
  {
    $fetch_data = $this->make_query_getSO();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $sub_array[] = $rs->id_sales_order;
      $sub_array[] = $rs->no_spk;
      $sub_array[] = $rs->nama_konsumen;
      $sub_array[] = $rs->no_ktp;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = mata_uang_rp($rs->tanda_jadi);
      $sub_array[] = mata_uang_rp($rs->dp_stor);
      $sub_array[] = mata_uang_rp($rs->total_bayar);
      $params_parse = ['no_spk' => $rs->no_spk, 'jenis_spk' => $rs->jenis_spk];
      $link        = '<button data-toggle="tooltip" title="Pilih Data" data-dismiss=\'modal\' onClick=\'return pilihSPK(' . json_encode($params_parse) . ')\' class="btn btn-success btn-xs btn-flat"><i class="fa fa-check"></i></button>';
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getSO(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getSO($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'pembayaran',
      'search' => $this->input->post('search')['value'],
    ];
    if (isset($_POST['spk_ada_dp'])) {
      $filter['spk_ada_dp'] = $_POST['spk_ada_dp'];
    }
    if (isset($_POST['id_invoice_dp_null'])) {
      $filter['id_invoice_dp_null'] = $_POST['id_invoice_dp_null'];
    }
    if (isset($_POST['id_inv_pelunasan_null'])) {
      $filter['id_inv_pelunasan_null'] = $_POST['id_inv_pelunasan_null'];
    }
    if (isset($_POST['jenis_beli'])) {
      $filter['jenis_beli'] = $_POST['jenis_beli'];
    }
    if (isset($_POST['status_tjs_in'])) {
      $filter['status_tjs_in'] = $_POST['status_tjs_in'];
    }
    if ($recordsFiltered == true) {
      return $this->m_jual->getSO($filter)->num_rows();
    } else {
      return $this->m_jual->getSO($filter)->result();
    }
  }

  public function getRiwayatPenerimaanPembayaran()
  {
    $fetch_data = $this->make_query_getRiwayatPenerimaanPembayaran();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      // $link        = '<button data-toggle="tooltip" title="Cetak Kwitansi" onClick=\'return printing(' . json_encode($rs) . ')\' class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></button>';
      if ($rs->jenis_invoice == 'tjs') {
        $link        = '<a target="_blank" data-toggle="tooltip" title="Cetak" href="' . base_url('dealer/print_receipt/cetak_tjs?id=' . $rs->id_kwitansi) . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
      } elseif ($rs->jenis_invoice == 'pelunasan') {
        $link        = '<a target="_blank" data-toggle="tooltip" title="Cetak" href="' . base_url('dealer/print_receipt/cetak_pelunasan?id=' . $rs->id_kwitansi) . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
      } elseif ($rs->jenis_invoice == 'dp') {
        $link        = '<a target="_blank" data-toggle="tooltip" title="Cetak" href="' . base_url('dealer/print_receipt/cetak_dp?id=' . $rs->id_kwitansi) . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
      }
      $sub_array[] = $rs->id_kwitansi;
      $sub_array[] = $rs->tgl_pembayaran;
      $sub_array[] = $rs->cara_bayar;
      $sub_array[] = mata_uang_rp($rs->amount);
      $sub_array[] = mata_uang_rp($rs->nominal_lebih);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getRiwayatPenerimaanPembayaran(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getRiwayatPenerimaanPembayaran($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'pembayaran',
      'search' => $this->input->post('search')['value'],
    ];
    if (isset($_POST['no_spk'])) {
      $filter['no_spk'] = $_POST['no_spk'];
    }
    if ($recordsFiltered == true) {
      return $this->m_bayar->getDealerInvoiceReceipt($filter)->num_rows();
    } else {
      return $this->m_bayar->getDealerInvoiceReceipt($filter)->result();
    }
  }

  function getSPKDetail()
  {
    $post = $this->input->post();
    if ($post['jenis_spk'] == 'gc') {
      $result = $this->m_spk->getSPKGCDetail($post)->result();
    } elseif ($post['jenis_spk'] == 'individu') {
      $result = $this->m_spk->getSPK($post)->result();
    }
    $resp = [
      'status' => 'sukses',
      'data' => $result
    ];
    send_json($resp);
  }
  function getSPKHeaderDetail()
  {
    $post = $this->input->post();
    if ($post['jenis_spk'] == 'gc') {
      $post['no_spk_gc'] = $post['no_spk'];
      $result = $this->m_spk->getSPKGCDetail($post)->result();
      $row = $this->m_jual->getSO($post);
      if ($row->num_rows() == 0) {
        $row = $this->m_spk->getSPK($post)->row();
      } else {
        $row = $row->row();
      }


      
    } elseif ($post['jenis_spk'] == 'individu') {
      $result = $this->m_spk->getSPK($post)->result();
      $row = $this->m_jual->getSO($post);
      if ($row->num_rows() == 0) {
        $row = $this->m_spk->getSPK($post)->row();
      } else {
        $row = $row->row();
      }

      $no_spk =$post['no_spk'];
      $cek_indent = $this->db->query("select a.tanda_jadi from tr_spk a join tr_po_dealer_indent b on a.no_spk = b.id_spk where b.status ='requested' and a.no_spk = '$no_spk' and a.tgl_spk >='2022-09-01'");
    }
    $resp = [
      'status' => 'sukses',
      'row' => $row,
      'data' => $result
    ];
    if($post['jenis_spk'] == 'individu'){
      if($cek_indent->num_rows()>0){
        if($cek_indent->row()->tanda_jadi < 300000){
          $resp = array();
          $resp = [
            'pesan' => 'Nominal TJS untuk SPK Indent tidak sesuai dengan ketentuan (min Rp 300.000). Silahkan lakukan pembaruan data!'
          ];
        }
      }
    }

    send_json($resp);
  }
}
