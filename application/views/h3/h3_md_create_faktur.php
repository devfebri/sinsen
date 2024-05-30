<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
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
        $disabled = 'disabled';
        $form = 'save_do';
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
        <?php if($do_sales_order['selesai_scan'] == 0): ?>
        <div class="alert alert-warning alert-dismissable">
          <strong>Perhatian</strong> Transaksi belum selesai dilakukan scanning parts, mohon selesaikan proses tersebut terlebih dahulu
          <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>  
          </button>
        </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal">
              <div class="box-body">    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.tanggal_do'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.nama_dealer'>                    
                  </div>  
                </div>    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor DO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='get_id_do_sales_order(do_sales_order)'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.kode_dealer'>                    
                  </div>      
                </div>      
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.tanggal_so'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.alamat'>                    
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.id_sales_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' v-model='do_sales_order.plafon' separator='.'></vue-numeric>                 
                  </div>      
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">TOP</label>
                  <div class="col-sm-4">                    
                    <input v-if='do_sales_order.top != "" && do_sales_order.top != null' type="text" readonly class="form-control" :value='moment(do_sales_order.top).format("DD/MM/YYYY")'/>
                    <input v-if='do_sales_order.top == "" || do_sales_order.top == null' type="text" readonly class="form-control" value='-'/>
                  </div>                                
                  <label class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' v-model='do_sales_order.plafon_booking' separator='.'></vue-numeric>                 
                  </div> 
                </div> 
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Name Salesman</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.nama_salesman'>                    
                  </div>     
                  <label class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric readonly class="form-control" currency='Rp' v-model='sisa_plafon' separator='.'></vue-numeric>                 
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.po_type'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.kategori_po'>                    
                  </div>      
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.status'>                    
                  </div>                                
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-condensed table-responsive">
                      <thead>
                        <tr class='bg-blue-gradient'>                                      
                          <th width='3%'>No.</th>              
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th v-if='kategori_kpb'>Tipe Kendaraan</th>
                          <th class='text-right'>HET</th>              
                          <th class='text-right'>Qty</th>
                          <th class='text-right'>Diskon Satuan Dealer</th>
                          <th class='text-right'>Diskon Campaign</th>
                          <th class='text-right'>Harga Setelah Diskon</th>
                          <th class='text-right'>Amount</th>
                          <th class='text-right'>Harga Beli</th>
                          <th class='text-right'>Selisih</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <td class="align-top">{{ index + 1 }}.</td>                       
                          <td class="align-top">{{ part.id_part }}</td>                       
                          <td class="align-top">{{ part.nama_part }}</td>                       
                          <td v-if='kategori_kpb' class="align-top">{{ part.id_tipe_kendaraan }}</td>                       
                          <td class="align-top text-right">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga_jual"/>
                          </td>
                          <td class="align-top text-right">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_supply"/>
                          </td>
                          <td class="align-top text-right">
                            <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='convert_diskon_ke_rupiah(part.tipe_diskon_satuan_dealer, part.diskon_satuan_dealer, part.harga_jual)'/>
                          </td> 
                          <td class="align-top text-right">
                            <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='convert_diskon_ke_rupiah(part.tipe_diskon_campaign, part.diskon_campaign, part.harga_jual)'/>
                          </td> 
                          <td class="align-top text-right">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part)"/>
                          </td>   
                          <td class="align-top text-right">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="amount(part)"/>
                          </td>  
                          <td class="align-top text-right">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga_beli"/>
                          </td> 
                          <td class="align-top text-right">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part) - part.harga_beli"/>
                          </td>    
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td class="text-right" :colspan="sub_total_colspan">Sub Total</td>
                          <td class="text-right" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0 && do_sales_order.check_ppn_tools == 1">
                          <td class="text-right" :colspan="sub_total_colspan">PPN</td>
                          <td class="text-right" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="do_sales_order.total_ppn" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td colspan='2' class='align-middle'>Total Insentif</td>
                          <td class='align-middle'>
                            <vue-numeric :read-only="true" class="form-control" separator="."/>
                          </td>
                          <td :colspan='insentif_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Diskon Insentif</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="do_sales_order.check_diskon_insentif == 0 || mode == 'detail'" class="form-control" separator="." v-model="do_sales_order.diskon_insentif" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td :colspan='total_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Cashback Langsung</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="do_sales_order.check_diskon_cashback == 0 || mode == 'detail'" class="form-control" separator="." v-model="do_sales_order.diskon_cashback_otomatis" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td :colspan='total_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="do_sales_order.check_diskon_cashback == 0 || mode == 'detail'" class="form-control" separator="." v-model="do_sales_order.diskon_cashback" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="parts.length > 0">
                          <td :colspan='total_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Total</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency='Rp'/>
                          </td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                               
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <a :disabled='do_sales_order.selesai_scan == 0' v-if='do_sales_order.faktur_created == 0' onclick='return confirm("Apakah anda yakin ingin membuat faktur? Aksi ini tidak dapat dibatalkan.")' :href="'h3/h3_md_create_faktur/create_faktur?id=' + do_sales_order.id_do_sales_order" class="btn btn-info btn-sm btn-flat">Create Faktur</a>
                </div>
                <div class="col-sm-6 text-right">
                  <a :disabled='do_sales_order.selesai_scan == 0' v-if='do_sales_order.faktur_created == 1 && do_sales_order.faktur_printed == 0' :href="'h3/h3_md_create_faktur/cetak?id=' + do_sales_order.id_do_sales_order" class='btn btn-flat btn-sm btn-info'>Print Faktur</a>
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
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            do_sales_order: <?= json_encode($do_sales_order) ?>,
            parts: <?= json_encode($do_sales_order_parts) ?>,
            <?php else: ?>
            do_sales_order: {},
            parts: [],
            <?php endif; ?>
          },
          methods: {
            approve: function(status){
              this.loading = true;
              post = {};
              post = _.pick(this.do_sales_order, ['id_do_sales_order', 'check_diskon_insentif', 'diskon_insentif', 'check_diskon_cashback', 'diskon_cashback', 'id_dealer']);
              post.total = this.total;
              post.parts = _.map(this.parts, function(part){
                return _.pick(part, ['id_part', 'qty_supply']);
              });

              axios.post("h3/h3_md_do_sales_order/approve", Qs.stringify(post))
              .then(function(res){  
                window.location = 'h3/h3_md_do_sales_order/detail?id=' + res.data.id_do_sales_order;
              })
              .catch(function(err){ toastr.error(err); })
              .then(function(){ app.loading = false; });
            },
            reject: function(status){
              this.loading = true;
              post = {};
              post.id_do_sales_order = this.do_sales_order.id_do_sales_order;
              post.alasan_reject = $('#alasan_reject').val();
              post.total = this.total;
              axios.post("h3/h3_md_do_sales_order/reject", Qs.stringify(post))
              .then(function(res){  
                window.location = 'h3/h3_md_do_sales_order/detail?id=' + res.data.id_do_sales_order;
              })
              .catch(function(err){ toastr.error(err); })
              .then(function(){ app.loading = false; });
            },
            harga_setelah_diskon: function(part){
              harga_setelah_diskon = parseFloat(part.harga_jual);
              harga_setelah_diskon -= this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, harga_setelah_diskon);

              if(part.additional_discount == 1){
                harga_setelah_diskon -= this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, harga_setelah_diskon);
              }else{
                harga_setelah_diskon -= this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_jual);
              }

              return harga_setelah_diskon;
            },
            calculate_discount: function(discount, tipe_diskon, price) {
              if(tipe_diskon == 'Persen'){
                if(discount == 0) return 0; 

                return discount = (discount/100) * price;
              }else if(tipe_diskon == 'Rupiah'){
                return discount;
              }
              return 0;
            },
            amount: function(part) {
              return this.harga_setelah_diskon(part) * part.qty_supply
            },
            convert_diskon_ke_rupiah: function(tipe_diskon, diskon_value, harga){
              if(tipe_diskon == 'Rupiah') return diskon_value;

              diskon = (diskon_value/100) * harga;
              return diskon;
            },
            get_id_do_sales_order: function(do_sales_order){
              id_do_sales_order = do_sales_order.id_do_sales_order;

              if(do_sales_order.sudah_revisi == 1){
                id_do_sales_order += '-REV';
              }

              return id_do_sales_order;
            }
          },
          computed: {
            kategori_kpb: function(){
              return this.do_sales_order.kategori_po == 'KPB';
            },
            sub_total_colspan: function(){
              colspan = 8;
              if(this.kategori_kpb){
                colspan += 1;
              }
              return colspan;
            },
            insentif_colspan: function(){
              colspan = 4;
              if(this.kategori_kpb){
                colspan += 1;
              }
              return colspan;
            },
            total_colspan: function(){
              colspan = 7;
              if(this.kategori_kpb){
                colspan += 1;
              }
              return colspan;
            },
            total_diskon_parts: function(){
              harga_setelah_diskon_fn = this.harga_setelah_diskon;
              return _.chain(this.parts)
              .sumBy(function(part){
                return (part.harga_jual - harga_setelah_diskon_fn(part)) * part.qty_supply;
              })
              .value();
            },
            sub_total: function(){
              total = 0;
              for (index = 0; index < this.parts.length; index++) {
                part = this.parts[index];
                total += this.amount(part);
              }
              return total;
            },
            total_diskon_insentif_cashback: function(){
              return this.do_sales_order.diskon_insentif + (this.do_sales_order.diskon_cashback + this.do_sales_order.diskon_cashback_otomatis);
            },
            total: function(){
              // return this.sub_total - this.total_diskon_insentif_cashback;
              return this.do_sales_order.total;
            },
            sisa_plafon: function(){
              return this.do_sales_order.plafon - this.do_sales_order.plafon_yang_dipakai - this.do_sales_order.plafon_booking;
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
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
        <div class="container-fluid">
            <div class="row">
              <div class="col-sm-3">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label class="control-label">Nama Customer</label>
                      <div class="input-group">
                        <input id='nama_customer_filter' type="text" class="form-control" disabled>
                        <input id='id_customer_filter' type="hidden" disabled>
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_create_faktur_index'>
                            <i class="fa fa-search"></i>
                          </button>
                        </div>
                      </div>
                    </div>  
                  </div>
                </div>              
              </div>
            </div>
        </div>
        <?php $this->load->view('modal/h3_md_dealer_filter_create_faktur_index'); ?>
        <script>
          function pilih_dealer_filter_create_faktur_index(data, type) {
            if(type == 'add_filter'){
              $('#nama_customer_filter').val(data.nama_dealer);
              $('#id_customer_filter').val(data.id_dealer);
            }else if(type == 'reset_filter'){
              $('#nama_customer_filter').val('');
              $('#id_customer_filter').val('');
            }
            create_faktur.draw();
            // h3_md_dealer_filter_create_faktur_index_datatable.draw();
            drawing_dealer_filter_faktur();
          }
        </script>
        <table id="create_faktur" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tgl. Faktur</th>              
              <th>No. Faktur</th>              
              <th>Tipe Penjualan</th>              
              <th>Tanggal SO</th>              
              <th>Nomor SO</th>              
              <th>Tanggal DO</th>              
              <th>Nomor DO</th>
              <th>Nama Customer</th>
              <th>Kode Customer</th>
              <th>Alamat</th>
              <th>Total (Amount)</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function(){
        create_faktur = $('#create_faktur').DataTable({
          processing: true,
          searching: false,
          serverSide: true,
          scrollX: true,
          order: [],
          ajax: {
              url: "<?= base_url('api/md/h3/create_faktur') ?>",
              dataSrc: "data",
              type: "POST",
              data: function(d){
                d.id_customer_filter = $('#id_customer_filter').val();
                d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
              }
          },
          columns: [
              { data: 'index', searchable: false, orderable: false, width: '3%' },
              { 
                data: 'tgl_faktur', 
                width: '200px',
                render: function(data){
                  if(data != null) return moment(data).format('DD/MM/YYYY HH:mm');
                  return '-';
                }
              }, 
              { data: 'no_faktur', width: '200px' }, 
              { data: 'produk' }, 
              { data: 'tanggal_so' }, 
              { data: 'id_sales_order', width: '200px' }, 
              { data: 'tanggal_do' }, 
              { 
                data: 'id_do_sales_order',
                width: '200px',
                render: function(data, type, row){
                  if(row.sudah_revisi == 1){
                    data += '-REV';
                  }
                  return data;
                }
              }, 
              { data: 'nama_dealer', width: '250px' }, 
              { data: 'kode_dealer' }, 
              { data: 'alamat', width: '400px' }, 
              { data: 'amount' }, 
              { data: 'action', orderable: false, width: '3%', className: 'text-center' }
          ],
        });
      });
    </script>
    <?php endif; ?>
  </section>
</div>