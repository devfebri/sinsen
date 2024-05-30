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

                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mohon Samsat</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal1" name="spk_awal" class="form-control" placeholder="Periode Awal" autocomplete="off">

                  </div>
                  <?php /*
                  <label for="inputEmail3" class="col-sm-2 control-label">Sampai tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal2" name="spk_akhir" class="form-control" placeholder="Periode Akhir" autocomplete="off">

                  </div>        
                  */?>          

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

      <?php if($tanggal1 != ''){ ?>

        

        

        <table class='table table-bordered' style='font-size: 12pt' width='200%'>

          <tr>                          

            <th bgcolor='yellow' class='bold text-center'>No</th>

            <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>

            <th bgcolor='yellow' class='bold text-center'>Tgl Mohon Samsat</th>

            <th bgcolor='yellow' class='bold text-center'>No BASTD</th>

            <th bgcolor='yellow' class='bold text-center'>Nama Konsumen</th>

            <th bgcolor='yellow' class='bold text-center'>No Telp</th>

            <th bgcolor='yellow' class='bold text-center'>Alamat</th>

            <th bgcolor='yellow' class='bold text-center'>No Mesin</th>

            <th bgcolor='yellow' class='bold text-center'>No Rangka</th>

            <th bgcolor='yellow' class='bold text-center'>Biaya BBN</th>

            <th bgcolor='yellow' class='bold text-center'>Biaya BBN MD-BJ</th>

            <th bgcolor='yellow' class='bold text-center'>No. STNK</th>

            <th bgcolor='yellow' class='bold text-center'>No. Polisi</th>

            <th bgcolor='yellow' class='bold text-center'>No. Plat</th>
            <th bgcolor='yellow' class='bold text-center'>No. BPKB</th>

            <th bgcolor='yellow' class='bold text-center'>Tgl STNK</th>

            <th bgcolor='yellow' class='bold text-center'>Tgl Plat</th>

            <th bgcolor='yellow' class='bold text-center'>Tgl BPKB</th>   

            <th bgcolor='yellow' class='bold text-center'>No SP BPKB</th>              
            <th bgcolor='yellow' class='bold text-center'>No SP STNK</th>              
            <th bgcolor='yellow' class='bold text-center'>No SP Plat</th>              

          </tr>

          

          <tr>

          

          <?php

            $start=1;

            foreach ($query->result() as $dw)

            {

                ?>

                <tr>

		      <td><?php echo $start ?></td>

		      <td width="300px;"><?php echo $dw->nama_dealer ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->tgl_mohon_samsat ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->no_bastd ?></td>

		      <td width="250px;"><?php echo $dw->nama_konsumen ?></td>

		      <td style=" text-align:'center';"><?php echo sprintf('%s',$dw->no_hp) ?></td>

		      <td width="300px;"><?php echo $dw->alamat ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->no_mesin ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->no_rangka ?></td>

		      <td style=" text-align:'center';"><?php echo number_format($dw->biaya_bbn,0,',','.')  ?></td>

		      <td style=" text-align:'center';"><?php echo number_format($dw->biaya_bbn_md_bj,0,',','.') ?></td>	

		      <td style=" text-align:'center';" ><?php echo $dw->no_stnk ?></td>	

		      <td style=" text-align:'center';" width="110px;"><?php echo $dw->no_pol ?></td>	

		      <td style=" text-align:'center';" width="110px;"><?php echo $dw->no_plat ?></td>	

		      <td style=" text-align:'center';" width="100px;"><?php echo $dw->no_bpkb ?></td>
          
		      <td style=" text-align:'center';"><?php echo $dw->tgl_kirim_stnk ?></td>	
          <td style=" text-align:'center';" width="90px;"><?php echo $dw->tgl_kirim_plat  ?></td>	
          <td style=" text-align:'center';"><?php echo $dw->tgl_kirim_bpkb ?></td>  
          <td style=" text-align:'center';"><?php echo $dw->no_serah_bpkb ?></td>  
          <td style=" text-align:'center';"><?php echo $dw->no_serah_stnk ?></td>  
          <td style=" text-align:'center';"><?php echo $dw->no_serah_plat ?></td>  
          
          <?php /*
          <?php $tgl_stnk=$this->db->query("select a.tgl_kirim_stnk,b.no_mesin from tr_kirim_stnk_detail b join tr_kirim_stnk a on b.no_kirim_stnk=a.no_kirim_stnk where b.no_mesin='$dw->no_mesin'")->row_array();?>
          <?php $tgl_plat=$this->db->query("select a.tgl_kirim_plat,b.no_mesin from tr_kirim_plat_detail b join tr_kirim_plat a on b.no_kirim_plat=a.no_kirim_plat where b.no_mesin='$dw->no_mesin'")->row_array();?>
          <?php $tgl_bpkb=$this->db->query("select a.tgl_kirim_bpkb,b.no_mesin from tr_kirim_bpkb_detail b join tr_kirim_bpkb a on b.no_kirim_bpkb=a.no_kirim_bpkb where b.no_mesin='$dw->no_mesin'")->row_array();?>
		      <td style=" text-align:'center';"><?php echo $tgl_stnk['tgl_kirim_stnk'] ?></td>	
		      <td style=" text-align:'center';" width="90px;"><?php echo $tgl_plat['tgl_kirim_plat'] ?></td>	
          <td style=" text-align:'center';"><?php echo $tgl_bpkb['tgl_kirim_bpkb'] ?></td>  
          <td style=" text-align:'center';"><?php echo get_data('tr_penyerahan_bpkb_detail','no_mesin',$dw->no_mesin,'no_serah_bpkb') ?></td> 
          <td style=" text-align:'center';"><?php echo get_data('tr_penyerahan_stnk_detail','no_mesin',$dw->no_mesin,'no_serah_stnk') ?></td> 
		      <td style=" text-align:'center';"><?php echo get_data('tr_penyerahan_plat_detail','no_mesin',$dw->no_mesin,'no_serah_plat') ?></td>	
          */?>

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

    header("Content-Disposition: attachment; filename=rekap_bbn_biro-$date_buat.xls");

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

      <?php if($tanggal1 != ''){ ?>

        

        

        <table class='table table-bordered' border="1" style='font-size: 10pt' width='100%'>

          <tr>  



          <th bgcolor='yellow' class='bold text-center'>No</th>

          <th bgcolor='yellow' class='bold text-center'>Nama Dealer</th>

          <th bgcolor='yellow' class='bold text-center'>Tgl Mohon Samsat</th>

          <th bgcolor='yellow' class='bold text-center'>No BASTD</th>

          <th bgcolor='yellow' class='bold text-center'>Nama Konsumen</th>

          <th bgcolor='yellow' class='bold text-center'>No Telp</th>

          <th bgcolor='yellow' class='bold text-center'>Alamat</th>

          <th bgcolor='yellow' class='bold text-center'>No Mesin</th>

          <th bgcolor='yellow' class='bold text-center'>No Rangka</th>

          <th bgcolor='yellow' class='bold text-center'>Biaya BBN</th>

          <th bgcolor='yellow' class='bold text-center'>Biaya BBN MD-BJ</th>

          <th bgcolor='yellow' class='bold text-center'>No. STNK</th>

          <th bgcolor='yellow' class='bold text-center'>No. Polisi</th>

          <th bgcolor='yellow' class='bold text-center'>No. Plat</th>

          <th bgcolor='yellow' class='bold text-center'>No. BPKB</th>

          <th bgcolor='yellow' class='bold text-center'>Tgl STNK</th>

          <th bgcolor='yellow' class='bold text-center'>Tgl Plat</th>

          <th bgcolor='yellow' class='bold text-center'>Tgl BPKB</th>

          <th bgcolor='yellow' class='bold text-center'>No SP BPKB</th>              
          <th bgcolor='yellow' class='bold text-center'>No SP STNK</th>              
          <th bgcolor='yellow' class='bold text-center'>No SP Plat</th>   



          </tr>

          

          <?php

            $start=1;

            foreach ($query->result() as $dw)

            {

                ?>

                <tr>

		      <td><?php echo $start ?></td>

		      <td width="400px;"><?php echo $dw->nama_dealer ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->tgl_mohon_samsat ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->no_bastd ?></td>

		      <td ><?php echo $dw->nama_konsumen ?></td>

		      <td style=" text-align:'center';">'<?php echo sprintf('%s',$dw->no_hp) ?></td>

		      <td width="400px;"><?php echo $dw->alamat ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->no_mesin ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->no_rangka ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->biaya_bbn  ?></td>

		      <td style=" text-align:'center';"><?php echo $dw->biaya_bbn_md_bj ?></td>	

		      <td style=" text-align:'center';"><?php echo $dw->no_stnk ?></td>	

		      <td style=" text-align:'center';"><?php echo $dw->no_pol ?></td>	

		      <td style=" text-align:'center';"><?php echo $dw->no_plat ?></td>	

		      <td style=" text-align:'center';"><?php echo $dw->no_bpkb ?></td>
          
		      <td style=" text-align:'center';"><?php echo $dw->tgl_kirim_stnk ?></td>	
          <td style=" text-align:'center';" width="90px;"><?php echo $dw->tgl_kirim_plat  ?></td>	
          <td style=" text-align:'center';"><?php echo $dw->tgl_kirim_bpkb ?></td>  
          <td style=" text-align:'center';"><?php echo $dw->no_serah_bpkb ?></td>  
          <td style=" text-align:'center';"><?php echo $dw->no_serah_stnk ?></td>  
          <td style=" text-align:'center';"><?php echo $dw->no_serah_plat ?></td>  

          <?php /*
              <?php $tgl_stnk=$this->db->query("select a.tgl_kirim_stnk,b.no_mesin from tr_kirim_stnk_detail b join tr_kirim_stnk a on b.no_kirim_stnk=a.no_kirim_stnk where b.no_mesin='$dw->no_mesin'")->row_array();?>
              <?php $tgl_plat=$this->db->query("select a.tgl_kirim_plat,b.no_mesin from tr_kirim_plat_detail b join tr_kirim_plat a on b.no_kirim_plat=a.no_kirim_plat where b.no_mesin='$dw->no_mesin'")->row_array();?>
              <?php $tgl_bpkb=$this->db->query("select a.tgl_kirim_bpkb,b.no_mesin from tr_kirim_bpkb_detail b join tr_kirim_bpkb a on b.no_kirim_bpkb=a.no_kirim_bpkb where b.no_mesin='$dw->no_mesin'")->row_array();?>
              <td style=" text-align:'center';"><?php echo $tgl_stnk['tgl_kirim_stnk'] ?></td>	
              <td style=" text-align:'center';"><?php echo $tgl_plat['tgl_kirim_plat'] ?></td>	
              <td style=" text-align:'center';"><?php echo $tgl_bpkb['tgl_kirim_bpkb'] ?></td>	
              <td style=" text-align:'center';"><?php echo get_data('tr_penyerahan_bpkb_detail','no_mesin',$dw->no_mesin,'no_serah_bpkb') ?></td> 
              <td style=" text-align:'center';"><?php echo get_data('tr_penyerahan_stnk_detail','no_mesin',$dw->no_mesin,'no_serah_stnk') ?></td> 
              <td style=" text-align:'center';"><?php echo get_data('tr_penyerahan_plat_detail','no_mesin',$dw->no_mesin,'no_serah_plat') ?></td> 
          */?>

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

            // tanggal2:document.getElementById("tanggal2").value,

            cetak:'cetak',

            }



  if (value.tanggal1 == '') {

    alert('Isi data dengan lengkap ..!');

    return false;

  }else{

    //alert(value.tipe);

    $('.loader').show();

    $('#btnShow').disabled;

    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_rekap_bbn_biro?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1);
    // $("#showReport").attr("src",'<?php echo site_url("h1/Lap_rekap_bbn_biro?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

    document.getElementById("showReport").onload = function(e){          

    $('.loader').hide();       

    };

  }

}



function getExcel(){

  var value={

            tanggal1:document.getElementById("tanggal1").value,

            // tanggal2:document.getElementById("tanggal2").value,

            

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

    $("#showReport").attr("src",'<?php echo site_url("h1/Lap_rekap_bbn_biro?") ?>cetak='+value.cetak+'&tanggal1='+value.tanggal1+'&tanggal2='+value.tanggal2);

    document.getElementById("showReport").onload = function(e){          

    $('.loader').hide();       

    };

  }

}

</script>