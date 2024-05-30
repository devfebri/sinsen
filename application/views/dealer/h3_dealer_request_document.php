<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">  
<!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><?= $title; ?></h1>
    <?= $breadcrumb ?>
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
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      } 
    ?>
      <script>
        Vue.use(VueNumeric.default);
      </script>
      <div id="form_" class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div v-if="loading" class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <h4><b>Masukkan data Request Document</b></h4>
                  <div class="form-group">
                    <div v-bind:class="{ 'has-error' : errors.id_customer != null }" class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No. Customer</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input type="text" class="form-control" v-model='request_document.id_customer' readonly>
                          <div class="input-group-btn">
                            <button v-show='customer_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' :data-target='mode != "detail" ? "#modal-customer" : ""'><i class="fa fa-search"></i></button>
                            <button v-show='!customer_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_customer'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label no-padding">Masukkan Pemesan</label>
                      <div class="col-sm-8">
                        <input :disabled='mode == "detail"' type="checkbox" v-model='request_document.masukkan_pemesan' true-value='1' false-value='0'>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Nama Customer</label>
                      <div class="col-sm-8">
                        <input disabled type="text" class="form-control" v-model="request_document.nama_customer">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label v-if='request_document.masukkan_pemesan == 1' for="inputEmail3" class="col-sm-4 control-label">Nama Pemesan</label>
                      <div v-if='request_document.masukkan_pemesan == 1' class="col-sm-8">
                        <input :readonly='mode == "detail"' type="text" class="form-control" v-model="request_document.nama_pemesan">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Nomor Identitas</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.no_identitas" disabled>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label v-if='request_document.masukkan_pemesan == 1' for="inputEmail3" class="col-sm-4 control-label">No HP Pemesan</label>
                      <div v-if='request_document.masukkan_pemesan == 1' class="col-sm-8">
                        <input :readonly='mode == "detail"' type="text" class="form-control" v-model="request_document.no_hp">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div v-bind:class="{ 'has-error' : error_exist(errors, 'no_hp_customer') }" class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No Telepon</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" v-model="request_document.no_hp_customer" :readonly='mode == "detail"'>
                        <small v-if="error_exist(errors, 'no_hp_customer')" class="form-text text-danger">{{ get_error(errors, 'no_hp_customer') }}</small>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Alamat</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.alamat" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <!-- <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Kelurahan</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.kelurahan" disabled>
                      </div>
                    </div> -->
                    <div v-bind:class="{ 'has-error' : error_exist(errors, 'kelurahan') }"  class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Kelurahan</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.kelurahan" readonly>
                        <input type="hidden" class="form-control" :value="request_document.id_kelurahan" readonly>
                        <small v-if="error_exist(errors, 'kelurahan')" class="form-text text-danger">{{ get_error(errors, 'kelurahan') }}</small>
                      </div>
                    </div>
                    <div class="col-sm-1" v-if="request_document.kelurahan== null">
                    <!-- <div class="col-sm-1" > -->
                        <input type="hidden" class="form-control" :value="request_document.id_kelurahan" readonly>
                        <input type="hidden" class="form-control" :value="request_document.kelurahan" readonly>
                          <button type="button" onclick="showModalKelurahan()" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Kecamatan</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.kecamatan" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Kabupaten</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.kabupaten" disabled>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Kota</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.provinsi" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No Polisi</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.no_polisi" disabled>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Tipe Kendaraan</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.tipe_kendaraan" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Deskripsi Unit</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.deskripsi_unit" disabled>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Deskripsi Warna</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.deskripsi_warna" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div v-bind:class="{ 'has-error' : error_exist(errors, 'no_mesin') }" class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No Mesin</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" v-model="request_document.no_mesin" :readonly='mode == "detail"'>
                        <small v-if="error_exist(errors, 'no_mesin')" class="form-text text-danger">{{ get_error(errors, 'no_mesin') }}</small>
                      </div>
                    </div>
                    <div v-bind:class="{ 'has-error' : error_exist(errors, 'no_rangka') }" class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No Rangka</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" v-model="request_document.no_rangka" :readonly='mode == "detail"'>
                        <small v-if="error_exist(errors, 'no_rangka')" class="form-text text-danger">{{ get_error(errors, 'no_rangka') }}</small>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Tahun Perakitan</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.tahun_produksi" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No. Sa Form</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input type="text" class="form-control" :value="request_document.id_sa_form" readonly>
                          <div class="input-group-btn">
                            <button v-show='sa_form_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' :data-target='mode != "detail" ? "#sa_form_request_document" : ""'><i class="fa fa-search"></i></button>
                            <button v-show='!sa_form_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_sa_form'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No. Work Order</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.id_work_order" data-toggle='modal' :data-target='mode != "detail" ? "#wo_request_document" : ""' readonly>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/sa_form_request_document') ?>
                  <script>
                    function pilih_sa_form_request_document(data){
                      form_.request_document.id_sa_form = data.id_sa_form;
                      form_.request_document.no_buku_claim_c2 = data.no_buku_claim_c2;
                      form_.request_document.no_claim_c2 = data.no_claim_c2;
                      wo_request_document_datatable.draw();
                    }
                  </script>
                  <?php $this->load->view('modal/wo_request_document') ?>
                  <script>
                    function pilih_wo_request_document(data){
                      form_.request_document.id_work_order = data.id_work_order;
                    }
                  </script>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No. Buku Claim C2</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.no_buku_claim_c2" disabled>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">No. Claim C2</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" :value="request_document.no_claim_c2" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label">Order To</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input v-show='!order_to_empty' readonly type="text" class="form-control" v-model="request_document.nama_dealer_terdekat">
                          <input v-show='order_to_empty' readonly type="text" class="form-control" value='MD'>
                          <div class="input-group-btn">
                            <button v-show='order_to_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button'  data-toggle="modal" data-target="#modal_dealer_terdekat"><i class="fa fa-search"></i></button>
                            <button v-show='!order_to_empty && mode != "detail"' @click.prevent='reset_order_to' class="btn btn-flat btn-danger" ><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                  <?php $this->load->view('modal/dealer_terdekat') ?>
                  <script>
                    function pilih_dealer_terdekat(data){
                      form_.request_document.nama_dealer_terdekat = data.nama_dealer;
                      form_.request_document.order_to = data.id_dealer
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Flag Renumbering</label>
                    <div class="col-sm-4">
                      <input :disabled='mode == "detail"' type="radio" value="1" v-model="request_document.penomoran_ulang">
                      <label>Yes</label>
                      <br>
                      <input :disabled='mode == "detail"' type="radio" value="0" v-model="request_document.penomoran_ulang">
                      <label>No</label>
                      <br>
                    </div>
                  </div>
                  <?php $this->load->view('dealer/h3_dealer_request_document_claim_entity') ?>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-4 control-label no-padding">Vehicle of The Road Flag</label>
                      <div class="col-sm-8">
                        <input :disabled='mode == "detail"' type="radio" value="1" v-model="request_document.vor">
                        <label>Yes</label>
                        <br>
                        <input :disabled='mode == "detail"' type="radio" value="0" v-model="request_document.vor">
                        <label>No</label>
                        <br>
                      </div>
                    </div>
                    <div class="col-sm-6">
                    <label for="inputEmail3" class="col-sm-4 control-label no-padding">Keterangan Tambahan</label>
                      <div class="col-sm-8">
                        <input :disabled='mode == "detail"' type="checkbox" v-model='request_document.ada_keterangan_tambahan' true-value='1' false-value='0'>
                        <textarea v-show='request_document.ada_keterangan_tambahan == 1' :disabled="mode == 'detail'" rows="1" class="form-control auto-resize" v-model='request_document.keterangan_tambahan'></textarea>
                      </div>
                    </div>
                  </div>
                  <style>
                    textarea.auto-resize{  
                    /* box-sizing: padding-box; */
                    overflow:hidden;
                    /* demo only: */
                    padding:10px;
                    width:250px;
                    font-size:14px;
                    margin:50px auto;
                    display:block;
                    border-radius:10px;
                    border:6px solid #556677;
                  }
                  </style>
                  <script>
                  $(document).ready(function(){
                    textarea = $('textarea.auto-resize');

                    textarea.keydown(autosize);
                  });
                  function autosize(){
                    var el = this;
                    setTimeout(function(){
                      el.style.cssText = 'height:auto; padding:0';
                      el.style.cssText = 'height:' + (el.scrollHeight + 20) + 'px';
                    },0);
                  }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Job Return</label>
                    <div class="col-sm-4">
                      <input :disabled='mode == "detail"' type="radio" value="1" v-model="request_document.job_return_flag">
                      <label>Yes</label>
                      <br>
                      <input :disabled='mode == "detail"' type="radio" value="0" v-model="request_document.job_return_flag">
                      <label>No</label>
                      <br>
                    </div>
                  </div>
                  <div class="form-group">
                   <div class="col-sm-6">
                    <div v-if='mode == "detail"'>
                      <a v-if='request_document.no_inv_uang_jaminan != null && request_document.po_id == null' :href="'dealer/h3_dealer_purchase_order/add?generateByBooking=true&booking='+ request_document.id_booking" class="btn btn-flat btn-primary btn-sm">Purchase Order</a>
                      <a :href="'dealer/h3_dealer_customer_h23/detail?k=' + request_document.id_customer" class="btn btn-flat btn-success btn-sm">Customer Dealer</a>
                      <a :href="'dealer/h3_dealer_sales_order/add?generateByBooking=true&booking='+ request_document.id_booking" class="btn btn-flat btn-info btn-sm">Sales Order</a></div>
                   </div>
                   <div class="col-sm-6">
                      <label for="inputEmail3" class="col-sm-2 control-label">Uang Muka</label>
                      <div v-bind:class="{ 'has-error' : errors.uang_muka != null }" class="col-sm-4">
                        <div>
                          <vue-numeric :readonly='mode == "detail"' class='form-control' v-model='request_document.uang_muka' currency='Rp ' separator='.' :max='grand_total'/>
                        </div>
                        <small v-if="error_exist(errors, 'uang_muka')" class="form-text text-danger">{{ get_error('uang_muka') }}</small>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Sisa Pembayaran</label>
                      <div class="col-sm-4">
                        <vue-numeric readonly class='form-control' v-model='sisa_pembayaran' currency='Rp ' separator='.'/>
                      </div>
                   </div>
                  </div>
                  <?php $this->load->view('modal/customer_request_document') ?>
                  <script>
                    function pilihCustomer(customer) {
                      form_.request_document.id_customer = customer.id_customer;
                      form_.request_document.nama_customer = customer.nama_customer;
                      form_.request_document.no_identitas = customer.no_identitas;
                      form_.request_document.no_hp_customer = customer.no_hp;
                      form_.request_document.kelurahan = customer.kelurahan;
                      form_.request_document.id_kelurahan = customer.id_kelurahan;
                      form_.request_document.kabupaten = customer.kabupaten;
                      form_.request_document.kecamatan = customer.kecamatan;
                      form_.request_document.provinsi = customer.provinsi;
                      form_.request_document.alamat = customer.alamat;
                      form_.request_document.no_polisi = customer.no_polisi;
                      form_.request_document.tipe_kendaraan = customer.tipe_kendaraan;
                      form_.request_document.deskripsi_unit = customer.deskripsi_unit;
                      form_.request_document.deskripsi_warna = customer.warna;
                      form_.request_document.no_mesin = customer.no_mesin;
                      form_.request_document.no_rangka = customer.no_rangka;
                      form_.request_document.tahun_produksi = customer.tahun_produksi;
                    }
                  </script>
                  <table class="table">
                    <tr>
                      <td width='3%'>No.</td>
                      <td>Nomor Parts</td>
                      <td>Deskripsi Parts</td>
                      <td>Import/Lokal</td>
                      <td>Current/Non-Current</td>
                      <td>Kuantitas Max Order</td>
                      <td width="10%">Kuantitas Order</td>
                      <td class="text-right" width="10%">Harga</td>
                      <td class="text-right" width="10%">Sub total</td>
                      <td width='10%'>ETA Tercepat</td>
                      <td width='10%'>ETA Terlama</td>
                      <td width='10%'>ETA Revisi</td>
                      <td width='10%' v-if="request_document.status =='Revisi Dealer' && mode !='insert'">Revisi Part</td>
                      <td v-if="mode!='detail'" width="3%"></td>
                    </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">
                        {{ part.id_part }}
                        <input type="hidden" name="id_part[]" :value="part.id_part">
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <span v-if='part.import_lokal =="N"'> Lokal </span>
                        <span v-if='part.import_lokal == "Y"'> Import</span>
                      </td>
                      <td class="align-middle">
                        <span v-if='part.current =="C"'> Current </span>
                        <span v-if='part.current == "N"'> Non-Current</span>
                      </td>
                      <td class="align-middle">
                        <span v-if='part.hoo_flag =="Y"'> {{part.hoo_max}}</span>
                        <span v-else>-</span>
                      </td>
                      <td v-if='part.hoo_flag=="Y"' class="align-middle text-right"> 
                        <input type="hidden" name="kuantitas[]" :value="part.kuantitas">
                        <vue-numeric :read-only="mode=='detail'" class="form-control" thousand-separator="." v-model="part.kuantitas" :empty-value="1" v-on:keyup.native="kuantitas_handler" v-bind:max='part.hoo_max'/>
                        
                      </td>
                      <td v-else class="align-middle text-right"> 
                        <input type="hidden" name="kuantitas[]" :value="part.kuantitas">
                        <vue-numeric :read-only="mode=='detail'" class="form-control" thousand-separator="." v-model="part.kuantitas" :empty-value="1" v-on:keyup.native="kuantitas_handler"/>
                      </td>
                      <!-- <td>
                        <input type="hidden" name="kuantitas[]" :value="part.kuantitas">
                        <vue-numeric :read-only="mode=='detail'" class="form-control" thousand-separator="." v-model="part.kuantitas" :empty-value="1" v-on:keyup.native="kuantitas_handler" />
                      </td> -->
                      <td class="align-middle text-right">
                        <input type="hidden" name="harga_saat_dibeli[]" v-model="part.harga_saat_dibeli">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.harga_saat_dibeli" />
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="subTotal(part)" />
                      </td>
                      <td class="align-middle">
                        <span v-if='part.eta_tercepat == null'>-</span>
                        <span v-if='part.eta_tercepat != null'>{{ moment(part.eta_tercepat).format('DD/MM/YYYY') }}</span>
                      </td>
                      <td class="align-middle">
                        <span v-if='part.eta_terlama == null'>-</span>
                        <span v-if='part.eta_terlama != null'>{{ moment(part.eta_terlama).format('DD/MM/YYYY') }}</span>
                      </td>
                      <td class="align-middle">
                        <span v-if='part.eta_revisi == null'>-</span>
                        <span v-if='part.eta_revisi != null'>{{ moment(part.eta_revisi).format('DD/MM/YYYY') }}</span>
                      </td>
                      <td v-if="mode=='detail' && request_document.status =='Revisi Dealer'" class="align-middle text-center">
                        <span v-if='part.alasan_part_revisi_md'> {{ part.alasan_part_revisi_md }}</span>
                      </td>
                      <td v-if="mode!='detail'" class="align-middle">
                        <button class="btn btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length > 0">
                      <td class="text-right" colspan="8">Total</td>
                      <td class="text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="grand_total" />
                      </td>
                      <td colspan='3'></td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td v-bind:class="{ 'bg-red': errors.parts != null }" colspan="9" class="text-center text-muted">Belum ada part</td>
                    </tr>
                  </table>
                  <button v-if="mode!='detail'" style="margin-top:15px" type="button" class="mt-3 pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#part_request_document"><i class="fa fa-plus"></i></button>
                  <?php $this->load->view('modal/part_request_document'); ?>
                  <script>
                    function pilih_parts_request_document(part)
                    {
                      form_.parts.push(part);
                      form_.update_eta_parts();
                    }
                  </script>
                  <div class="box-footer">
                    <div class="col-sm-6">
                      <button v-if="mode=='insert'" @click.prevent="<?= $form ?>" type="submit" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button v-if="mode=='edit'" @click.prevent="<?= $form ?>" type="submit" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                      <a v-if='auth.can_update && mode == "detail" && request_document.status == "Open" && request_document.po_id == null' :href="'dealer/<?= $isi ?>/edit?k=' + request_document.id_booking"><button type="button" class="btn btn-sm btn-warning btn-flat">Edit</button></a>
                    </div>
                    <div class="col-sm-6 no-padding text-right">
                        <a v-if='auth.can_submit && mode == "detail" && request_document.status == "Open"' :href="'dealer/<?= $isi ?>/process_dp?k=' + request_document.id_booking"><button type="button" class="btn btn-sm btn-primary btn-flat">Proses Bayar DP</button></a>
                        <a v-if='auth.can_cancel && mode == "detail" && request_document.status == "Open" && request_document.po_id == null' :href="'dealer/<?= $isi ?>/cancel?k=' + request_document.id_booking"><button type="button" class="btn btn-sm btn-danger btn-flat">Cancel</button></a>
                        <a v-if='auth.can_print && mode == "detail"' :href="'dealer/<?= $isi ?>/cetak?k=' + request_document.id_booking"><button type="button" class="btn btn-sm btn-info btn-flat">Print Cetak Dokumen</button></a>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = ['kelurahan'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            auth: <?= json_encode(get_user('h3_dealer_request_document')) ?>, 
            loading: false,
            mode: '<?= $mode ?>',
            <?php if ($mode == 'detail' or $mode == 'edit') : ?>
              request_document: <?= json_encode($request_document) ?>,
              parts: <?= json_encode($parts) ?>,
            <?php else : ?>
              parts: <?= $this->input->get('id_customer') != null ? json_encode($parts) : '[]' ?>,
              request_document : {
                id_customer: '',
                nama_customer: '',
                no_identitas: '',
                no_hp_customer: '',
                kelurahan: '',
                id_kelurahan: '',
                kabupaten: '',
                no_polisi: '',
                deskripsi_unit: '',
                tahun_perakitan: '',
                alamat: '',
                kecamatan: '',
                kota: '',
                tipe_kendaraan: '',
                deskripsi_warna: '',
                no_rangka: '',
                no_mesin: '',
                tahun_produksi: '',
                order_to: null,
                nama_dealer_terdekat: '',
                

                id_sa_form: '',
                id_work_order: '',
                no_buku_claim_c2: '',
                no_claim_c2: '',
                penomoran_ulang: 0,
                tipe_penomoran_ulang: '',
                form_warranty_claim_c2_c2: '',
                copy_faktur_ahm_claim_c1_c2: 0,
                gesekan_nomor_framebody_claim_c1_c2: 0,
                gesekan_nomor_crankcase_claim_c1_c2: 0,
                copy_ktp_claim_c1_c2: 0,
                copy_stnk_claim_c1_c2: 0,
                copy_bpkb_faktur_ahm_non_claim: 0,
                copy_stnk_non_claim: 0,
                copy_ktp_non_claim: 0,
                gesekan_nomor_framebody_non_claim: 0,
                gesekan_nomor_crankcase_non_claim: 0,
                potongan_no_rangka_mesin_non_claim: 0,
                surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim: 0,
                surat_laporan_forensik_kepolisian_non_claim: 0,

                vor: 0,
                job_return_flag: 0,
                ada_keterangan_tambahan: 0,
                keterangan_tambahan: '',
                uang_muka: 0,
                
                masukkan_pemesan: 0,
                nama_pemesan: '',
                no_hp: '',
              },
            <?php endif; ?>
            errors: [],
            errors_update_no_rangka_mesin: []
          },
          mounted: function(){
            <?php if($this->input->get('generateByCustomer') == 'true'): ?>
            <?php 
              $customer = $this->db
              ->select('c.*, kel.kelurahan, kel.id_kelurahan,kec.kecamatan, kab.kabupaten, prov.provinsi, tk.tipe_ahm as tipe_kendaraan, w.warna')
              ->select('tk.deskripsi_ahm as deskripsi_unit')
              ->from('ms_customer_h23 as c')
              ->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan')
              // ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan')
              // ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten')
              // ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi')
              ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
              ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
              ->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
              ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan')
              ->join('ms_warna as w', 'w.id_warna = c.id_warna')
              ->where('c.id_customer', $this->input->get('customer'))
              ->limit(1)
              ->get()->row();
            ?>
            this.request_document.id_customer = '<?= $customer->id_customer ?>';
            this.request_document.nama_customer = '<?= $customer->nama_customer ?>';
            this.request_document.no_identitas = '<?= $customer->no_identitas ?>';
            this.request_document.no_hp_customer = '<?= $customer->no_hp ?>';
            this.request_document.kelurahan = '<?= $customer->kelurahan ?>';
            this.request_document.id_kelurahan = '<?= $customer->id_kelurahan ?>';
            this.request_document.kabupaten = '<?= $customer->kabupaten ?>';
            this.request_document.kecamatan = '<?= $customer->kecamatan ?>';
            this.request_document.provinsi = '<?= $customer->provinsi ?>';
            this.request_document.alamat = '<?= $customer->alamat ?>';
            this.request_document.no_polisi = '<?= $customer->no_polisi ?>';
            this.request_document.tipe_kendaraan = '<?= $customer->tipe_kendaraan ?>';
            this.request_document.deskripsi_unit = '<?= $customer->deskripsi_unit ?>';
            this.request_document.deskripsi_warna = '<?= $customer->warna ?>';
            this.request_document.no_mesin = '<?= $customer->no_mesin ?>';
            this.request_document.no_rangka = '<?= $customer->no_rangka ?>';
            this.request_document.tahun_produksi = '<?= $customer->tahun_produksi ?>';
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function() {
              this.errors = [];
              this.loading = true;
              keys = [
                'id_customer', 'no_hp_customer', 'no_rangka', 'no_mesin','id_kelurahan', 'kelurahan', 
                'id_sa_form',
                'no_buku_claim_c2',
                'no_claim_c2',
                'penomoran_ulang',
                'tipe_penomoran_ulang',
                'form_warranty_claim_c2_c2',
                'copy_faktur_ahm_claim_c1_c2',
                'gesekan_nomor_framebody_claim_c1_c2',
                'gesekan_nomor_crankcase_claim_c1_c2',
                'copy_ktp_claim_c1_c2',
                'copy_stnk_claim_c1_c2',
                'copy_bpkb_faktur_ahm_non_claim',
                'copy_stnk_non_claim',
                'copy_ktp_non_claim',
                'gesekan_nomor_framebody_non_claim',
                'gesekan_nomor_crankcase_non_claim',
                'potongan_no_rangka_mesin_non_claim',
                'surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim',
                'surat_laporan_forensik_kepolisian_non_claim',
                'vor',
                'job_return_flag',
                'ada_keterangan_tambahan',
                'keterangan_tambahan',
                'uang_muka',
                'masukkan_pemesan',
                'nama_pemesan',
                'no_hp',
                'order_to',
              ];

              if(this.mode == 'edit'){
                keys.push('id_booking');
                keys.push('id_data_pemesan');
              }

              post = _.pick(this.request_document, keys);

              post.parts = _.map(this.parts, function(p){
                return _.pick(p, ['id_part', 'harga_saat_dibeli', 'kuantitas', 'eta_terlama', 'eta_tercepat', 'eta_revisi']);
              });

              axios.post('dealer/h3_dealer_request_document/<?= $form ?>', Qs.stringify(post))
              .then(function(res) {
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url; 
              })
              .catch(function(err) {
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  form_.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
              })
              .then(function(){
                form_.loading = false;
              });
            },
            subTotal: function(part) {
              return part.kuantitas * part.harga_saat_dibeli
            },
            hapusPart: function(index) {
              this.parts.splice(index, 1);
              this.update_eta_parts();
            },
            reset_order_to: function(){
              this.request_document.order_to = null;
            },
            reset_sa_form: function(){
              this.request_document.id_sa_form = '';
            },
            reset_customer: function(){
              this.request_document.id_customer = '';
              this.request_document.nama_customer = '';
              this.request_document.no_identitas = '';
              this.request_document.no_hp_customer = '';
              this.request_document.kelurahan = '';
              this.request_document.id_kelurahan = '';
              this.request_document.kabupaten = '';
              this.request_document.kecamatan = '';
              this.request_document.provinsi = '';
              this.request_document.alamat = '';
              this.request_document.no_polisi = '';
              this.request_document.tipe_kendaraan = '';
              this.request_document.deskripsi_unit = '';
              this.request_document.deskripsi_warna = '';
              this.request_document.no_mesin = '';
              this.request_document.no_rangka = '';
              this.request_document.tahun_produksi = '';
            },
            reset_non_claim: function(){
              this.request_document.copy_bpkb_faktur_ahm_non_claim = 0;
              this.request_document.copy_stnk_non_claim = 0;
              this.request_document.copy_ktp_non_claim = 0;
              this.request_document.gesekan_nomor_framebody_non_claim = 0;
              this.request_document.gesekan_nomor_crankcase_non_claim = 0;
              this.request_document.potongan_no_rangka_mesin_non_claim = 0;
              this.request_document.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim = 0;
              this.request_document.surat_laporan_forensik_kepolisian_non_claim = 0;
            },
            reset_claim_c1_c2: function(){
              this.request_document.form_warranty_claim_c2_c2 = '';
              this.request_document.copy_faktur_ahm_claim_c1_c2 = 0;
              this.request_document.gesekan_nomor_framebody_claim_c1_c2 = 0;
              this.request_document.gesekan_nomor_crankcase_claim_c1_c2 = 0;
              this.request_document.copy_ktp_claim_c1_c2 = 0;
              this.request_document.copy_stnk_claim_c1_c2 = 0;
            },
            update_eta_parts: function() {
              this.reset_eta();

              post = {};
              post.claim = this.request_document.penomoran_ulang;
              if (this.request_document.tipe_penomoran_ulang == 'claim_c1_c2') {
                post.tipe_claim = 'renumbering_claim';
              } else if (this.request_document.tipe_penomoran_ulang == 'non_claim') {
                post.tipe_claim = 'renumbering_non_claim';
              }
              post.parts = _.chain(this.parts)
              .map(function(part){
                return part.id_part;
              })
              .value();

              axios.post('dealer/h3_dealer_request_document/update_eta_parts', Qs.stringify(post))
              .then(function(res) {
                if(res.data.length > 0){
                  for (row of res.data) {
                    index_part = _.findIndex(form_.parts, function(part) { return part.id_part == row.id_part; });
                    if(index_part != -1){
                      form_.parts[index_part].eta_terlama = row.eta_terlama;                
                      form_.parts[index_part].eta_tercepat = row.eta_tercepat;
                    }
                  }
                }
              })
              .catch(function(e) {
                toastr.error(e);
              });
            },
            reset_eta: function(){
              for (index = 0; index < this.parts.length; index++) {
                this.parts[index].eta_terlama = null;                
                this.parts[index].eta_tercepat = null;                
              }
            },
            kuantitas_handler: _.debounce(function(){
              this.update_eta_parts();
            }, 500),
            error_exist: function(errors, key){
              return _.get(errors, key) != null;
            },
            get_error: function(errors, key){
              return _.get(errors, key)
            },
          },
          computed: {
            order_to_empty: function(){
              return this.request_document.order_to == 0 || this.request_document.order_to == null;
            },
            sa_form_empty: function(){
              return this.request_document.id_sa_form == '' || this.request_document.id_sa_form == null;
            },
            customer_empty: function(){
              return this.request_document.id_customer == '' || this.request_document.id_customer == null;
            },
            sisa_pembayaran: function(){
              return this.grand_total - this.request_document.uang_muka;
            },
            grand_total: function() {
              subTotalfn = this.subTotal;
              return _.chain(this.parts)
              .sumBy(function(part){
                return subTotalfn(part);
              })
              .value();
            },
            customer_terisi: function() {
              return this.request_document.id_customer != '' || this.request_document.id_customer != null;
            },
          },
          watch: {
            parts: {
              deep: true,
              handler: function(){
                part_request_document_datatable.draw();
              }
            },
            'request_document.penomoran_ulang': function() {
              this.update_eta_parts();
            },
            'request_document.order_to': function(data){
              part_request_document_datatable.draw();
            },
            <?php
              $claim_c1_c2 = [
                'form_warranty_claim_c2_c2',
                'copy_faktur_ahm_claim_c1_c2',
                'gesekan_nomor_framebody_claim_c1_c2',
                'gesekan_nomor_crankcase_claim_c1_c2',
                'copy_ktp_claim_c1_c2',
                'copy_stnk_claim_c1_c2',
              ];

              $non_claim = [
                'copy_bpkb_faktur_ahm_non_claim',
                'copy_stnk_non_claim',
                'copy_ktp_non_claim',
                'gesekan_nomor_framebody_non_claim',
                'gesekan_nomor_crankcase_non_claim',
                'potongan_no_rangka_mesin_non_claim',
                'surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim',
                'surat_laporan_forensik_kepolisian_non_claim',
              ];
            ?>
            <?php foreach($claim_c1_c2 as $each): ?>
            'request_document.<?= $each ?>': function(n, o){
              this.reset_non_claim();
            },
            <?php endforeach; ?>

            <?php foreach($non_claim as $each): ?>
            'request_document.<?= $each ?>': function(n, o){
              this.reset_claim_c1_c2();
            },
            <?php endforeach; ?>
            'request_document.tipe_penomoran_ulang': function(value){
              if(value == 'claim_c1_c2'){
                this.reset_non_claim();
              }else if(value == 'non_claim'){
                this.reset_claim_c1_c2();
              }
              this.update_eta_parts();
            }
          }
        });

        
        function pilihKelurahan(data) {
          console.log(kelurahan_untuk);
          // if (kelurahan_untuk == 'customer') {
            form_.request_document.kelurahan = data.kelurahan;
            form_.request_document.id_kelurahan = data.id_kelurahan;
            form_.request_document.kecamatan = data.kecamatan;
            form_.request_document.kabupaten = data.kabupaten;
            form_.request_document.provinsi = data.provinsi;
          // }
        }
      </script>
    <?php

    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
        <?php if(can_access('h3_dealer_request_document', 'can_insert')): ?>
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
          </h3>
        <?php endif; ?>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="request_document" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>ID Booking</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Tipe Motor</th>
                <th>No. Polisi</th>
                <th>Email</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <script>
          $(document).ready(function() {
            request_document = $('#request_document').DataTable({
                initComplete: function() {
                    $('#request_document_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                    $('#request_document_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                    axios.get('html/filter_request_document')
                        .then(function(res) {
                            $('#request_document_filter').prepend(res.data);
                        });
                },
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/index_request_document') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(data){
                      start_date = $('#filter_request_document_date_start').val();
                      end_date = $('#filter_request_document_date_end').val();
                      if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                          data.filter_request_document_date = true;
                          data.start_date = start_date;
                          data.end_date = end_date;
                      }
                    }
                },
                columns: [
                    { data: null, width: '3%', orderable: false },
                    { data: 'id_booking' },
                    { data: 'id_customer' },
                    { data: 'nama_customer' },
                    { data: 'alamat' },
                    { data: 'no_hp' },
                    { data: 'tipe_motor', name: 'tk.deskripsi' },
                    { data: 'no_polisi' },
                    { data: 'email' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
            request_document.on('draw.dt', function() {
              var info = request_document.page.info();
              request_document.column(0, {
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
    <?php
    }
    ?>
  </section>
</div>