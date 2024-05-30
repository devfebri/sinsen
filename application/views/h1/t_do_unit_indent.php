<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>                        
      <th width="10%">ID SPK</th>
      <th width="10%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="15%">Nama Konsumen</th>      
      <th width="10%">Qty on Hand</th>      
      <th width="10%">Qty RFS</th>        
      <th width="10%">Qty DO</th>        
      <th width="10%">Action</th> 
    </tr>
  </thead> 

  <?php   
  $isi_po = 1;

  $hal = $dt_do_reg->num_rows() + 1;
  $tot_onhand=0;$tot_rfs=0;$tot_order=0;$tot_do=0;$tot_hrg=0;$tot=0;
  foreach($dt_do_reg->result() as $row) {     
    $jumlah = $dt_do_reg->num_rows();
    $sekarang   = gmdate("Y-m-d", time()+60*60*7);           
    $cek_harga  = $this->db->query("SELECT * FROM ms_kelompok_md INNER JOIN ms_kelompok_harga 
                  ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
                  INNER JOIN ms_dealer ON ms_kelompok_harga.id_kelompok_harga = ms_dealer.id_kelompok_harga
                  WHERE ms_dealer.id_dealer = '$id_dealer' AND ms_kelompok_md.id_item = '$row->id_item'
                  AND ms_kelompok_md.active = 1 AND '$tanggal' BETWEEN ms_kelompok_md.start_date AND '$sekarang'
                  ORDER BY ms_kelompok_md.id_kelompok_md DESC LIMIT 0,1");
    if($cek_harga->num_rows() > 0){
      $har = $cek_harga->row();
      $harga = $har->harga_jual;
    }else{
      $harga = 0;
    }

    $cek_rfs = $this->db->query("SELECT * FROM tr_real_stock WHERE id_item = '$row->id_item'");    
    $th = date("Y");
    $cek_no = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '1' AND tipe='RFS' AND LEFT(fifo,4) <= '$th'");
    $cek_no2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '1' AND tipe='NRFS' AND LEFT(fifo,4) <= '$th'");
    if($cek_no->num_rows() > 0){
      $ju = $cek_no->row();
      $ju2 = $cek_no2->row();
      if($cek_rfs->num_rows() > 0){
        $isi = $cek_rfs->row();

        //cek qty booking

        $cek_book = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS booking FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
          WHERE tr_do_po.status = 'input' AND tr_do_po_detail.id_item = '$row->id_item'");
        $qty_book = ($cek_book->num_rows() > 0) ? $cek_book->row()->booking : 0 ;

        $rfs = $ju->jum;
        $nrfs = $ju2->jum;
        $on_hand = ($rfs + $nrfs) - $qty_book;
        if($on_hand == 0){
          $warna = 'red';
          $tipe = 'readonly value=0';
        }else{
          $warna = '';
          $tipe = '';  
        }
      }else{
        $rfs = $ju->jum;
        $on_hand = 0;
        $warna = 'red';
        $tipe = 'readonly value=0';
      }            
    }else{
      $rfs = 0;
      $on_hand = 0;
      $warna = 'red';
      $tipe = 'readonly value=0';
    }
    
    $qty_1 = $row->qty_order;    

    $cek_sisa = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS tot FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
      WHERE tr_do_po.no_po = '$no_po' AND tr_do_po_detail.id_item = '$row->id_item'");
    if($cek_sisa->num_rows() > 0){
      $qty_do = $cek_sisa->row();
      if($qty_do->tot < $qty_1){
        $qty = $qty_1 - $qty_do->tot;
      }else{
        $qty = 0;
      }
    }else{
      $qty = $qty_1;
    }
    $tot_onhand +=$on_hand;
    $tot_rfs +=$rfs;
    $tot_order +=$qty;
    
    echo "
    <tr bgcolor='$warna'>
      <td width='7%'>$row->no_spk</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='10%'>$row->nama_konsumen</td>
      <td width='10%'>$on_hand unit</td>
      <td width='10%'>$rfs unit</td>
      <td width='10%'>$qty unit</td>
      <td width='10%'>"; ?>
        <input type="hidden" id="isi_po" name="isi_po" value="<?php echo $isi_po ?>">
        <input type="hidden" id="hal" name="hal" value="<?php echo $hal ?>">
        <input type="hidden" id="jumlah" name="jumlah" value="<?php echo $jumlah ?>">
        <input type="hidden" name="<?php echo "id_item_$isi_po"; ?>" value="<?php echo $row->id_item ?>">        
        <input type="hidden" id="<?php echo "qty_order_$isi_po"; ?>" name="<?php echo "qty_order_$isi_po"; ?>" value="<?php echo $qty ?>">        
        
        <input type="hidden" id="<?php echo "qty_on_$isi_po"; ?>" name="<?php echo "qty_on_$isi_po"; ?>" value="<?php echo $on_hand ?>">        
        <input type="hidden" id="<?php echo "qty_rfs_$isi_po"; ?>" name="<?php echo "qty_rfs_$isi_po"; ?>" value="<?php echo $rfs ?>">        
        <input type="text" id="<?php echo "qty_po_$isi_po"; ?>" <?php echo $tipe ?> onkeypress="return number_only(event)" onchange="kali_po()"  class="form-control isi" placeholder="Qty DO" name="<?php echo "qty_do_$isi_po"; ?>">
      </td>
      <td style="display:none;" width='10%'>        
        <input style="text-align: right;" type="text" readonly id="<?php echo "tmp_harga_$isi_po"; ?>" onpaste="return false" onkeypress="return nihil(event)" value="<?php echo mata_uang3($harga) ?>" class="form-control isi" placeholder="Harga"> 
        <input type="hidden" id="<?php echo "harga_$isi_po"; ?>" value="<?php echo $harga ?>" name="<?php echo "harga_$isi_po"; ?>">        
      </td>
      <td style="display:none;" width='10%'>
        <input style="text-align: right;" type="hidden" id="<?php echo "total_po_$isi_po"; ?>">
        <input style="text-align: right;" type="text" id="<?php echo "total_tmp_$isi_po"; ?>" readonly value=0 onpaste="return false" <?php echo $tipe ?> onkeypress="return nihil(event)" required="required" onchange="kali_po()" class="form-control isi" placeholder="Total">
      </td>
    </tr>
    <?php    
    $isi_po++;
    
    }
  ?> 
  <tfoot>
    <tr>
      <td colspan="4" align="right"><b>Total</b></td>
      <td><?php echo $tot_onhand?></td>
      <td><?php echo $tot_rfs?></td>
      <td><?php echo $tot_order ?></td>      
    </tr> 
  </tfoot>
</table>