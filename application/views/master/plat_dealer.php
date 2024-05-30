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

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">Master Data</li>
        <li class="">Dealaer</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">

      <?php
      if ($set == "form") {
        $form = '';
        if ($mode == 'insert') {
          $form = 'save';
        } elseif ($mode == 'edit') {
          $form = 'update';
        }
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="master/plat_dealer">
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
                <butto class="close" data-dismiss="alert">
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
                <form class="form-horizontal" action="master/plat_dealer/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" name="id_dealer" id='id_dealer' required>
                          <option>-- Choose --</option>
                          <?php $dealer = $this->db->query("SELECT * FROM ms_dealer ORDER BY nama_dealer"); ?>
                          <?php foreach ($dealer->result() as $dealer) :
                            $selected = isset($row) ? $row->id_dealer == $dealer->id_dealer ? 'selected' : '' : '';
                          ?>
                            <option value="<?php echo $dealer->id_dealer ?>" <?= $selected ?>><?php echo $dealer->nama_dealer ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. Plat</label>
                      <div class="col-sm-2">
                        <input type="hidden" name="id" value="<?= isset($row) ? $row->id_master_plat : '' ?>">
                        <input type="text" class="form-control" placeholder="No. Plat" name="no_plat" id="no_plat" required value="<?= isset($row) ? $row->no_plat : '' ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Driver</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="driver" placeholder="Driver" name="driver" required readonly value="<?= isset($row) ? $row->driver : '' ?>">
                      </div>
                      <div class="col-sm-1">
                        <button class='btn btn-primary btn-flat' type='button' onclick="showModalKaryawanDealer()"><i class='fa fa-search'></i></button>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="no_hp" placeholder="No HP" name="no_hp" required readonly value="<?= isset($row) ? $row->no_hp : '' ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID Karyawan Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="id_karyawan_dealer" placeholder="Honda ID Driver" name="id_karyawan_dealer" required readonly value="<?= isset($row) ? $row->id_karyawan_dealer : '' ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Honda ID</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="honda_id" placeholder="Honda ID" name="honda_id" required readonly value="<?= isset($row) ? $row->honda_id : '' ?>">
                      </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-12" align='center'>
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <?php
        $data['data'] = ['modalKaryawanDealer', 'post_id_dealer'];
        $this->load->view('dealer/dgi_api', $data); ?>
        <script>
          function pilihKaryawanDealer(params) {
            $('#driver').val(params.nama_lengkap);
            $('#id_karyawan_dealer').val(params.id_karyawan_dealer);
            $('#no_hp').val(params.no_hp);
            $('#honda_id').val(params.honda_id);
          }
        </script>
      <?php
      } elseif ($set == "view") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="master/plat_dealer/add">
                <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>No. Plat</th>
                  <th>Driver</th>
                  <th>Nama Dealer</th>
                  <th>No HP</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($plat_dealer->result() as $row) {
                  $tomb = "<a data-toggle='tooltip' title='Edit Data' href='master/plat_dealer/edit?id=$row->id_master_plat' class='btn btn-warning btn-flat btn-xs'><i class='fa fa-pencil'></i></a>";
                  // <a href='master/plat_dealer/delete?id=$row->id_master_plat' class='btn btn-danger btn-flat btn-xs'><i class='fa fa-trash-o'></i></a>";

                  if ($row->active == '1') $active = "<i class='glyphicon glyphicon-ok'></i>";

                  else $active = "";
                  echo "          
            <tr>
              <td>$no</td>              
              <td>$row->no_plat</td>              
              <td>$row->driver</td>                              
              <td>$row->nama_dealer</td>                           
              <td>$row->no_hp</td>                           
              <td align='center'>$tomb</td>";
                  $no++;
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
    function GetDetail() {
      $.ajax({
        beforeSend: function() {
          $('#loading-status').show();
        },
        url: "<?php echo site_url('master/plat_dealer/t_detail'); ?>",
        type: "POST",
        //data:"id_gudang="+id_gudang,
        cache: false,
        success: function(html) {
          $('#loading-status').hide();
          $("#tampil_detail").html(html);
        }
      });
    }



    $(document).on("click", ".btn_submit", function() {
      var id_gudang = $("#gudang option:selected").val();
      var id_master_plat = $(".myTable1 .id_master_plat").val();
      var tgl_pinjaman = $("#tgl_pinjaman").val();
      var keterangan = $("#keterangan").val();
      var ksu = $("#ksu").val();
      $.ajax({
        beforeSend: function() {
          $('#loading-status').show();
        },
        url: "<?php echo site_url('master/plat_dealer/save'); ?>",
        type: "POST",
        data: "id_master_plat=" + id_master_plat +
          "&ksu=" + ksu +
          "&keterangan=" + keterangan +
          "&tgl_pinjaman=" + tgl_pinjaman,
        cache: false,
        success: function(html) {
          $('#loading-status').hide();
          window.location.replace("<?php echo site_url('master/plat_dealer/add') ?>");
        },
        statusCode: {
          500: function() {
            $('#loading-status').hide();
            alert('Terjadi Kesalahan Saat Menambahkan Data');
          }
        }
      });
    })
  </script>