<?php
if ($set == "view") {
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
        <li class="">H3</li>
        <li class="">Laporan</li>
        <li class="active">Laporan Stock Versi All</li>
      </ol>
    </section>
    <body onload="ld();"></body>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body" style="padding-bottom:0px">
                  <div class="form-group">
                    <!-- <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="start_date" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="end_date" readonly />
                    </div>
                  </div> -->
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" id="btnShow" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                <div id="loader" class="loader" style="display:flex;align-items:center;align-content:center;justify-content:center;">
                    <img src="assets/keong.gif">
                </div>
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
      function ld(){
          $('.loader').hide(); 
      }
        function getReport(tipe) {
              $('.loader').show();
               $("#showReport").hide();
          var value = {
            // start_date: $('#start_date').val(),
            // end_date: $('#end_date').val(),
            kpb: $('#kpb').val(),
            tipe: tipe,
            cetak: 'cetak',
          }
            if(value.tipe=='preview'){
            //     if (value.end_date == '' || value.start_date == '' || value.kpb == '') {
            // confirm('Periode belum dipilih, anda yakin ingin menampilkan seluruh data ? \nProses ini mungkin memerlukan waktu, mohon menunggu.');
            // if(confirm){
            //     let values = JSON.stringify(value);
            //     $('.loader').show();
              
              
            //     $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
            //     document.getElementById("showReport").onload = function(e) {
            //       $('.loader').hide();
            //       $("#showReport").show();
            //     };
            // }else{
            //     return false;
            // }
          // } else {
              
                let values = JSON.stringify(value);
                $('.loader').show();
               
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                  $("#showReport").show();
                };
          
           
          // } 
            }else{
               let values = JSON.stringify(value);
                  $('.loader').show();
               
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                  $("#showReport").show();
                 
                };
            }
         
        }
        
      </script>

    </section>
  </div>
