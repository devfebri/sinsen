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
        $form = 'detail';
        $disabled = 'disabled';
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
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode Claim</label>
                  <div v-bind:class="{ 'has-error': error_exist('kode_claim') }" class="col-sm-4">                    
                    <input :disabled='mode == "detail"' type="text" class="form-control" v-model='kategori_claim_c3.kode_claim'>
                    <small v-if="error_exist('kode_claim')" class="form-text text-danger">{{ get_error('kode_claim') }}</small>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Claim</label>
                  <div v-bind:class="{ 'has-error': error_exist('nama_claim') }" class="col-sm-4">                      
                    <input :disabled='mode == "detail"' type="text" class="form-control" v-model='kategori_claim_c3.nama_claim'>
                    <small v-if="error_exist('nama_claim')" class="form-text text-danger">{{ get_error('nama_claim') }}</small>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe Claim</label>
                  <div v-bind:class="{ 'has-error': error_exist('tipe_claim') }" class="col-sm-4">                    
                    <select :disabled='mode == "detail"' class="form-control" v-model='kategori_claim_c3.tipe_claim'>
                      <option value="">-Pilih-</option>
                      <option value="Kualitas">Kualitas</option>
                      <option value="Non Kualitas">Non Kualitas</option>
                      <option value="Claim Ekspedisi">Claim Ekspedisi</option>
                    </select>
                    <small v-if="error_exist('tipe_claim')" class="form-text text-danger">{{ get_error('tipe_claim') }}</small>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="no-padding col-sm-2 control-label">Claim Potong Stok AVS</label>
                  <div class="col-sm-4">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="kategori_claim_c3.claim_potong_avs" true-value='1' false-value='0'>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="no-padding col-sm-2 control-label">Active</label>
                  <div class="col-sm-4">                    
                    <input :disabled='mode == "detail"' type="checkbox" v-model="kategori_claim_c3.active" true-value='1' false-value='0'>
                  </div>                                
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" @click.prevent='<?= $form ?>' class="btn btn-sm btn-flat btn-primary">Submit</button>
                  <button v-if="mode == 'edit'" @click.prevent='<?= $form ?>' class="btn btn-sm btn-flat btn-warning">Update</button>
                  <a v-if='mode == "detail"' class="btn btn-sm btn-flat btn-warning" :href="'h3/<?= $isi ?>/edit?id=' + kategori_claim_c3.id">Edit</a>
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
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            kategori_claim_c3: <?= json_encode($kategori_claim_c3) ?>,
            <?php else: ?>
            kategori_claim_c3: {
              kode_claim: '',
              nama_claim: '',
              tipe_claim: '',
              claim_potong_avs: 0,
              active: 1
            }
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.kategori_claim_c3, [
                'id', 'kode_claim', 'nama_claim', 'tipe_claim', 'active', 'claim_potong_avs'
              ]);

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
              })
              .catch(function(err){
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
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="kategori_claim_c3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Kode Claim</th>              
              <th>Nama Claim</th>              
              <th>Tipe Claim</th>              
              <th>Active</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function(){
            kategori_claim_c3 = $('#kategori_claim_c3').DataTable({
              initComplete: function() {
                  $('#kategori_claim_c3_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                  $('#kategori_claim_c3_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                  axios.get('html/filter_md_kategori_claim_c3')
                      .then(function(res) {
                          $('#kategori_claim_c3_filter').prepend(res.data);

                          $('#filter_tipe_claim').change(function() {
                            kategori_claim_c3.draw();
                          });
                      });
              },
              processing: true,
              serverSide: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/kategori_claim_c3') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(data){
                    data.filter_tipe_claim = $('#filter_tipe_claim').val();
                  }
              },
              columns: [
                  { data: null, orderable: false, width: '3%' }, 
                  { data: 'kode_claim', width: '10%' }, 
                  { data: 'nama_claim' }, 
                  { data: 'tipe_claim' }, 
                  { 
                    data: 'active', 
                    width: '5%', 
                    className: 'text-center',
                    render: function(data){
                      if(data == 1){
                        return '<i class="glyphicon glyphicon-ok"></i>'
                      }
                      return '<i class="glyphicon glyphicon-remove"></i>';
                    }
                  }, 
                  { data: 'action', orderable: false, width: '3%', className: 'text-center' }
              ],
            });
            kategori_claim_c3.on('draw.dt', function() {
              var info = kategori_claim_c3.page.info();
              kategori_claim_c3.column(0, {
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