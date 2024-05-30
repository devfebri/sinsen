<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H23_customer extends CI_Controller
{

  var $folder = "master";
  var $page   = "h23_customer";
  var $title  = "Upload Customer H23";
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
    $this->load->model('m_h2_master', 'm_h2');


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
      $sub_array[] = $rs->id_customer;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->id_tipe_kendaraan;
      $sub_array[] = $rs->id_warna;
      $sub_array[] = $rs->no_mesin;
      $sub_array[] = $rs->no_rangka;
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
      'order_column' => 'view',
      'deleted' => false,
    ];
    if (isset($_POST['periode'])) {
      $filter['periode'] = $_POST['periode'];
    }
    // send_json($filter);
    if ($recordsFiltered == true) {
      return $this->m_h2->getCustomer23($filter)->num_rows();
    } else {
      return $this->m_h2->getCustomer23($filter)->result();
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

  public function _cek_id_customer($id_customer, $multi_id_customer, $gen_id)
  {
    if (in_array($id_customer, $multi_id_customer)) {
      $id_customer_old = substr($id_customer, -4);
      $id_customer_exp = explode('/', $id_customer);
      $id_customer_new = $id_customer_exp[0] . '/' . $id_customer_exp[1] . '/' . $id_customer_exp[2] . '/' . $id_customer_exp[3] . '/' . sprintf("%'.04d", $id_customer_old + 1);
      $this->_cek_id_customer($id_customer_new, $multi_id_customer, $gen_id);
    } else {
      $this->id_customer = $id_customer;
    }
  }
  function import_db()
  {
    $filename = $_FILES["userfile"]["tmp_name"];
    $name     = $_FILES["userfile"]["name"];
    $size     = $_FILES["userfile"]["size"];
    $name_r   = explode('.', $name);

    $generated_id = $this->_random_hex(10);
    $multi_id_customer = [];
    if ($size > 0 and $name_r[1] == 'csv') {
      $file = fopen($filename, "r");
      $is_header_removed = FALSE;
      $no = 1;
      $ada_nosin = [];
      $ins_customer = [];
      while (($rs = fgetcsv($file, 10000, ";")) !== FALSE) {
        $no++;
        // if ($no == 1) continue; // Skip Header
        //Cek No. Mesin
        $fcm = ['no_mesin' => $rs[12]];
        $cek_nosin = $this->m_h2->getCustomer23($fcm);
        if ($cek_nosin->num_rows() > 0) {
          $cek_nosin = $cek_nosin->row();
          $ada_nosin[$no] = [
            'no_mesin' => $cek_nosin->no_mesin,
            'nama_customer' => $cek_nosin->nama_customer,
          ];
        } else {
          $filter_id = [
            'id_dealer' => $rs[17],
            'tgl_pembelian' => $rs[23],
            'multi_id_customer' => $multi_id_customer
          ];
          $id_customer = $this->m_h2->get_id_customer($filter_id);
          $this->_cek_id_customer($id_customer, $multi_id_customer, $generated_id);
          $id_customer = $this->id_customer;
          $multi_id_customer[] = $id_customer;
          if (!in_array($rs[12], $ins_customer)) {
            $ins_customer[$rs[12]] = [
              'id_customer' => $id_customer,
              'nama_customer' => $rs[1],
              'no_identitas' => $rs[2],
              'jenis_identitas' => $rs[3],
              'no_hp' => $rs[4],
              'alamat' => $rs[5],
              'id_kelurahan' => $rs[6],
              'id_kecamatan' => $rs[7],
              'id_kabupaten' => $rs[8],
              'id_provinsi' => $rs[9],
              'id_tipe_kendaraan' => $rs[10],
              'id_warna' => $rs[11],
              'no_mesin' => $rs[12],
              'no_rangka' => $rs[13],
              'tahun_produksi' => NULL,
              'no_polisi' => $rs[14],
              'id_cdb' => $rs[15] == 'NULL' ? 0 : $rs[15],
              'no_spk' => $rs[16],
              'id_dealer' => $rs[17],
              'created_by' => 1,
              'created_at' => waktu_full(),
              'ganti_customer' => 0,
              'tgl_pembelian' => $rs[23],
              'jenis_kelamin' => $rs[24],
              'alamat_identitas' => $rs[25],
              'email' => $rs[26],
              'nama_stnk' => $rs[27],
              'jenis_customer_beli' => $rs[28],
              'id_agama' => $rs[29] == NULL || $rs[29] == 'NULL' ? NULL : $rs[29],
              'id_kelurahan_identitas' => $rs[30] == NULL || $rs[30] == 'NULL' ? NULL : $rs[30],
              'longitude' => $rs[31] == NULL || $rs[31] == 'NULL' ? NULL : $rs[31],
              'latitude' => $rs[32] == NULL || $rs[32] == 'NULL' ? NULL : $rs[32],
              'id_dealer_h1' => $rs[17],
              'tgl_lahir' => $rs[36] == NULL || $rs[36] == 'NULL' ? NULL : $rs[36],
              'id_pekerjaan' => $rs[37] == NULL || $rs[37] == 'NULL' ? NULL : $rs[37],
              'instagram' => $rs[38] == NULL || $rs[38] == 'NULL' ? NULL : $rs[38],
              'facebook' => $rs[39] == NULL || $rs[39] == 'NULL' ? NULL : $rs[39],
              'twitter' => $rs[40] == NULL || $rs[40] == 'NULL' ? NULL : $rs[40],
              'sumber_data' => 'inject',
              'generated_inject_id' => $generated_id,
              'nama_file_inject' => $name
            ];
          }
          // send_json($ins_customer);
        }
        $no++;
      }
      fclose($file);

      if (count($ada_nosin) > 0) {
        $html_pesan = 'No. Mesin Sudah Ada Dalam Database : <ul>';
        foreach ($ada_nosin as $key => $er) {
          $html_pesan .= "<li> Line : $key";
          $html_pesan .= "<ol>";
          // send_json($er);
          $html_pesan .= "<li>No. Mesin : {$er['no_mesin']}, Nama Cusomer : {$er['nama_customer']} </li>";
          $html_pesan .= "</ol>";
          $html_pesan .= "</li>";
        }
        $html_pesan .= "</ul>";
        $rsp_error = ['status' => 'error', 'tipe' => 'html', 'pesan' => $html_pesan];
      }

      $tes = [
        'ins_customer' => isset($ins_customer) ? $ins_customer : NULL,
        'multi_id_customer' => isset($multi_id_customer) ? $multi_id_customer : NULL,
        'ada_nosin' => isset($ada_nosin) ? $ada_nosin : NULL
      ];
      // send_json($tes);

      $this->db->trans_begin();
      if (isset($ins_customer)) {
        $this->db->insert_batch('ms_customer_h23', $ins_customer);
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
        if (isset($ins_customer)) {
          $cins = count($ins_customer);
        }
        $pesan =   $cins . " Data berhasil di upload";
        if (count($ada_nosin) > 0) {
          $pesan .=  ", " . count($ada_nosin) . " No. mesin sudah ada di dalam database";
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
