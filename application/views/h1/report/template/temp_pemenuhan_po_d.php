<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=pemenuhan_po_dealer_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Nama Dealer</td>
 		<td align="center">Type</td>
 		<td align="center">Warna</td>
 		<td align="center">PO (Unit)</td>
 		<td align="center">Pemenuhan (Unit)</td>
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
