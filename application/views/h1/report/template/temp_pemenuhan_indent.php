<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PemenuhanIndent_".$no.".xls");
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
 		<td align="center">Status</td>
 		<td align="center">No Indent</td>
 		<td align="center">Tgl Indent</td>
 		<td align="center">Nama Konsumen di STNK</td>
 		<td align="center">Alamat</td>
 		<td align="center">No KTP</td> 		
 		<td align="center">No Telp/HP</td> 		
 		<td align="center">Tipe</td> 		
 		<td align="center">Kode Item</td> 		
 		<td align="center">Warna</td> 		
 		<td align="center">Nama Dealer</td> 		
 		<td align="center">Keterangan</td> 		
 		<td align="center">Area</td> 		
 		<td align="center">Tanda Jadi (Rp.)</td> 		
 		<td align="center">Tgl Pemenuhan</td> 		
 		<td align="center">Tgl Close</td> 		
 		<td align="center">No DO</td> 		
 		<td align="center">Tgl SJ</td> 		
 		<td align="center">No SJ</td> 		
 		<td align="center">No Mesin</td> 		
 		<td align="center">Validasi Nama Konsumen</td> 		
 		<td align="center">Validasi No KTP</td> 		 		
 	</tr>
 	<?php 
 	$sql = $this->db->query("SELECT * FROM tr_po_dealer_indent INNER JOIN ms_dealer ON tr_po_dealer_indent.id_dealer = ms_dealer.id_dealer
 		LEFT JOIN ms_tipe_kendaraan ON tr_po_dealer_indent.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
 		LEFT JOIn ms_warna ON tr_po_dealer_indent.id_warna = ms_warna.id_warna
 		LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
 		LEFT JOIn ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
 		LEFT JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
 	 	WHERE tr_po_dealer_indent.tgl BETWEEN '$tgl1' AND '$tgl2'");
 	foreach ($sql->result() as $isi) {
 		$sql2 = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'");
 		$id_item = ($sql2->num_rows() > 0) ? $sql2->row()->id_item : "" ;
 		$cek = $this->db->query("SELECT * FROM tr_do_indent_detail INNER JOIN tr_do_indent ON tr_do_indent_detail.no_do = tr_do_indent.no_do
 			WHERE tr_do_indent_detail.id_indent = '$isi->id_indent'");
 		$no_sj="";$tgl_surat="";$no_do="";$no_mesin="";
 		if($cek->num_rows() > 0){
 			$no_do = $cek->row()->no_do;
 			$cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
 				WHERE tr_picking_list.no_do = '$no_do'");
 			if($cek_sj->num_rows() > 0){
 				$no_sj = $cek_sj->row()->no_surat_jalan;
 				$tgl_surat = $cek_sj->row()->tgl_surat;
 				$cek_no = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_surat_jalan = '$no_sj' AND id_item = '$id_item'");
 				$no_mesin = ($cek_no->num_rows() > 0) ? $cek_no->row()->no_mesin : "" ;
 			}
 		} 

 		echo "
 		<tr>
 			<td>$isi->status</td>
 			<td>$isi->id_spk</td>
 			<td>$isi->tgl</td>
 			<td>$isi->nama_konsumen</td>
 			<td>$isi->alamat</td>
 			<td>$isi->no_ktp</td>
 			<td>$isi->no_telp</td>
 			<td>$isi->tipe_ahm</td>
 			<td>$id_item</td>
 			<td>$isi->warna</td>
 			<td>$isi->nama_dealer</td>
 			<td>$isi->ket</td>
 			<td>$isi->kabupaten</td>
 			<td>".mata_uang($isi->nilai_dp)."</td>
 			<td>$isi->tgl_cetak_kwitansi</td>
 			<td></td>
 			<td>$no_do</td>
 			<td>$tgl_surat</td>
 			<td>$no_sj</td>
 			<td>$no_mesin</td>
 			<td></td>
 			<td></td>
 		</tr>
 		";
 	}
 	?>
</table>