<?php 
function mata_uang3($a){
  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    if(is_numeric($a) AND $a != 0 AND $a != ""){
      return number_format($a, 0, ',', '.');
    }else{
      return $a;
    }        
}

$no = $tgl1;
if (empty($download)) {
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Penjualan_harian_".$no.".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>
<center>
	<h2>PENJUALAN HARIAN</h2>
</center>
<table width="100%">
	<tr>
		<td width="10%">Main Dealer</td>
		<td width="30%">: PT Sinar Sentosa Primatama</td>	
		<td></td>
		<td>Tgl DO</td>
		<td>: <?php echo $tgl1 ?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>: Jl. Kolonel Abunjano, No.09</td>
		<td width="10%"></td>
		<td width="10%">Tgl & Waktu</td>
		<td width="30%">: <?php echo gmdate("d-m-Y H:i", time()+60*60*7) ?></td>
	</tr>	
</table>
 <table border='1'>  
 	<?php  	 	
 	$g_total=0;
 	$sql = $this->db->query("SELECT * FROM tr_do_po INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer WHERE tgl_do = '$tgl1'");
 	foreach ($sql->result() as $isi) { 		 		 	
 		echo "
 			<tr>
 				<td colspan='11'><b>Dealer : $isi->nama_dealer</b></td>
 			</tr>
		 	<tr> 		 		
		 		<td align='center'>No</td>
		 		<td align='center'>JnsByr</td>
		 		<td align='center'>No DO</td> 		 		
		 		<td align='center'>Nama</td> 		 		
		 		<td align='center'>Item SM</td> 		
		 		<td align='center'>Jumlah</td> 		
		 		<td align='center'>Harga Kosong</td> 		
		 		<td align='center'>Disc</td> 		
		 		<td align='center'>Disc Top</td> 		
		 		<td align='center'>PPN</td> 		
		 		<td align='center'>Jumlah</td> 		
		 	</tr>";
 		// $sql2 = $this->db->query("SELECT * FROM tr_do_po_detail WHERE no_do = '$isi->no_do' AND qty_do>0");
		$sql2 = $this->m_admin->get_detail_inv_dealer($isi->no_do);
 		$no=1;$t_total=0;
 		foreach ($sql2['detail'] as $row) {
 			echo "
		 	<tr> 		 		
		 		<td>$no</td>		 		
		 		<td>R</td>		 		
		 		<td>$isi->no_do</td>		 		
		 		<td>$isi->nama_dealer</td>		 		
		 		<td>".$row['id_item']."</td>		 		
		 		<td>".$row['qty_do']."</td>		 		
		 		<td align='right'>".mata_uang3($row['subtotal'])."</td>		 		
		 		<td align='right'>".mata_uang3($row['diskon_tot'])."</td>		 		
		 		<td align='right'>".mata_uang3($row['diskon_top'])."</td>	 		
		 		<td align='right'>".mata_uang3($row['ppn'])."</td>		 		
		 		<td align='right'>".mata_uang3($row['subtotal_detail'])."</td>		 				 		
		 	</tr>";
		 	// $t_total += $total;
 			$no++;
 		}
 		$t_total = $sql2['total_bayar'];
 		echo "
 		<tr>
 			<td colspan='10' align='right'>Total</td>
 			<td align='right'>".mata_uang3($t_total)."</td>
 		</tr>
 		";
 		$g_total += $t_total;
 	}	
 	echo "
	<tr>
		<td colspan='10' align='right'>Total MD</td>
		<td align='right'>".mata_uang3($g_total)."</td>
	</tr>
	";
 	?>
</table>
