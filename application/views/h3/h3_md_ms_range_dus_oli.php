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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Range</label>
                    <div v-bind:class="{ 'has-error': error_exist('kode_range') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='range_dus_oli.kode_range'>
                      <small v-if="error_exist('kode_range')" class="form-text text-danger">{{ get_error('kode_range') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Awal Range Dus</label>
                    <div v-bind:class="{ 'has-error': error_exist('range_start') }" class="col-sm-4">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='range_dus_oli.range_start' separator='.' currency='Dus' currency-symbol-position='suffix'></vue-numeric>
                      <small v-if="error_exist('range_start')" class="form-text text-danger">{{ get_error('range_start') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Akhir Range Dus</label>
                    <div v-bind:class="{ 'has-error': error_exist('range_end') }" class="col-sm-4">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='range_dus_oli.range_end' separator='.' currency='Dus' currency-symbol-position='suffix'></vue-numeric>
                      <small v-if="error_exist('range_end')" class="form-text text-danger">{{ get_error('range_end') }}</small>
                    </div>
                  </div>
                  <div v-if='mode != "insert"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model='range_dus_oli.active' true-value='1' false-value='0' :disabled='mode == "detail"'>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/<?= $isi ?>/edit?id=' + range_dus_oli.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var app = new Vue({
      el: '#app',
      data: {
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        range_dus_oli: <?= json_encode($range_dus_oli) ?>,
        <?php else: ?>
        range_dus_oli: {
          kode_range: '',
          range_start: 0,
          range_end: 0,
          active: 1,
        }
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.range_dus_oli, [
            'id', 'kode_range', 'range_start', 'range_end', 'active'
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
          .then(function(){ app.loading = false; })
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
        <table id="master_range_dus_oli" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Range</th>
              <th>Awal Range Dus</th>
              <th>Akhir Range Dus</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_range_dus_oli = $('#master_range_dus_oli').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_range_dus_oli') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'kode_range' },
                    { data: 'range_start' },
                    { data: 'range_end' },
                    {
                      data: 'active',
                      render: function(data){
                        if(data == 1){
                          return '<i class="glyphicon glyphicon-ok"></i>'
                        }
                        return '<i class="glyphicon glyphicon-remove"></i>'
                      },
                      width: '5%',
                      className: 'text-center',
                      orderable: false,
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_range_dus_oli.on('draw.dt', function() {
              var info = master_range_dus_oli.page.info();
              master_range_dus_oli.column(0, {
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