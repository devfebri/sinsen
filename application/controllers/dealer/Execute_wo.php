<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Execute_wo extends CI_Controller
{
  var $folder = "dealer";
  var $page   = "execute_wo";
  var $title  = "Execute Work Order";

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
    $this->load->model('m_h2_work_order', 'm_wo');
    //===== Load Library =====
    $this->load->library('upload');
    $this->load->library('form_validation');
    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
    $this->load->model('notifikasi_model', 'notifikasi');
    $this->load->model('m_sm_master', 'm_sm');
    $this->load->model('h3_dealer_sales_order_model', 'sales_order');
    $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
    $this->load->model('h3_dealer_picking_slip_model', 'picking_slip');
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $page = $this->page;
      if (isset($data['mode'])) {
        if ($data['mode'] == 'insert_wo') $page = 'sa_form';
        if ($data['mode'] == 'detail_wo') $page = 'sa_form';
      }
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']  = "index";
    $filter = ['id_work_order_not_null' => 'ya', 'id_karyawan_dealer_not_null' => 'ya', 'status_wo_in' => "'pause', 'open','pending'"];
    $data['wo'] = $this->m_wo->get_sa_form($filter);
    $this->template($data);
  }

  public function send_wo_part_counter()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Send WO to Parts Counter';
    $data['set']   = "send_wo";
    $data['mode']  = "send_wo";
    $id_work_order = $this->input->get('id');

    $filter['id_work_order'] = $id_work_order;
    $sa_form = $this->m_wo->get_sa_form($filter);
    if ($sa_form->num_rows() > 0) {
      $data['wo'] = $sa_form->row();
      $filter = [
        'id_work_order' => $id_work_order,
        'id_kirim_null' => 'ya',
        'jenis_order' => 'reguler'
      ];
      // $data['parts'] = $this->m_h2->getPartsWO($filter)->result();
      $parts = $this->m_h2->getPartsWO($filter)->result();
      foreach ($parts as $val) {
        $data['parts'][] = [
          'id_part' => $val->id_part,
          'id_part_int' => $val->id_part_int,
          'id_gudang' => $val->id_gudang,
          'nama_part' => $val->nama_part,
          'id_rak' => $val->id_rak,
          'kuantitas' => $val->qty,
          'id_jasa' => $val->id_jasa,
          'harga_saat_dibeli' => $val->harga,
          'id_promo' => $val->id_promo,
          'diskon_value' => $val->diskon_value,
          'tipe_diskon' => $val->tipe_diskon,
        ];
      }
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data tidak ditemukan !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
    }
  }

  function save_send_wo()
  {
    $tgl           = date("Y-m-d");
    $login_id      = $this->session->userdata('id_user');
    $id_dealer     = $this->m_admin->cari_dealer();
    $id_kirim_part = $this->m_h2->get_id_kirim_part();
    $id_work_order = $this->input->post('id_work_order');
    $nomor_so = $this->input->post('nomor_so');
    $filter = ['id_work_order' => $id_work_order];
    $wo = $this->m_wo->get_sa_form($filter);
    if ($wo->num_rows() == 0) {
      $rsp = ['status' => 'error', 'pesan' => 'Data work order tidak ditemukan !'];
      echo json_encode($rsp);
      die();
    } else {
      $wo = $wo->row();
    }
    $parts         = $this->input->post('parts');

    $ins_data     = [
      'id_kirim_part' => $id_kirim_part,
      'id_work_order' => $id_work_order,
      'id_dealer'     => $id_dealer,
      'nomor_so'      => $nomor_so,
      'tgl_kirim'     => $tgl,
      'created_at'    => waktu(),
      'created_by'    => $login_id,
    ];

    // Skenario Picking Slip manual belum dibuat. Jadi Sementara Good Issue Dibuat Otomatis Dulu
    if (1 == 1) {
      $ins_data['good_issue_id'] = $this->m_wo->get_good_issue_id();
      $ins_data['status']        = 'received';
    }
    // $result = ['ins_data' => $ins_data, 'upd_parts' => isset($upd_parts) ? $upd_parts : ''];
    // echo json_encode($result);
    // exit();
    $this->db->trans_begin();
    $this->db->insert('tr_h2_kirim_ke_part_counter', $ins_data);
    foreach ($parts as $prt) {
      $upd_part = ['id_kirim_part' => $id_kirim_part, 'nomor_so' => $nomor_so, 'sudah_terbuat_picking_slip' => 1];
      $where = [
        'id_work_order' => $id_work_order,
        'id_part'       => $prt['id_part'],
        'id_gudang'     => $prt['id_gudang'],
        'id_jasa'       => $prt['id_jasa'],
        'id_rak'        => $prt['id_rak']
      ];
      $this->db->update('tr_h2_wo_dealer_parts', $upd_part, $where);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();

      $this->notifikasi->insert([
        'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_send_part_wo')->get()->row()->id_notif_kat,
        'judul'        => 'Kebutuhan Parts Work Order',
        'pesan'        => "Terdapat Kebutuhan Parts Work Order : " . $id_work_order . ", dengan Nomor SO : " . $nomor_so,
        'link'         => "dealer/h3_dealer_sales_order/detail?k=$nomor_so",
        'id_referensi' => $nomor_so,
        'id_dealer'    => $this->m_admin->cari_dealer(),
        'show_popup'   => false,
      ]);
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/execute_wo')
      ];
      $_SESSION['pesan']   = "Data berhasil di proses";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  function save_send_wo_2()
  {
    $this->db->trans_start();
    $tgl           = date("Y-m-d");
    $login_id      = $this->session->userdata('id_user');
    $id_dealer     = $this->m_admin->cari_dealer();

    //Cek ID Dealer apakah kosong atau tidak 
    if($id_dealer == '' or $id_dealer == NULL){
      $rsp = ['status' => 'error', 'pesan' => 'WO tidak dapat diproses, Silahkan Login Ulang!'];
      echo json_encode($rsp);
      die();
    }

    $id_customer = $this->input->post('id_customer');
    $id_work_order = $this->input->post('id_work_order');

    if(!isset($id_customer) && isset($id_work_order)){
      //Jika tidak ada id_customer maka cek melalui WO 
      $cek_id_customer = $this->db->select('sa.id_customer')
                              ->from('tr_h2_wo_dealer wo')
                              ->join('tr_h2_sa_form sa','wo.id_sa_form=sa.id_sa_form')
                              ->where('wo.id_work_order',$id_work_order)
                              ->get()
                              ->row_array();

      $id_customer = $cek_id_customer['id_customer'];
   
    }

    $detail_customer = $this->db->select('nama_customer') 
                                ->select('no_hp')
                                ->select('alamat')
                                ->select('id_customer_int') 
                                ->from('ms_customer_h23')
                                ->where('id_customer',$id_customer)
                                ->get()
                                ->row_array();


    $salesOrderData = $this->input->post([
      'id_customer', 'id_work_order'
    ]);

    $salesOrderData = array_merge($salesOrderData, [
      'nomor_so' => $this->sales_order->generateNomorSO(),
      'id_dealer' => $this->m_admin->cari_dealer(),
      'tanggal_so' => date('Y-m-d', time()),
      'id_customer'=> $id_customer,
      'id_customer_int' => $detail_customer['id_customer_int'],
      'nama_pembeli' => $detail_customer['nama_customer'],
      'no_hp_pembeli' => $detail_customer['no_hp'],
      'alamat_pembeli' => $detail_customer['alamat'],
      'pembelian_dari_dealer_lain' => 0,
      'status' => 'Processing',
      'id_work_order' => $id_work_order,
    ]);


    $nomor_so_int = $this->sales_order->insert($salesOrderData);

    $salesOrderPartsData = $this->input->post('parts');

    $parts = [];
    
    foreach ($salesOrderPartsData as $part) {
      $part = ['kuantitas' => $part['kuantitas'], 
                'id_part' => $part['id_part'], 
                'id_part_int' => $part['id_part_int'], 
                'id_gudang' => $part['id_gudang'], 
                'id_rak' => $part['id_rak'], 
                'diskon_value' => $part['diskon_value'], 
                'nomor_so' => $salesOrderData['nomor_so'], 
                'nomor_so_int' => $nomor_so_int, 
                'harga_saat_dibeli' => $part['harga_saat_dibeli'],
                'tipe_diskon' => $part['tipe_diskon'],
                'harga_setelah_diskon' => $part['harga_setelah_diskon'],];
      if ($part['tipe_diskon'] == 'Percentage') {
          $part['disc_percentage'] = $part['diskon_value'];
          $part['disc_amount'] = null;
          $part['disc_foc'] = null;
      } else if ($part['tipe_diskon'] == 'Value') {
          $part['disc_amount'] = $part['diskon_value'];
          $part['disc_percentage'] = null;
          $part['disc_foc'] = null;
      } else if ($part['tipe_diskon'] == 'FoC') {
          $part['disc_foc'] = $part['diskon_value'];
          $part['disc_percentage'] = null;
          $part['disc_amount'] = null;
      }

      $this->sales_order_parts->insert($part);
      $parts[] = $part;

      $kelompok_part = $this->db->select('kelompok_part')
      ->from('ms_part')
      ->where('id_part_int', $part['id_part_int'])
      ->get()->row_array();

      $qty_harus_dipenuhi = $part['kuantitas'];
        if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
          // Type ACC 
          if($kelompok_part['kelompok_part'] == 'EVBT'){
            $type_acc = 'B';
          }elseif($kelompok_part['kelompok_part'] == 'EVCH'){
            $type_acc = 'C';
          }

          $stocks = $this->db->select('ts.serial_number')
               ->select('ts.fifo_dealer')
               ->select('1 as qty')
               ->from('tr_h3_serial_ev_tracking as ts')
               ->where('ts.accStatus',4)
               ->where('ts.type_accesories',$type_acc)
               ->where('id_lokasi_rak_dealer',$part['id_rak'])
               ->where('id_part_int',$part['id_part_int'])
               ->where('id_gudang_dealer',$part['id_gudang'])
               ->group_start()
               ->where('ts.no_so_wo_booking',null)
               ->or_where('ts.no_so_wo_booking',0)
               ->or_where('ts.no_so_wo_booking','')
               ->group_end()
               ->order_by('ts.fifo_dealer','ASC')
               ->get()->result_array();

          foreach ($stocks as $stock) {
              if($qty_harus_dipenuhi == 0) break;
              $serial_number = $stock['serial_number'];
              if($stock['qty'] <=$qty_harus_dipenuhi){
                  $qty_harus_dipenuhi -= $stock['qty'];
              }else{
                  $qty_harus_dipenuhi -= $qty_harus_dipenuhi;
              }

              // Update Status id_do_sales_order di tr_h3_serial_ev_tracking 
              $customer = $this->db->select('no_mesin')
                      ->select('no_rangka')
                      ->from('ms_customer_h23')
                      ->where('id_customer', $salesOrderData['id_customer'])
                      ->get()->row_array();
          
              $this->db->set('no_so_wo_booking',  $salesOrderData['nomor_so'])
                  ->set('id_customer', $salesOrderData['id_customer'])
                  ->set('nama_customer', $salesOrderData['nama_pembeli'])
                  ->set('no_hp', $salesOrderData['no_hp_pembeli'])
                  ->set('no_mesin', $customer['no_mesin'])
                  ->set('no_rangka', $customer['no_rangka'])
                  ->where('id_part_int', $part['id_part_int'])	
                  ->where('serial_number', $serial_number)
                  ->update('tr_h3_serial_ev_tracking');

              $data_so_ev = array(
                      'nomor_so' => $salesOrderData['nomor_so'],
                      'nomor_so_int' => $nomor_so_int,
                      'id_part' => $part['id_part'],
                      'id_part_int' => $part['id_part_int'],
                      'serial_number' => $serial_number,
                      'is_return' => 0
              );
              
              $this->db->insert('tr_h3_dealer_sales_order_serial_ev', $data_so_ev);    
          }		
        }
    }

    $id_kirim_part = $this->m_h2->get_id_kirim_part();
    $nomor_so = $salesOrderData['nomor_so'];
    $filter = ['id_work_order' => $id_work_order];
    $wo = $this->m_wo->get_sa_form($filter);
    if ($wo->num_rows() == 0) {
      $rsp = ['status' => 'error', 'pesan' => 'Data work order tidak ditemukan !'];
      echo json_encode($rsp);
      die();
    } else {
      $wo = $wo->row();
    }
    $parts         = $this->input->post('parts');

    $ins_data     = [
      'id_kirim_part' => $id_kirim_part,
      'id_work_order' => $id_work_order,
      'id_dealer'     => $id_dealer,
      'nomor_so'      => $nomor_so,
      'tgl_kirim'     => $tgl,
      'created_at'    => waktu(),
      'created_by'    => $login_id,
    ];

    // Skenario Picking Slip manual belum dibuat. Jadi Sementara Good Issue Dibuat Otomatis Dulu
    if (1 == 1) {
      $ins_data['good_issue_id'] = $this->m_wo->get_good_issue_id();
      $ins_data['status']        = 'received';
    }
    // $result = ['ins_data' => $ins_data, 'upd_parts' => isset($upd_parts) ? $upd_parts : ''];
    // echo json_encode($result);
    // exit();
    // $this->db->trans_begin();
    $this->db->insert('tr_h2_kirim_ke_part_counter', $ins_data);
    foreach ($parts as $prt) {
      $upd_part = ['id_kirim_part' => $id_kirim_part, 'nomor_so' => $nomor_so, 'sudah_terbuat_picking_slip' => 1];
      $where = [
        'id_work_order' => $id_work_order,
        'id_part'       => $prt['id_part'],
        'id_gudang'     => $prt['id_gudang'],
        'id_jasa'       => $prt['id_jasa'],
        'id_rak'        => $prt['id_rak']
      ];
      $this->db->update('tr_h2_wo_dealer_parts', $upd_part, $where);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();

      $this->notifikasi->insert([
        'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_send_part_wo')->get()->row()->id_notif_kat,
        'judul'        => 'Kebutuhan Parts Work Order',
        'pesan'        => "Terdapat Kebutuhan Parts Work Order : " . $id_work_order . ", dengan Nomor SO : " . $nomor_so,
        'link'         => "dealer/h3_dealer_sales_order/detail?k=$nomor_so",
        'id_referensi' => $nomor_so,
        'id_dealer'    => $this->m_admin->cari_dealer(),
        'show_popup'   => false,
      ]);
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('dealer/execute_wo')
      ];
      $_SESSION['pesan']   = "Data berhasil di proses";
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function detail_wo()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail Work Order';
    $data['mode']  = 'detail_wo';
    $data['set']   = "form";
    $id_work_order    = $this->input->get('id');

    $filter['id_work_order'] = $id_work_order;
    $sa_form = $this->m_wo->get_sa_form($filter);
    if ($sa_form->num_rows() > 0) {
      $row                     = $data['row_wo'] = $sa_form->row();
      $data['tipe_coming']     = explode(',', $row->tipe_coming);
      $data['pkp'] = $row->pkp;
      $filter['id_work_order'] = $id_work_order;
      $data['details']         = $this->m_h2->wo_detail($filter);
      $data['estimasi_waktu_daftar'] = $row->estimasi_waktu_daftar;
      $data['activity_promotion']    = $this->m_sm->getActivityPromotion()->result();
      $data['activity_cap']          = $this->m_sm->getActivityCapacity()->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
    }
  }

  function closed_wo()
  {
    $id_work_order = $this->input->get('id');
    $cek = $this->db->get_where('tr_h2_wo_dealer', ['id_work_order' => $id_work_order]);
    if ($cek->num_rows() > 0) {
      $data_wo = $cek->row();
      $cek_sa = $this->db->query("SELECT id_pit FROM tr_h2_sa_form WHERE id_sa_form='$data_wo->id_sa_form' AND (id_pit IS NULL OR id_pit='')")->num_rows();
      if ($cek_sa > 0) {
        $_SESSION['pesan']   = "ID Pit belum ditentukan !";
        $_SESSION['tipe']   = "danger";
        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
        die();
      }
      $data = [
        'status' => 'closed',
        'closed_at' => waktu_full(),
        'closed_by' => user()->id_user,
        'saran_mekanik' => $this->input->get('saran_mekanik'),
        'tgl_service_selanjutnya' => $this->input->get('tgl_service_selanjutnya'),
        'claim_e_kpb' =>  $this->input->get('ekpb') // 1= e_kpb, 0= manual , null = belum diproses 
      ];
      // send_json($data);

      $filter = [
        'id_work_order' => $id_work_order,
        'select' => 'id_booking',
        'not_exists_picking_slip' => true,
        'group_by' => 'id_booking'
      ];
      $cek_hlo_belum_selesai = $this->m_wo->getHLOWOParts($filter);
      if ($cek_hlo_belum_selesai->num_rows() > 0) {
        $_SESSION['pesan']   = "Masih ada parts HLO yang belum selesai di proses !";
        $_SESSION['tipe']   = "danger";
        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
        die();
      }
      $filter = [
        'id_work_order' => $id_work_order,
        'select' => 'nomor_so',
        'jenis_order' => 'reguler',
        'nomor_so_null' => true
      ];
      $cek_reg_belum_selesai = $this->m_wo->getWOParts($filter);
      if ($cek_reg_belum_selesai->num_rows() > 0) {
        $_SESSION['pesan']   = "Masih ada parts reguler yang belum selesai di proses !";
        $_SESSION['tipe']   = "danger";
        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
        die();
      }

      $filter = [
        'id_work_order' => $id_work_order,
        'select' => 'id_booking',
        'not_exists_picking_slip' => true,
        'group_by' => 'id_booking'
      ];
      $cek_hlo_belum_selesai = $this->m_wo->getHLOWOParts($filter);
      if ($cek_hlo_belum_selesai->num_rows() > 0) {
        $_SESSION['pesan']   = "Masih ada parts HLO yang belum selesai di proses !";
        $_SESSION['tipe']   = "danger";
        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
        die();
      }

      // Cek Booking Apakah Dari Customer App, Jika Iya Lakukan Update Status=3 (pemeriksaan akhir)
      $book = $this->db->query("SELECT book.id_booking 
              FROM tr_h2_manage_booking book 
              JOIN tr_h2_sa_form sa ON sa.id_booking=book.id_booking
              WHERE sa.id_sa_form='$data_wo->id_sa_form' AND IFNULL(customer_apps_booking_number,'')!=''
              ")->row();
      if ($book != null) {
        $upd_book = ['customer_apps_status' => 3, 'updated_at' => waktu_full()];
      }

      $this->db->trans_begin();
      
      $record_reason = $this->input->get('record_reason');
      $hasil_pengecekan = $this->input->get('hasil_pengecekan');

      //Cek apakah wo ada di tabel tr_h2_wo_lcr_history
      $lcr_history = $this->db->select('id_work_order')
                              ->select('kesediaan_customer_lcr_id')
                              ->select('record_reason_lcr_id')
                              ->select('hasil_pengecekan_lcr_id')
                              ->from('tr_h2_wo_lcr_history')
                              ->where('id_work_order',$id_work_order)
                              ->where('status_lcr','LC')
                              ->get()
                              ->row_array();

      if(!empty($lcr_history)){
        //Cek apakah hasil pengecekan / record reason telah dimasuki ke DB 
        if($lcr_history['kesediaan_customer_lcr_id'] == 1 && ($lcr_history['hasil_pengecekan_lcr_id'] == '' || $lcr_history['hasil_pengecekan_lcr_id'] == null)){
          $this->db->set('hasil_pengecekan_lcr_id',$hasil_pengecekan);
          $this->db->where('id_work_order', $id_work_order);
          $this->db->where('status_lcr', 'LC');
          $this->db->update('tr_h2_wo_lcr_history');
        // }elseif($lcr_history['kesediaan_customer_lcr_id'] == 3 && ($lcr_history['record_reason_lcr_id'] == '' || $lcr_history['record_reason_lcr_id'] == null)){
        //   $this->db->set('record_reason_lcr_id',$record_reason);
        //   $this->db->where('id_work_order', $id_work_order);
        //   $this->db->where('status_lcr', 'LC');
        //   $this->db->update('tr_h2_wo_lcr_history');
        }
      }

      $upd_lcr_history = ['status' => 'closed'
      ];
      $this->db->update('tr_h2_wo_lcr_history', $upd_lcr_history, ['id_work_order' => $id_work_order,'status' => 'open']);
      
      $serial_number_battery = $this->input->get('serial_number_battery');
      $soc = $this->input->get('soc');

      if($serial_number_battery === ''){
        $serial_number_battery = null ; 
      }

      if($soc === ''){
        $soc = null ; 
      }

      $upd_sa = ['status_monitor' => 'selesai',
      'soc' => $soc,
      'serial_number_battery' => $serial_number_battery,
      'informasi_bensin' => null,
      ];

      $upd_sa = ['status_monitor' => 'selesai'];
      $this->db->update('tr_h2_sa_form', $upd_sa, ['id_sa_form' => $data_wo->id_sa_form]);
      $this->db->update('tr_h2_wo_dealer', $data, ['id_work_order' => $id_work_order]);

      if (isset($upd_book)) {
        $this->db->update('tr_h2_manage_booking', $upd_book, ['id_booking' => $book->id_booking]);
        $this->load->library('mokita');
        $this->load->model('m_h2_booking');
        $request = $this->m_h2_booking->customer_app_booking_checkout($id_work_order);
        $this->mokita->booking_checkout($request);
      }
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
      } else {
        $this->db->trans_commit();
        $_SESSION['pesan']   = "Proses perubahan status Work Order berhasil";
        $_SESSION['tipe']   = "success";
        echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
      }
    } else {
      $_SESSION['pesan']   = "Data tidak ditemukan !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/execute_wo'>";
    }
    // send_json($rsp);
  }

  function tes()
  {
    $filter = [
      'id_work_order' => $this->input->get('id'),
      'select' => 'nomor_so',
      'jenis_order' => 'reguler',
      'nomor_so_null' => true
    ];
    $cek_reg_belum_selesai = $this->m_wo->getWOParts($filter);
    send_json($cek_reg_belum_selesai->num_rows());
  }

  function check_wo(){
    $id_work_order = $this->input->post('id_work_order');
    $parts = $this->input->post('parts');

    $is_so = 0;
    foreach($parts as $part){
      $id_part_int = $part['id_part_int'];
      $check_data_wo = $this->db->query("SELECT so.nomor_so, sop.id_part 
          from tr_h3_dealer_sales_order so 
          join tr_h3_dealer_sales_order_parts sop on sop.nomor_so_int= so.id
          where so.id_work_order is not null and so.status != 'Canceled'
          and sop.id_part_int='$id_part_int' and so.id_work_order='$id_work_order'")->row_array();
      if(!empty($check_data_wo['nomor_so'])){
        $is_so += 1;
      }
    }
    if($is_so > 0){
      echo json_encode(['available' => false, 'message' => 'SO Sudah dibuat, cek kembali nomor SO']);
    }else{
      echo json_encode(['available' => true, 'message' => '' ]);
    }
  }

  function check_serial_number(){
		if(isset($_POST['serial_number_battery'])) {
			$serial_number = $_POST['serial_number_battery'];
		
			$check_data_serial_number = $this->db->query("SELECT serial_number FROM tr_h3_serial_ev_tracking WHERE serial_number = '$serial_number'")->row_array();
		
			// Cek hasil dan kirim respons
			if(empty($check_data_serial_number['serial_number'])){
				$check_data_serial_number_h1 = $this->db->query("SELECT serial_number FROM tr_stock_battery WHERE serial_number = '$serial_number'")->row_array();
				if(empty($check_data_serial_number_h1['serial_number'])){
					echo json_encode(['available' => false, 'message' => 'Serial Number : ' .$serial_number. ' Tidak tersedia, cek kembali Serial Number']);
				}else{
					echo json_encode(['available' => true, 'message' => '']);
				}
			}else{
				echo json_encode(['available' => true, 'message' => '']);
			}
		} 
	}
}
