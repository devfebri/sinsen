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
        <li class="">H1</li>
        <li class="">Laporan</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form" id="frm" method="GET">
                <input type='hidden' name="cetak" value=1>
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <label>Start Date</label>
                      <input type="text" class="form-control datepicker" id="start_date" name="start_date">
                    </div>
                    <div class="col-sm-3">
                      <label>End Date</label>
                      <input type="text" class="form-control datepicker" id="end_date" name="end_date">
                    </div>
                    <div class="col-sm-3">
                      <label>Filter</label>
                      <select class="form-control" id="filter" name="filter">
                        <option value="all">All</option>
                        <option value="njb">NJB</option>
                        <option value="nsc">NSC</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="submit" id="submitBtn" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-download"></i> Download .csv</button>
                  </div>
                  <!-- <div style="min-height: 600px">
                    <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                  </div> -->
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    <?php } ?>
    </section>
  </div>
  <script>
    function getReport() {
      var value = {
        start_date: document.getElementById("start_date").value,
        end_date: document.getElementById("end_date").value,
        id_dealer: document.getElementById("id_dealer").value,
        cetak: 'cetak',
      }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      } else {
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src", '<?php echo site_url("h1/monitoring_penjualan_dealer_daily_tes?") ?>tipe=' + value.tipe + '&cetak=' + value.cetak + '&start_date=' + value.start_date + '&end_date=' + value.end_date + '&id_dealer=' + value.id_dealer);
        document.getElementById("showReport").onload = function(e) {
          $('.loader').hide();
        };
      }
    }

    function getRadioVal(form, name) {
      var val;
      var radios = form.elements[name];
      for (var i = 0, len = radios.length; i < len; i++) {
        if (radios[i].checked) { // radio checked?
          val = radios[i].value; // if so, hold its value in val
          break; // and break out of for loop
        }
      }
      return val; // return value of checked radio or undefined if none checked
    }
  </script>