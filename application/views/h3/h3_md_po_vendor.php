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
<body onload="auto()">
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
        $form= 'detail';
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
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">
                <div v-if='mode != "insert"' class="form-group">
                  <label for="" class="control-label col-sm-2">Tanggal</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='po_vendor.tanggal'>  
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Vendor</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">
                    <div class="input-group">
                      <input v-model="po_vendor.vendor_name" type="text" class="form-control" readonly>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_vendor_po_vendor'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>  
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_vendor_po_vendor'); ?>
                <script>
                  function pilih_po_vendor(vendor) {
                    app.po_vendor.id_vendor = vendor.id_vendor;
                    app.po_vendor.vendor_name = vendor.vendor_name;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div v-bind:class="{ 'has-error': error_exist('keterangan') }" class="col-sm-4">                    
                    <textarea rows="3" class="form-control" v-model="po_vendor.keterangan" :readonly="mode == 'detail'"></textarea>
                    <small v-if="error_exist('keterangan')" class="form-text text-danger">{{ get_error('keterangan') }}</small>  
                  </div>
                </div>
                <div v-if='mode != "insert"' class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model='po_vendor.status'>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No</th>
                          <th>Kode Part</th>              
                          <th>Nama Part</th>              
                          <th width="10%">Qty On Hand</th>              
                          <th width="10%">Qty AVG Sales</th>
                          <th width="10%">Qty Order</th>
                          <th width="10%" class='text-center'>HPP</th>
                          <th v-if="mode != 'detail'" width="3%"></th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_on_hand"></vue-numeric>
                          </td>            
                          <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avg_sales"></vue-numeric>
                          </td>  
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                          </td>  
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " class="form-control" separator="." :empty-value="1" v-model="part.harga"></vue-numeric>
                          </td>
                          <td v-if="mode != 'detail'" class="align-middle">
                            <button class="btn btn-flat btn-danger" v-on:click.prevent="hapus_part(index)"><i class="fa fa-trash-o"></i></button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td class="text-right" colspan="6">Grand Total</td>
                          <td class="text-right">
                            <vue-numeric read-only v-model='total' separator='.' currency='Rp'></vue-numeric>
                          </td>
                          <td></td>
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="7">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
                <div v-if="mode != 'detail' && mode != 'terima_claim'" class="form-group">
                  <div class="col-sm-12 text-right">
                    <button type="button" class="btn btn-flat btn-primary" data-toggle="modal" data-target="#h3_md_parts_po_vendor"><i class="fa fa-plus"></i></button>
                  </div>
                  <?php $this->load->view('modal/h3_md_parts_po_vendor'); ?>
                  <script>
                    function pilih_parts_po_vendor(part) {
                      app.parts.push(part);
                    }
                  </script>
                </div> 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode == 'insert'" :disabled='loading' class="btn btn-flat btn-primary btn-sm" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" :disabled='loading' class="btn btn-flat btn-warning btn-sm" @click.prevent="<?= $form ?>">Update</button>
                  <a v-if='mode == "detail" && po_vendor.status == "Open"' class="btn btn-flat btn-warning btn-sm" :href="'h3/<?= $isi ?>/edit?id_po_vendor=' + po_vendor.id_po_vendor">Edit</a>
                </div>
                <div class="col-sm-6 text-right">
                  <a v-if='mode == "detail"' class="btn btn-flat btn-info btn-sm" :href="'h3/<?= $isi ?>/cetak?id_po_vendor=' + po_vendor.id_po_vendor">Cetak</a>
                  <button v-if='mode == "detail" && po_vendor.status == "Processed"' @click.prevent='close' class="btn btn-flat btn-danger btn-sm">Close</button>
                  <button v-if='mode == "detail" && po_vendor.status == "Open"' @click.prevent='proses' class="btn btn-flat btn-success btn-sm">Proses</button>
                  <button v-if='mode == "detail" && po_vendor.status == "Open"' @click.prevent='cancel' class="btn btn-flat btn-danger btn-sm">Cancel</button>
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
            errors: {},
            loading: false,
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            po_vendor: <?= json_encode($po_vendor) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            po_vendor: {
              id_vendor: '',
              vendor_name: '',
              keterangan: '',
            },
            parts: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.po_vendor, [
                'id_po_vendor', 'id_vendor', 'keterangan'
              ]);
              post.total = this.total;
              post.parts = this.parts;

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function (res) {
                window.location = 'h3/<?= $isi ?>/detail?id_po_vendor=' + res.data.id_po_vendor
              })
              .catch(function (err) {
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
              })
              .then(function(){ app.loading = false; });
            },
            close: function(){
              confirmed = confirm('Apakah anda yakin ingin men-close PO Vendor ini? Tindakan ini tidak dapat dibatalkan!')

              this.loading = true;

              axios.get('h3/h3_md_po_vendor/close', {
                params: {
                  id_po_vendor: this.po_vendor.id_po_vendor
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_po_vendor=' + res.data.id_po_vendor
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = true; });
            },
            proses: function(){
              confirmed = confirm('Apakah anda yakin ingin memproses PO Vendor ini?')

              this.loading = true;
              axios.get('h3/h3_md_po_vendor/proses', {
                params: {
                  id_po_vendor: this.po_vendor.id_po_vendor
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_po_vendor=' + res.data.id_po_vendor
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = true; });
            },
            cancel: function(){
              confirmed = confirm('Apakah anda yakin ingin membatalkan PO Vendor ini?')

              this.loading = true;
              axios.get('h3/h3_md_po_vendor/cancel', {
                params: {
                  id_po_vendor: this.po_vendor.id_po_vendor
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_po_vendor=' + res.data.id_po_vendor
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = true; });
            },
            sub_total: function(part) {
              return parseInt(part.qty_order) * parseFloat(part.harga);
            },
            hapus_part: function(index) {
              this.parts.splice(index, 1);
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            total: function(){
              sub_total_fn = this.sub_total;
              return _.sumBy(this.parts, function(part){
                return sub_total_fn(part);
              });
            },
          },
          watch: {
            parts: {
              deep: true,
              handler: function(){
                h3_md_parts_po_vendor_datatable.draw();
              }
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
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
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="po_vendor" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No</th>              
              <th>Tanggal</th>              
              <th>No PO</th>              
              <th>Nama Vendor</th>              
              <th>Total Amount</th>              
              <th>Status</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          po_vendor = $('#po_vendor').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/po_vendor_md') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
                { data: null, orderable: false, width: '3%' },
                { data: 'tanggal' }, 
                { data: 'id_po_vendor' }, 
                { data: 'vendor_name' }, 
                { data: 'total' }, 
                { data: 'status' }
            ],
          });

          po_vendor.on('draw.dt', function() {
            var info = po_vendor.page.info();
              po_vendor.column(0, {
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
    <?php endif; ?>
  </section>
</div>