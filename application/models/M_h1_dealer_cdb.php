<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_cdb extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h1_dealer_spk', 'm_spk');
  }

  function getCDB($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE spk.id_dealer = '$id_dealer' ";

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND spk.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_cdb'])) {
      if ($filter['id_cdb'] != '') {
        $where .= " AND cdb.id_cdb='{$filter['id_cdb']}'";
      }
    }
    if (isset($filter['document_is_null'])) {
      $where .= " AND cdb.document IS NULL";
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_ktp LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'history') {
          $order_column = ['spk.no_spk_gc', 'spk.nama_npwp', 'spk.no_npwp', 'spk.alamat', 'spk.status', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY spk.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    return $this->db->query("SELECT cdb.*,spk.no_ktp,spk.nama_konsumen
    FROM tr_cdb cdb
    JOIN tr_spk spk ON spk.no_spk=cdb.no_spk
		$where
    $order
    $limit
    ");
  }

  function updateCDBSTNK($post, $spk)
  {
    $f_jenis_pembelian = ['id' => $post['jenis_pembelian_id'], 'cek_referensi' => true];
    $jenis_beli = $this->m_master->getJenisPembelian($f_jenis_pembelian);

    if (isset($post['master_agama_id'])) {
      $f_agama = ['id_agama' => $post['master_agama_id'], 'cek_referensi' => true];
      $agama = $this->m_master->getAgama($f_agama);
    } else {
      $f_agama = ['agama' => 'Lain-Lain ', 'cek_referensi' => true];
      $agama = $this->m_master->getAgama($f_agama);
    }

    $f_pdk = ['id_pendidikan' => $post['pendidikan_id'], 'cek_referensi' => true];
    $pendidikan = $this->m_master->getPendidikan($f_pdk);

    $filter = ['id_merk_sebelumnya' => $post['motorcycle_brand_id'], 'cek_referensi' => true];
    $brand = $this->m_master->getMotorCycleBrand($filter);

    $filter = ['id_jenis_sebelumnya' => $post['motorcycle_type_id'], 'cek_referensi' => true];
    $type = $this->m_master->getMotorType($filter);

    $filter = ['id' => $post['pengguna_kendaraan_id'], 'cek_referensi' => true];
    $pgn = $this->m_master->getPenggunaKendaraan($filter);

    if (isset($post['master_status_rumah_id'])) {
      $filter = ['id' => $post['master_status_rumah_id'], 'cek_referensi' => true];
      $sts_rumah = $this->m_master->getStatusRumah($filter);
    } else {
      $filter = ['name' => 'Lain-lain', 'cek_referensi' => true];
      $sts_rumah = $this->m_master->getStatusRumah($filter);
    }

    if (isset($post['keperluan_pembelian_id'])) {
      $filter = ['id' => $post['keperluan_pembelian_id'], 'cek_referensi' => true];
      $digunakan = $this->m_master->getKeperluanPembelian($filter);
    } else {
      $filter = ['name' => 'LAIN-LAIN', 'cek_referensi' => true];
      $digunakan = $this->m_master->getKeperluanPembelian($filter);
    }

    $filter = ['id_sub_pekerjaan' => $post['pekerjaan_id'], 'cek_referensi' => true];
    $get_pkj = $this->m_master->getPekerjaan($filter);

    $filter = ['id_pengeluaran_bulan' => $post['pengeluaran_id'], 'cek_referensi' => true];
    $pengeluaran = $this->m_master->getPengeluaran($filter);

    $filter = ['id' => $post['jenis_penjualan_id'], 'cek_referensi' => true];
    $jenis_jual = $this->m_master->getJenisPenjualan($filter);

    $filter = ['id_hobi' => $post['hobby_id']];
    $hb = $this->m_master->getHobi($filter);
    if ($hb->num_rows() > 0) {
      $hb = $hb->row();
      $hobi = $hb->id;
    } else {
      $hobi = $post['hobby_id'];
    }

    $aktivitas_penjualan = $this->db->query("SELECT sprp.id_cdb FROm tr_prospek prp
                                      JOIN ms_sumber_prospek sprp ON sprp.id_dms=prp.sumber_prospek
                                      WHERE prp.id_customer='$spk->id_customer'
    ")->row()->id_cdb;

    $nama_instansi        = isset($post['instansi_name']) ? $post['instansi_name'] : NULL;
    $alamat_instansi      = isset($post['instansi_address']) ? $post['instansi_address'] : NULL;
    $deskripsi_pekerjaan  = isset($post['deskripsi_pekerjaan']) ? $post['deskripsi_pekerjaan'] : NULL;
    $kecamatan_instansi   = isset($post['instansi_kecamatan_id']) ? $post['instansi_kecamatan_id'] : NULL;

    $corr = $post['correspondence'];
    $alamat_sama    = $corr == 'true' ? 'Tidak' : 'Ya';
    $alamat2        = $corr == 'true' ? $post['correspondence_address'] : $post['address'];
    $id_provinsi2   = $corr == 'true' ? $post['correspondence_provinsi_id'] : $post['master_provinsi_id'];
    $id_kabupaten2  = $corr == 'true' ? $post['correspondence_kabupaten_kota_id'] : $post['master_kabupaten_kota_id'];
    $id_kecamatan2  = $corr == 'true' ? $post['correspondence_kecamatan_id'] : $post['master_kecamatan_id'];
    $id_kelurahan2  = $corr == 'true' ? $post['correspondence_kelurahan_id'] : $post['master_kelurahan_id'];
    $spk_kode_pos   = $this->db->query("SELECT kode_pos FROM ms_kelurahan WHERE id_kelurahan='{$post['master_kelurahan_id']}'")->row()->kode_pos;
    $kodepos2       = $corr == 'true' ? $post['correspondence_kode_pos'] : $spk_kode_pos;
    $sedia_hub = $post['information'] == 'true' ? 'Ya' : 'Tidak';

    $field_cdb = [
      'jenis_beli'            => $jenis_beli->name,
      'id_jenis_pembelian'    => $jenis_beli->id,
      'agama'                 => $agama->id,
      'pendidikan'            => $pendidikan->id,
      'sedia_hub'             => $sedia_hub,
      'merk_sebelumnya'       => $post['motorcycle_brand_id'],
      'jenis_sebelumnya'      => $post['motorcycle_type_id'],
      'digunakan'             => $digunakan->id,
      'menggunakan'           => $pgn->name,
      'facebook'              => $post['facebook'],
      'twitter'               => $post['twitter'],
      'instagram'             => $post['instagram'],
      'youtube'               => $post['youtube'],
      'hobi'                  => $hobi,
      'cicilan'               => $post['cicilan'],
      'nama_instansi'         => $nama_instansi,
      'alamat_instansi'       => $alamat_instansi,
      'id_kecamatan_instansi' => $kecamatan_instansi,
      'updated_at'            => waktu_full(),
      'updated_by'            => $spk->id_user,
      'aktivitas_penjualan'   => $aktivitas_penjualan,
      'id_dealer'             => $spk->id_dealer,
      'no_spk'                => $spk->no_spk,
    ];

    $filter_cdb = ['no_spk' => $spk->no_spk, 'id_dealer' => $spk->id_dealer];
    $cek_cdb = $this->m_cdb->getCDB($filter_cdb);
    if ($cek_cdb->num_rows() > 0) {
      $cdb = $cek_cdb->row();
      $upd_cdb = $field_cdb;
    } else {
      $ins_cdb = $field_cdb;
    }

    $id_finance_company = NULL;
    if (!($post['leasing_id'] == '')) {
      $f = ['id_finance_company_int' => $post['leasing_id']];
      $lsg = $this->m_master->getLeasing($f);
      if ($lsg->num_rows() > 0) {
        $lsg = $lsg->row();
        $id_finance_company = $lsg->id_finance_company;
        $upd_skema_kredit = [
          'id_finco' => $lsg->id_finance_company,
          'angsuran' => $post['cicilan']
        ];
      }
    }

    if ($get_pkj == null) {
      $pekerjaan = null;
      $sub_pekerjaan = null;
    } else {
      $pekerjaan = $get_pkj->id_pekerjaan;
      $sub_pekerjaan = $get_pkj->id;
    }

    $upd_spk = [
      'no_spk'                => $spk->no_spk,
      'no_ktp'                => $post['nik'],
      'no_kk'                 => $post['kk'],
      'nama_konsumen'         => $post['name'],
      'alamat'                => $post['address'],
      'id_provinsi'           => $post['master_provinsi_id'],
      'id_kabupaten'          => $post['master_kabupaten_kota_id'],
      'id_kecamatan'          => $post['master_kecamatan_id'],
      'id_kelurahan'          => $post['master_kelurahan_id'],
      'status_rumah'          => $sts_rumah->name,
      'status_rumah_id'       => $sts_rumah->id,
      'email'                 => $post['email'],
      'pekerjaan'             => $post['pekerjaan_id'],
      'pengeluaran_bulan'     => $post['pengeluaran_id'],
      // 'id_finance_company'    => $id_finance_company,
      'no_telp'               => $post['no_telp'],
      'no_hp'                 => isset($post['no_hp']) ? $post['no_hp'] : 0,
      'refferal_id'           => $post['ref_id'],
      'robd_id'               => $post['ro_bd_id'],
      'keterangan'            => $post['keterangan'],
      'tempat_lahir'          => $post['tempat_lahir'],
      'tgl_lahir'             => $post['tanggal_lahir'],
      'status_hp'             => $post['status_kepemilikan_no_hp'],
      'updated_at'            => waktu_full(),
      'updated_by'            => $spk->id_user,
      'pekerjaan'             => $pekerjaan,
      'alamat_sama'           => $alamat_sama,
      'alamat2'               => $alamat2,
      'id_provinsi2'          => $id_provinsi2,
      'id_kabupaten2'         => $id_kabupaten2,
      'id_kecamatan2'         => $id_kecamatan2,
      'id_kelurahan2'         => $id_kelurahan2,
      'kodepos2'              => $kodepos2,
      'bpkb_stnk_birth_place' => $post['tempat_lahir'],
      'kodepos'               => $spk_kode_pos,
      'denah_lokasi'          => '-1.613510, 103.594603'
    ];

    // if ($post['jenis_penjualan_id'] == 2) {
    //   $upd_spk['angsuran']  = $post['cicilan'];
    //   $upd_spk['uang_muka'] = $post['dp_awal'];
    // } else {
    //   $upd_spk['angsuran']           = 0;
    //   $upd_spk['voucher_2']          = 0;
    //   $upd_spk['voucher_tambahan_2'] = 0;
    //   $upd_spk['tenor']              = 0;
    //   $upd_spk['dp_stor']            = 0;
    //   $upd_spk['uang_muka']          = 0;
    // }

    $upd_prospek = [
      'pekerjaan_lain'    => $deskripsi_pekerjaan,
      'pekerjaan'         => $pekerjaan,
      'sub_pekerjaan'     => $sub_pekerjaan,
      'alamat_kantor'     => $alamat_instansi,
      'nama_tempat_usaha' => $nama_instansi,
      'sedia_hub'         => $sedia_hub,
      'merk_sebelumnya'   => $post['motorcycle_brand_id'],
      'jenis_sebelumnya'  => $post['motorcycle_type_id'],
      'digunakan'         => $digunakan->id,
      'pemakai_motor'     => $pgn->name,
      'tempat_lahir'      => $post['tempat_lahir'],
      'id_kelurahan'      => $post['master_kelurahan_id'],
      'id_kecamatan'      => $post['master_kecamatan_id'],
      'id_kabupaten'      => $post['master_kabupaten_kota_id'],
      'id_provinsi'       => $post['master_provinsi_id'],
      'kodepos'           => $spk_kode_pos,
      'agama'             => $agama->id,
      'no_hp'             => isset($post['no_hp']) ? $post['no_hp'] : 0,
      'no_telp'           => $post['no_telp'],
      'status_nohp'       => $post['status_kepemilikan_no_hp'],
      'jenis_customer'    => 'regular'
    ];

    // if ($post['dp_awal'] < 500000 && $post['jenis_penjualan_id'] == 2) {
    //   $msg = ['Silahkan cek kembali nominal DP Awal'];
    //   send_json(msg_sc_error($msg));
    // }
    $this->db->query("SET FOREIGN_KEY_CHECKS=0");

    $this->db->trans_begin();
    if (isset($ins_cdb)) {
      $this->db->insert('tr_cdb', $ins_cdb);
    }
    if (isset($upd_cdb)) {
      $cond = ['id_cdb' => $cdb->id_cdb];
      $this->db->update('tr_cdb', $upd_cdb, $cond);
    }
    if (isset($upd_spk)) {
      $cond = ['no_spk' => $spk->no_spk];
      $this->db->update('tr_spk', $upd_spk, $cond);
    }
    if (isset($upd_prospek)) {
      $cond = ['id_customer' => $spk->id_customer];
      $this->db->update('tr_prospek', $upd_prospek, $cond);
    }
    if (isset($upd_skema_kredit)) {
      $cond = ['id_prospek' => $spk->id_prospek];
      // $this->db->update('tr_skema_kredit', $upd_skema_kredit, $cond);
    }
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $msg = ['Terjadi kesalahan'];
      send_json(msg_sc_error($msg));
    } else {
      $this->db->trans_commit();
      $msg = ['Data has been updated'];
      $this->db->query("SET FOREIGN_KEY_CHECKS=1");
      // $this->m_spk->cetakSPK($spk->no_spk, true);
      $response = msg_sc_success(NULL, $msg);
      send_json($response);
    }
  }
}
