<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Dashboard extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2');
    $this->load->model('m_dms');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h2_booking', 'm_book');
    $this->load->model('m_h2_dealer_laporan', 'm_h2_lap');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_sc_master', 'm_sc');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function mechanic()
  {
    $get = $this->input->get();
    $get['period_month'] = sprintf("%'.02d", $get['period_month']);
    $mandatory = [
      'limit' => 'required',
      'offset' => 'required',
      'period_year' => 'required',
      'period_month' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_mc = [
      'length' => $get['limit'],
      'offset' => $get['offset'],
      'id_dealer' => $this->login->id_dealer
    ];

    $get_data = $this->m_h2->get_pit_mekanik($f_mc)->result();
    $result = [];
    foreach ($get_data as $rs) {
      $f_sa = [
        'tahun_bulan_wo' => $get['period_year'] . '-' . $get['period_month'],
        'id_karyawan_dealer' => $rs->id_karyawan_dealer,
        'select' => ['count_sum_jasa_data'],
        'status_wo' => ['closed'],
        'id_dealer' => $this->login->id_dealer
      ];
      $real = $this->m_wo->get_sa_form($f_sa)->row()->count;

      $f_mk = [
        'id_dealer' => $this->login->id_dealer,
        'tahun_bulan' => $get['period_year'] . '-' . $get['period_month'],
        // 'id_flp_md' => $rs->id_flp_md,
        'id_karyawan_dealer' => $rs->id_karyawan_dealer,
        'select' => 'sum'
      ];
      $plan = $this->m_dms->getH23TargetManagementMekanik($f_mk)->row()->sum;

      $result[] = [
        'id' => (int)$rs->id_karyawan_dealer_int,
        'photo' => image_karyawan($rs->image, $rs->jk),
        'name' => $rs->nama_lengkap,
        'achievement' => [
          'real' => (int)$real,
          'plan' => (int)$plan
        ]
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function slot_management()
  {
    $get = $this->input->get();
    $mandatory = [
      'date' => 'required',
      'sort_by' => 'required',
      'sort_dir' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_mc = [
      'id_dealer' => $this->login->id_dealer,
      'sort_by' => $get['sort_by'],
      'sort_dir' => $get['sort_dir'],
    ];

    $get_data = $this->m_h2->get_pit_mekanik($f_mc)->result();
    // send_json($get_data);
    $result = [];
    foreach ($get_data as $rs) {
      $task_list = [];
      $f_sa = [
        'id_dealer' => $this->login->id_dealer,
        'id_pit' => $rs->id_pit,
        'id_karyawan_dealer' => $rs->id_karyawan_dealer,
        'tgl_servis' => $get['date'],
        // 'status_wo_not' => 'closed',
        // 'last_stats_not' => 'end',
        'id_work_order_not_null' => true,
        'order' => 'order_jam_asc'
      ];
      $get_task = $this->m_wo->get_sa_form($f_sa);
      foreach ($get_task->result() as $ts) {
        $params = [
          'status_wo' => $ts->status_wo,
          'status' => $ts->status_wo,
          'start_at' => $ts->start_at,
          'vehicle_offroad' => $ts->vehicle_offroad,
          'last_stats' => $ts->last_stats
        ];
        $status = status_pkb_on_pit($params);
        $task_list[] = [
          'pkb_id' => (int)$ts->id_work_order_int,
          'time' => (int)substr($ts->jam_servis, 0, 2),
          'status' => $status
        ];
      }
      $result[] = [
        'pit_no' => $rs->id_pit,
        'name' => $rs->nama_lengkap,
        'task_list' => $task_list
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function _target_fulfillment2($get)
  {
    $f_tg = [
      'tahun_bulan' => $get['tahun_bulan'],
      'id_dealer' => $get['id_dealer'],
      'select' => 'summary'
    ];
    $get_plan = $this->m_dms->getH23TargetManagementAHASS($f_tg);
    $plan = [
      'ue' => 0,
      'service_revenue' => 'IDR 0',
      'oil_revenue' => 'IDR 0',
      'non_oil_revenue' => 'IDR 0',
    ];
    if ($get_plan->num_rows() > 0) {
      $get_plan = $get_plan->row();
      $plan = [
        'ue' => (int)$get_plan->target_ue,
        'service_revenue' => 'IDR ' . custom_number_format($get_plan->target_revenue),
        'oil_revenue' => 'IDR ' . custom_number_format($get_plan->target_oli),
        'non_oil_revenue' => 'IDR ' . custom_number_format($get_plan->target_non_oli),
      ];
    }

    $f_sa = [
      'tahun_bulan_wo' => $get['tahun_bulan'],
      'select' => ['count_sum_jasa_data'],
      'status_wo' => ['closed'],
      'id_dealer' => $get['id_dealer']
    ];
    $sa = $this->m_wo->get_sa_form($f_sa)->row();

    $f_oli = [
      'tahun_bulan' => $get['tahun_bulan'],
      'select' => 'sum_oli',
      'sum_final_result' => true,
      'id_dealer' => $get['id_dealer']
    ];
    // $real_oli_revenue = $this->m_h2_lap->getPendapatanHarianServis($f_oli);
    $real_oli_revenue=0;

    $f_non_oli = [
      'tahun_bulan' => $get['tahun_bulan'],
      'select' => 'sum_non_oli',
      'sum_final_result' => true,
      'id_dealer' => $get['id_dealer']
    ];
    // $real_non_oli_revenue = $this->m_h2_lap->getPendapatanHarianServis($f_non_oli);
    $real_non_oli_revenue = 0;

    return [
      'unit_entry' => [
        'real' => (int)$sa->count,
        'plan' => $plan['ue']
      ],
      'service_revenue' => [
        'real' => 'IDR ' . custom_number_format($sa->total_jasa),
        'plan' => $plan['service_revenue']
      ],
      'oil_revenue' => [
        'real' => 'IDR ' . custom_number_format($real_oli_revenue),
        'plan' => $plan['oil_revenue']
      ],
      'non_oil_revenue' => [
        'real' => 'IDR ' . custom_number_format($real_non_oli_revenue),
        'plan' => $plan['non_oil_revenue']
      ],
    ];
  }
  function _target_fulfillment($get)
  {
    $f_tg = [
      'tahun_bulan' => $get['tahun_bulan'],
      'id_dealer' => $get['id_dealer'],
      'select' => 'summary'
    ];
    $get_plan = $this->m_dms->getH23TargetManagementAHASS($f_tg);
    $plan = [
      'ue' => 0,
      'service_revenue' => 'IDR 0',
      'oil_revenue' => 'IDR 0',
      'non_oil_revenue' => 'IDR 0',
    ];
    if ($get_plan->num_rows() > 0) {
      $get_plan = $get_plan->row();
      $plan = [
        'ue' => (int)$get_plan->target_ue,
        'service_revenue' => 'IDR ' . custom_number_format($get_plan->target_revenue),
        'oil_revenue' => 'IDR ' . custom_number_format($get_plan->target_oli),
        'non_oil_revenue' => 'IDR ' . custom_number_format($get_plan->target_non_oli),
      ];
    }

    $f_sa = [
      'tahun_bulan_wo' => $get['tahun_bulan'],
      'select' => ['count_sum_jasa_data'],
      'id_dealer' => $get['id_dealer'],
      'status_wo' => ['closed']
    ];
    $sa = $this->m_wo->get_sa_form($f_sa)->row();

    // $f_oli = [
    //   'tahun_bulan' => $get['tahun_bulan'],
    //   'id_dealer' => $get['id_dealer']
    // ];
    // $real_oli_revenue = $this->m_h2_lap->getNSCTotal($f_oli);

    $f_sa['njb_or_nsc_not_null'] = true;
    $f_sa['njb_in_receipt'] = true;
    $sa_jasa = $this->m_wo->get_sa_form($f_sa)->row();
    $f_oli = [
      'tahun_bulan' => $get['tahun_bulan'],
      'select' => 'sum_oli',
      'sum_final_result' => true,
      'id_dealer' => $get['id_dealer']
    ];
    // $real_oli_revenue = $this->m_h2_lap->getPendapatanHarianServis($f_oli);
    $real_oli_revenue =0;

    $f_non_oli = [
      'tahun_bulan' => $get['tahun_bulan'],
      'select' => 'sum_non_oli',
      'sum_final_result' => true,
      'id_dealer' => $get['id_dealer']
    ];
    // $real_non_oli_revenue = $this->m_h2_lap->getPendapatanHarianServis($f_non_oli);
    $real_non_oli_revenue = 0;

    return [
      'unit_entry' => [
        'real' => (int)$sa->count,
        'plan' => $plan['ue']
      ],
      'service_revenue' => [
        'real' => 'IDR ' . custom_number_format($sa_jasa->total_jasa),
        'plan' => $plan['service_revenue']
      ],
      'oil_revenue' => [
        'real' => 'IDR ' . custom_number_format($real_oli_revenue),
        'plan' => $plan['oil_revenue']
      ],
      'non_oil_revenue' => [
        'real' => 'IDR ' . custom_number_format($real_non_oli_revenue),
        'plan' => $plan['non_oil_revenue']
      ],
    ];
  }

  function _dashboard_service($get)
  {
    $type = [
      'complete_service' => ['CS'],
      'light_service' => ['LS'],
      'oil_replacement' => ['OR+'],
      'heavy_repair' => ['HR'],
      'light_repair' => ['LR'],
      'claim_c2' => ['C2'],
      'job_return' => ['JR'],
      'other' => ['OTHER'],
    ];
    $result = [];
    foreach ($type as $key => $value) {
      $f = [
        'tahun_bulan_njb' => $get['tahun_bulan'],
        'id_type_in' => arr_in_sql($value),
        'id_dealer' => $get['id_dealer']
      ];

      $count = 1;
      if($get['id_dealer']!=103){
        $count = $this->m_wo->getMekanikHistoryServis($f);
      }

      $f = [
        'periode_njb' => $get['date_range_m_min1'],
        'id_type_in' => arr_in_sql($value),
        'id_dealer' => $get['id_dealer']
      ];
      // send_json($f);
      
      $count_last_month=1;
      if($get['id_dealer']!=103){
        $count_last_month = $this->m_wo->getMekanikHistoryServis($f);
      }

      $percentage_last_month = @($count / $count_last_month) - 1;

      $result[$key] = [
        'value' => (int)$count,
        'percentage_last_month' => (string)@number_format(ROUND($percentage_last_month), 0)
      ];
    }
    return $result;
  }

  function _free_maintenance($get)
  {
    $f_so = [
      'bulan_so' => $get['tahun_bulan'],
      'id_dealer' => $get['id_dealer'],
      'tgl_cetak_invoice_not_null' => true,
      'select' => 'count'
    ];
    $sales_now = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    $f_so = [
      'bulan_so' => $get['tahun_bulan_lalu'],
      'id_dealer' => $get['id_dealer'],
      'tgl_cetak_invoice_not_null' => true,
      'select' => 'count'
    ];
    $sales_last = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;
    $percentage_last_month = number_format(@($sales_now / $sales_last) - 1, 0);


    $result['last_month_unit_sales'] = [
      'value' => (int)$sales_last,
      'percentage_last_month' => (string)$percentage_last_month
    ];

    $type = [
      'kpb1' => ['ASS1'],
      'kpb2' => ['ASS2'],
      'kpb3' => ['ASS3'],
      'kpb4' => ['ASS4'],
    ];

    foreach ($type as $key => $value) {
      $f = [
        'tahun_bulan_njb' => $get['tahun_bulan'],
        'id_type_in' => arr_in_sql($value),
        'id_dealer' => $get['id_dealer']
      ];

      $count = 1;
      if($get['id_dealer']!=103){
        $count = $this->m_wo->getMekanikHistoryServis($f);
      }
      
      $f = [
        'periode_njb' => $get['date_range_m_min1'],
        'id_type_in' => arr_in_sql($value),
        'id_dealer' => $get['id_dealer']
      ];

      $count_last_month = 1;
      if($get['id_dealer']!=103){
        $count_last_month = $this->m_wo->getMekanikHistoryServis($f);
      }

      $percentage_last_month = @($count / $count_last_month) - 1;

      $result[$key] = [
        'value' => (int)$count,
        'percentage_last_month' => (string)@number_format(ROUND($percentage_last_month), 0)
      ];
    }
    return $result;
  }
  function _customer_satisfaction($get)
  {
    $f_sa = [
      'tahun_bulan_wo' => $get['tahun_bulan'],
      'select' => ['count_sum_jasa_data'],
      'status_wo' => ['closed'],
      'id_dealer' => $get['id_dealer']
    ];
    $sa = $this->m_wo->get_sa_form($f_sa)->row();

    $f_sa = [
      'tahun_bulan' => $get['tahun_bulan'],
      'po_type' => 'HLO',
      'id_dealer' => $get['id_dealer'],
      'select' => 'count'
    ];
    $hotline = $this->m_wo->getH3PODealer($f_sa)->row()->count;

    $f_sa = [
      'tahun_bulan_sa' => $get['tahun_bulan'],
      'select' => ['count_sa'],
      'id_dealer' => $get['id_dealer'],
      'status_form' => ['cancel']
    ];
    $reject = $this->m_wo->get_sa_form($f_sa)->row();

    $f_sa = [
      'tahun_bulan_servis' => $get['tahun_bulan'],
      'select' => 'count',
      'id_dealer' => $get['id_dealer'],
      'left_join_queue' => true,
      'id_antrian_null' => true
    ];
    $not_come = $this->m_book->fetch_booking($f_sa)->row()->count;

    $f_sa = [
      'tahun_bulan_wo' => $get['tahun_bulan'],
      'select' => ['sum_waktu_estimasi_aktual'],
      'id_dealer' => $get['id_dealer'],
    ];
    $est = $this->m_wo->get_sa_form($f_sa)->row();
    if ($est->actual < $est->etr) {
      $sea = (int)@(($est->actual / $est->etr) * 100);
    } else {
      $sea = (int)@(($est->actual / $est->etr) - 1) * 100;
    }
    return [
      'service_est_accuracy' => $sea,
      'total_unit' => (int)$sa->count,
      'cart_hotline' => (int)$hotline,
      'reject' => (int)$reject->count_sa,
      'not_come' => (int)$not_come
    ];
  }

  function ahass_report()
  {
    $get = $this->input->get();
    $get['period_month'] = sprintf("%'.02d", $get['period_month']);
    $mandatory = [
      'period_year' => 'required',
      'period_month' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $get['id_dealer'] = $this->login->id_dealer;
    $get['tahun_bulan'] = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']);
    $get['tahun_bulan_lalu'] = bulan_kemarin($get['tahun_bulan'] . '-01');

    $end_min1 = tanggal_kemarin($get['tahun_bulan'] . get_d());
    if (sprintf("%'.02d", $get['period_month']) != get_m()) {
      $end_min1 = date('Y-m-t', strtotime($get['tahun_bulan_lalu'] . '-01'));
    }
    $get['date_range_m_min1'] = [
      'start' => bulan_kemarin($get['tahun_bulan'] . '-01') . '-01',
      'end' => $end_min1
    ];
    // send_json($get);
    // $result = [
    //   'target_fulfillment' => $this->_target_fulfillment($get), //ok
    //   'dashboard_service' => $this->_dashboard_service($get),
    //   'free_maintenance' => $this->_free_maintenance($get),
    //   'customer_satisfaction' => $this->_customer_satisfaction($get),
    // ];
    $result = [
      'target_fulfillment' => $this->_target_fulfillment($get), //ok//
      // 'target_fulfillment' => '',
      // 'dashboard_service' => '',
      'dashboard_service' => $this->_dashboard_service($get),
      // 'free_maintenance' => '',
      'free_maintenance' => $this->_free_maintenance($get),
      'customer_satisfaction' => $this->_customer_satisfaction($get),
      // 'customer_satisfaction' => '',
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function detail_unit_entry()
  {
    $get = $this->input->get();
    $get['period_month'] = sprintf("%'.02d", $get['period_month']);

    $mandatory = [
      'period_year' => 'required',
      'period_month' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $start = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']) . '-01';
    $end = date("Y-m-t", strtotime($start));
    while (strtotime($start) <= strtotime($end)) {
      $no = date("d", strtotime($start));
      $f_sa = [
        'tgl_njb' => $start,
        'select' => ['count_sum_grand_data'],
        'id_dealer' => $this->login->id_dealer
      ];
      $sa = $this->m_wo->get_sa_form($f_sa)->row();
      $result[] = [
        'date_no' => (int)$no,
        'unit' => (int)$sa->count,
        'revenue' => (float)$sa->total
      ];
      $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
    }
    send_json(msg_sc_success($result, NULL));
  }

  function detail_service_revenue()
  {
    $get = $this->input->get();
    $mandatory = [
      'period_year' => 'required',
      'period_month' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $start = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']) . '-01';
    $end = date("Y-m-t", strtotime($start));
    while (strtotime($start) <= strtotime($end)) {
      $no = date("d", strtotime($start));
      $f_sa = [
        'tgl_njb' => $start,
        'select' => ['count_sum_jasa_data'],
        'id_dealer' => $this->login->id_dealer
      ];
      $sa = $this->m_wo->get_sa_form($f_sa)->row();
      $result[] = [
        'date_no' => (int)$no,
        'unit' => (int)$sa->count,
        'revenue' => (int)$sa->total_jasa,
      ];
      $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
    }
    send_json(msg_sc_success($result, NULL));
  }

  function detail_oil_revenue()
  {
    $get = $this->input->get();
    $mandatory = [
      'period_year' => 'required',
      'period_month' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $start = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']) . '-01';
    $end = date("Y-m-t", strtotime($start));
    while (strtotime($start) <= strtotime($end)) {
      $no = date("d", strtotime($start));

      //indirect = Penjualan Melalui WO
      $f_sa = [
        'tgl_njb' => $start,
        'select' => ['count_data'],
        'id_dealer' => $this->login->id_dealer
      ];
      $sa = $this->m_wo->get_sa_form($f_sa)->row();

      $f_sa = [
        'tgl_transaksi' => $start,
        'select' => 'sum_oli',
        'sum_final_result' => true,
        'id_dealer' => $this->login->id_dealer
      ];
      // $total = $this->m_h2_lap->getPendapatanHarianServis($f_sa);
      $indirect[] = [
        'date_no' => (int)$no,
        'unit' => (int)$sa->count,
        // 'revenue' => (float)$total,
        'revenue' => 0,
      ];


      //Direct = Penjualan Langsung
      $f_sa = [
        'tgl_transaksi' => $start,
        'select' => 'sum_oli',
        'id_dealer' => $this->login->id_dealer
      ];
      // $total = $this->m_h2_lap->getPendapatanHarianServisSalesParts($f_sa)->row();
      $direct[] = [
        'date_no' => (int)$no,
        // 'unit' => (int)$total->total_qty,
        'unit' => 0,
        // 'revenue' => (float)$total->total,
        'revenue' => 0
      ];

      $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
    }
    $result = [
      'direct' => $direct,
      'indirect' => $indirect,
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function detail_non_oil_revenue()
  {
    $get = $this->input->get();
    $mandatory = [
      'period_year' => 'required',
      'period_month' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $start = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']) . '-01';
    $end = date("Y-m-t", strtotime($start));
    while (strtotime($start) <= strtotime($end)) {
      $no = date("d", strtotime($start));
      $f_sa = [
        'tgl_njb' => $start,
        'select' => ['count_data'],
        'id_dealer' => $this->login->id_dealer
      ];
      $sa = $this->m_wo->get_sa_form($f_sa)->row();

      $f_sa = [
        'tgl_transaksi' => $start,
        'select' => 'sum_non_oli',
        'sum_final_result' => true,
        'id_dealer' => $this->login->id_dealer
      ];
      // $total = $this->m_h2_lap->getPendapatanHarianServis($f_sa);
      $indirect[] = [
        'date_no' => (int)$no,
        'unit' => (int)$sa->count,
        // 'revenue' => (float)$total,
        'revenue' => 0,
      ];

      $f_sa = [
        'tgl_transaksi' => $start,
        'select' => 'sum_part',
        'id_dealer' => $this->login->id_dealer
      ];
      // $total = $this->m_h2_lap->getPendapatanHarianServisSalesParts($f_sa)->row();
      $direct[] = [
        'date_no' => (int)$no,
        // 'unit' => (int)$total->total_qty,
        // 'revenue' => (float)$total->total,
        'unit' => 0,
        'revenue' =>0
      ];

      $start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
    }
    $result = [
      'direct' => $direct,
      'indirect' => $indirect,
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function pit_view()
  {
    $f = [
      'id_dealer' => $this->login->id_dealer,
      'id_work_order_not_null' => true
    ];
    $pit_wo = $this->m_wo->getPITdanWO($f)->result();
    $id_work_order_multi =  [];
    foreach ($pit_wo as $pit) {
      $id_work_order_multi[] = $pit->id_work_order_int;
      $f_sa = [
        'id_work_order_int' => $pit->id_work_order_int,
        'id_dealer' => $this->login->id_dealer,
      ];
      $pkb = $this->m_wo->get_sa_form($f_sa)->row();
      $customer = [
        'name' => $pkb->nama_customer,
        'phone' => $pkb->no_hp,
        'address' => $pkb->alamat,
        'district' => $pkb->kecamatan,
      ];
      $wo_pekerjaan = $this->m_wo->getWOPekerjaan($f_sa)->row();
      $job_type_name = $wo_pekerjaan->desk_type;
      $job_type_code = $wo_pekerjaan->id_type;
      $time_estimate = $pkb->estimasi_waktu_kerja . ' Menit';
      $params = [
        'status' => $pkb->status_wo,
        'last_stats' => $pkb->last_stats
      ];
      $status_pkb = status_pkb_apps($params);
      $result[] = [
        'id' => (int)$pit->id_work_order_int,
        'pkb_no' => (int)substr($pkb->id_work_order, -5),
        'pkb_date' => $pkb->tgl_wo,
        'customer' => $customer,
        'job_type_name' => $job_type_name,
        'job_type_code' => $job_type_code,
        'status' => $status_pkb['status'],
        'status_color' => $status_pkb['status_color'],
        'time_estimate' => $time_estimate,
        'pit_no' => (int)substr($pit->id_pit, 2),
        'document' => (string)$pkb->document,
      ];
    }
    $outstanding = [];
    foreach ($id_work_order_multi as $wo) {
      $f_sa = [
        'id_dealer' => $this->login->id_dealer,
        // 'id_work_order_not_null' => true,
        // 'start_at_null' => true,
        'select' => ['concat_tipe_pekerjaan'],
        'id_work_order_int' => $wo
      ];
      $out = $this->m_wo->get_sa_form($f_sa)->row();
      $waiting_time = 0;
      if ($out->waktu_kedatangan != NULL) {
        $wo_created = $this->db->query("SELECT RIGHT(created_at,8) created FROM tr_h2_wo_dealer WHERE id_work_order_int=$wo")->row();
        $wo_created = strtotime($wo_created->created);
        $datang = strtotime($out->waktu_kedatangan);
        $waiting_time = ($wo_created - $datang) / 60;
      }
      $outstanding[] = [
        'pkb_id' => (int)substr($out->id_work_order, -5),
        'job_type_name' => $out->concat_tipe_pekerjaan,
        'waiting_time' => (int)$waiting_time . ' Menit'
      ];
    }
    $data = [
      'pit_load' => isset($result) ? $result : [],
      'outstanding_pkb' => $outstanding
    ];
    send_json(msg_sc_success($data, NULL));
  }

  function _mechanic_achievement_this_month($get)
  {
    $get['tahun_bulan'] = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']);
    $get['select']      = 'sum';
    $plan = $this->m_dms->getH23TargetManagementMekanik($get)->row()->sum;

    $get['tahun_bulan_wo']  = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']);
    $get['select']          = 'count';
    // $get['no_njb_not_null'] = true;
    $real = $this->m_wo->getWorkOrder($get)->row()->count;

    return [
      'plan' => (int)$plan,
      'real' => (int)$real
    ];
  }

  function _mechanic_today_summary($get)
  {
    $filter  = [
      'id_dealer' => $get['id_dealer'],
      'id_karyawan_dealer' => $get['id_karyawan_dealer'],
      'tanggal' => get_ymd()
    ];
    $row  = $this->m_dms->getMechanicProfile($filter)->row();
    return [
      'task_done' => (int)$row->ue_selesai_today,
      'productivity_mean' => round($row->produktif_rata2, 2),
      'mechanic_revenue_mean' => (float)$row->pendapatan_mekanik,
      'ahass_revenue_mean' => (float)$row->pendapatan_ahass
    ];
  }

  function _mechanic_current_task($get)
  {
    $filter = [
      'id_karyawan_dealer' => $get['id_karyawan_dealer'],
      'id_dealer' => $get['id_dealer'],
      'select' => ['concat_tipe_pekerjaan'],
      'tgl_servis' => get_ymd()
    ];
    $get = $this->m_wo->get_sa_form($filter);
    if ($get->num_rows() > 0) {
      $wo = $get->row();

      $f_sa = [
        'id_work_order_int' => $wo->id_work_order_int,
        'id_dealer' => $this->login->id_dealer,
      ];
      $wo_pekerjaan = $this->m_wo->getWOPekerjaan($f_sa)->row();
      $job_type_name = $wo_pekerjaan->desk_type;
      $job_type_color = $wo_pekerjaan->color;
      return [
        'pkb_id' => (int)$wo->id_work_order_int,
        'job_type_name' => $job_type_name,
        'job_type_color' => $job_type_color,
        'pit_no' => $wo->id_pit,
        'time_estimate' => $wo->estimasi_waktu_kerja . 'menit',
      ];
    } else {
      return [
        'pkb_id' => 0,
        'job_type_name' => '',
        'job_type_color' => '',
        'pit_no' => '',
        'time_estimate' => '',
      ];
    }
  }

  function _mechanic_task_diagram($get)
  {
    return $this->m_dms->mechanic_diagram($get);
  }

  function _mechanic_task_history($get)
  {
    $filter = [
      'id_karyawan_dealer' => $get['id_karyawan_dealer'],
      'id_dealer' => $get['id_dealer'],
      'status_wo' => ['closed'],
      'select' => ['concat_tipe_pekerjaan'],
      'tahun_bulan_wo' => $get['tahun_bulan_wo']
    ];
    $get = $this->m_wo->get_sa_form($filter);
    $result = [];
    foreach ($get->result() as $rs) {
      $result[] = [
        'pkb_id' => (int)$rs->id_work_order_int,
        'job_type_name' => $rs->concat_tipe_pekerjaan,
        'time_estimate' => $rs->estimasi_waktu_kerja . 'menit',
        'time_actual' => round(($rs->total_waktu / 60), 1) . 'menit'
      ];
    }
    return $result;
  }

  function mechanic_detail()
  {
    $get = $this->input->get();
    $mandatory = [
      'period_year' => 'required',
      'mechanic_id' => 'required',
      'period_month' => 'required',
    ];
    $get['id_dealer'] = $this->login->id_dealer;
    cek_mandatory($mandatory, $get);
    $f_mc = [
      'id_karyawan_dealer_int' => $get['mechanic_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $mekanik = $this->m_h2->fetch_mekanik($f_mc);
    cek_referensi($mekanik, 'Mechanic ID');
    $mekanik = $mekanik->row();
    $get_training = $this->m_sc->getKaryawanTraining($f_mc);
    $training = '';
    if ($get_training->num_rows() > 0) {
      $training = $get_training->row()->training;
    }
    $get['id_karyawan_dealer'] = $mekanik->id_karyawan_dealer;
    $get['id_flp_md'] = $mekanik->id_flp_md;
    $get['tahun_bulan_wo']  = $get['period_year'] . '-' . sprintf("%'.02d", $get['period_month']);
    $result = [
      'name' => $mekanik->nama_lengkap,
      'photo' => image_karyawan($mekanik->image, 'laki-laki'),
      'training_status' => (string) $training,
      'dealer_name' => $mekanik->nama_dealer,
      'achievement_this_month' => $this->_mechanic_achievement_this_month($get), //ok
      'today_summary' => $this->_mechanic_today_summary($get), //ok
      'current_task' => $this->_mechanic_current_task($get),
      'task_diagram' => $this->_mechanic_task_diagram($get), //ok
      'task_history' => $this->_mechanic_task_history($get), //ok
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
