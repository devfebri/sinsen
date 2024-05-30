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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Wilayah Penagihan</label>
                    <div v-bind:class="{ 'has-error': error_exist('kode_wilayah') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='wilayah_penagihan.kode_wilayah'>
                      <small v-if="error_exist('kode_wilayah')" class="form-text text-danger">{{ get_error('kode_wilayah') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Wilayah Penagihan</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='wilayah_penagihan.nama'>
                      <small v-if="error_exist('nama')" class="form-text text-danger">{{ get_error('nama') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div class="col-sm-4 ">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='wilayah_penagihan.active'>
                    </div>
                  </div>
                  <div class="container-fluid">
                      <div class="row">
                        <div class="col-sm-12 no-padding">
                          <table class="table table-condensed">
                            <tr>
                              <td width='3%'>No.</td>
                              <td>Kode Customer</td>
                              <td>Nama Customer</td>
                              <td v-if='mode != "detail"' width='3%'></td>
                            </tr>
                            <tr v-if='items.length > 0' v-for='(item, index) of items'>
                              <td width='3%'>{{ index + 1 }}.</td>
                              <td>{{ item.kode_dealer_md }}</td>
                              <td>{{ item.nama_dealer }}</td>
                              <td v-if='mode != "detail"' width='3%'>
                                <button class="btn flat btn-sm btn-danger" @click.prevent='hapus_item(index)'><i class="fa fa-trash-o"></i></button>
                              </td>
                            </tr>
                            <tr v-if='items.length < 1'>
                              <td class='text-center' colspan='4'>Tidak ada data</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12 no-padding text-right">
                          <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_wilayah_penagihan'><i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_wilayah_penagihan'); ?>
                  <script>
                    function pilih_dealer_wilayah_penagihan(data) {
                      form_.items.push(data);
                      h3_md_dealer_wilayah_penagihan_datatable.draw();
                    }
                  </script>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + wilayah_penagihan.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        index_part: 0,
        loading: false,
        errors: {},
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        wilayah_penagihan: <?= json_encode($wilayah_penagihan) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        wilayah_penagihan: {
          kode_wilayah: '',
          nama: '',
          active: 1
        },
        items: []
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.wilayah_penagihan, ['id', 'kode_wilayah', 'nama', 'active']);
          post.items = _.map(this.items, function(data){
            return _.pick(data, ['id_dealer']);
          });

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
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
        hapus_item: function(index){
          this.items.splice(index, 1);
          h3_md_dealer_wilayah_penagihan_datatable.draw();
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
        <table id="wilayah_penagihan" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Wilayah</th>
              <th>Wilayah Penagihan</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            wilayah_penagihan = $('#wilayah_penagihan').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/wilayah_penagihan') ?>",
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
                    { data: 'kode_wilayah' },
                    { data: 'nama' },
                    { 
                      data: 'active',
                      render: function(data){
                        if(data == 1){
                          return '<i class="glyphicon glyphicon-ok"></i>';
                        }
                        return '<i class="glyphicon glyphicon-remove"></i>';
                      },
                      width: '3%',
                      className: 'text-center'
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            wilayah_penagihan.on('draw.dt', function() {
              var info = wilayah_penagihan.page.info();
              wilayah_penagihan.column(0, {
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