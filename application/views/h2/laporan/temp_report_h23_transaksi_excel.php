<?php 
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Reporting Transaksional H23 Main Dealer_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
	<caption>Reporting Transaksional H23 Main Dealer <?php echo $start_date." s/d ".$end_date?> - <?php echo date('H:i')?> WIB</caption>
 	<tr> 		
        <td align="center">No</td>
 		<td align="center">No AHASS</td>
        <td align="center">Tanggal NJB/NSC/Kwitansi</td>
 		<td align="center">No NJB/NSC/Kwitansi</td>
 		<td align="center">Tanggal WO/PKB</td>
 		<td align="center">No WO/PKB</td>
        <td align="center">Status WO/PKB</td>
        <td align="center">No Polisi</td>
		<td align="center">No Rangka</td>
		<td align="center">No Mesin</td>
        <td align="center">Type Motor (3 Digit)</td>
		<td align="center">Type Pekerjaan</td>
		<td align="center">Jenis Pekerjaan</td>
        <td align="center">Durasi Pekerjaan</td>
        <td align="center">No Claim</td>
        <td align="center">Jenis Bayar</td>
		<td align="center">Part Number</td>
		<td align="center">Deskripsi Part</td>
		<td align="center">Total Biaya</td>
		<td align="center">Biaya Jasa</td>
		<td align="center">Biaya Parts</td>
        <td align="center">Diskon Jasa</td>
		<td align="center">Diskon Parts</td>
        <td align="center">Program Promo Jasa</td>
		<td align="center">Program Promo Parts</td>
        <td align="center">Nama Mekanik</td>
		<td align="center">ID Mekanik</td>
		<td align="center">Nama SA</td>
		<td align="center">ID SA</td>
		<td align="center">Alasan Datang ke AHASS</td>
		<td align="center">Sumber Activity Promotion</td>
        <td align="center">Sumber Activity Capacity</td>
		<td align="center">Pos Service</td>
 	</tr>

<?php 
 	$nom=1;	
	if($transaksi->num_rows()>0){
		foreach ($transaksi->result() as $row) { 
			$grand_total = number_format($row->grand_total, 0, ',', '.');	
			$harga_jasa = number_format($row->harga_jasa, 0, ',', '.');
			$harga_part = number_format($row->harga_part, 0, ',', '.');	
 		echo "
 			<tr>
 				<td>$nom</td>
 				<td>'$row->kode_dealer_md</td>
				<td>$row->created_njb_at</td>
 				<td>$row->no_njb</td>
 				<td>$row->created_at</td>
 				<td>$row->id_work_order</td>
				<td>$row->status</td>
 				<td>$row->no_polisi</td>
				<td>$row->no_rangka</td>
 				<td>$row->no_mesin</td>
				<td>$row->id_tipe_kendaraan</td>
				<td>$row->tipe_jasa</td>
				<td>$row->deskripsi</td>
				<td>$row->durasi_pekerjaan</td>
				<td>$row->no_claim_c2</td>
 				<td>$row->tipe_pembayaran</td>
				<td>$row->id_part</td>
 				<td>$row->nama_part</td>
				<td>$grand_total</td>
				<td>$harga_jasa</td>
				<td>$harga_part</td>
                <td>$row->diskon_jasa</td>
				<td>$row->diskon_part</td>
                <td>$row->promo_jasa</td>
				<td>$row->promo_part</td>
 				<td>$row->nama_mekanik</td>
				<td>$row->id_mekanik</td>
 				<td>$row->nama_sa</td>
				<td>$row->id_sa</td>
				<td>$row->alasan_ke_ahass</td>
				<td>$row->name</td>
                <td>$row->keterangan</td>
                <td>-</td>
			
 			</tr>
	 	";
 		$nom++;
 		}
	}else{
		echo "<td colspan='16' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>


