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
            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-1 control-label">Tahun</label>
                  <span id="label_bulan">                    
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
                  </span>
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
                    <input type="radio" name="tipe" id="tipe" value="global" class="minimal" checked> Global
                  </div>
                  <div class="col-sm-3">                    
                    <input type="radio" name="tipe" id="tipe" value="per_tipe" class="minimal"> Per Dealer & Tipe
                  </div>  
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_dealer" class="minimal"> Per Dealer
                  </div>                  
                                                                                      
                </div>             
                <div class="form-group">                  
                  <span id="label_dealer">
                    <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_dealer" id="id_dealer">
                        <option value="all">All Dealers</option>
                        <?php 
                        $sql_kab = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 AND h1=1 ORDER BY ms_dealer.id_dealer ASC");
                        foreach ($sql_kab->result() as $isi) {
                          echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                        }
                         ?>
                      </select>
                    </div>
                  </span>   
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Preview</button>                                                      
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
      <?php if($tipe == 'global'){ $tgl = bln($bulan)." ".$tahun; ?>
        <!-- <div style="text-align: center;font-size: 13pt"><b>Grafik Penjualan Tahun <?php $tahun ?></b></div>                
        <hr>               -->
        <!-- <div id="tampil_grafik_global"></div> -->

        <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan Tahun <?php $tahun ?></b></div>                
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Nama Dealer</td>              
              <td bgcolor='yellow' class='bold text-center' colspan="12">Sales</td>              
              <td bgcolor='yellow' class='bold text-center' rowspan="2">Total Sales</td>
            </tr>
            <tr>
              <?php for ($i=1; $i <= 12; $i++) { 
                $i_j = bln($i);    
                echo "<td bgcolor='yellow' class='bold text-center'>$i_j</td>";
              } ?>
            </tr>
          </thead>
          <tbody>
            <?php 
            $query_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_provinsi = 1500");
            $total=0;
            foreach ($query_kab->result() as $kab) {              
              $dealer = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                    INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
                    INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
                    WHERE ms_dealer.active = 1 AND ms_dealer.h1=1 AND ms_kabupaten.id_kabupaten = '$kab->id_kabupaten'");
              $sub_total=0;
              foreach ($dealer->result() as $isi) {
                echo "<tr>
                        <td>$isi->nama_dealer</td>";           
                        $tot=0;           
                        for ($i=1; $i <= 12; $i++) {
                          $bln = sprintf("%'.02d",$i);                            
                          $tgl_surat_1 = $tahun."-".$bln;
                          $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                            WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$isi->id_dealer'")->row()->jum;
                          if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                            else $jumlah_jual = 0;
                          $tot += $jumlah_jual;
                          echo "<td align='center'>".mata_uang3($jumlah_jual)."</td>";
                        }
                        echo "
                        <td align='center'>".mata_uang3($tot)."</td>
                      </tr>";
              }
              echo "
              <tr>
                <td bgcolor='yellow' class='bold text-center'>Sub Total $kab->kabupaten</td>";
                for ($i=1; $i <= 12; $i++) { 
                  $bln = sprintf("%'.02d",$i);                            
                  $tgl_surat_1 = $tahun."-".$bln;
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                    INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                    INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                    INNER JOIN ms_kecamatan ON ms_kecamatan.id_kecamatan = ms_kelurahan.id_kecamatan
                    INNER JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten 
                    WHERE ms_dealer.active = 1 AND ms_kabupaten.id_kabupaten = '$kab->id_kabupaten'
                    AND LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                    else $jumlah_jual = 0;
                  $sub_total += $jumlah_jual;
                  echo "<td bgcolor='yellow' class='bold text-center'>".mata_uang3($jumlah_jual)."</td>";
                }
                echo "<td bgcolor='yellow' class='bold text-center'>".mata_uang3($sub_total)."</td>
              </tr>";              
            }
            ?>                      
          </tbody>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>GRAND TOTAL</td>
              <?php for ($i=1; $i <= 12; $i++) { 
                $bln = sprintf("%'.02d",$i);                            
                $tgl_surat_1 = $tahun."-".$bln;
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                       
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                $total += $jumlah_jual;
                echo "<td bgcolor='yellow' class='bold text-center'>".mata_uang3($jumlah_jual)."</td>";
              } ?>
              <td bgcolor='yellow' class='bold text-center'><?php echo mata_uang3($total) ?></td>
            </tr>
          </tfoot>
        </table>
        <?php
      }elseif($tipe=='per_tipe'){ $tgl = bln($bulan)." ".$tahun;         
        ?>
        <style>
          @media print {
          @page {
            sheet-size: 330mm 216mm;
            margin-left: 0.1cm;
            margin-right: 0.1cm;
            margin-bottom: 0.5cm;
            margin-top: 0.5cm;
          }
        }      
        </style>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan Per Dealer Per Tipe Motor</b></div>        
        <div style="text-align: center; font-weight: bold;">Periode : <?php echo $tgl ?></div>
        <hr>      
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr text-rotate='90'>                
            <td bgcolor='yellow' text-rotate='0' class='bold text-center'>Nama Dealer/Tipe Motor</td>
            <?php 
            //$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan where active = 1");
            $sql_1 = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan 
                WHERE ms_tipe_kendaraan.active = 1 GROUP BY tr_scan_barcode.tipe_motor");
            foreach ($sql_1->result() as $isi) {
              echo "<td valign='bootom' bgcolor='yellow' class='bold text-center'>$isi->tipe_ahm</td>";
            }
            ?>            
            <td bgcolor='yellow' class='bold text-center'>Total</td>                
          </tr> 
          <?php 
          $sql_2 = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1");
          $total = 0;
          foreach ($sql_2->result() as $row) {
            echo "
              <tr>
                <td>$row->nama_dealer</td>";
                //$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan where active = 1");
                $sql_1 = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan 
                  WHERE ms_tipe_kendaraan.active = 1 GROUP BY tr_scan_barcode.tipe_motor");
                $tot=0;
                foreach ($sql_1->result() as $isi) {
                  $bln = sprintf("%'.02d",$bulan);                            
                  $tgl_surat_1 = $tahun."-".$bln;
                  $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan' 
                    AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                  if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                    else $jumlah_jual = 0;
                  $tot += $jumlah_jual;
                  echo "<td>$jumlah_jual</td>";
                }
                echo "<td>$tot</td>   
              </tr>
            ";
          }
          ?>          
          <tr>
            <td bgcolor='yellow' class='bold text-center'>Total</td>                
            <?php 
            //$sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan where active = 1");
            $sql_1 = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan 
                WHERE ms_tipe_kendaraan.active = 1 GROUP BY tr_scan_barcode.tipe_motor");
            foreach ($sql_1->result() as $isi) {
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'")->row()->jum;
              if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                else $jumlah_jual = 0;
              $total += $jumlah_jual;
              echo "<td valign='bootom' bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
            }
            ?>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $total ?></td>                
          </tr>                   
        </table>
        <?php
      }elseif($tipe == 'per_dealer' AND $id_dealer != 'all'){ $tgl = bln($bulan)." ".$tahun; 

        $nama_dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row()->nama_dealer;
        ?>
        <!-- <div style="text-align: center;font-size: 13pt"><b>Grafik Penjualan Tahun <?php $tahun ?></b></div>                
        <hr>               -->
        <!-- <div id="tampil_grafik_global"></div> -->




        <div style="text-align: center;font-size: 13pt"><b>Data Penjualan Tahun <?php $tahun ?></b></div>                
        <div style="text-align: center;font-size: 10pt"><i>Dealer: <?php echo $nama_dealer ?></i></div><br>                
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Tipe Motor</td>                            
              <?php for ($i=1; $i <= 12; $i++) { 
                $i_j = substr(bln($i),0,3);    
                echo "<td bgcolor='yellow' class='bold text-center'>$i_j</td>";
              } ?>
              <td bgcolor='yellow' class='bold text-center'>Total Sales</td>
            </tr>            
          </thead>
          <tbody>
          <?php 
          $sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN tr_scan_barcode ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,4) = '$tahun' AND tr_sales_order.id_dealer = '$id_dealer'
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
          $g_tot=0;
          foreach ($sql_1->result() as $isi) {           
            echo "<tr>
                    <td>$isi->tipe_ahm</td>";
                    $total=0;
                    for ($i=1; $i <= 12; $i++) {  
                      $bln = sprintf("%'.02d",$i);                                                                     
                      $tgl_surat_1 = $tahun."-".$bln;
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'")->row()->jum;
                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                      $total += $jumlah_jual;
                      echo "<td align='center'>$jumlah_jual</td>";
                    }
                    echo "<td align='center'>$total</td>
                  </tr>";
          }
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Grand Total</td>
              <?php for ($i=1; $i <= 12; $i++) { 
                $bln = sprintf("%'.02d",$i);                            
                $tgl_surat_1 = $tahun."-".$bln;
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                $g_tot += $jumlah_jual;
                echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
              } ?>
              <td bgcolor='yellow' class='bold text-center'><?php echo $g_tot ?></td>
            </tr>
          </tfoot>            
        </table> 
        <?php       
      }elseif($tipe == 'per_dealer' AND $id_dealer == 'all'){ $tgl = bln($bulan)." ".$tahun; ?>
        <!-- <div style="text-align: center;font-size: 13pt"><b>Grafik Penjualan Tahun <?php $tahun ?></b></div>                
        <hr>              
        <div id="imgContainer"></div> -->
        <!-- <div id="tampil_grafik_global"></div> -->

        <div style="text-align: center;font-size: 13pt"><b>Data Penjualan Tahun <?php $tahun ?></b></div>                
        <div style="text-align: center;font-size: 11pt"><i>Dealer: Seluruh Dealer</i></div><br>                
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Tipe Motor</td>                            
              <?php for ($i=1; $i <= 12; $i++) { 
                $i_j = substr(bln($i),0,3);    
                echo "<td bgcolor='yellow' class='bold text-center'>$i_j</td>";
              } ?>
              <td bgcolor='yellow' class='bold text-center'>Total Sales</td>
            </tr>            
          </thead>
          <tbody>
          <?php 
          $sql_1 = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN tr_scan_barcode ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,4) = '$tahun'
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
          $g_tot=0;
          foreach ($sql_1->result() as $isi) {           
            echo "<tr>
                    <td>$isi->tipe_ahm</td>";
                    $total=0;
                    for ($i=1; $i <= 12; $i++) {  
                      $bln = sprintf("%'.02d",$i);                                                                     
                      $tgl_surat_1 = $tahun."-".$bln;
                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'
                        AND tr_scan_barcode.tipe_motor = '$isi->id_tipe_kendaraan'")->row()->jum;
                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                        else $jumlah_jual = 0;
                      $total += $jumlah_jual;
                      echo "<td align='center'>$jumlah_jual</td>";
                    }
                    echo "<td align='center'>$total</td>
                  </tr>";
          }
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Grand Total</td>
              <?php for ($i=1; $i <= 12; $i++) { 
                $bln = sprintf("%'.02d",$i);                            
                $tgl_surat_1 = $tahun."-".$bln;
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                $g_tot += $jumlah_jual;
                echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
              } ?>
              <td bgcolor='yellow' class='bold text-center'><?php echo $g_tot ?></td>
            </tr>
          </tfoot>            
        </table>        
        
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
        get_grafik();
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/laporan_penjualan_bulanan?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan+'&id_dealer='+value.id_dealer);
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
<script
  src="https://code.jquery.com/jquery-1.9.1.js"
  integrity="sha256-e9gNBsAcA0DBuRWbm0oZfbiCyhjLrI6bmqAl5o+ZjUA="
  crossorigin="anonymous">

$('.radio').change(function(){
    var value = $(this).val();
    alert(value);
});
  
</script>
<script type="text/javascript">
function laporan_global(){    
  $("#tampil_grafik_global").show();
  var id = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id="+id;                           
     xhr.open("POST", "h1/laporan/laporan_penjualan_bulanan/get_grafik", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_grafik_global").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-git.js"></script>
<script type="text/javascript">
var options = {

    exporting: {
        url: 'http://export.highcharts.com/'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    series: [{
        data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
    }]
};
function get_grafik(){
  var obj = {},
  exportUrl = options.exporting.url;
  obj.options = JSON.stringify(options);
  obj.type = 'image/png';
  obj.async = true;

  $.ajax({
      type: 'post',
      url: exportUrl,
      data: obj,
      success: function (data) {
          var imgContainer = $("#imgContainer");
          $('<img>').attr('src', exportUrl + data).attr('width', '250px').appendTo(imgContainer);
          $('<a>or Download Here</a>').attr('href', exportUrl + data).appendTo(imgContainer);

      }
  });
}
</script>