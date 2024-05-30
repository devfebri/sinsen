<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
<?php
if ($cetak != 'cetak_barcode_gc') {
	function mata_uang($a)
	{
		if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		return number_format($a, 0, ',', '.');
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

			.text-center {
				text-align: center;
			}

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

			body {
				font-family: "Arial";
				font-size: 11pt;
			}
		}
	</style>
</head>

<body>

	<?php
	if ($cetak == 'cetak_barcode_gc') { ?>
		<table>
			<tr>
				<?php for ($i = 1; $i <= 3; $i++) { ?>
					<td>
						<!--  -->
						<table style='border: 1px solid black;width: 63mm'>
							<tr>
								<td align="center">
									<barcode code="<?= $so->no_mesin ?>" type="C128A" size="1.0" height="1.0" />
								</td>
							</tr>
							<tr>
								<td>
									<table style="font-size: 8pt;line-height: 7pt;">
										<tr>
											<td style="vertical-align: top;">No. Mesin</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->no_mesin ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">No. Rangka</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->norangka ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Nama</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->nama_npwp ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Alamat</td>
											<td style="vertical-align: top;text-align: justify;">:</td>
											<td><?= $so->alamat ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">No. HP</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->no_hp ?></td>
										</tr>
										<tr>
											<?php
											$tgl = date('d-m-Y', strtotime($so->tgl_cetak_invoice));
											$deskripsi_ahm = strip_tags($so->deskripsi_ahm);
											?>
											<td style="vertical-align: top;">Tgl. Pembelian</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $tgl ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Tipe / No. Polisi</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $deskripsi_ahm ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				<?php } ?>
			</tr>
			<tr>
				<?php for ($i = 1; $i <= 3; $i++) { ?>
					<td>
						<!--  -->
						<table style='border: 1px solid black;width: 63mm'>
							<tr>
								<td align="center">
									<barcode code="<?= $so->no_mesin ?>" type="C128A" size="1.0" height="1.0" />
								</td>
							</tr>
							<tr>
								<td>
									<table style="font-size: 8pt;line-height: 7pt;">
										<tr>
											<td style="vertical-align: top;">No. Mesin</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->no_mesin ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">No. Rangka</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->norangka ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Nama</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->nama_npwp ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Alamat</td>
											<td style="vertical-align: top;text-align: justify;">:</td>
											<td><?= $so->alamat ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">No. HP</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->no_hp ?></td>
										</tr>
										<tr>
											<?php
											$tgl = date('d-m-Y', strtotime($so->tgl_cetak_invoice));
											$deskripsi_ahm = strip_tags($so->deskripsi_ahm);
											?>
											<td style="vertical-align: top;">Tgl. Pembelian</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $tgl ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Tipe / No. Polisi</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $deskripsi_ahm ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				<?php } ?>
			</tr>
			<tr>
				<?php for ($i = 1; $i <= 1; $i++) { ?>
					<td>
						<!--  -->
						<table style='border: 1px solid black;width: 63mm'>
							<tr>
								<td align="center">
									<barcode code="<?= $so->no_mesin ?>" type="C128A" size="1.0" height="1.0" />
								</td>
							</tr>
							<tr>
								<td>
									<table style="font-size: 8pt;line-height: 7pt;">
										<tr>
											<td style="vertical-align: top;">No. Mesin</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->no_mesin ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">No. Rangka</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->norangka ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Nama</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->nama_npwp ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Alamat</td>
											<td style="vertical-align: top;text-align: justify;">:</td>
											<td><?= $so->alamat ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">No. HP</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $so->no_hp ?></td>
										</tr>
										<tr>
											<?php
											$tgl = date('d-m-Y', strtotime($so->tgl_cetak_invoice));
											$deskripsi_ahm = strip_tags($so->deskripsi_ahm);
											?>
											<td style="vertical-align: top;">Tgl. Pembelian</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $tgl ?></td>
										</tr>
										<tr>
											<td style="vertical-align: top;">Tipe / No. Polisi</td>
											<td style="vertical-align: top;">:</td>
											<td><?= $deskripsi_ahm ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				<?php } ?>
			</tr>
		</table>
	<?php
	} elseif ($cetak == 'cetak_kwitansi') { ?>
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
				<?= isset($dealer->nama_dealer) ? $dealer->nama_dealer : '' ?><br>
				<?= isset($dealer->alamat) ? $dealer->alamat : '' ?><br>
				Telp : <?= isset($dealer->no_telp) ? $dealer->no_telp : '' ?><br>
				Fax : <?= isset($dealer->no_fax) ? $dealer->no_fax : '' ?><br>
			</p>
			<?php $t = $this->m_admin->getByID("tr_sales_order", "id_sales_order", $_GET['id'])->row(); ?>
			<table class="table">
				<tr>
					<td align="center" style="font-size: 15pt"><b>KWITANSI</b></td>
				</tr>
				<tr>
					<td align="center">No : <?php echo $t->no_kwitansi ?></td>
				</tr>
				<tr>
					<td align="center">Tanggal : <?php echo substr($t->tgl_cetak_kwitansi, 0, 10); ?></td>
				</tr>
			</table><br>
			<table class="table" style="font-size: 10pt">
				<?php
				$jenis = $this->db->query("SELECT * FROM tr_sales_order_gc_jenis_bayar WHERE id_sales_order_gc='$konsumen->id_sales_order_gc'")->row();
				$jenisDetail = $this->db->query("SELECT * from tr_sales_order_gc_jenis_bayar_detail WHERE id_jenis_bayar = $jenis->id_jenis_bayar ");
				?>
				<tr>
					<td width="30%" rowspan="2" style="vertical-align: top">Telah Terima Dari</td>
					<td width="3%" rowspan="2" style="vertical-align: top">:</td>
					<td style="border: 1px solid black"> <?= isset($konsumen->nama_npwp) ? $konsumen->nama_npwp : '&nbsp;' ?></td>
				</tr>
				<tr>
					</td>
					<td style="border: 1px solid black"> <?= isset($konsumen->alamat) ? $konsumen->alamat : '&nbsp;' ?></td>
				</tr>
				<tr>
					<td width="30%" style="vertical-align: top">Uang Sejumlah</td>
					<td width="3%" style="vertical-align: top">:</td>
					<td style="border: 1px solid black"> <?= isset($jenis->uang_dibayar) ? 'Rp. ' . mata_uang($jenis->uang_dibayar) : '&nbsp;' ?></td>
				</tr>
				<tr>
					<td width="30%" style="vertical-align: top">Terbilang</td>
					<td width="3%" style="vertical-align: top">:</td>
					<td style="border: 1px solid black"> <?= isset($jenis->uang_dibayar) ? ucwords(number_to_words($jenis->uang_dibayar)) . ' Rupiah' : '&nbsp;' ?></td>
				</tr>
				<?php
				$s = $this->m_admin->getByID("tr_spk", "no_spk", $t->no_spk)->row();
				if ($s->jenis_beli == 'Cash') {
					$harga = $s->harga_tunai;
				} else {
					$harga = $s->dp_stor;
				}
				?>
				<tr>
					<td width="30%" style="vertical-align: top">Keterangan</td>
					<td width="3%" style="vertical-align: top">:</td>
					<td style="border: 1px solid black">Total Pembayaran <?= isset($jenis->uang_dibayar) ? 'Rp. ' . mata_uang($jenis->uang_dibayar) : '&nbsp;' ?>
						<!-- , Sisa Piutang Rp. <?php echo mata_uang($harga - $jenis->uang_dibayar) ?> -->
					</td>
				</tr>
			</table>
			<br>

			<!-- <b style="font-weight: bold;padding-top: 5px">Untuk Pembayaran</b>
	<table class="table table-bordered">
		<tr>
			<td  style="font-weight: bold; text-align: center;">No</td>
			<td  style="font-weight: bold; text-align: center;">Kode Account</td>
			<td  style="font-weight: bold; text-align: center;">No Referensi</td>
			<td  style="font-weight: bold; text-align: center;">Keterangan</td>
			<td  style="font-weight: bold; text-align: center;">Nilai</td>
		</tr>
		
		<tr>
			<td>1</td>
			<td>123332</td>
			<td><?php echo $t->no_invoice ?></td>
			<td>Pembayaran 1 Unit Motor</td>
			<td align='right'><?php echo mata_uang($harga) ?></td>
		</tr>
	</table>
	<br> -->

			<b style="font-weight: bold;padding-top: 5px">Data Kendaraan</b>
			<table class="table table-bordered">
				<tr>
					<td style="font-weight: bold; text-align: center;">No Mesin</td>
					<td style="font-weight: bold; text-align: center;">No Rangka</td>
					<td style="font-weight: bold; text-align: center;">Tipe</td>
					<td style="font-weight: bold; text-align: center;">Warna</td>
					<td style="font-weight: bold; text-align: center;">Tahun Kendaraan</td>
				</tr>
				<?php
				$sql = $this->db->query("SELECT tr_sales_order_gc_nosin.*,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna 
			FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
			LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna 
			WHERE tr_sales_order_gc_nosin.id_sales_order_gc = '$konsumen->id_sales_order_gc'");
				foreach ($sql->result() as $isi) {
					$tahun_produksi = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi='$isi->no_mesin'")->row()->tahun_produksi;
					echo "
				<tr>
					<td>$isi->no_mesin</td>
					<td>$isi->no_rangka</td>
					<td>$isi->tipe_ahm</td>
					<td>$isi->warna</td>
					<td>$tahun_produksi</td>
				</tr>
			";
				}
				?>
			</table><br>
			<table class="table">
				<tr>
					<td width="78%" style="vertical-align: top">
						<?php if ($jenis->jenis_bayar == 'Transfer') { ?>
							<table class="table table-bordered">
								<tr>
									<td style="font-weight: bold; text-align: center;">Bank Penerima</td>
									<td style="font-weight: bold; text-align: center;">No Rekening</td>
									<td style="font-weight: bold; text-align: center;">Tgl Transfer</td>
									<td style="font-weight: bold; text-align: center;">Nilai</td>
								</tr>
								<?php foreach ($jenisDetail->result() as $key => $val) :
									$id_dealer = $this->m_admin->cari_dealer();
									$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
									if ($rek->num_rows() > 0) {
										$bank = $rek->row()->bank;
										$no_rek = $rek->row()->no_rek;
									} else {
										$bank = '';
										$no_rek = '';
									}
								?>
									<tr>
										<td><?= $bank ?></td>
										<td><?= $no_rek ?></td>
										<td><?= isset($val->tgl_transfer) ? $val->tgl_transfer : '' ?></td>
										<td align="right"><?= isset($val->nilai) ? mata_uang($val->nilai) : '' ?></td>
									</tr>
								<?php endforeach ?>
							</table>
						<?php } elseif ($jenis->jenis_bayar == 'Cek/Giro') { ?>
							<table class="table table-bordered">
								<tr>
									<td style="font-weight: bold; text-align: center;">Bank Konsumen</td>
									<td style="font-weight: bold; text-align: center;">No Rekening Tujuan</td>
									<td style="font-weight: bold; text-align: center;">No Cek/Giro</td>
									<td style="font-weight: bold; text-align: center;">Tgl Cek/Giro</td>
									<td style="font-weight: bold; text-align: center;">Nilai</td>
								</tr>
								<?php foreach ($jenisDetail->result() as $key => $val) :
									$id_dealer = $this->m_admin->cari_dealer();
									$rek = $this->db->query("SELECT * FROM ms_norek_dealer_detail
												INNER JOIN ms_norek_dealer on ms_norek_dealer_detail.id_norek_dealer=ms_norek_dealer.id_norek_dealer
												INNER JOIN ms_bank on ms_norek_dealer_detail.id_bank=ms_bank.id_bank
												WHERE id_dealer='$id_dealer' AND id_norek_dealer_detail='$val->no_rek_tujuan' ");
									if ($rek->num_rows() > 0) {
										$bank = $rek->row()->bank;
										$no_rek = $rek->row()->no_rek;
									} else {
										$bank = '';
										$no_rek = '';
									}
								?>
									<tr>
										<td><?= $val->bank_konsumen ?></td>
										<td><?= $no_rek ?></td>
										<td><?= isset($val->no_cek_giro) ? $val->no_cek_giro : '&nbsp;' ?></td>
										<td><?= isset($val->tgl_cek_giro) ? $val->tgl_cek_giro : '&nbsp;' ?></td>
										<td align="right"><?= isset($val->nilai) ? mata_uang($val->nilai) : '&nbsp;' ?></td>
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
				<li>Pembayaran dengan transfer/BG/Cek harus diatasnamakan <?= isset($dealer->nama_dealer) ? $dealer->nama_dealer : '' ?>.</li>
				<li>Pembayaran dengan transfer/BG/Cek dianggap sah jika telah cair dan diterima direkening <?= isset($dealer->nama_dealer) ? $dealer->nama_dealer : '' ?>.</li>
			</ol>
		</div>
	<?php
	} elseif ($cetak == 'cetak_so_gc') {
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
				<td align="center" style="font-size: 15pt"><b>SALES ORDER</b></td>
			</tr>
			<tr>
				<td align="center">Nomor : <?php echo $id_sales_order ?></td>
			</tr>
			<tr>
				<td align="center">Tanggal : <?php echo $tanggal; ?></td>
			</tr>
		</table><br>
		<?php
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_so->id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kecamatan 		= $dt_kel->kecamatan;
		$id_kabupaten = $dt_kel->id_kabupaten;
		$kabupaten  	= $dt_kel->kabupaten;
		$id_provinsi  = $dt_kel->id_provinsi;
		$provinsi  		= $dt_kel->provinsi;

		if ($dt_so->alamat_sama != 'Ya') {
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_so->id_kelurahan2'")->row();
			$kelurahan2 		= $dt_kel->kelurahan;
			$id_kecamatan 		= $dt_kel->id_kecamatan;
			$kecamatan2 		= $dt_kel->kecamatan;
			$id_kabupaten 		= $dt_kel->id_kabupaten;
			$kabupaten2  		= $dt_kel->kabupaten;
			$id_provinsi  		= $dt_kel->id_provinsi;
			$provinsi2  		= $dt_kel->provinsi;
			$alamat2 = $dt_so->alamat2;
		} else {
			$kelurahan2  = $kelurahan;
			$kecamatan2  = $kecamatan;
			$kabupaten2  = $kabupaten;
			$provinsi2 = $provinsi;
			$alamat2 = $dt_so->alamat;
		}


		$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_so->id_finance_company'");
		if ($finco->num_rows() > 0) {
			$t = $finco->row();
			$finance_co = $t->finance_company;
		} else {
			$finance_co = "";
		}

		$get_info_kredit= $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE no_spk_gc = '$dt_so->no_spk_gc'");
		if ($get_info_kredit->num_rows() > 0) {
			$t = $get_info_kredit->row();
			$dp_stor = $t->dp_stor;
			$angsuran = $t->angsuran;
			$tenor = $t->tenor;
			$voucer = $t->nilai_voucher;
		} else {
			$dp_stor= 0;
			$angsuran = 0;
			$tenor = 0;
			$voucer =0;
		}


		// $fkb = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$dt_so->no_mesin'");
		// if ($fkb->num_rows() > 0) {
		// 	$fkb = $fkb->row()->tahun_produksi;
		// }else{
		$fkb = '';
		?>
		<table class="table" width="100%" border="0">
			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KONSUMEN</b></td>
			</tr>
			<tr>
				<td width="20%">Nama Perusahaan</td>
				<td width="1%">:</td>
				<td width="25%"><?php echo $dt_so->nama_npwp ?></td>
				<td width="2%"></td>
				<td width="20%">Nama Penanggungjawab</td>
				<td width="1%">:</td>
				<td width="30%"><?php echo $dt_so->nama_penanggung_jawab ?></td>
			</tr>
			<tr>
				<td>Nomor NPWP</td>
				<td>:</td>
				<td><?php echo $dt_so->no_npwp ?></td>
				<td></td>
				<td>No KTP</td>
				<td>:</td>
				<td><?php echo $dt_so->no_ktp ?></td>
			</tr>
			<tr>
				<td>Alamat Perusahaan</td>
				<td>:</td>
				<td><?php echo $dt_so->alamat2 ?></td>
				<td></td>
				<td>No Telp/HP</td>
				<td>:</td>
				<td><?php echo $dt_so->no_hp ?></td>
			</tr>
			<tr>
				<td>Kelurahan</td>
				<td>:</td>
				<td><?php echo $kelurahan2 ?></td>
				<td></td>
				<td>Kecamatan</td>
				<td>:</td>
				<td><?php echo $kecamatan2 ?></td>
			</tr>
			<tr>
				<td>Kabupaten</td>
				<td>:</td>
				<td><?php echo $kabupaten2 ?></td>
				<td></td>
				<td>Provinsi</td>
				<td>:</td>
				<td><?php echo $provinsi2 ?></td>
			</tr>
			<tr>
				<td>Tgl Berdiri Perusahaan</td>
				<td>:</td>
				<td><?php echo $dt_so->tgl_berdiri ?></td>
				<td></td>
				<td>Nama Pengambil BPKB</td>
				<td>:</td>
				<td></td>
			</tr>

			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KENDARAAN</b></td>
			</tr>
			<?php
			$get_tipe 	= $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin	 		
	 		INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
	 		INNER JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna WHERE id_sales_order_gc = '$id_sales_order'
	 		GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna");
			$i = 1;
			$total = 0;
			$total_harga_fix = 0;

			foreach ($get_tipe->result() as $s) {
				$harga = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE tr_spk_gc_detail.no_spk_gc = '$s->no_spk_gc' AND tr_spk_gc_detail.id_tipe_kendaraan = '$s->id_tipe_kendaraan' AND tr_spk_gc_detail.id_warna = '$s->id_warna' GROUP BY id_tipe_kendaraan")->row();
				$program = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$s->no_spk_gc'")->row();
				$unit = $this->db->query("SELECT count(tr_sales_order_gc_nosin.no_mesin) as jum FROM tr_sales_order_gc_nosin 
	  		JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order_gc_nosin.no_mesin
	  		WHERE id_sales_order_gc = '$id_sales_order' AND tipe_motor='$s->id_tipe_kendaraan' AND warna='$s->id_warna'")->row();
				$ta = $this->db->query("SELECT * FROM tr_fkb WHERE no_mesin_spasi = '$s->no_mesin'");

				$tahun_rakit = "";
				if ($ta->num_rows() > 0) {
					$tahun_rakit = $ta->row()->tahun_produksi;
				}
				$dpp = floor($harga->harga / getPPN(1.1));
				$ppn = floor($dpp * getPPN(0.1));


				$no_spk = $s->no_spk_gc;
				$detail = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$no_spk' AND ms_tipe_kendaraan.id_tipe_kendaraan = '$s->id_tipe_kendaraan'")->row();
				$cari_bbn = $this->m_admin->getByID("tr_spk_gc_detail", "no_spk_gc", $no_spk);
				$bbn = ($cari_bbn->num_rows() > 0) ? $cari_bbn->row()->biaya_bbn : "";

				$total_harga = $detail->total_unit;
				$bbn = $harga->biaya_bbn;
				$diskon_voucher = $harga->nilai_voucher + $harga->voucher_tambahan;

				$harga_off = ($harga->harga - $diskon_voucher) * $harga->qty;
				$harga_on  = $harga_off + ($bbn * $harga->qty);

				// $harga_off2 = $total_harga - $bbn - $harga->voucher_tambahan - $harga->nilai_voucher;
				// $harga_on2 = ($harga_off * $harga->qty) + ($bbn * $harga->qty);

				$harga_asli = $harga_off / getPPN(1.1,false);
				$ppn = $harga_asli * getPPN(0.1,false);
				$tot_diskon_voucher = $diskon_voucher * $harga->qty;
				$harga_akhir_ontheroad = ($total_harga - $diskon_voucher) * $harga->qty;
			?>
				<tr>
					<td width="20%">Tipe Kendaraan</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo $s->tipe_ahm ?></td>
					<td width="2%"></td>
					<td width="20%">Harga</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($harga_asli) ?></td>
				</tr>
				<tr>
					<td width="20%">Warna</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo $s->warna ?></td>
					<td width="2%"></td>
					<td width="20%">PPN</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($ppn) ?></td>
				</tr>
				<tr>
					<td width="20%">Jumlah</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo $unit->jum ?> Unit</td>
					<td width="2%"></td>
					<td width="20%">Harga Off The Road</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($harga_off) ?></td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="1%"></td>
					<td width="25%"></td>
					<td width="2%"></td>
					<td width="20%">Biaya Surat</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($harga->biaya_bbn * $unit->jum) ?></td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="1%"></td>
					<td width="25%"></td>
					<td width="2%"></td>
					<td width="20%">Harga On The Road</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($harga_on) ?></td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="1%"></td>
					<td width="25%"></td>
					<td width="2%"></td>
					<td width="20%">Program</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $program->id_program ?></td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="1%"></td>
					<td width="25%"></td>
					<td width="2%"></td>
					<td width="20%">Diskon/Voucher</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($tot_diskon_voucher) ?></td>
				</tr>
				<tr>
					<td width="20%"></td>
					<td width="1%"></td>
					<td width="25%"></td>
					<td width="2%"></td>
					<td width="20%">Harga Akhir On The Road</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($harga_akhir_ontheroad) ?></td>
				</tr>
				<?php
				// $total += $harga_akhir_ontheroad;
				$total_harga_fix += $harga_akhir_ontheroad;
				?>
				<tr>
					<td colspan="7">
						<table class="table table-bordered" style="border: 1px solid black">
							<tr style="border: 1px solid black">
								<td style="border: 1px solid black" width="15%">Type</td>
								<td style="border: 1px solid black" width="10%">Warna</td>
								<td style="border: 1px solid black" width="20%">No Mesin</td>
								<td style="border: 1px solid black" width="20%">No Rangka</td>
								<td style="border: 1px solid black" width="10%">Tahun Rakitan</td>
								<td style="border: 1px solid black" width="25%">Nama Pada STNK/BPKB</td>
							</tr>
							<?php
							$get_nosin 	= $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin
					  WHERE id_sales_order_gc = '$id' AND tr_scan_barcode.tipe_motor = '$s->id_tipe_kendaraan' AND tr_scan_barcode.warna='$s->id_warna'");
							$i = 1;
							foreach ($get_nosin->result() as $r) {
								$cek_pik = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_item 
				          ON tr_scan_barcode.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
				          ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
				          ON ms_item.id_warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$r->no_mesin'");
								$cek_tahun = $this->m_admin->getByID("tr_fkb", "no_mesin_spasi", $r->no_mesin);
								$tahun = ($cek_tahun->num_rows() > 0) ? $cek_tahun->row()->tahun_produksi : "";
								$ju = $get_nosin->num_rows();

								$nama_stnk = $r->nama_stnk;
								if($r->nama_stnk==''){
									$nama_stnk = $dt_so->nama_bpkb;
								}
								
								if ($cek_pik->num_rows() > 0) {
									$cek_pik = $cek_pik->row();
									echo "
					    	<tr>";
									if ($i == 1) {
										echo "<td style='border: 1px solid black' rowspan='$ju'>$cek_pik->tipe_ahm</td>
					    		<td style='border: 1px solid black' rowspan='$ju'>$cek_pik->warna</td>";
									}
									echo "<td style='border: 1px solid black'>$r->no_mesin</td>
					    		<td style='border: 1px solid black'>$r->no_rangka</td>
					    		<td style='border: 1px solid black'>$tahun</td>
					    		<td style='border: 1px solid black'>$nama_stnk</td>
					    	</tr>
					    	";
								}
								$i++;
							}
							$total += ($harga_akhir_ontheroad * $get_nosin->num_rows());
							?>
						</table>
					</td>
				</tr>
			<?php
			}
			?>
			<br>
			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>SISTEM PEMBELIAN</b></td>
			</tr>
			<?php
			if ($dt_so->jenis_beli == 'Cash') {
			?>

				<tr>
					<td width="20%">Jenis</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo $dt_so->on_road_gc ?></td>
					<td width="2%"></td>
					<td width="20%">Jumlah Harga</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($total_harga_fix) ?></td>
				</tr>
				<tr>
					<td width="20%">Program Khusus</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo "" ?></td>
					<td width="2%"></td>
					<td width="20%">Total Bayar</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($total_harga_fix) ?></td>
				</tr>
			<?php } else {

				$kerja				= $this->db->query("SELECT * FROM ms_pekerjaan WHERE id_pekerjaan = '$dt_so->id_pekerjaan'");
				if ($kerja->num_rows() > 0) {
					$tr = $kerja->row();
					$pekerjaan = $tr->pekerjaan;
				} else {
					$pekerjaan = "-";
				}
			?>
				<tr>
					<td width="20%">Leasing/Finco</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo $finance_co ?></td>
					<td width="2%"></td>
					<?php /*
					<td width="20%">Pekerjaan</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $pekerjaan ?></td>
					*/?>
				</tr>
				<tr>
					<td width="20%">Uang Muka/DP</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo mata_uang($dp_stor) ?></td>
					<td width="2%"></td>
					<td width="20%">Angsuran</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo mata_uang($angsuran) ?></td>
					<td width="2%"></td>

					<?php /*
					<td width="20%">Jabatan</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo "-" ?></td>
					*/?>
				</tr>
				<tr>
					<td width="20%">Voucher Tambahan</td>
					<td width="1%">:</td>
					<td width="25%"><?php echo mata_uang($voucer) ?></td>
					<td width="2%"></td>
					<td width="20%">Tenor</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $tenor ?></td>
					<td width="2%"></td>

					<?php /*
					<td width="20%">Status Rumah</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo "-" ?></td>
					*/?>

				</tr>
				<tr>
					<?php /*
					<td width="20%">Lama Kerja</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo "-" ?></td>
					*/?>

				</tr>
				<tr>
					<?php /*
					<td width="20%">Total Penghasilan</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo "-" ?></td>
					<td width="2%"></td>
					*/?>
				</tr>
			<?php } ?>
			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>SYARAT DAN KETENTUAN</b></td>
			</tr>
			<tr>
				<td colspan="7">1. Harga yang tercantum dalam Sales Order <b>telah mengikat</b></td>
			</tr>
			<tr>
				<td colspan="7">2. Surat Pesanan ini dianggap SAH apabila ditandatangani oleh Pemesan, Sales Person, dan Kepala Cabang.</td>
			</tr>
			<tr>
				<td colspan="7">3. Pembayaran dengan Cek/ Bilyet Giro/ Transfer dianggap sah apabila telah diterima di rekening:</td>
			</tr>
			<tr>
				<?php
				$norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer WHERE id_dealer = '$dt_so->id_dealer' ")->row()->id_norek_dealer;
				$detail_norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer_detail WHERE id_norek_dealer = '$norek_dealer' LIMIT 0,2");
				foreach ($detail_norek_dealer->result() as $isi) {
					$bank = $this->db->query("SELECT * FROM ms_bank WHERE id_bank = '$isi->id_bank'")->row();
					echo "
				<td align='right'>
					Atas Nama <br>
					Nama Bank <br>
					No Rekening
				</td><td></td>
				<td>
					: <b>$isi->nama_rek</b> <br>
					: $bank->bank <br>
					: $isi->no_rek
				</td>
				<td></td>				
			";
				}
				?>
			</tr>
			<tr>
				<td colspan="7">4. Pembayaran Tunai dianggap <b>sah</b> apabila telah diterbitkan kwitansi oleh <b> <?php echo $dt_so->nama_dealer ?>.</b></td>
			</tr>
			<tr>
				<td colspan="7">5. Pengurusan STNK & BPKB dilaksanakan setelah 100% harga kendaraan lunas.</td>
			</tr>
			<tr>
				<td colspan="7">6. Nama pada Faktur STNK (BPKB) yang tercantum dalam Sales Order ini <b>tidak dapat diubah.</b></td>
			</tr>
			<tr>
				<td colspan="7">7. Sepeda motor yang sudah dibeli <b>tidak dapat dikembalikan</b> atau <b>ditukar.</b> </td>
			</tr>

			<tr>
				<td align="center"><br><br>PEMESAN,</td>
				<td colspan="2" align="center"><br><br>SALES PERSON,</td>
				<td colspan="2" align="center"><br><br>KEPALA CABANG,</td>
				<td colspan="2" align="center"><br><br>PENGAMBIL BPKB,</td>
			</tr>
			<tr>
				<td><br><br><br></td>
			</tr>

			<tr>
				<td align="center"><br><br>(<?php echo $dt_so->nama_penanggung_jawab ?>)</td>
				<td colspan="2" align="center"><br><br>(<?php echo $dt_so->nama_lengkap ?>)</td>
				<td colspan="2" align="center"><br><br>(<?php echo $dt_so->pic ?>)</td>
				<td colspan="2" align="center"><br><br>(_________________)</td>
			</tr>

		</table>



	<?php
	}
	?>
</body>

</html>