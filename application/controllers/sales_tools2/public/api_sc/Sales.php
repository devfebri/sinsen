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
    $this->load->model('m_h1_dealer_sales_order', 'm_so');
    $this->load->model('m_h1_dealer_cdb', 'm_cdb');
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
    $this->load->model('m_sc_master', 'm_master');
    $this->load->model('m_dms');
    $this->load->model('M_sc_sp_stock', 'm_stock');

    $this->load->helper('sc');
    $this->login = middleWareAPI();
  }

  function detail()
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
    cek_referensi($get_data, 'SPK ID');
    $spk = $get_data->row();

    $filter_folup = [
      'id_sales_order' => $so->id_sales_order,
      'id_dealer' => $this->login->id_dealer,
      'order' => 'ORDER BY id DESC',
    ];
    $folup = $this->m_so->getSOFollowUp($filter_folup);

    $no = $folup->num_rows();
    foreach ($folup->result() as $fol) {
      $follow_up[] = [
        'id' => (int)$fol->id,
        'name' => 'Follow Up ' . $no,
        'activity' => $fol->metode_fol_up_text,
        'check_date' => $fol->check_date == NULL ? '' : mediumdate_indo($fol->check_date, ' '),
        'commited_date' => (string)mediumdate_indo($fol->tgl_fol_up, ' '),
        'description' => $fol->description
      ];
      $no--;
    }

    $filter = ['no_spk' => $spk->no_spk, 'id_dealer' => $this->login->id_dealer];
    $bayar = $this->m_bayar->getDealerInvoiceReceipt($filter);
    $res_riwayat = $bayar->num_rows() > 0 ? 'Ada Pembayaran' : 'Belum Ada Pembayaran';

    // foreach ($bayar->result() as $rs) {
    //   $res = [
    //     'id' => $rs->id_kwitansi,
    //     'price' => $rs->amount,
    //     'payment_method_id' => '',
    //     'payment_method_name' => $rs->cara_bayar,
    //     'date' => $rs->tgl_pembayaran,
    //     'leasing' => $spk->id_finance_company
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
        'key' => $rd->key == NULL ? '' : $rd->key,
        'name' => $rd->name,
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
        'url' => $rd->path == NULL ? '' : $rd->path,
        'key' => $rd->key == NULL ? '' : $rd->key,
        'name' => $rd->nama_file,
        'is_required' => false,
      ];
    }

    $f_acc = [
      'no_spk' => $spk->no_spk,
      'select' => 'sum_qty',
      'id_dealer' => $spk->id_dealer
    ];
    $tot_acc = (int)$this->m_spk->getSPKIndividuAccessories($f_acc)->row()->tot;

    $result = [
      'status' => $spk->status_spk,
      'name' => $spk->nama_konsumen,
      'phone' => $spk->no_hp,
      'image' => (string)image_karyawan($spk->customer_image, $spk->jenis_kelamin),
      'spk_pdf' => (string)$spk->document_spk,
      'produk' => $spk->tipe_ahm,
      'accessories' => $tot_acc,
      'riwayat_pembayaran' => $res_riwayat,
      'formulir_cdb_stnk' => $this->m_spk->cekFormulirCDBSTNK($spk->no_spk),
      'estimasi_penerimaan' => $spk->tgl_pengiriman != NULL ? mediumdate_indo($spk->tgl_pengiriman, ' ') : '',
      'document' => $res_document,
      'follow_up' => isset($follow_up) ? $follow_up : [],
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function follow_up()
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
    ];
    $so = $this->m_so->getSOFollowUp($filter);
    $no = 1;
    $new_result = [];
    foreach ($so->result() as $rs) {
      $new_result[] = [
        'id' => $rs->id,
        'nama' => 'Follow Up ' . $no,
        'activity' => $rs->activity,
        'check_date' => $rs->check_date == NULL ? '' : mediumdate_indo($rs->check_date, ' '),
        'commited_date' => (string)mediumdate_indo($rs->tgl_fol_up, ' '),
        'description' => $rs->description,
      ];
      $no++;
    }
    send_json(msg_sc_success($new_result, NULL));
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
      'provinsi' => (string)$spk->provinsi,
      'kabupaten_kota_id' => (int)$spk->id_kabupaten,
      'kabupaten_kota' => (string)$spk->kabupaten,
      'kecamatan_id' => (int)$spk->id_kecamatan,
      'kecamatan' => (string)$spk->kecamatan,
      'kelurahan_id' => (int)$spk->id_kelurahan,
      'kelurahan' => (string)$spk->kelurahan,
      'agama_id' => (int)$spk->id_agama,
      'agama' => (string)$spk->agama,
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

  function informasi_pelanggan()
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
    $get_spk = $get_data->row();

    $filter = [
      'id_prospek' => $get_spk->id_prospek,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'customer_detail_sc',
    ];
    $prp = $this->m_prospek->getProspek($filter)->row();
    $result = [
      'name' => $get_spk->nama_konsumen,
      'title' => set_title($prp->jenis_kelamin),
      'image' => (string)image_karyawan($get_spk->customer_image, $prp->jenis_kelamin),
      'nik' => $get_spk->no_ktp,
      'no_hp' => (string)$get_spk->no_hp,
      'no_telepon' => $get_spk->no_telp,
      'tempat_lahir' => $get_spk->tempat_lahir ?: '',
      'birthdate' => (string)$get_spk->tgl_lahir == '' ? '' : mediumdate_indo($get_spk->tgl_lahir, ' '),
      'address' => $get_spk->alamat,
      'latitude' => (float)$get_spk->latitude,
      'longitude' => (float)$get_spk->longitude,
      'pekerjaan_id' => $prp->pekerjaan_id,
      'pekerjaan_name' => $prp->pekerjaan_name,
      'postal_code' => (int)$prp->kodepos,
      'email' => $get_spk->email,
      'jenis_kelamin' => $prp->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
      'sumber_prospek_id' => (int)$get_spk->sumber_prospek_id,
      'sumber_prospek_name' => $get_spk->sumber_prospek_name,
      'metode_follow_up_id' => (int)$prp->metode_follow_up_id,
      'metode_follow_up_name' => $prp->metode_fol_up_name,
      'office_phone' => $prp->office_phone,
      'office_address' => $prp->office_address,
      'rencana_pembelian' => $prp->rencana_pembelian,
    ];

    send_json(msg_sc_success($result, NULL));
  }

  function informasi_produk()
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

    $filter = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer
    ];
    $get_data = $this->m_spk->getSPKIndividu($filter);
    cek_referensi($get_data, 'SPK ID');
    $prp = $get_data->row();
    $get_item = $this->m_spk->getSPKIndividuProduct($filter)->result();
    foreach ($get_item as $itm) {
      $item[] = [
        'id'         => $itm->id_item_int,
        'name'       => $itm->tipe_ahm,
        'image'      => '',
        'code'       => $itm->id_item,
        'price'      => $prp->harga_tunai,
        'color_name' => $itm->warna,
        'color_hex'  => '',
        'type'       => 'Unit'
      ];
    }
    $f_spk = [
      'no_spk' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_acc = $this->m_spk->getSPKIndividuAccessories($f_spk)->result();
    foreach ($get_acc as $acc) {
      $item[] = [
        'id'         => $acc->id_part,
        'name'       => $acc->nama_part,
        'image'      => '',
        'code'       => $acc->id_part,
        'price'      => $acc->accessories_harga,
        'color_name' => '',
        'color_hex'  => '',
        'type'       => 'Accessories'
      ];
    }

    $f_app = [
      'no_spk_int' => $so->no_spk,
      'id_dealer' => $this->login->id_dealer,
      'select' => 'unit'
    ];
    $get_app = $this->m_spk->getSPKIndividuApparel($f_app)->result();
    foreach ($get_app as $app) {
      $item[] = [
        'id'         => $app->id_part,
        'name'       => $app->nama_part,
        'image'      => '',
        'code'       => $app->id_part,
        'price'      => $app->apparel_harga,
        'color_name' => '',
        'color_hex'  => '',
        'type'       => 'Apparel'
      ];
    }
    send_json(msg_sc_success($item, NULL));
  }

  function sales_list()
  {
    $get = $this->input->get();
    $mandatory = [
      'page' => 'required',
      'employee_id' => 'required',
    ];
    cek_mandatory($mandatory, $get);

    $get['select'] = 'all';
    $get['cek_referensi'] = true;
    $get['id_karyawan_dealer_int'] = $get['employee_id'];
    $kry = $this->m_master->getKaryawan($get);
    $so = $this->m_so->getSalesOrderActivity($kry->id_karyawan_dealer, $this->login, $kry->honda_id);
    // send_json($kry);

    $data = [
      'name' => $kry->nama_lengkap,
      'image' => (string)image_karyawan($kry->image, $kry->jk),
      'actual' => (int)$so['actual'],
      'target' => (int)$so['target'],
      'item' => []
    ];

    $f_so = [
      'id_karyawan_dealer' => $kry->id_karyawan_dealer,
      'id_dealer' => $this->login->id_dealer,
      'tahun_bulan' => get_ym(),
      'page' => $get['page']
    ];
    $get_data = $this->m_so->getSalesOrderIndividu($f_so);
    foreach ($get_data->result() as $rs) {
      $data['item'][] = [
        'id' => (int)$rs->id_sales_order_int,
        'image' => (string)image_karyawan($rs->customer_image, $rs->jenis_kelamin),
        'name' => $rs->nama_konsumen,
        'produk' => $rs->tipe_ahm,
        'status' => $rs->status_on_sc,
        'actual_document' => (int)$rs->actual_document,
        'total_document' => (int)$rs->total_document
      ];
    }

    send_json(msg_sc_success($data, NULL));
  }

  function riwayat_pembayaran()
  {
    $get = $this->input->get();
    $mandatory = ['sales_id' => 'required'];
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

  function index()
  {
    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_dt = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer
    ];
    $data_sales = $this->m_dms->getTeamStructureManagementDetail($f_dt);

    $tot_actual = 0;
    $tot_target = 0;
    $tot_kredit = 0;
    $tot_tunai = 0;

    foreach ($data_sales->result() as $dt) {
      $kry = new stdClass();
      $kry->id_karyawan_dealer = $dt->id_karyawan_dealer;
      $kry->honda_id = $dt->honda_id == NULL ? $dt->id_flp_md : $dt->honda_id;

      // send_json($kry);
      $user = new stdClass();
      $user->id_dealer = $this->login->id_dealer;

      $so = $this->m_so->getSalesOrderActivity($kry->id_karyawan_dealer, $user, $kry->honda_id);

      $actual = 0;
      $target = 0;
      $item[] = [
        'id'             => (int)$dt->id_karyawan_dealer_int,
        'name'           => $dt->nama_lengkap,
        'image'          => (string)image_karyawan($dt->image, $dt->jenis_kelamin),
        'actual'         => $so['actual'],
        'target'         => $so['target'],
        'actual_sales' => $so['actual'],
        'target_sales' => $so['target']
      ];
      $tot_actual += $so['actual'];
      $tot_target += $so['target'];
      $tot_kredit += $so['kredit'];
      $tot_tunai += $so['tunai'];
    }
    $result = [
      'actual' => $tot_actual,
      'target' => $tot_target,
      'tunai' => $tot_tunai,
      'kredit' => $tot_kredit,
      'item' => isset($item) ? $item : ''
    ];
    send_json(msg_sc_success($result, NULL));
  }

  function target_unit()
  {
    $get = $this->input->get();
    $karyawan = sc_user(['username' => $this->login->username])->row();

    $filter_target_sales = [
      // 'honda_id'    => $karyawan->honda_id,
      'id_dealer'   => $this->login->id_dealer,
      'page'        => $get['page'],
      'type_id'     => $this->input->post('type_id'),
      'tahun_motor' => $this->input->post('year'),
      'tahun'       => get_y(),
      'bulan'       => get_m(),
    ];
    $res_ = $this->m_dms->getH1TargetManagement($filter_target_sales);
    $res = [];
    foreach ($res_->result() as $rs) {
      $f_so = [
        // 'id_karyawan_dealer' => $karyawan->id_karyawan_dealer,
        'id_dealer' => $this->login->id_dealer,
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'bulan_so' => get_ym(),
        'select' => 'count',
      ];
      $actual_so = $this->m_so->getSalesOrderIndividu($f_so)->row()->count;

      $params = ['id_tipe_kendaraan' => $rs->id_tipe_kendaraan];
      $harga = $this->m_prospek->cek_bbn($params);
      $res[] = [
        'id' => (int)$rs->id,
        'name' => $rs->tipe_ahm,
        'code' => $rs->id_tipe_kendaraan,
        'image' => '',
        'price' => (int)$harga,
        'actual' => (int)$actual_so,
        'target' => (int)$rs->target,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $res
    ];
    send_json($result);
  }

  function target_accessories()
  {
    $get = $this->input->get();
    $get['select'] = 'select_tipe_kendaraan_tot_price_acc';
    $res_ = $this->m_stock->getTipeKendaraan($get)->result();

    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_dt = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer
    ];
    $data_sales = $this->m_dms->getTeamStructureManagementDetail($f_dt);
    $karyawan_dealer = '';

    foreach ($data_sales->result() as $dt) {
      $karyawan_dealer[] = $dt->id_karyawan_dealer;
    }

    foreach ($res_ as $rs) {
      $f_acc = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'id_dealer' => $this->login->id_dealer,
        'id_karyawan_dealer_in' => arr_in_sql($karyawan_dealer),
        'join_sales_order' => true,
        'so_invoice' => true,
        'select' => 'sum_qty'
      ];
      // send_json($f_acc);
      $actual = $this->m_spk->getSPKIndividuAccessories($f_acc)->row()->tot;
      $target = 0;
      $data[] = [
        'id' => $rs->id_tipe_kendaraan_int,
        'name' => $rs->tipe_ahm,
        'code' => $rs->id_tipe_kendaraan,
        'image' => (string)$rs->image,
        'price' => (int)$rs->tot_price,
        'actual' => $actual,
        'target' => $target
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $data
    ];
    send_json($result);
  }

  function target_accessories_detail()
  {
    $get = $this->input->get();

    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_dt = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer
    ];
    $data_sales = $this->m_dms->getTeamStructureManagementDetail($f_dt);
    $karyawan_dealer = '';
    foreach ($data_sales->result() as $dt) {
      $karyawan_dealer[] = $dt->id_karyawan_dealer;
    }

    // send_json($this->login);
    // send_json($get);
    $get['select'] = 'select_detail';
    $get['group_by_id_part'] = true;
    $res_ = $this->m_stock->getDetailAksesoris($get)->result();
    $new_res = [];
    foreach ($res_ as $rs) {
      $f_acc = [
        'id_part' => $rs->id,
        'id_dealer' => $this->login->id_dealer,
        'id_karyawan_dealer_in' => arr_in_sql($karyawan_dealer),
        'join_sales_order' => true,
        'so_invoice' => true,
        'select' => 'sum_qty'
      ];
      // send_json($f_acc);
      $actual = $this->m_spk->getSPKIndividuAccessories($f_acc)->row()->tot;
      $target = 0;
      $new_res[] = [
        'id' => $rs->id,
        'name' => $rs->name,
        'code' => $rs->code,
        'image' => (string)$rs->image,
        'price' => (int)$rs->price,
        'actual' => (int)$actual,
        'target' => $target,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }

  function target_apparel()
  {
    $get = $this->input->get();

    $karyawan = sc_user(['username' => $this->login->username])->row();
    $f_dt = [
      'id_sales_coordinator' => $karyawan->id_karyawan_dealer
    ];
    $data_sales = $this->m_dms->getTeamStructureManagementDetail($f_dt);
    $karyawan_dealer = '';

    $get['select'] = 'select_apparel';
    $get['group_by_id_part'] = true;
    $res_ = $this->m_stock->getApparel($get)->result();
    $new_res = [];
    foreach ($res_ as $rs) {
      $target = 0;

      $f_acc = [
        'id_part' => $rs->id,
        'id_dealer' => $this->login->id_dealer,
        'id_karyawan_dealer_in' => arr_in_sql($karyawan_dealer),
        'join_sales_order' => true,
        'so_invoice' => true,
        'select' => 'sum_qty'
      ];
      // send_json($f_acc);
      $actual = $this->m_spk->getSPKIndividuApparel($f_acc)->row()->tot;

      $new_res[] = [
        'id' => $rs->id,
        'name' => $rs->name,
        'image' => (string)$rs->image,
        'code' => $rs->code,
        'price' => (int)$rs->price,
        'actual' => (int)$actual,
        'target' => $target,
      ];
    }
    $result = [
      'status' => 1,
      'message' => ['success'],
      'data' => $new_res
    ];
    send_json($result);
  }
}
