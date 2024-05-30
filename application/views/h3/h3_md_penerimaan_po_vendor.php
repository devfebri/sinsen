<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form= 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">                  
                    <label class="col-sm-2 control-label">No Surat Jalan Ekspedisi</label>
                    <div v-bind:class="{ 'has-error': error_exist('surat_jalan_ekspedisi') }" class="col-sm-4">
                      <input :readonly="mode == 'detail'"  v-model="penerimaan_po_vendor.surat_jalan_ekspedisi" type="text" class="form-control">
                    <small v-if="error_exist('surat_jalan_ekspedisi')" class="form-text text-danger">{{ get_error('surat_jalan_ekspedisi') }}</small>
                    </div>       
                    <label class="col-sm-2 control-label">Tgl Surat Jalan Ekspedisi</label>
                    <div v-bind:class="{ 'has-error': error_exist('tgl_surat_jalan_ekspedisi') }" class="col-sm-4">
                      <date-picker :disabled='mode == "detail"' @update-date='tgl_surat_jalan_ekspedisi_datepicker_change' class='form-control' readonly :config='config' v-model='penerimaan_po_vendor.tgl_surat_jalan_ekspedisi'></date-picker>
                      <small v-if="error_exist('tgl_surat_jalan_ekspedisi')" class="form-text text-danger">{{ get_error('tgl_surat_jalan_ekspedisi') }}</small>
                    </div>  
                  </div>
                  <div class="form-group">                  
                    <label class="col-sm-2 control-label">No PO</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_po_vendor') }" class="col-sm-4">
                      <div class="input-group">
                        <input v-model="penerimaan_po_vendor.id_po_vendor" type="text" class="form-control" readonly>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_po_vendor_penerimaan_part_vendor'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_po_vendor')" class="form-text text-danger">{{ get_error('id_po_vendor') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_po_vendor_penerimaan_part_vendor'); ?>
                    <script>
                      function pilih_po_vendor(po_vendor) {
                        app.penerimaan_po_vendor.id_po_vendor = po_vendor.id_po_vendor;
                      }
                    </script>
                    <label class="col-sm-2 control-label">No Plat</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_plat') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly v-model="penerimaan_po_vendor.no_plat" type="text" class="form-control">
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_supir_penerimaan_part_vendor'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('no_plat')" class="form-text text-danger">{{ get_error('no_plat') }}</small>
                    </div>    
                    <?php $this->load->view('modal/h3_md_supir_penerimaan_part_vendor'); ?>
                    <script>
                      function pilih_supir(data) {
                        app.penerimaan_po_vendor.nama_driver = data.nama_supir;
                        app.penerimaan_po_vendor.no_plat = data.no_polisi;
                        app.penerimaan_po_vendor.type_mobil = data.type_mobil;

                        app.get_harga_ekspedisi();
                      }
                    </script>
                  </div>
                  <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Berat/Truk</label>
                    <div class="col-sm-4">
                      <vue-numeric :disabled='mode == "detail"' precision='2' class='form-control' v-model='penerimaan_po_vendor.berat_truk'></vue-numeric>
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
                    <div v-bind:class="{ 'has-error': error_exist('id_ekspedisi') }" class="col-sm-4">
                      <div class="input-group">
                        <input v-model="penerimaan_po_vendor.nama_ekspedisi" type="text" class="form-control" readonly>
                        <div class="input-group-btn"> 
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_penerimaan_part_vendor'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_ekspedisi')" class="form-text text-danger">{{ get_error('id_ekspedisi') }}</small>
                    </div>                   
                    <label class="col-sm-2 control-label">Nama Driver</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_driver') }" class="col-sm-4">
                      <input readonly v-model="penerimaan_po_vendor.nama_driver" type="text" class="form-control">
                      <small v-if="error_exist('nama_driver')" class="form-text text-danger">{{ get_error('nama_driver') }}</small>
                    </div>       
                  </div>
                  <?php $this->load->view('modal/h3_md_ekspedisi_penerimaan_part_vendor'); ?>
                  <script>
                    function pilih_ekspedisi(data) {
                      app.penerimaan_po_vendor.id_ekspedisi = data.id;
                      app.penerimaan_po_vendor.nama_ekspedisi = data.nama_ekspedisi;
                    }
                  </script>
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
                              <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                            </td>            
                            <td class="align-middle">
                              <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_telah_diterima"></vue-numeric>
                            </td>      
                            <td class="align-middle">
                              <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :max='part.sisa_penerimaan' :empty-value="1" v-model="part.qty_diterima"></vue-numeric>
                            </td>  
                            <td class="align-middle">
                              <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="get_qty_lebih(part)"></vue-numeric>
                            </td>  
                            <td class="align-middle">
                              <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="get_qty_kurang(part)"></vue-numeric>
                            </td> 
                            <td class="align-middle">
                              <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.kapasitas_tersedia"></vue-numeric>
                            </td> 
                            <td class="align-middle">
                              <input readonly type="text" class="form-control" @click.prevent='open_lokasi(index)' v-model='part.kode_lokasi_rak'>
                            </td> 
                            <td class="align-middle">
                              <input :readonly="mode == 'detail'" v-model="part.keterangan" type="text" class="form-control"/>
                            </td>                             
                          </tr>
                          <tr v-if="parts.length < 1">
                            <td class="text-center" colspan="9">Belum ada part</td>
                          </tr>
                        </tbody>                    
                      </table>
                    </div>
                  </div>   
                <?php $this->load->view('modal/h3_md_lokasi_penerimaan_part_vendor'); ?>
                <script>
                  function pilih_lokasi(data){
                    app.parts[app.index_part].id_lokasi_rak = data.id;
                    app.parts[app.index_part].kode_lokasi_rak = data.kode_lokasi_rak;
                    app.parts[app.index_part].kapasitas_tersedia = data.kapasitas_tersedia;
                  }
                </script>
                <?php $this->load->view('modal/h3_md_view_stock_lokasi_penerimaan_part_vendor'); ?>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Save</button>
                  <button v-if="mode == 'edit'" class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && penerimaan_po_vendor.status == "Open"' class="btn btn-sm btn-flat btn-warning" :href="'h3/<?= $isi ?>/edit?id_penerimaan_po_vendor=' + penerimaan_po_vendor.id_penerimaan_po_vendor">Edit</a>
                </div>
                <div class="col-sm-4 no-padding text-center">
                  <button v-if='mode == "detail" && penerimaan_po_vendor.status == "Open"' class="btn btn-sm btn-flat btn-success" @click.prevent='proses'>Proses</button>
                  <button v-if='mode == "detail" && penerimaan_po_vendor.status == "Open"' class="btn btn-sm btn-flat btn-danger" data-toggle='modal' data-target='#cancel_modal' type='button'>Cancel</button>
                  <div id="cancel_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title text-left" id="myModalLabel">Alasan Cancel</h4>
                              </div>
                              <div class="modal-body">
                              <div class="form-group">
                                  <div class="col-sm-12">
                                  <textarea class="form-control" id="alasan_cancel"></textarea>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-sm-12">
                                  <button @click.prevent='cancel' class="btn btn-flat btn-sm btn-primary" data-dismiss="modal">Submit</button>
                                  </div>
                              </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="col-sm-4 no-padding text-right">
                  <a v-if='mode == "detail" && penerimaan_po_vendor.status == "Processed"' target='_blank' class='btn btn-sm btn-flat btn-info' :href="'h3/h3_md_penerimaan_po_vendor/cetak?id_penerimaan_po_vendor=' + penerimaan_po_vendor.id_penerimaan_po_vendor">Cetak</a>
                  <a v-if='mode == "detail" && penerimaan_po_vendor.status == "Processed"' target='_blank' class='btn btn-sm btn-flat btn-info' :href="'h3/h3_md_penerimaan_po_vendor/cetak?id_penerimaan_po_vendor=' + penerimaan_po_vendor.id_penerimaan_po_vendor + '&dengan_harga=true'">Cetak (Dengan Harga)</a>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            penerimaan_po_vendor: <?= json_encode($penerimaan_po_vendor) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            penerimaan_po_vendor: {
              id_po_vendor: '',
              surat_jalan_ekspedisi: '',
              tgl_surat_jalan_ekspedisi: '',
              no_plat: '',
              nama_driver: '',
              id_ekspedisi: '',
              nama_ekspedisi: '',
              type_mobil: '',
              harga_ongkos_angkut_part: 0,
              jenis_ongkos_angkut_part: '',
              per_satuan_ongkos_angkut_part: 0,
              berat_truk: 0,
            },
            parts: [],
            <?php endif; ?>
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
              todayBtn: 'linked',
              todayHighlight: true,
            },
          },
          methods: {
            <?= $form ?>: function(){
              if(this.part_tanpa_lokasi.length > 0){
                toastr.warning('Terdapat penerimaan part yang tidak memiliki lokasi.');
                return;
              }

              parts_melebihi_kapasitas_tersedia = this.parts_melebihi_kapasitas_tersedia;
              if(parts_melebihi_kapasitas_tersedia.length > 0){
                toastr.warning('Terdapat penerimaan part yang melebihi kapasitas tersedia untuk lokasi rak ' + parts_melebihi_kapasitas_tersedia[0].kode_lokasi_rak);
                return;
              }

              if(this.parts_melebihi_kapasitas_tersedia_per_part.length > 0){
                toastr.warning('Terdapat penerimaan part yang melebihi kapasitas tersedia pada kode part ' + parts_melebihi_kapasitas_tersedia_per_part[0].id_part);
                return;
              }

              this.loading = true;
              post = _.pick(this.penerimaan_po_vendor, [
                'surat_jalan_ekspedisi', 'no_plat', 'nama_driver', 'id_po_vendor', 'id_ekspedisi', 'tgl_surat_jalan_ekspedisi',
                'type_mobil','harga_ongkos_angkut_part','jenis_ongkos_angkut_part','per_satuan_ongkos_angkut_part','berat_truk',
              ]);
              post.total_harga_angkut = this.total_harga_angkut;
              if(this.mode == 'edit'){
                post.id_penerimaan_po_vendor = this.penerimaan_po_vendor.id_penerimaan_po_vendor;
              }
              post.parts = this.parts;
              
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function (res) {
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function (err) {
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
                app.loading = false;
              });
            },
            proses: _.throttle(function(){
              confirmed = confirm('Apakah anda yakin ingin memproses penerimaan ini? Aksi ini akan mempengaruhi stok gudang.');
              if(!confirmed) return;

              parts_melebihi_kapasitas_tersedia = this.parts_melebihi_kapasitas_tersedia;
              if(parts_melebihi_kapasitas_tersedia.length > 0){
                toastr.warning('Terdapat penerimaan part yang melebihi kapasitas tersedia untuk lokasi rak ' + parts_melebihi_kapasitas_tersedia[0].kode_lokasi_rak);
                return;
              }
              
              this.loading = true;
              axios.get('h3/h3_md_penerimaan_po_vendor/proses', {
                params: {
                  id_penerimaan_po_vendor: this.penerimaan_po_vendor.id_penerimaan_po_vendor,
                  id_po_vendor: this.penerimaan_po_vendor.id_po_vendor,
                }
              })
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                toastr.error(data.message);
                app.loading = false;
              });
            }, 500),
            cancel: function(){
              this.loading = true;
              axios.get('h3/h3_md_penerimaan_po_vendor/cancel', {
                params: {
                  id_penerimaan_po_vendor: this.penerimaan_po_vendor.id_penerimaan_po_vendor,
                  alasan_cancel: $('#alasan_cancel').val()
                }
              })
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                toastr.error(data.message);

                app.loading = false;
              });
            },
            get_po_vendor_parts: function(){
              this.loading = true;
              axios.get('h3/h3_md_penerimaan_po_vendor/get_po_vendor_parts', {
                params: {
                  id_po_vendor: this.penerimaan_po_vendor.id_po_vendor
                }
              }).then(function(res){
                app.parts = res.data;
              }).catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            open_lokasi: function(index){
              if(this.mode == "detail") return; 

              this.index_part = index;
              h3_md_lokasi_penerimaan_part_vendor_datatable.draw();
              $('#h3_md_lokasi_penerimaan_part_vendor').modal('show');
            },
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
            get_harga_ekspedisi: function(){
              axios.get('h3/<?= $isi ?>/harga_ekspedisi', {
                params: {
                  type_mobil: this.penerimaan_po_vendor.type_mobil,
                  id_ekspedisi: this.penerimaan_po_vendor.id_ekspedisi,
                }
              })
              .then(function (res) {
                data = res.data;

                app.penerimaan_po_vendor.harga_ongkos_angkut_part = data.harga_ongkos_angkut_part;
                app.penerimaan_po_vendor.jenis_ongkos_angkut_part = data.jenis_ongkos_angkut_part;
                app.penerimaan_po_vendor.per_satuan_ongkos_angkut_part = data.per_satuan_ongkos_angkut_part;
              })
              .catch(function(err){
                toastr.error(err);
              });
            },
            ppnPerPart: function(part) {
              return (10 / 100) * part.harga ;
            },
            subTotal: function(part) {
              return (part.qty_order * part.harga ) + this.ppnPerPart(part);
            },
            hapusPart: function(index) {
              this.parts.splice(index, 1);
            },
            parts_dan_kuantitas_lokasi_yang_digunakan: function(part){
              return _.chain(app.parts)
              .filter(function(part){
                return part.setting_per_part == false;
              })
              .groupBy('id_lokasi_rak')
              .map(function(items, id_lokasi_rak){
                  return {
                      id_lokasi_rak: id_lokasi_rak,
                      kode_lokasi_rak: items[0].kode_lokasi_rak,
                      kapasitas_tersedia: items[0].kapasitas_tersedia,
                      parts: items,
                      kapasitas_yang_diperlukan: _.chain(items)
                      .sumBy(function(item){
                          return item.qty_diterima;
                      })
                      .value(),
                  };
              })
              .value();
            },
            tgl_surat_jalan_ekspedisi_datepicker_change: function(date){
              this.penerimaan_po_vendor.tgl_surat_jalan_ekspedisi = date.format('yyyy-mm-dd');
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          watch: {
            'penerimaan_po_vendor.id_po_vendor' : function(){
              this.get_po_vendor_parts();
            },
            'penerimaan_po_vendor.id_ekspedisi': function() {
              h3_md_supir_penerimaan_part_vendor_datatable.draw();
            }
          },
          computed: {
            total_harga_angkut: function(){
              return this.penerimaan_po_vendor.harga_ongkos_angkut_part * (this.penerimaan_po_vendor.berat_truk/this.penerimaan_po_vendor.per_satuan_ongkos_angkut_part);
            },
            part_tanpa_lokasi: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return part.qty_diterima > 0 && (part.id_lokasi_rak == '' || part.id_lokasi_rak == null);
              })
              .value();
            },
            parts_melebihi_kapasitas_tersedia: function(){
              parts_dan_kuantitas_lokasi_yang_digunakan = this.parts_dan_kuantitas_lokasi_yang_digunakan();
              return _.chain(parts_dan_kuantitas_lokasi_yang_digunakan)
              .filter(function(part){
                  return parseInt(part.kapasitas_yang_diperlukan) > parseInt(part.kapasitas_tersedia);
              })
              .value();
            },
            parts_melebihi_kapasitas_tersedia_per_part: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return part.setting_per_part;
              })
              .filter(function(part){
                  return parseInt(part.qty_diterima) > parseInt(part.kapasitas_tersedia);
              })
              .value();
            },
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="penerimaan_po_vendor" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tanggal</th>              
              <th>No. Penerimaan PO Vendor</th>              
              <th>No. PO Vendor</th>              
              <th>Nama Vendor</th>              
              <th>Tanggal SJ Ekspedisi</th>              
              <th>No. SJ</th>              
              <th>Nama Ekspedisi</th>              
              <th>No. Polisi Ekspedisi</th>              
              <th>Status</th>              
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          penerimaan_po_vendor = $('#penerimaan_po_vendor').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/penerimaan_po_vendor') ?>",
                dataSrc: "data",
                type: "POST"
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'tanggal' }, 
                { data: 'id_penerimaan_po_vendor' }, 
                { data: 'id_po_vendor' }, 
                { data: 'vendor_name' }, 
                { data: 'tgl_surat_jalan_ekspedisi' }, 
                { data: 'surat_jalan_ekspedisi' }, 
                { data: 'nama_ekspedisi' }, 
                { data: 'no_plat' }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });

          penerimaan_po_vendor.on('draw.dt', function() {
          var info = penerimaan_po_vendor.page.info();
            penerimaan_po_vendor.column(0, {
                search: 'applied',
                order: 'applied',
                page: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + info.start + ".";
            });
          });
        });
      </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>