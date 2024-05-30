<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="7%">ID Item</th>
      <th width="15%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="10%">Qty on Hand</th>      
      <th width="10%">Qty RFS</th>       
      <th width="10%">Qty Order</th>              
      <th width="10%">Qty DO</th>  
      <!-- <th width="10%">Harga</th>  
      <th width="10%">Total</th>   -->
      <!--th width="10%">Action</th-->                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $isi_po=1;$jum=0;$gt=0;
  $hal = $dt_do_reg->num_rows() + 1;
  foreach($dt_do_reg->result() as $row) {       
    $cek_po = $this->db->query("SELECT * FROM tr_po_dealer_detail INNER JOIN tr_po_dealer ON tr_po_dealer_detail.id_po = tr_po_dealer.id_po
        WHERE tr_po_dealer.id_po = '$row->no_po' AND tr_po_dealer_detail.id_item = '$row->id_item'")->row();
    $jenis_do = $row->source;
    if($jenis_do == 'po_reguler'){
      $qty = $cek_po->qty_po_fix;
    }elseif($jenis_do == 'po_additional') {
      $qty = $cek_po->qty_order;
    } 
    $sekarang   = gmdate("Y-m-d", time()+60*60*7);           
    $cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md INNER JOIN ms_kelompok_harga 
                      ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
                      INNER JOIN ms_dealer ON ms_kelompok_harga.id_kelompok_harga = ms_dealer.id_kelompok_harga 
                      WHERE ms_dealer.id_dealer = '$id_dealer' AND ms_kelompok_md.id_item = '$row->id_item' AND 
                      ms_kelompok_md.active = 1 AND '$row->tgl_do' BETWEEN ms_kelompok_md.start_date AND '$sekarang'
                      ORDER BY ms_kelompok_md.id_kelompok_md DESC LIMIT 0,1");
    if($cek_harga->num_rows() > 0){
      $har = $cek_harga->row();
      $harga = $har->harga_jual;
    }else{
      $harga = 0;
    }

    

    $cek_rfs = $this->db->query("SELECT * FROM tr_real_stock WHERE id_item = '$row->id_item'");
    $cek_no = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '1' AND tipe='RFS'");
    $cek_no2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$row->id_item' AND status = '1' AND tipe='NRFS'");
    if($cek_no->num_rows() > 0){
      $ju = $cek_no->row();
      $ju2 = $cek_no2->row();
      if($cek_rfs->num_rows() > 0){
        $isi = $cek_rfs->row();
        $rfs = $ju->jum;
        $nrfs = $ju2->jum;
        $cek_book = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS booking FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
          WHERE tr_do_po.status = 'input' AND tr_do_po_detail.id_item = '$row->id_item'");
        $qty_book = ($cek_book->num_rows() > 0) ? $cek_book->row()->booking : 0 ;

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
      $kosong = $kosong + 1;
    }           
    $total_harga = $harga * $row->qty_do;
    
    echo "
    <tr>
      <td width='7%'>$row->id_item $tanggal</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='10%'>$on_hand unit</td>
      <td width='10%'>$rfs unit</td>
      <td width='10%'>$qty unit</td>      
      <td align='right' width='10%'>"; ?>
        <input type="hidden" id="isi_po" value="<?php echo $isi_po ?>">
        <input type="hidden" id="hal" value="<?php echo $hal ?>">
        <input type="hidden" name="qty_on_hand[]" value="<?php echo $on_hand ?>">
        <input type="hidden" name="qty_rfs[]" value="<?php echo $rfs ?>">        
        <input type="hidden" name="id_item[]" value="<?php echo $row->id_item ?>">        
        <input type="hidden" name="qty_order[]" value="<?php echo $qty ?>">        
        <input type="hidden" id="<?php echo "qty_order_$isi_po"; ?>"  value="<?php echo $qty ?>">        

        <input type="hidden" id="<?php echo "qty_or_$isi_po"; ?>" name="qty_or[]" value="<?php echo $qty ?>">        
        <input type="hidden" id="<?php echo "qty_on_$isi_po"; ?>" name="qty_on[]" value="<?php echo $on_hand ?>">        
        <input type="text" id="<?php echo "qty_po_$isi_po"; ?>" value="<?php echo $row->qty_do ?>" onkeypress="return number_only(event)" onchange="kali_po()"  class="form-control isi" placeholder="Qty DO" name="qty_po[]">
      </td>
      <td style="display:none;" align='right' width='10%'>        
        <input style="text-align: right;" type="text" readonly id="<?php echo "tmp_harga_$isi_po"; ?>" onpaste="return false" onkeypress="return nihil(event)" value="<?php echo mata_uang3($harga) ?>" class="form-control isi" placeholder="Harga" name="tmp_harga[]">        
        <input type="hidden" id="<?php echo "harga_$isi_po"; ?>" value="<?php echo $harga ?>" name="harga[]">        
      </td>
      <td style="display:none;" width='10%'>
        <input style="text-align: right;" type="text" readonly id="<?php echo "total_po_$isi_po"; ?>" value="<?php echo $total_harga ?>" onpaste="return false" onkeypress="return nihil(event)" required="required" onchange="kali_po()" class="form-control isi" placeholder="Total" name="qty_do[]">        
      </td>      
    </tr>
    <?php    
    $isi_po++;  
    $jum = $jum + $row->qty_do;
    $gt = $gt + $total_harga;
    }
  ?>    
</table>
