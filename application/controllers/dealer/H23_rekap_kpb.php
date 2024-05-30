<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H23_rekap_kpb extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "h23_rekap_kpb";
  var $title  = "Rekap KPB";

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
      $button2 = '';
      $button3 = '';
      $button4 = '';
      $button5 = '';
      $btn_edit = '<a style="margin-top:2px; margin-right:1px;"href="dealer/' . $this->page . '/edit?id=' . $rs->id_rekap_kpb . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $btn_print_report_kpb = '<a target="_blank" href="dealer/h23_rekap_kpb/report_kpb?id=' . $rs->id_rekap_kpb . '" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-print"></i> Report KPB Excel</a>';
      $btn_print_report_kpb_pdf = '<a target="_blank" href="dealer/h23_rekap_kpb/report_kpb_pdf?id=' . $rs->id_rekap_kpb . '" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Report KPB PDF</a>';
      $button .=  $btn_edit;
      $button3 .= $btn_print_report_kpb;
      $button5 .= $btn_print_report_kpb_pdf;
      $sub_array[] = '<a href="dealer/' . $this->page . '/detail?id=' . $rs->id_rekap_kpb . '">' . $rs->id_rekap_kpb . '</a>';;
      $sub_array[] = date_dmy($rs->tgl_rekap);
      $sub_array[] = $rs->jml_kpb;
      $sub_array[] = date_dmy($rs->start_date) . ' - ' . date_dmy($rs->end_date);
      $sub_array[] = 'Rp. ' . mata_uang_rp((int) $rs->tot_jasa);
      $sub_array[] = $rs->tot_qty_oli;
      $sub_array[] = '';
      $sub_array[] = '';
      $sub_array[] = '';
      $sub_array[] = '';
      $sub_array[] = $button .'|' . $button3 .'|'  . $button5;
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
      $filter['select'] = 'count';
      return $this->m_fin->getRekapKPB($filter)->num_rows();
    } else {
      return $this->m_fin->getRekapKPB($filter)->result();
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
    $id_rekap_kpb    = $this->input->get('id');

    $filter['id_rekap_kpb'] = $id_rekap_kpb;
    $result = $this->m_fin->getRekapKPB($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      $filter = [
        'id_rekap_kpb' => $id_rekap_kpb,
        'select_add' => ['kpb_ke', 'tot_qty_oli', 'jml_oli'],
        'id_type_wo_in' => "'ASS1','ASS2','ASS3','ASS4'"
      ];
      $data['details'] = $this->m_wo->get_sa_form($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url('dealer/' . $this->page) . "'>";
    }
  }


  // function generateData_old($return = null)
  // {
  //   $post = $this->input->post();
  //   $filter = [
  //     'not_in_rekap' => true,
  //     'periode_servis' => true,
  //     'start' => $post['start_date'],
  //     'end' => $post['end_date'],
  //     'njb_not_null' => true,
  //     'select_add' => ['kpb_ke', 'tot_qty_oli', 'checked', 'jml_oli'],
  //     'id_type_wo_in' => "'ASS1','ASS2','ASS3','ASS4'"
  //   ];
  //   $result = $this->m_wo->get_sa_form($filter);
  //   if ($result->num_rows() > 0) {
  //     $res = ['status' => 'sukses', 'details' => $result->result()];
  //   } else {
  //     $res = ['status' => 'error', 'pesan' => 'Data kosong !'];
  //   }
  //   if ($return == null) {
  //     send_json($res);
  //   } else {
  //     return $result->result();
  //   }
  // }

  function generateData($return = null)
  {
    $post = $this->input->post();
    $filter = [
      'not_in_rekap' => true,
      'periode_servis' => true,
      'start' => $post['start_date'],
      'end' => $post['end_date'],
      // 'kategori_kpb' => $post['kategori_kpb'],
      'njb_not_null' => true,
      'select_add' => ['kpb_ke', 'tot_qty_oli', 'checked', 'jml_oli'],
      'id_type_wo_in' => "'ASS1','ASS2','ASS3','ASS4'"
    ];
    // $result = $this->m_wo->get_sa_form($filter);
    $result = $this->m_wo->get_rekap_kpb_v1($filter);
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
    $details = $post['details'];
    // send_json($post);
    $id_rekap_kpb = $this->m_fin->get_id_rekap_kpb();
    $insert = [
      'id_rekap_kpb'         => $id_rekap_kpb,
      'id_dealer'        => dealer()->id_dealer,
      'tgl_rekap'        => tanggal(),
      'start_date'       => $post['start_date'],
      'end_date'         => $post['end_date'],
      'created_at'       => waktu_full(),
      'created_by'       => user()->id_user,
    ];
    // $jumlah = 0;
    $tot_jasa    = 0;
    $tot_qty_oli = 0;
    $jml_oli = 0;
    foreach ($details as $pr) {
      if ($pr['checked'] == 1) {
        $upd_wo[] = [
          'id_rekap_kpb' => $id_rekap_kpb,
          'id_work_order' => $pr['id_work_order']
        ];
        $tot_jasa += $pr['tot_pekerjaan'];
        if ($pr['kpb_ke'] == '1') {
          $tot_qty_oli += $pr['tot_qty_oli'];
          $jml_oli += $pr['jml_oli'];
        }
      }
      // $jumlah += $pr->total;
    }
    if (empty($upd_wo)) {
      $rsp = [
        'status' => 'error',
        'pesan' => 'Belum ada data yang dipilih !'
      ];
      send_json($rsp);
    }
    $insert['jml_kpb']     = count($details);
    $insert['tot_jasa']    = $tot_jasa;
    $insert['tot_qty_oli'] = $tot_qty_oli;
    $insert['jml_oli'] = $jml_oli;
    $tes = ['insert' => $insert, 'upd_wo' => $upd_wo];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_h2_dealer_rekap_kpb', $insert);
    $this->db->update_batch('tr_h2_wo_dealer', $upd_wo, 'id_work_order');
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

  function nama_dealer(){
    $id_dealer    = dealer()->id_dealer;
    $nama_dealer = $this->db->select('nama_dealer')
                            ->from('ms_dealer')
                            ->where('id_dealer',$id_dealer)
                            ->get()->row();
    return $nama_dealer;
  }

  function cetak_program_oli_gratis(){
    $id_rekap_kpb = $this->input->get('id');
    $filter = [
      'id_rekap_kpb' => $id_rekap_kpb,
      'id_type_wo_in' => "'ASS2','ASS3','ASS4'",
      'id_dealer'    => dealer()->id_dealer,
    ];

    // $data['cetak_laporan']     = $this->m_wo->get_rekap_kpb_cetak_program_oli_gratis($id_rekap_kpb);
    $nama_dealer_1  = $this->nama_dealer();
    $data['nama_dealer'] = $nama_dealer_1->nama_dealer;
    $data['cetak_laporan']     = $this->m_wo->get_rekap_kpb_cetak_program_oli_gratis($filter);
    $this->load->view("dealer/laporan/temp_rekap_kpb_cetak_program_oli_gratis",$data);
  }

  function report_kpb(){
    $id_rekap_kpb = $this->input->get('id');
    $filter = [
      'id_rekap_kpb' => $id_rekap_kpb,
      'id_type_wo_in' => "'ASS1','ASS2','ASS3','ASS4'",
      'id_dealer'        => dealer()->id_dealer,
    ];
    // $data['cetak_laporan']     = $this->m_wo->get_rekap_kpb_cetak_program_oli_gratis($id_rekap_kpb);
    $nama_dealer_1  = $this->nama_dealer();
    $data['nama_dealer'] = $nama_dealer_1->nama_dealer;
    $data['cetak_laporan']     = $this->m_wo->get_rekap_kpb_cetak_program_oli_gratis($filter);
    $this->load->view("dealer/laporan/temp_rekap_kpb_report_kpb_h23",$data);
  }

  public function cetak_program_oli_gratis_pdf()
  {
    // $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    // $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    // $login_id   = $this->session->userdata('id_user');
    $id_rekap_kpb = $this->input->get('id');

    $filter = [
      'id_rekap_kpb'  => $id_rekap_kpb,
      'id_type_wo_in' => "'ASS2','ASS3','ASS4'",
      'id_dealer'        => dealer()->id_dealer,
    ];
    $cetak_laporan  =$data['cetak_laporan'] = $this->m_wo->get_rekap_kpb_cetak_program_oli_gratis($filter);
    $nama_dealer_1  = $this->nama_dealer();
    $data['nama_dealer'] = $nama_dealer_1->nama_dealer;
    if ($cetak_laporan->num_rows() > 0) {
      $rows = $data['rows'] = $cetak_laporan->result();
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set']   = 'cetak';
      $title         = 'cetak_gol1ath_'.$data['nama_dealer'].'_pdf';
      $data['title'] = $title;

      // send_json($data);
      $html = $this->load->view('dealer/laporan/temp_rekap_kpb_cetak_program_oli_gratis_pdf', $data, true);
      // render the view into HTML
      $mpdf->WriteHTML($html);
      // write the HTML into the mpdf

      $output = $title . '.pdf';
      $mpdf->Output("$output", 'I');
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
    }
  }

  public function report_kpb_pdf()
  {
    // $tgl        = gmdate("y-m-d", time() + 60 * 60 * 7);
    // $waktu      = gmdate("y-m-d H:i:s", time() + 60 * 60 * 7);
    // $login_id   = $this->session->userdata('id_user');
    $id_rekap_kpb = $this->input->get('id');

    $filter = [
      'id_rekap_kpb' => $id_rekap_kpb,
      'id_type_wo_in' => "'ASS1','ASS2','ASS3','ASS4'",
      'id_dealer'        => dealer()->id_dealer,
    ];
    $cetak_laporan  =$data['cetak_laporan'] = $this->m_wo->get_rekap_kpb_cetak_program_oli_gratis($filter);
    $nama_dealer_1  = $this->nama_dealer();
    $data['nama_dealer'] = $nama_dealer_1->nama_dealer;
    if ($cetak_laporan->num_rows() > 0) {
      $rows = $data['rows'] = $cetak_laporan->result();
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;

      $data['set']   = 'cetak';
      $title         = 'cetak_report_kpb_ahass_ke_md'.$data['nama_dealer'].'_pdf';
      $data['title'] = $title;

      // send_json($data);
      $html = $this->load->view('dealer/laporan/temp_rekap_kpb_report_kpb_h23_pdf', $data, true);
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
