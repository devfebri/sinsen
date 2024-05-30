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
    <?php 
    if($mode != 'view_check'):
      $this->load->view('h3/h3_md_do_revisi_full_form');
    else:
      $this->load->view('h3/h3_md_do_revisi_view_check');
    endif; 
    ?>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header">
        <?php if($this->input->get('history') != null): ?>
        <a href="h3/<?= $isi ?>">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
        </a>  
        <?php else: ?>
        <a href="h3/<?= $isi ?>?history=true">
          <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
        </a> 
        <?php endif; ?>
      </div>
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="row" id='filter_customer'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Customer</label>
                    <div class="input-group">
                      <input :value='filters.length + " Customer"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_do_revisi_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_dealer_filter_do_revisi_index'); ?>
              <script>
                filter_customer = new Vue({
                  el: '#filter_customer',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      do_revisi.draw();
                    }
                  }
                });

                $("#h3_md_dealer_filter_do_revisi_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_dealer = target.attr('data-id-dealer');

                  if(target.is(':checked')){
                    filter_customer.filters.push(id_dealer);
                  }else{
                    index_dealer = _.indexOf(filter_customer.filters, id_dealer);
                    filter_customer.filters.splice(index_dealer, 1);
                  }
                  h3_md_dealer_filter_do_revisi_index_datatable.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row" id='filter_so'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. SO</label>
                    <div class="input-group">
                      <input readonly :value='filters.length + " Sales Order"' id='so_filter' type="text" class="form-control">
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_so_filter_do_revisi_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_so_filter_do_revisi_index'); ?>
              <script>
                filter_so = new Vue({
                  el: '#filter_so',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      do_revisi.draw();
                    }
                  }
                });

                $("#h3_md_so_filter_do_revisi_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_sales_order = target.attr('data-id-sales-order');

                  if(target.is(':checked')){
                    filter_so.filters.push(id_sales_order);
                  }else{
                    id_sales_order = _.indexOf(filter_so.filters, id_sales_order);
                    filter_so.filters.splice(id_sales_order, 1);
                  }
                  h3_md_so_filter_do_revisi_index_datatable.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row" id='filter_do'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">No. DO</label>
                    <div class="input-group">
                      <input readonly :value='filters.length + " Delivery Order"' id='so_filter' type="text" class="form-control">
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_do_filter_do_revisi_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_do_filter_do_revisi_index'); ?>
              <script>
                filter_do = new Vue({
                  el: '#filter_do',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      do_revisi.draw();
                    }
                }
                });

                $("#h3_md_do_filter_do_revisi_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_do_sales_order = target.attr('data-id-do-sales-order');

                  if(target.is(':checked')){
                    filter_do.filters.push(id_do_sales_order);
                  }else{
                    id_do_sales_order = _.indexOf(filter_do.filters, id_do_sales_order);
                    filter_do.filters.splice(id_do_sales_order, 1);
                  }
                  h3_md_do_filter_do_revisi_index_datatable.draw();
                });
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
                  do_revisi.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_filter_start').val('');
                  $('#periode_filter_end').val('');
                  do_revisi.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row" id='tipe_penjualan_filter'>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Tipe PO</label>
                    <div class="input-group">
                      <input :value='filters.length + " Tipe PO"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_penjualan_filter_do_revisi_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_tipe_penjualan_filter_do_revisi_index'); ?>
              </div>
              <script>
                  tipe_penjualan_filter = new Vue({
                      el: '#tipe_penjualan_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          do_revisi.draw();
                        }
                      }
                  })
              </script>
            </div>
          </div>
        </div>
        <table id="do_revisi" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Source</th>              
              <th>Tanggal SO</th>              
              <th>Tanggal Scan</th>              
              <th>Tanggal DO</th>              
              <th>Nomor DO</th>
              <th>Nama Customer</th>
              <th>Kode Customer</th>
              <th>Alamat</th>
              <th>Total (Amount)</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      currency_formatter = function(data){
        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
      }

      date_formatter = function(data){
        if(data != null) return moment(data).format('DD/MM/YYYY');
        return '-';
      }

      $(document).ready(function(){
        do_revisi = $('#do_revisi').DataTable({
          processing: true,
          serverSide: true,
          order: [],
          searching: false,
          scrollX: true,
          ajax: {
              url: "<?= base_url('api/md/h3/do_revisi') ?>",
              dataSrc: "data",
              type: "POST",
              data: function(d){
                d.filter_customer = filter_customer.filters;
                d.filter_so = filter_so.filters;
                d.filter_do = filter_do.filters;
                d.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                d.periode_filter_start = $('#periode_filter_start').val();
                d.periode_filter_end = $('#periode_filter_end').val();
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
              { data: 'index', orderable: false, width: '3%' },
              { 
                data: 'source', 
                render: function(data){
                  if(data == 'scan_picking_list'){
                    return 'Scan Picking List';
                  }else if(data == 'validasi_picking_list'){
                    return 'Validasi Picking List';
                  }
                  return data;
                }
              }, 
              { 
                data: 'tanggal_so',
                render: date_formatter
              }, 
              { 
                data: 'tanggal_scan',
                render: date_formatter
              }, 
              { 
                data: 'tanggal_do',
                render: date_formatter
              }, 
              { data: 'id_do_sales_order', width: '200px' }, 
              { data: 'nama_dealer', width: '200px' }, 
              { data: 'kode_dealer_md' }, 
              { data: 'alamat', width: '200px' }, 
              { 
                data: 'total',
                render: currency_formatter
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