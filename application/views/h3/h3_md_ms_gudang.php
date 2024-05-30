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
      } ?>

<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>

<script>
  Vue.use(VueNumeric.default);
</script>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Gudang</label>
                    <div v-bind:class="{ 'has-error': error_exist('kode_gudang') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.kode_gudang' type="text" class="form-control">
                      <small v-if="error_exist('kode_gudang')" class="form-text text-danger">{{ get_error('kode_gudang') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Gudang</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_gudang') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.nama_gudang' type="text" class="form-control">
                      <small v-if="error_exist('nama_gudang')" class="form-text text-danger">{{ get_error('nama_gudang') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Gudang</label>
                    <div v-bind:class="{ 'has-error': error_exist('jenis_gudang') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='gudang.jenis_gudang'>
                        <option value="">-Choose-</option>
                        <option value="Part">Part</option>
                        <option value="Oli">Oli</option>
                      </select>
                      <small v-if="error_exist('jenis_gudang')" class="form-text text-danger">{{ get_error('jenis_gudang') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.alamat' type="text" class="form-control">
                      <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Luas Gudang</label>
                    <div v-bind:class="{ 'has-error': error_exist('luas_gudang') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.luas_gudang' type="text" class="form-control">
                      <small v-if="error_exist('luas_gudang')" class="form-text text-danger">{{ get_error('luas_gudang') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Rak</label>
                    <div v-bind:class="{ 'has-error': error_exist('jumlah_rak') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.jumlah_rak' type="text" class="form-control">
                      <small v-if="error_exist('jumlah_rak')" class="form-text text-danger">{{ get_error('jumlah_rak') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Binbox</label>
                    <div v-bind:class="{ 'has-error': error_exist('jumlah_binbox') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.jumlah_binbox' type="text" class="form-control">
                      <small v-if="error_exist('jumlah_binbox')" class="form-text text-danger">{{ get_error('jumlah_binbox') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Pallet</label>
                    <div v-bind:class="{ 'has-error': error_exist('jumlah_pallet') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' v-model='gudang.jumlah_pallet' type="text" class="form-control">
                      <small v-if="error_exist('jumlah_pallet')" class="form-text text-danger">{{ get_error('jumlah_pallet') }}</small>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="no-padding col-sm-2 control-label">Active</label>
                    <div class="col-sm-4">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='gudang.active'>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Perbarui</button>
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + gudang.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        gudang: <?= json_encode($gudang) ?>,
        <?php else: ?>
        gudang: {
          id: '',
          kode_gudang: '',
          nama_gudang: '',
          jenis_gudang: '',
          alamat: '',
          luas_gudang: '',
          jumlah_rak: '',
          jumlah_binbox: '',
          jumlah_pallet: '',
        },
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          this.errors = {};
          this.loading = true;

          keys = ['id', 'kode_gudang', 'nama_gudang', 'jenis_gudang', 'alamat', 'luas_gudang', 'jumlah_rak', 'jumlah_binbox', 'jumlah_pallet'];
          if(this.mode == 'edit') keys.push('active');
          post = _.pick(this.gudang, keys);
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;

            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;

            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
            }

            if(data.message != null){
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }

            form_.loading = false;
          });
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
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="master_gudang" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Gudang</th>
              <th>Nama Gudang</th>
              <th>Jenis Gudang</th>
              <th>Luas Gudang</th>
              <th>Jumlah Rak</th>
              <th>Jumlah Binbox</th>
              <th>Jumlah Pallet</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_gudang = $('#master_gudang').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_gudang') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(data){
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'kode_gudang' },
                    { data: 'nama_gudang' },
                    { data: 'jenis_gudang' },
                    { data: 'luas_gudang' },
                    { data: 'jumlah_rak' },
                    { data: 'jumlah_binbox' },
                    { data: 'jumlah_pallet' },
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
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_gudang.on('draw.dt', function() {
              var info = master_gudang.page.info();
              master_gudang.column(0, {
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
    <?php } ?>
  </section>
</div>