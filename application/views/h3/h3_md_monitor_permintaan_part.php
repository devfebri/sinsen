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
              <div id='tipe_penjualan_filter' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Tipe PO</label>
                    <div class="input-group">
                      <input :value='filters.length + " Tipe PO"' type="text" class="form-control" readonly>
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
                        monitor_permintaan_part.draw();
                      }
                    }
                });
              </script>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Periode PO</label>
                    <input id='periode_purchase_order_filter' type="text" class="form-control" readonly>
                    <input id='periode_purchase_order_filter_start' type="hidden" disabled>
                    <input id='periode_purchase_order_filter_end' type="hidden" disabled>
                  </div>
                </div>
              </div>
              <script>
                $('#periode_purchase_order_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }, function(start, end, label) {
                  $('#periode_purchase_order_filter_start').val(start.format('YYYY-MM-DD'));
                  $('#periode_purchase_order_filter_end').val(end.format('YYYY-MM-DD'));
                  monitor_permintaan_part.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_purchase_order_filter_start').val('');
                  $('#periode_purchase_order_filter_end').val('');
                  monitor_permintaan_part.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div id='filter_purchase_order' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. PO</label>
                    <div class="input-group">
                      <input :value='filters.length + " PO"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_purchase_filter_monitor_permintaan_part_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_purchase_filter_monitor_permintaan_part_index'); ?>
              <script>
                filter_purchase_order = new Vue({
                  el: '#filter_purchase_order',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      monitor_permintaan_part.draw();
                    }
                  }
                });

                $("#h3_md_purchase_filter_monitor_permintaan_part_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  po_id = target.attr('data-po-id');

                  if(target.is(':checked')){
                    filter_purchase_order.filters.push(po_id);
                  }else{
                    index_po_id = _.indexOf(filter_purchase_order.filters, po_id);
                    filter_purchase_order.filters.splice(index_po_id, 1);
                  }
                  h3_md_purchase_filter_monitor_permintaan_part_index_datatable.draw();
                });
              </script>
              <div id='status_filter' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Status</label>
                    <div class="input-group">
                      <input :value='filters.length + " Status"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_status_filter_monitoring_permintaan_part_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_status_filter_monitoring_permintaan_part_index'); ?>
              </div>
              <script>
                  status_filter = new Vue({
                      el: '#status_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          monitor_permintaan_part.draw();
                        }
                      }
                  });
              </script>
            </div>
            <div class="col-sm-3">
              <div id='filter_customer' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Dealer</label>
                    <div class="input-group">
                      <input :value='filters.length + " Customer"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_monitor_permintaan_part_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_dealer_filter_monitor_permintaan_part_index'); ?>
              <script>
                filter_customer = new Vue({
                  el: '#filter_customer',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      monitor_permintaan_part.draw();
                    }
                  }
                });

                $("#h3_md_dealer_filter_monitor_permintaan_part_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_dealer = target.attr('data-id-dealer');

                  if(target.is(':checked')){
                    filter_customer.filters.push(id_dealer);
                  }else{
                    index_dealer = _.indexOf(filter_customer.filters, id_dealer);
                    filter_customer.filters.splice(index_dealer, 1);
                  }
                  h3_md_dealer_filter_monitor_permintaan_part_index_datatable.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div id='salesman_filter' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Salesman</label>
                    <div class="input-group">
                      <input :value='filters.length + " Salesman"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_salesman_filter_monitoring_permintaan_part_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_salesman_filter_monitoring_permintaan_part_index'); ?>
              <script>
                  salesman_filter = new Vue({
                      el: '#salesman_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          monitor_permintaan_part.draw();
                        }
                      }
                  });

                  $("#h3_md_salesman_filter_sales_order_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_salesman = target.attr('data-id-salesman');

                    if(target.is(':checked')){
                      salesman_filter.filters.push(id_salesman);
                    }else{
                      index_salesman = _.indexOf(salesman_filter.filters, id_salesman);
                      salesman_filter.filters.splice(index_salesman, 1);
                    }
                    h3_md_salesman_filter_monitoring_permintaan_part_index_datatable.draw();
                  });
              </script>
            </div>
          </div>
        </div>
        <table id="monitor_permintaan_part" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Nama Salesman</th>              
              <th>Tipe PO</th>              
              <th>Tanggal Order</th>              
              <th>No. PO</th>              
              <th>Sales Order</th>
              <th>Nama Dealer</th>              
              <th>Alamat</th>              
              <th>Total Amount</th>              
              <th>Status</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            monitor_permintaan_part = $('#monitor_permintaan_part').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                scrollX: true,
                searching: false,
                ajax: {
                    url: "<?= base_url('api/md/h3/monitor_permintaan_part') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                      d.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                      d.periode_purchase_order_filter_start = $('#periode_purchase_order_filter_start').val();
                      d.periode_purchase_order_filter_end = $('#periode_purchase_order_filter_end').val();
                      d.filter_purchase_order = filter_purchase_order.filters;
                      d.filter_customer = filter_customer.filters;
                      d.salesman_filter = salesman_filter.filters;
                      d.status_filter = status_filter.filters;
                      d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    }
                },
                columns: [
                    { data: null, orderable: false, width: '3%' }, 
                    { data: 'nama_salesman' },
                    { data: 'po_type' },
                    { data: 'tanggal_order' },
                    { data: 'po_id' },
                    { data: 'view_sales_order', orderable: false, className: 'text-center' },
                    { data: 'nama_dealer' },
                    { data: 'alamat' },
                    { data: 'total_amount', className: 'text-right' },
                    { data: 'status' },
                ],
            });
            
            monitor_permintaan_part.on('draw.dt', function() {
              var info = monitor_permintaan_part.page.info();
              monitor_permintaan_part.column(0, {
                  search: 'applied',
                  order: 'applied',
                  page: 'applied'
              }).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1 + info.start + ".";
              });
            });
          });
        </script>
        <?php $this->load->view('modal/h3_md_view_modal_purchase_order'); ?>
        <script>
        function view_modal_purchase_order(po_id) {
          url = 'iframe/dealer/h3/h3_dealer_purchase_order?po_id=' + po_id;
          $('#view_iframe_purchase_order').attr('src', url);
          $('#h3_md_view_modal_purchase_order').modal('show');
        }
        </script>
        <?php $this->load->view('modal/h3_md_view_sales_order_monitoring_permintaan_part'); ?>
        <script>
          function open_view_sales_order(po_id){
            $('#po_id_monitoring_permintaan_part').val(po_id);
            $('#h3_md_view_sales_order_monitoring_permintaan_part').modal('show');
            h3_md_view_sales_order_monitoring_permintaan_part_datatable.draw();
          }
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>