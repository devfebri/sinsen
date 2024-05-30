<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Master extends CI_Controller
{
  private $login;

  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_sc_auth', 'm_auth');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_h1_dealer_cdb', 'm_cdb');

    $this->load->helper('sc');
    // middleWareAPI(['id_user_not_null' => true]);
    $this->login = middleWareAPI();
  }
  public function agama()
  {
    $res_ = $this->m_master->getAgama();
    foreach ($res_->result() as $rs) {
      $new[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new
    ];
    send_json($result);
  }
  public function bulan()
  {
    $res_ = $this->m_master->getBulan();
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function calendar()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getCalendar($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function customer()
  {
    $get = $this->input->get();
    $get['id_dealer'] = $this->login->id_dealer;
    $get_data = $this->m_master->getCustomer($get);
    $result = [];
    foreach ($get_data->result() as $rs) {
      $result[] = [
        "id" => (int)$rs->id,
        "name" => $rs->name,
        "image" => (string)$rs->customer_image,
        "ktp" => $rs->ktp,
        "phone" => $rs->phone,
        "address" => $rs->address,
        "latitude" => $rs->latitude,
        "latitude" => '0.0',
        "longitude" => $rs->longitude,
        "longitude" => '0.0',
        "pekerjaan_id" => (int)$rs->pekerjaan_id,
        "pekerjaan_name" => $rs->pekerjaan_name,
        "office_address" => $rs->office_address,
        "office_phone" => $rs->office_phone
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $result
    ];
    send_json($result);
  }
  public function document_prospek()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getDocumentProspek($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function document_spk()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getDocumentSPK($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function document_sales()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getDocumentSales($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }

  public function provinsi()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getProvinsi($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function kabupaten_kota()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getKabupatenKota($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function kecamatan()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getKecamatan($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function kelurahan()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getKelurahan($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function leasing()
  {
    $get = $this->input->get();
    $get['active'] = 1;
    $res_ = $this->m_master->getLeasing($get);
    $data = [];
    foreach ($res_->result() as $dt) {
      $data[] = [
        'id' => (int)$dt->id,
        'name' => $dt->name,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  public function motorcycle_brand()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getMotorCycleBrand($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function motorcycle_type()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getMotorType($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function pekerjaan()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getPekerjaan($get);
    $data = [];
    foreach ($res_->result() as $dt) {
      $data[] = [
        'id' => $dt->id,
        'name' => $dt->name,
        'is_address_instansi' => (int)$dt->is_address_instansi,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  public function pendidikan()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getPendidikan($get);
    $data = [];
    foreach ($res_->result() as $rs) {
      $data[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  public function pengeluaran()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getPengeluaran($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function pengguna_kendaraan()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getPenggunaKendaraan($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function tenor()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getTenor($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function payment_method()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getPaymentMethod($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function jenis_pembelian()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getJenisPembelian($get);
    foreach ($res_->result() as $rs) {
      $res[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }
  public function jenis_penjualan()
  {
    // $get = $this->input->get();
    // $res_ = $this->m_master->getJenisPenjualan($get);
    $res_ = [
      ['id' => 0, 'name' => 'Pilih'],
      ['id' => 1, 'name' => 'Cash'],
      ['id' => 2, 'name' => 'Kredit'],
    ];
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
  public function keperluan_pembelian()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getKeperluanPembelian($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function metode_followup()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getMetodeFollowUp($get);
    foreach ($res_->result() as $rs) {
      $new_res[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }
  public function status_rumah()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getStatusRumah($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  public function setting()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getSetting($get);
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->row()
    ];
    send_json($result);
  }
  public function e_learning()
  {
    $get = $this->input->get();
    $get['aktif'] = 1;
    $res_ = $this->m_master->getElearning($get);
    $new_res = [];
    foreach ($res_->result() as $rs) {
      $new_res[] = ['id' => (int)$rs->id, 'title' => $rs->title];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }
  public function e_learning_detail()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getElearningDetail($get);
    $new_res = [];
    foreach ($res_->result() as $rs) {
      $new_res[] = [
        'id' => (int)$rs->id,
        'parent_id' => (int)$rs->parent_id,
        'title' => $rs->title,
        'content_type' => $rs->content_type,
        'extension' => $rs->extension,
        'url' => base_url($rs->url),
        'player' => base_url($rs->url),
        'size' => $rs->size,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }

  function alasan_reassign()
  {
    $res_ = $this->m_master->getAlasanReassign();
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }
  function sales_baru()
  {
    $get = $this->input->get();
    $mandatory = ['employee_id' => 'required'];
    cek_mandatory($mandatory, $get);

    $f_kry = [
      'id_dealer' => $this->login->id_dealer,
      'join' => 'join_team_structure',
      'id_karyawan_dealer_int_not' => $get['employee_id']
    ];
    $res_ = $this->m_master->getKaryawan($f_kry);
    $data = [];
    foreach ($res_->result() as $rs) {
      $data[] = [
        'id' => (int)$rs->id,
        'name' => $rs->name,
        'image' => image_karyawan($rs->image, 'laki-laki')
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function subject()
  {
    $res_ = $this->m_master->getSubject();
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_->result()
    ];
    send_json($result);
  }

  function customer_ktp()
  {
    $get = $this->input->get();
    $get['id_dealer'] = $this->login->id_dealer;
    $res_ = $this->m_cdb->getCDB($get);
    foreach ($res_->result() as $rs) {
      $data[] = [
        'id' => (int)$rs->id_cdb,
        'name' => $rs->nama_konsumen,
        'ktp' => $rs->no_ktp,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  function follow_up_activity()
  {
    $get = $this->input->get();
    $get['id_dealer'] = $this->login->id_dealer;
    $data = $this->m_master->getFollowUpActivity($get)->result();

    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  function tanggal()
  {
    $get = $this->input->get();
    $data = $this->m_master->getTanggalDalamBulan($get);

    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  public function type_unit()
  {
    $this->load->model('m_master_unit', 'm_unit');
    $get = $this->input->get();
    $res_ = $this->m_unit->getTipeKendaraan($get);
    $result = [];
    foreach ($res_->result() as $rs) {
      $result[] = [
        'id' => (int)$rs->id_tipe_kendaraan_int,
        'name' => $rs->tipe_ahm,
        'code' => $rs->id_tipe_kendaraan . ' - ' . $rs->deskripsi_ahm
      ];
    }
    send_json(msg_sc_success($result, NULL));
  }

  public function alasan_not_deal()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getAlasanNotDeal($get);
    $data = [];
    foreach ($res_->result() as $dt) {
      $data[] = [
        'id' => (int)$dt->id,
        'name' => $dt->name
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  public function sumber_prospek()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getSumberProspek($get);
    $data = [];
    foreach ($res_->result() as $dt) {
      $data[] = [
        'id' => $dt->id,
        'name' => $dt->name
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }
  public function hobby()
  {
    $get = $this->input->get();
    $res_ = $this->m_master->getHobby($get)->result();
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res_
    ];
    send_json($result);
  }
}
