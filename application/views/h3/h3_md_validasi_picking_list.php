<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/humanize-duration.js") ?>" type="text/javascript"></script>
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
    <div id='app' class="box box-default">
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
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div v-if='!sesuai_dengan_pembagian_paket_bundling' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Kuantitas Supply tidak sesuai dengan pembagian paket bundling.
              </div>
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Picking List</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.id_picking_list">
                  </div>                              
                  <label class="col-sm-2 control-label">Nomor DO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.id_do_sales_order">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal Picking List</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.tanggal_picking">
                  </div>                              
                  <label class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.tanggal_do">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.nama_dealer">
                  </div>                              
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.tanggal_so">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.alamat">
                  </div>                              
                  <label class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model='picking.nama_salesman'>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.po_type">
                  </div>                              
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.kategori_po">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.status">
                  </div>
                  <label class="col-sm-2 control-label">Revisi Validasi</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="picking.revisi_validasi">
                  </div>
                </div>
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <span class="text-bold">Search: </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group">
                        <div class="input-group">
                          <input readonly :value='filters_lokasi.length + " Lokasi"' type="text" class="form-control">
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_lokasi_filter_validasi_picking_list'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_lokasi_filter_validasi_picking_list'); ?>
                  <script>
                      $(document).ready(function(){
                        $("#h3_md_lokasi_filter_validasi_picking_list").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_lokasi_rak = target.attr('data-id');

                          if(target.is(':checked')){
                            app.filters_lokasi.push(id_lokasi_rak);
                          }else{
                            index_id_lokasi_rak = _.indexOf(app.filters_lokasi, id_lokasi_rak);
                            app.filters_lokasi.splice(index_id_lokasi_rak, 1);
                          }
                          h3_md_lokasi_filter_validasi_picking_list_datatable.draw();
                        });
                      });
                  </script>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group">
                        <div class="input-group">
                          <input readonly :value='filters_part.length + " Part"' type="text" class="form-control">
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_part_filter_validasi_picking_list'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_part_filter_validasi_picking_list'); ?>
                  <script>
                      $(document).ready(function(){
                        $("#h3_md_part_filter_validasi_picking_list").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_part = target.attr('data-id-part');

                          if(target.is(':checked')){
                            app.filters_part.push(id_part);
                          }else{
                            index_id_part = _.indexOf(app.filters_part, id_part);
                            app.filters_part.splice(index_id_part, 1);
                          }
                          h3_md_part_filter_validasi_picking_list_datatable.draw();
                        });
                      });
                  </script>
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group">
                        <div class="input-group">
                          <input readonly :value='filters_kelompok_part.length + " Kelompok Part"' type="text" class="form-control">
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_filter_validasi_picking_list'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_kelompok_part_filter_validasi_picking_list'); ?>
                  <script>
                      $(document).ready(function(){
                        $("#h3_md_kelompok_part_filter_validasi_picking_list").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_kelompok_part = target.attr('data-id-kelompok-part');

                          if(target.is(':checked')){
                            app.filters_kelompok_part.push(id_kelompok_part);
                          }else{
                            index_id_kelompok_part = _.indexOf(app.filters_kelompok_part, id_kelompok_part);
                            app.filters_kelompok_part.splice(index_id_kelompok_part, 1);
                          }
                          h3_md_kelompok_part_filter_validasi_picking_list_datatable.draw();
                        });
                      });
                  </script>
                </div>
                <?php if($this->input->get('status') == 'start'): ?>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <input type="checkbox" true-value='1' false-value='0' v-model='samakan_qty_disiapkan'> Samakan Qty Disiapkan dengan Qty Picking
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Kode Part</th>              
                          <th>Nama Part</th>              
                          <th v-if='kategori_kpb'>Tipe Kendaraan</th>                     
                          <th>Serial Number</th>           
                          <th>Lokasi Part</th>              
                          <th width="15%">Qty On Hand</th>              
                          <th width="15%">Qty Picking List</th>              
                          <th width="15%">Qty Disiapkan</th>              
                          <th width='10%' class='text-center'>Status</th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in filtered_parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td v-if='kategori_kpb' class="align-middle">{{ part.id_tipe_kendaraan }}</td>                      
                          <td class="align-middle" v-if="part.serial_number"><b>{{ part.serial_number }}</b></td>
                          <td class="align-middle" v-else>{{ part.serial_number }}</td>                     
                          <td class="align-middle">{{ part.nama_lokasi }}</td>                       
                          <td class="align-middle">
                            <vue-numeric class="form-control" v-model='part.qty_on_hand' :read-only='true' separator='.'/>
                          </td>                       
                          <td class="align-middle">
                            <vue-numeric class="form-control" v-model='part.qty_picking' :read-only='true' separator='.'/>
                          </td>
                          <td class="align-middle">
                            <vue-numeric :read-only='!allow_edit_qty_disiapkan(part)' class="form-control" v-model='part.qty_disiapkan' :max='part.qty_picking' separator='.'/>
                          </td>
                          <td class='align-middle text-center'>
                            <span v-if='part.recheck == 1'>Re-check</span>
                            <span v-if='part.recheck == 0'>-</span>
                          </td>                   
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if='picking.status != "Closed PL" && status == "start"' :disabled='!sesuai_dengan_pembagian_paket_bundling || sum_qty_disiapkan === 0' class='btn btn-sm btn-flat btn-primary' @click.prevent='simpan'>Simpan</button>
                  <button v-if='picking.status != "Closed PL" && status == "start"' :disabled='!sesuai_dengan_pembagian_paket_bundling' class='btn btn-sm btn-flat btn-danger' @click.prevent='close'>Close</button>
                </div>
                <div class="col-sm-6 text-right">
                  <a class="btn btn-flat btn-sm btn-info" :href='"h3/h3_md_validasi_picking_list/cetak_picking_list?" + query_string_cetak_picking_list'>Cetak Picking List</a>
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
            start: <?= $this->input->get('status') != null ? 'true' : 'false'; ?>,
            picking: <?= json_encode($picking) ?>,
            <?php if($mode != 'tugaskan_picker'): ?>
            parts: <?= json_encode($parts) ?>
            <?php endif; ?>,
            status: '<?= $this->input->get('status') ?>',
            filters_lokasi: [],
            filters_part : [],
            filters_kelompok_part : [],
            samakan_qty_disiapkan: 0,
            paket_bundling: [],
          },
          mounted: function(){
            if(this.kategori_bundling){
              this.get_paket_bundling(this.picking.id_paket_bundling);
            }
          },
          methods: {
            simpan: function(){
              this.loading = true;
              post = _.pick(this.picking, ['id_picking_list', 'id_do_sales_order', 'kategori_po']);
              post.parts = _.map(this.parts, function(p){
                keys = ['id_part', 'qty_disiapkan', 'id_lokasi_rak','serial_number'];
                if(app.kategori_kpb){
                  keys.push('id_tipe_kendaraan');
                }
                return _.pick(p, keys);
              });

              axios.post('h3/<?= $isi ?>/simpan', Qs.stringify(post))
              .then(function(res){
                toastr.success(res.data.message);
              })
              .catch(function(err){
                toastr.error(err.response.data.message);
              })
              .then(function(){ app.loading = false; });
            },
            close: function(){
              this.loading = true;
              post = _.pick(this.picking, ['id_picking_list', 'id_do_sales_order', 'total', 'kategori_po']);
              post.buat_do_revisi = this.buat_do_revisi;
              post.parts = _.chain(this.parts)
              .filter(function(part){
                if(app.picking.status == 'Re-Check'){
                  return part.recheck == 1;
                }else{
                  return true;
                }
              })
              .value();

              axios.post('h3/<?= $isi ?>/close', Qs.stringify(post))
              .then(function(res){
                window.location = res.data;
              })
              .catch(function(err){
                toastr.error(err.response.data.message);
              })
              .then(function(){ app.loading = false; });
            },
            allow_edit_qty_disiapkan: function(part){
              if(this.picking.status == 'Re-Check'){
                return this.picking.status == "Re-Check" && this.status == "start" && part.recheck == 1;
              }else if(this.mode == 'detail' && !this.start){
                return false;
              }
              return true;
            },
            get_paket_bundling: function(id_paket_bundling){
              this.loading = true;
              axios.get('h3/h3_md_create_do_sales_order/get_paket_bundling', {
                params: {
                  id_paket_bundling: id_paket_bundling
                }
              })
              .then(function(res){
                app.paket_bundling = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              })
            },
          },
          watch: {
            samakan_qty_disiapkan: function(val){
              if(val == 1){
                this.parts = _.chain(this.parts)
                .filter(function(data){
                  data.qty_disiapkan = data.qty_picking;
                  return data;
                })
                .value();
              }else{
                this.parts = _.chain(this.parts)
                .filter(function(data){
                  data.qty_disiapkan = 0;
                  return data;
                })
                .value();
              }
            }
          },
          computed: {
            kategori_kpb: function(){
              return this.picking.kategori_po == 'KPB';
            },
            sum_qty_disiapkan: function(){
              sum_qty_disiapkan = 0;
              for (part of this.parts) {
                sum_qty_disiapkan += part.qty_disiapkan;
              }
              return sum_qty_disiapkan;
            },
            filtered_parts: function(){
              filters_lokasi = this.filters_lokasi;
              filters_part = this.filters_part;
              filters_kelompok_part = this.filters_kelompok_part;

              return _.chain(this.parts)
              .filter(function(data){
                if(filters_lokasi.length){
                  return filters_lokasi.includes(data.id_lokasi_rak);
                }
                return true;
              })
              .filter(function(data){
                if(filters_part.length){
                  return filters_part.includes(data.id_part);
                }
                return true;
              })
              .filter(function(data){
                if(filters_kelompok_part.length){
                  return filters_kelompok_part.includes(data.kelompok_part);
                }
                return true;
              })
              .value();
            },
            buat_do_revisi: function(){
              for (part of this.parts) {
                if(parseInt(part.qty_do) > parseInt(part.qty_disiapkan)){
                  return 1;
                }
              }
              return 0;
            },
            query_string_cetak_picking_list: function(){
              return $.param({
                id_picking_list: this.picking.id_picking_list,
                filters_lokasi: this.filters_lokasi,
                filters_part: this.filters_part,
                filters_kelompok_part: this.filters_kelompok_part,
              });
            },
            kategori_bundling: function(){
              return this.picking.kategori_po == 'Bundling H1';
            },
            sesuai_dengan_pembagian_paket_bundling: function(){
              if(this.paket_bundling.length < 1) return [];

              perhitungan_kelipatan =  _.chain(this.parts)
              .groupBy(function(row){
                return row.id_part;
              })
              .map(function(row, id_part){
                return {
                  'id_part': id_part,
                  'qty_disiapkan': _.sumBy(row, 'qty_disiapkan')
                };
              })
              .map(function(part){
                index = _.findIndex(app.paket_bundling, function(row){
                  return part.id_part == row.id_part;
                });

                if(index != -1){
                  part.kelipatan = part.qty_disiapkan / app.paket_bundling[index].qty_part;
                }else{
                  part.kelipatan = 0;
                }

                return _.pick(part, [
                  'id_part', 'qty_disiapkan', 'kelipatan'
                ]);
              })
              .value();

              kelipatan = perhitungan_kelipatan[0].kelipatan;
              
              mempunyai_kelipatan_sama = _.every(perhitungan_kelipatan, ['kelipatan', kelipatan]);

              return mempunyai_kelipatan_sama;
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-md-3">
              <div id='customer-filter' class="form-group">
                <label for="" class="control-label">Customer</label>
                <div class="input-group">
                  <input type="text" class="form-control" readonly :value='dealers.length + " customer"'>
                  <div class="input-group-btn">
                    <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_customer_filter_validation_picking_list'><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
              <script>
                customerFilter = new Vue({
                  el: '#customer-filter',
                  data: {
                    dealers: []
                  },
                  watch: {
                    dealers: {
                      deep: true,
                      handler: function(){
                        validasi_picking_list.draw();
                      }
                    }
                  }
                });
              </script>
              <?php $this->load->view('modal/h3_md_customer_filter_validation_picking_list'); ?>
              <script>
                $(document).ready(function(){
                  $("#h3_md_customer_filter_validation_picking_list").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_dealer = target.attr('data-id-dealer');

                    if(target.is(':checked')){
                      customerFilter.dealers.push(id_dealer);
                    }else{
                      index_id_dealer = _.indexOf(customerFilter.dealers, id_dealer);
                      customerFilter.dealers.splice(index_id_dealer, 1);
                    }
                    h3_md_customer_filter_validation_picking_list_datatable.draw();
                  });
                });
              </script>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">Tanggal Picking List</label>
                <input id='tanggal_picking_list_filter' type="text" class="form-control" readonly>
                <input id='tanggal_picking_list_filter_start' type="hidden" disabled>
                <input id='tanggal_picking_list_filter_end' type="hidden" disabled>
              </div>                
              <script>
                $('#tanggal_picking_list_filter').daterangepicker({
                  opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }).on('apply.daterangepicker', function(ev, picker) {
                  $('#tanggal_picking_list_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                  $('#tanggal_picking_list_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  validasi_picking_list.draw();
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#tanggal_picking_list_filter_start').val('');
                  $('#tanggal_picking_list_filter_end').val('');
                  validasi_picking_list.draw();
                });
              </script>
            </div>
          </div>
        </div>
        <table id="validasi_picking_list" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Nama Picker</th>              
              <th>Kategori PO</th>              
              <th>Tipe PO</th>              
              <th>Tanggal Picking List</th>              
              <th>No Picking List</th>              
              <th>Nama Customer</th>              
              <th>Alamat Customer</th>              
              <th>Start Pick</th>              
              <th>End Pick</th>              
              <th>Durasi</th>              
              <th>Status</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          date_render = function(data, type, row){
            if(data != null) {
              return moment(data).format('DD/MM/YYYY HH:mm');
            }
            return '-';
          }

          $(document).ready(function() {
            validasi_picking_list = $('#validasi_picking_list').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/validasi_picking_list') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.dealers = customerFilter.dealers;
                    d.tanggal_picking_list_filter_start = $('#tanggal_picking_list_filter_start').val();
                    d.tanggal_picking_list_filter_end = $('#tanggal_picking_list_filter_end').val();
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'nama_picker', width: '100px' },
                    { data: 'kategori_po' },
                    { data: 'po_type' },
                    { data: 'tanggal_picking' },
                    { data: 'id_picking_list', width: '180px' },
                    { data: 'nama_dealer', width: '300px' },
                    { data: 'alamat', width: '500px' },
                    { 
                      data: 'start_pick',
                      width: '150px',
                      render: date_render
                    },
                    { 
                      data: 'end_pick',
                      width: '150px',
                      render: date_render
                    },
                    { 
                      data: 'duration', 
                      render: function(data, type, row){
                        if(data != null) return humanizeDuration(data, { language: "id", round: true, delimiter: ' ' });
                        return '-';
                      },
                      width: '150px'
                    },
                    { data: 'status', width: '150px' },
                    { data: 'action', width: '150px', orderable: false, },
                ],
            });
          });
        </script>
        <?php $this->load->view('modal/h3_md_modal_view_picking_list'); ?>
        <script>
          function open_view_modal_picking_list(id_picking_list) {
            $('#h3_md_modal_view_picking_list').modal('show');
            h3_md_modal_view_picking_list_vue.picking.id_picking_list = id_picking_list;
            h3_md_modal_view_picking_list_vue.no_action = true;
            h3_md_modal_view_picking_list_vue.get_view_picking_list_data();
          }
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
