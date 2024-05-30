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

<body onload="cek_generate();">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H1</li>
        <li class="">Faktur STNK</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">

      <?php
      if ($set == "check") {
        $row = $dt_faktur->row();
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pengajuan_bbn_md">
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
                <form class="form-horizontal" action="h1/pengajuan_bbn_md/save" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Nama Dealer" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" readonly placeholder="NO BASTD" class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" name="tgl_bastd" placeholder="Tgl BASTD" value="<?php echo $row->tgl_bastd ?>" readonly class="form-control">
                      </div>
                    </div>

                    <table id="example4" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th width="1%">Aksi</th>
                          <th>Nama Konsumen</th>
                          <th>Alamat Konsumen</th>
                          <th>No Mesin</th>
                          <th>No Rangka</th>
                          <th>No Faktur AHM</th>
                          <th>Tipe</th>
                          <th>Warna</th>
                          <th>Tahun</th>
                          <th>Harga BBN</th>
                          <th>Tgl Jual</th>
                          <th>Tgl Mohon Samsat</th>
                          <th>Kekurangan</th>
                          <th>Kesalahan Disengaja</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $no = 1;
                          foreach ($dt_stnk->result() as $row) {
                            $er = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
                            WHERE tr_spk.no_spk = '$row->no_spk'");
                            if ($er->num_rows() > 0) {
                              $ts = $er->row();
                              $nama_konsumen = $ts->nama_konsumen;
                              $alamat = $ts->alamat;
                            } else {
                              $nama_konsumen = "";
                              $alamat = "";
                            }
                            $re = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $row->no_mesin)->row();
                            $nosin_spasi = substr_replace($row->no_mesin, " ", 5, -strlen($row->no_mesin));
                            $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi);
                            if ($rw->num_rows() > 0) {
                              $ry = $rw->row();
                              $no_fkb = $ry->nomor_faktur;
                              $tahun_produksi = $ry->tahun_produksi;
                            } else {
                              $no_fkb = "";
                              $tahun_produksi = "";
                            }
                            $tipe = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                          INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
                          WHERE ms_item.id_tipe_kendaraan = '$re->tipe_motor'");
                            if ($tipe->num_rows() > 0) {
                              $rq = $tipe->row();
                              $tipe_motor = $rq->tipe_ahm;
                              $warna = $rq->warna;
                            } else {
                              $tipe_motor = "";
                              $warna = "";
                            }
                            $ra = $this->m_admin->getByID("tr_sales_order", "no_mesin", $row->no_mesin);
                            if ($ra->num_rows() > 0) {
                              $rp = $ra->row();
                              $tgl_cetak_invoice = $rp->tgl_cetak_invoice;
                            } else {
                              $tgl_cetak_invoice = 0;
                            }
                            $cek  = $this->db->query("SELECT * FROM tr_pengajuan_bbn_detail WHERE no_bastd = '$row->no_bastd' AND no_mesin = '$re->no_mesin'");
                            if ($cek->num_rows() > 0) {
                              $isi = $cek->row();
                              $tipe = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item=ms_item.id_item 
                            INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
                            WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'");
                              if ($tipe->num_rows() > 0) {
                                $rq = $tipe->row();
                                $tipe_motor = $rq->tipe_ahm;
                                $warna = $rq->warna;
                              } else {
                                $tipe_motor = "";
                                $warna = "";
                              }


                              ?>
                            <tr>
                              <td align="center">
                                <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> class="btn btn-flat btn-primary btn-sm" href="h1/pengajuan_bbn_md/edit?id=<?php echo $row->id_sales_order ?>&b=<?php echo $row->no_bastd ?>&no=<?php echo $isi->no_mesin ?>">Edit</a>
                              </td>
                              <?php
                                    echo "
                            <td>$isi->nama_konsumen</td> 
                            <td>$isi->alamat</td> 
                            <td>$isi->no_mesin</td> 
                            <td>$isi->no_rangka</td> 
                            <td>$isi->no_faktur</td> 
                            <td>$tipe_motor</td>
                            <td>$warna</td>
                            <td>$isi->tahun</td> 
                            <td align='right'>" . mata_uang2($isi->biaya_bbn) . "</td>                           
                            <td>$tgl_cetak_invoice</td>                                
                            <td>"; ?>
                              <?php echo $isi->tgl_mohon_samsat ?>
                              <!-- <input type="text" placeholder="yyyy-mm-dd" value="<?php echo $isi->tgl_mohon_samsat ?>" name="tgl_mohon_samsat[]" id="tanggal<?php echo $no ?>"  style="width:80px;"> -->
                              </td>
                              <td><?php echo $isi->kekurangan ?></td>
                              <td align='center'>
                                <?php
                                      $cek = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                                  INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
                                      if ($cek->num_rows() > 0) {
                                        $c = $cek->row();
                                        $jenis = $c->source;
                                        if ($jenis == 'po_indent') {
                                          if ($isi->sengaja == '1') {
                                            ?>
                                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                                        <input type="checkbox" class="flat-red" name="sengaja[]" value="1" checked disabled onclick="return false;">
                                      </div>
                                    <?php } else { ?>
                                      <input type="checkbox" class="flat-red" name="sengaja[]" value="1" onclick="return false;">
                                <?php
                                          }
                                        }
                                      } ?>
                              </td>

                            </tr>
                          <?php
                              } else { ?>
                            <tr>
                              <td align="center">
                                <a class="btn btn-flat btn-primary btn-sm" href="h1/pengajuan_bbn_md/edit?id=<?php echo $row->id_sales_order ?>&b=<?php echo $row->no_bastd ?>&no=<?php echo $re->no_mesin ?>">Edit</a>
                              </td>
                              <?php
                                    echo "
                            <td>$nama_konsumen</td> 
                            <td>$alamat</td> 
                            <td>$re->no_mesin</td> 
                            <td>$re->no_rangka</td> 
                            <td>$no_fkb</td> 
                            <td>$tipe_motor</td>
                            <td>$warna</td>
                            <td>$tahun_produksi</td> 
                            <td>" . number_format($row->biaya_bbn, 0, ',', '.') . "</td>                           
                            <td>$tgl_cetak_invoice</td>                                
                            <td>"; ?>
                              <!-- <input type="text" placeholder="yyyy-mm-dd" name="tgl_mohon_samsat[]" id="tanggal<?php echo $no ?>"  style="width:80px;"> -->
                              <td></td>
                              </td>
                              <td align='center'>
                                <!-- <input type='checkbox' name='sengaja[]'> -->
                              </td>

                            </tr>
                        <?php
                              $no++;
                            }
                          }
                          ?>
                      </tbody>
                    </table>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                      <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="submit" onclick="return confirm('Are you sure to cancel all data?')" name="save" value="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      <?php
      } elseif ($set == 'reject') {
        $row = $dt_faktur->row();
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pengajuan_bbn_md">
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
                <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nomor Retur</label>
                      <div class="col-sm-4">
                        <input readonly type="text" name="no_retur" placeholder="Nomor Retur" value="<?php echo $this->m_admin->cari_id('tr_pengajuan_bbn', 'id_pengajuan_bbn') ?>" class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Retur</label>
                      <div class="col-sm-4">
                        <input type="text" name="tgl_retur" placeholder="Tgl Retur" value="<?php echo date("Y-m-d") ?>" id="tanggal" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alasan Retur</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" name="alasan_retur">
                          <option value="">- choose -</option>
                          <?php
                            $dt_retur = $this->m_admin->getSortCond("ms_alasan_return", "alasan_return", "ASC");
                            foreach ($dt_retur->result() as $isi) {
                              echo "<option>$isi->alasan_return</option>";
                            }
                            ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                      <div class="col-sm-10">
                        <input type="text" name="keterangan" placeholder="Keterangan" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                      </div>
                    </div>

                    <table class='table table-bordered table-hover' id="example1">
                      <thead>
                        <tr>
                          <th>No Mesin</th>
                          <th>No Rangka</th>
                          <th>Nama Konsumen</th>
                          <th>Tipe</th>
                          <th>Warna</th>
                          <th>Tahun</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($dt_stnk->result() as $key) {
                            $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                            $nosin_spasi = substr_replace($key->no_mesin, " ", 5, -strlen($key->no_mesin));
                            $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi)->row();
                            echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>                    
                    </tr>";
                          }
                          ?>
                      </tbody>

                    </table>

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
      } elseif ($set == 'cetak_faktur') {
        $row = $dt_faktur->row();
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pengajuan_bbn_md">
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
                <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                      </div>
                    </div>
                    <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mohon Samsat</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_mohon_samsat" value="" placeholder="Nomor BASTD" readonly class="form-control" id="tanggal">
                  </div>                                    
                </div>                
                 -->
                    <table class='table table-bordered table-hover' id="example1">
                      <thead>
                        <tr>
                          <th>No Mesin</th>
                          <th>No Rangka</th>
                          <th>Tgl Mohon Samsat</th>
                          <th>Nama Konsumen</th>
                          <th>Tipe</th>
                          <th>Warna</th>
                          <th>Tahun</th>
                          <th width="5%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($dt_stnk->result() as $key) {
                            $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                            $nosin_spasi = substr_replace($key->no_mesin, " ", 5, -strlen($key->no_mesin));
                            $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi)->row();
                            if (isset($rw->tahun_produksi)) {
                              $tahun_produksi = $rw->tahun_produksi;
                            } else {
                              $tahun_produksi = "";
                            }
                            echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->tgl_mohon_samsat</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->deskripsi_ahm</td>
                      <td>$re->warna</td>
                      <td>$tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_faktur_act?id=$key->no_mesin&id2=$row->no_bastd' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";
                          }
                          ?>
                      </tbody>

                    </table>
                    <?php /* ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer --> <?php */ ?>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      <?php
      } elseif ($set == 'cetak_tagihan_ubahnama_stnk') {
        $row = $dt_faktur->row();
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pengajuan_bbn_md">
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
                <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                      </div>
                    </div>

                    <table class='table table-bordered table-hover' id="example1">
                      <thead>
                        <tr>
                          <th>No Mesin</th>
                          <th>No Rangka</th>
                          <th>Nama Konsumen</th>
                          <th>Tipe</th>
                          <th>Warna</th>
                          <th>Tahun</th>
                          <th width="5%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($dt_tagihan->result() as $key) {
                            if ($key->sengaja == '1') {
                              $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                              $nosin_spasi = substr_replace($key->no_mesin, " ", 5, -strlen($key->no_mesin));
                              $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi)->row();
                              echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->tipe_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_tagihan_ubahnama_stnk_act?id=$key->no_mesin' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";
                            }
                          }
                          ?>
                      </tbody>

                    </table>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      <?php
      } elseif ($set == 'cetak_permohonan') {
        $row = $dt_faktur->row();
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pengajuan_bbn_md">
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
                <form class="form-horizontal" action="h1/pengajuan_bbn_md/save_reject" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                      </div>
                    </div>

                    <table class='table table-bordered table-hover' id="example1">
                      <thead>
                        <tr>
                          <th>No Mesin</th>
                          <th>No Rangka</th>
                          <th>Nama Konsumen</th>
                          <th>Tipe</th>
                          <th>Warna</th>
                          <th>Tahun</th>
                          <th width="5%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($dt_stnk->result() as $key) {
                            $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                            $nosin_spasi = substr_replace($key->no_mesin, " ", 5, -strlen($key->no_mesin));
                            $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi)->row();
                            echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->deskripsi_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_permohonan_act?id=$key->no_mesin&id2=$key->no_bastd' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";
                          }
                          ?>
                      </tbody>

                    </table>
                    <?php /* ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer --> <?php */ ?>
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      <?php
      } elseif ($set == 'cetak_pendaftaran_bpkb') {
        $row = $dt_faktur->row();
        ?>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/pengajuan_bbn_md">
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

                <div class="box-body">
                  <br>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nomor BASTD</label>
                    <div class="col-sm-4">
                      <input type="text" name="no_bastd" value="<?php echo $row->no_bastd ?>" placeholder="Nomor BASTD" readonly class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                    <div class="col-sm-4">
                      <input type="text" name="nama_dealer" value="<?php echo $row->nama_dealer ?>" placeholder="Nama Dealer" readonly class="form-control">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Dealer</label>
                    <div class="col-sm-4">
                      <input type="text" name="alamat_dealer" value="<?php echo $row->alamat ?>" placeholder="Alamat Dealer" readonly class="form-control">
                    </div>
                  </div>

                  <table class='table table-bordered table-hover' id="example1">
                    <thead>
                      <tr>
                        <th>No Mesin</th>
                        <th>No Rangka</th>
                        <th>Nama Konsumen</th>
                        <th>Tipe</th>
                        <th>Warna</th>
                        <th>Tahun</th>
                        <th width="5%">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($dt_stnk->result() as $key) {
                          $re = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                        INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan = ms_item.id_tipe_kendaraan
                        INNER JOIN ms_warna ON ms_warna.id_warna = ms_item.id_warna WHERE tr_scan_barcode.no_mesin='$key->no_mesin'")->row();
                          $nosin_spasi = substr_replace($key->no_mesin, " ", 5, -strlen($key->no_mesin));
                          $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi)->row();
                          echo "                                      
                    <tr>                                        
                      <td>$key->no_mesin</td>
                      <td>$key->no_rangka</td>
                      <td>$key->nama_konsumen</td>
                      <td>$re->deskripsi_ahm</td>
                      <td>$re->warna</td>
                      <td>$rw->tahun_produksi</td>  
                      <td>
                        <a href='h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb_act?id=$key->no_mesin' type='button' class='btn btn-flat btn-primary btn-sm' target='_blank'><i class='fa fa-print'></i></a>
                      </td>
                    </tr>";
                        }
                        ?>
                    </tbody>

                  </table>
                  <?php /* ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer --> <?php */ ?>
                </div>
              </div>
            </div>
          </div><!-- /.box -->
        <?php
        } elseif ($set == 'edit') {
          ?>
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h1/pengajuan_bbn_md/check?id=<?php echo $no_bastd ?>">
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
                  <?php
                    if ($dt_so->num_rows() == 0) {
                      echo "Data Sales Order atau Data Pengajuan BBN MD tidak tersedia";
                    } else {
                      $row = $dt_so->row();
                      $nama_konsumen = $row->nama_konsumen;
                      $alamat = $row->alamat;
                      $no_telp = $row->no_telp;
                      $no_npwp = $row->npwp;
                      $ra = $this->m_admin->getByID("tr_sales_order", "no_mesin", $row->no_mesin)->row();

                      $nosin_spasi = substr_replace($row->no_mesin, " ", 5, -strlen($row->no_mesin));
                      $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi);
                      if ($rw->num_rows() > 0) {
                        $ry = $rw->row();
                        $no_fkb = $ry->nomor_faktur;
                        $tahun_produksi = $ry->tahun_produksi;
                      } else {
                        $no_fkb = "";
                        $tahun_produksi = "";
                      }
                      ?>
                    <form class="form-horizontal" action="h1/pengajuan_bbn_md/update" method="post" enctype="multipart/form-data">
                      <div class="box-body">
                        <br>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_tipe_kendaraan" value="<?php echo $row->id_tipe_kendaraan ?>">
                            <input type="hidden" name="id_warna" value="<?php echo $row->id_warna ?>">
                            <input type="hidden" name="no_bastd" value="<?php echo $no_bastd ?>">
                            <input type="hidden" name="tgl_jual" value="<?php echo $ra->tgl_cetak_invoice ?>">
                            <input type="hidden" name="tahun" value="<?php echo $tahun_produksi ?>">
                            <input type="hidden" name="no_faktur" value="<?php echo $no_fkb ?>">
                            <input type="hidden" name="biaya_bbn" value="<?php echo $row->biaya_bbn ?>">
                            <input type="text" readonly name="no_mesin" value="<?php echo $row->no_mesin ?>" placeholder="No Mesin" class="form-control">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                          <div class="col-sm-4">
                            <input type="text" readonly name="no_rangka" value="<?php echo $row->no_rangka ?>" placeholder="No Rangka" value="" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                          <div class="col-sm-4">
                            <input type="text" name="tipe" placeholder="Tipe" value="<?php echo $row->tipe_ahm ?>" readonly class="form-control">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                          <div class="col-sm-4">
                            <input type="text" name="warna" autocomplete="false" value="<?php echo $row->warna ?>" placeholder="Warna" class="form-control">
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                          <div class="col-sm-10">
                            <input type="text" value="<?php echo $nama_konsumen ?>" name="nama_konsumen" placeholder="Nama Konsumen" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tempat/Tanggal Lahir</label>
                          <div class="col-sm-4">
                            <input type="text" name="tempat_lahir" value="<?php echo $row->tempat_lahir ?>" placeholder="Tempat Lahir" class="form-control">
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="tgl_lahir" placeholder="Tanggal Lahir" value="<?php echo $row->tgl_lahir ?>" id="tanggal2" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                          <div class="col-sm-4">
                            <?php
                                $dt_cust    = $this->m_admin->getByID("ms_kelurahan", "id_kelurahan", $row->id_kelurahan)->row();
                                if (isset($dt_cust)) {
                                  $kel = $dt_cust->kelurahan;
                                } else {
                                  $kel = "";
                                }
                                ?>
                            <input type="hidden" value="<?php echo $row->id_kelurahan ?>" name="id_kelurahan" id="id_kelurahan">
                            <input type="text" value="<?php echo $kel ?>" name="kelurahan" data-toggle="modal" placeholder="Kelurahan" data-target="#Kelurahanmodal" class="form-control" id="kelurahan" onchange="take_kec()">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                            <input type="text" class="form-control" id="kecamatan" readonly placeholder="Kecamatan" name="kecamatan">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_kabupaten" id="id_kabupaten">
                            <input type="text" class="form-control" readonly placeholder="Kota/Kabupaten" id="kabupaten" name="kabupaten">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                          <div class="col-sm-4">
                            <input type="hidden" name="id_provinsi" id="id_provinsi">
                            <input type="text" class="form-control" readonly placeholder="Provinsi" id="provinsi" name="provinsi">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                          <div class="col-sm-10">
                            <input type="text" name="alamat" placeholder="Alamat" value="<?php echo $row->alamat ?>" class="form-control">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No HP" value="<?php echo $row->no_hp ?>" id="no_hp" name="no_hp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No Telp" value="<?php echo $no_telp ?>" name="no_telp">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No KTP" value="<?php echo $row->no_ktp ?>" name="no_ktp">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">No KK</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No KK" value="<?php echo $row->no_kk ?>" name="no_kk">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No NPWP</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="No NPWP" value="<?php echo $no_npwp ?>" name="no_npwp">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Gadis Ibu Kandung</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Nama Gadis Ibu Kandung" value="<?php echo $row->nama_ibu ?>" name="nama_ibu">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Lahir Ibu Kandung</label>
                          <div class="col-sm-4">
                            <input id="tanggal3" type="text" class="form-control" placeholder="Tgl Lahir Ibu Kandung" value="<?php echo $row->tgl_ibu ?>" name="tgl_ibu">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                          <div class="col-sm-4">
                            <!--	<select class="form-control select2">
                  		<option>--Choose--</option>
                  		<?php $pn = $this->db->query("SELECT * FROM ms_pekerjaan where active=1 order by pekerjaan ASC"); ?>
                  		<?php foreach ($pn->result() as $pn) : ?>
                  			<option value="<?php echo $pn->id_pekerjaan ?>"><?php echo $pn->pekerjaan ?></option>
                  		<?php endforeach ?>
                  	</select> -->
                            <input type="text" class="form-control" placeholder="Pekerjaan" value="<?php echo $row->kerja ?>" name="pekerjaan">
                          </div>
                          <label for="inputEmail3" class="col-sm-2 control-label">Pengeluaran 1 Bulan</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Pengeluaran 1 Bulan" value="<?php echo $row->penghasilan ?>" name="penghasilan">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" placeholder="Tgl Mohon Samsat" id="tanggal4" name="tgl_mohon_samsat" value="<?php if (isset($row->tgl_mohon_samsat)) {
                                                                                                                                                      echo $row->tgl_mohon_samsat;
                                                                                                                                                    } ?>">
                          </div>
                          <?php
                              $cek = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                      INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do WHERE tr_picking_list_view.no_mesin = '$row->no_mesin'");
                              if ($cek->num_rows() > 0) {
                                $c = $cek->row();
                                $jenis = $c->source;
                                if ($jenis == 'po_indent') {
                                  ?>
                              <label for="inputEmail3" class="col-sm-2 control-label">Kesalahan Disengaja</label>
                              <div class="col-sm-4">
                                <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                                  <input type="checkbox" class="flat-red" name="sengaja" value="1" <?php if (isset($row->sengaja)) {
                                                                                                              echo $sengaja = $row->sengaja == 1 ? 'checked' : '';
                                                                                                            } ?>>
                                </div>
                              </div>
                          <?php }
                              } ?>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kekurangan</label>
                          <div class="col-sm-10">
                            <input type="text" onchange="cek_tombol()" class="form-control" placeholder="Kekurangan" id="kekurangan" name="kekurangan" value="<?php if (isset($row->kekurangan)) {
                                                                                                                                                                    echo $row->kekurangan;
                                                                                                                                                                  } ?>">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Keterangan" id="keterangan_d" name="keterangan_d" value="<?php if (isset($row->keterangan_d)) {
                                                                                                                                                echo $row->keterangan_d;
                                                                                                                                              } ?>">
                          </div>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                          <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                          <button type="submit" onclick="return confirm('Are you sure to reject data?')" name="save" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>
                        </div>
                      </div><!-- /.box-footer -->
                    </form>
                </div>
              </div>
            <?php } ?>
            </div>
          </div><!-- /.box -->
        <?php
        } elseif ($set == 'generate_ulang') {
          ?>
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h1/cetak_faktur">
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
                  <form class="form-horizontal" action="h1/cetak_faktur/download_file_samsat_history" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                      <br>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mohon Samsat</label>
                        <div class="col-sm-4">
                          <input type="text" name="tgl_mohon_samsat" placeholder="Tgl Mohon Samsat" value="<?php echo date("Y-m-d") ?>" id="tanggal" class="form-control tgl_mohon_samsat">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nama Biro Jasa</label>
                        <div class="col-sm-4">
                          <select class="form-control select2" required name="nama_biro_jasa" id="nama_biro_jasa">
                            <option value="">- choose -</option>
                            <?php
                              $biro = $this->db->query("SELECT * FROM ms_vendor INNER JOIN ms_vendor_type ON 
                          ms_vendor.id_vendor_type = ms_vendor_type.id_vendor_type
                          WHERE ms_vendor_type.vendor_type = 'Biro Jasa' OR ms_vendor_type.vendor_type = 'biro jasa' OR ms_vendor_type.vendor_type = 'Biro_Jasa'
                          OR ms_vendor_type.vendor_type = 'BiroJasa'");
                              foreach ($biro->result() as $row) {
                                echo "<option value='$row->id_vendor'>$row->vendor_name</option>";
                              }
                              ?>
                          </select>
                        </div>
                        <div class="col-sm-2">
                          <button class="btn btn-primary btn-flat btn-sm" type="button" onclick="generateDetail()">Cek</button>

                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table table-bordered table-hovered table-striped tblDatatables">
                            <thead>
                              <th>No</th>
                              <th>Nama Konsumen</th>
                              <th>Alamat Konsumen</th>
                              <th>No Rangka</th>
                              <th>No Mesin</th>
                              <th>No Faktur AHM</th>
                              <th>Tipe</th>
                              <th>Warna</th>
                              <th>Tahun</th>
                              <th>Harga BBN</th>
                              <th>Tgl Jual</th>
                              <th>Tgl Mohon Samsat</th>
                            </thead>
                            <tbody id="tabelGenerate"></tbody>
                          </table>
                        </div>
                      </div>
                    </div><!-- /.box-body -->
                    <span id="tombol_generate">
                      <div class="box-footer">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-10">
                          <button type="submit" onclick="return confirm('Are you sure to generate all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Generate Txt File</button>
                          <button type="submit" onclick="return confirm('Are you sure to generate?')" name="save_excel" value="save_excel" class="btn btn-success  btn-flat"><i class="fa fa-download"></i> Generate Excel (SP)</button>
			  <!-- <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button> -->
                        </div>
                      </div><!-- /.box-footer -->
                    </span>
                  </form>
                </div>
              </div>
            </div>
          </div><!-- /.box -->
          <script>
            function generateDetail() {
              let values = {
                tgl_mohon_samsat: $('.tgl_mohon_samsat').val(),
                nama_biro_jasa: $('#nama_biro_jasa').val()
              }
              $.ajax({
                beforeSend: function() {
                  $('#tabelGenerate').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
                },
                url: "<?php echo site_url('h1/cetak_faktur/cekGenerateUlang') ?>",
                type: "POST",
                data: values,
                cache: false,
                success: function(response) {
                  $('#tabelGenerate').html(response);
                  // loadDatatables();
                }
              })
            }

            function loadDatatables() {
              $('.tblDatatables').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                // 'scrollY': '281px',
                'scrollX': true,
                'scrollCollapse': true,
                'autoWidth': true,

              })
            }
          </script>
        <?php
        } elseif ($set == "view") {
          ?>
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h1/pengajuan_bbn_md/generate?r=cetak_faktur">
                  <button class="btn bg-red btn-flat margin"><i class="fa fa-download"></i> Generate File TXT Samsat</button>
                </a>
                <a href="h1/cetak_faktur/history">
                  <button class="btn bg-blue btn-flat margin">History</button>
                </a>
                <a href="h1/cetak_faktur/generate_ulang">
                  <button class="btn bg-green btn-flat margin"><i class="fa fa-download"></i> Generate Ulang</button>
                </a>
		<a class="btn btn-info" data-toggle="modal" onclick="show_faktur()" href="#modal_faktur">Cek Faktur</a>
              </h3>
              <form action="" method="POST">
                <div class="form-horizontal">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mohon Samsat</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" id="tanggal" name="tgl_mohon_samsat" value="<?= isset($tgl_mohon_samsat) ? $tgl_mohon_samsat : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No Faktur AHM</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" name="no_faktur_ahm" value="<?= isset($no_faktur_ahm) ? $no_faktur_ahm : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" id="no_mesin" name="no_mesin" value="<?= isset($no_mesin) ? $no_mesin : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div class="col-sm-3">
                      <select name="id_dealer" id="id_dealer" class="form-control select2">
                        <?php if ($dealer->num_rows() > 0) : ?>
                          <option value="">--Choose--</option>
                          <?php foreach ($dealer->result() as $dl) :
                                $selected = isset($id_dealer) ? $id_dealer == $dl->id_dealer ? 'selected' : '' : '';
                                ?>
                            <option value="<?= $dl->id_dealer ?>" <?= $selected ?>><?= $dl->kode_dealer_md ?> | <?= $dl->nama_dealer ?></option>
                          <?php endforeach ?>
                        <?php endif ?>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <button id="btnCari" name="btnCari" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </form>
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
                    <th>Nama Konsumen</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Dealer</th>
                    <th>No Faktur AHM</th>
                    <th>Tipe</th>
                    <th>Warna Samsat</th>
                    <th>Tgl Mohon Samsat</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dt_bbn->result() as $rs) :
                    $cek_bbn_luar = $this->db->get_where('tr_bantuan_bbn_luar', array('no_mesin'=>$rs->no_mesin));
                    if ($cek_bbn_luar->num_rows() > 0) {
                      $rw = $cek_bbn_luar->row();
                      $no_faktur = $rw->no_faktur;
                      $deskripsi_ahm = get_data('ms_tipe_kendaraan','id_tipe_kendaraan',$rw->id_tipe_kendaraan,'deskripsi_ahm');
                      $warna = get_data('ms_warna','id_warna',$rw->id_warna,'warna');

                    }
			/* 2021-09-06 dicommend
                    $this->db->select('b.nama_bpkb');
                    $this->db->from('tr_sales_order a');
                    $this->db->join('tr_spk b', 'a.no_spk = b.no_spk', 'inner');
                    $this->db->where('a.no_mesin', $rs->no_mesin);
                    $cek_nama = $this->db->get();
                    if ($cek_nama->num_rows() > 0) {
                      $nama_konsumen = $cek_nama->row()->nama_bpkb;
                    } else {
                      $nama_konsumen = $rs->nama_konsumen;
                    }*/

                   ?>
                    <tr>
                      <td><?= $rs->nama_konsumen ?></td>
                      <td><?= $rs->no_mesin ?></td>
                      <td><?= $rs->no_rangka ?></td>
                      <td><?= $rs->nama_dealer ?></td>
                      <td><?= ($rs->nomor_faktur == '') ? $no_faktur : $rs->nomor_faktur ?></td>
                      <td><?= ($rs->deskripsi_ahm == '') ? $deskripsi_ahm : $rs->deskripsi_ahm ?></td>
                      <td><?= ($rs->warna == '') ? $warna : $rs->warna ?></td>
                      <td><?= $rs->tgl_mohon_samsat ?></td>
                      <?php if ($rs->tgl_mohon_samsat == NULL) { ?>
                        <td align="center">
                          <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> class="btn btn-flat btn-primary btn-xs" href="h1/pengajuan_bbn_md/edit?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Edit</a>
                        </td>
                      <?php } else { ?>
                        <td>
                          <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> class="btn btn-flat btn-primary btn-xs" href="h1/pengajuan_bbn_md/edit?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Edit</a>
                          <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "print"); ?> class="btn btn-flat btn-warning btn-xs" href="h1/pengajuan_bbn_md/cetak_faktur_act?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Cetak Faktur</a>

                          <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "print"); ?> class="btn btn-flat btn-success btn-xs" href="h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb_act?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Cetak Pendaftaran BPKB</a>
                          <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "print"); ?> class="btn btn-flat btn-danger btn-xs" href="h1/pengajuan_bbn_md/cetak_permohonan_act?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Cetak Permohonan STNK</a>
                        </td>
                      <?php } ?>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
	  <div class="modal fade" id="modal_faktur">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">List Faktur <?php echo date('Y')?></h4>
                    </div>
                    <div class="modal-body" id="bodymodal_faktur">
                    </div>
                </div>
            </div>
        </div>
  	<script type="text/javascript">
	function show_faktur(){
                $.ajax({
                    type: "post",
                    url: "<?=site_url('h1/cetak_faktur/get_data_faktur');?>",
                    dataType: "html",
                    success: function (response) {
                        $('#bodymodal_faktur').empty();
                        $('#bodymodal_faktur').append(response);
                    }
                });
            }
