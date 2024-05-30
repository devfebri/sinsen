<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Laporan Penerimaan Barang</title>
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
                                 <label class="col-sm-2 control-label">Nomor Penerimaan</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.no_penerimaan_barang" />
                                 </div>
                                 <label class="col-sm-2 control-label">Tanggal Penerimaan</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.tanggal_penerimaan" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Nama Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.vendor_name" />
                                 </div>
                                 <label class="col-sm-2 control-label">Produk</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.produk" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">No. Surat Jalan Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.no_surat_jalan_ekspedisi" />
                                 </div>
                                 <label class="col-sm-2 control-label">Tanggal SJ Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.tgl_surat_jalan_ekspedisi" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">No. Plat</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.no_plat" />
                                 </div>
                                 <label class="col-sm-2 control-label">Berat / Truk</label>
                                 <div class="col-sm-4">
                                    <vue-numeric disabled class="form-control" precision='2' v-model='header.berat_truk'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Nama Driver</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.nama_driver" />
                                 </div>
                                 <label class="col-sm-2 control-label">Harga Satuan</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" disabled v-model='header.harga_ongkos_angkut_part' separator='.' currency='Rp '></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Type Mobil</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.type_mobil" />
                                 </div>
                                 <label class="col-sm-2 control-label">Total Harga</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class="form-control" disabled v-model='total_harga' separator='.' currency='Rp '></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Status</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.status" />
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
                     header: <?= json_encode($header); ?>,
                  },
                  computed: {
                     total_harga: function(){
                        return this.header.harga_ongkos_angkut_part * (this.header.berat_truk/this.header.per_satuan_ongkos_angkut_part);
                     },
                  }
               });
            </script>
         </section>
   </body>
</html>