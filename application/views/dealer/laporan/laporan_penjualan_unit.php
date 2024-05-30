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
              <form class="form-horizontal" action="dealer/laporan_penjualan_unit/download" id="frm" method="POST" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Invoice Awal *</label>
                    <div class="col-sm-4">
                      <input type="text" id="tanggal2" name="tanggal2" class="form-control" placeholder="Periode Invoice Awal" autocomplete="off">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Invoice Akhir *</label>
                    <div class="col-sm-4">
                      <input type="text" id="tanggal3" name="tanggal3" class="form-control" placeholder="Periode Invoice Akhir" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Lokasi</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="id_gudang" id="id_gudang">
                        <option value="all">All</option>
                        <?php
                        $id_dealer = $this->m_admin->cari_dealer();
                        $sql = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE id_dealer = '$id_dealer'");
                        foreach ($sql->result() as $isi) {
                          echo "<option value='$isi->gudang'>$isi->gudang</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Karyawan</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_karyawan_dealer" id="id_karyawan_dealer">
                        <option value="all">All</option>
                        <?php
                        $id_dealer = $this->m_admin->cari_dealer();
                        $sql = $this->db->query("SELECT * FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer'");
                        foreach ($sql->result() as $isi) {
                          echo "<option value='$isi->id_karyawan_dealer'>$isi->nama_lengkap</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Type</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="tipe" id="id_tipe_kendaraan">
                        <option value="all">All</option>
                        <?php
                        $sql = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active = '1'");
                        foreach ($sql->result() as $isi) {
                          echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Warna</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_warna" id="id_warna">
                        <option value="all">All</option>
                        <?php
                        $sql = $this->db->query("SELECT * FROM ms_warna WHERE active = '1'");
                        foreach ($sql->result() as $isi) {
                          echo "<option value='$isi->id_warna'>$isi->id_warna | $isi->warna</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Penjualan</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="jenis_penjualan" id="jenis_penjualan">
                        <option value="all">All</option>
                        <option>Cash</option>
                        <option>Kredit</option>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label"></label>
                    <div class="col-sm-2">
                      <button type="button" onclick="getReport()" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="submit" name="process" value="download" class="btn btn-primary btn-flat"><i class="fa fa-download"></i> Download</button>
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
        <?php
        if ($download == "yes") {
          $no = date("dmyhis");
          header("Content-type: application/octet-stream");
          header("Content-Disposition: attachment; filename=laporan_penjualan_" . $no . ".xls");
          header("Pragma: no-cache");
          header("Expires: 0");
        }
        ?>
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
        <?php if ($tanggal2 != '') {
          $tipe_ahm = "All";
          $nama_lengkap = "All";
          $warna = "All"; ?>
          <table>
            <tr>
              <td><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
            </tr>
          </table>
          <div style="text-align: center;font-size: 13pt"><b>Laporan Penjualan Unit</b></div>
          <!-- <div style="text-align: center; font-weight: bold;">Bulan : <?php echo $tgl ?></div> -->
          <hr>
          <table border="0" width="100%">
            <tr>
              <td>Periode Invoice</td>
              <td>: <?php echo $tanggal2 ?> s/d <?php echo $tanggal3 ?></td>
              <td></td>
              <td>Jenis Penjualan</td>
              <td>: <?php echo $jenis_penjualan ?></td>
            </tr>
            <tr>
              <td>Lokasi</td>
              <td>: <?php echo $id_gudang ?></td>
              <td></td>
              <td>Salesman</td>
              <td>: <?php
                    if ($id_karyawan_dealer != 'all') {
                      $nama_lengkap = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $id_karyawan_dealer)->row()->nama_lengkap;
                    }
                    echo $nama_lengkap ?></td>
            </tr>
            <tr>
              <td>Tipe Motor</td>
              <td>: <?php
                    if ($id_tipe_kendaraan != 'all') {
                      $tipe_ahm = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $id_tipe_kendaraan)->row()->tipe_ahm;
                    }
                    echo "$id_tipe_kendaraan - $tipe_ahm"; ?></td>
              <td></td>
              <td>Warna Motor</td>
              <td>: <?php
                    if ($id_warna != 'all') {
                      $warna = $this->m_admin->getByID("ms_warna", "id_warna", $id_warna)->row()->warna;
                    }
                    echo "$id_warna - $warna"; ?></td>
            </tr>
          </table>
          <br>
          <table class='table table-bordered' style='font-size: 8pt' width='100%'>
            <tr>
              <td bgcolor='yellow' class='bold text-center' width='3%'>No</td>
              <td bgcolor='yellow' class='bold text-center' width='12%'>No.SO</td>
              <td bgcolor='yellow' class='bold text-center' width='6%'>Tgl.SO</td>
              <td bgcolor='yellow' class='bold text-center' width='8%'>No.Inv</td>
              <td bgcolor='yellow' class='bold text-center' width='6%'>Tgl.Inv</td>
              <td bgcolor='yellow' class='bold text-center' width='7%'>No Mesin</td>
              <td bgcolor='yellow' class='bold text-center' width='6%'>Salesman</td>
              <td bgcolor='yellow' class='bold text-center' width='4%'>Tipe Bayar</td>
              <td bgcolor='yellow' class='bold text-center' width='5%'>Tipe Cust</td>
              <td bgcolor='yellow' class='bold text-center' width='10%'>Nama Konsumen</td>
              <td bgcolor='yellow' class='bold text-center' width='5%'>Tipe-Color</td>
              <td bgcolor='yellow' class='bold text-center' width='7%'>Harga</td>
              <td bgcolor='yellow' class='bold text-center' width='5%'>Disc. Program</td>
              <td bgcolor='yellow' class='bold text-center' width='5%'>Disc. Dealer</td>
              <td bgcolor='yellow' class='bold text-center' width='6%'>BBN</td>
              <td bgcolor='yellow' class='bold text-center' width='6%'>Total Harga</td>
            </tr>
            <tr>
              <?php
              $total_harga = 0;
              $total_discount1 = 0;
              $total_discount2 = 0;
              $total_bbn = 0;
              $total_harga_akhir = 0;
              $no = 1;
              $query = "";
              if ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and  $id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna'";
              } elseif ($id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'
                        AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna' ";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_warna = '$id_warna'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan'";
              } elseif ($id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_spk.id_warna = '$id_warna' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk.id_warna = '$id_warna'";
              } elseif ($id_gudang != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'";
              } elseif ($id_karyawan_dealer != 'all') {
                $query = "AND tr_prospek.id_karyawan_dealer = '$id_karyawan_dealer'";
              } elseif ($id_tipe_kendaraan != 'all') {
                $query = "AND tr_spk.id_tipe_kendaraan = '$id_tipe_kendaraan'";
              } elseif ($id_warna != 'all') {
                $query = "AND tr_spk.id_warna = '$id_warna'";
              } elseif ($jenis_penjualan != 'all') {
                $query = "AND tr_spk.jenis_beli = '$jenis_penjualan'";
              }
              $id_dealer = $this->m_admin->cari_dealer();
              $query = "";
              $sql = $this->db->query("SELECT *,ms_karyawan_dealer.nama_lengkap AS salesman,tr_spk.no_spk AS no_spk_fix,tr_sales_order.created_at AS tgl_so, tr_spk.id_tipe_kendaraan AS tipe_motor, tr_spk.id_warna AS warna,tr_penerimaan_unit_dealer_detail.no_mesin AS nosin , tr_spk.nama_konsumen
              FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              LEFT JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_sales_order.no_mesin 
              LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
              LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
              LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
              WHERE tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_sales_order.id_dealer = '$id_dealer' AND tr_sales_order.tgl_cetak_invoice IS NOT NULL AND 
              tr_sales_order.tgl_cetak_invoice BETWEEN '$tanggal2' AND '$tanggal3'
               group by tr_sales_order.no_mesin
               order by tr_sales_order.id_sales_order_int
               $query
               ");
              foreach ($sql->result() as $row) {

                if ($row->jenis_beli == 'Cash') {
                  $voucher_tambahan = $row->voucher_tambahan_1 + $row->diskon;
                  if ($row->the_road == 'On The Road') {
                    $total_bayar = $row->harga_tunai - ($row->voucher_1 + $voucher_tambahan);
                    $bbn = $row->biaya_bbn;
                  } elseif ($row->the_road == 'Off The Road') {
                    $total_bayar = $row->harga_off_road - ($row->voucher_1 + $voucher_tambahan);
                    $bbn = 0;
                  }
                  $ho = $row->harga_tunai - ($row->voucher_1 + $voucher_tambahan) - $row->biaya_bbn;
                } else {
                  $voucher_tambahan = $row->voucher_tambahan_2 + $row->diskon;
                  if ($row->the_road == 'On The Road') {
                    $total_bayar = $row->harga_tunai - ($row->voucher_2 + $voucher_tambahan);
                    $bbn = $row->biaya_bbn;
                  } elseif ($row->the_road == 'Off The Road') {
                    $total_bayar = $row->harga_off_road - ($row->voucher_2 + $voucher_tambahan);
                    $bbn = 0;
                  }
                  //$ho = $row->harga_on_road - ($row->voucher_1 + $voucher_tambahan) - $row->biaya_bbn;
                  $ho = $total_bayar - $row->biaya_bbn;
                }

                echo "
              <tr>
                <td align='center'>$no</td>                
                <td>$row->id_sales_order</td>
                <td>" . substr($row->tgl_so, 0, 10) . "</td>
                <td>$row->no_invoice</td>
                <td>" . substr($row->tgl_cetak_invoice, 0, 10) . "</td>
                <td>$row->nosin</td>
                <td>$row->salesman</td>
                <td>$row->jenis_beli</td>
                <td>Individu</td>
                <td>$row->nama_konsumen</td>
                <td>$row->tipe_motor - $row->warna</td>
                <td align='right'>" . mata_uang3($harga = $ho) . "</td>
                <td align='right'>" . mata_uang3($disc1 = $row->voucher_1 + $row->voucher_2) . "</td>
                <td align='right'>" . mata_uang3($disc2 = $row->voucher_tambahan_1 + $row->voucher_tambahan_2 + $row->diskon) . "</td>
           	<td align='right'>" . mata_uang3($bbn = $row->biaya_bbn) . "</td>
                <td align='right'>" . mata_uang3($row->total_bayar) . "</td>
              </tr>
            ";
                $no++;
                $total_harga  += $harga;
                $total_discount1  += $disc1;
                $total_discount2  += $disc2;
                $total_bbn  += $bbn;
                $total_harga_akhir    += $row->total_bayar;
              }


              if ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and  $id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna'";
              } elseif ($id_tipe_kendaraan != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'
                        AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna' ";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_warna != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_warna = '$id_warna'";
              } elseif ($id_gudang != 'all' and $id_karyawan_dealer != 'all' and $id_tipe_kendaraan != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang' AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'
                      AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan'";
              } elseif ($id_warna != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_spk_gc_kendaraan.id_warna = '$id_warna' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_tipe_kendaraan != 'all' and $jenis_penjualan != 'all') {
                $query = "AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              } elseif ($id_tipe_kendaraan != 'all' and $id_warna != 'all') {
                $query = "AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan' AND tr_spk_gc_kendaraan.id_warna = '$id_warna'";
              } elseif ($id_gudang != 'all') {
                $query = "AND tr_penerimaan_unit_dealer.id_gudang_dealer = '$id_gudang'";
              } elseif ($id_karyawan_dealer != 'all') {
                $query = "AND tr_prospek_gc.id_karyawan_dealer = '$id_karyawan_dealer'";
              } elseif ($id_tipe_kendaraan != 'all') {
                $query = "AND tr_spk_gc_kendaraan.id_tipe_kendaraan = '$id_tipe_kendaraan'";
              } elseif ($id_warna != 'all') {
                $query = "AND tr_spk_gc_kendaraan.id_warna = '$id_warna'";
              } elseif ($jenis_penjualan != 'all') {
                $query = "AND tr_spk_gc.jenis_beli = '$jenis_penjualan'";
              }
              $id_dealer = $this->m_admin->cari_dealer();
              
              // $sql = $this->db->query("SELECT *,ms_karyawan_dealer.nama_lengkap AS salesman, tr_sales_order_gc.created_at AS tgl_so, tr_spk_gc_kendaraan.id_tipe_kendaraan AS tipe_motor, tr_spk_gc_kendaraan.id_warna AS warna,tr_sales_order_gc_nosin.no_mesin AS nosin 
              // FROM tr_sales_order_gc INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
              // LEFT JOIN tr_spk_gc_kendaraan ON tr_spk_gc.no_spk_gc = tr_spk_gc_kendaraan.no_spk_gc              
              // LEFT JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc
              // LEFT JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
              // LEFT JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin 
              // LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
              // LEFT JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
              // LEFT JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
              // WHERE tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_sales_order_gc.id_dealer = '$id_dealer' 
		          // AND tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tanggal2' AND '$tanggal3' $query
              // GROUP BY tr_sales_order_gc_nosin.no_mesin");

              $sql = $this->db->query("
              SELECT tr_sales_order_gc.id_sales_order_gc , tr_sales_order_gc.no_invoice , tr_sales_order_gc.tgl_cetak_invoice , tr_spk_gc.jenis_beli , tr_spk_gc.nama_npwp ,
              tr_spk_gc_detail.harga , tr_spk_gc_detail.nilai_voucher , tr_spk_gc_detail.biaya_bbn , tr_spk_gc_detail.voucher_tambahan ,
              ms_karyawan_dealer.nama_lengkap AS salesman, tr_sales_order_gc.created_at AS tgl_so, tr_scan_barcode.id_item ,
              tr_spk_gc_kendaraan.id_tipe_kendaraan AS tipe_motor, tr_spk_gc_kendaraan.id_warna AS warna,tr_sales_order_gc_nosin.no_mesin AS nosin 
              FROM tr_sales_order_gc 
              INNER JOIN tr_spk_gc ON tr_sales_order_gc.no_spk_gc = tr_spk_gc.no_spk_gc
              INNER JOIN tr_sales_order_gc_nosin ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
              INNER JOIN tr_scan_barcode on tr_scan_barcode.no_mesin  = tr_sales_order_gc_nosin.no_mesin  
              INNER JOIN tr_spk_gc_kendaraan ON tr_spk_gc.no_spk_gc = tr_spk_gc_kendaraan.no_spk_gc and tr_scan_barcode.tipe_motor = tr_spk_gc_kendaraan.id_tipe_kendaraan       
              INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc and tr_scan_barcode.tipe_motor = tr_spk_gc_detail.id_tipe_kendaraan       
              INNER JOIN tr_penerimaan_unit_dealer_detail ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_sales_order_gc_nosin.no_mesin 
              INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
              INNER JOIN tr_prospek_gc ON tr_spk_gc.id_prospek_gc = tr_prospek_gc.id_prospek_gc
              INNER JOIN ms_karyawan_dealer ON tr_prospek_gc.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer
              WHERE tr_penerimaan_unit_dealer_detail.retur = 0 AND tr_sales_order_gc.id_dealer = '$id_dealer' 
		          AND tr_sales_order_gc.tgl_cetak_invoice BETWEEN '$tanggal2' AND '$tanggal3' $query
              GROUP BY tr_sales_order_gc_nosin.no_mesin");

              //  IS NOT NULL AND LEFT(tr_sales_order_gc.created_at,10) 

              foreach ($sql->result() as $row) {
                // $cek = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $row->nosin);
                // $item = ($cek->num_rows() > 0) ? $cek->row()->id_item : "";
                echo "
              <tr>
                <td align='center'>$no</td>                
                <td>$row->id_sales_order_gc</td>
                <td>" . substr($row->tgl_so, 0, 10) . "</td>
                <td>$row->no_invoice</td>
                <td>" . substr($row->tgl_cetak_invoice, 0, 10) . "</td>
                <td>$row->nosin</td>
                <td>$row->salesman</td>
                <td>$row->jenis_beli</td>
                <td>Group Customer</td>
                <td>$row->nama_npwp</td>
                <td>$row->id_item</td>
                <td align='right'>" . mata_uang3($harga2 = $row->harga) . "</td>
                <td align='right'>" . mata_uang3($disc2 = $row->nilai_voucher) . "</td>
                <td align='right'>" . mata_uang3($disc3 = $row->voucher_tambahan) . "</td>
                <td align='right'>" . mata_uang3($bbn2 = $row->biaya_bbn) . "</td>
                <td align='right'>" . mata_uang3($harga_akhir2 = $harga2 - $disc2 + $bbn2) . "</td>
              </tr>
            ";
                $no++;
                $total_harga  += $harga2;
                $total_discount1  += $disc2;
                $total_discount2  += $disc3;
                $total_bbn  += $bbn2;
                $total_harga_akhir    += $harga_akhir2;
              }
              ?>
            </tr>
            <tr>
              <td colspan="11"></td>
              <td bgcolor='yellow' class='bold text-center' width='6%'><?php echo mata_uang3($total_harga) ?></td>
              <td bgcolor='yellow' class='bold text-center' width='6%'><?php echo mata_uang3($total_discount1) ?></td>
              <td bgcolor='yellow' class='bold text-center' width='6%'><?php echo mata_uang3($total_discount2) ?></td>
              <td bgcolor='yellow' class='bold text-center' width='6%'><?php echo mata_uang3($total_bbn) ?></td>
              <td bgcolor='yellow' class='bold text-center' width='6%'><?php echo mata_uang3($total_harga_akhir) ?></td>
            </tr>
          </table> <br>

          <table>
                <tr>
                    <td colspan ="2" style='font-size: 13px;'>Dibuat Oleh,</td>
                    <td style='font-size: 13px;padding: left 30px;'>Diperiksa Oleh,</td>
                    <td style='font-size: 13px;padding: left 30px;'>Diketahui Oleh,</td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                
                <tr>
                    <td colspan ="2">__________________________</td>
                    <td style='padding: left 30px;'>__________________________</td>
                    <td style='padding: left 30px;'>__________________________</td>
                </tr>
                <tr>
                    <td style='font-size: 13px;' colspan ="2">Admin H1</td>
                    <td style='font-size: 13px;padding: left 30px;'>Finance</td>
                    <td style='font-size: 13px;padding: left 30px;'>Kacab</td>
                </tr>
          </table>
        <?php } else { ?>
          <p>Tanggal SPK Harus ditentukan dulu.</p>
        <?php } ?>
      </body>

      </html>
    <?php } ?>
  </section>
</div>


<script>
  function getReport() {
    var value = {
      tanggal2: document.getElementById("tanggal2").value,
      tanggal3: document.getElementById("tanggal3").value,
      id_gudang: document.getElementById("id_gudang").value,
      id_karyawan_dealer: document.getElementById("id_karyawan_dealer").value,
      id_tipe_kendaraan: document.getElementById("id_tipe_kendaraan").value,
      id_warna: document.getElementById("id_warna").value,
      jenis_penjualan: document.getElementById("jenis_penjualan").value,
      cetak: 'cetak',
      //tipe:getRadioVal(document.getElementById("frm"),"tipe"),
    }

    if (value.tanggal2 == '' && value.tanggal3 == '') {
      alert('Isi data dengan lengkap ..!');
      return false;
    } else {
      //alert(value.tipe);
      $('.loader').show();
      $('#btnShow').disabled;
      $("#showReport").attr("src", '<?php echo site_url("dealer/laporan_penjualan_unit?") ?>cetak=' + value.cetak + '&tanggal2=' + value.tanggal2 + '&tanggal3=' + value.tanggal3 + '&id_gudang=' + value.id_gudang + '&id_karyawan_dealer=' + value.id_karyawan_dealer + '&tipe=' + value.id_tipe_kendaraan + '&id_warna=' + value.id_warna + '&jenis_penjualan=' + value.jenis_penjualan);
      document.getElementById("showReport").onload = function(e) {
        $('.loader').hide();
      };
    }
  }
</script>