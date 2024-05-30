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
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=dealer_contribusi_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
	<tr>
		<td>Sales Dealer Contribution By Down Payment</td>
		<td></td>
	</tr>	
</table>
<table border="1">  
	<tr>
		<td  bgcolor='aqua' align="center" rowspan="3">No</td>
		<td  bgcolor='aqua' align="center" rowspan="3">Nama Dealer</td>				
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
		$jum = $sql->num_rows() * 3;
		?>
		<td  bgcolor='orange' align="center" colspan="<?php echo $jum ?>">Hitungan Real</td>				
		<td  bgcolor='pink' align="center" colspan="<?php echo $jum ?>">Presentase</td>				
	</tr>
	<tr>
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
		foreach ($sql->result() as $ambil) {
			echo "
			<td align='center' colspan='3'>$ambil->finance_company</td>
			";
		}
		?>
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
		foreach ($sql->result() as $ambil) {
			echo "
			<td align='center' colspan='3'>$ambil->finance_company</td>
			";
		}
		?>
	</tr>
	<tr>		
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
		foreach ($sql->result() as $ambil) {
			echo "
			<td align='center'><10%</td>
			<td align='center'>10%-20%</td>
			<td align='center'>>20%</td>
			";
		}
		?>
		<?php 
		$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
		foreach ($sql->result() as $ambil) {
			echo "
			<td align='center'><10%</td>
			<td align='center'>10%-20%</td>
			<td align='center'>>20%</td>
			";
		}
		?>
	</tr>
<?php 
$no=1;
         
$sql_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE ms_kabupaten.id_provinsi = 1500
	ORDER BY ms_kabupaten.Kabupaten ASC");
foreach ($sql_kab->result() as $isi) {  
	$cek_dealer = $this->db->query("SELECT * FROM ms_dealer 
    LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
    LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
    LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
    WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND ms_dealer.active = 1");	
	echo "
	<tr>";		
		foreach ($cek_dealer->result() as $amb) {					
			echo "
			<tr>
				<td>$no</td>
				<td>$amb->nama_dealer</td>";
				
				$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
				foreach ($sql->result() as $row) {
					$id_dealer = $amb->id_dealer;
					$spk = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
            WHERE jenis_beli = 'Kredit' AND tr_spk.id_dealer = '$id_dealer'
            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					$spk1 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
		            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0 AND 10 AND tr_spk.id_dealer = '$id_dealer'
		            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					$spk2 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
		            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 11 AND 20 AND tr_spk.id_dealer = '$id_dealer'
		            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					$spk3 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
		            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 > 20 AND tr_spk.id_dealer = '$id_dealer'
		            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					if ($spk->jum != 0) {
						$isi_spk1 = round((($spk1->jum / $spk->jum) * 100), 2);
						$isi_spk2 = round((($spk2->jum / $spk->jum) * 100), 2);
						$isi_spk3 = round((($spk3->jum / $spk->jum) * 100), 2);
					} else {
						$isi_spk1 = round((($spk1->jum) * 100), 2);
						$isi_spk2 = round((($spk2->jum) * 100), 2);
						$isi_spk3 = round((($spk3->jum) * 100), 2);
					}
					echo "
					<td align='center'>$isi_spk1</td>
					<td align='center'>$isi_spk2</td>
					<td align='center'>$isi_spk3</td>
					";
				}

				$sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");		
				foreach ($sql->result() as $row) {
					$id_dealer = $amb->id_dealer;
					$spk = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
            WHERE jenis_beli = 'Kredit' AND tr_spk.id_dealer = '$id_dealer'
            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					$spk1 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
		            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0 AND 10 AND tr_spk.id_dealer = '$id_dealer'
		            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					$spk2 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
		            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 11 AND 20 AND tr_spk.id_dealer = '$id_dealer'
		            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					$spk3 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
		            WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 > 20 AND tr_spk.id_dealer = '$id_dealer'
		            AND tr_spk.id_finance_company = '$row->id_finance_company'")->row();
					if ($spk->jum != 0) {
						$isi_spk1 = round((($spk1->jum / $spk->jum) * 100), 2);
						$isi_spk2 = round((($spk2->jum / $spk->jum) * 100), 2);
						$isi_spk3 = round((($spk3->jum / $spk->jum) * 100), 2);
					} else {
						$isi_spk1 = round((($spk1->jum) * 100), 2);
						$isi_spk2 = round((($spk2->jum) * 100), 2);
						$isi_spk3 = round((($spk3->jum) * 100), 2);
					}


					$jum_spk 		= $isi_spk1 + $isi_spk2 + $isi_spk3;
					$isi_spk11 	= @($isi_spk1 / $jum_spk) * 100;					
					$isi_spk11 	= round($isi_spk11, 2);

					$isi_spk21 	= @($isi_spk2 / $jum_spk) * 100;					
					$isi_spk21 	= round($isi_spk21, 2);

					$isi_spk31 	= @($isi_spk3 / $jum_spk) * 100;					
					$isi_spk31 	= round($isi_spk31, 2);

					echo "
					<td align='center'>$isi_spk11</td>
					<td align='center'>$isi_spk21</td>
					<td align='center'>$isi_spk31</td>
					";
				}
				
				echo "
			</tr>";					
			$no++;
		}				
		echo "
		<td bgcolor='yellow' colspan='54'>$isi->kabupaten</td>
	</tr>";	
}
echo "<tr>";
?>