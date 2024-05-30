	<?php 
function tanggal_kemarin($tanggal)
{
	// $a_date = "2019-11-31";
	// echo $tanggal;
	$date = new DateTime($tanggal);
	// $date->modify('last day of this month');
	$date = $date->format('Y-m-d');
	if (substr($tanggal, 8, 2) != substr($date, 8, 2)) {
		$tanggal = substr($tanggal, 0, 7) . '-01';
		$date = new DateTime($tanggal);
		$date->modify('last day of this month');
		$date = $date->format('Y-m-d');
	}
	return $date;
}
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=secret_weapon_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Type</td>
 		<td align="center">Sales M-1</td>
 		<td align="center">Sales M</td>
 		<td align="center">Growth</td>
 		<td align="center">Growth (%)</td> 		
 		<td align="center">Outlook</td> 		 		
 		<td align="center">Stock MD</td> 		
 		<td align="center">Stock D</td> 		
 		<td align="center">Total Stock</td> 		
 		<td align="center">Stock Days</td> 		
 	</tr>
 	<?php 
 	$no=1;
	$stok_md = 0;
	$stok_dealer = 0;
 	$bulan_kemarin_jum=0;$bulan_ini_jum=0; 	
	$sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND STATUS = '1' AND tipe='RFS') AS ready
			FROM ms_tipe_kendaraan INNER JOIN ms_series ON ms_tipe_kendaraan.id_series = ms_series.id_series 
			INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
			ORDER BY ms_segment.segment,ms_series.series ASC");
 	foreach ($sql->result() as $row) {
 		$tanggal        = date("Y-m-d");
 		$tanggal_aja    = date("d");
		$bulan          = date("Y-m", strtotime($tanggal));		
		$bulan_kemarin2 = date('Y-m', strtotime('-1 months'));		
		$tgl_akhir_min1 = tanggal_kemarin($bulan_kemarin2 .'-'. $tanggal_aja);
		$tanggal_arr      = [$bulan . '-01', $tanggal];
		$tanggal_arr_min1 = [$bulan_kemarin2 . '-01', $tgl_akhir_min1];
 		$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, $row->id_tipe_kendaraan, null, null, null, null, null, null, null);
		$bulan_kemarin = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr_min1, $row->id_tipe_kendaraan, null, null, null, null, null, null, null);
		$growth1 = @($bulan_ini - $bulan_kemarin);
		$growth2 = @( ($bulan_ini/$bulan_kemarin) -1) * 100;
		$growth2_s = round($growth2,0);

		$cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND status = '2'")->row();			
		$cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'NRFS' AND status < 4")->row();
		$cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'PINJAMAN' AND status < 4")->row();
		$total = $row->ready + $cek_booking->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
		
		$total = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_scan_barcode 
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna        
                WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
                AND tr_scan_barcode.status =1")->row()->jum;

		/*
		$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
                AND tr_scan_barcode.status = '4'")->row();
		*/

		$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_scan_barcode 
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna        
                WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
                AND tr_scan_barcode.status > 1 and tr_scan_barcode.status <5")->row();
		
		$kalendar = CAL_GREGORIAN;
		$total_hari_kerja = cal_days_in_month($kalendar,date('m'),date('Y'));

		$stock_d = $cek_qty->jum;
		$total_stock = $total + $stock_d;
		$total_day = @(($total_stock / $bulan_ini)* $total_hari_kerja);
		$total_days = round($total_day,1);
		

		$outlock = @($bulan_ini / $tanggal_aja * $total_hari_kerja);
		$outlocks = round($outlock,2);

		if($bulan_ini > 0 OR $bulan_kemarin > 0 OR $total > 0 OR $stock_d > 0){
	 		echo "
	 			<tr>
	 				<td>$no</td> 				
	 				<td>$row->tipe_ahm ($row->id_tipe_kendaraan)</td> 				
	 				<td align='center'>$bulan_kemarin</td> 				
	 				<td align='center'>$bulan_ini</td> 				
	 				<td align='center'>$growth1</td> 				
	 				<td align='center'>$growth2_s %</td> 				
	 				<td align='center'>$outlocks</td> 				
	 				<td align='center'>$total</td> 				
	 				<td align='center'>$stock_d</td> 				
	 				<td align='center'>$total_stock</td> 				
	 				<td align='center'>$total_days</td> 				
	 			</tr>
	 		";
	 		$bulan_kemarin_jum += $bulan_kemarin;
	 		$bulan_ini_jum += $bulan_ini;
			$stok_md += $total;
			$stok_dealer += $stock_d;
	 		$no++;	 	
	 	}
 	}
 	?>
 	<tr>
 		<td colspan="2">Total</td>
 		<td><?php echo $bulan_kemarin_jum ?></td>
 		<td><?php echo $bulan_ini_jum ?></td>
 		<td><?php echo $bulan_ini_jum-$bulan_kemarin_jum ; ?></td>
 		<td><?php echo round(@( ($bulan_ini_jum/$bulan_kemarin_jum) -1) * 100,0); ?> %</td>
 		<td><?php echo round(@($bulan_ini_jum / $tanggal_aja * $total_hari_kerja),0); ?></td>
 		<td><?php echo $stok_md; ?></td>
 		<td><?php echo $stok_dealer; ?></td>
 		<td><?php echo $stok_md + $stok_dealer; ?></td>
 		<td><?php echo round(@( ($stok_md + $stok_dealer) / $bulan_ini_jum) *$total_hari_kerja ,0); ?></td>
 	</tr>
</table>
