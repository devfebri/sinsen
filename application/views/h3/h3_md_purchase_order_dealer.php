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
            <form class='form-horizontal'>
              <div class="box-body">       
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.po_id'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.tanggal_order'>                    
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.nama_dealer'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.status'>                    
                  </div>    
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.kategori_po'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Produk</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.produk'>                    
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.po_type'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Periode</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.periode'>                    
                  </div> 
                </div>
                <div v-if='purchase_order_hotline' class="form-group">                  
                  <label class="col-sm-2 control-label">Booking Number</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.id_booking'>                    
                  </div>
                </div>
                <div v-if='purchase_order_urgent' class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor NRFS</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='purchase_order.dokumen_nrfs_id'>                    
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-responsive table-condensed">
                      <thead>
                        <tr>                                      
                          <th width='3%'>No.</th>              
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th>Kelompok</th>              
                          <th>Qty Order</th>
                          <th>HPP</th>
                          <th>Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>                       
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle">{{ part.kelompok_vendor }}</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.kuantitas"></vue-numeric>
                          </td>                       
                          <td width="8%" class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="part.harga_saat_dibeli" />
                          </td>                       
                          <td width="8%" class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                          </td>          
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="15">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                </div>
                <div class="col-sm-6 no-padding text-right">
                  <button class="btn btn-flat btn-sm btn-info" @click.prevent='proses'>Proses</button>
                  <button class="btn btn-flat btn-sm btn-danger" @click.prevent='reject'>Reject</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
    <script>
      Vue.use(VueNumeric.default);
      var app = new Vue({
          el: '#app',
          data: {
            loading: false,
            mode: '<?= $mode ?>',
            purchase_order: <?= json_encode($purchase_order) ?>,
            parts: <?= json_encode($parts) ?>,
          },
          methods: {
            proses: function(){
              this.loading = true;
              axios.get('h3/h3_md_purchase_order_dealer/proses', {
                params: {
                  id: this.purchase_order.po_id
                }
              })
              .then(function(res){
                window.location = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; })
            },
            ppn: function(part) {
              return (10 / 100) * part.harga_saat_dibeli ;
            },
            sub_total: function(part) {
              return (part.kuantitas * part.harga_saat_dibeli ) + this.ppn(part);
            },
          },
          computed: {
            purchase_order_hotline: function(){
              return this.purchase_order.po_type == 'HLO';
            },
            purchase_order_reguler: function(){
              return this.purchase_order.po_type == 'REG';
            },
            purchase_order_urgent: function(){
              return this.purchase_order.po_type == 'URG';
            },
            purchase_order_fix: function(){
              return this.purchase_order.po_type == 'FIX';
            },
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <table id="purchase_dari_dealer" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th>No.</th>              
              <th>Purchase Order</th>              
              <th>Tanggal PO</th>              
              <th>Dealer</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
          $(document).ready(function() {
            purchase_dari_dealer = $('#purchase_dari_dealer').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/purchase_dari_dealer') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null, orderable: false, width: '3%' },
                    { data: 'po_id' },
                    { data: 'tanggal_order' },
                    { data: 'nama_dealer' },
                    { data: 'status' },
                    { data: 'action', width: '3%', orderable: false, },
                ],
            });

            purchase_dari_dealer.on('draw.dt', function() {
              var info = purchase_dari_dealer.page.info();
              purchase_dari_dealer.column(0, {
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
