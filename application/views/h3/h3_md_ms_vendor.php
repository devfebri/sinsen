<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">
                      <input :readonly='mode != "insert"' type="text" class="form-control" v-model='vendor.id_vendor'>
                      <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('vendor_name') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='vendor.vendor_name'>
                      <small v-if="error_exist('vendor_name')" class="form-text text-danger">{{ get_error('vendor_name') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Telepon</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_telp') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='vendor.no_telp'>
                      <small v-if="error_exist('no_telp')" class="form-text text-danger">{{ get_error('no_telp') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                      <textarea :readonly='mode == "detail"' rows="5" class="form-control" v-model='vendor.alamat'></textarea>
                      <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Tipe Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor_type') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='vendor.id_vendor_type'>
                        <div class="input-group-btn">
                          <button v-if='!ada_vendor_type || mode == "detail"' :disabled='mode == "detail"' type='button' data-toggle='modal' data-target='#h3_md_tipe_vendor' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                          <button v-if='ada_vendor_type && mode != "detail"' @click.prevent='hapus_vendor_type' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_vendor_type')" class="form-text text-danger">{{ get_error('id_vendor_type') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor_type') }" class="col-sm-4">
                      <input type="text" class="form-control" v-model='vendor.tipe_vendor' readonly>
                      <small v-if="error_exist('id_vendor_type')" class="form-text text-danger">{{ get_error('id_vendor_type') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_tipe_vendor'); ?>
                  <script>
                    function pilih_tipe_vendor(data) {
                      console.log(data);
                      app.vendor.id_vendor_type= data.id_vendor_type;
                      app.vendor.tipe_vendor= data.vendor_type;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Group Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor_group') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='vendor.id_vendor_group'>
                        <div class="input-group-btn">
                          <button v-if='!ada_vendor_group || mode == "detail"' :disabled='mode == "detail"' type='button' data-toggle='modal' data-target='#h3_md_group_vendor' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                          <button v-if='ada_vendor_group && mode != "detail"' @click.prevent='hapus_vendor_group' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_vendor_group')" class="form-text text-danger">{{ get_error('id_vendor_group') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Group Vendor</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor_group') }" class="col-sm-4">
                      <input type="text" class="form-control" v-model='vendor.group_vendor' readonly>
                      <small v-if="error_exist('id_vendor_group')" class="form-text text-danger">{{ get_error('id_vendor_group') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_group_vendor'); ?>
                  <script>
                    function pilih_group_vendor(data) {
                      app.vendor.id_vendor_group= data.id_vendor_group;
                      app.vendor.group_vendor= data.vendor_group;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">PPN (%)</label>
                    <div v-bind:class="{ 'has-error': error_exist('ppn') }" class="col-sm-4">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='vendor.ppn' max='100' currency='%' currency-symbol-position='suffix'></vue-numeric>
                      <small v-if="error_exist('ppn')" class="form-text text-danger">{{ get_error('ppn') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Rekening</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_rekening') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='vendor.no_rekening'>
                      <small v-if="error_exist('no_rekening')" class="form-text text-danger">{{ get_error('no_rekening') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_rekening') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='vendor.nama_rekening'>
                      <small v-if="error_exist('nama_rekening')" class="form-text text-danger">{{ get_error('nama_rekening') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Atas Nama</label>
                    <div v-bind:class="{ 'has-error': error_exist('atas_nama_bank') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='vendor.atas_nama_bank'>
                      <small v-if="error_exist('atas_nama_bank')" class="form-text text-danger">{{ get_error('atas_nama_bank') }}</small>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div v-bind:class="{ 'has-error': error_exist('active') }" class="col-sm-4">
                      <input type="checkbox" :disabled='mode == "detail"' true-value='1' false-value='0' v-model='vendor.active'>
                      <small v-if="error_exist('active')" class="form-text text-danger">{{ get_error('active') }}</small>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id_vendor=' + vendor.id_vendor" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        vendor: <?= json_encode($vendor) ?>,
        <?php else: ?>
        vendor: {
          kode: '',
          nama: '',
          no_telp: '',
          alamat: '',
          id_vendor_type: '',
          tipe_vendor: '',
          id_vendor_group: '',
          group_vendor: '',
          ppn: 0,
          no_rek: '',
          nama_rekening: '',
          atas_nama_bank: '',
          active: 1,
        },
        <?php endif; ?>
        config: {
          autoclose: true,
          format: 'dd/mm/yyyy',
          todayBtn: 'linked',
          todayHighlight: true,
        },
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.vendor, [
            'id_vendor', 'vendor_name', 'no_telp', 'alamat',
            'id_vendor_type', 'id_vendor_group', 'ppn', 'no_rekening',
            'nama_rekening', 'atas_nama_bank', 'active'
          ]);
          post.mode = this.mode;

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
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
        hapus_vendor_type: function(){
          this.vendor.id_vendor_type = '';
          this.vendor.vendor_type = '';
        },
        hapus_vendor_group: function(){
          this.vendor.id_vendor_group = '';
          this.vendor.vendor_group = '';
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        ada_vendor_type: function(){
          return this.vendor.id_vendor_type != null && this.vendor.id_vendor_type != '';
        },
        ada_vendor_group: function(){
          return this.vendor.id_vendor_group != null && this.vendor.id_vendor_group != '';
        }
      }
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
        <table id="master_vendor" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Vendor</th>
              <th>Nama Vendor</th>
              <th>No. Telp</th>
              <th>Alamat</th>
              <th>Tipe Vendor</th>
              <th>PPN</th>
              <th>No. Rekening</th>
              <th>Nama Bank</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_vendor = $('#master_vendor').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_vendor') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'id_vendor' },
                    { data: 'vendor_name' },
                    { data: 'no_telp' },
                    { data: 'alamat' },
                    { data: 'vendor_type' },
                    { 
                      data: 'ppn',
                      render: function(data){
                        if(data != null && data != ''){
                          return data + '%';
                        }
                        return '-';
                      }
                    },
                    { data: 'no_rekening' },
                    { 
                      data: 'nama_bank',
                      render: function(data){
                        if(data != null){
                          return data;
                        }

                        return '-';
                      }
                    },
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