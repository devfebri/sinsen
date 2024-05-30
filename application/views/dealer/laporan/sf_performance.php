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
function weekNumberOfMonth($date) {
  $tgl=date_parse($date);
  $tanggal =  $tgl['day'];
  $bulan   =  $tgl['month'];
  $tahun   =  $tgl['year'];
  //tanggal 1 tiap bulan
  $tanggalAwalBulan = mktime(0, 0, 0, $bulan, 1, $tahun);
  $mingguAwalBulan = (int) date('W', $tanggalAwalBulan);
  //tanggal sekarang
  $tanggalYangDicari = mktime(0, 0, 0, $bulan, $tanggal, $tahun);
  $mingguTanggalYangDicari = (int) date('W', $tanggalYangDicari);
  $mingguKe = $mingguTanggalYangDicari - $mingguAwalBulan + 1;
  return $mingguKe;
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
      <table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <?php $tgl = bln($bulan)." ".$tahun; ?>
        <div style="text-align: center;font-size: 13pt"><b>Sales Frontlier Performance</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center'>Nama SF</td>
            <td bgcolor='yellow' class='bold text-center'>Jabatan</td>
            <td bgcolor='yellow' class='bold text-center'>W1</td>                
            <td bgcolor='yellow' class='bold text-center'>W2</td>                
            <td bgcolor='yellow' class='bold text-center'>W3</td>                
            <td bgcolor='yellow' class='bold text-center'>W4</td>                
            <td bgcolor='yellow' class='bold text-center'>W5</td>                
            <td bgcolor='yellow' class='bold text-center'>Total</td>                
          </tr>          
          <?php           
          $sql_1 = $this->db->query("SELECT * FROM ms_karyawan_dealer LEFT JOIN ms_jabatan ON ms_karyawan_dealer.id_jabatan = ms_jabatan.id_jabatan 
            WHERE ms_karyawan_dealer.id_dealer = '$id_dealer' AND ms_karyawan_dealer.id_flp_md <> '' ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
          $g_tot=0;$gt_1=0;$gt_2=0;$gt_3=0;$gt_4=0;$gt_5=0;
          $bln = sprintf("%'.02d",$bulan);                                                                                         
          $tgl_surat_1 = $tahun."-".$bln;
          $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'")->row()->jum;
          if(isset($cek_so) AND $cek_so != 0) $jumlah_total = $cek_so;
            else $jumlah_total = 0;
          $g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;
          foreach ($sql_1->result() as $isi) {           
            echo "<tr>
                    <td>$isi->nama_lengkap $isi->id_karyawan_dealer</td>
                    <td>$isi->jabatan</td>";
                    $total=0;                    
                    $cek_so2 = $this->db->query("SELECT DISTINCT(tr_spk.no_spk),LEFT(tr_sales_order.tgl_create_ssu,10) AS tgl FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        INNER JOIN tr_prospek ON tr_prospek.id_customer = tr_spk.id_customer
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_prospek.id_karyawan_dealer = '$isi->id_karyawan_dealer'");
                    $g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;
                    foreach ($cek_so2->result() as $amb) {
                      $tgl_ssu = $amb->tgl;
                      $mgu_ssu = weekNumberOfMonth($tgl_ssu);
                      if($mgu_ssu == 1){
                        $g_1++;
                      }elseif($mgu_ssu == 2){
                        $g_2++;                      
                      }elseif($mgu_ssu == 3){
                        $g_3++;
                      }elseif($mgu_ssu == 4){
                        $g_4++;
                      }elseif($mgu_ssu == 5){
                        $g_5++;
                      }
                    }
                                        

                    echo "<td align='center'>$g_1</td>
                          <td align='center'>$g_2</td>
                          <td align='center'>$g_3</td>
                          <td align='center'>$g_4</td>
                          <td align='center'>$g_5</td>";


                                                                                  
                    echo "<td align='center'>".$total = $g_1 + $g_2 + $g_3 + $g_4 + $g_5."</td>
                  </tr>";
                  $gt_1+=$g_1;$gt_2+=$g_2;$gt_3+=$g_3;$gt_4+=$g_4;$gt_5+=$g_5;
                  $g_tot += $total;
          }
          ?>
          <tr>
            <td bgcolor='yellow' class='bold text-center' colspan="2">TOTAL</td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $gt_1 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $gt_2 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $gt_3 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $gt_4 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $gt_5 ?></td>                        
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_tot ?></td>            
          </tr>
        </table> <br>
                    
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
        $("#showReport").attr("src",'<?php echo site_url("dealer/sf_performance?") ?>cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan);
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