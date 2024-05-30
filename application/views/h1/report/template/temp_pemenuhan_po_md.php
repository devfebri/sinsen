<?php 
if($tipe=='DO'){ 	
	$nama_file='pemenuhan_do_md';	
}else if($tipe=='Penerimaan'){
	$nama_file='pemenuhan_po_md';	
}else if($tipe=='PO'){
	$nama_file='rekap_po_ahm';	
}

$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$nama_file.'_'.$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

if($tipe=='DO'){ 			 	
?>
	<table border="1">  
	 	<tr> 		
	 		<td align="center">No</td>
	 		<td align="center">Nama Dealer</td>
	 		<td align="center">Kode Item</td>
	 		<td align="center">PO MD</td>
	 		<td align="center">DO AHM</td>
	 		<td align="center">Service Rate</td> 		
	 	</tr>
	 	<?php 
	 	$no=1;
	 	$bulan_2 = sprintf("%'.02d",$bulan);				
	 	$tahun_bulan = $tahun."-".$bulan_2;
	 	$sql = $this->db->query("SELECT tr_po.id_po, tr_po_detail.id_item,SUM(tr_po_detail.qty_po_fix) AS jum FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
	 		WHERE LEFT(tr_po.tgl,7)='$tahun_bulan' GROUP BY tr_po.id_po,tr_po_detail.id_item"); 	 	
	 	foreach ($sql->result() as $row) { 		
	 		//if($row->jum=='') $row->jum = 0;
	 		if($row->jum>0){
		 		$spl = explode("-",$row->id_item);
		 		$id_tipe = $spl[0];
		 		$id_warna = $spl[1];
		 		$sql2 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb 	 				 		
		 			WHERE LEFT(tr_sipb.tgl_sipb,7)='$tahun_bulan' AND id_tipe_kendaraan = '$id_tipe' ANd id_warna = '$id_warna'")->row(); 	 	
		 		$service = round(($sql2->jum / $row->jum) * 100, 2);
		 		echo "
		 			<tr>
		 				<td>$no</td>
		 				<td>E20</td>
		 				<td>$row->id_item</td>
		 				<td>$row->jum</td>
		 				<td>$sql2->jum</td>
		 				<td>$service %</td> 			
		 			</tr>
		 		";
		 		$no++;
		 	}
	 	}
	 	?>
	</table>
<?php 
}else if($tipe=='Penerimaan'){
?>
	<table border="1">  
	 	<tr> 		
	 		<td align="center">No</td>
	 		<td align="center">Nama Dealer</td>
	 		<td align="center">Kode Item</td>
	 		<td align="center">PO MD</td>
	 		<td align="center">Terima dari AHM</td>
	 		<td align="center">Service Rate</td> 		
	 	</tr>
	 	<?php 
	 	$no=1;
	 	$bulan_2 = sprintf("%'.02d",$bulan);				
	 	$tahun_bulan = $tahun."-".$bulan_2;
	 	$sql = $this->db->query("SELECT tr_po.id_po, tr_po_detail.id_item,SUM(tr_po_detail.qty_po_fix) AS jum FROM tr_po INNER JOIN tr_po_detail ON tr_po.id_po = tr_po_detail.id_po 
	 		WHERE LEFT(tr_po.tgl,7)='$tahun_bulan' GROUP BY tr_po.id_po,tr_po_detail.id_item"); 	 	
	 	foreach ($sql->result() as $row) { 		
	 		//if($row->jum=='') $row->jum = 0;
	 		if($row->jum>0){
		 		$spl = explode("-",$row->id_item);
		 		$id_tipe = $spl[0];
		 		$id_warna = $spl[1];
		 		$sql2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list 	 				 		
		 			WHERE LEFT(tr_shipping_list.tgl_sl,7)='$tahun_bulan' AND id_modell = '$id_tipe' ANd id_warna = '$id_warna'")->row(); 	 	
		 		$service = round(($sql2->jum / $row->jum) * 100, 2);
		 		echo "
		 			<tr>
		 				<td>$no</td>
		 				<td>E20</td>
		 				<td>$row->id_item</td>
		 				<td>$row->jum</td>
		 				<td>$sql2->jum</td>
		 				<td>$service %</td> 			
		 			</tr>
		 		";
		 		$no++;
		 	}
	 	}
	 	?>
	</table>

<?php 
}else if($tipe=='PO'){
?>
	<table border="1">  
	 	<tr> 		
	 		<td align="center">No</td>
	 		<td align="center">No PO</td>
	 		<td align="center">Jenis PO</td>
			<td align="center">Periode</td>
	 		<td align="center">Kode Item</td>
	 		<td align="center">Deskripsi</td>
	 		<td align="center">Warna</td>
	 		<td align="center">Qty Order</td>		
	 	</tr>
		<?php 
			$no=1;
			$total = 0;
			$bulan_2 = sprintf("%'.02d",$bulan);				
			$tahun_bulan = $bulan_2."-".$tahun;

			$sql = $this->db->query("
				select a.id_po, a.jenis_po , a.bulan , a.tahun, b.id_item , c.tipe_ahm , d.warna , 
				sum( (case when b.qty_po_fix is null then b.qty_order else b.qty_po_fix end)) as qty
				from tr_po a 
				join tr_po_detail b on a.id_po = b.id_po 
				join ms_tipe_kendaraan c on left(b.id_item,3) = c.id_tipe_kendaraan 
				join ms_warna d on RIGHT (b.id_item,2) = d.id_warna 
				where a.status not in ('reject_ahm','input') and bulan = '$bulan_2' and tahun ='$tahun'
				group by a.id_po, a.jenis_po , a.bulan , a.tahun, b.id_item , c.tipe_ahm , d.warna 
				order by a.jenis_po desc, a.id_po asc, b.id_item asc, c.tipe_ahm asc, d.warna asc
			"); 	 	
			foreach ($sql->result() as $row) { 		
				if($row->qty > 0){
					echo "
						<tr>
							<td>$no</td>
							<td>$row->id_po</td>
							<td>$row->jenis_po</td>
							<td>$tahun_bulan</td>
							<td>$row->id_item</td>
							<td>$row->tipe_ahm</td>
							<td>$row->warna</td>
							<td>$row->qty</td>		
						</tr>
					";
					$no++;
					$total += $row->qty;
				}
			}
			echo "
				<tr>
					<td colspan='6'>Total</td>
					<td>$total</td>
				</tr>
			";
	 	?>
</table>
<?php
}
?>


