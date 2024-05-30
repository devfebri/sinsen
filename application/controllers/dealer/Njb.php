<?php
defined('BASEPATH') or exit('No direct script access allowed');

class njb extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "njb";
  var $title  = "Nota Jasa Bengkel (NJB)";

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
    $this->load->model('m_h2_crm', 'm_crm');
    $this->load->model('m_h2_billing', 'm_bil');
    $this->load->model('notifikasi_model', 'notifikasi');

    //===== Load Library =====
    $this->load->library('upload');
    $this->load->library('form_validation');
    $this->load->helper('tgl_indo');
    $this->load->helper('terbilang');
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
        if ($data['mode'] == 'generate_wo') {
          $page = 'sa_form';
        }
        if ($data['mode'] == 'detail_wo') {
          $page = 'sa_form';
        }
      }
      $this->load->view($this->folder . "/" . $page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "index";


    // $data['wo']    = $this->m_wo->getWorkOrder($filter);
    // send_json($data);
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();

      $sub_array[] = '<a href="dealer/njb/detail_njb?id=' . $rs->no_njb . '">' . $rs->no_njb . '</a>';;
      $sub_array[] = $rs->tgl_njb;
      $sub_array[] = $rs->id_work_order;
      $sub_array[] = $rs->tgl_servis;
      $sub_array[] = $rs->no_polisi;
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->tipe_ahm;
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
      'order_column' => ['no_njb', 'LEFT(created_njb_at)', 'wo.id_work_order', 'sa.tgl_servis', 'ch23.no_polisi', 'ch23.nama_customer', 'tipe_ahm'],
      'search' => $this->input->post('search')['value'],
      'join' => ['customer', 'tipe_kendaraan', 'warna'],
      'select' => 'history',
      'no_njb_not_null' => true,
      'id_dealer' => $this->m_admin->cari_dealer()
    ];

    if ($recordsFiltered == true) {
      return $this->m_wo->getWorkOrder($filter)->num_rows();
    } else {
      return $this->m_wo->getWorkOrder($filter)->result();
    }
  }

  public function create_njb()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['mode']  = 'create_njb';
    $data['set']   = "form";
    // $data['pkp'] = dealer()->pkp;
    $data['pkp'] = 0;
    $this->template($data);
  }

  public function detail_njb()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail NJB';
    $data['mode']  = 'detail_njb';
    $data['set']   = "form";
    $no_njb = $this->input->get('id');

    $filter = ['no_njb' => $no_njb];
    $get_wo = $this->m_wo->get_sa_form($filter);

    if ($get_wo->num_rows() > 0) {
      $row = $data['row'] = $get_wo->row();
      $data['pkp'] = $row->pkp_njb;
      $data['pkp'] = 0;
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/njb'>";
    }
  }

  function saveNJB()
  {
    $waktu         = waktu_full();
    $tgl_hari_ini  = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id      = $this->session->userdata('id_user');
    $id_work_order = $this->input->post('id_work_order');
    $id_dealer     = $this->m_admin->cari_dealer();
    $no_njb        = $this->m_bil->get_no_njb();

    //Hirarki Jasa AHM 
    $hirarki_jasa = array('C2','C1','PUD','HR','ASS1','ASS2','ASS3','ASS4','CS','LS','PL','OTHER','LR','OR+','QS','JR');

    //Check jasa untuk ID Jasa SA FORM 
    $jasa_wo = $this->db->select('(CASE WHEN jasa.id_type in (\'LC\',\'LT1\',\'LT2\',\'LG\') THEN "C2" ELSE jasa.id_type END) as id_type')
                        ->from('tr_h2_wo_dealer_pekerjaan as wop')
                        ->join('ms_h2_jasa as jasa','jasa.id_jasa=wop.id_jasa')
                        ->where('wop.id_work_order',$id_work_order)
                        ->where('wop.pekerjaan_batal',0)->get()->result_array();
    
    $temp = count($hirarki_jasa);
    $selected_item = null;
    
    foreach ($jasa_wo as $item) {
        $index = array_search($item["id_type"], $hirarki_jasa);

        if ($index !== false) {
            if ($index < $temp) {
                $temp = $index; 
                $selected_item = $item["id_type"];
            }
        }
    }

    $get_jasa_sa_form = $this->db->select('id_sa_form')
                                  ->from('tr_h2_wo_dealer')
                                  ->where('id_work_order',$id_work_order)
                                  ->get()->row_array();
        
    $this->db->set('id_type', $selected_item)
              ->where('id_sa_form', $get_jasa_sa_form['id_sa_form'])
              ->update('tr_h2_sa_form');

    // send_json($no_njb);
    $filter = ['id_work_order' => $id_work_order, 'njb_null' => 1];
    $get_wo = $this->m_wo->get_sa_form($filter);
    if ($get_wo->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Data WO tidak ditemukan atau sudah di proses !'];
      echo json_encode($result);
      die();
    } else {
      $wo = $get_wo->row();
    }
    // send_json($wo);
    $upd = [
      'no_njb'         => $no_njb,
      'waktu_njb'      => $waktu,
      // 'pkp_njb'        => 0,
      'pkp_njb'     => dealer()->pkp,
      'created_njb_at' => $waktu,
      'created_njb_by' => $login_id,
    ];
    if ($wo->tipe_pembayaran == 'top') {
      $tgl = tambah_dmy('tanggal', +$wo->jangka_waktu_top, $tgl_hari_ini);
      $upd['tgl_jatuh_tempo'] = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];
    }
    // send_json($upd);

    //Get Batas Reminder & Follow Up
    $sett               = $this->db->get_where("ms_h2_kpb_reminder", ['active' => 1]);
    if ($sett->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Reminder KPB belum ditentukan, silahkan hubungi Main Dealer !'];
      send_json($result);
    } else {
      $sett = $sett->row();
    }
    // send_json($sett);
    //Cek dan Create Next Service Reminder
    $cek_dealer = cek_dealer($wo->id_dealer_h1);
    // send_json($cek_dealer);
    if ($cek_dealer == 'null' or $cek_dealer == 'h123') {
      $filter = ['id_type_in' => "'ass1','ass2','ass3','ass4'", 'id_work_order' => $id_work_order];

      $cek_kpb = $this->m_h2->getPekerjaanWO($filter);
      // echo json_encode($cek_kpb->row());
      // die();
      if ($cek_kpb->num_rows() > 0) {
        $kpb = $cek_kpb->row();
        $next_kpb         = substr($kpb->job_type, -1) + 1;
        // $next_kpb         = substr($kpb->job_type, -1) + 2;
        // echo $next_kpb;
        $get_kpb = $this->db->get_where("ms_kpb_detail", ['id_tipe_kendaraan' => $wo->id_tipe_kendaraan, 'kpb_ke' => $next_kpb]);
        if ($get_kpb->num_rows() > 0) {
          $get_kpb          = $get_kpb->row();
          // send_json($get_kpb);
          // echo $get_kpb->batas_maks_kpb;
          // die();
          $tgl              = tambah_dmy('tanggal', $get_kpb->batas_maks_kpb, $wo->tgl_pembelian);
          $tgl_serv_next    = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];
          $tgl              = tambah_dmy('tanggal', -$sett->sms_kpb1, $tgl_serv_next);
          $tgl_reminder_sms = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];
          $tgl              = tambah_dmy('tanggal', -$sett->call_kpb1, $tgl_serv_next);
          $tgl_reminder_call = $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal'];

          $ins_reminder = [
            'id_dealer'                => $wo->id_dealer,
            'id_work_order_sebelumnya' => $id_work_order,
            'tgl_reminder_sms'         => $tgl_reminder_sms,
            'tgl_contact_sms'          => $tgl_reminder_call,
            'tgl_contact_call'         => $tgl_reminder_call,
            'tgl_servis_berikutnya'    => $tgl_serv_next,
            'tipe_servis_berikutnya'   => trim('ass' . $next_kpb),
            'created_at'               => $waktu,
            'created_by'               => $login_id,
            'reminder_from'            => 'billing',
          ];
          // echo $tgl_reminder_call;
          // die();
        }
      }
    } else {
      // echo 'fewfewf';
      // echo json_encode(cek_dealer($wo->id_dealer_h1));
      // die();
    }


    //Create Follow Up After Service
    $sett = $this->m_crm->getBatas();
    if ($sett->num_rows() == 0) {
      $result = ['status' => 'error', 'pesan' => 'Batas untuk follow up setelah servis belum ditentukan !'];
      echo json_encode($result);
      die();
    } else {
      $sett = $sett->row();
    }

    $id_follow_up = $this->m_h2->get_id_follow_up();
    // send_json($id_follow_up);
    $tgl_follow_up = tambah_dmy('tanggal', $sett->h_follow_up_after_service, $tgl_hari_ini);
    // send_json($tgl_follow_up);
    $tgl_follow_up = $tgl_follow_up['tahun'] . '-' . $tgl_follow_up['bulan'] . '-' . $tgl_follow_up['tanggal'];
    $ins_folup = [
      'id_follow_up'  => $id_follow_up,
      'tgl_follow_up' => $tgl_follow_up,
      'id_dealer'     => $id_dealer,
      'id_work_order' => $id_work_order,
      'status'        => 'open',
      'created_at'    => $waktu,
      'created_by'    => $login_id,
    ];

    //Create Follow Up After Service
    $ins_folup_history = [
      'id_follow_up'  => $id_follow_up,
      'tgl_follow_up' => $tgl_follow_up
    ];

    $tes = [
      'update' => $upd,
      'ins_reminder' => isset($ins_reminder) ? $ins_reminder : null,
      'ins_folup' => isset($ins_folup) ? $ins_folup : null,
    ];
    // send_json($tes);

    $this->db->trans_begin();
    $this->db->update('tr_h2_wo_dealer', $upd, ['id_work_order' => $id_work_order]);
    $pesan = "Pembuatan nota jasa bengkel (NJB) berhasil";
    if (isset($ins_reminder)) {
      $pesan .= ". Perlu dilakukan reminder service untuk " . trim('ass' . $next_kpb) . " pada tanggal " . $tgl_reminder_call;
      $this->notifikasi->insert([
        'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_service_reminder')->get()->row()->id_notif_kat,
        'judul'        => 'Service Reminder Customer',
        'pesan'        => "Terdapat reminder service via SMS yang dilakukan tanggal : $tgl_reminder_sms, dan perlu dilakukan contact via call pada tanggal : $tgl_reminder_call untuk ID Customer : $wo->id_customer",
        'link'         => "dealer/service_reminder_schedule",
        'id_referensi' => $wo->id_customer,
        'id_dealer'    => $this->m_admin->cari_dealer(),
        'show_popup'   => false,
      ]);
      $this->db->insert('tr_h2_service_reminder', $ins_reminder);
    }
    if (isset($ins_folup)) {
      $pesan .= ". Perlu dilakukan follow up after service pada tanggal $tgl_follow_up";
      $this->notifikasi->insert([
        'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_follow_up_service')->get()->row()->id_notif_kat,
        'judul'        => 'Follow Up After Service',
        'pesan'        => "Terdapat follow up after service yang perlu dilakukan via call pada tanggal : $tgl_follow_up untuk ID Customer : $wo->id_customer",
        'link'         => "dealer/follow_up_service",
        'id_referensi' => $wo->id_customer,
        'id_dealer'    => $this->m_admin->cari_dealer(),
        'show_popup'   => false,
      ]);
      $this->db->insert('tr_h2_follow_up_after_service', $ins_folup);
    }
    $this->notifikasi->insert([
      'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_njb')->get()->row()->id_notif_kat,
      'judul'        => 'Nota Jasa Bengkel (NJB)',
      'pesan'        => "Terdapat Nota Jasa Bengkel (NJB) dengan No. NJB : " . $no_njb,
      'link'         => "dealer/njb/detail_njb?id=$no_njb",
      'id_referensi' => $no_njb,
      'id_dealer'    => $this->m_admin->cari_dealer(),
      'show_popup'   => false,
    ]);

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
        'link' => base_url('dealer/njb')
      ];
      $_SESSION['pesan']   = $pesan;
      $_SESSION['tipe']   = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  function get_wo_njb()
  {
    $post = $this->input->post();
    $id_work_order = $post['id_work_order'];
    $filter = ['id_work_order' => $id_work_order];
    $data          = $this->m_wo->get_sa_form($filter);
    if ($data->num_rows() > 0) {
      $result_data            = $data->row();
      $filter = [
        'id_work_order' => $id_work_order,
        'pekerjaan_batal' => 0
      ];
      $cek_pekerjaan = $this->m_h2->getPekerjaanWO($filter);
      if ($cek_pekerjaan->num_rows() == 0) {
        $result = ['status' => 'error', 'pesan' => 'Data pekerjaan tidak ditemukan !'];
        send_json($result);
      } else {
        $result_data->pekerjaan = $cek_pekerjaan->result();
      }
      $result                 = ['status' => 'sukses', 'data' => $result_data];
    }
    send_json($result);
  }
  public function set_wo_top($set = NULL)
  {
    $filter['tipe_pembayaran'] = 'top';
    $filter['id_dealer'] = dealer()->id_dealer;
    $wo = $this->m_wo->get_sa_form($filter)->result();
    $tgl_hari_ini = date('Y-m-d');
    foreach ($wo as $w) {
      $tgl = tambah_dmy('tanggal', +$w->jangka_waktu_top, $tgl_hari_ini);
      $upd[] = [
        'id_work_order' => $w->id_work_order,
        'tgl_jatuh_tempo' => $tgl['tahun'] . '-' . $tgl['bulan'] . '-' . $tgl['tanggal']
      ];
    }
    if ($set == 1) {
      $this->db->update_batch('tr_h2_wo_dealer', $upd, 'id_work_order');
      echo 'Tot update : ' . count($upd);
    } elseif ($set == NULL) {
      send_json($upd);
    }
  }
  function normalisasiNjbDobel()
  {
    $_SESSION['njb']=[];
    $result = $this->db->query("SELECT COUNT(no_njb) c,no_njb from tr_h2_wo_dealer WHERE no_njb!=''
    group by no_njb having c>1
    ORDER BY `tr_h2_wo_dealer`.`no_njb` ASC LIMIT 1")->result();
    // send_json($result);
    foreach ($result as $res) {
      //Cek WO apa saja yang ada di no_njb ini
      $cek_wo = $this->db->query("SELECT wo.id_work_order 
        FROM tr_h2_wo_dealer wo 
        WHERE wo.no_njb='$res->no_njb' ORDER BY wo.id_work_order ASC")->result();
      foreach ($cek_wo as $key => $value) {
        // cek apakah wo sudah dibuat receipt
        $cek_receipt = $this->db->query("SELECT * from tr_h2_receipt_customer where id_referensi='$value->id_work_order'")->row();
        if ($cek_receipt==null) {
          $new_no_njb = $this->set_no_njb($res->no_njb);
          $set_update[]=[
            'id_work_order'=>$value->id_work_order,
            'old_no_njb'=>$res->no_njb,
            'new_no_njb'=>$new_no_njb,
          ];
          break;
        }
        if ($key==(count($cek_wo)-1)) {
          $new_no_njb = $this->set_no_njb($res->no_njb);
          $set_update[]=[
            'id_work_order'=>$value->id_work_order,
            'old_no_njb'=>$res->no_njb,
            'new_no_njb'=>$new_no_njb,
            'id_receipt'=>$cek_receipt->id_receipt
          ];
        }
      }
    }
    if (isset($set_update)) {
      // send_json($set_update);

      $this->db->trans_begin();
      foreach ($set_update as $key => $set) {
        $upd    = ['no_njb'=>$set['new_no_njb']];
        $cond   = ['id_work_order'=>$set['id_work_order']];

        $this->db->update('tr_h2_wo_dealer',$upd,$cond);

        if (isset($set['id_receipt'])) {
          $upd  = ['id_referensi'=>$set['new_no_njb']];
          $cond   = ['id_receipt'=>$set['id_receipt'],'id_referensi'=>$set['old_no_njb']];
          $this->db->update('tr_h2_receipt_customer_transaksi',$upd,$cond);
        }
      }
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo 'gagal';
      } else {
        $this->db->trans_commit();
        log_message('ERROR','Normalisasi No. NJB. raw data : '.json_encode($set_update));
        send_json($set_update);
      }
    }
  }
  function set_no_njb($no_njb){
    $njb_cek = substr($no_njb,0,15);
    $cek = $this->db->query("SELECT RIGHT(no_njb,4) no_njb FROM tr_h2_wo_dealer WHERE no_njb like'%$njb_cek%' ORDER BY no_njb DESC")->row();
    $new_no_njb=$njb_cek.'/'.sprintf('%04d',$cek->no_njb+1);
    $this->cek_no_njb($new_no_njb);
    $new_no_njb=$_SESSION['njb_set'];
    return  $new_no_njb;
  }
  function cek_no_njb($no_njb)
  {
    if (in_array($no_njb,$_SESSION['njb'])) {
      $new_no_njb = substr($no_njb,0,15).'/'.sprintf('%04d',substr($no_njb,-4)+1);
      $this->cek_no_njb($new_no_njb);
    }else{
      $_SESSION['njb_set']    = $no_njb;
      $_SESSION['njb'][]      = $no_njb;
    }
  }
}
