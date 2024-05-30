<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Prospek extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('M_sc_sp_stock', 'm_stock');
    $this->load->model('M_sc_sp_home', 'm_home');
    $this->load->model('M_h1_dealer_stok', 'm_stok');
    $this->load->model('M_sm_master', 'sm_m');
    $this->load->model('M_sc_master', 'm_sc');
    $this->load->model('m_admin');
    $this->load->helper('tgl_indo');
    $this->load->model('m_h1_dealer_diskon', 'm_diskon');



    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function buat_spk()
  {
    $get = $this->input->get();
    $mandatory = ['prospek_id' => 'required'];
    // send_json($get);
    cek_mandatory($mandatory, $get);

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'all'
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prp = $get_data->row();
    if (strtolower($prp->status_prospek)!='hot') {
      $msg = ['Silahkan ubah status prospek menjadi HOT terlebih dahulu'];
      send_json(msg_sc_error($msg));
    }
    $result = [
      'info_pelanggan' => [
        'customer' => [
          'name' => $prp->nama_konsumen,
          'no_ktp' => $prp->no_ktp,
          'phone' => $prp->no_telp,
          // 'no_kontak' => $prp->no_hp,
          'birth_date' => $prp->tgl_lahir == NULL ? '' : $prp->tgl_lahir,
          'address' => $prp->alamat,
          'no_kk' => '',
          'address_ktp' => $prp->alamat,
        ],
        'pajak' => [
          'kode_ppn' => 0,
          'spp' => '',
          'faktur_pajak' => '',
          'npwp' => isset($prp->npwp) ? $prp->npwp : '',
          'payment_method_id' => 0,
          'payment_method_name' => '',
          'dp' => 0,
          'master_tenor_id' => (int)'',
          'master_tenor_value' => (int)'',
          'angsuran' => 0,
          'bpkb_stnk' => false,
        ],
        'bpkb_stnk' => [
          'no_ktp' => '',
          'name' => '',
          'phone' => '',
          'birth_place' => '',
          'birth_date' => '',
          'address' => '',
          'postal_code' => '',
          'jabatan' => ''
        ],
      ],
    ];
    $get_data = $this->m_prospek->getProspekUnitDetail($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $row  = $get_data->row();

    $filter_prospek['select'] = 'detail_unit';
    $get_item = $this->m_prospek->getProspekUnitDetail($filter_prospek)->result();
    foreach ($get_item as $itm) {
      $fspk=[
        'id_dealer'=>$this->login->id_dealer,
        'id_tipe_kendaraan'=>$itm->id_tipe_kendaraan,
        'id_warna'=>$itm->id_warna,
      ];
      $item[] = [
        'id'             => (int)$itm->id_item_int,
        'unit_id'        => (int)$itm->id_tipe_kendaraan_int,
        'model_id'       => (int)$itm->id_warna_int,
        'accessories_id' => 0,
        'apparel_id'     => 0,
        'image'          => '',
        'name'           => '',
        'code'           => $itm->id_item,
        'price'          => (int)$row->price_unit,
        'prospek_spk'    => $this->_prospek_spk($fspk),
        'available'      => 0,
        'color'          => $itm->warna,
        'stock'          => 0,
        'qty'            => 0,
        'type'           => 'Unit'
      ];
    }
    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_acc = $this->m_prospek->getProspekAccessories($filter_prospek)->result();
    foreach ($get_acc as $acc) {
      $item[] = [
        'id'             => (int)$acc->id_part_int,
        'unit_id'        => 0,
        'model_id'       => 0,
        'accessories_id' => $acc->id_part,
        'apparel_id'     => 0,
        'image'          => '',
        'name'           => $acc->nama_part,
        'code'           => $acc->id_part,
        'price'          => $acc->accessories_harga,
        'prospek_spk'    => 0,
        'available'      => 0,
        'color'          => 0,
        'stock'          => 0,
        'qty'            => $acc->accessories_qty,
        'type'           => 'Accessories'
      ];
    }

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_app = $this->m_prospek->getProspekApparel($filter_prospek)->result();
    foreach ($get_app as $app) {
      $item[] = [
        'id'             => (int)$app->id_part_int,
        'unit_id'        => 0,
        'model_id'       => 0,
        'accessories_id' => 0,
        'apparel_id'     => $app->id_part,
        'image'          => '',
        'name'           => $app->nama_part,
        'code'           => $app->id_part,
        'price'          => (int)$app->apparel_harga,
        'prospek_spk'    => 0,
        'available'      => 0,
        'color'          => 0,
        'stock'          => 0,
        'qty'            => (int)$app->apparel_qty,
        'type'           => 'Apparel'
      ];
    }

    $result_data = [
      'name' => $row->tipe_ahm,
      'price_unit' => (float)$row->price_unit,
      'total_accessories' => (float)$row->total_accessories,
      'price_accessories' => (float)$row->price_accessories,
      'total_apparel' => (float)$row->total_apparel,
      'price_apparel' => (float)$row->price_apparel,
      'grand_total' => (float)$row->grand_total,
      'item' => $item
    ];
    $result['product'] = $result_data;

    $result['product']['sales_program'] = [];

    $get_doc = $this->m_sc->getDocumentProspek();
    $document = [];
    foreach ($get_doc as $doc) {
      $f_d = [
        'id_prospek' => $prp->id_prospek,
        'key' => $doc['key'],
        'id_dealer' => $this->login->id_dealer
      ];
      $pd = $this->m_prospek->getProspekDokumen($f_d);
      $url = '';
      if ($pd->num_rows() > 0) {
        $url = $pd->row()->path == '' ? '' : base_url($pd->row()->path);
      }
      $document[] = [
        'id' => (int)$doc['id'],
        'url' => $url,
        'document_key' => $doc['key'],
        'document_name' => $doc['name'],
        'is_required' => true
      ];
    }
    $result['document'] = $document;

    send_json(msg_sc_success($result, NULL));
  }


  public function cari_id_real($id_dealer, $id_karyawan_dealer)
  {

    //$tgl				= $this->input->post('tgl');
    $th         = date("y");
    $bln         = date("m");
    $tgl         = date("d");
		$tahun = date("Y");
    $isi         = $this->db->query("SELECT * FROM ms_karyawan_dealer INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
								WHERE ms_karyawan_dealer.id_karyawan_dealer = '$id_karyawan_dealer'")->row();
    $kode_dealer     = $isi->kode_dealer_md;
    $pr_num       = $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_dealer = '$id_dealer' and left(created_at,4)='$tahun' ORDER BY id_prospek DESC LIMIT 0,1");
    if ($pr_num->num_rows() > 0) {
      $row   = $pr_num->row();
      $pan  = strlen($row->id_prospek) - 5;
      $id   = substr($row->id_prospek, $pan, 5) + 1;
      if ($id < 10) {
        $kode1 = $th . $bln . $tgl . "0000" . $id;
      } elseif ($id > 9 && $id <= 99) {
        $kode1 = $th . $bln . $tgl . "000" . $id;
      } elseif ($id > 99 && $id <= 999) {
        $kode1 = $th . $bln . $tgl . "00" . $id;
      } elseif ($id > 999) {
        $kode1 = $th . $bln . $tgl . "0" . $id;
      }
      $kode = $kode_dealer . $kode1;
    } else {
      $kode = $kode_dealer . $th . $bln . $tgl . "00001";
    }
    //$rt = rand(1111,9999);
    $rt = $this->m_admin->get_customer(); // generate random kode utk field id customer
    $get_dealer = $this->db->query("SELECT kode_dealer_md from ms_dealer WHERE id_dealer='$id_dealer'");
    if ($get_dealer->num_rows() > 0) {
      $get_dealer = $get_dealer->row()->kode_dealer_md;
      $panjang = strlen($get_dealer);
    } else {
      $get_dealer = '';
      $panjang = '';
    }
    $tgl            = $this->input->post('tgl');
    $th             = date("y");
    $waktu           = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $pr_num         = $this->db->query("SELECT id_list_appointment FROM tr_prospek WHERE RIGHT(id_list_appointment,$panjang) = '$get_dealer' and left(created_at,4)='$tahun' ORDER BY id_list_appointment DESC LIMIT 0,1");
    if ($pr_num->num_rows() > 0) {
      $row   = $pr_num->row();
      $pan  = strlen($row->id_list_appointment) - ($panjang + 6);
      $id   = substr($row->id_list_appointment, $pan, 5) + 1;
      if ($id < 10) {
        $kode1 = $th . "0000" . $id;
      } elseif ($id > 9 && $id <= 99) {
        $kode1 = $th . "000" . $id;
      } elseif ($id > 99 && $id <= 999) {
        $kode1 = $th . "00" . $id;
      } elseif ($id > 999 && $id <= 9999) {
        $kode1 = $th . "0" . $id;
      } else {
        $kode1 = $th . "" . $id;
      }
      $kode2 = "PR" . $kode1 . "-" . $get_dealer;
    } else {
      $kode2 = "PR" . $th . "00001-" . $get_dealer;
    }

    return array('kode' => $kode, 'rt' => $rt, 'kode2' => $kode2);
    //echo $kode."|".$rt."|".$kode2;
  }
  function create()
  {
    
    // $msg[] = "Silahkan coba 5 menit lagi";
    $post = $this->input->post();
    // send_json($post);

    if (isset($post['prospek_name'])) {
      if ($post['prospek_name'] == '') {
        $msg[] = "Prospek name required";
      }else{
        $stringName = $post['prospek_name'];
        $vowelsCount = strlen(preg_replace('/[^aeiouAEIOU]/', '', $stringName));
        if($vowelsCount==0){
          $msg[] = "Nama konsumen tidak sesuai";
        }
      }
    } else {
      $msg[] = "Prospek name required";
    }
    if (isset($post['prospek_date'])) {
      if ($post['prospek_date'] == '') {
        $msg[] = "Prospek date required";
      }
    } else {
      $msg[] = "Prospek date required";
    }
    if (isset($post['customer_id'])) {
      if ($post['customer_id'] == '') {
        $msg[] = "Customer ID required";
      }
    } else {
      $msg[] = "Customer ID required";
    }
    if (isset($post['no_hp'])) {
      if ($post['no_hp'] == '' || $post['no_hp'] == 0) {
        $msg[] = "No. HP required";
      }else{
        if(!is_numeric($post['no_hp'])){ 
          $msg[] = "Format No. HP harus angka";
        }else if($post['no_hp'][0]==' ' || $post['no_hp'][0]=='+' || $post['no_hp'][0]=='.'){
          $msg[] = "Format No. HP tidak boleh ada spasi/ spesial karakter";
        }else if(strlen($post['no_hp']) < 10 || strlen($post['no_hp']) > 14){
          $msg[] = "Jumlah Digit No. HP tidak sesuai";
        }else if($post['no_hp'][0]!=0 || $post['no_hp'][0]!='0'){
          $msg[] = "Format No. HP tidak sesuai";
        }
      }
    } else {
      $msg[] = "No. HP no found";
    }
    if (isset($post['no_telepon'])) {
      // if ($post['no_telepon'] == '') {
      //   $msg[] = "No. HP required";
      // }
    } else {
      $msg[] = "No. telepon no found";
    }
    if (isset($post['customer_address'])) {
      if ($post['customer_address'] == '') {
        $msg[] = "Customer Address required";
      }else{
        $stringName = $post['customer_address'];
        $vowelsCount = strlen(preg_replace('/[^aeiouAEIOU]/', '', $stringName));
        if(($vowelsCount==0 && strlen($stringName)>= 20) || strlen($stringName) <= 2){
          $msg[] = "Alamat konsumen tidak sesuai";
        }
      }
    } else {
      $msg[] = "Customer Address required";
    }
    if (isset($post['customer_latitude'])) {
      if ($post['customer_latitude'] == '') {
        $msg[] = "Customer Latitude required";
      }
    } else {
      $msg[] = "Customer Latitude required";
    }
    if (isset($post['customer_longitude'])) {
      if ($post['customer_longitude'] == '') {
        $msg[] = "Customer Longitude required";
      }
    } else {
      $msg[] = "Customer Longitude required";
    }
    // if (isset($post['office_address'])) {
    //   if ($post['office_address'] == '') {
    //     $msg[] = "Office Address required";
    //   }
    // } else {
    //   $msg[] = "Office Address required";
    // }
    if (isset($post['metode_follow_up_id'])) {
      if ($post['metode_follow_up_id'] == '') {
        $msg[] = "Metode follow up required";
      }
    } else {
      $msg[] = "Metode follow up required";
    }
    // if (isset($post['unit_model_id'])) {
    //   if ($post['unit_model_id'] == '') {
    //     $msg[] = "Unit model required required";
    //   }
    // } else {
    //   $msg[] = "Unit model required required";
    // }
    $hari_ini = date('Y-m-d');
    $dealer_login = $this->login->id_dealer;
    $flp_login = $this->login->username;

    $kry = sc_user(['username' => $this->login->username])->row();

    if(date('Y-m-d')>='2023-06-26') { 
      if($kry!=false){
        $honda_id = $kry->honda_id;
        
        $this->load->model('m_dms');
        $filter_target_prospek = [
          'honda_id_in' => $honda_id == NULL ? '' : $honda_id,
          'id_dealer' => $dealer_login,
          'tahun' => get_y(),
          'bulan' => get_m(),
          'select' => 'sum_prospek',
          'active' => 1,
        ];

        $prospek_target=$this->m_dms->getH1TargetManagement($filter_target_prospek)->row()->sum_prospek;

        if((int)$prospek_target==0){
          $msg[] = "Mohon Setting Target FLP terlebih dahulu!";
        } 
      }
    }

    if (isset($msg)) {
      send_json(msg_sc_error($msg));
    } else {
      $no_hp = $post['no_hp'];
      $alamat_cust = $post['customer_address'];
      $alamat_cust = preg_replace("/'/", '', $alamat_cust);
      
      // prevent duplicate entry
      $is_konsumen_exist = $this->db->query("select id_prospek from tr_prospek tp where id_dealer = '$dealer_login' and id_flp_md= '$flp_login' and (alamat = '$alamat_cust' or no_hp = '$no_hp') and date(created_at) = '$hari_ini' and status_prospek not in ('No Deal','Deal','Not Deal')");
      if ($is_konsumen_exist->num_rows() > 0) {
        $msg[] = "Data sudah pernah disimpan, Silahkan dicek kembali No Hp dan Alamat Konsumen!";
        send_json(msg_sc_error($msg));
      }

      /*
		  $this->db_crm       = $this->load->database('db_crm', true);
      $is_konsumen_leads_crm = $this->db_crm->query("select leads from leads tp where id_dealer = '$dealer_login' and no_hp = '$no_hp' and date(created_at) = '$hari_ini'");
      */

      // send_json(json_decode($post['accessories']));
      $id_prospek = $this->m_prospek->getIDProspek($post['sumber_prospek_id'], $this->login->id_dealer, false);
      $accessories = json_decode($post['accessories'], true);
      $dokumen = json_decode($post['document'], true);
      $apparel = json_decode($post['apparel'], true);
      // send_json($id_prospek);
      $this->load->library('upload');
      $ym = date('Y/m');
      $y_m = date('y-m');
      $path = "./uploads/prospek/" . $ym;
      if (!is_dir($path)) {
        mkdir($path, 0777, true);
      }
      $config['upload_path']   = $path;
      $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
      $config['max_size']      = '3000';
      $config['max_width']     = '30000';
      $config['max_height']    = '30000';
      $config['remove_spaces'] = TRUE;
      $config['overwrite']     = TRUE;
      $config['file_name']     = $y_m . '-' . $id_prospek . '-img';
      $this->upload->initialize($config);
      if ($this->upload->do_upload('customer_image')) {
        $customer_image     = 'uploads/prospek/' . $ym . '/' . $this->upload->file_name;
      }

      // $filter_unit['id_tipe_kendaraan_int'] = $this->input->post('unit_id');
      // $tk = $this->m_unit->getTipeKendaraan($filter_unit);
      // if ($tk->num_rows() > 0) {
      //   $id_tipe_kendaraan = $tk->row()->id_tipe_kendaraan;
      // } else {
      //   $msg = ['Unit ID not found'];
      //   // send_json(msg_sc_error($msg));
      // }
      $filter['id_item_int'] = $this->input->post('unit_model_id');
      $item = $this->m_unit->getItem($filter);
      if ($item->num_rows() > 0) {
        $id_warna = $item->row()->id_warna;
        $id_tipe_kendaraan = $item->row()->id_tipe_kendaraan;
      } else {
        $msg = ['Unit Model ID not found'];
        // send_json(msg_sc_error($msg));
      }

      if (isset($id_warna) && isset($id_tipe_kendaraan)) {
        $filter_item = [
          'id_warna' => $id_warna,
          'id_tipe_kendaraan' => $id_tipe_kendaraan
        ];
        $id_item = $this->m_unit->getItem($filter_item);
        if ($id_item->num_rows() > 0) {
          $id_item = $id_item->row()->id_item;
        } else {
          $msg = ['Unit Model ID not found'];
          send_json(msg_sc_error($msg));
        }
      }

      $cari_id = $this->cari_id_real($this->login->id_dealer, $kry->id_karyawan_dealer);
      if ($post['preferensi_uji_perjalanan'] != '') {
        $tgl_tes = $post['preferensi_uji_perjalanan'];
        $test_ride = '1';
      } else {
        $tgl_tes = '';
        $test_ride = '0';
      }
      $pekerjaan = null;
      $sub_pekerjaan = null;
      if ($post['pekerjaan_id'] != '') {
        $f_pkj = ['id_sub_pekerjaan' => $post['pekerjaan_id']];
        $get_pkj = $this->m_sc->getPekerjaan($f_pkj)->row();
        if ($get_pkj != null) {
          $pekerjaan = $get_pkj->id_pekerjaan;
          $sub_pekerjaan = $get_pkj->id;
        }
      }
      $insert = [
        'id_list_appointment' => $cari_id['kode2'],
        'id_prospek' => $id_prospek,
        'id_customer' => $this->m_admin->get_customer(),
        'tgl_prospek' => $post['prospek_date'],
        'nama_konsumen' => strtoupper($post['prospek_name']),
        'id_dealer' => $this->login->id_dealer,
        'status_prospek' => $post['prospek_status'],
        'customer_image' => isset($customer_image) ? $customer_image : NULL,
        // 'document' => isset($document) ? $document : NULL,
        'no_hp' => $post['no_hp'],
        'no_telp' => $post['no_telepon'],
        'jenis_kelamin' => $post['jenis_kelamin'] == 'L' ? 'Pria' : 'Wanita',
        'no_ktp' => $post['nik'],
        'alamat' => strtoupper($post['customer_address']),
        'latitude' => $post['customer_latitude'],
        'longitude' => $post['customer_longitude'],
        'pekerjaan' => $pekerjaan,
        'sub_pekerjaan' => $sub_pekerjaan,
        'alamat_kantor' => $post['office_address'],
        'no_telp_kantor' => $post['office_phone'],
        'id_tipe_kendaraan' => isset($id_tipe_kendaraan) ? $id_tipe_kendaraan : null,
        'id_warna' => isset($id_warna) ? $id_warna : null,
        'tgl_tes_kendaraan' => $tgl_tes,
        'test_ride_preference' => $test_ride,
        // 'accessories' => $post['accessories'],
        // 'apparel' => $post['apparel'], //belum ada
        'rencana_pembelian' => $post['rencana_pembelian'],
        'metode_follow_up_id' => $post['metode_follow_up_id'],
        'id_karyawan_dealer' => $kry->id_karyawan_dealer,
        'id_flp_md' => $kry->id_flp_md,
        'created_at' => waktu_full(),
        'created_by' => $this->login->id_user,
        // 'tgl_lahir'     => $post['birth_date'],
        'sumber_prospek' => $post['sumber_prospek_id'],
        'status_aktifitas' => $post['prospek_status'] != '' ? 'In Progress' : 'Not Started',
        'input_from' => 'sc',
        'jenis_wn' => 'WNI',
        'prioritas_prospek' => 0,
        'jenis_customer'    => 'regular'
        // 'status_prospek'=>'Low'
      ];
      $fol_up = [
        'id_prospek' => $id_prospek,
        'tgl_fol_up' => $post['prospek_date'],
        'metode_fol_up' => $post['metode_follow_up_id']
      ];
      $total_accessories = 0;
      $price_accessories = 0;

      foreach ($accessories as $acc) {
        $fp = ['id_part_int' => $acc['accessories_id']];
        $prt = $this->m_sm->getParts($fp);
        cek_referensi($prt, 'Accessories ID');
        $prt = $prt->row();
        $prospek_accessories[] = [
          'id_prospek' => $id_prospek,
          'accessories_id' => $acc['accessories_id'],
          'accessories_qty' => $acc['accessories_qty']
        ];
        $sum = $prt->harga_dealer_user * $acc['accessories_qty'];
        $price_accessories += $sum;
        $total_accessories += $acc['accessories_qty'];
      }
      foreach ($dokumen as $acc) {
        $prospek_dokumen[] = [
          'id_prospek' => $id_prospek,
          'key' => $acc['key'],
          'path' => $acc['path']
        ];
      }
      $total_apparel = 0;
      $price_apparel = 0;
      foreach ($apparel as $acc) {
        $fp = ['id_part_int' => $acc['apparel_id']];
        $prt = $this->m_sm->getParts($fp);
        cek_referensi($prt, 'Apparel ID');
        $prt = $prt->row();
        $prospek_apparel[] = [
          'id_prospek' => $id_prospek,
          'apparel_id' => $acc['apparel_id'],
          'apparel_qty' => $acc['apparel_qty']
        ];
        $sum = $prt->harga_dealer_user * $acc['apparel_qty'];
        $price_apparel += $sum;
        $total_apparel += $acc['apparel_qty'];
      }
      if (isset($id_warna)) {
        $params = ['id_warna' => $id_warna, 'id_tipe_kendaraan' => $id_tipe_kendaraan];
        $price_unit = $this->m_prospek->cek_bbn($params);
        $insert['price_unit'] = $price_unit;
        $insert['total_accessories'] = $total_accessories;
        $insert['price_accessories'] = $price_accessories;
        $insert['total_apparel'] = $total_apparel;
        $insert['price_apparel'] = $price_apparel;
        $insert['grand_total'] = $price_apparel + $total_accessories + $price_unit;
      }

      $insert_folup = [
        'id_prospek' => $id_prospek,
        'tgl_fol_up' => get_ymd(),
        'metode_fol_up' => $post['metode_follow_up_id'],
        'keterangan' => ''
      ];
      $f_k = ['id_karyawan_dealer' => $kry->id_karyawan_dealer, 'select' => 'all'];
      $nkry = $this->m_sc->getKaryawan($f_k)->row();

      $ins_activity = [
        'id_dealer'              => $this->login->id_dealer,
        'parent_id'              => $id_prospek,
        'id_karyawan_dealer_int' => $nkry->id_karyawan_dealer_int,
        'name'                   => $post['prospek_name'],
        'info'                   => 'Follow Up Prospek 1',
        'id_kategori_activity'   => 1,
        'tanggal'                => get_ymd(),
        'jam'                    => '',
        'status'                 => 'new',
        'created_at'             => waktu_full(),
        'created_by'            => $this->login->id_user
      ];

      $ins_guest = [
        'id_karyawan_dealer' => $kry->id_karyawan_dealer,
        'nama_konsumen' => $post['prospek_name'],
        'alamat' =>  $post['customer_address'],
        'no_telp' => $post['no_hp'],
        'id_dealer' => $this->login->id_dealer,
        'created_at' => waktu_full(),
        'created_by' => $this->login->id_user,
        'id_tipe_kendaraan' => isset($id_tipe_kendaraan) ? $id_tipe_kendaraan : null,
        'id_warna' => isset($id_warna) ? $id_warna : null,
        'active' => 1,
        'generate' => 'sc'
      ];


      // $tes = [
      //   'insert' => $insert,
      //   'fol_up' => $fol_up,
      //   'accessories' => $prospek_accessories,
      //   'document' => $prospek_dokumen,
      //   'apparel' => $prospek_apparel,
      // ];
      // send_json($tes);
      $this->db->query("SET FOREIGN_KEY_CHECKS=0");

      $this->db->trans_begin();
      $this->db->insert('tr_prospek_fol_up', $insert_folup);
      $this->m_activity->insertActivity($ins_activity);
      $this->db->insert('tr_prospek', $insert);
      $this->db->insert('tr_guest_book_new', $ins_guest);
      // $this->db->insert('tr_prospek_fol_up', $fol_up);
      if (isset($prospek_accessories)) {
        $this->db->insert_batch('tr_prospek_accessories', $prospek_accessories);
      }
      if (isset($prospek_apparel)) {
        $this->db->insert_batch('tr_prospek_apparel', $prospek_apparel);
      }
      if (isset($prospek_dokumen)) {
        $this->db->insert_batch('tr_prospek_dokumen', $prospek_dokumen);
      }
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $msg = ['Terjadi kesalahan'];
        send_json(msg_sc_error($msg));
      } else {
        $this->db->trans_commit();
        $msg = ['Data berhasil disimpan'];
        $this->db->query("SET FOREIGN_KEY_CHECKS=1");
        send_json(msg_sc_success(NULL, $msg));
      }
    }
  }

  function customer_detail()
  {
    $get = $this->input->get();
    $mandatory = [
      'prospek_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);
    $filter = [
      'id_prospek_int' => $get['prospek_id'],
      'select' => 'customer_detail_sc',
      'id_dealer' => $this->login->id_dealer
    ];
    $get_prospek = $this->m_prospek->getProspek($filter);
    if ($get_prospek->num_rows()) {
      $prospek = $get_prospek->row();
      $prospek->sumber_prospek_id = (int)$prospek->sumber_prospek_id;
      $prospek->sumber_prospek_name = (string)$prospek->sumber_prospek_name;
      $prospek->metode_follow_up_id = (int)$prospek->metode_follow_up_id;
      $prospek->metode_follow_up_name = $prospek->metode_fol_up_name;
      $prospek->nik = $prospek->ktp;
      $prospek->unit_id = (int)$prospek->unit_id;
      $prospek->pekerjaan_name = (string)$prospek->sub_pekerjaan_name;
      $prospek->tempat_lahir = (string)$prospek->tempat_lahir;
      $prospek->tgl_lahir = (string)$prospek->tgl_lahir;
      $prospek->kodepos = (string)$prospek->kodepos;
      $prospek->email = (string)$prospek->email;
      $prospek->image = image_karyawan($prospek->image, 'laki-laki');
    } else {
      $prospek = NULL;
    }
    send_json(msg_sc_success($prospek, NULL));
  }

  function customer_update()
  {
    $post = $this->input->post();

    if (isset($post['name'])) {
      if ($post['name'] == '') {
        $msg[] = "Prospek name required";
      }
    } else {
      $msg[] = "Prospek name required";
    }
    if (isset($post['prospek_date'])) {
      if ($post['prospek_date'] == '') {
        $msg[] = "Prospek date required";
      }
    } else {
      $msg[] = "Prospek date required";
    }
    if (isset($post['no_hp'])) {
      if ($post['no_hp'] == '' || $post['no_hp'] == 0) {
        $msg[] = "No. HP required";
      }else{
        if(!is_numeric($post['no_hp'])){ 
          $msg[] = "Format No. HP harus angka";
        }else if($post['no_hp'][0]==' ' || $post['no_hp'][0]=='+' || $post['no_hp'][0]=='.'){
          $msg[] = "Format No. HP tidak boleh ada spasi/ spesial karakter";
        }else if(strlen($post['no_hp']) < 10){
          $msg[] = "Jumlah Digit No. HP tidak sesuai";
        }
      }

    } else {
      $msg[] = "Customer Phone required";
    }
    if (isset($post['address'])) {
      if ($post['address'] == '') {
        $msg[] = "Customer Address required";
      }
    } else {
      $msg[] = "Customer Address required";
    }
    if (isset($post['latitude'])) {
      if ($post['latitude'] == '') {
        $msg[] = "Customer Latitude required";
      }
    } else {
      $msg[] = "Customer Latitude required";
    }
    if (isset($post['longitude'])) {
      if ($post['longitude'] == '') {
        $msg[] = "Customer Longitude required";
      }
    } else {
      $msg[] = "Customer Longitude required";
    }
    // if (isset($post['office_address'])) {
    //   if ($post['office_address'] == '') {
    //     $msg[] = "Office Address required";
    //   }
    // } else {
    //   $msg[] = "Office Address required";
    // }

    if (isset($msg)) {
      send_json(msg_sc_error($msg));
    } else {

      $this->load->library('upload');
      $ym = date('Y/m');
      $y_m = date('y-m');
      $path = "./uploads/prospek/" . $ym;
      if (!is_dir($path)) {
        mkdir($path, 0777, true);
      }
      $filter = [
        'id_prospek_int' => $post['prospek_id'],
        'id_dealer' => $this->login->id_dealer
      ];
      $get_prospek = $this->m_prospek->getProspek($filter);
      if ($get_prospek->num_rows() > 0) {
        $prp = $get_prospek->row();
      } else {
        $msg = ['Prospek not found'];
        send_json(msg_sc_error($msg));
      }
      $config['upload_path']   = $path;
      $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
      $config['max_size']      = '3000';
      $config['max_width']     = '30000';
      $config['max_height']    = '30000';
      $config['remove_spaces'] = TRUE;
      $config['overwrite']     = TRUE;
      $config['file_name']     = $y_m . '-' . $prp->id_prospek . '-img';
      $this->upload->initialize($config);
      if ($this->upload->do_upload('image')) {
        $image     = 'uploads/prospek/' . $ym . '/' . $this->upload->file_name;
      }

      $id_tipe_kendaraan = '';
      if (isset($post['unit_id'])) {
        if (($post['unit_id'] == 0 || $post['unit_id'] == '') === false) {
          $filter_unit['id_tipe_kendaraan_int'] = $post['unit_id'];
          $tk = $this->m_unit->getTipeKendaraan($filter_unit);
          if ($tk->num_rows() > 0) {
            $id_tipe_kendaraan = $tk->row()->id_tipe_kendaraan;
          } else {
            $msg = ['Unit ID not found'];
            send_json(msg_sc_error($msg));
          }
        }
      }
      $no_hp = '';
      if (isset($post['no_hp'])) {
        $no_hp = $post['no_hp'];
      }

      if ($post['preferensi_uji_perjalanan'] != '') {
        $tgl_tes = $post['preferensi_uji_perjalanan'];
        $test_ride = '1';
      } else {
        $tgl_tes = '';
        $test_ride = '0';
      }

      $pekerjaan = null;
      $sub_pekerjaan = null;
      if ($post['pekerjaan_id'] != '') {
        $f_pkj = ['id_sub_pekerjaan' => $post['pekerjaan_id']];
        $get_pkj = $this->m_sc->getPekerjaan($f_pkj)->row();
        if ($get_pkj != null) {
          $pekerjaan = $get_pkj->id_pekerjaan;
          $sub_pekerjaan = $get_pkj->id;
        }
      }
      $sumber_prospek = $this->db->query("SELECT id_dms FROM ms_sumber_prospek WHERE id_dms='{$post['sumber_prospek_id']}'")->row();
      $update = [
        'tgl_prospek' => $post['prospek_date'],
        'nama_konsumen' => strtoupper($post['name']),
        'customer_image' => isset($image) ? $image : NULL,
        'no_hp' => $no_hp,
        'no_telp' => $post['no_telepon'],
        'no_ktp' => $post['nik'],
        'jenis_kelamin' => $post['jenis_kelamin'] == 'L' ? 'Pria' : 'Wanita',
        'alamat' => $post['address'],
        'latitude' => $post['latitude'],
        'longitude' => $post['longitude'],
        'pekerjaan' => $pekerjaan,
        'sub_pekerjaan' => $sub_pekerjaan,
        'alamat_kantor' => $post['office_address'],
        'no_telp_kantor' => $post['office_phone'],
        'id_tipe_kendaraan' => $id_tipe_kendaraan,
        'tgl_tes_kendaraan' => $tgl_tes,
        'test_ride_preference' => $test_ride,
        'sumber_prospek' => $sumber_prospek->id_dms,
        'updated_at' => waktu_full(),
        'updated_by' => $this->login->id_user,
      ];
      // $tes = [
      //   'update' => $update,
      // ];
      // send_json($tes);
      $this->db->query("SET FOREIGN_KEY_CHECKS=0");

      $this->db->trans_begin();
      $this->db->update('tr_prospek', $update, ['id_prospek_int' => $post['prospek_id']]);
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        $msg = ['Terjadi kesalahan'];
        send_json(msg_sc_error($msg));
      } else {
        $this->db->trans_commit();
        $msg = ['Data has been updated'];
        $this->db->query("SET FOREIGN_KEY_CHECKS=1");

        send_json(msg_sc_success(NULL, $msg));
      }
    }
  }
  function change_status()
  {
    $post = $this->input->post();
    $mandatory = [
      'prospek_id' => 'required',
      'status' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    if($prospek->leads_id !=''){
      $msg = ['Gagal! Silahkan update status prospek di NMS'];
      send_json(msg_sc_error($msg));
    }

    $update = [
      'status_prospek' => $post['status'],
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user
    ];
    // $tes = [
    //   'update' => $update,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_prospek', $update, ['id_prospek' => $prospek->id_prospek]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Status has been updated'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function _cek_usia($tahun_sekarang, $tahun_lahir)
  {
    $tgl = explode("-", $tahun_lahir);
    $explode = $tgl[0];
    $umur = $tahun_sekarang - $explode;
    if ($umur < 17) {
      return true;
    }
  }

  function deal()
  {

    $id_dealer = $this->login->id_dealer;
    $post = $this->input->post();
    $mandatory = [
      'prospek_id'        => 'required',
      'name'              => 'required',
      'no_ktp'            => 'required',
      'email'             => 'required',
      'phone'             => 'required',
      'birth_date'        => 'required',
      'address'           => 'required',
      'no_kk'             => 'required',
      'address_ktp'       => 'required',
      'payment_method_id' => 'required',
      'unit_model_id'     => 'required',
      'accessories'       => 'required',
      'apparel'           => 'required',
      'sales_program'     => 'required',
      'sales_program'     => 'required',
      'bpkb_stnk'         => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prp = $get_data->row();

    if($prp->leads_id !=''){
      $msg = ['Gagal! Silahkan update status prospek di NMS'];
      send_json(msg_sc_error($msg));
    }

    // Cek Status Prospek Harus Hot
    if (strtolower($prp->status_prospek)!='hot') {
      $msg = ['Silahkan ubah status prospek menjadi HOT terlebih dahulu'];
      send_json(msg_sc_error($msg));
    }

    //Cek Dokumen KK & KTP Wajib di Upload
    $cek_dok = $this->m_prospek->getJmlProspekDokumenKtpKkBelumDiisi($prp->id_prospek);
    if ($cek_dok) {
      send_json(msg_sc_error([$cek_dok]));
    }

    //Cek On SPK
    $f_spk = ['id_customer' => $prp->id_customer, 'id_dealer' => $prp->id_dealer];
    $cek_spk = $this->m_spk->getSPK($f_spk);
    if ($cek_spk->num_rows() > 0) {
      $msg = ['Prospek sudah diproses menjadi SPK'];
      send_json(msg_sc_error($msg));
    }

    $no_spk = $this->m_spk->cari_id_new($this->login->id_dealer);

    $sales_program = json_decode($post['sales_program'], true);
    $jenis_beli = $post['payment_method_id'] == 1 ? 'Cash' : 'Kredit';
    $s_program = $this->m_prospek->setSalesProgramForSPK($sales_program, $prp->id_tipe_kendaraan, $jenis_beli);

    $params = ['id_warna' => $prp->id_warna, 'id_tipe_kendaraan' => $prp->id_tipe_kendaraan];
    $prc = $this->m_prospek->cek_bbn($params, true);


    if ($this->_cek_usia(tanggal(), $this->input->post('birth_date'))) {
      $msg = ['Usia Kurang Dari 17 Tahun'];
      send_json(msg_sc_error($msg));
    }

    $insert = [
      'no_spk'        => $no_spk,
      'id_dealer'     => $this->login->id_dealer,
      'id_customer'   => $prp->id_customer,
      'nama_konsumen' => $post['name'],
      'no_ktp'        => $post['no_ktp'],
      'no_kk'         => $post['no_kk'],
      'email'         => $this->input->post('email'),
      'no_telp'       => $this->input->post('phone'),
      'no_hp'         => $prp->no_hp,
      'tgl_lahir'     => $this->input->post('birth_date'),
      'alamat2'       => $this->input->post('address'),
      'alamat'        => $this->input->post('address_ktp'),
      'kode_ppn'      => $this->input->post('kode_ppn'),
      'spp'           => $this->input->post('spp'),
      'faktur_pajak'  => $this->input->post('faktur'),
      'npwp'          => $this->input->post('npwp'),
      'jenis_beli'    => $jenis_beli,
      'dp_stor'       => $this->input->post('dp'),
      'tenor'         => $this->input->post('tenor_id'),
      'angsuran'      => $this->input->post('angsuran'),
      'diskon'        => 0,
      'bpkb_stnk'             => $post['bpkb_stnk'] == 'false' ? 0 : 1,
      'nama_bpkb'             => $this->input->post('bpkb_stnk_name') ?: $post['name'],
      'bpkb_stnk_phone'       => $this->input->post('bpkb_stnk_phone'),
      'bpkb_stnk_birth_place' => $this->input->post('bpkb_stnk_birht_place'), //Typo Dari Inputan
      'bpkb_stnk_birth_date'  => $this->input->post('bpkb_stnk_birth_date'),
      'alamat_ktp_bpkb'       => $this->input->post('bpkb_stnk_address') ?: $post['address_ktp'],
      'no_ktp_bpkb'           => $this->input->post('bpkb_stnk_no_ktp') ?: $post['no_ktp'],
      'bpkb_stnk_postal_code' => $this->input->post('bpkb_stnk_postal_code'),
      'bpkb_stnk_jabatan'     => $this->input->post('bpkb_stnk_jabatan'),
      'id_tipe_kendaraan'     => $prp->id_tipe_kendaraan,
      'id_warna'       => $prp->id_warna,
      'the_road'       => 'On The Road',
      'created_at'     => waktu_full(),
      'created_by'     => $this->login->id_user,
      'status_spk'     => 'booking',
      'tgl_spk'        => get_ymd(),
      'harga'          => $prc['dpp'],
      'harga_tunai'    => $prc['harga_tunai'],
      'harga_off_road' => $prc['harga_jual'],
      'harga_on_road'  => $prc['harga_tunai'],
      'ppn'            => $prc['ppn'],
      'biaya_bbn'      => $prc['biaya_bbn'],
      'total_bayar'    => $prc['harga_tunai'],
      'input_from'     => 'sc',
      'jenis_wn'       => 'WNI',
      'pekerjaan'      => $prp->pekerjaan,
      'tanda_jadi'     => 0,
      'longitude'      => 0,
      'latitude'       => 0,
      'status_survey' => 'baru'
    ];
    if ($jenis_beli == 'Kredit') {
      $ins_skema_kredit = [
        'id_prospek'     => $prp->id_prospek,
        'id_dealer'      => $this->login->id_dealer,
        'id_finco'       => '',
        'harga_on_road'  => $prc['harga_tunai'],
        'harga_off_road' => $prc['harga_jual'],
        'bbn'            => $prc['biaya_bbn'],
        'tenor'          => $insert['tenor'],
        'angsuran'       => $insert['angsuran'],
        'dp'             => $insert['dp_stor'],
        'created_at'     => waktu_full(),
        'created_by'     => $this->login->id_user,
      ];
    }
    $sales_program = json_decode($post['sales_program'], true);
    foreach ($sales_program as $key => $sp) {
      $f_s_program = [
        'id_sales_program' => $sp['sales_program_id']
      ];
      $s_program = $this->m_home->getSalesProgram($f_s_program)->row();
      if ($key == 0 && $s_program != null) {
        $insert['program_umum'] = $s_program->code;
        if (strtolower($insert['jenis_beli']) == 'kredit') {
          $insert['voucher_2'] = $s_program->price;
        } else {
          $insert['voucher_1'] = $s_program->price;
        }
      }
      if ($key == 1 && $s_program != null) {
        $insert['program_gabungan'] = $s_program->code;
        if (strtolower($insert['jenis_beli']) == 'kredit') {
          $insert['voucher_2'] = $s_program->price;
        } else {
          $insert['voucher_1'] = $s_program->price;
        }
      }
    }
    $no_mesin = null;
    $book_no_mesin = $this->m_spk->get_nosin_fifo($this->login->id_dealer, $prp->id_tipe_kendaraan, $prp->id_warna);
    if ($book_no_mesin == false) {
      $id_ind = $this->m_spk->get_kode_indent($id_dealer);
      $ins_indent = [
        'id_indent'         => $id_ind,
        'id_spk'            => $no_spk,
        'id_dealer'         => $id_dealer,
        'nama_konsumen'     => $post['name'],
        'alamat'            => $this->input->post('address'),
        'no_ktp'            => $post['no_ktp'],
        'no_telp'           => $this->input->post('phone'),
        'email'             => $this->input->post('email'),
        'id_tipe_kendaraan' => $prp->id_tipe_kendaraan,
        'id_warna'          => $prp->id_warna,
        'nilai_dp'          => $this->input->post('dp'),
        'ket'               => '',
        'qty'               => 1,
        'status'            => 'requested',
        'tgl'               => date('Y-m-d'),
        'created_at'        => waktu_full(),
        'created_by'        => $this->login->id_user
      ];

      $id_po = $this->m_spk->newPO_ID('indent', $id_dealer);
      $item = $this->db->query("SELECT id_item FROM ms_item WHERE id_tipe_kendaraan = '$prp->id_tipe_kendaraan' AND id_warna = '$prp->id_warna'");
      $id_item = ($item->num_rows() > 0) ? $item->row()->id_item : "";
      $bulan  = date("m");
      $tahun  = date("Y");
      $po_indent = [
        'id_po'         => $id_po,
        'bulan'         => $bulan,
        'tahun'         => $tahun,
        'tgl'           => date('Y-m-d'),
        'id_dealer'     => $id_dealer,
        'created_at'    => waktu_full(),
        'created_by'    => $this->login->id_user,
        'po_from'       => $no_spk,
        'status'         => 'input',
        'jenis_po'         => 'PO Indent',
        'submission_deadline' => date('Y-m-d'),
        'id_pos_dealer' => ''
      ];
      $po_indent_detail = [
        'id_po'         => $id_po,
        'id_item'         => $id_item,
        'qty_order'         => 1,
        'qty_po_fix'         => 1
      ];
    } else {
      $no_mesin       = $book_no_mesin;
      $insert['status_spk']   = 'booking';
      $insert['no_mesin_spk'] = $no_mesin;
      $upd_penerimaan = ['no_spk' => $no_spk, 'status_on_spk' => $insert['status_spk'], 'booking_at' => waktu_full(), 'booking_by' => $this->login->id_user];
    }
    $accessories = json_decode($post['accessories'], true);
    $dokumen = json_decode($post['document'], true);
    $apparel = json_decode($post['apparel'], true);
    foreach ($accessories as $acc) {
      $fp = ['id_part_int' => $acc['accessories_id']];
      $prt = $this->m_sm->getParts($fp);
      cek_referensi($prt, 'Accessories ID');
      $prt = $prt->row();
      $spk_accessories[] = [
        'no_spk' => $no_spk,
        'accessories_id' => $acc['accessories_id'],
        'accessories_qty' => $acc['accessories_qty'],
        'accessories_harga' => $prt->harga_dealer_user
      ];
    }
    foreach ($dokumen as $acc) {
      $spk_dokumen[] = [
        'no_spk' => $no_spk,
        'nama_file' => $acc['key'],
        'file' => $acc['path'],
        'path' => $acc['path'],
        'ket' => $acc['key'],
        'key' => $acc['key'],
      ];
      if (strtolower($acc['key']) == 'ktp') {
        $insert['file_foto'] = $acc['path'];
      }
      if (strtolower($acc['key']) == 'kk') {
        $insert['file_kk'] = $acc['path'];
      }
    }
    foreach ($apparel as $acc) {
      $fp = ['id_part_int' => $acc['apparel_id']];
      $prt = $this->m_sm->getParts($fp);
      cek_referensi($prt, 'Apparel ID');
      $prt = $prt->row();

      $spk_apparel[] = [
        'no_spk' => $no_spk,
        'apparel_id' => $acc['apparel_id'],
        'apparel_harga' => $prt->harga_dealer_user,
        'apparel_qty' => $acc['apparel_qty'],
      ];
    }

    // $tes = [
    //   'insert' => $insert,
    // ];
    // send_json($tes);
    $upd_prospek = [
      'status_prospek'     => 'Deal',
      'updated_at'         => waktu_full(),
      'updated_by'         => $this->login->id_user,
      'rencana_pembayaran' => strtolower($jenis_beli),
      'status_aktifitas'   => 'Completed',
      'no_kk'              => $post['no_kk'],
      'no_npwp'            => $this->input->post('npwp'),
      'email'              => $this->input->post('email'),
      'tgl_lahir'          => $this->input->post('birth_date'),
      'nama_konsumen'      => $post['name'],
      'no_ktp'             => $post['no_ktp'],
      'alamat'             => $post['address_ktp'],
      'no_telp'            => $post['phone'],
    ];
    $this->db->query("SET FOREIGN_KEY_CHECKS=0");
    $this->db->trans_begin();
    $this->db->update('tr_prospek', $upd_prospek, ['id_prospek' => $prp->id_prospek]);
    if ($this->input->post('discount') > 0) {
      $fcd = [
        'id_dealer' => $this->login->id_dealer,
        'id_prospek' => $prp->id_prospek,
      ];
      $cek_diskon = $this->m_diskon->getPengajuanDiskon($fcd);

      $filter = [
        'id_user'            => $this->login->id_user,
        'id_dealer'          => $insert['id_dealer'],
        'id_tipe_kendaraan'  => $insert['id_tipe_kendaraan'],
        'id_warna'           => $insert['id_warna'],
        'diskon'             => $post['discount'],
        'id_prospek'         => $prp->id_prospek,
        'id_karyawan_dealer' => $prp->id_karyawan_dealer,
        'no_spk'             => $no_spk,
        'jenis_beli'         => $insert['jenis_beli']
      ];
      if ($cek_diskon->num_rows() > 0) {
        $cek_diskon = $cek_diskon->row();
        if ($cek_diskon->status == 'Waiting Approval Disc') {
          $this->m_diskon->updateDiskon($filter);
        }
      } else {
        $status_diskon = $this->m_diskon->setDiskon($filter);
      }
      if (isset($status_diskon)) {
        if ($status_diskon=='Approved Disc') {
          $insert['diskon']=$post['discount'];
        }
      }
    }
    $this->db->insert('tr_spk', $insert);
    if (isset($spk_accessories)) {
      $this->db->insert_batch('tr_spk_accessories', $spk_accessories);
    }
    if (isset($spk_apparel)) {
      $this->db->insert_batch('tr_spk_apparel', $spk_apparel);
    }
    if (isset($spk_dokumen)) {
      $this->db->insert_batch('tr_spk_file', $spk_dokumen);
    }
    if (isset($upd_penerimaan)) {
      $this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $no_mesin]);
    }
    if (isset($po_indent)) {
      $this->db->insert('tr_po_dealer', $po_indent);
    }
    if (isset($po_indent_detail)) {
      $this->db->insert('tr_po_dealer_detail', $po_indent_detail);
    }
    if (isset($ins_indent)) {
      $this->db->insert('tr_po_dealer_indent', $ins_indent);
    }
    if (isset($ins_skema_kredit)) {
      $this->db->insert('tr_skema_kredit', $ins_skema_kredit);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["SPK has been created"];
      $f_spk = ['id_dealer' => $this->login->id_dealer, 'no_spk' => $no_spk];
      $this->db->query("SET FOREIGN_KEY_CHECKS=1");
      $spk = $this->m_spk->getSPKIndividu($f_spk)->row();
      $data = ['id' => (int)$spk->no_spk_int];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function document_create()
  {
    $post = $this->input->post();

    $mandatory = [
      'name' => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/prospek/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $key = strtolower(remove_space($post['name'], '_'));

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '100';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = strtotime(waktu_full()) . '-' . $key;
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/prospek/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = [strip_tags($this->upload->display_errors())];
      send_json(msg_sc_error($msg));
    }
    $response = [
      'status' => 1,
      'data' => [
        'name' => $post['name'],
        'key' => $key,
        'path' => base_url($file)
      ]
    ];
    send_json($response);
  }

  function document_new()
  {
    $post = $this->input->get();

    $mandatory = [
      'name' => 'required',
      'prospek_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/prospek/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $key = strtolower(remove_space($post['name'], '_'));

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '3000';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = strtotime(waktu_full()) . '-' . $key;
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/prospek/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = ['File required'];
      send_json(msg_sc_error($msg));
    }
    $insert = [
      'id_prospek' => $prospek->id_prospek,
      'key' => $key,
      'path' => $file,
      'name' => $post['name'],
    ];

    $this->db->trans_begin();
    $this->db->insert('tr_prospek_dokumen', $insert);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been uploaded'];
      $filter = [
        'id_prospek' => $prospek->id_prospek,
        'id_dealer' => $this->login->id_dealer,
        'key' => $key
      ];
      $data = $this->m_prospek->getProspekDokumen($filter);
      $data = $data->row();
      $data = [
        'id' => $data->id,
        'url' => $data->path,
        'document_key' => $data->key,
        'document_name' => $data->name,
      ];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function document_remove()
  {
    $post = $this->input->post();

    $mandatory = [
      'path' => 'required',
    ];
    cek_mandatory($mandatory, $post);
    if (!delete_file_by_url($post['path'])) {
      $msg = ['File not found'];
      send_json(msg_sc_error($msg));
    }
    $path = explode('uploads',$post['path']);
    $delpath ="uploads".$path[1];
    $this->db->trans_begin();
    $this->db->delete('tr_prospek_dokumen', ['path' => $delpath]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been deleted'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function document_update()
  {
    $post = $this->input->post();

    $mandatory = [
      'prospek_id' => 'required',
      'key' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    $filter = [
      'id_prospek' => $prospek->id_prospek,
      'id_dealer' => $this->login->id_dealer,
      'key' => $post['key']
    ];
    $get_data = $this->m_prospek->getProspekDokumen($filter);
    if ($get_data->num_rows() > 0) {
      $prp_doc = $get_data->row();
      delete_file_by_url($prp_doc->path);
    }
    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/prospek/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '100';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = strtotime(waktu_full()) . '-' . $post['key'];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/prospek/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = [strip_tags($this->upload->display_errors())];
      send_json(msg_sc_error($msg));
    }
    // send_json(['status'=>0,'message'=>['cek'],'dt'=>$this->upload]);

    if (isset($prp_doc)) {
      $update = [
        'path' => $file
      ];
    } else {
      $insert = [
        'id_prospek' => $prospek->id_prospek,
        'key' => $post['key'],
        'name' => $post['key'],
        'path' => $file
      ];
    }

    $this->db->trans_begin();
    if (isset($update)) {
      $cond = [
        'key' => $post['key'],
        'id_prospek' => $prospek->id_prospek
      ];
      $this->db->update('tr_prospek_dokumen', $update, $cond);
    } else {
      $this->db->insert('tr_prospek_dokumen', $insert);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been updated'];
      $path = ['path' => base_url($file)];
      $response = msg_sc_success($path, $msg);
      send_json($response);
    }
  }

  function document_upload()
  {
    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/prospek/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '100';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = strtotime(waktu_full());
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/prospek/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = [strip_tags($this->upload->display_errors())];
      send_json(msg_sc_error($msg));
    }
    $response = [
      'status' => 1,
      'message' => ['Document has been uploaded'],
      'data' => [
        'path' => base_url($file)
      ]
    ];
    send_json($response);
  }

  function follow_up_create()
  {
    $post = $this->input->post();

    $mandatory = [
      'prospek_id' => 'required',
      'date' => 'required',
      'activity_id' => 'required',
      'description' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    $flt = [
      'id_prospek' => $prospek->id_prospek,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count'
    ];
    $cek_fol = $this->m_prospek->getProspekFollowUp($flt)->row()->count + 1;

    $insert = [
      'id_prospek' => $prospek->id_prospek,
      'tgl_fol_up' => $post['date'],
      'metode_fol_up' => $post['activity_id'],
      'keterangan' => $post['description']
    ];
    $f_k = ['id_karyawan_dealer' => $prospek->id_karyawan_dealer, 'select' => 'all'];
    $kry = $this->m_sc->getKaryawan($f_k)->row();

    $ins_activity = [
      'id_dealer'              => $this->login->id_dealer,
      'parent_id'              => $prospek->id_prospek,
      'id_karyawan_dealer_int' => $kry->id_karyawan_dealer_int,
      'name'                   => $prospek->nama_konsumen,
      'info'                   => 'Follow Up Prospek ' . $cek_fol,
      'id_kategori_activity'   => 1,
      'tanggal'                => $post['date'],
      'jam'                    => '',
      'status'                 => 'new',
      'created_at'             => waktu_full(),
      'created_by'            => $this->login->id_user
    ];

    // send_json($insert);

    $this->db->trans_begin();
    $this->db->insert('tr_prospek_fol_up', $insert);
    $this->m_activity->insertActivity($ins_activity);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Follow Up has been created'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function follow_up_list()
  {
    $get = $this->input->get();
    $mandatory = [
      'prospek_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);
    $filter = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'return' => 'for_service_concept',
      'order' => "order by pf.id DESC"
    ];
    $prospek = $this->m_prospek->getProspekFollowUp($filter);
    // send_json((object)$prospek);
    send_json(msg_sc_success($prospek, NULL));
  }

  function follow_up_submit()
  {
    $post = $this->input->post();

    $mandatory = [
      'follow_up_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter = [
      'id' => $post['follow_up_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspekFollowUp($filter);
    cek_referensi($get_data, 'Follow Up ID');
    $fl = $get_data->row();
    $f_act = [
      'id_dealer' => $this->login->id_dealer,
      'parent_id' => $fl->id_prospek,
      'tanggal' => $fl->tgl_fol_up,
      'jam' => $fl->waktu_fol_up,
    ];
    $cek_activity = $this->m_activity->getActivity($f_act);
    $update_prp = [
      'check_date' => get_ymd()
    ];
    if ($cek_activity->num_rows() > 0) {
      $act = $cek_activity->row();
      $update_activity = [
        'id' => $act->id,
        'check_date' => get_ymd(),
        'status' => 'selesai'

      ];
    }

    $this->db->trans_begin();
    $this->db->update('tr_prospek_fol_up', $update_prp, ['id' => $post['follow_up_id']]);
    if (isset($update_activity)) {
      $this->db->update('tr_sc_sales_activity', $update_activity, ['id' => $update_activity['id']]);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Follow Up has been submited'];
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }

  function info()
  {
    $this->load->model('m_dms');

    $karyawan = sc_user(['username' => $this->login->username])->row();
    $filter_actual_prospek = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'status_prospek_not' => 'Deal',
      'bulan_prospek' => get_ym(),
      'select' => 'count'
    ];

    $filter_target_prospek = [
      'honda_id' => $karyawan->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_prospek',
    ];

    $filter_belum_fu = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_prospek' => get_ym(),
      'status_prospek_not' => 'Deal',
      // 'status_prospek_tidak_sama' => 'Deal',
      'select' => 'count',
      'belum_fu' => true
    ];

    $filter_prospek = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'status_prospek_not' => 'Deal',
      'bulan_prospek' => get_ym(),
      'select' => 'count',
      'sudah_fu' => true,
    ];
    // $actual_prospek = $this->m_prospek->getProspek($filter_actual_prospek)->row()->count;
    // $target = $this->m_dms->getH1TargetManagement($filter_target_prospek)->row()->sum_prospek;
    // $prospek = [
    //   'actual'   => (int)$actual_prospek,
    //   'target'   => (int)$target == 0 ? 1 : (int)$target,
    //   'sudah_fu'   => (int)$this->m_prospek->getProspek($filter_prospek)->row()->count,
    //   'belum_fu'   => (int)$this->m_prospek->getProspek($filter_belum_fu)->row()->count
    // ];

    $prospek = $this->m_prospek->getProspekActivity($karyawan->id_karyawan_dealer, $this->login, $karyawan->honda_id);

    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $prospek
    ];
    send_json($result);
  }

  function no_deal()
  {
    $post = $this->input->post();
    $mandatory = [
      'prospek_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    if($prospek->leads_id !=''){
      $msg = ['Gagal! Silahkan update status prospek di NMS'];
      send_json(msg_sc_error($msg));
    }

    $update = [
      'status_prospek' => 'Not Deal',
      'id_reasons' => $post['master_alasan_not_deal_id'],
      'keterangan_not_deal' => $post['master_alasan_not_deal_id'] == 5 ? $post['description'] : NULL,
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user
    ];
    // $tes = [
    //   'update' => $update,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_prospek', $update, ['id_prospek' => $prospek->id_prospek]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Prospek has been cancelled"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function status_deal()
  {
    $post = $this->input->post();
    $mandatory = [
      'prospek_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();

    //cek validasi data
    if (
      $prospek->no_kk != "" AND
      $prospek->agama != "" AND
      $prospek->id_kelurahan != "" AND
      $prospek->tempat_lahir != "" AND
      $prospek->tgl_lahir != "" AND
      $prospek->sumber_prospek != "" AND
      $prospek->rencana_pembayaran != "" AND
      $prospek->pekerjaan != ""
       ) {

      if($prospek->input_from =='crm'){
        $msg = ['Gagal! Data leads ini mohon diupdate lewat seeds'];
        send_json(msg_sc_error($msg));
      }else{
        $update = [
          'status_prospek' => 'Deal',
          'updated_at' => waktu_full(),
          'updated_by' => $this->login->id_user
        ];
        $this->db->trans_begin();
        $this->db->update('tr_prospek', $update, ['id_prospek' => $prospek->id_prospek]);
        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
          $msg = ['Terjadi kesalahan'];
          send_json(msg_sc_error($msg));
        } else {
          $this->db->trans_commit();
          $msg = ["Prospek has been deal"];
          send_json(msg_sc_success(NULL, $msg));
        }
      }
    } else {
        $msg = ['Data masih ada yg kosong silahkan lengkapi kembali di seeds'];
        send_json(msg_sc_error($msg));
    }

  }

  function product_detail()
  {
    $get = $this->input->get();
    $mandatory = [
      'prospek_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_data = $this->m_prospek->getProspekUnitDetail($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $row  = $get_data->row();
    $filter_prospek['select'] = 'detail_unit';
    $get_item = $this->m_prospek->getProspekUnitDetail($filter_prospek)->result();
    foreach ($get_item as $itm) {
      $flt = [
        'id_dealer' => $this->login->id_dealer,
        'id_tipe_kendaraan' => $itm->id_tipe_kendaraan,
        'id_warna' => $itm->id_warna,
        'select' => 'count'
      ];
      $prospek_spk = $this->_prospek_spk($flt);
      $filter = [
        'id_tipe_kendaraan' => $itm->id_tipe_kendaraan,
        'id_warna' => $itm->id_warna,
        'id_dealer' => $this->login->id_dealer,
      ];
      $available   = $this->m_stok->GetReadyStock($filter);
      $item[] = [
        'id'             => (int)$itm->id_item_int,
        'unit_id'        => (int)$itm->id_tipe_kendaraan_int,
        'model_id'       => (int)$itm->id_warna_int,
        'accessories_id' => 0,
        'apparel_id'     => 0,
        'image'          => '',
        'name'           => (string)$itm->tipe_ahm,
        'code'           => (string)$itm->id_item,
        'price'          => (int)$row->price_unit,
        'prospek_spk'    => (int)$prospek_spk,
        'available'      => (int)$available,
        'color'          => (string)$itm->warna,
        'stock'          => 0,
        'qty'            => 0,
        'type'           => 'Unit'
      ];
    }
    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_acc = $this->m_prospek->getProspekAccessories($filter_prospek)->result();
    foreach ($get_acc as $acc) {
      $item[] = [
        'id'             => $acc->id_part,
        'unit_id'        => 0,
        'model_id'       => 0,
        'accessories_id' => $acc->id_part,
        'apparel_id'     => 0,
        'image'          => '',
        'name'           => $acc->nama_part,
        'code'           => $acc->id_part,
        'price'          => $acc->accessories_harga,
        'prospek_spk'    => 0,
        'available'      => 0,
        'color'          => 0,
        'stock'          => 0,
        'qty'            => $acc->accessories_qty,
        'type'           => 'Accessories'
      ];
    }

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_app = $this->m_prospek->getProspekApparel($filter_prospek)->result();
    foreach ($get_app as $app) {
      $item[] = [
        'id'             => $app->id_part,
        'unit_id'        => 0,
        'model_id'       => 0,
        'accessories_id' => 0,
        'apparel_id'     => $app->id_part,
        'image'          => '',
        'name'           => $app->nama_part,
        'code'           => $app->id_part,
        'price'          => $app->apparel_harga,
        'prospek_spk'    => 0,
        'available'      => 0,
        'color'          => 0,
        'stock'          => 0,
        'qty'            => $app->apparel_qty,
        'type'           => 'Apparel'
      ];
    }

    $result_data = [
      'name' => $row->tipe_ahm,
      'price_unit' => (int)$row->price_unit,
      'total_accessories' => (int)$row->total_accessories,
      'price_accessories' => (int)$row->price_accessories,
      'total_apparel' => (int)$row->total_apparel,
      'price_apparel' => (int)$row->price_apparel,
      'grand_total' => (int)$row->grand_total,
      'item' => $item
    ];
    send_json(msg_sc_success($result_data, NULL));
  }

  function product_update()
  {
    $post = $this->input->post();
    $mandatory = [
      'prospek_id'        => 'required',
      'unit_model_id'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();
    $id_prospek = $prospek->id_prospek;
    $filter['id_item_int'] = $post['unit_model_id'];
    $item = $this->m_unit->getItem($filter);
    if ($item->num_rows() > 0) {
      $id_warna = $item->row()->id_warna;
      $id_tipe_kendaraan = $item->row()->id_tipe_kendaraan;
    } else {
      $msg = ['Unit Model ID not found'];
      send_json(msg_sc_error($msg));
    }
    $update = [
      'id_warna' => $id_warna,
      'id_tipe_kendaraan' => $id_tipe_kendaraan,
      'updated_at'            => waktu_full(),
      'updated_by'            => $this->login->id_user
    ];

    $accessories = json_decode($post['accessories'], true);
    $apparel = json_decode($post['apparel'], true);
    $total_accessories = 0;
    $price_accessories = 0;
    foreach ($accessories as $acc) {
      $fp = ['id_part_int' => $acc['accessories_id']];
      $prt = $this->m_sm->getParts($fp);
      cek_referensi($prt, 'Accessories ID');
      $prt = $prt->row();
      $prp_accessories[] = [
        'id_prospek' => $id_prospek,
        'accessories_id' => $acc['accessories_id'],
        'accessories_harga' => $prt->harga_dealer_user,
        'accessories_qty' => $acc['accessories_qty']
      ];
      $sum = $prt->harga_dealer_user * $acc['accessories_qty'];
      $price_accessories += $sum;
      $total_accessories += $acc['accessories_qty'];
    }
    $total_apparel = 0;
    $price_apparel = 0;
    foreach ($apparel as $acc) {
      $fp = ['id_part_int' => $acc['apparel_id']];
      $prt = $this->m_sm->getParts($fp);
      cek_referensi($prt, 'Apparel ID');
      $prt = $prt->row();

      $prp_apparel[] = [
        'id_prospek' => $id_prospek,
        'apparel_id' => $acc['apparel_id'],
        'apparel_harga' => $prt->harga_dealer_user,
        'apparel_qty' => $acc['apparel_qty']
      ];

      $sum = $prt->harga_dealer_user * $acc['apparel_qty'];
      $price_apparel += $sum;
      $total_apparel += $acc['apparel_qty'];
    }

    $params = ['id_warna' => $id_warna, 'id_tipe_kendaraan' => $id_tipe_kendaraan];
    $price_unit = $this->m_prospek->cek_bbn($params);
    $update['price_unit'] = $price_unit;
    $update['total_accessories'] = $total_accessories;
    $update['price_accessories'] = $price_accessories;
    $update['total_apparel'] = $total_apparel;
    $update['price_apparel'] = $price_apparel;
    $update['grand_total'] = $price_apparel + $total_accessories + $price_unit;
    // $tes = [
    //   'update' => $update,
    //   'accessories' => $prp_accessories,
    //   'apparel' => $prp_apparel,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_prospek', $update, ['id_prospek' => $id_prospek]);
    $this->db->delete('tr_prospek_accessories', ['id_prospek' => $id_prospek]);
    $this->db->delete('tr_prospek_apparel', ['id_prospek' => $id_prospek]);

    if (isset($prp_accessories)) {
      $this->db->insert_batch('tr_prospek_accessories', $prp_accessories);
    }
    if (isset($prp_apparel)) {
      $this->db->insert_batch('tr_prospek_apparel', $prp_apparel);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Product has been updated"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function index()
  {
    $get = $this->input->get();
    $get['status_prospek'] = $this->input->get('status');
    $get['id_dealer'] = $this->login->id_dealer;
    $karyawan = sc_user(['username' => $this->login->username])->row();
    // send_json($karyawan);
    $get['id_karyawan_dealer_in'] = "'$karyawan->id_karyawan_dealer'";
    $get['select'] = 'show_prospek_mobile';
    $get['status_prospek_not'] = 'deal';
    $get['order'] = ['field' => 'tr_prospek.created_at', 'order' => 'desc'];
    $get['bulan_prospek'] = get_ym();
    $res_ = $this->m_prospek->getProspek($get);
    $res = [];
    foreach ($res_->result() as $rs) {
      $filter_folup = [
        'id_prospek' => $rs->id_prospek,
        'id_dealer' => $get['id_dealer'],
        'check_date_null' => true,
        'order' => 'ORDER BY id DESC'
      ];
      $fol_up = $this->m_prospek->getProspekFollowUp($filter_folup);
      if ($fol_up->num_rows() > 0) {
        $fol = $fol_up->row();
        $fol_up = $fol->check_date == NULL ? 'N/A' : tgl_indo($fol_up->row()->tgl_fol_up);
      } else {
        $fol_up = 'N/A';
      }
      $res[] = [
        'id' => (int)$rs->id,
        'image' => image_karyawan($rs->image, $rs->jenis_kelamin),
        'name' => $rs->name,
        'produk_name' => $rs->produk_name,
        'status' => $rs->status,
        'assigned' => (bool)$rs->assigned,
        'follow_up' => (string)$fol_up
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }

  function update_tes_kendaraan()
  {
    $post = $this->input->post();
    $mandatory = [
      'prospek_id'        => 'required',
      'date'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();
    $id_prospek = $prospek->id_prospek;
    $update = [
      'tgl_tes_kendaraan' => $post['date'],
      'updated_at'            => waktu_full(),
      'updated_by'            => $this->login->id_user
    ];

    // $tes = [
    //   'update' => $update,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_prospek', $update, ['id_prospek' => $id_prospek]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Data has been updated"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function sales_program()
  {
    $get = $this->input->get();
    $get['id_dealer'] = $this->login->id_dealer;
    if (isset($get['prospek_id'])) {
      $fp = [
        'id_dealer' => $get['id_dealer'],
        'id_prospek_int' => $get['prospek_id']
      ];
      $prp = $this->m_prospek->getProspekUnitDetail($fp);
      if ($prp->num_rows() > 0) {
        $prp = $prp->row();
        $get['type_unit_id'] = $prp->id_tipe_kendaraan_int;
        $get['id_warna'] = $prp->id_warna;
      }
    }
    // send_json($get);
    $filter = [
      'cek_periode' => tanggal(),
      'type_unit_id' => $prp->id_tipe_kendaraan
    ];
    $get_program = $this->m_home->getSalesProgram($filter)->result();
    foreach ($get_program as $rs) {
      $res[] = [
        'id'         => (int)$rs->id,
        'juklak_id'  => $rs->juklak_id,
        'code'       => $rs->code,
        'name'       => $rs->name,
        'price'      => (int)$rs->price,
        'date_start' => $rs->date_start,
        'date_end'   => $rs->date_end,
        'default'    => $rs->otomatis == 1 ? true : false,
        'unit'       => $rs->unit,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => isset($res) ? $res : []
    ];
    send_json($result);
  }

  function detail()
  {
    $get = $this->input->get();
    $mandatory = ['prospek_id' => 'required'];
    // send_json($get);
    cek_mandatory($mandatory, $get);

    $filter_prospek = [
      'id_prospek_int' => $get['prospek_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'all'
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prp = $get_data->row();
    // send_json($prp);
    $filter_folup = [
      'id_prospek' => $prp->id_prospek,
      'id_dealer' => $this->login->id_dealer,
      'order' => 'ORDER BY id DESC',
    ];
    $folup = $this->m_prospek->getProspekFollowUp($filter_folup);
    if ($folup->num_rows() > 0) {
      $fol = $folup->row();
      $latest_folup = (object)[
        'id' => (int)$fol->id,
        'name' => 'Follow Up Prospek ' . $folup->num_rows(),
        'activity' => $fol->metode_fol_up_text,
        'commited_date' => mediumdate_indo($fol->tgl_fol_up, ' '),
        'check_date' => $fol->check_date == NULL ? '' : mediumdate_indo($fol->check_date, ' '),
        'description' => $fol->keterangan
      ];
    } else {
      $latest_folup = (object)[
        'id' => 0,
        'name' => '',
        'activity' => '',
        'check_date' => '',
        'commited_date' => '',
        'description' => ''
      ];
    }
    $step = [
      [
        'id' => 1,
        'name' => 'PROSPEK',
        'info' => $prp->id_prospek,
        'status' => 'active'
      ],
      [
        'id' => 2,
        'name' => 'SPK',
        'info' => '',
        'status' => 'not_active'
      ],
      [
        'id' => 3,
        'name' => 'Sales',
        'info' => '',
        'status' => 'not_active'
      ]
    ];

    $fol_create = true;
    if (isset($latest_folup)) {
      if ($latest_folup->check_date == '' && $latest_folup->id != '') {
        $fol_create = false;
      }
    }

    $f_info = [
      'id_dealer' => $this->login->id_dealer,
      'parent_id' => $prp->id_prospek_int,
      'check_date_null' => true,
    ];

    $get_fol = $this->m_activity->getActivity($f_info);
    $fol_info = '';
    if ($get_fol->num_rows() > 0) {
      $fol = $get_fol->row();
      $selisih = selisihWaktu(get_ymd(), $fol->tanggal);
      $cek = strtotime(get_ymd()) > strtotime($fol->tanggal);
      if ($cek == true) {
        $fol_info = $selisih . ' hari telah lewat follow up';
      } else {
        $fol_info = $selisih . 'hari lagi follow up';
      }
    }
    $result = [
      'status' => $prp->status_prospek,
      'customer_image' => image_karyawan($prp->customer_image, $prp->jenis_kelamin),
      'customer_name' => $prp->nama_konsumen,
      'customer_phone' => $prp->no_hp == NULL ? '' : $prp->no_hp,
      'step' => $step,
      'follow_up_create' => $fol_create,
      'follow_up_info' => $fol_info,
      'follow_up' => isset($latest_folup) ? $latest_folup : (object)[],
      'product' => $prp->tipe_ahm,
      'test_kendaraan' => (string)tgl_indo($prp->tgl_tes_kendaraan),
      'document' => ''
    ];

    $filter = [
      'id_prospek' => $prp->id_prospek
    ];
    $get_doc = $this->m_prospek->getProspekDokumenWajib($filter);
    $document = [];
    foreach ($get_doc->result() as $doc) {
      $document[] = [
        'id' => $doc->id,
        'url' => $doc->path,
        'document_key' => $doc->key,
        'document_name' => $doc->name,
        'is_required' => true,
      ];
    }

    $filter = [
      'id_prospek' => $prp->id_prospek,
      'id_dealer' => $this->login->id_dealer,
      'key_ms_null' => true,
    ];
    $get_doc = $this->m_prospek->getProspekDokumen($filter);
    foreach ($get_doc->result() as $doc) {
      $document[] = [
        'id' => $doc->id,
        'url' => $doc->path,
        'document_key' => $doc->key,
        'document_name' => $doc->name,
        'is_required' => false,
      ];
    }
    $result['document'] = $document;
    send_json(msg_sc_success($result, NULL));
  }

  function update_rencana_pembelian()
  {
    $post = $this->input->post();
    $mandatory = [
      'prospek_id'        => 'required',
      'date'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $filter_prospek = [
      'id_prospek_int' => $post['prospek_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_prospek->getProspek($filter_prospek);
    cek_referensi($get_data, 'Prospek ID');
    $prospek = $get_data->row();
    $id_prospek = $prospek->id_prospek;
    $update = [
      'rencana_pembelian' => $post['date'],
      'updated_at'            => waktu_full(),
      'updated_by'            => $this->login->id_user
    ];

    // $tes = [
    //   'update' => $update,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_prospek', $update, ['id_prospek' => $id_prospek]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ["Data has been updated"];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function _prospek_spk($filter)
  {
    $ym = get_ym();
    $where = "WHERE 
                prp.id_dealer='{$filter['id_dealer']}' 
                AND LEFT(tgl_prospek,7)='$ym' 
                AND IFNULL(so.tgl_cetak_invoice,'')!=''
                AND prp.status_prospek!='Deal'
                AND spk.status_spk NOT IN('rejected','canceled')
              ";

    if (isset($filter['id_tipe_kendaraan'])) {
      $where.=" AND spk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }

    if (isset($filter['id_warna'])) {
      $where.=" AND spk.id_warna='{$filter['id_warna']}'";
    }

    return $this->db->query("SELECT COUNT(id_prospek) c 
                            FROM tr_prospek prp
                            LEFT JOIN tr_spk spk ON spk.id_customer=prp.id_customer
                            LEFT JOIN tr_sales_order so ON so.no_spk=spk.no_spk
                            $where")->row()->c;
  }
}
