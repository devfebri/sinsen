<?php

function bln($a)
{
  $bulan = $bl = $month = $a;
  switch ($bulan) {
    case "1":
      $bulan = "Januari";
      break;
    case "2":
      $bulan = "Februari";
      break;
    case "3":
      $bulan = "Maret";
      break;
    case "4":
      $bulan = "April";
      break;
    case "5":
      $bulan = "Mei";
      break;
    case "6":
      $bulan = "Juni";
      break;
    case "7":
      $bulan = "Juli";
      break;
    case "8":
      $bulan = "Agustus";
      break;
    case "9":
      $bulan = "September";
      break;
    case "10":
      $bulan = "Oktober";
      break;
    case "11":
      $bulan = "November";
      break;
    case "12":
      $bulan = "Desember";
      break;
  }
  $bln = $bulan;
  return $bln;
}

function mata_uang3($a)
{
  return number_format($a, 0, ',', '.');
}

?>

<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }

  .vertical-text {
    writing-mode: lr-tb;
    text-orientation: mixed;
  }

  .rotate {
    -webkit-transform: rotate(-90deg);
    -moz-transform: rotate(-90deg);
  }

  #mySpan {
    writing-mode: vertical-lr;
    transform: rotate(180deg);
  }
</style>

<base href="<?php echo base_url(); ?>" />
<?php
if ($set == "view") {
?>

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
              <form class="form-horizontal" id="frm" method="post" action="h2/h2_md_historical_service/downloadExcel" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-1 control-label">Start Date</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" name="tgl1" value="<?= date('Y-m-d') ?>" id="tanggal1">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" name="tgl2" value="<?= date('Y-m-d') ?>" id="tanggal2">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-1 control-label">Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_dealer" id="id_dealer">
                        <option value="all">All Dealers</option>
                        <?php
                        foreach ($dt_dealer as $isi) {
                          echo "<option value='$isi->id_dealer'>$isi->kode_dealer_ahm - $isi->nama_dealer</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="modal-footer">
                    <div class="col-sm-12" align="center">
                      <button type="submit" name="process" value="excel" class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                    </div>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      </table>
      </body>

      </html>
    </section>
  </div>

  <script>
    $(function() {
      $("#tanggal1").datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
      }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date);
        minDate.setDate(minDate.getDate());
        $('#tanggal2').datepicker('setStartDate', minDate);
      });

      $("#tanggal2").datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
      }).on('changeDate', function(selected) {
        var minDate = new Date(selected.date);
        minDate.setDate(minDate.getDate());
        $('#tanggal1').datepicker('setEndDate', minDate);
      });
    });
  </script>
<?php } ?>