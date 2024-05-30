<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
<?php
function mata_uang($a)
{
	if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
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
				font-size: 10pt;
			}
		}
	</style>
</head>

<body>

	<?php
	if ($cetak == 'cetak_spk_gc_1') {

		$id_dealer = $this->m_admin->cari_dealer();
		$ap = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer);
		$r = $ap->row();
		if ($r->logo != "") {
			$logo = $r->logo;
		} else {
			$logo = "logo_sinsen.jpg";
		}
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

			#gambar {
				width: 120px;
				position: relative;
				z-index: 2;
				padding-top: -40px;
				padding-bottom: 30px;
			}
		</style>
		<table class="table" width="30%" border="0">
			<tr>
				<td colspan="4" align="center" style="font-size: 13pt"><b>SURAT PESANAN KENDARAAN</b></td>
			</tr>
			<tr>
				<td width="20%"></td>
				<td align="center">NOMOR : <?php echo $no_spk ?></td>
				<td align="center">TANGGAL : <?php echo shortdate_indo($dt_spk->tanggal_spk_gc) ?></td>
				<td width="20%"></td>
			</tr>
		</table>
		<img id="gambar" src="assets/panel/images/<?php echo $logo ?>" height='30px'>
		<br>
		<?php
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_spk->id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kecamatan 		= $dt_kel->kecamatan;
		$id_kabupaten = $dt_kel->id_kabupaten;
		$kabupaten  	= $dt_kel->kabupaten;
		$id_provinsi  = $dt_kel->id_provinsi;
		$provinsi  		= $dt_kel->provinsi;

		if ($dt_spk->alamat_sama != 'Ya') {
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_spk->id_kelurahan2'")->row();
			$kelurahan2 		= $dt_kel->kelurahan;
			$id_kecamatan 		= $dt_kel->id_kecamatan;
			$kecamatan2 		= $dt_kel->kecamatan;
			$id_kabupaten 		= $dt_kel->id_kabupaten;
			$kabupaten2  		= $dt_kel->kabupaten;
			$id_provinsi  		= $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;
			$alamat2 = $dt_spk->alamat2;
		} else {
			$kelurahan2  = $kelurahan;
			$kecamatan2  = $kecamatan;
			$kabupaten2  = $kabupaten;
			$alamat2 =	$dt_spk->alamat;
		}


		$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_spk->id_finance_company'");
		if ($finco->num_rows() > 0) {
			$t = $finco->row();
			$finance_co = $t->finance_company;
		} else {
			$finance_co = "";
		}
		$fkb = '';
		?>
		<table width="100%" border="0">
			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KONSUMEN</b></td>
			</tr>
			<tr>
				<td width="20%"><br>Nama Perusahaan</td>
				<td width="1%"><br>:</td>
				<td width="30%" colspan="4"><br><?php echo $dt_spk->nama_npwp ?></td>
			</tr>
			<tr>
				<td>Nomor NPWP</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->no_npwp ?></td>
				<td rowspan="8" align="right" valign="bottom">
					<?php
					//$lokasi = explode(',', $dt_spk->denah_lokasi);
					$latitude = $dt_spk->latitude;
					$longitude = $dt_spk->longitude;
					if (strpos($latitude, "E") !== true) {
						$latitude	= -1.613510;
						$longitude = 103.594603;
					}
					
					$latitude	= -1.613510;
					$longitude = 103.594603;
					$url = base_url('assets/panel/images/chart_qr_md.png');

					$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
					// echo "<img src='https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77' width='150px'>"; // error google 502
					echo "<img src='$url' width='150px'>";
					?>
				</td>
			</tr>
			<tr>
				<td>Alamat Perusahaan</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->alamat ?></td>
			</tr>
			<tr>
				<td>Kelurahan</td>
				<td>:</td>
				<td colspan="4"><?php echo $kelurahan ?></td>
			</tr>
			<tr>
				<td>Kecamatan</td>
				<td>:</td>
				<td colspan="4"><?php echo $kecamatan ?></td>
			</tr>
			<tr>
				<td>Kabupaten</td>
				<td>:</td>
				<td colspan="4"><?php echo $kabupaten ?></td>
			</tr>
			<tr>
				<td>Tgl Berdiri Perusahaan</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->tgl_berdiri ?></td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Nama Penanggung Jawab</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->nama_penanggung_jawab ?></td>
			</tr>
			<tr>
				<td>No KTP</td>
				<td>:</td>
				<td colspan="2"><?php echo $dt_spk->no_ktp ?></td>
				<td colspan="3" align="right">
					<font style="font-size: 10px;"><?php echo $qr_generate; ?></font>
				</td>
			</tr>
			<tr>
				<td>No Telp/HP</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->no_hp ?></td>
			</tr>
			<tr>
				<td>Nama Pengambil BPKB</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->nama_bpkb ?></td>
			</tr>

			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KENDARAAN</b></td>
			</tr>
			<?php
			$get_tipe 	= $this->db->query("SELECT * FROM tr_spk_gc_detail INNER JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	 		INNER JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna WHERE no_spk_gc = '$no_spk'
	 		ORDER BY tr_spk_gc_detail.id ASC
	 		-- GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan
	 		");
			$i = 1;
			$total = 0;
			$total_harga = 0;
			$total_qty = 0;
			foreach ($get_tipe->result() as $s) {
				$harga = $this->db->query("SELECT * FROM tr_spk_gc_detail 
	  		WHERE tr_spk_gc_detail.no_spk_gc = '$s->no_spk_gc' 
	  		AND tr_spk_gc_detail.id_tipe_kendaraan = '$s->id_tipe_kendaraan' 
	  		AND tr_spk_gc_detail.id_warna = '$s->id_warna' GROUP BY id_tipe_kendaraan")->row();
				$program = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$s->no_spk_gc'")->row();
				$ta = $this->db->query("SELECT * FROM tr_fkb WHERE kode_tipe='$s->id_tipe_kendaraan' AND kode_warna='$s->id_warna' GROUP BY kode_tipe,kode_warna");
				$cari_item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan='$s->id_tipe_kendaraan' AND id_warna='$s->id_warna'");
				$id_item = ($cari_item->num_rows() > 0) ? $cari_item->row()->id_item : "";
				$dpp = floor(($harga->harga - $harga->biaya_bbn) / getPPN(1.1));
				$ppn = floor($dpp * getPPN(0.1));

				//carian baru
				$no_spk = $this->input->get("id_c");
				$detail = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$no_spk' AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$s->id_tipe_kendaraan'
					ORDER BY tr_spk_gc_kendaraan.id ASC")->row();

				$tahun_rakit = $detail->tahun_produksi;
				$total_harga = $detail->total_unit;
				$bbn = $harga->biaya_bbn;
				$harga_off = ($harga->harga - ($harga->nilai_voucher + $harga->voucher_tambahan)) * $harga->qty;
				$harga_on  = $harga_off + ($bbn * $harga->qty);
				$harga_asli = $harga_off / getPPN(1.1);
				$ppn = $harga_asli * getPPN(0.1);

				$harga_on3 = ($detail->total_unit + $bbn) * $harga->qty;
				$harga_off3 = $harga_on - ($harga->biaya_bbn * $harga->qty);

				$harga_on2 = ($harga_off * $harga->qty) + ($bbn * $harga->qty);
				$harga_off2 = $total_harga - $bbn - $harga->voucher_tambahan - $harga->nilai_voucher;

			?>
				<tr>
					<td width="20%"><br>Harga</td>
					<td width="1%"><br>:</td>
					<td width="30%"><br>Rp. <?php echo mata_uang($harga_asli) ?></td>
					<td width="2%"></td>
					<td width="20%"><br>Type</td>
					<td width="1%"><br>:</td>
					<td width="30%"><br><?php echo $s->tipe_ahm . "/" . $id_item ?></td>
				</tr>
				<tr>
					<td width="20%">PPN</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($ppn) ?></td>
					<td width="2%"></td>
					<td width="20%">Deskripsi AHM</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $s->deskripsi_ahm ?></td>
				</tr>
				<tr>
					<td width="20%">Harga Off The Road</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga_off) ?></td>
					<td width="2%"></td>
					<td width="20%">Warna</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $s->warna ?></td>
				</tr>
				<tr>
					<td width="20%">Biaya Surat</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga->biaya_bbn * $harga->qty) ?></td>
					<td width="2%"></td>
					<td width="20%">Tahun Rakitan</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $tahun_rakit ?></td>
				</tr>
				<tr>
					<td width="20%">Harga On The Road</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga_on) ?></td>
					<td width="2%"></td>
					<td width="20%">Jumlah</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $harga->qty ?> Unit</td>
				</tr>
				<?php
				$total += $harga_on;
				$total_qty += $harga->qty;


				?>
			<?php
			}
			?>

			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>SISTEM PEMBELIAN</b></td>
			</tr>
			<?php
			if ($dt_spk->jenis_beli == 'Cash') {
				$cari_bbn = $this->m_admin->getByID("tr_spk_gc_detail", "no_spk_gc", $no_spk);
				/*
		$detail2 = $this->db->query("SELECT SUM(tr_spk_gc_kendaraan.total_unit * tr_spk_gc_kendaraan.qty) AS pricelist FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$no_spk' ORDER BY tr_spk_gc_kendaraan.id ASC")->row();	
	*/

// 				$detail2 = $this->db->query("SELECT SUM(tr_spk_gc_detail.qty * (tr_spk_gc_detail.harga + tr_spk_gc_detail.biaya_bbn)) AS pricelist , 
// 			sum(tr_spk_gc_detail.voucher_tambahan) as voucher_tambahan
// 			FROM tr_spk_gc_detail
// 					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
// 					LEFT JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna
// 					WHERE no_spk_gc='$no_spk' ORDER BY tr_spk_gc_detail.id ASC")->row();
	        $detail2 = $this->db->query("SELECT SUM(tr_spk_gc_detail.qty * (tr_spk_gc_detail.harga + tr_spk_gc_detail.biaya_bbn)) AS pricelist , 
			tr_spk_gc_detail.voucher_tambahan as voucher_tambahan
			FROM tr_spk_gc_detail
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$no_spk' ORDER BY tr_spk_gc_detail.id ASC")->row();
			?>

				<tr>
					<td>Pembelian GC</td>
				</tr>
				<tr>
					<td>Harga Price List</td>
					<td width="1%">:</td>
					<td>Rp. <?php echo mata_uang($detail2->pricelist) ?></td>
				</tr>
				<tr>
					<td width="20%">Diskon/Voucher</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga->nilai_voucher * $total_qty) ?></td>
				</tr>
				<tr>
					<td width="20%">Voucher Tambahan</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($detail2->voucher_tambahan * $total_qty) ?></td>
				</tr>
				<tr>
					<td width="20%">Total Bayar</td>
					<td width="1%">:</td>
					<!-- <td width="30%">Rp. <?php echo mata_uang((($detail->total_unit) - $harga->voucher_tambahan - $harga->nilai_voucher) * $total_qty) ?></td>					 -->
					<td width="30%">Rp. <?php echo mata_uang($total) ?></td>
				</tr>
			<?php } ?>
			<?php
			if ($dt_spk->jenis_beli == 'Kredit') {
			?>
				<tr>
					<td width="20%">Leasing</td>
					<td width="1%">:</td>
					<td width="30%"><?= $finance_co ?></td>
				</tr>
				<tr>
					<td width="100%" colspan=7>
						<table class="table table-bordered" style='margin-bottom:20px'>
							<tr>
								<td style='border: 1px solid black'>Tipe-Warna</td>
								<td style='border: 1px solid black'>Program</td>
								<td style='border: 1px solid black' align="right">Nilai Voucher</td>
								<td style='border: 1px solid black' align="right">Voucher Tambahan</td>
								<td style='border: 1px solid black' align="right">DP Stor</td>
								<td style='border: 1px solid black' align="right">Angsuran</td>
								<td style='border: 1px solid black'>Tenor</td>
							</tr>
							<?php
							foreach ($get_tipe->result() as $s) {
							?>
								<tr>
									<td style='border: 1px solid black'><?= $s->id_tipe_kendaraan . '-' . $s->id_warna ?></td>
									<td style='border: 1px solid black'><?= $dt_spk->id_program ?></td>
									<td style='border: 1px solid black' align="right">Rp.<?= mata_uang($s->nilai_voucher) ?></td>
									<td style='border: 1px solid black' align="right">Rp.<?= mata_uang($s->voucher_tambahan) ?></td>
									<td style='border: 1px solid black' align="right">Rp.<?= mata_uang($s->dp_stor) ?></td>
									<td style='border: 1px solid black' align="right">Rp.<?= mata_uang($s->angsuran) ?></td>
									<td style='border: 1px solid black' align="center"><?= $s->tenor ?></td>
								</tr>
							<?php
							}
							?>
						</table>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td style='border: 1px solid black' align="center" colspan="7"><b>SYARAT DAN KETENTUAN</b></td>
				</tr>
				<tr>
					<td colspan="7"><br>1. Harga yang tercantum dalam surat pesanan ini tidak mengikat dan surat pesanan ini bukan merupakan bukti pembayaran</td>
				</tr>
				<tr>
					<td colspan="7">2. Surat Pesanan ini dianggap SAH apabila ditandatangani oleh Pemesan, Sales Person, dan Kepala Cabang.</td>
				</tr>
				<tr>
					<td colspan="7">3. Pembayaran dengan Cek/ Bilyet Giro/ Transfer dianggap sah apabila telah diterima di rekening:</td>
				</tr>
				<tr>
					<?php
					$norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer WHERE id_dealer = '$dt_spk->id_dealer' ")->row()->id_norek_dealer;
					$detail_norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer_detail WHERE id_norek_dealer = '$norek_dealer' LIMIT 0,2");
					foreach ($detail_norek_dealer->result() as $isi) {
						$bank = $this->db->query("SELECT * FROM ms_bank WHERE id_bank = '$isi->id_bank'")->row();
						echo "
				
				<td style='text-align:right'>
					Atas Nama <br>
					Nama Bank <br>
					No Rekening
				</td><td></td>
				<td colspan='2'>
					: <b>$isi->nama_rek</b> <br>
					: $bank->bank <br>
					: $isi->no_rek
				</td>
								
			";
					}
					?>
				</tr>
				<tr>
					<td colspan="7">4. Pembayaran Tunai dianggap <b>sah</b> apabila telah diterbitkan kwitansi oleh <b> <?php echo $dt_spk->nama_dealer ?>.</b></td>
				</tr>
				<tr>
					<td colspan="7">5. Pengiriman unit dan pengurusan surat-surat dilaksanakan setelah 100% harga kendaraan lunas.</td>
				</tr>
				<tr>
					<td colspan="7">6. Nama pada Faktur STNK (BPKB) yang tercantum dalam surat pesanan ini <b>tidak dapat diubah.</b></td>
				</tr>


				<tr>
					<td align="center" colspan="2"><br><br>PEMESAN,</td>
					<td colspan="2" align="center"><br><br>SALES PERSON,</td>
					<td colspan="2" align="center"><br><br>KEPALA CABANG,</td>
				</tr>
				<tr>
					<td><br><br><br></td>
				</tr>

				<tr>
					<td align="center"><br><br>(<?php echo $dt_spk->nama_penanggung_jawab ?>)</td>
					<td colspan="2" align="center"><br><br>(<?php echo $dt_spk->nama_lengkap ?>)</td>
					<td colspan="2" align="center"><br><br>(<?php echo $dt_spk->pic ?>)</td>
				</tr>

		</table>

	<?php
	} elseif ($cetak == 'cetak_spk_gc_2') {
		$id_dealer = $this->m_admin->cari_dealer();
		$ap = $this->m_admin->getByID("ms_dealer", "id_dealer", $id_dealer);
		$r = $ap->row();
		if ($r->logo != "") {
			$logo = $r->logo;
		} else {
			$logo = "logo_sinsen.jpg";
		}
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

			#gambar {
				width: 120px;
				position: relative;
				z-index: 2;
				padding-top: -40px;
				padding-bottom: 30px;
			}
		</style>
		<table class="table" width="30%" border="0">
			<tr>
				<td colspan="4" align="center" style="font-size: 13pt"><b>SURAT PESANAN KENDARAAN</b></td>
			</tr>
			<tr>
				<td width="20%"></td>
				<td align="center">NOMOR : <?php echo $no_spk ?></td>
				<td align="center">TANGGAL : <?php echo shortdate_indo($dt_spk->tanggal_spk_gc); ?></td>
				<td width="20%"></td>
			</tr>
		</table>
		<img id="gambar" src="assets/panel/images/<?php echo $logo ?>" height='30px'>
		<br>
		<?php
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_spk->id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kecamatan 		= $dt_kel->kecamatan;
		$id_kabupaten = $dt_kel->id_kabupaten;
		$kabupaten  	= $dt_kel->kabupaten;
		$id_provinsi  = $dt_kel->id_provinsi;
		$provinsi  		= $dt_kel->provinsi;

		if ($dt_spk->alamat_sama != 'Ya') {
			$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
  		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
  		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
  		WHERE ms_kelurahan.id_kelurahan = '$dt_spk->id_kelurahan2'")->row();
			$kelurahan2 		= $dt_kel->kelurahan;
			$id_kecamatan 		= $dt_kel->id_kecamatan;
			$kecamatan2 		= $dt_kel->kecamatan;
			$id_kabupaten 		= $dt_kel->id_kabupaten;
			$kabupaten2  		= $dt_kel->kabupaten;
			$id_provinsi  		= $dt_kel->id_provinsi;
			$provinsi  		= $dt_kel->provinsi;
			$alamat2 = $dt_spk->alamat2;
		} else {
			$kelurahan2  = $kelurahan;
			$kecamatan2  = $kecamatan;
			$kabupaten2  = $kabupaten;
			$alamat2 =	$dt_spk->alamat;
		}


		$finco				= $this->db->query("SELECT * FROM ms_finance_company WHERE id_finance_company = '$dt_spk->id_finance_company'");
		if ($finco->num_rows() > 0) {
			$t = $finco->row();
			$finance_co = $t->finance_company;
		} else {
			$finance_co = "";
		}
		// $fkb = $this->db->query("SELECT tahun_produksi from tr_fkb WHERE no_mesin_spasi='$dt_spk->no_mesin'");
		// if ($fkb->num_rows() > 0) {
		// 	$fkb = $fkb->row()->tahun_produksi;
		// }else{
		$fkb = '';
		?>
		<table width="100%" border="0">
			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KONSUMEN</b></td>
			</tr>
			<tr>
				<td width="20%"><br>Nama Perusahaan</td>
				<td width="1%"><br>:</td>
				<td width="30%" colspan="4"><br><?php echo $dt_spk->nama_npwp ?></td>
			</tr>
			<tr>
				<td>Nomor NPWP</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->no_npwp ?></td>
				<td rowspan="8" align="right" valign="bottom">
					<?php
					//$lokasi = explode(',', $dt_spk->denah_lokasi);
					$latitude = $dt_spk->latitude;
					$longitude = $dt_spk->longitude;
					$qr_generate = "maps.google.com/local?q=$latitude,$longitude";
					echo "<img src='https://chart.googleapis.com/chart?cht=qr&chl=$qr_generate&chs=77x77' width='150px'>";
					?>
				</td>
			</tr>
			<tr>
				<td>Alamat Perusahaan</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->alamat ?></td>
			</tr>
			<tr>
				<td>Kelurahan</td>
				<td>:</td>
				<td colspan="4"><?php echo $kelurahan ?></td>
			</tr>
			<tr>
				<td>Kecamatan</td>
				<td>:</td>
				<td colspan="4"><?php echo $kecamatan ?></td>
			</tr>
			<tr>
				<td>Kabupaten</td>
				<td>:</td>
				<td colspan="4"><?php echo $kabupaten ?></td>
			</tr>
			<tr>
				<td>Tgl Berdiri Perusahaan</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->tgl_berdiri ?></td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Nama Penanggung Jawab</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->nama_penanggung_jawab ?></td>
			</tr>
			<tr>
				<td>No KTP</td>
				<td>:</td>
				<td colspan="2"><?php echo $dt_spk->no_ktp ?></td>
				<td colspan="3" align="right">
					<font style="font-size: 10px;"><?php echo $qr_generate; ?></font>
				</td>
			</tr>
			<tr>
				<td>No Telp/HP</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->no_hp ?></td>
			</tr>
			<tr>
				<td>Nama Pengambil BPKB</td>
				<td>:</td>
				<td colspan="4"><?php echo $dt_spk->nama_bpkb ?></td>
			</tr>

			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>DATA KENDARAAN</b></td>
			</tr>
			<?php
			$get_tipe 	= $this->db->query("SELECT * FROM tr_spk_gc_detail INNER JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
	 		INNER JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna WHERE no_spk_gc = '$no_spk'
	 		ORDER BY tr_spk_gc_detail.id ASC
	 		-- GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan
	 		");
			$i = 1;
			$total = 0;
			$total_harga = 0;
			foreach ($get_tipe->result() as $s) {
				$harga = $this->db->query("SELECT * FROM tr_spk_gc_detail 
	  		WHERE tr_spk_gc_detail.no_spk_gc = '$s->no_spk_gc' 
	  		AND tr_spk_gc_detail.id_tipe_kendaraan = '$s->id_tipe_kendaraan' 
	  		AND tr_spk_gc_detail.id_warna = '$s->id_warna' GROUP BY id_tipe_kendaraan")->row();
				$program = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$s->no_spk_gc'")->row();
				$cari_item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan='$s->id_tipe_kendaraan' AND id_warna='$s->id_warna'");
				$id_item = ($cari_item->num_rows() > 0) ? $cari_item->row()->id_item : "";


				$ta = $this->db->query("SELECT * FROM tr_fkb WHERE kode_tipe='$s->id_tipe_kendaraan' AND kode_warna='$s->id_warna' GROUP BY kode_tipe,kode_warna");
				$dpp = floor(($harga->harga - $harga->biaya_bbn) / getPPN(1.1));
				$ppn = floor($dpp * getPPN(0.1));


				$no_spk = $this->input->get("id_c");
				$detail = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$no_spk' AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$s->id_tipe_kendaraan'
					ORDER BY tr_spk_gc_kendaraan.id ASC")->row();
				$cari_bbn = $this->m_admin->getByID("tr_spk_gc_detail", "no_spk_gc", $no_spk);
				$bbn = ($cari_bbn->num_rows() > 0) ? $cari_bbn->row()->biaya_bbn : "";
				$tahun_rakit = $detail->tahun_produksi;
				$total_harga = $detail->total_unit;
				$bbn = $harga->biaya_bbn;

				$harga_off = ($harga->harga - ($harga->nilai_voucher + $harga->voucher_tambahan)) * $harga->qty;
				$harga_on  = $harga_off + ($bbn * $harga->qty);
				$harga_asli = $harga_off / getPPN(1.1);
				$ppn = $harga_asli * getPPN(0.1);

				$harga_on3 = ($detail->total_unit + $bbn) * $harga->qty;
				$harga_off3 = $harga_on - ($harga->biaya_bbn * $harga->qty);

				$harga_on2 = ($harga_off * $harga->qty) + ($bbn * $harga->qty);
				$harga_off2 = $total_harga - $bbn - $harga->voucher_tambahan - $harga->nilai_voucher;


			?>
				<tr>
					<td width="20%"><br>Harga</td>
					<td width="1%"><br>:</td>
					<td width="30%"><br>Rp. <?php echo mata_uang($harga_asli) ?></td>
					<td width="2%"></td>
					<td width="20%"><br>Type</td>
					<td width="1%"><br>:</td>
					<td width="30%"><br><?php echo $s->tipe_ahm . "/" . $id_item ?></td>
				</tr>
				<tr>
					<td width="20%">PPN</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($ppn) ?></td>
					<td width="2%"></td>
					<td width="20%">Deskripsi AHM</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $s->deskripsi_ahm ?></td>
				</tr>
				<tr>
					<td width="20%">Harga Off The Road</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga_off) ?></td>
					<td width="2%"></td>
					<td width="20%">Warna</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $s->warna ?></td>
				</tr>
				<tr>
					<td width="20%">Biaya Surat</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga->biaya_bbn * $harga->qty) ?></td>
					<td width="2%"></td>
					<td width="20%">Tahun Rakitan</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $tahun_rakit ?></td>
				</tr>
				<tr>
					<td width="20%">Harga On The Road</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga_on) ?></td>
					<td width="2%"></td>
					<td width="20%">Jumlah</td>
					<td width="1%">:</td>
					<td width="30%"><?php echo $harga->qty ?> Unit</td>
				</tr>
				<?php
				$total += $harga_on;
				$total_qty += $harga->qty;
				?>
			<?php
			}
			?>

			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>SISTEM PEMBELIAN</b></td>
			</tr>
			<?php
			if ($dt_spk->jenis_beli == 'Cash') {
				$no_spk = $this->input->get("id_c");
				$detail2 = $this->db->query("SELECT SUM(tr_spk_gc_kendaraan.total_unit * tr_spk_gc_kendaraan.qty) AS pricelist 
					FROM tr_spk_gc_kendaraan
					LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
					LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
					WHERE no_spk_gc='$no_spk' ORDER BY tr_spk_gc_kendaraan.id ASC")->row();
			?>

				<tr>
					<td>Pembelian GC</td>
				</tr>
				<tr>
					<td>Harga Price List</td>
					<td width="1%">:</td>
					<td>Rp. <?php echo mata_uang($detail2->pricelist) ?></td>
				</tr>
				<tr>
					<td width="20%">Diskon/Voucher</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga->nilai_voucher * $total_qty) ?></td>
				</tr>
				<tr>
					<td width="20%">Voucher Tambahan</td>
					<td width="1%">:</td>
					<td width="30%">Rp. <?php echo mata_uang($harga->voucher_tambahan * $total_qty) ?></td>
				</tr>
				<tr>
					<td width="20%">Total Bayar</td>
					<td width="1%">:</td>
					<!-- <td width="30%">Rp. <?php echo mata_uang(($detail2->total_unit - $harga->voucher_tambahan - $harga->nilai_voucher) * $total_qty) ?></td>					 -->
					<td width="30%">Rp. <?php echo mata_uang($total) ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td style='border: 1px solid black' align="center" colspan="7"><b>SYARAT DAN KETENTUAN</b></td>
			</tr>
			<tr>
				<td colspan="7"><br>1. Harga yang tercantum dalam surat pesanan ini tidak mengikat dan surat pesanan ini bukan merupakan bukti pembayaran</td>
			</tr>
			<tr>
				<td colspan="7">2. Surat Pesanan ini dianggap SAH apabila ditandatangani oleh Pemesan, Sales Person, dan Kepala Cabang.</td>
			</tr>
			<tr>
				<td colspan="7">3. Pembayaran dengan Cek/ Bilyet Giro/ Transfer dianggap sah apabila telah diterima di rekening:</td>
			</tr>
			<tr>
				<?php
				$norek_dealer = $this->db->query("SELECT * FROM ms_norek_dealer WHERE id_dealer = '$dt_spk->id_dealer' ")->row()->id_norek_dealer;
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
				<td colspan="7">4. Pembayaran Tunai dianggap <b>sah</b> apabila telah diterbitkan kwitansi oleh <b> <?php echo $dt_spk->nama_dealer ?>.</b></td>
			</tr>
			<tr>
				<td colspan="7">5. Nama pada Faktur STNK (BPKB) yang tercantum dalam surat pesanan ini <b>tidak dapat diubah.</b></td>
			</tr>


			<tr>
				<td align="center" colspan="2"><br><br>PEMESAN,</td>
				<td colspan="2" align="center"><br><br>SALES PERSON,</td>
				<td colspan="2" align="center"><br><br>KEPALA CABANG,</td>
			</tr>
			<tr>
				<td><br><br><br></td>
			</tr>

			<tr>
				<td align="center"><br><br>(<?php echo $dt_spk->nama_penanggung_jawab ?>)</td>
				<td colspan="2" align="center"><br><br>(<?php echo $dt_spk->nama_lengkap ?>)</td>
				<td colspan="2" align="center"><br><br>(<?php echo $dt_spk->pic ?>)</td>
			</tr>

		</table>

	<?php
	} elseif ($cetak == 'tes') {
	?>
		<h1>Dalam Perbaikan</h1>
	<?php
	}
	?>
</body>

</html>