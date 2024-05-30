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

    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Menu</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_menu') }" class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='menu.menu_name'>
                      <small v-if="error_exist('id_menu')" class="form-text text-danger">{{ get_error('id_menu') }}</small>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button v-if='mode != "detail" ||mode != "edit" ' :disabled='mode == "detail" ||mode == "edit" ' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_nama_menu_setting_password'><i class="fa fa-search"></i></button>
                    </div>
                    <?php $this->load->view('modal/h3_nama_menu_setting_password') ?>
                    <script>
                      function pilih_menu(data){
                        app.menu.id_menu = data.id_menu;
                        app.menu.menu_name = data.menu_name;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-1 control-label">ID Menu</label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" readonly v-model='menu.id_menu'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-4">
                        <input :disabled='mode == "detail"' class="form-control" v-model='menu.password'></input>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-2">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='menu.active'>
                    </div>
                  </div>
                  <div class="box-footer">
                      <div class="alert alert-warning" v-if="showAlert">
                        <strong>PPN Tools Belum Dichecklist!</strong>
                      </div>
                      <div class="col-sm-6 no-padding">
                      <button v-if='mode == "edit"' class ="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Perbarui</button>
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + menu.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  app = new Vue({
      el: '#app',
      data: {
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        showAlert : false,
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        menu: <?= json_encode($menu) ?>,
        <?php else: ?>
        menu: {
          id_menu: '',
          menu_name: '',
          password: '',
          active: '1',
        },
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          this.errors = {};
          this.loading = false;

          post = _.pick(this.menu, [
            'id_menu', 'password', 'active','id'
          ]);
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              app.errors = data.errors;
              toastr.error(data.message);
            }

            app.loading = false;
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
        <table id="master_setting_password" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama Menu</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_setting_password = $('#master_setting_password').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_setting_password') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'menu_name' },
                    { data: 'active', orderable: false },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>