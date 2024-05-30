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
        <li class="">H2</li>
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
                <div class="box-body" style="padding-bottom:0px">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Awal</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tgl_awal" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="tgl_akhir" readonly />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-3">
                      <div class="input-group">
                        <input type="hidden" class="form-control" id='id_dealer'>
                        <input type="text" class="form-control" id='kode_dealer_md' readonly>
                        <span class="input-group-btn">
                          <button type="button" name="search" id="search-btn" class="btn btn-primary btn-flat" onclick="showModalAHASS()"><i class="fa fa-search"></i></button>
                        </span>
                      </div>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                    <div class="col-sm-3">
                      <input class="form-control" id="nama_dealer" readonly />
                    </div>
                  </div>
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download Tanda Terima KPB.xls</button>
                      <button type="button" onclick="download_kpb()" class="btn bg-orange btn-flat"><i class="fa fa-download"></i> Download Inputan Claim KPB.xls</button>
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
      <?php
      $data = ['data' => ['modalAHASS']];
      $this->load->view('h2/api', $data);
      ?>
      <script>
        function pilihAHASS(ahass) {
          $('#kode_dealer_md').val(ahass.kode_dealer_md);
          $('#nama_dealer').val(ahass.nama_dealer);
          $('#id_dealer').val(ahass.id_dealer);
        }

        function getReport(tipe) {
          var value = {
            tgl_awal: $('#tgl_awal').val(),
            tgl_akhir: $('#tgl_akhir').val(),
            id_dealer: $('#id_dealer').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.tgl_akhir == '' || value.tgl_awal == '' || value.kpb == '' || value.id_dealer == '') {
            toastr_warning('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("h2/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
            document.getElementById("showReport").onload = function(e) {
              $('.loader').hide();
            };
          }
        }

        function download_kpb() {
          var tgl_start = $('#tgl_awal').val();
          var tgl_end = $('#tgl_akhir').val();
          var id_dealer = $('#id_dealer').val();
          document.location = '<?php echo site_url("h2/h2_tanda_terima_kpb/download_kpb?") ?>started=' + tgl_start + '&ended=' + tgl_end+ '&id_dealer=' + id_dealer;
        }
      </script>

    </section>
  </div>
<?php } elseif ($set == 'cetak') {
  if ($params->tipe == 'download') {
    header("Content-type: application/octet-stream");
    $file_name = remove_space($title, '_') . '.xls';
    header("Content-Disposition: attachment; filename=" . $file_name);
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
          margin-bottom: 0.8cm;
          margin-top: 1cm;
        }

        .text-center {
          text-align: center;
        }

        .bold {
          font-weight: bold;
        }

        .center {
          text-align: center;
        }

        .right {
          text-align: right;
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
    <div style="text-align: center;font-size: 12pt"><b>TANDA TERIMA KPB</b></div>
    <div style="text-align: center;font-size: 12pt"><b>Periode : <?= $params->tgl_awal ?> - <?= $params->tgl_akhir ?></b></div>
    <div style="text-align: center;font-size: 12pt"><b>NAMA AHASS : <?= $dealer->nama_dealer ?></b></div>
    <div style="text-align: center;font-size: 12pt"><b>NOMOR AHASS : <?= $dealer->kode_dealer_md ?></b></div>
    <hr>
    <table class='table table-bordered' border=1>
      <?php foreach ($details as $dt_5) { ?>
        <tr>
          <td class='center' colspan=11><?= $dt_5['no_mesin_5'] ?></td>
        </tr>
        <tr>
          <td class='center' rowspan=2 colspan=2>KPB</td>
          <td class='center' rowspan=2>Jasa</td>
          <td class='center' rowspan=2>Keunt. Oli</td>
          <td class='center' rowspan=2>Oli</td>
          <td class='center' colspan=6>AHASS</td>
        </tr>
        <tr>
          <td class='center'>Jasa</td>
          <td class='center'>Insentif Oli</td>
          <td class='center'>Oli</td>
          <td class='center'>PPN</td>
          <td class='center'>PPH</td>
          <td class='center'>Sub Total</td>
        </tr>
        <?php $tot = [
          'tot_jasa' => 0,
          'tot_insentif_oli' => 0,
          'tot_oli' => 0,
          'ppn' => 0,
          'pph' => 0,
          'subtotal' => 0,
        ];
        foreach ($dt_5['kpb'] as $dt) {
          $subtotal = $dt['tot_jasa'] + $dt['tot_insentif_oli'] + $dt['tot_oli'] + $dt['ppn'] - $dt['pph'];
          $tot['tot_jasa'] += $dt['tot_jasa'];
          $tot['tot_insentif_oli'] += $dt['tot_insentif_oli'];
          $tot['tot_oli'] += $dt['tot_oli'];
          $tot['ppn'] += $dt['ppn'];
          $tot['pph'] += $dt['pph'];
          $tot['subtotal'] += $subtotal;
        ?>
          <tr>
            <td>KPB <?= dec_romawi($dt['kpb']) ?></td>
            <td><?= $dt['qty'] ?></td>
            <td class='right'><?= mata_uang_rp($dt['harga_jasa']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['insentif_oli']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['harga_material']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['tot_jasa']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['tot_insentif_oli']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['tot_oli']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['ppn']) ?></td>
            <td class='right'><?= mata_uang_rp($dt['pph']) ?></td>
            <td class='right'><?= mata_uang_rp($subtotal) ?></td>
          </tr>
        <?php } ?>
        <tr>
          <td colspan=5 class='center'>Total</td>
          <td class='right'><?= mata_uang_rp($tot['tot_jasa']) ?></td>
          <td class='right'><?= mata_uang_rp($tot['tot_insentif_oli']) ?></td>
          <td class='right'><?= mata_uang_rp($tot['tot_oli']) ?></td>
          <td class='right'><?= mata_uang_rp($tot['ppn']) ?></td>
          <td class='right'><?= mata_uang_rp($tot['pph']) ?></td>
          <td class='right'><?= mata_uang_rp($tot['subtotal']) ?></td>
        </tr>
      <?php } ?>
    </table>

  </html>
<?php } ?>