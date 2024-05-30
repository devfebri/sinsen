<?php 
$no = $bulan."-".$tahun;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=PerformaEkspedisi_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
  <tr>
    <td>Main Dealer</td>
    <td>: Sinar Sentosa Primatama</td>
  </tr>
  <tr>
    <td>Month</td>
    <td>: <?php 
    $bln = sprintf("%'.02d",$bulan);
    echo $bln."-".$tahun ?></td>
  </tr>
</table>
<table border="1">  
 	<tr> 		 		
    <td rowspan="2" align="center">Target</td>
    <?php 
    for ($i=1; $i <= 31; $i++) { 
      echo "
 		   <td colspan='4' align='center'>$i</td>"; 		    
    }
    ?>
 	</tr>
  <tr>
    <?php 
    for ($i=1; $i <= 31; $i++) { 
      $tgl = sprintf("%'.02d",$i);                                                                           
      $tgl_sipb = $tgl.$bln.$tahun;
      $cek_sipb = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb WHERE tgl_sipb = '$tgl_sipb'");
      $sipb = ($cek_sipb->row()->jum > 0) ? $cek_sipb->row()->jum : 0 ;                      
      echo "
       <td colspan='4' align='center'>$sipb</td>";         
    }
    ?>
  </tr>
  <tr>
    <td rowspan="2" align="center">Actual</td>
    <?php 
    for ($i=1; $i <= 31; $i++) { 
      echo "
        <td align='center'>RJTM</td>
        <td align='center'>TBMA</td>
        <td align='center'>TM</td>
        <td align='center'>SLPL</td>";         
    }
    ?>    
  </tr> 
  <tr>
    <?php 
    for ($i=1; $i <= 31; $i++) { 
      $tgl = sprintf("%'.02d",$i);                                                                     
      $tgl_sl = $tgl.$bln.$tahun;            
      $sql = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list 
        INNER JOIN ms_unit_transporter ON REPLACE(tr_shipping_list.no_pol_eks,' ','') = REPLACE(ms_unit_transporter.no_polisi,' ','')
        WHERE ms_unit_transporter.id_vendor = 'RJTM' AND tr_shipping_list.tgl_sl = '$tgl_sl'");
      $sql2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list 
        INNER JOIN ms_unit_transporter ON REPLACE(tr_shipping_list.no_pol_eks,' ','') = REPLACE(ms_unit_transporter.no_polisi,' ','')
        WHERE ms_unit_transporter.id_vendor = 'TBMA' AND tr_shipping_list.tgl_sl = '$tgl_sl'");
      $sql3 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list 
        INNER JOIN ms_unit_transporter ON REPLACE(tr_shipping_list.no_pol_eks,' ','') = REPLACE(ms_unit_transporter.no_polisi,' ','')
        WHERE ms_unit_transporter.id_vendor = 'TM' AND tr_shipping_list.tgl_sl = '$tgl_sl'");
       $sql4 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list 
        INNER JOIN ms_unit_transporter ON REPLACE(tr_shipping_list.no_pol_eks,' ','') = REPLACE(ms_unit_transporter.no_polisi,' ','')
        WHERE ms_unit_transporter.id_vendor = 'EX2071' AND tr_shipping_list.tgl_sl = '$tgl_sl'");
     
      echo "
        <td align='center'>".$sql->row()->jum."</td>
        <td align='center'>".$sql2->row()->jum."</td>
        <td align='center'>".$sql3->row()->jum."</td>
        <td align='center'>".$sql4->row()->jum."</td>";
    }
    ?>
  </tr>
</table>
