<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_tagihan_lain extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h2_tagihan_lain";
  var $title  = "Tagihan Lain-lain";

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
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id_tagihan . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $btn_ubah_stat = '<a style="margin-top:2px; margin-right:1px;" href="' . $this->folder . '/' . $this->page . '/ubah_status?id=' . $rs->id_tagihan . '" class="btn btn-primary btn-xs btn-flat">Ubah Status</a>';

      if ($rs->status == 'draft') {
        $status .= "<label class='label label-info'>Draft</label>";
        if (can_access($this->page, 'can_update')) $button .= $btn_edit;
        if (can_access($this->page, 'can_approval')) $button .= $btn_ubah_stat;
      } elseif ($rs->status == 'batal') {
        $status .= "<label class='label label-danger'>Batal</label>";
      } elseif ($rs->status == 'approved') {
        $status .= "<label class='label label-success'>Approved</label>";
      }
      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_tagihan . '">' . $rs->id_tagihan . '</a>';
      $sub_array[] = $rs->tgl_tagihan;
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = 'Rp. ' . mata_uang_rp($rs->total);
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
      return $this->m_fin->getTagihanLain($filter)->num_rows();
    } else {
      return $this->m_fin->getTagihanLain($filter)->result();
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
    $id_tagihan    = $this->input->get('id');

    $filter['id_tagihan'] = $id_tagihan;
    $result = $this->m_fin->getTagihanLain($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getTagihanLainDetail($filter)->result();
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
    $waktu      = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal      = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $post       = $this->input->post();
    $id_tagihan = $this->m_fin->get_id_tagihan();
    $id_dealer    = $this->m_admin->cari_dealer();

    $insert = [
      'id_tagihan'   => $id_tagihan,
      'id_dealer'   => $id_dealer,
      'tgl_tagihan'   => $post['tgl_tagihan'],
      'status' => 'draft',
      'tipe_customer' => $post['tipe_customer'],
      'id_vendor' => $post['id_vendor'],
      'created_at'  => $waktu,
      'created_by'  => $login_id,
    ];
    $total = 0;
    foreach ($post['details'] as $pr) {
      $dpp = ROUND($pr['tot_po'] / getPPN(1.1));
      $tot_ppn = $pr['tot_po'] * getPPN(0.1);
      $tot_pph = $pr['tot_po'] * ($pr['tipe_pph'] / 100);
      $total += $pr['tot_po_tagihan'];
      $upd_detail[] = [
        'id_po' => $pr['id_po'],
        'kode_coa' => $pr['kode_coa'],
        'id_tagihan' => $id_tagihan,
        'no_kwitansi' => $pr['no_kwitansi'],
        'tgl_kwitansi' => $pr['tgl_kwitansi'],
        'no_bast' => $pr['no_bast'],
        'tgl_bast' => $pr['tgl_bast'],
        'due_date' => $pr['due_date'],
        'ppn' => $pr['ppn'],
        'tipe_pph' => $pr['tipe_pph'],
        'dpp' => $dpp,
        'tot_ppn' => $tot_ppn,
        'tot_pph' => $tot_pph,
        'tot_po_tagihan' => $pr['tot_po_tagihan'],
      ];
    }
    $insert['total'] = (int) $total;
    // $tes = ['insert' => $insert, 'upd_detail' => $upd_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_dealer_tagihan_lain', $insert);
    $this->db->update_batch('tr_h2_dealer_po_finance', $upd_detail, 'id_po');
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
    $id_tagihan    = $this->input->get('id');

    $filter['id_tagihan'] = $id_tagihan;
    $result = $this->m_fin->getTagihanLain($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getTagihanLainDetail($filter)->result();
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
    $waktu     = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $id_dealer = $this->m_admin->cari_dealer();

    $id_tagihan = $post['id_tagihan'];

    $update = [
      'tgl_tagihan'   => $post['tgl_tagihan'],
      'tipe_customer' => $post['tipe_customer'],
      'id_vendor' => $post['id_vendor'],
      'updated_at'  => $waktu,
      'updated_by'  => $login_id,
    ];

    $total = 0;
    foreach ($post['details'] as $pr) {
      $dpp = ROUND($pr['tot_po'] / getPPN(1.1));
      $tot_ppn = $pr['tot_po'] * getPPN(0.1);
      $tot_pph = $pr['tot_po'] * ($pr['tipe_pph'] / 100);
      $total += $pr['tot_po_tagihan'];

      $upd_detail[] = [
        'id_po' => $pr['id_po'],
        'kode_coa' => $pr['kode_coa'],
        'id_tagihan' => $id_tagihan,
        'no_kwitansi' => $pr['no_kwitansi'],
        'tgl_kwitansi' => $pr['tgl_kwitansi'],
        'no_bast' => $pr['no_bast'],
        'tgl_bast' => $pr['tgl_bast'],
        'due_date' => $pr['due_date'],
        'ppn' => $pr['ppn'],
        'tipe_pph' => $pr['tipe_pph'],
        'dpp' => $dpp,
        'tot_ppn' => $tot_ppn,
        'tot_pph' => $tot_pph,
        'tot_po_tagihan' => $pr['tot_po_tagihan'],
      ];
    }
    $update['total'] = $total;

    $reset_detail = [
      'kode_coa' => NULL,
      'id_tagihan' => NULL,
      'no_kwitansi' => NULL,
      'tgl_kwitansi' => NULL,
      'no_bast' => NULL,
      'tgl_bast' => NULL,
      'due_date' => NULL,
      'ppn' => NULL,
      'tipe_pph' => NULL,
      'dpp' => NULL,
      'tot_ppn' => NULL,
      'tot_pph' => NULL,
      'tot_po_tagihan' => NULL,
    ];
    // $tes = ['update' => $update, 'upd_detail' => $upd_detail];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h2_dealer_tagihan_lain', $update, ['id_tagihan' => $id_tagihan, 'id_dealer' => $id_dealer]);
    $this->db->update('tr_h2_dealer_po_finance', $reset_detail, ['id_tagihan' => $id_tagihan]);
    $this->db->update_batch('tr_h2_dealer_po_finance', $upd_detail, 'id_po');

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
    $id_tagihan    = $this->input->get('id');

    $filter['id_tagihan'] = $id_tagihan;
    $result = $this->m_fin->getTagihanLain($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $data['details'] = $this->m_fin->getTagihanLainDetail($filter)->result();
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
    $waktu     = gmdate("Y-m-d H: i: s", time() + 60 * 60 * 7);
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $id_dealer = $this->m_admin->cari_dealer();

    $id_tagihan = $post['id_tagihan'];

    $update = [
      'status' => $post['status'],
      'updated_status_at'  => $waktu,
      'updated_status_by'  => $login_id,
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h2_dealer_tagihan_lain', $update, ['id_tagihan' => $id_tagihan, 'id_dealer' => $id_dealer]);
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

  // public function cetak()
  // {
  //   $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
  //   $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
  //   $login_id   = $this->session->userdata('id_user');
  //   $id_po = $this->input->get('id');

  //   $filter = ['id_po' => $id_po];
  //   $get_wo = $this->m_fin->getPOFinance($filter);

  //   if ($get_wo->num_rows() > 0) {
  //     $row = $data['row'] = $get_wo->row();
  //     $this->load->library('mpdf_l');
  //     $mpdf                           = $this->mpdf_l->load();
  //     $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
  //     $mpdf->charset_in               = 'UTF-8';
  //     $mpdf->autoLangToFont           = true;

  //     $data['set'] = 'cetak';
  //     $data['row']    = $row;
  //     $data['detail'] = $this->m_fin->getPOFinanceDetail($filter)->result();
  //     // send_json($data);
  //     $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
  //     // render the view into HTML
  //     $mpdf->WriteHTML($html);
  //     // write the HTML into the mpdf
  //     $output = 'cetak_nsc.pdf';
  //     $mpdf->Output("$output", 'I');
  //   } else {
  //     echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
  //   }
  // }
}
