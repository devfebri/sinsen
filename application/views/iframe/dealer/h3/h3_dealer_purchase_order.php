<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Purchase Order</title>
   </head>
   <body>
      <link rel="stylesheet" href="<?= base_url('assets/panel/bootstrap/css/bootstrap.min.css') ?>">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/font-awesome/css/font-awesome.min.css') ?>">   
      <!-- Ionicons -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/ionicons.min.css') ?>">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/dist/css/AdminLTE.min.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/custom.css') ?>">
      <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/dist/css/skins/_all-skins.min.css') ?>">
      <!-- iCheck -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/iCheck/flat/blue.css') ?>">
      <!-- Morris chart -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/morris/morris.css') ?>">
      <!-- jvectormap -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>">
      <!-- Date Picker -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/datepicker/datepicker3.css') ?>">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/daterangepicker/daterangepicker-bs3.css') ?>">
      <!-- bootstrap wysihtml5 - text editor -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/select2/select2.min.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/datatables/dataTables.bootstrap.css') ?>">
      <script src="<?= base_url('assets/panel/plugins/jQuery/jQuery-2.1.4.min.js') ?>"></script>
      <script src="<?= base_url('assets/panel/bootstrap/js/bootstrap.min.js') ?>"></script>
      <script src="<?= base_url('assets/panel/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
      <script src="<?= base_url('assets/panel/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
      <script src="<?= base_url('assets/panel/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') ?>"></script>
      <script src="<?= base_url('assets/panel/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
      <script src="<?= base_url('assets/panel/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
      <link rel="stylesheet" href="<?= base_url('assets/panel/plugins/iCheck/all.css') ?>">
      <link rel="stylesheet" href="<?= base_url('assets/toastr/toastr.css') ?>">
      <script src="<?= base_url('assets/panel/plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>
      <script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url('assets/panel/lodash.min.js') ?>"></script> 
      <script src="<?= base_url('assets/panel/moment.min.js') ?>"></script> 
      <script src="<?= base_url('assets/panel/daterangepicker.min.js') ?>"></script> 
      <link rel="stylesheet" type="text/css" href="<?= base_url('assets/panel/daterangepicker.css') ?>" />
      <script src="<?= base_url('assets/toastr/toastr.min.js') ?>"></script>
      <script>
         Vue.use(VueNumeric.default);
      </script>
      <base href="<?php echo base_url(); ?>" />
      <body>
      <section class="content">
      <div id="form_" class="box box-default">
        <div v-if='loading' class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div v-bind:class="{ 'has-error': error_exist('kategori_po') }" class="col-sm-4">
                    <select disabled class="form-control" v-model='purchase.kategori_po'>
                        <option value="">-Pilih-</option>
                        <option value="SIM Part">SIM Part</option>
                        <option value="Non SIM Part">Non SIM Part</option>
                      </select>
                      <small v-if="error_exist('kategori_po')" class="form-text text-danger">{{ get_error('kategori_po') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Produk</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">
                      <select disabled class="form-control" v-model='purchase.produk'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Acc</option>
                      </select>
                      <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Target Pembelian</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='purchase.target_pembelian' currency='Rp' separator='.' readonly/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ACH %</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='ach' currency='%' currency-symbol-position='suffix' readonly/>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total amount</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='totalHarga' currency='Rp' separator='.' readonly/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                    <div class="col-sm-4">
                     <input v-model='purchase.kode_dealer_md' type="text" class="form-control" disabled>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.nama_dealer' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <div v-if='is_hlo'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Order To</label>
                      <div class="col-sm-4">
                        <input v-if='!order_to_empty' readonly type="text" class="form-control" v-model="purchase.nama_dealer_terdekat">
                        <input v-if='order_to_empty' readonly type="text" class="form-control" value='MD'>
                      </div>
                    </div>
                    <label for="inputEmail3" class="control-label col-sm-2">Ship To</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.nama_dealer' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Purchase</label>
                    <div v-bind:class="{ 'has-error': errors.po_type != null }" class="col-sm-4">
                      <select required class="form-control" disabled v-model="purchase.po_type">
                        <option value="">-choose-</option>
                        <option value="FIX">Fix</option>
                        <option value="REG">Regular</option>
                        <option value="URG">Urgent</option>
                        <option value="HLO">Hotline</option>
                      </select>
                      <small v-if='errors.po_type != null' class="form-text text-danger">{{ errors.po_type }}</small>
                    </div>
                    <div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Order</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.tanggal_order' type="text" class="form-control" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.periode' type="text" class="form-control" disabled>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.tanggal_selesai' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div v-show='is_fix || is_reg' class="form-group">
                    <div v-show='is_fix || is_reg'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Batas Waktu</label>
                      <div v-bind:class="{ 'has-error': error_exist('batas_waktu') }" class="col-sm-4">
                        <input disabled type="text" class="form-control" v-model='purchase.batas_waktu'>
                        <small v-if="error_exist('batas_waktu')" class="form-text text-danger">{{ get_error('batas_waktu') }}</small>
                      </div>
                    </div>
                  </div>
                  <div v-if='is_urg' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">NRFS</label>
                    <div v-bind:class="{ 'has-error': error_exist('dokumen_nrfs_id') }" class="col-sm-4">
                        <input readonly type="text" placeholder="Klik untuk pilih" class="form-control" v-model="purchase.dokumen_nrfs_id">
                        <small v-if="error_exist('dokumen_nrfs_id')" class="form-text text-danger">{{ get_error('dokumen_nrfs_id') }}</small>
                    </div>
                  </div>
                  <div v-show='is_hlo' class="form-group">
                    <label class="col-sm-2 control-label">Booking Reference</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_booking') }" class="col-sm-4">
                        <input readonly type="text" class="form-control" v-model="purchase.id_booking">
                        <small v-if="error_exist('id_booking')" class="form-text text-danger">{{ get_error('id_booking') }}</small>
                    </div>
                  </div>
                  <div v-show='is_fix' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Pesan untuk bulan</label>
                    <div v-bind:class="{ 'has-error': error_exist('pesan_untuk_bulan') }" class="col-sm-4">
                      <select disabled v-model='purchase.pesan_untuk_bulan' class="form-control">
                        <option value="">-Pilih-</option>
                        <?php for ($i=1; $i <= 12 ; $i++): ?>
                        <option value="<?= $i ?>"><?= $this->lang->line("month_{$i}", true) ?></option>
                        <?php endfor; ?>
                      </select>
                      <small v-if="error_exist('pesan_untuk_bulan')" class="form-text text-danger">{{ get_error('pesan_untuk_bulan') }}</small>
                    </div>
                  </div>
                  <div class='form-group'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total Item Price</label>
                    <div class="col-sm-4">
                      <vue-numeric v-model='totalHargaNonFiltered' thousand-separator='.' currency='Rp ' disabled class='form-control'></vue-numeric>
                    </div>
                  </div>
                  <div v-if='is_fix' class="form-group">
                    <div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.status' type="text" class="form-control" disabled>
                      </div>
                    </div>
                  </div>
                  <div v-if='purchase.status == "Rejected"' class='form-group'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.alasan_reject' type="text" class="form-control" disabled>
                    </div>   
                  </div>
                  <div v-if='is_fix || is_reg' class="container no-margin">
                    <div class="row">
                      <div class="col-sm-2 mr-10">
                        <div class="form-group">
                          <label>Part Group</label>
                          <input readonly v-model='checked_part_group' type="text" class="form-control" data-toggle='modal' data-target='#part_group_purchase_order'>
                        </div>
                      </div>
                      <?php $this->load->view('modal/part_group_purchase_order') ?>
                      <div class="col-sm-2 mr-10">
                        <div class="form-group">
                          <label>Search</label>
                          <input :disabled='loading' type="text" class="form-control" v-model="search_filter">
                        </div>
                      </div>
                      <div style="padding-top: 30px;" class="col-sm-2 mr-10">
                        <div class="form-group">
                          <input :disabled='loading' type="checkbox" v-model="sim_part_filter">
                          <label> Show SIM Part</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <td width="3%">No. </td>
                        <td>
                          <span>Part Number</span>
                        </td>
                        <td>
                          <span>Part Deskripsi</span>
                        </td>
                        <td v-if='is_fix || is_reg'>
                          <span>Rank</span>
                        </td>
                        <td v-if='is_fix || is_reg' width='5%'>SIM Part</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-6</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-5</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-4</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-3</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-2</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-1</td>
                        <td v-if='is_fix || is_reg'>Avg. Weekly Demand</td>
                        <td v-if='is_fix || is_reg'>Qty AVS</td>
                        <td v-if='is_fix || is_reg'>Qty On Order</td>
                        <td v-if='is_fix || is_reg'>Qty In Transit</td>
                        <td v-if='is_fix || is_reg'>Stock Days</td>
                        <td v-if='is_fix || is_reg'>Sugessted Order</td>
                        <td v-if='is_fix || is_reg' width='7%'>Adjust Order</td>
                        <td v-if='!is_fix && !is_reg' width='7%'>Qty Order</td>
                        <td width='15%' class="text-right">Harga</td>
                        <td width='15%' class="text-right">Sub total</td>
                      </tr>
                      <tr v-if="filtered_parts.length > 0" v-for="(part, index) of filtered_parts" v-bind:class="{ 'text-red': melewati_maks_stok(part) }">
                        <td class="align-middle text-right">{{ index + 1 }}.</td>
                        <td class="align-middle">
                          <span>{{ part.id_part }}</span>
                        </td>
                        <td class="align-middle">
                          <span>{{ part.nama_part }}</span>
                        </td>
                        <td v-if='is_fix || is_reg' class="align-middle">
                          <span> ({{ part.rank }})</span>
                        </td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.sim_part }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w6 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w5 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w4 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w3 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w2 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w1 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.avg_six_weeks }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.stock }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">0</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.order_md }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">0</td>
                        <td v-if='is_fix || is_reg' class="align-middle" class="align-middle">
                          <vue-numeric read-only thousand-separator="." v-model="part.suggested_order" :empty-value="1" />
                        </td>
                        <td class='align-middle'>
                          <vue-numeric read-only class="input-compact" thousand-separator="." v-model="part.kuantitas" :empty-value="1" />
                        </td>
                        <td class="align-middle text-right">
                          <vue-numeric read-only currency="Rp " thousand-separator="." v-model="part.harga_saat_dibeli" />
                        </td>
                        <td class="align-middle text-right">
                          <vue-numeric read-only currency="Rp " thousand-separator="." v-model="subTotal(part)" />
                        </td>
                      </tr>
                      <tr v-if="parts.length > 0">
                        <td class="text-right" v-bind:colspan="item_colspan-2">Total</td>
                        <td class="text-right">
                          <vue-numeric read-only currency="Rp " thousand-separator="." v-model="totalHargaTanpaPPN" />
                        </td>
                      </tr>
                      <tr v-if="parts.length < 1">
                        <td v-bind:class='{ "bg-red": errors.parts != null }' v-bind:colspan="item_colspan" class="text-center text-muted">Belum ada part</td>
                      </tr>
                    </table>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        form_ = new Vue({
          el: '#form_',
          data: {
            search_filter: '',
            sim_part_filter: false,
            errors: {},
            loading: false,
            checked_part_group: [],
            purchase: <?= json_encode($purchase_order) ?>,
            parts: <?= json_encode($parts) ?>,
            part_group: <?= json_encode($part_group) ?>,
          },
          methods: {
            melewati_maks_stok: function(part){
              return false;
              return (Number(part.kuantitas) + Number(part.stock)) > part.maks_stok;
            },
            hapus_request_document: function () {
              this.purchase.id_booking = null;
              this.parts = [];
            },
            hapus_nrfs: function(){
              this.purchase.dokumen_nrfs_id = null;
              this.parts = [];
            },
            hapus_order_to: function(){
              this.purchase.order_to = null;
              this.parts = [];
            },
            simulate: function(){
              this.loading = true;
              axios.get('api/suggestedOrder')
              .then(function(response) {
                form_.parts = response.data;
                form_.get_parts_diskon();
                form_.get_parts_sales_campaign();
              })
              .catch(function(err) {
                toastr.error(err);
              })
              .then(function() {
                form_.loading = false;
              });
            },
            get_nrfs_parts:function(){
              if(this.purchase.dokumen_nrfs_id == '' || this.purchase.dokumen_nrfs_id == null) return;

              this.loading = true;
              axios.get('api/nrfs_part', {
                params: {
                    dokumen_nrfs_id: this.purchase.dokumen_nrfs_id
                }
              }).then(function(res) {
                form_.parts = res.data;
                form_.get_parts_diskon();
                form_.get_parts_sales_campaign();
              }).catch(function(error) {
                toastr.error(error);
              }).then(function(){ form_.loading = false; });
            },
            ambil_data_target_pembelian: function(){
              this.loading = true;
              axios.get('dealer/h3_dealer_purchase_order/ambil_data_target_pembelian', {
                params: {
                  produk: this.purchase.produk,
                }
              })
              .then(function(res) {
                data = res.data;
                if(data != null){
                  form_.purchase.target_pembelian = data.total_amount;
                  form_.purchase.id_salesman = data.id_salesman;
                }else{
                  form_.purchase.target_pembelian = 0;
                  form_.purchase.id_salesman = null;
                }
              })
              .catch(function(err) {
                toastr.error(err);
              })
              .then(function() {
                form_.loading = false;
              });
            },
            subTotal: function(part) {
              harga_setelah_diskon = part.harga_saat_dibeli;

              if(part.tipe_diskon == 'Rupiah'){
                harga_setelah_diskon = part.harga_saat_dibeli - part.diskon_value;
              }else if(part.tipe_diskon == 'Persen'){
                diskon = (part.diskon_value/100) * part.harga_saat_dibeli;
                harga_setelah_diskon = part.harga_saat_dibeli - diskon;
              }

              if(part.tipe_diskon_campaign == 'Rupiah'){
                harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
              }else if(part.tipe_diskon_campaign == 'Persen'){
                diskon = (part.diskon_value_campaign/100) * harga_setelah_diskon;
                harga_setelah_diskon = harga_setelah_diskon - diskon;
              }

              return part.kuantitas * harga_setelah_diskon;
            },
            get_parts_diskon: function(){ 
              return;
              if(this.parts.length < 1 && this.purchase.po_type == '') return;

              this.loading = true;
              axios.get('dealer/h3_dealer_purchase_order/get_parts_diskon', {
                params: {
                    id_part: _.map(this.parts, function(p){
                      return p.id_part
                    }),
                    po_type: this.purchase.po_type,
                }
              }).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(form_.parts, function(p) { 
                    return p.id_part == data.id_part; 
                  });

                  form_.parts[index].tipe_diskon = data.tipe_diskon;
                  form_.parts[index].diskon_value = data.diskon_value;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            get_parts_sales_campaign: function(){
              return;
              this.loading = true;
              axios.get('dealer/h3_dealer_purchase_order/get_parts_sales_campaign', {
                params: {
                    id_part: _.map(this.parts, function(p){
                      return p.id_part
                    }),
                }
              }).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(form_.parts, function(p) { 
                    return p.id_part == data.id_part; 
                  });

                  form_.parts[index].tipe_diskon_campaign = data.tipe_diskon_campaign;
                  form_.parts[index].diskon_value_campaign = data.diskon_value_campaign;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            getRequestDocumentParts: function(){
              this.loading = true;
              axios.get('dealer/h3_dealer_request_document/getRequestDocumentParts', {
                params: {
                    id_booking: this.purchase.id_booking,
                    po_type: this.purchase.po_type,
                }
              }).then(function(res) {
                form_.parts = _.map(res.data, function(p){
                  return _.omit(p, ['eta_revisi']);
                });
                form_.get_parts_diskon();
                form_.get_parts_sales_campaign();
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            hapusPart: function(index) {
              this.parts.splice(index, 1);
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            is_urg: function(){
              return this.purchase.po_type == 'URG';
            },
            is_fix: function(){
              return this.purchase.po_type == 'FIX';
            },
            is_hlo: function(){
              return this.purchase.po_type == 'HLO';
            },
            is_reg: function(){
              return this.purchase.po_type == 'REG';
            },
            item_colspan: function(){
              if(this.is_fix || this.is_reg){
                return 21;
              }
              return 7;
            },
            totalHargaTanpaPPN: function() {
              total = 0;
              for (part of this.filtered_parts) {
                total += this.subTotal(part);
              }
              return total;
            },
            totalPPN: function (){
              return 0;
              return (10/100) * this.totalHargaTanpaPPN;
            },
            totalHarga: function() {
              return this.totalHargaTanpaPPN + this.totalPPN;
            },
            totalHargaNonFiltered: function(){
              total = 0;
              for (part of this.parts) {
                total += this.subTotal(part);
              }
              return total;
              return total + ((10/100) * total);
            },
            ach: function(){
              if(this.purchase.target_pembelian != 0){
                ach = this.totalHarga/this.purchase.target_pembelian;
                ach = ach > 1 ? 1 : ach;
                ach = ach * 100;
                return ach;
              }else{
                return 0;
              }
            },
            request_document_empty: function(){
              return this.purchase.id_booking == null;
            },
            nrfs_empty: function(){
              return this.purchase.dokumen_nrfs_id == null;
            },
            order_to_empty: function(){
              return this.purchase.order_to == null || this.purchase.order_to == 0;
            },
            filtered_parts: function(){
              checked_part_group = this.checked_part_group
              filtered =  _.filter(this.parts, function(part){
                if(checked_part_group.length > 0){
                  return _.includes(checked_part_group, part.kelompok_part);
                }else{
                  return true;
                }
              });

              search_filter = this.search_filter;
              filtered = _.filter(filtered, function(part){
                return part.id_part.toLowerCase().includes(search_filter.toLowerCase())
              });

              if(this.sim_part_filter){
                filtered = _.filter(filtered, function(part){
                  return part.sim_part > 0;
                });
              }

              return filtered;
            }
          },
          watch: {
            'purchase.po_type': function(val) {
              this.parts = [];
              this.purchase.dokumen_nrfs_id = null;
              this.purchase.id_booking = null;
              this.purchase.order_to = null;
              this.purchase.nama_dealer_terdekat = '';
              this.purchase.pesan_untuk_bulan = '';
              datatable_part.draw();
            },
            'purchase.produk': function(n, o){
              this.ambil_data_target_pembelian();
              datatable_part.draw();
            },
            'purchase.pesan_untuk_bulan': function(n, o){
              this.ambil_data_target_pembelian();
            },
            totalHargaTanpaPPN: function(n, o){
              this.ambil_data_target_pembelian();
            }
          }
        });
      </script>
         </section>
   </body>
</html>