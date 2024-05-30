<!DOCTYPE html>
<html>
<!-- <html lang="ar"> for arabic only -->
	 <?php 
		  function mata_uang2($a){
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
					 sheet-size: 210mm 165mm;
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
					 /*font-size: 10pt;*/
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

<table width="100%" border="0" style="padding-top:7px;font-size: 13px;">
	<tr>
		<td width="60%"></td>
		<td>Tgl Entry</td>
		<td>: <?php echo tgl_indo($tgl); ?></td>
	</tr>
	<tr>	
		<td></td>
		<td>Bank</td>
		<?php 
		$cari_bank = $this->m_admin->getByID('ms_rek_md','no_rekening',$row->account);
		$bank = ($cari_bank->num_rows()>0) ? $cari_bank->row()->bank : "" ;
		?>
		<td>: <?php echo $bank ?> a/c <?php echo $row->account ?></td>
	</tr>
	<tr>	
		<td></td>
		<td>No BG</td>
		<td>: <?php echo $row->no_bg ?></td>
	</tr>
	<tr>	
		<?php 	
		if($row->via_bayar == 'BG'){
			$tgl_bayar  = $row->tgl_bg;
		}else{
			$tgl_bayar  = $row->tgl_transfer;
		}
		?>
		<td></td>
		<td>Tanggal Bayar</td>	
		<td>: <?php echo tgl_indo($tgl_bayar) ?></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><h4> <br> <?php echo $row->vendor_name." ".$row->no_rekening."  ".$row->nama_rekening; ?></h></td>
	</tr>
</table>
<br><br><br><br>

<table width="100%" style="font-size: 10px;">
	<tr>	
		<td width="80%">
			<table width="80%">
			<tr>
				<td></td>
				<td colspan="3" align="left">
					<?php 
					$customer = $row->dibayar;
          if ($row->tipe_customer=='Dealer') {
            $customer = $this->db->get_where('ms_dealer',['id_dealer'=>$row->dibayar]);
            $customer = $customer->num_rows()>0?$customer->row()->nama_dealer:'';
          }                              
          if ($row->tipe_customer=='Vendor') {
            $customer = $this->db->get_where('ms_vendor',['id_vendor'=>$row->dibayar]);
            $customer = $customer->num_rows()>0?$customer->row()->vendor_name:'';
          }  
					echo $customer ?> <br>
					<?php echo $row->deskripsi ?>
				</td>
			</tr>
			<?php 
			$nom=0;
			$no=1;
			$sql2 = $this->db->query("SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id_voucher_bank'");
			foreach ($sql2->result() as $isi) {				
				echo "
				<tr>
					<td valign='top'>$no.</td>
					<td align='left'>$isi->referensi ($isi->keterangan) </td>
					<td valign='top' align='left' width='15%'>: Rp.</td>
					<td valign='top' align='right'> ".mata_uang2($isi->nominal)." </td>
				</tr>";
				$no++;
				$nom += $isi->nominal;
			}
			?>							
			</table>
		</td>
		<td valign="top" align="right"  style="font-size: 12px;">
			<?php echo "Rp. ".mata_uang2($nom) ?>
		</td>
	</tr>
</table>
<br><br><br>
<table width="100%" border="0" style="padding-top:100px;"  style="font-size: 12px;">
	<tr>
		<td width="10%"></td>
		<td width="70%" style="font-size: 10px;">
			<?php echo ucwords($terbilang)." Rupiah"; ?>
		</td>
		<td valign="top" align="right">
			<?php echo "Rp. ".mata_uang2($nom) ?>
		</td>
	</tr>
</table>
<br>
<table width="100%" border="0" style="padding-top:100px;"  style="font-size: 12px;">	
	<tr>
		<td width="20%">
			<?php 
			$sq = $this->db->query("SELECT DISTINCT(coa) FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$id_voucher_bank'");
			foreach ($sq->result() as $isi) {
				echo $isi->coa;
			}
			//echo $row->jenis_bayar; 
			?>
		</td>
		<td valign="top" width="50%">
			: <?php echo "Rp. ".mata_uang2($nom) ?>
		</td>
	</tr> 	
	<tr>
		<td>
			<?php echo $row->bank ?>
		</td>
		<td valign="top">
			: <?php echo "Rp. ".mata_uang2($nom) ?>
		</td>
	</tr>
</table> 

</body>
</html>
