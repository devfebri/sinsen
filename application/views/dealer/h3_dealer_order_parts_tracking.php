<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
      <script>
        Vue.use(VueNumeric.default);
      </script>
      <div id="form_" class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div v-if='loading' class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Part Number</label>
                    <div class="col-sm-4">
                        <input v-model='part.id_part' type="text" class="form-control" readonly>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button v-if='part_empty' :disabled='!purchase_order' type='button' data-toggle="modal" data-target="#part_order_tracking_modal" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                      <button v-if='!part_empty' @click.prevent=" part = {} " class="btn btn-flat btn-danger"><i class="fa fa-trash-o"></i></button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Part Deskripsi</label>
                    <div class="col-sm-4">
                        <input v-model='part.nama_part' type="text" class="form-control" readonly data-toggle="modal" data-target="#part_order_tracking_modal">
                    </div>
                  </div>
                  <?php $this->load->view('modal/part_order_tracking') ?>
                  <script>
                  function pilihPart(part) {
                      form_.part = part;
                  }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">PO Number</label>
                    <div class="col-sm-4">
                      <input readonly v-model='purchase_order.po_id' type="text" class="form-control">
                    </div>
                    <div class="col-sm-2 no-padding">
                      <button v-if='purchase_order_empty' :disabled='!part_empty' type='button' data-toggle='modal' data-target='#purchase_order_tracking_modal' class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                      <button v-if='!purchase_order_empty' @click.prevent=" purchase_order = {} " class="btn btn-flat btn-danger"><i class="fa fa-trash-o"></i></button>
                    </div>
                  </div>
                  <?php $this->load->view('modal/purchase_order_tracking') ?>
                  <script>
                    function pilih_purchase_order_tracking(data){
                      form_.purchase_order = data;
                    }
                  </script>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tipe PO</label>
                    <div class="col-sm-4">
                        <select class="form-control" v-model='tipe_po'>
                          <option value="">All</option>
                          <option value="FIX">Fix</option>
                          <option value="REG">Reguler</option>
                          <option value="URG">Urgent</option>
                          <option value="HLO">Hotline</option>
                        </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                    <div class="col-sm-4">
                        <input readonly id='tanggal_po' type="text" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button @click.prevent='track_part' class="btn btn-flat btn-primary">Cari</button>
                    </div>
                  </div>
                  <div class="container no-margin">
                    <div class="row">
                      <div class="col-sm-2 mr-10">
                        <div class="form-group">
                          <label>Part Group</label>
                          <input readonly v-model='checked_part_group' type="text" class="form-control" data-toggle='modal' data-target='#part_group_order_part_tracking'>
                        </div>
                      </div>
                      <?php $this->load->view('modal/part_group_order_part_tracking') ?>
                      <div class="col-sm-2 mr-10">
                        <div class="form-group">
                          <label>Search</label>
                          <input v-model='search_filter' type="text" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                  <table class="table table-bordered">
                    <tr class='bg-blue-gradient'>
                      <td>No</td>
                      <td>PO Number</td>
                      <td>PO Date</td>
                      <td>Part Number</td>
                      <td>Part Description</td>
                      <td>PO</td>
                      <td>Book</td>
                      <td>Pick</td>
                      <td>Pack</td>
                      <td>Bill</td>
                      <td>Ship</td>
                    </tr>
                    <tr v-if='filtered_track_items.length > 0' v-for='(i, index) in filtered_track_items'>
                      <td>{{ index + 1 }}.</td>
                      <td>{{ i.po_id }}</td>
                      <td>{{ i.tanggal_order }}</td>
                      <td>{{ i.id_part }}</td>
                      <td>{{ i.nama_part }}</td>
                      <td>{{ i.po }}</td>
                      <td>{{ i.book }}</td>
                      <td>{{ i.pick }}</td>
                      <td>{{ i.pack }}</td>
                      <td>{{ i.bill }}</td>
                      <td @click.prevent='check_ship_date(i)'>{{ i.ship }}</td>
                    </tr>
                    <tr v-if='track_items.length < 1'>
                      <td colspan='9' class='text-center'>Tidak ada data</td>
                    </tr>
                  </table>          
                  <?php $this->load->view('modal/check_ship_date_part_tracking') ?>        
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            loading: false,
            part: {},
            purchase_order: {},
            tipe_po: '',
            tanggal_po_start: '',
            tanggal_po_end: '',
            track_items: [],
            part_group: <?= json_encode($kelompok_part) ?>,
            checked_part_group: [],
            search_filter: '',
            ship_dates: [],
            id_part_ship_dates: '',
            nama_part_ship_dates: '',
          },
          methods: {
            track_part: function(){
              post = {};
              post.tipe_filter = this.tipe_filter;
              if(this.tipe_filter == 'purchase_order'){
                post.filter_value = this.purchase_order.po_id;
              }else{
                post.filter_value = this.part.id_part;
              }
              post.tipe_po = this.tipe_po;
              post.kategori_po = this.purchase_order.kategori_po;
              this.loading = true;
              axios.post('dealer/h3_dealer_order_parts_tracking/track_part', Qs.stringify(post))
              .then(function(res){
                console.log(res.data);
                form_.track_items = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                form_.loading = false;
              });
            },
            check_ship_date: function(part){
              post = {};
              post.po_id = part.po_id;
              post.id_part = part.id_part;

              this.id_part_ship_dates = part.id_part;
              this.nama_part_ship_dates = part.nama_part;

              this.loading = true;
              axios.post('dealer/h3_dealer_order_parts_tracking/check_ship_date', Qs.stringify(post))
              .then(function(res){
                form_.ship_dates = res.data;
                $('#check_ship_date_part_tracking').modal('show');
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ form_.loading = false; })
            }
          },
          watch: {
            tanggal_po_start: function(){
              purchase_order_tracking_datatable.draw();
            },
            tanggal_po_end: function(){
              purchase_order_tracking_datatable.draw();
            },
            tipe_po: function(){
              purchase_order_tracking_datatable.draw();
            },
          },
          computed: {
            total_qty_ship: function(){
              return _.sumBy(this.ship_dates, function(o){
                return parseInt(o.ship_qty);
              });
            },
            tipe_filter: function(){
              if(!_.isEqual(this.purchase_order, {})){
                return 'purchase_order';
              }
              return 'part_number';
            },
            purchase_order_empty: function(){
              return _.isEqual(this.purchase_order, {});
            },
            part_empty: function(){
              return _.isEqual(this.part, {});
            },
            filtered_track_items: function(){
              checked_part_group = this.checked_part_group
              filtered =  _.filter(this.track_items, function(item){
                if(checked_part_group.length > 0){
                  return _.includes(checked_part_group, item.kelompok_part);
                }else{
                  return true;
                }
              });

              search_filter = this.search_filter;
              filtered = _.filter(filtered, function(item){
                return item.id_part.toLowerCase().includes(search_filter.toLowerCase())
              });
              return filtered;
            }
          },
          mounted: function(){
            tanggal_po = $('#tanggal_po').daterangepicker({
              opens: 'left',
              autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
              locale: {
                format: 'DD/MM/YYYY'
              }
            }, function(start, end, label) {
                form_.tanggal_po_start = start.format('YYYY-MM-DD');
                form_.tanggal_po_end = end.format('YYYY-MM-DD');
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
          }
        });
      </script>
  </section>
</div>