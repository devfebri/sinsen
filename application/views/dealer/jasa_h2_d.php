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
      <li class="">Master</li>
      <li class="">Master H2</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "form") {
      $form = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
        $readonly = 'readonly';
      }
      if ($mode == 'detail') {
        $form = '';
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          if (typeof value !== "number") {
            return value;
          }
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/jasa_h2_d">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
              <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Jasa</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="id_jasa" id="id_jasa" autocomplete="off" value="<?= isset($row) ? $row->id_jasa : '' ?>" :readonly="mode=='detail'" <?= $readonly ?> required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Jasa 2</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="id_jasa2" id="id_jasa2" autocomplete="off" value="<?= isset($row) ? $row->id_jasa2 : '' ?>" :readonly="mode=='detail'" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="deskripsi" id="deskripsi" autocomplete="off" value="<?= isset($row) ? $row->deskripsi : '' ?>" :readonly="mode=='detail'" required readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Job Type</label>
                    <div class="col-sm-4">
                      <select name="id_type" id="id_type" class="form-control select2" <?= $disabled ?> required disabled>
                        <option value="">-choose-</option>
                        <?php $dt_type = $this->db->get('ms_h2_jasa_type');
                        foreach ($dt_type->result() as $rs) {
                          $select = isset($row) ? $row->id_type == $rs->id_type ? 'selected' : '' : '';
                        ?>
                          <option value="<?= $rs->id_type ?>" <?= $select ?>><?= $rs->id_type . ' | ' . $rs->deskripsi ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                    <div class="col-sm-4">
                      <select name="tipe_motor" id="tipe_motor" class="form-control select2" disabled>
                        <option value="">-choose-</option>
                        <?php $tipe_motor = $this->db->query("SELECT tipe_marketing,tipe_produksi,deskripsi FROM ms_ptm where tipe_produksi ='$row->tipe_motor' GROUP BY tipe_marketing");
                        foreach ($tipe_motor->result() as $rs) {
                          $select = isset($row) ? $row->tipe_motor == $rs->tipe_produksi ? 'selected' : '' : '';
                        ?>
                          <option value="<?= $rs->tipe_produksi ?>" <?= $select ?>><?= $rs->tipe_produksi . ' | ' . $rs->deskripsi ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div class="col-sm-4">
                      <select name="kategori" id="kategori" class="form-control select2" disabled v-model="kategori" required>

                        <option value="<?= $row->kategori ?>"><?= $row->kategori ?></option>
                        <option value="Penggantian">Penggantian</option>
                        <option value="Perawatan">Perawatan</option>
                        <option value="Perbaikan">Perbaikan</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control" v-model="harga" v-bind:minus="false" readonly :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Batas Harga Bawah</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control" v-model="batas_bawah" v-bind:minus="false" readonly :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Batas Harga Atas</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control" v-model="batas_atas" v-bind:minus="false" readonly :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Dealer</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control" v-model="harga_dealer" v-bind:minus="false" :readonly="mode=='detail'" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Waktu</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="waktu" id="waktu" autocomplete="off" value="<?= isset($row) ? $row->waktu : '' ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Is Favorite</label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input disabled type="checkbox" class="form-control flat-red" name="is_favorite" value="1" <?= isset($row) ? $row->is_favorite == 1 ? 'checked' : '' : 'checked' ?>>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input disabled type="checkbox" class="form-control flat-red" name="active" value="1" <?= isset($row) ? $row->active == 1 ? 'checked' : '' : 'checked' ?>>
                        Active
                      </div>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
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
            mode: '<?= $mode ?>',
            kategori: '<?= isset($row) ? $row->kategori : '' ?>',
            harga: <?= isset($row) ? $row->harga : 0 ?>,
            batas_atas: <?= isset($row) ? $row->batas_atas : 0 ?>,
            harga_dealer: <?= isset($row) ? $row->harga_dealer : 0 ?>,
            batas_bawah: <?= isset($row) ? $row->batas_bawah : 0 ?>,
          },
          methods: {

          }
        })

        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
            }
          })
          var values = {
            harga_dealer: form_.harga_dealer
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {

            if (confirm("Apakah anda yakin ?") == true) {
              if (form_.batas_bawah > form_.batas_atas) {
                alert('Batas bawah tidak boleh melebihi batas atas !');
                return false;
              }
              if (form_.harga_dealer < form_.batas_bawah) {
                alert('Harga dealer tidak boleh lebih kecil dari batas bawah !');
                return false;
              }
              if (form_.harga_dealer > form_.batas_atas) {
                alert('Harga dealer tidak boleh lebih besar dari batas bawah !');
                return false;
              }
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('dealer/jasa_h2_d/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save');
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    $('#submitBtn').attr('disabled', false);
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save');
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save');
                    $('#submitBtn').attr('disabled', false);

                  }
                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <!--  <a href="dealer/jasa_h2_d/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>   -->
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
            <table id="datatable_server" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID Jasa</th>
                  <th>Deskripsi</th>
                  <th>Job Type</th>
                  <th>Kategori</th>
                  <th>Tipe Motor</th>
                  <th>Deskripsi Motor</th>
                  <th>Harga Pas</th>
                  <th>Range Harga</th>
                  <th>Harga Dealer</th>
                  <th>Waktu</th>
                  <th>Active</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            $(document).ready(function() {
               dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('dealer/jasa_h2_d/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [9],
                    "className": 'text-center'
                  },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>