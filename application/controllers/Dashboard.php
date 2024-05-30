<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url', 'string');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->helper('tgl_indo');
	}

	public function showDetailStok()
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			redirect(base_url(''), 'refresh');
			exit;
		}


		$query = $this->m_admin->getStokAll();
		$data = array();
		$no = 1;
		// while($row=mysqli_fetch_array($query) ) {  // preparing an array
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_&_Stock_Unit.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales & Stock Unit</p>';
			echo '<table border=1>';
		}
		echo '
		<thead>
		<tr>
		<th style="text-align:center;">Tipe</th>
		<th style="text-align:center;">Deskripsi</th>
		<th style="text-align:center;">Unfill AHM</th>
		<th style="text-align:center;">Int AHM</th>
		<th style="text-align:center;">Stok MD</th>
		<th style="text-align:center;">Unfill Dealer</th>
		<th style="text-align:center;">Int Dealer</th>
		<th style="text-align:center;">Stok Dealer</th>
		<th style="text-align:center;">Total Stok</th>
		<th style="text-align:center;">Stok Market</th>
		<th style="text-align:center;">Sales Dealer</th>
		<th style="text-align:center;">Stock Days</th>
		</tr>
		</thead>
		<tbody>
		';
		$tot = [
			'unfill_ahm' => 0,
			'int_ahm' => 0,
			'stok_md' => 0,
			'unfill_dealer' => 0,
			'int_dealer' => 0,
			'stok_dealer' => 0,
			'tot_stok' => 0,
			'stok_market' => 0,
			'sales_dealer' => 0,
			'stok_days' => 0
		];
		foreach ($query->result() as $row) {
			$no++;
			$id_tipe_kendaraan = $row->id_tipe_kendaraan;
			$tipe_ahm = $row->tipe_ahm;

			$rr = $row->unfill_md;
			$r2 = $row->intransit_md;
			$stok_md = $row->stok_md;
			$unfill = $row->unfill_dealer;
			$total_stock = $row->unfill_md + $row->intransit_md + $row->stok_md + $row->unfill_dealer + $row->intransit_dealer + $row->stok_dealer;
			$stock_market = $row->stok_md + $row->unfill_dealer + $row->intransit_dealer + $row->stok_dealer;

			// $cek_sales = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan);
			$cek_sales = $this->m_admin->get_penjualan_inv('bulan', date('Y-m'), $row->id_tipe_kendaraan);
			$tg = date('d');
			$stock_r 		= @($stock_market / $cek_sales) * $tg;
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
			$stock_days = ceil(@($stock_market / $cek_sales));

			if ($total_stock + $cek_sales > 0) {
				echo "
				<tr>
				<td>$id_tipe_kendaraan</td>
				<td>$tipe_ahm</td>
				<td>$rr</td>
				<td>$r2</td>
				<td>$stok_md</td>
				<td>$unfill</td>
				<td>$row->intransit_dealer</td>
				<td>$row->stok_dealer</td>
				<td>$total_stock</td>
				<td>$stock_market</td>
				<td>$cek_sales</td>
				<td>$stock_day_r</td>
				</tr>
				";
				$tot['unfill_ahm']    += $rr;
				$tot['int_ahm']       += $r2;
				$tot['stok_md']       += $stok_md;
				$tot['unfill_dealer'] += $unfill;
				$tot['int_dealer']    += $row->intransit_dealer;
				$tot['stok_dealer']   += $row->stok_dealer;
				$tot['tot_stok']      += $total_stock;
				$tot['stok_market']   += $stock_market;
				$tot['sales_dealer']  += $cek_sales;
			}
		}
		echo '</tbody>';
		echo '<tfoot>';
		echo '<th colspan=2 style="text-align:center;">Total</th>';
		echo '<th style="text-align:center;">' . $tot['unfill_ahm'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['int_ahm'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['stok_md'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['unfill_dealer'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['int_dealer'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['stok_dealer'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['tot_stok'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['stok_market'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['sales_dealer'] . '</th>';
		$tg = date('d');
		$stock_d 		= ($tot['stok_market'] / $tot['sales_dealer']) * $tg;
		$stock_days = round($stock_d, 2);
		$pecah  = explode(".", $stock_days);
		if (isset($pecah[1])) {
			if ($pecah[1] / 100 > 0.5) {
				$stock_days_r = ceil($stock_days);
			} else {
				$stock_days_r = floor($stock_days);
			}
		} else {
			$stock_days_r = $stock_days;
		}
		echo '<th style="text-align:center;">' . $stock_days_r . '</th>';
		echo '</tfoot>';
		if (isset($_GET['download'])) {
			echo '</table>';
		}
	}


	public function old_showDetailStok()
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			redirect(base_url(''), 'refresh');
			exit;
		}
		// getting total number records without any search
		$sql = "SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND status = '1' AND tipe='RFS') AS ready";
		$sql .= " FROM ms_tipe_kendaraan";
		$query = $this->db->query("$sql");
		$data = array();
		$no = 1;
		// while($row=mysqli_fetch_array($query) ) {  // preparing an array
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_&_Stock_Unit.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales & Stock Unit</p>';
			echo '<table border=1>';
		}
		echo '
				<thead>
	                <tr>
	                  <th style="text-align:center;">Tipe</th>
	                  <th style="text-align:center;">Deskripsi</th>
	                  <th style="text-align:center;">Unfill AHM</th>
	                  <th style="text-align:center;">Int AHM</th>
	                  <th style="text-align:center;">Stok MD</th>
	                  <th style="text-align:center;">Unfill Dealer</th>
	                  <th style="text-align:center;">Int Dealer</th>
	                  <th style="text-align:center;">Stok Dealer</th>
	                  <th style="text-align:center;">Total Stok</th>
	                  <th style="text-align:center;">Stok Market</th>
	                  <th style="text-align:center;">Sales Dealer</th>
	                  <th style="text-align:center;">Stock Days</th>
	                </tr>
	             </thead>
	             <tbody>
			';
		$tot = [
			'unfill_ahm' => 0,
			'int_ahm' => 0,
			'stok_md' => 0,
			'unfill_dealer' => 0,
			'int_dealer' => 0,
			'stok_dealer' => 0,
			'tot_stok' => 0,
			'stok_market' => 0,
			'sales_dealer' => 0,
			'stok_days' => 0
		];
		foreach ($query->result() as $row) {
			$cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND status = '2'")->row();
			$cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND status = '3'")->row();
			$cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'NRFS' AND status < 4")->row();
			$cek_booking2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'BOOKING' AND status = 1")->row();
			$cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'PINJAMAN' AND status < 4")->row();
			$cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb WHERE tr_sipb.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND RIGHT(tgl_sipb,4) = '2020'")->row();
			$cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list WHERE tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row();
			//$cek_in3 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();
			$sipb = 0;
			$total = $row->ready + $cek_booking2->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
			// $total = $row['ready'];
			if ($cek_in1->jum - $cek_in2->jum > 0) {
				$rr = $cek_in1->jum - $cek_in2->jum;
			} else {
				$rr = 0;
			}
			$cek_sl1 	= $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
                  tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row();
			$cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
                  LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
                  WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();
			$cek_sl3 	= $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list  		
							 		LEFT JOIN ms_tipe_kendaraan ON tr_shipping_list.id_modell = ms_tipe_kendaraan.id_tipe_kendaraan
							 		LEFT JOIN ms_warna ON tr_shipping_list.id_warna = ms_warna.id_warna
							 		WHERE ms_tipe_kendaraan.id_tipe_kendaraan = '$row->id_tipe_kendaraan'
							 		AND tr_shipping_list.no_mesin NOT IN (SELECT no_mesin FROM tr_scan_barcode)")->row();
			$cek_sl1_jum = $cek_sl1->jum;
			$cek_sl2_jum = $cek_sl2_1->jum;
			$cek_sl3_jum = $cek_sl3->jum;
			//if ($cek_sl1_jum - $cek_sl2_jum >= 0) {
			if ($cek_sl3_jum >= 0) {
				//$r2 = $cek_sl1_jum - $cek_sl2_jum;
				$r2 = $cek_sl3_jum;
			} else {
				$r2 = 0;
			}
			$stok_md = $total;
			// $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
			//     LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
			//     LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
			//     LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			//     LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
			//     LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
			//     WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'")->row();
			$cek_qty2 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
                AND tr_scan_barcode.status = '4'")->row();
			$cek_qty = $this->db->query("SELECT COUNT(DISTINCT(tr_scan_barcode.no_mesin)) AS jum FROM tr_penerimaan_unit_dealer 
                			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
                			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
                			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
                      WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
                			AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4")->row();
			//  $cek_unfill = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
			// LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
			// INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
			// LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
			// WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
			// AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();
			$cek_unfill2 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view
		      							LEFT JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
		      							LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do                        
                        INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'
                        ")->row();
			$cek_unfill1 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
								        INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
								        INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list AND tr_do_po_detail.id_item = tr_picking_list_view.id_item
								       	INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
								        WHERE tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
								        AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'
								        AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_picking_list_view.retur = 0")->row();

			$cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
								INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
								WHERE tr_picking_list_view.no_mesin NOT IN 
									(SELECT no_mesin FROM tr_surat_jalan_detail 										
										WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
								AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_picking_list_view.retur = 0")->row();
			if (isset($cek_unfill->jum)) {
				$unfill = $cek_unfill->jum;
			} else {
				$unfill	= 0 + $cek_pl->jum;
			}
			$unfill = $cek_unfill1->jum;


			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
		      				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
		      				INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
		              WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL AND status = 'close')
		              AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'
		              AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();
			$stock_market =  $stok_md + $unfill + $cek_in->jum + $cek_qty->jum;;
			$total_stock 	= $rr + $r2 + $stok_md + $unfill + $cek_in->jum + $cek_qty->jum;
			$tgl_bln      = gmdate("Y-m", time() + 60 * 60 * 7);
			$cek_sales = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan);
			$tg = date('d');
			$stock_r 		= @($stock_market / $cek_sales) * $tg;
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
			$stock_days = ceil(@($stock_market / $cek_sales));
			if ($rr > 0 or $r2 > 0 or $stok_md > 0 or $unfill > 0 or $cek_in->jum > 0 or $cek_qty->jum > 0 or $total_stock > 0 or $stock_market > 0 or $cek_sales > 0 or $stock_day_r > 0) {
				$no++;
				$id_tipe_kendaraan = $row->id_tipe_kendaraan;
				$tipe_ahm = $row->tipe_ahm;
				echo "
						<tr>
							<td>$id_tipe_kendaraan</td>
							<td>$tipe_ahm</td>
							<td>$rr</td>
							<td>$r2</td>
							<td>$stok_md</td>
							<td>$unfill</td>
							<td>$cek_in->jum</td>
							<td>$cek_qty->jum</td>
							<td>$total_stock</td>
							<td>$stock_market</td>
							<td>$cek_sales</td>
							<td>$stock_day_r</td>
						</tr>
					";
				$tot['unfill_ahm']    += $rr;
				$tot['int_ahm']       += $r2;
				$tot['stok_md']       += $stok_md;
				$tot['unfill_dealer'] += $unfill;
				$tot['int_dealer']    += $cek_in->jum;
				$tot['stok_dealer']   += $cek_qty->jum;
				$tot['tot_stok']      += $total_stock;
				$tot['stok_market']   += $stock_market;
				$tot['sales_dealer']  += $cek_sales;
			}
		}
		echo '</tbody>';
		echo '<tfoot>';
		echo '<th colspan=2 style="text-align:center;">Total</th>';
		echo '<th style="text-align:center;">' . $tot['unfill_ahm'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['int_ahm'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['stok_md'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['unfill_dealer'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['int_dealer'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['stok_dealer'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['tot_stok'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['stok_market'] . '</th>';
		echo '<th style="text-align:center;">' . $tot['sales_dealer'] . '</th>';
		$tg = date('d');
		$stock_d 		= ($tot['stok_market'] / $tot['sales_dealer']) * $tg;
		$stock_days = round($stock_d, 2);
		$pecah  = explode(".", $stock_days);
		if (isset($pecah[1])) {
			if ($pecah[1] / 100 > 0.5) {
				$stock_days_r = ceil($stock_days);
			} else {
				$stock_days_r = floor($stock_days);
			}
		} else {
			$stock_days_r = $stock_days;
		}
		echo '<th style="text-align:center;">' . $stock_days_r . '</th>';
		echo '</tfoot>';
		if (isset($_GET['download'])) {
			echo '</table>';
		}
	}
	public function showDetailStokDealer()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$name = $this->session->userdata('nama');
		if ($name == "") {
			redirect(base_url(''), 'refresh');
			exit;
		}
		// getting total number records without any search
		$sql = "SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND status <> '1' AND tipe='RFS') AS ready";
		$sql .= " FROM ms_tipe_kendaraan";
		$query = $this->db->query("$sql");
		$data = array();
		$no = 1;
		// while($row=mysqli_fetch_array($query) ) {  // preparing an array
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_&_Stock_Unit_Dealer.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales & Stock Unit Dealer</p>';
			echo '<table border=1>';
		}
		echo '
				<thead>
	                <tr align=center>
	                  <td><b>Tipe</td>
	                  <td><b>Deskripsi</td>	                  
	                  <td><b>Unfill Dealer</td>
	                  <td><b>Int Dealer</td>
	                  <td><b>Stok Dealer</td>
	                  <td><b>Total Stok</td>	                  
	                  <td><b>Sales Dealer</td>
	                  <td><b>Stock Days</td>
	                </tr>
	             </thead>
	             <tbody>
			';
		$tot = [
			'unfill_dealer' => 0,
			'int_dealer' => 0,
			'stok_dealer' => 0,
			'tot_stok' => 0,
			'sales_dealer' => 0,
			'stok_days' => 0
		];
		foreach ($query->result() as $row) {
			$cek_sl1 	= $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
                  tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row();
			$cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
                  LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
                  WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();
			$cek_sl1_jum = $cek_sl1->jum;
			$cek_sl2_jum = $cek_sl2_1->jum;

			$cek_qty2 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
                AND tr_scan_barcode.status = '4'")->row();
			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer 
                			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
                			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
                			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
                      WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
                      AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
                			AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4")->row();
			$cek_unfill2 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view
		      							LEFT JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
		      							LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do                        
                        INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'
                        ")->row();
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
				$unfill	= 0;
			}
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
		      				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item		      				
		              WHERE tr_surat_jalan.status ='proses' AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();
			$stock_market = $unfill + $cek_in->jum + $cek_qty->jum;;
			$total_stock 	= $unfill + $cek_in->jum + $cek_qty->jum;
			$tgl_bln      = gmdate("Y-m", time() + 60 * 60 * 7);
			$cek_sales = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer);
			$tg = date('d');
			$stock_r 		= @($stock_market / $cek_sales) * $tg;
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
			$stock_days = ceil(@($total_stock / $cek_sales));
			if ($unfill > 0 or $cek_in->jum > 0 or $cek_qty->jum > 0 or $total_stock > 0 or $cek_sales > 0 or $stock_days > 0) {
				$no++;
				$id_tipe_kendaraan = $row->id_tipe_kendaraan;
				$tipe_ahm = $row->tipe_ahm;
				echo "
						<tr align=center>
							<td>$id_tipe_kendaraan</td>
							<td>$tipe_ahm</td>
							<td>$unfill</td>
							<td>$cek_in->jum</td>
							<td>$cek_qty->jum</td>
							<td>$total_stock</td>
							<td>$cek_sales</td>
							<td>$stock_day_r</td>
						</tr>
					";
				$tot['unfill_dealer'] += $unfill;
				$tot['int_dealer']    += $cek_in->jum;
				$tot['stok_dealer']   += $cek_qty->jum;
				$tot['tot_stok']      += $total_stock;
				$tot['sales_dealer']  += $cek_sales;
			}
		}
		echo '</tbody>';
		echo '<tfoot align=center>';
		echo '<td colspan=2 align=center>Total</td>';
		echo '<td><b>' . $tot['unfill_dealer'] . '</td>';
		echo '<td><b>' . $tot['int_dealer'] . '</td>';
		echo '<td><b>' . $tot['stok_dealer'] . '</td>';
		echo '<td><b>' . $tot['tot_stok'] . '</td>';
		echo '<td><b>' . $tot['sales_dealer'] . '</td>';
		$tg = date('d');
		$stock_d 		= ($tot['tot_stok'] / $tot['sales_dealer']) * $tg;
		$stock_days = round($stock_d, 2);
		$pecah  = explode(".", $stock_days);
		if (isset($pecah[1])) {
			if ($pecah[1] / 100 > 0.5) {
				$stock_days_r = ceil($stock_days);
			} else {
				$stock_days_r = floor($stock_days);
			}
		} else {
			$stock_days_r = $stock_days;
		}
		echo '<td><b>' . $stock_days_r . '</td>';
		echo '</tfoot>';
		if (isset($_GET['download'])) {
			echo '</table>';
		}
	}
	public function showDetailStokDealer_old()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$name = $this->session->userdata('nama');
		if ($name == "") {
			redirect(base_url(''), 'refresh');
			exit;
		}
		// getting total number records without any search
		$sql = "SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND status <> '1' AND tipe='RFS') AS ready";
		$sql .= " FROM ms_tipe_kendaraan";
		$query = $this->db->query("$sql");
		$data = array();
		$no = 1;
		// while($row=mysqli_fetch_array($query) ) {  // preparing an array
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_&_Stock_Unit_Dealer.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales & Stock Unit Dealer</p>';
			echo '<table border=1>';
		}
		echo '
				<thead>
	                <tr align=center>
	                  <td><b>Tipe</td>
	                  <td><b>Deskripsi</td>	                  
	                  <td><b>Unfill Dealer</td>
	                  <td><b>Int Dealer</td>
	                  <td><b>Stok Dealer</td>
	                  <td><b>Total Stok</td>	                  	                  
	                </tr>
	             </thead>
	             <tbody>
			';
		$tot = [
			'unfill_dealer' => 0,
			'int_dealer' => 0,
			'stok_dealer' => 0,
			'tot_stok' => 0,
		];
		foreach ($query->result() as $row) {
			$cek_sl1 	= $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
                  tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row();
			$cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
                  LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
                  WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();
			$cek_sl1_jum = $cek_sl1->jum;
			$cek_sl2_jum = $cek_sl2_1->jum;

			$cek_qty2 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
                AND tr_scan_barcode.status = '4'")->row();
			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer 
                			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
                			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
                			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
                      WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
                      AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
                			AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4")->row();
			$cek_unfill2 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view
		      							LEFT JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
		      							LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do                        
                        INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'
                        ")->row();
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
				$unfill	= 0;
			}
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
		      				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item		      				
		              WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
		              AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();
			$stock_market = $unfill + $cek_in->jum + $cek_qty->jum;;
			$total_stock 	= $unfill + $cek_in->jum + $cek_qty->jum;
			$tgl_bln      = gmdate("Y-m", time() + 60 * 60 * 7);
			$cek_sales = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer);
			$tg = date('d');
			$stock_r 		= @($stock_market / $cek_sales) * $tg;
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
			$stock_days = ceil(@($total_stock / $cek_sales));
			if ($unfill > 0 or $cek_in->jum > 0 or $cek_qty->jum > 0 or $total_stock > 0 or $cek_sales > 0 or $stock_days > 0) {
				$no++;
				$id_tipe_kendaraan = $row->id_tipe_kendaraan;
				$tipe_ahm = $row->tipe_ahm;
				echo "
						<tr align=center>
							<td>$id_tipe_kendaraan</td>
							<td>$tipe_ahm</td>
							<td>$unfill</td>
							<td>$cek_in->jum</td>
							<td>$cek_qty->jum</td>
							<td>$total_stock</td>						
						</tr>
					";
				$tot['unfill_dealer'] += $unfill;
				$tot['int_dealer']    += $cek_in->jum;
				$tot['stok_dealer']   += $cek_qty->jum;
				$tot['tot_stok']      += $total_stock;
				$tot['sales_dealer']  += $cek_sales;
			}
		}
		echo '</tbody>';
		echo '<tfoot align=center>';
		echo '<td colspan=2 align=center>Total</td>';
		echo '<td><b>' . $tot['unfill_dealer'] . '</td>';
		echo '<td><b>' . $tot['int_dealer'] . '</td>';
		echo '<td><b>' . $tot['stok_dealer'] . '</td>';
		echo '<td><b>' . $tot['tot_stok'] . '</td>';
		echo '</tfoot>';
		if (isset($_GET['download'])) {
			echo '</table>';
		}
	}
	public function realtime_stok_md()
	{
		echo '
				<thead>
          <tr>              
            <td width="5%">No</td>
            <td>Action</td>
            <td>Kode Item</td>              
            <td>Tipe</td>              
            <td>Warna</td>              
            <td colspan="2" align="center">RFS</td>              
            <td>NRFS</td>              
            <td>Pinjaman</td>              
            <td>Unfill</td>              
            <td>Intransit</td>
            <td>Total</td>              
          </tr>           
          <tr>
            <td colspan="5"></td>
            <td>Ready</td>
            <td>Booking</td>            
            <td colspan="5"></td>              
          </tr>             
        </thead>
       <tbody>
		';
		// $sql = "SELECT DISTINCT(tr_scan_barcode.id_item),ms_dealer.kode_dealer_md,tr_do_po.id_dealer,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna";
		// $sql .= " FROM tr_scan_barcode LEFT JOIN tr_picking_list_view ON tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin";
		// $sql .= " LEFT JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list";
		// $sql .= " LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan";
		// $sql .= " LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna";
		// $sql .= " LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do";
		// $sql .= " LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer";
		// $sql .= " GROUP BY tr_scan_barcode.id_item,ms_dealer.id_dealer";
		// $sql .= " ORDER BY tr_scan_barcode.id_item ASC LIMIT 0,50";
		$a1 = 0;
		$a2 = 0;
		$a3 = 0;
		$a4 = 0;
		$a5 = 0;
		$a6 = 0;
		$a7 = 0;
		$a8 = 0;
		$sql2 = 'SELECT * FROM tr_real_stock ORDER BY id_item ASC';
		$sql2 = 'SELECT DISTINCT id_item, tipe_motor as id_tipe_kendaraan, warna as id_warna FROM tr_scan_barcode tsb where status !=5 ORDER BY id_item ASC';
		$query = $this->db->query("$sql2");
		$data = array();
		$no = 1;
		foreach ($query->result() as $isi) {
			$dt = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,ms_item.bundling,ms_item.id_item_lama,ms_item.id_warna_lama FROM ms_item 
						INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
						INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna 						
						WHERE ms_item.id_item = '$isi->id_item'");
			if ($dt->num_rows() > 0) {
				$r = $dt->row();
				$tipe_ahm = $r->tipe_ahm;
				$warna = $r->warna;
				$bundling = $r->bundling;
				$id_item_lama = $r->id_item_lama;
				$id_warna_lama = $r->id_warna_lama;
			} else {
				$tipe_ahm = "";
				$warna = "";
				$bundling = "";
				$id_item_lama = "";
				$id_warna_lama = "";
			}
			$cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'")->row();
			$cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '2'")->row();
			$cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '3'")->row();
			$cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'NRFS' AND status < 4")->row();
			$cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
			$cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                        WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode) 
                        AND id_modell = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'")->row();
			//$cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'")->row();
			if ($bundling == 'Ya') {
				$id_tipe = $id_item_lama;
				$id_warna = $id_warna_lama;
			} else {
				$id_tipe  = $isi->id_tipe_kendaraan;
				$id_warna = $isi->id_warna;
			}
			$cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE 
          tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'")->row();
			if ($bundling != 'Ya') {
				$cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$isi->id_warna'")->row();
				$cek_sl2_2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE ms_item.id_item_lama = '$isi->id_tipe_kendaraan' AND ms_item.id_warna_lama = '$isi->id_warna'")->row();
				if (isset($cek_sl2_2->jum)) {
					$jumlah_sl = $cek_sl2_1->jum + $cek_sl2_2->jum;
				} else {
					$jumlah_sl = $cek_sl2_1->jum;
				}
			} else {
				$cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
          JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item  
          WHERE tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' AND tr_scan_barcode.warna = '$isi->id_warna'")->row();
				$jumlah_sl = $cek_sl2->jum;
			}
			//$cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND warna = '$isi->id_warna'")->row();      			
			$cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
      	WHERE tr_sipb.id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tr_sipb.id_warna = '$isi->id_warna'
      	AND ms_item.bundling <> 'Ya'")->row();
			$cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
			 	WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
			 	AND ms_item.bundling <> 'Ya'")->row();
			$cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$isi->id_item'")->row();
			$sipb = 0;
			$total = $cek_ready->jum + $cek_booking->jum  + $cek_nrfs->jum  + $cek_pinjaman->jum;
			if ($cek_in1->jum - $cek_in2->jum > 0 and $cek_item->bundling != 'Ya') {
				$rr = ($cek_in1->jum - $cek_in2->jum) + $cek_pl->jum;
			} else {
				$rr = 0 + $cek_pl->jum;
			}
			$cek_sl2_jum = 0;
			$cek_sl1_jum = 0;
			if (isset($cek_sl1->jum)) $cek_sl1_jum = $cek_sl1->jum;
			if (isset($jumlah_sl)) $cek_sl2_jum = $jumlah_sl;
			if ($cek_sl1_jum - $cek_sl2_jum >= 0 and $cek_item->bundling != 'Ya') {
				$r2 = $cek_sl1_jum - $cek_sl2_jum;
			} else {
				$r2 = 0;
			}
			if ($total > 0) {
				echo "
					<tr>
	          <td>$no</td>
	          <td>
	            <a href='h1/realtime_stok/detail?id=$isi->id_item'>
	              <button type='button' title='Detail' class='btn bg-maroon btn-flat btn-sm'><i class='fa fa-eye'></i> Detail</button>
	            </a>
	          </td>
	          <td>$isi->id_item</td>
	          <td>$tipe_ahm</td>	          
	          <td>$warna</td>	          
	          <td>$cek_ready->jum</td>
	          <td>$cek_booking->jum</td>	          
	          <td>$cek_nrfs->jum</td>
	          <td>$cek_pinjaman->jum</td>              
	          <td>$rr</td>              
	          <td>$r2</td>              
	          <td>$total</td>              
	        </tr>
				";
				$a1 += $cek_ready->jum;
				$a2 += $cek_booking->jum;
				$a4 += $cek_nrfs->jum;
				$a5 += $cek_pinjaman->jum;
				$a6 += $rr;
				$a7 += $r2;
				$a8 += $total;
				$no++;
			}
		}
		echo '</tbody>';
		echo '<tfoot>';
		echo '<th colspan=5>Total</th>';
		echo '<th>' . $a1 . '</th>';
		echo '<th>' . $a2 . '</th>';
		echo '<th>' . $a4 . '</th>';
		echo '<th>' . $a5 . '</th>';
		echo '<th>' . $a6 . '</th>';
		echo '<th>' . $a7 . '</th>';
		echo '<th>' . $a8 . '</th>';
		echo '</tfoot>';
	}
	public function realtime_stok()
	{
		$sql = "SELECT DISTINCT(tr_scan_barcode.id_item),ms_dealer.kode_dealer_md,tr_do_po.id_dealer,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna";
		$sql .= " FROM tr_scan_barcode LEFT JOIN tr_picking_list_view ON tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin";
		$sql .= " JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list";
		$sql .= " JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan";
		$sql .= " JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna";
		$sql .= " JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do";
		$sql .= " JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer";
		$sql .= " where tr_scan_barcode.status !=5";
		$sql .= " GROUP BY tr_scan_barcode.id_item,ms_dealer.id_dealer";
		$sql .= " ORDER BY tr_scan_barcode.id_item ASC";
		$query = $this->db->query("$sql");
		$data = array();
		$no = 1;
		foreach ($query->result() as $isi) {
			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
           JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
           JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
           JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
           JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
           JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
          WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
          AND tr_penerimaan_unit_dealer_detail.retur = 0
          AND tr_scan_barcode.status = '4'")->row();
			$cek_unfill2 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
                  LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                  LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                  WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                  AND tr_do_po_detail.id_item = '$isi->id_item' AND tr_do_po.id_dealer = '$isi->id_dealer'")->row();
			$cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
								INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
								INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
								INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
								WHERE tr_picking_list_view.no_mesin NOT IN 
									(SELECT no_mesin FROM tr_surat_jalan_detail 										
										WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
								AND ms_item.id_item = '$isi->id_item' AND tr_do_po.id_dealer = '$isi->id_dealer'")->row();
			// SELECT *,tr_picking_list_view.no_mesin AS nosin FROM tr_picking_list
			// 	INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 		
			// LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
			// LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
			// LEFT JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin 		
			// LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			// WHERE tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE retur = 0
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                  WHERE tr_surat_jalan.status ='proses'
                  AND tr_surat_jalan_detail.id_item = '$isi->id_item' AND tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'")->row();
			$dr = $this->m_admin->getByID("ms_dealer", "id_dealer", $isi->id_dealer);
			if ($dr->num_rows() > 0) {
				$t = $dr->row();
				$nama_dealer = $t->nama_dealer;
				$kode_dealer_md = $t->kode_dealer_md;
			} else {
				$nama_dealer = "";
				$kode_dealer_md = "";
			}
			if ($cek_qty->jum > 0 or $cek_unfill->jum > 0 or $cek_in->jum > 0) {
				echo "
					<tr>
	          <td>$no</td>
	          <td>
	            <a href='h1/realtime_stok_d/detail?id=$isi->id_item&d=$isi->id_dealer'>
	              <button type='button' title='Detail' class='btn bg-maroon btn-flat btn-sm'><i class='fa fa-eye'></i> Detail</button>
	            </a>
	          </td>
	          <td>$isi->id_item</td>
	          <td>$kode_dealer_md</td>
	          <td>$nama_dealer</td>
	          <td>$isi->tipe_ahm</td>
	          <td>$isi->warna</td>
	          <td>$cek_qty->jum</td>
	          <td>$cek_unfill->jum</td>
	          <td>$cek_in->jum</td>              
	        </tr>
				";
				$no++;
			}
		}
	}
	public function history_spk()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		//$id_dealer = '65';
		$sql = "SELECT id_tipe_kendaraan,id_warna,id_customer,tr_spk.no_spk,alamat,no_ktp, status_spk 
		FROM tr_spk 
		LEFT JOIN tr_sales_order so ON so.no_spk=tr_spk.no_spk
		WHERE (so.id_sales_order IS NOT NULL OR tr_spk.status_spk='rejected' OR tr_spk.status_spk='canceled')
		";
		// $sql .= " WHERE (id_dealer = '$id_dealer' AND expired = 1) OR (id_dealer = '$id_dealer' AND no_spk IN(SELECT no_spk FROM tr_sales_order))";
		$sql .= " AND tr_spk.id_dealer='$id_dealer'";
		$sql .= " ORDER BY so.no_spk ASC";
		$query = $this->db->query("$sql");
		$data = array();
		$no = 1;
		foreach ($query->result() as $row) {
			$prospek = $this->m_admin->getByID("tr_prospek", "id_customer", $row->id_customer);
			$nama = ($prospek->num_rows() > 0) ? $prospek->row()->nama_konsumen : "";
			$tipe = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $row->id_tipe_kendaraan);
			$ahm = ($tipe->num_rows() > 0) ? $tipe->row()->tipe_ahm : "";
			$warna = $this->m_admin->getByID("ms_warna", "id_warna", $row->id_warna);
			$war = ($warna->num_rows() > 0) ? $warna->row()->warna : "";

			$btn_cetak = "
				<a  data-toggle='tooltip' title='Cetak' target='_blank' href='dealer/spk/cetak?id=$row->no_spk'>
	              <button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak SPK</button>
	            </a>
			";
			if ($row->status_spk == 'canceled') {
				$btn_cetak = "";
			}
			echo "
				<tr>
          <td>$no</td>
          <td><a href='" . base_url('dealer/spk/detail?id=') . "$row->no_spk'>$row->no_spk</a></td>
          <td>$nama</td>
          <td>$row->alamat</td>              
          <td>$ahm</td>
          <td>$war</td>            
          <td>$row->no_ktp</td>                       
          <td>
          	$btn_cetak
          </td>             
        </tr>
			";
			$no++;
		}
	}
	public function history_indent()
	{
		$sql = "SELECT tr_po_dealer_indent.*,ms_dealer.nama_dealer,tr_spk.nama_konsumen,tr_spk.no_ktp,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_spk.tanda_jadi FROM tr_po_dealer_indent ";
		$sql .= " INNER JOIN ms_warna ON tr_po_dealer_indent.id_warna =  ms_warna.id_warna";
		$sql .= " INNER JOIN ms_tipe_kendaraan ON tr_po_dealer_indent.id_tipe_kendaraan =  ms_tipe_kendaraan.id_tipe_kendaraan";
		$sql .= " INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer =  ms_dealer.id_dealer";
		$sql .= " LEFT JOIN tr_spk ON tr_po_dealer_indent.id_spk =  tr_spk.no_spk";
		$sql .= " WHERE (tr_po_dealer_indent.status = 'completed' OR tr_po_dealer_indent.status = 'canceled' OR tr_po_dealer_indent.status = 'closed')";
		$sql .= " ORDER BY tr_po_dealer_indent.tgl DESC";
		$query = $this->db->query("$sql");
		$data = array();
		$no = 1;
		foreach ($query->result() as $row) {
			$tgl = explode(' ', $row->tgl);
			$tgl = $tgl[0];
			$status = $row->status;
			if ($row->status == 'completed') {
				$status = "<span class='label label-primary'>Closed</span>";
			} elseif ($row->status == 'canceled') {
				$status = "<span class='label label-danger'>Canceled</span>";
			}
			echo "
				<tr>
          <td>$no</td>
          <td>$tgl</td>                            
          <td>
	          <a href='h1/indent/detail?id=$row->id_spk&h'>
	            $row->id_spk
	          </a>
          </td>          
          <td>$row->nama_dealer</td>                            
          <td>$row->nama_konsumen</td>                            
          <td>$row->no_ktp</td>              
          <td>$row->id_tipe_kendaraan - $row->tipe_ahm</td>              
          <td>$row->id_warna - $row->warna</td>                            
          <td>$row->tanda_jadi</td>                            
          <td>$status</td>            
        </tr>
			";
			$no++;
		}
	}
	function history_invoice()
	{
		echo '
				<thead>
          <tr>              
            <td width="5%">No</td>
            <td>No Faktur</td>
            <td>Tgl Faktur</td>              
            <td>No DO</td>              
            <td>Nama Customer</td>                          
            <td>Tgl Cair</td>              
            <td>Total</td>              
            <td>Status</td>                          
            <td colspan="10%">Aksi</td>              
          </tr>             
        </thead>
       <tbody>
		';
		$sql = "SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do ";
		$sql .= " WHERE tr_invoice_dealer.status_invoice = 'printable' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC";
		$query = $this->db->query("$sql");
		$no = 1;
		foreach ($query->result() as $row) {
			$group = $this->session->userdata("group");
			$print = $this->m_admin->set_tombol('invoice_dealer_unit', $group, 'print');
			if ($row->status_bayar == 'lunas') {
				$status_bayar = "<span class='label label-success'>Lunas</span>";
			} else {
				$status_bayar = "";
			}
			if ($row->status_invoice == 'waiting approval') {
				$status = "<span class='label label-warning'>$row->status_invoice</span>";
				//$tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/approve?id=$row->no_do\">Approve</a>";
				$tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" href=\"h1/invoice_dealer_unit/view?id=$row->no_do\" class=\"btn btn-success btn-xs btn-flat\">Approve</a>";
				$tampil2 = "<a $approval data-toggle=\"tooltip\" title=\"Reject Data\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/reject?id=$row->no_do\">Reject</a>";
				$tampil3 = "";
				$tampil4 = "";
			} elseif ($row->status_invoice == 'rejected' or $row->status_invoice == 'approved') {
				$status = "<span class='label label-danger'>$row->status_invoice</span>";
				$tampil2 = "";
				$tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
            onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>";
				$tampil = "";
				$tampil3 = "";
			} elseif ($row->status_invoice == 'printable') {
				$status = "<span class='label label-success'>$row->status_invoice</span>";
				$tampil2 = "";
				$tampil = "";
				$tampil4 = "";
				$tampil3 = "
        <button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
            onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>
        <a $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";
			}
			$rt = $this->m_admin->getByID("ms_dealer", "id_dealer", $row->id_dealer)->row();
			echo "
					<tr>
	          <td>$no</td>
	          <td>$row->no_faktur $status_bayar</td>
	          <td>$row->tgl_faktur</td>
	          <td>
	          	<a href='h1/invoice_dealer_unit/view?id=$row->no_do'>
                $row->no_do
              </a>
	          </td>
	          <td>$rt->nama_dealer</td>
	          <td>";
			$nosin = $this->m_admin->get_detail_inv_dealer($row->no_do);
			$total_bayar2 = $nosin['total_bayar'];
			echo $total_bayar2 . "</td>             
            <td>$row->tgl_cair</td>                                                        
            <td>$status</td>                                                        
            <td>";
			echo $tampil;
			echo $tampil2;
			echo $tampil3;
			echo $tampil4;
			echo "</td>                                          	          
	        </tr>
				";
			$no++;
		}
		echo '</tbody>';
	}
	function getDashboard()
	{
		$tanggal = $this->input->get('tgl');
		$result = $this->m_admin->get_data_dashboard($tanggal);
		echo json_encode($result);
	}
	function tanggal_kemarin($tanggal)
	{
		//Ambil Bulan Kemarin
		$ym = substr($tanggal, 0, 7);
		$bulan_kemarin =  $this->bulan_kemarin($ym);
		$last_bulan_ini = $this->last_date_month($tanggal);
		$tgl_bulan_kemarin = date('Y-m-d', strtotime($bulan_kemarin . '-' . substr($tanggal, 8, 2)));
		// if ($this->get_tgl($last_bulan_ini) == $this->get_tgl($tanggal)) {
		// 	return $this->last_date_month($bulan_kemarin);
		// }
		if ($this->get_tgl($tgl_bulan_kemarin) == $this->get_tgl($tanggal)) {
			return $tgl_bulan_kemarin;
		}
		if ($this->get_tgl($tgl_bulan_kemarin) < $this->get_tgl($tanggal)) {
			return $this->last_date_month($bulan_kemarin);
		}
	}

	function tanggal_tahun_kemarin($tanggal)
	{
		//Ambil Bulan Kemarin
		$ym = substr($tanggal, 0, 7);
		$bulan_kemarin =  $this->bulan_tahun_kemarin($ym);
		$last_bulan_ini = $this->last_date_month($tanggal);
		$tgl_bulan_kemarin = date('Y-m-d', strtotime($bulan_kemarin . '-' . substr($tanggal, 8, 2)));
		// if ($this->get_tgl($last_bulan_ini) == $this->get_tgl($tanggal)) {
		// 	return $this->last_date_month($bulan_kemarin);
		// }
		if ($this->get_tgl($tgl_bulan_kemarin) == $this->get_tgl($tanggal)) {
			return $tgl_bulan_kemarin;
		}
		if ($this->get_tgl($tgl_bulan_kemarin) < $this->get_tgl($tanggal)) {
			return $this->last_date_month($bulan_kemarin);
		}
	}

	function get_tgl($tanggal)
	{
		return substr($tanggal, 8, 2);
	}
	function last_date_month($tanggal)
	{
		$tanggal = substr($tanggal, 0, 7) . '-01';
		$date = new DateTime($tanggal);
		$date->modify('last day of this month');
		return $date->format('Y-m-d');
	}
	function bulan_kemarin($tanggal)
	{
		$tanggal = date_create($tanggal);
		// return $tanggal;
		date_add($tanggal, date_interval_create_from_date_string('-1 months'));
		return date_format($tanggal, 'Y-m');
	}

	function bulan_tahun_kemarin($tanggal)
	{
		$tanggal = date_create($tanggal);
		// return $tanggal;
		date_add($tanggal, date_interval_create_from_date_string('-12 months'));
		return date_format($tanggal, 'Y-m');
	}

	function getSegmentByCategory($tanggal, $kategori)
	{
		$bulan         = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$series[] = ['name' => '01-' . mediumdate_indo($tgl_akhir_min1, ' '), 'color' => '#3286f3'];
		$series[] = ['name' => '01-' . mediumdate_indo($tanggal, ' '), 'color' => '#ff7a57'];
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$where = '';
		if ($kategori != 'ALL TYPE') {
			$where = " AND ms_segment.id_kategori=(SELECT id_kategori FROM ms_kategori WHERE kategori='$kategori')";
		}
		$get_seg = $this->db->query("SELECT id_segment,segment FROM ms_segment 
			JOIN ms_kategori ON ms_kategori.id_kategori=ms_segment.id_kategori
			WHERE ms_segment.active=1 $where ORDER BY ms_kategori.kategori,ms_segment.order_by_dashboard");
		$bulan_at      = 0;
		$bulan_min1_at = 0;
		foreach ($get_seg->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, null, null, null, null, null, $rs->id_segment);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, null, null, null, null, null, null, null, $rs->id_segment);
			$categories[] = $rs->segment;
			// $series[0]['data'][]['y']=$bulan_kemarin;
			// $series[1]['data'][]['y']=$bulan_ini;
			$series[0]['data'][] = ['y' => $bulan_kemarin];
			$series[1]['data'][] = ['y' => $bulan_ini];
			$bulan_at += $bulan_ini;
			$bulan_min1_at += $bulan_kemarin;
		}
		$categories[] = 'TOTAL';
		// $series[0]['data'][]['y'] =$bulan_min1_at;
		// $series[1]['data'][]['y'] =$bulan_at;
		$series[0]['data'][] = ['y' => $bulan_min1_at];
		$series[1]['data'][] = ['y' => $bulan_at];
		return $result = ['categories' => $categories, 'series' => $series];
		// echo json_encode($result);
	}
	function getSegmentByCategoryDealer($tanggal, $kategori)
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$bulan         = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$series[] = ['name' => '01-' . mediumdate_indo($tgl_akhir_min1, ' '), 'color' => '#3286f3'];
		$series[] = ['name' => '01-' . mediumdate_indo($tanggal, ' '), 'color' => '#ff7a57'];
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$where = '';
		if ($kategori != 'ALL TYPE') {
			$where = " AND ms_segment.id_kategori=(SELECT id_kategori FROM ms_kategori WHERE kategori='$kategori')";
		}
		$get_seg = $this->db->query("SELECT id_segment,segment FROM ms_segment 
			JOIN ms_kategori ON ms_kategori.id_kategori=ms_segment.id_kategori
			WHERE ms_segment.active=1 $where ORDER BY ms_kategori.kategori,ms_segment.order_by_dashboard");
		$bulan_at      = 0;
		$bulan_min1_at = 0;
		foreach ($get_seg->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, $id_dealer, null, null, null, null, null, null, $rs->id_segment);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, $id_dealer, null, null, null, null, null, null, $rs->id_segment);
			$categories[] = $rs->segment;
			// $series[0]['data'][]['y']=$bulan_kemarin;
			// $series[1]['data'][]['y']=$bulan_ini;
			$series[0]['data'][] = ['y' => $bulan_kemarin];
			$series[1]['data'][] = ['y' => $bulan_ini];
			$bulan_at += $bulan_ini;
			$bulan_min1_at += $bulan_kemarin;
		}
		$categories[] = 'TOTAL';
		// $series[0]['data'][]['y'] =$bulan_min1_at;
		// $series[1]['data'][]['y'] =$bulan_at;
		$series[0]['data'][] = ['y' => $bulan_min1_at];
		$series[1]['data'][] = ['y' => $bulan_at];
		return $result = ['categories' => $categories, 'series' => $series];
		// echo json_encode($result);
	}
	function chartByCategoryDealer()
	{
		$tanggal    = $this->db->escape_str($this->input->post('tanggal'));
		$categories = $this->db->escape_str($this->input->post('categories'));
		$id_dealer 	= $this->m_admin->cari_dealer();
		if ($categories != null) {
			$result = $this->getSegmentByCategoryDealer($tanggal, $categories);
			echo json_encode($result);
			exit();
		}
		// $tanggal = '2019-12-12';
		$bulan = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$series[] = ['name' => '01-' . mediumdate_indo($tgl_akhir_min1, ' '), 'color' => '#3286f3'];
		$series[] = ['name' => '01-' . mediumdate_indo($tanggal, ' '), 'color' => '#ff7a57'];
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$get_ctg = $this->db->query("SELECT kategori,id_kategori FROM ms_kategori WHERE id_kategori IN('T','S','C','EV') ORDER BY kategori ASC");
		$bulan_at = 0;
		$bulan_min1_at = 0;
		foreach ($get_ctg->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, $id_dealer, null, $rs->id_kategori);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, $id_dealer, null, $rs->id_kategori);
			$categories[] = $rs->kategori;
			// $series[0]['data'][]['y']=$bulan_kemarin;
			// $series[1]['data'][]['y']=$bulan_ini;
			$series[0]['data'][] = ['y' => $bulan_kemarin];
			$series[1]['data'][] = ['y' => $bulan_ini];
			$bulan_at += $bulan_ini;
			$bulan_min1_at += $bulan_kemarin;
		}
		$categories[] = 'ALL TYPE';
		// $series[0]['data'][]['y'] =$bulan_min1_at;
		// $series[1]['data'][]['y'] =$bulan_at;
		$series[0]['data'][] = ['y' => $bulan_min1_at];
		$series[1]['data'][] = ['y' => $bulan_at];
		$result = ['categories' => $categories, 'series' => $series];
		echo json_encode($result);
	}
	function chartByCategory()
	{
		$tanggal    = $this->db->escape_str($this->input->post('tanggal'));
		$categories = $this->db->escape_str($this->input->post('categories'));
		if ($categories != null) {
			$result = $this->getSegmentByCategory($tanggal, $categories);
			echo json_encode($result);
			exit();
		}
		// $tanggal = '2019-12-12';
		$bulan = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$series[] = ['name' => '01-' . mediumdate_indo($tgl_akhir_min1, ' '), 'color' => '#3286f3'];
		$series[] = ['name' => '01-' . mediumdate_indo($tanggal, ' '), 'color' => '#ff7a57'];
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$get_ctg = $this->db->query("SELECT kategori,id_kategori FROM ms_kategori WHERE id_kategori IN('T','S','C','EV') ORDER BY kategori ASC");
		$bulan_at = 0;
		$bulan_min1_at = 0;
		foreach ($get_ctg->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, $rs->id_kategori);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, null, null, $rs->id_kategori);
			$categories[] = $rs->kategori;
			// $series[0]['data'][]['y']=$bulan_kemarin;
			// $series[1]['data'][]['y']=$bulan_ini;
			$series[0]['data'][] = ['y' => $bulan_kemarin];
			$series[1]['data'][] = ['y' => $bulan_ini];
			$bulan_at += $bulan_ini;
			$bulan_min1_at += $bulan_kemarin;
		}
		$categories[] = 'ALL TYPE';
		// $series[0]['data'][]['y'] =$bulan_min1_at;
		// $series[1]['data'][]['y'] =$bulan_at;
		$series[0]['data'][] = ['y' => $bulan_min1_at];
		$series[1]['data'][] = ['y' => $bulan_at];
		$result = ['categories' => $categories, 'series' => $series];
		echo json_encode($result);
	}

	function chartByCategoryOfYear()
	{
		$tanggal    = $this->input->post('tanggal');
		$categories = $this->input->post('categories');
		if ($categories != null) {
			$result = $this->getSegmentByCategory($tanggal, $categories);
			echo json_encode($result);
			exit();
		}
		// $tanggal = '2019-12-12';
		$bulan = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_tahun_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_tahun_kemarin($tanggal);

		$series[] = ['name' => '01-' . mediumdate_indo($tgl_akhir_min1, ' '), 'color' => '#3286f3'];
		$series[] = ['name' => '01-' . mediumdate_indo($tanggal, ' '), 'color' => '#ff7a57'];

		//set ambil value tahun lalu
		$tahun_lalu = date('Y')-1;
		$tahun_skrg = date('Y');
		$januari_tahun_lalu = "$tahun_lalu-01-01";
		$januari_tahun_skrg = "$tahun_skrg-01-01";

		// $tanggal_arr = [$bulan . '-01', $tanggal];
		// $tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];

		$tanggal_arr_min1 = [$januari_tahun_lalu, $tgl_akhir_min1];
		$tanggal_arr = [$januari_tahun_skrg, $tanggal];
		

		$get_ctg = $this->db->query("SELECT kategori,id_kategori FROM ms_kategori WHERE id_kategori IN('T','S','C') ORDER BY kategori ASC");
		$bulan_at = 0;
		$bulan_min1_at = 0;

		// $tanggal_arr_min1 = ['2020-10-01','2020-10-11'];
		// log_data($tanggal_arr_min1);
		// log_r($tanggal_arr);

		foreach ($get_ctg->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, $rs->id_kategori);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, null, null, $rs->id_kategori);
			$categories[] = $rs->kategori;
			// $series[0]['data'][]['y']=$bulan_kemarin;
			// $series[1]['data'][]['y']=$bulan_ini;
			$series[0]['data'][] = ['y' => $bulan_kemarin];
			$series[1]['data'][] = ['y' => $bulan_ini];
			$bulan_at += $bulan_ini;
			$bulan_min1_at += $bulan_kemarin;
		}
		$categories[] = 'ALL TYPE';
		// $series[0]['data'][]['y'] =$bulan_min1_at;
		// $series[1]['data'][]['y'] =$bulan_at;
		$series[0]['data'][] = ['y' => $bulan_min1_at];
		$series[1]['data'][] = ['y' => $bulan_at];
		$result = ['categories' => $categories, 'series' => $series];
		echo json_encode($result);
	}


	function getTotalDistribusiOfYear()
	{
		$tanggal = date('Y-m-d');

		$tgl_akhir_min1 = $this->tanggal_tahun_kemarin($tanggal);

		//set ambil value tahun lalu
		$tahun_lalu = date('Y')-1;
		$tahun_skrg = date('Y');
		$januari_tahun_lalu = "$tahun_lalu-01-01";
		$januari_tahun_skrg = "$tahun_skrg-01-01";

		$tanggal_arr_min1 = [$januari_tahun_lalu, $tgl_akhir_min1];
		$tanggal_arr = [$januari_tahun_skrg, $tanggal];

		// $tanggal_arr_min1 = ['2020-10-01','2020-10-11'];
		// log_data($tanggal_arr_min1);
		// log_r($tanggal_arr);

		$tahun_ini_retail_sales = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr, null, null, null, null);
		$tahun_kemarin_retail_sales = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr_min1, null, null, null, null);
		if($tahun_kemarin_retail_sales==0 || $tahun_ini_retail_sales==0){
			$growth_retail_sales = -1 * 100;
		}else{
			$growth_retail_sales = ($tahun_ini_retail_sales/$tahun_kemarin_retail_sales - 1) * 100;
		}

		$tahun_ini_ahm_to_md = $this->m_admin->get_ahm_to_md($tanggal_arr)->num_rows();
		$tahun_kemarin_ahm_to_md = $this->m_admin->get_ahm_to_md($tanggal_arr_min1)->num_rows();
		if($tahun_kemarin_ahm_to_md==0 || $tahun_ini_ahm_to_md==0){
			$growth_ahm_to_md = -1 * 100;		
		}else{
			$growth_ahm_to_md = ($tahun_ini_ahm_to_md/$tahun_kemarin_ahm_to_md - 1) * 100;
		}

		$tahun_ini_md_to_dealer = $this->m_admin->get_md_to_dealer($tanggal_arr)->num_rows();
		$tahun_kemarin_md_to_dealer = $this->m_admin->get_md_to_dealer($tanggal_arr_min1)->num_rows();
		if($tahun_ini_md_to_dealer==0 || $tahun_kemarin_md_to_dealer==0){		
			$growth_md_to_dealer = -1 * 100;		
		}else{
			$growth_md_to_dealer = ($tahun_ini_md_to_dealer/$tahun_kemarin_md_to_dealer - 1) * 100;
		}

		// $result = [
		// 	'code' => '0',
		// 	'pesan' => 'success',
		// 	'data' => [
		// 		'retail_sales' => [
		// 			'tahun_lalu' => $tanggal_arr_min1,
		// 			'tahun_sekarang' => $tanggal_arr,
		// 			'total_lalu' => $tahun_kemarin_retail_sales,
		// 			'total_sekarang' => $tahun_ini_retail_sales,
		// 			'growth' => number_format($growth_retail_sales,2).' %'
		// 		],
		// 		'ahm_to_md' => [
		// 			'tahun_lalu' => $tanggal_arr_min1,
		// 			'tahun_sekarang' => $tanggal_arr,
		// 			'total_lalu' => $tahun_ini_ahm_to_md,
		// 			'total_sekarang' => $tahun_ini_ahm_to_md,
		// 			'growth' => number_format($growth_ahm_to_md,2).' %'
		// 		]
		// 	]
		// ];
		// echo json_encode($result);

		?>
		<table class="table table-bordered table-hovered table-striped" style="font-size: 10pt; width:100%">
            <thead>
                <tr>
                    <th style="text-align: center;">KPI</th>
                    <th style="text-align: center;">Y-1</th>
                    <th style="text-align: center;">Y</th>
                    <th style="text-align: center;">Growth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Retail Sales</td>
                    <td><?php echo $tahun_kemarin_retail_sales ?></td>
                    <td><?php echo $tahun_ini_retail_sales ?></td>
                    <td><?php echo number_format($growth_retail_sales,2).' %' ?></td>
                </tr>
                <tr>
                    <td>Distribution AHM to MD</td>
                    <td><?php echo $tahun_kemarin_ahm_to_md ?></td>
                    <td><?php echo $tahun_ini_ahm_to_md ?></td>
                    <td><?php echo number_format($growth_ahm_to_md,2).' %' ?></td>
                </tr>
                <tr>
                    <td>Distribution MD to D</td>
                    <td><?php echo $tahun_kemarin_md_to_dealer ?></td>
                    <td><?php echo $tahun_ini_md_to_dealer ?></td>
                    <td><?php echo number_format($growth_md_to_dealer,2).' %' ?></td>
                </tr>
            </tbody>
        </table>
		<?php

	}

	function getTotalDistribusiOfYearDealer()
	{
		$tanggal = date('Y-m-d');
		$id_dealer = $this->m_admin->cari_dealer();

		$tgl_akhir_min1 = $this->tanggal_tahun_kemarin($tanggal);

		//set ambil value tahun lalu
		$tahun_lalu = date('Y')-1;
		$tahun_skrg = date('Y');
		$januari_tahun_lalu = "$tahun_lalu-01-01";
		$januari_tahun_skrg = "$tahun_skrg-01-01";

		$tanggal_arr_min1 = [$januari_tahun_lalu, $tgl_akhir_min1];
		$tanggal_arr = [$januari_tahun_skrg, $tanggal];

		// $tanggal_arr_min1 = ['2020-10-01','2020-10-11'];
		// log_data($tanggal_arr_min1);
		// log_r($tanggal_arr);

		$tahun_ini_retail_sales = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, $id_dealer, null, null);
		$tahun_kemarin_retail_sales = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, $id_dealer, null, null);
		if($tahun_ini_retail_sales==0 || $tahun_kemarin_retail_sales==0){		
			$growth_retail_sales = -1 * 100;		
		}else{
			$growth_retail_sales = ($tahun_ini_retail_sales/$tahun_kemarin_retail_sales - 1) * 100;
		}
		$tahun_ini_md_to_dealer = $this->m_admin->get_md_to_dealer($tanggal_arr, $id_dealer)->num_rows();
		$tahun_kemarin_md_to_dealer = $this->m_admin->get_md_to_dealer($tanggal_arr_min1, $id_dealer)->num_rows();
		if($tahun_ini_md_to_dealer==0 || $tahun_kemarin_md_to_dealer==0){		
			$growth_md_to_dealer = -1 * 100;		
		}else{
			$growth_md_to_dealer = ($tahun_ini_md_to_dealer/$tahun_kemarin_md_to_dealer - 1) * 100;
		}
		?>
		<div class="table-responsive">
		<table class="table table-bordered table-hovered table-striped" style="font-size: 10pt; width:100%">
            <thead>
                <tr>
                    <th style="text-align: center;">KPI</th>
                    <th style="text-align: center;">Y-1</th>
                    <th style="text-align: center;">Y</th>
                    <th style="text-align: center;">Growth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Retail Sales</td>
                    <td><?php echo $tahun_kemarin_retail_sales ?></td>
                    <td><?php echo $tahun_ini_retail_sales ?></td>
                    <td><?php echo number_format($growth_retail_sales,2).' %' ?></td>
                </tr>
                <tr>
                    <td>Distribution MD to D</td>
                    <td><?php echo $tahun_kemarin_md_to_dealer ?></td>
                    <td><?php echo $tahun_ini_md_to_dealer ?></td>
                    <td><?php echo number_format($growth_md_to_dealer,2).' %' ?></td>
                </tr>
            </tbody>
        </table>
        </div>
		<?php

	}


	function getFinco($bulan)
	{
		/*
					-- 			SELECT * FROM (
   --                          SELECT id_finance_company 
   --                          FROM tr_sales_order
   --                          JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
   --                          INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
   --                          JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.`id_tipe_kendaraan`=tr_scan_barcode.tipe_motor
   --                          WHERE LEFT(tgl_cetak_invoice,7)='$bulan' AND id_finance_company IS NOT NULL
   --                          GROUP BY tr_spk.id_finance_company
   --                          UNION
   --                          SELECT id_finance_company 
   --                          FROM tr_sales_order_gc_nosin  
   --                          JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
   --                          JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
   --                          INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
   --                          JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.`id_tipe_kendaraan`=tr_scan_barcode.tipe_motor
   --                          WHERE LEFT(tgl_cetak_invoice,7)='$bulan' AND id_finance_company IS NOT NULL
   --                          GROUP BY id_finance_company
   --                      ) AS tabel
			-- INNER JOIN ms_finance_company ON ms_finance_company.id_finance_company = tabel.id_finance_company
		*/
		$get_finco = $this->db->query("
			SELECT * FROM ms_finance_company
			WHERE ms_finance_company.active=1
					");
		return $get_finco;
	}
	function getDistrict($bulan)
	{
		// $district = $this->db->query("SELECT * FROM (
		//                           SELECT tr_spk.id_kabupaten, kabupaten 
		//                           FROM tr_sales_order
		//                           INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
		//                           INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_sales_order.id_dealer
		//                           INNER JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
		//                           INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
		//                           INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
		//                           -- WHERE LEFT(tgl_cetak_invoice,7)='$bulan'
		//                           GROUP BY kabupaten
		//                           UNION
		//                           SELECT tr_spk_gc.id_kabupaten, kabupaten 
		//                           FROM tr_sales_order_gc
		//                           INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
		//                           INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_sales_order_gc.id_dealer
		//                           INNER JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
		//                           INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
		//                           INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
		//                           -- WHERE LEFT(tgl_cetak_invoice,7)='$bulan'
		//                           GROUP BY kabupaten
		//                       ) AS tabel GROUP BY kabupaten
		// 			");
		$district = $this->db->query("SELECT ms_kabupaten.id_kabupaten,
			CASE 
				WHEN kabupaten='KAB. TANJUNG JABUNG BARAT' THEN 'TANJABBAR'
				WHEN kabupaten='KAB. TANJUNG JABUNG TIMUR' THEN 'TANJABTIM'
				WHEN kabupaten='KAB. MUARO JAMBI' THEN 'MA. JAMBI'
				ELSE kabupaten
			END AS kabupaten
		 FROM ms_dealer 
			JOIN ms_kelurahan ON ms_kelurahan.id_kelurahan=ms_dealer.id_kelurahan
            JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan=ms_kelurahan.id_kecamatan
            JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten=ms_kecamatan.id_kabupaten
			WHERE active=1 AND h1=1
			GROUP BY ms_kabupaten.id_kabupaten
			ORDER BY kabupaten ASC
			");
		return $district;
	}
	// function chartByFinco()
	// {
	// 	$tanggal = $this->input->post('tanggal');
	// 	$tanggal = '2019-12-19';
	// 	$bulan = date("Y-m", strtotime($tanggal));
	// 	$bulan_kemarin = date('Y-m', strtotime('-1 months', strtotime($tanggal)));
	// 	$series[]=['name'=>'M-1','color'=>'#3286f3'];
	// 	$series[]=['name'=>'M','color'=>'#ff7a57'];
	// 	$get_finco = $this->getFinco($bulan);
	// 	foreach ($get_finco->result() as $rs) {
	// 		$bulan_ini = $this->m_admin->get_penjualan_inv('bulan', $bulan, null, null, null,null,$rs->id_finance_company);
	// 		$bulan_kemarin = $this->m_admin->get_penjualan_inv('bulan', $bulan_kemarin, null, null, null,null,$rs->id_finance_company);
	// 		$categories[] = $rs->finance_company;
	// 		$series[0]['data'][]=$bulan_kemarin;
	// 		$series[1]['data'][]=$bulan_ini;
	// 	}
	// 	$result = ['categories'=>$categories,'series'=>$series];
	// 	echo json_encode($result);
	// }
	function chartByFinco()
	{
		$tanggal = $this->db->escape_str($this->input->post('tanggal'));
		// $tanggal = '2019-12-19';
		$bulan = date("Y-m", strtotime($tanggal));
		// /$bulan_kemarin = date('Y-m', strtotime('-1 months', strtotime($tanggal)));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$series[] = ['name' => 'M-1', 'color' => '#3286f3'];
		$series[] = ['name' => 'M', 'color' => '#ff7a57'];
		$tanggal_arr      = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$get_finco = $this->getFinco($bulan);
		foreach ($get_finco->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr, null, null, null, null, $rs->id_finance_company);
			$bulan_kemarin = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr_min1, null, null, null, null, $rs->id_finance_company);
			$finco[] = ['finco' => $rs->finance_company, 'm' => $bulan_ini, 'm1' => $bulan_kemarin];
			// $categories[] = $rs->finance_company;
			// $series[0]['data'][]=$bulan_kemarin;
			// $series[1]['data'][]=$bulan_ini;
		}
		array_multisort(array_map(function ($element) {
			return $element['m'];
		}, $finco), SORT_DESC, $finco);
		foreach ($finco as $fc) {
			if ($fc['m1'] > 0 or $fc['m'] > 0) {
				$categories[] = $fc['finco'];
				$series[0]['data'][] = $fc['m1'];
				$series[1]['data'][] = $fc['m'];
			}
		}
		$result = ['categories' => $categories, 'series' => $series];
		echo json_encode($result);
	}
	function chartByFincoDealer()
	{
		$tanggal = $this->db->escape_str($this->input->post('tanggal'));
		//$tanggal = '2020-05-15';
		$id_dealer = $this->m_admin->cari_dealer();
		$bulan = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$series[] = ['name' => 'M-1', 'color' => '#3286f3'];
		$series[] = ['name' => 'M', 'color' => '#ff7a57'];
		$tanggal_arr      = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$get_finco = $this->getFinco($bulan);
		foreach ($get_finco->result() as $rs) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, $id_dealer, null, null, $rs->id_finance_company);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, $id_dealer, null, null, $rs->id_finance_company);
			if ($bulan_ini > 0 or $bulan_kemarin > 0) {
				$finco[] = ['finco' => $rs->finance_company, 'm' => $bulan_ini, 'm1' => $bulan_kemarin];
			}
		}
		array_multisort(array_map(function ($element) {
			return $element['m'];
		}, $finco), SORT_DESC, $finco);
		foreach ($finco as $fc) {
			if ($fc['m1'] > 0 or $fc['m'] > 0) {
				$categories[] = $fc['finco'];
				$series[0]['data'][] = $fc['m1'];
				$series[1]['data'][] = $fc['m'];
			}
		}
		$result = ['categories' => $categories, 'series' => $series];
		echo json_encode($result);
	}

	function chartByContribution()
	{
		$tanggal = $this->db->escape_str($this->input->post('tanggal'));
		$bulan = date("Y-m", strtotime($tanggal));
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$get_finco = $this->getFinco($bulan);
		foreach ($get_finco->result() as $rs) {
			$hari_ini = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr, null, null, null, null, $rs->id_finance_company);
			$data[] = ['name' => $rs->finance_company, 'y' => $hari_ini];
		}
		array_multisort(array_map(function ($element) {
			return $element['y'];
		}, $data), SORT_DESC, $data);
		$no = 1;
		$new_data[4]['y'] = 0;
		foreach ($data as $rs) {
			if ($no <= 3) {
				$new_data[$no] = ['name' => $rs['name'], 'y' => $rs['y']];
			} else {
				$new_data[4]['y'] += $rs['y'];
				$new_data[4]['name'] = 'OTHER';
			}
			$no++;
		}
		array_multisort(array_map(function ($element) {
			return $element['y'];
		}, $new_data), SORT_DESC, $new_data);
		$new_data[5] = ['name' => 'CASH', 'y' => $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr, null, null, null, null, null, null, 'cash')];
		$sum = array_sum(array_column($new_data, 'y'));
		$persen = 0;
		foreach ($new_data as $key => $dt) {
			// $persen = abs(@(round((($dt['y']/$sum) * 100),0)));
			$persen = ($dt['y'] / $sum) * 100;
			$data_n[] = $dt['y'];
			$labels[] = $dt['name'] . ' (' . number_format($persen, 1) . ' %)';
			// $backgroundColor[]='#'.random_hex(6);
		};
		$backgroundColor = ['#66a7fb', '#fced87', '#ff7a57', '#4bddb8', '#a4a4a4'];
		$result = ['labels' => $labels, 'data' => $data_n, 'backgroundColor' => $backgroundColor];
		echo json_encode($result);
	}
	function chartByContributionDealer()
	{
		$tanggal = anti_injection($this->input->post('tanggal'));
		//$tanggal = '2020-05-15';
		$bulan = date("Y-m", strtotime($tanggal));
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$id_dealer = $this->m_admin->cari_dealer();
		$get_finco = $this->getFinco($bulan);
		foreach ($get_finco->result() as $rs) {
			$hari_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, $id_dealer, null, null, $rs->id_finance_company);
			$data[] = ['name' => $rs->finance_company, 'y' => $hari_ini];
		}
		array_multisort(array_map(function ($element) {
			return $element['y'];
		}, $data), SORT_DESC, $data);
		$no = 1;
		$new_data[4]['y'] = 0;
		foreach ($data as $rs) {
			if ($no <= 3) {
				$new_data[$no] = ['name' => $rs['name'], 'y' => $rs['y']];
			} else {
				$new_data[4]['y'] += $rs['y'];
				$new_data[4]['name'] = 'OTHER';
			}
			$no++;
		}
		array_multisort(array_map(function ($element) {
			return $element['y'];
		}, $new_data), SORT_DESC, $new_data);
		$new_data[5] = ['name' => 'CASH', 'y' => $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, $id_dealer, null, null, null, null, 'cash')];
		$sum = array_sum(array_column($new_data, 'y'));
		$persen = 0;
		foreach ($new_data as $key => $dt) {
			// $persen = abs(@(round((($dt['y']/$sum) * 100),0)));		
			$persen = ($dt['y'] / $sum) * 100;
			if ($persen > 0) {
				$data_n[] = $dt['y'];
				$labels[] = $dt['name'] . ' (' . number_format($persen, 1) . ' %)';
			}
			// $backgroundColor[]='#'.random_hex(6);
		};
		$backgroundColor = ['#66a7fb', '#fced87', '#ff7a57', '#4bddb8', '#a4a4a4'];
		$result = ['labels' => $labels, 'data' => $data_n, 'backgroundColor' => $backgroundColor];
		echo json_encode($result);
	}
	function getSalesByDistrict()
	{
		$tanggal         = $this->input->post('tanggal');
		// $tanggal = '2019-12-13';
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Comparison_By_District.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Comparison By District</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		$bulan           = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$district = $this->getDistrict($bulan);
		$get_ctg = $this->db->query("SELECT kategori,id_kategori FROM ms_kategori WHERE active=1 AND id_kategori IN('T','S','C','EV') ORDER BY kategori ASC");
		// $bulan = '2019-12-10';
		// $bulan_kemarin = '2019-12-09';
		echo '<table border=1>';
		echo '
			<thead>
			<tr>
				<td rowspan=2 style="width:10%" align="center" style="padding-top:10px"><b>District</td>';
		foreach ($get_ctg->result() as $rs) {
			echo '<td colspan=3 align="center"><b>' . $rs->kategori . '</td>';
			$total[$rs->id_kategori]['m_1'] = 0;
			$total[$rs->id_kategori]['m'] = 0;
		}
		echo '<td colspan=3 align="center"><b>ALL TYPE</td>';
		$total['ALL TYPE'] = ['m_1' => 0, 'm' => 0];
		echo '
			</tr>
		';
		echo '<tr>';
		for ($i = 0; $i <= $get_ctg->num_rows(); $i++) {
			echo '<td style="width:4%" align="center"><b>M-1</td>';
			echo '<td style="width:4%" align="center"><b>M</td>';
			echo '<td style="width:4%" align="center"><b>%</td>';
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$no = 1;
		foreach ($district->result() as $dst) {
			echo '<tr>';
			// echo '<td>'.$no.'</td>';
			$kab = str_replace('KAB.', '', $dst->kabupaten);
			echo '<td align="center">' . $kab . '</td>';
			$t_m = 0;
			$t_m1 = 0;
			foreach ($get_ctg->result() as $ktg) {
				$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, $ktg->id_kategori, null, $dst->id_kabupaten);
				$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, null, null, $ktg->id_kategori, null, $dst->id_kabupaten);
				// $persen = abs(@(($bulan_kemarin/$bulan_ini)-1)*100);
				// $persen = @(($bulan_kemarin/$bulan_ini)-1);
				$persen = @(($bulan_ini / $bulan_kemarin) - 1);
				$persen = number_format($persen, 2) * 100;
				echo '<td align="center">' . $bulan_kemarin . '</td>';
				echo '<td align="center">' . $bulan_ini . '</td>';
				echo '<td align="center">' . $persen . '%</td>';
				$total[$ktg->id_kategori]['m_1'] += $bulan_kemarin;
				$total[$ktg->id_kategori]['m'] += $bulan_ini;
				$t_m1 += $bulan_kemarin;
				$t_m += $bulan_ini;
				$total['ALL TYPE']['m_1'] += $bulan_kemarin;
				$total['ALL TYPE']['m'] += $bulan_ini;
				// $data[$dst->id_kabupaten][$ktg->id_kategori]=['m_1'=>$bulan_kemarin,
				// 						   'm'=>$bulan_ini,
				// 						   'persen'=>(float)number_format($persen,2)
				// 						  ];
			}
			// $persen = abs(@(($t_m1/$t_m)-1)*100);
			// $persen = @(($t_m1/$t_m)-1);
			$persen = @(($t_m / $t_m1) - 1);
			$persen = number_format($persen, 2) * 100;
			echo '<td align="center">' . $t_m1 . '</td>';
			echo '<td align="center">' . $t_m . '</td>';
			echo '<td align="center">' . $persen . '%</td>';
			echo '</tr>';
			$no++;
		}
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td align="center"><b>Total</td>';
		foreach ($total as $rs) {
			$persen = @(($rs['m'] / $rs['m_1']) - 1);
			$persen = number_format($persen, 2) * 100;
			// $persen = abs(@(($rs['m_1']/$rs['m'])-1)*100);
			echo '<td align="center"><b>' . $rs['m_1'] . '</td>';
			echo '<td align="center"><b>' . $rs['m'] . '</td>';
			echo '<td align="center"><b>' . $persen . '%</td>';
		}
		// echo json_encode($total);
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
		// echo json_encode($data);
	}
	function getDealerGroup($bulan)
	{
		// $group = $this->db->query("SELECT * FROM (
		//                           SELECT id_group_dealer 
		//                           FROM tr_sales_order
		//                           INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
		//                           INNER JOIN ms_group_dealer_detail ON ms_group_dealer_detail.id_dealer=tr_sales_order.id_dealer
		//                           WHERE LEFT(tgl_cetak_invoice,7)='$bulan'
		//                           GROUP BY id_group_dealer
		//                           UNION
		//                           SELECT id_group_dealer 
		//                           FROM tr_sales_order_gc
		//                           INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
		//                           INNER JOIN ms_group_dealer_detail ON ms_group_dealer_detail.id_dealer=tr_sales_order_gc.id_dealer
		//                           WHERE LEFT(tgl_cetak_invoice,7)='$bulan'
		//                           GROUP BY id_group_dealer
		//                       ) AS tabel
		// 			");
		$group = $this->db->query("SELECT id_group_dealer,group_dealer FROM ms_group_dealer");
		return $group;
	}
	function getSalesByDealerGroup()
	{
		$tanggal        = $this->input->post('tanggal');
		// $tanggal        = '2019-12-13';
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Contribution_By_Dealer_Group.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Contribution By Dealer Group</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		$bulan          = date("Y-m", strtotime($tanggal));
		$bulan_kemarin = $this->bulan_kemarin($bulan);
		$tgl_akhir_min1 = $this->tanggal_kemarin($tanggal);
		$tanggal_arr      = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin . '-01', $tgl_akhir_min1];
		$group = $this->getDealerGroup($bulan);
		echo '<table border=1>';
		echo '<thead>
			<tr>
				
				<td style="text-align:center;"><b>Dealer Group</b></td>
				<td style="text-align:center;"><b>Sales M-1</b></td>
				<td style="text-align:center;"><b>Sales M</b></td>
				<td style="text-align:center;"><b>Growth vs M-1</b></td>
				<td style="text-align:center;"><b>Cont.</b></td>
			</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$tot_m = 0;
		$tot_min1 = 0;
		$tot_cont = 0;
		$tot_bln_kmrn = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr, null, null, null, null, null, null, null, null);
		foreach ($group->result() as $gr) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, null, null, null, null, $gr->id_group_dealer);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, null, null, null, null, null, null, $gr->id_group_dealer);
			// $growth=abs(@($bulan_ini/$bulan_kemarin-1)*100);
			// $growth=@($bulan_ini/$bulan_kemarin-1);
			$growth = @($bulan_ini / $bulan_kemarin) - 1;
			$growth = number_format($growth, 2) * 100;
			$cont   = @round(($bulan_ini / $tot_bln_kmrn), 4) * 100;
			$dt_result[] = [
				'id_group_dealer' => $gr->group_dealer,
				'bulan_kemarin' => $bulan_kemarin,
				'bulan_ini' => $bulan_ini,
				'growth' => $growth,
				'cont' => $cont
			];
			$no++;
			$tot_m += $bulan_ini;
			$tot_min1 += $bulan_kemarin;
			$tot_cont += $cont;
		}
		array_multisort(array_map(function ($element) {
			return $element['cont'];
		}, $dt_result), SORT_DESC, $dt_result);
		$no = 1;
		foreach ($dt_result as $rs) {
			echo '<tr>';
			// echo '<td>'.$no.'</td>';
			echo '<td style="text-align:center">' . $rs['id_group_dealer'] . '</td>';
			echo '<td style="text-align:center">' . $rs['bulan_kemarin'] . '</td>';
			echo '<td style="text-align:center">' . $rs['bulan_ini'] . '</td>';
			echo '<td style="text-align:center">' . $rs['growth'] . '%</td>';
			echo '<td style="text-align:center">' . $rs['cont'] . '%</td>';
			echo '</tr>';
			$no++;
		}
		// $tot_growth=abs(@($tot_m/$tot_min1-1))*100;
		$tot_growth = @($tot_m / $tot_min1) - 1;
		$tot_growth = number_format($tot_growth, 2) * 100;
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td style="text-align:center"><b>Total</td>';
		echo '<td style="text-align:center"><b>' . $tot_min1 . '</td>';
		echo '<td style="text-align:center"><b>' . $tot_m . '</td>';
		echo '<td style="text-align:center"><b>' . $tot_growth . ' %</td>';
		echo '<td style="text-align:center"><b>' . round($tot_cont) . ' %</td>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
		// echo json_encode($data);
	}

	function getSalesByDealerGroupOfYear()
	{
		$tanggal        = $this->input->post('tanggal');
		// $tanggal        = '2019-12-13';
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Contribution_By_Dealer_Group_Of_Year.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Contribution By Dealer Group Of Year</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		$bulan          = date("Y-m", strtotime($tanggal));

		$tgl_akhir_min1 = $this->tanggal_tahun_kemarin($tanggal);

		//set ambil value tahun lalu
		$tahun_lalu = date('Y')-1;
		$tahun_skrg = date('Y');
		$januari_tahun_lalu = "$tahun_lalu-01-01";
		$januari_tahun_skrg = "$tahun_skrg-01-01";

		$tanggal_arr_min1 = [$januari_tahun_lalu, $tgl_akhir_min1];
		$tanggal_arr = [$januari_tahun_skrg, $tanggal];

		$group = $this->getDealerGroup($bulan);
		echo '<table border=1>';
		echo '<thead>
			<tr>
				
				<td style="text-align:center;"><b>Dealer Group</b></td>
				<td style="text-align:center;"><b>Sales Y-1</b></td>
				<td style="text-align:center;"><b>Sales Y</b></td>
				<td style="text-align:center;"><b>Growth vs Y-1</b></td>
				<td style="text-align:center;"><b>Cont.</b></td>
			</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$tot_m = 0;
		$tot_min1 = 0;
		$tot_cont = 0;
		$tot_bln_kmrn = $this->m_admin->get_penjualan_dashboard('range_tanggal', $tanggal_arr, null, null, null, null, null, null, null, null);
		foreach ($group->result() as $gr) {
			$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, null, null, null, null, $gr->id_group_dealer);
			$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, null, null, null, null, null, null, null, $gr->id_group_dealer);
			// $growth=abs(@($bulan_ini/$bulan_kemarin-1)*100);
			// $growth=@($bulan_ini/$bulan_kemarin-1);
			$growth = @($bulan_ini / $bulan_kemarin) - 1;
			$growth = number_format($growth, 2) * 100;
			$cont   = @round(($bulan_ini / $tot_bln_kmrn), 4) * 100;
			$dt_result[] = [
				'id_group_dealer' => $gr->group_dealer,
				'bulan_kemarin' => $bulan_kemarin,
				'bulan_ini' => $bulan_ini,
				'growth' => $growth,
				'cont' => $cont
			];
			$no++;
			$tot_m += $bulan_ini;
			$tot_min1 += $bulan_kemarin;
			$tot_cont += $cont;
		}
		array_multisort(array_map(function ($element) {
			return $element['cont'];
		}, $dt_result), SORT_DESC, $dt_result);
		$no = 1;
		foreach ($dt_result as $rs) {
			echo '<tr>';
			// echo '<td>'.$no.'</td>';
			echo '<td style="text-align:center">' . $rs['id_group_dealer'] . '</td>';
			echo '<td style="text-align:center">' . $rs['bulan_kemarin'] . '</td>';
			echo '<td style="text-align:center">' . $rs['bulan_ini'] . '</td>';
			echo '<td style="text-align:center">' . $rs['growth'] . '%</td>';
			echo '<td style="text-align:center">' . $rs['cont'] . '%</td>';
			echo '</tr>';
			$no++;
		}
		// $tot_growth=abs(@($tot_m/$tot_min1-1))*100;
		$tot_growth = @($tot_m / $tot_min1) - 1;
		$tot_growth = number_format($tot_growth, 2) * 100;
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td style="text-align:center"><b>Total</td>';
		echo '<td style="text-align:center"><b>' . $tot_min1 . '</td>';
		echo '<td style="text-align:center"><b>' . $tot_m . '</td>';
		echo '<td style="text-align:center"><b>' . $tot_growth . ' %</td>';
		echo '<td style="text-align:center"><b>' . round($tot_cont) . ' %</td>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
		// echo json_encode($data);
	}

	function get_dp_finco($periode, $waktu, $id_finance_company, $range = null)
	{
		$where = 'WHERE 1=1 ';
		if ($range != null) {
			$bawah = $range[0];
			$atas = $range[1];
			$where .= "AND persen_dp BETWEEN $bawah AND $atas";
		}
		if ($periode == 'range_tanggal') {
			$where_periode = " WHERE tgl_cetak_invoice BETWEEN '" . $this->db->escape_str($waktu[0]) . "' AND '" . $this->db->escape_str($waktu[1]) . "'";
		}
		if ($periode == 'tanggal') {
			$waktu=$this->db->escape_str($waktu);
			$where_periode = "WHERE tgl_cetak_invoice='$waktu'";
		}
		$filter_finco = '';
		if ($id_finance_company != null) {
			$id_finance_company = $this->db->escape_str($id_finance_company);
			$filter_finco = " AND id_finance_company='$id_finance_company'";
		}
		// harga_off_road-(IFNULL(voucher_2,0)+IFNULL(voucher_tambahan_2,0)+IFNULL(diskon,0))
		$get_unit = $this->db->query("SELECT COUNT(no_mesin) AS jum 
					FROM(
					SELECT *,round((uang_muka/total_bayar)*100,2) AS persen_dp 
						FROM (
							SELECT tr_scan_barcode.no_mesin,tr_sales_order.id_sales_order,dp_stor,uang_muka,the_road,id_finance_company,
								CASE the_road 
								WHEN 'On The Road' THEN harga_on_road
								ELSE harga_off_road
								END AS total_bayar
							FROM tr_sales_order 
									INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
									INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
									$where_periode AND jenis_beli='kredit'
									$filter_finco
						) AS tabel 
					) AS tabel2 
					$where
					");
		return $get_unit->row()->jum;
	}
	function get_dp_finco_dealer($periode, $waktu, $id_finance_company, $range = null)
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$where = 'WHERE 1=1 ';
		if ($range != null) {
			$bawah = $range[0];
			$atas = $range[1];
			$where .= "AND persen_dp BETWEEN $bawah AND $atas";
		}
		if ($periode == 'range_tanggal') {
			$where_periode = " WHERE tgl_cetak_invoice BETWEEN '" . $this->db->escape_str($waktu[0]) . "' AND '" . $this->db->escape_str($waktu[1]) . "'";
		}
		if ($periode == 'tanggal') {
			$waktu=$this->db->escape_str($waktu);
			$where_periode = "WHERE tgl_cetak_invoice='$waktu'";
		}
		$filter_finco = '';
		if ($id_finance_company != null) {
			$id_finance_company = $this->db->escape_str($id_finance_company);
			$filter_finco = " AND id_finance_company='$id_finance_company'";
		}
		// harga_off_road-(IFNULL(voucher_2,0)+IFNULL(voucher_tambahan_2,0)+IFNULL(diskon,0))
		if($id_dealer==''){
			return 0;
		}else{
			$id_dealer = $this->db->escape_str($id_dealer);
			$get_unit = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM(
						SELECT *,round((uang_muka/total_bayar)*100,2) AS persen_dp FROM (
							SELECT tr_scan_barcode.no_mesin,tr_sales_order.id_sales_order,dp_stor,uang_muka,the_road,id_finance_company,
								CASE the_road 
								WHEN 'On The Road' THEN harga_on_road
								ELSE harga_off_road
								END AS total_bayar
							FROM ms_dealer 
									INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
									INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
									INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
									$where_periode AND jenis_beli='kredit'
									$filter_finco
									AND tr_sales_order.id_dealer = '$id_dealer'
						) AS tabel 
						) AS tabel2 $where
						");
			return $get_unit->row()->jum;
		}
	}
	function get_penjualan_dp_finco($periode, $waktu, $id_finance_company, $range = null)
	{
		$total = $this->get_dp_finco($periode, $waktu, $id_finance_company);
		$tot_range = $this->get_dp_finco($periode, $waktu, $id_finance_company, $range);
		return @ROUND((($tot_range / $total) * 100), 2);
		// return $tot_range.'-'.$total;
	}
	function get_penjualan_dp_finco_dealer($periode, $waktu, $id_finance_company, $range = null)
	{
		$total = $this->get_dp_finco_dealer($periode, $waktu, $id_finance_company);
		$tot_range = $this->get_dp_finco_dealer($periode, $waktu, $id_finance_company, $range);
		return @ROUND((($tot_range / $total) * 100), 2);
	}

	function getSalesFincoByDP_new()
	{
		$tanggal        = anti_injection($this->db->escape_str($this->input->post('tanggal')));
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Finco_Contribution_By_Down_Payment.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Finco Contribution By Down Payment</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		// $tanggal        = '2019-12-12';
		$bulan          = date("Y-m", strtotime($tanggal));
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$get_finco = $this->getFinco($bulan);
		echo '<table border=1 >';
		echo '
			<thead>
				<tr>
					<td style="text-align:center;"><b>Finco</b></td>
					<td style="text-align:center;"><b><10%</b></td>
					<td style="text-align:center;"><b>10-15%</b></td>
					<td style="text-align:center;"><b>15-20%</b></td>
					<td style="text-align:center;"><b>>20%</b></td>
				</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$tot = ['10' => 0, '1015' => 0, '1520'=> 0, '20' => 0];
		$dp_10_t = 0;
		$dp_1015_t = 0;
		$dp_1520_t = 0;
		$dp_20_t = 0;
		foreach ($get_finco->result() as $fnc) {
			$dp_10   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [0, 9.99]);
			$dp_1015 = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [10.00, 14.99]);
			$dp_1520   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [15.00, 19.99]);
			$dp_20 = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [20.00, 100]);
			if ($dp_10 > 0 or $dp_1015 > 0  or $dp_1520 > 0 or $dp_20 > 0) {
				$tot_dp = $dp_10 + $dp_1015 + $dp_1520 + $dp_20;
				$tot['10']   += $dp_10;
				$tot['1015'] += $dp_1015;
				$tot['1520']   += $dp_1520;
				$tot['20']   += $dp_20;
				echo '<tr>';
				// echo '<td>'.$no.'</td>';
				echo '<td style="text-align:center">' . $fnc->finance_company . '</td>';
				echo '<td style="text-align:center">' . $dp_10 . ' %</td>';
				echo '<td style="text-align:center">' . $dp_1015 . ' %</td>';
				echo '<td style="text-align:center">' . $dp_1520 . ' %</td>';
				echo '<td style="text-align:center">' . $dp_20 . ' %</td>';
				// echo '<td>'.round($tot_dp).' %</td>';
				echo '</tr>';
				$no++;
				$dp_10_t += $dp_10;
				$dp_1015_t += $dp_1015;
				$dp_1520_t += $dp_1520;
				$dp_20_t += $dp_20;
			}
		}
		$dp_10   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [0, 9.99]);
		$dp_1015   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [10, 14.99]);
		$dp_1520 = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [15, 19.99]);
		$dp_20   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [20, 100]);
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td style="text-align:center"><b>Total</td>';
		echo '<td style="text-align:center"><b>' . $dp_10 . '%</td>';
		echo '<td style="text-align:center"><b>' . $dp_1015 . '%</td>';
		echo '<td style="text-align:center"><b>' . $dp_1520 . '%</td>';
		echo '<td style="text-align:center"><b>' . $dp_20 . '%</td>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
	}

	function getSalesFincoByDP()
	{
		$tanggal        = anti_injection($this->input->post('tanggal'));
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Finco_Contribution_By_Down_Payment.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Finco Contribution By Down Payment</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		// $tanggal        = '2019-12-12';
		$bulan          = date("Y-m", strtotime($tanggal));
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$get_finco = $this->getFinco($bulan);
		echo '<table border=1 >';
		echo '
			<thead>
				<tr>
					<td style="text-align:center;"><b>Finco</b></td>
					<td style="text-align:center;"><b><16%</b></td>
					<td style="text-align:center;"><b>16-20%</b></td>
					<td style="text-align:center;"><b>>20%</b></td>
				</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$tot = ['10' => 0, '1020' => 0, '20' => 0];
		$dp_10_t = 0;
		$dp_1020_t = 0;
		$dp_20_t = 0;
		foreach ($get_finco->result() as $fnc) {
			$dp_10   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [0, 15]);
			$dp_1020 = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [16, 19]);
			$dp_20   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [20, 100]);
			if ($dp_10 > 0 or $dp_1020 > 0 or $dp_20 > 0) {
				$tot_dp = $dp_10 + $dp_1020 + $dp_20;
				$tot['10']   += $dp_10;
				$tot['1020'] += $dp_1020;
				$tot['20']   += $dp_20;
				echo '<tr>';
				// echo '<td>'.$no.'</td>';
				echo '<td style="text-align:center">' . $fnc->finance_company . '</td>';
				echo '<td style="text-align:center">' . $dp_10 . ' %</td>';
				echo '<td style="text-align:center">' . $dp_1020 . ' %</td>';
				echo '<td style="text-align:center">' . $dp_20 . ' %</td>';
				// echo '<td>'.round($tot_dp).' %</td>';
				echo '</tr>';
				$no++;
				$dp_10_t += $dp_10;
				$dp_1020_t += $dp_1020;
				$dp_20_t += $dp_20;
			}
		}
		$dp_10   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [0, 15]);
		$dp_1020 = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [16, 19]);
		$dp_20   = $this->get_penjualan_dp_finco('range_tanggal', $tanggal_arr, null, [20, 100]);
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td style="text-align:center"><b>Total</td>';
		echo '<td style="text-align:center"><b>' . $dp_10 . '%</td>';
		echo '<td style="text-align:center"><b>' . $dp_1020 . '%</td>';
		echo '<td style="text-align:center"><b>' . $dp_20 . '%</td>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
	}

	function getSalesFincoByDPDealer_new()
	{
		$tanggal        = anti_injection($this->input->post('tanggal'));
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Finco_Contribution_By_Down_Payment_Dealer.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Finco Contribution By Down Payment</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		// $tanggal        = '2019-12-12';
		$bulan          = date("Y-m", strtotime($tanggal));
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$get_finco = $this->getFinco($bulan);
		echo '<table border=1>';
		echo '<thead>
			<tr align=center>
				<td><b>Finco</td>
				<td><b><10%</td>
				<td><b>10-15%</td>
				<td><b>15-20%</td>
				<td><b>>20%</td>
			</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$dp_20_t = 0;
		$dp_1015_t = 0;
		$dp_1520_t = 0;
		$dp_10_t = 0;
		$tot = ['10' => 0, '1015' => 0, '1520' => 0, '20' => 0];
		foreach ($get_finco->result() as $fnc) {
			$dp_10   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [0, 9.99]);
			$dp_1015 = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [10, 14.99]);
			$dp_1520 = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [15, 19.99]);
			$dp_20   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [20, 100]);
			if ($dp_10 > 0 or $dp_1015 > 0 or $dp_1520 > 0 or $dp_20 > 0) {
				$tot_dp = $dp_10 + $dp_1015 + $dp_1520  + $dp_20;
				$tot['10']   += $dp_10;
				$tot['1015'] += $dp_1015;
				$tot['1520'] += $dp_1520;
				$tot['20']   += $dp_20;
				echo '<tr>';
				// echo '<td>'.$no.'</td>';
				echo '<td>' . $fnc->finance_company . '</td>';
				echo '<td>' . $dp_10 . ' %</td>';
				echo '<td>' . $dp_1015 . ' %</td>';
				echo '<td>' . $dp_1520 . ' %</td>';
				echo '<td>' . $dp_20 . ' %</td>';
				// echo '<td>'.round(val)und($tot_dp).' %</td>';
				echo '</tr>';
				$no++;
				$dp_10_t += $dp_10;
				$dp_1015_t += $dp_1015;
				$dp_1520_t += $dp_1520;
				$dp_20_t += $dp_20;
			}
		}
		$dp_10   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [0, 9.99]);
		$dp_1015 = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [10, 14.99]);
		$dp_1520 = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [15, 19.99]);
		$dp_20   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [20, 100]);
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td>Total</td>';
		echo '<td>' . $dp_10 . '%</td>';
		echo '<td>' . $dp_1015 . '%</td>';
		echo '<td>' . $dp_1520 . '%</td>';
		echo '<td>' . $dp_20 . '%</td>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
	}

	function getSalesFincoByDPDealer()
	{
		$tanggal        = anti_injection($this->input->post('tanggal'));
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_Finco_Contribution_By_Down_Payment_Dealer.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales Finco Contribution By Down Payment</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		// $tanggal        = '2019-12-12';
		$bulan          = date("Y-m", strtotime($tanggal));
		$tanggal_arr = [$bulan . '-01', $tanggal];
		$get_finco = $this->getFinco($bulan);
		echo '<table border=1>';
		echo '<thead>
			<tr align=center>
				<td><b>Finco</td>
				<td><b><16%</td>
				<td><b>16-20%</td>
				<td><b>>20%</td>
			</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$dp_20_t = 0;
		$dp_1020_t = 0;
		$dp_10_t = 0;
		$tot = ['10' => 0, '1020' => 0, '20' => 0];
		foreach ($get_finco->result() as $fnc) {
			$dp_10   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [0, 15]);
			$dp_1020 = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [16, 19]);
			$dp_20   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, $fnc->id_finance_company, [20, 100]);
			if ($dp_10 > 0 or $dp_1020 > 0 or $dp_20 > 0) {
				$tot_dp = $dp_10 + $dp_1020 + $dp_20;
				$tot['10']   += $dp_10;
				$tot['1020'] += $dp_1020;
				$tot['20']   += $dp_20;
				echo '<tr>';
				// echo '<td>'.$no.'</td>';
				echo '<td>' . $fnc->finance_company . '</td>';
				echo '<td>' . $dp_10 . ' %</td>';
				echo '<td>' . $dp_1020 . ' %</td>';
				echo '<td>' . $dp_20 . ' %</td>';
				// echo '<td>'.round(val)und($tot_dp).' %</td>';
				echo '</tr>';
				$no++;
				$dp_10_t += $dp_10;
				$dp_1020_t += $dp_1020;
				$dp_20_t += $dp_20;
			}
		}
		$dp_10   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [0, 15]);
		$dp_1020 = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [16, 19]);
		$dp_20   = $this->get_penjualan_dp_finco_dealer('range_tanggal', $tanggal_arr, null, [20, 100]);
		echo '</tbody>';
		echo '<tfoot>';
		echo '<tr>';
		echo '<td>Total</td>';
		echo '<td>' . $dp_10 . '%</td>';
		echo '<td>' . $dp_1020 . '%</td>';
		echo '<td>' . $dp_20 . '%</td>';
		echo '</tr>';
		echo '</tfoot>';
		echo '</table>';
	}
	function getSalesPerformanceDealer()
	{
		$id_dealer = $this->m_admin->cari_dealer();
		$tanggal1_akhir   = date("Y-m-d");
		$tanggal1_awal   	= date("Y-m") . "-01";
		$tanggalan   	= date("d");
		$bulan_ini 		= substr($tanggal1_awal, 0, 7);
		$bulan_kemarin =  $this->bulan_kemarin($bulan_ini);
		$tanggal2_awal = $bulan_kemarin . "-01";
		$tanggal2_akhir	 = $bulan_kemarin . "-" . $tanggalan;
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Sales_People_Performance.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Sales People Performance</p>';
		}
		echo '<table border=1>';
		echo '<thead>
			<tr align=center>
				<td><b>No</td>
				<td><b>Honda ID</td>
				<td><b>Nama Sales People</td>
				<td><b>Jabatan</td>
				<td><b>M-1</td>
				<td><b>M</td>
				<td><b>Growth</td>
			</tr>
			</thead>
			';
		echo '<tbody>';
		$no = 1;
		$sr = $this->db->query("SELECT id_karyawan,id_flp_md,nama_lengkap, jabatan, SUM(jum) AS hasil FROM
		(
		  SELECT ms_karyawan_dealer.id_karyawan_dealer AS id_karyawan, ms_karyawan_dealer.id_flp_md AS id_flp_md,ms_karyawan_dealer.nama_lengkap AS nama_lengkap,ms_jabatan.jabatan AS jabatan, COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 	
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        LEFT JOIN ms_jabatan ON ms_jabatan.id_jabatan = ms_karyawan_dealer.id_jabatan
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.tgl_cetak_invoice BETWEEN '$tanggal1_awal' AND '$tanggal1_akhir'					  
        AND tr_sales_order.id_dealer = '$id_dealer' AND ms_karyawan_dealer.active = 1
        GROUP BY ms_karyawan_dealer.id_karyawan_dealer
		  UNION ALL
		  SELECT ms_karyawan_dealer.id_karyawan_dealer AS id_karyawan, ms_karyawan_dealer.id_flp_md AS id_flp_md,ms_karyawan_dealer.nama_lengkap AS nama_lengkap,ms_jabatan.jabatan AS jabatan, COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc 	
				LEFT JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
        LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
        LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
        LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        LEFT JOIN ms_jabatan ON ms_jabatan.id_jabatan = ms_karyawan_dealer.id_jabatan
        WHERE tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL AND tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tanggal1_awal' AND '$tanggal1_akhir'					  
        AND tr_sales_order_gc.id_dealer = '$id_dealer' AND ms_karyawan_dealer.active = 1
        GROUP BY ms_karyawan_dealer.id_karyawan_dealer
		)a GROUP BY id_karyawan ORDER BY hasil DESC");
		foreach ($sr->result() as $isi) {
			$ss1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 	
	        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
	        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
	        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
	        LEFT JOIN ms_jabatan ON ms_jabatan.id_jabatan = ms_karyawan_dealer.id_jabatan
	        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.tgl_cetak_invoice BETWEEN '$tanggal2_awal' AND '$tanggal2_akhir'					  
	        AND ms_karyawan_dealer.id_karyawan_dealer = '$isi->id_karyawan'
	        GROUP BY ms_karyawan_dealer.id_karyawan_dealer");
			$ss2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc 	
					LEFT JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
	        LEFT JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
	        LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
	        LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
	        LEFT JOIN ms_jabatan ON ms_jabatan.id_jabatan = ms_karyawan_dealer.id_jabatan
	        WHERE tr_sales_order_gc.tgl_cetak_invoice IS NOT NULL AND tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tanggal2_awal' AND '$tanggal2_akhir'					  
	        AND ms_karyawan_dealer.id_karyawan_dealer = '$isi->id_karyawan'
	        GROUP BY ms_karyawan_dealer.id_karyawan_dealer
	        ORDER BY jum DESC");
			$ss_1 = ($ss1->num_rows() > 0) ? $ss1->row()->jum : 0;
			$ss_2 = ($ss2->num_rows() > 0) ? $ss2->row()->jum : 0;
			$ss_real = $ss_1 + $ss_2;


			$gr = @($isi->hasil / $ss_real) - 1;
			$gr = number_format($gr, 2) * 100;

			if ($ss_real <= 0) {
				$gr = 100;
			}
			echo '<tr align=center>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . $isi->id_flp_md . '</td>';
			echo '<td>' . $isi->nama_lengkap . '</td>';
			echo '<td>' . $isi->jabatan . '</td>';
			echo '<td>' . $ss_real . '</td>';
			echo '<td>' . $isi->hasil . '</td>';
			echo '<td>' . $gr . ' %</td>';
			echo '</tr>';
			$no++;
		}
		echo '</tfoot>';
		echo '</table>';
	}
	function getStokMonitoring()
	{
		echo '<table border=1>';
		echo '<thead>
			<tr>
				<th>Item</th>
				<th>Tipe</th>
				<th>Warna</th>
				<th>Stok</th>
				<th>Unfill</th>
				<th>Intransit</th>
			</tr>
			</thead>
			<tbody>
			';
		$dt_list = $this->db->query("SELECT ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item
        LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna                                
        ORDER BY ms_item.id_item ASC");
		$no = 1;
		$t_qty = 0;
		$t_unfill = 0;
		$t_in = 0;
		foreach ($dt_list->result() as $row) {
			$id_dealer = $this->m_admin->cari_dealer();
			$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
            LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
            LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
            LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
            LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
            LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
            WHERE tr_scan_barcode.id_item = '$row->id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
            AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'")->row();
			$cek_unfill = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
                        INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan) AND
                        tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer' AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'")->row();
			$cek_unfill2 = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                    WHERE tr_do_po.no_do NOT IN (SELECT no_do FROM tr_picking_list) 
                    AND tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer'")->row();
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                    WHERE tr_surat_jalan.status ='proses' AND tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();
			if ($cek_qty->jum > 0 or $cek_unfill->jum > 0 or $cek_in->jum > 0) {
				echo "
          <tr>                     
            <td>$row->id_item</td>              
            <td>$row->tipe_ahm</td>
            <td>$row->warna</td>
            <td>$cek_qty->jum</td>
            <td>$cek_unfill->jum</td>
            <td>$cek_in->jum</td>              
          </tr>
          ";
				$t_qty = $t_qty + $cek_qty->jum;
				$t_unfill = $t_unfill + $cek_unfill->jum;
				$t_in = $t_in + $cek_in->jum;
			}
		}
		echo '</tbody></table>';
	}
	function getSalesDP()
	{
		echo '<table border=1>';
		echo '<thead>
			<tr>
				<th rowspan=2>Finance Company</th>
				<th colspan=3>Contribution By DP</th>
				<th rowspan=2>Total</th>				
			</tr>
			<tr>
				<th>10%</th>
				<th>10%-20%</th>
				<th>20%</th>
			</tr>
			</thead>
			<tbody>
			';
		$no = 1;
		$p1 = 0;
		$p2 = 0;
		$p3 = 0;
		$p4 = 0;
		$id_dealer = $this->m_admin->cari_dealer();
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
		foreach ($sql->result() as $isi) {
			$spk = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
            WHERE jenis_beli = 'Kredit' AND tr_spk.id_dealer = '$id_dealer'
            AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
			$spk1 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0 AND 10 AND tr_spk.id_dealer = '$id_dealer'
            AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
			$spk2 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 11 AND 20 AND tr_spk.id_dealer = '$id_dealer'
            AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
			$spk3 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 > 20 AND tr_spk.id_dealer = '$id_dealer'
            AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
			if ($spk->jum != 0) {
				$isi_spk1 = round((($spk1->jum / $spk->jum) * 100), 2);
				$isi_spk2 = round((($spk2->jum / $spk->jum) * 100), 2);
				$isi_spk3 = round((($spk3->jum / $spk->jum) * 100), 2);
			} else {
				$isi_spk1 = round((($spk1->jum) * 100), 2);
				$isi_spk2 = round((($spk2->jum) * 100), 2);
				$isi_spk3 = round((($spk3->jum) * 100), 2);
			}
			$tot = $isi_spk1 + $isi_spk2 + $isi_spk3;
			$p1 = $p1 + $isi_spk1;
			$p2 = $p2 + $isi_spk2;
			$p3 = $p3 + $isi_spk3;
			$p4 = $p4 + $tot;
			if ($tot != 0) {
				echo "
          <tr>
            <td>$isi->finance_company</td>
            <td>$isi_spk1 %</td>
            <td>$isi_spk2 %</td>
            <td>$isi_spk3 %</td>
            <td>$tot %</td>
          </tr>
          ";
				$no++;
			}
		}
		echo "</tbody>
			<tfoot>
				<tr>
					<td>Total</td>
					<td>" . $p1 . "</td>
					<td>" . $p2 . "</td>
					<td>" . $p3 . "</td>
					<td>" . $p4 . "</td>
				</tr>
			</tfoot>
		</table>";
	}
	function getSSU()
	{
		$tanggal 	= $this->input->post('tanggal');
		if (isset($_GET['download'])) {
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=Rank_dealer_by_ssu.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '<p align="center" style="font-weight:bold">Rank Dealer By SSU</p>';
			$tanggal        = $this->input->get('tanggal');
		}
		$bulan 		= substr($tanggal, 0, 7);
		$where1 = '';
		$where2 = '';
		if ($bulan != null) {
			$where1 = "WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan'";
			$where2 = "WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan'";
		}
		echo '<table border=1>';
		echo '<thead>			
			<tr align=center>
				<td width=5%><b>No</td>
				<td><b>Dealer/POS</td>
				<td><b>SSU</td>
			</tr>
			</thead>
			<tbody>
			';
		$no = 0;
		$total1 = "";
		$sql = $this->db->query("SELECT de, SUM(total) as hasil FROM
		(
		  SELECT ms_dealer.nama_dealer AS de, COUNT(tr_sales_order.no_mesin) AS total 
			FROM tr_sales_order 
			INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer 
			$where1
			GROUP BY tr_sales_order.id_dealer
		  UNION ALL
		  SELECT ms_dealer.nama_dealer AS de, COUNT(tr_sales_order_gc_nosin.no_mesin) AS total 
			FROM tr_sales_order_gc 
			INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
			INNER JOIN ms_dealer ON tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
			$where2
			GROUP BY tr_sales_order_gc.id_dealer
		)a GROUP BY de ORDER BY hasil DESC");
		foreach ($sql->result() as $isi) {
			if ($total1 <> $isi->hasil) {
				$total1 = $isi->hasil;
				$no++;
			} else {
				$total1 = $total1;
			}
			echo "
      	<tr>
      		<td>$no</td>
      		<td>$isi->de</td>
      		<td>$isi->hasil</td>
      	</tr>
      	";
		}
		echo "</tbody></table>";
	}
	public function ajax()
	{
		/* Database connection start */
		$servername = '123.100.226.36';
		$username = 'sinarsentosadb';
		$password = '5z[T#ca(6gyE';
		$dbname = 'sinarsen_honda';
		$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
		/* Database connection end */
		// storing  request (ie, get/post) global array to a variable  
		$requestData = $_REQUEST;
		//print_r( $requestData );
		$columns = array(
			// datatable column index  => database column name
			0 => 'no_surat_jalan'
		);
		// getting total number records without any search
		$sql = "SELECT tr_surat_jalan.no_picking_list,tr_surat_jalan.status,tr_surat_jalan.no_surat_jalan, ms_dealer.nama_dealer,tr_surat_jalan.tgl_surat";
		$sql .= " FROM tr_surat_jalan INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer";
		$sql .= " WHERE 1=1";
		$query = mysqli_query($conn, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		$sql = "SELECT tr_surat_jalan.no_picking_list,tr_surat_jalan.status,tr_surat_jalan.no_surat_jalan, ms_dealer.nama_dealer,tr_surat_jalan.tgl_surat";
		$sql .= " FROM tr_surat_jalan INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer=ms_dealer.id_dealer";
		$sql .= " WHERE 1=1";
		if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql .= " AND ( no_surat_jalan LIKE '" . $requestData['search']['value'] . "%' ";
			$sql .= " OR tgl_surat LIKE '" . $requestData['search']['value'] . "%' )";
		}
		$query = mysqli_query($conn, $sql);
		$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
		/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
		$query = mysqli_query($conn, $sql);
		$data = array();
		$no = 1;
		while ($row = mysqli_fetch_array($query)) {  // preparing an array
			$cari = $this->db->query("SELECT tgl_pl,no_do FROM tr_picking_list WHERE no_picking_list = '$row[no_picking_list]'");
			if ($cari->num_rows() > 0) {
				$t = $cari->row();
				$tgl = $t->tgl_pl;
				$no_do = $t->no_do;
			} else {
				$tgl = "";
				$no_do = "";
			}
			$nestedData = array();
			$nestedData[] = $no;
			$nestedData[] = $row["no_picking_list"];
			$nestedData[] = $tgl;
			$nestedData[] = $row["no_surat_jalan"];
			$nestedData[] = $row["tgl_surat"];
			$nestedData[] = $no_do;
			$nestedData[] = $row["nama_dealer"];
			$nestedData[] = "ok";
			$nestedData[] = "ok";
			$data[] = $nestedData;
			$no++;
		}
		$json_data = array(
			"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval($totalData),  // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);
		echo json_encode($json_data);  // send data as json format
	}
}
