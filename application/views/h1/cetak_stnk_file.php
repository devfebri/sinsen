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
					 sheet-size: 207mm 316mm;
					 margin-left: 1cm;
					 margin-right: 1cm;
					 margin-bottom: 1.5cm;
					 margin-top: 1.5cm;
				}
				.kertas {page-break-after: always;}
				.kertas2 {page-break-before: always;}
				.text-center{text-align: center;}
				.table {                    
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
					 font-family: "Times New Roman";
					 font-size: 10pt;
				}
				
				#tipis{
					 padding-top: -3px;
				}
				

		  }
	 </style>    
</head>
<?php 
$row = $query->row();
?>
<body>

<table class="table table-bordered" width="100%" border="1" style="font-size: 13px;">
	 <tr>
		  <td align="center"><h2>SERAH TERIMA STNK</h2></td>
	 </tr>
</table>
<br>
<table width="100%" border="0">
	 <tr>
		  <td width="20%">No Serah Terima</td>
		  <td width="80%">: <?php echo $no_kirim_stnk ?></td>        
	 </tr>    
	 <tr>
		  <td>Tanggal</td>
		  <td>: <?php echo $tgl = date('d-m-Y', strtotime($tgl)); ?></td>        
	 </tr>    
	 <tr>
	 	<td colspan="2">
	 		<br>
	 		Kepada Yth <br>
	 		Kepala Bagian STNK <br>
	 		Di PT. Sinar Sentosa Primatama <br><br>
	 	</td>
	 </tr>
	 <tr>
	 	<td colspan="2">
	 		<br>
	 		Dengan Hormat, <br>
	 		Dengan ini kami serah terimakan dokumen STNK sebagai berikut:
	 	</td>
	</tr>
</table> 	
<table class="table table-bordered" width="100%" border="1" style="font-size: 13px;">
	 <tr>
		  <td width="5%" align='center'>No</td>		 
		  <td width="30%" align='center'>Nama Dealer</td>		 
		  <td width="20%" align='center'>Nama Konsumen</td>		 
		  <td width="15%" align='center'>No Mesin</td>		 
		  <td width="13%" align='center'>No Polisi</td>		 
		  <td width="13%" align='center'>No STNK</td>		 
	 </tr>
	 <?php 
	 $no=1;
	 foreach ($query->result() as $isi) {
	 	$ds["no_kirim_stnk"]	= $no_kirim_stnk;
  	$ds["no_stnk"] 				= $isi->no_stnk;
		$ds["no_mesin"] 	= $no_mesin		= $isi->no_mesin;	
		$ds["cetak"] = $dss["cetak"]	= "ya";	

		
		if(!isset($_GET['id'])){
			$cek = $this->db->query("SELECT * FROM tr_kirim_stnk_detail WHERE no_mesin = '$no_mesin'");
			if($cek->num_rows() > 0){
				$this->m_admin->update("tr_kirim_stnk_detail",$dss,"no_mesin",$no_mesin);															
			}else{							
				$this->m_admin->insert("tr_kirim_stnk_detail",$ds);								
			}
			$dw["print_stnk"] 		= "ya";	
			$this->db->query("UPDATE tr_entry_stnk SET print_stnk = 'ya' WHERE no_mesin = '$isi->no_mesin'");
		}
	 	echo "
	 	<tr>
	 		<td align='center'>$no</td>
	 		<td>$isi->nama_dealer</td>
	 		<td>$isi->nama_konsumen</td>
	 		<td>$isi->no_mesin</td>
	 		<td>$isi->no_pol</td>
	 		<td>$isi->no_stnk</td>
	 	</tr>
	 	";
	 	$no++;
	 }
	 ?>
</table>
<table width="100%" border="0">		
	<tr>
		<td>
			Demikian kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
		</td>
	</tr> 
	<tr>
		<td>
			<hr>
		</td>
	</tr> 
</table> 
<table border="0">
	<tr>
		<td colspan="5" align="center"><b>Dokumen STNK telah diterima oleh Pihak PT.Sinar Sentosa Primatama</b></td>
	</tr>
	<tr>
		<td colspan="3">Yang Menyerahkan</td>
		<td>Yang Menerima</td>		
	</tr>
	<tr>
		<td width="20%">Nama</td>
		<td width="25%">: ___________________________</td>
		<td width="2%"></td>
		<td width="20%">Nama</td>
		<td width="25%">: ____________________________</td>
	</tr>
	<tr>
		<td width="20%">Tanggal</td>
		<td width="25%">: ___________________________</td>
		<td width="2%"></td>
		<td width="20%">Tanggal</td>
		<td width="25%">: ____________________________</td>
	</tr>
	<tr>
		<td valign="top" width="20%">Jam</td>
		<td valign="top"  width="25%">: _______________________WIB</td>
		<td valign="top"  width="2%"></td>
		<td valign="top"  width="20%">Jam</td>
		<td valign="top"  width="25%">: _______________________WIB <br><br><br><br><br><br></td>
	</tr>
	<tr>
		<td width="20%">TTD</td>
		<td width="25%">: ___________________________</td>
		<td width="2%"></td>
		<td width="20%">TTD</td>
		<td width="25%">: ___________________________</td>
	</tr>
</table> 
</body>
</html>
