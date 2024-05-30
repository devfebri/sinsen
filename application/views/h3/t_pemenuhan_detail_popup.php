<?php $row = $dt_sql->row(); ?>
<form class="form-horizontal" action="h1/wo/save" method="post" enctype="multipart/form-data">                  
  <div class="form-group">                  
    <label for="inputEmail3" class="col-sm-2 control-label">Kode Part</label>
    <div class="col-sm-4">
      <input type="text" name="tgl_checker" value="<?php echo $row->id_part ?>" placeholder="Tanggal wo" class="form-control" readonly>
    </div>                  
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Nama Part</label>
    <div class="col-sm-10">
      <input type="text" name="no_mesin" value="<?php echo $row->nama_part ?>" placeholder="No Mesin" readonly class="form-control">
    </div>                      
  </div>                   
</form>
<table id="example2" class="table table-bordered table-hovered myTable1" width="100%">
  <thead>
    <th>Request ID</th>
    <th>Dokumen NRFS ID</th>
    <th>Nomor Mesin</th>
  </thead>
  <tbody>
    <?php 
    $dt = $this->db->query("SELECT * FROM tr_part_request_nrfs LEFT JOIN tr_dokumen_nrfs ON tr_part_request_nrfs.dokumen_nrfs_id = tr_dokumen_nrfs.dokumen_nrfs_id 
        WHERE tr_part_request_nrfs.request_id = '$request_id'");
    foreach ($dt->result() as $isi) {
      echo "
      <tr>
        <td>$request_id</td>
        <td>$isi->dokumen_nrfs_id</td>
        <td>$isi->no_rangka</td>
      </tr>
      ";
    }
    ?>
  </tbody>
</table>