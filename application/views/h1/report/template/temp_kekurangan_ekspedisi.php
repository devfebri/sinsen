<?php 
$no = $id_vendor."-".$tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=KekuranganEkspedisi_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">No</td>
 		<td align="center">Tgl Surat Jalan</td>
    <td align="center">Tgl SL</td>
 		<td align="center">Tgl Receive</td>
    <td align="center">Ekspedisi</td>         
    <td align="center">No Polisi</td>        
    <td align="center">Driver</td>         
    <td align="center">No Mesin</td>            
    <td align="center">Kode Part</td>            
    <td align="center">Deskripsi Part</td>            
    <td align="center">Keterangan</td>            
    <td align="center">Qty</td>            
    <td align="center">Harga</td>            
    <td align="center">Biaya Pasang</td>            
    <td align="center">Total</td>            
    <td align="center">No Wo</td>                
 	</tr>
 	<?php  	
 	$no=1;
  $where = "";
  if($id_vendor!='') $where = "AND tr_penerimaan_unit.ekspedisi = '$id_vendor'";  
  if($tgl3!=''){
    $tgl_a = substr($tgl3, 8,2);
    $tgl_b = substr($tgl3, 5,2);
    $tgl_c = substr($tgl3, 0,4);
    $tanggal_a = $tgl_a.$tgl_b.$tgl_c;

    $tgl_d = substr($tgl4, 8,2);
    $tgl_e = substr($tgl4, 5,2);
    $tgl_f = substr($tgl5, 0,4);
    $tanggal_b = $tgl_d.$tgl_e.$tgl_f;
    $where .= "AND tr_shipping_list.tgl_sl BETWEEN '$tanggal_a' AND '$tanggal_b'";
  }
  //if($id_vendor!='') $where = "AND tr_penerimaan_unit.ekspedisi = '$id_vendor'";  
  $sql = $this->db->query("SELECT * FROM tr_checker INNER JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
    INNER JOIN tr_shipping_list ON tr_checker_detail.no_mesin = tr_shipping_list.no_mesin
    LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
    WHERE tr_checker.tgl_checker BETWEEN '$tgl1' AND '$tgl2'
    $where");
  foreach ($sql->result() as $row) {    

    $sql4 = $this->db->query("SELECT * FROM tr_penerimaan_unit INNER JOIN tr_penerimaan_unit_detail ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
      LEFT JOIN ms_vendor ON tr_penerimaan_unit.ekspedisi = ms_vendor.id_vendor
      WHERE tr_penerimaan_unit_detail.no_shipping_list='$row->no_shipping_list'");
    $tgl_surat_jalan = ($sql4->num_rows() > 0) ? $sql4->row()->tgl_surat_jalan : "" ;
    $tgl_receive = ($sql4->num_rows() > 0) ? $sql4->row()->tgl_penerimaan : "" ;
    $ekspedisi = ($sql4->num_rows() > 0) ? $sql4->row()->vendor_name : "" ;
    $no_polisi = ($sql4->num_rows() > 0) ? $sql4->row()->no_polisi : "" ;
    $nama_driver = ($sql4->num_rows() > 0) ? $sql4->row()->nama_driver : "" ;

    $sql3 = $this->m_admin->getByID("tr_wo","id_checker",$row->id_checker);
    $no_wo = ($sql3->num_rows() > 0) ? $sql3->row()->no_wo : "" ;
    $harga_jasa = ($row->harga_jasa != "") ? $row->harga_jasa : "0" ;
    $biaya_pasang = $harga_jasa + $row->ongkos_kerja;
    echo "
    <tr>
      <td>$no</td>            
      <td>$tgl_surat_jalan</td>            
      <td>$row->tgl_sl</td>            
      <td>$tgl_receive</td>            
      <td>$ekspedisi</td>            
      <td>$no_polisi</td>            
      <td>$nama_driver</td>            
      <td>$row->no_mesin</td>            
      <td>$row->id_part</td>            
      <td>$row->nama_part</td>            
      <td>$row->ket</td>            
      <td>$row->qty_order</td>            
      <td>$row->harga_md_dealer</td>            
      <td>$biaya_pasang</td>            
      <td>".$total = $biaya_pasang + $row->harga_md_dealer."</td>            
      <td>$no_wo</td>            
    </tr>
    ";  
    $no++;
  }
 	?>
</table>
 