<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_laporan extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h1_dealer_pembayaran', 'm_bayar');
    $this->load->model('m_h1_dealer_spk', 'm_spk');
  }

  function cekSalesDealer($filter)
  {
    return $this->m_admin->get_penjualan_inv($filter['periode'], $filter['tahun_bln'], $filter['id_tipe_kendaraan'], $filter['id_dealer']);
  }

  function getLaporanSalesStockMonitoring($filter)
  {
    $id_dealer = $filter['id_dealer'];
    $get_data = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND status <> '1' AND tipe='RFS') AS ready FROM ms_tipe_kendaraan having ready > 0");
    $no = 1;
    foreach ($get_data->result() as $row) {
      $cek_sl1   = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
				tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row();
      $cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
				LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
				WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();
      $cek_sl1_jum = $cek_sl1->jum;
      $cek_sl2_jum = $cek_sl2_1->jum;

      $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer 
				INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
				INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
				INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
				WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
				AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
        AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4")->row();

      $cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
				INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
				INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
				INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
				WHERE tr_picking_list_view.no_mesin NOT IN 
				(SELECT no_mesin FROM tr_surat_jalan_detail 										
				WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
        AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_do_po.id_dealer = '$id_dealer'")->row();
      if (isset($cek_unfill->jum)) {
        $unfill = $cek_unfill->jum;
      } else {
        $unfill  = 0;
      }

      $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item		      				
				WHERE tr_surat_jalan.status = 'proses' AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();

      $stock_market = $unfill + $cek_in->jum + $cek_qty->jum;;
      $total_stock  = $unfill + $cek_in->jum + $cek_qty->jum;

      $today        = gmdate("Y-m-d", time() + 60 * 60 * 7);
      $tahun_bln    = gmdate("Y-m", time() + 60 * 60 * 7);
      $tgl          = gmdate("d", time() + 60 * 60 * 7);
      $tgl_1        = date('d', strtotime('-1 days', strtotime($today)));
      $last_tgl     = gmdate("t", time() + 60 * 60 * 7);
      $tahun_bln_1  = date('Y-m', strtotime('-1 month', strtotime($tahun_bln)));

      $filter_sales = [
        'id_dealer' => $id_dealer,
        'periode' => 'bulan',
        'tahun_bln' => $tahun_bln_1,
        'id_tipe_kendaraan' => $row->id_tipe_kendaraan
      ];
      $sales_m_1 = $this->cekSalesDealer($filter_sales);
      $filter_sales['tahun_bln'] = $tahun_bln;
      $sales_m = $this->cekSalesDealer($filter_sales);

      $tg = date('d');
      $stock_r     = @($stock_market / $sales_m_1) * $tg;
      $stock_day = round($stock_r, 2);
      $pecah  = explode(".", $stock_day);
      if (isset($pecah[1])) {
        if ($pecah[1] / 100 > 0.5) {
          $stock_day_r = ceil($stock_day);
        } else {
          $stock_day_r = floor($stock_day);
        }
      } else {
        $stock_day_r = $stock_day;
      }
      $stock_days = ceil(@($total_stock / $sales_m_1));
      if ($unfill > 0 or $cek_in->jum > 0 or $cek_qty->jum > 0 or $total_stock > 0 or $sales_m_1 > 0 or $stock_days > 0) {
        $growth = $sales_m - $sales_m_1;
        $growth_persen = ceil(@($sales_m / $sales_m_1 - 1) * 100);
        $outlook = ceil(@($sales_m / ($tgl - 1)) * $last_tgl);
        $daily_sales_m_1 = ceil(@($sales_m_1 / $tgl_1));
        $daily_sales_m = ceil(@($sales_m / $tgl_1));
        $growth_persen_sales = ceil(@($daily_sales_m / $daily_sales_m_1 - 1) * 100);
        $res[] = [
          'no' => $no,
          'tipe_ahm' => $row->tipe_ahm,
          'sales_m_1' => $sales_m_1,
          'sales_m' => $sales_m,
          'growth' => $growth,
          'growth_persen' => $growth_persen,
          'outlook' => number_format($outlook, 2),
          'daily_sales_m_1' => number_format($daily_sales_m_1, 2),
          'daily_sales_m' => number_format($daily_sales_m, 2),
          'growth_persen_sales' => $growth_persen_sales,
          'stock_dealer' => $total_stock,
          'stock_days' => $stock_days,
        ];

        $no++;
      }
    }
    return $res;
  }

  function getLaporanDailySalesOnlySales($filter)
  {
    $start_tanggal = $filter['tahun_bulan'] . '-1';
    $end_tanggal   = $filter['tahun_bulan'] . '-' . date('t', strtotime($start_tanggal));
    while (strtotime($start_tanggal) <= strtotime($end_tanggal)) {
      $ymd  = date('Y-m-d', strtotime($start_tanggal));
      $tot_sales = $sales_reg + $sales_gc;
      $result[] = [
        'tanggal' => $ymd,
        'sales_reg' => $sales_reg,
        'sales_gc' => $sales_gc,
        'tot_sales' => $tot_sales
      ];
      $start_tanggal = date("Y-m-d", strtotime("+1 day", strtotime($start_tanggal)));
    }
  }

  function getLaporanDailySales($filter)
  {
    $begin_stock     = 0;
    $distribusi_md_d = 0;
    $tot_sales_reg       = 0;
    $tot_sales_gc        = 0;
    $grand_tot_sales       = 0;
    $end_stock       = 0;

    $start_tanggal = $filter['tahun_bulan'] . '-1';
    $end_tanggal   = $filter['tahun_bulan'] . '-' . date('t', strtotime($start_tanggal));
    while (strtotime($start_tanggal) <= strtotime($end_tanggal)) {
      $ymd  = date('Y-m-d', strtotime($start_tanggal));

      $sales_gc = 1;
      $tot_sales_gc += $sales_gc;

      $sales_reg = 1;
      $tot_sales_reg += $sales_reg;

      $tot_sales = $sales_gc + $sales_reg;
      $grand_tot_sales += $tot_sales;
      $res_sales[] = ['jumlah' => $tot_sales, 'tanggal' => $ymd];
      $start_tanggal = date("Y-m-d", strtotime("+1 day", strtotime($start_tanggal)));
    }
    return [
      'begin_stock'     => $begin_stock,
      'distribusi_md_d' => $distribusi_md_d,
      'tot_sales_reg'   => $tot_sales_reg,
      'tot_sales_gc'    => $tot_sales_gc,
      'grand_tot_sales' => $grand_tot_sales,
      'end_stock'       => $end_stock,
      'res_sales'       => $res_sales,
    ];
  }
}
