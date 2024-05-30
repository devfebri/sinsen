<?php 
if($mode != 'detail'){
?>
<table id="myTable" class="table myTable1 order-list table-bordered" border="0">
  <thead>
    <tr>
      <th width="30%">Tipe</th>
      <th width="15%">Qty Analisis</th>
      <!-- <th width="15%">Qty MD</th> -->
      <th width="15%">Item</th>
      <th width="15%">Qty Order</th>  
      <th width="10%">
        Action
      </th>                      
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tipe_reg->result() as $rss): ?>
      <?php 
          $getQtyMD = $this->db->query("SELECT count(tipe_motor) as stok FROM tr_scan_barcode WHERE tipe_motor='$rss->id_tipe_kendaraan' AND status=1 AND tipe='RFS'")->row()->stok;
           $getAnalisis = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order_detail
                        inner JOIN tr_analisis_proyeksi_order on tr_analisis_proyeksi_order_detail.id_analisis= tr_analisis_proyeksi_order.id_analisis
                        WHERE id_tipe_kendaraan='$rss->id_tipe_kendaraan' AND id_dealer='$id_dealer'
      ");
           if ($getAnalisis->num_rows()>0) {
              $ty = $getAnalisis->row();
             $getAnalisis_isi = $ty->qty_order;
           }else{
            $getAnalisis_isi = "";
           }

       ?>

        <?php $x=0; foreach($dt_po_reg->result() as $row) {  
            if ($row->id_tipe_kendaraan == $rss->id_tipe_kendaraan)
            {
              $x+=1;
            }
        }

        ?>
      <?php $xx=0; foreach($dt_po_reg->result() as $key=> $row) {  ?>
          <?php if ($row->id_tipe_kendaraan == $rss->id_tipe_kendaraan): ?>
             <tr>
                <?php if ($xx==0): ?>
                  <td rowspan="<?=$x?>"><?=$row->tipe_ahm?></td>
                <td rowspan="<?=$x?>"><?=$getAnalisis_isi?></td>
                <!-- <td rowspan="<?=$x?>"><?=$getQtyMD?></td> -->
                <?php endif ?>
                <td><?=$row->id_item?></td>
                <td><?=$row->qty_po_fix?></td>
                <td><button title="Hapus Data"
                    class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                    onClick="hapus_po('<?php echo $row->id_po_detail; ?>','<?php echo $row->id_item; ?>')"></button></td>
              </tr>
          <?php $xx++; endif ?>
      <?php } ?>
    <?php endforeach ?>
  </tbody>
</table>
 <div id="showInput">
  <table class="table-bordered table">
    <tr>
      <td width="30%">
        <?php
          $bulan=date('m');
        $getTipe = $this->db->query("SELECT * FROM tr_analisis_proyeksi_order_detail
                        inner JOIN tr_analisis_proyeksi_order on tr_analisis_proyeksi_order_detail.id_analisis= tr_analisis_proyeksi_order.id_analisis
                        inner join ms_tipe_kendaraan on tr_analisis_proyeksi_order_detail.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                        WHERE id_dealer='$id_dealer' AND qty_order > 0 AND bulan=$bulan "); ?>
        <select class="form-control select2" onchange="getInput()" id="id_tipe_kendaraan" style="width: 100%">
          <?php if ($getTipe->num_rows()>0) {
            echo "<option value=''>- choose -</option>";
          foreach ($getTipe->result() as $key => $res) { ?>
            <option value="<?=$res->id_tipe_kendaraan?>"><?php echo $res->id_tipe_kendaraan ?> | <?php echo $res->tipe_ahm ?> </option>

        <?php }
        } ?>
        </select>
      </td>
      <td width="15%"></td>
      <td width="15%"></td>
      <td width="15%"></td>
      <td width="15%"></td>
      <td width="10%"></td>
    </tr>
  </table>
  </div>
<script type="text/javascript">
  

</script>

<!-- 
<table id="myTable" class="table myt order-list" border="0">     
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
        <input type="text" id="qty_po_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty PO Fix" name="qty_po_fix">
      </td>      
       <td width="10%">
        <input type="text" id="qty_po_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty PO T1" name="qty_po_t1">
      </td>      
       <td width="10%">
        <input type="text" id="qty_po_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="Qty PO T2" name="qty_po_t2">
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_po()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table> -->

<?php }else{ ?>

<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="7%">ID Item</th>
      <th width="15%">Tipe</th>
      <th width="10%">Warna</th>
      <th width="10%">Qty Order</th>      
<!--       <th width="10%">Qty PO T1</th>        
      <th width="10%">Qty PO T2</th>   -->                        
    </tr>
  </thead> 
</table>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_po_reg->result() as $row) {           
    echo "   
    <tr>                    
      <td width='7%'>$row->id_item</td>
      <td width='15%'>$row->tipe_ahm</td>
      <td width='10%'>$row->warna</td>
      <td width='10%'>$row->qty_po_fix</td>                                                            
    </tr>";  
    }
  ?>  
</table>


<?php } ?>