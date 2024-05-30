<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		    if(is_numeric($a) AND $a != 0 AND $a != ""){
		      return number_format($a, 0, ',', '.');
		    }else{
		      return $a;
		    } 
			} 
	?>

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
					border: 1px solid black;
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

<?php 
$hari = nama_hari($row->tgl_faktur);
$tgl_indo = tgl_indo($row->tgl_faktur);
if ($cetak=='st_bpkb'){ 
?>


	<div>
<br><br>
	<table class="table table-bordered">
		<tr>
			<td align="center" style="font-size: 12pt"><b>TANDA TERIMA</b></td>
		</tr>
	</table><br>
	<table width="80%">
		<tr>
			<td width="30%">No Tanda Terima</td>
			<td>: <?php echo $no_tanda_terima ?></td>
		</tr>
		<tr>
			<td width="30%">Tgl Tanda Terima</td>
			<td>: <?php echo tgl_indo($tgl_terima) ?></td>
		</tr>
	</table>
	<p style="font-size: 11pt;text-align: justify;">
		Pada hari ini, <?php echo $hari." ".$tgl_indo; ?> telah diterima dari PT. Sinar Sentosa Primatama, 1 buku BPKB dengan rincian sebagai berikut :
	</p>
	
	<table class="table table-bordered" style="font-size: 11pt;">
		<tr style="height: 55px">
			<td style="text-align: center;height: 25px"><b>Nama Konsumen</b></td>
			<td style="text-align: center;height: 25px"><b>No Mesin</b></td>
			<td style="text-align: center;height: 25px"><b>Tipe</b></td>
			<td style="text-align: center;height: 25px"><b>Warna</b></td>			
			<td style="text-align: center;height: 25px"><b>No BPKB</b></td>
		</tr>
		<tr>
			<?php 
			$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->row()->tipe_ahm;
			$warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$row->id_warna'")->row()->warna;
			$no_bpkb = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin ='$row->no_mesin'")->row()->no_bpkb;
			 ?>
			<td style="font-size: 10pt"><?php echo $row->pemohon ?></td>
			<td style="font-size: 10pt"><?php echo $row->no_mesin ?></td>
			<td style="font-size: 10pt"><?php echo $tipe ?></td>
			<td style="font-size: 10pt"><?php echo $warna ?></td>			
			<td style="font-size: 10pt"><?php echo $no_bpkb ?></td>
		</tr>
	</table><br><br>
	<table class="table">
		<tr>
			<td style="text-align: center;">Disetujui Oleh,<br><br><br><br><br><br></td>
			<td style="text-align: center;vertical-align: top;">Diserahkan Oleh,</td>
			<td style="text-align: center;vertical-align: top;">Diterima Oleh,</td>
		</tr>
		<tr>
			<td style="text-align: center;"><pre>(              )</pre></td>
			<td style="text-align: center;"><pre>(              )</pre></td>
			<td style="text-align: center;"><pre>(              )</pre></td>
		</tr>
	</table>

</div>

<?php }elseif($cetak=='st_stnk'){ ?>

<div>
<br><br>
	<table class="table table-bordered">
		<tr>
			<td align="center" style="font-size: 12pt"><b>TANDA TERIMA</b></td>
		</tr>
	</table><br>
	<table width="80%">
		<tr>
			<td width="30%">No Tanda Terima</td>
			<td>: <?php echo $no_tanda_terima ?></td>
		</tr>
		<tr>
			<td width="30%">Tgl Tanda Terima</td>
			<td>: <?php echo tgl_indo($tgl_terima) ?></td>
		</tr>
	</table>
	<p style="font-size: 11pt;text-align: justify;">
		Pada hari ini, <?php echo $hari." ".$tgl_indo; ?> telah diterima dari PT. Sinar Sentosa Primatama, 1 lembar STNK dengan rincian sebagai berikut :
	</p>
	
	<table class="table table-bordered" style="font-size: 11pt;">
		<tr style="height: 55px">
			<td style="text-align: center;height: 25px"><b>Nama Konsumen</b></td>
			<td style="text-align: center;height: 25px"><b>No Mesin</b></td>
			<td style="text-align: center;height: 25px"><b>Tipe</b></td>
			<td style="text-align: center;height: 25px"><b>Warna</b></td>			
			<td style="text-align: center;height: 25px"><b>No STNK</b></td>
		</tr>
		<tr>
			<?php 
			$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->row()->tipe_ahm;
			$warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$row->id_warna'")->row()->warna;
			$no_bpkb = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin ='$row->no_mesin'")->row()->no_stnk;
			 ?>
			<td style="font-size: 10pt"><?php echo $row->pemohon ?></td>
			<td style="font-size: 10pt"><?php echo $row->no_mesin ?></td>
			<td style="font-size: 10pt"><?php echo $tipe ?></td>
			<td style="font-size: 10pt"><?php echo $warna ?></td>			
			<td style="font-size: 10pt"><?php echo $no_stnk ?></td>
		</tr>
	</table><br><br>
	<table class="table">
		<tr>
			<td style="text-align: center;">Disetujui Oleh,<br><br><br><br><br><br></td>
			<td style="text-align: center;vertical-align: top;">Diserahkan Oleh,</td>
			<td style="text-align: center;vertical-align: top;">Diterima Oleh,</td>
		</tr>
		<tr>
			<td style="text-align: center;"><pre>(              )</pre></td>
			<td style="text-align: center;"><pre>(              )</pre></td>
			<td style="text-align: center;"><pre>(              )</pre></td>
		</tr>
	</table>

</div>

<?php }elseif($cetak=='st_plat'){ ?>

<div>
<br><br>
	<table class="table table-bordered">
		<tr>
			<td align="center" style="font-size: 12pt"><b>TANDA TERIMA</b></td>
		</tr>
	</table><br>
	<table width="80%">
		<tr>
			<td width="30%">No Tanda Terima</td>
			<td>: <?php echo $no_tanda_terima ?></td>
		</tr>
		<tr>
			<td width="30%">Tgl Tanda Terima</td>
			<td>: <?php echo tgl_indo($tgl_terima) ?></td>
		</tr>
	</table>
	<p style="font-size: 11pt;text-align: justify;">
		Pada hari ini, <?php echo $hari." ".$tgl_indo; ?> telah diterima dari PT. Sinar Sentosa Primatama, 1 Plat dengan rincian sebagai berikut :
	</p>
	
	<table class="table table-bordered" style="font-size: 11pt;">
		<tr style="height: 55px">
			<td style="text-align: center;height: 25px"><b>Nama Konsumen</b></td>
			<td style="text-align: center;height: 25px"><b>No Mesin</b></td>
			<td style="text-align: center;height: 25px"><b>Tipe</b></td>
			<td style="text-align: center;height: 25px"><b>Warna</b></td>			
			<td style="text-align: center;height: 25px"><b>No Plat</b></td>
		</tr>
		<tr>
			<?php 
			$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->row()->tipe_ahm;
			$warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$row->id_warna'")->row()->warna;
			$no_bpkb = $this->db->query("SELECT * FROM tr_entry_stnk WHERE no_mesin ='$row->no_mesin'")->row()->no_plat;
			 ?>
			<td style="font-size: 10pt"><?php echo $row->pemohon ?></td>
			<td style="font-size: 10pt"><?php echo $row->no_mesin ?></td>
			<td style="font-size: 10pt"><?php echo $tipe ?></td>
			<td style="font-size: 10pt"><?php echo $warna ?></td>			
			<td style="font-size: 10pt"><?php echo $no_plat ?></td>
		</tr>
	</table><br><br>
	<table class="table">
		<tr>
			<td style="text-align: center;">Disetujui Oleh,<br><br><br><br><br><br></td>
			<td style="text-align: center;vertical-align: top;">Diserahkan Oleh,</td>
			<td style="text-align: center;vertical-align: top;">Diterima Oleh,</td>
		</tr>
		<tr>
			<td style="text-align: center;"><pre>(              )</pre></td>
			<td style="text-align: center;"><pre>(              )</pre></td>
			<td style="text-align: center;"><pre>(              )</pre></td>
		</tr>
	</table>

</div>

<?php }elseif($cetak=='syarat_bpkb'){ ?>
	<br><br>
	<table class="table table-bordered">
		<tr>
			<td align="center" style="font-size: 12pt"><b>TANDA TERIMA</b></td>
		</tr>
	</table><br>
<?php }elseif($cetak=='syarat_stnk'){ ?>
	<br><br>
	<table class="table table-bordered">
		<tr>
			<td align="center" style="font-size: 12pt"><b>TANDA TERIMA</b></td>
		</tr>
	</table><br>
<?php }elseif($cetak=='syarat_tagihan'){ ?>
	<br>
	Jambi, <br>
	Nomor&nbsp;&nbsp;&nbsp;&nbsp; : 
	<p style="font-weight: bold;font-size: 11pt;">
		Kepada Yth, <br>
		PT. ASTRA INTERNATIONAL TBK-HONDA <br>
		JL. MT Haryono Rt. 100 No. 101-103 <br> 
		Kel. Gunung Bahagia Kec. BPP Selatan <br>
		Kab. Balikpapan 76114 <br>
		Up. Ibu Jumranah

	</p>
	<p style="margin-left: 40px;">
		Perihal&nbsp;&nbsp;&nbsp;&nbsp;: Tagihan Bantuan Proses Pengurusan STNK/BPKB
	</p>
	<p style="text-align: justify;">
		Dengan hormat,<br>
Sehubungan dengan permintaan pengurusan STNK/BPKB melalui Perusahaan kami, maka bersama surat ini kami kirimkan tagihan biaya pengurusan STNK/BPKB dengan rincian sebagai berikut :
	</p>
	<ul type="circle">
		<li>Biaya STNK/BPKB a.n</li>
			<table class="table table-bordered" style="width: 80%">
				<tr>
					<td style="text-align: center;"><b>Nama</b></td>
					<td style="text-align: center;"><b>Type</b></td>
					<td style="text-align: center;"><b>Unit</b></td>
					<td style="text-align: center;"><b>Harga</b></td>
				</tr>
				 <tr>
					<?php 
					$tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$row->id_tipe_kendaraan'")->row()->tipe_ahm;
					$warna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$row->id_warna'")->row()->warna;
					 ?>
					<td style="font-size: 10pt"><?=$row->pemohon?></td>
					<td style="font-size: 10pt"><?=$tipe?></td>
					<td style="font-size: 10pt">1</td>
					<td align='right' style="font-size: 10pt;"><?=mata_uang($row->biaya_bbn)?></td>
				</tr>
				<tr>
					<td colspan="3" style="text-align: center;">Total</td>
					<td align='right'><?=mata_uang($row->biaya_bbn)?></td>
				</tr>
			</table>
			<br>
			<?php $tot = $row->biaya_adm*1 ?>
		<li>
			<span style="padding-left: 90px">Biaya Jasa Pengurusan STNK/BPKB : 1 unit x Rp. <?=mata_uang($row->biaya_adm)?> </span>
			<span>: Rp. <?=mata_uang($tot)?></span><br>
			<p style="margin-left: 370px">: Rp. <?=mata_uang($row->total)?></p>
			<p>Terbilang : <?php echo ucwords(number_to_words($row->total)) ?> Rupiah</p>
		</li>
			<p>
				Bersama surat ini kami lampirkan pula :
			</p>
			<ol>
				<li>kwitansi asli bermaterai Rp. 6.000</li>
				<li>1 (Satu) lembar Fotokopi STNK</li>
				<li>1 (Satu) lembar Fotokopi Surat Bantuan Proses STNK/BPKB</li>
			</ol>
	</ul>
	<p>Besar harapan kami tagihan tersebut dapat segera diproses dan ditransfer ke rekening :</p>
	<table class="table" style="width: 80%;font-weight: bold;margin-left: 30px;">
		<tr>
			<td width="30%">Atas nama</td> <td>: PT. SINAR SENTOSA PRIMATAMA</td>
		</tr>
		<tr>
			<td>Bank</td><td>: BCA</td>
		</tr>
		<tr>
			<td>No. Rekening</td><td>: 7870900800</td>
		</tr>
	</table>
	<p style="text-align: justify;">Demikian surat ini kami sampaikan. Atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.</p><br>
	<p>Hormat kami,</p>
<?php } ?>
</body>
</html>
