<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
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
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'detail';
        $disabled = 'disabled';
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
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div v-if='selisih_cashback && selisih_qty_retur' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Terdapat selisih perbedaan cashback dikarenakan retur penjualan yang sebagian. Jika ingin melanjutkan retur, diharapkan untuk melakukan retur secara penuh.
              </div>
              <div v-if='part_tanpa_lokasi.length > 0' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Terdapat retur part tanpa lokasi rak, harap lengkapi data!.
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Nama Customer</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='retur_penjualan.nama_dealer'>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' v-if='customer_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_retur_penjualan'><i class="fa fa-search"></i></button>
                        <button v-if='!customer_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_customer'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>  
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_retur_penjualan') ?>
                  <script>
                    function pilih_dealer_retur_penjualan(data) {
                        app.retur_penjualan.id_dealer = data.id_dealer;
                        app.retur_penjualan.nama_dealer = data.nama_dealer;
                        app.retur_penjualan.alamat = data.alamat;
                        app.retur_penjualan.nama_salesman = data.nama_salesman;
                    }
                  </script>
                  <label for="" class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='retur_penjualan.alamat'>  
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='retur_penjualan.nama_salesman'>  
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">No Faktur</label>
                  <div v-bind:class="{ 'has-error': error_exist('no_faktur') }" class="col-sm-4">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='retur_penjualan.no_faktur'>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' v-if='faktur_empty || mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_faktur_retur_penjualan'><i class="fa fa-search"></i></button>
                        <button v-if='!faktur_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='reset_faktur'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('no_faktur')" class="form-text text-danger">{{ get_error('no_faktur') }}</small>  
                  </div>
                  <?php $this->load->view('modal/h3_md_faktur_retur_penjualan') ?>
                  <script>
                    function pilih_faktur_retur_penjualan(data) {
                      app.retur_penjualan.no_faktur = data.no_faktur;
                      app.retur_penjualan.tgl_faktur = data.tgl_faktur;
                      app.retur_penjualan.id_packing_sheet = data.id_packing_sheet;
                      app.retur_penjualan.tgl_packing_sheet = data.tgl_packing_sheet;
                      app.retur_penjualan.id_dealer = data.id_dealer;
                      app.retur_penjualan.nama_dealer = data.nama_dealer;
                      app.retur_penjualan.alamat = data.alamat;
                      app.retur_penjualan.diskon_cashback = data.diskon_cashback;
                      app.retur_penjualan.diskon_insentif = data.diskon_insentif;
                      app.retur_penjualan.nama_salesman = data.nama_salesman;
                      app.retur_penjualan.diskon_cashback_otomatis = data.diskon_cashback_otomatis;
                      app.retur_penjualan.total_nilai_faktur = data.total_nilai_faktur;
                    }
                  </script>
                  <label for="" class="col-sm-2 control-label">No. Packing Sheet</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='retur_penjualan.id_packing_sheet'>  
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Tgl Faktur</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='retur_penjualan.tgl_faktur'>  
                  </div>
                  <label for="" class="col-sm-2 control-label">Tgl Packing Sheet</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='retur_penjualan.tgl_packing_sheet'>  
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Tgl Terima Retur</label>
                  <div v-bind:class="{ 'has-error': error_exist('tanggal_terima_retur') }" class="col-sm-4">
                    <date-picker :disabled='mode == "detail"' @update-date='tanggal_terima_retur_datepicker_change' class='form-control' readonly :config='config' v-model='retur_penjualan.tanggal_terima_retur'></date-picker>
                    <small v-if="error_exist('tanggal_terima_retur')" class="form-text text-danger">{{ get_error('tanggal_terima_retur') }}</small>  
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th class='align-top' width='3%'>No.</th>              
                          <th class='align-top'>Kode Part</th>              
                          <th class='align-top'>Nama Part</th>              
                          <th class='align-top' width="8%">Qty Faktur</th>
                          <th class='align-top' width="8%">Qty Retur</th>
                          <th class='align-top' width="8%">Lokasi Rak</th>
                          <th class='align-top' width='15%'>Alasan Retur</th>
                          <th class='align-top' v-if="mode != 'detail'" width="3%"></th>
                        </tr>
                      <tbody>            
                        <tr v-for="(part, index) in parts">
                          <td class="align-middle">{{ index + 1 }}</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric :read-only='true' separator="." :empty-value="1" v-model="part.qty_faktur"/>
                          </td>            
                          <td class="align-middle">
                            <vue-numeric class='form-control' :read-only='mode == "detail"' separator="." :empty-value="1" v-model="part.qty_retur" :max='part.qty_faktur'  v-on:keyup.native='qty_retur_change'/>
                          </td>            
                          <td class="align-middle">
                            <input :disabled='mode == "detail"' type="text" class="form-control" readonly @click.prevent='open_lokasi(index)' v-model='part.kode_lokasi_rak'>
                          </td> 
                          <td class="align-middle">
                            <input v-if='mode != "detail"' type="text" class="form-control" v-model='part.alasan'>
                            <span v-if='mode == "detail"'>{{ part.alasan }}</span>
                          </td>            
                          <td class="align-middle" v-if='mode != "detail"'>
                            <button class="btn btn-flat btn-danger" @click.prevent='hapus_part(index)'><i class="fa fa-trash-o"></i></button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="9">Belum ada part</td>
                        </tr>
                        <tr>
                          <td colspan='5' class='text-right'>Total Nilai Faktur</td>
                          <td class='text-right'>
                            <vue-numeric :read-only='true' separator="." :empty-value="1" v-model="retur_penjualan.total_nilai_faktur"/>
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan='5' class='text-right'>Total Nilai Retur</td>
                          <td class='text-right'>
                            <vue-numeric :read-only='true' separator="." :empty-value="1" v-model="total_nilai_retur"/>
                          </td>
                          <td></td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                  <div class="col-sm-12 text-right">  
                    <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary" type='button' data-toggle='modal' data-target='#h3_md_faktur_parts_retur_penjualan'><i class="fa fa-plus"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_faktur_parts_retur_penjualan') ?>
                  <script>
                    function pilih_faktur_parts_retur_penjualan(data) {
                      app.parts.push(data);
                      h3_md_faktur_parts_retur_penjualan_datatable.draw();
                      app.check_cashback_retur();
                    }
                  </script>
                  <?php $this->load->view('modal/h3_md_lokasi_rak_retur_penjualan'); ?>
                  <script>
                    function pilih_lokasi_rak_retur_penjualan (data){
                      app.parts[app.index_part].id_lokasi_rak = data.id;
                      app.parts[app.index_part].kode_lokasi_rak = data.kode_lokasi_rak;
                    }
                  </script>
                  <?php $this->load->view('modal/h3_md_view_stock_lokasi_retur_penjualan') ?>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label class="control-label col-sm-2">Alasan</label>
                      <div v-bind:class="{ 'has-error': error_exist('alasan') }" class="col-sm-4">
                        <input :disabled='mode == "detail"' type="text" class="form-control" v-model='retur_penjualan.alasan'>
                        <small v-if="error_exist('alasan')" class="form-text text-danger">{{ get_error('alasan') }}</small>
                      </div>
                    </div>
                  </div>
                </div>                                                                                                                                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4 no-padding">
                  <button :disabled='(selisih_cashback && selisih_qty_retur) || part_tanpa_lokasi.length > 0' v-if="mode == 'insert'" class="btn btn-sm btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                  <button :disabled='(selisih_cashback && selisih_qty_retur) || part_tanpa_lokasi.length > 0' v-if="mode == 'edit'" class="btn btn-sm btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && retur_penjualan.status != "Processed"' :href="'h3/h3_md_retur_penjualan/edit?id_retur_penjualan=' + retur_penjualan.id_retur_penjualan" class="btn btn-sm btn-flat btn-warning">Edit</a>
                </div>
                <div class="col-sm-4 no-padding text-center">
                  <button v-if="mode == 'detail' && retur_penjualan.status == 'Open'" :disabled='part_tanpa_lokasi.length > 0' class="btn btn-sm btn-flat btn-success" @click.prevent="proses">Proses</button>
                  <button v-if="mode == 'detail' && retur_penjualan.status == 'Open'" class="btn btn-sm btn-flat btn-danger" data-toggle='modal' data-target='#cancel_modal' type='button'>Cancel</button>
                  <div id="cancel_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title text-left" id="myModalLabel">Alasan Cancel</h4>
                              </div>
                              <div class="modal-body">
                              <div class="form-group">
                                  <div class="col-sm-12">
                                  <textarea class="form-control" id="alasan_cancel"></textarea>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="col-sm-12">
                                  <button @click.prevent='cancel' class="btn btn-flat btn-sm btn-primary" data-dismiss="modal">Submit</button>
                                  </div>
                              </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="col-sm-4 no-padding text-right">
                  <a v-if='mode == "detail"' :href="'h3/h3_md_retur_penjualan/cetak_memo_pengajuan_retur?id_retur_penjualan=' + retur_penjualan.id_retur_penjualan" class="btn btn-sm btn-flat btn-info">Cetak Memo Pengajuan Retur</a>
                  <a v-if='mode == "detail"' :href="'h3/h3_md_retur_penjualan/cetak_retur_penjualan?id_retur_penjualan=' + retur_penjualan.id_retur_penjualan" class="btn btn-sm btn-flat btn-info">Cetak Retur Penjualan</a>
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
            index_part: 0,
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            config: {
              autoclose: true,
              format: 'dd/mm/yyyy',
            },
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            retur_penjualan: <?= json_encode($retur_penjualan) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            retur_penjualan: {
              id_dealer: '',
              nama_dealer: '',
              alamat: '',
              nama_salesman: '',
              no_faktur: '',
              tgl_faktur: '',
              tanggal_terima_retur: '',
              id_packing_sheet: '',
              tgl_packing_sheet: '',
              tgl_terima_retur: '',
              diskon_insentif: 0,
              diskon_cashback: 0,
              total_nilai_faktur: 0,
            },
            parts: [],
            <?php endif; ?>
            cashback_retur: 0,
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.retur_penjualan, [
                'id_dealer', 'no_faktur', 'alasan', 'tanggal_terima_retur'
              ]);

              if (this.mode == 'edit') {
                post.id_retur_penjualan = this.retur_penjualan.id_retur_penjualan;
              }

              post.total_nilai_retur = this.total_nilai_retur;

              post.parts = _.chain(this.parts)
              .map(function(part){
                return _.pick(part, [
                  'id_part', 'qty_retur', 'alasan', 'id_lokasi_rak'
                ]);
              }).value();

              this.loading = true;
              axios.post('h3/h3_md_retur_penjualan/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;

                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  if(data.message != null) {
                    toastr.error(data.message);
                  }else{
                    toastr.error(err);
                  }
                }
                app.loading = false;
              });
            },
            proses: function(){
              this.loading = true;
              axios.get('h3/h3_md_retur_penjualan/proses', {
                params: {
                  id_retur_penjualan: this.retur_penjualan.id_retur_penjualan
                }
              })
              .then(function(res){
                window.location = 'h3/h3_md_retur_penjualan/detail?id_retur_penjualan=' + res.data.id_retur_penjualan;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            cancel: function(){
              this.loading = true;
              axios.get('h3/h3_md_retur_penjualan/cancel', {
                params: {
                  id_retur_penjualan: this.retur_penjualan.id_retur_penjualan,
                  alasan_cancel: $('#alasan_cancel').val()
                }
              })
              .then(function(res){
                window.location = 'h3/h3_md_retur_penjualan/detail?id_retur_penjualan=' + res.data.id_retur_penjualan;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            open_lokasi: function (index) {
              this.index_part = index;
              h3_md_lokasi_rak_retur_penjualan_datatable.draw();
              $('#h3_md_lokasi_rak_retur_penjualan').modal('show');
            },
            hapus_part: function(index){
              this.parts.splice(index, 1);
              h3_md_faktur_parts_retur_penjualan_datatable.draw();
              this.check_cashback_retur();
            },
            reset_customer: function(){
              this.retur_penjualan.id_dealer = '';
              this.retur_penjualan.nama_dealer = '';
              this.retur_penjualan.alamat = '';
              this.reset_faktur();
            },
            reset_faktur: function(){
              this.retur_penjualan.no_faktur = '';
              this.retur_penjualan.tgl_faktur = '';
              this.retur_penjualan.id_packing_sheet = '';
              this.retur_penjualan.tgl_packing_sheet = '';
              this.retur_penjualan.nama_salesman = '';
              this.retur_penjualan.diskon_insentif = 0;
              this.retur_penjualan.diskon_cashback = 0;
              this.retur_penjualan.diskon_cashback_otomatis = 0;
              this.retur_penjualan.total_nilai_faktur = 0;
              this.parts = [];
            },
            harga_setelah_diskon: function(part){
              return part.harga_dealer_user -
              this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, part.harga_dealer_user) - 
              this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_dealer_user);
            },
            calculate_discount: function(discount, tipe_diskon, price) {
              if(tipe_diskon == 'Persen'){
                if(discount == 0) return 0; 

                return discount = (discount/100) * price;
              }else if(tipe_diskon == 'Rupiah'){
                return discount;
              }
              return 0;
            },
            amount_faktur: function(part) {
              return this.harga_setelah_diskon(part) * part.qty_faktur
            },
            amount_retur: function(part) {
              return this.harga_setelah_diskon(part) * part.qty_retur
            },
            total_diskon: function(){
              return parseFloat(this.retur_penjualan.diskon_insentif) + (parseFloat(this.retur_penjualan.diskon_cashback) + parseFloat(this.retur_penjualan.diskon_cashback_otomatis));
            },
            qty_retur_change: _.debounce(function(){
              this.check_cashback_retur();
            }, 300),
            check_cashback_retur: function(){
              if(this.parts.length < 1) return;
              
              post = {};
              post.no_faktur = this.retur_penjualan.no_faktur;
              post.parts = _.chain(this.parts)
              .map(function(part){
                return {
                  id_part: part.id_part,
                  qty_order: parseInt(part.qty_retur)
                };
              })
              .value();

              this.loading = true;
              axios.post('h3/h3_md_retur_penjualan/check_cashback_retur', Qs.stringify(post))
              .then(function(res){
                app.cashback_retur = _.chain(res.data)
                .filter(function(data){
                  return data.reward_cashback == 'Langsung';
                })
                .sumBy(function(data){
                  return data.cashback;
                })
                .value();
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            tanggal_terima_retur_datepicker_change: function(date){
              this.retur_penjualan.tanggal_terima_retur = date.format('yyyy-mm-dd');
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            total_nilai_retur: function(){
              amount_retur_fn = this.amount_retur;
              total_diskon_fn = this.total_diskon;
              return _.sumBy(this.parts, function(part){
                return amount_retur_fn(part) - total_diskon_fn();
              });
            },
            customer_empty: function(){
              return this.retur_penjualan.id_dealer == '' || this.retur_penjualan.id_dealer == null;
            },
            faktur_empty: function(){
              return this.retur_penjualan.no_faktur == '' || this.retur_penjualan.no_faktur == null;
            },
            selisih_qty_retur: function(){
              for (part of this.parts) {
                if(part.qty_faktur != part.qty_retur){
                  return true;
                }
              }
              return false;
            },
            selisih_cashback: function(){
              if(this.parts.length > 0){
                return this.retur_penjualan.diskon_cashback_otomatis != this.cashback_retur;
              }
              return false;
            },
            part_tanpa_lokasi: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return part.id_lokasi_rak == null || part.id_lokasi_rak == '';
              })
              .value();
            }
          },
          watch: {
            'retur_penjualan.id_dealer': function(){
              h3_md_faktur_retur_penjualan_datatable.draw();
            },
            'retur_penjualan.no_faktur': function(){
              h3_md_faktur_parts_retur_penjualan_datatable.draw();
            },
          },
          mounted: function(){
            this.check_cashback_retur();
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
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="retur_penjualan" class="table table-hover table-bordered table-striped">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl Retur</th>              
              <th>No Retur</th>              
              <th>Tgl Faktur</th>              
              <th>No Faktur</th>              
              <th>Nama Customer</th>              
              <th>Alamat</th>              
              <th>Nilai Faktur</th>              
              <th>Nilai Retur</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          retur_penjualan = $('#retur_penjualan').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            scrollX: true,
            ajax: {
                url: "<?= base_url('api/md/h3/retur_penjualan') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'created_at' }, 
                { data: 'id_retur_penjualan' }, 
                { data: 'tgl_faktur' }, 
                { data: 'no_faktur' }, 
                { data: 'nama_dealer' }, 
                { data: 'alamat' }, 
                { 
                  data: 'total_nilai_faktur',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  },
                  className: 'text-right'
                }, 
                { 
                  data: 'total_nilai_retur',
                  render: function(data){
                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  },
                  className: 'text-right'
                }, 
                { data: 'status' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }
            ],
          });

          retur_penjualan.on('draw.dt', function() {
            var info = retur_penjualan.page.info();
              retur_penjualan.column(0, {
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
    <?php endif; ?>
  </section>
</div>
