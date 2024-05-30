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
        $form = 'save_do';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <div id='app' class="box box-default">
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
            <form class="form-horizontal">
              <div class="box-body">    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">                    
                    <input v-if='do_sales_order.tanggal_do != "" && do_sales_order.tanggal_do != null' type="text" readonly class="form-control" :value='moment(do_sales_order.tanggal_do).format("DD/MM/YYYY")'/>
                  </div>                                
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.nama_dealer'>                    
                  </div>  
                </div>    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.id_do_sales_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.kode_dealer_md'>                    
                  </div>      
                </div>      
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">                    
                    <input v-if='do_sales_order.tanggal_so != "" && do_sales_order.tanggal_so != null' type="text" readonly class="form-control" :value='moment(do_sales_order.tanggal_so).format("DD/MM/YYYY")'/>
                  </div>                                
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.alamat'>                    
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.id_sales_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon'></vue-numeric>                
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">TOP</label>
                  <div class="col-sm-4">                    
                    <input v-if='do_sales_order.top != "" && do_sales_order.top != null' type="text" readonly class="form-control" :value='moment(do_sales_order.top).format("DD/MM/YYYY")'/>
                  </div>                                
                  <label class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='sisa_plafon'></vue-numeric>                
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Name Salesman</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.nama_salesman'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon_booking'></vue-numeric>                
                  </div>      
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.po_type'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.kategori_po'>                    
                  </div>      
                </div>
                <div v-if='do_sales_order.gimmick == 1' class="form-group">                  
                  <label class="col-sm-2 control-label">Gimmick</label>
                  <div class="col-sm-4">                    
                    <input v-if='do_sales_order.gimmick == 1' type="text" readonly class="form-control" value='Yes'>                    
                    <input v-if='do_sales_order.gimmick == 0' type="text" readonly class="form-control" value='No'>                    
                  </div>
                  <label class="col-sm-2 control-label">Nama Campaign</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" :value='do_sales_order.nama_campaign + " (" + do_sales_order.kode_campaign + ")"'>                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.status'>                    
                  </div>                                
                  <div v-if='do_sales_order.status == "Rejected"'>
                    <label class="col-sm-2 control-label">Alasan Reject</label>
                    <div class="col-sm-4">                    
                      <input type="text" readonly class="form-control" v-model='do_sales_order.alasan_reject'>                    
                    </div>  
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr class='bg-blue-gradient'>                                      
                          <th width='3%'>No.</th>              
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th v-if='kategori_kpb'>Tipe Kendaraan</th>              
                          <th>HET</th>              
                          <th width='10%'>Qty</th>
                          <th width='10%'>Diskon Satuan Dealer</th>
                          <th width='10%'>Diskon Campaign</th>
                          <th width='10%' class='text-right'>Harga Setelah Diskon</th>
                          <th width='10%' class='text-right'>Amount</th>
                          <!-- <th width='10%' class='text-right'>Harga Beli</th> -->
                          <!-- <th width='10%' class='text-right'>Selisih</th> -->
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) of parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td v-if='kategori_kpb' class="align-middle">{{ part.id_tipe_kendaraan }}</td>                       
                          <td class="align-middle">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.harga_jual"/>
                          </td>
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" :max='part.qty_order' v-model="part.qty_supply" v-on:keypress.native="qty_order_change_handler"/>
                          </td>
                          <td class="align-middle">
                            <vue-numeric read-only v-model="part.diskon_satuan_dealer" :currency='get_currency_symbol(part.tipe_diskon_satuan_dealer)' :currency-symbol-position='get_currency_position(part.tipe_diskon_satuan_dealer)' v-bind:precision="2" thousand-separator='.'/>
                          </td> 
                          <td class="align-middle">
                            <vue-numeric read-only v-model="part.diskon_campaign" :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)' v-bind:precision="2" thousand-separator='.'/>
                          </td> 
                          <td class="align-middle text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part)"/>
                          </td>   
                          <td class="align-middle text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="amount(part)"/>
                          </td>  
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td class="text-right" colspan="8">Sub Total</td>
                          <td class="text-right" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td class="text-right" colspan="8">PPN</td>
                          <td class="text-right" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="do_sales_order.total_ppn" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='7'></td>
                          <td class="text-right align-middle" colspan="1">Insentif Langsung</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric read-only class="form-control" separator="." v-model="insentif_langsung" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='2' class='align-middle'>Total Insentif</td>
                          <td class='align-middle'>
                            <vue-numeric :read-only="true" class="form-control" v-model='do_sales_order.insentif_dealer' separator="." currency='Rp'/>
                          </td>
                          <td colspan='4'></td>
                          <td class="text-right align-middle" colspan="1">Diskon Insentif</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric read-only class="form-control" separator="." v-model="do_sales_order.diskon_insentif" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='7'></td>
                          <td class="text-right align-middle" colspan="1">Cashback Langsung</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric read-only class="form-control" separator="." v-model="do_sales_order.diskon_cashback_otomatis" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='7'></td>
                          <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric read-only class="form-control" separator="." v-model="do_sales_order.diskon_cashback" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='7'></td>
                          <td class="text-right align-middle" colspan="1">Total Diskon</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_diskon" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='7'></td>
                          <td class="text-right align-middle" colspan="1">Total</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency='Rp'/>
                          </td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
                <div class="container-fluid no-padding">
                  <div class="col-sm-6 no-padding">
                    <a v-if='do_sales_order.status == "Rejected" && mode == "detail"' :href="'h3/h3_md_do_sales_order_h3/edit?id=' + do_sales_order.id_do_sales_order" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    <button v-if='mode == "edit"' @click.prevent='update' class="btn btn-flat btn-sm btn-warning">Update</button>
                    <button v-if='mode == "detail" && do_sales_order.status == "Rejected"' @click.prevent='proses' class="btn btn-flat btn-sm btn-info">Proses</button>
                  </div>
                  <div class="col-sm-6 no-padding text-right">
                    <button v-if='mode == "detail" && do_sales_order.status == "Rejected"' @click.prevent='cancel' class="btn btn-flat btn-sm btn-danger">Cancel</button>
                    <!-- <button v-if='mode == "edit"' class="btn btn-flat btn-primary btn-sm" type='button' data-toggle='modal' data-target='#h3_md_parts_do_sales_order'><i class="fa fa-plus"></i></button> -->
                  </div>
                  <?php // $this->load->view('modal/h3_md_parts_do_sales_order'); ?>
                  <?php $this->load->view('modal/h3_md_view_tipe_motor_part_sales_order'); ?>
                  <input type="hidden" id='id_part_untuk_view_tipe_motor'>
                  <script>
                      function pilih_parts_do_sales_order(part) {
                          app.parts.push(part);
                          app.get_diskon_parts();
                          h3_md_parts_do_sales_order_datatable.draw();
                      }

                      function open_view_tipe_motor_part_sales_order_modal(id_part) {
                        $('#id_part_untuk_view_tipe_motor').val(id_part);
                        h3_md_view_tipe_motor_part_sales_order_datatable.draw();
                        $('#h3_md_view_tipe_motor_part_sales_order').modal('show');
                      }
                  </script>
                </div>
                <table style='margin-top: 20px;' class="table table-compact">
                  <tr class='bg-blue-gradient'>
                    <td>No.</td>
                    <td>No Faktur</td>
                    <td>Tanggal Faktur</td>
                    <td>Tanggal Jatuh Tempo</td>
                    <td>Nominal</td>
                    <td>Status Pembayaran</td>
                  </tr>
                  <tr v-if='monitoring_piutang.length > 0' v-for='(piutang, index) of monitoring_piutang'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ piutang.referensi }}</td>
                    <td>{{ piutang.tanggal_transaksi }}</td>
                    <td>{{ piutang.tanggal_jatuh_tempo }}</td>
                    <td class='text-right'>
                      <vue-numeric read-only v-model='piutang.sisa_piutang' currency='Rp' separator='.'></vue-numeric>
                    </td>
                    <td @click.prevent='open_status_pembayaran(piutang.referensi)'>
                      <ul v-if='piutang.list_bg.length > 0' class='no-margin'>
                        <li v-for='bg of piutang.list_bg'>{{ bg.nomor_bg }}</li>
                      </ul>
                    </td>
                  </tr>
                  <tr v-if='monitoring_piutang.length > 0'>
                    <td colspan='4' class='text-right'>Total</td>
                    <td class='text-right'>
                      <vue-numeric read-only v-model='total_sisa_piutang' currency='Rp' separator='.'></vue-numeric>
                    </td>
                    <td></td>
                  </tr>
                  <tr v-if='monitoring_piutang.length < 1'>
                    <td class='text-center' colspan='6'>Tidak ada data</td>
                  </tr>
                </table>
                <?php $this->load->view('modal/h3_md_open_status_pembayaran_piutang_pada_do'); ?>
                <table style='margin-top: 20px;' class="table table-compact">
                  <tr class='bg-blue-gradient'>
                    <td width='3%'>No.</td>
                    <td>No. Campaign</td>
                    <td>Nama</td>
                    <td>Jenis Reward</td>
                    <td>Cashback</td>
                  </tr>
                  <tr v-if='do_cashback.length > 0' v-for='(row, index) of do_cashback'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ row.kode_campaign }}</td>
                    <td>{{ row.nama }}</td>
                    <td>{{ row.reward_cashback }}</td>
                    <td>
                      <vue-numeric read-only v-model='row.cashback' separator='.'></vue-numeric>
                    </td>
                  </tr>
                  <tr v-if='do_cashback.length < 1'>
                    <td class='text-center' colspan='5'>Tidak ada data</td>
                  </tr>
                </table>
                <table style='margin-top: 20px;' class="table table-compact">
                  <tr class='bg-blue-gradient'>
                    <td width='3%'>No.</td>
                    <td>No. Campaign</td>
                    <td>Nama</td>
                    <td>Poin</td>
                    <td>Insentif</td>
                  </tr>
                  <tr v-if='do_poin.length > 0' v-for='(row, index) of do_poin'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ row.kode_campaign }}</td>
                    <td>{{ row.nama }}</td>
                    <td>
                      <vue-numeric read-only v-model='row.poin' separator='.'></vue-numeric>
                    </td>
                    <td>
                      <vue-numeric read-only v-model='row.nilai_insentif' separator='.' currency='Rp'></vue-numeric>
                    </td>
                  </tr>
                  <tr v-if='do_poin.length < 1'>
                    <td class='text-center' colspan='5'>Tidak ada data</td>
                  </tr>
                </table>
                <table style='margin-top: 20px;' class="table table-compact">
                  <tr class='bg-blue-gradient'>
                    <td width='3%'>No.</td>
                    <td>No. Campaign</td>
                    <td>Nama</td>
                    <td>Hadiah Part</td>
                    <td>Kuantitas Hadiah</td>
                    <td>No. SO</td>
                    <td>Status SO</td>
                    <td></td>
                  </tr>
                  <tr v-if='do_gimmick.length > 0' v-for='(row, index) of do_gimmick'>
                    <td>{{ index + 1 }}.</td>
                    <td>{{ row.kode_campaign }}</td>
                    <td>{{ row.nama }}</td>
                    <td>{{ row.id_part }}</td>
                    <td>
                      <vue-numeric read-only v-model='row.qty_hadiah' separator='.'></vue-numeric>
                    </td>
                    <td>
                      <span v-if='row.id_sales_order != null'>{{ row.id_sales_order }}</span>
                      <span v-if='row.id_sales_order == null'>-</span>
                    </td>
                    <td>
                      <span v-if='row.id_sales_order != null'>{{ row.status_so }}</span>
                      <span v-if='row.id_sales_order == null'>-</span>
                    </td>
                    <td>
                      <a v-if='boleh_claim_gimmick(row)' :disabled='row.sudah_claim == 1' target='_blank' :href="'h3/h3_md_sales_order/add?generateGimmick=true&id_do_sales_order=' + do_sales_order.id_do_sales_order + '&id_campaign=' + row.id_campaign + '&id_item=' + row.id_item"  class="btn btn-flat btn-xs btn-success">Generate SO Gimmick</a>
                    </td>
                  </tr>
                  <tr v-if='do_gimmick.length < 1'>
                    <td class='text-center' colspan='6'>Tidak ada data</td>
                  </tr>
                </table>
              </div><!-- /.box-body -->
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
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            do_sales_order: <?= json_encode($do_sales_order) ?>,
            parts: <?= json_encode($do_sales_order_parts) ?>,
            monitoring_piutang: <?= json_encode($monitoring_piutang) ?>,
            do_cashback: <?= json_encode($do_cashback) ?>,
            do_gimmick: <?= json_encode($do_gimmick) ?>,
            do_poin: <?= json_encode($do_poin) ?>,
            <?php else: ?>
            do_sales_order: {},
            parts: [],
            monitoring_piutang: [],
            do_poin: [],
            <?php endif; ?>
          },
          methods: {
            update: function(status){
              this.loading = true;
              post = {};
              post = _.pick(this.do_sales_order, [
                'id_do_sales_order', 'diskon_cashback', 'diskon_insentif'
              ]);
              post.total = this.total;
              post.total_ppn = this.total_ppn;
              post.sub_total = this.sub_total;
              
              harga_setelah_diskon_fn = this.harga_setelah_diskon;
              post.parts = _.map(this.parts, function(part){
                data = _.pick(part, [
                  'id_part', 'qty_supply', 'harga_jual', 'harga_beli',
                  'tipe_diskon_campaign', 'diskon_campaign',
                  'tipe_diskon_satuan_dealer', 'diskon_satuan_dealer'
                ]);
                data.harga_setelah_diskon = harga_setelah_diskon_fn(part);
                return data;
              });

              axios.post("h3/h3_md_do_sales_order_h3/update", Qs.stringify(post))
              .then(function(res){  
                window.location = 'h3/h3_md_do_sales_order_h3/detail?id=' + res.data.id_do_sales_order;
              })
              .catch(function(err){ toastr.error(err); })
              .then(function(){ app.loading = false; });
            },
            proses: function(status){
              this.loading = true;
              axios.get('h3/h3_md_do_sales_order_h3/proses', {
                params: {
                  id_do_sales_order: this.do_sales_order.id_do_sales_order
                }
              })
              .then(function(res){  
                window.location = 'h3/h3_md_do_sales_order_h3/detail?id=' + res.data.id_do_sales_order;
              })
              .catch(function(err){ toastr.error(err); })
              .then(function(){ app.loading = false; });
            },
            cancel: function(status){
              this.loading = true;
              axios.get('h3/h3_md_do_sales_order_h3/cancel', {
                params: {
                  id_do_sales_order: this.do_sales_order.id_do_sales_order
                }
              })
              .then(function(res){  
                data = res.data;
                if(data.redirect_url != null){
                  window.location = data.redirect_url;
                }
              })
              .catch(function(err){ 
                data = err.response.data;
                if (data.error_type == 'validation_error') {
                  app.errors = data.errors;
                  toastr.error(data.message);
                } else {
                  toastr.error(data.message);
                }

                app.loading = false;
              });
            },
            get_salesman: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/get_salesman', {
                params: {
                  id_dealer: this.do_sales_order.id_dealer
                }
              })
              .then(function(res){
                app.do_sales_order.id_salesman = res.data.id_salesman;
                app.do_sales_order.nama_salesman = res.data.nama_salesman;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            hitung_dpp: function(part){
              if(part.include_ppn == 1){
                return part.harga/1.1;
              }
              return part.harga;
            },
            harga_setelah_diskon: function(part){
              harga_setelah_diskon = part.harga_jual;
              harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, harga_setelah_diskon);

              if(part.jenis_diskon_campaign == 'Additional'){
                harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, harga_setelah_diskon);
              }else if(part.jenis_diskon_campaign == 'Non Additional'){
                harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_jual);
              }

              return harga_setelah_diskon;
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
            amount: function(part) {
              return this.harga_setelah_diskon(part) * part.qty_supply
            },
            get_parts_diskon: function(){
              if(this.parts.length < 1 || this.do_sales_order.po_type == ''|| this.do_sales_order.id_dealer == '') return;

              this.loading = true;
              axios.get('h3/h3_md_diskon_part_tertentu/get_parts_diskon', {
                params: {
                    id_part: _.map(this.parts, function(p){
                      return p.id_part
                    }),
                    po_type: this.do_sales_order.po_type,
                    id_dealer: this.do_sales_order.id_dealer,
                }
              }).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(app.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  app.parts[index].tipe_diskon_satuan_dealer = data.tipe_diskon;
                  app.parts[index].diskon_satuan_dealer = data.diskon_value;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ app.loading = false; });
            },
            get_parts_sales_campaign: function(){
              return;
              this.loading = true;
              axios.get('h3/h3_md_sales_order/get_parts_sales_campaign', {
                params: {
                    id_part: _.map(this.parts, function(p){
                      return p.id_part
                    }),
                }
              }).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(app.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  app.parts[index].tipe_diskon_campaign = data.tipe_diskon_campaign;
                  app.parts[index].diskon_value_campaign = data.diskon_value_campaign;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ app.loading = false; });
            },
            get_parts_diskon_oli_reguler: function(){
              if(this.parts.length < 1 || this.do_sales_order.id_dealer == '') return;

              this.loading = true;
              post = _.pick(this.do_sales_order, ['id_dealer']);
              post.parts = _.map(this.parts, function(p){
                data = _.pick(p, ['id_part']);
                data.kuantitas = p.qty_supply;
                return data;
              });

              axios.post('h3/h3_md_diskon_oli_reguler/get_parts_diskon_oli_reguler', Qs.stringify(post)).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(app.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  app.parts[index].tipe_diskon_satuan_dealer = data.tipe_diskon;
                  app.parts[index].diskon_satuan_dealer = data.diskon_value;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ app.loading = false; });
            },
            get_diskon_parts: function(){
              if(this.do_sales_order.produk){
                this.get_parts_diskon_oli_reguler();
              }else{
                this.get_parts_diskon();
              }
              this.get_parts_sales_campaign();
            },
            qty_order_change_handler: _.debounce(function($event){
              app.get_diskon_parts();
            }, 500),
            get_currency_position: function(tipe_diskon){
              if(tipe_diskon == 'Rupiah'){
                return 'prefix';
              }else if(tipe_diskon == 'Persen'){
                return 'suffix';
              }
              return;
            },
            get_currency_symbol: function(tipe_diskon){
              if(tipe_diskon == 'Rupiah'){
                return 'Rp';
              }else if(tipe_diskon == 'Persen'){
                return '%';
              }
              return;
            },
            open_status_pembayaran: function(referensi){
              $('#referensi_open_status_pembayaran').val(referensi);
              h3_md_open_status_pembayaran_piutang_pada_do_datatable.draw();
              $('#h3_md_open_status_pembayaran_piutang_pada_do').modal('show');
            },
            boleh_claim_gimmick: function(row){
              result = this.do_sales_order.sudah_create_faktur == 1 && row.reward_gimmick == "Langsung" && row.id_sales_order == null && row.status_so != "Closed";
              // console.log(this.do_sales_order.sudah_create_faktur == 1 , row.reward_gimmick == "Langsung" , row.id_sales_order == null , row.status_so != "Closed");
              // console.log(result);
              return result;
            }
          },
          computed: {
            kategori_kpb: function(){
              return this.do_sales_order.kategori_po == 'KPB';
            },
            sub_total: function(){
              total = 0;
              for (index = 0; index < this.parts.length; index++) {
                part = this.parts[index];
                total += this.amount(part);
              }
              return total;
            },
            total_diskon: function(){
              return (parseFloat(this.do_sales_order.diskon_insentif) + parseFloat(this.insentif_langsung)) + ( parseFloat(this.do_sales_order.diskon_cashback) + parseFloat(this.do_sales_order.diskon_cashback_otomatis) );
            },
            total: function(){
              // return this.sub_total - this.total_diskon;
              return this.sub_total - this.total_diskon + this.do_sales_order.total_ppn;
            },
            sisa_plafon: function(){
              return this.do_sales_order.plafon - this.do_sales_order.plafon_booking - this.do_sales_order.plafon_yang_dipakai;
            },
            total_sisa_piutang: function(){
              return _.chain(this.monitoring_piutang)
              .sumBy(function(item){
                return item.sisa_piutang;
              })
              .value();
            },
            do_gimmick_langsung: function(){
              return _.chain(this.do_gimmick)
              .filter(function(row){
                return row.reward_gimmick == 'Langsung';
              })
              .filter(function(row){
                return row.sudah_claim == 0;
              })
              .value();
            },
            insentif_langsung: function(){
              return _.chain(this.do_poin)
              .sumBy(function(row){
                return row.nilai_insentif;
              })
              .value();
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
      <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
        <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div id='customer_filter' class="form-group">
                  <label class="control-label col-sm-4 align-middle">Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " Customer"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_do_sales_order_index'); ?>         
                <script>
                    customer_filter = new Vue({
                        el: '#customer_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            do_sales.draw();
                          }
                        }
                    });

                    $("#h3_md_dealer_filter_do_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_dealer = target.attr('data-id-dealer');

                      if(target.is(':checked')){
                        customer_filter.filters.push(id_dealer);
                      }else{
                        index_kabupaten = _.indexOf(customer_filter.filters, id_dealer);
                        customer_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_dealer_filter_do_sales_order_index_datatable.draw();
                    });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Periode Sales</label>
                  <div class="col-sm-8">
                    <input id='periode_sales_filter' type="text" class="form-control" readonly>
                    <input id='periode_sales_filter_start' type="hidden" disabled>
                    <input id='periode_sales_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#periode_sales_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#periode_sales_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#periode_sales_filter_end').val(end.format('YYYY-MM-DD'));
                    do_sales.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_sales_filter_start').val('');
                    $('#periode_sales_filter_end').val('');
                    do_sales.draw();
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No SO</label>
                  <div class="col-sm-8">
                    <input id='no_so_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#no_so_filter').on("keyup", _.debounce(function(){
                      do_sales.draw();
                    }, 500));
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No DO</label>
                  <div class="col-sm-8">
                    <input id='no_do_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#no_do_filter').on("keyup", _.debounce(function(){
                      do_sales.draw();
                    }, 500));
                  });
                </script>
              </div>
            </div>
            <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Salesman</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_salesman_filter' type="text" class="form-control" disabled>
                      <input id='id_salesman_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_salesman_filter_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_salesman_filter_do_sales_order_index'); ?>         
                <script>
                function pilih_salesman_filter_do_sales_order_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_salesman_filter').val(data.nama_lengkap);
                    $('#id_salesman_filter').val(data.id_karyawan);
                  }else if(type == 'reset_filter'){
                    $('#nama_salesman_filter').val('');
                    $('#id_salesman_filter').val('');
                  }
                  do_sales.draw();
                  h3_md_salesman_filter_do_sales_order_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Tipe Penjualan</label>
                  <div class="col-sm-8">
                    <select id="tipe_penjualan_filter" class="form-control">
                      <option value="">-Pilih-</option>
                      <option value="FIX">Fixed</option>
                      <option value="REG">Reguler</option>
                      <option value="HLO">Hotline</option>
                      <option value="URG">Urgent</option>
                    </select>
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#tipe_penjualan_filter').on("change", function(){
                      do_sales.draw();
                    });
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kategori Sales</label>
                  <div class="col-sm-8">
                    <select id="kategori_sales_filter" class="form-control">
                      <option value="">-Pilih-</option>
                      <option value="SIM Part">SIM Part</option>
                      <option value="Non SIM Part">Non SIM Part</option>
                      <option value="KPB">KPB</option>
                    </select>
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#kategori_sales_filter').on("change", function(){
                      do_sales.draw();
                    });
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Tipe Produk</label>
                  <div class="col-sm-8">
                    <select id="tipe_produk_filter" class="form-control">
                      <option value="">-Pilih-</option>
                      <option value="Parts">Parts</option>
                      <option value="Oil">Oil</option>
                      <option value="Acc">Accesories</option>
                      <option value="Apparel">Apparel</option>
                      <option value="Tools">Tools</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#tipe_produk_filter').on("change", function(){
                      do_sales.draw();
                    });
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div id='jenis_dealer_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Jenis Dealer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " filter"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_jenis_dealer_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_jenis_dealer_filter_sales_order_index'); ?>
                <script>
                    jenis_dealer_filter = new Vue({
                        el: '#jenis_dealer_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            do_sales.draw();
                          }
                        }
                    })
                </script>
              </div>
              <div id='kabupaten_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kabupaten</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " kabupaten"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kabupaten_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_kabupaten_filter_sales_order_index'); ?>
                <script>
                    kabupaten_filter = new Vue({
                        el: '#kabupaten_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            do_sales.draw();
                          }
                        }
                    });

                    $("#h3_md_kabupaten_filter_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_kabupaten = target.attr('data-id-kabupaten');


                      if(target.is(':checked')){
                        kabupaten_filter.filters.push(id_kabupaten);
                      }else{
                        index_kabupaten = _.indexOf(kabupaten_filter.filters, id_kabupaten);
                        kabupaten_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_kabupaten_filter_sales_order_index_datatable.draw();
                    });
                </script>
              </div>
            </div>
            <div class="row">
              <div id='kelompok_part_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " kelompok part"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_sales_order_index'); ?>
                <script>
                    kelompok_part_filter = new Vue({
                        el: '#kelompok_part_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            do_sales.draw();
                          }
                        }
                    });

                    $("#h3_md_kelompok_part_filter_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_kelompok_part = target.attr('data-id-kelompok-part');

                      if(target.is(':checked')){
                        kelompok_part_filter.filters.push(id_kelompok_part);
                      }else{
                        index_kabupaten = _.indexOf(kelompok_part_filter.filters, id_kelompok_part);
                        kelompok_part_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_kelompok_part_filter_sales_order_index_datatable.draw();
                    });
                </script>
              </div>
              <div id='status_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Status</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " Status"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_status_filter_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_status_filter_do_sales_order_index'); ?>
                <script>
                    status_filter = new Vue({
                        el: '#status_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            do_sales.draw();
                          }
                        }
                    })
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table class="table table-condensed">
                  <tr>
                    <th colspan='2'>Produk</th>
                    <th>Nilai SO</th>
                    <th>Nilai DO</th>
                    <th>S/R (%)</th>
                  </tr>
                  <tr>
                    <td>Parts</td>
                    <td width='3%' class='text-center'>:</td>
                    <td id='amount_parts_sales_order'>Rp 0</td>
                    <td id='amount_parts_do_sales_order'>Rp 0</td>
                    <td id='service_rate_parts'>0%</td>
                  </tr>
                  <tr>
                    <td>Qty Parts</td>
                    <td width='3%' class='text-center'>:</td>
                    <td id='qty_parts_sales_order'>0</td>
                    <td id='qty_parts_do_sales_order' colspan='2'>0</td>
                  </tr>
                  <tr>
                    <td>Oil</td>
                    <td width='3%' class='text-center'>:</td>
                    <td id='amount_oil_sales_order'>Rp 0</td>
                    <td id='amount_oil_do_sales_order'>Rp 0</td>
                    <td id='service_rate_oil'>0%</td>
                  </tr>
                  <tr>
                    <td>Qty Oil</td>
                    <td width='3%' class='text-center'>:</td>
                    <td id='qty_oil_sales_order'>0</td>
                    <td id='qty_oil_do_sales_order' colspan='2'>0</td>
                  </tr>
                  <tr>
                    <td>Accesories</td>
                    <td width='3%' class='text-center'>:</td>
                    <td id='amount_acc_sales_order'>Rp 0</td>
                    <td id='amount_acc_do_sales_order'>Rp 0</td>
                    <td id='service_rate_acc'>0%</td>
                  </tr>
                  <tr>
                    <td>Qty Accesories</td>
                    <td width='3%' class='text-center'>:</td>
                    <td id='qty_acc_sales_order'>0</td>
                    <td id='qty_acc_do_sales_order' colspan='2'>0</td>
                  </tr>
                </table>
              </div>
            </div>
          </form>
        </div>
        <table id="create_do_sales_order_index" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal SO</th>              
              <th>Nomor SO</th>              
              <th>Tanggal DO</th>              
              <th>Nomor DO</th>
              <th>Kode Customer</th>
              <th>Nama Customer</th>
              <th>Kota/Kabupaten</th>
              <th>Nilai SO</th>
              <th>Nilai DO Awal</th>
              <th>Nilai DO-Rev</th>
              <th>S/R</th>
              <th>Selisih Nilai DO</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function(){
        do_sales = $('#create_do_sales_order_index').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          scrollX: true,
          order: [],
          ajax: {
              url: "<?= base_url('api/md/h3/do_sales_order_h3') ?>",
              dataSrc: function(json){
                filters = {};
                filters.customer_filter = customer_filter.filters;
                filters.id_salesman_filter = $('#id_salesman_filter').val();
                filters.no_so_filter = $('#no_so_filter').val();
                filters.no_do_filter = $('#no_do_filter').val();
                filters.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                filters.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                filters.tipe_penjualan_filter = $('#tipe_penjualan_filter').val();
                filters.kategori_sales_filter = $('#kategori_sales_filter').val();
                filters.tipe_produk_filter = $('#tipe_produk_filter').val();
                filters.jenis_dealer_filter = jenis_dealer_filter.filters;
                filters.kabupaten_filter = kabupaten_filter.filters;
                filters.kelompok_part_filter = kelompok_part_filter.filters;
                filters.status_filter = status_filter.filters;
                filters.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;

                axios.post('<?= base_url('api/md/h3/do_sales_order_h3/get_sales_order_info/Parts') ?>', Qs.stringify(filters))
                .then(function(res){
                  data = res.data;
                  $('#amount_parts_sales_order').text(
                    accounting.formatMoney(data.total_so, "Rp ", 0, ".", ",")
                  );

                  $('#amount_parts_do_sales_order').text(
                    accounting.formatMoney(data.sub_total_do_awal, "Rp ", 0, ".", ",")
                  );

                  service_rate_parts = (data.sub_total_do_awal / data.total_so) * 100;
                  if(Number.isNaN(service_rate_parts)){
                    service_rate_parts = 0;
                  }
                  $('#service_rate_parts').text(
                    accounting.toFixed(service_rate_parts, service_rate_parts % 1 == 0 ? 0 : 2) + '%'
                  );

                  $('#qty_parts_sales_order').text(accounting.formatNumber(data.qty_parts_sales_order, 0, '.'));
                  $('#qty_parts_do_sales_order').text(accounting.formatNumber(data.qty_parts_do_sales_order, 0, '.'));
                })
                .catch(function(err){
                  toastr.error(err);
                });

                axios.post('<?= base_url('api/md/h3/do_sales_order_h3/get_sales_order_info/Oil') ?>', Qs.stringify(filters))
                .then(function(res){
                  data = res.data;
                  $('#amount_oil_sales_order').text(
                    accounting.formatMoney(data.total_so, "Rp ", 0, ".", ",")
                  );

                  $('#amount_oil_do_sales_order').text(
                    accounting.formatMoney(data.sub_total_do_awal, "Rp ", 0, ".", ",")
                  );

                  service_rate_oil = (data.sub_total_do_awal / data.total_so) * 100;
                  if(Number.isNaN(service_rate_oil)){
                    service_rate_oil = 0;
                  }
                  $('#service_rate_oil').text(
                    accounting.toFixed(service_rate_oil, service_rate_oil % 1 == 0 ? 0 : 2) + '%'
                  );

                  $('#qty_oil_sales_order').text(accounting.formatNumber(data.qty_parts_sales_order, 0, '.'));
                  $('#qty_oil_do_sales_order').text(accounting.formatNumber(data.qty_parts_do_sales_order, 0, '.'));
                })
                .catch(function(err){
                  toastr.error(err);
                });

                axios.post('<?= base_url('api/md/h3/do_sales_order_h3/get_sales_order_info/Acc') ?>', Qs.stringify(filters))
                .then(function(res){
                  data = res.data;
                  $('#amount_acc_sales_order').text(
                    accounting.formatMoney(data.total_so, "Rp ", 0, ".", ",")
                  );

                  $('#amount_acc_do_sales_order').text(
                    accounting.formatMoney(data.sub_total_do_awal, "Rp ", 0, ".", ",")
                  );

                  service_rate_acc = (data.sub_total_do_awal / data.total_so) * 100;
                  if(Number.isNaN(service_rate_acc)){
                    service_rate_acc = 0;
                  }
                  $('#service_rate_acc').text(
                    accounting.toFixed(service_rate_acc, service_rate_acc % 1 == 0 ? 0 : 2) + '%'
                  );

                  $('#qty_acc_sales_order').text(accounting.formatNumber(data.qty_parts_sales_order, 0, '.'));
                  $('#qty_acc_do_sales_order').text(accounting.formatNumber(data.qty_parts_do_sales_order, 0, '.'));
                })
                .catch(function(err){
                  toastr.error(err);
                });

                return json.data;
              },
              type: "POST",
              data: function(d){
                d.customer_filter = customer_filter.filters;
                d.id_salesman_filter = $('#id_salesman_filter').val();
                d.no_so_filter = $('#no_so_filter').val();
                d.no_do_filter = $('#no_do_filter').val();
                d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                d.tipe_penjualan_filter = $('#tipe_penjualan_filter').val();
                d.kategori_sales_filter = $('#kategori_sales_filter').val();
                d.tipe_produk_filter = $('#tipe_produk_filter').val();
                d.jenis_dealer_filter = jenis_dealer_filter.filters;
                d.kabupaten_filter = kabupaten_filter.filters;
                d.kelompok_part_filter = kelompok_part_filter.filters;
                d.status_filter = status_filter.filters;
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
              { data: null, orderable: false, width: '3%' },
              { data: 'tanggal_so' }, 
              { data: 'id_sales_order', width: '200px' }, 
              { data: 'tanggal_do' }, 
              { 
                data: 'id_do_sales_order', width: '200px',
                render: function(data, type, row){
                  if(row.sudah_revisi == 1){
                      data += "-REV";
                  }
                  return data;
                }
              }, 
              { data: 'kode_dealer_md' }, 
              { data: 'nama_dealer', width: '200px' },
              { data: 'kabupaten' },
              { 
                data: 'total_so', 
                width: '100px', 
                className: 'text-right',
                render: function(data){
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              }, 
              { 
                data: 'sub_total_do_awal', 
                width: '100px', 
                className: 'text-right',
                render: function(data){
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              }, 
              { 
                data: 'sub_total_do_rev', 
                width: '100px', 
                className: 'text-right',
                render: function(data){
                  if(data == null) return '-';
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              }, 
              { 
                data: 'service_rate',
                render: function(data){
                  return accounting.formatNumber(data, 2) + '%';
                }
              },
              { 
                data: 'sisa_nilai_do', 
                width: '100px', 
                className: 'text-right',
                render: function(data){
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              },
              { data: 'status' },
              { data: 'action', orderable: false, width: '3%' }
          ],
        });

        do_sales.on('draw.dt', function() {
          var info = do_sales.page.info();
          do_sales.column(0, {
              search: 'applied',
              order: 'applied',
              page: 'applied'
          }).nodes().each(function(cell, i) {
              cell.innerHTML = i + 1 + info.start + ".";
          });
        });
      });
    </script>
    <?php $this->load->view('modal/h3_md_view_modal_sales_order_on_do_sales_order'); ?>
    <script>
    function view_modal_sales_order_on_do_sales_order(id_sales_order) {
      url = 'iframe/md/h3/h3_md_sales_order?id_sales_order=' + id_sales_order;
      $('#view_iframe_sales_order_on_do_sales_order').attr('src', url);
      $('#h3_md_view_modal_sales_order_on_do_sales_order').modal('show');
    }
    </script>
    <?php endif; ?>
  </section>
</div>