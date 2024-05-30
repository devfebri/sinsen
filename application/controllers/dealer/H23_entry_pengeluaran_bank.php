<?php
defined('BASEPATH') or exit('No direct script access allowed');

class h23_entry_pengeluaran_bank extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h23_entry_pengeluaran_bank";
  var $title  = "Entry Pengeluaran Bank";

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
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="dealer/' . $this->page . '/edit?id=' . $rs->no_bukti . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';

      $btn_ubah_stat = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/ubah_status?id=' . $rs->no_bukti . '" class="btn btn-primary btn-xs btn-flat">Ubah Status</a>';


      if ($rs->status == 'input' || $rs->status == NULL) {
        $status .= "<label class='label label-info'>Input</label>";
        if (can_access($this->page, 'can_update')) $button .= $btn_edit;
        if (can_access($this->page, 'can_approval')) $button .= $btn_ubah_stat;
      } elseif ($rs->status == 'batal') {
        $status .= "<label class='label label-danger'>Batal</label>";
      } elseif ($rs->status == 'approved') {
        // $button .= $btn_cetak;/
        $status .= "<label class='label label-success'>Approved</label>";
      }

      $sub_array[] = '<a href="dealer/' . $this->page . '/detail?id=' . $rs->no_bukti . '">' . $rs->no_bukti . '</a>';
      $sub_array[] = date_dmy($rs->tgl_bukti);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->total);
      $sub_array[] = $rs->dibayar_kepada;
      $sub_array[] = $status;
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
      'order_column' => 'view_entry',
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      $filter['select'] = 'count';
      return $this->m_fin->getEntryPengeluaranBank($filter)->row()->count;
    } else {
      return $this->m_fin->getEntryPengeluaranBank($filter)->result();
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
    $no_bukti    = $this->input->get('id');

    $filter['no_bukti'] = $no_bukti;
    $result = $this->m_fin->getEntryPengeluaranBank($filter);
    if ($result->num_rows() > 0) {
      $row = $result->row();
      $data['row'] = $row;
      $data['generated'] = 1;
      $filter['no_voucher'] = $row->no_voucher;
      $data['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      $data['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
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
      'no_voucher' => $post['no_voucher'],
      'status' => 'approved',
    ];
    // send_json($post);
    $result['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
    $result['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
    $result['status'] = 'sukses';
    if ($return == null) {
      send_json($result);
    } else {
      return $result;
    }
  }

  function save()
  {
    $post       = $this->input->post();
    $no_bukti = $this->m_fin->get_no_bukti();
    $insert = [
      'no_bukti'   => $no_bukti,
      'tgl_bukti'  => tanggal(),
      'id_dealer'  => dealer()->id_dealer,
      'no_voucher' => $post['no_voucher'],
      'status'     => 'input',
      'created_at' => waktu_full(),
      'created_by' => user()->id_user,
    ];
    $upd_voucher = ['no_bukti' => $no_bukti];
    $total = 0;
    foreach ($post['pembayarans'] as $pr) {
      $upd_pembayarans[] = [
        'no_bukti' => $no_bukti,
        'id'       => $pr['id'],
        'tgl_cair' => $pr['tgl_cair'],
        'nominal'  => $pr['nominal']
      ];
      $total += $pr['nominal'];
    }
    $insert['total'] = $total;
    // $tes = ['insert' => $insert, 'upd_pembayarans' => $upd_pembayarans, 'upd_voucher' => $upd_voucher];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h23_entry_pengeluaran_bank', $insert);
    $this->db->update('tr_h23_pengeluaran_finance', $upd_voucher, ['no_voucher' => $post['no_voucher']]);
    $this->db->update_batch('tr_h23_pengeluaran_finance_pembayaran', $upd_pembayarans, 'id');
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

  public function ubah_status()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Ubah Status ' . $this->title;
    $data['mode']  = 'ubah_status';
    $data['set']   = "form";
    $no_bukti    = $this->input->get('id');

    $filter['no_bukti'] = $no_bukti;
    $result = $this->m_fin->getEntryPengeluaranBank($filter);
    if ($result->num_rows() > 0) {
      $row = $result->row();
      $data['row'] = $row;
      $data['generated'] = 1;
      $filter['no_voucher'] = $row->no_voucher;
      $data['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      $data['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }

  function save_status()
  {
    $waktu     = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();

    $no_bukti = $post['no_bukti'];

    $update = [
      'status' => $post['status'],
      'approved_at' => $waktu,
      'approved_by' => $login_id,
    ];

    // $tes = ['update' => $update, 'no_bukti' => $no_bukti];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h23_entry_pengeluaran_bank', $update, ['no_bukti' => $no_bukti]);
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
        'link' => base_url($this->folder . '/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been processed successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $no_bukti    = $this->input->get('id');

    $filter['no_bukti'] = $no_bukti;
    $result = $this->m_fin->getEntryPengeluaranBank($filter);
    if ($result->num_rows() > 0) {
      $row = $result->row();
      $data['row'] = $row;
      $data['generated'] = 1;
      $filter['no_voucher'] = $row->no_voucher;
      $data['details'] = $this->m_fin->getPengeluaranFinanceDetail($filter)->result();
      $data['pembayarans'] = $this->m_fin->getPengeluaranFinancePembayaran($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $post       = $this->input->post();
    $no_bukti = $post['no_bukti'];
    $update = [
      'updated_at' => waktu_full(),
      'updated_by' => user()->id_user,
    ];
    $upd_voucher = ['no_bukti' => $no_bukti];
    $total = 0;
    foreach ($post['pembayarans'] as $pr) {
      $upd_pembayarans[] = [
        'no_bukti' => $no_bukti,
        'id'       => $pr['id'],
        'tgl_cair' => $pr['tgl_cair']
      ];
      $total += $pr['nominal'];
    }
    $update['total'] = $total;
    // $tes = ['upd_pembayarans' => $upd_pembayarans, 'upd_voucher' => $upd_voucher];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h23_entry_pengeluaran_bank', $update, ['no_bukti' => $no_bukti]);
    $this->db->update('tr_h23_pengeluaran_finance', $upd_voucher, ['no_voucher' => $post['no_voucher']]);

    //Reset Detail Pembayaran
    $upd_reset = ['no_bukti' => NULL, 'tgl_cair' => NULL];
    $this->db->update('tr_h23_pengeluaran_finance_pembayaran', $upd_reset, ['no_bukti' => $no_bukti]);

    $this->db->update_batch('tr_h23_pengeluaran_finance_pembayaran', $upd_pembayarans, 'id');
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
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    send_json($rsp);
  }
}
