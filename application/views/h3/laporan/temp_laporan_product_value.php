<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Product Value_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
		$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Laporan Product Value
				<br> <?php echo $laporan_product_value->row()->nama_dealer ?> </b> <br><br></caption>
	<?php } else { ?>
		<caption><b>Laporan Product Value
				<br> Periode <?php echo $start_date_2 . " s/d " . $end_date_2 ?>
			</b><br><br></caption>
	<?php } ?>



	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Nama Customer </b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Status</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Kabupaten</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Salesman</b></td>
		<?php
		$bulan = '';
		$start_date_bulan = date("m", strtotime($start_date));
		$end_date_bulan = date("m", strtotime($end_date));

		if ($start_date_bulan == $end_date_bulan) {
			setlocale(LC_TIME, 'id_ID');
			$bulan = date('F-y', strtotime($start_date));
		} else {
			$bulan = $start_date_2 . " s/d " . $end_date_2;
		}
		?>
		<td align="center" colspan="11"><b><?php echo $bulan ?></b></td>
		<td align="center" colspan="10"><b>Stock Terakhir pada saat Penarikan Data (Pcs)</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;"><b>UE</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>OIL </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>GMO</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>HPC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>HIC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>HBF</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ACG</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ACL</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>OSC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>CC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>TBC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>OIL </b></td>
		<td style="vertical-align : middle;text-align:center;"><b>GMO</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>HPC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>HIC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>HBF</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ACG</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>ACL</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>OSC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>CC</b></td>
		<td style="vertical-align : middle;text-align:center;"><b>TBC</b></td>
	</tr>
	<?php
	$nom = 1;
	$sum_ue = 0;
	$sum_oil = 0;
	$sum_gmo = 0;
	$sum_hpc = 0;
	$sum_hic = 0;
	$sum_hbf = 0;
	$sum_acg = 0;
	$sum_acl = 0;
	$sum_osc = 0;
	$sum_cc = 0;
	$sum_tbc = 0;
	$sum_stock_oil = 0;
	$sum_stock_gmo = 0;
	$sum_stock_hpc = 0;
	$sum_stock_hic = 0;
	$sum_stock_hbf = 0;
	$sum_stock_acg = 0;
	$sum_stock_acl = 0;
	$sum_stock_osc = 0;
	$sum_stock_cc = 0;
	$sum_stock_tbc = 0;
	if ($laporan_product_value->num_rows() > 0) {
		foreach ($laporan_product_value->result() as $row) {
			$ue = $this->db->query("SELECT ifnull(count(sa.id_type),0) as ue
			FROM tr_h2_sa_form sa
			JOIN tr_h2_wo_dealer wo on sa.id_sa_form=wo.id_sa_form 
			WHERE sa.id_dealer='$row->id_dealer' and wo.status='Closed' and left(wo.closed_at,10) >= '$start_date' and left(wo.closed_at,10) <= '$end_date'")->row();
			
			$oil = $this->db->query("SELECT ifnull(sum(ds.stock),0) as oil
			FROM ms_h3_dealer_stock ds
			JOIN ms_part mp on mp.id_part_int = ds.id_part_int
			WHERE ds.id_dealer='$row->id_dealer' and mp.kelompok_part = 'OIL'
			GROUP BY ds.id_dealer")->row();

			if($oil->oil!=0){
				$oil = $oil->oil;
			}else{
				$oil = 0;
			}

			$gmo = $this->db->query("SELECT ifnull(sum(ds.stock),0) as gmo
			FROM ms_h3_dealer_stock ds
			JOIN ms_part mp on mp.id_part_int = ds.id_part_int
			WHERE ds.id_dealer='$row->id_dealer' and mp.kelompok_part = 'GMO'
			GROUP BY ds.id_dealer")->row();
			
			if($gmo->gmo!=0){
				$gmo = $gmo->gmo;
			}else{
				$gmo = 0;
			}

			$osc = $this->db->query("SELECT ifnull(sum(ds.stock),0) as osc
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='HPC480ML' 
			GROUP BY ds.id_dealer")->row();

			if($osc->osc!=0){
				$osc = $osc->osc;
			}else{
				$osc = 0;
			}

			$hic = $this->db->query("SELECT ifnull(sum(ds.stock),0) as hic
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='HIC60ML' 
			GROUP BY ds.id_dealer")->row();

			if($hic->hic!=0){
				$hic = $hic->hic;
			}else{
				$hic = 0;
			}

			$hbf = $this->db->query("SELECT ifnull(sum(ds.stock),0) as hbf
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='HBF50ML' 
			GROUP BY ds.id_dealer")->row();

			if($hbf->hbf!=0){
				$hbf = $hbf->hbf;
			}else{
				$hbf = 0;
			}

			$acg = $this->db->query("SELECT ifnull(sum(ds.stock),0) as acg
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='ACG10GR' 
			GROUP BY ds.id_dealer")->row();

			if($acg->acg!=0){
				$acg = $acg->acg;
			}else{
				$acg = 0;
			}

			$acl = $this->db->query("SELECT ifnull(sum(ds.stock),0) as acl
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='ACL70ML' 
			GROUP BY ds.id_dealer")->row();

			if($acl->acl!=0){
				$acl = $acl->acl;
			}else{
				$acl = 0;
			}

			$osc = $this->db->query("SELECT ifnull(sum(ds.stock),0) as osc
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='OSC70ML' 
			GROUP BY ds.id_dealer")->row();

			if($osc->osc!=0){
				$osc = $osc->osc;
			}else{
				$osc = 0;
			}

			$cc = $this->db->query("SELECT ifnull(sum(ds.stock),0) as cc
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='CC200ML' 
			GROUP BY ds.id_dealer")->row();

			if($cc->cc!=0){
				$cc = $cc->cc;
			}else{
				$cc = 0;
			}

			$tbc = $this->db->query("SELECT ifnull(sum(ds.stock),0) as tbc
			FROM ms_h3_dealer_stock ds
			WHERE ds.id_dealer='$row->id_dealer' and id_part='TBC500ML' 
			GROUP BY ds.id_dealer")->row();

			if($tbc->tbc!=0){
				$tbc = $tbc->tbc;
			}else{
				$tbc = 0;
			}

			echo "
				<tr>
					<td>$nom</td>
					<td>$row->nama_dealer</td>
					<td>$row->status</td>
					<td>$row->kabupaten</td>
					<td>$row->nama_lengkap</td>
					<td>$ue->ue</td>
					<td>$row->oil</td>
					<td>$row->gmo</td>
					<td>$row->osc</td>
					<td>$row->hic</td>
					<td>$row->hbf</td>
					<td>$row->acg</td>
					<td>$row->acl</td>
					<td>$row->osc</td>
					<td>$row->cc</td>
					<td>$row->tbc</td>
					<td>$oil</td>
					<td>$gmo</td>
					<td>$osc</td>
					<td>$hic</td>
					<td>$hbf</td>
					<td>$acg</td>
					<td>$acl</td>
					<td>$osc</td>
					<td>$cc</td>
					<td>$tbc</td>
				</tr>
			";
			$nom++;
			$sum_ue  += $ue->ue;
			$sum_oil += $row->oil;
			$sum_gmo += $row->gmo;
			$sum_hpc += $row->osc;
			$sum_hic += $row->hic;
			$sum_hbf += $row->hbf;
			$sum_acg += $row->acg;
			$sum_acl += $row->acl;
			$sum_osc += $row->osc;
			$sum_cc  += $row->cc;
			$sum_tbc += $row->tbc;
			$sum_stock_oil += $oil;
			$sum_stock_gmo += $gmo;
			$sum_stock_hpc += $osc;
			$sum_stock_hic += $hic;
			$sum_stock_hbf += $hbf;
			$sum_stock_acg += $acg;
			$sum_stock_acl += $acl;
			$sum_stock_osc += $osc;
			$sum_stock_cc  += $cc;
			$sum_stock_tbc += $tbc;
		}
		echo "
				<tr>
					<td style='vertical-align : middle;text-align:center;' colspan='5'><b>Total</b></td>
					<td><b>$sum_ue</b></td>
					<td><b>$sum_oil</b></td>
					<td><b>$sum_gmo</b></td>
					<td><b>$sum_hpc</b></td>
					<td><b>$sum_hic</b></td>
					<td><b>$sum_hbf</b></td>
					<td><b>$sum_acg</b></td>
					<td><b>$sum_acl</b></td>
					<td><b>$sum_osc</b></td>
					<td><b>$sum_cc</b></td>
					<td><b>$sum_tbc</b></td>
					<td><b>$sum_stock_oil</b></td>
					<td><b>$sum_stock_gmo</b></td>
					<td><b>$sum_stock_hpc</b></td>
					<td><b>$sum_stock_hic</b></td>
					<td><b>$sum_stock_hbf</b></td>
					<td><b>$sum_stock_acg</b></td>
					<td><b>$sum_stock_acl</b></td>
					<td><b>$sum_stock_osc</b></td>
					<td><b>$sum_stock_cc</b></td>
					<td><b>$sum_stock_tbc</b></td>
				</tr>
			";
	} else {
		echo "<td colspan='26' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>