</script>
        <?php
        } elseif ($set == "history") {
          ?>
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h1/cetak_faktur">
                  <button class="btn bg-blue btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
                </a>

              </h3>
              <form action="" method="POST">
                <div class="form-horizontal">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mohon Samsat</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" id="tanggal" name="tgl_mohon_samsat" value="<?= isset($tgl_mohon_samsat) ? $tgl_mohon_samsat : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No Faktur AHM</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" name="no_faktur_ahm" value="<?= isset($no_faktur_ahm) ? $no_faktur_ahm : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" id="no_mesin" name="no_mesin" value="<?= isset($no_mesin) ? $no_mesin : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div class="col-sm-3">
                      <select name="id_dealer" id="id_dealer" class="form-control select2">
                        <?php if ($dealer->num_rows() > 0) : ?>
                          <option value="">--Choose--</option>
                          <?php foreach ($dealer->result() as $dl) :
                                $selected = isset($id_dealer) ? $id_dealer == $dl->id_dealer ? 'selected' : '' : '';
                                ?>
                            <option value="<?= $dl->id_dealer ?>" <?= $selected ?>><?= $dl->kode_dealer_md ?> | <?= $dl->nama_dealer ?></option>
                          <?php endforeach ?>
                        <?php endif ?>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <button id="btnCari" name="btnCari" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </form>
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
                    <th>Nama Konsumen</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Dealer</th>
                    <th>No Faktur AHM</th>
                    <th>Tipe</th>
                    <th>Warna</th>
                    <th>Tgl Mohon Samsat</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dt_bbn->result() as $rs) : ?>
                    <tr>
                      <td><?= $rs->nama_konsumen ?></td>
                      <td><?= $rs->no_mesin ?></td>
                      <td><?= $rs->no_rangka ?></td>
                      <td><?= $rs->nama_dealer ?></td>
                      <td><?= $rs->nomor_faktur ?></td>
                      <td><?= $rs->deskripsi_ahm ?></td>
                      <td><?= $rs->warna ?></td>
                      <td><?= $rs->tgl_mohon_samsat ?></td>
                      <td>
                        <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "print"); ?> class="btn btn-flat btn-warning btn-xs" href="h1/pengajuan_bbn_md/cetak_faktur_act?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Cetak Faktur</a>

                        <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "print"); ?> class="btn btn-flat btn-success btn-xs" href="h1/pengajuan_bbn_md/cetak_pendaftaran_bpkb_act?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Cetak Pendaftaran BPKB</a>
                        <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "print"); ?> class="btn btn-flat btn-danger btn-xs" href="h1/pengajuan_bbn_md/cetak_permohonan_act?id=<?php echo $rs->id_sales_order ?>&b=<?php echo $rs->no_bastd ?>&no=<?php echo $rs->no_mesin ?>">Cetak Permohonan STNK</a>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        <?php
        } elseif ($set == 'detail') {
          $row = $dt_faktur->row();
          ?>

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h1/pengajuan_bbn_md">
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
              <div class="col-md-12">
                <form class="form-horizontal" enctype="multipart/form-data" action="h1/pengajuan_bbn_md/save_approval" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" value="<?php echo $row->no_bastd ?>" placeholder="No BASTD" name="no_bastd">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>" placeholder="Start Date" name="start_date">
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="hidden" name="no_bastd" id="no_bastd" value="<?php echo $row->no_bastd ?>">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" value="<?php echo $row->tgl_bastd ?>" placeholder="Tgl BASTD" name="tgl_bastd">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" value="<?php echo $row->end_date ?>" placeholder="End Date" name="end_date">
                      </div>
                    </div>
                    <div id="row">
                      <div class="form-group">
                        <table id="example4" class="table myTable1 table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>No Mesin</th>
                              <th>No Rangka</th>
                              <th>Nama Konsumen</th>
                              <th>Alamat</th>
                              <th>Biaya BBN</th>
                              <th>Fotocopy KTP (5)</th>
                              <th>Cek Fisik Kendaraan (2)</th>
                              <th>Hasil Cek Fisik STNK (1)</th>
                              <th>Formulir Data BPKB (1)</th>
                              <th>Surat Kuasa (2)</th>
                              <th>CKD STNK & BPKB (2)</th>
                              <th>Form Permohonan STNK (1)</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                              $no = 1;
                              foreach ($dt_stnk->result() as $row) {
                                if ($row->ktp == 'ya') $ktp = "checked";
                                else $ktp = "";
                                if ($row->fisik == 'ya') $fisik = "checked";
                                else $fisik = "";
                                if ($row->stnk == 'ya') $stnk = "checked";
                                else $stnk = "";
                                if ($row->bpkb == 'ya') $bpkb = "checked";
                                else $bpkb = "";
                                if ($row->kuasa == 'ya') $kuasa = "checked";
                                else $kuasa = "";
                                if ($row->ckd == 'ya') $ckd = "checked";
                                else $ckd = "";
                                if ($row->permohonan == 'ya') $pem = "checked";
                                else $pem = "";

                                $er = $this->db->query("SELECT * FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
                            WHERE tr_spk.no_spk = '$row->no_spk'");
                                if ($er->num_rows() > 0) {
                                  $ts = $er->row();
                                  $nama_konsumen = $ts->nama_bpkb;
                                  $alamat = $ts->alamat;
                                } else {
                                  $nama_konsumen = "";
                                  $alamat = "";
                                }
                                $re = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $row->no_mesin)->row();
                                echo "
                        <tr>             
                          <td>$row->no_mesin</td> 
                          <td>$row->no_rangka</td> 
                          <td>$row->nama_konsumen</td> 
                          <td>$row->alamat</td> 
                          <td>" . number_format($row->biaya_bbn, 0, ',', '.') . "</td> 
                          <td align='center'>                            
                            <input type='checkbox' name='check_ktp[]' $ktp disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_fisik[]' $fisik disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_stnk[]' $stnk disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_bpkb[]' $bpkb disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_kuasa[]' $kuasa disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_ckd[]' $ckd disabled>
                          </td>      
                          <td align='center'>
                            <input type='checkbox' name='check_permohonan[]' $pem disabled>
                          </td>      
                        </tr>";
                                $no++;
                              }
                              ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          <?php
          } elseif ($set == 'cek_approval') {
            $row = $dt_faktur->row();
            $row2 = $dt_stnk->row();
            ?>

            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="h1/pengajuan_bbn_md">
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
                <div class="col-md-12">
                  <form class="form-horizontal" enctype="multipart/form-data" action="h1/pengajuan_bbn_md/save_approval" method="post">
                    <div class="box-body">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                        <div class="col-sm-4">
                          <input type="text" readonly class="form-control" value="<?php echo $row->no_bastd ?>" placeholder="No BASTD" name="no_bastd">
                        </div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-4">
                          <input type="text" readonly class="form-control" value="<?php echo $row->start_date ?>" placeholder="Start Date" name="start_date">
                        </div>
                      </div>
                      <div class="form-group">
                        <input type="hidden" name="no_bastd" id="no_bastd" value="<?php echo $row->no_bastd ?>">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tgl BASTD</label>
                        <div class="col-sm-4">
                          <input type="text" readonly class="form-control" value="<?php echo $row->tgl_bastd ?>" placeholder="Tgl BASTD" name="tgl_bastd">
                        </div>
                        <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-4">
                          <input type="text" readonly class="form-control" value="<?php echo $row->end_date ?>" placeholder="End Date" name="end_date">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                        <div class="col-sm-4">
                          <input type="text" readonly class="form-control" value="<?php echo $row2->nama_dealer ?>" placeholder="Nama Dealer" name="nama_dealer">
                        </div>
                      </div>
                      <div id="row">
                        <div class="form-group">
                          <table id="example4" class="table myTable1 table-bordered table-hover">
                            <thead>
                              <tr>
                                <th width="2%">No</th>
                                <th>Nama Konsumen</th>
                                <th>Alamat</th>
                                <th>Tipe</th>
                                <th>No Mesin</th>
                                <th>No Rangka</th>
                                <th>Biaya BBN</th>
                              </tr>
                            </thead>

                            <tbody>
                              <?php
                                $no = 1;
                                $tot = 0;
                                foreach ($get_nosin->result() as $row) {
                                  $cek_pik = $this->db->query("SELECT tr_faktur_stnk_detail.*,tr_scan_barcode.no_rangka,tr_scan_barcode.tipe_motor,tr_spk.tipe_customer
                            FROM tr_faktur_stnk_detail INNER JOIN tr_scan_barcode ON tr_faktur_stnk_detail.no_mesin = tr_scan_barcode.no_mesin                
                            LEFT JOIN tr_spk ON tr_faktur_stnk_detail.no_spk = tr_spk.no_spk
                            WHERE tr_faktur_stnk_detail.no_mesin = '$row->no_mesin'")->row();
                                  $getTipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$cek_pik->tipe_motor'");
                                  if ($getTipe->num_rows() > 0) {
                                    $tipe = $getTipe->row()->deskripsi_ahm;
                                  } else {
                                    $tipe = '';
                                  }
                                  echo "
                        <tr>        
                          <td>$no</td>     
                          <td>$row->nama_konsumen</td> 
                          <td>$row->alamat</td> 
                          <td>$cek_pik->tipe_motor | $tipe</td>
                          <td>$row->no_mesin</td> 
                          <td>$cek_pik->no_rangka</td> 
                          
                          
                          <td>" . number_format($cek_pik->biaya_bbn, 0, ',', '.') . "</td>                           
                        </tr>";
                                  $no++;
                                  $tot += $cek_pik->biaya_bbn;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="6">Total</td>
                                <td><?= number_format($tot, 0, ',', '.') ?></td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="box-footer">
                      <div class="col-sm-2">
                      </div>
                      <div class="col-sm-10">
                        <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve All</button>
                        <button type="submit" onclick="return confirm('Are you sure to danger all data?')" name="save" value="danger" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject All</button>
                      </div>
                    </div><!-- /.box-footer -->
                  </form>
                </div>
              </div>
             <?php
        } elseif ($set == "view_fix") {
          ?>
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                <a href="h1/pengajuan_bbn_md/generate?r=cetak_faktur">
                  <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Generate File TXT Samsat</button>
                </a>
                <a href="h1/cetak_faktur/history">
                  <button class="btn bg-blue btn-flat margin">History</button>
                </a>
                <a href="h1/cetak_faktur/generate_ulang">
                  <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Generate Ulang File TXT Samsat</button>
                </a>

              </h3>
              <form action="" method="POST">
                <div class="form-horizontal">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mohon Samsat</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" id="tanggal" name="tgl_mohon_samsat" value="<?= isset($tgl_mohon_samsat) ? $tgl_mohon_samsat : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No Faktur AHM</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" name="no_faktur_ahm" value="<?= isset($no_faktur_ahm) ? $no_faktur_ahm : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" autocomplete="off" placeholder="" id="no_mesin" name="no_mesin" value="<?= isset($no_mesin) ? $no_mesin : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div class="col-sm-3">
                      <select name="id_dealer" id="id_dealer" class="form-control select2">
                        <?php if ($dealer->num_rows() > 0) : ?>
                          <option value="">--Choose--</option>
                          <?php foreach ($dealer->result() as $dl) :
                                $selected = isset($id_dealer) ? $id_dealer == $dl->id_dealer ? 'selected' : '' : '';
                                ?>
                            <option value="<?= $dl->id_dealer ?>" <?= $selected ?>><?= $dl->kode_dealer_md ?> | <?= $dl->nama_dealer ?></option>
                          <?php endforeach ?>
                        <?php endif ?>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <button id="btnCari" name="btnCari" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </form>
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
              <table id="showDetail" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Nama Konsumen</th>
                    <th>No Mesin</th>
                    <th>No Rangka</th>
                    <th>Dealer</th>
                    <th>No Faktur AHM</th>
                    <th>Tipe</th>
                    <th>Warna</th>
                    <th>Tgl Mohon Samsat</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>                  
                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
            <?php
            }
            ?>
    </section>
  </div>

  <div class="modal fade" id="Kelurahanmodal">
    <div class="modal-dialog" role="document">
      <div class="modal-content" width="70%">
        <div class="modal-header">
          Search Kelurahan
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <table id="table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Kelurahan</th>
                <th>Kecamatan</th>
                <th>Kabupaten</th>
                <th width="1%"></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <script type="text/javascript">
  function getStokMD() {
  $.ajax({
        beforeSend: function() {
          $('#showDetailStok').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('h1/cetak_faktur/showDetailStok')?>",
        type:"POST",
        data:"",            
        cache:false,
        success:function(response){                
           $('#showDetailStok').html(response);
           loadDatatables('showDetailStok');
        } 
    })
}
  </script>