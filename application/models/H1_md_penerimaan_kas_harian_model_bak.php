<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H1_md_penerimaan_kas_harian_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h1_md_pembayaran', 'm_bayar');
    $this->load->model('m_h1_md_spk', 'm_spk');
  }

  public function getDataDealer()
  {
	$dt_dealer=$this->db->query("SELECT id_dealer, kode_dealer_md, nama_dealer FROM ms_dealer WHERE h1=1 and active = 1  ORDER BY ms_dealer.nama_dealer ASC");
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
    $res_ = $this->m_bayar->getDealerInvoiceReceipt($filter);
    // send_json($res_->result());
    //var_dump($res_->result());
    $result = [];
    foreach ($res_->result() as $rs) {
      //var_dump($filter['id_dealer']);
      
      $filter_spk = ['no_spk' => $rs->no_spk, 'id_dealer'=>$filter['id_dealer']];
      $spk        = $this->m_spk->getSPK($filter_spk)->row();

      $tot_penerimaan = 0;
     
      if($spk->the_road == 'On The Road'){
        $bbn_real            = $spk->biaya_bbn;
      	$bbn            = $spk->biaya_bbn;
      	$sisa_bbn       = $spk->biaya_bbn;
      } else {
        $bbn_real = 0;
        $bbn = 0; 
        $sisa_bbn = 0;
      }

      $total_bayar    = $spk->total_bayar;

      $detail_penerimaan = [];
   
      $filter_detail = [
        'no_spk' => $rs->no_spk,  
        'id_dealer'=>$filter['id_dealer'],
        // 'created_at_lebih_kecil' => $rs->created_at,
        'jenis_invoice_in' => "'tjs'"
      ];

      //Cek TJS
      $get_tjs = $this->m_bayar->getDealerInvoiceReceiptDetail($filter_detail);
      $filter_detail['jenis_invoice_in'] = "'dp','pelunasan'";
      $filter_detail['get_first_kwitansi'] = true;
      //var_dump($filter_detail);
      $cek_first_kwitansi = $this->m_bayar->getDealerInvoiceReceipt($filter_detail)->row()->id_kwitansi;
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
   
      $get_detail = $this->m_bayar->getDealerInvoiceReceiptDetail($filter_detail);
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
        $summ_terima_sebelumnya = $this->m_bayar->getDealerInvoiceReceipt($filter)->row()->sum_amount;
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
    $res_ = $this->m_bayar->getDealerInvoiceReceipt($filter);
    // send_json($res_);
    $result = [];
    // send_json($res_->num_rows());
    foreach ($res_->result() as $rs) {
     
      $filter_detail = ['id_kwitansi' => $rs->id_kwitansi,'id_dealer'=>$filter['id_dealer']];
      $detail        = $this->m_bayar->getDealerInvoiceReceiptDetail($filter_detail)->result();
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
      	$spk        = $this->m_spk->getSPK($filter_spk)->row();


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

  
}
