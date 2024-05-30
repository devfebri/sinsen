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
header("Content-Disposition: attachment; filename=dealer_finco_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$tanggal 	= gmdate("Y-m-d", time() + 60 * 60 * 7);			
$tgl_kemarin = kemarin($tanggal);
?>

Sales Dealer By Finco as of <?php echo $tgl_kemarin ?>
<table border=1>
	<tr>
		<td>No</td>
		<td>Nama Dealer</td>
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1 ORDER BY FIELD (finance_company,'FIFASTRA','ADMF','SOF','NSC','WOMF','MCF','MUF','MF')");
		foreach ($sql->result() as $isi) {
			echo "<td>$isi->finance_company</td>";
		}		
		?>	
		<td>Cash</td>
		<td>Total</td>
		<td></td>
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1 ORDER BY FIELD (finance_company,'FIFASTRA','ADMF','SOF','NSC','WOMF','MCF','MUF','MF')");
		foreach ($sql->result() as $isi) {
			echo "<td>$isi->finance_company</td>";
		}		
		?>	
		<td>Cash</td>
		<td>Total</td>
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

	$jum_m1=0;$jum_m=0;$jum_gr=0;
	$dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
	$no=1;
	foreach ($dealer->result() as $row) {
		echo "
		<tr>
			<td>$no</td>
			<td>$row->nama_dealer</td>";
			$jum_unit=0;
			$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1 ORDER BY FIELD (finance_company,'FIFASTRA','ADMF','SOF','NSC','WOMF','MCF','MUF','MF')");
			foreach ($sql->result() as $isi) {
				$tanggal_arr = [$tgl2_a, $tgl2_b];
				$tanggal_arr2 = [$tgl1_a, $tgl1_b];
				//function get_penjualan_inv($periode, $waktu, $id_tipe_kendaraan = null, $id_dealer = null, $id_series = null, $id_kategori = null, $id_finco = null, $id_kabupaten = null, $jenis_beli = null, $id_group_dealer = null, $id_segment = null)
				$unit = $this->m_admin->get_penjualan_inv('tanggal', $tgl_kemarin, null, $row->id_dealer, null, null, $isi->id_finance_company);				
				$jum_unit += $unit;
				echo "
				<td>$unit</td>
				";
			}
			$unit_cash = $this->m_admin->get_penjualan_inv('tanggal', $tgl_kemarin, null, $row->id_dealer, null, null, null, null, 'Cash');
			$jum_unit += $unit_cash;
			echo "
			<td>$unit_cash</td>
			<td>$jum_unit</td>
			<td></td>";
			
			$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1 ORDER BY FIELD (finance_company,'FIFASTRA','ADMF','SOF','NSC','WOMF','MCF','MUF','MF')");
			foreach ($sql->result() as $isi) {
				$tanggal_arr = [$tgl2_a, $tgl2_b];
				$tanggal_arr2 = [$tgl1_a, $tgl1_b];
				//function get_penjualan_inv($periode, $waktu, $id_tipe_kendaraan = null, $id_dealer = null, $id_series = null, $id_kategori = null, $id_finco = null, $id_kabupaten = null, $jenis_beli = null, $id_group_dealer = null, $id_segment = null)
				$unit = $this->m_admin->get_penjualan_inv('tanggal', $tgl_kemarin, null, $row->id_dealer, null, null, $isi->id_finance_company);				
				$unit_pr = @($unit/$jum_unit);
				$unit_pr = number_format($unit_pr,2) * 100;
				echo "
				<td>$unit_pr %</td>
				";
			}
			$unit_cash = $this->m_admin->get_penjualan_inv('tanggal', $tgl_kemarin, null, $row->id_dealer, null, null, null, null, 'Cash');
			//$jum_unit += $unit_cash;
			$unit_cash_pr = @($unit_cash/$jum_unit);
			$unit_cash_pr = number_format($unit_cash_pr,2) * 100;
			
			$total_pr = @($jum_unit/$jum_unit);			
			$total_pr = number_format($total_pr,2) * 100;
			echo "
			<td>$unit_cash_pr %</td>
			<td>$total_pr %</td>

		</tr>
		";
		$no++;
	}
	?>		
</table>