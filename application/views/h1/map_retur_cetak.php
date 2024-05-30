<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang($a){
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
	<div>
	<p style="font-size: 10pt">
	   Jambi, <?php echo tgl_indo(date("Y-m-d")) ?><br>
	   Kepada Yth :<br>
	   Tebing <br>
	   Fax : <?=isset($dealer->no_fax)?$dealer->no_fax:''?><br>
	</p>
	<?php $t = $this->m_admin->getByID("tr_sales_order","id_sales_order",$_GET['id'])->row(); ?>
	<table class="table">
		<tr>
			<td align="center" style="font-size: 15pt"><b>KWITANSI</b></td>
		</tr>
		<tr>
			<td align="center">No : <?php echo $t->no_kwitansi ?></td>
		</tr>
		<tr>
			<td align="center">Tanggal : <?php echo substr($t->tgl_cetak_kwitansi,0,10); ?></td>
		</tr>
	</table><br>
	<table class="table" style="font-size: 10pt">
		<?php         
		$jenis=$this->db->query("SELECT * FROM tr_sales_order_jenis_bayar WHERE id_sales_order='$konsumen->id_sales_order'")->row();
		$jenisDetail = $this->db->query("SELECT * from tr_sales_order_jenis_bayar_detail WHERE id_jenis_bayar = $jenis->id_jenis_bayar ");  
		?>
		<tr>
			<td width="30%" rowspan="2" style="vertical-align: top">Telah Terima Dari</td>
			<td width="3%" rowspan="2" style="vertical-align: top">:</td><td style="border: 1px solid black"> <?=isset($konsumen->nama_konsumen)?$konsumen->nama_konsumen:'&nbsp;' ?></td>
		</tr>
		<tr>
			</td><td style="border: 1px solid black"> <?=isset($konsumen->alamat)?$konsumen->alamat:'&nbsp;' ?></td>
		</tr>
		<tr>
			<td width="30%" style="vertical-align: top">Uang Sejumlah</td>
			<td width="3%" style="vertical-align: top">:</td><td align='right' style="border: 1px solid black"> <?=isset($jenis->uang_dibayar)?'Rp. '.mata_uang($jenis->uang_dibayar):'&nbsp;' ?></td>
		</tr>
		<tr>
			<td width="30%" style="vertical-align: top">Terbilang</td>
			<td width="3%" style="vertical-align: top">:</td><td style="border: 1px solid black"> <?=isset($jenis->uang_dibayar)?ucwords(number_to_words($jenis->uang_dibayar)).' Rupiah':'&nbsp;' ?></td>
		</tr>
		<tr>
			<td width="30%" style="vertical-align: top">Keterangan</td>
			<td width="3%" style="vertical-align: top">:</td>
			<td style="border: 1px solid black"></td>
		</tr>
	</table>
	<br>
	<b style="font-weight: bold;padding-top: 5px">Untuk Pembayaran</b>
	<table class="table table-bordered">
		<tr>
			<td  style="font-weight: bold; text-align: center;">No</td>
			<td  style="font-weight: bold; text-align: center;">Kode Account</td>
			<td  style="font-weight: bold; text-align: center;">No Referensi</td>
			<td  style="font-weight: bold; text-align: center;">Keterangan</td>
			<td  style="font-weight: bold; text-align: center;">Nilai</td>
		</tr>
		<?php         
		$s = $this->m_admin->getByID("tr_spk","no_spk",$t->no_spk)->row();
		if($s->jenis_beli == 'Cash'){
			$harga = $s->harga_tunai;
		}else{
			$harga = $s->dp_stor;
		}
		?>
		<tr>
			<td>1</td>
			<td>123332</td>
			<td><?php echo $t->no_invoice ?></td>
			<td>Pembayaran 1 Unit Motor</td>
			<td align='right'><?php echo mata_uang($harga) ?></td>
		</tr>
	</table>
	<br>
	<b style="font-weight: bold;padding-top: 5px">Data Kendaraan</b>
	<table class="table table-bordered">
		<tr>
			<td  style="font-weight: bold; text-align: center;">No Mesin</td>
			<td  style="font-weight: bold; text-align: center;">No Rangka</td>
			<td  style="font-weight: bold; text-align: center;">Tipe</td>
			<td  style="font-weight: bold; text-align: center;">Warna</td>
			<td  style="font-weight: bold; text-align: center;">Tahun Kendaraan</td>
		</tr>
		<tr>
			<td><?= isset($konsumen->no_mesin)?$konsumen->no_mesin:'&nbsp;' ?></td>
			<td><?= isset($konsumen->no_rangka)?$konsumen->no_rangka:'&nbsp;' ?></td>
			<td><?= isset($konsumen->tipe_ahm)?$konsumen->tipe_ahm:'&nbsp;' ?></td>
			<td><?= isset($konsumen->warna)?$konsumen->warna:'&nbsp;' ?></td>
			<?php $tahun_produksi = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi='$konsumen->no_mesin'")->row()->tahun_produksi; ?>
			<td><?= isset($tahun_produksi)?$tahun_produksi:'&nbsp;' ?></td>
		</tr>
	</table><br>
	<table class="table">
		<tr>
			<td width="78%" style="vertical-align: top">
				<?php if ($jenis->jenis_bayar=='Transfer'){ ?>
					<table class="table table-bordered">
						<tr>
							<td style="font-weight: bold; text-align: center;">Bank Penerima</td>
							<td style="font-weight: bold; text-align: center;">No Rekening</td>
							<td style="font-weight: bold; text-align: center;">Tgl Transfer</td>
							<td style="font-weight: bold; text-align: center;">Nilai</td>
						</tr>
						<?php foreach ($jenisDetail->result() as $key => $val): 
							$id_dealer = $this->m_admin->cari_dealer();
						$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
						if ($rek->num_rows()>0) {
							$bank = $rek->row()->bank;
							$no_rek = $rek->row()->no_rek;
						}else{
							$bank='';
							$no_rek='';
						}
							?>
							<tr>
								<td><?=$bank?></td>
								<td><?=$no_rek?></td>
								<td><?= isset($val->tgl_transfer)?$val->tgl_transfer:'' ?></td>
								<td align="right"><?= isset($val->nilai)?mata_uang($val->nilai):'' ?></td>
							</tr>
						<?php endforeach ?>
					</table>
				<?php }elseif ($jenis->jenis_bayar=='Cek/Giro') {?>
					<table class="table table-bordered">
						<tr>
							<td style="font-weight: bold; text-align: center;">Bank Konsumen</td>
							<td style="font-weight: bold; text-align: center;">No Rekening Tujuan</td>
							<td style="font-weight: bold; text-align: center;">No Cek/Giro</td>
							<td style="font-weight: bold; text-align: center;">Tgl Cek/Giro</td>
							<td style="font-weight: bold; text-align: center;">Nilai</td>
						</tr>
						<?php foreach ($jenisDetail->result() as $key => $val): 
							$id_dealer = $this->m_admin->cari_dealer();
						$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
						if ($rek->num_rows()>0) {
							$bank = $rek->row()->bank;
							$no_rek = $rek->row()->no_rek;
						}else{
							$bank='';
							$no_rek='';
						}
							?>
							<tr>
								<td></td>
								<td><?=$no_rek?></td>
								<td><?= isset($val->no_cek_giro)?$val->no_cek_giro:'&nbsp;' ?></td>
								<td><?= isset($val->tgl_cek_giro)?$val->tgl_cek_giro:'&nbsp;' ?></td>
								<td align="right"><?= isset($val->nilai)?mata_uang($val->nilai):'&nbsp;' ?></td>
							</tr>
						<?php endforeach ?>
					</table>
				 <?php } ?>
			</td>
			<td></td>
			<td width="20%" align="center">
				Jambi, <?= date('d/m/Y') ?> <br><br><br><br><br>
				(___________________)
			</td>
		</tr>
	</table><br>    
		<ol style="font-size: 10pt"> 
			<li>Pembayaran dengan transfer/BG/Cek harus diatasnamakan PT.Sinar Sentosa Primatama.</li>
			<li>Pembayaran dengan transfer/BG/Cek dianggap sah jika telah cair dan diterima direkening PT.Sinar Sentosa Primatama.</li>
		</ol>
</div>
</body>
</html>
