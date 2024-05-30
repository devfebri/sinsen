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
<?php 
if($download != 'ya'){
?>
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
<?php } ?>
  <section class="content">
    
    <?php 
    if($set=="view"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">              
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <div class="col-sm-6">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  
                    <button type="button" onclick="getReport2()" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download Stok Acc EV</button>                                  
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

    
    <?php }elseif ($set=='cetak') { 
      if($download == 'ya'){
        $no = date('his');
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=Rekap_stock_acc_ev_".$no.".xls");
        header("Pragma: no-cache");
        header("Expires: 0"); 
      }else{
      ?>
        <!DOCTYPE html>
        <html>
        <!-- <html lang="ar"> for arabic only -->
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Cetak</title>
          <style>
            @media print {
              @page {
                sheet-size: 290mm 210mm;
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
      <?php } ?>  
      <body>
        <table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Stock Acc EV</b></div>        
        <div style="text-align: center; font-weight: bold;">Periode : <?php echo date("d F Y"); ?></div>
        <hr>

        <table class='table table-bordered' style='font-size: 10pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' width='5%'>No</td>
            <td bgcolor='yellow' class='bold text-center'>Kode Dealer</td>                
            <td bgcolor='yellow' class='bold text-center'>Dealer</td>                
            <td bgcolor='yellow' class='bold text-center'>Kode Part</td>                 
            <td bgcolor='yellow' class='bold text-center'>Deskripsi Part</td>                
            <td bgcolor='yellow' class='bold text-center'>Serial Number</td>              
            <td bgcolor='yellow' class='bold text-center'>Tgl Surat Jalan MD</td>             
            <td bgcolor='yellow' class='bold text-center'>Tgl Penerimaan</td>              
            <td bgcolor='yellow' class='bold text-center'>Status</td>                
          </tr>
          <?php 

          $get_stok = $this->db->query("
          select a.part_id , a.part_desc, a.serial_number , a.no_shipping_list , a.tgl_shipping_list , a.status_scan , 
          b.tanggal_terima_md as mdReceiveDate, c.kode_dealer_md as dealerCode , b.tgl_surat_jalan mdSLDate, b.tanggal_terima_dealer as dealerReceiveDate,
          c.nama_dealer, c.kode_dealer_md  
          from tr_shipping_list_ev_accoem a 
          join tr_stock_battery b on a.serial_number  =b.serial_number 
          left join ms_dealer c on b.id_dealer = c.id_dealer
          where b.acc_status <5 and b.id_dealer = $id_dealer
          ");

          $no = 1;
          if($get_stok->num_rows() >0){
            foreach($get_stok->result() as $row) {
              $dealer = '';
              if($row->status_scan == 1){
                if($row->mdSLDate !='' && $row->dealerReceiveDate !=''){
                  $dealer = "'".$row->dealerCode;
                  $status = 'Stok Dealer';
                }else if($row->mdSLDate !='' && $row->dealerReceiveDate ==''){
                  $dealer = "'".$row->dealerCode;
                  $status = 'Intransit Dealer';
                }else if($row->dealerCode !='' && $row->mdSLDate ==''){
                  $dealer = "'".$row->dealerCode;
                  $status = 'Unfill Dealer';
                }else{
                  $status = 'Stok MD';
                }
              }else{
                $status = 'Intransit AHM';
              } 
              echo "<tr>
                <td>$no</td>
                <td>$row->kode_dealer_md</td>
                <td>$row->nama_dealer</td>
                <td>$row->part_id</td>
                <td>$row->part_desc</td>
                <td>'$row->serial_number</td>
                <td>$row->mdSLDate</td>
                <td>$row->dealerReceiveDate</td>
                <td>$status</td>
                </tr>	
              ";
            }
          }          
          ?>          
        </table>              
      </body>
    <?php } ?>
  </html>
  </section>
</div>

<script>
function getReport(){
    var value={
      cetak:'cetak'
    }

    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("dealer/rekap_stock_acc_ev?") ?>cetak='+value.cetak);
    document.getElementById("showReport").onload = function(e){          
      $('.loader').hide();       
    };
}

function getReport2(){
  var value={
    cetak:'cetak',
    download:'ya'
  }

  $('.loader').show();
  $('#btnShow').disabled;
  $("#showReport").attr("src",'<?php echo site_url("dealer/rekap_stock_acc_ev?") ?>cetak='+value.cetak+'&download='+value.download);
  document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
  };
}
</script>