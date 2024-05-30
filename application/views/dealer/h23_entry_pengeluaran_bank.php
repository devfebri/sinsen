<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H23</li>
      <li class="">Finance</li>
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
              <a href="dealer/<?= $isi ?>/add">
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
              <tr>
                <th>No. Bukti</th>
                <th>Tgl. Entry</th>
                <th>Amount</th>
                <th>Customer</th>
                <th>Status</th>
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
                  url: "<?php echo site_url('dealer/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [5],
                    "orderable": false
                  },
                  {
                    "targets": [4, 5],
                    "className": 'text-center'
                  },
                  {
                    "targets": [2],
                    "className": 'text-right'
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
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      } elseif ($mode == 'edit') {
        $form = 'save_edit';
      } elseif ($mode == 'ubah_status') {
        $form = 'save_status';
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
            <a href="dealer/<?= $this->uri->segment(2); ?>">
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
            <div class="col-sm-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group" v-if="mode!='insert'">
                    <label class="col-sm-2 control-label">No. Bukti</label>
                    <div class="col-sm-4">
                      <input type="text" readonly v-model="row.no_bukti" name="no_bukti" id="no_bukti" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Tanggal Entry</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="row.tgl_entry" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">No. Voucher</label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <input type="text" readonly v-model="row.no_voucher" name="no_voucher" id="no_voucher" class="form-control" required>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' :disabled="mode=='edit' || mode=='detail' || mode=='ubah_status'" onclick="showModalPengeluaranFinance()" id="btnSearchPengeluaran"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tipe Customer</label>
                      <div class="col-sm-4">
                        <input type="text" readonly v-model="row.tipe_customer" name="tipe_customer" id="tipe_customer" class="form-control" required>
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Dibayar Kepada</label>
                      <div class="col-sm-4">
                        <input type="text" readonly v-model="row.dibayar_kepada" name="dibayar_kepada" id="dibayar_kepada" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert'">
                      <button type="button" id="generateBtn" @click.prevent="generateData" class="btn btn-success btn-flat">Generate Data</button>
                    </div>
                  </div>
                  <button style="font-size: 12pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Pembayaran</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped" v-if="generated==1">
                    <thead>
                      <th v-if="row.via_bayar=='bg'">No. BG/Cek</th>
                      <th v-if="row.via_bayar=='bg'">Tgl. Jatuh Tempo BG/Cek</th>
                      <th v-if="row.via_bayar=='transfer'">Tgl. Transfer</th>
                      <th>Nominal</th>
                      <th width='20%'>Tgl. Cair</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of pembayarans">
                        <th v-if="row.via_bayar=='bg'">{{dt.no_bg_cek}}</td>
                        <th v-if="row.via_bayar=='bg'">{{dt.tgl_jatuh_tempo_bg_cek}}</td>
                        <th v-if="row.via_bayar=='transfer'">{{dt.tgl_transfer}}</td>
                        <td align="right">{{dt.nominal | toCurrency}}</td>
                        <td align="center">
                          <date-picker class='form-control input-inline' v-model="dt.tgl_cair" :disabled="mode=='detail' || mode=='ubah_status'"></date-picker>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr style="font-size:12pt;text-align:right">
                        <td colspan="2" v-if="row.via_bayar=='bg'"><b>Total</b></td>
                        <td v-if="row.via_bayar=='transfer'"><b>Total</b></td>
                        <td align="right"><b>{{tot_bayar | toCurrency}}</b></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="pembayarans.length>0">
                      <button type="button" v-if="mode=='insert' || mode=='edit'" id="submitBtn" @click.prevent="saveEntryPengeluaranBank('')" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="button" id="submitBtn" v-if="mode=='ubah_status'" @click.prevent="saveEntryPengeluaranBank('approved')" class="btn btn-success btn-flat">Approve</button>
                      <button type="button" id="submitBtn" v-if="mode=='ubah_status'" @click.prevent="saveEntryPengeluaranBank('batal')" class="btn btn-danger btn-flat">Batal</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <input id='jenis_pengeluaran' value='bank' type='hidden'>
        <?php
        $data['data'] = ['modalPengeluaranFinance', 'jenis_pengeluaran', 'no_bukti_null'];
        $this->load->view('dealer/h2_api_finance', $data); ?>
        <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>

        <script>
          Vue.component('date-picker', {
            template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
            directives: {
              datepicker: {
                inserted(el, binding, vNode) {
                  $(el).datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight: false,
                  }).on('changeDate', function(e) {
                    vNode.context.$emit('input', e.format(0))
                  })
                }
              }
            },
            props: ['value'],
            methods: {
              update(v) {
                this.$emit('input', v)
              }
            }
          })
          var coa_to = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              row: <?= isset($row) ? json_encode($row) : "{tipe_customer:'',dibayar_kepada:'',no_voucher:'',via_bayar:''}" ?>,
              pembayarans: <?= isset($row) ? json_encode($pembayarans) : '[]' ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              generated: <?= isset($generated) ? $generated : 0 ?>,
            },
            methods: {
              saveEntryPengeluaranBank: function(status) {
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
                    pembayarans: form_.pembayarans,
                    status: status
                  };
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    if (values.pembayarans.length == 0) {
                      alert('Detail pembayaran kosong !');
                      return false;
                    }
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      url: '<?= base_url('dealer/' . $isi . '/' . $form) ?>',
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
              generateData: function() {
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
                  $.ajax({
                    beforeSend: function() {
                      $('#generateBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#generateBtn').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/' . $isi . '/generateData') ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#generateBtn').html('Generate Data');
                      $('#generateBtn').attr('disabled', false);
                      if (response.status == 'sukses') {
                        form_.generated = 1;
                        form_.pembayarans = response.pembayarans;
                        form_.details = response.details;
                        console.log(form_.pembayarans)
                      } else {
                        alert(response.pesan);
                        form_.generated = 0;
                        form_.details = [];
                        form_.pembayarans = [];
                      }
                    },
                    error: function() {
                      alert("Something Went Wrong !");
                      $('#generateBtn').html('Generate Data');
                      $('#generateBtn').attr('disabled', false);
                    },
                  });
                } else {
                  alert('Silahkan isi field required !')
                }
              },
              clearDetail: function() {
                this.dtl = {
                  kode_coa: '',
                  coa: '',
                  tipe_transaksi: '',
                  id_referensi: '',
                  dibayar: 0,
                  sisa_hutang: 0,
                  keterangan: '',
                  from: '',
                }
              },
              addDetails: function() {
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
            },
            computed: {
              tot_bayar: function() {
                let tot = 0;
                for (dtl of this.pembayarans) {
                  tot += parseInt(dtl.nominal);
                }
                return tot;
              }
            }
          });

          function pilihVoucher(vcr) {
            console.log(vcr)
            form_.row.tipe_customer = vcr.tipe_customer;
            form_.row.dibayar_kepada = vcr.dibayar_kepada;
            form_.row.no_voucher = vcr.no_voucher;
            form_.row.via_bayar = vcr.via_bayar;
          }
        </script>
      <?php } ?>
  </section>
</div>