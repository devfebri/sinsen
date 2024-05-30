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
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Awal *</label>
                    <div class="col-sm-2">
                      <input type="text" id="tgl_awal" class="form-control datepicker" placeholder="Tanggal Awal" autocomplete="off">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Akhir *</label>
                    <div class="col-sm-2">
                      <input type="text" id="tgl_akhir" class="form-control datepicker" placeholder="Tanggal Akhir" autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                    </div>
                    <div class="col-sm-2">
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
        function getReport(tipe = null) {
          var value = {
            tgl_awal: document.getElementById("tgl_awal").value,
            tgl_akhir: document.getElementById("tgl_akhir").value,
            tipe: tipe,
            cetak: 'cetak',
            //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
          }

          if (value.tgl_awal == '' && value.tgl_akhir == '') {
            alert('Isi data dengan lengkap ..!');
            return false;
          } else {
            //alert(value.tipe);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("dealer/$page?") ?>cetak=' + value.cetak + '&tgl_awal=' + value.tgl_awal + '&tgl_akhir=' + value.tgl_akhir + '&tipe=' + value.tipe);
            document.getElementById("showReport").onload = function(e) {
              $('.loader').hide();
            };
          }
        }
      </script>

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
              font-size: 11pt;
            }
          }
        </style>
      </head>

      <body>
        <?= kop_surat_dealer($this->m_admin->cari_dealer()); ?>
        <div style="text-align: center;font-size: 13pt">
          <b><?= $title ?></b></div>
        <div>Periode : <?php echo $tgl_awal ?> s/d <?php echo $tgl_akhir ?></div>
        <table class="table table-bordered" <?= $tipe == 'download' ? 'border=1' : '' ?>>
          <tr>
            <td>No.</td>
            <td>Promotion ID</td>
            <td>No. SPK</td>
            <td>No. SO</td>
            <td>ID Customer</td>
            <td>Nama Customer</td>
            <td>Kode Tipe Unit</td>
            <td>Deskripsi Tipe Unit</td>
            <td>Nomor Mesin</td>
            <td>Nomor Rangka</td>
            <td>Promotion</td>
          </tr>
          <?php
          $data = $this->db->query("SELECT tr_sales_order.*,tr_spk.*,tipe_ahm FROM tr_sales_order 
                JOIN tr_spk ON tr_sales_order.no_spk=tr_spk.no_spk
                JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                WHERE tr_sales_order.id_dealer='$id_dealer' 
                AND (no_invoice IS NOT NULL OR no_invoice!='') 
                AND tgl_cetak_invoice BETWEEN '$tgl_awal' AND '$tgl_akhir'
                AND tr_spk.program_umum IS NOT NULL AND tr_spk.program_umum!=''
                ");
          foreach ($data->result() as $key => $rs) {
            $no = $key + 1;
            $promo = 0;
            if ($rs->jenis_beli == 'Kredit') {
              $promo = ($rs->voucher_2 + $rs->voucher_tambahan_2);
            } else {
              $promo = ($rs->voucher_1 + $rs->voucher_tambahan_1);
            }
            echo "<tr>
                    <td>$no</td>
                    <td>$rs->program_umum</td>
                    <td>$rs->no_spk</td>
                    <td>$rs->id_sales_order</td>
                    <td>$rs->id_customer</td>
                    <td>$rs->nama_konsumen</td>
                    <td>$rs->id_tipe_kendaraan</td>
                    <td>$rs->tipe_ahm</td>
                    <td>$rs->no_mesin</td>
                    <td>$rs->no_rangka</td>
                    <td align='right'>" . mata_uang_rp($promo) . "</td>
                  </tr>
                ";
          }
          ?>
        </table>
      </body>

      </html>
    <?php } ?>
    </section>
  </div>