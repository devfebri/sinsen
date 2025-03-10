<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Claim_sales_program extends CI_Controller
{



  var $tables =   "tr_do_dealer";

  var $folder =   "h1";

  var $page   =   "claim_sales_program";

  var $pk     =   "no_do";

  var $title  =   "Claim Sales Program";



  public function __construct()

  {

    parent::__construct();
    //===== Load Database =====
    $this->load->database();

    $this->load->helper('url');

    //===== Load Model =====

    $this->load->model('m_admin');
    $this->load->model('M_business_control_h1', 'mbc');

    //===== Load Library =====

    $this->load->library('upload');



    //---- cek session -------//    

    $name = $this->session->userdata('nama');

    $auth = $this->m_admin->user_auth($this->page, "select");

    $sess = $this->m_admin->sess_auth();

    if ($name == "" or $auth == 'false' or $sess == 'false') {

      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
    }
  }

  protected function template($data)

  {

    $name = $this->session->userdata('nama');

    if ($name == "") {

      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {

      $data['id_menu'] = $this->m_admin->getMenu($this->page);

      $data['group']  = $this->session->userdata("group");

      $this->load->view('template/header', $data);

      $this->load->view('template/aside');

      $this->load->view($this->folder . "/" . $this->page);

      $this->load->view('template/footer');
    }
  }

  // public function index()

  // {       

  //   $data['isi']    = $this->page;    

  //   $data['title']  = $this->title;                             

  //   $data['set']    = "view";       
  //   if (isset($_POST['submit'])) {
  //     $data['id_program_md']  = $id_program_md  = $this->input->post('id_program_md');
  //     $data['id_program_ahm'] = $id_program_ahm  = $this->input->post('id_program_ahm');
  //     $data['id_dealer']      = $id_dealer  = $this->input->post('id_dealer');
  //     $data['dt']     = $this->mbc->get_data_claim($id_program_ahm,$id_dealer,$id_program_md,null);
  //   }else{
  //     $data['dt']     = $this->mbc->get_data_claim(null,null,null,null);
  //   }

  //   $this->template($data);     

  // }

  public function index()
  {
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "view";
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data       = array();
    $id_menu    = $this->input->post('id_menu');
    $group      = $this->input->post('group');
    $edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
    foreach ($fetch_data->result() as $rs) {
      $status = '<span class="label label-warning">Proses</span>';
      $button         = '';
      $btn_edit       = "<a $edit href='h1/claim_sales_program/edit_dokumen?idd=$rs->id_dealer&ipm=$rs->id_program_md' class='btn btn-flat btn-warning btn-xs'><i class='fa fa-edit'></i> Edit Dokumen</a>";
      $btn_verifikasi = "<a $edit href='h1/claim_sales_program/verifikasi?idd=$rs->id_dealer&ipm=$rs->id_program_md' class='btn btn-flat btn-info btn-xs'><i class='fa fa-check'></i> Verifikasi</a>";
      $btn_detail     = "<a href='h1/claim_sales_program/detail?idd=$rs->id_dealer&ipm=$rs->id_program_md' class='btn btn-flat btn-success btn-xs'><i class='fa fa-eye'></i> View</a>";
      if ($rs->tot_revisi > 0) {
        $status  = '<span class="label label-primary">Waiting</span>';
      }
      $tot_gantung = $this->db->query("SELECT COUNT(id_sales_order) c
                    FROM tr_claim_dealer AS tcd
                    WHERE STATUS = 'ajukan' 
                    AND tcd.id_program_md='$rs->id_program_md' AND tcd.id_dealer='$rs->id_dealer'")->row()->c;
      if ($tot_gantung > 0) {
        $status = '<span class="label label-warning">Proses</span>';
      }
      $button         = $btn_detail . ' ' . $btn_verifikasi . ' ' . $btn_edit;
      $sub_array = array();
      $sub_array[] = $rs->id_program_md;
      $sub_array[] = $rs->id_program_ahm;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->periode_awal . ' s/d ' . $rs->periode_akhir;
      $sub_array[] = $status;
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
  public function make_query($no_limit = null)
  {
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start,$length";
    $order  = '';
    $search = $this->input->post('search')['value'];

    if (isset($_POST["order"])) $order     = $_POST["order"];
    if ($no_limit == 'y') $limit = '';

    $id_dealer      = $this->input->post('id_dealer');
    $id_program_md  = $this->input->post('id_program_md');
    $id_program_ahm = $this->input->post('id_program_ahm');

    $status         = " IS NULL";
    $status_in      = "'ajukan','approved','rejected'";
    return $this->mbc->fetch_data($id_dealer, $start, $length, $search, $order, $limit, $id_program_md, $id_program_ahm, $status, $status_in);
  }

  function get_filtered_data()
  {
    return $this->make_query('y')->num_rows();
  }

  public function fetch_history()
  {
    $fetch_data = $this->make_query_history();
    $data       = array();
    $id_menu    = $this->input->post('id_menu');
    $group      = $this->input->post('group');
    $edit       = $this->m_admin->set_tombol($id_menu, $group, 'edit');
    foreach ($fetch_data->result() as $rs) {
      $status      = '<span class="label label-success">Closed</span>';
      $button      = "<a href='h1/claim_sales_program/detail?idd=$rs->id_dealer&ipm=$rs->id_program_md' class='btn btn-flat btn-success btn-xs'><i class='fa fa-eye'></i> View</a>";
      $sub_array   = array();
      $sub_array[] = $rs->id_program_md;
      $sub_array[] = $rs->id_program_ahm;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->periode_awal . ' s/d ' . $rs->periode_akhir;
      $sub_array[] = $status;
      $sub_array[] = $button;
      $data[]      = $sub_array;
    }

    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->get_filtered_data_history(),
      "data"            =>     $data
    );
    echo json_encode($output);
  }
  public function make_query_history($no_limit = null)
  {
    $start  = $this->input->post('start');
    $length = $this->input->post('length');
    $limit  = "LIMIT $start,$length";
    $order  = '';
    $search = $this->input->post('search')['value'];

    if (isset($_POST["order"])) $order     = $_POST["order"];
    if ($no_limit == 'y') $limit = '';

    $id_dealer      = $this->input->post('id_dealer');
    $id_program_md  = $this->input->post('id_program_md');
    $id_program_ahm = $this->input->post('id_program_ahm');
    $status         = " ='close'";
    return $this->mbc->fetch_data($id_dealer, $start, $length, $search, $order, $limit, $id_program_md, $id_program_ahm, $status);
  }

  function get_filtered_data_history()
  {
    return $this->make_query_history('y')->num_rows();
  }

  // public function add()

  // {       

  //   $data['isi']    = $this->page;    

  //   $data['title']  = $this->title;                             

  //   $data['set']    = "insert";       

  //   $this->template($data);     

  // }
  function detail()
  {
    $idd = $this->input->get('idd');
    $ipm = $this->input->get('ipm');
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "detail";
    $row = $this->mbc->get_claim_md($idd, $ipm);
    if ($row->num_rows() > 0) {
      $data['row'] = $row->row();
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_sales_program'>";
    }
  }

  function verifikasi()
  {
    $idd = $this->input->get('idd');
    $ipm = $this->input->get('ipm');
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "verifikasi";
    $row = $this->mbc->get_claim_md($idd, $ipm);
    if ($row->num_rows() > 0) {
      $data['row'] = $row->row();
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_sales_program'>";
    }
  }
  public function history()

  {

    $data['isi']    = $this->page;

    $data['title']  = $this->title;

    $data['set']    = "history";

    $this->template($data);
  }



  public function getDealer()

  {

    $id_program_md  = $this->input->post('id_program_md');

    // $get_dealer  = $this->db->query("SELECT * FROM tr_sales_program_dealer 

    //        inner join ms_dealer on tr_sales_program_dealer.id_dealer=ms_dealer.id_dealer

    //        WHERE id_program_md='$id_program_md'");

    $get_dealer   = $this->db->query("SELECT id_dealer,kode_dealer_md,nama_dealer FROM ms_dealer where h1=1 ORDER BY nama_dealer ASC");

    if ($get_dealer->num_rows() > 0) {

      echo "<option value=''>- choose -</option>";

      foreach ($get_dealer->result() as $key => $rs) {

        echo "<option value='$rs->id_dealer'>$rs->kode_dealer_md | $rs->nama_dealer</option>";
      }
    }
  }



  public function generate()

  {

    $id_program_md = $this->input->post('id_program_md');
    $id_claim_sp   = $this->input->post('id_claim_sp');
    $id_dealer     = $this->input->post('id_dealer');
    $mode          = $this->input->post('mode');
    $no_reset      = $this->input->post('no_reset');

    if ($mode == 'new') {
      if ($no_reset == null) {
        $this->mbc->get_generate_new($id_dealer, $id_program_md);
      }
      if (isset($_SESSION['generate_new'])) {
        $data_detail = $_SESSION['generate_new'];
      }
      $index_sess = 'generate_new';
    }

    if ($mode == 'detail') {
      if ($no_reset == null) {
        $this->mbc->get_generate_detail($id_dealer, $mode, $id_program_md);
      }
      // $data_detail = $_SESSION['generate_detail'];
      $data_detail = isset($_SESSION['generate_detail']) ? $_SESSION['generate_detail'] : 'kosong';

      $index_sess = 'generate_detail';
    }

    if ($mode == 'verifikasi') {
      if ($no_reset == null) {
        $this->mbc->get_generate_detail($id_dealer, $mode, $id_program_md);
      }
      $data_detail = isset($_SESSION['generate_verifikasi']) ? $_SESSION['generate_verifikasi'] : 'kosong';
      $index_sess = 'generate_verifikasi';
    }


    // $detail      = $this->db->query("SELECT *,tr_claim_dealer.status FROM tr_claim_dealer 

    //            INNER jOIN tr_sales_order on tr_claim_dealer.id_sales_order=tr_sales_order.id_sales_order

    //            INNER JOIN tr_spk on tr_sales_order.no_spk = tr_spk.no_spk

    //  WHERE tr_claim_dealer.id_dealer='$id_dealer' AND tr_claim_dealer.id_program_md='$id_program_md' AND tr_claim_dealer.status='ajukan'");

    // $detail = $this->mbc->get_generate($id_dealer,$id_program_md);
    // echo json_encode($data_detail);
    // exit;


    if (isset($data_detail)) {
      $data['detail']     = $data_detail;
      $data['set']        = 'generate';
      $data['index_sess'] = $index_sess;
      $data['mode']       = $mode;
      $this->load->view('h1/t_claim_sales_program_generate', $data);
    } else {

      echo 'kosong';
    }
  }



  public function getSyarat()

  {

    $id_claim      = $data['id_claim']     = $this->input->post('id_claim');
    $id_program_md = $data['id_program_md']     = $this->input->post('id_program_md');
    $id_dealer = $data['id_dealer']     = $this->input->post('id_dealer_syarat');
    $claim         = $this->mbc->get_claim($id_claim);
    $detail_key    = $this->input->post('key');
    $mode          = $this->input->post('mode');

    // exit;

    if ($claim->num_rows() > 0) {

      $data['row']  = $claim->row();
      $data['set']  = 'showdatamodal';
      $data['mode'] = $mode;
      $data['detail_key']  = $detail_key;

      // if (isset($_POST['mode'])) {

      //   $data['mode'] = 'edit';

      // }else{

      //   $data['mode']='';

      // }
      $index_sess = 'generate_new';
      if ($mode == 'insert') {
        $index_sess = 'generate_new';
      }
      if ($mode == 'detail') {
        $index_sess = 'generate_detail';
      }

      if ($mode == 'verifikasi') {
        $index_sess = 'generate_verifikasi';
      }

      // if (isset($_SESSION[$index_sess][$detail_key]['data']['syarat'])) {
      //   echo 'in';
      //   // $data['detail'] = $_SESSION[$index_sess][$detail_key]['data']['syarat'];
      // } else {
        $get_syarat = $this->mbc->get_syarat_claim($id_claim, $id_program_md);
        $_SESSION[$index_sess][$detail_key]['data']['syarat'] = $get_syarat->result_array();
      // }

      $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi_dealer'] = $claim->row()->perlu_revisi;
      $data['get_syarat'] = $_SESSION[$index_sess][$detail_key]['data']['syarat'];
      $data['perlu_revisi_dealer'] = $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi_dealer'];

      $this->load->view('h1/t_claim_sales_program_generate', $data);
    } else {
      echo 'kosong';
    }
  }

  public function saveCekSyarat(){
    $r          = $this->input->post('row');
    $id_claim   = $this->input->post('id_claim');
    $mode       = $this->input->post('mode');
    $detail_key = $this->input->post('detail_key');
    $perlu_revisi_dealer = $this->input->post('cek_perlu_revisi_dealer');

    $index_sess = 'generate_new';
    if ($mode == 'insert') {
      $index_sess = 'generate_new';
    }
    if ($mode == 'verifikasi') {
      $index_sess = 'generate_verifikasi';
    }

    //Cek Syarat Ada Dipilih Atau Tidak
    $cek  = 0;
    for ($i = 0; $i < $r; $i++) {
      $dt_syarat[$i]['id'] = $this->input->post('id_' . $i);
      $dt_syarat[$i]['id_claim'] = $id_claim;
      $dt_syarat[$i]['id_syarat_ketentuan'] = $this->input->post('id_syarat_ketentuan_' . $i);
      $dt_syarat[$i]['syarat_ketentuan'] = $this->input->post('syarat_ketentuan_' . $i);
      $dt_syarat[$i]['checklist_reject_md'] = $this->input->post('cek_' . $i);
      $alasan_reject = $dt_syarat[$i]['alasan_reject'] = $this->input->post('alasan_reject_' . $i);
      $cek  += $this->input->post('cek_' . $i);
      
      if ($alasan_reject == null || $alasan_reject == '') {
        $alasan_reject = null;
      }

      $upd_syarat[$id_claim][] = [
        'id'=> $dt_syarat[$i]['id'] ,
        // 'id_syarat_ketentuan' => $dt_syarat[$i]['id_syarat_ketentuan'],
        'checklist_reject_md' => $dt_syarat[$i]['checklist_reject_md'] == 1 ? $dt_syarat[$i]['checklist_reject_md'] : 0,
        'alasan_reject' => $alasan_reject,
      ];
    }

    if ($cek > 0) {
      $status_claim    = 'rejected';
    } else {
      $status_claim    = 'approved';
    }

    $_SESSION[$index_sess][$detail_key]['data']['syarat'] = $dt_syarat;
    $_SESSION[$index_sess][$detail_key]['data']['status'] = $status_claim;
    
    if($perlu_revisi_dealer== 1){
      $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi'] = 1;
      $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi_dealer'] = 'Ya';
      $status_claim    = 'rejected';
    }else{
      $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi'] = 0;
      $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi_dealer'] = '';
    }

    if (isset($_SESSION[$index_sess][$detail_key]['data']['perlu_revisi'])) {
      if ($status_claim == 'approved') {
        $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi'] = 0;
        $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi_dealer'] = '';
      }
    }

    // update database utk update nilai perlu revisi ikut function save_verifikasi
    // tombol close ada di function close
   
    // update field perlu revisi dan status claim dealer
    // $this->save_verifikasi(); // hilangkan proses save, tambahkan fiedl perlu revisi
    // $perlu_revisi=0;
		// if($this->input->post('perlu_revisi')=='on'){
		// 	$perlu_revisi=1;
		// }

    
    $waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id      = $this->session->userdata('id_user');
    $tabel         = 'tr_claim_sales_program';
    $id_dealer     = $this->input->post('id_dealer_syarat');
    $id_program_md = $this->input->post('id_program_md_syarat');
    $id_claim_sp   = $this->input->post('id_claim_sp');
    if ($id_claim_sp == '') {
      $id_claim_sp   = $this->cari_id();
    }

    if ($perlu_revisi_dealer == 1) {
      $status_proposal = 'rejected_by_md';
      $perlu_revisi    = 1;
    } else if($perlu_revisi_dealer == 0 && $cek > 0) {
      $status_proposal = 'rejected_by_md';
      $perlu_revisi    = 0;
    }else{
      $status_proposal = 'completed_by_md';
      $perlu_revisi    = 0;
    }

    $cek_sp      = $this->db->get_where('tr_claim_sales_program', ['id_dealer' => $id_dealer, 'id_program_md' => $id_program_md]);
    if ($cek_sp->num_rows() == 0) {
      $ins_data = [
        'id_claim_sp' => $id_claim_sp,
        'id_program_md' => $id_program_md,
        'id_dealer'     => $id_dealer,
        'created_at'    => $waktu,
        'created_by'    => $login_id,
      ];
      
      $cek_det_sp = $this->db->get_where('tr_claim_sales_program_detail', ['id_claim_sp' => $id_claim_sp, 'id_claim_dealer' => $id_claim_dealer]);
      if ($cek_det_sp->num_rows() == 0) {
        $nilai_potongan = $this->mbc->get_nilai_potongan($id_claim);
        $ins_data_detail = [
          'id_claim_sp' => $id_claim_sp,
          'id_claim_dealer' => $id_claim,
          'nilai_potongan'  => $nilai_potongan,
          'perlu_revisi'    => $perlu_revisi
        ];
      } 
    } else {
      $cek_det_sp = $this->db->get_where('tr_claim_sales_program_detail', ['id_claim_sp' => $cek_sp->row()->id_claim_sp, 'id_claim_dealer' => $id_claim]);
      if ($cek_det_sp->num_rows() == 0) {
        $nilai_potongan = $this->mbc->get_nilai_potongan($id_claim);
        $ins_data_detail = [
          'id_claim_sp' => $cek_sp->row()->id_claim_sp,
          'id_claim_dealer' => $id_claim,
          'nilai_potongan'  => $nilai_potongan,
          'perlu_revisi'    => $perlu_revisi
        ];
      } else {
        $upd_data_detail = [
          'id_claim_dealer' => $id_claim,
          'perlu_revisi' => $perlu_revisi
        ];
      }

      $upd_data = [
        'updated_at' => $waktu,
        'updated_by' => $login_id,
      ];
    }

    $upd_claim_dealer = [
      'status' => $status_claim,
      'status_proposal' => $status_proposal,
      'tgl_approve_reject_md' => $waktu
    ];

    $this->db->trans_begin();
    if (isset($ins_data)) {
      $this->db->insert('tr_claim_sales_program', $ins_data);
    }
    if (isset($upd_data)) {
      $this->db->update('tr_claim_sales_program', $upd_data, ['id_claim_sp' => $id_claim_sp]);
    }
    if (isset($ins_data_detail)) {
      $this->db->insert('tr_claim_sales_program_detail', $ins_data_detail);
    }
    if (isset($upd_data_detail)) {
      $this->db->update('tr_claim_sales_program_detail', $upd_data_detail, ['id_claim_dealer' => $id_claim]);
      // $this->db->where('id_claim_sp', $id_claim_sp);
      // $this->db->update_batch('tr_claim_sales_program_detail', $upd_data_detail, 'id_claim_dealer');
    }
    // $this->db->update_batch('tr_claim_dealer', $upd_claim_dealer, 'id_claim');
    
    $this->db->update('tr_claim_dealer', $upd_claim_dealer, ['id_claim' => $id_claim]);

    // if($id_claim=='202304120011'){
      if (isset($upd_syarat) && $cek > 0) {
        foreach ($upd_syarat as $key => $srt) {
          $this->db->where('id_claim', $key);
          $this->db->update_batch('tr_claim_dealer_syarat', $srt, 'id');
        }
      }
    // }else{
    //   if (isset($upd_syarat) && $cek > 0) {
    //     foreach ($upd_syarat as $key => $srt) {
    //       $this->db->where('id_claim', $key);
    //       $this->db->update_batch('tr_claim_dealer_syarat', $srt, 'id_syarat_ketentuan');

    //       /*
    //         $this->db->update('tr_claim_dealer_syarat',array(
    //           'filename'=> $dt_detail[$key]['filename'],
    //           'checklist_dealer' => $dt_detail[$key]['checklist_dealer'],
    //           'file' => $dt_detail[$key]['file'],
    //           'checklist_reject_md' => $dt_detail[$key]['checklist_reject_md'],
    //           'alasan_reject' => $dt_detail[$key]['alasan_reject']
    //         ), array('id'=>$rs->id_cd));
    //       */
    //     }
    //   }
    // }
    
    // if (isset($notif)) {
    //   $this->db->insert('tr_notifikasi', $notif); // belum ditambahkan koding utk notifikasi
    // }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $result = [
        'status' => 'error',
        'pesan' => ' Something went wrong',
        'detail' => $_SESSION[$index_sess][$detail_key]
      ];
    } else {
      $this->db->trans_commit();
      $result = [
        'status' => 'sukses',
        'pesan' => ' Berhasil',
        'detail' => $_SESSION[$index_sess][$detail_key]
        // 'link' => base_url('h1/claim_sales_program')
      ];
      // $_SESSION['pesan']  = "Data has been saved successfully";
      // $_SESSION['tipe']   = "success";
    }
    
    // $result = ['status' => 'sukses', 'detail' => $_SESSION[$index_sess][$detail_key]];
    echo json_encode($result);
  }

  
  public function saveCekSyarat_bak()
  {
    $r          = $this->input->post('row');
    $id_claim   = $this->input->post('id_claim');
    $mode       = $this->input->post('mode');
    $detail_key = $this->input->post('detail_key');
    $index_sess = 'generate_new';
    if ($mode == 'insert') {
      $index_sess = 'generate_new';
    }
    if ($mode == 'verifikasi') {
      $index_sess = 'generate_verifikasi';
    }
    // $detail   = $_SESSION[$index_sess][$detail_key];

    //Cek Syarat Ada Dipilih Atau Tidak
    $cek  = 0;
    for ($i = 0; $i < $r; $i++) {
      $dt_syarat[$i]['id'] = $this->input->post('id_' . $i);
      $dt_syarat[$i]['id_claim'] = $id_claim;
      $dt_syarat[$i]['id_syarat_ketentuan'] = $this->input->post('id_syarat_ketentuan_' . $i);
      $dt_syarat[$i]['syarat_ketentuan'] = $this->input->post('syarat_ketentuan_' . $i);
      $dt_syarat[$i]['checklist_reject_md'] = $this->input->post('cek_' . $i);
      $alasan_reject = $dt_syarat[$i]['alasan_reject'] = $this->input->post('alasan_reject_' . $i);
      $cek  += $this->input->post('cek_' . $i);
    }
    if ($cek > 0) {
      $status_claim    = 'rejected';
    } else {
      $status_claim    = 'approved';
    }

    $_SESSION[$index_sess][$detail_key]['data']['syarat'] = $dt_syarat;
    $_SESSION[$index_sess][$detail_key]['data']['status'] = $status_claim;
    if (isset($_SESSION[$index_sess][$detail_key]['data']['perlu_revisi'])) {
      if ($status_claim == 'approved') {
        $_SESSION[$index_sess][$detail_key]['data']['perlu_revisi'] = 0;
      }
    }

    $result = ['status' => 'sukses', 'detail' => $_SESSION[$index_sess][$detail_key]];
    echo json_encode($result);
  }

  public function setRevisi()
  {
    $data[0]['id']    = $this->input->post('id_claim');
    $data[0]['perlu_revisi']    = $this->input->post('perlu_revisi');
    $this->db->trans_begin();
    $this->db->update_batch('tr_claim_sales_program_detail', $data, 'id');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      echo 0;
    } else {
      $this->db->trans_commit();
      echo 1;
    }
  }

  public function cari_id()
  {
    $th         = date("Y");
    $bln          = date("m");
    $tgl_         = date("d");
    $tgl          = date("Y-m");
    $pr_num         = $this->db->query("SELECT id_claim_sp FROM tr_claim_sales_program WHERE LEFT(created_at,7)='$tgl' ORDER BY id_claim_sp DESC LIMIT 0,1");

    if ($pr_num->num_rows() > 0) {
      $row  = $pr_num->row();
      $id = substr($row->id_claim_sp, 7, 4);
      $kode = $th . $bln . sprintf("%04d", $id + 1);
    } else {
      $kode = $th . $bln . '0001';
    }

    return $kode;
  }

  // function save()
  // {
  //   $waktu       = gmdate("Y-m-d H:i:s", time()+60*60*7);
  //   $login_id    = $this->session->userdata('id_user');
  //   $tabel       = 'tr_claim_sales_program';
  //   $id_claim_sp = $this->cari_id();
  //   $id_dealer   = $this->input->post('id_dealer');

  //   $cek      = $this->m_admin->getByID($tabel,'id_claim_sp',$id_claim_sp)->num_rows();
  //   if($cek > 0){
  //     $result = ['status'=>'error','pesan'=>'Duplicate primary key !'];
  //     echo json_encode($result);
  //     exit;
  //   }
  //   $ins_data = ['id_claim_sp'=>$id_claim_sp,
  //                'id_program_md'=>$this->input->post('id_program_md'),
  //                'keterangan'=>$this->input->post('keterangan'),
  //                'id_dealer'=>$this->input->post('id_dealer'),
  //                'created_at'=>$waktu,
  //                'created_by'=>$login_id,
  //               ];
  //   $detail = $_SESSION['generate_new'];
  //   $cek_revisi=0;
  //   $cek_tot=0;
  //   foreach ($detail as $key=> $dtl) {
  //     if ($dtl['field']=='row') {
  //       if (isset($_POST['chk_revisi_'.$key])) {
  //         $status_proposal = 'rejected_by_md';
  //         $perlu_revisi    = 1;
  //         $cek_revisi++;
  //       }else{
  //         $status_proposal = 'completed_by_md';
  //         $perlu_revisi    = 0;
  //       }

  //       $id_claim       = $dtl['data']['id_claim'];
  //       $nilai_potongan = $this->mbc->get_nilai_potongan($id_claim);

  //       $upd_claim_dealer[]=['id_claim'=> $id_claim,
  //                            'status'=> $dtl['data']['status'],
  //                            'tgl_approve_reject_md'=> $waktu
  //                           ];

  //       $ins_data_detail[] = ['id_claim_sp'=>$id_claim_sp,
  //                            'id_claim_dealer'=>$id_claim,
  //                            'nilai_potongan'=> $nilai_potongan,
  //                            'perlu_revisi'=>$perlu_revisi
  //                          ];
  //       if (isset($dtl['data']['syarat'])) {
  //         foreach ($dtl['data']['syarat'] as $srt) {
  //           if ($srt['alasan_reject']==null || $srt['alasan_reject']=='') {
  //             $alasan_reject = null;
  //           }else{
  //             $alasan_reject = $srt['alasan_reject'];
  //           }
  //           $upd_syarat[$id_claim][]=['id_syarat_ketentuan'=> $srt['id_syarat_ketentuan'],
  //                          'checklist_reject_md'=> $srt['checklist_reject_md']==1?$srt['checklist_reject_md']:0,
  //                          'alasan_reject'=> $alasan_reject,
  //                         ];
  //         }
  //       }
  //       $cek_tot++;
  //     }
  //   }
  //   if ($cek_revisi>0) {
  //     $ket_reject = $cek_revisi==$cek_tot?'seluruh':'sebagian';
  //     $judul = "Reject Klaim Proposal By MD";
  //     $pesan = "Proposal Klaim telah ditolak oleh MD untuk $ket_reject SO. Silakan melihat Claim Report untuk detail klaim promosi.";
  //   }else {
  //     $judul = "Approved Klaim Proposal By MD";
  //     $pesan = "Proposal Klaim telah disetujui oleh MD untuk seluruh SO. Silakan melihat Claim Report untuk detail klaim promosi.";
  //   }
  //   $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori',['kode_notif'=>'ntf_klaim_prop_md_d'])->row();
  //   $notif = ['id_notif_kat'=> $ktg_notif->id_notif_kat,
  //         'id_referensi' => '',
  //         'judul'        => $judul,
  //         'pesan'        => $pesan,
  //         'link'         => $ktg_notif->link,
  //         'status'       => 'baru',
  //         'id_dealer'    => $id_dealer,
  //         'created_at'   => $waktu,
  //         'created_by'   => $login_id
  //        ];
  //   // $cek_trans = ['ins'=>$ins_data,
  //   //         'ins_data_detail'=>$ins_data_detail,
  //   //         'upd_claim_dealer'=>$upd_claim_dealer,
  //   //         'upd_syarat'=>isset($upd_syarat)?$upd_syarat:null,
  //   //         'notif'=>$notif,
  //   //        ];
  //   // echo json_encode($cek_trans);
  //   // exit; 
  //   $this->db->trans_begin();
  //     $this->db->insert('tr_claim_sales_program',$ins_data);
  //     $this->db->insert_batch('tr_claim_sales_program_detail',$ins_data_detail);
  //     $this->db->update_batch('tr_claim_dealer',$upd_claim_dealer,'id_claim');
  //     if (isset($upd_syarat)) {
  //       foreach ($upd_syarat as $key=> $srt) {
  //         $this->db->where('id_claim',$key);
  //         $this->db->update_batch('tr_claim_dealer_syarat', $srt, 'id_syarat_ketentuan');
  //       }
  //     }
  //     if (isset($notif)) {
  //       $this->db->insert('tr_notifikasi',$notif);      
  //     }
  //     if ($this->db->trans_status() === FALSE)
  //     {
  //       $this->db->trans_rollback();
  //       $rsp = ['status'=> 'error',
  //         'pesan'=> ' Something went wrong'
  //          ];
  //     }
  //     else
  //     {
  //       $this->db->trans_commit();
  //       $rsp = ['status'=> 'sukses',
  //               'link'=>base_url('h1/claim_sales_program')
  //              ];
  //       $_SESSION['pesan']  = "Data has been saved successfully";
  //       $_SESSION['tipe']   = "success";
  //     }
  //       echo json_encode($rsp);
  // }

  // function save_verifikasi()
  // {
  //   echo 'dinonaktifkan karena adanya project auto claim';die;

  //   $waktu         = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
  //   $login_id      = $this->session->userdata('id_user');
  //   $tabel         = 'tr_claim_sales_program';
  //   $id_dealer     = $this->input->post('id_dealer');
  //   $id_program_md = $this->input->post('id_program_md');
  //   $id_claim_sp   = $this->input->post('id_claim_sp');
  //   if ($id_claim_sp == '') {
  //     $id_claim_sp   = $this->cari_id();
  //   }
  //   $cek_sp      = $this->db->get_where($tabel, ['id_dealer' => $id_dealer, 'id_program_md' => $id_program_md]);
  //   if ($cek_sp->num_rows() == 0) {
  //     $ins_data = [
  //       'id_claim_sp' => $id_claim_sp,
  //       'id_program_md' => $id_program_md,
  //       'keterangan'    => $this->input->post('keterangan'),
  //       'id_dealer'     => $id_dealer,
  //       'created_at'    => $waktu,
  //       'created_by'    => $login_id,
  //     ];
  //   } else {
  //     $upd_data = [
  //       'keterangan' => $this->input->post('keterangan'),
  //       'updated_at' => $waktu,
  //       'updated_by' => $login_id,
  //     ];
  //   }

  //   $detail = $_SESSION['generate_verifikasi'];

  //   $cek_revisi = 0;
  //   $cek_tot = 0;
  //   foreach ($detail as $key => $dtl) {
  //     if ($dtl['field'] == 'row') {
  //       if (isset($_POST['chk_revisi_' . $key])) {
  //         $status_proposal = 'rejected_by_md';
  //         $perlu_revisi    = 1;
  //         $cek_revisi++;
  //       } else {
  //         $status_proposal = 'completed_by_md';
  //         $perlu_revisi    = 0;
  //       }

  //       $id_claim       = $dtl['data']['id_claim'];
  //       $cek_det_sp = $this->db->get_where('tr_claim_sales_program_detail', ['id_claim_dealer' => $id_claim]);
  //       if ($cek_det_sp->num_rows() == 0) {
  //         $nilai_potongan = $this->mbc->get_nilai_potongan($id_claim);
  //         $ins_data_detail[] = [
  //           'id_claim_sp' => $id_claim_sp,
  //           'id_claim_dealer' => $id_claim,
  //           'nilai_potongan'  => $nilai_potongan,
  //           'perlu_revisi'    => $perlu_revisi
  //         ];
  //       } else {
  //         $upd_data_detail[] = [
  //           'id_claim_dealer' => $id_claim,
  //           // 'nilai_potongan'=> $nilai_potongan,
  //           'perlu_revisi' => $perlu_revisi
  //         ];
  //       }

  //       $upd_claim_dealer[] = [
  //         'id_claim' => $id_claim,
  //         'status' => $dtl['data']['status'],
  //         'status_proposal' => $status_proposal,
  //         'tgl_approve_reject_md' => $waktu
  //       ];

  //       if (isset($dtl['data']['syarat'])) {
  //         foreach ($dtl['data']['syarat'] as $srt) {
  //           if ($srt['alasan_reject'] == null || $srt['alasan_reject'] == '') {
  //             $alasan_reject = null;
  //           } else {
  //             $alasan_reject = $srt['alasan_reject'];
  //           }
  //           $upd_syarat[$id_claim][] = [
  //             'id_syarat_ketentuan' => $srt['id_syarat_ketentuan'],
  //             'checklist_reject_md' => $srt['checklist_reject_md'] == 1 ? $srt['checklist_reject_md'] : 0,
  //             'alasan_reject' => $alasan_reject,
  //           ];
  //         }
  //       }
  //       $cek_tot++;
  //     }
  //   }
  //   if ($cek_revisi > 0) {
  //     $ket_reject = $cek_revisi == $cek_tot ? 'seluruh' : 'sebagian';
  //     $judul = "Reject Klaim Proposal By MD";
  //     $pesan = "Proposal Klaim telah ditolak oleh MD untuk $ket_reject SO. Silakan melihat Claim Report untuk detail klaim promosi.";
  //   } else {
  //     $judul = "Approved Klaim Proposal By MD";
  //     $pesan = "Proposal Klaim telah disetujui oleh MD untuk seluruh SO. Silakan melihat Claim Report untuk detail klaim promosi.";
  //   }

  //   $ktg_notif      = $this->db->get_where('ms_notifikasi_kategori', ['kode_notif' => 'ntf_klaim_prop_md_d'])->row();
  //   $notif = [
  //     'id_notif_kat' => $ktg_notif->id_notif_kat,
  //     'id_referensi' => '',
  //     'judul'        => $judul,
  //     'pesan'        => $pesan,
  //     'link'         => $ktg_notif->link,
  //     'status'       => 'baru',
  //     'id_dealer'    => $id_dealer,
  //     'created_at'   => $waktu,
  //     'created_by'   => $login_id
  //   ];
  //   // $cek_trans = ['upd_claim_dealer'=>$upd_claim_dealer,
  //   //         'upd_data'=>isset($upd_data)?$upd_data:null,
  //   //         'ins_data'=>isset($ins_data)?$ins_data:null,
  //   //         'upd_data_detail'=>isset($upd_data_detail)?$upd_data_detail:null,
  //   //         'ins_data_detail'=>isset($ins_data_detail)?$ins_data_detail:null,
  //   //         'upd_syarat'=>isset($upd_syarat)?$upd_syarat:null,
  //   //         'notif'=>$notif,
  //   //        ];
  //   // echo json_encode($cek_trans);
  //   // exit; 
  //   $this->db->trans_begin();
  //   if (isset($upd_data)) {
  //     $this->db->update('tr_claim_sales_program', $upd_data, ['id_claim_sp' => $id_claim_sp]);
  //   }
  //   if (isset($ins_data)) {
  //     $this->db->insert('tr_claim_sales_program', $ins_data);
  //   }
  //   if (isset($upd_data_detail)) {
  //     $this->db->where('id_claim_sp', $id_claim_sp);
  //     $this->db->update_batch('tr_claim_sales_program_detail', $upd_data_detail, 'id_claim_dealer');
  //   }
  //   if (isset($ins_data_detail)) {
  //     $this->db->insert_batch('tr_claim_sales_program_detail', $ins_data_detail);
  //   }
  //   $this->db->update_batch('tr_claim_dealer', $upd_claim_dealer, 'id_claim');
  //   if (isset($upd_syarat)) {
  //     foreach ($upd_syarat as $key => $srt) {
  //       $this->db->where('id_claim', $key);
  //       $this->db->update_batch('tr_claim_dealer_syarat', $srt, 'id_syarat_ketentuan');
  //     }
  //   }
  //   if (isset($notif)) {
  //     $this->db->insert('tr_notifikasi', $notif);
  //   }
  //   if ($this->db->trans_status() === FALSE) {
  //     $this->db->trans_rollback();
  //     $rsp = [
  //       'status' => 'error',
  //       'pesan' => ' Something went wrong'
  //     ];
  //   } else {
  //     $this->db->trans_commit();
  //     $rsp = [
  //       'status' => 'sukses',
  //       'link' => base_url('h1/claim_sales_program')
  //     ];
  //     $_SESSION['pesan']  = "Data has been saved successfully";
  //     $_SESSION['tipe']   = "success";
  //   }
  //   echo json_encode($rsp);
  // }

  function edit_dokumen()
  {
    $idd = $this->input->get('idd');
    $ipm = $this->input->get('ipm');
    $data['isi']    = $this->page;
    $data['title']  = $this->title;
    $data['set']    = "edit_dokumen";
    $row = $this->mbc->get_claim_md($idd, $ipm);
    if ($row->num_rows() > 0) {
      $data['rows'] = $row->row();
      $data['detail'] = $this->mbc->get_claim_md_detail($idd, $ipm);
      $this->template($data);
    } else {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_sales_program'>";
    }
  }

  function save_edit()
  {
    $id_claim_sp   = $this->input->post('id_claim_sp');
    $no_po_leasing = $this->input->post('no_po_leasing');
    $tgl_po        = $this->input->post('tgl_po_leasing');
    $so            = $this->input->post('id_sales_order');
    $waktu       = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id    = $this->session->userdata('id_user');
    foreach ($no_po_leasing as $key => $npl) {
      $upd_so[] = [
        'id_sales_order' => $so[$key],
        'tgl_po_leasing' => $tgl_po[$key],
        'no_po_leasing' => $npl,
        'updated_at' => $waktu,
        'updated_by' => $login_id
      ];
    }
    $this->db->trans_begin();
    $this->db->update_batch('tr_sales_order', $upd_so, 'id_sales_order');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $_SESSION['pesan']  = "Something went wrong !";
      $_SESSION['tipe']   = "danger";
      echo "<script>history.go(-1)</script>";
    } else {
      $this->db->trans_commit();
      $_SESSION['pesan']  = "Data has been updated successfully";
      $_SESSION['tipe']   = "success";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h1/claim_sales_program'>";
    }
  }

  function close()
  {
    $id_claim_sp = $this->input->post('id_claim_sp');
    $waktu       = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $login_id    = $this->session->userdata('id_user');
    $upd = [
      'status' => 'close',
      'closed_at' => $waktu,
      'closed_by' => $login_id,
    ];
    $cek_gantung = $this->mbc->cek_gantung($id_claim_sp);
    if ($cek_gantung > 0) {
      $result = ['status' => 'error', 'pesan' => 'Masih ada unit dengan status gantung !'];
      echo json_encode($result);
      exit();
    }
    // echo json_encode($upd);
    // exit();
    $this->db->trans_begin();
    $this->db->update('tr_claim_sales_program', $upd, ['id_claim_sp' => $id_claim_sp]);
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
        'link' => base_url('h1/claim_sales_program')
      ];
      $_SESSION['pesan']  = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    echo json_encode($rsp);
  }

  function tes_query()
  {
    $sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
    foreach ($sql->result() as $isi) {
      $spk = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
          WHERE jenis_beli = 'Kredit' AND tr_spk.id_dealer = '$id_dealer'
          AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
      $spk1 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
          WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0 AND 10 AND tr_spk.id_dealer = '$id_dealer'
          AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
      $spk2 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
          WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 11 AND 20 AND tr_spk.id_dealer = '$id_dealer'
          AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
      $spk3 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
          WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 > 20 AND tr_spk.id_dealer = '$id_dealer'
          AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
      if ($spk->jum != 0) {
        $isi_spk1 = round((($spk1->jum / $spk->jum) * 100), 2);
        $isi_spk2 = round((($spk2->jum / $spk->jum) * 100), 2);
        $isi_spk3 = round((($spk3->jum / $spk->jum) * 100), 2);
      } else {
        $isi_spk1 = round((($spk1->jum) * 100), 2);
        $isi_spk2 = round((($spk2->jum) * 100), 2);
        $isi_spk3 = round((($spk3->jum) * 100), 2);
      }
    }
  }

  function download_image(){
    //  $data['b64'] = ''; 
    // set_time_limit(0);
    $id = $this->input->get('id');
    $id_claim = $this->input->get('id_claim');
    $get_data = $this->db->query("select file, filename from tr_claim_dealer_syarat where id='$id'")->row();
    
    $filename='no_file';
    if(count($get_data) > 0){
      $b64 = $get_data->file;
      $ext = explode('.',$get_data->filename)[1];
      $filename = $get_data->filename;
    }

    $data['b64'] = $b64;
    $data['ext'] = $ext;
    $data['filename'] = $filename;
    $this->load->view('h1/t_download_image', $data);
    // $this->load->view('dealer/t_download_juklak', $data);
  }

  function download_image2(){
    //  $data['b64'] = ''; 
    // set_time_limit(0);
    $id = $this->input->get('id');
    $get_data = $this->db->query("select file, filename from tr_claim_dealer_syarat where id='$id'")->row();
    
    $filename='no_file';
    if(count($get_data) > 0){
      $b64 = $get_data->file;
      $ext = explode('.',$get_data->filename)[1];
      $filename = $get_data->filename;
    }

    $file = basename($filename);
    $file = base_url('/assets/syarat_claim/'.$file); // bs dipreview

    // $file_url = base_url('/assets/syarat_claim/'.$file);

    
    // $data = file_get_contents($file);
    // echo $data;die;    
    // echo $file;die;

    if(!file_exists($file)){ // file does not exist
        die('file not found');
    } else {
        // header("Cache-Control: public");
        // header("Content-Description: File Transfer");
        // header("Content-Disposition: attachment; filename=$file");
        // header("Content-Type: application/zip");
        // header("Content-Transfer-Encoding: binary");

        // // read the file from disk
        // readfile($file);
        
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file) . "\""); 
        readfile($file); 
    }
  }

}
