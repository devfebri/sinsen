<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Finance H23</li>
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
            <a href="dealer/<?= $isi ?>/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
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
                <th>Referensi</th>
                <th>ID Referensi</th>
                <th>No NJB</th>
                <th>No NSC</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>Tipe Motor</th>
                <th>Total Pembayaran</th>
                <th>Sudah Dibayar</th>
                <th>Sisa</th>
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
                    d.sisa_lebih_besar = true;
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
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } elseif ($set == "history_tes") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>" class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <!-- <li class='li_tabs' id='all_tab'><a href="#tab_0" data-toggle="tab" onclick="changeTab('all_tab')">All</a></li> -->
              <!-- <li class='li_tabs' id='wo_tab'><a href="#tab_0" data-toggle="tab" onclick="changeTab('wo_tab')">Work Order</a></li>
              <li class='li_tabs' id='part_tab'><a href="#tab_1" data-toggle="tab" onclick="changeTab('part_tab')">Part Sales</a></li> -->
            </ul>
            <div class="tab-content">
              <div id="tab_0" class="active tab-pane">
                <table id="datatable_server" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Referensi</th>
                      <th>ID Referensi</th>
                      <th>No NJB</th>
                      <th>No NSC</th>
                      <th>No. Polisi</th>
                      <th>Nama Customer</th>
                      <th>Tipe Motor</th>
                      <th>Total Pembayaran</th>
                      <th>Sudah Dibayar</th>
                      <th>Sisa</th>
                      <th>Tgl Kwitansi</th>
                      <th>No. Kwitansi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <script>
            var tabs = 'all_tab';

            function changeTab(set_tabs) {
              tabs = set_tabs;
              $('.li_tabs').removeClass('active');
              $('#' + tabs).addClass('active');
              $('#datatable_server').DataTable().ajax.reload();
            }
            $(document).ready(function() {
              $('#all_tab').addClass('active');
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
                  url: "<?php echo site_url('dealer/' . $isi . '/fetch_history'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    d.tabs = tabs;
                    d.sisa_0 = true;
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [12],
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
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'create_kwitansi') {
        $form = 'save_kwitansi';
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
              <form class="form-horizontal" id="form_">
                <div class="form-group" v-if="mode=='detail_kwitansi'">
                  <label class="col-sm-2 control-label">No. Receipt</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.id_receipt" name="id_receipt" id="id_receipt" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tgl. Receipt</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.tgl_receipt" name="tgl_receipt" id="tgl_receipt" class="form-control">
                  </div>
                </div>
                <div class="form-group" v-if="data.id_work_order!=undefined">
                  <label class="col-sm-2 control-label">No. WO</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.id_work_order" name="id_work_order" id="id_work_order" class="form-control">
                  </div>
                  <span v-if="data!=''">
                    <label class="col-sm-2 control-label">Tanggal WO</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tgl_servis" class="form-control">
                    </div>
                  </span>
                </div>
                <div class="form-group" v-if="data.nomor_so!=undefined">
                  <label class="col-sm-2 control-label">No. Sales Order</label>
                  <div class="col-sm-4">
                    <input type="text" readonly v-model="data.nomor_so" name="nomor_so" id="nomor_so" class="form-control">
                  </div>
                  <span v-if="data!=''">
                    <label class="col-sm-2 control-label">Tanggal SO</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tanggal_so" class="form-control">
                    </div>
                  </span>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" disabled v-model="data.id_customer" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" disabled v-model="data.nama_customer" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">No. Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" disabled v-model="data.no_polisi" class="form-control">
                  </div>
                  <label class="col-sm-2 control-label">Tipe Unit</label>
                  <div class="col-sm-4">
                    <input type="text" disabled v-model="data.tipe_ahm" class="form-control">
                  </div>
                </div>
                <div class="col-sm-12" v-if="ada_uang_muka==1">
                  <div class="alert alert-success alert-dismissable">
                    <strong>Terdapat uang jaminan untuk print receipt ini</strong>
                  </div>
                </div>
                <div class="col-md-12" v-if="kwitansi.length==0">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Pembayaran</button><br><br>
                </div>
                <div class="col-sm-12" v-if="kwitansi.length==0">
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>Metode Pembayaran</th>
                      <th>ID Uang Jaminan</th>
                      <th>Uang Jaminan</th>
                      <th>No. Rekening</th>
                      <th>Nama Bank</th>
                      <th>Tanggal</th>
                      <th>Nominal Pembayaran</th>
                      <th v-if="mode=='create_kwitansi'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(byr, index) of pembayarans">
                        <td>{{byr.metode_bayar=='uang_muka'?'Uang Jaminan':byr.metode_bayar}}</td>
                        <td>{{byr.no_inv_jaminan}}</td>
                        <td>{{byr.uang_muka | toCurrency}}</td>
                        <td>{{byr.no_rekening}}</td>
                        <td>{{byr.bank}}</td>
                        <td>{{byr.tanggal}}</td>
                        <td align="right">{{byr.nominal | toCurrency}}</td>
                        <td align="center" v-if="mode=='create_kwitansi'">
                          <button @click.prevent="delPembayarans(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr v-if="mode=='create_kwitansi'">
                        <td>
                          <select v-model="pembayaran.metode_bayar" class="form-control" id="metode_bayar">
                            <option value="">- Pilih -</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                          </select>
                        </td>
                        <td>
                          <ul v-if="pembayaran.metode_bayar=='uang_muka'">
                            <li v-for="(ujn, index) of pembayaran.no_inv_jaminan">{{ujn}} ({{nominal_inv_jaminan[index] | toCurrency}})</li>
                          </ul>
                        </td>
                        <td>{{pembayaran.uang_muka | toCurrency}}</td>
                        <td>
                          <input v-if="pembayaran.metode_bayar=='Transfer'" class="form-control" v-model="pembayaran.no_rekening" onclick="showModalRekDealer()" readonly placeholder='Klik Untuk Memilih' />
                        </td>
                        <td>
                          <input v-if="pembayaran.metode_bayar=='Transfer'" class="form-control" v-model="pembayaran.bank" onclick="showModalRekDealer()" readonly placeholder='Klik Untuk Memilih' />
                        </td>
                        <td>
                          <date-picker v-if="pembayaran.metode_bayar=='Transfer'" v-model="pembayaran.tanggal" readonly required placeholder='Klik Untuk Memilih'></date-picker>
                        </td>
                        <td>
                          <div v-if="pembayaran.metode_bayar!='uang_muka'">
                            <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control" v-model="pembayaran.nominal" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" />
                          </div>
                          <div v-else>
                            <vue-numeric v-for="(nom, index) of set_inv_jaminan" style="float: left;width: 100%;text-align: right;" class="form-control" v-model="form_.set_inv_jaminan[index]" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" />
                          </div>
                        </td>
                        <td align="center">
                          <button @click.prevent="addPembayarans" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                      <tr align="right">
                        <td colspan="6"><b>Total</b></td>
                        <td align="right"><b>{{totBayar | toCurrency}}</b></td>
                        <td v-if="mode=='create_kwitansi'"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="col-md-12">
                  <div v-if="nominal_lebih>0">
                    <button class="btn btn-info btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled style='font-size:12pt;margin-bottom:20px'>Pencatatan Kelebihan Pembayaran</button>
                    <div class='col-sm-12'>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">COA *</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" name="kode_coa" autocomplete="off" readonly placeholder='Klik untuk memilih' onclick="showModalCOADealer()" id="kode_coa" value="<?= isset($row->kode_coa) ? $row->kode_coa : '' ?>" required :disabled="mode!='create_kwitansi'">
                        </div>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" autocomplete="off" readonly id="coa" value="<?= isset($row->coa) ? $row->coa : '' ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nominal</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control text-rata-kanan" autocomplete="off" :value="nominal_lebih | toCurrency" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="keterangan_lebih" autocomplete="off" value="<?= isset($row->keterangan_lebih) ? $row->keterangan_lebih : '' ?>" :disabled="mode!='create_kwitansi'">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class=" col-md-12">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Transaksi</button><br><br>
                </div>
                <div class="col-sm-12">
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>No. Transaksi</th>
                      <th>Tgl. Transaksi</th>
                      <th>Nilai Transaksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details.details">
                        <td><a @click.prevent="showDetailTransaksi(dtl.id_referensi)" href="#">{{dtl.id_referensi}}</a></td>
                        <td>{{dtl.tgl_transaksi}}</td>
                        <td align="right">{{dtl.nilai | toCurrency}}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr align="right">
                        <td colspan="2"><b>Total</b></td>
                        <td align="right"><b>{{totBiaya | toCurrency}}</b></td>
                      </tr>
                      <tr align="right">
                        <td colspan="2"><b>Sudah Dibayar</b></td>
                        <td align="right"><b>{{sudahBayar | toCurrency}}</b></td>
                      </tr>
                      <tr align="right" v-if="mode!='detail_kwitansi'">
                        <td colspan="2"><b>Sisa</b></td>
                        <td align="right"><b>{{sisa | toCurrency}}</b></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="col-md-12" v-if="kwitansi.length>0">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>Detail Kwitansi</button><br><br>
                </div>
                <div class="col-sm-12" v-if="kwitansi.length>0">
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>ID Receipt</th>
                      <th>Tanggal</th>
                      <th>Total Dibayar</th>
                      <th width="10%">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(kwt, index) of kwitansi">
                        <td>{{kwt.id_receipt}}</td>
                        <td>{{kwt.tgl_receipt}}</td>
                        <td align="right">{{kwt.dibayar | toCurrency}}</td>
                        <td align="center">
                          <a :href="'<?= base_url('dealer/print_receipt_customer/one_kwitansi?id=') ?>'+kwt.id_receipt" class="btn btn-primary btn-xs btn-flat" target="_blank"><i class="fa fa-eye"></i></a>
                          <a :href="'<?= base_url('dealer/print_receipt_customer/cetak_kwitansi?id=') ?>'+kwt.id_receipt" class="btn btn-success btn-xs btn-flat" target="_blank"><i class="fa fa-print"></i></a>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr align="right">
                        <td colspan="2"><b>Total</b></td>
                        <td align="right"><b>{{totKwitansiDibayar | toCurrency}}</b></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="col-sm-12" align="center" v-if="mode=='create_kwitansi'">
                  <button type="button" id="submitBtn" @click.prevent="saveReceipt" class="btn btn-info btn-flat">Save & Print</button>
                </div>
            </div> <!-- END IF -->
            </form>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = ['modalRekDealer'];
      $this->load->view('dealer/h2_api', $data);
      $data['data'] = ['modalCOADealer', 'modalDetailNJB', 'modalDetailNSC'];
      $this->load->view('dealer/h2_api_finance', $data);
      ?>
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>

      <script>
        function showModalCOADealer(to) {
          coa_to = to
          $('#modalCOADealer').modal('show');
        }

        function pilihCOADealer(params) {
          $('#kode_coa').val(params.kode_coa)
          $('#coa').val(params.coa)
        }
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
        var form_ = new Vue({
          el: '#form_',
          data: {
            ada_uang_muka: 0,
            uang_muka: 0,
            no_inv_jaminan: '',
            mode: '<?= $mode ?>',
            data: <?= json_encode($row) ?>,
            details: <?= json_encode($detail_transaksi) ?>,
            pembayaran: {
              metode_bayar: '',
              no_rekening: '',
              atas_nama: '',
              bank: '',
              id_bank: '',
              uang_muka: 0,
              nominal: 0,
              no_inv_jaminan: ''
            },
            riwayat_bayar: <?= isset($riwayat_bayar) ? json_encode($riwayat_bayar) : 0 ?>,
            pembayarans: <?= isset($pembayarans) ? json_encode($pembayarans) : "[]" ?>,
            kwitansi: <?= isset($kwitansi) ? json_encode($kwitansi) : "[]" ?>,
            set_inv_jaminan:[],
          },
          methods: {
            saveReceipt: function() {
              if (this.pembayarans.length == 0) {
                alert('Detail pembayaran belum ditentukan !');
                return false
              }
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
                  pembayarans: form_.pembayarans,
                  details: form_.details,
                  nominal_lebih: form_.nominal_lebih
                };
                var form = $('#form_').serializeArray();
                for (field of form) {
                  values[field.name] = field.value;
                }
                if (confirm("Apakah anda yakin ?") == true) {
                  if (parseInt(form_.sisa) < 0) {
                    alert('Total Pembayaran tidak boleh melebihi sisa !');
                    return false;
                  }
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                      $('#submitBtn').attr('disabled', true);
                    },
                    url: '<?= base_url('dealer/print_receipt_customer/' . $form) ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#submitBtn').html('Save & Print');
                      if (response.status == 'sukses') {
                        window.location = response.link;
                      } else {
                        alert(response.pesan);
                        $('#submitBtn').attr('disabled', false);
                      }
                    },
                    error: function() {
                      alert("Something Went Wrong !");
                      $('#submitBtn').html('Save & Print');
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
              tipe = dtl.substr(0, 3);
              if (tipe == 'NJB') {
                modalDetailNJB(dtl)
              } else {
                modalDetailNSC(dtl)
              }
            },
            clearPembayaran: function() {
              this.pembayaran = {
                metode_bayar: '',
                no_rekening: '',
                atas_nama: '',
                bank: '',
                id_bank: '',
              }
            },
            addPembayarans: function() {
              if (this.pembayaran.metode_bayar === '') {
                alert('Pilih metode pembayaran !');
                return false;
              }
              if (this.pembayaran.metode_bayar == 'Cash') {
                this.pembayaran.no_rekening = '';
                this.pembayaran.atas_nama = '';
                this.pembayaran.bank = '';
                this.pembayaran.id_bank = '';
                this.pembayaran.tanggal = '<?= tanggal() ?>';
              } else if (this.pembayaran.metode_bayar == 'uang_muka') {
                if (parseInt(this.pembayaran.uang_muka) < parseInt(this.pembayaran.nominal)) {
                  alert('Nominal pembayaran melebihi Uang Jaminan !')
                  return false;
                }
                cek = 0
                for(pby of this.pembayaran.no_inv_jaminan){
                  byrs = {
                    no_inv_jaminan : pby,
                    uang_muka : form_.nominal_inv_jaminan[cek],
                    nominal : form_.set_inv_jaminan[cek],
                    no_rekening:'',
                    atas_nama:'',
                    bank:'',
                    id_bank:'',
                    metode_bayar:this.pembayaran.metode_bayar,
                    tanggal:'<?= tanggal() ?>',
                  }
                  console.log(form_.set_inv_jaminan[cek]);
                  this.pembayarans.push(byrs);
                  cek++;
                }
              } else {
                if (this.pembayaran.no_rekening === '') {
                  alert('Rekening belum ditentukan !')
                  return false;
                }
                if (this.pembayaran.tanggal === '') {
                  alert('Tanggal transfer belum ditentukan !')
                  return false;
                }
              }
             if (this.pembayaran.metode_bayar!='uang_muka') {
               console.log(this.pembayaran)
              if (this.pembayaran.nominal === undefined) {
                alert('Nominal pembayaran belum ditentukan !')
                return false;
              }
             }
              if (this.pembayaran.metode_bayar!='uang_muka') {
                let total = parseInt(this.totBayar) + parseInt(this.pembayaran.nominal);
                if (total > this.totBiaya) {
                  if (confirm('Terdapat kelebihan pembayaran, apakah ingin melanjutkan ?') === true) {
                    this.pushPembayaran();
                  } else {
                    return false;
                  }
                } else {
                  this.pushPembayaran()
                }
              }else{
                this.clearPembayaran();
              }
            },
            pushPembayaran: function() {
              this.pembayarans.push(this.pembayaran);
              this.clearPembayaran();
            },
            delPembayarans: function(index) {
              this.pembayarans.splice(index, 1);
            },
          },
          computed: {
            totBiaya: function() {
              let tot = 0;
              let diskon = 0;
              console.log(this.details);
              if (this.details.details != null) {
                for (dtl of this.details.details) {
                  tot += parseInt(dtl.nilai);
                }
              }
              return tot;
            },
            totBayar: function() {
              let tot = 0;
              let diskon = 0;
              for (dtl of this.pembayarans) {
                tot += parseInt(dtl.nominal);
              }
              return tot;
            },
            sudahBayar: function() {
              return this.riwayat_bayar;
            },
            sisa: function() {
              let sisa = this.totBiaya - this.sudahBayar - this.totBayar;
              console.log('this.totBiaya : ' + this.totBiaya + ' this.sudahBayar : ' + this.sudahBayar + ' this.totBayar : ' + this.totBayar)
              sisa = sisa > 0 ? sisa : 0;
              return sisa;
            },
            nominal_lebih: function() {
              lebih = (this.totBayar + this.sudahBayar) - this.totBiaya;
              return lebih;
            },
            totKwitansiDibayar: function() {
              let tot = 0;
              for (dtl of this.kwitansi) {
                tot += parseInt(dtl.dibayar);
              }
              return tot;
            },
          },
          watch: {
            pembayaran: {
              deep: true,
              handler: function() {
                if (this.pembayaran.metode_bayar == 'uang_muka') {
                  this.pembayaran.uang_muka = this.nominal_uang_muka;
                  this.pembayaran.no_inv_jaminan = this.no_inv_jaminan;
                }
              }
            }
          }
        });

        function pilihRek(rk) {
          form_.pembayaran.no_rekening = rk.no_rek
          form_.pembayaran.atas_nama = rk.nama_rek
          form_.pembayaran.bank = rk.bank
          form_.pembayaran.id_bank = rk.id_bank
        }
        $(document).ready(function() {
          cekUangMuka();
        })

        function cekUangMuka() {
          for (let index = 0; index < form_.details.details.length; index++) {
            x = form_.details.details[index];
            if (x.uang_muka != null) {
              if (x.uang_muka.length > 0) {
                form_.ada_uang_muka = 1;
                nominal_um = 0;
                no_inv_jaminan = [];
                nominal_inv_jaminan =[];
                set_inv_jaminan =[];
                for(um of x.uang_muka){
                  nominal_um+=parseInt(um.uang_muka);
                  no_inv_jaminan.push(um.no_inv_jaminan);
                  nominal_inv_jaminan.push(parseInt(um.uang_muka));
                  set_inv_jaminan.push(parseInt(um.uang_muka));
                }
                form_.nominal_uang_muka         = nominal_um;
                form_.nominal_inv_jaminan       = nominal_inv_jaminan;
                form_.set_inv_jaminan           = set_inv_jaminan;
                form_.no_inv_jaminan            = no_inv_jaminan;
                $('#metode_bayar').append('<option value="uang_muka">Uang Jaminan</option>');
              }
            }
          }
        }
      </script>
    <?php } ?>
  </section>
</div>