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
?>
<?php
if ($set == "view") {
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
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">

      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" action="" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-1 control-label">Dari Tanggal</label>
                    <div class="col-sm-2">
                      <input type="date" name="tgl1" id="tgl1" class="form-control">
                    </div>
                    <label for="inputEmail3" class="col-sm-1 control-label">Sampai Tanggal</label>
                    <div class="col-sm-2">
                      <input type="date" name="tgl2" id="tgl2" class="form-control">
                    </div>
                    <label for="inputEmail3" class="col-sm-1 control-label"></label>
                    <div class="col-sm-4">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="button" onclick="getReport('download')" class="btn btn-primary btn-flat"><i class="fa fa-download"></i> Download .xls</button>
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


    <?php } elseif ($set == 'cetak') {

    if ($tipe == 'download') {
      header("Content-type: application/octet-stream");
      $file_name = remove_space($title, '_') . '.xls';
      header("Content-Disposition: attachment; filename=" . $file_name);
      header("Pragma: no-cache");
      header("Expires: 0");
    }
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
              sheet-size: 297mm 210mm;
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
              font-size: 11pt;
            }
          }
        </style>
      </head>

      <body>
        <?= kop_surat_dealer($this->m_admin->cari_dealer()); ?>
        <div style="text-align: center;font-size: 13pt"><b>Pembelian Unit</b></div>
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>

        <table class='table table-bordered' style='font-size: 9pt' width='100%' <?= $tipe == 'download' ? 'border=1' : '' ?>>
          <tr>
            <td bgcolor='yellow' class='bold text-center' width='5%'>No</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>No Faktur</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>Tgl Faktur</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>Nama Dealer</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>Tipe Motor</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>Kode Warna</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>Desc Motor</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>Warna</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>No Mesin</td>
            <td bgcolor='yellow' class='bold text-center' width='5%'>No Rangka</td>
          </tr>

          
          
          <?php
          $id_dealer = $this->m_admin->cari_dealer();
          $no = 1;
          $sql = "
          SELECT
            a.no_faktur,
            a.tgl_faktur,
            f.nama_dealer,
            e.tipe_motor,
            e.warna as kode_warna,
            g.tipe_ahm,
            h.warna,
            d.no_mesin,
            e.no_rangka,
            ( CASE WHEN d.retur = 1 THEN 'Unit Retur' ELSE '' END ) AS retur 
          FROM
            tr_invoice_dealer a
            JOIN tr_do_po b ON a.no_do = b.no_do
            JOIN tr_picking_list c ON b.no_do = c.no_do
            JOIN tr_picking_list_view d ON c.no_picking_list = d.no_picking_list
            JOIN tr_scan_barcode e ON e.no_mesin = d.no_mesin
            JOIN ms_dealer f ON f.id_dealer = b.id_dealer
            JOIN ms_tipe_kendaraan g ON g.id_tipe_kendaraan = e.tipe_motor
            JOIN ms_warna h ON e.warna = h.id_warna 
            WHERE f.id_dealer='$id_dealer' AND a.tgl_faktur BETWEEN '$tgl1' and '$tgl2'
          ORDER BY
            a.tgl_faktur DESC
          ";
           foreach ($this->db->query($sql)->result() as $row): ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row->no_faktur ?></td>
              <td><?php echo $row->tgl_faktur ?></td>
              <td><?php echo $row->nama_dealer ?></td>
              <td><?php echo $row->tipe_motor ?></td>
              <td><?php echo $row->kode_warna ?></td>
              <td><?php echo $row->tipe_ahm ?></td>
              <td><?php echo $row->warna ?></td>
              <td><?php echo $row->no_mesin ?></td>
              <td><?php echo $row->no_rangka ?></td>
          </tr>
          <?php $no++; endforeach ?>
          
        </table> <br>
      </body>

      </html>
    <?php } ?>
    </section>
  </div>


  <script>
    function getReport(tipe) {
      var value = {
        tgl1: document.getElementById("tgl1").value,
        tgl2: document.getElementById("tgl2").value,
        cetak: 'cetak'
        //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
      }

      if (value.tipe == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      } else {
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src", '<?php echo site_url("dealer/Pembelian_unit?") ?>cetak=' + value.cetak + '&tgl1=' + value.tgl1 + '&tgl2=' + value.tgl2 + '&tipe=' + tipe);
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