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
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_dealer" class="minimal" checked> Per Dealer
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_kab" class="minimal"> Per Kabupaten
                  </div>
                  <div class="col-sm-2">                    
                    <input type="radio" name="tipe" id="tipe" value="per_bulan" class="minimal"> Per Bulanan
                  </div>                      
                </div>                                                                                                      
                <div class="form-group">
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
      <?php if($tipe == 'per_dealer'){ $tgl = bln($bulan)." ".$tahun; ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Distribusi Unit</b></div>        
        <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div>
        <hr>      
        <?php 
        $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.active = 1
                  ORDER BY ms_dealer.id_dealer ASC");
        foreach ($sql_dealer->result() as $isi) {
              $sql_stok = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan
                  ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");                        
              if($sql_stok->num_rows() > 0){
              echo "Dealer : $isi->kode_dealer_md - $isi->nama_dealer"; ?>
              <table class='table table-bordered' style='font-size: 9pt' width='100%'>
                <tr>                
                  <td bgcolor='yellow' class='bold text-center' width='25%' rowspan="2">Tipe Motor</td>
                  <td bgcolor='yellow' class='bold text-center' colspan="31">Distribusi Unit Per Tanggal</td>
                  <td bgcolor='yellow' class='bold text-center' width='10%' rowspan="2">Total</td>                
                </tr>
                <tr>
                  <?php for ($i=1; $i <= 31; $i++) { 
                    $i = sprintf("%'.02d",$i);    
                    echo "<td align='center'>$i</td>";
                  } ?>
                </tr>
              <?php 
              $no=1;$g_total=0;
              foreach ($sql_stok->result() as $amb) {                            
                echo "
                <tr>                
                  <td>$amb->tipe_ahm</td>";
                  $total=0;
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat = $tahun."-".$bln."-".$tgl;
                    $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                      INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                      WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' 
                      AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' AND tr_surat_jalan.tgl_surat = '$tgl_surat'")->row()->jum;
                    if(isset($cek_sj) AND $cek_sj != 0){
                      $jumlah = $cek_sj;
                    }else{
                      $jumlah = "-";
                    }
                    $total = $total + $jumlah;                    
                    echo "<td width='5%' align='center'>$jumlah</td>";
                  }
                  echo "<td class='bold text-center'>$total</td>
                </tr>";                            
              }
              echo "<tfoot>
                      <tr>
                        <td class='bold text-center' bgcolor='yellow'>Total</td>";
                        for ($i=1; $i <= 31; $i++) { 
                          $bln = sprintf("%'.02d",$bulan);    
                          $tgl_surat = $tahun."-".$bln."-".$i;
                          $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                            INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                            WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' 
                            AND tr_surat_jalan.tgl_surat = '$tgl_surat'")->row()->jum;
                          if(isset($cek_sj) AND $cek_sj != 0){
                            $jumlah = $cek_sj;
                          }else{
                            $jumlah = "-";
                          }
                          $g_total = $g_total + $jumlah;                    
                          echo "<td class='bold text-center' bgcolor='yellow' align='center'>$jumlah</td>";
                        }
                      echo "<td class='bold text-center' bgcolor='yellow'>$g_total</td>
                      </tr>
                    </tfoot>";
            }
            ?>
            </table> <br>
        <?php
        }
      }elseif($tipe=='per_kab'){ $tgl = bln($bulan)." ".$tahun; ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Distribusi Unit</b></div>        
        <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div>
        <hr>      
        <?php 
        $sql_kab = $this->db->query("SELECT * FROM ms_kabupaten WHERE ms_kabupaten.id_provinsi = 1500
                  ORDER BY ms_kabupaten.Kabupaten ASC");
        foreach ($sql_kab->result() as $isi) {
              $sql_stok = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan
                  ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
                  LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                  LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                  LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                  LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                  LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                  WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND (tr_scan_barcode.status = 3 OR tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                  GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");
              if($sql_stok->num_rows() > 0){
              echo "Kabupaten : $isi->id_kabupaten - $isi->kabupaten"; ?>
              <table class='table table-bordered' style='font-size: 9pt' width='100%'>
                <tr>                
                  <td bgcolor='yellow' class='bold text-center' width='25%' rowspan="2">Tipe Motor</td>
                  <td bgcolor='yellow' class='bold text-center' colspan="31">Distribusi Unit Per Tanggal</td>
                  <td bgcolor='yellow' class='bold text-center' width='10%' rowspan="2">Total</td>                
                </tr>
                <tr>
                  <?php for ($i=1; $i <= 31; $i++) { 
                    $i = sprintf("%'.02d",$i);    
                    echo "<td align='center'>$i</td>";
                  } ?>
                </tr>
              <?php 
              $no=1;$g_total=0;
              foreach ($sql_stok->result() as $amb) {                            
                echo "
                <tr>                
                  <td>$amb->tipe_ahm</td>";
                  $total=0;
                  for ($i=1; $i <= 31; $i++) { 
                    $bln = sprintf("%'.02d",$bulan);    
                    $tgl = sprintf("%'.02d",$i);    
                    $tgl_surat = $tahun."-".$bln."-".$tgl;
                    $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                      INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                      LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                      LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                      LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                      LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                      WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND tr_surat_jalan_detail.ceklist = 'ya' 
                      AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' AND tr_surat_jalan.tgl_surat = '$tgl_surat'")->row()->jum;
                    if(isset($cek_sj) AND $cek_sj != 0){
                      $jumlah = $cek_sj;
                    }else{
                      $jumlah = "-";
                    }
                    $total = $total + $jumlah;                    
                    echo "<td width='5%' align='center'>$jumlah</td>";
                  }
                  echo "<td class='bold text-center'>$total</td>
                </tr>";                            
              }
              echo "<tfoot>
                      <tr>
                        <td class='bold text-center' bgcolor='yellow'>Total</td>";
                        for ($i=1; $i <= 31; $i++) { 
                          $bln = sprintf("%'.02d",$bulan);    
                          $tgl_surat = $tahun."-".$bln."-".$i;
                          $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                            INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                            LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                            LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
                            LEFT JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
                            LEFT JOIN ms_kabupaten ON ms_kabupaten.id_kabupaten = ms_kecamatan.id_kabupaten
                            WHERE ms_kabupaten.id_kabupaten = '$isi->id_kabupaten' AND tr_surat_jalan_detail.ceklist = 'ya' 
                            AND tr_surat_jalan.tgl_surat = '$tgl_surat'")->row()->jum;
                          if(isset($cek_sj) AND $cek_sj != 0){
                            $jumlah = $cek_sj;
                          }else{
                            $jumlah = "-";
                          }
                          $g_total = $g_total + $jumlah;                    
                          echo "<td class='bold text-center' bgcolor='yellow' align='center'>$jumlah</td>";
                        }
                      echo "<td class='bold text-center' bgcolor='yellow'>$g_total</td>
                      </tr>
                    </tfoot>";
            }
            ?>
            </table> <br>
        <?php
        }
      }elseif($tipe=='per_bulan'){ ?>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Distribusi Unit</b></div>        
        <div style="text-align: center; font-weight: bold;">Tahun : <?php echo $tahun ?></div>
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>    
            <td bgcolor='yellow' class='bold text-center' width='5%'>No</td>
            <td bgcolor='yellow' class='bold text-center' width='25%'>Tipe Motor</td>
            <?php for ($i=1; $i <= 12; $i++) {         
              $isi = substr(bln($i),0,3);
              echo "<td class='bold text-center' bgcolor='yellow'>$isi</td>";
            } ?>                
            <td bgcolor='yellow' class='bold text-center' width='7%'>Total</td>                
          </tr>                
          <?php 
          $sql_kab = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
          $no=1;
          foreach ($sql_kab->result() as $isi) { 
            echo "
            <tr>
              <td align='center'>$no</td>
              <td>$isi->nama_dealer</td>";
              $t=0;
              for ($i=1; $i <= 12; $i++) {   
                $bln = sprintf("%'.02d",$i);                    
                $tgl_surat = $tahun."-".$bln;
                $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                  LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer                  
                  WHERE ms_dealer.id_dealer = '$isi->id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' 
                  AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_sj) AND $cek_sj != 0){
                  $jumlah = $cek_sj;
                }else{
                  $jumlah = "";
                }
                echo "<td align='center'>$jumlah</td>";
                $t += $jumlah;
              }
              echo "
              <td align='center'>$t</td>
            </tr>";
            $no++;
          } ?>
          <tfoot>
            <tr>
              <td bgcolor='yellow' class='bold text-center'></td>
              <td bgcolor='yellow' class='bold text-center'>Total Distribusi</td>
              <?php 
              $s=0;
              for ($i=1; $i <= 12; $i++) {         
                $bln = sprintf("%'.02d",$i);                    
                $tgl_surat = $tahun."-".$bln;
                $cek_sj = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                  INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                  LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer                  
                  WHERE tr_surat_jalan_detail.ceklist = 'ya' AND LEFT(tr_surat_jalan.tgl_surat,7) = '$tgl_surat'")->row()->jum;
                if(isset($cek_sj) AND $cek_sj != 0){
                  $jumlah = $cek_sj;
                }else{
                  $jumlah = "";
                }
                echo "<td bgcolor='yellow' class='bold text-center'>$jumlah</td>";
                $s += $jumlah;
              }
              ?>
              <td bgcolor='yellow' class='bold text-center'><?php echo $s ?></td>
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
                cetak:'cetak',
                }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/laporan_distribusi_unit?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan);
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