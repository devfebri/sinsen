<script src="<?= base_url("assets/panel/accounting.min.js") ?>" type="text/javascript"></script>
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
      <div class="box-header with-border">
        <div class="btn-group">
          <button type="button" class="btn btn-info btn-flat">Report</button>
          <button type="button" class="btn btn-flat btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="h3/<?= $isi ?>/report_new">Dengan Lokasi Rak</a></li>
            <li><a href="h3/<?= $isi ?>/report_csv">Dengan Lokasi Rak (CSV)</a></li>
            <li><a href="h3/<?= $isi ?>/report_tanpa_lokasi">Tanpa Lokasi Rak</a></li>
            <li><a href="h3/<?= $isi ?>/report_tanpa_lokasi_csv">Tanpa Lokasi Rak (CSV)</a></li>
          </ul>
        </div>
        <a href="h3/<?= $isi ?>/import">
            <button class="btn bg-blue btn-flat margin">Import</button>
          </a>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="" class="control-label">Kode Part</label>
                      <input type="text" class="form-control" id='kode_part_filter'>
                    </div>
                </div>
              </div>
              <script>
                // $(document).ready(function(){
                //   $('#kode_part_filter').on("keyup", _.debounce(function(){
                //     online_stock_md.draw();
                //   }, 500));
                // });
              </script>
              <div class="row">
                <div class="col-sm-12">
                  <div id='filter_produk' class="form-group">
                    <label for="" class="control-label">Produk</label>
                    <div class="input-group">
                      <input :value='filters.length + " Produk"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_produk_filter_online_stock_md_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_produk_filter_online_stock_md_index'); ?>
                  </div>
                  <script>
                    filter_produk = new Vue({
                      el: '#filter_produk',
                      data: {
                        filters: []
                      },
                      watch: {
                        filters: function(){
                          // online_stock_md.draw();
                        }
                      }
                    });
                  </script>
                </div>
              </div>
              <!-- <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Parts</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_parts'>Rp 0</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Oil</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_oil'>Rp 0</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Accesories</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_accesories'>Rp 0</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Other</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_other'>Rp 0</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Nama Part</label>
                    <input type="text" class="form-control" id='nama_part_filter'>
                  </div>
                </div>
              </div>
              <script>
                // $(document).ready(function(){
                //   $('#nama_part_filter').on("keyup", _.debounce(function(){
                //     online_stock_md.draw();
                //   }, 500));
                // });
              </script>
              <div id='filter_kelompok_part' class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="" class="control-label">Kelompok Part</label>
                    <div class="input-group">
                      <input :value='filters.length + " Kelompok Part"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_filter_online_stock_md_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_kelompok_part_filter_online_stock_md_index'); ?>
              <script>
                filter_kelompok_part = new Vue({
                  el: '#filter_kelompok_part',
                  data: {
                    filters: []
                  },
                  watch: {
                    filters: function(){
                      // online_stock_md.draw();
                    }
                  }
                });

                $("#h3_md_kelompok_part_filter_online_stock_md_index").on('change',"input[type='checkbox']",function(e){
                  target = $(e.target);
                  id_kelompok_part = target.attr('data-id-kelompok-part');

                  if(target.is(':checked')){
                    filter_kelompok_part.filters.push(id_kelompok_part);
                  }else{
                    index_id_kelompok_part = _.indexOf(filter_kelompok_part.filters, id_kelompok_part);
                    filter_kelompok_part.filters.splice(index_id_kelompok_part, 1);
                  }
                  h3_md_kelompok_part_filter_online_stock_md_index_datatable.draw();
                });
              </script>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div id='filter_kategori' class="form-group">
                    <label for="" class="control-label">Kategori</label>
                    <div class="input-group">
                      <input :value='filters.length + " Kategori"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kategori_filter_online_stock_md_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_kategori_filter_online_stock_md_index'); ?>
                  </div>
                  <script>
                    filter_kategori = new Vue({
                      el: '#filter_kategori',
                      data: {
                        filters: []
                      },
                      watch: {
                        filters: function(){
                          // online_stock_md.draw();
                        }
                      }
                    });
                  </script>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div id='filter_status' class="form-group">
                    <label for="" class="control-label">Status</label>
                    <div class="input-group">
                      <input :value='filters.length + " Status"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_status_filter_online_stock_md_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_status_filter_online_stock_md_index'); ?>
                  </div>
                  <script>
                    filter_status = new Vue({
                      el: '#filter_status',
                      data: {
                        filters: []
                      },
                      watch: {
                        filters: function(){
                          // online_stock_md.draw();
                        }
                      }
                    });
                  </script>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div id='filter_rank' class="form-group">
                  <label for="" class="control-label">Rank</label>
                  <div class="input-group">
                    <input :value='filters.length + " Rank"' type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_rank_filter_online_stock_md_index'><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_rank_filter_online_stock_md_index'); ?>
                </div>
                <script>
                  filter_rank = new Vue({
                    el: '#filter_rank',
                    data: {
                      filters: []
                    },
                    watch: {
                      filters: function(){
                        // online_stock_md.draw();
                      }
                    }
                  });
                </script>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <div id='filter_tipe_so' class="form-group">
                    <label for="" class="control-label">Tipe SO</label>
                    <div class="input-group">
                      <input :value='filters.length + " Tipe SO"' type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_so_filter_online_stock_md_index'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_tipe_so_filter_online_stock_md_index'); ?>
                  </div>
                  <script>
                    filter_tipe_so = new Vue({
                      el: '#filter_tipe_so',
                      data: {
                        filters: []
                      },
                      watch: {
                        // filters: _.debounce(function(){
                        //   online_stock_md.draw();
                        // }, 500)
                      }
                    });
                  </script>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                        <button type="button" class="btn btn-success btn-block" id="btn-cari_filter"><span class="fa fa-search"></span>  SEARCH</button>
                  </div> 
                </div>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Parts</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_parts'>Rp 0</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Oil</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_oil'>Rp 0</span>
                      </div>
                      <!-- <div class="col-sm-9">
                        <span class="text-bold">Rp 0</span>
                      </div> -->
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Accesories</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_accesories'>Rp 0</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <span class="text-bold">Other</span>
                      </div>
                      <div class="col-sm-9">
                        <span class="text-bold" id='amount_other'>Rp 0</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <table id="online_stock_md" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Part Number</th>              
              <th>Part Deskripsi</th>              
              <th>HPP</th>              
              <th>HET</th>              
              <th>Status</th>              
              <th>On Hand</th>              
              <th>Booking</th>              
              <th>AVS</th>              
              <th>In Transit</th>              
              <th>Keep Stock</th>              
              <th>Claim MD</th>              
              <th>Qty PO</th>              
              <th>Rak Lokasi</th>              
              <th>Tipe Motor</th>               
              <th>Serial Number</th>           
            </tr>
          </thead>
          <tbody>    
          </tbody>
        </table>
        <?php $this->load->view('modal/h3_md_open_view_keep_stock_online_stock_md'); ?>
        <?php $this->load->view('modal/h3_md_open_view_rak_lokasi_online_stock_md'); ?>
        <?php $this->load->view('modal/h3_md_open_view_tipe_motor_online_stock_md'); ?>
        <?php $this->load->view('modal/h3_md_open_view_qty_intransit_online_stock_md'); ?>
        <?php $this->load->view('modal/h3_md_open_view_qty_booking_online_stock_md'); ?>
        <?php $this->load->view('modal/h3_md_open_view_qty_po_online_stock_md'); ?>
        <?php $this->load->view('modal/h3_md_open_view_serial_number_online_stock_md'); ?>
        <script>
          // $(document).ready(function() {

            var online_stock_md;
            function online_stock_md2(){
            online_stock_md = $('#online_stock_md').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                "bDestroy":true,
                pageLength: 10,
                order: [],
                scrollX: true,
                ajax: {
                    url: "<?= base_url('api/md/h3/online_stock_md') ?>",
                    dataSrc: function(json){
                      filter_data = {};
                      filter_data.kode_part_filter = $('#kode_part_filter').val();
                      filter_data.nama_part_filter = $('#nama_part_filter').val();
                      filter_data.filter_produk = filter_produk.filters;
                      filter_data.filter_kelompok_part = filter_kelompok_part.filters;
                      filter_data.filter_kategori = filter_kategori.filters;
                      filter_data.filter_status = filter_status.filters;
                      filter_data.filter_rank = filter_rank.filters;
                      filter_data.filter_tipe_so = filter_tipe_so.filters;

                      axios.post('<?= base_url('api/md/h3/online_stock_md/amount/Parts') ?>', Qs.stringify(filter_data))
                      .then(function(res){
                        $('#amount_parts').text(
                          accounting.formatMoney(res.data.amount, "Rp ", 0, ".", ",")
                        );
                      })
                      .catch(function(err){
                        toastr.error(err);
                      });

                      axios.post('<?= base_url('api/md/h3/online_stock_md/amount/Oil') ?>', Qs.stringify(filter_data))
                      .then(function(res){
                        $('#amount_oil').text(
                          accounting.formatMoney(res.data.amount, "Rp ", 0, ".", ",")
                        );
                      })
                      .catch(function(err){
                        toastr.error(err);
                      });

                      axios.post('<?= base_url('api/md/h3/online_stock_md/amount/Acc') ?>', Qs.stringify(filter_data))
                      .then(function(res){
                        $('#amount_accesories').text(
                          accounting.formatMoney(res.data.amount, "Rp ", 0, ".", ",")
                        );
                      })
                      .catch(function(err){
                        toastr.error(err);
                      });

                      axios.post('<?= base_url('api/md/h3/online_stock_md/amount/Other') ?>', Qs.stringify(filter_data))
                      .then(function(res){
                        $('#amount_other').text(
                          accounting.formatMoney(res.data.amount, "Rp ", 0, ".", ",")
                        );
                      })
                      .catch(function(err){
                        toastr.error(err);
                      });

                      return json.data;
                    },
                    type: "POST",
                    data: function(d){
                      d.kode_part_filter = $('#kode_part_filter').val();
                      d.nama_part_filter = $('#nama_part_filter').val();
                      d.filter_produk = filter_produk.filters;
                      d.filter_kelompok_part = filter_kelompok_part.filters;
                      d.filter_kategori = filter_kategori.filters;
                      d.filter_status = filter_status.filters;
                      d.filter_rank = filter_rank.filters;
                      d.filter_tipe_so = filter_tipe_so.filters;
                    }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' }, 
                    { data: 'id_part' },
                    { data: 'nama_part', width: '15%' },
                    { data: 'hpp', className: 'text-right', width: '8%' },
                    { data: 'het', className: 'text-right', width: '8%' },
                    { data: 'status' },
                    { 
                      data: 'qty_onhand', 
                      orderable: false,
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",")
                      }
                    },
                    { 
                      data: 'qty_booking', 
                      orderable: false,
                      render: function(data, type, row){
                        return '<a onclick="return open_view_modal_qty_booking(\'' + row.id_part + '\')">' + accounting.formatMoney(data, "", 0, ".", ",") + '</a>';
                      }
                    },
                    { 
                      data: 'qty_avs', 
                      orderable: false,
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",")
                      }
                    },
                    { 
                      data: 'qty_intransit', 
                      orderable: false,
                      render: function(data, type, row){
                        return '<a onclick="return open_view_modal_qty_intransit(\'' + row.id_part + '\')">' + accounting.formatMoney(data, "", 0, ".", ",") + '</a>';
                      }
                    },
                    { 
                      data: 'qty_keep_stock', 
                      orderable: false,
                      render: function(data, type, row){
                        return '<a onclick="return open_view_modal_keep_stock(\'' + row.id_part + '\')">' + accounting.formatMoney(data, "", 0, ".", ",") + '</a>';
                      }
                    },
                    { 
                      data: 'qty_claim', 
                      orderable: false,
                      render: function(data){
                        return accounting.formatMoney(data, "", 0, ".", ",");
                      }
                    },
                    { 
                      data: 'qty_po', 
                      orderable: false,
                      render: function(data, type, row){
                        return '<a onclick="return open_view_modal_qty_po(\'' + row.id_part + '\')">' + accounting.formatMoney(data, "", 0, ".", ",") + '</a>';
                      }
                    },
                    { data: 'rak_lokasi', orderable: false, className: 'text-center' },
                    { data: 'tipe_motor', orderable: false, className: 'text-center' },
                    { data: 'serial_number', orderable: false, className: 'text-center' }
                ],
            });
          // });
          }

          function open_view_modal_keep_stock(id_part) {
            $('#h3_md_open_view_keep_stock_online_stock_md').modal();
            h3_md_open_view_keep_stock_online_stock_md.get_keep_stock(id_part);
          }

          function open_view_modal_rak_lokasi(id_part) {
            $('#h3_md_open_view_rak_lokasi_online_stock_md').modal();
            h3_md_open_view_rak_lokasi_online_stock_md.get_rak_lokasi(id_part);
          }

          function open_view_modal_tipe_motor(id_part) {
            $('#h3_md_open_view_tipe_motor_online_stock_md').modal();
            h3_md_open_view_tipe_motor_online_stock_md.get_tipe_motor(id_part);
          }

          function open_view_modal_qty_intransit(id_part) {
            $('#id_part_open_view_qty_intransit').val(id_part);
            $('#h3_md_open_view_qty_intransit_online_stock_md').modal();
            h3_md_open_view_qty_intransit_online_stock_md_datatable.draw();
          }

          function open_view_modal_qty_booking(id_part) {
            $('#id_part_open_view_qty_booking').val(id_part);
            $('#h3_md_open_view_qty_booking_online_stock_md').modal();
            h3_md_open_view_qty_booking_online_stock_md_datatable.draw();
          }

          function open_view_modal_qty_po(id_part) {
            $('#id_part_open_view_qty_po').val(id_part);
            $('#h3_md_open_view_qty_po_online_stock_md').modal();
            h3_md_open_view_qty_po_online_stock_md_datatable.draw();
          }

          function open_view_modal_serial_number(id_part) {
            $('#h3_md_open_view_serial_number_online_stock_md').modal();
            h3_md_open_view_serial_number_online_stock_md.get_serial_number(id_part);
          }

          $(document).ready(function() {
            $('#btn-cari_filter').on('click', function(e){
              online_stock_md2();
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
    <?php if($mode=="upload"): ?>
      <div id="app" class="box box-default">
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
        <!-- <div v-if='validation_error.length > 0' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='validation_error = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Alert!
          </h4>
          <ol class="">
            <li v-for='(each, index) of validation_error.slice(0, 10)'>
              {{ each.message }}
              <ul>
                <li v-for='(error, index) of each.errors'>{{ error }}</li>
              </ul>
            </li>
          </ol>
        </div> -->
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Upload Stock</label>
                  <div class="col-sm-4">                    
                    <input class="form-control" @change='on_file_change()' ref='file' type='file'>
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button :disabled='file == null' class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
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
            validation_error: [],
            file: null
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);

              this.errors = {};
              this.loading = true;
              axios.post('h3/<?= $isi ?>/inject', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                // window.location = 'h3/<?= $isi ?>';
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.validation_error = data.payload;
                }else{
                  toastr.error(err);
                }
                app.reset_file();
              })
              .then(function(){ app.loading = false; });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            reset_file: function(){
              const input = this.$refs.file;
              input.type = 'text';
              input.type = 'file';
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          }
        });
    </script>
    <?php endif; ?>
  </section>
</div>