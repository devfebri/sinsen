<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Booking & Queue</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'ubah_jadwal') {
        $readonly = 'readonly';
        $form = 'save_jadwal';
      }
      if ($mode == 'edit') {
        $readonly = 'readonly';
        $form = 'save_edit';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
    ?>
      <style>
        .isi {
          height: 25px;
          padding-left: 4px;
          padding-right: 4px;
        }
      </style>

      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <!-- or point to a specific vue-select release -->
      <!-- <script src="assets/vue-select/vue-select@3.0.0"></script> -->
      <link rel="stylesheet" href="assets/vue-select/vue-select.css">
      <script src="assets/lodash/lodash.min.js"></script>
      <script>
        Vue.use(VueNumeric.default);
        // Vue.component('v-select', VueSelect.VueSelect);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>

          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/manage_queue">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
          <?php
          }
          $_SESSION['pesan'] = '';

          ?>
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="box-body" v-if="mode=='insert' || mode=='edit'">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Customer <i style="color: red">*</i></label>
                    <div class="col-sm-4">
                      <select name="jenis_customer" id="jenis_customer" v-model="jenis_customer" class="form-control" :disabled="mode!='insert'" required>
                        <option value="">-choose-</option>
                        <option value="booking">Booking</option>
                        <option value="reguler">Reguler</option>
                      </select>
                      <?php if (isset($row)) { ?>
                        <input type='hidden' value='<?= $row->id_antrian ?>' name='id_antrian'>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Isi Data Lengkap <i style="color: red">*</i></label>
                    <div class="col-sm-4">
                      <select name="lengkap" id="lengkap" v-model="lengkap" class="form-control" :disabled="mode=='detail'" required>
                        <option value="">-choose-</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group" v-if="lengkap=='0'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Pilih No. Polisi / No. Mesin <i style="color: red">*</i></label>
                    <div class="col-sm-4">
                      <select name="pilihan" id="pilihan" v-model="pilihan" class="form-control" :disabled="mode=='detail'" required>
                        <option value="">-choose-</option>
                        <option value="no_polisi">No. Polisi</option>
                        <option value="no_mesin">No. Mesin</option>
                        <!-- <option value="no_rangka">No. Rangka</option> -->
                      </select>
                    </div>
                    <div class="col-sm-4">
                      <input v-if="pilihan=='no_polisi'" type="text" class="form-control" name="no_polisi_antri" required v-model="no_polisi_antri" :disabled="mode=='detail'">
                      <input v-if="pilihan=='no_mesin'" type="text" class="form-control" name="no_mesin_antri" required v-model="no_mesin_antri" :disabled="mode=='detail'">
                    </div>
                  </div>
                  <div class="" v-if="lengkap=='1'">
                    <div class="col-md-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>{{pencarian}}</button><br><br>
                    </div>
                    <div class="col-md-12">
                      <h4 style="padding-left: 15px"><b>Data Customer</b></h4></br>
                      <div class="form-group" v-if="jenis_customer=='booking'">
                        <label for="inputEmail3" class="col-sm-2 control-label">ID Booking<i style="color: red">*</i></label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="id_booking" required :readonly="edit_cust==''" v-model="customer.id_booking">
                        </div>
                        <div class="col-sm-3">
                          <button type="button" onclick="showModalCustomerBooking()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="hidden" id="id_customer" v-model="customer.id_customer" />
                            <input type="text" class="form-control" name="nama_customer" required :readonly="edit_cust==''" v-model="customer.nama_customer">
                          </div>
                        </div>
                        <div class="col-sm-5" v-if="mode=='insert' || mode=='edit'">
                          <button type="button" v-if="jenis_customer=='reguler' && mode=='insert'" id="searchCustomer" onclick="showModalAllCustomer()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                          <span v-if="customer.id_customer">
                            <button v-if="edit_cust==''" type="button" @click.prevent="editCust" class="btn btn-flat btn-warning">Edit Data Customer</button>
                            <button v-if="edit_cust=='1'" type="button" @click.prevent="editCust" class="btn btn-flat btn-danger">Batal Edit</button>
                          </span>
                          <button v-if="jenis_customer=='reguler' && mode=='insert'" type="button" onclick="customerH23Baru()" class="btn btn-info btn-flat"><i class="fa fa-plus"></i> Customer Baru</button>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Sesuai STNK <i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="nama_stnk" required v-model="customer.nama_stnk">
                          </div>
                        </div>

                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. HP / No. Telp<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_hp" :minlength="10" :maxlength="15"  required :readonly="edit_cust==''" v-model="customer.no_hp">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="email" :readonly="edit_cust==''" v-model="customer.email">
                        </div>
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Saat Ini<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="alamat" id="alamat"  v-model="customer.alamat" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Jenis Kelamin<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <select name="jenis_kelamin" class="form-control" required  v-model="customer.jenis_kelamin" required>
                              <option value="">-choose-</option>
                              <option value="Laki-laki">Laki-laki</option>
                              <option value="Perempuan">Perempuan</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Agama</label>
                          <div class="col-sm-4">
                            <?php $agama = $this->m_h2->getAgama(); ?>
                            <select name="id_agama" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.id_agama">
                              <option value="">-choose-</option>
                              <?php foreach ($agama->result() as $agm) { ?>
                                <option value="<?= $agm->id_agama ?>"><?= $agm->agama ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Lahir</label>
                          <div class="col-sm-4">
                            <date-picker :readonly="edit_cust==''" v-model="customer.tgl_lahir" id="tgl_lahir" placeholder='Klik untuk memilih'></date-picker>
                          </div>
                        </div>
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Pekerjaan</label>
                          <div class="col-sm-4">
                            <?php $agama = $this->m_h2->getPekerjaan(); ?>
                            <select name="id_pekerjaan" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.id_pekerjaan">
                              <option value="">-choose-</option>
                              <?php foreach ($agama->result() as $pk) { ?>
                                <option value="<?= $pk->id_pekerjaan ?>"><?= $pk->pekerjaan ?></option>
                              <?php } ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" readonly v-model="customer.kelurahan">
                        </div>
                        <div class="col-sm-1" v-if="edit_cust!=''">
                          <button type="button" onclick="showModalKelurahan()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="col-sm-1" v-if="edit_cust==''"></div>

                        <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model="customer.kecamatan">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model="customer.kabupaten">
                        </div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model="customer.provinsi">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Longitude</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" :disabled="edit_cust==''" v-model="customer.longitude">
                        </div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Latitude</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" :disabled="edit_cust==''" v-model="customer.latitude">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Jenis Identitas</label>
                        <div class="col-sm-4">
                          <select name="jenis_identitas" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.jenis_identitas">
                            <option value="">-choose-</option>
                            <option value="ktp">KTP</option>
                            <option value="sim">SIM</option>
                            <option value="kitap">KITAP</option>
                            <option value="npwp">NPWP</option>
                          </select>
                        </div>
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Identitas</label>
                          <div class="col-sm-4">
                            <input type="text" onkeypress="return number_only(event)" :minlength="min_id" :maxlength="max_id" class="form-control" name="no_identitas" id="no_identitas" :readonly="edit_cust==''" v-model="customer.no_identitas">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label">Alamat Sama ?</label>
                        <div class="col-sm-2"><input v-model="alamat_sama" type="checkbox" value="sama"></div>
                      </div>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Alamat Identitas<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="alamat_identitas" id="alamat_identitas" :readonly="edit_cust==''" v-model="customer.alamat_identitas" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Kelurahan</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" readonly v-model="customer.kelurahan_identitas">
                        </div>
                        <div class="col-sm-1" v-if="edit_cust!=''">
                          <button type="button" onclick="showModalKelurahan('identitas')" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="col-sm-1" v-if="edit_cust==''"></div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Kecamatan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model="customer.kecamatan_identitas">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Kota/Kabupaten</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model="customer.kabupaten_identitas">
                        </div>
                        <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model="customer.provinsi_identitas">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembelian Customer</label>
                        <div class="col-sm-4">
                          <select name="jenis_customer_beli" class="form-control" :required="edit_cust==''" :disabled="edit_cust==''" v-model="customer.jenis_customer_beli">
                            <option value="">-choose-</option>
                            <option value="Regular">Regular</option>
                            <option value="Group Sales">Group Sales</option>
                          </select>
                        </div>
                      </div>
                      </br>
                      <h4 style="padding-left: 15px"><b>Data Unit Motor</b></h4></br>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">No. Polisi<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="no_polisi" v-model="customer.no_polisi" required :readonly="mode=='detail'">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor<i style="color: red">*</i></label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" name="id_tipe_kendaraan" readonly v-model="customer.tipe_ahm" required>
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <!--<button type="button" v-if="customer_from=='baru'" onclick="showModalItem()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>-->
                          <button type="button" onclick="showModalItem()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Warna<i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" readonly v-model="customer.warna" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">No. Mesin</label>
                        <div class="col-sm-4">
                          <input type="text" :minlength="12"  id="no_mesin_input" :maxlength="12" class="form-control" name="no_mesin" id="no_mesin" :readonly="edit_cust==''" v-model="customer.no_mesin">
                        </div>
                        <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control"  id="no_rangka_input" :minlength="17" v-model="customer.no_rangka" :readonly="edit_cust==''">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" v-model="customer.tahun_produksi" :readonly="edit_cust==''" onkeypress="return number_only(event)" name='tahun_produksi'>
                        </div>
                        <div class="form-input">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Pembelian <i style="color: red">*</i></label>
                          <div class="col-sm-4">
                            <date-picker v-model="customer.tgl_pembelian" id="tgl_pembelian" disabled required></date-picker>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" name="id_cdb" id="id_cdb">
                      </br>
                      <!-- <h4 style="padding-left: 15px"><b>Data Pembawa</b></h4></br>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Pembawa<i style="color: red">*</i></label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="nama_pembawa" required <?= $readonly ?> v-model="customer.nama_pembawa">
                      </div>
                    </div> -->
                    </div>
                    <div class="col-md-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-primary btn-flat btn-sm" disabled>Data Servis</button><br><br>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-1 col-sm-4">
                        <button type="button" class="btn btn-primary btn-flat" id="btnRiwayatServis" onclick="cekRiwayatServis()">Riwayat Servis</button>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Servis<i style="color: red">*</i></label>
                      <div class="col-sm-4">
                        <input type="text" autocomplete="off" class="form-control" name="tgl_servis" id="tgl_servis" required readonly value="<?= isset($row) ? $row->tgl_servis : date('Y-m-d') ?>">
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Jam Servis<i style="color: red">*</i></label>
                      <div class="col-sm-4">
                        <input type="text" autocomplete="off" class="form-control" name="jam_servis" id="jam_servis" required readonly value="<?= isset($row) ? $mode == 'edit' ? jam_menit() : $row->jam_servis : gmdate("H:i", time() + 60 * 60 * 7) ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Keluhan</label>
                      <div class="col-sm-10">
                        <input type="text" autocomplete="off" class="form-control" name="keluhan" id="keluhan" value="<?= isset($row) ? $row->keluhan_konsumen : '' ?>" :disabled="mode=='detail'">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label" v-if="mode=='insert' || mode=='edit' ">Tipe Servis <i style="color: red">*</i></label>
                      <div class="col-sm-4" v-if="mode=='insert' || mode=='edit' ">
                        <select name="id_type" id="id_type" class="form-control select2" required>
                          <option value="">-choose-</option>
                          <?php $tipe_servis = $this->db->get("ms_h2_jasa_type")->result() ?>
                          <?php foreach ($tipe_servis as $rs) :
                            $select = isset($row) ? $rs->id_type == $row->id_type ? 'selected' : '' : '';
                            if($rs->active ==1){
                          ?>
                            <option <?= $select ?> value="<?= $rs->id_type ?>"><?= $rs->id_type . ' | ' . $rs->deskripsi ?></option>
                          <?php } endforeach ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" v-if="mode=='insert' || mode=='edit'" align="center">
                      <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div><!-- /.box-footer -->

              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = ['riwayatServisCustomerH23', 'kelurahan', 'item', 'allCustomer', 'customerBooking'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>

      <script>
        Vue.component('date-picker', {
          template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
          directives: {
            datepicker: {
              inserted(el, binding, vNode) {
                $(el).datepicker({
                  autoclose: true,
                  format: 'yyyy-mm-dd',
                  todayHighlight: false,
                }).on('changeDate', function(e) {
                  vNode.context.$emit('input', e.format(0))
                })
              }
            }
          },
          props: ['value'],
          methods: {
            update(v) {
              this.$emit('input', v)
            }
          }
        })
        var form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            lengkap: "<?= isset($row) ? $row->lengkap : '' ?>",
            pilihan: "<?= isset($row) ? $row->pilihan : '' ?>",
            no_mesin_antri: '<?= isset($row) ? $row->no_mesin_antri : "" ?>',
            no_polisi_antri: '<?= isset($row) ? $row->no_polisi_antri : "" ?>',
            mode: '<?= $mode ?>',
            edit_cust: '',
            riwayatServis: [],
            jenis_customer: "<?= isset($jenis_customer) ? $jenis_customer : '' ?>",
            details: [],
            pencarian: 'Data Customer',
            customer: <?= isset($customer) ? json_encode($customer) : "{
              tipe_ahm: '',
              kelurahan: '',
              kelurahan_identitas: ''
            }" ?>,
            customer_from: "<?= isset($customer_from) ? $customer_from : '' ?>",
            alamat_sama: "<?= isset($alamat_sama) ? $alamat_sama : '' ?>",
            opt_tipe_servis: []
          },
          methods: {
            editCust: function() {
              if (this.edit_cust == 1) {
                this.edit_cust = '';
                this.cust = this.customer_old;
                $('#tgl_pembelian').attr('disabled', true);
              } else {
                $('#tgl_pembelian').attr('disabled', false);
                this.edit_cust = 1;
              }
            },
          },
          watch: {
            jenis_customer: function() {
              if (this.jenis_customer == 'booking') {
                this.pencarian = 'Data Customer Booking';
                this.lengkap = '1';
              } else if (this.jenis_customer == 'reguler') {
                this.pencarian = 'Data Customer';
                if (this.lengkap == '1') {
                  this.lengkap = '0';
                }
              }
            },
            alamat_sama: function() {
              if (this.alamat_sama === true) {
                this.customer.alamat_identitas = this.customer.alamat;
                this.customer.id_kelurahan_identitas = this.customer.id_kelurahan;
                this.customer.kelurahan_identitas = this.customer.kelurahan;
                this.customer.kecamatan_identitas = this.customer.kecamatan;
                this.customer.kabupaten_identitas = this.customer.kabupaten;
                this.customer.provinsi_identitas = this.customer.provinsi;
              } else {
                this.customer.alamat_identitas = '';
                this.customer.id_kelurahan_identitas = '';
                this.customer.kelurahan_identitas = '';
                this.customer.kecamatan_identitas = '';
                this.customer.kabupaten_identitas = '';
                this.customer.provinsi_identitas = '';
              }
            }
          },
          computed: {
            min_id: function() {
              let set = 0;
              if (this.customer.jenis_identitas == 'ktp') set = 16;
              if (this.customer.jenis_identitas == 'sim') set = 12;
              if (this.customer.jenis_identitas == 'kitap') set = 16;
              return set;
            },
            max_id: function() {
              let set = 30;
              if (this.customer.jenis_identitas == 'ktp') set = 16;
              if (this.customer.jenis_identitas == 'sim') set = 12;
              if (this.customer.jenis_identitas == 'kitap') set = 16;
              return set;
            }
          }
        });

        function pilihItem(item) {
          form_.customer.tipe_ahm = item.id_tipe_kendaraan + ' | ' + item.tipe_ahm;
          form_.customer.id_tipe_kendaraan = item.id_tipe_kendaraan;
          form_.customer.warna = item.id_warna + ' | ' + item.warna;
          form_.customer.id_warna = item.id_warna;
        }

        function pilihKelurahan(data) {
          console.log(kelurahan_untuk);
          if (kelurahan_untuk == 'customer') {
            form_.customer.kelurahan = data.kelurahan;
            form_.customer.id_kelurahan = data.id_kelurahan;
            form_.customer.kecamatan = data.kecamatan;
            form_.customer.kabupaten = data.kabupaten;
            form_.customer.provinsi = data.provinsi;
          } else if (kelurahan_untuk == 'identitas') {
            form_.customer.kelurahan_identitas = data.kelurahan;
            form_.customer.id_kelurahan_identitas = data.id_kelurahan;
            form_.customer.kecamatan_identitas = data.kecamatan;
            form_.customer.kabupaten_identitas = data.kabupaten;
            form_.customer.provinsi_identitas = data.provinsi;
          }
        }

        function pilihAllCustomer(customer) {
          $.ajax({
            beforeSend: function() {
              $('#searchCustomer').attr('disabled', true);
              $('#searchCustomer').html('<i class="fa fa-spinner fa-spin">');
            },
            url: '<?= base_url('api/H2/getCustomer') ?>',
            type: "POST",
            data: customer,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                form_.customer = response.data;
                form_.customer_old = response.data;
                if (form_.customer_from != 'booking') {
                  form_.customer_from = customer.customer_from;
                }
                form_.customer.id_booking = customer.id_booking;
                form_.customer_old.id_booking = customer.id_booking;
                form_.customer.nama_pembawa = customer.nama_pembawa;
                form_.customer_old.nama_pembawa = customer.nama_pembawa;
                form_.customer.keluhan = customer.keluhan;
                form_.customer_old.keluhan = customer.keluhan;
                $("#id_type").val(customer.id_type).trigger('change');

              } else {
                alert(response.pesan);
              }
              $('#searchCustomer').attr('disabled', false);
              $('#searchCustomer').html('<i class="fa fa-search">');
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#searchCustomer').attr('disabled', false);
              $('#searchCustomer').html('<i class="fa fa-search">');

            }
          });
          form_.customer = customer;
          form_.customer_old = customer;
          form_.customer_from = customer.customer_from;
        }

        function pilihCustomerBooking(cust) {
          console.log(cust);
          pilihAllCustomer(cust);
          form_.customer_from = 'booking';
        }

        function customerH23Baru() {
          if (confirm('Apakah Anda yakin ?') == true) {
            form_.customer = {
              tipe_ahm: '',
              kelurahan: '',
              kelurahan_identitas: ''
            }
            form_.customer_from = 'baru';
            form_.edit_cust = 1;
            $('#modalAllCustomer').modal('hide');
            $('#tgl_pembelian').attr('disabled', false);
          }
        }
        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-input').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-input').removeClass('has-error');
            }
          })
          var values = {
            edit_cust: form_.edit_cust,
            customer_from: form_.customer_from,
            customer: form_.customer
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if($('#no_rangka_input').val().length < 17) {
              alert('No Rangka minimal 17 karakter');
              return false; 
            }

            if ($('#no_mesin_input').val().length !== 12) {
              alert('No Mesin harus 12 karakter');
              return false; 
            }

            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('dealer/manage_queue/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    $('#submitBtn').attr('disabled', false);
                    alert(response.pesan);
                  }
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').attr('disabled', false);

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/manage_queue/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
            <?php endif; ?>
            <a href="dealer/manage_queue/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
          <?php
          }
          $_SESSION['pesan'] = '';

          ?>
          <table id="datatables_" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No. Antrian</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($antrian->result() as $rs) :
                $status = '';
                $button = '';
                // $btn_sa_form = '<button type="button" onclick="closePrompt(\'$rs->id_booking\')" class="btn btn-danger btn-xs btn-flat">Cancel</button>';
                $btn_print = '<a href="dealer/manage_queue/cetak?id=' . $rs->id_antrian . '" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i></a>';
                $btn_edit = '<a style="margin-right:2px" href="dealer/manage_queue/edit?id=' . $rs->id_antrian . '" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-edit"></i></a>';
                if (can_access($isi, 'can_update')) $button .= $btn_edit;
                if (can_access($isi, 'can_print')) $button .= $btn_print;
              ?>
                <tr>
                  <td><?= $rs->id_antrian_short ?></td>
                  <td><?= date_dmy($rs->tgl_servis) ?></td>
                  <td><?= $rs->jenis_customer ?></td>
                  <td><?= $rs->no_polisi == NULL ? $rs->no_polisi_antri : $rs->no_polisi ?></td>
                  <td><?= $rs->nama_customer ?></td>
                  <td><?= $rs->no_mesin == NULL ? $rs->no_mesin_antri : $rs->no_mesin ?></td>
                  <td><?= $rs->no_rangka ?></td>
                  <td><?= $rs->tipe_ahm ?></td>
                  <td><?= $rs->warna ?></td>
                  <td><?= $rs->tahun_produksi ?></td>
                  <td align='center'><?= $button ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
          <script>
            $(function() {
              $('#datatables_').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "scrollX": true,
                "order": [],
                "info": true,
                fixedHeader: true,
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "autoWidth": true
              })
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == "history") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/manage_queue">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_history" style="width: 100%">
            <thead>
              <tr>
                <th>No. Antrian</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Tahun Motor</th>
                <!-- <th>Status</th> -->
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_history').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('dealer/manage_queue/fetch_history') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    // d.kode_item     = $('#kode_item').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  // {
                  //   "targets": [11],
                  //   "className": 'text-center'
                  // },
                  // { "targets":[4], "searchable": false } 
                ]
              });
            });
            // function loads()
            // {
            //   alert('d');
            //     $('#tabel_harga_sebelumnya').DataTable().ajax.reload();
            // }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } ?>
  </section>
</div>