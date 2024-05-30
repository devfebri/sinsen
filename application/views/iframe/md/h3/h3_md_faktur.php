<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Picking List</title>
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
                                 <label class="col-sm-2 control-label">Tanggal DO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="faktur.tanggal_do" />
                                 </div>
                                 <label class="col-sm-2 control-label">Nama Customer</label>
                                 <div class="col-sm-4">
                                    <input v-model='faktur.nama_dealer' type="text" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">No. DO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="faktur.id_do_sales_order" />
                                 </div>
                                 <label class="col-sm-2 control-label">Kode Customer</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="faktur.kode_dealer_md" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Tanggal SO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="faktur.tanggal_so" />
                                 </div>
                                 <label class="col-sm-2 control-label">Alamat Customer</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="faktur.alamat" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">No. SO</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="faktur.id_sales_order" />
                                 </div>
                                 <label class="col-sm-2 control-label">Plafon</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" currency='Rp' separator='.' readonly v-model='faktur.plafon'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">TOP</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='faktur.top' readonly>
                                 </div>
                                 <label class="col-sm-2 control-label">Plafon Booking</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" currency='Rp' separator='.' readonly v-model='faktur.plafon_booking'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Nama Salesman</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='faktur.nama_salesman' readonly>
                                 </div>
                                 <label class="col-sm-2 control-label">Sisa Plafon</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" currency='Rp' separator='.' readonly v-model='faktur.sisa_plafon'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Tipe PO</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='faktur.po_type' readonly>
                                 </div>
                                 <label class="col-sm-2 control-label">Kategori PO</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='faktur.kategori_po' readonly>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Status</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='faktur.status' readonly>
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
                                             <th>Harga (DPP)</th>
                                             <th class='text-right'>Qty DO</th>
                                             <th class='text-right'>Qty DO Rev</th>
                                             <th class='text-right'>Qty Scan</th>
                                             <th class='text-right'>Qty Faktur</th>
                                             <th class='text-right'>Disc. Dealer</th>
                                             <th class='text-right'>Disc. Campaign</th>
                                             <th class='text-right'>Harga Setelah Diskon</th>
                                             <th class='text-right'>Amount</th>
                                             <th class='text-right'>Harga Beli</th>
                                             <th class='text-right'>Harga Selisih</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr v-for="(part, index) in parts">
                                             <td class="align-middle">{{ index + 1 }}.</td>
                                             <td class="align-middle">{{ part.id_part }}</td>
                                             <td class="align-middle">{{ part.nama_part }}</td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" :value='hitung_dpp(part.harga_jual, part.include_ppn)'/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_do"/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_revisi"/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_scan" />
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_faktur" />
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='convert_diskon_ke_rupiah(part.tipe_diskon_satuan_dealer, part.diskon_satuan_dealer, hitung_dpp(part.harga_jual, part.include_ppn))'/>
                                             </td> 
                                             <td class="align-middle text-right">
                                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='convert_diskon_ke_rupiah(part.tipe_diskon_campaign, part.diskon_campaign, hitung_dpp(part.harga_jual, part.include_ppn))'/>
                                             </td> 
                                             <td class="align-middle text-right">
                                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='harga_setelah_diskon(part)'/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='amount(part)'/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='part.harga_beli'/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='selisih(part)'/>
                                             </td>
                                          </tr>
                                          <tr v-if="parts.length < 1">
                                             <td class="text-center" colspan="12">Tidak ada data.</td>
                                          </tr>
                                          <tr v-if="parts.length > 0">
                                             <td class="text-right" :colspan="sub_total_colspan">Sub Total</td>
                                             <td class="text-right" colspan='3'>
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp'/>
                                             </td>
                                             </tr>
                                             <tr v-if="parts.length > 0">
                                             <td colspan='2' class='align-middle'>Total Insentif</td>
                                             <td class='align-middle'>
                                                <vue-numeric :read-only="true" class="form-control" separator="."/>
                                             </td>
                                             <td :colspan='insentif_colspan'></td>
                                             <td class="text-right align-middle" colspan="1">Diskon Insentif</td>
                                             <td class="text-right align-middle" colspan='3'>
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="faktur.diskon_insentif" currency='Rp'/>
                                             </td>
                                             </tr>
                                             <tr v-if="parts.length > 0">
                                             <td :colspan='total_colspan'></td>
                                             <td class="text-right align-middle" colspan="1">Cashback Langsung</td>
                                             <td class="text-right align-middle" colspan='3'>
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="faktur.diskon_cashback_otomatis" currency='Rp'/>
                                             </td>
                                             </tr>
                                             <tr v-if="parts.length > 0">
                                             <td :colspan='total_colspan'></td>
                                             <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                                             <td class="text-right align-middle" colspan='3'>
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="faktur.diskon_cashback" currency='Rp'/>
                                             </td>
                                             </tr>
                                             <tr v-if="parts.length > 0">
                                             <td :colspan='total_colspan'></td>
                                             <td class="text-right align-middle" colspan="1">Total</td>
                                             <td class="text-right align-middle" colspan='3'>
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency='Rp'/>
                                             </td>
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
                     faktur: <?= json_encode($faktur); ?>,
                     parts: <?= json_encode($parts); ?>,
                  },
                  methods: {
                     hitung_dpp: function(harga, include_ppn){
                        if(include_ppn == 1){
                           return parseFloat(harga) / 1.1;
                        }
                        return parseFloat(harga);
                     },
                     convert_diskon_ke_rupiah: function(tipe_diskon, diskon_value, harga){
                        if(tipe_diskon == 'Rupiah') return diskon_value;

                        diskon = (diskon_value/100) * harga;
                        return diskon;
                     },
                     harga_setelah_diskon: function(part){
                        harga_setelah_diskon = parseFloat(part.harga_jual);
                        harga_setelah_diskon -= this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, harga_setelah_diskon);

                        if(part.additional_discount == 1){
                           harga_setelah_diskon -= this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, harga_setelah_diskon);
                        }else{
                           harga_setelah_diskon -= this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_jual);
                        }

                        return harga_setelah_diskon;
                     },
                     calculate_discount: function(discount, tipe_diskon, price) {
                        if(tipe_diskon == 'Persen'){
                           if(discount == 0) return 0; 

                           return discount = (discount/100) * price;
                        }else if(tipe_diskon == 'Rupiah'){
                           return discount;
                        }
                        return 0;
                     },
                     amount: function(part) {
                        return this.harga_setelah_diskon(part) * part.qty_faktur;
                     },
                     selisih: function(part){
                        return Math.abs(this.harga_setelah_diskon(part) - part.harga_beli);
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
                  computed: {
                     kategori_kpb: function(){
                        return this.faktur.kategori_po == 'KPB';
                     },
                     sub_total_colspan: function(){
                        colspan = 8;
                        if(this.kategori_kpb){
                           colspan += 1;
                        }

                        colspan += 3;

                        return colspan;
                     },
                     sub_total: function(){
                        total = 0;
                        for (index = 0; index < this.parts.length; index++) {
                           part = this.parts[index];
                           total += this.amount(part);
                        }
                        return total;
                     },
                     insentif_colspan: function(){
                        colspan = 4;
                        if(this.kategori_kpb){
                           colspan += 1;
                        }

                        colspan += 3;

                        return colspan;
                     },
                     total_colspan: function(){
                        colspan = 7;
                        if(this.kategori_kpb){
                           colspan += 1;
                        }

                        colspan += 3;

                        return colspan;
                     },
                  }
               });
            </script>
         </section>
   </body>
</html>