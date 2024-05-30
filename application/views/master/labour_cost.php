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
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/labour_cost">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Mulai Berlaku</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control datepicker2" name="tgl_mulai_berlaku" id="tgl_mulai_berlaku" autocomplete="off" value="<?= isset($row) ? $row->tgl_mulai_berlaku : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Kendaraan</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>ID Tipe Kendaraan</th>
                      <th>Tipe Kendaraan</th>
                      <th align='right'>Harga Terakhir</th>
                      <th v-if="mode=='insert'">Ubah</th>
                      <th v-if="mode=='insert'">Harga Baru</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{dt.id_tipe_kendaraan}}</td>
                        <td>{{dt.tipe_ahm}}</td>
                        <td align='right'>{{dt.harga_terakhir | toCurrency}}</td>
                        <td align='center' v-if="mode=='insert'">
                          <input v-model='dt.ceklist' type="checkbox" true-value='1' false-value='0' :disabled="mode=='detail'">
                        </td>
                        <td v-if="mode=='insert'">
                          <input v-model='dt.nominal' class='form-control isi2' type="text" :disabled="mode=='detail' || dt.ceklist==0">
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
            details: <?= isset($details) ? json_encode($details) : '[]' ?>,
          },
          methods: {
            filter_tipe: function() {
              return this.details.filter(prt => prt.ceklist == 1)
            },
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
            details: form_.filter_tipe()
          };
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
                url: '<?= base_url('master/labour_cost/' . $form) ?>',
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
            <a href="master/labour_cost/add">
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
                <th>ID Labour Cost</th>
                <th>Total Kendaraan</th>
                <th>Tgl. Mulai berlaku</th>
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
                  url: "<?php echo site_url('master/labour_cost/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // {
                  //   "targets": [3],
                  //   "className": 'text-right'
                  // },
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