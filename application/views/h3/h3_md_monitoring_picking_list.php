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
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $form = 'create';
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
      <div v-if='loading' class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-3">
                    <label for="" class="control-label">Nama Picker</label>
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='picker.nama_picker'>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_picker_monitoring_picking'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_picker_monitoring_picking'); ?>
                <script>
                  function pilih_picker_monitoring_picking(data, type) {
                    if(type == 'select'){
                      app.picker.id_picker = data.id_karyawan;
                      app.picker.nama_picker = data.nama_lengkap;
                    }else{
                      app.picker.id_picker = '';
                      app.picker.nama_picker = '';
                    }
                    h3_md_picker_monitoring_picking_datatable.draw();
                  }
                </script>
                <div class="row" style='margin-bottom: 20px;'>
                  <div class="col-sm-3">
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Total Picking List (Belum Terbagi)</label>
                        <vue-numeric class="form-control" disabled v-model='total_picking_list_belum_terbagi' separator='.'></vue-numeric>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Total Item (Belum Terbagi)</label>
                        <vue-numeric class="form-control" disabled v-model='total_item_belum_terbagi' separator='.'></vue-numeric>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Total Pcs (Belum Terbagi)</label>
                        <vue-numeric class="form-control" disabled v-model='total_pcs_belum_terbagi' separator='.'></vue-numeric>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Nilai Picking List (Belum Terbagi)</label>
                        <vue-numeric class="form-control" disabled v-model='nilai_picking_belum_terbagi' currency='Rp' separator='.'></vue-numeric>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Total Picking List</label>
                        <vue-numeric class="form-control" disabled v-model='total_picking_list_terbagi' separator='.'></vue-numeric>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Total Item</label>
                        <vue-numeric class="form-control" disabled v-model='total_item_terbagi' separator='.'></vue-numeric>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Total Pcs</label>
                        <vue-numeric class="form-control" disabled v-model='total_pcs_terbagi' separator='.'></vue-numeric>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Nilai Picking List</label>
                        <vue-numeric class="form-control" disabled v-model='nilai_picking_terbagi' currency='Rp' separator='.'></vue-numeric>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Search No. Picking List</label>
                        <div class="input-group">
                          <input :value='filters_picking_list.length + " Picking list"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_picking_list_filter_monitoring_picking'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_picking_list_filter_monitoring_picking'); ?>
                    <script>
                      $(document).ready(function(){
                        $("#h3_md_picking_list_filter_monitoring_picking").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_picking_list = target.attr('data-id-picking-list');

                          if(target.is(':checked')){
                            app.filters_picking_list.push(id_picking_list);
                          }else{
                            index_picking_list = _.indexOf(app.filters_picking_list, id_picking_list);
                            app.filters_picking_list.splice(index_picking_list, 1);
                          }
                          h3_md_picking_list_filter_monitoring_picking_datatable.draw();
                        });
                      });
                    </script>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Search No. DO</label>
                        <div class="input-group">
                          <input :value='filters_no_do.length + " Delivery Order"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_do_filter_monitoring_picking'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_do_filter_monitoring_picking'); ?>
                    <script>
                      $(document).ready(function(){
                        $("#h3_md_do_filter_monitoring_picking").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_do_sales_order = target.attr('data-id-do-sales-order');

                          if(target.is(':checked')){
                            app.filters_no_do.push(id_do_sales_order);
                          }else{
                            index_id_do_sales_order = _.indexOf(app.filters_no_do, id_do_sales_order);
                            app.filters_no_do.splice(index_id_do_sales_order, 1);
                          }
                          h3_md_do_filter_monitoring_picking_datatable.draw();
                        });
                      });
                    </script>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Filter Jenis Dealer</label>
                        <div class="input-group">
                          <input :Value='filters_jenis_dealer.length + " Jenis Dealer"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_jenis_dealer_filter_monitoring_picking'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_jenis_dealer_filter_monitoring_picking'); ?>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Filter Kabupaten</label>
                        <div class="input-group">
                          <input :value='filters_kabupaten.length + " Kabupaten"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kabupaten_filter_monitoring_picking'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_kabupaten_filter_monitoring_picking'); ?>
                    <script>
                      $(document).ready(function(){
                        $("#h3_md_kabupaten_filter_monitoring_picking").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_kabupaten = target.attr('data-id-kabupaten');

                          if(target.is(':checked')){
                            app.filters_kabupaten.push(id_kabupaten);
                          }else{
                            index_id_kabupaten = _.indexOf(app.filters_kabupaten, id_kabupaten);
                            app.filters_kabupaten.splice(index_id_kabupaten, 1);
                          }
                          h3_md_kabupaten_filter_monitoring_picking_datatable.draw();
                        });
                      });
                    </script>
                  </div>
                  <div class="col-sm-3">
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Filter Tanggal SO</label>
                        <input id='periode_sales_filter' type="text" class="form-control" readonly>
                        <input id='periode_sales_filter_start' type="hidden" disabled>
                        <input id='periode_sales_filter_end' type="hidden" disabled>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Filter Tanggal DO</label>
                        <input id='periode_do_filter' type="text" class="form-control" readonly>
                        <input id='periode_do_filter_start' type="hidden" disabled>
                        <input id='periode_do_filter_end' type="hidden" disabled>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Filter Tanggal Picking List</label>
                        <input id='periode_picking_list_filter' type="text" class="form-control" readonly>
                        <input id='periode_picking_list_filter_start' type="hidden" disabled>
                        <input id='periode_picking_list_filter_end' type="hidden" disabled>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Filter Customer</label>
                        <div class="input-group">
                          <input :value='filters_customer.length + " Customer"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_customer_filter_monitoring_picking'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_customer_filter_monitoring_picking'); ?>
                    <script>
                      $(document).ready(function(){
                        $("#h3_md_customer_filter_monitoring_picking").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_dealer = target.attr('data-id-dealer');

                          if(target.is(':checked')){
                            app.filters_customer.push(id_dealer);
                          }else{
                            index_id_dealer = _.indexOf(app.filters_customer, id_dealer);
                            app.filters_customer.splice(index_id_dealer, 1);
                          }
                          h3_md_customer_filter_monitoring_picking_datatable.draw();
                        });
                      });
                    </script>
                    <div class="row">
                      <div class="col-sm-12">
                        <label for="" class="control-label">Status</label>
                        <select v-model='filters_status' class="form-control">
                          <option value="">Kosong</option>
                          <option value="Open">Open</option>
                          <option value="On Process">On Process</option>
                          <option value="Closed">Closed</option>
                          <option value="Re-Check">Re-Check</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Tgl SO</th>              
                          <th>No. SO</th>              
                          <th>Tgl DO</th>              
                          <th>No. DO</th>              
                          <th>Tgl Picking List</th>              
                          <th>No. Picking List</th>              
                          <th>Kode Customer</th>              
                          <th>Nama Customer</th>              
                          <th>Kabupaten</th>              
                          <th>Nilai DO</th>              
                          <th>Total Item</th>              
                          <th>Total Pcs</th>              
                          <th v-if="!showCheckbox">Picker</th>              
                          <th v-if="showCheckbox">Checklist</th>              
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(list, index) in picking_list"> 
                          <td>{{ index + 1 }}.</td>
                          <td>{{ list.tanggal_sales }}</td>
                          <td>{{ list.id_sales_order}}</td>
                          <td>{{ list.tanggal_do }}</td>
                          <td>{{ list.id_do_sales_order }}</td>
                          <td>{{ list.tanggal_picking }}</td>
                          <td class="align-middle"><a target="_blank" :href="'h3/h3_md_picking_list/detail?id=' + list.id_picking_list">{{ list.id_picking_list }}</a></td>                       
                          <td class="align-middle">{{ list.kode_dealer_md }}</td>            
                          <td class="align-middle">{{ list.nama_dealer }}</td>            
                          <td class="align-middle">{{ list.kabupaten }}</td>            
                          <td class="align-middle">
                            <vue-numeric v-model='list.total' currency='Rp' separator='.' read-only></vue-numeric>
                          </td>            
                          <td class="align-middle">{{ list.total_item }}</td>            
                          <td class="align-middle">{{ list.total_pcs }}</td>            
                          <td v-if="!showCheckbox" class="align-middle">{{ list.nama_picker }}</td>            
                          <td v-if="showCheckbox" class="align-middle">
                            <div class="checkbox">
                              <label><input type="checkbox" v-model="list.checked" true-value="1" false-value='0'></label>
                            </div>
                          </td>            
                          <td class="align-middle">{{ list.status }}</td>                                        
                        </tr>
                        <tr v-if="picking_list.length < 1">
                          <td class="text-center" colspan="15">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button @click.prevent='set_picker' class="btn btn-flat btn-sm btn-primary">Simpan</button>
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
            picker: {
              id_picker: '',
              nama_picker: '',
            },
            picking_list: [],
            filters_picking_list: [],
            filters_no_do: [],
            filters_kabupaten: [],
            filters_jenis_dealer: [],
            filters_customer: [],
            filters_status: '',
          },
          methods: {
            set_picker: function(){
              post = {};
              post.id_picker = this.picker.id_picker;
              post.picking_list = _.chain(this.picking_list)
              .map(function (data) {
                return _.pick(data, ['id_picking_list', 'checked']);
              })
              .value();

              this.loading = true;
              axios.post('h3/<?= $isi ?>/set_picker', Qs.stringify(post))
              .then(function(res){
                app.get_picking_list();
                toastr.success('Data berhasil disimpan');
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            get_picking_list: function(){
              post = {};
              post.id_picker = this.picker.id_picker;
              post.filters_picking_list = this.filters_picking_list;
              post.filters_no_do = this.filters_no_do;
              post.filters_kabupaten = this.filters_kabupaten;
              post.filters_jenis_dealer = this.filters_jenis_dealer;
              post.filters_customer = this.filters_customer;
              post.periode_sales_filter_start = $('#periode_sales_filter_start').val();
              post.periode_sales_filter_end = $('#periode_sales_filter_end').val();
              post.periode_do_filter_start = $('#periode_do_filter_start').val();
              post.periode_do_filter_end = $('#periode_do_filter_end').val();
              post.periode_picking_list_filter_start = $('#periode_picking_list_filter_start').val();
              post.periode_picking_list_filter_end = $('#periode_picking_list_filter_end').val();
              post.filters_status = this.filters_status;

              this.loading = true;
              axios.post('h3/<?= $isi ?>/get_picking_list', Qs.stringify(post))
              .then(function(res){
                app.picking_list = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ 
                app.loading = false;
              });
            },
          },
          watch: {
            'picker.id_picker': function(){
              this.get_picking_list();
            },
            filters_picking_list: function(){
              this.get_picking_list();
            },
            filters_no_do: function(){
              this.get_picking_list();
            },
            filters_kabupaten: function(){
              this.get_picking_list();
            },
            filters_jenis_dealer: function(){
              this.get_picking_list();
            },
            filters_customer: function(){
              this.get_picking_list();
            },
            filters_status: function(){
              this.get_picking_list();
            },
          },
          computed: {
            showCheckbox: function(){
              return !_.isEqual(this.picker.id_picker, '');
            },
            total_picking_list_belum_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker == null;
              })
              .value().length;
            },
            total_item_belum_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker == null;
              })
              .sumBy(function(data){
                return parseInt(data.total_item);
              })
              .value();
            },
            total_pcs_belum_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker == null;
              })
              .sumBy(function(data){
                return parseInt(data.total_pcs);
              })
              .value();
            },
            nilai_picking_belum_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker == null;
              })
              .sumBy(function(data){
                return parseFloat(data.total);
              })
              .value();
            },
            total_picking_list_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker != null;
              })
              .value().length;
            },
            total_item_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker != null;
              })
              .sumBy(function(data){
                return parseInt(data.total_item);
              })
              .value();
            },
            total_pcs_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker != null;
              })
              .sumBy(function(data){
                return parseInt(data.total_pcs);
              })
              .value();
            },
            nilai_picking_terbagi: function(){
              return _.chain(this.picking_list)
              .filter(function(data){
                return data.id_picker != null;
              })
              .sumBy(function(data){
                return parseFloat(data.total);
              })
              .value();
            },
          },
          mounted: function(){
            this.get_picking_list();

            $('#periode_sales_filter').daterangepicker({
              opens: 'left',
              autoUpdateInput: false,
              locale: {
                format: 'DD/MM/YYYY'
              }
            }, function(start, end, label) {
              $('#periode_sales_filter_start').val(start.format('YYYY-MM-DD'));
              $('#periode_sales_filter_end').val(end.format('YYYY-MM-DD'));
              app.get_picking_list();
            }).on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            }).on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
              $('#periode_sales_filter_start').val('');
              $('#periode_sales_filter_end').val('');
              app.get_picking_list();
            });

            $('#periode_do_filter').daterangepicker({
              opens: 'left',
              autoUpdateInput: false,
              locale: {
                format: 'DD/MM/YYYY'
              }
            }, function(start, end, label) {
              $('#periode_do_filter_start').val(start.format('YYYY-MM-DD'));
              $('#periode_do_filter_end').val(end.format('YYYY-MM-DD'));
              app.get_picking_list();
            }).on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            }).on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
              $('#periode_do_filter_start').val('');
              $('#periode_do_filter_end').val('');
              app.get_picking_list();
            });

            $('#periode_picking_list_filter').daterangepicker({
              opens: 'left',
              autoUpdateInput: false,
              locale: {
                format: 'DD/MM/YYYY'
              }
            }, function(start, end, label) {
              $('#periode_picking_list_filter_start').val(start.format('YYYY-MM-DD'));
              $('#periode_picking_list_filter_end').val(end.format('YYYY-MM-DD'));
              app.get_picking_list();
            }).on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            }).on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
              $('#periode_picking_list_filter_start').val('');
              $('#periode_picking_list_filter_end').val('');
              app.get_picking_list();
            });
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/portal_tugaskan_picker">
            <button class="btn bg-blue btn-flat margin">Tugaskan Picker</button>
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
        <div class="container-fluid" style='margin-bottom: 10px;'>
          <div class="row">
            <div class="col-sm-3">
              <div id='filter_customer' class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Search Nama Customer</label>
                  <div class="input-group">
                    <input :value='filters.length + " Customer"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_monitoring_picking_index'><i class="fa fa-search"></i></button>
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
                      monitoring.draw();
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
              <div id='filter_picking_list' class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Search No. Picking List</label>
                  <div class="input-group">
                    <input :value='filters.length + " Picking List"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_picking_list_filter_monitoring_picking_index'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_picking_list_filter_monitoring_picking_index'); ?>
              <script>
                filter_picking_list = new Vue({
                  el: '#filter_picking_list',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      monitoring.draw();
                    }
                  }
                });

                $("#h3_md_picking_list_filter_monitoring_picking_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_picking_list = target.attr('data-id-picking-list');

                  if(target.is(':checked')){
                    filter_picking_list.filters.push(id_picking_list);
                  }else{
                    index_picking_list = _.indexOf(filter_picking_list.filters, id_picking_list);
                    filter_picking_list.filters.splice(index_picking_list, 1);
                  }
                  h3_md_picking_list_filter_monitoring_picking_index_datatable.draw();
                });
              </script>
              <div id='filter_do' class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Search No. DO</label>
                  <div class="input-group">
                    <input :value='filters.length + " Delivery Order"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_do_filter_monitoring_picking_index'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_do_filter_monitoring_picking_index'); ?>
              <script>
                filter_do = new Vue({
                  el: '#filter_do',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      monitoring.draw();
                    }
                  }
                });

                $("#h3_md_do_filter_monitoring_picking_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_do_sales_order = target.attr('data-id-do-sales-order');

                  if(target.is(':checked')){
                    filter_do.filters.push(id_do_sales_order);
                  }else{
                    index_id_do_sales_order = _.indexOf(filter_do.filters, id_do_sales_order);
                    filter_do.filters.splice(index_id_do_sales_order, 1);
                  }
                  h3_md_do_filter_monitoring_picking_index_datatable.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Periode Sales Order</label>
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
                  monitoring.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_sales_filter_start').val('');
                  $('#periode_sales_filter_end').val('');
                  monitoring.draw();
                });
              </script>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Periode Picking List</label>
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
                  monitoring.draw();
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#periode_picking_list_filter_start').val('');
                  $('#periode_picking_list_filter_end').val('');
                  monitoring.draw();
                });
              </script>
              <div id='filter_picker' class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Picker</label>
                  <div class="input-group">
                    <input :value='filters.length + " Picker"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_picker_filter_monitoring_picking_index'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_picker_filter_monitoring_picking_index'); ?>
              <script>
                filter_picker = new Vue({
                  el: '#filter_picker',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      monitoring.draw();
                    }
                  }
                });

                $("#h3_md_picker_filter_monitoring_picking_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_karyawan = target.attr('data-id-karyawan');

                  if(target.is(':checked')){
                    filter_picker.filters.push(id_karyawan);
                  }else{
                    index_id_karyawan = _.indexOf(filter_picker.filters, id_karyawan);
                    filter_picker.filters.splice(index_id_karyawan, 1);
                  }
                  h3_md_picker_filter_monitoring_picking_index_datatable.draw();
                });
              </script>
              <div id='filter_kabupaten' class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Kabupaten</label>
                  <div class="input-group">
                    <input :value='filters.length + " Kabupaten"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kabupaten_filter_monitoring_picking_index'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_kabupaten_filter_monitoring_picking_index'); ?>
              <script>
                filter_kabupaten = new Vue({
                  el: '#filter_kabupaten',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      monitoring.draw();
                    }
                  }
                });

                $("#h3_md_kabupaten_filter_monitoring_picking_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_kabupaten = target.attr('data-id-kabupaten');

                  if(target.is(':checked')){
                    filter_kabupaten.filters.push(id_kabupaten);
                  }else{
                    index_id_kabupaten = _.indexOf(filter_kabupaten.filters, id_kabupaten);
                    filter_kabupaten.filters.splice(index_id_kabupaten, 1);
                  }
                  h3_md_kabupaten_filter_monitoring_picking_index_datatable.draw();
                });
              </script>
            </div>
            <div id='belum_terbagi' class="col-sm-3">
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Total Picking List (Belum Terbagi)</label>
                  <vue-numeric class="form-control" disabled v-model='total_picking_list'></vue-numeric>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Total Item (Belum Terbagi)</label>
                  <vue-numeric class="form-control" disabled v-model='total_item'></vue-numeric>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Total Pcs (Belum Terbagi)</label>
                  <vue-numeric class="form-control" disabled v-model='total_pcs'></vue-numeric>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Nilai Picking List (Belum Terbagi)</label>
                  <vue-numeric class="form-control" disabled currency='Rp' separator='.' v-model='nilai_picking_list'></vue-numeric>
                </div>
              </div>
            </div>
            <script>
              belum_terbagi = new Vue({
                el: '#belum_terbagi',
                data: {
                  total_picking_list: 0,
                  total_item: 0,
                  total_pcs: 0,
                  nilai_picking_list: 0,
                }
              })
            </script>
            <div id='terbagi' class="col-sm-3">
            <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Total Picking List (Terbagi)</label>
                  <vue-numeric class="form-control" disabled v-model='total_picking_list'></vue-numeric>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Total Item (Terbagi)</label>
                  <vue-numeric class="form-control" disabled v-model='total_item'></vue-numeric>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Total Pcs (Terbagi)</label>
                  <vue-numeric class="form-control" disabled v-model='total_pcs'></vue-numeric>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="" class="control-label">Nilai Picking List (Terbagi)</label>
                  <vue-numeric class="form-control" disabled currency='Rp' separator='.' v-model='nilai_picking_list'></vue-numeric>
                </div>
              </div>
            </div>
            <script>
              terbagi = new Vue({
                el: '#terbagi',
                data: {
                  total_picking_list: 0,
                  total_item: 0,
                  total_pcs: 0,
                  nilai_picking_list: 0,
                }
              })
            </script>
          </div>
        </div>
        <table id="monitoring_picking_list_index" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl SO</th>              
              <th>No. SO</th>              
              <th>Tgl DO</th>              
              <th>No. DO</th>              
              <th>Tgl Picking List</th>              
              <th>No. Picking List</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Kabupaten</th>              
              <th>Nilai DO</th>              
              <th>Total Item</th>              
              <th>Total Pcs</th>              
              <th>Nama Picker</th>              
              <th>Action</th>              
              <th>Status</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function(){
        monitoring = $('#monitoring_picking_list_index').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          order: [],
          scrollX: true,
          ajax: {
              url: "<?= base_url('api/md/h3/monitoring_picking_list') ?>",
              dataSrc: function(json){
                belum_terbagi.total_picking_list = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker == null;
                })
                .value().length;

                belum_terbagi.total_item = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker == null;
                })
                .sumBy(function(data){
                  return parseInt(data.total_item)
                })
                .value();

                belum_terbagi.total_pcs = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker == null;
                })
                .sumBy(function(data){
                  return parseInt(data.total_pcs)
                })
                .value();

                belum_terbagi.nilai_picking_list = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker == null;
                })
                .sumBy(function(data){
                  return parseFloat(data.total)
                })
                .value();

                terbagi.total_picking_list = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker != null;
                })
                .value().length;

                terbagi.total_item = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker != null;
                })
                .sumBy(function(data){
                  return parseInt(data.total_item)
                })
                .value();

                terbagi.total_pcs = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker != null;
                })
                .sumBy(function(data){
                  return parseInt(data.total_pcs)
                })
                .value();

                terbagi.nilai_picking_list = _.chain(json.data)
                .filter(function(data){
                  return data.id_picker != null;
                })
                .sumBy(function(data){
                  return parseFloat(data.total)
                })
                .value();

                return json.data;
              },
              type: "POST",
              data: function(d){
                d.id_customer = filter_customer.filters;
                d.id_picking_list = filter_picking_list.filters;
                d.id_do_sales_order = filter_do.filters;
                d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                d.periode_picking_list_filter_start = $('#periode_picking_list_filter_start').val();
                d.periode_picking_list_filter_end = $('#periode_picking_list_filter_end').val();
                d.picker = filter_picker.filters;
                d.kabupaten = filter_kabupaten.filters;
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
            { data: null, orderable: false, width: '3%' },
            { data: 'tanggal_sales' },
            { data: 'id_sales_order' },
            { data: 'tanggal_do' },
            { data: 'id_do_sales_order' },
            { data: 'tanggal_picking' },
            { data: 'id_picking_list' },
            { data: 'kode_dealer_md' },
            { data: 'nama_dealer' },
            { data: 'kabupaten' },
            { data: 'total_formatted' },
            { data: 'total_item' },
            { data: 'total_pcs' },
            { data: 'nama_picker' },
            { data: 'action', className: 'text-center', orderable: false },
            { data: 'status' },
          ],
        });

        monitoring.on('draw.dt', function() {
          var info = monitoring.page.info();
          monitoring.column(0, {
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
<?php $this->load->view('modal/h3_md_modal_view_picking_list'); ?>
<script>
  function open_view_modal_picking_list(id_picking_list) {
    $('#h3_md_modal_view_picking_list').modal('show');
    h3_md_modal_view_picking_list_vue.picking.id_picking_list = id_picking_list;
    h3_md_modal_view_picking_list_vue.no_action = true;
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