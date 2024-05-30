<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

class Sales extends CI_Controller
{
  private $login;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('m_h1_dealer_spk', 'm_spk');
    $this->load->model('m_h1_dealer_cdb', 'm_cdb');
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_sc_activity', 'm_activity');
    $this->load->model('m_dms');
    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function customer_detail()
  {
    $get = $this->input->get();
    $mandatory = ['sales_id' => 'required'];
    cek_mandatory($mandatory, $get);

    $f_so = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $f_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_spk->getSPKIndividu($f_spk);
    $spk = $get_data->row();
    $data['customer'] = [
      'name' => (string)$spk->nama_konsumen,
      'no_ktp' => (string)$spk->no_ktp,
      'email' => (string)$spk->email,
      'phone' => (string)$spk->no_hp,
      'birth_date' => (string)$spk->tgl_lahir,
      'address' => (string)$spk->alamat,
      'no_kk' => (string)$spk->no_kk,
      'address_ktp' => (string)$spk->alamat2,
    ];
    $data['pajak'] = [
      'kode_ppn' => (int)$spk->kode_ppn,
      'spp' => (string)$spk->spp,
      'faktur_pajak' => (string)$spk->faktur_pajak,
      'npwp' => (string)$spk->npwp,
      'payment_method_id' => strtolower($spk->jenis_beli == 'kredit') ? 2 : 1,
      'payment_method_name' => (string)$spk->jenis_beli,
      'dp' => (int)$spk->dp_stor,
      'master_tenor_id' => (int)$spk->tenor,
      'master_tenor_value' => (int)$spk->tenor,
      'angsuran' => (int)$spk->angsuran,
      'bpkb_stnk' => $spk->bpkb_stnk == 1 ? true : false,
    ];
    $data['bpkb_stnk'] = [
      'name' => (string)$spk->nama_bpkb,
      'phone' => (string)$spk->bpkb_stnk_phone,
      'birth_place' => (string)$spk->bpkb_stnk_birth_place,
      'birth_date' => (string)$spk->bpkb_stnk_birth_date,
      'address' => (string)$spk->alamat_ktp_bpkb,
      'postal_code' => (string)$spk->bpkb_stnk_postal_code,
      'jabatan' => (string)$spk->bpkb_stnk_jabatan,
    ];
    send_json(msg_sc_success($data, NULL));
  }

