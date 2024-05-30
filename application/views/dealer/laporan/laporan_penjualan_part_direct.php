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
        <li class="">Finance H23</li>
        <li class="">Laporan</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
         <body onload="ld();"></body>
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body" style="padding-bottom:0px">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="start_date" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="end_date" readonly />
                    </div>
                  </div>
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
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
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            kpb: $('#kpb').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

                    if(value.tipe=='preview'){
                if (value.end_date == '' || value.start_date == '' || value.kpb == '') {
            confirm('Periode belum dipilih, anda yakin ingin menampilkan seluruh data ? \nProses ini mungkin memerlukan waktu, mohon menunggu.');
            if(confirm){
                let values = JSON.stringify(value);
                $('.loader').show();
              
              
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                  $("#showReport").show();
                };
            }else{
                return false;
            }
          } else {
              
                let values = JSON.stringify(value);
                $('.loader').show();
               
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                  $("#showReport").show();
                };
          
           
          } 
            }else{
               let values = JSON.stringify(value);
               
                 $('.loader').hide();
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                 
                };
            }
        }
      </script>

    </section>
  </div>
<?php } elseif ($set == 'cetak') {
  if ($params->tipe == 'download') {
       ini_set('display_errors', 0);
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
          padding-left: 6px;
          padding-right: 6px;
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
        <td colspan="9"><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <div style="text-align: center;font-size: 11pt">Periode : <?= date_dmy($params->start_date) . ' - ' . date_dmy($params->end_date) ?></div>
    <div style="text-align: right;font-size: 11pt">Dicetak  : <?=$_SESSION['nama']?> - <?=tgl_indo(date('Y-m-d H:i:s'))?> <?=date('H:i:s')?></div>
    <hr>
    <table class="table table-bordered" border=1>
      <tr>
        <th style="text-align:center;">No.</th>
        <th>No. NSC</th>
        <th>Part Number</th>
        <th>Description</th>
        <th style="text-align:center;">Qty</th>
        <th style="text-align:center;">Harga Jual</th>
        <th style="text-align:center;">Disc</th>
        <th style="text-align:center;">Harga Akhir</th>
        <th style="text-align:center;">Total</th>
     
      </tr>
      <?php $no = 1;
      $sum_qty = array();
      $sum_harga_beli = array();
      $sum_disc = array();
      $sum_harga_akhir = array();
      $sum_total = array();
      foreach ($details as $rows) {
        $sum_qty[]=intval($rows->qty);
        $sum_harga_beli[]=intval($rows->harga_beli);
        $sum_disc[]=intval($rows->disc);
        $sum_harga_akhir[]=intval($rows->harga_akhir);
        $sum_total[]=intval($rows->qty * $rows->harga_akhir);
      ?>
        <tr>
          <td style="text-align:center;" width="30px"><?= $no ?></td>
          <td><?= $rows->no_nsc ?></td>
          <td><?= $rows->id_part ?></td>
          <td><?= $rows->nama_part ?></td>
          <td style="text-align:center;"><?= $rows->qty ?></td>
          <td style="text-align:right;"><?= mata_uang_rp($rows->harga_beli) ?></td>
          <td style="text-align:right;"><?= mata_uang_rp($rows->disc) ?></td>
          <td style="text-align:right;"><?= mata_uang_rp($rows->harga_akhir) ?></td>
          <td style="text-align:right;"><?= mata_uang_rp($rows->qty * $rows->harga_akhir) ?></td>
        </tr>
      <?php $no++;
      } ?>
      <tr>
          <th colspan="4">Total</th>
          <th><?=mata_uang_rp(array_sum($sum_qty))?></th>
          <th style="text-align:right;"><?=mata_uang_rp(array_sum($sum_harga_beli))?></th>
          <th style="text-align:right;"><?=mata_uang_rp(array_sum($sum_disc))?></th>
          <th style="text-align:right;"><?=mata_uang_rp(array_sum($sum_harga_akhir))?></th>
          <th style="text-align:right;"><?=mata_uang_rp(array_sum($sum_total))?></th>
      </tr>
    </table>
  </body>

  </html>
<?php } ?>