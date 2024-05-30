<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PengeluaranKSU_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">No Surat Jalan</td>
    <td align="center">Tgl Surat Jalan</td>
 		<td align="center">No Do</td>
    <td align="center">Kode KSU</td>         
    <td align="center">KSU</td>        
    <td align="center">Qty</td>         
    <td align="center">Lokasi</td>        
    <td align="center">Dealer Tujuan</td>         
    <td align="center">Keterangan</td>        
 		<td align="center">Status</td> 		 		
 	</tr>
 	<?php  	
 	$no=1;
  $where = "";
  if($id_ksu!="") $where = "AND tr_surat_jalan_ksu.id_ksu = '$id_ksu'";
  $sql = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_ksu ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_ksu.no_surat_jalan
    LEFT JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
    LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
    WHERE tr_surat_jalan.tgl_surat BETWEEN '$tgl1' AND '$tgl2' $where");
  foreach ($sql->result() as $row) {
    echo "
    <tr>
      <td>$no</td>
      <td>$row->no_surat_jalan</td>
      <td>$row->tgl_surat</td>
      <td>$row->no_do</td>
      <td>$row->id_ksu</td>
      <td>$row->ksu</td>
      <td>$row->qty_do</td>
      <td></td>
      <td>$row->nama_dealer</td>
      <td>$row->ket</td>
      <td>Regular</td>
    </tr>
    ";
    $no++;
  }
 	?>
</table>
