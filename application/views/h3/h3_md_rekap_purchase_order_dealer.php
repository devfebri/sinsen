x<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/sweet_alert.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
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
              <div class="box-body">       
                <div class="form-group">
                  <label for="" class="col-sm-2 control-label">Tipe PO</label>
                  <div v-bind:class="{ 'has-error': error_exist('tipe_po') }" class="col-sm-4">
                    <select :disabled='mode == "detail"' class="form-control" v-model='rekap.tipe_po'>
                      <option value="">Choose</option>
                      <option value="URG">Urgent</option>
                      <option value="HLO">Hotline</option>
                    </select>
                    <small v-if="error_exist('tipe_po')" class="form-text text-danger">{{ get_error('tipe_po') }}</small>
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode customer</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">                    
                    <div class="input-group">
                      <input v-model='rekap.kode_dealer_md' type="text" readonly class="form-control">                    
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_rekap_purchase_order_dealer'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                  </div>                                
                  <?php $this->load->view('modal/h3_md_dealer_rekap_purchase_order_dealer'); ?>
                  <script>
                    function pilih_dealer_rekap_purchase_order_dealer(data) {
                      app.rekap.id_dealer = data.id_dealer;
                      app.rekap.kode_dealer_md = data.kode_dealer_md;
                      app.rekap.nama_dealer = data.nama_dealer;
                    }
                  </script>
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">                    
                    <input v-model='rekap.nama_dealer' type="text" readonly class="form-control">
                    <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>                    
                  </div>   
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table">
                      <thead>
                        <tr class='bg-blue-gradient'>                                      
                          <th width='3%'>No.</th>              
                          <th>No. Purchase Order</th>              
                          <th v-if='mode != "detail"' width='3%'></th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(item, index) in items"> 
                          <td class="align-middle" width='3%'>{{ index + 1 }}.</td>
                          <td class="align-middle">{{ item.id_referensi }}</td>                       
                          <td v-if='mode != "detail"' width="3%" class="align-middle text-right">
                            <button class="btn btn-flat btn-danger" @click.prevent='hapus_item(index)'><i class="fa fa-trash-o"></i></button>
                          </td>                              
                        </tr>
                        <tr v-if="items.length < 1">
                          <td class="text-center" colspan="3">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
                <div v-if='mode != "detail"' class="container-fluid">
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <button class="btn btn-flat btn-primary margin" type='button' data-toggle='modal' data-target='#h3_md_purchase_order_logistik_rekap_purchase_order_dealer'><i class="fa fa-plus"></i></button>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_purchase_order_logistik_rekap_purchase_order_dealer'); ?>
                <script>
                  function pilih_purchase_order_logistik_rekap_purchase_order_dealer(data){
                    app.items.push({
                      id_referensi: data.po_id
                    });
                  }
                </script>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table">
                      <thead>
                        <tr class='bg-blue-gradient'>                                      
                          <th width='3%'>No.</th>              
                          <th>Kode Part</th>              
                          <th>Nama Part</th>              
                          <th>Kuantitas</th>              
                          <th width='3%'></th>              
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in grouped_parts"> 
                          <td class="align-middle" width='3%'>{{ index + 1 }}.</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle">
                            <vue-numeric class="form-control" v-model='part.kuantitas' separator='.' read-only></vue-numeric>
                          </td>                       
                        </tr>
                        <tr v-if="items.length < 1">
                          <td class="text-center" colspan="3">Tidak ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
                <div class="col-sm-12 text-right">
                  <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-success" @click.prevent='get_parts'>Perbarui Part</button>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if='mode == "insert"' class="btn btn-flat btn-primary btn-sm" @click.prevent='<?= $form ?>'>Simpan</button>
                  <button v-if='mode == "edit"' class="btn btn-flat btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                  <a v-if='mode == "detail" && rekap.id_sales_order == null' :href="'h3/h3_md_rekap_purchase_order_dealer/edit?id=' + rekap.id" class="btn btn-flat btn-warning btn-sm">Edit</a>
                </div>
                <div class="col-sm-6 no-padding text-right">  
                  <a v-if='mode == "detail" && rekap.id_sales_order == null' :href="'h3/h3_md_sales_order/add?id_rekap_purchase_order_dealer=' + rekap.id + '&generateByRekapPurchaseOrder=true'" class="btn btn-flat btn-info btn-sm">Create Sales Order</a>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  <script>
      app = new Vue({
          el: '#app',
          mounted: function(){
            if(this.mode == "detail" || this.mode == "edit"){
              // this.get_parts();
            }
          },
          data: {
            loading: false,
            errors: {},
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' || $mode == 'edit'): ?>
            rekap: <?= json_encode($rekap) ?>,
            items: <?= json_encode($items) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            rekap: {
              kode_dealer_md: '',
              nama_dealer: '',
              id_dealer: '',
              tipe_po: '',
            },
            items: [],
            parts: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              post = _.pick(this.rekap, ['id_dealer', 'id', 'tipe_po']);
              post.items = _.chain(this.items)
              .map(function(data){
                return _.pick(data, ['id_referensi']);
              })
              .value();

              post.parts = _.chain(this.parts)
              .map(function(part){
                return _.pick(part, ['po_id', 'id_part', 'kuantitas']);
              })
              .value();

              this.errors = {};
              this.loading = true;
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                window.location = 'h3/h3_md_rekap_purchase_order_dealer/detail?id=' + res.data.id;
              })
              .catch(function(err){
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
            get_parts: function(){
              this.loading = true;

              post = {};
              post.items = _.chain(this.items)
              .map(function(data){
                return data.id_referensi;
              })
              .value();

              axios.post('h3/<?= $isi ?>/get_parts', Qs.stringify(post))
              .then(function(res){
                app.parts = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            hapus_item: function(index){
              this.items.splice(index, 1);
            },
            reset_items: function(){
              this.items = [];
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            },
          },
          computed: {
            grouped_parts: function(){
              return _.chain(this.parts)
              .groupBy('id_part')
              .map(function(group, id_part){
                return {
                  id_part: id_part,
                  kuantitas: _.sumBy(group, function(data){
                    return parseInt(data.kuantitas);
                  }),
                  nama_part: group[0].nama_part
                }
              })
              .value();
            }
          },
          watch: {
            items: {
              deep: true,
              handler: function(){
                this.get_parts();
                h3_md_purchase_order_logistik_rekap_purchase_order_dealer_datatable.draw();
              }
            },
            'rekap.id_dealer': function(){
              this.reset_items();
              h3_md_purchase_order_logistik_rekap_purchase_order_dealer_datatable.draw();
            },
            'rekap.tipe_po': function(){
              this.reset_items();
              h3_md_purchase_order_logistik_rekap_purchase_order_dealer_datatable.draw();
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
            <button class="btn btn-primary btn-flat margin">Add New</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="pemenuhan_po_dealer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th>No.</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Tanggal Dibuat</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            pemenuhan_po_dealer = $('#pemenuhan_po_dealer').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/rekap_purchase_order_dealer') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'kode_dealer_md' },
                    { data: 'nama_dealer' },
                    { data: 'created_at' },
                    { data: 'action', width: '3%', orderable: false, },
                ],
            });

            pemenuhan_po_dealer.on('draw.dt', function() {
              var info = pemenuhan_po_dealer.page.info();
              pemenuhan_po_dealer.column(0, {
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