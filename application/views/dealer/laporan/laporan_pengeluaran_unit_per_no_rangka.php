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
                    <input type="text" id="tgl_awal" class="form-control datepicker" placeholder="Tanggal Awal" autocomplete="off">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir *</label>
                  <div class="col-sm-3">
                    <input type="text" id="tgl_akhir"  class="form-control datepicker" placeholder="Tanggal Akhir" autocomplete="off">
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

<script>
function getReport(){
  var value={tgl_awal:document.getElementById("tgl_awal").value,
            tgl_akhir:document.getElementById("tgl_akhir").value,            
            cetak:'cetak',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
            }

  if (value.tgl_awal == '' && value.tgl_akhir == '') {
    alert('Isi data dengan lengkap ..!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("dealer/$isi?") ?>cetak='+value.cetak+'&tgl_awal='+value.tgl_awal+'&tgl_akhir='+value.tgl_akhir);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
</script>

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
      <?php if($tgl_awal != ''){ ?>
        
        <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        <table border="0" width="100%">
          <tr>
            <td>Periode Surat : <?php echo $tgl_awal ?> s/d <?php echo $tgl_akhir ?></td>                     
          </tr>          
        </table>
        <?php 
        $sql_2 = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) BETWEEN '$tgl_awal' AND '$tgl_akhir'
              AND tr_spk.jenis_beli = 'Kredit' AND tr_sales_order.id_dealer = '$id_dealer'
              GROUP BY tr_spk.id_finance_company");
        foreach ($sql_2->result() as $amb) {          
        ?>
          <br>
          Nama Leasing Comp : <?php echo $amb->finance_company ?>
          <table class='table table-bordered' style='font-size: 8pt' width='100%'>
            <tr>                
              <td bgcolor='yellow' class='bold text-center' width='3%'>No</td>
              <td bgcolor='yellow' class='bold text-center'>Tgl.Inv</td>                
              <td bgcolor='yellow' class='bold text-center'>No.Inv</td>
              <td bgcolor='yellow' class='bold text-center'>No PO</td>                
              <td bgcolor='yellow' class='bold text-center'>Nama</td>                
              <td bgcolor='yellow' class='bold text-center'>Alamat</td>                
              <td bgcolor='yellow' class='bold text-center'>No.Rangka</td>                            
              <td bgcolor='yellow' class='bold text-center'>No.Mesin</td>                            
              <td bgcolor='yellow' class='bold text-center'>Type Warna</td>                            
            </tr>
            <tr>
            <?php 
            $no=1;
            
            $sql = $this->db->query("SELECT *, tr_scan_barcode.no_mesin AS nosin,tr_spk.alamat AS alam FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              WHERE LEFT(tr_sales_order.tgl_create_ssu,7) BETWEEN '$tgl_awal' AND '$tgl_akhir' AND tr_sales_order.id_dealer = '$id_dealer'
              AND tr_spk.jenis_beli = 'Kredit' AND tr_spk.id_finance_company = '$amb->id_finance_company'");
            foreach ($sql->result() as $row) {
                          
              echo "
                <tr>
                  <td align='center'>$no</td>
                  <td>".substr($row->tgl_cetak_invoice2,0,10)."</td>
                  <td>$row->no_invoice</td>
                  <td>$row->no_po_leasing</td>
                  <td>$row->nama_konsumen</td>                
                  <td>$row->alam</td>
                  <td>$row->no_rangka</td>
                  <td>$row->nosin</td>                
                  <td>$row->id_item</td>                
                </tr>
              ";
              $no++;
            }
            ?>
            </tr>
          </table> <br>
      <?php } 
          }else{ ?>
        <p>Data tidak ditemukan.</p>
      <?php } ?>                
    </body>
  </html>
  <?php } ?>
  </section>
</div>