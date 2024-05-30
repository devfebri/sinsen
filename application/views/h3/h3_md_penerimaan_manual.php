<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script> 
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body onload="auto()">
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
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode Vendor</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">
                    <div class="input-group">
                      <input v-model="penerimaan_manual.id_vendor" type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_vendor_po_vendor'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>  
                  </div>
                  <label class="col-sm-2 control-label">Nama Vendor</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">
                    <input v-model="penerimaan_manual.vendor_name" type="text" class="form-control" readonly>
                    <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>  
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_vendor_po_vendor'); ?>
                <script>
                  function pilih_po_vendor(vendor) {
                    app.penerimaan_manual.id_vendor = vendor.id_vendor;
                    app.penerimaan_manual.vendor_name = vendor.vendor_name;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode Ekspedisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_ekspedisi') }" class="col-sm-4">
                    <div class="input-group">
                      <input v-model="penerimaan_manual.nama_ekspedisi" type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_penerimaan_manual'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_ekspedisi')" class="form-text text-danger">{{ get_error('id_ekspedisi') }}</small>  
                  </div>
                  <label class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_ekspedisi') }" class="col-sm-4">
                    <input v-model="penerimaan_manual.nama_ekspedisi" type="text" class="form-control" readonly>
                    <small v-if="error_exist('id_ekspedisi')" class="form-text text-danger">{{ get_error('id_ekspedisi') }}</small>  
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_ekspedisi_penerimaan_manual'); ?>
                <script>
                  function pilih_ekspedisi(data) {
                    app.penerimaan_manual.id_ekspedisi = data.id;
                    app.penerimaan_manual.nama_ekspedisi = data.nama_ekspedisi;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Supir</label>
                  <div v-bind:class="{ 'has-error': error_exist('nama_supir') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model="penerimaan_manual.nama_supir">
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_supir_penerimaan_manual'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('nama_supir')" class="form-text text-danger">{{ get_error('nama_supir') }}</small>  
                  </div>
                  <?php $this->load->view('modal/h3_md_supir_penerimaan_manual'); ?>
                  <script>
                    function pilih_supir(data){
                        app.penerimaan_manual.no_polisi = data.no_polisi;
                        app.penerimaan_manual.nama_supir = data.nama_supir;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Nomor Polisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_polisi') }" class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model="penerimaan_manual.no_polisi">
                    <small v-if="error_exist('no_polisi')" class="form-text text-danger">{{ get_error('no_polisi') }}</small>  
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Gudang</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_gudang') }" class="col-sm-4">
                    <div class="input-group">
                      <input v-model="penerimaan_manual.nama_gudang" type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_gudang_penerimaan_manual'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_gudang')" class="form-text text-danger">{{ get_error('id_gudang') }}</small>  
                  </div>
                  <?php $this->load->view('modal/h3_md_gudang_penerimaan_manual'); ?>
                  <script>
                    function pilih_gudang(data) {
                      app.penerimaan_manual.id_gudang = data.id;
                      app.penerimaan_manual.nama_gudang = data.nama_gudang;
                    }
                  </script>
                  <label class="col-sm-2 control-label">No. Referensi</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_referensi') }" class="col-sm-4">
                    <input :readonly='mode == "detail"' v-model="penerimaan_manual.id_referensi" type="text" class="form-control">
                    <small v-if="error_exist('id_referensi')" class="form-text text-danger">{{ get_error('id_referensi') }}</small>  
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div v-bind:class="{ 'has-error': error_exist('keterangan') }" class="col-sm-4">                    
                    <textarea rows="3" class="form-control" v-model="penerimaan_manual.keterangan" :readonly="mode == 'detail'"></textarea>
                    <small v-if="error_exist('keterangan')" class="form-text text-danger">{{ get_error('keterangan') }}</small>  
                  </div>
                  <label for="" class="col-sm-2 control-label">Tanggal Referensi</label>
                  <div v-bind:class="{ 'has-error': error_exist('tanggal_referensi') }" class="col-sm-4">
                    <input :disabled='mode == "detail"' readonly type="text" class="form-control" id='tanggal_referensi'>
                    <small v-if="error_exist('tanggal_referensi')" class="form-text text-danger">{{ get_error('tanggal_referensi') }}</small>  
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No</th>
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th width='10%'>Qty Terima</th>              
                          <th class='text-center' width='10%'>Suggest Lokasi</th>
                          <th width='10%'>Lokasi</th>
                          <th class='text-center'>Harga</th>
                          <th class='text-center'>Total Harga</th>
                          <th v-if="mode != 'detail'" width="3%"></th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_terima"/>
                          </td>            
                          <td class="align-middle text-center">
                            <span v-if='part.id_lokasi_rak_suggest == null'>-</span>
                            <input v-if='part.id_lokasi_rak_suggest != null' :readonly="true" class="form-control" v-model="part.lokasi_suggest"/>
                          </td>  
                          <td class="align-middle">
                            <input v-model='part.lokasi' type="text" class="form-control" readonly @click.prevent='pilih_lokasi_rak(index)'>
                          </td>  
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " class="form-control" separator="." :empty-value="1" v-model="part.harga"/>
                          </td>
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " class="form-control" separator="." :empty-value="1" v-model="sub_total(part)"/>
                          </td>
                          <td v-if="mode != 'detail'" class="align-middle">
                            <button class="btn btn-flat btn-danger" v-on:click.prevent="hapus_part(index)"><i class="fa fa-trash-o"></i></button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="9">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                    <?php $this->load->view('modal/h3_md_lokasi_penerimaan_manual'); ?>
                    <script>
                      function pilih_lokasi_rak_penerimaan_manual(data) {
                        app.parts[app.index_part].id_lokasi_rak = data.id;
                        app.parts[app.index_part].lokasi = data.kode_lokasi_rak;
                      }
                    </script>
                  </div>
                </div>                                                                                                                                
                <div v-if="mode != 'detail'" class="form-group">
                  <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-flat btn-primary" data-toggle="modal" data-target="#h3_md_parts_penerimaan_manual"><i class="fa fa-plus"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_parts_penerimaan_manual'); ?>
                  <script>
                    function pilih_parts_penerimaan_manual(part) {
                      app.parts.push(part);
                    }
                  </script>
                </div> 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-flat btn-primary btn-sm" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" class="btn btn-flat btn-warning btn-sm" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && penerimaan_manual.status != "Closed"' class="btn btn-flat btn-warning btn-sm" :href="'h3/<?= $isi ?>/edit?id_penerimaan_manual=' + penerimaan_manual.id_penerimaan_manual">Edit</a>
                </div>
                <div class="col-sm-6 text-right">
                  <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/cetak?id_penerimaan_manual=' + penerimaan_manual.id_penerimaan_manual" class="btn btn-sm btn-flat btn-info">Cetak</a>
                  <button v-if='mode == "detail" && penerimaan_manual.status != "Closed"' :disabled='part_tanpa_lokasi.length > 0' @click.prevent='proses' class="btn btn-flat btn-success btn-sm">Proses</button>
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
            index_part: 0,
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            penerimaan_manual: <?= json_encode($penerimaan_manual) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            penerimaan_manual: {
              id_vendor: '',
              vendor_name: '',
              id_ekspedisi: '',
              nama_ekspedisi: '',
              nama_supir: '',
              no_polisi: '',
              id_gudang: '',
              nama_gudang: '',
              id_referensi: '',
              tanggal_referensi: '',
              keterangan: '',
            },
            parts: [],
            <?php endif; ?>
          },
          mounted: function(){
            config = {
              autoclose: true,
              format: 'dd/mm/yyyy'
            };
            $(document).ready(function(){
              $('#tanggal_referensi').datepicker(config)
              .on('changeDate', function(e){
                app.penerimaan_manual.tanggal_referensi = e.format('yyyy-mm-dd');
              });
            });

            if(this.mode == "detail" || this.mode == "edit"){
              date = new Date(this.penerimaan_manual.tanggal_referensi);
              $(document).ready(function(){
                $("#tanggal_referensi").datepicker("setDate", date);
                $('#tanggal_referensi').datepicker('update');
              });
            }

          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.penerimaan_manual, [
                'id_vendor', 'id_ekspedisi', 'nama_supir', 'no_polisi', 'id_gudang', 'id_referensi', 'tanggal_referensi', 'keterangan', 'id_penerimaan_manual'
              ]);

              sub_total_fn = this.sub_total;
              post.parts = _.chain(this.parts)
              .filter(function(part){
                return part.qty_terima > 0;
              })
              .map(function(part){
                part.total_harga = sub_total_fn(part);
                return part;
              })
              .value();

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function (res) {
                window.location = 'h3/<?= $isi ?>/detail?id_penerimaan_manual=' + res.data.id_penerimaan_manual;
              })
              .catch(function (err) {
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
              })
              .then(function(){ app.loading = false; });
            },
            proses: function(){
              confirmed = confirm('Apakah anda yakin ingin memproses penerimaan manual ini? tindakan tidak dapat dibatalkan.');
              if(!confirmed) return;

              this.loading = true;
              axios.get('h3/h3_md_penerimaan_manual/proses', {
                params: {
                  id_penerimaan_manual: this.penerimaan_manual.id_penerimaan_manual
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_penerimaan_manual=' + res.data.id_penerimaan_manual;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            pilih_lokasi_rak: function(index){
              if(this.mode == "detail") return;

              this.index_part = index;
              $('#h3_md_lokasi_penerimaan_manual').modal('show');
            },
            sub_total: function(part) {
              return parseInt(part.qty_terima) * parseFloat(part.harga);
            },
            hapus_part: function(index) {
              this.parts.splice(index, 1);
            },
            get_suggest_lokasi: _.debounce(function(){
              if(this.mode == "detail" || this.parts.length < 1) return;

              post = {};
              post.id_gudang = this.penerimaan_manual.id_gudang;
              post.parts = _.chain(this.parts)
              .map(function(part){
                return _.pick(part, ['id_part', 'qty_terima']);
              })
              .value();

              axios.post('h3/<?= $isi ?>/get_suggest_lokasi', Qs.stringify(post))
              .then(function(res){
                for (part of res.data) {
                  index = _.findIndex(app.parts, function(data){ return data.id_part == part.id_part });
                  app.parts[index].id_lokasi_rak_suggest = part.id_lokasi_suggest;
                  app.parts[index].lokasi_suggest = part.lokasi_suggest;
                }
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              });
            }, 500),
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            part_tanpa_lokasi: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return part.id_lokasi_rak == null || part.id_lokasi_rak == '';
              })
              .value();
            }
          },
          watch: {
            'penerimaan_manual.id_gudang': function(){
              h3_md_lokasi_penerimaan_manual_datatable.draw();
              app.get_suggest_lokasi();
            },
            'penerimaan_manual.id_ekspedisi': function(){
              h3_md_supir_penerimaan_manual_datatable.draw();
            },
            parts: {
              deep: true,
              handler: function(){
                app.get_suggest_lokasi();
              }
            }
          },
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
          <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="penerimaan_manual" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No</th>              
              <th>Tanggal Penerimaan</th>              
              <th>No. Penerimaan</th>              
              <th>Nama Ekspedisi</th>              
              <th>Nama Vendor</th>              
              <th>Status Penerimaan</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          penerimaan_manual = $('#penerimaan_manual').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/penerimaan_manual') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'tanggal_penerimaan_manual' }, 
                { data: 'id_penerimaan_manual' }, 
                { data: 'nama_ekspedisi' }, 
                { data: 'vendor_name' }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%' }
            ],
          });

          penerimaan_manual.on('draw.dt', function() {
            var info = penerimaan_manual.page.info();
              penerimaan_manual.column(0, {
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