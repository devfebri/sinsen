<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surat_jalan_pekerjaan_luar extends CI_Controller
{

  var $tables = "tr_h2_wo_dealer";
  var $folder = "dealer";
  var $page   = "surat_jalan_pekerjaan_luar";
  var $title  = "Surat Jalan Pekerjaan Luar";

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
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h2_api', 'm_api');
    //===== Load Library =====
    $this->load->library('upload');
    $this->load->library('form_validation');
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
        if ($data['mode'] == 'insert_wo') $page = 'sa_form';
        if ($data['mode'] == 'detail_wo') $page = 'sa_form';
        if ($data['mode'] == 'update_wo') $page = 'sa_form';
      }
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']  = "index";
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
      $btn_print = '<a href="dealer/surat_jalan_pekerjaan_luar/cetak?id=' . $rs->id_surat_jalan . '" class="btn btn-success btn-xs btn-flat">Cetak</a>';

      if (can_access($this->page, 'can_print'))  $button .= $btn_print;

      $sub_array[] = '<a href="dealer/surat_jalan_pekerjaan_luar/detail?id=' . $rs->id_surat_jalan . '">' . $rs->id_surat_jalan . '</a>';

      $sub_array[] = date_dmy($rs->tgl_surat_jalan);
      $sub_array[] = $rs->id_work_order;
      $sub_array[] = $rs->id_vendor;
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->dibawa_oleh;
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
      'order_column' => 'view',
      'search' => $this->input->post('search')['value'],
      'status_wo' => 'open',
    ];
    if ($recordsFiltered == true) {
      return $this->m_wo->get_sj_keluar($filter)->num_rows();
    } else {
      return $this->m_wo->get_sj_keluar($filter)->result();
    }
  }


  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail ' . $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $id_surat_jalan    = $this->input->get('id');

    $filter['id_surat_jalan'] = $id_surat_jalan;
    $result = $this->m_wo->get_sj_keluar($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $filter = ['id_surat_jalan' => $id_surat_jalan];
      $data['pekerjaans'] = $this->m_wo->get_sj_keluar_pekerjaans($filter)->result();
      $data['parts_related'] = $this->m_wo->get_sj_keluar_parts_related($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/surat_jalan_pekerjaan_luar'>";
    }
  }

  public function cetak()
  {
    $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $id_surat_jalan = $this->input->get('id');

    $filter = ['id_surat_jalan' => $id_surat_jalan];
    $get_data = $this->m_wo->get_sj_keluar($filter);
    if ($get_data->num_rows() > 0) {
      $row = $data['row'] = $get_data->row();
      // $upd = [
      //   'cetak_sa_form_ke' => $row->cetak_sa_form_ke + 1,
      //   'cetak_sa_form_at' => $waktu,
      //   'cetak_sa_form_by' => $login_id,
      // ];
      // $this->db->update('tr_h2_sa_form', $upd, ['id_sa_form' => $id_sa_form]);
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set'] = 'print_sj';
      $data['pekerjaans'] = $this->m_wo->get_sj_keluar_pekerjaans($filter)->result();
      $data['parts_related'] = $this->m_wo->get_sj_keluar_parts_related($filter)->result();
      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf
      $output = 'cetak_sa_form.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/surat_jalan_pekerjaan_luar'>";
    }
  }

  public function create()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Surat Jalan Pekerjaan Luar';
    $data['mode']  = 'create';
    $data['set']   = "form";
    $this->template($data);
  }

  function save_surat_jalan()
  {
    $waktu          = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl            = date("Y-m-d");
    $login_id       = $this->session->userdata('id_user');
    $post  = $this->input->post();

    $id_surat_jalan = $this->m_h2->get_id_surat_jalan();

    $ins = [
      'id_surat_jalan'  => $id_surat_jalan,
      'id_work_order'   => $post['id_work_order'],
      'id_vendor'       => $post['id_vendor'],
      'tgl_surat_jalan' => $tgl,
      'alasan'          => $this->input->post('alasan'),
      'dibawa_oleh'     => $this->input->post('dibawa_oleh'),
      'created_at'      => waktu_full(),
      'created_by'      => $login_id
    ];

    foreach ($post['pekerjaans'] as $pk) {
      if ($pk['harga'] < $pk['harga_dari_vendor']) {
        $msg = 'Harga ID Jasa :' . $pk['id_jasa'] . 'dari vendor melebihi harga jasa. Silahkan lakukan penyesuaian harga !';
        $rsp = [
          'status' => 'error',
          'pesan' => $msg
        ];
        send_json($rsp);
      }
      // if ($pk['harga'] > $pk['harga_dari_vendor']) {
      //   $msg = 'Harga ID Jasa :' . $pk['id_jasa'] . 'dari vendor lebih kecil dari harga jasa. Silahkan lakukan penyesuaian harga !';
      //   $rsp = [
      //     'status' => 'error',
      //     'pesan' => $msg
      //   ];
      //   send_json($rsp);
      // }
      $upd_pekerjaan[] = [
        'id_jasa' => $pk['id_jasa'],
        'harga_dari_vendor' => $pk['harga_dari_vendor'],
        'id_surat_jalan' => $id_surat_jalan
      ];
    }

    if (isset($post['parts_related'])) {
      foreach ($post['parts_related'] as $pk) {
        $ins_parts_related[] = [
          'id_part' => $pk['id_part'],
          'qty' => $pk['qty'],
          'id_surat_jalan' => $id_surat_jalan
        ];
      }
    }

    // $tes = [
    //   'ins' => $ins,
    //   'upd_pekerjaan' => isset($upd_pekerjaan) ? $upd_pekerjaan : '',
    //   'ins_parts_related' => isset($ins_parts_related) ? $ins_parts_related : ''
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    if (isset($ins)) {
      $this->db->insert('tr_h2_wo_dealer_surat_jalan_keluar', $ins);
    }

    if (isset($upd_pekerjaan)) {
      $this->db->where(['id_work_order' => $post['id_work_order']]);
      $this->db->update_batch('tr_h2_wo_dealer_pekerjaan', $upd_pekerjaan, 'id_jasa');
    }
    if (isset($ins_parts_related)) {
      $this->db->insert_batch('tr_h2_wo_dealer_surat_jalan_keluar_part_related', $ins_parts_related);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/surat_jalan_pekerjaan_luar')
      ];
      $_SESSION['pesan']   = "Data has been saved succesfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
