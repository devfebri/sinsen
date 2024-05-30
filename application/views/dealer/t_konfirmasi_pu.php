<?php if (isset($scan)) { ?>
  <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>            
              <th>Tipe</th>
              <th>Warna</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;                 
          foreach ($dt_scan->result() as $ve2) {            
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->no_mesin</td>
              <td>$ve2->no_rangka</td>
              <td>$ve2->tipe_motor</td>
              <td>$ve2->warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="choose_nosin('<?php echo $ve2->no_mesin; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
          <?php
            $no++;
          }          
          ?>
          </tbody>
        </table>
<?php }else{ ?>

<?php  if($jenis == 'rfs'){ ?>
<button class="btn btn-block btn-flat btn-success" disabled=""><?php echo strtoupper($jenis) ?></button>
<br>
<table id="table table1" class="table table-bordered table-hover">
  <thead>
    <tr>              
      <th>No Mesin</th>              
      <th>No Rangka</th>              
      <th>Tipe Motor</th>
      <th>Warna</th>
      <th>Kode Item</th>              
      <th>Status</th>
      <th>No FIFO</th>
      <th width="5%">Aksi</th>
    </tr>
  </thead>
  <tbody>            
  <?php   
    foreach ($dt_data->result() as $isi) {        
      echo "
      <tr>
        <td>$isi->no_mesin</td>
        <td>$isi->no_rangka</td>
        <td>$isi->tipe_ahm</td>
        <td>$isi->warna</td>
        <td>$isi->id_item</td>
        <td>".strtoupper($jenis)."</td>
        <td>$isi->fifo</td>"?>
        <td width='5%'>
        <?php 
        $this->db->where('no_mesin', $isi->no_mesin);
        $this->db->where('retur', 0);
        $cek_nosin = $this->db->get('tr_penerimaan_unit_dealer_detail');
        if ($cek_nosin->num_rows() > 1): ?>
          <a onclick="javasciprt: return confirm('Apakah yakin akan menghapus data ini ?')" href="<?php echo base_url() ?>dealer/konfirmasi_pu/delete_nosin_double?id=<?php echo $isi->id_sj ?>&no_mesin=<?php echo $isi->no_mesin ?>" class="label label-danger">Hapus</a>
        <?php endif ?>
        </td> 
      </tr>

    <?php
    }  
  ?>
  </tbody>
</table>
<?php }else{ ?>
<button class="btn btn-block btn-flat btn-warning" disabled=""><?php echo strtoupper($jenis) ?></button>
<br>
<table id="table table1" class="table table-bordered table-striped">
  <thead>
    <tr>              
      <th>No Mesin</th>              
      <th>No Rangka</th>              
      <th>Kode Item</th>              
      <th>No FIFO</th>
      <th style="text-align: center;width: 35% ">Part</th>
    </tr>
  </thead>
  <tbody>            
  <?php   
    foreach ($dt_data->result() as $key=> $isi) {   
      $get_dokumen_id = $this->db->get_where('tr_dokumen_nrfs',['no_mesin'=>$isi->no_mesin])->result();
      echo "
      <tr>
        <td>
          $isi->no_mesin </br>
          Sumber Kerusakan : $isi->sumber_kerusakan</br>
          Dokumen NRFS ID :</br>
          ";
          foreach ($get_dokumen_id as $rs) {
            echo '- '.$rs->dokumen_nrfs_id.'</br>';
          }
       echo" </td>
        <td>$isi->no_rangka</td>
        <td>$isi->id_item</td>
        <td>$isi->fifo</td>"?>
        <td align="center">
          <table class="table table-bordered">
            <tr>
              <td>Need Parts</td>
              <td colspan="2" style="width: 100%">
              <select  id="need_parts_<?= $isi->no_mesin?>" name="need_parts_<?= $isi->no_mesin?>" onchange="setNeedParts('<?= $isi->no_mesin ?>')">
                <option value="">--choose--</option>
                <option value="yes"<?= isset($_SESSION[$isi->no_mesin])?$_SESSION[$isi->no_mesin]=='yes'?'selected':'':'' ?> >Yes</option>
                <option value="no"<?= isset($_SESSION[$isi->no_mesin])?$_SESSION[$isi->no_mesin]=='no'?'selected':'':'' ?> >No</option>
              </select></td>
            </tr>
            <tr>
              <td><b>Nomor Parts</b></td>
              <td colspan="2"><b>Kuantitas Part</b></td>
            </tr>
            <tr>
              <td style="width: 70%"><input onclick="showModalPart('<?= $isi->no_mesin ?>')" readonly id="id_part_<?=$isi->no_mesin?>" style="width: 90%" class="form-control isi" type="text"></td>
              <td style="width: 25%"><input id="qty_part_<?=$isi->no_mesin?>" style="width: 90%" class="form-control isi" type="text"></td>
              <td><button type="button" onclick="addPart('<?= $isi->no_mesin ?>')" class="btn btn-flat btn-primary btn-xs"><i class="fa fa-plus"></i></button></td>
            </tr>
            <?php if($part_add = $this->part_add->get_content()) { ?>
              <?php foreach ($part_add as $prt): 
                if ($prt['no_mesin']==$isi->no_mesin) { ?>
                  <tr>
                    <td><?= $prt['id_part'] ?></td>
                    <td><?= $prt['qty'] ?></td>
                    <td><button data-toggle="tooltip" title="Delete" class="btn btn-danger btn-xs" type="button" onclick="delPart('<?= $prt['rowid']?>')"><i class="fa fa-trash" ></i></button></td>
                  </tr>  
               <?php }
              ?>
              <?php endforeach ?>
            <?php } ?>
          </table>
        </td>
        <!--<td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button"       
            onclick="hapus_data('<?php echo $isi->id_penerimaan_unit_dealer_detail; ?>','<?php echo $isi->no_mesin; ?>','detail')"></button> 
        </td> -->
      </tr>

    <?php
    }  
  ?>
  </tbody>
</table>

<?php } ?>
<?php } ?>