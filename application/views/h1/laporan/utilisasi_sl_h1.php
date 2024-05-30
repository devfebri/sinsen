<?php
ini_set('date.timezone', 'Asia/Jakarta');
$date = date("dmY-Hi");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=util_shipping-$date.xls");
?>

<!DOCTYPE html>
<html lang="en">

<style>
	th {
		font-weight: normal;
	}

	#width {
		width: 75px;
	}

	.width_dealer{
		width: 280px;
	}

</style>

<body>
	<table id="tableID" class="display" border="1" align="center">
		<thead>
			<tr align="center">
				<th colspan="33" style="background-color: #c5e374;">Utilisasi Shipping List Unit Periode <?php echo tgl_indo($start_date) ." s/d ". tgl_indo($end_date) .' - '. date('H:i') . ' WIB';  ?></th>
			</tr>
			<tr>
				<th style="background-color: #9bc6ec;">Kode Dealer</th>
				<?php 						
					for($i=1; $i<=31 ; $i++){
							echo '<th style="background-color: #9bc6ec; width:20px;">'.$i.'</th>';
					}
				?>
				<th style="background-color: #9bc6ec; width:50px;">Total</th>
			</tr>
		</thead>
		<thead>
			<?php
			
			$sort_dealer = [
				'05529','06935','13621','17338','13387','13759','07628','12203','12598','13381','13385','00675','01925','03530','04692','07465',
				'07717','13386','06111','07781','09673','00758','01354','04730','04576','07464','12142','08549','03538','05391','05545','07720',
				'06112','05621','07780','12143','12144','12797','13388','13384','13382','00888','01118','03540','03573','05399','07719','11791'
				];

			foreach($sort_dealer as $key => $kode_dealer){
				$count = 0;
				$list_tgl = $list_data[$kode_dealer];	
				$tgl = explode(',',$list_tgl);
			?>	
				<tr>
					<td>'<?php echo $kode_dealer ?> </td>
					<?php 						
						for($i=1; $i<=31 ; $i++){
							if(in_array($i,$tgl)){
								echo '<td>1</td>';
								$count++;
							}else{
								echo '<td></td>';
							}
						}
					?>
					<td><?php echo $count?> </td>
				</tr>
			<?php
			}
			?>
		</thead>
	</table>
</body>




