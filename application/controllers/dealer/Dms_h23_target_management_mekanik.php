<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dms_h23_target_management_mekanik extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "dms_h23_target_management_mekanik";
  var $title  = "Target Management Mekanik";

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
    $this->load->model('m_dms');


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
      $status = '';
      $button = '';
      $btn_edit = '<a data-toggle="tooltip" title="Edit Data" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/edit?id=' . $rs->id . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
      $btn_delete = '<a onclick=\' return confirm("Apakah Anda yakin ?")\' data-toggle="tooltip" title="Delete" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/deleted?id=' . $rs->id . '" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></a>';

      $history = isset($_POST['is_history']) ? '&h=y' : '';
      $btn_detail = '<a data-toggle="tooltip" title="Detail" style="margin-top:2px; margin-right:1px;"href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . $history . '" class="btn btn-info btn-xs btn-flat"><i class="fa fa-eye"></i></a>';

      $button = $btn_detail;
      if (can_access($this->page, 'can_update')) $button .= $btn_edit;
      if (can_access($this->page, 'can_delete')) $button .= $btn_delete;

      // $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id . '">' . $rs->id . '</a>';
      $active = $rs->active == 1 ? '<i class="fa fa-check"></i>' : '';
      if (isset($_POST['is_history'])) {
        $button = $btn_detail;
      }
      $sub_array[] = $rs->tanggal;
      $sub_array[] = $rs->kode_dealer_md;
      $sub_array[] = $rs->id_flp_md;
      $sub_array[] = $rs->nama_lengkap;
      $sub_array[] = $rs->target_ue;
      $sub_array[] = mata_uang_rp($rs->target_revenue);
      $sub_array[] = mata_uang_rp($rs->target_oli);
      $sub_array[] = mata_uang_rp($rs->target_non_oli);
      $sub_array[] = $active;
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
      'order_column' => 'view',
      'deleted' => false
    ];
    if (isset($_POST['is_history'])) {
      $filter['tanggal_lebih_kecil'] = tanggal();
    } else {
      $filter['tanggal_lebih_besar_sama'] = tanggal();
    }
    if ($recordsFiltered == true) {
      return $this->m_dms->getH23TargetManagementMekanik($filter)->num_rows();
    } else {
      return $this->m_dms->getH23TargetManagementMekanik($filter)->result();
    }
  }
  function deleted()
  {
    $get       = $this->input->get();
    $filter = ['id' => $get['id']];
    $cek = $this->m_dms->getH23TargetManagementMekanik($filter);
    if ($cek->num_rows() > 0) {
      $this->db->trans_begin();
      $deleted = ['deleted' => 1];
      $this->db->update('dms_h23_target_management_mekanik', $deleted, ['id' => $get['id']]);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $_SESSION['pesan']   = "Something went wrong !";
        $_SESSION['tipe']   = "error";
      } else {
        $this->db->trans_commit();
        $_SESSION['pesan']   = "Data has been deleted successfully";
        $_SESSION['tipe']   = "error";
      }
    }
    echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->page) . "'>";
  }
  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    $this->template($data);
  }

  function save()
  {
    $post       = $this->input->post();
    $insert = [
      'tanggal'             => $post['tanggal'],
      'id_flp_md'             => $post['id_flp_md'],
      'target_ue'             => $post['target_ue'],
      'target_revenue'             => $post['target_revenue'],
      'target_oli'             => $post['target_oli'],
      'target_non_oli'             => $post['target_non_oli'],
      'id_dealer'         => dealer()->id_dealer,
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'created_at'        => waktu_full(),
      'created_by'        => user()->id_user,
    ];
    // $tes = ['insert' => $insert];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('dms_h23_target_management_mekanik', $insert);
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

  public function upload()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "upload";
    $this->template($data);
  }

  function import_db()
  {
    $filename = $_FILES["userfile"]["tmp_name"];
    $name     = $_FILES["userfile"]["name"];
    $size     = $_FILES["userfile"]["size"];
    $name_r   = explode('.', $name);

    if ($size > 0 and $name_r[count($name_r) - 1] == 'csv') {
      $file = fopen($filename, "r");
      $is_header_removed = FALSE;
      $id_dealer = dealer()->id_dealer;
      $id_user = user()->id_user;
      $no = 0;
      $err = [];
      while (($rs = fgetcsv($file, 10000, ";")) !== FALSE) {
        $no++;
        $no_min = $no - 1;
        $rss[] = $rs;
        if ($no == 1) {
          continue;
        }

        //Cek Tanggal
        $tanggal = date_ymd($rs[0]);
        if (strtotime($tanggal) < strtotime(tanggal())) {
          $err[$no_min][] = "Tanggal Harus Lewat Tanggal Hari Ini";
          break;
        }

        //Cek Sudah Ada Data Atau Belum
        $filter = [
          'tanggal' => $tanggal,
          'id_flp_md' => $rs[1]
        ];
        $cek = $this->m_dms->getH23TargetManagementMekanik($filter);
        if ($cek->num_rows() == 0) {
          $cek_honda_id = $this->db->query("SELECT id_karyawan_dealer FROM ms_karyawan_dealer WHERE (id_flp_md='{$rs[1]}' OR honda_id='{$rs[1]}') AND id_dealer='$id_dealer' AND active=1")->num_rows();
          if ($cek_honda_id == 0) {
            $err[$no_min][] = "Honda ID tidak ditemukan !";
            continue;
          }
          $ins_batch[] = [
            'tanggal' => $tanggal,
            'id_flp_md'      => $rs[1],
            'target_ue' => $rs[2],
            'target_revenue' => $rs[3],
            'target_oli' => $rs[4],
            'target_non_oli' => $rs[5],
            'id_dealer' => $id_dealer,
            'created_at' => waktu_full(),
            'active' => 1,
            'created_by' => $id_user
          ];
        } else {
          $cek = $cek->row();
          $upd_batch[] = [
            'id' => $cek->id,
            'target_ue' => $rs[2],
            'target_revenue' => $rs[3],
            'target_oli' => $rs[4],
            'target_non_oli' => $rs[5],
            'id_dealer' => $id_dealer,
            'updated_at' => waktu_full(),
            'active' => 1,
            'updated_by' => $id_user
          ];
        }
        // echo $no;
      }
      // send_json($rss);
      fclose($file);
      if (count($err) > 0) {
        $rsp = ['status' => 'error', 'tipe' => 'html', 'pesan' => $err];
        send_json($rsp);
      }
      $tes = [
        'ins' => isset($ins_batch) ? $ins_batch : NULL,
        'upd' => isset($upd_batch) ? $upd_batch : NULL
      ];
      // send_json($tes);
      $this->db->trans_begin();
      if (isset($ins_batch)) {
        $this->db->insert_batch('dms_h23_target_management_mekanik', $ins_batch);
      }
      if (isset($upd_batch)) {
        $this->db->update_batch('dms_h23_target_management_mekanik', $upd_batch, 'id');
      }
      if (!$this->db->trans_status()) {
        $this->db->trans_rollback();
        $rsp = [
          'status' => 'error',
          'pesan' => ' Something went wrong !'
        ];
      } else {
        $this->db->trans_commit();
        $c_ins = isset($ins_batch) ? count($ins_batch) : 0;
        $c_upd = isset($upd_batch) ? count($upd_batch) : 0;
        if ($c_ins > 0) {
          $set_pesan[] = "$c_ins data berhasil ditambah";
        }
        if ($c_upd > 0) {
          $set_pesan[] = "$c_upd data berhasil diupdate";
        }
        $_SESSION['pesan']   = implode(', ', $set_pesan);
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
    $result = $this->m_dms->getH23TargetManagementMekanik($filter);
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
      'tanggal'           => $post['tanggal'],
      'id_flp_md'             => $post['id_flp_md'],
      'target_ue'         => $post['target_ue'],
      'target_revenue'    => $post['target_revenue'],
      'target_oli'        => $post['target_oli'],
      'target_non_oli'    => $post['target_non_oli'],
      'id_dealer'         => dealer()->id_dealer,
      'active' => $this->input->post('active') == 'on' ? 1 : 0,
      'updated_at'        => waktu_full(),
      'updated_by'        => user()->id_user,
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('dms_h23_target_management_mekanik', $update, ['id' => $id]);
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
      $_SESSION['pesan']   = "Data has been updated successfully";
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
    $result = $this->m_dms->getH23TargetManagementMekanik($filter);
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
