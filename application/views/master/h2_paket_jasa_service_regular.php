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
            <a href="master/<?=$isi?>">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Paket</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" name="kode_paket" id="kode_paket" autocomplete="off" value="<?= isset($row) ? $row->kode_paket : '' ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Paket</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="nama_paket" id="nama_paket" autocomplete="off" value="<?= isset($row) ? $row->nama_paket : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="deskripsi_paket" id="deskripsi_paket" autocomplete="off" value="<?= isset($row) ? $row->deskripsi_paket : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Mileage</label>
                    <div class="col-sm-2">
                      <vue-numeric onkeypress="return number_only(event)" style="float: left;width: 100%;" class="form-control" v-model="mileage" v-bind:minus="false" :readonly="mode=='detail'" :empty-value="0" separator="." />
                    </div>
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
                  <button style="font-size: 12pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>List Service</button><br><br>
                  <table class="table table-bordered table-condensed">
                    <thead>
                      <th style="width: 8%;">No.</th>
                      <th>Nama Pekerjaan</th>
                      <th v-if="mode=='insert' || mode=='edit'" style="width: 8%;text-align:center">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of list_service">
                        <td>{{index+1}}</td>
                        <td>
                          <b>Nama Jasa : {{dt.nama_jasa}}</b>
                          <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                              <div class="box">
                                <div class="box-body">
                                  <b>Detail Work List</b> <br>
                                  <table class="table table-bordered table-condensed">
                                    <thead>
                                      <th>No.</th>
                                      <th>Kode</th>
                                      <th>Nama Pekerjaan</th>
                                    </thead>
                                    <tbody>
                                      <tr v-for="(wl, idx_wl) of dt.detail_work_lists" v-if="dt.detail_work_lists.length>0">
                                        <td>{{idx_wl+1}}</td>
                                        <td>{{wl.kode_detail}}</td>
                                        <td>{{wl.nama_detail}}</td>
                                      </tr>
                                      <tr v-if="dt.detail_work_lists.length==0">
                                        <td colspan="3" style="text-align: center;"><i>Data Kosong</i></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="box">
                                <div class="box-body">
                                  <b>Detail Spareparts</b> <br>
                                  <table class="table table-bordered table-condensed">
                                    <thead>
                                      <th>No.</th>
                                      <th>Kode</th>
                                      <th>Nama Part</th>
                                    </thead>
                                    <tbody>
                                      <tr v-for="(sp, idx_sp) of dt.spareparts" v-if="dt.spareparts.length>0">
                                        <td>{{idx_sp+1}}</td>
                                        <td>{{sp.id_part}}</td>
                                        <td>{{sp.nama_part}}</td>
                                      </tr>
                                      <tr v-if="dt.spareparts.length==0">
                                        <td colspan="3" style="text-align: center;"><i>Data Kosong</i></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'" style="vertical-align: middle;">
                          <button @click.prevent="delWorkList(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td colspan="2">
                        </td>
                        <td align="center">
                          <button onclick="showModalJasa()" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-search"></i></button>
                        </td>
                      </tr>
                    </tfoot>
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
      <?php
      $data['data'] = ['modal_jasa'];
      $this->load->view('modal/h2_jasa', $data); ?>
      <script>
        function pilihJasa(params) {
          for (wl of form_.list_service) {
            if (params.id_jasa == wl.id_jasa) {
              alert("Data sudah dipilih");
              return false;
            }
          }
          form_.list_service.push(params);
          $("#modal_jasa").modal('hide');
        }

        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            mileage: <?= isset($row) ? $row->mileage : 0 ?>,
            list_service: <?= isset($row) ? json_encode($list_service) : '[]' ?>,
          },
          methods: {
            delWorkList: function(index) {
              this.list_service.splice(index, 1);
            }
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
            mileage: form_.mileage,
            list_service: form_.list_service,
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
                url: '<?= base_url('master/' . $isi . '/' . $form) ?>',
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
            <a href="master/<?= $isi ?>/add">
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
                <th>Kode Paket</th>
                <th>Nama Paket</th>
                <th>Mileage</th>
                <th>Total List Service</th>
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
                  url: "<?php echo base_url('master/' . $isi . '/loadData'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [4, 5],
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
    } ?>
  </section>
</div>