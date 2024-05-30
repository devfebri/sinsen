<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/third_party/PHPExcel/PHPExcel.php';

class Promo_servis extends CI_Controller
{

  var $folder = "master";
  var $page   = "promo_servis";
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
      $btn_edit = "<a data-toggle='tooltip' href='master/promo_servis/edit?id=$rs->id_promo'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-edit'></i></button></a>";
      $button = $btn_edit;
      $aktif = $rs->aktif == 1 ? '<i class="fa fa-check"></i>' : '';

      $sub_array[] = "<a href='master/promo_servis/detail?id=$rs->id_promo'>$rs->id_promo</a>";
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
    $searchs      = "WHERE 1=1 ";

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
    $this->template($data);
  }

  public function save()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $post = $this->input->post();
    $id_promo = $this->m_h2->get_id_promo('E20');

    if ($post['kode_promo_customer_apps'] != '') {
      $cek_kode_promo_customer_apps = $this->db->get_where('ms_promo_servis', ['kode_promo_customer_apps' => $post['kode_promo_customer_apps']])->row();
      if ($cek_kode_promo_customer_apps) {
        $rsp = [
          'status' => 'error',
          'pesan' => 'Kode Promo Customer Apps Sudah Ada'
        ];
        echo json_encode($rsp);
        die;
      }
    }

    $insert   = [
      'id_promo'   => $id_promo,
      'start_date' => $post['start_date'],
      'end_date'   => $post['end_date'],
      'nama_promo' => $post['nama_promo'],
      'kode_promo_customer_apps' => $post['kode_promo_customer_apps'] == '' ? null : $post['kode_promo_customer_apps'],
      'aktif'     => isset($_POST['aktif']) ? 1 : 0,
      'created_at' => $waktu,
      'created_by' => $login_id,
      'sumber' => 'md'
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
    $dealers = $post['dealers'];
    foreach ($dealers as $dl) {
      $ins_dealer[] = [
        'id_promo'    => $id_promo,
        'id_dealer'     => $dl['id_dealer']
      ];
    }
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
        'link' => base_url('master/promo_servis')
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
      $data['dealers'] = $this->m_h2->get_promo_servis_dealer($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/promo_servis'>";
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
      $data['dealers'] = $this->m_h2->get_promo_servis_dealer($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "master/promo_servis'>";
    }
  }

  public function save_edit()
  {
    $waktu    = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id = $this->session->userdata('id_user');
    $post = $this->input->post();
    $id_promo = $post['id_promo'];

    $promo = $this->db->get_where('ms_promo_servis', ['id_promo' => $post['id_promo']])->row();

    if ($post['kode_promo_customer_apps'] != $promo->kode_promo_customer_apps) {
      $cek_kode_promo_customer_apps = $this->db->get_where('ms_promo_servis', ['kode_promo_customer_apps' => $post['kode_promo_customer_apps']])->row();
      if ($cek_kode_promo_customer_apps) {
        $rsp = [
          'status' => 'error',
          'pesan' => 'Kode Promo Customer Apps Sudah Ada'
        ];
        echo json_encode($rsp);
        die;
      }
    }

    $update   = [
      'start_date' => $post['start_date'],
      'end_date'   => $post['end_date'],
      'nama_promo' => $post['nama_promo'],
      'kode_promo_customer_apps' => $post['kode_promo_customer_apps'] == '' ? null : $post['kode_promo_customer_apps'],
      'aktif'     => isset($_POST['aktif']) ? 1 : 0,
      'updated_at' => $waktu,
      'updated_by' => $login_id
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
    $dealers = $post['dealers'];
    foreach ($dealers as $dl) {
      $ins_dealer[] = [
        'id_promo'    => $id_promo,
        'id_dealer'     => $dl['id_dealer']
      ];
    }
    // $tes = ['update' => $update, 'ins_jasa' => $ins_jasa, 'ins_dealer' => $ins_dealer];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('ms_promo_servis', $update, ['id_promo' => $id_promo]);

    $this->db->delete('ms_promo_servis_dealer', ['id_promo' => $id_promo]);
    $this->db->insert_batch('ms_promo_servis_dealer', $ins_dealer);

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
        'link' => base_url('master/promo_servis')
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }
  function excel_apps()
  {
    $excel = new PHPExcel();
    $excel->getProperties()->setCreator('Master Jasa')
      ->setLastModifiedBy('Master Jasa')
      ->setTitle("Master Jasa")
      ->setSubject("Master Jasa")
      ->setDescription("Master Jasa")
      ->setKeywords("Master Jasa");
    $excel->setActiveSheetIndex(0)->setCellValue('A2', 'Type motor(3 Digit code AHM)');
    $excel->setActiveSheetIndex(0)->setCellValue('B2', 'Nama Paket');
    $excel->setActiveSheetIndex(0)->setCellValue('C2', 'No Part');
    $excel->setActiveSheetIndex(0)->setCellValue('D2', 'Nama Jasa');
    $excel->setActiveSheetIndex(0)->setCellValue('E2', 'Kategori');
    $excel->setActiveSheetIndex(0)->setCellValue('F2', 'Mileage');
    $excel->setActiveSheetIndex(0)->setCellValue('G2', 'Harga Servis');
    $excel->setActiveSheetIndex(0)->setCellValue('H2', 'Harga Min');
    $excel->setActiveSheetIndex(0)->setCellValue('I2', 'Harga Max');
    $excel->setActiveSheetIndex(0)->setCellValue('J2', 'Harga Sparepart');
    $excel->setActiveSheetIndex(0)->setCellValue('K2', 'Ket.');
    $datas = $this->db->query("SELECT tk.id_tipe_kendaraan, nama_promo nama_paket,'' no_part, js.deskripsi nama_jasa,'' kategori, 0 mileage, js.harga harga_servis, js.batas_atas harga_max, js.batas_bawah harga_min,0 harga_sparepart,'' keterangan  
    FROM ms_promo_servis_jasa psj
    JOIN ms_promo_servis ps ON ps.id_promo = psj.id_promo
    JOIN ms_h2_jasa js ON psj.id_jasa = js.id_jasa
    LEFT JOIN ms_tipe_kendaraan tk ON tk.kode_ptm=js.tipe_motor
    ")->result();
    $row = 3;
    foreach ($datas as $key => $dt) {
      $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, $dt->id_tipe_kendaraan);
      $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $dt->nama_paket);
      $excel->setActiveSheetIndex(0)->setCellValue('C' . $row, $dt->no_part);
      $excel->setActiveSheetIndex(0)->setCellValue('D' . $row, $dt->nama_jasa);
      $excel->setActiveSheetIndex(0)->setCellValue('E' . $row, $dt->kategori);
      $excel->setActiveSheetIndex(0)->setCellValue('F' . $row, $dt->mileage);
      $excel->setActiveSheetIndex(0)->setCellValue('G' . $row, $dt->harga_servis);
      $excel->setActiveSheetIndex(0)->setCellValue('H' . $row, $dt->harga_min);
      $excel->setActiveSheetIndex(0)->setCellValue('I' . $row, $dt->harga_max);
      $excel->setActiveSheetIndex(0)->setCellValue('J' . $row, $dt->harga_sparepart);
      $excel->setActiveSheetIndex(0)->setCellValue('K' . $row, $dt->keterangan);
      $row++;
    }

    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    // Set judul file excel nya
    $excel->getActiveSheet(0)->setTitle("Master Data List Jasa Servis");
    $excel->setActiveSheetIndex(0);
    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Master Data List Jasa Servis.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $write->save('php://output');
  }
}
