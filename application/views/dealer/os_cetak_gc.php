<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang($a){
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
					border: 0px solid black;
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
if($cetak=='cetak_gc'){
?>
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
			<td colspan="2" align="center" style="font-size: 15pt"><b>ORDER SURVEY</b></td>
		</tr>
		<tr>
			<td align="center">Nomor : <?php echo $no_spk ?></td>		
			<td align="center">Tanggal : <?php echo $tanggal; ?></td>
		</tr>
	</table><br>	
	<?php 
	$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_os->id_kelurahan'")->row();
	$kelurahan 		= $dt_kel->kelurahan;
	$id_kecamatan = $dt_kel->id_kecamatan;		
	$kecamatan 		= $dt_kel->kecamatan;
	$id_kabupaten = $dt_kel->id_kabupaten;		
	$kabupaten  	= $dt_kel->kabupaten;
	$id_provinsi  = $dt_kel->id_provinsi;		
	$provinsi  		= $dt_kel->provinsi;		

	if ($dt_os->alamat_sama !='Ya') {
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_os->id_kelurahan2'")->row();			
		$kelurahan2 		= $dt_kel->kelurahan;
		$id_kecamatan 		= $dt_kel->id_kecamatan;			
		$kecamatan2 		= $dt_kel->kecamatan;
		$id_kabupaten 		= $dt_kel->id_kabupaten;			
		$kabupaten2  		= $dt_kel->kabupaten;
		$id_provinsi  		= $dt_kel->id_provinsi;			
		$provinsi  		= $dt_kel->provinsi;			
		$alamat2=$dt_os->alamat2;
	}else{
		$kelurahan2  = $kelurahan;
		$kecamatan2  = $kecamatan;
		$kabupaten2  = $kabupaten;
		$alamat2 =	$dt_os->alamat;
	}


	$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_os->id_finance_company'");
	if($finco->num_rows() > 0){
		$t = $finco->row();
		$finance_co = $t->finance_company;
	}else{
		$finance_co = "";
	}
	// $fkb = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$dt_os->no_mesin'");
	// if ($fkb->num_rows() > 0) {
	// 	$fkb = $fkb->row()->tahun_produksi;
	// }else{
	 		$fkb='';
	?>
	<table width="100%" border="0">
		<tr>
			<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KONSUMEN</b></td>
		</tr>
		<tr>
			<td width="20%">Nama Dealer</td><td width="1%">:</td>
			<td width="30%"> <?php echo $dt_os->nama_dealer ?></td><td width="2%"></td>
			<td width="20%"></td><td width="1%"></td>
			<td width="30%"></td>
		</tr>
		<tr>
			<td>Nama Sales Person</td><td>:</td>
			<td> <?php echo $dt_os->nama_lengkap ?></td><td></td>
			<td></td><td></td>
			<td></td>
		</tr>
		<tr>
			<td>Finance Company</td><td>:</td>
			<td> <?php echo $dt_os->alamat ?></td><td></td>
			<td></td><td></td>
			<td></td>
		</tr>
		<tr>
			<td>Verifier/CMO</td><td>:</td>
			<td> <?php echo "" ?></td><td></td>
			<td></td><td></td>
			<td></td>
		</tr>
		<tr>
			<td>Jam Report</td><td>:</td>
			<td></td><td></td>
			<td></td><td></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4"><b><u>Pemohon</u></b></td>
			<td colspan="4"><b><u>Penjamin</u></b></td>
		</tr>
		<tr>
			<td>Nama</td><td>:</td>
			<td> <?php echo $dt_os->nama_penanggung_jawab ?></td><td></td>
			<td>Nama</td><td>:</td>
			<td> <?php echo $dt_os->nama_penjamin ?></td><td></td>
		</tr>
		<tr>
			<td>Alamat</td><td>:</td>
			<td> <?php echo $dt_os->alamat ?></td><td></td>
			<td>Alamat</td><td>:</td>
			<td> <?php echo $dt_os->alamat_penjamin ?></td><td></td>
		</tr>
		<tr>
			<td>No KTP</td><td>:</td>
			<td> <?php echo "" ?></td><td></td>
			<td>No KTP</td><td>:</td>
			<td> <?php echo $dt_os->no_ktp ?></td><td></td>
		</tr>
		<tr>
			<td>No KK</td><td>:</td>
			<td> <?php echo "" ?></td><td></td>
			<td>No KK</td><td>:</td>
			<td> <?php echo "" ?></td><td></td>
		</tr>
		<tr>
			<td>No HP</td><td>:</td>
			<td> <?php echo $dt_os->no_telp ?></td><td></td>
			<td>No HP</td><td>:</td>
			<td> <?php echo $dt_os->no_hp ?></td><td></td>
		</tr>
		<tr>
			<td></td><td></td>
			<td></td><td></td>
			<td>Hub dg Pemohon</td><td>:</td>
			<td> <?php echo "" ?></td><td></td>
		</tr>
		<tr>
			<td colspan="7"><u>Untuk dan Atas Nama</u></td>
		</tr>
		<tr>
			<td>Nama Perusahaan</td><td>:</td>
			<td><?php echo $dt_os->nama_npwp ?></td><td></td>
			<td></td><td></td>
			<td></td><td></td>
		</tr>
		<tr>
			<td>Alamat</td><td>:</td>
			<td><?php echo $dt_os->alamat ?></td><td></td>
			<td></td><td></td>
			<td></td><td></td>
		</tr>
		<tr>
			<td>No NPWP</td><td>:</td>
			<td><?php echo $dt_os->no_npwp ?></td><td></td>
			<td></td><td></td>
			<td></td><td></td>
		</tr>
		<tr>
			<td>Tgl Berdiri Perusahaan</td><td>:</td>
			<td><?php echo $dt_os->tgl_berdiri ?></td><td></td>
			<td></td><td></td>
			<td></td><td></td>
		</tr>
		<tr>
			<td>Nama BPKB</td><td>:</td>
			<td></td><td></td>
			<td></td><td></td>
			<td></td><td></td>
		</tr>

		<tr>
			<td style='border: 1px solid black' align="center" colspan="7"><b>DATA SURVEY</b></td>
		</tr>
		<?php 
		$get_tipe 	= $this->db->query("SELECT * FROM tr_spk_gc_detail INNER JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	 		INNER JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna WHERE no_spk_gc = '$no_spk'
	 		GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
	  $i=1;$total=0;$total_harga=0;	  
	  foreach ($get_tipe->result() as $s){	  	
	  	$harga = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE tr_spk_gc_detail.no_spk_gc = '$s->no_spk_gc' AND tr_spk_gc_detail.id_tipe_kendaraan = '$s->id_tipe_kendaraan' AND tr_spk_gc_detail.id_warna = '$s->id_warna' GROUP BY id_tipe_kendaraan")->row();
	  	$program = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$s->no_spk_gc'")->row();
	  	$ta = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi = '$s->no_mesin'");
	  	$tahun_rakit = "";
	  	if($ta->num_rows() > 0){
	  		$tahun_rakit = $ta->row()->tahun_produksi;
	  	}
			?>
			<tr>
				<td width="20%">Tipe Kendaraan</td><td width="1%">:</td>
				<td width="30%"><?php echo $s->tipe_ahm ?></td><td width="2%"></td>
				<td width="20%">DP Gross</td><td width="1%">:</td>
				<td width="30%"><?php echo mata_uang(0) ?></td>
			</tr>
			<tr>
				<td width="20%">Warna</td><td width="1%">:</td>
				<td width="30%"><?php echo $s->warna ?></td><td width="2%"></td>
				<td width="20%">Program</td><td width="1%">:</td>
				<td width="30%"><?php echo $program->id_program ?></td>
			</tr>
			<tr>
				<td width="20%">Harga On The Road</td><td width="1%">:</td>
				<td width="30%"><?php echo mata_uang($harga->harga) ?></td><td width="2%"></td>
				<td width="20%">Nilai Voucher</td><td width="1%">:</td>
				<td width="30%"><?php echo ($harga->nilai_voucher) ?></td>
			</tr>			
			<tr>
				<td width="20%">Jumlah</td><td width="1%">:</td>
				<td width="30%"><?php echo $harga->qty ?> Unit</td><td width="2%"></td>
				<td width="20%">Voucher Tambahan</td><td width="1%">:</td>
				<td width="30%"><?php echo mata_uang($harga->voucher_tambahan) ?></td>
			</tr>			
			<tr>
				<td width="20%"></td><td width="1%"></td>
				<td width="30%"></td><td width="2%"></td>
				<td width="20%">DP Stor</td><td width="1%">:</td>
				<td width="30%"><?php echo mata_uang($harga->dp_stor) ?></td>
			</tr>
			<tr>
				<td width="20%"></td><td width="1%"></td>
				<td width="30%"></td><td width="2%"></td>
				<td width="20%">Tenor</td><td width="1%">:</td>
				<td width="30%"><?php echo $harga->tenor ?></td>
			</tr>
			<tr>
				<td width="20%"></td><td width="1%"></td>
				<td width="30%"></td><td width="2%"></td>
				<td width="20%">Angsuran/bulan</td><td width="1%">:</td>
				<td width="30%"><?php echo mata_uang($harga->angsuran) ?></td>
			</tr>
			<tr>
				<td width="20%">Hasil Survey</td><td width="1%">:</td>
				<td colspan="5">[Approve / Reject]</td>
			</tr>
			<tr>
				<td width="20%">Keterangan</td><td width="1%">:</td>
				<td colspan="5">_____________________________________________________________________________________</td>
			</tr>			
			<tr>
				<td width="20%"></td><td width="1%"></td>
				<td colspan="5">_____________________________________________________________________________________</td>
			</tr>			

			<?php 
			$total += $tot;
		  $total_harga += $harga->harga;
			?>			
		<?php 
		} 
		?>

		<tr>
			<td align="center" colspan="6"><br><br></td>			
			<td align="center"><br><br>Jambi, <?php echo $tanggal ?></td>			
		</tr>
		<tr>
			<td><br><br><br></td>
		</tr>		
		<tr>
			<td colspan="6" align="center"><br><br></td>			
			<td align="center"><br><br>(____________________)</td>			
		</tr>
		<tr>
			<td colspan="6" align="center"></td>			
			<td align="center">CMO</td>			
		</tr>

	</table>

<?php 
}
?>
</body>
</html>
