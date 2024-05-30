<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Absen Mekanik</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php if ($set == 'form') :
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <style>
        .isi {
          height: 25px;
          padding-left: 4px;
          padding-right: 4px;
        }
      </style>

      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });

        $(document).ready(function() {
          form_.showDetailAbsensi('ya')
          <?php if (isset($row)) { ?>
            form_.showDetailAbsensi()
          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/absen_mekanik">
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
          <form id="form_">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
              <div class="col-sm-4">
                <?php if (isset($row)) { ?>
                  <input type="hidden" name="id_absen" value="<?= isset($row) ? $row->id_absen : '' ?>">
                <?php } ?>
                <input type="text" class="form-control" name="tanggal" autocomplete="off" value="<?= isset($row) ? $row->tanggal : date('Y-m-d') ?>" readonly>
              </div>
              <div class="col-sm-1" v-if="mode=='insert'">
                <button type="button" id="btnShow" class="btn btn-flat btn-primary" @click.prevent="showDetailAbsensi('ya')">Show</button>
              </div>
            </div>
            <br><br><br>
            <div v-if="dt_absen.length>0">
              <div class="col-md-12">
                <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Absensi Mekanik</button><br><br>
              </div>
              <div class="col-sm-12">
                <table class="table table-bordered table-hover table-condensed table-stripped">
                  <thead>
                    <th>No</th>
                    <th>ID Karyawan Dealer</th>
                    <th>Honda ID</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Hadir</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dt, index) of dt_absen">
                      <td>{{index+1}}</td>
                      <td>{{dt.id_karyawan_dealer}}</td>
                      <td>{{dt.honda_id}}</td>
                      <td>{{dt.nama_lengkap}}</td>
                      <td>{{dt.jabatan}}</td>
                      <td><input type="checkbox" v-model="dt.aktif" :disabled="mode=='detail'"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" align="center">
                  <button type="button" id="submitBtn" @click.prevent="saveFunc" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <script>
          var form_ = new Vue({
            el: '#form_',
            data: {
              kosong: '',
              mode: '<?= $mode ?>',
              dt_absen: [],
            },
            methods: {
              showDetailAbsensi: function(cek_tanggal = null) {
                let values = {
                  cek_tanggal: cek_tanggal,
                  mode: form_.mode
                };
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                if (values.tanggal == '') {
                  alert('Silahkan pilih tanggal terlebih dahulu !');
                  return false;
                }
                $.ajax({
                  beforeSend: function() {
                    $('#btnShow').html('<i class="fa fa-spinner fa-spin"></i> Process');
                    $('#btnShow').attr('disabled', true);
                  },
                  url: '<?= base_url('dealer/absen_mekanik/showDetailAbsensi') ?>',
                  type: "POST",
                  data: values,
                  cache: false,
                  dataType: 'JSON',
                  success: function(response) {
                    $('#btnShow').html('Show');
                    $('#btnShow').attr('disabled', false);
                    if (response.status == 'sukses') {
                      form_.dt_absen = [];
                      for (dtl of response.data) {
                        // console.log(dtl.aktif);
                        if (dtl.aktif == 0) {
                          dtl.aktif = false
                        } else {
                          dtl.aktif = true
                        }
                        form_.dt_absen.push(dtl);
                      }
                      // console.log(form_.dt_absen);
                    } else {
                      form_.dt_absen = [];
                      alert(response.pesan);
                    }
                  },
                  error: function() {
                    alert("failure");
                    $('#btnShow').html('Show');
                    $('#btnShow').attr('disabled', false);
                  },
                  statusCode: {
                    500: function() {
                      alert('fail');
                      $('#btnShow').html('Show');
                      $('#btnShow').attr('disabled', false);
                    }
                  }
                });
              },
              saveFunc: function() {
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
                if ($('#form_').valid()) // check if form is valid
                {
                  let values = {
                    absen: form_.dt_absen
                  };
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#submitBtn').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/absen_mekanik/' . $form) ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      if (response.status == 'sukses') {
                        window.location = response.link;
                      } else {
                        alert(response.pesan);
                        $('#submitBtn').attr('disabled', false);
                      }
                    },
                    error: function() {
                      alert("failure");
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                      $('#submitBtn').attr('disabled', false);
                    },
                    statusCode: {
                      500: function() {
                        alert('fail');
                        $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                        $('#submitBtn').attr('disabled', false);
                      }
                    }
                  });
                } else {
                  alert('Silahkan isi field required !')
                }
              },
            },
          });
        </script>
      <?php endif ?>
      <?php
      if ($set == "index") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?php if (can_access($isi, 'can_insert')) : ?>
                <a href="dealer/absen_mekanik/add">
                  <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
                </a>
              <?php endif; ?>
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
                <th>Tanggal</th>
                <th>Total Mekanik</th>
                <th>Aktif</th>
                <th>Tidak Aktif</th>
                <th>Action</th>
              </thead>
            </table>
            <script>
              $(document).ready(function() {
                var dataTable = $('#datatable_server').DataTable({
                  "processing": true,
                  "serverSide": true,
                  "scrollX": true,
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
                    url: "<?php echo site_url($folder . '/' . $isi . '/fetch'); ?>",
                    type: "POST",
                    dataSrc: "data",
                    data: function(d) {
                      return d;
                    },
                  },
                  "columnDefs": [
                    // { "targets":[2],"orderable":false},
                    {
                      "targets": [4],
                      "className": 'text-center'
                    },
                    // {
                    //   "targets": [3],
                    //   "className": 'text-right'
                    // },
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