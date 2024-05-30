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
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan DO</label>
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun DO</label>
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
        <div style="text-align: center;font-size: 13pt"><b>Distribusi MD</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' width='25%' rowspan="2">Tipe Motor</td>
            <td bgcolor='yellow' class='bold text-center' width='25%' rowspan="2">Kode Item</td>
            <td bgcolor='yellow' class='bold text-center' colspan="31"><?php echo $tgl ?></td>
            <td bgcolor='yellow' class='bold text-center' width='10%' rowspan="2">Total</td>                
          </tr>
          <tr>            
            <?php 
            for ($i=1; $i <= 31; $i++) { 
              echo "<td bgcolor='yellow' class='bold text-center'>$i</td>";
            }
            ?>
            
          </tr>
          <?php   
          $gtotal=0;        
          $bln = sprintf("%'.02d",$bulan);  
          $tgl_sj = $tahun."-".$bln."-01";
          $sql = $this->db->query("
            SELECT * 
            FROM tr_surat_jalan_detail 
            INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
            INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
            WHERE tr_surat_jalan.id_dealer = '$id_dealer' and tr_surat_jalan.tgl_surat >='$tgl_sj '
            GROUP BY tr_surat_jalan_detail.id_item");
          foreach ($sql->result() as $isi) {
            echo "
            <tr>
              <td>$isi->tipe_ahm</td>
              <td>$isi->id_item</td>";
              $total=0;
              for ($i=1; $i <= 31; $i++) {                                                                    
                $tgl = sprintf("%'.02d",$i);                                                                     
                $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
                $cek_so2 = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.id_item) AS jum 
                  FROM tr_surat_jalan_detail 
                  INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                  
                  WHERE tr_surat_jalan.tgl_surat = '$tgl_surat_1' AND tr_surat_jalan.id_dealer = '$id_dealer'
                  AND tr_surat_jalan_detail.id_item = '$isi->id_item'")->row()->jum;
                if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                  else $jumlah_jual = 0;
                $total += $jumlah_jual;
                echo "<td align='center'>$jumlah_jual</td>";
              }
              echo "<td align='center'>$total</td>
            </tr>
            ";
          }
          ?>
          <tr>
            <td bgcolor='yellow' class='bold text-center' colspan="2">All Type</td>            
            <?php             
            
            for ($i=1; $i <= 31; $i++) {                                                                    
              $tgl = sprintf("%'.02d",$i);                                                                     
              $tgl_surat_1 = $tahun."-".$bln."-".$tgl;
              $cek_so2 = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.id_item) AS jum 
                FROM tr_surat_jalan_detail 
                INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                  
                WHERE tr_surat_jalan.tgl_surat = '$tgl_surat_1' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row()->jum;
              if(isset($cek_so2) AND $cek_so2 != 0) $jumlah_jual = $cek_so2;
                else $jumlah_jual = 0;
              $gtotal += $jumlah_jual;
              echo "<td bgcolor='yellow' class='bold text-center'>$jumlah_jual</td>";
            }          
            ?>            
            <td bgcolor='yellow' class='bold text-center'><?php echo $gtotal ?></td>            
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
        $("#showReport").attr("src",'<?php echo site_url("dealer/distribusi_md?") ?>cetak='+value.cetak+'&tahun='+value.tahun+'&bulan='+value.bulan);
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