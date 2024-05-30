<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H23_rekap_pendapatan_harian extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h23_rekap_pendapatan_harian";
  var $title  = "Rekap Pendapatan Harian";

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
    $this->load->model('m_h2_dealer_laporan', 'm_lap');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h2_finance', 'm_fin');


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
    $data['set']   = "index";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="dealer/' . $this->page . '/edit?id=' . $rs->no_rekap . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail?id=' . $rs->no_rekap . '">' . $rs->no_rekap . '</a>';;
      $sub_array[] = date_dmy($rs->tgl_rekap);
      $sub_array[] = $rs->start_date;
      $sub_array[] = $rs->end_date;
      $sub_array[] = ucwords($rs->jenis_penerimaan);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->jumlah);
      $sub_array[] = $button;
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
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getRekapPendapatanHarian($filter)->num_rows();
    } else {
      return $this->m_fin->getRekapPendapatanHarian($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    // send_json($data);
    $this->template($data);
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail ' . $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $no_rekap    = $this->input->get('id');

    $filter['no_rekap'] = $no_rekap;
    $result = $this->m_fin->getRekapPendapatanHarian($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPrintReceipt($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }


  function generateData($return = null)
  {
    $post = $this->input->post();
    $filter = [
      'jenis_penerimaan' => $post['jenis_penerimaan'],
      'not_in_rekap' => true,
      'periode_receipt' => "'{$post['start_date']}'  AND '{$post['end_date']}'",
      'total_lebih_besar' => true,
      'no_rekap_null' => true
    ];
    $result = $this->m_fin->getPrintReceipt($filter);
    if ($result->num_rows() > 0) {
      $res = ['status' => 'sukses', 'details' => $result->result()];
    } else {
      $res = ['status' => 'error', 'pesan' => 'Data kosong !'];
    }
    if ($return == null) {
      send_json($res);
    } else {
      return $result->result();
    }
  }

  function save()
  {
    $post       = $this->input->post();
    $details = $this->generateData(true);
    $no_rekap = $this->m_fin->get_no_rekap();
    $insert = [
      'no_rekap'         => $no_rekap,
      'id_dealer'        => dealer()->id_dealer,
      'tgl_rekap'        => tanggal(),
      'jam_rekap'        => tanggal(),
      'start_date'       => $post['start_date'],
      'end_date'         => $post['end_date'],
      'jenis_penerimaan' => $post['jenis_penerimaan'],
      'created_at'       => waktu_full(),
      'created_by'       => user()->id_user,
    ];
    $jumlah = 0;
    foreach ($details as $pr) {
      $upd_detail[] = [
        'no_rekap' => $no_rekap,
        'id_receipt' => $pr->id_receipt
      ];
      $jumlah += $pr->total;
    }
    $insert['jumlah'] = $jumlah;
    // $tes = ['insert' => $insert, 'upd_detail' => $upd_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h23_rekap_pendapatan_harian', $insert);

    $this->db->where('metode_bayar', $post['jenis_penerimaan']);
    $this->db->update_batch('tr_h2_receipt_customer_metode', $upd_detail, 'id_receipt');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }

  public function cetak_gab()
  {
    $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $id_work_order = $this->input->get('id');

    $filter = ['id_work_order' => $id_work_order];
    $get_wo = $this->m_wo->get_sa_form($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      // $upd = ['cetak_nsc_ke'=> $row->cetak_nsc_ke+1,
      //         'cetak_nsc_at'=> $waktu,
      //         'cetak_nsc_by'=> $login_id,
      //       ];
      // $this->db->update('tr_h2_wo_dealer',$upd,['no_njb'=>$no_njb]);

      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set'] = 'cetak_gabungan';
      $data['row']    = $row;
      $data['nsc'] = $this->m_lap->detailNSC(['id_work_order' => $row->id_work_order]);
      $data['njb'] = $this->m_lap->detailNJB($row->id_work_order);

      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_nsc.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }
}
