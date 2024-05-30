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
            <form class="form-horizontal" action="h1/monitor_kekurangan_bbn_md/export_xls" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">                                                                                    
                  <label for="inputEmail3" class="col-sm-1 control-label"></label>                  
                  <div class="col-sm-4">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  
                    <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download Excel</button>                                  
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
        <div style="text-align: center;font-size: 13pt"><b>Monitor Kekurangan BBN</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        
        <table class='table table-bordered' style='font-size: 9pt' width='100%'>
          <tr>                
            <td bgcolor='yellow' class='bold text-center' width='25%'>No Mesin</td>
            <td bgcolor='yellow' class='bold text-center' width='25%'>No Rangka</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Item Kendaraan</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Kode Dealer</td>            
            <td bgcolor='yellow' class='bold text-center' >Nama Dealer</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Nama Customer</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>No BASTD (D - MD)</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Created BASTD (D - MD)</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Tanggal SSU</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Jam SSU</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>Tanggal Hari ini</td>            
            <td bgcolor='yellow' class='bold text-center' width='25%'>GAP</td>            
          </tr>          
          <?php   
          /*
          $sql = $this->db->query("SELECT * FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
            
            AND tr_sales_order.no_mesin NOT IN (SELECT no_mesin FROM tr_pengajuan_bbn_detail)");
          */
          $tgl_thn = date('Y-01-01');
          $sql = $this->db->query("
            SELECT tr_sales_order.no_mesin, tr_sales_order.tgl_create_ssu, tr_sales_order.id_dealer, tr_sales_order.no_rangka, tr_scan_barcode.id_item, tr_spk.nama_konsumen, tr_faktur_stnk.no_bastd, tr_faktur_stnk.created_at
            FROM tr_sales_order 
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin 
            INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
            left join tr_faktur_stnk_detail on tr_sales_order.id_sales_order  = tr_faktur_stnk_detail.id_sales_order 
            left join tr_faktur_stnk on tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd 
            where tr_sales_order.tgl_create_ssu >= '$tgl_thn' and (tr_faktur_stnk_detail.no_bastd is null or tr_faktur_stnk.status_faktur not in ('approved','rejected'))
          ");
          
          foreach ($sql->result() as $isi) {

            /*
            $this->db->select('a.no_bastd, a.created_at');
            $this->db->from('tr_faktur_stnk a');
            $this->db->join('tr_faktur_stnk_detail b', 'a.no_bastd = b.no_bastd', 'inner');
            $this->db->where('b.id_sales_order', $isi->id_sales_order);
            $bastd = $this->db->get()->row();
            */

            $tgl_ssu = substr($isi->tgl_create_ssu,0,10);
            $jam_ssu = substr($isi->tgl_create_ssu,11);
            $tgl_today = date('Y-m-d');
            $tgl1 = new DateTime($tgl_ssu);
            $tgl2 = new DateTime($tgl_today);
            $gap = $tgl2->diff($tgl1)->days;

            $kode_dealer = get_data('ms_dealer','id_dealer',$isi->id_dealer,'kode_dealer_md');
            $nama_dealer = get_data('ms_dealer','id_dealer',$isi->id_dealer,'nama_dealer');

            echo "
            <tr>
              <td>$isi->no_mesin</td>
              <td>$isi->no_rangka</td>
              <td>$isi->id_item</td>
              <td>$kode_dealer</td>
              <td>$nama_dealer</td>
              <td>$isi->nama_konsumen</td>
              <td>$isi->no_bastd</td>
              <td>$isi->created_at</td>
              <td>$tgl_ssu</td>
              <td>$jam_ssu</td>
              <td>$tgl_today</td>
              <td>$gap</td>
            </tr>
            ";
          }
          
          ?> 

          <?php   
          $sql2 = $this->db->query("
          SELECT tr_scan_barcode.no_mesin, tr_sales_order_gc.tgl_create_ssu, tr_sales_order_gc.id_dealer, tr_scan_barcode.no_rangka, tr_scan_barcode.id_item, tr_spk_gc.nama_npwp, tr_faktur_stnk.no_bastd, tr_faktur_stnk.created_at
          FROM tr_sales_order_gc_nosin 
          INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin 
          INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
          INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
          left join tr_faktur_stnk_detail on tr_sales_order_gc.id_sales_order_gc  = tr_faktur_stnk_detail.id_sales_order 
          left join tr_faktur_stnk on tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd 
          where tr_sales_order_gc.tgl_create_ssu >= '$tgl_thn' and (tr_faktur_stnk_detail.no_bastd is null or tr_faktur_stnk.status_faktur not in ('approved','rejected'))
        ");

        
          /*
          $sql2 = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin 
            INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
            INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
            
            AND tr_sales_order_gc_nosin.no_mesin NOT IN (SELECT no_mesin FROM tr_pengajuan_bbn_detail)");
            */

          foreach ($sql2->result() as $isi) {

            /*
            $this->db->select('a.no_bastd, a.created_at');
            $this->db->from('tr_faktur_stnk a');
            $this->db->join('tr_faktur_stnk_detail b', 'a.no_bastd = b.no_bastd', 'inner');
            $this->db->where('b.id_sales_order', $isi->id_sales_order_gc);
            $bastd = $this->db->get()->row();
            */

            $tgl_ssu = substr($isi->tgl_create_ssu,0,10);
            $jam_ssu = substr($isi->tgl_create_ssu,11);
            $tgl_today = date('Y-m-d');
            $tgl1 = new DateTime($tgl_ssu);
            $tgl2 = new DateTime($tgl_today);
            $gap = $tgl2->diff($tgl1)->days;

            $kode_dealer = get_data('ms_dealer','id_dealer',$isi->id_dealer,'kode_dealer_md');
            $nama_dealer = get_data('ms_dealer','id_dealer',$isi->id_dealer,'nama_dealer');

            echo "
            <tr>
              <td>$isi->no_mesin</td>
              <td>$isi->no_rangka</td>
              <td>$isi->id_item</td>
              <td>$kode_dealer</td>
              <td>$nama_dealer</td>
              <td>$isi->nama_npwp</td>
              <td>$isi->no_bastd</td>
              <td>$isi->created_at</td>
              <td>$tgl_ssu</td>
              <td>$jam_ssu</td>
              <td>$tgl_today</td>
              <td>$gap</td>
            </tr>
            ";
          }
          
          ?>          
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
      var value={cetak:'cetak',}

      if (value.cetak == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/monitor_kekurangan_bbn_md?") ?>cetak='+value.cetak);
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