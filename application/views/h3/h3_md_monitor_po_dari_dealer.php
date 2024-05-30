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
            <div v-if='!sesuai_dengan_bulan_yang_dipesan' class="alert alert-warning" role="alert">
              <strong>Perhatian!</strong> Tidak bisa melanjutkan ke proses selanjutnya (Approve), dikarenakan PO FIX dengan nomor {{ this.purchase_order.po_id }} adalah PO untuk bulan <?= lang('month_' . $purchase_order['pesan_untuk_bulan']) ?>.
            </div>
            <form class='form-horizontal'>
              <div class="box-body">       
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal PO Dealer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.tanggal_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.kode_dealer_md'>                    
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.po_id'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.nama_dealer'>                    
                  </div>    
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe Penjualan</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.po_type'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.alamat'>                    
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.produk'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.nama_salesman'>                    
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Terima di MD</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.tanggal_submit'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Masa Berlaku PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.batas_waktu'>                    
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive table-condensed">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Kode Part</th>              
                          <th>Nama Part</th>  
                          <th v-if='purchase_order.po_type=="HLO"'>Flag Hotline</th> 
                          <th v-if='purchase_order.po_type=="HLO"'>Import/Lokal</th>   
                          <th v-if='purchase_order.po_type=="HLO"'>Current/Non-Current</th>               
                          <th>HET</th>
                          <th>Disc. Dealer</th>
                          <th>Disc. Campaign</th>
                          <th>Qty AVS</th>
                          <th>Qty PO</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-if='parts.length > 0' v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>
                          <td v-if='purchase_order.po_type=="HLO"' class="align-middle">
                            <!-- <span v-if='part.hoo_flag =="Y"'>Y</span>
                            <span v-if='part.hoo_flag =="N"'>N</span> -->
                            <span v-if='part.hoo_flag ==""'> -</span>
                            <span v-else>{{part.hoo_flag}}</span>
                          </td>  
                          <td v-if='purchase_order.po_type=="HLO"' class="align-middle">
                            <span v-if='part.import_lokal =="N"'> Lokal </span>
                            <span v-if='part.import_lokal =="Y"'> Import</span>
                          </td>
                          <td v-if='purchase_order.po_type=="HLO"' class="align-middle">
                            <span v-if='part.current =="C"'> Current </span>
                            <span v-if='part.current =="N"'> Non-Current</span>
                          </td>                           
                          <td width="8%" class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.harga_saat_dibeli" />
                          </td>
                          <td width="8%" class="align-middle">
                            <vue-numeric read-only :currency='get_currency_symbol(part.tipe_diskon)' :currency-symbol-position='get_currency_position(part.tipe_diskon)' v-model='part.diskon_value' type="text" class="form-control">
                          </td>
                          <td width="8%" class="align-middle">
                            <vue-numeric read-only :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)' v-model='part.diskon_value_campaign' type="text" class="form-control">
                          </td>
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs"></vue-numeric>
                          </td> 
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.kuantitas"></vue-numeric>
                          </td>                       
                          <td width="8%" class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                          </td>          
                        </tr>
                        <tr v-if='parts.length > 0'>
                          <td colspan='8' class='text-right'>Grand Total</td>
                          <td class='text-right'>
                            <vue-numeric read-only currency='Rp' separator='.' v-model='grand_total'></vue-numeric>
                          </td>
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="7">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 text-center">
                  <button v-if='purchase_order.status == "Submitted"' :disabled='loading || !sesuai_dengan_bulan_yang_dipesan' class="btn btn-flat btn-sm btn-success" @click.prevent='proses'>Approve</button>
                  <button v-if='purchase_order.status == "Submitted"' :disabled='loading' class="btn btn-flat btn-sm btn-danger" @click.prevent='reject'>Reject</button>
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
            mode: '<?= $mode ?>',
            purchase_order: <?= json_encode($purchase_order) ?>,
            parts: <?= json_encode($parts) ?>,
          },
          methods: {
            proses: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/proses', {
                params: {
                  id: this.purchase_order.po_id
                }
              })
              .then(function(res){
                window.location = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            reject: function(){
              this.loading = true;
              axios.get('h3/<?= $isi ?>/reject', {
                params: {
                  id: this.purchase_order.po_id
                }
              })
              .then(function(res){
                window.location = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            sub_total: function(part) {
              harga_setelah_diskon = part.harga_saat_dibeli;

              if(part.tipe_diskon == 'Rupiah'){
                harga_setelah_diskon = part.harga_saat_dibeli - part.diskon_value;
              }else if(part.tipe_diskon == 'Persen'){
                diskon = (part.diskon_value/100) * part.harga_saat_dibeli;
                harga_setelah_diskon = part.harga_saat_dibeli - diskon;
              }

              if(part.tipe_diskon_campaign == 'Rupiah'){
                harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
              }else if(part.tipe_diskon_campaign == 'Persen'){
                if(part.jenis_diskon_campaign == 'Additional'){
                  diskon = (part.diskon_value_campaign/100) * harga_setelah_diskon;
                  harga_setelah_diskon = harga_setelah_diskon - diskon;
                }else if(part.jenis_diskon_campaign == 'Non Additional'){
                  diskon = (part.diskon_value_campaign/100) * part.harga_saat_dibeli;
                  harga_setelah_diskon = harga_setelah_diskon - diskon;
                }
              }

              return part.kuantitas * harga_setelah_diskon;
            },
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
          },
          computed: {
            sesuai_dengan_bulan_yang_dipesan: function(){
              if(this.purchase_order.po_type == 'FIX'){
                bulan_sekarang = '<?= date('m', time()) ?>';
                if(bulan_sekarang != parseInt(this.purchase_order.pesan_untuk_bulan)){
                  return false;
                }
              }
              return true;
            },
            grand_total: function(){
              sub_total_fn = this.sub_total;
              return _.chain(this.parts)
              .sumBy(function(part){
                return sub_total_fn(part);
              }).value();
            },
            purchase_order_hotline: function(){
              return this.purchase_order.po_type == 'HLO';
            },
            purchase_order_reguler: function(){
              return this.purchase_order.po_type == 'REG';
            },
            purchase_order_urgent: function(){
              return this.purchase_order.po_type == 'URG';
            },
            purchase_order_fix: function(){
              return this.purchase_order.po_type == 'FIX';
            },
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
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
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-4">
              <div class="row" id='customer_filter'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input :value='filters.length + " Customer "' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_monitor_po_dari_dealer_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_dealer_filter_monitor_po_dari_dealer_index'); ?>         
              <script>
                customer_filter = new Vue({
                    el: '#customer_filter',
                    data: {
                        filters: []
                    },
                    watch: {
                      filters: function(){
                        // monitor_po_dari_dealer.draw();
                        monitor_po_dari_dealer_fix_reguler.draw();
                        monitor_po_dari_dealer_urgent.draw();
                        monitor_po_dari_dealer_hotline.draw();
                        monitor_po_dari_dealer_other.draw();
                      }
                    }
                });

                $("#h3_md_dealer_filter_monitor_po_dari_dealer_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_dealer = target.attr('data-id-dealer');

                  if(target.is(':checked')){
                    customer_filter.filters.push(id_dealer);
                  }else{
                    index_id_dealer = _.indexOf(customer_filter.filters, id_dealer);
                    customer_filter.filters.splice(index_id_dealer, 1);
                  }
                  h3_md_dealer_filter_monitor_po_dari_dealer_index_datatable.draw();
                });
              </script>
              <div class="row" id='tipe_po_filter'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Tipe PO</label>
                    <div class="input-group">
                      <input type="text" class="form-control" readonly :value='filters.length + " Tipe PO"'>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_po_filter_monitor_po_dari_dealer_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php $this->load->view('modal/h3_md_tipe_po_filter_monitor_po_dari_dealer_index'); ?>         
              </div>
              <script>
                tipe_po_filter = new Vue({
                    el: '#tipe_po_filter',
                    data: {
                        filters: []
                    },
                    watch: {
                      filters: function(){
                        // monitor_po_dari_dealer.draw();
                        monitor_po_dari_dealer_fix_reguler.draw();
                        monitor_po_dari_dealer_urgent.draw();
                        monitor_po_dari_dealer_hotline.draw();
                        monitor_po_dari_dealer_other.draw();
                      }
                    }
                });
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No PO</label>
                    <input type="text" class="form-control" id='no_po_filter'>
                  </div>
                </div>
              </div>
              <script>
                $(document).ready(function(){
                  $('#no_po_filter').on('keyup', _.debounce(function(){
                    // monitor_po_dari_dealer.draw();
                    monitor_po_dari_dealer_fix_reguler.draw();
                    monitor_po_dari_dealer_urgent.draw();
                    monitor_po_dari_dealer_hotline.draw();
                    monitor_po_dari_dealer_other.draw();
                  }, 500))
                });
              </script>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Periode</label>
                    <input id='periode_filter' type="text" class="form-control" readonly>
                    <input id='periode_filter_start' type="hidden" disabled>
                    <input id='periode_filter_end' type="hidden" disabled>
                  </div>
                </div>
              </div>
              <script>
                $('#periode_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }, function(start, end, label) {
                  $('#periode_filter_start').val(start.format('YYYY-MM-DD'));
                  $('#periode_filter_end').val(end.format('YYYY-MM-DD'));
                  // monitor_po_dari_dealer.draw();
                  monitor_po_dari_dealer_fix_reguler.draw();
                  monitor_po_dari_dealer_urgent.draw();
                  monitor_po_dari_dealer_hotline.draw();
                  monitor_po_dari_dealer_other.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_filter_start').val('');
                  $('#periode_filter_end').val('');
                  // monitor_po_dari_dealer.draw();
                  monitor_po_dari_dealer_fix_reguler.draw();
                  monitor_po_dari_dealer_urgent.draw();
                  monitor_po_dari_dealer_hotline.draw();
                  monitor_po_dari_dealer_other.draw();
                });
              </script>
            </div>
            <div class="col-sm-4">
              <div class="row" id='salesman_filter'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Salesman</label>
                    <div class="input-group">
                      <input :value='filters.length + " Salesman"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_salesman_filter_create_do_sales_order_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_salesman_filter_create_do_sales_order_index'); ?>
              <script>
                  salesman_filter = new Vue({
                      el: '#salesman_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          // monitor_po_dari_dealer.draw();
                          monitor_po_dari_dealer_fix_reguler.draw();
                          monitor_po_dari_dealer_urgent.draw();
                          monitor_po_dari_dealer_hotline.draw();
                          monitor_po_dari_dealer_other.draw();
                        }
                      }
                  });

                  $("#h3_md_salesman_filter_create_do_sales_order_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_salesman = target.attr('data-id-salesman');

                    if(target.is(':checked')){
                      salesman_filter.filters.push(id_salesman);
                    }else{
                      index_salesman = _.indexOf(salesman_filter.filters, id_salesman);
                      salesman_filter.filters.splice(index_salesman, 1);
                    }
                    h3_md_salesman_filter_create_do_sales_order_index_datatable.draw();
                  });
              </script>
            </div>
          </div>
        </div>
        <!-- <table id="monitor_po_dari_dealer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Nama Customer</th>              
              <th>Alamat</th>              
              <th>Tipe PO</th>              
              <th>Tanggal PO Dealer</th>              
              <th>Tanggal di Terima di MD</th>              
              <th>Tanggal Proses MD</th>              
              <th>No PO</th>              
              <th>Amount</th>              
              <th>Nilai Supply</th>              
              <th>SR (%)</th>              
              <th>Status</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table> -->
        <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#fix_reguler" aria-controls="fix_reguler" role="tab" data-toggle="tab">Fix / Reguler</a></li>
              <li role="presentation"><a href="#urgent" aria-controls="urgent" role="tab" data-toggle="tab">Urgent</a></li>
              <li role="presentation"><a href="#hotline" aria-controls="hotline" role="tab" data-toggle="tab">Hotline</a></li>
              <li role="presentation"><a href="#other" aria-controls="other" role="tab" data-toggle="tab">Other</a></li>
        </ul>

        <div class="tab-content" style="width: 100%;">
          <div role="tabpanel" class="tab-pane active" id="fix_reguler">
            <div class="container-fluid no-padding" style='margin-top: 20px;'>
                <table id="monitor_po_fix_reguler" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th>No.</th>              
                      <th>Nama Customer</th>              
                      <th>Alamat</th>              
                      <th>Tipe PO</th>              
                      <th>Tanggal PO Dealer</th>              
                      <th>Tanggal di Terima di MD</th>              
                      <th>Tanggal Proses MD</th>              
                      <th>No PO</th>              
                      <th>Amount</th>              
                      <th>Nilai Supply</th>              
                      <th>SR (%)</th>              
                      <th>Status</th>  
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <script>
                  $(document).ready(function() {
                    monitor_po_dari_dealer_fix_reguler = $('#monitor_po_fix_reguler').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        // scrollX: true,
                        ajax: {
                          url: "<?= base_url('api/md/h3/monitor_po_dari_dealer_fix_reguler') ?>",
                          dataSrc: "data",
                          type: "POST",
                          data: function(d){
                            d.customer_filter = customer_filter.filters;
                            d.tipe_po_filter = tipe_po_filter.filters;
                            d.salesman_filter = salesman_filter.filters;
                            d.periode_filter_start = $('#periode_filter_start').val();
                            d.periode_filter_end = $('#periode_filter_end').val();
                            d.no_po_filter = $('#no_po_filter').val();
                            d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          }
                        },
                        createdRow: function (row, data, index) {
                          $('td', row).addClass('align-middle');
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'nama_dealer', width: '250px' },
                            { data: 'alamat', width: '200px' },
                            { data: 'po_type' },
                            { data: 'tanggal_order' },
                            { data: 'tanggal_submit' },
                            { data: 'tanggal_proses' },
                            { data: 'po_id' },
                            { 
                              data: 'total_amount', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'amount_sudah_disupply', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'persentase', 
                              className: 'text-right', 
                              render: function(data){
                                return accounting.format(data, 0, ".", ",") + '%';
                              }
                            },
                            { data: 'status', width: '100px' },
                        ],
                    });
                  });
                </script>
              </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="urgent">
            <div class="container-fluid no-padding" style='margin-top: 20px;'>
                <table id="monitor_po_urgent" class="table table-bordered table-hover" style="width: 100% ;overflow:auto;">
                  <thead>
                    <tr>
                      <th>No.</th>              
                      <th>Nama Customer</th>              
                      <th style="width: 200px;">Alamat</th>              
                      <th>Tipe PO</th>              
                      <th>Tanggal PO Dealer</th>              
                      <th>Tanggal di Terima di MD</th>              
                      <th>Tanggal Proses MD</th>              
                      <th>No PO</th>              
                      <th>Amount</th>              
                      <th>Nilai Supply</th>              
                      <th>SR (%)</th>              
                      <th>Status</th>  
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <script>
                  $(document).ready(function() {
                    monitor_po_dari_dealer_urgent = $('#monitor_po_urgent').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        // scrollX: true,
                        ajax: {
                          url: "<?= base_url('api/md/h3/monitor_po_dari_dealer_urgent') ?>",
                          dataSrc: "data",
                          type: "POST",
                          data: function(d){
                            d.customer_filter = customer_filter.filters;
                            d.tipe_po_filter = tipe_po_filter.filters;
                            d.salesman_filter = salesman_filter.filters;
                            d.periode_filter_start = $('#periode_filter_start').val();
                            d.periode_filter_end = $('#periode_filter_end').val();
                            d.no_po_filter = $('#no_po_filter').val();
                            d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          }
                        },
                        createdRow: function (row, data, index) {
                          $('td', row).addClass('align-middle');
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'nama_dealer', width: '250px' },
                            { data: 'alamat', width: '200px' },
                            { data: 'po_type' },
                            { data: 'tanggal_order' },
                            { data: 'tanggal_submit' },
                            { data: 'tanggal_proses' },
                            { data: 'po_id' },
                            { 
                              data: 'total_amount', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'amount_sudah_disupply', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'persentase', 
                              className: 'text-right', 
                              render: function(data){
                                return accounting.format(data, 0, ".", ",") + '%';
                              }
                            },
                            { data: 'status', width: '100px' },
                        ],
                    });
                  });
                </script>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane " id="hotline">
            <div class="table-responsive" style='margin-top: 20px;'>
                <table id="monitor_po_hotline" class="table table-bordered table-hover" style="width: 100%; overflow:auto;">
                  <thead style="width: 100%">
                    <tr>
                      <th>No.</th>              
                      <th>Nama Customer</th>              
                      <th style="width: 200px;">Alamat</th>              
                      <th>Tipe PO</th>              
                      <th>Tanggal PO Dealer</th>              
                      <th>Tanggal di Terima di MD</th>              
                      <th>Tanggal Proses MD</th>              
                      <th>No PO</th>              
                      <th>Amount</th>              
                      <th>Nilai Supply</th>              
                      <th>SR (%)</th>              
                      <th>Status</th>  
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <script>
                  $(document).ready(function() {
                    monitor_po_dari_dealer_hotline = $('#monitor_po_hotline').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        // scrollX: true,
                        ajax: {
                          url: "<?= base_url('api/md/h3/monitor_po_dari_dealer_hotline') ?>",
                          dataSrc: "data",
                          type: "POST",
                          data: function(d){
                            d.customer_filter = customer_filter.filters;
                            d.tipe_po_filter = tipe_po_filter.filters;
                            d.salesman_filter = salesman_filter.filters;
                            d.periode_filter_start = $('#periode_filter_start').val();
                            d.periode_filter_end = $('#periode_filter_end').val();
                            d.no_po_filter = $('#no_po_filter').val();
                            d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          }
                        },
                        createdRow: function (row, data, index) {
                          $('td', row).addClass('align-middle');
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'nama_dealer', width: '250px' },
                            { data: 'alamat', width: '200px' },
                            { data: 'po_type' },
                            { data: 'tanggal_order' },
                            { data: 'tanggal_submit' },
                            { data: 'tanggal_proses' },
                            { data: 'po_id' },
                            { 
                              data: 'total_amount', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'amount_sudah_disupply', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'persentase', 
                              className: 'text-right', 
                              render: function(data){
                                return accounting.format(data, 0, ".", ",") + '%';
                              }
                            },
                            { data: 'status', width: '100px' },
                        ],
                    });
                  });
                </script>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane " id="other">
            <div class="table-responsive" style='margin-top: 20px;'>
                <table id="monitor_po_other" class="table table-bordered table-hover" style="width: 100%; overflow:auto;">
                  <thead>
                    <tr>
                      <th>No.</th>              
                      <th>Nama Customer</th>              
                      <th>Alamat</th>              
                      <th>Tipe PO</th>              
                      <th>Tanggal PO Dealer</th>              
                      <th>Tanggal di Terima di MD</th>              
                      <th>Tanggal Proses MD</th>              
                      <th>No PO</th>              
                      <th>Amount</th>              
                      <th>Nilai Supply</th>              
                      <th>SR (%)</th>              
                      <th>Status</th>  
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <script>
                  $(document).ready(function() {
                    monitor_po_dari_dealer_other = $('#monitor_po_other').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        // scrollX: true,
                        ajax: {
                          url: "<?= base_url('api/md/h3/monitor_po_dari_dealer_other') ?>",
                          dataSrc: "data",
                          type: "POST",
                          data: function(d){
                            d.customer_filter = customer_filter.filters;
                            d.tipe_po_filter = tipe_po_filter.filters;
                            d.salesman_filter = salesman_filter.filters;
                            d.periode_filter_start = $('#periode_filter_start').val();
                            d.periode_filter_end = $('#periode_filter_end').val();
                            d.no_po_filter = $('#no_po_filter').val();
                            d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                          }
                        },
                        createdRow: function (row, data, index) {
                          $('td', row).addClass('align-middle');
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'nama_dealer', width: '250px' },
                            { data: 'alamat', width: '200px' },
                            { data: 'po_type' },
                            { data: 'tanggal_order' },
                            { data: 'tanggal_submit' },
                            { data: 'tanggal_proses' },
                            { data: 'po_id' },
                            { 
                              data: 'total_amount', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'amount_sudah_disupply', 
                              className: 'text-right', 
                              width: '80px',
                              render: function(data){
                                return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                              }
                            },
                            { 
                              data: 'persentase', 
                              className: 'text-right', 
                              render: function(data){
                                return accounting.format(data, 0, ".", ",") + '%';
                              }
                            },
                            { data: 'status', width: '100px' },
                        ],
                    });
                  });
                </script>
            </div>
          </div>
        </div>  
        <!-- <script>
          $(document).ready(function() {
            monitor_po_dari_dealer = $('#monitor_po_dari_dealer').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                scrollX: true,
                ajax: {
                  url: "<?= base_url('api/md/h3/monitor_po_dari_dealer') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.customer_filter = customer_filter.filters;
                    d.tipe_po_filter = tipe_po_filter.filters;
                    d.salesman_filter = salesman_filter.filters;
                    d.periode_filter_start = $('#periode_filter_start').val();
                    d.periode_filter_end = $('#periode_filter_end').val();
                    d.no_po_filter = $('#no_po_filter').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'nama_dealer', width: '250px' },
                    { data: 'alamat', width: '200px' },
                    { data: 'po_type' },
                    { data: 'tanggal_order' },
                    { data: 'tanggal_submit' },
                    { data: 'tanggal_proses' },
                    { data: 'po_id' },
                    { 
                      data: 'total_amount', 
                      className: 'text-right', 
                      width: '80px',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      }
                    },
                    { 
                      data: 'amount_sudah_disupply', 
                      className: 'text-right', 
                      width: '80px',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      }
                    },
                    { 
                      data: 'persentase', 
                      className: 'text-right', 
                      render: function(data){
                        return accounting.format(data, 0, ".", ",") + '%';
                      }
                    },
                    { data: 'status', width: '100px' },
                ],
            });
          });
        </script> -->
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
