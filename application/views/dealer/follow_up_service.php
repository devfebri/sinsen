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
                        <?php $tipe_motor = $this->db->query("SELECT tipe_motor,deskripsi FROM ms_ptm GROUP BY tipe_motor");
                        foreach ($tipe_motor->result() as $rs) {
                          $select = isset($row) ? $row->tipe_motor == $rs->tipe_motor ? 'selected' : '' : '';
                        ?>
                          <option value="<?= $rs->tipe_motor ?>" <?= $select ?>><?= $rs->tipe_motor . ' | ' . $rs->deskripsi ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div class="col-sm-4">
                      <select name="kategori" id="kategori" class="form-control select2" disabled v-model="kategori" required>
                        <option value="">-choose-</option>
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
          <table id="tbl_folup_service" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Follow Up</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>Deskripsi Tipe Unit</th>
                <th>ID Front Desk</th>
                <th>Respon Service</th>
                <th>Status Follow Up</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#tbl_folup_service').DataTable({
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
                  url: "<?php echo site_url('dealer/follow_up_service/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [7],
                    "orderable": false
                  },
                  {
                    "targets": [7],
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
    } elseif ($set == "service_history") {
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
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/follow_up_service">
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
          <form id="form_" class='form-horizontal'>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">ID Follow Up</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->id_follow_up : '' ?>" id="id_follow_up" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->id_customer : '' ?>" id="id_customer" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="tanggal" autocomplete="off" value="<?= isset($row) ? $row->nama_customer : '' ?>" readonly>
              </div>
            </div>
            <br>
            <div class="col-md-12">
              <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Service History</button><br><br>
            </div>
            <div class="col-sm-12">
              <table id="tbl_service_history" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="10%">Tgl. Servis</th>
                    <th width="12%">No. Work Order</th>
                    <th>Keluhan</th>
                    <th width="8%">Aksi</th>
                  </tr>
                </thead>
              </table>
              <script>
                $(document).ready(function() {
                  var dataTable = $('#tbl_service_history').DataTable({
                    "processing": true,
                    "serverSide": true,

                    "searching": false,
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
                      url: "<?php echo site_url('dealer/follow_up_service/fetch_service_history'); ?>",
                      type: "POST",
                      dataSrc: "data",
                      data: function(d) {
                        d.id_customer = $('#id_customer').val();
                        return d;
                      },
                    },
                    "columnDefs": [{
                        "targets": [3],
                        "orderable": false
                      },
                      {
                        "targets": [3],
                        "className": 'text-center'
                      },
                      // // { "targets":[0],"checkboxes":{'selectRow':true}}
                      // { "targets":[4],"className":'text-right'}, 
                      // // { "targets":[2,4,5], "searchable": false } 
                    ],
                  });
                });
              </script>
              </br>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Contactable</label>
                <div class="col-sm-4">
                  <select class="form-control" v-model="contactable" :disabled="mode=='detail'">
                    <option value=null>- choose -</option>
                    <option value=1>Ya</option>
                    <option value=0>Tidak</option>
                  </select>
                </div>
              </div>
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" align="center">
                  <button v-if="contactable==1" type="button" @click.prevent="showModalResult" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Update & Record FU Result</button>
                  <button v-if="contactable==0 && can_reschedule==1" type="button" @click.prevent="showModalSrvReminder" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Update FU Service Reminder</button>
                  <button v-if="contactable==0 && can_reschedule==0" type="button" id="submitBtn" @click.prevent="saveClose" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save & Closed Follow Up</button>
                </div>
              </div>
            </div>
          </form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalResult">
        <div class="modal-dialog" style="width:45%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel"><b>Update & Record FU Result</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" id="form_result">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Respon Service</label>
                    <div class="col-sm-8">
                      <select class="form-control" name="respon_service" required>
                        <option value="">- choose -</option>
                        <option value="1">OK</option>
                        <option value="0">Not OK</option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-sm-12" align="center">
                  <button type="button" id="btnSaveResult" onclick="saveResult()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSrvReminder">
        <div class="modal-dialog" style="width:45%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel"><b>Update FU Service Reminder</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" id="form_reminder">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Tgl Follow Up</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control datepicker" name="tgl_follow_up" required />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Keterangan</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" name="keterangan" required />
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-sm-12" align="center">
                  <button type="button" id="btnSaveReminder" onclick="saveReminder()" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = ['detailWO'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            sa: '',
            mode: '<?= $mode ?>',
            contactable: null,
            can_reschedule: <?= $reshcedule ?>,
            details: []
          },
          methods: {
            showModalResult: function() {
              $('#modalResult').modal('show');
            },
            showModalSrvReminder: function() {
              $('#modalSrvReminder').modal('show');
            },
            saveClose: function() {
              if (confirm("Apakah anda yakin ?") == true) {
                let values = {
                  id_follow_up: $('#id_follow_up').val()
                }
                $.ajax({
                  beforeSend: function() {
                    $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                    $('#submitBtn').attr('disabled', true);
                  },
                  url: '<?= base_url('dealer/follow_up_service/save_close') ?>',
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
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save & Closed Follow Up');
                  },
                  error: function() {
                    alert("failure");
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save & Closed Follow Up');
                    $('#submitBtn').attr('disabled', false);

                  }
                });
              } else {
                return false;
              }
            }
          }
        })

        function saveResult() {
          $('#form_result').validate({
            highlight: function(input) {
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
            }
          })
          if ($('#form_result').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              let values = {
                id_follow_up: $('#id_follow_up').val()
              }
              let form = $('#form_result').serializeArray();
              for (field of form) {
                values[field.name] = field.value;
              }
              $.ajax({
                beforeSend: function() {
                  $('#btnSaveResult').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#btnSaveResult').attr('disabled', true);
                },
                url: '<?= base_url('dealer/follow_up_service/save_result') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#btnSaveResult').attr('disabled', false);
                  }
                  $('#btnSaveResult').html('<i class="fa fa-save"></i> Save');
                },
                error: function() {
                  alert("failure");
                  $('#btnSaveResult').html('<i class="fa fa-save"></i> Save');
                  $('#btnSaveResult').attr('disabled', false);

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')

          }
        }

        function saveReminder() {
          $('#form_reminder').validate({
            highlight: function(input) {
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
            }
          })
          if ($('#form_reminder').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              let values = {
                id_follow_up: $('#id_follow_up').val()
              }
              let form = $('#form_reminder').serializeArray();
              for (field of form) {
                values[field.name] = field.value;
              }
              $.ajax({
                beforeSend: function() {
                  $('#btnSaveReminder').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#btnSaveReminder').attr('disabled', true);
                },
                url: '<?= base_url('dealer/follow_up_service/save_reminder') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#btnSaveReminder').attr('disabled', false);
                  }
                  $('#btnSaveReminder').html('<i class="fa fa-save"></i> Save');
                },
                error: function() {
                  alert("failure");
                  $('#btnSaveReminder').html('<i class="fa fa-save"></i> Save');
                  $('#btnSaveReminder').attr('disabled', false);

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')

          }
        }
      </script>
    <?php } ?>
  </section>
</div>