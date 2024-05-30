<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Packing Sheet</title>
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
                                 <label class="col-sm-2 control-label">No. Packing Sheet</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="header.packing_sheet_number" />
                                 </div>
                                 <label class="col-sm-2 control-label">Tanggal PS</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" :value='moment(header.packing_sheet_date).format("DD/MM/YYYY")'/>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <table class="table">
                                    <tr>
                                       <td width='3%'>No.</td>
                                       <td>Kode Part</td>
                                       <td>Nama Part</td>
                                       <td>Nomor Karton</td>
                                       <td>No. PO</td>
                                       <td>Jenis PO</td>
                                       <td>Tanggal PO</td>
                                       <td class='text-center'>Qty PS</td>
                                       <td class='text-center'>Qty Order</td>
                                       <td class='text-center'>Qty Back Order</td>
                                    </tr>
                                    <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                                       <td width='3%'>{{ index + 1 }}.</td>
                                       <td>{{ part.id_part }}</td>
                                       <td>{{ part.nama_part }}</td>
                                       <td>{{ part.no_doos }}</td>
                                       <td>{{ part.no_po }}</td>
                                       <td>{{ part.jenis_po }}</td>
                                       <td>{{ moment(part.tanggal_po).format("DD/MM/YYYY") }}</td>
                                       <td class='text-right'>
                                          <vue-numeric read-only separator='.' v-model='part.packing_sheet_quantity'></vue-numeric>
                                       </td>
                                       <td class='text-right'>
                                          <vue-numeric read-only separator='.' v-model='part.qty_order'></vue-numeric>
                                       </td>
                                       <td class='text-right'>
                                          <vue-numeric read-only separator='.' v-model='part.qty_back_order'></vue-numeric>
                                       </td>
                                    </tr>
                                 </table>
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
                     parts: <?= json_encode($parts); ?>,
                  },
               });
            </script>
         </section>
   </body>
</html>