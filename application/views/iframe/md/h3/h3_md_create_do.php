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
                                    <input type="text" readonly class="form-control" v-model="delivery_order.id_sales_order" />
                                 </div>
                                 <label class="col-sm-2 control-label">Nama Customer</label>
                                 <div class="col-sm-4">
                                    <input v-model='delivery_order.nama_dealer' type="text" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Tanggal SO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="delivery_order.tanggal_so" />
                                 </div>
                                 <label class="col-sm-2 control-label">Alamat Customer</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="delivery_order.alamat" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Tipe PO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="delivery_order.po_type" />
                                 </div>
                                 <label class="col-sm-2 control-label">Kategori</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="delivery_order.kategori_po" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Produk</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="delivery_order.produk" />
                                 </div>
                                 <label class="col-sm-2 control-label">Plafon</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" readonly currency='Rp' separator='.' v-model='delivery_order.plafon'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Plafon Booking</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" readonly currency='Rp' separator='.' v-model='delivery_order.plafon_booking'></vue-numeric>
                                 </div>
                                 <label class="col-sm-2 control-label">Sisa Plafon</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" readonly currency='Rp' separator='.' v-model='delivery_order.sisa_plafon'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Jenis Pembayaran</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='delivery_order.jenis_pembayaran' readonly>
                                 </div>
                                 <label class="col-sm-2 control-label">Status</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='delivery_order.status' readonly>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="" class="col-sm-2 control-label no-padding">Nilai SO</label>
                                 <div class="col-sm-4">
                                    <div class="row">
                                       <div class="col-sm-4 align-items">
                                          <vue-numeric read-only currency='Rp' v-model='jumlah_amount_sales_order' separator='.'></vue-numeric>
                                       </div>
                                       <div class="col-sm-4">
                                          <vue-numeric read-only currency='Item' currency-symbol-position='suffix' v-model='jumlah_item_sales_order'></vue-numeric>
                                       </div>
                                       <div class="col-sm-4">
                                          <vue-numeric read-only currency='Pcs' currency-symbol-position='suffix' v-model='jumlah_pcs_sales_order'></vue-numeric>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="" class="col-sm-2 control-label no-padding">Nilai DO</label>
                                 <div class="col-sm-4">
                                    <div class="row">
                                       <div class="col-sm-4 align-items">
                                          <vue-numeric read-only currency='Rp' v-model='jumlah_amount_delivery_order' separator='.'></vue-numeric>
                                       </div>
                                       <div class="col-sm-4">
                                          <vue-numeric read-only currency='Item' currency-symbol-position='suffix' v-model='jumlah_item_delivery_order'></vue-numeric>
                                       </div>
                                       <div class="col-sm-4">
                                          <vue-numeric read-only currency='Pcs' currency-symbol-position='suffix' v-model='jumlah_pcs_delivery_order'></vue-numeric>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">
                                    <table id="table" class="table table-condensed table-responsive">
                                       <thead>
                                          <tr class='bg-blue-gradient'>
                                             <th width='3%'>No.</th>
                                             <th>Kode Part</th>
                                             <th>Nama Part</th>
                                             <th>HET</th>
                                             <th width='10%'>Disc. Dealer</th>
                                             <th width='10%'>Disc. Campaign</th>
                                             <th width='5%' class='text-right'>Qty AVS</th>
                                             <th width='5%' class='text-right'>Qty SO</th>
                                             <th width='5%' class='text-right'>Qty DO</th>
                                             <th width='5%' class='text-center'>S/R</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr v-for="(part, index) in parts">
                                             <td class="align-middle">{{ index + 1 }}.</td>
                                             <td class="align-middle">{{ part.id_part }}</td>
                                             <td class="align-middle">{{ part.nama_part }}</td>
                                             <td class="align-middle">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga_jual" currency="Rp " />
                                             </td>
                                             <td class="align-top">
                                                <vue-numeric read-only v-model="part.diskon_satuan_dealer" :currency='get_currency_symbol(part.tipe_diskon_satuan_dealer)' :currency-symbol-position='get_currency_position(part.tipe_diskon_satuan_dealer)' separator='.'/>
                                             </td>
                                             <td class="align-top">
                                                <vue-numeric read-only v-model="part.diskon_campaign" :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)' separator='.'/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs" />
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_so"/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_do"/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" thousand-separator="." :empty-value="1" v-model="part.service_rate" currency='%' currency-symbol-position='suffix'/>
                                             </td>
                                          </tr>
                                          <tr v-if='parts.length > 0'>
                                             <td colspan='8' class='text-right'>Total Amount</td>
                                             <td colspan='2' class='text-right'>
                                                <vue-numeric read-only v-model='total' currency='Rp' separator='.'></vue-numeric>
                                             </td>
                                          </tr>
                                          <tr v-if="parts.length < 1">
                                             <td class="text-center" colspan="12">Tidak ada data.</td>
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
                     loading: false,
                     errors: {},
                     delivery_order: <?= json_encode($delivery_order); ?>,
                     parts: <?= json_encode($parts); ?>,
                     jumlah_amount_delivery_order: 0,
                     jumlah_item_delivery_order: 0,
                     jumlah_pcs_delivery_order: 0,
                     jumlah_amount_sales_order: 0,
                     jumlah_item_sales_order: 0,
                     jumlah_pcs_sales_order: 0,
                   },
                   methods: {
                     get_info_order: function(){
                        this.loading = true;
                        axios.get('h3/h3_md_create_do_sales_order/get_info_order', {
                           params: {
                              id_sales_order: this.delivery_order.id_sales_order
                           }
                        })
                        .then(function(res){
                           data = res.data;
                           app.jumlah_amount_delivery_order = data.jumlah_amount_delivery_order;
                           app.jumlah_item_delivery_order = data.jumlah_item_delivery_order;
                           app.jumlah_pcs_delivery_order = data.jumlah_pcs_delivery_order;
                           app.jumlah_amount_sales_order = data.jumlah_amount_sales_order;
                           app.jumlah_item_sales_order = data.jumlah_item_sales_order;
                           app.jumlah_pcs_sales_order = data.jumlah_pcs_sales_order;
                        })
                        .catch(function(err){
                           toastr.error(err);
                        })
                        .then(function(){ app.loading = false; });
                     },
                     sub_total: function(part) {
                        harga_setelah_diskon = part.harga_jual;

                        if(part.tipe_diskon_satuan_dealer == 'Rupiah'){
                           harga_setelah_diskon = part.harga_jual - part.diskon_satuan_dealer;
                        }else if(part.tipe_diskon_satuan_dealer == 'Persen'){
                           diskon = (part.diskon_satuan_dealer/100) * part.harga_jual;
                           harga_setelah_diskon = part.harga_jual - diskon;
                        }

                        if(part.tipe_diskon_campaign == 'Rupiah'){
                           harga_setelah_diskon = harga_setelah_diskon - part.diskon_campaign;
                        }else if(part.tipe_diskon_campaign == 'Persen'){
                           if(part.jenis_diskon_campaign == 'Additional'){
                              diskon = (part.diskon_campaign/100) * harga_setelah_diskon;
                              harga_setelah_diskon = harga_setelah_diskon - diskon;
                           }else if(part.jenis_diskon_campaign == 'Non Additional'){
                              diskon = (part.diskon_campaign/100) * part.harga_jual;
                              harga_setelah_diskon = harga_setelah_diskon - diskon;
                           }
                        }

                        if(harga_setelah_diskon < 0){
                           harga_setelah_diskon = 0;
                        }

                        return (part.qty_do * harga_setelah_diskon);
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
                     this.get_info_order();
                   },
                   computed: {
                     total: function(){
                        sub_total_fn = this.sub_total;
                        total = 0;
                        for(part of this.parts){
                           sub_total = sub_total_fn(part);
                           total += sub_total;
                        }
                        return total;
                     },
                     sisa_plafon: function(){
                        return this.sales_order.plafon - this.sales_order.plafon_yang_dipakai;
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
                        colspan = 0;
                        if(this.kategori_sim_part){
                        colspan = 7;
                        return;
                        }
                  
                        if(this.produk_parts){
                        colspan = 8;
                        }
                  
                        if(this.produk_oli){
                        colspan = 8;
                        }
                  
                        if (this.sales_order.created_by_md) {
                        colspan += 1;
                        }
                        return colspan;
                     }
                  }
               });
            </script>
         </section>
   </body>
</html>