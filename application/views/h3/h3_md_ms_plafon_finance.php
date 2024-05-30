<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/jquery.min.js") ?>"></script>
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
        <?php $this->load->view('template/normal_session_message'); ?>
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='plafon.nama_dealer'>
                        <div class="input-group-btn">
                          <button :disabled='mode != "insert"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_plafon'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_plafon'); ?>
                    <script>
                      function pilih_dealer_plafon(data) {
                        form_.plafon.id_dealer = data.id_dealer;
                        form_.plafon.nama_dealer = data.nama_dealer;
                        form_.plafon.alamat = data.alamat;
                        form_.plafon.status_dealer = data.status_dealer;
                        form_.plafon.luas_bangunan = data.luas_bangunan;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Plafon Awal</label>
                    <div class="col-sm-4">
                      <vue-numeric readonly v-model='plafon.plafon_awal' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat Customer</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='plafon.alamat'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                    <div class="col-sm-4">
                      <vue-numeric v-if='mode == "detail"' readonly v-model='plafon.sisa_plafon' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <vue-numeric v-if='mode != "detail"' readonly v-model='sisa_plafon' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status Toko</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='plafon.status_dealer'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nilai Penambahan Plafon</label>
                    <div v-bind:class="{ 'has-error': error_exist('nilai_penambahan_plafon') }" class="col-sm-4">
                      <vue-numeric :readonly='mode == "detail"' v-model='plafon.nilai_penambahan_plafon_finance' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('nilai_penambahan_plafon')" class="form-text text-danger">{{ get_error('nilai_penambahan_plafon') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Luas Bangunan</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='plafon.luas_bangunan'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nilai Penambahan Sementara</label>
                    <div v-bind:class="{ 'has-error': error_exist('nilai_penambahan_sementara') }" class="col-sm-4">
                      <vue-numeric :readonly='mode == "detail"' v-model='plafon.nilai_penambahan_sementara_finance' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('nilai_penambahan_sementara')" class="form-text text-danger">{{ get_error('nilai_penambahan_sementara') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Salesman</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='plafon.nama_salesman'>
                        <div class="input-group-btn">
                          <button :disabled='mode != "insert"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_salesman_plafon'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('nama')" class="form-text text-danger">{{ get_error('nama') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_salesman_plafon'); ?>
                    <script>
                      function pilih_salesman_plafon(data) {
                        form_.plafon.id_salesman = data.id_salesman;
                        form_.plafon.nama_salesman = data.nama_lengkap;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nilai Pengurang Plafon</label>
                    <div v-bind:class="{ 'has-error': error_exist('nilai_pengurang_plafon') }" class="col-sm-4">
                      <vue-numeric :readonly='mode == "detail"' v-model='plafon.nilai_pengurang_plafon_finance' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('nilai_pengurang_plafon')" class="form-text text-danger">{{ get_error('nilai_pengurang_plafon') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div v-bind:class="{ 'has-error': error_exist('keterangan_finance') }" class="col-sm-4">
                      <textarea rows="5" v-model='plafon.keterangan_finance' :disabled='mode == "detail"' class="form-control"></textarea>
                      <small v-if="error_exist('keterangan_finance')" class="form-text text-danger">{{ get_error('keterangan_finance') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total Plafon Baru</label>
                    <div v-bind:class="{ 'has-error': error_exist('kontribusi') }" class="col-sm-4">
                      <vue-numeric readonly v-model='total_plafon_baru' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('kontribusi')" class="form-text text-danger">{{ get_error('kontribusi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Nilai PO - Part</label>
                    <div v-bind:class="{ 'has-error': error_exist('kontribusi') }" class="col-sm-4">
                      <vue-numeric readonly v-model='nilai_po_part' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('kontribusi')" class="form-text text-danger">{{ get_error('kontribusi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Nilai PO - Oli</label>
                    <div v-bind:class="{ 'has-error': error_exist('kontribusi') }" class="col-sm-4">
                      <vue-numeric readonly v-model='nilai_po_oli' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('kontribusi')" class="form-text text-danger">{{ get_error('kontribusi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Grand Total Nilai PO</label>
                    <div v-bind:class="{ 'has-error': error_exist('kontribusi') }" class="col-sm-4">
                      <vue-numeric readonly v-model='grand_total_nilai_po' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                      <small v-if="error_exist('kontribusi')" class="form-text text-danger">{{ get_error('kontribusi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Nilai Kekurangan Plafon</label>
                    <div class="col-sm-4">
                      <vue-numeric readonly v-model='total_sales_order_potong_sisa_plafon' thousand-separator='.' currency='Rp ' class='form-control'></vue-numeric>
                    </div>
                  </div>
                </div>
                <div class="container-fluid">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-6">
                        <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                        <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                        <a v-if='mode == "detail" && plafon.status == "Approved by Admin"' :href="'h3/<?= $isi ?>/edit?id=' + plafon.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                      </div>
                      <div class="col-sm-6 text-right">
                        <button v-if='mode == "detail" && plafon.status == "Approved by Admin"' class="btn btn-flat btn-sm btn-success" @click.prevent='approve'>Approve</button>
                        <button v-if='mode == "detail" && plafon.status == "Approved by Admin"' class="btn btn-flat btn-sm btn-danger" @click.prevent='close'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('h3/h3_md_detail_pengajuan_plafon'); ?>
                <table class="table table-condensed table-bordered">
                      <tr class='bg-blue-gradient'>
                        <td colspan='6' class='text-center'>Detail Tagihan</td>
                      </tr>
                      <tr>
                          <td width='8%' class='text-center'>No.</td>
                          <td class='text-center'>No Faktur</td>
                          <td class='text-center'>Tgl Jatuh Tempo</td>
                          <td class='text-center'>Produk</td>
                          <td class='text-center'>Nilai Faktur</td>
                          <td width='10%' class='text-center'>Rincian Pembayaran</td>
                      </tr>
                      <tr v-if='tagihan.length > 0' v-for='(each, index) of tagihan'>
                        <td class='text-center'>{{ index + 1 }}.</td>
                        <td>{{ each.no_faktur }}</td>
                        <td>{{ moment(each.tgl_jatuh_tempo).format('DD/MM/YYYY') }}</td>
                        <td>{{ each.produk }}</td>
                        <td>
                          <vue-numeric v-model='each.nilai_faktur' thousand-separator='.' currency='Rp ' :read-only='true'/>
                        </td>
                        <td class='text-center'>
                          <button class="btn btn-flat btn-info btn-xs" @click.prevent='open_rincian_pembayaran(each.no_faktur)'>View</button>
                        </td>
                      </tr>
                      <tr v-if='tagihan.length > 0'>
                        <td class='text-center'>Grand total</td>
                        <td colspan='5'>
                          <vue-numeric :read-only='true' v-model='total_tagihan' thousand-separator='.' currency='Rp '>
                        </td>
                      </tr>
                      <tr v-if='tagihan.length < 1'>
                        <td colspan='6' class='text-center'>Tidak ada data.</td>
                      </tr>
                </table>
                <!-- Modal -->
                <div id="rincian_pembayaran_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                              <h4 class="modal-title" id="myModalLabel">Rincian Pembayaran</h4>
                          </div>
                          <div class="modal-body">
                              <table class="table table-condensed">
                                <tr>
                                  <td width='3%'>No.</td>
                                  <td>Jenis Pembayaran</td>
                                  <td>Nomor BG</td>
                                  <td>Tanggal Jatuh Tempo BG</td>
                                  <td>Nominal</td>
                                </tr>
                                <tr v-if='rincian_pembayaran.length > 0' v-for='(each, index) of rincian_pembayaran'>
                                  <td width='3%'>{{ index + 1 }}.</td>
                                  <td>{{ each.jenis_pembayaran }}</td>
                                  <td>
                                    <span v-if='each.nomor_bg != ""'>{{ each.nomor_bg }}</span>
                                    <span v-if='each.nomor_bg == ""'>-</span>
                                  </td>
                                  <td>
                                    <span v-if='each.tanggal_jatuh_tempo_bg != "00-00-0000"'>{{ moment(each.tanggal_jatuh_tempo_bg).format('DD/MM/YYYY') }}</span>
                                    <span v-if='each.tanggal_jatuh_tempo_bg == "00-00-0000"'>-</span>
                                  </td>
                                  <td>
                                    <vue-numeric read-only class="form-control" v-model='each.nominal' thousand-separator='.' currency='Rp '></vue-numeric>
                                  </td>
                                </tr>
                              </table>
                          </div>
                        </div>
                    </div>
                </div>
                <table class="table-condensed table">
                  <tr class='bg-blue-gradient'>
                    <td colspan='8' class='text-center'>Detail Sales Order</td>
                  </tr>
                  <tr>
                    <td>No.</td>
                    <td>Nomor SO</td>
                    <td>Tanggal SO</td>
                    <td>Tipe Penjualan</td>
                    <td>Kategori</td>
                    <td>Produk</td>
                    <td>Amount</td>
                    <td width='3%'></td>
                  </tr>
                  <tr v-if='sales_orders.length > 0' v-for='(sales_order, index) of sales_orders'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ sales_order.id_sales_order }}</td>
                    <td>{{ sales_order.tanggal_order }}</td>
                    <td>{{ sales_order.po_type }}</td>
                    <td>{{ sales_order.kategori_po }}</td>
                    <td>{{ sales_order.produk }}</td>
                    <td>
                      <vue-numeric read-only v-model='sales_order.total_amount' thousand-separator='.' currency='Rp'></vue-numeric>
                    </td>
                    <td>
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='sales_order.checked'>
                    </td>
                  </tr>
                  <tr v-if='sales_orders.length < 1'>
                    <td colspan='8' class='text-center'>Tidak ada data</td>
                  </tr>
                </table>
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
        plafon: <?= json_encode($plafon) ?>,
        sales_orders: <?= json_encode($sales_orders) ?>,
        tagihan: [],
        <?php else: ?>
        plafon: {
          nama_dealer: '',
          id_dealer: '',
          alamat: '',
          status_dealer: '',
          luas_bangunan: '',
          id_salesman: '',
          nama_salesman: '',
          plafon_awal: 0,
          plafon_booking: 0,
          nilai_penambahan_plafon: 0,
          nilai_penambahan_sementara: 0,
          nilai_pengurang_plafon: 0,
          total_plafon_baru: 0,
          nilai_po_part: 0,
          nilai_po_oli: 0,
        },
        tagihan: [],
        sales_orders: [],
        <?php endif; ?>
        rincian_pembayaran: []
      },
      mounted: function(){
        if(this.mode != 'insert'){
          this.get_tagihan();
        }
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.plafon, [
            'id_dealer', 'id_salesman','plafon_awal', 'plafon_booking','nilai_penambahan_plafon','nilai_penambahan_sementara','nilai_pengurang_plafon',
            'nilai_penambahan_plafon_finance','nilai_penambahan_sementara_finance','nilai_pengurang_plafon_finance',
			      'nilai_penambahan_plafon_pimpinan','nilai_penambahan_sementara_pimpinan','nilai_pengurang_plafon_pimpinan',
            'total_plafon_baru','keterangan', 'keterangan_finance'
          ]);

          post.nilai_po_part = this.nilai_po_part;
          post.nilai_po_oli = this.nilai_po_oli;
          post.sisa_plafon = this.sisa_plafon;
          post.total_plafon_baru = this.total_plafon_baru;

          post.sales_orders = _.map(this.sales_orders, function(sales_order){
            return _.pick(sales_order, ['id_sales_order', 'total_amount', 'checked']);
          });

          if(this.mode == 'edit'){
            post.id = this.plafon.id;
          }

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;

            if(data.redirect_url != null) window.location = data.redirect_url;
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
        get_tagihan: function(){
          this.loading = true;
          axios.get('h3/h3_md_ms_plafon/get_tagihan', {
            params: {
              id_dealer: this.plafon.id_dealer
            }
          })
          .then(function(res){
            form_.tagihan = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_sales_orders: function(){
          this.loading = true;
          axios.get('h3/h3_md_ms_plafon/get_sales_orders', {
            params: {
              id_dealer: this.plafon.id_dealer
            }
          })
          .then(function(res){
            form_.sales_orders = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_plafon_awal: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/get_plafon_awal', {
            params: {
              id_dealer: this.plafon.id_dealer
            }
          })
          .then(function(res){
            form_.plafon.plafon_awal = res.data.plafon_h3;
            form_.plafon.plafon_booking = res.data.plafon_booking;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_nilai_po_part: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/get_nilai_po_part', {
            params: {
              id_dealer: this.plafon.id_dealer
            }
          })
          .then(function(res){
            form_.plafon.nilai_po_part = res.data.amount;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_nilai_po_oli: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/get_nilai_po_oli', {
            params: {
              id_dealer: this.plafon.id_dealer
            }
          })
          .then(function(res){
            form_.plafon.nilai_po_oli = res.data.amount;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        approve: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/approve', {
            params: {
              id: this.plafon.id
            }
          })
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        close: function(){
          if(
            !confirm("Apakah anda yakin ingin meng-close Pengajuan Plafon ini?")
          ) return true;

          this.loading = true;
          axios.get('h3/<?= $isi ?>/close', {
            params: {
              id: this.plafon.id
            }
          })
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id=' + res.data.id;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        open_rincian_pembayaran: function(no_faktur){
          this.get_rincian_pembayaran(no_faktur);
          $('#rincian_pembayaran_modal').modal('show');
        },
        get_rincian_pembayaran: function(no_faktur){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/get_rincian_pembayaran', {
            params: {
              no_faktur: no_faktur
            }
          })
          .then(function(res){
            form_.rincian_pembayaran = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          })
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        'plafon.id_dealer': function(n, o){
          this.get_tagihan();
          this.get_sales_orders();
          this.get_plafon_awal();
          this.get_nilai_po_part();
          this.get_nilai_po_oli();

          h3_md_salesman_plafon_datatable.draw();
        }
      },
      computed: {
        total_tagihan: function(){
          return _.sumBy(this.tagihan, function(e){
            return e.nilai_faktur;
          });
        },
        total_sales_order: function(){
          return _.chain(this.sales_orders)
          .filter(function(data){
            return data.checked == 1;
          })
          .sumBy(function(e){
            return e.total_amount;
          });
        },
        total_sales_order_potong_sisa_plafon: function(){
          result =  this.total_sales_order - this.sisa_plafon;
          if(result > 0){
            return result;
          }
          return 0;
        },
        sisa_plafon: function(){
          return this.plafon.plafon_awal - this.total_tagihan - this.plafon.plafon_booking;
        },
        total_plafon_baru: function(){
          return parseInt(this.sisa_plafon) + parseInt(this.plafon.nilai_penambahan_plafon_finance) + parseInt(this.plafon.nilai_penambahan_sementara_finance) - parseInt(this.plafon.nilai_pengurang_plafon_finance);
        },
        nilai_po_part: function(){
          return _.chain(this.sales_orders)
          .filter(function(data){
            return data.checked == 1;
          })
          .filter(function(data){
            return data.produk == 'Parts';
          })
          .sumBy(function(data){
            return data.total_amount;
          })
          .value();
        },
        nilai_po_oli: function(){
          return _.chain(this.sales_orders)
          .filter(function(data){
            return data.checked == 1;
          })
          .filter(function(data){
            return data.produk == 'Oil';
          })
          .sumBy(function(data){
            return data.total_amount;
          })
          .value();
        },
        grand_total_nilai_po: function(){
          return parseInt(this.nilai_po_part) + parseInt(this.nilai_po_oli);
        },
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
          <div class="btn-group">
            <button type="button" class="btn btn-success">Download</button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="caret"></span>
              <span class="sr-only">Download</span>
            </button>
            <ul class="dropdown-menu">
              <li><a id='memo_excel' href="h3/<?= $isi ?>/memo?filetype=excel">Excel</a></li>
              <li><a id='memo_pdf' href="h3/<?= $isi ?>/memo?filetype=pdf">PDF</a></li>
            </ul>
          </div>
        </h3>
      </div><!-- /.box-header -->
      <script>
        $(document).ready(function(){
          $('#memo_excel').on('click', function(e){
            e.preventDefault();
            $('#filetype_memo').val('excel');
            $('#tanda_tangan_memo_plafon').modal('show');
          });

          $('#memo_pdf').on('click', function(e){
            e.preventDefault();
            $('#filetype_memo').val('pdf');
            $('#tanda_tangan_memo_plafon').modal('show');
          });
        });
      </script>
      <?php $this->load->view('modal/tanda_tangan_memo_plafon'); ?>
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message'); ?>
        <table id="master_plafon" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th></th>
              <th>No.</th>
              <th>Kode Customer</th>
              <th>Nama Customer</th>
              <th>Status Toko</th>
              <th>Plafon Awal</th>
              <th>Sisa Plafon</th>
              <th>Nilai Penambahan Plafon</th>
              <th>Nilai Penambahan Plafon Sementara</th>
              <th>Nilai Pengurang</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_plafon = $('#master_plafon').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_plafon_finance') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'checkbox', orderable: false, className: 'text-center' },
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'kode_dealer_md' },
                    { data: 'nama_dealer' },
                    { data: 'status_dealer' },
                    { 
                      data: 'plafon_awal',
                      width: '130px',
                      render: function(data){
                        if(data != null){
                          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                        }
                        return data;
                      }
                    },
                    { 
                      data: 'sisa_plafon',
                      width: '130px',
                      render: function(data){
                        if(data != null){
                          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                        }
                        return data;
                      }
                    },
                    { 
                      data: 'nilai_penambahan_plafon_finance',
                      width: '130px',
                      render: function(data){
                        if(data != null){
                          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                        }
                        return data;
                      } 
                    },
                    { 
                      data: 'nilai_penambahan_sementara_finance',
                      width: '130px',
                      render: function(data){
                        if(data != null){
                          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                        }
                        return data;
                      }
                    },
                    { 
                      data: 'nilai_pengurang_plafon_finance',
                      width: '130px',
                      render: function(data){
                        if(data != null){
                          return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                        }
                        return data;
                      }
                    },
                    { 
                      data: 'keterangan',
                      width: '200px',
                    },
                    { 
                      data: 'status',
                      width: '120px',
                    },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            $('#master_plafon').on('click', 'input[type=checkbox].plafon-checkbox', function(e){
              checked = $(e.target).is(':checked');
              id = $(e.target).data('id');
              if(checked){
                axios.get("<?= base_url('api/md/h3/master_plafon_finance/set_plafon_id') ?>", {
                  params: {
                    id: id
                  }
                })
                .then(function(res){ master_plafon.draw(); });
              }else{
                axios.get("<?= base_url('api/md/h3/master_plafon_finance/unset_plafon_id') ?>", {
                  params: {
                    id: id
                  }
                })
                .then(function(res){ master_plafon.draw(); });
              }
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>