<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <?= $breadcrumb ?>
</section>
<section class="content">
<?php

  if ($set=="form") {
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      }
       ?>

    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Lokasi Rak</label>
                    <div v-bind:class="{ 'has-error': error_exist('kode_lokasi_rak') }" class="col-sm-3">
                      <input :readonly='mode == "detail"' v-model='lokasi_rak.kode_lokasi_rak' type="text" class="form-control">
                      <small v-if="error_exist('kode_lokasi_rak')" class="form-text text-danger">{{ get_error('kode_lokasi_rak') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Kapasitas</label>
                    <div v-bind:class="{ 'has-error': error_exist('kapasitas') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='lokasi_rak.kapasitas' type="text" class="form-control">
                      <small v-if="error_exist('kapasitas')" class="form-text text-danger">{{ get_error('kapasitas') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Lokasi Rak</label>
                    <div v-bind:class="{ 'has-error': error_exist('deskripsi') }" class="col-sm-3">
                      <input :readonly='mode == "detail"' v-model='lokasi_rak.deskripsi' type="text" class="form-control">
                      <small v-if="error_exist('deskripsi')" class="form-text text-danger">{{ get_error('deskripsi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Gudang</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_gudang') }" class="col-sm-3">
                      <input type="text" class="form-control" readonly v-model='lokasi_rak.kode_gudang'>
                      <small v-if="error_exist('id_gudang')" class="form-text text-danger">{{ get_error('id_gudang') }}</small>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_gudang_lokasi_rak'><i class="fa fa-search"></i></button>
                    </div>
                    <?php $this->load->view('modal/h3_gudang_lokasi_rak') ?>
                    <script>
                      function pilih_gudang_lokasi_rak(data){
                        form_.lokasi_rak.id_gudang = data.id;
                        form_.lokasi_rak.kode_gudang = data.kode_gudang;
                        form_.lokasi_rak.nama_gudang = data.nama_gudang;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_gudang') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='lokasi_rak.nama_gudang' type="text" class="form-control" readonly>
                      <small v-if="error_exist('id_gudang')" class="form-text text-danger">{{ get_error('id_gudang') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Lokasi Retur</label>
                    <div v-bind:class="{ 'has-error': error_exist('lokasi_retur') }" class="col-sm-3">
                      <select :disabled='mode == "detail"' class="form-control" v-model='lokasi_rak.lokasi_retur'>
                        <option value="">-Choose-</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                      </select>
                      <small v-if="error_exist('lokasi_retur')" class="form-text text-danger">{{ get_error('lokasi_retur') }}</small>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="no-padding col-sm-2 control-label">Active</label>
                    <div class="col-sm-4">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='lokasi_rak.active'>
                    </div>
                  </div>
                  <div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-condensed">
                              <tr>
                                <td width='3%'>No.</td>
                                <td>Kode Part</td>
                                <td>Nama Part</td>
                                <td>Qty Maks</td>
                                <td v-if='mode != "detail"' width='3%'></td>
                              </tr>
                              <tr v-if='parts.length > 0' v-for='(each, index) of parts'>
                                <td width='3%'>{{ index + 1 }}.</td>
                                <td>{{ each.id_part }}</td>
                                <td>{{ each.nama_part }}</td>
                                <td width='10%'>
                                  <vue-numeric :read-only='mode == "detail"' class="form-control" separator='.' v-model='each.qty_maks'></vue-numeric>
                                </td>
                                <td v-if='mode != "detail"' width='3%'>
                                  <button class="btn btn-flat btn-danger" @click.prevent='hapus_part(index)'><i class="fa fa-trash-o"></i></button>
                                </td>
                              </tr>
                              <tr v-if='parts.length < 1'>
                                <td class='text-center' colspan='4'>Tidak ada data</td>
                              </tr>
                          </table>
                        </div>
                      </div>
                      <?php $this->load->view('modal/h3_parts_lokasi_rak') ?>
                      <script>
                        function pilih_parts_lokasi_rak(data){
                          form_.parts.push(data);
                          h3_parts_lokasi_rak_datatable.draw();
                        }
                      </script>
                  </div>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary margin" type='button' data-toggle='modal' data-target='#h3_parts_lokasi_rak'><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Perbarui</button>
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + lokasi_rak.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                    <div class="col-sm-6 no-padding text-right">
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        kosong :'',
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        errors: {},
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        lokasi_rak: <?= json_encode($lokasi_rak) ?>,
        parts: <?= json_encode($parts) ?>,
        <?php else: ?>
        lokasi_rak: {
          kode_lokasi_rak: '',
          deskripsi: '',
          kapasitas: '',
          id_gudang: '',
          kode_gudang: '',
          nama_gudang: '',
          lokasi_retur: '',
        },
        parts: []
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          this.errors = {};
          this.loading = true;

          post = {};
          if(this.mode == 'edit'){
            post.id = this.lokasi_rak.id;
            post.active = this.lokasi_rak.active;
          }
          post.kode_lokasi_rak = this.lokasi_rak.kode_lokasi_rak;
          post.kapasitas = this.lokasi_rak.kapasitas;
          post.id_gudang = this.lokasi_rak.id_gudang;
          post.lokasi_retur = this.lokasi_rak.lokasi_retur;
          post.deskripsi = this.lokasi_rak.deskripsi;

          post.parts = _.map(this.parts, function(p){
            return _.pick(p, ['id_part', 'id_part_int', 'qty_maks']);
          });

          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(data.message);
            }
            form_.loading = false;
          });
        },
        hapus_part: function (index){
          this.parts.splice(index, 1);
          h3_parts_lokasi_rak_datatable.draw();
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <a href="h3/<?= $isi ?>/import">
            <button class="btn bg-blue btn-flat margin">Import Lokasi Rak</button>
          </a>
          <a href="h3/<?= $isi ?>/import_lokasi_parts">
            <button class="btn bg-blue btn-flat margin">Import Lokasi Rak Parts</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="master_lokasi_rak" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Lokasi Rak</th>
              <th>Deskripsi Lokasi Rak</th>
              <th>Nama Gudang</th>
              <th>Kapasitas</th>
              <th>Kapasitas Terpakai</th>
              <th>Kapasitas Tersedia</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_lokasi_rak = $('#master_lokasi_rak').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_lokasi_rak') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'kode_lokasi_rak' },
                    { data: 'deskripsi' },
                    { data: 'nama_gudang' },
                    { 
                      data: 'kapasitas',
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",");
                      }
                    },
                    { 
                      data: 'kapasitas_terpakai',
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",");
                      }
                    },
                    { 
                      data: 'kapasitas_tersedia',
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",");
                      }
                    },
                    {
                      data: 'active',
                      render: function ( data, type, row ) {
                        if(data == 1){
                          return '<i class="fa fa-check"></i>';
                        }
                        return '<i class="fa fa-close"></i>';
                      },
                      width: '3%',
                      orderable: false,
                      className: 'text-center'
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_lokasi_rak.on('draw.dt', function() {
              var info = master_lokasi_rak.page.info();
              master_lokasi_rak.column(0, {
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
    <?php }elseif($set=="upload") { ?>
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
          <!-- <div v-if='validation_error.length > 0' class="alert alert-warning alert-dismissible">
            <button type="button" class="close" @click.prevent='validation_error = []' aria-hidden="true">×</button>
            <h4>
              <i class="icon fa fa-warning"></i> 
              Alert!
            </h4>
            <ol class="">
              <li v-for='(each, index) of validation_error.slice(0, 10)'>
                {{ each.message }}
                <ul>
                  <li v-for='(error, index) of each.errors'>{{ error }}</li>
                </ul>
              </li>
            </ol>
          </div> -->
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">                  
                    <label class="col-sm-2 control-label">Upload Lokasi Rak</label>
                    <div class="col-sm-4">                    
                      <input class="form-control" @change='on_file_change()' ref='file' type='file'>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12 no-padding">
                    <button :disabled='file == null' class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
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
              loading: false,
              validation_error: [],
              file: null
            },
            methods: {
              upload: function(){
                post = new FormData();
                post.append('file', this.file);

                this.errors = {};
                this.loading = true;
                axios.post('h3/<?= $isi ?>/inject', post, {
                  headers: {
                    'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                  }
                })
                .then(function(res){
                  // window.location = 'h3/<?= $isi ?>';
                })
                .catch(function(err){
                  data = err.response.data;
                  if(data.error_type == 'validation_error'){
                    app.validation_error = data.payload;
                  }else{
                    toastr.error(err);
                  }
                  app.reset_file();
                })
                .then(function(){ app.loading = false; });
              },
              on_file_change: function(){
                this.file = this.$refs.file.files[0];
              },
              reset_file: function(){
                const input = this.$refs.file;
                input.type = 'text';
                input.type = 'file';
              },
              error_exist: function(key){
                return _.get(this.errors, key) != null;
              },
              get_error: function(key){
                return _.get(this.errors, key)
              }
            }
          });
      </script>
    <?php }elseif($set=="upload_lokasi_parts") {?>
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
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">                  
                    <label class="col-sm-2 control-label">Upload Lokasi Parts</label>
                    <div class="col-sm-4">                    
                      <input class="form-control" @change='on_file_change()' ref='file' type='file'>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12 no-padding">
                    <button :disabled='file == null' class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
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
              loading: false,
              validation_error: [],
              file: null
            },
            methods: {
              upload: function(){
                post = new FormData();
                post.append('file', this.file);

                this.errors = {};
                this.loading = true;
                axios.post('h3/<?= $isi ?>/inject_lokasi_parts', post, {
                  headers: {
                    'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                  }
                })
                .then(function(res){
                  // window.location = 'h3/<?= $isi ?>';
                })
                .catch(function(err){
                  data = err.response.data;
                  if(data.error_type == 'validation_error'){
                    app.validation_error = data.payload;
                  }else{
                    toastr.error(err);
                  }
                  app.reset_file();
                })
                .then(function(){ app.loading = false; });
              },
              on_file_change: function(){
                this.file = this.$refs.file.files[0];
              },
              reset_file: function(){
                const input = this.$refs.file;
                input.type = 'text';
                input.type = 'file';
              },
              error_exist: function(key){
                return _.get(this.errors, key) != null;
              },
              get_error: function(key){
                return _.get(this.errors, key)
              }
            }
          });
      </script>
    <?php }?>  
  </section>
</div>