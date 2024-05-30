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

    <div id="form_" class="box box-default">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Kelompok Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_kelompok_vendor') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='kelompok_vendor.id_kelompok_vendor'>
                      <small v-if="error_exist('id_kelompok_vendor')" class="form-text text-danger">{{ get_error('id_kelompok_vendor') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Kelompok Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('kelompok_vendor') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='kelompok_vendor.kelompok_vendor'>
                      <small v-if="error_exist('kelompok_vendor')" class="form-text text-danger">{{ get_error('kelompok_vendor') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status Pengiriman</label>
                    <div v-bind:class="{ 'has-error': error_exist('status_pengiriman') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='kelompok_vendor.status_pengiriman'>
                        <option value="">-Pilih-</option>
                        <option value="Dikirim ke AHM">Dikirim ke AHM</option>
                        <option value="Tidak dikirim ke AHM">Tidak dikirim ke AHM</option>
                      </select>
                      <small v-if="error_exist('status_pengiriman')" class="form-text text-danger">{{ get_error('status_pengiriman') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Status</label>
                    <div class="col-sm-4 ">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='kelompok_vendor.active'>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail" && kelompok_vendor.created_manually == 1' :href="'h3/h3_md_ms_kelompok_vendor/edit?id=' + kelompok_vendor.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        kelompok_vendor: <?= json_encode($kelompok_vendor) ?>,
        <?php else: ?>
        kelompok_vendor: {
          id_kelompok_vendor: '',
          kelompok_vendor: '',
          status_pengiriman: 'Tidak dikirim ke AHM',
          active: 1,
          created_manually: 1,
        }
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.kelompok_vendor, [
            'id_kelompok_vendor', 'kelompok_vendor', 'status_pengiriman', 'created_manually', 'active'
          ]);

          if(this.mode == 'edit'){
            post.id = this.kelompok_vendor.id;
          }

          this.loading = true;
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/h3_md_ms_kelompok_vendor/detail?id=' + res.data.id;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){ form_.loading = false; })
          ;
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
        <table id="master_kelompok_vendor" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Kelompok Vendor</th>
              <th>Nama Kelompok Vendor</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_kelompok_vendor = $('#master_kelompok_vendor').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_kelompok_vendor') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'id_kelompok_vendor' },
                    { data: 'kelompok_vendor' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_kelompok_vendor.on('draw.dt', function() {
              var info = master_kelompok_vendor.page.info();
              master_kelompok_vendor.column(0, {
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