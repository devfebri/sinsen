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
        $form = 'detail';
        $disabled = 'disabled';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>

    <?php if($mode == 'upload'): 
      $this->load->view('h3/h3_md_sales_order_upload');
    else:
      $this->load->view('h3/h3_md_sales_order_form');
    endif; ?>

    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-md-6">
              <a href="h3/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat"><i class="fa fa-plus"></i> Add New</button>
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
            </div>
            <div class="col-md-6 text-right">
              <a href="h3/<?= $isi ?>/upload">
                <button class="btn btn-success btn-flat">Upload</button>
              </a>
              <a href="h3/<?= $isi ?>/download_template">
                <button class="btn btn-info btn-flat">Download Template</button>
              </a>
            </div>
          </div>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
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
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_sales_order_index'); ?>         
                <script>
                function pilih_dealer_filter_sales_order_index(data, type) {
                  if(type == 'add_filter'){
                    $('#nama_customer_filter').val(data.nama_dealer);
                    $('#id_customer_filter').val(data.id_dealer);
                  }else if(type == 'reset_filter'){
                    $('#nama_customer_filter').val('');
                    $('#id_customer_filter').val('');
                  }
                  sales_order.draw();
                  h3_md_dealer_filter_sales_order_index_datatable.draw();
                }
                </script>
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
                      sales_order.draw();
                    }, 500));
                  });
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
                    sales_order.draw();
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_sales_filter_start').val('');
                    $('#periode_sales_filter_end').val('');
                    sales_order.draw();
                  });
                </script>
              </div>
              <div id='tipe_penjualan_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Tipe Penjualan</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " tipe penjualan"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_tipe_penjualan_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_tipe_penjualan_filter_sales_order_index'); ?>
                <script>
                    tipe_penjualan_filter = new Vue({
                        el: '#tipe_penjualan_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            sales_order.draw();
                          }
                        }
                    })
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kategori Sales</label>
                  <div class="col-sm-8">
                    <select id="kategori_sales_filter" class="form-control">
                      <option value="">-Pilih-</option>
                      <option value="SIM Part">SIM Part</option>
                      <option value="Non SIM Part">Non SIM Part</option>
                      <option value="KPB">KPB</option>
                    </select>
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#kategori_sales_filter').on("change", function(){
                      sales_order.draw();
                    });
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Tipe Produk</label>
                  <div class="col-sm-8">
                    <select id="tipe_produk_filter" class="form-control">
                      <option value="">-Pilih-</option>
                      <option value="Parts">Parts</option>
                      <option value="Oil">Oil</option>
                      <option value="Acc">Accesories</option>
                      <option value="Apparel">Apparel</option>
                      <option value="Tools">Tools</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#tipe_produk_filter').on("change", function(){
                      sales_order.draw();
                    });
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div id='jenis_dealer_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Jenis Dealer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " filter"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_jenis_dealer_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_jenis_dealer_filter_sales_order_index'); ?>
                <script>
                    jenis_dealer_filter = new Vue({
                        el: '#jenis_dealer_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            sales_order.draw();
                          }
                        }
                    })
                </script>
              </div>
              <div id='kabupaten_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kabupaten</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " kabupaten"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kabupaten_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_kabupaten_filter_sales_order_index'); ?>
                <script>
                    kabupaten_filter = new Vue({
                        el: '#kabupaten_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            sales_order.draw();
                          }
                        }
                    });

                    $("#h3_md_kabupaten_filter_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_kabupaten = target.attr('data-id-kabupaten');


                      if(target.is(':checked')){
                        kabupaten_filter.filters.push(id_kabupaten);
                      }else{
                        index_kabupaten = _.indexOf(kabupaten_filter.filters, id_kabupaten);
                        kabupaten_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_kabupaten_filter_sales_order_index_datatable.draw();
                    });
                </script>
              </div>
            </div>
            <div class="row">
              <div id='kelompok_part_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " kelompok part"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_sales_order_index'); ?>
                <script>
                    kelompok_part_filter = new Vue({
                        el: '#kelompok_part_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            sales_order.draw();
                          }
                        }
                    });

                    $("#h3_md_kelompok_part_filter_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_kelompok_part = target.attr('data-id-kelompok-part');

                      if(target.is(':checked')){
                        kelompok_part_filter.filters.push(id_kelompok_part);
                      }else{
                        index_kabupaten = _.indexOf(kelompok_part_filter.filters, id_kelompok_part);
                        kelompok_part_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_kelompok_part_filter_sales_order_index_datatable.draw();
                    });
                </script>
              </div>
              <div id='salesman_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Salesman</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " salesman"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_salesman_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_salesman_filter_sales_order_index'); ?>
                <script>
                    salesman_filter = new Vue({
                        el: '#salesman_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            sales_order.draw();
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
                      h3_md_salesman_filter_sales_order_index_datatable.draw();
                    });
                </script>
              </div>
            </div>
            <div class="row">
              <div id='status_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Status</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " Status"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_status_filter_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_status_filter_sales_order_index'); ?>
                <script>
                    status_filter = new Vue({
                        el: '#status_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            sales_order.draw();
                          }
                        }
                    })
                </script>
              </div>
              <div id='autofulfillment_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Autofulfillment Filter</label>
                  <div class="col-sm-8">
                    <select id="autofulfillment_md_filter" class="form-control">
                        <option value="">-Pilih-</option>
                        <option value="1">Ya</option>
                      </select>
                  </div>
                </div> 
                <script>
                  $(document).ready(function(){
                    $('#autofulfillment_md_filter').on("change", function(){
                      sales_order.draw();
                      // alert($('#autofulfillment_md_filter').val());
                    });
                  });
                </script>  
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">No PO Dealer</label>
                  <div class="col-sm-8">
                    <input id='no_po_filter' type="text" class="form-control">
                  </div>
                  <script>
                    $(document).ready(function(){
                        $('#no_po_filter').on("keyup", _.debounce(function(){
                          sales_order.draw();
                        }, 500));
                    });
                  </script>
                </div>   
              </div>  
            </div>
            <div id='info_penjualan' class="row">
              <div class="col-sm-12">
                <table class="table-bordered table">
                    <tr>
                      <td class='text-right text-bold'>Parts</td>
                      <td>
                        : <vue-numeric read-only v-model='total_amount_parts' currency='Rp' separator='.'></vue-numeric>
                      </td>
                      <td class='text-right text-bold'>Oil</td>
                      <td>
                        : <vue-numeric read-only v-model='total_amount_oil' separator='.'></vue-numeric>
                      </td>
                      <td class='text-right text-bold'>Accesories</td>
                      <td>
                        : <vue-numeric read-only v-model='total_amount_acc' currency='Rp' separator='.'></vue-numeric>
                      </td>
                    </tr>
                    <tr>
                      <td class='text-right text-bold'>Qty</td>
                      <td>
                        : <vue-numeric read-only v-model='qty_parts' separator='.'></vue-numeric>
                      </td>
                      <td class='text-right text-bold'>Qty</td>
                      <td>
                        : <vue-numeric read-only v-model='qty_oil' separator='.'></vue-numeric>
                      </td>
                      <td class='text-right text-bold'>Qty</td>
                      <td>
                        : <vue-numeric read-only v-model='qty_acc' separator='.'></vue-numeric>
                      </td>
                    </tr>
                </table>
              </div>
              <script>
                info_penjualan = new Vue({
                  el: '#info_penjualan',
                  data: {
                    total_amount_parts: 0,
                    qty_parts: 0,
                    total_amount_oil: 0,
                    qty_oil: 0,
                    total_amount_acc: 0,
                    qty_acc: 0,
                  }
                })
              </script>
            </div>
          </form>
        </div>
        <table id="sales_order_index" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tanggal SO</th>              
              <th>Nomor SO</th>               
              <th>No PO Dealer</th>                    
              <th>Produk</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Kabupaten</th>              
              <th>Nilai SO Awal</th>
              <th>Nilai SO to DO</th>
              <th>S/R</th>
              <th>Jumlah DO</th>
              <th>DO Proses</th>
              <th>DO Close</th>
              <th>Salesman</th>
              <th>Status</th>  
              <th>Created PO By</th> 
              <th>Autofulfillment MD</th> 
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function(){
        sales_order = $('#sales_order_index').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          scrollX: true,
          order: [],
          ajax: {
              url: "<?= base_url('api/md/h3/sales_order') ?>",
              dataSrc: function(json){

                filter_data = {};
                filter_data.id_customer_filter = $('#id_customer_filter').val();
                filter_data.no_so_filter = $('#no_so_filter').val();
                filter_data.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                filter_data.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                filter_data.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                filter_data.jenis_dealer_filter = jenis_dealer_filter.filters;
                filter_data.kabupaten_filter = kabupaten_filter.filters;
                filter_data.kelompok_part_filter = kelompok_part_filter.filters;
                filter_data.salesman_filter = salesman_filter.filters;
                filter_data.status_filter = status_filter.filters;
                filter_data.kategori_sales_filter = $('#kategori_sales_filter').val();
                filter_data.tipe_produk_filter = $('#tipe_produk_filter').val();
                filter_data.autofulfillment_md_filter = $('#autofulfillment_md_filter').val();
                filter_data.no_po_filter = $('#no_po_filter').val();

                /* 26-08-2023 dicomment dlu krna H3 kadang cepat kadang lama, 
                axios.post('<?= base_url('api/md/h3/sales_order/get_sales_order_info/Parts') ?>', Qs.stringify(filter_data))
                .then(function(res){
                  info_penjualan.total_amount_parts = res.data.amount;
                  info_penjualan.qty_parts = res.data.kuantitas_part;
                })
                .catch(function(err){
                  toastr.error(err);
                });

                axios.post('<?= base_url('api/md/h3/sales_order/get_sales_order_info/Oil') ?>', Qs.stringify(filter_data))
                .then(function(res){
                  info_penjualan.total_amount_oil = res.data.amount;
                  info_penjualan.qty_oil = res.data.kuantitas_part;
                })
                .catch(function(err){
                  toastr.error(err);
                });

                axios.post('<?= base_url('api/md/h3/sales_order/get_sales_order_info/Acc') ?>', Qs.stringify(filter_data))
                .then(function(res){
                  info_penjualan.total_amount_acc = res.data.amount;
                  info_penjualan.qty_acc = res.data.kuantitas_part;
                })
                .catch(function(err){
                  toastr.error(err);
                });
                */
                return json.data;
              },
              type: "POST",
              data: function(d){
                d.id_customer_filter = $('#id_customer_filter').val();
                d.no_so_filter = $('#no_so_filter').val();
                d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                d.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                d.jenis_dealer_filter = jenis_dealer_filter.filters;
                d.kabupaten_filter = kabupaten_filter.filters;
                d.kelompok_part_filter = kelompok_part_filter.filters;
                d.salesman_filter = salesman_filter.filters;
                d.status_filter = status_filter.filters;
                d.kategori_sales_filter = $('#kategori_sales_filter').val();
                d.tipe_produk_filter = $('#tipe_produk_filter').val();
                d.autofulfillment_md_filter = $('#autofulfillment_md_filter').val();
                d.no_po_filter = $('#no_po_filter').val();
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
              { data: 'index', orderable: false, width: '3%' }, 
              { data: 'created_at', name: 'so.created_at' }, 
              { data: 'id_sales_order', width: '200px' }, 
              { data: 'id_ref', width: '200px' }, 
              { data: 'produk' }, 
              { data: 'kode_dealer' }, 
              { data: 'nama_dealer', width:'200px' }, 
              { data: 'kabupaten' }, 
              { data: 'total_amount_formatted', width: '8%', className: 'text-right' }, 
              { data: 'nilai_so_to_do', width: '8%', className: 'text-right' }, 
              { data: 'service_rate' }, 
              { data: 'jumlah_do' }, 
              { data: 'do_proses' }, 
              { data: 'do_close' },
              { 
                data: 'salesman',
                render: function(data){
                  if(data != null){
                    return data;
                  }
                  return '-';
                }
              }, 
              { data: 'status', width:'100px' }, 
              { data: 'created_by_md', width: '80px' }, 
              { data: 'autofulfillment_md', width: '80px' }, 
              { data: 'action', orderable: false, width: '3%', className: 'text-center' }
          ],
        });
      });
    </script>
    <?php endif; ?>
  </section>
</div>