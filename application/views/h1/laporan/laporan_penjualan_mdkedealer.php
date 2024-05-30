<?php 



function mata_uang3($a){



  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);



    if(is_numeric($a) AND $a != 0 AND $a != ""){



      return number_format($a, 0, ',', '.');



    }else{



      return $a;



    }        



}



function bln($a){



  $bulan=$bl=$month=$a;



  switch($bulan)



  {



    case"1":$bulan="Januari"; break;



    case"2":$bulan="Februari"; break;



    case"3":$bulan="Maret"; break;



    case"4":$bulan="April"; break;



    case"5":$bulan="Mei"; break;



    case"6":$bulan="Juni"; break;



    case"7":$bulan="Juli"; break;



    case"8":$bulan="Agustus"; break;



    case"9":$bulan="September"; break;



    case"10":$bulan="Oktober"; break;



    case"11":$bulan="November"; break;



    case"12":$bulan="Desember"; break;



  }



  $bln = $bulan;



  return $bln;



}



?>



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



        

        <center>

            <h1>PT. SINAR SENTOSA PRIMATAMA </h1>

            <h2>LAPORAN PENJUALAN MD KE DEALER</h2>

            <h2>PERIODE : <?php echo date('d-m-Y',strtotime($tanggal1)); ?> s/d <?php echo date('d-m-Y',strtotime($tanggal2)); ?></h2>

        </center>

        

        



        <table class='table table-bordered' style='font-size: 12pt' width='200%'>



          <tr>                          



            <th bgcolor='yellow' class='bold text-center'>No</th>



            <th bgcolor='yellow' class='bold text-center'>Tgl Faktur</th>



            <th bgcolor='yellow' class='bold text-center'>No Faktur</th>



            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>



            <th bgcolor='yellow' class='bold text-center'>No Mesin</th>



            <th bgcolor='yellow' class='bold text-center'>No Rangka</th>



            <th bgcolor='yellow' class='bold text-center'>No DO</th>



            <th bgcolor='yellow' class='bold text-center'>No Picking List</th>



            <th bgcolor='yellow' class='bold text-center'>No Surat Jalan</th>



            <th bgcolor='yellow' class='bold text-center'>Harga</th>



            <th bgcolor='yellow' class='bold text-center'>Disc SCP</th>



            <th bgcolor='yellow' class='bold text-center'>Tipe Faktur</th>



            <th bgcolor='yellow' class='bold text-center'>Kode Tipe</th>



            <th bgcolor='yellow' class='bold text-center'>Kode Warna</th>



            <th bgcolor='yellow' class='bold text-center'>Return</th>



            



          </tr>



          



          <tr>



          



          <?php



            $start=1;



            foreach ($query->result() as $dw)



            {



                ?>



                <tr>



		      <td><?php echo $start ?></td>



          <td style=" text-align:'center';"><?php echo $dw->tgl_faktur ?></td>



		      <td width="300px;"><?php echo $dw->no_faktur ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->nama_dealer ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->no_mesin ?></td>



		      <td width="250px;"><?php echo $dw->no_rangka ?></td>


		      <td width="300px;"><?php echo $dw->no_do ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->no_picking_list ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->no_surat_jalan ?></td>



		      <td style=" text-align:'center';"><?php echo number_format($dw->harga,0,',','.')  ?></td>

		      <td style=" text-align:'center';"><?php echo number_format($dw->disc_scp,0,',','.')  ?></td>



          <td style=" text-align:'center';"><?php echo $dw->deskripsi_ahm ?></td>



          <td style=" text-align:'center';"><?php echo $dw->tipe_motor ?></td>



          <td style=" text-align:'center';"><?php echo $dw->warna ?></td>



          <td style=" text-align:'center';"><?php echo ($dw->retur == '1') ? 'Ya' : 'Tidak' ?></td>



		      



                </tr>



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



    header("Content-Disposition: attachment; filename=lap_penjualan_md_ke_dealer-$date_buat.xls");



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



        

        <center>

            <h1>PT. SINAR SENTOSA PRIMATAMA </h1>

            <h2>LAPORAN PENJUALAN MD KE DEALER</h2>

            <h2>PERIODE : <?php echo date('d-m-Y',strtotime($tanggal1)); ?> s/d <?php echo date('d-m-Y',strtotime($tanggal2)); ?></h2>

        </center>

        



        <table class='table table-bordered' style='font-size: 12pt' width='200%' border="1">



          <tr>                          



          <th bgcolor='yellow' class='bold text-center'>No</th>



            <th bgcolor='yellow' class='bold text-center'>Tgl Faktur</th>



            <th bgcolor='yellow' class='bold text-center'>No Faktur</th>



            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>



            <th bgcolor='yellow' class='bold text-center'>No Mesin</th>



            <th bgcolor='yellow' class='bold text-center'>No Rangka</th>



            <th bgcolor='yellow' class='bold text-center'>No DO</th>



            <th bgcolor='yellow' class='bold text-center'>No Picking List</th>



            <th bgcolor='yellow' class='bold text-center'>No Surat Jalan</th>



            <th bgcolor='yellow' class='bold text-center'>Harga</th>



            <th bgcolor='yellow' class='bold text-center'>Disc SCP</th>



            <th bgcolor='yellow' class='bold text-center'>Tipe Faktur</th>



            <th bgcolor='yellow' class='bold text-center'>Kode Tipe</th>



            <th bgcolor='yellow' class='bold text-center'>Kode Warna</th>



            <th bgcolor='yellow' class='bold text-center'>Return</th>



            



          </tr>



          



          



          

            

          <?php



            $start=1;



            foreach ($query->result() as $dw)



            {



                ?>



            <tr>



            <td><?php echo $start ?></td>



            <td style=" text-align:'center';"><?php echo $dw->tgl_faktur ?></td>



		      <td width="300px;"><?php echo $dw->no_faktur ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->nama_dealer ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->no_mesin ?></td>



		      <td width="250px;"><?php echo $dw->no_rangka ?></td>



		      <td width="300px;"><?php echo $dw->no_do ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->no_picking_list ?></td>



		      <td style=" text-align:'center';"><?php echo $dw->no_surat_jalan ?></td>



		      <td style=" text-align:'center';"><?php echo number_format($dw->harga,0,',','.')  ?></td>

		      <td style=" text-align:'center';"><?php echo number_format($dw->disc_scp,0,',','.')  ?></td>



          <td style=" text-align:'center';"><?php echo $dw->deskripsi_ahm ?></td>



          <td style=" text-align:'center';"><?php echo $dw->tipe_motor ?></td>



          <td style=" text-align:'center';"><?php echo $dw->warna ?></td>



          <td style=" text-align:'center';"><?php echo ($dw->retur == '1') ? 'Ya' : 'Tidak' ?></td>



		      



                </tr>



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



    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_penjualan_mdkedealer?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);



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



    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_penjualan_mdkedealer?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);



    document.getElementById("showReport").onload = function(e){          



    $('.loader').hide();       



    };



  }



}



</script>