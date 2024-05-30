<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?php echo $breadcrumb ?>
</section>
<section class="content">
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
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
<script>
  Vue.use(VueNumeric.default);
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= base_url("dealer/{$isi}") ?>">
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
              <form  class="form-horizontal">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <div v-if='mode == "detail" || mode == "edit"'>
                    <label class="col-sm-2 control-label">ID Schedule</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model="schedule.id_schedule" :readonly='mode == "detail" || mode == "edit"'>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Jenis Schedule</label>
                  <div v-bind:class="{ 'has-error': errors.jenis_schedule != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' class="form-control" v-model='schedule.jenis_schedule'>
                      <option value="">-Choose-</option>
                      <option value="Stock Opname Parts">Stock Opname Parts</option>
                      <option value="Stock Opname Unit">Stock Opname Unit</option>
                      <option value="Cycle Count Parts">Cycle Count Parts</option>
                      <option value="Cycle Count Unit">Cycle Count Unit</option>
                    </select>
                    <small v-if='errors.jenis_schedule != null' class="form-text text-danger">{{ errors.jenis_schedule }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Cycle Days</label>
                  <div v-bind:class="{ 'has-error': errors.cycle_days != null }" class="col-sm-4">
                    <input type="text" class="form-control" v-model="schedule.cycle_days" :readonly='mode == "detail"'>
                    <small v-if='errors.cycle_days != null' class="form-text text-danger">{{ errors.cycle_days }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Reminder Days</label>
                  <div v-bind:class="{ 'has-error': errors.reminder_days != null }" class="col-sm-4">
                    <input type="text" class="form-control" v-model="schedule.reminder_days" :readonly='mode == "detail"'>
                    <small v-if='errors.reminder_days != null' class="form-text text-danger">{{ errors.reminder_days }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Date Opname</label>
                  <div v-bind:class="{ 'has-error': errors.date_opname != null }" class="col-sm-4">
                    <input :disabled='mode == "detail"' id='date_opname' type="text" class="form-control">
                    <small v-if='errors.date_opname != null' class="form-text text-danger">{{ errors.date_opname }}</small>
                  </div>
                </div>
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Simpan</button>
                  <button v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                  <a v-if='mode == "detail"' :href="'dealer/h3_dealer_set_up_schedule_stock_opname/edit?id_schedule=' + schedule.id_schedule" class="btn btn-flat btn-sm btn-warning">Edit</a>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
form_ = new Vue({
    el: '#form_',
    data: {
      kosong :'',
      mode : '<?= $mode ?>',
      loading: false,
      errors: {},
      <?php if ($mode == 'detail' or $mode == 'edit'): ?>
      schedule: <?= json_encode($schedule) ?>,
      <?php else: ?>
      schedule: {
        jenis_schedule: '',
        cycle_days: '',
        reminder_days: '',
        date_opname: '',
        date_opname_end: '',
      },
      <?php endif; ?>
    },
    methods: {
      <?= $form ?>: function(){
        post = {};
        if(this.mode == 'edit'){
          post.id_schedule = this.schedule.id_schedule;
        }
        post.jenis_schedule = this.schedule.jenis_schedule;
        post.cycle_days = this.schedule.cycle_days;
        post.reminder_days = this.schedule.reminder_days;
        post.date_opname = this.schedule.date_opname;
        post.date_opname_end = this.schedule.date_opname_end;

        this.loading = true;
        axios.post('dealer/h3_dealer_set_up_schedule_stock_opname/<?= $form ?>', Qs.stringify(post))
        .then(function(res){
          window.location = 'dealer/h3_dealer_set_up_schedule_stock_opname/detail?id_schedule=' + res.data.id_schedule;
        })
        .catch(function(err){
          form_.errors = err.response.data;
          toastr.error(err);
        })
        .then(function(){ form_.loading = false; });
      },
    },
    mounted: function(){
      config = {
        opens: 'left',
        autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
        locale: {
          format: 'DD/MM/YYYY'
        }
      };

      if(this.mode == 'detail' || this.mode == 'edit'){
        config.startDate = new Date(this.schedule.date_opname);
        config.endDate = new Date(this.schedule.date_opname_end);
      }

      date_opname = $('#date_opname').daterangepicker(config).on('apply.daterangepicker', function(ev, picker) {
        form_.schedule.date_opname = picker.startDate.format('YYYY-MM-DD');
        form_.schedule.date_opname_end = picker.endDate.format('YYYY-MM-DD');

        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));

      }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
      });
    }
});
</script>
<?php } elseif ($set=="index") { ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href='<?= base_url("dealer/{$isi}/add") ?>'>
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="schedule" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Schedule</th>
              <th>Jenis Schedule</th>
              <th>Cycle Days</th>
              <th>Reminder Days</th>
              <th>Date Opname</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            schedule = $('#schedule').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/schedule_opname') ?>",
                    dataSrc: "data",
                    type: "POST"
                },
                columns: [
                    { data: 'id_schedule' },
                    { data: 'jenis_schedule' },
                    { data: 'cycle_days' },
                    { data: 'reminder_days' },
                    { data: 'date_opname' },
                    { data: 'action' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>