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
        <li class="">Pembelian Unit</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <?php

      if ($set == "insert") {
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/create_indent">
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
                <form class="form-horizontal" action="dealer/create_indent/save" method="post" enctype="multipart/form-data">
                  <div class="box-body">


                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" id="id_spk" name="id_spk" onchange="take_spk()">
                          <option value="">- choose -</option>
                          <?php
                          foreach ($dt_spk->result() as $isi) {
                            echo "<option value='$isi->no_spk'>$isi->no_spk</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control" placeholder="Nama Konsumen" name="nama_konsumen" id="nama_konsumen">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Konsumen</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control" placeholder="Alamat Konsumen" name="alamat" id="alamat">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                      <div class="col-sm-4">
                        <input type="text" readonly onkeypress="return number_only(event)" class="form-control" id="no_ktp" placeholder="No KTP" name="no_ktp">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                      <div class="col-sm-4">
                        <input type="text" readonly onkeypress="return number_only(event)" class="form-control" id="no_telp" placeholder="No telp" name="no_telp">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-4">
                        <input type="email" readonly class="form-control" placeholder="Email" name="email" id="email">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" placeholder="Tipe Kendaraan" name="tipe_ahm" id="tipe_ahm">
                        <input type="hidden" name="id_tipe_kendaraan" id="id_tipe_kendaraan">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Warna Kendaraan</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" placeholder="Warna Kendaraan" name="warna" id="warna">
                        <input type="hidden" name="id_warna" id="id_warna">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Nilai Indent</label>
                      <div class="col-sm-4">
                        <input type="text" onkeypress="return number_only(event)" required class="form-control" placeholder="Nilai Indent" name="nilai_dp">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Qty</label>
                      <div class="col-sm-4">
                        <input type="text" onkeypress="return number_only(event)" class="form-control" readonly placeholder="Qty" name="qty" value="1">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal ETA</label>
                      <div class="col-sm-4">
                        <input type="text" id="tanggal" class="form-control" placeholder="Tanggal ETA" name="tgl">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                      <div class="col-sm-10">
                        <input type="text" required class="form-control" placeholder="Keterangan" name="ket">
                      </div>
                    </div>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save </button>
                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->

      <?php
      } elseif ($set == "edit") {
        $row = $dt_indent->row();
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/create_indent">
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
                <form class="form-horizontal" action="dealer/create_indent/update" method="post" enctype="multipart/form-data">
                  <div class="box-body">

                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                      <div class="col-sm-4">
                        <input type="hidden" value="<?php echo $row->id_spk ?>" name="id">
                        <input type="text" readonly class="form-control" value="<?php echo $row->id_spk ?>" placeholder="ID SPK" name="id_spk">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control" value="<?php echo $row->nama_konsumen ?>" placeholder="Nama Konsumen" name="nama_konsumen">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Alamat Konsumen</label>
                      <div class="col-sm-10">
                        <input type="text" readonly class="form-control" value="<?php echo $row->alamat ?>" placeholder="Alamat Konsumen" name="alamat">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                      <div class="col-sm-4">
                        <input type="text" readonly onkeypress="return number_only(event)" value="<?php echo $row->no_ktp ?>" class="form-control" placeholder="No KTP" name="no_ktp">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                      <div class="col-sm-4">
                        <input type="text" readonly onkeypress="return number_only(event)" value="<?php echo $row->no_telp ?>" class="form-control" placeholder="No telp" name="no_telp">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-4">
                        <input type="email" class="form-control" readonly placeholder="Email" value="<?php echo $row->email ?>" name="email">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" disabled name="id_tipe_kendaraan">
                          <option value="<?php echo $row->id_tipe_kendaraan ?>">
                            <?php
                            $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $row->id_tipe_kendaraan)->row();
                            if (isset($dt_cust)) {
                              echo $dt_cust->id_tipe_kendaraan . " - " . $dt_cust->tipe_ahm;
                            } else {
                              echo "- choose -";
                            }
                            ?>
                          </option>
                          <?php
                          $dt_tipe = $this->m_admin->kondisi("ms_tipe_kendaraan", "id_tipe_kendaraan != '$row->id_tipe_kendaraan'");
                          foreach ($dt_tipe->result() as $val) {
                            echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                          }
                          ?>
                          <option value="">- choose -</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Warna Kendaraan</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" disabled name="id_warna">
                          <option value="<?php echo $row->id_warna ?>">
                            <?php
                            $dt_cust    = $this->m_admin->getByID("ms_warna", "id_warna", $row->id_warna)->row();
                            if (isset($dt_cust)) {
                              echo $dt_cust->id_warna . " - " . $dt_cust->warna;
                            } else {
                              echo "- choose -";
                            }
                            ?>
                          </option>
                          <?php
                          $dt_warna = $this->m_admin->kondisi("ms_warna", "id_warna != '$row->id_warna'");
                          foreach ($dt_warna->result() as $val) {
                            echo "
                        <option value='$val->id_warna'>$val->id_warna - $val->warna</option>;
                        ";
                          }
                          ?>
                          <option value="">- choose -</option>
                        </select>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Nilai Indent</label>
                      <div class="col-sm-4">
                        <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->nilai_dp ?>" required class="form-control" placeholder="Nilai Indent" name="nilai_dp">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Qty</label>
                      <div class="col-sm-4">
                        <input type="text" readonly onkeypress="return number_only(event)" value="<?php echo $row->qty ?>" class="form-control" placeholder="Qty" name="qty">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal ETA</label>
                      <div class="col-sm-4">
                        <input type="text" id="tanggal" class="form-control" value="<?php echo $row->tgl ?>" placeholder="Tanggal ETA" name="tgl">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                      <div class="col-sm-10">
                        <input type="text" required class="form-control" value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">
                      </div>
                    </div>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update </button>
                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      <?php
      } elseif ($set == "detail") {
        $row = $dt_indent->row();
        $disabled = '';
        $form = '';
        if ($mode == 'detail') {
          $disabled = 'disabled';
        }
        if ($mode == 'edit') {
          $form = 'save_nilai';
        }
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/create_indent">
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
                <form class="form-horizontal" action="dealer/create_indent/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kode Indent</label>
                      <div class="col-sm-4">
                        <input type="hidden" value="<?php echo $row->id_indent ?>" name="id_indent">
                        <input type="text" required class="form-control" disabled value="<?php echo $row->id_indent ?>" name="id_indent">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Status Indent</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" disabled value="<?php echo $row->status ?>" name="status">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" disabled value="<?php echo $row->nama_konsumen ?>" name="nama_konsumen">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" disabled value="<?php echo $row->id_spk ?>" name="id_spk">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" disabled value="<?php echo $row->no_ktp ?>" name="no_ktp">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" disabled value="<?php echo $row->no_telp ?>" name="no_telp">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                      <div class="col-sm-4">
                        <?php $tipe = $this->db->get_where('ms_tipe_kendaraan', ['id_tipe_kendaraan' => $row->id_tipe_kendaraan])->row()->tipe_ahm ?>
                        <input type="text" required class="form-control" disabled value="<?php echo $row->id_tipe_kendaraan . ' | ' . $tipe ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                      <div class="col-sm-4">
                        <?php $warna = $this->db->get_where('ms_warna', ['id_warna' => $row->id_warna])->row()->warna ?>
                        <input type="text" required class="form-control" disabled value="<?php echo $row->id_warna . ' | ' . $warna ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                      <div class="col-sm-4">
                        <?php $spk = $this->db->get_where('tr_spk', ['no_spk' => $row->id_spk])->row()->jenis_beli;
                        $jenis_bayar = $spk;
                        ?>
                        <input type="text" required class="form-control" disabled value="<?php echo $jenis_bayar ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Alasan Cancel Indent</label>
                      <?php $reasons = $this->db->get_where('ms_alasan_cancel', ['id_alasan_cancel' => $row->id_reasons]);
                      $reasons = $reasons->num_rows() > 0 ? $reasons->row()->alasan_cancel : '';
                      ?>
                      <div class="col-sm-4">
                        <input type="text" disabled class="form-control" value="<?= $reasons ?>">
                      </div>
                    </div>
                    <?php if ($row->alasan_cancel_indent != '' or $row->alasan_cancel_indent != NULL) : ?>
                      <div class="form-group">
                        <div class="col-md-offset-8 col-sm-4">
                          <input type="text" disabled class="form-control" value="<?= $row->alasan_cancel_indent ?>">
                        </div>
                      </div>
                    <?php endif ?>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nilai Indent</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control" <?= $disabled ?> name="nilai_dp" value="<?php echo $row->nilai_dp ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl ETA</label>
                      <div class="col-sm-4">
                        <input type="text" required class="form-control datepicker" name="tgl" <?= $disabled ?> value="<?php echo $row->tgl ?>">
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <?php if ($mode != 'detail') : ?>
                    <div class="box-footer">
                      <div class="col-sm-12" align="center">
                        <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                      </div>
                    </div>
                  <?php endif ?>
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
              <a href="dealer/create_indent/history" class="btn bg-blue btn-flat">
                <i class="fa fa-list"></i> History
              </a>
            </h3>
          </div>
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
            <table id="datatables_" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No SPK</th>
                  <th>Tgl Indent</th>
                  <th>Tipe</th>
                  <th>Warna</th>
                  <th>Nama Konsumen</th>
                  <th>No KTP</th>
                  <th>Tanda Jadi SPK</th>
                  <th>Status Pembayaran TJS</th>
                  <th>Status Indent</th>
		  <th>Tgl Fulfiled</th>
                  <th width="7%">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($dt_indent as $row) {
                  $status = '';
                  $tombol = '';
                  if ($row->status == 'requested') {
                    $status = "<span class='label label-warning'>Requested</span>";
                  }elseif ($row->status == 'canceled') {
                    $status = "<span class='label label-danger'>Canceled</span>";
                  }elseif ($row->status == 'input') {
                    $status = "<span class='label label-warning'>Requested</span>";
                  } elseif ($row->status == 'sent') {
                    $status = "<span class='label label-primary'>Sent to MD</span>";
                  } elseif ($row->status == 'rejected') {
                    $status = "<span class='label label-danger'>Rejected</span>";
                  } elseif ($row->status == 'approved') {
                    $status = "<span class='label label-success'>Approved</span>";
                  } elseif ($row->status == 'cancel') {
                    $status = "<span class='label label-info'>Cancel by Dealer</span>";
                  } elseif ($row->status == 'completed') {
                    $status = "<span class='label label-success'>Completed</span>";
                  }elseif ($row->status == 'proses') {
                    $status = "<span class='label label-primary'>Proses</span>";
                  }

		if($row->status_bayar == 'Lunas'){	
                    $status_bayar = "<span class='label label-success'>Lunas</span>";
		}else{
                   $status_bayar = "<span class='label label-danger'>Belum Lunas</span>";
		}
		
		$tgl_fulfiled = '';
		$tgl_indent = '';
		if($row->updated_at !=''){
			$tgl_fulfiled = date("d M Y",strtotime($row->updated_at));
		}
		if($row->created_at !=''){
			$tgl_indent = date("d M Y",strtotime($row->created_at));
		}


                  $edit = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                  $delete = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                  $print = $this->m_admin->set_tombol($id_menu, $group, 'print');
                  $btn_input = "<a data-toggle='tooltip' title='Input Nilai Indent' class='btn btn-primary btn-xs btn-flat' href='dealer/create_indent/input_nilai?id=$row->id_spk'>Input</a>";
                  // $btn_cetak_kwitansi = "<a data-toggle='tooltip' $print title='Cetak Data' href='dealer/create_indent/cetak_tandaterima?id=$row->id_spk' target='_blank' onclick='window.location.reload(true);'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Kwitansi</button></a>";
                  $btn_cetak_kwitansi = ''; //di hide. Karena sudah Ada Invoice TJS.
                  // $btn_delete = "<a data-toggle='tooltip' $delete title='Delete Data' onclick='return confirm('Are you sure to delete this data?')' class='btn btn-danger btn-xs btn-flat' href='dealer/create_indent/delete?id=$row->id_spk'><i class='fa fa-trash-o'></i> Delete</a>";
                  $btn_delete = ''; //Dihide sementara karena fungsi mau disamakan dengan cancel
                  $btn_cancel = "<a data-toggle='tooltip' title='Cancel Data' $edit href='dealer/create_indent/cancel?id=$row->id_spk' onclick=\"return confirm('Are you sure ?')\" class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Cancel</a>";
                  $btn_delete = $btn_cancel;
                  $btn_edit = "<a data-toggle='tooltip' title='Edit Data' $edit href='dealer/create_indent/edit?id=$row->id_spk'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>";


                  if ($row->status == 'input' or $row->status == 'requested') {
                    if ($row->status_cetak == 'cetak_kwitansi') {
                      $tombol = $btn_input . ' ' . $btn_cetak_kwitansi;
                    } else {
                      $tombol = $btn_delete . ' ' . $btn_cetak_kwitansi;
                    }
                  } elseif ($row->status == 'sent') {
                    // $tombol = $btn_cetak_kwitansi . ' ' . $btn_cancel;
                    $tombol = $btn_cetak_kwitansi;
                  } elseif ($row->status == 'rejected') {
                    $tombol = $btn_edit . ' ' . $btn_cancel;
                  }
                  echo "
            <tr>
              <td>
              <a href='dealer/create_indent/detail?id=$row->id_spk'>
                $row->id_spk
              </a>
              </td>
		<td>$tgl_indent</td>
              <td>$row->id_tipe_kendaraan - $row->tipe_ahm</td>              
              <td>$row->id_warna - $row->warna</td>                            
              <td>$row->nama_konsumen</td>              
              <td>$row->no_ktp</td>              
              <td>" . mata_uang_rp($row->amount_tjs) . "</td>   
              <td>$status_bayar</td>               
              <td>$status</td>
              <td>$tgl_fulfiled</td>                                    
              <td align='center'>$tombol</td></tr>";
                ?>
                <?php
                  $no++;
                }
                ?>
              </tbody>
            </table>
            <script>
              $(function() {
                $('#datatables_').DataTable({
                  "paging": true,
                  "lengthChange": true,
                  "searching": true,
                  "ordering": true,
                  // "scrollX": true,
                  "order": [],
                  "info": true,
                  fixedHeader: true,
                  "lengthMenu": [
                    [10, 25, 50, 75, 100, -1],
                    [10, 25, 50, 75, 100, "All"]
                  ],
                  "autoWidth": true
                })
              });
            </script>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php
      } elseif ($set == "history") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/create_indent/" class="btn bg-maroon btn-flat">
                <i class="fa fa-eye"></i> View Data
              </a>
            </h3>
          </div>
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
            <table id="datatables_" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Kode Indent</th>
                  <th>No SPK</th>
                  <th>Tipe</th>
                  <th>Warna</th>
                  <th>Nama Konsumen</th>
                  <th>No KTP</th>
                  <th>Tanda Jadi</th>
                  <th>Status Indent</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($dt_indent as $row) {
                  $tombol = '';
                  if ($row->status == 'requested') {
                    $status = "<span class='label label-warning'>Requested</span>";
                  }elseif ($row->status == 'canceled') {
                    $status = "<span class='label label-danger'>Canceled</span>";
                  }elseif ($row->status == 'input') {
                    $status = "<span class='label label-warning'>Requested</span>";
                  } elseif ($row->status == 'sent') {
                    $status = "<span class='label label-primary'>Sent to MD</span>";
                  } elseif ($row->status == 'rejected') {
                    $status = "<span class='label label-danger'>Rejected</span>";
                  } elseif ($row->status == 'approved') {
                    $status = "<span class='label label-success'>Approved</span>";
                  } elseif ($row->status == 'cancel') {
                    $status = "<span class='label label-info'>Cancel by Dealer</span>";
                  } elseif ($row->status == 'completed') {
                    $status = "<span class='label label-success'>Completed</span>";
                  }
                  $edit = $this->m_admin->set_tombol($id_menu, $group, 'edit');
                  $delete = $this->m_admin->set_tombol($id_menu, $group, 'delete');
                  $print = $this->m_admin->set_tombol($id_menu, $group, 'print');
                  //$s = $this->db->query("SELECT * FROM ms_vendor WHERE id_vendor = '$row->ekspedisi'")->row();    
                  if ($row->status == 'input' or $row->status == 'requested') {
                    if ($row->status_cetak == 'cetak_kwitansi') {
                      $tombol = "
                          <a data-toggle='tooltip' title='Input Nilai Indent' class='btn btn-primary btn-xs btn-flat' href='dealer/create_indent/input_nilai?id=$row->id_spk'>Input Nilai Indent</a>
                          <a data-toggle='tooltip' $print title='Cetak Data' href='dealer/create_indent/cetak_tandaterima?id=$row->id_spk' target='_blank' onclick='window.location.reload(true);'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Kwitansi</button></a>
                          ";
                    } else {
                      //  $tombol = "
                      //     <a data-toggle='tooltip' title='Edit Data' $edit href='dealer/create_indent/edit?id=$row->id_spk'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>
                      //     <a data-toggle='tooltip' $delete title='Delete Data' onclick='return confirm('Are you sure to delete this data?')' class='btn btn-danger btn-xs btn-flat' href='dealer/create_indent/delete?id=$row->id_spk'><i class='fa fa-trash-o'></i> Delete</a>
                      //     <a data-toggle='tooltip' $print title='Cetak Data' href='dealer/create_indent/cetak_tandaterima?id=$row->id_spk' target='_blank' onclick='window.location.reload(true);'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Kwitansi</button></a>
                      // ";
                      $tombol = "
                          <a data-toggle='tooltip' $delete title='Delete Data' onclick='return confirm('Are you sure to delete this data?')' class='btn btn-danger btn-xs btn-flat' href='dealer/create_indent/delete?id=$row->id_spk'><i class='fa fa-trash-o'></i> Delete</a>
                          <a data-toggle='tooltip' $print title='Cetak Data' href='dealer/create_indent/cetak_tandaterima?id=$row->id_spk' target='_blank' onclick='window.location.reload(true);'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Kwitansi</button></a>
                      ";
                    }
                  } elseif ($row->status == 'sent') {
                    $tombol = "<a data-toggle='tooltip' $print title='Cetak Data' href='dealer/create_indent/cetak_tandaterima?id=$row->id_spk' target='_blank' onclick='window.location.reload(true);'><button class='btn btn-flat btn-xs btn-warning'><i class='fa fa-print'></i> Cetak Kwitansi</button></a>
                        <a data-toggle='tooltip' title='Cancel Data' $edit href='dealer/create_indent/cancel?id=$row->id_spk'><button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Cancel by Dealer</button></a>";
                  } elseif ($row->status == 'rejected') {
                    $tombol = "<a data-toggle='tooltip' title='Edit Data' $edit href='dealer/create_indent/edit?id=$row->id_spk'><button class='btn btn-flat btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</button></a>
                        <a data-toggle='tooltip' title='Cancel Data' $edit href='dealer/create_indent/cancel?id=$row->id_spk'><button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Cancel by Dealer</button></a>";
                  } else {
                    $tombol = "";
                  }
                  echo "
              <tr>
                <td>$row->id_indent</td>
                <td>
                <a href='dealer/create_indent/detail?id=$row->id_spk'>
                  $row->id_spk
                </a>
                </td>
                <td>$row->id_tipe_kendaraan - $row->tipe_ahm</td>              
                <td>$row->id_warna - $row->warna</td>                            
                <td>$row->nama_konsumen</td>              
                <td>$row->no_ktp</td>              
                <td align='right'>" . mata_uang_rp($row->amount_tjs) . "</td>
                <td>$status</td>
                </tr>";
                ?>
                <?php
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <script>
          $(function() {
            $('#datatables_').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              // "scrollX": true,
              "order": [],
              "info": true,
              fixedHeader: true,
              "lengthMenu": [
                [10, 25, 50, 75, 100, -1],
                [10, 25, 50, 75, 100, "All"]
              ],
              "autoWidth": true
            })
          });
        </script>
      <?php
      } elseif ($set == "notif_indent") {
        $form = '';
        $mode = 'detail';
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="dealer/create_indent">
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
                <form class="form-horizontal" action="dealer/create_indent/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kode Gudang</label>
                      <div class="col-sm-4">
                        <?php $gudang = $this->db->query("SELECT * FROM ms_gudang_dealer WHERE gudang='$row->id_gudang_dealer' OR id_gudang_dealer='$row->id_gudang_dealer'")->row() ?>
                        <input type="text" required class="form-control" disabled value="<?php echo $gudang->gudang ?>">
                      </div>
                    </div>
                    <?php foreach ($indent as $rs) : ?>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                        <div class="col-sm-4">
                          <input type="text" required class="form-control" disabled value="<?php echo $rs->id_tipe_kendaraan . ' | ' . $rs->tipe_ahm ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                        <div class="col-sm-4">
                          <input type="text" required class="form-control" disabled value="<?php echo $rs->id_warna . ' | ' . $rs->warna ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Quantity</label>
                        <div class="col-sm-4">
                          <input type="text" required class="form-control" disabled value="<?php echo $rs->jml ?>">
                        </div>
                      </div>
                    <?php endforeach ?>
                  </div><!-- /.box-body -->
                  <?php if ($mode != 'detail') : ?>
                    <div class="box-footer">
                      <div class="col-sm-12" align="center">
                        <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                      </div>
                    </div>
                  <?php endif ?>
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
        url: "<?php echo site_url('dealer/create_indent/cari_id') ?>",
        type: "POST",
        data: "tgl=" + tgl_js,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          $("#id_indent").val(data[0]);
          $("#id_customer").val(data[1]);
        }
      })
    }

    function take_sales() {
      var id_karyawan_dealer = $("#id_karyawan_dealer").val();
      $.ajax({
        url: "<?php echo site_url('dealer/create_indent/take_sales') ?>",
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

    function take_spk() {
      var id_spk = $("#id_spk").val();
      $.ajax({
        url: "<?php echo site_url('dealer/create_indent/take_spk') ?>",
        type: "POST",
        data: "id_spk=" + id_spk,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          if (data[0] == 'ok') {
            $("#nama_konsumen").val(data[1]);
            $("#alamat").val(data[2]);
            $("#no_ktp").val(data[3]);
            $("#no_telp").val(data[4]);
            $("#email").val(data[5]);
            $("#id_tipe_kendaraan").val(data[6]);
            $("#tipe_ahm").val(data[7]);
            $("#id_warna").val(data[8]);
            $("#warna").val(data[9]);
          } else {
            alert(data[0]);
          }
        }
      })
    }
  </script>