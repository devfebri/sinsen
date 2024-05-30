<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Performance Sales By Channel_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table>
	<tr>
		<td style="vertical-align : middle;text-align:center;" colspan="9"><b>Laporan Performance Sales by Channel</b></td>
	</tr>
</table>

<br>

<!-- Cost Price Oil-->
<table border="1">
	<tr>
		<td colspan="9" style="vertical-align : middle;text-align:left;"><b>Cost Price Oil</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Status Customer </b></td>
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
	<tr>
		<?php 
			$ach_cost_oli_m1_h123 = 0;
			$ach_cost_oli_m_h123 = 0;
			$growth_cost_oli_h123 = 0;
			if($laporan_sales_by_channel_target_oli_m1->row()->h123 !=0){
				$ach_cost_oli_m1_h123 = round(($laporan_sales_by_channel_cost_price_oil_m1->row()->h123 / $laporan_sales_by_channel_target_oli_m1->row()->h123)*100);
			}else{
				$ach_cost_oli_m1_h123 = 0;
			}

			if($laporan_sales_by_channel_target_oli_m->row()->h123 !=0){
				$ach_cost_oli_m_h123= round(($laporan_sales_by_channel_cost_price_oil_m->row()->h123 / $laporan_sales_by_channel_target_oli_m->row()->h123)*100);
			}else{
				$ach_cost_oli_m_h123 = 0;
			}

			if($laporan_sales_by_channel_cost_price_oil_m1->row()->h123 != 0){
				$growth_cost_oli_h123 = round((($laporan_sales_by_channel_cost_price_oil_m->row()->h123 / $laporan_sales_by_channel_cost_price_oil_m1->row()->h123)-1)*100);
			}else{
				$growth_cost_oli_h123 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">1</td>
		<td style="vertical-align : middle;text-align:center;">H123</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m1->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_oil_m1->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_cost_oli_m1_h123 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m->row()->h123, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_oil_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_cost_oli_m_h123;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_cost_oli_h123;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_cost_oli_m1_h23 = 0;
			$ach_cost_oli_m_h23 = 0;
			$growth_cost_oli_h23 = 0;
			if($laporan_sales_by_channel_target_oli_m1->row()->h23 !=0){
				$ach_cost_oli_m1_h23 = round(($laporan_sales_by_channel_cost_price_oil_m1->row()->h23 / $laporan_sales_by_channel_target_oli_m1->row()->h23)*100);
			}else{
				$ach_cost_oli_m1_h23 = 0;
			}

			if($laporan_sales_by_channel_target_oli_m->row()->h23 !=0){
				$ach_cost_oli_m_h23 = round(($laporan_sales_by_channel_cost_price_oil_m->row()->h23 / $laporan_sales_by_channel_target_oli_m->row()->h23)*100);
			}else{
				$ach_cost_oli_m_h23 = 0;
			}

			if($laporan_sales_by_channel_cost_price_oil_m1->row()->h23 != 0){
				$growth_cost_oli_h23 = round((($laporan_sales_by_channel_cost_price_oil_m->row()->h23 / $laporan_sales_by_channel_cost_price_oil_m1->row()->h23)-1)*100);
			}else{
				$growth_cost_oli_h23 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">2</td>
		<td style="vertical-align : middle;text-align:center;">H23</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m1->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_oil_m1->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_cost_oli_m1_h23 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_oil_m->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_cost_oli_m_h23;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_cost_oli_h23;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_cost_oli_m1_h3 = 0;
			$ach_cost_oli_m_h3 = 0;
			$growth_cost_oli_h3 = 0;
			if($laporan_sales_by_channel_target_oli_m1->row()->h3 !=0){
				$ach_cost_oli_m1_h3 = round(($laporan_sales_by_channel_cost_price_oil_m1->row()->h3 / $laporan_sales_by_channel_target_oli_m1->row()->h3)*100);
			}else{
				$ach_cost_oli_m1_h3 = 0;
			}

			if($laporan_sales_by_channel_target_oli_m->row()->h3 !=0){
				$ach_cost_oli_m_h3 = round(($laporan_sales_by_channel_cost_price_oil_m->row()->h3 / $laporan_sales_by_channel_target_oli_m->row()->h3)*100);
			}else{
				$ach_cost_oli_m_h3 = 0;
			}

			if($laporan_sales_by_channel_cost_price_oil_m1->row()->h3 != 0){
				$growth_cost_oli_h3 = round((($laporan_sales_by_channel_cost_price_oil_m->row()->h3 / $laporan_sales_by_channel_cost_price_oil_m1->row()->h3)-1)*100);
			}else{
				$growth_cost_oli_h3 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">3</td>
		<td style="vertical-align : middle;text-align:center;">H3</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m1->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_oil_m1->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_cost_oli_m1_h3 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_oil_m->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_cost_oli_m_h3;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_cost_oli_h3;?> %</td>
	</tr>
	<tr>
		<?php 
			$total_cost_price_target_m1 = 0; 
			$total_cost_price_aktual_m1 = 0; 
			$total_cost_price_ach_m1 = 0; 
			$total_cost_price_target_m = 0; 
			$total_cost_price_aktual_m = 0; 
			$total_cost_price_ach_m = 0; 
			$total_cost_price_growth = 0; 

			$total_cost_price_target_m1 = ($laporan_sales_by_channel_target_oli_m1->row()->h123+$laporan_sales_by_channel_target_oli_m1->row()->h23+$laporan_sales_by_channel_target_oli_m1->row()->h3);
			$total_cost_price_aktual_m1 = ($laporan_sales_by_channel_cost_price_oil_m1->row()->h123+$laporan_sales_by_channel_cost_price_oil_m1->row()->h23+$laporan_sales_by_channel_cost_price_oil_m1->row()->h3);

			if($total_cost_price_target_m1 != 0){
				$total_cost_price_ach_m1 = round(($total_cost_price_aktual_m1 / $total_cost_price_target_m1)*100); 
			}else{
				$total_cost_price_ach_m1 = 0;
			}


			$total_cost_price_target_m = ($laporan_sales_by_channel_target_oli_m->row()->h123+$laporan_sales_by_channel_target_oli_m->row()->h23+$laporan_sales_by_channel_target_oli_m->row()->h3);
			$total_cost_price_aktual_m = ($laporan_sales_by_channel_cost_price_oil_m->row()->h123+$laporan_sales_by_channel_cost_price_oil_m->row()->h23+$laporan_sales_by_channel_cost_price_oil_m->row()->h3);

			if($total_cost_price_target_m != 0){
				$total_cost_price_ach_m =  round(($total_cost_price_aktual_m / $total_cost_price_target_m)*100); 
			}else{
				$total_cost_price_ach_m = 0;
			}

			if($total_cost_price_aktual_m1 != 0){
				$total_cost_price_growth =   round((($total_cost_price_aktual_m / $total_cost_price_aktual_m1)-1)*100);
			}else{
				$total_cost_price_growth = 0;
			}

		?>
		<td style="vertical-align : middle;text-align:center;" colspan="2"><b>Total</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_target_m1, 0, ',', '.') ; ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_aktual_m1, 0, ',', '.') ;?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_cost_price_ach_m1;?> %</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_target_m, 0, ',', '.') ; ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_aktual_m, 0, ',', '.') ;?></b></td>	
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_cost_price_ach_m;?> %</b></td>	
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_cost_price_growth;?> %</b></td>
	</tr>
