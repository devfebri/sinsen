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
  <?php if($set=="index"){ ?>
    <section class="content">
      <div class="box">    
        <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/h3_md_monitoring_outstanding/history" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</a>
            </h3>
          </div><!-- /.box-header -->
        <div class="box-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-3">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="" class="control-label">Periode Packing Sheet</label>
                      <input id='periode_packing_sheet_filter' type="text" class="form-control" readonly>
                      <input id='periode_packing_sheet_filter_start' type="hidden" disabled>
                      <input id='periode_packing_sheet_filter_end' type="hidden" disabled>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                $('#periode_packing_sheet_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }, function(start, end, label) {
                  $('#periode_packing_sheet_filter_start').val(start.format('YYYY-MM-DD'));
                  $('#periode_packing_sheet_filter_end').val(end.format('YYYY-MM-DD'));
                  monitoring_outstanding.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_packing_sheet_filter_start').val('');
                  $('#periode_packing_sheet_filter_end').val('');
                  monitoring_outstanding.draw();
                });
              </script>
              <div class="col-sm-3" id='part_filter'>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="" class="control-label">Kode Part</label>
                      <div class="input-group">
                        <input :value='filters.length + " Part"' type="text" class="form-control" readonly>
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_part_filter_monitoring_outstanding_index'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_part_filter_monitoring_outstanding_index'); ?>         
              <script>
                  part_filter = new Vue({
                      el: '#part_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          monitoring_outstanding.draw();
                        }
                      }
                  });

                  $("#h3_md_part_filter_monitoring_outstanding_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_part = target.attr('data-id-part');

                    if(target.is(':checked')){
                      part_filter.filters.push(id_part);
                    }else{
                      index_id_part = _.indexOf(part_filter.filters, id_part);
                      part_filter.filters.splice(index_id_part, 1);
                    }
                    h3_md_part_filter_monitoring_outstanding_index_datatable.draw();
                  });
              </script>
              <div class="col-sm-3" id='purchase_filter'>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="" class="control-label">No. PO</label>
                      <div class="input-group">
                        <input :value='filters.length + " Purchase Order"' type="text" class="form-control" readonly>
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_purchase_filter_monitoring_outstanding_index'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_purchase_filter_monitoring_outstanding_index'); ?>         
              <script>
                  purchase_filter = new Vue({
                      el: '#purchase_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          monitoring_outstanding.draw();
                        }
                      }
                  });

                  $("#h3_md_purchase_filter_monitoring_outstanding_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_purchase_order = target.attr('data-id-purchase-order');

                    if(target.is(':checked')){
                      purchase_filter.filters.push(id_purchase_order);
                    }else{
                      index_id_purchase_order = _.indexOf(purchase_filter.filters, id_purchase_order);
                      purchase_filter.filters.splice(index_id_purchase_order, 1);
                    }
                    // h3_md_purchase_filter_monitoring_outstanding_index_datatable.draw();
                    drawing_purchase_monitoring_outstanding();
                  });
              </script>
              <div class="col-sm-3" id='surat_sl_ahm_filter'>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="" class="control-label">No. Surat SL AHM</label>
                      <div class="input-group">
                        <input :value='filters.length + " Surat SL AHM"' type="text" class="form-control" readonly>
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_surat_sl_ahm_filter_monitoring_outstanding_index'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_surat_sl_ahm_filter_monitoring_outstanding_index'); ?>         
              <script>
                  surat_sl_ahm_filter = new Vue({
                      el: '#surat_sl_ahm_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          monitoring_outstanding.draw();
                        }
                      }
                  });

                  $("#h3_md_surat_sl_ahm_filter_monitoring_outstanding_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    surat_sl_ahm = target.attr('data-surat-jalan-ahm');

                    if(target.is(':checked')){
                      surat_sl_ahm_filter.filters.push(surat_sl_ahm);
                    }else{
                      index_surat_sl_ahm = _.indexOf(surat_sl_ahm_filter.filters, surat_sl_ahm);
                      surat_sl_ahm_filter.filters.splice(index_surat_sl_ahm, 1);
                    }
                    // h3_md_surat_sl_ahm_filter_monitoring_outstanding_index_datatable.draw();
                    drawing_surat_jalan_ahm();
                  });
              </script>
            </div>
          </div>
          <table id="monitoring_outstanding" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>              
                <th>No PO</th>              
                <th>Part Number</th>              
                <th>Nama Part</th>              
                <th>Tanggal Packing Sheet</th>              
                <th>No Packing Sheet</th>              
                <th>Kode Karton</th>              
                <th>Tgl SL AHM</th>              
                <th>Qty PO</th>              
                <th>Qty Unfill</th>              
                <th>Qty Intransit</th>              
                <th>Qty On Hand</th>              
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <script>
          $(document).ready(function(){
            monitoring_outstanding = $('#monitoring_outstanding').DataTable({
              processing: true,
              serverSide: true,
              scrollX: true,
              searching: false,
              order: [],
              ajax: {
                url: "<?= base_url('api/md/h3/monitoring_outstanding') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.periode_packing_sheet_filter_start = $('#periode_packing_sheet_filter_start').val();
                    d.periode_packing_sheet_filter_end = $('#periode_packing_sheet_filter_end').val();
                    d.part_filter = part_filter.filters;
                    d.purchase_filter = purchase_filter.filters;
                    d.surat_sl_ahm_filter = surat_sl_ahm_filter.filters;
                  }
              },
              columns: [
                  { data: null, orderable: false, width: '3%' },
                  { data: 'id_purchase_order', width: '150px' }, 
                  { data: 'id_part' }, 
                  { data: 'nama_part', width: '200px' }, 
                  { data: 'packing_sheet_date' }, 
                  { data: 'packing_sheet_number' }, 
                  { data: 'no_doos' }, 
                  { data: 'tanggal_surat_jalan_ahm' }, 
                  { 
                    data: 'qty_order',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
                  { 
                    data: 'qty_unfill',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
                  { 
                    data: 'qty_intransit',orderable: false,
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
                  { 
                    data: 'qty_onhand',orderable: false,
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
              ],
            });

            monitoring_outstanding.on('draw.dt', function() {
              var info = monitoring_outstanding.page.info();
                monitoring_outstanding.column(0, {
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
    </section>
  <?php }elseif($set=="history"){?>
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h3/h3_md_monitoring_outstanding" class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> View Data</a>
          </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="history_monitoring_outstanding" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>              
                <th>No PO</th>              
                <th>Part Number</th>              
                <th>Nama Part</th>              
                <th>Tanggal Packing Sheet</th>              
                <th>No Packing Sheet</th>              
                <th>Kode Karton</th>              
                <th>Tgl SL AHM</th>              
                <th>Qty PO</th>              
                <th>Qty Unfill</th>              
                <th>Qty Intransit</th>              
                <th>Qty On Hand</th>     
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <script>
            $(document).ready(function(){
            history_monitoring_outstanding = $('#history_monitoring_outstanding').DataTable({
              processing: true,
              serverSide: true,
              scrollX: true,
              searching: false,
              order: [],
              ajax: {
                url: "<?= base_url('h3/h3_md_monitoring_outstanding/getDataHistory') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                  }
              },
              columns: [
                  { data: null, orderable: false, width: '3%' },
                  { data: 'id_purchase_order', width: '150px' }, 
                  { data: 'id_part' }, 
                  { data: 'nama_part', width: '200px' }, 
                  { data: 'packing_sheet_date' }, 
                  { data: 'packing_sheet_number' }, 
                  { data: 'no_doos' }, 
                  { data: 'tanggal_surat_jalan_ahm' }, 
                  { 
                    data: 'qty_order',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
                  { 
                    data: 'qty_unfill',
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
                  { 
                    data: 'qty_intransit',orderable: false,
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
                  { 
                    data: 'qty_onhand',orderable: false,
                    render: function(data){
                      return accounting.formatMoney(data, "", 0, ".", ",");
                    }
                  }, 
              ],
            });

            history_monitoring_outstanding.on('draw.dt', function() {
              var info = history_monitoring_outstanding.page.info();
              history_monitoring_outstanding.column(0, {
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
    </section>
  <?php }?>
</div>
