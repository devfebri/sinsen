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
      <?php $tgl = bln($bulan)." ".$tahun; ?>  
      <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan ke Leasing</b></div>                
      <div style="text-align: center;font-size: 10pt"><b>Bulan : <?php echo $tgl ?></b></div>                
      <br>

        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <thead>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>Nama Dealer</td>              
              <?php 
              $sql_1 = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
              foreach ($sql_1->result() as $isi) {                
                echo "<td bgcolor='yellow' class='bold text-center'>$isi->finance_company</td>";
              } ?>
              <td bgcolor='yellow' class='bold text-center'>Tunai</td>                                        
              <td bgcolor='yellow' class='bold text-center'>Total</td>                                        
            </tr>
          </thead>
          <tbody>
            <?php                         
            $bln = sprintf("%'.02d",$bulan);                            
            $tgl_surat_1 = $tahun."-".$bln;
            $sql_2 = $this->db->query("SELECT * FROM ms_tipe_kendaraan INNER JOIN tr_scan_barcode ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  INNER JOIN tr_sales_order ON tr_scan_barcode.no_mesin = tr_sales_order.no_mesin
                  INNER JOIN ms_dealer ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1'
                  GROUP BY tr_sales_order.id_dealer");
            $total=0;
            foreach ($sql_2->result() as $row) {                                        
              echo "<tr>
                      <td>$row->nama_dealer</td>";           
                      $tot=0;           
                      $sql_1 = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
                      $bln = sprintf("%'.02d",$bulan);                            
                      $tgl_surat_1 = $tahun."-".$bln;
                      foreach ($sql_1->result() as $isi) {                
                        $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_spk.jenis_beli = 'Kredit'
                          AND tr_spk.id_finance_company = '$isi->id_finance_company' AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                        if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                          else $jumlah_jual = 0;
                        $tot += $jumlah_jual;
                        echo "<td align='center'>$jumlah_jual</td>";
                      }
                      
                      $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_spk.jenis_beli = 'Cash'
                        AND tr_sales_order.id_dealer = '$row->id_dealer'")->row()->jum;
                      if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_jual2 = $cek_so3;
                        else $jumlah_jual2 = 0;
                        $tot += $jumlah_jual2;
                      echo "
                      <td align='center'>".mata_uang3($jumlah_jual2)."</td>
                      <td align='center'>".mata_uang3($tot)."</td>
                    </tr>";
              }                             
            ?>                  
          </tbody>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'>TOTAL</td>
              <?php 
              $sql_1 = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
              $bln = sprintf("%'.02d",$bulan);                            
              $tgl_surat_1 = $tahun."-".$bln;
              foreach ($sql_1->result() as $isi) {                
                $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_spk.jenis_beli = 'Kredit'
                  AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                $total += $jumlah_jual;
                echo "<td  bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
              }
              
              $cek_so3 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_spk.jenis_beli = 'Cash'")->row()->jum;
              if(isset($cek_so3) AND $cek_so3 != 0) $jumlah_jual2 = $cek_so3;
                else $jumlah_jual2 = 0;
              $total += $jumlah_jual2;
              ?>                            
              <td bgcolor='yellow' class='bold text-center'><?php echo $jumlah_jual2 ?></td>
              <td bgcolor='yellow' class='bold text-center'><?php echo $total ?></td>
            </tr>
          </tfoot>
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
                }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);        
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/laporan_penjualan_leasing?") ?>cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan);
        document.getElementById("showReport").onload = function(e){          
        $('.loader').hide();       
        };
      }
    }
</script>
