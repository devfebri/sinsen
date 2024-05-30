<?php 

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

            <form class="form-horizontal" action="h1/ssu/create" id="frm" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                              

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-1 control-label">Bulan</label>

                  <div class="col-sm-2">

                    <select class="form-control" name="bulan" id="bulan">

                      <option value="">- choose -</option>                      

                      <?php 

                      $m = date("m"); 

                      for ($i=1; $i <= 12; $i++) { 

                        if($i==$m) $select="selected";

                          else $select = ''; 

                        $i_j = bln($i);    

                        echo "<option $select value='$i'>$i_j</option>";                                                

                      } ?>  

                    </select>

                  </div>

                  <label for="inputEmail3" class="col-sm-1 control-label">Tahun</label>

                  <div class="col-sm-2">

                    <select class="form-control" name="tahun" id="tahun">                      

                      <?php 

                      $y = date("Y");

                      for ($i=$y - 5; $i <= $y + 10; $i++) { 

                        if($i==$y) $select="selected";

                          else $select = '';

                        echo "<option $select>$i</option>";

                      }

                      ?>                          

                    </select>

                  </div>                                                                           

                  <label for="inputEmail3" class="col-sm-1 control-label"></label>                  

                  <div class="col-sm-4">

                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  

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


            // function days_in_month($month, $year) { 
            //  return date('t', mktime(0, 0, 0, $month+1, 0, $year)); 
            // }
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

            sheet-size: 210mm 297mm;

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

<table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <?php $tgl = bln($bulan)." ".$tahun; ?>

        <div style="text-align: center;font-size: 13pt"><b>Stock Per Tipe dan Stock Days</b></div>        

        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->

        <hr>      

        

        <table class='table table-bordered' style='font-size: 9pt' width='100%'>

          <tr>                

            <td bgcolor='yellow' class='bold text-center' width='25%' colspan="2">Tipe Motor</td>            

            <td bgcolor='yellow' class='bold text-center'>Distribusi</td>                

            <td bgcolor='yellow' class='bold text-center'>Stock</td>                

            <td bgcolor='yellow' class='bold text-center'>Sales</td>                

            <td bgcolor='yellow' class='bold text-center'>Unfilled</td>                

            <td bgcolor='yellow' class='bold text-center'>Intransit</td>                

            <td bgcolor='yellow' class='bold text-center'>Avg. Daily Sales</td>                

            <td bgcolor='yellow' class='bold text-center'>Stock Days</td>                

          </tr>

          

          <?php   

          $t_sj=0;$t_so=0;$t_stock=0;$t_unfill=0;$t_in=0;$t_avg=0;$t_days=0;

          $dt_list = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE ms_tipe_kendaraan.active = 1 ORDER BY ms_tipe_kendaraan.id_tipe_kendaraan ASC");     

          foreach($dt_list->result() as $row) {  

            $bln = sprintf("%'.02d",$bulan);                                                                     

            $tgl = sprintf("%'.02d",$i);                                                                     

            $tgl_surat = $tahun."-".$bln;    

            $bulan_1 = $bulan-1;

            if($bulan_1 == "-1"){

              $bln_1 = "11";

              $th = $tahun-1;

            }elseif($bulan_1 == "0"){

              $bln_1 = "12";

              $th = $tahun-1;

            }else{

              $bln_1 = $bulan;

              $th = $tahun;

            }

            $bulan_fix_1 = sprintf("%'.02d",$bln_1);

            $tgl_surat_1 = $th."-".$bulan_fix_1;    
            // $jum_tgl = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $jum_tgl = days_in_month($bulan, $tahun);



            $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail 

                  INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                  

                  INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item                  

                  WHERE tr_surat_jalan.id_dealer = '$id_dealer' AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();             

            $cek_stock = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail

                  LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               

                  LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin

                  LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan

                  LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna

                  LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                

                  WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 

                  AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'")->row();    

            $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 

                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   

                  WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();

            $cek_unfill = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 

                  INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do

                  INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item

                  WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) AND

                  ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND tr_do_po.id_dealer = '$id_dealer' AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'

                  AND LEFT(tr_do_po.tgl_do,7) = '$tgl_surat'")->row();                        

            $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       

                  WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)

                  AND tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();



            $days1 = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail

                  LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               

                  LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin

                  LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan

                  LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna

                  LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                

                  WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 

                  AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'")->row();    

            $days2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 

                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   

                  WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'

                  AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat'")->row();

            $days3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 

                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   

                  WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'

                  AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'")->row();

            //$stock_days = $days1->jum / (($days2->jum * $days3->jum) / 30);

            $stock_days = 0;



            if($cek_stock->jum > 0 or $cek_unfill->jum > 0 OR $cek_so->jum > 0 OR $cek_sj->jum > 0 OR $cek_in->jum > 0){

              $avg = number_format($cek_so->jum / $jum_tgl,2);

              echo "

              <tr>

                <td align='center'>$row->id_tipe_kendaraan</td>

                <td align='center'>$row->tipe_ahm</td>                                            

                <td align='center'>$cek_sj->jum</td>

                <td align='center'>$cek_stock->jum</td>

                <td align='center'>$cek_so->jum</td>

                <td align='center'>$cek_unfill->jum</td>              

                <td align='center'>$cek_in->jum</td>              

                <td align='center'>$avg</td>              

                <td align='center'>$stock_days</td>              

              </tr>

              ";

              $t_sj += $cek_sj->jum;                          

              $t_stock += $cek_stock->jum;                          

              $t_so += $cek_so->jum;                          

              $t_unfill += $cek_unfill->jum;                          

              $t_in += $cek_in->jum;                          

              $t_avg += $avg;                          

              $t_days += $stock_days;                          

            }

          }

          ?>

          <tr>

            <td bgcolor='yellow' class='bold text-center' colspan="2">Total</td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_sj ?></td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_stock ?></td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_so ?></td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_unfill ?></td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_in ?></td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_avg ?></td>                                  

            <td bgcolor='yellow' class='bold text-center'><?php echo $t_days ?></td>                                  

          </tr>

        </table> <br>



        <br>                

    </body>

  </html>

  <?php } ?>

  </section>

</div>





<script>

    function getReport()

    {

      var value={bulan:document.getElementById("bulan").value,

                tahun:document.getElementById("tahun").value,

                cetak:'cetak',

                //tipe:getRadioVal(document.getElementById("frm"),"tipe"),

                }



      if (value.tipe == '') {

        alert('Isi data dengan lengkap ..!');

        return false;

      }else{

        //alert(value.tipe);

        $('.loader').show();

        $('#btnShow').disabled;

        $("#showReport").attr("src",'<?php echo site_url("dealer/laporan_stock_days?") ?>cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan);

        document.getElementById("showReport").onload = function(e){          

        $('.loader').hide();       

        };

      }

    }

function getRadioVal(form, name) {

  var val;  

  var radios = form.elements[name];

  for (var i=0, len=radios.length; i<len; i++) {

      if ( radios[i].checked ) { // radio checked?

          val = radios[i].value; // if so, hold its value in val

          break; // and break out of for loop

      }

  }

  return val; // return value of checked radio or undefined if none checked

}

</script>