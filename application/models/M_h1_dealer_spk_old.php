<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_spk extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h1_dealer_prospek', 'm_prospek');
    $this->load->library('cfpdf');
    $this->load->library('PDF_HTML');
    $this->load->library('PDF_HTML');
    $this->load->library('mpdf_l');
    $this->load->helper('tgl_indo');
    $this->load->model('m_admin');
  }
  function mata_uang($a)
  {
    if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 0, ',', '.');
  }
  function format_tgl($a)
  {
    return date('d/m/Y', strtotime($a));
  }


  function getSPKGC($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE spk.id_dealer = '$id_dealer' ";

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk_gc'] != '') {
        $where .= " AND spk.no_spk_gc='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['status_in'])) {
      $where .= " AND spk.status in ({$filter['status_in']})";
    }
    if (isset($filter['expired'])) {
      $where .= " AND spk.expired=1";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk_gc LIKE '%$search%'
                            OR spk.nama_npwp LIKE '%$search%'
                            OR spk.no_npwp LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status LIKE '%$search%'
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

    return $this->db->query("SELECT spk.no_spk_gc,spk.nama_npwp,spk.no_npwp,spk.alamat,spk.status
    FROM tr_spk_gc spk
		$where
    $order
    $limit
    ");
  }

  function getSPKIndividu($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE spk.id_dealer = '$id_dealer' ";
    $join = "";

    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND spk.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['status_spk'])) {
      if ($filter['status_spk'] != '') {
        $where .= " AND spk.status_spk='{$filter['status_spk']}'";
      }
    }
    if (isset($filter['status_spk_not'])) {
      if ($filter['status_spk_not'] != '') {
        $where .= " AND spk.status_spk !='{$filter['status_spk_not']}'";
      }
    }
    if (isset($filter['status_spk_not_in'])) {
      if ($filter['status_spk_not_in'] != '') {
        $where .= " AND spk.status_spk NOT IN({$filter['status_spk_not_in']})";
      }
    }
    if (isset($filter['document_is_null'])) {
      if ($filter['document_is_null'] != '') {
        $where .= " AND spk.document_spk IS NULL";
      }
    }

    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1)='{$filter['id_karyawan_dealer']}'";
      }
    }

    if (isset($filter['tahun_bulan'])) {
      if ($filter['tahun_bulan'] != '') {
        $where .= " AND LEFT(spk.tgl_spk,7)='{$filter['tahun_bulan']}'";
      }
    }
    if (isset($filter['have_program'])) {
      if ($filter['have_program'] != '') {
        $where .= " AND spk.program_umum!=''";
      }
    }
    if (isset($filter['status_proposal_not'])) {
      if ($filter['status_proposal_not'] != '') {
        $where .= " AND cd.status_proposal!='{$filter['status_proposal_not']}'";
      }
    }
    if (isset($filter['status_proposal'])) {
      if ($filter['status_proposal'] != '') {
        $where .= " AND cd.status_proposal='{$filter['status_proposal']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR tk.tipe_ahm LIKE '%$search%'
                            ) 
            ";
      }
    }

    if (isset($filter['left_join_cdb'])) {
      $join .= " LEFT JOIN tr_cdb cdb ON cdb.no_spk=spk.no_spk
                 LEFT JOIN ms_agama ag ON ag.id_agama=cdb.agama
                 LEFT JOIN ms_pendidikan pdn ON pdn.id_pendidikan=cdb.pendidikan
                 LEFT JOIN ms_pekerjaan pkj ON pkj.id_pekerjaan=spk.pekerjaan
                 LEFT JOIN ms_pengeluaran_bulan plb ON plb.id_pengeluaran_bulan=spk.pengeluaran_bulan
                 LEFT JOIN ms_merk_sebelumnya merk ON merk.id_merk_sebelumnya=cdb.merk_sebelumnya
                 LEFT JOIN ms_digunakan dgn ON dgn.id_digunakan=cdb.digunakan
                 LEFT JOIN ms_jenis_sebelumnya jsb ON jsb.id_jenis_sebelumnya=cdb.jenis_sebelumnya
                 LEFT JOIN sc_ms_pengguna_kendaraan pkd ON pkd.id=cdb.menggunakan
                 LEFT JOIN ms_hobi hb ON hb.id_hobi=cdb.hobi
      ";
    }
    if (isset($filter['left_join_jenis_pembelian'])) {
      $join .= " LEFT JOIN ms_jenis_pembelian jp ON jp.id_jenis_pembelian=cdb.id_jenis_pembelian";
    }
    // if (isset($filter['join_agama'])) {
    //   $join .= " LEFT JOIN ms_agama ag ON ag.id_agama=cdb.agama";
    // }
    if (isset($filter['join_status_rumah'])) {
      $join .= " LEFT JOIN sc_ms_status_rumah sc ON sc.id=spk.status_rumah_id";
    }
    if (isset($filter['join_wilayah'])) {
      $join .= " LEFT JOIN ms_kelurahan kel ON kel.id_kelurahan=spk.id_kelurahan
                 LEFT JOIN ms_kecamatan kec ON kec.id_kecamatan=spk.id_kecamatan
                 LEFT JOIN ms_kabupaten kab ON kab.id_kabupaten=spk.id_kabupaten
                 LEFT JOIN ms_provinsi prov ON prov.id_provinsi=spk.id_provinsi
                 LEFT JOIN sc_ms_jenis_penjualan jj ON jj.id=cdb.jenis_penjualan_id
               ";
    }
    if (isset($filter['join_wilayah_instansi'])) {
      $join .= " LEFT JOIN ms_kecamatan kec_ins ON kec_ins.id_kecamatan=cdb.id_kecamatan_instansi
                 LEFT JOIN ms_kabupaten kab_ins ON kab_ins.id_kabupaten=kec_ins.id_kabupaten
                 LEFT JOIN ms_provinsi prov_ins ON prov_ins.id_provinsi=kab_ins.id_provinsi
               ";
    }
    if (isset($filter['join_wilayah_correspondence'])) {
      $join .= " LEFT JOIN ms_kelurahan kel_corr ON kel_corr.id_kelurahan=cdb.id_kelurahan_corr
                 LEFT JOIN ms_kecamatan kec_corr ON kec_corr.id_kecamatan=cdb.id_kecamatan_corr
                 LEFT JOIN ms_kabupaten kab_corr ON kab_corr.id_kabupaten=cdb.id_kabupaten_corr
                 LEFT JOIN ms_provinsi prov_corr ON prov_corr.id_provinsi=cdb.id_provinsi_corr
               ";
    }

    if (isset($filter['join_so'])) {
      $join .= " JOIN tr_sales_order so ON so.no_spk=spk.no_spk";
    }
    if (isset($filter['join_claim_program'])) {
      $join .= " JOIN tr_sales_order so ON so.no_spk=spk.no_spk
        JOIN tr_claim_dealer cd ON cd.id_sales_order=so.id_sales_order
      ";
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if (isset($order['field'])) {
          $order = "ORDER BY {$order['field']} {$order['order']}";
        } else {
          if ($filter['order_column'] == 'history') {
            $order_column = ['spk.no_spk_gc', 'spk.nama_npwp', 'spk.no_npwp', 'spk.alamat', 'spk.status', NULL];
          }
          $order_clm  = $order_column[$order['0']['column']];
          $order_by   = $order['0']['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      } else {
        $order .= " ORDER BY spk.created_at DESC ";
      }
    } else {
      $order = "ORDER BY spk.created_at DESC";
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'formulir_cdb') {
        $select = "spk.*,prp.customer_image,tk.tipe_ahm,cdb.*,jp.jenis_pembelian,prov.provinsi,kab.kabupaten,kec.kecamatan,kel.kelurahan,ag.id_agama,ag.agama,cdb.facebook,cdb.twitter,cdb.instagram,
        cdb.hobi id_hobi,hb.hobi,
        cdb.youtube,cdb.correspondence,cdb.correspondence_address,pdn.id_pendidikan,pdn.pendidikan,spk.pekerjaan AS pekerjaan_id,pkj.pekerjaan,plb.pengeluaran,plb.id_pengeluaran_bulan,cdb.sedia_hub,merk.merk_sebelumnya,dgn.id_digunakan,dgn.digunakan,merk.id_merk_sebelumnya,jsb.id_jenis_sebelumnya,jsb.jenis_sebelumnya,
        pkd.id AS pengguna_kendaraan_id,
        pkd.name pengguna_kendaraan_name,
        kd.nama_lengkap,kd.image AS kry_image,kd.id_karyawan_dealer,dl.kode_dealer_md,fn.finance_company,cdb.jenis_penjualan_id,jj.name AS jenis_penjualan_name,prp.sumber_prospek,fn.id_finance_company_int,cdb.cicilan,document_spk_form,
        CASE 
          WHEN spk.jenis_wn IS NULL THEN 1
          WHEN spk.jenis_wn='WNI' THEN 1 
        ELSE 2 END AS jenis_wn_int,
        kec_ins.id_kecamatan id_kecamatan_instansi,kec_ins.kecamatan kecamatan_instansi,kab_ins.id_kabupaten id_kabupaten_instansi,kab_ins.kabupaten kabupaten_instansi, prov_ins.id_provinsi id_provinsi_instansi, prov_ins.provinsi provinsi_instansi,
        CASE WHEN ps.id IS NULL THEN prp.sumber_prospek ELSE ps.id END AS sumber_prospek_id,
        CASE WHEN ps.id IS NULL THEN prp.sumber_prospek ELSE ps.description END AS sumber_prospek_name,
        prov_corr.provinsi provinsi_corr,
        kab_corr.kabupaten kabupaten_corr,
        kec_corr.kecamatan kecamatan_corr,
        kel_corr.kelurahan kelurahan_corr,prp.id_prospek
        ";
      } elseif ($filter['select'] == 'sum_voucher') {
        $voucher = "CASE 
                      WHEN spk.jenis_beli='kredit' THEN spk.voucher_2
                      ELSE spk.voucher_1
                    END
                    ";
        $select = "IFNULL(SUM($voucher),0) AS tot_voucher";
      } elseif ($filter['select'] == 'average_payment_claim') {
        $voucher = "CASE 
                      WHEN spk.jenis_beli='kredit' THEN spk.voucher_2
                      ELSE spk.voucher_1
                    END
                    ";
        $select = "IFNULL(AVG(IFNULL($voucher,0)),0) AS total";
      } elseif ($filter['select'] == 'sum_diskon') {
        $voucher = "CASE 
                      WHEN spk.jenis_beli='kredit' THEN spk.voucher_2
                      ELSE spk.voucher_1
                    END
                    ";
        $voucher_tambahan = "CASE 
                      WHEN spk.jenis_beli='kredit' THEN spk.voucher_tambahan_2
                      ELSE spk.voucher_tambahan_1
                    END
                    ";
        $total  = "(($voucher)+($voucher_tambahan)+spk.diskon)";
        $select = "IFNULL(SUM(IFNULL($total,0)),0) AS total_diskon";
      }
    } else {
      $act_doc = "SELECT COUNT(id) FROM tr_spk_file WHERE no_spk=spk.no_spk";
      $tot_doc = "SELECT COUNT(id) FROM sc_ms_document_spk";
      $select = "spk.*,fn.finance_company,prp.id_karyawan_dealer,prp.customer_image,tk.tipe_ahm,prp.no_telp_kantor,prp.alamat_kantor,'' status_complete,($tot_doc) AS total_document,($act_doc) AS actual_document,prp.rencana_pembelian,prp.jenis_kelamin,prp.sumber_prospek,prp.id_prospek";
    }

    return $this->db->query("SELECT $select
    FROM tr_spk spk
    JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
    LEFT JOIN ms_sumber_prospek ps ON ps.id=prp.sumber_prospek
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
    JOIN ms_dealer dl ON dl.id_dealer=spk.id_dealer
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp.id_karyawan_dealer
    LEFT JOIN ms_finance_company fn ON fn.id_finance_company=spk.id_finance_company
    $join
		$where
    $order
    $limit
    ");
  }
  function getEvent()
  {
    return $this->db->query("SELECT * FROM ms_event ORDER BY created_at DESC");
  }

  function getDataNPWP($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    return $this->db->query("SELECT * FROM tr_prospek_gc LEFT JOIN ms_kelurahan ON tr_prospek_gc.id_kelurahan=ms_kelurahan.id_kelurahan 
							WHERE tr_prospek_gc.id_dealer = '$id_dealer' AND tr_prospek_gc.status_prospek = 'Deal'
							ORDER BY tr_prospek_gc.nama_npwp ASC LIMIT 1");
  }

  function getSPK($filter = NULL)
  {
    if (dealer()) {
      $id_dealer = dealer()->id_dealer;
    } else {
      $id_dealer = $filter['id_dealer'];
    }
    $where_id  = "WHERE spk.id_dealer    = '$id_dealer' ";
    $where_gc  = "WHERE spk_gc.id_dealer = '$id_dealer' ";
    // $where_id  = "WHERE 1=1 ";
    // $where_gc  = "WHERE 1=1 ";
    $select_id = '';
    $select_gc = '';
    $dp_gc = "SELECT SUM(IFNULL(dp_stor,0)) FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc";
    $angsuran_gc = "SELECT SUM(IFNULL(angsuran,0)) FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc";
    $tenor_gc = "SELECT tenor FROM tr_spk_gc_detail spk_gc_detail WHERE spk_gc_detail.no_spk_gc=spk_gc.no_spk_gc LIMIT 1";
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where_id .= " AND spk.no_spk='{$filter['no_spk']}'";
        $where_gc .= " AND spk_gc.no_spk_gc='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where_id .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
        $where_gc .= " AND 1=0";
      }
    }
    if (isset($filter['id_invoice_tjs'])) {
      if ($filter['id_invoice_tjs'] != '') {
        $where_id .= " AND tjs.id_invoice='{$filter['id_invoice_tjs']}'";
        $where_gc .= " AND tjs_gc.id_invoice='{$filter['id_invoice_tjs']}'";
      }
    }
    if (isset($filter['id_customer'])) {
      if ($filter['id_customer'] != '') {
        $where_id .= " AND spk.id_customer='{$filter['id_customer']}'";
        $where_gc .= " AND spk_gc.id_prospek_gc='{$filter['id_customer']}'";
      }
    }
    if (isset($filter['bulan_spk'])) {
      if ($filter['bulan_spk'] != '') {
        $where_id .= " AND LEFT(spk.tgl_spk,7)='{$filter['bulan_spk']}'";
        $where_gc .= " AND LEFT(spk_gc.tgl_spk_gc,7)='{$filter['bulan_spk']}'";
      }
    }
    if (isset($filter['status_in'])) {
      $where_id .= " AND spk.status in ({$filter['status_in']})";
      $where_gc .= " AND spk_gc.status in ({$filter['status_in']})";
    }
    if (isset($filter['expired'])) {
      $where_id .= " AND spk.expired=1";
      $where_gc .= " AND spk_gc.expired=1";
    }
    if (isset($filter['spk_ada_tanda_jadi'])) {
      $where_id .= " AND IFNULL(spk.tanda_jadi,0)>0";
      $where_gc .= " AND IFNULL(spk_gc.tanda_jadi,0)>0";
    }
    if (isset($filter['spk_ada_dp'])) {
      $where_id .= " AND IFNULL(spk.dp_stor,0)>0 AND jenis_beli='Kredit' ";
      $where_gc .= " AND IFNULL($dp_gc)>0 AND spk_gc.jenis_beli='Kredit' ";
    }
    if (isset($filter['id_invoice_dp_null'])) {
      $where_id .= " AND dp.id_invoice_dp IS NULL";
      $where_gc .= " AND dp_gc.id_invoice_dp IS NULL";
    }
    if (isset($filter['id_tjs_null'])) {
      $where_id .= " AND tjs.id_invoice IS NULL";
      $where_gc .= " AND tjs_gc.id_invoice IS NULL";
    }
    if (isset($filter['id_karyawan_dealer'])) {
      if ($filter['id_karyawan_dealer'] != '') {
        $where_id .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1)='{$filter['id_karyawan_dealer']}'";
        $where_gc .= " AND prp_gc.id_karyawan_dealer = ({$filter['id_karyawan_dealer']})";
      }
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where_id .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1) IN ({$filter['id_karyawan_dealer_in']})";
        $where_gc .= " AND prp_gc.id_karyawan_dealer IN ({$filter['id_karyawan_dealer_in']})";
      }
    }


    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where_id .= " AND (spk.no_spk LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_ktp LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.jenis_beli LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status_spk LIKE '%$search%'
                            ) 
            ";
        $where_gc .= " AND (spk_gc.no_spk_gc LIKE '%$search%'
                            OR spk_gc.nama_npwp LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'pembayaran') {
          $order_column = ['no_spk', 'tgl_spk', 'nama_konsumen', 'no_ktp', 'no_hp', 'id_tipe_kendaraan', 'id_warna', 'jenis_beli', 'tanda_jadi', 'total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $join_additional_id = '';
    $join_additional_gc = '';
    $select = "*";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'invoice_tjs') {
        $select_id = "no_spk,spk.nama_konsumen,tgl_spk,spk.no_ktp,spk.no_hp,spk.id_tipe_kendaraan,spk.id_warna,spk.jenis_beli," . sql_total_bayar_spk() . " AS total_bayar,spk.tanda_jadi,spk.created_at," . sql_diskon_spk() . " AS diskon,tk.tipe_ahm,wr.warna,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,tjs.id_invoice,tjs.created_at created_at_tjs,tjs_r.print_at,tjs_r.print_by,tjs_r.print_ke,spk.harga_tunai";
        $join_additional_id = " LEFT JOIN tr_h1_dealer_invoice_receipt tjs_r ON tjs_r.id_invoice=tjs.id_invoice";

        $select_gc = "no_spk_gc,spk_gc.nama_npwp,tgl_spk_gc,spk_gc.no_npwp,spk_gc.no_telp,'' AS id_tipe_kendaraan,AS id_warna,spk_gc.jenis_beli,(" . sql_total_bayar_spk_gc_summary() . ") AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,(" . sql_diskon_spk_gc_summary() . ") AS diskon,'' AS tipe_ahm,'' AS warna,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,tjs_gc.id_invoice,tjs_gc.created_at,tjs_gc_r.print_at,tjs_gc_r.print_by,tjs_gc_r.print_ke,0 AS harga_tunai";
        $join_additional_gc = " LEFT JOIN tr_h1_dealer_invoice_receipt tjs_gc_r ON tjs_gc_r.id_invoice=tjs_gc.id_invoice";
      } elseif ($filter['select'] == 'count') {

        $select_id = "spk.no_spk";
        $select_gc = "spk_gc.no_spk_gc";
        $select = "COUNT(no_spk) AS count";
      }
    } else {
      $select_id = "no_spk,spk.nama_konsumen,spk.tgl_spk,spk.no_ktp,spk.no_hp,spk.id_tipe_kendaraan,spk.id_warna,spk.jenis_beli," . sql_total_bayar_spk() . " AS total_bayar,spk.tanda_jadi,spk.created_at," . sql_diskon_spk() . " AS diskon,tk.tipe_ahm,wr.warna,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,dp_stor,'individu' AS jenis_spk,prp.id_customer,spk.alamat,spk.harga_tunai,spk.biaya_bbn,spk.tenor,spk.angsuran,spk.id_finance_company,finco.finance_company";
      // $dp_gc = 0;
      $select_gc = "spk_gc.no_spk_gc,spk_gc.nama_npwp,tgl_spk_gc,spk_gc.no_npwp,spk_gc.no_telp,'' AS id_tipe_kendaraan,'' AS id_warna,spk_gc.jenis_beli,(" . sql_total_bayar_spk_gc_summary() . ") AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,(" . sql_diskon_spk_gc_summary() . ") AS diskon,'' AS tipe_ahm,'' AS warna,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,($dp_gc) AS dp_stor,'gc' AS jenis_spk,prp_gc.id_prospek_gc,spk_gc.alamat,0 AS harga_tunai,0 AS biaya_bbn,($tenor_gc),($angsuran_gc),spk_gc.id_finance_company,finco_gc.finance_company";
    }
    return $this->db->query("SELECT $select FROM(
      SELECT $select_id
      FROM tr_spk spk
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
      JOIN ms_warna wr ON wr.id_warna=spk.id_warna
      LEFT JOIN tr_prospek prp ON prp.id_customer=spk.id_customer
      LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp.id_karyawan_dealer
      LEFT JOIN tr_invoice_tjs tjs ON tjs.id_spk=spk.no_spk
      LEFT JOIN tr_invoice_dp dp ON dp.id_spk=spk.no_spk
      LEFT JOIN ms_finance_company finco ON finco.id_finance_company=spk.id_finance_company
      $join_additional_id
      $where_id
      UNION
      SELECT $select_gc
      FROM tr_spk_gc spk_gc
      LEFT JOIN tr_prospek_gc prp_gc ON prp_gc.id_prospek_gc=spk_gc.id_prospek_gc
      LEFT JOIN ms_karyawan_dealer kd_gc ON kd_gc.id_karyawan_dealer=prp_gc.id_karyawan_dealer
      LEFT JOIN tr_invoice_tjs tjs_gc ON tjs_gc.id_spk=spk_gc.no_spk_gc
      LEFT JOIN tr_invoice_dp dp_gc ON dp_gc.id_spk=spk_gc.no_spk_gc
      LEFT JOIN ms_finance_company finco_gc ON finco_gc.id_finance_company=spk_gc.id_finance_company
      $join_additional_gc
      $where_gc
    ) AS tabel
    $order
    $limit
    ");
  }

  function getSPKGCDetail($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['no_spk'])) {
      $where .= " AND spk_gc_k.no_spk_gc='{$filter['no_spk']}'";
    }
    $diskon = "(IFNULL(nilai_voucher,0)+IFNULL(voucher_tambahan,0))";
    return $this->db->query("SELECT tk.id_tipe_kendaraan,tk.tipe_ahm,wr.id_warna,wr.warna,spk_gc_k.total_unit,spk_gc_d.harga,$diskon AS diskon,spk_gc_k.qty,dp_stor,((total_unit-$diskon)*spk_gc_k.qty) AS total_bayar,spk_gc_d.harga AS harga_tunai
    FROM tr_spk_gc_kendaraan spk_gc_k 
    JOIN tr_spk_gc_detail spk_gc_d ON spk_gc_d.no_spk_gc=spk_gc_k.no_spk_gc AND spk_gc_d.id_tipe_kendaraan=spk_gc_k.id_tipe_kendaraan AND spk_gc_d.id_warna=spk_gc_k.id_warna
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk_gc_k.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=spk_gc_k.id_warna
    $where");
  }

  public function getSPKAllDetail($filter)
  {
    if ($filter['jenis_spk'] == 'gc') {
      $res_details = $this->getSPKGCDetail($filter)->result();
    } elseif ($filter['jenis_spk'] == 'individu') {
      $res_details = $this->getSPK($filter)->result();
    }
    return $res_details;
  }

  function cari_id_new($id_dealer)
  {
    $th       = date('Y');
    $bln      = date('m/d');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    // $id_sumber='E20';
    // if ($id_dealer!=null) {
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $id_sumber = $dealer->kode_dealer_md;
    // }
    $get_data  = $this->db->query("SELECT * FROM tr_spk
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $no_spk = substr($row->no_spk, 9, 5);
      $new_kode   = $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $no_spk + 1) . '-' . $id_sumber;
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_spk', ['no_spk' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, 9, 5);
          $new_kode = $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $neww + 1) . '-' . $id_sumber;
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = $th_kecil . '/' . $bln . '/' . '00001-' . $id_sumber;
    }
    return strtoupper($new_kode);
  }

  function getSPKDokumen($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE spk.id_dealer = '$id_dealer' ";

    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND spk_d.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['key'])) {
      if ($filter['key'] != '') {
        $where .= " AND spk_d.key='{$filter['key']}'";
      }
    }

    if (isset($filter['path_or_file'])) {
      if ($filter['path_or_file'] != '') {
        $where .= " AND (spk_d.path='{$filter['path_or_file']}' OR spk_d.file='{$filter['path_or_file']}')";
      }
    }
    if (isset($filter['key_ms_null'])) {
      if ($filter['key_ms_null'] != '') {
        $where .= " AND ms_dc.key IS NULL";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk_gc LIKE '%$search%'
                            OR spk.nama_npwp LIKE '%$search%'
                            OR spk.no_npwp LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status LIKE '%$search%'
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

    $select = "spk_d.*,CONCAT('" . base_url() . "',spk_d.path) full_path";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(spk_d.id) AS count";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_spk_file spk_d
    JOIN tr_spk spk ON spk.no_spk=spk_d.no_spk
    LEFT JOIN sc_ms_document_spk ms_dc ON ms_dc.key=spk_d.key
		$where
    $order
    $limit
    ");
  }
  function getSPKDokumenWajib($filter = NULL)
  {
    $where = "WHERE 1=1";

    if (isset($filter['no'])) {
      if ($filter['no'] != '') {
        $where .= " AND spk.no='{$filter['no']}'";
      }
    }
    $base_url = base_url('assets/panel/files/');
    $path = "SELECT 
          CASE WHEN file IS NULL THEN path 
            ELSE file END 
          FROM tr_spk_file 
          WHERE no_spk='{$filter['no_spk']}' AND tr_spk_file.key IS NOT NULL AND tr_spk_file.key=ms_dc.key";

    $path_ktp = "SELECT 
            CASE WHEN file_foto='' OR file_foto IS NULL THEN '' 
            ELSE concat('$base_url',file_foto) END 
          FROM tr_spk WHERE no_spk='{$filter['no_spk']}' AND ms_dc.key='ktp'";
    $path_kk = "SELECT 
            CASE WHEN file_kk='' OR file_kk IS NULL THEN '' 
            ELSE concat('$base_url',file_kk) 
            END
          FROM tr_spk WHERE no_spk='{$filter['no_spk']}' AND ms_dc.key='kk'";
    return $this->db->query("SELECT *,
    CASE WHEN ($path) IS NULL THEN
      CASE WHEN ms_dc.key='kk' THEN ($path_kk) ELSE ($path_ktp) END
    ELSE ($path)
    END AS path
    FROM sc_ms_document_spk ms_dc
    WHERE active=1
    ");
  }

  function getSPKFollowUp($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE p.id_dealer = '$id_dealer' ";

    if (isset($filter['id'])) {
      if ($filter['id'] != '') {
        $where .= " AND pf.id='{$filter['id']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND pf.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where .= " AND p.no_spk_int='{$filter['no_spk_int']}'";
      }
    }
    $select = 'pf.*,fol.name metode_fol_up_text';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(pf.no_spk) AS count";
      }
    }

    $order = "ORDER BY pf.id ASC";
    if (isset($filter['order'])) {
      $order = $filter['order'];
    }
    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $result = $this->db->query("SELECT 
    $select
    FROM tr_spk_fol_up pf
    LEFT JOIN tr_spk p ON p.no_spk=pf.no_spk
    LEFT JOIN sc_ms_metode_follow_up fol ON fol.id=pf.activity_id
    $where
    $order
    $limit
    ");
    if (isset($filter['return'])) {
      if ($filter['return'] == 'for_service_concept') {
        $no = 1;
        $new_result = [];
        foreach ($result->result() as $rs) {
          $new_result[] = [
            'id' => $rs->id,
            'name' => 'Follow Up SPK ' . $no,
            'activity' => $rs->activity,
            'commited_date' => $rs->tgl_fol_up,
            'check_date' => $rs->check_date,
            'description' => $rs->description,
          ];
          $no++;
        }

        $filter_prospek = [
          'id_customer' => $filter['id_customer'],
          'id_dealer' => $filter['id_dealer'],
          'return' => 'for_service_concept'
        ];
        $prospek = $this->m_prospek->getProspekFollowUp($filter_prospek);
        $result = [
          'spk' => $new_result,
          'prospek' => $prospek
        ];
        return $result;
      }
    } else {
      return $result;
    }
  }

  function getSPKIndividuProduct($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE spk.id_dealer = '$id_dealer' ";

    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND spk.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['status_spk'])) {
      if ($filter['status_spk'] != '') {
        $where .= " AND spk.status_spk='{$filter['status_spk']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk_gc LIKE '%$search%'
                            OR spk.nama_npwp LIKE '%$search%'
                            OR spk.no_npwp LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status LIKE '%$search%'
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

    return $this->db->query("SELECT spk.*,tk.tipe_ahm,wr.warna,tk.id_tipe_kendaraan_int,wr.id_warna_int,
    (SELECT id_item FROM ms_item WHERE id_tipe_kendaraan=spk.id_tipe_kendaraan AND id_warna=spk.id_warna) AS id_item,
    (SELECT id_item_int FROM ms_item WHERE id_tipe_kendaraan=spk.id_tipe_kendaraan AND id_warna=spk.id_warna) AS id_item_int
    FROM tr_spk spk
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=spk.id_warna
		$where
    $order
    $limit
    ");
  }

  function getSPKIndividuAccessories($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE spk.id_dealer = '$id_dealer' ";

    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND spk.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_part'])) {
      if ($filter['id_part'] != '') {
        $where .= " AND prt.id_part='{$filter['id_part']}'";
      }
    }
    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1) IN ({$filter['id_karyawan_dealer_in']})";
      }
    }
    $select = 'spk_acc.*,prt.*';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_qty') {
        $select = "IFNULL(SUM(spk_acc.accessories_qty),0) AS tot";
      }
    }
    $join = '';
    if (isset($filter['join_sales_order'])) {
      $join = "JOIN tr_sales_order so ON so.no_spk=spk.no_spk";
    }
    if (isset($filter['so_invoice'])) {
      if ($filter['so_invoice'] != '') {
        $where .= " AND (so.tgl_cetak_invoice IS NOT NULL OR so.tgl_cetak_invoice !='')";
      }
    }
    $result = $this->db->query("SELECT $select
    FROM tr_spk_accessories spk_acc
    JOIN tr_spk spk ON spk.no_spk=spk_acc.no_spk
    JOIN ms_part prt ON prt.id_part=spk_acc.accessories_id
    $join
    $where
    ");
    return $result;
  }

  function getSPKIndividuApparel($filter = NULL)
  {
    $id_dealer = $filter['id_dealer'];
    $where = "WHERE spk.id_dealer = '$id_dealer' ";
    $join = '';

    if (isset($filter['no_spk_int'])) {
      if ($filter['no_spk_int'] != '') {
        $where .= " AND spk.no_spk_int='{$filter['no_spk_int']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND spk.no_spk='{$filter['no_spk']}'";
      }
    }

    if (isset($filter['id_part'])) {
      if ($filter['id_part'] != '') {
        $where .= " AND prt.id_part='{$filter['id_part']}'";
      }
    }

    if (isset($filter['id_karyawan_dealer_in'])) {
      if ($filter['id_karyawan_dealer_in'] != '') {
        $where .= " AND (SELECT id_karyawan_dealer FROM tr_prospek WHERE id_customer=spk.id_customer ORDER BY created_at DESC LIMIT 1) IN ({$filter['id_karyawan_dealer_in']})";
      }
    }
    if (isset($filter['join_sales_order'])) {
      $join = "JOIN tr_sales_order so ON so.no_spk=spk.no_spk";
    }
    if (isset($filter['so_invoice'])) {
      if ($filter['so_invoice'] != '') {
        $where .= " AND (so.tgl_cetak_invoice IS NOT NULL OR so.tgl_cetak_invoice !='')";
      }
    }

    $select = 'spk_app.*,prt.*';
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_qty') {
        $select = "IFNULL(SUM(spk_app.apparel_qty),0) AS tot";
      }
    }
    $result = $this->db->query("SELECT $select
    FROM tr_spk_apparel spk_app
    JOIN tr_spk spk ON spk.no_spk=spk_app.no_spk
    JOIN ms_part prt ON prt.id_part=spk_app.apparel_id
    $join
    $where
    ");
    return $result;
  }

  function getSPKActivity($id_karyawan_dealer, $user, $honda_id)
  {
    $f_act = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_spk' => get_ym(),
      'select' => 'count',
    ];
    $actual = $this->getSPK($f_act)->row()->count;

    $filter_spk_program = [
      'id_karyawan_dealer_in' => $id_karyawan_dealer == NULL ? '' : $id_karyawan_dealer,
      'id_dealer' => $user->id_dealer,
      'bulan_spk' => get_ym(),
      'ada_program' => true,
      'select' => 'count',
    ];
    $spk_dengan_program = $this->m_spk->getSPK($filter_spk_program)->row()->count;


    $this->load->model('m_dms');
    $f_target_spk = [
      'honda_id_in' => $honda_id == NULL ? '' : $honda_id,
      'id_dealer' => $user->id_dealer,
      'tahun' => get_y(),
      'bulan' => get_m(),
      'select' => 'sum_spk',
      'active' => 1,
    ];

    return [
      'actual' => (int)$actual,
      'spk_dengan_program' => (int)$spk_dengan_program,
      'target' => (int)$this->m_dms->getH1TargetManagement($f_target_spk)->row()->sum_spk,
    ];
  }
  function cekFormulirCDBSTNK($no_spk)
  {
    $res =  $this->db->query("SELECT 
    CASE 
      WHEN 
        spk.no_ktp IS NULL
        OR spk.no_ktp=''
        OR spk.id_customer IS NULL
        OR spk.id_customer=''
        OR spk.tgl_lahir IS NULL
        OR spk.tgl_lahir =''
        OR spk.alamat IS NULL
        OR spk.alamat =''
        OR spk.id_kelurahan IS NULL
        OR spk.id_kelurahan =''
        OR spk.id_kecamatan IS NULL
        OR spk.id_kecamatan =''
        OR spk.id_kabupaten IS NULL
        OR spk.id_kabupaten =''
        OR spk.id_provinsi IS NULL
        OR spk.id_provinsi =''
        OR spk.kodepos IS NULL
        OR spk.kodepos =''
        OR spk.id_kelurahan IS NULL
        OR spk.id_kelurahan =''
        OR cdb.agama IS NULL
        OR cdb.agama =''
        OR spk.pekerjaan IS NULL
        OR spk.pekerjaan =''
        OR spk.pengeluaran_bulan IS NULL
        OR spk.pengeluaran_bulan =''
        OR cdb.pendidikan IS NULL
        OR cdb.pendidikan =''
        OR spk.no_hp IS NULL
        OR spk.no_hp =''
        OR spk.no_telp IS NULL
        OR spk.no_telp =''
        OR cdb.sedia_hub IS NULL
        OR cdb.sedia_hub =''
        OR cdb.merk_sebelumnya IS NULL
        OR cdb.merk_sebelumnya =''
        OR cdb.jenis_sebelumnya IS NULL
        OR cdb.jenis_sebelumnya =''
        OR cdb.digunakan IS NULL
        OR cdb.digunakan =''
        OR spk.email IS NULL
        OR spk.email =''
        OR spk.status_rumah_id IS NULL
        OR spk.status_rumah_id =''
        OR spk.status_hp IS NULL
        OR spk.status_hp =''
        OR cdb.facebook IS NULL
        OR cdb.facebook =''
        OR cdb.twitter IS NULL
        OR cdb.twitter =''
        OR cdb.instagram IS NULL
        OR cdb.instagram =''
        OR cdb.youtube IS NULL
        OR cdb.youtube =''
        OR cdb.hobi IS NULL
        OR cdb.hobi =''
        OR spk.jenis_wn IS NULL
        OR spk.jenis_wn =''
        OR spk.no_kk IS NULL
        OR spk.no_kk =''
        OR spk.tempat_lahir IS NULL
        OR spk.tempat_lahir =''
      THEN 0
    ELSE 1 END AS count
    FROM tr_spk spk
    JOIN tr_cdb cdb ON cdb.no_spk=spk.no_spk
    WHERE spk.no_spk='$no_spk'
    ");
    if ($res->num_rows() > 0) {
      $r = $res->row();
      if ($r->count == 1) {
        return 'Lengkap';
      } else {
        return 'Belum Lengkap';
      }
    } else {
      return 'Belum Lengkap';
    }
  }

  function cetakSPK($id, $save_server = NULL)
  {
    $spk = $this->db->query("SELECT * FROM tr_spk 
						left join ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						left join ms_warna on tr_spk.id_warna = ms_warna.id_warna
				WHERE no_spk ='$id'");
    if ($spk->num_rows() > 0) {
      $row = $spk->row();
      $dt_kel        = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$row->id_kelurahan'");
      $kelurahan = '';
      if ($dt_kel->num_rows() > 0) {
        $dt_kel       = $dt_kel->row();
        $kelurahan    = $dt_kel->kelurahan;
        $id_kecamatan = $dt_kel->id_kecamatan;
      }
      $dt_kec        = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$row->id_kecamatan'");
      if ($dt_kec->num_rows() > 0) {
        $dt_kec = $dt_kec->row();
        $kecamatan     = $dt_kec->kecamatan;
        $id_kabupaten     = $dt_kec->id_kabupaten;
      } else {
        $kecamatan = '';
        $id_kabupaten = '';
      }
      $dt_kab        = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$row->id_kabupaten'");
      if ($dt_kab->num_rows() > 0) {
        $dt_kab = $dt_kab->row();
        $kabupaten    = $dt_kab->kabupaten;
        $id_provinsi  = $dt_kab->id_provinsi;
      } else {
        $kabupaten = '';
        $id_provinsi = '';
      }

      $dt_pro        = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$row->id_provinsi'")->row();

      if ($row->alamat_sama != 'Ya') {
        $dt_kel        = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$row->id_kelurahan2'");
        if ($dt_kel->num_rows() > 0) {
          $dt_kel = $dt_kel->row();
          $kelurahan2     = $dt_kel->kelurahan;
          $id_kecamatan2     = $dt_kel->id_kecamatan;
        } else {
          $kelurahan2 = '';
          $id_kecamatan2 = '';
        }
        $dt_kec        = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$row->id_kecamatan2'");
        if ($dt_kec->num_rows() > 0) {
          $dt_kec = $dt_kec->row();
          $kecamatan2     = $dt_kec->kecamatan;
          $id_kabupaten2     = $dt_kec->id_kabupaten;
        } else {
          $kecamatan2 = '';
          $id_kabupaten2 = '';
        }
        $dt_kab        = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$row->id_kabupaten2'");
        if ($dt_kab->num_rows() > 0) {
          $dt_kab = $dt_kab->row();
          $kabupaten2    = $dt_kab->kabupaten;
          $id_provinsi  = $dt_kab->id_provinsi;
        } else {
          $kabupaten2 = '';
          $id_provinsi = '';
        }
        $dt_pro2        = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$row->id_provinsi'")->row();
        $alamat2 = $row->alamat2;
      } else {
        $kelurahan2  = $kelurahan;
        $kecamatan2  = $kecamatan;
        $kabupaten2  = $kabupaten;
        $alamat2 =  $row->alamat;
      }
      $id_dealer = $row->id_dealer;
      $ap = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer);
      $r = $ap->row();
      if ($r->logo != "") {
        $logo = $r->logo;
      } else {
        $logo = "logo_sinsen.jpg";
      }
      $pdf = new PDF_HTML('p', 'mm', 'A4');
      $pdf->SetAutoPageBreak(false);
      $pdf->AddPage();
      // head	  
      $pdf->SetFont('ARIAL', 'B', 13);
      // $pdf->Image(base_url('assets/panel/images/' . $logo), 10, 8, -140);
      // $pdf->Image(base_url('assets/panel/images/' . $logo), 10, 8, 30, 18);
      if ($logo == "logo_sinsen.jpg") {
        $pdf->Image(base_url('assets/panel/images/' . $logo), 10, 8, 30);
      } else {
        $logo = base_url('assets/panel/images/') . $logo;
        if (file_exists($logo)) {
          $pdf->Image($logo, 10, 8, 29, 11);
        } else {
          $pdf->Image(base_url('assets/panel/images/logo_sinsen.jpg'), 10, 8, 30);
        }
      }
      $pdf->Cell(190, 6, 'SURAT PESANAN KENDARAAN', 0, 1, 'C');
      $pdf->SetFont('ARIAL', '', 10);

      $tgl = explode(' ', $row->created_at);
      $tgl = date('d-m-Y', strtotime($row->tgl_spk));
      $pdf->Cell(190, 5, "NOMOR : $row->no_spk        TANGGAL  : $tgl", 0, 1, 'C');
      /*$pdf->Cell(40, 5, ': '.$row->no_spk, 0, 0, 'L');
		  
	
		  
		  $pdf->Cell(18, 5, 'TANGGAL', 0, 0, 'L');$pdf->Cell(2, 5, ':', 0,0, 'C');$pdf->Cell(40, 5, $tgl, 0, 1, 'L');
		  */
      $pdf->Ln(3);
      $pdf->SetFont('ARIAL', 'B', 10);
      $pdf->Cell(190, 6, 'DATA KONSUMEN', 1, 1, 'C');
      $pdf->SetFont('ARIAL', '', 10);
      $pdf->Ln(2);
      $pdf->Cell(33, 5, 'NAMA PEMESAN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $row->nama_konsumen, 0, 0, 'L');
      $pdf->Cell(5, 5, '', 0, 0, 'L');
      $pdf->Cell(32, 5, 'TEMPAT LAHIR', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(50, 5,  $row->tempat_lahir, 0, 1, 'L');

      $pdf->Cell(33, 5, 'NOMOR KTP', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $row->no_ktp, 0, 0, 'L');
      $pdf->Cell(5, 5, '', 0, 0, 'L');
      $pdf->Cell(32, 5, 'TANGGAL LAHIR', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(50, 5, tgl_indo($row->tgl_lahir, ' '), 0, 1, 'L');
      $pdf->Ln(1);
      $pdf->Cell(33, 5, 'ALAMAT DOMISILI', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $row->alamat, 0, 1, 'L');

      $pdf->Cell(33, 5, 'KELURAHAN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $kelurahan, 0, 1, 'L');

      $pdf->Cell(33, 5, 'KECAMATAN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $kecamatan, 0, 1, 'L');

      $pdf->Cell(33, 5, 'KOTA/KABUPATEN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $kabupaten, 0, 1, 'L');
      $pdf->Ln(1);
      $pdf->Cell(33, 5, 'NOMOR TELP/HP', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $row->no_hp, 0, 1, 'L');
      $pdf->Ln(1);
      $pdf->Cell(33, 5, 'ALAMAT KTP', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $alamat2, 0, 1, 'L');

      $pdf->Cell(33, 5, 'KOTA/KABUPATEN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $kabupaten2, 0, 1, 'L');

      $pdf->Cell(33, 5, 'KELURAHAN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $kelurahan2, 0, 1, 'L');

      $pdf->Cell(33, 5, 'KECAMATAN', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $kecamatan2, 0, 0, 'L');
      $pdf->SetFont('ARIAL', '', 9);

      $lokasi = explode(',', $row->denah_lokasi);
      $latitude = str_replace(' ', '', $row->latitude);
      $longitude = str_replace(' ', '', $row->longitude);
      $qr_generate = "maps.google.com/local?q=$latitude,$longitude";
      $pdf->Cell(90, 6, $qr_generate, 0, 1, 'R');
      $pdf->SetFont('ARIAL', '', 10);
      $pdf->Cell(33, 5, 'NPWP', 0, 0, 'L');
      $pdf->Cell(5, 5, ':', 0, 0, 'C');
      $pdf->Cell(60, 5, $row->npwp, 0, 1, 'L');
      $pdf->Ln(1);
      $pdf->Image("https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77", 155, 46, 40, 0, 'PNG');
      $pdf->SetFont('ARIAL', 'B', 10);
      $pdf->Cell(190, 6, 'DATA KENDARAAN', 1, 1, 'C');
      $pdf->SetFont('ARIAL', '', 10);
      $pdf->Ln(2);



      $return = $this->m_admin->detail_individu($id);
      // send_json($return);


      $pdf->Cell(33, 5, 'Harga', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['harga']), 0, 0, 'L');
      $pdf->Cell(33, 5, 'Type', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, $row->id_tipe_kendaraan . "-" . $row->tipe_ahm, 0, 1, 'L');
      $pdf->Cell(33, 5, 'PPN', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['ppn']), 0, 0, 'L');
      $pdf->Cell(33, 5, 'Warna', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, $row->id_warna . "-" . $row->warna, 0, 1, 'L');
      $pdf->Cell(33, 5, 'Harga Off The Road', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['harga_off_road']), 0, 1, 'L');
      // $pdf->Cell(33, 5, 'Tahun', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(55, 5, $row->, 0, 1, 'L');
      $pdf->Cell(33, 5, 'Biaya Surat', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['bbn']), 0, 1, 'L');

      $pdf->Cell(33, 5, 'Harga On The Road', 0, 0, 'L');
      $pdf->Cell(2, 5, ':', 0, 0, 'L');
      $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['harga_on_road']), 0, 1, 'L');
      $pdf->Ln(2);
      $pdf->SetFont('ARIAL', 'B', 10);
      $pdf->Cell(190, 6, 'SISTEM PEMBELIAN', 1, 1, 'C');
      $pdf->Ln(2);
      $yy = 170;
      $program_umum = '';
      $program_g   = '';
      //	$program[0] = $row->program_umum==null?null:$row->program_umum;
      if ($row->program_umum != null or $row->program_umum != '') {
        $program_umum = $row->program_umum;
      }
      if ($row->program_gabungan != null or $row->program_gabungan != '') {
        $program_g = "&	 $row->program_gabungan";
      }

      if ($row->jenis_beli == 'Cash') {
        $yy = 191;
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->Cell(33, 5, 'PEMBELIAN TUNAI', 0, 1, 'L');
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Ln(1);
        $pdf->Cell(33, 5, 'Harga Tunai', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['harga_tunai']), 0, 1, 'L');
        $pdf->Cell(33, 5, 'Voucher', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['voucher']) . ' (' . $program_umum . ' ' . $program_g . ')', 0, 0, 'L');
        $pdf->Cell(33, 5, 'Voucher Tambahan', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['voucher_tambahan']), 0, 1, 'L');
        $pdf->Cell(33, 5, 'Total Bayar', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['total_bayar']), 0, 1, 'L');
      } elseif ($row->jenis_beli == 'Kredit') {
        $yy = 216;
        $finco        = $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$row->id_finance_company'");
        if ($finco->num_rows() > 0) {
          $t = $finco->row();
          $finance_company = $t->finance_company;
        } else {
          $finance_company  = "";
        }
        $pdf->SetFont('ARIAL', 'B', 10);
        $pdf->Cell(33, 5, 'PEMBELIAN KREDIT', 0, 1, 'L');
        $pdf->SetFont('ARIAL', '', 10);
        $pdf->Ln(1);
        $pdf->Cell(33, 5, 'Leasing / FINCO', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, $finance_company, 0, 1, 'L');
        //$pdf->Cell(33, 5, 'Lainnya', 0, 0, 'L'); $pdf->Cell(2, 5, ':', 0, 0, 'L');$pdf->Cell(55, 5, 'Rp. ', 0, 1, 'L');
        $kerja        = $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$row->pekerjaan'");
        if ($kerja->num_rows() > 0) {
          $tr = $kerja->row();
          $pekerjaan = $tr->pekerjaan;
        } else {
          $pekerjaan = "-";
        }
        $jabat        = $this->db->query("SELECT * FROM ms_jabatan WHERE id_jabatan = '$row->jabatan'");
        if ($jabat->num_rows() > 0) {
          $tr = $jabat->row();
          $jabatan = $tr->jabatan;
        } else {
          $jabatan = "-";
        }
        $pdf->Cell(33, 5, 'Uang Muka / DP', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($row->dp_stor) . ' (UANG MUKA / DP YANG DISETOR KONSUMEN)', 0, 1, 'L');
        $pdf->Cell(33, 5, 'Voucher', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['voucher2']) . ' (' . $program_umum . ' ' . $program_g . ')', 0, 1, 'L');
        $pdf->Cell(33, 5, 'Voucher Tambahan', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($return['voucher_tambahan']), 0, 1, 'L');
        $pdf->Cell(33, 5, 'Angsuran', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, 'Rp. ' . $this->mata_uang($row->angsuran), 0, 0, 'L');
        $pdf->Cell(33, 5, 'Tenor', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, $row->tenor, 0, 1, 'L');
        $pdf->Cell(33, 5, 'Pekerjaan', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, $pekerjaan, 0, 1, 'L');
        $pdf->Cell(33, 5, 'Jabatan', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, $jabatan, 0, 0, 'L');
        $pdf->Cell(33, 5, 'Lama Kerja', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, $row->lama_kerja, 0, 1, 'L');
        if ($row->penghasilan == '.' or $row->penghasilan == '-' or $row->penghasilan == null or $row->penghasilan == '') {
          $penghasilan = 0;
        } elseif ($row->penghasilan > 0) {
          $penghasilan = $this->mata_uang($row->penghasilan);
        } else {
          $penghasilan = 0;
        }
        $pdf->Cell(33, 5, 'Status Rumah', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, $row->status_rumah, 0, 0, 'L');
        $pdf->Cell(33, 5, 'Total Penghasilan', 0, 0, 'L');
        $pdf->Cell(2, 5, ':', 0, 0, 'L');
        $pdf->Cell(55, 5, "Rp. " . $penghasilan, 0, 1, 'L');
      }
      $pdf->Ln(2);
      $pdf->SetFont('ARIAL', 'B', 10);
      $pdf->Cell(190, 6, 'SYARAT DAN KETENTUAN', 1, 1, 'C');
      $pdf->Ln(2);
      $pdf->SetFont('ARIAL', '', 10);
      $pdf->Cell(5, 5, '1. ', 0, 0, 'L');
      $pdf->MultiCell(185, 5, 'HARGA yang tercantum dalam Surat Pesanan ini tidak mengikat dan Surat Pesanan ini bukan merupakan bukti pembayaran.', 0, 1);
      $pdf->Cell(5, 5, '2. ', 0, 0, 'L');
      $pdf->MultiCell(185, 5, 'Surat Pesanan ini dianggap SAH apabila telah ditandatangani oleh Pemesan, Sales Person, dan Kepala Cabang.', 0, 1);
      $pdf->Cell(5, 5, '3. ', 0, 0, 'L');
      $pdf->MultiCell(185, 5, 'Pembayaran dengan Cek/Bilyet Giro/Transfer dianggap sah apabila telah diterima di rekening :', 0, 1);
      $norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer WHERE id_dealer = '$row->id_dealer' ");
      $norek_dealer = $norek_dealer->num_rows() > 0 ? $norek_dealer->row()->id_norek_dealer : '';
      $detail_norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer_detail WHERE id_norek_dealer = '$norek_dealer' LIMIT 0,2");
      $x = 1;
      $cek = 0;
      $xx = 18;
      $count = 1;
      $count_isi = $detail_norek_dealer->num_rows();
      foreach ($detail_norek_dealer->result() as $key => $norek) {
        if ($count <= 2) {

          $bank = $this->db->query("SELECT * FROM ms_bank WHERE id_bank = '$norek->id_bank'")->row();
          $pdf->SetXY($xx, $yy);
          //	$pdf->MultiCell(70,5," Atas Nama \t\t\t\t: <b>$norek->nama_rek</b> \n Nama Bank \t\t\t: $bank->bank \n No Rekening \t: $norek->no_rek",0,'L');
          //$pdf->WriteHTML("Atas Nama\t\t\t\t\t\t\t: <b>$norek->nama_rek</b><br>
          // Nama Bank  \t\t\t\t: <b>$bank->bank</b><br>
          //No Rekening : <b> $norek->no_rek</b>
          //");
          $pdf->WriteHTML("Atas Nama\t\t\t\t\t\t\t: <b>$norek->nama_rek</b><br>");
          $pdf->SetX($xx);
          $pdf->WriteHTML("Nama Bank\t\t\t\t\t\t: $bank->bank<br>");
          $pdf->SetX($xx);
          $pdf->WriteHTML("No Rekening\t\t\t\t: $norek->no_rek<br>");
          if ($x < $count_isi) {
            $xx += 70;
            $pdf->SetXY($xx, $yy);
            $pdf->MultiCell(30, 5, "\n Atau \n", 0, 'L');
            $xx += 30;
          }
        }
        $count++;
        $x++;
      }
      $pdf->Ln(2);
      // $norek = implode(" atau ", $no_rekening);
      // $nama_rek = implode(" atau ", $nama_rek);
      $pdf->Cell(5, 5, '4. ', 0, 0, 'L');
      $pdf->MultiCell(185, 5, 'Pembayaran Tunai dianggap sah apabila telah diterbitkan kwitansi oleh ' . $r->nama_dealer . '.', 0, 1);
      $pdf->Cell(5, 5, '5. ', 0, 0, 'L');
      $pdf->MultiCell(185, 5, 'Pengiriman unit dan pengurusan surat-surat dilaksanakan setelah 100% harga kendaraan lunas.', 0, 1);
      //$pdf->Cell(5, 5, '6. ', 0, 0, 'L'); $pdf->MultiCell(185, 5, 'Nama dan Faktur STNK (BPKB) yang tercantum dalam Surat Pesanan ini TIDAK DAPAT DIRUBAH.', 0, 1);
      $pdf->Ln(3);
      $pdf->Cell(63, 5, 'PEMESAN,', 0, 0, 'C');
      $pdf->Cell(63, 5, 'SALES PERSON,', 0, 0, 'C');
      $pdf->Cell(63, 5, 'KEPALA CABANG,', 0, 1, 'C');
      $pdf->Ln(18);
      $karyawan = $this->db->query("SELECT nama_lengkap FROM tr_prospek 
								inner join ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
				WHERE id_customer='$row->id_customer'");
      $karyawan = $karyawan->num_rows() > 0 ? $karyawan->row()->nama_lengkap : '';
      $kacab = $this->db->query("SELECT pic FROM ms_dealer WHERE id_dealer='$id_dealer' ");
      $kacab = $kacab->num_rows() > 0 ? $kacab->row()->pic : '';

      $pdf->Cell(63, 5, '( ' . strtoupper($row->nama_konsumen) . ' )', 0, 0, 'C');
      $pdf->Cell(63, 5, '( ' . $karyawan . ' )', 0, 0, 'C');
      $pdf->Cell(63, 5, '( ' . $kacab . ' )', 0, 1, 'C');
      $pdf->SetFont('ARIAL', 'I', 8);
      // $pdf->Cell(63, 5, 'nama jelas', 0, 0, 'C');$pdf->Cell(63, 5, 'nama jelas', 0, 0, 'C');$pdf->Cell(63, 5,'nama jelas', 0, 1, 'C');
      // $save_server = 1;
      if ($save_server == NULL) {
        $pdf->Output();
      } else {
        $ym = date('Y/m/d');
        $path = "./uploads/document_spk/" . $ym;
        if (!is_dir($path)) {
          mkdir($path, 0777, true);
        }
        $rpl = str_replace('/', '-', $row->no_spk);
        $rpl = str_replace('./', '', $rpl);
        $filename = "$path/$rpl-form.pdf";
        $upd = ['document_spk_form' => $filename];
        $cond = ['no_spk' => $row->no_spk];
        $this->db->update('tr_spk', $upd, $cond);
        $pdf->Output($filename, 'F');
      }
    }
  }
  function getSalesProgram($filter = NULL)
  {
    $where = "WHERE 1=1";

    if (isset($filter['id_program_md'])) {
      if ($filter['id_program_md'] != '') {
        $where .= " AND sp.id_program_md='{$filter['id_program_md']}'";
      }
    }

    return $this->db->query("SELECT *
    FROM tr_sales_program sp
    $where
    ");
  }

  public function cek_bbn($params)
  {
    $id_tipe_kendaraan   = $params['id_tipe_kendaraan'];
    $id_warna   = $params['id_warna'];
    $tipe               = "Customer Umum";
    $cek_bbn = $this->db->query("SELECT * FROM ms_bbn_dealer WHERE id_tipe_kendaraan = '$id_tipe_kendaraan'");
    if ($cek_bbn->num_rows() > 0) {
      $te = $cek_bbn->row();
      $biaya_bbn = $te->biaya_bbn;
    } else {
      $biaya_bbn = 0;
    }
    $item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$id_tipe_kendaraan' AND id_warna = '$id_warna'");
    if ($item->num_rows() > 0) {
      $ty = $item->row();
      $id_item = $ty->id_item;
    } else {
      $id_item = "";
    }
    $date = date('Y-m-d');
    $cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md 
			INNER JOIN ms_kelompok_harga ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
			WHERE ms_kelompok_md.id_item = '$id_item' AND start_date <='$date' AND ms_kelompok_harga.target_market = '$tipe' ORDER BY start_date DESC LIMIT 0,1");
    if ($cek_harga->num_rows() > 0) {
      $ts = $cek_harga->row();
      $harga_jual = $ts->harga_jual;
    } else {
      $harga_jual = 0;
    }


    // if ($row->jenis_beli == 'Cash') {
    //   $voucher_tambahan = $row->voucher_tambahan_1 + $row->diskon;
    //   if ($row->the_road == 'On The Road') {
    //     $total_bayar = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan);
    //     $bbn = $row->biaya_bbn;
    //   } elseif ($row->the_road == 'Off The Road') {
    //     $total_bayar = $row->harga_off_road - ($row->voucher_1 + $voucher_tambahan);
    //     $bbn = 0;
    //   }
    //   $ho = $total_bayar - $row->biaya_bbn;
    // } else {
    //   $voucher_tambahan = $row->voucher_tambahan_2 + $row->diskon;
    //   if ($row->the_road == 'On The Road') {          
    //     $total_bayar = $row->harga_on_road - ($row->voucher_2 + $voucher_tambahan);
    //     $bbn = $row->biaya_bbn;
    //   } elseif ($row->the_road == 'Off The Road') {
    //     $total_bayar = $row->harga_off_road - ($row->voucher_2 + $voucher_tambahan);
    //     $bbn = 0;
    //   }
    //   //$ho = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan) - $row->biaya_bbn;
    //   $ho = $total_bayar - $row->biaya_bbn;
    // }                


    $harga     = floor($harga_jual / 1.1);
    $ppn       = floor(0.1 * $harga);
    $harga_on = $harga_jual + $biaya_bbn;
    $harga_tunai = $harga_on;
    // echo $biaya_bbn . "|" . $harga_on . "|" . $harga_jual . "|" . $ppn . "|" . $harga . "|" . $harga_tunai;
    return  [
      'biaya_bbn' => $biaya_bbn,
      'harga_on' => $harga_on,
      'harga_jual' => $harga_jual,
      'ppn' => $ppn,
      'harga' => $harga,
      'harga_tunai' => $harga_tunai,
    ];
  }

  public function get_nosin_fifo($id_dealer, $id_tipe_kendaraan, $id_warna)
  {
    $dt = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.* FROM tr_penerimaan_unit_dealer_detail
		JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
		JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
		JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
		JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer            
		LEFT JOIN tr_spk spk ON spk.no_mesin_spk=tr_penerimaan_unit_dealer_detail.no_mesin
		LEFT JOIN tr_sales_order so ON so.no_mesin=tr_penerimaan_unit_dealer_detail.no_mesin  
		LEFT JOIN tr_sales_order_gc_nosin so_gc ON so_gc.no_mesin= tr_penerimaan_unit_dealer_detail.no_mesin
		WHERE tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan' 
		AND tr_scan_barcode.warna = '$id_warna' 
		AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
		AND tr_scan_barcode.status = '4' 
		AND tr_scan_barcode.tipe = 'RFS'
		AND tr_penerimaan_unit_dealer.status = 'close'
		AND tr_penerimaan_unit_dealer_detail.jenis_pu='RFS'
		AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
		AND po_indent IS NULL
		AND spk.no_mesin_spk IS NULL AND so.no_mesin IS NULL AND so_gc.no_mesin IS NULL
		ORDER BY tr_penerimaan_unit_dealer_detail.fifo ASC LIMIT 1
                ");
    if ($dt->num_rows() > 0) {
      return $dt->row()->no_mesin;
    }
  }

  public function get_kode_indent($id_dealer)
  {
    $th               = date('Y');
    $bln              = date('m');
    $th_bln           = date('Y-m');
    $th_kecil         = date('y');
    $dmy              = date('dmy');
    // $id_sumber     ='E20';
    // if ($id_dealer !=null) {
    $dealer           = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $id_sumber        = $dealer->kode_dealer_md;
    // }
    $get_data  = $this->db->query("SELECT * FROM tr_po_dealer_indent
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $id_indent = substr($row->id_indent, -4);
      $new_kode   = 'INDENT/' . $id_sumber . '/' . $dmy . '/' . sprintf("%'.04d", $id_indent + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_po_dealer_indent', ['id_indent' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = 'INDENT/' . $id_sumber . '/' . $dmy . '/' . sprintf("%'.04d", $id_indent + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'INDENT/' . $id_sumber . '/' . $dmy . '/00001';
    }
    return strtoupper($new_kode);
  }

  public function newPO_ID($po_type, $id_dealer)
  {
    // $po_type = $this->input->get('type');
    // $id_dealer = $this->input->get('id_dealer');
    $th        = date('Y');
    $bln       = date('m');
    $th_bln    = date('Y-m');
    $thbln     = date('Ym');
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();

    $get_data  = $this->db->query("SELECT *,RIGHT(LEFT(created_at,7),2) as bulan FROM tr_po_dealer
		WHERE id_dealer='$id_dealer'
		ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      // $thbln_po = $row->tahun.'-'.sprintf("%'.02d",$row->bulan);
      // if ($th_bln==$thbln_po) {
      $id       = substr($row->id_po, -4);
      $new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $id + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_po_dealer', ['id_po' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -4);
          $new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
      // }else{
      // 	$new_kode = 'PO/'.$po_type.'/'.$dealer->kode_dealer_md.'/'.$thbln.'/0001';
      // }
    } else {
      $new_kode = 'PO/' . $po_type . '/' . $dealer->kode_dealer_md . '/' . $thbln . '/0001';
    }
    return strtoupper($new_kode);
    //echo strtoupper($new_kode);
  }
}
