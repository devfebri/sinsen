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

$no = date('mdhs');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report Sales Comparison Result - ".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>      

<h3>Laporan Perbandingan Data Penjualan Tgl <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></h3>
<table border="1">
	<tr>
		<td bgcolor='yellow' align='center' rowspan="2">Nama Dealer</td>
		<?php 
		$isi=0;
		if($bulan_1!='') $isi++;		
		if($bulan_2!='') $isi++;		
		if($bulan_3!='') $isi++;		
		if($bulan_4!='') $isi++;		
		if($bulan_5!='') $isi++;		
		if($bulan_6!='') $isi++;		
		if($bulan_7!='') $isi++;		
		if($bulan_8!='') $isi++;		
		if($bulan_9!='') $isi++;		
		if($bulan_10!='') $isi++;		
		if($bulan_11!='') $isi++;		
		if($bulan_12!='') $isi++;

		$sql_2 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
		//$sql_2 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
		foreach ($sql_2->result() as $row) {
			echo "<td colspan='$isi' bgcolor='yellow' align='center'>$row->deskripsi_ahm</td>";
		}
		?>		
	</tr>
	<tr>
		<?php  		
		$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
		//$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
		foreach ($sql_1->result() as $row) {			
			if($bulan_1!=''){			
				$bln_1 = substr(bln($bulan_1),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
			}
			if($bulan_2!=''){			
				$bln_2 = substr(bln($bulan_2),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
			}							

			if($bulan_3!=''){			
				$bln_3 = substr(bln($bulan_3),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
			}
			if($bulan_4!=''){			
				$bln_4 = substr(bln($bulan_4),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
			}							

			if($bulan_5!=''){			
				$bln_5 = substr(bln($bulan_5),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
			}
			if($bulan_6!=''){			
				$bln_6 = substr(bln($bulan_6),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
			}							

			if($bulan_7!=''){			
				$bln_7 = substr(bln($bulan_7),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
			}
			if($bulan_8!=''){			
				$bln_8 = substr(bln($bulan_8),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
			}							

			if($bulan_9!=''){			
				$bln_9 = substr(bln($bulan_9),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
			}
			if($bulan_10!=''){			
				$bln_10 = substr(bln($bulan_10),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
			}							

			if($bulan_11!=''){			
				$bln_11 = substr(bln($bulan_11),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
			}
			if($bulan_12!=''){			
				$bln_12 = substr(bln($bulan_12),0,3);
				echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
			}							
		}
		?>
	</tr>
	<?php  
	$sql_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500 ORDER BY kabupaten ASC");
	foreach ($sql_kab->result() as $amb) {	
		$dealer = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'");
		foreach ($dealer->result() as $dea) {
			$j1=0;$j2=0;$j3=0;$j4=0;$j5=0;$j6=0;$j7=0;$j8=0;$j9=0;$j10=0;$j11=0;$j12=0;
			echo "
				<tr>
					<td>$dea->nama_dealer</td>";					  	
					//$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
					$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
					foreach ($sql_1->result() as $row) {			
						

						if($bulan_1!=''){			

							$bln = sprintf("%'.02d",$bulan_1);                            
	            $tgl_surat_1 = $tahun_1."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
	              else $jumlah_jual1 = 0;
	            $j1 += $jumlah_jual1;
							$bln_1 = substr(bln($bulan_1),0,3);
							echo "<td align='center'>$jumlah_jual1</td>";
						}
						if($bulan_2!=''){		
							$bln = sprintf("%'.02d",$bulan_2);                            
	            $tgl_surat_1 = $tahun_2."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual2 = $cek_so2;
	              else $jumlah_jual2 = 0;
	            $j2 += $jumlah_jual2;
							$bln_2 = substr(bln($bulan_2),0,3);
							echo "<td align='center'>$jumlah_jual2</td>";
						}			
						if($bulan_3!=''){			
							$bln = sprintf("%'.02d",$bulan_3);                            
	            $tgl_surat_1 = $tahun_3."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual3 = $cek_so2;
	              else $jumlah_jual3 = 0;
	            $j3 += $jumlah_jual3;
							$bln_3 = substr(bln($bulan_3),0,3);
							echo "<td align='center'>$jumlah_jual3</td>";
						}
						if($bulan_4!=''){		
							$bln = sprintf("%'.02d",$bulan_4);                            
	            $tgl_surat_1 = $tahun_4."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual4 = $cek_so2;
	              else $jumlah_jual4 = 0;
	            $j4 += $jumlah_jual4;
							$bln_4 = substr(bln($bulan_4),0,3);
							echo "<td align='center'>$jumlah_jual4</td>";
						}							

						if($bulan_5!=''){			
							$bln = sprintf("%'.02d",$bulan_5);                            
	            $tgl_surat_1 = $tahun_5."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual5 = $cek_so2;
	              else $jumlah_jual5 = 0;
	            $j5 += $jumlah_jual5;
							$bln_5 = substr(bln($bulan_5),0,3);
							echo "<td align='center'>$jumlah_jual5</td>";
						}
						if($bulan_6!=''){			
							$bln = sprintf("%'.02d",$bulan_6);                            
	            $tgl_surat_1 = $tahun_6."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual6 = $cek_so2;
	              else $jumlah_jual6 = 0;
	            $j6 += $jumlah_jual6;
							$bln_6 = substr(bln($bulan_6),0,3);
							echo "<td align='center'>$jumlah_jual6</td>";
						}							

						if($bulan_7!=''){			
							$bln = sprintf("%'.02d",$bulan_7);                            
	            $tgl_surat_1 = $tahun_7."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual7 = $cek_so2;
	              else $jumlah_jual7 = 0;
	            $j7 += $jumlah_jual7;
							$bln_7 = substr(bln($bulan_7),0,3);
							echo "<td align='center'>$jumlah_jual7</td>";
						}
						if($bulan_8!=''){			
							$bln = sprintf("%'.02d",$bulan_8);                            
	            $tgl_surat_1 = $tahun_8."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual8 = $cek_so2;
	              else $jumlah_jual8 = 0;
	            $j8 += $jumlah_jual8;
							$bln_8 = substr(bln($bulan_8),0,3);
							echo "<td align='center'>$jumlah_jual8</td>";
						}							

						if($bulan_9!=''){			
							$bln = sprintf("%'.02d",$bulan_9);                            
	            $tgl_surat_1 = $tahun_9."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual9 = $cek_so2;
	              else $jumlah_jual9 = 0;
	            $j9 += $jumlah_jual9;
							$bln_9 = substr(bln($bulan_9),0,3);
							echo "<td align='center'>$jumlah_jual9</td>";
						}
						if($bulan_10!=''){			
							$bln = sprintf("%'.02d",$bulan_10);                            
	            $tgl_surat_1 = $tahun_10."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual10 = $cek_so2;
	              else $jumlah_jual10 = 0;
	            $j10 += $jumlah_jual10;
							$bln_10 = substr(bln($bulan_10),0,3);
							echo "<td align='center'>$jumlah_jual10</td>";
						}							

						if($bulan_11!=''){			
							$bln = sprintf("%'.02d",$bulan_11);                            
	            $tgl_surat_1 = $tahun_11."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual11 = $cek_so2;
	              else $jumlah_jual11 = 0;
							$j11 += $jumlah_jual11;
							$bln_11 = substr(bln($bulan_11),0,3);
							echo "<td align='center'>$jumlah_jual11</td>";
						}
						if($bulan_12!=''){			
							$bln = sprintf("%'.02d",$bulan_12);                            
	            $tgl_surat_1 = $tahun_12."-".$bln;
	            $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
	              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
	              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_sales_order.id_dealer = '$dea->id_dealer'")->row()->jum;
	            if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual12 = $cek_so2;
	              else $jumlah_jual12 = 0;
							$j12 += $jumlah_jual12;
							$bln_12 = substr(bln($bulan_12),0,3);
							echo "<td align='center'>$jumlah_jual12</td>";
						}					
					}					
				echo "
				</tr>
			";
		}
	?>

		<tr>
			<td bgcolor='yellow'>Sub Total <?php echo $amb->kabupaten ?></td>
			<?php  		
			//$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
			$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
			foreach ($sql_1->result() as $row) {			
				if($bulan_1!=''){			
					$bln = sprintf("%'.02d",$bulan_1);                            
          $tgl_surat_1 = $tahun_1."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
            else $jumlah_jual1 = 0;

					$bln_1 = substr(bln($bulan_1),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual1</td>";
				}
				if($bulan_2!=''){			
					$bln = sprintf("%'.02d",$bulan_2);                            
          $tgl_surat_1 = $tahun_2."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual2 = $cek_so2;
            else $jumlah_jual2 = 0;

					$bln_2 = substr(bln($bulan_2),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual2</td>";
				}						
				if($bulan_3!=''){
					$bln = sprintf("%'.02d",$bulan_3);                            
          $tgl_surat_1 = $tahun_3."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual3 = $cek_so2;
            else $jumlah_jual3 = 0;

					$bln_3 = substr(bln($bulan_3),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual3</td>";
				}
				if($bulan_4!=''){			
					$bln = sprintf("%'.02d",$bulan_4);                            
          $tgl_surat_1 = $tahun_4."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual4 = $cek_so2;
            else $jumlah_jual4 = 0;

					$bln_4 = substr(bln($bulan_4),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual4</td>";
				}							

				if($bulan_5!=''){			
					$bln = sprintf("%'.02d",$bulan_5);                            
          $tgl_surat_1 = $tahun_5."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual5 = $cek_so2;
            else $jumlah_jual5 = 0;

					$bln_5 = substr(bln($bulan_5),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual5</td>";
				}
				if($bulan_6!=''){			
					$bln = sprintf("%'.02d",$bulan_6);                            
          $tgl_surat_1 = $tahun_6."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual6 = $cek_so2;
            else $jumlah_jual6 = 0;

					$bln_6 = substr(bln($bulan_6),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual6</td>";
				}							

				if($bulan_7!=''){			
					$bln = sprintf("%'.02d",$bulan_7);                            
          $tgl_surat_1 = $tahun_7."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual7 = $cek_so2;
            else $jumlah_jual7 = 0;

					$bln_7 = substr(bln($bulan_7),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual7</td>";
				}
				if($bulan_8!=''){			
					$bln = sprintf("%'.02d",$bulan_8);                            
          $tgl_surat_1 = $tahun_8."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual8 = $cek_so2;
            else $jumlah_jual8 = 0;

					$bln_8 = substr(bln($bulan_8),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual8</td>";
				}							

				if($bulan_9!=''){		
					$bln = sprintf("%'.02d",$bulan_9);                            
          $tgl_surat_1 = $tahun_9."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual9 = $cek_so2;
            else $jumlah_jual9 = 0;

					$bln_9 = substr(bln($bulan_9),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual9</td>";
				}
				if($bulan_10!=''){	
					$bln = sprintf("%'.02d",$bulan_10);                            
          $tgl_surat_1 = $tahun_10."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual10 = $cek_so2;
            else $jumlah_jual10 = 0;

					$bln_10 = substr(bln($bulan_10),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual10</td>";
				}							

				if($bulan_11!=''){		
					$bln = sprintf("%'.02d",$bulan_11);                            
          $tgl_surat_1 = $tahun_11."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual11 = $cek_so2;
            else $jumlah_jual11 = 0;

					$bln_11 = substr(bln($bulan_11),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual11</td>";
				}
				if($bulan_12!=''){			
					$bln = sprintf("%'.02d",$bulan_12);                            
          $tgl_surat_1 = $tahun_12."-".$bln;
          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
            INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
            INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
	          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
	          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
	          WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$amb->id_kabupaten'
            AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual12 = $cek_so2;
            else $jumlah_jual12 = 0;
					$bln_12 = substr(bln($bulan_12),0,3);
					echo "<td align='center' bgcolor='yellow'>$jumlah_jual12</td>";
				}	
			}
			?>
		</tr>
	<?php 
	}
	?>
	<tr>
		<td bgcolor='pink'>Grand Total</td>
		<?php  		
		//$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
		$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
		foreach ($sql_1->result() as $row) {			
			if($bulan_1!=''){			
				$bln = sprintf("%'.02d",$bulan_1);                            
        $tgl_surat_1 = $tahun_1."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
          else $jumlah_jual1 = 0;

				$bln_1 = substr(bln($bulan_1),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual1</td>";
			}
			if($bulan_2!=''){			
				$bln = sprintf("%'.02d",$bulan_2);                            
        $tgl_surat_1 = $tahun_2."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual2 = $cek_so2;
          else $jumlah_jual2 = 0;

				$bln_2 = substr(bln($bulan_2),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual2</td>";
			}						
			if($bulan_3!=''){
				$bln = sprintf("%'.02d",$bulan_3);                            
        $tgl_surat_1 = $tahun_3."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual3 = $cek_so2;
          else $jumlah_jual3 = 0;

				$bln_3 = substr(bln($bulan_3),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual3</td>";
			}
			if($bulan_4!=''){			
				$bln = sprintf("%'.02d",$bulan_4);                            
        $tgl_surat_1 = $tahun_4."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual4 = $cek_so2;
          else $jumlah_jual4 = 0;

				$bln_4 = substr(bln($bulan_4),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual4</td>";
			}							

			if($bulan_5!=''){			
				$bln = sprintf("%'.02d",$bulan_5);                            
        $tgl_surat_1 = $tahun_5."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual5 = $cek_so2;
          else $jumlah_jual5 = 0;

				$bln_5 = substr(bln($bulan_5),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual5</td>";
			}
			if($bulan_6!=''){			
				$bln = sprintf("%'.02d",$bulan_6);                            
        $tgl_surat_1 = $tahun_6."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual6 = $cek_so2;
          else $jumlah_jual6 = 0;

				$bln_6 = substr(bln($bulan_6),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual6</td>";
			}							

			if($bulan_7!=''){			
				$bln = sprintf("%'.02d",$bulan_7);                            
        $tgl_surat_1 = $tahun_7."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual7 = $cek_so2;
          else $jumlah_jual7 = 0;

				$bln_7 = substr(bln($bulan_7),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual7</td>";
			}
			if($bulan_8!=''){			
				$bln = sprintf("%'.02d",$bulan_8);                            
        $tgl_surat_1 = $tahun_8."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual8 = $cek_so2;
          else $jumlah_jual8 = 0;

				$bln_8 = substr(bln($bulan_8),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual8</td>";
			}							

			if($bulan_9!=''){		
				$bln = sprintf("%'.02d",$bulan_9);                            
        $tgl_surat_1 = $tahun_9."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual9 = $cek_so2;
          else $jumlah_jual9 = 0;

				$bln_9 = substr(bln($bulan_9),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual9</td>";
			}
			if($bulan_10!=''){	
				$bln = sprintf("%'.02d",$bulan_10);                            
        $tgl_surat_1 = $tahun_10."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual10 = $cek_so2;
          else $jumlah_jual10 = 0;

				$bln_10 = substr(bln($bulan_10),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual10</td>";
			}							

			if($bulan_11!=''){		
				$bln = sprintf("%'.02d",$bulan_11);                            
        $tgl_surat_1 = $tahun_11."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual11 = $cek_so2;
          else $jumlah_jual11 = 0;

				$bln_11 = substr(bln($bulan_11),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual11</td>";
			}
			if($bulan_12!=''){			
				$bln = sprintf("%'.02d",$bulan_12);                            
        $tgl_surat_1 = $tahun_12."-".$bln;
        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
          INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
          INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
          INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
          INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
          WHERE ms_dealer.active = 1 
          AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row()->jum;
        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual12 = $cek_so2;
          else $jumlah_jual12 = 0;
				$bln_12 = substr(bln($bulan_12),0,3);
				echo "<td align='center' bgcolor='pink'>$jumlah_jual12</td>";
			}	
		}
		?>
	</tr>
</table>	

