<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H2_upload_kpb extends CI_Controller
{

  var $folder = "master";
  var $page   = "h2_upload_kpb";
  var $title  = "Upload KPB";
  var $id_customer = '';

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
    $this->load->model('M_h2_md_claim', 'm_h2_claim');


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
  public function history()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'History ' . $this->title;
    $data['set']   = "history";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      // $button = '';
      // $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      // $btn_edit = '<a data-toggle="tooltip" title="Edit" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      // $btn_detail = '<a data-toggle="tooltip" title="Detail" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . '" class="btn btn-info btn-xs btn-flat"><i class="fa fa-eye"></i></a>';
      // $btn_delete = '<a onclick=\' return confirm("Apakah Anda yakin ?")\' data-toggle="tooltip" title="Delete" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/deleted?id=' . $rs->id . '" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></a>';
      // $button .= $btn_detail;
      // if (can_access($this->page, 'can_update')) $button .= $btn_edit;
      // if (can_access($this->page, 'can_delete')) {
      //   if ($rs->tahun == get_y() && $rs->bulan == get_m()) {
      //     $button .= $btn_delete;
      //   }
      // }
      // if (isset($_POST['is_history'])) {
      //   $button = $btn_detail;
      // }
      // $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . '">' . $rs->id . '</a>';
      // $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->kpb_ke;
      $sub_array[] = $rs->created_at;
      // $sub_array[] = $button;
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
      'order_column' => 'view_upload',
      'deleted' => false,
    ];
    // send_json($filter);
    if ($recordsFiltered == true) {
      return $this->m_h2_claim->getDataClaimKPBMD($filter)->num_rows();
    } else {
      return $this->m_h2_claim->getDataClaimKPBMD($filter)->result();
    }
  }

  public function upload()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "upload";
    $this->template($data);
  }

  public function _random_hex($length)
  {
    $data = 'ABCDEF1234567890';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
      $pos = rand(0, strlen($data) - 1);
      $string .= $data{
        $pos};
    }
    return $string;
  }

  function import_db()
  {
    $filename = $_FILES["userfile"]["tmp_name"];
    $name     = $_FILES["userfile"]["name"];
    $size     = $_FILES["userfile"]["size"];
    $name_r   = explode('.', $name);

    $generated_id = $this->_random_hex(10);

    if ($size > 0 and $name_r[1] == 'csv') {
      $file = fopen($filename, "r");
      $is_header_removed = FALSE;
      $no = 1;
      $ada_nosin = [];
      while (($rs = fgetcsv($file, 10000, ";")) !== FALSE) {
        $no++;
        // if ($no == 1) continue; // Skip Header
        //Cek No. Mesin
        $fcm = [
          'no_mesin' => $rs[0],
          'kpb_ke' => $rs[3]
        ];
        $cek_data = $this->m_h2_claim->getDataClaimKPBMD($fcm);

        if ($cek_data->num_rows() > 0) {
          $cek_data = $cek_data->row();
          $ada_nosin[$no] = [
            'no_mesin' => $cek_data->no_mesin,
            'no_rangka' => $cek_data->no_rangka,
          ];
        } else {
          $ins_kpb[] = [
            'no_mesin' => $rs[0],
            'no_rangka' => $rs[1],
            'id_tipe_kendaraan' => $rs[2],
            'no_kpb' => 0,
            'kpb_ke' => $rs[3] == NULL || $rs[3] == 'NULL' ? NULL : $rs[3],
            'id_part' => $rs[4] == NULL || $rs[4] == 'NULL' ? NULL : $rs[4],
            'harga_jasa' => $rs[5] == NULL || $rs[5] == 'NULL' ? NULL : $rs[5],
            'harga_material' => $rs[6] == NULL || $rs[6] == 'NULL' ? NULL : $rs[6],
            'diskon_material' => $rs[7] == NULL || $rs[7] == 'NULL' ? NULL : $rs[7],
            'tgl_beli_smh' => $rs[8] == NULL || $rs[8] == 'NULL' ? NULL : $rs[8],
            'km_service' => $rs[9] == NULL || $rs[9] == 'NULL' ? NULL : $rs[9],
            'tgl_service' => $rs[10] == NULL || $rs[10] == 'NULL' ? NULL : $rs[10],
            'id_dealer' => $rs[11] == NULL || $rs[11] == 'NULL' ? NULL : $rs[11],
            'id_periode' => $rs[12] == NULL || $rs[12] == 'NULL' ? NULL : $rs[12],
            'status' => $rs[13] == NULL || $rs[13] == 'NULL' ? NULL : $rs[13],
            'created_at' => $rs[14] == NULL || $rs[14] == 'NULL' ? NULL : $rs[14],
            'created_by' => 1,
            'is_pkp' => $rs[15] == NULL || $rs[15] == 'NULL' ? NULL : $rs[15],
            'is_inject' => 1,
            'generated_inject_id' => $generated_id,
            'nama_file_inject' => $name
          ];
          // send_json($ins_kpb);
        }
        $no++;
      }
      fclose($file);

      if (count($ada_nosin) > 0) {
        $html_pesan = 'No. Mesin & KPB Ke- Sudah Ada Dalam Database : <ul>';
        foreach ($ada_nosin as $key => $er) {
          $html_pesan .= "<li> Line : $key";
          $html_pesan .= "<ol>";
          // send_json($er);
          $html_pesan .= "<li>No. Mesin : {$er['no_mesin']}, Nama Cusomer : {$er['no_rangka']} </li>";
          $html_pesan .= "</ol>";
          $html_pesan .= "</li>";
        }
        $html_pesan .= "</ul>";
        $rsp_error = ['status' => 'error', 'tipe' => 'html', 'pesan' => $html_pesan];
      }

      $tes = [
        'ins_kpb' => isset($ins_kpb) ? $ins_kpb : NULL,
        'ada_nosin' => isset($ada_nosin) ? $ada_nosin : NULL
      ];
      // send_json($tes);

      $this->db->trans_begin();
      if (isset($ins_kpb)) {
        $this->db->insert_batch('tr_claim_kpb', $ins_kpb);
      }
      if (!$this->db->trans_status()) {
        $this->db->trans_rollback();
        $rsp = [
          'status' => 'error',
          'pesan' => ' Something went wrong !'
        ];
      } else {
        $this->db->trans_commit();
        $cins = 0;
        if (isset($ins_kpb)) {
          $cins = count($ins_kpb);
        }
        $pesan =   $cins . " Data berhasil di upload";
        if (count($ada_nosin) > 0) {
          $pesan .=  ", " . count($ada_nosin) . " No. mesin dan KPB sudah ada di dalam database";
        }
        $_SESSION['pesan']   = $pesan;
        $_SESSION['tipe']   = "success";
        $rsp = [
          'status' => 'sukses',
          'link' => base_url($this->folder . '/' . $this->page)
        ];
      }
    } else {
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    }
    send_json($rsp);
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Edit ' . $this->title;
    $data['mode']  = 'edit';
    $data['set']   = "form";
    $id    = $this->input->get('id');

    $filter['id'] = $id;
    $result = $this->m_dms->getH1TargetManagement($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
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
    $post      = $this->input->post();

    $id = $post['id'];

    $update = [
      'tahun'             => $post['tahun'],
      'bulan'             => $post['bulan'],
      'honda_id'          => $post['honda_id'],
      'target'            => $post['target'],
      'target_prospek'    => $post['target_prospek'],
      'target_sales'      => $post['target_sales'],
      'target_diskon'      => $post['target_diskon'],
      'target_diskon_amount'      => $post['target_diskon_amount'],
      'target_spk'        => $post['target_spk'],
      'id_dealer'         => dealer()->id_dealer,
      'id_tipe_kendaraan' => $post['id_tipe_kendaraan'],
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'updated_at'        => waktu_full(),
      'updated_by'        => user()->id_user,
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_h1_target_management', $update, ['id' => $id]);
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

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail ' . $this->title;
    $data['mode']  = 'detail';
    $data['set']   = "form";
    $id    = $this->input->get('id');

    $filter['id'] = $id;
    $result = $this->m_dms->getH1TargetManagement($filter);
    if ($result->num_rows() > 0) {
      $data['row'] = $result->row();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . "/" . $this->page) . "'>";
    }
  }
}