</table>

<br>
<br>
<br>

<!-- Selling Price Oil-->
<table border="1">
	<tr>
		<td colspan="9" style="vertical-align : middle;text-align:left;"><b>Selling Price Oil</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Status Customer </b></td>
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
	<tr>
		<?php 
			$ach_oli_selling_m1_h123 = 0;
			$ach_oli_selling_m_h123 = 0;
			$growth_selling_oli_h123 = 0;
			if($laporan_sales_by_channel_target_oli_m1->row()->h123 !=0){
				$ach_oli_selling_m1_h123 = round(($laporan_sales_by_channel_selling_price_oil_m1->row()->h123 / $laporan_sales_by_channel_target_oli_m1->row()->h123)*100);
			}else{
				$ach_oli_selling_m1_h123 = 0;
			}

			if($laporan_sales_by_channel_target_oli_m->row()->h123 !=0){
				$ach_oli_selling_m_h123 = round(($laporan_sales_by_channel_selling_price_oil_m->row()->h123 / $laporan_sales_by_channel_target_oli_m->row()->h123)*100);
			}else{
				$ach_oli_selling_m_h123 = 0;
			}

			if($laporan_sales_by_channel_selling_price_oil_m1->row()->h123 !=0){
				$growth_selling_oli_h123 = round((($laporan_sales_by_channel_selling_price_oil_m->row()->h123 / $laporan_sales_by_channel_selling_price_oil_m1->row()->h123)-1)*100);
			}else{
				$growth_selling_oli_h123 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">1</td>
		<td style="vertical-align : middle;text-align:center;">H123</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m1->row()->h123, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_oil_m1->row()->h123, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_oli_selling_m1_h123 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_oil_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_oli_selling_m_h123;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_selling_oli_h123;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_oli_selling_m1_h23 = 0;
			$ach_oli_selling_m_h23 = 0;
			$growth_selling_oli_h23 = 0;
			if($laporan_sales_by_channel_target_oli_m1->row()->h23 !=0){
				$ach_oli_selling_m1_h23 = round(($laporan_sales_by_channel_selling_price_oil_m1->row()->h23 / $laporan_sales_by_channel_target_oli_m1->row()->h23)*100);
			}else{
				$ach_oli_selling_m1_h23 = 0;
			}

			if($laporan_sales_by_channel_target_oli_m->row()->h23 !=0){
				$ach_oli_selling_m_h23 = round(($laporan_sales_by_channel_selling_price_oil_m->row()->h23 / $laporan_sales_by_channel_target_oli_m->row()->h23)*100);
			}else{
				$ach_oli_selling_m_h23 = 0;
			}

			if($laporan_sales_by_channel_selling_price_oil_m1->row()->h23 !=0){
				$growth_selling_oli_h23 = round((($laporan_sales_by_channel_selling_price_oil_m->row()->h23 / $laporan_sales_by_channel_selling_price_oil_m1->row()->h23)-1)*100);
			}else{
				$growth_selling_oli_h23 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">2</td>
		<td style="vertical-align : middle;text-align:center;">H23</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m1->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_oil_m1->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_oli_selling_m1_h23 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_oil_m->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_oli_selling_m_h23;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_selling_oli_h23;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_oli_selling_m1_h3 = 0;
			$ach_oli_selling_m_h3 = 0;
			$growth_selling_oli_h3 = 0;
			if($laporan_sales_by_channel_target_oli_m1->row()->h3 !=0){
				$ach_oli_selling_m1_h3 = round(($laporan_sales_by_channel_selling_price_oil_m1->row()->h3 / $laporan_sales_by_channel_target_oli_m1->row()->h3)*100);
			}else{
				$ach_oli_selling_m1_h3 = 0;
			}

			if($laporan_sales_by_channel_target_oli_m->row()->h3 !=0){
				$ach_oli_selling_m_h3 = round(($laporan_sales_by_channel_selling_price_oil_m->row()->h3 / $laporan_sales_by_channel_target_oli_m->row()->h3)*100);
			}else{
				$ach_oli_selling_m_h3 = 0;
			}

			if($laporan_sales_by_channel_selling_price_oil_m1->row()->h3 !=0){
				$growth_selling_oli_h3 = round((($laporan_sales_by_channel_selling_price_oil_m->row()->h3 / $laporan_sales_by_channel_selling_price_oil_m1->row()->h3)-1)*100);
			}else{
				$growth_selling_oli_h3 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">3</td>
		<td style="vertical-align : middle;text-align:center;">H3</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m1->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_oil_m1->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_oli_selling_m1_h3 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_oli_m->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_oil_m->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_oli_selling_m_h3;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_selling_oli_h3;?> %</td>
	</tr>
	<tr>
		<?php 
			$total_selling_price_target_m1 = 0; 
			$total_selling_price_aktual_m1 = 0; 
			$total_selling_price_target_m = 0; 
			$total_selling_price_aktual_m = 0; 

			$total_selling_price_ach_m1 = 0; 
			$total_selling_price_ach_m = 0; 
			$total_selling_price_growth = 0;

			$total_selling_price_target_m1 = ($laporan_sales_by_channel_target_oli_m1->row()->h123+$laporan_sales_by_channel_target_oli_m1->row()->h23+$laporan_sales_by_channel_target_oli_m1->row()->h3);
			$total_selling_price_aktual_m1 = ($laporan_sales_by_channel_selling_price_oil_m1->row()->h123+$laporan_sales_by_channel_selling_price_oil_m1->row()->h23+$laporan_sales_by_channel_selling_price_oil_m1->row()->h3);

			if($total_selling_price_target_m1 != 0){
				$total_selling_price_ach_m1 = round(($total_selling_price_aktual_m1 / $total_selling_price_target_m1)*100);  
			}else{
				$total_selling_price_target_m1 = 0;
			}

			$total_selling_price_target_m = ($laporan_sales_by_channel_target_oli_m->row()->h123+$laporan_sales_by_channel_target_oli_m->row()->h23+$laporan_sales_by_channel_target_oli_m->row()->h3);
			$total_selling_price_aktual_m = ($laporan_sales_by_channel_selling_price_oil_m->row()->h123+$laporan_sales_by_channel_selling_price_oil_m->row()->h23+$laporan_sales_by_channel_selling_price_oil_m->row()->h3);
			
			if($total_selling_price_target_m != 0){
				$total_selling_price_ach_m =  round(($total_selling_price_aktual_m / $total_selling_price_target_m)*100); 
			}else{
				$total_selling_price_ach_m = 0;
			}
			
			if($total_selling_price_aktual_m1 != 0){
				$total_selling_price_growth =   round((($total_selling_price_aktual_m / $total_selling_price_aktual_m1)-1)*100);
			}else{
				$total_selling_price_growth = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;" colspan="2"><b>Total</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_target_m1, 0, ',', '.'); ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_aktual_m1, 0, ',', '.') ;?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_selling_price_ach_m1;?> %</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_target_m, 0, ',', '.') ; ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_aktual_m, 0, ',', '.') ;?></b></td>	
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_selling_price_ach_m;?> %</b></td>	
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_selling_price_growth;?> %</b></td>	
	</tr>
</table>

<br>
<br>
<br>

<!-- Cost Price Parts-->
<table border="1">
	<tr>
		<td colspan="9" style="vertical-align : middle;text-align:left;"><b>Cost Price Parts</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Status Customer </b></td>
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
	<tr>
		<?php 
			$ach_cost_part_m1_h123 = 0;
			$ach_cost_part_m_h123 = 0;
			$growth_cost_part_h123 = 0;
			if($laporan_sales_by_channel_target_part_m1->row()->h123 !=0){
				$ach_cost_part_m1_h123 = round(($laporan_sales_by_channel_cost_price_part_m1->row()->h123 / $laporan_sales_by_channel_target_part_m1->row()->h123)*100);
			}else{
				$ach_cost_part_m1_h123 = 0;
			}

			if($laporan_sales_by_channel_target_part_m->row()->h123 !=0){
				$ach_cost_part_m_h123 = round(($laporan_sales_by_channel_cost_price_part_m->row()->h123 / $laporan_sales_by_channel_target_part_m->row()->h123)*100);
			}else{
				$ach_cost_part_m_h123 = 0;
			}

			if($laporan_sales_by_channel_cost_price_part_m1->row()->h123 != 0){
				$growth_cost_part_h123 = round((($laporan_sales_by_channel_cost_price_part_m->row()->h123 / $laporan_sales_by_channel_cost_price_part_m1->row()->h123)-1)*100);
			}else{
				$growth_cost_part_h123 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">1</td>
		<td style="vertical-align : middle;text-align:center;">H123</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m1->row()->h123, 0, ',', '.')  ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_part_m1->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_cost_part_m1_h123 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  number_format($laporan_sales_by_channel_target_part_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  number_format($laporan_sales_by_channel_cost_price_part_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_cost_part_m_h123;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_cost_part_h123;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_cost_part_m1_h23 = 0;
			$ach_cost_part_m_h23 = 0;
			$growth_cost_part_h23 = 0;
			if($laporan_sales_by_channel_target_part_m1->row()->h23 !=0){
				$ach_cost_part_m1_h23 = round(($laporan_sales_by_channel_cost_price_part_m1->row()->h23 / $laporan_sales_by_channel_target_part_m1->row()->h23)*100);
			}else{
				$ach_cost_part_m1_h23 = 0;
			}

			if($laporan_sales_by_channel_target_part_m->row()->h23 !=0){
				$ach_cost_part_m_h23 = round(($laporan_sales_by_channel_cost_price_part_m->row()->h23 / $laporan_sales_by_channel_target_part_m->row()->h23)*100);
			}else{
				$ach_cost_part_m_h23 = 0;
			}

			if($laporan_sales_by_channel_cost_price_part_m1->row()->h23 != 0){
				$growth_cost_part_h23 = round((($laporan_sales_by_channel_cost_price_part_m->row()->h23 / $laporan_sales_by_channel_cost_price_part_m1->row()->h23)-1)*100);
			}else{
				$growth_cost_part_h23 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">2</td>
		<td style="vertical-align : middle;text-align:center;">H23</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m1->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_part_m1->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_cost_part_m1_h23 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_part_m->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_cost_part_m_h23;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_cost_part_h23;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_cost_part_m1_h3 = 0;
			$ach_cost_part_m_h3 = 0;
			$growth_cost_part_h3 = 0;
			if($laporan_sales_by_channel_target_part_m1->row()->h3 !=0){
				$ach_cost_part_m1_h3 = round(($laporan_sales_by_channel_cost_price_part_m1->row()->h3 / $laporan_sales_by_channel_target_part_m1->row()->h3)*100);
			}else{
				$ach_cost_part_m1_h3 = 0;
			}

			if($laporan_sales_by_channel_target_part_m->row()->h3 !=0){
				$ach_cost_part_m_h3 = round(($laporan_sales_by_channel_cost_price_part_m->row()->h3 / $laporan_sales_by_channel_target_part_m->row()->h3)*100);
			}else{
				$ach_cost_part_m_h3 = 0;
			}

			if($laporan_sales_by_channel_cost_price_part_m1->row()->h3 != 0){
				$growth_cost_part_h3 = round((($laporan_sales_by_channel_cost_price_part_m->row()->h3 / $laporan_sales_by_channel_cost_price_part_m1->row()->h3)-1)*100);
			}else{
				$growth_cost_part_h3 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">3</td>
		<td style="vertical-align : middle;text-align:center;">H3</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m1->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_part_m1->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_cost_part_m1_h3 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_cost_price_part_m->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_cost_part_m_h3;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_cost_part_h3;?> %</td>
	</tr>
	<tr>
		<?php 
			$total_cost_price_part_target_m1 = 0; 
			$total_cost_price_part_aktual_m1 = 0; 
			$total_cost_price_part_target_m = 0; 
			$total_cost_price_part_aktual_m = 0; 
			
			$total_cost_price_part_ach_m1 = 0; 
			$total_cost_price_part_ach_m = 0; 
			$total_cost_price_part_growth = 0; 

			$total_cost_price_part_target_m1 = ($laporan_sales_by_channel_target_part_m1->row()->h123+$laporan_sales_by_channel_target_part_m1->row()->h23+$laporan_sales_by_channel_target_part_m1->row()->h3);
			$total_cost_price_part_aktual_m1 = ($laporan_sales_by_channel_cost_price_part_m1->row()->h123+$laporan_sales_by_channel_cost_price_part_m1->row()->h23+$laporan_sales_by_channel_cost_price_part_m1->row()->h3);

			if($total_cost_price_part_target_m1 != 0){
				$total_cost_price_part_ach_m1 = round(($total_cost_price_part_aktual_m1 / $total_cost_price_part_target_m1)*100); 
			}else{
				$total_cost_price_part_ach_m1 = 0;
			}

			$total_cost_price_part_target_m = ($laporan_sales_by_channel_target_part_m->row()->h123+$laporan_sales_by_channel_target_part_m->row()->h23+$laporan_sales_by_channel_target_part_m->row()->h3);
			$total_cost_price_part_aktual_m = ($laporan_sales_by_channel_cost_price_part_m->row()->h123+$laporan_sales_by_channel_cost_price_part_m->row()->h23+$laporan_sales_by_channel_cost_price_part_m->row()->h3);

			if($total_cost_price_part_target_m != 0){
				$total_cost_price_part_ach_m = round(($total_cost_price_part_aktual_m / $total_cost_price_part_target_m)*100); 
			}else{
				$total_cost_price_part_ach_m = 0;
			}

			if($total_cost_price_part_aktual_m1 != 0){
				$total_cost_price_part_growth =   round((($total_cost_price_part_aktual_m / $total_cost_price_part_aktual_m1)-1)*100); 
			}else{
				$total_cost_price_part_growth = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;" colspan="2"><b>Total</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_part_target_m1, 0, ',', '.') ; ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_part_aktual_m1, 0, ',', '.');?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_cost_price_part_ach_m1;?> %</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_part_target_m, 0, ',', '.'); ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_cost_price_part_aktual_m, 0, ',', '.');?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_cost_price_part_ach_m;?> %</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_cost_price_part_growth;?> %</b></td>		
	</tr>
</table>

<br>
<br>
<br>

<!-- Selling Price Parts-->
<table border="1">
	<tr>
		<td colspan="9" style="vertical-align : middle;text-align:left;"><b>Selling Price Parts</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Status Customer </b></td>
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
	<tr>
		<?php 
			$ach_part_selling_m1_h123 = 0;
			$ach_part_selling_m_h123 = 0;
			$growth_selling_part_h123 = 0;
			if($laporan_sales_by_channel_target_part_m1->row()->h123 !=0){
				$ach_part_selling_m1_h123 = round(($laporan_sales_by_channel_selling_price_part_m1->row()->h123 / $laporan_sales_by_channel_target_part_m1->row()->h123)*100);
			}else{
				$ach_part_selling_m1_h123 = 0;
			}

			if($laporan_sales_by_channel_target_part_m->row()->h123 !=0){
				$ach_part_selling_m_h123 = round(($laporan_sales_by_channel_selling_price_part_m->row()->h123 / $laporan_sales_by_channel_target_part_m->row()->h123)*100);
			}else{
				$ach_part_selling_m_h123 = 0;
			}

			if($laporan_sales_by_channel_selling_price_part_m1->row()->h123 !=0){
				$growth_selling_part_h123 = round((($laporan_sales_by_channel_selling_price_part_m->row()->h123 / $laporan_sales_by_channel_selling_price_part_m1->row()->h123)-1)*100);
			}else{
				$growth_selling_part_h123 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">1</td>
		<td style="vertical-align : middle;text-align:center;">H123</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m1->row()->h123, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_part_m1->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_part_selling_m1_h123 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_part_m->row()->h123, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_part_selling_m_h123;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_selling_part_h123;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_part_selling_m1_h23 = 0;
			$ach_part_selling_m_h23 = 0;
			$growth_selling_part_h23 = 0;
			if($laporan_sales_by_channel_target_part_m1->row()->h23 !=0){
				$ach_part_selling_m1_h23 = round(($laporan_sales_by_channel_selling_price_part_m1->row()->h23 / $laporan_sales_by_channel_target_part_m1->row()->h23)*100);
			}else{
				$ach_part_selling_m1_h23 = 0;
			}

			if($laporan_sales_by_channel_target_part_m->row()->h23 !=0){
				$ach_part_selling_m_h23 = round(($laporan_sales_by_channel_selling_price_part_m->row()->h23 / $laporan_sales_by_channel_target_part_m->row()->h23)*100);
			}else{
				$ach_oli_selling_m_h23 = 0;
			}

			if($laporan_sales_by_channel_selling_price_part_m1->row()->h23 !=0){
				$growth_selling_part_h23 = round((($laporan_sales_by_channel_selling_price_part_m->row()->h23 / $laporan_sales_by_channel_selling_price_part_m1->row()->h23)-1)*100);
			}else{
				$growth_selling_part_h23 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">2</td>
		<td style="vertical-align : middle;text-align:center;">H23</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m1->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_part_m1->row()->h23, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_part_selling_m1_h23 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  number_format($laporan_sales_by_channel_target_part_m->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_part_m->row()->h23, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_part_selling_m_h23;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_selling_part_h23;?> %</td>
	</tr>
	<tr>
		<?php 
			$ach_part_selling_m1_h3 = 0;
			$ach_part_selling_m_h3 = 0;
			$growth_selling_part_h3 = 0;
			if($laporan_sales_by_channel_target_part_m1->row()->h3 !=0){
				$ach_part_selling_m1_h3 = round(($laporan_sales_by_channel_selling_price_part_m1->row()->h3 / $laporan_sales_by_channel_target_part_m1->row()->h3)*100);
			}else{
				$ach_part_selling_m1_h3 = 0;
			}

			if($laporan_sales_by_channel_target_part_m->row()->h3 !=0){
				$ach_part_selling_m_h3 = round(($laporan_sales_by_channel_selling_price_part_m->row()->h3 / $laporan_sales_by_channel_target_part_m->row()->h3)*100);
			}else{
				$ach_part_selling_m_h3 = 0;
			}

			if($laporan_sales_by_channel_selling_price_part_m1->row()->h3 !=0){
				$growth_selling_part_h3 = round((($laporan_sales_by_channel_selling_price_part_m->row()->h3 / $laporan_sales_by_channel_selling_price_part_m1->row()->h3)-1)*100);
			}else{
				$growth_selling_part_h3 = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;">3</td>
		<td style="vertical-align : middle;text-align:center;">H3</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m1->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_part_m1->row()->h3, 0, ',', '.');?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo  $ach_part_selling_m1_h3 ?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_target_part_m->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo number_format($laporan_sales_by_channel_selling_price_part_m->row()->h3, 0, ',', '.') ;?></td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $ach_part_selling_m_h3;?> %</td>
		<td style="vertical-align : middle;text-align:center;"><?php echo $growth_selling_part_h3;?> %</td>
	</tr>
	<tr>
		<?php 
			$total_selling_price_part_target_m1 = 0; 
			$total_selling_price_part_aktual_m1 = 0; 
			$total_selling_price_part_target_m = 0; 
			$total_selling_price_part_aktual_m = 0; 

			$total_selling_price_part_ach_m1 = 0; 
			$total_selling_price_part_ach_m = 0; 
			$total_selling_price_part_growth = 0; 

			$total_selling_price_part_target_m1 = ($laporan_sales_by_channel_target_part_m1->row()->h123+$laporan_sales_by_channel_target_part_m1->row()->h23+$laporan_sales_by_channel_target_part_m1->row()->h3);
			$total_selling_price_part_aktual_m1 = ($laporan_sales_by_channel_selling_price_part_m1->row()->h123+$laporan_sales_by_channel_selling_price_part_m1->row()->h23+$laporan_sales_by_channel_selling_price_part_m1->row()->h3);

			if($total_selling_price_part_target_m1 != 0){
				$total_selling_price_part_ach_m1 = round(($total_selling_price_part_aktual_m1 / $total_selling_price_part_target_m1)*100); 
			}else{
				$total_selling_price_part_ach_m1 = 0;
			}


			$total_selling_price_part_target_m = ($laporan_sales_by_channel_target_part_m->row()->h123+$laporan_sales_by_channel_target_part_m->row()->h23+$laporan_sales_by_channel_target_part_m->row()->h3);
			$total_selling_price_part_aktual_m = ($laporan_sales_by_channel_selling_price_part_m->row()->h123+$laporan_sales_by_channel_selling_price_part_m->row()->h23+$laporan_sales_by_channel_selling_price_part_m->row()->h3);

			if($total_selling_price_part_target_m != 0){
				$total_selling_price_part_ach_m = round(($total_selling_price_part_aktual_m / $total_selling_price_part_target_m)*100); 
			}else{
				$total_selling_price_part_ach_m = 0;
			}


			if($total_selling_price_part_aktual_m1 != 0){
				$total_selling_price_part_growth =   round((($total_selling_price_part_aktual_m / $total_selling_price_part_aktual_m1)-1)*100);
			}else{
				$total_selling_price_part_growth = 0;
			}
		?>
		<td style="vertical-align : middle;text-align:center;" colspan="2"><b>Total</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_part_target_m1, 0, ',', '.') ; ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_part_aktual_m1, 0, ',', '.');?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_selling_price_ach_m1;?> %</b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_part_target_m, 0, ',', '.') ; ?></b></td>
		<td style="vertical-align : middle;text-align:center;"><b><?php echo number_format($total_selling_price_part_aktual_m, 0, ',', '.') ;?></b></td>	
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_selling_price_ach_m;?> %</b></td>	
		<td style="vertical-align : middle;text-align:center;"><b><?php echo $total_selling_price_part_growth;?> %</b></td>
	</tr>
</table>

