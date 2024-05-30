<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
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

      if ($mode == 'upload') {
        $form = 'inject';
      }

      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
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
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Vendor</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='purchase.id_vendor'>
                      <div class="input-group-btn">
                        <button type='button' v-if='!ada_vendor || mode == "detail"' :disabled='mode == "detail"' data-toggle='modal' data-target='#h3_md_vendor_po_umum' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        <button type='button' v-if='ada_vendor && mode != "detail"' @click.prevent='hapus_vendor' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_vendor_po_umum') ?>
                <script>
                  function pilih_vendor_po_umum(data){
                    app.purchase.id_vendor = data.id_vendor;
                  }
                </script>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Divisi</label>
                  <div v-bind:class="{ 'has-error': error_exist('divisi') }" class="col-sm-4">                    
                    <input type="text" :readonly='mode == "detail"' class="form-control" v-model='purchase.divisi'>
                    <small v-if="error_exist('divisi')" class="form-text text-danger">{{ get_error('divisi') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Dibuat Oleh</label>
                  <div v-bind:class="{ 'has-error': error_exist('dibuat_oleh') }" class="col-sm-4">                    
                    <input type="text" :readonly='mode == "detail"' class="form-control" v-model='purchase.dibuat_oleh'>
                    <small v-if="error_exist('dibuat_oleh')" class="form-text text-danger">{{ get_error('dibuat_oleh') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Diketahui Oleh</label>
                  <div v-bind:class="{ 'has-error': error_exist('diketahui_oleh') }" class="col-sm-4">                    
                    <input type="text" :readonly='mode == "detail"' class="form-control" v-model='purchase.diketahui_oleh'>
                    <small v-if="error_exist('diketahui_oleh')" class="form-text text-danger">{{ get_error('diketahui_oleh') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Disetujui Oleh</label>
                  <div v-bind:class="{ 'has-error': error_exist('disetujui_oleh') }" class="col-sm-4">                    
                    <input type="text" :readonly='mode == "detail"' class="form-control" v-model='purchase.disetujui_oleh'>
                    <small v-if="error_exist('disetujui_oleh')" class="form-text text-danger">{{ get_error('disetujui_oleh') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label no-padding">Dana Talangan</label>
                  <div v-bind:class="{ 'has-error': error_exist('dana_talangan') }" class="col-sm-4">                    
                    <input type="checkbox" :disabled='mode == "detail"' true-value='1' false-value='0' v-model='purchase.dana_talangan'>
                    <small v-if="error_exist('dana_talangan')" class="form-text text-danger">{{ get_error('dana_talangan') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div v-bind:class="{ 'has-error': error_exist('keterangan') }" class="col-sm-4">                    
                    <textarea :readonly='mode == "detail"' rows="5" class="form-control" v-model='purchase.keterangan'></textarea>
                    <small v-if="error_exist('keterangan')" class="form-text text-danger">{{ get_error('keterangan') }}</small>
                  </div>
                </div>
                <div v-if='mode == "detail"' class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase.status'>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table class="table table-hover table-responsive">
                      <tr>
                        <th width='3%'>No.</th>
                        <th>Nama Penanggung</th>
                        <th>Dana yang ditanggung</th>
                        <th v-if='mode != "detail"' width='3%'></th>
                      </tr>
                      <tr v-if='penanggung.length > 0' v-for="(item, index) in penanggung">
                        <td width='3%'>{{ index + 1 }}.</td>
                        <td>{{ item.nama_penanggung }}</td>
                        <td>
                          <vue-numeric class="form-control" separator='.' currency='Rp' v-model='item.dana_yang_ditanggung' read-only></vue-numeric>
                        </td>
                        <td v-if='mode != "detail"' width='3%'>
                          <button @click.prevent='hapus_penanggung(index)' class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                      <tr v-if='penanggung.length < 1'>
                        <td colspan='4' class='text-center'>Tidak ada data</td>
                      </tr>
                      <tr v-if='mode != "detail"'>
                        <td width='3%'></td>
                        <td>
                          <div class="input-group">
                            <input type="text" class="form-control" v-model='item_penanggung.nama_penanggung'>
                            <div class="input-group-btn">
                              <button type='button' data-toggle='modal' data-target='#h3_md_dealer_po_umum' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                          </div>
                        </td>
                        <td>
                          <vue-numeric class="form-control" separator='.' currency='Rp' v-model='item_penanggung.dana_yang_ditanggung'></vue-numeric>
                        </td>
                        <td width='3%'>
                          <button @click.prevent='add_item_penanggung' class="btn btn-sm btn-flat btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_dealer_po_umum'); ?>
                <script>
                  function pilih_dealer_po_umum(data){
                    app.item_penanggung.id_dealer = data.id_dealer;
                    app.item_penanggung.nama_penanggung = data.kode_dealer_md + ' - ' + data.nama_dealer;
                  }
                </script>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Nama Barang</th>              
                          <th>Kuantitas</th>              
                          <th>Harga Satuan</th>              
                          <th class='text-right'>Total</th>              
                          <th v-if='mode != "detail"' width='3%'></th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-if='parts.length > 0' v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.nama_barang }}</td>                       
                          <td class="align-middle">{{ part.kuantitas }}</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.harga" />
                          </td>  
                          <td class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." :value='sub_total(part)' />
                          </td>                       
                          <td v-if='mode != "detail"'>
                            <button @click.prevent='hapus_part(index)' class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td class="text-right" colspan="4">Grand Total</td>
                          <td class="text-right">
                            <vue-numeric separator='.' v-model='grand_total' read-only currency='Rp'></vue-numeric>
                          </td>
                          <td v-if='mode != "detail"'></td>
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="6">Belum ada part</td>
                        </tr>
                        <tr v-if='mode != "detail"'> 
                          <td class="align-middle"></td>                       
                          <td class="align-middle">
                            <input type="text" class="form-control" placeholder='Nama barang' v-model='item.nama_barang'>
                          </td>                       
                          <td class="align-middle">
                            <vue-numeric class='form-control' thousand-separator="." v-model="item.kuantitas" />
                          </td>                       
                          <td class="align-middle">
                            <vue-numeric class='form-control' currency="Rp " thousand-separator="." v-model="item.harga" />
                          </td>  
                          <td class="align-middle">
                            <vue-numeric read-only currency="Rp " thousand-separator="." v-model='total_item' />
                          </td>                       
                          <td>
                            <button @click.prevent='add_item' class="btn btn-sm btn-flat btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
                <div class="container-fluid">
                    <div class="row">
                      <div class="col-sm-6 no-padding">
                        <button v-if='mode == "edit"' class="btn btn-flat btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                        <button v-if='mode == "insert"' class="btn btn-flat btn-primary btn-sm" @click.prevent='<?= $form ?>'>Simpan</button>
                        <a v-if='mode == "detail" && purchase.status != "Approved"' :href="'h3/h3_md_po_umum/edit?id_purchase_order=' + purchase.id_purchase_order" class="btn btn btn-flat btn-sm btn-warning">Edit</a>
                      </div>
                      <div class="col-sm-6 text-right">
                        <button v-if='mode == "detail" && purchase.status == "Open"' @click.prevent='approve' class="btn btn-flat btn-sm btn-success">Approve</button>
                        <button v-if='mode == "detail" && purchase.status == "Open"' @click.prevent='cancel' class="btn btn-flat btn-sm btn-danger">Cancel</button>
                      </div>
                    </div>
                </div>
              </div><!-- /.box-body -->
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
            errors: [],
            <?php if($mode == 'detail' || $mode == 'edit'): ?>
            purchase: <?= json_encode($purchase) ?>,
            parts: <?= json_encode($parts) ?>,
            penanggung: <?= json_encode($penanggung) ?>,
            <?php else: ?>
            purchase: {
              id_vendor: '',
              divisi: '',
              dibuat_oleh: '',
              diketahui_oleh: '',
              disetujui_oleh: '',
              dana_talangan: 0,
              keterangan: '',
            },
            parts: [],
            penanggung: [],
            <?php endif; ?>
            item_penanggung: {
              id_dealer: '',
              nama_penanggung: '',
              dana_yang_ditanggung: 0,
            },
            item: {
              nama_barang: '',
              kuantitas: '',
              harga: 0,
            }
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.purchase, [
                'id_purchase_order', 'id_vendor', 'divisi', 'dibuat_oleh', 'diketahui_oleh',
                'disetujui_oleh', 'dana_talangan', 'keterangan',
              ]);
              post.grand_total = this.grand_total;

              post.penanggung = this.penanggung;

              sub_total_fn = this.sub_total;
              post.parts = _.chain(this.parts)
              .map(function(part){
                part.sub_total = sub_total_fn(part);
                return part;
              })
              .value();

              this.loading = true;
              this.errors = [];
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_purchase_order=' + res.data.id_purchase_order;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
              })
              .then(function(){ app.loading = false; });
            },
            approve: function(){
              axios.get('h3/<?= $isi ?>/approve', {
                params: {
                  id_purchase_order: this.purchase.id_purchase_order
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_purchase_order=' + res.data.id_purchase_order;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
              })
              .then(function(){ app.loading = false; });
            },
            cancel: function(){
              axios.get('h3/<?= $isi ?>/cancel', {
                params: {
                  id_purchase_order: this.purchase.id_purchase_order
                }
              })
              .then(function(res){
                window.location = 'h3/<?= $isi ?>/detail?id_purchase_order=' + res.data.id_purchase_order;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.errors = data.errors;
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
              })
              .then(function(){ app.loading = false; });
            },
            hapus_part: function(index){
              this.parts.splice(index, 1);
            },
            hapus_penanggung: function(index){
              this.penanggung.splice(index, 1);
            },
            hapus_vendor: function(){
              this.purchase.id_vendor = '';
            },
            add_item: function(){
              if(this.item.nama_barang == ''){
                toastr.warning('Nama barang belum diisi');
                return;
              }

              if(this.item.kuantitas < 1){
                toastr.warning('Tidak boleh kuantitas 0');
                return;
              }

              this.parts.push(this.item);
              this.item = {
                nama_barang: '',
                kuantitas: 0,
                harga: 0,
              };
            },
            add_item_penanggung: function(){
              if(this.item_penanggung.nama_penanggung == '' || this.item_penanggung.nama_penanggung == null){
                toastr.warning('Nama penanggung belum diisi.');
                return;
              }

              this.penanggung.push(this.item_penanggung);
              this.item_penanggung = {
                id_dealer: '',
                nama_penanggung: '',
                dana_yang_ditanggung: 0,
              };
            },
            sub_total: function(part){
              return parseFloat(part.harga) * parseFloat(part.kuantitas);
            },
            error_exist: function(key){
              return _.has(this.errors, key);
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            total_item: function(){
              return parseFloat(this.item.harga) * parseFloat(this.item.kuantitas);
            },
            ada_vendor: function(){
              return this.purchase.id_vendor != '' && this.purchase.id_vendor != null;
            },
            grand_total: function(){
              sub_total_fn = this.sub_total;
              return _.sumBy(this.parts, function(part){
                return sub_total_fn(part);
              });
            }
          },
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
        </h3>
      </div>
      <div class="box-body">
        <table id="po_umum_datatable" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. PO</th>              
              <th>Tanggal Entry</th>              
              <th>Nama Vendor</th>              
              <th>Divisi</th>              
              <th>Nominal</th>              
              <th>Keterangan</th>              
              <th width="10%"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          po_umum_datatable = $('#po_umum_datatable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/po_umum') ?>",
                dataSrc: "data",
                type: "POST"
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { data: 'id_purchase_order' }, 
                { 
                  data: 'created_at',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { data: 'vendor_name' }, 
                { data: 'divisi' }, 
                { 
                  data: 'grand_total',
                  render: function(data){
                    return accounting.formatMoney(data, 'Rp ', ',', '.');
                  }
                }, 
                { data: 'keterangan' }, 
                { data: 'action', orderable: false, width: '3%', className: 'text-center' }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>