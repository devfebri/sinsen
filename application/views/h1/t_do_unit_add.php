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
      <th width="10%">Qty DO2</th>  
      <!-- <th width="10%">Harga</th>  
      <th width="10%">Total</th>   -->
      <!--th width="10%">Action</th-->                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $isi_po = 1;
  $hal = $dt_do_add->num_rows() + 1;
  foreach($dt_do_add->result() as $row) {        
    $id_dealer = $this->m_admin->cari_dealer();   
    $cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md INNER JOIN ms_kelompok_harga 
                  ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
                  INNER JOIN ms_dealer ON ms_kelompok_harga.id_kelompok_harga = ms_dealer.id_kelompok_harga
                  WHERE ms_dealer.id_dealer = '$id_dealer' AND ms_kelompok_md.id_item = '$row->id_item'");
    if($cek_harga->num_rows() > 0){
      $har = $cek_harga->row();
      $harga = $har->harga_bbn;
    }else{
      $harga = 0;
    }

    $cek_rfs = $this->db->query("SELECT * FROM tr_real_stock WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'");
    if($cek_rfs->num_rows() > 0){
      $isi = $cek_rfs->row();
      $rfs = $isi->stok_rfs;
      $on_hand = $isi->stok_rfs + $isi->stok_nrfs + $isi->stok_pinjaman + $isi->stok_booking;
    }else{
      $rfs = 0;
      $on_hand = 0;
    }
    
    echo "
    <tr>
      <td width='7%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='10%'>$on_hand unit</td>
      <td width='10%'>$rfs unit</td>
      <td width='10%'>"; ?>
        <input type="hidden" id="isi_po" value="<?php echo $isi_po ?>">
        <input type="hidden" id="hal" value="<?php echo $hal ?>">
        <input type="hidden" name="qty_on_hand[]" value="<?php echo $on_hand ?>">
        <input type="hidden" name="qty_rfs[]" value="<?php echo $rfs ?>">        
        <input type="hidden" name="id_item[]" value="<?php echo $row->id_item ?>">        
        <input type="text" id="<?php echo "qty_po_$isi_po"; ?>" onchange="kali_po()"  class="form-control isi" placeholder="Qty DO" name="qty_po[]">
      </td>
      <td style="display:none" width='10%'>        
        <input type="text" id="<?php echo "harga_$isi_po"; ?>" value="<?php echo $harga ?>" readonly class="form-control isi" placeholder="Harga" name="harga[]">        
      </td>
      <td style="display:none" width='10%'>
        <input type="text" id="<?php echo "total_po_$isi_po"; ?>" onchange="kali_po()" readonly class="form-control isi" placeholder="Total" name="qty_do[]">        
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
