<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Queue extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2_booking', 'm_booking');
    $this->load->model('m_h2_api');
    $this->load->model('m_h2_master');
    $this->load->model('m_sc_master', 'm_sc');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function today_service_overview()
  {
    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'jenis_customer' => 'reguler',
      'select' => 'count',
      'id_sa_form_null' => true
    ];
    $walk_in = $this->m_booking->getQueue($f_w)->row()->count;

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'jenis_customer' => 'booking',
      'select' => 'count',
      'id_sa_form_null' => true
    ];
    $booking = $this->m_booking->getQueue($f_w)->row()->count;

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count',
      'status_wo_not' => 'closed',
      'id_work_order_not_null' => true,
      'join_wo' => true
    ];
    $service = $this->m_booking->getQueue($f_w)->row()->count;

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count',
      'status_form' => 'cancel'
    ];
    $canceled = $this->m_booking->getQueue($f_w)->row()->count;

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count',
      'job_return' => 1,
    ];
    $return = $this->m_booking->getQueue($f_w)->row()->count;

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count',
      'status_wo' => 'closed',
      'join_wo' => true
    ];
    $finished = $this->m_booking->getQueue($f_w)->row()->count;

    $data = [
      'walk_in' => (int)$walk_in,
      'booking' => (int)$booking,
      'canceled' => (int)$canceled,
      'service' => (int)$service,
      'finished' => (int)$finished,
      'return' => (int)$return,
    ];
    send_json(msg_sc_success($data, NULL));
  }
  function get_list()
  {
    $get = $this->input->get();
    $mandatory = [
      'offset' => 'required',
      'limit' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'status_monitor_in' => "'antrian','dipanggil'",
      'left_join_wo' => true,
      'offset' => $get['offset'],
      'length' => $get['limit'],
      'id_wo_null' => true,
      'order' => 'waktu_kedatangan_created_at_asc'
    ];
    $res = $this->m_booking->getQueue($f_w)->result();
    $result = [];
    foreach ($res as $rs) {
      $kedatangan = $rs->tgl_servis . ' ' . $rs->waktu_kedatangan;
      $service = $rs->tgl_servis . ' ' . $rs->jam_servis;
      $selisih = strtotime(waktu_full()) - strtotime($kedatangan);
      $waiting_time = floor($selisih / 60);
      $result[] = [
        'id' => $rs->id_antrian_int,
        'queue_no' => $rs->id_antrian_short,
        'service_time' => $rs->jam_servis,
        'time_in' => $rs->waktu_kedatangan,
        'waiting_time' => $waiting_time . ' menit',
        'police_no' => $rs->no_polisi,
        'status' => $rs->status_queue,
        'status_color' => color_status_queue($rs->status_queue),
      ];
    }

    send_json(msg_sc_success($result, NULL));
  }
  function monitor()
  {

    $f_w = [
      'tgl_servis' => tanggal(),
      'id_dealer' => $this->login->id_dealer,
      'order' => 'waktu_kedatangan_created_at_asc',
      'status_wo_null_not_closed' => true,
      'left_join_wo' => true,
    ];
    $res = $this->m_booking->getQueue($f_w)->result();
    $result = [];
    foreach ($res as $rs) {
      $result[] = [
        'queue_no' => $rs->id_antrian_short,
        'police_no' => $rs->no_polisi,
        'status' => ucwords(str_replace('_', ' ', $rs->status_monitor)),
        'status_color' => color_status_monitor($rs->status_monitor),
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function call()
  {

    $f_w = [
      'id_antrian_short' => $this->input->post('queue_no'),
      'id_dealer' => $this->login->id_dealer,
    ];
    $res = $this->m_booking->getQueue($f_w);
    cek_referensi($res, 'Queue No.');
    $res = $res->row();

    $update = [
      'status_monitor' => 'dipanggil',
    ];

    $this->db->trans_begin();
    $cond = [
      'id_antrian' => $res->id_antrian,
      'id_dealer' => $this->login->id_dealer
    ];
    $this->db->update('tr_h2_sa_form', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $booking = [
        'queue_no' => $res->id_antrian_short,
        'service_date' => $res->tgl_servis,
        'vehicle' => [
          'police_no' => $res->no_polisi,
          'stnk_name' => $res->nama_customer,
          'vehicle_year' => (int)$res->tahun_produksi,
          'vehicle_color_name' => $res->warna,
          'vehicle_type_name' => $res->kategori_tk,
          'vehicle_product_name' => $res->tipe_ahm,
          'engine_no' => $res->no_mesin,
          'frame_no' => $res->no_rangka,
        ]
      ];
      $f_k = [
        'id_dealer' => $this->login->id_dealer,
        'role_code' => 'device_monitor',
        'select' => 'regid'
      ];
      $dev = $this->m_sc->getKaryawan($f_k)->result_array();
      $regid = get_only_one($dev, 'regid');
      // send_json($regid);
      $params = [
        'judul' => 'command=refresh_queue_monitor',
        'pesan' => 'command=refresh_queue_monitor',
        'command' => 'refresh_queue_monitor',
        'regid' => $regid,
        'for' => 'h23',
        'is_mobile' => true
      ];
      $res_fcm = send_fcm($params);
      $result = [
        'fcm_result' => $res_fcm,
        'booking_data' => $booking
      ];

      send_json(msg_sc_success($result, NULL));
    }
  }
}
