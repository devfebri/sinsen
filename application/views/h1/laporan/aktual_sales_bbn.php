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
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
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
    <li class="">H1</li>
    <li class="">Laporan</li>
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
            <form class="form-horizontal" id="frm" method="post" action="h1/aktual_sales_bbn/download" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-1 control-label">Tahun</label>                  
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
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_tanggal" class="minimal" checked> Detail Per Tanggal
                  </div>
                  <div class="col-sm-3">                    
                    <input type="radio" name="tipe" id="tipe" value="per_dealer" class="minimal"> Rekap Per Dealer
                  </div>                    
                </div>         
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_kab = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_kab->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
                  </div>                                  
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Preview</button>                                                      
                  </div>                             
                  <div class="col-sm-2">
                    <button type="submit" name="download" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download (.xls)</button>                                                      
                  </div>                             
                </div>        
              </div><!-- /.box-body -->              
              <div class="box-footer">                                                              
                <div style="min-height: 600px">                 
                  <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                </div>
              </div>
            </form>
            <!-- <div id="imgContainer"></div> -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    
    <?php }elseif ($set=='cetak') { ?>
    <!DOCTYPE html>
    <html>
    <!-- <html lang="ar"> for arabic only -->
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Cetak</title>
      <style>
        @media print {
          @page {
            sheet-size: 297mm 210mm;
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
      <?php if($tipe == 'per_tanggal'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Aktual Sales VS BBN</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Bulan : <?php echo $tgl ?></b></div>                
        <br>
        <?php  
        $bln = sprintf("%'.02d",$bulan);                            
        $tgl_surat_1 = $tahun."-".$bln;
        $sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN tr_scan_barcode ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                    INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
                    INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'
                    GROUP BY tr_sales_order.id_dealer");
        foreach ($sql->result() as $amb) {          
          echo "<b>Dealer : $amb->kode_dealer_md - $amb->nama_dealer</b>";
        ?>
          <table class='table table-bordered' style='font-size: 9pt' width='100%'>
            <thead>
              <tr>
                <td bgcolor='yellow' class='bold text-center'>Tanggal</td>              
                <td bgcolor='yellow' class='bold text-center'>Penjualan</td>                                        
                <td bgcolor='yellow' class='bold text-center'>Data BBN</td>                                        
                <td bgcolor='yellow' class='bold text-center'>Selisih</td>                                                      
              </tr>
            </thead>
            <tbody>
              <?php             
              $tot=0;$tot1=0;$tot2=0;
              for ($i=1; $i <= 31; $i++) {               
                $bln = sprintf("%'.02d",$bulan);                            
                $tgl = sprintf("%'.02d",$i);                            
                $tgl_surat_1 = $tgl."-".$bln."-".$tahun;              
                $tgl_surat_2 = $tahun."-".$bln."-".$tgl;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl_surat_2' AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;

                $cek_bbn = $this->db->query("SELECT COUNT(tr_faktur_stnk_detail.no_mesin) AS jum FROM tr_faktur_stnk_detail
                  INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd                  
                  WHERE tr_faktur_stnk.tgl_bastd = '$tgl_surat_2' AND tr_faktur_stnk.id_dealer = '$amb->id_dealer'")->row()->jum;
                if(isset($cek_bbn) AND $cek_bbn != 0) $jumlah_jual2 = $cek_bbn;
                  else $jumlah_jual2 = 0;
                $hasil = $jumlah_jual - $jumlah_jual2;

                $tot += $jumlah_jual;
                $tot1 += $jumlah_jual2;
                $tot2 += $hasil;
                echo "<tr>
                        <td>$tgl_surat_1</td>
                        <td align='center'>$jumlah_jual</td>
                        <td align='center'>$jumlah_jual2</td>
                        <td align='center'>$hasil</td>
                      </tr>";
                }                             
              ?>                  
            </tbody>
            <tfoot>
              <tr>
                <td bgcolor='yellow' class='bold text-center'>TOTAL</td>              
                <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>
                <td bgcolor='yellow' class='bold text-center'><?php echo $tot1 ?></td>
                <td bgcolor='yellow' class='bold text-center'><?php echo $tot2 ?></td>
              </tr>
            </tfoot>
          </table> <br>
        <?php
        } 
      }elseif($tipe == 'per_dealer' AND $id_dealer == 'all'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Rekap Penjualan VS BBN</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Bulan : <?php echo $tgl ?></b></div>                
        <br>                
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Nama Dealer</td>              
              <td bgcolor='yellow' class='bold text-center'>Penjualan</td>                                        
              <td bgcolor='yellow' class='bold text-center'>Data BBN</td>                                        
              <td bgcolor='yellow' class='bold text-center'>Selisih</td>                                                      
            </tr>
          </thead>
          <tbody>
            <?php             
            $tot=0;$tot1=0;$tot2=0;
            $sql = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
            foreach ($sql->result() as $row) {              
              $bln = sprintf("%'.02d",$bulan);                                          
              $tgl_surat_2 = $tahun."-".$bln;
              $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
              if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                else $jumlah_jual = 0;

              $cek_bbn = $this->db->query("SELECT COUNT(tr_faktur_stnk_detail.no_mesin) AS jum FROM tr_faktur_stnk_detail
                INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                                                   
                INNER JOIN tr_faktur_stnk ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd                  
                WHERE LEFT(tr_faktur_stnk.tgl_bastd,7) = '$tgl_surat_2' AND tr_faktur_stnk.id_dealer = '$row->id_dealer'")->row()->jum;
              if(isset($cek_bbn) AND $cek_bbn != 0) $jumlah_jual2 = $cek_bbn;
                else $jumlah_jual2 = 0;
              $hasil = $jumlah_jual - $jumlah_jual2;

              $tot += $jumlah_jual;
              $tot1 += $jumlah_jual2;
              $tot2 += $hasil;
              echo "<tr>
                      <td>$row->nama_dealer</td>
                      <td align='center'>$jumlah_jual</td>
                      <td align='center'>$jumlah_jual2</td>
                      <td align='center'>$hasil</td>
                    </tr>";
              }                             
            ?>                  
          </tbody>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>TOTAL</td>              
              <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>
              <td bgcolor='yellow' class='bold text-center'><?php echo $tot1 ?></td>
              <td bgcolor='yellow' class='bold text-center'><?php echo $tot2 ?></td>
            </tr>
          </tfoot>
        </table> <br>
      <?php      
      }
    ?>
    </body>
  </html>
  <?php } ?>

  </section>
</div>


<script>
    function getReport()
    {
      var value={tipe:getRadioVal(document.getElementById("frm"),"tipe"),
                bulan:document.getElementById("bulan").value,
                tahun:document.getElementById("tahun").value,                
                id_dealer:document.getElementById("id_dealer").value,                
                cetak:'cetak',
                }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);        
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/aktual_sales_bbn?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan+'&id_dealer='+value.id_dealer);
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
