<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Master Data</li>
      <li class="">Unit</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "insert") {
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/item">
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
              <form id="form_" class="form-horizontal" action="master/item/save" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Item</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" autofocus required id="inputEmail3" placeholder="ID Item" name="id_item">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_tipe_kendaraan">
                        <option value="">- choose -</option>
                        <?php
                        foreach ($dt_tipe->result() as $val) {
                          echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan</option>;
                        ";
                        }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_warna">
                        <option value="">- choose -</option>
                        <?php
                        foreach ($dt_warna->result() as $val) {
                          echo "
                        <option value='$val->id_warna'>$val->id_warna</option>;
                        ";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Upload Video</label>
                    <div class="col-md-4">
                      <input type="file" class="form-control" v-model="video" name="video" accept="video/*">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Link Youtube</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="link_youtube" name="link_youtube">
                    </div>
                  </div>
                  <div class="form-group" v-if="video!='' && link_youtube!=''">
                    <label for="inputEmail3" class="col-sm-2 control-label">Preview</label>
                    <div class="col-md-4">
                      <input type="radio" name="preview" value="video"> Upload Video &nbsp;&nbsp;&nbsp;
                      <input type="radio" name="preview" value="link_youtube"> Link Youtube
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Upload Gambar</label>
                    <div class="col-md-4">
                      <input type="file" class="form-control" v-model="gambar" name="gambar" accept="image/*">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="keterangan">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input type="checkbox" class="flat-red" name="active" value="1" checked>
                        Active
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                    <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            video: '<?= isset($row) ? $row->video : '' ?>',
            link_youtube: '<?= isset($row) ? $row->link_youtube : '' ?>',
            gambar: '<?= isset($row) ? $row->gambar : '' ?>',
          },
          methods: {}
        });
      </script>
    <?php
    } elseif ($set == "edit") {
      $row = $dt_item->row();
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/item">
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
              <form id="form_" class="form-horizontal" action="master/item/update" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row->id_item ?>" />
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Item</label>
                    <div class="col-sm-4">
                      <input type="text" value="<?php echo $row->id_item ?>" class="form-control" autofocus required id="inputEmail3" placeholder="ID Item" name="id_item">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_tipe_kendaraan">
                        <option value="<?php echo $row->id_tipe_kendaraan ?>">
                          <?php
                          $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan", "id_tipe_kendaraan", $row->id_tipe_kendaraan)->row();
                          if (isset($dt_cust)) {
                            echo $dt_cust->id_tipe_kendaraan;
                          } else {
                            echo "- choose -";
                          }
                          ?>
                        </option>
                        <?php
                        $dt_tipe = $this->m_admin->getAll("ms_tipe_kendaraan", "id_tipe_kendaraan != " . $row->id_tipe_kendaraan);
                        foreach ($dt_tipe->result() as $val) {
                          echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan</option>;
                        ";
                        }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" name="id_warna">
                        <option value="<?php echo $row->id_warna ?>">
                          <?php
                          $dt_cust    = $this->m_admin->getByID("ms_warna", "id_warna", $row->id_warna)->row();
                          if (isset($dt_cust)) {
                            echo $dt_cust->id_warna;
                          } else {
                            echo "- choose -";
                          }
                          ?>
                        </option>
                        <?php
                        $dt_warna = $this->m_admin->getAll("ms_warna", "id_warna != " . $row->id_warna);
                        foreach ($dt_warna->result() as $val) {
                          echo "
                        <option value='$val->id_warna'>$val->id_warna</option>;
                        ";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Upload Video</label>
                    <div class="col-md-4">
                      <input type="file" class="form-control" name="video" accept="video/*" onchange="setVideo(this)">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Link Youtube</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" v-model="link_youtube" name="link_youtube">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Upload Gambar</label>
                    <div class="col-md-4">
                      <input type="file" class="form-control" name="gambar" accept="image/*">
                    </div>
                  </div>
                  <div class="form-group" v-if="video!='' && link_youtube!=''">
                    <label for="inputEmail3" class="col-sm-2 control-label">Preview</label>
                    <div class="col-md-4">
                      <input type="radio" name="preview" value="video"> Upload Video &nbsp;&nbsp;&nbsp;
                      <input type="radio" name="preview" value="link_youtube"> Link Youtube
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-10">
                      <input type="text" value="<?php echo $row->keterangan ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="keterangan">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <?php
                        if ($row->active == '1') {
                        ?>
                          <input type="checkbox" class="flat-red" name="active" value="1" checked>
                        <?php } else { ?>
                          <input type="checkbox" class="flat-red" name="active" value="1">
                        <?php } ?>
                        Active
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>
                    <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            video: '<?= isset($row) ? $row->video : '' ?>',
            link_youtube: '<?= isset($row) ? $row->link_youtube : '' ?>',
            gambar: '<?= isset($row) ? $row->gambar : '' ?>',
          },
          methods: {}
        });

        function setVideo(thiss) {
          form_.video = thiss.files[0].name;
        }
      </script>
    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/item/add">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <th>ID Item</th>
                <th>Tipe Kendaraan</th>
                <th>Warna</th>
                <th>Bundling</th>
                <th>Keterangan</th>
                <th width="5%">Active</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($dt_item->result() as $row) {
                if ($row->active == '1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
                echo "          
            <tr>
              <td>$no</td>              
              <td>$row->id_item</td>                            
              <td>$row->id_tipe_kendaraan</td>                            
              <td>$row->id_warna</td>                            
              <td>$row->bundling</td>                            
              <td>$row->keterangan</td>                            
              <td>$active</td>                            
              <td>";
              ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/item/delete?id=<?php echo $row->id_item ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu, $group, "edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/item/edit?id=<?php echo $row->id_item ?>'><i class='fa fa-edit'></i></a>
                </td>
                </tr>
              <?php
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
  function bulk_delete() {
    var list_id = [];
    $(".data-check:checked").each(function() {
      list_id.push(this.value);
    });
    if (list_id.length > 0) {
      if (confirm('Are you sure delete this ' + list_id.length + ' data?')) {
        $.ajax({
          type: "POST",
          data: {
            id: list_id
          },
          url: "<?php echo site_url('master/item/ajax_bulk_delete') ?>",
          dataType: "JSON",
          success: function(data) {
            if (data.status) {
              window.location.reload();
            } else {
              alert('Failed.');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
          }
        });
      }
    } else {
      alert('no data selected');
    }
  }
</script>