<?php
function mata_uang3($a)
{
  if (preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
  if (is_numeric($a) and $a != 0 and $a != "") {
    return number_format($a, 0, ',', '.');
  } else {
    return $a;
  }
}
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
    if ($set == "view") {
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" action="h1/ssu/create" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="no_invoice" id="no_invoice">
                        <option value="all">All</option>
                        <?php
                        $id_dealer = $this->m_admin->cari_dealer();
                        $sql = $this->db->query("SELECT * FROM tr_sales_order WHERE id_dealer = '$id_dealer' AND no_invoice <> ''");
                        foreach ($sql->result() as $isi) {
                          echo "<option value='$isi->no_invoice'>$isi->no_invoice</option>";
                        }
                        ?>
                      </select>
                    </div>


                    <div class="col-sm-2">
                      <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
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


    <?php } elseif ($set == 'cetak') { ?>
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

        <table>
          <tr>
            <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
          </tr>
        </table>
        <div style="text-align: center;font-size: 13pt"><b>Laporan BPKB Belum Diambil</b></div>
        <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
        <hr>


        <br>
        <table class='table table-bordered' style='font-size: 10pt' width='100%'>
          <tr>
            <td bgcolor='yellow' class='bold text-center' width='3%'>No</td>
            <td bgcolor='yellow' class='bold text-center'>No.Inv</td>
            <td bgcolor='yellow' class='bold text-center'>Nama Pembeli</td>
            <td bgcolor='yellow' class='bold text-center'>Nama STNK</td>
            <td bgcolor='yellow' class='bold text-center'>Type</td>
            <td bgcolor='yellow' class='bold text-center'>No.Mesin</td>
            <td bgcolor='yellow' class='bold text-center'>No.Polisi</td>
            <td bgcolor='yellow' class='bold text-center'>No.BPKB</td>
            <td bgcolor='yellow' class='bold text-center'>Tgl.Pengurusan</td>
            <td bgcolor='yellow' class='bold text-center'>Tgl.Selesai</td>
          </tr>
          <tr>
            <?php
            $no = 1;
            if ($no_invoice == 'all') {
              $query = "";
            } else {
              $query = "AND tr_sales_order.no_invoice = '$no_invoice'";
            }
            $sql = $this->db->query("SELECT *,tr_scan_barcode.no_mesin AS nosin FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
            INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan              
            INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
            INNER JOIN tr_entry_stnk ON tr_scan_barcode.no_mesin = tr_entry_stnk.no_mesin
            LEFT JOIN tr_tandaterima_stnk_konsumen_detail tskd ON tskd.no_mesin=tr_scan_barcode.no_mesin 
            LEFT JOIN tr_tandaterima_stnk_konsumen tsk ON tsk.kd_stnk_konsumen=tskd.kd_stnk_konsumen AND jenis_cetak='bpkb'
            WHERE tr_sales_order.id_dealer = '$id_dealer' AND tskd.no_mesin IS NULL $query");
            foreach ($sql->result() as $row) {
              $tgl_bastd = $this->db->query("SELECT * FROM tr_faktur_stnk_detail INNER JOIN tr_faktur_stnk ON tr_faktur_stnk_detail.no_bastd = tr_faktur_stnk.no_bastd 
              WHERE tr_faktur_stnk_detail.id_sales_order = '$row->id_sales_order'")->row()->no_bastd;
              $tgl_terima = $this->db->query("SELECT * FROM tr_tandaterima_stnk_konsumen INNER JOIN tr_tandaterima_stnk_konsumen_detail 
              WHERE tr_tandaterima_stnk_konsumen_detail.no_mesin = '$row->no_mesin'")->row();

              echo "
              <tr>
                <td align='center'>$no</td>
                <td>$row->no_invoice</td>
                <td>$row->nama_konsumen</td>
                <td>$row->nama_bpkb</td>                
                <td>$row->deskripsi_ahm</td>                                
                <td>$row->nosin</td>                
                <td>$row->no_plat</td>                
                <td>$tgl_terima->no_bpkb</td>                
                <td>$tgl_bastd</td>                
                <td>" . tgl_indo(substr($tgl_terima->tgl_cetak, 0, 10), ' ') . "</td>                                                
              </tr>
            ";
              $no++;
            }
            ?>
          </tr>
        </table> <br>

      </body>

      </html>
    <?php } ?>
  </section>
</div>


<script>
  function getReport() {
    var value = {
      no_invoice: document.getElementById("no_invoice").value,
      cetak: 'cetak',
      //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
    }

    if (value.cetak == '') {
      alert('Isi data dengan lengkap ..!');
      return false;
    } else {
      //alert(value.tipe);
      $('.loader').show();
      $('#btnShow').disabled;
      $("#showReport").attr("src", '<?php echo site_url("dealer/laporan_bpkb_belum_diambil?") ?>cetak=' + value.cetak + '&no_invoice=' + value.no_invoice);
      document.getElementById("showReport").onload = function(e) {
        $('.loader').hide();
      };
    }
  }
</script>