  function detail()
  {
    $get = $this->input->get();
    $mandatory = ['sales_id' => 'required'];
    // send_json($get);
    cek_mandatory($mandatory, $get);

    //Cek SO
    $f_so = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    //Cek SPK
    $f_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
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
        'info' => '',
        'status' => 'finish'
      ],
      [
        'id' => 3,
        'name' => 'Sales',
        'info' => '',
        'status' => 'active'
      ]
    ];

    $filter_folup = [
      'no_spk' => $spk->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'order' => 'ORDER BY id DESC',
    ];
    $folup = $this->m_so->getSOFollowUp($filter_folup);
    $latest_folup = '';
    if ($folup->num_rows() > 0) {
      $fol = $folup->row();
      $latest_folup = [
        'id' => (int)$fol->id,
        'name' => 'Follow Up Sales' . $folup->num_rows(),
        'activity' => (string)$fol->metode_fol_up_text,
        'commited_date' => mediumdate_indo($fol->tgl_fol_up, ' '),
        'check_date' => mediumdate_indo($fol->check_date, ' ') ?: '',
        'description' => $fol->description
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
    $product_disc = true;
    if ($spk->program_umum == '' || $spk->program_umum == NULL) {
      $product_disc = false;
    }

    $filter = ['no_spk' => $spk->no_spk, 'id_dealer' => $this->login->id_dealer];
    $bayar = $this->m_bayar->getDealerInvoiceReceipt($filter)->num_rows() > 0;
    $res_riwayat = $bayar > 0 ? 'Ada' : 'Belum Ada';
    // foreach ($bayar->result() as $rs) {
    //   $res = [
    //     'id' => $rs->id_kwitansi,
    //     'price' => $rs->amount,
    //     'payment_method_id' => (int)'',
    //     'payment_method_name' => $rs->cara_bayar,
    //     'date' => $rs->tgl_pembayaran,
    //     'leasing' => $spk->id_finance_company
    //   ];
    //   $res_riwayat[] = $res;
    // }

    $f_doc = [
      'no_spk' => $spk->no_spk,
      'id_dealer' => $this->login->id_dealer,
    ];
    $res_document = [];
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
      'key_ms_doc_spk_null' => true
    ];
    $res_doc = $this->m_spk->getSPKDokumen($f_doc);
    foreach ($res_doc->result() as $rd) {
      $res_document[] = [
        'id' => (int)$rd->id,
        'url' => $rd->path == NULL ? '' : $rd->path,
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
      'parent_id' => $so->id_sales_order,
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

    $stnk = $this->m_so->cekProsesSTNK($so->no_mesin);
    $penerimaan = [
      [
        'id' => 1,
        'name' => 'Unit',
        'date' => $so->tgl_terima_unit_ke_konsumen,
        'status' => $so->status_delivery
      ],
      [
        'id' => 2,
        'name' => 'STNK',
        'date' => $stnk['tgl_stnk'],
        'status' => $stnk['status_stnk']
      ],
    ];

    $result = [
      'status' => $spk->status_spk,
      'customer_image' => image_karyawan($spk->customer_image, $spk->jenis_kelamin),
      'customer_name' => $spk->nama_konsumen,
      'customer_phone' => $spk->no_hp,
      'step' => $step,
      'follow_up_create' => $fol_create,
      'follow_up_info' => $fol_info,
      'follow_up' => $latest_folup,
      'product_info' => $spk->tipe_ahm,
      'product_disc' => $product_disc,
      'estimasi_kedatangan' => $so->tgl_pengiriman,
      'penerimaan' => $penerimaan,
      'document' => $res_document,
      'spk_pdf' => (string)$spk->document_spk,
      'riwayat_pembayaran' => $res_riwayat,
      'formulir_cdb_stnk' => $this->m_spk->cekFormulirCDBSTNK($spk->no_spk),
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function follow_up_create()
  {
    $post = $this->input->post();

    $mandatory = [
      'sales_id' => 'required',
      'date' => 'required',
      'activity_id' => 'required',
      'description' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter = ['id' => $post['activity_id']];
    $act = $this->m_master->getMetodeFollowUp($filter)->row();
    $insert = [
      'id_sales_order' => $so->id_sales_order,
      'tgl_fol_up' => $post['date'],
      'activity_id' => $post['activity_id'],
      'activity' => $act->name,
      'description' => $post['description']
    ];

    $kry = sc_user(['username' => $this->login->username])->row();

    $filter = [
      'id_dealer' => $this->login->id_dealer,
      'id_sales_order' => $so->id_sales_order,
      'select' => 'count',
    ];
    $cek_fol = $this->m_so->getSOFollowUp($filter)->row()->count;
    $cek_fol += 1;
    $ins_activity = [
      'id_dealer'              => $this->login->id_dealer,
      'parent_id'              => $so->id_sales_order,
      'id_karyawan_dealer_int' => $kry->id_karyawan_dealer_int,
      'name'                   => $so->nama_konsumen,
      'info'                   => 'Follow Up Sales ' . $cek_fol,
      'id_kategori_activity'   => 3,
      'tanggal'                => $post['date'],
      'jam'                    => '',
      'status'                 => 'new',
      'created_at'             => waktu_full(),
      'created_by'            => $this->login->id_user
    ];


    $this->db->trans_begin();
    $this->db->insert('tr_sales_order_fol_up', $insert);
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
      'sales_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    //Cek SO
    $f_so = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();
    //Get Data Follow Up
    $filter = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer,
      'id_customer' => $so->id_customer,
      'no_spk' => $so->no_spk,
      'return' => 'for_service_concept',
    ];
    $so = $this->m_so->getSOFollowUp($filter);
    send_json(msg_sc_success($so, NULL));
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
    $get_data = $this->m_so->getSOFollowUp($filter);
    cek_referensi($get_data, 'Follow Up ID');
    $so = $get_data->row();

    $update = [
      'check_date' => get_ymd()
    ];
    $f_act = [
      'id_dealer' => $this->login->id_dealer,
      'parent_id' => $so->id_sales_order,
      'tanggal' => $so->tgl_fol_up,
      'jam' => $so->waktu_fol_up,
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

    $this->db->trans_begin();
    $this->db->update('tr_sales_order_fol_up', $update, ['id' => $post['follow_up_id']]);
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
      'sales_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $f_so = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter_spk = [
      'no_spk' => $so->no_spk,
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

    $result['customer'] = [
      'document_form' => (string)$spk->document_spk_form,
      'document' => (string)$spk->document_spk,
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
      'twiter' => (string)$spk->twitter,
      'instagram' => (string)$spk->instagram,
      'youtube' => (string)$spk->youtube,
      'hobby_id' => (string)$spk->id_hobi,
      'hobby_name' => (string)$spk->hobi,
      'correspondence' => $spk->correspondence == 1 ? true : false,
      'address' => (string)$spk->correspondence_address,
      'provinsi_id' => (int)$spk->id_provinsi_corr,
      'provinsi_name' => (string)$spk->provinsi_corr,
      'kabupaten_kota_id' => (int)$spk->id_kabupaten_corr,
      'kabupaten_kota_name' => (string)$spk->kabupaten_corr,
      'kecamatan_id' => (int)$spk->id_kecamatan_corr,
      'kecamatan_name' => (string)$spk->kecamatan_corr,
      'kelurahan_id' => (int)$spk->id_kelurahan_corr,
      'kelurahan_name' => (string)$spk->kelurahan_corr,
      'kode_pos' => (string)$spk->kode_pos_corr,
    ];
    $result['job_info'] = [
      'pekerjaan_id' => (string)$spk->pekerjaan_id,
      'pekerjaan_name' => (string)$spk->pekerjaan,
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
      'sumber_prospek_id' => (int)$spk->sumber_prospek_id,
      'sumber_prospek_name' => (int)$spk->sumber_prospek_name,
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

  function update_kedatangan()
  {
    $post = $this->input->get();
    $mandatory = [
      'sales_id'        => 'required',
      'date'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();
    $id_sales_order = $so->id_sales_order;
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
    $this->db->update('tr_sales_order', $update, ['id_sales_order' => $id_sales_order]);
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

  function document_update()
  {
    $post = $this->input->get();
    $mandatory = [
      'sales_id'        => 'required',
      'key'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $f_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($f_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter = [
      'no_spk' => $spk->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'key' => $post['key']
    ];
    $get_data = $this->m_spk->getSPKDokumen($filter);
    cek_referensi($get_data, 'Key File');
    $spk_doc = $get_data->row();

    delete_file_by_url($spk_doc->path);

    $this->load->library('upload');
    $ym = date('Y/m');
    $path = "./uploads/spk/" . $ym;
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
    $config['file_name']     = strtotime(waktu_full()) . '-' . $post['key'];
    $this->upload->initialize($config);
    if ($this->upload->do_upload('file')) {
      $file     = 'uploads/spk/' . $ym . '/' . $this->upload->file_name;
    } else {
      $msg = ['File required'];
      send_json(msg_sc_error($msg));
    }

    $update = [
      'path' => base_url($file)
    ];
    // send_json($update);

    $this->db->trans_begin();
    $cond = [
      'key' => $post['key'],
      'no_spk' => $spk->no_spk
    ];
    $this->db->update('tr_spk_file', $update, $cond);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Document has been updated'];
      $response = msg_sc_success($update, $msg);
      send_json($response);
    }
  }

  function document_new()
  {
    $post = $this->input->get();
    $mandatory = [
      'sales_id'        => 'required',
      'name'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter_spk = [
      'no_spk' => $so->no_spk,
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
      'no_spk' => $spk->no_spk,
      'key' => $key,
      'path' => base_url($file),
      'file' => base_url($file),
      'nama_file' => $post['name'],
    ];
    // send_json($insert);
    $this->db->trans_begin();
    $this->db->insert('tr_spk_file', $insert);
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
        'url' => $doc->path,
        'document_key' => $doc->key,
        'document_name' => $doc->nama_file,
        'is_required' => false
      ];
      send_json(msg_sc_success($data, $msg));
    }
  }

  function document_remove()
  {
    $post = $this->input->post();
    $mandatory = [
      'sales_id'        => 'required',
      'path'              => 'required'
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();
    // send_json($so);
    $filter_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    if (!delete_file_by_url($post['path'])) {
      $msg = ['File not found'];
      send_json(msg_sc_error($msg));
    }
    $this->db->trans_begin();
    $this->db->delete('tr_spk_file', ['path' => $post['path'], 'no_spk' => $spk->no_spk]);
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

    $f_so = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count',
    ];
    $actual_so = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    $filter_target_sales = [
      'honda_id' => $karyawan->honda_id,
      'id_dealer' => $this->login->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_sales'
    ];
    $target_spk = $this->m_dms->getH1TargetManagement($filter_target_sales)->row()->sum_sales;

    $f_so = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count',
      'jenis_beli' => 'cash'
    ];
    $so_tunai = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    $f_so = [
      'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'bulan_so' => get_ym(),
      'select' => 'count',
      'jenis_beli' => 'kredit'
    ];
    $so_kredit = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    // $target_spk = 0;
    $target_spk = $target_spk == 0 ? 1 : $target_spk;
    $data = [
      'actual' => (int)$actual_so,
      'target' => (int)$target_spk,
      'tunai' => (int)$so_tunai,
      'kredit' => (int)$so_kredit,
    ];
    send_json(msg_sc_success($data, NULL));
  }

  function payment_history()
  {
    $get = $this->input->get();
    $mandatory = [
      'sales_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    //Cek SO
    $f_so = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter_spk = [
      'no_spk' => $so->no_spk,
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
        'id' => $rs->id_kwitansi_int,
        'price' => $rs->amount,
        'payment_method_id' => (int)'',
        'payment_method_name' => $rs->cara_bayar,
        'date' => $rs->tgl_pembayaran,
        'leasing' => (string)$spk->id_finance_company
      ];
      $result[] = $res;
    }
    send_json(msg_sc_success($result, NULL));
  }

  function product_detail()
  {
    $get = $this->input->get();
    $mandatory = [
      'sales_id' => 'required'
    ];
    cek_mandatory($mandatory, $get);

    //Cek SO
    $f_so = [
      'id_sales_order_int' => $get['sales_id'],
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_spk->getSPK($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk  = $get_data->row();

    $get_item = $this->m_spk->getSPKIndividuProduct($filter_spk)->result();
    foreach ($get_item as $itm) {
      $item[] = [
        'id'             => (int)$itm->id_item_int,
        'unit_id'        => (int)$itm->id_tipe_kendaraan_int,
        'model_id'       => (int)$itm->id_warna_int,
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
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_acc = $this->m_spk->getSPKIndividuAccessories($f_acc)->result();
    $tot_acc = 0;
    $price_acc = 0;
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
      $sub_tot = $acc->accessories_qty * $acc->accessories_harga;
      $tot_acc += $acc->accessories_qty;
      $price_acc += $sub_tot;
    }

    $f_app = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_app = $this->m_spk->getSPKIndividuApparel($f_app)->result();
    $tot_app = 0;
    $price_app = 0;
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

      $sub_tot = $app->apparel_qty * $app->apparel_harga;
      $tot_app += $app->apparel_qty;
      $price_app += $sub_tot;
    }

    $sales_program = [];
    if (($so->program_umum == NULL || $so->program_umum == '') == false) {
      $f_sp = ['id_program_md' => $so->program_umum];
      $sp = $this->m_spk->getSalesProgram($f_sp)->row();
      $sales_program[] = [
        'id' => (int)$sp->id_sales_program,
        'name' => $sp->judul_kegiatan,
        'price' => $so->voucher_1 + $so->voucher_2,
        'default' => true
      ];
    }
    if (($so->program_gabungan == NULL || $so->program_gabungan == '') == false) {
      $f_sp = ['id_program_md' => $so->program_gabungan];
      $sp = $this->m_spk->getSalesProgram($f_sp)->row();
      $sales_program[] = [
        'id' => (int)$sp->id_sales_program,
        'name' => $sp->judul_kegiatan,
        'price' => $so->voucher_1 + $so->voucher_2,
        'default' => true
      ];
    }

    $result_data = [
      'name' => $itm->tipe_ahm,
      'price_unit' => $spk->harga_tunai,
      'discount' => $itm->diskon,
      'price_sales_program' => $spk->diskon - $itm->diskon,
      'max_sales_program' => 0,
      'price_unit_discount' => $spk->total_bayar,
      'total_accessories' => $tot_acc,
      'price_accessories' => $price_acc,
      'total_apparel' => $tot_app,
      'price_apparel' => $price_app,
      'grand_total' => ($spk->total_bayar + $price_app + $price_acc),
      'product' => $item,
      'sales_program' => $sales_program,
    ];
    send_json(msg_sc_success($result_data, NULL));
  }

  function index()
  {
    $get = $this->input->get();
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $get['status_on_sc'] = $this->input->get('status');
    $get['id_dealer'] = $this->login->id_dealer;
    $get['id_karyawan_dealer'] = "$karyawan->id_karyawan_dealer";
    $get['bulan_so'] = get_ym();
    $res_ = $this->m_so->getSalesOrderIndividu($get);
    $res = [];
    foreach ($res_->result() as $rs) {
      $f_doc = [
        'no_spk' => $rs->no_spk,
        'id_dealer' => $this->login->id_dealer,
        'select' => 'count'
      ];
      $tot_doc = $this->m_spk->getSPKDokumen($f_doc)->row()->count;
      $res[] = [
        'id' => (int)$rs->id_sales_order_int,
        'image' => image_karyawan($rs->customer_image, $rs->jenis_kelamin),
        'name' => $rs->nama_konsumen,
        'produk_name' => $rs->tipe_ahm,
        'status' => $rs->status_on_sc,
        'document_completed' => (int)$tot_doc,
        'document_total' => (int)$tot_doc,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }
  function target_unit()
  {
    $get = $this->input->get();
    $karyawan = sc_user(['username' => $this->login->username])->row();

    $filter_target_sales = [
      'honda_id'    => $karyawan->honda_id,
      'id_dealer'   => $this->login->id_dealer,
      'page'        => $get['page'],
      'type_id'     => isset($get['type_id']) ? $get['type_id'] : '',
      'tahun_motor' => isset($get['year']) ? $get['year']      : '',
      'tahun'       => get_y(),
      'bulan'       => get_m(),
    ];
    $res_ = $this->m_dms->getH1TargetManagement($filter_target_sales);
    $res = [];
    foreach ($res_->result() as $rs) {
      $f_so = [
        'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
        'id_dealer' => $this->login->id_dealer,
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'bulan_so' => get_ym(),
        'select' => 'count',
      ];
      $actual_so = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

      $params = ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan];
      $harga = $this->m_prospek->cek_bbn($params);
      $res[] = [
        'id' => $rs->id,
        'name' => $rs->tipe_ahm,
        'code' => $rs->id_tipe_kendaraan,
        'image' => '',
        'price' => $harga,
        'actual' => $actual_so,
        'target' => $rs->target,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }

  function customer_update()
  {
    $post = $this->input->post();
    $mandatory = [
      'sales_id' => 'required',
      'customer_name' => 'required',
      'customer_phone' => 'required',
      'customer_ktp' => 'required',
      'customer_address' => 'required',
      'customer_latitude' => 'required',
      'customer_longitude' => 'required',
      'office_address' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();
    $update_spk = [
      'nama_konsumen' => $post['customer_name'],
      'no_ktp' => $post['customer_ktp'],
      'no_hp' => $post['customer_phone'],
      'latitude' => $post['customer_latitude'],
      'longitude' => $post['customer_longitude'],
      'alamat' => $post['customer_address'],
      'pekerjaan' => $post['pekerjaan_id'],
      'kode_ppn' => $post['kode_ppn'],
      'spp' => $post['spp'],
      'faktur_pajak' => $post['faktur'],
      'npwp' => $post['npwp'],
      'jenis_beli' => $post['payment_method_id'] == 1 ? 'Cash' : 'Kredit',
      'updated_at' => waktu_full(),
      'updated_by' => $this->login->id_user
    ];

    if ($post['office_address'] != '') {
      $upd_prospek['alamat_kantor'] = $post['office_address'];
    }
    if ($post['office_phone'] != '') {
      $upd_prospek['no_telp_kantor'] = $post['office_phone'];
    }
    // send_json($update);
    $this->db->trans_begin();
    $this->db->update('tr_spk', $update_spk, ['no_spk' => $so->no_spk]);
    if (isset($upd_prospek)) {
      $upd_prospek['updated_at'] = waktu_full();
      $upd_prospek['updated_by'] = $this->login->id_user;
      $this->db->update('tr_prospek', $upd_prospek, ['id_customer' => $spk->id_customer]);
    }
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

  function formulir_cdb_stnk_update()
  {
    $post = $this->input->post();

    $mandatory = [
      'sales_id' => 'required',
    ];
    cek_mandatory($mandatory, $post);

    $f_so = [
      'id_sales_order_int' => $post['sales_id'],
      'id_dealer' => $this->login->id_dealer,
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    cek_referensi($get_data, 'Sales ID');
    $so = $get_data->row();

    $filter_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter_spk);
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $this->m_cdb->updateCDBSTNK($post, $spk);
  }
  function target_accessories()
  {
    $get = $this->input->get();
    $karyawan = sc_user(['username' => $this->login->username])->row();

    $filter_target_sales = [
      'honda_id'    => $karyawan->honda_id,
      'id_dealer'   => $this->login->id_dealer,
      'page'        => $get['page'],
      'type_id'     => isset($get['type_id']) ? $get['type_id'] : '',
      'tahun_motor' => isset($get['year']) ? $get['year']      : '',
      'tahun'       => get_y(),
      'bulan'       => get_m(),
    ];
    $res = [];
    // $res_ = $this->m_dms->getH1TargetManagement($filter_target_sales);
    // foreach ($res_->result() as $rs) {
    //   $f_so = [
    //     'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
    //     'id_dealer' => $this->login->id_dealer,
    //     'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
    //     'bulan_so' => get_ym(),
    //     'select' => 'count',
    //   ];
    //   $actual_so = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    //   $params = ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan];
    //   $harga = $this->m_prospek->cek_bbn($params);
    //   $res[] = [
    //     'id' => $rs->id,
    //     'name' => $rs->tipe_ahm,
    //     'code' => $rs->id_tipe_kendaraan,
    //     'image' => '',
    //     'price' => $harga,
    //     'actual' => $actual_so,
    //     'target' => $rs->target,
    //   ];
    // }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }
  function target_apparel()
  {
    $get = $this->input->get();
    $karyawan = sc_user(['username' => $this->login->username])->row();

    $filter_target_sales = [
      'honda_id'    => $karyawan->honda_id,
      'id_dealer'   => $this->login->id_dealer,
      'page'        => $get['page'],
      'type_id'     => isset($get['type_id']) ? $get['type_id'] : '',
      'tahun_motor' => isset($get['year']) ? $get['year']      : '',
      'tahun'       => get_y(),
      'bulan'       => get_m(),
    ];
    $res = [];
    // $res_ = $this->m_dms->getH1TargetManagement($filter_target_sales);
    // foreach ($res_->result() as $rs) {
    //   $f_so = [
    //     'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
    //     'id_dealer' => $this->login->id_dealer,
    //     'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
    //     'bulan_so' => get_ym(),
    //     'select' => 'count',
    //   ];
    //   $actual_so = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

    //   $params = ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan];
    //   $harga = $this->m_prospek->cek_bbn($params);
    //   $res[] = [
    //     'id' => $rs->id,
    //     'name' => $rs->tipe_ahm,
    //     'code' => $rs->id_tipe_kendaraan,
    //     'image' => '',
    //     'price' => $harga,
    //     'actual' => $actual_so,
    //     'target' => $rs->target,
    //   ];
    // }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }
}
