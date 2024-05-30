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
            <form class="form-horizontal" action="h1/ssu/create" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Type</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_tipe_kendaraan" id="id_tipe_kendaraan">
                      <option value="all">All</option>
                      <?php                       
                      $sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = '1'");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Warna</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_warna" id="id_warna">
                      <option value="all">All</option>
                      <?php                       
                      $sql = $this->db->query("SELECT * FROM ms_warna WHERE active = '1'");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->id_warna'>$isi->id_warna | $isi->warna</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang Dealer</label>
                  <div class="col-sm-3">
                    <select class="form-control" name="id_gudang" id="id_gudang">
                      <option value="all">All</option>
                      <?php 
                      $id_dealer = $this->m_admin->cari_dealer();
                      $sql = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer'");
                      foreach ($sql->result() as $isi) {
                        echo "<option value='$isi->gudang'>$isi->gudang</option>";
                      }
                      ?>
                    </select>
                  </div>
                  
                
                  <div class="col-sm-2">
                    <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>                                  
                    <button type="button" onclick="getReport2()" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download</button>                                  
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
        header("Content-Disposition: attachment; filename=Rekap_stock_frame_".$no.".xls");
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
      <?php if($id_gudang != ''){ ?>
        <table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <div style="text-align: center;font-size: 13pt"><b>Laporan Stock Per Lokasi Per No Rangka</b></div>        
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>
        <?php
        if($id_gudang == 'all'){
          $query = "";
        }else{
          $query = "AND gudang = '$id_gudang'";
        } 
        $sql_gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer' $query");
        foreach ($sql_gudang->result() as $ambil) {          
          ?>
          <table border="0" width="100%">
            <tr>
              <td>Lokasi : <?php echo $ambil->gudang ?></td>                                 
              <!-- <td>Type Warna : 
                <?php 
                if($id_tipe_kendaraan != 'all'){
                  echo $id_tipe_kendaraan;
                }else{
                  echo "";
                }

                if($id_warna != 'all'){
                  echo "-$id_warna";
                }else{
                  echo "";
                } 

                ?>                
              </td> -->
            </tr>
          </table>          
          <table class='table table-bordered' style='font-size: 10pt' width='100%'>
            <tr>                
              <td bgcolor='yellow' class='bold text-center' width='5%'>No</td>
              <td bgcolor='yellow' class='bold text-center'>Kode Dealer</td>                
              <td bgcolor='yellow' class='bold text-center'>Dealer</td>                
              <td bgcolor='yellow' class='bold text-center'>Tipe Motor</td>                
              <td bgcolor='yellow' class='bold text-center'>Kode Warna</td>                
              <td bgcolor='yellow' class='bold text-center'>Deskripsi Tipe</td>                
              <td bgcolor='yellow' class='bold text-center'>No Mesin</td>                
              <td bgcolor='yellow' class='bold text-center'>No Rangka</td>                
              <td bgcolor='yellow' class='bold text-center'>Status</td>              
              <td bgcolor='yellow' class='bold text-center'>Aging</td>                
            </tr>
            <tr>
            <?php 
            $no=1;            
            if($id_warna != 'all' AND $id_tipe_kendaraan != 'all'){
              $query = "AND tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan' AND tr_scan_barcode.warna = '$id_warna'";
            }elseif($id_warna != 'all'){
              $query = "AND tr_scan_barcode.warna = '$id_warna'";
            }elseif($id_tipe_kendaraan != 'all'){
              $query = "AND tr_scan_barcode.tipe_motor = '$id_tipe_kendaraan'";
            }
            $sql = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin FROM tr_penerimaan_unit_dealer_detail
                    LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                    LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                    LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                    WHERE (tr_penerimaan_unit_dealer.id_gudang_dealer = '$ambil->gudang' OR tr_penerimaan_unit_dealer.id_gudang_dealer = '$ambil->id_gudang_dealer') AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
                    AND tr_penerimaan_unit_dealer_detail.retur = 0
                    $query AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'");
            foreach ($sql->result() as $row) {
              $cek_tgl = $this->db->query("SELECT tr_do_po.tgl_do FROM tr_surat_jalan JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
                INNER JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
                INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
                WHERE tr_surat_jalan_detail.no_mesin = '$row->nosin' AND tr_surat_jalan_detail.retur = 0");
              $tgl_do = ($cek_tgl->num_rows() > 0) ? $cek_tgl->row()->tgl_do : "" ;                          
              $awal = $tgl_do;
              $akhir = date("Y-m-d");
              $today = strtotime($awal." 00:00:00");
              $myBirthDate = strtotime($akhir." 00:00:00");
              $interval = floor(($myBirthDate - $today)/60/60/24);


                echo "
                  <tr>
                    <td align='center'>$no</td>
                    <td>$row->kode_dealer_md</td>
                    <td>$row->nama_dealer</td>
                    <td>$row->id_tipe_kendaraan</td>
                    <td>$row->id_warna</td>
                    <td>$row->tipe_ahm</td>
                    <td>$row->nosin</td>
                    <td>$row->no_rangka</td>
                    <td>$row->tipe</td>                    
                    <td>".$interval."</td>
                  </tr>
                ";
                $no++;              
            }
            ?>
            </tr>            
          </table> <br>
          <?php 
            }
          }else{ ?>
            <p>Gudang Harus ditentukan dulu.</p>
    <?php } ?>                
    </body>
  </html>
  <?php } ?>
  </section>
</div>


<script>
function getReport(){
  var value={id_gudang:document.getElementById("id_gudang").value,
            id_tipe_kendaraan:document.getElementById("id_tipe_kendaraan").value,
            id_warna:document.getElementById("id_warna").value,
            cetak:'cetak',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
            }

  if (value.id_gudang == '') {
    alert('Isi data dengan lengkap ..!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("dealer/rekap_stock_frame?") ?>cetak='+value.cetak+'&id_gudang='+value.id_gudang+'&id_tipe_kendaraan='+value.id_tipe_kendaraan+'&id_warna='+value.id_warna);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
function getReport2(){
  var value={id_gudang:document.getElementById("id_gudang").value,
            id_tipe_kendaraan:document.getElementById("id_tipe_kendaraan").value,
            id_warna:document.getElementById("id_warna").value,
            cetak:'cetak',
            download:'ya',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
            }

  if (value.id_gudang == '') {
    alert('Isi data dengan lengkap ..!');
    return false;
  }else{
    //alert(value.tipe);
    $('.loader').show();
    $('#btnShow').disabled;
    $("#showReport").attr("src",'<?php echo site_url("dealer/rekap_stock_frame?") ?>cetak='+value.cetak+'&id_gudang='+value.id_gudang+'&id_tipe_kendaraan='+value.id_tipe_kendaraan+'&id_warna='+value.id_warna+'&download='+value.download);
    document.getElementById("showReport").onload = function(e){          
    $('.loader').hide();       
    };
  }
}
</script>