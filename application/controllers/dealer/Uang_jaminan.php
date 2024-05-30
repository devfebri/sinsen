<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uang_jaminan extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "uang_jaminan";
  var $title  = "Uang Jaminan Invoice";

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
    $this->load->model('m_h2_billing', 'm_bil');
    $this->load->model('notifikasi_model', 'notifikasi');


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
        if ($data['mode'] == 'generate_wo') {
          $page = 'sa_form';
        }
        if ($data['mode'] == 'detail_wo') {
          $page = 'sa_form';
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
    // $data['res']    = $this->m_bil->get_uang_jaminan()->result();
    // send_json($data);
    $this->template($data);
  }
  public function history()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "history";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $button = '';
      $btn_print = '<a href="dealer/uang_jaminan/cetak?id=' . $rs->no_inv_uang_jaminan . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
      $btn_edit = '<a href="dealer/uang_jaminan/edit?id=' . $rs->no_inv_uang_jaminan . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-pencil"></i></a>';
      if (can_access($this->page, 'can_update')) {
        if ($rs->cetak_ke == 0 && $rs->sisa_bayar > 0) {
          $button .= $btn_edit;
        }
      }
      if (can_access($this->page, 'can_print')) $button .= $btn_print;

      $sub_array[] = '<a href="dealer/uang_jaminan/detail?id=' . $rs->no_inv_uang_jaminan . '">' . $rs->no_inv_uang_jaminan . '</a>';;
      $sub_array[] =  date_dmy($rs->tgl_invoice);
      $sub_array[] = $rs->id_booking;
      $sub_array[] = $rs->tgl_request;
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = mata_uang_rp($rs->total_bayar);
      $sub_array[] = mata_uang_rp($rs->sisa_bayar);
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
      'sisa' => $this->input->post('sisa')
    ];

    if ($recordsFiltered == true) {
      return $this->m_bil->get_uang_jaminan($filter)->num_rows();
    } else {
      return $this->m_bil->get_uang_jaminan($filter)->result();
    }
  }

  public function create()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'create';
    $data['set']   = "form";
    $id_dealer     = $this->m_admin->cari_dealer();
    $this->template($data);
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail Invoice Uang Jaminan';
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $no_inv_uang_jaminan = $this->input->get('id');

    $filter = ['no_inv_uang_jaminan' => $no_inv_uang_jaminan];
    $get_inv = $this->m_bil->get_uang_jaminan($filter);

    if ($get_inv->num_rows() > 0) {
      $data['row'] = $get_inv->row();
      $data['pembayarans'] = $this->m_bil->get_uang_jaminan_metode($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/uang_jaminan'>";
    }
  }

  function save_uang_jaminan()
  {
    $waktu        = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tgl_hari_ini = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id     = $this->session->userdata('id_user');
    $post         = $this->input->post();
    $id_dealer    = $this->m_admin->cari_dealer();
    $id_booking        = $post['id_booking'];
    $no_inv_uang_jaminan = $this->get_no_inv_uang_jaminan();

    $ins = [
      'no_inv_uang_jaminan' => $no_inv_uang_jaminan,
      'id_dealer'           => $id_dealer,
      'id_booking'          => $id_booking,
      'tgl_invoice'         => $tgl_hari_ini,
      'total_bayar'         => $post['total_bayar'],
      'sisa_bayar'          => $post['grand_total'] - $post['total_bayar'],
      'created_at'          => $waktu,
      'created_by'          => $login_id,
    ];

    foreach ($post['parts'] as $prt) {
      $upd_req_parts[] = [
        'id_part' => $prt['id_part'],
        'uang_muka' => $prt['uang_muka'],
        'persen_uang_muka' => $prt['persen_uang_muka'],
      ];
    }

    foreach ($post['pembayarans'] as $pr) {
      $ins_bayar[] = [
        'no_inv_uang_jaminan' => $no_inv_uang_jaminan,
        'no_rekening' => isset($pr['no_rekening']) ? $pr['no_rekening'] : null,
        'id_bank' => isset($pr['id_bank']) ? $pr['id_bank'] : null,
        'nominal' => $pr['nominal'],
        'tanggal_transaksi' => $pr['tanggal_transaksi'],
        'metode_bayar' => $pr['metode_bayar'],
      ];
    }

    $tes = [
      'insert' => $ins,
      'upd_req_parts' => $upd_req_parts,
      'ins_bayar' => $ins_bayar,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_uang_jaminan', $ins);
    $this->db->insert_batch('tr_h2_uang_jaminan_metode', $ins_bayar);
    $this->db->where('id_booking', $id_booking);
    $this->db->update_batch('tr_h3_dealer_request_document_parts', $upd_req_parts, 'id_part');

    $this->db->update('tr_h3_dealer_request_document', array('status'=>'Process DP'), array('id_booking'=>$id_booking));

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
        'link' => base_url('dealer/uang_jaminan')
      ];
      $_SESSION['pesan'] = "Pembuatan uang jaminan berhasil";
      $_SESSION['tipe']  = "success";

      $ntf = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_uang_jaminan')->get()->row();
      $this->notifikasi->insert([
        'id_notif_kat' => $ntf->id_notif_kat,
        'judul'        => $ntf->nama_kategori,
        'pesan'        => "Request document dengan ID Booking : $id_booking, sudah dilakukan pembayaran uang muka. Invoice : $no_inv_uang_jaminan",
        'link'         => "dealer/h3_dealer_request_document/detail?k=$id_booking",
        'id_referensi' => $id_booking,
        'id_dealer'    => $this->m_admin->cari_dealer(),
        'show_popup'   => false,
      ]);

      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function get_no_inv_uang_jaminan()
  {
    $th        = date('y');
    $bln       = date('m');
    $tgl       = date('Y-m-d');
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT no_inv_uang_jaminan FROM tr_h2_uang_jaminan
			WHERE id_dealer='$id_dealer'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->no_inv_uang_jaminan, -4);
      $new_kode   = 'UJ/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_uang_jaminan', ['no_inv_uang_jaminan' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = 'UJ/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'UJ/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getUangJaminan()
  {
    $post = $this->input->post();
    $id_booking = $post['book']['id_booking'];
    $filter = ['id_booking' => $id_booking];
    if ($post['mode'] == 'create') {
      $get_data = $this->m_bil->getRequestDocument($filter);
    }
    if ($post['mode'] == 'detail' || $post['mode'] == 'edit') {
      $get_data = $this->m_bil->get_uang_jaminan($filter);
    }
    if ($get_data->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data tidak ditemukan !'];
      send_json($result);
    } else {
      $result_data        = $get_data->row();
      $result_data->parts = $this->m_bil->getRequestDocumentParts($filter)->result();
      $result             = ['status' => 'sukses', 'data' => $result_data];
    }
    send_json($result);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit Invoice Uang Jaminan';
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $no_inv_uang_jaminan = $this->input->get('id');

    $filter = [
      'no_inv_uang_jaminan' => $no_inv_uang_jaminan,
      // 'cetak_ke' => 0
    ];
    $get_inv = $this->m_bil->get_uang_jaminan($filter);

    if ($get_inv->num_rows() > 0) {
      $data['row'] = $get_inv->row();
      $data['pembayarans'] = $this->m_bil->get_uang_jaminan_metode($filter)->result();
      if (isset($_GET['cek'])) {
        send_json($data);
      }
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/uang_jaminan'>";
    }
  }

  function save_edit()
  {
    $waktu               = waktu_full();
    $tgl_hari_ini        = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id            = $this->session->userdata('id_user');
    $post                = $this->input->post();
    $id_dealer           = $this->m_admin->cari_dealer();
    $id_booking          = $post['id_booking'];
    $no_inv_uang_jaminan = $post['no_inv_uang_jaminan'];

    $update = [
      'total_bayar' => $post['total_bayar'],
      'sisa_bayar'  => $post['grand_total'] - $post['total_bayar'],
      'updated_at'  => $waktu,
      'updated_by'  => $login_id,
    ];

    foreach ($post['parts'] as $prt) {
      $upd_req_parts[] = [
        'id_part' => $prt['id_part'],
        'uang_muka' => $prt['uang_muka'],
        'persen_uang_muka' => $prt['persen_uang_muka'],
      ];
    }

    foreach ($post['pembayarans'] as $pr) {
      $ins_bayar[] = [
        'no_inv_uang_jaminan' => $no_inv_uang_jaminan,
        'no_rekening' => isset($pr['no_rekening']) ? $pr['no_rekening'] : null,
        'id_bank' => isset($pr['id_bank']) ? $pr['id_bank'] : null,
        'nominal' => $pr['nominal'],
        'tanggal_transaksi' => $pr['tanggal_transaksi'],
        'metode_bayar' => $pr['metode_bayar'],
      ];
    }

    // $tes = [
    //   'update' => $update,
    //   'upd_req_parts' => $upd_req_parts
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $cond = ['no_inv_uang_jaminan' => $no_inv_uang_jaminan];
    $this->db->update('tr_h2_uang_jaminan', $update, $cond);
    $this->db->where('id_booking', $id_booking);
    $this->db->update_batch('tr_h3_dealer_request_document_parts', $upd_req_parts, 'id_part');

    $this->db->delete('tr_h2_uang_jaminan_metode', $cond);
    $this->db->insert_batch('tr_h2_uang_jaminan_metode', $ins_bayar);

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
        'link' => base_url('dealer/uang_jaminan')
      ];
      $_SESSION['pesan'] = "Edit uang jaminan berhasil";
      $_SESSION['tipe']  = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function cetak()
  {
    // $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    // $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $no_inv_uang_jaminan = $this->input->get('id');

    $filter = ['no_inv_uang_jaminan' => $no_inv_uang_jaminan];
    $get_jaminan = $this->m_bil->get_uang_jaminan($filter);

    if ($get_jaminan->num_rows() > 0) {
      $row = $data['row'] = $get_jaminan->row();
      $upd = [
        'cetak_ke' => $row->cetak_ke + 1,
        'cetak_at' => waktu_full(),
        'cetak_by' => $login_id,
      ];
      $this->db->update('tr_h2_uang_jaminan', $upd, $filter);

      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set']   = 'cetak';
      $title         = 'cetak_uang_jaminan';
      $data['title'] = $title;

      $filter = ['id_booking' => $row->id_booking];
      $data['detail'] = $this->m_bil->get_uang_jaminan_detail($filter, $row);
      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf

      $output = $title . '.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
    }
  }

  public function cetak_revisi()
  {
    // $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    // $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id   = $this->session->userdata('id_user');
    $no_inv_uang_jaminan = $this->input->get('id');

    $data['nama_user'] = $this->db->select('nama_lengkap')
                          ->from('ms_karyawan_dealer mk')
                          ->join('ms_user mu', 'mu.id_karyawan_dealer=mk.id_karyawan_dealer')
                          ->where('mu.id_user',$login_id)
                          ->get()->row();

    $filter = ['no_inv_uang_jaminan' => $no_inv_uang_jaminan];
    $get_jaminan = $this->m_bil->get_uang_jaminan($filter);

    if ($get_jaminan->num_rows() > 0) {
      $row = $data['row'] = $get_jaminan->row();
      $upd = [
        'cetak_ke' => $row->cetak_ke + 1,
        'cetak_at' => waktu_full(),
        'cetak_by' => $login_id,
      ];
      $this->db->update('tr_h2_uang_jaminan', $upd, $filter);

      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set']   = 'cetak';
      $title         = 'cetak_uang_jaminan';
      $data['title'] = $title;

      $filter = ['id_booking' => $row->id_booking];
      $data['detail'] = $this->m_bil->get_uang_jaminan_detail($filter, $row);
      $data['detail_revisi'] = $this->m_bil->get_uang_jaminan_detail_revisi($filter, $row);
      $data['detail_reject'] = $this->m_bil->getRequestDocumentPartsReject($filter, $row);
      // var_dump($data['detail_revisi']);
      // die();
      // send_json($data);
      $html = $this->load->view('dealer/' . $this->page . '_revisi_cetak', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf

      $output = $title . '.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
    }
  }
}
