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
function mata_uang3($a){

  // if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);

    // if(is_numeric($a) AND $a != 0 AND $a != ""){

    //   return number_format($a, 0, ',', '.');

    // }else{

    //   return $a;

    // } 
    return number_format($a, 0, ',', '.');       

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
                  <label for="inputEmail3" class="col-sm-1 control-label">Tanggal</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control datepicker" name="tanggal" value="<?= date('Y-m-d') ?>" id="tanggal">
                  </div>                                     
                </div>             
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 $filter_deler ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
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
      <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan Unit Harian</b></div>        
      <div style="text-align: center; font-weight: bold;">Tanggal : <?php echo date('d/m/Y',strtotime($tanggal)) ?></div>
      <?php 
          $filter_deler = '';
          if ($id_dealer!='all') {
            $filter_deler = "AND id_dealer='$id_dealer'";
          }
          $dealer = $this->db->query("SELECT * FROM ms_dealer 
                  WHERE ms_dealer.active = 1 $filter_deler
                  AND id_dealer IN(SELECT id_dealer FROM tr_invoice_dealer 
                JOIN  tr_do_po ON tr_invoice_dealer.no_do=tr_do_po.no_do
                WHERE status_invoice='printable')
                  ORDER BY ms_dealer.kode_dealer_md ASC"); 
          foreach ($dealer->result() as $dl) { ?>
            Dealer : <?= $dl->kode_dealer_md.' - '.$dl->nama_dealer ?> <br>
            <table style='font-size: 9pt' width='100%'>
              <tr>                
                <td bgcolor='yellow' class='bold'>No</td>
                <td bgcolor='yellow' class='bold'>No Faktur</td>
                <td bgcolor='yellow' class='bold'>No DO</td>
                <td bgcolor='yellow' class='bold'>Tipe Kendaraan</td>
                <td bgcolor='yellow' class='bold'>Type</td>
                <td bgcolor='yellow' class='bold'>Qty</td>
                <td bgcolor='yellow' class='bold'>DPP</td>
                <td bgcolor='yellow' class='bold'>Disc DF</td>
                <td bgcolor='yellow' class='bold'>Discount</td>
                <td bgcolor='yellow' class='bold'>DPP Net</td>
                <td bgcolor='yellow' class='bold'>PPN</td>
                <td bgcolor='yellow' class='bold'>Total</td>
              </tr>
              <?php 
                $invoice = $this->db->query("SELECT * FROM tr_invoice_dealer 
                JOIN  tr_do_po ON tr_invoice_dealer.no_do=tr_do_po.no_do
                WHERE id_dealer='$dl->id_dealer' AND status_invoice='printable'");
                $no=1;
                $dpp_dealer     = 0;
                $disc_dealer    =0;
                $dpp_net_dealer =0;
                $ppn_dealer     =0;
                $total_dealer   =0;
                $disc_df_dealer =0;
                $qty_dealer     =0;
                foreach ($invoice->result() as $inv) {
                  $bunga_bank = $inv->bunga_bank / 100;
                  $top = $inv->top_unit;
                  $do = $this->db->query("SELECT *,LEFT(tr_invoice_dealer_detail.id_item,6) AS item FROM tr_invoice_dealer_detail 
                    WHERE tr_invoice_dealer_detail.no_do='$inv->no_do' AND qty_do>0");
                  $dpp_inv     = 0;
                  $disc_inv    =0;
                  $dpp_net_inv =0;
                  $ppn_inv     =0;
                  $total_inv   =0;
                  $disc_df_inv =0;
                  $qty_inv     =0;
                  foreach ($do->result() as $do) { 
                    $item = $this->db->query("SELECT * FROM ms_item
                    JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                    JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna WHERE id_item='$do->item'")->row();
                    $do_detail = $this->db->query("SELECT * FROM tr_do_po_detail WHERE no_do='$do->no_do' AND id_item='$do->item'")->row();

                    $dpp     = $do_detail->harga*$do_detail->qty_do;
                    $disc     = (($do_detail->disc_scp+$do_detail->disc)*$do_detail->qty_do)+$do_detail->disc_tambahan;
                    $dpp_net = (($dpp-$disc)-($bunga_bank/360*$top))/(1+((getPPN(1.1,$inv->tgl_faktur)*$bunga_bank/360)*$top));
                    $ppn     = $dpp * getPPN(0.1,$inv->tgl_faktur);
                    $disc_df = ($dpp-$disc)-$dpp_net;
                    $total   = $dpp+$ppn;

                    $dpp_inv+=$dpp;
                    $disc_inv+=$disc;
                    $disc_df_inv+=$disc_df;
                    $dpp_net_inv+=$dpp_net;
                    $total_inv+= $total;
                    $qty_inv+=$do_detail->qty_do;
                  ?>
                    <tr>
                      <td><?= $no ?></td>
                      <td><?= $inv->no_faktur ?></td>
                      <td><?= $do->no_do ?></td>
                      <td><?= $item->id_item ?></td>
                      <td><?= strip_tags($item->deskripsi_ahm) ?></td>
                      <td align="right"><?= $do->qty_do ?></td>
                      <td align="right"><?= mata_uang3($dpp) ?></td>
                      <td align="right"><?= mata_uang3($disc_df) ?></td>
                      <td align="right"><?= mata_uang3($disc) ?></td>
                      <td align="right"><?= mata_uang3($dpp_net) ?></td>
                      <td align="right"><?= mata_uang3($ppn) ?></td>
                      <td align="right"><?= mata_uang3($total) ?></td>
                    </tr>
                  <?php $no++; 
                  } ?>
                  <tr>
                    <td colspan="5"><b>Total</b></td>
                    <td align="right"><?= mata_uang3($qty_inv) ?></td>
                    <td align="right"><?= mata_uang3($dpp_inv) ?></td>
                    <td align="right"><?= mata_uang3($disc_df_inv) ?></td>
                    <td align="right"><?= mata_uang3($disc_inv) ?></td>
                    <td align="right"><?= mata_uang3($dpp_net_inv) ?></td>
                    <td align="right"><?= mata_uang3($ppn_inv) ?></td>
                    <td align="right"><?= mata_uang3($total_inv) ?></td>
                  </tr> 
                <?php 
                   $dpp_dealer     +=$dpp_inv;
                   $disc_dealer    +=$disc_inv;
                   $dpp_net_dealer +=$dpp_net_inv;
                   $ppn_dealer     +=$ppn_inv;
                   $total_dealer   +=$total_inv;
                   $disc_df_dealer +=$disc_df_inv;
                   $qty_dealer     +=$qty_inv;
                  }
                ?>
                <tr>
                  <td colspan="5"><b>Sub Total</b></td>
                  <td align="right"><b><?= mata_uang3($qty_dealer) ?></b></td>
                  <td align="right"><b><?= mata_uang3($dpp_dealer) ?></b></td>
                  <td align="right"><b><?= mata_uang3($disc_df_dealer) ?></b></td>
                  <td align="right"><b><?= mata_uang3($disc_dealer) ?></b></td>
                  <td align="right"><b><?= mata_uang3($dpp_net_dealer) ?></b></td>
                  <td align="right"><b><?= mata_uang3($ppn_dealer) ?></b></td>
                  <td align="right"><b><?= mata_uang3($total_dealer) ?></b></td>
                </tr>
            </table>
            <br>
          <?php } ?>
          <table>
            <tr>
              <td>Grand Total</td>
              <td></td>
            </tr>
          </table>
    </body>
  </html>
  <?php } ?>

  </section>
</div>


<script>
    function getReport()
    {
      var value={tanggal:document.getElementById("tanggal").value,
                id_dealer:document.getElementById("id_dealer").value,
                cetak:'cetak',
                }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      }else{
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src",'<?php echo site_url("h1/laporan_penjualan_unit_harian?") ?>tipe='+value.tipe+'&cetak='+value.cetak+'&tanggal='+value.tanggal+'&bulan='+value.bulan+'&id_dealer='+value.id_dealer);
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