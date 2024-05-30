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
                                 <label class="col-sm-2 control-label">Nama Customer</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="surat_pengantar.nama_dealer" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Nama Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="surat_pengantar.nama_ekspedisi" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">No Plat</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="surat_pengantar.no_plat" />
                                 </div>
                              </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">
                                    <table id="table" class="table table-condensed table-responsive">
                                       <thead>
                                          <tr class='bg-blue-gradient'>
                                             <th width='3%'>No.</th>
                                             <th>No. Packing Sheet</th>
                                             <th>Tgl. Packing Sheet</th>
                                             <th>No. DO</th>
                                             <th>Tipe PO</th>
                                             <th>Jumlah Koli</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr v-if='items.length > 0' v-for="(item, index) in items">
                                             <td class="align-middle">{{ index + 1 }}.</td>
                                             <td class="align-middle">{{ item.id_packing_sheet }}</td>
                                             <td class="align-middle">{{ item.tgl_packing_sheet }}</td>
                                             <td class="align-middle">{{ item.id_do_sales_order }}</td>
                                             <td class="align-middle">{{ item.po_type }}</td>
                                             <td class="align-middle">{{ item.jumlah_koli }}</td>
                                          </tr>
                                          <tr v-if="items.length < 1">
                                             <td class="text-center" colspan="4">Tidak ada data.</td>
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
                     surat_pengantar: <?= json_encode($surat_pengantar); ?>,
                     items: <?= json_encode($items); ?>,
                  },
                  methods: {
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
                     }
                  },
               });
            </script>
         </section>
   </body>
</html>