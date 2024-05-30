
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script> 
<script src="<?= base_url("assets/panel/humanize-duration.js") ?>" type="text/javascript"></script>
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
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kode Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='kode_customer_filter' type="text" class="form-control" disabled>
                      <input type="hidden" id="id_customer_filter">
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" id='filter_customer_modal' type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_monitoring_supply'><i class="fa fa-search"></i></button>
                        <button class="btn btn-flat btn-danger hidden" id='reset_filter_customer'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_monitoring_supply'); ?>         
                <script>
                function pilih_dealer_filter_monitoring_supply(data) {
                  $('#nama_customer_filter').val(data.nama_dealer);
                  $('#kode_customer_filter').val(data.kode_dealer_md);
                  $('#id_customer_filter').val(data.id_dealer);
                  $('#kabupaten_customer_filter').val(data.kabupaten);

                  $('#filter_customer_modal').addClass('hidden');
                  $('#reset_filter_customer').removeClass('hidden');

                  monitoring_supply.draw();
                  h3_md_dealer_filter_monitoring_supply_datatable.draw();
                }

                $(document).ready(function(){
                  $('#reset_filter_customer').click(function(e){
                    e.preventDefault();

                    $('#nama_customer_filter').val('');
                    $('#kode_customer_filter').val('');
                    $('#id_customer_filter').val('');
                    $('#kabupaten_customer_filter').val('');

                    $('#filter_customer_modal').removeClass('hidden');
                    $('#reset_filter_customer').addClass('hidden');

                    monitoring_supply.draw();
                    h3_md_dealer_filter_monitoring_supply_datatable.draw();
                  })
                });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Salesman</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_salesman_filter' type="text" class="form-control" disabled>
                      <input id='id_salesman_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_salesman_filter_monitoring_supply'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>                
                <?php $this->load->view('modal/h3_md_salesman_filter_monitoring_supply'); ?>         
                <script>
                function pilih_salesman_filter_monitoring_supply(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_salesman_filter').val(data.nama_lengkap);
                    $('#id_salesman_filter').val(data.id_karyawan);
                  }else if(type == 'reset_filter'){
                    $('#nama_salesman_filter').val('');
                    $('#id_salesman_filter').val('');
                  }
                  monitoring_supply.draw();
                  h3_md_salesman_filter_monitoring_supply_datatable.draw();
                }
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Customer</label>
                  <div class="col-sm-8">
                    <input id='nama_customer_filter' type="text" class="form-control" disabled>
                  </div>
                </div>
              </div>
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
                      monitoring_supply.draw();
                    }, 500));
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kabupaten Customer</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="kabupaten_customer_filter" readonly>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 no-padding-x">Periode Sales</label>
                  <div class="col-sm-8">
                    <input id='periode_po_filter' type="text" class="form-control" readonly>
                    <input id='periode_po_filter_start' type="hidden" disabled>
                    <input id='periode_po_filter_end' type="hidden" disabled>
                  </div>
                </div>                
                <script>
                  $('#periode_po_filter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#periode_po_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#periode_po_filter_end').val(end.format('YYYY-MM-DD'));
                    monitoring_supply.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_po_filter_start').val('');
                    $('#periode_po_filter_end').val('');
                    monitoring_supply.draw();
                  });
                </script>
              </div>
            </div>
          </form>
        </div>
        <table id="monitoring_supply" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No</th>              
              <th>No. SO</th>              
              <th>Nama Customer</th>              
              <th>Kode Customer</th>              
              <th>Kabupaten</th>              
              <th>Amount</th>              
              <th>Amount Supply</th>              
              <th>S/R</th>              
              <th>No. DO</th>              
              <th>Status SO</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function(){
            monitoring_supply = $('#monitoring_supply').DataTable({
              processing: true,
              serverSide: true,
              searching: false,
              ordering: false,
              scrollX: true,
              order: [],
              ajax: {
                  url: "<?= base_url('api/md/h3/monitoring_supply') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.id_customer_filter = $('#id_customer_filter').val();
                    d.alamat_customer_filter = $('#alamat_customer_filter').val();
                    d.id_salesman_filter = $('#id_salesman_filter').val();
                    d.no_so_filter = $('#no_so_filter').val();
                    d.periode_po_filter_start = $('#periode_po_filter_start').val();
                    d.periode_po_filter_end = $('#periode_po_filter_end').val();
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
              },
              columns: [
                  { data: 'index', orderable: false, width: '3%' }, 
                  { data: 'id_sales_order' }, 
                  { data: 'nama_dealer' }, 
                  { data: 'kode_dealer_md' }, 
                  { data: 'kabupaten' }, 
                  { 
                    data: 'total_amount',
                    render: function(data){
                      return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                    },
                    orderable: false
                  }, 
                  { 
                    data: 'amount_supply',
                    render: function(data){
                      return accounting.formatMoney(data, 'Rp ', 0, ".", ",");
                    },
                    orderable: false
                  }, 
                  { 
                    data: 'service_rate',
                    orderable: false,
                  }, 
                  { data: 'list_do', width: '8%', className: 'text-center' }, 
                  { data: 'status' }
              ],
            });
          });
        </script>
        <input type="hidden" id="selected_id_sales_order">
        <?php $this->load->view('modal/h3_md_list_do_monitoring_supply'); ?>
        <script>
          function open_list_do(id_sales_order){
            $('#selected_id_sales_order').val(id_sales_order);
            $('#h3_md_list_do_monitoring_supply').modal('show');
            h3_md_list_do_monitoring_supply_datatable.draw();
          }
        </script>
        <?php $this->load->view('modal/h3_md_view_modal_sales_order'); ?>
        <script>
        function view_modal_sales_order(id_sales_order) {
          url = 'iframe/md/h3/h3_md_sales_order?id_sales_order=' + id_sales_order + '&dengan_kuantitas_do_dan_revisi=true';
          $('#view_iframe_sales_order').attr('src', url);
          $('#h3_md_view_modal_sales_order').modal('show');
        }
        </script>
        <!-- Baru Sampai sini -->
        <?php $this->load->view('modal/h3_md_rincian_proses_monitoring_supply'); ?>
        <script>
          function open_rincian_proses_monitoring_supply(id_do_sales_order) {
            url = 'iframe/md/h3/h3_md_create_do?id_do_sales_order=' + id_do_sales_order;
            $('#view_iframe_do').attr('src', url);
            $('#h3_md_view_modal_do').modal('show');
          }
        </script>
        <?php $this->load->view('modal/h3_md_rincian_picking_monitoring_supply'); ?>
        <?php $this->load->view('modal/h3_md_rincian_scan_monitoring_supply'); ?>
        <?php $this->load->view('modal/h3_md_rincian_faktur_monitoring_supply'); ?>
        <?php $this->load->view('modal/h3_md_rincian_packing_monitoring_supply'); ?>
        <?php $this->load->view('modal/h3_md_rincian_shipping_monitoring_supply'); ?>
        <input type="hidden" id="id_rincian_po">
        <script>
          function open_rincian_picking_monitoring_supply(id_do_sales_order) {
            url = 'iframe/md/h3/h3_md_picking_list?id_do_sales_order=' + id_do_sales_order;
            $('#view_iframe_picking').attr('src', url);
            $('#h3_md_view_modal_picking').modal('show');
          }

          function open_rincian_scan_monitoring_supply(id_do_sales_order) {
            url = 'iframe/md/h3/h3_md_scan_picking_list?id_do_sales_order=' + id_do_sales_order;
            $('#view_iframe_scan').attr('src', url);
            $('#h3_md_view_modal_scan').modal('show');
          }

          function open_rincian_faktur_monitoring_supply(id_do_sales_order) {
            url = 'iframe/md/h3/h3_md_faktur?id_do_sales_order=' + id_do_sales_order;
            $('#view_iframe_faktur').attr('src', url);
            $('#h3_md_view_modal_faktur').modal('show');
          }

          function open_rincian_packing_monitoring_supply(id_do_sales_order) {
            url = 'iframe/md/h3/h3_md_packing_sheet?id_do_sales_order=' + id_do_sales_order;
            $('#view_iframe_packing').attr('src', url);
            $('#h3_md_view_modal_packing').modal('show');
          }

          function open_rincian_shipping_monitoring_supply(id_do_sales_order) {
            url = 'iframe/md/h3/h3_md_shipping?id_do_sales_order=' + id_do_sales_order;
            $('#view_iframe_shipping').attr('src', url);
            $('#h3_md_view_modal_shipping').modal('show');
          }
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </section>
</div>