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
        <li class="">Faktur STNK</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">

      <?php
      if ($set == 'detail') {
        $row = $dt_stnk->row();
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/konfirmasi_stnk">
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
                <form class="form-horizontal" action="dealer/konfirmasi_stnk/save" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Serah Terima</label>
                      <div class="col-sm-3">
                        <input type="text" readonly name="tgl_serah_terima" placeholder="Tgl Serah Terima" value="<?php echo $row->tgl_serah_terima ?>" class="form-control">
                      </div>
                    </div>

                    <div>
                      <table id="example2" class="table myTable1 table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th>No <?php echo strtoupper($jenis) ?></th>
                            <th>Nama Konsumen</th>
                            <th>Tipe</th>
                            <th>Tahun Produksi</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                          $no = 1;
                          if ($jenis == 'bpkb') {
                            $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail WHERE no_serah_bpkb = '$row->no_serah_bpkb'");
                          } elseif ($jenis == 'stnk') {
                            $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail WHERE no_serah_stnk = '$row->no_serah_stnk'");
                          } elseif ($jenis == 'plat') {
                            $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail WHERE no_serah_plat = '$row->no_serah_plat'");
                          }
                          foreach ($dt_b->result() as $isi) {
                            $rt = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm FROM tr_terima_bj 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_terima_bj.no_mesin = '$isi->no_mesin'")->row();
                            if ($jenis == 'bpkb') {
                              $no_a = $rt->no_bpkb;
                            } elseif ($jenis == 'stnk') {
                              $no_a = $rt->no_stnk;
                            } elseif ($jenis == 'plat') {
                              $no_a = $rt->no_plat;
                            }

                            $nosin_spasi = substr_replace($isi->no_mesin, " ", 5, -strlen($isi->no_mesin));
                            $rw = $this->m_admin->getByID("tr_fkb", "no_mesin", $nosin_spasi);
                            if ($rw->num_rows() > 0) {
                              $ry = $rw->row();
                              $tahun_produksi = $ry->tahun_produksi;
                            } else {
                              $no_fkb = "";
                              $tahun_produksi = $rt->tahun;
                            }
                            echo "
                        <tr>                     
                          <td>$isi->no_mesin</td> 
                          <td>$rt->no_rangka</td> 
                          <td>$no_a</td> 
                          <td>$rt->nama_konsumen</td> 
                          <td>$rt->tipe_ahm</td>       
                          <td>$tahun_produksi</td>                                           
                        </tr>";
                            $no++;
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>

                  </div><!-- /.box-body -->

                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->

      <?php
      } elseif ($set == "konfirmasi") {
        $row = $dt_stnk->row();
        if ($jenis == 'stnk') {
          $no_serah = $row->no_serah_stnk;
        } elseif ($jenis == 'bpkb') {
          $no_serah = $row->no_serah_bpkb;
        } elseif ($jenis == 'plat') {
          $no_serah = $row->no_serah_plat;
        } else {
          $no_serah = "";
        }
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/konfirmasi_stnk">
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
                <form class="form-horizontal" action="dealer/konfirmasi_stnk/save_konfirmasi" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No Serah Terima</label>
                      <div class="col-sm-3">
                        <input type='hidden' name='jenis_dokumen' value='<?php echo $jenis ?>'>
                        <input type='text' class="form-control" readonly name='no_serah' value='<?php echo $no_serah ?>'>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Serah Terima</label>
                      <div class="col-sm-3">
                        <input type='hidden' name='no_serah' value='<?php echo $no_serah ?>'>
                        <input type="text" readonly name="tgl_serah_terima" placeholder="Tgl Serah Terima" value="<?php echo $row->tgl_serah_terima ?>" class="form-control">
                      </div>
                    </div>

                    <div>
                      <table id="example2" class="table myTable1 table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th>No <?php echo strtoupper($jenis) ?></th>
                            <th>Nama Konsumen</th>
                            <th>Tipe</th>
                            <th>Tahun Produksi</th>
                            <th align='center'>Action</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                          $no = 1;
                          if ($jenis == 'bpkb') {
                            $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail WHERE no_serah_bpkb = '$row->no_serah_bpkb'");
                          } elseif ($jenis == 'stnk') {
                            $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail WHERE no_serah_stnk = '$row->no_serah_stnk'");
                          } elseif ($jenis == 'plat') {
                            $dt_b = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail WHERE no_serah_plat = '$row->no_serah_plat'");
                          }
                          foreach ($dt_b->result() as $isi) {
                            $jum = $dt_b->num_rows();
                            $rt = $this->db->query("SELECT tr_terima_bj.*,tr_pengajuan_bbn_detail.id_tipe_kendaraan,
                            tr_pengajuan_bbn_detail.tahun,tr_pengajuan_bbn.id_dealer,ms_tipe_kendaraan.tipe_ahm FROM tr_terima_bj 
                            INNER JOIN tr_pengajuan_bbn_detail ON tr_terima_bj.no_mesin = tr_pengajuan_bbn_detail.no_mesin
                            INNER JOIN tr_pengajuan_bbn ON tr_pengajuan_bbn_detail.no_bastd = tr_pengajuan_bbn.no_bastd
                            INNER JOIN ms_tipe_kendaraan ON tr_pengajuan_bbn_detail.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                            WHERE tr_terima_bj.no_mesin = '$isi->no_mesin'");
                            $no_a = '';
                            if ($rt->num_rows() > 0) {
                              $rt = $rt->row();
                              if ($jenis == 'bpkb') {
                                $no_a = $rt->no_bpkb;
                              } elseif ($jenis == 'stnk') {
                                $no_a = $rt->no_stnk;
                              } elseif ($jenis == 'plat') {
                                $no_a = $rt->no_plat;
                              }
                            } else {
                              $rt->no_mesin = '';
                              $rt->nama_konsumen = '';
                              $rt->tipe_ahm = '';
                              $rt->tahun = '';
                              $rt->no_rangka = '';
                            }

                            $cek = $this->db->query("SELECT * FROM tr_konfirmasi_dokumen_detail WHERE no_mesin = '$isi->no_mesin' AND no_serah_terima = '$no_serah'");
                            if ($cek->num_rows() > 0) {
                              $f = "checked disabled";
                            } else {
                              $f = "";
                            }
                            echo "
                        <tr>                     
                          <td>$isi->no_mesin</td> 
                          <td>$rt->no_rangka</td> 
                          <td>$no_a</td> 
                          <td>$rt->nama_konsumen</td> 
                          <td>$rt->tipe_ahm</td>       
                          <td>$rt->tahun</td>                                           
                          <td align='center'>
                            <input type='hidden' name='no_mesin_$no' value='$isi->no_mesin'>
                            <input type='hidden' name='jum' value='$jum'>
                            <input $f type='checkbox' name='check_$no'>
                          </td>
                        </tr>";
                            $no++;
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>

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
      } elseif ($set == "view") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <!-- <a href="dealer/konfirmasi_stnk/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>           -->

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
                  <th>No Serah Terima</th>
                  <th>Tgl Serah Terima</th>
                  <th>Jenis Dokumen</th>
                  <th>Status</th>
                  <th width="15%">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $id_dealer  = $this->m_admin->cari_dealer();
                $dt_stnk    = $this->db->query("SELECT * FROM tr_penyerahan_stnk INNER JOIN ms_dealer ON tr_penyerahan_stnk.id_dealer = ms_dealer.id_dealer 
              WHERE tr_penyerahan_stnk.id_dealer = '$id_dealer'
              ORDER BY tr_penyerahan_stnk.no_serah_stnk ASC");

                $dt_plat    = $this->db->query("SELECT * FROM tr_penyerahan_plat INNER JOIN ms_dealer ON tr_penyerahan_plat.id_dealer = ms_dealer.id_dealer 
              WHERE tr_penyerahan_plat.id_dealer = '$id_dealer'
              ORDER BY tr_penyerahan_plat.no_serah_plat ASC");

                $dt_bpkb    = $this->db->query("SELECT * FROM tr_penyerahan_bpkb INNER JOIN ms_dealer ON tr_penyerahan_bpkb.id_dealer = ms_dealer.id_dealer 
              WHERE tr_penyerahan_bpkb.id_dealer = '$id_dealer'
              ORDER BY tr_penyerahan_bpkb.no_serah_bpkb ASC");
                foreach ($dt_stnk->result() as $row) {
                  if ($row->status_stnk == 'input') {
                    $status = "<span class='label label-warning'>$row->status_stnk</span>";
                  } else {
                    $status = "<span class='label label-success'>$row->status_stnk</span>";
                  }

                  $cek = $this->db->query("SELECT * FROM tr_penyerahan_stnk_detail WHERE no_serah_stnk = '$row->no_serah_stnk' AND status_nosin = 'input'");
                  if ($cek->num_rows() > 0) {
                    $tom = "<a href='dealer/konfirmasi_stnk/konfirmasi?stnk=$row->no_serah_stnk' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'></i> Konfirmasi</a>";
                  } else {
                    $tom = "";
                  }

                  echo "          
            <tr>
              <td>
                <a href='dealer/konfirmasi_stnk/detail?stnk=$row->no_serah_stnk'>
                  $row->no_serah_stnk
                </a>
              </td>
              <td>$row->tgl_serah_terima</td>                                         
              <td>STNK</td>
              <td>$status</td>                                                        
              <td>$tom</td>
            </tr>";
                }
                foreach ($dt_plat->result() as $row) {
                  if ($row->status_plat == 'input') {
                    $status = "<span class='label label-warning'>$row->status_plat</span>";
                  } else {
                    $status = "<span class='label label-success'>$row->status_plat</span>";
                  }
                  $cek = $this->db->query("SELECT * FROM tr_penyerahan_plat_detail WHERE no_serah_plat = '$row->no_serah_plat' AND status_nosin = 'input'");
                  if ($cek->num_rows() > 0) {
                    $tom2 = "<a href='dealer/konfirmasi_stnk/konfirmasi?plat=$row->no_serah_plat' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'></i> Konfirmasi</a>";
                  } else {
                    $tom2 = "";
                  }
                  echo "          
            <tr>
              <td>
                <a href='dealer/konfirmasi_stnk/detail?plat=$row->no_serah_plat'>
                  $row->no_serah_plat
                </a>
              </td>
              <td>$row->tgl_serah_terima</td>                                         
              <td>Plat</td>
              <td>$status</td>                            
              <td>$tom2</td>
            </tr>";
                }
                foreach ($dt_bpkb->result() as $row) {
                  if ($row->status_bpkb == 'input') {
                    $status = "<span class='label label-warning'>$row->status_bpkb</span>";
                  } else {
                    $status = "<span class='label label-success'>$row->status_bpkb</span>";
                  }

                  $cek = $this->db->query("SELECT * FROM tr_penyerahan_bpkb_detail WHERE no_serah_bpkb = '$row->no_serah_bpkb' AND status_nosin = 'input'");
                  if ($cek->num_rows() > 0) {
                    $tom3 = "<a href='dealer/konfirmasi_stnk/konfirmasi?bpkb=$row->no_serah_bpkb' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'></i> Konfirmasi</a>";
                  } else {
                    $tom3 = "";
                  }
                  echo "          
            <tr>
              <td>
                <a href='dealer/konfirmasi_stnk/detail?bpkb=$row->no_serah_bpkb'>
                  $row->no_serah_bpkb
                </a>
              </td>
              <td>$row->tgl_serah_terima</td>                                         
              <td>BPKB</td>
              <td>$status</td>                            
              <td>$tom3</td>
            </tr>";
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

      <?php
      }
      ?>
    </section>
  </div>
  <script type="text/javascript">
    function generate() {
      $("#tampil_data").show();
      cek_alamat();
      var id_dealer = document.getElementById("id_dealer").value;
      var xhr;
      if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
      } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      //var data = "birthday1="+birthday1_js;          
      var data = "id_dealer=" + id_dealer;
      xhr.open("POST", "dealer/konfirmasi_stnk/t_stnk", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(data);
      xhr.onreadystatechange = display_data;

      function display_data() {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document.getElementById("tampil_data").innerHTML = xhr.responseText;
          } else {
            alert('There was a problem with the request.');
          }
        }
      }
    }

    function cek_alamat() {
      var id_dealer = document.getElementById("id_dealer").value;
      $.ajax({
        url: "<?php echo site_url('dealer/konfirmasi_stnk/cari_alamat') ?>",
        type: "POST",
        data: "id_dealer=" + id_dealer,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          $("#alamat").val(data[0]);
        }
      })
    }
  </script>