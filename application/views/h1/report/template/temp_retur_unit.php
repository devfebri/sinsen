<?php 
$no = $tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ReturUnit_".$no.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
    <td align="center">No</td>
 		<td align="center">Tgl Retur</td>
 		<td align="center">Dealer Asal</td>
    <td align="center">Tgl Distribusi Asal</td>
 		<td align="center">No Surat Jalan Asal</td>
    <td align="center">Kode Item</td>         
    <td align="center">No Mesin</td>            
    <td align="center">No Rangka</td>            
    <td align="center">Dealer Tujuan</td>            
    <td align="center">No DO</td>            
    <td align="center">Tgl DO</td>            
    <td align="center">No Faktur</td>            
    <td align="center">Tgl Faktur</td>            
    <td align="center">No Surat Jalan</td>            
    <td align="center">Tgl Surat Jalan</td>            
 	</tr>
 	<?php  	
 	$no=1;  
  $sql = $this->db->query("SELECT * FROM tr_retur_dealer 
    INNER JOIN tr_retur_dealer_detail ON tr_retur_dealer.no_retur_dealer = tr_retur_dealer_detail.no_retur_dealer
    LEFT JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer
    WHERE tr_retur_dealer.status_retur_d = 'approved' AND tr_retur_dealer.tgl_retur BETWEEN '$tgl1' AND '$tgl2' 
    ");

  $temp_surat[] = '';
  foreach ($sql->result() as $isi) {
    $where = '';
    if($temp_surat[$isi->no_mesin] != ''){
      $tgl1 = $temp_surat[$isi->no_mesin];
      $where = " AND tr_surat_jalan.tgl_surat >= '$tgl1'";
    }

    $sql2 = $this->db->query("SELECT * FROM tr_surat_jalan_detail 
      INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
      INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
      LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
      WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' and tr_surat_jalan.id_dealer = '$isi->id_dealer' $where 
      ORDER BY tr_surat_jalan_detail.id_surat_jalan_detail ASC LIMIT 0,1");
    $tgl_surat = ($sql2->num_rows() > 0) ? $sql2->row()->tgl_surat : "" ;
    $no_surat_jalan = ($sql2->num_rows() > 0) ? $sql2->row()->no_surat_jalan : "" ;
    $no_do = ($sql2->num_rows() > 0) ? $sql2->row()->no_do : "" ;
    $tgl_do = ($sql2->num_rows() > 0) ? $sql2->row()->tgl_do : "" ;

    // pengecekan no_surat jalan tr_penerimaan_unit_dealer_detail
    $this->db->select('a.no_surat_jalan, a.tgl_surat_jalan');
    $this->db->from('tr_penerimaan_unit_dealer a');
    $this->db->join('tr_penerimaan_unit_dealer_detail b', 'a.id_penerimaan_unit_dealer = b.id_penerimaan_unit_dealer', 'inner');
    $this->db->where('b.no_mesin', $isi->no_mesin);
    $this->db->where('b.retur', 1);
    $cek_surat = $this->db->get();

    // $sub_tgl = substr($tgl1, 0,7);
    $sub_tgl = $tgl1;    

	// filter $sub_tgl sblmnya pakai like & %, skrg >= , apakah ada mslh dngn retur yg lbh dari 2x atau filter tgl yg tidak sesuai
    $sql5 = $this->db->query("SELECT * FROM tr_surat_jalan_detail 
      INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
      INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
      LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
      LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
      WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin'
      AND tr_surat_jalan.tgl_surat > '$sub_tgl' 
      ORDER BY tr_surat_jalan_detail.id_surat_jalan_detail ASC LIMIT 0,1"); // awal nya desc dan pakai tgl created surat jalan
    $tgl_surat2 = ($sql5->num_rows() > 0) ? $sql5->row()->tgl_surat : "" ;
    $no_surat_jalan2 = ($sql5->num_rows() > 0) ? $sql5->row()->no_surat_jalan : "" ;
    $no_do2 = ($sql5->num_rows() > 0) ? $sql5->row()->no_do : "" ;
    $tgl_do2 = ($sql5->num_rows() > 0) ? $sql5->row()->tgl_do : "" ;    
    $nama_dealer = ($sql5->num_rows() > 0) ? $sql5->row()->nama_dealer : "" ;    

    $temp_surat[$isi->no_mesin] = $tgl_surat2;

    $sql3 = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin);
    $id_item = ($sql3->num_rows() > 0) ? $sql3->row()->id_item : "" ;
    //cek wo bundling
    $cek_wo = $this->db->query("
      SELECT
        b.id_paket_bundling,
        b.updated_at,
        c.id_item,
        c.id_item_baru
      FROM
        tr_wo_bundling_nosin a
        INNER JOIN tr_wo_bundling b ON a.no_wo_bundling = b.no_wo_bundling 
        INNER JOIN ms_paket_bundling c ON b.id_paket_bundling=c.id_paket_bundling
        AND a.no_mesin = '$isi->no_mesin' 
        AND b.status_paket = 'closed'
      ");
    if ($cek_wo->num_rows() > 0) {
      //cek jika tgl return lebil kecil dari pada updated at bundling
      $bundling = $cek_wo->row();
      if (strtotime($isi->tgl_retur) < strtotime($bundling->updated_at)) {
        $id_item = $bundling->id_item;
      } else {
        $id_item = $bundling->id_item_baru;
      }
    }
    $no_rangka = ($sql3->num_rows() > 0) ? $sql3->row()->no_rangka : "" ;

    $sql4 = $this->db->query("SELECT no_faktur,tgl_faktur FROM tr_invoice_dealer WHERE no_do='$no_do2'");
    $no_faktur = ($sql4->num_rows() > 0) ? $sql4->row()->no_faktur : "" ;
    $tgl_faktur = ($sql4->num_rows() > 0) ? $sql4->row()->tgl_faktur : "" ;
    echo "
    <tr>
      <td>$no</td>
      <td>$isi->tgl_retur</td>
      <td>$isi->nama_dealer</td>
      <td>$tgl_surat</td>
      <td>$no_surat_jalan</td>
      <td>$id_item</td>
      <td>$isi->no_mesin</td>
      <td>$no_rangka</td>
      <td>$nama_dealer</td>
      <td>$no_do2</td>
      <td>$tgl_do2</td>
      <td>$no_faktur</td>
      <td>$tgl_faktur</td>
      <td>$no_surat_jalan2</td>
      <td>$tgl_surat2</td>
    </tr>
    ";
    $no++;
  }
 	?>
</table>
 