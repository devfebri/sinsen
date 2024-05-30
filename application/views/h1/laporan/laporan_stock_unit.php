<?php 
function bln(){
  $bulan=$bl=$month=date("m");
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
            <form class="form-horizontal" action="h1/ssu/create" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  
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
      <div style="text-align: center;font-size: 13pt"><b>Laporan Stock Unit</b></div>
      
      <hr>      
      <?php 
      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY kode_dealer_md ASC");
      foreach ($sql_dealer->result() as $isi) {
            $sql_stok = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,tr_scan_barcode.* FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer
                ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
                INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = tr_scan_barcode.tipe_motor
                WHERE tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' AND (tr_scan_barcode.status = 4 OR tr_scan_barcode.status = 5)
                GROUP BY ms_tipe_kendaraan.id_tipe_kendaraan");                        
            if($sql_stok->num_rows() > 0){
            echo "Dealer : $isi->kode_dealer_md - $isi->nama_dealer"; ?>
            <table class='table table-bordered' style='font-size: 9pt' width='100%'>
              <tr>
                <td class='bold text-center' width='5%'>No</td>
                <td class='bold text-center' width='40%'>Tipe Motor</td>
                <td class='bold text-center' width='20%'>Tgl Dist (Terakhir)</td>
                <td class='bold text-center' width='20%'>Tgl Jual (Terakhir)</td>
                <td class='bold text-center' width='15%'>Stock O.H</td>          
            </tr>
            <?php 
            $no=1;
            foreach ($sql_stok->result() as $amb) {
              $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.tipe_motor = '$amb->tipe_motor' AND tr_penerimaan_unit_dealer.id_dealer = '$isi->id_dealer' 
                AND tr_scan_barcode.status = '4'")->row();                
              $cek_ssu = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                WHERE tr_sales_order.id_dealer = '$isi->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' 
                ORDER BY tgl_create_ssu DESC LIMIT 0,1");
              if($cek_ssu->num_rows() > 0){
                $tgl_create_ssu = $cek_ssu->row()->tgl_create_ssu;
                if(isset($tgl_create_ssu)){
                  $tgl_jual = date("d F Y", strtotime($tgl_create_ssu));    
                }else{
                  $tgl_jual = "";
                }
              }else{
                $tgl_jual = "";
              }

              $cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin 
                WHERE tr_surat_jalan.id_dealer = '$isi->id_dealer' AND tr_scan_barcode.tipe_motor = '$amb->tipe_motor' 
                ORDER BY tgl_surat DESC LIMIT 0,1");
              if($cek_sj->num_rows() > 0){
                $tgl_surat1 = $cek_sj->row()->tgl_surat;
                if(isset($tgl_surat1)){
                  $tgl_surat = date("d F Y", strtotime($tgl_surat1));    
                }else{
                  $tgl_surat = "";
                }
              }else{
                $tgl_surat = "";
              }
              echo "
              <tr>
                <td>$no</td>
                <td>$amb->tipe_motor - $amb->tipe_ahm</td>
                <td>$tgl_surat</td>
                <td>$tgl_jual</td>
                <td>$cek_qty->jum</td>            
              </tr>";              
              $no++;
            }
          }
          ?>
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
      var value={id:'1',                 
                cetak:'cetak',
                }
      if (value.id == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/laporan_stock_unit?") ?>id='+value.id+'&cetak='+value.cetak);
        document.getElementById("showReport").onload = function(e){          
        $('.loader').hide();
        //$('#btnShow').removeAttr('disabled');
        };
      }
    }
</script>