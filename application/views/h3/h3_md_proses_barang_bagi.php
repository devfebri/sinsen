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
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <?= $breadcrumb ?>
</section>
<section class="content">
<?php

  if ($set=="form") {
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>

    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date') }" class="col-sm-4">
                      <div class="input-group">
                        <input :disabled='mode == "detail"' readonly id='periode' type="text" class="form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" @click.prevent='generate_so'>Generate SO</button>
                        </div>
                      </div>
                      <small v-if="error_exist('start_date')" class="form-text text-danger">{{ get_error('start_date') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div v-bind:class="{ 'has-error': error_exist('kategori') }" class="col-sm-4">
                      <select class="form-control" v-model='proses_barang_bagi.kategori'>
                        <option value="">-</option>
                        <option value="SIM Part">SIM Part</option>
                        <option value="Non SIM Part">Non SIM Part</option>
                        <option value="KPB">KPB</option>
                      </select>
                      <small v-if="error_exist('kategori')" class="form-text text-danger">{{ get_error('kategori') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="control-label col-sm-2">Kelompok Parts</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control" readonly :value='selected_kelompok_part.length + " Kelompok Part"'>
                        <div class="input-group-btn">
                          <button class="btn btn-primary btn-flat" type='button' data-toggle='modal' data-target='#kelompok_part_modal'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="kelompok_part_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                              <h4 class="modal-title" id="myModalLabel">Kelompok Parts</h4>
                          </div>
                          <div class="modal-body">
                              <div class="row">
                                <div class="col-sm-12">
                                  <input type="checkbox" true-value='1' false-value='0' v-model='check_all'> Check All
                                </div>
                              </div>
                              <div v-for='chunk of kelompok_part_chunk' class="row">
                                <div v-for='kelompok_part of chunk' class="col-sm-3">
                                  <input type="checkbox" v-if='mode != "detail"' v-model='kelompok_part.checked' true-value='1' false-value='0'> {{ kelompok_part.id_kelompok_part }}
                                </div>
                              </div>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Fix %</label>
                    <div class='col-sm-4'>
                      <vue-numeric :disabled='mode == "detail" || proses_barang_bagi.kategori == "KPB"' v-model='proses_barang_bagi.fix' class='form-control' separator='.' currency='%' currency-symbol-position='suffix' :max='100' :minus='false'></vue-numeric>
                    </div>
                    <!-- <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input :value='filter_tipe_back_order.length + " Jenis PO"' readonly type="text" class="form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_jenis_po_filter_proses_barang_bagi'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div> -->
                    <?php $this->load->view('modal/h3_md_jenis_po_filter_proses_barang_bagi'); ?>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Reguler %</label>
                    <div class='col-sm-4'>
                      <vue-numeric :disabled='mode == "detail" || proses_barang_bagi.kategori == "KPB"' v-model='proses_barang_bagi.reguler' class='form-control' separator='.' currency='%' currency-symbol-position='suffix' :max='100' :minus='false'></vue-numeric>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe Back Order</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input :value='filter_tipe_back_order.length + " Tipe Back Order"' readonly type="text" class="form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_tipe_back_order_filter_proses_barang_bagi'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_tipe_back_order_filter_proses_barang_bagi'); ?>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Urgent %</label>
                    <div class='col-sm-4'>
                      <vue-numeric :disabled='mode == "detail" || proses_barang_bagi.kategori == "KPB"' v-model='proses_barang_bagi.urgent' class='form-control' separator='.' currency='%' currency-symbol-position='suffix' :max='100' :minus='false'></vue-numeric>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input :value='filter_dealers.length + " Customer"' readonly type="text" class="form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_filter_proses_barang_bagi'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_filter_proses_barang_bagi'); ?>
                  <script>
                    $(document).ready(function(){
                      $("#h3_md_dealer_filter_proses_barang_bagi").on('change',"input[type='checkbox']",function(e){
                        target = $(e.target);
                        id_dealer = target.attr('data-id-dealer');

                        if(target.is(':checked')){
                          form_.filter_dealers.push(id_dealer);
                        }else{
                          index_id_dealer = _.indexOf(form_.filter_dealers, id_dealer);
                          form_.filter_dealers.splice(index_id_dealer, 1);
                        }
                        h3_md_dealer_filter_proses_barang_bagi_datatable.draw();
                      });
                    });
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Hotline %</label>
                    <div class='col-sm-4'>
                      <vue-numeric :disabled='mode == "detail" || proses_barang_bagi.kategori == "KPB"' v-model='proses_barang_bagi.hotline' class='form-control' separator='.' currency='%' currency-symbol-position='suffix' :max='100' :minus='false'></vue-numeric>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kabupaten</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input :value='filter_kabupaten.length + " Kabupaten"' readonly type="text" class="form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kabupaten_filter_proses_barang_bagi'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_kabupaten_filter_proses_barang_bagi'); ?>
                  <script>
                    $(document).ready(function(){
                      $("#h3_md_kabupaten_filter_proses_barang_bagi").on('change',"input[type='checkbox']",function(e){
                        target = $(e.target);
                        id_kabupaten = target.attr('data-id-kabupaten');

                        if(target.is(':checked')){
                          form_.filter_kabupaten.push(id_kabupaten);
                        }else{
                          index_id_kabupaten = _.indexOf(form_.filter_kabupaten, id_kabupaten);
                          form_.filter_kabupaten.splice(index_id_kabupaten, 1);
                        }
                        h3_md_kabupaten_filter_proses_barang_bagi_datatable.draw();
                      });
                    });
                  </script>
                  <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                      <input type="checkbox" true-value='1' false-value='0' v-model='simpan_persentase_pembagian'> Simpan Persentase Pembagian
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No SO</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" v-model='no_so_filter'>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Tanggal SO</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" id='tanggal_so_filter'>
                    </div>
                  </div>
                </div>
                <table class="table table-compact">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>No List SO</td>
                    <td>Tanggal SO</td>
                    <td>Nama Customer</td>
                    <td>Alamat Customer</td>
                    <td>Kabupaten</td>
                    <td>Nilai (Amount)</td>
                    <td v-if='mode != "detail"' width='3%'></td>
                  </tr>
                  <tr v-if='filtered_sales_orders.length > 0' v-for='(sales_order, index) of filtered_sales_orders'>
                    <td>{{ index + 1 }}.</td>
                    <td>
                      <a @click.prevent='open_view_sales_order(index)'>{{ sales_order.id_sales_order }}</a>
                    </td>
                    <td>{{ sales_order.tanggal_order_formatted }}</td>
                    <td>{{ sales_order.nama_dealer }}</td>
                    <td>{{ sales_order.alamat }}</td>
                    <td>{{ sales_order.kabupaten }}</td>
                    <td>{{ sales_order.total_amount }}</td>
                    <td v-if='mode != "detail"' class='text-center'>
                      <input type="checkbox" true-value='1' false-value='0' v-model='sales_order.check'>
                    </td>
                  </tr>
                </table>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' :disabled='kebutuhan_parts_tidak_mencukupi.length > 0 && false' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Proses</button>
                    </div>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php $this->load->view('modal/h3_md_view_modal_sales_order_on_do_sales_order'); ?>
<script>
  var form_ = new Vue({
      el: '#form_',
      mounted: function(){
        config = {
          opens: 'left',
          autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
          locale: {
            format: 'DD/MM/YYYY'
          }
        };

        if(this.mode == 'detail' || this.mode == 'edit'){
          config.startDate = new Date(this.proses_barang_bagi.start_date);
          config.endDate = new Date(this.proses_barang_bagi.end_date);
        }

        periode_promo = $('#periode').daterangepicker(config).on('apply.daterangepicker', function(ev, picker) {
          form_.proses_barang_bagi.start_date = picker.startDate.format('YYYY-MM-DD');
          form_.proses_barang_bagi.end_date = picker.endDate.format('YYYY-MM-DD');
          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
        });

        $(document).ready(function(){
          $('#tanggal_so_filter').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            clearBtn: true
          })
          .on('changeDate', function(e){
            form_.tanggal_so_filter = e.format('yyyy-mm-dd');
          });
        });
      },
      data: {
        mode : '<?= $mode ?>',
        index_part: 0,
        loading: false,
        errors: {},
        simpan_persentase_pembagian: 0,
        check_all: 1,
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        proses_barang_bagi: <?= json_encode($proses_barang_bagi) ?>,
        sales_orders: <?= json_encode($sales_orders) ?>,
        <?php else: ?>
        proses_barang_bagi: {
          kategori: '',
          start_date: '',
          end_date: '',
          fix: <?= $setting_persentase['fix'] ?>, 
          reguler: <?= $setting_persentase['reguler'] ?>, 
          hotline: <?= $setting_persentase['hotline'] ?>, 
          urgent: <?= $setting_persentase['urgent'] ?>, 
          umum: <?= $setting_persentase['umum'] ?>, 
        },
        sales_orders: [],
        <?php endif; ?>
        kelompok_parts: <?= json_encode($kelompok_parts) ?>,
        no_so_filter: '',
        tanggal_so_filter: '',
        filter_dealers: [],
        filter_kabupaten: [],
        filter_tipe_back_order: [],
        filter_jenis_po: [],
        kebutuhan_parts: [],
      },
      methods:{
        <?= $form ?>: function(){
          post = this.proses_barang_bagi;
          post.simpan_persentase_pembagian = this.simpan_persentase_pembagian;
          post.kelompok_parts = _.chain(this.selected_kelompok_part)
          .map(function(data){
            return _.pick(data, ['id_kelompok_part']);
          })
          .value();

          post.items = _.chain(this.sales_orders)
          .filter(function(so){
            return so.check == 1;
          })
          .map(function(so){
            return _.pick(so, ['id_sales_order']);
          }).value();

          // this.loading = true;
          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;

            if(data.redirect_url != null){
              window.location = data.redirect_url;
            }
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
            form_.loading = false;
          });
        },
        open_view_sales_order: function(index){
          id_sales_order = this.sales_orders[index].id_sales_order;
          url = 'iframe/md/h3/h3_md_sales_order?id_sales_order=' + id_sales_order;
          $('#view_iframe_sales_order_on_do_sales_order').attr('src', url);
          $('#h3_md_view_modal_sales_order_on_do_sales_order').modal('show');
        },
        generate_so: function(){
          params = _.pick(this.proses_barang_bagi, ['start_date', 'end_date', 'kategori']);
          
          this.loading = true;
          axios.get('h3/<?= $isi ?>/generate_so', {
            params: params
          })
          .then(function(res){
            form_.sales_orders = res.data;
            form_.get_kebutuhan_parts();
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_kebutuhan_parts: _.debounce(function(){
          this.loading = true;
          post = {};
          post.id_sales_order = _.chain(this.sales_orders)
          .filter(function(row){
            return row.check == 1;
          })
          .map(function(row){
            return row.id_sales_order;
          })
          .value();

          axios.post('h3/<?= $isi ?>/get_kebutuhan_parts', Qs.stringify(post))
          .then(function(res){
            form_.kebutuhan_parts = res.data;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        }, 1000),
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        }
      },
      watch: {
        check_all: function(data){
          if(data == 1){
            for (let index = 0; index < this.kelompok_parts.length; index++) {
              this.kelompok_parts[index].checked = 1;
            }
          }else{
            for (let index = 0; index < this.kelompok_parts.length; index++) {
              this.kelompok_parts[index].checked = 0;
            }
          }
        },
        'proses_barang_bagi.kategori': function(data){
          if(data == 'KPB'){
            this.proses_barang_bagi.fix = 0;
            this.proses_barang_bagi.reguler = 100;
            this.proses_barang_bagi.hotline = 0;
            this.proses_barang_bagi.urgent = 0;
            this.proses_barang_bagi.umum = 0;
          }
        },
        sales_orders: {
          deep: true,
          handler: function() {
            this.get_kebutuhan_parts();
          }
        }
      },
      computed: {
        kelompok_part_chunk: function(){
          return _.chunk(this.kelompok_parts, 4);
        },
        selected_kelompok_part: function(){
          return _.chain(this.kelompok_parts)
            .filter(function(row){
              return row.checked == 1;
            })
            .value();
        },
        filtered_sales_orders: function(){
          filter_jenis_po = this.filter_jenis_po;
          filter_tipe_back_order = this.filter_tipe_back_order
          filter_kabupaten = this.filter_kabupaten;
          filter_dealers = this.filter_dealers;
          tanggal_so_filter = this.tanggal_so_filter;
          no_so_filter = this.no_so_filter;

          return _.chain(this.sales_orders)
          .filter(function(sales_order){
            if(filter_jenis_po.length > 0){
              return filter_jenis_po.includes(sales_order.po_type);
            }

            return true;
          })
          .filter(function(sales_order){
            if(filter_tipe_back_order.length > 0){
              return (filter_tipe_back_order.includes(sales_order.po_type)) && (sales_order.back_order == 1);
            }

            return true;
          })
          .filter(function(sales_order){
            if(filter_kabupaten.length > 0){
              return filter_kabupaten.includes(sales_order.id_kabupaten);
            }

            return true;
          })
          .filter(function(sales_order){
            if(filter_dealers.length > 0){
              return filter_dealers.includes(sales_order.id_dealer);
            }

            return true;
          })
          .filter(function(sales_order){
            if(tanggal_so_filter != ''){
              return sales_order.tanggal_order == tanggal_so_filter;
            }

            return true;
          })
          .filter(function(sales_order){
            if(no_so_filter != ''){
              return sales_order.id_sales_order.includes(no_so_filter);
            }
            return true;
          })
          .value();
        },
        kebutuhan_parts_tidak_mencukupi: function(){
          return _.chain(this.kebutuhan_parts)
          .filter(function(row){
            return parseFloat(row.kuantitas) > parseFloat(row.qty_avs);
          })
          .value();
        }
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="proses_barang_bagi" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nomor Barang Bagi</th>
              <th>Periode Awal</th>
              <th>Periode Akhir</th>
              <th>Fix</th>
              <th>Reguler</th>
              <th>Hotline</th>
              <th>Urgent</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            proses_barang_bagi = $('#proses_barang_bagi').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/proses_barang_bagi') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(data){
                    data.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'id_proses_barang_bagi' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'fix' },
                    { data: 'reguler' },
                    { data: 'hotline' },
                    { data: 'urgent' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });

            proses_barang_bagi.on('draw.dt', function() {
              var info = proses_barang_bagi.page.info();
              proses_barang_bagi.column(0, {
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
    <?php } ?>
  </section>
</div>