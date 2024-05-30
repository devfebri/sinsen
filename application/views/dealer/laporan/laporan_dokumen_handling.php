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

            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">

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

                    <button type="button" onclick="getStock()" name="process" value="edit" class="btn bg-white btn-flat"><i class="fa fa-download"></i> Export (SSU)</button>

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

        

        

        <table class='table table-bordered' style='font-size: 12pt' width='200%'>
          <tr>           
            <th bgcolor='lightblue' class='bold' rowspan="2">No.</th>      
            <th bgcolor='lightblue' class='bold' rowspan="2">Kode Dealer</th>      
            <th bgcolor='lightblue' class='bold' rowspan="2">Dealer</th>      
            <th bgcolor='lightblue' class='bold' colspan="3">STNK</th>      
            <th bgcolor='lightblue' class='bold' colspan="3">BPKB</th>      
            <th bgcolor='lightblue' class='bold' colspan="3">PLAT</th>  
          </tr>
          <tr>
            <th bgcolor='lightblue' class='bold'>Distribusi MD to D</th>
            <th bgcolor='lightblue' class='bold'>Terima D</th>
            <th bgcolor='lightblue' class='bold'>Serah Terima to Customer</th>
            <th bgcolor='lightblue' class='bold'>Distribusi MD to D</th>
            <th bgcolor='lightblue' class='bold'>Terima D</th>
            <th bgcolor='lightblue' class='bold'>Serah Terima to Customer</th>
            <th bgcolor='lightblue' class='bold'>Distribusi MD to D</th>
            <th bgcolor='lightblue' class='bold'>Terima D</th>
            <th bgcolor='lightblue' class='bold'>Serah Terima to Customer</th>
          </tr>

          

          <tr>

          

          <?php

            $start=1;

            foreach ($query->result() as $dw)

            {

                ?>

                <tr>
		                <td><?php echo $start ?></td>
                    <td><?php echo $dw->kode_dealer_md ?></td>
                    <td><?php echo $dw->nama_dealer ?></td>
                    <td><?php echo $dw->total_dist_stnk ?></td>
                    <td><?php echo $dw->total_terima_stnk ?></td>
                    <td><?php echo $dw->total_serah_stnk ?></td>
                    <td><?php echo $dw->total_dist_bpkb ?></td>
                    <td><?php echo $dw->total_terima_bpkb ?></td>
                    <td><?php echo $dw->total_serah_bpkb ?></td>
                    <td><?php echo $dw->total_dist_plat ?></td>
                    <td><?php echo $dw->total_terima_plat ?></td>
                    <td><?php echo $dw->total_serah_plat ?></td>
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

    header("Content-Disposition: attachment; filename=Document_Handling-$date_buat.xls");

    ?>

    <!DOCTYPE html>

    <html>

    <!-- <html lang="ar"> for arabic only -->

    <head>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

      <title>Cetak</title>

      <style>

        .str{ mso-number-format:\@; } 

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

        

        <table>
          <tr>
            <th>Periode : </th>
            <th><?php echo tgl_indo($tanggal1).' - '. tgl_indo($tanggal2) ?></th>
          </tr>
        </table>
        <tr></tr>

        <table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>
          <tr>           
            <th bgcolor='lightblue' class='bold' rowspan="2">No.</th>      
            <th bgcolor='lightblue' class='bold' rowspan="2">Kode Dealer</th>      
            <th bgcolor='lightblue' class='bold' rowspan="2">Dealer</th>      
            <th bgcolor='lightblue' class='bold' colspan="3">STNK</th>      
            <th bgcolor='lightblue' class='bold' colspan="3">BPKB</th>      
            <th bgcolor='lightblue' class='bold' colspan="3">PLAT</th>  
          </tr>
          <tr>
            <th bgcolor='lightblue' class='bold'>Distribusi MD to D</th>
            <th bgcolor='lightblue' class='bold'>Terima D</th>
            <th bgcolor='lightblue' class='bold'>Serah Terima to Customer</th>
            <th bgcolor='lightblue' class='bold'>Distribusi MD to D</th>
            <th bgcolor='lightblue' class='bold'>Terima D</th>
            <th bgcolor='lightblue' class='bold'>Serah Terima to Customer</th>
            <th bgcolor='lightblue' class='bold'>Distribusi MD to D</th>
            <th bgcolor='lightblue' class='bold'>Terima D</th>
            <th bgcolor='lightblue' class='bold'>Serah Terima to Customer</th>
          </tr>

          

          <?php
            $tot_stnk1 = 0;
            $tot_stnk2 = 0;
            $tot_stnk3 = 0;
            $tot_bpkb1 = 0;
            $tot_bpkb2 = 0;
            $tot_bpkb3 = 0;
            $tot_plat1 = 0;
            $tot_plat2 = 0;
            $tot_plat3 = 0;
            $start=1;

            foreach ($query->result() as $dw)

            {

                ?>

                <tr>
                    <td><?php echo $start ?></td>
                    <td class="str"><?php echo $dw->kode_dealer_md ?></td>
                    <td><?php echo $dw->nama_dealer ?></td>
                    <td><?php echo $dw->total_dist_stnk ?></td>
                    <td><?php echo $dw->total_terima_stnk ?></td>
                    <td><?php echo $dw->total_serah_stnk ?></td>
                    <td><?php echo $dw->total_dist_bpkb ?></td>
                    <td><?php echo $dw->total_terima_bpkb ?></td>
                    <td><?php echo $dw->total_serah_bpkb ?></td>
                    <td><?php echo $dw->total_dist_plat ?></td>
                    <td><?php echo $dw->total_terima_plat ?></td>
                    <td><?php echo $dw->total_serah_plat ?></td>
                </tr>

                <?php

                $tot_stnk1 = $tot_stnk1 + $dw->total_dist_stnk;
                $tot_stnk2 = $tot_stnk2 + $dw->total_terima_stnk;
                $tot_stnk3 = $tot_stnk3 + $dw->total_serah_stnk;
                $tot_bpkb1 = $tot_bpkb1 + $dw->total_dist_bpkb;
                $tot_bpkb2 = $tot_bpkb2 + $dw->total_terima_bpkb;
                $tot_bpkb3 = $tot_bpkb3 + $dw->total_serah_bpkb;
                $tot_plat1 = $tot_plat1 + $dw->total_dist_plat;
                $tot_plat2 = $tot_plat2 + $dw->total_terima_plat;
                $tot_plat3 = $tot_plat3 + $dw->total_serah_plat;

                $start++;

            }

            ?>
            <tr>
              <th colspan="3" class='bold'>Total</th>
              <td><?php echo $tot_stnk1; ?></td>
              <td><?php echo $tot_stnk2; ?></td>
              <td><?php echo $tot_stnk3; ?></td>
              <td><?php echo $tot_bpkb1; ?></td>
              <td><?php echo $tot_bpkb2; ?></td>
              <td><?php echo $tot_bpkb3; ?></td>
              <td><?php echo $tot_plat1; ?></td>
              <td><?php echo $tot_plat2; ?></td>
              <td><?php echo $tot_plat3; ?></td>
            </tr>



        </table> <br><br>

        <table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>
          <tr>           
            <th bgcolor='lightblue' class='bold text-center' colspan="3">Percentage Document Handling</th> 
          </tr>
          <?php error_reporting(0); ?>
          <tr>
            <td>1</td>
            <td>STNK</td>
            <td><?php echo number_format(($tot_stnk3/$tot_stnk1) * 100, 2) ?>%</td>
          </tr>
          <tr>
            <td>2</td>
            <td>BPKB</td>
            <td><?php echo number_format(($tot_bpkb3/$tot_bpkb1) * 100, 2) ?>%</td>
          </tr>
          <tr>
            <td>3</td>
            <td>PLAT</td>
            <td><?php echo number_format(($tot_plat3/$tot_plat1) * 100, 2) ?>%</td>
          </tr>


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

    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_dokumen_handling?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

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

    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_dokumen_handling?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

    document.getElementById("showReport").onload = function(e){          

    $('.loader').hide();       

    };

  }

}


function getStock(){
	var value={
            tanggal1:document.getElementById("tanggal1").value,
            tanggal2:document.getElementById("tanggal2").value,
            cetak:'export_stock',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
	}	
	if (value.tanggal1 == '' && value.tanggal2 == '') {
		alert('Isi data dengan lengkap ..!');
		return false;
	}else{
		//alert(value.tipe);
		$('.loader').show();
		$('#btnShow').disabled;
		$("#showReport").attr("src",'<?php echo site_url("h1/Lap_dokumen_handling?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);
		document.getElementById("showReport").onload = function(e){          
			$('.loader').hide();       
		};
	}
}


</script>