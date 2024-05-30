<table class="table table-bordered table-hover myTable1">
  <thead>
    <tr>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>Tipe Warna</th>     
      <th class="serial-is-ev"></th> 
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    foreach ($detail->result() as $rs): ?>
      <tr>
        <td><?= $rs->no_mesin?></td>
        <td><?= $rs->no_rangka?></td>
        <td><?= $rs->tipe_ahm." - ".$rs->warna?></td>    
        <?
        if($rs->id_tipe_kendaraan =='ME0' || $rs->id_tipe_kendaraan =='MH0' ){ ?>
          <td>
            <?$serial_set = $this->db->query("SELECT * from tr_sales_order_acc_ev where no_spk = '$rs->no_spk_gc' ")->row();
              echo $serial_set->serial_number;
            ?>
          <td>
        <?}
        ?>    
        <td>
          <button type="button" class="btn btn-danger btn-xs btn-flat" title="Add" onclick="delDetail(<?= $rs->id?>)"><i class="fa fa-trash"></i></button>
          <!-- <button type="button" class="btn btn-warning btn-flat btn-xs" data-toggle="modal" data-target=".modal_edit" id="<?php echo $rs->id ?>" onclick="edit_popup('<?php echo $rs->id ?>')"><i class="fa fa-edit"></i></button> -->
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
    <tr>
      <td>                                                            
        <select class="form-control select3" name="no_mesin_gc" id="no_mesin_gc" onchange="getNosin_gc()">
            <?php 
            $id_dealer = $this->m_admin->cari_dealer();
            if($no_spk_gc == '') {
              $filter = "";
            }else{              
              $where = "";$p=1;
              $gt = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan WHERE no_spk_gc = '$no_spk_gc'");
              foreach ($gt->result() as $hasil) {
                $jum = $gt->num_rows();
                $tipe = $hasil->id_tipe_kendaraan;
                $warna = $hasil->id_warna;
                $where .= " (ms_tipe_kendaraan.id_tipe_kendaraan = '$tipe' AND ms_warna.id_warna = '$warna')";
                $p++;
                if($p <= $jum){
                  $where .= " OR";
                }
              }
                              
              $filter = "AND (".$where.")";              
            }

            $dt_nosin = $this->db->query("SELECT tr_penerimaan_unit_dealer_detail.no_mesin,tr_scan_barcode.no_rangka,ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,ms_warna.id_warna,ms_warna.warna,tr_scan_barcode.tipe 
              FROM tr_penerimaan_unit_dealer_detail LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin=tr_scan_barcode.no_mesin 
              LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
              LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
              LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan 
              LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna WHERE tr_penerimaan_unit_dealer_detail.status_dealer = 'input'
              $filter 
              AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' and tr_scan_barcode.tipe='RFS' 
              AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_sales_order WHERE no_mesin IS NOT NULL)
              AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin FROM tr_sales_order_gc_nosin WHERE no_mesin IS NOT NULL)
              ORDER BY tr_scan_barcode.no_mesin ASC");
              //AND ms_item.id_tipe_kendaraan='$id_tipe_kendaraan' AND ms_item.id_warna = '$id_warna'
            if ($dt_nosin->num_rows()==0){ 
              echo "<option value=''>- choose -</option>";
            }else{
              echo "<option value=''>- choose -</option>";
              foreach ($dt_nosin->result() as $rs){
                  echo "<option value='$rs->no_mesin'>$rs->no_mesin | $rs->no_rangka | $rs->tipe_ahm | $rs->warna</option>";
              }
            } ?>
        </select>
      </td>
      <td>
        <input readonly type="text" autocomplete="off" class="form-control" id="no_rangka_gc" placeholder="No Rangka">
      </td>
      <td>
        <input readonly type="text" autocomplete="off" class="form-control" id="tipe_warna_gc" placeholder="Tipe Warna">
      </td>   
      <td class="serial-is-ev-detail">
      </td> 
      <td>
        <button type="button" class="btn btn-primary btn-xs btn-flat" title="Add" onclick="addDetail_gc()"><i class="fa fa-plus"></i></button>
      </td>
    </tr>
  </tfoot>
</table>   

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Battery EV</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table class="table table-bordered table-hover data-table-scan" id='example2'>
      <thead>
        <tr>
          <th>No</th>
          <th>Dealer</th>    
          <th>Tipe</th>    
          <th>Kode Part</th>    
          <th>Nama Part</th>    
          <th>Serial Number</th>    
          <th>FIFO</th>    
          <th>Aksi</th>    
        </tr>
      </thead>
      <tbody> 
      <?
       $dealer = $this->m_admin->cari_dealer();
       $battery = $this->db->query("SELECT stock_battery_int,serial_number,tipe,fifo,part_desc,part_id,id_dealer FROM tr_stock_battery where no_sales_order is null and id_dealer ='$id_dealer' order by fifo asc")->result();	
       $urut = 1;
       foreach($battery as $row)
       {?>
         <td><?= $urut++ ?></td>
         <td><?= $row->id_dealer?></td>
         <td><?= $row->tipe?></td>
         <td><?= $row->part_id?></td>
         <td><?= $row->part_desc?></td>
         <td><?= $row->serial_number?></td>
         <td><?= $row->fifo?></td>
         <td>
          <button  type="button"  onclick="get_serial_number_ev('<?php echo $row->serial_number; ?>')" class="btn btn-flat btn-success btn-sm btn_get"><i class="fa fa-check"></i></button>     
        </td>
       <?}?>

      </tbody>     
    </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $(".serial-is-ev-detail").click(function() {
      $("#exampleModal").modal('show');
    });
  });

</script>



