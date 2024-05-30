<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	<?php 
		function mata_uang($a){
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
		if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
			return number_format($a, 0, ',', '.');
		} 
		function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Cetak Kwitansi</title>
	<style>
		@media print {
			@page {
				sheet-size: 210mm 297mm;
				margin-left: 1cm;
				margin-right: 1cm;
				margin-bottom: 1cm;
				margin-top: 0.2cm;
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
	<table border="0" width="100%">
		<tr>
			<td align="center"><h2>KWITANSI</h2></td>
		</tr>
	</table>
	<br><br>
	<table border="0" align="right" width="100%">
		<tr>
			<td width="20%">No Bukti</td>
			<td width="80%">: 
				<?php echo $header->id_penerimaan_bank; ?>
			</td>
		</tr>
		<tr>
			<td width="20%">Tgl Entry</td>
			<td width="80%">: 
				<?php echo $header->tgl_entry; ?>
			</td>
		</tr>
		<tr>
			<td width="20%">Terima Dari</td>
			<td width="80%">: 
				<?php 
				if($header->tipe_customer == 'Dealer'){
					$amb = $this->m_admin->getByID("ms_dealer","id_dealer",$header->dibayar)->row()->nama_dealer;
				}elseif($header->tipe_customer == 'Vendor'){
					$amb = $this->m_admin->getByID("ms_vendor","id_vendor",$header->dibayar)->row()->vendor_name;
				}else{
					$amb = $header->dibayar;
				}
				echo $amb;
				?>
			</td>
		</tr>
		<tr>
			<td width="20%">Alamat</td>
			<td width="80%">: 
				<?php 
				if($header->tipe_customer == 'Dealer'){
					$amb1 = $this->m_admin->getByID("ms_dealer","id_dealer",$header->dibayar)->row()->alamat;
				}elseif($header->tipe_customer == 'Vendor'){
					$amb1 = $this->m_admin->getByID("ms_vendor","id_vendor",$header->dibayar)->row()->alamat;
				}else{
					$amb1 = "-";
				}
				echo $amb1;
				?>			
			</td>
		</tr>
		<tr>
			<td width="20%">Sejumlah</td>
			<td width="80%">: 
				<?php 			
				$jum = $this->db->query("SELECT SUM(nominal) as jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id_penerimaan_bank'")->row()->jum;
				echo "Rp.".mata_uang($jum);
				?>
			</td>
		</tr>
		<tr>
			<td width="20%">Terbilang</td>
			<td width="80%">: 
				<?php 			
				$jum = $this->db->query("SELECT SUM(nominal) as jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id_penerimaan_bank'")->row()->jum;
				$tot_terbilang = ucwords(terbilang($jum));
				echo $tot_terbilang." Rupiah";
				?>
			</td>
		</tr>
		<tr>
			<td width="20%">Pembayaran</td>
			<td width="80%">: 
				<!-- <?php 			
				$jum = $this->db->query("SELECT SUM(nominal) as jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id_penerimaan_bank'")->row()->jum;
				$sisa = $this->db->query("SELECT SUM(sisa_hutang) as jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id_penerimaan_bank'")->row()->jum;			
				echo "Total Yang Telah Dibayar :".mata_uang($jum).", Sisa hutang : ".mata_uang($sisa);
				?> -->
				<?php 
				$bayar = "";$a=0;$b=0;
				$sql = $this->m_admin->getByID("tr_penerimaan_bank_detail","id_penerimaan_bank",$id_penerimaan_bank);
				foreach ($sql->result() as $isi2) {					
					$referensi = trim($isi2->referensi);
					$cek3	= $this->db->query("SELECT tgl_faktur FROM tr_invoice_dealer WHERE no_faktur = '$referensi'");						       					
					$cek4	= $this->db->query("SELECT tgl_bastd FROM tr_faktur_stnk WHERE no_bastd = '$referensi'");						       		
					if($cek3->num_rows() > 0){
						if($a<1){
							if($bayar != ""){
								$bayar .= "dan Faktur";
							}else{
								$bayar = "Faktur";								
							}
							$a++;
						}
					}elseif($cek4->num_rows() > 0){
						if($b<1){
							if($bayar != ""){
								$bayar .= "dan BBN";
							}else{
								$bayar = "BBN";
							}
							$b++;
						}
					}
				}
				echo $bayar;
				?>
			</td>
		</tr>
		<!-- <tr>
			<td width="20%">Cara Pembayaran</td>
			<td width="80%">: 
				<?php 						
				echo $header->via_bayar;
				?>
			</td>
		</tr> -->
	</table>

	<table width="90%" align="center" class="table table-bordered">
		<tr>
			<td>Tgl</td>
			<td>No Referensi</td>
			<td>Nominal (Rp)</td>
			<td>Keterangan</td>
		</tr>
		<?php
		$tot=0;
		$sql = $this->m_admin->getByID("tr_penerimaan_bank_detail","id_penerimaan_bank",$id_penerimaan_bank);
		foreach ($sql->result() as $isi2) {
			$referensi = trim($isi2->referensi);
			$cek1	= $this->db->query("SELECT tgl_invoice_program FROM tr_invoice_ekspedisi WHERE no_invoice_program = '$referensi'");						       
			$cek2	= $this->db->query("SELECT tgl_invoice_program FROM tr_invoice_ekspedisi WHERE no_penerimaan = '$referensi'");						       		
			$cek3	= $this->db->query("SELECT tgl_faktur FROM tr_invoice_dealer WHERE no_faktur = '$referensi'");						       					
			$cek4	= $this->db->query("SELECT tgl_bastd FROM tr_faktur_stnk WHERE no_bastd = '$referensi'");						       		
		  if($cek4->num_rows()>0){
				$row4 	= $cek4->row();			 				
		    $tgl 		=	$row4->tgl_bastd;
		  }elseif($cek1->num_rows()>0){
		   	$row 		= $cek1->row();			 	   			   	
		    $tgl 		= $row->tgl_invoice_program;
			}elseif($cek2->num_rows()>0){
				$row2 	= $cek2->row();			   	
				$tgl 		= $row2->tgl_invoice_program;
		  }elseif($cek3->num_rows()>0){
				$row3 	= $cek3->row();			 				
		    $tgl 		=	$row3->tgl_faktur;		  
		  }else{	
		  	$tgl 		= "-";		  
			}

			// $cek_tgl = $this->m_admin->getByID("tr_penerimaan_bank","id_penerimaan_bank",$id_penerimaan_bank);
			// $tgl = ($cek_tgl->num_rows() > 0) ? $cek_tgl->row()->tgl_entry:"";
			if($isi2->sisa_hutang ==''){
				$isi2->sisa_hutang = 0;
			}

			if($isi2->nominal == $isi2->sisa_hutang || $isi2->sisa_hutang == 0){
				$sisa = ""; 
			}else{
				$sisa = "Sisa Rp.".mata_uang($isi2->sisa_hutang);
			}
			$tot += $isi2->nominal;
			echo "
				<tr>
					<td>$tgl</td>
					<td>$isi2->referensi</td>
					<td align='right'>Rp.".mata_uang($isi2->nominal)."</td>					
					<td align='right'>$sisa</td>
				</tr>
			";
		}
		?>
		<tr>
			<td colspan="2" align='right'>Total</td>
			<td align="right">Rp. <?php echo mata_uang($tot); ?></td>
			<td></td>
		</tr>
	</table>
	<br>
	<table width="90%" align="center">
		<tr>
			<td>Ket.</td>
			<td>Nominal</td>			
			<td>No.BG/Cek</td>			
			<td>Nama Bank</td>
			<td>Tgl KU/Cek/BG</td>
		</tr>	
		<tr>
			<td colspan="5"><hr></td>
		</tr>	
		<?php 
		if($header->via_bayar == 'Transfer'){
			$sql = $this->m_admin->getByID("tr_penerimaan_bank_transfer","id_penerimaan_bank",$id_penerimaan_bank);
			$cek_bank = $this->m_admin->getByID("ms_rek_md","no_rekening",$header->account);
			$bank = ($cek_bank->num_rows() > 0) ? $cek_bank->row()->bank : "" ;
			foreach ($sql->result() as $isi) {
				echo "
					<tr>
						<td></td>
						<td>Rp.".mata_uang($isi->nominal_transfer)."</td>
						<td></td>
						<td>$bank</td>
						<td>$isi->tgl_transfer</td>
					</tr>
				";
			}
		}else{
			$sql = $this->m_admin->getByID("tr_penerimaan_bank_bg","id_penerimaan_bank",$id_penerimaan_bank);
			foreach ($sql->result() as $isi) {
				echo "
					<tr>
						<td></td>
						<td>Rp.".mata_uang($isi->nominal_transfer)."</td>
						<td>$isi->no_bg</td>
						<td>$header->rekening_tujuan</td>
						<td>$isi->tgl_bg</td>
					</tr>
				";
			}
		}
		?>
	</table>
	<hr>
	<table width="100%">
		<tr>
			<td width="80%">
				<ul>
					<li>Pembayaran dengan Bilyet Giro/Cek dianggap sah  bila telah diuangkan.</li>
					<li>Jika dalam batas waktu 1 (satu) minggu dari tanggal pengeluaran tanda terima ini tidak ada keberatan
					yang disampaikan mengenai pembayaran untuk data-data tercetak, maka transaksi ini kami anggap telah disetujui </li>
				</ul>
			</td>
			<td align="center">
				PENERIMA <br><br><br><br>
				(_____________) <br>				
				FINANCE HEAD
			</td>
		</tr>
	</table>	
</body>
</html>