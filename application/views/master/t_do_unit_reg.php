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
      <th width="10%">Harga</th>  
      <th width="10%">Total</th>  
      <!--th width="10%">Action</th-->                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $isi_po = 1;
  $hal = $dt_do_reg->num_rows() + 1;
  foreach($dt_do_reg->result() as $row) {        
    $cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md INNER JOIN ms_kelompok_harga 
                  ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
                  INNER JOIN ms_dealer ON ms_kelompok_harga.id_kelompok_harga = ms_dealer.id_kelompok_harga
                  WHERE ms_dealer.id_dealer = '$id_dealer' AND ms_kelompok_md.id_item = '$row->id_item'
                  AND ms_kelompok_md.active = 1 AND '$tanggal' BETWEEN ms_kelompok_md.start_date AND ms_kelompok_md.end_date");
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
        $on_hand = $rfs + $nrfs;
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

    if($jenis_do == 'po_reguler'){
      $qty_1 = $row->qty_po_fix;
    }elseif($jenis_do == 'po_additional') {
      $qty_1 = $row->qty_order;
    }

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

    
    echo "
    <tr bgcolor='$warna'>
      <td width='7%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='10%'>$on_hand unit</td>
      <td width='10%'>$rfs unit</td>
      <td width='10%'>$qty unit</td>
      <td width='10%'>"; ?>
        <input type="hidden" id="isi_po" value="<?php echo $isi_po ?>">
        <input type="hidden" id="hal" value="<?php echo $hal ?>">
        <input type="hidden" name="qty_on_hand[]" value="<?php echo $on_hand ?>">
        <input type="hidden" name="qty_rfs[]" value="<?php echo $rfs ?>">        
        <input type="hidden" name="id_item[]" value="<?php echo $row->id_item ?>">        
        <input type="hidden" name="qty_order[]" value="<?php echo $qty ?>">        

        <input type="hidden" id="<?php echo "qty_or_$isi_po"; ?>" name="qty_or[]" value="<?php echo $qty ?>">        
        <input type="hidden" id="<?php echo "qty_on_$isi_po"; ?>" name="qty_on[]" value="<?php echo $on_hand ?>">        
        <input type="hidden" id="<?php echo "qty_rfs_$isi_po"; ?>" name="qty_rfs[]" value="<?php echo $rfs ?>">        
        <input type="text" id="<?php echo "qty_po_$isi_po"; ?>" <?php echo $tipe ?> onkeypress="return number_only(event)" onchange="kali_po()"  class="form-control isi" placeholder="Qty DO" name="qty_po[]">
      </td>
      <td width='10%'>        
        <input type="text" id="<?php echo "tmp_harga_$isi_po"; ?>" onpaste="return false" onkeypress="return nihil(event)" value="<?php echo mata_uang3($harga) ?>" class="form-control isi" placeholder="Harga" name="tmp_harga[]">        
        <input type="hidden" id="<?php echo "harga_$isi_po"; ?>" value="<?php echo $harga ?>" name="harga[]">        
      </td>
      <td width='10%'>
        <input type="text" id="<?php echo "total_po_$isi_po"; ?>" value=0 onpaste="return false" <?php echo $tipe ?> onkeypress="return nihil(event)" required="required" onchange="kali_po()" class="form-control isi" placeholder="Total" name="qty_do[]">        
      </td>
    </tr>
    <?php    
    $isi_po++;
    
    }
  ?>  
</table>


<!--table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="7%">
        <input id="id_item" readonly type="text" data-toggle="modal" data-target="#Itemmodal" name="id_item" class="form-control isi" placeholder="ID Item">
      </td>
      <td width="15%">
        <input type="text" id="tipe" data-toggle="modal" data-target="#Itemmodal" placeholder="Tipe" class="form-control isi" name="tipe" readonly>
      </td>
      <td width="10%">
        <input type="text" id="warna" data-toggle="modal" data-target="#Itemmodal" placeholder="Warna" class="form-control isi" name="warna" readonly>
      </td>
      <td width="10%">
        <input type="text" id="qty_on_hand" class="form-control isi" placeholder="Qty On Hand" name="qty_on_hand" readonly>
      </td>      
      <td width="10%">
        <input type="text" id="qty_rfs" class="form-control isi" placeholder="Qty RFS" name="qty_rfs" readonly>
      </td>      
       <td width="10%">
        <input type="text" id="qty_do" onchange="kalian()"  onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty DO" name="qty_do">
      </td>      
      <td width="10%">
        <input type="text" id="harga" class="form-control isi" placeholder="Harga" name="harga" readonly>
      </td>      
      <td width="10%">
        <input type="text" id="total_harga" class="form-control isi" placeholder="Total Harga" name="total_harga" readonly>
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_do()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table-->
