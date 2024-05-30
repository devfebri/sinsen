<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H3</li>
    <li class="">Warehouse Management</li>
    <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
  </ol>
</section>

<section class="content">
<?php if($set=="form_schedule"){
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
  }
?>
<script>
  Vue.use(VueNumeric.default);
</script>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= base_url("dealer/h3_dealer_stock_opname") ?>">
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
                  <div v-bind:class="{ 'has-error': error_exist('jenis_schedule') }" class="col-sm-4">
                  <!-- <div v-bind:class="{ 'has-error': errors.jenis_schedule != null }" class="col-sm-4"> -->
                    <select :disabled='mode == "detail"' class="form-control" v-model='schedule.jenis_schedule'>
                      <option value="">-Choose-</option>
                      <option value="Stock Opname">Stock Opname</option>
                      <!-- <option value="Stock Opname Unit">Stock Opname Unit</option> -->
                      <option disabled value="Cycle Count">Cycle Count</option>
                      <!-- <option value="Cycle Count Unit">Cycle Count Unit</option> -->
                    </select>
                    <small v-if="error_exist('jenis_schedule')" class="form-text text-danger">{{ get_error('jenis_schedule') }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Cycle Days</label>
                  <div v-bind:class="{ 'has-error': errors.cycle_days != null }" class="col-sm-4">
                    <input type="number" class="form-control" v-model="schedule.cycle_days" placeholder="Harus Berupa Angka" :readonly='mode == "detail"'>
                    <small v-if='errors.cycle_days != null' class="form-text text-danger">{{ errors.cycle_days }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Reminder Days</label>
                  <div v-bind:class="{ 'has-error': errors.reminder_days != null }" class="col-sm-4">
                    <input type="number" class="form-control" v-model="schedule.reminder_days" placeholder="Harus Berupa Angka" :readonly='mode == "detail"'>
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
                  <a v-if='mode == "detail"' :href="'dealer/h3_dealer_stock_opname/edit?id_schedule=' + schedule.id_schedule" class="btn btn-flat btn-sm btn-warning">Edit</a>
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

        this.errors = {};
        this.loading = true;
        axios.post('dealer/h3_dealer_stock_opname/<?= $form ?>', Qs.stringify(post))
        .then(function(res){
          window.location = 'dealer/h3_dealer_stock_opname/detail?id_schedule=' + res.data.id_schedule;
        })
        .catch(function(err){
          // form_.errors = err.response.data;
          // toastr.error(err);
          data = err.response.data;
            if (data.error_type == 'validation_error') {
              form_.errors = data.errors;
              toastr.error(data.message);
            } else {
              toastr.error(data.message);
            }

            form_.loading = false;
        })
        .then(function(){ form_.loading = false; });
      },
      error_exist: function(key) {
        return _.get(this.errors, key) != null;
      },
      get_error: function(key) {
        return _.get(this.errors, key)
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

<?php
  }elseif ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
          $form = 'save_so';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail_so';
      }

      if ($mode=='edit') {
          $form = 'update_so';
      } ?>

    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
          <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php } $_SESSION['pesan'] = ''; ?>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <?php if ($mode=='edit'): ?>
                <input type="hidden" name="id_stock_opname" value="<?= $stock_opname->id_stock_opname ?>">
                <?php endif; ?>
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Schedule Stock Opname</label>
                  <div class="col-sm-4">
                      <!-- <input v-model='schedule_so.id_schedule' type="text" class="form-control" readonly data-toggle='modal' :data-target=' mode != "detail" ? "#schedule_stock_opname" : ""'> -->
                      <input  value="<?php echo $schedule_so->id_schedule?>" type="text" class="form-control" readonly>
                  </div>
                </div>
                <script>
                  function pilih_schedule_stock_opname(schedule_so) {
                    form_.schedule_so = schedule_so;
                  }
                </script>
                <div v-if='mode != "insert"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Opname</label>
                  <div class="col-sm-4">
                      <input v-model='stock_opname.id_stock_opname' type="text" class="form-control" readonly>  
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">NIK PIC</label>
                  <!-- <div class="col-sm-4"> -->
                  <div v-bind:class="{ 'has-error': error_exist('pic') }" class="col-sm-4">
                      <input v-model='pic.nik' type="text" class="form-control" readonly data-toggle='modal' :data-target="is_pic_warehouse && mode != 'detail' ? '#pic_stock_opname' : ''" :placeholder=' is_pic_warehouse ? "Pilih PIC." : "Belum ada PIC"'>  
                      <input v-model='pic.id_karyawan_dealer_int' type="hidden" class="form-control" readonly data-toggle='modal' :data-target="is_pic_warehouse && mode != 'detail' ? '#pic_stock_opname' : ''" placeholder='Pilih PIC.'>
                    <small v-if="error_exist('pic')" class="form-text text-danger">{{ get_error('pic') }}</small>
                  </div>
                  <div class="col-sm-2 no-padding">
                    <button v-if='is_pic_warehouse && mode != "insert" && mode != "detail"' class="btn btn-flat btn-primary" data-toggle='modal' data-target='#member_stock_opname' type='button'>Assign Member</button>
                  </div>
                </div>
                <?php $this->load->view('modal/member_stock_opname') ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama PIC</label>
                  <div class="col-sm-4">
                      <input v-model='pic.nama_lengkap' type="text" class="form-control" readonly data-toggle='modal' :data-target="is_pic_warehouse && mode != 'detail' ? '#pic_stock_opname' : ''" :placeholder=' is_pic_warehouse ? "Pilih PIC." : "Belum ada PIC"'>  
                  </div>
                </div>
                <?php $this->load->view('modal/pic_stock_opname') ?>
                <script>
                  function pilih_pic_stock_opname(data) {
                    form_.pic = data;
                  }
                </script>
                
                <?php if ($mode != 'detail'): ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Mulai</label>
                  <div class="col-sm-4">
                      <!-- <input type="text" class="form-control" v-model='schedule_so.date_opname' disabled> -->
                      <input  value="<?php echo $schedule_so->date_opname?>" type="text" class="form-control" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                  <div class="col-sm-4">
                      <!-- <input type="text" class="form-control" v-model='schedule_so.date_opname_end' disabled>  -->
                      <input  value="<?php echo $schedule_so->date_opname_end?>" type="text" class="form-control" disabled>
                  </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_gudang') }" class="col-sm-4">
                  <!-- <div class="col-sm-4"> -->
                      <input v-model='gudang.id_gudang' type="text" class="form-control" readonly data-toggle='modal' :data-target=' mode != "detail" ? "#gudang_stock_opname" : ""'>
                    <small v-if="error_exist('id_gudang')" class="form-text text-danger">{{ get_error('id_gudang') }}</small>
                  </div>
                </div>
                <script>
                  function pilih_gudang_stock_opname(gudang) {
                    form_.gudang = gudang;
                  }
                </script>
                <div v-if='!_.isEqual(gudang, {})' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Opname</label>
                  <div class="col-sm-4">
                      <!-- <select name="tipe" class="form-control" v-model="tipe_opname" <?= $mode == 'detail' ? 'disabled' : '' ?>>
                        <option value="">--choose--</option>
                        <option value="Stock Opname">Stock Opname</option>
                        <option value="Cycle Count">Cycle Count</option>
                      </select> -->
                      <input value="Stock Opname" name="tipe" type="text" class="form-control" readonly>
                  </div>
                </div>
                <?php $this->load->view('modal/gudang_stock_opname') ?>
                <div v-if='mode == "detail"||mode=="edit"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                      <input v-model='stock_opname.status' type="text" class="form-control" disabled> 
                  </div>
                </div>
                <div v-if='mode == "detail" && stock_opname.keterangan != "" && stock_opname.status=="Recount"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">
                      <input v-model='stock_opname.keterangan' type="text" class="form-control" disabled> 
                  </div>
                </div>
                <div v-if='mode == "detail" && stock_opname.keterangan != ""' class="form-group">
                  <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-flat btn-sm btn-primary" type='button' data-toggle='modal' data-target='#summary_stock_opname'>Summary Stock Opname</button>
                  </div>
                </div>
                <?php $this->load->view('modal/summary_stock_opname') ?>
                
                <?php /*
                <div class="container">
                  <div class="row">
                    <div class="col-sm-2 mr-10">
                        <div class="form-group">
                            <label>ID Part</label>
                            <input type="text" class="form-control" v-model='filter_id_part'>
                        </div>
                    </div>
                    <div class="col-sm-1 mr-10">
                        <div class="form-group">
                            <label>Unit</label>
                            <select class="form-control" v-model='filter_unit'>
                                <option value="">-Choose-</option>
                                <option v-for="e of unit" :value="e">{{ e }}</option>
                            </select>
                        </div>
                    </div>
                  </div>
                </div>
                */?>
                <table class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td>Gudang</td>
                    <td v-if='mode=="detail"' width='5%'>Unit</td>
                    <td>Rak Lokasi</td>
                    <td v-if='is_pic_finance || is_pic_warehouse'>Stock</td>
                    <td>Stock Actual</td>
                    <td>Keterangan</td>
                    <td></td>
                  </tr>
                    <tr v-if="filtered_parts.length > 0" v-for="(part, index) of filtered_parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">
                        {{ part.id_part }}
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td>
                        <span>{{ part.id_gudang }}</span>
                      </td>
                      <td v-if='mode == "detail"'>
                        <span>{{ part.unit }}</span>
                      </td>
                      <td>
                        <span>{{ part.id_rak }}</span>
                      </td>
                      <td v-if='is_pic_finance || is_pic_warehouse'>
                        <span>{{ part.stock }}</span>
                      </td>
                      <td>
                        <span v-if='mode=="detail"'>{{ part.stock_aktual }}</span>
                        <span v-else><input type="number" v-model='part.stock_aktual' class="form-control"></span>
                      </td> 
                      <td>
                        <span v-if='mode=="detail"'>{{ part.keterangan }}</span>
                        <span v-else><input type="text" v-model='part.keterangan' class="form-control"></span>
                      </td>
                      <!-- <td v-if="mode == 'detail' && (is_pic_finance || is_pic_warehouse)" class="align-middle" width='13%'>
                        <button class="btn btn-sm btn-flat btn-success" @click.prevent="tambah_stock_aktual(index)"><i class="fa fa-plus"></i></button>
                        <button class="btn btn-sm btn-flat btn-danger" @click.prevent="kurang_stock_aktual(index)"><i class="fa fa-minus"></i></button>
                      </td> -->
                      <td v-if="mode!='detail'" class="align-middle" width='3%'>
                        <button class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <button v-if="mode!='detail'" type="button" class="margin pull-right btn btn-flat btn-primary btn-sm" data-toggle="modal" data-target="#part_stock_opname"><i class="fa fa-plus"></i></button>
              <?php $this->load->view('modal/part_stock_opname') ?>
              <script>
                function pilih_part_stock_opname(part){
                    form_.parts.push(part);
                }
              </script>
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <?php date_default_timezone_set('Asia/Jakarta');
                  if(date('h:i:s')>='07:00:00'){?>
                  <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <?php }else{?>
                    <button v-if="mode=='insert'" @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <!-- <button v-if="mode=='insert'" type="button" class="btn btn-sm btn-danger btn-flat" disabled><i class="fa fa-regular fa-ban"></i> Stock Opname hanya dapat dilakukan mulai jam 17.00 </button> -->
                  <?php }?>
                  <button v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                  <a v-if='mode == "detail" && (stock_opname.status=="Open"|| stock_opname.status=="Recount")' :href="'dealer/h3_dealer_stock_opname/edit_so?id=' + stock_opname.id_stock_opname" class="btn btn-flat btn-sm btn-warning">Edit</a>
                  <a v-if='mode == "detail" && (stock_opname.status=="Open"|| stock_opname.status=="Recount")'  @click="hasil_opname" class="btn btn-flat btn-sm btn-primary">Save Hasil Stock Opname</a>
                  
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <button v-if="mode=='detail' && (stock_opname.status != 'Open' && stock_opname.status != 'Closed' && stock_opname.status != 'Recount') && (id_user_group.id_user_group == '23'||id_user_group.id_user_group == '43' ||id_user_group.id_user_group == '55')" @click.prevent='reject_report' type="button" class="btn btn-sm btn-danger btn-flat">Reject Report</button>
                  <button v-if="mode=='detail' && (stock_opname.status != 'Open' && stock_opname.status != 'Closed' && stock_opname.status != 'Recount') && (id_user_group.id_user_group == '23'||id_user_group.id_user_group == '43' ||id_user_group.id_user_group == '55')" @click.prevent='approved_report' type="button" class="btn btn-sm btn-success btn-flat">Approve Report</button>
                  <button v-if="mode=='detail' && (stock_opname.status != 'Open' && stock_opname.status != 'Closed' && stock_opname.status != 'Recount') && (id_user_group.id_user_group == '23'||id_user_group.id_user_group == '43' ||id_user_group.id_user_group == '55')" type="button" class="btn btn-sm btn-warning btn-flat" data-toggle='modal' data-target='#request_recount_stock_opname'>Request recount</button>
                  <button v-if="mode=='detail' && stock_opname.status != 'Open'"  type="button" class="btn btn-sm btn-default btn-flat"  data-toggle="modal" data-target="#modal_upload_berita_acara">Upload Berita Acara</button>
                  <a v-if="mode=='detail'" :href="'dealer/h3_dealer_stock_opname/cetak_berita_acara?id=' + stock_opname.id_stock_opname" class="btn btn-flat btn-sm btn-primary">Berita Acara</a>
                  <a v-if="mode=='detail'" :href="'dealer/h3_dealer_stock_opname/cetak_berita_acara_penyesuaian?id=' + stock_opname.id_stock_opname" class="btn btn-flat btn-sm btn-info">Berita Acara Penyesuaian</a>
                  <!-- <button v-if="mode=='detail' && (stock_opname.status != 'Report Approved' && stock_opname.status != 'Request to Recount' && stock_opname.status != 'Request for Owner\'s Approval')" @click.prevent='request_owner_approval' type="button" class="btn btn-sm btn-info btn-flat">Request Owner's approval</button>
                  <button v-if="mode=='detail' && (stock_opname.status == 'Request to Recount' && stock_opname.status != 'Open')" @click.prevent='reopen' type="button" class="btn btn-sm btn-success btn-flat">Re-Open</button> -->
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal_upload_berita_acara" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 class="modal-title" id="myModalLabel">Upload Dokumen Berita Acara</h3>
            </div>
            <form class="form-horizontal" method="post" action="<?php echo base_url().'dealer/h3_dealer_stock_opname/upload_berita_acara'?>" enctype="multipart/form-data">
                <div class="modal-body">
                <input name="id_stock_opname" class="form-control" type="hidden" value="<?php echo $stock_opname->id_stock_opname?>">
                    <div class="form-group">
                        <label class="control-label col-xs-3" >Pilih File Berita Acara</label>
                        <div class="col-xs-8">
                            <input name="import_file" class="form-control" type="file" accept="application/pdf,image/png,image/jpg,image/jpeg" required>
                        </div>
                    </div>
                </div>
 
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
                    <button class="btn btn-info">Simpan</button>
                </div>
            </form>
            </div>
            </div>
    </div>
    <form class="form-horizontal" method="post" action="dealer/h3_dealer_stock_opname/request_recount" enctype="multipart/form-data">
      <div id="request_recount_stock_opname" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog"> 
              <div class="modal-content">

                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title text-left" id="myModalLabel">Keterangan Request Recount</h4>
                      </div>
                      <div class="modal-body">
                          <input type="hidden" value="<?php echo $stock_opname->id_stock_opname?>" name="id_stock_opname">
                          <textarea name="keterangan" class="form-control"></textarea>
                          
                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-flat btn-sm btn-primary">Proses</button>
                          
                    </div>
                      </div>
                  
              </div>
          </div>
      </div>
    </form>
