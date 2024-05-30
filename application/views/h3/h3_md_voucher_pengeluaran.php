<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/daterangepicker.min.js") ?>" type="text/javascript"></script>
<link href="<?= base_url("assets/panel/daterangepicker.css") ?>" rel="stylesheet" type="text/css"/>
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
        <?php $this->load->view('template/normal_session_message'); ?>
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Transaksi</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_transaksi') }" class="col-sm-4">
                      <date-picker @update-date='tanggal_transaksi_datepicker_change' class='form-control' readonly :config='config' v-model='voucher_pengeluaran.tanggal_transaksi'></date-picker>
                      <small v-if="error_exist('tanggal_transaksi')" class="form-text text-danger">{{ get_error('tanggal_transaksi') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Penerima</label>
                    <div v-bind:class="{ 'has-error': error_exist('tipe_penerima') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='voucher_pengeluaran.tipe_penerima'>
                        <option value="">-Choose-</option>
                        <option value="Vendor">Vendor</option>
                        <option value="Dealer">Dealer</option>
                        <option value="Karyawan">Karyawan</option>
                        <option value="Toko">Toko</option>
                        <option value="Lain-lain">Lain-lain</option>
                      </select>
                      <small v-if="error_exist('tipe_penerima')" class="form-text text-danger">{{ get_error('tipe_penerima') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Account</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_account') }" class="col-sm-4">
                      <div class="input-group">
                        <input readonly type="text" class="form-control" v-model='voucher_pengeluaran.nama_account'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_bank_voucher_pengeluaran'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_account')" class="form-text text-danger">{{ get_error('id_account') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_bank_voucher_pengeluaran'); ?>
                    <script>
                      function pilih_bank_voucher_pengeluaran(data){
                        form_.voucher_pengeluaran.id_account = data.id_rek_md;
                        form_.voucher_pengeluaran.id_bank = data.id_bank;
                        form_.voucher_pengeluaran.nama_account = data.bank;
                        form_.voucher_pengeluaran.no_rekening_account = data.no_rekening;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Account Name</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_account') }" class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='voucher_pengeluaran.nama_account'>
                      <small v-if="error_exist('id_account')" class="form-text text-danger">{{ get_error('id_account') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Dibayarkan Kepada</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_dibayarkan_kepada') }" class="col-sm-4">
                      <div v-if='!tipe_penerima_lain_lain' class="input-group">
                        <input type="text" class="form-control" readonly v-model='voucher_pengeluaran.referensi_dibayarkan_kepada'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail" || !tipe_peneriman_sudah_dipilih' class="btn btn-flat btn-primary" type='button' data-toggle='modal' :data-target='modal_dibayarkan_kepada'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <input v-if='tipe_penerima_lain_lain' type="text" class='form-control' v-model='voucher_pengeluaran.referensi_dibayarkan_kepada'>
                      <small v-if="error_exist('id_dibayarkan_kepada')" class="form-text text-danger">{{ get_error('id_dibayarkan_kepada') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_vendor_voucher_pengeluaran'); ?>
                    <script>
                      function pilih_vendor_voucher_pengeluaran(data){
                        form_.voucher_pengeluaran.id_dibayarkan_kepada = data.id_vendor;
                        form_.voucher_pengeluaran.referensi_dibayarkan_kepada = data.alias;
                        form_.voucher_pengeluaran.nama_penerima_dibayarkan_kepada = data.vendor_name;
                        form_.voucher_pengeluaran.alamat = data.alamat;
                        form_.voucher_pengeluaran.no_rekening_tujuan = data.no_rekening_tujuan;
                        form_.voucher_pengeluaran.bank_tujuan = data.bank;
                        form_.voucher_pengeluaran.atas_nama_tujuan = data.atas_nama;
                      }
                    </script>
                    <?php $this->load->view('modal/h3_md_dealer_voucher_pengeluaran'); ?>
                    <script>
                      function pilih_dealer_voucher_pengeluaran(data){
                        form_.voucher_pengeluaran.id_dibayarkan_kepada = data.id_dealer;
                        form_.voucher_pengeluaran.referensi_dibayarkan_kepada = data.kode_dealer_md;
                        form_.voucher_pengeluaran.nama_penerima_dibayarkan_kepada = data.nama_dealer;
                        form_.voucher_pengeluaran.alamat = data.alamat;
                        form_.voucher_pengeluaran.no_rekening_tujuan = data.no_rekening_h3;
                        form_.voucher_pengeluaran.bank_tujuan = data.nama_bank_h3;
                        form_.voucher_pengeluaran.atas_nama_tujuan = data.atas_nama_bank_h3;
                      }
                    </script>
                    <?php $this->load->view('modal/h3_md_toko_voucher_pengeluaran'); ?>
                    <script>
                      function pilih_toko_voucher_pengeluaran(data){
                        form_.voucher_pengeluaran.id_dibayarkan_kepada = data.id_dealer;
                        form_.voucher_pengeluaran.referensi_dibayarkan_kepada = data.kode_dealer_md;
                        form_.voucher_pengeluaran.nama_penerima_dibayarkan_kepada = data.nama_dealer;
                        form_.voucher_pengeluaran.alamat = data.alamat;
                        form_.voucher_pengeluaran.no_rekening_tujuan = data.no_rekening_h3;
                        form_.voucher_pengeluaran.bank_tujuan = data.nama_bank_h3;
                        form_.voucher_pengeluaran.atas_nama_tujuan = data.atas_nama_bank_h3;
                      }
                    </script>
                    <?php $this->load->view('modal/h3_md_karyawan_voucher_pengeluaran'); ?>
                    <script>
                      function pilih_karyawan_voucher_pengeluaran(data){
                        form_.voucher_pengeluaran.id_dibayarkan_kepada = data.id_karyawan;
                        form_.voucher_pengeluaran.referensi_dibayarkan_kepada = data.npk;
                        form_.voucher_pengeluaran.nama_penerima_dibayarkan_kepada = data.nama_lengkap;
                        form_.voucher_pengeluaran.alamat = data.alamat;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Penerima</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_penerima_dibayarkan_kepada') }" class="col-sm-4">
                      <input type="text" class="form-control" :readonly='!tipe_penerima_lain_lain' v-model='voucher_pengeluaran.nama_penerima_dibayarkan_kepada'>
                      <small v-if="error_exist('nama_penerima_dibayarkan_kepada')" class="form-text text-danger">{{ get_error('nama_penerima_dibayarkan_kepada') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                      <input type="text" class="form-control" :readonly='!tipe_penerima_lain_lain' v-model='voucher_pengeluaran.alamat'>
                      <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Amount</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" readonly currency='Rp' separator='.' v-model='total_amount'></vue-numeric>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Via Bayar</label>
                    <div v-bind:class="{ 'has-error': error_exist('via_bayar') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='voucher_pengeluaran.via_bayar'>
                        <option value="">-Choose-</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Giro">Giro</option>
                      </select>
                      <small v-if="error_exist('via_bayar')" class="form-text text-danger">{{ get_error('via_bayar') }}</small>
                    </div>
                  </div>
                  <div v-if='via_bayar_transfer' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Transfer</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_transfer') }" class="col-sm-4">
                      <date-picker :disabled='mode == "detail"' class='form-control' readonly  v-model='voucher_pengeluaran.tanggal_transfer'></date-picker>
                      <small v-if="error_exist('tanggal_transfer')" class="form-text text-danger">{{ get_error('tanggal_transfer') }}</small>
                    </div>
                  </div>
                  <div v-if='via_bayar_transfer' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nominal</label>
                    <div v-bind:class="{ 'has-error': error_exist('nominal_transfer') }" class="col-sm-4">
                      <vue-numeric class="form-control" :disabled='mode == "detail"' v-model='voucher_pengeluaran.nominal_transfer' thousand-separator='.' currency='Rp'></vue-numeric>
                      <small v-if="error_exist('nominal_transfer')" class="form-text text-danger">{{ get_error('nominal_transfer') }}</small>
                    </div>
                  </div>
                  <div v-if='via_bayar_transfer' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Rekening Tujuan</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_rekening_tujuan') }" class="col-sm-4">
                      <input type="text" class="form-control" v-model='voucher_pengeluaran.no_rekening_tujuan'>
                      <small v-if="error_exist('no_rekening_tujuan')" class="form-text text-danger">{{ get_error('no_rekening_tujuan') }}</small>
                    </div>
                  </div>
                  <div v-if='via_bayar_transfer' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                    <div v-bind:class="{ 'has-error': error_exist('bank_tujuan') }" class="col-sm-4">
                      <input type="text" class="form-control" v-model='voucher_pengeluaran.bank_tujuan'>
                      <small v-if="error_exist('bank_tujuan')" class="form-text text-danger">{{ get_error('bank_tujuan') }}</small>
                    </div>
                  </div>
                  <div v-if='via_bayar_transfer' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">A/N</label>
                    <div v-bind:class="{ 'has-error': error_exist('atas_nama_tujuan') }" class="col-sm-4">
                      <input type="text" class="form-control" v-model='voucher_pengeluaran.atas_nama_tujuan'>
                      <small v-if="error_exist('atas_nama_tujuan')" class="form-text text-danger">{{ get_error('atas_nama_tujuan') }}</small>
                    </div>
                  </div>
                  <div v-if='via_bayar_giro' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Giro</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_giro') }" class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" readonly v-model='voucher_pengeluaran.no_giro'>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn-flat btn btn-primary" type='button' data-toggle='modal' data-target='#h3_md_no_giro_voucher_pengeluaran'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('no_giro')" class="form-text text-danger">{{ get_error('no_giro') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Giro</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_giro') }" class="col-sm-4">
                      <date-picker @update-date='tanggal_giro_datepicker_change' class='form-control' readonly :config='config' v-model='voucher_pengeluaran.tanggal_giro'></date-picker>
                      <small v-if="error_exist('tanggal_giro')" class="form-text text-danger">{{ get_error('tanggal_giro') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_no_giro_voucher_pengeluaran'); ?>
                  <script>
                    function pilih_no_giro_voucher_pengeluaran(data){
                      form_.voucher_pengeluaran.id_giro = data.id_giro;
                      form_.voucher_pengeluaran.no_giro = data.no_giro;
                    }
                  </script>
                  <div v-if='via_bayar_giro' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nominal Giro</label>
                    <div v-bind:class="{ 'has-error': error_exist('nominal_giro') }" class="col-sm-4">
                      <vue-numeric class="form-control" currency='Rp' separator='.' :readonly='mode == "detail"' v-model='voucher_pengeluaran.nominal_giro'></vue-numeric>
                      <small v-if="error_exist('nominal_giro')" class="form-text text-danger">{{ get_error('nominal_giro') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                    <div v-bind:class="{ 'has-error': error_exist('deskripsi') }" class="col-sm-10">
                      <textarea :readonly='mode == "detail"' class='form-control' v-model='voucher_pengeluaran.deskripsi' rows='10' cols='60'></textarea>
                      <small v-if="error_exist('deskripsi')" class="form-text text-danger">{{ get_error('deskripsi') }}</small>
                    </div>
                  </div>
                  <div v-if='voucher_pengeluaran.status == "Canceled"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan Cancel</label>
                    <div class="col-sm-4">
                      <textarea readonly class='form-control' v-model='voucher_pengeluaran.alasan_cancel'></textarea>
                    </div>
                  </div>
                  <div class="continer-fluid">
                    <div class="row">
                      <div class="col-sm-12">
                        <table class="table table-condensed">
                          <tr>
                            <td width='3%'>No.</td>
                            <td>No. Account</td>
                            <td width='10%'>COA</td>
                            <td>Jenis Transaksi</td>
                            <td>Referensi</td>
                            <td>Jumlah Terutang</td>
                            <td>Nominal</td>
                            <td>Keterangan</td>
                            <td v-if='mode != "detail"' width='3%'></td>
                          </tr>
                          <tr v-if='items.length > 0' v-for='(row, index) of items'>
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.nomor_account }}</td>
                            <td>{{ row.nama_coa }}</td>
                            <td>{{ row.jenis_transaksi.replaceAll('_', ' ').toUpperCase() }}</td>
                            <td>{{ row.referensi == null ? "-" : row.referensi }}</td>
                            <td>
                              <vue-numeric class='form-control' currency='Rp' separator='.' read-only v-model='row.jumlah_terutang'></vue-numeric>
                            </td>
                            <td>
                              <vue-numeric class='form-control' currency='Rp' separator='.' :read-only='mode == "detail"' v-model='row.nominal'></vue-numeric>
                            </td>
                            <td>
                              <input type="text" class="form-control" :readonly='mode == "detail"' v-model='row.keterangan'>
                            </td>
                            <td v-if='mode != "detail"'>
                              <button class="btn btn-flat btn-danger" @click.prevent='hapus_item(index)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </td>
                          </tr>
                          <tr v-if='items.length < 1'>
                            <td class="text-center" colspan='8'>Tidak ada data</td>
                          </tr>
                          <tr v-if='mode != "detail"'>
                            <td class='align-middle'>-</td>
                            <td>
                              <input v-model='item.nomor_account' type="text" class="form-control" readonly placeholder='Search No. COA' data-toggle='modal' data-target='#h3_md_coa_voucher_pengeluaran'>
                            </td>
                            <td class='align-middle'>{{ item.nama_coa }}</td>
                            <td class='align-middle'>{{ item.jenis_transaksi }}</td>
                            <td>
                              <input v-model='item.referensi' type="text" class="form-control" readonly placeholder='Search No. Transaksi' data-toggle='modal' data-target='#h3_md_transaksi_voucher_pengeluaran'>
                            </td>
                            <td class='align-middle'>
                              <vue-numeric v-model='item.jumlah_terutang' read-only currency='Rp' separator='.'></vue-numeric>
                            </td>
                            <td>
                              <vue-numeric class='form-control' currency='Rp' separator='.' v-model='item.nominal'></vue-numeric>
                            </td>
                            <td>
                              <input type="text" class="form-control" v-model='item.keterangan'>
                            </td>
                            <td>
                              <button class="btn btn-flat btn-primary" @click.prevent='add_item'><i class="fa fa-plus"></i></button>
                            </td>
                          </tr>
                        </table>
                        <?php $this->load->view('modal/h3_md_coa_voucher_pengeluaran'); ?>
                        <script>
                          function pilih_coa_voucher_pengeluaran(data){
                            form_.item.nomor_account = data.kode_coa;
                            form_.item.nama_coa = data.coa;
                            form_.item.jenis_transaksi = 'coa';
                          }                          
                        </script>
                        <?php $this->load->view('modal/h3_md_transaksi_voucher_pengeluaran'); ?>
                        <script>
                          function pilih_transaksi_voucher_pengeluaran(data){
                            form_.item = data;
                          }
                        </script>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"':disabled='items.length < 1 || loading' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"':disabled='items.length < 1 || loading' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail" && voucher_pengeluaran.status == "Open"' class='btn btn-flat btn-sm btn-warning' :href="'h3/<?= $isi ?>/edit?id_voucher_pengeluaran=' + voucher_pengeluaran.id_voucher_pengeluaran">Edit</a>
                      <button v-if='mode == "detail" && voucher_pengeluaran.status == "Open"' class='btn btn-flat btn-sm btn-danger' type='button' data-toggle='modal' data-target='#alasan_cancel_voucher_pengeluaran'>Cancel</button>
                    </div>
                    <div class="col-sm-6 text-right">
                      <a v-if='mode == "detail"' class='btn btn-flat btn-sm btn-info' :href="'h3/<?= $isi ?>/cetak?id_voucher_pengeluaran=' + voucher_pengeluaran.id_voucher_pengeluaran">Cetak</a>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/alasan_cancel_voucher_pengeluaran'); ?>
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
        voucher_pengeluaran: <?= json_encode($voucher_pengeluaran) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        voucher_pengeluaran: {
          tanggal_transaksi: '',
          tipe_penerima: '',
          id_account: '',
          nama_account: '',
          id_bank: '',
          id_dibayarkan_kepada: '',
          referensi_dibayarkan_kepada: '',
          nama_penerima_dibayarkan_kepada: '',
          alamat: '',
          via_bayar: '',
          tanggal_transfer: '',
          nominal_transfer: 0,
          no_rekening_tujuan: '',
          bank_tujuan: '',
          atas_nama_tujuan: '',
          id_giro: '',
          no_giro: '',
          tanggal_giro: '',
          nominal_giro: 0,
          deskripsi: '',
        },
        items: [],
        <?php endif; ?>
        item: {
          nomor_account: '',
          nama_coa: '',
          jenis_transaksi: null,
          id_referensi: null,
          referensi: null,
          jumlah_terutang: 0,
          nominal: 0,
          keterangan: '',
        },
        config: {
          autoclose: true,
          format: 'dd/mm/yyyy',
          todayBtn: 'linked',
          clearBtn: true,
        },
      },
      methods:{
        <?= $form ?>: _.throttle(function(){
          post = _.pick(this.voucher_pengeluaran, [
            'tanggal_transaksi','tipe_penerima','id_account','id_dibayarkan_kepada', 'nama_penerima_dibayarkan_kepada', 'referensi_dibayarkan_kepada', 'alamat',
            'via_bayar','id_giro', 'no_giro', 'tanggal_giro', 'deskripsi', 'nominal_giro', 'nama_account', 'no_rekening_account',
            'no_rekening_tujuan', 'bank_tujuan', 'atas_nama_tujuan', 'id_voucher_pengeluaran', 'id_bank', 'tanggal_transfer', 'nominal_transfer'
          ]);
          post.total_amount = this.total_amount;

          post.items = _.chain(this.items)
          .filter(function(item){
            return item.nominal != 0;
          })
          .map(function(item){
            return _.pick(item, ['nomor_account', 'nama_coa', 'id_referensi', 'jumlah_terutang', 'jenis_transaksi', 'nominal', 'keterangan']);
          }).value();

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
            }else{
              toastr.error(data.message);
            }

            form_.loading = false;
          });
        }, 500),
        cancel: function(){
          this.loading = true;
          axios.get('h3/<?= $isi ?>/cancel', {
            params: {
              id_voucher_pengeluaran: this.voucher_pengeluaran.id_voucher_pengeluaran,
              alasan_cancel: $('#alasan_cancel').val()
            }
          })
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?id_voucher_pengeluaran=' + res.data.id_voucher_pengeluaran;
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
          .then(function(){ form_.loading = false; });
        },
        add_item: function(){
          this.items.push(
            this.item
          );

          this.item = {
            nomor_account: '',
            jenis_transaksi: null,
            referensi: null,
            jumlah_terutang: 0,
            nominal: 0,
            keterangan: '',
          };

          h3_md_transaksi_voucher_pengeluaran_datatable.draw();
        },
        hapus_item: function(index){
          this.items.splice(index , 1);
          h3_md_transaksi_voucher_pengeluaran_datatable.draw();
        },
        tambah_manual_coa: function(){
          this.items.push(this.manual_coa);
          this.manual_coa = {
            kode_coa: '',
            jumlah_pembayaran: 0,
            tipe_transaksi: 'manual_coa',
          };
        },
        hapus_manual_coa: function(index){
          this.items.splice(index, 1);
        },
        tanggal_transaksi_datepicker_change: function(date){
          this.voucher_pengeluaran.tanggal_transaksi = date.format('yyyy-mm-dd');
        },
        tanggal_giro_datepicker_change: function(date){
          this.voucher_pengeluaran.tanggal_giro = date.format('yyyy-mm-dd');
        },
        cek_nominal_giro: function(){
          if(!this.via_bayar_giro) return;

          params = {
            id_voucher_pengeluaran: this.voucher_pengeluaran.id_voucher_pengeluaran,
            id_giro: this.voucher_pengeluaran.id_giro,
          };

          axios.get('h3/h3_md_voucher_pengeluaran/cek_nominal_giro', {
            params: params
          })
          .then(function(res){
            form_.voucher_pengeluaran.nominal_giro = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            app.loading = false;
          });
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      computed: {
        tipe_peneriman_sudah_dipilih: function(){
          return this.voucher_pengeluaran.tipe_penerima != '' && this.voucher_pengeluaran.tipe_penerima != null;
        },
        via_bayar_giro: function(){
          return this.voucher_pengeluaran.via_bayar == 'Giro';
        },
        via_bayar_transfer: function(){
          return this.voucher_pengeluaran.via_bayar == 'Transfer';
        },
        tipe_penerima_lain_lain: function(){
          return this.voucher_pengeluaran.tipe_penerima == 'Lain-lain';
        },
        modal_dibayarkan_kepada: function(){
          if(this.voucher_pengeluaran.tipe_penerima == 'Vendor'){
            return '#h3_md_vendor_voucher_pengeluaran';
          }else if(this.voucher_pengeluaran.tipe_penerima == 'Dealer'){
            return '#h3_md_dealer_voucher_pengeluaran';
          }else if(this.voucher_pengeluaran.tipe_penerima == 'Karyawan'){
            return '#h3_md_karyawan_voucher_pengeluaran';
          }else if(this.voucher_pengeluaran.tipe_penerima == 'Toko'){
            return '#h3_md_toko_voucher_pengeluaran';
          }

          return '';
        },
        total_amount: function(){
          return _.chain(this.items)
          .sumBy(function(item){
            return item.nominal;
          })
          .value();
        }
      },
      watch: {
        // 'voucher_pengeluaran.via_bayar': function(value){
        //   if(value != 'Giro'){
        //     this.voucher_pengeluaran.id_giro = '';
        //     this.voucher_pengeluaran.tanggal_giro = '';
        //     this.voucher_pengeluaran.no_giro = '';
        //     this.voucher_pengeluaran.nominal_giro = '';
        //   }

        //   if(value != 'Transfer'){
        //     this.voucher_pengeluaran.no_rekening_tujuan = '';
        //     this.voucher_pengeluaran.bank_tujuan = '';
        //     this.voucher_pengeluaran.atas_nama_tujuan = '';
        //   }
        // },
        'voucher_pengeluaran.id_dibayarkan_kepada': function(){
          h3_md_transaksi_voucher_pengeluaran_datatable.draw();
        },
        'voucher_pengeluaran.id_account': function(){
          h3_md_no_giro_voucher_pengeluaran_datatable.draw();
        },
        // 'voucher_pengeluaran.id_giro': function(value){
        //   this.cek_nominal_giro();
        // },
        'voucher_pengeluaran.tipe_penerima': function(){
          this.voucher_pengeluaran.id_dibayarkan_kepada = '';
          this.voucher_pengeluaran.referensi_dibayarkan_kepada = '';
          this.voucher_pengeluaran.nama_penerima_dibayarkan_kepada = '';
          this.voucher_pengeluaran.alamat = '';
        }
      },
      // mounted: function(){
      //   this.cek_nominal_giro();
      // }
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
        <?php $this->load->view('template/normal_session_message'); ?>
        <table id="voucher_pengeluaran" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>No. Bukti</th>
              <th>Tanggal Transaksi</th>
              <th>Nama Penerima</th>
              <th>Amount</th>
              <th>Account</th>
              <th>No. Giro</th>
              <th>Nominal Giro</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            voucher_pengeluaran = $('#voucher_pengeluaran').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/voucher_pengeluaran') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'id_voucher_pengeluaran' },
                    { 
                      data: 'tanggal_transaksi',
                      render: function(data){
                        return moment(data).format('DD/MM/YYYY');
                      }
                    },
                    { 
                      data: 'nama_penerima_dibayarkan_kepada',
                      render: function(data){
                        if(data != null) return data;
                        return '-';
                      }
                    },
                    { 
                      data: 'total_amount', 
                      className: 'text-right',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      } 
                    },
                    { data: 'bank' },
                    { 
                      data: 'kode_giro',
                      render: function(data){
                        if(data != null) return data;
                        return '-';
                      }
                    },
                    { 
                      data: 'nominal_giro',
                      className: 'text-right',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      } 
                    },
                    { data: 'status' },
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