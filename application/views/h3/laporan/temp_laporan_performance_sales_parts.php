<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Performance Salesman Parts by Amount_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Laporan Performance Salesman Parts by Amount
				<br> <?php echo $laporan_performance_sales_parts->row()->nama_dealer ?> </b> <br><br></caption>
	<?php } else { ?>
		<caption><b>Laporan Performance Salesman Parts by Amount
				<br> Periode <?php echo $start_date_2 . " s/d " . $end_date_2 ?>
			</b><br><br></caption>
	<?php } ?>

	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Nama Salesman </b></td>
		<td style="vertical-align : middle;text-align:center;" colspan="3"><b>M-1</b></td>
		<td style="vertical-align : middle;text-align:center;" colspan="3"><b>M</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>% Growth</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;"><b>Target Global</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Actual</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>% Ach</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Target Global</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>Actual</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>% Ach</b></td>
	</tr>
	<?php
	$nom = 1;
	$sum_target_global_m1=0;
	$sum_actual_m1=0;
	$sum_target_global=0;
	$sum_actual=0;
	$sum_ach_m1 =0;
	$sum_ach = 0; 
	$sum_growth = 0; 
	if ($laporan_performance_sales_parts->num_rows() > 0) {
		foreach ($laporan_performance_sales_parts->result() as $row) {


			$start_date_bulan = date("Y-m", strtotime($start_date));
			$end_date_bulan = date("Y-m", strtotime($end_date));

			$start_date_bulan_m1 = date('Y-m', strtotime($start_date_bulan . ' -1 month'));
			$end_date_bulan_m1 = date('Y-m', strtotime($end_date_bulan . ' -1 month'));


			if($id_dealer == 'all'){
				$target_global = $this->db->query("SELECT ifnull(ts.target_salesman_global,0) as target
				from ms_h3_md_target_salesman ts 
				where DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan' and  DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan' AND ts.jenis_target_salesman='Parts' and ts.id_salesman = '$row->id_salesman'")->row();
			}else{
				$target_global = $this->db->query("SELECT ifnull(tsp.target_part,0) as target
				from ms_h3_md_target_salesman ts
				join ms_h3_md_target_salesman_parts tsp on tsp.id_target_salesman=ts.id 
				WHERE tsp.id_dealer = $row->id_dealer and ts.id_salesman= '$row->id_salesman' and DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan' and  DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan'")->row();
			}

			if($id_dealer == 'all'){
				$target_global_m1 = $this->db->query("SELECT ifnull(ts.target_salesman_global,0) as target
				from ms_h3_md_target_salesman ts 
				where DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan_m1' and  DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan_m1' AND ts.jenis_target_salesman='Parts' and ts.id_salesman = '$row->id_salesman'")->row();
			}else{
				$target_global_m1 = $this->db->query("SELECT ifnull(tsp.target_part,0) as target
				from ms_h3_md_target_salesman ts
				join ms_h3_md_target_salesman_parts tsp on tsp.id_target_salesman=ts.id 
				WHERE tsp.id_dealer = $row->id_dealer and ts.id_salesman= '$row->id_salesman' and DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan_m1' and  DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan_m1'")->row();
			}

			if($target_global_m1->target != 0){
				$ach_m1 = ($row->total_aktual_m1/$target_global_m1->target)*100;
				$ach_m1 = round($ach_m1);
			}else{
				$ach_m1 = 0;
			}

			if($target_global->target != 0){
				$ach = ($row->total_aktual_m/$target_global->target)*100;
				$ach = round($ach);
			}else{
				$ach = 0;
			}

			if($row->total_aktual_m1 != 0){
				$growth= (($row->total_aktual_m/$row->total_aktual_m1)-1)*100;
				$growth=round($growth);
			}else{
				$growth=0;
			}
			
			echo "
				<tr>
					<td>$nom</td>
					<td>$row->nama_lengkap</td>
					<td align='right'>".number_format($target_global_m1->target, 0, ',', '.')."</td>
					<td align='right'>".number_format($row->total_aktual_m1, 0, ',', '.')."</td>
					<td align='right'>$ach_m1 %</td>
					<td align='right'>".number_format($target_global->target , 0, ',', '.')."</td>
					<td align='right'>".number_format($row->total_aktual_m , 0, ',', '.')."</td>
					<td align='right'>$ach %</td>
					<td align='right'>$growth %</td>
				</tr>
			";
			$nom++;
			$sum_target_global_m1 += $target_global_m1->target;
			$sum_actual_m1 += $row->total_aktual_m1;
			$sum_target_global += $target_global->target;
			$sum_actual += $row->total_aktual_m;

		
			
		}

		$sum_ach_m1 = round(($sum_actual_m1/$sum_target_global_m1)*100);
		$sum_ach = round(($sum_actual/$sum_target_global)*100);
		$sum_growth = round((($sum_actual/$sum_actual_m1)-1)*100);
		echo "
		<tr>
			<td colspan='2'>Total</td>
			<td align='right'>".number_format($sum_target_global_m1 , 0, ',', '.')."</td>
			<td align='right'>".number_format($sum_actual_m1 , 0, ',', '.')."</td>
			<td align='right'> $sum_ach_m1 %</td>
			<td align='right'>".number_format($sum_target_global , 0, ',', '.')."</td>
			<td align='right'>".number_format($sum_actual , 0, ',', '.')."</td>
			<td align='right'> $sum_ach %</td>
			<td align='right'> $sum_growth %</td>
		</tr>
	";
	} else {
		echo "<td colspan='9' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>