<script>
  var form_ = new Vue({
      el: '#form_',
      errors: {},
      data: {
        kosong :'',
        mode : '<?= $mode ?>',
        is_pic_warehouse: <?= $this->m_admin->is_pic_warehouse() ? 'true' : 'false' ?>,
        is_pic_finance: <?= $this->m_admin->is_pic_finance() ? 'true' : 'false' ?>,
        filter_id_part: '',
        filter_unit: '',
        loading: false,
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        tipe_opname: '<?= $stock_opname->tipe ?>',
        id_user_group: <?= json_encode($id_user_group) ?>,
        stock_opname: <?= json_encode($stock_opname) ?>,
        parts: <?= json_encode($parts) ?>,
        summary: <?= json_encode($summary) ?>,
        gudang: <?= json_encode($gudang) ?>,
        schedule_so: <?= $schedule_so != null ? json_encode($schedule_so) : '{}' ?>,
        pic: <?= $pic != null ? json_encode($pic) : '{}' ?>,
        members: <?= $members != null ? json_encode($members) : '[]' ?>,
        member: {},
        member_errors: [],
        keterangan: '',
        <?php else: ?>
        members: [],
        member: {},
        summary: {},
        member_errors: [],
        parts: [],
        gudang: {},
        pic : {},
        schedule_so: {},
        id_schedule:'<?= $schedule_so->id_schedule ?>',
        tipe_opname: 'Stock Opname',
        date_opname: '<?= $schedule_so->date_opname?>',
        date_opname_end: '<?= $schedule_so->date_opname_end?>',
        keterangan: '',
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = {};
          this.errors = {};

          if(this.mode == 'edit'){
            post.id_stock_opname = this.stock_opname.id_stock_opname;
            post.id_pic = this.pic.id_karyawan_dealer;
          }
          post.id_schedule = this.id_schedule;
          post.id_gudang = this.gudang.id_gudang;
          post.tipe = this.tipe_opname;
          post.date_opname = this.date_opname;
          post.date_opname_end = this.date_opname_end;
          post.pic = this.pic.id_karyawan_dealer_int;
          post.parts = _.map(this.parts, function(p){
            return _.pick(p, ['id_part','id_part_int', 'id_gudang', 'id_rak', 'stock','stock_aktual','keterangan'])
          });

          axios.post('dealer/h3_dealer_stock_opname/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            console.log(res);
            window.location = 'dealer/h3_dealer_stock_opname/detail_so?id=' + res.data.id_stock_opname;
            
          })
          .catch(function(err){
            // toastr.error(err);
            data = err.response.data;
            if (data.error_type == 'validation_error') {
              form_.errors = data.errors;
              toastr.error(data.message);
            } else {
              toastr.error(data.message);
            }
          });
        },
        assign_member: function(){
          this.member_errors = {};
          post = {};
          post.id_stock_opname = this.stock_opname.id_stock_opname;
          post.id_member = this.member.id_karyawan_dealer;
          post.dari = this.member.dari;
          post.sampai = this.member.sampai;

          axios.post('dealer/h3_dealer_stock_opname/assign_member', Qs.stringify(post))
          .then(function(res){
            form_.members.push(res.data);
            form_.member = {};
          })
          .catch(function(err){
            form_.member_errors = err.response.data;
            toastr.error(err);
          });
        },
        error_exist: function(key) {
        return _.get(this.errors, key) != null;
        },
        get_error: function(key) {
          return _.get(this.errors, key)
        },
        remove_member: function(index){
          member = this.members[index];

          axios.get('dealer/h3_dealer_stock_opname/remove_member',{
            params: {
              id: member.id
            }
          })
          .then(function(res){
            form_.members.splice(index, 1);
          })
          .catch(function(err){
            toastr.error(err);
          });
        },
        // get_all_stock: function(){
        //   axios.post('dealer/h3_dealer_stock_opname/get_stock_in_warehouse', Qs.stringify({
        //       id_gudang: this.gudang.id_gudang
        //   }))
        //   .then(function (response) {
        //     form_.parts = response.data;
        //   })
        //   .catch(function (error) {
        //     toastr.error(error);
        //   });
        // },
        update_stock_aktual_handler: _.debounce(function(index){
          post = _.pick(form_.parts[index], ['id_stock_opname', 'id_part','id_part_int', 'id_rak', 'id_gudang', 'stock_aktual']);

          this.loading = true;
          axios.post('dealer/h3_dealer_stock_opname/update_stock_aktual', Qs.stringify(post))
          .then(function(res){

          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; })
        }, 500),
        tambah_stock_aktual: function(index){
          this.parts[index].stock_aktual++;
          this.update_stock_aktual_handler(index);
        },
        kurang_stock_aktual: function(index){
          this.parts[index].stock_aktual--;
          this.update_stock_aktual_handler(index);
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        },
        // request_recount: function(){
        //   post = {};
        //   post.id_stock_opname = this.stock_opname.id_stock_opname;
        //   post.status = 'Request to Recount';
        //   post.keterangan = this.keterangan;
          
        //   axios.post('dealer/h3_dealer_stock_opname/request_recount', Qs.stringify(post))
        //   .then(function(res){
        //     window.location = 'dealer/h3_dealer_stock_opname/detail?id=' + res.data.id_stock_opname;
        //   })
        //   .catch(function(err){
        //     toastr.error(err);
        //   });
        // },
        // approved_report: function(){
        //   post = {};
        //   post.id_stock_opname = this.stock_opname.id_stock_opname;
        //   post.status = 'Report Approved';
          
        //   axios.post('dealer/h3_dealer_stock_opname/approved_report', Qs.stringify(post))
        //   .then(function(res){
        //     window.location = 'dealer/h3_dealer_stock_opname/detail?id=' + res.data.id_stock_opname;
        //   })
        //   .catch(function(err){
        //     toastr.error(err);
        //   });
        // },
        // request_owner_approval: function(){
        //   post = {};
        //   post.id_stock_opname = this.stock_opname.id_stock_opname;
        //   post.status = 'Request for Owner\'s Approval';
          
        //   axios.post('dealer/h3_dealer_stock_opname/request_owner_approval', Qs.stringify(post))
        //   .then(function(res){
        //     window.location = 'dealer/h3_dealer_stock_opname/detail?id=' + res.data.id_stock_opname;
        //   })
        //   .catch(function(err){
        //     toastr.error(err);
        //   });
        // },
        // reopen: function(){
        //   post = {};
        //   post.id_stock_opname = this.stock_opname.id_stock_opname;
        //   post.status = 'Open';
          
        //   axios.post('dealer/h3_dealer_stock_opname/request_owner_approval', Qs.stringify(post))
        //   .then(function(res){
        //     window.location = 'dealer/h3_dealer_stock_opname/detail?id=' + res.data.id_stock_opname;
        //   })
        //   .catch(function(err){
        //     toastr.error(err);
        //   });
        // },
        hasil_opname: function()
        {
          stock_opname_id = this.stock_opname.id_stock_opname
          if(confirm('Yakin untuk menyimpan hasil stock opname ? \n Data akan dikonfirmasi kepada Owner/Branch Manager')){
              // window.location = 'dealer/h3_dealer_stock_opname/save_hasil_opname?id=' + stock_opname_id;
            // alert("test 123");
            $.ajax({
              type: "POST",
              url: "<?= base_url('dealer/h3_dealer_stock_opname/approval_to_branch_manager') ?>",
              dataType: "JSON",
              data: {
                id_stock_opname : stock_opname_id
              },
              success: function(Result){
                const {
                        status,
                        message,
                        data
                    } = Result;

                    if (status) {
                      alert('Data berhasil disimpan');
                      // window.location = 'dealer/h3_dealer_stock_opname/save_hasil_opname?id=' + stock_opname_id;
                      window.location = 'dealer/h3_dealer_stock_opname/detail_so?id=' + stock_opname_id;
                    } else {
                        alert('Data gagal disimpan');
                    }
                
              }
            });
          }
          // alert(stock_opname_id);
        },
        reject_report: function()
        {
          id_stock_opname = this.stock_opname.id_stock_opname
          if(confirm('Yakin hasil Stock Opname direject ?')){
            $.ajax({
                  type: "POST",
                  url: "<?= base_url('dealer/h3_dealer_stock_opname/reject_report') ?>",
                  dataType: "JSON",
                  beforeSend: function(){ $('.loading').show();},
                  complete: function() { $('.loading').hide(); }, 
                  data: {
                    id_stock_opname: id_stock_opname
                  },
                  success: function(Result) {
                      const {
                          status,
                          message,
                          data
                      } = Result;

                      if (status) {
                        alert('Report Hasil Opname Berhasil Direject');
                        window.location = 'dealer/h3_dealer_stock_opname';
                      } else {
                          alert('Report Hasil Opname Gagal Direject');
                      }
                  },
                  error: function(x, y, z) {
                      alert('Data gagal diapprove');
                  }
            });
          }  
        },
        approved_report: function()
        {
          id_stock_opname = this.stock_opname.id_stock_opname
          if(confirm('Yakin hasil Stock Opname disetujui ? \n Stock akan langsung diupdate.')){
            $.ajax({
                  type: "POST",
                  url: "<?= base_url('dealer/h3_dealer_stock_opname/approved_report') ?>",
                  dataType: "JSON",
                  data: {
                    id_stock_opname: id_stock_opname
                  },
                  success: function(Result) {
                      const {
                          status,
                          message,
                          data
                      } = Result;

                      if (status) {
                        alert('Hasil Opname berhasil diapproved dan stock berhasil diupdate');
                        window.location = 'dealer/h3_dealer_stock_opname/detail_so?id=' + id_stock_opname;
                      } else {
                          alert('Hasil Opname gagal diapproved dan stock gagal diupdate');
                      }
                  },
                  error: function(x, y, z) {
                      alert('Data gagal diapprove');
                  }
            });
          }   
        }
      },
      computed: {
        unit: function(){
          unique_unit = _.uniqBy(this.parts, function(e){
            return e.unit;
          });

          unique_unit = _.map(unique_unit, function(e){
            return e.unit;
          });

          return unique_unit;
        },
        filtered_parts: function(){
          filter_unit = this.filter_unit;
          filtered =  _.filter(this.parts, function(part){
            if(filter_unit != ''){
              return part.unit == filter_unit;
            }else{
              return true;
            }
          });

          filter_id_part = this.filter_id_part;
          filtered = _.filter(filtered, function(part){
            return part.id_part.toLowerCase().includes(filter_id_part.toLowerCase())
          });
          return filtered;
        }
      },
      watch: {
        gudang: {
          deep: true,
          handler: function(){
            this.parts = [];
            part_stock_opname_datatable.draw();
            // if(this.tipe_opname == 'Stock Opname'){
            //   this.get_all_stock();
            // }
          }
        },
        tipe_opname: function(){
          this.parts = [];
          // if(this.tipe_opname == 'Stock Opname'){
          //   this.get_all_stock();
          // }
        }
      }
  });
