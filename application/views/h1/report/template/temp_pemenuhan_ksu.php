<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PenerimaanBPKB_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Kode KSU</td>
    <td align="center">KSU</td>
    <td align="center">No Surat Jalan</td>    
    <td align="center">Tgl Surat Jalan</td>    
    <td align="center">No DO</td>    
    <td align="center">Qty</td>    
    <td align="center">Dealer Tujuan</td>    
    <td align="center">Keterangan</td>    
    <td align="center">Tgl Pemenuhan</td>    
    <td align="center">No SJ Pemenuhan</td>    
    <td align="center">Qty Pemenuhan</td>    
    <td align="center">Sisa Hutang</td>        
 	</tr>
 	<?php  	 	  
  $sql = $this->db->query("SELECT * FROM tr_surat_jalan_ksu 
    INNER JOIN tr_surat_jalan ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan.no_surat_jalan
    LEFT JOIN ms_ksu ON tr_surat_jalan_ksu.id_ksu = ms_ksu.id_ksu
    LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
    LEFT JOIN tr_surat_jalan_ksu_pl ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_ksu_pl.no_surat_jalan
    LEFT JOIN tr_mon_ksu_detail ON tr_surat_jalan_ksu_pl.no_pl_ksu = tr_mon_ksu_detail.no_pl_ksu    
    WHERE tr_surat_jalan.tgl_surat BETWEEN '$tgl1' AND '$tgl2'");  
  foreach ($sql->result() as $isi) {
    echo "
    <tr>
      <td>$isi->id_ksu</td>          
      <td>$isi->ksu</td>          
      <td>$isi->no_surat_jalan</td>          
      <td>$isi->tgl_surat</td>          
      <td>$isi->no_do</td>          
      <td>$isi->qty</td>          
      <td>$isi->nama_dealer</td>          
      <td>$isi->ket</td>          
      <td>$isi->no_sj_outstanding_ksu</td>          
      <td>$isi->tgl_sj_outstanding_ksu</td>          
      <td>$isi->qty_konfirmasi</td>          
      <td>".$hasil = $isi->qty - $isi->qty_konfirmasi."</td>          
    </tr>
    ";
  }
 	?>
</table>
 