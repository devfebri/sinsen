<?php 
function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 0, ',', '.');
}

$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=stock_unit_qty_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
  <tr>
    <td align="center" rowspan="2">Kode Type</td>
    <td align="center" rowspan="2">Type</td>
    <td align="center" rowspan="2">Desc</td>
    <td colspan="5" align="center">Stok Per Awal</td>
    <td align="center" rowspan="2">DO AHM</td>
    <td align="center" rowspan="2">Terima Dari AHM</td>
    <td align="center" rowspan="2">Unfill Real Time</td>
    <td align="center" rowspan="2">Pembatalan DO</td>
		<td align="center" rowspan="2">PO Main Dealer</td> 
    <td align="center" rowspan="2">Total Add PO MD</td>
    <td align="center" rowspan="2">Total PO Fix + Add</td>
    <td align="center" rowspan="2">Plan Dist. AHM</td>
    <td align="center" rowspan="2">% Service Rate</td>
    <td align="center" rowspan="2">MD Distribusi ke Dealer</td>
    <td colspan="3" align="center">Stock Per Sekarang</td>
    <td align="center" rowspan="2">Total</td>
 	</tr>    	                    
 	<tr> 		
 		<td align="center">Gudang</td>
 		<td align="center">Retur</td>
 		<td align="center">Intransit</td>
 		<td align="center">Unfill Bulan Lalu</td>
 		<td align="center">Total</td>
 		<td align="center">Gudang</td>
 		<td align="center">Retur</td>
 		<td align="center">Intransit + Unfill</td>
 	</tr>
 	<?php
 	$a1=$bulan-1;$b1=$tahun;	  	
	if($a1 == "0"){
	  $a1 = "12";
	  $b1 = $tahun-1;
	}
 	$bulan_2 = sprintf("%'.02d",$bulan);				
 	$bulan_lalu = sprintf("%'.02d",$a1);				
 	$tahun_bulan = $tahun."-".$bulan_2;
 	$tahun_bulan_spasi = $bulan_2.$tahun;
 	$tahun_bulan_lalu = $b1."-".$bulan_lalu;
 	$sql = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
 		INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
 		GROUP BY ms_tipe_kendaraan.id_segment");
 	foreach ($sql->result() as $row) {
 		echo "
 			<tr>
 				<td colspan='22'>$row->segment</td>
 			</tr>"; 		
 			$t_gudang=0;$t_retur=0;$t_intransit=0;$t_unfill=0;$t_total=0;$t_do_ahm=0;$t_terima_ahm=0;$t_unfill_real=0;$t_po_reg=0;$t_po_add=0;$t_total_po=0;$t_displan=0;$t_surat_jalan=0;
 			$t_gudang_s=0;$t_retur_s=0;$t_jum_s=0;$t_tot_s=0;
 			$sql2 = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		 		INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
		 		GROUP BY tr_scan_barcode.tipe_motor");
 			foreach ($sql2->result() as $row2) {
 				$gudang = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row2->id_tipe_kendaraan' AND status = '1'")->row();
 				$retur = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row2->id_tipe_kendaraan' AND status = '7'")->row();
		 		
		 		//cek intransit
		 		$cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
	       	WHERE tr_shipping_list.id_modell = '$row2->id_tipe_kendaraan' AND LEFT(tgl_sl,7) = '$tahun_bulan'
	       	AND ms_item.bundling <> 'Ya'")->row();
				$cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan AND tr_scan_barcode.warna = ms_item.id_warna 
					WHERE tipe_motor = '$row2->id_tipe_kendaraan'
					AND ms_item.bundling <> 'Ya'")->row();    

		 		$cek_sl2_jum=0;$cek_sl1_jum=0;
	      if(isset($cek_sl2->jum)) $cek_sl2_jum = $cek_sl2->jum;
	      if(isset($cek_sl1->jum)) $cek_sl1_jum = $cek_sl1->jum;
	      if($cek_sl1_jum - $cek_sl2_jum > 0){
	      	$r2 = $cek_sl1_jum - $cek_sl2_jum;
	      }else{
	      	$r2 = 0;
	      }


				//cek unfill
				$cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
	      	WHERE tr_sipb.id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND LEFT(tgl_sipb,7) = '$tahun_bulan_lalu'
	      	AND ms_item.bundling <> 'Ya'")->row();                
				$cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
				 	WHERE tr_shipping_list.id_modell = '$row2->id_tipe_kendaraan'
				 	AND ms_item.bundling <> 'Ya'")->row();	      
	      if($cek_in1->jum - $cek_in2->jum > 0){
	      	$rr = $cek_in1->jum - $cek_in2->jum;
	      }else{
	      	$rr = 0;
	      }

	      //do ahm
	      $do_ahm = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb 
	      	WHERE tr_sipb.id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND LEFT(tgl_sipb,7) = '$tahun_bulan'")->row();                
	      //terima ahm
	      $terima_ahm = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row2->id_tipe_kendaraan' AND status = '1' AND LEFT(tgl_penerimaan,7) = '$tahun_bulan'")->row();

	      //unfill realtime	      
				$cek_in3 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
	      	WHERE tr_sipb.id_tipe_kendaraan = '$row2->id_tipe_kendaraan'
	      	AND ms_item.bundling <> 'Ya'")->row();                
				$cek_in4 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
				 	WHERE tr_shipping_list.id_modell = '$row2->id_tipe_kendaraan'
				 	AND ms_item.bundling <> 'Ya'")->row();	      
	      if($cek_in3->jum - $cek_in4->jum > 0){
	      	$rt = $cek_in3->jum - $cek_in4->jum;
	      }else{
	      	$rt = 0;
	      }
	      //po reg
	      $po_reg = $this->db->query("SELECT SUM(qty_po_fix) AS jum FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
	      		INNER JOIN ms_item ON ms_item.id_item = tr_po_detail.id_item
	      		WHERE ms_item.id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND tr_po.jenis_po = 'PO Reguler' AND LEFT(tr_po.tgl,7) = '$tahun_bulan'")->row();
	      //po add
	      $po_add = $this->db->query("SELECT SUM(qty_po_fix) AS jum FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
	      		INNER JOIN ms_item ON ms_item.id_item = tr_po_detail.id_item
	      		WHERE ms_item.id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND tr_po.jenis_po = 'PO Additional' AND LEFT(tr_po.tgl,7) = '$tahun_bulan'")->row();
		 		//displan
	      $displan = $this->db->query("SELECT SUM(qty_plan) AS jum FROM tr_displan 
	      	WHERE id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND RIGHT(tanggal,6) = '$tahun_bulan_spasi'")->row();                
	      //md dist dealer
	      $surat_jalan = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
	      		INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
	      		WHERE ms_item.id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tahun_bulan'")->row();
	      //stok sekarang
	      $gudang_s = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row2->id_tipe_kendaraan' AND status = '1'")->row();
 				$retur_s = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row2->id_tipe_kendaraan' AND status = '7'")->row();
		 		
		 		//cek intransit
		 		$cek_sl1_s = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
	       	WHERE tr_shipping_list.id_modell = '$row2->id_tipe_kendaraan' AND LEFT(tgl_sl,7) = '$tahun_bulan'
	       	AND ms_item.bundling <> 'Ya'")->row();
				$cek_sl2_s = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan AND tr_scan_barcode.warna = ms_item.id_warna 
					WHERE tipe_motor = '$row2->id_tipe_kendaraan'
					AND ms_item.bundling <> 'Ya'")->row();    

		 		$cek_sl2_jum_s=0;$cek_sl1_jum_s=0;
	      if(isset($cek_sl2_s->jum)) $cek_sl2_jum_s = $cek_sl2_s->jum;
	      if(isset($cek_sl1_s->jum)) $cek_sl1_jum_s = $cek_sl1_s->jum;
	      if($cek_sl1_jum_s - $cek_sl2_jum_s > 0){
	      	$r2_s = $cek_sl1_jum_s - $cek_sl2_jum_s;
	      }else{
	      	$r2_s = 0;
	      }


				//cek unfill
				$cek_in1_s = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
	      	WHERE tr_sipb.id_tipe_kendaraan = '$row2->id_tipe_kendaraan' AND LEFT(tgl_sipb,7) = '$tahun_bulan_lalu'
	      	AND ms_item.bundling <> 'Ya'")->row();                
				$cek_in2_s = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
				 	WHERE tr_shipping_list.id_modell = '$row2->id_tipe_kendaraan'
				 	AND ms_item.bundling <> 'Ya'")->row();	      
	      if($cek_in1_s->jum - $cek_in2_s->jum > 0){
	      	$rr_s = $cek_in1_s->jum - $cek_in2_s->jum;
	      }else{
	      	$rr_s = 0;
	      }
	      if($gudang->jum>0||$retur->jum>0||$r2>0||$rr>0||$do_ahm->jum>0||$terima_ahm->jum>0||$rt>0||$po_reg->jum>0||$po_add->jum>0||$displan->jum>0||$surat_jalan->jum>0||$gudang_s->jum>0||$retur_s->jum>0||$rr_s>0||$r2_s>0){
			 		echo "
			 			<tr>
			 				<td>$row2->id_tipe_kendaraan</td>
			 				<td>$row2->deskripsi_ahm</td>
			 				<td>$row2->tipe_ahm</td>
			 				<td align='right'>$gudang->jum</td>
			 				<td align='right'>$retur->jum</td>
			 				<td align='right'>$r2</td>
			 				<td align='right'>$rr</td>
			 				<td align='right'>".$total_stok = $gudang->jum + $retur->jum + $r2 + $rr."</td>
			 				<td align='right'>$do_ahm->jum</td>
			 				<td align='right'>$terima_ahm->jum</td>
			 				<td align='right'>$rt</td>
			 				<td align='right'>0</td>
			 				<td align='right'>$po_reg->jum</td>
			 				<td align='right'>$po_add->jum</td>
			 				<td align='right'>".$total_po = $po_add->jum + $po_reg->jum."</td>
			 				<td align='right'>$displan->jum</td>
			 				<td align='right'>0</td>
			 				<td align='right'>$surat_jalan->jum</td>
			 				<td align='right'>$gudang_s->jum</td>
			 				<td align='right'>$retur_s->jum</td>
			 				<td align='right'>".$jum_s = $r2_s + $rr_s."</td>
			 				<td align='right'>".$tot_s = $r2_s + $rr_s + $gudang_s->jum + $retur_s->jum."</td>
			 			</tr>";
			 		$t_gudang += $gudang->jum;
			 		$t_retur += $retur->jum;
			 		$t_intransit += $r2;
			 		$t_unfill += $rr;
			 		$t_total += $total_stok;
			 		$t_do_ahm += $do_ahm->jum;
			 		$t_terima_ahm += $terima_ahm->jum;
			 		$t_unfill_real += $rt;
			 		$t_po_reg += $po_reg->jum;
			 		$t_po_add += $po_add->jum;
			 		$t_total_po += $total_po;
			 		$t_displan += $displan->jum;
			 		$t_surat_jalan += $surat_jalan->jum;
			 		$t_gudang_s += $gudang_s->jum;
			 		$t_retur_s += $retur_s->jum;
			 		$t_jum_s += $jum_s;
			 		$t_tot_s += $tot_s;
			 	}
		 	}
 	} 
 	?> 	
 	<tr>
 		<td colspan="3">TOTAL</td>
 		<td align='right'><?php echo mata_uang($t_gudang); ?></td>
 		<td align='right'><?php echo mata_uang($t_retur); ?></td>
 		<td align='right'><?php echo mata_uang($t_intransit); ?></td>
 		<td align='right'><?php echo mata_uang($t_unfill); ?></td>
 		<td align='right'><?php echo mata_uang($t_total); ?></td>
 		<td align='right'><?php echo mata_uang($t_do_ahm); ?></td>
 		<td align='right'><?php echo mata_uang($t_terima_ahm); ?></td>
 		<td align='right'><?php echo mata_uang($t_unfill_real); ?></td>
 		<td align='right'>0</td>
 		<td align='right'><?php echo mata_uang($t_po_reg); ?></td>
 		<td align='right'><?php echo mata_uang($t_po_add); ?></td>
 		<td align='right'><?php echo mata_uang($t_total_po); ?></td>
 		<td align='right'><?php echo mata_uang($t_displan); ?></td>
 		<td align='right'>0</td>
 		<td align='right'><?php echo mata_uang($t_surat_jalan); ?></td>
 		<td align='right'><?php echo mata_uang($t_gudang_s); ?></td>
 		<td align='right'><?php echo mata_uang($t_retur_s); ?></td>
 		<td align='right'><?php echo mata_uang($t_jum_s); ?></td>
 		<td align='right'><?php echo mata_uang($t_tot_s); ?></td>



 	</tr>
</table>


