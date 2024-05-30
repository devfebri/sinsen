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
                <th>No. Rekap</th>
                <th>Tgl. Rekap</th>
                <th>Jumlah KPB</th>
                <th>Periode Rekap</th>
                <th>Jasa</th>
                <th>Oli (Btl)</th>
                <th>Qty Terpenuhi (Oli)</th>
                <th>Qty Belum Terpenuhi (Oli)</th>
                <th>PO Ke MD</th>
                <th>No. Surat Jalan</th>
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
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [6],
                    "className": 'text-center'
                  },
                  {
                    "targets": [5],
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
                    <label class="col-sm-2 control-label">No. rekap</label>
                    <div class="col-sm-4">
                      <input type="text" readonly v-model="data.id_rekap_kpb" name="no_rekap" id="no_rekap" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Tanggal Entry</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.created_at" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Start Date</label>
                      <div class="col-sm-4">
                        <input type="text" readonly id='start_date' name="start_date" class="form-control datepicker" required value="<?= isset($row) ? $row->start_date : '' ?>">
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">End Date</label>
                      <div class="col-sm-4">
                        <input type="text" readonly id='end_date' name='end_date' class="form-control datepicker" required value="<?= isset($row) ? $row->end_date : '' ?>" :disabled="mode=='detail'">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="generateBtn" @click.prevent="generateData" class="btn btn-success btn-flat">Generate Data</button>
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>No.</th>
                      <th>NJB</th>
                      <th>NSC</th>
                      <th>Tipe Kendaraan</th>
                      <th>No. Mesin</th>
                      <th>No. Rangka</th>
                      <th>Tgl. Beli</th>
                      <th>Tempat Pembelian</th>
                      <th>Tgl. Service</th>
                      <th>KPB</th>
                      <th>Jasa</th>
                      <th>Oli</th>
                      <th>Oli (Btl)</th>
                      <th align='center' v-if="mode=='insert'"><input v-model='check_all' type="checkbox" true-value='1' false-value='0'></th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{index+1}}</td>
                        <td>{{dt.no_njb}}</td>
                        <td>{{dt.no_nsc}}</td>
                        <td>{{dt.id_tipe_kendaraan}}</td>
                        <td>{{dt.no_mesin}}</td>
                        <td>{{dt.no_rangka}}</td>
                        <td>{{dt.tgl_pembelian_indo}}</td>
                        <td>{{dt.dealer_beli}}</td>
                        <td>{{dt.tgl_servis_indo}}</td>
                        <td>{{dt.kpb_ke}}</td>
                        <td align='right'>{{dt.tot_pekerjaan | toCurrency}}</td>
                        <td align='right'>{{dt.jml_oli | toCurrency }}</td>
                        <td align='center'>{{dt.kpb_ke=='1'?dt.tot_qty_oli:0}}</td>
                        <th align='center' v-if="mode=='insert'"><input v-model='dt.checked' type="checkbox" true-value='1' false-value='0'></th>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <!-- <td colspan=8><b>Grand Total</b></td>
                        <td colspan=1 align='right'><b>{{grand_total.tot_pekerjaan | toCurrency}}</b></td>
                        <td colspan=1 align='right'>
                          <b>Qty : {{grand_total.qty_oli}}</b></br>
                          <b>{{grand_total.jml_oli | toCurrency}}</b>
                        </td> -->
                        <!-- <td></td> -->
                        <td colspan=9><b>Grand Total</b></td>
                        <td colspan=2 align='right'><b>{{grand_total.tot_pekerjaan | toCurrency}}</b></td>
                        <td colspan=1 align='right'><b>{{grand_total.jml_oli | toCurrency}}</b></td>
                        <td colspan=1 align='center'><b>{{grand_total.qty_oli}}</b></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="details.length>0 && (mode=='insert' || mode=='edit')">
                      <button type="button" id="submitBtn" @click.prevent="saveRekap" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div>
                  
                <div v-if="details.length>0 && mode=='insert'">
                <table>
                    <tr>
                      <td colspan="2"></td>
                      <td>Total Data</td>
                      <td>Total Data yang Dichecklist</td>
                    </tr>
                    <tr>
                      <td>Total KPB 1</td>
                      <td>:</td>
                      <td>{{kpb1}}</td>
                      <td>{{checkedcount_kpb1}}</td>
                    </tr>
                    <tr>
                      <td>Total KPB 2</td>
                      <td>:</td>
                      <td>{{kpb2}}</td>
                      <td>{{checkedcount_kpb2}}</td>
                    </tr>
                    <tr>
                      <td>Total KPB 3</td>
                      <td>:</td>
                      <td>{{kpb3}}</td>
                      <td>{{checkedcount_kpb3}}</td>
                    </tr>
                    <tr>
                      <td>Total KPB 4</td>
                      <td>:</td>
                      <td>{{kpb4}}</td>
                      <td>{{checkedcount_kpb4}}</td>
                    </tr>
                </table>
                </div>
                <div v-if="details.length>0 && mode=='detail'">
                <table>
                    <tr>
                      <td colspan="2"></td>
                      <td>Total Data</td>
                    </tr>
                    <tr>
                      <td>Total KPB 1</td>
                      <td>:</td>
                      <td>{{kpb1}}</td>
                    </tr>
                    <tr>
                      <td>Total KPB 2</td>
                      <td>:</td>
                      <td>{{kpb2}}</td>
                    </tr>
                    <tr>
                      <td>Total KPB 3</td>
                      <td>:</td>
                      <td>{{kpb3}}</td>
                    </tr>
                    <tr>
                      <td>Total KPB 4</td>
                      <td>:</td>
                      <td>{{kpb4}}</td>
                    </tr>
                </table>
                </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <script>
          var coa_to = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              data: <?= isset($row) ? json_encode($row) : "{}" ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              check_all: '0',
              dtl: {
                kode_coa: '',
                coa: '',
                tipe_transaksi: '',
                id_referensi: '',
                dibayar: 0,
                sisa_hutang: 0,
                keterangan: '',
                from: '',
              },
            },
            methods: {
              saveRekap: function() {
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
                    details: form_.details
                  };
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    if (values.details.length == 0) {
                      alert('Detail penerimaan belum ditentukan !');
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
                        form_.details = response.details;
                      } else {
                        alert(response.pesan);
                        form_.details = [];
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
              grand_total: function() {
                var grand = {
                  tot_pekerjaan: 0,
                  qty_oli: 0,
                  jml_oli: 0,
                };
                for (dtl of this.details) {
                  if (this.mode == 'detail') {
                    grand.tot_pekerjaan += parseInt(dtl.tot_pekerjaan);
                    if (dtl.kpb_ke == '1') {
                      grand.qty_oli += parseInt(dtl.tot_qty_oli);
                    }
                    grand.jml_oli += parseInt(dtl.jml_oli);
                  } else {
                    if (dtl.checked == '1') {
                      grand.tot_pekerjaan += parseInt(dtl.tot_pekerjaan);
                      if (dtl.kpb_ke == '1') {
                        grand.qty_oli += parseInt(dtl.tot_qty_oli);
                      }
                      grand.jml_oli += parseInt(dtl.jml_oli);
                    }
                  }
                }
                return grand;
              },
              kpb1() {
                return this.details.filter(item => item.kpb_ke === '1').length;
              },
              kpb2() {
                return this.details.filter(item => item.kpb_ke === '2').length;
              },
              kpb3() {
                return this.details.filter(item => item.kpb_ke === '3').length;
              },
              kpb4() {
                return this.details.filter(item => item.kpb_ke === '4').length;
              },
              checkedcount_kpb1(){
                return this.details.filter(dt => dt.checked === '1' && dt.kpb_ke === '1').length;
              },
              checkedcount_kpb2(){
                return this.details.filter(dt => dt.checked === '1' && dt.kpb_ke === '2').length;
              },
              checkedcount_kpb3(){
                return this.details.filter(dt => dt.checked === '1' && dt.kpb_ke === '3').length;
              },
              checkedcount_kpb4(){
                return this.details.filter(dt => dt.checked === '1' && dt.kpb_ke === '4').length;
              }
            },
            watch: {
              check_all: {
                deep: false,
                handler: function() {
                  let idx = 0;
                  for (dtl of this.details) {
                    this.details[idx].checked = this.check_all;
                    idx++;
                  }
                }
              }
            }
          });

          function showModalCOADealer(to) {
            coa_to = to
            $('#modalCOADealer').modal('show');
          }

          function pilihCOADealer(coa) {
            if (coa_to == 'detail') {
              form_.dtl.kode_coa = coa.kode_coa;
              form_.dtl.coa = coa.coa;
              form_.dtl.tipe_transaksi = coa.coa;
            } else if (coa_to == 'header') {
              form_.data.kode_coa = coa.kode_coa;
              form_.data.coa = coa.coa;
            }
            coa_to = '';
          }

          function pilihRef(ref) {
            form_.dtl.id_referensi = ref.no_transaksi;
            form_.dtl.sisa_hutang = ref.saldo;
            form_.dtl.from = ref.from;
            console.log(form_.dtl)
          }
        </script>
      <?php } ?>
  </section>
</div>