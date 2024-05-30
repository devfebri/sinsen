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
                    <label class="col-sm-2 control-label">Kode Customer</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" v-model='sales_order.kode_dealer_md'>                    
                    </div>                                
                    <label class="col-sm-2 control-label">Tipe PO</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" v-model='sales_order.po_type'>                    
                    </div>  
                </div>    
                <div class="form-group">                  
                    <label class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" v-model='sales_order.nama_dealer'>                    
                    </div>                                
                    <label class="col-sm-2 control-label">No SO</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" v-model='sales_order.id_sales_order'>                    
                    </div>      
                </div>
                <div class="form-group">                  
                    <label class="col-sm-2 control-label">Alamat Customer</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" v-model='sales_order.alamat'>                    
                    </div>                                
                    <label class="col-sm-2 control-label">Tanggal SO</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" :value='moment(sales_order.tanggal_order).format("DD/MM/YYYY")'>                    
                    </div>      
                </div>
                <div class="form-group">                             
                    <label class="col-sm-2 col-sm-offset-6 control-label">Expired BO</label>
                    <div class="col-sm-4">                    
                        <input type="text" readonly class="form-control" :value='moment(sales_order.batas_waktu).format("DD/MM/YYYY")'>                    
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
                            <th class='text-right'>HET</th>              
                            <th class='text-right'>Qty AVS</th>
                            <th class='text-right'>Qty SO</th>
                            <th class='text-right'>Qty Supplied</th>
                            <th class='text-right'>Qty BO</th>
                            <th class='text-right'>Amount</th>
                        </tr>
                        </thead>
                        <tbody>            
                        <tr v-for="(part, index) in parts"> 
                            <td class="align-top">{{ index + 1 }}.</td>                       
                            <td class="align-top">{{ part.id_part }}</td>                       
                            <td class="align-top">{{ part.nama_part }}</td>                       
                            <td class="align-top text-right">
                                <vue-numeric :read-only="true" class="form-control" thousand-separator="." :empty-value="1" currency='Rp' v-model="part.harga"/>
                            </td>
                            <td class="align-top text-right">
                                <vue-numeric :read-only="true" class="form-control" thousand-separator="." :empty-value="1" v-model="part.qty_avs"/>
                            </td>
                            <td class="align-top text-right">
                                <vue-numeric :read-only="true" class="form-control" thousand-separator="." :empty-value="1" v-model="part.qty_so"/>
                            </td>
                            <td class="align-top text-right">
                                <vue-numeric :read-only="true" class="form-control" thousand-separator="." :empty-value="1" v-model="part.qty_supply"/>
                            </td>
                            <td class="align-top text-right">
                                <vue-numeric :read-only="true" class="form-control" thousand-separator="." :empty-value="1" v-model="part.qty_bo"/>
                            </td>
                            <td class="align-top text-right">
                                <vue-numeric :read-only="true" class="form-control" thousand-separator="." :empty-value="1" v-model="amount(part)"/>
                            </td>
                        </tr>
                        <tr v-if="parts.length > 1">
                            <td class="text-right" colspan="8">Total</td>
                            <td class="text-right">
                            <vue-numeric :read-only="true" class="form-control" thousand-separator="." v-model="total" currency='Rp'/>
                            </td>
                        </tr>
                        </tbody>                    
                    </table>
                    </div>
                </div>                                                                                                                               
            </div><!-- /.box-body -->
            <div class="box-footer">
            <div class="col-sm-6 no-padding">
                <button v-if='sales_order.status == "Back Order"' @click.prevent='proses' type="submit" class="btn btn-success btn-sm btn-flat">Proses</button>                  
                <button v-if='sales_order.status == "Back Order"' @click.prevent='close' class="btn btn-danger btn-sm btn-flat">Close</button>                  
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
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            sales_order: <?= json_encode($sales_order) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            sales_order: {},
            parts: [],
            <?php endif; ?>
        },
        methods: {
            proses : function(status){
                this.loading = true;
                post = _.pick(this.sales_order, ['id_sales_order']);
                post.parts = _.map(this.parts, function(p){
                    return _.pick(p, ['id_part', 'qty_bo']);
                });

                axios.post("h3/h3_md_back_order/proses", Qs.stringify(post))
                .then(function(res){  
                    window.location = 'h3/h3_md_back_order/detail?id_sales_order=' + res.data.id_sales_order;
                })
                .catch(function(err){ toastr.error(err); })
                .then(function(){ app.loading = false; });
            },
            close: function(status){
                this.loading = true;
                post = {};
                post = _.pick(this.sales_order, ['id_sales_order']);

                axios.post("h3/h3_md_back_order/close", Qs.stringify(post))
                .then(function(res){  
                    window.location = 'h3/h3_md_back_order/detail?id_sales_order=' + res.data.id_sales_order;
                })
                .catch(function(err){ toastr.error(err); })
                .then(function(){ app.loading = false; });
            },
            amount: function(part){
                return parseFloat(part.harga) * parseFloat(part.qty_bo);
            }
        },
        computed: {
            total: function(){
                return _.sumBy(this.parts, function(p){
                    return p.harga * p.qty_bo;
                });
            },
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
            <div class="row">
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-12" id='filter_customer'>
                            <div class="form-group">
                                <label for="" class="control-label">Customer</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly :value='filters.length + " Customer"'>
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_back_order_index'><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->load->view('modal/h3_md_dealer_filter_back_order_index'); ?>
                        <script>
                        filter_customer = new Vue({
                            el: '#filter_customer',
                            data: {
                                filters: []
                            },
                            watch: {
                                filters: function(){
                                    back_order.draw();
                                }
                            }
                        });
                        $(document).ready(function(){
                            $("#h3_md_dealer_filter_back_order_index").on('change',"input[type='checkbox']",function(e){
                                target = $(e.target);
                                id_dealer = target.attr('data-id-dealer');

                                if(target.is(':checked')){
                                    filter_customer.filters.push(id_dealer);
                                }else{
                                    index_id_dealer = _.indexOf(filter_customer.filters, id_dealer);
                                    filter_customer.filters.splice(index_id_dealer, 1);
                                }
                                h3_md_dealer_filter_back_order_index_datatable.draw();
                            });
                        });
                        </script>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="" class="control-label">No. SO</label>
                                <input type="text" class="form-control" id='no_so_filter'>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#no_so_filter').on('keyup', _.debounce(function(){
                                    back_order.draw();
                                }, 500));
                            });
                        </script>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-12" id='filter_tipe_penjualan'>
                            <div class="form-group">
                                <label for="" class="control-label">Tipe Penjualan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly :value='filters.length + " Tipe Penjualan"'>
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_penjualan_filter_back_order_index'><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <?php $this->load->view('modal/h3_md_tipe_penjualan_filter_back_order_index'); ?>
                        </div>
                        <script>
                        filter_tipe_penjualan = new Vue({
                            el: '#filter_tipe_penjualan',
                            data: {
                                filters: []
                            },
                            watch: {
                                filters: function(){
                                    back_order.draw();
                                }
                            }
                        });
                        </script>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="" class="control-label">Periode Sales Order</label>
                                <input id='periode_sales_order_filter' type="text" class="form-control" readonly>
                                <input id='periode_sales_order_filter_start' type="hidden" disabled>
                                <input id='periode_sales_order_filter_end' type="hidden" disabled>
                            </div>
                        </div>
                    </div>
                    <script>
                        $('#periode_sales_order_filter').daterangepicker({
                            opens: 'left',
                            autoUpdateInput: false,
                            locale: {
                                format: 'DD/MM/YYYY'
                            }
                        }, function(start, end, label) {
                            $('#periode_sales_order_filter_start').val(start.format('YYYY-MM-DD'));
                            $('#periode_sales_order_filter_end').val(end.format('YYYY-MM-DD'));
                            back_order.draw();
                        }).on('apply.daterangepicker', function(ev, picker) {
                            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                        }).on('cancel.daterangepicker', function(ev, picker) {
                            $(this).val('');
                            $('#periode_sales_order_filter_start').val('');
                            $('#periode_sales_order_filter_end').val('');
                            back_order.draw();
                        });
                    </script>
                </div>
            </div>
        </div>
        <table id="back_order" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal SO</th>
              <th>No SO</th>
              <th>Kode Customer</th>
              <th>Nama Customer</th>
              <th>Alamat</th>
              <th>Tipe PO</th>
              <th>Amount</th>
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
        back_order = $('#back_order').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          order: [],
          ajax: {
              url: "<?= base_url('api/md/h3/back_order') ?>",
              dataSrc: "data",
              type: "POST",
              data: function(d){
                d.filter_customer = filter_customer.filters;
                d.filter_tipe_penjualan = filter_tipe_penjualan.filters;
                d.no_so_filter = $('#no_so_filter').val();
                d.periode_sales_order_filter_start = $('#periode_sales_order_filter_start').val();
                d.periode_sales_order_filter_end = $('#periode_sales_order_filter_end').val();
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
              { data: 'index', orderable: false, width: '3%' },
              { data: 'tanggal_so', name: 'so.tanggal_order' }, 
              { data: 'nomor_so', name: 'so.id_sales_order' }, 
              { data: 'kode_customer', name: 'd.kode_dealer_md' }, 
              { data: 'nama_dealer' }, 
              { data: 'alamat' },
              { data: 'po_type' }, 
              { 
                  data: 'amount_back_order', 
                  className: 'text-right',
                  render: function(data){
                      return accounting.formatMoney(data, 'Rp', 0, ".", ",");
                  }
              }, 
              { data: 'status' }, 
              { data: 'action', orderable: false, width: '3%' }
          ],
        });
      });
    </script>
    <?php endif; ?>
  </section>
</div>