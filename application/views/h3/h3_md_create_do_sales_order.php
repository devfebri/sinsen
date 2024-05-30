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
  <h1>
    <?= $title; ?>    
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" @keydown.enter="$event.preventDefault()">
              <div v-if='!sesuai_dengan_pembagian_paket_bundling && mode != "detail"' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Kuantitas Supply tidak sesuai dengan pembagian paket bundling.
              </div>
              <div v-if='sales_order.ada_wilayah_penagihan == 0 && mode != "detail"' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Nama Customer ini belum di daftarkan di menu wilayah penagihan collector, harap menghubungi Finance H3.
              </div>
              <div class="box-body">    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='sales_order.id_sales_order'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='sales_order.nama_dealer'>                    
                  </div> 
                </div>    
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" :value='moment(sales_order.tanggal_order).format("DD/MM/YYYY")'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='sales_order.alamat'>                    
                  </div> 
                </div>      
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='sales_order.po_type'>                    
                  </div>                                
                  <label class="col-sm-2 control-label">Kategori PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='sales_order.kategori_po'>                    
                  </div> 
                </div>            
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Produk</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='sales_order.produk'>                    
                  </div>                   
                  <label class="col-sm-2 control-label">Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" v-model='plafon_awal' currency='Rp' separator='.'></vue-numeric>
                  </div> 
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label">Filter Part Number</label>
                  <div class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" readonly class="form-control" v-model='filter_part_number'>                    
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_parts_filter_create_do_sales_order'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_parts_filter_create_do_sales_order'); ?>                         
                  <script>
                    function pilih_parts_filter_create_do_sales_order(data, type){
                      if(type == 'hapus_selected'){
                        app.filter_part_number = '';
                      }else{
                        app.filter_part_number = data.id_part;
                      }
                      h3_md_parts_filter_create_do_sales_order_datatable.draw();
                    }
                  </script>
                  <label class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" v-model='sales_order.plafon_booking' currency='Rp' separator='.'></vue-numeric>
                  </div> 
                </div>              
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Filter Kelompok Part</label>
                  <div class="col-sm-4">                    
                    <div class="input-group">
                      <input type="text" readonly class="form-control" v-model='filter_kelompok_part'>                    
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_filter_create_do_sales_order'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>                                
                  <?php $this->load->view('modal/h3_md_kelompok_part_filter_create_do_sales_order'); ?>                         
                  <script>
                    function pilih_kelompok_part_filter_create_do_sales_order(data, type){
                      if(type == 'hapus_selected'){
                        app.filter_kelompok_part = '';
                      }else{
                        app.filter_kelompok_part = data.kelompok_part;
                      }
                      h3_md_kelompok_part_filter_create_do_sales_order_datatable.draw();
                    }
                  </script>
                  <label class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" v-model='sisa_plafon' currency='Rp' separator='.'></vue-numeric>
                  </div> 
                </div>     
                <div class="form-group">
                  <label for="" class="control-label col-sm-2 no-padding">Nilai SO</label>
                  <div class="col-sm-4">
                    <div class="row">
                      <div class="col-sm-4">
                        <vue-numeric class='form-control' currency='Rp' separator='.' v-model='jumlah_amount_sales_order' read-only></vue-numeric>
                      </div>
                      <div class="col-sm-4">
                        <vue-numeric class='form-control' currency='Item' currency-symbol-position='suffix' separator='.' v-model='jumlah_item_sales_order' read-only></vue-numeric>
                      </div>
                      <div class="col-sm-4">
                        <vue-numeric class='form-control' currency='Pcs' currency-symbol-position='suffix' separator='.' v-model='jumlah_pcs_sales_order' read-only></vue-numeric>
                      </div>
                    </div>
                  </div>
                  <label for="" class="control-label col-sm-2">Jenis Pembayaran</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='sales_order.jenis_pembayaran'>
                  </div>
                </div>
                <div class="form-group">
                  <label for="" class="control-label col-sm-2 no-padding">Nilai DO</label>
                  <div class="col-sm-4">
                    <div class="row">
                      <div class="col-sm-4">
                        <vue-numeric class='form-control' currency='Rp' separator='.' v-model='jumlah_amount_delivery_order' read-only></vue-numeric>
                      </div>
                      <div class="col-sm-4">
                        <vue-numeric class='form-control' currency='Item' currency-symbol-position='suffix' separator='.' v-model='jumlah_item_delivery_order' read-only></vue-numeric>
                      </div>
                      <div class="col-sm-4">
                        <vue-numeric class='form-control' currency='Pcs' currency-symbol-position='suffix' separator='.' v-model='jumlah_pcs_delivery_order' read-only></vue-numeric>
                      </div>
                    </div>
                  </div>
                  <label for="" class="control-label col-sm-2">Status</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly v-model='sales_order.status'>
                  </div>
                </div>
                <div class="container-fluid">
                  <div v-if='sales_order.gimmick == 1 && parts_selisih_qty_supply_dengan_order.length > 0' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> Untuk Sales Order gimmick hanya boleh dilakukan pemenuhan 1 kali (penuh). Berikut adalah kode part yang pemenuhan nya selisih dengan permintaan order, antara lain:
                    <ul>
                      <li v-for='part in parts_selisih_qty_supply_dengan_order'>{{ part.id_part }} - Permintaan Order {{ part.qty_so }} Pcs, tetapi yang akan dipenuhi {{ part.qty_supply }} Pcs</li>
                    </ul>
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
                          <th>HET</th>              
                          <th>Disc. Dealer</th>
                          <th>Disc. Campaign</th>
                          <th class='text-right'>Qty On Hand</th>
                          <th class='text-right'>Qty AVS</th>
                          <th class='text-right'>Qty SO</th>
                          <th class='text-right'>Qty Suggest</th>
                          <th class='text-right' width='8%'>Qty Supply</th>
                          <th width="10%" class="text-right">Nilai (Amount)</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in filtered_parts"> 
                          <td class="align-middle">{{ index + 1 }}.</td>
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle" v-if='kategori_kpb'>{{ part.id_tipe_kendaraan }}</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only='true' class="form-control" separator='.' currency='Rp' v-model='part.harga_jual'/>
                          </td>                       
                          <td class="align-middle">
                            <vue-numeric thousand-separator='.' v-bind:precision="2" read-only v-model="part.diskon_satuan_dealer" :currency='get_currency_symbol(part.tipe_diskon_satuan_dealer)' :currency-symbol-position='get_currency_position(part.tipe_diskon_satuan_dealer)'/>
                          </td> 
                          <td class="align-middle">
                            <vue-numeric thousand-separator='.' v-bind:precision="2" read-only v-model="part.diskon_campaign" :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)'/>
                          </td> 
                          <td class="align-middle text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_on_hand"/>
                          </td>
                          <td class="align-middle text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_avs"/>
                          </td>
                          <td class="align-middle text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_so"/>
                          </td>
                          <td class="align-middle text-right">
                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_suggest"/>
                          </td> 
                          <!-- <td class="align-middle">
                            <vue-numeric :readonly='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_supply" :min='min_input_qty_supply(part)' :max='max_input_qty_supply(part)'/>
                          </td>    -->
                          <td v-if='sales_order.status=="Barang Bagi"' class="align-middle">
                            <vue-numeric :readonly='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_supply" :min='min_input_qty_supply(part)' :max='max_input_qty_supply(part)'/>
                          </td>   
                          <td v-else class="align-middle">
                            <vue-numeric :readonly='mode == "detail"' class="form-control" separator="." :empty-value="1" v-model="part.qty_supply" :min='min_input_qty_supply(part)' :max='max_input_qty_supply(part)'/>
                          </td>   
                          <td width="8%" class="align-middle text-right">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="amount(part)" />
                          </td>                       
                        </tr>
                        <tr v-if='this.parts.length < 1'>
                          <td colspan='12' class='text-center'>Tidak ada data.</td>
                        </tr>
                        <tr v-if="filtered_parts.length > 0">
                          <td class="text-right" :colspan="table_colspan">Sub Total</td>
                          <td class="text-right">
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="filtered_parts.length > 0">
                          <td class="text-right" :colspan="table_colspan">Diskon Addtional</td>
                          <td class="text-right">
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="sales_order.diskon_additional" currency='Rp'/>
                          </td>
                        </tr>
                        <tr v-if="filtered_parts.length > 0">
                          <td class="text-right" :colspan="table_colspan">Total</td>
                          <td class="text-right">
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency='Rp'/>
                          </td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <div v-if='get_do_user.do > 10' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> DO tidak dapat di Create, karena masih terdapat {{get_do_user.do}} yang belum diselesaikan untuk salesman ini. 
                </div>
                <div v-if='get_do_user.do >= 3' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> DO tidak dapat di Create, karena masih terdapat {{get_do_waktu.do}} yang belum diselesaikan untuk salesman ini yang lebih dari 3 hari.
                  </div>
                <div class="col-sm-6 no-padding">
                  <button v-if='mode == "edit" || mode == "detail" ' :disabled='!sesuai_dengan_pembagian_paket_bundling || !allow_to_create_do || this.parts.length < 1 || sales_order.ada_wilayah_penagihan == 0 || loading || tidak_ada_part_untuk_do' @click.prevent='create_do' type="submit" class="btn btn-primary btn-sm btn-flat">Create</button>                  
                  <a v-if='mode == "detail" && sales_order.status != "Closed"' :href="'h3/h3_md_create_do_sales_order/edit?id=' + sales_order.id_sales_order" class="btn btn-sm btn-warning btn-flat">Edit</a>
                </div>
                <div class="col-sm-6 text-right no-padding">
                  <a onclick='return confirm("Apakah anda yakin ingin menghilangkan SO ini dari menu Create DO?")' :href="'h3/h3_md_create_do_sales_order/delete_from_create_do_sales_order?id=' + sales_order.id_sales_order" class="btn btn-sm btn-flat btn-danger" style='margin-top: 5px;'>Del</a>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script>
      Vue.use(VueNumeric.default);
      app = new Vue({
          el: '#app',
          data: {
            loading: false,
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            sales_order: <?= json_encode($sales_order) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else: ?>
            sales_order: {},
            parts: [],
            <?php endif; ?>
            filter_part_number: '',
            filter_kelompok_part: '',
            jumlah_amount_delivery_order: 0,
            get_do_user: 0,
            get_do_waktu: 0,
            jumlah_item_delivery_order: 0,
            jumlah_pcs_delivery_order: 0,
            jumlah_amount_sales_order: 0,
            jumlah_item_sales_order: 0,
            jumlah_pcs_sales_order: 0,
            paket_bundling: [],
          },
          mounted: function(){
            this.get_create_do_user();
            this.get_create_do_waktu();
            this.get_info_order();
            if(this.sales_order.kategori_po == 'Bundling H1'){
              this.get_paket_bundling(this.sales_order.id_paket_bundling);
            }
          },
          methods: {
            create_do: _.throttle(function(){
              this.loading = true;
              post = _.pick(this.sales_order, ['id_sales_order', 'diskon_additional', 'gimmick', 'kategori_po', 'produk', 'po_type']);
              post.total = this.total;
              post.sub_total = this.sub_total;
              post.back_order = this.back_order;
              post.parts = _.chain(this.parts)
              .filter(function(part){
                return part.qty_supply > 0;
              })
              .map(function(part){
                keys = ['id_part_int','id_part', 'harga_jual', 'qty_supply', 'tipe_diskon_satuan_dealer', 'diskon_satuan_dealer', 'tipe_diskon_campaign', 'diskon_campaign', 'qty_dus', 'id_campaign_diskon'];

                if(app.kategori_kpb){
                  keys.push('id_tipe_kendaraan');
                }

                return _.pick(part, keys);
              }).value();
              
              axios.post("h3/h3_md_create_do_sales_order/create_do", Qs.stringify(post))
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){ 
                data = err.response.data;
                if(data.error_type == 'parts_for_supply_not_available'){
                  toastr.error(data.message);
                }else if(data.message != null){
                  toastr.error(data.message);
                }else{
                  toastr.error(err);
                }
                app.loading = false;
              });
            }, 500),
            harga_setelah_diskon: function(part){
              harga_setelah_diskon = part.harga_jual;
              harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, harga_setelah_diskon);

              if(part.jenis_diskon_campaign == 'Additional'){
                harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, harga_setelah_diskon);
              }else if(part.jenis_diskon_campaign == 'Non Additional'){
                harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_jual);
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
            get_info_order: function(){
              this.loading = true;
              axios.get('h3/h3_md_create_do_sales_order/get_info_order', {
                params: {
                  id_sales_order: this.sales_order.id_sales_order
                }
              })
              .then(function(res){
                data = res.data;
                app.jumlah_amount_delivery_order = data.jumlah_amount_delivery_order;
                app.jumlah_item_delivery_order = data.jumlah_item_delivery_order;
                app.jumlah_pcs_delivery_order = data.jumlah_pcs_delivery_order;
                app.jumlah_amount_sales_order = data.jumlah_amount_sales_order;
                app.jumlah_item_sales_order = data.jumlah_item_sales_order;
                app.jumlah_pcs_sales_order = data.jumlah_pcs_sales_order;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){ app.loading = false; });
            },
            max_input_qty_supply: function(part){
              // if(this.sales_order.created_by_md == 0 && this.sales_order.po_type == 'HLO'){
              //   if(part.qty_on_hand >= part.qty_so){
              //     return part.qty_so;
              //   }
              // }
              // if(this.sales_order.po_type == 'URG'){
              //   if(part.qty_on_hand >= part.qty_so){
              //     return part.qty_so;
              //   }
              // }
              // if(this.sales_order.created_by_md != 0 && (this.sales_order.po_type != 'HLO' ||this.sales_order.po_type != 'URG')){
              //   if(part.qty_avs > part.qty_so){
              //     return part.qty_so;
              //   }
              // }
              if(part.qty_avs > part.qty_so){
                return part.qty_so;
              }
              return part.qty_avs;
            },
            min_input_qty_supply: function(part){
              // const qtyBooking = this.get_qty_booking(part);
              // console.log(qtyBooking);
              if(part.part_booking !== '' || part.part_booking !== null ||part.part_booking !== undefined ){
                return part.qty_order;
              }
              
              if(this.sales_order.kategori_po == 'KPB'){
                return part.qty_order;
              }

              if(this.sales_order.gimmick == 1){
                return part.qty_order;
              }
              return 0;
            },
            get_qty_booking: function(part){
              axios.get('h3/h3_md_create_do_sales_order/check_qty_booking', {
                params: {
                  part: part,
                  id_sales_order : this.sales_order.id_sales_order
                }
              })
              .then(function(res){
                this.total = res.data.kuantitas;
                return res.data.kuantitas;
              })
              .catch(function(err){
                toastr.error(err);
                return 0;
              })
              .then(function(){
                app.loading = false;
              })
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
            get_currency_position: function(tipe_diskon){
              if(tipe_diskon == 'Rupiah'){
                return 'prefix';
              }else if(tipe_diskon == 'Persen'){
                return 'suffix';
              }
              return;
            },
            get_currency_symbol: function(tipe_diskon){
              if(tipe_diskon == 'Rupiah'){
                return 'Rp';
              }else if(tipe_diskon == 'Persen'){
                return '%';
              }
              return;
            },
            get_create_do_user: function(id_sales_order){
              this.loading = true;
              axios.get('h3/h3_md_create_do_sales_order/get_create_do_user', {
                params: {
                  id_sales_order: this.sales_order.id_sales_order
                }
              })
              .then(function(res){
                app.get_do_user = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              })
            },
            get_create_do_waktu: function(id_sales_order){
              this.loading = true;
              axios.get('h3/h3_md_create_do_sales_order/get_create_do_waktu', {
                params: {
                  id_sales_order: this.sales_order.id_sales_order
                }
              })
              .then(function(res){
                app.get_do_waktu = res.data;
              })
              .catch(function(err){
                toastr.error(err);
              })
              .then(function(){
                app.loading = false;
              })
            },
          },
          computed: {
            kategori_kpb: function(){
              return this.sales_order.kategori_po == 'KPB';
            },
            sub_total: function(){
              amount_fn = this.amount;
              return _.chain(this.parts)
              .sumBy(function(data){
                return amount_fn(data);
              })
              .value();
            },
            total: function(){
              return this.sub_total - this.sales_order.diskon_additional;
            },
            plafon_awal: function(){
              return this.sales_order.plafon - this.sales_order.plafon_yang_dipakai;
            },
            sisa_plafon: function(){
              return this.plafon_awal - this.sales_order.plafon_booking;
            },
            parts_selisih_qty_supply_dengan_order: function(){
              return _.chain(this.parts)
              .filter(function(part){
                return part.qty_supply != part.qty_so;
              })
              .value();
            },
            allow_to_create_do: function() {
              if(this.sales_order.status == 'Back Order' || this.sales_order.status == 'Closed'){
                  toastr.error('Tidak dapat Create DO!. Status SO Back Order atau Closed');
                return false;
              }

              if(this.sales_order.gimmick == 1 && this.parts_selisih_qty_supply_dengan_order.length > 0){
                return false;
              }

              if(this.sales_order.jenis_pembayaran == 'Tunai') return true;

              if(this.sisa_plafon < this.total){
                  toastr.error('Tidak dapat Create DO!. Sisa Plafon tidak mencukupi!');
                return false;
              }

              if(this.sales_order.ada_wilayah_penagihan == 0){
                  toastr.error('Tidak dapat Create DO!. Wilayah Penagihan belum disetting');
                return false;
              }

              if(this.get_do_user.do > 100){
                return false;
              }

              if(this.get_do_waktu.do >= 100){
                return false;
              }

              if(this.sales_order.po_type != 'HLO' && this.sales_order.kategori_po != 'KPB' && this.total <500){
                toastr.error('Tidak dapat memproses dibawah 500 ribu untuk non HLO');
                return false;
              }

              return true;
            },
            back_order: function(){
              for(part of this.parts){
                if(
                  parseInt(part.qty_so) > parseInt(part.qty_supply)
                ){
                  return 1;
                }
              }
              return 0;
            },
            filtered_parts: function(){
              filter_part_number = this.filter_part_number;
              filter_kelompok_part = this.filter_kelompok_part;

              return _.chain(this.parts)
              .filter(function(part){
                if(filter_part_number != ''){
                  return filter_part_number == part.id_part;
                }
                return true;
              })
              .filter(function(part){
                if(filter_kelompok_part != ''){
                  return filter_kelompok_part == part.kelompok_part;
                }
                return true;
              }).value();
            },
            parts_yang_tidak_bisa_dipenuhi: function(){
              return _.chain(this.parts)
              .groupBy('id_part')
              .map(function(grouped, index){
                return {
                  id_part: index,
                  qty_order: _.sumBy(grouped, function(group){
                    return group.qty_order;
                  }),
                  qty_supply: _.sumBy(grouped, function(group){
                    return group.qty_supply;
                  }),
                  qty_avs: grouped[0].qty_avs
                };
              })
              .value();
            },
            table_colspan: function(){
              colspan = 11;

              if(this.kategori_kpb){
                colspan += 1;
              }
              
              return colspan;
            },
            sesuai_dengan_pembagian_paket_bundling: function(){
              if(this.paket_bundling.length < 1) return [];

              perhitungan_kelipatan =  _.chain(this.parts)
              .map(function(part){
                index = _.findIndex(app.paket_bundling, function(row){
                  return part.id_part == row.id_part;
                });

                if(index != -1){
                  part.kelipatan = part.qty_supply / app.paket_bundling[index].qty_part;
                }else{
                  part.kelipatan = 0;
                }

                return _.pick(part, [
                  'id_part', 'qty_supply', 'kelipatan'
                ]);
              })
              .value();

              kelipatan = perhitungan_kelipatan[0].kelipatan;
              
              mempunyai_kelipatan_sama = _.every(perhitungan_kelipatan, ['kelipatan', kelipatan]);

              return mempunyai_kelipatan_sama;
            },
            tidak_ada_part_untuk_do: function(){
              jumlah_part_untuk_do = _.chain(this.parts)
              .sumBy(function(part){
                return parseInt(part.qty_supply);
              })
              .value();

              return jumlah_part_untuk_do == 0;
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
      <div class="container-fluid">
          <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6" id='customer_filter'>
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Customer</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " Customer"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_create_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_dealer_filter_create_do_sales_order_index'); ?>         
                <script>
                    customer_filter = new Vue({
                        el: '#customer_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            create_do_sales_order.draw();
                          }
                        }
                    });

                    $("#h3_md_dealer_filter_create_do_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_dealer = target.attr('data-id-dealer');

                      if(target.is(':checked')){
                        customer_filter.filters.push(id_dealer);
                      }else{
                        index_id_dealer = _.indexOf(customer_filter.filters, id_dealer);
                        customer_filter.filters.splice(index_id_dealer, 1);
                      }
                      h3_md_dealer_filter_create_do_sales_order_index_datatable.draw();
                    });
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
                      create_do_sales_order.draw();
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
                  }, function(start, end, label) {
                    $('#periode_sales_filter_start').val(start.format('YYYY-MM-DD'));
                    $('#periode_sales_filter_end').val(end.format('YYYY-MM-DD'));
                    create_do_sales_order.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#periode_sales_filter_start').val('');
                    $('#periode_sales_filter_end').val('');
                    create_do_sales_order.draw();
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
                            create_do_sales_order.draw();
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
                      <option value="">All</option>
                      <option value="SIM Part">SIM Part</option>
                      <option value="Non SIM Part">Non SIM Part</option>
                    </select>
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#kategori_sales_filter').on("change", function(){
                      create_do_sales_order.draw();
                    });
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Tipe Produk</label>
                  <div class="col-sm-8">
                    <select id="tipe_produk_filter" class="form-control">
                      <option value="">All</option>
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
                      create_do_sales_order.draw();
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
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_jenis_dealer_filter_create_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_jenis_dealer_filter_create_do_sales_order_index'); ?>
                <script>
                    jenis_dealer_filter = new Vue({
                        el: '#jenis_dealer_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            create_do_sales_order.draw();
                          }
                        }
                    });
                </script>
              </div>
              <div id='kabupaten_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kabupaten</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input :value='filters.length + " kabupaten"' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kabupaten_filter_create_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_kabupaten_filter_create_do_sales_order_index'); ?>
                <script>
                    kabupaten_filter = new Vue({
                        el: '#kabupaten_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            create_do_sales_order.draw();
                          }
                        }
                    });

                    $("#h3_md_kabupaten_filter_create_do_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_kabupaten = target.attr('data-id-kabupaten');

                      if(target.is(':checked')){
                        kabupaten_filter.filters.push(id_kabupaten);
                      }else{
                        index_kabupaten = _.indexOf(kabupaten_filter.filters, id_kabupaten);
                        kabupaten_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_kabupaten_filter_create_do_sales_order_index_datatable.draw();
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
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_create_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_create_do_sales_order_index'); ?>
                <script>
                    kelompok_part_filter = new Vue({
                        el: '#kelompok_part_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            create_do_sales_order.draw();
                          }
                        }
                    });

                    $("#h3_md_kelompok_part_filter_create_do_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_kelompok_part = target.attr('data-id-kelompok-part');

                      if(target.is(':checked')){
                        kelompok_part_filter.filters.push(id_kelompok_part);
                      }else{
                        index_kabupaten = _.indexOf(kelompok_part_filter.filters, id_kelompok_part);
                        kelompok_part_filter.filters.splice(index_kabupaten, 1);
                      }
                      h3_md_kelompok_part_filter_create_do_sales_order_index_datatable.draw();
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
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_salesman_filter_create_do_sales_order_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_salesman_filter_create_do_sales_order_index'); ?>
                <script>
                    salesman_filter = new Vue({
                        el: '#salesman_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            create_do_sales_order.draw();
                          }
                        }
                    });

                    $("#h3_md_salesman_filter_create_do_sales_order_index").on('change',"input[type='checkbox']",function(e){
                      target = $(e.target);
                      id_salesman = target.attr('data-id-salesman');

                      if(target.is(':checked')){
                        salesman_filter.filters.push(id_salesman);
                      }else{
                        index_salesman = _.indexOf(salesman_filter.filters, id_salesman);
                        salesman_filter.filters.splice(index_salesman, 1);
                      }
                      h3_md_salesman_filter_create_do_sales_order_index_datatable.draw();
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
                            create_do_sales_order.draw();
                          }
                        }
                    })
                </script>
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
        <table id="create_do_sales_order_index" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal SO</th>              
              <th>Nomor SO</th>              
              <th>Nomor DO</th>              
              <th>Tipe Penjualan</th>              
              <th>Produk</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Alamat Customer</th>              
              <th>Kota / Kabupaten</th>              
              <th>Nilai SO</th>              
              <th>Nilai DO</th>              
              <th>Sisa DO</th>              
              <th>S/R</th>              
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function(){
        create_do_sales_order = $('#create_do_sales_order_index').DataTable({
          processing: true,
          serverSide: true,
          searching: false,
          scrollX: true,
          order: [],
          ajax: {
              url: "<?= base_url('api/md/h3/create_do_sales_order') ?>",
              dataSrc: function(json){
                filter_data = {};
                filter_data.customer_filter = customer_filter.filters;
                filter_data.no_so_filter = $('#no_so_filter').val();
                filter_data.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                filter_data.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                filter_data.kategori_sales_filter = $('#kategori_sales_filter').val();
                filter_data.tipe_produk_filter = $('#tipe_produk_filter').val();
                filter_data.status_filter = status_filter.filters;
                filter_data.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                filter_data.jenis_dealer_filter = jenis_dealer_filter.filters;
                filter_data.kabupaten_filter = kabupaten_filter.filters;
                filter_data.kelompok_part_filter = kelompok_part_filter.filters;
                filter_data.salesman_filter = salesman_filter.filters;

                axios.post('<?= base_url('api/md/h3/create_do_sales_order/get_sales_order_info/Parts') ?>', Qs.stringify(filter_data))
                .then(function(res){
                  info_penjualan.total_amount_parts = res.data.amount;
                  info_penjualan.qty_parts = res.data.kuantitas_part;
                })
                .catch(function(err){
                  toastr.error(err);
                });

                axios.post('<?= base_url('api/md/h3/create_do_sales_order/get_sales_order_info/Oil') ?>', Qs.stringify(filter_data))
                .then(function(res){
                  info_penjualan.total_amount_oil = res.data.amount;
                  info_penjualan.qty_oil = res.data.kuantitas_part;
                })
                .catch(function(err){
                  toastr.error(err);
                });

                axios.post('<?= base_url('api/md/h3/create_do_sales_order/get_sales_order_info/Acc') ?>', Qs.stringify(filter_data))
                .then(function(res){
                  info_penjualan.total_amount_acc = res.data.amount;
                  info_penjualan.qty_acc = res.data.kuantitas_part;
                })
                .catch(function(err){
                  toastr.error(err);
                });

                return json.data;
              },
              type: "POST",
              data: function(d){
                d.id_customer_filter = customer_filter.filters;
                d.no_so_filter = $('#no_so_filter').val();
                d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                d.kategori_sales_filter = $('#kategori_sales_filter').val();
                d.tipe_produk_filter = $('#tipe_produk_filter').val();
                d.status_filter = status_filter.filters;
                d.tipe_penjualan_filter = tipe_penjualan_filter.filters;
                d.jenis_dealer_filter = jenis_dealer_filter.filters;
                d.kabupaten_filter = kabupaten_filter.filters;
                d.kelompok_part_filter = kelompok_part_filter.filters;
                d.salesman_filter = salesman_filter.filters;
              }
          },
          columns: [
              { data: 'index', width: '3%', orderable: false }, 
              { data: 'tanggal_so' }, 
              { data: 'id_sales_order', width: '200px' }, 
              { data: 'no_do', orderable: false, className: 'text-center' }, 
              { data: 'po_type' }, 
              { data: 'produk' }, 
              { data: 'kode_dealer_md', width: '100px' }, 
              { data: 'nama_dealer', width: '100px' }, 
              { data: 'alamat' }, 
              { data: 'kabupaten' }, 
              { 
                data: 'total_amount', 
                width: '100px',
                className: 'text-right',
                render: function(data){
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              }, 
              { 
                data: 'nilai_do', 
                width: '100px',
                className: 'text-right',
                render: function(data){
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              }, 
              { 
                data: 'sisa_do', 
                width: '100px',
                className: 'text-right',
                render: function(data){
                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                }
              }, 
              { 
                data: 'service_rate',
                className: 'text-right', 
                render: function(data){
                  return accounting.formatMoney(data, "", 2, ".", ",") + "%";
                }
              }, 
              { data: 'status', orderable: false, width: '100px' }, 
              { data: 'action', orderable:false, width: '3%' }
          ],
        });
      });
    </script>
    <?php $this->load->view('modal/h3_md_open_view_do_create_do_sales_order'); ?>
    <script>
      h3_md_open_view_do_create_do_sales_order  = new Vue({
        el: '#h3_md_open_view_do_create_do_sales_order',
        data: {
          delivery_orders: []
        },
        methods: {
          get_list_delivery_orders: function(id_sales_order){
            axios.get('h3/h3_md_create_do_sales_order/get_list_delivery_orders', {
              params: {
                id_sales_order: id_sales_order
              }
            })
            .then(function(res){
              h3_md_open_view_do_create_do_sales_order.delivery_orders = res.data;
            })
            .catch(function(err){
              toastr.error(err);
            });
          }
        }
      });

      function open_view_do(id_sales_order) {
        h3_md_open_view_do_create_do_sales_order.get_list_delivery_orders(id_sales_order);
        $('#h3_md_open_view_do_create_do_sales_order').modal('show');
      }
    </script>
    <?php endif; ?>
  </section>
</div>