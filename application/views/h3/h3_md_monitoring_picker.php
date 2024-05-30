<base href="<?php echo base_url(); ?>" /> 
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
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <div class="box" id="picking_list">
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
      </div>
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message') ?>
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <label for="" class="control-label">Filter :</label>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="row">
                <div id='filter_picker' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Picker</label>
                    <div class="input-group">
                      <input :value='filters.length + " Picker"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_picker_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_picker_filter_monitoring_kerja_picker_index'); ?>
                <script>
                  filter_picker = new Vue({
                    el: '#filter_picker',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });

                  $("#h3_md_picker_filter_monitoring_kerja_picker_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_karyawan = target.attr('data-id-karyawan');

                    if(target.is(':checked')){
                      filter_picker.filters.push(id_karyawan);
                    }else{
                      index_picker = _.indexOf(filter_picker.filters, id_karyawan);
                      filter_picker.filters.splice(index_picker, 1);
                    }
                    h3_md_picker_filter_monitoring_kerja_picker_index_datatable.draw();
                  });
                </script>
              </div>
              <div class="row">
                <div id='filter_customer' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input :value='filters.length + " Customer"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_dealer_filter_monitoring_kerja_picker_index'); ?>
                <script>
                  filter_customer = new Vue({
                    el: '#filter_customer',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });

                  $("#h3_md_dealer_filter_monitoring_kerja_picker_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_dealer = target.attr('data-id-dealer');

                    if(target.is(':checked')){
                      filter_customer.filters.push(id_dealer);
                    }else{
                      index_dealer = _.indexOf(filter_customer.filters, id_dealer);
                      filter_customer.filters.splice(index_dealer, 1);
                    }
                    // h3_md_dealer_filter_monitoring_kerja_picker_index_datatable.draw();
                  });
                </script>
              </div>
              <div class="row">
                <div id='filter_kabupaten' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Filter Kabupaten</label>
                    <div class="input-group">
                      <input :value='filters.length + " Kabupaten"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kabupaten_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_kabupaten_filter_monitoring_kerja_picker_index'); ?>
                <script>
                  filter_kabupaten = new Vue({
                    el: '#filter_kabupaten',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });

                  $("#h3_md_kabupaten_filter_monitoring_kerja_picker_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_kabupaten = target.attr('data-id-kabupaten');

                    if(target.is(':checked')){
                      filter_kabupaten.filters.push(id_kabupaten);
                    }else{
                      index_kabupaten = _.indexOf(filter_kabupaten.filters, id_kabupaten);
                      filter_kabupaten.filters.splice(index_kabupaten, 1);
                    }
                    // h3_md_kabupaten_filter_monitoring_kerja_picker_index_datatable.draw();
                    drawing_kabupaten();
                  });
                </script>
              </div>
              <div class="row">
                <div id='filter_tipe_customer' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Filter Tipe Customer</label>
                    <div class="input-group">
                      <input :value='filters.length + " Tipe Customer"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_customer_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                <?php $this->load->view('modal/h3_md_tipe_customer_filter_monitoring_kerja_picker_index'); ?>
                </div>
                <script>
                  filter_tipe_customer = new Vue({
                    el: '#filter_tipe_customer',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });
                </script>
              </div>
              <div class="row">
                <div id='filter_status' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Filter Status</label>
                    <div class="input-group">
                      <input :value='filters.length + " Status"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_status_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                <?php $this->load->view('modal/h3_md_status_filter_monitoring_kerja_picker_index'); ?>
                </div>
                <script>
                  filter_status = new Vue({
                    el: '#filter_status',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });
                </script>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Tanggal Sales Order</label>
                    <input id='periode_so_filter' type="text" class="form-control" readonly>
                    <input id='periode_so_filter_start' type="hidden" disabled>
                    <input id='periode_so_filter_end' type="hidden" disabled>
                  </div>
                </div>
                <script>
                  $('#periode_so_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#periode_so_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#periode_so_filter_end').val(end.format('YYYY-MM-DD'));
                    monitoring_picker.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_so_filter_start').val('');
                    $('#periode_so_filter_end').val('');
                    monitoring_picker.draw();
                  });
                </script>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Tanggal Delivery Order</label>
                    <input id='periode_do_filter' type="text" class="form-control" readonly>
                    <input id='periode_do_filter_start' type="hidden" disabled>
                    <input id='periode_do_filter_end' type="hidden" disabled>
                  </div>
                </div>
                <script>
                  $('#periode_do_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#periode_do_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#periode_do_filter_end').val(end.format('YYYY-MM-DD'));
                    monitoring_picker.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_do_filter_start').val('');
                    $('#periode_do_filter_end').val('');
                    monitoring_picker.draw();
                  });
                </script>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Tanggal Picking List</label>
                    <input id='periode_picking_list_filter' type="text" class="form-control" readonly>
                    <input id='periode_picking_list_filter_start' type="hidden" disabled>
                    <input id='periode_picking_list_filter_end' type="hidden" disabled>
                  </div>
                </div>
                <script>
                  $('#periode_picking_list_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#periode_picking_list_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#periode_picking_list_filter_end').val(end.format('YYYY-MM-DD'));
                    monitoring_picker.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_picking_list_filter_start').val('');
                    $('#periode_picking_list_filter_end').val('');
                    monitoring_picker.draw();
                  });
                </script>
              </div>
              <div class="row">
                <div id='filter_jenis_po' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Filter Jenis PO</label>
                    <div class="input-group">
                      <input :value='filters.length + " Jenis PO"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_jenis_po_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                <?php $this->load->view('modal/h3_md_jenis_po_filter_monitoring_kerja_picker_index'); ?>
                </div>
                <script>
                  filter_jenis_po = new Vue({
                    el: '#filter_jenis_po',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });
                </script>
              </div>
            </div>
            <div class="col-sm-4">
              <!-- <div class="row">
                <div id='filter_sales_order' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Search No. SO</label>
                    <div class="input-group">
                      <input :value='filters.length + " Sales Order"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_so_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                ?php $this->load->view('modal/h3_md_so_filter_monitoring_kerja_picker_index'); ?>
                <script>
                  filter_sales_order = new Vue({
                    el: '#filter_sales_order',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });

                  $("#h3_md_so_filter_monitoring_kerja_picker_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_sales_order = target.attr('data-id-sales-order');

                    if(target.is(':checked')){
                      filter_sales_order.filters.push(id_sales_order);
                    }else{
                      index_sales_order = _.indexOf(filter_sales_order.filters, id_sales_order);
                      filter_sales_order.filters.splice(index_sales_order, 1);
                    }
                    h3_md_so_filter_monitoring_kerja_picker_index_datatable.draw();
                  });
                </script>
              </div> -->
              <!-- <div class="row">
                <div id='filter_delivery_order' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Search No. DO</label>
                    <div class="input-group">
                      <input :value='filters.length + " Delivery Order"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_do_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                ?php $this->load->view('modal/h3_md_do_filter_monitoring_kerja_picker_index'); ?>
                <script>
                  filter_delivery_order = new Vue({
                    el: '#filter_delivery_order',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });

                  $("#h3_md_do_filter_monitoring_kerja_picker_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_do_sales_order = target.attr('data-id-do-sales-order');

                    if(target.is(':checked')){
                      filter_delivery_order.filters.push(id_do_sales_order);
                    }else{
                      index_do_sales_order = _.indexOf(filter_delivery_order.filters, id_do_sales_order);
                      filter_delivery_order.filters.splice(index_do_sales_order, 1);
                    }
                    h3_md_do_filter_monitoring_kerja_picker_index_datatable.draw();
                  });
                </script>
              </div> -->
              <div class="row">
                <div id='filter_picking_list' class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Search No. PL</label>
                    <div class="input-group">
                      <input :value='filters.length + " Picking List"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_picking_list_filter_monitoring_kerja_picker_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_picking_list_filter_monitoring_kerja_picker_index'); ?>
                <script>
                  filter_picking_list = new Vue({
                    el: '#filter_picking_list',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        monitoring_picker.draw();
                      }
                    }
                  });

                  $("#h3_md_picking_list_filter_monitoring_kerja_picker_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_picking_list = target.attr('data-id-picking-list');

                    if(target.is(':checked')){
                      filter_picking_list.filters.push(id_picking_list);
                    }else{
                      index_picking_list = _.indexOf(filter_picking_list.filters, id_picking_list);
                      filter_picking_list.filters.splice(index_picking_list, 1);
                    }
                    // h3_md_picking_list_filter_monitoring_kerja_picker_index_datatable.draw();
                    // drawing_pl_filter();
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
        <table id='monitoring_picker' class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tgl SO</th>
              <th>No. SO</th>
              <th>Tgl DO</th>
              <th>No. DO</th>
              <th>Tgl Picking List</th>              
              <th>No Picking List</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <!-- <th>Kabupaten</th>               -->
              <th>Amount DO</th>              
              <!-- <th>Item DO</th>               -->
              <!-- <th>Pcs DO</th>               -->
              <th>Amount PL</th>              
              <!-- <th>Item PL</th>               -->
              <!-- <th>Pcs PL</th>   -->
              <th>S/R</th>  
              <th>Nama Picker</th>  
              <th>Status</th>  
              <th width="10%"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function(){
            monitoring_picker = $('#monitoring_picker').DataTable({
              processing: true,
              serverSide: true,
              order: [],
              searching: false,
              scrollX: true,
              "iDisplayLength": 5, 
              ajax: {
                  url: "<?= base_url('api/md/h3/monitoring_picker') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.filter_picker = filter_picker.filters;
                    d.filter_customer = filter_customer.filters;
                    d.filter_kabupaten = filter_kabupaten.filters;
                    d.filter_tipe_customer = filter_tipe_customer.filters;
                    d.filter_status = filter_status.filters;
                    d.filter_jenis_po = filter_jenis_po.filters;
                    // d.filter_sales_order = filter_sales_order.filters;
                    // d.filter_delivery_order = filter_delivery_order.filters;
                    d.filter_picking_list = filter_picking_list.filters;
                    d.periode_so_filter_start = $('#periode_so_filter_start').val();
                    d.periode_so_filter_end = $('#periode_so_filter_end').val();
                    d.periode_do_filter_start = $('#periode_do_filter_start').val();
                    d.periode_do_filter_end = $('#periode_do_filter_end').val();
                    d.periode_picking_list_filter_start = $('#periode_picking_list_filter_start').val();
                    d.periode_picking_list_filter_end = $('#periode_picking_list_filter_end').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
              },
              columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'tanggal_so' },
                { data: 'id_sales_order', width: '180px' },
                { data: 'tanggal_do' },
                { data: 'id_do_sales_order', width: '180px' },
                { data: 'tanggal_picking' },
                { data: 'id_picking_list', width: '180px' },
                { data: 'kode_dealer_md' },
                { data: 'nama_dealer', width: '200px' },
                // { data: 'kabupaten' },
                { data: 'amount_do', width: '70px', className: 'text-right' },
                // { data: 'total_item_do', className: 'text-right' },
                // { data: 'total_pcs_do', className: 'text-right' },
                { data: 'amount_pl', width: '70px', className: 'text-right' },
                // { data: 'total_item_pl' },
                // { data: 'total_pcs_pl' },
                { data: 'service_rate', width: '50px' },
                { data: 'nama_picker' },
                { data: 'status' },
                { data: 'action', width: '3%', orderable:false }
              ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </section>
</div>
<?php $this->load->view('modal/h3_md_modal_view_picking_list'); ?>
<script>
  function open_view_modal_picking_list(id_picking_list) {
    $('#h3_md_modal_view_picking_list').modal('show');
    h3_md_modal_view_picking_list_vue.picking.id_picking_list = id_picking_list;
    h3_md_modal_view_picking_list_vue.get_view_picking_list_data();
  }
</script>
<?php $this->load->view('modal/h3_md_view_modal_do_sales_order'); ?>
<script>
function view_modal_do_sales_order(id_do_sales_order) {
  url = 'iframe/md/h3/h3_md_do_sales_order?id=' + id_do_sales_order;
  $('#view_iframe_do_sales_order').attr('src', url);
  $('#h3_md_view_modal_do_sales_order').modal('show');
}
</script>
<?php $this->load->view('modal/h3_md_view_modal_sales_order'); ?>
<script>
function view_modal_sales_order(id_sales_order) {
  url = 'iframe/md/h3/h3_md_sales_order?id_sales_order=' + id_sales_order;
  $('#view_iframe_sales_order').attr('src', url);
  $('#h3_md_view_modal_sales_order').modal('show');
}
</script>