<?php
$no = date('d/m/y_Hi');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Laporan Performance AHASS_" . $no . " WIB.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php $start_date_2 = date("d-m-Y", strtotime($start_date));
$end_date_2 = date("d-m-Y", strtotime($end_date)); ?>
<table border="1">
	<?php if ($id_dealer != 'all') { ?>
		<caption><b>Laporan Penjualan Spare Parts (Cost Price)
				<br> <?php echo $report->row()->nama_dealer ?> </b><br><br></caption>
	<?php } else { ?>
		<caption><b>Laporan Penjualan Spare Parts (Cost Price)
				<br> Periode <?php echo $start_date_2 . " s/d " . $end_date_2 ?>
			</b><br><br></caption>
	<?php } ?>



	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="4"><b>No</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="4"><b>Nama Dealer / AHASS </b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="4"><b>Status</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="4"><b>Kabupaten</b></td>
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
		<td align="center" colspan="15"><b><?php echo $bulan ?></b></td>
	</tr>
	<tr>
		<td align="center" colspan="7"><b>Unit Entry (1:1)</b></td>
		<td align="center" colspan="4"><b>Performance Parts</b></td>
		<td align="center" colspan="4"><b>Performance Oil</b></td>
	</tr>
	<tr>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>KPB 1</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>KPB 2</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>KPB 3</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>KPB 4</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>GO</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Non KPB</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Total UE</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Beli</b></td>
		<td align="center" colspan="2"><b>Jual</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Stock <br> 30/31</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Beli</b></td>
		<td align="center" colspan="2"><b>Jual</b></td>
		<td style="vertical-align : middle;text-align:center;" rowspan="2"><b>Stock <br> 30/31</b></td>
	</tr>
	<tr>
		<td align="center"><b>PKB</b></td>
		<td align="center"><b>NON PKB</b></td>
		<td align="center"><b>PKB</b></td>
		<td align="center"><b>NON PKB</b></td>
	</tr>

	<?php
	$nom = 1;
	$sum_kpb1 = 0;
	$sum_kpb2 = 0;
	$sum_kpb3 = 0;
	$sum_kpb4 = 0;
	$sum_go = 0;
	$sum_non_kpb = 0;
	$sum_ue = 0;
	$sum_beli_part_2 = 0;
	$sum_pkb_part_2 = 0;
	$sum_non_pkb_part_2 = 0;
	$sum_stock_part_2 = 0;
	$sum_beli_oli_2 = 0;
	$sum_pkb_oli_2 = 0;
	$sum_non_pkb_oli_2 = 0;
	$sum_stock_oli_2 = 0;
	if ($report->num_rows() > 0) {
		foreach ($report->result() as $row) {
			$beli_oli = $this->db->query("SELECT sum(dso.total) as total
			-- plp.id_part, plp.id_picking_list, plp.id_part_int , plp.id_tipe_kendaraan, dso.id as id_do_sales_order_int
			from tr_h3_md_packing_sheet ps
			join tr_h3_md_picking_list pl on ps.id_picking_list_int = pl.id 
			-- JOIN tr_h3_md_picking_list_parts plp on plp.id_picking_list_int=pl.id
			join tr_h3_md_do_sales_order dso on dso.id = pl.id_ref_int 
			-- join ms_part mp on mp.id_part_int=plp.id_part_int 
			join tr_h3_md_sales_order so on so.id = dso.id_sales_order_int 
			join ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE left(ps.tgl_faktur,10) >= '$start_date' and left(ps.tgl_faktur,10) <= '$end_date' 
			-- and mp.kelompok_part in ('OIL','GMO')
			and so.produk='Oil'
			AND pl.id_dealer = $row->id_dealer 
			GROUP BY pl.id_dealer")->row();
			$sum_beli_oli = $beli_oli->total;

			$beli_part = $this->db->query("SELECT sum(dso.total) as total
			-- plp.id_part, plp.id_picking_list, plp.id_part_int , plp.id_tipe_kendaraan, dso.id as id_do_sales_order_int
			from tr_h3_md_packing_sheet ps
			join tr_h3_md_picking_list pl on ps.id_picking_list_int = pl.id 
			-- JOIN tr_h3_md_picking_list_parts plp on plp.id_picking_list_int=pl.id
			join tr_h3_md_do_sales_order dso on dso.id = pl.id_ref_int 
			-- join ms_part mp on mp.id_part_int=plp.id_part_int 
			join tr_h3_md_sales_order so on so.id = dso.id_sales_order_int 
			join ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE left(ps.tgl_faktur,10) >= '$start_date' and left(ps.tgl_faktur,10) <= '$end_date' 
			-- and mp.kelompok_part not in ('OIL','GMO','PACC','ACCEC','FED OIL','OTHERS','PA','TL')
			and so.produk='Parts'
			AND pl.id_dealer = $row->id_dealer
			GROUP BY pl.id_dealer")->row();
			$sum_beli_part = $beli_part->total;

			// $sum_beli_oli = 0;
			// foreach($beli_oli as $oli){
			// 	if($oli->id_tipe_kendaraan != null){
			// 		$tipe_kendaraan = " and id_tipe_kendaraan='$oli->id_tipe_kendaraan'";
			// 	}
				
			// 	$beli_oli_parts = $this->db->query("SELECT ifnull((harga_setelah_diskon*qty_supply),0) as total_beli_oli
			// 	from tr_h3_md_do_sales_order_parts dsop
			// 	where id_do_sales_order_int='$oli->id_do_sales_order_int' and id_part_int = '$oli->id_part_int' $tipe_kendaraan")->row();

			// 	$sum_beli_oli += $beli_oli_parts->total_beli_oli;
			// }

			// $sum_beli_part = 0;
			// foreach($beli_part as $part){
			// 	$beli_part_parts = $this->db->query("SELECT ifnull((harga_setelah_diskon*qty_supply),0) as total_beli_part
			// 	from tr_h3_md_do_sales_order_parts dsop
			// 	where id_do_sales_order_int='$part->id_do_sales_order_int' and id_part_int = '$part->id_part_int'")->row();

			// 	$sum_beli_part += $beli_part_parts->total_beli_part;
			// }


			$part = $this->db->query("SELECT 
			sum(CASE WHEN nsc.referensi='work_order' THEN ((nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
			THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) ELSE 0 END) as total_part_kpb,
				sum(CASE WHEN nsc.referensi='sales' THEN ((nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
			THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) ELSE 0 END) as total_part_non_kpb
			FROM tr_h23_nsc nsc
			JOIN tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc 
			JOIN ms_part mp on mp.id_part_int=nspart.id_part_int 
			where left(nsc.created_at,10)>='$start_date' and left(nsc.created_at,10)<='$end_date' and nsc.id_dealer=$row->id_dealer and mp.kelompok_part not in ('OIL','GMO','PACC','ACCEC','FED OIL','OTHERS','PA','TL')
			group by nsc.id_dealer")->row();

			if ($part == '') {
				$total_part_kpb = number_format(0, 0, ',', '.');
				$total_part_non_kpb = number_format(0, 0, ',', '.');
			} else {
				$total_part_kpb = number_format($part->total_part_kpb, 0, ',', '.');
				$total_part_non_kpb = number_format($part->total_part_non_kpb, 0, ',', '.');
			}

			$oli = $this->db->query("SELECT 
			sum(CASE WHEN nsc.referensi='work_order' THEN ((nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
			THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) ELSE 0 END) as total_oli_kpb,
				sum(CASE WHEN nsc.referensi='sales' THEN ((nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
			THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) ELSE 0 END) as total_oli_non_kpb
			FROM tr_h23_nsc nsc
			JOIN tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc 
			JOIN ms_part mp on mp.id_part_int=nspart.id_part_int 
			where left(nsc.created_at,10)>='$start_date' and left(nsc.created_at,10)<='$end_date' and nsc.id_dealer=$row->id_dealer and mp.kelompok_part in ('OIL','GMO')
			group by nsc.id_dealer")->row();


			if ($oli == '') {
				$total_oli_kpb = number_format(0, 0, ',', '.');
				$total_oli_non_kpb = number_format(0, 0, ',', '.');
			} else {
				$total_oli_kpb = number_format($oli->total_oli_kpb, 0, ',', '.');
				$total_oli_non_kpb = number_format($oli->total_oli_non_kpb, 0, ',', '.');
			}

			echo "
				<tr>
					<td>$nom</td>
					<td>$row->nama_dealer</td>
					<td>$row->status</td>
					<td>$row->kabupaten</td>
					<td>$row->ue_kpb_1</td>
					<td>$row->ue_kpb_2</td>
					<td>$row->ue_kpb_3</td>
					<td>$row->ue_kpb_4</td>
					<td>$row->ue_go</td>
					<td>$row->ue_non_kpb</td>
					<td>$row->total_ue</td>
					<td align='right'>".number_format($sum_beli_part, 0, ',', '.')."</td>
					<td align='right'>$total_part_kpb</td>
					<td align='right'>$total_part_non_kpb</td>
					<td align='right'> - </td>
					<td align='right'>".number_format($sum_beli_oli, 0, ',', '.')."</td>
					<td align='right'>$total_oli_kpb</td>
					<td align='right'>$total_oli_non_kpb</td>
					<td align='right'> - </td>
				</tr>
			";
			$nom++;
			$sum_kpb1 += $row->ue_kpb_1;
			$sum_kpb2 += $row->ue_kpb_2;
			$sum_kpb3 += $row->ue_kpb_3;
			$sum_kpb4 += $row->ue_kpb_4;
			$sum_go += $row->ue_go;
			$sum_non_kpb += $row->ue_non_kpb;
			$sum_ue += $row->total_ue;
			$sum_beli_part_2 += $sum_beli_part;
			$sum_pkb_part_2 += $part->total_part_kpb;
			$sum_non_pkb_part_2 += $part->total_part_non_kpb;
			$sum_beli_oli_2 += $sum_beli_oli;
			$sum_pkb_oli_2 += $oli->total_oli_kpb;
			$sum_non_pkb_oli_2 += $oli->total_oli_non_kpb;
		}
		echo "
		<tr>
			<td colspan='4'><b>Total</b></td>
			<td align='right'><b>$sum_kpb1</b></td>
			<td align='right'><b>$sum_kpb2</b></td>
			<td align='right'><b>$sum_kpb3</b></td>
			<td align='right'><b>$sum_kpb4</b></td>
			<td align='right'><b>$sum_go</b></td>
			<td align='right'><b>$sum_non_kpb</b></td>
			<td align='right'><b>$sum_ue</b></td>
			<td align='right'><b>".number_format($sum_beli_part_2, 0, ',', '.')."</b></td>
			<td align='right'><b>".number_format($sum_pkb_part_2, 0, ',', '.')."</b></td>
			<td align='right'><b>".number_format($sum_non_pkb_part_2, 0, ',', '.')."</b></td>
			<td align='right'><b> 0 </b></td>
			<td align='right'><b>".number_format($sum_beli_oli_2, 0, ',', '.')."</b></td>
			<td align='right'><b>".number_format($sum_pkb_oli_2, 0, ',', '.')."</b></td>
			<td align='right'><b>".number_format($sum_non_pkb_oli_2, 0, ',', '.')."</b></td>
			<td align='right'><b> 0 </td>
		</tr>
		";
	} else {
		echo "<td colspan='19' style='text-align:center'> Maaf, Tidak Ada Data </td>";
	}
	?>
</table>