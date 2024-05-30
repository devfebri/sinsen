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
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.nama_dealer'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.jenis_pembayaran'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Bank</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.nama_bank_bg'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Giro</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.nomor_bg'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jatuh tempo</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.tanggal_jatuh_tempo_bg'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nominal Giro</label>
                    <div class="col-sm-4">
                      <vue-numeric v-model='pencairan_bg.nominal_bg' currency='Rp' thousand-separator='.' class='form-control' readonly></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Bank Tujuan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.nama_bank_tujuan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Rek Tujuan</label>
                    <div class="col-sm-4">
                      <input disabled type="text" class="form-control" v-model='pencairan_bg.no_rekening_tujuan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Cair</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_cair_bg') }" class="col-sm-4">
                      <input type="text" class="form-control" readonly id='tanggal_cair_picker'>
                      <small v-if="error_exist('tanggal_cair_bg')" class="form-text text-danger">{{ get_error('tanggal_cair_bg') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status BG</label>
                    <div v-bind:class="{ 'has-error': error_exist('status_bg') }" class="col-sm-4">
                      <select :disabled='pencairan_bg.proses_bg == 1' v-model='pencairan_bg.status_bg' class="form-control">
                        <option value="">-Choose-</option>
                        <option value="Cair">Cair</option>
                        <option value="Tolak">Tolak</option>
                      </select>
                      <small v-if="error_exist('status_bg')" class="form-text text-danger">{{ get_error('status_bg') }}</small>
                    </div>
                  </div>
                  <div v-if='pencairan_bg.status_bg == "Tolak"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan (Jika di tolak)</label>
                    <div v-bind:class="{ 'has-error': error_exist('alasan_penolakan_bg') }" class="col-sm-4">
                      <input :readonly='pencairan_bg.proses_bg == 1' type="text" class="form-control" v-model='pencairan_bg.alasan_penolakan_bg'>
                      <small v-if="error_exist('alasan_penolakan_bg')" class="form-text text-danger">{{ get_error('alasan_penolakan_bg') }}</small>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='pencairan_bg.proses_bg != 1' class="btn btn-flat btn-sm btn-success" @click.prevent='proses'>Proses</button>
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
        pencairan_bg: <?= json_encode($pencairan_bg) ?>,
      },
      mounted: function(){
        $(document).ready(function(){
          $('#tanggal_cair_picker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            enableOnReadonly: true,
          }).on('changeDate', function(e){
            form_.pencairan_bg.tanggal_cair_bg = e.format('yyyy-mm-dd');
          });
        });
        if(this.pencairan_bg.tanggal_cair_bg != null){
          date = new Date(this.pencairan_bg.tanggal_cair_bg);
          $(document).ready(function(){
            $("#tanggal_cair_picker").datepicker("setDate", date);
            $('#tanggal_cair_picker').datepicker('update');
          });
        }

        if(this.pencairan_bg.proses_bg == 1){
          $('#tanggal_cair_picker').prop('disabled', true);
        }
      },
      methods:{
        proses: function(){
          post = _.pick(this.pencairan_bg, [
            'id_penerimaan_pembayaran', 'tanggal_cair_bg', 'status_bg', 'alasan_penolakan_bg'
          ]);

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/proses', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id_penerimaan_pembayaran=' + res.data.id_penerimaan_pembayaran;
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
              <div class="row" id='filter_customer'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" :value='filters.length + " Customer"' disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_pencairan_bg_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>       
              <?php $this->load->view('modal/h3_md_dealer_filter_pencairan_bg_index'); ?>         
              <script>
                filter_customer = new Vue({
                  el: '#filter_customer',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      pencairan_bg.draw();
                    }
                  }
                });

                $("#h3_md_dealer_filter_pencairan_bg_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_dealer = target.attr('data-id-dealer');

                  if(target.is(':checked')){
                    filter_customer.filters.push(id_dealer);
                  }else{
                    index_dealer = _.indexOf(filter_customer.filters, id_dealer);
                    filter_customer.filters.splice(index_dealer, 1);
                  }
                  h3_md_dealer_filter_pencairan_bg_index_datatable.draw();
                });
              </script>
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
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Jatuh Tempo</label>
                    <input id='jatuh_tempo_filter' type="text" class="form-control" readonly>
                    <input id='jatuh_tempo_filter_value' type="hidden">
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#jatuh_tempo_filter').datepicker({
                    clearBtn: true,
                    autoclose: true,
                    format: 'dd/mm/yyyy'
                  }).on('changeDate', function(e){
                      $('#jatuh_tempo_filter_value').val(e.format('yyyy-mm-dd'));
                      pencairan_bg.draw();
                  });
                });
              </script>
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
              <div class="row" id='filter_bank_tujuan'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Bank Tujuan</label>
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_bank_tujuan_filter_pencairan_bg_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>       
              <?php $this->load->view('modal/h3_md_bank_tujuan_filter_pencairan_bg_index'); ?>         
              <script>
                filter_bank_tujuan = new Vue({
                  el: '#filter_bank_tujuan',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      pencairan_bg.draw();
                    }
                  }
                });

                $("#h3_md_bank_tujuan_filter_pencairan_bg_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_rek_md = target.attr('data-id-rek-md');

                  if(target.is(':checked')){
                    filter_bank_tujuan.filters.push(id_rek_md);
                  }else{
                    index_id_rek_md = _.indexOf(filter_bank_tujuan.filters, id_rek_md);
                    filter_bank_tujuan.filters.splice(index_id_rek_md, 1);
                  }
                  h3_md_bank_tujuan_filter_pencairan_bg_index_datatable.draw();
                });
              </script>
            </div>
          </div>
        </div>
        <table id="pencairan_bg" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama Customer</th>
              <th>Jenis Pembayaran</th>
              <th>Nama Bank</th>
              <th>No. Giro</th>
              <th>Jatuh Tempo</th>
              <th>Nominal Giro</th>
              <th>Nama Bank Tujuan</th>
              <th>No Rek Tujuan</th>
              <th>Status</th>
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
                  url: "<?= base_url('api/md/h3/pencairan_bg') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.filter_customer = filter_customer.filters;
                    d.filter_bank_tujuan = filter_bank_tujuan.filters;
                    d.jatuh_tempo_filter = $('#jatuh_tempo_filter_value').val();
                    d.nama_bank_bg_filter = $('#nama_bank_bg_filter').val();
                    d.no_giro_filter = $('#no_giro_filter').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'nama_dealer' },
                    { data: 'jenis_pembayaran' },
                    { data: 'nama_bank_bg' },
                    { data: 'nomor_bg' },
                    { data: 'tanggal_jatuh_tempo_bg' },
                    { data: 'nominal_bg' },
                    { data: 'nama_bank_tujuan' },
                    { data: 'no_rekening_tujuan' },
                    { 
                      data: 'status_bg',
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

            pencairan_bg .on('draw.dt', function() {
              var info = pencairan_bg .page.info();
              pencairan_bg .column(0, {
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