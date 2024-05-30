<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
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
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      } ?>
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Purchase</label>
                    <div class="col-sm-4">
                      <input value="OTHER" type="text" class="form-control" disabled>
                    </div>
                    <div v-if='mode == "detail"'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Order</label>
                      <div class="col-sm-4">
                        <input :value='moment(purchase.tanggal_order).format("DD/MM/YYYY")' type="text" class="form-control" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div v-bind:class="{ 'has-error': error_exist('kategori_po') }" class="col-sm-4">
                    <select :disabled='mode != "insert"' class="form-control" v-model='purchase.kategori_po'>
                        <option value="">-Pilih-</option>
                        <option value="SIM Part">SIM Part</option>
                        <option value="Non SIM Part">Non SIM Part</option>
                      </select>
                      <small v-if="error_exist('kategori_po')" class="form-text text-danger">{{ get_error('kategori_po') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Produk</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='purchase.produk'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Acc</option>
                        <option value="Apparel">Apparel</option>
                        <option value="Tools">Tools</option>
                        <option value="Other">Other</option>
                      </select>
                      <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Total amount</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='total_amount_po_for_ach2' currency='Rp' separator='.' readonly/>
                    </div>
                  </div>
                  <div v-if='mode == "detail"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.periode' type="text" class="form-control" disabled>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.tanggal_selesai' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div class='form-group'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total Item Price</label>
                    <div class="col-sm-4">
                      <vue-numeric v-model='totalHargaNonFiltered2' thousand-separator='.' currency='Rp ' disabled class='form-control'></vue-numeric>
                    </div>
                  </div>
                  <div v-if='mode == "detail" || is_fix' class="form-group">
                    <div v-if='mode == "detail"'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.status' type="text" class="form-control" disabled>
                      </div>
                    </div>
                  </div>
                  <div v-if='mode == "detail" && purchase.status == "Rejected"' class='form-group'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.alasan_reject' type="text" class="form-control" disabled>
                    </div>   
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-2" v-if="mode=='insert'">
                      <button @click.prevent='<?= $form ?>' type="button" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                    <div class="col-sm-12 col-sm-offset-2" v-if="mode=='edit'">
                      <button @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <td width="3%">No. </td>
                        <td>
                          <span>Part Number</span>
                        </td>
                        <td>
                          <span>Part Deskripsi</span>
                        </td>
                        <td v-if='!is_fix && !is_reg' width='7%'>Qty Order</td>
                        <td width='10%' class="text-right">Harga</td>
                        <td width='10%' class="text-right">Sub total</td>
                        <td width='3%' v-if="mode!='detail' && !is_urg && !is_fix"></td>
                      </tr>
                      <tr v-if="filtered_parts.length > 0" v-for="(part, index) of filtered_parts" v-bind:class="{ 'text-red': melewati_maks_stok(part) }">
                        <td class="align-middle text-right">{{ index + 1 }}.</td>
                        <td class="align-middle">
                          <span>{{ part.id_part }}</span>
                        </td>
                        <td class="align-middle">
                          <span>{{ part.nama_part }}</span>
                        </td>
                        <td class='align-middle'>
                          <vue-numeric :read-only="mode=='detail' || is_urg" class="input-compact" thousand-separator="." v-model="part.kuantitas" :empty-value="1"  v-on:keyup.native="qty_order_change_handler" />
                        </td>

                        <td class="align-middle text-right">
                          <vue-numeric :read-only="mode=='detail'"  class="input-compact" thousand-separator="." v-model="part.harga_saat_dibeli" :empty-value="0"  v-on:keyup.native="" />
                        </td>

                        <td class="align-middle text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="subTotal2(part)" /> 
                        </td>
                        <td v-if="mode!='detail' && !is_urg && !is_fix && part.part_revisi_dari_md != 1" class="align-middle text-center">
                          <button class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                      <tr v-if="parts.length > 0 && is_other && is_nmd">
                        <td class="text-right" v-bind:colspan="item_colspan-2">Total</td>
                        <td class="text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalHargaTanpaPPN2" />
                        </td>
                      </tr>
                      <tr v-if="parts.length < 1">
                        <td v-bind:class='{ "bg-red": errors.parts != null }' v-bind:colspan="item_colspan" class="text-center text-muted">Belum ada part</td>
                      </tr>
                    </table>
                  </div>
                  <button v-if="( mode!='detail' && (!is_urg && !is_fix)) || (mode == 'edit' && is_hlo)" style="margin-top:15px" type="button" class="mt-3 pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-part"><i class="fa fa-plus"></i></button>
                  <?php $this->load->view('modal/part_purchase_order') ?>
                  <script>
                  function pilihPart(part) {
                      form_.parts.push(part);
                  }
                  </script>
                  <div class="box-footer">
                    <div v-if="mode=='detail'">
                    <?php if ($mode == 'detail') : ?>
                      <div class="col-sm-6 no-padding">
                        <a v-if='mode == "detail" && (purchase.status == "Draft" || purchase.status == "Rejected") && auth.can_update' :href="'dealer/h3_dealer_purchase_order_non_md/edit?id=' + purchase.po_id" class="btn btn-sm btn-warning btn-flat">Edit</a>
                      </div>
                      <div class="col-sm-6 no-padding text-right">
                          <button v-if='mode == "detail" && purchase.status == "Rejected" && auth.can_reopen' class="btn btn-flat btn-sm btn-success" @click.prevent='reopen_purchase'>Re-Open</button>
                          <button v-if='allowed_submit' class="btn btn-flat btn-sm btn-info" @click.prevent='update_status("Submitted")'>Submit</button>
                          <button v-if='mode == "detail" && purchase.status == "Draft" && auth.can_approval' class="btn btn-flat btn-sm btn-success" @click.prevent='update_status("Approved")'>Approve</button>
                          <button v-if='mode == "detail" && purchase.status == "Draft" && auth.can_cancel' class="btn btn-flat btn-sm btn-danger" @click.prevent='update_status("Canceled")'>Cancel</button>
                          <button v-if='mode == "detail" && purchase.status != "Processed by MD" && purchase.status != "Rejected" && purchase.status != "Closed" && purchase.status != "Submit & Approve Revisi" && auth.can_reject' class="btn btn-flat btn-sm btn-danger" type='button' data-toggle='modal' data-target='#reject_purchase'>Reject</button>
                          <a v-if='mode == "detail" && (purchase.po_type == "REG" || purchase.po_type == "OTHER" || purchase.po_type == "FIX") && auth.can_print ' :href="'h23_api/cetak_po_reg?id=' + purchase.po_id" class="btn btn-sm btn-primary btn-flat">Cetak PO</a>
                          <?php $this->load->view('modal/alasan_reject_purchase_order') ?>
                      </div>
                    <?php endif; ?>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        form_ = new Vue({
          el: '#form_',
          data: {
            auth: <?= json_encode(get_user('h3_dealer_purchase_order_non_md')) ?>, 
            mode: '<?= $mode ?>',
            search_filter: '',
            sim_part_filter: false,
            errors: {},
            loading: false,
            checked_part_group: [],
            <?php if ($mode == 'detail' or $mode == 'edit' or $mode == 'revisi_po') : ?>
            purchase: <?= json_encode($purchase_order) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else : ?>
            purchase : {
              kategori_po: '',
              id_salesman: '',
              po_type: 'OTHER',
              pesan_untuk_bulan: '',
              dokumen_nrfs_id: null,
              order_to: null,
              id_booking: null,
              nama_dealer_terdekat: '',
              produk: '',
              total_pembelian: 0,
              total_amount: 0,
              total_amount_po: 0,
              ach: 0,
              po_nmd:1
            },
            parts: [],
            <?php endif; ?>
            dealer: <?= json_encode($dealer) ?>,
            part_group: <?= json_encode($part_group); ?>,
          },
          mounted: function(){
           
          },
          methods: {
            <?= $form ?>: function(){
              this.loading = true;
              this.errors = {};
              
              post = {};
              if(this.mode == 'edit'){
                post.po_id = this.purchase.po_id;
              }

              post = this.purchase;
              post.total_amount = this.totalHarga;
              post.batas_waktu = $('#batas_waktu').val();
              post.ach = this.ach;

              subTotal_fn = this.subTotal2;
              post.parts = _.chain(this.parts).filter(function(p){
                return p.kuantitas > 0;
              })
              .map(function(p){
                part = _.omit(p, [
                  'id_dealer', 'id', 'nama_part', 
                  'kelompok_vendor', 'min_sales', 'safety_stock', 
                  'kelompok_part', 'min_stok', 'maks_stok', 
                  'qty_part', 'dokumen_nrfs_id', 'id_booking', 
                  'harga_md_dealer', 'sim_part', 'id_dealer'
                ]);
                part.tot_harga_part = subTotal_fn(part);
                return part;
              }).value();

              axios.post('dealer/h3_dealer_purchase_order_non_md/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                data = res.data;

                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                if (data.error_type == 'validation_error') {
                  form_.errors = data.errors;
                  toastr.error(data.message);
                } else {
                  toastr.error(data.message);
                }

                form_.loading = false;
              });
            },
            update_status: function(status){
              post = {};
              post.status = status;
              post.po_id = this.purchase.po_id;

              this.loading = true;
              axios.post('dealer/h3_dealer_purchase_order_non_md/update_status', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_purchase_order_non_md/detail?id=' + res.data.po_id;
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ form_.loading = false; });
            },
            reopen_purchase: function(){
              post = {};
              post.po_id = this.purchase.po_id;

              this.loading = true;
              axios.post('dealer/h3_dealer_purchase_order_non_md/reopen_purchase', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_purchase_order_non_md/detail?id=' + res.data.po_id;
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ form_.loading = false; })
            },
            reject_purchase: function(){
              post = {};
              post.po_id = this.purchase.po_id;
              post.alasan_reject = $('#alasan_reject').val();
              post.status = 'Rejected';

              this.loading = true;
              axios.post('dealer/h3_dealer_purchase_order_non_md/reject', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_purchase_order_non_md/detail?id=' + res.data.po_id;
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ form_.loading = false; })
            },
            melewati_maks_stok: function(part){
              return false;
              return (Number(part.kuantitas) + Number(part.stock)) > part.maks_stok;
            },
            qty_order_change_handler: _.debounce(function(){
              // form_.get_diskon_parts();
            }, 500),
            harga_setelah_diskon: function(part){
              harga_setelah_diskon = part.harga_saat_dibeli;

              if(part.tipe_diskon == 'Rupiah'){
                harga_setelah_diskon = part.harga_saat_dibeli - part.diskon_value;
              }else if(part.tipe_diskon == 'Persen'){
                diskon = (part.diskon_value/100) * part.harga_saat_dibeli;
                harga_setelah_diskon = part.harga_saat_dibeli - diskon;
              }

              if(part.tipe_diskon_campaign == 'Rupiah'){
                harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
              }else if(part.tipe_diskon_campaign == 'Persen'){
                diskon = (part.diskon_value_campaign/100) * harga_setelah_diskon;
                harga_setelah_diskon = harga_setelah_diskon - diskon;
              }

              return harga_setelah_diskon;
            },
            // subTotal: function(part) {
            //   harga_setelah_diskon = this.harga_setelah_diskon(part);

            //   return part.kuantitas * harga_setelah_diskon;
            // },
            subTotal2: function(part) {
              return part.kuantitas * part.harga_saat_dibeli;
            },
            hapusPart: function(index) {
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
            allowed_submit: function(){
              if(this.is_hlo && this.purchase.no_inv_uang_jaminan == null){
                return false;
              }

              return this.mode == "detail" && this.purchase.status == "Approved" && this.auth.can_submit;
            },
            is_urg: function(){
              return this.purchase.po_type == 'URG';
            },
            is_fix: function(){
              return this.purchase.po_type == 'FIX';
            },
            is_hlo: function(){
              return this.purchase.po_type == 'HLO';
            },
            is_other: function(){
              return this.purchase.po_type == 'OTHER';
            },
            is_reg: function(){
              return this.purchase.po_type == 'REG';
            },
            is_md: function(){
              return this.purchase.po_nmd == '0';
            },
            is_nmd: function(){
              return this.purchase.po_nmd == '1';
            },
            item_colspan: function(){
              if(!this.is_hlo || !this.is_fix || !this.is_reg){
                return 7;
              }
            },
            // totalHargaTanpaPPN: function() {
            //   total = 0;
            //   for (part of this.filtered_parts) {
            //     total += this.subTotal(part);
            //   }
            //   return total;
            // },
            totalHargaTanpaPPN2: function() {
              total = 0;
              for (part of this.filtered_parts) {
                total += this.subTotal2(part);
              }
              return total;
            },
            // totalPPN: function (){
            //   return 0;
            //   return (10/100) * this.totalHargaTanpaPPN;
            // },
            totalPPN2: function (){
              return 0;
              return (10/100) * this.totalHargaTanpaPPN2;
            },
            // totalHarga: function() {
            //   return this.totalHargaTanpaPPN + this.totalPPN;
            // },
            totalHarga2: function() {
              return this.totalHargaTanpaPPN2 + this.totalPPN2;
            },
            totalHargaNonFiltered2: function(){
              total = 0;
              for (part of this.parts) {
                total += this.subTotal2(part);
              }
              return total;
              return total + ((10/100) * total);
            },
            // total_amount_po_for_ach: function(){
            //   return parseFloat(this.totalHarga) + parseFloat(this.purchase.total_amount_po);
            // },            
            total_amount_po_for_ach2: function(){
              return parseFloat(this.totalHarga2) + parseFloat(this.purchase.total_amount_po);
            },
            // ach: function(){
            //   if(this.purchase.target_pembelian != 0){
            //     ach = this.total_amount_po_for_ach / this.purchase.target_pembelian;
            //     ach = ach > 1 ? 1 : ach;
            //     ach = ach * 100;
            //     return ach;
            //   }else{
            //     return 0;
            //   }
            // },
            order_to_empty: function(){
              return this.purchase.order_to == null || this.purchase.order_to == 0;
            },
            filtered_parts: function(){
              checked_part_group = this.checked_part_group
              filtered =  _.filter(this.parts, function(part){
                if(checked_part_group.length > 0){
                  return _.includes(checked_part_group, part.kelompok_part);
                }else{
                  return true;
                }
              });

              search_filter = this.search_filter;
              filtered = _.filter(filtered, function(part){
                return part.id_part.toLowerCase().includes(search_filter.toLowerCase())
              });

              if(this.sim_part_filter){
                filtered = _.filter(filtered, function(part){
                  return part.sim_part > 0;
                });
              }

              return filtered;
            }
          },
          watch: {
            'purchase.po_type': function(val) {
              this.parts = [];
              $('#batas_waktu').datepicker('remove');
              datatable_part.draw();
            },
            'purchase.produk': function(data){
              datatable_part.draw();
            },
            'purchase.kategori_po': function(data){
              datatable_part.draw();
            },
            'purchase.pesan_untuk_bulan': function(data){
              // this.ambil_data_target_pembelian();
            },
            totalHargaTanpaPPN: function(data){
              // this.ambil_data_target_pembelian();
            },
            parts: {
              deep: true,
              handler: function(){
                datatable_part.draw();
              }
            }
          }
        });

        $(document).ready(function(){
          if(form_.mode == 'detail' || form_.mode == 'edit'){
            $('#batas_waktu').datepicker({ format: 'yyyy-mm-dd' });
            $('#batas_waktu').val(form_.purchase.batas_waktu);
          }
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if(can_access('h3_dealer_purchase_order', 'can_insert')): ?>
            <a href="dealer/<?= $isi ?>/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <?php endif; ?>
          </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="" class="control-label">No PO</label>
                  <input type="text" class="form-control" name="filter_search_po_id" id="filter_search_po_id" placeholder="Cari No PO">
                </div>
              </div> 
              <div class="col-sm-2">
                <div class="form-group"> 
                    <label class="control-label">&nbsp</label>
                      <br>
                      <button type="button" class="btn btn-primary btn-sm" id="btn-cari_filter"><span class="fa fa-search"></span></button>
                </div>
              </div> 
            </div>
            <div class="row">
              <p style="padding-left: 15px;"><b>Search</b></p>
            </div>
            <div class="row">
              
            </div>
          </div>
          <table id="purchase_order" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>PO ID</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
           <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
          <script>
            $(document).ready(function() {
                      purchase_order = $('#purchase_order').DataTable({
                      processing: true,
                      serverSide: true,
                      ordering: false,
                      searching:false,
                      order: [],
                      language: {
                        infoFiltered: "",
                        processing: "Processing",
                       },
                      lengthMenu: [10, 25, 50, 75, 100 ],
                      scrollX: true,
                      ajax: {
                          url: "<?= base_url('api/dealer/purchase_order_non_md') ?>",
                          dataSrc: "data",
                          type: "POST",
                          data: function(data) {
                              data.filter_search_po_id=$('#filter_search_po_id').val();
                          }
                      },
                      createdRow: function(row, data, index) {
                          $('td', row).addClass('align-middle');
                      },
                      columns: [
                        { data: 'index', orderable: false, width: '3%' }, 
                        { data: 'aksi' }, 
                      ],
                      
                  });
            });
                
        $('#btn-cari').click(function(){
            purchase_order.draw();
        });
        
        $('#btn-cari_filter').click(function(e){
                            e.preventDefault();
                           purchase_order.draw();
                         });
            
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>