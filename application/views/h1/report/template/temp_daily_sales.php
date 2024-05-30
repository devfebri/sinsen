<?php 
// $Date = str_replace("/","-",$Date);
// $Month = substr($Date,0, 2)."<br/>";
// $Day = substr($Date, strpos($Date, ‘-’) + 1, 2)."<br/>";
// $Year = substr($Date, strpos($Date, ‘-’) + 4);
// $Date = date(“Y-m-d”,mktime(0,0,0,$Month,$Day,$Year))."<br />";
// echo $Date;

function namahari($info){        
    switch($info){
        case 'Sunday': return "Minggu"; break;
        case 'Monday': return "Senin"; break;
        case 'Tuesday': return "Selasa"; break;
        case 'Wednesday': return "Rabu"; break;
        case 'Thursday': return "Kamis"; break;
        case 'Friday': return "Jumat"; break;
        case 'Saturday': return "Sabtu"; break;
    };
}
function set_tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
	return $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
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
header("Content-Disposition: attachment; filename=daily_sales_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
	<tr>
		<td>Month</td>
		<td>: <?php		
		$tgl_sekarang = date("Y-m-d");
		$tgl_bln = substr($bulan, 0,7);
		$bulan_a = substr($bulan, 6,2);
		$tahun_a = substr($bulan, 0,4);
		$bulan_s = set_tgl_indo($bulan);
		$bulan_sekarang = set_tgl_indo($tgl_sekarang);
		echo $bulan_s; ?></td>
	</tr>
	<tr>
		<td>Nama Dealer</td>
		<td>: <?php 		
		if($id_dealer != 'All'){
			$dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer);
			$nama_dealer = ($dealer->num_rows()>0) ? $dealer->row()->nama_dealer : "" ;
		}else{
			$nama_dealer = "Semua Dealer";
		}		
		echo $nama_dealer ?></td>
	</tr>
