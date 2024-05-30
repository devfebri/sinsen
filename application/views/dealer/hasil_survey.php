<?php

function bln()
{

  $bulan = $bl = $month = date("m");

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

<body onload="auto()">

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        <?php echo $title; ?>

      </h1>

      <ol class="breadcrumb">

        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>

        <li class="">Customer</li>

        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>

      </ol>

    </section>

    <section class="content">

      <?php

      if ($set == "approve") {

        $row = $dt_hasil->row();

      ?>



        <div class="box box-default">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="dealer/hasil_survey/save_approve" method="post" enctype="multipart/form-data">

                  <div class="box-body">

                    <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>

                    <div class="form-group">

                      <input type="hidden" name="id" value="<?php echo $row->no_spk ?>">

                      <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_spk ?>" readonly class="form-control" placeholder="No SPK" name="no_spk">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->nama_konsumen ?>" readonly class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->alamat ?>" readonly class="form-control" placeholder="Alamat Konsumen" name="nama_konsumen">

                      </div>

                    </div>

                    <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>

                      <div class="col-sm-4">

                        <input type="text" readonly class="form-control" value="<?php echo "$row->id_tipe_kendaraan | $row->tipe_ahm"; ?>" placeholder="Tipe Motor" name="nama_konsumen">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">DP Gross</label>

                      <div class="col-sm-4">

                        <input type="text" class="form-control" placeholder="Nilai DP Gross" readonly value="<?php echo $row->uang_muka ?>" name="nilai_dp_gross">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo "$row->id_warna | $row->warna"; ?>" readonly class="form-control" placeholder="Warna" name="nama_konsumen">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->voucher_2 ?>" readonly class="form-control" placeholder="Nilai Voucher" name="voucher">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Harga Motor</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->harga_tunai ?>" readonly class="form-control" placeholder="Harga Motor" name="harga_motor">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->voucher_tambahan_2 ?>" readonly class="form-control" placeholder="Voucher Tamabahan" name="voucher_tambahan">

                      </div>

                    </div>



                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>

                      <div class="col-sm-4">

                        <input type="text" required class="form-control" readonly value="<?php echo $row->tenor ?>" placeholder="Tenor" name="tenor">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">DP Setor</label>

                      <div class="col-sm-4">

                        <input type="text" class="form-control" required placeholder="DP Setor" value="<?php echo $row->dp_stor ?>" name="nilai_dp">

                      </div>

                    </div>



                    <div class="form-group">

                    <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>

                    <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->finance_company ?>" required class="form-control"  readonly>
                    </div>
                    
                    <div class="col-sm-4">

                    </div>
                    </div>




                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Approve</label>

                      <div class="col-sm-4">

                      <input type="datetime-local"  class="form-control" placeholder="Tanggal Approve" name="tgl_approval" step="1" required>
                      <i>* Mohon diinput sesuai dengan Tanggal Approve dari leasing</i>
                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Tanda Jadi</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->tanda_jadi ?>" required class="form-control" placeholder="Tanda Jadi" name="tanda_jadi">

                      </div>

                    </div>

                  </div>

                  <div class="box-footer">

                    <div class="col-sm-2">

                    </div>

                    <div class="col-sm-10">

                      <button type="submit" onclick="return confirm('Are you sure to save and approve?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save and Approve</button>

                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>

                    </div>

                  </div><!-- /.box-footer -->

                </form>

              </div>

            </div>

          </div>

        </div><!-- /.box -->

        <script>
        document.querySelector('form').addEventListener('submit', function(event) {
        var input = document.querySelector('input[name="tgl_approval"]');
        var selectedDateTime = new Date(input.value); 
        var selectedHour = selectedDateTime.getHours();
          var validHoursRange1 = (selectedHour >= 7 && selectedHour <= 22); // 7 AM to 12 AM (midnight)
          if (!validHoursRange1) {
            event.preventDefault(); // Prevent form submission
            alert('Tanggal Approval hanya bisa pada jam 7 AM - 12 AM dan 1 AM - 10 AM.');
          } 
      });
      </script>




      <?php

      } elseif ($set == 'approve_gc') {

        $row = $dt_hasil->row();

      ?>

        <div class="box box-default">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey/gc">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="dealer/hasil_survey/save_approve_gc" method="post" enctype="multipart/form-data">

                  <div class="box-body">

                    <button class="btn btn-block btn-success btn-flat" disabled> HASIL SURVEY </button> <br>

                    <div class="form-group">

                      <input type="hidden" name="no_spk_gc" value="<?php echo $row->no_spk_gc ?>">

                      <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_npwp ?>" readonly class="form-control" placeholder="No NPWP" name="no_npwp">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->nama_npwp ?>" readonly class="form-control" placeholder="Nama NPWP" name="nama_npwp">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->jenis_gc ?>" readonly class="form-control" placeholder="Jenis GC" name="jenis_gc">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_telp ?>" readonly class="form-control" placeholder="No Telp Perusahaan" name="no_telp">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">tgl Berdiri Perusahaan</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->tgl_berdiri ?>" readonly class="form-control" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>

                      <div class="col-sm-4">

                        <?php

                        $id_kelurahan = $row->id_kelurahan;

                        $dt_kel       = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();

                        $kelurahan    = $dt_kel->kelurahan;

                        $kode_pos     = $dt_kel->kode_pos;

                        $id_kecamatan = $dt_kel->id_kecamatan;

                        $dt_kec       = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();

                        $kecamatan    = $dt_kec->kecamatan;

                        $id_kabupaten = $dt_kec->id_kabupaten;

                        $dt_kab       = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();

                        $kabupaten    = $dt_kab->kabupaten;

                        $id_provinsi  = $dt_kab->id_provinsi;

                        $dt_pro       = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();

                        $provinsi     = $dt_pro->provinsi;



                        ?>

                        <input type="hidden" name="id_kelurahan" value="<?php echo $row->id_kelurahan ?>" onchange="take_kec()" id="id_kelurahan">

                        <input readonly type="text" value="<?php echo $kelurahan ?>" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>

                      <div class="col-sm-4">

                        <input type="hidden" name="id_kecamatan" id="id_kecamatan">

                        <input type="text" value="<?php echo $kecamatan ?>" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan" required>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>

                      <div class="col-sm-4">

                        <input type="hidden" name="id_kabupaten" id="id_kabupaten">

                        <input type="text" value="<?php echo $kabupaten ?>" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required>

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>

                      <div class="col-sm-4">

                        <input type="hidden" name="id_provinsi" id="id_provinsi">

                        <input type="text" value="<?php echo $provinsi ?>" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" maxlength="100" readonly value="<?php echo $row->alamat ?>" placeholder="Alamat Domisili" name="alamat" id="alamat" required>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>

                      <div class="col-sm-4">

                        <input type="text" class="form-control" placeholder="Kodepos" readonly id="kodepos" name="kodepos" value="<?php echo $row->kodepos ?>" required>

                      </div>

                    </div>



                    <button class="btn btn-block btn-danger btn-flat" disabled> Data Kendaraan </button> <br>

                    <table class="table table-bordered table-hover myTable1">

                      <thead>

                        <tr>

                          <th>Tipe Kendaraan</th>

                          <th>Warna</th>

                          <th>Qty</th>

                          <th>Total Harga Per Unit</th>

                          <th>Total Harga Per Type-Warna</th>

                        </tr>

                      </thead>

                      <tbody>

                        <?php

                        $g = 0;

                        $am = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

                          LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna                           

                          WHERE tr_spk_gc_kendaraan.no_spk_gc = '$row->no_spk_gc'");

                        foreach ($am->result() as $isi) {

                          echo "

                        <tr>

                          <td>$isi->tipe_ahm</td>

                          <td>$isi->warna</td>

                          <td>$isi->qty</td>

                          <td>" . mata_uang2($isi->total_unit) . "</td>

                          <td>" . mata_uang2($isi->total_harga) . "</td>

                        </tr>";

                          $g += $isi->total_harga;
                        }

                        ?>

                        <tr>

                          <td colspan="4"></td>

                          <td><?php echo mata_uang2($g) ?></td>

                        </tr>

                      </tbody>

                    </table>



                    <br>

                    <button class="btn btn-block btn-warning btn-flat" disabled> Penanggung Jawab </button> <br>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab</label>

                      <div class="col-sm-4">

                        <input type="text" id="nama_penanggung_jawab" readonly value="<?php echo $row->nama_penanggung_jawab ?>" class="form-control" placeholder="Nama Penanggung Jawab" name="nama_penanggung_jawab">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                      <div class="col-sm-4">

                        <input type="text" id="email" value="<?php echo $row->email ?>" readonly class="form-control" placeholder="Email" name="email">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>

                      <div class="col-sm-4">

                        <input type="text" id="no_hp" value="<?php echo $row->no_hp ?>" readonly class="form-control" placeholder="No HP" name="no_hp">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Status HP</label>

                      <div class="col-sm-4">

                        <select class="form-control" disabled name="status_nohp" required>

                          <option value="<?php echo $row->status_nohp ?>">

                            <?php

                            $dt_cust    = $this->m_admin->getByID("ms_status_hp", "id_status_hp", $row->status_nohp)->row();

                            if (isset($dt_cust)) {

                              echo $dt_cust->status_hp;
                            } else {

                              echo "- choose -";
                            }

                            ?>

                          </option>

                        </select>

                      </div>

                    </div>

                    <div class="form-group">

                      <table class="table table-bordered table-hover myTable1">

                        <thead>

                          <tr>

                            <th>Tipe - Warna</th>

                            <th>Qty</th>

                            <th>Harga Satuan</th>

                            <th>Biaya BBN</th>

                            <th>Nilai Voucher</th>

                            <th>Voucher Tambahan</th>

                            <th>DP Stor</th>

                            <th>Angsuran</th>

                            <th>Tenor</th>

                            <th>Total</th>

                          </tr>

                        </thead>

                        <tbody>

                          <?php

                          $am = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

                          LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna                           

                          WHERE tr_spk_gc_kendaraan.no_spk_gc = '$row->no_spk_gc'");

                          foreach ($am->result() as $isi) {

                            $amb = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE no_spk_gc = '$isi->no_spk_gc' AND id_tipe_kendaraan = '$isi->id_tipe_kendaraan'

                              AND id_warna = '$isi->id_warna'");

                            if ($amb->num_rows() > 0) {

                              $r = $amb->row();

                              $harga = $r->harga;

                              $biaya_bbn = $r->biaya_bbn;

                              $nilai_voucher = $r->nilai_voucher;

                              $voucher_tambahan = $r->voucher_tambahan;

                              $dp_stor = $r->dp_stor;

                              $angsuran = $r->angsuran;

                              $tenor = $r->tenor;

                              $total = $r->total;
                            } else {

                              $harga = 0;

                              $biaya_bbn = 0;

                              $nilai_voucher = 0;

                              $voucher_tambahan = 0;

                              $dp_stor = 0;

                              $angsuran = 0;

                              $tenor = 0;

                              $total = 0;
                            }

                            echo "

                          <tr>

                            <td>$isi->tipe_ahm $isi->warna</td> 

                            <td>$isi->qty</td> 

                            <td>" . mata_uang2($harga) . "</td> 

                            <td>" . mata_uang2($biaya_bbn) . "</td> 

                            <td>" . mata_uang2($nilai_voucher) . "</td> 

                            <td>" . mata_uang2($voucher_tambahan) . "</td> 

                            <td>" . mata_uang2($dp_stor) . "</td> 

                            <td>" . mata_uang2($angsuran) . "</td> 

                            <td>$tenor</td> 

                            <td>" . mata_uang2($total) . "</td> 

                          </tr>

                        ";
                          }

                          ?>

                        </tbody>

                      </table>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Approve</label>

                      <div class="col-sm-4">

                        <input type="text" id="tanggal1" class="form-control" placeholder="Tgl Approve" name="tgl_approve">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" placeholder="Keterangan" name="keterangan">

                      </div>

                    </div>

                  </div>

                  <div class="box-footer">

                    <div class="col-sm-2">

                    </div>

                    <div class="col-sm-10">

                      <button type="submit" onclick="return confirm('Are you sure to save and approve?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save and Approve</button>

                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>

                    </div>

                  </div><!-- /.box-footer -->

                </form>

              </div>

            </div>

          </div>

        </div><!-- /.box -->



      <?php

      } elseif ($set == 'reject_gc') {

        $row = $dt_hasil->row();



      ?>



        <div class="box box-default">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey/gc">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="dealer/hasil_survey/save_reject_gc" method="post" enctype="multipart/form-data">

                  <div class="box-body">

                    <button class="btn btn-block btn-success btn-flat" disabled> HASIL SURVEY </button> <br>

                    <div class="form-group">

                      <input type="hidden" name="no_spk_gc" value="<?php echo $row->no_spk_gc ?>">

                      <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_npwp ?>" readonly class="form-control" placeholder="No NPWP" name="no_npwp">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama NPWP</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->nama_npwp ?>" readonly class="form-control" placeholder="Nama NPWP" name="nama_npwp">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis GC</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->jenis_gc ?>" readonly class="form-control" placeholder="Jenis GC" name="jenis_gc">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">No Telp Perusahaan</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_telp ?>" readonly class="form-control" placeholder="No Telp Perusahaan" name="no_telp">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">tgl Berdiri Perusahaan</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->tgl_berdiri ?>" readonly class="form-control" placeholder="Tgl Berdiri Perusahaan" name="tgl_berdiri">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan Domisili *</label>

                      <div class="col-sm-4">

                        <?php

                        $id_kelurahan = $row->id_kelurahan;

                        $dt_kel       = $this->db->query("SELECT * FROM ms_kelurahan WHERE id_kelurahan = '$id_kelurahan'")->row();

                        $kelurahan    = $dt_kel->kelurahan;

                        $kode_pos     = $dt_kel->kode_pos;

                        $id_kecamatan = $dt_kel->id_kecamatan;

                        $dt_kec       = $this->db->query("SELECT * FROM ms_kecamatan WHERE id_kecamatan = '$id_kecamatan'")->row();

                        $kecamatan    = $dt_kec->kecamatan;

                        $id_kabupaten = $dt_kec->id_kabupaten;

                        $dt_kab       = $this->db->query("SELECT * FROM ms_kabupaten WHERE id_kabupaten = '$id_kabupaten'")->row();

                        $kabupaten    = $dt_kab->kabupaten;

                        $id_provinsi  = $dt_kab->id_provinsi;

                        $dt_pro       = $this->db->query("SELECT * FROM ms_provinsi WHERE id_provinsi = '$id_provinsi'")->row();

                        $provinsi     = $dt_pro->provinsi;



                        ?>

                        <input type="hidden" name="id_kelurahan" value="<?php echo $row->id_kelurahan ?>" onchange="take_kec()" id="id_kelurahan">

                        <input readonly type="text" value="<?php echo $kelurahan ?>" onpaste="return false" onkeypress="return nihil(event)" name="kelurahan" data-toggle="modal" placeholder="Kelurahan Domisili" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan Domisili</label>

                      <div class="col-sm-4">

                        <input type="hidden" name="id_kecamatan" id="id_kecamatan">

                        <input type="text" value="<?php echo $kecamatan ?>" class="form-control" readonly id="kecamatan" placeholder="Kecamatan Domisili" name="kecamatan" required>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten Domisili</label>

                      <div class="col-sm-4">

                        <input type="hidden" name="id_kabupaten" id="id_kabupaten">

                        <input type="text" value="<?php echo $kabupaten ?>" class="form-control" readonly placeholder="Kota/Kabupaten Domisili" id="kabupaten" name="kabupaten" required>

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Provinsi Domisili</label>

                      <div class="col-sm-4">

                        <input type="hidden" name="id_provinsi" id="id_provinsi">

                        <input type="text" value="<?php echo $provinsi ?>" class="form-control" readonly placeholder="Provinsi Domisili" id="provinsi" name="provinsi" required>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Domisili *</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" maxlength="100" readonly value="<?php echo $row->alamat ?>" placeholder="Alamat Domisili" name="alamat" id="alamat" required>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Kodepos *</label>

                      <div class="col-sm-4">

                        <input type="text" class="form-control" placeholder="Kodepos" readonly id="kodepos" name="kodepos" value="<?php echo $row->kodepos ?>" required>

                      </div>

                    </div>

                    <button class="btn btn-block btn-warning btn-flat" disabled> Penanggung Jawab </button> <br>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Penanggung Jawab</label>

                      <div class="col-sm-4">

                        <input type="text" id="nama_penanggung_jawab" readonly value="<?php echo $row->nama_penanggung_jawab ?>" class="form-control" placeholder="Nama Penanggung Jawab" name="nama_penanggung_jawab">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>

                      <div class="col-sm-4">

                        <input type="text" id="email" value="<?php echo $row->email ?>" readonly class="form-control" placeholder="Email" name="email">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>

                      <div class="col-sm-4">

                        <input type="text" id="no_hp" value="<?php echo $row->no_hp ?>" readonly class="form-control" placeholder="No HP" name="no_hp">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Status HP</label>

                      <div class="col-sm-4">

                        <select class="form-control" disabled name="status_nohp" required>

                          <option value="<?php echo $row->status_nohp ?>">

                            <?php

                            $dt_cust    = $this->m_admin->getByID("ms_status_hp", "id_status_hp", $row->status_nohp)->row();

                            if (isset($dt_cust)) {

                              echo $dt_cust->status_hp;
                            } else {

                              echo "- choose -";
                            }

                            ?>

                          </option>

                        </select>

                      </div>

                    </div>



                    <div class="form-group">

                      <table class="table table-bordered table-hover myTable1">

                        <thead>

                          <tr>

                            <th>Tipe Kendaraan</th>

                            <th>Warna</th>

                            <th>Qty</th>

                            <th>Total Harga Per Unit</th>

                            <th>Total Harga Per Type-Warna</th>

                          </tr>

                        </thead>

                        <tbody>

                          <?php

                          $am = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

                          LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna                           

                          WHERE tr_spk_gc_kendaraan.no_spk_gc = '$row->no_spk_gc'");

                          foreach ($am->result() as $isi) {

                            echo "

                          <tr>

                            <td>$isi->tipe_ahm</td> 

                            <td>$isi->warna</td> 

                            <td>$isi->qty</td> 

                            <td>" . mata_uang2($isi->total_unit) . "</td> 

                            <td>" . mata_uang2($isi->total_harga) . "</td>                             

                          </tr>

                        ";
                          }

                          ?>

                        </tbody>

                      </table>

                    </div>



                    <div class="form-group">

                      <table class="table table-bordered table-hover myTable1">

                        <thead>

                          <tr>

                            <th>Tipe - Warna</th>

                            <th>Qty</th>

                            <th>Harga Satuan</th>

                            <th>Biaya BBN</th>

                            <th>Nilai Voucher</th>

                            <th>Voucher Tambahan</th>

                            <th>DP Stor</th>

                            <th>Angsuran</th>

                            <th>Tenor</th>

                            <th>Total</th>

                          </tr>

                        </thead>

                        <tbody>

                          <?php

                          $am = $this->db->query("SELECT * FROM tr_spk_gc_kendaraan LEFT JOIN ms_tipe_kendaraan ON tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan

                          LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna                           

                          WHERE tr_spk_gc_kendaraan.no_spk_gc = '$row->no_spk_gc'");

                          foreach ($am->result() as $isi) {

                            $amb = $this->db->query("SELECT * FROM tr_spk_gc_detail WHERE no_spk_gc = '$isi->no_spk_gc' AND id_tipe_kendaraan = '$isi->id_tipe_kendaraan'")->row();

                            echo "

                          <tr>

                            <td>$isi->tipe_ahm $isi->warna</td> 

                            <td>$isi->qty</td> 

                            <td>" . mata_uang2($amb->harga) . "</td> 

                            <td>" . mata_uang2($amb->biaya_bbn) . "</td> 

                            <td>" . mata_uang2($amb->nilai_voucher) . "</td> 

                            <td>" . mata_uang2($amb->voucher_tambahan) . "</td> 

                            <td>" . mata_uang2($amb->dp_stor) . "</td> 

                            <td>" . mata_uang2($amb->angsuran) . "</td> 

                            <td>$amb->tenor</td> 

                            <td>" . mata_uang2($amb->total) . "</td> 

                          </tr>

                        ";
                          }

                          ?>

                        </tbody>

                      </table>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Reject</label>

                      <div class="col-sm-4">

                        <input type="text" id="tanggal1" class="form-control" placeholder="Tgl Reject" name="tgl_approve">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>

                      <div class="col-sm-10">

                        <input type="text" class="form-control" placeholder="Keterangan" name="keterangan">

                      </div>

                    </div>

                  </div>

                  <div class="box-footer">

                    <div class="col-sm-2">

                    </div>

                    <div class="col-sm-10">

                      <button type="submit" onclick="return confirm('Are you sure to save and reject?')" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-save"></i> Save and Reject</button>

                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>

                    </div>

                  </div><!-- /.box-footer -->

                </form>

              </div>

            </div>

          </div>

        </div><!-- /.box -->



      <?php

      } elseif ($set == "reject") {

        $row = $dt_hasil->row();

      ?>



        <div class="box box-default">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="dealer/hasil_survey/save_reject" method="post" enctype="multipart/form-data">

                  <div class="box-body">

                    <div class="form-group">

                      <input type="hidden" name="id" value="<?php echo $row->no_spk ?>">

                      <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_spk ?>" readonly class="form-control" placeholder="No SPK" name="no_spk">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->nama_konsumen ?>" readonly class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->alamat ?>" readonly class="form-control" placeholder="Alamat Konsumen" name="alamat_konsumen">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>

                      <div class="col-sm-4">

                        <input type="text" readonly class="form-control" value="<?php echo "$row->id_tipe_kendaraan | $row->tipe_ahm"; ?>" placeholder="Tipe Motor" name="nama_konsumen">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo "$row->id_warna | $row->warna"; ?>" readonly class="form-control" placeholder="Warna" name="nama_konsumen">

                      </div>

                    </div>
                    <!-- testing reject -->

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Leasing</label>

                      <div class="col-sm-4">
                      <input type="text" value="<?php echo $row->finance_company ?>" required class="form-control"  readonly>
                      </div>

                      <div class="col-sm-4">

                      </div>
                      </div>

                    
                    <div class="form-group">
                      
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Reject</label>

                        <div class="col-sm-4">
                        <input type="datetime-local"  class="form-control" placeholder="Tanggal Approve" name="tgl_approval" step="1" required>
                         <i>*Mohon diinput sesuai dengan Tgl approval dari leasing, sebagai bentuk tarikan report </i>
                        </div> 


                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>

                      <div class="col-sm-10">

                        <input type="text" required class="form-control" placeholder="Alasan Reject" name="alasan">

                      </div>

                    </div>

                  </div>

                  <div class="box-footer">

                    <div class="col-sm-2">

                    </div>

                    <div class="col-sm-10">

                      <button type="submit" onclick="return confirm('Are you sure to save and reject?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save and Reject</button>

                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>

                    </div>

                  </div><!-- /.box-footer -->

                </form>

              </div>

            </div>
          </div>
        </div><!-- /.box -->

        <script>
        document.querySelector('form').addEventListener('submit', function(event) {
        var input = document.querySelector('input[name="tgl_approval"]');
        var selectedDateTime = new Date(input.value); 
        var selectedHour = selectedDateTime.getHours();
          var validHoursRange1 = (selectedHour >= 7 && selectedHour <= 22); 
          if (!validHoursRange1) {
            event.preventDefault(); // Prevent form submission
            alert('Tanggal Approval hanya bisa pada jam 7 AM - 12 AM dan 1 AM - 10 AM.');
          } 
      });
      </script>


      <?php
      } elseif ($set == "view") {
      ?>

        <div class="box">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey/history">

                <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>

              </a>

              <a href="dealer/hasil_survey/gc">

                <button class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Group Customer</button>

              </a>

              <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <div class="table-responsive">
              <table id="example2" class="table table-bordered table-hover">

                <thead>

                  <tr>

                    <!--th width="1%"><input type="checkbox" id="check-all"></th-->

                    <th width="5%">No</th>

                    <th>No SPK</th>

                    <th>Nama Konsumen</th>

                    <th>Alamat</th>

                    <th>Leasing</th>

                    <th>Harga Motor</th>

                    <th>Tanda Jadi</th>

                    <th>DP Setor</th>

                    <th>Tenor</th>

                    <th>Tgl Approval</th>

                    <th>Status Approval</th>

                    <th>Action</th>

                  </tr>

                </thead>

                <tbody>

                  <?php

                  $no = 1;

                  foreach ($dt_hasil_survey->result() as $row) {

                    $s = $this->db->query("SELECT tr_prospek.nama_konsumen,tr_spk.* FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer=tr_prospek.id_customer 

                  WHERE tr_spk.no_spk = '$row->no_spk'");

                    if ($s->num_rows() > 0) {

                      $rt       = $s->row();

                      $nama     = $rt->nama_konsumen;

                      $alamat   = $rt->alamat;

                      $id_f     = $rt->id_finance_company;

                      $id_tipe  = $rt->id_tipe_kendaraan;

                      $id_warna = $rt->id_warna;

                      $harga_jual_f = $rt->harga_tunai;

                      $dp_stor_f = $rt->dp_stor;

                      $tenor_f   = $rt->tenor;

                      $uang_muka_f = $rt->uang_muka;
                      $tanda_jadi = $rt->tanda_jadi;
                    } else {

                      $nama     = "";

                      $alamat   = "";

                      $id_f     = "";

                      $id_tipe  = "";

                      $id_warna = "";

                      $harga_jual_f = "";

                      $dp_stor_f = "";

                      $tenor_f = "";

                      $uang_muka_f = "";
                      $tanda_jadi = '';
                    }



                    $r = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $id_f);

                    if ($r->num_rows() > 0) {

                      $tr = $r->row();

                      $leasing = $tr->finance_company;
                    } else {

                      $leasing = "";
                    }



                    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_kelompok_md ON ms_item.id_item = ms_kelompok_md.id_item 

                        WHERE ms_item.id_tipe_kendaraan = '$id_tipe' AND ms_item.id_warna = '$id_warna'");

                    if ($item->num_rows() > 0) {

                      $rr = $item->row();

                      $id_item  = $rr->id_item;

                      $harga_jual    = $rr->harga_jual;
                    } else {

                      $id_item    = "";

                      $harga_jual = "0";
                    }



                    $cek = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk' AND status_spk <> 'lama'");

                    if ($cek->num_rows() > 0) {

                      $rt = $cek->row();

                      $tanda_jadi = $rt->tanda_jadi;

                      $tenor = $rt->tenor;

                      $tgl_approval = $rt->tgl_approval;

                      $status = $rt->status_approval;

                      $tombol = "";
                    } else {

                      $tanda_jadi = 0;

                      $tenor = "";

                      $tgl_approval = "";

                      $status = "input";

                      $tombol = "<a href='dealer/hasil_survey/approve?id=$row->no_spk'>

                    <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button>

                  </a>

                  <a href='dealer/hasil_survey/reject?id=$row->no_spk'>

                    <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Reject</button>

                  </a>";
                    }



                    if ($status == 'approved') {

                      $status2 = "<span class='label label-success'>Approved</span>";
                    } elseif ($status == 'rejected') {

                      $status2 = "<span class='label label-danger'>Rejected</span>";
                    } else {

                      $status2 = "<span class='label label-primary'>Proses</span>";
                    }





                    $harga_jual_f = $harga_jual_f > 0 ? mata_uang2($harga_jual_f) : 0;

                    // if(is_numeric($harga_jual_f)){

                    //   $harga_jual_f = $harga_jual_f;

                    // }else{

                    //   $harga_jual_f = 0;

                    // }

                    $tanda_jadi = $tanda_jadi > 0 ? mata_uang2($tanda_jadi) : 0;

                    // if(is_numeric($tanda_jadi)){

                    //   $tanda_jadi = $tanda_jadi;

                    // }else{

                    //   $tanda_jadi = 0;

                    // }

                    $uang_muka_f = $uang_muka_f > 0 ? mata_uang2($uang_muka_f) : 0;

                    // if(is_numeric($uang_muka_f)){

                    //   $uang_muka_f = $uang_muka_f;

                    // }else{

                    //   $uang_muka_f = 0;

                    // }



                    if ($status != 'approved') {

                      echo "

                <tr>

                  <td>$no</td>

                  <td>$row->no_spk</td>

                  <td>$nama</td>              

                  <td>$alamat</td>

                  <td>$leasing</td>

                  <td>" . $harga_jual_f . "</td>                            

                  <td>" . $tanda_jadi . "</td>                                          

                  <td>" . $uang_muka_f . "</td>

                  <td>$tenor_f</td>                            

                  <td>$tgl_approval</td>

                  <td>$status2</td>                                          

                  <td>                                

                    $tombol

                  </td>

                </tr>

                ";

                      $no++;
                    }
                  }

                  ?>

                </tbody>

              </table>
            </div>
          </div><!-- /.box-body -->

        </div><!-- /.box -->



      <?php

      } elseif ($set == "view_new") {

        ?>
  
          <div class="box">
  
            <div class="box-header with-border">
  
              <h3 class="box-title">
  
                <a href="dealer/hasil_survey/history">
  
                  <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>
  
                </a>
  
                <a href="dealer/hasil_survey/gc">
  
                  <button class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Group Customer</button>
  
                </a>
  
                <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->
  
              </h3>
  
              <div class="box-tools pull-right">
  
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
  
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
  
              </div>
  
            </div><!-- /.box-header -->
  
            <div class="box-body">
  
              <?php
  
              if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
  
              ?>
  
                <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
  
                  <strong><?php echo $_SESSION['pesan'] ?></strong>
  
                  <button class="close" data-dismiss="alert">
  
                    <span aria-hidden="true">&times;</span>
  
                    <span class="sr-only">Close</span>
  
                  </button>
  
                </div>
  
              <?php
  
              }
  
              $_SESSION['pesan'] = '';
              ?>
                <table id="table_hasil_survey_dealer" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <!--th width="1%"><input type="checkbox" id="check-all"></th-->  
                        <th width="5%">No</th>  
                        <th>No SPK</th>  
                        <th>Nama Konsumen</th>  
                        <th>Alamat</th>  
                        <th>Leasing</th>  
                        <th>Harga Motor</th>  
                        <th>Tanda Jadi</th>  
                        <th>DP Setor</th>  
                        <th>Tenor</th>  
                        <th>Tgl Approval</th>  
                        <th>Status Approval</th>  
                        <th>Action</th>  
                      </tr>  
                   </thead>
  
                  <tbody>
                  </tbody>
  
                </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        <?php
  
        } elseif ($set == "view_gc") {
      ?>


        <div class="box">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey/history_gc">

                <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History GC</button>

              </a>

              <a href="dealer/hasil_survey">

                <button class="btn btn-warning btn-flat margin"><i class="fa fa-users"></i> Individu</button>

              </a>

              <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <table id="example2" class="table table-bordered table-hover">

              <thead>

                <tr>

                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->

                  <th width="5%">No</th>

                  <th>No SPK</th>

                  <th>No NPWP</th>

                  <th>Nama NPWP</th>

                  <th>Alamat</th>

                  <th>Finance Company</th>

                  <th>Status</th>

                  <th>Action</th>

                </tr>

              </thead>

              <tbody>

                <?php

                $no = 1;

                foreach ($dt_hasil_survey->result() as $row) {

                  $s = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$row->no_spk_gc'");

                  if ($s->num_rows() > 0) {

                    $rt       = $s->row();

                    $nama_npwp = $rt->nama_npwp;

                    $no_npwp = $rt->no_npwp;

                    $alamat   = $rt->alamat;

                    $id_f     = $rt->id_finance_company;

                  } else {

                    $nama     = "";

                    $alamat   = "";

                    $nama_npwp = "";

                    $no_npwp = "";

                    $id_f     = "";
                  }


                  $r = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $id_f);

                  if ($r->num_rows() > 0) {

                    $tr = $r->row();

                    $leasing = $tr->finance_company;
                  } else {

                    $leasing = "";
                  }



                  $cek = $this->db->query("SELECT * FROM tr_hasil_survey_gc WHERE no_spk_gc = '$row->no_spk_gc' AND status_spk_gc <> 'lama'");

                  if ($cek->num_rows() > 0) {

                    $rt = $cek->row();

                    $tanda_jadi = $rt->tanda_jadi;

                    $tenor = $rt->tenor;

                    $tgl_approval = $rt->tgl_approval;

                    $status = $rt->status_approval;

                    $tombol = "";
                  } else {

                    $tanda_jadi = 0;

                    $tenor = "";

                    $tgl_approval = "";

                    $status = "input";

                    $tombol = "<a href='dealer/hasil_survey/approve_gc?id=$row->no_spk_gc'>

                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button>

                </a>

                <a href='dealer/hasil_survey/reject_gc?id=$row->no_spk_gc'>

                  <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Reject</button>

                </a>";
                  }



                  if ($status == 'approved') {

                    $status2 = "<span class='label label-success'>Approved</span>";
                  } elseif ($status == 'rejected') {

                    $status2 = "<span class='label label-danger'>Rejected</span>";
                  } else {

                    $status2 = "<span class='label label-primary'>Proses</span>";
                  }




                  if ($status != 'approved') {

                    echo "

              <tr>

                <td>$no</td>

                <td>$row->no_spk_gc</td>

                <td>$no_npwp</td>              

                <td>$nama_npwp</td>              

                <td>$alamat</td>

                <td>$leasing</td>                

                <td>$status2</td>                                          

                <td>                                

                  $tombol

                </td>

              </tr>

              ";

                    $no++;
                  }
                }

                ?>

              </tbody>

            </table>

          </div><!-- /.box-body -->

        </div><!-- /.box -->



      <?php

      } elseif ($set == "history") {

      ?>



        <div class="box">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

              <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <table id="example2" class="table table-bordered table-hover">

              <thead>

                <tr>

                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->

                  <th width="5%">No</th>

                  <th>No SPK</th>

                  <th>Nama Konsumen</th>

                  <th>Alamat</th>

                  <th>Leasing</th>

                  <th>Harga Motor</th>

                  <th>Tanda Jadi</th>

                  <th>DP Setor</th>

                  <th>Tenor</th>

                  <th>Tgl Approval</th>

                  <th>Status Approval</th>

                </tr>

              </thead>

              <tbody>

                <?php

                $no = 1;

                foreach ($dt_hasil_survey->result() as $row) {


                  $r = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $row->id_finance_company);

                  if ($r->num_rows() > 0) {

                    $tr = $r->row();

                    $leasing = $tr->finance_company;
                  } else {

                    $leasing = "";
                  }


                  $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_kelompok_md ON ms_item.id_item = ms_kelompok_md.id_item 

                      WHERE ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan' AND ms_item.id_warna = '$row->id_warna'");

                  if ($item->num_rows() > 0) {

                    $rr = $item->row();

                    $id_item  = $rr->id_item;

                    $harga_jual    = $rr->harga_jual;
                  } else {

                    $id_item    = "";

                    $harga_jual = "0";
                  }



                  if ($row->status_approval == 'approved') {

                    $status2 = "<span class='label label-success'>Approved</span>";
                  } elseif ($row->status_approval == 'rejected') {

                    $status2 = "<span class='label label-danger'>Rejected</span>";
                  } else {

                    $status2 = "<span class='label label-primary'>Proses</span>";
                  }



                  $cek_c = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk' AND no_order_survey = '$row->no_order_survey'");

                  if ($cek_c->num_rows() > 0) {

                    $tu = $cek_c->row();

                    $harga_jualan = $tu->harga_motor;

                    $uang_muka = $tu->nilai_dp;

                    $tanda_jadi = $tu->tanda_jadi;

                    $tgl_approval = $tu->tgl_approval;
                  } else {

                    $harga_jualan = 0;

                    $uang_muka = 0;

                    $tanda_jadi = 0;

                    $tgl_approval = "";
                  }



                  $harga_jual_f = $row->harga_tunai;

                  if (is_numeric($harga_jual_f)) {

                    $harga_jual_f = $harga_jual_f > 0 ? mata_uang2($harga_jual_f) : 0;

                    $harga_jual_f = $harga_jual_f;
                  } else {

                    $harga_jual_f = 0;
                  }

                  if (is_numeric($tanda_jadi)) {

                    $tanda_jadi = $tanda_jadi > 0 ? mata_uang2($tanda_jadi) : 0;

                    $tanda_jadi = $tanda_jadi;
                  } else {

                    $tanda_jadi = 0;
                  }

                  $uang_muka_f = $row->uang_muka;

                  if (is_numeric($uang_muka_f)) {

                    $uang_muka_f = $uang_muka_f > 0 ? mata_uang2($uang_muka_f) : 0;

                    $uang_muka_f = $uang_muka_f;
                  } else {

                    $uang_muka_f = 0;
                  }

                  echo "

            <tr>

              <td>$no</td>

              <td>$row->no_spk</td>

              <td>$row->nama_konsumen</td>              

              <td>$row->alamat</td>

              <td>$leasing</td>

              <td>" . $harga_jual_f . "</td>                            

              <td>" . $tanda_jadi . "</td>                                          

              <td>" . $uang_muka_f . "</td>

              <td>$row->tenor</td>                            

              <td>$tgl_approval</td>

              <td>$status2</td>                                                        

            </tr>

            ";

                  $no++;
                }

                ?>

              </tbody>

            </table>

          </div><!-- /.box-body -->

        </div><!-- /.box -->



      <?php

      }
      // testing
      elseif ($set == "history_serverside") {
        ?>
          <div class="box">
  
            <div class="box-header with-border">
  
              <h3 class="box-title">
  
                <a href="dealer/hasil_survey">
  
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
  
                </a>
  
                <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->
  
              </h3>
  
              <div class="box-tools pull-right">
  
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
  
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
  
              </div>
  
            </div><!-- /.box-header -->
  
            <div class="box-body">
  
              <?php
  
              if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
  
              ?>
  
                <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
  
                  <strong><?php echo $_SESSION['pesan'] ?></strong>
  
                  <button class="close" data-dismiss="alert">
  
                    <span aria-hidden="true">&times;</span>
  
                    <span class="sr-only">Close</span>
  
                  </button>
  
                </div>
  
              <?php
              }
              $_SESSION['pesan'] = '';
              ?>
  
              <table  id="table_hasil_survey_history" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                    <th width="5%">No</th>
                    <th>No SPK</th>
                    <th>Nama Konsumen</th>
                    <th>Alamat</th>
                    <th>Leasing</th>
                    <th>Harga Motor</th>
                    <th>Tanda Jadi</th>
                    <th>DP Setor</th>
                    <th>Tenor</th>
                    <th>Tgl Approval</th>
                    <th>Status Approval</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
          <script>
            $( document ).ready(function() {
            $('#table_hasil_survey_history').DataTable({
                  "scrollX": true,
                  "processing": true, 
                  "bDestroy": true,
                  "serverSide": true, 
                  "order": [],
                  "ajax": {
                    "url": "<?php  echo site_url('dealer/hasil_survey/fetchData')?>",
                      "type": "POST"
                  },  
                  "columnDefs": [
                  {
                      "targets": [ 0,5 ],
                      "orderable": false, 
                  },
                  ],
                  });
          });
          </script>

        <?php
  
        }

       elseif ($set == "history_gc") {

      ?>



        <div class="box">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey/gc">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

              <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <table id="example2" class="table table-bordered table-hover">

              <thead>

                <tr>

                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->

                  <th width="5%">No</th>

                  <th>No SPK</th>

                  <th>No NPWP</th>

                  <th>Nama NPWP</th>

                  <th>Alamat</th>

                  <th>Finance Company</th>

                  <th>Status Approval</th>

                </tr>

              </thead>

              <tbody>

                <?php

                $no = 1;

                foreach ($dt_hasil_survey->result() as $row) {



                  $r = $this->m_admin->getByID("ms_finance_company", "id_finance_company", $row->id_finance_company);

                  if ($r->num_rows() > 0) {

                    $tr = $r->row();

                    $leasing = $tr->finance_company;
                  } else {

                    $leasing = "";
                  }



                  $s = $this->db->query("SELECT * FROM tr_spk_gc WHERE tr_spk_gc.no_spk_gc = '$row->no_spk_gc'");

                  if ($s->num_rows() > 0) {

                    $rt       = $s->row();

                    $nama_npwp = $rt->nama_npwp;

                    $no_npwp = $rt->no_npwp;

                    $alamat   = $rt->alamat;

                    $id_f     = $rt->id_finance_company;
                  } else {

                    $nama     = "";

                    $alamat   = "";

                    $id_f     = "";
                  }



                  if ($row->status_approval == 'approved') {

                    $status2 = "<span class='label label-success'>Approved</span>";
                  } elseif ($row->status_approval == 'rejected') {

                    $status2 = "<span class='label label-danger'>Rejected</span>";
                  } else {

                    $status2 = "<span class='label label-primary'>Proses</span>";
                  }



                  $cek_c = $this->db->query("SELECT * FROM tr_hasil_survey_gc WHERE no_spk_gc = '$row->no_spk_gc' AND no_order_survey_gc = '$row->no_order_survey_gc'");

                  if ($cek_c->num_rows() > 0) {

                    $tu = $cek_c->row();

                    $harga_jualan = $tu->harga_motor;

                    $uang_muka = $tu->nilai_dp;

                    $tanda_jadi = $tu->tanda_jadi;

                    $tgl_approval = $tu->tgl_approval;
                  } else {

                    $harga_jualan = 0;

                    $uang_muka = 0;

                    $tanda_jadi = 0;

                    $tgl_approval = "";
                  }



                  echo "

            <tr>

              <td>$no</td>

              <td>$row->no_spk_gc</td>

              <td>$no_npwp</td>              

              <td>$nama_npwp</td>              

              <td>$alamat</td>

              <td>$leasing</td>                

              <td>$status2</td>                                                        

            </tr>

            ";

                  $no++;
                }

                ?>

              </tbody>

            </table>

          </div><!-- /.box-body -->

        </div><!-- /.box -->


      <?php

      } elseif ($set == "notif_reject") { ?>



        <div class="box box-default">

          <div class="box-header with-border">

            <h3 class="box-title">

              <a href="dealer/hasil_survey">

                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

              </a>

            </h3>

            <div class="box-tools pull-right">

              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>

            </div>

          </div><!-- /.box-header -->

          <div class="box-body">

            <?php

            if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {

            ?>

              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">

                <strong><?php echo $_SESSION['pesan'] ?></strong>

                <button class="close" data-dismiss="alert">

                  <span aria-hidden="true">&times;</span>

                  <span class="sr-only">Close</span>

                </button>

              </div>

            <?php

            }

            $_SESSION['pesan'] = '';



            ?>

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" method="post" action="hasil_survey/send_manage" enctype="multipart/form-data">

                  <div class="box-body">

                    <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>

                    <div class="form-group">


                      <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->no_spk ?>" readonly class="form-control" placeholder="No SPK" name="no_spk">
                        <input type="hidden" name="id" value="<?php echo $row->no_spk ?>">


                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->nama_konsumen ?>" readonly class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>

                      <div class="col-sm-10">

                        <input type="text" value="<?php echo $row->alamat ?>" readonly class="form-control" placeholder="Alamat Konsumen" name="nama_konsumen">

                      </div>

                    </div>

                    <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>

                      <div class="col-sm-4">

                        <input type="text" readonly class="form-control" value="<?php echo "$row->id_tipe_kendaraan | $row->tipe_ahm"; ?>" placeholder="Tipe Motor" name="nama_konsumen">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">DP Gross</label>

                      <div class="col-sm-4">

                        <input type="text" class="form-control" placeholder="Nilai DP Gross" readonly value="<?php echo $row->uang_muka ?>" name="nilai_dp_gross">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo "$row->id_warna | $row->warna"; ?>" readonly class="form-control" placeholder="Warna" name="nama_konsumen">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->voucher_2 ?>" readonly class="form-control" placeholder="Nilai Voucher" name="voucher">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Harga Motor</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->harga_tunai ?>" readonly class="form-control" placeholder="Harga Motor" name="harga_motor">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->voucher_tambahan_2 ?>" readonly class="form-control" placeholder="Voucher Tamabahan" name="voucher_tambahan">

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>

                      <div class="col-sm-4">

                        <input type="text" required class="form-control" readonly value="<?php echo $row->tenor ?>" placeholder="Tenor" name="tenor">

                      </div>

                      <label for="inputEmail3" class="col-sm-2 control-label">DP Setor</label>

                      <div class="col-sm-4">

                        <input type="text" class="form-control" required placeholder="DP Setor" value="<?php echo $row->dp_stor ?>" name="nilai_dp" readonly>

                      </div>

                    </div>

                    <div class="form-group">

                      <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Tanda Jadi</label>

                      <div class="col-sm-4">

                        <input type="text" value="<?php echo $row->uang_muka ?>" required class="form-control" placeholder="Tanda Jadi" name="tanda_jadi" readonly>

                      </div>

                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>
                      <div class="col-sm-10">
                        <input type="text" value="<?php echo $row->keterangan ?>" required class="form-control" readonly>
                      </div>
                    </div>

                  </div>
                  <div class="box-footer">
                    <div class="col-sm-12" align="center">
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat">Update Activity Sales - Menghubungi Customer</button>
                    </div>
                  </div>
                </form>

              </div>

            </div>

          </div>

        </div><!-- /.box -->



      <?php } ?>

    </section>

  </div>







  <script type="text/javascript">
    function auto() {

      var tgl_js = document.getElementById("tgl").value;

      $.ajax({

        url: "<?php echo site_url('dealer/hasil_survey/cari_id') ?>",

        type: "POST",

        data: "tgl=" + tgl_js,

        cache: false,

        success: function(msg) {

          data = msg.split("|");

          $("#id_hasil_survey").val(data[0]);

          $("#id_customer").val(data[1]);

        }

      })

    }

    function take_sales() {

      var id_karyawan_dealer = $("#id_karyawan_dealer").val();

      $.ajax({

        url: "<?php echo site_url('dealer/hasil_survey/take_sales') ?>",

        type: "POST",

        data: "id_karyawan_dealer=" + id_karyawan_dealer,

        cache: false,

        success: function(msg) {

          data = msg.split("|");

          //$("#no_polisi").html(msg);                                                    

          $("#kode_sales").val(data[0]);

          $("#nama_sales").val(data[1]);

        }

      })
    }


    function take_kec() {

      var id_kelurahan = $("#id_kelurahan").val();

      $.ajax({

        url: "<?php echo site_url('dealer/spk/take_kec') ?>",

        type: "POST",

        data: "id_kelurahan=" + id_kelurahan,

        cache: false,

        success: function(msg) {

          data = msg.split("|");

          $("#id_kecamatan").val(data[0]);

          $("#kecamatan").val(data[1]);

          $("#id_kabupaten").val(data[2]);

          $("#kabupaten").val(data[3]);

          $("#id_provinsi").val(data[4]);

          $("#provinsi").val(data[5]);

          $("#kelurahan").val(data[6]);
        }

      })

    }
  </script>

<script>
  $( document ).ready(function() {
   tabless = $('#table_hasil_survey_dealer').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php  echo site_url('dealer/hasil_survey/fetch_data_spk_datatables')?>",
            "type": "POST"
        },  
              
        "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
        ],
        });
});
</script>