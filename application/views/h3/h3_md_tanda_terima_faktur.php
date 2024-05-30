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
                    <label for="inputEmail3" class="col-sm-2 control-label">Wilayah Penagihan</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_wilayah_penagihan') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='tanda_terima_faktur.nama_wilayah_penagihan'>
                        <div class="input-group-btn"> 
                          <button v-if='wilayah_penagihan_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_wilayah_penagihan_tanda_terima_faktur'><i class="fa fa-search"></i></button>
                          <button v-if='!wilayah_penagihan_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_wilayah_penagihan'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_wilayah_penagihan')" class="form-text text-danger">{{ get_error('id_wilayah_penagihan') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_wilayah_penagihan_tanda_terima_faktur'); ?>
                  <script>
                    function pilih_wilayah_penagihan_tanda_terima_faktur (data){
                      form_.tanda_terima_faktur.id_wilayah_penagihan = data.id;
                      form_.tanda_terima_faktur.nama_wilayah_penagihan = data.nama;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='tanda_terima_faktur.nama_dealer'>
                        <div class="input-group-btn"> 
                          <button :disabled='mode == "detail"' v-if='customer_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_tanda_terima_faktur'><i class="fa fa-search"></i></button>
                          <button v-if='!customer_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_customer'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_tanda_terima_faktur'); ?>
                  <script>
                    function pilih_dealer_tanda_terima_faktur(data){
                      form_.tanda_terima_faktur.id_dealer = data.id_dealer;
                      form_.tanda_terima_faktur.nama_dealer = data.nama_dealer;
                      form_.tanda_terima_faktur.id_wilayah_penagihan = data.id_wilayah_penagihan;
                      form_.tanda_terima_faktur.nama_wilayah_penagihan = data.nama_wilayah_penagihan;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status Faktur</label>
                    <div v-bind:class="{ 'has-error': error_exist('status_faktur') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' v-model='tanda_terima_faktur.status_faktur' class="form-control">
                        <option value="">-Pilih-</option>
                        <option value="1">Lunas</option>
                        <option value="0">Belum Lunas</option>
                      </select>
                      <small v-if="error_exist('status_faktur')" class="form-text text-danger">{{ get_error('status_faktur') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Jatuh Tempo</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date') }" class="col-sm-4">
                      <input type="text" class="form-control" id="periode_faktur" readonly="readonly">
                      <small v-if="error_exist('start_date')" class="form-text text-danger">{{ get_error('start_date') }}</small>
                    </div>
                  </div>
                  <div v-if='mode != "detail"' class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                      <button class="btn btn-flat btn-success" @click.prevent='proses_faktur'>Proses</button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Yang Menyerahkan</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_yang_menyerahkan') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='tanda_terima_faktur.nama_yang_menyerahkan'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' v-if='yang_menyerahkan_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_yang_menyerahkan_tanda_terima_faktur'><i class="fa fa-search"></i></button>
                          <button v-if='!yang_menyerahkan_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_yang_menyerahkan'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_yang_menyerahkan')" class="form-text text-danger">{{ get_error('id_yang_menyerahkan') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_yang_menyerahkan_tanda_terima_faktur'); ?>
                  <script>
                    function pilih_yang_menyerahkan_tanda_terima_faktur(data){
                      form_.tanda_terima_faktur.id_yang_menyerahkan = data.id_karyawan;
                      form_.tanda_terima_faktur.nama_yang_menyerahkan = data.nama_lengkap;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Yang Menerima</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_yang_menerima') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='tanda_terima_faktur.nama_yang_menerima'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' v-if='yang_menerima_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_yang_menerima_tanda_terima_faktur'><i class="fa fa-search"></i></button>
                          <button v-if='!yang_menerima_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_yang_menerima'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_yang_menerima')" class="form-text text-danger">{{ get_error('id_yang_menerima') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_yang_menerima_tanda_terima_faktur'); ?>
                  <script>
                    function pilih_yang_menerima_tanda_terima_faktur(data){
                      form_.tanda_terima_faktur.id_yang_menerima = data.id_karyawan;
                      form_.tanda_terima_faktur.nama_yang_menerima = data.nama_lengkap;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Disetujui Oleh</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_yang_menyetujui') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='tanda_terima_faktur.nama_yang_menyetujui'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' v-if='disetujui_oleh_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_yang_menyetujui_tanda_terima_faktur'><i class="fa fa-search"></i></button>
                          <button v-if='!disetujui_oleh_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_disetujui_oleh'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_yang_menyetujui')" class="form-text text-danger">{{ get_error('id_yang_menyetujui') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_yang_menyetujui_tanda_terima_faktur'); ?>
                  <script>
                    function pilih_yang_menyetujui_tanda_terima_faktur(data){
                      form_.tanda_terima_faktur.id_yang_menyetujui = data.id_karyawan;
                      form_.tanda_terima_faktur.nama_yang_menyetujui = data.nama_lengkap;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Rekening Bank</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_bank') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='no_rekening_dan_nama_bank'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' v-if='bank_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_bank_tanda_terima_faktur'><i class="fa fa-search"></i></button>
                          <button v-if='!bank_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_bank'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_bank')" class="form-text text-danger">{{ get_error('id_bank') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_bank_tanda_terima_faktur'); ?>
                  <script>
                    function pilih_bank_tanda_terima_faktur(data){
                      form_.tanda_terima_faktur.id_bank = data.id_rek_md;
                      form_.tanda_terima_faktur.nama_bank = data.bank;
                      form_.tanda_terima_faktur.no_rekening = data.no_rekening;
                    }
                  </script>
                  <div class="continer-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <table v-if='items.length > 0' class="table table-condensed">
                          <tr>
                            <td width='3%'>No.</td>
                            <td>No. Invoice</td>
                            <td>Tanggal Invoice</td>
                            <td>Tanggal Jatuh Tempo</td>
                            <td class='text-right'>Amount</td>
                            <td v-if='mode != "detail"' width='3%'>Aksi</td>
                          </tr>
                          <tr v-for='(item, index) of items'>
                            <td>{{ index + 1 }}.</td>
                            <td>{{ item.no_faktur }}</td>
                            <td>{{ item.tgl_faktur }}</td>
                            <td>{{ item.tgl_jatuh_tempo }}</td>
                            <td class='text-right'>
                              <vue-numeric v-model='item.total' read-only separator='.' currency='Rp'></vue-numeric>
                            </td>
                            <td v-if='mode != "detail"'>
                              <input type="checkbox" true-value='1' false-value='0' v-model='item.checked'>
                            </td>
                          </tr>
                          <tr>
                            <td colspan='4'></td>
                            <td class='text-right'>
                              <vue-numeric v-model='total' read-only separator='.' currency='Rp'></vue-numeric>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <a v-if='mode == "detail"' class='btn btn-flat btn-sm btn-primary' :href="'h3/<?= $isi ?>/cetakan_tanda_terima_faktur?id=' + tanda_terima_faktur.id">Cetakan Tanda Terima Faktur</a>
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
        tanda_terima_faktur: <?= json_encode($tanda_terima_faktur) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        tanda_terima_faktur: {
          id_dealer: '',
          nama_dealer: '',
          id_wilayah_penagihan: '',
          nama_wilayah_penagihan: '',
          status_faktur: '',
          start_date: '',
          end_date: '',
          id_yang_menyerahkan: '',
          nama_yang_menyerahkan: '',
          id_yang_menerima: '',
          nama_yang_menerima: '',
          id_yang_menerima: '',
          nama_yang_menerima: '',
          id_yang_menyetujui: '',
          nama_yang_menyetujui: '',
          id_bank: '',
          nama_bank: '',
          no_rekening: '',
        },
        items: []
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.tanda_terima_faktur, [
            'id', 'id_dealer', 'id_wilayah_penagihan',
            'status_faktur', 'start_date', 'end_date',
            'id_yang_menyerahkan', 'id_yang_menerima',
            'id_yang_menyetujui', 'id_bank'
          ]);
          post.total = this.total;
          post.items = _.chain(this.items)
          .filter(function(item){
            return item.checked == 1;
          })
          .map(function(item){
            return _.pick(item, ['no_faktur'])
          }).value();

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
        proses_faktur: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/proses_faktur', {
            params: {
              id_dealer: this.tanda_terima_faktur.id_dealer,
              status_faktur: this.tanda_terima_faktur.status_faktur,
              start_date: this.tanda_terima_faktur.start_date,
              end_date: this.tanda_terima_faktur.end_date,
            }
          })
          .then(function(res){
            form_.items = res.data;
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
          .then(function(){
            form_.loading = false;
          })
        },
        reset_wilayah_penagihan: function(){
          this.tanda_terima_faktur.id_wilayah_penagihan = null;
          this.tanda_terima_faktur.nama_wilayah_penagihan = null;
        },
        reset_customer: function(){
          this.tanda_terima_faktur.id_dealer = null;
          this.tanda_terima_faktur.nama_dealer = null;
          this.reset_wilayah_penagihan();
        },
        reset_yang_menyerahkan: function(){
          this.tanda_terima_faktur.id_yang_menyerahkan = null;
          this.tanda_terima_faktur.nama_yang_menyerahkan = null;
        },
        reset_yang_menerima: function(){
          this.tanda_terima_faktur.id_yang_menerima = null;
          this.tanda_terima_faktur.nama_yang_menerima = null;
        },
        reset_disetujui_oleh: function(){
          this.tanda_terima_faktur.id_yang_menyetujui = null;
          this.tanda_terima_faktur.nama_yang_menyetujui = null;
        },
        reset_bank: function(){
          this.tanda_terima_faktur.id_bank = null;
          this.tanda_terima_faktur.nama_bank = null;
          this.tanda_terima_faktur.no_rekening = null;
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        total: function(){
          return _.chain(this.items)
          .filter(function(item){
            return item.checked == 1;
          })
          .sumBy(function(item){
            return Number(item.total);
          }).value();
        },
        wilayah_penagihan_empty: function(){
          return this.tanda_terima_faktur.id_wilayah_penagihan == null || this.tanda_terima_faktur.id_wilayah_penagihan == '';
        },
        customer_empty: function(){
          return this.tanda_terima_faktur.id_dealer == null || this.tanda_terima_faktur.id_dealer == '';
        },
        yang_menyerahkan_empty: function(){
          return this.tanda_terima_faktur.id_yang_menyerahkan == null || this.tanda_terima_faktur.id_yang_menyerahkan == '';
        },
        yang_menerima_empty: function(){
          return this.tanda_terima_faktur.id_yang_menerima == null || this.tanda_terima_faktur.id_yang_menerima == '';
        },
        disetujui_oleh_empty: function(){
          return this.tanda_terima_faktur.id_yang_menyetujui == null || this.tanda_terima_faktur.id_yang_menyetujui == '';
        },
        bank_empty: function(){
          return this.tanda_terima_faktur.id_bank == null || this.tanda_terima_faktur.id_bank == '';
        },
        no_rekening_dan_nama_bank: function(){
          if(this.tanda_terima_faktur.id_bank == null || this.tanda_terima_faktur.id_bank == '') return null;

          return this.tanda_terima_faktur.no_rekening + " - " + this.tanda_terima_faktur.nama_bank;
        }
      },
      watch: {
        'tanda_terima_faktur.id_wilayah_penagihan': function(){
          h3_md_dealer_tanda_terima_faktur_datatable.draw();
        }
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
          config.startDate = new Date(this.tanda_terima_faktur.start_date);
          config.endDate = new Date(this.tanda_terima_faktur.end_date);
        }
        $('#periode_faktur').daterangepicker(config, function(start, end, label) {
          form_.tanda_terima_faktur.start_date = start.format('YYYY-MM-DD');
          form_.tanda_terima_faktur.end_date = end.format('YYYY-MM-DD');
        }).on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
          form_.tanda_terima_faktur.start_date = '';
          form_.tanda_terima_faktur.end_date = '';
        });
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
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_tanda_terima_faktur_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_tanda_terima_faktur_index'); ?>         
                <script>
                function pilih_dealer_filter_tanda_terima_faktur_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_customer_filter').val(data.nama_dealer);
                    $('#id_customer_filter').val(data.id_dealer);
                  }else if(type == 'reset_filter'){
                    $('#nama_customer_filter').val('');
                    $('#id_customer_filter').val('');
                  }
                  tanda_terima_faktur.draw();
                  h3_md_dealer_filter_tanda_terima_faktur_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No Tanda Terima Faktur</label>
                  <div class="col-sm-8">
                    <input id='no_tanda_terima_faktur_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#no_tanda_terima_faktur_filter').on("keyup", _.debounce(function(){
                      tanda_terima_faktur.draw();
                    }, 500));
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Tanggal Jatuh Tempo</label>
                  <div class="col-sm-8">
                    <input id='tanggal_jatuh_tempo_filter' type="text" class="form-control" readonly>
                    <input id='tanggal_jatuh_tempo_filter_start' type="hidden" disabled>
                    <input id='tanggal_jatuh_tempo_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#tanggal_jatuh_tempo_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#tanggal_jatuh_tempo_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#tanggal_jatuh_tempo_filter_end').val(end.format('YYYY-MM-DD'));
                    tanda_terima_faktur.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#tanggal_jatuh_tempo_filter_start').val('');
                    $('#tanggal_jatuh_tempo_filter_end').val('');
                    tanda_terima_faktur.draw();
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No Faktur</label>
                  <div class="col-sm-8">
                    <input id='no_faktur_filter' type="text" class="form-control">
                  </div>
                </div>                
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_faktur_filter').on("keyup", _.debounce(function(){
                    tanda_terima_faktur.draw();
                  }, 500));
                });
              </script>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Wilayah Penagihan</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='wilayah_penagihan_filter' type="text" class="form-control" disabled>
                      <input id='id_wilayah_penagihan_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_wilayah_penagihan_filter_tanda_terima_faktur_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>                
                <?php $this->load->view('modal/h3_md_wilayah_penagihan_filter_tanda_terima_faktur_index'); ?>         
                <script>
                function pilih_wilayah_penagihan_filter_tanda_terima_faktur_index(data, type) {
                  if(type == 'add_filter'){
                    $('#wilayah_penagihan_filter').val(data.nama);
                    $('#id_wilayah_penagihan_filter').val(data.id);
                  }else if(type == 'reset_filter'){
                    $('#wilayah_penagihan_filter').val('');
                    $('#id_wilayah_penagihan_filter').val('');
                  }
                  tanda_terima_faktur.draw();
                  h3_md_wilayah_penagihan_filter_tanda_terima_faktur_index_datatable.draw();
                }
                </script>
              </div>
            </div>
          </form>
        </div>
        <table id="tanda_terima_faktur" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>No Tanda Terima Faktur</th>
              <th>Nama Customer</th>
              <th>Tgl Awal Jatuh Tempo</th>
              <th>Tgl Akhir Jatuh Tempo</th>
              <th>Amount</th>
              <th>Wilayah Penagihan</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            tanda_terima_faktur = $('#tanda_terima_faktur').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/tanda_terima_faktur') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.id_customer_filter = $('#id_customer_filter').val();
                    d.no_tanda_terima_faktur_filter = $('#no_tanda_terima_faktur_filter').val();
                    d.tanggal_jatuh_tempo_filter_start = $('#tanggal_jatuh_tempo_filter_start').val();
                    d.tanggal_jatuh_tempo_filter_end = $('#tanggal_jatuh_tempo_filter_end').val();
                    d.no_faktur_filter = $('#no_faktur_filter').val();
                    d.id_wilayah_penagihan_filter = $('#id_wilayah_penagihan_filter').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'no_tanda_terima_faktur' },
                    { data: 'nama_dealer' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'total' },
                    { data: 'nama_wilayah_penagihan', name: 'wp.nama' },
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