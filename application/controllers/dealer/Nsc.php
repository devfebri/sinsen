<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nsc extends CI_Controller
{

  var $folder = "dealer";
  var $page   = "nsc";
  var $title  = "Nota Suku Cadang (NSC)";

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
    $this->load->model('m_h2_billing', 'mh2');
    $this->load->model('m_h2_api', 'm_api');
    $this->load->model('m_h2_master', 'mh2m');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_h23_nsc', 'm_nsc');
    $this->load->model('notifikasi_model', 'notifikasi');
    $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');

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

    // $data['wo']    = $this->mh2->getNSC()->result();
    // send_json($data);
    $this->template($data);
  }

  public function fetch()
  {
    $fetch_data = $this->make_query();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';

      $sub_array[] = '<a href="dealer/nsc/detail_nsc?id=' . $rs->no_nsc . '">' . $rs->no_nsc . '</a>';;
      $sub_array[] = $rs->tgl_nsc;
      $nomor_so_wo = $this->mh2->getNomorSODariWO(['id_work_order' => $rs->id_referensi])->row();
      $sub_array[] = $rs->referensi == 'sales' ? $rs->id_referensi : $nomor_so_wo->group_so;
      $sub_array[] = $rs->referensi == 'work_order' ? $rs->id_referensi : '';
      $sub_array[] = $rs->nama_customer;
      $sub_array[] = $rs->no_hp;
      $sub_array[] = $rs->tipe_ahm;
      $sub_array[] = $rs->no_polisi;
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
      'order_column' => ['no_nsc', 'tgl_nsc', 'nomor_so', 'id_work_order', 'nama_customer', 'no_telp', 'tipe_ahm', 'no_polisi'],
      'search' => $this->input->post('search')['value'],
    ];

    if ($recordsFiltered == true) {
      return $this->m_nsc->getNSC($filter)->num_rows();
    } else {
      return $this->m_nsc->getNSC($filter)->result();
    }
  }

  public function detail_nsc()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Detail NSC';
    $data['mode']  = 'detail_nsc';
    $data['set']   = "form";
    $no_nsc = $this->input->get('id');

    $filter = ['no_nsc' => $no_nsc];
    $get_nsc = $this->mh2->getNSC($filter);

    if ($get_nsc->num_rows() > 0) {
      $nsc = $get_nsc->row();
      $filter = ['id_work_order' => $nsc->id_referensi];
      $wo = $this->m_wo->get_sa_form($filter);
      if ($wo->num_rows() > 0) {
        $wo = $wo->row();
        $nsc->tgl_servis         = $wo->tgl_servis;
        $nsc->id_karyawan_dealer = $wo->id_karyawan_dealer;
        $nsc->nama_lengkap       = $wo->nama_lengkap;
        $nsc->kd_dealer_so       = $wo->kode_dealer_md;
        $nsc->dealer_so          = $wo->nama_dealer;
        $nsc->tipe_ahm           = $wo->tipe_ahm;
        $nsc->no_polisi          = $wo->no_polisi;
      } else {
        $so = $this->m_nsc->getSOH3($nsc->id_referensi);
        $nsc->dealer_so       = $so->nama_dealer;
        $nsc->kd_dealer_so       = $so->kode_dealer_md;
        $nsc->nama_lengkap       = $so->nama_lengkap;
      }
      $filter = ['no_nsc' => $nsc->no_nsc];
      $nsc->parts = $this->mh2->getNSCParts($filter)->result();
      $data['row'] = $nsc;
      $data['pkp'] = $nsc->pkp;
      $data['tampil_ppn'] = $nsc->tampil_ppn;
      $data['tampil_ppn'] = 0;
      // send_json($nsc);
      $this->template($data);
    } else {
      $_SESSION['pesan']   = "Data not found !";
      $_SESSION['tipe']   = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/nsc'>";
    }
  }


  public function create_nsc()
  {
    $data['isi']        = $this->page;
    $data['title']      = $this->title;
    $data['mode']       = 'create_nsc';
    $data['set']        = "form";
    $data['pkp']        = dealer()->pkp;
    // $data['tampil_ppn'] = dealer()->tampil_ppn_h23;
    $data['tampil_ppn'] = 0;
    $this->template($data);
  }

  function saveNSC()
  {

    $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');

    $this->load->model('h3_dealer_sales_order_model', 'h3_so');
    $post_parts = $this->input->post('parts');
    $waktu     = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $tanggal   = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $login_id  = $this->session->userdata('id_user');
    $post      = $this->input->post();
    $no_nsc    = $this->get_no_nsc();
    $id_dealer = $this->m_admin->cari_dealer();
    $uang_muka = 0;
    if (isset($post['uang_muka'])) {
      $uang_muka = $post['uang_muka'] == '' ? 0 : $post['uang_muka'];

      //Update Uang Jaminan, Menambahkan NSC
      if (isset($post['no_inv_jaminan'])) {
        $exp_uj = explode('; ', $post['no_inv_jaminan']);
        foreach ($exp_uj as $uj) {
          $upd_uang_jaminan[] = [
            'no_inv_uang_jaminan' => $uj,
            'no_nsc' => $no_nsc,
            'created_nsc_at' => $waktu,
            'created_nsc_by' => $login_id,
          ];
        }
      }
    }
    $insert = [
      'no_nsc'            => $no_nsc,
      'tgl_nsc'           => $tanggal,
      'id_dealer'         => $id_dealer,
      'pkp'               => dealer()->pkp,
      // 'tampil_ppn'        => dealer()->tampil_ppn_h23,
      'tampil_ppn'        => 0,
      'referensi'         => $post['referensi'],
      'id_customer'       => $post['id_customer'],
      'id_referensi'      => isset($post['nomor_so']) ? $post['nomor_so'] : $post['id_work_order'],
      'no_inv_jaminan'    => isset($post['no_inv_jaminan']) ? $post['no_inv_jaminan'] : null,
      'uang_muka'         => $uang_muka,
      'tot_nsc'           => $post['tot_nsc'],
      'created_at'        => $waktu,
      'created_by'        => $login_id,
    ];

    $this->db->trans_begin();

    //Referensi Dari SO
    if (isset($_POST['nomor_so'])) {
      $filter = [
        'nomor_so' => $post['nomor_so'],
        'qty_besar_dari_nol' => true
      ];
      $get_parts = $this->m_api->getSoPart($filter);
      if ($get_parts->num_rows() > 0) {
        foreach ($get_parts->result() as $pr) {
          $prt = $this->db->get_where('ms_part', ['id_part' => $pr->id_part])->row();
          $parts[] = [
            'no_nsc'       => $no_nsc,
            'id_part'      => $pr->id_part,
            'id_part_int'  => $prt->id_part_int,
            'harga_beli'   => $pr->harga_saat_dibeli,
            'qty'          => $pr->qty,
            'tipe_diskon'  => $pr->tipe_diskon,
            'diskon_value' => $pr->diskon_value,
            'id_promo'     => $pr->id_promo,
          ];
        }
      }
      $nomor_so = $post['nomor_so'];
      $upd_so = ['status' => 'Closed'];
      $get_so = $this->m_api->getSo($filter)->row();
      $upd_ps[] = ['status' => 'Closed', 'nomor_ps' => $get_so->nomor_ps];
      $insert['id_referensi_int'] = $get_so->nomor_so_int;

      $this->h3_so->update_status_po_untuk_sales_order($get_so->nomor_so);
      if ($get_so->pembelian_dari_dealer_lain == 1) {
        $insert['id_dealer_pembeli'] = $get_so->id_dealer_pembeli;
      }
    }

    //Referensi Dari WO
    if (isset($_POST['id_work_order'])) {
      $wo = $this->db->query("SELECT id_work_order_int FROM tr_h2_wo_dealer WHERE id_work_order='{$post['id_work_order']}'")->row();
      $insert['id_referensi_int'] = $wo->id_work_order_int;

      $this->m_wo->updateGrandTotalWO($post['id_work_order']);


      $this->load->model('h3_dealer_stock_model', 'dealer_stock');
      foreach ($post_parts as $part) {

        // Validasi Ke dealer stock
        $conds_stock = [
          'id_part'   => $part['id_part'],
          'id_gudang' => $part['id_gudang'],
          'id_rak'    => $part['id_rak'],
          'id_dealer' => $id_dealer,
        ];

        $id_dealer_stock = '';
        $cek_stock = $this->db->get_where('ms_h3_dealer_stock', $conds_stock)->row();
        if ($cek_stock == null) {
          log_message('ERROR', 'Cek Stock di NSC. raw data : ' . json_encode($part));
          $rsp = [
            'status'    => 'error',
            'pesan'     => "Data tidak ditemukan pada tabel stok dealer"
          ];
          send_json($rsp);
        }else{
          $id_dealer_stock  = $cek_stock->id;
        }

        //Set Parts
        $parts[] = [
          'no_nsc'       => $no_nsc,
          'id_part'      => $part['id_part'],
          'id_part_int'  => $part['id_part_int'],
          'harga_beli'   => $part['harga_beli'],
          'qty'          => $part['qty'],
          'nomor_so_wo'  => $part['nomor_so'],
          'tipe_diskon'  => $part['tipe_diskon'],
          'id_promo'     => $part['id_promo'],
          'diskon_value' => $part['diskon_value'],
        ];

        // Cek Stok AVS
        // 18 April 2023 11:20 WIB
        $avs = $this->dealer_stock->qty_avs_v2($id_dealer, $part['id_part'], $part['id_gudang'], $part['id_rak'], false, $part['id_part_int']) + $part['qty'];
        if ($avs < $part['qty']) {
          log_message('ERROR', 'Cek AVS di NSC. raw data : ' . json_encode($part));
          $rsp = [
            'status'    => 'error',
            'pesan'     => "Stok AVS untuk ID Part : {$part['id_part']} tidak mencukupi. Stok AVS : $avs"
          ];
          send_json($rsp);
        }

        $filter = ['nomor_so' => $part['nomor_so']];
        $get_so = $this->m_api->getSo($filter);
        if ($get_so->num_rows() > 0) {
          $get_so = $get_so->row();
          $this->h3_so->update_status_po_untuk_sales_order($get_so->nomor_so);
          if ($get_so->pembelian_dari_dealer_lain == 1) {
            $insert['id_dealer_pembeli'] = $get_so->id_dealer_pembeli;
          }
        } else {
          $filter = ['booking_by_wo' => $post['id_work_order']];
          $get_so = $this->m_api->getSo($filter);
          $rsp = [
            'status' => 'error',
            'pesan' => 'No. SO ' . $part['nomor_so'] . ' tidak ditemukan !'
          ];
          send_json($rsp);
        }
        $upd_so_multi[] = ['status' => 'Closed', 'nomor_so' => $get_so->nomor_so];
        $upd_ps[] = ['status' => 'Closed', 'nomor_ps' => $get_so->nomor_ps];
      }
    }


    // Potong Stok
    foreach ($post_parts as $part) {
      $id_part = $part['id_part'];
      $part_int = 0;

      $prt = $this->db->get_where('ms_part', ['id_part' => $id_part]);
      if($prt->num_rows()> 0){
        $part_int = $prt->row()->id_part_int;
      }

      $transaksi_stock = [
        'id_part' => $part['id_part'],
        'id_gudang' => $part['id_gudang'],
        'id_rak' => $part['id_rak'],
        'tipe_transaksi' => '-',
        'sumber_transaksi' => 'nsc',
        'referensi' => $no_nsc,
        'stok_value' => $part['qty'],
      ];
      $this->transaksi_stok->insert($transaksi_stock);
      if($part_int!=0){
        $this->db->set('id_part_int', $part_int);
      }
      $this->db->set('stock', "stock - {$part['qty']}", FALSE);
      $this->db->where('ds.id_part', $part['id_part']);
      $this->db->where('ds.id_gudang', $part['id_gudang']);
      $this->db->where('ds.id_rak', $part['id_rak']);
      $this->db->where('ds.stock>0');
      $this->db->where('ds.id_dealer', $this->m_admin->cari_dealer());
      $this->db->update('ms_h3_dealer_stock as ds');

      //Cek apakah EV atau Tidak 
      $kelompok_part = $this->db->select('kelompok_part')
        ->from('ms_part')
        ->where('id_part_int', $part['id_part_int'])
        ->get()->row_array();

      if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
          $customer = $this->db->select('no_mesin')
              ->select('no_rangka')
              ->select('nama_customer')
              ->select('serial_number')
              ->select('no_hp')
              ->from('tr_h3_serial_ev_tracking')
              ->where('id_part_int', $part['id_part_int'])
              ->where('no_so_wo_booking', $part['nomor_so'])
              ->get()->result_array();
  
          foreach($customer as $cust){
            $this->db->set('no_nsc', $no_nsc)
            ->set('created_at_nsc', $waktu)
            ->set('created_by_nsc', $login_id)
            ->set('accStatus', 9)
            ->where('id_part_int', $part_int)	
            ->where('serial_number', $cust['serial_number'])
            ->update('tr_h3_serial_ev_tracking');
  
            //Data Untuk insert status ev
            $accType ='';
              if($kelompok_part['kelompok_part']=='EVBT'){
                $accType ='B';
              }elseif($kelompok_part['kelompok_part']=='EVCH'){
                $accType ='C';
              }
  
            $status_ev = $this->db->select('mdReceiveDate')
                ->select('mdSLDate')
                ->select('mdSLNo')
                ->select('dealerCode')
                ->select('dealerReceiveDate')
                ->select('accStatus_2_processed_at')
                ->select('accStatus_2_processed_by_user')
                ->select('accStatus_3_processed_at')
                ->select('accStatus_3_processed_by_user')
                ->select('accStatus_4_processed_at')
                ->select('accStatus_4_processed_by_user')
                ->from('tr_status_ev_acc')
                ->where('serialNo', $cust['serial_number'])
                ->where('accStatus', 4)
                ->limit(1)
                ->get()->row_array();
  
            //Insert tr_status_ev_acc
            $data_ev = array(
                'serialNo' =>  $cust['serial_number'],
                'accType' => $accType,
                'accStatus' => 9,
                'mdReceiveDate' =>  $status_ev['mdReceiveDate'],
                'mdSLDate' => $status_ev['mdSLDate'],
                'mdSLNo' =>  $status_ev['mdSLNo'],
                'dealerCode' => $status_ev['dealerCode'],
                'dealerReceiveDate' => $status_ev['dealerReceiveDate'],
                'frameNo' => $cust['no_rangka'],
                'engineNo' => $cust['no_mesin'],
                'phoneNo' => $cust['no_hp'],
                'custName' => $cust['nama_customer'],
                'invDirectSalesDate' => $waktu,
                'invDirectSalesNo' => $no_nsc,
                'accStatus_2_processed_at' =>  $status_ev['accStatus_2_processed_at'],
                'accStatus_2_processed_by_user' =>  $status_ev['accStatus_2_processed_by_user'],
                'accStatus_3_processed_at' =>  $status_ev['accStatus_3_processed_at'],
                'accStatus_3_processed_by_user' => $status_ev['accStatus_3_processed_by_user'],
                'accStatus_4_processed_at' =>  $status_ev['accStatus_4_processed_at'],
                'accStatus_4_processed_by_user' => $status_ev['accStatus_4_processed_by_user'],
                'accStatus_9_processed_at' =>  $waktu,
                'accStatus_9_processed_by_user' => $login_id,
                'api_from' =>2,
                'last_updated' => date('Y-m-d H:i:s', time())
            );
            
            $this->db->insert('tr_status_ev_acc', $data_ev);

            //Insert data di table ev_log_send_api_3
						$data_ev_to_ahm = array(
							'serialNo' =>  $cust['serial_number'],
							'accStatus' => 9,
							'created_at' =>  $waktu,
							'status_scan' => 1, 
						);
						
						$this->db->insert('ev_log_send_api_3', $data_ev_to_ahm);
          }
        }
    }

    $tes = [
      'insert' => $insert,
      'parts' => isset($parts) ? $parts : null,
      'upd_so' => isset($upd_so) ? $upd_so : null,
      'upd_so_multi' => isset($upd_so_multi) ? $upd_so_multi : null,
      'upd_po' => isset($upd_po) ? $upd_po : null,
      'po_id' => isset($po_id) ? $po_id : null,
      'upd_ps' => isset($upd_ps) ? $upd_ps : null,
    ];
    // send_json($tes);
    if (isset($parts)) {
      $this->db->insert_batch('tr_h23_nsc_parts', $parts);
    }
    $this->notifikasi->insert([
      'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_nsc')->get()->row()->id_notif_kat,
      'judul'        => 'Nota Suku Cadang (NSC)',
      'pesan'        => "Terdapat Nota Suku Cadang (NSC) dengan No. NSC : " . $no_nsc,
      'link'         => "dealer/nsc/detail_nsc?id=$no_nsc",
      'id_referensi' => $no_nsc,
      'id_dealer'    => $this->m_admin->cari_dealer(),
      'show_popup'   => false,
    ]);
    if (isset($upd_so)) {
      $this->db->update('tr_h3_dealer_sales_order', $upd_so, ['nomor_so' => $nomor_so]);
    }
    if (isset($upd_ps)) {
      $this->db->update_batch('tr_h3_dealer_picking_slip', $upd_ps, 'nomor_ps');
    }
    // if (isset($upd_po)) {
    //   $this->db->update('tr_h3_dealer_purchase_order', $upd_po, ['po_id' => $po_id]);
    // }
    if (isset($upd_so_multi)) {
      $this->db->update_batch('tr_h3_dealer_sales_order', $upd_so_multi, 'nomor_so');
    }

    if (isset($upd_uang_jaminan)) {
      $this->db->update_batch('tr_h2_uang_jaminan', $upd_uang_jaminan, 'no_inv_uang_jaminan');
    }

    $this->db->insert('tr_h23_nsc', $insert);

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
        'no_nsc' => $no_nsc,
        'link' => base_url('dealer/nsc')
      ];

      $filter_part = [
        'no_nsc' => $no_nsc,
        'group_by_no_nsc' => true,
        'group_by_no_nsc_only_grand' => true,
        'kelompok_part_not_in' => "'Oil'"
      ];
      $get_part = $this->m_bil->getNSCParts($filter_part);
      $nilai_part = 0;
      if ($get_part->num_rows() > 0) {
        $nilai_part = $get_part->row()->gt;
      }

      $filter_oli = [
        'no_nsc' => $no_nsc,
        'group_by_no_nsc' => true,
        'group_by_no_nsc_only_grand' => true,
        'kelompok_part_in' => "'Oil'"
      ];
      $get_oli = $this->m_bil->getNSCParts($filter_oli);
      $nilai_oli = 0;
      if ($get_oli->num_rows() > 0) {
        $nilai_oli = $get_oli->row()->gt;
      }
      $upd = ['tot_nsc_part' => $nilai_part, 'tot_nsc_oli' => $nilai_oli];
      $this->db->update('tr_h23_nsc', $upd, ['no_nsc' => $no_nsc]);
      $_SESSION['pesan']   = "Pembuatan nota suku cadang (NSC) berhasil";
      $_SESSION['tipe']    = "success";
      // echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/mutasi_stok/add'>";
    }
    echo json_encode($rsp);
  }

  public function get_no_nsc()
  {
    $th        = gmdate("y", time() + 60 * 60 * 7);
    $bln       = gmdate("m", time() + 60 * 60 * 7);
    $th_bln       = gmdate("Y-m", time() + 60 * 60 * 7);
    $tgl       = tanggal();
    $id_dealer = $this->m_admin->cari_dealer();
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT no_nsc FROM tr_h23_nsc
			WHERE id_dealer='$id_dealer'
			AND LEFT(created_at,7)='$th_bln'
			ORDER BY created_at,no_nsc DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $last_number = substr($row->no_nsc, -4);
      $new_kode   = 'NSC/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $last_number + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h23_nsc', ['no_nsc' => $new_kode])->num_rows();
        if ($cek > 0) {
          $gen_number    = substr($new_kode, -4);
          $new_kode = 'NSC/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/' . sprintf("%'.04d", $gen_number + 1);
          $i = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'NSC/' . $dealer->kode_dealer_md . '/' . $th . '/' . $bln . '/0001';
    }
    if (isset($_POST['ajax'])) {
      $response = ['status' => 'sukses', 'no_nsc' => strtoupper($new_kode)];
      send_json($response);
    } else {
      return strtoupper($new_kode);
    }
  }
}
