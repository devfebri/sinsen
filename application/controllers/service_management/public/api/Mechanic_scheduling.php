<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Mechanic_scheduling extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2');
    $this->load->model('m_dms');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_sc_master', 'm_sc');
    $this->load->model('m_sm_master', 'm_sm');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_h2_master');


    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function get_list()
  {
    $get = $this->input->get();
    $mandatory = [
      'limit' => 'required',
      'offset' => 'required',
    ];
    cek_mandatory($mandatory, $get);
    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_work_order_not_null' => true,
      'status_wo_not' => 'closed',
      'last_stats_not' => 'end',
      'offset' => $get['offset'],
      'length' => $get['limit'],
      'id_pit_not_null' => true,
      'mekanik_not_null' => true,
    ];



    $get_data = $this->m_wo->get_sa_form($f_mc)->result();
    $result = [];
    foreach ($get_data as $rs) {
      $f_cc = [
        'id_work_order' => $rs->id_work_order,
        'select' => 'select_type'
      ];
      $desk_type = $this->m_wo->getWOPekerjaan($f_cc)->row()->desk_type;

      $f_k = ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan];
      $tk = $this->m_unit->getTipeKendaraan($f_k)->row();
      $vehicle = [
        'police_no' => (string)$rs->no_polisi,
        'stnk_name' => (string)$rs->nama_customer,
        'vehicle_year' => (int)$rs->tahun_produksi,
        'vehicle_color_name' => (string)$rs->warna,
        'vehicle_type_name' => $tk != null ? $tk->tipe_ahm : '',
        'engine_no' => (string)$rs->no_mesin,
        'frame_no' => (string)$rs->no_rangka,
      ];

      $params = [
        'status' => $rs->status_wo,
        'last_stats' => $rs->last_stats
      ];
      $status_pkb = status_pkb_work($params);

      $f_mk = ['id_karyawan_dealer' => $rs->id_karyawan_dealer, 'select' => 'all'];
      $mk = $this->m_sc->getKaryawan($f_mk)->row();


      $tot_waktu_berjalan_simpan = $this->m_h2_master->get_tot_waktu($rs->id_work_order);
      $last_waktu                = $this->m_h2_master->get_last_waktu_wo($rs->id_work_order);
      $tot_waktu_berjalan_real   = 0;
      if ($last_waktu->num_rows() > 0) {
        $last_waktu = $last_waktu->row();
        if ($last_waktu->stats == 'start' || $last_waktu->stats == 'resume') {
          $waktu                   = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
          $tot_waktu_berjalan_real = strtotime($waktu) - strtotime($last_waktu->set_at);
        }
      }
      $rs->etr = $rs->etr / 60;
      $te          = ($rs->etr * 60) - ($tot_waktu_berjalan_simpan + $tot_waktu_berjalan_real);

      $work_time = ROUND(($rs->total_waktu + $tot_waktu_berjalan_real) / 60);

      $progress = @($work_time / $rs->estimasi_waktu_kerja) * 100;
      if ($rs->status_wo == 'closed') {
        $progress = 100;
      }
      if ($progress > 100) {
        $progress = 100;
      }
      $is_new_task = $status_pkb['status'] == 'Available' ? 1 : 0;

      $fp = ['id_pit' => $rs->id_pit, 'id_dealer' => $rs->id_dealer];
      $pit = $this->m_sm->getPIT($fp);
      $id_pit = 0;
      if ($pit->num_rows() > 0) {
        // $id_pit = $pit->row()->id_pit_int;
        $id_pit = substr($pit->row()->id_pit, -3);
      }
      $time_estimate = $rs->estimasi_waktu_kerja - $work_time;
      $result[] = [
        'name' => $mk->nama_lengkap,
        'id_karyawan_dealer' => $mk->id_karyawan_dealer,
        'photo' => image_karyawan($mk->image, $mk->jk),
        'status' => $status_pkb['status'],
        'pkb_no' => $rs->id_work_order,
        'pit_no' => (int)$id_pit,
        'vehicle' => $vehicle,
        'progress' => ROUND($progress),
        'time_estimate' => (int)$time_estimate,
        'work_time' => $work_time,
        'job_type_name' => $desk_type,
        'is_new_task' => $is_new_task
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function start_working()
  {
    $post = $this->input->post();
    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_work_order' => $post['pkb_no']
    ];
    $get_data = $this->m_wo->get_sa_form($f_mc);
    cek_referensi($get_data, 'PKB ID');
    $wo = $get_data->row();
    if (strtolower($wo->status_wo) != 'open') {
      if (strtolower($wo->status_wo) == 'pause') {
        $msg = ['PKB dalam keadaan pause. Silahkan resume PKB'];
      } else {
        $msg = ['PKB tidak tersedia'];
      }
      send_json(msg_sc_error($msg));
    }

    $params = [
      'id_work_order' => $wo->id_work_order,
      'stats' => 'start',
      'login_id' => $this->login->id_user,
      'from' => 'api_mobile'
    ];
    $this->m_wo->setClockMekanik($params);
    $msg = ['Pekerjaan dimulai!'];
    send_json(msg_sc_success(NULL, $msg));
  }

  function pause_working()
  {
    $post = $this->input->post();
    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_work_order' => $post['pkb_no']
    ];
    $get_data = $this->m_wo->get_sa_form($f_mc);
    cek_referensi($get_data, 'PKB ID');
    $wo = $get_data->row();

    $params = [
      'id_work_order' => $wo->id_work_order,
      'stats' => 'pause',
      'login_id' => $this->login->id_user,
      'from' => 'api_mobile'
    ];
    $this->m_wo->setClockMekanik($params);
    $msg = ['Pekerjaan ditunda!'];
    send_json(msg_sc_success(NULL, $msg));
  }

  function resume_working()
  {
    $post = $this->input->post();
    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_work_order' => $post['pkb_no']
    ];
    $get_data = $this->m_wo->get_sa_form($f_mc);
    cek_referensi($get_data, 'PKB ID');
    $wo = $get_data->row();

    $params = [
      'id_work_order' => $wo->id_work_order,
      'stats' => 'resume',
      'login_id' => $this->login->id_user,
      'from' => 'api_mobile'
    ];
    $this->m_wo->setClockMekanik($params);
    $msg = ['Pekerjaan dilanjutkan!'];
    send_json(msg_sc_success(NULL, $msg));
  }

  function end_working()
  {
    $post = $this->input->post();
    $f_mc = [
      'id_dealer'  => $this->login->id_dealer,
      'id_work_order' => $post['pkb_no']
    ];
    $get_data = $this->m_wo->get_sa_form($f_mc);
    cek_referensi($get_data, 'PKB ID');
    $wo = $get_data->row();

    $params = [
      'id_work_order' => $wo->id_work_order,
      'stats' => 'closed',
      'login_id' => $this->login->id_user,
      'from' => 'api_mobile'
    ];
    $this->m_wo->setClockMekanik($params);
    $msg = ['Pekerjaan selesai!'];
    send_json(msg_sc_success(NULL, $msg));
  }
}
