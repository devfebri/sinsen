<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
    margin-right: 0px;
  }

  .isi_combo {
    height: 30px;
    border: 1px solid #ccc;
    padding-left: 1.5px;
  }
</style>
<base href="<?php echo base_url(); ?>" />

<body onload="kirim_data_pl()">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H1</li>
        <li class="">Bussiness Control</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <?php
      if ($set == "detail") {
        $row = $program;
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/report_sales_program">
                <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID Program AHM</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="ID Program AHM" readonly class="form-control" value="<?= $row->id_program_ahm ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Periode Awal</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="Periode Awal" readonly class="form-control" value="<?= $row->periode_awal ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID Program MD</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="ID Program AHM" readonly class="form-control" value="<?= $row->id_program_md ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Periode Akhir</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_akhir" placeholder="Periode Akhir" readonly class="form-control" value="<?= $row->periode_akhir ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Judul Kegiatan</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="Judul Kegiatan" readonly class="form-control" value="<?= $row->judul_kegiatan ?>">
                      </div>
                      <!--  <label for="inputEmail3" class="col-sm-2 control-label">Draft Jutlak</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_akhir" placeholder="Draft Jutlak" readonly class="form-control">
                  </div>   -->
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Program</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="Jenis Program" readonly class="form-control" value="<?= $row->jenis ?>">
                      </div>
                      <!--  <label for="inputEmail3" class="col-sm-2 control-label">Final Jutlak</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_akhir" placeholder="Final Jutlak" readonly class="form-control">
                  </div>      -->
                    </div>
                    <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nilai Kontribusi</label>
                  <div class="col-sm-4">
                    <table border="0">
                      <tr>
                        <td width="10%">
                          <input type="checkbox" name=""> AHM
                        </td>
                        <td width="20%">
                          <input type="text" class="form-control isi" name="">
                        </td>
                      </tr>
                      <tr>
                        <td width="10%">
                          <input type="checkbox" name=""> MD
                        </td>
                        <td width="20%">
                          <input type="text" class="form-control isi" name="">
                        </td>
                      </tr>
                      <tr>
                        <td width="10%">
                          <input type="checkbox" name=""> Dealer
                        </td>
                        <td width="20%">
                          <input type="text" class="form-control isi" name="">
                        </td>
                      </tr>
                      <tr>
                        <td width="10%">
                          <input type="checkbox" name=""> Other
                        </td>
                        <td width="20%">
                          <input type="text" class="form-control isi" name="">
                        </td>
                      </tr>

                    </table>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>                  
                </div>   -->

                    <br>
                    <button type='button' disabled class="btn btn-flat btn-info btn-block">Detail</button>
                    <table class="table table-bordered table-hovered" id="example2" width="100%">
                      <tr>
                        <td>Nama Dealer</td>
                        <td>Qty Reject</td>
                        <td>Qty Approve</td>
                        <td>Total Penjualan</td>
                        <td>Piutang Dealer</td>
                      </tr>
                      <?php
                      $get_claim = $this->db->query("SELECT tr_claim_sales_program_detail.*,ms_dealer.nama_dealer, (SELECT sum(nilai_potongan) FROM tr_claim_sales_program_detail WHERE perlu_revisi=0) as piutang, count(id) as jml_jual FROM tr_claim_sales_program_detail 
                      INNER JOIN tr_claim_sales_program on tr_claim_sales_program_detail.id_claim_sp = tr_claim_sales_program.id_claim_sp
                      INNER JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
                      WHERE id_program_md='$row->id_program_md' GROUP BY tr_claim_sales_program.id_dealer");
                      ?>
                      <?php if ($get_claim->num_rows() > 0) : ?>
                        <?php foreach ($get_claim->result() as $key => $rs) : ?>
                          <tr>
                            <td><?= $rs->nama_dealer ?></td>
                            <td></td>
                            <td></td>
                            <td><?= $rs->jml_jual ?></td>
                            <td><?= number_format($rs->piutang, 0, ',', '.') ?></td>
                          </tr>
                        <?php endforeach ?>
                      <?php endif ?>
                    </table>
                    <br>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                      <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->

      <?php
      } elseif ($set == 'download_report_monitoring_claim') {
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/report_sales_program/view">
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
                <form class="form-horizontal" action="h1/report_sales_program/download_report_monitoring_claim" method="post">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">ID Program MD</label>
                      <div class="col-sm-3">
                        <select class="form-control select2" name="id_program_md" required>
                          <?php
                          $get_program = $this->mbc->get_program();
                          if ($get_program->num_rows() > 0) {
                            echo "<option value=''>- choose -</option>";

                            foreach ($get_program->result() as $key => $rs) {
                              echo "<option value='$rs->id_program_md' data-ahm='$rs->id_program_ahm'>$rs->id_program_md</option>";
                            }
                          } ?>
                        </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-12" align='center'>
                      <button type="submit" value="save" class="btn btn-success btn-flat"><i class="fa fa-download"></i> Download</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->

      <?php
      } elseif ($set == "view") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <!--a href="h1/report_sales_program/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
        </a-->
              <a href="h1/report_sales_program/download_report_monitoring_claim" class="btn bg-green btn-flat margin"><i class="fa fa-download"></i> Download Report Monitoring Claim</a>
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
                  <th width="5%">No</th>
                  <th>ID Program AHM</th>
                  <th>ID Program MD</th>
                  <th>Jenis Program</th>
                  <th>Periode Awal</th>
                  <th>Periode Akhir</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $dt = $this->db->query("SELECT * FROM tr_sales_program WHERE id_program_md NOT IN (SELECT id_program_md_gabungan FROM tr_sales_program_gabungan WHERE id_program_md_gabungan IS NOT NULl) ORDER BY tr_sales_program.id_program_md DESC");
                foreach ($dt->result() as $row) {
                  echo "          
            <tr>
              <td>$no</td>                           
              <td>
                <a href='h1/report_sales_program/view?id=$row->id_program_md'>
                  $row->id_program_ahm
                </a>
              </td>              
              <td>$row->id_program_md</td>              
              <td>$row->judul_kegiatan</td>
              <td>$row->periode_awal</td>
              <td>$row->periode_akhir</td>";
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

      <?php
      } elseif ($set == 'report_view') {
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/report_sales_program/view">
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
                <form class="form-horizontal" action="h1/report_sales_program/save" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="Kode Tipe Kendaraan" readonly class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tahun Kendaraan</label>
                      <div class="col-sm-4">
                        <input type="text" name="periode_awal" placeholder="Tahun Kendaraan" readonly class="form-control">
                      </div>
                    </div>

                    <br>
                    <button type='button' disabled class="btn btn-flat btn-info btn-block">Detail Penjualan</button>
                    <table class="table table-bordered table-hovered myTable1" width="100%">
                      <tr>
                        <td width='10%'>No Mesin</td>
                        <td width='10%'>No Rangka</td>
                        <td width='10%'>Warna Kendaraan</td>
                        <td width='10%'>Tgl Penjualan</td>
                        <td width='10%'>Nama Dealer</td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </table>
                    <br>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                      <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->

      <?php
      }
      ?>
    </section>
  </div>