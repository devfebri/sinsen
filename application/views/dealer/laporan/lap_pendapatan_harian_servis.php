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
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tanggal" />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tanggal_akhir" />
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center">
                      <?php  
                          // $tgl = date('Y-m-d'); if($tgl <='2023-07-10' || $tgl >='2023-07-15'){ 
                          // $tanggal = date('Y-m-d');  
                          // if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){

                          if(!$this->config->item('ahm_d_only')){
                        ?>
                        <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                        <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-red btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                      <?php }else{ ?>
                        <button type="button" onclick="getReportNon('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                      <?php } ?>
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
        function getReport(tipe) {
          var value = {
            tanggal: $('#tanggal').val(),
            tanggal_akhir: $('#tanggal_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tanggal == ''|| value.tanggal_akhir == '') {
            alert('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "/cetak2?") ?>cetak=' + value.cetak + '&params=' + values);
            document.getElementById("showReport").onload = function(e) {
              $('.loader').hide();
            };
          }
        }

        function getReportNon(tipe) {
          var value = {
            tanggal: $('#tanggal').val(),
            tanggal_akhir: $('#tanggal_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tanggal == '') {
            alert('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "/cetak_nonfed?") ?>cetak=' + value.cetak + '&params=' + values);
            document.getElementById("showReport").onload = function(e) {
              $('.loader').hide();
            };
          }
        }

        $(function() {
          $("#tanggal").datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
          }).on('changeDate', function(selected) {
            var minDate = new Date(selected.date);
            var maxDate = new Date(selected.date);
            minDate.setDate(minDate.getDate());
            maxDate.setDate(maxDate.getDate() + 30);
            $('#tanggal_akhir').datepicker('setStartDate', minDate);
            $('#tanggal_akhir').datepicker('setEndDate', maxDate);
          });

          $("#tanggal_akhir").datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
          }).on('changeDate', function(selected) {
            var minDate = new Date(selected.date);
            // var maxDate = new Date(selected.date+30);
            minDate.setDate(minDate.getDate());
            // maxDate.setDate(maxDate.getDate());
            $('#tanggal').datepicker('setEndDate', minDate);
          });
        });
      </script>

    </section>
  </div>
