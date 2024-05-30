<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
  Vue.component('v-select', VueSelect.VueSelect);
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
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" id="form_" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data Customer H23</b>
                </h4>
                <?php if($mode == 'detail'): ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Customer</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.id_customer" disabled> 
                  </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div v-bind:class="{ 'has-error': error_exist('nama_customer') }" class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.nama_customer"> 
                      <small v-if="error_exist('nama_customer')" class="form-text text-danger">{{ get_error('nama_customer') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Handphone</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_hp') }" class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.no_hp"> 
                      <small v-if="error_exist('no_hp')" class="form-text text-danger">{{ get_error('no_hp') }}</small> 
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama STNK</label>
                  <div v-bind:class="{ 'has-error': error_exist('nama_stnk') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.nama_stnk"> 
                    <small v-if="error_exist('nama_stnk')" class="form-text text-danger">{{ get_error('nama_stnk') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div v-bind:class="{ 'has-error': error_exist('email') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.email"> 
                    <small v-if="error_exist('email')" class="form-text text-danger">{{ get_error('email') }}</small> 
                  </div>
                </div>
                <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Jenis Identitas</label>
                  <div v-bind:class="{ 'has-error': error_exist('jenis_identitas') }" class="col-sm-4">
                    <select :disabled="mode=='detail'" class="form-control" v-model="customer.jenis_identitas">
                      <option value="">-Pilih-</option>
                      <option value="ktp">KTP</option>
                      <option value="sim">SIM</option>
                      <option value="kitap">KITAP</option>
                    </select>
                    <small v-if="error_exist('jenis_identitas')" class="form-text text-danger">{{ get_error('jenis_identitas') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Identitas</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_identitas') }" class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.no_identitas"> 
                      <small v-if="error_exist('no_identitas')" class="form-text text-danger">{{ get_error('no_identitas') }}</small> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.alamat"> 
                    <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_kelurahan') }" class="col-sm-4">
                    <input readonly v-model='customer.kelurahan' type="text" class="form-control" data-toggle='modal' data-target='#kelurahan_customer'>
                    <small v-if="error_exist('id_kelurahan')" class="form-text text-danger">{{ get_error('id_kelurahan') }}</small> 
                  </div>
                </div>
                <?php $this->load->view('modal/kelurahan_customer') ?>
                <script>
                  function pilih_kelurahan_customer(data){
                    form_.customer.id_kelurahan = data.id_kelurahan;
                    form_.customer.kelurahan = data.kelurahan;
                    form_.customer.id_kecamatan = data.id_kecamatan;
                    form_.customer.kecamatan = data.kecamatan;
                    form_.customer.id_kabupaten = data.id_kabupaten;
                    form_.customer.kabupaten = data.kabupaten;
                    form_.customer.id_provinsi = data.id_provinsi;
                    form_.customer.provinsi = data.provinsi;
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_tipe_kendaraan') }" class="col-sm-4">
                    <input readonly v-model='customer.id_tipe_kendaraan' type="text" class="form-control" data-toggle='modal' data-target='#tipe_kendaraan'>
                    <small v-if="error_exist('id_tipe_kendaraan')" class="form-text text-danger">{{ get_error('id_tipe_kendaraan') }}</small> 
                  </div>
                  <?php $this->load->view('modal/tipe_kendaraan') ?>
                  <script>
                    function pilih_tipe_kendaraan(data){
                      form_.customer.id_tipe_kendaraan = data.id_tipe_kendaraan;
                    }
                  </script>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                  <div class="col-sm-4">
                    <input readonly v-model='customer.kecamatan' type="text" class="form-control">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_warna') }" class="col-sm-4">
                    <input v-model='customer.warna' type="text" class="form-control" readonly data-toggle='modal' data-target='#warna_customer'>
                    <small v-if="error_exist('id_warna')" class="form-text text-danger">{{ get_error('id_warna') }}</small> 
                  </div>
                  <?php $this->load->view('modal/warna_customer') ?>
                  <script>
                    function pilih_warna_customer(data){
                      form_.customer.id_warna = data.id_warna;
                      form_.customer.warna = data.warna;
                    }
                  </script>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten</label>
                  <div class="col-sm-4">
                    <input readonly v-model='customer.kabupaten' type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Mesin</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_mesin') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.no_mesin"> 
                    <small v-if="error_exist('no_mesin')" class="form-text text-danger">{{ get_error('no_mesin') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-4">
                    <input readonly v-model='customer.provinsi' type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Rangka</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_rangka') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.no_rangka"> 
                    <small v-if="error_exist('no_rangka')" class="form-text text-danger">{{ get_error('no_rangka') }}</small> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                  <div v-bind:class="{ 'has-error': error_exist('tahun_produksi') }" class="col-sm-4">
                    <!-- <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.tahun_produksi">  -->
                    <date-picker :disabled='mode == "detail"' @update-date='year_datepicker_change' class='form-control' readonly :config='year_date_config' v-model='customer.tahun_produksi'></date-picker>
                    <small v-if="error_exist('tahun_produksi')" class="form-text text-danger">{{ get_error('tahun_produksi') }}</small> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Polisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_polisi') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.no_polisi"> 
                    <small v-if="error_exist('no_polisi')" class="form-text text-danger">{{ get_error('no_polisi') }}</small> 
                  </div>
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Nomor SPK</label>
                  <div class="col-sm-4">
                      <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.no_spk"> 
                  </div> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pembelian Motor</label>
                  <div v-bind:class="{ 'has-error': error_exist('tgl_pembelian') }" class="col-sm-4">
                    <date-picker :disabled='mode == "detail"' @update-date='tgl_pembelian_change' class='form-control' readonly :config='tgl_pembelian_config' v-model='customer.tgl_pembelian'></date-picker>
                    <small v-if="error_exist('tgl_pembelian')" class="form-text text-danger">{{ get_error('tgl_pembelian') }}</small> 
                  </div>
                </div>
                <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div v-bind:class="{ 'has-error': error_exist('email') }" class="col-sm-4">
                    <input type="text" class="form-control" :disabled="mode=='detail'" v-model="customer.email"> 
                    <small v-if="error_exist('email')" class="form-text text-danger">{{ get_error('email') }}</small> 
                  </div>
                </div> -->
              <div class="box-footer">
                <div class="col-sm-12" v-if="mode=='insert'">
                  <button @click.prevent='<?= $form ?>' type="button" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
                <div class="col-sm-12" v-if="mode=='edit'">
                  <button @click.prevent='<?= $form ?>' type="button" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                </div>
                <div v-if="mode=='detail'">
                  <div class="col-sm-6">
                    <?php if ($mode == 'detail'): ?>
                    <a v-if='auth.can_update' :href="'dealer/<?= $isi ?>/edit?k=' + customer.id_customer"><button type="button" class="btn btn-sm btn-primary btn-flat">Ubah</button></a>
                    <a v-if='auth.can_delete' :href="'dealer/<?= $isi ?>/delete?k=' + customer.id_customer"><button type="button" class="btn btn-sm btn-danger btn-flat">Hapus</button></a>
                    <?php endif; ?>
                  </div>   
                  <div class="col-sm-6 no-padding text-right">
                    <a :href="'dealer/h3_dealer_request_document/add?generateByCustomer=true&customer=' + customer.id_customer"><button type="button" class="btn btn-sm btn-info btn-flat">Request Document</button></a>
                  </div>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        auth: <?= json_encode(get_user('h3_dealer_customer_h23')) ?>, 
        mode : '<?= $mode ?>',
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        customer: <?= json_encode($customer_h23) ?>,
        <?php else: ?>
        customer: {
          nama_customer: '',
          no_identitas: '',
          jenis_identitas: '',
          no_hp: '',
          alamat: '',
          id_tipe_kendaraan: '',
          id_warna: '',
          no_mesin: '',
          no_rangka: '',
          no_polisi: '',
          id_kelurahan: '',
          kelurahan: '',
          id_kecamatan: '',
          kecamatan: '',
          id_kabupaten: '',
          kabupaten: '',
          id_provinsi: '',
          provinsi: '',
          id_warna: '',
          warna: '',
          tahun_produksi: '',
          no_spk: '',
          email: '',
          nama_stnk: '',
          tgl_pembelian: '',
        },
        <?php endif; ?>
        errors: {},
        year_date_config: {
          autoclose: true,
          format: 'yyyy',
          viewMode: 'years',
          minViewMode: 'years'
        },
        tgl_pembelian_config: {
          autoclose: true,
          format: 'yyyy-mm-dd'
        },
      },
      methods:{
        <?= $form ?> : function(){
          post = this.customer;
          if(this.mode == 'edit'){
            post.id_customer = this.customer.id_customer;
          }
          form_.loading = true;
          axios.post('dealer/h3_dealer_customer_h23/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              toastr.error(data.message);
              form_.errors = err.response.data.errors;
            }else{
              toastr.error(data.message);
            }
            
            form_.loading = false;
          });
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        },
        year_datepicker_change: function(date) {
                this.customer.tahun_produksi = date.format('yyyy-mm-dd');
        },
        tgl_pembelian_change: function(date) {
                this.customer.tgl_pembelian = date.format('yyyy-mm-dd');
        },
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <?php if(can_access('h3_dealer_customer_h23', 'can_insert')): ?>
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        <?php endif; ?>
        </h3>
      </div><!-- /.box-header -->
      <div class='box-body'>
      <?php $this->load->view('template/session_message') ?>
        <table id="customer_h23" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Customer</th>
              <th>Kabupaten / Kota</th>
              <th>Nomor Identitas</th>
              <th>Nama</th>
              <th>Nomor Polisi</th>
              <th>Email</th>
              <th>Handphone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            customer_h23 = $('#customer_h23').DataTable({
                initComplete: function() {
                  $('#customer_h23_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                  $('#customer_h23_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                  axios.get('html/filter_customer_h23')
                  .then(function(res) {
                      $('#customer_h23_filter').prepend(res.data);
                  });
                },
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/customer_h23') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                      d.id_kabupaten = $('#id_kabupaten').val();
                    }
                },
                columns: [
                    { data: null, width: '3%', orderable: false },
                    { data: 'id_customer' },
                    { data: 'domisili' },
                    { data: 'no_identitas' },
                    { data: 'nama_customer' },
                    { data: 'no_polisi' },
                    { data: 'email' },
                    { data: 'no_hp' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
            customer_h23.on('draw.dt', function() {
              var info = customer_h23.page.info();
              customer_h23.column(0, {
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