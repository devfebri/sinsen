<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Customer extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h2_api', 'm_h2_api');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function find_vehicle()
  {
    $get = $this->input->get();
    // send_json($get);
    $mandatory = [
      'number' => 'required',
      'number_type' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $where_v2 = "where 1=1";

    if ($get['number_type'] == 'police_no') {
      $f['no_polisi']         = $get['number'];
      $f_h1['no_polisi_sc']   = $get['number'];
      $msg = ['No. Polisi : ' . $get['number'] . ' tidak ditemukan !'];
      $no_pol = str_replace(' ', '', $f_h1['no_polisi_sc']);
      $where_v2 .=" AND replace(a.no_pol,' ','') = '$no_pol' ";          

      if (strlen($get['number'])<8) {
        send_json(msg_sc_error(['Minimal karakter untuk No. Polisi adalah 8 karakter !']));
      }
    } elseif ($get['number_type'] == 'frame_no') {
      $f['no_rangka']         = $get['number'];
      $f_h1['no_rangka_sc']   = $get['number'];
      $msg = ['No. Rangka : ' . $get['number'] . ' tidak ditemukan !'];

      $temp_rangka = strtoupper($f_h1['no_rangka_sc']);
      if(strlen($temp_rangka) == 17){
        $originalStr = $temp_rangka;
        $prefix = substr($originalStr, 0, 3);
        $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
      }

      $where_v2 .=" AND a.no_rangka = '$temp_rangka' "; 
      
      if (strlen($get['number'])!=17) {
        send_json(msg_sc_error(['Jumlah karakter untuk No. Rangka adalah 17 karakter !']));
      }
    } elseif ($get['number_type'] == 'engine_no') {
      $msg = ['No. Mesin : ' . $get['number'] . ' tidak ditemukan !'];
      $f['no_mesin']          = $get['number'];
      $f_h1['no_mesin_sc']    = $get['number'];
      $nosin = $f_h1['no_mesin_sc'];
      $where_v2 .=" AND a.no_mesin = '$nosin' "; 

      if (strlen($get['number'])!=12) {
        send_json(msg_sc_error(['Jumlah karakter untuk No. Mesin adalah 12 karakter !']));
      }
    }
    $cek_h23 = $this->m_h2_api->getCustomerH23($f);
    $result = [];
    if ($cek_h23->num_rows()>0) {
      $ch23 = $cek_h23->row();
      $customer = [
        'id' => (int)$ch23->id_customer_int,
        'name' => $ch23->nama_customer,
        'email' => (string)$ch23->email,
        'phone' => $ch23->no_hp,
        'address' => $ch23->alamat,
        'district' => $ch23->kecamatan,
        'sub_district' => $ch23->kelurahan . '-' . $ch23->id_kelurahan,
        'facebook' => (string)$ch23->facebook,
        'twitter' => (string)$ch23->twitter,
        'instagram' => (string)$ch23->instagram,
      ];
      $result =  [
        'police_no' => (string)$ch23->no_polisi,
        'frame_no' => $ch23->no_rangka,
        'engine_no' => $ch23->no_mesin,
        'vehicle_type_name' => $ch23->id_tipe_kendaraan,
        'vehicle_type_id' => $ch23->id_tipe_kendaraan_int,
        'vehicle_type_color' => $ch23->only_warna,
        'vehicle_color_id' => $ch23->id_warna_int,
        'year' => $ch23->tahun_produksi,
        'customer' => $customer,
      ];
      send_json(msg_sc_success($result, NULL));
    } else {
      // send_json(msg_sc_error($msg));

      $get_tipe_customer = $this->db->query("
        select a.no_mesin , a.no_rangka , a.no_pol , replace(no_pol,' ','') ,  case when length(b.id_sales_order) >22 then 'Group Sales' else 'Regular' end tipe_customer
        from tr_entry_stnk a
        join tr_faktur_stnk_detail b on a.no_mesin = b.no_mesin 
        $where_v2
        group by a.no_mesin 
      ");

      if($get_tipe_customer->num_rows()> 0){
        // getcustomerH1_v2
        $f_h1['jenis_customer_beli'] = $get_tipe_customer->row()->tipe_customer;
        $cek_h1 = $this->m_h2_api->getCustomerH1_v2($f_h1);
      }else{
        send_json(msg_sc_success($result, NULL));

        // getcustomerH1
        // $cek_h1 = $this->m_h2_api->getCustomerH1($f_h1);
      }
      
      // $cek_h1 = $this->m_h2_api->getCustomerH1($f_h1); // nanti di comment
      if ($cek_h1->num_rows() > 0) {
        $ch1 = $cek_h1->row();
        // $ch23 = $cek_h23->row();
        $customer = [
          'id' => 1,
          'name' => $ch1->nama_customer,
          'email' => (string)$ch1->email,
          'phone' => $ch1->no_hp,
          'address' => $ch1->alamat,
          'district' => $ch1->kecamatan,
          'sub_district' => $ch1->kelurahan . '-' . $ch1->id_kelurahan,
          'facebook' => (string)$ch1->facebook,
          'twitter' => (string)$ch1->twitter,
          'instagram' => (string)$ch1->instagram,
        ];
        $result =  [
          'police_no' => (string)$ch1->no_polisi,
          'frame_no' => $ch1->no_rangka,
          'engine_no' => $ch1->no_mesin,
          'vehicle_type_name' => $ch1->id_tipe_kendaraan,
          'vehicle_type_color' => $ch1->warna,
          'year' => $ch1->tahun_produksi,
          'customer' => $customer,
        ];
      }
      send_json(msg_sc_success($result, NULL));
    }
  }
}
