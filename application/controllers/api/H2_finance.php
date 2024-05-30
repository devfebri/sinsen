<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class H2_finance extends CI_Controller
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
    $this->load->model('m_h2_finance', 'm_fin');
    $this->load->model('m_h2_billing', 'm_bil');
    $this->load->model('m_master_finance', 'm_msfin');
  }

  public function getCOADealer()
  {
    $fetch_data = $this->make_query_getCOADealer();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihCOADealer(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->kode_coa;
      $sub_array[] = $rs->coa;
      $sub_array[] = $rs->tipe_transaksi;
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getCOADealer(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getCOADealer($recordsFiltered = null)
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
      return $this->m_msfin->fetch_getCOADealer($filter)->num_rows();
    } else {
      return $this->m_msfin->fetch_getCOADealer($filter)->result();
    }
  }

  public function getVendorPO()
  {
    $fetch_data = $this->make_query_getVendorPO();
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
      "recordsFiltered" =>     $this->make_query_getVendorPO(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getVendorPO($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'search' => $this->input->post('search')['value'],
      'aktif' => 1
    ];
    if ($recordsFiltered == true) {
      return $this->m_msfin->getVendorPO($filter)->num_rows();
    } else {
      return $this->m_msfin->getVendorPO($filter)->result();
    }
  }

  public function getBarangLuar()
  {
    $fetch_data = $this->make_query_getBarangLuar();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihBarangLuar(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_barang;
      $sub_array[] = $rs->nama_barang;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->harga_satuan);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getBarangLuar(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getBarangLuar($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'search' => $this->input->post('search')['value'],
      'aktif' => 1
    ];
    if ($recordsFiltered == true) {
      return $this->m_msfin->getBarangLuar($filter)->num_rows();
    } else {
      return $this->m_msfin->getBarangLuar($filter)->result();
    }
  }

  public function getPOFinance()
  {
    $fetch_data = $this->make_query_getPOFinance();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihPO(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->id_po;
      $sub_array[] = $rs->tgl_po;
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->total);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getPOFinance(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getPOFinance($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'     => $limit,
      'order'     => isset($_POST['order']) ? $_POST["order"]        : '',
      'id_vendor' => isset($_POST['id_vendor']) ? $_POST["id_vendor"] : '',
      'status' => isset($_POST['status']) ? $_POST["status"] : '',
      'search'    => $this->input->post('search')['value'],
      'order_column' => ['id_po', 'tgl_po', 'nama_vendor', 'total', null],
      'aktif'     => 1
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getPOFinance($filter)->num_rows();
    } else {
      return $this->m_fin->getPOFinance($filter)->result();
    }
  }

  public function getRefPenerimaan()
  {
    $data = [];
    $post = $this->input->post();
    $filter = [
      'jenis_penerimaan'  => $post['jenis_penerimaan'],
      'total_lebih_besar' => true,
      'no_rekap_null'     => true
    ];
    $wo_so = $this->m_fin->getPrintReceipt($filter)->result();
    foreach ($wo_so as $rs) {
      $res = [
        'id_referensi' => $rs->id_referensi,
        'tanggal'      => $rs->tgl_receipt,
        'jumlah'       => $rs->total,
        'from'         => 'wo_dan_so_part',
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['jumlah'] = 'Rp. ' . mata_uang_rp($rs->total);
      $data[] = $res;
    }

    $result = [
      'status' => 'sukses',
      'data' => $data
    ];
    echo json_encode($result);
  }

  public function make_query_getRefPenerimaan($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'            => $limit,
      'order'            => isset($_POST['order']) ? $_POST["order"]                      : '',
      'jenis_penerimaan' => isset($_POST['jenis_penerimaan']) ? $_POST["jenis_penerimaan"] : '',
      'search'           => $this->input->post('search')['value'],
      'dibayar' => ">0",
      'not_exist_penerimaan' => isset($_POST['not_exist_penerimaan']) ? $_POST["not_exist_penerimaan"] : '',
      'order_column'     => 'ref_penerimaan',
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getRefPenerimaan($filter)->num_rows();
    } else {
      return $this->m_fin->getRefPenerimaan($filter)->result();
    }
  }
  public function getRefPenerimaanBank()
  {
    $data = [];
    // $filter = [
    //   'sisa' => '>0',
    //   'id_tagihan_not_null' => 1
    // ];
    // $po_finance = $this->m_fin->getPOFinance($filter)->result();
    // foreach ($po_finance as $rs) {
    //   $res = [
    //     'no_transaksi' => $rs->id_po,
    //     'tgl_transaksi' => $rs->tgl_po,
    //     'saldo' => $rs->sisa,
    //     'referensi' => 'po_finance'
    //   ];
    //   $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
    //   $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
    //   $data[] = $res;
    // }
    $filter = [
      'jenis_penerimaan' => 'transfer',
      'group_by_tgl' => true
    ];

    $penerimaan = $this->m_fin->getRekapPendapatanHarian($filter)->result();
    foreach ($penerimaan as $rs) {
      $res = [
        'no_transaksi' => $rs->tgl_rekap,
        'tgl_transaksi' => $rs->tgl_rekap,
        'saldo' => $rs->sisa,
        'referensi' => 'rekap',
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
      $data[] = $res;
    }

    $filter = [
      'jenis_pengeluaran' => 'kas',
      'group_by_tgl' => true
    ];

    $pengeluaran = $this->m_fin->getPengeluaranFinance($filter)->result();
    foreach ($pengeluaran as $rs) {
      $res = [
        'no_transaksi' => $rs->tgl_entry,
        'tgl_transaksi' => $rs->tgl_entry,
        'saldo' => $rs->sisa,
        'referensi' => 'pengeluaran_kas',
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
      $data[] = $res;
    }

    $result = [
      'status' => 'sukses',
      'data' => $data
    ];
    echo json_encode($result);
  }

  public function getRefPengeluaran()
  {
    $data = [];

    $filter = [
      'sisa' => '>0',
      'id_tagihan_not_null' => 1
    ];
    $po_finance = $this->m_fin->getPOFinance($filter)->result();
    foreach ($po_finance as $rs) {
      $res = [
        'referensi' => 'PO Finance',
        'no_transaksi' => $rs->id_po,
        'tgl_transaksi' => $rs->tgl_po,
        'saldo' => $rs->sisa,
        'from' => 'po_finance'
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
      $data[] = $res;
    }

    $filter = [
      'jenis_penerimaan' => 'kas',
      'cek_sisa' => true,
      'sisa_lebih_besar' => true,
      // 'group_by_tgl' => true
    ];
    $penerimaan = $this->m_fin->getPenerimaanFinanceDetail($filter)->result();
    foreach ($penerimaan as $rs) {
      $res = [
        'referensi' => 'Penerimaan',
        'no_transaksi' => $rs->id_referensi,
        'tgl_transaksi' => $rs->tgl_entry,
        'saldo' => $rs->sisa,
        'from' => 'penerimaan',
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
      $data[] = $res;
    }

    $filter = [
      'jenis_penerimaan' => 'cash',
      'cek_sisa' => true,
      'sisa_lebih_besar' => true,
    ];
    $rekap_pendapatan = $this->m_fin->getRekapPendapatanHarian($filter)->result();
    foreach ($rekap_pendapatan as $rs) {
      $res = [
        'referensi' => 'Pendapatan',
        'no_transaksi' => $rs->no_rekap,
        'tgl_transaksi' => $rs->tgl_rekap,
        'saldo' => $rs->sisa,
        'from' => 'rekap_pendapatan',
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
      $data[] = $res;
    }

    $filter = [
      'jenis_penerimaan' => 'cash',
      'select' => 'sisa',
      'sisa_lebih_besar' => ['operator' => '>', 'value' => 0],
    ];
    $uang_jaminan = $this->m_bil->get_uang_jaminan($filter)->result();
    foreach ($uang_jaminan as $rs) {
      $res = [
        'referensi' => 'Uang Jaminan',
        'no_transaksi' => $rs->no_inv_uang_jaminan,
        'tgl_transaksi' => $rs->tgl_request2,
        'saldo' => $rs->sisa,
        'from' => 'uang_jaminan',
      ];
      $res['aksi'] = '<button data-dismiss=\'modal\' onClick=\'return pilihRef(' . json_encode($res) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $res['saldo'] = 'Rp. ' . mata_uang_rp($rs->sisa);
      $data[] = $res;
    }

    $result = [
      'status' => 'sukses',
      'data' => $data
    ];
    echo json_encode($result);
  }

  public function getPengeluaranFinance()
  {
    $fetch_data = $this->make_query_getPengeluaranFinance();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $link        = '<button data-dismiss=\'modal\' onClick=\'return pilihVoucher(' . json_encode($rs) . ')\' class="btn btn-success btn-xs">Pilih</button>';
      $sub_array[] = $rs->no_voucher;
      $sub_array[] = $rs->tgl_entry;
      $sub_array[] = $rs->tipe_customer;
      $sub_array[] = $rs->dibayar_kepada;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->tot_dibayar);
      $sub_array[] = $link;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_getPengeluaranFinance(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_getPengeluaranFinance($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'     => $limit,
      'order'     => isset($_POST['order']) ? $_POST["order"]        : '',
      'jenis_pengeluaran' => isset($_POST['jenis_pengeluaran']) ? $_POST["jenis_pengeluaran"] : '',
      'no_bukti_null' => isset($_POST['no_bukti_null']) ? $_POST["no_bukti_null"] : '',
      'status' => isset($_POST['status']) ? $_POST["status"] : '',
      'search'    => $this->input->post('search')['value'],
      'order_column' => 'modalPengeluaranFinance'
    ];
    if ($recordsFiltered == true) {
      $filter['select'] = 'count';
      return $this->m_fin->getPengeluaranFinance($filter)->row()->count;
    } else {
      return $this->m_fin->getPengeluaranFinance($filter)->result();
    }
  }
}
