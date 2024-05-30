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
                    <label for="inputEmail3" class="col-sm-2 control-label">NIK</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_karyawan') }" class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='debt_collector.id_karyawan'>
                      <small v-if="error_exist('id_karyawan')" class="form-text text-danger">{{ get_error('id_karyawan') }}</small>
                    </div>
                    <div v-if='mode != "detail" ' class="col-sm-1 no-padding">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_karyawan_debt_collector'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_karyawan_debt_collector'); ?>
                  <script>
                    function pilih_karyawan_debt_collector(data){
                      form_.debt_collector = data;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Collector</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='debt_collector.nama_lengkap'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div class="col-sm-4">
                      <textarea disabled cols="30" rows="5" class="form-control" v-model='debt_collector.alamat'></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Telepon</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='debt_collector.no_telp'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Masuk Kerja</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='debt_collector.tgl_masuk'>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div class="col-sm-4 ">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='debt_collector.active'>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + debt_collector.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        debt_collector: <?= json_encode($debt_collector) ?>,
        <?php else: ?>
        debt_collector: {
          id_karyawan: '',
          npk: '',
          nama_lengkap: '',
          no_telp: '',
          alamat: '',
          tgl_masuk: '',
          active: 1
        },
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.debt_collector, ['id', 'id_karyawan', 'active']);

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
        <table id="debt_collector" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>NPK</th>
              <th>Nama</th>
              <th>No Telepon</th>
              <th>Tanggal Masuk</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            debt_collector = $('#debt_collector').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/debt_collector') ?>",
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
                    { data: 'npk' },
                    { data: 'nama_lengkap' },
                    { data: 'no_telp' },
                    { data: 'tgl_masuk' },
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

            debt_collector.on('draw.dt', function() {
              var info = debt_collector.page.info();
              debt_collector.column(0, {
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