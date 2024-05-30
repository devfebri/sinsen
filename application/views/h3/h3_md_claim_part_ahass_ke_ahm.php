<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
        }
        if ($mode == 'terima_claim') {
          $form = 'simpan_claim';
        }
        if ($mode == 'detail') {
          $form = 'detail';
          $disabled = 'disabled';
        }
        if ($mode == 'edit') {
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
            <?php $this->load->view('template/session_message.php'); ?>
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div v-if='part_yang_sama_berbeda_dealer.length > 0' class="alert alert-warning" role="alert">
                      <strong>Perhatian!</strong> Terdapat part yang sama untuk dealer yang berbeda dalam 1 Claim MD ke AHM.
                    </div>
                    <div v-if='mode == "detail"' class="form-group">
                      <label for="" class="col-sm-2 control-label">No Claim Part AHASS</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" readonly v-model='claim_part_ahass.id_claim_part_ahass'>
                      </div>
                      <label for="" class="col-sm-2 control-label">Tgl Claim Part AHASS</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" readonly v-model='claim_part_ahass.created_at'>
                      </div>
                    </div>
                    <div v-if='mode == "detail"' class="form-group">
                      <label for="" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" readonly v-model='claim_part_ahass.status'>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <table id="table" class="table table-responsive">
                          <thead>
                            <tr>
                              <th class='align-top' width='3%'>No.</th>
                              <th class='align-top'>No Claim Dealer</th>
                              <th class='align-top'>Tgl Claim Dealer</th>
                              <th class='align-top'>Nama Customer</th>
                              <th class='align-top' width="10%">Kode Part</th>
                              <th class='align-top' width="10%">Qty Part Diclaim</th>
                              <th class='align-top' width="10%">Tgl Packing Sheet</th>
                              <th class='align-top' width="10%">No Packing Sheet</th>
                              <th class='align-top' width="10%">Keterangan</th>
                              <th class='align-top' v-if="mode != 'detail'" width="3%"></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(claim_dealer_part, index) in claim_dealer_parts">
                              <td class="align-middle">{{ index + 1 }}.</td>
                              <td class="align-middle">{{ claim_dealer_part.id_claim_dealer }}</td>
                              <td class="align-middle">{{ claim_dealer_part.tanggal }}</td>
                              <td class="align-middle">{{ claim_dealer_part.nama_dealer }}</td>
                              <td class="align-middle">{{ claim_dealer_part.id_part }}</td>
                              <td class="align-middle">
                                <vue-numeric :read-only='true' class="form-control" separator="." :empty-value="1" v-model="claim_dealer_part.qty_part_diclaim" />
                              </td>
                              <td class="align-middle">{{ claim_dealer_part.tgl_packing_sheet }}</td>
                              <td class="align-middle">{{ claim_dealer_part.id_packing_sheet }}</td>
                              <td class="align-middle">{{ claim_dealer_part.nama_claim }}</td>
                              <td class="align-middle" v-if='mode != "detail"'>
                                <input type="checkbox" true-value='1' false-value='0' @change.prevent='checklistChanged' v-model='claim_dealer_part.checklist'>
                              </td>
                            </tr>
                            <tr v-if="claim_dealer_parts.length < 1">
                              <td class="text-center" colspan="9">Belum ada part</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="container-fluid bg-blue" style='padding: 5px 0; margin-bottom: 15px;'>
                      <div class="row">
                        <div class="col-sm-12 text-center">
                          <span class='text-bold'>PROSES CLAIM</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="" class="col-sm-2 control-label">No. Packing Sheet</label>
                      <div v-bind:class="{ 'has-error': error_exist('packing_sheet_number') }" class="col-sm-4">
                        <div class="input-group">
                          <input type="text" class="form-control" readonly v-model='claim_part_ahass.packing_sheet_number'>
                          <div class="input-group-btn">
                            <button :disabled='mode == "detail"' v-if='empty_packing_sheet_number || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-target='#h3_md_packing_sheet_ahm_claim_part_ahass_modal' data-toggle='modal'><i class="fa fa-search"></i></button>
                            <button v-if='!empty_packing_sheet_number && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_packing_sheet_number'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                        <small v-if="error_exist('packing_sheet_number')" class="form-text text-danger">{{ get_error('packing_sheet_number') }}</small>
                      </div>
                      <?php $this->load->view('modal/h3_md_packing_sheet_ahm_claim_part_ahass_modal'); ?>
                      <script>
                        function pilih_packing_sheet_ahm_claim_part_ahass(data) {
                          app.claim_part_ahass.packing_sheet_number_int = data.id;
                          app.claim_part_ahass.packing_sheet_number = data.packing_sheet_number;
                          app.claim_part_ahass.packing_sheet_date = data.packing_sheet_date;
                          app.claim_part_ahass.nomor_karton = data.nomor_karton;
                          app.claim_part_ahass.nomor_karton_int = data.nomor_karton_int;
                          app.claim_part_ahass.jumlah_item_dalam_karton = data.jumlah_item;
                        }
                      </script>
                      <label for="" class="col-sm-2 control-label">Tgl. Packing Sheet</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" readonly v-model='claim_part_ahass.packing_sheet_date'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="" class="col-sm-2 control-label">Nomor karton</label>
                      <div class="col-sm-4">
                        <input disabled type="text" class="form-control" v-model='claim_part_ahass.nomor_karton'>
                      </div>
                      <label for="" class="col-sm-2 control-label">Jumlah item dalam karton</label>
                      <div class="col-sm-4">
                        <input disabled type="text" class="form-control" v-model='claim_part_ahass.jumlah_item_dalam_karton'>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <table id="table" class="table table-responsive">
                          <thead>
                            <tr>
                              <th class='align-top' width="10%">Kode Part</th>
                              <th class='align-top' width="10%">Qty PS</th>
                              <th class='align-top' width="10%">Qty Part Diclaim</th>
                              <th class='align-top' width="10%">Qty Dikirim ke AHM</th>
                              <th class='align-top' width="10%">Kode Claim</th>
                              <th class='align-top' width="10%">Keterangan</th>
                              <th class='align-top' width="10%">Keputusan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(part, index) in claim_parts_to_ahm">
                              <td class="align-middle">{{ part.id_part }}</td>
                              <td class="align-middle">
                                <vue-numeric :read-only='true' class="form-control" separator="." :empty-value="1" v-model="part.qty_ps" />
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only='true' class="form-control" separator="." :empty-value="1" v-model="part.qty_part_diclaim" />
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only='true' class="form-control" separator="." :empty-value="1" v-model="part.qty_part_diclaim" />
                              </td>
                              <td class="align-middle">{{ gabungkan_kode_dan_nama_claim(part) }}</td>
                              <td class="align-middle">{{ part.nama_claim }}</td>
                              <td class="align-middle">{{ part.keputusan }}</td>
                            </tr>
                            <tr v-if="claim_parts_to_ahm.length < 1">
                              <td class="text-center" colspan="7">Tidak ada data</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="container-fluid bg-blue" style='padding: 5px 0; margin-bottom: 15px;'>
                      <div class="row">
                        <div class="col-sm-12 text-center">
                          <span class='text-bold'>Dokumen Pendukung yang Wajib Disertakan</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label no-padding">Packing sheet</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_packing_sheet'>
                      </div>
                      <label class="col-sm-3 control-label no-padding">Packing Ticket</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_packing_ticket'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label no-padding">Foto Bukti (Parts/Kardus/Label/dll)</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_foto_bukti'>
                      </div>
                      <label class="col-sm-3 control-label no-padding">Shipping List</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_shipping_list'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label no-padding">Nomor Karton</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_nomor_karton'>
                      </div>
                      <label class="col-sm-3 control-label no-padding">Tutup Botol</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_tutup_botol'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label no-padding">Label Timbangan</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_label_timbangan'>
                      </div>
                      <label class="col-sm-3 control-label no-padding">Label Karton</label>
                      <div class="col-sm-1">
                        <input :disabled='mode == "detail"' type="checkbox" true-value="1" false-value="0" v-model='claim_part_ahass.dokumen_label_karton'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Lain - lain</label>
                      <div class="col-sm-3">
                        <input :disabled='mode == "detail"' class="form-control form-control-sm" type="type" v-model='claim_part_ahass.dokumen_lain'>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <button v-if="mode == 'insert'" :disabled='part_yang_sama_berbeda_dealer.length > 0 || loading || claim_parts_to_ahm.length < 1' class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                      <button v-if="mode == 'edit'" :disabled='part_yang_sama_berbeda_dealer.length > 0 || loading || claim_parts_to_ahm.length < 1' class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                      <a v-if='mode == "detail" && claim_part_ahass.status != "Canceled" && claim_part_ahass.status != "Processed"' :href="'h3/<?= $isi ?>/edit?id_claim_part_ahass=' + claim_part_ahass.id_claim_part_ahass" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                    <div class="col-sm-6 no-padding text-right">
                      <button v-if="mode == 'detail' && claim_part_ahass.status != 'Processed' && claim_part_ahass.status == 'Open'" :disabled='part_yang_sama_berbeda_dealer.length > 0 || loading || claim_parts_to_ahm.length < 1' class="btn btn-sm btn-flat btn-info" @click.prevent="proses">Proses</button>
                      <button v-if="mode == 'detail' && claim_part_ahass.status != 'Canceled' && claim_part_ahass.status != 'Processed'" :disabled='part_yang_sama_berbeda_dealer.length > 0 || loading || claim_parts_to_ahm.length < 1' class="btn btn-sm btn-flat btn-danger" @click.prevent="cancel">Cancel</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/cetak?id_claim_part_ahass=' + claim_part_ahass.id_claim_part_ahass" class="btn btn-flat btn-sm btn-info">Cetak</a>
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
              mode: '<?= $mode ?>',
              <?php if ($mode == "detail" || $mode == "edit") : ?>
                claim_part_ahass: <?= json_encode($claim_part_ahass) ?>,
              <?php else : ?>
                claim_part_ahass: {
                  packing_sheet_number_int: '',
                  packing_sheet_number: '',
                  packing_sheet_date: '',
                  nomor_karton: '',
                  nomor_karton_int: '',
                  jumlah_item_dalam_karton: 0,
                  dokumen_packing_sheet: 0,
                  dokumen_packing_ticket: 0,
                  dokumen_foto_bukti: 0,
                  dokumen_shipping_list: 0,
                  dokumen_nomor_karton: 0,
                  dokumen_tutup_botol: 0,
                  dokumen_label_timbangan: 0,
                  dokumen_label_karton: 0,
                  dokumen_lain: '',
                  status: '',
                },
              <?php endif; ?>
              claim_dealer_parts: <?= json_encode($claim_dealer_parts) ?>,
            },
            mounted: function() {
              this.get_claim_dealer_parts();
            },
            methods: {
              <?= $form ?>: function() {
                post = _.pick(this.claim_part_ahass, [
                  'packing_sheet_number', 'packing_sheet_number_int', 'nomor_karton', 'nomor_karton_int', 'jumlah_item_dalam_karton',
                  'dokumen_packing_sheet', 'dokumen_packing_ticket', 'dokumen_foto_bukti', 'dokumen_shipping_list',
                  'dokumen_nomor_karton', 'dokumen_tutup_botol', 'dokumen_label_timbangan', 'dokumen_label_karton',
                  'dokumen_lain', 'id_claim_part_ahass'
                ]);

                post.claim_dealer_parts = _.chain(this.claim_dealer_parts).filter(function(part) {
                    return part.checklist == 1;
                  })
                  .map(function(part) {
                    return _.pick(part, [
                      'id_part', 'id_claim_dealer', 'id_kategori_claim_c3'
                    ]);
                  }).value();

                this.loading = true;
                axios.post('h3/h3_md_claim_part_ahass_ke_ahm/<?= $form ?>', Qs.stringify(post))
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.error_type == 'validation_error') {
                      app.errors = data.errors;
                      toastr.error(data.message);
                    } else {
                      toastr.error(err);
                    }

                    app.loading = false;
                  });
              },
              proses: function() {
                params = {
                  id_claim_part_ahass: this.claim_part_ahass.id_claim_part_ahass
                };

                this.loading = true;
                axios.get('h3/<?= $isi ?>/proses', {
                    params: params
                  })
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.message != null) {
                      toastr.error(data.message);
                    } else {
                      toastr.error(err);
                    }
                    app.loading = false;
                  });
              },
              cancel: function() {
                params = {
                  id_claim_part_ahass: this.claim_part_ahass.id_claim_part_ahass
                };

                this.loading = true;
                axios.get('h3/<?= $isi ?>/cancel', {
                    params: params
                  })
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) window.location = data.redirect_url;
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.message != null) {
                      toastr.error(data.message);
                    } else {
                      toastr.error(err);
                    }
                    app.loading = false;
                  });
              },
              check_packing_sheet_available: function() {
                if (this.mode == 'detail') return;

                post = {};
                post.claim_parts_to_ahm = _.chain(this.claim_parts_to_ahm)
                  .map(function(part) {
                    return _.pick(part, ['id_part', 'id_part_int', 'qty_part_diclaim']);
                  })
                  .value();
                post.packing_sheet_number = app.claim_part_ahass.packing_sheet_number;
                this.loading = true;
                axios.post('<?= base_url('api/md/h3/packing_sheet_ahm_claim_part_ahass/check_packing_sheet_available') ?>', Qs.stringify(post))
                  .then(function(res) {
                    console.log(res);
                    if (res.data == 0) {
                      app.claim_part_ahass.packing_sheet_number_int = '';
                      app.claim_part_ahass.packing_sheet_number = '';
                      app.claim_part_ahass.packing_sheet_date = '';
                    }
                  })
                  .catch(function(err) {
                    toastr.error('ERROR: Saat pengecekan ketersediaan packing sheet.');
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              get_claim_dealer_parts: function() {
                params = {
                  id_claim_part_ahass: this.claim_part_ahass.id_claim_part_ahass,
                  packing_sheet_number_int: this.claim_part_ahass.packing_sheet_number_int,
                  mode: this.mode,
                };

                this.loading = true;
                axios.get('h3/<?= $isi ?>/get_claim_dealer_parts', {
                    params: params
                  })
                  .then(function(res) {
                    app.claim_dealer_parts = res.data;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              update_qty_ps: function() {
                this.reset_qty_ps();

                params = {
                  packing_sheet_number_int: this.claim_part_ahass.packing_sheet_number_int,
                  nomor_karton_int: this.claim_part_ahass.nomor_karton_int,
                  id_part_int: _.chain(this.claim_parts_to_ahm)
                    .map(function(row) {
                      return row.id_part_int;
                    })
                    .value()
                }

                this.loading = true;
                axios.get('h3/<?= $isi ?>/update_qty_ps', {
                    params: params
                  })
                  .then(function(res) {
                    data = res.data;

                    for (row of data) {
                      for (var i = 0; i < app.claim_dealer_parts.length; i++) {
                        claim_dealer_part = app.claim_dealer_parts[i];
                        if (claim_dealer_part.checklist == 1 && claim_dealer_part.id_part_int == row.id_part_int) {
                          app.claim_dealer_parts[i].qty_ps = row.kuantitas;
                        }
                      }
                    }
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              reset_qty_ps: function() {
                for (var i = 0; i < app.claim_dealer_parts.length; i++) {
                  claim_dealer_part = app.claim_dealer_parts[i];
                  if (claim_dealer_part.checklist == 1) {
                    app.claim_dealer_parts[i].qty_ps = 0;
                  }
                }
              },
              reset_packing_sheet_number: function() {
                this.claim_part_ahass.packing_sheet_number_int = '';
                this.claim_part_ahass.packing_sheet_number = '';
                this.claim_part_ahass.packing_sheet_date = '';
                this.claim_part_ahass.nomor_karton = '';
                this.claim_part_ahass.nomor_karton_int = '';
                this.claim_part_ahass.jumlah_item_dalam_karton = 0;
              },
              gabungkan_kode_dan_nama_claim: function(item) {
                if (item.kode_claim != '' && item.kode_claim != null) {
                  return item.kode_claim + ' - ' + item.nama_claim;
                }
                return '-';
              },
              error_exist: function(key) {
                return _.get(this.errors, key) != null;
              },
              get_error: function(key) {
                return _.get(this.errors, key)
              },
              checklistChanged: function() {
                if (this.claim_part_ahass.packing_sheet_number != '' && this.claim_part_ahass.packing_sheet_number != null) {
                  app.check_packing_sheet_available();
                  this.update_qty_ps();
                }
                h3_md_packing_sheet_ahm_claim_part_ahass_datatable.draw();
              }
            },
            watch: {
              'claim_part_ahass.packing_sheet_number_int': function() {
                this.update_qty_ps();
                h3_md_packing_sheet_ahm_claim_part_ahass_datatable.draw();
              }
            },
            computed: {
              empty_packing_sheet_number: function() {
                return this.claim_part_ahass.packing_sheet_number == '' || this.claim_part_ahass.packing_sheet_number == null;
              },
              claim_parts_to_ahm: function() {
                return _.chain(this.claim_dealer_parts)
                  .filter(function(part) {
                    return part.checklist == 1;
                  })
                  .value();
              },
              part_yang_sama_berbeda_dealer: function() {
                return _.chain(this.claim_dealer_parts)
                  .filter(function(part) {
                    return part.checklist == 1;
                  })
                  .groupBy('id_part')
                  .map(function(data, index) {
                    dealers = _.chain(data)
                      .map(function(row) {
                        return row.id_dealer
                      })
                      .uniq()
                      .value();
                    return {
                      id_part: index,
                      dealers: dealers
                    }
                  })
                  .filter(function(data) {
                    return data.dealers.length > 1;
                  })
                  .value();
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($mode == "index") : ?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
            </h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <table id="claim_part_ahass" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Tanggal Claim MD</th>
                  <th>No Claim MD</th>
                  <th>Customer</th>
                  <th>Tgl Faktur</th>
                  <th>No. Faktur</th>
                  <th>No. PS AHM</th>
                  <th>Tgl PS AHM</th>
                  <th>Status</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <script>
              $(document).ready(function() {
                claim_part_ahass = $('#claim_part_ahass').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  scrollX: true,
                  ajax: {
                    url: "<?= base_url('api/md/h3/claim_part_ahass') ?>",
                    dataSrc: "data",
                    type: "POST"
                  },
                  columns: [{
                      data: 'index',
                      orderable: false,
                      width: '3%'
                    },
                    {
                      data: 'created_at'
                    },
                    {
                      data: 'id_claim_part_ahass'
                    },
                    {
                      data: 'customer_diclaim'
                    },
                    {
                      data: 'invoice_date',
                      render: function(data) {
                        if (data != null) {
                          return data;
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'invoice_number',
                      render: function(data) {
                        if (data != null) {
                          return data;
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'packing_sheet_number'
                    },
                    {
                      data: 'packing_sheet_date'
                    },
                    {
                      data: 'status'
                    },
                    {
                      data: 'action',
                      orderable: false,
                      width: '3%',
                      className: 'text-center'
                    }
                  ],
                });
              });
            </script>
          </div><!-- /.box-body -->
          <?php $this->load->view('modal/h3_md_open_view_customer_claim_part_ahass'); ?>
          <script>
            function open_view_customer(id_claim_part_ahass) {
              $('#id_claim_part_ahass_for_open_view_customer').val(id_claim_part_ahass);
              $('#h3_md_open_view_customer_claim_part_ahass').modal('show');
              h3_md_open_view_customer_claim_part_ahass_datatable.draw();
            }
          </script>
        </div><!-- /.box -->
      <?php endif; ?>
    </section>
  </div>