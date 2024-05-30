<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Surat_jalan_dealer_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">No SJ</td>
 		<td align="center">Tgl SJ</td> 		 		
 		<td align="center">No DO</td> 		 	
 		<td align="center">Tgl DO</td> 		 		
 		<td align="center">Qty</td> 		
 		<td align="center">Nama Dealer</td> 
 		<td align="center">No Polisi</td> 
 		<td align="center">Nama Driver</td> 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("select tr_surat_jalan.no_surat_jalan as no_sj, tr_surat_jalan.tgl_surat , tr_picking_list.no_do , 
	 	count(tr_surat_jalan_detail.no_mesin) AS jum , ms_dealer.nama_dealer , b.no_plat , a.driver, c.tgl_do 
		FROM tr_surat_jalan
 		INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
 		INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
 		inner join tr_sppm a on a.no_surat_sppm = tr_surat_jalan.no_surat_sppm 
 		inner join ms_plat_dealer b on b.id_master_plat  = a.no_pol 
 		inner JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
 		join tr_do_po c on c.no_do = tr_picking_list.no_do 
 		WHERE tr_surat_jalan.tgl_surat BETWEEN '$tgl1' AND '$tgl2' GROUP BY tr_surat_jalan.no_surat_jalan");
 	foreach ($sql->result() as $isi) { 		
		$nama = strtoupper($isi->driver); 		 	
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->no_sj</td> 			 			 			 			 			 			
 			<td>$isi->tgl_surat</td> 			 			 			 			 			 			 			
 			<td>$isi->no_do</td> 			 					 			 			 			 			 			
 			<td>$isi->tgl_do</td> 			 			 			 			 			 			 			
 			<td>$isi->jum</td> 			 			 			 			 			 			 			
 			<td>$isi->nama_dealer</td> 				 			 			 			 			 			
 			<td>$isi->no_plat </td> 				 			 			 			 			 			
 			<td>$nama</td> 			 			 			 			 			 			 			
 		</tr>
 		";
 		$no++; 	
 	}
 	?>
</table>
