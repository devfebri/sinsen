
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

                  <label for="inputEmail3" class="col-sm-2 control-label">Dari tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal1" name="spk_awal" class="form-control" placeholder="Periode Awal" autocomplete="off">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Sampai tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal2" name="spk_akhir" class="form-control" placeholder="Periode Akhir" autocomplete="off">

                  </div>                  

                </div>

                

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-2">

                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  

                  </div> 

                  <div class="col-sm-2">

                    <button type="button" onclick="getExcel()" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-print"></i> Export Excel</button>

                  </div>   

                  <div class="col-sm-2">

                    <button type="button" onclick="getCsv()" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-print"></i> Export CSV</button>

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

        }

      </style>

    </head>

    <body>

      <?php if($tanggal2 != ''){ ?>

        

        <h2>Laporan Finance Company Indent Priority System (FIPS) </h2>
        <h3>Dari tanggal : <?php echo $tanggal1 ?> Sampai Tanggal : <?php echo $tanggal2 ?></h3>
        <table class='table table-bordered' style='font-size: 12pt' width='200%'>

          <tr>                          

            <th bgcolor='yellow' class='bold text-center'>No</th>

            <th bgcolor='yellow' class='bold text-center'>Tgl SPK</th>          
            <th bgcolor='yellow' class='bold text-center'>No SPK</th>          
            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>          
            <th bgcolor='yellow' class='bold text-center'>Nama Konsumen</th>          
            <th bgcolor='yellow' class='bold text-center'>Deskripsi Type</th>          
            <th bgcolor='yellow' class='bold text-center'>Type</th>          
            <th bgcolor='yellow' class='bold text-center'>Warna</th>          
            <th bgcolor='yellow' class='bold text-center'>Nama Finco</th>          
            <th bgcolor='yellow' class='bold text-center'>Tgl PO</th>          
            <th bgcolor='yellow' class='bold text-center'>No. PO</th>          

          </tr>

          

          <tr>

          

          <?php

            $start=1;

            foreach ($query->result() as $dw)

            {

                ?>

                <?php if ( ($dw->jenis_beli == 'Kredit' and $dw->po_dari_finco!='') OR ($dw->jenis_beli == 'Cash') ): ?>
                  <tr>

                    <td><?php echo $start ?></td>

                    <td><?php echo $dw->tgl_spk ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->no_spk ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->nama_dealer ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->nama_konsumen ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->tipe_ahm ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->id_tipe_kendaraan ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->id_warna ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->finance_company ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->tgl_pembuatan_po ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->po_dari_finco ?></td>

                      

                  </tr>
                <?php endif ?>

                <?php

                $start++;

            }

            ?>



        </table> <br>

      <?php }else{ ?>

        <p>Tanggal Rekap Harus ditentukan dulu.</p>

      <?php } ?>                

    </body>

  </html>

  <?php } elseif ($set == 'export_excel') { 

    $date_buat = date("dmY-hi", strtotime($date_create));

    header("Content-type: application/vnd-ms-excel");

    header("Content-Disposition: attachment; filename=laporan_fips-$date_buat.xls");

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

      <?php if($tanggal2 != ''){ ?>

        

        <h2>Laporan Finance Company Indent Priority System (FIPS) </h2>
        <h3>Dari tanggal : <?php echo $tanggal1 ?> Sampai Tanggal : <?php echo $tanggal2 ?></h3>

        <table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>

          <tr>  



          <th bgcolor='yellow' class='bold text-center'>No</th>

          <th bgcolor='yellow' class='bold text-center'>Tgl SPK</th>          
            <th bgcolor='yellow' class='bold text-center'>No SPK</th>          
            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>          
            <th bgcolor='yellow' class='bold text-center'>Nama Konsumen</th>          
            <th bgcolor='yellow' class='bold text-center'>Deskripsi Type</th>          
            <th bgcolor='yellow' class='bold text-center'>Type</th>          
            <th bgcolor='yellow' class='bold text-center'>Warna</th>          
            <th bgcolor='yellow' class='bold text-center'>Nama Finco</th>          
            <th bgcolor='yellow' class='bold text-center'>Tgl PO</th>          
            <th bgcolor='yellow' class='bold text-center'>No. PO</th> 
            <th bgcolor='yellow' class='bold text-center'>Status Indent</th> 
            <th bgcolor='yellow' class='bold text-center'>Jenis Beli</th> 



          </tr>

          

          <?php

            $start=1;

            foreach ($query->result() as $dw)

            {

                ?>

                <?php if ( ($dw->jenis_beli == 'Kredit' and $dw->po_dari_finco!='') OR ($dw->jenis_beli == 'Cash') ): ?>

                  <tr>

                    <td><?php echo $start ?></td>

                    <td><?php echo $dw->tgl_spk ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->no_spk ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->nama_dealer ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->nama_konsumen ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->tipe_ahm ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->id_tipe_kendaraan ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->id_warna ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->finance_company ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->tgl_pembuatan_po ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->po_dari_finco ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->status_indent ?></td>
                    <td style=" text-align:'center';"><?php echo $dw->jenis_beli ?></td>

                  </tr>
                  
                <?php endif ?>

                <?php

                $start++;

            }

            ?>



        </table> <br>

      <?php }else{ ?>

        <p>Tanggal Rekap Harus ditentukan dulu.</p>

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



  if (value.tanggal1 == '' && value.tanggal2 == '') {

    alert('Isi data dengan lengkap ..!');

    return false;

  }else{

    //alert(value.tipe);

    $('.loader').show();

    $('#btnShow').disabled;

    $("#showReport").attr("src",'<?php echo site_url("h1/laporan_fips?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

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



  if (value.tanggal1 == '' && value.tanggal2 == '') {

    alert('Isi data dengan lengkap ..!');

    return false;

  }else{

    //alert(value.tipe);

    $('.loader').show();

    $('#btnShow').disabled;

    $("#showReport").attr("src",'<?php echo site_url("h1/laporan_fips?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

    document.getElementById("showReport").onload = function(e){          

    $('.loader').hide();       

    };

  }

}


function getCsv(){

  var value={

            tanggal1:document.getElementById("tanggal1").value,

            tanggal2:document.getElementById("tanggal2").value,

            

            cetak:'export_csv',

            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),

            }



  if (value.tanggal1 == '' && value.tanggal2 == '') {

    alert('Isi data dengan lengkap ..!');

    return false;

  }else{

    //alert(value.tipe);

    $('.loader').show();

    $('#btnShow').disabled;

    $("#showReport").attr("src",'<?php echo site_url("h1/laporan_fips?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

    document.getElementById("showReport").onload = function(e){          

    $('.loader').hide();       

    };

  }

}

</script>