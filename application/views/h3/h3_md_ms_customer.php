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
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer MD</label>
                    <div v-bind:class="{ 'has-error': error_exist('kode_dealer_md') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.kode_dealer_md'>
                      <small v-if="error_exist('kode_dealer_md')" class="form-text text-danger">{{ get_error('kode_dealer_md') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer AHM</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" v-model='customer.kode_dealer_ahm' readonly>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_resmi_customer'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_resmi_customer'); ?>
                    <script>
                      function pilih_dealer_resmi_customer(data) {
                        form_.customer.kode_dealer_ahm = data.kode_dealer_md;
                      }
                    </script>
                  </div>
                
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer POD/SAL</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" v-model='customer.kode_dealer_ahm_link' readonly>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_resmi'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    <?php  $this->load->view('modal/h3_md_dealer_resmi'); ?>
                    <script>
                      function pilih_dealer_resmi(data) {
                        form_.customer.kode_dealer_ahm_link = data.kode_dealer_ahm_link;
                      }
                    </script>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_dealer') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.nama_dealer'>
                      <small v-if="error_exist('nama_dealer')" class="form-text text-danger">{{ get_error('nama_dealer') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div v-bind:class="{ 'has-error': error_exist('email') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.email'>
                      <small v-if="error_exist('email')" class="form-text text-danger">{{ get_error('email') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Jaringan</label>
                    <div class="col-sm-1">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='customer.h1'> H1
                    </div>
                    <div class="col-sm-1">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='customer.h2'> H2
                    </div>
                    <div class="col-sm-1">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='customer.h3'> H3
                    </div>
                    <label for="inputEmail3" class="col-sm-2 col-sm-offset-1 control-label">Pimpinan</label>
                    <div v-bind:class="{ 'has-error': error_exist('pimpinan') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.pimpinan'>
                      <small v-if="error_exist('pimpinan')" class="form-text text-danger">{{ get_error('pimpinan') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Telp / No HP</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_telp') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.no_telp'>
                      <small v-if="error_exist('no_telp')" class="form-text text-danger">{{ get_error('no_telp') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div v-bind:class="{ 'has-error': error_exist('alamat') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.alamat'>
                      <small v-if="error_exist('alamat')" class="form-text text-danger">{{ get_error('alamat') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_kelurahan') }" class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" v-model='customer.kelurahan' readonly>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelurahan_customer'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('id_kelurahan')" class="form-text text-danger">{{ get_error('id_kelurahan') }}</small>
                    </div>
                    <?php $this->load->view('modal/h3_md_kelurahan_customer'); ?>
                    <script>
                      function pilih_kelurahan_customer(data) {
                        form_.customer.kelurahan = data.kelurahan;
                        form_.customer.id_kelurahan = data.id_kelurahan;
                        form_.customer.kecamatan = data.kecamatan;
                        form_.customer.id_kecamatan = data.id_kecamatan;
                        form_.customer.kabupaten = data.kabupaten;
                        form_.customer.id_kabupaten = data.id_kabupaten;
                        form_.customer.provinsi = data.provinsi;
                        form_.customer.id_provinsi = data.id_provinsi;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model='customer.kecamatan' readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model='customer.kabupaten' readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model='customer.provinsi' readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">TOP Part</label>
                    <div v-bind:class="{ 'has-error': error_exist('top_part') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.top_part'>
                      <small v-if="error_exist('top_part')" class="form-text text-danger">{{ get_error('top_part') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">TOP Oli</label>
                    <div v-bind:class="{ 'has-error': error_exist('top_oli') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.top_oli'>
                      <small v-if="error_exist('top_oli')" class="form-text text-danger">{{ get_error('top_oli') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">NPWP</label>
                    <div v-bind:class="{ 'has-error': error_exist('npwp') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.npwp'>
                      <small v-if="error_exist('npwp')" class="form-text text-danger">{{ get_error('npwp') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Pemilik</label>
                    <div v-bind:class="{ 'has-error': error_exist('pemilik') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.pemilik'>
                      <small v-if="error_exist('pemilik')" class="form-text text-danger">{{ get_error('pemilik') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Diskon</label>
                    <div v-bind:class="{ 'has-error': error_exist('tipe_diskon') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='customer.tipe_diskon'>
                        <option value="">-Pilih-</option>
                        <option value="Rupiah">Rupiah</option>
                        <option value="Persen">Persen</option>
                      </select>
                      <small v-if="error_exist('tipe_diskon')" class="form-text text-danger">{{ get_error('tipe_diskon') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Foto Pemilik</label>
                    <div v-bind:class="{ 'has-error': error_exist('foto_pemilik') }" class="col-sm-4">
                      <input v-if='mode != "detail"' type="file" @change='on_foto_pemilik_change()' ref='foto_pemilik' class="form-control">
                      <small v-if="error_exist('foto_pemilik')" class="form-text text-danger">{{ get_error('foto_pemilik') }}</small>
                      <img v-if='mode == "detail"' style='margin-top: 10px; max-width:100%' :src="'<?= base_url('assets/panel/images/') ?>' + customer.uploaded_foto_pemilik" height='150'>
                      <small v-if='mode == "edit"' class="form-text text-muted">Kosongkan, jika tidak ingin merubah foto.</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diskon Fix Order</label>
                    <div v-bind:class="{ 'has-error': error_exist('diskon_fixed_order') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.diskon_fixed_order'>
                      <small v-if="error_exist('diskon_fixed_order')" class="form-text text-danger">{{ get_error('diskon_fixed_order') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Diskon Reguler</label>
                    <div v-bind:class="{ 'has-error': error_exist('diskon_reguler') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.diskon_reguler'>
                      <small v-if="error_exist('diskon_reguler')" class="form-text text-danger">{{ get_error('diskon_reguler') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diskon Hotline</label>
                    <div v-bind:class="{ 'has-error': error_exist('diskon_hotline') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.diskon_hotline'>
                      <small v-if="error_exist('diskon_hotline')" class="form-text text-danger">{{ get_error('diskon_hotline') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Diskon Urgent</label>
                    <div v-bind:class="{ 'has-error': error_exist('diskon_urgent') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.diskon_urgent'>
                      <small v-if="error_exist('diskon_urgent')" class="form-text text-danger">{{ get_error('diskon_urgent') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-2">PIC</label>
                    <div v-bind:class="{ 'has-error': error_exist('pic') }" class="col-sm-4">
                      <input :disabled='mode == "detail"' type="text" class="form-control" v-model='customer.pic'>
                      <small v-if="error_exist('pic')" class="form-text text-danger">{{ get_error('pic') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Status Ruko/Bangunan</label>
                    <div v-bind:class="{ 'has-error': error_exist('status_bangunan') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='customer.status_bangunan'>
                        <option value="">-Pilih-</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Sewa">Sewa</option>
                      </select>
                      <small v-if="error_exist('status_bangunan')" class="form-text text-danger">{{ get_error('status_bangunan') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Ruko</label>
                    <div v-bind:class="{ 'has-error': error_exist('jumlah_ruko') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.jumlah_ruko'>
                      <small v-if="error_exist('jumlah_ruko')" class="form-text text-danger">{{ get_error('jumlah_ruko') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Foto Ruko</label>
                    <div v-bind:class="{ 'has-error': error_exist('foto_ruko') }" class="col-sm-4">
                      <input v-if='mode != "detail"' type="file" @change='on_foto_ruko_change()' ref='foto_ruko' class="form-control">
                      <small v-if="error_exist('foto_ruko')" class="form-text text-danger">{{ get_error('foto_ruko') }}</small>
                      <img v-if='mode == "detail"' style='margin-top: 10px; max-width:100%' :src="'<?= base_url('assets/panel/images/') ?>' + customer.uploaded_foto_ruko" height='150'>
                      <small v-if='mode == "edit"' class="form-text text-muted">Kosongkan, jika tidak ingin merubah foto.</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Luas Bangunan</label>
                    <div v-bind:class="{ 'has-error': error_exist('luas_bangunan') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='customer.luas_bangunan'>
                      <small v-if="error_exist('luas_bangunan')" class="form-text text-danger">{{ get_error('luas_bangunan') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">PKP</label>
                    <div v-bind:class="{ 'has-error': error_exist('pkp') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='customer.pkp'>
                        <option value="">-Pilih-</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                      </select>
                      <small v-if="error_exist('pkp')" class="form-text text-danger">{{ get_error('pkp') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status Customer</label>
                    <div v-bind:class="{ 'has-error': error_exist('status_dealer') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='customer.status_dealer'>
                        <option value="">-Pilih-</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Non Aktif">Non Aktif</option>
                        <option value="Blacklist">Blacklist</option>
                      </select>
                      <small v-if="error_exist('status_dealer')" class="form-text text-danger">{{ get_error('status_dealer') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Kerjasama</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_kerjasama') }" class="col-sm-4">
                      <input :disabled='mode == "detail"' readonly type="text" class="form-control" id="tanggal_kerjasama_datepicker">
                      <small v-if="error_exist('tanggal_kerjasama')" class="form-text text-danger">{{ get_error('tanggal_kerjasama') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Bank H3</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_bank_h3') }" class="col-sm-4 ">
                      <input type="text" :disabled='mode == "detail"' class="form-control" v-model='customer.nama_bank_h3'>
                      <small v-if="error_exist('nama_bank_h3')" class="form-text text-danger">{{ get_error('nama_bank_h3') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Atas Nama Bank H3</label>
                    <div v-bind:class="{ 'has-error': error_exist('atas_nama_bank_h3') }" class="col-sm-4 ">
                      <input type="text" :disabled='mode == "detail"' class="form-control" v-model='customer.atas_nama_bank_h3'>
                      <small v-if="error_exist('atas_nama_bank_h3')" class="form-text text-danger">{{ get_error('atas_nama_bank_h3') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Rekening H3</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_rekening_h3') }" class="col-sm-4 ">
                      <input type="text" :disabled='mode == "detail"' class="form-control" v-model='customer.no_rekening_h3'>
                      <small v-if="error_exist('no_rekening_h3')" class="form-text text-danger">{{ get_error('no_rekening_h3') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Jenis Dealer</label>
                    <div v-bind:class="{ 'has-error': error_exist('jenis_dealer') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='customer.jenis_dealer'>
                        <option value="">-Pilih-</option>
                        <option value="H123 Reguler">H123 Reguler</option>
                        <option value="H123 Wing">H123 Wing</option>
                        <option value="H123 Big Wing">H123 Big Wing</option>
                        <option value="H23">H23 (AHASS Murni)</option>
                      </select>
                      <small v-if="error_exist('jenis_dealer')" class="form-text text-danger">{{ get_error('jenis_dealer') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Grouping Dealer</label>
                    <div v-bind:class="{ 'has-error': error_exist('grouping_dealer') }" class="col-sm-4 ">
                      <select :disabled='mode == "detail"' v-model='customer.grouping_dealer' class="form-control">
                        <option value="Dealer Group Lokal">Dealer Group Lokal</option>
                        <option value="Dealer Group Nasional">Dealer Group Nasional</option>
                        <option value="Dealer Group Many">Dealer Group Many</option>
                        <option value="Dealer Independent">Dealer Independent</option>
                        <option value="SO MD">SO MD</option>
                        <option value="AHASS Murni">AHASS Murni</option>
                      </select>
                      <small v-if="error_exist('grouping_dealer')" class="form-text text-danger">{{ get_error('grouping_dealer') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div class="col-sm-4 ">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='customer.active'>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Plafon H3</label>
                    <div v-bind:class="{ 'has-error': error_exist('tipe_plafon_h3') }" class="col-sm-4 ">
                      <select :disabled='mode == "detail"' v-model='customer.tipe_plafon_h3' class="form-control">
                        <option value="reguler">Reguler</option>
                        <option value="gimmick">Gimmick</option>
                        <option value="kpb">KPB</option>
                      </select>
                      <small v-if="error_exist('tipe_plafon_h3')" class="form-text text-danger">{{ get_error('tipe_plafon_h3') }}</small>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail"' :href="'h3/h3_md_ms_customer/edit?id_dealer=' + customer.id_dealer" class="btn btn-flat btn-sm btn-warning">Edit</a>
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
        customer: <?= json_encode($customer) ?>,
        <?php else: ?>
        customer: {
          h1: 0,
          h2: 0,
          h3: 0,
          tanggal_kerjasama: '',
          kode_dealer_md: '',
          nama_dealer: '',
          no_telp: '',
          alamat: '',
          kelurahan: '',
          id_kelurahan: '',
          kecamatan: '',
          id_kecamatan: '',
          kabupaten: '',
          id_kabupaten: '',
          provinsi: '',
          id_provinsi: '',
          top_part: '',
          top_oli: '',
          npwp: '',
          pemilik: '',
          tipe_diskon: '',
          diskon_fixed_order: '',
          diskon_reguler: '',
          diskon_hotline: '',
          diskon_urgent: '',
          kode_dealer_ahm: '',
          status_dealer: '',
          kode_dealer_ahm_link: '',
          jumlah_ruko: '',
          active: 1,
          tipe_plafon_h3: 'reguler',
          foto_ruko: null,
          foto_pemilik: null,
          nama_bank_h3: '',
          atas_nama_bank_h3: '',
          no_rekening_h3: '',
          jenis_dealer: '',
          grouping_dealer: '',
        }
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          keys = [
            'h1', 'h2', 'h3',
            'kode_dealer_md','nama_dealer','no_telp','alamat','id_kelurahan', 'pic', 'pimpinan', 'email',
            'id_kecamatan','id_kabupaten','id_provinsi','top_part','top_oli','npwp','pemilik', 'pkp', 'tanggal_kerjasama',
            'tipe_diskon','diskon_fixed_order','diskon_reguler','diskon_hotline','diskon_urgent','kode_dealer_ahm','status_dealer', 'kode_dealer_ahm_link', 'status_bangunan', 'luas_bangunan',
            'jumlah_ruko','active', 'tipe_plafon_h3', 'nama_bank_h3', 'atas_nama_bank_h3', 'no_rekening_h3', 'jenis_dealer','grouping_dealer'
          ];

          post = new FormData();
          for ( key of keys ) {
            if(this.customer[key] != null){
              post.set(key, this.customer[key]);
            }
          }

          if(this.mode == 'edit'){
            post.set('id_dealer', this.customer.id_dealer);
          }

          post.append('foto_ruko', this.customer.foto_ruko);
          post.append('foto_pemilik', this.customer.foto_pemilik);

          this.loading = true;
          axios.post('h3/<?= $isi ?>/<?= $form ?>', post, {
            headers: {
              'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
            }
          })
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
          })
          .then(function(){ form_.loading = false; })
          ;
        },
        on_foto_ruko_change: function(){
          this.customer.foto_ruko = this.$refs.foto_ruko.files[0];
        },
        on_foto_pemilik_change: function(){
          this.customer.foto_pemilik = this.$refs.foto_pemilik.files[0];
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        'target_salesman.id_dealer': function(n, o){
          h3_salesman_parts_target_salesman_datatable.draw();
          h3_salesman_oil_target_salesman_datatable.draw();
          h3_salesman_acc_target_salesman_datatable.draw();
        }
      },
      mounted: function(){
        config = {
          autoclose: true,
          format: 'dd/mm/yyyy',
        };
        $(document).ready(function(){
          $('#tanggal_kerjasama_datepicker').datepicker(config)
          .on('changeDate', function(e){
            form_.customer.tanggal_kerjasama = e.format('yyyy-mm-dd');
          });
        });
        if(this.mode != "insert" && this.customer.tanggal_kerjasama != null){
          date = new Date(this.customer.tanggal_kerjasama);
          $(document).ready(function(){
            $("#tanggal_kerjasama_datepicker").datepicker("setDate", date);
            $('#tanggal_kerjasama_datepicker').datepicker('update');
          });
        }
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-md-6">
              <a href="h3/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
            </div>
            <div class="col-md-6 text-right">
              <a href="h3/h3_md_update_diskon">
                <button class="btn bg-blue btn-flat margin">Update diskon</button>
              </a>
            </div>
          </div>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="master_customer" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Customer</th>
              <th>Nama Customer</th>
              <th>Tipe Diskon</th>
              <th>Fix</th>
              <th>Reg</th>
              <th>Hotline</th>
              <th>Urgent</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_customer = $('#master_customer').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_customer') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(data){
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'kode_dealer_md' },
                    { data: 'nama_dealer' },
                    { data: 'tipe_diskon' },
                    { data: 'diskon_fixed_order' },
                    { data: 'diskon_reguler' },
                    { data: 'diskon_hotline' },
                    { data: 'diskon_urgent' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            master_customer.on('draw.dt', function() {
              var info = master_customer.page.info();
              master_customer.column(0, {
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