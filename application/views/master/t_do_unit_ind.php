<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="7%">ID SPK</th>
      <th width="10%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="15%">Nama Konsumen</th>      
      <th width="10%">Qty on Hand</th>      
      <th width="10%">Qty RFS</th>        
      <th width="10%">Qty DO</th>  
      <th width="10%">Harga</th>  
      <th width="10%">Total</th>  
      <th width="10%">Action</th>                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_do_ind->result() as $row) {           
    $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna'");
    if($cek_item->num_rows() > 0){
      $it = $cek_item->row();
      $id_item = $it->id_item;
    }else{
      $id_item = "";
    }
    
    $cek_harga = $this->db->query("SELECT * FROM ms_kelompok_md INNER JOIN ms_kelompok_harga 
                  ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
                  INNER JOIN ms_dealer ON ms_kelompok_harga.id_kelompok_harga = ms_dealer.id_kelompok_harga
                  WHERE ms_dealer.id_dealer = '$id_dealer' AND ms_kelompok_md.id_item = '$id_item'
                  AND ms_kelompok_md.active = 1 AND '$tanggal' BETWEEN ms_kelompok_md.start_date AND ms_kelompok_md.end_date");
    if($cek_harga->num_rows() > 0){
      $har = $cek_harga->row();
      $harga = $har->harga_jual;
    }else{
      $harga = 0;
    }
    
    $cek_rfs = $this->db->query("SELECT * FROM tr_real_stock WHERE id_item = '$row->id_item'");
    $cek_no = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '1' AND tipe='RFS'");
    $cek_no2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '1' AND tipe='NRFS'");
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
    
    echo "
    <tr>
      <td width='7%'>$row->id_spk $id_item $tanggal</td>
      <td width='10%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='15%'>$row->nama_konsumen</td>  
      <td width='10%'>$on_hand unit</td>
      <td width='10%'>$rfs unit</td>
      <td width='10%'>"; ?>        
        <input type="hidden" id="qty_on_hand" value="<?php echo $on_hand ?>">
        <input type="hidden" id="qty_rfs" value="<?php echo $rfs ?>">        
        <input type="hidden" id="id_item" value="<?php echo $id_item ?>">        
        <input type="hidden" id="id_indent" value="<?php echo $row->id_indent ?>">        
        <input type="text" id="qty_do" onkeypress="return number_only(event)" onchange="kalian()"  class="form-control isi" placeholder="Qty DO">
      </td>
      <td width='10%'>        
        <input type="text" id="harga" value="<?php echo $harga ?>" onkeypress="return nihil(event)" class="form-control isi" placeholder="Harga">        
      </td>
      <td width='10%'>
        <input type="text" id="total_harga" onchange="kalian()" onkeypress="return nihil(event)" class="form-control isi" placeholder="Total">        
      </td>
      <td width='10%'>
        <button type="button" onclick="simpan_indent()" class="btn btn-xs bg-maroon btn-flat"><i class="fa fa-check"></i> Approve</button>
      </td>      
    </tr>
    <?php    
    }
  ?>  
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $sql = $this->db->query("SELECT * FROM tr_do_indent_detail
      INNER JOIN ms_item ON tr_do_indent_detail.id_item=ms_item.id_item 
      INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
      INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna 
      WHERE tr_do_indent_detail.no_do = '$no_do'");
  foreach($sql->result() as $row) {       
    $t = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_indent = '$row->id_indent'")->row();    
    
    echo "
    <tr>
      <td width='7%'>$t->id_spk</td>
      <td width='10%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='15%'>$t->nama_konsumen</td>  
      <td width='10%'>$row->qty_on_hand unit</td>
      <td width='10%'>$row->qty_rfs unit</td>
      <td width='10%'>$row->qty_do unit</td>
      <td width='10%'>".mata_uang3($row->harga)."</td>
      <td width='10%'>".$row->qty_do * $row->harga."</td>"; ?>
     
      <td width='10%'>
        <button type="button" onclick="hapus_indent(<?php echo $row->id_indent ?>,<?php echo $row->id_do_indent_detail ?>)" class="btn btn-xs bg-maroon btn-flat"><i class="fa fa-trash-o"></i> Delete</button>
      </td>      
    </tr>
    <?php    
    }
  ?>  
</table>
