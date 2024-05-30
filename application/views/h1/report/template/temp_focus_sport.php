<?php 
function bln($a){
  $bulan=$bl=$month=$a;
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
function bulan_kemarin($tanggal)
{
	$tanggal = date_create($tanggal);	
	date_add($tanggal, date_interval_create_from_date_string('-1 months'));
	return date_format($tanggal, 'Y-m-d');
}
function kemarin($tanggal)
{
	$tanggal = date_create($tanggal);	
	date_add($tanggal, date_interval_create_from_date_string('-1 day'));
	return date_format($tanggal, 'Y-m-d');
}
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=sales_focus_sport_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$tanggal 	= gmdate("Y-m-d", time() + 60 * 60 * 7);			
$tgl_kemarin = kemarin($tanggal);
?>

Sales Focus Per Type Sport as of <?php echo $tgl_kemarin ?>
<table border=1>
	<tr>
		<td rowspan="2">No</td>
		<td rowspan="2">District</td>
		<td colspan="3">All Type</td>
		<td colspan="3">Total Sport</td>
		<td colspan="3">Sonic</td>
		<td colspan="3">CB150 VERZA</td>
		<td colspan="3">CB 150</td>		
		<td colspan="3">CBR 150</td>		
		<td colspan="3">CRF 150</td>		
		<td colspan="3">CBR 250</td>		
		<td colspan="3">CRF 250</td>		
	</tr>	
	<tr>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
		<td>M-1</td>
		<td>M</td>
		<td>%</td>
	</tr>
	
	<?php 
	$bulan 		= substr($tanggal, 0,7);
	$tgl 			= substr($tanggal, 8,2);
	$tgl2_a 	= $bulan."-01";
	$tgl2_b 	= $tanggal;

	//$bulan2 	= 
	$bulan2   = date('Y-m', strtotime('-1 month', strtotime($tanggal)));
	$tgl1_a		= $bulan2."-01";
	$tgl1_b		= $bulan2."-".$tgl;

	$jum_m11=0;$jum_m1=0;$jum_gr1=0;
	$jum_m12=0;$jum_m2=0;$jum_gr2=0;
	$jum_m13=0;$jum_m3=0;$jum_gr3=0;
	$jum_m14=0;$jum_m4=0;$jum_gr4=0;
	$jum_m15=0;$jum_m5=0;$jum_gr5=0;
	$jum_m16=0;$jum_m6=0;$jum_gr6=0;
	$jum_m17=0;$jum_m7=0;$jum_gr7=0;
	$jum_m18=0;$jum_m8=0;$jum_gr8=0;
	$jum_m19=0;$jum_m9=0;$jum_gr9=0;
	$no=1;
	$kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500");
	foreach ($kab->result() as $row) {
		$tanggal_arr = [$tgl2_a, $tgl2_b];
		$tanggal_arr2 = [$tgl1_a, $tgl1_b];
		//function get_penjualan_inv($periode, $waktu, $id_tipe_kendaraan = null, $id_dealer = null, $id_series = null, $id_kategori = null, $id_finco = null, $id_kabupaten = null, $jenis_beli = null, $id_group_dealer = null, $id_segment = null)
		$bulan_ini1 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, null, null, $row->id_kabupaten);
		$bulan_lalu1 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, null, null, null, $row->id_kabupaten);
		$jum_m11 += $bulan_lalu1;
		$jum_m1 += $bulan_ini1;
		$gr1 = @($bulan_ini1 / $bulan_lalu1) - 1;
		$gr1 = number_format($gr1,2) * 100;	
		if($jum_m1 <= 0) $gr1 = 100; 	
		$jum_gr1 += $gr1;	

		$bulan_ini2 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, 'S', null, $row->id_kabupaten);
		$bulan_lalu2 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, null, 'S', null, $row->id_kabupaten);
		$jum_m12 += $bulan_lalu2;
		$jum_m2 += $bulan_ini2;
		$gr2 = @($bulan_ini2 / $bulan_lalu2) - 1;
		$gr2 = number_format($gr2,2) * 100;	
		if($jum_m2 <= 0) $gr2 = 100; 	
		$jum_gr2 += $gr2;	

		$bulan_ini3 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'SONIC', null, null, $row->id_kabupaten);
		$bulan_lalu3 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'SONIC', null, null, $row->id_kabupaten);
		$jum_m13 += $bulan_lalu3;
		$jum_m3 += $bulan_ini3;
		$gr3 = @($bulan_ini3 / $bulan_lalu3) - 1;
		$gr3 = number_format($gr3,2) * 100;	
		if($jum_m3 <= 0) $gr3 = 100; 	
		$jum_gr3 += $gr3;	

		$bulan_ini4 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'CB150VERZA', null, null, $row->id_kabupaten);
		$bulan_lalu4 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'CB150VERZA', null, null, $row->id_kabupaten);
		$jum_m14 += $bulan_lalu4;
		$jum_m4 += $bulan_ini4;
		$gr4 = @($bulan_ini4 / $bulan_lalu4) - 1;
		$gr4 = number_format($gr4,2) * 100;	
		if($jum_m4 <= 0) $gr4 = 100; 	
		$jum_gr4 += $gr4;	

		$bulan_ini5 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'CB150R', null, null, $row->id_kabupaten);
		$bulan_lalu5 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'CB150R', null, null, $row->id_kabupaten);
		$jum_m15 += $bulan_lalu5;
		$jum_m5 += $bulan_ini5;
		$gr5 = @($bulan_ini5 / $bulan_lalu5) - 1;
		$gr5 = number_format($gr5,2) * 100;	
		if($jum_m5 <= 0) $gr5 = 100; 	
		$jum_gr5 += $gr5;	

		$bulan_ini6 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'CBR150', null, null, $row->id_kabupaten);
		$bulan_lalu6 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'CBR150', null, null, $row->id_kabupaten);
		$jum_m16 += $bulan_lalu6;
		$jum_m6 += $bulan_ini6;
		$gr6 = @($bulan_ini6 / $bulan_lalu6) - 1;
		$gr6 = number_format($gr6,2) * 100;	
		if($jum_m6 <= 0) $gr6 = 100; 	
		$jum_gr6 += $gr6;	

		$bulan_ini7 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'CRF150', null, null, $row->id_kabupaten);
		$bulan_lalu7 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'CRF150', null, null, $row->id_kabupaten);
		$jum_m17 += $bulan_lalu7;
		$jum_m7 += $bulan_ini7;
		$gr7 = @($bulan_ini7 / $bulan_lalu7) - 1;
		$gr7 = number_format($gr7,2) * 100;	
		if($jum_m7 <= 0) $gr7 = 100; 	
		$jum_gr7 += $gr7;	

		$bulan_ini8 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'CBR250', null, null, $row->id_kabupaten);
		$bulan_lalu8 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'CBR250', null, null, $row->id_kabupaten);
		$jum_m18 += $bulan_lalu8;
		$jum_m8 += $bulan_ini8;
		$gr8 = @($bulan_ini8 / $bulan_lalu8) - 1;
		$gr8 = number_format($gr8,2) * 100;	
		if($jum_m8 <= 0) $gr8 = 100; 	
		$jum_gr8 += $gr8;	

		$bulan_ini9 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, 'CRF250', null, null, $row->id_kabupaten);
		$bulan_lalu9 = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, 'CRF250', null, null, $row->id_kabupaten);
		$jum_m19 += $bulan_lalu9;
		$jum_m9 += $bulan_ini9;
		$gr9 = @($bulan_ini9 / $bulan_lalu9) - 1;
		$gr9 = number_format($gr9,2) * 100;	
		if($jum_m9 <= 0) $gr9 = 100; 	
		$jum_gr9 += $gr9;	


		echo "
		<tr>
			<td>$no</td>
			<td>$row->kabupaten</td>
			<td>$bulan_lalu1</td>
			<td>$bulan_ini1</td>
			<td>$gr1</td>
			<td>$bulan_lalu2</td>
			<td>$bulan_ini2</td>
			<td>$gr2</td>
			<td>$bulan_lalu3</td>
			<td>$bulan_ini3</td>
			<td>$gr3</td>
			<td>$bulan_lalu4</td>
			<td>$bulan_ini4</td>
			<td>$gr4</td>
			<td>$bulan_lalu5</td>
			<td>$bulan_ini5</td>
			<td>$gr5</td>
			<td>$bulan_lalu6</td>
			<td>$bulan_ini6</td>
			<td>$gr6</td>
			<td>$bulan_lalu7</td>
			<td>$bulan_ini7</td>
			<td>$gr7</td>
			<td>$bulan_lalu8</td>
			<td>$bulan_ini8</td>
			<td>$gr8</td>
			<td>$bulan_lalu9</td>
			<td>$bulan_ini9</td>
			<td>$gr9</td>
		</tr>
		";
		$no++;
	}
	?>				
	<tr>
		<td colspan="2">Grand Total</td>
		<td><?php echo $jum_m11 ?></td>
		<td><?php echo $jum_m1 ?></td>
		<td><?php echo $jum_gr1 ?></td>
		<td><?php echo $jum_m12 ?></td>
		<td><?php echo $jum_m2 ?></td>
		<td><?php echo $jum_gr2 ?></td>
		<td><?php echo $jum_m13 ?></td>
		<td><?php echo $jum_m3 ?></td>
		<td><?php echo $jum_gr3 ?></td>
		<td><?php echo $jum_m14 ?></td>
		<td><?php echo $jum_m4 ?></td>
		<td><?php echo $jum_gr4 ?></td>
		<td><?php echo $jum_m15 ?></td>
		<td><?php echo $jum_m5 ?></td>
		<td><?php echo $jum_gr5 ?></td>
		<td><?php echo $jum_m16 ?></td>
		<td><?php echo $jum_m6 ?></td>
		<td><?php echo $jum_gr6 ?></td>
		<td><?php echo $jum_m17 ?></td>
		<td><?php echo $jum_m7 ?></td>
		<td><?php echo $jum_gr7 ?></td>
		<td><?php echo $jum_m18 ?></td>
		<td><?php echo $jum_m8 ?></td>
		<td><?php echo $jum_gr8 ?></td>
		<td><?php echo $jum_m19 ?></td>
		<td><?php echo $jum_m9 ?></td>
		<td><?php echo $jum_gr9 ?></td>
	</tr>
</table>