</script>

    <?php } elseif ($set=="index") {?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php 
            $id_dealer = $this->m_admin->cari_dealer();
            $query = $this->db->query("SELECT count(1) as hitung FROM ms_set_up_schedule_stock_opname WHERE MONTH(current_date())=MONTH(date_opname) and id_dealer='$id_dealer'")->row();
            if($query->hitung ==0){ ?>
            <a href="dealer/<?= $isi ?>/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
          <?php 
            }else{
          ?>
            <button class="btn bg-danger btn-flat margin" disabled>Anda telah melakukan stock opname bulan ini</button>
          <?php }?>
          
        </h3>

        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->

      <div class="box-body">
        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>

        </div>
        <?php
        }
      $_SESSION['pesan'] = ''; ?>
        <table id="example12" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>ID Schedule</th>
              <th>ID Stock Opname</th>
              <th>Tipe</th>
              <th>Date Opname Start</th>
              <th>Date Opname End</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>

          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function() {
          var table;
          table = $('#example12').DataTable({ 
              "processing": true, 
              "serverSide": true, 
              "order": [], 

              "ajax": {
                "url": "<?php echo site_url('dealer/h3_dealer_stock_opname/getDataStockOpname')?>",
                "type": "POST"
              },

              "columnDefs": [
              { 
                "targets": [ 0 ], 
                "orderable": false, 
              },
              ],
          });

      });
    </script>
    <?php }?>
  </section>

</div>