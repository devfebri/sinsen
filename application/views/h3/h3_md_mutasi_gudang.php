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
  <?= $breadcrumb; ?>
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
        $form = 'detail';
      }

      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id='app' class="box box-default">
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
                  <label class="col-sm-2 control-label">Part Number</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_part') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model="mutasi.id_part" readonly type="text" class="form-control">
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn-flat btn btn-primary" type='button' data-toggle='modal' data-target='#h3_md_parts_mutasi_gudang'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_part')" class="form-text text-danger">{{ get_error('id_part') }}</small>  
                  </div>    
                  <?php $this->load->view('modal/h3_md_parts_mutasi_gudang') ?>                           
                  <script>
                    function pilih_part_mutasi_gudang(part){
                      app.mutasi.id_part = part.id_part;
                      app.mutasi.nama_part = part.nama_part;
                    }
                  </script>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Part</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_part') }" class="col-sm-4">                    
                    <input v-model="mutasi.nama_part" readonly type="text" class="form-control">
                    <small v-if="error_exist('id_part')" class="form-text text-danger">{{ get_error('id_part') }}</small>                                
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kuantitas</label>
                  <div v-bind:class="{ 'has-error': error_exist('qty') }" class="col-sm-4">                    
                    <div v-if='mode != "detail"'>
                      <vue-numeric v-model='mutasi.qty' :max='max_qty' class='form-control' :readonly='mode == "detail"' />
                    </div>
                    <div v-if='mode == "detail"'>
                      <vue-numeric v-model='mutasi.qty' class='form-control' :readonly='mode == "detail"' />
                    </div>
                    <small v-if="error_exist('qty')" class="form-text text-danger">{{ get_error('qty') }}</small>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Gudang Awal</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_gudang_awal') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input readonly v-model='mutasi.nama_gudang_awal' type="text" class="form-control">
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_gudang_asal_mutasi_gudang'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_gudang_awal')" class="form-text text-danger">{{ get_error('id_gudang_awal') }}</small>
                  </div>  
                  <?php $this->load->view('modal/h3_md_gudang_asal_mutasi_gudang'); ?>
                  <script>
                    function pilih_gudang_asal_mutasi_gudang(data){
                      app.mutasi.id_gudang_awal = data.id;
                      app.mutasi.nama_gudang_awal = data.nama_gudang;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Gudang Tujuan</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_gudang_tujuan') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model='mutasi.nama_gudang_tujuan' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_gudang_tujuan_mutasi_gudang'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_gudang_tujuan')" class="form-text text-danger">{{ get_error('id_gudang_tujuan') }}</small>
                  </div>                               
                </div>
                <?php $this->load->view('modal/h3_md_gudang_tujuan_mutasi_gudang'); ?>
                <script>
                  function pilih_gudang_tujuan_mutasi_gudang(data){
                    app.mutasi.id_gudang_tujuan = data.id;
                    app.mutasi.nama_gudang_tujuan = data.nama_gudang;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Lokasi Asal</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_lokasi_awal') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model='mutasi.kode_lokasi_rak_awal' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_lokasi_awal_mutasi_gudang'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_lokasi_awal')" class="form-text text-danger">{{ get_error('id_lokasi_awal') }}</small>
                  </div> 
                  <?php $this->load->view('modal/h3_md_lokasi_awal_mutasi_gudang'); ?>
                  <script>
                    function pilih_lokasi_asal_mutasi_gudang(data){
                      app.mutasi.id_lokasi_awal = data.id;
                      app.mutasi.kode_lokasi_rak_awal = data.kode_lokasi_rak;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Lokasi Tujuan</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_lokasi_tujuan') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model='mutasi.kode_lokasi_rak_tujuan' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_lokasi_tujuan_mutasi_gudang'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_lokasi_tujuan')" class="form-text text-danger">{{ get_error('id_lokasi_tujuan') }}</small>
                  </div>                               
                  <?php $this->load->view('modal/h3_md_lokasi_tujuan_mutasi_gudang'); ?>
                  <script>
                    function pilih_lokasi_tujuan_mutasi_gudang(data){
                      app.mutasi.id_lokasi_tujuan = data.id;
                      app.mutasi.kode_lokasi_rak_tujuan = data.kode_lokasi_rak;
                    }
                  </script>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-flat btn-primary" @click.prevent="<?= $form ?>">Simpan</button>
                  <button v-if="mode == 'edit'" class="btn btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                </div>
              </div>
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
            errors: {},
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            mutasi: <?= json_encode($mutasi) ?>,
            <?php else: ?>
            mutasi: {
              id_part: '',
              nama_part: '',
              id_gudang_awal: '',
              nama_gudang_awal: '',
              id_lokasi_awal: '',
              kode_lokasi_rak_awal: '',
              id_gudang_tujuan: '',
              nama_gudang_tujuan: '',
              id_lokasi_tujuan: '',
              kode_lokasi_rak_tujuan: '',
              qty: 0,
            },
            <?php endif; ?>
            max_qty: 0
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.mutasi, [
                'id_mutasi_gudang', 'id_gudang_awal', 'id_lokasi_awal', 'id_gudang_tujuan', 'id_lokasi_tujuan', 'qty', 'id_part'
              ]);

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_mutasi_gudang/detail?id_mutasi_gudang=' + res.data.id_mutasi_gudang;
              }).catch(function (err) {
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
            get_max_qty_mutasi: function(){
              if(this.mutasi.id_part == '' || this.mutasi.id_lokasi_awal == '') return;

              axios.get('h3/h3_md_mutasi_gudang/get_max_qty_mutasi', {
                params: {
                  id_part: this.mutasi.id_part,
                  id_lokasi_rak_awal: this.mutasi.id_lokasi_awal
                }
              })
              .then(function(res){
                app.max_qty = res.data.max_qty;
              })
              .catch(function(err){
                toastr.error(err);
              });
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          watch: {
            'mutasi.id_part': function(){
              h3_md_gudang_asal_mutasi_gudang_datatable.draw();
              this.get_max_qty_mutasi();
            },
            'mutasi.id_gudang_awal': function(){
              h3_md_lokasi_awal_mutasi_gudang_datatable.draw();
            },
            'mutasi.id_lokasi_awal': function(){
              this.get_max_qty_mutasi();
            },
            'mutasi.id_gudang_tujuan': function(){
              h3_md_lokasi_tujuan_mutasi_gudang_datatable.draw();
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
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="mutasi_gudang" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>No. Mutasi</th>              
              <th>Tanggal Mutasi</th>              
              <th>Gudang Awal</th>              
              <th>Lokasi awal</th>              
              <th>Gudang Tujuan</th>              
              <th>Lokasi Tujuan</th>              
              <th>Kuantitas Mutasi</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          mutasi_gudang = $('#mutasi_gudang').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/mutasi_gudang') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(data){
                    data.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'id_mutasi_gudang' }, 
                { data: 'tanggal' }, 
                { data: 'gudang_awal', name: 'gudang_awal.nama_gudang' }, 
                { data: 'lokasi_awal', name: 'lokasi_awal.kokde_lokasi_rak' }, 
                { data: 'gudang_tujuan', name: 'gudang_tujuan.nama_gudang' }, 
                { data: 'lokasi_tujuan', name: 'lokasi_tujuan.kokde_lokasi_rak' }, 
                { data: 'qty' },
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });

          mutasi_gudang.on('draw.dt', function() {
            var info = mutasi_gudang.page.info();
              mutasi_gudang.column(0, {
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
