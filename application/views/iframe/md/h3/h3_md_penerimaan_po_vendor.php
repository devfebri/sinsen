<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Penerimaan PO Vendor</title>
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
                                 <label class="col-sm-2 control-label">No Surat Jalan Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input disabled v-model="penerimaan_po_vendor.surat_jalan_ekspedisi" type="text" class="form-control">
                                 </div>       
                                 <label class="col-sm-2 control-label">Tgl Surat Jalan Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input disabled v-model="penerimaan_po_vendor.tgl_surat_jalan_ekspedisi" type="text" class="form-control">
                                 </div>  
                              </div>
                              <div class="form-group">                  
                                 <label class="col-sm-2 control-label">No PO</label>
                                 <div class="col-sm-4">
                                    <input v-model="penerimaan_po_vendor.id_po_vendor" type="text" class="form-control" readonly>
                                 </div>
                                 <label class="col-sm-2 control-label">No Plat</label>
                                 <div class="col-sm-4">
                                    <input readonly v-model="penerimaan_po_vendor.no_plat" type="text" class="form-control">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="" class="col-sm-2 control-label">Berat/Truk</label>
                                 <div class="col-sm-4">
                                    <vue-numeric disabled precision='2' class='form-control' v-model='penerimaan_po_vendor.berat_truk'></vue-numeric>
                                 </div>
                                 <label for="" class="col-sm-2 control-label">Tipe Mobil</label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" v-model='penerimaan_po_vendor.type_mobil' readonly>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="" class="col-sm-2 control-label">Harga Satuan</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class='form-control' currency='Rp' separator='.' disabled v-model='penerimaan_po_vendor.harga_ongkos_angkut_part'></vue-numeric>
                                 </div>
                                 <label for="" class="col-sm-2 control-label">Total Harga</label>
                                 <div class="col-sm-4">
                                    <vue-numeric class='form-control' currency='Rp' separator='.' disabled v-model='total_harga_angkut'></vue-numeric>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Nama Ekspedisi</label>
                                 <div class="col-sm-4">
                                    <input v-model="penerimaan_po_vendor.nama_ekspedisi" type="text" class="form-control" readonly>
                                 </div>                   
                                 <label class="col-sm-2 control-label">Nama Driver</label>
                                 <div class="col-sm-4">
                                    <input readonly v-model="penerimaan_po_vendor.nama_driver" type="text" class="form-control">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">
                                    <table id="table" class="table table-hover table-responsive">
                                       <thead>
                                          <tr>
                                             <th width='3%'>No</th>              
                                             <th>Kode Part</th>              
                                             <th>Nama Part</th>              
                                             <th width="5%">Qty PO</th>
                                             <th width="5%">Qty Telah Diterima</th>
                                             <th width="5%">Qty Diterima</th>
                                             <th width="5%">Qty Lebih</th>
                                             <th width="5%">Qty Kurang</th>
                                             <th width="5%">Kapasitas Tersedia</th>
                                             <th width='10%'>Lokasi</th>
                                             <th>Keterangan</th>
                                          </tr>
                                       </thead>
                                       <tbody>            
                                          <tr v-for="(part, index) in parts"> 
                                             <td class="align-middle">{{ index + 1 }}.</td>
                                             <td class="align-middle">{{ part.id_part }}</td>                       
                                             <td class="align-middle">{{ part.nama_part }}</td>            
                                             <td class="align-middle">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                                             </td>            
                                             <td class="align-middle">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_telah_diterima"></vue-numeric>
                                             </td>      
                                             <td class="align-middle">
                                                <vue-numeric read-only class="form-control" separator="." :max='part.sisa_penerimaan' :empty-value="1" v-model="part.qty_diterima"></vue-numeric>
                                             </td>  
                                             <td class="align-middle">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="get_qty_lebih(part)"></vue-numeric>
                                             </td>  
                                             <td class="align-middle">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="get_qty_kurang(part)"></vue-numeric>
                                             </td> 
                                             <td class="align-middle">
                                                <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.kapasitas_tersedia"></vue-numeric>
                                             </td> 
                                             <td class="align-middle">
                                                <input readonly type="text" class="form-control" v-model='part.kode_lokasi_rak'>
                                             </td> 
                                             <td class="align-middle">
                                                <input readonly v-model="part.keterangan" type="text" class="form-control"/>
                                             </td>                             
                                          </tr>
                                          <tr v-if="parts.length < 1">
                                             <td class="text-center" colspan="9">Belum ada part</td>
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
                     penerimaan_po_vendor: <?= json_encode($penerimaan_po_vendor); ?>,
                     parts: <?= json_encode($parts); ?>,
                  },
                  methods: {
                     get_qty_lebih: function(part){
                        qty_telah_diterima = parseInt(part.qty_telah_diterima);
                        qty_order = parseInt(part.qty_order);

                        if(qty_telah_diterima > qty_order){
                           return qty_telah_diterima - qty_order;
                        }

                        return 0;
                     },
                     get_qty_kurang: function(part){
                        qty_telah_diterima = parseInt(part.qty_telah_diterima);
                        qty_order = parseInt(part.qty_order);

                        if(qty_telah_diterima < qty_order){
                           return qty_order - qty_telah_diterima;
                        }

                        return 0;
                     },
                  },
                  computed: {
                     total_harga_angkut: function(){
                        return this.penerimaan_po_vendor.harga_ongkos_angkut_part * (this.penerimaan_po_vendor.berat_truk/this.penerimaan_po_vendor.per_satuan_ongkos_angkut_part);
                     },
                  }
               });
            </script>
         </section>
   </body>
</html>