</table>
<table border="1">  
 	<tr> 		 		
 		<td align="center" rowspan="2">Type</td>
 		<td align="center" rowspan="2">B. Stock</td>
 		<td align="center" rowspan="2">Dist.</td>
 		<td align="center" colspan="3">Sales</td>
 		<td align="center" rowspan="2">E. Stock</td> 		
 		<td align="center" colspan="31">Daily Sales</td> 		 		 		
 	</tr>
 	<tr>
 		<td align="center">Reg</td>
 		<td align="center">GC</td>
 		<td align="center">Total</td>
 		<?php 
 		for ($i=1; $i <= 31; $i++) { 			
 			$tgl = sprintf("%'.02d",$i);                                                                     
 			$tgl_lengkap = substr($bulan,0,7)."-".$tgl;
 			$hari =date('l', strtotime($tgl_lengkap)); 
 			$nama_hari = namahari($hari);
 			$bg = ($nama_hari == 'Minggu') ? "bgcolor='pink'" : "" ;
 			echo "<td width='2%' $bg align='center'>$i</td>";
 		}
 		?>
 	</tr>
 	<?php 
 	$cek_bulan 	= explode("-", $bulan);
 	$amb_bulan 	= $cek_bulan[1];
 	$amb_tahun 	= $cek_bulan[0];
 	$tgl_skr 		= gmdate("Y-m-d", time()+60*60*7);
 	$cek_akhir_bulan = $this->m_admin->akhirBulan($amb_tahun,$amb_bulan);

 	if($tgl_skr == $cek_akhir_bulan AND $id_dealer == "All"){
 		$amb_dealer = $this->m_admin->getAll("ms_dealer");
 		foreach ($amb_dealer->result() as $dt) { 			
 			$id_dealer = $dt->id_dealer;
	 		$where_reg 		= " AND tr_sales_order.id_dealer = '$id_dealer'";
	 		$where_gc 		= " AND tr_sales_order_gc.id_dealer = '$id_dealer'";
	 		$where_dist 	= " AND tr_do_po.id_dealer = '$id_dealer'"; 		
	 		$where_qty 		= "	AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'";
	 		$where_in 		= "	AND tr_surat_jalan.id_dealer = '$id_dealer'";
	 		$where_un 		= "	AND tr_do_po.id_dealer = '$id_dealer'";
	 		$where_ssu 		= "	AND ms_dealer.id_dealer = '$id_dealer'";

 			$sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm FROM ms_tipe_kendaraan 
				WHERE active = 1 ORDER BY ms_tipe_kendaraan.id_tipe_kendaraan ASC");
		 	foreach ($sql->result() as $row) { 		 		
		 		$dist2 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
						INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
						INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
						INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do				
						WHERE LEFT(tr_do_po.tgl_do,7) = '$tgl_bln'
						$where_dist AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_picking_list_view.retur = 0")->row()->jum;		

		 		$cek_qty = $this->db->query("SELECT COUNT(DISTINCT(tr_scan_barcode.no_mesin)) AS jum FROM tr_penerimaan_unit_dealer 
      			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
      			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
      			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
            WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
      			AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4 $where_qty")->row()->jum;
		 		
				$cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
						INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
						INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
						INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
						WHERE tr_picking_list_view.no_mesin NOT IN 
							(SELECT no_mesin FROM tr_surat_jalan_detail 										
								WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
						AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_picking_list_view.retur = 0 $where_un")->row()->jum;

				$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
    				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
    				INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
            WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
            AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'
            AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' $where_in")->row()->jum;
				
				$e_stock = $cek_qty + $cek_unfill + $cek_in;			

		    $cek_sales_gc = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer,null,null,null,null,null,null,null,"none");
		    $cek_sales_ind = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer,null,null,null,null,null,null,null,null,"none");
		    $jml_total = $cek_sales_gc + $cek_sales_ind;    

		    $b_stock = ($e_stock - $dist2) + $jml_total;

		    if($e_stock==0) $e_stock = "";    
		    if($b_stock==0) $b_stock = "";        
		    if($dist2==0) $dist2 = "";    
		    if($jml_total==0) $jml_total = "";		
				if($cek_sales_ind==0) $cek_sales_ind = "";
				if($cek_sales_gc==0) $cek_sales_gc = "";

		 		if($jml_total > 0 OR $dist2 > 0 OR $e_stock > 0){
			    if($tgl_skr == $cek_akhir_bulan AND $id_dealer != "All"){
			 			$cek_qty = $this->db->query("SELECT * FROM tr_daily WHERE id_dealer = '$id_dealer' AND id_tipe_kendaraan = '$row->id_tipe_kendaraan'
			 				AND tgl = '$tgl_skr'");
						$dat['id_dealer'] = $id_dealer;
						$dat['tgl'] = $tgl_skr;
						$dat['id_tipe_kendaraan'] = $row->id_tipe_kendaraan;
						$dat['qty'] = $b_stock;
			 			if($cek_qty->num_rows() > 0){
			 				$r = $cek_qty->row();
			 				$this->m_admin->update("tr_daily",$dat,"id_daily",$r->id_daily);
			 			}else{
			 				$this->m_admin->insert("tr_daily",$dat);
			 			}
			 		}			 		
			 	}
		 	}
 		}
 	}



 	$no=1; 	
 	$where_reg = "";$where_gc = "";$where_dist="";$where_qty="";$where_in="";$where_un="";
 	$e_stock_t=0;$b_stock_t=0;$jml_total_t=0;$cek_sales_gc_t=0;$cek_sales_ind_t=0;$dist2_t=0;
 	if($id_dealer!="All"){
 		$where_reg 		= " AND tr_sales_order.id_dealer = '$id_dealer'";
 		$where_gc 		= " AND tr_sales_order_gc.id_dealer = '$id_dealer'";
 		$where_dist 	= " AND tr_do_po.id_dealer = '$id_dealer'"; 		
 		$where_qty 		= "	AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'";
 		$where_in 		= "	AND tr_surat_jalan.id_dealer = '$id_dealer'";
 		$where_un 		= "	AND tr_do_po.id_dealer = '$id_dealer'";
 		$where_ssu 		= "	AND ms_dealer.id_dealer = '$id_dealer'";
 		$where_daily 	= "	AND tr_daily.id_dealer = '$id_dealer'";
 	}
	$sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm FROM ms_tipe_kendaraan 
		WHERE active = 1 ORDER BY ms_tipe_kendaraan.id_tipe_kendaraan ASC");
 	foreach ($sql->result() as $row) { 		 		
 		$dist2 = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
				INNER JOIN tr_scan_barcode ON tr_picking_list_view.no_mesin = tr_scan_barcode.no_mesin
				INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
				INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do				
				WHERE LEFT(tr_do_po.tgl_do,7) = '$tgl_bln'
				$where_dist AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_picking_list_view.retur = 0")->row()->jum;		

 		$cek_qty = $this->db->query("SELECT COUNT(DISTINCT(tr_scan_barcode.no_mesin)) AS jum FROM tr_penerimaan_unit_dealer 
                			INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer 
                			INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                			INNER JOIN ms_dealer ON ms_dealer.id_dealer=tr_penerimaan_unit_dealer.id_dealer
                			INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_scan_barcode.tipe_motor
                      WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = 0
                			AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = 4 $where_qty")->row()->jum;
 		
		$cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
								INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
								INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
								INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
								WHERE tr_picking_list_view.no_mesin NOT IN 
									(SELECT no_mesin FROM tr_surat_jalan_detail 										
										WHERE tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya')
								AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_picking_list_view.retur = 0 $where_un")->row()->jum;

		$cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
		      				INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
		      				INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
		              WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
		              AND tr_surat_jalan_detail.retur = 0 AND tr_surat_jalan_detail.ceklist = 'ya'
		              AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' $where_in")->row()->jum;
		

		if($set=='download1'){
			$e_stock = $cek_qty + $cek_unfill + $cek_in;
			
			$cek_sales_gc = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer,null,null,null,null,null,null,null,"none");
	    $cek_sales_ind = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer,null,null,null,null,null,null,null,null,"none");
	    $jml_total = $cek_sales_gc + $cek_sales_ind;    

	    $b_stock = ($e_stock - $dist2) + $jml_total;
			if($bulan_s!=$bulan_sekarang){				
				$cek_stok_ssu = $this->db->query("SELECT count(tr_stok_ssu_tmp.no_mesin) AS jum FROM tr_stok_ssu_tmp 
					INNER JOIN tr_scan_barcode ON tr_stok_ssu_tmp.no_mesin = tr_scan_barcode.no_mesin
					LEFT JOIN ms_dealer ON tr_stok_ssu_tmp.kode_dealer_md = ms_dealer.kode_dealer_md 
					WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND LEFT(tr_stok_ssu_tmp.tanggal,7) = '$tgl_bln'
					$where_ssu");			 
				$jum = ($cek_stok_ssu->num_rows() > 0) ? $cek_stok_ssu->row()->jum : 0 ;			
				$e_stock = $jum;
				$e_stock = ($b_stock - $jml_total) + $dist2;				
			}

		}else{

			
			$cek_sales_gc = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer,null,null,null,null,null,null,null,"none");
	    $cek_sales_ind = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan, $id_dealer,null,null,null,null,null,null,null,null,"none");
	    $jml_total = $cek_sales_gc + $cek_sales_ind;    

			$e_stock = $cek_qty + $cek_unfill + $cek_in;
	    $b_stock = ($e_stock - $dist2) + $jml_total;


			if($bulan_s!=$bulan_sekarang){	
				$bln_lalu    = date('Y-m', strtotime('-1 month', strtotime($bulan))); 			
				$cek_stok_daily = $this->db->query("SELECT tr_daily.qty AS jum FROM tr_daily					
					WHERE tr_daily.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND LEFT(tr_daily.tgl,7) = '$bln_lalu'
					$where_daily");			 
				$jum = ($cek_stok_daily->num_rows() > 0) ? $cek_stok_daily->row()->jum : 0 ;			
				$b_stock = $jum;

				$e_stock = ($b_stock - $jml_total) + $dist2;
			}
		}




    



    if($e_stock==0) $e_stock = "";    
    if($b_stock==0) $b_stock = "";        
    if($dist2==0) $dist2 = "";    
    if($jml_total==0) $jml_total = "";		
		if($cek_sales_ind==0) $cek_sales_ind = "";
		if($cek_sales_gc==0) $cek_sales_gc = "";

 		if($jml_total > 0 OR $dist2 > 0 OR $e_stock > 0){

	    if($tgl_skr == $cek_akhir_bulan AND $id_dealer != "All"){
	 			$cek_qty = $this->db->query("SELECT * FROM tr_daily WHERE id_dealer = '$id_dealer' AND id_tipe_kendaraan = '$row->id_tipe_kendaraan'
	 				AND tgl = '$tgl_skr'");
				$dat['id_dealer'] = $id_dealer;
				$dat['tgl'] = $tgl_skr;
				$dat['id_tipe_kendaraan'] = $row->id_tipe_kendaraan;
				$dat['qty'] = $e_stock;
	 			if($cek_qty->num_rows() > 0){
	 				$r = $cek_qty->row();
	 				$this->m_admin->update("tr_daily",$dat,"id_daily",$r->id_daily);
	 			}else{
	 				$this->m_admin->insert("tr_daily",$dat);
	 			}
	 		}

	 		echo "
	 			<tr>	 				
	 				<td>$row->tipe_ahm ($row->id_tipe_kendaraan)</td> 		
	 				<td align='center'>$b_stock</td>			 				
	 				<td align='center'>$dist2</td>			 				
	 				<td align='center'>$cek_sales_ind</td>			 				
	 				<td align='center'>$cek_sales_gc</td>			 				
	 				<td align='center'>$jml_total</td>			 				
	 				<td align='center'>$e_stock</td>";
	 				for ($i=1; $i <= 31; $i++) { 	 							 					 	
	 					$tgl = sprintf("%'.02d",$i);                                                                     
			 			$tgl_lengkap = substr($bulan,0,7)."-".$tgl;
			 			$hari =date('l', strtotime($tgl_lengkap)); 
			 			$nama_hari = namahari($hari);
			 			$bg = ($nama_hari == 'Minggu') ? "bgcolor='pink'" : "" ;			 			
			 			
			 			$cek_sales = $this->m_admin->get_penjualan_inv('tanggal', $tgl_lengkap, $row->id_tipe_kendaraan, $id_dealer);			 			
			 						 			
				    if($cek_sales==0) $cek_sales = "";				    
						echo "<td align='center' $bg>$cek_sales</td>";
					}
					echo "			 					 						
	 			</tr>
	 		";	 	
	 		$b_stock_t += $b_stock;	
	 		$e_stock_t += $e_stock;	
	 		$dist2_t += $dist2;	
	 		$cek_sales_gc_t += $cek_sales_gc;	
	 		$cek_sales_ind_t += $cek_sales_ind;	
	 		$jml_total_t += $jml_total;	
	 	}
 	}
 	?>
 	<tr bgcolor="yellow">
 		<td>Total</td>
 		<td align="center"><?php echo $b_stock_t ?></td>
 		<td align="center"><?php echo $dist2_t ?></td>
 		<td align="center"><?php echo $cek_sales_ind_t ?></td>
 		<td align="center"><?php echo $cek_sales_gc_t ?></td>
 		<td align="center"><?php echo $jml_total_t ?></td>
 		<td align="center"><?php echo $e_stock_t ?></td>
 		<?php 
 		for ($i=1; $i <= 31; $i++) { 	 							 					 	
			$tgl = sprintf("%'.02d",$i);                                                                     
 			$tgl_lengkap = substr($bulan,0,7)."-".$tgl;
 			$hari =date('l', strtotime($tgl_lengkap)); 
 			$nama_hari = namahari($hari);
 			$bg = ($nama_hari == 'Minggu') ? "bgcolor='pink'" : "" ;			 			
 			
 			$cek_sales2 = $this->m_admin->get_penjualan_inv('tanggal', $tgl_lengkap, null, $id_dealer);			 			
 						 			
	    //if($cek_sales2==0) $cek_sales2 = "";
			echo "<td align='center' $bg>$cek_sales2</td>";
		}
 		?> 		
 	</tr>
</table>
