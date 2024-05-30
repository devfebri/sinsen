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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
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

      if ($mode == 'upload') {
        $form = 'inject';
      }

      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form id="vueForm" class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Surat Jalan AHM</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control" value="<?= $psl->surat_jalan_ahm ?>">
                  </div>  
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Packing Sheet</th>              
                          <th>Nomor Karton</th>              
                          <th>Kode Part</th>              
                          <th>Nama Part</th>              
                          <th>Qty</th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(item, index) in items"> 
                          <td>{{ index + 1 }}.</td>
                          <td>{{ item.packing_sheet_number }}</td>                       
                          <td>{{ item.no_doos }}</td>                       
                          <td>{{ item.id_part }}</td>                       
                          <td>{{ item.nama_part }}</td>                       
                          <td>{{ item.packing_sheet_quantity }}</td>                       
                        </tr>
                        <tr v-if="items.length < 1">
                          <td class="text-center" colspan="15">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <?php if($mode == 'upload'): ?>
                  <button class="btn btn-flat btn-sm btn-primary" type="submit">Upload</button>
                  <?php endif; ?>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      var vueForm = new Vue({
          el: '#vueForm',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail'): ?>
            items: <?= json_encode($items) ?>,
            <?php endif; ?>
          },
        });
    </script>
    <?php endif; ?>
    <?php if($set=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/upload">
            <button class="btn bg-blue btn-flat margin">Upload</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <table id="psl" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Surat Jalan AHM</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          psl = $('#psl').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/psl') ?>",
                  dataSrc: "data",
                  type: "POST"
              },
              columns: [
                  { data: 'index', orderable: false, width: '3%' }, 
                  { data: 'surat_jalan_ahm' }, 
                  { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
              ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>

    <?php if($set == 'upload'): ?>
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
            <div v-if='packing_sheet_not_found.length > 0' class="alert alert-warning alert-dismissible">
              <button type="button" class="close" @click.prevent='packing_sheet_not_found = []' aria-hidden="true">×</button>
              <h4>
                <i class="icon fa fa-warning"></i> 
                Alert!
              </h4>
              <div class="row">
                <div class="col-sm-12">
                  <span>Terdapat Packing Sheet yang tidak ada di sistem. Mohon dilakukan pengecekan kembali, antara lain:</span>
                  <ul>
                    <li v-for='(error, index) of packing_sheet_not_found'>{{ error }}</li>
                  </ul> 
                </div>
              </div>
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">File PSL</label>
                  <div class="col-sm-4">                    
                    <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".psl,.PSL">
                  </div>  
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button :disabled='file == null' class="btn btn-flat btn-sm btn-primary" type="submit" @click.prevent='upload'>Upload</button>
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
            errors: {},
            packing_sheet_not_found: [],
            file: null
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);

              this.errors = {};
              this.packing_sheet_not_found = [];
              this.loading = true;
              axios.post('h3/<?= $isi ?>/inject', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                data = res.data;

                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else if(data.error_type == 'packing_sheet_not_complete'){
                  app.packing_sheet_not_found = data.payload;
                }else{
                  toastr.error(data.message);
                }

                app.loading = false;
                app.reset_file();
              });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            reset_file: function(){
              const input = this.$refs.file;
              input.type = 'text';
              input.type = 'file';
              this.file = null;
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
    <?php endif; ?>
  </section>
</div>