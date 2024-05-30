<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Spk extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_cdb', 'm_cdb');
    $this->load->model('m_h1_dealer_diskon', 'm_diskon');
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_master_unit', 'm_unit');
    $this->load->model('m_dms');
    $this->load->model('M_sc_sp_home', 'm_home');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function cancel()
  {
    $get = $this->input->post();
    $mandatory = ['spk_id' => 'required'];
    cek_mandatory($mandatory, $get);

    $filter_spk = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer,
      // 'status_spk' => 'input'
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    if (($spk->status_spk == 'input' || $spk->status_spk == 'booking') == false) {
      $msg = ["SPK tidak dapat dibatalkan. Status SPK Sekarang :" . $spk->status_spk];
      send_json(msg_sc_error($msg));
    }
    $cek_indent = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_spk='$spk->no_spk'");
    if ($cek_indent->num_rows() > 0) {
      $indent = $cek_indent->row();
      $msg = ["SPK tidak dapat dibatalkan karena Inden no : $indent->id_indent masih aktif"];
      send_json(msg_sc_error($msg));
    }
    $update = [
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user,
      'id_reasons' => $this->input->post('master_alasan_not_deal_id'),
      'status_spk' => 'canceled'
    ];

    $this->db->trans_begin();
    $this->db->update('tr_spk', $update, ['no_spk_int' => $get['spk_id']]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Cancel SPK berhasil'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function customer_detail()
  {
    $get = $this->input->get();
    $mandatory = ['spk_id' => 'required'];
    cek_mandatory($mandatory, $get);

    $filter_spk = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $data['customer'] = [
      'name' => $spk->nama_konsumen,
      'no_ktp' => $spk->no_ktp,
      'email' => $spk->email,
      'phone' => $spk->no_hp,
      'birth_date' => $spk->tgl_lahir,
      'address' => $spk->alamat2,
      'no_kk' => $spk->no_kk,
      'address_ktp' => $spk->alamat,
    ];
    $data['pajak'] = [
      'kode_ppn' => $spk->kode_ppn,
      'spp' => $spk->spp,
      'faktur_pajak' => $spk->faktur_pajak,
      'npwp' => $spk->npwp,
      'payment_method_id' => strtolower($spk->jenis_beli == 'kredit') ? 2 : 1,
      'payment_method_name' => $spk->jenis_beli,
      'dp' => $spk->dp_stor,
      'master_tenor_id' => $spk->tenor,
      'master_tenor_value' => $spk->tenor,
      'angsuran' => $spk->angsuran,
      'bpkb_stnk' => $spk->bpkb_stnk == 1 ? true : false,
    ];
    $data['bpkb_stnk'] = [
      'name' => $spk->nama_bpkb,
      'phone' => $spk->bpkb_stnk_phone,
      'birth_place' => $spk->bpkb_stnk_birth_place,
      'birth_date' => $spk->bpkb_stnk_birth_date,
      'address' => $spk->alamat_ktp_bpkb,
      'postal_code' => $spk->bpkb_stnk_postal_code,
      'jabatan' => $spk->bpkb_stnk_jabatan,
      'no_ktp' => $spk->no_ktp_bpkb,
      'email' => $spk->email,
    ];
    send_json(msg_sc_success($data, NULL));
  }
  function customer_update()
  {
    $post = $this->input->post();
    $mandatory = [
      'spk_id' => 'required',
      'name' => 'required',
      'email' => 'required',
      'phone' => 'required',
      'birth_date' => 'required',
      'address' => 'required',
      'no_kk' => 'required',
      'address_ktp' => 'required',
      'payment_method_id' => 'required',
      'bpkb_stnk' => 'required',
      'npwp' => 'required',
      // 'bpkb_stnk_jabatan' => 'required',
    ];
    cek_mandatory($mandatory, $post);
    // send_json($mandatory);
    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $update = [
      'nama_konsumen' => $post['name'],
      'no_ktp'        => $post['no_ktp'],
      'email'         => $post['email'],
      'no_hp'         => $post['phone'],
      'tgl_lahir'     => $post['birth_date'],
      'alamat'        => $post['address'],
      'no_kk'         => $post['no_kk'],
      'alamat2'       => $post['address_ktp'],
      'kode_ppn'      => $this->input->post('kode_ppn'),
      'spp'           => $post['spp'],
      'faktur_pajak'  => $post['faktur'],
      'npwp'          => $post['npwp'],
      'jenis_beli' => $post['payment_method_id'] == 1 ? 'Cash' : 'Kredit',
      'dp_stor' => $post['dp'] == '' ? 0 : $post['dp'],
      'tenor' => $post['tenor'] == '' ? 0 : $post['tenor'],
      'angsuran' => $post['angsuran'] == '' ? 0 : $post['angsuran'],
      'bpkb_stnk' => $post['bpkb_stnk'] == 'true' ? 1 : 0,
      'nama_bpkb' => $this->input->post('bpkb_stnk_name') ?: $post['name'],
      'bpkb_stnk_phone' => $this->input->post('bpkb_stnk_phone') ?: $post['phone'],
      'bpkb_stnk_birth_place' => $this->input->post('bpkb_stnk_birth_place'),
      'bpkb_stnk_birth_date' => $this->input->post('bpkb_stnk_birth_date') ?: $post['birth_date'],
      'alamat_ktp_bpkb' => $this->input->post('bpkb_stnk_address') ?: $post['address_ktp'],
      'bpkb_stnk_postal_code' => $this->input->post('bpkb_stnk_postal_code'),
      'bpkb_stnk_jabatan' => $this->input->post('bpkb_stnk_jabatan'),
      'no_ktp_bpkb' => $this->input->post('bpkb_stnk_no_ktp') ?: $post['no_ktp'],
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user
    ];
    // send_json($update);
    $this->db->trans_begin();
    $this->db->update('tr_spk', $update, ['no_spk_int' => $post['spk_id']]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Data has been updated'];
      send_json(msg_sc_success(NULL, $msg));
    }
  }

  function document_new()
  {
    $post = $this->input->post();

    $mandatory = [
      'name' => 'required',
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/spk/" . $ym;
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
      $file     = 'uploads/spk/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = ['File required'];
      send_json(msg_sc_error($msg));
    }
    $insert = [
      'no_spk'    => $spk->no_spk,
      'key'       => $key,
      'path'      => $file,
      'file'      => base_url($file),
      'nama_file' => $post['name'],
    ];
    // send_json($insert);
    if (strtolower($key) == 'ktp') {
      $upd_spk['file_foto'] = $file;
    }
    if (strtolower($key) == 'kk') {
      $upd_spk['file_kk'] = $file;
    }
    $this->db->trans_begin();
    $this->db->insert('tr_spk_file', $insert);
    if (isset($upd_spk)) {
      $this->db->update('tr_spk', $upd_spk, ['no_spk_int' => $post['spk_id']]);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been uploaded'];
      $filter = [
        'no_spk' => $spk->no_spk,
        'id_dealer' => $this->login->id_dealer,
        'key' => $key
      ];
      $data = $this->m_spk->getSPKDokumen($filter);
      $doc = $data->row();
      $data = [
        'id' => $doc->id,
        'url' => $doc->full_path,
        'document_key' => $doc->key,
        'document_name' => $doc->nama_file,
        'is_required' => false
      ];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function document_update()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
      'key' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter = [
      'no_spk' => $spk->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'key' => $post['key']
    ];
    $get_data = $this->m_spk->getSPKDokumen($filter);
    // cek_referensi($get_data, 'Key File');
    $spk_doc = $get_data->row();

    // delete_file_by_url($spk_doc->path);

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/spk/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
    if ($post['key'] == 'ktp' || $post['key'] == 'kk') {
      $path = "./assets/panel/files";
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|gif';
    $config['max_size']      = '3000';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = strtotime(waktu_full()) . '-' . $post['key'];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/spk/' . $ym . '/' . $this->upload->file_name;
      if ($post['key'] == 'ktp' || $post['key'] == 'kk') {
        $file     = 'assets/panel/files/' . $this->upload->file_name;
      }
    } else {
      $msg = ['File required'];
      send_json(msg_sc_error($msg));
    }

    if ($spk_doc == NULL) {
      $insert = [
        'no_spk' => $spk->no_spk,
        'key' => $post['key'],
        'path' => base_url($file),
        'file' => base_url($file)
      ];
      $resp_update = [
        'path' => base_url($file),
        'file' => base_url($file)
      ];
    } else {
      $update = [
        'path' => base_url($file),
        'file' => base_url($file)
      ];
      $resp_update = $update;
    }
    // send_json($update);

    $this->db->trans_begin();
    $cond = [
      'key' => $post['key'],
      'no_spk' => $spk->no_spk
    ];
    if ($post['key'] == 'ktp') {
      $upd_spk = ['file_foto' => $this->upload->file_name];
    } elseif ($post['key'] == 'kk') {
      $upd_spk = ['file_kk' => $this->upload->file_name];
    }

    if (isset($upd_spk)) {
      $cond_spk = ['no_spk' => $spk->no_spk];
      $this->db->update('tr_spk', $upd_spk, $cond_spk);
    }

    if (isset($update)) {
      $this->db->update('tr_spk_file', $update, $cond);
    } else {
      $this->db->insert('tr_spk_file', $insert);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been updated'];
      $response = msg_sc_success($resp_update, $msg);
      send_json($response);
    }
  }

  function document_remove()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
      'path' => 'required',
    ];
    cek_mandatory($mandatory, $post);
    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter_spk['path_or_file'] = $post['path'];
    $get_doc = $this->m_spk->getSPKDokumen($filter_spk);

    $doc = $get_doc->row();
    // send_json($doc);
    if ($doc != NULL) {
      if ($doc->key == 'kk') {
        $update = ['file_kk' => ''];
      } elseif ($doc->key == 'ktp') {
        $update = ['file_foto' => ''];
      }
    }
    if (!delete_file_by_url($post['path'])) {
      $msg = ['File not found'];
      // send_json(msg_sc_error($msg));
    }
    // send_json($update);
    $this->db->trans_begin();
    $cond = ['no_spk' => $spk->no_spk];

    if (isset($update)) {
      $this->db->update('tr_spk', $update, $cond);
    }
    if ($doc != NULL) {
      $this->db->query("DELETE FROM tr_spk_file WHERE path='$doc->path' AND no_spk='$doc->no_spk'");
    }

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

  function follow_up_create()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
      'date' => 'required',
      'activity_id' => 'required',
      'description' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter = ['id' => $post['activity_id']];
    $act = $this->m_master->getMetodeFollowUp($filter)->row();
    $insert = [
      'no_spk' => $spk->no_spk,
      'tgl_fol_up' => $post['date'],
      'activity_id' => $post['activity_id'],
      'activity' => $act->name,
      'description' => $post['description']
    ];

    $kry = sc_user(['username' => $this->login->username])->row();
    $filter = [
      'no_spk_int' => $spk->no_spk_int,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'count',
    ];
    $cek_fol = $this->m_spk->getSPKFollowUp($filter)->row()->count + 1;

    $ins_activity = [
      'id_dealer'              => $this->login->id_dealer,
      'parent_id'              => $spk->no_spk,
      'id_karyawan_dealer_int' => $kry->id_karyawan_dealer_int,
      'name'                   => $spk->nama_konsumen,
      'info'                   => 'Follow Up SPK ' . $cek_fol,
      'id_kategori_activity'   => 2,
      'tanggal'                => $post['date'],
      'jam'                    => '',
      'status'                 => 'new',
      'created_at'             => waktu_full(),
      'created_by'            => $this->login->id_user
    ];

    $this->db->trans_begin();
    $this->db->insert('tr_spk_fol_up', $insert);
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
      'spk_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    //Cek SPK
    $filter_spk = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    //Get Data Follow Up
    $filter = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer,
      'id_customer' => $spk->id_customer,
      'return' => 'for_service_concept',
    ];
    $spk = $this->m_spk->getSPKFollowUp($filter);
    send_json(msg_sc_success($spk, NULL));
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
    $get_data = $this->m_spk->getSPKFollowUp($filter);
    cek_referensi($get_data, 'Follow Up ID');
    $fl = $get_data->row();
    $update = [
      'check_date' => get_ymd()
    ];

    $f_act = [
      'id_dealer' => $this->login->id_dealer,
      'parent_id' => $fl->no_spk,
      'tanggal' => $fl->tgl_fol_up,
      'jam' => $fl->waktu_fol_up,
    ];
    $cek_activity = $this->m_activity->getActivity($f_act);
    if ($cek_activity->num_rows() > 0) {
      $act = $cek_activity->row();
      $update_activity = [
        'id' => $act->id,
        'check_date' => get_ymd(),
        'status' => 'selesai'
      ];
    }
    // send_json($update);

    $this->db->trans_begin();
    $this->db->update('tr_spk_fol_up', $update, ['id' => $post['follow_up_id']]);
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

  function formulir_cdb_stnk()
  {
    $get = $this->input->get();

    $mandatory = [
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $filter_spk = [
      'no_spk_int' => $get['spk_id'],
      'left_join_cdb' => true,
      'left_join_jenis_pembelian' => true,
      'join_wilayah' => true,
      'join_agama' => true,
      'join_status_rumah' => true,
      'join_wilayah_instansi' => true,
      'join_wilayah_correspondence' => true,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'formulir_cdb'
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    // send_json($spk);

    $document_form = '';
    $doc_f = $this->db->get_where('sc_ms_file_cdb', ['id_dealer' => $this->login->id_dealer, 'aktif' => 1])->row();
    if ($doc_f != NULL) {
      $document_form = base_url($doc_f->path);
    }
    $result['customer'] = [
      'document_form' => (string)$document_form,
      'document' => (string)base_url($spk->document_spk),
      'jenis_pembelian_id' => (int)$spk->id_jenis_pembelian,
      'jenis_pembelian_name' => (string)$spk->jenis_pembelian,
      'nik' => (string)$spk->no_ktp,
      'kewarganegaraan' => (int)$spk->jenis_wn_int,
      'kk' => (string)$spk->no_kk,
      'name' => (string)$spk->nama_konsumen,
      'tempat_lahir' => (string)$spk->tempat_lahir,
      'tanggal_lahir' => (string)$spk->tgl_lahir,
      'address' => (string)$spk->alamat,
      'provinsi_id' => (int)$spk->id_provinsi,
      'provinsi_name' => (string)$spk->provinsi,
      'kabupaten_kota_id' => (int)$spk->id_kabupaten,
      'kabupaten_kota_name' => (string)$spk->kabupaten,
      'kecamatan_id' => (int)$spk->id_kecamatan,
      'kecamatan_name' => (string)$spk->kecamatan,
      'kelurahan_id' => (int)$spk->id_kelurahan,
      'kelurahan_name' => (string)$spk->kelurahan,
      'agama_id' => (int)$spk->id_agama,
      'agama_name' => (string)$spk->agama,
      'status_rumah_id' => (int)$spk->status_rumah_id,
      'status_rumah_name' => (string)$spk->status_rumah,
      'no_telp' => (string)$spk->no_telp,
      'no_hp' => (string)$spk->no_hp,
      'status_kepemilikan_no_hp' => (int)$spk->status_hp,
    ];
    $result['media_social'] = [
      'email' => (string)$spk->email,
      'facebook' => (string)$spk->facebook,
      'twitter' => (string)$spk->twitter,
      'instagram' => (string)$spk->instagram,
      'youtube' => (string)$spk->youtube,
      'hobby_id' => (string)$spk->id_hobi,
      'hobby_name' => (string)$spk->hobi,
      'correspondence' => $spk->alamat_sama == 'Tidak' ? true : false,
      'address' => (string)$spk->correspondence_address,
      'provinsi_id' => (int)$spk->id_provinsi_corr,
      'provinsi_name' => (string)$spk->provinsi_corr,
      'kabupaten_kota_id' => (int)$spk->id_kabupaten_corr,
      'kabupaten_kota_name' => (string)$spk->kabupaten_corr,
      'kecamatan_id' => (int)$spk->id_kecamatan_corr,
      'kecamatan_name' => (string)$spk->kecamatan_corr,
      'kelurahan_id' => (int)$spk->id_kelurahan_corr,
      'kelurahan_name' => (string)$spk->kelurahan_corr,
      'alamat_sama' => (string)$spk->alamat_sama,
      'kode_pos' => (string)$spk->kode_pos_corr,
    ];
    $result['job_info'] = [
      'pekerjaan_id' => (string)$spk->id_sub_pekerjaan,
      'pekerjaan_name' => (string)$spk->sub_pekerjaan,
      'nama_instansi_usaha' => (string)$spk->nama_instansi,
      'address' => (string)$spk->alamat_instansi,
      'provinsi_id' => (int)$spk->id_provinsi_instansi,
      'provinsi_name' => (string)$spk->provinsi_instansi,
      'kabupaten_kota_id' => (int)$spk->id_kabupaten_instansi,
      'kabupaten_kota_name' => (string)$spk->kabupaten_instansi,
      'kecamatan_id' => (int)$spk->id_kecamatan_instansi,
      'kecamatan_name' => (string)$spk->kecamatan_instansi,
      'deskripsi_pekerjaan' => (string)$spk->deskripsi_pekerjaan,
      'pengeluaran_id' => (int)$spk->id_pengeluaran_bulan,
      'pengeluaran_name' => (string)$spk->pengeluaran,
      'pendidikan_id' => (int)$spk->id_pendidikan,
      'pendidikan_name' => (string)$spk->pendidikan,
      'information' => strtolower($spk->sedia_hub) == 'ya' ? true : false,
      'motorcycle_brand_id' => (int)$spk->id_merk_sebelumnya,
      'motorcycle_brand_name' => (string)$spk->merk_sebelumnya,
      'motorcycle_type_id' => (int)$spk->id_jenis_sebelumnya,
      'motorcycle_type_name' => (string)$spk->jenis_sebelumnya,
      'keperluan_pembelian_id' => (int)$spk->id_digunakan,
      'keperluan_pembelian_name' => (string)$spk->digunakan,
      'pengguna_kendaraan_id' => (int)$spk->pengguna_kendaraan_id,
      'pengguna_kendaraan_name' => (string)$spk->pengguna_kendaraan_name,
    ];
    $result['sales_info'] = [
      'sumber_prospek_id' => $spk->sumber_prospek_id,
      'sumber_prospek_name' => $spk->sumber_prospek_name,
      'ref_id' => (string)$spk->refferal_id,
      'ro_bd_id' => (string)$spk->robd_id,
      'jenis_penjualan_id' => (int)$spk->jenis_penjualan_id,
      'jenis_penjualan_name' => (string)$spk->jenis_penjualan_name,
      'leasing_id' => (int)$spk->id_finance_company_int,
      'leasing_name' => (string)$spk->finance_company,
      'dp_awal' => (string)$spk->dp_stor,
      'cicilan' => (int)$spk->cicilan,
      'dealer_code' => (string)$spk->kode_dealer_md,
      'name' => (string)$spk->nama_lengkap,
      'image' => image_karyawan($spk->kry_image, 'laki-laki'),
      'code' => (string)$spk->id_karyawan_dealer,
      'keterangan' => (string)$spk->keterangan
    ];
    $response = msg_sc_success($result, NULL);
    send_json($response);
  }

  function formulir_cdb_stnk_update()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $spk->id_user = $this->login->id_user;
    $this->m_cdb->updateCDBSTNK($post, $spk);
  }

  function formulir_cdb_stnk_document_upload()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_cdb->getCDB($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $cdb = $get_data->row();

    $filter_spk = [
      'id_cdb' => $cdb->id_cdb,
      'document_is_null' => true,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_cdb->getCDB($filter_spk);
    if ($get_data->num_rows() == 0) {
      // $msg = ['Document sudah di upload'];
      // send_json(msg_sc_error($msg));
    }

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/cdb/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|pdf';
    $config['max_size']      = '3000';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = strtotime(waktu_full()) . $cdb->id_cdb;
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/cdb/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = ['File required'];
      send_json(msg_sc_error($msg));
    }

    $update = [
      'document' => $file
    ];

    // send_json($update);
    $this->db->trans_begin();
    $cond = [
      'id_cdb' => $cdb->id_cdb
    ];
    $this->db->update('tr_cdb', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been updated'];
      $send_data['path'] = base_url($file);
      $response = msg_sc_success($send_data, $msg);
      send_json($response);
    }
  }

  function formulir_cdb_stnk_document_remove()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);
    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_cdb->getCDB($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    if (!delete_file_by_url($spk->document)) {
      $msg = ['File not found'];
      send_json(msg_sc_error($msg));
    }
    $this->db->trans_begin();
    $this->db->update('tr_cdb', ['document' => NULL], ['no_spk' => $spk->no_spk]);
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

  function info()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();

    $filter_actual_spk = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_spk' => get_ym(),
      'select' => 'count',
    ];
    $actual_spk = $this->m_spk->getSPK($filter_actual_spk)->row()->count;

    $filter_target_sales = [
      'honda_id' => $karyawan->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_spk'
    ];
    $target_spk = $this->m_dms->getH1TargetManagement($filter_target_sales)->row()->sum_spk;
    $data = ['actual' => (int)$actual_spk, 'target' => (int)$target_spk];
    send_json(msg_sc_success($data, NULL));
  }

  function payment_history()
  {
    $get = $this->input->get();
    $mandatory = ['spk_id' => 'required'];
    cek_mandatory($mandatory, $get);

    $filter_spk = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter = ['no_spk' => $spk->no_spk, 'id_dealer' => $this->login->id_dealer];
    $bayar = $this->m_bayar->getDealerInvoiceReceipt($filter);
    $result = [];
    foreach ($bayar->result() as $rs) {
      $res = [
        'id' => (int)$rs->id_kwitansi_int,
        'price' => (int)$rs->amount,
        'payment_method_id' => (int)$rs->cara_bayar_id,
        'payment_method_name' => $rs->cara_bayar,
        'date' => $rs->tgl_pembayaran,
        'leasing' => $spk->finance_company
      ];
      $result[] = $res;
    }
    send_json(msg_sc_success($result, NULL));
  }

  function product_detail()
  {
    $get = $this->input->get();
    $mandatory = [
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $filter_spk = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_spk->getSPK($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk  = $get_data->row();

    $get_item = $this->m_spk->getSPKIndividuProduct($filter_spk)->result();
    foreach ($get_item as $itm) {
      $item[] = [
        'id'             => $itm->id_item_int,
        'unit_id'        => $itm->id_tipe_kendaraan_int,
        'model_id'       => $itm->id_item_int,
        'accessories_id' => 0,
        'apparel_id'     => 0,
        'image'          => '',
        'name'           => '',
        'code'           => $itm->id_item,
        'price'          => $spk->harga_tunai,
        'prospek_spk'    => 0,
        'available'      => 0,
        'color'          => $itm->warna,
        'stock'          => 0,
        'qty'            => 0,
        'type'           => 'Unit'
      ];
    }

    $f_acc = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_acc = $this->m_spk->getSPKIndividuAccessories($f_acc)->result();
    $tot_acc = 0;
    $price_acc = 0;
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
      $sub_tot = $acc->accessories_qty * $acc->accessories_harga;
      $tot_acc += $acc->accessories_qty;
      $price_acc += $sub_tot;
    }

    $f_app = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_app = $this->m_spk->getSPKIndividuApparel($f_app)->result();
    $tot_app = 0;
    $price_app = 0;
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
        'price'          => $app->apparel_harga,
        'prospek_spk'    => 0,
        'available'      => 0,
        'color'          => 0,
        'stock'          => 0,
        'qty'            => $app->apparel_qty,
        'type'           => 'Apparel'
      ];

      $sub_tot = $app->apparel_qty * $app->apparel_harga;
      $tot_app += $app->apparel_qty;
      $price_app += $sub_tot;
    }


    $get_data = $this->m_spk->getSPKIndividu($filter_spk)->row();
    $sales_program = [];
    if (($get_data->program_umum == NULL || $get_data->program_umum == '') == false) {
      $f_sp = ['id_program_md' => $get_data->program_umum];
      $spu = $this->m_home->getSalesProgram($f_sp)->row();
      $sales_program[] = [
        'id'         => (int)$spu->id,
        'juklak_id'  => $spu->juklak_id,
        'code'       => $spu->code,
        'name'       => $spu->name,
        'price'      => (int)$spu->price,
        'date_start' => $spu->date_start,
        'date_end'   => $spu->date_end,
        'default'    => false,
        'unit'       => $spu->unit,
      ];
    }

    if (($get_data->program_gabungan == NULL || $get_data->program_gabungan == '') == false) {
      $f_sp = ['id_program_md' => $get_data->program_gabungan];
      $spu = $this->m_home->getSalesProgram($f_sp)->row();
      $sales_program[] = [
        'id'         => (int)$spu->id,
        'juklak_id'  => $spu->juklak_id,
        'code'       => $spu->code,
        'name'       => $spu->name,
        'price'      => (int)$spu->price,
        'date_start' => $spu->date_start,
        'date_end'   => $spu->date_end,
        'default'    => false,
        'unit'       => $spu->unit,
      ];
    }

    $result_data = [
      'name' => $itm->tipe_ahm,
      'price_unit' => (int)$spk->harga_tunai,
      'discount' => (int)$itm->diskon,
      'price_sales_program' => $spk->diskon - $itm->diskon,
      'max_sales_program' => 0,
      'price_unit_discount' => (int)$spk->diskon,
      'total_accessories' => $tot_acc,
      'price_accessories' => $price_acc,
      'total_apparel' => $tot_app,
      'price_apparel' => $price_app,
      'grand_total' => ($spk->total_bayar + $price_app + $price_acc),
      'item' => $item,
      'sales_program' => $sales_program
    ];
    send_json(msg_sc_success($result_data, NULL));
  }

  function product_update()
  {
    $post = $this->input->post();
    // send_json($post);
    $id_dealer = $this->login->id_dealer;
    $mandatory = [
      'spk_id'        => 'required',
      'unit_model_id' => 'required',
      'accessories'   => 'required',
      'apparel'       => 'required',
      'sales_program' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $f_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($f_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $no_spk = $spk->no_spk;

    $sales_program = json_decode($post['sales_program'], true);
    $voucher = 0;
    foreach ($sales_program as $key => $sp) {
      $f_s_program = [
        'id_sales_program' => $sp['sales_program_id']
      ];
      $s_program = $this->m_home->getSalesProgram($f_s_program)->row();
      if ($key == 0 && $s_program != null) {
        $program_umum = $s_program->code;
        if (strtolower($spk->jenis_beli) == 'kredit') {
          $voucher_2 = $s_program->price;
          $voucher   = $voucher_2;
        } else {
          $voucher_1 = $s_program->price;
          $voucher   = $voucher_1;
        }
      }
      if ($key == 1 && $s_program != null) {
        $program_gabungan = $s_program->code;
        if (strtolower($spk->jenis_beli) == 'kredit') {
          $voucher_2 = $s_program->price;
          $voucher   = $voucher_2;
        } else {
          $voucher_1 = $s_program->price;
          $voucher   = $voucher_1;
        }
      }
    }

    $accessories = json_decode($post['accessories'], true);
    $apparel = json_decode($post['apparel'], true);

    foreach ($accessories as $acc) {
      $fp = ['id_part_int' => $acc['accessories_id']];
      $prt = $this->m_sm->getParts($fp);
      cek_referensi($prt, 'Accessories ID');
      $prt = $prt->row();

      $spk_acc[] = [
        'no_spk' => $no_spk,
        'accessories_id' => $acc['accessories_id'],
        'accessories_harga' => $prt->harga_dealer_user,
        'accessories_qty' => $acc['accessories_qty']
      ];
    }

    foreach ($apparel as $acc) {
      $fp = ['id_part_int' => $acc['apparel_id']];
      $prt = $this->m_sm->getParts($fp);
      cek_referensi($prt, 'Apparel ID');
      $spk_app[] = [
        'no_spk'        => $no_spk,
        'apparel_id'    => $acc['apparel_id'],
        'apparel_harga' => $prt->harga_dealer_user,
        'apparel_qty' => $acc['apparel_qty']
      ];
    }

    $filter_item['id_item_int'] = $this->input->post('unit_model_id');
    $item = $this->m_unit->getItem($filter_item)->row();

    if ($item != null) {
      $id_tipe_kendaraan = $item->id_tipe_kendaraan;
      $id_warna          = $item->id_warna;
      if ($id_tipe_kendaraan != $spk->id_tipe_kendaraan && $id_warna != $spk->id_warna) {
        $book_no_mesin = $this->m_spk->get_nosin_fifo($id_dealer, $id_tipe_kendaraan, $id_warna);
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
            'id_tipe_kendaraan' => $id_tipe_kendaraan,
            'id_warna'          => $id_warna,
            'nilai_dp'          => $this->input->post('dp'),
            'ket'               => '',
            'qty'               => 1,
            'status'            => 'requested',
            'tgl'               => date('Y-m-d'),
            'created_at'        => waktu_full(),
            'created_by'        => $this->login->id_user
          ];

          $id_po = $this->m_spk->newPO_ID('indent', $id_dealer);
          $item = $this->db->query("SELECT id_item FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
          $id_item = ($item->num_rows() > 0) ? $item->row()->id_item : "";
          $bulan  = date("m");
          $tahun  = date("Y");
          $po_indent = [
            'id_po'               => $id_po,
            'bulan'               => $bulan,
            'tahun'               => $tahun,
            'tgl'                 => date('Y-m-d'),
            'id_dealer'           => $id_dealer,
            'created_at'          => waktu_full(),
            'created_by'          => $this->login->id_user,
            'po_from'             => $no_spk,
            'status'              => 'input',
            'jenis_po'            => 'PO Indent',
            'submission_deadline' => date('Y-m-d'),
            'id_pos_dealer'       => ''
          ];
          $po_indent_detail = [
            'id_po'      => $id_po,
            'id_item'    => $id_item,
            'qty_order'  => 1,
            'qty_po_fix' => 1
          ];
        } else {
          $no_mesin       = $book_no_mesin;
          $insert['status_spk']         = 'booking';
          $upd_penerimaan = ['no_spk' => $no_spk, 'status_on_spk' => $insert['status_spk'], 'booking_at' => waktu_full(), 'booking_by' => $this->login->id_user];
        }
      }
    } else {
      $msg = ['Unit Model ID not found'];
      send_json(msg_sc_error($msg));
    }

    $params = [
      'id_tipe_kendaraan' => $id_tipe_kendaraan,
      'id_warna' => $id_warna,
    ];
    $harga = $this->m_spk->cek_bbn($params);
    // send_json($harga);
    $diskon = $post['discount'] + $voucher;
    $update = [
      'the_road' => 'On The Road',
      'id_tipe_kendaraan' => $id_tipe_kendaraan,
      'biaya_bbn'         => $harga['biaya_bbn'],
      'ppn'               => $harga['ppn'],
      'harga'             => $harga['harga'],
      'harga'             => $harga['harga'],
      'harga_off_road'    => $harga['harga'],
      'harga_on_road'     => $harga['harga_on'],
      'harga_tunai'       => $harga['harga_tunai'],
      'total_bayar'       => $harga['harga_tunai'] - $diskon,
      'id_warna'          => $id_warna,
      'voucher_1'         => isset($voucher_1) ? $voucher_1 : 0,
      'voucher_2'         => isset($voucher_2) ? $voucher_2 : 0,
      'program_umum'      => isset($program_umum) ? $program_umum : NULL,
      'program_gabungan'  => isset($program_gabungan) ? $program_gabungan : NULL,
      'updated_at'        => waktu_full(),
      'updated_by'        => $this->login->id_user
    ];

    if (isset($no_mesin)) {
      $update['no_mesin_spk'] = $no_mesin;
      $update['status_spk']   = 'booking';
    }
    // $tes = [
    //   'update' => $update,
    //   'accessories' => $spk_acc,
    //   'apparel' => $spk_app,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    if ($spk->diskon == 0) {
      $filter = [
        'id_user' => $this->login->id_user,
        'id_dealer' => $spk->id_dealer,
        'id_prospek' => $spk->id_prospek,
        'spk' => $spk,
        'id_tipe_kendaraan' => $id_tipe_kendaraan,
        'id_warna' => $id_warna,
        'diskon' => $post['discount'],
      ];
      $fdiskon = [
        'id_prospek' => $spk->id_prospek,
        'id_dealer' => $id_dealer
      ];
      $cek_diskon = $this->m_diskon->getPengajuanDiskon($fdiskon)->row();
      if ($cek_diskon != null) {
        $filter['id_karyawan_dealer'] = $spk->id_karyawan_dealer;
        $filter['jenis_beli'] = $spk->jenis_beli;
        $this->m_diskon->updateDiskon($filter);
      } else {
        $this->m_diskon->setDiskon($filter);
      }
    } else {
      if ($post['discount'] != $spk->diskon) {
        $msg = ["Gagal. Diskon sudah diapprove dengan nominal " . mata_uang_rp($spk->diskon) . ". Silahkan diedit kembali diskonnya"];
        send_json(msg_sc_error($msg));
      }
    }
    $this->db->update('tr_spk', $update, ['no_spk' => $no_spk]);
    $this->db->delete('tr_spk_accessories', ['no_spk' => $no_spk]);
    $this->db->delete('tr_spk_apparel', ['no_spk' => $no_spk]);

    if (isset($spk_acc)) {
      $this->db->insert_batch('tr_spk_accessories', $spk_acc);
    }
    if (isset($spk_app)) {
      $this->db->insert_batch('tr_spk_apparel', $spk_app);
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
    if (isset($upd_penerimaan)) {
      $reset_penerimaan = ['status_on_spk' => null, 'no_spk' => null, 'booking_at' => null, 'booking_by' => null];
      $this->db->update('tr_penerimaan_unit_dealer_detail', $reset_penerimaan, ['no_spk' => $no_spk]);
      $this->db->update('tr_penerimaan_unit_dealer_detail', $upd_penerimaan, ['no_mesin' => $no_mesin]);
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

  function sales_program()
  {
    $get = $this->input->get();
    $spk = $this->db->query("SELECT id_tipe_kendaraan from tr_spk WHERE no_spk_int={$get['spk_id']}")->row();
    $filter = [
      'cek_periode' => tanggal(),
      'type_unit_id' => $spk->id_tipe_kendaraan
    ];
    $get_program = $this->m_home->getSalesProgram($filter)->result();
    foreach ($get_program as $rs) {
      $default = $this->db->query("SELECT count(no_spk) c FROM tr_spk 
              WHERE no_spk_int={$get['spk_id']} 
              AND (program_umum='$rs->code' OR program_gabungan='$rs->code')")->row()->c;
      $res[] = [
        'id'         => (int)$rs->id,
        'juklak_id'  => $rs->juklak_id,
        'code'       => $rs->code,
        'name'       => $rs->name,
        'price'      => (int)$rs->price,
        'date_start' => $rs->date_start,
        'date_end'   => $rs->date_end,
        'default'    => $default == 1 ? true : false,
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

  function index()
  {
    $get = $this->input->get();
    $get['status_spk'] = isset($get['status']) ? $get['status'] : '';
    $get['id_dealer'] = $this->login->id_dealer;
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get['id_karyawan_dealer'] = "$karyawan->id_karyawan_dealer";
    $get['order'] = ['field' => 'spk.created_at', 'order' => 'desc'];
    $get['status_spk_not_in'] = "'close','canceled'";
    $get['tahun_bulan'] = get_ym();


    // $get['no_spk_int'] = '501';
    $res_ = $this->m_spk->getSPKIndividu($get);
    // send_json($res_->result());
    foreach ($res_->result() as $rs) {
      $filter_folup = [
        'no_spk' => $rs->no_spk,
        'id_dealer' => $get['id_dealer'],
        'order' => 'ORDER BY id DESC'
      ];
      $fol_up = $this->m_spk->getSPKFollowUp($filter_folup);
      if ($fol_up->num_rows() > 0) {
        $fol_up = $fol_up->row()->tgl_fol_up;
      } else {
        $fol_up = '';
      }
      $f_doc = [
        'no_spk' => $rs->no_spk,
        'id_dealer' => $this->login->id_dealer,
        'select' => 'count'
      ];
      $act_doc = $this->m_spk->getSPKDokumen($f_doc)->row()->count;
      $tot_doc = $this->db->get_where('sc_ms_document_spk', ['active' => 1])->num_rows();
      $res[] = [
        'id' => (int)$rs->no_spk_int,
        'image' => image_karyawan($rs->customer_image, $rs->jenis_kelamin),
        'name' => $rs->nama_konsumen,
        'produk_name' => $rs->tipe_ahm,
        'status' => $rs->status_spk == NULL ? '' : $rs->status_spk,
        'total_document' => (int)$tot_doc,
        'assigned_document' => (int)$act_doc,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => isset($res) ? $res : []
    ];
    send_json($result);
  }

  function update_kedatangan()
  {
    $post = $this->input->post();
    $mandatory = [
      'spk_id'        => 'required',
      'date'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($f_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $no_spk = $spk->no_spk;
    $update = [
      'tgl_pengiriman' => $post['date'],
      'updated_at'            => waktu_full(),
      'updated_by'            => $this->login->id_user
    ];

    // $tes = [
    //   'update' => $update,
    // ];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_spk', $update, ['no_spk' => $no_spk]);
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

  function upload_spk()
  {
    $post = $this->input->post();

    $mandatory = [
      'spk_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $filter_spk = [
      'no_spk_int' => $post['spk_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter_spk = [
      'no_spk' => $spk->no_spk,
      'document_is_null' => true,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    // if ($get_data->num_rows() == 0) {
    //   $msg = ['Document sudah di upload'];
    //   send_json(msg_sc_error($msg));
    // }

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/spk/" . $ym;
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    $config['upload_path']   = $path;
    $config['allowed_types'] = 'jpg|png|jpeg|bmp|pdf';
    $config['max_size']      = '3000';
    $config['max_width']     = '30000';
    $config['max_height']    = '30000';
    $config['remove_spaces'] = TRUE;
    $config['overwrite']     = TRUE;
    $config['file_name']     = 'doc-spk-' . strtotime(waktu_full()) . $spk->no_spk_int;
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/spk/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = ['File required'];
      send_json(msg_sc_error($msg));
    }

    $update = [
      'document_spk' => $file
    ];

    // send_json($update);
    $this->db->trans_begin();
    $cond = [
      'no_spk' => $spk->no_spk
    ];
    $this->db->update('tr_spk', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been uploaded'];
      $data['path'] = base_url($update['document_spk']);
      $response = msg_sc_success($data, $msg);
      send_json($response);
    }
  }

  function detail()
  {
    $get = $this->input->get();
    $mandatory = ['spk_id' => 'required'];
    // send_json($get);
    cek_mandatory($mandatory, $get);

    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get['id_karyawan_dealer'] = "$karyawan->id_karyawan_dealer";
    $f_spk = [
      'no_spk_int' => $get['spk_id'],
      'id_dealer' => $this->login->id_dealer,
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($f_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $step = [
      [
        'id' => 1,
        'name' => 'PROSPEK',
        'info' => '',
        'status' => 'finish'
      ],
      [
        'id' => 2,
        'name' => 'SPK',
        'info' => $spk->keterangan == NULL ? $spk->no_spk : $spk->no_spk,
        'status' => 'active'
      ],
      [
        'id' => 3,
        'name' => 'Sales',
        'info' => '',
        'status' => 'not_active'
      ]
    ];

    $filter_folup = [
      'no_spk' => $spk->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'order' => 'ORDER BY id DESC',
    ];
    $folup = $this->m_spk->getSPKFollowUp($filter_folup);
    if ($folup->num_rows() > 0) {
      $fol = $folup->row();
      $latest_folup = [
        'id' => (int)$fol->id,
        'name' => 'Follow Up SPK ' . $folup->num_rows(),
        'activity' => $fol->activity,
        'commited_date' => mediumdate_indo($fol->tgl_fol_up, ' '),
        'check_date' => $fol->check_date == NULL ? '' : mediumdate_indo($fol->check_date, ' '),
        'description' => $fol->description ?: ''
      ];
    } else {
      $latest_folup = [
        'id' => 0,
        'name' => '',
        'activity' => '',
        'check_date' => '',
        'commited_date' => '',
        'description' => ''
      ];
    }
    $product_disc = false;

    if ($spk->diskon > 0) {
      $product_disc = true;
    }

    $filter = ['no_spk' => $spk->no_spk, 'id_dealer' => $this->login->id_dealer];
    $bayar = $this->m_bayar->getDealerInvoiceReceipt($filter);
    $res_riwayat = 'Belum Ada';
    if ($bayar->num_rows() > 0) {
      $res_riwayat = 'Sudah Ada';
    }
    // foreach ($bayar->result() as $rs) {
    //   $res = [
    //     'id' => (int)$rs->id_kwitansi,
    //     'price' => $rs->amount,
    //     'payment_method_id' => 1,
    //     'payment_method_name' => $rs->cara_bayar,
    //     'date' => $rs->tgl_pembayaran,
    //     'leasing' => $spk->id_finance_company == NULL ? '' : $spk->id_finance_company
    //   ];
    //   $res_riwayat[] = $res;
    // }

    $f_doc = [
      'no_spk' => $spk->no_spk,
    ];
    $res_doc = $this->m_spk->getSPKDokumenWajib($f_doc);
    $res_document = array();
    foreach ($res_doc->result() as $rd) {
      $res_document[] = [
        'id' => (int)$rd->id,
        'url' => $rd->path == NULL ? '' : $rd->path,
        'document_key' => $rd->key == NULL ? '' : $rd->key,
        'document_name' => $rd->name,
        'is_required' => true,
      ];
    }

    $f_doc = [
      'no_spk' => $spk->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'key_ms_null' => true
    ];
    $res_doc = $this->m_spk->getSPKDokumen($f_doc);
    foreach ($res_doc->result() as $rd) {
      $res_document[] = [
        'id' => (int)$rd->id,
        'url' => $rd->path == NULL ? '' : $rd->full_path,
        'document_key' => $rd->key == NULL ? '' : $rd->key,
        'document_name' => $rd->nama_file,
        'is_required' => false,
      ];
    }

    $fol_create = true;
    if (isset($latest_folup)) {
      if ($latest_folup['check_date'] == '' && $latest_folup['id'] != '') {
        $fol_create = false;
      }
    }

    $f_info = [
      'id_dealer' => $this->login->id_dealer,
      'parent_id' => $spk->no_spk_int,
      'check_date_null' => true,
    ];

    $get_fol = $this->m_activity->getActivity($f_info);
    $fol_info = 'Follow Up di hari ini';
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
      'status' => $spk->status_spk == NULL ? 'Incomplete' : $spk->status_spk,
      'customer_image' => image_karyawan($spk->customer_image, $spk->jenis_kelamin),
      'customer_name' => $spk->nama_konsumen,
      'customer_phone' => (string)$spk->no_hp,
      'step' => $step,
      'follow_up_create' => $fol_create,
      'follow_up_info' => $fol_info,
      'follow_up' => $latest_folup,
      'product_info' => $spk->tipe_ahm,
      'product_disc' => $product_disc,
      'estimasi_kedatangan' => $spk->tgl_pengiriman == NULL ? '' : $spk->tgl_pengiriman,
      'document' => $res_document,
      'spk_pdf' => $spk->document_spk == NULL ? '' : base_url($spk->document_spk),
      'riwayat_pembayaran' => $res_riwayat,
      'formulir_cdb_stnk' => $this->m_spk->cekFormulirCDBSTNK($spk->no_spk),
    ];
    send_json(msg_sc_success($result, NULL));
  }
}
