<?php 
//$no = $id_vendor."-".$tgl1."-".$tgl2;
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=StokDealer.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">  
 	<tr> 		 		
 		<td align="center">Nama Dealer</td>
 		<td align="center">Kode Item</td>
    <td align="center">Deskripsi</td>
 		<td align="center">Warna</td>
    <td align="center">Qty Stock</td>         
    <td align="center">Qty Unfill</td>            
    <td align="center">Qty Intransit</td>            
 	</tr>
 	<?php  	
if($id_dealer!=""){
 	$no=1;  
  // $sql = $this->db->query("SELECT ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item
  //               LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
  //               LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna                                
  //               ORDER BY ms_item.id_item ASC");    
  $sql = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_scan_barcode 
                LEFT JOIN tr_picking_list_view ON tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin 
                LEFT JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do                
                LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer                                
                GROUP BY tr_scan_barcode.id_item,ms_dealer.id_dealer
                ORDER BY tr_scan_barcode.id_item ASC");                      
  $where1 = "";$where2 = "";$where3 = "";
  if($id_dealer != ""){
    $where1 = "AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'";
    $where2 = "AND tr_do_po.id_dealer = '$id_dealer'";
    $where3 = "AND tr_surat_jalan.id_dealer = '$id_dealer'";
  }
  foreach ($sql->result() as $row) {            
    $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
        LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
        LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
        LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
        LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
        LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
        WHERE tr_scan_barcode.id_item = '$row->id_item' $where1         
        AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'
        AND tr_penerimaan_unit_dealer_detail.retur = 0
        AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
        -- AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin_spk FROM tr_spk WHERE no_mesin_spk IS NOT NULL)
        ")->row();        
    $cek_unfill = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                        LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND tr_do_po_detail.id_item = '$row->id_item' $where2")->row();
    $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
                AND tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan_detail.retur = 0 $where3")->row();
    $stok_ada = $cek_qty->jum;

    $nama_dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row()->nama_dealer;
     
    if( $stok_ada > 0 OR $cek_in->jum > 0 OR $cek_unfill->jum > 0){
      $unfill = (isset($cek_unfill->jum)) ? $cek_unfill->jum : "0" ;
      echo "
      <tr>
        <td>$nama_dealer</td>                  
        <td>$row->id_item</td>              
        <td>$row->tipe_ahm</td>
        <td>$row->warna</td>
        <td>$cek_qty->jum</td>      
        <td>$unfill</td>      
        <td>$cek_in->jum</td>
      </tr>
      ";  
      $no++;
    }
  }
}else{
  $no=1;  
  // $sql = $this->db->query("SELECT ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item
  //               LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
  //               LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna                                
  //               ORDER BY ms_item.id_item ASC");    
  $sql = $this->db->query("SELECT DISTINCT(tr_scan_barcode.id_item),ms_dealer.nama_dealer,tr_do_po.id_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_scan_barcode 
                LEFT JOIN tr_picking_list_view ON tr_scan_barcode.no_mesin = tr_picking_list_view.no_mesin 
                LEFT JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do                
                LEFT JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer                                
                GROUP BY tr_scan_barcode.id_item,ms_dealer.id_dealer
                ORDER BY tr_scan_barcode.id_item ASC");                        
  foreach ($sql->result() as $row) {            
    $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
        LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
        LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
        LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
        LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
        LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
        WHERE tr_scan_barcode.id_item = '$row->id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$row->id_dealer'
        AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'
        AND tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL
        -- AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin_spk FROM tr_spk WHERE no_mesin_spk IS NOT NULL)
        ")->row();        
    $cek_unfill = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                        LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$row->id_dealer'")->row();
    $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
                AND tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan.id_dealer = '$row->id_dealer'")->row();
    $stok_ada = $cek_qty->jum;
    
     
    if( $stok_ada > 0 OR $cek_in->jum > 0 OR $cek_unfill->jum > 0){
      $unfill = (isset($cek_unfill->jum)) ? $cek_unfill->jum : "0" ;
      echo "
      <tr>
        <td>$row->nama_dealer</td>                  
        <td>$row->id_item</td>              
        <td>$row->tipe_ahm</td>
        <td>$row->warna</td>
        <td>$cek_qty->jum</td>      
        <td>$unfill</td>      
        <td>$cek_in->jum</td>
      </tr>
      ";  
      $no++;
    }
  } 	
}
?>
</table>
 