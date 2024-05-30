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
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir *</label>
                  <div class="col-sm-3">
                    <input type="text" id="tgl_akhir"  class="form-control datepicker" placeholder="Tanggal Akhir" autocomplete="off">
                  </div>                  
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="">-choose-</option>
                      <option value="all">All Dealers</option>
                      <?php 
                      $sql_dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 ORDER BY ms_dealer.id_dealer ASC");
                      foreach ($sql_dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md - $isi->nama_dealer</option>";
                      }
                       ?>
                    </select>
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
            id_dealer:document.getElementById("id_dealer").value,            
            cetak:'cetak',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
            }

  if (value.tgl_awal == '' && value.tgl_akhir == ''&& value.id_dealer == '') {
    alert('Isi data dengan lengkap ..!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("h1/$page?") ?>cetak='+value.cetak+'&tgl_awal='+value.tgl_awal+'&tgl_akhir='+value.tgl_akhir+'&id_dealer='+value.id_dealer);
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
      <?php if($tgl_awal != ''){ 
        $dealer = $this->db->get_where('ms_dealer',['id_dealer'=>$id_dealer])->row();
      ?>
        <div style="text-align: left;font-size: 12pt"><?= $dealer->nama_dealer ?></div>
        <div style="text-align: left;font-size: 12pt"><?= $dealer->alamat ?></div>        
        <div style="text-align: left;font-size: 12pt"><?= $dealer->no_telp ?></div>        
        <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>      
        <table border="0" width="100%">
          <tr>
            <td>Periode : <?php echo $tgl_awal ?> s/d <?php echo $tgl_akhir ?></td>                     
          </tr>          
        </table>
        <?php 
        $sql_2 = $this->db->query("SELECT tr_sales_order.*,tr_spk.*,
          CASE WHEN finco.finance_company IS NULL THEN 'CASH' ELSE finco.finance_company END AS via,deskripsi_ahm
         FROM tr_sales_order 
          INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
          INNER JOIN ms_tipe_kendaraan AS mtk ON tr_spk.id_tipe_kendaraan=mtk.id_tipe_kendaraan
          LEFT JOIN ms_finance_company AS finco ON tr_spk.id_finance_company=finco.id_finance_company
              WHERE LEFT(tr_sales_order.tgl_bastk,10) BETWEEN '$tgl_awal' AND '$tgl_akhir'
              AND tr_sales_order.id_dealer = '$id_dealer'
            ORDER BY tgl_cetak_invoice ASC
            "); ?>
  <table class='table table-bordered' style='font-size: 8pt' width='100%'>
            <tr>                
              <td bgcolor='yellow' class='bold text-center' width='3%'>NO</td>
              <td bgcolor='yellow' class='bold text-center'>NO SRT JALAN</td>                            
              <td bgcolor='yellow' class='bold text-center'>TGL. SJ</td>                            
              <td bgcolor='yellow' class='bold text-center'>NO. INV</td>                            
              <td bgcolor='yellow' class='bold text-center'>TGL. INV</td>                            
              <td bgcolor='yellow' class='bold text-center'>KODE</td>                            
              <td bgcolor='yellow' class='bold text-center'>KETERANGAN</td>                            
              <td bgcolor='yellow' class='bold text-center'>NAMA KONSUMEN</td>                            
              <td bgcolor='yellow' class='bold text-center'>ALAMAT</td>                            
              <td bgcolor='yellow' class='bold text-center'>NO RANGKA</td>                            
              <td bgcolor='yellow' class='bold text-center'>NO MESIN</td>                            
              <td bgcolor='yellow' class='bold text-center'>VIA</td>                            
            </tr>
  <?php
      $no=1;
      $id_user = $this->session->userdata('id_user');
      $user = $this->db->query("SELECT username FROM ms_user
              -- JOIN ms_karyawan ON ms_user.id_karyawan=ms_karyawan.id_karyawan
               WHERE id_user='$id_user'
              ");
      $user = $user->num_rows()>0?$user->row()->username:'';
        foreach ($sql_2->result() as $rs) {        
        ?>
            <tr>
            <?php 
              echo "
                <tr>
                  <td align='center'>$no</td>
                  <td>$rs->no_bastk</td>
                  <td>".date('d/m/Y',strtotime($rs->tgl_pengiriman))."</td>
                  <td>$rs->no_invoice</td>
                  <td>".date('d/m/Y',strtotime($rs->tgl_cetak_invoice))."</td>
                  <td>$rs->id_tipe_kendaraan</td>
                  <td>$rs->deskripsi_ahm</td>
                  <td>$rs->nama_konsumen</td>
                  <td>$rs->alamat</td>
                  <td>$rs->no_rangka</td>
                  <td>$rs->no_mesin</td>
                  <td>$rs->via</td>
                </tr>
              ";
              $no++;
            ?>
            </tr>
          
      <?php } ?>
      </table> <br>
      <?php 

       ?>
      Dicetak :  <?= $user.' '.tgl_indo(date('Y-m-d')) ?> <?= gmdate("H:i:s", time()+60*60*7) ?>
          <?php }else{ ?>
        <p>Data tidak ditemukan.</p>
      <?php } ?>                
    </body>
  </html>
  <?php } ?>
  </section>
</div>