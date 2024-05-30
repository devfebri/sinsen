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
            <a href="master/kpb_reminder">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">SMS KPB 1</label>
                    <div class="col-sm-4">
                      <input type="hidden" name="id_kpb" value="<?= isset($row) ? $row->id_kpb : '' ?>" :readonly="mode=='detail'" required>
                      <input type="number" class="form-control" name="sms_kpb1" id="sms_kpb1" autocomplete="off" value="<?= isset($row) ? $row->sms_kpb1 : '' ?>" :readonly="mode=='detail'" required>
                      <input type="hidden" name="id_kpb" id="id_kpb" value="<?= isset($row) ? $row->id_kpb : '' ?>" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Call KPB 1</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="call_kpb1" id="call_kpb1" autocomplete="off" value="<?= isset($row) ? $row->call_kpb1 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">SMS KPB 2</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="sms_kpb2" id="sms_kpb2" autocomplete="off" value="<?= isset($row) ? $row->sms_kpb2 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Call KPB 2</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="call_kpb2" id="call_kpb2" autocomplete="off" value="<?= isset($row) ? $row->call_kpb2 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">SMS KPB 3</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="sms_kpb3" id="sms_kpb3" autocomplete="off" value="<?= isset($row) ? $row->sms_kpb3 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Call KPB 3</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="call_kpb3" id="call_kpb3" autocomplete="off" value="<?= isset($row) ? $row->call_kpb3 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">SMS KPB 4</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="sms_kpb4" id="sms_kpb4" autocomplete="off" value="<?= isset($row) ? $row->sms_kpb4 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Call KPB 4</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" name="call_kpb4" id="call_kpb4" autocomplete="off" value="<?= isset($row) ? $row->call_kpb4 : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                    <label for="inputEmail3" class="col-sm-4 control-label" style="text-align:left">Hari (H- Sebelum Tanggal Servis)</label>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input <?= $disabled ?> type="checkbox" class="form-control flat-red" name="active" value="1" <?= isset($row) ? $row->active == 1 ? 'checked' : '' : 'checked' ?>>
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
          var values = {};
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {

            if (confirm("Apakah anda yakin ?") == true) {

              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('master/kpb_reminder/' . $form) ?>',
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
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
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
            <a href="master/kpb_reminder/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>SMS KPB 1</th>
                <th>Call KPB 1</th>
                <th>SMS KPB 2</th>
                <th>Call KPB 2</th>
                <th>SMS KPB 3</th>
                <th>Call KPB 3</th>
                <th>SMS KPB 4</th>
                <th>Call KPB 4</th>
                <th>Active</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
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
                  url: "<?php echo site_url('master/kpb_reminder/fetch'); ?>",
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