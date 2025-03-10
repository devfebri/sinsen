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
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $isi ?>/upload"> <button class="btn btn-info btn-flat margin"><i class="fa fa-upload"></i> Upload .csv</button>
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
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>ID Tipe Kendaraan</th>
                <th>ID Warna</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
              </tr>
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
                    d.periode = '<?= get_ym() ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  // {
                  //   "targets": [7, 8],
                  //   "className": 'text-center'
                  // },
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
    <?php } elseif ($set == "history") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Kode Dealer</th>
                <th>Honda ID</th>
                <th>Kode Tipe</th>
                <th>Tipe</th>
                <th>Target</th>
                <th>Aksi</th>
              </tr>
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
                    d.is_history = true;
                    d.periode_lebih_kecil = '<?= get_ym() ?>';
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [7],
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
    <?php } elseif ($set == 'upload') { ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= 'dealer/' . $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id='form_' method="post" enctype="multipart/form-data">
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

                  <div class="alert alert-danger alert-dismissable" v-if="error==true">
                    <strong>Telah terjadi kesalahan :</strong>
                    <ul>
                      <li v-for="(er,index) of error_list">
                        Line {{index}}
                        <ul>
                          <li v-for="(e1) of er">{{e1}}</li>
                        </ul>
                      </li>
                    </ul>
                    <strong>Upload Data Gagal !</strong>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                    <div class="col-sm-10">
                      <input type="file" accept=".csv" required class="form-control" autofocus name="userfile">
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button type="button" id='submitBtn' name="process" class="btn btn-info btn-flat">Start Upload</button>
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
            error: false,
            error_list: ''
          },
        })
        $('#submitBtn').click(function() {
          $('#form_').validate({
            highlight: function(element, errorClass, validClass) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
              } else {
                $(element).parents('.form-input').addClass('has-error');
              }
            },
            unhighlight: function(element, errorClass, validClass) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
              } else {
                $(element).parents('.form-input').removeClass('has-error');
              }
            },
            errorPlacement: function(error, element) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + elem.attr("id") + "-container").parent();
                error.insertAfter(element);
              } else {
                error.insertAfter(element);
              }
            }
          })
          var values = new FormData($('#form_')[0]);
          if ($('#form_').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                  form_.error = false;
                  form_.error_list = '';
                },
                enctype: 'multipart/form-data',
                url: '<?= base_url($folder . '/' . $isi . '/import_db') ?>',
                type: "POST",
                data: values,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    if (response.tipe == 'html') {
                      form_.error = true;
                      form_.error_list = response.pesan;
                    } else {
                      alert(response.pesan);
                    }
                    $('#submitBtn').attr('disabled', false);
                  }
                  $('#submitBtn').html('Start Upload');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('Start Upload');
                  $('#submitBtn').attr('disabled', false);

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
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          // return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          }
        });

        $(document).ready(function() {})
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $this->uri->segment(2); ?>"> <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <div class="col-sm-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">

                <div class="box-body">
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tahun</label>
                      <div class="col-sm-4">
                        <input type="number" name="tahun" readonly class="form-control" required v-model="row.tahun">
                        <input type="hidden" name="id" :readonly="mode=='detail'" class="form-control" required v-model="row.id">
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Bulan</label>
                      <div class="col-sm-4">
                        <select class='form-control' v-model='row.bulan' name='bulan' :disabled="mode=='detail'">
                          <option value=''>-choose-</option>
                          <?php for ($i = 1; $i <= 12; $i++) {  ?>
                            <option value='<?= $i ?>'><?= $i ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Honda ID</label>
                      <div class="col-sm-4">
                        <input type="text" name="honda_id" readonly v-model="row.honda_id" class="form-control" required>
                      </div>
                      <label class="col-sm-2 control-label">Nama Lengkap</label>
                      <div class="col-sm-3">
                        <input type="text" name="nama_lengkap" readonly v-model="row.nama_lengkap" class="form-control" required>
                      </div>
                      <div class="col-sm-1">
                        <button type='button' v-if="mode!='detail'" class="btn btn-flat btn-primary" onclick="showModaKaryawanDealer()"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">ID Tipe Kendaraan</label>
                      <div class="col-sm-4">
                        <input type="text" name="id_tipe_kendaraan" readonly v-model="row.id_tipe_kendaraan" class="form-control" required>
                      </div>
                      <label class="col-sm-2 control-label">Deskripsi</label>
                      <div class="col-sm-3">
                        <input type="text" name="tipe_ahm" readonly v-model="row.tipe_ahm" class="form-control" required>
                      </div>
                      <div class="col-sm-1">
                        <button type='button' v-if="mode!='detail'" class="btn btn-flat btn-primary" onclick="showModalTipeKendaraan()"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target Prospek</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_prospek" :readonly="mode=='detail'" class="form-control" v-model="row.target_prospek" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target SPK</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_spk" :readonly="mode=='detail'" class="form-control" v-model="row.target_spk" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target Sales</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_sales" :readonly="mode=='detail'" class="form-control" v-model="row.target_sales" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target Diskon (Unit)</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_diskon" :readonly="mode=='detail'" class="form-control" v-model="row.target_diskon" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">All Target Unit</label>
                      <div class="col-sm-4">
                        <input type="number" name="target" :readonly="mode=='detail'" class="form-control" v-model="row.target" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Target Diskon (Amount)</label>
                      <div class="col-sm-4">
                        <input type="number" name="target_diskon_amount" :readonly="mode=='detail'" class="form-control" v-model="row.target_diskon_amount" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-4">
                      <input v-model='active' type="checkbox" name='active' true-value='1' false-value='0' :disabled="mode=='detail'">
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="submitBtn" @click.prevent="save_data" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['tipe_kendaraan', 'karyawan_dealer'];
        $this->load->view('dealer/h2_api', $data); ?>
        <script>
          function pilihTipeKendaraan(item) {
            form_.row.id_tipe_kendaraan = item.id_tipe_kendaraan;
            form_.row.tipe_ahm = item.tipe_ahm;
          }

          function pilihKaryawanDealer(params) {
            form_.row.honda_id = params.id_flp_md
            form_.row.nama_lengkap = params.nama_lengkap
          }
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              active: '<?= isset($row) ? $row->active : '' ?>',
              row: <?= isset($row) ? json_encode($row) : "{id_tipe_kendaraan:'',tahun:" . date('Y') . ",bulan:'',honda_id:'',target:'',id:''}" ?>,
            },
            methods: {
              save_data: function() {
                $('#form_').validate({
                  rules: {
                    'checkbox': {
                      required: true
                    }
                  },
                  highlight: function(input) {
                    $(input).parents('.form-input').addClass('has-error');
                  },
                  unhighlight: function(input) {
                    $(input).parents('.form-input').removeClass('has-error');
                  }
                })
                if ($('#form_').valid()) // check if form is valid
                {
                  let values = {};
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      url: '<?= base_url($folder . '/' . $isi . '/' . $form) ?>',
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
                        alert("Something Went Wrong !");
                        $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                        $('#submitBtn').attr('disabled', false);
                      },
                    });
                  } else {
                    return false;
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              },
              showDetailTransaksi: function(dtl) {
                console.log(dtl)
              },
              clearDetail: function() {
                this.dtl = {}
              },
              addDetails: function() {
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
            },
            watch: {
              row: {
                deep: true,
                handler: function() {
                  this.row.target = parseInt(this.row.target_prospek) + parseInt(this.row.target_spk) + parseInt(this.row.target_sales) + parseInt(this.row.target_diskon);
                }
              }
            }
          });
        </script>
      <?php } ?>
  </section>
</div>