<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
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
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
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
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
      if ($mode == 'revisi_po') {
        $form = 'save_revisi_po';
      }
    ?>
    <div id='app' class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
          <a href="h3/<?= $isi ?>/ps_dummy?id_purchase_order=<?= $this->input->get('id_purchase_order') ?>">
            <button class="btn btn-info btn-flat margin">.PS Dummy</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <div v-if='mode != "insert"'>
                    <label class="col-sm-2 control-label">No. PO</label>
                    <div class="col-sm-4">                    
                      <input type="text" readonly class="form-control" v-model='purchase.id_purchase_order'>                    
                    </div>  
                  </div>
                  <div v-show='is_htl || is_urg || mode != "insert"'>
                    <label class="col-sm-2 control-label">Tanggal PO</label>
                    <div v-bind:class="{ 'has-error': error_exist('tanggal_po') }" class="col-sm-4">                    
                      <date-picker :disabled='(is_fix || is_reg) || mode == "detail"' @update-date='tanggal_po_datepicker_change' class='form-control' readonly :config='config' v-model='purchase.tanggal_po'></date-picker>
                      <small v-if="error_exist('tanggal_po')" class="form-text text-danger">{{ get_error('tanggal_po') }}</small>                        
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Jenis PO</label>
                  <div v-bind:class="{ 'has-error': error_exist('jenis_po') }" class="col-sm-4">                    
                    <select :disabled="mode != 'insert' || purchase_order_logistik" v-model="purchase.jenis_po" class="form-control">
                        <option value="">-Pilih-</option>
                        <option value="REG">Reguler</option>
                        <option value="FIX">Fix</option>
                        <option value="HTL">Hotline</option>
                        <option value="URG">Urgent</option>
                    </select>
                    <small v-if="error_exist('jenis_po')" class="form-text text-danger">{{ get_error('jenis_po') }}</small>                    
                  </div>    
                  <div class="col-sm-2 no-padding">
                    <button v-if='(purchase.jenis_po == "FIX" || purchase.jenis_po == "REG") && this.mode != "detail"' @click.prevent='generate_parts' class="btn btn-flat btn-sm btn-info">Generate Parts</button>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Produk</label>
                  <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">                    
                    <select :disabled="mode == 'detail'" v-model="purchase.produk" class="form-control">
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Accesories</option>
                        <option value="Apparel">Apparel</option>
                        <option value="Tools">Tools</option>
                        <option value="Other">Other</option>
                    </select>
                    <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>                    
                  </div>
                </div>
                <div v-if='is_htl' class="form-group">
                  <label for="" class="col-sm-2 control-label">Referensi PO Hotline Dealer</label>
                  <div v-bind:class="{ 'has-error': error_exist('referensi_po_hotline') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='purchase.referensi_po_hotline'>
                      <div class="input-group-btn">
                        <button v-if='empty_referensi_po_hotline || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_po_hotline_purchase_order'><i class="fa fa-search"></i></button>
                        <button v-if='!empty_referensi_po_hotline && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_referensi_po_hotline'><i class="fa fa-trash-o"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('referensi_po_hotline')" class="form-text text-danger">{{ get_error('referensi_po_hotline') }}</small>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_po_hotline_purchase_order'); ?>
                <script>
                  function pilih_referensi_po_hotline_purchase_order(data){
                    app.purchase.referensi_po_hotline = data.referensi;
                  }
                </script>
                <div v-if='is_htl'>
                  <div class="container-fluid bg-blue-gradient" style='margin-bottom: 15px;'>
                    <div class="row" style='padding: 6px 0;'>
                      <div class="col-sm-12 text-center">
                        <span class="bold">Data Request Document</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">No. Customer</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.id_customer'>
                    </div>
                    <label for="" class="control-label col-sm-2">Nama Customer</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.nama_customer'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2 no-padding">Masukkan Pemesan</label>
                    <div class="col-sm-4">
                      <input disabled type="checkbox" v-model='purchase.masukkan_pemesan' true-value='1' false-value='0'>
                    </div>
                  </div>
                  <div v-if='purchase.masukkan_pemesan == 1' class="form-group">
                    <label for="" class="control-label col-sm-2">Nama Pemesan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.nama_pemesan'>
                    </div>
                    <label for="" class="control-label col-sm-2">No. HP Pemesan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_hp'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Nomor Identitas</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_identitas'>
                    </div>
                    <label for="" class="control-label col-sm-2">No. Telepon</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_hp_customer'> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Alamat</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.alamat'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Kelurahan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.kelurahan'>
                    </div>
                    <label for="" class="control-label col-sm-2">Kecamatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.kecamatan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Kabupaten/Kota</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.kabupaten'>
                    </div>
                    <label for="" class="control-label col-sm-2">Provinsi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.provinsi'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">No Polisi</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_polisi'>
                    </div>
                    <label for="" class="control-label col-sm-2">Tipe Kendaraan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.tipe_kendaraan'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Deskripsi Unit</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.deskripsi_unit'>
                    </div>
                    <label for="" class="control-label col-sm-2">Deskripsi Warna</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.deskripsi_warna'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">No Mesin</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_mesin'>
                    </div>
                    <label for="" class="control-label col-sm-2">No Rangka</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_rangka'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Tahun Perakitan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.tahun_produksi'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">ID SA Form</label>
                    <div class="col-sm-4">
                      <input readonly type="text" class="form-control" v-model='purchase.id_sa_form'>
                    </div>
                    <label for="" class="control-label col-sm-2">ID Work Order</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.id_work_order'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">No. Buku Claim C2</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_buku_claim_c2'>
                    </div>
                    <label for="" class="control-label col-sm-2">No. Claim C2</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" readonly v-model='purchase.no_claim_c2'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Flag Renumbering</label>
                    <div class="col-sm-4">
                      <input disabled type="radio" value="1" v-model="purchase.penomoran_ulang">
                      <label>Yes</label>
                      <br>
                      <input disabled type="radio" value="0" v-model="purchase.penomoran_ulang">
                      <label>No</label>
                      <br>
                    </div>
                  </div>
                  <div v-if="purchase.penomoran_ulang == '1'">
                      <div class="form-group">
                        <label class='control-label col-sm-2 no-padding'>Claim C1/C2</label>
                        <div class="col-sm-4">
                          <input disabled type="radio" value="claim_c1_c2" v-model="purchase.tipe_penomoran_ulang">
                        </div>
                        <label class='control-label col-sm-2 no-padding'>Non-Claim</label>
                        <div class="col-sm-4">
                          <input disabled type="radio" value="non_claim" v-model="purchase.tipe_penomoran_ulang">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                          <div class="col-sm-6 no-padding">
                            <span>Form Warranty Claim C1/C2</span>
                            <input disabled type="text" class="input-compact" v-model="purchase.form_warranty_claim_c2_c2">
                          </div>
                        </div>
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_bpkb_faktur_ahm_non_claim"> Copy BPKB/Faktur AHM
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_faktur_ahm_claim_c1_c2"> Copy Faktur AHM
                        </div>
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_stnk_non_claim"> Copy STNK
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_framebody_claim_c1_c2"> Gesekan Nomor Framebody (Rangka)
                        </div>
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_ktp_non_claim"> Copy KTP
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_crankcase_claim_c1_c2"> Gesekan Nomor Crankcase (Mesin)
                        </div>
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_framebody_non_claim"> Gesekan Nomor Framebody (Rangka)
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                          <span class="text-bold visible-lg-block">Khusus Untuk Claim C2</span>
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_ktp_claim_c1_c2"> Copy KTP
                        </div>
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_crankcase_non_claim"> Gesekan Nomor Crankcase (Mesin)
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_stnk_claim_c1_c2"> Copy STNK
                        </div>
                        <div class="col-sm-4 col-sm-offset-2">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.potongan_no_rangka_mesin_non_claim"> Potongan No Rangka/Mesin (Jangan Dipotong)*
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-8">
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim"> Surat Permohonan Penomoran Ulang Dari Kepolisian (Asli)
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-8">
                          <span class="text-bold visible-lg-block">Khusus untuk kasus nomor pada rangka/mesin tidak terbaca</span>
                          <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.surat_laporan_forensik_kepolisian_non_claim"> Surat laporan forensik kepolisian (Asli)
                        </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Vehicle of The Road Flag</label>
                    <div class="col-sm-4">
                      <input disabled type="radio" value="1" v-model="purchase.vor">
                      <label>Yes</label>
                      <br>
                      <input disabled type="radio" value="0" v-model="purchase.vor">
                      <label>No</label>
                      <br>
                    </div>
                    <label for="" class="control-label col-sm-2 no-padding">Keterangan Tambahan</label>
                    <div class="col-sm-4">
                      <input disabled type="checkbox" v-model='purchase.ada_keterangan_tambahan' true-value='1' false-value='0'>
                      <textarea v-show='purchase.ada_keterangan_tambahan == 1' disabled rows="1" class="form-control auto-resize" v-model='purchase.keterangan_tambahan'></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Job Return</label>
                    <div class="col-sm-4">
                      <input disabled type="radio" value="1" v-model="purchase.job_return_flag">
                      <label>Yes</label>
                      <br>
                      <input disabled type="radio" value="0" v-model="purchase.job_return_flag">
                      <label>No</label>
                      <br>
                    </div>
                  </div>
                </div>
                <div v-if='is_reg || is_fix' class="form-group">                  
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div v-bind:class="{ 'has-error': error_exist('keterangan') }" class="col-sm-4">           
                    <textarea :readonly='mode == "detail"' name="keterangan" class="form-control" rows="3" v-model='purchase.keterangan'></textarea>
                    <small v-if="error_exist('keterangan')" class="form-text text-danger">{{ get_error('keterangan') }}</small>   
                  </div>
                </div>
                <div v-if='is_reg || is_fix' class="form-group">
                  <label class="col-sm-2 control-label">Bulan</label>
                  <div v-bind:class="{ 'has-error': error_exist('bulan') }" class="col-sm-4">
                    <date-picker :disabled='mode == "detail"' @update-date='bulan_datepicker_change' class='form-control' readonly :config='bulan_date_config' v-model='purchase.bulan'></date-picker>
                    <small v-if="error_exist('bulan')" class="form-text text-danger">{{ get_error('bulan') }}</small>                       
                  </div>
                </div>
                <div v-if='is_reg || is_fix' class="form-group">
                  <label class="col-sm-2 control-label">Tahun</label>
                  <div v-bind:class="{ 'has-error': error_exist('tahun') }" class="col-sm-4">
                    <date-picker :disabled='mode == "detail"' @update-date='year_datepicker_change' class='form-control' readonly :config='year_date_config' v-model='purchase.tahun'></date-picker>
                    <small v-if="error_exist('tahun')" class="form-text text-danger">{{ get_error('tahun') }}</small>                       
                  </div>
                </div>
                <div v-if='mode != "insert"' class="form-group">
                  <label class="col-sm-2 control-label">Status</label>
                  <div  class="col-sm-4">
                    <input type="text" readonly class='form-control' v-model='purchase.status'>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-8 col-sm-2 no-padding">
                    <label for="" class="control-label">Filter Kelompok Part</label>
                    <div class="col-sm-12 no-padding">
                      <div class="input-group">
                        <input :value='filter_kelompok_part.length + " Kelompok Part"' type="text" class="form-control" readonly>
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_filter_kelompok_part_purchase_order'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <label for="" class="control-label">Cari Kode Part</label>
                    <input type="text" class="form-control" v-model='filter_kode_part'>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_filter_kelompok_part_purchase_order'); ?>
                <script>
                $(document).ready(function(){
                  $("#h3_md_filter_kelompok_part_purchase_order").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_kelompok_part = target.attr('data-id-kelompok-part');

                    if(target.is(':checked')){
                      app.filter_kelompok_part.push(id_kelompok_part);
                    }else{
                      index_kelompok_part = _.indexOf(app.filter_kelompok_part, id_kelompok_part);
                      app.filter_kelompok_part.splice(index_kelompok_part, 1);
                    }
                    h3_md_filter_kelompok_part_purchase_order_datatable.draw();
                  });
                });
                </script>
                <div class="form-group">
                  <div class="col-sm-12">
                    <div class="tableFixHead">
                      <table id="table" class="table table-condensed table-responsive">
                        <thead>
                          <tr>                                      
                            <th width='3%'>No.</th>            
                            <th v-if='is_urg'>Nomor SO/PO</th>              
                            <th v-if='is_urg'>Nama Dealer</th>              
                            <th>Kode Part</th>              
                            <th>Nama Part</th>        
                            <th v-if='is_htl'>Import/Lokal</th>  
                            <th v-if='is_htl'>Current/Non-Current</th>         
                            <th v-if='is_htl'>Flag Hotline</th>  
                            <th v-if='is_htl'>Qty Max</th>                 
                            <th v-if='is_urg'>Tipe Motor</th>              
                            <th v-if='is_fix || is_reg'>Kelompok</th>              
                            <th class='text-right' v-if='is_fix || is_reg'>Qty Min Order</th>
                            <th class='text-right' v-if='is_fix || is_reg'>Qty AVS</th>
                            <th class='text-right'>Qty In Transit</th>
                            <th class='text-right' v-if='is_fix || is_reg'>Qty BO</th>
                            <th class='text-right' v-if='is_fix || is_reg'>AVG Sales</th>              
                            <th class='text-right' v-if='is_fix || is_reg'>Qty BO Dealer</th>
                            <th class='text-right' v-if='is_fix || is_reg'>Fix Bulan Lalu</th>              
                            <th class='text-right'>Qty On Hand</th>              
                            <th class='text-right' v-if='is_fix || is_reg'>Qty Suggest</th>
                            <th class='text-right' width='5%'>Qty Order</th>
                            <th width='8%' class='text-right' v-if='is_fix || is_reg || is_htl'>HPP</th>
                            <th width='8%' class='text-right' v-if='is_fix || is_reg || is_htl'>Total Harga</th>
                            <th v-if="mode != 'detail' && !purchase_order_logistik" width="3%"></th>
                            <th class='text-center' v-if='is_htl'>ETA Terlama</th>
                            <th class='text-center' v-if='is_htl'>ETA Tercepat</th>
                            <th class='text-center' v-if='is_htl'>ETA Revisi</th>
                            <th class='text-center' v-if="mode=='revisi_po' || purchase_order_status_reject" class="text-right">Alasan Revisi</th>
                          </tr>
                        </thead>
                        <tbody>            
                          <tr v-for="(part, index) in filtered_parts">
                            <td class="align-middle">{{ index + 1 }}.</td>
                            <td v-if='is_urg' class="align-middle">{{ part.referensi }}</td>                       
                            <td v-if='is_urg' class="align-middle">{{ part.nama_dealer }}</td>   
                            <td class="align-middle">{{ part.id_part }}</td>                       
                            <td class="align-middle">{{ part.nama_part }}</td>
                            <td v-if='is_htl' class="align-middle">
                              <span v-if='part.import_lokal == "N"'> Lokal</span>
                              <span v-if='part.import_lokal == "Y"'> Import</span>
                              <!-- {{ part.import_lokal }}  -->
                            </td> 
                            <td v-if='is_htl' class="align-middle">
                              <span v-if='part.current == "C"'> Current</span>
                              <span v-if='part.current == "N"'> Non-Current</span> 
                              <!-- {{ part.current }}  -->
                            </td>                       
                            <td v-if='is_urg' class="align-middle">
                              <span v-if='part.tipe_ahm == null'>-</span>
                              <span v-if='part.tipe_ahm != null'>{{ part.tipe_ahm }}</span>
                            </td>   
                            <td v-if='is_htl' class="align-middle">
                              <span v-if='part.hoo_flag == ""'> - </span>
                              <span v-else> {{part.hoo_flag}}</span> 
                            </td> 
                            <td v-if='is_htl' class="align-middle">
                              <span v-if='part.hoo_max == ""'> - </span>
                              <span v-else> {{part.hoo_max}}</span> 
                            </td>                      
                            <td v-if='is_fix || is_reg' class="align-middle">{{ part.kelompok_part }}</td>                       
                            <td v-if='is_fix || is_reg' class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_min_order'></vue-numeric>
                            </td>                       
                            <td v-if='is_fix || is_reg' class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_avs'></vue-numeric>
                            </td> 
                            <td class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_in_transit'></vue-numeric>
                            </td>                       
                            <td v-if='is_fix || is_reg' class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_bo'></vue-numeric>
                            </td>  
                            <td v-if='is_fix || is_reg' class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.avg_sales'></vue-numeric>
                            </td>
                            <td v-if='is_fix || is_reg' class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_bo_dealer'></vue-numeric>
                            </td>
                            <td v-if='is_fix || is_reg' class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.fix_bulan_lalu'></vue-numeric>
                            </td>
                            <td class="align-middle text-right">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_on_hand'></vue-numeric>
                            </td>                       
                            <td v-if='is_fix || is_reg' class="align-middle text-right bg-gray">
                              <vue-numeric read-only class="form-control" separator='.' v-model='part.qty_suggest'></vue-numeric>
                            </td>                       
                            <td class="align-middle text-right bg-blue">
                              <vue-numeric :read-only='mode == "detail" || is_htl || is_urg' class="form-control" separator='.' :min='get_min_order(part)' v-model='part.qty_order'></vue-numeric>
                            </td> 
                            <td v-if='is_reg || is_fix || is_htl' class="align-middle text-right">
                              <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model='part.harga'/>
                            </td>
                            <td v-if='is_reg || is_fix || is_htl' class="align-middle text-right">
                              <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                            </td>                       
                            <td v-if="mode != 'detail' && !purchase_order_logistik" class="align-middle">
                              <button v-if='is_fix || is_reg' class="btn btn-flat btn-danger" v-on:click.prevent="hapus_part(index)"><i class="fa fa-trash-o"></i></button>
                              <input v-if='is_urg' type="checkbox" v-model='part.checked' true-value='1' false-value>
                            </td>
                            <td v-if='is_htl'class="align-middle text-center" @click.prevent='open_eta_history(index)'>
                              <span v-if='part.eta == null'>-</span>
                              <span v-if='part.eta != null'>{{ moment(part.eta).format('DD/MM/YYYY') }}</span>
                            </td>
                            <td v-if='is_htl' class="align-middle text-center" @click.prevent='open_etd_history(index)'>
                              <span v-if='part.etd == null'>-</span>
                              <span v-if='part.etd != null'>{{ moment(part.etd).format('DD/MM/YYYY') }}</span>
                            </td>
                            <td v-if='is_htl' class="align-middle text-center" @click.prevent='open_eta_revisi_history(index)'>
                              <span v-if='part.eta_revisi != null'>{{ part.eta_revisi }}</span>  
                            </td>
                            <td class="align-middle text-center" v-if='purchase_order_status_reject'>
                                  <span v-if='part.alasan_part_revisi_md'> {{ part.alasan_part_revisi_md }}</span>
                                </td>
                                <td class="align-middle text-right" v-else-if='mode == "revisi_po"'>
                                  <select name="alasan_part_revisi_md" v-model='part.alasan_part_revisi_md' id="alasan_part_revisi_md" class='form-control'>
                                    <option value="">-</option>
                                    <option value="discontinue">Discontinue</option>
                                    <option value="part_set">Part Set</option>
                                    <option value="supersede">Supersede</option>
                                    <option value="lainnya">Lainnya</option>
                                  </select>
                                </td>
                            <!-- <td v-if='is_htl' class="align-middle text-center">
                              <span> - </span>  
                            </td> -->
                          </tr>
                          <tr v-if="parts.length < 1">
                            <td class="text-center" colspan="15">Tidak ada data</td>
                          </tr>
                          <tr v-if='(is_reg || is_fix || is_htl) && parts.length > 0'>
                            <td :colspan='colspan_grand_total' class='text-right'>Grand Total</td>
                            <td class='text-right'>
                              <vue-numeric read-only thousand.separator='.' v-model='total_qty_filtered'></vue-numeric>
                            </td>
                            <td></td>
                            <td class='text-right'>
                              <vue-numeric read-only currency='Rp' separator='.' v-model='total_amount_filtered'></vue-numeric>
                            </td>
                          </tr>
                        </tbody>                    
                      </table>
                    </div>
                  </div>
                </div>     
                <div v-if="mode != 'detail'" class="form-group">
                  <div class="col-sm-12">
                    <button v-if='is_fix || is_reg' type="button" class="pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#h3_md_parts_purchase_order_reguler_and_fix"><i class="fa fa-plus"></i></button>
                    <button v-if='is_urg && !purchase_order_logistik' type="button" class="pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#h3_md_referensi_po_hotline"><i class="fa fa-plus"></i></button>
                  </div>
                </div>                                                                                                                              
              </div><!-- /.box-body -->
              <?php $this->load->view('modal/h3_md_parts_purchase_order_reguler_and_fix'); ?>
              <script>
                function pilih_parts_purchase_reguler_and_fix(data) {
                  app.parts.push(data);
                }
              </script>
              <?php $this->load->view('modal/h3_md_referensi_po_hotline'); ?>
              <script>
                function pilih_referensi_po_hotline(data) {
                  app.parts_by_referensi_so_atau_po(data);
                }
              </script>
              <?php $this->load->view('modal/h3_md_open_eta_history'); ?>
              <?php $this->load->view('modal/h3_md_open_etd_history'); ?>
              <?php $this->load->view('modal/h3_md_open_eta_revisi_history'); ?>
              <div class="alert alert-warning alert-dismissable" v-if='terdapat_alasan_revisi.length < 1 && mode=="revisi_po"'>
                    <strong>Perhatian!</strong>
                    <p>Minimal 1 Part Harus Diisi Alasan Revisi!</p>
                    <button class="close" data-dismiss="alert">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                    </button>
                  </div>
              <div class="box-footer">
                <div class="col-sm-6">
                  <a v-if='mode == "detail" && purchase.status != "Approved" && purchase.status != "Canceled" && !purchase_order_logistik && purchase.status != "Reject & Revisi by MD"' :href="'h3/h3_md_purchase_order/edit?id_purchase_order=' + purchase.id_purchase_order" class="btn btn-sm btn-flat btn-warning">Edit</a>
                  <button :disabled='loading' v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                  <button :disabled='loading' v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                  <button :disabled='loading ||terdapat_alasan_revisi.length < 1' v-if='mode == "revisi_po" && !purchase_order_status_reject' class="btn btn-flat btn-sm btn-success" @click.prevent='<?= $form ?>'>Update Alasan Revisi</button>
                </div>
                <div class="col-sm-6 text-right">
                  <a v-if='mode == "detail" && (purchase.status == "Canceled"||purchase.status == "Approved") && is_htl' :href="'h3/h3_md_purchase_order/revisi_po?id_purchase_order=' + purchase.id_purchase_order" class="btn btn-sm btn-warning btn-flat">Reject PO Dealer</a>
                  <a onclick='return confirm("Apakah anda yakin ingin membatalkan PO ini?")' v-if='mode == "detail" && purchase.status != "Approved" && purchase.status != "Canceled" && purchase.status != "Reject & Revisi by MD"' :href="'h3/h3_md_purchase_order/cancel?id_purchase_order=' + purchase.id_purchase_order" class="btn btn-sm btn-flat btn-danger">Cancel</a>
                  <a onclick='return confirm("Apakah anda yakin ingin menyetujui PO ini?")' v-if='mode == "detail" && purchase.status == "Waiting Approval"' :href="'h3/h3_md_purchase_order/approve?id_purchase_order=' + purchase.id_purchase_order" class="btn btn-sm btn-flat btn-success">Approve</a>
                  <a onclick='return confirm("Apakah anda yakin ingin menutup PO ini?")' v-if='mode == "detail" && purchase.status == "Approved"' :href="'h3/h3_md_purchase_order/close?id_purchase_order=' + purchase.id_purchase_order" class="btn btn-sm btn-flat btn-danger">Close</a>
                </div>
              </div><!-- /.box-footer -->
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
            errors: {},
            index_part: 0,
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' OR $mode == 'edit' OR $this->input->get('generatePOLogistik') != null  or $mode == 'revisi_po'): ?>
            purchase: <?= json_encode($purchase) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            purchase: {
              jenis_po: '',
              keterangan: '',
              bulan: '',
              tahun: '',
              tanggal_po: '',
              referensi_po_hotline: '',
              produk: '',
              status: '',
              id_booking: '',
              id_dealer: '',
              id_customer: '',
              nama_customer: '',
              no_identitas: '',
              no_hp: '',
              no_hp_customer: '',
              kelurahan: '',
              kecamatan: '',
              kabupaten: '',
              provinsi: '',
              alamat: '',
              no_polisi: '',
              tipe_ahm: '',
              tipe_kendaraan: '',
              deskripsi_ahm: '',
              deskripsi_unit: '',
              deksripsi_warna: '',
              warna: '',
              no_mesin: '',
              no_rangka: '',
              tahun_produksi: '',
              id_data_pemesan: '',
              masukkan_pemesan: '',
              nama: '',
              no_hp: '',
              id_sa_form: '',
              id_work_order: '',
              no_buku_claim_c2: '',
              no_claim_c2: '',
              penomoran_ulang: '',
              form_warranty_claim_c2_c2: '',
              copy_faktur_ahm_claim_c1_c2: '',
              gesekan_nomor_framebody_claim_c1_c2: '',
              gesekan_nomor_crankcase_claim_c1_c2: '',
              copy_ktp_claim_c1_c2: '',
              copy_stnk_claim_c1_c2: '',
              copy_bpkb_faktur_ahm_non_claim: '',
              copy_stnk_non_claim: '',
              copy_ktp_non_claim: '',
              gesekan_nomor_framebody_non_claim: '',
              gesekan_nomor_crankcase_non_claim: '',
              potongan_no_rangka_mesin_non_claim: '',
              surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim: '',
              surat_laporan_forensik_kepolisian_non_claim: '',
              tipe_penomoran_ulang: '',
              vor: '',
              uang_muka: '',
              job_return_flag: '',
              ada_keterangan_tambahan: '',
              keterangan_tambahan: '',
            },
            parts: [],
            <?php endif; ?>
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
              todayBtn: 'linked'
            },
            bulan_date_config: {
              autoclose: true,
              format: 'mm',
              viewMode: 'months',
              minViewMode: 'months'
            },
            year_date_config: {
              autoclose: true,
              format: 'yyyy',
              viewMode: 'years',
              minViewMode: 'years'
            },
            filter_kode_part: '',
            filter_kelompok_part: [],
          },
          methods: {
            <?= $form ?>: function(){
              this.loading = true;
              this.errors = {};

              post = _.pick(this.purchase, ['id', 'id_purchase_order', 'jenis_po', 'keterangan', 'bulan', 'tahun', 'tanggal_po', 'referensi_po_hotline', 'total_amount', 'id_po_logistik', 'produk']);
              post.total_amount = this.total_amount;
              post.parts = _.chain(this.filter_parts_by_kelompok_part)
              .filter(function(part){
                return part.checked == 1;
              })
              .value();

              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  toastr.error(data.message);
                  app.errors = data.errors;
                }else{
                  toastr.error(data.message);
                }
                app.loading = false;
              });
            },
            hpp: function(part) {
              return (10 / 100) * parseFloat(part.harga);
            },
            sub_total: function(part) {
              return (part.qty_order * part.harga);
            },
            hapus_part: function(index) {
              this.parts.splice(index, 1);
              h3_md_referensi_po_hotline_datatable.draw();
            },
            generate_parts: function(){
              this.parts = [];

              toastr.options = {
                preventDuplicates: true,
                preventOpenDuplicates: true
              };

              if(this.purchase.bulan == null || this.purchase.bulan == ''){
                toastr.warning('Inputan bulan belum terisi.');
                return;
              }

              if(this.purchase.tahun == null || this.purchase.tahun == ''){
                toastr.warning('Inputan tahun belum terisi.');
                return;
              }

              params = {};
              params.jenis_po = this.purchase.jenis_po;
              params.bulan = this.purchase.bulan;
              params.tahun = this.purchase.tahun;
              params.tanggal_order = this.purchase.tanggal_order;
              params.produk = this.purchase.produk;

              this.loading = true;
              axios.get('h3/<?= $isi ?>/generate_parts', {
                params: params
              })
              .then(function(res){
                app.parts = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              });
            },
            parts_by_referensi_so_atau_po: function(data){
              params = _.pick(data, ['referensi']);
              params.jenis_po = this.purchase.jenis_po;

              this.loading = true;
              axios.get('h3/h3_md_purchase_order/parts_by_referensi_so_atau_po', {
                params: params
              })
              .then(function(res){
                for(each of res.data){
                  app.parts.push(each);
                }
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
                h3_md_referensi_po_hotline_datatable.draw();
              });
            },
            get_request_document: function(){
              params = _.pick(this.purchase, ['referensi_po_hotline']);

              this.loading = true;
              axios.get('h3/h3_md_purchase_order/get_request_document', {
                params: params
              })
              .then(function(res){
                request_document = res.data.request_document;
                if(request_document != null){
                  app.purchase = _.assignIn(app.purchase, request_document);
                }
                parts = res.data.parts;
                if(parts.length > 0){
                  app.parts = parts;
                  app.update_eta_parts();
                }
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              });
            },
            reset_referensi_po_hotline: function(){
              this.purchase.referensi_po_hotline = '';
            },
            reset_request_document: function(){
              this.purchase.status = '';
              this.purchase.id_dealer = '';
              this.purchase.id_booking = '';
              this.purchase.id_customer = '';
              this.purchase.nama_customer = '';
              this.purchase.no_identitas = '';
              this.purchase.no_hp = '';
              this.purchase.no_hp_customer   = '';
              this.purchase.kelurahan = '';
              this.purchase.kecamatan = '';
              this.purchase.kabupaten = '';
              this.purchase.provinsi = '';
              this.purchase.alamat = '';
              this.purchase.no_polisi = '';
              this.purchase.tipe_ahm = '';
              this.purchase.tipe_kendaraan = '';
              this.purchase.deskripsi_ahm = '';
              this.purchase.deskripsi_unit = '';
              this.purchase.deksripsi_warna = '';
              this.purchase.warna = '';
              this.purchase.no_mesin = '';
              this.purchase.no_rangka = '';
              this.purchase.tahun_produksi = '';
              this.purchase.id_data_pemesan = '';
              this.purchase.masukkan_pemesan = '';
              this.purchase.nama = '';
              this.purchase.no_hp = '';
              this.purchase.id_sa_form = '';
              this.purchase.id_work_order = '';
              this.purchase.no_buku_claim_c2 = '';
              this.purchase.no_claim_c2 = '';
              this.purchase.penomoran_ulang = '';
              this.purchase.form_warranty_claim_c2_c2 = '';
              this.purchase.copy_faktur_ahm_claim_c1_c2 = '';
              this.purchase.gesekan_nomor_framebody_claim_c1_c2 = '';
              this.purchase.gesekan_nomor_crankcase_claim_c1_c2 = '';
              this.purchase.copy_ktp_claim_c1_c2 = '';
              this.purchase.copy_stnk_claim_c1_c2 = '';
              this.purchase.copy_bpkb_faktur_ahm_non_claim = '';
              this.purchase.copy_stnk_non_claim = '';
              this.purchase.copy_ktp_non_claim = '';
              this.purchase.gesekan_nomor_framebody_non_claim = '';
              this.purchase.gesekan_nomor_crankcase_non_claim = '';
              this.purchase.potongan_no_rangka_mesin_non_claim = '';
              this.purchase.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim = '';
              this.purchase.surat_laporan_forensik_kepolisian_non_claim = '';
              this.purchase.tipe_penomoran_ulang = '';
              this.purchase.vor = '';
              this.purchase.uang_muka = '';
              this.purchase.job_return_flag = '';
              this.purchase.ada_keterangan_tambahan = '';
              this.purchase.keterangan_tambahan = '';

              this.parts = [];
            },
            update_eta_parts: function() {
              this.reset_eta();

              post = {};
              post.id_dealer = this.purchase.id_dealer;
              post.claim = this.purchase.penomoran_ulang;
              if (this.purchase.tipe_penomoran_ulang == 'claim_c1_c2') {
                post.tipe_claim = 'renumbering_claim';
              } else if (this.purchase.tipe_penomoran_ulang == 'non_claim') {
                post.tipe_claim = 'renumbering_non_claim';
              }
              post.parts = _.chain(this.parts)
              .map(function(part){
                return part.id_part;
              })
              .value();

              axios.post('h3/h3_md_purchase_order/update_eta_parts', Qs.stringify(post))
              .then(function(res) {
                if(res.data.length > 0){
                  for (row of res.data) {
                    index_part = _.findIndex(app.parts, function(part) { return part.id_part == row.id_part; });
                    if(index_part != -1){
                      app.parts[index_part].etd = row.eta_tercepat;
                      app.parts[index_part].eta = row.eta_terlama;
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
                this.parts[index].etd = null;                
                this.parts[index].eta = null;                
              }
            },
            open_eta_history: function(index){
              this.index_part = index;
              h3_md_open_eta_history_datatable.draw();
              $('#h3_md_open_eta_history').modal('show');
            },
            open_etd_history: function(index){
              this.index_part = index;
              h3_md_open_etd_history_datatable.draw();
              $('#h3_md_open_etd_history').modal('show');
            },
            open_eta_revisi_history: function(index){
              this.index_part = index;
              h3_md_open_eta_revisi_history_datatable.draw();
              $('#h3_md_open_eta_revisi_history').modal('show');
            },
            update_qty_bo_dealer: function(){
              if(!this.is_reg && !this.is_fix) return;

              post = {};
              post.bulan = this.purchase.bulan;
              post.tahun = this.purchase.tahun;
              post.parts = _.chain(this.parts)
              .map(function(part){
                return part.id_part;
              })
              .value();

              this.loading = true;
              axios.post('h3/h3_md_purchase_order/update_qty_bo_dealer', Qs.stringify(post))
              .then(function(res){
                if(res.data.length > 0){
                  for (part of res.data) {
                    index = _.findIndex(app.parts, function(row){
                      return row.id_part == part.id_part;
                    });
                    app.parts[index].qty_bo_dealer = part.qty_bo_dealer;
                  }
                }
              })
              .catch(function(err){
                toast.error('ERROR: Terjadi kesalahan saat update kuantitas BO Dealer.');
              })
              .then(function(){
                app.loading = false;
              })
            },
            update_qty_suggest: function(){
              if(!this.is_reg && !this.is_fix) return;

              post = {};
              post.jenis_po = this.purchase.jenis_po;
              post.tanggal_po = this.purchase.tanggal_po;
              post.bulan = this.purchase.bulan;
              post.tahun = this.purchase.tahun;
              post.parts = _.chain(this.parts)
              .map(function(part){
                return part.id_part;
              })
              .value();

              this.loading = true;
              axios.post('h3/h3_md_purchase_order/update_qty_suggest', Qs.stringify(post))
              .then(function(res){
                if(res.data.length > 0){
                  for (part of res.data) {
                    index = _.findIndex(app.parts, function(row){
                      return row.id_part == part.id_part;
                    });
                    app.parts[index].qty_suggest = part.qty_suggest;
                    if(app.purchase.jenis_po == 'FIX'){
                      app.parts[index].qty_order = part.qty_order;
                    }
                  }
                }
              })
              .catch(function(err){
                toast.error('ERROR: Terjadi kesalahan saat update kuantitas suggest.');
              })
              .then(function(){
                app.loading = false;
              })
            },
            get_min_order: function(part){
              if(part.qty_suggest == null){
                return 0;
              }
              
              index = _.findIndex(this.parts, function(row){
                return row.id_part == part.id_part;
              });

              // if(parseInt(part.qty_suggest) > parseInt(part.qty_min_order)){
              //   this.parts[index].qty_order = part.qty_suggest;
              //   return parseInt(part.qty_suggest);
              // }

              if(parseInt(part.qty_order) < parseInt(part.qty_min_order)){
                this.parts[index].qty_order = part.qty_min_order;
              }
              return parseInt(part.qty_min_order);
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
            tanggal_po_datepicker_change: function(date){
              this.purchase.tanggal_po = date.format('yyyy-mm-dd');
            },
            bulan_datepicker_change: function(date){
              this.purchase.bulan = date.format('yyyy-mm-dd');
            },
            year_datepicker_change: function(date){
              this.purchase.tahun = date.format('yyyy-mm-dd');
            },
          },
          watch: {
            parts: {
              deep: true,
              handler: function(){
                if(this.is_reg || this.is_fix){
                  // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
                  drawing_po_part();
                }
                // h3_md_filter_kelompok_part_purchase_order_datatable.draw();
              }
            },
            'purchase.jenis_po': function(value){
              this.parts = [];
              h3_md_referensi_po_hotline_datatable.draw();
              if(this.is_reg || this.is_fix){
                // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
              }
            },
            'purchase.referensi_po_hotline': function(n, o){
              if(n == ''){
                this.reset_request_document();
              }else{
                this.get_request_document();
              }
            },
            'purchase.produk': function(n, o){
              if(this.is_reg || this.is_fix){
                // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
                drawing_po_part();
                this.parts = [];
              }
              h3_md_purchase_order_kelompok_part_filter_datatable.draw();
            },
            'purchase.bulan': function(){
              // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
              drawing_po_part();
              this.update_qty_bo_dealer();
              this.update_qty_suggest();

              if(this.parts.length > 0){
                // this.generate_parts();
              }
            },
            'purchase.tahun': function(){
              // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
              drawing_po_part();
              this.update_qty_bo_dealer();
              this.update_qty_suggest();

              if(this.parts.length > 0){
                // this.generate_parts();
              }
            }
          },
          computed: {
            total_amount: function(){
              sub_total_fn = this.sub_total;
              return _.chain(this.parts)
              .sumBy(function(part){
                return sub_total_fn(part);
              })
              .value();
            },
            total_amount_filtered: function(){
              sub_total_fn = this.sub_total;
              return _.chain(this.filtered_parts)
              .sumBy(function(part){
                return sub_total_fn(part);
              })
              .value();
            },
            total_qty_filtered: function(){
              sub_total_fn = this.sub_total;
              return _.chain(this.filtered_parts)
              .sumBy(function(part){
                return parseInt(part.qty_order);
              })
              .value();
            },
            is_fix: function(){
              return this.purchase.jenis_po == 'FIX';
            },
            is_reg: function(){
              return this.purchase.jenis_po == 'REG';
            },
            is_urg: function(){
              return this.purchase.jenis_po == 'URG';
            },
            is_htl: function(){
              return this.purchase.jenis_po == 'HTL';
            },
            purchase_order_status_reject: function() {
                return this.purchase.status == 'Reject & Revisi by MD';
            },
            terdapat_alasan_revisi: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    return part.alasan_part_revisi_md;
                  })
                  .value();
              },
            empty_referensi_po_hotline: function(){
              return this.purchase.referensi_po_hotline == '' || this.purchase.referensi_po_hotline == null;
            },
            purchase_order_logistik: function(){
              return this.purchase.id_po_logistik != '' && this.purchase.id_po_logistik != null;
            },
            referensi_terpakai: function(){
              return _.chain(this.parts)
              .uniqBy(function(p){
                return p.referensi;
              })
              .map(function(p){
                return p.referensi;
              }).value();
            },
            colspan_grand_total: function(){
              if(this.is_fix || this.is_reg){
                return 13;
              }
              if(this.is_htl){
                return 9;
              }
              return 0;
            },
            filter_parts_by_kelompok_part: function(){
              filter_kelompok_part = this.filter_kelompok_part;
              return _.chain(this.parts)
              .filter(function(part){
                if(filter_kelompok_part.length > 0){
                  index = _.findIndex(filter_kelompok_part, function(kelompok_part){
                    return kelompok_part.toLowerCase() == part.kelompok_part.toLowerCase();
                  });
                  return index != -1; 
                }else{
                  return true;
                }
              })
              .value();
            },
            filtered_parts: function(){
              filter_kode_part = this.filter_kode_part;
              return _.chain(this.filter_parts_by_kelompok_part)
              .filter(function(part){
                return part.id_part.toLowerCase().includes(filter_kode_part.toLowerCase())
              })
              .value();
            }
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
          <a href="h3/<?= $isi ?>/upload_po_reg_fix">
                <button class="btn bg-green  btn-flat margin"><i class="fa fa-upload"></i> Upload PO Reg/FIX</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
            <div class="row">
              <div class="col-sm-3">
                <div class="row">
                  <div class="form-group">
                    <label for="" class="control-label">Periode PO</label>
                    <input id='periode_purchase_filter' type="text" class="form-control" readonly>
                    <input id='periode_purchase_filter_start' type="hidden" disabled>
                    <input id='periode_purchase_filter_end' type="hidden" disabled>
                  </div>
                </div>
                <script>
                  $('#periode_purchase_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                    $('#periode_purchase_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#periode_purchase_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                    purchase_order_fix_reguler.draw();
                    purchase_order_urgent.draw();
                    purchase_order_hotline.draw();
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_purchase_filter_start').val('');
                    $('#periode_purchase_filter_end').val('');
                    purchase_order_fix_reguler.draw();
                    purchase_order_urgent.draw();
                    purchase_order_hotline.draw();
                  });
                </script>
              </div>
            </div>
        </div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#fix_reguler" aria-controls="fix_reguler" role="tab" data-toggle="tab">Fix / Reguler</a></li>
          <li role="presentation"><a href="#urgent" aria-controls="urgent" role="tab" data-toggle="tab">Urgent</a></li>
          <li role="presentation"><a href="#hotline" aria-controls="hotline" role="tab" data-toggle="tab">Hotline</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="fix_reguler">
            <div class="container-fluid no-padding" style='margin-top: 20px;'>
              <table id="purchase_order_fix_reguler" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No.</th>              
                    <th>Tanggal PO</th>              
                    <th>Produk</th>              
                    <th>Bulan</th>              
                    <th>Jenis PO</th>              
                    <th>No PO</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>    
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  purchase_order_fix_reguler = $('#purchase_order_fix_reguler').DataTable({
                      processing: true,
                      serverSide: true,
                      order: [],
                      ajax: {
                        url: "<?= base_url('api/md/h3/purchase_order_fix_reguler') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                          d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          d.periode_purchase_filter_start = $('#periode_purchase_filter_start').val();
                          d.periode_purchase_filter_end = $('#periode_purchase_filter_end').val();
                        }
                      },
                      createdRow: function (row, data, index) {
                        $('td', row).addClass('align-middle');
                      },
                      columns: [
                          { data: 'index', orderable: false, width: '3%' },
                          { data: 'tanggal_po' },
                          { data: 'produk' },
                          { data: 'bulan' },
                          { data: 'jenis_po' },
                          { data: 'id_purchase_order' },
                          { 
                            data: 'total_amount',
                            render: function(data){
                              return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                            },
                            className: 'text-right'
                          },
                          { data: 'status' },
                          { data: 'action', width: '200px', orderable: false, },
                      ],
                  });
                });
              </script>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="urgent">
            <div class="container-fluid no-padding" style='margin-top: 20px;'>
              <table id="purchase_order_urgent" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No.</th>              
                    <th>Tanggal PO</th>              
                    <th>No PO</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>    
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  purchase_order_urgent = $('#purchase_order_urgent').DataTable({
                      processing: true,
                      serverSide: true,
                      order: [],
                      ajax: {
                        url: "<?= base_url('api/md/h3/purchase_order_urgent') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                          d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          d.periode_purchase_filter_start = $('#periode_purchase_filter_start').val();
                          d.periode_purchase_filter_end = $('#periode_purchase_filter_end').val();
                        }
                      },
                      createdRow: function (row, data, index) {
                        $('td', row).addClass('align-middle');
                      },
                      columns: [
                          { data: 'index', orderable: false, width: '3%' },
                          { data: 'tanggal_po' },
                          { data: 'id_purchase_order' },
                          { data: 'view_customer', width: '3%', orderable: false, className: 'text-center' },
                          { 
                            data: 'total_amount',
                            render: function(data){
                              return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                            }
                          },
                          { data: 'status' },
                          { data: 'action', width: '200px', orderable: false, },
                      ],
                  });
                });
                
              </script>
              <?php $this->load->view('modal/h3_md_view_customer_purchase_order'); ?>
              <script>
                  function view_customer(id_purchase_order){
                    $('#id_purchase_order_for_view_customer').val(id_purchase_order);
                    $('#h3_md_view_customer_purchase_order').modal('show');
                    h3_md_view_customer_purchase_order_datatable.draw();
                  }
                </script>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="hotline">
            <div class="container-fluid no-padding" style='margin-top: 20px;'>
              <table id="purchase_order_hotline" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No.</th>              
                    <th>Tanggal PO</th>              
                    <th>No PO</th>
                    <th>Kode Customer</th>
                    <th>Nama Customer</th>
                    <th>Alamat</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>    
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  purchase_order_hotline = $('#purchase_order_hotline').DataTable({
                      processing: true,
                      serverSide: true,
                      order: [],
                      ajax: {
                        url: "<?= base_url('api/md/h3/purchase_order_hotline') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                          d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          d.periode_purchase_filter_start = $('#periode_purchase_filter_start').val();
                          d.periode_purchase_filter_end = $('#periode_purchase_filter_end').val();
                        }
                      },
                      createdRow: function (row, data, index) {
                        $('td', row).addClass('align-middle');
                      },
                      columns: [
                          { data: 'index', orderable: false, width: '3%' },
                          { data: 'tanggal_po' },
                          { data: 'id_purchase_order' },
                          { data: 'kode_dealer_md' },
                          { data: 'nama_dealer' },
                          { data: 'alamat' },
                          { data: 'nama_customer' },
                          { data: 'status' },
                          { data: 'action', width: '200px', orderable: false, },
                      ],
                  });
                });
              </script>
            </div>
          </div>
        </div>
        
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
    <?php if($mode == "upload_po_reg_fix") : ?>
        <div id="app" class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/h3_md_purchase_order">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
          </div>
          <div v-if="loading" class="overlay">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="box-body">
          <?php $this->load->view('template/session_message.php'); ?>
            <div class="row">
                <div class="col-md-12">
            <div v-if='upload_errors.length > 0' class="container-fluid">
              <div class="row">
                <div class="col-sm-12">
                  <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i> Peringatan, terdapat masalah dalam mengimport Purchase Order.</h4>
                    <ul>
                      <li v-for='error of upload_errors'>{{ error }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
                  <form class="form-horizontal">
                    <div class="box-body">
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Import PO Reg/Fix Template</label>
                        <div class="col-sm-4">
                          <input type="file" @change='on_file_change()' ref='file' class="form-control">
                        </div>
                        <div class="col-sm-3 no-padding">
                          <a href="h3/h3_md_purchase_order/download_template_import_po_reg_fix_ahm" class="btn btn-flat btn-info">Download Template</a>
                        </div>
                      </div>
                    </div>
                    <div class="box-footer">
                      <div class="col-sm-6 no-padding">
                        <button class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <script>
          app = new Vue({
        el: '#app',
        data: {
          loading: false,
          errors: {},
          upload_errors: [],
          file: null
        },
        methods: {
          upload: function(){
            post = new FormData();
            post.append('file', this.file);

            this.errors = {};
            this.upload_errors = [];
            this.loading = true;
            axios.post('h3/h3_md_purchase_order/store_upload_po_reg_fix', post, {
              headers: {
                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
              }
            })
            .then(function(res){
              data = res.data;
              if(data.payload != null){
                window.location = 'h3/h3_md_purchase_order/detail?id_purchase_order=' + data.payload.id_purchase_order;
              }
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                app.errors = data.errors;
                toastr.error(data.message);
              }else if(data.error_type == 'upload_error'){
                
                app.upload_errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(err);
              }
            })
            .then(function(){ app.loading = false; });
          },
          on_file_change: function(){
            this.file = this.$refs.file.files[0];
          },
          error_exist: function(key){
            return _.get(this.upload_errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.upload_errors, key)
          }
        }
      });
      </script>
    <?php endif; ?>
  </section>
</div>
