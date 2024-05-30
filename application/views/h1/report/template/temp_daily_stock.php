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
header("Content-Disposition: attachment; filename=daily_stock_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$bulan 		= substr($tanggal, 0,7);
$tgl 			= substr($tanggal, 8,2);
$tgl2_a 	= $bulan."-01";
$tgl2_b 	= $tanggal;

//$bulan2 	= 
$bulan2   = date('Y-m', strtotime('-1 month', strtotime($tanggal)));
$tgl1_a		= $bulan2."-01";
$tgl1_b		= $bulan2."-".$tgl;

$awal  		= strtotime($tgl1_a);
$akhir  	= strtotime($tgl1_b);
$diff  		= $akhir - $awal;
$selisih_1 = floor($diff / (60 * 60 * 24));            

$awal2  	= strtotime($tgl2_a);
$akhir2  	= strtotime($tgl2_b);
$diff2  	= $akhir2 - $awal2;
$selisih_2 = floor($diff2 / (60 * 60 * 24));  
?>
<table>
	<tr>
		<td>Working Days</td>
		<td>: 
		<?php 
		echo $bulan2." s/d ".$tgl1_b." ($selisih_1 Hari)";
		?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
		<?php 
		echo $tgl2_a." s/d ".$tgl2_b." ($selisih_2 Hari)";;
		?>
		</td>
	</tr>
	
</table>
<table border="1">  
	<tr bgcolor='orange'>
		<td align="center" rowspan="3">No</td>
		<td align="center" rowspan="3">Nama Dealer</td>
		<td align="center" rowspan="3">Kode Dealer</td>
		<td align="center" colspan="11">All Segment</td>
		<?php 
		//$cari_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan ORDER BY tipe_ahm ASC LIMIT 30,20");
		$cari_tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");
		foreach ($cari_tipe->result() as $ambil) {
			echo "
			<td align='center' rowspan='3'>$ambil->tipe_ahm</td>
			";
		}
		?>
	</tr>
	<tr bgcolor='orange'>
		<td align="center" colspan="3">Sales</td>
		<td align="center" colspan="3">Daily Sales</td>
		<td align="center" rowspan="2">Stock on Hand</td>
		<td align="center" rowspan="2">Dealer Intransit</td>
		<td align="center" rowspan="2">Dealer Unfill</td>
		<td align="center" rowspan="2">Stock + Intransit + Unfill</td>
		<td align="center" rowspan="2">Stock Days</td>
			
	</tr>
	<tr bgcolor='orange'>
		<td align="center">M-1</td>
		<td align="center">M</td>
		<td align="center">Growth</td>
		<td align="center">M-1</td>
		<td align="center">M</td>
		<td align="center">Growth</td>
		
	</tr>
<?php 
$no=1;
          

$g_jum_m1=0;$g_jum_m=0;$g_growth_p=0;$g_d_growth_p=0;$g_d_jum_m1=0;$g_d_jum_m=0;$g_cek_qty2=0;$g_cek_in=0;$g_cek_unfill=0;$g_jum_cek=0;$g_cek_qty=0;$g_cek_qty=0;$g_stock_days=0;
$sql_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE ms_kabupaten.id_provinsi = 1500
	ORDER BY ms_kabupaten.kode_samsat ASC LIMIT 0,5");
foreach ($sql_kab->result() as $isi) {  
	$cek_dealer = $this->db->query("SELECT * FROM ms_dealer 
    LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
    LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
    LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
    WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND ms_dealer.active = 1
    ORDER BY FIELD(kode_dealer_md,'00888','12203','05399','05391','01118','03530','05621','13385.1','13385','07628.1','07628','12598','12598.1','12142','12142.1','12142.2','12797','13384','13384.2','07719','07465','','11791','06935','13387','07780','13384.3','13384.1','13384.4','13384.5','','12598.2','05545','04730','13386','17338','','03540','03573','07781','13621','13382','13382.1','','03538','07464','12144','12144.1','07717','04576','','01354','09155','13382.2','04692','04692.1','13759','01925','','06111','06112','12143','12143.1','00758','09673','','05529','13388.1','13388','00675','07720','13384.6','','08549','13381','13381.1','05621.1')");	
	echo "
	<tr>";
		$t_jum_m1=0;$t_jum_m=0;$t_growth_p=0;$t_d_growth_p=0;$t_d_jum_m1=0;$t_d_jum_m=0;$t_cek_qty2=0;$t_cek_in=0;$t_cek_unfill=0;$t_jum_cek=0;$g_cek_qty=0;$t_cek_qty=0;$t_stock_days=0;
		foreach ($cek_dealer->result() as $amb) {
			$cek_qty2 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
        LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
        LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
        LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
        LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
        LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
        WHERE tr_penerimaan_unit_dealer.id_dealer = '$amb->id_dealer'
        AND tr_penerimaan_unit_dealer_detail.retur = 0
        AND tr_scan_barcode.status = '4'")->row()->jum;			
			$cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
				INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
				INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
				INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin 
				WHERE tr_picking_list_view.no_mesin NOT IN 
					(SELECT no_mesin FROM tr_surat_jalan_detail 										
						WHERE tr_surat_jalan_detail.retur = 0)
				AND tr_do_po.id_dealer = '$amb->id_dealer'")->row()->jum;			
			$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail 
				INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
				INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
        WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
        AND tr_surat_jalan_detail.retur = 0
        AND tr_surat_jalan.id_dealer = '$amb->id_dealer'")->row()->jum;
			$jum_cek = $cek_in + $cek_unfill;
			$cek_qty2 = ($cek_qty2 > 0) ? $cek_qty2 : 0 ;
			$cek_in = ($cek_in > 0) ? $cek_in : 0 ;
			$cek_unfill = ($cek_unfill > 0) ? $cek_unfill : 0 ;
			$jum_cek = ($jum_cek > 0) ? $jum_cek : 0 ;


			$jml_reg = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer 
        INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
        INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
        INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
        LEFT JOIN ms_group_dealer_detail ON ms_group_dealer_detail.id_dealer=ms_dealer.id_dealer 
        WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$tgl1_a' AND '$tgl1_b'
        AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
    	$jml_gc = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
        INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
        INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
        INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
        INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_sales_order_gc.id_dealer        
        WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tgl1_a' AND '$tgl1_b'
        AND tr_sales_order_gc.id_dealer = '$amb->id_dealer'
        ")->row()->jum;
    	$jum_m1 = $jml_reg + $jml_gc;

    	$jml_reg2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer 
        INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
        INNER JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
        INNER JOIN tr_scan_barcode ON tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
        LEFT JOIN ms_group_dealer_detail ON ms_group_dealer_detail.id_dealer=ms_dealer.id_dealer 
        WHERE tr_sales_order.tgl_cetak_invoice BETWEEN '$tgl2_a' AND '$tgl2_b'
        AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
    	$jml_gc2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
        INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
        INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
        INNER JOIN tr_spk_gc ON tr_spk_gc.no_spk_gc = tr_sales_order_gc.no_spk_gc
        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
        INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_sales_order_gc.id_dealer        
        WHERE tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tgl2_a' AND '$tgl2_b'
        AND tr_sales_order_gc.id_dealer = '$amb->id_dealer'
        ")->row()->jum;
    	$jum_m 		= $jml_reg2 + $jml_gc2;
    	$growth 	= @($jum_m / $jum_m1) - 1;
    	$growth_p = number_format($growth, 2) * 100;

    	$d_jum_m1 = @($jum_m1 / $selisih_1);
    	$d_jum_m 	= @($jum_m / $selisih_2);
    	$d_growth = @($d_jum_m / $d_jum_m1) - 1;
    	$d_growth_p = number_format($d_growth, 2) * 100;

    	$d_jum_m1 = round($d_jum_m1,2);
    	$d_jum_m 	= round($d_jum_m,2);
    	$growth_p = round($growth_p,2);
    	$d_growth_p = round($d_growth_p,2);
    	$stock_days = @($cek_qty2 / $jum_m) * 30;
    	$stock_days = round($stock_days,2);
			echo "
			<tr>
				<td>$no</td>
				<td>$amb->nama_dealer</td>
				<td align='center'>$amb->kode_dealer_md</td>
				<td align='center'>$jum_m1</td>
				<td align='center'>$jum_m</td>
				<td align='center'>$growth_p %</td>
				<td align='center'>$d_jum_m1</td>
				<td align='center'>$d_jum_m</td>
				<td align='center'>$d_growth_p %</td>
				<td align='center'>$cek_qty2</td>
				<td align='center'>$cek_in</td>
				<td align='center'>$cek_unfill</td>
				<td align='center'>$jum_cek</td>
				<td align='center'>$stock_days</td>";
				$t_cek_qty=0;
				$cari_tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");
				//$cari_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan ORDER BY tipe_ahm ASC LIMIT 30,20");
				foreach ($cari_tipe->result() as $ambil) {
					$cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
		        LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
		        LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
		        LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
		        LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
		        LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
		        WHERE tr_scan_barcode.tipe_motor = '$ambil->id_tipe_kendaraan' AND tr_penerimaan_unit_dealer.id_dealer = '$amb->id_dealer'
		        AND tr_penerimaan_unit_dealer_detail.retur = 0
		        AND tr_scan_barcode.status = '4'")->row()->jum;
					if($cek_qty == 0) $cek_qty = "";
					echo "
					<td align='center'>$cek_qty</td>
					";
					$t_cek_qty += $cek_qty;
				}
				echo"
			</tr>";		
			$g_cek_qty += $t_cek_qty;
			$t_jum_m1 += $jum_m1;
			$t_jum_m += $jum_m;
			$t_growth_p += $growth_p;
			$t_d_jum_m1 += $d_jum_m1;
			$t_d_jum_m += $d_jum_m;
			$t_d_growth_p += $d_growth_p;
			$t_cek_qty2 += $cek_qty2;
			$t_jum_cek += $jum_cek;
			$t_cek_in += $cek_in;
			$t_cek_unfill += $cek_unfill;
			$t_stock_days += $stock_days;
			$no++;
		}
		echo "
		<td bgcolor='yellow' colspan='3'>$isi->kabupaten</td>	
		<td bgcolor='yellow' align='center'>$t_jum_m1</td>	
		<td bgcolor='yellow' align='center'>$t_jum_m</td>	
		<td bgcolor='yellow' align='center'>$t_growth_p</td>	
		<td bgcolor='yellow' align='center'>$t_d_jum_m1</td>	
		<td bgcolor='yellow' align='center'>$t_d_jum_m</td>	
		<td bgcolor='yellow' align='center'>$t_d_growth_p</td>	
		<td bgcolor='yellow' align='center'>$t_cek_qty2</td>	
		<td bgcolor='yellow' align='center'>$t_cek_in</td>	
		<td bgcolor='yellow' align='center'>$t_cek_unfill</td>	
		<td bgcolor='yellow' align='center'>$t_jum_cek</td>
		<td bgcolor='yellow' align='center'>$t_stock_days</td>";
		$cari_tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");
		//$cari_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan ORDER BY tipe_ahm ASC LIMIT 30,20");
		//$g_cek_qty=0;
		foreach ($cari_tipe->result() as $ambil) {	
			echo "
			<td bgcolor='yellow' align='center'></td>";			
		}
		echo "
	</tr>";
	$g_jum_m1 += $t_jum_m1;
	$g_jum_m += $t_jum_m;
	$g_growth_p += $t_growth_p;
	$g_d_jum_m1 += $t_d_jum_m1;
	$g_d_jum_m += $t_d_jum_m;
	$g_d_growth_p += $t_d_growth_p;
	$g_cek_qty2 += $t_cek_qty2;
	$g_cek_in += $t_cek_in;
	$g_cek_unfill += $t_cek_unfill;
	$g_jum_cek += $t_jum_cek;
	$g_stock_days += $t_stock_days;	
}
echo "
	<td bgcolor='pink' colspan='3'>GRAND TOTAL</td>	
	<td bgcolor='pink' align='center'>$g_jum_m1</td>	
	<td bgcolor='pink' align='center'>$g_jum_m</td>	
	<td bgcolor='pink' align='center'>$g_growth_p</td>	
	<td bgcolor='pink' align='center'>$g_d_jum_m1</td>	
	<td bgcolor='pink' align='center'>$g_d_jum_m</td>	
	<td bgcolor='pink' align='center'>$g_d_growth_p</td>	
	<td bgcolor='pink' align='center'>$g_cek_qty2</td>	
	<td bgcolor='pink' align='center'>$g_cek_in</td>	
	<td bgcolor='pink' align='center'>$g_cek_unfill</td>	
	<td bgcolor='pink' align='center'>$g_jum_cek</td>
	<td bgcolor='pink' align='center'>$g_stock_days</td>";
	$cari_tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","tipe_ahm","ASC");
	//$cari_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan ORDER BY tipe_ahm ASC LIMIT 30,20");
	foreach ($cari_tipe->result() as $ambil) {	
		echo "
		<td bgcolor='pink' align='center'></td>";			
	}
	echo "
</tr>";
?>