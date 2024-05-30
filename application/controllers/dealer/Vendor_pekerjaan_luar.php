<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendor_pekerjaan_luar extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "vendor_pekerjaan_luar";
  var $title  = "Vendor Pekerjaan Luar";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_master', 'm_h2');
    //===== Load Library =====
    $this->load->helper('tgl_indo');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false' or $sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']       = $this->page;
    $data['title']     = $this->title;
    $data['set']       = "view";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data->result() as $rs) {
      $sub_array = array();
      $button    = '';
      $btn_edit = "<a data-toggle='tooltip' href='dealer/vendor_pekerjaan_luar/edit?id=$rs->id_vendor'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      if (can_access($this->page, 'can_update')) $button = $btn_edit;
      $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = "<a href='dealer/vendor_pekerjaan_luar/detail?id=$rs->id_vendor'>$rs->id_vendor</a>";
      $sub_array[] = $rs->nama_vendor;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->alamat;
      $sub_array[] = $aktif;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  function make_query($no_limit = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $order_column = array('id_vendor', 'nama_vendor', 'no_hp', 'alamat', 'aktif', null);
    $limit        = "LIMIT $start,$length";
    $order        = 'ORDER BY vdr.created_at DESC';
    $search       = $this->input->post('search')['value'];
    $id_dealer    = $this->m_admin->cari_dealer();
    $searchs      = "WHERE id_dealer='$id_dealer' ";

    if ($search != '') {
      $searchs .= "AND (vdr.id_vendor LIKE '%$search%' 
	          OR vdr.nama_vendor LIKE '%$search%'
	          OR vdr.no_hp LIKE '%$search%'
	          OR vdr.alamat LIKE '%$search%'
	          )
	      ";
    }

    if (isset($_POST["order"])) {
      $order_clm = $order_column[$_POST['order']['0']['column']];
      $order_by  = $_POST['order']['0']['dir'];
      $order     = "ORDER BY $order_clm $order_by";
    }

    if ($no_limit == 'y') $limit = '';

    return $this->db->query("SELECT id_vendor,nama_vendor,no_hp,alamat,aktif
   		 FROM ms_h2_vendor_pekerjaan_luar vdr
   		 $searchs $order $limit ");
  }
  function get_filtered_data()
  {
    return $this->make_query('y')->num_rows();
  }


  public function add()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "form";
    $data['mode']    = "insert";
    $data['dealers'] = $this->m_h2->getDealer();

    $this->template($data);
  }

  public function save()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $post     = $this->input->post();
    $dealer   = $this->m_h2->getDealer();
    $id_vendor = $this->m_h2->get_id_vendor($dealer);
    $id_dealer     = $this->m_admin->cari_dealer();
    $insert   = [
      'id_vendor'   => $id_vendor,
      'id_dealer'   => $id_dealer,
      'nama_vendor' => $post['nama_vendor'],
      'no_hp'       => $post['no_hp'],
      'alamat'      => $post['alamat'],
      'aktif'       => isset($_POST['aktif']) ? 1 : 0,
      'created_at'  => $waktu,
      'created_by'  => $login_id,
    ];
    $details = $post['details'];
    foreach ($details as $dt) {
      $ins_jasa[] = [
        'id_vendor'    => $id_vendor,
        'id_jasa'     => $dt['id_jasa']
      ];
    }
    // $tes = ['insert' => $insert, 'ins_jasa' => $ins_jasa];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_h2_vendor_pekerjaan_luar', $insert);
    $this->db->insert_batch('ms_h2_vendor_pekerjaan_luar_jasa', $ins_jasa);
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
        'link' => base_url('dealer/vendor_pekerjaan_luar')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function detail()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $id_vendor      = $this->input->get('id');
    $filter        = ['id_vendor' => $id_vendor];
    $row = $this->m_h2->get_vendor_pekerjaan_luar($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']     = "form";
      $data['mode']    = "detail";
      $data['details'] = $this->m_h2->get_vendor_pekerjaan_luar_jasa($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/vendor_pekerjaan_luar'>";
    }
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $id_vendor      = $this->input->get('id');
    $filter        = ['id_vendor' => $id_vendor];
    $row = $this->m_h2->get_vendor_pekerjaan_luar($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']     = "form";
      $data['mode']    = "edit";
      $data['details'] = $this->m_h2->get_vendor_pekerjaan_luar_jasa($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/vendor_pekerjaan_luar'>";
    }
  }

  public function save_edit()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $post = $this->input->post();
    $id_vendor = $post['id_vendor'];
    $update   = [
      'nama_vendor' => $post['nama_vendor'],
      'no_hp'       => $post['no_hp'],
      'alamat'      => $post['alamat'],
      'aktif'       => isset($_POST['aktif']) ? 1 : 0,
      'updated_at'  => $waktu,
      'updated_by'  => $login_id,
    ];
    $details = $post['details'];
    foreach ($details as $dt) {
      $ins_jasa[] = [
        'id_vendor'    => $id_vendor,
        'id_jasa'     => $dt['id_jasa'],
      ];
    }
    // $tes = ['update' => $update, 'ins_jasa' => $ins_jasa];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_h2_vendor_pekerjaan_luar', $update, ['id_vendor' => $id_vendor]);

    $this->db->delete('ms_h2_vendor_pekerjaan_luar_jasa', ['id_vendor' => $id_vendor]);
    $this->db->insert_batch('ms_h2_vendor_pekerjaan_luar_jasa', $ins_jasa);

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
        'link' => base_url('dealer/vendor_pekerjaan_luar')
      ];
      $_SESSION['pesan']   = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
