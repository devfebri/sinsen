<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Sales Order</title>
   </head>
   <body>
      <link rel="stylesheet" href="<?= base_url('assets/panel/bootstrap/css/bootstrap.min.css') ?>">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?= base_url('assets/panel/font-awesome/css/font-awesome.min.css">  ') ?>  
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
            <div id="app" class="box" style='border-top: 0; border-bottom: 0;'>
               <div v-if="loading" class="overlay">
                  <i class="fa fa-refresh fa-spin text-light-blue"></i>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <form class="form-horizontal">
                           <div class="box-body">
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">No SO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="sales_order.id_sales_order" />
                                 </div>
                                 <label class="col-sm-2 control-label">Tanggal SO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="sales_order.tanggal_so" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Nama Customer</label>
                                 <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                                    <input v-model='sales_order.nama_dealer' type="text" class="form-control" disabled>
                                    <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                                 </div>
                                 <label class="col-sm-2 control-label">Kode Customer</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="sales_order.kode_dealer_md" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Tipe PO</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model="sales_order.po_type" disabled>
                                 </div>
                                 <label class="col-sm-2 control-label">Alamat Customer</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="sales_order.alamat" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Masa Berlaku</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="sales_order.batas_waktu" />
                                 </div>
                                 <label class="col-sm-2 control-label">Jenis Pembayaran</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model="sales_order.jenis_pembayaran" disabled>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Kategori PO</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model="sales_order.kategori_po" disabled>
                                 </div>
                                 <div>
                                    <label class="col-sm-2 control-label">Nama Salesman</label>
                                    <div v-bind:class="{ 'has-error': error_exist('id_salesman') }" class="col-sm-4">
                                       <input v-model='sales_order.nama_salesman' type="text" class="form-control" disabled>
                                       <small v-if="error_exist('id_salesman')" class="form-text text-danger">{{ get_error('id_salesman') }}</small>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Produk</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model="sales_order.produk" disabled>
                                 </div>
                                 <label class="col-sm-2 control-label">Target Customer</label>
                                 <div v-bind:class="{ 'has-error': error_exist('target_customer') }" class="col-sm-4">
                                    <vue-numeric class="form-control" v-model='sales_order.target_customer' currency='Rp' separator='.' disabled></vue-numeric>
                                    <small v-if="error_exist('target_customer')" class="form-text text-danger">{{ get_error('target_customer') }}</small>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Tipe Source</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model="sales_order.tipe_source" disabled>
                                 </div>
                                 <label class="col-sm-2 control-label">Actual SO</label>
                                 <div v-bind:class="{ 'has-error': error_exist('sales_order_target') }" class="col-sm-4">
                                    <vue-numeric class="form-control" v-model='sales_order.sales_order_target' currency='Rp' separator='.' disabled></vue-numeric>
                                    <small v-if="error_exist('sales_order_target')" class="form-text text-danger">{{ get_error('sales_order_target') }}</small>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="control-label col-sm-2">Plafon</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" readonly v-model='sales_order.plafon' currency='Rp' separator='.'></vue-numeric>
                                 </div>
                                 <label class="col-sm-2 control-label">% Ach SO</label>
                                 <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_target') }" class="col-sm-4">
                                    <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_target' currency-symbol-position='suffix' currency='%' precision='1' disabled></vue-numeric>
                                    <small v-if="error_exist('persentase_sales_order_target')" class="form-text text-danger">{{ get_error('persentase_sales_order_target') }}</small>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="control-label col-sm-2">Sisa Plafon</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" readonly v-model='sisa_plafon' currency='Rp' separator='.'></vue-numeric>
                                 </div>
                                 <label class="col-sm-2 control-label">Actual Sales Out</label>
                                 <div v-bind:class="{ 'has-error': error_exist('sales_order_target_out') }" class="col-sm-4">
                                    <vue-numeric class="form-control" v-model='sales_order.sales_order_target_out' currency='Rp' separator='.' disabled></vue-numeric>
                                    <small v-if="error_exist('sales_order_target_out')" class="form-text text-danger">{{ get_error('sales_order_target_out') }}</small>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 col-sm-offset-6 control-label">% Ach Sales Out</label>
                                 <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_target_out') }" class="col-sm-4">
                                    <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_target_out' currency-symbol-position='suffix' currency='%' precision='1' disabled></vue-numeric>
                                    <small v-if="error_exist('persentase_sales_order_target_out')" class="form-text text-danger">{{ get_error('persentase_sales_order_target_out') }}</small>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">
                                    <table id="table" class="table table-condensed table-responsive">
                                       <thead>
                                          <tr>
                                             <th>No.</th>
                                             <th>Part Number</th>
                                             <th>Nama Part</th>
                                             <th v-if='kategori_kpb'>Tipe Kendaraan</th>
                                             <th width='10%'>HET</th>
                                             <th>Disc. Dealer</th>
                                             <th>Disc. Campaign</th>
                                             <th v-if="kategori_sim_part">Qty SIM Part Dealer</th>
                                             <th class='text-right'>Qty Actual Dealer</th>
                                             <th class='text-right'>Qty AVS</th>
                                             <th class='text-right' width='10%'>Qty Order</th>
                                             <?php if( $this->input->get('dengan_kuantitas_do_dan_revisi') ): ?>
                                             <th class='text-right' width='10%'>Qty DO</th>
                                             <!-- <th class='text-right' width='10%'>Qty Revisi</th> -->
                                             <?php endif; ?>
                                             <th v-if="sales_order.created_by_md == 0" class='text-right'>Qty Terpenuhi</th>
                                             <th width="15%" class="text-right">Nilai (Amount)</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr v-for="(part, index) in parts">
                                             <td class="align-middle">{{ index + 1 }}.</td>
                                             <td class="align-middle">{{ part.id_part }}</td>
                                             <td class="align-middle">{{ part.nama_part }}</td>
                                             <td v-if='kategori_kpb' class="align-middle">{{ part.id_tipe_kendaraan }}</td>
                                             <td class="align-middle">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga" currency="Rp " />
                                             </td>
                                             <td class="align-middle">
                                                <vue-numeric read-only v-model="part.diskon_value" :currency='get_currency_symbol(part.tipe_diskon)' :currency-symbol-position='get_currency_position(part.tipe_diskon)' separator='.'/>
                                             </td>
                                             <td class="align-middle">
                                                <vue-numeric read-only v-model="part.diskon_value_campaign" :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)' separator='.'/>
                                             </td>
                                             <td v-if="kategori_sim_part" class="align-middle">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_sim_part_dealer" />
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_actual_dealer" />
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs" />
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_order"/>
                                             </td>
                                             <?php if( $this->input->get('dengan_kuantitas_do_dan_revisi') ): ?>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_do"/>
                                             </td>
                                             <!-- <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_revisi"/>
                                             </td> -->
                                             <?php endif; ?>
                                             <td width="8%" class="align-middle text-right">
                                                <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                                             </td>
                                          </tr>
                                          <tr v-if="parts.length > 0">
                                             <td class="text-right" :colspan="total_label_coslpan">Total</td>
                                             <td class="text-right" colspan="1">
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency="Rp"></vue-numeric>
                                             </td>
                                          </tr>
                                          <tr v-if="parts.length < 1">
                                             <td class="text-center" colspan="15">Belum ada part</td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /.box -->
            <script>
               app = new Vue({
                   el: '#app',
                   data: {
                   generateByPO: <?= $this->input->get('generateByPO') != null ? 'true' : 'false' ?>,
                   loading: false,
                   errors: {},
                   sales_order: <?= json_encode($sales_order); ?>,
                   parts: <?= json_encode($parts); ?>,
                   },
                   methods: {
                     get_target_customer: function(){
                        if(this.sales_order.id_dealer == '') return;
                  
                        this.loading = true;
                        axios.get('h3/h3_md_sales_order/get_target_customer', {
                        params: {
                              id_dealer: this.sales_order.id_dealer,
                              produk: this.sales_order.produk
                        }
                        })
                        .then(function(res){
                        data = res.data;
                        app.sales_order.target_customer = data.target_customer;
                        app.sales_order.id_salesman = data.id_salesman;
                        app.sales_order.nama_salesman = data.nama_salesman;
                        })
                        .catch(function(err){
                        toastr.error(err);
                        })
                        .then(function(){
                        app.loading = false;
                        });
                     },
                     get_statistik_penjualan_customer: function(){
                        if(this.sales_order.id_dealer == '') return;
                  
                        this.loading = true;
                        axios.get('h3/h3_md_sales_order/get_statistik_penjualan_customer', {
                           params: {
                                 id_dealer: this.sales_order.id_dealer,
                                 produk: this.sales_order.produk
                           }
                        })
                        .then(function(res){
                           data = res.data;
                           app.sales_order.sales_order_target = data.sales_order_target;
                           app.sales_order.persentase_sales_order_target = data.persentase_sales_order_target;
                           app.sales_order.sales_order_out_target = data.sales_order_out_target;
                           app.sales_order.persentase_sales_order_out_target = data.persentase_sales_order_out_target;
                        })
                        .catch(function(err){
                           toastr.error(err);
                        })
                        .then(function(){
                           app.loading = false;
                        });
                     },
                     get_plafon: function(){
                        if(this.sales_order.id_dealer == '') return;
                  
                        this.loading = true;
                        axios.get('h3/h3_md_sales_order/get_plafon', {
                           params: {
                                 id_dealer: this.sales_order.id_dealer,
                           }
                        })
                        .then(function(res){
                           data = res.data;
                           app.sales_order.plafon = data.plafon;
                           app.sales_order.plafon_yang_dipakai = data.plafon_yang_dipakai;
                        })
                        .catch(function(err){
                           toastr.error(err);
                        })
                        .then(function(){
                           app.loading = false;
                        });
                     },
                     sub_total: function(part) {
                        harga_setelah_diskon = part.harga;
                  
                        if(part.tipe_diskon == 'Rupiah'){
                           harga_setelah_diskon = part.harga - part.diskon_value;
                        }else if(part.tipe_diskon == 'Persen'){
                           diskon = (part.diskon_value/100) * part.harga;
                           harga_setelah_diskon = part.harga - diskon;
                        }
                  
                        if(part.tipe_diskon_campaign == 'Rupiah'){
                           harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
                        }else if(part.tipe_diskon_campaign == 'Persen'){
                           diskon = (part.diskon_value_campaign/100) * harga_setelah_diskon;
                           harga_setelah_diskon = harga_setelah_diskon - diskon;
                        }
                  
                        if(this.sales_order.created_by_md == 1){
                           return (part.qty_order * harga_setelah_diskon);
                        }
                        return (part.qty_pemenuhan * harga_setelah_diskon);
                     },
                     get_parts_diskon: function(){
                        if(this.parts.length < 1 || this.sales_order.po_type == ''|| this.sales_order.id_dealer == '') return;
                  
                        this.loading = true;
                        axios.get('h3/h3_md_sales_order/get_parts_diskon', {
                           params: {
                              id_part: _.map(this.parts, function(p){
                                 return p.id_part
                              }),
                              po_type: this.sales_order.po_type,
                              id_dealer: this.sales_order.id_dealer,
                           }
                        }).then(function(res) {
                        for(data of res.data){
                           index = _.findIndex(app.parts, function(p) {
                              return p.id_part == data.id_part;
                           });
                  
                           app.parts[index].tipe_diskon = data.tipe_diskon;
                           app.parts[index].diskon_value = data.diskon_value;
                        }
                        }).catch(function(error) {
                           toastr.error(error);
                        })
                        .then(function(){ app.loading = false; });
                     },
                     get_parts_sales_campaign: function(){
                        return;
                        this.loading = true;
                        axios.get('h3/h3_md_sales_order/get_parts_sales_campaign', {
                           params: {
                              id_part: _.map(this.parts, function(p){
                              return p.id_part
                              }),
                           }  
                        }).then(function(res) {
                           for(data of res.data){
                              index = _.findIndex(app.parts, function(p) {
                              return p.id_part == data.id_part;
                           });
                  
                           app.parts[index].tipe_diskon_campaign = data.tipe_diskon_campaign;
                           app.parts[index].diskon_value_campaign = data.diskon_value_campaign;
                        }
                        }).catch(function(error) {
                           toastr.error(error);
                        })
                        .then(function(){ app.loading = false; });
                     },
                     get_parts_diskon_oli_reguler: function(){
                        if(this.parts.length < 1 || this.sales_order.id_dealer == '') return;
                  
                        this.loading = true;
                        post = _.pick(this.sales_order, ['id_dealer']);
                        post.parts = _.map(this.parts, function(p){
                           return _.pick(p, ['id_part', 'qty_order']);
                        });
                  
                        axios.post('h3/h3_md_sales_order/get_parts_diskon_oli_reguler', Qs.stringify(post)).then(function(res) {
                           for(data of res.data){
                              index = _.findIndex(app.parts, function(p) {
                              return p.id_part == data.id_part;
                              });
                  
                              app.parts[index].tipe_diskon = data.tipe_diskon;
                              app.parts[index].diskon_value = data.diskon_value;
                           }
                        }).catch(function(error) {
                           toastr.error(error);
                        })
                        .then(function(){ app.loading = false; });
                     },
                     get_diskon_parts: function(){
                        if(this.produk_oli){
                           this.get_parts_diskon_oli_reguler();
                        }else{
                           this.get_parts_diskon();
                           this.get_parts_sales_campaign();
                        }
                     },
                     reset_parts: function(){
                        this.parts = [];
                     },
                     error_exist: function(key){
                        return _.get(this.errors, key) != null;
                     },
                     get_error: function(key){
                        return _.get(this.errors, key)
                     },
                     get_currency_position: function(tipe_diskon){
                        if(tipe_diskon == 'Rupiah'){
                        return 'prefix';
                        }else if(tipe_diskon == 'Persen'){
                           return 'suffix';
                        }
                        return;
                     },
                     get_currency_symbol: function(tipe_diskon){
                        if(tipe_diskon == 'Rupiah'){
                        return 'Rp';
                        }else if(tipe_diskon == 'Persen'){
                           return '%';
                        }
                        return;
                     },
                   },
                   watch: {
                     'sales_order.id_dealer': function(){
                        h3_md_parts_sales_order_datatable.draw();
                        h3_md_salesman_sales_order_datatable.draw();
                        this.get_target_customer();
                        this.get_statistik_penjualan_customer();
                        this.get_plafon();
                  
                        this.get_diskon_parts();
                     },
                     'sales_order.produk': function(){
                        h3_md_parts_sales_order_datatable.draw();
                        this.get_target_customer();
                        this.reset_parts();
                     },
                     'sales_order.kategori_po': function(){
                        h3_md_parts_sales_order_datatable.draw();
                     },
                     'sales_order.po_type': function(){
                        this.get_diskon_parts();
                     }
                   },
                   mounted: function () {
                     this.get_target_customer();
                     this.get_plafon();
                     this.get_statistik_penjualan_customer();
                   },
                   computed: {
                     total: function(){
                        total = 0;
                        for(part of this.parts){
                           total += this.sub_total(part);
                        }
                        return total;
                     },
                     sisa_plafon: function(){
                        return this.sales_order.plafon - this.sales_order.plafon_yang_dipakai;
                     },
                     kategori_kpb: function () {
                        return this.sales_order.kategori_po == 'KPB';
                     },
                     kategori_sim_part: function () {
                        return this.sales_order.kategori_po == "SIM Part";
                     },
                     produk_oli: function () {
                        return this.sales_order.produk == "Oil";
                     },
                     produk_parts: function () {
                        return this.sales_order.produk == "Parts";
                     },
                     dealer_terisi: function(){
                        return this.sales_order.id_dealer != '';
                     },
                     total_label_coslpan: function(){
                        colspan = 9;

                        <?php if( $this->input->get('dengan_kuantitas_do_dan_revisi') ): ?>
                        colspan += 1;
                        <?php endif; ?>
                        
                        if(this.kategori_sim_part){
                           colspan += 1;
                        }

                        if(this.kategori_kpb){
                           colspan += 1;
                        }
                  
                        if (this.sales_order.created_by_md) {
                           colspan += 1;
                        }

                        colspan -= 1;

                        return colspan;
                     }
                  }
               });
            </script>
         </section>
   </body>
</html>