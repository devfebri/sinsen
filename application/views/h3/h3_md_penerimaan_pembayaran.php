<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>"/>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Group Dealer</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_group_dealer') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='penerimaan_pembayaran.nama_group_dealer'>
                        <div class="input-group-btn">
                          <button v-if='!group_dealer_terpilih || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_group_dealer_penerimaan_pembayaran'><i class="fa fa-search"></i></button>
                          <button v-if='group_dealer_terpilih && mode != "detail"' @click.prevent='hapus_group_dealer' class="btn btn-flat btn-danger" type='button'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_group_dealer')" class="form-text text-danger">{{ get_error('id_group_dealer') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_group_dealer_penerimaan_pembayaran'); ?>
                    <script>
                      function pilih_group_dealer_penerimaan_pembayaran (data){
                        form_.penerimaan_pembayaran.id_group_dealer = data.id_group_dealer;
                        form_.penerimaan_pembayaran.nama_group_dealer = data.group_dealer;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal BAP</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_bap') }" class="col-sm-4">
                      <date-picker readonly :disabled='mode == "detail"' v-model='penerimaan_pembayaran.tanggal_bap' class='form-control'></date-picker>
                      <small v-if="error_exist('tanggal_bap')" class="form-text text-danger">{{ get_error('tanggal_bap') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='penerimaan_pembayaran.nama_dealer'>
                        <div class="input-group-btn">
                          <button v-if='!customer_terpilih || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_penerimaan_pembayaran'><i class="fa fa-search"></i></button>
                          <button v-if='customer_terpilih && mode != "detail"' @click.prevent='hapus_customer' class="btn btn-flat btn-danger" type='button'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_penerimaan_pembayaran'); ?>
                    <script>
                      function pilih_dealer_penerimaan_pembayaran(data){
                        form_.penerimaan_pembayaran.id_dealer = data.id_dealer;
                        form_.penerimaan_pembayaran.nama_dealer = data.nama_dealer;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Debt Collector</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_debt_collector') }" class="col-sm-4">
                      <div class="input-group">
                        <input disabled type="text" class="form-control" v-model='penerimaan_pembayaran.nama_debt_collector'>
                        <div class="input-group-btn">
                          <button v-if='!debt_collector_terpilih || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_debt_collector_penerimaan_pembayaran'><i class="fa fa-search"></i></button>
                          <button v-if='debt_collector_terpilih && mode != "detail"' @click.prevent='hapus_debt_collector' class="btn btn-flat btn-danger" type='button'><i class="fa fa-trash-o"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_debt_collector')" class="form-text text-danger">{{ get_error('id_debt_collector') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_debt_collector_penerimaan_pembayaran'); ?>
                    <script>
                      function pilih_debt_collector_penerimaan_pembayaran(data){
                        form_.penerimaan_pembayaran.id_debt_collector = data.id_karyawan;
                        form_.penerimaan_pembayaran.nama_debt_collector = data.nama_lengkap;
                        h3_md_dealer_penerimaan_pembayaran_datatable.draw();
                      }
                    </script>
                  </div>
                  <div v-if='mode != "detail"' class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-flat btn-success" @click.prevent='proses_faktur'>Generate Faktur</button>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Proses</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_proses') }" class="col-sm-3">
                      <date-picker readonly :disabled='mode == "detail"' v-model='penerimaan_pembayaran.tanggal_proses' class='form-control'></date-picker>
                      <small v-if="error_exist('tanggal_proses')" class="form-text text-danger">{{ get_error('tanggal_proses') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Pembayaran</label>
                    <div v-bind:class="{ 'has-error': error_exist('jenis_pembayaran') }" class="col-sm-3">
                      <select v-model='penerimaan_pembayaran.jenis_pembayaran' class="form-control" :disabled='mode == "detail"'>
                        <option value="">-Pilih-</option>
                        <option value="Cash">Cash</option>
                        <option value="BG">BG</option>
                        <option value="Transfer">Transfer</option>
                      </select>
                      <small v-if="error_exist('jenis_pembayaran')" class="form-text text-danger">{{ get_error('jenis_pembayaran') }}</small>
                    </div>
                  </div>
                  <div v-show='jenis_pembayaran_cash' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nominal Cash</label>
                    <div v-bind:class="{ 'has-error': error_exist('nominal_cash') }" class="col-sm-3">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='penerimaan_pembayaran.nominal_cash' separator='.' currency='Rp'></vue-numeric>
                      <small v-if="error_exist('nominal_cash')" class="form-text text-danger">{{ get_error('nominal_cash') }}</small>
                    </div>
                  </div>
                  <div v-show='jenis_pembayaran_bg'>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">No. BG</label>
                      <div v-bind:class="{ 'has-error': error_exist('nomor_bg') }" class="col-sm-3">
                        <input :readonly='mode == "detail"' type="text" class="form-control" v-model='penerimaan_pembayaran.nomor_bg'>
                        <small v-if="error_exist('nomor_bg')" class="form-text text-danger">{{ get_error('nomor_bg') }}</small>
                      </div>
                      <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Nama Bank</label>
                      <div v-bind:class="{ 'has-error': error_exist('nama_bank_bg') }" class="col-sm-3">
                        <input :readonly='mode == "detail"' type="text" class="form-control" v-model='penerimaan_pembayaran.nama_bank_bg'>
                        <small v-if="error_exist('nama_bank_bg')" class="form-text text-danger">{{ get_error('nama_bank_bg') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Jatuh Tempo BG</label>
                      <div v-bind:class="{ 'has-error': error_exist('tanggal_jatuh_tempo_bg') }" class="col-sm-3">
                        <date-picker readonly :disabled='mode == "detail"' v-model='penerimaan_pembayaran.tanggal_jatuh_tempo_bg' class='form-control'></date-picker>
                        <small v-if="error_exist('tanggal_jatuh_tempo_bg')" class="form-text text-danger">{{ get_error('tanggal_jatuh_tempo_bg') }}</small>
                      </div>
                      <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Nominal BG</label>
                      <div v-bind:class="{ 'has-error': error_exist('nominal_bg') }" class="col-sm-3">
                        <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='penerimaan_pembayaran.nominal_bg' separator='.' currency='Rp'></vue-numeric>
                        <small v-if="error_exist('nominal_bg')" class="form-text text-danger">{{ get_error('nominal_bg') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Rekening Tujuan</label>
                      <div v-bind:class="{ 'has-error': error_exist('id_rekening_md_bg') }" class="col-sm-3">
                        <input type="text" class="form-control" readonly v-model='penerimaan_pembayaran.nama_bank_rekening_md_bg'>
                        <small v-if="error_exist('id_rekening_md_bg')" class="form-text text-danger">{{ get_error('id_rekening_md_bg') }}</small>
                      </div>
                      <div class="col-sm-1 no-padding">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_rekening_tujuan_bg_penerimaan_pembayaran'><i class="fa fa-search"></i></button>
                      </div>
                      <?php $this->load->view('modal/h3_md_rekening_tujuan_bg_penerimaan_pembayaran'); ?>
                      <script>
                        function pilih_rekening_tujuan_bg_penerimaan_pembayaran(data) {
                          form_.penerimaan_pembayaran.id_rekening_md_bg = data.id_rek_md;
                          form_.penerimaan_pembayaran.nama_bank_rekening_md_bg = data.bank;
                        }
                      </script>
                    </div>
                  </div>
                  <div v-show='jenis_pembayaran_transfer'>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Transfer</label>
                      <div v-bind:class="{ 'has-error': error_exist('tanggal_transfer') }" class="col-sm-3">
                        <date-picker readonly :disabled='mode == "detail"' v-model='penerimaan_pembayaran.tanggal_transfer' class='form-control'></date-picker>
                        <small v-if="error_exist('tanggal_transfer')" class="form-text text-danger">{{ get_error('tanggal_transfer') }}</small>
                      </div>
                      <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Nominal Transfer</label>
                      <div v-bind:class="{ 'has-error': error_exist('nominal_transfer') }" class="col-sm-3">
                        <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='penerimaan_pembayaran.nominal_transfer' separator='.' currency='Rp'></vue-numeric>
                        <small v-if="error_exist('nominal_transfer')" class="form-text text-danger">{{ get_error('nominal_transfer') }}</small>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Rekening Tujuan</label>
                      <div v-bind:class="{ 'has-error': error_exist('id_rekening_md_bg') }" class="col-sm-3">
                        <input type="text" class="form-control" readonly v-model='penerimaan_pembayaran.nama_bank_rekening_md_transfer'>
                        <small v-if="error_exist('id_rekening_md_bg')" class="form-text text-danger">{{ get_error('id_rekening_md_bg') }}</small>
                      </div>
                      <div class="col-sm-1 no-padding">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_rekening_tujuan_transfer_penerimaan_pembayaran'><i class="fa fa-search"></i></button>
                      </div>
                      <?php $this->load->view('modal/h3_md_rekening_tujuan_transfer_penerimaan_pembayaran'); ?>
                      <script>
                        function pilih_rekening_tujuan_transfer_penerimaan_pembayaran(data) {
                          form_.penerimaan_pembayaran.id_rekening_md_transfer = data.id_rek_md;
                          form_.penerimaan_pembayaran.nama_bank_rekening_md_transfer = data.bank;
                        }
                      </script>
                    </div>
                  </div>
                  <div class="continer-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-condensed">
                          <tr>
                            <td width='3%'>No.</td>
                            <td>No. Account</td>
                            <td>No. Faktur</td>
                            <td>Tanggal Jatuh Tempo</td>
                            <td>Nama Customer</td>
                            <td class='text-right'>Amount</td>
                            <td class='text-right'>Jumlah Pembayaran</td>
                            <td class='text-right'>Sisa Piutang</td>
                            <td>Debt Collector</td>
                            <td width='5%'>Lunas</td>
                          </tr>
                          <tr v-for='(item, index) of items'>
                            <td class='align-middle'>{{ index + 1 }}.</td>
                            <td class='align-middle'>{{ item.kode_coa }}</td>
                            <td class='align-middle'>{{ item.tipe_transaksi == 'faktur_penjualan' ? item.referensi : '-' }}</td>
                            <td class='align-middle'>{{ item.tipe_transaksi == 'faktur_penjualan' ? item.tgl_jatuh_tempo : '-' }}</td>
                            <td class='align-middle'>{{ item.tipe_transaksi == 'faktur_penjualan' ? item.nama_dealer : '-' }}</td>
                            <td class='text-right align-middle'>
                              <vue-numeric v-if='item.tipe_transaksi == "faktur_penjualan"' v-model='item.amount' read-only thousand-separator='.' currency='Rp'></vue-numeric>
                              <span v-if='item.tipe_transaksi == "manual_coa"'>-</span>
                            </td>
                            <td class='text-right align-middle'>
                              <vue-numeric :read-only='mode == "detail"' v-model='item.jumlah_pembayaran' :max='item.amount' class='form-control text-right' thousand-separator='.' currency='Rp'></vue-numeric>
                            </td>
                            <td class='text-right align-middle'>
                              <vue-numeric v-if='item.tipe_transaksi == "faktur_penjualan"' v-model='sisa_piutang(item)' read-only thousand-separator='.' currency='Rp'></vue-numeric>
                              <span v-if='item.tipe_transaksi == "manual_coa"'>-</span>
                            </td>
                            <td class='align-middle'>{{ item.tipe_transaksi == 'faktur_penjualan' ? item.nama_debt_collector : '' }}</td>
                            <td class='align-middle'>
                              <input :disabled='sisa_piutang(item) != 0 || mode == "detail"' v-if='item.tipe_transaksi == "faktur_penjualan"' type="checkbox" true-value='1' false-value='0' v-model='item.lunas'>
                              <button v-if='item.tipe_transaksi == "manual_coa" && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='hapus_manual_coa(index)'><i class="fa fa-trash-o"></i></button>
                            </td>
                          </tr>
                          <tr v-if='items.length < 1'>
                            <td class="text-center" colspan='10'>Tidak ada data.</td>
                          </tr>
                          <tr v-if='mode != "detail"'>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>
                              <input placeholder='Pilih COA' type="text" class="form-control" readonly v-model='manual_coa.kode_coa' data-toggle='modal' data-target='#h3_md_coa_penerimaan_pembayaran'>
                            </td>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>
                              <vue-numeric v-model='manual_coa.jumlah_pembayaran' class='form-control text-right' thousand-separator='.' currency='Rp'></vue-numeric>
                            </td>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>-</td>
                            <td class='align-middle'>
                              <button class="btn btn-flat btn-primary" @click.prevent='tambah_manual_coa'><i class="fa fa-plus"></i></button>
                            </td>
                          </tr>
                          <tr v-if='items.length > 0'>
                            <td colspan='5'></td>
                            <td class='text-right'>
                              <vue-numeric v-model='total_amount' read-only thousand-separator='.' currency='Rp'></vue-numeric>
                            </td>
                            <td class='text-right'>
                              <vue-numeric v-model='total_jumlah_pembayaran' read-only thousand-separator='.' currency='Rp'></vue-numeric>
                            </td>
                            <td class='text-right'>
                              <vue-numeric v-model='total_sisa_piutang' read-only thousand-separator='.' currency='Rp'></vue-numeric>
                            </td>
                          </tr>
                        </table>
                        <?php $this->load->view('modal/h3_md_coa_penerimaan_pembayaran'); ?>
                        <script>
                          function pilih_coa_penerimaan_pembayaran(data){
                            form_.manual_coa.kode_coa = data.kode_coa;
                          }                          
                        </script>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' :disabled='!pembayaran_tanpa_selisih || terdapat_faktur_bisa_lunas.length > 0' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <!-- <a v-if='mode == "detail"' class='btn btn-flat btn-sm btn-primary' :href="'h3/<?= $isi ?>/cetakan_tanda_terima_faktur?id=' + tanda_terima_faktur.id">Cetakan Tanda Terima Faktur</a> -->
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
        penerimaan_pembayaran: <?= json_encode($penerimaan_pembayaran) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        penerimaan_pembayaran: {
          tanggal_bap: '',
          id_dealer: '',
          nama_dealer: '',
          id_group_dealer: '',
          nama_group_dealer: '',
          id_debt_collector: '',
          nama_debt_collector: '',
          jenis_pembayaran: '',
          nominal_cash: 0,
          tanggal_proses: '',
          nomor_bg: '',
          nama_bank_bg: '',
          tanggal_jatuh_tempo_bg: '',
          nominal_bg: 0,
          id_rekening_md_bg: '',
          nama_bank_rekening_md_bg: '',
          tanggal_transfer: '',
          nominal_transfer: 0,
          id_rekening_md_transfer: '',
          nama_bank_rekening_md_transfer: '',
        },
        items: [],
        <?php endif; ?>
        manual_coa: {
          kode_coa: '',
          jumlah_pembayaran: 0,
          tipe_transaksi: 'manual_coa'
        },
      },
      methods:{
        <?= $form ?>: function(){
          post = _.pick(this.penerimaan_pembayaran, [
            'tanggal_bap',
            'id_dealer',
            'id_group_dealer',
            'id_debt_collector',
            'jenis_pembayaran',
            'nominal_cash',
            'tanggal_proses',
            'nomor_bg',
            'nama_bank_bg',
            'tanggal_jatuh_tempo_bg',
            'nominal_bg',
            'id_rekening_md_bg',
            'tanggal_transfer',
            'nominal_transfer',
            'id_rekening_md_transfer',
          ]);

          post.items = _.chain(this.items)
          .filter(function(item){
            return item.jumlah_pembayaran != 0;
          })
          .map(function(item){
            filtered_data = _.pick(item, ['kode_coa', 'referensi', 'tipe_transaksi', 'amount', 'jumlah_pembayaran', 'lunas']);
            filtered_data.sisa_piutang = form_.sisa_piutang(item);
            return filtered_data;
          }).value();

          post.total_pembayaran = this.total_pembayaran;

          this.errors = {};
          this.loading = true;
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
            }

            if(data.message != null){
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }

            form_.loading = false;
          });
        },
        proses_faktur: function(){
          this.loading = true;
          this.errors = {};
          axios.get('h3/<?= $isi ?>/proses_faktur', {
            params: {
              id_group_dealer: this.penerimaan_pembayaran.id_group_dealer,
              id_dealer: this.penerimaan_pembayaran.id_dealer,
              tanggal_bap: this.penerimaan_pembayaran.tanggal_bap,
              id_debt_collector: this.penerimaan_pembayaran.id_debt_collector,
              jenis_pembayaran: this.penerimaan_pembayaran.jenis_pembayaran,
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
          });
        },
        tambah_manual_coa: function(){
          data = this.manual_coa;
          data.amount = data.jumlah_pembayaran;
          data.sisa_piutang = this.sisa_piutang(data);
          data.lunas = 1;
          this.items.push(data);
          this.manual_coa = {
            kode_coa: '',
            jumlah_pembayaran: 0,
            tipe_transaksi: 'manual_coa',
          };
        },
        hapus_manual_coa: function(index){
          this.items.splice(index, 1);
        },
        sisa_piutang: function(data){
          return data.amount - data.jumlah_pembayaran;
        },
        hapus_group_dealer: function(){
          this.penerimaan_pembayaran.id_group_dealer = '';
          this.penerimaan_pembayaran.nama_group_dealer = '';
        },
        hapus_customer: function(){
          this.penerimaan_pembayaran.id_dealer = '';
          this.penerimaan_pembayaran.nama_dealer = '';
        },
        hapus_debt_collector: function(){
          this.penerimaan_pembayaran.id_debt_collector = '';
          this.penerimaan_pembayaran.nama_debt_collector = '';
          h3_md_dealer_penerimaan_pembayaran_datatable.draw();
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        total_amount: function(){
          return _.chain(this.items)
          .sumBy(function(item){
            return Number(item.amount);
          }).value();
        },
        total_jumlah_pembayaran: function(){
          return _.chain(this.items)
          .sumBy(function(item){
            return Number(item.jumlah_pembayaran);
          }).value();
        },
        total_sisa_piutang: function(){
          return _.chain(this.items)
          .sumBy(function(item){
            return item.amount - item.jumlah_pembayaran;
          }).value();
        },
        total_pembayaran: function(){
          if(this.jenis_pembayaran_cash){
            return this.penerimaan_pembayaran.nominal_cash;
          }else if(this.jenis_pembayaran_bg){
            return this.penerimaan_pembayaran.nominal_bg;
          }else if(this.jenis_pembayaran_transfer){
            return this.penerimaan_pembayaran.nominal_transfer;
          }
        },
        jenis_pembayaran_cash: function(){
          return this.penerimaan_pembayaran.jenis_pembayaran == 'Cash';
        },
        jenis_pembayaran_bg: function(){
          return this.penerimaan_pembayaran.jenis_pembayaran == 'BG';
        },
        jenis_pembayaran_transfer: function(){
          return this.penerimaan_pembayaran.jenis_pembayaran == 'Transfer';
        },
        pembayaran_tanpa_selisih: function(){
          return this.total_pembayaran == this.total_jumlah_pembayaran;
        },
        terdapat_faktur_bisa_lunas: function(){
          sisa_piutang_fn = this.sisa_piutang;

          invoices = _.chain(this.items)
          .filter(function(item){
            return sisa_piutang_fn(item) == 0 && item.lunas == 0;
          })
          .value();

          return invoices;
        },
        group_dealer_terpilih: function(){
          return this.penerimaan_pembayaran.id_group_dealer != null && this.penerimaan_pembayaran.id_group_dealer != '';
        },
        customer_terpilih: function(){
          return this.penerimaan_pembayaran.id_dealer != null && this.penerimaan_pembayaran.id_dealer != '';
        },
        debt_collector_terpilih: function(){
          return this.penerimaan_pembayaran.id_debt_collector != null && this.penerimaan_pembayaran.id_debt_collector != '';
        }
      },
      watch: {
        'penerimaan_pembayaran.id_group_dealer': function(){
          h3_md_dealer_penerimaan_pembayaran_datatable.draw();
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
          <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
          <?php endif; ?>
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <div class="btn-group">
            <button type="button" class="btn btn-flat btn-success">Download</button>
            <button type="button" class="btn btn-flat btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a id='download_excel' href="h3/<?= $isi ?>/download_excel">Excel</a></li>
                <li><a id='download_pdf' href="h3/<?= $isi ?>/download_pdf">PDF</a></li>
            </ul>
          </div>
        </h3>
      </div><!-- /.box-header -->
      <script>
      function set_download_excel_url(){
        query_string = new URLSearchParams({
          periode_awal : $('#tanggal_penerimaan_filter_start').val(),
          periode_akhir : $('#tanggal_penerimaan_filter_end').val(),
          urut_berdasarkan_filter : $('#urut_berdasarkan_filter').val()
        }).toString();

        $('#download_excel').attr('href', 'h3/<?= $isi ?>/download_excel?' + query_string);
        $('#download_pdf').attr('href', 'h3/<?= $isi ?>/download_pdf?' + query_string);
      }
      </script>
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Group Dealer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_group_dealer_filter' type="text" class="form-control" disabled>
                      <input id='id_group_dealer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_group_dealer_filter_penerimaan_pembayaran_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_group_dealer_filter_penerimaan_pembayaran_index'); ?>         
                <script>
                function pilih_group_dealer_filter_penerimaan_pembayaran_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_group_dealer_filter').val(data.group_dealer);
                    $('#id_group_dealer_filter').val(data.id_group_dealer);
                  }else if(type == 'reset_filter'){
                    $('#nama_group_dealer_filter').val('');
                    $('#id_group_dealer_filter').val('');
                  }

                  $('#id_customer_filter').val('');
                  $('#nama_customer_filter').val('');

                  penerimaan_pembayaran.draw();
                  h3_md_group_dealer_filter_penerimaan_pembayaran_index_datatable.draw();
                  h3_md_dealer_filter_penerimaan_pembayaran_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_penerimaan_pembayaran_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_penerimaan_pembayaran_index'); ?>         
                <script>
                function pilih_dealer_filter_penerimaan_pembayaran_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_customer_filter').val(data.nama_dealer);
                    $('#id_customer_filter').val(data.id_dealer);
                  }else if(type == 'reset_filter'){
                    $('#nama_customer_filter').val('');
                    $('#id_customer_filter').val('');
                  }
                  penerimaan_pembayaran.draw();
                  h3_md_dealer_filter_penerimaan_pembayaran_index_datatable.draw();
                }
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Tanggal BAP</label>
                  <div class="col-sm-8">
                    <input id='tanggal_bap_filter' type="text" class="form-control" readonly>
                    <input id='tanggal_bap_filter_start' type="hidden" disabled>
                    <input id='tanggal_bap_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#tanggal_bap_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#tanggal_bap_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#tanggal_bap_filter_end').val(end.format('YYYY-MM-DD'));
                    penerimaan_pembayaran.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#tanggal_bap_filter_start').val('');
                    $('#tanggal_bap_filter_end').val('');
                    penerimaan_pembayaran.draw();
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No Penerimaan Pembayaran</label>
                  <div class="col-sm-8">
                    <input id='no_penerimaan_pembayaran_filter' type="text" class="form-control">
                  </div>
                  <script>
                    $(document).ready(function(){
                      $('#no_penerimaan_pembayaran_filter').on("keyup", _.debounce(function(){
                        penerimaan_pembayaran.draw();
                      }, 500));
                    });
                  </script>
                </div>                
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Debt Collector</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='debt_collector_filter' type="text" class="form-control" disabled>
                      <input id='id_debt_collector_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_debt_collector_filter_penerimaan_pembayaran_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>                
                <?php $this->load->view('modal/h3_md_debt_collector_filter_penerimaan_pembayaran_index'); ?>         
                <script>
                function pilih_debt_collector_filter_penerimaan_pembayaran_index (data, type) {
                  if(type == 'add_filter'){
                    $('#debt_collector_filter').val(data.nama_lengkap);
                    $('#id_debt_collector_filter').val(data.id_karyawan);
                  }else if(type == 'reset_filter'){
                    $('#debt_collector_filter').val('');
                    $('#id_debt_collector_filter').val('');
                  }
                  penerimaan_pembayaran.draw();
                  h3_md_debt_collector_filter_penerimaan_pembayaran_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Jenis Pembayaran</label>
                  <div class="col-sm-8">
                    <select id='jenis_pembayaran_filter' class="form-control">
                      <option value="">-All-</option>
                      <option value="Cash">Cash</option>
                      <option value="BG">BG</option>
                      <option value="Transfer">Transfer</option>
                    </select>
                  </div>
                  <script>
                    $(document).ready(function(){
                      $('#jenis_pembayaran_filter').on("change", function(){
                        penerimaan_pembayaran.draw();
                      });
                    });
                  </script>
                </div>                
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Tanggal Penerimaan</label>
                  <div class="col-sm-8">
                    <input id='tanggal_penerimaan_filter' type="text" class="form-control" readonly>
                    <input id='tanggal_penerimaan_filter_start' type="hidden" disabled>
                    <input id='tanggal_penerimaan_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#tanggal_penerimaan_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $('#tanggal_penerimaan_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#tanggal_penerimaan_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));

                    penerimaan_pembayaran.draw();
                    set_download_excel_url();
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $('#tanggal_penerimaan_filter_start').val('');
                    $('#tanggal_penerimaan_filter_end').val('');
                    $(this).val('');

                    penerimaan_pembayaran.draw();
                    set_download_excel_url();
                  });
                </script>
              </div>
              <!-- <div class="col-sm-6">
                <div class="form-group">
                  <label for="" class="control-label col-sm-4">Urut berdasarkan</label>
                  <div class="col-sm-8">
                    <select id='urut_berdasarkan_filter' class="form-control">
                      <option value="tanggal_bayar">Tanggal Bayar</option>
                      <option value="customer">Customer</option>
                    </select>
                  </div>
                </div>
              </div>
              <script>
                $('#urut_berdasarkan_filter').on('change', function(e){
                  penerimaan_pembayaran.draw();
                  set_download_excel_url();
                });
              </script> -->
            </div>
          </form>
        </div>
        <table id="penerimaan_pembayaran" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>No Penerimaan Pembayaran</th>
              <th>Tanggal Proses</th>
              <th>Group Dealer</th>
              <th>Nama Customer</th>
              <th>Tanggal BAP</th>
              <th>Jenis Pembayaran</th>
              <th>Total Pembayaran</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            penerimaan_pembayaran = $('#penerimaan_pembayaran').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/penerimaan_pembayaran') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.id_group_dealer_filter = $('#id_group_dealer_filter').val();
                    d.id_customer_filter = $('#id_customer_filter').val();
                    d.no_penerimaan_pembayaran_filter = $('#no_penerimaan_pembayaran_filter').val();
                    d.tanggal_bap_filter_start = $('#tanggal_bap_filter_start').val();
                    d.tanggal_bap_filter_end = $('#tanggal_bap_filter_end').val();
                    d.no_faktur_filter = $('#no_faktur_filter').val();
                    d.id_debt_collector_filter = $('#id_debt_collector_filter').val();
                    d.jenis_pembayaran_filter = $('#jenis_pembayaran_filter').val();
                    d.tanggal_penerimaan_filter_start = $('#tanggal_penerimaan_filter_start').val();
                    d.tanggal_penerimaan_filter_end = $('#tanggal_penerimaan_filter_end').val();
                    d.urut_berdasarkan_filter = $('#urut_berdasarkan_filter').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'id_penerimaan_pembayaran' },
                    { 
                      data: 'tanggal_proses',
                      render: function(data){
                        return moment(data).format('DD/MM/YYYY');
                      }
                    },
                    { data: 'group_dealer' },
                    { data: 'nama_dealer' },
                    { data: 'tanggal_bap' },
                    { data: 'jenis_pembayaran' },
                    { data: 'total_pembayaran' },
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