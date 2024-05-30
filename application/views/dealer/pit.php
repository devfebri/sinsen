<base href="<?php echo base_url(); ?>" />
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
      <li class="">PIT</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php
          if (isset($row)) { ?>
            loadDetail()
          <?php  }
          ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/pit">
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
              <form class="form-horizontal" id="form_" action="dealer/pit/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group" v-if="mode!='insert'">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID PIT</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="id_pit" value="<?= isset($row) ? $row->id_pit : 'Otomatis Setalah Save' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pit</label>
                    <div class="col-sm-4">
                      <select name="jenis_pit" class="form-control" required v-model="jenis_pit" <?= $disabled ?>>
                        <option value="">--choose--</option>
                        <option value="Reguler">Reguler</option>
                        <option value="Booking">Booking</option>
                        <option value="PIT Express">PIT Express</option>
                        <option value="Fast Track">Fast Track</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-4">
                      <input type="checkbox" <?= $disabled ?> name="active" <?= isset($row) ? $row->active == 1 ? 'checked' : '' : '' ?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Set Booking</label>
                    <div class="col-sm-4">
                      <input type="checkbox" <?= $disabled ?> name="booking" <?= isset($row) ? $row->booking == 1 ? 'checked' : '' : '' ?>>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>Mekanik</button><br><br>
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <th>Kode Dealer MD</th>
                      <th>Honda ID</th>
                      <th>Nama Mekanik</th>
                      <th v-if="mode!='detail'" width='10%'>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(mk, index) of mekanik">
                        <td>{{mk.id_karyawan_dealer}}</td>
                        <td>{{mk.honda_id}}</td>
                        <td>{{mk.nama_lengkap}}</td>
                        <td v-if="mode!='detail'" align='center'>
                          <button type="button" @click.prevent="delMekanik(index)" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot v-if="mode!='detail'">
                      <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                          <button type="button" name="search" class="btn btn-primary btn-flat btn-xs" @click.prevent="showModalMekanik"><i class="fa fa-search"></i> Cari Mekanik</button>
                          <!-- <button class="btn btn-primary btn-sm" type="button" @click.prevent="addMekanik"><i class="fa fa-plus"></i></button> -->
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                      <button type="button" id="submitBtn" onclick="funcSubmit()" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

      <div class="modal fade modalKaryawan" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Data Mekanik</h4>
            </div>
            <div class="modal-body">
              <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_karyawan" style="width: 100%">
                <thead>
                  <tr>
                    <th>Kode Karyawan</th>
                    <th>Honda ID</th>
                    <th>Nama Lengkap</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  $('#tbl_karyawan').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('dealer/pit/fetch_kry') ?>",
                      dataSrc: "data",
                      data: function(d) {
                        // d.kode_item     = $('#kode_item').val();
                        return d;
                      },
                      type: "POST"
                    },
                    "columnDefs": [
                      // { "targets":[4],"orderable":false},
                      {
                        "targets": [3],
                        "className": 'text-center'
                      },
                      // { "targets":[4], "searchable": false } 
                    ]
                  });
                });
                // function loads()
                // {
                //   alert('d');
                //     $('#tabel_harga_sebelumnya').DataTable().ajax.reload();
                // }
              </script>
            </div>
          </div>
        </div>
      </div>

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            jenis_pit: '<?= isset($row) ? $row->jenis_pit : '' ?>',
            mekanik: [],
            mkn: {
              id_karyawan_dealer: ''
            }
          },
          methods: {
            showModalMekanik: function() {
              if (this.mekanik.length == 2) {
                toastr_warning('Maksimal hanya 2 mekanik')
                return false;
              }
              $('.modalKaryawan').modal('show');
            },
            clearMekanik: function() {
              this.mkn = {
                id_karyawan_dealer: ''
              };
            },
            addMekanik: function() {
              if (this.mkn.id_karyawan_dealer == '') {
                alert('Pilih mekanik terlebih dahulu !');
                return false;
              }
              this.mekanik.push(this.mkn);
              this.clearMekanik()
            },
            delMekanik: function(index) {
              this.mekanik.splice(index, 1);
            },
          },
        });

        function pilihKaryawan(kry) {
          for (kry_set of form_.mekanik)
            if (kry.id_karyawan_dealer == kry_set.id_karyawan_dealer) {
              toastr_warning('Mekanik Sudah Dipilih !')
              return false;
            }
          form_.mkn = {
            id_karyawan_dealer: kry.id_karyawan_dealer,
            nama_lengkap: kry.nama_lengkap,
            honda_id: kry.honda_id,
          }
          form_.mekanik.push(form_.mkn);
        }

        function loadDetail() {
          values = {
            id_pit: '<?= isset($row->id_pit) ? $row->id_pit : '' ?>'
          }
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/pit/loadDetail') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                for (mk of response.mekanik) {
                  form_.mekanik.push(mk);
                }
              }
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }

        function funcSubmit() {
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

          var form = $('#form_').serializeArray();
          var values = {
            mekanik: form_.mekanik
          };
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            $.ajax({
              beforeSend: function() {
                $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                $('#submitBtn').attr('disabled', true);
              },
              url: '<?= base_url('dealer/pit/' . $form) ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  window.location = response.link;
                } else {
                  alert(response.pesan);
                  $('#submitBtn').attr('disabled', false);
                }
                $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
              },
              error: function() {
                alert("failure");
                $('#submitBtn').attr('disabled', false);
                $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
              },
              statusCode: {
                500: function() {
                  alert('fail');
                  $('#submitBtn').attr('disabled', false);
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                }
              }
            });
          } else {
            alert('Silahkan isi field required !')
          }
        }
      </script>
    <?php
    } elseif ($set == "form_atur_jam") {
      $form     = '';
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php
          if (isset($row)) { ?>
            loadDetail()
          <?php  }
          ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/pit">
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
              <form class="form-horizontal" id="form_" action="dealer/pit/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="box" v-for="(ls, index) of list">
                    <div class="box-body">
                      <p>Hari : {{ls.hari}}</p>
                      <table class="table table-bordered table-condensed table-striped">
                        <thead>
                          <th>No.</th>
                          <th style="width: 50%;">Jam Pit</th>
                          <th style="text-align: center;">Happy Hour</th>
                          <th>Diskon Happy Hour</th>
                          <th style="text-align: center;">Active</th>
                        </thead>
                        <tbody>
                          <tr v-for="(lj, index_jam) of ls.list_jam">
                            <td>{{index_jam+1}}</td>
                            <td>{{lj.jam}}</td>
                            <td align="center">
                              <input v-model='lj.is_happy_hour' type="checkbox" name='active' true-value='1' false-value='0'>
                            </td>
                            <td><input type="text" class="form-control" v-model="lj.diskon_happy_hour" :disabled="lj.is_happy_hour=='0'"></td>
                            <td align="center">
                              <input v-model='lj.active' type="checkbox" name='active' true-value='1' false-value='0'>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" @click.prevent="funcSubmit()" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save Pengaturan </button>
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
            list: <?= json_encode($list) ?>
          },
          methods: {
            funcSubmit: function() {
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

              var form = $('#form_').serializeArray();
              var values = {
                list: form_.list
              };
              for (field of form) {
                values[field.name] = field.value;
              }
              if ($('#form_').valid()) // check if form is valid
              {
                $.ajax({
                  beforeSend: function() {
                    $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                    $('#submitBtn').attr('disabled', true);
                  },
                  url: '<?= base_url('dealer/pit/save_atur_jam') ?>',
                  type: "POST",
                  data: values,
                  cache: false,
                  dataType: 'JSON',
                  success: function(response) {
                    if (response.status == 'sukses') {
                      window.location = response.link;
                    } else {
                      alert(response.pesan);
                      $('#submitBtn').attr('disabled', false);
                    }
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  },
                  error: function() {
                    alert("failure");
                    $('#submitBtn').attr('disabled', false);
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  },
                  statusCode: {
                    500: function() {
                      alert('fail');
                      $('#submitBtn').attr('disabled', false);
                      $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
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
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/pit/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
              <a href="dealer/pit/add/atur_jam">
                <button class="btn btn-info btn-flat margin"><i class="fa fa-cogs"></i> Atur Jam</button>
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
          <table id="datatables_" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Pit</th>
                <th>Jenis Pit</th>
                <th>Active</th>
                <th>Booking</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pit->result() as $rs) :
                $status = '';
                $button = '';
                $btn_edit = '<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'dealer/pit/edit?id=' . $rs->id_pit . '\'><i class=\'fa fa-edit\'></i></a>';
                if (can_access($isi, 'can_update')) $button = $btn_edit;
              ?>
                <tr>
                  <td><a href="<?= base_url('dealer/pit/detail?id=' . $rs->id_pit) ?>"><?= $rs->id_pit ?></a></td>
                  <td><?= $rs->jenis_pit ?></td>
                  <td><?= $rs->active == 1 ? '<i class="fa fa-check"></i>' : '' ?></td>
                  <td><?= $rs->booking == 1 ? '<i class="fa fa-check"></i>' : '' ?></td>
                  <td align="center">
                    <?= $button ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>

          </table>
          <script>
            $(function() {
              $('#datatables_').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "scrollX": true,
                "order": [],
                "info": true,
                fixedHeader: true,
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "autoWidth": true
              })
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>