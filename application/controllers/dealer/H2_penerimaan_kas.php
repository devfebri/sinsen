<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_penerimaan_kas extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h2_penerimaan_kas";
  var $title  = "Penerimaan Kas";

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
      if (isset($data['mode'])) {
        if ($data['mode'] == 'detail_wo') {
          $page = 'sa_form';
        }
        if ($data['mode'] == 'detail_njb') {
          $page = 'njb';
        }
        if ($data['mode'] == 'detail_nsc') {
          $page = 'nsc';
        }
      }
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
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="dealer/' . $this->page . '/edit?id=' . $rs->no_receipt_kas . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      if (can_access($this->page, 'can_update')) $button = $btn_edit;
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail?id=' . $rs->no_receipt_kas . '">' . $rs->no_receipt_kas . '</a>';;
      $sub_array[] = date_dmy($rs->tgl_entry);
      $sub_array[] = $rs->kode_coa;
      $sub_array[] = $rs->coa;
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->tot_dibayar);
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
      'jenis_penerimaan' => $this->input->post('jenis_penerimaan'),
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getPenerimaanFinance($filter)->num_rows();
    } else {
      return $this->m_fin->getPenerimaanFinance($filter)->result();
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
    $no_receipt_kas    = $this->input->get('id');

    $filter['no_receipt_kas'] = $no_receipt_kas;
    $result = $this->m_fin->getPenerimaanFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPenerimaanFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/h2_penerimaan_kas'>";
    }
  }

  function save_kas()
  {
    $waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_dealer  = $this->m_admin->cari_dealer();
    $no_receipt_kas = $this->m_fin->get_no_receipt_kas();

    $insert = [
      'no_receipt_kas' => $no_receipt_kas,
      'id_dealer' => $id_dealer,
      'tgl_entry' => $tanggal,
      'kode_coa' => $post['kode_coa'],
      'jenis_penerimaan' => 'kas',
      'created_at' => $waktu,
      'created_by' => $login_id,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'no_receipt_kas' => $no_receipt_kas,
        'kode_coa' => $pr['kode_coa'],
        'id_referensi' => $pr['id_referensi'],
        'dibayar' => $pr['dibayar'],
        'keterangan' => $pr['keterangan'],
      ];
      $filter['id_referensi'] = $pr['id_referensi'];
      $cek_ref = $this->m_fin->getPrintReceipt($filter);
      if ($cek_ref->num_rows() > 0) {
        $ref = $cek_ref->row();
        $upd_ref[] = [
          'no_rekap'   => $no_receipt_kas,
          'rekap_from' => 'penerimaan_kas',
          'id_receipt' => $ref->id_receipt,
        ];
      }
    }
    // $tes = ['insert' => $insert, 'ins_detail' => $ins_detail, 'upd_ref' => isset($upd_ref) ? $upd_ref : NULL];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h23_penerimaan_finance', $insert);
    $this->db->insert_batch('tr_h23_penerimaan_finance_detail', $ins_detail);
    if (isset($upd_ref)) {
      $this->db->where('metode_bayar', 'Cash');
      $this->db->update_batch('tr_h2_receipt_customer_metode', $upd_ref, 'id_receipt');
    }
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
        'link' => base_url('dealer/h2_penerimaan_kas')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $no_receipt_kas    = $this->input->get('id');

    $filter['no_receipt_kas'] = $no_receipt_kas;
    $result = $this->m_fin->getPenerimaanFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPenerimaanFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/h2_penerimaan_kas'>";
    }
  }

  function save_edit()
  {
    $waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_dealer  = $this->m_admin->cari_dealer();
    $no_receipt_kas = $post['no_receipt_kas'];

    $update = [
      'kode_coa' => $post['kode_coa'],
      'updated_at' => $waktu,
      'updated_by' => $login_id,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'no_receipt_kas' => $no_receipt_kas,
        'kode_coa' => $pr['kode_coa'],
        'id_referensi' => $pr['id_referensi'],
        'dibayar' => $pr['dibayar'],
        'keterangan' => $pr['keterangan'],
      ];
    }
    // $tes = ['update' => $update, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h23_penerimaan_finance', $update, ['no_receipt_kas' => $no_receipt_kas]);
    $this->db->delete('tr_h23_penerimaan_finance_detail', ['no_receipt_kas' => $no_receipt_kas]);
    if (isset($ins_detail)) {
      $this->db->insert_batch('tr_h23_penerimaan_finance_detail', $ins_detail);
    }
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
        'link' => base_url('dealer/h2_penerimaan_kas')
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
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/h2_penerimaan_kas'>";
    }
  }
}
