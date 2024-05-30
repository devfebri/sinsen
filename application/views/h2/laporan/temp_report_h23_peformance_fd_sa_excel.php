<?php 
$no = date('dmY_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Peformance_FD_SA_MD_".$no." WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>


<table border="1">  
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>No WO/ PKB</b></td>
 		<td align="center"><b>No NJB</b></td>
 		<td align="center"><b>Nama SA</b></td>
 		<td align="center"><b>No NSC</b></td>
 		<td align="center"><b>Total Jasa (Rp)</b></td>
		<td align="center"><b>Grand Total Part (Rp)</b></td>
		<td align="center"><b>Total Part (Rp)</b></td>
		<td align="center"><b>Total Oli (Rp)</b></td>
 		<td align="center"><b>Tgl Create PKB</b></td>
		<td align="center"><b>Tgl Close PKB</b></td>
		<td align="center"><b>Nama Frontdesk (NJB)</b></td>
 		<td align="center"><b>Tgl Create NJB</b></td>
		<td align="center"><b>Tgl Cetak NJB</b></td>
		<td align="center"><b>Cetak NJB (menit)</b></td>
		<td align="center"><b>Nama Frontdesk (NSC)</b></td>
		<td align="center"><b>Tgl Create NSC</b></td>
		<td align="center"><b>Tgl Cetak NSC</b></td>
		<td align="center"><b>Cetak NSC (menit)</b></td>
 	</tr>
	<?php 
		$i=0;
		$data = array();

		foreach($peformance_fd_sa->result() as $row){
			$data['sa'][$row->nama_sa]['nama_sa'] = $row->nama_sa;
			$data['sa'][$row->nama_sa]['jumlah_ue'] += 1;
			$data['sa'][$row->nama_sa]['total_jasa'] += $row->total_jasa;
			$data['sa'][$row->nama_sa]['total_part'] += $row->total_part;
			$data['sa'][$row->nama_sa]['total_nsc_part'] += $row->total_nsc_part;
			$data['sa'][$row->nama_sa]['total_nsc_oli'] += $row->total_nsc_oli;
	
			$to_time = strtotime($row->cetak_njb);
			$from_time = strtotime($row->created_njb_at);
			$time_njb = round(abs($to_time - $from_time) / 60,2);

			$to_time = strtotime($row->cetak_nsc);
			$from_time = strtotime($row->create_nsc);
			$time_nsc = round(abs($to_time - $from_time) / 60,2);

			// if($row->nama_njb == $row->nama_nsc && $row->nama_njb !='' && $row->nama_nsc !=''){
				$data['fd'][$row->nama_njb]['nama_fd'] = $row->nama_njb;
				$data['fd'][$row->nama_njb]['jumlah_ue'] += 1;
				$data['fd'][$row->nama_njb]['time_njb'] += $time_njb;
				$data['fd'][$row->nama_nsc]['time_nsc'] += $time_nsc;
			// }else if($row->nama_njb != $row->nama_nsc && $row->nama_njb !='' $row->nama_nsc !=''){
				// jika nama create njb dan nsc berbeda
			// 	$data['fd'][$row->nama_njb]['nama_fd'] = $row->nama_njb;
			// 	$data['fd'][$row->nama_nsc]['nama_fd'] = $row->nama_nsc;
			// 	// $data['fd'][$row->nama_nsc]['jumlah_ue'] += 1;
			// 	$data['fd'][$row->nama_nsc]['time_njb'] += $time_njb;
			// 	$data['fd'][$row->nama_nsc]['time_nsc'] += $time_nsc;
			// }
			$i++;
	?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $row->id_work_order; ?></td>
			<td><?php echo $row->no_njb; ?></td>
			<td><?php echo $row->nama_sa; ?></td>
			<td><?php echo $row->no_nsc; ?></td>
			<td><?php echo $row->total_jasa; ?></td>
			<td><?php echo $row->total_part; ?></td>
			<td><?php echo $row->total_nsc_part; ?></td>
			<td><?php echo $row->total_nsc_oli; ?></td>
			<td><?php echo $row->create_wo; ?></td>
			<td><?php echo $row->close_wo; ?></td>
			<td><?php echo $row->nama_njb; ?></td>
			<td><?php echo $row->created_njb_at; ?></td>
			<td><?php echo $row->cetak_njb; ?></td>
			<td><?php if($time_njb > 5){echo '<b>';} echo $time_njb; if($time_njb > 5){echo '</b>';} ?></td>
			<td><?php echo $row->nama_nsc; ?></td>
			<td><?php echo $row->create_nsc; ?></td>
			<td><?php echo $row->cetak_nsc; ?></td>
			<td><?php if($time_nsc > 5){echo '<b>';} echo $time_nsc; if($time_njb > 5){echo '</b>';} ?></td>
		</tr>
	<?php
		} 
	?>
</table>

<br>
<br>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Nama SA</b></td>
		<td align="center"><b>Jumlah PKB (UE TOJ 1:1)</b></td>
		<td align="center"><b>Jumlah Jasa (Rp)</b></td>
		<td align="center"><b>Jumlah Part dan Oli (Rp)</b></td>
		<td align="center"><b>Jumlah Part (Rp)</b></td>
		<td align="center"><b>Jumlah Oli (Rp)</b></td>
 	</tr>
	<?php 
		$i=0;
		$total_ue = 0;
		$total_jasa = 0;
		$total_part = 0;
		$total_nsc_part = 0;
		$total_nsc_oli = 0;
		foreach($data['sa'] as $row){
			$total_ue += $row['jumlah_ue'];
			$total_jasa += $row['total_jasa'];
			$total_part += $row['total_part'];
			$total_nsc_part += $row['total_nsc_part'];
			$total_nsc_oli += $row['total_nsc_oli'];
			$i++;
	?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $row['nama_sa']; ?></td>
			<td><?php echo $row['jumlah_ue']; ?></td>
			<td><?php echo $row['total_jasa']; ?></td>
			<td><?php echo $row['total_part']; ?></td>
			<td><?php echo $row['total_nsc_part']; ?></td>
			<td><?php echo $row['total_nsc_oli']; ?></td>
		</tr>
	<?php
		} 
	?>

	<tr> 		
 		<td colspan="2"><b>Total</b></td>
		<td><b><?php echo $total_ue; ?></b></td>
		<td align="right"><b><?php echo number_format($total_jasa,0); ?></b></td>
		<td align="right"><b><?php echo number_format($total_part,0); ?></b></td>
		<td align="right"><b><?php echo number_format($total_nsc_part,0); ?></b></td>
		<td align="right"><b><?php echo number_format($total_nsc_oli,0); ?></b></td>
 	</tr>
</table>


<br>
<br>

<table border="1">  
 	<tr> 		
 		<td align="center"><b>No</b></td>
 		<td align="center"><b>Nama Frontdesk</b></td>
		<td align="center"><b>Jumlah UE</b></td>
		<td align="center"><b>Jumlah Time NJB (menit)</b></td>
		<td align="center"><b>AVG Time NJB (menit)</b></td>
		<td align="center"><b>Jumlah Time NSC (menit)</b></td>
		<td align="center"><b>AVG Time NSC (menit)</b></td>
 	</tr>
	<?php 
		$i=0;
		$total_ue = 0;
		foreach($data['fd'] as $row){
			$total_ue += $row['jumlah_ue'];
			$i++;
	?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $row['nama_fd']; ?></td>
			<td><?php echo $row['jumlah_ue']; ?></td>
			<td align="right"><?php echo $row['time_njb']; ?></td>
			<td align="right"><?php echo round(($row['time_njb']/$row['jumlah_ue']),2); ?></td>
			<td align="right"><?php echo $row['time_nsc']; ?></td>
			<td align="right"><?php echo round(($row['time_nsc']/$row['jumlah_ue']),2); ?></td>
		</tr>
	<?php
		} 
	?>
	<tr> 		
 		<td colspan="2"><b>Total</b></td>
		<td align="right"><b><?php echo $total_ue; ?></b></td>
 	</tr>
</table>