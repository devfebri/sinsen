<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H1_list_ar extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h1_list_ar";
  var $title  = "List AR";

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
    $this->load->model('m_h1_dealer_lap_finance', 'm_fin');
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');


    //===== Load Library =====
    $this->load->library('upload');
    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $page = $this->page;
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['folder'] = $this->folder;
    $data['set']   = "index";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $sub_array[] = $rs->id_sales_order;
      $sub_array[] = $rs->tgl_sales_order;
      $sub_array[] = $rs->no_invoice;
      $sub_array[] = $rs->nama_konsumen;
      $sub_array[] = $rs->finance_company;
      // $sub_array[] = '<a target="_blank" href="dealer/' . $this->page . '/detail_nsc?id=' . $rs->no_nsc . '">' . $rs->no_nsc . '</a>';
      $nominal_penerimaan = $this->m_bayar->cek_tot_penerimaan_spk($rs->no_spk)->row()->total;
      $sisa_piutang = $rs->nilai_invoice - $nominal_penerimaan;
      $keterangan_pembayaran = $this->m_fin->get_keterangan_pembayaran($rs->no_spk);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->nilai_invoice);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $sisa_piutang);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $nominal_penerimaan);
      $sub_array[] =  $keterangan_pembayaran;
      $sub_array[] = $rs->keterangan_pembayaran;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST['order'] : '',
      'belum_lunas' => true,
      'search' => $this->input->post('search')['value'],
      'id_sales_order' => $this->input->post('id_sales_order'),
      'finance_company' => $this->input->post('finance_company'),
      'no_invoice' => $this->input->post('no_invoice'),
      'nama_konsumen' => $this->input->post('nama_konsumen'),
      'id_dealer' => dealer()->id_dealer,
      'order_column' => 'list_ar'
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getLaporanAR($filter)->num_rows();
    } else {
      return $this->m_fin->getLaporanAR($filter)->result();
    }
  }

  function ar()
  {
    $filter = [
      'id_dealer' => dealer()->id_dealer,
      'belum_lunas' => true,
      // 'limit' => "LIMIT 0,10"
    ];
    $result = $this->m_fin->getLaporanAR($filter)->result();
    foreach ($result as $rs) {
      $nominal_penerimaan = $this->m_bayar->cek_tot_penerimaan_spk($rs->no_spk)->row()->total;
      $sisa_piutang = $rs->nilai_invoice - $nominal_penerimaan;

      $re_[] = [
        'id_sales_order' => $rs->id_sales_order,
        'nilai_invoice' => $rs->nilai_invoice,
        'nominal_penerimaan' => $nominal_penerimaan,
        'sisa_piutang' => $sisa_piutang,
      ];
    }
    send_json($re_);
  }
  function read_csv()
  {
    //Open the file.
    $fileHandle = fopen("assets/inv_h1_d/tr_h1_dealer_invoice_receipt.csv", "r");

    //Loop through the CSV rows.
    $no = 1;
    $x = 0;
    while (($row = fgetcsv($fileHandle, 0, ";")) !== FALSE) {
      // if ($no == 1) continue;
      $id_kwitansi = $row[0];
      $cek_inv = $this->db->query("SELECT id_kwitansi FROM tr_h1_dealer_invoice_receipt WHERE id_kwitansi='$id_kwitansi'");
      if ($cek_inv->num_rows() == 0) {
        $insert[] = [
          'id_kwitansi' => $id_kwitansi,
          'id_invoice' => $row[1],
          'no_spk' => $row[2],
          'jenis_invoice' => $row[3],
          'id_dealer' => $row[4],
          'tgl_pembayaran' => $row[5],
          'cara_bayar' => $row[6],
          'amount' => $row[7],
          'note' => $row[8],
          'created_at' => $row[9],
          'created_by' => $row[10],
        ];
        $x++;
      }
      $no++;
    }
    $this->db->insert_batch('tr_h1_dealer_invoice_receipt', $insert);
    echo $x;
  }
  function read_csv_detail()
  {
    //Open the file.
    $fileHandle = fopen("assets/inv_h1_d/tr_h1_dealer_invoice_receipt_pembayaran.csv", "r");

    //Loop through the CSV rows.
    $no = 0;
    while (($row = fgetcsv($fileHandle, 0, ";")) !== FALSE) {
      $id_kwitansi = $row[0];
      $insert[] = [
        'id_kwitansi'       => $id_kwitansi,
        'metode_penerimaan' => $row[1],
        'nominal'           => $row[2],
        'no_bg_cek'         => $row[3],
        'id_bank'           => $row[4],
        'tgl_terima'        => $row[5],
      ];
      $no++;
    }
    // send_json($insert);
    $this->db->insert_batch('tr_h1_dealer_invoice_receipt_pembayaran', $insert);
    echo $no;
  }
  function cek_amount_pembayaran()
  {
    $get = $this->db->query("SELECT id_kwitansi FROM tr_h1_dealer_invoice_receipt WHERE amount=0 OR amount='' LIMIT 10000");
    $no = 0;
    foreach ($get->result() as $rs) {
      $amount = $this->db->query("SELECT 
                                  IFNULL(SUM(nominal),0) AS nominal 
                                  FROM tr_h1_dealer_invoice_receipt_pembayaran 
                                  WHERE id_kwitansi='$rs->id_kwitansi'")->row()->nominal;
      $up = [
        'id_kwitansi' => $rs->id_kwitansi,
        'amount' => $amount,
      ];
      // var_dump($up);
      // echo '<br>';
      $upd[] = $up;
      $no++;
    }
    $this->db->update_batch('tr_h1_dealer_invoice_receipt', $upd, 'id_kwitansi');
    echo $no;
  }

  function set_close_dp()
  {
    $get = $this->db->query("SELECT id_spk,total_bayar,LEFT(created_at,10) created_at 
            FROm tr_invoice_dp dp 
            WHERE (dp.status='input' OR dp.status IS NULL)
            AND LEFT(created_at,10) BETWEEN '2019-12-01' AND '2019-12-31'
            -- LIMIT 100
          ");
    foreach ($get->result() as $rs) {
      $filter_sisa = [
        'id_spk' => $rs->id_spk,
        'jenis' => 'dp'
      ];
      $dibayar = $this->m_bayar->sisa_pelunasan($filter_sisa);
      if ($dibayar == $rs->total_bayar) {
        $up = [
          'status' => 'close',
          'id_spk' => $rs->id_spk
        ];
        $upd[] = $up;
        var_dump($up);
      }
    }
    // if (isset($upd)) {
    //   $this->db->update_batch('tr_invoice_dp', $upd, 'no_spk');
    //   echo $this->db->affected_rows();
    // }
  }
}
