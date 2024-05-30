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
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Awal *</label>
                  <div class="col-sm-3">
                    <input type="text" id="tanggal2" name="spk_awal" class="form-control" placeholder="Tanggal Awal" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir *</label>
                  <div class="col-sm-3">
                    <input type="text" id="tanggal3" name="spk_akhir" class="form-control" placeholder="Tanggal Akhir" autocomplete="off">
                  </div>                  
                
                
                  <div class="col-sm-2">
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
            sheet-size: 330mm 210mm;
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
      <?php if($tanggal2 != ''){ ?>
        <table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Harian Penerimaan Sepeda Motor Per Type Warna</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        <table border="0" width="100%">
          <tr>
            <td>Periode : <?php echo $tanggal2 ?> s/d <?php echo $tanggal3 ?></td>                     
          </tr>          
        </table>
        <br>
        <table class='table table-bordered' style='font-size: 8pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' width='3%'>No</td>
            <td bgcolor='yellow' class='bold text-center'>No.SPG</td>
            <td bgcolor='yellow' class='bold text-center'>Tgl.SPG</td>                
            <td bgcolor='yellow' class='bold text-center'>No.Reff</td>                
            <td bgcolor='yellow' class='bold text-center'>Lokasi</td>                
            <td bgcolor='yellow' class='bold text-center'>Kode TypeClr</td>                
            <td bgcolor='yellow' class='bold text-center'>Ket.Type</td>                
            <td bgcolor='yellow' class='bold text-center'>Warna</td>                
            <td bgcolor='yellow' class='bold text-center'>Jumlah</td>                            
          </tr>
          <tr>
          <?php 
          $no=1;
          
          $sql = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin,tr_penerimaan_unit_dealer.tgl_penerimaan AS tgl, COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer 
              ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
              LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
              LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
              WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' AND tr_penerimaan_unit_dealer.tgl_penerimaan BETWEEN '$tanggal2' AND '$tanggal3'
              GROUP BY tr_scan_barcode.id_item");
          foreach ($sql->result() as $row) {
                        
            echo "
              <tr>
                <td align='center'>$no</td>
                <td>$row->id_penerimaan_unit_dealer</td>
                <td>$row->tgl</td>
                <td>$row->no_surat_jalan</td>
                <td>$row->id_gudang_dealer</td>                
                <td>$row->id_item</td>
                <td>$row->tipe_ahm</td>
                <td>$row->warna</td>
                <td>$row->jum</td>                
              </tr>
            ";
            $no++;
          }
          ?>
          </tr>
        </table> <br>
      <?php }else{ ?>
        <p>Tanggal SPK Harus ditentukan dulu.</p>
      <?php } ?>                
    </body>
  </html>
  <?php } ?>
  </section>
</div>


<script>
function getReport(){
  var value={tanggal2:document.getElementById("tanggal2").value,
            tanggal3:document.getElementById("tanggal3").value,            
            cetak:'cetak',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
            }

  if (value.tanggal2 == '' && value.tanggal3 == '') {
    alert('Isi data dengan lengkap ..!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("dealer/rekap_penerimaan_warna?") ?>cetak='+value.cetak+'&tanggal2='+value.tanggal2+'&tanggal3='+value.tanggal3);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
</script>