<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>

<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>    
    </h1>

    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>        
      <li class="">Report</li>
      <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
    </ol>

  </section>
  <section class="content">
    <?php 
    if($set=="view"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">              
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat *</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal1" name="spk_awal" class="form-control" placeholder="Tgl Mohon Samsat" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal2" name="spk_akhir" class="form-control" placeholder="Tgl BASTD" autocomplete="off">
                  </div>        
                </div>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <?php /*
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  
                  </div> 
                  */?>
                  <div class="col-sm-2">
                    <button type="button" onclick="getExcel()" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-print"></i> Export Excel</button>
                  </div>                 
                </div>                
              </div><!-- /.box-body -->              

              <div class="box-footer">                                                              
                <div style="min-height: 600px">                 
                  <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php }elseif ($set=='cetak') { 
      ?>
    <!DOCTYPE html>
    <html>
    <!-- <html lang="ar"> for arabic only -->
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Cetak</title>
      <style>
        /*@media print {*/
        /*  @page {*/
        /*    sheet-size: 330mm 210mm;*/
        /*    margin-left: 0.8cm;*/
        /*    margin-right: 0.8cm;*/
        /*    margin-bottom: 1cm;*/
        /*    margin-top: 1cm;*/
        /*  }*/
          .text-center{text-align: center;}
          .bold{font-weight: bold;}
          .table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse !important;
           /*border-collapse: separate;*/
          }

          .table-bordered tr td {
            border: 0.01em solid black;
            border-collapse: collapse !important;
            padding-left: 6px;
            padding-right: 6px;
            font-size: 12pt;
          }

          body{
            font-family: "Arial";
            font-size: 12pt;
          }
      </style>
    </head>

    <body>
      <?php if($tanggal1 != '' || $tanggal2!=''){ ?>
        <table class='table table-bordered' style='font-size: 12pt' width='200%'>
          <tr>                          
            <th bgcolor='yellow' class='bold text-center'>Tgl Mohon Samsat</th>
            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>
            <th bgcolor='yellow' class='bold text-center'>Nama Konsumen</th>
            <th bgcolor='yellow' class='bold text-center'>Tipe Kendaraan</th>
            <th bgcolor='yellow' class='bold text-center'>Warna</th>
            <th bgcolor='yellow' class='bold text-center'>No Mesin</th>
            <th bgcolor='yellow' class='bold text-center'>No Rangka</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl BASTD</th>
            <th bgcolor='yellow' class='bold text-center'>No BASTD</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl Transfer KU Dealer ke MD</th>
            <th bgcolor='yellow' class='bold text-center'>Harga BBN (BASTD)</th>
            <th bgcolor='yellow' class='bold text-center'>Biaya BBN ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>Biaya ADM</th>
            <th bgcolor='yellow' class='bold text-center'>Total BBN MD ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl Bayar BBN MD ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>No. Entry Pengeluaran</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl Bayar ADM BBN MD ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>No Entry Pengeluaran</th>        
          </tr>
          <tr>

          <?php
            $start=1;
            foreach ($query->result() as $dw)
            {
                ?>
                <tr>
                <td style=" text-align:'center';"><?php echo $dw->tgl_mohon_samsat ?></td>
                  <td width="300px;"><?php echo $dw->nama_dealer ?></td>
                  <td width="250px;"><?php echo $dw->nama_konsumen ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->id_tipe_kendaraan; ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->id_warna; ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->no_mesin ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->no_rangka ?></td>
                  <td width="250px;"><?php echo $dw->tgl_bastd ?></td> 
                  <td width="250px;"><?php echo $dw->no_bastd ?></td> 
                  <td style=" text-align:'center';"><?php echo $dw->tgl_transfer; ?></td>
                  <td style=" text-align:'center';"><?php echo number_format($dw->biaya_bbn,0,',','.')  ?></td>
                  <td style=" text-align:'center';"><?php echo number_format($dw->biaya_bbn_md_bj,0,',','.') ?></td>	
                  <td style=" text-align:'center';"><?php echo number_format($dw->biaya_adm,0,',','.'); ?></td>
                  <td style=" text-align:'center';"><?php echo number_format(($dw->biaya_adm+$dw->biaya_bbn_md_bj),0,',','.'); ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->tgl_bayar; ?></td>
                  <td style=" text-align:'center';">&nbsp;<?php echo $dw->no_entry; ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->tgl_bayar_adm; ?></td>
                  <td style=" text-align:'center';">&nbsp;<?php echo $dw->no_entry_adm; ?></td>
                </tr>
              <?php
              $start++;
            }
            ?>
        </table> <br>
      <?php }else{ ?>
        <p>Tanggal Harus ditentukan dulu.</p>
      <?php } ?>                
    </body>
  </html>

  <?php } elseif ($set == 'export_excel') { 
    $date_buat = date("dmY-hi", strtotime($date_create));
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=rekap_pembayaran_bbn_biro-$date_buat.xls");
    ?>

    <!DOCTYPE html>
    <html>
    <!-- <html lang="ar"> for arabic only -->
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Cetak</title>
      <style>
        @media print {
          @page {
            sheet-size: 330mm 210mm;
            margin-left: 0.8cm;
            margin-right: 0.8cm;
            margin-bottom: 1cm;
            margin-top: 1cm;
          }

          .text-center{text-align: center;}
          .bold{font-weight: bold;}
          .table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
           /*border-collapse: separate;*/
          }

          .table-bordered tr td {
            border: 0.01em solid black;
            padding-left: 6px;
            padding-right: 6px;
          }

          body{
            font-family: "Arial";
            font-size: 11pt;
          }
        }
      </style>

    </head>

    <body>
      <?php if($tanggal1 != '' || $tanggal2 !=''){ ?>
        <table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>
          <?php if($tanggal1 != ''){ ?>
          <tr> 
            <td>Tgl Mohon Samsat </td>
            <td><?php echo $tanggal1;?></td>
          </tr>
          <?php 
          }
          ?>
          <?php if($tanggal2 != ''){ ?>
          <tr>
            <td>Tgl BASTD </td>
            <td><?php echo $tanggal2;?></td>
          </tr>
          <?php 
          }
          ?>
          <tr>  
          <th bgcolor='yellow' class='bold text-center'>Tgl Mohon Samsat</th>
            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>
            <th bgcolor='yellow' class='bold text-center'>Nama Konsumen</th>
            <th bgcolor='yellow' class='bold text-center'>Tipe Kendaraan</th>
            <th bgcolor='yellow' class='bold text-center'>Warna</th>
            <th bgcolor='yellow' class='bold text-center'>No Mesin</th>
            <th bgcolor='yellow' class='bold text-center'>No Rangka</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl BASTD</th>
            <th bgcolor='yellow' class='bold text-center'>No BASTD</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl Transfer KU Dealer ke MD</th>
            <th bgcolor='yellow' class='bold text-center'>Harga BBN (BASTD)</th>
            <th bgcolor='yellow' class='bold text-center'>Biaya BBN ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>Biaya ADM</th>
            <th bgcolor='yellow' class='bold text-center'>Total BBN MD ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl Bayar BBN MD ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>No. Entry Pengeluaran</th>
            <th bgcolor='yellow' class='bold text-center'>Tgl Bayar ADM BBN MD ke Biro Jasa</th>
            <th bgcolor='yellow' class='bold text-center'>No Entry Pengeluaran</th>    
          </tr>

          <?php
            $start=1;
            foreach ($query->result() as $dw)
            {
                ?>
                <tr>
                  <td style=" text-align:'center';"><?php echo $dw->tgl_mohon_samsat ?></td>
                  <td width="300px;"><?php echo $dw->nama_dealer ?></td>
                  <td width="250px;"><?php echo $dw->nama_konsumen ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->id_tipe_kendaraan; ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->id_warna; ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->no_mesin ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->no_rangka ?></td>
                  <td width="250px;"><?php echo $dw->tgl_bastd ?></td> 
                  <td width="250px;"><?php echo $dw->no_bastd ?></td> 
                  <td style=" text-align:'center';"><?php echo $dw->tgl_transfer; ?></td>
                  <td style=" text-align:'center';"><?php echo number_format($dw->biaya_bbn,0,',','.')  ?></td>
                  <td style=" text-align:'center';"><?php echo number_format($dw->biaya_bbn_md_bj,0,',','.') ?></td>	
                  <td style=" text-align:'center';"><?php echo number_format($dw->biaya_adm,0,',','.'); ?></td>
                  <td style=" text-align:'center';"><?php echo number_format(($dw->biaya_adm+$dw->biaya_bbn_md_bj),0,',','.'); ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->tgl_bayar; ?></td>
                  <td style=" text-align:'center';">&nbsp;<?php echo $dw->no_entry; ?></td>
                  <td style=" text-align:'center';"><?php echo $dw->tgl_bayar_adm; ?></td>
                  <td style=" text-align:'center';">&nbsp;<?php echo $dw->no_entry_adm; ?></td>
                </tr>
                <?php
                $start++;
            }
            ?>
        </table> <br>
      <?php }else{ ?>
        <p>Tanggal Harus ditentukan dulu.</p>
      <?php } ?>                
    </body>
  </html>
  <?php } ?>
  </section>
</div>

<script>
function getReport(){
  var value={
    tanggal1:document.getElementById("tanggal1").value,
    tanggal2:document.getElementById("tanggal2").value,
    cetak:'cetak',
  }
 
  if (value.tanggal1 == '') {
    alert('Tgl Mohon Samsat wajib diisi!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_pembayaran_bbn_biro?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);
    // $("#showReport").attr("src",'<?php echo site_url("h1/Lap_pembayaran_bbn_biro?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}

function getExcel(){
  var value={
    tanggal1:document.getElementById("tanggal1").value,
    tanggal2:document.getElementById("tanggal2").value,
    cetak:'export_excel',
    //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
  }

  if (value.tanggal1 == '') {
    alert('Tgl Mohon Samsat wajib diisi!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_pembayaran_bbn_biro?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
</script>