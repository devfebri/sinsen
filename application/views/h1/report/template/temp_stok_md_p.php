<?php 
//$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=StokMD.xls");
header("Pragma: no-cache");
header("Expires: 0");

function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
 		<td align="center">Tipe Motor</td>
 		<td align="center">Kode Item</td>
 		<td align="center">Deskripsi Type</td> 	
 		<td align="center">Tahun</td> 		
 		<td align="center">Status Lokasi</td> 		
 		<td align="center">Lokasi</td> 		
 		<td align="center">Status</td> 		
 		<td align="center">Harga</td> 		
 		<td align="center">No Faktur AHM</td> 		
 		<td align="center">Tgl Faktur</td> 		 	
 		<td align="center">No Shipping List</td> 		 		
 	</tr>
 	<?php 
 	$no=1; 
	$tot_harga = 0;
	$tot_ppn = 0;
 	$sql = $this->db->query("
		SELECT tr_shipping_list.no_shipping_list as no_sl, tr_scan_barcode.no_mesin AS nosin, tr_scan_barcode.no_rangka, ms_tipe_kendaraan.tipe_ahm, tr_scan_barcode.id_item, ms_tipe_kendaraan.deskripsi_ahm, tr_scan_barcode.lokasi, tr_scan_barcode.slot, tr_scan_barcode.tipe AS statuss, tr_fkb.tahun_produksi, (tr_invoice.harga /tr_invoice.qty) as harga, (tr_invoice.ppn /tr_invoice.qty) as ppn, tr_invoice.no_faktur, tr_invoice.tgl_faktur
		FROM tr_scan_barcode 
		INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		join tr_fkb on tr_fkb.no_mesin_spasi = tr_scan_barcode.no_mesin
		join tr_shipping_list on tr_shipping_list.no_mesin  = tr_scan_barcode.no_mesin 
		join tr_invoice on tr_invoice.no_sl = tr_shipping_list.no_shipping_list and tr_shipping_list.id_modell = tr_fkb.kode_tipe and tr_fkb.kode_warna =  tr_shipping_list.id_warna
		WHERE tr_scan_barcode.status = 1
		group by tr_shipping_list.no_shipping_list, tr_scan_barcode.no_mesin, tr_scan_barcode.no_rangka, ms_tipe_kendaraan.tipe_ahm, tr_scan_barcode.id_item, ms_tipe_kendaraan.deskripsi_ahm, tr_scan_barcode.lokasi, tr_scan_barcode.slot, tr_scan_barcode.tipe, tr_fkb.tahun_produksi
	");
 	foreach ($sql->result() as $isi) { 		
		$tot_harga +=$isi->harga;
		$tot_ppn +=$isi->ppn;
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->nosin</td>
 			<td>$isi->no_rangka</td>
 			<td>$isi->tipe_ahm</td>
 			<td>$isi->id_item</td>
 			<td>$isi->deskripsi_ahm</td>
 			<td>$isi->tahun_produksi</td>
 			<td>$isi->lokasi</td>
 			<td>$isi->slot</td>
 			<td>$isi->statuss</td> 	
 			<td>".number_format($isi->harga,0 , ',', '')."</td> 	
 			<td>$isi->no_faktur</td> 
 			<td>$isi->tgl_faktur</td> 		
			 <td>$isi->no_sl</td> 			
 		</tr>
 		";
 		$no++;
 	}

	$sql_intransit = $this->db->query("
		select a.no_shipping_list as no_sl, a.no_mesin as nosin, a.no_rangka , b.tipe_ahm , CONCAT(a.id_modell,'-',a.id_warna) as id_item, b.deskripsi_ahm , c.tahun_produksi , 'Intransit' as statuss, '' as lokasi, '' as slot, (d.harga /d.qty) as harga, (d.ppn /d.qty) as ppn,  d.no_faktur, d.tgl_faktur
		from tr_shipping_list a 
		join ms_tipe_kendaraan b on a.id_modell = b.id_tipe_kendaraan
		left join tr_invoice d on d.no_sl = a.no_shipping_list and a.id_modell = d.id_tipe_kendaraan and d.id_warna =  a.id_warna
		left join tr_fkb c on a.no_mesin = c.no_mesin_spasi 
		where a.no_mesin not in (
			select no_mesin from tr_scan_barcode tsb 
		)
	");

	$tot_harga_int = 0;
	$tot_ppn_int = 0;
	foreach ($sql_intransit->result() as $isi) { 		
		$tot_harga_int +=$isi->harga;
		$tot_ppn_int +=$isi->ppn;
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->nosin</td>
 			<td>$isi->no_rangka</td>
 			<td>$isi->tipe_ahm</td>
 			<td>$isi->id_item</td>
 			<td>$isi->deskripsi_ahm</td>
 			<td>$isi->tahun_produksi</td>
 			<td>$isi->lokasi</td>
 			<td>$isi->slot</td>
 			<td>$isi->statuss</td> 	
 			<td>".number_format($isi->harga,0 , ',', '')."</td> 	
			<td>$isi->no_faktur</td> 
			<td>$isi->tgl_faktur</td> 		
			<td>$isi->no_sl</td> 					
 		</tr>
 		";
 		$no++;
 	}
 	?>
	<tr>
		 <td colspan = "10" align="right">Harga DPP (Intransit)</td>
		 <td><?php echo number_format($tot_harga_int, 0, ',', '.');?></td>
	 </tr>
	<tr>
		<td colspan = "10" align="right">PPN (Intransit)</td>
		<td><?php echo number_format($tot_ppn_int, 0, ',', '.');?></td>
	</tr>
	<tr>
		<td colspan = "10" align="right">Total Harga (Intransit)</td>
		<td><?php echo number_format($tot_ppn_int+$tot_harga_int, 0, ',', '.');?></td>
	</tr>

	<tr>
		 <td colspan = "10" align="right">Harga DPP (Stok Unit)</td>
		 <td><?php echo number_format($tot_harga, 0, ',', '.');?></td>
	 </tr>
	<tr>
		<td colspan = "10" align="right">PPN (Stok Unit)</td>
		<td><?php echo number_format($tot_ppn, 0, ',', '.');?></td>
	</tr>
	<tr>
		<td colspan = "10" align="right">Total Harga (Stok Unit)</td>
		<td><?php echo number_format($tot_ppn+$tot_harga, 0, ',', '.');?></td>
	</tr>
</table>