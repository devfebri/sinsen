<?php 
$no = date("dmyhis");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=data_kualitas_akibat_trans_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
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
?>
<table border="1">  
 	<tr> 		
 		<td align="center">No</td>
 		<td align="center">Bulan</td>
 		<td align="center">Main Dealer</td> 		
 		<td align="center">Nama Ekspedisi</td> 		
 		<td align="center">No Polisi</td> 		 		
 		<td align="center">No Penerimaan</td> 		
 		<td align="center">Tgl</td> 		
 		<td align="center">No Mesin</td> 		
 		<td align="center">No Rangka</td> 		 		
 		<td align="center">Kode Item</td> 		 		
 		<td align="center">Plan</td> 		 		
 		<td align="center">Part Masalah</td> 		 		
 		<td align="center">Gejala</td> 		 		
 		<td align="center">Penyebab</td> 		 		
 		<td align="center">Pengatasan</td> 		 		
 		<td align="center">Keterangan</td> 		 		
 		<td align="center">Nama Kapal</td> 		 		
 	</tr>
 	<?php 
 	$no=1; 	 	
 	
 	foreach ($sql->result() as $isi) { 		
 		$penerimaan = $this->db->query("SELECT * FROM tr_penerimaan_unit 
 			INNER JOIN tr_penerimaan_unit_detail ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit 			
 			LEFT JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi = ms_vendor.id_vendor
 			WHERE tr_penerimaan_unit_detail.no_shipping_list = '$isi->no_shipping_list'");
 		$no_penerimaan = ($penerimaan->num_rows() > 0) ? $penerimaan->row()->id_penerimaan_unit:"";
 		$tgl_penerimaan = ($penerimaan->num_rows() > 0) ? $penerimaan->row()->tgl_penerimaan:"";
 		$vendor_name = ($penerimaan->num_rows() > 0) ? $penerimaan->row()->vendor_name:"";
 		$no_polisi = ($penerimaan->num_rows() > 0) ? $penerimaan->row()->no_polisi:"";
 		$scan = $this->m_admin->getByid("tr_scan_barcode","no_mesin",$isi->no_mesin);
 		$no_rangka = ($scan->num_rows() > 0) ? $scan->row()->no_rangka:"";
 		$id_item = ($scan->num_rows() > 0) ? $scan->row()->id_item:"";

 		$cek_kapal = $this->m_admin->getByid("tr_fkb","no_mesin_spasi",$isi->no_mesin);
 		$kapal = ($cek_kapal->num_rows() > 0) ? $cek_kapal->row()->nama_kapal:"";

		echo "
		<tr>
			<td>$no</td>
			<td>".bln($isi->bulan)."</td>
			<td>PT. SINAR SENTOSA PRIMATAMA</td>
			<td>$vendor_name</td>
			<td>$no_polisi</td>
			<td>$no_penerimaan</td>
			<td>$tgl_penerimaan</td>
			<td>$isi->no_mesin</td>
			<td>$no_rangka</td>
			<td>$id_item</td>
			<td>1350</td>
			<td>$isi->nama_part</td>
			<td>$isi->gejala</td>
			<td>$isi->penyebab</td>
			<td>$isi->pengatasan</td>
			<td>$isi->ket</td>
			<td>$kapal</td>
		</tr>
		";
		$no++;	 	 	
	}
 	?>
</table>
