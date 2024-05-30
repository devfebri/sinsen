<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>PO Logistik</title>
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
                                 <label class="col-sm-2 control-label">Dokumen NRFS ID</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.dokumen_nrfs_id" />
                                 </div>
                                 <label class="col-sm-2 control-label">Nomor Rangka</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.no_rangka" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Request ID</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.request_id" />
                                 </div>
                                 <label class="col-sm-2 control-label">Nomor Mesin</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.no_mesin" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Kode Tipe Unit</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.type_code" />
                                 </div>
                                 <label class="col-sm-2 control-label">Status Request</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.status" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Type Unit</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.deskripsi_ahm" />
                                 </div>
                                 <label class="col-sm-2 control-label">No. PO AHM</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.no_po_ahm" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Deskripsi Warna</label>
                                 <div class="col-sm-4">
                                    <input type="text" readonly class="form-control" v-model="po_logistik.deskripsi_warna" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">
                                    <table id="table" class="table table-condensed table-responsive">
                                       <thead>
                                          <tr>
                                             <th>No.</th>
                                             <th>Nomor Part</th>
                                             <th>Deskripsi Part</th>
                                             <th>Qty</th>
                                             <th class='text-center'>HET</th>
                                             <th class='text-center'>Amount</th>
                                             <th class='text-center'>No. Faktur MD</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr v-for="(part, index) in parts">
                                             <td class="align-middle">{{ index + 1 }}.</td>
                                             <td class="align-middle">{{ part.id_part }}</td>
                                             <td class="align-middle">{{ part.nama_part }}</td>
                                             <td class="align-middle">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_part"/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga" currency='Rp'/>
                                             </td>
                                             <td class="align-middle text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.amount" currency='Rp'/>
                                             </td>
                                             <td class="align-middle">{{ part.no_faktur_md }}</td>
                                          </tr>
                                          <tr v-if="parts.length > 0">
                                             <td class="text-right" colspan="5">Total</td>
                                             <td class="text-right">
                                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_amount" currency="Rp"></vue-numeric>
                                             </td>
                                             <td></td>
                                          </tr>
                                          <tr v-if="parts.length < 1">
                                             <td class="text-center" colspan="7">Belum ada part</td>
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
                     po_logistik: <?= json_encode($po_logistik); ?>,
                     parts: <?= json_encode($parts); ?>,
                  },
                  computed: {
                     total_amount: function(){
                        return _.chain(this.parts)
                        .sumBy(function(part){
                           return parseFloat(part.amount);
                        })
                        .value();
                     }
                  }
               });
            </script>
         </section>
   </body>
</html>