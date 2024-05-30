<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><?= $title; ?></h1>
      <?= $breadcrumb ?>
    </section>
    <section class="content">
      <?php if ($set == 'form') : ?>
        <?php
        $form     = '';
        $disabled = '';
        $readonly = '';
        if ($mode == 'insert') {
          $form = 'save';
        } elseif ($mode == 'detail') {
          $disabled = 'disabled';
          $form = 'detail';
        } elseif ($mode == 'edit') {
          $form = 'update';
        }
        ?>
        <div id="app" class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
          </div><!-- /.box-header -->
          <div v-if="loading" class="overlay">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="box-body">
            <?php $this->load->view('template/normal_session_message.php'); ?>
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal">
                  <div v-if='dealer_tidak_tersedia' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> Customer tidak tersedia.
                  </div>
                  <div v-if='qty_avs_tidak_memenuhi.length > 0' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> Terdapat kuantitas order yang melebihi kuantitas AVS.
                  </div>
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">No. PO Bundling</label>
                      <div v-bind:class="{ 'has-error': error_exist('no_po_aksesoris') }" class="col-sm-4">
                        <input v-model="po_bundling.no_po_aksesoris" type="text" class="form-control" readonly>
                        <small v-if="error_exist('no_po_aksesoris')" class="form-text text-danger">{{ get_error('no_po_aksesoris') }}</small>
                      </div>
                      <label class="col-sm-2 control-label">Kode Customer</label>
                      <div v-bind:class="{ 'has-error': error_exist('kode_dealer_md') }" class="col-sm-4">
                        <input v-if='po_bundling.kode_dealer_md != null' v-model="po_bundling.kode_dealer_md" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.kode_dealer_md == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('kode_dealer_md')" class="form-text text-danger">{{ get_error('kode_dealer_md') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tgl. PO</label>
                      <div v-bind:class="{ 'has-error': error_exist('tgl_po') }" class="col-sm-4">
                        <input :value="moment(po_bundling.tgl_po).format('DD/MM/YYYY')" type="text" class="form-control" readonly>
                        <small v-if="error_exist('tgl_po')" class="form-text text-danger">{{ get_error('tgl_po') }}</small>
                      </div>
                      <label class="col-sm-2 control-label">Nama Customer</label>
                      <div v-bind:class="{ 'has-error': error_exist('nama_dealer') }" class="col-sm-4">
                        <input v-if='po_bundling.nama_dealer != null' v-model="po_bundling.nama_dealer" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.nama_dealer == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('nama_dealer')" class="form-text text-danger">{{ get_error('nama_dealer') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tipe Penjualan</label>
                      <div v-bind:class="{ 'has-error': error_exist('po_type') }" class="col-sm-4">
                        <input v-model="po_bundling.po_type" type="text" class="form-control" readonly>
                        <small v-if="error_exist('po_type')" class="form-text text-danger">{{ get_error('po_type') }}</small>
                      </div>
                      <label class="col-sm-2 control-label">Alamat Customer</label>
                      <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                        <input v-if='po_bundling.alamat != null' v-model="po_bundling.alamat" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.alamat == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Jenis PO</label>
                      <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">
                        <input v-model="po_bundling.produk" type="text" class="form-control" readonly>
                        <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                      </div>
                      <label class="col-sm-2 control-label">Nama Salesman</label>
                      <div v-bind:class="{ 'has-error': error_exist('nama_salesman') }" class="col-sm-4">
                        <input v-if='po_bundling.nama_salesman != null' v-model="po_bundling.nama_salesman" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.nama_salesman == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('nama_salesman')" class="form-text text-danger">{{ get_error('nama_salesman') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tanggal Terima MD</label>
                      <div v-bind:class="{ 'has-error': error_exist('tgl_terima') }" class="col-sm-4">
                        <input :value="moment(po_bundling.tgl_terima).format('DD/MM/YYYY')" type="text" class="form-control" readonly>
                        <small v-if="error_exist('tgl_terima')" class="form-text text-danger">{{ get_error('tgl_terima') }}</small>
                      </div>
                      <label class="col-sm-2 control-label">Nama Paket Bundling</label>
                      <div v-bind:class="{ 'has-error': error_exist('nama_paket_bundling') }" class="col-sm-4">
                        <input v-if='po_bundling.nama_paket_bundling != null' v-model="po_bundling.nama_paket_bundling" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.nama_paket_bundling == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('nama_paket_bundling')" class="form-text text-danger">{{ get_error('nama_paket_bundling') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">No. Sales Order</label>
                      <div v-bind:class="{ 'has-error': error_exist('id_sales_order') }" class="col-sm-4">
                        <input v-if='po_bundling.id_sales_order != null' v-model="po_bundling.id_sales_order" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.id_sales_order == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('id_sales_order')" class="form-text text-danger">{{ get_error('id_sales_order') }}</small>
                      </div>
                      <label class="col-sm-2 control-label">Qty Paket Bundling</label>
                      <div v-bind:class="{ 'has-error': error_exist('qty_paket') }" class="col-sm-4">
                        <input v-if='po_bundling.qty_paket != null' v-model="po_bundling.qty_paket" type="text" class="form-control" readonly>
                        <input v-if='po_bundling.qty_paket == null' value='-' type="text" class="form-control" readonly>
                        <small v-if="error_exist('qty_paket')" class="form-text text-danger">{{ get_error('qty_paket') }}</small>
                      </div>
                    </div>
                    <div class="container-fluid">
                      <table class="table table-condensed">
                        <tr>
                          <td width='3%'>No.</td>
                          <td>Kode Part</td>
                          <td>Deskripsi Part </td>
                          <td class='text-center'>HET</td>
                          <td class='text-center'>Diskon</td>
                          <td width='8%' class='text-center'>Qty AVS</td>
                          <td width='8%' class='text-center'>Qty Order</td>
                          <td width='8%' class='text-center'>Qty Supply</td>
                          <td class='text-center'>Harga Setelah Diskon</td>
                          <td class='text-center'>Harga H1</td>
                          <td class='text-center'>Sub Total</td>
                        </tr>
                        <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                          <td class='align-middle'>{{ index + 1 }}.</td>
                          <td class='align-middle'>{{ part.id_part }}</td>
                          <td class='align-middle'>{{ part.nama_part }}</td>
                          <td class='align-middle text-right'>
                            <vue-numeric read-only v-model='part.harga' separator='.' currency='Rp'></vue-numeric>
                          </td>
                          <td class='align-middle text-right'>
                            <vue-numeric read-only v-model='part.diskon_value' separator='.' :currency='get_currency_symbol(part.tipe_diskon)' :currency-symbol-position='get_currency_position(part.tipe_diskon)'></vue-numeric>
                          </td>
                          <td class='align-middle text-center'>
                            <vue-numeric read-only v-model='part.qty_avs' separator='.'></vue-numeric>
                          </td>
                          <td class='align-middle text-center'>
                            <vue-numeric read-only v-model='part.qty_order' separator='.'></vue-numeric>
                          </td>
                          <td class='align-middle text-center'>
                            <vue-numeric read-only v-model='part.qty_supply' separator='.'></vue-numeric>
                          </td>
                          <td class='align-middle text-right'>
                            <vue-numeric read-only :value='harga_setelah_diskon(part, part.harga)' separator='.' currency='Rp'></vue-numeric>
                          </td>
                          <td class='align-middle text-right'>
                            <vue-numeric read-only :value='harga_setelah_diskon(part, part.harga_h1)' separator='.' currency='Rp'></vue-numeric>
                          </td>
                          <td class='align-middle text-right'>
                            <vue-numeric read-only :value='sub_total(part, part.harga)' separator='.' currency='Rp'></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='parts.length > 0'>
                          <td class='text-right' colspan='9'>Total</td>
                          <td class="text-right">
                            <vue-numeric read-only v-model='this.total_amount' separator='.' currency='Rp'></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if='parts.length < 1'>
                          <td colspan='10'>Tidak ada data.</td>
                        </tr>
                      </table>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-4 no-padding">
                      <button v-if='sales_order_belum_dibuat && po_bundling.status_po != "reject H3"' :disabled='dealer_tidak_tersedia' @click.prevent='proses' class="btn btn-flat btn-sm btn-success">Proses</button>
                    </div>
                    <div class="col-sm-8 no-padding text-right">
                      <button v-if='po_bundling.id_sales_order == null && po_bundling.status_po != "reject H3"' data-toggle='modal' data-target='#reject_modal' type="button" class="btn btn-danger btn-sm btn-flat">Reject</button>
                      <div id="reject_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <div class="col-sm-12">
                                  <textarea class="form-control" id="alasan_reject"></textarea>
                                  <small v-if="error_exist('alasan_reject')" class="form-text text-danger">{{ get_error('alasan_reject') }}</small>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-sm-12">
                                  <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          var app = new Vue({
            el: '#app',
            data: {
              errors: {},
              loading: false,
              index_part: 0,
              mode: '<?= $mode ?>',
              po_bundling: <?= json_encode($po_bundling) ?>,
              parts: <?= json_encode($parts) ?>,
            },
            methods: {
              proses: function() {
                post = _.pick(this.po_bundling, [
                  'referensi_po_bundling', 'kategori_po', 'po_type', 'produk', 'tipe_source',
                  'id_dealer', 'id_salesman', 'created_by_md', 'jenis_pembayaran'
                ]);
                post.total_amount = this.total_amount;

                post.parts = _.chain(this.parts)
                  .map(function(part) {
                    return _.pick(part, [
                      'id_part', 'harga', 'qty_order', 'qty_order', 'qty_pemenuhan', 'tipe_diskon', 'diskon_value'
                    ])
                  })
                  .value();

                this.loading = true;
                this.errors = {};
                axios.post('h3/h3_md_sales_order/save', Qs.stringify(post))
                  .then(function(res) {
                    window.location = 'h3/h3_md_po_bundling/detail?no_po_aksesoris=' + res.data.payload.referensi_po_bundling;
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.error_type == 'validation_error') {
                      app.errors = data.errors;
                      toastr.error(data.message);
                    } else {
                      toastr.error(data.message);
                    }
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              reject: function(status) {
                // this.loading = true;
                post = {};
                post.no_po_aksesoris = this.po_bundling.no_po_aksesoris;
                post.alasan_reject = $('#alasan_reject').val();

                axios.post("h3/h3_md_po_bundling/reject", Qs.stringify(post))
                  .then(function(res) {
                    data = res.data;

                    if (data.redirect_url != null) {
                      window.location = data.redirect_url;
                    }
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.error_type == 'validation_error') {
                      app.errors = data.errors;
                      toastr.error(data.message);
                    } else {
                      toastr.error(data.message);
                    }
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              harga_setelah_diskon: function(part, harga) {
                harga_setelah_diskon = harga;

                if (part.tipe_diskon == 'Rupiah') {
                  harga_setelah_diskon = harga - part.diskon_value;
                } else if (part.tipe_diskon == 'Persen') {
                  diskon = (part.diskon_value / 100) * harga;
                  harga_setelah_diskon = harga - diskon;
                }

                if (harga_setelah_diskon < 0) {
                  harga_setelah_diskon = 0;
                }

                return harga_setelah_diskon;
              },
              sub_total: function(part, harga) {
                harga_setelah_diskon = this.harga_setelah_diskon(part, harga);
                return (part.qty_order * harga_setelah_diskon);
              },
              error_exist: function(key) {
                return _.get(this.errors, key) != null;
              },
              get_error: function(key) {
                return _.get(this.errors, key)
              },
              get_currency_position: function(tipe_diskon) {
                if (tipe_diskon == 'Rupiah') {
                  return 'prefix';
                } else if (tipe_diskon == 'Persen') {
                  return 'suffix';
                }
                return;
              },
              get_currency_symbol: function(tipe_diskon) {
                if (tipe_diskon == 'Rupiah') {
                  return 'Rp';
                } else if (tipe_diskon == 'Persen') {
                  return '%';
                }
                return;
              },
            },
            computed: {
              total_amount: function() {
                sub_total_fn = this.sub_total;
                return _.chain(this.parts)
                  .sumBy(function(row) {
                    return sub_total_fn(row, row.harga);
                  })
                  .value();
              },
              dealer_tidak_tersedia: function() {
                return this.po_bundling.id_dealer == null || this.po_bundling.id_dealer == '';
              },
              qty_avs_tidak_memenuhi: function() {
                return _.chain(this.parts)
                  .filter(function(row) {
                    return (row.qty_order - row.qty_supply) > row.qty_avs;
                  })
                  .value();
              },
              selisih_harga_h3_dengan_h1: function() {
                harga_setelah_diskon_fn = this.harga_setelah_diskon;
                return _.chain(this.parts)
                  .filter(function(row) {
                    return harga_setelah_diskon_fn(row, row.harga) != harga_setelah_diskon_fn(row, row.harga_h1)
                  })
                  .value();
              },
              sales_order_belum_dibuat: function() {
                return this.po_bundling.id_sales_order == '' || this.po_bundling.id_sales_order == null;
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($set == "index") : ?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?php if ($this->input->get('history') != null) : ?>
                <a href="h3/<?= $isi ?>">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
                </a>
              <?php else : ?>
                <a href="h3/<?= $isi ?>?history=true">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
                </a>
                <a href="h3/<?= $isi ?>/rekap_po_bundling">
                  <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> Rekap PO Bundling</button>
                </a>
              <?php endif; ?>
            </h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="container-fluid no-padding" style='margin-top: 20px;'>
              <table id="po_logistik" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. PO</th>
                    <th>Tanggal PO</th>
                    <th>No. Paket Bundling</th>
                    <th>Kuantitas Paket</th>
                    <th>Keterangan</th>
                    <th>No. Surat Jalan</th>
                    <th>Status</th>
                    <th>No. Sales Order</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  po_logistik = $('#po_logistik').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    scrollX: true,
                    ajax: {
                      url: "<?= base_url('api/md/h3/po_bundling') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(d) {
                        d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                      }
                    },
                    createdRow: function(row, data, index) {
                      $('td', row).addClass('align-middle');
                    },
                    columns: [{
                        data: 'index',
                        orderable: false,
                        width: '3%'
                      },
                      {
                        data: 'no_po_aksesoris',
                      },
                      {
                        data: 'tgl_po',
                        render: function(data) {
                          return moment(data).format('DD/MM/YYYY');
                        }
                      },
                      {
                        data: 'id_paket_bundling',
                      },
                      {
                        data: 'qty_paket',
                      },
                      {
                        data: 'keterangan',
                        render: function(data) {
                          if (data != null && data != '') {
                            return data;
                          }
                          return '-';
                        }
                      },
                      {
                        data: 'no_surat_jalan',
                        render: function(data) {
                          if (data != null && data != '') {
                            return data;
                          }
                          return '-';
                        }
                      },
                      {
                        data: 'status_po'
                      },
                      {
                        data: 'id_sales_order',
                        width: '200px',
                        render: function(data) {
                          if (data != null) {
                            return data;
                          }
                          return '-';
                        }
                      },
                      {
                        data: 'action',
                        width: '3%',
                        orderable: false,
                        className: 'text-center'
                      },
                    ],
                  });
                });
              </script>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php endif; ?>
      <?php if($set == 'rekap_po_bundling') : ?>
        <p>test</p>
      <?php endif; ?>
    </section>
  </div>