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
								margin-left: 1.8cm;
								margin-right: 1.8cm;
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

<?php if ($cetak=='surat'){ ?>
<?php 
		// $hari = nama_hari($row->tgl_faktur);
		// $tgl_indo = tgl_indo($row->tgl_faktur);
 ?>
<div>
		<?php $row=$sql->row(); ?>
		<p style="font-size: 15pt;font-weight: bold;text-align: center;">Surat Pengantar Biro Jasa</p>
		<br>
		<p> Tanggal Mohon Samsat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo date('d-m-Y', strtotime( $tgl_samsat))?></p>
		<table class="table table-bordered">
				<tr>
						<td style="height: 26px;text-align: center;font-weight: bold; width: 7%">No</td>
						<td style="height: 26px;text-align: center;font-weight: bold; ">Nama Dealer/Nama Konsumen</td>
						<td style="height: 26px;text-align: center;font-weight: bold; width: 20%">Total</td>
				</tr>
				<?php $no=1;$tot;
						foreach ($sql->result() as $rs) { ?>
								<tr>
										<td style="text-align: center;"><?php echo $no?></td>
										<?php
											$cek = $this->db->query("SELECT count(tr_pengajuan_bbn_detail.no_mesin) as jum FROM tr_pengajuan_bbn_detail
												inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
												inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
											 	WHERE tr_pengajuan_bbn_detail.id_generate = '$id_generate' 
											 	AND ms_dealer.id_dealer = '$rs->id_dealer'")->row();
											$jum = $cek->jum;
										?>
										<td><?php echo $rs->nama_dealer?></td>																															
										<td style="text-align: center;"><?php echo $jum?></td>
								</tr>
					<?php $no++;$tot+=$jum; }


						foreach ($sql2->result() as $rs) { ?>
								<tr>
										<td style="text-align: center;"><?php echo $no?></td>																				
										<td><?php echo $rs->nama_konsumen?></td>										
										<td style="text-align: center;">1</td>
								</tr>
					<?php $no++;$tot+=$jum; }
						


				 ?>
				 <tr>
						 <td colspan="2" style="text-align: right;"><b>Total</b>&nbsp;&nbsp;</td>
						 <td style="text-align: center;"><?php echo $tot?></td>
				 </tr>
		</table>
		<br><br><br>
		<table style="width: 100%">
				<tr>
						<td style="text-align: center">Yang Menyerahkan
								<br><br><br><br><br>
								<pre>(                     )</pre>

						</td>
						<td style="text-align: center">Diterima
										<br><br><br><br><br>
								<pre>(                     )</pre>
						</td>
				</tr>
		</table>
</div>

	 <!--  <div><br><br>
				<p align="right">Jambi, <?php echo  date('d/m/Y')?></p>
				<p>No Surat Pengantar &nbsp;&nbsp;&nbsp;: </p>
				<br><br>

				<p>Dengan hormat,</p>
				<p align="justify" style="line-height: 1.8em">
						Bersama dengan surat pengantar ini, kami lampirkan dokumen map berkas untuk proses BBN dengan nomor  sebanyak <?php echo $jml?> unit. Demikian surat ini kami sampaikan, Atas kerja sama yang baik, kami ucapkan terima kasih.
				</p>
				<br> <br>
				<p>Hormat Kami,</p>
				<br>
				<br>
				<br>
				<pre>(              )</pre>
		</div> -->


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
<?php }elseif($cetak=='pembayaran_bbn_1'){ ?>
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
		<?php $row=$sql->row(); ?>
		<p style="font-size: 15pt;font-weight: bold;text-align: center;">Pembayaran BBN</p>
		<p> Tanggal Mohon Samsat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo  date('d-m-Y', strtotime( $tgl_samsat))?></p>
		<?php $grand_total=0; $jum=0; foreach ($sql_group->result() as $rs_g): ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($rs_g->nama_dealer) ?>
				<table class="table" style="font-size: 10pt;">
						<tr>
								<td style="text-align: center;width: 8%">No</td>
								<td style="text-align: center;">Nama Customer</td>
								<td style="text-align: center;">Tipe Motor</td>
								<td style="text-align: center;">Amount</td>
						</tr>
						<?php $sub_jum=0; $subtotal =0; $no=1;foreach ($sql->result() as $rs) { 
								$tipe_motor = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan'");
								$tipe_motor = $tipe_motor->num_rows()>0?$tipe_motor->row()->deskripsi_ahm:'';
								//$sub_jum = $sql->num_rows();
								?>
								<?php if ($rs->id_dealer==$rs_g->id_dealer): $sub_jum++;?>
										<tr>
												<td align="center"><?php echo $no?></td>
												<td><?php echo $rs->nama_konsumen?></td>
												<td><?php echo $tipe_motor?></td>
												<td align="right"><?php echo  mata_uang($rs->biaya_bbn_md_bj) ?> </td>
										</tr>
								<?php $subtotal+=$rs->biaya_bbn_md_bj;  $no++; endif ?>
						<?php  } ?>
						<tr>
								<td colspan="3" style="text-align: right;">Subtotal</td>
								<td align="right"><?php echo mata_uang($subtotal) ?></td>
						</tr>
				</table>
		<?php $grand_total+=$subtotal; $jum+=$sub_jum; endforeach ?>
		<br><br>
		<table class="table" style="font-size: 10pt;" border='0'>
				<tr>
						<td style="vertical-align: top">
								Grand Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo mata_uang($grand_total)?> <br>
								Grand Total Unit : <?php echo $jum ?> Unit<br><br>
								BG Bank :__________________NO _______________ <br><br>
								Tanggal :__________________Rp.________________ <br>
						</td>
						<td style="text-align: center;">
								Disetujui
								<br><br><br><br><br><br><br>
								______________
						</td>
						<td style="text-align: center;">
								Dibayar
								<br><br><br><br><br><br><br>
								______________
						</td>
						<td style="text-align: center;">
								Diterima
						<br><br><br><br><br><br><br>
								______________
						</td>
				</tr>
		</table>
<?php }elseif($cetak=='pembayaran_bbn_2'){ ?>
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
		<?php $row=$sql->row(); ?>
		<p style="font-size: 15pt;font-weight: bold;text-align: center;">Pembayaran BBN</p>
		<p> Tanggal Mohon Samsat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo  date('d-m-Y', strtotime( $tgl_samsat))?></p>						
				<table class="table" style="font-size: 10pt;" border="0">
						<tr>
								<td style="text-align: center;width: 8%">No</td>
								<td style="text-align: center;">Nama Customer</td>
								<td style="text-align: center;">Tipe Motor</td>
								<td style="text-align: center;">Amount</td>
						</tr>
						<?php $subtotal=0;$jum=0; $sub_jum=0; $no=1;foreach ($sql->result() as $rs) { 
								$tipe_motor = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan'");
								$tipe_motor = $tipe_motor->num_rows()>0?$tipe_motor->row()->deskripsi_ahm:'';
								$sub_jum++;
								?>								
										<tr>
												<td align="center"><?php echo $no?></td>
												<td><?php echo $rs->nama_konsumen?></td>
												<td><?php echo $tipe_motor?></td>
												<td align="right"><?php echo  mata_uang($rs->biaya_bbn_md_bj) ?> </td>
										</tr>							
						<?php $no++;$subtotal+=$rs->biaya_bbn_md_bj; $jum+=$sub_jum; } ?>
						<tr>
								<td colspan="3" style="text-align: right;">Subtotal</td>
								<td align="right"><?php echo mata_uang($subtotal) ?></td>
						</tr>
				</table>		
		<br><br>
		<table class="table" style="font-size: 10pt;">
				<tr>
						<td style="vertical-align: top">
								Grand Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo mata_uang($grand_total)?> <br>
								Grand Total Unit : <?php echo $jum ?> Unit<br><br>
								BG Bank :__________________NO _______________ <br><br>
								Tanggal :__________________Rp.________________ <br>
						</td>
						<td style="text-align: center;">
								Disetujui
								<br><br><br><br><br><br><br>
								______________
						</td>
						<td style="text-align: center;">
								Dibayar
								<br><br><br><br><br><br><br>
								______________
						</td>
						<td style="text-align: center;">
								Diterima
						<br><br><br><br><br><br><br>
								______________
						</td>
				</tr>
		</table>
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
										<td style="font-size: 10pt"><?php echo $row->pemohon?></td>
										<td style="font-size: 10pt"><?php echo $tipe?></td>
										<td style="font-size: 10pt">1</td>
										<td style="font-size: 10pt;"><?php echo mata_uang($row->biaya_bbn)?></td>
								</tr>
								<tr>
										<td colspan="3" style="text-align: center;">Total</td>
										<td><?php echo mata_uang($row->biaya_bbn)?></td>
								</tr>
						</table>
						<br>
						<?php $tot = $row->biaya_adm*1 ?>
				<li>
						<span style="padding-left: 90px">Biaya Jasa Pengurusan STNK/BPKB : 1 unit x Rp. <?php echo mata_uang($row->biaya_adm)?> </span>
						<span>: Rp. <?php echo mata_uang($tot)?></span><br>
						<p style="margin-left: 370px">: Rp. <?php echo mata_uang($row->total)?></p>
						<p>Terbilang : <?php echo ucwords(number_to_words($row->total)) ?> Rupiah</p>
				</li>
						<p>
								Bersama surat ini kami lampirkan pula :
						</p>
						<ol>
								<li>kwitansi asli bermaterai Rp. 6.000</li>
								<li>1 (Satu) lembar Fotokopi STNK</li>
								<li>1 (Satu) lembar Fotokopi Surat Bantuan Proses STNK/BPKB </li>
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
