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
    <?php if($set == 'index'): ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid" style='margin-bottom: 10px;'>
          <div class="row">
            <div class="col-sm-12 no-padding">
              <a href="h3/<?= $isi ?>/upload_excel_stok">
                <button class="btn btn-primary btn-flat">Upload Excel Stok</button>
              </a>
              <a href="h3/<?= $isi ?>/upload_excel_sales">
                <button class="btn btn-primary btn-flat">Upload Excel Sales</button>
              </a>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_customer_filter' type="text" class="form-control" disabled>
                      <input id='id_customer_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_online_stock_dealer_index', [
                  'offline' => true,
                ]); ?>         
                <script>
                function pilih_dealer_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_customer_filter').val(data.nama_dealer);
                    $('#id_customer_filter').val(data.id_dealer);
                  }else if(type == 'reset_filter'){
                    $('#nama_customer_filter').val('');
                    $('#id_customer_filter').val('');
                  }
                  online_stock_dealer_offline.draw();
                  h3_md_dealer_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='id_kelompok_part_filter' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_online_stock_dealer_index'); ?>         
                <script>
                function pilih_kelompok_part_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#id_kelompok_part_filter').val(data.id_kelompok_part);
                  }else if(type == 'reset_filter'){
                    $('#id_kelompok_part_filter').val('');
                  }
                  online_stock_dealer_offline.draw();
                  h3_md_kelompok_part_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Part Number</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='nama_part_filter' type="text" class="form-control" disabled>
                      <input id='id_part_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_part_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_part_filter_online_stock_dealer_index'); ?>         
                <script>
                function pilih_part_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_part_filter').val(data.id_part + ' - ' + data.nama_part);
                    $('#id_part_filter').val(data.id_part);
                  }else if(type == 'reset_filter'){
                    $('#nama_part_filter').val('');
                    $('#id_part_filter').val('');
                  }
                  online_stock_dealer_offline.draw();
                  h3_md_part_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">SIM Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                    <input id='nama_simpart_filter' type="text" class="form-control" disabled>
                      <input id='id_simpart_filter' type="hidden" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_simpart_filter_online_stock_dealer_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_simpart_filter_online_stock_dealer_index'); ?>         
                <script>
                function pilih_simpart_filter_online_stock_dealer_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_simpart_filter').val(data.id_part + ' - ' + data.nama_part);
                    $('#id_simpart_filter').val(data.id_part);
                  }else if(type == 'reset_filter'){
                    $('#nama_simpart_filter').val('');
                    $('#id_simpart_filter').val('');
                  }
                  online_stock_dealer_offline.draw();
                  h3_md_simpart_filter_online_stock_dealer_index_datatable.draw();
                }
                </script>
              </div>
            </div>
            <div class="row">
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
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $('#periode_sales_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                    $('#periode_sales_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                    online_stock_dealer_offline.draw();
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_sales_filter_start').val('');
                    $('#periode_sales_filter_end').val('');
                    online_stock_dealer_offline.draw();
                  });
                </script>
              </div>
            </div>
          </form>
        </div>
        <?php $this->load->view('template/normal_session_message'); ?>
        <table id="online_stock_dealer_offline" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Part Number</th>              
              <th>Nama Part</th>              
              <th>HET</th>              
              <th>Tipe Motor</th>              
              <th>Qty On Hand</th>              
              <th>Qty AVS</th>              
              <th>Qty Sales</th>               
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            online_stock_dealer_offline = $('#online_stock_dealer_offline').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/md/h3/online_stock_dealer_offline') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                      d.id_customer_filter = $('#id_customer_filter').val();
                      d.id_kelompok_part_filter = $('#id_kelompok_part_filter').val();
                      d.id_part_filter = $('#id_part_filter').val();
                      d.id_simpart_filter = $('#id_simpart_filter').val();
                      d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                      d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                    }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' }, 
                    { data: 'id_part' },
                    { data: 'nama_part' },
                    { 
                      data: 'harga_dealer_user',
                      render: function(data){
                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                      }
                    },
                    { data: 'tipe_motor', orderable: false, className: 'text-center', width: '5%' },
                    { data: 'qty_onhand' },
                    { data: 'qty_avs' },
                    { data: 'qty_sales' },
                ],
            });
          });
          

          function view_qty_hotline(id_part) {
            $('#id_part_for_view_qty_hotline').val(id_part);
            h3_md_view_qty_hotline_online_stock_dealer_datatable.draw();
            $('#h3_md_view_qty_hotline_online_stock_dealer').modal('show');
          }

          function view_tipe_motor(id_part) {
            $('#id_part_for_view_tipe_motor').val(id_part);
            h3_md_view_tipe_motor_online_stock_dealer_datatable.draw();
            $('#h3_md_view_tipe_motor_online_stock_dealer').modal('show');
          }
        </script>
        <?php $this->load->view('modal/h3_md_view_qty_hotline_online_stock_dealer'); ?>
        <?php $this->load->view('modal/h3_md_view_tipe_motor_online_stock_dealer'); ?>
        <input type="hidden" id='id_part_for_view_qty_hotline'>
        <input type="hidden" id='id_part_for_view_tipe_motor'>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
    <?php if($set == 'upload_excel_stok'): ?>
      <div id='app' class="box">
        <div v-if="loading" class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div v-if='errors.length > 0' class="alert alert-warning alert-dismissible">
              <button type="button" class="close" @click.prevent='errors = []' aria-hidden="true">×</button>
              <h4>
                <i class="icon fa fa-warning"></i> 
                Alert!
              </h4>
              <ol class="">
                <li v-for='(each, index) of errors.slice(0, 10)'>
                  {{ each.message }}
                  <ul>
                    <li v-for='(error, index) of each.errors'>{{ error }}</li>
                  </ul>
                </li>
              </ol>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                              <label for="" class="control-label col-sm-2">Dealer</label>
                              <div class="col-sm-4">
                                <div class="input-group">
                                  <input type="text" class="form-control" v-model='nama_dealer' readonly>
                                  <div class="input-group-btn">
                                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_online_stok_dealer_offline_template_stok'><i class="fa fa-search"></i></button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php $this->load->view('modal/h3_md_dealer_online_stok_dealer_offline_template_stok'); ?>
                            <script>
                                function pilih_dealer_online_stok_dealer_offline_template_stok(data) {
                                    app.id_dealer = data.id_dealer;
                                    app.nama_dealer = data.nama_dealer;
                                }
                            </script>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Template Excel Stok</label>
                                <div class="col-sm-4">
                                    <input type="file" @change="on_file_change()" ref="file" class="form-control" />
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <a href="h3/h3_md_online_stock_part_dealer_offline/download_template_stok" class="btn btn-flat btn-info">Download Template</a>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-6 no-padding">
                                <button class="btn btn-flat btn-primary" @click.prevent="upload">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        app = new Vue({
          el: '#app',
          data: {
            loading: false,
            errors: [],
            file: null,
            id_dealer: null,
            nama_dealer: null,
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);
              post.set('id_dealer', this.id_dealer);

              this.errors = [];
              this.loading = true;
              axios.post('h3/h3_md_online_stock_part_dealer_offline/store_upload_stok', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                window.location = 'h3/h3_md_online_stock_part_dealer_offline';
              })
              .catch(function(err){
                app.loading = false;
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.payload;
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
              });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          }
        })
      </script>
    <?php endif; ?>
    <?php if($set == 'upload_excel_sales'): ?>
      <div id='app' class="box">
        <div v-if="loading" class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div v-if='errors.length > 0' class="alert alert-warning alert-dismissible">
              <button type="button" class="close" @click.prevent='errors = []' aria-hidden="true">×</button>
              <h4>
                <i class="icon fa fa-warning"></i> 
                Alert!
              </h4>
              <ol class="">
                <li v-for='(each, index) of errors.slice(0, 10)'>
                  {{ each.message }}
                  <ul>
                    <li v-for='(error, index) of each.errors'>{{ error }}</li>
                  </ul>
                </li>
              </ol>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                              <label for="" class="control-label col-sm-2">Dealer</label>
                              <div class="col-sm-4">
                                <div class="input-group">
                                  <input type="text" class="form-control" v-model='nama_dealer' readonly>
                                  <div class="input-group-btn">
                                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_online_stok_dealer_offline_template_stok'><i class="fa fa-search"></i></button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php $this->load->view('modal/h3_md_dealer_online_stok_dealer_offline_template_stok'); ?>
                            <script>
                                function pilih_dealer_online_stok_dealer_offline_template_stok(data) {
                                    app.id_dealer = data.id_dealer;
                                    app.nama_dealer = data.nama_dealer;
                                }
                            </script>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Template Excel Sales</label>
                                <div class="col-sm-4">
                                    <input type="file" @change="on_file_change()" ref="file" class="form-control" />
                                </div>
                                <div class="col-sm-3 no-padding">
                                    <a href="h3/h3_md_online_stock_part_dealer_offline/download_template_sales" class="btn btn-flat btn-info">Download Template</a>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-6 no-padding">
                                <button class="btn btn-flat btn-primary" @click.prevent="upload">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        app = new Vue({
          el: '#app',
          data: {
            loading: false,
            errors: [],
            file: null,
            id_dealer: null,
            nama_dealer: null,
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);
              post.set('id_dealer', this.id_dealer);

              this.errors = [];
              this.loading = true;
              axios.post('h3/h3_md_online_stock_part_dealer_offline/store_upload_sales', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                window.location = 'h3/h3_md_online_stock_part_dealer_offline';
              })
              .catch(function(err){
                app.loading = false;
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.payload;
                }else{
                  toastr.error(err);
                }
              });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          }
        })
      </script>
    <?php endif; ?>
  </section>
</div>
