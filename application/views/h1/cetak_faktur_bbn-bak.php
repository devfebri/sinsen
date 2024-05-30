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
	<title>Cetak Faktur</title>
	<style>
		@media print {
			@page {
				sheet-size: 202mm 279mm;
				margin-left: 0.8mm;
				margin-right: 0.5mm;
				margin-bottom: 0.5mm;
				margin-top: 53mm;
				/*background: url('<?= base_url('assets/ctk_faktur.jpg') ?>') no-repeat 0 0;*/
				background-image-resize: 6;
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
				border: 1px solid black;
				padding-left: 6px;
				padding-right: 6px;
			}

			body {
				font-size: 12pt;
				/*word-spacing: 30px;*/
				font-family: courier3;
				/*letter-spacing: 10px;*/
			}
		}
	</style>
</head>

<body>
	<?php
	$getKel = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan='$r->id_kelurahan'");
	$kel = $getKel->num_rows() > 0 ? $getKel->row()->kelurahan : '';
	$no_ktp = '';
	$cek_individu = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin='$r->no_mesin'");
	if ($cek_individu->num_rows() > 0) {
		$no_ktp = $r->no_ktp;
	} else {
		$cek_gc = $this->db->query("SELECT jenis_gc,no_tdp FROM tr_sales_order_gc_nosin
		JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc=tr_sales_order_gc_nosin.no_spk_gc
		WHERE no_mesin='$r->no_mesin'");
		if ($cek_gc->num_rows() > 0) {
			$cek_gc = $cek_gc->row();
			if ($cek_gc->jenis_gc == 'Instansi') {
				$no_ktp = $r->npwp;
			} else {
				$no_ktp = $r->no_tdp;
			}
			if ($no_ktp == "") {
				$cek = $this->m_admin->getByID("tr_pengajuan_bbn_detail", "no_mesin", $r->no_mesin);
				$no_ktp = ($cek->num_rows() > 0) ? $cek->row()->no_tdp : "";
			}
		}
	}
	?>
	<table border="0" width="89%" align="center" style=" word-spacing: -4px;font-weight: normal;">
		<tr>
			<td colspan="3">
				<table style="width: 100%">
					<tr>
						<td style="text-align: center;line-height: 13px;font-size: 13pt"><?php echo $dealer->nama_dealer ?></td>
					</tr>
					<tr>
						<td align="right"><?php echo date('d-m-Y', strtotime($r->tgl_mohon_samsat)) ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="line-height: 16px"><br><br><br></td>
		</tr>
		<tr>
			<td width="40%"></td>
			<td style="line-height: 13px"><?php echo $r->nama_konsumen ?></td>
			<td width="15%"></td>
		</tr>
		<!-- 	<tr>
		<td style="line-height: 2px"><br></td>
	</tr> -->
		<tr>
			<td width="40%"></td>
			<td style="height:88px;line-height: 18px;vertical-align: top"><?php echo $r->alamat ?><br>
				<?php echo strtoupper($kel) . ' - KEC.' . strtoupper($r->kecamatan) ?><br><?= strtoupper($r->kabupaten) ?>
			</td>
		</tr>
		<!-- <tr>
		<td width="40%"></td>
		<td style="height:57px;line-height: 16px;vertical-align: top"></td>
	</tr> -->
		<tr>
			<td width="40%"></td>
			<td style="line-height: 18px"><?php echo $no_ktp ?></td>
		</tr>
	</table>
</body>

</html>