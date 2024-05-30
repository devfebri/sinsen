<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=rekap_penerimaan_unit_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
    <tr>
         <td colspan="5" style="border:0"> PT. SINAR SENTOSA PRIMATAMA	</td>
    </tr>
    <tr>
        <td colspan="5" style="border:0">LAPORAN PENERIMAAN UNIT	</td>
    </tr>
    <tr>
        <td colspan="5" style="border:0">PERIODE : <?php echo $re_tgl1 .' s/d '. $re_tgl2;?></td>
    </tr>
 	<tr></tr>

 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">No Faktur</td>
 		<td align="center">Tgl Faktur</td>
 		<td align="center">Kode Item Kendaraan</td>
 		<td align="center">No Mesin</td>
 		<td align="center">No Rangka</td>
 		<td align="center">DPP</td>
 		<td align="center">Disc</td>
 		<td align="center">PPn In</td>
 		<td align="center">PPh 22</td>
 		<td align="center">Total</td>
 		<td align="center">Ekspedisi</td>
 		<td align="center">No. Polisi</td>
 		<td align="center">No SIPB</td>
 		<td align="center">Tgl SIPB</td>
 		<td align="center">No SL</td>
 		<td align="center">Tgl SL</td>
 		<td align="center">No Penerimaan</td>
 		<td align="center">Tgl Penerimaan</td>
 	</tr>
 	<?php 
 	$no=1;
	$total_dpp = 0;
	$total_ppn = 0;	
	$total_pph = 0;	
	$total_disc = 0;
	$temp_inv = '';

 	foreach ($sql->result() as $row) {
 		$bulan = substr($row->tgl_sl, 2,2);
        $tahun = substr($row->tgl_sl, 4,4);
        $tgl = substr($row->tgl_sl, 0,2);
        $tanggal_sl = $tgl."-".$bulan."-".$tahun;
  
 	$sipb = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$row->no_sipb'");
   	 if($sipb->num_rows() > 0){        
        	$bulan_s = substr($sipb->row()->tgl_sipb, 2,2);
        	$tahun_s = substr($sipb->row()->tgl_sipb, 4,4);
        	$tgl_s = substr($sipb->row()->tgl_sipb, 0,2);
        	$tanggal_sipb = $tgl_s."-".$bulan_s."-".$tahun_s;
    	}else{
        	$tanggal_sipb = "";
    	}

        $dpp = number_format($row->dpp,0);
        $disc = number_format($row->disc_quo,0);
        $ppn = number_format($row->ppn,0);
        $pph = number_format($row->pph,0);

        //$dpp = $row->dpp;
        //$disc = $row->disc_quo;
        //$ppn = $row->ppn;
        //$pph = $row->pph;


		if($temp_inv != $row->no_faktur && $temp_inv !=''){
			echo '
			<tr>
				<td colspan="6" align="center">Subtotal</td>
				<td> Rp. '.number_format($total_dpp,0).'</td>
				<td> Rp. '.number_format($total_disc,0).'</td>
				<td> Rp. '.number_format($total_ppn,0).'</td>
				<td> Rp. '.number_format($total_pph,0).'</td>
				<td> Rp. '.number_format($total_dpp - $total_disc + $total_ppn + $total_pph,0).'</td>

			</tr>
			';

			// echo '
			// <tr>
			// 	<td colspan="6" align="center">Subtotal</td>
			// 	<td> '.$total_dpp.'</td>
			// 	<td> '.$total_disc.'</td>
			// 	<td> '.$total_ppn.'</td>
			// 	<td> '.$total_pph.'</td>
			// 	<td> '.$total_dpp - $total_disc + $total_ppn + $total_pph.'</td>

			// </tr>
			// ';
			
			$temp_inv = $row->no_faktur;
			$total_dpp = 0;
			$total_ppn = 0;
			$total_pph = 0;	
			$total_disc = 0;
			$no=1;
		}else{
			$temp_inv = $row->no_faktur;
		}

		$total = $row->dpp - $row->disc_quo + $row->pph + $row->ppn;
		$total = number_format($total,0);
 		echo "
 			<tr>
 				<td>$no</td>
 				<td>$row->no_faktur</td>
 				<td>$row->tgl_faktur</td>
 				<td>$row->id_tipe_kendaraan-$row->id_warna</td>
 				<td>$row->no_mesin</td>
 				<td>$row->no_rangka</td>
 				<td> $dpp</td>
 				<td> $disc</td>
 				<td> $ppn</td>
 				<td> $pph</td>
 				<td> $total</td>
 				<td>$row->vendor_name</td>
 				<td>$row->no_polisi</td>
 				<td>$row->no_sipb</td>
 				<td>$tanggal_sipb</td>
 				<td>$row->no_sl</td>
 				<td>$tanggal_sl</td>
 				<td>$row->id_penerimaan_unit</td>
 				<td>$row->tgl_penerimaan</td>
 			</tr>
 		";
 		$no++;
		$total_dpp += $row->dpp;
		$total_disc += $row->disc_quo;
		$total_ppn += $row->ppn;
		$total_pph += $row->pph;
 	}

	$total_ppn = floor($total_ppn);
	
		echo '
			<tr>
				<td colspan="6" align="center">Subtotal</td>
				<td> Rp. '.number_format($total_dpp,0).'</td>
				<td> Rp. '.number_format($total_disc,0).'</td>
				<td> Rp. '.number_format($total_ppn,0).'</td>
				<td> Rp. '.number_format($total_pph,0).'</td>
				<td> Rp. '.number_format($total_dpp - $total_disc + $total_ppn + $total_pph,0).'</td>
			</tr>
		';

		// echo '
		// 	<tr>
		// 		<td colspan="6" align="center">Subtotal</td>
		// 		<td> '.$total_dpp.'</td>
		// 		<td> '.$total_disc.'</td>
		// 		<td> '.$total_ppn.'</td>
		// 		<td> '.$total_pph.'</td>
		// 		<td> '.$total_dpp - $total_disc + $total_ppn + $total_pph.'</td>

		// 	</tr>
		// 	';




 	?>
</table>