<?php } elseif ($set == 'cetak') {
  if ($params->tipe == 'download') {
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
        <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <div style="text-align: center; font-weight: bold;">Tanggal : <?php echo $params->tanggal ?></div>
    <hr>
    <table class="table table-bordered" border=1>
      <tr>
        <td colspan=7 rowspan=3></td>
        <td colspan=10>Pembayaran</td>
        <td rowspan=4>Pembayaran Cash</td>
        <td rowspan=4>Pembayaran Transfer</td>
        <td rowspan=4>Tgl Transfer</td>
        <td rowspan=4>Total</td>
      </tr>
      <tr>
        <td colspan=5 rowspan=2>Konsumen</td>
        <td colspan=5>KPB</td>
      </tr>
      <tr>
        <td colspan=2>KPB 1</td>
        <td>KPB 2</td>
        <td>KPB 3</td>
        <td>KPB 4</td>
      </tr>
      <tr>
        <td>No.</td>
        <td>No. Receipt</td>
        <td>No. WO</td>
        <td>Nama Konsumen</td>
        <td>no. Polisi</td>
        <td>Waktu</td>
        <td>Mekanik</td>
        <td>Jasa</td>
        <td>Oli</td>
        <td>Part</td>
        <td>Diskon</td>
        <td>Total</td>
        <td>Jasa</td>
        <td>Oli</td>
        <td>Jasa</td>
        <td>Jasa</td>
        <td>Jasa</td>
      </tr>
      <?php
      $tot_jasa_customer = 0;
      $tot_oli = 0;
      $tot_diskon = 0;
      $tot_part = 0;
      $tot_jasa_kpb1 = 0;
      $tot_oli_kpb1 = 0;
      $tot_jasa_kpb2 = 0;
      $tot_jasa_kpb3 = 0;
      $tot_jasa_kpb4 = 0;
      $tot_bayar_cash = 0;
      $tot_bayar_transfer = 0;
      foreach ($details as $key => $dt) {
        $total = ($dt->tot_jasa_customer + $dt->tot_oli + $dt->tot_part) - $dt->diskon;
        $dt->bayar_cash = $dt->bayar_cash > $total ? $total : $dt->bayar_cash;
        $tot_jasa_customer += (int) $dt->tot_jasa_customer;
        $tot_oli += (int) $dt->tot_oli;
        $tot_diskon += $dt->diskon;
        $tot_part += (int) $dt->tot_part;
        $tot_jasa_kpb1 += (int) $dt->tot_jasa_kpb1;
        $tot_oli_kpb1 += (int) $dt->tot_oli_kpb1;
        $tot_jasa_kpb2 += (int) $dt->tot_jasa_kpb2;
        $tot_jasa_kpb3 += (int) $dt->tot_jasa_kpb3;
        $tot_jasa_kpb4 += (int) $dt->tot_jasa_kpb4;
        $tot_bayar_cash += (int) $dt->bayar_cash;
        $tot_bayar_transfer += (int) $dt->bayar_transfer;
      ?>
        <tr>
          <td><?= $key + 1 ?></td>
          <td><?= $dt->id_receipt ?></td>
          <td><?= $dt->id_work_order ?></td>
          <td><?= $dt->nama_customer ?></td>
          <td><?= $dt->no_polisi ?></td>
          <td><?= (int) $dt->waktu ?></td>
          <td><?= $dt->mekanik ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_jasa_customer) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_oli) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_part) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->diskon) ?></td>
          <td align="right"><?= mata_uang_rp($total) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_jasa_kpb1) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_oli_kpb1) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_jasa_kpb2) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_jasa_kpb3) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_jasa_kpb4) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->bayar_cash) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->bayar_transfer) ?></td>
          <td><?= $dt->tgl_transfer ?></td>
          <td><?= mata_uang_rp($dt->bayar_cash + $dt->bayar_transfer) ?></td>
        </tr>
      <?php }
      $total = ($tot_jasa_customer + $tot_oli + $tot_part) - $tot_diskon;

      ?>
      <tr>
        <td colspan=7 align="right">Grand Total</td>
        <td align="right"><?= mata_uang_rp($tot_jasa_customer) ?></td>
        <td align="right"><?= mata_uang_rp($tot_oli) ?></td>
        <td align="right"><?= mata_uang_rp($tot_part) ?></td>
        <td align="right"><?= mata_uang_rp($tot_diskon) ?></td>
        <td align="right"><?= mata_uang_rp($total) ?></td>
        <td align="right"><?= mata_uang_rp($tot_jasa_kpb1) ?></td>
        <td align="right"><?= mata_uang_rp($tot_oli_kpb1) ?></td>
        <td align="right"><?= mata_uang_rp($tot_jasa_kpb2) ?></td>
        <td align="right"><?= mata_uang_rp($tot_jasa_kpb3) ?></td>
        <td align="right"><?= mata_uang_rp($tot_jasa_kpb4) ?></td>
        <td align="right"><?= mata_uang_rp($tot_bayar_cash) ?></td>
        <td align="right"><?= mata_uang_rp($tot_bayar_transfer) ?></td>
        <td></td>
        <td align="right"><?= mata_uang_rp($tot_bayar_transfer + $tot_bayar_cash) ?></td>
      </tr>
      <tr>
        <td colspan=7 align="right">Total Pendapatan Bengkel Jasa & Part</td>
        <td colspan=5 align="right"><?= mata_uang_rp($tot_jasa_customer + $tot_oli + $tot_part + $tot_diskon) ?></td>
        <td colspan=5 align="right"><?= mata_uang_rp($tot_jasa_kpb1 + $tot_oli_kpb1 + $tot_jasa_kpb2 + $tot_jasa_kpb3 + $tot_jasa_kpb4) ?></td>
        <td colspan=4 align="right"><?= mata_uang_rp($tot_bayar_cash + $tot_bayar_transfer) ?></td>
      </tr>
    </table>
    <br>
    <table class="table table-bordered" border=1 style='font-size:9pt'>
      <tr>
        <td colspan=4></td>
        <td colspan=4>Pembayaran Konsumen</td>
        <td rowspan=2>Pembayaran Cash</td>
        <td rowspan=2>Pembayaran Transfer</td>
        <td rowspan=2>Tgl Transfer</td>
        <td rowspan=2>Total</td>
      </tr>
      <tr>
        <td>No.</td>
        <td>No. Receipt</td>
        <td>No. SO</td>
        <td>Nama Konsumen</td>
        <td>Oli</td>
        <td>Part</td>
        <td>Diskon</td>
        <td>Total</td>
      </tr>
      <?php
      $tot_oli            = 0;
      $tot_diskon         = 0;
      $tot_part           = 0;
      $tot_bayar_cash     = 0;
      $tot_bayar_transfer = 0;
      foreach ($details_sales_part as $key => $dt) {
        $total = ($dt->tot_jasa_customer + $dt->tot_oli + $dt->tot_part) - $dt->diskon;
        $dt->bayar_cash = $dt->bayar_cash > $total ? $total : $dt->bayar_cash;
        $tot_oli += (int) $dt->tot_oli;
        $tot_diskon += $dt->diskon;
        $tot_part += (int) $dt->tot_part;
        $tot_bayar_cash += (int) $dt->bayar_cash;
        $tot_bayar_transfer += (int) $dt->bayar_transfer;
      ?>
        <tr>
          <td><?= $key + 1 ?></td>
          <td><?= $dt->id_receipt ?></td>
          <td><?= $dt->nomor_so ?></td>
          <td><?= $dt->nama_customer ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_oli) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->tot_part) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->diskon) ?></td>
          <td align="right"><?= mata_uang_rp($total) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->bayar_cash) ?></td>
          <td align="right"><?= mata_uang_rp((int) $dt->bayar_transfer) ?></td>
          <td><?= $dt->tgl_transfer ?></td>
          <td><?= mata_uang_rp($dt->bayar_cash + $dt->bayar_transfer) ?></td>
        </tr>
      <?php }
      $total = ($tot_oli + $tot_part) - $tot_diskon;

      ?>
      <tr>
        <td colspan=4 align="right">Grand Total</td>
        <td align="right"><?= mata_uang_rp($tot_oli) ?></td>
        <td align="right"><?= mata_uang_rp($tot_part) ?></td>
        <td align="right"><?= mata_uang_rp($tot_diskon) ?></td>
        <td align="right"><?= mata_uang_rp($total) ?></td>
        <td align="right"><?= mata_uang_rp($tot_bayar_cash) ?></td>
        <td align="right"><?= mata_uang_rp($tot_bayar_transfer) ?></td>
        <td></td>
        <td align="right"><?= mata_uang_rp($tot_bayar_transfer + $tot_bayar_cash) ?></td>
      </tr>
      <tr>
        <td colspan=4 align="right">Total Pendapatan Direct Sales Part</td>
        <td colspan=4 align="right"><?= mata_uang_rp($tot_oli + $tot_part + $tot_diskon) ?></td>
        <td colspan=4 align="right"><?= mata_uang_rp($tot_bayar_cash + $tot_bayar_transfer) ?></td>
      </tr>
    </table>
    <div>Dicetak : <?= kry_login($this->session->userdata('id_user'))->nama_lengkap . ' ' . waktu() ?></div>
  </body>

  </html>
<?php } ?>