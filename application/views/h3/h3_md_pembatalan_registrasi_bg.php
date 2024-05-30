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
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div v-bind:class="{ 'has-error': error_exist('bank') }" class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Bank</label>
                    <div class="col-sm-4">
                      <select :disabled='mode == "detail" || loading' v-model='pembatalan_registrasi_bg.bank' class="form-control">
                        <option value="">-</option>
                        <option v-for='bank of ms_bank' :value="bank.id_bank">{{ bank.bank }}</option>
                      </select>
                      <small v-if="error_exist('bank')" class="form-text text-danger">{{ get_error('bank') }}</small>
                    </div>
                  </div>
                  <div v-bind:class="{ 'has-error': error_exist('kode_giro') }" class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Giro</label>
                    <div class="col-sm-4">
                      <input :disabled='mode == "detail" || loading' type="text" class="form-control" v-model='pembatalan_registrasi_bg.kode_giro'>
                      <small v-if="error_exist('kode_giro')" class="form-text text-danger">{{ get_error('kode_giro') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nominal Giro</label>
                    <div class="col-sm-4">
                      <vue-numeric v-model='pembatalan_registrasi_bg.nominal' currency='Rp' thousand-separator='.' class='form-control' readonly></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pembatalan_registrasi_bg.status'>
                    </div>
                  </div>
                  <div v-if='pembatalan_registrasi_bg.status == "Canceled"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan Cancel</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pembatalan_registrasi_bg.alasan_cancel'>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                  <div class="col-sm-6">
                      <a v-if='pembatalan_registrasi_bg.status == "Open" && mode == "detail"' :disabled='loading' class="btn btn-flat btn-warning btn-sm" :href='"h3/<?= $isi ?>/edit?id_cek_giro=" + this.pembatalan_registrasi_bg.id_cek_giro'>Edit</a>
                      <button v-if='pembatalan_registrasi_bg.status == "Open" && mode == "edit"' :disabled='loading' class="btn btn-flat btn-warning btn-sm" type='button' @click.prevent='<?= $form ?>'>Update</button>
                    </div>
                    <div class="col-sm-6 text-right">
                      <button v-if='pembatalan_registrasi_bg.status == "Open" && mode == "detail"' :disabled='loading' class="btn btn-flat btn-danger btn-sm" type='button' data-toggle='modal' data-target='#alasan_cancel_registrasi_bg'>Cancel</button>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/alasan_cancel_registrasi_bg'); ?>
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
        pembatalan_registrasi_bg: <?= json_encode($pembatalan_registrasi_bg) ?>,
        ms_bank: <?= json_encode($ms_bank) ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.pembatalan_registrasi_bg, [
            'id_cek_giro', 'kode_giro', 'bank'
          ]);

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null){
              window.location = data.redirect_url;
            }
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(data.message);
            }

            form_.loading = false;
          });
        },
        cancel: function(){
          confirmed = confirm('Apakah anda yakin ingin membatalkan no. giro ini?');

          if(!confirmed) return;

          post = {
            id_cek_giro: this.pembatalan_registrasi_bg.id_cek_giro,
            alasan_cancel: $('#alasan_cancel').val(),
          }

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/cancel', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null){
              window.location = data.redirect_url;
            }
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
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
      <div class="box-header">
        <?php if($this->input->get('history') != null): ?>
        <a href="h3/<?= $isi ?>">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
        </a>  
        <?php else: ?>
        <a href="h3/<?= $isi ?>?history=true">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
        </a> 
        <?php endif; ?>
      </div>
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
          <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. Giro</label>
                    <input type="text" class="form-control" id="no_giro_filter">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_giro_filter').on('keyup', _.debounce(function(){
                    pencairan_bg.draw();
                  }, 500));
                })
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Bank</label>
                    <input type="text" class="form-control" id="nama_bank_bg_filter">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#nama_bank_bg_filter').on('keyup', _.debounce(function(){
                    pencairan_bg.draw();
                  }, 500));
                })
              </script>
            </div>
          </div>
        </div>
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <table id="pencairan_bg" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal Buat</th>
              <th>No. Giro</th>
              <th>Nama Bank</th>
              <th>Nominal</th>
              <th>Status</th>
              <th>Aktif</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            pencairan_bg  = $('#pencairan_bg').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                searching: false,
                ajax: {
                  url: "<?= base_url('api/md/h3/pembatalan_registrasi_bg') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.nama_bank_bg_filter = $('#nama_bank_bg_filter').val();
                    d.no_giro_filter = $('#no_giro_filter').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { 
                      data: 'tgl_buat',
                      render: function(data){
                        return moment(data).format('DD/MM/YYYY');
                      }
                    },
                    { data: 'kode_giro' },
                    { data: 'bank' },
                    { 
                      data: 'nominal',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    { data: 'status' },
                    { 
                      data: 'active',
                      render: function(data){
                        if(data == 1){
                          return 'Ya';
                        }else{
                          return 'Tidak';
                        }
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