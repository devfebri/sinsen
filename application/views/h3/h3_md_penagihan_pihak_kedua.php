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
                  <div v-bind:class="{ 'has-error': error_exist('referensi') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='penagihan.referensi'>
                      <div class="input-group-btn">
                        <button type='button' v-if='!ada_referensi || mode == "detail"' :disabled='mode == "detail"' data-toggle='modal' data-target='#h3_md_voucher_pengeluaran_penagihan_pihak_kedua' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        <button type='button' v-if='ada_referensi && mode != "detail"' @click.prevent='hapus_referensi' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('referensi')" class="form-text text-danger">{{ get_error('referensi') }}</small>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_voucher_pengeluaran_penagihan_pihak_kedua') ?>
                <script>
                  function pilih_referensi(data){
                    app.penagihan.referensi_int = data.id;
                    app.penagihan.referensi = data.id_voucher_pengeluaran;
                    app.penagihan.tipe_referensi = data.tipe_referensi;
                    app.penagihan.nama_vendor = data.nama_penerima_dibayarkan_kepada;
                    app.penagihan.nominal_pembayaran = data.total_amount;
                    app.penagihan.nomor_bg = data.no_giro;
                    app.penagihan.nominal_bg = data.nominal_giro;
                    app.penagihan.divisi = data.divisi;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Vendor</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model='penagihan.nama_vendor'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nominal Pembayaran</label>
                  <div class="col-sm-4">                    
                    <vue-numeric class="form-control" v-model='penagihan.nominal_pembayaran' currency='Rp' thousand-separator='.' disabled></vue-numeric>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No. Giro</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model='penagihan.nomor_bg'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nominal Giro</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model='penagihan.nominal_bg'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Divisi</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model='penagihan.divisi'>
                  </div>
                </div>
                <div v-bind:class="{ 'has-error': error_exist('no_surat') }" class="form-group">                  
                  <label class="col-sm-2 control-label">No. Surat</label>
                  <div class="col-sm-4">                    
                    <input :disabled='mode == "detail" || loading' type="text" class="form-control" v-model='penagihan.no_surat'>
                    <small v-if="error_exist('no_surat')" class="form-text text-danger">{{ get_error('no_surat') }}</small>
                  </div>
                </div>
                <div v-bind:class="{ 'has-error': error_exist('tgl_surat') }" class="form-group">                  
                  <label class="col-sm-2 control-label">Tgl Surat</label>
                  <div class="col-sm-4">                    
                    <date-picker :disabled='mode == "detail" || loading' v-model='penagihan.tgl_surat' class='form-control' readonly></date-picker>
                    <small v-if="error_exist('tgl_surat')" class="form-text text-danger">{{ get_error('tgl_surat') }}</small>
                  </div>
                </div>
                <div v-bind:class="{ 'has-error': error_exist('tgl_jatuh_tempo') }" class="form-group">                  
                  <label class="col-sm-2 control-label">Tgl Jatuh Tempo</label>
                  <div class="col-sm-4">                    
                    <date-picker :disabled='mode == "detail" || loading' v-model='penagihan.tgl_jatuh_tempo' class='form-control' readonly></date-picker>
                    <small v-if="error_exist('tgl_jatuh_tempo')" class="form-text text-danger">{{ get_error('tgl_jatuh_tempo') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nominal Tagihan</label>
                  <div class="col-sm-4">                    
                    <vue-numeric :disabled='mode == "detail" || loading' currency='Rp' thousand-separator='.' class="form-control" v-model='penagihan.nominal'></vue-numeric>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">                    
                    <textarea :disabled='mode == "detail" || loading' v-model='penagihan.keterangan' rows="5" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <h3>Tujuan Penagihan</h3>
                        <table class="table table-condensed table-striped table-hover">
                          <tr>
                            <th width='3%'>No.</th>
                            <th>Kode Vendor</th>
                            <th>Nama Vendor</th>
                            <th v-if='mode != "detail"' width='3%'></th>
                          </tr>
                          <tr v-if='penagihan_tujuan.length > 0' v-for='(row, index) in penagihan_tujuan'>
                            <td>{{ index + 1 }}.</td>
                            <td>{{ row.id_vendor }}</td>
                            <td>{{ row.vendor_name}}</td>
                            <td v-if='mode != "detail"'>
                              <button @click.prevent='hapus_vendor(index)' :disabled='loading' class="btn btn-flat btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </td>
                          </tr>
                          <tr v-if='penagihan_tujuan.length < 1'>
                            <td colspan='4' class='text-center'>Tidak ada data</td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div v-if='mode != "detail"' class="row">
                      <div class="col-sm-12 text-right">
                        <button type='button' data-toggle='modal' data-target='#h3_md_vendor_penagihan_pihak_kedua' class="btn btn-flat btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_vendor_penagihan_pihak_kedua') ?>
                <script>
                  function pilih_vendor(data){
                    app.penagihan_tujuan.push({
                      id_vendor: data.id_vendor,
                      vendor_name: data.vendor_name,
                    });
                    h3_md_vendor_penagihan_pihak_kedua_datatable.draw();
                  }
                </script>
                <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-6 no-padding">
                        <button v-if='mode == "edit"' :disabled='loading' class="btn btn-flat btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                        <button v-if='mode == "insert"' :disabled='loading' class="btn btn-flat btn-primary btn-sm" @click.prevent='<?= $form ?>'>Simpan</button>
                        <a v-if='mode == "detail" && penagihan.status != "Approved"' :href="'h3/h3_md_penagihan_pihak_kedua/edit?id=' + penagihan.id" class="btn btn btn-flat btn-sm btn-warning">Edit</a>
                      </div>
                      <div class="col-sm-6 text-right">
                        <button v-if='mode == "detail" && penagihan.status == "Open"' @click.prevent='approve' class="btn btn-flat btn-sm btn-success">Approve</button>
                        <button v-if='mode == "detail" && penagihan.status == "Open"' @click.prevent='cancel' class="btn btn-flat btn-sm btn-danger">Cancel</button>
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
            penagihan: <?= json_encode($penagihan) ?>,
            penagihan_tujuan: <?= json_encode($penagihan_tujuan) ?>,
            <?php else: ?>
            penagihan: {
              referensi_int: '',
              referensi: '',
              tipe_referensi: '',
              nama_vendor: '',
              nominal_pembayaran: '',
              nomor_bg: '',
              nominal_bg: '',
              divisi: '',
              no_surat: '',
              tgl_surat: '',
              tgl_jatuh_tempo: '',
              nominal: 0,
              keterangan: '',
            },
            penagihan_tujuan: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              post = this.penagihan;
              post.penagihan_tujuan = this.penagihan_tujuan;

              this.loading = true;
              this.errors = [];
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
                  toastr.error(data.message);
                }

                app.loading = false;
              });
            },
            approve: function(){
              axios.get('h3/<?= $isi ?>/approve', {
                params: {
                  id: this.penagihan.id
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
              })
              .then(function(){ app.loading = false; });
            },
            cancel: function(){
              axios.get('h3/<?= $isi ?>/cancel', {
                params: {
                  id: this.penagihan.id
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
              })
              .then(function(){ app.loading = false; });
            },
            hapus_vendor: function(index){
              this.penagihan_tujuan.splice(index, 1);
              h3_md_vendor_penagihan_pihak_kedua_datatable.draw();
            },
            hapus_referensi: function(){
              this.penagihan.referensi = '';
              this.penagihan.tipe_referensi = '';
              this.penagihan.nama_vendor = '';
              this.penagihan.nominal_pembayaran = 0;
              this.penagihan.nomor_bg = '';
              this.penagihan.nominal_bg = '';
              this.penagihan.divisi = '';
            },
            error_exist: function(key){
              return _.has(this.errors, key);
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            ada_referensi: function(){
              return this.penagihan.referensi != '' && this.penagihan.referensi != null;
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
        <table id="penagihan_datatable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Surat</th>              
              <th>Tgl. Enty</th>              
              <th>Tgl. Surat</th>              
              <th>Nama Tujuan Penagihan</th>              
              <th>Referensi</th>              
              <th>Nominal</th>              
              <th>Status</th>              
              <th width="10%"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          penagihan_datatable = $('#penagihan_datatable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/penagihan_pihak_kedua') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { data: 'no_surat' }, 
                { 
                  data: 'created_at',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { 
                  data: 'tgl_surat',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                },
                { data: 'nama_tujuan_penagihan' }, 
                { data: 'referensi' }, 
                { 
                  data: 'nominal',
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