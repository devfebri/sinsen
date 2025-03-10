<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Service_consultation extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2_booking', 'm_booking');
    $this->load->model('m_h2_work_order', 'm_wo');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_sm_master', 'm_sm');
    $this->load->model('m_h2_api');
    $this->load->model('m_h2_master');
    $this->load->model('m_sc_master', 'm_sc_master');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }


  function download_catatan_mekanik()
  {
      $this->load->library('mpdf_l');
      $mpdf                           = $this->mpdf_l->load();
      $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
      $mpdf->charset_in               = 'UTF-8';
      $mpdf->autoLangToFont           = true;
      $id_dealer = $this->login->id_dealer;
      $data['set'] = 'print';
      $data['id_dealer'] = $id_dealer;
      $html = $this->load->view('dealer/cetak_catatan_mekanik', $data, true);
      $mpdf->WriteHTML($html);

      $get_data = $this->db->query("select id_dealer, kode_dealer_md, kode_dealer_ahm, nama_dealer,logo from ms_dealer where id_dealer ='$id_dealer'");
      $row = $get_data->row();

    	$params['save_server'] = true;
	if ($get_data->num_rows() > 0 && $id_dealer!='') {
	  if ($params['save_server']) {
		$path = 'uploads/catatan_mekanik/' . $row->kode_dealer_md;
		if (!is_dir($path)) {
		  mkdir($path, 0777, true);
		}
		$doc = $path . '/catatan_mekanik'.'_'.$row->kode_dealer_md.'.pdf';
	 
		if (file_exists(FCPATH . $doc)) {
		  unlink($doc); //Delete File
		}
		
		$mpdf->Output("$doc", 'F');

		$msg = ["File berhasil didownload!"];
		$data = [
			'document' => base_url($doc)
		];
		send_json(msg_sc_success($data, $msg));
	  } else {
		// $output = 'catatan_mekanik'.'_'.$row->kode_dealer_md.'.pdf';
		$doc = $path . '/catatan_mekanik'.'_'.$row->kode_dealer_md.'.pdf';
		$mpdf->Output("$doc", 'I');

		$msg = ["File berhasil didownload!"];
		$data = [
			'document' => base_url($doc)
		];
		send_json(msg_sc_success($data, $msg));
	  }
	}else{
		$msg = ["Gagal tarik file, Silahkan coba login kembali."];
		$data = [
		 'document' => ""
		];
		send_json(msg_sc_success($data, $msg));
	}
  }

  function download_catatan_mekanik1()
  {
    $id_dealer = $this->login->id_dealer;
    $msg = ["Data berhasil di generate $id_dealer !"];
    //panggil link hasil generate pdf nya di sini
    $data = [
      'document' => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf"
    ];
    send_json(msg_sc_success($data, $msg));
  }

  function _cek_input_antrian($get)
  {
    if (is_numeric($get)) {
      $get = (int)$get;
      //Cek Digit
      if ($get > 999) {
        $msg = ['Maksimal no. Antrian hanya 3 digit'];
        send_json(msg_sc_error($msg));
      }

      //Cek Antrian Terakhir;
      $f = [
        'jenis_customer' => 'reguler',
        'id_dealer' => $this->login->id_dealer,
        'tgl_servis' => tanggal(),
        // 'id_wo_null' => true,
        'order' => 'waktu_kedatangan_created_at_desc',
        'LIMIT' => 'LIMIT 1'
        // 'left_join_wo' => true,
        // 'id_sa_form_null' => true,
      ];
      $cek_atr = $this->m_booking->getQueue($f);
      if ($cek_atr->num_rows() > 0) {
        $atr = $cek_atr->row();
        $last = (int)substr($atr->id_antrian_short, 1, 3);
        $next = $last + 1;
        if ($get > $next) {
          $msg = ['No. Antrian terakhir #' . $atr->id_antrian_short];
          send_json(msg_sc_error($msg));
        } elseif ($get < $last) {
          $msg = ['No. Antrian terakhir #' . $atr->id_antrian_short];
          send_json(msg_sc_error($msg));
        }
      } else {
        if ($get > 1) {
          $msg = ['No. Antrian tidak boleh lebih dari 1. Silahkan isi dengan angka 1'];
          send_json(msg_sc_error($msg));
        }
      }
    } else {
      $msg = ['No. Antrian yang diizinkan hanya berupa angka'];
      send_json(msg_sc_error($msg));
    }
  }
  function create()
  {

    ini_set("allow_url_fopen", true);
    $post = json_decode(file_get_contents('php://input'), true);
    $id_sa_form      = $this->m_wo->get_id_sa_form($this->login->id_dealer);
    $id_dealer = $this->login->id_dealer;

    if (strlen($post['police_no'])<8) {
      send_json(msg_sc_error(['Minimal karakter untuk No. Polisi adalah 8 karakter !']));
    }

    // if (strlen($post['frame_no'])<14) {
    //   send_json(msg_sc_error(['Minimal karakter untuk No. Rangka adalah 14 karakter !']));
    // }

    // if (substr(strtoupper($post['frame_no']),0,3) == 'MH1'){
    //   send_json(msg_sc_error(["Silahkan Hapus 'MH1' di No. Rangka"]));
    // }

    // if (strlen($post['frame_no'])<14 || $post['frame_no'])>17) {
    if (strlen($post['frame_no'])!=17) {
      send_json(msg_sc_error(['Jumlah karakter untuk No. Rangka adalah 17 karakter !']));
    }

    if (strlen($post['engine_no'])!=12) {
      send_json(msg_sc_error(['Jumlah karakter untuk No. Mesin adalah 12 karakter !']));
    }

    $f = [
      'no_mesin_no_rangka_no_polisi' => [$post['engine_no'],$post['frame_no'],$post['police_no']],
    ];
    // $cek_h1   = $this->m_h2_api->getCustomerH1($f);
    // $cek_h23  = $this->m_h2_api->getCustomerH23($f);
    // $is_insert =0;
    // if ($cek_h1->num_rows()==0) {
    //   if ($cek_h23->num_rows()==0) {
    //     $is_insert = 1;
    //   }else{
    //     $is_insert=0;
    //   }
    // }else{
    //   if ($cek_h23->num_rows()==0) {
    //     $is_insert=1;
    //   }else{
    //     $is_insert=0;
    //   }
    // }
    
    $is_insert =0;
    $cek_h23  = $this->m_h2_api->getCustomerH23($f);    
    if ($cek_h23->num_rows()==0) {
      $is_insert=1;
    }

    if ((string)$post['owner_current_address']=='') {
      $msg = ['Alamat wajib diisi'];
      send_json(msg_sc_error($msg));
    }
    
    if ((string)$post['owner_address']=='') {
      $msg = ['Alamat identitas wajib diisi'];
      send_json(msg_sc_error($msg));
    }

    if ($is_insert==0) {
      $customer_from = 'h23';
      $cust = $cek_h23->row();
      $id_customer = $cust->id_customer;
      $id_customer_int = $cust->id_customer_int;
      $exp_kelurahan = explode('-', $post['owner_sub_district']);
      $fkel = ['id_kelurahan' => str_replace(" ", "", ($exp_kelurahan[count($exp_kelurahan) - 1]))];
      $kel = $this->m_sc_master->getKelurahan($fkel)->row();
      $upd_cust = [
        'nama_customer'          => strtoupper($post['owner_name']),
        'nama_stnk'              => strtoupper($post['owner_name']),
        'no_hp'                  => $post['owner_phone'],
        'email'                  => $post['owner_email'],
        'alamat'                 => strtoupper($post['owner_current_address']),
        'no_identitas'           => $post['identity_no'],
        'alamat_identitas'       => strtoupper($post['owner_address']),
        'id_kelurahan'           => $kel != NULL ? $kel->id : NULL,
        'id_kecamatan'           => $kel != NULL ? $kel->id_kecamatan : NULL, //
        'id_kabupaten'           => $kel != NULL ? $kel->id_kabupaten : NULL, //
        'id_provinsi'            => $kel != NULL ? $kel->id_provinsi : NULL, //
        'no_mesin'               => strtoupper($post['engine_no']),
        'no_rangka'              => strtoupper($post['frame_no']),
        'no_polisi'              => strtoupper($post['police_no']),
        'id_tipe_kendaraan'      => $post['code_unit'],
        'tahun_produksi'         => $post['vehicle_year'],
        'id_kelurahan_identitas' => NULL,
        'facebook'               => $post['owner_facebook'],
        'instagram'              => $post['owner_instagram'],
        'twitter'                => $post['owner_twitter'],
        'input_from'             => 'sc',
        'updated_at'             => waktu_full(),
        'updated_by'             => $this->login->id_user,
      ];
    } else {
      $exp_kelurahan = explode('-', $post['owner_sub_district']);
      $fkel = ['id_kelurahan' => str_replace(" ", "", ($exp_kelurahan[count($exp_kelurahan) - 1]))];
      $kel = $this->m_sc_master->getKelurahan($fkel)->row();

      $fid = ['id_dealer' => $this->login->id_dealer];
      $id_customer = $this->m_h2_master->get_id_customer($fid);
      $id_customer_int = $this->db->query("SHOW TABLE STATUS LIKE 'ms_customer_h23'")->row()->Auto_increment;
      $h1 = $this->db->get_where('tr_sales_order',['no_mesin'=>$post['engine_no']])->row();
      $ins_cust = [
        'id_customer_int'           => $id_customer_int,
        'id_customer'               => $id_customer,
        'nama_customer'             => strtoupper($post['owner_name']),
        'nama_stnk'                 => strtoupper($post['owner_name']),
        'no_hp'                     => $post['owner_phone'],
        'email'                     => $post['owner_email'],
        'alamat'                    => strtoupper($post['owner_current_address']),
        'jenis_identitas'           => '',
        'no_identitas'              => $post['identity_no'],
        'alamat_identitas'          => strtoupper($post['owner_address']),
        'id_kelurahan'              => $kel != NULL ? $kel->id : NULL, //
        'id_kecamatan'              => $kel != NULL ? $kel->id_kecamatan : NULL, //
        'id_kabupaten'              => $kel != NULL ? $kel->id_kabupaten : NULL, //
        'id_provinsi'               => $kel != NULL ? $kel->id_provinsi : NULL, //
        'jenis_kelamin'             => '',
        'no_mesin'                  => strtoupper($post['engine_no']),
        'no_rangka'                 => strtoupper($post['frame_no']),
        'no_polisi'                 => strtoupper($post['police_no']),
        'id_tipe_kendaraan'         => $post['code_unit'],
        'id_warna'                  => '',
        'tahun_produksi'            => $post['vehicle_year'],
        'tgl_pembelian'             => $h1!=null?$h1->tgl_cetak_invoice:'',
        'id_kelurahan_identitas'    => NULL,
        'jenis_customer_beli'       => '',
        'id_agama'                  => '',
        'tgl_lahir'                 => '',
        'id_pekerjaan'              => '',
        'longitude'                 => '',
        'latitude'                  => '',
        'facebook'                  => $post['owner_facebook'],
        'instagram'                 => $post['owner_instagram'],
        'twitter'                   => $post['owner_twitter'],
        'input_from'                => 'sc',
        'id_dealer'                 => $id_dealer,
        'created_at'                => waktu_full(),
        'created_by'                => $this->login->id_user,
      ];
      $id_dealer_cus = $ins_cust['id_dealer'];
      if ($id_dealer_cus=='' || $id_dealer_cus==null || $id_dealer_cus==0) {
        $msg = ['ID Dealer kosong'];
        send_json(msg_sc_error($msg));
      }
    }

    if ($post['ahass_service_reason'] == 'Stiker') {
      $post['ahass_service_reason'] = 'Stiker Reminder';
    } elseif ($post['ahass_service_reason'] == 'Inisiatif Diri') {
      $post['ahass_service_reason'] = 'Inisiatif sendiri';
    }

    $f_atr = [
      'id_antrian_short' => sprintf("%'.03d", $post['queue_no']),
      'id_dealer' => $this->login->id_dealer,
      // 'left_join_wo' => true,
      'id_sa_form_null' => true,
      'tgl_antrian' => get_ymd()
    ];
    $atr = $this->m_booking->getQueue($f_atr);
    // send_json($atr->num_rows());
    $f_act = ['name' => $post['activity_promotion']];
    $act_promotion = $this->m_sm->getActivityPromotion($f_act)->row();

    $f_act = ['keterangan' => $post['activity_capacity']];
    $act_cap = $this->m_sm->getActivityCapacity($f_act)->row();
    // if ($atr->num_rows() > 0) {
    //   $atr = $atr->row_array();
    //   $id_antrian = $atr['id_antrian'];
    //   $update = [
    //     'id_sa_form'                    => $id_sa_form,
    //     'id_dealer'                     => $this->login->id_dealer,
    //     'id_customer'                   => $id_customer,
    //     'tgl_servis'                    => $post['service_date'],
    //     'jam_servis'                    => $atr['jam_servis'],
    //     'id_booking'                    => $atr['id_booking'],
    //     'informasi_bensin'              => $post['bbm_level'],
    //     'km_terakhir'                   => $post['kilometer'],
    //     'keluhan_konsumen'              => $post['complaint'],
    //     'jenis_customer'                => 'reguler',
    //     'rekomendasi_sa'                => $post['service_advisor_suggestion'],
    //     'konfirmasi_pekerjaan_tambahan' => $post['is_confirmation_work'] == 0 ? 'langsung' : 'via_no_hp',
    //     'updated_sa_form_at'            => waktu_full(),
    //     'updated_sa_form_by'            => $this->login->id_user,
    //     'status_form'                   => 'closed',
    //     'status_monitor'                => 'menunggu_masuk_pit',
    //     // 'id_karyawan_dealer'            => 0,
    //     'tipe_coming'                   => $post['carrier_same_with_owner'] == 0 ? 'bawa' : 'milik', 'activity_promotion_id'         => $act_promotion->id != NULL ? $act_promotion->id : NULL, 'activity_capacity_id'          => $act_cap->id != NULL ? $act_cap->id : NULL,
    //     'asal_unit_entry'               => $post['activity_promotion'],
    //     'alasan_ke_ahass'               => $post['ahass_service_reason'],
    //   ];
    // } else {
    $this->_cek_input_antrian($post['queue_no']);
    $id_antrian = $this->m_h2->get_id_antrian('reguler', $this->login->id_dealer);
    $cek_srbu        = $this->m_h2->cekSRBU($post['engine_no']);

    $tipe_coming = 'milik';
    if(strtoupper($post['owner_name']) !== strtoupper($post['carrier_name']) && $post['carrier_name']!='' && strlen($post['carrier_name'])> 0){
      $post['carrier_same_with_owner'] = 0;
      $tipe_coming = 'bawa';
    }

    $ins_sa = [
      'id_antrian'                    => $id_antrian,
      'id_sa_form'                    => $id_sa_form,
      'id_dealer'                     => $this->login->id_dealer,
      'id_customer'                   => $id_customer,
      'tgl_servis'                    => $post['service_date'],
      'jam_servis'                    => jam_menit(),
      'id_booking'                    => NULL,
      'informasi_bensin'              => $post['bbm_level'],
      'km_terakhir'                   => $post['kilometer'],
      'keluhan_konsumen'              => $post['complaint'],
      'jenis_customer'                => 'reguler',
      'rekomendasi_sa'                => $post['service_advisor_suggestion'],
      'konfirmasi_pekerjaan_tambahan' => $post['is_confirmation_work'] == 0 ? 'langsung' : 'via_no_hp',
      'created_at'                    => waktu_full(),
      'created_by'                    => $this->login->id_user,
      'created_sa_form_at'            => waktu_full(),
      'created_sa_form_by'            => $this->login->id_user,
      'closed_at'                     => waktu_full(),
      'closed_by'                     => $this->login->id_user,
      'status_form'                   => 'closed',
      'status_monitor'                => 'menunggu_masuk_pit',
      'id_karyawan_dealer'            => 0,
      'tipe_coming'                   => $tipe_coming, // $post['carrier_same_with_owner'] == 1 ? 'milik' : 'bawa'
      'activity_promotion_id'         => $act_promotion->id != NULL ? $act_promotion->id : NULL, 'activity_capacity_id'          => $act_cap->id != NULL ? $act_cap->id : NULL,
      'asal_unit_entry'               => $post['activity_promotion'],
      'id_type'                       => $post['type'], // menambahkan type pekerjaan
      'input_from'                    => 'sc',
      'waktu_kedatangan'              => jam_menit(),
      'id_customer_int'               => $id_customer_int,
      'alasan_ke_ahass'               => $post['ahass_service_reason'],
      'tipe_pembayaran'               => 'cash',
      'srbu' => $cek_srbu,
      'id_wo_job_return' => $post['job_return_no']
    ];
    // }

    //Data Pembawa
    $id_pembawa = $this->m_h2->get_id_pembawa($this->login->id_dealer);
    if (isset($ins_sa)) {
      $ins_sa['id_pembawa'] = $id_pembawa;
    } else {
      $update['id_pembawa'] = $id_pembawa;
    }
    $exp_kelurahan = explode('-', $post['carrier_sub_district']);
    $fkel = ['id_kelurahan' => str_replace(" ", "", ($exp_kelurahan[count($exp_kelurahan) - 1]))];
    $kel = $this->m_sc_master->getKelurahan($fkel)->row();

    // if ($post['carrier_same_with_owner']==0) {
    $ins_pembawa = [
      'id_pembawa' => $id_pembawa,
      'id_customer' => $id_customer,
      'nama' => strtoupper($post['carrier_name']),
      'no_hp' => $post['carrier_phone'],
      'email' => '',
      'alamat_saat_ini' => $post['carrier_current_address'],
      'jenis_kelamin' => '',
      'id_kelurahan'    => $kel != NULL ? $kel->id : NULL,
      'jenis_identitas' => '',
      'no_identitas' => '',
      'alamat_identitas' => $post['carrier_address'],
      'id_kelurahan_identitas' => $kel != NULL ? $kel->id : NULL,
      'hubungan_dengan_pemilik' => $post['carrier_relationship'],
      'facebook' => $post['carrier_facebook'],
      'twitter' => $post['carrier_twitter'],
      'instagram' => $post['carrier_instagram'],
      'aktif' => 1,
      'created_at' => waktu_full(),
      'created_by' => $this->login->id_user,
      'input_from' => 'sc',
    ];
    // }

    $total_jasa         = 0;
    $set_id_jasa        = '';
    $tipe_pembayaran    = 'cash';

    foreach ($post['service_type'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_jasa_or_id_jasa_int' => $st,
      ];
      $get_data = $this->m_h2_master->fetch_jasa_h2_dealer($f, $post['code_unit']);
      if ($get_data->num_rows() > 0) {
        $res_type = $get_data->row();
        if ($res_type->kpb_ke > 0) {
          //Cek KPB
          $cek_h1_tgl_pembelian = $this->db->query("SELECT tgl_cetak_invoice tgl_pembelian FROM(
            SELECT tgl_cetak_invoice FROM tr_sales_order WHERE no_mesin='{$post['engine_no']}'
            UNION
            select tgl_cetak_invoice from tr_sales_order_gc gc JOIN tr_sales_order_gc_nosin gcn ON gc.id_sales_order_gc=gcn.id_sales_order_gc WHERE no_mesin='{$post['engine_no']}'
            ) as tabel LIMIT 1")->row();
          if (count($cek_h1_tgl_pembelian) > 0) {
            $tgl_pembelian = $cek_h1_tgl_pembelian->tgl_pembelian;
          } else {
            log_message('debug', sprintf('Raw SA Form/WO', json_encode($post)));
            $msg = ['Tgl. Pembelian Tidak Ditemukan'];
            send_json(msg_sc_error($msg));
          }
          $fkpb = [
            'no_mesin' => $post['engine_no'],
            'id_tipe_kendaraan' => $post['code_unit'],
            'kpb_ke' => $res_type->id_type,
            'tgl_pembelian' => $tgl_pembelian
          ];
          $kpb = $this->m_h2_master->cekKPB($fkpb);
          if ($kpb['status'] != 'oke') {
            $msg = [$kpb['msg']];
            send_json(msg_sc_error($msg));
          }
          $tipe_pembayaran='top';
        }
      } else {
        $msg = ['Service Type Tidak Ditemukan'];
        send_json(msg_sc_error($msg));
      }

      // if ($key == 0) {
      //   if ($post['service_promo_code'] != '') {
      //     $id_promo = $post['service_promo_code'];
      //     $diskon_value = $post['service_promo_price'];
      //   }
      // }

      $id_promo = NULL;
      $diskon_value = 0;

      // $f = [
      //   'id_dealer' => $this->login->id_dealer,
      //   'cek_periode' => tanggal(),
      //   'group_by_id_promo' => true,
      //   'id_jasa' => $res_type->id_jasa
      // ];
      // $srv = $this->m_h2_master->get_promo_servis_jasa($f)->row();
      // if ($srv != NULL) {
      //   $id_promo = $srv->id_promo;
      //   if ($srv->tipe_diskon == 'Percentage') {
      //     $diskon_value = $res_type->harga_dealer * ($srv->diskon / 100);
      //   } else {
      //     $diskon_value = $srv->diskon;
      //   }
      // }

      $ins_jasa_sa[] = [
        'id_sa_form' => $id_sa_form,
        'id_jasa' => $res_type->id_jasa,
        'harga' => $res_type->harga_dealer,
        'waktu' => $res_type->waktu,
        'id_promo' => isset($id_promo) ? $id_promo : NULL,
        'diskon_value' => isset($diskon_value) ? $diskon_value : 0,

      ];
      if ($key == 0) {
        $set_id_jasa = $res_type->id_jasa;
      }

      $total_jasa += ($res_type->harga_dealer - $diskon_value);
    }
    $total_jasa = isset($diskon_value) ? $total_jasa - $diskon_value : $total_jasa;

    $total_part = 0;
    //Part Ready Stok
    foreach ($post['part'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_part_int' => $st['part_id'],
        'cek_referensi' => true
      ];
      $prt = $this->m_sm->getParts($f);

      // if ($key == 0) {
      //   if ($post['part_promo_code'] != '') {
      //     $id_promo_part = $post['part_promo_code'];
      //     $diskon_value_part = $post['part_promo_price'];
      //   }
      // }

      $id_promo_part = NULL;
      $diskon_value_part = 0;
      $tipe_diskon_part = '';
      //Get Diskon Part
      $cek_diskon_part = $this->m_sm->promo_part_query($prt->id_part, $prt->kelompok_part);
      if (count($cek_diskon_part) > 0) {
        // $id_promo_part = $cek_diskon_part[0]['id_promo'];
        $get_part_d = $cek_diskon_part[0]['promo_items'];
        foreach ($get_part_d as $key => $pd) {
          if ($pd['id_part'] == $prt->id_part) {
            // $tipe_diskon_part = $pd['tipe_disc'];
            // $diskon_value_part = $pd['disc_value'];
            break;
          }
        }
      }
      
      $rak_gudang = $this->_getRakGudangByPartInDealer($prt->id_part, $id_dealer);
      $id_gudang = '';
      $id_rak = '';

      if ($rak_gudang!=null) {
        if ($rak_gudang->id_gudang!='---') {
          $id_gudang =$rak_gudang->id_gudang;
        }else{
          $msg = ['ID Gudang untuk part : '.$prt->id_part.' kosong'];
          send_json(msg_sc_error($msg));
        }
        if ($rak_gudang->id_rak!='---') {
          $id_rak =$rak_gudang->id_rak;
        }else{
          $msg = ['ID Rak untuk part : '.$prt->id_part.' kosong'];
          send_json(msg_sc_error($msg));
        }
      }

      $ins_part_sa[] = [
        'id_sa_form' => $id_sa_form,
        'id_jasa' => $set_id_jasa,
        'id_part' => $prt->id_part,
        'harga' => $prt->harga_dealer_user,
        'qty' => $st['quantity'],
        'id_gudang' => $id_gudang,
        'id_rak' => $id_rak,
        'jenis_order' => 'Reguler',
        'id_promo' => $id_promo_part,
        'diskon_value' => $diskon_value_part,
        'tipe_diskon' => $tipe_diskon_part,
        'part_utama' => 0
      ];

      $total_part += $res_type->harga_dealer;
    }

    //Hotline
    foreach ($post['hotline_part'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_part_int' => $st['part_id'],
        'cek_referensi' => true
      ];
      $prt = $this->m_sm->getParts($f);

      $ins_part_sa[] = [
        'id_sa_form' => $id_sa_form,
        'id_jasa' => '',
        'id_part' => $prt->id_part,
        'harga' => $prt->harga_dealer_user,
        'qty' => $st['quantity'],
        'id_gudang' => '',
        'id_rak' => '',
        'jenis_order' => 'HLO',
        'id_promo' => NULL,
        'diskon_value' => 0,
        'tipe_diskon' => null,
        'part_utama' => 0
      ];

      $total_part += $res_type->harga_dealer;
    }
    $total_part = isset($diskon_value) ? $total_part - $diskon_value : $total_part;

    //Part Demand
    foreach ($post['part_demand'] as $key => $st) {
      $f = [
        'id_dealer' => $this->login->id_dealer,
        'id_part_int' => $st['part_id'],
        'cek_referensi' => true
      ];
      $prt = $this->m_sm->getParts($f);

      $ins_part_demand[] = [
        'id_sa_form' => $id_sa_form,
        'id_part' => $prt->id_part,
        'id_dealer' => $this->login->id_dealer,
        'search_result' => $prt->nama_part . ', ' . $prt->id_part,
        'qty'           => $st['quantity'],
        'harga_satuan'  => $prt->harga_dealer_user,
        'search_field' => '',
        'sisa_stock' => 0,
        'note_field' => 'Tidak Ada Stok (Service Concept)'
      ];
    }

    $id_work_order      = $this->m_wo->get_id_work_order($this->login->id_dealer);

    $ins_wo =
      [
        'id_work_order' => $id_work_order,
        'id_dealer'     => $id_dealer,
        'id_sa_form'    => $id_sa_form,
        'status'        => 'open',
        'created_at'    => waktu_full(),
        'created_by'    => $this->login->id_user,
        'total_jasa'    => $total_jasa,
        'total_part'    => $total_part,
        'grand_total'   => $total_jasa + $total_part,
        'id_karyawan_dealer' => NULL,
        'tipe_pembayaran' => $tipe_pembayaran,
        'input_from' => 'sc',
      ];

    $i_js = 1;
    foreach ($ins_jasa_sa as $key => $js) {
      $need_parts='n';
      if ($i_js==1 && isset($ins_parts_sa)) {
        $need_parts='y';
      }
      $subtotal = $js['harga'] - $js['diskon_value'];
      $ins_jasa_wo[] = [
        'id_work_order' => $id_work_order,
        'id_jasa' => $js['id_jasa'],
        'harga' => $js['harga'],
        'waktu' => $js['waktu'],
        'id_promo' => $js['id_promo'],
        'disc_amount' => $js['diskon_value'],
        'pekerjaan_luar' => 0,
        'subtotal' => $subtotal,
        'need_parts'=>$need_parts
      ];
      $i_js++;
    }

    if (isset($ins_part_sa)) {
      foreach ($ins_part_sa as $key => $prts) {
        $prt = $this->db->get_where('ms_part', ['id_part' => $prts['id_part']])->row();
        $ins_part_wo[] = [
          'id_part_int' => $prt->id_part_int,
          'id_work_order' => $id_work_order,
          'id_jasa' => $prts['id_jasa'],
          'id_part' => $prts['id_part'],
          'id_part_int' => $prt->id_part_int,
          'qty' => $prts['qty'],
          'harga' => $prts['harga'],
          'id_gudang' => $prts['id_gudang'],
          'id_rak' => $prts['id_rak'],
          'jenis_order' => $prts['jenis_order'],
          'diskon_value' => $prts['diskon_value'],
          'tipe_diskon' => $prts['tipe_diskon'],
          'id_promo' => $prts['id_promo'],
          'order_to' => 0,
          'send_notif' => 0,
          'part_utama' => 0,
          'subtotal' => subtotal_part($prts, $prts['harga'])
        ];
      }
    }
    //Set Tipe Pembayaran Ins. SA
    $ins_sa['tipe_pembayaran']=$tipe_pembayaran;
    $tes = [
      'ins_sa' => $ins_sa,
      'ins_wo' => $ins_wo,
      'ins_jasa_sa' => $ins_jasa_sa,
      'ins_pembawa' => $ins_pembawa,
      'ins_part_sa' => isset($ins_part_sa) ? $ins_part_sa : NULL,
      'ins_part_demand' => isset($ins_part_demand) ? $ins_part_demand : NULL,
      'ins_cust' => isset($ins_cust) ? $ins_cust : NULL,
      'upd_cust' => isset($upd_cust) ? $upd_cust : NULL,
    ];
    // send_json($tes);
    $this->db->trans_begin();
    if (isset($ins_cust)) {
      $this->db->insert('ms_customer_h23', $ins_cust);
    } elseif (isset($upd_cust)) {
      $this->db->update('ms_customer_h23', $upd_cust, ['id_customer' => $id_customer]);
    }
    if (isset($ins_sa)) {
      $this->db->insert('tr_h2_sa_form', $ins_sa);
    }
    if (isset($update)) {
      $this->db->update('tr_h2_sa_form', $update, ['id_antrian' => $id_antrian]);
    }
    $this->db->insert('ms_h2_pembawa', $ins_pembawa);
    $this->db->insert_batch('tr_h2_sa_form_pekerjaan', $ins_jasa_sa);
    // send_json($ins_part_sa);
    if (isset($ins_part_sa)) {
      $this->db->insert_batch('tr_h2_sa_form_parts', $ins_part_sa);
    }

    $this->db->insert('tr_h2_wo_dealer', $ins_wo);
    $this->db->insert_batch('tr_h2_wo_dealer_pekerjaan', $ins_jasa_wo);
    if (isset($ins_part_wo)) {
      $this->db->insert_batch('tr_h2_wo_dealer_parts', $ins_part_wo);
    }

    if (isset($ins_part_demand)) {
      $this->db->insert_batch('tr_h3_dealer_record_reasons_and_parts_demand', $ins_part_demand);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Service Consultation berhasil disimpan!"];
      $params = [
        'id_sa_form'    => $id_sa_form,
        'id_dealer'     => $id_dealer,
        'id_user'       => $this->login->id_user,
        'save_server'   => true
      ];
      $this->m_wo->updateGrandTotalWO($id_work_order);
      $sa = $this->db->query("SELECT id_antrian_int FROM tr_h2_sa_form WHERE id_sa_form='$id_sa_form'")->row();
      $this->db->update('tr_h2_wo_dealer',['id_sa_form_int'=>$sa->id_antrian_int],['id_sa_form'=>$id_sa_form]);
      $cetak = $this->m_wo->cetak_wo($params, true);
      $data = [
        'document' => str_replace('https','http',$cetak)
      ];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function _getRakGudangByPartInDealer($id_part,$id_dealer)
  {
    $this->load->model('h3_dealer_stock_model', 'dealer_stock');
    $qty_avs = $this->dealer_stock->qty_avs('ds.id_dealer', 'ds.id_part', 'ds.id_gudang', 'ds.id_rak', true);

    $query = $this->db
    ->select('ds.*')
    ->select('mp.id_part')
    ->select('mp.nama_part')
    ->select('mp.harga_dealer_user as harga_saat_dibeli')
    ->select("
        case
            when ds.id_gudang is not null then ds.id_gudang
            else '---'
        end as id_gudang
    ")
    ->select("
        case
            when ds.id_rak is not null then ds.id_rak
            else '---'
        end as id_rak
    ")
    ->select("IFNULL(({$qty_avs}), 0) AS stock")
    ->from('ms_part as mp')
    ->join('ms_h3_dealer_stock as ds', "(ds.id_part = mp.id_part and ds.id_dealer = '{$id_dealer}')", 'left')
    ->where('mp.id_part', $id_part)
    ->where("IFNULL(({$qty_avs}), 0) > 0")
    ->order_by("ds.stock", 'desc');
    return $query->get()->row();
  }

  function service_promo()
  {
    $f = [
      'id_dealer' => $this->login->id_dealer,
      'cek_periode' => tanggal(),
      'aktif' => 1,
      'group_by_id_promo' => true
    ];
    $get_data = $this->m_h2_master->get_promo_servis_jasa($f);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_promo_int,
        'name' => $rs->nama_promo,
        'code' => $rs->id_promo,
        'amount' => (int)$rs->diskon,
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function part_promo()
  {
    $f = [
      'id_dealer' => $this->login->id_dealer,
      'cek_periode' => tanggal(),
    ];
    $get_data = $this->m_h2_master->get_promo_part($f)->result();
    $result = [];
    foreach ($get_data as $rs) {
      $result[] = [
        'id' => (int)$rs->id,
        'name' => $rs->nama,
        'code' => $rs->id_promo,
        'amount' => (int)$rs->diskon_value_master,
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function temporary_wo_number()
  {
    $result['no']      = $this->m_wo->get_id_work_order($this->login->id_dealer);
    send_json(msg_sc_success($result, NULL));
  }

  function code_unit()
  {
    $get_data = $this->m_unit->getTipeKendaraan();
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_tipe_kendaraan_int,
        'name' => $rs->id_tipe_kendaraan,
        'description' => "$rs->id_tipe_kendaraan - $rs->tipe_ahm",
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function activity_promotion()
  {
    $f = ['active' => 1];
    $get_data = $this->m_sm->getActivityPromotion($f);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }
  function activity_capacity()
  {
    $f = ['active' => 1];
    $get_data = $this->m_sm->getActivityCapacity($f);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id,
        'name' => $rs->keterangan
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  /* API COMBO TYPE JOB */
  function type_job()
  {
    $get_data = $this->m_sm->getTypeJob();
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_type_int,
        'type' => $rs->id_type
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  function last_service()
  {
    $get = $this->input->get();

    $f    = ['no_polisi' => $get['police_no']];
    $f_h1 = ['no_polisi_sc' => $get['police_no']];
    $cek_h23 = $this->m_h2_api->getCustomerH23($f);

    if ($cek_h23->num_rows()>0) {
      $cust = $cek_h23->row();
      $order[0]=['column'=>0,'dir'=>'desc'];
      $f = [
        'id_customer' => $cust->id_customer,
        'id_dealer' => $this->login->id_dealer,
        'cek_riwayat_servis'=>true,
        'status_wo_in' => "'closed'",
        'limit' => "LIMIT 1",
        'order_by'=>"sa_form.created_at DESC"
      ];
      $get_data = $this->m_wo->get_sa_form($f);
      if ($get_data->num_rows() > 0) {
        $dt = $get_data->row();
        $f_k = ['id_tipe_kendaraan' => $dt->id_tipe_kendaraan];
        $tk = $this->m_unit->getTipeKendaraan($f_k)->row();
        $vehicle = [
          'police_no' => $dt->no_polisi,
          'stnk_name' => $dt->no_polisi,
          'vehicle_year' => $dt->tahun_produksi,
          'vehicle_color_name' => $dt->only_warna,
          'vehicle_type_name' => $tk->id_tipe_kendaraan,
          'vehicle_product_name' => $dt->tipe_ahm,
          'engine_no' => $dt->no_mesin,
          'frame_no' => $dt->no_rangka,
        ];
        $no_mesin = $dt->no_mesin;

        $owner = [
          'id' => $dt->id_customer_int,
          'name' => $dt->nama_customer,
          'email' => $dt->email ?: '',
          'phone' => $dt->no_hp,
          'address' => $dt->alamat_identitas,
          'current_address' => $dt->alamat,
          'district' => $dt->kecamatan,
          'sub_district' => $dt->kelurahan . '-' . $dt->id_kelurahan,
          'facebook' => $dt->facebook_cus ?: '',
          'twitter' => $dt->twitter_cus ?: '',
          'instagram' => $dt->instagram_cus ?: '',
        ];

        $f_p = ['id_pembawa' => $dt->id_pembawa];
        $pbw = $this->m_h2_api->getPembawa($f_p)->row();
        $carrier = [
          'relationship' => $dt->hubungan_dengan_pemilik,
          'name' => $dt->nama_pembawa,
          'email' => $pbw->email ?: '',
          'phone' => $pbw->no_hp,
          'address' => $pbw->alamat_identitas,
          'current_address' => $pbw->alamat_saat_ini,
          'district' => $pbw->kecamatan,
          'sub_district' => $pbw->kelurahan . '-' . $pbw->id_kelurahan,
          'facebook' => $pbw->facebook ?: '',
          'twitter' => $pbw->twitter ?: '',
          'instagram' => $pbw->instagram ?: '',
        ];

        $f_pr = ['id' => $dt->activity_promotion_id];
        $get_act_promo = $this->m_sm->getActivityPromotion($f_pr);
        if ($get_act_promo->num_rows() > 0) {
          $act_promo = $get_act_promo->row_array();
        }

        $f_cap = ['id' => $dt->activity_capacity_id];
        $get_act_cap = $this->m_sm->getActivityCapacity($f_cap);
        if ($get_act_cap->num_rows() > 0) {
          $act_cap = $get_act_cap->row_array();
        }
      } else {
        if ($cek_h23->num_rows() == 0) {
          $msg = ['No. Polisi : ' . $get['police_no'] . ' tidak ditemukan !'];
          send_json(msg_sc_error($msg));
        }
        $ch = $cek_h23->row();
        // send_json($ch);
        $f_k = ['id_tipe_kendaraan' => $ch->id_tipe_kendaraan];
        $tk = $this->m_unit->getTipeKendaraan($f_k)->row();
        $vehicle = [
          'police_no' => $ch->no_polisi,
          'stnk_name' => $ch->no_polisi,
          'vehicle_year' => (int)$ch->tahun_produksi,
          'vehicle_color_name' => $ch->only_warna,
          'vehicle_type_name' => $tk == null ? '' : $tk->id_tipe_kendaraan,
          'vehicle_product_name' => $ch->tipe_ahm,
          'engine_no' => $ch->no_mesin,
          'frame_no' => $ch->no_rangka,
        ];
        $no_mesin = $ch->no_mesin;

        $owner = [
          'id' => $ch->id_customer_int,
          'name' => $ch->nama_customer,
          'email' => $ch->email ?: '',
          'phone' => $ch->no_hp,
          'address' => $ch->alamat_identitas,
          'current_address' => $ch->alamat,
          'district' => $ch->kecamatan,
          'sub_district' => $ch->kelurahan . '-' . $ch->id_kelurahan,
          'facebook' => $ch->facebook ?: '',
          'twitter' => $ch->twitter ?: '',
          'instagram' => $ch->instagram ?: '',
        ];

        $carrier = [
          'relationship' => '',
          'name' => '',
          'email' => '',
          'phone' => '',
          'address' => '',
          'current_address' => '',
          'district' => '',
          'sub_district' => '',
          'facebook' => '',
          'twitter' => '',
          'instagram' => '',
        ];
      }

      $result = [
        'service_id' => isset($dt->id_antrian_int) ? (int)$dt->id_antrian_int : 0,
        'service_date' => isset($dt->tgl_servis) ? $dt->tgl_servis : '',
        'identity_no' => isset($dt->no_identitas) ? $dt->no_identitas : '',
        'job_return_no' => isset($dt->id_wo_job_return) ? $dt->id_wo_job_return : '',
        'vehicle' => $vehicle,
        'kilometer' => isset($dt->km_terakhir) ? (int)$dt->km_terakhir : 0,
        'pud_identity' => isset($no_mesin) ? $this->m_sm->cekSRBU($no_mesin) : 'Tidak Termasuk',
        'book_service_no' => isset($dt->id_booking) ? $dt->id_booking : '',
        'bbm_level' => isset($dt->informasi_bensin) ? (int)$dt->informasi_bensin : 0,
        'id_work_order' => isset($dt->id_work_order) ? $dt->id_work_order : 0,
        'owner' => $owner,
        'carrier_same_with_owner' => isset($dt->tipe_coming) ? $dt->tipe_coming == 'milik' ? 1 : 0 : 0,
        'carrier' => $carrier,
        'ahass_service_reason' => isset($dt->keluhan) ? $dt->keluhan : '',
        'last_service_advisor_suggestion' => isset($dt->rekomendasi_sa) ? $dt->rekomendasi_sa : '',
        'activity_promotion_id' => isset($dt->activity_promotion_id) ? (int)$dt->activity_promotion_id : 0,
        'activity_promotion_name' => isset($act_promo['name']) ? $act_promo['name'] : '',
        'activity_capacity_id' => isset($dt->activity_capacity_id) ? $dt->activity_capacity_id : 0,
        'activity_capacity_name' => isset($act_cap['name']) ? $act_cap['name'] : '',
        'code_unit' => isset($tk->id_tipe_kendaraan) ? $tk->id_tipe_kendaraan : '',
        'code_unit_desc' => isset($tk->tipe_ahm) ? $tk->tipe_ahm : '',
      ];
    } else {

      $dt = $this->m_h2_api->getCustomerH1($f_h1)->row();
      if ($dt != null) {
        $vehicle = [
          'police_no' => $dt != null ? $dt->no_polisi : '',
          'stnk_name' => $dt != null ? $dt->no_polisi : '',
          'vehicle_year' => $dt != null ? (int)$dt->tahun_produksi : '',
          'vehicle_color_name' => $dt != null ? $dt->warna : '',
          'vehicle_type_name' => $dt != null ? $dt->id_tipe_kendaraan : '',
          'vehicle_product_name' => $dt->tipe_ahm,
          'engine_no' => $dt->no_mesin,
          'frame_no' => $dt->no_rangka,
        ];
        $tk = $this->db->get_where('ms_tipe_kendaraan', ['id_tipe_kendaraan' => $dt->id_tipe_kendaraan])->row();

        $owner = [
          'id' => 1,
          'name' => $dt->nama_customer,
          'email' => $dt->email ?: '',
          'phone' => $dt->no_hp,
          'address' => $dt->alamat,
          'current_address' => $dt->alamat,
          'district' => $dt->kecamatan,
          'sub_district' => $dt->kelurahan . '-' . $dt->id_kelurahan,
          'facebook' => $dt->facebook ?: '',
          'twitter' => $dt->twitter ?: '',
          'instagram' => $dt->instagram ?: '',
        ];

        $carrier = [
          'relationship' => '',
          'name' => '',
          'email' => '',
          'phone' => '',
          'address' => '',
          'current_address' => '',
          'district' => '',
          'sub_district' => '',
          'facebook' => '',
          'twitter' => '',
          'instagram' => '',
        ];

        $no_mesin = $dt->no_mesin;

        $result = [
          'service_id' => 0,
          'service_date' => '',
          'identity_no' => isset($dt->no_identitas) ? $dt->no_identitas : '',
          'job_return_no' => '',
          'vehicle' => $vehicle,
          'kilometer' => isset($dt->km_terakhir) ? (int)$dt->km_terakhir : 0,
          'pud_identity' => isset($no_mesin) ? $this->m_sm->cekSRBU($no_mesin) : 'Tidak Termasuk',
          'book_service_no' => isset($dt->id_booking) ? $dt->id_booking : '',
          'bbm_level' => isset($dt->informasi_bensin) ? (int)$dt->informasi_bensin : 0,
          'owner' => $owner,
          'carrier_same_with_owner' => 0,
          'carrier' => $carrier,
          'ahass_service_reason' => '',
          'last_service_advisor_suggestion' => '',
          'activity_promotion_id' => 0,
          'activity_promotion_name' => '',
          'activity_capacity_id' =>  0,
          'activity_capacity_name' => '',
          'code_unit' => isset($tk->id_tipe_kendaraan) ? $tk->id_tipe_kendaraan : '',
          'code_unit_desc' => isset($tk->tipe_ahm) ? $tk->tipe_ahm : '',
        ];
      }
    }
    if (isset($result)) {
      send_json(msg_sc_success($result, NULL));
    } else {
      $msg = ['No. Polisi : ' . $get['police_no'] . ' tidak ditemukan !'];
      send_json(msg_sc_error($msg));
    }
  }
}
