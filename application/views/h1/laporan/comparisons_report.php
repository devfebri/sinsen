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
            <form class="form-horizontal" id="frm" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-1 control-label">Tanggal</label>                  
                  <div class="col-sm-1">
                    <input type="text" name="tgl1" placeholder="Mulai Tanggal" value="1" autocomplete="off" id="tgl1" class="form-control">                    
                  </div>                                                                        
                  <div class="col-sm-1">
                    <input type="text" name="tgl2" placeholder="Sampai Tanggal" value="31" autocomplete="off" id="tgl2" class="form-control">                    
                  </div>                                    
                  <div class="col-sm-1"></div>                                    
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_tipe" class="minimal" checked> Per Tipe Motor
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_dealer_tipe" class="minimal"> Per Dealer & Tipe
                  </div>  
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_dealer_fin" class="minimal"> Per Dealer (Fincoy)
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_kab_tipe" class="minimal"> Per Kab & Tipe
                  </div>
                </div>
                <div class="form-group">                  
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
                    <input type="radio" name="tipe" id="tipe" value="per_dealer_kab" class="minimal"> Per Dealer & Kab
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_fincoy" class="minimal"> Per Fincoy
                  </div>  
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_kab" class="minimal"> Per Kab (Fincoy)
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_tipe_fin" class="minimal"> Per Tipe & Fincoy
                  </div>
                </div>         
                
                <div class="form-group">                  
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Preview</button>                                                      
                  </div>                             
                </div>              
                <div class="box box-warning">
                  <div class="box-header with-border">        
                    <h3 class="box-title">
                      Data Perbandingan
                    </h3>
                    <input type="hidden" id="tahun_asal" value="<?php echo date('Y') ?>">
                    <div class="row">
                      <div class="col-md-12">
                          <div class="box-body">                                                              
                            <div class="form-group">                                                
                              <table border="0">
                                <tr>                                  
                                  <td width="1%">
                                    <input type="checkbox" name="check_1" id="check_1">
                                  </td>
                                  <td width="3%">
                                    Januari
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_1" name="tahun_1">
                                  </td>
                                  <td width="1%"></td>

                                  <td width="1%">
                                    <input type="checkbox" name="check_2" id="check_2">
                                  </td>
                                  <td width="3%">
                                    Februari
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_2" name="tahun_2">
                                  </td>
                                  <td width="1%"></td>


                                  <td width="1%">
                                    <input type="checkbox" name="check_3" id="check_3">
                                  </td>
                                  <td width="3%">
                                    Maret
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_3" name="tahun_3">
                                  </td>                              
                                  <td width="1%"></td>


                                  <td width="1%">
                                    <input type="checkbox" name="check_4" id="check_4">
                                  </td>
                                  <td width="3%">
                                    April
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_4" name="tahun_4">
                                  </td>                                   
                                </tr>

                                <tr>                                  
                                  <td width="1%">
                                    <input type="checkbox" name="check_5" id="check_5">
                                  </td>
                                  <td width="3%">
                                    Mei
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_5" name="tahun_5">
                                  </td>
                                  <td width="1%"></td>

                                  <td width="1%">
                                    <input type="checkbox" name="check_6" id="check_6">
                                  </td>
                                  <td width="3%">
                                    Juni
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_6" name="tahun_6">
                                  </td>
                                  <td width="1%"></td>


                                  <td width="1%">
                                    <input type="checkbox" name="check_7" id="check_7">
                                  </td>
                                  <td width="3%">
                                    Juli
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control"  disabled id="tahun_7" name="tahun_7">
                                  </td>                              
                                  <td width="1%"></td>


                                  <td width="1%">
                                    <input type="checkbox" name="check_8" id="check_8">
                                  </td>
                                  <td width="3%">
                                    Agustus
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_8" name="tahun_8">
                                  </td>                                   
                                </tr>

                                <tr>                                  
                                  <td width="1%">
                                    <input type="checkbox" name="check_9" id="check_9">
                                  </td>
                                  <td width="3%">
                                    September
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_9" name="tahun_9">
                                  </td>
                                  <td width="1%"></td>

                                  <td width="1%">
                                    <input type="checkbox" name="check_10" id="check_10">
                                  </td>
                                  <td width="3%">
                                    Oktober
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_10" name="tahun_10">
                                  </td>
                                  <td width="1%"></td>


                                  <td width="1%">
                                    <input type="checkbox" name="check_11" id="check_11">
                                  </td>
                                  <td width="3%">
                                    November
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_11" name="tahun_11">
                                  </td>                              
                                  <td width="1%"></td>


                                  <td width="1%">
                                    <input type="checkbox" name="check_12" id="check_12">
                                  </td>
                                  <td width="3%">
                                    Desember
                                  </td>                                  
                                  <td width="5%">
                                    <input placeholder="Tahun" type="text" class="form-control" disabled id="tahun_12" name="tahun_12">
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
      <?php if($tipe == 'per_tipe'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Sales</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
          </tr>
          </thead>
          <tbody>
          <?php  
          $bln = sprintf("%'.02d",$bulan);                            
          $tgl_surat_1 = $tahun."-".$bln;
          //$sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
          $sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
          foreach ($sql->result() as $amb) {          
            echo "<tr>
                    <td>$amb->tipe_ahm</td>";
                    $bln = sprintf("%'.02d",$bulan);                                                
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                    $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                      AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                      else $jumlah_jual = 0;
                      $tot += $jumlah_jual;
                    
                    echo "<td align='center'>$jumlah_jual</td>";
                      
                      if($tahun_1!=''){     
                        $bln = sprintf("%'.02d",1);                            
                        $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_2!=''){     
                        $bln = sprintf("%'.02d",2);                            
                        $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_3!=''){     
                        $bln = sprintf("%'.02d",3);                            
                        $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_4!=''){     
                        $bln = sprintf("%'.02d",4);                            
                        $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_5!=''){     
                        $bln = sprintf("%'.02d",5);                            
                        $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_6!=''){     
                        $bln = sprintf("%'.02d",6);                            
                        $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_7!=''){     
                        $bln = sprintf("%'.02d",7);                            
                        $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_8!=''){     
                        $bln = sprintf("%'.02d",8);                            
                        $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_9!=''){     
                        $bln = sprintf("%'.02d",9);                            
                        $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_10!=''){     
                        $bln = sprintf("%'.02d",10);                            
                        $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual10</td>";
                      }

                      if($tahun_11!=''){     
                        $bln = sprintf("%'.02d",11);                            
                        $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_12!=''){     
                        $bln = sprintf("%'.02d",12);                            
                        $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                     
                    
                  echo "</tr>";
          }
          ?>                        
            </tbody>
            <tfoot>
              <tr>
                <td bgcolor='yellow' class='bold text-center'>TOTAL</td>              
                <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                <?php 
                if($tahun_1!=''){     
                  $bln = sprintf("%'.02d",1);                            
                  $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                  $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_2!=''){     
                  $bln = sprintf("%'.02d",2);                            
                  $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_3!=''){     
                  $bln = sprintf("%'.02d",3);                            
                  $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_4!=''){     
                  $bln = sprintf("%'.02d",4);                            
                  $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_5!=''){     
                  $bln = sprintf("%'.02d",5);                            
                  $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_6!=''){     
                  $bln = sprintf("%'.02d",6);                            
                  $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_7!=''){     
                  $bln = sprintf("%'.02d",7);                            
                  $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_8!=''){     
                  $bln = sprintf("%'.02d",8);                            
                  $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_9!=''){     
                  $bln = sprintf("%'.02d",9);                            
                  $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_10!=''){     
                  $bln = sprintf("%'.02d",10);                            
                  $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_11!=''){     
                  $bln = sprintf("%'.02d",11);                            
                  $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }

                if($tahun_12!=''){     
                  $bln = sprintf("%'.02d",12);                            
                  $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                    else $jumlah_jual1 = 0;
                  
                  echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                }
                ?>
              </tr>
            </tfoot>
          </table> <br>
        <?php        
      }elseif($tipe == 'per_dealer_tipe'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Sales</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        
        <?php 
        $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
        foreach ($sql_dealer->result() as $row) {          
        ?>
          Nama Dealer : <?php echo "$row->kode_dealer_md - $row->nama_dealer"; ?>
          <table class='table table-bordered' style='font-size: 9pt' width='100%'>
            <thead>
              <tr>
                <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>              
                <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
                <?php 
                $isi=0;
                if($tahun_1!='') $isi++;    
                if($tahun_2!='') $isi++;    
                if($tahun_3!='') $isi++;    
                if($tahun_4!='') $isi++;    
                if($tahun_5!='') $isi++;    
                if($tahun_6!='') $isi++;    
                if($tahun_7!='') $isi++;    
                if($tahun_8!='') $isi++;    
                if($tahun_9!='') $isi++;    
                if($tahun_10!='') $isi++;   
                if($tahun_11!='') $isi++;   
                if($tahun_12!='') $isi++;
                ?>
                <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
              </tr>
              <tr>
              <?php                             
              if($tahun_1!=''){     
                $bln_1 = substr(bln(1),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
              }
              if($tahun_2!=''){     
                $bln_2 = substr(bln(2),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
              }             

              if($tahun_3!=''){     
                $bln_3 = substr(bln(3),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
              }
              if($tahun_4!=''){     
                $bln_4 = substr(bln(4),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
              }             

              if($tahun_5!=''){     
                $bln_5 = substr(bln(5),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
              }
              if($tahun_6!=''){     
                $bln_6 = substr(bln(6),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
              }             

              if($tahun_7!=''){     
                $bln_7 = substr(bln(7),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
              }
              if($tahun_8!=''){     
                $bln_8 = substr(bln(8),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
              }             

              if($tahun_9!=''){     
                $bln_9 = substr(bln(9),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
              }
              if($tahun_10!=''){      
                $bln_10 = substr(bln(10),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
              }             

              if($tahun_11!=''){      
                $bln_11 = substr(bln(11),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
              }
              if($tahun_12!=''){      
                $bln_12 = substr(bln(12),0,3);
                echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
              }                         
              ?>
            </tr>
            </thead>
            <tbody>
            <?php  
            $bln = sprintf("%'.02d",$bulan);                            
            $tgl_surat_1 = $tahun."-".$bln;
            //$sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1 LIMIT 230,10");
            $sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
            foreach ($sql->result() as $amb) {          
              echo "<tr>
                      <td>$amb->tipe_ahm</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'
                        AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                       
                      
                    echo "</tr>";
            }
            ?>                        
              </tbody>
              <tfoot>
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>TOTAL</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php 
                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }
                  ?>
                </tr>
              </tfoot>
            </table> <br>
        <?php        
        }
      }elseif($tipe == 'per_dealer_fin'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Penjualan via Leasing</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Kode Fincoy</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
            </tr>
          </thead>
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
          foreach ($sql_dealer->result() as $row) {          
          ?>                   
            <tbody>
              <tr>
                <td bgcolor='pink' colspan="<?php echo $isi+2 ?>"><?php echo $row->nama_dealer ?></td>
              </tr>
              <?php  
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $total=0;
              $sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
              //$sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = 1");
              foreach ($sql->result() as $amb) {          
                echo "<tr>
                      <td>$amb->finance_company</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }   
                        echo "</tr>";                                                                                          
                }
                      echo "                
                      <tr>
                        <td>Tunai</td>";
                        $bln = sprintf("%'.02d",$bulan);                                                
                        $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                        $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'
                          AND tr_spk.jenis_beli = 'Cash'")->row()->jum;

                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                          else $jumlah_jual = 0;
                          $tot += $jumlah_jual;
                        
                        echo "<td align='center'>$jumlah_jual</td>";
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }
                        echo "
                      </tr>"; ?>
                ?>   
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Sub Total</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php 
                  $bln = sprintf("%'.02d",$bulan);                                                
                  $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                  $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'
                    AND tr_spk.jenis_beli = 'Cash'")->row()->jum;

                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah = $cek_so2;
                    else $jumlah = 0;                    


                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";

                    $total += $tot;
                    $total_jum += $jumlah_jual1;
                  }
                  ?>
                </tr>                     
            </tbody>            
          <?php } ?>
            <tr>
              <td bgcolor='grey' class='bold text-center'>Grand Total</td>
              <td bgcolor='grey' class='bold text-center'><?php echo $total ?></td>
              <?php                                               


              if($tahun_1!=''){     
                $bln = sprintf("%'.02d",1);                            
                $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_2!=''){     
                $bln = sprintf("%'.02d",2);                            
                $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_3!=''){     
                $bln = sprintf("%'.02d",3);                            
                $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_4!=''){     
                $bln = sprintf("%'.02d",4);                            
                $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_5!=''){     
                $bln = sprintf("%'.02d",5);                            
                $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_6!=''){     
                $bln = sprintf("%'.02d",6);                            
                $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_7!=''){     
                $bln = sprintf("%'.02d",7);                            
                $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_8!=''){     
                $bln = sprintf("%'.02d",8);                            
                $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_9!=''){     
                $bln = sprintf("%'.02d",9);                            
                $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_10!=''){     
                $bln = sprintf("%'.02d",10);                            
                $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_11!=''){     
                $bln = sprintf("%'.02d",11);                            
                $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }                  

              if($tahun_12!=''){     
                $bln = sprintf("%'.02d",12);                            
                $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";

                $total += $tot;
                $total_jum += $jumlah_jual1;
              }
              ?>
              ?>
            </tr>            
        </table> <br>
        <?php
      }elseif($tipe == 'per_kab_tipe'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Sales</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
            </tr>
          </thead>
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500 ORDER BY kabupaten");
          $total=0;
          foreach ($sql_dealer->result() as $row) {          
          ?>                   
            <tbody>
              <tr>
                <td bgcolor='pink' colspan="<?php echo $isi+2 ?>"><?php echo $row->kabupaten ?></td>
              </tr>
              <?php  
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $tot=0;
              $sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan
                  ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE ms_kabupaten.id_kabupaten = '$row->id_kabupaten' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
              foreach ($sql->result() as $amb) {          
                echo "<tr>
                      <td>$amb->tipe_ahm</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                        LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                        LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                        AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }   
                        echo "</tr>";                                                                                          
                }                      
                ?>   
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Sub Total</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php                                     


                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";

                    $total += $tot;
                    $total_jum += $jumlah_jual1;
                  }
                  ?>
                </tr>                     
            </tbody>            
          <?php } ?>
            <tr>
              <td bgcolor='grey' class='bold text-center'>Grand Total</td>
              <td bgcolor='grey' class='bold text-center'><?php echo $total ?></td>
              <?php                                     


              if($tahun_1!=''){     
                $bln = sprintf("%'.02d",1);                            
                $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_2!=''){     
                $bln = sprintf("%'.02d",2);                            
                $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_3!=''){     
                $bln = sprintf("%'.02d",3);                            
                $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_4!=''){     
                $bln = sprintf("%'.02d",4);                            
                $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_5!=''){     
                $bln = sprintf("%'.02d",5);                            
                $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_6!=''){     
                $bln = sprintf("%'.02d",6);                            
                $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_7!=''){     
                $bln = sprintf("%'.02d",7);                            
                $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_8!=''){     
                $bln = sprintf("%'.02d",8);                            
                $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_9!=''){     
                $bln = sprintf("%'.02d",9);                            
                $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_10!=''){     
                $bln = sprintf("%'.02d",10);                            
                $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_11!=''){     
                $bln = sprintf("%'.02d",11);                            
                $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }                  

              if($tahun_12!=''){     
                $bln = sprintf("%'.02d",12);                            
                $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;

                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";

                $total += $tot;
                $total_jum += $jumlah_jual1;
              }
              ?>
            </tr>            
        </table> <br>
        <?php      
      }elseif($tipe == 'per_fincoy'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Penjualan via Leasing</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Kode Fincoy</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
            </tr>
          </thead>
          <?php
          $total=0;
          $sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");              
          foreach ($sql->result() as $amb) {          
            echo "<tr>
                    <td>$amb->finance_company</td>";
                    $bln = sprintf("%'.02d",$bulan);                                                
                    $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                    $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2' AND tr_sales_order.id_dealer = '$row->id_dealer'
                      AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                      else $jumlah_jual = 0;
                      $tot += $jumlah_jual;
                    
                    echo "<td align='center'>$jumlah_jual</td>";
                      
                      if($tahun_1!=''){     
                        $bln = sprintf("%'.02d",1);                            
                        $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_2!=''){     
                        $bln = sprintf("%'.02d",2);                            
                        $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_3!=''){     
                        $bln = sprintf("%'.02d",3);                            
                        $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_4!=''){     
                        $bln = sprintf("%'.02d",4);                            
                        $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_5!=''){     
                        $bln = sprintf("%'.02d",5);                            
                        $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_6!=''){     
                        $bln = sprintf("%'.02d",6);                            
                        $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_7!=''){     
                        $bln = sprintf("%'.02d",7);                            
                        $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_8!=''){     
                        $bln = sprintf("%'.02d",8);                            
                        $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_9!=''){     
                        $bln = sprintf("%'.02d",9);                            
                        $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_10!=''){     
                        $bln = sprintf("%'.02d",10);                            
                        $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_11!=''){     
                        $bln = sprintf("%'.02d",11);                            
                        $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              

                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }

                      if($tahun_12!=''){     
                        $bln = sprintf("%'.02d",12);                            
                        $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                        $tgl_beli_1 = $tgl_surat."-".$tgl1;              
                        $tgl_beli_2 = $tgl_surat."-".$tgl2;              

                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                          else $jumlah_jual1 = 0;
                        
                        echo "<td align='center'>$jumlah_jual1</td>";
                      }   
                      echo "</tr>";                                                                                                                                                                  
            } ?>
            <tr>
                  <td>Tunai</td>
                  <?php                  
                $bln = sprintf("%'.02d",$bulan);                                                
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                  AND tr_spk.jenis_beli = 'Cash'")->row()->jum;

                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                  $tot += $jumlah_jual;
                
                echo "<td align='center'>$jumlah_jual</td>";
                  
                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;
                    $tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }   
                  echo "</tr>";
                  ?>

            </tr>
            <tr>
              <td bgcolor='grey' class='bold text-center'>Grand Total</td>
              <td bgcolor='grey' class='bold text-center'><?php echo $total ?></td>
              <?php                                               


              if($tahun_1!=''){     
                $bln = sprintf("%'.02d",1);                            
                $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_2!=''){     
                $bln = sprintf("%'.02d",2);                            
                $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_3!=''){     
                $bln = sprintf("%'.02d",3);                            
                $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_4!=''){     
                $bln = sprintf("%'.02d",4);                            
                $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_5!=''){     
                $bln = sprintf("%'.02d",5);                            
                $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_6!=''){     
                $bln = sprintf("%'.02d",6);                            
                $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_7!=''){     
                $bln = sprintf("%'.02d",7);                            
                $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_8!=''){     
                $bln = sprintf("%'.02d",8);                            
                $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_9!=''){     
                $bln = sprintf("%'.02d",9);                            
                $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_10!=''){     
                $bln = sprintf("%'.02d",10);                            
                $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_11!=''){     
                $bln = sprintf("%'.02d",11);                            
                $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }                  

              if($tahun_12!=''){     
                $bln = sprintf("%'.02d",12);                            
                $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";

                $total += $tot;
                $total_jum += $jumlah_jual1;
              }              
              ?>
            </tr>            
        </table> <br>
        <?php
      }elseif($tipe == 'per_dealer_kab'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Sales</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
            </tr>
          </thead>
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500 ORDER BY kabupaten");
          $total=0;
          foreach ($sql_dealer->result() as $row) {          
          ?>                   
            <tbody>
              <tr>
                <td bgcolor='pink' colspan="<?php echo $isi+2 ?>"><?php echo $row->kabupaten ?></td>
              </tr>
              <?php  
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $tot=0;
              $sql = $this->db->query("SELECT * FROM ms_dealer 
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE ms_kabupaten.id_kabupaten = '$row->id_kabupaten'");
              foreach ($sql->result() as $amb) {          
                echo "<tr>
                      <td>$amb->nama_dealer</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                        LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                        LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                        AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;
                          $tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                          
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }   
                        echo "</tr>";                                                                                          
                }                      
                ?>   
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Sub Total</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php                                     


                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";

                    $total += $tot;
                    $total_jum += $jumlah_jual1;
                  }
                  ?>
                </tr>                     
            </tbody>            
          <?php } ?>
            <tr>
              <td bgcolor='grey' class='bold text-center'>Grand Total</td>
              <td bgcolor='grey' class='bold text-center'><?php echo $total ?></td>
              <?php                                     


              if($tahun_1!=''){     
                $bln = sprintf("%'.02d",1);                            
                $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_2!=''){     
                $bln = sprintf("%'.02d",2);                            
                $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_3!=''){     
                $bln = sprintf("%'.02d",3);                            
                $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_4!=''){     
                $bln = sprintf("%'.02d",4);                            
                $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_5!=''){     
                $bln = sprintf("%'.02d",5);                            
                $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_6!=''){     
                $bln = sprintf("%'.02d",6);                            
                $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_7!=''){     
                $bln = sprintf("%'.02d",7);                            
                $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_8!=''){     
                $bln = sprintf("%'.02d",8);                            
                $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_9!=''){     
                $bln = sprintf("%'.02d",9);                            
                $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_10!=''){     
                $bln = sprintf("%'.02d",10);                            
                $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_11!=''){     
                $bln = sprintf("%'.02d",11);                            
                $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }                  

              if($tahun_12!=''){     
                $bln = sprintf("%'.02d",12);                            
                $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;

                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";

                $total += $tot;
                $total_jum += $jumlah_jual1;
              }
              ?>
            </tr>            
        </table> <br>
        <?php      
      }elseif($tipe == 'per_kab'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Penjualan via Leasing</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Kode Fincoy</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
            </tr>
          </thead>
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500 ORDER BY kabupaten");
          $total=0;
          foreach ($sql_dealer->result() as $row) {          
          ?>                   
            <tbody>
              <tr>
                <td bgcolor='pink' colspan="<?php echo $isi+2 ?>"><?php echo $row->kabupaten ?></td>
              </tr>
              <?php  
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $tot=0;
              $sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
              foreach ($sql->result() as $amb) {          
                echo "<tr>
                      <td>$amb->finance_company</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                        LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                        LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                        AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten' AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;
                          $tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                            AND tr_sales_order.id_dealer = '$amb->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                          $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                            AND tr_spk.id_finance_company = '$amb->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }   
                        echo "</tr>";                                                                                          
                }                      
                ?>
                <tr>
                  <td>Tunai</td>
                  <?php                  
                $bln = sprintf("%'.02d",$bulan);                                                
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                  AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;

                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                  $tot += $jumlah_jual;
                
                echo "<td align='center'>$jumlah_jual</td>";
                  
                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;
                    $tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
                    $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk                            
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'
                      AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    
                    echo "<td align='center'>$jumlah_jual1</td>";
                  }   
                  echo "</tr>";
                  ?>                


                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Sub Total</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php                                     


                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND ms_kabupaten.id_kabupaten = '$row->id_kabupaten'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";

                    $total += $tot;
                    $total_jum += $jumlah_jual1;
                  }
                  ?>
                </tr>                     
            </tbody>            
          <?php } ?>
            <tr>
              <td bgcolor='grey' class='bold text-center'>Grand Total</td>
              <td bgcolor='grey' class='bold text-center'><?php echo $total ?></td>
              <?php                                     


              if($tahun_1!=''){     
                $bln = sprintf("%'.02d",1);                            
                $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_2!=''){     
                $bln = sprintf("%'.02d",2);                            
                $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_3!=''){     
                $bln = sprintf("%'.02d",3);                            
                $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_4!=''){     
                $bln = sprintf("%'.02d",4);                            
                $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_5!=''){     
                $bln = sprintf("%'.02d",5);                            
                $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_6!=''){     
                $bln = sprintf("%'.02d",6);                            
                $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_7!=''){     
                $bln = sprintf("%'.02d",7);                            
                $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_8!=''){     
                $bln = sprintf("%'.02d",8);                            
                $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_9!=''){     
                $bln = sprintf("%'.02d",9);                            
                $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_10!=''){     
                $bln = sprintf("%'.02d",10);                            
                $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_11!=''){     
                $bln = sprintf("%'.02d",11);                            
                $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }                  

              if($tahun_12!=''){     
                $bln = sprintf("%'.02d",12);                            
                $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;

                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";

                $total += $tot;
                $total_jum += $jumlah_jual1;
              }
              ?>
            </tr>            
        </table> <br>
        <?php      
      }elseif($tipe == 'per_tipe_fin'){ $tgl = bln($bulan)." ".$tahun; ?>
        
        <div style="text-align: center;font-size: 13pt"><b>Laporan Perbandingan Penjualan via Leasing</b></div>                
        <div style="text-align: center;font-size: 10pt"><b>Dari Tgl : <?php echo $tgl1 ?> s/d <?php echo $tgl2 ?></b></div>                
        <br>
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Tipe Motor</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Sales <?php echo $tgl ?></td>                                        
              <?php 
              $isi=0;
              if($tahun_1!='') $isi++;    
              if($tahun_2!='') $isi++;    
              if($tahun_3!='') $isi++;    
              if($tahun_4!='') $isi++;    
              if($tahun_5!='') $isi++;    
              if($tahun_6!='') $isi++;    
              if($tahun_7!='') $isi++;    
              if($tahun_8!='') $isi++;    
              if($tahun_9!='') $isi++;    
              if($tahun_10!='') $isi++;   
              if($tahun_11!='') $isi++;   
              if($tahun_12!='') $isi++;
              ?>
              <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $isi ?>">Data Perbandingan</td>                                                      
            </tr>
            <tr>
            <?php                             
            if($tahun_1!=''){     
              $bln_1 = substr(bln(1),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_1 $tahun_1</td>";
            }
            if($tahun_2!=''){     
              $bln_2 = substr(bln(2),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_2 $tahun_2</td>";
            }             

            if($tahun_3!=''){     
              $bln_3 = substr(bln(3),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_3 $tahun_3</td>";
            }
            if($tahun_4!=''){     
              $bln_4 = substr(bln(4),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_4 $tahun_4</td>";
            }             

            if($tahun_5!=''){     
              $bln_5 = substr(bln(5),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_5 $tahun_5</td>";
            }
            if($tahun_6!=''){     
              $bln_6 = substr(bln(6),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_6 $tahun_6</td>";
            }             

            if($tahun_7!=''){     
              $bln_7 = substr(bln(7),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_7 $tahun_7</td>";
            }
            if($tahun_8!=''){     
              $bln_8 = substr(bln(8),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_8 $tahun_8</td>";
            }             

            if($tahun_9!=''){     
              $bln_9 = substr(bln(9),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_9 $tahun_9</td>";
            }
            if($tahun_10!=''){      
              $bln_10 = substr(bln(10),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_10 $tahun_10</td>";
            }             

            if($tahun_11!=''){      
              $bln_11 = substr(bln(11),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_11 $tahun_11</td>";
            }
            if($tahun_12!=''){      
              $bln_12 = substr(bln(12),0,3);
              echo "<td align='center' bgcolor='yellow'>$bln_12 $tahun_12</td>";
            }                         
            ?>
            </tr>
          </thead>
          
          <?php 
          $sql_dealer = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
          $total=0;
          foreach ($sql_dealer->result() as $row) {          
          ?>    
            <tbody>                           
              <tr>
                <td bgcolor='pink' colspan="<?php echo $isi+2 ?>"><?php echo $row->finance_company ?></td>
              </tr>
              <?php  
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $tot=0;
              $sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_scan_barcode INNER JOIN tr_sales_order
                  ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
              foreach ($sql->result() as $amb) {          
                echo "<tr>
                      <td>$amb->tipe_ahm</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                        LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                        LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                        AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }   
                        echo "</tr>";                                                                                          
                }                      
                ?>                   
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Sub Total</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php                                     


                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";

                    $total += $tot;
                    $total_jum += $jumlah_jual1;
                  }
                  ?>
                </tr>                                   
            </tbody> 

                     
          <?php } ?>
            <tbody>
              <tr>
                <td bgcolor='pink' colspan="<?php echo $isi+2 ?>">T U N A I</td>
              </tr>
              <?php  
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $tot=0;
              $sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_scan_barcode INNER JOIN tr_sales_order
                  ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE tr_spk.jenis_beli = 'Cash' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
              foreach ($sql->result() as $amb) {          
                echo "<tr>
                      <td>$amb->tipe_ahm</td>";
                      $bln = sprintf("%'.02d",$bulan);                                                
                      $tgl_surat_1 = $tahun."-".$bln."-".$tgl1;              
                      $tgl_surat_2 = $tahun."-".$bln."-".$tgl2;              
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                        LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                        LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_surat_1' AND '$tgl_surat_2'
                        AND tr_spk.jenis_beli = 'Cash'")->row()->jum;

                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                      
                      echo "<td align='center'>$jumlah_jual</td>";
                        
                        if($tahun_1!=''){     
                          $bln = sprintf("%'.02d",1);                            
                          $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_2!=''){     
                          $bln = sprintf("%'.02d",2);                            
                          $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_3!=''){     
                          $bln = sprintf("%'.02d",3);                            
                          $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_4!=''){     
                          $bln = sprintf("%'.02d",4);                            
                          $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_5!=''){     
                          $bln = sprintf("%'.02d",5);                            
                          $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_6!=''){     
                          $bln = sprintf("%'.02d",6);                            
                          $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_7!=''){     
                          $bln = sprintf("%'.02d",7);                            
                          $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_8!=''){     
                          $bln = sprintf("%'.02d",8);                            
                          $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_9!=''){     
                          $bln = sprintf("%'.02d",9);                            
                          $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_10!=''){     
                          $bln = sprintf("%'.02d",10);                            
                          $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_11!=''){     
                          $bln = sprintf("%'.02d",11);                            
                          $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }

                        if($tahun_12!=''){     
                          $bln = sprintf("%'.02d",12);                            
                          $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                            LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2' AND tr_spk.jenis_beli = 'Cash'
                            AND tr_scan_barcode.tipe_motor = '$amb->id_tipe_kendaraan'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                            else $jumlah_jual1 = 0;
                          
                          echo "<td align='center'>$jumlah_jual1</td>";
                        }   
                        echo "</tr>";                                                                                          
                }                      
                ?>                   
                <tr>
                  <td bgcolor='yellow' class='bold text-center'>Sub Total</td>              
                  <td bgcolor='yellow' class='bold text-center'><?php echo $tot ?></td>              
                  <?php                                     


                  if($tahun_1!=''){     
                    $bln = sprintf("%'.02d",1);                            
                    $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_2!=''){     
                    $bln = sprintf("%'.02d",2);                            
                    $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_3!=''){     
                    $bln = sprintf("%'.02d",3);                            
                    $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_4!=''){     
                    $bln = sprintf("%'.02d",4);                            
                    $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_5!=''){     
                    $bln = sprintf("%'.02d",5);                            
                    $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_6!=''){     
                    $bln = sprintf("%'.02d",6);                            
                    $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_7!=''){     
                    $bln = sprintf("%'.02d",7);                            
                    $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_8!=''){     
                    $bln = sprintf("%'.02d",8);                            
                    $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_9!=''){     
                    $bln = sprintf("%'.02d",9);                            
                    $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_10!=''){     
                    $bln = sprintf("%'.02d",10);                            
                    $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }

                  if($tahun_11!=''){     
                    $bln = sprintf("%'.02d",11);                            
                    $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";
                  }                  

                  if($tahun_12!=''){     
                    $bln = sprintf("%'.02d",12);                            
                    $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                      LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                      WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'
                      AND tr_spk.id_finance_company = '$row->id_finance_company' AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;

                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $jumlah_jual1 += $jumlah;
                    echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual1</td>";

                    $total += $tot;
                    $total_jum += $jumlah_jual1;
                  }
                  ?>
                </tr>
            </tbody>
            <tr>
              <td bgcolor='grey' class='bold text-center'>Grand Total</td>
              <td bgcolor='grey' class='bold text-center'><?php echo $total ?></td>
              <?php                                     


              if($tahun_1!=''){     
                $bln = sprintf("%'.02d",1);                            
                $tgl_surat = $tahun_1."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_2!=''){     
                $bln = sprintf("%'.02d",2);                            
                $tgl_surat = $tahun_2."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_3!=''){     
                $bln = sprintf("%'.02d",3);                            
                $tgl_surat = $tahun_3."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_4!=''){     
                $bln = sprintf("%'.02d",4);                            
                $tgl_surat = $tahun_4."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_5!=''){     
                $bln = sprintf("%'.02d",5);                            
                $tgl_surat = $tahun_5."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_6!=''){     
                $bln = sprintf("%'.02d",6);                            
                $tgl_surat = $tahun_6."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_7!=''){     
                $bln = sprintf("%'.02d",7);                            
                $tgl_surat = $tahun_7."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_8!=''){     
                $bln = sprintf("%'.02d",8);                            
                $tgl_surat = $tahun_8."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_9!=''){     
                $bln = sprintf("%'.02d",9);                            
                $tgl_surat = $tahun_9."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_10!=''){     
                $bln = sprintf("%'.02d",10);                            
                $tgl_surat = $tahun_10."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }

              if($tahun_11!=''){     
                $bln = sprintf("%'.02d",11);                            
                $tgl_surat = $tahun_11."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";
              }                  

              if($tahun_12!=''){     
                $bln = sprintf("%'.02d",12);                            
                $tgl_surat = $tahun_12."-".$bln;$tgl_beli_1 = $tgl_surat."-".$tgl1;              
            $tgl_beli_2 = $tgl_surat."-".$tgl2;              
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  LEFT JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,10) BETWEEN '$tgl_beli_1' AND '$tgl_beli_2'")->row()->jum;

                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                  else $jumlah_jual1 = 0;
                $jumlah_jual1 += $jumlah;
                echo "<td bgcolor='grey' class='bold text-center'>$jumlah_jual1</td>";

                $total += $tot;
                $total_jum += $jumlah_jual1;
              }
              ?>
            </tr>            
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
                tipe:getRadioVal(document.getElementById("frm"),"tipe"),
                bulan:document.getElementById("bulan").value,                
                tahun:document.getElementById("tahun").value,                
                check_1:document.getElementById("check_1").value,tahun_1:document.getElementById("tahun_1").value,                
                check_2:document.getElementById("check_2").value,tahun_2:document.getElementById("tahun_2").value,                
                check_3:document.getElementById("check_3").value,tahun_3:document.getElementById("tahun_3").value,                
                check_4:document.getElementById("check_4").value,tahun_4:document.getElementById("tahun_4").value,                
                check_5:document.getElementById("check_5").value,tahun_5:document.getElementById("tahun_5").value,                
                check_6:document.getElementById("check_6").value,tahun_6:document.getElementById("tahun_6").value,                
                check_7:document.getElementById("check_7").value,tahun_7:document.getElementById("tahun_7").value,                
                check_8:document.getElementById("check_8").value,tahun_8:document.getElementById("tahun_8").value,                
                check_9:document.getElementById("check_9").value,tahun_9:document.getElementById("tahun_9").value,                
                check_10:document.getElementById("check_10").value,tahun_10:document.getElementById("tahun_10").value,                
                check_11:document.getElementById("check_11").value,tahun_11:document.getElementById("tahun_11").value,                
                check_12:document.getElementById("check_12").value,tahun_12:document.getElementById("tahun_12").value,                
                cetak:'cetak',
                }
      //alert(value.tahun_1);

      if (value.tahun_1 == '' && value.tahun_2 == '' && value.tahun_3 == '' && value.tahun_4 == '' && value.tahun_5 == '' && value.tahun_6 == '' && value.tahun_7 == '' && value.tahun_8 == '' && value.tahun_9 == '' && value.tahun_10 == '' && value.tahun_11 == '' && value.tahun_12 == '') {
        alert('Data Pembanding belum dipilih, silahkan pilih salah satu bulan sebagai data perbandingan penjualan ..!');
        return false;
      }else{
        //alert(value.check_1);        
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/comparisons_report?") ?>tipe='+value.tipe+'&tgl1='+value.tgl1+'&tahun='+value.tahun+'&bulan='+value.bulan+'&cetak='+value.cetak+'&tgl2='+value.tgl2+'&check_1='+value.check_1+'&tahun_1='+value.tahun_1+'&check_2='+value.check_2+'&tahun_2='+value.tahun_2+'&check_3='+value.check_3+'&tahun_3='+value.tahun_3+'&check_4='+value.check_4+'&tahun_4='+value.tahun_4+'&check_5='+value.check_5+'&tahun_5='+value.tahun_5+'&check_6='+value.check_6+'&tahun_6='+value.tahun_6+'&check_7='+value.check_7+'&tahun_7='+value.tahun_7+'&check_8='+value.check_8+'&tahun_8='+value.tahun_8+'&check_9='+value.check_9+'&tahun_9='+value.tahun_9+'&check_10='+value.check_10+'&tahun_10='+value.tahun_10+'&check_11='+value.check_11+'&tahun_11='+value.tahun_11+'&check_12='+value.check_12+'&tahun_12='+value.tahun_12);
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
  //for (var i = 1; i <= 12; i++) {    
    $("#tahun_1").prop("disabled", true);          
  //}
}
</script>
<script type="text/javascript">
$(document).ready(function() {    
  var tahun = $("#tahun_asal").val();
  $("#check_1").click(function () {
    if($(this).prop('checked')){
      $("#tahun_1").prop('disabled', false);
      $("#tahun_1").val(tahun);
    }else{
      $("#tahun_1").prop('disabled', true);
      $("#tahun_1").val('');
    }      
  });

  $("#check_2").click(function () {
    if($(this).prop('checked')){
      $("#tahun_2").prop('disabled', false);
      $("#tahun_2").val(tahun);
    }else{
      $("#tahun_2").prop('disabled', true);
      $("#tahun_2").val('');
    }      
  });

  $("#check_3").click(function () {
    if($(this).prop('checked')){
      $("#tahun_3").prop('disabled', false);
      $("#tahun_3").val(tahun);
    }else{
      $("#tahun_3").prop('disabled', true);
      $("#tahun_3").val('');
    }      
  });

  $("#check_4").click(function () {
    if($(this).prop('checked')){
      $("#tahun_4").prop('disabled', false);
      $("#tahun_4").val(tahun);
    }else{
      $("#tahun_4").prop('disabled', true);
      $("#tahun_4").val('');
    }      
  });

  $("#check_5").click(function () {
    if($(this).prop('checked')){
      $("#tahun_5").prop('disabled', false);
      $("#tahun_5").val(tahun);
    }else{
      $("#tahun_5").prop('disabled', true);
      $("#tahun_5").val('');
    }      
  });

  $("#check_6").click(function () {
    if($(this).prop('checked')){
      $("#tahun_6").prop('disabled', false);
      $("#tahun_6").val(tahun);
    }else{
      $("#tahun_6").prop('disabled', true);
      $("#tahun_6").val('');
    }      
  });

  $("#check_7").click(function () {
    if($(this).prop('checked')){
      $("#tahun_7").prop('disabled', false);
      $("#tahun_7").val(tahun);
    }else{
      $("#tahun_7").prop('disabled', true);
      $("#tahun_7").val('');
    }      
  });

  $("#check_8").click(function () {
    if($(this).prop('checked')){
      $("#tahun_8").prop('disabled', false);
      $("#tahun_8").val(tahun);
    }else{
      $("#tahun_8").prop('disabled', true);
      $("#tahun_8").val('');
    }      
  });

  $("#check_9").click(function () {
    if($(this).prop('checked')){
      $("#tahun_9").prop('disabled', false);
      $("#tahun_9").val(tahun);
    }else{
      $("#tahun_9").prop('disabled', true);
      $("#tahun_9").val('');
    }      
  });

  $("#check_10").click(function () {
    if($(this).prop('checked')){
      $("#tahun_10").prop('disabled', false);
      $("#tahun_10").val(tahun);
    }else{
      $("#tahun_10").prop('disabled', true);
      $("#tahun_10").val('');
    }      
  });
  $("#check_11").click(function () {
    if($(this).prop('checked')){
      $("#tahun_11").prop('disabled', false);
      $("#tahun_11").val(tahun);
    }else{
      $("#tahun_11").prop('disabled', true);
      $("#tahun_11").val('');
    }      
  });
  $("#check_12").click(function () {
    if($(this).prop('checked')){
      $("#tahun_12").prop('disabled', false);
      $("#tahun_12").val(tahun);
    }else{
      $("#tahun_12").prop('disabled', true);
      $("#tahun_12").val('');
    }      
  });
});
</script>
