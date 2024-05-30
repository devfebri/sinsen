<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Delivery Order</title>
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
         <div id='app' class="box box-default" style='border-top: 0; border-bottom: 0;'>
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
                    <input type="text" readonly class="form-control" v-model='do_sales_order.tanggal_do'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.nama_dealer'>                    
                  </div>  
                </div>    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.id_do_sales_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.kode_dealer'>                    
                  </div>      
                </div>      
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.tanggal_so'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.alamat'>                    
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.id_sales_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon'></vue-numeric>                
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">TOP</label>
                  <div class="col-sm-4">                    
                    <input v-if='do_sales_order.top != "" && do_sales_order.top != null' type="text" readonly class="form-control" :value='moment(do_sales_order.top).format("DD/MM/YYYY")'/>
                    <input v-if='do_sales_order.top == "" || do_sales_order.top == null' type="text" readonly class="form-control" value='-'/>             
                  </div>                                
                  <label class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='sisa_plafon'></vue-numeric>                
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Name Salesman</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.nama_salesman'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon_booking'></vue-numeric>                
                  </div>      
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.po_type'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.kategori_po'>                    
                  </div>      
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.status'>                    
                  </div>                                
                  <div v-if='do_sales_order.status == "Rejected"'>
                    <label class="col-sm-2 control-label">Alasan Reject</label>
                    <div class="col-sm-4">                    
                      <input type="text" readonly class="form-control" v-model='do_sales_order.alasan_reject'>                    
                    </div>  
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr class='bg-blue-gradient'>                                      
                          <th width='3%'>No.</th>              
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th v-if='kategori_kpb'>Tipe Kendaraan</th>              
                          <th>HET</th>              
                          <th>Qty Supply</th>
                          <th width='10%'>Diskon Satuan Dealer</th>
                          <th width='10%'>Diskon Campaign</th>
                          <th class='text-center' width='10%'>Harga Setelah Diskon</th>
                          <th class='text-center' width='10%'>Amount</th>
                          <th class='text-center' width='10%'>Harga Beli</th>
                          <th class='text-center' width='10%'>Selisih</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-top">{{ index + 1 }}.</td>                       
                          <td class="align-top">{{ part.id_part }}</td>                       
                          <td class="align-top">{{ part.nama_part }}</td>                       
                          <td v-if='kategori_kpb' class="align-top">{{ part.id_tipe_kendaraan }}</td>                       
                          <td class="align-top">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.harga"/>
                          </td>
                          <td class="align-top">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_supply"/>
                          </td>
                          <td class="align-top">
                           <vue-numeric read-only v-model="part.diskon_satuan_dealer" separator='.' :currency='get_currency_symbol(part.tipe_diskon_satuan_dealer)' :currency-symbol-position='get_currency_position(part.tipe_diskon_satuan_dealer)'/>
                          </td> 
                          <td class="align-top">
                           <vue-numeric read-only v-model="part.diskon_campaign" separator='.' :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)'/>
                          </td> 
                          <td class="align-top text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part)"/>
                          </td>   
                          <td class="align-top text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="amount(part)"/>
                          </td>  
                          <td class="align-top text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.harga_beli"/>
                          </td> 
                          <td class="align-top text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part) - part.harga_beli"/>
                          </td>    
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
                            <vue-numeric :read-only="do_sales_order.check_diskon_insentif == 0" class="form-control" separator="." v-model="do_sales_order.diskon_insentif" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td :colspan='total_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="do_sales_order.check_diskon_cashback == 0" class="form-control" separator="." v-model="do_sales_order.diskon_cashback" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td :colspan='total_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Total Diskon</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_diskon" currency='Rp'/>
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
                <table style='margin-top: 20px;' class="table table-compact">
                  <tr class='bg-blue-gradient'>
                    <td>No.</td>
                    <td>No Faktur</td>
                    <td>Tanggal Faktur</td>
                    <td>Tanggal Jatuh Tempo</td>
                    <td>Nominal</td>
                    <td>Status Pembayaran</td>
                  </tr>
                  <tr v-if='monitoring_piutang.length > 0' v-for='(piutang, index) of monitoring_piutang'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ piutang.referensi }}</td>
                    <td>{{ piutang.tanggal_transaksi }}</td>
                    <td>{{ piutang.tanggal_jatuh_tempo }}</td>
                    <td>
                      <vue-numeric :read-only="true" separator="." v-model="piutang.sisa_piutang" currency='Rp'/>
                    </td>
                    <td @click.prevent='open_status_pembayaran(piutang.referensi)'>
                      <ul v-if='piutang.list_bg.length > 0' class='no-margin'>
                        <li v-for='bg of piutang.list_bg'>{{ bg.nomor_bg }}</li>
                      </ul>
                    </td>
                  </tr>
                  <tr v-if='monitoring_piutang.length < 1'>
                    <td class='text-center' colspan='6'>Tidak ada data</td>
                  </tr>
                </table>
                <?php $this->load->view('modal/h3_md_open_status_pembayaran_piutang_pada_do'); ?>
              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            loading: false,
            do_sales_order: <?= json_encode($do_sales_order) ?>,
            parts: <?= json_encode($do_sales_order_parts) ?>,
            monitoring_piutang: <?= json_encode($monitoring_piutang) ?>,
          },
          methods: {
            hitung_dpp: function(part){
              if(part.include_ppn == 1){
                return part.harga/1.1;
              }
              return part.harga;
            },
            harga_setelah_diskon: function(part){
              return part.harga -
              this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, part.harga) - 
              this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga);
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
              return this.harga_setelah_diskon(part) * part.qty_supply
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
            open_status_pembayaran: function(referensi){
              $('#referensi_open_status_pembayaran').val(referensi);
              h3_md_open_status_pembayaran_piutang_pada_do_datatable.draw();
              $('#h3_md_open_status_pembayaran_piutang_pada_do').modal('show');
            }
          },
          computed: {
            kategori_kpb: function(){
              return this.do_sales_order.kategori_po == 'KPB';
            },
            sub_total_colspan: function(){
              colspan = 8;
              if(this.kategori_kpb){
                colspan += 1;
              }

              return colspan;
            },
            insentif_colspan: function(){
              colspan = 4;
              if(this.kategori_kpb){
                colspan += 1;
              }

              return colspan;
            },
            total_colspan: function(){
              colspan = 7;
              if(this.kategori_kpb){
                colspan += 1;
              }

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
            total_diskon: function(){
              return this.do_sales_order.diskon_insentif + (this.do_sales_order.diskon_cashback + this.do_sales_order.diskon_cashback_otomatis);

            },
            total: function(){
              return this.sub_total - this.total_diskon;
            },
            sisa_plafon: function(){
              return this.do_sales_order.plafon - this.do_sales_order.plafon_booking;
            }
          }
        });
    </script>
         </section>
   </body>
</html>