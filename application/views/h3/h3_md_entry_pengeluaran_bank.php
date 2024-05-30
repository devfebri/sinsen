<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }

      if ($mode == 'upload') {
        $form = 'inject';
      }

      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Voucher</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_voucher_pengeluaran_int') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='entry_pengeluaran.id_voucher_pengeluaran'>
                      <div class="input-group-btn">
                        <button type='button' v-if='!ada_voucher_pengeluaran || mode == "detail"' :disabled='mode == "detail"' data-toggle='modal' data-target='#h3_md_voucher_pengeluaran_entry_pengeluaran_bank' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        <button type='button' v-if='ada_voucher_pengeluaran && mode != "detail"' @click.prevent='hapus_voucher_pengeluaran' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_voucher_pengeluaran_int')" class="form-text text-danger">{{ get_error('id_voucher_pengeluaran_int') }}</small>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_voucher_pengeluaran_entry_pengeluaran_bank') ?>
                <script>
                  function pilih_voucher_pengeluaran(data){
                    app.entry_pengeluaran.id_voucher_pengeluaran_int = data.id;
                    app.entry_pengeluaran.id_voucher_pengeluaran = data.id_voucher_pengeluaran;
                  }
                </script>    
                <div v-if='entry_pengeluaran.via_bayar == "Giro"' class="form-group">
                  <label class="col-sm-2 control-label">Nominal Giro</label>
                  <div class="col-sm-4">                    
                    <vue-numeric class="form-control" disabled v-model='entry_pengeluaran.nominal_giro' thousand-separator='.' currency='Rp'></vue-numeric>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-condensed table-bordered table-striped">
                        <tr>
                          <th>No. BG</th>
                          <th>Tgl. BG</th>
                          <th>Via Pembayaran</th>
                          <th>Dibayar Kepada</th>
                          <th>Amount</th>
                          <th>Keterangan</th>
                          <th>Tgl. Cair</th>
                        </tr>
                        <tr>
                          <td>
                            <span v-if='entry_pengeluaran.kode_giro != ""'>{{ entry_pengeluaran.kode_giro }}</span>
                            <span v-if='entry_pengeluaran.kode_giro == ""'>-</span>
                          </td>
                          <td>
                            <span v-if='entry_pengeluaran.tanggal_giro != "" && entry_pengeluaran.tanggal_giro != null'>{{ moment(entry_pengeluaran.tanggal_giro).format("DD/MM/YYYY") }}</span>
                            <span v-if='entry_pengeluaran.tanggal_giro == "" || entry_pengeluaran.tanggal_giro == null'>-</span>
                          </td>
                          <td>
                            <span v-if='entry_pengeluaran.via_bayar != ""'>{{ entry_pengeluaran.via_bayar }}</span>
                            <span v-if='entry_pengeluaran.via_bayar == ""'>-</span>
                          </td>
                          <td>
                            <span v-if='entry_pengeluaran.nama_penerima_dibayarkan_kepada != ""'>{{ entry_pengeluaran.nama_penerima_dibayarkan_kepada }}</span>
                            <span v-if='entry_pengeluaran.nama_penerima_dibayarkan_kepada == ""'>-</span>
                          </td>
                          <td>
                            <vue-numeric v-model='entry_pengeluaran.total_amount' separator='.' currency='Rp' read-only></vue-numeric>
                          </td>
                          <td>
                            <span v-if='entry_pengeluaran.deskripsi != "" && entry_pengeluaran.deskripsi != null'>{{ entry_pengeluaran.deskripsi }}</span>
                            <span v-if='entry_pengeluaran.deskripsi == "" || entry_pengeluaran.deskripsi == null'>-</span>
                          </td>
                          <td width='15%'>
                            <date-picker readonly class='form-control' v-model='entry_pengeluaran.tgl_cair'></date-picker>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-6 no-padding">
                        <button v-if='mode == "edit"' :disabled='loading' class="btn btn-flat btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                        <button v-if='mode == "insert"' :disabled='loading' class="btn btn-flat btn-primary btn-sm" @click.prevent='<?= $form ?>'>Simpan</button>
                        <a v-if='mode == "detail" && entry_pengeluaran.status != "Approved"' :disabled='loading' :href="'h3/h3_md_entry_pengeluaran_bank/edit?id_entry_pengeluaran_bank=' + entry_pengeluaran.id_entry_pengeluaran_bank" class="btn btn btn-flat btn-sm btn-warning">Edit</a>
                      </div>
                      <div class="col-sm-6 text-right">
                        <button v-if='mode == "detail" && entry_pengeluaran.status == "Open"' :disabled='loading' @click.prevent='approve' class="btn btn-flat btn-sm btn-success">Approve</button>
                        <button v-if='mode == "detail" && entry_pengeluaran.status == "Open"' :disabled='loading' @click.prevent='reject' class="btn btn-flat btn-sm btn-danger">Reject</button>
                      </div>
                    </div>
                </div>
              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
   <script>
      var app = new Vue({
          el: '#app',
          data: {
            loading: false, 
            mode: '<?= $mode ?>',
            errors: [],
            <?php if($mode == 'detail' || $mode == 'edit'): ?>
            entry_pengeluaran: <?= json_encode($entry_pengeluaran) ?>,
            <?php else: ?>
            entry_pengeluaran: {
              id_voucher_pengeluaran_int: '',
              kode_giro: '',
              tanggal_giro: '',
              via_bayar: '',
              nama_penerima_dibayarkan_kepada: '',
              deskripsi: '',
              total_amount: 0,
              tgl_cair: '',
            },
            <?php endif; ?>
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
            },
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.entry_pengeluaran, [
                'id_voucher_pengeluaran_int', 'tgl_cair', 'id_entry_pengeluaran_bank'
              ]);

              this.loading = true;
              this.errors = [];
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_entry_pengeluaran_bank=' + res.data.id_entry_pengeluaran_bank;
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
            approve: function(){
              axios.get('h3/<?= $isi ?>/approve', {
                params: {
                  id_entry_pengeluaran_bank: this.entry_pengeluaran.id_entry_pengeluaran_bank
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_entry_pengeluaran_bank=' + res.data.id_entry_pengeluaran_bank;
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
            reject: function(){
              axios.get('h3/<?= $isi ?>/reject', {
                params: {
                  id_entry_pengeluaran_bank: this.entry_pengeluaran.id_entry_pengeluaran_bank
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_entry_pengeluaran_bank=' + res.data.id_entry_pengeluaran_bank;
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
            get_voucher_pengeluaran_data: function(){
              if(this.entry_pengeluaran.id_voucher_pengeluaran_int == '' || this.entry_pengeluaran.id_voucher_pengeluaran_int == null) return;

              this.loading = true;
              axios.get('h3/<?= $isi ?>/get_voucher_pengeluaran_data', {
                params: {
                  id_voucher_pengeluaran_int: this.entry_pengeluaran.id_voucher_pengeluaran_int
                }
              })
              .then(function(res){
                data = res.data;
                if(data != null){
                  app.entry_pengeluaran.kode_giro = data.kode_giro;
                  app.entry_pengeluaran.tanggal_giro = data.tanggal_giro;
                  app.entry_pengeluaran.via_bayar = data.via_bayar;
                  app.entry_pengeluaran.nama_penerima_dibayarkan_kepada = data.nama_penerima_dibayarkan_kepada;
                  app.entry_pengeluaran.deskripsi = data.deskripsi;
                  app.entry_pengeluaran.nominal_giro = data.nominal_giro;
                  app.entry_pengeluaran.total_amount = data.total_amount;
                }
              })
              .catch(function(error){
                toastr.error(error);
              })
              .then(function(){
                app.loading = false;
              });
            },
            hapus_voucher_pengeluaran: function(){
              this.entry_pengeluaran.id_voucher_pengeluaran_int = '';
              this.entry_pengeluaran.id_voucher_pengeluaran = '';
            },
            error_exist: function(key){
              return _.has(this.errors, key);
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          watch: {
            'entry_pengeluaran.id_voucher_pengeluaran_int': function(){
              app.get_voucher_pengeluaran_data();
            }
          },
          computed: {
            ada_voucher_pengeluaran: function(){
              return this.entry_pengeluaran.id_voucher_pengeluaran_int != '' && this.entry_pengeluaran.id_voucher_pengeluaran_int != null;
            },
          },
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <?php if($this->input->get('history') != null): ?>
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
          </a>  
          <?php else: ?>
          <a href="h3/<?= $isi ?>?history=true">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
          </a> 
          <?php endif; ?>
        </h3>
      </div>
      <div class="box-body">
        <table id="po_umum_datatable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Entry Pengeluaran Bank</th>              
              <th>Via Pembayaran</th>              
              <th>Dibayar Kepada</th>              
              <th>Amount</th>              
              <th>Status</th>              
              <th width="10%"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          po_umum_datatable = $('#po_umum_datatable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/entry_pengeluaran_bank') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { data: 'id_entry_pengeluaran_bank' }, 
                { data: 'via_bayar' }, 
                { 
                  data: 'nama_penerima_dibayarkan_kepada',
                  render: function(data){
                    if(data != null) return data;
                    return '-';
                  }
                }, 
                { 
                  data: 'total_amount',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                  }
                }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>