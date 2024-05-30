<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class H2 extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_api', 'm_api');
    $this->load->model('m_h2_master', 'mh2m');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_master_finance', 'm_msfin');
    $this->load->model('m_h2_billing', 'm_bil');
  }

  public function riwayatServisCustomerH23()
  {
    $fetch_data = $this->make_query_riwayatServisCustomerH23();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $btn_detail_wo = '<button type="button" onClick = \'return detailWO(' . json_encode($rs) . ')\' class = "btn btn-info btn-xs">Detail WO</button>';

      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->jam_servis;
      $sub_array[] = $rs->kode_dealer_md;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->pekerjaan;
      $sub_array[] = $btn_detail_wo;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_riwayatServisCustomerH23(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_riwayatServisCustomerH23($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";
    $order        = '';
    $search       = $this->input->post('search')['value'];

    if (isset($_POST["order"])) $order     = $_POST["order"];
    if ($no_limit == 'y') $limit = '';
    $id_customer = isset($_POST['id_customer']) ? $_POST['id_customer'] : '';

    return $this->m_api->fetch_riwayatServisCustomerH23($start, $length, $search, $order, $limit, $id_customer);
  }

  function get_filtered_data_riwayatServisCustomerH23()
  {
    return $this->make_query_riwayatServisCustomerH23('y')->num_rows();
  }

  public function selectTipeKendaraan()
  {
    $search = $this->input->get('q');
    $query = [];
    if ($search != '') {
      $query  = $this->db->query("SELECT id_tipe_kendaraan,tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan LIKE '%$search%' OR tipe_ahm LIKE '%$search%'")->result();
    }
    echo json_encode(['items' => $query]);
  }

  public function selectWarnaItem()
  {
    $search = $this->input->get('q');
    $id_tipe_kendaraan = $this->input->get('tk');
    $query = [];
    if ($search != '') {
      $query  = $this->db->query("SELECT ms_warna.id_warna,warna FROM ms_item 
              JOIN ms_warna ON ms_warna.id_warna=ms_item.id_warna
              JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=ms_item.id_tipe_kendaraan
              WHERE ms_tipe_kendaraan.id_tipe_kendaraan='$id_tipe_kendaraan' AND (ms_item.id_warna LIKE '%$search%' OR warna LIKE '%$search%')")->result();
    }
    echo json_encode(['items' => $query]);
  }

  public function salesPartsDealer()
  {
    $id_dealer  = $this->m_admin->cari_dealer();
    $fetch_data = $this->make_query_partWithAllStock(null, $id_dealer);
    $this->load->model('h3_dealer_stock_model', 'dealer_stock');

    $data = array();
    if (is_array($fetch_data)) {
      $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsFiltered" => 0,
        "data"            => $data
      );
    } else {
    $id_dealer  = $this->m_admin->cari_dealer();
      foreach ($fetch_data->result() as $key => $rs) {
        $rs->jenis_order = 'Reguler';
        $sub_array = array();
        $link        = '
        <script> var prd_' . $key . ' = ' . json_encode($rs) . '</script>
        <button onClick=\'return pilihPart(prd_' . $key . ')\' class="btn btn-success btn-xs">Pilih</button>';
        // $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPart(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
        $sub_array[] = $rs->id_part;
        $sub_array[] = $rs->nama_part;
        // $sub_array[] = $rs->kelompok_vendor;
        $sub_array[] = mata_uang_rp($rs->harga_dealer_user);
        // $sub_array[] = $rs->kode_dealer_md . ' | ' . $rs->nama_dealer;
        // $params = [
        //   'id_dealer' => $id_dealer,
        //   'id_part' => $rs->id_part,
        //   'id_gudang' => $rs->id_gudang,
        //   'id_rak' => $rs->id_rak,
        // ];
        // $book_so = $this->m_api->getQtyBookSO($params);
        // $book_wo = $this->m_api->getQtyBookWO($params);
        // $book_sa = $this->m_api->getQtyBookSA($params);
        // // $book_sa = 0;
        // // $book_wo = 0;
        // // $book_so = 0;

        // $book = $this->dealer_stock->qty_book($id_dealer,$rs->id_part,$rs->id_gudang,$rs->id_rak);
        // $stock = $rs->stock - ($book);
        $stock = $this->dealer_stock->qty_avs($id_dealer, $rs->id_part, $rs->id_gudang, $rs->id_rak);
        if ($stock <= 0) $link = '';

        $sub_array[] = $rs->id_gudang;
        $sub_array[] = $rs->id_rak;
        $sub_array[] = $stock;
        $sub_array[] = $rs->status;
        $sub_array[] = $link;
        $data[]      = $sub_array;
      }
      $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsFiltered" => $this->get_filtered_data_partWithAllStock($id_dealer),
        "data"            => $data
      );
    }

    echo json_encode($output);
  }

  public function make_query_partWithAllStock($no_limit = null, $id_dealer = null, $exeptSales = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";
    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'limit' => $limit,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
      'id_tipe_kendaraan' => isset($_POST['id_tipe_kendaraan']) ? $_POST["id_tipe_kendaraan"] : '',
      'status_booking' => ["'cancel'"],
      'id_sa_form_not_null' => 1
    ];
    if ($id_dealer != null) {
      $filter['id_dealer'] = $id_dealer;
    }
    if (isset($_POST['id_part'])) {
      $filter['id_part'] = $_POST['id_part'];
    }
    if (isset($_POST['nama_part'])) {
      $filter['nama_part'] = $_POST['nama_part'];
    }
    if ($exeptSales != null) {
      return $this->m_api->fetch_partWithAllStock($filter);
    } else {
      if ($_POST['id_part'] == '' && $_POST['nama_part'] == '') {
        $result = [];
        return $result;
      } else {
        return $this->m_api->fetch_partWithAllStock($filter);
      }
    }
  }

  function get_filtered_data_partWithAllStock($id_dealer = null, $exeptSales = null)
  {
    return $this->make_query_partWithAllStock('y', $id_dealer, $exeptSales)->num_rows();
  }

  public function salesHLOPartsDealer()
  {
    $fetch_data = $this->make_query_salesHLOPartsDealer();
    $data = array();
    if (is_array($fetch_data)) {
      $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsFiltered" => 0,
        "data"            => $data
      );
    } else {
      foreach ($fetch_data->result() as $rs) {
        $rs->jenis_order   = 'HLO';
        $rs->order_to      = 0;
        $rs->order_to_name = 'MD';
        $sub_array = array();
        $link        = '<button style="margin-bottom:1px" onClick=\'return pilihPart(' . json_encode($rs) . ')\' class="btn btn-danger btn-xs">Pilih HLO</button>';
        $link        .= '<button onClick=\'return partRecordDemand(' . json_encode($rs) . ')\' class="btn btn-warning btn-xs">Record Demand</button>';
        $sub_array[] = $rs->id_part;
        $sub_array[] = $rs->nama_part;
        $sub_array[] = mata_uang_rp($rs->harga_dealer_user);
        $sub_array[] = $rs->status;
        $sub_array[] = $link;
        $data[]      = $sub_array;
      }
      $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsFiltered" => $this->get_filtered_data_salesHLOPartsDealer(),
        "data"            => $data
      );
    }

    echo json_encode($output);
  }

  public function make_query_salesHLOPartsDealer($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";
    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'limit' => $limit,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
      'id_tipe_kendaraan' => isset($_POST['id_tipe_kendaraan']) ? $_POST["id_tipe_kendaraan"] : '',
      'status_booking' => ["'cancel'"],
      'id_sa_form_not_null' => 1
    ];

    if (isset($_POST['id_part'])) {
      $filter['id_part'] = $_POST['id_part'];
    }
    if (isset($_POST['nama_part'])) {
      $filter['nama_part'] = $_POST['nama_part'];
    }
    if ($_POST['id_part'] == '' && $_POST['nama_part'] == '') {
      $result = [];
      return $result;
    } else {
      return $this->m_api->fetch_salesHLOPartsDealer($filter);
    }
  }

  function get_filtered_data_salesHLOPartsDealer($id_dealer = null, $exeptSales = null)
  {
    return $this->make_query_salesHLOPartsDealer('y', $id_dealer, $exeptSales)->num_rows();
  }

  public function salesPartsDealerLain()
  {
    $fetch_data = $this->make_query_salesPartsDealerLain();
    $data = array();
    if (is_array($fetch_data)) {
      $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsFiltered" => 0,
        "data"            => $data
      );
    } else {
      foreach ($fetch_data->result() as $rs) {
        $rs->jenis_order = 'HLO';
        $rs->order_to = $rs->id_dealer;
        $rs->order_to_name = $rs->nama_dealer;
        $sub_array = array();
        $link = '';
        if ($rs->status == 'Ada') {
          $link        = '<button style="margin-bottom:1px" onClick=\'return pilihPart(' . json_encode($rs) . ')\' class="btn btn-danger btn-xs">Pilih HLO</button>';
        }
        $link        .= '<button onClick=\'return partRecordDemand(' . json_encode($rs) . ')\' class="btn btn-warning btn-xs">Record Demand</button>';

        $sub_array[] = $rs->id_part;
        $sub_array[] = $rs->nama_part;
        $sub_array[] = $rs->harga_saat_dibeli;
        $sub_array[] = $rs->nama_dealer;
        $sub_array[] = $rs->status;
        $sub_array[] = $rs->status_part;
        $sub_array[] = $link;
        $data[]      = $sub_array;
      }
      $output = array(
        "draw"            => intval($_POST["draw"]),
        "recordsFiltered" => $this->get_filtered_data_salesPartsDealerLain(),
        "data"            => $data
      );
    }

    echo json_encode($output);
  }

  public function make_query_salesPartsDealerLain($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    // $limit        = "$length, $start";
    
    $limit        = $length;
    $offset       = $start;
    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'id_tipe_kendaraan' => isset($_POST['id_tipe_kendaraan']) ? $_POST["id_tipe_kendaraan"] : '',
      'limit' => $limit,
      'offset' => $offset,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
    ];

    if (isset($_POST['id_part'])) {
      $filter['id_part'] = $_POST['id_part'];
    }
    if (isset($_POST['nama_part'])) {
      $filter['nama_part'] = $_POST['nama_part'];
    }
    if (isset($_POST['qty_part'])) {
      $filter['qty_part'] = $_POST['qty_part'];
    }
    if ($_POST['id_part'] == '' && $_POST['nama_part'] == '') {
      $result = [];
    } else {
      if (isset($_POST['qty_part'])) {
        if ($_POST['qty_part'] == '') {
          $result = [];
        } else {
          $result =  $this->m_api->fetch_salesPartsDealerLain($filter);
        }
      } else {
        $result = [];
      }
    }
    return $result;
  }

  function get_filtered_data_salesPartsDealerLain()
  {
    return $this->make_query_salesPartsDealerLain('y')->num_rows();
  }

  public function partWithDealerStock()
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $fetch_data = $this->make_query_partWithAllStock(null, $id_dealer, true);
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPart(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_part;
      $sub_array[] = $rs->nama_part;
      $sub_array[] = $rs->kelompok_vendor;
      $sub_array[] = mata_uang_rp($rs->harga_dealer_user);
      $sub_array[] = $rs->deskripsi_gudang;
      $sub_array[] = $rs->id_rak;
      $sub_array[] = $rs->stock;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->get_filtered_data_partWithAllStock($id_dealer, true),
      "data"            => $data
    );
    echo json_encode($output);
  }

  public function kelurahan()
  {
    $fetch_data = $this->make_query_kelurahan();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihKelurahan(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_kelurahan;
      $sub_array[] = $rs->kode_pos;
      $sub_array[] = $rs->kelurahan;
      $sub_array[] = $rs->kecamatan;
      $sub_array[] = $rs->kabupaten;
      $sub_array[] = $rs->provinsi;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_kelurahan(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_kelurahan($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";
    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'limit' => $limit,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
    ];

    return $this->m_api->fetch_kelurahan($filter);
  }

  function get_filtered_data_kelurahan()
  {
    return $this->make_query_kelurahan('y')->num_rows();
  }

  public function item()
  {
    $fetch_data = $this->make_query_item();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihItem(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->id_item;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->warna;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_item(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_item($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'limit' => $limit,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
    ];

    return $this->m_api->fetch_item($filter);
  }

  function get_filtered_data_item()
  {
    return $this->make_query_item('y')->num_rows();
  }
  public function tipe_kendaraan()
  {
    $fetch_data = $this->make_query_tipe_kendaraan();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihTipeKendaraan(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_tipe_kendaraan(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_tipe_kendaraan($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'search' => $this->input->post('search')['value'],
      'limit' => $limit,
      'order' => isset($_POST['order']) ? $_POST["order"] : '',
      'not_in_tipe_vs_5nosin' => isset($_POST['not_in_tipe_vs_5nosin']) ? true : false
    ];

    return $this->m_api->fetch_tipe_kendaraan($filter);
  }

  function get_filtered_data_tipe_kendaraan()
  {
    return $this->make_query_tipe_kendaraan('y')->num_rows();
  }

  public function customerH23()
  {
    $fetch_data = $this->make_query_customerH23();
    $data = array();
    foreach ($fetch_data as $key=>$rs) {
      $sub_array = array();
      $link        = '
      <script> var allcus_' . $key . ' = ' . json_encode($rs) . '</script>
      <button data-dismiss=\'modal\' onClick=\'return pilihAllCustomer(allcus_' .$key . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_customerH23(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_customerH23($no_limit = null)
  {
    $start        = $this->db->escape_str($this->input->post('start'));
    $length       = $this->db->escape_str($this->input->post('length'));
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'limit'         => $limit,
      'order'         => isset($_POST['order']) ? $this->db->escape_str($_POST["order"])                : '',
      'nama_customer' => isset($_POST['nama_customer']) ? $this->db->escape_str($_POST['nama_customer']) : '',
      'no_hp'         => isset($_POST['no_hp']) ? $this->db->escape_str($_POST['no_hp'])                : '',
      'no_mesin'      => isset($_POST['no_mesin']) ? $this->db->escape_str($_POST['no_mesin'])          : '',
      'no_polisi'     => isset($_POST['no_polisi']) ? $this->db->escape_str($_POST['no_polisi']) : ''
    ];
    if ($filter['nama_customer'] == '' && $filter['no_hp'] == '' && $filter['no_mesin'] == '' && $filter['no_polisi'] == '') {
      $result = [];
      if ($no_limit == 'y') $result = 0;
    } else {
      $result = $this->m_api->fetch_customerH23($filter)->result();
      if ($no_limit == 'y') $result = $this->m_api->fetch_customerH23($filter)->num_rows();
    }
    return $result;
  }

  function get_filtered_data_customerH23()
  {
    return $this->make_query_customerH23('y');
  }

  public function customerH1()
  {
    $fetch_data = $this->make_query_customerH1();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihAllCustomer(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_sales_order;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_customerH1(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_customerH1($no_limit = null)
  {
    $start        = $this->db->escape_str($this->input->post('start'));
    $length       = $this->db->escape_str($this->input->post('length'));
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'limit'         => $limit,
      'order'         => isset($_POST['order']) ? $this->db->escape_str($_POST["order"])                : '',
      'nama_customer' => isset($_POST['nama_customer']) ?$this->db->escape_str($_POST['nama_customer']) : '',
      'no_hp'         => isset($_POST['no_hp']) ? $this->db->escape_str($_POST['no_hp'])                : '',
      'no_mesin'      => isset($_POST['no_mesin']) ? $this->db->escape_str($_POST['no_mesin'])          : '',
      'no_polisi'     => isset($_POST['no_polisi']) ? $this->db->escape_str($_POST['no_polisi']) : ''
    ];
    if ($filter['nama_customer'] == '' && $filter['no_hp'] == '' && $filter['no_mesin'] == '' && $filter['no_polisi'] == '') {
      $result = [];
      if ($no_limit == 'y') $result = 0;
    } else {
      $result = $this->m_api->fetch_customerH1($filter)->result();
      if ($no_limit == 'y') $result = $this->m_api->fetch_customerH1($filter)->num_rows();
    }
    return $result;
  }

  function get_filtered_data_customerH1()
  {
    return $this->make_query_customerH1('y');
  }

  public function customerBooking()
  {
    $fetch_data = $this->make_query_customerBooking();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihCustomerBooking(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_booking;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->jam_servis;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_customerBooking(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_customerBooking($no_limit = null)
  {
    $start        = $this->db->escape_str($this->input->post('start'));
    $length       = $this->db->escape_str($this->input->post('length'));
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'limit'         => $limit,
      'order'         => isset($_POST['order']) ? $this->db->escape_str($_POST["order"])                : '',
      'id_booking'    => isset($_POST['id_booking']) ? $this->db->escape_str($_POST['id_booking'])      : '',
      'nama_customer' => isset($_POST['nama_customer']) ? $this->db->escape_str($_POST['nama_customer']) : '',
      'no_hp'         => isset($_POST['no_hp']) ? $this->db->escape_str($_POST['no_hp'])                : '',
      'no_mesin'      => isset($_POST['no_mesin']) ? $this->db->escape_str($_POST['no_mesin'])          : '',
      'no_polisi'     => isset($_POST['no_polisi']) ? $this->db->escape_str($_POST['no_polisi']) : ''
    ];

    return $this->m_api->fetch_customerBooking($filter);
  }

  function get_filtered_data_customerBooking()
  {
    return $this->make_query_customerBooking('y')->num_rows();
  }

  function getCustomer()
  {
    $customer_from = $this->input->post('customer_from');
    if ($customer_from == 'h23') {
      $id_customer = $this->input->post('id_customer');
      $filter      = ['id_customer' => $id_customer];
      $customer    = $this->m_api->getCustomerH23($filter);
      if ($customer->num_rows() == 0) {
        $result = ['status' => 'error', 'pesan' => 'Data Customer tidak ditemukan !'];
      } else {
        $customer = $customer->row();
        $customer->customer_from = $customer_from;
        $result = ['status' => 'sukses', 'data' => $customer];
      }
    }
    if ($customer_from == 'h1') {
      $no_mesin = $this->input->post('no_mesin');
      // $filter   = ['no_mesin' => $no_mesin];
      // $customer = $this->m_api->getCustomerH1($filter);
      $jenis_customer_beli = $this->input->post('jenis_customer_beli');
      $filter   = ['no_mesin' => $no_mesin,
                    'jenis_customer_beli'=>$jenis_customer_beli];
      $customer = $this->m_api->getCustomerH1_v2($filter);
      if ($customer->num_rows() == 0) {
        $result = ['status' => 'error', 'pesan' => 'Data Customer tidak ditemukan !'];
      } else {
        $customer = $customer->row();
        $customer->customer_from = $customer_from;
        $result = ['status' => 'sukses', 'data' => $customer];
      }
    }
    echo json_encode($result);
  }


  public function pembawa()
  {
    $fetch_data = $this->make_query_pembawa();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPembawa(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_pembawa;
      $sub_array[] = $rs->nama;
      $sub_array[] = $rs->jenis_kelamin;
      $sub_array[] = $rs->hubungan_dengan_pemilik;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_pembawa(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_pembawa($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($no_limit == 'y') $limit = '';

    $filter = [
      'limit'        => $limit,
      'order'        => isset($_POST['order']) ? $_POST["order"]              : '',
      'id_customer'  => isset($_POST['id_customer']) ? $_POST['id_customer']  : '',
      'nama_pembawa' => isset($_POST['nama_pembawa']) ? $_POST['nama_pembawa'] : '',
      'no_hp'        => isset($_POST['no_hp']) ? $_POST['no_hp']              : ''
    ];
    if ($filter['id_customer'] == '') {
      $result = [];
      if ($no_limit == 'y') $result = 0;
    } else {
      $result = $this->m_api->fetch_pembawa($filter)->result();
      if ($no_limit == 'y') $result = $this->m_api->fetch_pembawa($filter)->num_rows();
    }
    return $result;
  }

  function get_filtered_data_pembawa()
  {
    return $this->make_query_pembawa('y');
  }

  function getPembawa()
  {
    $id_pembawa = $this->input->post('id_pembawa');
    $filter = ['id_pembawa' => $id_pembawa];
    $pembawa = $this->m_api->getPembawa($filter);
    if ($pembawa->num_rows() > 0) {
      $result = ['status' => 'sukses', 'data' => $pembawa->row()];
    } else {
      $result = ['status' => 'error', 'data' => 'Data Pembawa tidak ditemukan !'];
    }
    echo json_encode($result);
  }
  public function so_ready_nsc()
  {
    $fetch_data = $this->make_query_so_ready_nsc();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihSO(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->nomor_so;
      $sub_array[] = $rs->tanggal_so;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_so_ready_nsc(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_so_ready_nsc($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'no_hp'  => isset($_POST['no_hp']) ? $_POST['no_hp'] : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_api->fetch_so_ready_nsc($filter)->num_rows();
    } else {
      return $this->m_api->fetch_so_ready_nsc($filter)->result();
    }
  }

  function getSoNSC()
  {
    $nomor_so = $this->input->post('nomor_so');
    // echo $nomor_so;
    $filter = [
      'nomor_so' => $nomor_so,
      'id_work_order_null' => true,
      'qty_besar_dari_nol' => true
    ];
    $result = $this->m_api->getSo($filter)->row();
    $get_part = $this->m_api->getSoPart($filter);
    if ($get_part->num_rows() > 0) {
      $result->parts = $get_part->result();
    }
    $response = ['status' => 'sukses', 'data' => $result];
    send_json($response);
  }

  public function wo_proses()
  {
    $fetch_data = $this->make_query_wo_proses();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihWO(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $link .= '<button style="margin-top:1px" type="button" onClick = \'return detailWO(' . json_encode($rs) . ')\' class = "btn btn-info btn-xs">Detail WO</button>';

      $sub_array[] = $rs->id_work_order;
      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->jam_servis;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->mekanik;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_wo_proses(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_wo_proses($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'need_parts'  => isset($_POST['need_parts']) ? $_POST['need_parts'] : '',
      'status_wo'  => isset($_POST['status_wo']) ? $_POST['status_wo'] : '',
      'not_exists_nsc'  => isset($_POST['not_exists_nsc']) ? $_POST['not_exists_nsc'] : '',
      'njb_null'  => isset($_POST['njb_null']) ? $_POST['njb_null'] : '',
      'njb_not_null'  => isset($_POST['njb_not_null']) ? $_POST['njb_not_null'] : '',
      'id_dealer'  => isset($_POST['id_dealer']) ? $_POST['id_dealer'] : '',
      'wo_c2'  => isset($_POST['wo_c2']) ? $_POST['wo_c2'] : '',
      'wo_c1'  => isset($_POST['wo_c1']) ? $_POST['wo_c1'] : '',
      'pekerjaan_luar'  => isset($_POST['pekerjaan_luar']) ? 1 : '',
      'id_claim_not_in_lkh'  => isset($_POST['id_claim_not_in_lkh']) ? 1 : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_api->fetch_wo_proses($filter)->num_rows();
    } else {
      return $this->m_api->fetch_wo_proses($filter)->result();
    }
  }

  function getWoNSC()
  {
    $id_work_order = $this->input->post('id_work_order');
    $filter = ['id_work_order' => $id_work_order];
    $result = $this->m_wo->get_sa_form($filter);
    if ($result->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data Work Order tidak ditemukan !'];
      send_json($result);
    } else {
      $result = $result->row();
      $result->kd_dealer_so = $result->kode_dealer_md;
      $result->dealer_so = $result->nama_dealer;
      $jaminan = $this->m_bil->getTotBayarUangJaminan($result->id_sa_form);
      // send_json($jaminan);
      if ($jaminan != null) {
        $no_inv = [];
        $total_bayar =0;
        foreach ($jaminan as $key => $jmn) {
          $no_inv[]=$jmn->no_inv_uang_jaminan;
          $total_bayar+=$jmn->total_bayar;
        }
        $result->total_bayar = $total_bayar;
        $result->no_inv_uang_jaminan = implode('; ',$no_inv);
      } else {
        $result->total_bayar = 0;
      }
    }
    // $filter['jenis_order'] = 'reguler';
    $filter = [
      'id_work_order' => $result->id_work_order,
      // 'status_so' => 'Open',
      'qty_besar_dari_nol' => true
    ];
    $get_part = $this->m_api->getSoPart($filter);
    $set_parts = [];
    foreach ($get_part->result() as $prt) {
      $set_parts[] = $prt;
    }
    $filter = [
      'booking_by_wo' => $result->id_work_order,
      'qty_besar_dari_nol' => true
    ];
    $get_part = $this->m_api->getSoPart($filter);
    foreach ($get_part->result() as $prt) {
      $set_parts[] = $prt;
    }
    if (count($set_parts) == 0) {
      $result = ['status' => 'error', 'pesan' => 'Tidak ditemukan part untuk WO ini !'];
      send_json($result);
    } else {
      $result->parts = $set_parts;
    }
    $response = ['status' => 'sukses', 'data' => $result];
    send_json($response);
  }

  function getTipePekerjaan()
  {
    $kategori = $this->input->post('kategori');
    $id_dealer  = $this->m_admin->cari_dealer();
    $dt = $this->mh2m->get_jasa_h2($id_dealer, null, $kategori);
    if ($dt->num_rows() > 0) {
      $result = ['status' => 'sukses', 'data' => $dt->result()];
    } else {
      $result = ['status' => 'error', 'pesan' => 'Type pekerjaan dari kategori ' . $kategori . ' tidak ditemukan !'];
    }
    send_json($result);
  }

  public function getAntrian()
  {
    $fetch_data = $this->make_query_getAntrian();
    $data = array();
    foreach ($fetch_data as $key => $rs) {
      $sub_array = array();
      // $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihAntrian(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $link        = '
        <script> var atr_' . $key . ' = ' . json_encode($rs) . '</script>
        <button data-dismiss=\'modal\' onClick=\'return pilihAntrian(atr_' . $key . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_antrian;
      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->jam_servis;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = ucwords($rs->jenis_customer);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getAntrian(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getAntrian($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'id_sa_form_null'  => isset($_POST['id_sa_form_null']) ? $_POST['id_sa_form_null'] : '',
      'tgl_servis'  => isset($_POST['id_sa_form_null']) ? date('Y-m-d') : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_api->fetch_getAntrian($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getAntrian($filter)->result();
    }
  }

  public function getSaForm()
  {
    $fetch_data = $this->make_query_getSaForm();
    $data = array();
    foreach ($fetch_data as $key => $rs) {
      $sub_array = array();
      $link        = '
      <script> var saf_' . $key . ' = ' . json_encode($rs) . '</script>
      <button data-dismiss=\'modal\' onClick=\'return pilihSaForm(saf_' . $key . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_sa_form;
      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->jam_servis;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = ucwords($rs->jenis_customer);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getSaForm(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getSaForm($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'status_form'  => isset($_POST['status_form']) ? $_POST['status_form'] : '',
      // 'tgl_servis'  => isset($_POST['status_form']) ? $_POST['status_form'] == 'open' ? date('Y-m-d') : '' : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_api->fetch_getSaForm($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getSaForm($filter)->result();
    }
  }

  public function modalRequestDocument()
  {
    $fetch_data = $this->make_query_modalRequestDocument();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihRequestDoc(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_booking;
      $sub_array[] = $rs->tgl_request;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_modalRequestDocument(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_modalRequestDocument($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'not_exists_on_po'  => isset($_POST['not_exists_on_po']) ? $_POST['not_exists_on_po'] : '',
      'search' => $this->input->post('search')['value'],
      'not_exists_uang_jaminan' => 1,
      'status' => 'Open',
      'order_column' => ['id_booking ', 'LEFT(created_at,10)', 'ch23.id_customer', 'ch23.nama_customer', null]
    ];
    if ($recordsFiltered == true) {
      return $this->m_bil->getRequestDocument($filter)->num_rows();
    } else {
      return $this->m_bil->getRequestDocument($filter)->result();
    }
  }


  public function fetch_jasa()
  {
    $fetch_data = $this->make_query();
    $data       = array();
    foreach ($fetch_data->result() as $rs) {
      if (strtolower($rs->id_type) == 'ass1' || strtolower($rs->id_type) == 'ass2' || strtolower($rs->id_type) == 'ass3' || strtolower($rs->id_type) == 'ass4') {
        $id_tipe_kendaraan = $this->input->post('id_tipe_kendaraan');
        $kpb_ke = substr($rs->id_type, 3, 1);
        $cek_harga_kpb = $this->db->query("SELECT harga_jasa FROM ms_kpb_detail WHERE id_tipe_kendaraan='$id_tipe_kendaraan' AND kpb_ke=$kpb_ke");
        if ($cek_harga_kpb->num_rows() > 0) {
          $rs->harga = $cek_harga_kpb->row()->harga_jasa;
        }
      }
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihJasa(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array   = array();
      $sub_array[] = $rs->id_jasa;
      $sub_array[] = $rs->deskripsi;
      $sub_array[] = $rs->kategori;
      $sub_array[] = $rs->desk_tipe;
      $sub_array[] = mata_uang_rp($rs->harga);
      $sub_array[] = $rs->waktu;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }

    $output = array(
      "draw"            => intval($_POST["draw"]),
      "recordsFiltered" => $this->get_filtered_data(),
      "data"            => $data
    );
    echo json_encode($output);
  }
  public function make_query($no_limit = null)
  {
    $start  = $this->db->escape_str($this->input->post('start'));
    $length = $this->db->escape_str($this->input->post('length'));
    $limit  = "LIMIT $start,$length";
    $order  = '';
    $search = $this->input->post('search')['value'];

    if (isset($_POST["order"])) $order = $this->db->escape_str($_POST["order"]);
    if ($no_limit == 'y') $limit = '';

    $job_type   = isset($_POST['job_type']) ? $this->db->escape_str($_POST['job_type']) : '';
    $kategori   = isset($_POST['kategori']) ? $this->db->escape_str($_POST['kategori']) : '';
    $id_tipe_kendaraan = isset($_POST['id_tipe_kendaraan']) ? $this->db->escape_str($_POST['id_tipe_kendaraan']) : '';
    $tp_m = $this->db->query("SELECT tipe_produksi FROM ms_ptm WHERE tipe_marketing='$id_tipe_kendaraan'")->row();
    $tipe_motor = $tp_m != NULL ? $tp_m->tipe_produksi : '';
    return $this->m_api->fetch_jasa_h2_dealer_modal($start, $length, $search, $order, $limit, $tipe_motor, $kategori, $job_type);
  }

  function get_filtered_data()
  {
    return $this->make_query('y')->num_rows();
  }

  // function getPromoServis()
  // {
  //   $post = $this->input->post();
  //   $id_dealer  = $this->m_admin->cari_dealer();
  //   $filter = [
  //     'id_dealer' => $id_dealer,
  //     'id_jasa' => $post['id_jasa'],
  //     'cek_periode' => gmdate("Y-m-d", time() + 60 * 60 * 7)
  //   ];
  //   $get = $this->mh2m->get_promo_servis_jasa($filter);
  //   $result = ['status' => 'sukses'];
  //   if ($get->num_rows() > 0) {
  //     $result['data'] = $get->result();
  //   }
  //   send_json($result);
  // }

  public function modalPromoServis()
  {
    $fetch_data = $this->make_query_modalPromoServis();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPromoServis(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_promo;
      $sub_array[] = $rs->nama_promo;
      $sub_array[] = $rs->id_jasa . ' - ' . $rs->deskripsi;
      $sub_array[] = ucwords($rs->tipe_diskon);
      $sub_array[] = $rs->diskon;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_modalPromoServis(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_modalPromoServis($recordsFiltered = null)
  {
    $start        = $this->db->escape_str($this->input->post('start'));
    $length       = $this->db->escape_str($this->input->post('length'));
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';
    $id_dealer  = $this->m_admin->cari_dealer();
    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $this->db->escape_str($_POST["order"]) : '',
      'id_jasa'  => isset($_POST['id_jasa']) ? $this->db->escape_str($_POST['id_jasa']) : '',
      'cek_periode' => tanggal(),
      'search' => $this->input->post('search')['value'],
    ];

    if ($recordsFiltered == true) {
      if ($filter['id_jasa'] == '') {
        return 0;
      } else {
        return $this->mh2m->get_promo_servis_jasa($filter)->num_rows();
      }
    } else {
      if ($filter['id_jasa'] == '') {
        return [];
      } else {
        return $this->mh2m->get_promo_servis_jasa($filter)->result();
      }
    }
  }

  public function getVendorPekerjaanLuar()
  {
    $fetch_data = $this->make_query_getVendorPekerjaanLuar();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihVendor(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_vendor;
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->alamat;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getVendorPekerjaanLuar(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getVendorPekerjaanLuar($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'id_work_order'  => isset($_POST['id_work_order']) ? $_POST['id_work_order'] : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_api->fetch_getVendorPekerjaanLuar($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getVendorPekerjaanLuar($filter)->result();
    }
  }

  public function getRekDealer()
  {
    $fetch_data = $this->make_query_getRekDealer();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihRek(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->no_rek;
      $sub_array[] = $rs->nama_rek;
      $sub_array[] = $rs->jenis_rek;
      $sub_array[] = $rs->bank;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getRekDealer(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getRekDealer($recordsFiltered = null)
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
      return $this->m_api->fetch_getRekDealer($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getRekDealer($filter)->result();
    }
  }

  public function getJasaWO()
  {
    $fetch_data = $this->make_query_getJasaWO();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihJasaWO(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_jasa;
      $sub_array[] = $rs->deskripsi;
      $sub_array[] = $rs->desk_type;
      $sub_array[] = $rs->kategori;
      $sub_array[] = mata_uang_rp($rs->harga);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getJasaWO(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getJasaWO($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'id_work_order'  => isset($_POST['id_work_order']) ? $_POST['id_work_order'] : '',
      'pekerjaan_luar'  => isset($_POST['pekerjaan_luar']) ? 1 : '',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      if ($filter['id_work_order'] == '') {
        return 0;
      } else {
        return $this->m_api->fetch_getJasaWO($filter)->num_rows();
      }
    } else {
      if ($filter['id_work_order'] == '') {
        return [];
      } else {
        return $this->m_api->fetch_getJasaWO($filter)->result();
      }
    }
  }

  public function getAllParts()
  {
    $fetch_data = $this->make_query_getAllParts();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihAllPart(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_part;
      $sub_array[] = $rs->nama_part;
      $sub_array[] = mata_uang_rp($rs->harga_dealer_user);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getAllParts(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getAllParts($recordsFiltered = null)
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
      return $this->m_api->fetch_getAllParts($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getAllParts($filter)->result();
    }
  }

  public function getNJBNSC()
  {
    $fetch_data = $this->make_query_getNJBNSC();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihNJBNSC(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_referensi;
      $sub_array[] = $rs->coa;
      $sub_array[] = $rs->tipe_transaksi;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getNJBNSC(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getNJBNSC($recordsFiltered = null)
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
      return $this->m_api->fetch_getNJBNSC($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getNJBNSC($filter)->result();
    }
  }

  public function getPromoPart()
  {
    $fetch_data = $this->make_query_getPromoPart();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPromoPart(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs['id_promo'];
      $sub_array[] = $rs['nama'];
      $sub_array[] = $rs['tipe_promo'];
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getPromoPart(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getPromoPart($recordsFiltered = null)
  {
    $id_part = $this->input->post('id_part');
    $kelompok_part = $this->input->post('kelompok_part');
    $result = $this->m_api->promo_query($id_part, $kelompok_part);

    if ($recordsFiltered == true) {
      return count($result);
    } else {
      return $result;
    }
  }

  public function getKaryawanDealer()
  {
    $fetch_data = $this->make_query_getKaryawanDealer();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihKaryawanDealer(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';

      $sub_array[] = $rs->id_karyawan_dealer;
      $sub_array[] = $rs->id_flp_md;
      $sub_array[] = $rs->honda_id;
      $sub_array[] = $rs->nama_lengkap;
      // $sub_array[] = $rs->username;
      // $sub_array[] = $rs->username_sc;
      $sub_array[] = $rs->jabatan;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getKaryawanDealer(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getKaryawanDealer($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'id_dealer' => $this->m_admin->cari_dealer(),
      'search' => $this->input->post('search')['value'],
      'active' => 1
    ];
    if (isset($_POST['karyawan_can_login'])) {
      $filter['karyawan_can_login'] = true;
    }
    if ($recordsFiltered == true) {
      return $this->m_api->fetch_getKaryawanDealer($filter)->num_rows();
    } else {
      return $this->m_api->fetch_getKaryawanDealer($filter)->result();
    }
  }
}
