<?php 
$no = $tgl1." sd ".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Tarikan Transaksi Tanpa PKB - Indirect ".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Laporan Tarikan Data dari <?php echo $tgl1." s/d ".$tgl2?></caption>
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No AHASS</td>
 		<td align="center">Nama AHASS</td>
 		<td align="center">No Nota</td>
 		<td align="center">Tanggal Nota</td>
		<td align="center">Part Number</td>
		<td align="center">Deskripsi Part Number</td>
		<td align="center">Quantity</td>
		<td align="center">Nominal Transaksi</td>
 	</tr>
	<?php 
 	$no=1;
	$filter_dealer = '';
          if ($id_dealer!='all') {
            $filter_dealer = "AND b.id_dealer='$id_dealer'";
          }


	$tgl2 = date_format(date_add(date_create($tgl2),date_interval_create_from_date_string("1 days")),"Y-m-d");

	$sql = $this->db->query("
			SELECT c.kode_dealer_ahm, c.nama_dealer, b.no_nsc, b.tgl_nsc, a.id_part, d.nama_part, a.qty, ifnull(((a.qty * a.harga_beli)-a.diskon_value),0) as nominal
			FROM tr_h23_nsc_parts a 
			join tr_h23_nsc b on a.no_nsc =b.no_nsc
			join ms_dealer c on c.id_dealer = b.id_dealer
			join ms_part d on d.id_part_int = a.id_part_int 	
			WHERE b.created_at >= '$tgl1' AND b.created_at <= '$tgl2' and b.referensi ='sales' and b.status is null and d.kelompok_part !='FED OIL' $filter_dealer 
			order by c.nama_dealer asc, b.created_at asc, a.no_nsc asc");	 	
 	foreach ($sql->result() as $row) { 		
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>'$row->kode_dealer_ahm</td>
 				<td>$row->nama_dealer</td>
 				<td>$row->no_nsc</td>
 				<td>$row->tgl_nsc</td>
				<td>'$row->id_part</td>
				<td>$row->nama_part</td>
				<td>$row->qty</td>
				<td>$row->nominal</td>
 			</tr>
	 	";
 		$no++;
 	}
	?>
</table>


