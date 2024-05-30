<?php 
$no = $id_dealer."-".$bulan."-".$tahun;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PenjualanUnitDealer_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td rowspan="2" align="center">Kode Item</td>
 		<td rowspan="2" align="center">Stok Awal</td>
 		<td rowspan="2" align="center">Distribusi MD</td>
 		<td rowspan="2" align="center">Retur</td>
 		<td align="center" colspan="31">Tanggal Penjualan</td>
 		<td rowspan="2" align="center">Total Penjualan</td>
 		<td rowspan="2" align="center">Sisa Stok</td> 	
 	</tr>
 	<tr>
 		<?php 
		for ($i=1; $i <= 31; $i++) { 
			echo "<td align='center'>$i</td>";
		}
 		?>
 	</tr>
 	<?php 
 	$dt_list = $this->db->query("SELECT ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item
                LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna                                
                ORDER BY ms_item.id_item ASC");			
 	$t_book=0;$t_hard_book=0;$g_stok=0;$g_retur=0;$g_cekin=0;$g_jual=0;$g_total=0;$total=0;$t_jual=0;
 	foreach ($dt_list->result() as $isi) {
 		$bulan_2 = sprintf("%'.02d",$bulan);				
 		$tgl_bulan = $tahun."-".$bulan_2;
 		$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.id_item = '$isi->id_item' 
      AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
      AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'
      AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
      -- AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin_spk FROM tr_spk WHERE no_mesin_spk IS NOT NULL)
      ")->row();   
      $stok_ada = $cek_qty->jum;
     	$soft = $this->db->query("SELECT count(tpud.no_mesin) AS c FROM tr_penerimaan_unit_dealer_detail AS tpud
        JOIN tr_penerimaan_unit_dealer AS tpu ON tpud.id_penerimaan_unit_dealer=tpu.id_penerimaan_unit_dealer
        JOIN tr_scan_barcode ON tpud.no_mesin=tr_scan_barcode.no_mesin
        WHERE tpud.status_on_spk='booking' AND id_dealer=$id_dealer AND id_item='$isi->id_item'
         AND tr_scan_barcode.status = '4' AND tpu.status = 'close'
         ")->row()->c;
      $t_book+=$soft;
      $hard = $this->db->query("SELECT count(tpud.no_mesin) AS c FROM tr_penerimaan_unit_dealer_detail AS tpud
        JOIN tr_penerimaan_unit_dealer AS tpu ON tpud.id_penerimaan_unit_dealer=tpu.id_penerimaan_unit_dealer
        JOIN tr_scan_barcode ON tpud.no_mesin=tr_scan_barcode.no_mesin
        WHERE tpud.status_on_spk='hard_book' AND id_dealer=$id_dealer AND id_item='$isi->id_item'
         AND tr_scan_barcode.status = '4' AND tpu.status = 'close'
          ")->row()->c;
       $t_hard_book+=$hard;

    if ($stok_ada==0) {
      $stok_ada = $soft+$hard;
    }
    $cek_retur = $this->db->query("SELECT * FROM tr_retur_dealer_detail INNER JOIN tr_scan_barcode ON tr_retur_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
    	INNER JOIN tr_retur_dealer ON tr_retur_dealer_detail.no_retur_dealer = tr_retur_dealer.no_retur_dealer
    	WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_retur_dealer.status_retur_d = 'approved' AND tr_retur_dealer.id_dealer = '$id_dealer'");    
    $retur = $cek_retur->num_rows();

    $cek_in = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
      WHERE tr_scan_barcode.id_item = '$isi->id_item' 
      AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
      AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'
      AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
      AND LEFT(tr_penerimaan_unit_dealer.tgl_penerimaan,7) = '$tgl_bulan'
      -- AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin_spk FROM tr_spk WHERE no_mesin_spk IS NOT NULL)
      ")->row();   
    
    if($stok_ada > 0 or $retur > 0){ 
	 		echo "
	 		<tr>
	 			<td>$isi->id_item</td>
	 			<td align='right'>$stok_ada</td>
	 			<td align='right'>$cek_in->jum</td>
	 			<td align='right'>$retur</td>";	 			
	 			$jualan=0;$t_jual=0;
				for ($i=1; $i <= 31; $i++) { 
					$tgl = sprintf("%'.02d",$i);				
					$bulan_2 = sprintf("%'.02d",$bulan);				
 					$tgl_bulan2 = $tahun."-".$bulan_2."-".$tgl;
					$cek_jual = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum  FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_sales_order.tgl_cetak_invoice = '$tgl_bulan2'
						AND tr_sales_order.id_dealer = '$id_dealer'")->row();

					$cek_jual2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc 
						INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
						INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_sales_order_gc.tgl_cetak_invoice = '$tgl_bulan2'
						AND tr_sales_order_gc.id_dealer = '$id_dealer'")->row();
					$jualan = $cek_jual->jum + $cek_jual2->jum;
					$hasil3 = ($jualan!=0) ? $jualan : "" ;
					echo "<td align='right'>$hasil3</td>";
					$t_jual += $jualan;
				}		 		
				$total = $stok_ada + $cek_in->jum - $t_jual;
				echo "
				<td align='right'>$t_jual</td>
				<td align='right'>$total</td>
	 		</tr>
	 		";
	 		$g_total += $total;
	 	}	 	
	 	$g_stok += $stok_ada;
	 	$g_cekin += $cek_in->jum;
	 	$g_jual += $t_jual;
	 	$g_retur += $retur;
 	}
 	?>
 	<tr>
 		<td>Total</td>
 		<td align="center"><?php echo $g_stok; ?></td>
 		<td align="center"><?php echo $g_cekin; ?></td>
 		<td align="center"><?php echo $g_retur; ?></td>
 		<?php 
 		$t_jual2=0;
 		for ($i=1; $i <= 31; $i++) { 
			$tgl = sprintf("%'.02d",$i);				
			$bulan_2 = sprintf("%'.02d",$bulan);				
				$tgl_bulan2 = $tahun."-".$bulan_2."-".$tgl;
			$cek_jual = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum  FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
				WHERE tr_sales_order.tgl_cetak_invoice = '$tgl_bulan2'
				AND tr_sales_order.id_dealer = '$id_dealer'")->row();

			$cek_jual2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc 
						INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
						INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
						WHERE tr_sales_order_gc.tgl_cetak_invoice = '$tgl_bulan2'
						AND tr_sales_order_gc.id_dealer = '$id_dealer'")->row();
			$jualan2 = $cek_jual->jum + $cek_jual2->jum;
			$hasil2 = ($jualan2!=0) ? $jualan2 : "" ;
			echo "<td align='right'>$hasil2</td>";
			$t_jual2 += $jualan2;
		}
 		?>
 		<td align="center"><?php echo $t_jual2; ?></td>
 		<td align="center"><?php echo $g_total; ?></td>
 	</tr>
</table>

<br><br>
<table border="1" width="100%">
	<tr>
		<td align="center" rowspan="2">Financing</td>
		<td colspan="31" align="center">Tanggal Penjualan</td>
		<td rowspan="2" align="center">Total</td>
	</tr>
	<tr>
		<?php 
 		for ($i=1; $i <= 31; $i++) {  		
 			echo "<td align='center'>$i</td>";
 		}
 		?>

	</tr>
	<?php 
	$s_total=0;
	$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
	foreach ($sql->result() as $row) {
		echo "
		<tr>
			<td>$row->finance_company</td>";			
 			for ($i=1; $i <= 31; $i++) { 
 				$tgl = sprintf("%'.02d",$i);				
				$bulan_2 = sprintf("%'.02d",$bulan);				
 				$cek_jual = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum  FROM tr_sales_order 
 					INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
 					INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
					WHERE tr_spk.id_finance_company = '$row->id_finance_company' AND tr_sales_order.tgl_cetak_invoice = '$tgl_bulan2'
					AND tr_sales_order.id_dealer = '$id_dealer'")->row();

				$cek_jual2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc 
					INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
					INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
					INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
					WHERE tr_spk_gc.id_finance_company = '$row->id_finance_company' AND tr_sales_order_gc.tgl_cetak_invoice = '$tgl_bulan2'
					AND tr_sales_order_gc.id_dealer = '$id_dealer'")->row();
				$juml = $cek_jual->jum + $cek_jual2->jum;
				$hasil = ($juml!=0) ? $juml : "" ;
 				echo "<td>$hasil</td>";
 				$s_total += $juml;
 			}
 			echo "<td>$s_total</td>
		</tr>
		";
	}
	?>
	<tr>
		<td>Total</td>
		<?php
		$g_total=0;
		for ($i=1; $i <= 31; $i++) { 
			$tgl = sprintf("%'.02d",$i);				
			$bulan_2 = sprintf("%'.02d",$bulan);				
				$cek_jual = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum  FROM tr_sales_order 
					INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
					INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
					WHERE tr_sales_order.tgl_cetak_invoice = '$tgl_bulan2'
					AND tr_sales_order.id_dealer = '$id_dealer'")->row();

			$cek_jual2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc 
					INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
					INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
					INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
					WHERE tr_sales_order_gc.tgl_cetak_invoice = '$tgl_bulan2'
					AND tr_sales_order_gc.id_dealer = '$id_dealer'")->row();
			$jumla = $cek_jual->jum + $cek_jual2->jum;
			$hasil2 = ($jumla!=0) ? $jumla : "" ;
			echo "<td>$hasil2</td>";
			$g_total += $jumla;
		}
		echo "<td>$g_total</td>";
		?>
	</tr>
</table>