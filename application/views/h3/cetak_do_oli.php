<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang3($a){
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
			return number_format($a, 0, ',', '.');
		} ?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Cetak</title>
	<style>
		@media print {
			@page {
				sheet-size: 210mm 297mm;
				margin-left: 1cm;
				margin-right: 1cm;
				margin-bottom: 1cm;
				margin-top: 1cm;
			}
			.text-center{text-align: center;}
			.table {
					width: 100%;
					max-width: 100%;
					border-collapse: collapse;
				   /*border-collapse: separate;*/
				}
			.table-bordered tr td {
					border: 0.001em solid black;
					padding-left: 6px;
					padding-right: 6px;
				}
			body{
				font-family: "Arial";
				font-size: 11pt;
			}
		}
	</style>
</head>

<body>

	<style>
		@media print {
				@page {
					sheet-size: 210mm 297mm;
					margin-left: 1cm;
					margin-right: 1cm;
					margin-bottom: 1cm;
					margin-top: 1cm;
				}
		}
	</style>
	<table class="table">
		<tr>
			<td colspan="2" align="center" style="font-size: 15pt"><b>DO OLI REGULER</b></td>
		</tr>		
	</table><br>	
	
	<table width="100%" border="0">		
		<tr>
			<td width="20%"><br>Tanggal DO</td><td width="1%"><br>:</td>
			<td width="30%"><br><?php echo $sql->tgl_do ?></td><td width="2%"></td>
			<td width="20%"><br>Kode Customer</td><td width="1%"><br>:</td>
			<td width="30%"><br><?php echo $sql->kode_dealer_md ?></td>
		</tr>
		<tr>
			<td>No DO</td><td>:</td>
			<td><?php echo $sql->no_do_oli_reguler ?></td><td></td>
			<td>Nama Customer</td><td>:</td>
			<td><?php echo $sql->nama_dealer ?></td>
		</tr>
		<tr>
			<td>Tanggal SO</td><td>:</td>
			<td><?php echo $sql->tgl_so ?></td><td></td>
			<td>Alamat Customer</td><td>:</td>
			<td><?php echo $sql->alamat ?></td>
		</tr>
		<tr>
			<td>No SO</td><td>:</td>
			<td><?php echo $sql->no_so_oil ?></td><td></td>			
		</tr>
		<tr>
			<td colspan="7">
				<table border="1" width="100%" class="table-bordered">
					<tr>
						<td>No</td>
						<td>Part Number</td>
						<td>Nama Part</td>
						<td>HET</td>
						<td>Qty</td>
						<td>Diskon Satuan (%)</td>
						<td>Diskon Campaign (%)</td>
						<td>Nilai (Amount)</td>
					</tr>
					<?php 
			    $no=1;$total=0;$g_total=0;
			    $sql2 = $this->db->query("SELECT * FROM tr_create_do_oli_detail LEFT JOIn ms_part ON tr_create_do_oli_detail.id_part = ms_part.id_part 			
							WHERE tr_create_do_oli_detail.no_do_oli_reguler = '$no_do_oli_reguler'");
			    foreach ($sql2->result() as $isi) {			      
			      $dpp = $isi->harga_md_dealer/1.1;
			      $harga_disc = $dpp - ($isi->harga_md_dealer * ($isi->disc_satuan/100)) - ($isi->harga_md_dealer * ($isi->disc_satuan/100));
			      echo "
			        <tr>
			          <td>$no</td>          
			          <td>$isi->id_part</td>
			          <td>$isi->nama_part</td>
			          <td align='right'>".mata_uang3($isi->harga_md_dealer)."</td>          
			          <td align='right'>$isi->qty_supply</td>          
			          <td align='right'>0</td>          
			          <td align='right'>0</td>          			          
			          <td align='right'>".mata_uang3($nilai = $isi->harga_md_dealer * $isi->qty_supply)."</td>          			          
			        </tr>";
			        $no++;        
			        $total += $nilai;
			    }
			    ?>
			    <tr>
			    	<td align="right" colspan="7">Sub Total</td>
			    	<td align="right"><?php echo mata_uang3($total) ?></td>			    	
			    </tr>
			    <tr>
			    	<td align="right" colspan="7">Total Diskon</td>
			    	<td align="right"><?php echo mata_uang3($disk = $sql->diskon_insentif + $sql->diskon_cashback) ?></td>			    	
			    </tr>
			    <tr>
			    	<td align="right" colspan="7">Total PPN</td>
			    	<td align="right"><?php echo mata_uang3($ppn = ($total - $disk) * 0.1) ?></td>			    	
			    </tr>
			    <tr>
			    	<td align="right" colspan="7">Total</td>
			    	<td align="right"><?php echo mata_uang3($total - $ppn) ?></td>			    	
			    </tr>
				</table>				
			</td>
		</tr>

		<tr>
			<td colspan="4"></td>
			<td colspan="2" align="left"><br><br>Jambi, <?php echo date("d/m/Y") ?></td>
		</tr>
		<tr>
			<td align="center" colspan="2">DIBUAT OLEH,</td>
			<td colspan="2" align="center">DIKETAHUI OLEH,</td>
			<td colspan="2" align="left">DISETUJUI OLEH,</td>			
		</tr>
		<tr>
			<td><br><br><br></td>
		</tr>
		
		<tr>
			<td align="center"><br><br>(Admin H3)</td>
			<td colspan="2" align="center"><br><br>(Atasan H3)</td>
			<td colspan="4" align="left"><br><br>(<?php echo $sql->nama_dealer ?>)</td>						
		</tr>

	</table>
</body>
</html>
