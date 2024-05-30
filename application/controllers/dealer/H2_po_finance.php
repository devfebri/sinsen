<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_po_finance extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h2_po_finance";
  var $title  = "Purchase Order";

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
      $data['folder'] = $this->folder;
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
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id_po . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $btn_cetak = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/cetak?id=' . $rs->id_po . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
      $btn_ubah_stat = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/ubah_status?id=' . $rs->id_po . '" class="btn btn-primary btn-xs btn-flat">Ubah Status</a>';

      if ($rs->status == 'draft') {
        $status .= "<label class='label label-info'>Draft</label>";
        if (can_access($this->page, 'can_update')) $button .= $btn_edit;
        if (can_access($this->page, 'can_approval')) $button .= $btn_ubah_stat;
      } elseif ($rs->status == 'batal') {
        $status .= "<label class='label label-danger'>Batal</label>";
      } elseif ($rs->status == 'approved') {
        $button .= $btn_cetak;
        $status .= "<label class='label label-success'>Approved</label>";
      }
      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_po . '">' . $rs->id_po . '</a>';
      $sub_array[] = $rs->tgl_po;
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->keterangan;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->total);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->tot_ppn);
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->grand_total);
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
      'search' => $this->input->post('search')['value'],
    ];
    if ($recordsFiltered == true) {
      return $this->m_fin->getPOFinance($filter)->num_rows();
    } else {
      return $this->m_fin->getPOFinance($filter)->result();
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
    $id_po    = $this->input->get('id');

    $filter['id_po'] = $id_po;
    $result = $this->m_fin->getPOFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPOFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save()
  {
    $waktu      = waktu_full();
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_po = $this->m_fin->get_id_po();
    $id_dealer    = $this->m_admin->cari_dealer();

    $insert = [
      'id_po'      => $id_po,
      'id_dealer'  => $id_dealer,
      'tgl_po'     => $tanggal,
      'status'     => 'draft',
      'id_vendor'  => $post['id_vendor'],
      'keterangan' => $post['keterangan'],
      'ada_ppn'    => $post['ada_ppn'],
      'ppn'    => $post['ppn'],
      'tot_ppn'    => $post['tot_ppn'],
      'total'    => $post['total'],
      'created_at' => $waktu,
      'created_by' => $login_id,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'id_po' => $id_po,
        'nama_barang' => $pr['nama_barang'],
        'qty' => $pr['qty'],
        'harga_satuan' => $pr['harga_satuan'],
      ];
    }
    $insert['grand_total'] = $post['grand'];
    $tes = ['insert' => $insert, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_dealer_po_finance', $insert);
    $this->db->insert_batch('tr_h2_dealer_po_finance_detail', $ins_detail);
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
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
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
    $id_po    = $this->input->get('id');

    $filter = ['id_po' => $id_po, 'status' => 'draft'];
    $result = $this->m_fin->getPOFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPOFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_edit()
  {
    $waktu      = waktu_full();
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_po = $this->m_fin->get_id_po();
    $id_dealer    = $this->m_admin->cari_dealer();

    $id_po = $post['id_po'];

    $update = [
      'id_dealer'  => $id_dealer,
      'tgl_po'     => $tanggal,
      'status'     => 'draft',
      'id_vendor'  => $post['id_vendor'],
      'keterangan' => $post['keterangan'],
      'ada_ppn'    => $post['ada_ppn'],
      'ppn'    => (int)$post['ppn'],
      'tot_ppn'    => (float)$post['tot_ppn'],
      'total'    => $post['total'],
      'updated_at' => $waktu,
      'updated_by' => $login_id,
    ];
    foreach ($post['details'] as $pr) {
      $ins_detail[] = [
        'id_po' => $id_po,
        'nama_barang' => $pr['nama_barang'],
        'qty' => $pr['qty'],
        'harga_satuan' => $pr['harga_satuan'],
      ];
    }
    $update['grand_total'] = $post['grand'];
    $tes = ['update' => $update, 'ins_detail' => $ins_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h2_dealer_po_finance', $update, ['id_po' => $id_po, 'id_dealer' => $id_dealer]);
    $this->db->delete('tr_h2_dealer_po_finance_detail', ['id_po' => $id_po]);
    $this->db->insert_batch('tr_h2_dealer_po_finance_detail', $ins_detail);
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
        'link' => base_url($this->folder . "/" . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function ubah_status()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Ubah Status ' . $this->title;
    $data['mode']  = 'ubah_status';
    $data['set']   = "form";
    $id_po    = $this->input->get('id');

    $filter['id_po'] = $id_po;
    $result = $this->m_fin->getPOFinance($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getPOFinanceDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }

  function save_status()
  {
    $waktu     = waktu_full();
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $id_dealer = $this->m_admin->cari_dealer();

    $id_po = $post['id_po'];

    $update = [
      'status' => $post['status'],
      'updated_status_at'  => $waktu,
      'updated_status_by'  => $login_id,
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h2_dealer_po_finance', $update, ['id_po' => $id_po, 'id_dealer' => $id_dealer]);
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

  public function cetak()
  {
    $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    $waktu      = waktu_full();
    $login_id   = $this->session->userdata('id_user');
    $id_po = $this->input->get('id');

    $filter = ['id_po' => $id_po];
    $get_wo = $this->m_fin->getPOFinance($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set'] = 'cetak';
      $data['row']    = $row;
      $data['detail'] = $this->m_fin->getPOFinanceDetail($filter)->result();
      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_nsc.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }
}
