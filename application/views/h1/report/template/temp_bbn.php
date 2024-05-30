<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report_BBN.xls");
header("Pragma: no-cache");
header("Expires: 0");
function mata_uang($a){
	if(is_numeric($a) AND $a != 0 AND $a != ""){
		return number_format($a, 0, ',', '.');
	}else{
		return $a;
	}
}
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Kode Tipe Kendaraan</td> 		 		
 		<td align="center">Tipe AHM</td>
 		<td align="center">Tipe Kendaraan</td> 		 		
 		<td align="center">Biaya BBN Dealer MD</td> 		 		 		
 		<td align="center">Biaya Instansi Dealer MD</td> 		 		 		
 		<td align="center">Tahun Produksi</td> 		 		 		
 		<td align="center">Biaya BBN MD Samsat</td> 		 		 		
 		<td align="center">Biaya Instansi MD Samsat</td> 		 		 		 		
 	</tr>
 	<?php  	
 	$no=1;
 	$sql = $this->db->query("SELECT * FROM ms_bbn_biro 
 		INNER JOIN ms_tipe_kendaraan ON ms_bbn_biro.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 		ORDER BY ms_tipe_kendaraan.id_tipe_kendaraan ASC");
 	foreach ($sql->result() as $isi) {
 		$cek = $this->m_admin->getByID("ms_bbn_dealer","id_tipe_kendaraan",$isi->id_tipe_kendaraan);
 		$biaya_bbn = ($cek->num_rows() > 0) ? $cek->row()->biaya_bbn : "" ;
 		$biaya_instansi = ($cek->num_rows() > 0) ? $cek->row()->biaya_instansi : "" ;
 		echo "
 		<tr>
 			<td>$no</td>
 			<td>$isi->id_tipe_kendaraan</td>
 			<td>$isi->deskripsi_ahm</td>
 			<td>$isi->tipe_ahm</td>
 			<td align='right'>".mata_uang($isi->biaya_bbn)."</td>
 			<td align='right'>".mata_uang($isi->biaya_instansi)."</td>
 			<td>$isi->tahun_produksi</td>
 			<td align='right'>".mata_uang($biaya_bbn)."</td>
 			<td align='right'>".mata_uang($biaya_instansi)."</td>
 		</tr>
 		";
 		$no++;
 	}
 	?>
</table>
