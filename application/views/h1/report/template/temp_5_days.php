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
header("Content-Disposition: attachment; filename=rep_5_days_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$tanggal 	= gmdate("Y-m-d", time() + 60 * 60 * 7);			
$tgl_kemarin = kemarin($tanggal);
?>
PT. Sinar Sentosa Primatama 
Daily Sales Info Last 5 Days <br> <?php echo $tanggal ?>
<table border=1>
	<tr>
		<td>No</td>
		<td>Nama Dealer</td>
		<td>H-5</td>
		<td>H-4</td>
		<td>H-3</td>
		<td>H-2</td>
		<td>H-1</td>
		<td>H</td>		
	</tr>	
	
	<?php 	
	$tgl1  = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));	
	$tgl2  = date('Y-m-d', strtotime('-2 days', strtotime($tanggal)));	
	$tgl3  = date('Y-m-d', strtotime('-3 days', strtotime($tanggal)));	
	$tgl4  = date('Y-m-d', strtotime('-4 days', strtotime($tanggal)));	
	$tgl5  = date('Y-m-d', strtotime('-5 days', strtotime($tanggal)));	

	$no=1;
	$h=0;$h1=0;$h2=0;$h3=0;$h4=0;$h5=0;
	$jum=0;$jum_1=0;$jum_2=0;$jum_3=0;$jum_4=0;$jum_5=0;
	$dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
	foreach ($dealer->result() as $row) {		
		//function get_penjualan_inv($periode, $waktu, $id_tipe_kendaraan = null, $id_dealer = null, $id_series = null, $id_kategori = null, $id_finco = null, $id_kabupaten = null, $jenis_beli = null, $id_group_dealer = null, $id_segment = null)
		$h = $this->m_admin->get_penjualan_inv('tanggal', $tanggal, null, $row->id_dealer);
		$h1 = $this->m_admin->get_penjualan_inv('tanggal', $tgl1, null, $row->id_dealer);
		$h2 = $this->m_admin->get_penjualan_inv('tanggal', $tgl2, null, $row->id_dealer);
		$h3 = $this->m_admin->get_penjualan_inv('tanggal', $tgl3, null, $row->id_dealer);
		$h4 = $this->m_admin->get_penjualan_inv('tanggal', $tgl4, null, $row->id_dealer);
		$h5 = $this->m_admin->get_penjualan_inv('tanggal', $tgl5, null, $row->id_dealer);
		
		echo "
		<tr>
			<td>$no</td>
			<td>$row->nama_dealer</td>
			<td>$h5</td>			
			<td>$h4</td>			
			<td>$h3</td>			
			<td>$h2</td>			
			<td>$h1</td>			
			<td>$h</td>			
		</tr>
		";
		$jum_5 += $h5;
		$jum_4 += $h4;
		$jum_3 += $h3;
		$jum_2 += $h2;
		$jum_1 += $h1;
		$jum += $h;
		$no++;
	}
	?>				
	<tr>
		<td colspan="2">Grand Total</td>
		<td><?php echo $jum_5 ?></td>		
		<td><?php echo $jum_4 ?></td>		
		<td><?php echo $jum_3 ?></td>		
		<td><?php echo $jum_2 ?></td>		
		<td><?php echo $jum_1 ?></td>		
		<td><?php echo $jum ?></td>		
	</tr>
</table>