<?php } elseif ($set == 'cetak') {
  if ($params->tipe == 'download') {
    echo "<script>
          $('.loader').hide(); 
    </script>";
    header("Content-type: application/octet-stream");
    $file_name = remove_space($title, '_') . '.xls';
    header("Content-Disposition: attachment; filename=$file_name.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  }
?>
  <!DOCTYPE html>
  <html>

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

        .text-center {
          text-align: center;
        }

        .bold {
          font-weight: bold;
        }

        .table {
          width: 100%;
          max-width: 100%;
          border-collapse: collapse;
          /*border-collapse: separate;*/
        }

        .table-bordered tr td {
          border: 0.01em solid black;
          padding-left: 5px;
          padding-right: 3px;
        }

        body {
          font-family: "Arial";
          font-size: 10pt;
        }
      }
    </style>
  </head>

  <body>
    <table>
      <tr>
        <td colspan="11"><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <!-- <div style="text-align: center;font-size: 11pt"><b>Periode : <?= $params->start_date !="" && $params->end_date !=""  ? date_dmy($params->start_date) . ' s/d ' . date_dmy($params->end_date) : "-" ?></b></div> -->
    <div style="text-align: right;font-size: 11pt">Dicetak  : <?=$_SESSION['nama']?> - <?=tgl_indo(date('Y-m-d H:i:s'))?> <?=date('H:i:s')?></div>
    <hr>
  
    <table class="table table-bordered" border=1>
        <tr>
            <th>No</th>
            <th>Part Number</th>
            <th>Description</th>
            <th>Rak</th>
            <th>Qty</th>
            
            <th>Harga Beli</th>
            <th>Jumlah</th>
            <th>Harga Jual</th>
            <th>Jumlah</th>
            <th>Kel. Produk</th>
            <th>Rank</th>
            <th>Status</th>
        </tr>
        <?php 
        // $ci =& get_instance();
        // $ci->load->model('h3_dealer_stock_model');
     
        $no=1;
        $sum_beli=array();
        $disk=0;
        $sum_jual=array();
        // $dealer =$this->db->query("select a.id_dealer,b.id_karyawan_dealer,c.id_user from ms_dealer a join ms_karyawan_dealer b on a.id_dealer=b.id_dealer join ms_user c on c.id_karyawan_dealer=b.id_karyawan_dealer where c.id_user='{$_SESSION['id_user']}'")->row()->id_dealer;
        foreach($details as $rows){
            $disk_tertentu = $this->db->query("select count(a.diskon_reguler) as ds,b.tipe_diskon,a.diskon_reguler from ms_h3_md_diskon_part_tertentu_items a 
            join ms_h3_md_diskon_part_tertentu b on b.id=a.id_diskon_part_tertentu where b.id_part_int='$rows->id_part_int' and a.id_dealer ='$dealer'");
            $diskonitem = $this->db->query("SELECT count(diskon_reguler) as dsk, diskon_reguler,tipe_diskon FROM ms_h3_md_diskon_part_tertentu where id_part_int='$rows->id_part_int'")->row();
            // $diskon_dealer = $this->db->query("SELECT diskon_reguler,tipe_diskon FROM ms_dealer where id_dealer='$dealer'")->row()->diskon_reguler;
            // $diskon_dealer2 = $this->db->query("SELECT diskon_reguler,tipe_diskon FROM ms_dealer where id_dealer='$dealer'")->row()->tipe_diskon;
            // $qty_booking = $ci->h3_dealer_stock_model->qty_book($dealer,$rows->id_part,$rows->id_gudang,$rows->id_rak,$sql = false);
           
            if($disk_tertentu->row()->ds == 0){
                $disk =  $diskonitem->diskon_reguler;
            }
            
            if($disk_tertentu->row()->ds == 0 and  $diskonitem->dsk == 0){
                $disk = $rows->diskon_reguler;
            }
            
            if($disk_tertentu->row()->ds != 0){
                $disk = $disk_tertentu->row()->diskon_reguler;
            }
            
            if($diskonitem->dsk == 1){
                $disk = $diskonitem->diskon_reguler;
            }
            
            
            
            // $hetKurangDiskon = ($diskonitem->tipe_diskon == 'Persen' || $diskon_dealer2 =='Persen' ) ? (($rows->harga_dealer_user * $disk) / 100) :  $disk ;
           if($rows->kelompok_vendor =="FED.OIL"){
                $hetKurangDiskon = $disk;
           }else{
               
               if($diskonitem->tipe_diskon == "Persen" && $rows->kelompok_vendor !="FED.OIL" ){
                   $hetKurangDiskon = (($rows->harga_dealer_user * $disk) / 100);
               }else{
                   $hetKurangDiskon = $disk;
               }
               
                if($disk_tertentu->row()->tipe_diskon == "Persen" && $rows->kelompok_vendor !="FED.OIL" ){
                   $hetKurangDiskon = (($rows->harga_dealer_user * $disk) / 100);
               }else{
                   $hetKurangDiskon = $disk;
               }
                if($rows->tipe_diskon == "Persen" && $rows->kelompok_vendor !="FED.OIL" ){
                   $hetKurangDiskon = (($rows->harga_dealer_user * $disk) / 100);
               }else{
                   $hetKurangDiskon = $disk;
               }
           }
           
           
          
            
            $het =$rows->harga_dealer_user - $hetKurangDiskon; 
            $harga =($het * $rows->stock);
            $sum_beli[] = intval($harga);
            $sum_jual[] = intval($rows->jumlah_jual);
        ?>
        <tr>
           <td style="text-align:center"><?=$no++?></td> 
           <td style="text-align:left">&nbsp;<?=$rows->id_part?></td> 
           <td><?=$rows->nama_part?></td> 
           <td><?=$rows->id_rak?></td> 
           <td style="text-align:center"><?=$rows->stock?></td> 
        
           <td style="text-align:right"><?=number_format($het,0,',','.')?></td> 
           <td style="text-align:right"><?=number_format($harga,0,',','.')?></td> 
           <td style="text-align:right"><?=number_format($rows->harga_dealer_user,0,',','.')?></td> 
           <td style="text-align:right"><?=number_format($rows->jumlah_jual,0,',','.')?></td> 
           <td><?=$rows->kelompok_part?></td> 
           <td style="text-align:center"><?=$rows->rank?></td> 
           <td style="text-align:center"><?=$rows->status?></td> 
        </tr>
        
        <?php } ?>
        <tr>
            <th colspan="6" style="text-align:right;">Total :</th>
            <th><?=number_format(array_sum($sum_beli),0,',','.')?></th>
            <th></th>
            <th style="text-align:right"><?=number_format(array_sum($sum_jual),0,',','.')?></th>
            <th colspan="4"></th>
        </tr>
    </table>
  </body>
   
  </html>
<?php } ?>