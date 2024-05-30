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
 
    <?php 
    if($set=="view"){
    ?>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="start_date" value="<?= date('Y-m-d') ?>" id="start_date">
                  </div>  
                  <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="end_date" value="<?= date('Y-m-d') ?>" id="end_date">
                  </div>                                     
                </div>             
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE h2=1 AND active = 1 ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
                  </div>
          <!--         <div class="col-sm-2">
                    <button type="button" onclick="getReport(this)" name="process" value="print" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-print"></i> Preview</button>
                  </div>  -->    
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport(this)" name="process" value="download" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>
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
function getReport(el)
{
  var el_val     = $(el).val();
  var start_date = document.getElementById("start_date").value;
  var end_date   = document.getElementById("end_date").value;
  var id_dealer  = document.getElementById("id_dealer").value;
  if (start_date=='' || end_date=='' || id_dealer=='') {
    alert('Silahkan lengkapi isian terlebih dahulu !');
    return false;
  }else{
    var value={start_date:start_date,
            end_date:end_date,
            id_dealer:id_dealer,
            cetak:'cetak',
            mode:el_val
            }
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("h2/laporan_data_kpb?") ?>mode='+value.mode+'&cetak='+value.cetak+'&start_date='+value.start_date+'&end_date='+value.end_date+'&id_dealer='+value.id_dealer);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
</script>

  </section>
</div>

    <?php }elseif ($set=='cetak') {
if ($mode=='download') {
//   header("Content-type: application/octet-stream");
// header("Content-Disposition: attachment; filename=Monitoring Penjualan Dealer Daily.xls");
// header("Pragma: no-cache");
// header("Expires: 0");
}
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
      <?php 
      $ci = &get_instance();
      $ci->load->model("m_h2"); 
      if ($id_dealer!='all'): 
        $dealer = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
      ?>
        <div style="text-align: left;font-size: 11pt">Nama AHASS : <?= $dealer->nama_dealer ?></div>
        <div style="text-align: left;font-size: 11pt">Kode AHASS : <?= $dealer->kode_dealer_md ?></div>
        <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>        
        <div style="text-align: center; font-weight: bold;">Tanggal : <?php echo date('d/m/Y',strtotime($start_date)) ?> s/d <?= date('d/m/Y',strtotime($end_date)) ?></div>
        <table class="table table-bordered" border="1">
          <tr>
            <td rowspan="2">KPB</td>
            <td rowspan="2">Jasa</td>
            <td rowspan="2">Keunt. Oli</td>
            <td rowspan="2">Oli</td>
            <td colspan="">TAGIHAN KE AHM</td>
            <td>PEMBAYARAN DARI AHM KE MD</td>
            <td>AHASS</td>
            <td>MD</td>
          </tr>
          <tr>
            <td>Jasa</td>
            <td>Oli</td>
            <td>PPN</td>
            <td>Total</td>
            <td>PPH</td>
            <td>Jasa</td>
            <td>Oli</td>
            <td>PPN</td>
            <td>Total</td>
            <td></td>
          </tr>
        </table>
      <?php endif ?>
      <?php if ($id_dealer=='all'):
        $dealer = $ci->m_h2->laporan_data_kpb_nosin();
      ?>
        <?php foreach ($dealer->result() as $dl): ?>
          <div style="text-align: left;font-size: 11pt">Nama AHASS : <?= $dl->nama_dealer ?></div>
          <div style="text-align: left;font-size: 11pt">Kode AHASS : <?= $dl->kode_dealer_md ?></div>
          <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>        
          <div style="text-align: center; font-weight: bold;">Tanggal : <?php echo date('d/m/Y',strtotime($start_date)) ?> s/d <?= date('d/m/Y',strtotime($end_date)) ?></div>
         <table border="1">
           <?php 
            $no_mesin_5 = $ci->m_h2->laporan_data_kpb_nosin($dl->id_dealer,'ya');
            foreach ($no_mesin_5->result() as $dt5) { ?>
              <tr>
                <td colspan="24" align="center"><?= $dt5->no_mesin_5 ?></td>
              </tr>
              <tr style="text-align: center;">
                  <td rowspan="2" colspan="2">KPB</td>
                  <td rowspan="2">Jasa</td>
                  <td rowspan="2">Keunt. Oli</td>
                  <td rowspan="2">Oli</td>
                  <td colspan="4">TAGIHAN KE AHM</td>
                  <td colspan="5">PEMBAYARAN DARI AHM KE MD</td>
                  <td colspan="6">AHASS</td>
                  <td colspan="4">MD</td>
                </tr>
                <tr style="text-align: center;">
                  <td>Jasa</td>  
                  <td>Oli</td> 
                  <td>PPN</td> 
                  <td>Total</td>
                  <td>PPH</td>
                  <td>Jasa</td>  
                  <td>Oli</td> 
                  <td>PPN</td> 
                  <td>Total</td>
                  <td>Jasa</td>  
                  <td>Insentif Oli</td>  
                  <td>Oli</td> 
                  <td>PPN</td> 
                  <td>PPH</td> 
                  <td>Sub Total</td>
                  <td>Oli</td> 
                  <td>PPN</td> 
                  <td>PPH</td> 
                  <td>Total</td>
                </tr>
              <?php 
                for ($i = 1; $i <=4; $i++) {
                  $dt_kpb = $ci->m_h2->laporan_data_kpb_nosin5_dealer($dt5->id_dealer,$i,$dt5->no_mesin_5);
                ?>
                <tr>
                  <td>KPB <?= getBulanRomawi($i) ?></td>
                  <td><?= $dt_kpb['jml_nosin'] ?></td>
                  <td align="right"><?= $dt_kpb['harga_jasa'] ?></td>
                  <td align="right"><?= $dt_kpb['keuntungan_oli'] ?></td>
                  <td align="right"><?= $dt_kpb['harga_oli'] ?></td>
                  <td align="right"><?= $dt_kpb['tagihan_jasa_ahm'] ?></td>
                  <td align="right"><?= $dt_kpb['tagihan_oli_ahm'] ?></td>
                  <td align="right"><?= $dt_kpb['tagihan_ppn_ahm'] ?></td>
                  <td align="right"><?= $dt_kpb['tagihan_total_ahm'] ?></td>
                  <td align="right"><?= $dt_kpb['bayar_pph_ahm_md'] ?></td>
                  <td align="right"><?= $dt_kpb['bayar_jasa_ahm_md'] ?></td>
                  <td align="right"><?= $dt_kpb['bayar_oli_ahm_md'] ?></td>
                  <td align="right"><?= $dt_kpb['bayar_ppn_ahm_md'] ?></td>
                  <td align="right"><?= $dt_kpb['bayar_total_ahm_md'] ?></td>
                  <td align="right"><?= $dt_kpb['jasa_ahass'] ?></td>
                  <td align="right"><?= $dt_kpb['insentif_oli_ahass'] ?></td>
                  <td align="right"><?= $dt_kpb['harga_oli_ahass'] ?></td>
                  <td align="right"><?= $dt_kpb['ppn_ahass'] ?></td>
                  <td align="right"><?= $dt_kpb['pph_ahass'] ?></td>
                  <td align="right"><?= $dt_kpb['subtotal_ahass'] ?></td>
                  <td align="right"><?= $dt_kpb['harga_oli_md'] ?></td>
                  <td align="right"><?= $dt_kpb['ppn_md'] ?></td>
                  <td align="right"><?= $dt_kpb['pph_md'] ?></td>
                  <td align="right"><?= $dt_kpb['total_md'] ?></td>
                </tr>
                <?php }  
              } ?>
         </table>
        <?php endforeach ?>
      <?php endif ?>
    </body>
  </html>
  <?php } ?>