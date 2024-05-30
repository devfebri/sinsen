<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Booking extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2_booking', 'm_booking');
    $this->load->model('m_h2_api');
    $this->load->model('m_h2_master');
    $this->load->model('m_sm_master', 'm_master');
    $this->load->model('m_master_unit', 'm_unit');


    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function slot_view()
  {
    $get = $this->input->get();
    $mandatory = ['period_month' => 'required', 'period_year' => 'required'];
    $id_dealer = $this->login->id_dealer;
    // send_json($get);
    cek_mandatory($mandatory, $get);
    $digit_bulan   = sprintf("%02d", $get['period_month']);
    $year = $get['period_year'];
    $start_date = $year . '-' . $digit_bulan . '-1';
    $end_date   = $year . '-' . $digit_bulan . '-' . date('t', strtotime($start_date));
    $data = '';
    $pit = $this->db->get_where('ms_h2_pit', ['id_dealer' => $id_dealer, 'booking' => 1])->result();

    $set       = $this->db->get('ms_h2_setting_jadwal')->row();
    $begin    = new DateTime($set->waktu_mulai);
    date_add($begin, date_interval_create_from_date_string("-$set->selisih_waktu min"));
    $end      = new DateTime($set->waktu_selesai);
    $set->selisih_waktu = 60; //Di Set Perjam
    $interval = DateInterval::createFromDateString("$set->selisih_waktu min");

    $times    = new DatePeriod($begin, $interval, $end);
    foreach ($times as $time) {
      $waktu[]     = $time->add($interval)->format('H');
    }

    //Looping Tanggal Dalam 1 bulan
    while (strtotime($start_date) <= strtotime($end_date)) {
      $tanggal  = date('Y-m-d', strtotime($start_date));
      $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
      $pit_data = [];
      //Looping Data Pit
      foreach ($pit as $pt) {
        $book_hour = [];
        //Looping Waktu
        foreach ($waktu as $wkt) {
          $f_booking = [
            'id_pit' => $pt->id_pit,
            'tgl_servis' => $tanggal,
            'jam_servis_like' => $wkt,
            'id_dealer' => $this->login->id_dealer,
            'status_booking_not' => 'cancel'
          ];
          $cek = $this->m_booking->fetch_booking($f_booking);
          if ($cek->num_rows() > 0) {
            foreach ($cek->result() as $ck) {
              $f_b = [
                'id_booking' => $ck->id_booking,
                'limit' => 'LIMIT 1',
                'id_dealer' => $this->login->id_dealer
              ];
              $type = $this->m_booking->getBookingDetailService($f_b);
              if ($type->num_rows() == 0) {
                $type = new stdClass();
                $type->desk_type = $ck->desc_type;
                $type->id_type = $ck->id_type;
                $type->color = $ck->color;
              } else {
                $type = $type->row();
              }
              $book_hour[] = [
                'hour' => (int)$wkt,
                'job_type_name' => $type->id_type,
                'job_type_color' => $type->color
              ];
            }
          } else {
            // $book_hour[] = [
            //   'hour' => (int)$wkt,
            //   'job_type_name' => '',
            //   'job_type_color' => ''
            // ];
          }
        }
        $pit_data[] = [
          'pit_no' => (int)$pt->id_pit_int,
          'book_hour' => $book_hour
        ];
      }
      $data[] = [
        'date' => $tanggal,
        'pit_data' => $pit_data,
        'total_pit' => count($pit)
      ];
    }

    send_json(msg_sc_success($data, NULL));
  }

  function list_view()
  {
    $get = $this->input->get();
    $mandatory = [
      'offset' => 'required',
      'limit' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_booking = [
      'tgl_servis' => $get['booking_date'],
      'offset' => $get['offset'],
      'length' => $get['limit'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'all',
      'status_booking_not' => 'cancel'
    ];

    $get_data = $this->m_booking->fetch_booking($f_booking);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $customer = [
        'name' => $rs->nama_customer,
        'phone' => $rs->no_hp,
        'email' => (string)$rs->email,
      ];
      $vehicle = [
        'police_no' => $rs->no_polisi,
        'stnk_name' => '',
        'vehicle_year' => $rs->tahun_produksi,
        'vehicle_color_name' => $rs->only_warna,
        'vehicle_type_name' => $rs->id_tipe_kendaraan,
        'vehicle_product_name' => $rs->tipe_ahm,
        'engine_no' => $rs->no_mesin,
        'frame_no' => $rs->no_rangka,
      ];

      $f_b = [
        'id_booking' => $rs->id_booking,
        'limit' => 'LIMIT 1',
        'id_dealer' => $this->login->id_dealer
      ];
      $type = $this->m_booking->getBookingDetailService($f_b);
      if ($type->num_rows() == 0) {
        $type = new stdClass();
        $type->desk_type = $rs->desc_type;
        $type->id_type = $rs->id_type;
        $type->color = $rs->color;
      } else {
        $type = $type->row();
      }

      $result[] = [
        'booking_id' => (int)$rs->id_booking_int,
        'booking_no' => $rs->id_booking,
        'booking_date' => $rs->tgl_booking,
        'book_hour' => $rs->jam_servis,
        'job_type_name' => $type->desk_type,
        'job_type_code' => $type->id_type,
        'job_type_color' => $type->color,
        'pit_no' => (int)$rs->id_pit_int,
        'vehicle' => $vehicle,
        'customer' => $customer,
        'carrier_name' => (string)$rs->carrier_name,
        'carrier_phone' => (string)$rs->carrier_phone,
        'carrier_email' => '',
        'carrier_address' => '',
        'service_date' => $rs->tgl_servis,
        'service_time' => $rs->jam_servis,
        'complaint' => $rs->keluhan,
      ];
    }

    send_json(msg_sc_success($result, NULL));
  }

  function create()
  {

    ini_set("allow_url_fopen", true);
    $post = json_decode(file_get_contents('php://input'), true);
    $id_dealer = $this->login->id_dealer;
    $mandatory = [
      'pit_no'           => 'required',
      'police_no'        => 'required',
      'engine_no'        => 'required',
      'frame_no'         => 'required',
      // 'service_type'  => 'required',
      'customer_name'    => 'required',
      'customer_phone'   => 'required',
      'customer_email'   => 'required',
      'carrier_name'     => 'required',
      'carrier_phone'    => 'required',
      'service_date'     => 'required',
      'service_time'     => 'required',
      // 'complaint'        => 'required',
      'vehicle_color_id' => 'required',
      'vehicle_type_id'  => 'required',
    ];
    cek_mandatory($mandatory, $post);
    $id_booking = $this->m_h2_master->get_id_booking();

    $tanggal = get_ymd();
    if (strtotime($post['service_date']) <= strtotime($tanggal)) {
      $msg = ['Tanggal servis harus lebih besar dari hari ini !'];
      send_json(msg_sc_error($msg));
    }
    $f = ['no_polisi' => $post['police_no']];
    $cek_h23 = $this->m_h2_api->getCustomerH23($f);
    $cek_h1idv = $this->m_h2_api->cekCustomerH1Individu($f);
    $cek_h1gc = $this->m_h2_api->cekCustomerH1GC($f);
    $f = ['id_dealer' => $id_dealer];
    if ($cek_h23->num_rows()) {
      $cust = $cek_h23->row();
      $customer_from = 'h23';
      $id_customer   = $cust->id_customer;
    } elseif ($cek_h1idv->num_rows()) {
      $cust = $cek_h1idv->row();
      $customer_from = 'h1';
      $id_customer   = $this->m_h2_master->get_id_customer($f);
    } elseif ($cek_h1gc->num_rows()) {
      $cust = $cek_h1gc->row();
      $customer_from = 'h1';
      $id_customer   = $this->m_h2_master->get_id_customer($f);
    } else {
      // $msg = ['Data Customer Tidak Ditemukan'];
      // send_json(msg_sc_error($msg));
      $f = ['id_dealer' => $this->login->id_dealer];
      $customer_from = 'baru';
      $id_customer   = $this->m_h2_master->get_id_customer($f);
    }

    $f_wr = [
      'id_warna_int' => $post['vehicle_color_id'],
      'select' => 'all'
    ];
    $id_warna = $this->m_unit->getWarna($f_wr)->row()->id_warna;

    $f_wr = [
      'id_tipe_kendaraan_int' => $post['vehicle_type_id'],
      'select' => 'all'
    ];

    $id_tipe_kendaraan = $this->m_unit->getTipeKendaraan($f_wr)->row()->id_tipe_kendaraan;
    // $no = 1;
    // foreach ($tipe_kendaraan as $tk) {
    //   if ($no == $vehicle_type_id) {
    //     $id_tipe_kendaraan = $tk->id_tipe_kendaraan;
    //     break;
    //   }
    //   $no++;
    // }

    if ($customer_from == 'h1' || $customer_from == 'baru') {

      $ins_cust['id_customer']   = $id_customer;
      $ins_cust['nama_customer'] = $post['customer_name'];
      $ins_cust['no_hp']         = $post['customer_phone'];
      $ins_cust['email']         = $post['customer_email'];
      $ins_cust['no_mesin']      = $post['engine_no'];
      $ins_cust['no_rangka']     = $post['frame_no'];
      $ins_cust['no_polisi']     = $post['police_no'];
      $ins_cust['id_tipe_kendaraan'] = $id_tipe_kendaraan;
      $ins_cust['id_warna']   = $id_warna;
      $ins_cust['id_dealer']  = $this->login->id_dealer;
      $ins_cust['created_at'] = waktu_full();
      $ins_cust['created_by'] = $this->login->id_user;
      $ins_cust['sumber_data'] = $customer_from;

      if ($customer_from == 'h1') {
        $this->load->model('m_h1_dealer_spk', 'spk');
        $fsp = [
          'id_dealer' => $id_dealer,
          'no_spk' => $cust->no_spk,
          'select' => 'formulir_cdb',
          'left_join_cdb' => true,
          'left_join_jenis_pembelian' => true,
          'join_status_rumah' => true,
          'join_wilayah' => true,
          'join_wilayah_instansi' => true,
          'join_wilayah_correspondence' => true,
          'join_so' => true,
        ];
        $spk = $this->spk->getSPKIndividu($fsp)->row();
        $ins_cust['no_spk'] = $spk->no_spk;
        if ($spk != NULL) {
          $ins_cust['id_kelurahan'] = $spk->id_kelurahan;
          $ins_cust['id_kelurahan_identitas'] = $spk->id_kelurahan2;
          $ins_cust['id_kecamatan'] = $spk->id_kecamatan;
          $ins_cust['id_kabupaten'] = $spk->id_kabupaten;
          $ins_cust['id_provinsi'] = $spk->id_provinsi;
          $ins_cust['alamat'] = $spk->alamat;
          $ins_cust['email'] = $spk->email;
          $ins_cust['id_dealer_h1'] = $spk->id_dealer;
          $ins_cust['alamat_identitas'] = $spk->alamat2;
          $ins_cust['id_provinsi'] = $spk->id_provinsi;
          $ins_cust['jenis_identitas'] = 'ktp';
          $ins_cust['no_identitas'] = $spk->no_ktp;
          $ins_cust['no_hp'] = $spk->no_hp;
          $ins_cust['no_spk'] = $spk->no_spk;
          $ins_cust['nama_stnk'] = $spk->nama_bpkb;
          $ins_cust['longitude'] = $spk->longitude;
          $ins_cust['latitude'] = $spk->latitude;
          $ins_cust['id_agama'] = $spk->id_agama;
          $ins_cust['id_pekerjaan'] = $spk->id_pekerjaan;
          $ins_cust['facebook'] = $spk->facebook;
          $ins_cust['twitter'] = $spk->twitter;
          $ins_cust['instagram'] = $spk->instagram;
          $ins_cust['tgl_lahir'] = $spk->tgl_lahir;
          $ins_cust['jenis_kelamin'] = $spk->jenis_kelamin == 'Pria' ? 'Laki-laki' : 'Perempuan';
        }
      }
    } else {
      $upd_cust['id_tipe_kendaraan'] = $id_tipe_kendaraan;
      $upd_cust['id_warna']   = $id_warna;
      $upd_cust['nama_customer'] = $post['customer_name'];
      $upd_cust['no_hp']         = $post['customer_phone'];
      $upd_cust['email']         = $post['customer_email'];
      $upd_cust['no_mesin']      = $post['engine_no'];
      $upd_cust['no_rangka']     = $post['frame_no'];
      $upd_cust['no_polisi']     = $post['police_no'];
    }

    foreach ($post['service_type'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_jasa_or_id_jasa_int' => $st,
      ];
      $get_data = $this->m_h2_master->fetch_jasa_h2_dealer($f);
      if ($get_data->num_rows() > 0) {
        $res_type = $get_data->row();
      } else {
        $msg = ['Service Type Tidak Ditemukan'];
        send_json(msg_sc_error($msg));
      }
      $insert_detail[] = [
        'id_booking' => $id_booking,
        'id_jasa' => $res_type->id_jasa,
        'harga' => $res_type->harga_dealer,
      ];
      if ($key == 0) {
        $id_type = $res_type->id_type;
      }
    }

    $list_pit = $this->db->get_where('ms_h2_pit', ['id_dealer' => $this->login->id_dealer, 'booking' => 1])->result();
    $pt = 1;
    foreach ($list_pit as $rs) {
      if ($pt == $post['pit_no']) {
        $post['id_pit'] = $rs->id_pit;
        break;
      }
      $pt++;
    }

    $insert = [
      'id_booking'    => $id_booking,
      'id_dealer'     => $id_dealer,
      'id_customer'   => $id_customer,
      'id_pit'        => $post['id_pit'],
      'tgl_servis'    => $post['service_date'],
      'jam_servis'    => $post['service_time'],
      'keluhan'       => $post['complaint'],
      'carrier_phone' => $post['carrier_phone'],
      'carrier_name'  => $post['carrier_name'],
      'created_at'    => waktu_full(),
      'created_by'    => $this->login->id_user,
      'status'        => 'draft',
      'customer_from' => $customer_from,
      'id_type'       => $id_type
    ];


    // $tes = [
    //   'insert' => $insert,
    //   'insert_detail' => $insert_detail,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    if (isset($ins_cust)) {
      $this->db->insert('ms_customer_h23', $ins_cust);
    }
    if (isset($upd_cust)) {
      $this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $id_customer]);
    }
    $this->db->insert('tr_h2_manage_booking', $insert);
    $this->db->insert_batch('tr_h2_manage_booking_detail', $insert_detail);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Booking has been created!"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }
  function update()
  {

    ini_set("allow_url_fopen", true);
    $post = json_decode(file_get_contents('php://input'), true);
    $mandatory = [
      'pit_no'           => 'required',
      'police_no'        => 'required',
      'engine_no'        => 'required',
      'frame_no'         => 'required',
      // 'service_type'  => 'required',
      'customer_name'    => 'required',
      'customer_phone'   => 'required',
      'customer_email'   => 'required',
      'carrier_name'     => 'required',
      'carrier_phone'    => 'required',
      'service_date'     => 'required',
      'service_time'     => 'required',
      // 'complaint'        => 'required',
      'vehicle_color_id' => 'required',
      'vehicle_type_id'  => 'required',
    ];
    cek_mandatory($mandatory, $post);

    //Cek Booking ID
    $f = [
      'id_dealer' => $this->login->id_dealer,
      'id_booking_int' => $post['booking_id'],
    ];
    $get_book = $this->m_booking->fetch_booking($f);
    cek_referensi($get_book, 'Booking ID');
    $book = $get_book->row();
    $id_booking = $book->id_booking;

    $f = ['no_mesin' => $post['engine_no']];
    $cek_h23 = $this->m_h2_api->getCustomerH23($f);
    if ($cek_h23->num_rows()) {
      $customer_from = 'h23';
      $cust = $cek_h23->row();
      $upd_cust['nama_customer'] = $post['customer_name'];
      $upd_cust['no_hp']         = $post['customer_phone'];
      $upd_cust['email']         = $post['customer_email'];
      $upd_cust['no_mesin']      = $post['engine_no'];
      $upd_cust['no_rangka']     = $post['frame_no'];
      $upd_cust['no_polisi']     = $post['police_no'];
    } else {
      $msg = ['Data Customer Tidak Ditemukan'];
      send_json(msg_sc_error($msg));
    }

    foreach ($post['service_type'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_jasa_or_id_jasa_int' => $st,
      ];
      $get_data = $this->m_h2_master->fetch_jasa_h2_dealer($f);
      if ($get_data->num_rows() > 0) {
        $res_type = $get_data->row();
      } else {
        $msg = ['Service Type Tidak Ditemukan'];
        send_json(msg_sc_error($msg));
      }
      $insert_detail[] = [
        'id_booking' => $id_booking,
        'id_jasa' => $res_type->id_jasa,
        'harga' => $res_type->harga_dealer,
      ];
      if ($key == 0) {
        $id_type = $res_type->id_type;
      }
    }
    $tanggal = get_ymd();
    if (strtotime($post['service_date']) <= strtotime($tanggal)) {
      $msg = ['Tanggal servis harus lebih besar dari hari ini !'];
      send_json(msg_sc_error($msg));
    }

    $update = [
      'id_booking'    => $id_booking,
      'id_dealer'     => $this->login->id_dealer,
      'id_customer'   => $cust->id_customer,
      'id_pit'        => $post['pit_no'],
      'tgl_servis'    => $post['service_date'],
      'jam_servis'    => $post['service_time'],
      'keluhan'       => $post['complaint'],
      'carrier_phone' => $post['carrier_phone'],
      'carrier_name'  => $post['carrier_name'],
      'updated_at'    => waktu_full(),
      'updated_by'    => $this->login->id_user,
      'status'        => 'draft',
      'customer_from' => $customer_from,
      'id_type'       => $id_type
    ];

    // $tes = [
    //   'update' => $update,
    //   'insert_detail' => $insert_detail,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $cond = ['id_booking' => $id_booking];
    $this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $cust->id_customer]);
    $this->db->update('tr_h2_manage_booking', $update, $cond);
    $this->db->delete('tr_h2_manage_booking_detail', $cond);

    $this->db->insert_batch('tr_h2_manage_booking_detail', $insert_detail);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Booking has been updated!"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function cancel_booking()
  {
    $post = $this->input->post();
    $mandatory = [
      'booking_id'        => 'required',
      'reason'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_booking_int' => $post['booking_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_booking->fetch_booking($filter_prospek);
    cek_referensi($get_data, 'Booking ID');
    $prospek = $get_data->row();
    $id_booking = $prospek->id_booking;
    $update = [
      'status' => 'cancel',
      'alasan_cancel' => $post['reason'],
      'cancel_at'            => waktu_full(),
      'cancel_by'            => $this->login->id_user
    ];

    // $tes = [
    //   'update' => $update,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_h2_manage_booking', $update, ['id_booking' => $id_booking]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Booking has been canceled!"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function services()
  {
    $f = [
      'id_dealer' => $this->login->id_dealer,
      'id_tipe_kendaraan' => $this->input->get('code_type_unit')
      // 'limit' => "LIMIT 10"
    ];
    $get_data = $this->m_h2_master->fetch_jasa_h2_dealer($f);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_jasa_int,
        'code' => $rs->id_jasa,
        'name' => $rs->deskripsi,
        'price' => (int)$rs->harga_dealer,
        'job_type_id' => (int)$rs->id_type_int,
        'job_type_name' => $rs->desk_tipe,
        'time_estimate' => (int)$rs->waktu,
        'is_favorite' => (int)$rs->is_favorite
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function service_history()
  {
    $this->load->model('m_h2_work_order', 'm_wo');

    $get = $this->input->get();
    $mandatory = [
      'offset' => 'required',
      'police_no' => 'required',
      'limit' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_booking = [
      'no_polisi'             => $get['police_no'],
      'offset'                => $get['offset'],
      'length'                => $get['limit'],
      'id_dealer'             => $this->login->id_dealer,
      'cek_riwayat_servis'    => true,
      'status_wo_in'          => "'closed'",
      'order_by'              => "sa_form.created_at DESC",
      'wo_not_null'           => true
    ];

    $get_data = $this->m_wo->get_sa_form($f_booking);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $items =[];
      $f_wo = [
        'id_work_order' => $rs->id_work_order,
        'pekerjaan_batal' => 0,
        'id_dealer' => $this->login->id_dealer
      ];
      $wo_pekerjaan = $this->m_h2_master->getPekerjaanWO($f_wo)->result();
      // send_json($wo_pekerjaan);
      foreach ($wo_pekerjaan as $wop) {
        $items[] = [
          'id_work_order' => $wop->id_work_order,
          'name' => $wop->pekerjaan,
          'code' => $wop->id_jasa,
          'unit_price' => $wop->harga,
          'qty' => 1,
          'total' => (int)$wop->subtotal,
        ];
      }

      $wo_parts = $this->m_h2_master->getPartsWO($f_wo)->result();
      foreach ($wo_parts as $wop) {
        $items[] = [
          'id_work_order' => $wop->id_work_order,
          'name' => $wop->nama_part,
          'code' => $wop->id_part,
          'unit_price' => $wop->harga,
          'qty' => $wop->qty,
          'total' => $wop->harga * $wop->qty,
        ];
      }
      $result[] = [
        // 'no_polisi' => $rs->no_polisi,
        'service_at' => $rs->tgl_servis_real,
        'service_date' => $rs->tgl_servis_real,
        'status' => $rs->status_wo,
        'status_color' => '',
        'complaint' => $rs->keluhan_konsumen,
        'mechanic_suggest' => (string)$rs->saran_mekanik,
        'wo_number' => ' '.$rs->id_work_order,
        'kilometer' => $rs->km_terakhir,
        'items' => isset($items) ? $items : '',
      ];
    }

    send_json(msg_sc_success($result, NULL));
  }
  function service_by_booking()
  {
    $get = $this->input->get();
    $mandatory = [
      'booking_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    $f_booking = [
      'id_booking_int' => $get['booking_id'],
      'id_dealer' => $this->login->id_dealer
    ];

    $get_data = $this->m_booking->getBookingDetailService($f_booking);
    $result = [];
    foreach ($get_data->result() as $book) {
      $result[] = [
        'id' => (int)$book->id,
        'code' => $book->id_jasa,
        'name' => $book->desk_jasa,
        'price' => (int)$book->harga,
        'job_type_id' => (int)$book->id_type_int,
        'job_type_name' => $book->desk_type,
        'time_estimate' => $book->waktu . ' Menit',
        'is_favorite' => (int)$book->is_favorite,
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
}
