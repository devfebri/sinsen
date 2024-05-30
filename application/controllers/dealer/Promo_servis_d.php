<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Promo_servis_d extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "promo_servis_d";
  var $title  = "Promo Servis";

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
      $btn_edit = "<a data-toggle='tooltip' href='dealer/promo_servis_d/edit?id=$rs->id_promo'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      if (can_access($this->page, 'can_update'))  $button = $btn_edit;
      $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = "<a href='dealer/promo_servis_d/detail?id=$rs->id_promo'>$rs->id_promo</a>";
      $sub_array[] = $rs->nama_promo;
      $sub_array[] = date_dmy($rs->start_date, '/');
      $sub_array[] = date_dmy($rs->end_date, '/');
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
    $order_column = array('id_promo', 'nama_promo', 'start_date', 'end_date', 'aktif', null);
    $limit        = "LIMIT $start,$length";
    $order        = 'ORDER BY pr.created_at DESC';
    $search       = $this->input->post('search')['value'];
    $id_dealer    = $this->m_admin->cari_dealer();
    $searchs      = "WHERE EXISTS (SELECT id_dealer FROM ms_promo_servis_dealer WHERE id_promo=pr.id_promo AND id_dealer='$id_dealer') ";

    if ($search != '') {
      $searchs .= "AND (pr.id_promo LIKE '%$search%' 
	          OR pr.nama_promo LIKE '%$search%'
	          )
	      ";
    }

    if (isset($_POST["order"])) {
      $order_clm = $order_column[$_POST['order']['0']['column']];
      $order_by  = $_POST['order']['0']['dir'];
      $order     = "ORDER BY $order_clm $order_by";
    }

    if ($no_limit == 'y') $limit = '';

    return $this->db->query("SELECT pr.id_promo,start_date,nama_promo,end_date,aktif
   		 FROM ms_promo_servis pr
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
    $id_promo = $this->m_h2->get_id_promo($dealer, 'dealer');
    $insert   = [
      'id_promo'   => $id_promo,
      'start_date' => $post['start_date'],
      'end_date'   => $post['end_date'],
      'nama_promo' => $post['nama_promo'],
      'aktif'     => isset($_POST['aktif']) ? 1 : 0,
      'created_at' => $waktu,
      'created_by' => $login_id,
      'sumber' => 'dealer'
    ];
    $details = $post['details'];
    foreach ($details as $dt) {
      $ins_jasa[] = [
        'id_promo'    => $id_promo,
        'id_jasa'     => $dt['id_jasa'],
        'tipe_diskon' => $dt['tipe_diskon'],
        'diskon'      => $dt['diskon'],
      ];
    }
    $ins_dealer[] = [
      'id_promo'    => $id_promo,
      'id_dealer'     => $dealer->id_dealer
    ];
    // $tes = ['insert' => $insert, 'ins_jasa' => $ins_jasa, 'ins_dealer' => $ins_dealer];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('ms_promo_servis', $insert);
    $this->db->insert_batch('ms_promo_servis_dealer', $ins_dealer);
    $this->db->insert_batch('ms_promo_servis_jasa', $ins_jasa);
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
        'link' => base_url('dealer/promo_servis_d')
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
    $id_promo      = $this->input->get('id');
    $filter        = ['id_promo' => $id_promo];
    $row = $this->m_h2->get_promo_servis($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']     = "form";
      $data['mode']    = "detail";

      $data['details'] = $this->m_h2->get_promo_servis_jasa($filter)->result();
      $data['dealers'] = $this->m_h2->getDealer();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/promo_servis_d'>";
    }
  }

  public function edit()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $id_promo      = $this->input->get('id');
    $filter        = ['id_promo' => $id_promo];
    $row = $this->m_h2->get_promo_servis($filter);
    if ($row->num_rows() > 0) {
      $row = $data['row'] = $row->row();
      $data['set']     = "form";
      $data['mode']    = "edit";
      $data['details'] = $this->m_h2->get_promo_servis_jasa($filter)->result();

      $data['dealers'] = $this->m_h2->getDealer();

      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/promo_servis_d'>";
    }
  }

  public function save_edit()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $post = $this->input->post();
    $id_promo = $post['id_promo'];
    $update   = [
      'start_date' => $post['start_date'],
      'end_date'   => $post['end_date'],
      'nama_promo' => $post['nama_promo'],
      'aktif'     => isset($_POST['aktif']) ? 1 : 0,
      'created_at' => $waktu,
      'created_by' => $login_id
    ];
    $details = $post['details'];
    foreach ($details as $dt) {
      $ins_jasa[] = [
        'id_promo'    => $id_promo,
        'id_jasa'     => $dt['id_jasa'],
        'tipe_diskon' => $dt['tipe_diskon'],
        'diskon'      => $dt['diskon'],
      ];
    }
    // $dealers = $post['dealers'];
    // foreach ($dealers as $dl) {
    //   $ins_dealer[] = [
    //     'id_promo'    => $id_promo,
    //     'id_dealer'     => $dl['id_dealer']
    //   ];
    // }
    // $dl = $this->db->query("SELECT * FROM ms_promo_servis_dealer WHERE id_promo='id_promo'");
    // $ins_dealer[] = [
    //   'id_promo'  => $id_promo,
    //   'id_dealer' => dealer()->id_dealer
    // ];
    // $tes = ['update' => $update, 'ins_jasa' => $ins_jasa, 'ins_dealer' => $ins_dealer];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_promo_servis', $update, ['id_promo' => $id_promo]);

    // $this->db->delete('ms_promo_servis_dealer', ['id_promo' => $id_promo]);
    // $this->db->insert_batch('ms_promo_servis_dealer', $ins_dealer);

    $this->db->delete('ms_promo_servis_jasa', ['id_promo' => $id_promo]);
    $this->db->insert_batch('ms_promo_servis_jasa', $ins_jasa);
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
        'link' => base_url('dealer/promo_servis_d')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
}
