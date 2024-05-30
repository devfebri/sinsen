<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H1_md_penerimaan_kas_harian_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    // $this->load->model('m_h1_md_pembayaran', 'm_bayar');
    // $this->load->model('m_h1_md_spk', 'm_spk');
  }

  public function getDataDealer()
  {
	$dt_dealer=$this->db->query("SELECT * FROM ms_dealer WHERE  active = 1  ORDER BY ms_dealer.id_dealer ASC");
	return $dt_dealer->result();
  }

  function  getLaporanPenerimaanKasHarian($filter = NULL)
  {
    $dp_pelunasan = $this->getLaporanPenerimaanKasHarianTJSDpPelunasan($filter);
    $tjs          = $this->getLaporanPenerimaanKasHarianTJS($filter);
    $result =  ['dp_pelunasan' => $dp_pelunasan, 'tjs' => $tjs];
   
    return $result;
  }

  public function getLaporanPenerimaanKasHarianTJSDpPelunasan($filter)
  {
   
    $filter['jenis_invoice_in'] = "'dp','pelunasan'";
    // send_json($filter);
    $res_ = $this->getDealerInvoiceReceipt($filter);
    // send_json($res_->result());
    //var_dump($res_->result());
    $result = [];
    foreach ($res_->result() as $rs) {
      //var_dump($filter['id_dealer']);
      
      $filter_spk = ['no_spk' => $rs->no_spk, 'id_dealer'=>$filter['id_dealer']];
      $spk        = $this->getSPK($filter_spk)->row();

      $tot_penerimaan = 0;
     
      if($spk->the_road == 'On The Road'){
        $bbn_real       = $spk->biaya_bbn;
      	$bbn            = $spk->biaya_bbn;
      	$sisa_bbn       = $spk->biaya_bbn;
        $total_bayar    = $spk->total_bayar;
      } else {
        $total_bayar    = $spk->total_bayar - $spk->biaya_bbn;
        $bbn_real = 0;
        $bbn = 0; 
        $sisa_bbn = 0;
      }
      
      $detail_penerimaan = [];
   
      $filter_detail = [
        'no_spk' => $rs->no_spk,  
        'id_dealer'=>$filter['id_dealer'],
        // 'created_at_lebih_kecil' => $rs->created_at,
        'jenis_invoice_in' => "'tjs'"
      ];

      //Cek TJS
      $get_tjs = $this->getDealerInvoiceReceiptDetail($filter_detail);
      $filter_detail['jenis_invoice_in'] = "'dp','pelunasan'";
      $filter_detail['get_first_kwitansi'] = true;
      //var_dump($filter_detail);
      $cek_first_kwitansi = $this->getDealerInvoiceReceipt($filter_detail)->row()->id_kwitansi;
      $tot_nominal_tjs = 0;
      if ($get_tjs->num_rows() > 0) {
        // echo $cek_first_kwitansi;
        foreach ($get_tjs->result() as $tj) {
          //Cek BBNnmm
          if ($sisa_bbn > 0) {
            $sisa_bbn -= $tj->nominal;
            if ($sisa_bbn < 0) {
              $sisa_bbn = 0;
            }
          }

          if ($rs->id_kwitansi == $cek_first_kwitansi) {
            $detail_penerimaan[] = [
              'id_kwitansi'      => $tj->id_kwitansi,
              'jenis_invoice'    => $tj->jenis_invoice,
              'tgl_terima'       => date_dmy($tj->tgl_terima, '-'),
              'jenis_invoice'    => $tj->jenis_invoice,
              'tunai'            => 0,
              'ku'               => 0,
              'bg'               => 0,
              'bank'             => '',
              'no_rek_bg'        => '',
              'id_tjs'           => $tj->id_kwitansi,
              'tgl_tjs'          => $tj->tgl_pembayaran,
              'nominal_tjs'      => $tj->amount,
              'bank_tjs'         => $tj->bank,
              'no_rek_tjs'       => $tj->no_bg_cek,
              'tgl_transfer_tjs' => date_dmy($tj->tgl_terima, '-')
            ];
          }
          $tot_nominal_tjs += $tj->nominal;
        }
      }

      $bbn_dibayar_tjs    = $bbn - $sisa_bbn;
      $amount = $tot_nominal_tjs - $bbn;

      //Cek Detail Penerimaan
      $filter_detail = [
        'id_dealer'=>$filter['id_dealer'],
        'id_kwitansi' => $rs->id_kwitansi,
      ];
   
      $get_detail = $this->getDealerInvoiceReceiptDetail($filter_detail);
      $tot_penerimaan = 0;
      $sisa_bbn_awal = $sisa_bbn;
      foreach ($get_detail->result() as $dt) {
        //Cek BBN
        if ($sisa_bbn > 0) {
          $sisa_bbn -= $dt->nominal;
          if ($sisa_bbn < 0) {
            $sisa_bbn = 0;
          }
        }
        $tunai = 0;
        $ku = 0;
        $bg = 0;
        $bank = '';
        $no_rek_bg = '';
        if ($dt->metode_penerimaan == 'cash') {
          $tunai = $dt->nominal;
        } elseif ($dt->metode_penerimaan == 'kredit_transfer') {
          $ku = $dt->nominal;
          $bank = $dt->bank;
          $no_rek_bg = $dt->no_bg_cek;
        } elseif ($dt->metode_penerimaan == 'bg_cek') {
          $bg = $dt->nominal;
          $bank = $dt->bank;
          $no_rek_bg = $dt->no_bg_cek;
        }
        $detail_penerimaan[] = [
          'id_kwitansi'      => $dt->id_kwitansi,
          'jenis_invoice'    => $dt->jenis_invoice,
          'tgl_terima'       => date_dmy($dt->tgl_terima, '-'),
          'jenis_invoice'    => $dt->jenis_invoice,
          'tunai'            => $tunai,
          'ku'               => $ku,
          'bg'               => $bg,
          'bank'             => $bank,
          'no_rek_bg'        => $no_rek_bg,
          'id_tjs'           => '',
          'tgl_tjs'          => '',
          'nominal_tjs'      => '',
          'bank_tjs'         => '',
          'no_rek_tjs'       => '',
          'tgl_transfer_tjs' => ''
        ];
        $tot_penerimaan += $dt->nominal;
      }

      $amount = $tot_penerimaan;
      $diskon = 0;
      $summ_terima_sebelumnya = 0;
      if ($rs->id_kwitansi == $cek_first_kwitansi) {
        $diskon = $spk->diskon;
        $amount += $tot_nominal_tjs;
        $bbn = $bbn_real - $sisa_bbn;
        $amount -= $bbn;
      } else {
        $filter = [
          'no_spk' => $rs->no_spk,
          'select' => 'sum_amount',
          'jenis_invoice_in' => "'dp','pelunasan'",
          'id_dealer'=>$filter['id_dealer'],
          'created_at_lebih_kecil' => $rs->created_at
        ];
        $summ_terima_sebelumnya = $this->getDealerInvoiceReceipt($filter)->row()->sum_amount;
        $bbn = 0;
      }

      $sisa_piutang = $total_bayar - ($tot_nominal_tjs + $tot_penerimaan + $summ_terima_sebelumnya);
      $result[] = [
        'id_sales_order'    => $rs->id_sales_order,
        'id_kwitansi'       => $rs->id_kwitansi,
        'tgl_penerimaan'    => $rs->tgl_pembayaran,
        'nama_konsumen'     => $rs->nama_konsumen,
        'amount_dp'         => $rs->jenis_invoice == 'dp' ? $amount       : 0,
        'angsuran'          => $rs->jenis_invoice == 'pelunasan' ? $amount : 0,
        'denda'             => 0,
        'diskon'            => $diskon,
        'bbn'               => $bbn,
        'detail_penerimaan' => $detail_penerimaan,
        'sisa_piutang'      => $sisa_piutang
      ];
      // send_json($result);
    }
    return $result;
  }
  public function getLaporanPenerimaanKasHarianTJS($filter)
  {
    $filter['jenis_invoice_in'] = "'tjs'";
    // send_json($filter);
    $res_ = $this->getDealerInvoiceReceipt($filter);
    // send_json($res_);
    $result = [];
    // send_json($res_->num_rows());
    foreach ($res_->result() as $rs) {
     
      $filter_detail = ['id_kwitansi' => $rs->id_kwitansi,'id_dealer'=>$filter['id_dealer']];
      $detail        = $this->getDealerInvoiceReceiptDetail($filter_detail)->result();
      // send_json($detail);
      $detail_penerimaan = [];
      $tot_penerimaan = 0;
      foreach ($detail as $dt) {
        $tunai     = 0;
        $ku        = 0;
        $bg        = 0;
        $bank      = '';
        $no_rek_bg = '';

        if ($dt->metode_penerimaan == 'cash') {
          $tunai = $dt->nominal;
        } elseif ($dt->metode_penerimaan == 'kredit_transfer') {
          $ku = $dt->nominal;
          $bank = $dt->bank;
          $no_rek_bg = $dt->no_bg_cek;
        } elseif ($dt->metode_penerimaan == 'bg_cek') {
          $bg = $dt->nominal;
          $bank = $dt->bank;
          $no_rek_bg = $dt->no_bg_cek;
        }
        $detail_penerimaan[] = [
          'tgl_terima' => date_dmy($dt->tgl_terima, '-'),
          'tunai' => $tunai,
          'ku' => $ku,
          'bg' => $bg,
          'bank' => $bank,
          'no_rek_bg' => $no_rek_bg,
        ];
        $tot_penerimaan += $dt->nominal;
      }

      	$filter_spk = ['no_spk' => $rs->no_spk, 'id_dealer'=>$filter['id_dealer']];
      	$spk        = $this->getSPK($filter_spk)->row();


      if($spk->kategori_spk =='individu'){
      	$tk        = $this->db->get_where('ms_tipe_kendaraan', ['id_tipe_kendaraan' => $spk->id_tipe_kendaraan])->row();
      	$keterangan = "TITIPAN UANG U/1 UNIT MOTOR " . $tk->tipe_ahm;
      }else{
	$keterangan = "TITIPAN UANG GROUP CUSTOMER";
      }

      $result[] = [
        'id_sales_order' => $rs->no_spk,
        'id_kwitansi'    => $rs->id_kwitansi,
        'tgl_penerimaan' => $rs->tgl_pembayaran,
        'nama_konsumen'  => $rs->nama_konsumen,
        'amount' => $rs->amount,
        'keterangan' => $keterangan,
        'detail_penerimaan' => $detail_penerimaan,
      ];
    }
    return $result;
  }

  function getLaporanAR($filter)
  {
    $where_id = "WHERE 1=1 AND LEFT(so.created_at,10)>'2019-11-30'";
    $where_gc = "WHERE 1=1 AND LEFT(so_gc.created_at,10)>'2019-11-30'";
    if (isset($filter['id_dealer'])) {
      $where_id .= " AND so.id_dealer='{$filter['id_dealer']}'";
      $where_gc .= " AND so_gc.id_dealer='{$filter['id_dealer']}'";
    }
    if (isset($filter['id_sales_order'])) {
      if ($filter['id_sales_order'] != '') {
        $where_id .= " AND so.id_sales_order='{$filter['id_sales_order']}'";
        $where_gc .= " AND so_gc.id_sales_order_gc='{$filter['id_sales_order']}'";
      }
    }
    if (isset($filter['no_invoice'])) {
      if ($filter['no_invoice'] != '') {
        $where_id .= " AND so.no_invoice='{$filter['no_invoice']}'";
        $where_gc .= " AND so_gc.no_invoice='{$filter['no_invoice']}'";
      }
    }
    if (isset($filter['nama_konsumen'])) {
      if ($filter['nama_konsumen'] != '') {
        $nama_konsumen = $filter['nama_konsumen'];
        $where_gc .= " AND spk_gc.nama_npwp LIKE '%$nama_konsumen%'";
        $where_id .= " AND spk.nama_konsumen LIKE '%$nama_konsumen%'";
      }
    }
    if (isset($filter['finance_company'])) {
      if ($filter['finance_company'] != '') {
        $finance_company = $filter['finance_company'];
        $where_gc .= " AND finco.finance_company LIKE '%$finance_company%'";
        $where_id .= " AND finco.finance_company LIKE '%$finance_company%'";
      }
    }
    if (isset($filter['belum_lunas'])) {
      $where_id .= " AND 1 = CASE WHEN spk.jenis_beli='kredit' THEN
                                  CASE WHEN dp.status!='close' THEN 1 ELSE 0 END
                              ELSE CASE WHEN lunas.status!='close' THEN 1 ELSE 0 END
                              END
                    ";
      $where_gc .= " AND 1 = CASE WHEN spk_gc.jenis_beli='kredit' THEN
                          CASE WHEN dp.status!='close' THEN 1 ELSE 0 END
                      ELSE CASE WHEN lunas.status!='close' THEN 1 ELSE 0 END
                      END";
    }
    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'list_ar') {
          $order_column = ['id_sales_order', 'tgl_sales_order', 'no_invoice', 'nama_konsumen', 'finance_company', 'nilai_invoice', NULL, NULL, NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY tgl_sales_order DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    // $tot_penerimaan = "SELECT IFNULL(SUM(amount),0) FROM tr_h1_dealer_invoice_receipt rc WHERE rc.no_spk=spk.no_spk";
    $tot_penerimaan_gc = "SELECT IFNULL(SUM(amount),0) FROM tr_h1_dealer_invoice_receipt rc WHERE rc.no_spk=spk_gc.no_spk_gc";


    $nilai_invoice_id = sql_total_bayar_spk();

    // $nilai_invoice_id = 0;
    $tot_penerimaan = 0;
    $sisa_piutang = 0;
    $tot_penerimaan_gc = 0;
    $sisa_piutang_gc = 0;
    return $this->db->query("SELECT * FROM(
      SELECT 'so_individu' AS tipe, so.id_sales_order,LEFT(so.created_at,10) tgl_sales_order, so.no_invoice,spk.nama_konsumen,finance_company,
      $nilai_invoice_id AS nilai_invoice,
      ($tot_penerimaan) nominal_penerimaan,
      ($sisa_piutang) sisa_piutang,
      '' keterangan_pembayaran,
      spk.jenis_beli,
      spk.no_spk,
      dp.status AS status_dp,
      lunas.status AS status_lunas
      FROM tr_sales_order so
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      LEFT JOIN ms_finance_company finco ON finco.id_finance_company=spk.id_finance_company
      LEFT JOIN tr_invoice_dp dp ON dp.id_spk=spk.no_spk
      LEFT JOIN tr_invoice_pelunasan lunas ON lunas.id_spk=spk.no_spk
      $where_id
      UNION
      SELECT 'so_gc' AS tipe,
      so_gc.id_sales_order_gc,
      LEFT(so_gc.created_at,10) AS tgl_sales_order,
      so_gc.no_invoice,
      nama_npwp, finance_company,
      (" . sql_total_bayar_spk_gc_summary() . ") AS nilai_invoice,
      ($tot_penerimaan_gc) nominal_penerimaan,
      ($sisa_piutang_gc) sisa_piutang,
      '' keterangan_pembayaran,
      spk_gc.jenis_beli,
      spk_gc.no_spk_gc,
      dp.status,
      lunas.status
      FROM tr_sales_order_gc so_gc
      JOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc=so_gc.no_spk_gc
      LEFT JOIN ms_finance_company finco ON finco.id_finance_company=spk_gc.id_finance_company
      LEFT JOIN tr_invoice_dp dp ON dp.id_spk=spk_gc.no_spk_gc
      LEFT JOIN tr_invoice_pelunasan lunas ON lunas.id_spk=spk_gc.no_spk_gc
      $where_gc
    ) AS tabel $order $limit");
  }

  function getSPK($filter = NULL)
  {
    
    $dealer=dealer($filter['id_dealer']);

    $where_id  = "WHERE spk.id_dealer    = '$dealer->id_dealer' ";
    $where_gc  = "WHERE spk_gc.id_dealer = '$dealer->id_dealer' ";
   
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
      $where_id .= " AND spk.status_spk in ({$filter['status_in']})";
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
    if (isset($filter['ada_program'])) {
      $where_id .= " AND (spk.program_umum!='' OR spk.program_gabungan IS NOT NULL)";
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
        $select_id = "no_spk,spk.nama_konsumen,tgl_spk,spk.no_ktp, spk.the_road, spk.no_hp,spk.id_tipe_kendaraan,spk.id_warna,spk.jenis_beli," . sql_total_bayar_spk() . " AS total_bayar,spk.tanda_jadi,spk.created_at," . sql_diskon_spk() . " AS diskon,tk.tipe_ahm,wr.warna,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,tjs.id_invoice,tjs.created_at created_at_tjs,tjs_r.print_at,tjs_r.print_by,tjs_r.print_ke,spk.harga_tunai, 'individu' as kategori_spk";
        $join_additional_id = " LEFT JOIN tr_h1_dealer_invoice_receipt tjs_r ON tjs_r.id_invoice=tjs.id_invoice";

        $select_gc = "no_spk_gc,spk_gc.nama_npwp,tgl_spk_gc,spk_gc.no_npwp,spk_gc.on_road_gc as the_road, spk_gc.no_telp,'' AS id_tipe_kendaraan,AS id_warna,spk_gc.jenis_beli,(" . sql_total_bayar_spk_gc_summary() . ") AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,(" . sql_diskon_spk_gc_summary() . ") AS diskon,'' AS tipe_ahm,'' AS warna,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,tjs_gc.id_invoice,tjs_gc.created_at,tjs_gc_r.print_at,tjs_gc_r.print_by,tjs_gc_r.print_ke,0 AS harga_tunai, 'gc' as kategori_spk";
        $join_additional_gc = " LEFT JOIN tr_h1_dealer_invoice_receipt tjs_gc_r ON tjs_gc_r.id_invoice=tjs_gc.id_invoice";
      } elseif ($filter['select'] == 'count') {

        $select_id = "spk.no_spk";
        $select_gc = "spk_gc.no_spk_gc";
        $select = "COUNT(no_spk) AS count";
      }
    } else {
      $select_id = "no_spk,spk.nama_konsumen,spk.tgl_spk,spk.no_ktp,spk.no_hp,spk.the_road, spk.id_tipe_kendaraan,spk.id_warna,spk.jenis_beli," . sql_total_bayar_spk() . " AS total_bayar,spk.tanda_jadi,spk.created_at," . sql_diskon_spk() . " AS diskon,tk.tipe_ahm,wr.warna,kd.id_flp_md,kd.nama_lengkap,prp.id_karyawan_dealer,dp_stor,'individu' AS jenis_spk,prp.id_customer,spk.alamat,spk.harga_tunai,spk.biaya_bbn,spk.tenor,spk.angsuran,spk.id_finance_company,finco.finance_company, 'individu' as kategori_spk";
      // $dp_gc = 0;
      $select_gc = "spk_gc.no_spk_gc,spk_gc.nama_npwp,tgl_spk_gc,spk_gc.no_npwp,spk_gc.on_road_gc as the_road,spk_gc.no_telp,'' AS id_tipe_kendaraan,'' AS id_warna,spk_gc.jenis_beli,(" . sql_total_bayar_spk_gc_summary() . ") AS total_bayar,spk_gc.tanda_jadi,spk_gc.created_at,(" . sql_diskon_spk_gc_summary() . ") AS diskon,'' AS tipe_ahm,'' AS warna,kd_gc.id_flp_md,kd_gc.nama_lengkap,prp_gc.id_karyawan_dealer,($dp_gc) AS dp_stor,'gc' AS jenis_spk,prp_gc.id_prospek_gc,spk_gc.alamat,0 AS harga_tunai,0 AS biaya_bbn,($tenor_gc),($angsuran_gc),spk_gc.id_finance_company,finco_gc.finance_company,'gc' as kategori_spk";
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
  
  function getDealerInvoiceReceipt($filter = NULL)
  {
   
    $dealer=dealer($filter['id_dealer']);
    $where = "WHERE dir.id_dealer = '$dealer->id_dealer' ";

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND dir.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_invoice'])) {
      if ($filter['id_invoice'] != '') {
        $where .= " AND dir.id_invoice='{$filter['id_invoice']}'";
      }
    }
    if (isset($filter['id_kwitansi'])) {
      if ($filter['id_kwitansi'] != '') {
        $where .= " AND dir.id_kwitansi='{$filter['id_kwitansi']}'";
      }
    }
    if (isset($filter['tgl_pembayaran']) && isset($filter['tgl_pembayaran2'])) {
      if ($filter['tgl_pembayaran'] != '') {
        // $where .= " AND dir.tgl_pembayaran >='{$filter['tgl_pembayaran']}' AND dir.tgl_pembayaran <='{$filter['tgl_pembayaran2']}' ";
        $where .= " AND dir.tgl_pembayaran BETWEEN '{$filter['tgl_pembayaran']}' AND '{$filter['tgl_pembayaran2']}' ";
      }
    }else{
      if(isset($filter['tgl_pembayaran'])){
        if ($filter['tgl_pembayaran'] != '') {
          $where .= " AND dir.tgl_pembayaran = '{$filter['tgl_pembayaran']}' ";
        }
      }
    }

    if (isset($filter['created_at_lebih_kecil'])) {
      if ($filter['created_at_lebih_kecil'] != '') {
        $where .= " AND dir.created_at<'{$filter['created_at_lebih_kecil']}'";
      }
    }

    if (isset($filter['jenis_invoice_in'])) {
      if ($filter['jenis_invoice_in'] != '') {
        $where .= " AND dir.jenis_invoice IN({$filter['jenis_invoice_in']})";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk LIKE '%$search%'
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
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY dir.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    if (isset($filter['get_first_kwitansi'])) {
      $order = "ORDER BY dir.created_at ASC";
    }
    $select = "
      dir.id_kwitansi,dir.tgl_pembayaran,dir.amount,dir.cara_bayar,IFNULL(dir.nominal_lebih,0) AS nominal_lebih,dir.jenis_invoice,dir.print_ke,dir.id_kwitansi_int,
    CASE 
      WHEN tjs.nama_konsumen IS NOT NULL THEN tjs.nama_konsumen
      WHEN dp.nama_konsumen IS NOT NULL THEN dp.nama_konsumen
      WHEN lunas.nama_konsumen IS NOT NULL THEN lunas.nama_konsumen
    END AS nama_konsumen,
    CASE 
      WHEN tjs.no_ktp IS NOT NULL THEN tjs.no_ktp
      WHEN dp.no_ktp IS NOT NULL THEN dp.no_ktp
      WHEN lunas.no_ktp IS NOT NULL THEN lunas.no_ktp
    END AS no_ktp,
    CASE 
      WHEN tjs.alamat IS NOT NULL THEN tjs.alamat
      WHEN dp.alamat IS NOT NULL THEN dp.alamat
      WHEN lunas.alamat IS NOT NULL THEN lunas.alamat
    END AS alamat,
    CASE 
      WHEN tjs.id_customer IS NOT NULL THEN tjs.id_customer
      WHEN dp.id_customer IS NOT NULL THEN dp.id_customer
      WHEN lunas.id_customer IS NOT NULL THEN lunas.id_customer
    END AS id_customer,
    CASE 
      WHEN kd_tjs.id_flp_md IS NOT NULL THEN kd_tjs.id_flp_md
      WHEN kd_dp.id_flp_md IS NOT NULL THEN kd_dp.id_flp_md
      WHEN kd_lunas.id_flp_md IS NOT NULL THEN kd_lunas.id_flp_md
    END AS id_flp_md,
    CASE 
      WHEN kd_tjs.id_flp_md IS NOT NULL THEN kd_tjs.nama_lengkap
      WHEN kd_dp.id_flp_md IS NOT NULL THEN kd_dp.nama_lengkap
      WHEN kd_lunas.id_flp_md IS NOT NULL THEN kd_lunas.nama_lengkap
    END AS nama_lengkap,
    CASE 
      WHEN tjs.tgl_spk IS NOT NULL THEN tjs.tgl_spk
      WHEN dp.tgl_spk IS NOT NULL THEN dp.tgl_spk
      WHEN lunas.tgl_spk IS NOT NULL THEN lunas.tgl_spk
    END AS tgl_spk,
    dir.no_spk, dir.note,
    CASE 
      WHEN (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk) IS NOT NULL THEN 
        (SELECT is_paid FROM tr_sales_order WHERE no_spk=dir.no_spk)
      ELSE (SELECT is_paid FROM tr_sales_order_gc WHERE no_spk_gc=dir.no_spk and status_cetak !='reject')
    END AS is_paid,
    CASE 
      WHEN (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk) IS NOT NULL THEN 
        (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk)
      ELSE (SELECT id_sales_order_gc FROM tr_sales_order_gc WHERE no_spk_gc=dir.no_spk and status_cetak !='reject')
    END AS id_sales_order,
    (SELECT CONCAT(id_invoice,'|',LEFT(created_at,10),'|',amount) FROM tr_invoice_tjs WHERE id_spk=dir.no_spk LIMIT 1) AS detail_tjs,dir.created_at,
    CASE WHEN dir.cara_bayar='cash' THEN 1 ELSE 2 END AS cara_bayar_id
      ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_amount') {
        $select = "IFNULL(SUM(dir.amount),0) AS sum_amount";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_h1_dealer_invoice_receipt dir
    LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=dir.id_invoice
    LEFT JOIN tr_invoice_dp dp ON dp.id_invoice_dp=dir.id_invoice
    LEFT JOIN tr_invoice_pelunasan lunas ON lunas.id_inv_pelunasan=dir.id_invoice
    LEFT JOIN ms_karyawan_dealer kd_tjs ON kd_tjs.id_karyawan_dealer=tjs.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_dp ON kd_dp.id_karyawan_dealer=dp.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_lunas ON kd_lunas.id_karyawan_dealer=lunas.id_karyawan_dealer
		$where
    $order
    $limit
    ");
  }

  function  getDealerInvoiceReceiptDetail($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_kwitansi'])) {
      $where .= " AND rcp.id_kwitansi='{$filter['id_kwitansi']}'";
    }
    if (isset($filter['no_spk'])) {
      $where .= " AND rc.no_spk='{$filter['no_spk']}'";
    }
    if (isset($filter['created_at_lebih_kecil'])) {
      $where .= " AND rc.created_at<'{$filter['created_at_lebih_kecil']}'";
    }
    if (isset($filter['tgl_pembayaran_lebih_kecil'])) {
      $where .= " AND rc.tgl_pembayaran<'{$filter['tgl_pembayaran_lebih_kecil']}'";
    }

    if (isset($filter['tgl_pembayaran'])) {
      $where .= " AND rc.tgl_pembayaran='{$filter['tgl_pembayaran']}'";
    }
    if (isset($filter['jenis_invoice_in'])) {
      if ($filter['jenis_invoice_in'] != '') {
        $where .= " AND rc.jenis_invoice IN({$filter['jenis_invoice_in']})";
      }
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_nominal') {
        $select = "IFNULL(SUM(nominal),0) AS sum_nominal";
      }
    } else {
      $select = "rcp.*,bk.bank,rc.jenis_invoice,rc.tgl_pembayaran,rc.id_invoice,rc.amount ";
    }
    return $this->db->query("SELECT $select
    FROM tr_h1_dealer_invoice_receipt_pembayaran rcp 
    JOIN tr_h1_dealer_invoice_receipt rc ON rc.id_kwitansi=rcp.id_kwitansi
    LEFT JOIN ms_bank bk ON bk.id_bank=rcp.id_bank
    $where ORDER BY rc.created_at ASC");
  }
}
