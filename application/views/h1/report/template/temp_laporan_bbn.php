<?php 

//$bln = sprintf("%'.02d",$bulan);

$no = date('dmY_Hi');

header("Content-type: application/octet-stream");

header("Content-Disposition: attachment; filename=LaporanBBN-".$no.".xls");

header("Pragma: no-cache");

header("Expires: 0");



?>

Laporan BBN AS OF  <?php echo date('d F Y - H:i') .' WIB <br>'; ?>

<br>

<table border="1">   	

 	<tr>

 		<td align="center">No</td> 

 		<td align="center">Tanggal SSU</td> 		 		 		 		 		

 		<td align="center">Nama Dealer</td> 		 		 		 		 		

 		<td align="center">No Mesin</td> 		 		 		 		 		

 		<td align="center">No Rangka</td> 	 		 		 		 		

 		<td align="center">Kode Tipe</td> 	 		 		 		 		

 		<td align="center">Nama Konsumen</td> 	 		 		 		

 		<td align="center">Tgl Pengajuan Ke MD</td> 			 		

 		<td align="center">No BASTD</td> 		 		 		 		 		

 		<td align="center">Tgl Entry Penerimaan</td> 		 		 		

 		<td align="center">Tgl Approve Finance</td> 		 		 		

 		<td align="center">Status Approve</td> 		 		 		 		

 		<td align="center">Tanggal Samsat</td> 		 		 		 		

 		<td align="center">Status BBN</td> 	 		
		<td align="center">No STNK</td> 	 		
		<td align="center">No Polisi</td> 		 		 		 		 		 		

 	</tr>

 	<?php 

 	$urut=1;

    if($list_data!='false'){

     	foreach ($list_data as $isi) {

     		echo "

         		<tr>

         			<td>$urut</td>

         			<td>$isi->tgl_cetak_invoice</td>

         			<td>$isi->nama_dealer</td>

         			<td>".$isi->no_mesin."</td>

         			<td>".$isi->no_rangka."</td>

         			<td>".$isi->tipe_motor."</td>

         			<td>".$isi->nama_bpkb."</td>

         			<td>".$isi->tgl_pengajuan_dealer."</td>

         			<td>".$isi->no_bastd."</td>

         			<td>".$isi->tgl_pembayaran."</td>

         			<td>".$isi->tgl_approval."</td>

         			<td>".$isi->status_approve_finance."</td>

         			<td>".$isi->tgl_pengajuan_samsat."</td>

         			<td>".$isi->status_bbn."</td>         			
				<td>".$isi->no_stnk."</td>         			
				<td>".$isi->no_pol."</td>

         		</tr>

     		";

     		$urut++;

     	}

    }

 	?> 

</table>