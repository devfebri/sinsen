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
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
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
        <div v-if='validation_error.length > 0' class="alert alert-warning alert-dismissible">
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
        </div>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File PVTM</label>
                  <div class="col-sm-4">                    
                    <input class="form-control" @change='on_file_change()' ref='file' type='file' accept=".pvtm,.PVTM">
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
                window.location = 'h3/<?= $isi ?>';
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
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/import">
            <button class="btn bg-blue btn-flat margin">Import</button>
          </a>
          <a href="h3/<?= $isi ?>/export">
            <button class="btn bg-green btn-flat margin">Download .csv</button></a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?> 
        <table id="pvtm" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Tipe Produksi</th>              
              <th>Deksripsi</th>                
              <th>Tipe Motor</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          pvtm = $('#pvtm').DataTable({
            processing: true,
            serverSide: true,
            languange: {
              infoFiltered: ''
            },
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/pvtm') ?>",
                dataSrc: "data",
                type: "POST"
            },
            columns: [
                { data: null, orderable: false}, 
                { data: 'no_part' }, 
                { data: 'nama_part' }, 
                { data: 'tipe_marketing' }, 
                { data: 'deskripsi' }, 
                { data: 'tipe_motor' }, 
            ],
          });
          pvtm.on('draw.dt', function() {
            var info = pvtm.page.info();
            pvtm.column(0, {
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
