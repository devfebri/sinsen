<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=Report Promo Part " .$no. ".xls");
header("Pragma: no-cache");
header("Expires: 0");
header('Cache-Control: must-revalidate');
header('Pragma: public');

?>

<table border="1">  
	<?php $start_date_2 = date("d/m/Y", strtotime($start_date));
			$end_date_2 = date("d/m/Y", strtotime($end_date)); ?>
	<caption>Report Promo Part <?php echo $start_date_2 ."-". $end_date_2?> <br> <?php echo $nama_dealer->row()->nama_dealer ?></caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>No NSC</b></td>
 		<td align="center"><b>Tgl NSC</b></td>
 		<td align="center"><b>Referensi</b></td>
 		<td align="center"><b>No Referensi</b></td>
 		<!-- <td align="center"><b>Nama Customer</b></td> -->
 		<td align="center"><b>Kode Part</b></td>
		<td align="center"><b>Deskripsi Part</b></td>
 		<td align="center"><b>Kode Promo</b></td>
 		<td align="center"><b>Nama Promo</b></td>
		<td align="center"><b>Harga Part</b></td>
 		<td align="center"><b>Harga Part Setelah Diskon</b></td>
	</tr>
	
<?php 
 	$nom=1;	
	if($query_promo_part->num_rows()>0){
		foreach ($query_promo_part->result() as  $row) {
			echo "
				<tr>
					<td align='center'>$nom</td>
					<td>$row->no_nsc</td>
					<td>$row->tgl_nsc</td>
					<td>$row->referensi</td>
					<td>$row->id_referensi</td>
					<td>'$row->id_part</td>
					<td>$row->nama_part</td>
					<td>$row->id_promo</td>
					<td>$row->nama</td>
					<td>$row->harga_beli</td>
					<td>$row->subtotal</td>
				</tr>
			";
 		$nom++;
 		}
	}else{
		echo "<td colspan='12' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


