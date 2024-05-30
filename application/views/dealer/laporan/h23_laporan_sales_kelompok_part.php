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
                <div class="box-body" style="padding-bottom:0px">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Bulan Awal</label>
                    <div class="col-sm-3">
                      <input class="form-control monthpicker" id="bulan_awal" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Bulan Akhir</label>
                    <div class="col-sm-3">
                      <input class="form-control monthpicker" id="bulan_akhir" readonly />
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
            bulan_awal: $('#bulan_awal').val(),
            bulan_akhir: $('#bulan_akhir').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.bulan_akhir == '' || value.bulan_awal == '' || value.kpb == '') {
            alert('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
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
    <hr>
    <table class="table table-bordered" border=1>
      <tr>
        <td rowspan=3 width='4%'><b>No.</b></td>
        <td rowspan=3 width='7%'><b>Bulan</b></td>
        <td rowspan=3 width='7%'><b>Unit Entri</b></td>
        <td colspan=12 width="49%"><b>Quantity & Amount Sales Out</b></td>
        <td colspan=6 rowspan=2><b>% Ganti Parts / Oil</b></td>
      </tr>
      <tr>
        <td colspan=2>OIL</td>
        <td colspan=2>GMO</td>
        <td colspan=2>HIC</td>
        <td colspan=2>HPC</td>
        <td colspan=2>CVT</td>
        <td colspan=2>CHAIN LUBE</td>
      </tr>
      <tr>
        <td>Qty</td>
        <td>Amount</td>
        <td>Qty</td>
        <td>Amount</td>
        <td>Qty</td>
        <td>Amount</td>
        <td>Qty</td>
        <td>Amount</td>
        <td>Qty</td>
        <td>Amount</td>
        <td>Qty</td>
        <td>Amount</td>
        <td>OIL</td>
        <td>GMO</td>
        <td>HIC</td>
        <td>HPC</td>
        <td>CVT</td>
        <td>CHAIN LUBE</td>
      </tr>
      <?php $no = 1;
      foreach ($details as $dts) { ?>
        <tr>
          <td><?= $no ?></td>
          <td><?= date('M-y', strtotime($dts->ym . '-01')) ?></td>
          <td><?= $dts->ue ?></td>
          <td><?= $dts->qty_oli ?></td>
          <td><?= $dts->amount_oli ?></td>
          <td><?= $dts->qty_gmo ?></td>
          <td><?= $dts->amount_gmo ?></td>
          <td><?= $dts->qty_hic ?></td>
          <td><?= $dts->amount_hic ?></td>
          <td><?= $dts->qty_hpc ?></td>
          <td><?= $dts->amount_hpc ?></td>
          <td><?= $dts->qty_cvt ?></td>
          <td><?= $dts->amount_cvt ?></td>
          <td><?= $dts->qty_chain ?></td>
          <td><?= $dts->amount_chain ?></td>
          <td><?= $dts->ue_oli ?></td>
          <td><?= $dts->ue_gmo ?></td>
          <td><?= $dts->ue_hic ?></td>
          <td><?= $dts->ue_hpc ?></td>
          <td><?= $dts->ue_cvt ?></td>
          <td><?= $dts->ue_chain ?></td>
        </tr>
      <?php $no++;
      } //dt 
      ?>
    </table>
  </body>

  </html>
<?php } ?>