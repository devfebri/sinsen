<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">DMS Extension</li>
      <li class="">H1</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="<?= $folder . '/' . $isi ?>/add"> <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
              <a href="<?= $folder . '/' . $isi ?>/received"> <button class="btn btn-success btn-flat margin"><i class="fa fa-envelope"></i> Received Message</button>
              </a>
            <?php endif; ?>
            <a href="<?= $folder . '/' . $isi ?>/history"> <button class="btn btn-primary btn-flat margin"><i class="fa fa-list"></i> History</button></a>
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
                <th>IID</th>
                <th>Title</th>
                <th>Message Type</th>
                <th>Created At</th>
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
                    d.periode = '<?= get_ym() ?>'
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [4],
                    "orderable": false
                  },
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
                <th>IID</th>
                <th>Title</th>
                <th>Message Type</th>
                <th>Created At</th>
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
                    d.periode_lebih_kecil = '<?= get_ym() ?>'
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [4],
                    "orderable": false
                  },
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
    <?php } elseif ($set == "received") { ?>
      <div class="box">
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>IID</th>
                <th>Waktu Pengiriman</th>
                <th>Nama Pengirim</th>
                <th>Message Type</th>
                <th>Subject</th>
                <th>Content</th>
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
                  url: "<?php echo site_url($folder . '/' . $isi . '/fetch_received'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_karyawan_dealer_receiver = '<?= kry_login(user()->id_user)->id_karyawan_dealer ?>'
                    return d;
                  },
                },
                "columnDefs": [
                  // {
                  //   "targets": [5],
                  //   "orderable": false
                  // },
                  // {
                  //   "targets": [5],
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
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      } elseif ($mode == 'edit') {
        $form = 'save_edit';
      } elseif ($mode == 'send') {
        $form = 'save_send';
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
                      <label class="col-sm-2 control-label">To</label>
                      <div class="col-sm-8">
                        <table class='table table-striped table-condensed table-bordered' style='margin-bottom:0px'>
                          <tr>
                            <td>Nama Lengkap</td>
                            <!-- <td>Username DMS</td> -->
                            <!-- <td>Username SC</td> -->
                            <td align='center'>Deleted</td>
                            <td align='center'>Marked</td>
                            <td align='center'>Read</td>
                            <td align='center'>Sent</td>
                            <td v-if="mode=='insert' || mode=='edit'"></td>
                          </tr>
                          <tr v-for="(dt, index) of to">
                            <td>{{dt.nama_lengkap}}</td>
                            <!-- <td>{{dt.username}}</td> -->
                            <!-- <td>{{dt.username_sc}}</td> -->
                            <td align='center'>
                              <span v-if="dt.bisdelete==1"><i class="fa fa-check"></i></span>
                              <span v-else></span>
                            </td>
                            <td align='center'>
                              <span v-if="dt.bismarked==1"><i class="fa fa-check"></i></span>
                              <span v-else></span>
                            </td>
                            <td align='center'>
                              <span v-if="dt.bisread==1"><i class="fa fa-check"></i></span>
                              <span v-else></span>
                            </td>
                            <td align='center'>
                              <span v-if="dt.bissent==1"><i class="fa fa-check"></i></span>
                              <span v-else></span>
                            </td>
                            <td width='5%' v-if="mode=='insert' || mode=='edit'">
                              <button type='button' class='btn btn-danger btn-flat btn-xs' @click.prevent="delTo(index)"><i class='fa fa-trash'></i></button>
                            </td>
                          </tr>
                          <tr v-if="mode=='insert' || mode=='edit'">
                            <td colspan=5></td>
                            <td width='5%'>
                              <button type='button' class='btn btn-primary btn-flat btn-xs' onclick="showModaKaryawanDealer()"><i class='fa fa-search'></i></button>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">From</label>
                      <div class="col-sm-4">
                        <input type="text" name="nama_lengkap" :readonly="mode=='detail'" class="form-control" required v-model="row.nama_lengkap" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Message Type</label>
                      <div class="col-sm-4">
                        <select class='form-control' v-model='row.imsgtype' required name='imsgtype' :disabled="mode=='detail' || mode=='send'">
                          <option value=''>-choose-</option>
                          <?php foreach ($msg_type as $msg) { ?>
                            <option value='<?= $msg->imsgtype ?>'><?= $msg->message_type ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Title</label>
                      <div class="col-sm-8">
                        <input type="text" name="vtitle" :disabled="mode=='detail' || mode=='send'" v-model="row.vtitle" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Content</label>
                      <div class="col-sm-10">
                        <textarea class='form-control' rows=3 name='vcontents' id='vcontents' required :disabled="mode=='detail' || mode=='send'"><?= isset($row) ? $row->vcontents : '' ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center">
                      <button v-if="mode=='insert' || mode=='edit'" type="button" id="submitBtn" @click.prevent="save_data" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button v-if="mode=='send'" type="button" id="submitBtn" @click.prevent="save_data" class="btn btn-primary btn-flat"><i class="fa fa-send"></i> Send</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['karyawan_dealer', 'karyawan_can_login'];
        $this->load->view('dealer/h2_api', $data); ?>
        <script>
          function pilihKaryawanDealer(params) {
            for (dt of form_.to) {
              if (params.id_karyawan_dealer == dt.id_karyawan_dealer) {
                alert('Sudah dipilih !');
                return false;
              }
            }
            let kry = {
              id_karyawan_dealer: params.id_karyawan_dealer,
              nama_lengkap: params.nama_lengkap,
              username: params.username,
              username_sc: params.username_sc,
              xpmmsg_iid: "",
              bisdelete: 0,
              bismarked: 0,
              bisread: 0,
              bissent: 0,
            }
            form_.to.push(kry);
          }
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              to: <?= isset($row) ? json_encode($to) : '[]' ?>,
              row: <?= isset($row) ? json_encode($row) : "{nama_lengkap:'" . $kry->nama_lengkap . "',tahun:'',bulan:'',honda_id:'',target:'',id:'',imsgtype:''}" ?>,
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
                  let values = {
                    to: form_.to,
                    iid: form_.row.iid
                  };
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (values.to.length == 0) {
                    alert('Tujuan pesan belum ditentukan !');
                    return false;
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
              delTo: function(index) {
                this.to.splice(index, 1);
              },
            }
          });
        </script>
      <?php } ?>
  </section>
</div>