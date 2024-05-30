<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php

    if ($set == "form") {
      $form     = '';
      $disabled = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      } ?>

      <div id="app" class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h3/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div v-if='loading' class="overlay">
          <i class="text-light-blue fa fa-refresh fa-spin"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" readonly v-model='ekspedisi.nama_ekspedisi'>
                        <div class="input-group-btn">
                          <button v-if='true' :disabled='mode == "detail"' class="btn btn-primary btn-flat" type='button' data-toggle='modal' data-target='#h3_md_vendor_ekspedisi'><i class="fa fa-search"></i></button>
                          <button v-if='false' class="btn btn-danger btn-flat" @click.prevent='reset_dealer'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_vendor_ekspedisi'); ?>
                    <script>
                      function pilih_vendor_ekspedisi(data) {
                        app.ekspedisi.id_vendor = data.id_vendor;
                        app.ekspedisi.nama_ekspedisi = data.vendor_name;
                        app.ekspedisi.no_telp = data.no_telp;
                        app.ekspedisi.alamat = data.alamat;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">No. NPWP</label>
                    <div v-bind:class="{ 'has-error': error_exist('npwp') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='ekspedisi.npwp'>
                      <small v-if="error_exist('npwp')" class="form-text text-danger">{{ get_error('npwp') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Pemilik</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_pemilik') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='ekspedisi.nama_pemilik'>
                      <small v-if="error_exist('nama_pemilik')" class="form-text text-danger">{{ get_error('nama_pemilik') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Telepon</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_telp') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='ekspedisi.no_telp'>
                      <small v-if="error_exist('no_telp')" class="form-text text-danger">{{ get_error('no_telp') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                      <textarea :readonly='mode == "detail"' v-model='ekspedisi.alamat' rows="3" class="form-control"></textarea>
                      <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Kerjasama</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_kerjasama') }" class="col-sm-4">
                      <date-picker :disabled='mode == "detail"' @update-date='tanggal_kerjasama_datepicker_change' class='form-control' readonly :config='config' v-model='ekspedisi.tanggal_kerjasama'></date-picker>
                      <small v-if="error_exist('tanggal_kerjasama')" class="form-text text-danger">{{ get_error('tanggal_kerjasama') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">PPN (%)</label>
                    <div v-bind:class="{ 'has-error': error_exist('ppn') }" class="col-sm-4">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='ekspedisi.ppn' max='100' currency='%' currency-symbol-position='suffix'></vue-numeric>
                      <small v-if="error_exist('ppn')" class="form-text text-danger">{{ get_error('ppn') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" readonly v-model='ekspedisi.nama_dealer'>
                        <div class="input-group-btn">
                          <button v-if='dealer_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-primary btn-flat" type='button' data-toggle='modal' data-target='#h3_md_dealer_ekspedisi'><i class="fa fa-search"></i></button>
                          <button v-if='!dealer_empty && mode != "detail"' class="btn btn-danger btn-flat" @click.prevent='reset_dealer'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_ekspedisi'); ?>
                  <script>
                    function pilih_dealer_ekspedisi(data) {
                      app.ekspedisi.id_dealer = data.id_dealer;
                      app.ekspedisi.nama_dealer = data.nama_dealer;
                    }
                  </script>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-condensed">
                          <tr>
                            <td width='3%'>No.</td>
                            <td>Type Mobil</td>
                            <td>Kapasitas</td>
                            <td>No. Polisi</td>
                            <td>Nama Supir</td>
                            <td>Produk Angkatan</td>
                            <td v-if='mode != "detail"' width='3%'></td>
                          </tr>
                          <tr v-if='items.length > 0' v-for='(item, index) of items'>
                            <td width='3%'>{{ index + 1 }}.</td>
                            <td>
                              <input :disabled='mode == "detail"' type="text" class="form-control" v-model='item.type_mobil'>
                            </td>
                            <td>
                              <vue-numeric :disabled='mode == "detail"' class="form-control" separator='.' currency='Ton' v-model='item.kapasitas' currency-symbol-position='suffix'></vue-numeric>
                            </td>
                            <td>
                              <input :disabled='mode == "detail"' type="text" class="form-control" v-model='item.no_polisi'>
                            </td>
                            <td>
                              <input :disabled='mode == "detail"' type="text" class="form-control" v-model='item.nama_supir'>
                            </td>
                            <td>
                              <select :disabled='mode == "detail"' v-model='item.produk_angkatan' class="form-control">
                                <option value="">-Pilih-</option>
                                <option value="Parts">Parts</option>
                                <option value="Oil">Oil</option>
                              </select>
                            </td>
                            <td v-if='mode != "detail"' width='3%'>
                              <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_item(index)'><i class="fa fa-trash-o"></i></button>
                            </td>
                          </tr>
                          <tr v-if='items.length < 1'>
                            <td colspan='7' class='text-center'>Tidak ada data.</td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div v-if='mode != "detail"' class="row">
                      <div class="col-sm-12 no-padding text-right">
                        <button class="btn btn-flat btn-sm btn-primary" @click.prevent='tambah_item'><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + ekspedisi.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        app = new Vue({
          el: '#app',
          data: {
            mode: '<?= $mode ?>',
            loading: false,
            errors: {},
            <?php if ($mode == 'detail' or $mode == 'edit') : ?>
              ekspedisi: <?= json_encode($ekspedisi) ?>,
              items: <?= json_encode($items) ?>,
            <?php else : ?>
              ekspedisi: {
                id_vendor: '',
                nama_ekspedisi: '',
                npwp: '',
                nama_pemilik: '',
                no_telp: '',
                alamat: '',
                tanggal_kerjasama: '',
                ppn: 0,
                id_dealer: '',
                nama_dealer: '',
              },
              items: [],
            <?php endif; ?>
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
              todayBtn: 'linked',
              todayHighlight: true,
            },
          },
          methods: {
            <?= $form ?>: function() {
              post = _.pick(this.ekspedisi, [
                'id', 'id_vendor', 'nama_ekspedisi', 'npwp', 'nama_pemilik',
                'no_telp', 'alamat', 'tanggal_kerjasama', 'ppn', 'id_dealer'
              ]);

              post.items = _.map(this.items, function(item) {
                return _.pick(item, [
                  'type_mobil', 'kapasitas', 'no_polisi',
                  'nama_supir', 'produk_angkatan'
                ]);
              });

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
                .then(function(res) {
                  data = res.data;
                  if(data.redirect_url != null) window.location = data.redirect_url;
                })
                .catch(function(err) {
                  data = err.response.data;
                  if (data.error_type == 'validation_error') {
                    app.errors = data.errors;
                  }

                  if (data.message != null) {
                    toastr.error(data.message);
                  } else {
                    toastr.error(err);
                  }

                  app.loading = false;
                });
            },
            tambah_item: function() {
              item = {
                type_mobil: '',
                kapasitas: '',
                no_polisi: '',
                nama_supir: '',
                produk_angkatan: '',
              };

              this.items.push(item);
            },
            hapus_item: function(index) {
              this.items.splice(index, 1);
            },
            tanggal_kerjasama_datepicker_change: function(date) {
              this.ekspedisi.tanggal_kerjasama = date.format('yyyy-mm-dd');
            },
            reset_dealer: function() {
              this.ekspedisi.id_dealer = '';
              this.ekspedisi.nama_dealer = '';
            },
            error_exist: function(key) {
              return _.get(this.errors, key) != null;
            },
            get_error: function(key) {
              return _.get(this.errors, key)
            }
          },
          computed: {
            dealer_empty: function() {
              return this.ekspedisi.id_dealer == '' || this.ekspedisi.id_dealer == null;
            }
          }
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h3/<?= $isi ?>/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="master_ekspedisi" class="table table-bordered table-hover table-condensed">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Ekspedisi</th>
                <th>Nama Pemilik</th>
                <th>Alamat</th>
                <th>No Telepon</th>
                <th>Produk Angkutan</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              master_ekspedisi = $('#master_ekspedisi').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_ekspedisi') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function(row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [{
                    data: null,
                    orderable: false,
                    width: '3%'
                  },
                  {
                    data: 'nama_ekspedisi'
                  },
                  {
                    data: 'nama_pemilik'
                  },
                  {
                    data: 'alamat'
                  },
                  {
                    data: 'no_telp'
                  },
                  {
                    data: 'produk_angkutan',
                    orderable: false
                  },
                  {
                    data: 'action',
                    width: '3%',
                    orderable: false,
                    className: 'text-center'
                  },
                ],
              });

              master_ekspedisi.on('draw.dt', function() {
                var info = master_ekspedisi.page.info();
                master_ekspedisi.column(0, {
                  search: 'applied',
                  order: 'applied',
                  page: 'applied'
                }).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1 + info.start + ".";
                });
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } ?>
  </section>
</div>