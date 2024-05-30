<?php 
$no = date('d-m-Y_His');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SSP_byType_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");

function mata_uang($a){
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
	return number_format($a, 0, ',', '.');
} 
?>
<table border="1">  
 	<tr> 		
 		<td align="center" rowspan ="2">No</td>
 		<td align="center" rowspan ="2">Kode Tipe</td>
 		<td align="center" rowspan ="2">Deskripsi</td>
 		<td align="center" rowspan ="2">PO</td>
 		<td align="center" rowspan ="2">Dist. Plan</td>
 		<td align="center" rowspan ="2">DO Harian</td> 	
 		<td align="center" rowspan ="2">Intransit AHM</td> 		
 		<td align="center" rowspan ="2">MD Stock</td> 			
 		<td align="center" rowspan ="2">Unfill D</td> 		
 		<td align="center" rowspan ="2">Intransit MD-D</td> 		
 		<td align="center" rowspan ="2">On Hand D</td> 		
 		<td align="center" rowspan ="2">Market Stock</td>
		<td align="center" colspan ="2">Distribution</td> 
		<tr>		
			<td align="center" >AHM</td> 		
			<td align="center" >MD</td> 	
			<td align="center">Sales </td> 		 	
			<td align="center">Sisa DO</td> 	
		</tr>	 		
 	</tr>
 	<?php 

	$get_tipe = $this->db->query('select id_tipe_kendaraan, tipe_ahm from ms_tipe_kendaraan where active = 1 order by created_at desc');

	$no = 1;
	$tot_po = 0;
	$tot_displan = 0;
	$tot_do_harian = 0;
	$tot_int_ahm = 0;

	$tot_md_stok = 0;
	$tot_unfill_d = 0;
	$tot_int_md = 0;
	$tot_stok_d = 0;
	$tot_market = 0;

	$tot_dist_ahm = 0;
	$tot_dist_md = 0;
	$tot_sales = 0;
	$sisa_do =0;

	$flag_do_harian = 1;
	$tot_do_harian_bfr=0;

	foreach($get_tipe->result() as $row) {     
		$today = date('Y-m-d');

		$bulan = date('m');	
		$tahun = date('Y');
		// $bulan_2 = sprintf("%'.02d",$bulan);			
		$tahun_bulan = $bulan."-".$tahun;

		// PO .UPO
		$sql_po = $this->db->query("
			select a.bulan , a.tahun, left(b.id_item,3) as id_tipe_kendaraan , sum( (case when b.qty_po_fix is null then b.qty_order else b.qty_po_fix end)) as qty
			from tr_po a 
			join tr_po_detail b on a.id_po = b.id_po 
			join ms_tipe_kendaraan c on left(b.id_item,3) = c.id_tipe_kendaraan 
			join ms_warna d on RIGHT (b.id_item,2) = d.id_warna 
			where a.status not in ('reject_ahm','input') and bulan = '$bulan' and tahun ='$tahun' and left(b.id_item,3) = '$row->id_tipe_kendaraan'
			group by a.bulan , a.tahun, left(b.id_item,3), c.tipe_ahm
		");
		
		$qty_po = 0;
		if($sql_po->num_rows()>0){
			$qty_po = $sql_po->row()->qty;
			$tot_po += $qty_po;
		}

		// displan 
		$sql_displan = $this->db->query("
			SELECT SUM(tr_displan.qty_plan) AS jum, tr_displan.id_tipe_kendaraan
			FROM tr_displan 
			WHERE tr_displan.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND MID(tr_displan.tanggal,3,2) = '$bulan' AND RIGHT(tr_displan.tanggal,4) = '$tahun'
			GROUP BY tr_displan.id_tipe_kendaraan
			ORDER BY tr_displan.id_displan DESC
		");
	
		$qty_displan = 0;
		if($sql_displan->num_rows()>0){		
			$qty_displan = $sql_displan->row()->jum;
			$tot_displan += $qty_displan;
		}

		// do harian
		$tgl = date('d');
		$not_today = '';
		$qty_do_harian = 0;
		
		// if($flag_do_harian==0){
		// 	$tgl = date('d',strtotime($today . "-1 days"));
		// 	$not_today ='*';
		// }

		$cek_do_harian = $this->db->query("SELECT SUM(jumlah) as jum 
			FROM tr_sipb 
			WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'
			AND MID(tr_sipb.tgl_sipb,3,2) = '$bulan' AND RIGHT(tr_sipb.tgl_sipb,4) = '$tahun' and left(tr_sipb.tgl_sipb,2) = '$tgl'
		");
		
		if($cek_do_harian->row()->jum != null || $cek_do_harian->row()->jum != ''){
			$qty_do_harian = $cek_do_harian->row()->jum;
			$tot_do_harian += $qty_do_harian;
		}else{
			$tgl = date('d',strtotime($today . "-1 days"));

			$cek_do_harian = $this->db->query("SELECT SUM(jumlah) as jum 
				FROM tr_sipb 
				WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'
				AND MID(tr_sipb.tgl_sipb,3,2) = '$bulan' AND RIGHT(tr_sipb.tgl_sipb,4) = '$tahun' and left(tr_sipb.tgl_sipb,2) = '$tgl'
			");

			if($cek_do_harian->row()->jum != null || $cek_do_harian->row()->jum != ''){
				$qty_do_harian = $cek_do_harian->row()->jum .'*';
				// $tot_do_harian += $cek_do_harian->row()->jum;
				$tot_do_harian_bfr += $cek_do_harian->row()->jum;
			}
			
			$flag_do_harian=0;
			// $not_today ='*'; // tidak bisa karna tdk semua tipe ada qty
		}

		// intransit ahm
		$set_pu = 'PU'.$tahun;
		$cek_sl = $this->db->query("
			SELECT COUNT(no_mesin) AS jumlah 
			FROM tr_shipping_list 
			WHERE id_modell = '$row->id_tipe_kendaraan'
			AND MID(tgl_sl,3,2) = '$bulan' AND RIGHT(tgl_sl,4) = '$tahun' and tr_shipping_list.no_mesin not in (
				select no_mesin from tr_scan_barcode 
			)
		");
	
		$qty_sl_ahm = 0;
		if($cek_sl->num_rows() > 0){
			$qty_sl_ahm = $cek_sl->row()->jumlah;
			$tot_int_ahm += $qty_sl_ahm;
		}

		// stok md
		$sql_stok_md = $this->db->query("SELECT COUNT(no_mesin) AS jumlah 
			FROM tr_scan_barcode 
			WHERE tipe_motor = '$row->id_tipe_kendaraan' and status in (1,7)
		");
		
		$qty_stok_md = 0;
		if($sql_stok_md->num_rows() > 0){
			$qty_stok_md = $sql_stok_md->row()->jumlah;
			$tot_md_stok += $qty_stok_md;
		}	
		
		// unfill dealer
		// $cek_unfill  = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum 
		// 	FROM tr_do_po 
		// 	INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do 
		// 	INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
		// 	INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
		// 	WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
		// 	AND tr_do_po.status = 'approved' AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'
		// ");

		$cek_unfill  = $this->db->query("
			SELECT COUNT(tr_picking_list_view.no_mesin) AS jum ,'stok_md_all_type' as menu
			FROM tr_picking_list 
			inner join tr_picking_list_view on tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list 
			inner join tr_scan_barcode on tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin 
			WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' 
			and tr_picking_list_view.no_mesin NOT IN (select no_mesin from tr_surat_jalan_detail where ceklist != 'tidak' and retur = 0)
		");

		$qty_unfill = 0;
		if($cek_unfill->num_rows() > 0){
			$qty_unfill = $cek_unfill->row()->jum;
			$tot_unfill_d += $qty_unfill;
		}

		// intransit md-d
		$cek_sl = $this->db->query("SELECT COUNT(b.no_mesin) AS jumlah 
			FROM tr_surat_jalan_detail b 
			join tr_scan_barcode c on c.no_mesin = b.no_mesin 
			WHERE c.tipe_motor = '$row->id_tipe_kendaraan' and b.terima is null and b.ceklist != 'tidak' and retur = 0
			-- AND MID(tgl_surat,6,2) = '$bulan' AND left(tgl_surat,4) = '$tahun' 
		");

		$qty_sl = 0;
		if($cek_sl->num_rows() > 0){
			$qty_sl = $cek_sl->row()->jumlah;
			$tot_int_md += $qty_sl;
		}

		// stok dealer
		$sql_stok_d = $this->db->query("SELECT COUNT(no_mesin) AS jumlah 
			FROM tr_scan_barcode 
			WHERE tipe_motor = '$row->id_tipe_kendaraan' and status ='4'
		");
		
		$qty_stok_d = 0;
		if($sql_stok_d->num_rows() > 0){
			$qty_stok_d = $sql_stok_d->row()->jumlah;
			$tot_stok_d += $qty_stok_d;
		}	
		
		// market stok
		$market_stok = $qty_stok_md + $qty_unfill + $qty_sl + $qty_stok_d;
		$tot_market += $market_stok;

		// dist ahm (sipb)
		$cek_sipb = $this->db->query("SELECT SUM(jumlah) as jum 
			FROM tr_sipb 
			WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'
			AND MID(tr_sipb.tgl_sipb,3,2) = '$bulan' AND RIGHT(tr_sipb.tgl_sipb,4) = '$tahun'");

		$qty_sipb = 0;
		if($cek_sipb->row()->jum !='' || $cek_sipb->row()->jum != null){
			$qty_sipb = $cek_sipb->row()->jum;
			$tot_dist_ahm += $qty_sipb;
		}

		// dist md (Picking list)
		$sql_dist_md = $this->db->query("SELECT count(tr_picking_list_view.no_mesin) as jum 
			FROM tr_picking_list_view
			INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
			WHERE MID(tr_picking_list.tgl_pl ,6,2) = '$bulan' AND left(tr_picking_list.tgl_pl ,4) = '$tahun'   
			AND tr_picking_list.status = 'close' and left(id_item,3) ='$row->id_tipe_kendaraan'
		");

		$qty_dist_md = 0;
		if($sql_dist_md->num_rows() > 0){
			$qty_dist_md = $sql_dist_md->row()->jum;
			$tot_dist_md += $qty_dist_md;
		}

		// sales
		$sql_sales= $this->db->query("SELECT COUNT(a.no_mesin) AS jumlah 
			FROM tr_scan_barcode a 
			join (
				select no_mesin 
				from tr_sales_order b
				where (MID(b.tgl_cetak_invoice,6,2) = '$bulan' AND left(b.tgl_cetak_invoice,4) = '$tahun' or MID(b.created_at,6,2) = '$bulan' AND left(b.created_at,4) = '$tahun' )
				union
				select no_mesin 
				from tr_sales_order_gc_nosin a
				join tr_sales_order_gc b on a.id_sales_order_gc = b.id_sales_order_gc
				where (MID(b.tgl_cetak_invoice,6,2) = '$bulan' AND left(b.tgl_cetak_invoice,4) = '$tahun' or MID(b.created_at,6,2) = '$bulan' AND left(b.created_at,4) = '$tahun' )
			) b on a.no_mesin = b.no_mesin
			WHERE a.tipe_motor = '$row->id_tipe_kendaraan' and status ='5' 
		");
		
		$qty_sales = 0;
		if($sql_sales->num_rows() > 0){
			$qty_sales = $sql_sales->row()->jumlah;
			$tot_sales += $qty_sales;
		}	

		// sisa do
		$sisa_do = $qty_displan - $qty_sipb;
		$tot_sisa_do += $sisa_do;

		echo "<tr>
			<td>$no</td>
			<td>$row->id_tipe_kendaraan</td>
			<td>$row->tipe_ahm</td>
			<td>$qty_po</td>
			<td>$qty_displan</td>
			<td>$qty_do_harian</td>
			<td>$qty_sl_ahm</td>
			<td>$qty_stok_md</td>
			<td>$qty_unfill</td>
			<td>$qty_sl</td>
			<td>$qty_stok_d</td>
			<td>$market_stok</td>
			<td>$qty_sipb</td>
			<td>$qty_dist_md</td>
			<td>$qty_sales</td>
			<td>$sisa_do</td>
			</tr>	
		";
		$no++;
	}

	echo "
		<tr>
			<td colspan='3'>Total</td>
			<td>$tot_po</td>
			<td>$tot_displan</td>
			<td>$tot_do_harian"."(".$tot_do_harian_bfr."*)"."</td>
			<td>$tot_int_ahm</td>
			<td>$tot_md_stok</td>
			<td>$tot_unfill_d</td>
			<td>$tot_int_md</td>
			<td>$tot_stok_d</td>
			<td>$tot_market</td>
			<td>$tot_dist_ahm</td>
			<td>$tot_dist_md</td>
			<td>$tot_sales</td>
			<td>$tot_sisa_do</td>
		</tr>
	";
 	?>
</table>