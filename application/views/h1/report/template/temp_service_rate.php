<?php 
//$bln = sprintf("%'.02d",$bulan);
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ServiceRate_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Nama Dealer</td>
 		<td align="center">Kode Tipe</td> 		 		
 		<td align="center">Kode Item</td>
 		<td align="center">Qty PO</td> 		 		
 		<td align="center">Qty Pemenuhan</td> 		 		 		
 		<td align="center">% Service Rate</td> 		 		 		
 	</tr> 	
 	<?php 
 	$no=1; 
 	$sql = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
 		LEFT JOIN tr_po_dealer ON tr_po_dealer.id_po = tr_do_po.no_po
 		LEFT JOIN tr_po_dealer_detail ON tr_po_dealer.id_po = tr_po_dealer_detail.id_po
 		LEFT JOIN ms_item ON tr_po_dealer_detail.id_item = ms_item.id_item
 		LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 		LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
 		WHERE tr_do_po.tgl_do BETWEEN '$tgl1' AND '$tgl2' GROUP BY tr_po_dealer.id_po");
 	foreach ($sql->result() as $isi) {
 		$cek = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
 			WHERE tr_do_po.no_po = '$isi->id_po'")->row();
 		$persen = floor(($isi->qty_order / $cek->jum) * 100);
 		echo "
 		<tr>
 			<td>$isi->nama_dealer</td>
 			<td>$isi->tipe_ahm</td>
 			<td>$isi->id_item</td>
 			<td>$isi->qty_order</td> 			
 			<td>$cek->jum</td> 		
 			<td>$persen %</td>
 		</tr>
 		";
 		$no++;
 	}
 	
 	?>
</table>
