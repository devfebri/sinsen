<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=Report Goliath " .$no. ".xls");
header("Pragma: no-cache");
header("Expires: 0");
header('Cache-Control: must-revalidate');
header('Pragma: public');

?>

<table border="1">  
	<?php $start_date_2 = date("d/m/Y", strtotime($start_date));
			$end_date_2 = date("d/m/Y", strtotime($end_date)); ?>
	<caption>Report Goliath <?php echo $start_date_2 ."-". $end_date_2?></caption>
 	<tr> 		
 		<td align="center"><b>No</b></td>
		<td align="center"><b>No WO </b></td>
 		<td align="center"><b>No NSC</b></td>
 		<td align="center"><b>Tgl NSC</b></td>
 		<td align="center"><b>ID Type Kendaraan</b></td>
		<td align="center"><b>No Mesin</b></td>
		<td align="center"><b>No Rangka </b></td>
		<td align="center"><b>Tgl Pembelian</b></td>
		<td align="center"><b>Dealer Pembelian</b></td>
 		<td align="center"><b>KM Terakhir</b></td>
		<td align="center"><b>KPB Ke-</b></td>
 		<td align="center"><b>Tgl Service</b></td>
		<td align="center"><b>Harga Jasa</b></td>
 		<td align="center"><b>Qty Oil (Botol)</b></td>
 		<td align="center"><b>Harga Beli</b></td>
 		<td align="center"><b>Harga Setelah Diskon</b></td>
	</tr>
	
<?php 
 	$nom=1;	
	if($query_promo_goliath->num_rows()>0){
		foreach ($query_promo_goliath->result() as $key => $row) {
			$dealer_pembelian2 = isset($data_dealer_pembelian[$key]) ? $data_dealer_pembelian[$key] : (object) array('id_dealer' => '','nama_dealer' => '');
			if($dealer_pembelian2->id_dealer == $row->id_dealer){
				$dealer_pembelian2->nama_dealer = $dealer_pembelian2->nama_dealer;
			}else{
				$dealer_pembelian2->nama_dealer = 'Other Dealer';
			}
			echo "
				<tr>
					<td align='center'>$nom</td>
					<td>$row->id_work_order</td>
					<td>$row->no_nsc</td>
					<td>$row->tgl_nsc</td>
					<td>$row->id_tipe_kendaraan</td>
					<td>$row->no_mesin</td>
					<td>$row->no_rangka</td>
					<td>$row->tgl_pembelian_indo</td>
					<td>$dealer_pembelian2->nama_dealer</td>
					<td>$row->km_terakhir</td>
					<td align='center'>$row->kpb_ke</td>
					<td>$row->tgl_servis_indo</td>
					<td>$row->tot_pekerjaan</td>
					<td align='center'>$row->qty</td>
					<td>$row->harga_beli</td>
					<td>$row->subtotal</td>
				</tr>
			";
 		$nom++;
 		}
	}else{
		echo "<td colspan='16' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


