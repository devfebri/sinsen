<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
<?php
function mata_uang($a)
{
	if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
}
function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
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
				font-size: 9pt;
			}
		}
	</style>
</head>

<body>
	<?php
	$cek_no_invoice = $this->db->query("SELECT no_invoice FROM tr_sales_order_gc WHERE no_invoice = '$no_invoice'");
	if ($cek_no_invoice->num_rows() == 0 || $cek_no_invoice	 != '') {
		$cek_invoice_so = $this->db->query("SELECT * FROM tr_sales_order_gc WHERE id_sales_order_gc = '$id'");
		if ($cek_invoice_so->num_rows() == 1) {
			$cek = $cek_invoice_so->row();
			if ($cek->cetak_invoice_ke == 0 and $cek->tgl_cetak_invoice2 != '0000-00-00 00:00:00') {
				$data['status_cetak']	= 'cetak_invoice';
				$data['status_so']	= 'so_invoice';
				$data['no_invoice']	= $no_invoice;
				$data['updated_at']		= $waktu;
				$data['updated_by']		= $login_id;
				$data['tgl_cetak_invoice2']		= $waktu;
				$data['cetak_invoice_by']		= $login_id;
				$data['cetak_invoice_ke']		= 1;
			} elseif ($cek->cetak_invoice_ke >= 0 and $cek->tgl_cetak_invoice2 == '0000-00-00 00:00:00') {
				$cetak_invoice_ke = $cek->cetak_invoice_ke;
				$data['cetak_invoice_ke']		= $cetak_invoice_ke + 1;
				$data['tgl_cetak_invoice2']		= $cek->generate_date;
				if ($cek->no_invoice	== "") {
					$data['no_invoice'] = $no_invoice;
				}
				if ($cek->status_cetak	== "cetak_so") {
					$data['status_cetak']	= 'cetak_invoice';
					$data['status_so']	= 'so_invoice';
				}
			} else {
				$cetak_invoice_ke = $cek->cetak_invoice_ke;
				$data['cetak_invoice_ke']		= $cetak_invoice_ke + 1;
				if ($cek->no_invoice	== "") {
					$data['no_invoice'] = $no_invoice;
				}
			}
			$this->m_admin->update("tr_sales_order_gc", $data, "id_sales_order_gc", $id);
		}
		$so = $this->db->query("SELECT tr_sales_order_gc.*,ms_dealer.nama_dealer,ms_dealer.alamat as alamat_dealer,ms_dealer.no_telp,ms_dealer.id_kelurahan as kelurahan_dealer, tr_spk_gc.*,ms_finance_company.finance_company FROM tr_sales_order_gc 						
			left join tr_spk_gc on tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc						
			left join ms_dealer on tr_sales_order_gc.id_dealer = ms_dealer.id_dealer
			left join ms_finance_company on tr_spk_gc.id_finance_company = ms_finance_company.id_finance_company 
			WHERE id_sales_order_gc = '$id' ")->row();
		$dt_kel				= $this->db->query("SELECT * FROM ms_kelurahan INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
		INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
		INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
		WHERE ms_kelurahan.id_kelurahan = '$so->id_kelurahan'")->row();
		$kelurahan 		= $dt_kel->kelurahan;
		$id_kecamatan = $dt_kel->id_kecamatan;
		$kecamatan 		= $dt_kel->kecamatan;
		$id_kabupaten = $dt_kel->id_kabupaten;
		$kabupaten  	= $dt_kel->kabupaten;
		$id_provinsi  = $dt_kel->id_provinsi;
		$provinsi  		= $dt_kel->provinsi;

		$dt_kel_dealer				= $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$so->kelurahan_dealer'")->row();
		$kelurahan_dealer 		= $dt_kel_dealer->kelurahan;
		$id_kecamatan_dealer = $dt_kel_dealer->id_kecamatan;
		$dt_kec_dealer				= $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan_dealer'")->row();
		$kecamatan_dealer 		= $dt_kec_dealer->kecamatan;
		$id_kabupaten_dealer = $dt_kec_dealer->id_kabupaten;
		$dt_kab_dealer				= $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten_dealer'")->row();
		$kabupaten_dealer  	= $dt_kab_dealer->kabupaten;
	?>

		<table width="100%" border="0">
			<tr>
				<td colspan="4"><?php echo $so->nama_dealer	 ?></td>
			</tr>
			<tr>
				<td colspan="4"><?php echo $so->alamat_dealer	 ?></td>
			</tr>
			<tr>
				<td colspan="4"><?php echo $kabupaten_dealer		 ?></td>
			</tr>
			<tr>
				<td colspan="4"><?php echo $so->no_telp	 ?></td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<h2>INVOICE</h2>
				</td>
			</tr>
			<?php
			if ($so->jenis_beli == 'Cash') {
			?>
				<tr>
					<td width="1%">Nomor Invoice</td>
					<td width="20%">: <?php echo $so->no_invoice ?></td>
					<td width="5%">Customer</td>
					<td width="20%">: <?php echo $so->nama_npwp ?></td>
				</tr>
				<tr>
					<td valign="top" width="1%">Tgl Invoice</td>
					<td valign="top" width="20%">: <?php echo $tgl_cetak_invoice = date('d-m-Y', strtotime($so->tgl_cetak_invoice)); ?></td>
					<td valign="top" width="5%">Alamat Pembeli</td>
					<td valign="top" width="20%">:
						<?php echo "$so->alamat"; ?>
					</td>
				</tr>
				<tr>
					<td width="1%">Nomor SPK</td>
					<td width="20%">: <?php echo $so->no_spk_gc ?></td>
					<td valign="top" width="5%"></td>
					<td valign="top" width="20%">&nbsp;
						<?php echo "$kelurahan"; ?>
					</td>
				</tr>
				<tr>
					<td width="1%">Nomor SO</td>
					<td width="20%">: <?php echo $so->id_sales_order_gc ?></td>
					<td valign="top" width="5%"></td>
					<td valign="top" width="20%">&nbsp;
						<?php echo "$kecamatan"; ?>
					</td>
				</tr>
				<tr>
					<td width="1%"></td>
					<td width="20%"></td>
					<td valign="top" width="5%"></td>
					<td valign="top" width="20%">&nbsp;
						<?php echo "$kelurahan"; ?>
					</td>
				</tr>
		</table>

		<table border="0" width="100%">
			<tr>
				<td colspan="7">
					<hr>
				</td>
			</tr>
			<tr>
				<td align="center" width="5%">No.</td>
				<td align="center">KODE</td>
				<td align="center">KETERANGAN</td>
				<td align="center">HARGA</td>
				<td align="center">DISCOUNT</td>
				<td align="center">QTY</td>
				<td align="center">NILAI</td>
			</tr>
			<tr>
				<td colspan="7">
					<hr>
				</td>
			</tr>
			<?php
				$get_nosin 	= $this->db->query("SELECT * FROM tr_spk_gc_detail INNER JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				  INNER JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna 
				  WHERE no_spk_gc = '$so->no_spk_gc'");
				$i = 1;
				$dpp = 0;
				$bbn_tot = 0;
				$nom = 0;
				foreach ($get_nosin->result() as $r) {
					$no_spk = $r->no_spk_gc;
					$detail = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
							LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
							LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
							WHERE no_spk_gc='$no_spk' AND ms_tipe_kendaraan.id_tipe_kendaraan = '$r->id_tipe_kendaraan'")->row();
					$cari_bbn = $this->m_admin->getByID("tr_spk_gc_detail", "no_spk_gc", $no_spk);
					$bbn = ($cari_bbn->num_rows() > 0) ? $cari_bbn->row()->biaya_bbn : "";

					$total_harga = $detail->total_unit;
					$harg = $r->harga;
					$bbn = $r->biaya_bbn;
					$harga_off = $total_harga - $bbn - $r->voucher_tambahan - $r->nilai_voucher;
					$harga_asli = $harga_off / getPPN(1.1,$so->tgl_cetak_invoice);
					$ppn = $harga_asli * getPPN(0.1,$so->tgl_cetak_invoice);

					$ppn_a = $r->harga / getPPN(1.1,$so->tgl_cetak_invoice);
					$ppn_ab = $ppn_a * getPPN(0.1,$so->tgl_cetak_invoice);
					$ppn_abc = $ppn_a - $ppn_ab;

					$harga_on = ($harga_off * $harga->qty) + ($bbn * $harga->qty);

					$voucher = round(($r->nilai_voucher + $r->voucher_tambahan) / getPPN(1.1,$so->tgl_cetak_invoice));
					$harga   = round($r->harga / getPPN(1.1,$so->tgl_cetak_invoice));
					$total   = round(($harga - $voucher) * $r->qty);
					$har = $harga_asli;
					echo "
					<tr>
						<td align='center'>$i</td>
						<td align='center'>$r->id_tipe_kendaraan - $r->id_warna</td>
						<td align='center'>" . strip_tags($r->deskripsi_ahm) . "</td>
						<td align='right'>Rp. " . mata_uang($harga) . "</td>
						<td align='right'>Rp. " . mata_uang($voucher) . "</td>
						<td align='center'>$r->qty</td>
						<td align='right'>Rp. " . mata_uang($tot = $harga_asli * $r->qty) . "</td>						
					</tr>
					";
					$i++;
					$dpp += $tot;
					$bbn_tot += ($bbn * $r->qty);
					$nom += $r->qty;
				}
			?>
			<tr>
				<td colspan="7">
					<hr>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Dasar Pengenaan Pajak (DPP)</td>
				<?php
				$nilai_round = round($dpp);
				?>
				<td align='right'>Rp.<?php echo mata_uang($nilai_round) ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Pajak Pertambahan Nilai (PPN)</td>
				<?php
				$ppn = round($dpp * getPPN(0.1,$so->tgl_cetak_invoice));
				?>
				<td align='right'>Rp.<?php echo mata_uang($ppn) ?></td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td>
					<hr>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4"></td>
				<?php
				$jml = $dpp + $ppn;
				?>
				<td align='right'>Rp.<?php echo mata_uang($jml) ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Biaya Balik Nama</td>
				<?php
				$nilai_bbn = $bbn_tot;
				?>
				<td align='right'>Rp.<?php echo mata_uang($nilai_bbn) ?></td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td>
					<hr>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Total</td>
				<?php
				$tot = round($dpp + $ppn + $nilai_bbn);
				?>
				<td align='right'>Rp.<?php echo mata_uang($tot) ?></td>
			</tr>
			<tr>
				<td colspan="3">Terbilang:</td>
			</tr>
			<tr>
				<td colspan="7">
					<?php
					$tot_terbilang = ucwords(terbilang(round($tot)));
					$tot_terbilang = str_replace('  ', ' ', $tot_terbilang);
					echo "&nbsp;&nbsp; $tot_terbilang Rupiah";
					?>

				</td>
			</tr>
		</table>

	<?php 	} else { ?>

		<tr>
			<td width="1%">Nomor Invoice</td>
			<td width="20%">: <?php echo $so->no_invoice ?></td>
			<td width="5%">Customer</td>
			<td width="20%">: <?php echo $so->nama_npwp ?></td>
		</tr>
		<tr>
			<td valign="top" width="1%">Tgl Invoice</td>
			<td valign="top" width="20%">: <?php echo $tgl_cetak_invoice = date('d-m-Y', strtotime($so->tgl_cetak_invoice2)); ?></td>
			<td valign="top" width="5%">Alamat Pembeli</td>
			<td valign="top" width="20%">:
				<?php echo "$so->alamat"; ?>
			</td>
		</tr>
		<tr>
			<td width="1%">Nomor SPK</td>
			<td width="20%">: <?php echo $so->no_spk_gc ?></td>
			<td valign="top" width="5%"></td>
			<td valign="top" width="20%">&nbsp;
				<?php echo "$kelurahan"; ?>
			</td>
		</tr>
		<tr>
			<td width="1%">Nomor SO</td>
			<td width="20%">: <?php echo $so->id_sales_order_gc ?></td>
			<td valign="top" width="5%"></td>
			<td valign="top" width="20%">&nbsp;
				<?php echo "$kecamatan"; ?>
			</td>
		</tr>
		<tr>
			<td width="1%"></td>
			<td width="20%"></td>
			<td valign="top" width="5%"></td>
			<td valign="top" width="20%">&nbsp;
				<?php echo "$kelurahan"; ?>
			</td>
		</tr>
		</table>

		<table border="0" width="100%">
			<tr>
				<td colspan="7">
					<hr>
				</td>
			</tr>
			<tr>
				<td align="center" width="5%">No.</td>
				<td align="center">KODE</td>
				<td align="center">KETERANGAN</td>
				<td align="center">HARGA</td>
				<td align="center">DISCOUNT</td>
				<td align="center">QTY</td>
				<td align="center">NILAI</td>
			</tr>
			<tr>
				<td colspan="7">
					<hr>
				</td>
			</tr>
			<?php
				$get_nosin 	= $this->db->query("SELECT * FROM tr_spk_gc_detail INNER JOIN ms_tipe_kendaraan ON tr_spk_gc_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
				  INNER JOIN ms_warna ON tr_spk_gc_detail.id_warna = ms_warna.id_warna 
				  WHERE no_spk_gc = '$so->no_spk_gc'");
				$i = 1;
				$dpp = 0;
				$bbn = 0;
				$nom = 0;
				foreach ($get_nosin->result() as $r) {
					$voucher = round($r->nilai_voucher / getPPN(1.1,$so->tgl_cetak_invoice));
					$harga   = round($r->harga / getPPN(1.1,$so->tgl_cetak_invoice));
					$total   = round(($harga - $voucher) * $r->qty);
					echo "
					<tr>
						<td align='center'>$i</td>
						<td align='center'>$r->id_tipe_kendaraan - $r->id_warna</td>
						<td align='center'>" . strip_tags($r->deskripsi_ahm) . "</td>
						<td align='right'>Rp. " . mata_uang($harga) . "</td>
						<td align='right'>Rp. " . mata_uang($voucher) . "</td>
						<td align='center'>$r->qty</td>
						<td align='right'>Rp. " . mata_uang($total) . "</td>						
					</tr>
					";
					$i++;
					$dpp += $total;
					$bbn += ($r->biaya_bbn * $r->qty);
					$nom += $r->qty;
				}
			?>
			<tr>
				<td colspan="7">
					<hr>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Dasar Pengenaan Pajak (DPP)</td>
				<?php
				$nilai_round = round($dpp);
				?>
				<td align='right'>Rp.<?php echo mata_uang($nilai_round) ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Pajak Pertambahan Nilai (PPN)</td>
				<?php
				$ppn = round($dpp * getPPN(0.1,$so->tgl_cetak_invoice));
				?>
				<td align='right'>Rp.<?php echo mata_uang($ppn) ?></td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td>
					<hr>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4"></td>
				<?php
				$jml = $dpp + $ppn;
				?>
				<td align='right'>Rp.<?php echo mata_uang($jml) ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Biaya Balik Nama</td>
				<?php
				// $nilai_bbn = $bbn*$nom;
				$nilai_bbn = $bbn;
				?>
				<td align='right'>Rp.<?php echo mata_uang($nilai_bbn) ?></td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td>
					<hr>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4">Total</td>
				<?php
				$tot = ($dpp + $ppn + $bbn);
				?>
				<td align='right'>Rp.<?php echo mata_uang($tot) ?></td>
			</tr>
			<tr>
				<td colspan="3">Terbilang:</td>
			</tr>
			<tr>
				<td colspan="7">
					<?php
					$tot_terbilang = ucwords(terbilang(round($tot)));
					$tot_terbilang = str_replace('  ', ' ', $tot_terbilang);
					echo "&nbsp;&nbsp; $tot_terbilang Rupiah";
					?>

				</td>
			</tr>
		</table>


	<?php 	} ?>
	</table>

<?php
	}
?>
</body>

</html>