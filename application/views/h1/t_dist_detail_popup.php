<?php if($jenis=='qty_plan'){ ?>
<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th colspan="4">Detail Qty Plan</th>
    </tr>
    <tr>
      <th>Tipe Kendaraan</th>              
      <th>Warna</th>              
      <th>Tanggal</th>                  
      <th>Qty Plan</th>              
    </tr>
  </thead>
  <tbody> 
  <?php 
  foreach ($dt_displan->result() as $isi) {
    $bulan = substr($isi->tanggal, 2,2);
    $tahun = substr($isi->tanggal, 4,4);
    $tgl = substr($isi->tanggal, 0,2);
    $tanggal = $tgl."-".$bulan."-".$tahun;
    echo "
      <tr>
        <td>$isi->id_tipe_kendaraan | $isi->tipe_ahm</td>
        <td>$isi->id_warna | $isi->warna</td>
        <td>$tanggal</td>
        <td>$isi->qty_plan</td>
      </tr>";
  }
  ?>    
  </tbody>
</table>
<?php }elseif ($jenis=='qty_do') { ?>
<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th colspan="5">Detail Qty Pembukaan DO</th>
    </tr>
    <tr>
      <th>No SIPB</th>              
      <th>Tgl SIPB</th>              
      <th>Tipe</th>                  
      <th>Warna</th>
      <th>Qty</th>              
    </tr>
  </thead>
  <tbody>     
  <?php 
  foreach ($dt_displan->result() as $isi) {
    $bulan = substr($isi->tgl_sipb, 2,2);
    $tahun = substr($isi->tgl_sipb, 4,4);
    $tgl = substr($isi->tgl_sipb, 0,2);
    $tanggal = $tgl."-".$bulan."-".$tahun;
    echo "
      <tr>
        <td>$isi->no_sipb</td>
        <td>$tanggal</td>
        <td>$isi->id_tipe_kendaraan | $isi->tipe_ahm</td>
        <td>$isi->id_warna | $isi->warna</td>
        <td>$isi->jumlah</td>
      </tr>";
  }
  ?> 
  </tbody>
</table>
<?php }elseif ($jenis=='qty_sl') { ?>
<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th colspan="6">Detail Qty Intransit</th>
    </tr>
    <tr>
      <th>No Mesin</th>              
      <th>No Rangka</th>              
      <th>Tipe</th>                  
      <th>Warna</th>
      <th>No SL</th>              
      <th>Tgl SL</th>
    </tr>
  </thead>
  <tbody>     
  <?php 
  foreach ($dt_displan->result() as $isi) {
    $bulan = substr($isi->tgl_sl, 2,2);
    $tahun = substr($isi->tgl_sl, 4,4);
    $tgl = substr($isi->tgl_sl, 0,2);
    $tanggal = $tgl."-".$bulan."-".$tahun;
    echo "
      <tr>
        <td>$isi->no_mesin</td>
        <td>$isi->no_rangka</td>
        <td>$isi->id_tipe_kendaraan | $isi->tipe_ahm</td>
        <td>$isi->id_warna | $isi->warna</td>
        <td>$isi->no_shipping_list</td>
        <td>$tanggal</td>
      </tr>";
  }
  ?> 
  </tbody>
</table>
<?php }elseif ($jenis=='qty_pu') { ?>
<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <th colspan="6">Detail Qty Received</th>
    </tr>
    <tr>
      <th>No Mesin</th>              
      <th>No Rangka</th>              
      <th>Tipe</th>                  
      <th>Warna</th>      
      <th>Tgl Terima</th>
    </tr>
  </thead>
  <tbody>     
  <?php 
  foreach ($dt_displan->result() as $isi) {    
    echo "
      <tr>
        <td>$isi->no_mesin</td>
        <td>$isi->no_rangka</td>
        <td>$isi->id_tipe_kendaraan | $isi->tipe_ahm</td>
        <td>$isi->id_warna | $isi->warna</td>        
        <td>$isi->tgl_penerimaan</td>
      </tr>";
  }
  ?> 
  </tbody>
</table>
<?php } ?>