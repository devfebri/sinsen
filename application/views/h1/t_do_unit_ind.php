<?php 
function mata_uang3($a){
  return number_format($a, 0, ',', '.');
}
$sql = $this->m_admin->getByID("tr_do_indent_detail","no_do",$no_do);
$isi = $sql->num_rows();
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
      <!-- <th width="10%">Harga</th>  
      <th width="10%">Total</th>   -->
      <th width="10%">Action</th> 
      <input type="hidden" id="isi_indent" value="<?php echo $isi ?>">
    </tr>
  </thead> 

  <?php   
  $no=1;$tot_onhand=0;$tot_rfs=0;
  $hal = $dt_do_ind->num_rows() + 1;
  foreach($dt_do_ind->result() as $row) {           
    $jumlah = $dt_do_ind->num_rows();
    $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND id_warna = '$row->id_warna' AND bundling!='ya'");
    if($cek_item->num_rows() > 0){
      $it = $cek_item->row();
      $id_item = $it->id_item;
    }else{
      $id_item = "";
    }
    $sekarang   = gmdate("Y-m-d", time()+60*60*7);           
    $cek_harga  = $this->db->query("SELECT * FROM ms_kelompok_md INNER JOIN ms_kelompok_harga 
                  ON ms_kelompok_md.id_kelompok_harga = ms_kelompok_harga.id_kelompok_harga 
                  INNER JOIN ms_dealer ON ms_kelompok_harga.id_kelompok_harga = ms_dealer.id_kelompok_harga
                  WHERE ms_dealer.id_dealer = '$id_dealer' AND ms_kelompok_md.id_item = '$id_item'
                  AND ms_kelompok_md.active = 1 AND '$tanggal' BETWEEN ms_kelompok_md.start_date AND '$sekarang'");
    if($cek_harga->num_rows() > 0){
      $har = $cek_harga->row();
      $harga = $har->harga_jual;
      $harga_f = mata_uang3($har->harga_jual);
    }else{
      $harga = 0;
      $harga_f = 0;
    }        

    $cek_rfs = $this->db->query("SELECT * FROM tr_real_stock WHERE id_item = '$id_item'");
    $cek_no = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '1' AND tipe='RFS'");
    $cek_no2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$id_item' AND status = '1' AND tipe='NRFS'");
    if($cek_no->num_rows() > 0){
      $ju = $cek_no->row();
      $ju2 = $cek_no2->row();
      if($cek_rfs->num_rows() > 0){
        $isi = $cek_rfs->row();
        $rfs = $ju->jum;
        $nrfs = $ju2->jum;

        $cek_book = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS booking FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
          WHERE tr_do_po.status = 'input' AND tr_do_po_detail.id_item = '$id_item'");
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
    }
    $tot_onhand+=$on_hand;
    $tot_rfs+=$rfs;
    if($rfs < 1){
      $cek = "style='display:none;'";
    }else{
      $cek = "";
    }
    echo "
    <tr>
      <td width='7%'>$row->id_spk</td>
      <td width='10%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='15%'>$row->nama_konsumen</td>  
      <td width='10%'>$on_hand unit</td>
      <td width='10%'>$rfs unit</td>
      <td width='10%'>"; ?>        
        <input type="hidden" id="<?php echo "qty_on_hand_$no"; ?>" value="<?php echo $on_hand ?>">
        <input type="hidden" id="jumlah" value="<?php echo $jumlah ?>">    
        <input type="hidden" id="hal" value="<?php echo $hal ?>">    
        <input type="hidden" id="<?php echo "qty_rfs_$no"; ?>" value="<?php echo $rfs ?>">        
        <input type="hidden" id="<?php echo "id_item_$no"; ?>" value="<?php echo $id_item ?>">        
        <input type="hidden" id="<?php echo "id_indent_$no"; ?>" value="<?php echo $row->id_indent ?>">        
        <input type="hidden" id="<?php echo "no_spk_$no"; ?>" value="<?php echo $row->id_spk ?>">        
        <input type="text" value="1" id="<?php echo "qty_do_$no"; ?>" onkeypress="return number_only(event)" onchange="kalian()"  class="form-control isi" placeholder="Qty DO">
      </td>
      <td width='10%' style="display:none">        
        <input type="hidden" id="<?php echo "harga_$no"; ?>" value="<?php echo $harga ?>">        
        <input style="text-align: right;" type="text" readonly id="<?php echo "harga_f_$no"; ?>" value="<?php echo $harga_f ?>" onkeypress="return nihil(event)" class="form-control isi" placeholder="Harga">        
      </td>
      <td width='10%' style="display:none">
        <input type="hidden" id="<?php echo "total_harga_$no"; ?>">        
        <input style="text-align: right;" type="text" value="<?php echo mata_uang3($harga) ?>" readonly id="<?php echo "total_harga_f_$no"; ?>" onchange="kalian()" onkeypress="return nihil(event)" class="form-control isi" placeholder="Total">        
      </td>
      <td width='10%'>
        <button type="button" <?php echo $cek ?> onclick="simpan_indent(<?php echo $no ?>)" class="btn btn-xs bg-maroon btn-flat"><i class="fa fa-plus"></i> Add</button>
      </td>      
    </tr>
    <?php    
    $no++;
    }
  ?>    
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  $sql = $this->db->query("SELECT * FROM tr_do_indent_detail
      INNER JOIN tr_po_dealer_indent ON tr_do_indent_detail.id_indent = tr_po_dealer_indent.id_indent
      INNER JOIN ms_item ON tr_do_indent_detail.id_item=ms_item.id_item 
      INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
      INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna 
      WHERE (tr_do_indent_detail.no_do = '$no_do' AND tr_po_dealer_indent.id_dealer = '$id_dealer')
      OR (tr_po_dealer_indent.id_dealer = '$id_dealer' AND tr_do_indent_detail.no_do NOT IN (SELECT no_do FROm tr_do_po))");
  $tot_onhand=0;$tot_rfs=0;$tot_do=0;$tot_tott=0;
  foreach($sql->result() as $row) {       
    $t = $this->db->query("SELECT * FROM tr_po_dealer_indent WHERE id_indent = '$row->id_indent'")->row();    
    $tot_onhand+=$row->qty_on_hand;
    $tot_rfs+=$row->qty_rfs;
    $tot_do+=$row->qty_do;
    $tot_tott += $row->qty_do * $row->harga;    
    echo "
    <tr>      
      <td width='10%'>$t->id_spk</td>
      <td width='10%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>  
      <td width='15%'>$t->nama_konsumen</td>  
      <td width='10%'>$row->qty_on_hand unit</td>
      <td width='10%'>$row->qty_rfs unit</td>
      <td width='10%'>$row->qty_do unit</td>
      "; ?>
     
      <td width='10%'>        
        <button type="button" onclick="hapus_indent(<?php echo $row->id_indent ?>,<?php echo $row->id_do_indent_detail ?>)" class="btn btn-xs bg-maroon btn-flat"><i class="fa fa-trash-o"></i> Delete</button>
      </td>      
    </tr>
    <?php    
    }
  ?>  
  <tfoot>
    <!-- <tr>
      <td width="10%">ID SPK</td>
      <td width="10%">Tipe</td>
      <td width="10%">Warna</td>
      <td width="15%">Nama Konsumen</td>      
      <td width="10%">Qty on Hand</td>      
      <td width="10%">Qty RFS</td>        
      <td width="10%">Qty DO</td>  
      <td width="10%">Harga</td>  
      <td width="10%">Total</td>  
      <td width="10%">Action</td>                      
    </tr> -->
    <tr>
      <td align="right" colspan="4"><b>Total</b></td>
      <td><?=$tot_onhand?></td>
      <td><?= $tot_rfs ?></td>
      <td><?= $tot_do ?></td>
      <td colspan="3"></td>      
    </tr>
  </tfoot>
</table>


<!-- <td align='right' width='10%'>".mata_uang3($row->harga)."</td>
      <td align='right' width='10%'>".$row->qty_do * $row->harga."</td> -->