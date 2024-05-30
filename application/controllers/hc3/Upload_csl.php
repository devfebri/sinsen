<?php
defined('BASEPATH') or exit('No direct script access allowed');

//load Spout Library
require_once APPPATH . '/third_party/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
// use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Upload_csl extends CI_Controller
{
  var $folder = "hc3";
  var $page   = "upload_csl";
  var $title  = "Upload CSL";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_md_csl_master', 'm_csl_master');
    $this->load->model('m_md_csl', 'm_csl');
    //===== Load Library =====
    $this->load->library('upload');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    } elseif ($sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
    }
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $data['folder']   = $this->folder;
      $data['page']   = $this->page;
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']  = "view";
    $this->template($data);
  }

  public function upload()
  {
    if (isset($_POST['submit'])) {
      $id_upload = $this->m_csl->get_id_upload();

      $f = [
        'tahun' => $this->input->post('tahun'),
        'bulan' => $this->input->post('bulan'),
        'kategori' => strtoupper($this->input->post('kategori')),
      ];
      $cek = $this->m_csl->getUploadCSL($f);
      if ($cek->num_rows() > 0) {
        $last      = $cek->row();
        $id_upload = $last->id_upload;
        $cond      = ['id_upload' => $id_upload];
        // $rsp = [
        //   'status' => 'error',
        //   'pesan' => "Data CSL tahun : {$f['tahun']} bulan : {$f['bulan']} kategori : {$f['kategori']} sudah di upload "
        // ];
        // send_json($rsp);
      }

      $path = './uploads/temp_csl/';
      if (!is_dir($path)) {
        mkdir($path, 0777, true);
      }
      $config['upload_path']      = $path; //siapkan path untuk upload file
      $config['allowed_types']    = 'xlsx|xls'; //siapkan format file
      $this->load->library('upload', $config);
      $this->upload->initialize($config);
      // echo json_encode($this->upload->display_errors());
      // exit();

      if ($this->upload->do_upload('file_csl')) {
        $file_name = $this->upload->file_name;
        $post = $this->input->post();

        //fetch data upload
        $file   = $this->upload->data();
        $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
        $reader->open('uploads/temp_csl/' . $file['file_name']); //open file xlsx

        foreach ($reader->getSheetIterator() as $sheet) {
          $numRow = 1;

          //siapkan variabel array kosong untuk menampung variabel array data
          $code_atribut   = [];
          $target   = [];
          $actual_dealer = [];
          $index_actual = 0;
          if ($sheet->getIndex() === 0) {
            //looping pembacaan row dalam sheet
            // send_json($sheet->getRowIterator());
            foreach ($sheet->getRowIterator() as $row) {

              //Set Code Atribut
              if ($numRow == 1) {
                foreach ($row as $key => $val) {
                  if ($val != '') {
                    $flt = [
                      'code' => $val,
                      'kategori' => $post['kategori']
                    ];
                    $cek_attr = $this->m_csl->getAtributCSL($flt);
                    if ($cek_attr->num_rows() > 0) {
                      $val = $cek_attr->row()->id;
                    } else {
                      $rsp = [
                        'status' => 'error',
                        'pesan' => "Kode Attribut : $val untuk kategori : " . strtoupper($post['kategori']) . " tidak ditemukan !"
                      ];
                      send_json($rsp);
                    }
                    $code_atribut[$key] = $val;
                  }
                }
              }
              //Set Target
              elseif ($numRow == 3) {
                foreach ($code_atribut as $key => $value) {
                  $val = $row[$key];
                  $val = $val < 1 ? $val * 100 : $val;
                  $target[] = [
                    'id_upload' => $id_upload,
                    'id_atribut' => $value,
                    'target' => $val
                  ];
                }
              }
              //Set Target
              elseif ($numRow > 3) {
                $id_dealer = $row[0];
                $filter = ['kode_dealer_md' => $id_dealer];
                $dealer = $this->m_csl_master->getDealer($filter);
                if ($dealer->num_rows() == 0) {
                  $rsp = [
                    'status' => 'error',
                    'pesan' => "Kode Dealer : $id_dealer tidak ditemukan !"
                  ];
                  send_json($rsp);
                } else {
                  $dealer = $dealer->row();
                }
                foreach ($code_atribut as $key => $value) {
                  $val = $row[$key];
                  $actual = $val < 1 ? $val * 100 : $val;
                  $actual_dealer[$index_actual]['id_upload'] = $id_upload;
                  $actual_dealer[$index_actual]['id_atribut'] = $value;
                  $actual_dealer[$index_actual]['id_dealer'] = $dealer->id_dealer;
                  $actual_dealer[$index_actual]['actual'] = $actual;
                  $index_actual++;
                }
              }
              $numRow++;
            }
          }
        }
        $reader->close();
      } else {
        $error = ($this->upload->display_errors());
        $rsp = [
          'status' => 'error',
          'pesan' => $error
        ];
        send_json($rsp);
      }
      if (isset($last)) {
        $update = [
          'tahun'      => $post['tahun'],
          'bulan'      => $post['bulan'],
          'tipe'       => $post['tipe'],
          'kategori'   => $post['kategori'],
          'status'     => '',
          'updated_at' => waktu_full(),
          'updated_by' => user()->id_user,
        ];
      } else {
        $insert = [
          'id_upload'  => $id_upload,
          'tahun'      => $post['tahun'],
          'bulan'      => $post['bulan'],
          'tipe'       => $post['tipe'],
          'kategori'   => $post['kategori'],
          'status'     => '',
          'created_at' => waktu_full(),
          'created_by' => user()->id_user,
        ];
      }
      // $tes = [
      //   'actual_dealer' => $actual_dealer,
      //   'target' => $target,
      //   'insert' => isset($insert) ? $insert : '',
      //   'update' => isset($update) ? $update : '',
      // ];
      // send_json($tes);
      $this->db->trans_begin();

      if (isset($insert)) {
        $this->db->insert('tr_csl_upload', $insert);
      }
      if (isset($update)) {
        $this->db->update('tr_csl_upload', $update);
      }

      if (isset($actual_dealer)) {
        if (count($actual_dealer) > 0) {
          if (isset($last)) {
            $this->db->delete('tr_csl_upload_actual_dealer', $cond);
          }
          $this->db->insert_batch('tr_csl_upload_actual_dealer', $actual_dealer);
        }
      }

      if (isset($target)) {
        if (count($target) > 0) {
          if (isset($last)) {
            $this->db->delete('tr_csl_upload_target', $cond);
          }
          $this->db->insert_batch('tr_csl_upload_target', $target);
        }
      }
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $rsp = [
          'status' => 'error',
          'pesan' => ' Something went wrong !'
        ];
      } else {
        $this->db->trans_commit();
        $tot_upl = $numRow - 4;
        $_SESSION['pesan']   = "$tot_upl record berhasil diupload.";
        $_SESSION['tipe']   = "success";
        if (file_exists(FCPATH . "uploads/temp_csl/" . $file_name)) {
          unlink("uploads/temp_csl/" . $file_name); //Hapus Gambar
        }
        $rsp = [
          'status' => 'sukses',
          'link' => base_url($this->folder . '/' . $this->page)
        ];
      }
      send_json($rsp);
    } else {
      $data['isi']    = $this->page;
      $data['title']  = $this->title;
      $data['mode']    = "upload";
      $data['set']    = "upload";
      $this->template($data);
    }
  }

  public function fetch()
  {
    $fetch_data = $this->make_query_fetch();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      // $btn_approval = "<a class='btn btn-success btn-xs btn-flat' href=\"" . base_url('h2/' . $this->page . '/approved?id=' . $rs->id_po_kpb) . "\">Approval</a>";

      // if ($rs->status == 'input') {
      // 	$status = '<label class="label label-primary">Input</label>';
      // 	// if (can_access($this->page, 'can_update'))  
      // 	// $button .= $btn_approval;
      // } elseif ($rs->status == 'approved') {
      // 	$status = '<label class="label label-success">Approved</label>';
      // } elseif ($rs->status == 'rejected') {
      // 	$status = '<label class="label label-danger">Rejected</label>';
      // }

      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_upload . '">' . $rs->id_upload . '</a>';
      $sub_array[] = $rs->tgl_upload;
      $sub_array[] = $rs->tahun;
      $sub_array[] = $rs->bulan;
      $sub_array[] = $rs->tipe;
      $sub_array[] = strtoupper($rs->kategori);
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_fetch(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_fetch($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'view',
      'search' => $this->input->post('search')['value']
    ];
    if (isset($_POST['kategori'])) {
      $filter['kategori'] = $_POST['kategori'];
    }
    if (isset($_POST['tahun'])) {
      $filter['tahun'] = $_POST['tahun'];
    }
    if (isset($_POST['bulan'])) {
      $filter['bulan'] = $_POST['bulan'];
    }
    if ($recordsFiltered == true) {
      return $this->m_csl->getUploadCSL($filter)->num_rows();
    } else {
      return $this->m_csl->getUploadCSL($filter)->result();
    }
  }

  public function detail()
  {
    $id = $this->input->get('id');
    $filter = ['id_upload' => $id];
    $row = $this->m_csl->getUploadCSL($filter);
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Detail ' . $this->title;
      $data['mode']  = 'detail';
      $data['set']   = "upload";
      $data['row'] = $row->row();
      // $data['actual_dealer'] = $this->m_csl->getDetailActualUpladCSL($filter);
      $f_target = [
        'id_upload' => $id,
        'select' => 'target_atribut',
        'order' => 'atribut_code_asc'
      ];
      $data['detail_target'] = $this->m_csl->getDetailTargetListUpladCSL($f_target)->result();
      $data['detail_actual'] = $this->m_csl->getDetailActualListPerDealerUpladCSL($filter);
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }
}
