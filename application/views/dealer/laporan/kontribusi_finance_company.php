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
        <div style="text-align: center;font-size: 13pt"><b>Kontribusi Finance Company</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' width='25%' rowspan="2">Finance Company</td>
            <td bgcolor='yellow' class='bold text-center' colspan="6">Range DP</td>
            <td bgcolor='yellow' class='bold text-center' width='10%' colspan="2">Total</td>                
          </tr>
          <tr>            
            <td bgcolor='yellow' class='bold text-center'>X < 1.500.000</td>
            <td bgcolor='yellow' class='bold text-center'>1.500.000 <= X < 2.000.000</td>
            <td bgcolor='yellow' class='bold text-center'>2.000.000 <= X < 2.500.000</td>
            <td bgcolor='yellow' class='bold text-center'>2.500.000 <= X < 3.000.000</td>
            <td bgcolor='yellow' class='bold text-center'>3.000.000 <= X < 4.000.000</td>            
            <td bgcolor='yellow' class='bold text-center'>4.000.000 <= X</td>
            <td bgcolor='yellow' class='bold text-center'>Unit</td>
            <td bgcolor='yellow' class='bold text-center'>Kontribusi</td>
          </tr>
          <?php           
          $sql_1 = $this->db->query("SELECT * FROM ms_finance_company WHERE active=1");
          $g_tot=0;$g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;$g_6=0;
          $bln = sprintf("%'.02d",$bulan);                                                                                         
          $tgl_surat_1 = $tahun."-".$bln;
          $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
              AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
          if(isset($cek_so) AND $cek_so != 0) $jumlah_total = $cek_so;
            else $jumlah_total = 0;

          foreach ($sql_1->result() as $isi) {           
            echo "<tr>
                    <td>$isi->finance_company</td>";
                    $total=0;                    
                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_spk.dp_stor < 1500000")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                      else $jumlah_jual1 = 0;
                    $total += $jumlah_jual1;
                    $g_1 += $jumlah_jual1;
                    echo "<td align='center'>$jumlah_jual1</td>";

                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_spk.dp_stor BETWEEN 1500000 AND 1999999")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual2 = $cek_so2;
                      else $jumlah_jual2 = 0;
                    $total += $jumlah_jual2;
                    $g_2 += $jumlah_jual2;
                    echo "<td align='center'>$jumlah_jual2</td>";

                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_spk.dp_stor BETWEEN 2000000 AND 2499999")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual3 = $cek_so2;
                      else $jumlah_jual3 = 0;
                    $total += $jumlah_jual3;
                    $g_3 += $jumlah_jual3;
                    echo "<td align='center'>$jumlah_jual3</td>";

                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_spk.dp_stor BETWEEN 2500000 AND 2999999")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual4 = $cek_so2;
                      else $jumlah_jual4 = 0;
                    $total += $jumlah_jual4;
                    $g_4 += $jumlah_jual4;
                    echo "<td align='center'>$jumlah_jual4</td>";

                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_spk.dp_stor BETWEEN 3000000 AND 3999999")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual5 = $cek_so2;
                      else $jumlah_jual5 = 0;
                    $total += $jumlah_jual5;
                    $g_5 += $jumlah_jual5;
                    echo "<td align='center'>$jumlah_jual5</td>";

                    $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                        INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                        WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                        AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                        AND tr_spk.dp_stor >= 4000000")->row()->jum;
                    if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual6 = $cek_so2;
                      else $jumlah_jual6 = 0;
                    $total += $jumlah_jual6;
                    $g_6 += $jumlah_jual6;
                    echo "<td align='center'>$jumlah_jual6</td>";

                    
                      
                    $kontribusi = round((($total / $jumlah_total) * 100),2);
                    $g_kon += $kontribusi;
                    echo "<td align='center'>$total</td>
                          <td align='center'>$kontribusi %</td>

                  </tr>";

                  $g_tot += $total;
          }
          ?>
          <tr>
            <td bgcolor='yellow' class='bold text-center'>TOTAL</td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_1 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_2 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_3 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_4 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_5 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_6 ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_tot ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_kon ?> %</td>            
          </tr>
        </table> <br>

        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>    
            <?php $seg = $this->db->query("SELECT * FROM ms_segment WHERE active = 1"); ?>            
            <td bgcolor='yellow' class='bold text-center' width='25%' rowspan="2">Finance Company</td>
            <td bgcolor='yellow' class='bold text-center' colspan="<?php echo $seg->num_rows() ?>">Segment</td>
            <td bgcolor='yellow' class='bold text-center' width='10%' colspan="2">Total</td>                
          </tr>
          <tr>            
            <?php             
            foreach ($seg->result() as $isi) {
              echo "<td bgcolor='yellow' class='bold text-center'>$isi->segment</td>";
            }
            ?>

            <td bgcolor='yellow' class='bold text-center'>Unit</td>
            <td bgcolor='yellow' class='bold text-center'>Kontribusi</td>
          </tr>
          <?php           
          $sql_1 = $this->db->query("SELECT * FROM ms_finance_company WHERE active=1");
          $g_tot=0;$g_1=0;$g_2=0;$g_3=0;$g_4=0;$g_5=0;$g_6=0;$g_kon=0;
          $bln = sprintf("%'.02d",$bulan);                                                                                         
          $tgl_surat_1 = $tahun."-".$bln;
          $cek_so = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
              AND tr_spk.jenis_beli = 'Kredit'")->row()->jum;
          if(isset($cek_so) AND $cek_so != 0) $jumlah_total = $cek_so;
            else $jumlah_total = 0;

          foreach ($sql_1->result() as $isi) {           
            echo "<tr>
                    <td>$isi->finance_company</td>";
                    $total=0;   
                    $seg = $this->db->query("SELECT * FROM ms_segment WHERE active = 1");
                    foreach ($seg->result() as $row) {

                      $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                          INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_ugm ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_ugm.id_tipe_kendaraan
                          WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                          AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$isi->id_finance_company'
                          AND ms_ugm.segment = '$row->segment'")->row()->jum;
                      if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual1 = $cek_so2;
                        else $jumlah_jual1 = 0;
                      $total += $jumlah_jual1;
                      $g_1 += $jumlah_jual1;
                      echo "<td align='center'>$jumlah_jual1</td>";

                    }

                    
                      
                    $kontribusi = round((($total / $jumlah_total) * 100),2);
                    $g_kon += $kontribusi;
                    echo "<td align='center'>$total</td>
                          <td align='center'>$kontribusi %</td>

                  </tr>";

                  $g_tot += $total;
          }
          ?>
          <tr>
            <td bgcolor='yellow' class='bold text-center'>TOTAL</td>            
            <?php  
            $g_tot=0;   
            $seg = $this->db->query("SELECT * FROM ms_segment WHERE active = 1");
            foreach ($seg->result() as $row) {

              $cek_so2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                                                   
                  INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                  INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  INNER JOIN ms_ugm ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_ugm.id_tipe_kendaraan
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$tgl_surat_1' AND tr_sales_order.id_dealer = '$id_dealer'
                  AND tr_spk.jenis_beli = 'Kredit' AND ms_ugm.segment = '$row->segment'")->row()->jum;
              if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                else $jumlah_jual = 0;
              $g_tot += $jumlah_jual;              
              echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";

            }
            ?>
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_tot ?></td>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $g_kon ?> %</td>            
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
        $("#showReport").attr("src",'<?php echo site_url("dealer/kontribusi_finance_company?") ?>cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan);
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