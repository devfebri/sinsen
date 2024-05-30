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
                <input type="text" readonly class="form-control" v-model='do_sales_order.tanggal_do'>                    
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
                <input type="text" readonly class="form-control" v-model='do_sales_order.kode_dealer'>                    
                </div>      
            </div>      
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Tanggal SO</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_sales_order.tanggal_so'>                    
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
                <input type="text" readonly class="form-control">                    
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">TOP</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control">                    
                </div>                                
                <label class="col-sm-2 control-label">Sisa Plafon</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control">                    
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Name Salesman</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control">                    
                </div>                                
                <label class="col-sm-2 control-label">Plafon Booking</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control">                    
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
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_sales_order.status'>                    
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
                        <th>Harga (DPP)</th>              
                        <th>Qty Supply</th>
                        <th>Diskon Satuan Dealer</th>
                        <th>Diskon Campaign</th>
                        <th>Harga Setelah Diskon</th>
                        <th>Amount</th>
                        <th>Harga Beli</th>
                        <th>Selisih</th>
                    </tr>
                    </thead>
                    <tbody>            
                    <tr v-for="(part, index) in parts"> 
                        <td class="align-top">{{ index + 1 }}.</td>                       
                        <td class="align-top">{{ part.id_part }}</td>                       
                        <td class="align-top">{{ part.nama_part }}</td>                       
                        <td class="align-top">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="hitung_dpp(part)"/>
                        </td>
                        <td class="align-top">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_supply"/>
                        </td>
                        <td class="align-top">
                        <select disabled class="form-control" v-model='part.tipe_diskon_satuan_dealer' style='margin-bottom: 10px;'>
                            <option value="">-No Disc-</option>
                            <option value="Percentage">Percentage</option>
                            <option value="Value">Value</option>
                        </select>
                        <vue-numeric disabled v-if='part.tipe_diskon_satuan_dealer != ""' class="form-control" separator="." :empty-value="1" v-model="part.diskon_satuan_dealer"/>
                        </td> 
                        <td class="align-top">
                        <select disabled class="form-control" v-model='part.tipe_diskon_campaign' style='margin-bottom: 10px;'>
                            <option value="">-No Disc-</option>
                            <option value="Percentage">Percentage</option>
                            <option value="Value">Value</option>
                        </select>
                        <vue-numeric disabled v-if='part.tipe_diskon_campaign != ""' class="form-control" separator="." :empty-value="1" v-model="part.diskon_campaign"/>
                        </td> 
                        <td class="align-top">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part)"/>
                        </td>   
                        <td class="align-top">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="amount(part)"/>
                        </td>  
                        <td class="align-top">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga"/>
                        </td> 
                        <td class="align-top">
                        <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga_beli"/>
                        </td>    
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td class="text-right" colspan="8">Sub Total</td>
                        <td class="text-right" colspan='3'>
                        <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td colspan='2' class='align-middle'>Total Insentif</td>
                        <td class='align-middle'>
                        <vue-numeric :read-only="true" class="form-control" separator="."/>
                        </td>
                        <td colspan='3'></td>
                        <td class='text-right align-middle'>
                        <input type="checkbox" true-value='1' false-value='0' v-model='do_sales_order.check_diskon_insentif'>
                        </td>
                        <td class="text-right align-middle" colspan="1">Diskon Insentif</td>
                        <td class="text-right align-middle" colspan='3'>
                        <vue-numeric :read-only="do_sales_order.check_diskon_insentif == 0" class="form-control" separator="." v-model="do_sales_order.diskon_insentif" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td colspan='6'></td>
                        <td class='text-right align-middle'>
                        <input type="checkbox" true-value='1' false-value='0' v-model='do_sales_order.check_diskon_cashback'>
                        </td>
                        <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                        <td class="text-right align-middle" colspan='3'>
                        <vue-numeric :read-only="do_sales_order.check_diskon_cashback == 0" class="form-control" separator="." v-model="do_sales_order.diskon_cashback" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td colspan='7'></td>
                        <td class="text-right align-middle" colspan="1">Total Diskon</td>
                        <td class="text-right align-middle" colspan='3'>
                        <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_diskon" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td colspan='7'></td>
                        <td class="text-right align-middle" colspan="1">Total PPN</td>
                        <td class="text-right align-middle" colspan='3'>
                        <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_ppn" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
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
            </div><!-- /.box-body -->
            <div class="box-footer">
            <div class="col-sm-6 no-padding">
                <!-- <a :href="'h3/h3_md_create_faktur/cetak?id=' + do_sales_order.id_do_sales_order" class="btn btn-info btn-sm btn-flat">Create Faktur</a> -->
                <button v-if='do_sales_order.status == "Draft"' @click.prevent='approve' type="submit" class="btn btn-success btn-sm btn-flat">Approve</button>                  
                <button v-if='do_sales_order.status == "Draft"' data-toggle='modal' data-target='#reject_modal' type="button" class="btn btn-danger btn-sm btn-flat">Reject</button>                  
                <div id="reject_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                            </div>
                            <div class="modal-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                <textarea class="form-control" id="alasan_reject"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        do_sales_order: <?= json_encode($do_sales_order) ?>,
        parts: <?= json_encode($do_sales_order_parts) ?>,
        <?php else: ?>
        do_sales_order: {},
        parts: [],
        <?php endif; ?>
        },
        methods: {
        approve: function(status){
            this.loading = true;
            post = {};
            post = _.pick(this.do_sales_order, ['id_do_sales_order', 'check_diskon_insentif', 'diskon_insentif', 'check_diskon_cashback', 'diskon_cashback', 'id_dealer']);
            post.total = this.total;
            post.parts = _.map(this.parts, function(part){
            return _.pick(part, ['id_part', 'qty_supply']);
            });

            axios.post("h3/h3_md_do_sales_order/approve", Qs.stringify(post))
            .then(function(res){  
            window.location = 'h3/h3_md_do_sales_order/detail?id=' + res.data.id_do_sales_order;
            })
            .catch(function(err){ toastr.error(err); })
            .then(function(){ app.loading = false; });
        },
        reject: function(status){
            this.loading = true;
            post = {};
            post.id_do_sales_order = this.do_sales_order.id_do_sales_order;
            post.alasan_reject = $('#alasan_reject').val();
            post.total = this.total;
            axios.post("h3/h3_md_do_sales_order/reject", Qs.stringify(post))
            .then(function(res){  
            window.location = 'h3/h3_md_do_sales_order/detail?id=' + res.data.id_do_sales_order;
            })
            .catch(function(err){ toastr.error(err); })
            .then(function(){ app.loading = false; });
        },
        hitung_dpp: function(part){
            if(part.include_ppn == 1){
            return part.harga/1.1;
            }
            return part.harga;
        },
        harga_setelah_diskon: function(part){
            return this.hitung_dpp(part) -
            this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, part.harga) - 
            this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga);
        },
        calculate_discount: function(discount, tipe_diskon, price) {
            if(tipe_diskon == 'Percentage'){
            if(discount == 0) return 0; 

            return discount = (discount/100) * price;
            }else if(tipe_diskon == 'Value'){
            return discount;
            }
            return 0;
        },
        amount: function(part) {
            return this.harga_setelah_diskon(part) * part.qty_supply
        },
        },
        computed: {
        sub_total: function(){
            total = 0;
            for (index = 0; index < this.parts.length; index++) {
            part = this.parts[index];
            total += this.amount(part);
            }
            return total;
        },
        total_diskon: function(){
            return this.do_sales_order.diskon_insentif + this.do_sales_order.diskon_cashback;
        },
        total_ppn: function(){
            return 0.1 * this.sub_total;
        },
        total: function(){
            return this.total_ppn + this.sub_total;
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="" class="control-label">No. DO</label>
                                <input id='no_do_filter' type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('#no_do_filter').on('keyup', function(){
                                hutang_do.draw();
                            });
                        })
                    </script>
                </div>
                <div class="col-sm-3">
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
                        hutang_do.draw();
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#periode_filter_start').val('');
                        $('#periode_filter_end').val('');
                        hutang_do.draw();
                    });
                    </script>
                </div>
                <div class="col-sm-3">
                    <div id='filter_customer' class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="" class="control-label">Kode Customer</label>
                                <div class="input-group">
                                    <input :value='filters.length + " Customer"' type="text" class="form-control" readonly>
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_monitoring_picking_index'><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_filter_monitoring_picking_index'); ?>
                    <script>
                        filter_customer = new Vue({
                            el: '#filter_customer',
                            data: {
                                filters: []
                            },
                            watch: {
                                filters: function(){
                                    hutang_do.draw();
                                }
                            }
                        });

                        $("#h3_md_dealer_filter_monitoring_picking_index").on('change',"input[type='checkbox']",function(e){
                            target = $(e.target);
                            id_dealer = target.attr('data-id-dealer');

                            if(target.is(':checked')){
                                filter_customer.filters.push(id_dealer);
                            }else{
                                index_dealer = _.indexOf(filter_customer.filters, id_dealer);
                                filter_customer.filters.splice(index_dealer, 1);
                            }
                            h3_md_dealer_filter_monitoring_picking_index_datatable.draw();
                        });
                    </script>
                </div>
                <div class="col-sm-3">
                    <div id='tipe_penjualan_filter' class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="" class="control-label">Tipe Penjualan</label>
                                <div class="input-group">
                                    <input :value='filters.length + " Tipe Penjualan"' type="text" class="form-control" readonly>
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_penjualan_filter_sales_order_index'><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $this->load->view('modal/h3_md_tipe_penjualan_filter_sales_order_index'); ?>
                    </div>
                    <script>
                        tipe_penjualan_filter = new Vue({
                            el: '#tipe_penjualan_filter',
                            data: {
                                filters: []
                            },
                            watch: {
                                filters: function(){
                                    hutang_do.draw();
                                }
                            }
                        })
                    </script>
                </div>
            </div>
        </div>
        <table id="hutang_do" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>No PO Dealer</th>              
              <th>Type PO</th>              
              <th>Tgl DO</th>              
              <th>Masa Berlaku</th>              
              <th>Nomor Part</th>
              <th>Nama Part</th>
              <th>HET</th>
              <th>Qty Order DO</th>
              <th>Total Amount Order DO</th>
              <th>Qty Supply</th>
              <th>Total Amount Supply</th>
              <th>Qty Sisa DO</th>
              <th>Total Amount Sisa DO</th>
              <th>Status PO</th>
              <!-- <th width="10%">Action</th> -->
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function(){
        hutang_do = $('#hutang_do').DataTable({
          processing: true,
          serverSide: true,
          order: [],
          scrollX: true,
          ajax: {
              url: "<?= base_url('api/md/h3/hutang_do') ?>",
              dataSrc: "data",
              type: "POST",
              data: function(d){
                  d.no_do_filter = $('#no_do_filter').val();
                  d.filter_customer = filter_customer.filters;
                  d.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                  d.periode_filter_start = $('#periode_filter_start').val();
                  d.periode_filter_end = $('#periode_filter_end').val();
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'po_id' }, 
                { data: 'po_type' }, 
                { data: 'tanggal_do' }, 
                { data: 'masa_berlaku' }, 
                { data: 'id_part' }, 
                { data: 'nama_part', width: '200px' }, 
                { 
                  data: 'harga_jual', 
                  width: '50px', 
                  className: 'text-right',
                  render: function(data){
                      return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                  }
                }, 
                { 
                  data: 'qty_do',
                  render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                  },
                  className: 'text-right'
                }, 
                { 
                    data: 'amount_do',
                    render: function(data){
                      return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                    },
                    width: '80px',
                    className: 'text-right'
                }, 
                { 
                    data: 'qty_supply',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    className: 'text-right'
                }, 
                { 
                    data: 'amount_supply',
                    render: function(data){
                      return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                    },
                    width: '80px',
                    className: 'text-right'
                }, 
                { 
                    data: 'qty_sisa_do',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    },
                    className: 'text-right'
                }, 
                { 
                    data: 'amount_sisa_do',
                    render: function(data){
                      return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                    },
                    width: '80px',
                    className: 'text-right'
                }, 
                { data: 'status', width: '80px' }, 
          ],
        });

        hutang_do.on('draw.dt', function() {
          var info = hutang_do.page.info();
          hutang_do.column(0, {
              search: 'applied',
              order: 'applied',
              page: 'applied'
          }).nodes().each(function(cell, i) {
              cell.innerHTML = i + 1 + info.start + ".";
          });
        });
      });
    </script>
    <?php endif; ?>
  </section>
</div>