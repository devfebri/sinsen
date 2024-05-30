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
    if ($set == "view") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/service_reminder_schedule/insert" class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</a>
            <a href="dealer/service_reminder_schedule/export">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-upload"></i> Export</button>
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
          <!-- <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label>Tanggal</label>
                <input type="text" class="form-control datepicker" id="tanggal" onclick="reload()" value="<?= tanggal() ?>">
              </div>
            </div>
          </div>
          <div class="row" style="border-top:1px solid #f4f4f4;border-bottom:1px solid #f4f4f4;margin-bottom:10px;padding-bottom:10px">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="col-sm-12" align="center" style="padding-top:10px">
                  <button type="button" onclick="reload()" class="btn bg-blue btn-flat"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </div> -->
          <table id="tbl_service_reminder" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>Deskripsi Tipe Unit</th>
                <th>Tgl. Servis Terakhir</th>
                <th>Tipe Servis Terakhir</th>
                <th>Tgl. Servis Berikutnya</th>
                <th>Tipe Servis Berikutnya</th>
                <th>Status Servis Berikutnya</th>
                <th>Status Reminder SMS</th>
                <th>Status Contact Via SMS</th>
                <th>Status Contact Via Call</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalReminderSMS">
            <div class="modal-dialog" style="width:30%">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel"><b>Reminder SMS</b></h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" id="form_reminder">
                    <div class="box-body">
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label>ID Customer</label>
                          <input type="text" class="form-control" readonly id="id_customer">
                          <input type="hidden" id="id_service_reminder" class="form-control" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label>Nama Customer</label>
                          <input type="text" class="form-control" readonly id="nama_customer">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label>Isi Pesan</label>
                          <div class="box box-primary">
                            <div class="box-header">
                              <div class="box-tools pull-right">
                                <button class="btn btn-info btn-sm" onclick="CopyToClipboard('copyReport')"><i class="fa fa-copy"></i></button>
                              </div>
                            </div>
                            <div class="box-body" id="copyReport" style="margin-top:10px"></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label>Status Reminder SMS</label>
                          <select class="form-control" name="status_reminder_sms">
                            <option value="">- choose -</option>
                            <option value="Terkirim">Terkirim</option>
                            <option value="Tidak Terkirim">Tidak Terkirim</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-sm-12" align="center">
                      <button class="btn btn-primary" type="button" id="btnSaveReminder" onclick="saveReminderSMS()"><i class="fa fa-save"></i> Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <script>
            $(document).ready(function() {
              var dataTable = $('#tbl_service_reminder').DataTable({
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
                  url: "<?php echo site_url('dealer/service_reminder_schedule/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    // d.tanggal_awal = $('#tanggal_awal').val();
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [10],
                    "orderable": false
                  },
                  {
                    "targets": [10],
                    "className": 'text-center'
                  },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });

            function reload() {
              $('#tbl_service_reminder').DataTable().ajax.reload();
            }

            function CopyToClipboard(id) {
              if (document.selection) {
                var range = document.body.createTextRange();
                range.moveToElementText(document.getElementById(id));
                range.select().createTextRange();
                document.execCommand("copy");

              } else if (window.getSelection) {
                var range = document.createRange();
                range.selectNode(document.getElementById(id));
                window.getSelection().addRange(range);
                document.execCommand("copy");
                alert("Teks berhasil disalin")
              }
            }

            function showModalReminderSms(id_service_reminder) {
              let values = {
                id_service_reminder: id_service_reminder
              }
              $.ajax({
                beforeSend: function() {},
                url: '<?= base_url('dealer/service_reminder_schedule/getReminderSms') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    $('#id_service_reminder').val(response.data.id_service_reminder);
                    $('#id_customer').val(response.data.id_customer);
                    $('#nama_customer').val(response.data.nama_customer);
                    $('#copyReport').html(response.data.pesan_sms);
                    $('#modalReminderSMS').modal('show');
                  } else {
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("Something Went Wrong !");
                }
              });
            }

            function saveReminderSMS() {
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
                    id_service_reminder: $('#id_service_reminder').val()
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
                    url: '<?= base_url('dealer/service_reminder_schedule/save_reminder') ?>',
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
                      alert("Something Went Wrong !");
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
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == "contact_customer") {
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
            <a href="dealer/service_reminder_schedule">
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
              <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
              <div class="col-sm-4">
                <input type="hidden" value="<?= isset($row) ? $row->id_service_reminder : '' ?>" id="id_service_reminder" readonly>
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->id_customer : '' ?>" id="id_customer" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->nama_customer : '' ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">No. HP</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->no_hp : '' ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->no_polisi : '' ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Servis Terakhir</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->tgl_servis_sebelumnya : '' ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tipe Servis Terakhir</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->tipe_servis_sebelumnya : '' ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tgl Servis Berikutnya</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->tgl_servis_berikutnya : '' ?>" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Tipe Servis Berikutnya</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" autocomplete="off" value="<?= isset($row) ? $row->tipe_servis_berikutnya : '' ?>" readonly>
              </div>
            </div>
            <!-- <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Contactable Via SMS</label>
              <div class="col-sm-4">
                <select class="form-control" id="status_contact_sms">
                  <option value=null>- choose -</option>
                  <option value="Terkirim">Terkirim</option>
                  <option value="Tidak Terkirim">Tidak Terkirim</option>
                </select>
              </div>
            </div> -->
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Contactable Via Call</label>
              <div class="col-sm-4">
                <select class="form-control" v-model="contactable_via_call">
                  <option value=null>- choose -</option>
                  <option value=1>Dapat Dihubungi</option>
                  <option value=0>Tidak Dapat Dihubungi</option>
                </select>
              </div>
            </div>
            <div class="box-footer" v-if="mode!='detail'">
              <div class="col-sm-12" align="center">
                <button v-if="contactable_via_call==1" type="button" @click.prevent="showModalResult" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Update FU Service Reminder</button>
                <button v-if="contactable_via_call==0 && reschedule==1" type="button" @click.prevent="showModalStatusCall" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Update Status Call</button>
                <button v-if="contactable_via_call==0 && reschedule==0" type="button" @click.prevent="showModalStatusCall" class="btn btn-primary btn-info"><i class="fa fa-save"></i> Save</button>
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
        <h4 class="modal-title" id="myModalLabel"><b>Update FU Service Reminder</b></h4>
      </div>
      <div class="modal-body">
        <a href="<?= base_url('dealer/manage_booking/add') ?>" target="_blank" class="btn btn-primary btn-flat">Manage Booking</a>
        <form class="form-horizontal" id="form_result">
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-3 control-label">Booking Status</label>
              <div class="col-sm-8">
                <select class="form-control" name="booking_status" v-model="booking_status" required>
                  <option value="">- choose -</option>
                  <option value="1">Ya</option>
                  <option value="0">Tidak</option>
                </select>
              </div>
            </div>
            <div class="form-group" v-if="booking_status=='0'">
              <label for="inputEmail3" class="col-sm-3 control-label">Alasan</label>
              <div class="col-sm-8">
                <textarea class="form-control" name="alasan_tidak_booking" required></textarea>
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
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalStatusCall">
  <div class="modal-dialog" style="width:45%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel"><b>Update Status Call</b></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form_status_call">
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-3 control-label">Status Telepon</label>
              <div class="col-sm-8">
                <select class="form-control" name="status_call" required>
                  <option value="">- choose -</option>
                  <option value="Salah Sambung">Salah Sambung</option>
                  <option value="Nomor Salah">Nomor Salah</option>
                  <option value="Nomor Tidak Terdaftar">Nomor Tidak Terdaftar</option>
                  <option value="Telepon ditolak">Telepon ditolak</option>
                  <option value="Telepon tidak diangkat">Telepon tidak diangkat</option>
                  <option value="Dialihkan">Dialihkan</option>
                  <option value="Tidak Aktif">Tidak Aktif</option>
                  <option value="Diluar Jangkauan">Diluar Jangkauan</option>
                </select>
              </div>
            </div>
            <div class="form-group" v-if="reschedule==1">
              <label for="inputEmail3" class="col-sm-3 control-label">Reschedule</label>
              <div class="col-sm-8">
                <input type="text" class="form-control datepicker" name="tgl_contact_call" required />
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
            <button type="button" id="btnSaveStatusCall" onclick="saveStatusCall()" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var form_status_call = new Vue({
    el: "#form_status_call",
    data: {
      reschedule: <?= $reshcedule ?>,
    }
  })
  var form_result = new Vue({
    el: "#form_result",
    data: {
      booking_status: '',
    }
  })
  var form_ = new Vue({
    el: '#form_',
    data: {
      sa: '',
      mode: '<?= $mode ?>',
      contactable_via_call: null,
      reschedule: <?= $reshcedule ?>,
      details: []
    },
    methods: {
      showModalResult: function() {
        $('#modalResult').modal('show');
      },
      showModalStatusCall: function() {
        $('#modalStatusCall').modal('show');
      },
      saveClose: function() {
        if (confirm("Apakah anda yakin ?") == true) {
          let values = {
            id_service_reminder: $('#id_service_reminder').val()
          }
          $.ajax({
            beforeSend: function() {
              $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
              $('#submitBtn').attr('disabled', true);
            },
            url: '<?= base_url('dealer/service_reminder_schedule/save_close') ?>',
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
              $('#submitBtn').html('<i class="fa fa-save"></i> Save');
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#submitBtn').html('<i class="fa fa-save"></i> Save');
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
          id_service_reminder: $('#id_service_reminder').val()
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
          url: '<?= base_url('dealer/service_reminder_schedule/save_result') ?>',
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
            alert("Something Went Wrong !");
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

  function saveStatusCall() {
    $('#form_status_call').validate({
      highlight: function(input) {
        $(input).parents('.form-group').addClass('has-error');
      },
      unhighlight: function(input) {
        $(input).parents('.form-group').removeClass('has-error');
      }
    })
    if ($('#form_status_call').valid()) // check if form is valid
    {
      if (confirm("Apakah anda yakin ?") == true) {
        let values = {
          id_service_reminder: $('#id_service_reminder').val(),
          status_contact_sms: $('#status_contact_sms').val(),
        }
        let form = $('#form_status_call').serializeArray();
        for (field of form) {
          values[field.name] = field.value;
        }
        $.ajax({
          beforeSend: function() {
            $('#btnSaveStatusCall').html('<i class="fa fa-spinner fa-spin"></i> Process');
            $('#btnSaveStatusCall').attr('disabled', true);
          },
          url: '<?= base_url('dealer/service_reminder_schedule/save_status_call') ?>',
          type: "POST",
          data: values,
          cache: false,
          dataType: 'JSON',
          success: function(response) {
            if (response.status == 'sukses') {
              window.location = response.link;
            } else {
              alert(response.pesan);
              $('#btnSaveStatusCall').attr('disabled', false);
            }
            $('#btnSaveStatusCall').html('<i class="fa fa-save"></i> Save');
          },
          error: function() {
            alert("Something Went Wrong !");
            $('#btnSaveStatusCall').html('<i class="fa fa-save"></i> Save');
            $('#btnSaveStatusCall').attr('disabled', false);

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
<?php } elseif ($set == "export") {
?>
  <div class="box box-default">
    <div class="box-header with-border">
      <div class="row">
        <div class="col-md-12">
          <form class="form">
            <input type='hidden' name="cetak" value=1>
            <div class="box-body">
              <div class="row">
                <div class="col-sm-3">
                  <label>Start Date</label>
                  <input type="text" class="form-control datepicker" id="start_date" name="start_date">
                </div>
                <div class="col-sm-3">
                  <label>End Date</label>
                  <input type="text" class="form-control datepicker" id="end_date" name="end_date">
                </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-12" align="center">
                <button type="button" onclick="getReport()" class="btn btn-primary btn-flat"><i class="fa fa-download"></i> Export .xls</button>
              </div>
              <div style="min-height: 600px">
                <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div><!-- /.box -->
  <script>
    function getReport() {
      var value = {
        start_date: document.getElementById("start_date").value,
        end_date: document.getElementById("end_date").value,
        cetak: 'cetak',
      }

      if (value.start_date == '' || value.end_date == '') {
        alert('Isi data dengan lengkap ..!');
        return false;
      } else {
        //alert(value.tipe);
        $('.loader').show();
        $('#btnShow').disabled;
        $("#showReport").attr("src", '<?php echo site_url("dealer/service_reminder_schedule/export?") ?>cetak=' + value.cetak + '&start_date=' + value.start_date + '&end_date=' + value.end_date);
        document.getElementById("showReport").onload = function(e) {
          $('.loader').hide();
        };
      }
    }
  </script>
<?php } elseif ($set == 'form') {
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
      // // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
      // if (typeof value !== "number") {
      //     return value;
      // }
      return "Rp. " + accounting.formatMoney(value, "", 0, ".", ",");
    });
  </script>
  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">
        <a href="dealer/service_reminder_schedule">
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
                <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="id_customer" id="id_customer" autocomplete="off" value="<?= isset($row) ? $row->id_customer : '' ?>" :readonly="mode=='detail'" required readonly>
                </div>
                <div class="col-sm-1">
                  <button type="button" onclick="showModalAllCustomer()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="nama_customer" id="nama_customer" autocomplete="off" value="<?= isset($row) ? $row->nama_customer : '' ?>" :readonly="mode=='detail'" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label" v-if="mode=='insert' || mode=='edit' ">Tipe Servis <i style="color: red">*</i></label>
                <div class="col-sm-4" v-if="mode=='insert' || mode=='edit' ">
                  <select name="id_type" id="id_type" class="form-control select2" required>
                    <option value="">-choose-</option>
                    <?php $tipe_servis = $this->db->get("ms_h2_jasa_type")->result() ?>
                    <?php foreach ($tipe_servis as $rs) : ?>
                      <option value="<?= $rs->id_type ?>"><?= $rs->id_type . ' | ' . $rs->deskripsi ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl Servis</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control datepicker" name="tgl_servis" id="tgl_servis" autocomplete="off" value="<?= isset($row) ? $row->tgl_servis : '' ?>" :readonly="mode=='detail'" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl Reminder SMS</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control datepicker" name="tgl_reminder_sms" id="tgl_reminder_sms" autocomplete="off" value="<?= isset($row) ? $row->tgl_reminder_sms : '' ?>" :readonly="mode=='detail'" readonly>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl Contact Via Call</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control datepicker" name="tgl_contact_call" id="tgl_contact_call" autocomplete="off" value="<?= isset($row) ? $row->tgl_contact_call : '' ?>" :readonly="mode=='detail'" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tgl Contact Via SMS</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control datepicker" name="tgl_contact_sms" id="tgl_contact_sms" autocomplete="off" value="<?= isset($row) ? $row->tgl_contact_sms : '' ?>" :readonly="mode=='detail'" readonly>
                </div>
              </div>
            </div><!-- /.box-body -->
            <div class=" box-footer">
              <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
              </div>
            </div><!-- /.box-footer -->
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
      $data['data'] = ['allCustomer', 'customer_h23'];
      $this->load->view('dealer/h2_api', $data); ?>
  <script>
    var form_ = new Vue({
      el: '#form_',
      data: {
        mode: '<?= $mode ?>',
        detail: {},
        details: <?= isset($row) ? json_encode($details) : '[]' ?>,
      },
      methods: {
        clearDetail: function() {
          this.detail = {}
        },
        addDetails: function() {
          this.details.push(this.detail);
          this.clearDetail();
        },
        delDetails: function(index) {
          this.details.splice(index, 1);
        },
      }
    })

    function pilihAllCustomer(cus) {
      $('#id_customer').val(cus.id_customer);
      $('#nama_customer').val(cus.nama_customer);
    }

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
        details: form_.details,
        dealers: form_.dealers,
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
            url: '<?= base_url('dealer/service_reminder_schedule/' . $form) ?>',
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

            }
          });
        } else {
          return false;
        }
      } else {
        alert('Silahkan isi field required !')
      }
    })

    function pilihJasa(js) {
      form_.detail = {
        id_jasa: js.id_jasa,
        deskripsi: js.deskripsi,
        harga: js.harga
      }
    }

    function pilihAHASS(ah) {
      form_.dealer = {
        kode_dealer_md: ah.kode_dealer_md,
        id_dealer: ah.id_dealer,
        nama_dealer: ah.nama_dealer
      }
      console.log(form_.dealer);
    }
  </script>
<?php } ?>
</section>
</div>