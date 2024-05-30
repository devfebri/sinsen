<body onload="ambil()">
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
header("Content-Disposition: attachment; filename=sales_finco_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$tanggal 	= gmdate("Y-m-d", time() + 60 * 60 * 7);			
$tgl_kemarin = kemarin($tanggal);
?>

Sales Finco Comparison as of <?php echo $tgl_kemarin ?>
<table border=1>
	<tr>
		<td rowspan="2">Nama Finco</td>
		<td colspan="2">Sales</td>
		<td rowspan="2">Growth</td>
		<td rowspan="2">M/S</td>
		<td rowspan="2">MSCP</td>
	</tr>	
	<tr>
		<td>M-1</td>
		<td>M</td>
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
	$tanggal_arr = [$tgl2_a, $tgl2_b];
	$tanggal_arr2 = [$tgl1_a, $tgl1_b];


	$grand_m1=0;$grand_m=0;
	$bulan_ini_cash = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, null, null, null, 'Cash');
	$bulan_lalu_cash = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, null, null, null, null, 'Cash');

	$grand_m1 += $bulan_lalu_cash;
	$grand_m += $bulan_ini_cash;

	$gr = @($bulan_ini_cash / $bulan_lalu_cash) - 1;
	$gr = number_format($gr,2) * 100;
	
	if($bulan_lalu_cash <= 0){
		$gr = 100; 
	}


	$jum_m1=0;$jum_m=0;$jum_gr=0;
	$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1 ORDER BY FIELD (finance_company,'FIFASTRA','ADMF','SOF','NSC','WOMF','MCF','MUF','MF')");
	foreach ($sql->result() as $isi) {
		//function get_penjualan_inv($periode, $waktu, $id_tipe_kendaraan = null, $id_dealer = null, $id_series = null, $id_kategori = null, $id_finco = null, $id_kabupaten = null, $jenis_beli = null, $id_group_dealer = null, $id_segment = null)
		$bulan_ini = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr, null, null, null, null, $isi->id_finance_company);
		$bulan_lalu = $this->m_admin->get_penjualan_inv('range_tanggal', $tanggal_arr2, null, null, null, null, $isi->id_finance_company);
		$jum_m1 += $bulan_lalu;
		$jum_m += $bulan_ini;

		$gr = @($bulan_ini / $bulan_lalu) - 1;
		$gr = number_format($gr,2) * 100;
		
		if($bulan_lalu <= 0){
			$gr = 100; 
		}
		$jum_gr += $gr;

		$ms = round(@($bulan_ini/($jum_m  + $grand_m1)),2);
		$mscp = round(@($bulan_ini/(($jum_m + $grand_m)-($jum_m1 + $grand_m1))),2);
		echo "
		<tr>
			<td>$isi->finance_company</td>
			<td>$bulan_lalu</td>
			<td>$bulan_ini</td>
			<td>$gr %</td>
			<td>$ms</td>
			<td>$mscp</td>
		</tr>
		";
	}
	echo "
		<tr>
			<td>Cash</td>
			<td>$bulan_ini_cash</td>
			<td>$bulan_lalu_cash</td>
			<td>$gr %</td>
			<td>
				<div id='ms'></div>
			</td>
			<td>
				<div id='mscp'></div>
			</td>
		</tr>
	";

	$ms_cash = round(@($bulan_ini_cash/($jum_m  + $grand_m1)),2);
	$mscp_cash = round(@($bulan_ini_cash/(($jum_m + $grand_m)-($jum_m1 + $grand_m1))),2);

	echo "<input type='hidden' id='ms_cash' value=$ms_cash>";
	echo "<input type='hidden' id='mscp_cash' value=$mscp_cash>";	


	?>	
	<tr>
		<td>
			<strong>Grand Total</strong>			
		</td>
		<td><?php echo $jum_m1 + $grand_m1 ?></td>
		<td><?php echo $jum_m + $grand_m ?></td>
		<td><?php echo $jum_gr ?></td>
		<td></td>
		<td></td>
	</tr>
</table>

<script type="text/javascript">
function ambil(){
	var hasil = document.getElementById("ms");  	
	var asal = document.getElementById("ms_cash").value;   
	var asal2 = document.getElementById("mscp_cash").value;   
	var hasil2 = document.getElementById("mscp");  
	
  hasil.innerHTML = asal;
  hasil2.innerHTML = asal2;
	//$("#ms").val("876");
}
</script>