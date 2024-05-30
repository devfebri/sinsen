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
                <th>No. Voucher</th>
                <th>Tgl. Entry</th>
                <th>Akun Bank</th>
                <th>Account Name</th>
                <th>Tot. Jml. Dibayar</th>
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
                    d.jenis_pengeluaran = 'bank'
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [6],
                    "orderable": false
                  },
                  {
                    "targets": [5, 6],
                    "className": 'text-center'
                  },
                  {
                    "targets": [4],
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
                    <label class="col-sm-2 control-label">No. Voucher</label>
                    <div class="col-sm-4">
                      <input type="text" readonly v-model="data.no_voucher" name="no_voucher" id="no_voucher" class="form-control">
                    </div>
                    <label class="col-sm-2 control-label">Tanggal Entry</label>
                    <div class="col-sm-4">
                      <input type="text" disabled v-model="data.tgl_entry" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Account Bank</label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <input type="text" readonly v-model="data.kode_coa" name="kode_coa" id="kode_coa" class="form-control" required>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' :disabled="mode=='detail' || mode=='ubah_status'" onclick="showModalCOADealer('header')" id="btnSearchCOA"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Account Name</label>
                      <div class="col-sm-4">
                        <input type="text" readonly v-model="data.coa" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tipe Customer</label>
                      <div class="col-sm-4">
                        <select class='form-control' name='tipe_customer' v-model="data.tipe_customer" :disabled="mode=='detail' || mode=='ubah_status'">
                          <option value=''>-choose-</option>
                          <option value='dealer'>Dealer</option>
                          <option value='vendor'>Vendor</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-input" v-if="data.tipe_customer=='vendor'">
                      <label class="col-sm-2 control-label">Dibayar Kepada</label>
                      <div class="col-sm-4">
                        <!-- <input type="text" v-model="data.dibayar_kepada" name='dibayar_kepada' class="form-control"> -->
                        <div class="input-group">
                          <input v-model="data.dibayar_kepada" type="text" class="form-control" readonly :disabled="mode=='detail' || mode=='ubah_status'">
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' :disabled="mode=='detail' || mode=='ubah_status'" onclick="showModalVendorPO()"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Deskripsi</label>
                      <div class="col-sm-10">
                        <input type="text" :disabled="mode=='detail' || mode=='ubah_status'" v-model="data.deskripsi" name='deskripsi' class="form-control">
                      </div>
                    </div>
                  </div>
                  <button style="font-size: 12pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail Pengeluaran</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th>Account</th>
                      <th>Jenis Transaksi</th>
                      <th>Referensi</th>
                      <th>Sisa Hutang</th>
                      <th>Jumlah Dibayar</th>
                      <th>Keterangan</th>
                      <th v-if="mode=='insert' || mode=='edit'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{dt.kode_coa}}</td>
                        <td>{{dt.coa}}</td>
                        <td>{{dt.id_referensi}}</td>
                        <td align="right">{{dt.sisa_hutang | toCurrency}}</td>
                        <td align="right">{{dt.dibayar | toCurrency}}</td>
                        <td>{{dt.keterangan}}</td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'">
                          <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr style="font-size:12pt;text-align:right">
                        <td colspan="3"><b>Total</b></td>
                        <td align="right"><b>{{total.tot_sisa | toCurrency}}</b></td>
                        <td align="right"><b>{{total.dibayar | toCurrency}}</b></td>
                        <td></td>
                        <td v-if="mode=='insert' || mode=='edit'"></td>
                      </tr>
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td>
                          <input class="form-control input-inline" v-model="dtl.kode_coa" onclick="showModalCOADealer('detail')" readonly />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.coa" onclick="showModalCOADealer('detail')" readonly />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.id_referensi" style="width:75%;display:inline" readonly />
                          <button style="text-align:right;width:18%" type="button" class="btn btn-primary btn-sm btn-flat" onclick="showModalRefPengeluaran('Transfer',form_.data.tipe_customer,form_.data.id_vendor)"><i class="fa fa-search"></i></button>
                        </td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control isi2 input-inline" v-model="dtl.sisa_hutang" readonly v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" />
                        </td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control isi2 input-inline" v-model="dtl.dibayar" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" />
                        </td>
                        <td>
                          <input class="form-control input-inline" v-model="dtl.keterangan" />
                        </td>
                        <td align="center">
                          <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                  <br>
                  <br>
                  <button style="font-size: 12pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Pembayaran</button><br><br>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Rekening Tujuan</label>
                      <div class="col-sm-4">
                        <input type="text" :disabled="mode=='detail' || mode=='ubah_status'" v-model="data.rekening_tujuan" name='rekening_tujuan' class="form-control" required>
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Via Bayar</label>
                      <div class="col-sm-4">
                        <select class='form-control' name='via_bayar' v-model="data.via_bayar" :disabled="mode=='detail' || mode=='ubah_status'">
                          <option value=''>-choose-</option>
                          <option value='bg'>BG/Cek</option>
                          <option value='transfer'>Transfer</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <table class="table table-bordered table-hover table-condensed table-stripped" v-if="data.via_bayar!=''">
                    <thead>
                      <th v-if="data.via_bayar=='bg'">No. BG/Cek</th>
                      <th v-if="data.via_bayar=='bg'">Tgl. Jatuh Tempo BG/Cek</th>
                      <th v-if="data.via_bayar=='transfer'">Tgl. Transfer</th>
                      <th>Nominal</th>
                      <th v-if="mode=='insert' || mode=='edit'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of pembayarans">
                        <th v-if="data.via_bayar=='bg'">{{dt.no_bg_cek}}</td>
                        <th v-if="data.via_bayar=='bg'">{{dt.tgl_jatuh_tempo_bg_cek}}</td>
                        <th v-if="data.via_bayar=='transfer'">{{dt.tgl_transfer}}</td>
                        <td align="right">{{dt.nominal | toCurrency}}</td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'">
                          <button @click.prevent="delPembayarans(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr style="font-size:12pt;text-align:right">
                        <td colspan="2" v-if="data.via_bayar=='bg'"><b>Total</b></td>
                        <td v-if="data.via_bayar=='transfer'"><b>Total</b></td>
                        <td align="right"><b>{{tot_bayar | toCurrency}}</b></td>
                        <td v-if="mode=='insert'||mode=='edit'"></td>
                      </tr>
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td v-if="data.via_bayar=='bg'">
                          <input class="form-control input-inline" v-model="byr.no_bg_cek" />
                        </td>
                        <td v-if="data.via_bayar=='bg'">
                          <date-picker class='form-control input-inline' v-model="byr.tgl_jatuh_tempo_bg_cek"></date-picker>
                        </td>
                        <td v-if="data.via_bayar=='transfer'">
                          <date-picker class='form-control input-inline' v-model="byr.tgl_transfer"></date-picker>
                        </td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control isi2" v-model="byr.nominal" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" />
                        </td>
                        <td align="center">
                          <button @click.prevent="addPembayarans" type="button" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit' || mode=='ubah_status'">
                      <button type="button" id="submitBtn" @click.prevent="savePenerimaan('')" class="btn btn-info btn-flat" v-if="mode!='ubah_status'"><i class="fa fa-save"></i> Save All</button>
                      <button type="button" id="submitBtn" v-if="mode=='ubah_status'" @click.prevent="savePenerimaan('approved')" class="btn btn-success btn-flat">Approve</button>
                      <button type="button" id="submitBtn" v-if="mode=='ubah_status'" @click.prevent="savePenerimaan('batal')" class="btn btn-danger btn-flat">Batal</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['modalCOADealer', 'modalRefPengeluaran', 'modalVendorPO'];
        $this->load->view('dealer/h2_api_finance', $data); ?>
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

          function pilihVendor(vendor) {
            form_.data.id_vendor = vendor.id_vendor;
            form_.data.dibayar_kepada = vendor.nama_vendor;
            console.log(form_.data)
          }
          var coa_to = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              data: <?= isset($row) ? json_encode($row) : "{kode_coa:'',coa:'',tipe_customer: '',dibayar_kepada:'',id_vendor:'',via_bayar:''}" ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              pembayarans: <?= isset($row) ? json_encode($pembayarans) : '[]' ?>,
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
              byr: {
                no_bg_cek: '',
                tgl_jatuh_tempo_bg_cek: '',
                tgl_transfer: '',
                nominal: 0
              },
            },
            methods: {
              savePenerimaan: function(status) {
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
                  if (form_.total.dibayar > form_.tot_bayar) {
                    alert('Pembayaran masih kurang dari detail pengeluaran !');
                    return false
                  }
                  if (form_.total.dibayar < form_.tot_bayar) {
                    alert('Pembayaran melebihi detail pengeluaran !');
                    return false
                  }
                  let values = {
                    details: form_.details,
                    pembayarans: form_.pembayarans,
                    id_vendor: form_.data.id_vendor,
                    dibayar_kepada: form_.data.dibayar_kepada,
                    rekening_tujuan: form_.data.rekening_tujuan,
                    via_bayar: form_.data.via_bayar,
                    status: status
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
              showDetailTransaksi: function(dtl) {
                console.log(dtl)
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
                if (this.dtl.kode_coa === '') {
                  alert('COA belum dipilih !');
                  return false
                }
                if (this.dtl.id_referensi === '') {
                  alert('Tentukan referensi !');
                  return false
                }
                if (this.dtl.dibayar == 0) {
                  alert('Tentukan jumlah dibayar !');
                  return false
                }
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },

              addPembayarans: function() {
                if (this.byr.nominal === 0) {
                  alert('Tentukan nominal terlebih dahulu !');
                  return false
                }
                if (this.data.via_bayar == 'bg') {
                  if (this.byr.tgl_jatuh_tempo_bg_cek === '') {
                    alert('Tentukan Tgl. Jatuh Tempo BG/Cek !');
                    return false
                  }
                  if (this.byr.no_bg_cek === '') {
                    alert('Tentukan No. BG/Cek !');
                    return false
                  }
                } else {
                  if (this.byr.tgl_transfer === '') {
                    alert('Tentukan Tgl. Transaksi !');
                    return false
                  }
                }
                this.pembayarans.push(this.byr);
                this.clearPembayaran();
              },
              clearPembayaran: function() {
                this.byr = {
                  no_bg_cek: '',
                  tgl_jatuh_tempo_bg_cek: '',
                  tgl_transfer: '',
                  nominal: 0
                }
              },
              delPembayarans: function(index) {
                this.pembayarans.splice(index, 1);
              },
            },
            computed: {
              total: function() {
                let tot_sisa = 0;
                let dibayar = 0;
                for (dtl of this.details) {
                  tot_sisa += parseInt(dtl.sisa_hutang);
                  dibayar += parseInt(dtl.dibayar);
                }
                let tot = {
                  tot_sisa: tot_sisa,
                  dibayar: dibayar
                }
                return tot;
              },
              tot_bayar: function() {
                let tot = 0;
                for (dtl of this.pembayarans) {
                  tot += parseInt(dtl.nominal);
                }
                return tot;
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