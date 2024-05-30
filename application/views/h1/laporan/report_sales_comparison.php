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
<body onload="cek()">
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
            <form class="form-horizontal" id="frm" action="h1/report_sales_comparison?cetak=cetak" method="GET" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Mulai Tanggal</label>                  
                  <div class="col-sm-1">
                    <input type="text" name="tgl1" placeholder="Mulai Tanggal" value="1" autocomplete="off" id="tgl1" class="form-control">                    
                  </div>                                                                        
                  <div class="col-sm-1">
                    <input type="text" name="tgl2" placeholder="Sampai Tanggal" value="31" autocomplete="off" id="tgl2" class="form-control">                    
                  </div>                                    
                  <div class="col-sm-2">
                    <button type="submit" name="cetak" value="cetak" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Save to Excel</button>                                                      
                  </div>                             
                </div>         
                <div class="box box-warning">
                  <div class="box-header with-border">        
                    <h3 class="box-title">
                      Data Perbandingan
                    </h3>
                    <div class="row">
                      <div class="col-md-12">
                          <div class="box-body">                                                              
                            <div class="form-group">                                                
                              <table border="0">
                                <tr>
                                  <td width="1%">1.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_1" id="check_1">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_1" id="bulan_1">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                         
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_1" name="tahun_1">
                                  </td>

                                  <td width='2%'></td>

                                  <td width="1%">7.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_7" id="check_7">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_7" id="bulan_7">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                       
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_7" name="tahun_7">
                                  </td>
                                </tr>


                                <tr>
                                  <td width="1%">2.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_2" id="check_2">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_2" id="bulan_2">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                         
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_2" name="tahun_2">
                                  </td>

                                  <td width='2%'></td>

                                  <td width="1%">8.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_8" id="check_8">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_8" id="bulan_8">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                       
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_8" name="tahun_8">
                                  </td>
                                </tr>

                                <tr>
                                  <td width="1%">3.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_3" id="check_3">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_3" id="bulan_3">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                         
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_3" name="tahun_3">
                                  </td>

                                  <td width='2%'></td>

                                  <td width="1%">9.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_9" id="check_9">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_9" id="bulan_9">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                       
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_9" name="tahun_9">
                                  </td>
                                </tr>

                                <tr>
                                  <td width="1%">4.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_4" id="check_4">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_4" id="bulan_4">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                         
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_4" name="tahun_4">
                                  </td>

                                  <td width='2%'></td>

                                  <td width="1%">10.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_10" id="check_10">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_10" id="bulan_10">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                       
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_10" name="tahun_10">
                                  </td>
                                </tr>

                                <tr>
                                  <td width="1%">5.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_5" id="check_5">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_5" id="bulan_5">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                         
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_5" name="tahun_5">
                                  </td>

                                  <td width='2%'></td>

                                  <td width="1%">11.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_11" id="check_11">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_11" id="bulan_11">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                       
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_11" name="tahun_11">
                                  </td>
                                </tr>

                                <tr>
                                  <td width="1%">6.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_6" id="check_6">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_6" id="bulan_6">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                         
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_6" name="tahun_6">
                                  </td>

                                  <td width='2%'></td>

                                  <td width="1%">12.</td>
                                  <td width="2%">
                                    <input type="checkbox" name="check_12" id="check_12">
                                  </td>
                                  <td width="20%">
                                    <select class="form-control" name="bulan_12" id="bulan_12">
                                      <option value="">- choose -</option>                      
                                      <?php 
                                      $m = date("m"); 
                                      for ($i=1; $i <= 12; $i++) {                                       
                                        $i_j = bln($i);    
                                        echo "<option $select value='$i'>$i_j</option>";                                                
                                      } ?>                        
                                    </select>
                                  </td>
                                  <td width='1%'></td>
                                  <td width="20%">
                                    <input placeholder="Tahun" type="text" class="form-control" id="tahun_12" name="tahun_12">
                                  </td>
                                </tr>

                              </table>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">                                                      
                </div>        
              </div><!-- /.box-body -->                            
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
      var value={tgl1:document.getElementById("tgl1").value,
                tgl2:document.getElementById("tgl2").value,                
                bulan_1:document.getElementById("bulan_1").value,tahun_1:document.getElementById("tahun_1").value,                
                bulan_2:document.getElementById("bulan_2").value,tahun_2:document.getElementById("tahun_2").value,                
                bulan_3:document.getElementById("bulan_3").value,tahun_3:document.getElementById("tahun_3").value,                
                bulan_4:document.getElementById("bulan_4").value,tahun_4:document.getElementById("tahun_4").value,                
                bulan_5:document.getElementById("bulan_5").value,tahun_5:document.getElementById("tahun_5").value,                
                bulan_6:document.getElementById("bulan_6").value,tahun_6:document.getElementById("tahun_6").value,                
                bulan_7:document.getElementById("bulan_7").value,tahun_7:document.getElementById("tahun_7").value,                
                bulan_8:document.getElementById("bulan_8").value,tahun_8:document.getElementById("tahun_8").value,                
                bulan_9:document.getElementById("bulan_9").value,tahun_9:document.getElementById("tahun_9").value,                
                bulan_10:document.getElementById("bulan_10").value,tahun_10:document.getElementById("tahun_10").value,                
                bulan_11:document.getElementById("bulan_11").value,tahun_11:document.getElementById("tahun_11").value,                
                bulan_12:document.getElementById("bulan_12").value,tahun_12:document.getElementById("tahun_12").value,                
                cetak:'cetak',
                }

      if (value.tgl1 == '' || value.tgl2 == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.bulan_1);        
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/report_sales_comparison?") ?>tgl1='+value.tgl+'&cetak='+value.cetak+'&tgl2='+value.tgl2+'&bulan_1='+value.bulan_1+'&tahun_1='+value.tahun_1+'&bulan_2='+value.bulan_2+'&tahun_2='+value.tahun_2+'&bulan_3='+value.bulan_3+'&tahun_3='+value.tahun_3+'&bulan_4='+value.bulan_4+'&tahun_4='+value.tahun_4+'&bulan_5='+value.bulan_5+'&tahun_5='+value.tahun_5+'&bulan_6='+value.bulan_6+'&tahun_6='+value.tahun_6+'&bulan_7='+value.bulan_7+'&tahun_7='+value.tahun_7+'&bulan_8='+value.bulan_8+'&tahun_8='+value.tahun_8+'&bulan_9='+value.bulan_9+'&tahun_9='+value.tahun_9+'&bulan_10='+value.bulan_10+'&tahun_10='+value.tahun_10+'&bulan_11='+value.bulan_11+'&tahun_11='+value.tahun_11+'&bulan_12='+value.bulan_12+'&tahun_12='+value.tahun_12);
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
function cek(){
  for (var i = 1; i <= 12; i++) {    
    $("#tahun_"+i).prop("disabled", true);      
    $("#bulan_"+i).prop("disabled", true);  
  }
}
</script>
<script type="text/javascript">
$(document).ready(function() {    
  $("#check_1").click(function () {
    if($(this).prop('checked')){
      $("#tahun_1").prop('disabled', false);
      $("#bulan_1").prop('disabled', false);
    }else{
      $("#tahun_1").prop('disabled', true);
      $("#bulan_1").prop('disabled', true);
      $("#bulan_1").val('');
      $("#tahun_1").val('');
    }      
  });

  $("#check_2").click(function () {
    if($(this).prop('checked')){
      $("#tahun_2").prop('disabled', false);
      $("#bulan_2").prop('disabled', false);
    }else{
      $("#tahun_2").prop('disabled', true);
      $("#bulan_2").prop('disabled', true);
      $("#bulan_2").val('');
      $("#tahun_2").val('');
    }      
  });

  $("#check_3").click(function () {
    if($(this).prop('checked')){
      $("#tahun_3").prop('disabled', false);
      $("#bulan_3").prop('disabled', false);
    }else{
      $("#tahun_3").prop('disabled', true);
      $("#bulan_3").prop('disabled', true);
      $("#bulan_3").val('');
      $("#tahun_3").val('');
    }      
  });

  $("#check_4").click(function () {
    if($(this).prop('checked')){
      $("#tahun_4").prop('disabled', false);
      $("#bulan_4").prop('disabled', false);
    }else{
      $("#tahun_4").prop('disabled', true);
      $("#bulan_4").prop('disabled', true);
      $("#bulan_4").val('');
      $("#tahun_4").val('');
    }      
  });

  $("#check_5").click(function () {
    if($(this).prop('checked')){
      $("#tahun_5").prop('disabled', false);
      $("#bulan_5").prop('disabled', false);
    }else{
      $("#tahun_5").prop('disabled', true);
      $("#bulan_5").prop('disabled', true);
      $("#bulan_5").val('');
      $("#tahun_5").val('');
    }      
  });

  $("#check_6").click(function () {
    if($(this).prop('checked')){
      $("#tahun_6").prop('disabled', false);
      $("#bulan_6").prop('disabled', false);
    }else{
      $("#tahun_6").prop('disabled', true);
      $("#bulan_6").prop('disabled', true);
      $("#bulan_6").val('');
      $("#tahun_6").val('');
    }      
  });

  $("#check_7").click(function () {
    if($(this).prop('checked')){
      $("#tahun_7").prop('disabled', false);
      $("#bulan_7").prop('disabled', false);
    }else{
      $("#tahun_7").prop('disabled', true);
      $("#bulan_7").prop('disabled', true);
      $("#bulan_7").val('');
      $("#tahun_7").val('');
    }      
  });

  $("#check_8").click(function () {
    if($(this).prop('checked')){
      $("#tahun_8").prop('disabled', false);
      $("#bulan_8").prop('disabled', false);
    }else{
      $("#tahun_8").prop('disabled', true);
      $("#bulan_8").prop('disabled', true);
      $("#bulan_8").val('');
      $("#tahun_8").val('');
    }      
  });

  $("#check_9").click(function () {
    if($(this).prop('checked')){
      $("#tahun_9").prop('disabled', false);
      $("#bulan_9").prop('disabled', false);
    }else{
      $("#tahun_9").prop('disabled', true);
      $("#bulan_9").prop('disabled', true);
      $("#bulan_9").val('');
      $("#tahun_9").val('');
    }      
  });

  $("#check_10").click(function () {
    if($(this).prop('checked')){
      $("#tahun_10").prop('disabled', false);
      $("#bulan_10").prop('disabled', false);
    }else{
      $("#tahun_10").prop('disabled', true);
      $("#bulan_10").prop('disabled', true);
      $("#bulan_10").val('');
      $("#tahun_10").val('');
    }      
  });
  $("#check_11").click(function () {
    if($(this).prop('checked')){
      $("#tahun_11").prop('disabled', false);
      $("#bulan_11").prop('disabled', false);
    }else{
      $("#tahun_11").prop('disabled', true);
      $("#bulan_11").prop('disabled', true);
      $("#bulan_11").val('');
      $("#tahun_11").val('');
    }      
  });
  $("#check_12").click(function () {
    if($(this).prop('checked')){
      $("#tahun_12").prop('disabled', false);
      $("#bulan_12").prop('disabled', false);
    }else{
      $("#tahun_12").prop('disabled', true);
      $("#bulan_12").prop('disabled', true);
      $("#bulan_12").val('');
      $("#tahun_12").val('');
    }      
  });
});
</script>
